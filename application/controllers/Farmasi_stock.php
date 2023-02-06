<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Farmasi_stock extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_farmasi_stock', 'M_farmasi_stock');
		$this->load->model('M_KartuStock');
		$this->load->model('M_cetak');
		$this->load->helper('simkeu_rpt');
		$this->load->helper('simkeu_nota');
		$this->session->set_userdata('menuapp', '3000');
		$this->session->set_userdata('submenuapp', '33010');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$data['poli'] = $this->db->get('tbl_namapos')->result();
			$cabang = $this->session->userdata('unit');
			if ($cabang == "") {
				$cabang = "DPS";
			}
			$data['cabang'] = $cabang;
			$data['gudang'] = $this->db->query('select gudang from tbl_barangstock group by gudang')->result();
			$this->load->view('farmasi/v_farmasi_stock', $data);
		} else {
			header('location:' . base_url());
		}
	}

	public function index_tarif($cabang)
	{
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$data['poli'] = $this->db->get('tbl_namapos')->result();
			$data['cabang'] = $cabang;
			$this->load->view('master/v_master_tarif_cabang', $data);
		} else {
			header('location:' . base_url());
		}
	}


	function cek_master($cabang)
	{
		$data = $this->db->query("select count(*) as jumlah from tbl_tarif where koders = '$cabang'")->row();
		if ($data->jumlah < 1) {
			$this->db->query("insert into tbl_tarif(koders, kodetarif) select '$cabang', kodetarif from tbl_tarifh");
		} else {
			$this->db->query("insert into tbl_tarif(koders, kodetarif) select '$cabang', kodetarif from tbl_tarifh where kodetarif not in(select kodetarif from tbl_tarif where koders = '$cabang')");
		}
	}

	public function ajax_list()
	{
		$cabang = $this->input->get('cabang');
		$gudang = $this->input->get('gudang');
		$this->cek_master($cabang);
		$list = $this->M_farmasi_stock->get_datatables($cabang, $gudang);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit) {
			$no++;
			$row = array();
			$row[] = $unit->koders;
			$row[] = $unit->kodebarang;
			$row[] = $unit->namabarang;
			// $row[] = angka_rp($unit->saldoawal, 0);
			// $row[] = angka_rp($unit->sesuai, 0);
			// $row[] = angka_rp($unit->terima, 0);
			// $row[] = angka_rp($unit->keluar, 0);
			// $row[] = angka_rp($unit->hasilso, 0);
			$sesuaiz = $this->db->query("SELECT sum(sesuai) as sesuai FROM tbl_aposesuai WHERE kodebarang = '$unit->kodebarang' AND gudang = '$unit->gudang' AND koders = '$unit->koders'")->row();
			$so_done = $unit->saldoakhir;
			$row[] = $unit->satuan1;
			$row[] = '<div class="text-right">'.number_format($so_done, 0).'</div>';
			$row[] = $unit->gudang;

			$row[] = '<div class="text-center">
				<button class="btn btn-sm btn-primary" type="button" title="Show" onclick="show(' . "'" . $unit->id . "'" . ')"><i class="glyphicon glyphicon-eye-open"></i></button>
				<button class="btn btn-sm btn-secondary" type="button" title="Valid" onclick="valid(' . "'" . $unit->id . "'" . ')"><i class="glyphicon glyphicon-refresh"></i> </button>
			</div>';
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->M_farmasi_stock->count_all(),
			"recordsFiltered" => $this->M_farmasi_stock->count_filtered($cabang, $gudang),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_farmasi_stock->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
			'kodetarif' => $this->input->post('kode'),
			'tindakan' => $this->input->post('nama'),
			'kodepos' => $this->input->post('poli'),
		);
		$insert = $this->M_farmasi_stock->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		//$this->_validate();

		$poli    =  str_replace(',', '', $this->input->post('klinik'));
		$dokter  =  str_replace(',', '', $this->input->post('dokter'));
		$obat    =  str_replace(',', '', $this->input->post('bhp'));
		$perawat =  str_replace(',', '', $this->input->post('perawat'));

		$total   = $poli + $dokter + $obat + $perawat;
		$data = array(
			'kodetarif' => $this->input->post('kode'),
			'tarifrspoli' => $poli,
			'tarifdrpoli' => $dokter,
			'obatpoli' => $obat,
			'feemedispoli' => $perawat,
			'cost' => $total,

		);
		$this->M_farmasi_stock->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_farmasi_stock->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('poli') == '') {
			$data['inputerror'][] = 'poli';
			$data['error_string'][] = 'Poli harus diisi';
			$data['status'] = FALSE;
		}

		if ($this->input->post('kode') == '') {
			$data['inputerror'][] = 'kode';
			$data['error_string'][] = 'Kode harus diisi';
			$data['status'] = FALSE;
		}

		if ($this->input->post('nama') == '') {
			$data['inputerror'][] = 'nama';
			$data['error_string'][] = 'Nama masih kosong';
			$data['status'] = FALSE;
		}


		if ($data['status'] === FALSE) {
			echo json_encode($data);
			exit();
		}
	}

	public function cetak2($gudang="",$cekpdf="")
	{
		$gudang   = $gudang;
		$cek      = $this->session->userdata('level');
		$unit     = $this->session->userdata('unit');
		if (!empty($cek)) {
			$profile       = $this->M_global->_LoadProfileLap();
			$unit          = $this->session->userdata('unit');
			$nama_usaha    = $profile->nama_usaha;
			$alamat1       = $profile->alamat1;
			$alamat2       = $profile->alamat2;

			$qheader       = "SELECT * from tbl_barangstock where gudang = '$gudang'";
			$header        = $gudang;


			// echo json_encode($data_tes, JSON_PRETTY_PRINT);

			$profile       = data_master('tbl_namers', array('koders' => $unit));
			$nama_usaha    = $profile->namars;
			$alamat1       = $profile->alamat;
			$alamat2       = $profile->kota;

			$judulx        = $this->db->get_where('tbl_depo', ['depocode' => $gudang])->row_array();

			$data_stok     = $this->db->query('SELECT tbl_barangstock.*,tbl_barang.namabarang, tbl_barang.satuan1 from tbl_barangstock join tbl_barang on tbl_barang.kodebarang=tbl_barangstock.kodebarang where tbl_barangstock.koders = "' . $unit . '" and tbl_barangstock.gudang ="' . $gudang . '"')->result();

			$pdf = new simkeu_nota();
			// header perusahaan
			$pdf->setID($nama_usaha, $alamat1, $alamat2);
			// header judul
			$pdf->setjudul('LAPORAN ' . $judulx['keterangan'] . ' CABANG ' . $unit);
			// header sub judul
			$pdf->setsubjudul('');
			// paper type and orientattion
			$pdf->addpage("P", "A4");
			$pdf->setsize("P", "A4");
			// set font title and size
			$pdf->SetFont('Arial', 'B', 16);
			$pdf->ln(2);

			// grid kolom
			$pdf->SetWidths(array(6, 20.5, 20.5, 20.5, 20.5, 20.5, 20.5, 20.5, 20.5, 21));
			// grid border kolom
			$border = array('TB', 'TB', 'TB', 'TB', 'TB', 'TB', 'TB', 'TB', 'TB', 'TB');
			// text title position
			$align  = array('L', 'L', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R');
			// set font text title and size
			$pdf->setfont('Arial', 'B', 6);
			// text position
			$pdf->SetAligns(array('L', 'L', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
			// unknow
			$fc = array('0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
			// set text title
			$judul = array('No', 'KODE BARANG', 'NAMA BARANG', 'SALDO AWAL', 'SESUAI', 'TERIMA', 'KELUAR', 'HASIL SO', 'SALDO AKHIR', 'SATUAN');
			// unknow
			$pdf->FancyRow2(10, $judul, $fc, $border, $align);
			// set border text
			$border = array('', '', '', '', '', '', '', '', '', '');
			// set font text
			$pdf->setfont('Arial', '', 6);
			// set color text
			$pdf->SetFillColor(0, 0, 139);
			$pdf->settextcolor(0);
			// set number 1
			$no = 1;
			// looping data
			foreach ($data_stok as $ds) {
				// show text in kolom
				$pdf->FancyRow(array(
					$no++,
					$ds->kodebarang,
					$ds->namabarang,
					number_format($ds->saldoawal, 0, ',', '.'),
					number_format($ds->sesuai, 0, ',', '.'),
					number_format($ds->terima, 0, ',', '.'),
					number_format($ds->keluar, 0, ',', '.'),
					number_format($ds->hasilso, 0, ',', '.'),
					number_format($ds->saldoakhir, 0, ',', '.'),
					$ds->satuan1
				), $fc, $border, $align);
			}
			// $border = array('TB','TB','TB','TB','TB','TB','TB','TB','TB','TB');
			// $pdf->FancyRow(array('','','','','','','','','',''),$fc, $border, $align);
			// $align  = array('L','R','L','L','L','L','L','L','L','L','L','L','L');
			// $pdf->setfont('Arial','B',6);
			// $pdf->AliasNbPages();

			// showing result
			$pdf->Output('Laporan gudang ' . $gudang . '.PDF', 'I');
		} else {

			header('location:' . base_url());
		}

		//exel
		// $data['prev'] = $chari;
		// $judul          = 'LAPORAN HARIAN KASIR PENDAFTARAN PER SHIFT';
		// switch ($cekpdf) {
		// 	case 0;
		// 		echo ("<title>DATA GLOBAL SKI</title>");
		// 		echo ($chari);
		// 		break;
		// 	case 1;
		// 		$this->M_cetak->mpdf('L', 'A3', $judul, $chari, 'KASIR-01.PDF', 10, 10, 10, 1);
		// 		break;
		// 	case 2;
		// 		header("Cache-Control: no-cache, no-store, must-revalidate");
		// 		header("Content-Type: application/vnd-ms-excel");
		// 		header("Content-Disposition: attachment; filename= $judul.xls");
		// 		$this->load->view('app/master_cetak', $data);
		// 		break;
		// }
	}

	function cetak($gudang = '', $cekpdf = '')
	{

		// $cek          = $this->input->get('cekk');
		$cek        = $cekpdf;
		$chari      = '';
		$cekk       = $this->session->userdata('level');
		$unit       = $this->session->userdata('unit');
		$unit       = $this->session->userdata('unit');
		$avatar     = $this->session->userdata('avatar_cabang');
		$profile    = $this->M_global->_LoadProfileLap();
		$unit       = $this->session->userdata('unit');
		$nama_usaha = $profile->nama_usaha;
		$alamat1    = $profile->alamat1;
		$alamat2    = $profile->alamat2;

		$qheader    = "SELECT * from tbl_barangstock where gudang = '$gudang'";
		$header     = $gudang;


		// echo json_encode($data_tes, JSON_PRETTY_PRINT);

		$profile       = data_master('tbl_namers', array('koders' => $unit));
		$nama_usaha    = $profile->namars;
		$alamat1       = $profile->alamat;
		$alamat2       = $profile->kota;

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
			<img src=\"" . base_url() . "assets/img_user/$avatar\"  width=\"70\" height=\"70\" /></td>

			<td colspan=\"20\"><b>
				<tr><td style=\"font-size:12px;border-bottom: none;\"><b>$namars</b></td></tr>
				<tr><td style=\"font-size:11px;\">$alamat</td></tr>
				<tr><td style=\"font-size:11px;\">$alamat2</td></tr>
				<tr><td style=\"font-size:11px;\">Wa :$whatsapp    Telp :$phone </td></tr>
				<tr><td style=\"font-size:11px;\">No. NPWP : $npwp</td></tr>

			</b></td>
		</tr> 
		
		</table>";

		$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
					
			<thead>
			<tr>
				<td colspan=\"10\" style=\"text-align:center;font-size:22px;border-bottom: none;color:#120292;\"><b><br></b></td>
			</tr> 
			<tr>
				<td colspan=\"10\" style=\"text-align:center;font-size:22px;border-bottom: none;color:#120292;\"><b>LAPORAN DAFTAR STOCK CABANG $unit</b></td>
			</tr> 
				
			
			</table>";

		$chari .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:10px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">

		<thead>
			<tr>
				<td style=\"border:0\" align=\"center\"><br></td>                
			</tr>
			<tr>       
				<td bgcolor=\"#cccccc\" width=\"5%\" align=\"center\"><b>No.</b></td>
				<td bgcolor=\"#cccccc\" width=\"10%\" align=\"center\"><b>KODE BARANG</b></td>
				<td bgcolor=\"#cccccc\" width=\"23%\" align=\"center\"><b>NAMA BARANG</b></td>
				<td bgcolor=\"#cccccc\" width=\"10%\" align=\"center\"><b>SALDO AWAL</b></td>
				<td bgcolor=\"#cccccc\" width=\"8%\" align=\"center\"><b>SESUAI</b></td>
				<td bgcolor=\"#cccccc\" width=\"8%\" align=\"center\"><b>MASUK</b></td>
				<td bgcolor=\"#cccccc\" width=\"8%\" align=\"center\"><b>KELUAR</b></td>
				<td bgcolor=\"#cccccc\" width=\"8%\" align=\"center\"><b>HASIL SO</b></td>
				<td bgcolor=\"#cccccc\" width=\"10%\" align=\"center\"><b>SALDO AKHIR</b></td>
				<td bgcolor=\"#cccccc\" width=\"10%\" align=\"center\"><b>SATUAN</b></td>
			</tr>
			
		</thead>";

		$data_stok     = $this->db->query("SELECT tbl_barangstock.*,tbl_barang.namabarang, tbl_barang.satuan1 from tbl_barangstock join tbl_barang on tbl_barang.kodebarang=tbl_barangstock.kodebarang where tbl_barangstock.koders = '$unit' and tbl_barangstock.gudang ='$gudang'")->result();

		$lcno=0;
		foreach ($data_stok as $row) {
			$lcno          = $lcno + 1;
			$kodebarang    = $row->kodebarang;
			$namabarang    = $row->namabarang;
			$satuan1       = $row->satuan1;
			if($cek==1){
				$saldoawal     = number_format($row->saldoawal, 0, ',', '.');
				$sesuai        = number_format($row->sesuai, 0, ',', '.');
				$terima        = number_format($row->terima, 0, ',', '.');
				$keluar        = number_format($row->keluar, 0, ',', '.');
				$hasilso       = number_format($row->hasilso, 0, ',', '.');
				$saldoakhir    = number_format($row->saldoakhir, 0, ',', '.');
			}else{
				$saldoawal     = ceil($row->saldoawal);
				$sesuai        = ceil($row->sesuai);
				$terima        = ceil($row->terima);
				$keluar        = ceil($row->keluar);
				$hasilso       = ceil($row->hasilso);
				$saldoakhir    = ceil($row->saldoakhir);
			}
			

			$chari .= "<tr>
			<td style=\"\" align=\"center\">$lcno</td>
			<td style=\"\" align=\"left\">$kodebarang </td>
			<td style=\"\" align=\"left\">$namabarang</td>
			<td style=\"\" align=\"left\">$saldoawal  </td>
			<td style=\"\" align=\"left\">$sesuai </td>
			<td style=\"\" align=\"left\">$terima</td>
			<td style=\"\" align=\"left\">$keluar</td>
			<td style=\"\" align=\"left\">$hasilso  </td>
			<td style=\"\" align=\"left\">$saldoakhir </td>
			<td style=\"\" align=\"left\">$satuan1</td>
			</tr>
			";

		}

		$chari .= "</table>";

		$data['prev'] = $chari;
		$judul        = 'LAPORAN DAFTAR STOCK';

			switch ($cek) {
				case 0;
					echo ("<title>LAPORAN DAFTAR STOCK</title>");
					echo ($chari);
					break;

				case 1;
					$this->M_cetak->mpdf('P', 'A4', $judul, $chari, 'LAPORAN_DAFTAR_STOCK.PDF', 10, 10, 10, 2);
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

	public function export()
	{
		$cek	= $this->session->userdata('level');
		$cabang	= $this->session->userdata('unit');
		$gudang = $this->input->get('gudang');
		if (!empty($cek)) {
			$query = 'SELECT tbl_barangstock.*,tbl_barang.namabarang, tbl_barang.satuan1 from tbl_barangstock join tbl_barang on tbl_barang.kodebarang=tbl_barangstock.kodebarang where tbl_barangstock.koders = "' . $cabang . '" and tbl_barangstock.gudang = "' . $gudang . '" order by id desc';
			// $d['data'] = $this->db->query($query)->result();
			$d = [
				'data'  => $this->db->query($query)->result(),
				'judul' => $this->input->get('gudang'),

			];
			$this->load->view('farmasi/v_farmasi_export', $d);
		} else {
			header('location:' . base_url());
		}
	}
	public function show(){
		$cek    = $this->session->userdata('level');
		$cabang = $this->session->userdata('unit');
		$avatar = $this->session->userdata('avatar_cabang');
		$id     = $this->input->get('id');
		if (!empty($cek)) {
			$kop           = $this->M_cetak->kop($cabang);
			$stok_b        = $this->db->get_where("tbl_barangstock", ["id"=>$id])->row();
			$datagudang    = $this->db->get_where("tbl_depo", ['depocode' => $stok_b->gudang])->row();
			$databarang    = $this->db->get_where("tbl_barang", ['kodebarang' => $stok_b->kodebarang])->row();
			$datars        = $this->db->get_where("tbl_namers", ['koders' => $cabang])->row();
			$namars        = $kop['namars'];
			$alamat        = $kop['alamat'];
			$alamat2       = $kop['alamat2'];
			$phone         = $kop['phone'];
			$whatsapp      = $kop['whatsapp'];
			$npwp          = $kop['npwp'];
			$chari         = '';
			$chari .= "
					<table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
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
			$chari .= "
								<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
									<tr>
										<td> &nbsp; </td>
									</tr> 
								</table>";
			$chari .= "
							<table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\">     
								<tr>
									<td style=\"border-top: none;border-right: none;border-left: none;\"></td>
								</tr> 
							</table>";
			$chari .= "
							<table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\">     
								<tr>
									<td style=\"border-top: none;border-right: none;border-left: none;\"></td>
								</tr> 
							</table>";
			$chari .= "
							<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
								<tr>
									<td> &nbsp; </td>
								</tr> 
							</table>";
			$chari .= "
							<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
								<tr>
									<td width=\"15%\" style=\"text-align:center; font-size:20px;\"><b>KARTU STOK CABANG $cabang ($datars->kota)</b></td>
								</tr>
							</table>";
			$chari .= "
							<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
								<tr>
									<td> &nbsp; </td>
								</tr> 
							</table>";
			$chari .= "
							<table style=\"border-collapse:collapse;font-family: tahoma; font-size:14px\" width=\"100%\" align=\"center\" border=\"0\">
								<tr>
									<td width=\"10%\">Cabang</td>
									<td width=\"1%\"> : </td>
									<td> $cabang ($datars->kota) </td>
								</tr> 
								<tr>
									<td width=\"10%\">Gudang</td>
									<td width=\"1%\"> : </td>
									<td> $datagudang->keterangan </td>
								</tr> 
								<tr>
									<td width=\"10%\">Kode Barang</td>
									<td width=\"1%\"> : </td>
									<td> $databarang->kodebarang </td>
								</tr> 
								<tr>
									<td width=\"10%\">Nama Barang</td>
									<td width=\"1%\"> : </td>
									<td> $databarang->namabarang </td>
								</tr> 
							</table>";
			$chari .= "
							<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
								<tr>
									<td> &nbsp; </td>
								</tr> 
							</table>";
			$chari .= "
							<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
								<tr>
									<td> &nbsp; </td>
								</tr> 
							</table>";
			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">
							<tr>
										<th width=\"5%\">No</th>
										<th width=\"20%\">No.Bukti</th>
										<th width=\"10%\">Tanggal</th>
										<th width=\"10%\">Keterangan</th>
										<th width=\"15%\">Rekanan</th>
										<th width=\"10%\">Nilai Pembelian</th>
										<th width=\"5%\">Terima</th>
										<th width=\"5%\">Keluar</th>
										<th width=\"5%\">Saldo Akhir</th>
										<th width=\"10%\">Nilai Persediaan</th>
										<th width=\"10%\">Total Nilai Persediaan</th>
							</tr>";
			$query_saldo = "SELECT * from tbl_barangstock where id = '$id'";
			$lap = $this->db->query($query_saldo)->row();
			if ($lap) {
				$_tanggalawal = $lap->lasttr;
				$saldo1 = $lap->saldoawal;
			} else {
				$_tanggalawal = date("d-m-Y");
				$saldo1 = 0;
			}
			$chari .= "
						<tr>
						<td width=\"5%\">#</td>
						<td width=\"20%\">SALDO</td>
						<td width=\"10%\">" . date("d-m-Y", strtotime($_tanggalawal)) . "</td>
						<td width=\"10%\">SALDO AWAL</td>
						<td width=\"15%\">SALDO AWAL</td>
						<td width=\"10%\"></td>
						<td width=\"5%\" style=\"text-align:right;\">0</td>
						<td width=\"5%\" style=\"text-align:right;\">0</td>
						<td width=\"5%\" style=\"text-align:right;\">" . number_format($saldo1) . "</td>
						<td width=\"10%\" style=\"text-align:right;\">0</td>
						<td width=\"10%\" style=\"text-align:right;\">0</td>
						</tr>
						<tr>
							<td colspan=\"11\">&nbsp;</td>
						</tr>";
			$queryx = $this->M_KartuStock->farmasistok($id);
			$saldo=0;
			$no = 1;
			foreach ($queryx as $db) {
				$nilai = $db->qty * $db->harga;
				$saldo2 = $saldo1 + ($saldo += $db->terima - $db->keluar);
				$chari .= "<tr>
						<td width=\"5%\">".$no++."</td>
						<td width=\"20%\">$db->nomor</td>
						<td width=\"10%\">" . date("d-m-Y", strtotime($db->tanggal)) . "</td>
						<td width=\"10%\">$db->keterangan</td>
						<td width=\"15%\">$db->rekanan</td>
						<td width=\"10%\" style=\"text-align:right;\">" . number_format($nilai) . "</td>
						<td width=\"5%\" style=\"text-align:right;\">" . number_format($db->terima) . "</td>
						<td width=\"5%\" style=\"text-align:right;\">" . number_format($db->keluar) . "</td>
						<td width=\"5%\" style=\"text-align:right;\">" . number_format($saldo2) . "</td>
						<td width=\"10%\" style=\"text-align:right;\">" . number_format($db->hpp) . "</td>
						<td width=\"10%\" style=\"text-align:right;\">" . number_format($db->totalhpp) . "</td>
					</tr>";
			}
			$chari .= "</table>";
			$data['prev']    = $chari;
			$judul           = "KARTU STOK CABANG $datars->kota";
			echo ("<title>$judul</title>");
			$this->M_cetak->mpdf('L', 'A4', $judul, $chari, '.PDF', 10, 10, 10, 2);
			// echo $chari;
		} else {
			header('location:' . base_url());
		}
	}

	function valid($id){
		$barang = $this->db->get_where("tbl_barangstock", ["id"=>$id])->row();
		$kodebarang = $barang->kodebarang;
		$gudang = $barang->gudang;
		$koders = $barang->koders;
		$saldoawal = $barang->saldoawal;
		$saldo=0;
		$terima=0;
		$keluar=0;
		$cek = $this->M_KartuStock->farmasistok($id);
		foreach($cek as $c){
			$terima += $c->terima;
			$keluar += $c->keluar;
			$saldo += $c->terima - $c->keluar;
		}
		$data = [
			'terima' => $terima,
			'keluar' => $keluar,
			'saldoakhir' => $saldo + $saldoawal,
		];
		$this->db->update("tbl_barangstock", $data, ["id"=>$id]);
		echo json_encode(['status' => 1]);
	}

	function valid_all($gudang){
		$cabang = $this->session->userdata("unit");
		$barangstok = $this->db->get_where("tbl_barangstock", ["koders" => $cabang, "gudang" => $gudang]);
		$no = 1;
		foreach($barangstok->result() as $bs){
			$kodebarang = $bs->kodebarang;
			$saldoawal = $bs->saldoawal;
			$nox = $no++;
			$saldo = 0;
			$terima = 0;
			$keluar = 0;
			$cek = $this->M_KartuStock->farmasistok($bs->id);
			foreach ($cek as $c) {
				$terima += $c->terima;
				$keluar += $c->keluar;
				$saldo += $c->terima - $c->keluar;
			}
			$data = [
				'terima' => $terima,
				'keluar' => $keluar,
				'saldoakhir' => $saldo + $saldoawal,
			];
			$this->db->update("tbl_barangstock", $data, ["id" => $bs->id]);
		}
		// if($nox == $barangstok->num_rows()){
			echo json_encode(['status' => 1,'no' => $nox]);
		// }
	}
}

/* End of file master_unit.php */
/* Location: ./application/controllers/master_unit.php */