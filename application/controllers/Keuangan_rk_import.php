<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Keuangan_rk_import extends CI_Controller {

	/**
	 * @author : Enjang RK
	 * @web : http://e-soft.comli.com
	 * @keterangan : Controller untuk modul import rekening koran dari file csv
	 **/
	
	
	
	
	public function __construct()
	{
		parent::__construct();			
		$this->session->set_userdata('menuapp', '100');
		$this->session->set_userdata('submenuapp', '106');	
		$this->load->model('M_keuangan_rk_import');		
	}

	public function index()
	{
		$cek = $this->session->userdata('level');	
		$usr = $this->session->userdata('username');		
		if(!empty($cek))
		{		
			$unit = $this->session->userdata('unit');	
			
			$qb ="select * from ms_bank where bank_jenis='B' order by bank_kode"; 
			$qh ="SELECT * FROM tr_rk1";
												
			$d['bank']  = $this->db->query($qb)->result();
			$d['rekk']  = $this->db->query($qh)->result();
			$d['pasar']  = $this->db->get('ms_pasar')->result();
			$d['jumdata']= $this->db->query($qh)->nuM_rows();	
			
			$this->load->view('keuangan/v_keuangan_import_rk',$d);
		} else
		{
			header('location:'.base_url());
		}			
	}
	
	function uploadData()
    {
		$this->M_keuangan_rk_import->import_rk();		
    }
		
	
	
	
	
}

