<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Harian extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Silakan login terlebih dahulu.');
            redirect('auth');
        }

        $this->load->model('Harian_model');
        $this->load->model('Periode_model');
        $this->load->model('Kandang_model');
    }

    public function index()
    {
        $active_periods = $this->Periode_model->get_all();
        $selected_periode_id = $this->input->get('periode_id');

        if (empty($selected_periode_id) && !empty($active_periods)) {
            $selected_periode_id = $active_periods[0]->id;
        }

        $summary = null;
        $harian_list = array();

        if (!empty($selected_periode_id)) {
            $summary = $this->Periode_model->get_summary($selected_periode_id);
            $harian_list = $this->Harian_model->get_by_periode($selected_periode_id);
        }

        $data = array(
            'title' => 'Riwayat Input Harian Peternakan',
            'active_menu' => 'harian',
            'active_periods' => $active_periods,
            'selected_periode_id' => $selected_periode_id,
            'summary' => $summary,
            'harian_list' => $harian_list,
            'content_view' => 'harian/index'
        );
        $this->load->view('layouts/wrapper', $data);
    }

    public function input()
    {
        // Hanya ABK / Admin yang menginput harian
        $active_periods = $this->Periode_model->get_active();
        if (empty($active_periods)) {
            $this->session->set_flashdata('error', 'Tidak ada periode panen yang aktif. Silakan buat periode baru terlebih dahulu.');
            redirect('periode/tambah');
        }

        $default_periode_id = $this->input->get('periode_id');
        if (empty($default_periode_id) && !empty($active_periods)) {
            $default_periode_id = $active_periods[0]->id;
        }

        $this->form_validation->set_rules('periode_id', 'Periode Panen', 'required|numeric');
        $this->form_validation->set_rules('tgl_input', 'Tanggal Laporan', 'required');
        $this->form_validation->set_rules('jumlah_mati', 'Jumlah Ayam Mati', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('pakan_kg', 'Jumlah Pakan (Kg)', 'required|numeric|greater_than_equal_to[0]');

        if ($this->form_validation->run() == FALSE) {
            $selected_periode = $this->Periode_model->get_by_id($default_periode_id);
            $today = date('Y-m-d');
            $calculated_age = $selected_periode ? $this->Harian_model->calculate_day_number($default_periode_id, $today) : 1;

            $data = array(
                'title' => 'Form Input Harian Ayam Broiler',
                'active_menu' => 'harian_input',
                'active_periods' => $active_periods,
                'default_periode_id' => $default_periode_id,
                'selected_periode' => $selected_periode,
                'today' => $today,
                'calculated_age' => $calculated_age,
                'content_view' => 'harian/input'
            );
            $this->load->view('layouts/wrapper', $data);
        } else {
            $periode_id = (int)$this->input->post('periode_id');
            $periode = $this->Periode_model->get_by_id($periode_id);
            $tgl_input = $this->input->post('tgl_input', TRUE);

            // Cek apakah tanggal input ini sudah diisi sebelumnya
            $existing = $this->Harian_model->get_today_entry($periode_id, $tgl_input);
            if ($existing) {
                $this->session->set_flashdata('error', 'Data harian untuk tanggal ' . date('d/m/Y', strtotime($tgl_input)) . ' pada batch ini sudah pernah diinput! Silakan edit data di tabel riwayat jika ingin mengubah.');
                redirect('harian?periode_id=' . $periode_id);
                return;
            }

            // Hitung hari ke-X otomatis
            $hari_ke = $this->Harian_model->calculate_day_number($periode_id, $tgl_input);
            if ($this->input->post('hari_ke') && (int)$this->input->post('hari_ke') > 0) {
                $hari_ke = (int)$this->input->post('hari_ke');
            }

            $pakan_kg = (float)$this->input->post('pakan_kg');
            $pakan_karung = (float)$this->input->post('pakan_karung');
            if ($pakan_karung <= 0 && $pakan_kg > 0) {
                $pakan_karung = round($pakan_kg / 50, 2);
            }

            $insert_data = array(
                'periode_id'        => $periode_id,
                'kandang_id'        => $periode->kandang_id,
                'user_id'           => $this->session->userdata('user_id'),
                'tgl_input'         => $tgl_input,
                'hari_ke'           => $hari_ke,
                'jumlah_mati'       => (int)$this->input->post('jumlah_mati'),
                'penyebab_kematian' => $this->input->post('penyebab_kematian', TRUE) ? $this->input->post('penyebab_kematian', TRUE) : 'Normal',
                'jenis_pakan'       => $this->input->post('jenis_pakan', TRUE),
                'pakan_kg'          => $pakan_kg,
                'pakan_karung'      => $pakan_karung,
                'obat_vitamin'      => $this->input->post('obat_vitamin', TRUE),
                'dosis_obat'        => $this->input->post('dosis_obat', TRUE),
                'cara_pemberian'    => $this->input->post('cara_pemberian', TRUE),
                'suhu_kandang'      => $this->input->post('suhu_kandang') ? (float)$this->input->post('suhu_kandang') : null,
                'kelembaban'        => $this->input->post('kelembaban') ? (float)$this->input->post('kelembaban') : null,
                'bobot_sampel_gram' => $this->input->post('bobot_sampel_gram') ? (float)$this->input->post('bobot_sampel_gram') : null,
                'catatan'           => $this->input->post('catatan', TRUE)
            );

            if ($this->Harian_model->insert($insert_data)) {
                $this->session->set_flashdata('success', 'Laporan harian Hari ke-' . $hari_ke . ' berhasil disimpan! Data kalkulasi sisa populasi dan pakan telah diperbarui otomatis.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menyimpan laporan harian.');
            }
            redirect('harian?periode_id=' . $periode_id);
        }
    }

    public function edit($id)
    {
        $log = $this->Harian_model->get_by_id($id);
        if (!$log) {
            $this->session->set_flashdata('error', 'Data input harian tidak ditemukan.');
            redirect('harian');
        }

        $this->form_validation->set_rules('jumlah_mati', 'Jumlah Ayam Mati', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('pakan_kg', 'Jumlah Pakan (Kg)', 'required|numeric|greater_than_equal_to[0]');

        if ($this->form_validation->run() == FALSE) {
            $data = array(
                'title' => 'Edit Laporan Harian Hari ke-' . $log->hari_ke,
                'active_menu' => 'harian',
                'log' => $log,
                'content_view' => 'harian/edit'
            );
            $this->load->view('layouts/wrapper', $data);
        } else {
            $pakan_kg = (float)$this->input->post('pakan_kg');
            $pakan_karung = (float)$this->input->post('pakan_karung');
            if ($pakan_karung <= 0 && $pakan_kg > 0) {
                $pakan_karung = round($pakan_kg / 50, 2);
            }

            $update_data = array(
                'jumlah_mati'       => (int)$this->input->post('jumlah_mati'),
                'penyebab_kematian' => $this->input->post('penyebab_kematian', TRUE),
                'jenis_pakan'       => $this->input->post('jenis_pakan', TRUE),
                'pakan_kg'          => $pakan_kg,
                'pakan_karung'      => $pakan_karung,
                'obat_vitamin'      => $this->input->post('obat_vitamin', TRUE),
                'dosis_obat'        => $this->input->post('dosis_obat', TRUE),
                'cara_pemberian'    => $this->input->post('cara_pemberian', TRUE),
                'suhu_kandang'      => $this->input->post('suhu_kandang') ? (float)$this->input->post('suhu_kandang') : null,
                'kelembaban'        => $this->input->post('kelembaban') ? (float)$this->input->post('kelembaban') : null,
                'bobot_sampel_gram' => $this->input->post('bobot_sampel_gram') ? (float)$this->input->post('bobot_sampel_gram') : null,
                'catatan'           => $this->input->post('catatan', TRUE)
            );

            if ($this->Harian_model->update($id, $update_data)) {
                $this->session->set_flashdata('success', 'Data laporan harian Hari ke-' . $log->hari_ke . ' berhasil diperbarui!');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui data.');
            }
            redirect('harian?periode_id=' . $log->periode_id);
        }
    }

    public function hapus($id)
    {
        $log = $this->Harian_model->get_by_id($id);
        if (!$log) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('harian');
        }

        $periode_id = $log->periode_id;
        if ($this->Harian_model->delete($id)) {
            $this->session->set_flashdata('success', 'Data laporan harian berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data.');
        }
        redirect('harian?periode_id=' . $periode_id);
    }

    public function ajax_info()
    {
        $periode_id = (int)$this->input->get('periode_id');
        $tgl_input = $this->input->get('tgl_input') ? $this->input->get('tgl_input') : date('Y-m-d');

        $periode = $this->Periode_model->get_by_id($periode_id);
        if (!$periode) {
            echo json_encode(array('success' => false, 'message' => 'Periode tidak valid'));
            return;
        }

        $hari_ke = $this->Harian_model->calculate_day_number($periode_id, $tgl_input);
        $summary = $this->Periode_model->get_summary($periode_id);

        // Rekomendasi jenis pakan berdasarkan umur ayam
        $rekomendasi_pakan = 'Starter';
        if ($hari_ke >= 11 && $hari_ke <= 21) {
            $rekomendasi_pakan = 'Grower';
        } elseif ($hari_ke > 21) {
            $rekomendasi_pakan = 'Finisher';
        }

        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => true,
            'hari_ke' => $hari_ke,
            'kandang_nama' => $periode->nama_kandang . ' (' . $periode->kode_kandang . ')',
            'strain' => $periode->strain,
            'tgl_masuk_doc' => date('d-m-Y', strtotime($periode->tgl_masuk_doc)),
            'populasi_awal' => number_format($periode->populasi_awal, 0, ',', '.'),
            'sisa_populasi' => number_format($summary->sisa_populasi, 0, ',', '.'),
            'total_mati' => number_format($summary->total_mati, 0, ',', '.'),
            'total_pakan_kg' => number_format($summary->total_pakan_kg, 1, ',', '.'),
            'rekomendasi_pakan' => $rekomendasi_pakan
        ));
    }
}
