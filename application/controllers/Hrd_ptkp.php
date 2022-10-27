<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hrd_ptkp extends CI_Controller {

	/**
	 * @author : Enjang RK
	 * @web : http://e-soft.comli.com
	 * @keterangan : Controller untuk manajemen jabatan (CRUD master jabatan)
	 **/
	
	
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_ptkp','M_ptkp');
		$this->session->set_userdata('menuapp', '700');
		$this->session->set_userdata('submenuapp', '704');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{
			$this->load->view('hrd/v_hrd_ptkp');
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list()
	{
		$list = $this->M_ptkp->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $departemen) {
			$no++;
			$row = array();
			$row[] = $departemen->tahun;
			$row[] = $departemen->kode;
			$row[] = $departemen->ptkp;
			
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_ptkp('."'".$departemen->id_ptkp."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_ptkp('."'".$departemen->id_ptkp."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_ptkp->count_all(),
						"recordsFiltered" => $this->M_ptkp->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_ptkp->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'kode' => $this->input->post('kode'),
				'tahun' => $this->input->post('tahun'),
				'ptkp' => $this->input->post('ptkp'),
			
			);
		$insert = $this->M_ptkp->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'kode' => $this->input->post('kode'),
				'tahun' => $this->input->post('tahun'),
				'ptkp' => $this->input->post('ptkp'),
				
			);
		$this->M_ptkp->update(array('id_ptkp' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_ptkp->delete_by_id($id);
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
			$data['error_string'][] = 'kode  masih kosong';
			$data['status'] = FALSE;
		}
		
		if($this->input->post('tahun') == '')
		{
			$data['inputerror'][] = 'tahun';
			$data['error_string'][] = 'tahun  masih kosong';
			$data['status'] = FALSE;
		}
		
		if($this->input->post('ptkp') == '')
		{
			$data['inputerror'][] = 'ptkp';
			$data['error_string'][] = 'ptkp  masih kosong';
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
          $d['master'] = $this->db->get("ms_bidang");
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  
		  $this->load->view('hrd/v_hrd_jabatan_prn',$d);				
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
          $d['master'] = $this->db->get("ms_bidang");
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  
		  $this->load->view('hrd/v_hrd_jabatan_exp',$d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
}

/* End of file master_bank.php */
/* Location: ./application/controllers/master_bank.php */