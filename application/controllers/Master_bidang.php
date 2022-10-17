<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_bidang extends CI_Controller {

	/**
	 * @author : Enjang RK
	 * @web : http://e-soft.comli.com
	 * @keterangan : Controller untuk manajemen bank (CRUD master bank)
	 **/
	
	
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_bidang','M_bidang');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{
			$this->load->view('master/bidang/v_master_bidang');
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list()
	{
		$list = $this->M_bidang->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $bidang) {
			$no++;
			$row = array();
			$row[] = $bidang->kode;
			$row[] = $bidang->nama;
			
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_bank('."'".$bidang->nomor."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_bank('."'".$bidang->nomor."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_bidang->count_all(),
						"recordsFiltered" => $this->M_bidang->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_bidang->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'kode' => $this->input->post('bidang_kode'),
				'nama' => $this->input->post('bidang_nama'),
			
			);
		$insert = $this->M_bidang->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'kode' => $this->input->post('bidang_kode'),
				'nama' => $this->input->post('bidang_nama'),
				
			);
		$this->M_bidang->update(array('nomor' => $this->input->post('nomor')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_bidang->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('bidang_kode') == '')
		{
			$data['inputerror'][] = 'bidang_kode';
			$data['error_string'][] = 'Kode  harus diisi';
			$data['status'] = FALSE;
		}

		if($this->input->post('bidang_nama') == '')
		{
			$data['inputerror'][] = 'bidang_nama';
			$data['error_string'][] = 'nama bidang masih kosong';
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
		  
		  $this->load->view('master/bidang/v_master_bidang_prn',$d);				
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
		  
		  $this->load->view('master/bidang/v_master_bidang_exp',$d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
}

/* End of file master_bank.php */
/* Location: ./application/controllers/master_bank.php */