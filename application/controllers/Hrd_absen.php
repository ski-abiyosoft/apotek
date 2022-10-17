<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hrd_absen extends CI_Controller {

	
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_absen','M_absen');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{
			$this->load->view('hrd/v_hrd_absen');
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list()
	{
		$list = $this->M_absen->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $absen) {
			$no++;
			$row = array();
			$row[] = $absen->kode;
			$row[] = $absen->nama;
			$row[] = $absen->potongan;
			
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_bank('."'".$absen->kode."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_bank('."'".$absen->kode."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_absen->count_all(),
						"recordsFiltered" => $this->M_absen->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_absen->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'kode' => $this->input->post('kode'),
				'nama' => $this->input->post('nama'),
				'potongan' => $this->input->post('potongan'),
			
			);
		$insert = $this->M_absen->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'kode' => $this->input->post('kode'),
				'nama' => $this->input->post('nama'),
				'potongan' => $this->input->post('potongan'),
				
			);
		$this->M_absen->update(array('kode' => $this->input->post('kode')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_absen->delete_by_id($id);
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
			$data['error_string'][] = 'kode masih kosong';
			$data['status'] = FALSE;
		}
		
		if($this->input->post('nama') == '')
		{
			$data['inputerror'][] = 'nama';
			$data['error_string'][] = 'nama  masih kosong';
			$data['status'] = FALSE;
		}

	
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	
	
	
}
