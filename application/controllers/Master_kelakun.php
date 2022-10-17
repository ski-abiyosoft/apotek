<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_kelakun extends CI_Controller {

	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_kelakun','M_kelakun');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '1000');
		$this->session->set_userdata('submenuapp', '1401');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');				
		if(!empty($cek))
		{
			$data['actype'] = $this->db->get('tbl_ac_type')->result();
			$this->load->view('master/v_master_kelakun', $data);
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list()
	{
		$list = $this->M_kelakun->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit) {
			$no++;
			$row = array();
			$row[] = $unit->actype;
			$row[] = $unit->typename;
			$row[] = $unit->acgol;
						
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$unit->id."'".')"><i class="glyphicon glyphicon-edit"></i> </a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$unit->id."'".')"><i class="glyphicon glyphicon-trash"></i> </a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_kelakun->count_all(),
						"recordsFiltered" => $this->M_kelakun->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_kelakun->get_by_id($id);		
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'actype' => $this->input->post('kode'),
				'typename' => $this->input->post('nama'),
				'ackode' => $this->input->post('lap'),
				'pls' => $this->input->post('pls'),
				
			);
		$insert = $this->M_kelakun->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'actype' => $this->input->post('kode'),
				'typename' => $this->input->post('nama'),
				'ackode' => $this->input->post('lap'),
				'pls' => $this->input->post('pls'),
				
			);
		$this->M_kelakun->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_kelakun->delete_by_id($id);
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
		
		if($this->input->post('lap') == '')
		{
			$data['inputerror'][] = 'lap';
			$data['error_string'][] = 'Kelompok Laporan masih kosong';
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
          $d['unit'] = '';          
          $d['master'] = $this->db->get("ms_unit")->result();
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  
		  $this->load->view('master/unit/v_master_unit_prn',$d);				
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
          $d['master'] = $this->db->get("ms_unit");
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  
		  $this->load->view('master/unit/v_master_unit_exp',$d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
}
