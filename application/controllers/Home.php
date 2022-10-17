<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	public  function __construct(){
	parent::__construct();
	
		$this->load->model('M_global', 'M_global');
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
		/* $str = data_master('tbl_namers',array('koders'=>$this->session->userdata('unit')))->namars;
		$str = $this->session->userdata('unit');
		$text = explode(" ", $str);
		
		$isi = array();
		for($i=0; $i<(count($text)-1); $i++){
			$isi[$i] = $text[$i];
			if($text[$i] == 'Affandi'){
				$i++;
			}
		} */

		$data['title'] = "".$this->session->userdata('namars');
		$data['cekkon'] = $this->M_global->cek_internet();

		$this->load->view('template/home', $data);
		
	}
	
}

/* End of file site.php */
/* Location: ./application/controllers/site.php */
