<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
        $this->load->view('auth/login');
    }

    public function login()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        $this->form_validation->set_rules('username', 'Username', 'required|trim', array(
            'required' => 'Username wajib diisi.'
        ));
        $this->form_validation->set_rules('password', 'Password', 'required', array(
            'required' => 'Password wajib diisi.'
        ));

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/login');
        } else {
            $username = $this->input->post('username', TRUE);
            $password = $this->input->post('password');

            $user = $this->User_model->login($username, $password);

            if ($user) {
                $session_data = array(
                    'user_id'   => $user->id,
                    'name'      => $user->name,
                    'username'  => $user->username,
                    'role'      => $user->role,
                    'phone'     => $user->phone,
                    'logged_in' => TRUE
                );
                $this->session->set_userdata($session_data);

                $role_name = ($user->role == 'pemilik') ? 'Pemilik / Manajemen' : 'Admin / ABK';
                $this->session->set_flashdata('success', 'Selamat datang, ' . $user->name . ' (' . $role_name . ')');
                redirect('dashboard');
            } else {
                $this->session->set_flashdata('error', 'Username atau password tidak sesuai atau akun non-aktif!');
                redirect('auth');
            }
        }
    }

    public function lupa_password()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        $this->form_validation->set_rules('username', 'Username', 'required|trim', array(
            'required' => 'Username wajib diisi.'
        ));
        $this->form_validation->set_rules('phone', 'No. WhatsApp / HP', 'required|trim', array(
            'required' => 'Nomor WhatsApp / HP wajib diisi.'
        ));
        $this->form_validation->set_rules('password', 'Password Baru', 'required|min_length[4]', array(
            'required' => 'Password baru wajib diisi.',
            'min_length' => 'Password baru minimal 4 karakter.'
        ));
        $this->form_validation->set_rules('passconf', 'Konfirmasi Password Baru', 'required|matches[password]', array(
            'required' => 'Konfirmasi password baru wajib diisi.',
            'matches' => 'Konfirmasi password tidak cocok dengan password baru.'
        ));

        if ($this->form_validation->run() == FALSE) {
            $data = array(
                'pemilik' => $this->User_model->get_pemilik()
            );
            $this->load->view('auth/lupa_password', $data);
        } else {
            $username = strtolower(trim($this->input->post('username', TRUE)));
            $phone_input = preg_replace('/[^0-9]/', '', $this->input->post('phone', TRUE));
            $new_password = $this->input->post('password');

            $user = $this->User_model->get_by_username($username);

            if (!$user) {
                $this->session->set_flashdata('error', 'Username tidak ditemukan!');
                redirect('auth/lupa_password');
                return;
            }

            if ($user->status !== 'aktif') {
                $this->session->set_flashdata('error', 'Akun sedang berstatus non-aktif. Silakan hubungi Pemilik Peternakan!');
                redirect('auth/lupa_password');
                return;
            }

            // Normalisasi dan verifikasi nomor HP terdaftar
            $user_phone = preg_replace('/[^0-9]/', '', $user->phone);

            $matched = false;
            if (!empty($user_phone) && !empty($phone_input)) {
                if ($user_phone === $phone_input) {
                    $matched = true;
                } elseif (ltrim($user_phone, '0') === ltrim($phone_input, '0')) {
                    $matched = true;
                } elseif (preg_replace('/^62/', '0', $user_phone) === preg_replace('/^62/', '0', $phone_input)) {
                    $matched = true;
                }
            }

            if (!$matched) {
                $this->session->set_flashdata('error', 'Nomor WhatsApp / HP tidak sesuai dengan data terdaftar akun ini!');
                redirect('auth/lupa_password');
                return;
            }

            // Hash password baru dengan bcrypt dan perbarui ke database
            $update_data = array(
                'password' => password_hash($new_password, PASSWORD_BCRYPT)
            );

            if ($this->User_model->update($user->id, $update_data)) {
                $this->session->set_flashdata('success', 'Password untuk akun <strong>' . htmlspecialchars($user->username) . '</strong> berhasil direset! Silakan masuk dengan password baru.');
                redirect('auth');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui password. Silakan coba kembali.');
                redirect('auth/lupa_password');
            }
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth');
    }
}
