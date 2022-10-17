<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public  function __construct(){
	parent::__construct();
	   $this->is_logged_in();
	   $this->load->model('M_dashboard');
	
	}
	
	public function is_logged_in(){
	$is_logged_in=$this->session->userdata('is_logged_in');
		if(!isset($is_logged_in)||$is_logged_in!= true) {
		redirect(base_url());
		} 
	}
	
	public function index()
	{
		$p1 = '';
		$p2 = '';
		$data['report']    = $this->M_dashboard->report();
		$pasien    		   = $this->M_dashboard->jcustomer();        
		$data['pasien']	   = $pasien[0]->jml;
		$data['periode']   = $this->M_global->_periodebulan().'-'.$this->M_global->_periodetahun();
		$data['tahun']     = 'Periode Tahun '.$this->M_global->_periodetahun();
		$data['hutang']    = $this->M_dashboard->dshutang();        
		$data['piutang']   = $this->M_dashboard->dspiutang();  
		$data['aset']      = $this->M_dashboard->dsaset();  
		$data['lr']        = 0;  
		
		$this->load->view('template',$data);
		$this->load->view('template/dashboard',$data);
		
	}
	
}

/* End of file site.php */
/* Location: ./application/controllers/site.php */