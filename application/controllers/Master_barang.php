<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_barang extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('M_barang','M_barang');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '1000');
		$this->session->set_userdata('submenuapp', '1206');
	}

    
	public function index(){
		$cek = $this->session->userdata('level');				
		if(!empty($cek))
		{
			$data["ppn"] = $this->db->query("SELECT * FROM tbl_pajak")->row();
			$this->load->view('master/v_master_barang',$data);
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list(){
		$list = $this->M_barang->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit) {
			$no++;
			$row = array();
			$row[] = $unit->kodebarang;
			$row[] = $unit->namabarang;
			$row[] = $unit->satuan1;
			$row[] = number_format($unit->hargabeli,0,',','.');
			$row[] = number_format($unit->hargajual,0,',','.');
						
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$unit->id."'".')"><i class="glyphicon glyphicon-edit"></i> </a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$unit->id."'".')"><i class="glyphicon glyphicon-trash"></i> </a>';
		
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

	public function ajax_edit($id){
		$data = $this->M_barang->get_by_id($id);		
		echo json_encode($data);
	}

    public function getdatamargin(){
		$barang = $this->input->get('barang');		
		echo $this->M_barang->_datamargin($barang);
	}
	
	public function ajax_add(){
		// $this->_validate();
		$kodebarang  = $this->input->post('kode');
		$data = array(
			'kodebarang'    => $this->input->post('kode'),
			'namabarang'    => $this->input->post('nama'),
			'icgroup'	=> $this->input->post("inventorycat"),
			'namageneric'   => $this->input->post('namageneric'),
			'pabrik'        => $this->input->post('pabrik'),
			'golongan'      => $this->input->post('golongan'),
			'kelasteraphi'  => $this->input->post('kelasterapi'),
			'jenisobat'     => $this->input->post('jenis'),
			'satuan1'       => $this->input->post('satuan'),
			'satuan2'       => $this->input->post('satuan2'),
			'satuan3'       => $this->input->post('satuan3'),
			'satuan2qty'       => $this->input->post('qtysatuan2'),
			'satuan3qty'       => $this->input->post('qtysatuan3'),
			'satuan2opr'       => $this->input->post('satuan2opr'),
			'satuan3opr'       => $this->input->post('satuan3opr'),
			'kemasan'		=> $this->input->post('kemasan'),
			'vat'           => $this->input->post('ppn'),
			'hargabeli' => $this->input->post('hna'),
			'hargabelippn' => $this->input->post('hnappn'),
			'hargajual'     => $this->input->post('hargajual'),
			'hpp'     => $this->input->post('hna'),
			'vendor_id'     => $this->input->post('vendor'),
			'hargatype'     => $this->input->post('jenisharga'),
			'tgledit'       => tglsystem(),
			'userbuat'      => user_login(),
			'tglbuat'       => tglsystem(),
		);
		// $jumdata =  count($this->input->post('td_data_1'));
		// $_cabang = $this->input->post('td_data_1');
		// $_margin = $this->input->post('td_data_2');
		// $_harga  = str_replace(",", "", $this->input->post('td_data_3'));
		
		
		$this->db->query("DELETE from tbl_barangcabang where kodebarang = '$kodebarang'");
		// $this->db->query("DELETE from tbl_barangdterima where terima_no = '$nobukti'");

		// foreach($_cabang as $key_cab => $valcab){
		// 	if($_harga[$key_cab] != "0"){
		// 		$this->db->query("INSERT INTO tbl_barangcabang (koders,kodebarang,margin,hargajual) 
		// 		VALUES ('". $_cabang[$key_cab] ."','$kodebarang','". $_margin[$key_cab] ."','". $_harga[$key_cab] ."')");
		// 	}
		// }
		  
		// for($i=0;$i<$jumdata-1;$i++){
		// 	$datad = array(
		// 	  'koders' => $_cabang[$i],
		// 	  'margin' => $_margin[$i],
		// 	  'hargajual' => $_harga[$i],
			  
		// 	);
			
		// 	$this->db->insert('tbl_barangcabang',$datad);
		// }
		
		
		
		$insert = $this->M_barang->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update(){
		$this->_validate();
		$kodebarang  = $this->input->post('kode');

		$data = array(
			'kodebarang'    => $this->input->post('kode'),
			'namabarang'    => $this->input->post('nama'),
			'icgroup'	=> $this->input->post("inventorycat"),
			'namageneric'   => $this->input->post('namageneric'),
			'pabrik'        => $this->input->post('pabrik'),
			'golongan'      => $this->input->post('golongan'),
			'kelasteraphi'  => $this->input->post('kelasterapi'),
			'jenisobat'     => $this->input->post('jenis'),
			'satuan1'       => $this->input->post('satuan'),
			'satuan2'       => $this->input->post('satuan2'),
			'satuan3'       => $this->input->post('satuan3'),
			'satuan2qty'       => $this->input->post('qtysatuan2'),
			'satuan3qty'       => $this->input->post('qtysatuan3'),
			'satuan2opr'       => $this->input->post('satuan2opr'),
			'satuan3opr'       => $this->input->post('satuan3opr'),
			'kemasan'		=> $this->input->post('kemasan'),
			'vat'           => $this->input->post('ppn'),
			'hargabeli' => $this->input->post('hna'),
			'hargabelippn' => $this->input->post('hnappn'),
			'hargajual'     => $this->input->post('hargajual'),
			'hpp'     => $this->input->post('hna'),
			'vendor_id'     => $this->input->post('vendor'),
			'hargatype'     => $this->input->post('jenisharga'),
			'tgledit'       => tglsystem(),
			'userbuat'      => user_login(),
			'tglbuat'       => tglsystem(),
		);
		$this->M_barang->update(array('id' => $this->input->post('id')), $data);
		$this->db->query("delete from tbl_barangcabang where kodebarang='$kodebarang'");
		
		$_cabang = $this->input->post('td_data_1');
		$_margin = $this->input->post('td_data_2');
		$_harga  = str_replace(",", "", $this->input->post('td_data_3'));
		// $jumdata =  count($_cabang);
		
		foreach($_cabang as $key_cab => $valcab){
			if($_harga[$key_cab] != "0"){
				$this->db->query("INSERT INTO tbl_barangcabang (koders,kodebarang,margin,hargajual) 
				VALUES ('". $_cabang[$key_cab] ."','$kodebarang','". $_margin[$key_cab] ."','". $_harga[$key_cab] ."')");
			}
		}
		
		// for($i=0;$i<=$jumdata-1;$i++){
		// 	$datad = array(
		// 	  'koders' => $_cabang[$i],
		// 	  'margin' => $_margin[$i],
		// 	  'kodebarang' => $kodebarang,
		// 	  'hargajual' => str_replace(',','',$_harga[$i]),
			  
		// 	);
			
		// 	$this->db->insert('tbl_barangcabang',$datad);
		// }
		
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id){
		$this->M_barang->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate(){
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
	
	public function cetak(){
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
	
	
	public function export(){
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
