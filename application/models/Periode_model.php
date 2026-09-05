<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Periode_model extends CI_Model {

    public function get_all($status = null)
    {
        $this->db->select('periode.*, kandang.nama_kandang, kandang.kode_kandang, kandang.kapasitas, kandang.tipe_kandang,
            (SELECT COALESCE(SUM(jumlah_mati), 0) FROM input_harian WHERE input_harian.periode_id = periode.id) AS total_mati,
            (SELECT COALESCE(SUM(pakan_kg), 0) FROM input_harian WHERE input_harian.periode_id = periode.id) AS total_pakan_kg,
            (SELECT COALESCE(MAX(hari_ke), 0) FROM input_harian WHERE input_harian.periode_id = periode.id) AS hari_terakhir,
            (SELECT bobot_sampel_gram FROM input_harian WHERE input_harian.periode_id = periode.id AND bobot_sampel_gram > 0 ORDER BY hari_ke DESC LIMIT 1) AS bobot_terakhir_gram
        ');
        $this->db->from('periode');
        $this->db->join('kandang', 'kandang.id = periode.kandang_id', 'left');
        
        if ($status !== null) {
            $this->db->where('periode.status', $status);
        }
        
        $this->db->order_by('periode.status', 'ASC'); // 'aktif' first
        $this->db->order_by('periode.tgl_masuk_doc', 'DESC');
        return $this->db->get()->result();
    }

    public function get_active()
    {
        return $this->get_all('aktif');
    }

    public function get_by_id($id)
    {
        $this->db->select('periode.*, kandang.nama_kandang, kandang.kode_kandang, kandang.kapasitas, kandang.tipe_kandang, kandang.lokasi');
        $this->db->from('periode');
        $this->db->join('kandang', 'kandang.id = periode.kandang_id', 'left');
        $this->db->where('periode.id', $id);
        return $this->db->get()->row();
    }

    public function insert($data)
    {
        $this->db->trans_start();
        $this->db->insert('periode', $data);
        $periode_id = $this->db->insert_id();

        // Update status kandang menjadi 'terisi' jika periode aktif
        if (isset($data['status']) && $data['status'] === 'aktif') {
            $this->db->where('id', $data['kandang_id']);
            $this->db->update('kandang', array('status' => 'terisi'));
        }

        $this->db->trans_complete();
        return $this->db->trans_status() ? $periode_id : false;
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update('periode', $data);
    }

    public function close_periode($id, $data_panen)
    {
        $this->db->trans_start();

        $periode = $this->get_by_id($id);
        if ($periode) {
            $update_data = array(
                'status' => 'selesai',
                'tgl_selesai_panen' => isset($data_panen['tgl_selesai_panen']) ? $data_panen['tgl_selesai_panen'] : date('Y-m-d'),
                'total_bobot_panen_kg' => isset($data_panen['total_bobot_panen_kg']) ? $data_panen['total_bobot_panen_kg'] : 0,
                'harga_kontrak_per_kg' => isset($data_panen['harga_kontrak_per_kg']) ? $data_panen['harga_kontrak_per_kg'] : $periode->harga_kontrak_per_kg,
                'catatan' => isset($data_panen['catatan']) ? $data_panen['catatan'] : $periode->catatan
            );

            $this->db->where('id', $id)->update('periode', $update_data);

            // Cek apakah masih ada periode aktif lain di kandang ini
            $active_other = $this->db->get_where('periode', array('kandang_id' => $periode->kandang_id, 'status' => 'aktif', 'id !=' => $id))->num_rows();
            if ($active_other == 0) {
                $this->db->where('id', $periode->kandang_id)->update('kandang', array('status' => 'tersedia'));
            }
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function delete($id)
    {
        $this->db->trans_start();
        $periode = $this->get_by_id($id);
        if ($periode) {
            $this->db->where('id', $id)->delete('periode');
            
            // Periksa sisa periode aktif di kandang tersebut
            $active_count = $this->db->get_where('periode', array('kandang_id' => $periode->kandang_id, 'status' => 'aktif'))->num_rows();
            if ($active_count == 0) {
                $this->db->where('id', $periode->kandang_id)->update('kandang', array('status' => 'tersedia'));
            }
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function get_summary($periode_id)
    {
        $periode = $this->get_by_id($periode_id);
        if (!$periode) return null;

        $harian = $this->db->select('
            COUNT(*) as total_hari_input,
            COALESCE(SUM(jumlah_mati), 0) as total_mati,
            COALESCE(SUM(pakan_kg), 0) as total_pakan_kg,
            COALESCE(SUM(pakan_karung), 0) as total_pakan_karung,
            COALESCE(MAX(hari_ke), 0) as hari_terakhir
        ')->where('periode_id', $periode_id)->get('input_harian')->row();

        // Cari bobot sampel terakhir
        $last_sample = $this->db->where('periode_id', $periode_id)
                                ->where('bobot_sampel_gram >', 0)
                                ->order_by('hari_ke', 'DESC')
                                ->limit(1)
                                ->get('input_harian')->row();

        $populasi_awal = (int)$periode->populasi_awal;
        $total_mati = (int)$harian->total_mati;
        $sisa_populasi = max(0, $populasi_awal - $total_mati);
        $mortalitas_persen = $populasi_awal > 0 ? round(($total_mati / $populasi_awal) * 100, 2) : 0;
        $livability_persen = round(100 - $mortalitas_persen, 2);

        $total_pakan_kg = (float)$harian->total_pakan_kg;
        $bobot_terakhir_gram = $last_sample ? (float)$last_sample->bobot_sampel_gram : 0;

        // FCR Sementara = Total Pakan (kg) / Total Estimasi Bobot Hidup (kg)
        // Estimasi Bobot Hidup (kg) = (Sisa Populasi * Bobot Rata-rata (kg))
        $estimasi_total_bobot_kg = ($sisa_populasi * ($bobot_terakhir_gram / 1000));
        $fcr_sementara = ($estimasi_total_bobot_kg > 0) ? round($total_pakan_kg / $estimasi_total_bobot_kg, 2) : 0;

        // FCR Final jika sudah panen
        $fcr_final = 0;
        $ip_final = 0;
        if ($periode->status === 'selesai' && (float)$periode->total_bobot_panen_kg > 0) {
            $total_bobot_panen_kg = (float)$periode->total_bobot_panen_kg;
            $fcr_final = round($total_pakan_kg / $total_bobot_panen_kg, 2);
            
            // Indeks Prestasi (IP) = (Daya Hidup % x Rata-rata Bobot Panen (kg)) / (FCR x Umur Rata-rata Panen (hari)) x 100
            $rata_bobot_panen_kg = $sisa_populasi > 0 ? ($total_bobot_panen_kg / $sisa_populasi) : 0;
            $umur_panen = $harian->hari_terakhir > 0 ? $harian->hari_terakhir : $periode->target_panen_hari;
            
            if ($fcr_final > 0 && $umur_panen > 0) {
                $ip_final = round(($livability_persen * $rata_bobot_panen_kg * 100) / ($fcr_final * $umur_panen), 1);
            }
        }

        return (object) array(
            'periode' => $periode,
            'populasi_awal' => $populasi_awal,
            'total_mati' => $total_mati,
            'sisa_populasi' => $sisa_populasi,
            'mortalitas_persen' => $mortalitas_persen,
            'livability_persen' => $livability_persen,
            'total_pakan_kg' => $total_pakan_kg,
            'total_pakan_karung' => (float)$harian->total_pakan_karung,
            'hari_terakhir' => (int)$harian->hari_terakhir,
            'bobot_terakhir_gram' => $bobot_terakhir_gram,
            'estimasi_total_bobot_kg' => round($estimasi_total_bobot_kg, 2),
            'fcr_sementara' => $fcr_sementara,
            'fcr_final' => $fcr_final,
            'ip_final' => $ip_final
        );
    }
}
