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

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth');
    }
}
