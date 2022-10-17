<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_nomr extends CI_Controller {

	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_norm','M_norm');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '1000');
		$this->session->set_userdata('submenuapp', '1107');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');				
		if(!empty($cek))
		{
			$this->load->view('master/v_master_penjamin');
		} else
		{
			header('location:'.base_url());
		}			
	}

   
	public function ajax_list()
	{		
		$list = $this->M_norm->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit) {
			$no++;
			$kode = substr($unit->mrkey,0,1);
			$row = array();
			$row[] = $unit->mrkey;
			$row[] = $unit->urut;
						
			$row[] = '<a class="" href="javascript:void(0)" title="Edit" onclick="edit_data_nomr('."'".$unit->mrkey."'".')">SET </a>
			<a class="" href="javascript:void(0)" title="Edit" onclick="detil_data('."'".$kode."'".')">DETIL </a>
			';
				  
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_norm->count_all(),
						"recordsFiltered" => $this->M_norm->count_filtered( ),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_norm->get_by_id($id);		
		echo json_encode($data);
	}
	
	public function ajax_update()
	{		
		$data = array(
				'urut' => $this->input->post('nourutmr'),	
				
			);
		$this->M_norm->update(array('mrkey' => $this->input->post('idmr')), $data);
		echo json_encode(array("status" => TRUE));
	}



	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('kode') == '')
		{
			$data['inputerror'][] = 'kode';
			$data['error_string'][] = 'Kode harus diisi';
			$data['status'] = FALSE;
		}

		if($this->input->post('nama') == '')
		{
			$data['inputerror'][] = 'nama';
			$data['error_string'][] = 'Nama masih kosong';
			$data['status'] = FALSE;
		}
		
	
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	

	
	
	
	
}

/* End of file master_unit.php */
/* Location: ./application/controllers/master_unit.php */