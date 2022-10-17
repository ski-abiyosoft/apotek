<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Backup extends CI_Controller {

	public function index()
	{
		$cek = $this->session->userdata('level');
		if(!empty($cek))
		{
			$this->m_global->manualQuery("TRUNCATE TABLE ci_sessions");
			$nama_file = 'backup-'.date('d-m-Y').'.zip';
			$this->load->dbutil();
			$backup = $this->dbutil->backup();
			force_download($nama_file,$backup);
		}
		else
		{
			header('location:'.base_url());
		}
	}
	
}

/* End of file site.php */
/* Location: ./application/controllers/site.php */
