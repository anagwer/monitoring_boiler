<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Harian_model extends CI_Model {

    public function get_by_periode($periode_id)
    {
        $this->db->select('input_harian.*, users.name as nama_abk, kandang.nama_kandang, kandang.kode_kandang');
        $this->db->from('input_harian');
        $this->db->join('users', 'users.id = input_harian.user_id', 'left');
        $this->db->join('kandang', 'kandang.id = input_harian.kandang_id', 'left');
        $this->db->where('input_harian.periode_id', $periode_id);
        $this->db->order_by('input_harian.hari_ke', 'ASC');
        return $this->db->get()->result();
    }

    public function get_by_id($id)
    {
        $this->db->select('input_harian.*, periode.kode_periode, periode.tgl_masuk_doc, periode.populasi_awal, kandang.nama_kandang, kandang.kode_kandang');
        $this->db->from('input_harian');
        $this->db->join('periode', 'periode.id = input_harian.periode_id', 'left');
        $this->db->join('kandang', 'kandang.id = input_harian.kandang_id', 'left');
        $this->db->where('input_harian.id', $id);
        return $this->db->get()->row();
    }

    public function get_today_entry($periode_id, $date = null)
    {
        if ($date === null) {
            $date = date('Y-m-d');
        }
        $this->db->where('periode_id', $periode_id);
        $this->db->where('tgl_input', $date);
        return $this->db->get('input_harian')->row();
    }

    public function calculate_day_number($periode_id, $tgl_input)
    {
        $periode = $this->db->get_where('periode', array('id' => $periode_id))->row();
        if (!$periode) return 1;

        $tgl_doc = strtotime($periode->tgl_masuk_doc);
        $tgl_target = strtotime($tgl_input);
        
        $diff = floor(($tgl_target - $tgl_doc) / (60 * 60 * 24));
        // Hari masuk DOC adalah Hari ke-0 atau Hari ke-1 (standar industri: tgl_masuk = Hari 1 atau selisih + 1)
        return max(1, (int)$diff + 1);
    }

    public function insert($data)
    {
        // Konversi otomatis pakan_kg ke pakan_karung jika belum ada
        if (isset($data['pakan_kg']) && (!isset($data['pakan_karung']) || $data['pakan_karung'] == 0)) {
            $data['pakan_karung'] = round($data['pakan_kg'] / 50, 2);
        }
        if (isset($data['pakan_karung']) && (!isset($data['pakan_kg']) || $data['pakan_kg'] == 0)) {
            $data['pakan_kg'] = round($data['pakan_karung'] * 50, 2);
        }

        return $this->db->insert('input_harian', $data);
    }

    public function update($id, $data)
    {
        if (isset($data['pakan_kg']) && (!isset($data['pakan_karung']) || $data['pakan_karung'] == 0)) {
            $data['pakan_karung'] = round($data['pakan_kg'] / 50, 2);
        }
        if (isset($data['pakan_karung']) && (!isset($data['pakan_kg']) || $data['pakan_kg'] == 0)) {
            $data['pakan_kg'] = round($data['pakan_karung'] * 50, 2);
        }

        $this->db->where('id', $id);
        return $this->db->update('input_harian', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('input_harian');
    }

    public function get_chart_data($periode_id)
    {
        $rows = $this->get_by_periode($periode_id);
        $periode = $this->db->get_where('periode', array('id' => $periode_id))->row();
        $populasi_awal = $periode ? (int)$periode->populasi_awal : 10000;

        $labels = array();
        $mortalitas_harian = array();
        $mortalitas_kumulatif = array();
        $pakan_harian = array();
        $pakan_kumulatif = array();
        $bobot_aktual = array();
        $bobot_standar = array();
        $suhu_harian = array();
        $kelembaban_harian = array();
        $feed_intake_per_bird = array(); // gram / ekor / hari

        $cum_mati = 0;
        $cum_pakan = 0;
        $last_sample_bobot = 42; // DOC standar berat 42 gram
        $causes_map = array();

        foreach ($rows as $r) {
            $labels[] = 'H-' . $r->hari_ke;
            
            // Kematian
            $mati = (int)$r->jumlah_mati;
            $mortalitas_harian[] = $mati;
            $cum_mati += $mati;
            $mortalitas_kumulatif[] = $cum_mati;

            // Grouping penyebab kematian
            $penyebab = trim($r->penyebab_kematian);
            if (empty($penyebab) || $penyebab === '-') {
                $penyebab = 'Normal / Seleksi';
            }
            if (!isset($causes_map[$penyebab])) {
                $causes_map[$penyebab] = 0;
            }
            $causes_map[$penyebab] += $mati;

            // Sisa ayam pada hari ini
            $sisa_ayam_hari_ini = max(1, $populasi_awal - $cum_mati);

            // Pakan
            $pakan_kg_hari = (float)$r->pakan_kg;
            $pakan_harian[] = round($pakan_kg_hari, 1);
            $cum_pakan += $pakan_kg_hari;
            $pakan_kumulatif[] = round($cum_pakan, 1);

            // Intake pakan per ekor (gram)
            $intake_gram = ($sisa_ayam_hari_ini > 0) ? round(($pakan_kg_hari * 1000) / $sisa_ayam_hari_ini, 1) : 0;
            $feed_intake_per_bird[] = $intake_gram;

            // Bobot
            if ((float)$r->bobot_sampel_gram > 0) {
                $last_sample_bobot = (float)$r->bobot_sampel_gram;
            }
            $bobot_aktual[] = $last_sample_bobot;

            // Kurva Standar Cobb 500 / Ross 308 (perkiraan kurva harian gram)
            $h = (int)$r->hari_ke;
            $std_bobot = round(42 + (1.25 * pow($h, 2.05)));
            $bobot_standar[] = $std_bobot;

            // Suhu & Kelembaban
            $suhu_harian[] = (float)$r->suhu_kandang > 0 ? (float)$r->suhu_kandang : null;
            $kelembaban_harian[] = (float)$r->kelembaban > 0 ? (float)$r->kelembaban : null;
        }

        // Urutkan penyebab kematian terbanyak
        arsort($causes_map);
        $cause_labels = array_keys($causes_map);
        $cause_values = array_values($causes_map);

        return array(
            'labels' => $labels,
            'mortalitas_harian' => $mortalitas_harian,
            'mortalitas_kumulatif' => $mortalitas_kumulatif,
            'pakan_harian' => $pakan_harian,
            'pakan_kumulatif' => $pakan_kumulatif,
            'bobot_aktual' => $bobot_aktual,
            'bobot_standar' => $bobot_standar,
            'suhu_harian' => $suhu_harian,
            'kelembaban_harian' => $kelembaban_harian,
            'feed_intake_per_bird' => $feed_intake_per_bird,
            'cause_labels' => $cause_labels,
            'cause_values' => $cause_values,
            'total_mati' => $cum_mati,
            'total_pakan_kg' => round($cum_pakan, 1),
            'sisa_hidup' => max(0, $populasi_awal - $cum_mati)
        );
    }
}
