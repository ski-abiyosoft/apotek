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
			$row[] = angka_rp($so_done, 0);
			$row[] = $unit->gudang;

			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Show" onclick="show(' . "'" . $unit->id . "'" . ')"><i class="glyphicon glyphicon-eye-open"></i> </a>';
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
		$data['prev'] = $chari;
		$judul          = 'LAPORAN HARIAN KASIR PENDAFTARAN PER SHIFT';
		switch ($cekpdf) {
			case 0;
				echo ("<title>DATA GLOBAL SKI</title>");
				echo ($chari);
				break;
			case 1;
				$this->M_cetak->mpdf('L', 'A3', $judul, $chari, 'KASIR-01.PDF', 10, 10, 10, 1);
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd-ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('app/master_cetak', $data);
				break;
		}
	}

	function cetak($gudang = '', $cekpdf = '')
	{

		// $cek          = $this->input->get('cekk');
		$cek          = $cekpdf;
		$chari   	  = '';
		$cekk         = $this->session->userdata('level');
		$unit   	  = $this->session->userdata('unit');
		$unit   	  = $this->session->userdata('unit');
		$avatar       = $this->session->userdata('avatar_cabang');
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
				<td colspan=\"10\" style=\"text-align:center;font-size:22px;border-bottom: none;color:#120292;\"><b>LAPORAN FARMASI CABANG $unit</b></td>
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
				<td bgcolor=\"#cccccc\" width=\"8%\" align=\"center\"><b>TERIMA</b></td>
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
			$apo = $this->db->query("SELECT * FROM tbl_aposesuai WHERE kodebarang='$row->kodebarang' AND gudang = '$row->gudang' AND koders = '$row->koders'")->row();
			$hasilso = $this->db->query("SELECT * FROM tbl_aposesuai WHERE kodebarang='$row->kodebarang' AND gudang = '$row->gudang' AND koders = '$row->koders' AND type = 'so' ORDER BY tglentry DESC LIMIT 1")->result();
			foreach ($hasilso as $key => $value) {
				$hso = $value->hasilso;
			}
			$hasiladj = $this->db->query("SELECT * FROM tbl_aposesuai WHERE kodebarang='$row->kodebarang' AND gudang = '$row->gudang' AND koders = '$row->koders' AND type = 'adjustment' ORDER BY tglentry DESC LIMIT 1")->result();
			if($hasiladj){
				foreach ($hasiladj as $key => $value) {
					$hdj = $value->hasilso;
				}
			} else {
				$hdj = 0;
			}
			$saldoawal     = number_format($row->saldoawal, 0, ',', '.');
			$sesuai        = number_format($hdj, 0, ',', '.');
			$terima        = number_format($row->terima, 0, ',', '.');
			$keluar        = number_format($row->keluar, 0, ',', '.');
			$hasilso       = number_format($hso, 0, ',', '.');
			$saldoakhir    = number_format($row->saldoakhir, 0, ',', '.');
			$satuan1       = $row->satuan1;

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
		$judul        = 'LAPORAN FARMASI CABANG';

			switch ($cek) {
				case 0;
					echo ("<title>LAPORAN FARMASI CABANG</title>");
					echo ($chari);
					break;

				case 1;
					$this->M_cetak->mpdf('P', 'A4', $judul, $chari, 'LAPORAN_FARMASI_CABANG.PDF', 10, 10, 10, 2);
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
		// $gudang = $this->input->get('gudang');
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
	public function show()
	{
		$cek  = $this->session->userdata('level');
		$cabang = $this->session->userdata('unit');
		$id = $this->input->get('id');
		if (!empty($cek)) {
			$judul = 'KARTU STOK';
			$profile = $this->M_global->_LoadProfileLap();
			$nama_usaha = $profile->nama_usaha;
			$alamat1  = $profile->alamat1;
			$alamat2  = $profile->alamat2;
			$profile = data_master('tbl_namers', array('koders' => $cabang));
			$nama_usaha = $profile->namars;
			$alamat1 = $profile->alamat;
			$alamat2 = $profile->kota;
			$pdf = new simkeu_nota();
			// $pdf->setID($nama_usaha,$alamat1,$alamat2);
			$pdf->setjudul($judul . ' CABANG ' . $cabang);
			$pdf->setsubjudul('');
			// $pdf->ln();
			$pdf->addpage("L", "A4");
			$pdf->setsize("L", "A4");
			$pdf->SetFont('Arial', 'B', 9);
			$query_saldo = "SELECT * from tbl_barangstock where id = '$id'";
			$lap = $this->db->query($query_saldo)->row_array();
			if ($lap) {
				$_tanggalawal = $lap['lasttr'];
				$saldo = $lap['saldoawal'];
			} else {
				$_tanggalawal = '';
				$saldo = 0;
			}
			$queryx = $this->M_KartuStock->farmasistok($id);
			// echo json_encode($queryx);
			$gudangx = $this->db->get_where('tbl_depo', ['depocode' => $lap['gudang']])->row_array();
			$brg = $this->db->get_where('tbl_barang', ['kodebarang' => $lap['kodebarang']])->row_array();
			$pdf->setfont('Arial', '', 10);
			$pdf->Cell(280, 6, 'Cabang : ' . $cabang, 0, 1, 'L');
			$pdf->Cell(280, 6, 'Gudang : ' . $gudangx['keterangan'], 0, 1, 'L');
			$pdf->Cell(280, 6, 'Kode Barang : ' . $lap['kodebarang'], 0, 1, 'L');
			$pdf->Cell(280, 6, 'Nama Barang : ' . $brg['namabarang'], 0, 1, 'L');
			$pdf->ln();
			$pdf->SetWidths(array(40, 20, 30, 30, 30, 20, 20, 20, 30, 30));
			$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
			$judul = array('No. Bukti', 'Tanggal', 'Keterangan', 'Rekanan', 'Nilai Pembelian', 'Terima', 'Keluar', 'Saldo Akhir', 'Nilai Persediaan', 'Total Nilai Persediaan');
			$pdf->setfont('Times', 'B', 10);
			$pdf->row($judul);
			$pdf->SetWidths(array(40, 20, 30, 30, 30, 20, 20, 20, 30, 30));
			$pdf->SetAligns(array('C', 'C', 'L', 'L', 'R', 'R', 'R', 'R', 'R', 'R'));
			$pdf->setfont('Times', '', 9);
			$pdf->SetFillColor(224, 235, 255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
			$pdf->row(array(
				'SALDO',
				date('d-m-Y', strtotime($_tanggalawal)),
				'SALDO AWAL ' . date('d-m-Y', strtotime($_tanggalawal)),
				'',
				number_format(0, 0, '.', ','),
				number_format(0, 0, '.', ','),
				number_format(0, 0, '.', ','),
				number_format($saldo, 0, '.', ','),
				number_format(0, 0, '.', ','),
				number_format(0, 0, '.', ',')
			));
			$pdf->ln();
			$nourut = 1;
			foreach ($queryx as $db) {
				$saldo = $saldo + $db->terima - $db->keluar;

				$nilai = $db->qty * $db->harga;
				$pdf->row(array(
					$db->nomor,
					date('d-m-Y', strtotime($db->tanggal)),
					$db->keterangan,
					$db->rekanan,
					number_format($nilai, 0, '.', ','),
					number_format($db->terima, 0, '.', ','),
					number_format($db->keluar, 0, '.', ','),
					number_format($saldo, 0, '.', ','),
					number_format($db->hpp, 2, '.', ','),
					number_format($db->totalhpp, 2, '.', ',')
				));

				$nourut++;
			}
			$pdf->output();
		} else {
			header('location:' . base_url());
		}
	}
}

/* End of file master_unit.php */
/* Location: ./application/controllers/master_unit.php */