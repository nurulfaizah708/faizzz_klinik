<?php
class M_user extends CI_Model{

    function tampil_data(){
        return $this->db->get('user');
    }

    function insert_data($data){
        return $this->db->insert('user', $data);
    }

    function edit_data($where){
        return $this->db->get_where('user', $where);
    }

    function update_data($data, $where){
        $this->db->where($where);
        $this->db->update('user', $data);
    }

    function hapus_data($where){
        $this->db->where($where);
        $this->db->delete('user');
    }


}