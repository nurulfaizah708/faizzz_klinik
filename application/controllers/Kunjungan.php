<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kunjungan extends CI_Controller {

    function __construct() {
        parent::__construct();

        if(empty($this->session->userdata('login'))){
            redirect('auth');
        }

        $this->load->model('m_kunjungan');
        $this->load->model('m_pasien');
        $this->load->model('m_dokter');
        $this->load->model('m_obat');
    }

	public function index()
	{
        $data['title'] = "Data Kunjungan/Berobat";

        $data['kunjungan'] = $this->m_kunjungan->tampil_data()->result_array();

		$this->load->view('v_header', $data);
        $this->load->view('kunjungan/v_data', $data);
        $this->load->view('v_footer');
	}

     function tambah(){
        $data['title'] = "Kunjungan Baru";

        $data['pasien'] = $this->m_pasien->tampil_data()->result_array();   
        $data['dokter'] = $this->m_dokter->tampil_data()->result_array();   

        $this->load->view('v_header', $data);
        $this->load->view('kunjungan/v_data_tambah');
        $this->load->view('v_footer');
    }

     function insert(){
        $tgl = $this->input->post('tgl_berobat');
        $pasien = $this->input->post('pasien');
        $dokter = $this->input->post('dokter');

        $data = array(
            'tgl_berobat' => $tgl,
            'id_pasien' => $pasien,
            'id_dokter' => $dokter
        );

        $this->m_kunjungan->insert_data($data);

        redirect('kunjungan');
    }

     function edit($id){
        $data['title'] = "Edit Data kunjungan";

        $where = array('id_pasien',  $id);
        $data['r'] = $this->m_kunjungan->edit_data($where)->row_array();

        $this->load->view('v_header', $data);
        $this->load->view('kunjungan/v_data_tambah', $data);
        $this->load->view('v_footer');  
    }

    public function update(){
        $id = $this->input->post('id');
        $nama = $this->input->post('nama_pasien');
        $jk = $this->input->post('jenis_kelamin');
        $umur = $this->input->post('umur');

        $data = array(
            'nama_pasien' => $nama,
            'jenis_kelamin' => $jk,
            'umur' => $umur
        );  
        $where = array('id_kunjungan' => $id);
        $this->m_pasien->update_data($data, $where);

        redirect('kunjungan');
    }

    function hapus($id){
        $where = array('id_kunjungan' => $id);
        $this->m_pasien->hapus_data($where);
        redirect('kunjungan');
    }

    function rekam($id){
        $data['title'] = "Rekam Medis";
        $data['d'] = $this->m_kunjungan->tampil_rm($id)->row_array();

        $q = $this->db->query("SELECT id_pasien FROM berobat WHERE id_berobat='$id'")->row_array();
        $id_pasien = $q['id_pasien'];
        $data['riwayat'] = $this->m_kunjungan->tampil_riwayat($id_pasien)->result_array();

        $data['obat'] = $this->m_obat->tampil_data()->result_array();
        $data['resep'] = $this->m_kunjungan->tampil_resep($id)->result_array();


        $this->load->view('v_header', $data);
        $this->load->view('kunjungan/v_rekam_medis', $data);
        $this->load->view('v_footer');
    }
    function insert_rm(){
         $id_berobat = $this->input->post('id');
         $keluhan = $this->input->post('keluhan');
         $diagnosa = $this->input->post('diagnosa');
         $penatalaksanaan = $this->input->post('penatalaksanaan');

         $data =  array(
            'keluhan_pasien' => $keluhan,
            'hasil_diagnosa' => $diagnosa,
            'penatalaksanaan' => $penatalaksanaan
         );

            $where= array('id_berobat'=>$id_berobat);

            $this->m_kunjungan->update_data($data, $where);

            redirect('kunjungan/rekam/'.$id_berobat);   
         
    }
    function insert_resep(){
        $id_berobat = $this->input->post('id');
        $obat = $this->input->post('obat');

        
        $data =  array(
            'id_berobat' => $id_berobat,
            'id_obat' => $obat
         );

         $this->m_kunjungan->insert_resep($data);

         redirect('kunjungan/rekam/'.$id_berobat);   
    }

    function hapus_resep($id, $id_berobat){
        $where = array('id_resep' =>$id);
        $this->m_kunjungan->hapus_resep($where);

        redirect('kunjungan/rekam/'.$id_berobat);   
    }
}
