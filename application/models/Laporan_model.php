<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_model extends CI_Model {

    public function get_pengaturan()
    {
        $data = $this->db->get_where('pengaturan', array('id' => 1))->row();
        if (!$data) {
            return (object) array(
                'nama_pt' => 'PT BTS (BERKAH TERNAK SEJAHTERA)',
                'nama_sistem' => 'Aplikasi Monitoring Peternakan Ayam Broiler',
                'alamat' => 'Kawasan Agribisnis Terpadu Blok C-12, Subang, Jawa Barat',
                'no_telp' => '(0260) 412389 / 0812-8888-9999',
                'email' => 'farm.support@ptbts-poultry.com',
                'standar_fcr' => 1.55,
                'toleransi_kematian_persen' => 4.00
            );
        }
        return $data;
    }

    public function update_pengaturan($data)
    {
        $check = $this->db->get_where('pengaturan', array('id' => 1))->row();
        if ($check) {
            $this->db->where('id', 1);
            return $this->db->update('pengaturan', $data);
        } else {
            $data['id'] = 1;
            return $this->db->insert('pengaturan', $data);
        }
    }

    public function get_detail_laporan($periode_id)
    {
        $this->load->model('Periode_model');
        $this->load->model('Harian_model');

        $summary = $this->Periode_model->get_summary($periode_id);
        if (!$summary) return null;

        $harian_list = $this->Harian_model->get_by_periode($periode_id);

        $populasi_awal = $summary->populasi_awal;
        $current_populasi = $populasi_awal;
        $cum_mati = 0;
        $cum_pakan_kg = 0;
        $cum_pakan_karung = 0;

        $rows = array();
        foreach ($harian_list as $h) {
            $mati_hari_ini = (int)$h->jumlah_mati;
            $cum_mati += $mati_hari_ini;
            $sisa_hidup = max(0, $populasi_awal - $cum_mati);

            $pakan_hari_ini_kg = (float)$h->pakan_kg;
            $pakan_hari_ini_karung = (float)$h->pakan_karung;
            $cum_pakan_kg += $pakan_hari_ini_kg;
            $cum_pakan_karung += $pakan_hari_ini_karung;

            $rows[] = (object) array(
                'id' => $h->id,
                'hari_ke' => $h->hari_ke,
                'tgl_input' => $h->tgl_input,
                'populasi_awal_hari' => $current_populasi,
                'jumlah_mati' => $mati_hari_ini,
                'penyebab_kematian' => $h->penyebab_kematian,
                'akumulasi_mati' => $cum_mati,
                'sisa_hidup' => $sisa_hidup,
                'jenis_pakan' => $h->jenis_pakan,
                'pakan_kg' => $pakan_hari_ini_kg,
                'pakan_karung' => $pakan_hari_ini_karung,
                'akumulasi_pakan_kg' => round($cum_pakan_kg, 2),
                'akumulasi_pakan_karung' => round($cum_pakan_karung, 2),
                'bobot_sampel_gram' => (float)$h->bobot_sampel_gram,
                'obat_vitamin' => $h->obat_vitamin,
                'dosis_obat' => $h->dosis_obat,
                'cara_pemberian' => $h->cara_pemberian,
                'suhu_kandang' => $h->suhu_kandang,
                'kelembaban' => $h->kelembaban,
                'catatan' => $h->catatan,
                'nama_abk' => $h->nama_abk
            );

            // update current populasi for next iteration
            $current_populasi = $sisa_hidup;
        }

        return array(
            'summary' => $summary,
            'pengaturan' => $this->get_pengaturan(),
            'rows' => $rows
        );
    }
}
