<?php 

defined('BASEPATH') or exit('No direct script access allowed');

class Master_barang_log extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('M_barang_log','M_barang_log');
		$this->load->model('M_template_cetak','M_template_cetak');
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

	function ctk($cekpdf=0)
	{

		// $cekpdf   = 1;
		$position = 'L';
		$date     = '-';
		$body     = '';

		$body.= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
			<tr>
				<td style=\"border:0\" colspan=\"29\" align=\"center\"><br></td>                
			</tr>
			<tr>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>kodebarang</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>namabarang</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>icgroup</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>golongan</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>jenis</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>subjenis</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>jenisharga</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>kemasan</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>satuan1</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>satuan2</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>satuan3</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>satuan2qty</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>satuan3qty</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>hargabeli</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>hargabelippn</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>hargajual</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>hpp</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>minstock</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>maxstock</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>useredit</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>tgledit</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>userbuat</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>tglbuat</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>het</b></td>
			</tr>";

			$sql =$this->db->query(
				"SELECT*FROM tbl_logbarang order by kodebarang")->result();


			$lcno          = 0;
			$kodebarang    = '';
			$namabarang    = '';
			$icgroup       = '';
			$golongan      = '';
			$jenis         = '';
			$subjenis      = '';
			$jenisharga    = '';
			$kemasan       = '';
			$satuan1       = '';
			$satuan2       = '';
			$satuan3       = '';
			$satuan2qty    = '';
			$satuan3qty    = '';
			$hargabeli     = '';
			$hargabelippn  = '';
			$hargajual     = '';
			$hpp           = '';
			$minstock      = '';
			$maxstock      = '';
			$useredit      = '';
			$tgledit       = '';
			$userbuat      = '';
			$tglbuat       = '';
			$het           = '';

			




			foreach ($sql as $row) {
				$lcno         = $lcno + 1;
				$kodebarang   = $row->kodebarang;
				$namabarang   = $row->namabarang;
				$icgroup      = $row->icgroup;
				$golongan     = $row->golongan;
				$jenis        = $row->jenis;
				$subjenis     = $row->subjenis;
				$jenisharga   = $row->jenisharga;
				$kemasan      = $row->kemasan;
				$satuan1      = $row->satuan1;
				$satuan2      = $row->satuan2;
				$satuan3      = $row->satuan3;
				$satuan2qty   = $row->satuan2qty;
				$satuan3qty   = $row->satuan3qty;
				$hargabeli    = $row->hargabeli;
				$hargabelippn = $row->hargabelippn;
				$hargajual    = $row->hargajual;
				$hpp          = $row->hpp;
				$minstock     = $row->minstock;
				$maxstock     = $row->maxstock;
				$useredit     = $row->useredit;
				$tgledit      = $row->tgledit;
				$userbuat     = $row->userbuat;
				$tglbuat      = $row->tglbuat;
				$het          = $row->het;

				$body .= "<tr>

						<td align=\"center\">$kodebarang</td>
						<td align=\"center\">$namabarang</td>
						<td align=\"center\">$icgroup</td>
						<td align=\"center\">$golongan</td>
						<td align=\"center\">$jenis</td>
						<td align=\"center\">$subjenis</td>
						<td align=\"center\">$jenisharga</td>
						<td align=\"center\">$kemasan</td>
						<td align=\"center\">$satuan1</td>
						<td align=\"center\">$satuan2</td>
						<td align=\"center\">$satuan3</td>
						<td align=\"center\">$satuan2qty</td>
						<td align=\"center\">$satuan3qty</td>
						<td align=\"center\">$hargabeli</td>
						<td align=\"center\">$hargabelippn</td>
						<td align=\"center\">$hargajual</td>
						<td align=\"center\">$hpp</td>
						<td align=\"center\">$minstock</td>
						<td align=\"center\">$maxstock</td>
						<td align=\"center\">$useredit</td>
						<td align=\"center\">$tgledit</td>
						<td align=\"center\">$userbuat</td>
						<td align=\"center\">$tglbuat</td>
						<td align=\"center\">$het</td>
						</tr>"; 
			
			}

			$body  .= "</table>";
			$judul  = 'DATA MASTER LOG BARANG';

			$this->M_template_cetak->template($judul, $body, $position, $date, $cekpdf);
		
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
