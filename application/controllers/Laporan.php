<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Silakan login terlebih dahulu.');
            redirect('auth');
        }

        $this->load->model('Laporan_model');
        $this->load->model('Periode_model');
        $this->load->model('Harian_model');
    }

    public function index()
    {
        $all_periods = $this->Periode_model->get_all();
        $selected_periode_id = $this->input->get('periode_id');

        if (empty($selected_periode_id) && !empty($all_periods)) {
            $selected_periode_id = $all_periods[0]->id;
        }

        $detail_laporan = null;
        $chart_data = null;

        if (!empty($selected_periode_id)) {
            $detail_laporan = $this->Laporan_model->get_detail_laporan($selected_periode_id);
            $chart_data = $this->Harian_model->get_chart_data($selected_periode_id);
        }

        $data = array(
            'title' => 'Laporan Produksi & Kinerja Kandang',
            'active_menu' => 'laporan',
            'all_periods' => $all_periods,
            'selected_periode_id' => $selected_periode_id,
            'laporan' => $detail_laporan,
            'chart_data' => $chart_data,
            'content_view' => 'laporan/index'
        );
        $this->load->view('layouts/wrapper', $data);
    }

    public function detail($periode_id)
    {
        redirect('laporan?periode_id=' . $periode_id);
    }

    public function cetak($periode_id)
    {
        $detail_laporan = $this->Laporan_model->get_detail_laporan($periode_id);
        if (!$detail_laporan) {
            $this->session->set_flashdata('error', 'Data periode laporan tidak ditemukan.');
            redirect('laporan');
        }

        $data = array(
            'title' => 'Cetak Laporan Produksi - ' . $detail_laporan['summary']->periode->kode_periode,
            'laporan' => $detail_laporan
        );
        $this->load->view('laporan/cetak', $data);
    }

    public function update_bobot_panen()
    {
        $periode_id = (int)$this->input->post('periode_id');
        $total_bobot_panen_kg = (float)$this->input->post('total_bobot_panen_kg');
        $tgl_selesai_panen = $this->input->post('tgl_selesai_panen');
        $status = $this->input->post('status') ? $this->input->post('status') : 'selesai';
        $harga_kontrak_per_kg = (float)$this->input->post('harga_kontrak_per_kg');
        $catatan = $this->input->post('catatan', TRUE);

        $data_update = array(
            'total_bobot_panen_kg' => $total_bobot_panen_kg,
            'tgl_selesai_panen' => !empty($tgl_selesai_panen) ? $tgl_selesai_panen : date('Y-m-d'),
            'status' => $status,
            'harga_kontrak_per_kg' => $harga_kontrak_per_kg,
            'catatan' => $catatan
        );

        if ($this->Periode_model->close_periode($periode_id, $data_update)) {
            $this->session->set_flashdata('success', 'Data bobot panen berhasil diperbarui! FCR Final & Indeks Prestasi (IP) otomatis terhitung.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data panen.');
        }
        redirect('laporan?periode_id=' . $periode_id);
    }
}
