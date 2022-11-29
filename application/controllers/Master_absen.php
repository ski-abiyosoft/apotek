<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_absen extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_absen2','M_absen2');
		$this->load->model('M_global');
		$this->load->model('M_cetak');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '6000');
		$this->session->set_userdata('submenuapp', '6100');
	}

	public function index()
	{
		$username   = $this->session->userdata('username');
		$cekk       = $this->session->userdata('level');
		$karyawan   = $this->db->query("SELECT*From tbl_kary_ski where user='$username' ORDER BY nik LIMIT 1")->row();
		$status     = $this->db->query("SELECT*From tbl_setinghms where lset='ABSN' order by kodeset")->result();
        $cek  = $this->M_global->tgln();
		if(!empty($cekk))
		{
			$data  = array(
				'form_id'    => 'absen',
				'form_title' => 'Data Absensi',
				'cek'        => $cek,
				'karyawan' => $karyawan,
				'status'     => $status,
			);
			$this->load->view('master/v_master_absen',$data);
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_add($status_masuk='')
	{
		$nik          = $this->input->post('nama_kary');
		$tgl_absen    = $this->input->post('tgl2');
		$tgll         = $this->M_cetak->tanggal_format_indonesia($tgl_absen);
		$jam          = date("H:i:s");
		$status_absen = $this->input->post('status_absen');
		$asc1         = $this->db->query("SELECT * FROM tbl_absen_ski where nik='$nik' and tgl_absen='$tgl_absen' and status_masuk='$status_masuk'")->num_rows();
		
		if($asc1>0){			
			echo json_encode(array("status" => '2',"cek"=>false,"status_masuk"=>$status_masuk,"tgll"=>$tgll));
		}else{
			$data = array(
				'nik'         	=> $nik,
				'tgl_absen'   	=> $tgl_absen,
				'jam'         	=> $jam,
				'status'      	=> $status_absen,				
				'status_masuk'  => $status_masuk,				
			);
			$asc = $this->M_absen2->save($data);
			if($asc){
				echo json_encode(array("status" => '1',"cek"=>$asc,"status_masuk"=>$status_masuk,"tgll"=>$tgll));
			}else{
				echo json_encode(array("status" => '2',"cek"=>$asc,"status_masuk"=>$status_masuk,"tgll"=>$tgll));
			}
		}
	}

	public function ajax_delete($id)
	{
		$this->M_absen2->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}	

	public function karyawan($nik)
	{
		$query=$this->db->query("SELECT*from tbl_kary_ski where nik='$nik'")->row();		
		echo json_encode(array("nama" => $query->namakary));
	}	
		
}