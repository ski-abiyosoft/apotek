<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_konversi extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_konversi');		
		$this->load->model('M_barang');		
		$this->session->set_userdata('menuapp', '500');
		$this->session->set_userdata('submenuapp', '521');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{
			$data['item']= $this->db->order_by('kodeitem')->get("inv_barang")->result_array();			
			$this->load->view('inventory/v_inventory_konversi',$data);
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list()
	{
		$list = $this->M_konversi->get_datatables();
		
		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $item) {
			$no++;
			$row = array();
			$kode1 = $item->kodeitem1;
			$kode2 = $item->kodeitem2;
			$nama1 = $this->M_global->_namabarang($kode1);
			$nama2 = $this->M_global->_namabarang($kode2);
			$row[] = $kode1;
			$row[] = $nama1;
			$row[] = $item->qty1;
			$row[] = $item->satuan1;
			$row[] = $kode2;
			$row[] = $nama2;
			$row[] = $item->qty2;
			$row[] = $item->satuan2;
			
			
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$item->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$item->id."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_konversi->count_all(),
						"recordsFiltered" => $this->M_konversi->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_konversi->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'kodeitem1'=> $this->input->post('kodeitem1'),
				'kodeitem2'=> $this->input->post('kodeitem2'),
				'satuan1'  => $this->input->post('satuan1'),
				'satuan2'  => $this->input->post('satuan2'),				
				'qty1'     => $this->input->post('qty1'),
				'qty2'     => $this->input->post('qty2'),
				'userid'   => $this->session->userdata('username'),
			);
		$insert = $this->M_konversi->save($data);
		
		
		
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'kodeitem1'=> $this->input->post('kodeitem1'),
				'kodeitem2'=> $this->input->post('kodeitem2'),
				'satuan1'  => $this->input->post('satuan1'),
				'satuan2'  => $this->input->post('satuan2'),				
				'qty1'     => $this->input->post('qty1'),
				'qty2'     => $this->input->post('qty2'),
				'userid'   => $this->session->userdata('username'),
				
			);
		$this->M_konversi->update(array('kodeitem1' => $this->input->post('kodeitem1'), 'kodeitem2' => $this->input->post('kodeitem2')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_konversi->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		
		if($this->input->post('kodeitem1') == '')
		{
			$data['inputerror'][] = 'kodeitem1';
			$data['error_string'][] = 'Nama barang belum dipilih';
			$data['status'] = FALSE;
		}
		
		if($this->input->post('kodeitem2') == '')
		{
			$data['inputerror'][] = 'kodeitem2';
			$data['error_string'][] = 'Nama barang belum dipilih';
			$data['status'] = FALSE;
		}

	
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	
	public function getinfoitem1($id)
	{
		$data = $this->M_barang->get_by_id($id);		
		echo json_encode($data);
	}
	
	
	
}

/* End of file master_data.php */
/* Location: ./application/controllers/master_data.php */