<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_barang extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('M_barang','M_barang');
		$this->load->model('M_cetak');
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
		$jenis = $this->input->get('jenis');		
		if($jenis==1){
		  $filter = '';
		} else {
		  $kd       = $this->input->get('kd');
		  $nm       = $this->input->get('nm');
		  $sat      = $this->input->get('sat');
		  $filter   = $kd.'~'.$nm.'~'.$sat;
		}  

		$list = $this->M_barang->get_datatables( $filter );
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
				<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$unit->id."'".",'".$unit->kodebarang."'".",'".$unit->namabarang."'".')"><i class="glyphicon glyphicon-trash"></i> </a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_barang->count_all( $filter ),
						"recordsFiltered" => $this->M_barang->count_filtered( $filter ),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function getmargin($kodebarang) {
		$nilai_persediaan = $this->db->query("SELECT koders, (SUM(totalrp)/SUM(qty_terima)) AS rata FROM tbl_barangdterima WHERE kodebarang = '$kodebarang' GROUP BY koders, kodebarang")->result();
		foreach ($nilai_persediaan as $np) { ?>
			<tr>
				<td><?= $np->koders; ?></td>
				<td class="text-right"><?= number_format($np->rata, 2); ?></td>
				<td class="text-right">0</td>
				<td class="text-right">0</td>
			</tr>
		<?php }
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
			'leadtime'		=> $this->input->post('leadtime'),
			'discc'		=> $this->input->post('disccash'),
			'minstock'		=> $this->input->post('minstock'),
			'reorder'		=> $this->input->post('reorderlevel'),
			'vat'           => $this->input->post('ppn'),
			'hargabeli' => $this->input->post('hna'),
			'hargabelippn' => $this->input->post('hnappn'),
			'hargajual'     => $this->input->post('hargajual'),
			'hpp'     => $this->input->post('hna'),
			'het'     => $this->input->post('het'),
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
			'kodebarang'   => $this->input->post('kode'),
			'namabarang'   => $this->input->post('nama'),
			'icgroup'      => $this->input->post("inventorycat"),
			'namageneric'  => $this->input->post('namageneric'),
			'pabrik'       => $this->input->post('pabrik'),
			'golongan'     => $this->input->post('golongan'),
			'kelasteraphi' => $this->input->post('kelasterapi'),
			'jenisobat'    => $this->input->post('jenis'),
			'satuan1'      => $this->input->post('satuan'),
			'satuan2'      => $this->input->post('satuan2'),
			'satuan3'      => $this->input->post('satuan3'),
			'leadtime'     => $this->input->post('leadtime'),
			'discc'     => $this->input->post('disccash'),
			'reorder'     => $this->input->post('reorderlevel'),
			'minstock'     => $this->input->post('minstock'),
			'satuan2qty'   => $this->input->post('qtysatuan2'),
			'satuan3qty'   => $this->input->post('qtysatuan3'),
			'satuan2opr'   => $this->input->post('satuan2opr'),
			'satuan3opr'   => $this->input->post('satuan3opr'),
			'kemasan'      => $this->input->post('kemasan'),
			'vat'          => $this->input->post('ppn'),
			'hargabeli'    => $this->input->post('hna'),
			'hargabelippn' => $this->input->post('hnappn'),
			'hargajual'    => $this->input->post('hargajual'),
			'hpp'          => $this->input->post('hna'),
			'het'          => $this->input->post('het'),
			'vendor_id'    => $this->input->post('vendor'),
			'hargatype'    => $this->input->post('jenisharga'),
			'tgledit'      => tglsystem(),
			'userbuat'     => user_login(),
			'tglbuat'      => tglsystem(),
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
	
	function cetak($cekpdf = ''){

		$cek        = $cekpdf;
		$chari      = '';
		$cekk       = $this->session->userdata('level');
		$unit       = $this->session->userdata('unit');
		$avatar     = $this->session->userdata('avatar_cabang');

		if (!empty($cekk)) {
			$kop       = $this->M_cetak->kop($unit);
			$namars    = $kop['namars'];
			$alamat    = $kop['alamat'];
			$alamat2   = $kop['alamat2'];
			$phone     = $kop['phone'];
			$whatsapp  = $kop['whatsapp'];
			$npwp      = $kop['npwp'];

			$chari .= "<table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
				<thead>
					<tr>
						<td rowspan=\"6\" align=\"center\">
							<img src=\"" . base_url() . "assets/img_user/$avatar\"  width=\"70\" height=\"70\" />
						</td>
						<td colspan=\"20\">
							<b>
							<tr>
								<td style=\"font-size:10px;border-bottom: none;\"><b><br>$namars</b></td>
							</tr>
							<tr>
								<td style=\"font-size:9px;\">$alamat</td>
							</tr>
							<tr>
								<td style=\"font-size:9px;\">$alamat2</td>
							</tr>
							<tr>
								<td style=\"font-size:9px;\">Wa :$whatsapp    Telp :$phone </td>
							</tr>
							<tr>
								<td style=\"font-size:9px;\">No. NPWP : $npwp</td>
							</tr>
							</b>
						</td>
					</tr>
			</table>";

			
			$chari .= "<table style=\"border-collapse:collapse;\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
				<tr>
					<td align=\"center\" style=\"font-size:18px;\"><b>&nbsp;</b>
					</td>
				</tr> 
				<tr >
					<td align=\"center\" style=\"border: 1px solid #000;font-size:18px;\"><b></b>
					</td>
				</tr>
			</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
					
				<thead>
				<tr>
					<td colspan=\"21\" style=\"text-align:center;font-size:22px;border-bottom: none;color:#120292;\"><br><b>LAPORAN MASTER BARANG</b></td>
				</tr> 
				
				
			</table>";
			
			$chari .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:10px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
				<thead>
					<tr>
						<td style=\"border:0\" align=\"center\"><br></td>                
					</tr>
					<tr>       
						<td bgcolor=\"#cccccc\" width=\"10%\" align=\"center\"><b>No.</b></td>
						<td bgcolor=\"#cccccc\" width=\"20%\" align=\"center\"><b>KODE BARANG</b></td>
						<td bgcolor=\"#cccccc\" width=\"30%\" align=\"center\"><b>NAMA BARANG</b></td>
						<td bgcolor=\"#cccccc\" width=\"10%\" align=\"center\"><b>SATUAN</b></td>
						<td bgcolor=\"#cccccc\" width=\"15%\" align=\"center\"><b>HARGA BELI</b></td>
						<td bgcolor=\"#cccccc\" width=\"15%\" align=\"center\"><b>HARGA JUAL</b></td>
					</tr>
					
			</thead>";
		
				$data_stok     = $this->db->query("SELECT * from tbl_barang ORDER BY kodebarang")->result();

				$lcno=0;
				foreach ($data_stok as $row) {
					$lcno          = $lcno + 1;
					$kodebarang    = $row->kodebarang;
					$namabarang    = $row->namabarang;
					$satuan1       = $row->satuan1;
					if($cek=='1'){
						$hargabeli     = number_format($row->hargabeli,0,',','.');
						$hargajual     = number_format($row->hargajual,0,',','.');
					}else{
						$hargabeli     = $row->hargabeli;
						$hargajual     = $row->hargajual;
					}
					

					$chari .= "<tr>
					<td align=\"center\">$lcno</td>
					<td align=\"left\">$kodebarang </td>
					<td align=\"left\">$namabarang</td>
					<td align=\"left\">$satuan1  </td>
					<td align=\"right\">$hargabeli </td>
					<td align=\"right\">$hargajual</td>
					</tr>
					";

				}

				$chari .= "</table>";

				$data['prev'] = $chari;
				$judul        = 'LAPORAN DATA BARANG';

			switch ($cek) {
				case 0;
					echo ("<title>LAPORAN DATA BARANG</title>");
					echo ($chari);
					break;

				case 1;
					$this->M_cetak->mpdf('P', 'A4', $judul, $chari, 'LAPORAN_DATA_BARANG.PDF', 10, 10, 10, 2);
					break;
				case 2;
					header("Cache-Control: no-cache, no-store, must-revalidate");
					header("Content-Type: application/vnd-ms-excel");
					header("Content-Disposition: attachment; filename= $judul.xls");
					$this->load->view('app/master_cetak', $data);
					break;
			}
		} else {

			header('location:' . base_url());
		}
	}
	
	public function export(){
		$cek = $this->session->userdata('level');		
		if(!empty($cek)){				  
			$page=$this->uri->segment(3);
			$limit=$this->config->item('limit_data');	
			$d['master'] = $this->db->get("ms_unit");
			$d['nama_usaha']=$this->config->item('nama_perusahaan');
			$d['alamat']=$this->config->item('alamat_perusahaan');
			$d['motto']=$this->config->item('motto');
			
			$this->load->view('master/unit/v_master_unit_exp',$d);				
		} else{
			header('location:'.base_url());
		}
	}
	
}
