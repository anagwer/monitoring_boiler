<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengguna extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Silakan login terlebih dahulu.');
            redirect('auth');
        }

        $this->load->model('User_model');
    }

    public function index()
    {
        $data = array(
            'title' => 'Manajemen Pengguna (ABK & Pemilik)',
            'active_menu' => 'pengguna',
            'users' => $this->User_model->get_all(),
            'content_view' => 'pengguna/index'
        );
        $this->load->view('layouts/wrapper', $data);
    }

    public function tambah()
    {
        $this->form_validation->set_rules('name', 'Nama Lengkap', 'required|trim');
        $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[users.username]|min_length[3]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[4]');
        $this->form_validation->set_rules('role', 'Role Hak Akses', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data = array(
                'title' => 'Tambah Pengguna Baru',
                'active_menu' => 'pengguna',
                'content_view' => 'pengguna/form'
            );
            $this->load->view('layouts/wrapper', $data);
        } else {
            $insert_data = array(
                'name'     => $this->input->post('name', TRUE),
                'username' => strtolower($this->input->post('username', TRUE)),
                'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                'role'     => $this->input->post('role', TRUE),
                'phone'    => $this->input->post('phone', TRUE),
                'status'   => $this->input->post('status', TRUE) ? $this->input->post('status', TRUE) : 'aktif'
            );

            if ($this->User_model->insert($insert_data)) {
                $this->session->set_flashdata('success', 'Pengguna baru berhasil ditambahkan!');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan pengguna.');
            }
            redirect('pengguna');
        }
    }

    public function edit($id)
    {
        $user = $this->User_model->get_by_id($id);
        if (!$user) {
            $this->session->set_flashdata('error', 'Data pengguna tidak ditemukan.');
            redirect('pengguna');
            return;
        }

        // Proteksi: Akun Pemilik hanya dapat diedit oleh Pemilik itu sendiri
        if ($user->role === 'pemilik' && $this->session->userdata('role') !== 'pemilik') {
            $this->session->set_flashdata('error', 'Akses ditolak: Akun Pemilik dilindungi dan tidak dapat diubah oleh ABK atau Admin.');
            redirect('pengguna');
            return;
        }

        $this->form_validation->set_rules('name', 'Nama Lengkap', 'required|trim');
        $this->form_validation->set_rules('role', 'Role Hak Akses', 'required');

        if ($this->input->post('username') !== $user->username) {
            $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[users.username]|min_length[3]');
        }

        if ($this->form_validation->run() == FALSE) {
            $data = array(
                'title' => 'Edit Pengguna: ' . $user->name,
                'active_menu' => 'pengguna',
                'user' => $user,
                'content_view' => 'pengguna/form'
            );
            $this->load->view('layouts/wrapper', $data);
        } else {
            $update_data = array(
                'name'     => $this->input->post('name', TRUE),
                'username' => strtolower($this->input->post('username', TRUE)),
                'role'     => $this->input->post('role', TRUE),
                'phone'    => $this->input->post('phone', TRUE),
                'status'   => $this->input->post('status', TRUE)
            );

            // Jika password diisi, update password hash
            if ($this->input->post('password')) {
                $update_data['password'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
            }

            if ($this->User_model->update($id, $update_data)) {
                $this->session->set_flashdata('success', 'Data pengguna berhasil diperbarui!');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui data pengguna.');
            }
            redirect('pengguna');
        }
    }

    public function hapus($id)
    {
        // Jangan hapus akun yang sedang login
        if ($this->session->userdata('user_id') == $id) {
            $this->session->set_flashdata('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
            redirect('pengguna');
            return;
        }

        $user = $this->User_model->get_by_id($id);
        if (!$user) {
            $this->session->set_flashdata('error', 'Data pengguna tidak ditemukan.');
            redirect('pengguna');
            return;
        }

        // Proteksi: User pemilik tidak bisa dihapus oleh ABK atau Admin
        if ($user->role === 'pemilik' && $this->session->userdata('role') !== 'pemilik') {
            $this->session->set_flashdata('error', 'Akses ditolak: Akun Pemilik dilindungi dan tidak dapat dihapus oleh ABK atau Admin.');
            redirect('pengguna');
            return;
        }

        if ($this->User_model->delete($id)) {
            $this->session->set_flashdata('success', 'Data pengguna ' . htmlspecialchars($user->name) . ' berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus pengguna.');
        }
        redirect('pengguna');
    }
}
