<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penjualan_sl extends CI_Controller {

	
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_sales','M_sales');
		$this->session->set_userdata('menuapp', '400');
		$this->session->set_userdata('submenuapp', '411');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{			
	        $level=$this->session->userdata('level');		
		    $akses= $this->M_global->cek_menu_akses($level, 411);
		    $d['akses']= $akses;
			$this->load->view('penjualan/v_penjualan_sl', $d);
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list()
	{
		$level=$this->session->userdata('level');		
		$akses= $this->M_global->cek_menu_akses($level, 410);
		
		$list = $this->M_sales->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $sales) {
			$no++;
			$row = array();			
			$row[] = $sales->nama_sales;
			$row[] = $sales->telpon;
			
			if($akses->uedit==1 && $akses->udel==1){
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$sales->id_sales."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$sales->id_sales."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';
			} else 
			if($akses->uedit==1 && $akses->udel==0){
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$sales->id_sales."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
				  
			} else 
            if($akses->uedit==0 && $akses->udel==1){
			$row[] = 
				  '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$sales->id_sales."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';	
            } else {
			$row[] = '';	
			}  				
			
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_sales->count_all(),
						"recordsFiltered" => $this->M_sales->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_sales->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'telpon' => $this->input->post('telp'),
				'nama_sales' => $this->input->post('nama'),
				
			);
		$insert = $this->M_sales->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'telpon' => $this->input->post('telp'),
				'nama_sales' => $this->input->post('nama'),				
			);
		$this->M_sales->update(array('id_sales' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_sales->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		
		
		if($this->input->post('nama') == '')
		{
			$data['inputerror'][] = 'nama';
			$data['error_string'][] = 'Nama tidak boleh kosong';
			$data['status'] = FALSE;
		}
		
		
	
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	
	
	
}

