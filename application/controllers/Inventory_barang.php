<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_barang extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_barang','M_barang');
		$this->session->set_userdata('menuapp', '500');
		$this->session->set_userdata('submenuapp', '519');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{
			$data['cabang'] = $this->db->get("ms_unit");
			$data['rak'] = $this->db->get("inv_rak");
			$data['satuan'] = $this->db->get("inv_satuan");
			$data['gudang'] = $this->db->get("inv_gudang");
			$data['kateg'] = $this->db->get("inv_kategori");
			$data['subkateg'] = $this->db->get("inv_subkategori");
			$data['merk'] = $this->db->get("inv_merk");
			$data['supplier'] = $this->db->get("ap_supplier");
			$level=$this->session->userdata('level');		
		    $akses= $this->M_global->cek_menu_akses($level, 519);
		    $data['akses']= $akses;
			$this->load->view('inventory/v_inventory_barang',$data);
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list()
	{
		$level=$this->session->userdata('level');		
		$akses= $this->M_global->cek_menu_akses($level, 519);
		$list = $this->M_barang->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $barang) {
			$no++;
			$row = array();
			$row[] = $barang->kodeitem;
			$row[] = $barang->namabarang;
			//$row[] = number_format($barang->hargajual,0,',','.');
			$row[] = number_format($barang->hargabeli,0,',','.');
			$row[] = $barang->stok;
			$row[] = $barang->satuan;
			
			if($akses->uedit==1 && $akses->udel==1){
		    $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$barang->kodeitem."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$barang->kodeitem."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';					
			} else 
			if($akses->uedit==1 && $akses->udel==0){
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$barang->kodeitem."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
				      
            } else 
			if($akses->uedit==0 && $akses->udel==1){
			$row[] = 
				     ' <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$barang->kodeitem."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';						
			} else {
			$row[] = '';	
			}	
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_barang->count_all(),
						"recordsFiltered" => $this->M_barang->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_barang->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}
	
	public function getmargin()
	{
		$data = $this->M_barang->getmarginsetting();		
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'kdkategori' => $this->input->post('kateg'),
				'kdcbg' => $this->session->userdata('unit'),
				'kodeitem' => $this->input->post('kodeitem'),
				'namabarang' => $this->input->post('namabarang'),
				'stok_min' => $this->input->post('stokmin'),
				'stok_max' => $this->input->post('stokmax'),
				'stok' => $this->input->post('stok'),
				'satuan' => $this->input->post('satuan'),
				'kdgudang' => $this->input->post('gudang'),
				'kdrak' => $this->input->post('rak'),
				'hargajual' => $this->input->post('hargajual'),
				'hargabeli' => $this->input->post('hargabeli'),
				'ppn' => $this->input->post('ppn'),
				'kdmerk' => $this->input->post('merk'),
				'kode_user' => $this->session->userdata('username'),				
			);
		$insert = $this->M_barang->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'kdkategori' => $this->input->post('kateg'),
				'kdcbg' => $this->session->userdata('unit'),
				'kodeitem' => $this->input->post('kodeitem'),
				'namabarang' => $this->input->post('namabarang'),
				'stok_min' => $this->input->post('stokmin'),
				'stok_max' => $this->input->post('stokmax'),
				'stok' => $this->input->post('stok'),
				'satuan' => $this->input->post('satuan'),
				'kdgudang' => $this->input->post('gudang'),
				'kdrak' => $this->input->post('rak'),
				'hargajual' => $this->input->post('hargajual'),
				'hargabeli' => $this->input->post('hargabeli'),
				'ppn' => $this->input->post('ppn'),
				'kdmerk' => $this->input->post('merk'),
				
				
			);
		$this->M_barang->update(array('kodeitem' => $this->input->post('kodeitem')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_barang->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		
		if($this->input->post('kodeitem') == '')
		{
			$data['inputerror'][] = 'kodeitem';
			$data['error_string'][] = 'Harus Diisi';
			$data['status'] = FALSE;
		}

	
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	
	
	
}

