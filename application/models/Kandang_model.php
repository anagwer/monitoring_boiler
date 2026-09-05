<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kandang_model extends CI_Model {

    public function get_all()
    {
        $this->db->select('kandang.*, 
            (SELECT COUNT(*) FROM periode WHERE periode.kandang_id = kandang.id AND periode.status = "aktif") AS periode_aktif_count,
            (SELECT kode_periode FROM periode WHERE periode.kandang_id = kandang.id AND periode.status = "aktif" LIMIT 1) AS active_periode_kode,
            (SELECT id FROM periode WHERE periode.kandang_id = kandang.id AND periode.status = "aktif" LIMIT 1) AS active_periode_id
        ');
        $this->db->order_by('kode_kandang', 'ASC');
        return $this->db->get('kandang')->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('kandang', array('id' => $id))->row();
    }

    public function get_tersedia()
    {
        $this->db->where('status', 'tersedia');
        $this->db->order_by('kode_kandang', 'ASC');
        return $this->db->get('kandang')->result();
    }

    public function insert($data)
    {
        return $this->db->insert('kandang', $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('kandang', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('kandang');
    }

    public function update_status($id, $status)
    {
        $this->db->where('id', $id);
        return $this->db->update('kandang', array('status' => $status));
    }

    public function count_all()
    {
        return $this->db->count_all('kandang');
    }

    public function count_by_status($status)
    {
        $this->db->where('status', $status);
        return $this->db->count_all_results('kandang');
    }
}
