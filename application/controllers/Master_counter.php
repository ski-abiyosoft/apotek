<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_counter extends CI_Controller {

	/**
	 * @author : Enjang RK
	 * @web : http://gorom2.com
	 * @keterangan : Controller untuk manajemen counter
	 **/
	
	
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_counter','M_counter');
		$this->load->helper('simkeu_rpt');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');				
		if(!empty($cek))
		{
			$this->load->view('master/counter/v_master_counter');
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list()
	{
		$list = $this->M_counter->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit) {
			$no++;
			$row = array();
			$row[] = $unit->kdtr;
			$row[] = $unit->cdno;
			$row[] = $unit->ket;
			
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$unit->kdtr."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
			
				  //<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$unit->kode."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_counter->count_all(),
						"recordsFiltered" => $this->M_counter->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_counter->get_by_id($id);		
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'kdtr' => $this->input->post('kode'),
				'cdno' => $this->input->post('nomor'),
				'ket' => $this->input->post('ket'),
				
			);
		$insert = $this->M_counter->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'kdtr' => $this->input->post('kode'),
				'cdno' => $this->input->post('nomor'),
				'ket' => $this->input->post('ket'),
				
			);
		$this->M_counter->update(array('kdtr' => $this->input->post('kode')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_counter->delete_by_id($id);
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

		if($this->input->post('nomor') == '')
		{
			$data['inputerror'][] = 'nomor';
			$data['error_string'][] = 'Nomor masih kosong';
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
		  
		  $this->load->view('master/unit/v_master_counter_prn',$d);				
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
		  
		  $this->load->view('master/unit/v_master_counter_exp',$d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
}

/* End of file master_unit.php */
/* Location: ./application/controllers/master_unit.php */