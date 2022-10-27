<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_pembelian extends CI_Controller {

	public  function __construct(){
	parent::__construct();
	   //$this->is_logged_in();
	   $this->session->set_userdata('menuapp', '300');
	   //$this->session->set_userdata('submenuapp', '300');
	
	}
	
	
	
	public function is_logged_in(){
	$is_logged_in=$this->session->userdata('is_logged_in');
		if(!isset($is_logged_in)||$is_logged_in!= true) {
		redirect(base_url());
		} 
	}
	
	public function index()
	{
		$this->load->view('template');
		$this->load->view('template\dashboard_pembelian');
	}
	
}

/* End of file site.php */
/* Location: ./application/controllers/site.php */
