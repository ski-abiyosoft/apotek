<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penjualan_cs extends CI_Controller {

	
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_customer','M_customer');
		$this->session->set_userdata('menuapp', '400');
		$this->session->set_userdata('submenuapp', '410');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{			
	        $level=$this->session->userdata('level');		
		    $akses= $this->M_global->cek_menu_akses($level, 410);
		    $d['akses']= $akses;
			$this->load->view('penjualan/v_penjualan_cs', $d);
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list()
	{
		$level=$this->session->userdata('level');		
		$akses= $this->M_global->cek_menu_akses($level, 410);
		
		$list = $this->M_customer->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $customer) {
			$no++;
			$row = array();			
			$row[] = $customer->kode;
			$row[] = $customer->nama;
			$row[] = $customer->alamat1;
			$row[] = $customer->telp;
			$row[] = $customer->contactname;
			
			if($akses->uedit==1 && $akses->udel==1){
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$customer->kode."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$customer->kode."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';
			} else 
			if($akses->uedit==1 && $akses->udel==0){
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$customer->kode."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
				  
			} else 
            if($akses->uedit==0 && $akses->udel==1){
			$row[] = 
				  '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$customer->kode."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';	
            } else {
			$row[] = '';	
			}  				
			
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_customer->count_all(),
						"recordsFiltered" => $this->M_customer->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_customer->get_by_id($id);
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
				'npwp' => $this->input->post('npwp'),				
				'kode_user' => $this->session->userdata('username'),
				'bataskredit' => $this->input->post('bataskredit'),
				'kodesales' => $this->input->post('sales'),
				'tipe' => $this->input->post('tipe'),
				'ktp' => $this->input->post('ktp'),
				'kredit' => $this->input->post('kredit'),
				
			);
		$insert = $this->M_customer->save($data);
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
				'npwp' => $this->input->post('npwp'),								
				'bataskredit' => $this->input->post('bataskredit'),
				'kodesales' => $this->input->post('sales'),
				'tipe' => $this->input->post('tipe'),
				'ktp' => $this->input->post('ktp'),	
                'kredit' => $this->input->post('kredit'),				
			);
		$this->M_customer->update(array('kode' => $this->input->post('kode')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_customer->delete_by_id($id);
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
			$data['error_string'][] = 'Kode tidak boleh kosong';
			$data['status'] = FALSE;
		}
		
		if($this->input->post('nama') == '')
		{
			$data['inputerror'][] = 'nama';
			$data['error_string'][] = 'Nama tidak boleh kosong';
			$data['status'] = FALSE;
		}
		
		if($this->input->post('hp') == '')
		{
			$data['inputerror'][] = 'hp';
			$data['error_string'][] = 'No. Handphone tidak boleh kosong';
			$data['status'] = FALSE;
		}

	
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	
	
	
}

