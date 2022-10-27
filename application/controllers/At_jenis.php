<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class At_jenis extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_at_jenis','M_at_jenis');
		$this->load->helper('simkeu_rpt');
		$this->load->helper('kode_helper');
		$this->session->set_userdata('menuapp', '5000');
		$this->session->set_userdata('submenuapp', '5401');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{
			$d['coa'] = $this->db->get("tbl_accounting")->result();	
			$d['unit'] = $this->db->get("tbl_namers");
			$this->load->view('at/v_at_jenis',$d);
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_find(string $fixid)
	{
		echo json_encode(($this->M_at_jenis->get_jenis($fixid)));
	}

	public function ajax_list()
	{
		$list = $this->M_at_jenis->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $at) {
			$no++;
			$row = array();
			$row[] = $at->koders;
			$row[] = $at->fixid;
			$row[] = $at->groupname;
			$row[] = $at->metode;
			$row[] = $at->fixrate;
			$row[] = $at->tahunsusut;
			
			
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$at->id."'".')"><i class="glyphicon glyphicon-edit"></i></a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$at->id."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_at_jenis->count_all(),
						"recordsFiltered" => $this->M_at_jenis->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_at_jenis->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();

		$code = generate_fix_group_code($this->M_at_jenis->get_auto_increment ());

		$data = array(
				'koders' => $this->input->post('cabang'),
				'fixid' => $code,
				'groupname' => $this->input->post('nama'),
				'tahunsusut' => $this->input->post('umurekonomis'),
				'fixrate' => $this->input->post('tarif'),
				'metode' => $this->input->post('metode'),
				'fix_account' => $this->input->post('fix_account'),
				'depreciation_account' => $this->input->post('depreciation_account'),
				'cost_account' => $this->input->post('cost_account')
			);
		$insert = $this->M_at_jenis->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
		        'koders' => $this->input->post('cabang'),
				'groupname' => $this->input->post('nama'),
				'tahunsusut' => $this->input->post('umurekonomis'),
				'fixrate' => $this->input->post('tarif'),
				'metode' => $this->input->post('metode'),
				'fix_account' => $this->input->post('fix_account'),
				'depreciation_account' => $this->input->post('depreciation_account'),
				'cost_account' => $this->input->post('cost_account')
			);
		$this->M_at_jenis->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_at_jenis->delete_by_id($id);
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
			$data['error_string'][] = 'Kode jenis at harus diisi';
			$data['status'] = FALSE;
		}

		if($this->input->post('nama') == '')
		{
			$data['inputerror'][] = 'nama';
			$data['error_string'][] = 'nama jenis masih kosong';
			$data['status'] = FALSE;
		}

		
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	
	public function cetak()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{				  
		  $page=$this->uri->segment(3);
		  $limit=$this->config->item('limit_data');	
          $d['master_bank'] = $this->db->get("ms_bank");
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  
		  $this->load->view('at/at_jenis_prn',$d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
	public function export()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{				  
		  $page=$this->uri->segment(3);
		  $limit=$this->config->item('limit_data');	
          $d['master_bank'] = $this->db->get("ms_bank");
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  
		  $this->load->view('at/at_jenis_exp',$d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
}

/* End of file master_bank.php */
/* Location: ./application/controllers/master_bank.php */