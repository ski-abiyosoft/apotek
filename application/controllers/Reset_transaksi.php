<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reset_transaksi extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('M_global', 'M_global');
		$this->load->model('M_cetak');
		$this->session->set_userdata('menuapp', '6000');
		$this->session->set_userdata('submenuapp', '6104');
		$this->load->model('M_template_cetak');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$unit          = $this->session->userdata('unit');
			$this->load->helper('url');
			$d['cabang']   = $this->db->get('tbl_namers')->result();
			$d['bulan']    = $this->db->query("SELECT*FROM ms_bln order by id")->result();
			// $jam           = date("H:i:s");
			// $tgl           = date("m-d");

			$this->load->view('master/v_master_reset_trans', $d);
		} else {
			header('location:' . base_url());
		}
	}

	

	public function cek_button($status_masuk='')
	{
		
		$jam    = date("H:i:s");		
		$tgl    = date("m-d");		
		echo json_encode(array("jam" => $jam,"tgl" => $tgl));

	}

	public function res_dat($status_masuk='')
	{
		
		$jam    = date("H:i:s");		
		
		
		$sql = $this->db->query("UPDATE tbl_urutrs set nourut=0 where (param1='th' or param2='th') ");

		if($sql){
			echo json_encode(array("status" => '1'));
				
		}else{
			echo json_encode(array("status" => '2'));
		}
	}

}
