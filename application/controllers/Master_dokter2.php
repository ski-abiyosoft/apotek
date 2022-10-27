<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_dokter2 extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('M_dokter','M_dokter');
		$this->load->model('M_dokter_poli','M_dokter_poli');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '1000');
		$this->session->set_userdata('submenuapp', '1105');
	}

	public function index() {
		$cek = $this->session->userdata('level');
		$cabangx = $this->input->get("cabang");
		if(!empty($cek)) {
			if($cabangx == '' || $cabangx == null){
				$cabang 	= '';
				$kondisi 	= "";
			} else {
				$cabang 	= $cabangx;
				$kondisi 	= "AND koders = '$cabang'";
			}
			$data["cabang"] = $cabang;
			$data["list"] 	= $this->db->query("SELECT * FROM tbl_dokter WHERE jenispegawai = 1 $kondisi")->result();
			$this->load->view('master/v_masterdokter2', $data);
		} else {
			header('location:'.base_url());
		}			
	}

	public function v_add() {
		$cek = $this->session->userdata('level');
		if(!empty($cek)) {
			$level = $this->session->userdata('level');
			$akses = $this->M_global->cek_menu_akses($level, 3102);
			$this->load->helper('url');
			$data['modul']    = 'DOKTER';
			$data['submodul'] = 'Master Dokter';
			$data['link']     = 'Master Dokter Entri';
			$data['url']      = 'Master_dokter2';
			$data['tanggal']  = date('d-m-Y');
			$data['akses']    = $akses;
			$this->load->view('master/v_add_masterdokter', $data);
		} else {
			header('location:'.base_url());
		}			
	}

	public function f_add(){
		$cek 		= $this->session->userdata('level');
		$koders = $this->session->userdata('unit');
		if (!empty($cek)) {
			$jenispegawai    = $this->input->post("jenispegawai");
			$kodokter        = urut_transaksi('MASTER_DR', 10);
			$nadokter        = $this->input->post("nadokter");
			$nik             = $this->input->post("nik");
			$npwp            = $this->input->post("npwp");
			$nosip           = $this->input->post("nosip");
			$hp              = $this->input->post("hp");
			$status          = $this->input->post("status");
			$tglmasuk        = $this->input->post("tglmasuk");
			$tglberhenti     = $this->input->post("tglberhenti");
			$alamat          = $this->input->post("alamat");
			$email           = $this->input->post("email");
			$data = [
				'koders'        => $koders,
				'kodokter'      => $kodokter,
				'nadokter'      => $nadokter,
				'nik'           => $nik,
				'npwp'          => $npwp,
				'nosip'         => $nosip,
				'hp'            => $hp,
				'status'        => $status,
				'tglmasuk'      => $tglmasuk,
				'tglberhenti'   => $tglberhenti,
				'alamat'        => $alamat,
				'jenispegawai'  => $jenispegawai,
				'email'         => $email,
			];
			$cek = $this->db->insert("tbl_dokter", $data);
			if($cek){
				echo json_encode(['status' => 1, 'kodokter' => $kodokter]);
			} else {
				echo json_encode(['status' => 0]);
			}
		} else {
			header('location:' . base_url());
		}	
	}

	public function fd_add(){
		$kodokter = $this->input->get("kodokter");
		$unit 		= $this->input->get("unit");
		$data = [
			'kodokter' 	=> $kodokter,
			'kopoli' 		=> $unit,
		];
		$this->db->insert("tbl_drpoli", $data);
	}

	public function fdc_add(){
		$kodokter = $this->input->get("kodokter");
		$lokasi 	= $this->input->get("lokasi");
		$data = [
			'kodokter' 	=> $kodokter,
			'koders' 		=> $lokasi,
		];
		$this->db->insert("tbl_doktercabang", $data);
	}

	public function delete($kodokter){
		$this->db->where("kodokter", $kodokter);
		$this->db->delete("tbl_doktercabang");

		$this->db->where("kodokter", $kodokter);
		$this->db->delete("tbl_drpoli");

		$this->db->where("kodokter", $kodokter);
		$this->db->delete("tbl_dokter");
		
		echo json_encode(['status' => 1]);
	}

	public function v_edit($id){
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$level = $this->session->userdata('level');
			$akses = $this->M_global->cek_menu_akses($level, 3102);
			$this->load->helper('url');
			$data['modul']    = 'DOKTER';
			$data['submodul'] = 'Master Dokter';
			$data['link']     = 'Master Dokter Edit';
			$data['url']      = 'Master_dokter2';
			$data['tanggal']  = date('d-m-Y');
			$data['akses']    = $akses;
			$dokter = $this->db->get_where("tbl_dokter", ["id" => $id])->row();
			$data['header'] 	= $dokter;
			$data['kopoli']		= $this->db->query("SELECT p.*, n.namapost FROM tbl_drpoli p JOIN tbl_namapos n ON p.kopoli=n.kodepos WHERE p.kodokter = '$dokter->kodokter'")->result();
			$data['jumunit']	= $this->db->query("SELECT p.*, n.namapost FROM tbl_drpoli p JOIN tbl_namapos n ON p.kopoli=n.kodepos WHERE p.kodokter = '$dokter->kodokter'")->num_rows();
			$data['drcabang']	= $this->db->query("SELECT p.*, n.namars FROM tbl_doktercabang p JOIN tbl_namers n ON p.koders=n.koders WHERE p.kodokter = '$dokter->kodokter'")->result();
			$data['jumcabang']= $this->db->query("SELECT p.*, n.namars FROM tbl_doktercabang p JOIN tbl_namers n ON p.koders=n.koders WHERE p.kodokter = '$dokter->kodokter'")->num_rows();
			$this->load->view('master/v_edit_masterdokter', $data);
		} else {
			header('location:' . base_url());
		}
	}

	public function f_edit()
	{
		$cek 		= $this->session->userdata('level');
		$koders = $this->session->userdata('unit');
		if (!empty($cek)) {
			$kodokter 		= $this->input->post('kodokter');
			$nadokter 		= $this->input->post("nadokter");
			$nik 					= $this->input->post("nik");
			$npwp 				= $this->input->post("npwp");
			$nosip 				= $this->input->post("nosip");
			$hp 					= $this->input->post("hp");
			$status 			= $this->input->post("status");
			$tglmasuk 		= $this->input->post("tglmasuk");
			$tglberhenti 	= $this->input->post("tglberhenti");
			$alamat 			= $this->input->post("alamat");
			$jenispegawai = $this->input->post("jenispegawai");
			$email 				= $this->input->post("email");
			$data = [
				'koders' 				=> $koders,
				'kodokter' 			=> $kodokter,
				'nadokter' 			=> $nadokter,
				'nik' 					=> $nik,
				'npwp' 					=> $npwp,
				'nosip' 				=> $nosip,
				'hp' 						=> $hp,
				'status' 				=> $status,
				'tglmasuk' 			=> $tglmasuk,
				'tglberhenti' 	=> $tglberhenti,
				'alamat' 				=> $alamat,
				'jenispegawai' 	=> $jenispegawai,
				'email' 				=> $email,
			];
			$this->db->where("kodokter", $kodokter);
			$this->db->delete("tbl_dokter");

			$this->db->where("kodokter", $kodokter);
			$this->db->delete("tbl_drpoli");

			$this->db->where("kodokter", $kodokter);
			$this->db->delete("tbl_doktercabang");

			$cek = $this->db->insert("tbl_dokter", $data);
			if ($cek) {
				echo json_encode(['status' => 1, 'kodokter' => $kodokter]);
			} else {
				echo json_encode(['status' => 0]);
			}
		} else {
			header('location:' . base_url());
		}
	}

	public function fd_edit() {
		$kodokter = $this->input->get("kodokter");
		$unit 		= $this->input->get("unit");
		$data = [
			'kodokter' 	=> $kodokter,
			'kopoli' 		=> $unit,
		];
		$this->db->insert("tbl_drpoli", $data);
		// echo json_encode($data);
	}

	public function fdc_edit() {
		$kodokter = $this->input->get("kodokter");
		$lokasi 	= $this->input->get("lokasi");
		$data = [
			'kodokter' 	=> $kodokter,
			'koders' 		=> $lokasi,
		];
		$this->db->insert("tbl_doktercabang", $data);
		// echo json_encode($data);
	}
}