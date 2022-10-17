<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public  function __construct(){
	parent::__construct();
	$this->is_logged_in();
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
	}
	
}

/* End of file site.php */
/* Location: ./application/controllers/site.php */
