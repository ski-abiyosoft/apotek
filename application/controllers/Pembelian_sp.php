<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_sp extends CI_Controller {

	
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_supplier','M_supplier');
		$this->session->set_userdata('menuapp', '300');
		$this->session->set_userdata('submenuapp', '310');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{			
	        $level=$this->session->userdata('level');		
			$akses= $this->M_global->cek_menu_akses($level, 310);
			$data['akses']= $akses;
			$this->load->view('pembelian/v_pembelian_sp', $data);
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list()
	{
		$level=$this->session->userdata('level');		
		$akses= $this->M_global->cek_menu_akses($level, 310);
			
		$list = $this->M_supplier->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $supplier) {
			$no++;
			$row = array();			
			$row[] = $supplier->kode;
			$row[] = $supplier->nama;
			$row[] = $supplier->alamat1;
			$row[] = $supplier->telp;
			$row[] = $supplier->contactname;
			
			if($akses->uedit==1 && $akses->udel==1){
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$supplier->kode."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$supplier->kode."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';
			} else 	  
			if($akses->uedit==1 && $akses->udel==0){
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$supplier->kode."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
				  
			} else 	  
			if($akses->uedit==0 && $akses->udel==1){
			$row[] =
				  ' <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$supplier->kode."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';
			} else 	{
			$row[] = '';	
			}  
				
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_supplier->count_all(),
						"recordsFiltered" => $this->M_supplier->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_supplier->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'kode' => $this->input->post('kode'),
				'telp' => $this->input->post('telp'),
				'nama' => $this->input->post('nama'),
				'fax' => $this->input->post('fax'),
				'alamat1' => $this->input->post('alamat1'),
				'hp' => $this->input->post('hp'),
				'alamat2' => $this->input->post('alamat2'),
				'contactname' => $this->input->post('contactname'),
				'kota' => $this->input->post('kota'),
				'email' => $this->input->post('email'),
				'kodepos' => $this->input->post('kodepos'),
				'top' => $this->input->post('top'),
				'pkp' => $this->input->post('pkp'),				
				'kode_user' => $this->session->userdata('username'),
				
				
			);
		$insert = $this->M_supplier->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'kode' => $this->input->post('kode'),
				'telp' => $this->input->post('telp'),
				'nama' => $this->input->post('nama'),
				'fax' => $this->input->post('fax'),
				'alamat1' => $this->input->post('alamat1'),
				'hp' => $this->input->post('hp'),
				'alamat2' => $this->input->post('alamat2'),
				'contactname' => $this->input->post('contactname'),
				'kota' => $this->input->post('kota'),
				'email' => $this->input->post('email'),
				'kodepos' => $this->input->post('kodepos'),
				'top' => $this->input->post('top'),
				'pkp' => $this->input->post('pkp'),								
			);
		$this->M_supplier->update(array('kode' => $this->input->post('kode')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_supplier->delete_by_id($id);
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
			$data['error_string'][] = 'kode tidak boleh kosong';
			$data['status'] = FALSE;
		}

	
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	
	
	
}

