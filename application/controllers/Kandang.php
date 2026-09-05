<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kandang extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Silakan login terlebih dahulu.');
            redirect('auth');
        }

        $this->load->model('Kandang_model');
    }

    public function index()
    {
        $data = array(
            'title' => 'Master Data Kandang',
            'active_menu' => 'kandang',
            'kandang_list' => $this->Kandang_model->get_all(),
            'content_view' => 'kandang/index'
        );
        $this->load->view('layouts/wrapper', $data);
    }

    public function tambah()
    {
        // Hanya ABK/Admin yang bisa tambah data master kandang
        $this->form_validation->set_rules('kode_kandang', 'Kode Kandang', 'required|trim|is_unique[kandang.kode_kandang]');
        $this->form_validation->set_rules('nama_kandang', 'Nama Kandang', 'required|trim');
        $this->form_validation->set_rules('kapasitas', 'Kapasitas', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('tipe_kandang', 'Tipe Kandang', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data = array(
                'title' => 'Tambah Data Kandang Baru',
                'active_menu' => 'kandang',
                'content_view' => 'kandang/form'
            );
            $this->load->view('layouts/wrapper', $data);
        } else {
            $insert_data = array(
                'kode_kandang' => strtoupper($this->input->post('kode_kandang', TRUE)),
                'nama_kandang' => $this->input->post('nama_kandang', TRUE),
                'kapasitas'    => (int)$this->input->post('kapasitas'),
                'tipe_kandang' => $this->input->post('tipe_kandang', TRUE),
                'lokasi'       => $this->input->post('lokasi', TRUE),
                'status'       => $this->input->post('status', TRUE) ? $this->input->post('status', TRUE) : 'tersedia'
            );

            if ($this->Kandang_model->insert($insert_data)) {
                $this->session->set_flashdata('success', 'Data kandang berhasil ditambahkan!');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan data kandang.');
            }
            redirect('kandang');
        }
    }

    public function edit($id)
    {
        $kandang = $this->Kandang_model->get_by_id($id);
        if (!$kandang) {
            $this->session->set_flashdata('error', 'Data kandang tidak ditemukan.');
            redirect('kandang');
        }

        $this->form_validation->set_rules('nama_kandang', 'Nama Kandang', 'required|trim');
        $this->form_validation->set_rules('kapasitas', 'Kapasitas', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('tipe_kandang', 'Tipe Kandang', 'required');

        if ($this->input->post('kode_kandang') !== $kandang->kode_kandang) {
            $this->form_validation->set_rules('kode_kandang', 'Kode Kandang', 'required|trim|is_unique[kandang.kode_kandang]');
        }

        if ($this->form_validation->run() == FALSE) {
            $data = array(
                'title' => 'Edit Data Kandang',
                'active_menu' => 'kandang',
                'kandang' => $kandang,
                'content_view' => 'kandang/form'
            );
            $this->load->view('layouts/wrapper', $data);
        } else {
            $update_data = array(
                'kode_kandang' => strtoupper($this->input->post('kode_kandang', TRUE)),
                'nama_kandang' => $this->input->post('nama_kandang', TRUE),
                'kapasitas'    => (int)$this->input->post('kapasitas'),
                'tipe_kandang' => $this->input->post('tipe_kandang', TRUE),
                'lokasi'       => $this->input->post('lokasi', TRUE),
                'status'       => $this->input->post('status', TRUE)
            );

            if ($this->Kandang_model->update($id, $update_data)) {
                $this->session->set_flashdata('success', 'Data kandang berhasil diperbarui!');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui data kandang.');
            }
            redirect('kandang');
        }
    }

    public function hapus($id)
    {
        $kandang = $this->Kandang_model->get_by_id($id);
        if (!$kandang) {
            $this->session->set_flashdata('error', 'Data kandang tidak ditemukan.');
            redirect('kandang');
        }

        if ($this->Kandang_model->delete($id)) {
            $this->session->set_flashdata('success', 'Data kandang berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data kandang.');
        }
        redirect('kandang');
    }
}
