<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_subkat extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_subkategori','M_subkategori');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{
			$data['kateg']    = $this->db->get("inv_kategori");
			$data['karyawan'] = $this->db->get("hrd_karyawan");
			$this->load->view('inventory/v_inventory_subkategori',$data);
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list()
	{
		$list = $this->M_subkategori->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $kategori) {
			$no++;
			$row = array();
			$row[] = $kategori->kode;
			$row[] = $kategori->kode_kateg;
			$row[] = $kategori->nama;
			$row[] = $kategori->pic;
			
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$kategori->kode."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$kategori->kode."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_subkategori->count_all(),
						"recordsFiltered" => $this->M_subkategori->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_subkategori->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'kode' => $this->input->post('kode'),
				'nama' => $this->input->post('nama'),
				'kode_kateg' => $this->input->post('kode_kateg'),
				'pic' => $this->input->post('pic'),
			
			);
		$insert = $this->M_subkategori->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'kode' => $this->input->post('kode'),
				'nama' => $this->input->post('nama'),
				'kode_kateg' => $this->input->post('kode_kateg'),
				'pic' => $this->input->post('pic'),
				
			);
		$this->M_subkategori->update(array('kode' => $this->input->post('kode')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_subkategori->delete_by_id($id);
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

/* End of file master_data.php */
/* Location: ./application/controllers/master_data.php */