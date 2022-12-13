<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    function __construct() {
        parent::__construct();

        if(empty($this->session->userdata('login'))){
            redirect('auth');
        }

        $this->load->model('m_user');
    }

	public function index()
	{
        $data['title'] = "Manajemen Data User";

        $data['user'] = $this->m_user->tampil_data()->result_array();

		$this->load->view('v_header', $data);
        $this->load->view('users/v_data', $data);
        $this->load->view('v_footer');
	}

     function tambah(){
        $data['title'] = "Tambah Data Users";

        $this->load->view('v_header', $data);
        $this->load->view('users/v_data_tambah');
        $this->load->view('v_footer');
    }

     function insert(){
        $u = $this->input->post('username');
        $n = $this->input->post('nama_lengkap');
        $p = md5($this->input->post('password'));

        $data = array(
            'username' => $u,
            'nama_lengkap' => $n,
            'password' => $p
        );

        $this->m_user->insert_data($data);

        redirect('user');
    }

     function edit($id){
        $data['title'] = "Edit Data Users";

        $where = array('id',  $id);
        $data['r'] = $this->m_user->edit_data($where)->row_array();

        $this->load->view('v_header', $data);
        $this->load->view('users/v_data_tambah', $data);
        $this->load->view('v_footer');  
    }

    public function update(){
        $id = $this->input->post('id');
        $u = $this->input->post('username');
        $n = $this->input->post('nama_lengkap');
        $p = md5($this->input->post('password'));

        $data = array(
            'username' => $u,
            'nama_lengkap' => $n,
            'password' => $p
        );

        $where = array('id' => $id);
        $this->m_user->update_data($data, $where);

        redirect('user');
    }

    function hapus($id){
        $where = array('id' => $id);
        $this->m_user->hapus_data($where);
        redirect('user');
    }
}
