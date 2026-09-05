<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Periode extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Silakan login terlebih dahulu.');
            redirect('auth');
        }

        $this->load->model('Periode_model');
        $this->load->model('Kandang_model');
        $this->load->model('Harian_model');
    }

    public function index()
    {
        $status = $this->input->get('status');
        $periode_list = $this->Periode_model->get_all($status);

        $data = array(
            'title' => 'Data Periode Panen (Batch)',
            'active_menu' => 'periode',
            'status_filter' => $status,
            'periode_list' => $periode_list,
            'content_view' => 'periode/index'
        );
        $this->load->view('layouts/wrapper', $data);
    }

    public function detail($id)
    {
        $summary = $this->Periode_model->get_summary($id);
        if (!$summary) {
            $this->session->set_flashdata('error', 'Data periode tidak ditemukan.');
            redirect('periode');
        }

        $daily_logs = $this->Harian_model->get_by_periode($id);
        $chart_data = $this->Harian_model->get_chart_data($id);

        $data = array(
            'title' => 'Detail Periode: ' . $summary->periode->kode_periode,
            'active_menu' => 'periode',
            'summary' => $summary,
            'daily_logs' => $daily_logs,
            'chart_data' => $chart_data,
            'content_view' => 'periode/detail'
        );
        $this->load->view('layouts/wrapper', $data);
    }

    public function tambah()
    {
        $this->form_validation->set_rules('kode_periode', 'Kode Periode', 'required|trim|is_unique[periode.kode_periode]');
        $this->form_validation->set_rules('kandang_id', 'Kandang', 'required|numeric');
        $this->form_validation->set_rules('tgl_masuk_doc', 'Tanggal Masuk DOC', 'required');
        $this->form_validation->set_rules('populasi_awal', 'Jumlah Populasi Awal (DOC)', 'required|numeric|greater_than[0]');

        if ($this->form_validation->run() == FALSE) {
            $kandang_list = $this->Kandang_model->get_all();

            $data = array(
                'title' => 'Tambah Periode / Batch Baru',
                'active_menu' => 'periode',
                'kandang_list' => $kandang_list,
                'content_view' => 'periode/form'
            );
            $this->load->view('layouts/wrapper', $data);
        } else {
            $insert_data = array(
                'kode_periode'            => strtoupper($this->input->post('kode_periode', TRUE)),
                'kandang_id'              => (int)$this->input->post('kandang_id'),
                'strain'                  => $this->input->post('strain', TRUE) ? $this->input->post('strain', TRUE) : 'Cobb 500',
                'tgl_masuk_doc'           => $this->input->post('tgl_masuk_doc', TRUE),
                'populasi_awal'           => (int)$this->input->post('populasi_awal'),
                'target_panen_hari'       => (int)$this->input->post('target_panen_hari') > 0 ? (int)$this->input->post('target_panen_hari') : 35,
                'target_bobot_panen_gram' => (int)$this->input->post('target_bobot_panen_gram') > 0 ? (int)$this->input->post('target_bobot_panen_gram') : 2000,
                'harga_kontrak_per_kg'    => (float)$this->input->post('harga_kontrak_per_kg') > 0 ? (float)$this->input->post('harga_kontrak_per_kg') : 21000,
                'status'                  => 'aktif',
                'catatan'                 => $this->input->post('catatan', TRUE)
            );

            if ($this->Periode_model->insert($insert_data)) {
                $this->session->set_flashdata('success', 'Periode baru berhasil dibuat dan status kandang telah aktif!');
                redirect('periode');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan data periode.');
                redirect('periode/tambah');
            }
        }
    }

    public function edit($id)
    {
        $periode = $this->Periode_model->get_by_id($id);
        if (!$periode) {
            $this->session->set_flashdata('error', 'Data periode tidak ditemukan.');
            redirect('periode');
        }

        $this->form_validation->set_rules('kandang_id', 'Kandang', 'required|numeric');
        $this->form_validation->set_rules('tgl_masuk_doc', 'Tanggal Masuk DOC', 'required');
        $this->form_validation->set_rules('populasi_awal', 'Jumlah Populasi Awal (DOC)', 'required|numeric|greater_than[0]');

        if ($this->input->post('kode_periode') !== $periode->kode_periode) {
            $this->form_validation->set_rules('kode_periode', 'Kode Periode', 'required|trim|is_unique[periode.kode_periode]');
        }

        if ($this->form_validation->run() == FALSE) {
            $kandang_list = $this->Kandang_model->get_all();

            $data = array(
                'title' => 'Edit Data Periode: ' . $periode->kode_periode,
                'active_menu' => 'periode',
                'periode' => $periode,
                'kandang_list' => $kandang_list,
                'content_view' => 'periode/form'
            );
            $this->load->view('layouts/wrapper', $data);
        } else {
            $update_data = array(
                'kode_periode'            => strtoupper($this->input->post('kode_periode', TRUE)),
                'kandang_id'              => (int)$this->input->post('kandang_id'),
                'strain'                  => $this->input->post('strain', TRUE),
                'tgl_masuk_doc'           => $this->input->post('tgl_masuk_doc', TRUE),
                'populasi_awal'           => (int)$this->input->post('populasi_awal'),
                'target_panen_hari'       => (int)$this->input->post('target_panen_hari'),
                'target_bobot_panen_gram' => (int)$this->input->post('target_bobot_panen_gram'),
                'harga_kontrak_per_kg'    => (float)$this->input->post('harga_kontrak_per_kg'),
                'catatan'                 => $this->input->post('catatan', TRUE)
            );

            if ($this->Periode_model->update($id, $update_data)) {
                $this->session->set_flashdata('success', 'Data periode berhasil diperbarui!');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui data periode.');
            }
            redirect('periode');
        }
    }

    public function tutup_panen($id)
    {
        $periode = $this->Periode_model->get_by_id($id);
        if (!$periode) {
            $this->session->set_flashdata('error', 'Data periode tidak ditemukan.');
            redirect('periode');
        }

        $this->form_validation->set_rules('total_bobot_panen_kg', 'Total Bobot Panen (Kg)', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('tgl_selesai_panen', 'Tanggal Selesai Panen', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors('', ''));
            redirect('periode/detail/' . $id);
        } else {
            $data_panen = array(
                'tgl_selesai_panen'    => $this->input->post('tgl_selesai_panen', TRUE),
                'total_bobot_panen_kg' => (float)$this->input->post('total_bobot_panen_kg'),
                'harga_kontrak_per_kg' => (float)$this->input->post('harga_kontrak_per_kg'),
                'catatan'              => $this->input->post('catatan', TRUE)
            );

            if ($this->Periode_model->close_periode($id, $data_panen)) {
                $this->session->set_flashdata('success', 'Periode berhasil ditutup / selesai panen! FCR Final & Indeks Prestasi (IP) telah dihitung.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menutup periode panen.');
            }
            redirect('laporan/detail/' . $id);
        }
    }

    public function hapus($id)
    {
        if ($this->Periode_model->delete($id)) {
            $this->session->set_flashdata('success', 'Data periode dan input harian terkait berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data periode.');
        }
        redirect('periode');
    }
}
