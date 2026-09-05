<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function login($username, $password)
    {
        $this->db->where('username', $username);
        $this->db->where('status', 'aktif');
        $user = $this->db->get('users')->row();

        if ($user) {
            // Verifikasi password bcrypt, md5, atau plain text
            if (password_verify($password, $user->password) || $user->password === md5($password) || $user->password === $password) {
                return $user;
            }
        }
        return false;
    }

    public function get_all()
    {
        $this->db->order_by('role', 'ASC');
        $this->db->order_by('name', 'ASC');
        return $this->db->get('users')->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('users', array('id' => $id))->row();
    }

    public function insert($data)
    {
        return $this->db->insert('users', $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('users');
    }

    public function count_users($role = null)
    {
        if ($role) {
            $this->db->where('role', $role);
        }
        return $this->db->count_all_results('users');
    }
}
