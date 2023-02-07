<?php 

defined('BASEPATH') or exit('No direct script access allowed');

class Master_barang_log extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('M_barang_log','M_barang_log');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '1000');
		$this->session->set_userdata('submenuapp', '1301');
	}

	public function index(){
		$cek = $this->session->userdata('level');				
		if(!empty($cek)){
			$data["ppn"] = $this->db->query("SELECT * FROM tbl_pajak")->row();
			$this->load->view('master/v_master_barang_log', $data);
		} else {
			header('location:'.base_url());
		}			
	}

	public function ajax_list(){
		$list = $this->M_barang_log->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit){
			$no++;
			$row = array();
			$row[] = $unit->kodebarang;
			$row[] = $unit->namabarang;
			$row[] = $unit->satuan1;
						
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$unit->id."'".')"><i class="glyphicon glyphicon-edit"></i> </a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$unit->id."'".')"><i class="glyphicon glyphicon-trash"></i> </a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_barang_log->count_all(),
						"recordsFiltered" => $this->M_barang_log->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id){
		$data = $this->M_barang_log->get_by_id($id);		
		echo json_encode($data);
	}

	public function ajax_add(){
		$this->_validate();
		$data = array(
				'kodebarang' => $this->input->post('kode'),
				'namabarang' => $this->input->post('nama'),
				'icgroup'	=> $this->input->post("inventorycat"),
				'jenisharga'	=> $this->input->post("jenisharga"),
				'kemasan'	=> $this->input->post("kemasan"),
				'satuan1' => $this->input->post('satuan'),
				'satuan2' => $this->input->post('satuan2'),
				'satuan3' => $this->input->post('satuan3'),
				'faktorsat2' => $this->input->post('faksatuan2'),
				'satuan2qty' => $this->input->post('qtysatuan2'),
				'faktorsat3' => $this->input->post('faksatuan3'),
				'satuan3qty' => $this->input->post('qtysatuan3'),
				'hargajual' => $this->input->post('hargajual'),
				'hargabeli' => $this->input->post('hargabeli'),
				'hpp' => $this->input->post('hpp'),
				'het' => $this->input->post('het'),
				'notax' => $this->input->post('ppn'),
				'hargabelippn' => $this->input->post('hargabelippn'),
				'minstock' => $this->input->post('minstock'),
				'maxstock' => $this->input->post('maxstock'),
				'jenis' => $this->input->post('jenis'),
				'subjenis' => $this->input->post('subjenis'),
				// 'vendor_id' => $this->input->post('vendor'),
				'userbuat' => user_login(),
				'tglbuat' => tglsystem(),
			);
		$insert = $this->M_barang_log->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update(){
		$this->_validate();
		$data = array(
			'kodebarang' 	=> $this->input->post('kode'),
			'namabarang' 	=> $this->input->post('nama'),
			'icgroup'		=> $this->input->post("inventorycat"),
			'jenisharga'	=> $this->input->post("jenisharga"),
			'satuan1' 		=> $this->input->post('satuan'),
			'satuan2' 		=> $this->input->post('satuan2'),
			'satuan3' 		=> $this->input->post('satuan3'),
			'faktorsat2' 	=> $this->input->post('faksatuan2'),
			'satuan2qty' 	=> $this->input->post('qtysatuan2'),
			'faktorsat3' 	=> $this->input->post('faksatuan3'),
			'satuan3qty' 	=> $this->input->post('qtysatuan3'),
			'hargajual' 	=> $this->input->post('hargajual'),
			'hargabeli' 	=> $this->input->post('hargabeli'),
			'hpp' 			=> $this->input->post('hpp'),
			'notax' 		=> $this->input->post('ppn'),
			'hargabelippn' 	=> $this->input->post('hargabelippn'),
			'minstock' 		=> $this->input->post('minstock'),
			'maxstock' 		=> $this->input->post('maxstock'),
			'jenis' 		=> $this->input->post('jenis'),
			// 'vendor_id' => $this->input->post('vendor'),
			'useredit' 		=> user_login(),
			'tgledit' 		=> tglsystem(),
			);
		$this->M_barang_log->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_barang_log->delete_by_id($id);
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

		if($this->input->post('nama') == '')
		{
			$data['inputerror'][] = 'nama';
			$data['error_string'][] = 'Nama masih kosong';
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
		  
		  $this->load->view('master/unit/v_master_unit_prn',$d);				
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
		  
		  $this->load->view('master/unit/v_master_unit_exp',$d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
}
