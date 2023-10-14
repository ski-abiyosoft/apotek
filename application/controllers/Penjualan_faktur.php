<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Penjualan_faktur extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->session->set_userdata('menuapp', '3000');
		$this->session->set_userdata('submenuapp', '3201');
		$this->load->helper('simkeu_nota1');
		$this->load->helper('simkeu_nota');
		$this->load->model('M_pasien','M_pasien');
		$this->load->model('M_template_cetak');
	}

	public function index() {
		$cek        = $this->session->userdata('level');
		$unit       = $this->session->userdata('unit');
		$tanggal    = date('Y-m-d');
		if (!empty($cek)) {
			$q1        = "SELECT a.* FROM tbl_apoposting AS a INNER JOIN tbl_apohresep AS b ON a.resepno=b.resepno WHERE a.koders  = '$unit' AND b.jenisjual = 1 AND a.tglresep  = '$tanggal' ORDER BY a.tglresep DESC, a.resepno DESC";
			$bulan     = $this->M_global->_periodebulan();
			$nbulan    = $this->M_global->_namabulan($bulan);
			$periode   = 'Periode ' . $nbulan . '-' . $this->M_global->_periodetahun();
			$level     = $this->session->userdata('level');
			$akses     = $this->M_global->cek_menu_akses($level, 3201);
			if(isset($_GET["filter-eresep"])){
				$tanggal        = $_GET["filter-eresep"];
				$extract_tgl    = explode("~", $tanggal);
				$query_eresep   = $this->db->query("SELECT c.kodepos, a.id, a.koders, a.tglorder, a.orderno, a.noreg, a.rekmed, b.namapas, c.kodokter, '-' AS keterangan, a.resep, a.resepok FROM tbl_orderperiksa AS a LEFT JOIN tbl_pasien AS b ON a.rekmed = b.rekmed LEFT JOIN tbl_rekammedisrs AS c ON c.noreg = a.noreg WHERE a.koders = '$unit' AND a.tglorder BETWEEN '". $extract_tgl[0] ."' AND '". $extract_tgl[1] ."' AND a.resep = 1 AND a.resepok = 0 ORDER BY a.id DESC")->result();
			} else {
				$tanggal        = date("Y-m-d");
				$query_eresep   = $this->db->query("SELECT c.kodepos, a.id, a.koders, a.tglorder, a.orderno, a.noreg, a.rekmed, b.namapas, c.kodokter, '-' AS keterangan, a.resep, a.resepok FROM tbl_orderperiksa AS a LEFT JOIN tbl_pasien AS b ON a.rekmed = b.rekmed LEFT JOIN tbl_rekammedisrs AS c ON c.noreg = a.noreg WHERE a.koders = '$unit' AND a.tglorder LIKE '%$tanggal%' AND a.resep = 1 AND a.resepok = 0 ORDER BY a.id DESC")->result();
			}
			$data	=	[
				"keu"			=> $this->db->query($q1)->result(),
				"eresep"	=> $query_eresep,
				"akses"		=> $akses,
				"periode"	=> $periode,
			];
			$this->load->view('penjualan/v_penjualan_faktur', $data);
		} else {
			header('location:' . base_url());
		}
	}

	public function filter($param) {
		$cek        = $this->session->userdata('level');
		$unit       = $this->session->userdata('unit');
		$tanggal    = date("Y-m-d");
		if (!empty($cek)) {
			$data  = explode("~", $param);
			$jns   = $data[0];
			$tgl1  = $data[1];
			$tgl2  = $data[2];
			$_tgl1 = date('Y-m-d', strtotime($tgl1));
			$_tgl2 = date('Y-m-d', strtotime($tgl2));
			if (!empty($jns)) {
				$periode        = 'Periode ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2));
				$level          = $this->session->userdata('level');
				$akses          = $this->M_global->cek_menu_akses($level, 3201);
				$d['keu']       = $this->db->query("SELECT a.* FROM tbl_apoposting AS a INNER JOIN tbl_apohresep AS b ON a.resepno = b.resepno WHERE a.koders = '$unit' AND b.jenisjual = 1 AND a.tglresep BETWEEN '$_tgl1' AND '$_tgl2' ORDER BY a.tglresep desc, a.resepno DESC")->result();
				$d['akses']     = $akses;
				$d['periode']   = $periode;
				$d['eresep']    = $this->db->query("SELECT a.id, a.koders, a.tglorder, a.orderno, a.noreg, a.rekmed, b.namapas, a.kodokter, '-' AS keterangan, a.resep, a.resepok FROM tbl_orderperiksa AS a LEFT JOIN tbl_pasien AS b ON a.rekmed = b.rekmed WHERE a.koders = '$unit' AND a.tglorder LIKE '%$tanggal%' AND a.resep = 1 AND a.resepok = 0 ORDER BY a.id DESC")->result();
				$this->load->view('penjualan/v_penjualan_faktur', $d);
			}
		} else {
			header('location:' . base_url());
		}
	}

	public function cetak() {
		$cek        = $this->session->userdata('level');
		$unit       = $this->session->userdata('unit');
		$user       = $this->session->userdata('username');
		$nobukti    = $this->input->get('nobukti');
		if (!empty($cek)) {
			$profile       = data_master('tbl_namers', array('koders' => $unit));
			$nama_usaha    = $profile->namars;
			$alamat1       = $profile->alamat;
			$alamat2       = $profile->kota;
			$param         = $this->input->get('nobukti');
			$detil         = $this->db->query("SELECT * from tbl_apodresep where resepno = '$param' AND koders='$unit'")->result();
			$rck           = $this->db->query("SELECT *, (SELECT aturanpakai FROM tbl_aporacik WHERE resepno = tbl_apodetresep.resepno) as aturanpakai, (SELECT namaracikan FROM tbl_aporacik WHERE resepno = tbl_apodetresep.resepno) as namaracikan from tbl_apodetresep where resepno = '$param' AND koders='$unit'")->result();
			$racikan       = $this->db->query("SELECT * from tbl_aporacik where resepno = '$param' AND koders='$unit'")->row_array();
			$header        = $this->db->query("SELECT * from tbl_apohresep where resepno = '$param'")->row();
			$posting       = $this->db->query("SELECT * from tbl_apoposting  where resepno = '$param'")->row();
			$data_pasien   = data_master('tbl_pasien', array('rekmed' => $header->rekmed));
			if($data_pasien){
				$alamat = $data_pasien->alamat;
			}else{
				$alamat = '-';
			}
			$pdf = new simkeu_nota();
			$pdf->setID($nama_usaha, $alamat1, $alamat2);
			$pdf->setjudul('');
			$pdf->setsubjudul('');
			$pdf->addpage("P", "A4");
			$pdf->setsize("P", "A4");
			$pdf->SetWidths(array(70, 30, 90));
			$pdf->setfont('Arial', 'B', 18);
			$pdf->SetAligns(array('C', 'C', 'C'));
			$border    = array('', '', '');
			$size      = array('', '', '');
			$align     = array('L', 'C', 'L');
			$style     = array('U', '', '');
			$size      = array('12', '', '18');
			$max       = array(5, 5, 20);
			$judul     = array('Nota Pengeluaran', '', '');
			$fc        = array('0', '0', '0');
			$hc        = array('20', '20', '20');
			$pdf->FancyRow2(10, $judul, $fc,  $border, $align, $style, $size, $max);
			$pdf->ln(1);
			$pdf->setfont('Arial', 'B', 10);
			$pdf->SetWidths(array(25, 5, 60, 30, 5, 50));
			$border = array('', '', '', '', '', '');
			$fc     = array('0', '0', '0', '0', '0', '0');
			$pdf->SetFillColor(230, 230, 230);
			$pdf->setfont('Arial', '', 9);
			$pdf->FancyRow(array('No. Resep', ':', $header->resepno, 'Tanggal Resep', ':', date('d M Y', strtotime($header->tglresep)),), $fc, $border);
			$pdf->FancyRow(array('Nama Pasien', ':', $posting->namapas, 'Alamat Kirim', ':', $alamat), $fc, $border);
			$pdf->FancyRow(array('No. Member', ':', $header->rekmed, '', '', ''), $fc, $border);
			$pdf->ln(10);
			$pdf->SetWidths(array(10, 25, 25, 30, 20, 20, 20, 20, 20, 20));
			$border = array('LTBR', 'LTBR', 'LTBR', 'LTBR', 'LTBR', 'LTBR', 'LTBR','LTBR', 'LTBR');
			$align  = array('C', 'C', 'C', 'C', 'C', 'C', 'C','C', 'C');
			$pdf->setfont('Arial', 'B', 9);
			$pdf->SetAligns(array('L', 'C', 'R'));
			$fc = array('0', '0', '0', '0', '0', '0', '0','0', '0');
			$judul = array('No.', 'Kode Obat', 'Nama Obat', 'Jenis Tr', 'Jumlah', 'Harga Sat', 'Diskon', 'Total Rp', 'Pakai');
			$pdf->FancyRow2(8, $judul, $fc, $border, $align);
			$border = array('', '', '');
			$pdf->setfont('Arial', '', 9);
			$border = array('', '', '', '', '', '', '','', '');
			$border = array('LTBR', 'LTBR', 'LTBR', 'LTBR', 'LTBR', 'LTBR', 'LTBR','LTBR', 'LTBR');
			$borderx = array('', 'B', 'B', 'B', 'B', 'B', 'B','B', 'B');
			$align  = array('C', 'L', 'L', 'C', 'R', 'R', 'R','R', 'C');
			$fc = array('0', '0', '0', '0', '0', '0', '0','0', '0');
			$pdf->SetFillColor(0, 0, 139);
			$pdf->settextcolor(0);
			$no            = 1;
			$Tot           = 0;
			$Totdisc       = 0;
			$Totsub        = 0;
			$jumlahObat    = 0;
			$ppn           = 0;
			$tot           = 0;
			$ttlharga      = 0;
			$ttlhargax     = 0;
			$hrgR          = 0;
			$jmlRsp        = 0;
			$ttlDisc       = 0;
			$ttlSubRsp     = 0;
			$ppnRsp        = 0;
			$ttlhargaRsp   = 0;
			$ttlRsp        = 0;
			$dpp           = 0;
			$TOTAL         = 0;
			$cekdpp        = 0;
			$DiskonPersen  = 0;
			$ongkos        = 0;
			$totaluangr    = 0;
			$racik = $this->db->query("SELECT a.namaracikan, a.jumlahracik, a.kemasanracik, a.aturanpakai, a.totalrp, a.diskonrp, a.ongkosracik, b.namabarang , b.resepno FROM tbl_aporacik AS a JOIN tbl_apodresep AS b ON a.resepno = b.resepno WHERE b.resepno ='$param'")->result();
			$query_ppn   = $this->db->query("SELECT * FROM tbl_pajak where kodetax='PPN'")->result();
			$cekppn2     = $query_ppn[0]->prosentase / 100;
			$td          = 0;
			$tr          = 0;
			$ppnx        = 0;
			$Totdiscx    = 0;
			foreach ($detil as $db1) {
				$diskon       = (int)$db1->qty * (int)$db1->price * (int)$db1->discount / 100;
				$diskonx      = $db1->discrp;
				$ttlrp        = $db1->qty * $db1->price - $diskonx;
				$cekdpp       += 111 / 100;
				$jumlahObat   += $db1->qty;
				$Totdisc      += $diskon;
				$Totdiscx     += $diskonx;
				$ttlharga     += $db1->qty * $db1->price;
				$tot          = ($ttlharga - $Totdisc);
				$ppn          = ($ttlharga - $Totdisc) * $cekppn2;
				$TOTAL        = $tot  + $ppn;
				$dpp          = ($TOTAL - $Totdisc) / (111 / 100);
				$td           += $ttlrp;
				if($header->kodepel == 'adr'){
					$kodepel = 'Apotik Dengan Resep';
				} else if ($header->kodepel == 'atr') {
					$kodepel = 'Apotik Tanpa Resep';
				} else {
					$kodepel = $header->kodepel;
				}
				$atx = $this->db->query("SELECT * from tbl_barangsetup where  apocode='$db1->atpakai'")->row();
				$pdf->FancyRow([
					$no,
					$db1->kodebarang,
					$db1->namabarang,
					$kodepel,
					number_format($db1->qty, 0, ',', '.'),
					number_format($db1->price, 0, ',', '.'),
					number_format($diskonx, 0, ',', '.'),
					number_format($ttlrp, 0, ',', '.'),
					$atx->aponame,
				], $fc, $border, $align);
				$no++;
			}
			if ($racikan != null && $rck != null) {
				foreach ($rck as $rck) {
					if ($header->kodepel == 'adr') {
						$kodepel = 'Apotik Dengan Resep';
					} else if ($header->kodepel == 'atr') {
						$kodepel = 'Apotik Tanpa Resep';
					} else {
						$kodepel = $header->kodepel;
					}
					$diskon        = $rck->qty * $rck->price * $racikan['diskon'] / 100;
					$ttlrp         = $rck->qty * $rck->price;
					$tr            += $rck->totalrp;
					$totaluangr    += $rck->uangr;
					$atx           = $this->db->query("SELECT * from tbl_barangsetup where  apocode='$rck->aturanpakai'")->row();
					$pdf->FancyRow([
						$no,
						$rck->kodebarang,
						("** $rck->namaracikan"),
						$kodepel,
						number_format($rck->qty, 0, ',', '.'),
						number_format($rck->price, 0, ',', '.'),
						'-',
						number_format((!isset($rck->totalrp) ? 0 : $rck->totalrp), 0, ',', '.'),
						$atx->aponame,
					], $fc, $border, $align);
					$ongkos        = ($racikan['ongkosracik']);
					$cekdpp        += 111 / 100;
					$jumlahObat    += $rck->qty;
					$Totdisc       += $diskon;
					$ttlhargax     += $rck->qty * $rck->price;
					$tot           = ($ttlhargax - $Totdisc);
					$ppn           = ($ttlharga - $Totdisc) *  $cekppn2;
					$TOTAL         = $tot + $ppn;
					$no++;
				}
				$diskonracik   = $racikan['diskonrp'];
				$ppnx          = $td * $cekppn2;
				$ppnxx         = $racikan['ppnrp'];
				$ttlracikan    = $racikan['totalrp'];
			} else {
				$diskonracik   = 0;
				$ppnx          = $td * $cekppn2;
				$ppnxx         = 0;
				$ttlracikan    = 0;
			}
			$totalx        = $td + ($tr - $diskonracik) + $ppnx + $ongkos;
			$dpp_done      = $td / (111 / 100);
			$ppn_done      = $dpp_done * $cekppn2;
			$pdf->ln();
			$pdf->ln();
			$pdf->SetWidths(array(140, 50));
			$border    = array('', '');
			$align     = array('R', 'R');
			$fc        = array('0', '0');
			$style     = array('B', 'B');
			$size      = array('', '');
			$border    = array('', '');
			$fc        = array('0', '0');
			$pdf->FancyRow(array("Jumlah Obat", number_format($jumlahObat, 0, ',', '.')), $fc, $borderx, $align, $style, $size);
			$pdf->FancyRow(array("Sub Total Resep", number_format($ttlharga, 0, ',', '.')), $fc, $border, $align, $style, $size);
			$pdf->SetMargins(10, 10, 10, 10);
			$pdf->FancyRow(array('Diskon Resep', number_format($Totdiscx, 0, ',', '.')), $fc, $border, $align, $style, $size);
			$pdf->SetMargins(10, 10, 10, 10);
			$pdf->FancyRow(array('DPP Resep', number_format($dpp_done, 0, ',', '.')), $fc, $border, $align, $style, $size);
			$pdf->SetMargins(10, 10, 10, 10);
			$pdf->FancyRow(array('PPN Resep', number_format($ppn_done, 0, ',', '.')), $fc, $border, $align, $style, $size);
			$pdf->SetMargins(10, 10, 10, 10);
			$pdf->FancyRow(array('Total Resep', number_format($td, 0, ',', '.')), $fc, $border, $align, $style, $size);
			$pdf->SetMargins(10, 10, 10, 10);
			if ($ttlhargax != 0) {
				$pdf->FancyRow(array("Sub Total Racik", number_format($ttlhargax, 0, ',', '.')), $fc, $border, $align, $style, $size);
				$pdf->SetMargins(10, 10, 10, 10);
			}
			if ($totaluangr != 0) {
				$pdf->FancyRow(array("Uang Racik", number_format($totaluangr, 0, ',', '.')), $fc, $border, $align, $style, $size);
				$pdf->SetMargins(10, 10, 10, 10);
			}
			if ($ongkos != 0) {
				$pdf->FancyRow(array("Ongkos Racik", number_format($ongkos, 0, ',', '.')), $fc, $border, $align, $style, $size);
			}
			if ($diskonracik != 0) {
				$pdf->FancyRow(array('Diskon Racik', number_format($diskonracik, 0, ',', '.')), $fc, $border, $align, $style, $size);
				$pdf->SetMargins(10, 10, 10, 10);
			}
			if ($ppnxx != 0) {
				$pdf->FancyRow(array('PPN Racik', number_format($ppnxx, 0, ',', '.')), $fc, $border, $align, $style, $size);
				$pdf->SetMargins(10, 10, 10, 10);
			}
			$cek_racik = $this->db->get_where("tbl_aporacik", ["resepno" => $param])->row();
			if($cek_racik){
				if ($cek_racik->harga_manual > 0) {
					$harga_asli = $cek_racik->harga_manual;
				} else {
					$harga_asli = $ttlracikan;
				}
			}else{
				$harga_asli = 0;
			}
			if ($ttlracikan != 0) {
				$pdf->FancyRow(array('Total Racik', number_format($harga_asli, 0, ',', '.')), $fc, $border, $align, $style, $size);
				$pdf->SetMargins(10, 10, 10, 10);
			}
			if ($header->ongkoskirim != 0) {
				$pdf->FancyRow(array('Ongkos Kirim', number_format($header->ongkoskirim, 0, ',', '.')), $fc, $borderx, $align, $style, $size);
				$pdf->SetMargins(10, 10, 10, 10);
			}
			if ($header->ongkoskirim != 0) {
				$ongkir = $header->ongkoskirim;
			} else {
				$ongkir = 0;
			}
			$pdf->FancyRow(array('Total Net', number_format(($td) + ($harga_asli) + ($ongkir), 0, ',', '.')), $fc, $border, $align, $style, $size);
			$pdf->settextcolor(0);
			$pdf->SetWidths(array(60, 60, 60));
			$pdf->SetFont('Arial', '', 9);
			$pdf->SetAligns(array('C', 'C', 'C'));
			$pdf->ln(15);
			$border = array('', '', '');
			$align  = array('C', 'C', 'C');
			$pdf->FancyRow(array('(...............)', '(...............)', '(...............)'), 0, $border, $align);
			$pdf->FancyRow(array('Bag.Stock ', 'Bag.Penerima', 'Approval'), 0, $border, $align);
			$pdf->ln(5);
			$pdf->SetWidths(array(190));
			$pdf->SetFont('Arial', '', 9);
			$pdf->SetAligns(array('L'));
			$align  = array('L');
			$pdf->FancyRow(array('Cetak ' . date('d-m-Y') . ' Jam : ' . date('H:i:s')), 0, $border, $align);
			$pdf->FancyRow(array($user . ' ' . $header->jam), 0, $border, $align);
			$pdf->SetTitle($param);
			$pdf->AliasNbPages();
			$pdf->output($param . '.PDF', 'I');
		} else {
			header('location:' . base_url());
		}
	}

	public function cetak2_asli() {
		setlocale(LC_ALL, 'id_ID.utf8');
		$cek       = $this->session->userdata('level');
		$unit      = $this->session->userdata('unit');
		$user      = $this->session->userdata('username');
		$nobukti   = $this->input->get('nobukti');
		$cekpdf    = 1;
		$body      = '';
		$date      = '';
		$judul     = 'Nota Pengeluaran';
		$profile   = data_master('tbl_namers', array('koders' => $unit));
		$kota      = $profile->kota;
		$position  = 'P';
		$kop       = $this->M_cetak->kop($unit);
		$namars    = $kop['namars'];
		$kota      = $kop['kota'];
		if (!empty($cek)) {
			$param       = $this->input->get('nobukti');
			$hresep      = $this->db->query("SELECT (CASE WHEN a.kodepel='adr'  THEN 'Dengan Resep' ELSE 'Tanpa Resep' END  )nmpel ,a.* FROM tbl_apohresep a WHERE resepno = '$param'")->row();
			$posting     = $this->db->query("SELECT * FROM tbl_apoposting WHERE resepno = '$param'")->row();
			$userid      = $this->db->query("SELECT * FROM userlogin WHERE uidlogin = '$posting->username'")->row();
			$pasien      = $this->db->query("SELECT * FROM tbl_pasien WHERE rekmed = '$hresep->rekmed'")->row();
			$dresep      = $this->db->query("SELECT (SELECT aponame FROM tbl_barangsetup b where a.atpakai=b.apocode )ap,a.*  FROM tbl_apodresep a WHERE resepno = '$param' AND koders = '$unit'")->result();
			$racik       = $this->db->query("SELECT (SELECT aponame FROM tbl_barangsetup b where a.aturanpakai=b.apocode )ap,a.*  FROM tbl_aporacik a WHERE resepno = '$param' AND koders = '$unit'")->row();
			$cekracik    = $this->db->query("SELECT * FROM tbl_aporacik WHERE resepno = '$param' AND koders = '$unit'")->num_rows();
			$detresep    = $this->db->query("SELECT * FROM tbl_apodetresep WHERE resepno = '$param' AND koders = '$unit'")->result();
			if($pasien){
				$namapas    = $pasien->namapas;
				$rekmed     = $pasien->rekmed;
				$alamat     = $pasien->alamat;
			}else{
				$namapas    = $posting->namapas;
				$rekmed     = '<b>Non Member</b>';
				$alamat     = $hresep->alamat;
			}
			$date        = substr($hresep->tglresep,0,10);
			$tglresep    = $this->M_cetak->tanggal_format_indonesia($date);
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
				<tr>
					<td width=\"17%\">No. Resep</td>
					<td width=\"3%\">:</td>
					<td width=\"25%\">$hresep->resepno</td>
					<td width=\"10%\">&nbsp;</td>
					<td width=\"17%\">Tgl Resep</td>
					<td width=\"3%\">:</td>
					<td width=\"25%\">$tglresep</td>
				</tr>
				<tr>
					<td width=\"17%\">No. Member</td>
					<td width=\"3%\">:</td>
					<td width=\"25%\">$rekmed</td>
					<td width=\"10%\">&nbsp;</td>
					<td width=\"17%\">Jenis Transaksi</td>
					<td width=\"3%\">:</td>
					<td width=\"25%\"><b>$hresep->nmpel</b></td>
				</tr>
				<tr>
					<td width=\"17%\">Nama Pasien</td>
					<td width=\"3%\">:</td>
					<td width=\"25%\">$namapas</td>
					<td width=\"10%\">&nbsp;</td>
					<td width=\"17%\">Alamat Kirim</td>
					<td width=\"3%\">:</td>
					<td width=\"25%\">$alamat</td>
				</tr>
			</table>";
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"5\" cellmargin=\"5\">
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>";
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellpadding=\"5\" cellmargin=\"5\">";
			$body .= "<tr>
				<th bgcolor=\"#cccccc\">No</th>
				<th bgcolor=\"#cccccc\">Kode Obat</th>
				<th bgcolor=\"#cccccc\">Nama Obat</th>
				<th bgcolor=\"#cccccc\">Aturan Pakai</th>
				<th bgcolor=\"#cccccc\">Jumlah</th>
				<th bgcolor=\"#cccccc\">Harga Satuan</th>
				<th bgcolor=\"#cccccc\">Diskon</th>
				<th bgcolor=\"#cccccc\">Total Rp</th>
			</tr>";
			$no               = 1;
			$jum_resep        = 0;
			$sub_resep1       = 0;
			$diskon_resep1    = 0;
			$total_resep1     = 0;
			foreach($dresep as $d) {
				$jum_resep       += $d->qty;
				$sub_resep1      += ($d->qty * $d->price);
				$diskon_resep1   += ($d->discrp);
				$total_resep1    += ($d->totalrp);
				if($cekpdf == 1){
					$qty        = number_format($d->qty);
					$price      = number_format($d->price);
					$discrp     = number_format($d->discrp);
					$totalrp    = number_format($d->totalrp);
				} else {
					$qty        = ceil($d->qty);
					$price      = ceil($d->price);
					$discrp     = ceil($d->discrp);
					$totalrp    = ceil($d->totalrp);
				}
				$body .= "<tr>
					<td style=\"text-align: right;\">".$no++."</td>
					<td style=\"text-align: left;\">$d->kodebarang</td>
					<td style=\"text-align: left;\">$d->namabarang</td>
					<td style=\"text-align: center;\">$d->ap</td>
					<td style=\"text-align: center;\">$qty</td>
					<td style=\"text-align: right;\">$price</td>
					<td style=\"text-align: right;\">$discrp</td>
					<td style=\"text-align: right;\">$totalrp</td>
				</tr>";
			}
			$ppn           = $this->db->query("SELECT * FROM tbl_pajak WHERE kodetax = 'PPN'")->row();
			$pajak         = (int)$ppn->prosentase / 100;
			$dpp_resep1    = $total_resep1 / (111 / 100);
			$ppn_resep1    = $dpp_resep1 * $pajak;
			if($cekracik > 0) {
				$jum_racik = $racik->jumlahracik;
				if((int)$racik->harga_manual > 0){
					$harga_total = (int)$racik->harga_manual;
				} else {
					$harga_total = (int)$racik->totalrp;
				}
				$harga_satuan = $harga_total / (int)$racik->jumlahracik;
				if($cekpdf == 1){
					$total 		= number_format($harga_total);
					$satuan 	= number_format($harga_satuan);
					$diskon 	= number_format($racik->diskonrp);
				} else {
					$total 		= ceil($harga_total);
					$satuan 	= ceil($harga_satuan);
					$diskon 	= ceil($racik->diskonrp);
				}
				$body .= "<tr>
					<td style=\"text-align: right;\">".$no."</td>
					<td style=\"text-align: left;\">$racik->resepno</td>
					<td style=\"text-align: left;\">** $racik->namaracikan</td>
					<td style=\"text-align: center;\">$racik->ap</td>
					<td style=\"text-align: center;\">$racik->jumlahracik</td>
					<td style=\"text-align: right;\">$satuan</td>
					<td style=\"text-align: right;\">$diskon</td>
					<td style=\"text-align: right;\">$total</td>
				</tr>";
			} else {
				$jum_racik = 0;
			}
			$body .= "</table>";
			$jum_obat1 = $jum_resep + $jum_racik;
			if($cekpdf == 1){
				$jum_obat 		= number_format($jum_obat1);
				$sub_resep 		= number_format($sub_resep1);
				$diskon_resep = number_format($diskon_resep1);
				$total_resep 	= number_format($total_resep1);
			} else {
				$jum_obat 		= ceil($jum_obat1);
				$sub_resep 		= ceil($sub_resep1);
				$diskon_resep = ceil($diskon_resep1);
				$total_resep 	= ceil($total_resep1);
			}
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"5\" cellmargin=\"5\">
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>";
			if($cekpdf == 1) {
				$dpp_resep = number_format($dpp_resep1);
				$ppn_resep = number_format($ppn_resep1);
			} else {
				$dpp_resep = ceil($dpp_resep1);
				$ppn_resep = ceil($ppn_resep1);
			}
			$link="";
			if($cekracik > 0){
				if((int)$racik->harga_manual > 0){
					$harga_total = $racik->harga_manual;
				} else {
					$harga_total = $racik->totalrp;
				}
				$satuan 				= $harga_total / $racik->jumlahracik;
				$sub_racik1 		= $satuan * $racik->jumlahracik;
				
				$dpp_racik1 = $harga_total / (111 / 100);
				$ppn_racik1 = $dpp_racik1 * $pajak;
				if($cekpdf == 1) {
					$sub_racik 		= number_format($sub_racik1);
					$diskon_racik = number_format($racik->diskonrp);
					$dpp_racik 		= number_format($dpp_racik1);
					$ppn_racik 		= number_format($ppn_racik1);
					$total_racik 	= number_format($harga_total);
				} else {
					$sub_racik 		= ceil($sub_racik1);
					$diskon_racik = ceil($racik->diskonrp);
					$dpp_racik 		= ceil($dpp_racik1);
					$ppn_racik 		= ceil($ppn_racik1);
					$total_racik 	= ceil($harga_total);
				}
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px;\" width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"5\" cellmargin=\"5\">
					<tr>
						<td width=\"100%\" style=\"text-align: right;\" colspan=\"6\">$link</td>
					</tr>
					<tr>
						<td width=\"33%\" style=\"text-align: right;\">Jumlah Obat</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right;\">$jum_obat</td>
						<td width=\"50%\" style=\"text-align: right;\" colspan=\"3\">&nbsp;</td>
					</tr>
					<tr>
						<td width=\"33%\" style=\"text-align: right;\">Subtotal Resep</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right;\">$sub_resep</td>
						<td width=\"33%\" style=\"text-align: right;\">Subtotal Racik</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right;\">$sub_racik</td>
					</tr>
					<tr>
						<td width=\"33%\" style=\"text-align: right;\">Diskon Resep</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right;\">$diskon_resep</td>
						<td width=\"33%\" style=\"text-align: right;\">Diskon Racik</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right;\">$diskon_racik</td>
					</tr>
					<tr>
						<td width=\"33%\" style=\"text-align: right;\">DPP Resep</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right;\">$dpp_resep</td>
						<td width=\"33%\" style=\"text-align: right;\">DPP Racik</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right;\">$dpp_racik</td>
					</tr>
					<tr>
						<td width=\"33%\" style=\"text-align: right;\">PPN Resep</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right;\">$ppn_resep</td>
						<td width=\"33%\" style=\"text-align: right;\">PPN Racik</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right;\">$ppn_racik</td>
					</tr>
					<tr>
						<td width=\"33%\" style=\"text-align: right; font-weight: bold;\">Total Resep</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right; font-weight: bold;\">$total_resep</td>
						<td width=\"33%\" style=\"text-align: right; font-weight: bold;\">Total Racik</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right; font-weight: bold;\">$total_racik</td>
					</tr>
				</table>";
			} else {
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px;\" width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"5\" cellmargin=\"5\">
					<tr>
						<td width=\"50%\" style=\"text-align: right;\" colspan=\"3\">$link</td>
					</tr>
					<tr>
						<td width=\"83%\" style=\"text-align: right;\">Jumlah Obat</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right;\">$jum_obat</td>
					</tr>
					<tr>
						<td width=\"83%\" style=\"text-align: right;\">Subtotal Resep</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right;\">$sub_resep</td>
					</tr>
					<tr>
						<td width=\"83%\" style=\"text-align: right;\">Diskon Resep</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right;\">$diskon_resep</td>
					</tr>
					<tr>
						<td width=\"83%\" style=\"text-align: right;\">DPP Resep</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right;\">$dpp_resep</td>
					</tr>
					<tr>
						<td width=\"83%\" style=\"text-align: right;\">PPN Resep</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right;\">$ppn_resep</td>
					</tr>
					<tr>
						<td width=\"83%\" style=\"text-align: right; font-weight: bold;\">Total Resep</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right; font-weight: bold;\">$total_resep</td>
					</tr>
				</table>";
			}
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"5\" cellmargin=\"5\">
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>";
			if($cekracik > 0){
				if((int)$racik->harga_manual > 0){
					$racikan = $racik->harga_manual;
				} else {
					$racikan = $racik->totalrp;
				}
			} else {
				$racikan = 0;
			}
			$resepan         = $total_resep1;
			$total_semua1    = $racikan + $resepan;
			if($cekpdf == 1){
				$total_semua = number_format($total_semua1);
			} else {
				$total_semua = ceil($total_semua1);
			}
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:14px; font-weight: bold;\" width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"5\" cellmargin=\"5\">
				<tr>
					<td width=\"50%\">&nbsp;</td>
					<td width=\"30%\" style=\"text-align: right;\">Total Keseluruhan</td>
					<td style=\"text-align: right;\">$total_semua</td>
				</tr>
			</table>";
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"5\" cellmargin=\"5\">
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>";
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"5\" cellmargin=\"5\">
			<tr>
				<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
				<td width=\"50%\" style=\"text-align:center;\"><b>$kota, $tglresep</b></td>
			</tr> 
			<tr>
				<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
				<td width=\"50%\" style=\"text-align:center; font-size:14px;\"><b>$namars</b></td>
			</tr> 
			<tr>
				<td style=\"text-align: center;\"><b>Pasien</b></td>
				<td style=\"text-align: center;\"><b>Kasir</b></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td width=\"33%\" style=\"text-align: center;\">(&nbsp; $namapas &nbsp;)</td>
				<td width=\"34%\" style=\"text-align: center;\">(&nbsp;" .$userid->username. "&nbsp;)</td>
			</tr>
			</table>";
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"5\" cellmargin=\"5\">
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>";
			$now = new DateTime(date("Y-m-d"));
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"right\" border=\"0\" cellpadding=\"5\" cellmargin=\"5\">";
			$body .= "<tr>
					<td style=\"text-align: right;\">Dicetak pada : ".strftime('%A, %d %B %Y', $now->getTimestamp())."</td>
				</tr>
				<tr>
					<td style=\"text-align: right;\">$user ( Jam ".date("H:i:s")." )</td>
				</tr>
			</table>";
			$this->M_template_cetak->template($judul, $body, $position, $tglresep, $cekpdf);
		} else {
			header('location:' . base_url());
		}
	}

	public function cetak2() {
		setlocale(LC_ALL, 'id_ID.utf8');
		$cek       = $this->session->userdata('level');
		$unit      = $this->session->userdata('unit');
		$user      = $this->session->userdata('username');
		$nobukti   = $this->input->get('nobukti');
		$cekpdf    = 1;
		$body      = '';
		$date      = '';
		$judul     = 'Nota Pengeluaran';
		$profile   = data_master('tbl_namers', array('koders' => $unit));
		$kota      = $profile->kota;
		$position  = 'P';
		$kop       = $this->M_cetak->kop($unit);
		$namars    = $kop['namars'];
		$kota      = $kop['kota'];
		if (!empty($cek)) {
			$param       = $this->input->get('nobukti');
			$hresep      = $this->db->query("SELECT (CASE WHEN a.kodepel='adr'  THEN 'Dengan Resep' ELSE 'Tanpa Resep' END  )nmpel ,a.* FROM tbl_apohresep a WHERE resepno = '$param'")->row();
			$posting     = $this->db->query("SELECT * FROM tbl_apoposting WHERE resepno = '$param'")->row();
			$userid      = $this->db->query("SELECT * FROM userlogin WHERE uidlogin = '$posting->username'")->row();
			$pasien      = $this->db->query("SELECT (select cust_nama from tbl_penjamin b where b.cust_id=a.penjamin)nm_penjamin,a.* FROM tbl_pasien a WHERE rekmed = '$hresep->rekmed'")->row();
			$dresep      = $this->db->query("SELECT (SELECT aponame FROM tbl_barangsetup b where a.atpakai=b.apocode limit 1 )ap,a.*  FROM tbl_apodresep a WHERE resepno = '$param' AND koders = '$unit'")->result();
			$racik       = $this->db->query("SELECT (SELECT aponame FROM tbl_barangsetup b where a.aturanpakai=b.apocode )ap,a.*  FROM tbl_aporacik a WHERE resepno = '$param' AND koders = '$unit'")->row();
			$racik_res   = $this->db->query("SELECT (SELECT aponame FROM tbl_barangsetup b where a.aturanpakai=b.apocode )ap,a.*  FROM tbl_aporacik a WHERE resepno = '$param' AND koders = '$unit'")->result();
			$kasir       = $this->db->query("SELECT * from tbl_kasir where nokwitansi = '$hresep->nokwitansi'")->row();;
			$cekracik    = $this->db->query("SELECT * FROM tbl_aporacik WHERE resepno = '$param' AND koders = '$unit'")->num_rows();
			$detresep    = $this->db->query("SELECT * FROM tbl_apodetresep WHERE resepno = '$param' AND koders = '$unit'")->result();
			if($pasien) {
				$namapas    = $pasien->namapas;
				$rekmed     = $pasien->rekmed;
				$alamat     = $pasien->alamat;
				$penjamin   = $pasien->nm_penjamin;
				$nocard     = $pasien->nocard;
				$handphone  = $pasien->handphone;
			} else {
				$namapas= $posting->namapas;
				$rekmed = 'Non Member';
				$alamat = $hresep->alamat;
			}
			$date        = substr($hresep->tglresep,0,10);
			$tglresep    = $this->M_cetak->tanggal_format_indonesia($date);
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
				<tr>
					<td width=\"17%\">No. Resep</td>
					<td width=\"3%\">:</td>
					<td width=\"25%\">$hresep->resepno</td>
					<td width=\"10%\">&nbsp;</td>
					<td width=\"17%\">Tgl Resep</td>
					<td width=\"3%\">:</td>
					<td width=\"25%\">$tglresep</td>
				</tr>
				<tr>
					<td width=\"17%\">No. Member</td>
					<td width=\"3%\">:</td>
					<td width=\"25%\"><b>$rekmed</b></td>
					<td width=\"10%\">&nbsp;</td>
					<td width=\"17%\">Jenis Transaksi</td>
					<td width=\"3%\">:</td>
					<td width=\"25%\"><b>$hresep->nmpel</b></td>
				</tr>
				<tr>
					<td width=\"17%\">Nama Pasien</td>
					<td width=\"3%\">:</td>
					<td width=\"25%\">$namapas</td>
					<td width=\"10%\">&nbsp;</td>
					<td width=\"17%\">Alamat Kirim</td>
					<td width=\"3%\">:</td>
					<td width=\"25%\">$alamat</td>
				</tr>";
			if($pasien) {
				$body .= "<tr>
					<td width=\"17%\">Penjamin</td>
					<td width=\"3%\">:</td>
					<td width=\"25%\">$penjamin</td>
					<td width=\"10%\">&nbsp;</td>
					<td width=\"17%\">No Kartu</td>
					<td width=\"3%\">:</td>
					<td width=\"25%\">$nocard</td>
				</tr>
				<tr>
					<td width=\"17%\">Handphone</td>
					<td width=\"3%\">:</td>
					<td width=\"25%\">$handphone</td>
					<td width=\"10%\">&nbsp;</td>
				</tr>";
			}
			$body .= "</table>";
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"5\" cellmargin=\"5\">
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>";
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellpadding=\"5\" cellmargin=\"5\">";
			$body .= "<tr>
				<th bgcolor=\"#cccccc\">No</th>
				<th bgcolor=\"#cccccc\">Kode</th>
				<th bgcolor=\"#cccccc\">Nama</th>
				<th bgcolor=\"#cccccc\">At Pakai</th>
				<th bgcolor=\"#cccccc\">Jumlah</th>
				<th bgcolor=\"#cccccc\">Harga Satuan</th>
				<th bgcolor=\"#cccccc\">Diskon</th>
				<th bgcolor=\"#cccccc\">Total Rp</th>
			</tr>";
			$no              = 1;
			$jum_resep       = 0;
			$sub_resep1      = 0;
			$diskon_resep1   = 0;
			$total_resep1    = 0;
			$price1          = 0;
			foreach($dresep as $d) {
				$jum_resep       += $d->qty;
				$sub_resep1      += ($d->qty * $d->price);
				$diskon_resep1   += ($d->discrp);
				$total_resep1    += ($d->totalrp);
				$price1          += ($d->price);
				if($cekpdf == 1){
					$qty        = number_format($d->qty);
					$price      = number_format($d->price);
					$discrp     = number_format($d->discrp);
					$totalrp    = number_format($d->totalrp);
				} else {
					$qty        = ceil($d->qty);
					$price      = ceil($d->price);
					$discrp     = ceil($d->discrp);
					$totalrp    = ceil($d->totalrp);
				}
				$body .= "<tr>
					<td style=\"text-align: right;\">".$no++."</td>
					<td style=\"text-align: left;\">$d->kodebarang</td>
					<td style=\"text-align: left;\">$d->namabarang</td>
					<td style=\"text-align: center;\">$d->ap</td>
					<td style=\"text-align: center;\">$qty</td>
					<td style=\"text-align: right;\">$price</td>
					<td style=\"text-align: right;\">$discrp</td>
					<td style=\"text-align: right;\">$totalrp</td>
				</tr>";
			}
			if($cekpdf == 1) {
				$jum_obat       = number_format($jum_resep);
				$diskon_resep   = number_format($diskon_resep1);
				$total_resep    = number_format($total_resep1);
				$jum_price      = number_format($price1);
			} else {
				$jum_obat       = ceil($jum_resep);
				$diskon_resep   = ceil($diskon_resep1);
				$total_resep    = ceil($total_resep1);
				$jum_price      = ceil($price1);
			}
			$body .= "<tr>
					<td bgcolor=\"#eeeff1\" colspan=\"4\" style=\"text-align: center;\"><b>Total Resep</b></td>
					<td bgcolor=\"#eeeff1\" style=\"text-align: center;\"><b>$jum_obat</b></td>
					<td bgcolor=\"#eeeff1\" style=\"text-align: right;\"><b>$jum_price</b></td>
					<td bgcolor=\"#eeeff1\" style=\"text-align: right;\"><b>$diskon_resep</b></td>
					<td bgcolor=\"#eeeff1\" style=\"text-align: right;\"><b>$total_resep</b></td>
				</tr>
				</table>";
			$ppn           = $this->db->query("SELECT * FROM tbl_pajak WHERE kodetax = 'PPN'")->row();
			$pajak         = (int)$ppn->prosentase / 100;
			$dpp_resep1    = $total_resep1 / (111 / 100);
			$ppn_resep1    = $dpp_resep1 * $pajak;
			if($cekracik > 0) {
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"5\" cellmargin=\"5\">
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>";
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellpadding=\"5\" cellmargin=\"5\">";
				$body .= "<tr>
					<th width=\"10%\" bgcolor=\"#cccccc\">No</th>
					<th width=\"20%\" bgcolor=\"#cccccc\">Resep</th>
					<th width=\"20%\" bgcolor=\"#cccccc\">Nama</th>
					<th width=\"10%\" bgcolor=\"#cccccc\">At Pakai</th>
					<th width=\"10%\" bgcolor=\"#cccccc\">Jumlah</th>
					<th width=\"10%\" bgcolor=\"#cccccc\">Harga Satuan</th>
					<th width=\"10%\" bgcolor=\"#cccccc\">Diskon</th>
					<th width=\"10%\" bgcolor=\"#cccccc\">Total Rp</th>
				</tr>";
				$jum_racik1       = 0;
				$sub_racik1       = 0;
				$diskon_racik1    = 0;
				$total_racik1     = 0;
				$tsemuax          = 0;
				$nox              = 1;
				foreach($racik_res as $racik){
					$jum_racik = $racik->jumlahracik;
					if((int)$racik->cek_rm > 0){
						$harga_total = (int)$racik->harga_manual;
					} else {
						$harga_total = (int)$racik->totalrp;
					}
					$harga_satuan = $harga_total / (int)$racik->jumlahracik;
					if($cekpdf == 1){
						$total 		= number_format($harga_total);
						$satuan 	= number_format($harga_satuan);
						$diskon 	= number_format($racik->diskonrp);
					} else {
						$total 		= ceil($harga_total);
						$satuan 	= ceil($harga_satuan);
						$diskon 	= ceil($racik->diskonrp);
					}
					$body .= "<tr>
						<td style=\"text-align: right;\">".$nox."</td>
						<td style=\"text-align: left;\">$racik->resepno</td>
						<td style=\"text-align: left;\"><span style=\"color:red;\">** $racik->namaracikan</span> </td>
						<td style=\"text-align: center;\">$racik->ap</td>
						<td style=\"text-align: center;\">$racik->jumlahracik</td>
						<td style=\"text-align: right;\">$satuan</td>
						<td style=\"text-align: right;\">$diskon</td>
						<td style=\"text-align: right;\">$total</td>
					</tr>";
					$satuan          = $harga_total / $racik->jumlahracik;
					$sub_racik       = ceil($satuan);
					$diskon_racik    = ceil($racik->diskonrp);
					$total_racik     = ceil($harga_total);
					$jum_racik1      += $jum_racik;
					$sub_racik1      += $sub_racik;
					$diskon_racik1   += $diskon_racik;
					$tsemuax         += $harga_total;
					if($cekpdf == 1) {
						$jum_racik1   = number_format($jum_racik1);
						$sub_racik2   = number_format($sub_racik1);
						$total_racik1 = number_format($tsemuax);
					} else {
						$jum_racik1   = ceil($jum_racik1);
						$sub_racik2   = ceil($sub_racik1);
						$total_racik1 = ceil($tsemuax);
					}
					$nox++;
				}
				$body .= "<tr>
					<td bgcolor=\"#eeeff1\" colspan=\"4\" style=\"text-align: center;\"><b>Total Racik</b></td>
					<td bgcolor=\"#eeeff1\" style=\"text-align: center;\"><b>$jum_racik1</b></td>
					<td bgcolor=\"#eeeff1\" style=\"text-align: right;\"><b>$sub_racik2</b></td>
					<td bgcolor=\"#eeeff1\" style=\"text-align: right;\"><b>$diskon_racik1</b></td>
					<td bgcolor=\"#eeeff1\" style=\"text-align: right;\"><b>$total_racik1</b></td>
				</tr>
				</table>";	
			} else {
				$jum_racik    = 0;
				$tsemuax      = 0;
			}
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"5\" cellmargin=\"5\">
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>";
			if($cekpdf == 1) {
				$dpp_resep = number_format($dpp_resep1);
				$ppn_resep = number_format($ppn_resep1);
			} else {
				$dpp_resep = ceil($dpp_resep1);
				$ppn_resep = ceil($ppn_resep1);
			}		
			$cek   = $this->db->get_where("tbl_hset_farma", ["koders" => $this->session->userdata("unit")])->row();
			$cek2  = $this->db->get_where("tbl_apohresep", ["resepno" => $hresep->resepno])->row();
			$cek3  = $this->db->get_where("tbl_apodresep", ["resepno" => $hresep->resepno])->num_rows();
			$cek4  = $this->db->get_where("tbl_aporacik", ["resepno" => $hresep->resepno])->num_rows();
			if($cek) {
				if($cek2->kodepel == "adr") {		
					if($cek4 > 0) {
						$uangracik = $cek->uang_racik * $cek4;
					} else {
						$uangracik = 0;
					}
					$uangr = ($cek->uang_r * $cek3) + $uangracik;
				} else {
					$uangr = $cek->uang_r * $cek3;
				}
			} else {
				$uangr = 0;
			}
			$link = "";
			if($cekracik > 0){
				$tsemua       = 0;
				$dpp_racik3   = 0;
				$ppn_racik3   = 0;
				$total_racik3 = 0;
				foreach($racik_res as $racik){	
					if((int)$racik->cek_rm > 0){
						$harga_total = $racik->harga_manual;
					} else {
						$harga_total = $racik->totalrp;
					}
					$tsemua        += $harga_total;
					$dpp_racik1    = $harga_total / (111 / 100);
					$ppn_racik1    = $dpp_racik1 * $pajak;
					$dpp_racik2    = ceil($dpp_racik1);
					$ppn_racik2    = ceil($ppn_racik1);
					$dpp_racik3    += $dpp_racik2;
					$ppn_racik3    += $ppn_racik2;
					if($cekpdf == 1) {
						$dpp_racik       = number_format($dpp_racik3);
						$ppn_racik       = number_format($ppn_racik3);
					} else {
						$dpp_racik       = ceil($dpp_racik3);
						$ppn_racik       = ceil($ppn_racik3);
					}
				}
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px;\" width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"5\" cellmargin=\"5\">
					<tr>
						<td width=\"100%\" style=\"text-align: right;\" colspan=\"6\">$link</td>
					</tr>
					<tr>
						<td width=\"33%\" style=\"text-align: right;\">DPP Resep</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right;\">$dpp_resep</td>
						<td width=\"33%\" style=\"text-align: right;\">DPP Racik</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right;\">$dpp_racik</td>
					</tr>
					<tr>
						<td width=\"33%\" style=\"text-align: right;\">PPN Resep</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right;\">$ppn_resep</td>
						<td width=\"33%\" style=\"text-align: right;\">PPN Racik</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right;\">$ppn_racik</td>
					</tr>
					<tr>
						<td colspan=\"3\" width=\"50%\">&nbsp;</td>
						<td width=\"33%\" style=\"text-align: right;\"><b>Uang R</b></td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right;\"><b>".number_format($uangr)."</b></td>
					</tr>
				</table>";
			} else {
				$tsemua = 0;
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px;\" width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"5\" cellmargin=\"5\">
					<tr>
						<td width=\"50%\" style=\"text-align: right;\" colspan=\"3\">$link</td>
					</tr>
					<tr>
						<td width=\"83%\" style=\"text-align: right;\">PPN Resep</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right;\">$ppn_resep</td>
					</tr>
					<tr>
						<td width=\"83%\" style=\"text-align: right;\"><b>Uang R</b></td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right;\"><b>".number_format($uangr)."</b></td>
					</tr>
				</table>";
			}
			if ($hresep->nokwitansi) {
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px;\" width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"3\" cellmargin=\"3\">
					<tr>
						<td style=\"text-align: left;\" colspan=\"3\"></td>
					</tr>
					<tr>
						<td width=\"20%\" style=\"text-align: left;\"><b>Cash</b></td>
						<td width=\"2%\">:</td>
						<td width=\"78%\" style=\"text-align: left;\">$kasir->bayarcash</td>
					</tr>
				</table>";
				$query_kartu_card	= $this->db->query("SELECT * FROM tbl_kartukredit WHERE nokwitansi = '$hresep->nokwitansi'");
				foreach ($query_kartu_card->result() as $ccval) {
					$query_nama_bank	= $this->db->query("SELECT * FROM tbl_edc WHERE bankcode = '$ccval->kodebank'")->row();
					switch ($ccval->cardtype) {
						case 1:
							$cardType = "DEBIT NO";
							break;
						case 2:
							$cardType = "CREDIT NO";
							break;
						case 3:
							$cardType = "TRANSFER NO";
							break;
						case 4:
							$cardType = "ONLINE";
							break;
						case 5:
							$cardType = "QRIS";
							break;
					}					
					$nocard_length   = count(array($ccval->nocard)) - 4;
					$nocard          = substr($ccval->nocard, 0, $nocard_length) . "XXXX";
					$jumbar          = number_format($ccval->jumlahbayar, 0, ',', '.');
					$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px;\" width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"3\" cellmargin=\"3\">
						<tr>
							<td style=\"text-align: left;\" colspan=\"3\"></td>
						</tr>
						<tr>
							<td width=\"20%\" style=\"text-align: left;\"><b>Bank Penerbit</b></td>
							<td width=\"2%\">:</td>
							<td width=\"78%\" style=\"text-align: left;\">$query_nama_bank->namabank</td>
						</tr>
						<tr>
							<td width=\"20%\" style=\"text-align: left;\"><b>$cardType</b></td>
							<td width=\"2%\">:</td>
							<td width=\"78%\" style=\"text-align: left;\">$nocard</td>
						</tr>
						<tr>
							<td width=\"20%\" style=\"text-align: left;\"><b>Approval Code</b></td>
							<td width=\"2%\">:</td>
							<td width=\"78%\" style=\"text-align: left;\">$ccval->nootorisasi</td>
						</tr>
						<tr>
							<td width=\"20%\" style=\"text-align: left;\"><b>Nominal</b></td>
							<td width=\"2%\">:</td>
							<td width=\"78%\" style=\"text-align: left;\">Rp. $jumbar</td>
						</tr>
					</table>";
				}
			}
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"5\" cellmargin=\"5\">
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>";
			$resepan         = $total_resep1;
			$total_semua1    = $tsemua + $resepan + $uangr;
			if($cekpdf == 1) {
				$total_semua = number_format($total_semua1);
			} else {
				$total_semua = ceil($total_semua1);
			}
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:14px; font-weight: bold;\" width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"5\" cellmargin=\"5\">
				<tr>
					<td width=\"50%\">&nbsp;</td>
					<td width=\"30%\" style=\"text-align: right;\">Total Keseluruhan</td>
					<td style=\"text-align: right;\">$total_semua</td>
				</tr>
			</table>";
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"5\" cellmargin=\"5\">
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>";
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"5\" cellmargin=\"5\">
			<tr>
				<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
				<td width=\"50%\" style=\"text-align:center;\"><b>$kota, $tglresep</b></td>
			</tr> 
			<tr>
				<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
				<td width=\"50%\" style=\"text-align:center; font-size:14px;\"><b>$namars</b></td>
			</tr> 
			<tr>
				<td style=\"text-align: center;\"><b>Pasien</b></td>
				<td style=\"text-align: center;\"><b>Kasir</b></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td width=\"33%\" style=\"text-align: center;\">(&nbsp; $namapas &nbsp;)</td>
				<td width=\"34%\" style=\"text-align: center;\">(&nbsp;" .$userid->username. "&nbsp;)</td>
			</tr>
			</table>";
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"5\" cellmargin=\"5\">
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>";
			$now = new DateTime(date("Y-m-d"));
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"right\" border=\"0\" cellpadding=\"5\" cellmargin=\"5\">";
			$body .= "<tr>
					<td style=\"text-align: right;\">Dicetak pada : ".strftime('%A, %d %B %Y', $now->getTimestamp())."</td>
				</tr>
				<tr>
					<td style=\"text-align: right;\">$user ( Jam ".date("H:i:s")." )</td>
				</tr>
			</table>";
			$this->M_template_cetak->template($judul, $body, $position, $tglresep, $cekpdf);
		} else {
			header('location:' . base_url());
		}
	}

	public function cetak2_nota() {
		setlocale(LC_ALL, 'id_ID.utf8');
		$cek       		= $this->session->userdata('level');
		$unit      		= $this->session->userdata('unit');
		$user      		= $this->session->userdata('username');
		$nobukti   		= $this->input->get('nobukti');
		$cekpdf    		= 1;
		$body      		= '';
		$date      		= '';
		$judul     		= 'Nota Pengeluaran';
		$profile   		= data_master('tbl_namers', array('koders' => $unit));
		$kota      		= $profile->kota;
		$position  		= 'P';
		$kop       		= $this->M_cetak->kop($unit);
		$avatar    		= $this->session->userdata('avatar_cabang');
		$profile   		= data_master('tbl_namers', array('koders' => $unit));
		$h_namars    	= $kop['namars'];
		$h_alamat    	= $kop['alamat'];
		$h_alamat2   	= $kop['alamat2'];
		$h_alamat3   	= $profile->kota;
		$h_kota      	= $kop['kota'];
		$h_phone     	= $kop['phone'];
		$h_whatsapp  	= $kop['whatsapp'];
		$h_npwp      	= $kop['npwp'];
		if (!empty($cek)) {
			$param         = $this->input->get('nobukti');
			$hresep        = $this->db->query("SELECT (CASE WHEN a.kodepel='adr'  THEN 'Dengan Resep' ELSE 'Tanpa Resep' END  )nmpel ,a.* FROM tbl_apohresep a WHERE resepno = '$param'")->row();
			$posting       = $this->db->query("SELECT * FROM tbl_apoposting WHERE resepno = '$param'")->row();
			$userid        = $this->db->query("SELECT * FROM userlogin WHERE uidlogin = '$posting->username'")->row();
			$pasien        = $this->db->query("SELECT (select cust_nama from tbl_penjamin b where b.cust_id=a.penjamin)nm_penjamin,a.* FROM tbl_pasien a WHERE rekmed = '$hresep->rekmed'")->row();
			$dresep        = $this->db->query("SELECT (SELECT aponame FROM tbl_barangsetup b where a.atpakai=b.apocode limit 1 )ap,a.*  FROM tbl_apodresep a WHERE resepno = '$param' AND koders = '$unit'")->result();
			$dresep_jml    = $this->db->query("SELECT (SELECT aponame FROM tbl_barangsetup b where a.atpakai=b.apocode limit 1 )ap,a.*  FROM tbl_apodresep a WHERE resepno = '$param' AND koders = '$unit'")->num_rows();
			$racik         = $this->db->query("SELECT (SELECT aponame FROM tbl_barangsetup b where a.aturanpakai=b.apocode )ap,a.*  FROM tbl_aporacik a WHERE resepno = '$param' AND koders = '$unit'")->row();
			$racik_res     = $this->db->query("SELECT (SELECT aponame FROM tbl_barangsetup b where a.aturanpakai=b.apocode )ap,a.*  FROM tbl_aporacik a WHERE resepno = '$param' AND koders = '$unit'")->result();
			$kasir         = $this->db->query("SELECT * from tbl_kasir where nokwitansi = '$hresep->nokwitansi'")->row();
			$cekracik      = $this->db->query("SELECT * FROM tbl_aporacik WHERE resepno = '$param' AND koders = '$unit'")->num_rows();
			$detresep      = $this->db->query("SELECT * FROM tbl_apodetresep WHERE resepno = '$param' AND koders = '$unit'")->result();
			if($pasien) {
				$namapas    = $pasien->namapas;
				$rekmed     = $pasien->rekmed;
				$alamat     = $pasien->alamat;
				$penjamin   = $pasien->nm_penjamin;
				$nocard     = $pasien->nocard;
				$handphone  = $pasien->handphone;
			} else {
				$namapas    = $posting->namapas;
				$rekmed     = 'Non Member';
				$alamat     = $hresep->alamat;
			}
			$body .= "<table style=\"border-collapse:collapse;font-family: 'Dot Matrix'; font-size:12px; color:#000;\" width=\"100%\" border=\"0\" >
				<tr>
					<td rowspan=\"7\" align=\"center\">
						<img src=\"" . base_url() . "assets/img_user/$avatar\"  width=\"70\" height=\"70\" /></td>
					</td>
				</tr>
				<tr>
					<td colspan=\"5\">
						<tr>
							<td align=\"center\" style=\"font-size:10px;border-bottom: none;\"><b><br>$h_namars</b></td>
						</tr>
						<tr>
							<td align=\"center\" style=\"font-size:9px;\">$h_alamat</td>
						</tr>
						<tr>
							<td align=\"center\" style=\"font-size:9px;\">$h_alamat2</td>
						</tr>
						<tr>
							<td align=\"center\" style=\"font-size:9px;\">Wa :$h_whatsapp    Telp :$h_phone </td>
						</tr>
						<tr>
							<td align=\"center\" style=\"font-size:9px;\">No. NPWP : $h_npwp</td>
						</tr>
					</td>
				</tr>
			</table>";
			$body .= "<table style=\"border-collapse:collapse;font-family: 'Dot Matrix'; font-size:5px\" width=\"100%\" align=\"center\" border=\"0\">
				<tr>
					<td>&nbsp;</td>
				</tr> 
			</table>";
			$body .= "
			   <table style=\"border-collapse:collapse;font-family: 'Dot Matrix'; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\">     
					<tr>
						 <td style=\"border-top: none;border-right: none;border-left: none;\"></td>
					</tr> 
			   </table>";
			$body .= "
				<table style=\"border-collapse:collapse;font-family: 'Dot Matrix'; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\">     
						<tr>
							<td style=\"border-top: none;border-right: none;border-left: none;\"></td>
						</tr> 
				</table>";
			$body .= "<table style=\"border-collapse:collapse;font-family: 'Dot Matrix'; font-size:5px\" width=\"100%\" align=\"center\" border=\"0\">
			<tr>
				<td>&nbsp;</td>
			</tr> 
			</table>";
			$date 				= substr($hresep->tglresep,0,10);
			$tglresep 		= $this->M_cetak->tanggal_format_indonesia($date);
			$body .= "<table style=\"border-collapse:collapse;font-family: 'Dot Matrix'; font-size:10px\" width=\"100%\" align=\"center\" border=\"0\">
				<tr>
					<td width=\"17%\">No. Resep</td>
					<td width=\"3%\">:</td>
					<td width=\"25%\">$hresep->resepno</td>
					<td width=\"10%\">&nbsp;</td>
					<td width=\"17%\">Tgl Resep</td>
					<td width=\"3%\">:</td>
					<td width=\"25%\">$tglresep</td>
				</tr>
				<tr>
					<td width=\"17%\">No. Member</td>
					<td width=\"3%\">:</td>
					<td width=\"25%\">$rekmed</td>
					<td width=\"10%\">&nbsp;</td>
					<td width=\"17%\">Jenis Tr</td>
					<td width=\"3%\">:</td>
					<td width=\"25%\">$hresep->nmpel</td>
				</tr>
				<tr>
					<td width=\"17%\">Nama Pasien</td>
					<td width=\"3%\">:</td>
					<td width=\"25%\">$namapas</td>
					<td width=\"10%\">&nbsp;</td>
					<td width=\"17%\">Alamat Kirim</td>
					<td width=\"3%\">:</td>
					<td width=\"25%\">$alamat</td>
				</tr>";
			if($pasien) {
				$body .= "<tr>
					<td width=\"17%\">Penjamin</td>
					<td width=\"3%\">:</td>
					<td width=\"25%\">$penjamin</td>
					<td width=\"10%\">&nbsp;</td>
					<td width=\"17%\">No Kartu</td>
					<td width=\"3%\">:</td>
					<td width=\"25%\">$nocard</td>
				</tr>
				<tr>
					<td width=\"17%\">Handphone</td>
					<td width=\"3%\">:</td>
					<td width=\"25%\">$handphone</td>
					<td width=\"10%\">&nbsp;</td>
				</tr>";
			}
			$body .= "</table>";
			$body .= "<table style=\"border-collapse:collapse;font-family: 'Dot Matrix'; font-size:10px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>";
			if($dresep_jml > 0) {
				$body .= "<table style=\"border-collapse:collapse;font-family: 'Dot Matrix'; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
				<tr>
					<th style=\"text-align: center; border-right: none;border-left: none;\" width=\"10%\">No</th>
					<th style=\"text-align: center; border-right: none;border-left: none;\" width=\"10%\">Kode</th>
					<th style=\"text-align: center; border-right: none;border-left: none;\" width=\"20%\">Nama</th>
					<th style=\"text-align: center; border-right: none;border-left: none;\" width=\"15%\">At Pakai</th>
					<th style=\"text-align: center; border-right: none;border-left: none;\" width=\"10%\">Jml</th>
					<th style=\"text-align: center; border-right: none;border-left: none;\" width=\"10%\">Hrg Sat</th>
					<th style=\"text-align: center; border-right: none;border-left: none;\" width=\"10%\">Diskon</th>
					<th style=\"text-align: center; border-right: none;border-left: none;\" width=\"15%\">Ttl</th>
				</tr>";
				$no              = 1;
				$jum_resep       = 0;
				$sub_resep1      = 0;
				$diskon_resep1   = 0;
				$total_resep1    = 0;
				$price1          = 0;
				foreach($dresep as $d) {
					$jum_resep       += $d->qty;
					$sub_resep1      += ($d->qty * $d->price);
					$diskon_resep1   += ($d->discrp);
					$total_resep1    += ($d->totalrp);
					$price1          += ($d->price);
					if($cekpdf == 1){
						$qty        = number_format($d->qty);
						$price      = number_format($d->price);
						$discrp     = number_format($d->discrp);
						$totalrp    = number_format($d->totalrp);
					} else {
						$qty        = ceil($d->qty);
						$price      = ceil($d->price);
						$discrp     = ceil($d->discrp);
						$totalrp    = ceil($d->totalrp);
					}
					$body .= "<tr>
						<td style=\"text-align: center; border-right: none;border-left: none;\">".$no++."</td>
						<td style=\"text-align: left; border-right: none;border-left: none;\">$d->kodebarang</td>
						<td style=\"text-align: left; border-right: none;border-left: none;\">$d->namabarang</td>
						<td style=\"text-align: center; border-right: none;border-left: none;\">$d->ap</td>
						<td style=\"text-align: center; border-right: none;border-left: none;\">$qty</td>
						<td style=\"text-align: right; border-right: none;border-left: none;\">$price</td>
						<td style=\"text-align: right; border-right: none;border-left: none;\">$discrp</td>
						<td style=\"text-align: right; border-right: none;border-left: none;\">$totalrp</td>
					</tr>";
				}
				if($cekpdf == 1){
					$jum_obat       = number_format($jum_resep);
					$diskon_resep   = number_format($diskon_resep1);
					$total_resep    = number_format($total_resep1);
					$jum_price      = number_format($price1);
				} else {
					$jum_obat       = ceil($jum_resep);
					$diskon_resep   = ceil($diskon_resep1);
					$total_resep    = ceil($total_resep1);
					$jum_price      = ceil($price1);
				}
				$body .= "<tr>
					<td colspan=\"4\" style=\"text-align: center; border-right: none;border-left: none;\"><b>Total Resep</b></td>
					<td style=\"text-align: center; border-right: none;border-left: none;\"><b>$jum_obat</b></td>
					<td style=\"text-align: right; border-right: none;border-left: none;\"><b>$jum_price</b></td>
					<td style=\"text-align: right; border-right: none;border-left: none;\"><b>$diskon_resep</b></td>
					<td style=\"text-align: right; border-right: none;border-left: none;\"><b>$total_resep</b></td>
				</tr>";
				$body .= "</table>";
			} else {
				$total_resep1 = 0;
			}
			$ppn           = $this->db->query("SELECT * FROM tbl_pajak WHERE kodetax = 'PPN'")->row();
			$pajak         = (int)$ppn->prosentase / 100;
			$dpp_resep1    = $total_resep1 / (111 / 100);
			$ppn_resep1    = $dpp_resep1 * $pajak;
			if($cekracik > 0) {
				$body .= "<table style=\"border-collapse:collapse;font-family: 'Dot Matrix'; font-size:5px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
				<tr>
					<td colspan=\"5\" style=\"text-align: center\">&nbsp;</td>
				</tr></table>";
				$body .= "<table style=\"border-collapse:collapse;font-family: 'Dot Matrix'; font-size:10px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
				<tr>
					<td colspan=\"5\" style=\"text-align: center\">&nbsp;** Racikan **</td>
				</tr></table>";
				$body .= "<table style=\"border-collapse:collapse;font-family: 'Dot Matrix'; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
				<tr>
					<th style=\"text-align: center; border-right: none;border-left: none;\" width=\"10%\">No</th>
					<th style=\"text-align: center; border-right: none;border-left: none;\" width=\"10%\">Resep</th>
					<th style=\"text-align: center; border-right: none;border-left: none;\" width=\"25%\">Nama</th>
					<th style=\"text-align: center; border-right: none;border-left: none;\" width=\"15%\">At Pakai</th>
					<th style=\"text-align: center; border-right: none;border-left: none;\" width=\"10%\">Jml</th>
					<th style=\"text-align: center; border-right: none;border-left: none;\" width=\"10%\">Hrg Sat</th>
					<th style=\"text-align: center; border-right: none;border-left: none;\" width=\"10%\">Diskon</th>
					<th style=\"text-align: center; border-right: none;border-left: none;\" width=\"10%\">Ttl</th>
				</tr>";
				$jum_racik1       = 0;
				$sub_racik1       = 0;
				$diskon_racik1    = 0;
				$total_racik1     = 0;
				$nor              = 1;
				foreach($racik_res as $racik) {
					$jum_racik 			= $racik->jumlahracik;
					if((int)$racik->harga_manual > 0) {
						$harga_total 	= (int)$racik->harga_manual;
					} else {
						$harga_total 	= (int)$racik->totalrp;
					}
					$harga_satuan 	= $harga_total / (int)$racik->jumlahracik;
					if($cekpdf == 1) {
						$total 				= number_format($harga_total);
						$satuan 			= number_format($harga_satuan);
						$diskon 			= number_format($racik->diskonrp);
					} else {
						$total 				= ceil($harga_total);
						$satuan 			= ceil($harga_satuan);
						$diskon 			= ceil($racik->diskonrp);
					}
					$body .= "<tr>
						<td style=\"text-align: center; border-right: none;border-left: none;\">".$nor."</td>
						<td style=\"text-align: left; border-right: none;border-left: none;\">$racik->resepno</td>
						<td style=\"text-align: left; border-right: none;border-left: none;\"><span style=\"color:red; border-right: none;border-left: none;\">** $racik->namaracikan</span> </td>
						<td style=\"text-align: center; border-right: none;border-left: none;\">$racik->ap</td>
						<td style=\"text-align: center; border-right: none;border-left: none;\">$racik->jumlahracik</td>
						<td style=\"text-align: right; border-right: none;border-left: none;\">$satuan</td>
						<td style=\"text-align: right; border-right: none;border-left: none;\">$diskon</td>
						<td style=\"text-align: right; border-right: none;border-left: none;\">$total</td>
					</tr>";
					if((int)$racik->harga_manual > 0) {
						$harga_total 	= $racik->harga_manual;
					} else {
						$harga_total 	= $racik->totalrp;
					}
					$satuan          = $harga_total / $racik->jumlahracik;
					$sub_racik       = ceil($satuan);
					$diskon_racik    = ceil($racik->diskonrp);
					$total_racik     = ceil($harga_total);
					$jum_racik1      += $jum_racik;
					$sub_racik1      += $sub_racik;
					$diskon_racik1   += $diskon_racik;
					$total_racik1    += $total_racik;
					if($cekpdf == 1) {
						$jum_racik1   = number_format($jum_racik1);
						$sub_racik2   = number_format($sub_racik1);
						$total_racik2 = number_format($total_racik1);
					} else {
						$jum_racik1   = ceil($jum_racik1);
						$sub_racik2   = ceil($sub_racik1);
						$total_racik2 = ceil($total_racik1);
					}
					$nor++;
				}
				$body .= "<tr>
					<td colspan=\"4\" style=\"text-align: center; border-right: none;border-left: none; \"><b>Total Racik</b></td>
					<td style=\"text-align: center; border-right: none;border-left: none; \"><b>$jum_racik1</b></td>
					<td style=\"text-align: right; border-right: none;border-left: none; \"><b>$sub_racik2</b></td>
					<td style=\"text-align: right; border-right: none;border-left: none; \"><b>$diskon_racik1</b></td>
					<td style=\"text-align: right; border-right: none;border-left: none; \"><b>$total_racik2</b></td>
				</tr>";
				$body .= "</table>";
			} else {
				$jum_racik = 0;
			}
			$body .= "<table style=\"border-collapse:collapse;font-family: 'Dot Matrix'; font-size:5px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>";
			if($cekpdf == 1) {
				$dpp_resep = number_format($dpp_resep1);
				$ppn_resep = number_format($ppn_resep1);
			} else {
				$dpp_resep = ceil($dpp_resep1);
				$ppn_resep = ceil($ppn_resep1);
			}
			$cek   = $this->db->get_where("tbl_hset_farma", ["koders" => $this->session->userdata("unit")])->row();
			$cek2  = $this->db->get_where("tbl_apohresep", ["resepno" => $hresep->resepno])->row();
			$cek3  = $this->db->get_where("tbl_apodresep", ["resepno" => $hresep->resepno])->num_rows();
			$cek4  = $this->db->get_where("tbl_aporacik", ["resepno" => $hresep->resepno])->num_rows();
			if($cek) {
				if($cek2->kodepel == "adr") {				
					if($cek4 > 0) {
						$uangracik = $cek->uang_racik * $cek4;
					} else {
						$uangracik = 0;
					}
					$uangr = ($cek->uang_r * $cek3) + $uangracik;
				} else {
					$uangr = $cek->uang_r * $cek3;
				}
			} else {
				$uangr = 0;
			}
			if($cekracik > 0) {
				$dpp_racik3   = 0;
				$ppn_racik3   = 0;
				$total_racik3 = 0;
				$tsemua       = 0;
				foreach($racik_res as $racik){	
					if((int)$racik->cek_rm > 0){
						$harga_total = $racik->harga_manual;
					} else {
						$harga_total = $racik->totalrp;
					}
					$tsemua        += $harga_total;
					$dpp_racik1    = $harga_total / (111 / 100);
					$ppn_racik1    = $dpp_racik1 * $pajak;
					$dpp_racik2    = ceil($dpp_racik1);
					$ppn_racik2    = ceil($ppn_racik1);
					$dpp_racik3    += $dpp_racik2;
					$ppn_racik3    += $ppn_racik2;
					if($cekpdf == 1) {
						$dpp_racik    = number_format($dpp_racik3);
						$ppn_racik    = number_format($ppn_racik3);
					} else {
						$dpp_racik    = ceil($dpp_racik3);
						$ppn_racik    = ceil($ppn_racik3);
					}	
				}
				$body .= "<table style=\"border-collapse:collapse;font-family: 'Dot Matrix'; font-size:10px;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
					<tr>
						<td width=\"33%\" style=\"text-align: right;\">DPP Resep</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right;\">$dpp_resep</td>
						<td width=\"33%\" style=\"text-align: right;\">DPP Racik</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right;\">$dpp_racik</td>
					</tr>
					<tr>
						<td width=\"33%\" style=\"text-align: right;\">PPN Resep</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right;\">$ppn_resep</td>
						<td width=\"33%\" style=\"text-align: right;\">PPN Racik</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right;\">$ppn_racik</td>
					</tr>
					<tr>
						<td colspan=\"3\" width=\"50%\">&nbsp;</td>
						<td width=\"33%\" style=\"text-align: right;\">Uang R</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right;\">$uangr</td>
					</tr>
				</table>";
			} else {
				$tsemua = 0;
				$body .= "<table style=\"border-collapse:collapse;font-family: 'Dot Matrix'; font-size:10px;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
					<tr>
						<td width=\"83%\" style=\"text-align: right;\">PPN Resep</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right;\">$ppn_resep</td>
					</tr>
					<tr>
						<td width=\"83%\" style=\"text-align: right;\">Uang R</td>
						<td width=\"2%\">:</td>
						<td width=\"15%\" style=\"text-align: right;\">$uangr</td>
					</tr>
				</table>";
			}
			if ($hresep->nokwitansi) {
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:10px;\" width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"3\" cellmargin=\"3\">
					<tr>
						<td style=\"text-align: left;\" colspan=\"3\"></td>
					</tr>
					<tr>
						<td width=\"20%\" style=\"text-align: left;\"><b>Cash</b></td>
						<td width=\"2%\">:</td>
						<td width=\"78%\" style=\"text-align: left;\">$kasir->bayarcash </td>
					</tr>
				</table>";
				$query_kartu_card	= $this->db->query("SELECT * FROM tbl_kartukredit WHERE nokwitansi = '$hresep->nokwitansi'")->result();
				foreach ($query_kartu_card as $ccval) {
					$query_nama_bank	= $this->db->query("SELECT * FROM tbl_edc WHERE bankcode = '$ccval->kodebank'")->row();
					switch ($ccval->cardtype) {
						case 1:
							$cardType = "DEBIT NO";
							break;
						case 2:
							$cardType = "CREDIT NO";
							break;
						case 3:
							$cardType = "TRANSFER NO";
							break;
						case 4:
							$cardType = "ONLINE";
							break;
						case 5:
							$cardType = "QRIS";
							break;
					}
					$nocard_length   = count(array($ccval->nocard)) - 4;
					$nocard          = substr($ccval->nocard, 0, $nocard_length) . "XXXX";
					$jumbar          = number_format($ccval->jumlahbayar, 0, ',', '.');
					$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:10px;\" width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"3\" cellmargin=\"3\">
						<tr>
							<td style=\"text-align: left;\" colspan=\"3\"></td>
						</tr>
						<tr>
							<td width=\"40%\" style=\"text-align: left;\">Bank Penerbit</td>
							<td width=\"2%\">:</td>
							<td width=\"58%\" style=\"text-align: left;\">$query_nama_bank->namabank</td>
						</tr>
						<tr>
							<td width=\"20%\" style=\"text-align: left;\">$cardType</td>
							<td width=\"2%\">:</td>
							<td width=\"78%\" style=\"text-align: left;\">$nocard</td>
						</tr>
						<tr>
							<td width=\"20%\" style=\"text-align: left;\">Approval Code</td>
							<td width=\"2%\">:</td>
							<td width=\"78%\" style=\"text-align: left;\">$ccval->nootorisasi</td>
						</tr>
						<tr>
							<td width=\"20%\" style=\"text-align: left;\">Nominal</td>
							<td width=\"2%\">:</td>
							<td width=\"78%\" style=\"text-align: left;\">Rp. $jumbar</td>
						</tr>
					</table>";
				}
			}
			$body .= "<table style=\"border-collapse:collapse;font-family: 'Dot Matrix'; font-size:5px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>";
			$resepan         = $total_resep1;
			$total_semua1    = $tsemua + $resepan + $uangr ;
			if($cekpdf == 1){
				$total_semua 	= number_format($total_semua1);
			} else {
				$total_semua 	= ceil($total_semua1);
			}
			$body .= "<table style=\"border-collapse:collapse;font-family: 'Dot Matrix'; font-size:12px; font-weight: bold;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
				<tr>
					<td width=\"50%\">&nbsp;</td>
					<td width=\"30%\" style=\"text-align: right;border-bottom: 1px solid #000;border-top: 1px solid #000;\">Total</td>
					<td style=\"text-align: right;border-bottom: 1px solid #000;border-top: 1px solid #000;\">$total_semua</td>
				</tr>
			</table>";
			$body .= "<table style=\"border-collapse:collapse;font-family: 'Dot Matrix'; font-size:5px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>";
			$body .= "<table style=\"border-collapse:collapse;font-family: 'Dot Matrix'; font-size:10px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
				<tr>
					<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
					<td width=\"50%\" style=\"text-align:center;\"><b>$kota, $tglresep</b></td>
				</tr> 
				<tr>
					<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
					<td width=\"50%\" style=\"text-align:center; font-size:8px;\"><b>$h_namars</b></td>
				</tr> 
				<tr>
					<td style=\"text-align: center;\"><b>Pasien</b></td>
					<td style=\"text-align: center;\"><b>Kasir</b></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td width=\"33%\" style=\"text-align: center;\">(&nbsp; $namapas &nbsp;)</td>
					<td width=\"34%\" style=\"text-align: center;\">(&nbsp;" .$userid->username. "&nbsp;)</td>
				</tr>
			</table>";
			$body .= "<table style=\"border-collapse:collapse;font-family: 'Dot Matrix'; font-size:5px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>";
			$now = new DateTime(date("Y-m-d"));
			$body .= "<table style=\"border-collapse:collapse;font-family: 'Dot Matrix'; font-size:10px\" width=\"100%\" align=\"right\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
				<tr>
					<td style=\"text-align: right;\">Dicetak pada : ".strftime('%A, %d %B %Y', $now->getTimestamp())."</td>
				</tr>
				<tr>
					<td style=\"text-align: right;\">$user ( Jam ".date("H:i:s")." )</td>
				</tr>
			</table>";
			$this->M_cetak->template_nota(75,190,$judul, $body, $position, $tglresep, $cekpdf);
		} else {
			header('location:' . base_url());
		}
	}

	public function ctk_etiket() {
		$cek        = $this->session->userdata('level');
		$unit       = $this->session->userdata('unit');
		$user       = $this->session->userdata('username');
		$noresep    = $this->input->get('resep');
		if (!empty($cek)) {
			$kop       = $this->M_cetak->kop($unit);
			$avatar    = $this->session->userdata('avatar_cabang');
			$profile   = data_master('tbl_namers', array('koders' => $unit));
			$namars    = $kop['namars'];
			$alamat    = $kop['alamat'];
			$alamat2   = $kop['alamat2'];
			$alamat3   = $profile->kota;
			$kota      = $kop['kota'];
			$phone     = $kop['phone'];
			$whatsapp  = $kop['whatsapp'];
			$npwp      = $kop['npwp'];
			$body      = '';
			$posting   = $this->db->get_where('tbl_apoposting', array('resepno' => $noresep))->row();
			$header    = $this->db->get_where('tbl_apohresep', array('resepno' => $noresep));
			// $detil     = $this->db->select('tbl_apodresep.*,(SELECT aponame from tbl_barangsetup where  apocode=tbl_apodresep.atpakai)nm_atpakai, tbl_barang.namabarang as namabarang1')->join('tbl_barang', 'tbl_barang.kodebarang=tbl_apodresep.kodebarang')->get_where('tbl_apodresep', array('resepno' => $noresep,'cetak' => 1))->result();
			$detil 		 =  $this->db->query("SELECT dr.*, aponame AS nm_atpakai, apocode FROM tbl_apodresep dr JOIN tbl_barangsetup bs ON bs.apocode = dr.atpakai WHERE dr.resepno = '$noresep' AND cetak = 1")->result();
			$header_r  = $this->db->get_where('tbl_aporacik', array('resepno' => $noresep));
			$detil_r   = $this->db->get_where('tbl_apodetresep', array('resepno' => $noresep));
			$dresep    = $this->db->get_where('tbl_apodresep', array('resepno' => $noresep));
			$dataRacik = $this->db->query("SELECT * FROM tbl_aporacik where resepno ='$noresep'");
			$total     = $this->db->query("SELECT totalrp FROM tbl_apodetresep WHERE resepno = '$noresep'");
			$body .= "<table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			<thead>
				<tr>
					<tr>
						<td align=\"center\" style=\"font-size:10px;border-bottom: none;\"><b><br>$namars</b></td>
					</tr>
					<tr>
						<td align=\"center\" style=\"font-size:9px;\">$alamat</td>
					</tr>
					<tr>
						<td align=\"center\" style=\"font-size:9px;\">$alamat2</td>
					</tr>
					<tr>
						<td align=\"center\" style=\"font-size:9px;\">Wa :$whatsapp    Telp :$phone </td>
					</tr>
					<tr>
						<td align=\"center\" style=\"font-size:9px;\">No. NPWP : $npwp</td>
					</tr>
					</td>
				</tr>
			</table>";
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"3\">     
				<tr>
					<td style=\"border-top: none;border-right: none;border-left: none;\"></td>
				</tr> 
			</table>";
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
				<tr>
					<td ><b>$posting->namapas</b></td>
				</tr> 
			</table>";
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"3\">     
				<tr>
					<td style=\"border-top: none;border-right: none;border-left: none;\"></td>
				</tr> 
			</table>";
			foreach ($detil as $db) {
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
					<tr>
						<td collspan=\"2\">$db->namabarang</td>
					</tr> 
					<tr>
						<td>Aturan Pakai</td>
						<td style=\"text-align: right\">$db->nm_atpakai</td>
					</tr> 
				</table>";
			// 	$body .= "<table style=\"margin-bottom:5px;border-collapse:collapse;font-family:Century Gothic;color:#000;border-bottom:1px dashed #000;\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			// 		<tr>
			// 			<td align=\"center\">&nbsp;</td>
			// 		</tr> 
			// 	</table>";
			// 	$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">     
			// 		<tr><td>&nbsp;</td></tr> 
			// 	</table>";
			}
			$position    = 'P';
			$tglresep    = date('Y-m-d');
			$cekpdf      = 1;
			$judul       = "CETAK DOKUMEN JAMINAN NO. RESEP : ".$noresep;
			echo ("<title>$judul</title>");
			$this->M_cetak->template_nota(80,190,$judul, $body, $position, $tglresep, $cekpdf);
		} else {
			header('location:' . base_url());
		}
	}

	public function ctk_telaah() {
		$cek        = $this->session->userdata('level');
		$unit       = $this->session->userdata('unit');
		$user       = $this->session->userdata('username');
		$noresep    = $this->input->get('resep');
		if (!empty($cek)) {
			$kop       = $this->M_cetak->kop($unit);
			$avatar    = $this->session->userdata('avatar_cabang');
			$posting   = $this->db->get_where('tbl_apoposting', array('resepno' => $noresep))->row();
			$header    = $this->db->get_where('tbl_apohresep', array('resepno' => $noresep));
			$header_r  = $this->db->get_where('tbl_aporacik', array('resepno' => $noresep));
			$detil_r   = $this->db->get_where('tbl_apodetresep', array('resepno' => $noresep));
			$dresep    = $this->db->get_where('tbl_apodresep', array('resepno' => $noresep));
			$dataRacik = $this->db->query("SELECT * FROM tbl_aporacik where resepno ='$noresep'");
			$total     = $this->db->query("SELECT totalrp FROM tbl_apodetresep WHERE resepno = '$noresep'");
			$detil     = $this->db->query("SELECT *,coalesce(cek,0)cek1 from (SELECT *, (select ok cek from tbl_apotelaah b where tbl_aspektelaah.kode=b.kode and orderno='$noresep')cek FROM tbl_aspektelaah )a")->result();
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
				<tr>
					<td ><b>Nama Pasien : $posting->namapas</b></td>
				</tr> 
			</table>";
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"3\">     
			<tr>
				<td style=\"border-top: none;border-right: none;border-left: none;\"></td>
			</tr> 
			</table>";
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"3\">
				<tr>
					<th bgcolor=\"#cccccc\">No</th>
					<th bgcolor=\"#cccccc\">Aspek</th>
					<th bgcolor=\"#cccccc\">Status</th>
				</tr> ";
			$no = 1;
			foreach ($detil as $db) {
				if($db->cek1 == 1) {
					$status = 'YA';
				} else {
					$status = 'TIDAK';
				}
				$body .= "<tr>
						<td align=\"center\">$no</td>
						<td>$db->aspek</td>
						<td align=\"center\">$status</td>
					</tr> ";
				$no++;
			}
			$body .= " </table>";
			$position    = 'P';
			$tglresep 	 = $this->M_cetak->tanggal_format_indonesia(substr($posting->tglresep,0,10));;
			$cekpdf      = 1;
			$judul       = "Cetak Telaah Resep & Telaah Obat" ;
			echo ("<title>$judul</title>");
			$this->M_cetak->template($judul, $body, $position, $tglresep, $cekpdf);
		} else {
			header('location:' . base_url());
		}
	}

	public function ctk_cr() {
		$cek        = $this->session->userdata('level');
		$unit       = $this->session->userdata('unit');
		$user       = $this->session->userdata('username');
		$noresep    = $this->input->get('resep');
		if (!empty($cek)) {
			$kop       = $this->M_cetak->kop($unit);
			$avatar    = $this->session->userdata('avatar_cabang');
			$profile   = data_master('tbl_namers', array('koders' => $unit));
			$namars    = $kop['namars'];
			$alamat    = $kop['alamat'];
			$alamat2   = $kop['alamat2'];
			$alamat3   = $profile->kota;
			$kota      = $kop['kota'];
			$phone     = $kop['phone'];
			$whatsapp  = $kop['whatsapp'];
			$npwp      = $kop['npwp'];
			$body      = '';
			$posting   = $this->db->get_where('tbl_apoposting', array('resepno' => $noresep))->row();
			$header    = $this->db->get_where('tbl_apohresep', array('resepno' => $noresep))->row();
			$header_r  = $this->db->get_where('tbl_aporacik', array('resepno' => $noresep));
			$detil_r   = $this->db->get_where('tbl_apodetresep', array('resepno' => $noresep));
			$dresep    = $this->db->get_where('tbl_apodresep', array('resepno' => $noresep));
			$dataRacik = $this->db->query("SELECT * FROM tbl_aporacik where resepno ='$noresep'");
			$total     = $this->db->query("SELECT totalrp FROM tbl_apodetresep WHERE resepno = '$noresep'");
			$tglresep  = $this->M_cetak->tanggal_format_indonesia(substr($posting->tglresep,0,10));
			// $detil     = $this->db->select('tbl_apodresep.*,(SELECT aponame from tbl_barangsetup where  apocode=tbl_apodresep.atpakai)nm_atpakai, tbl_barang.namabarang as namabarang1')->join('tbl_barang', 'tbl_barang.kodebarang=tbl_apodresep.kodebarang')->get_where('tbl_apodresep', array('resepno' => $noresep))->result();
			$detil 		 =  $this->db->query("SELECT dr.*, aponame AS nm_atpakai, apocode FROM tbl_apodresep dr JOIN tbl_barangsetup bs ON bs.apocode = dr.atpakai WHERE dr.resepno = '$noresep'")->result();
			$body .= "<table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
				<tr>
					<td rowspan=\"6\" align=\"center\">
						<img src=\"" . base_url() . "assets/img_user/$avatar\"  width=\"70\" height=\"70\" />
					</td>
					<tr>
						<td align=\"center\" style=\"font-size:10px;border-bottom: none;\"><b><br>$namars</b></td>
					</tr>
					<tr>
						<td align=\"center\" style=\"font-size:9px;\">$alamat</td>
					</tr>
					<tr>
						<td align=\"center\" style=\"font-size:9px;\">$alamat2</td>
					</tr>
					<tr>
						<td align=\"center\" style=\"font-size:9px;\">Wa :$whatsapp    Telp :$phone </td>
					</tr>
					<tr>
						<td align=\"center\" style=\"font-size:9px;\">No. NPWP : $npwp</td>
					</tr>
					</td>
				</tr>
			</table>";
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"3\">     
				<tr>
					<td style=\"border-top: none;border-right: none;border-left: none;\"></td>
				</tr> 
			</table>";
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
				<tr>
					<td >&nbsp;</td>
				</tr> 
				<tr>
					<td width=\"20%\">Resep Dari </td>
					<td width=\"40%\">: <b>$header->kodokter</b></td>
					<td width=\"40%\">&nbsp;</td>
				</tr> 
				<tr>
					<td>Untuk </td>
					<td>: <b>$posting->namapas</b></td>
					<td>&nbsp;</td>
				</tr> 
				<tr>
					<td>Tanggal</td>
					<td>: <b>$tglresep</b></td>
					<td>&nbsp;</td>
				</tr> 
			</table>";
			$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
				<tr>
					<th width=\"5%\"></th>
					<th width=\"40%\"></th>
					<th width=\"15%\"></th>
					<th width=\"25%\"></th>
					<th width=\"15%\"></th>
				</tr> 
				<tr>
					<td colspan=\"5\" style=\"border-bottom: 1px solid #000;\"></td>
				</tr>";
			$no = 1;
			foreach ($detil as $db) {
				$body .= "<tr>
						<td><b>R/</b></td>
						<td>$db->namabarang</td>
						<td>$db->qty</td>
						<td>$db->satuan</td>
						<td align=\"right\">E-RESEP 1</td>
					</tr> 
					<tr>
						<td colspan=\"5\">$db->nm_atpakai</td>
					</tr> 
					<tr>
						<td align=\"center\" style=\"margin-bottom:5px;border-collapse:collapse;color:#000;border-bottom:1px dashed #000;\" colspan=\"5\"></td>
					</tr>";
				$no++;
			}
			$body .= "</table>"; 
			$position    = 'P';
			$cekpdf      = 1;
			$judul       = "TURUNAN" ;
			echo ("<title>$judul</title>");
			$this->M_cetak->template_nota(140, 190, $judul, $body, $position, $tglresep, $cekpdf);
		} else {
			header('location:' . base_url());
		}
	}

	public function email($param) {
		$cek    = $this->session->userdata('level');
		$unit   = $this->session->userdata('unit');
		if (!empty($cek)) {
			$profile       = $this->M_global->_LoadProfileLap();
			$unit          = $this->session->userdata('unit');
			$nama_usaha    = $profile->nama_usaha;
			$alamat1       = $profile->alamat1;
			$alamat2       = $profile->alamat2;
			$email_usaha   = $profile->email;
			$detil         = $this->db->query("SELECT ar_sidetail.*, inv_barang.namabarang from ar_sidetail inner join inv_barang on ar_sidetail.kodeitem=inv_barang.kodeitem where ar_sidetail.kodesi = '$param'")->result();
			$header        = $this->db->query("SELECT * from ar_sifile inner join ar_customer on ar_sifile.kodecust=ar_customer.kode where kodesi = '$param'")->row();
			$biaya         = $this->db->query("SELECT sum(jumlah) as total from ar_sibiaya  where ar_sibiaya.kodesi = '$param'")->row();
			$pdf           = new simkeu_nota();
			$pdf->setID($nama_usaha, $alamat1, $alamat2);
			$pdf->setjudul('');
			$pdf->setsubjudul('');
			$pdf->addpage("P", "A4");
			$pdf->setsize("P", "A4");
			$pdf->SetWidths(array(70, 30, 90));
			$border = array('T', '', 'BT');
			$size   = array('', '', '');
			$pdf->setfont('Arial', 'B', 18);
			$pdf->SetAligns(array('C', 'C', 'C'));
			$align = array('L', 'C', 'L');
			$style = array('', '', 'B');
			$size  = array('12', '', '18');
			$max   = array(5, 5, 20);
			$judul = array('Kepada :', '', 'Faktur Penjualan');
			$fc     = array('0', '0', '0');
			$hc     = array('20', '20', '20');
			$pdf->FancyRow2(10, $judul, $fc,  $border, $align, $style, $size, $max);
			$pdf->ln(1);
			$pdf->setfont('Arial', 'B', 10);
			$pdf->SetWidths(array(70, 30, 30, 5, 55));
			$border = array('', '', '', '', '');
			$fc     = array('0', '0', '0', '0', '0');
			$pdf->SetFillColor(230, 230, 230);
			$pdf->setfont('Arial', '', 9);
			$pdf->FancyRow(array($header->nama, '', 'Nomor', ':', $header->kodesi), $fc, $border);
			$pdf->FancyRow(array($header->alamat1, '', 'Tanggal', ':', date('d M Y', strtotime($header->tglsi))), $fc, $border);
			$pdf->FancyRow(array($header->alamat2, '', 'Tgl. Kirim', ':', date('d M Y', strtotime($header->tglkirim))), $fc, $border);
			$pdf->FancyRow(array($header->telp, '', 'Jatuh Tempo', ':', date('d M Y', strtotime($header->tgljthtempo))), $fc, $border);
			$pdf->ln(2);
			$pdf->SetWidths(array(30, 60, 20, 25, 25, 30));
			$border = array('TB', 'TB', 'TB', 'TB', 'TB', 'TB');
			$align  = array('L', 'L', 'R', 'R', 'R', 'R');
			$pdf->setfont('Arial', 'B', 10);
			$pdf->SetAligns(array('L', 'C', 'R'));
			$fc = array('0', '0', '0', '0', '0', '0');
			$judul = array('Kode Barang', 'Nama Barang', 'Qty', 'Harga', 'Diskon', 'Total Harga');
			$pdf->FancyRow2(8, $judul, $fc, $border, $align);
			$border = array('', '', '');
			$pdf->setfont('Arial', '', 10);
			$tot       = 0;
			$subtot    = 0;
			$tdisc     = 0;
			$border    = array('', '', '', '', '', '');
			$align     = array('L', 'L', 'R', 'R', 'R', 'R');
			$fc        = array('0', '0', '0', '0', '0', '0');
			$pdf->SetFillColor(0, 0, 139);
			$pdf->settextcolor(0);
			foreach ($detil as $db) {
				$dpp    = $db->qtysi * $db->hargajual;
				$dis    = ($db->disc / 100) * $dpp;
				$jum    = $dpp - $dis;
				$tot    = $tot + $jum;
				$subtot = $subtot + $dpp;
				$tdisc  = $tdisc + $dis;
				$pdf->FancyRow([
					$db->kodeitem,
					$db->namabarang,
					$db->qtysi,
					number_format($db->hargajual, 0, '.', ','),
					$db->disc,
					number_format($jum, 0, '.', ',')
				], $fc, $border, $align);
			}
			if ($header->sppn == "Y") {
				$ppn = $subtot * 0.1;
			} else {
				$ppn = 0;
			}
			$biayalain   = $biaya->total;
			$tot         += $ppn + $biayalain;
			$pdf->SetFillColor(230);
			$border = array('B', 'B', 'B', 'B', 'B', 'B');
			$pdf->FancyRow(array('', '', '', '', '', ''), 0, $border);
			$pdf->ln(2);
			$pdf->SetWidths(array(100, 20, 30, 40));
			$border    = array('TB', '', 'T', 'T');
			$align     = array('L', '', 'L', 'R');
			$fc        = array('0', '0', '0', '0');
			$pdf->FancyRow(array('Keterangan', '', 'Sub Total', number_format($subtot, 0, '.', ',')), $fc, $border, $align, 0);
			$border = array('', '', '', '');
			$pdf->FancyRow(array($header->ket, '', 'Diskon', number_format($tdisc, 0, '.', ',')), $fc, $border, $align);
			$pdf->FancyRow(array('', '', 'PPN (10%)', number_format($ppn, 0, '.', ',')), $fc, $border, $align);
			$pdf->FancyRow(array('', '', 'Biaya Lain-lain', number_format($biayalain, 0, '.', ',')), $fc, $border, $align);
			$style   = array('', '', 'B', 'B');
			$size    = array('', '', '', '');
			$border  = array('T', '', 'BT', 'BT');
			$fc      = array('0', '0', '0', '0');
			$pdf->FancyRow(array('', '', 'Total', number_format($tot, 0, '.', ',')), $fc, $border, $align, $style, $size);
			$pdf->settextcolor(0);
			$pdf->SetWidths(array(50, 50));
			$pdf->SetFont('Arial', '', 9);
			$pdf->SetAligns(array('C', 'C'));
			$pdf->ln(5);
			$border = array('', '');
			$align  = array('C', 'C');
			$pdf->FancyRow(array('Disiapkan Oleh', 'Disetujui Oleh'), 0, $border, $align);
			$pdf->ln(1);
			$pdf->ln(15);
			$pdf->SetWidths(array(49, 2, 49));
			$pdf->SetFont('Arial', '', 8);
			$pdf->SetAligns(array('C', 'C', 'C'));
			$border = array('B', '', 'B');
			$pdf->FancyRow(array('', '', ''), 0, $border, $align);
			$border = array('', '', '');
			$align  = array('L', 'C', 'L');
			$pdf->FancyRow(array('Tgl.', '', 'Tgl.'), 0, $border, $align);
			$pdf->AliasNbPages();
			$pdf->output('./uploads/si/' . $param . '.PDF', 'F');
			$email_tujuan    = trim($header->email);
			$nama_tujuan     = $header->nama;
			$server_subject  = "Faktur Penjualan ";
			$ready_message   = "
				Kepada Yth " . $nama_tujuan . ",
				Berikut ini kami lampirkan File Faktur Penjualan ";
			$attched_file 	 = './uploads/si/' . $param . '.PDF';
			$this->load->library('email');
			$config['protocol']    = "smtp";
			$config['smtp_host']   = $profile->smtp_host;
			$config['smtp_port']   = $profile->smtp_port;
			$config['smtp_user']   = $profile->email;
			$config['smtp_pass']   = $profile->pwdemail;
			$config['smtp_crypto'] = 'tls';
			$config['charset']     = "utf-8";
			$config['mailtype']    = "html";
			$config['newline']     = "\r\n";
			$this->email->from($email_usaha, $nama_usaha);
			$this->email->to($email_tujuan);
			$this->email->subject($server_subject);
			$this->email->message($ready_message);
			$this->email->attach($attched_file);
			if ($this->email->send()) {
				echo "success";
			} else {
				echo "failed";
			}
		} else {
			header('location:' . base_url());
		}
	}

	public function cekstok() {
		$barang   = $this->input->get('kode');
		$gudang   = $this->input->get('gudang');
		$cabang   = $this->session->userdata('unit');
		$data     = $this->db->query("SELECT * FROM tbl_barangstock WHERE kodebarang = '$barang' AND koders = '$cabang' AND gudang = '$gudang'")->row_array();
		if ($data) {
			$jumlah = $data['saldoakhir'];
			echo $jumlah;
		} else {
			echo json_encode(['status' => 2]);
		}
	}

	public function cekharga() {
		$barang = $this->input->get('kode');
		$jumlah = data_master('tbl_barang', array('kodebarang' => $barang))->hargabelippn;
		echo $jumlah;
	}

	public function getpo($po) {
		$data = $this->db->select('ar_sodetail.*, inv_barang.namabarang')->join('inv_barang', 'inv_barang.kodeitem=ar_sodetail.kodeitem', 'left')->get_where('ar_sodetail', array('kodeso' => $po))->result();
		echo json_encode($data);
	}

	public function getsd($po) {
		$data = $this->db->select('ar_kirimdetil.*, inv_barang.namabarang')->join('inv_barang', 'inv_barang.kodeitem=ar_kirimdetil.kodeitem', 'left')->get_where('ar_kirimdetil', array('kodekirim' => $po))->result();
		echo json_encode($data);
	}

	public function getctk() {
		$resep    = $this->input->get('noresep');
		$data     = $this->db->select('tbl_apodresep.*, (SELECT aponame from tbl_barangsetup where  apocode=tbl_apodresep.atpakai)nm_atpakai, tbl_barang.namabarang as namabarang1')->join('tbl_barang', 'tbl_barang.kodebarang=tbl_apodresep.kodebarang')->get_where('tbl_apodresep', array('resepno' => $resep))->result();

		// $data     = $this->db->query("SELECT tbl_apodresep.resepno,tbl_apodresep.cetak,tbl_apodresep.kodebarang, (SELECT aponame from tbl_barangsetup where apocode=tbl_apodresep.atpakai)nm_atpakai, tbl_barang.namabarang as namabarang1 FROM tbl_apodresep JOIN tbl_barang ON tbl_barang.kodebarang=tbl_apodresep.kodebarang WHERE resepno = '$resep'
		// UNION ALL
		// SELECT tbl_aporacik.resepno,tbl_aporacik.cetak,''kodebarang,(SELECT aponame from tbl_barangsetup where apocode=tbl_aporacik.aturanpakai)nm_atpakai,namaracikan as namabarang1  FROM tbl_aporacik where resepno='$resep'")->result();
		echo json_encode($data);
	}

	public function get_telaah() {
		$resep    = $this->input->get('noresep');
		$data     = $this->db->query("SELECT*,(select ok from tbl_apotelaah b where a.kode=b.kode and orderno='$resep')cek from tbl_aspektelaah a")->result();
		echo json_encode($data);
	}

	public function updt_ctk() {
		$barang           = $this->input->get('kd');
		$resep            = $this->input->get('resep');
		$stat             = $this->input->get('stat');
		$cabang           = $this->session->userdata('unit');
		$query            = $this->db->query("UPDATE tbl_apodresep SET cetak = '$stat' WHERE kodebarang = '$barang' AND resepno = '$resep'");
		$data['cetak']    = $stat;
		echo json_encode(['status' => 1]);
	}

	public function updt_telaah() {
		$kode         = $this->input->get('kd');
		$resep        = $this->input->get('resep');
		$stat         = $this->input->get('stat');
		$cabang       = $this->session->userdata('unit');
		$data['ok']   = $stat;
		$cek          = $this->db->query("SELECT*FROM tbl_apotelaah WHERE orderno='$resep' and kode='$kode' ")->num_rows();
		if($cek > 0) {
			$query = $this->db->update('tbl_apotelaah', $data, array('orderno' => $resep,'kode' => $kode));		
		} else {
			$aspek  = $this->db->get_where('tbl_aspektelaah', array('kode' => $kode))->row();
			$data = [
				'orderno'	=> $resep,
				'kode'   	=> $kode,
				'aspek'   => $aspek->aspek,
				'ok'   		=> 1,
				'resepno' => $resep,
			];
			$this->db->insert('tbl_apotelaah', $data);
		}
		echo json_encode(['status' => 1]);
	}

	public function getbiaya($po) {
		$data = $this->db->select('ar_sobiaya.*, ms_akun.namaakun')->join('ms_akun', 'ms_akun.kodeakun=ar_sobiaya.kodeakun', 'left')->get_where('ar_sobiaya', array('kodeso' => $po))->result();
		echo json_encode($data);
	}

	public function getlistpo($supp) {
		if (!empty($supp)) {
			$po  = $this->db->get_where('ar_sofile', array('kodecust' => $supp, 'statusid' => 1))->result();
		?>
			<select name="kodeso" id="kodeso" class="form-control  input-medium select2me">
				<option value="">-- Tanpa SO ---</option>
				<?php
				foreach ($po  as $row) {
				?>
					<option value="<?php echo $row->kodeso; ?>"><?php echo $row->kodeso; ?></option>
				<?php } ?>
			</select>
		<?php } else { echo ""; } 
	}

	public function getlistsd($supp) {
		if (!empty($supp)) {
			$po  = $this->db->query("select * from ar_kirim where kodekirim not in(select kodesd from ar_sifile)")->result();
		?>
			<select name="kodesd" id="kodesd" class="form-control  input-medium select2me">
				<option value="">-- Tanpa SD ---</option>
				<?php
				foreach ($po  as $row) {
				?>
					<option value="<?php echo $row->kodekirim; ?>"><?php echo $row->kodekirim; ?></option>
				<?php } ?>
			</select>
		<?php
		} else {
			echo "";
		}
	}

	public function entri() {
		$cek = $this->session->userdata('level');
		$uid = $this->session->userdata('unit');
		if (!empty($cek)) {
			$page          = $this->uri->segment(3);
			$limit         = $this->config->item('limit_data');
			$d['nomor']    = urut_transaksi('URUT_BHP', 19);
			$d['ppn']      = $this->db->get_where('tbl_pajak', ['kodetax' => 'PPN'])->row_array();
			$d['atpakaix'] = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='ATURANPAKAI' ")->result();
			$this->load->view('penjualan/v_penjualan_faktur_add2', $d);
		} else {
			header('location:' . base_url());
		}
	}

	public function getakun($kode) {
		if (!empty($kode)) {
			$q = $kode;
			$query = "select * from ms_akun where (kodeakun like '%$q%' or namaakun like '%$q%') and kodeakun like '6%' and akuninduk<>''";
			$data  = $this->db->query($query);
		?>
		<table id="myTable">
			<tr class="header">
				<th style="width:20%;">Kode</th>
				<th style="width:80%;">Nama</th>
			</tr>
			<?php foreach ($data->result_array() as $row) { ?>
				<tr>
					<td width="50" align="center">
						<a href="#" onclick="post_akun('<?php echo $row['kodeakun']; ?>','<?php echo $row['namaakun']; ?>')"><?php echo $row['kodeakun']; ?></a>
					</td>
					<td><?php echo $row['namaakun']; ?></td>
				</tr>
			<?php
			}
			echo "</table>";
		} else {
			echo "";
		}
	}

	public function getbarang($kode) {
		if (!empty($kode)) {
			$data    = explode("~", $kode);
			$q       = $data[0];
			$cust    = $data[1];
			$jenis   = $this->db->get_where('ar_customer', array('kode' => $cust))->row()->tipe;
			$data    = $this->db->query("SELECT * from inv_barang where kodeitem like '%$q%' or namabarang like '%$q%' order by namabarang");
			?>
			<table id="myTable">
				<tr class="header">
					<th style="width:20%;">Kode</th>
					<th style="width:60%;">Nama</th>
					<th style="width:10%;">Stok</th>
					<th style="width:10%;">Satuan</th>
				</tr>
				<?php foreach ($data->result_array() as $row) {
					if ($jenis == 1) {
						$harga = $row['hargajual1'];
					} else if ($jenis == 2) {
						$harga = $row['hargajual2'];
					} else if ($jenis == 3) {
						$harga = $row['hargajual3'];
					}
				?>
				<tr>
					<td width="50" align="center">
						<a href="#" onclick="post_value('<?php echo $row['kodeitem']; ?>','<?php echo $row['namabarang']; ?>','<?php echo $row['satuan']; ?>','<?php echo $harga; ?>')"><?php echo $row['kodeitem']; ?></a>
					</td>
					<td><?php echo $row['namabarang']; ?></td>
					<td><?php echo $row['qty']; ?></td>
					<td><?php echo $row['satuan']; ?></td>
				</tr>
				<?php
			}
			echo "</table>";
		} else {
			echo "";
		}
	}

	public function getharga($kode) {
		if (!empty($kode)) {
			$data  = explode("~", $kode);
			$supp  = $data[0];
			$item  = $data[1];
			$data  = $this->db->query("SELECT * from ar_sidetail inner join ar_sifile on ar_sifile.kodesi=ar_sidetail.kodesi where ar_sifile.kodecust = '$supp' and ar_sidetail.kodeitem = '$item' order by ar_sifile.tglsi desc")->result();
		?>
		<table class="table" id="myTable">
			<tr class="headerx">
				<th style="width:20%;">No. Faktur</th>
				<th style="width:20%;">Tanggal</th>
				<th style="width:20%;">Harga</th>
				<th style="width:10%;">Disc</th>
				<th style="width:10%;">Satuan</th>
			</tr>
			<?php foreach ($data  as $row) { ?>
			<tr>
				<td width="50" align="centerx">
					<a href="#" onclick="post_harga('<?php echo $row->hargajual; ?>','<?php echo $row->satuan; ?>')"><?php echo $row->kodesi; ?></a>
				</td>
				<td><?php echo date('d-m-Y', strtotime($row->tglsi)); ?></td>
				<td><?php echo $row->hargajual; ?></td>
				<td><?php echo $row->disc; ?></td>
				<td><?php echo $row->satuan; ?></td>
			</tr>
			<?php } echo "</table>"; 
		} else {
			echo "";
		}
	}

	public function getinfobarang() {		
		$cabang   = $this->session->userdata("unit");
		$kode     = $this->input->get('kode');
		$gudang   = $this->input->get('gudang');
		$data     = $this->db->query("SELECT b.*, bc.saldoakhir FROM tbl_barang b JOIN tbl_barangstock bc ON b.kodebarang = bc.kodebarang WHERE bc.koders = '$cabang' AND bc.gudang = '$gudang' AND b.kodebarang = '$kode' GROUP BY bc.kodebarang")->row();
		echo json_encode($data);
	}

	public function ceksaldoakhir($kode) {
		$cabang   = $this->session->userdata("unit");
		$gudang   = $this->input->get("gudang");
		$data     = $this->db->query("SELECT * FROM tbl_barangstock WHERE kodebarang = '$kode' AND gudang = '$gudang' AND koders = '$cabang'")->row();
		echo json_encode($data);
	}

	public function getinfobarang_cbg() {
		$cabang   = $this->session->userdata("unit");
		$kode     = $this->input->get('kode');
		$gudang   = $this->input->get('gudang');
		$cek      = $this->db->query("SELECT * FROM tbl_barang WHERE kodebarang = '$kode'");
		if($cek->num_rows() > 0){
			$data = $cek->row();
		} else {
			$data = $this->db->query("SELECT * FROM tbl_logbarang WHERE kodebarang = '$kode'")->row();
		} 
		echo json_encode($data);
	}

	public function gethargapenjamin() {
		$cust_id    = $this->input->get("cust_id");
		$data       = $this->db->query("SELECT farmasirj AS harga FROM tbl_penjamin WHERE cust_id = '$cust_id'")->row();
		echo json_encode($data);
	}

	public function getinfoakun($kode) {
		$data = $this->M_global->_data_akun($kode);
		echo json_encode($data);
	}

	public function getpoheader($kodepo) {
		$data = $this->db->get_where('ar_sifile', array('kodesi' => $kodepo))->row();
		echo json_encode($data);
	}

	public function getbarangname($kode) {
		if (!empty($kode)) {
			$query = "select namabarang from inv_barang where kodeitem = '$kode'";
			$data  = $this->db->query($query);
			foreach ($data->result_array() as $row) {
				echo $row['namabarang'];
			}
		} else {
			echo "";
		}
	}

	public function norespauto() {
		$kode_cabang    = $this->session->userdata('unit');
		$nobukti        = temp_urut_transaksi('RESEP', $kode_cabang, 19);
		echo json_encode($nobukti);
	}

	public function save_pasien() {
		$user               = $this->session->userdata('username');
		$cabang             = $this->session->userdata('unit');
		$c_lupnamapasienx   = $this->input->post('lupnamapasien');
		$c_lupnamapasien    = preg_replace('/\s+/', ' ', $c_lupnamapasienx);
		$c_vpenjamin        = $this->input->post('vpenjamin');
		$c_lupidentitas     = $this->input->post('lupidentitas');
		$c_lupnoidentitas   = $this->input->post('lupnoidentitas');
		$c_no_bpjs          = $this->input->post('no_bpjs');
		$c_luppreposition   = $this->input->post('luppreposition');
		$c_luphp            = $this->input->post('luphp');
		$c_lupalamat        = $this->input->post('lupalamat');
		$c_jkelp            = $this->input->post('jkelp');
		$c_tgllahirp        = $this->input->post('tgllahirp');
		$q                  = $this->input->post('searchTerm');
		$qry = $this->db->query("SELECT * from tbl_pasien where namapas = '$c_lupnamapasien' and  penjamin='$c_vpenjamin' and nocard='$c_no_bpjs' ")->num_rows();
		if($qry > 0){
			echo json_encode(["status" => true,"value" => 0]);	
		}else{
			$jumdata = $this->db->query("SELECT * from tbl_pasien where noidentitas = '$c_lupnoidentitas' ")->num_rows();
			if($jumdata > 0) {
				echo json_encode(["status" => true,"value" => 0]);	
			} else {
				$rekmed     = pasien_rekmed_baru($c_lupnamapasien);
				$data = [
					'rekmed'      => $rekmed,
					'koders'      => $cabang,
					'preposisi'   => $c_luppreposition,
					'namapas'     => $c_lupnamapasien,
					'handphone'   => $c_luphp,
					'noidentitas' => $c_lupnoidentitas,
					'alamat'      => $c_lupalamat,
					'idpas'       => $c_lupidentitas,
					'nocard'      => $c_no_bpjs,
					'penjamin'    => $c_vpenjamin,
					'jkel  '      => $c_jkelp,
					'tgllahir'    => $c_tgllahirp,
				];
				$insert = $this->M_pasien->save($data);
				echo json_encode([
					"status" 	=> true,
					"value" 	=> 1, 
					"idtr" 		=> $insert,
					"rekmed" 	=> $rekmed,
					"nama" 		=> $c_lupnamapasien, 
					"alamat" 	=> $c_lupalamat
				]);
			} 
		}
	}

	public function saveracik($param) {
		$hasil    			= 0;
		$cek      			= $this->session->userdata('level');
		$userid   			= $this->session->userdata('username');
		$cabang   			= $this->session->userdata('unit');
		$gudang					= $this->input->post("gudang");
		$kode_cabang 		= $this->session->userdata('unit');
		if($param == 1) {
			$nobukti  		= temp_urut_transaksi('RESEP', $kode_cabang, 19);
		} else {
			$nobukti      = $this->input->post("noresep");
			$dresep    		= $this->db->get_where('tbl_apodresep', ['resepno' => $nobukti])->result();
			foreach ($dresep as $row) {
				$_qty 			= $row->qty;
				$_kode 			= $row->kodebarang;
				$this->db->query("UPDATE tbl_barangstock SET keluar = keluar - $_qty, saldoakhir = saldoakhir + $_qty WHERE kodebarang = '$_kode' AND koders = '$cabang' AND gudang = '$gudang'");
			}
		}
		$ppn_pajak_insx = $this->db->get_where('tbl_pajak', ['kodetax' => 'PPN'])->row_array();
		$ppn_pajak_ins 	= $ppn_pajak_insx['prosentase'] / 100;
		if (!empty($cek)) {
			// RACIKAN 1
			$noresepo    			= $nobukti;
			$jenis_1     			= $this->input->post('jenis_1');
			$namaracik_1 			= $this->input->post('namaracik_1');
			$jumracik_1  			= $this->input->post('jumracik_1');
			$stajum_1    			= $this->input->post('stajum_1');
			$atpakai_1   			= $this->input->post('atpakai_1');
			$toto_1      			= $this->input->get('toto_racikan_1');
			$disknom_1   			= $this->input->post('disknom_racik_1');
			$disk_1      			= $this->input->get('disk_racik_1');
			$ongra_1     			= $this->input->post('ongra_racik_1');
			$carapakai_1     	= $this->input->post('carapakai_1');
			$totp_1      			= $this->input->get('totp_racik_1');
			$resep_manual     = $this->input->get('resman_racik_1');
			$cek_rm      			= $this->input->get('cek_rm1');
			if($cek_rm == null || $cek_rm == "") {
				$cek_rm = 0;
			} else {
				$cek_rm = $cek_rm;
			}
			$harga_manual     = $this->input->post('toto_racik_1');
			$cekppn_1 				= $ppn_pajak_ins;
			$ppn_1 						= (($totp_1) / (111 / 100)) * $ppn_pajak_ins;
			$cekid						= $this->db->query("SELECT id from tbl_aporacik order by id desc limit 1")->result();
			foreach ($cekid as $row) {
				$idd    = $row->id;
				if ($idd == null) {
					$idd 	= 0;
				} else {
					$idd 	= $idd;
				}
				$id_ok  = $idd + 1;
			}
			if($param > 1) {
				$this->db->query("DELETE FROM tbl_aporacik WHERE noracik = 1 AND resepno = '$noresepo'");
			}
			$data = [
				'id'		   			=> $id_ok,
				'noracik'      	=> 1,
				'koders'       	=> $cabang,
				'resepno'      	=> $noresepo,
				'jenisracik'   	=> $jenis_1,
				'namaracikan'  	=> $namaracik_1,
				'aturanpakai'  	=> $atpakai_1,
				'jumlahracik'  	=> $jumracik_1,
				'kemasanracik' 	=> $stajum_1,
				'carapakai'  		=> $carapakai_1,
				'subtotal'     	=> $toto_1,
				'diskon'       	=> $disknom_1,
				'resep_manual' 	=> $resep_manual,
				'diskonrp'     	=> str_replace(',', '', $disk_1),
				'ppn'          	=> $cekppn_1,
				'ppnrp'        	=> str_replace(',', '', $ppn_1),
				'ongkosracik'  	=> str_replace(',', '', $ongra_1),
				'totalrp'      	=> str_replace(',', '', $totp_1),
				'cek_rm'      	=> $cek_rm,
				'harga_manual'  => str_replace(',', '', $harga_manual),
			];
			$this->db->insert('tbl_aporacik', $data);

			$koderacik_1      	= $this->input->post('koderacik_1');
			$namaracik_1      	= $this->input->post('nama_racik_1');
			$satracik_1       	= $this->input->post('satracik_1');
			$qty_jual_racik_1   = $this->input->post('qty_jualracik_1');
			$qty_racik_racik_1  = $this->input->post('qty_racik_racik_1');
			$hargajual_racik_1  = $this->input->post('hargajualracik_1');
			$total_hrg_racik_1  = $this->input->post('total_hrg_racik_1');
			$exp_racik_1    		= $this->input->post('exp_racik_1');

			$jumdata   = $this->input->get('jml1');
			$nourut    = 1;
			$tot       = 0;
			$tdisc     = 0;
			$tothpp    = 0;

			if($param > 1) {
				$cekr1 = $this->db->get_where("tbl_apodetresep", ["resepno" => $noresepo, "resepid" => 1])->result();
				foreach ($cekr1 as $cr1) {
					$this->db->query("UPDATE tbl_barangstock SET keluar = keluar - $cr1->qty, terima = terima + $cr1->qty, saldoakhir = saldoakhir + $cr1->qty");
				}
				$this->db->query("DELETE FROM tbl_apodetresep WHERE resepno = '$noresepo' AND resepid = 1");
			}

			for ($i = 0; $i <= ($jumdata - 1); $i++) {
				$_koderacik_1       		= $koderacik_1[$i];
				$_namaracik_1       		= $namaracik_1[$i];
				$_satracik_1        		= $satracik_1[$i];
				$_qty_racik_racik_1   	= $qty_racik_racik_1[$i];
				$_qty_jual_racik_1    	= $qty_jual_racik_1[$i];
				$_exp_racik_1        		= $exp_racik_1[$i];
				$_hargajual_racik_1     = preg_replace('/[,]/', '', $hargajual_racik_1[$i]);
				$_total_hrg_racik_1     = preg_replace('/[,]/', '', $total_hrg_racik_1[$i]);
				$hpp1 									= $this->db->get_where('tbl_barang', ['kodebarang' => $_koderacik_1])->row_array();
				$hpp 										= $hpp1['hpp'];
				$barang 								= $this->db->query("SELECT * FROM tbl_barang WHERE kodebarang = '$_koderacik_1'")->row();

				$datad = [
					'koders'      => $cabang,
					'resepid'     => 1,
					'hpp'      	  => $hpp,
					'resepno'     => $noresepo,
					'kodebarang'  => $_koderacik_1,
					'namabarang'  => $barang->namabarang,
					'satuan'      => $_satracik_1,
					'qtyr'        => $_qty_racik_racik_1,
					'qty'         => $_qty_jual_racik_1,
					'uangr'       => 0,
					'price'       => $_hargajual_racik_1,
					'totalrp'     => $_total_hrg_racik_1,
					'exp_date'    => date('Y-m-d H:i:s', strtotime($_exp_racik_1)),
					'jamdresep'   => date('H:i:s'),
				];
				
				if ($_koderacik_1 != "") {
					$this->db->insert('tbl_apodetresep', $datad);

					$cekstok = $this->db->query("SELECT * FROM tbl_barangstock WHERE kodebarang = '$_koderacik_1' AND koders = '$cabang'  AND gudang = '$gudang'")->num_rows();
					$date_now = date('Y-m-d H:i:s');
					if($cekstok > 0){
						$this->db->query("UPDATE tbl_barangstock SET keluar = keluar + $_qty_jual_racik_1, saldoakhir = saldoakhir - $_qty_jual_racik_1, lasttr = '$date_now' WHERE kodebarang = '$_koderacik_1' AND koders = '$cabang'  AND gudang = '$gudang'");
					} else {
						$datastock = [
							'koders'       => $cabang,
							'kodebarang'   => $_koderacik_1,
							'gudang'       => $gudang,
							'saldoawal'    => 0,
							'terima'    	 => 0,
							'keluar'       => $_qty_jual_racik_1,
							'saldoakhir'   => 0 - $_qty_jual_racik_1,
							'lasttr'       => $date_now,
						];
						$this->db->insert('tbl_barangstock', $datastock);
					}
				}
			}

			// RACIKAN 2
			$namaracik_2 			= $this->input->post('namaracik_2');
			if($namaracik_2 != '' || $namaracik_2 != null) {
				$jenis_2     			= $this->input->post('jenis_2');
				$jumracik_2  			= $this->input->post('jumracik_2');
				$stajum_2    			= $this->input->post('stajum_2');
				$atpakai_2   			= $this->input->post('atpakai_2');
				$toto_2      			= $this->input->get('toto_racikan_2');
				$disknom_2   			= $this->input->post('disknom_racik_2');
				$disk_2      			= $this->input->get('disk_racik_2');
				$ongra_2     			= $this->input->post('ongra_racik_2');
				$carapakai_2     	= $this->input->post('carapakai_2');
				$totp_2      			= $this->input->get('totp_racik_2');
				$resep_manual_2   = $this->input->get('resman_racik_2');
				$cek_rm_2      		= $this->input->get('cek_rm2');
				$harga_manual_2   = $this->input->post('toto_racik_2');
				$cekppn_2 				= $ppn_pajak_ins;
				$ppn_2 						= (($totp_2) / (111 / 100)) * $ppn_pajak_ins;
				$cekid2						= $this->db->query("SELECT id from tbl_aporacik order by id desc limit 1")->result();
				foreach ($cekid2 as $row2) {
					$idd2    = $row2->id;
					if ($idd2 == null) {
						$idd2 	= 0;
					} else {
						$idd2 	= $idd2;
					}
					$id_ok2  = $idd2 + 1;
				}
				if($param > 1) {
					$this->db->query("DELETE FROM tbl_aporacik WHERE noracik = 2 AND resepno = '$noresepo'");
				}
				$data2 = [
					'id'		   			=> $id_ok2,
					'noracik'      	=> 2,
					'koders'       	=> $cabang,
					'resepno'      	=> $noresepo,
					'jenisracik'   	=> $jenis_2,
					'namaracikan'  	=> $namaracik_2,
					'aturanpakai'  	=> $atpakai_2,
					'jumlahracik'  	=> $jumracik_2,
					'kemasanracik' 	=> $stajum_2,
					'carapakai'  		=> $carapakai_2,
					'subtotal'     	=> $toto_2,
					'diskon'       	=> $disknom_2,
					'resep_manual' 	=> $resep_manual_2,
					'diskonrp'     	=> str_replace(',', '', $disk_2),
					'ppn'          	=> $cekppn_2,
					'ppnrp'        	=> str_replace(',', '', $ppn_2),
					'ongkosracik'  	=> str_replace(',', '', $ongra_2),
					'totalrp'      	=> str_replace(',', '', $totp_2),
					'cek_rm'      	=> $cek_rm_2,
					'harga_manual'  => str_replace(',', '', $harga_manual_2),
				];
				$this->db->insert('tbl_aporacik', $data2);
				
				$koderacik_2      	= $this->input->post('koderacik_2');
				$namaracik_2      	= $this->input->post('nama_racik_2');
				$satracik_2       	= $this->input->post('satracik_2');
				$qty_jual_racik_2   = $this->input->post('qty_jualracik_2');
				$qty_racik_racik_2  = $this->input->post('qty_racik_racik_2');
				$hargajual_racik_2  = $this->input->post('hargajualracik_2');
				$total_hrg_racik_2  = $this->input->post('total_hrg_racik_2');
				$exp_racik_2    		= $this->input->post('exp_racik_2');
	
				$jumdata2   = $this->input->get('jml2');
				$nourut2    = 1;
				$tot2       = 0;
				$tdisc2     = 0;
				$tothpp2    = 0;

				if($param > 1) {
					$cekr2 = $this->db->get_where("tbl_apodetresep", ["resepno" => $noresepo, "resepid" => 2])->result();
					foreach ($cekr2 as $cr2) {
						$this->db->query("UPDATE tbl_barangstock SET keluar = keluar - $cr2->qty, terima = terima + $cr2->qty, saldoakhir = saldoakhir + $cr2->qty");
					}
					$this->db->query("DELETE FROM tbl_apodetresep WHERE resepno = '$noresepo' AND resepid = 2");
				}
	
				for ($i = 0; $i <= ($jumdata2 - 1); $i++) {
					$_koderacik_2       		= $koderacik_2[$i];
					$_namaracik_2       		= $namaracik_2[$i];
					$_satracik_2        		= $satracik_2[$i];
					$_qty_racik_racik_2   	= $qty_racik_racik_2[$i];
					$_qty_jual_racik_2    	= $qty_jual_racik_2[$i];
					$_exp_racik_2        		= $exp_racik_2[$i];
					$_hargajual_racik_2     = preg_replace('/[,]/', '', $hargajual_racik_2[$i]);
					$_total_hrg_racik_2     = preg_replace('/[,]/', '', $total_hrg_racik_2[$i]);
					$hpp12 									= $this->db->get_where('tbl_barang', ['kodebarang' => $_koderacik_2])->row_array();
					$hpp2 									= $hpp12['hpp'];
					$barang2 								= $this->db->query("SELECT * FROM tbl_barang WHERE kodebarang = '$_koderacik_2'")->row();
					
					$datad2 = [
						'koders'      => $cabang,
						'resepid'     => 2,
						'hpp'      	  => $hpp2,
						'resepno'     => $noresepo,
						'kodebarang'  => $_koderacik_2,
						'namabarang'  => $barang2->namabarang,
						'satuan'      => $_satracik_2,
						'qtyr'        => $_qty_racik_racik_2,
						'qty'         => $_qty_jual_racik_2,
						'uangr'       => 0,
						'price'       => $_hargajual_racik_2,
						'totalrp'     => $_total_hrg_racik_2,
						'exp_date'    => date('Y-m-d H:i:s', strtotime($_exp_racik_2)),
						'jamdresep'   => date('H:i:s'),
					];

					if ($_koderacik_2 != "") {
						$this->db->insert('tbl_apodetresep', $datad2);

						$cekstok2 = $this->db->query("SELECT * FROM tbl_barangstock WHERE kodebarang = '$_koderacik_2' AND koders = '$cabang'  AND gudang = '$gudang'")->num_rows();
						$date_now = date('Y-m-d H:i:s');
						if($cekstok2 > 0){
							$this->db->query("UPDATE tbl_barangstock SET keluar = keluar + $_qty_jual_racik_2, saldoakhir = saldoakhir - $_qty_jual_racik_2, lasttr = '$date_now' WHERE kodebarang = '$_koderacik_2' AND koders = '$cabang'  AND gudang = '$gudang'");
						} else {
							$datastock2 = [
								'koders'       => $cabang,
								'kodebarang'   => $_koderacik_2,
								'gudang'       => $gudang,
								'saldoawal'    => 0,
								'terima'    	 => 0,
								'keluar'       => $_qty_jual_racik_2,
								'saldoakhir'   => 0 - $_qty_jual_racik_2,
								'lasttr'       => $date_now,
							];
							$this->db->insert('tbl_barangstock', $datastock2);
						}
					}
				}
			}

			// RACIKAN 3
			$namaracik_3 			= $this->input->post('namaracik_3');
			if($namaracik_3 != '' || $namaracik_3 != null) {
				$jenis_3     			= $this->input->post('jenis_3');
				$jumracik_3  			= $this->input->post('jumracik_3');
				$stajum_3    			= $this->input->post('stajum_3');
				$atpakai_3   			= $this->input->post('atpakai_3');
				$toto_3      			= $this->input->get('toto_racikan_3');
				$disknom_3   			= $this->input->post('disknom_racik_3');
				$disk_3      			= $this->input->get('disk_racik_3');
				$ongra_3     			= $this->input->post('ongra_racik_3');
				$carapakai_3     	= $this->input->post('carapakai_3');
				$totp_3      			= $this->input->get('totp_racik_3');
				$resep_manual_3   = $this->input->get('resman_racik_3');
				$cek_rm_3      		= $this->input->get('cek_rm3');
				$harga_manual_3   = $this->input->post('toto_racik_3');
				$cekppn_3 				= $ppn_pajak_ins;
				$ppn_3 						= (($totp_3) / (111 / 100)) * $ppn_pajak_ins;
				$cekid3						= $this->db->query("SELECT id from tbl_aporacik order by id desc limit 1")->result();
				foreach ($cekid3 as $row3) {
					$idd3    = $row3->id;
					if ($idd3 == null) {
						$idd3 	= 0;
					} else {
						$idd3 	= $idd3;
					}
					$id_ok3  = $idd3 + 1;
				}
				if($param > 1) {
					$this->db->query("DELETE FROM tbl_aporacik WHERE noracik = 3 AND resepno = '$noresepo'");
				}
				$data3 = [
					'id'		   			=> $id_ok3,
					'noracik'      	=> 3,
					'koders'       	=> $cabang,
					'resepno'      	=> $noresepo,
					'jenisracik'   	=> $jenis_3,
					'namaracikan'  	=> $namaracik_3,
					'aturanpakai'  	=> $atpakai_3,
					'jumlahracik'  	=> $jumracik_3,
					'kemasanracik' 	=> $stajum_3,
					'carapakai'  		=> $carapakai_3,
					'subtotal'     	=> $toto_3,
					'diskon'       	=> $disknom_3,
					'resep_manual' 	=> $resep_manual_3,
					'diskonrp'     	=> str_replace(',', '', $disk_3),
					'ppn'          	=> $cekppn_3,
					'ppnrp'        	=> str_replace(',', '', $ppn_3),
					'ongkosracik'  	=> str_replace(',', '', $ongra_3),
					'totalrp'      	=> str_replace(',', '', $totp_3),
					'cek_rm'      	=> $cek_rm_3,
					'harga_manual'  => str_replace(',', '', $harga_manual_3),
				];
				$this->db->insert('tbl_aporacik', $data3);

				$koderacik_3      	= $this->input->post('koderacik_3');
				$namaracik_3      	= $this->input->post('nama_racik_3');
				$satracik_3       	= $this->input->post('satracik_3');
				$qty_jual_racik_3   = $this->input->post('qty_jualracik_3');
				$qty_racik_racik_3  = $this->input->post('qty_racik_racik_3');
				$hargajual_racik_3  = $this->input->post('hargajualracik_3');
				$total_hrg_racik_3  = $this->input->post('total_hrg_racik_3');
				$exp_racik_3    		= $this->input->post('exp_racik_3');
	
				$jumdata3   = $this->input->get('jml3');
				$nourut3    = 1;
				$tot3       = 0;
				$tdisc3     = 0;
				$tothpp3    = 0;

				if($param > 1) {
					$cekr3 = $this->db->get_where("tbl_apodetresep", ["resepno" => $noresepo, "resepid" => 3])->result();
					foreach ($cekr3 as $cr3) {
						$this->db->query("UPDATE tbl_barangstock SET keluar = keluar - $cr3->qty, terima = terima + $cr3->qty, saldoakhir = saldoakhir + $cr3->qty");
					}
					$this->db->query("DELETE FROM tbl_apodetresep WHERE resepno = '$noresepo' AND resepid = 3");
				}
	
				for ($i = 0; $i <= ($jumdata3 - 1); $i++) {
					$_koderacik_3       		= $koderacik_3[$i];
					$_namaracik_3       		= $namaracik_3[$i];
					$_satracik_3        		= $satracik_3[$i];
					$_qty_racik_racik_3   	= $qty_racik_racik_3[$i];
					$_qty_jual_racik_3    	= $qty_jual_racik_3[$i];
					$_exp_racik_3        		= $exp_racik_3[$i];
					$_hargajual_racik_3     = preg_replace('/[,]/', '', $hargajual_racik_3[$i]);
					$_total_hrg_racik_3     = preg_replace('/[,]/', '', $total_hrg_racik_3[$i]);
					$hpp13 									= $this->db->get_where('tbl_barang', ['kodebarang' => $_koderacik_3])->row_array();
					$hpp3 									= $hpp13['hpp'];
					$barang3 								= $this->db->query("SELECT * FROM tbl_barang WHERE kodebarang = '$_koderacik_3'")->row();
					
					$datad3 = [
						'koders'      => $cabang,
						'resepid'     => 3,
						'hpp'      	  => $hpp3,
						'resepno'     => $noresepo,
						'kodebarang'  => $_koderacik_3,
						'namabarang'  => $barang3->namabarang,
						'satuan'      => $_satracik_3,
						'qtyr'        => $_qty_racik_racik_3,
						'qty'         => $_qty_jual_racik_3,
						'uangr'       => 0,
						'price'       => $_hargajual_racik_3,
						'totalrp'     => $_total_hrg_racik_3,
						'exp_date'    => date('Y-m-d H:i:s', strtotime($_exp_racik_3)),
						'jamdresep'   => date('H:i:s'),
					];

					if ($_koderacik_3 != "") {
						$this->db->insert('tbl_apodetresep', $datad3);
						$cekstok3 = $this->db->query("SELECT * FROM tbl_barangstock WHERE kodebarang = '$_koderacik_3' AND koders = '$cabang'  AND gudang = '$gudang'")->num_rows();
						$date_now = date('Y-m-d H:i:s');
						if($cekstok3 > 0){
							$this->db->query("UPDATE tbl_barangstock SET keluar = keluar + $_qty_jual_racik_3, saldoakhir = saldoakhir - $_qty_jual_racik_3, lasttr = '$date_now' WHERE kodebarang = '$_koderacik_3' AND koders = '$cabang'  AND gudang = '$gudang'");
						} else {
							$datastock3 = [
								'koders'       => $cabang,
								'kodebarang'   => $_koderacik_3,
								'gudang'       => $gudang,
								'saldoawal'    => 0,
								'terima'    	 => 0,
								'keluar'       => $_qty_jual_racik_3,
								'saldoakhir'   => 0 - $_qty_jual_racik_3,
								'lasttr'       => $date_now,
							];
							$this->db->insert('tbl_barangstock', $datastock3);
						}
					}
				}
			}

			// RACIKAN 4
			$namaracik_4 			= $this->input->post('namaracik_4');
			if($namaracik_4 != '' || $namaracik_4 != null) {
				$jenis_4     			= $this->input->post('jenis_4');
				$jumracik_4  			= $this->input->post('jumracik_4');
				$stajum_4    			= $this->input->post('stajum_4');
				$atpakai_4   			= $this->input->post('atpakai_4');
				$toto_4      			= $this->input->get('toto_racikan_4');
				$disknom_4   			= $this->input->post('disknom_racik_4');
				$disk_4      			= $this->input->get('disk_racik_4');
				$ongra_4     			= $this->input->post('ongra_racik_4');
				$carapakai_4     	= $this->input->post('carapakai_4');
				$totp_4      			= $this->input->get('totp_racik_4');
				$resep_manual_4   = $this->input->get('resman_racik_4');
				$cek_rm_4      		= $this->input->get('cek_rm4');
				$harga_manual_4   = $this->input->post('toto_racik_4');
				$cekppn_4 				= $ppn_pajak_ins;
				$ppn_4 						= (($totp_4) / (111 / 100)) * $ppn_pajak_ins;
				$cekid4						= $this->db->query("SELECT id from tbl_aporacik order by id desc limit 1")->result();
				foreach ($cekid4 as $row4) {
					$idd4    = $row4->id;
					if ($idd4 == null) {
						$idd4 	= 0;
					} else {
						$idd4 	= $idd4;
					}
					$id_ok4  = $idd4 + 1;
				}
				if($param > 1) {
					$this->db->query("DELETE FROM tbl_aporacik WHERE noracik = 4 AND resepno = '$noresepo'");
				}
				$data4 = [
					'id'		   			=> $id_ok4,
					'noracik'      	=> 4,
					'koders'       	=> $cabang,
					'resepno'      	=> $noresepo,
					'jenisracik'   	=> $jenis_4,
					'namaracikan'  	=> $namaracik_4,
					'aturanpakai'  	=> $atpakai_4,
					'jumlahracik'  	=> $jumracik_4,
					'kemasanracik' 	=> $stajum_4,
					'carapakai'  		=> $carapakai_4,
					'subtotal'     	=> $toto_4,
					'diskon'       	=> $disknom_4,
					'resep_manual' 	=> $resep_manual_4,
					'diskonrp'     	=> str_replace(',', '', $disk_4),
					'ppn'          	=> $cekppn_4,
					'ppnrp'        	=> str_replace(',', '', $ppn_4),
					'ongkosracik'  	=> str_replace(',', '', $ongra_4),
					'totalrp'      	=> str_replace(',', '', $totp_4),
					'cek_rm'      	=> $cek_rm_4,
					'harga_manual'  => str_replace(',', '', $harga_manual_4),
				];
				$this->db->insert('tbl_aporacik', $data4);

				$koderacik_4      	= $this->input->post('koderacik_4');
				$namaracik_4      	= $this->input->post('nama_racik_4');
				$satracik_4       	= $this->input->post('satracik_4');
				$qty_jual_racik_4   = $this->input->post('qty_jualracik_4');
				$qty_racik_racik_4  = $this->input->post('qty_racik_racik_4');
				$hargajual_racik_4  = $this->input->post('hargajualracik_4');
				$total_hrg_racik_4  = $this->input->post('total_hrg_racik_4');
				$exp_racik_4    		= $this->input->post('exp_racik_4');
	
				$jumdata4   = $this->input->get('jml4');
				$nourut4    = 1;
				$tot4       = 0;
				$tdisc4     = 0;
				$tothpp4    = 0;

				if($param > 1) {
					$cekr4 = $this->db->get_where("tbl_apodetresep", ["resepno" => $noresepo, "resepid" => 4])->result();
					foreach ($cekr4 as $cr4) {
						$this->db->query("UPDATE tbl_barangstock SET keluar = keluar - $cr4->qty, terima = terima + $cr4->qty, saldoakhir = saldoakhir + $cr4->qty");
					}
					$this->db->query("DELETE FROM tbl_apodetresep WHERE resepno = '$noresepo' AND resepid = 4");
				}
	
				for ($i = 0; $i <= ($jumdata4 - 1); $i++) {
					$_koderacik_4       		= $koderacik_4[$i];
					$_namaracik_4       		= $namaracik_4[$i];
					$_satracik_4        		= $satracik_4[$i];
					$_qty_racik_racik_4   	= $qty_racik_racik_4[$i];
					$_qty_jual_racik_4    	= $qty_jual_racik_4[$i];
					$_exp_racik_4        		= $exp_racik_4[$i];
					$_hargajual_racik_4     = preg_replace('/[,]/', '', $hargajual_racik_4[$i]);
					$_total_hrg_racik_4     = preg_replace('/[,]/', '', $total_hrg_racik_4[$i]);
					$hpp14 									= $this->db->get_where('tbl_barang', ['kodebarang' => $_koderacik_4])->row_array();
					$hpp4 									= $hpp14['hpp'];
					$barang4 								= $this->db->query("SELECT * FROM tbl_barang WHERE kodebarang = '$_koderacik_4'")->row();
					
					$datad4 = [
						'koders'      => $cabang,
						'resepid'     => 4,
						'hpp'      	  => $hpp4,
						'resepno'     => $noresepo,
						'kodebarang'  => $_koderacik_4,
						'namabarang'  => $barang4->namabarang,
						'satuan'      => $_satracik_4,
						'qtyr'        => $_qty_racik_racik_4,
						'qty'         => $_qty_jual_racik_4,
						'uangr'       => 0,
						'price'       => $_hargajual_racik_4,
						'totalrp'     => $_total_hrg_racik_4,
						'exp_date'    => date('Y-m-d H:i:s', strtotime($_exp_racik_4)),
						'jamdresep'   => date('H:i:s'),
					];

					if ($_koderacik_4 != "") {
						$this->db->insert('tbl_apodetresep', $datad4);
						$cekstok4 = $this->db->query("SELECT * FROM tbl_barangstock WHERE kodebarang = '$_koderacik_4' AND koders = '$cabang'  AND gudang = '$gudang'")->num_rows();
						$date_now = date('Y-m-d H:i:s');
						if($cekstok4 > 0){
							$this->db->query("UPDATE tbl_barangstock SET keluar = keluar + $_qty_jual_racik_4, saldoakhir = saldoakhir - $_qty_jual_racik_4, lasttr = '$date_now' WHERE kodebarang = '$_koderacik_4' AND koders = '$cabang'  AND gudang = '$gudang'");
						} else {
							$datastock4 = [
								'koders'       => $cabang,
								'kodebarang'   => $_koderacik_4,
								'gudang'       => $gudang,
								'saldoawal'    => 0,
								'terima'    	 => 0,
								'keluar'       => $_qty_jual_racik_4,
								'saldoakhir'   => 0 - $_qty_jual_racik_4,
								'lasttr'       => $date_now,
							];
							$this->db->insert('tbl_barangstock', $datastock4);
						}
					}
				}
			}

			// RACIKAN 5
			$namaracik_5 			= $this->input->post('namaracik_5');
			if($namaracik_5 != '' || $namaracik_5 != null) {
				$jenis_5     			= $this->input->post('jenis_5');
				$jumracik_5  			= $this->input->post('jumracik_5');
				$stajum_5    			= $this->input->post('stajum_5');
				$atpakai_5   			= $this->input->post('atpakai_5');
				$toto_5      			= $this->input->get('toto_racikan_5');
				$disknom_5   			= $this->input->post('disknom_racik_5');
				$disk_5      			= $this->input->get('disk_racik_5');
				$ongra_5     			= $this->input->post('ongra_racik_5');
				$carapakai_5     	= $this->input->post('carapakai_5');
				$totp_5      			= $this->input->get('totp_racik_5');
				$resep_manual_5   = $this->input->get('resman_racik_5');
				$cek_rm_5      		= $this->input->get('cek_rm5');
				$harga_manual_5   = $this->input->post('toto_racik_5');
				$cekppn_5 				= $ppn_pajak_ins;
				$ppn_5 						= (($totp_5) / (111 / 100)) * $ppn_pajak_ins;
				$cekid5						= $this->db->query("SELECT id from tbl_aporacik order by id desc limit 1")->result();
				foreach ($cekid5 as $row5) {
					$idd5    = $row5->id;
					if ($idd5 == null) {
						$idd5 	= 0;
					} else {
						$idd5 	= $idd5;
					}
					$id_ok5  = $idd5 + 1;
				}
				if($param > 1) {
					$this->db->query("DELETE FROM tbl_aporacik WHERE noracik = 5 AND resepno = '$noresepo'");
				}
				$data5 = [
					'id'		   			=> $id_ok5,
					'noracik'      	=> 5,
					'koders'       	=> $cabang,
					'resepno'      	=> $noresepo,
					'jenisracik'   	=> $jenis_5,
					'namaracikan'  	=> $namaracik_5,
					'aturanpakai'  	=> $atpakai_5,
					'jumlahracik'  	=> $jumracik_5,
					'kemasanracik' 	=> $stajum_5,
					'carapakai'  		=> $carapakai_5,
					'subtotal'     	=> $toto_5,
					'diskon'       	=> $disknom_5,
					'resep_manual' 	=> $resep_manual_5,
					'diskonrp'     	=> str_replace(',', '', $disk_5),
					'ppn'          	=> $cekppn_5,
					'ppnrp'        	=> str_replace(',', '', $ppn_5),
					'ongkosracik'  	=> str_replace(',', '', $ongra_5),
					'totalrp'      	=> str_replace(',', '', $totp_5),
					'cek_rm'      	=> $cek_rm_5,
					'harga_manual'  => str_replace(',', '', $harga_manual_5),
				];
				$this->db->insert('tbl_aporacik', $data5);

				$koderacik_5      	= $this->input->post('koderacik_5');
				$namaracik_5      	= $this->input->post('nama_racik_5');
				$satracik_5       	= $this->input->post('satracik_5');
				$qty_jual_racik_5   = $this->input->post('qty_jualracik_5');
				$qty_racik_racik_5  = $this->input->post('qty_racik_racik_5');
				$hargajual_racik_5  = $this->input->post('hargajualracik_5');
				$total_hrg_racik_5  = $this->input->post('total_hrg_racik_5');
				$exp_racik_5    		= $this->input->post('exp_racik_5');
	
				$jumdata5   = $this->input->get('jml5');
				$nourut5    = 1;
				$tot5       = 0;
				$tdisc5     = 0;
				$tothpp5    = 0;

				if($param > 1) {
					$cekr5 = $this->db->get_where("tbl_apodetresep", ["resepno" => $noresepo, "resepid" => 5])->result();
					foreach ($cekr5 as $cr5) {
						$this->db->query("UPDATE tbl_barangstock SET keluar = keluar - $cr5->qty, terima = terima + $cr5->qty, saldoakhir = saldoakhir + $cr5->qty");
					}
					$this->db->query("DELETE FROM tbl_apodetresep WHERE resepno = '$noresepo' AND resepid = 5");
				}
	
				for ($i = 0; $i <= ($jumdata5 - 1); $i++) {
					$_koderacik_5       		= $koderacik_5[$i];
					$_namaracik_5       		= $namaracik_5[$i];
					$_satracik_5        		= $satracik_5[$i];
					$_qty_racik_racik_5   	= $qty_racik_racik_5[$i];
					$_qty_jual_racik_5    	= $qty_jual_racik_5[$i];
					$_exp_racik_5        		= $exp_racik_5[$i];
					$_hargajual_racik_5     = preg_replace('/[,]/', '', $hargajual_racik_5[$i]);
					$_total_hrg_racik_5     = preg_replace('/[,]/', '', $total_hrg_racik_5[$i]);
					$hpp15 									= $this->db->get_where('tbl_barang', ['kodebarang' => $_koderacik_5])->row_array();
					$hpp5 									= $hpp15['hpp'];
					$barang5 								= $this->db->query("SELECT * FROM tbl_barang WHERE kodebarang = '$_koderacik_5'")->row();
					
					$datad5 = [
						'koders'      => $cabang,
						'resepid'     => 5,
						'hpp'      	  => $hpp5,
						'resepno'     => $noresepo,
						'kodebarang'  => $_koderacik_5,
						'namabarang'  => $barang5->namabarang,
						'satuan'      => $_satracik_5,
						'qtyr'        => $_qty_racik_racik_5,
						'qty'         => $_qty_jual_racik_5,
						'uangr'       => 0,
						'price'       => $_hargajual_racik_5,
						'totalrp'     => $_total_hrg_racik_5,
						'exp_date'    => date('Y-m-d H:i:s', strtotime($_exp_racik_5)),
						'jamdresep'   => date('H:i:s'),
					];
					
					if ($_koderacik_5 != "") {
						$this->db->insert('tbl_apodetresep', $datad5);

						$cekstok5 = $this->db->query("SELECT * FROM tbl_barangstock WHERE kodebarang = '$_koderacik_5' AND koders = '$cabang'  AND gudang = '$gudang'")->num_rows();
						$date_now = date('Y-m-d H:i:s');
						if($cekstok5 > 0){
							$this->db->query("UPDATE tbl_barangstock SET keluar = keluar + $_qty_jual_racik_5, saldoakhir = saldoakhir - $_qty_jual_racik_5, lasttr = '$date_now' WHERE kodebarang = '$_koderacik_5' AND koders = '$cabang'  AND gudang = '$gudang'");
						} else {
							$datastock5 = [
								'koders'       => $cabang,
								'kodebarang'   => $_koderacik_5,
								'gudang'       => $gudang,
								'saldoawal'    => 0,
								'terima'    	 => 0,
								'keluar'       => $_qty_jual_racik_5,
								'saldoakhir'   => 0 - $_qty_jual_racik_5,
								'lasttr'       => $date_now,
							];
							$this->db->insert('tbl_barangstock', $datastock5);
						}
					}
				}
			}
			echo json_encode(["status" => 1]);
		} else {
			header('location:' . base_url());
		}
	}

	public function save($param){
		$hasil        = 0;
		$vnoreg       = '';
		$rekmed       = '';
		$unit         = $this->session->userdata('unit');
		$shift        = $this->session->userdata('shift');
		$cabang       = $this->session->userdata('unit');
		$cek          = $this->session->userdata('level');
		$userid       = $this->session->userdata('username');
		$racikanxx    = $this->input->get('racikan');
		$noreg        = $this->input->post('noreg');
		$rekmed       = $this->input->post('pasien');
		$nama_pas     = $this->input->post('nama_pas');
		if($rekmed){
			$rekmed = $rekmed;
		}else{
			$rekmed = 'Non Member';
		}
		$pembeli        = $this->input->post('pembeli');
		$dokter         = $this->input->post('dokter');
		$eresepstatus   = $this->input->post("eresepstatus");
		$gudang         = $this->input->post("gudang");
		$ketpakai       = $this->input->post("keterangan");
		$aturpakai      = $this->input->post("aturan_pakai");
		$expire         = $this->input->post("expire");

		// if($eresepstatus == 1){
		// 	$noeresep	= $this->input->post("noeresep");
		// } else {
			$noeresep	= "";
		// }

		$ppn_pajak_insx = $this->db->get_where('tbl_pajak', ['kodetax' => 'PPN'])->row_array();
		$ppn_pajak_ins = $ppn_pajak_insx['prosentase'] / 100;
		if (!empty($cek)) {
			if($dokter==''){
				$dokter='';
			}

			switch ($pembeli) {
				case "KULIT":
					$gudang = "FARMASI";
					break;
				case "LOKAL":
					$gudang = "FARMASI";
					$dokter = "";
					break;
				case "KIRIM":
					$gudang = "FARMASI";
					break;
				case "SPA":
					$gudang = "FARMASISPA";
					break;
				case "GIGI":
					$gudang = "FARMASIGIG";
					break;
				case "ONLINE":
					$gudang = "ONLINE";
					break;
				case "RAJAL":
					$gudang = "FARMASI";
					break;
				case "RANAP":
					$gudang = "FARMASI";
					break;
				case "APOTIK":
					$gudang = "FARMASI";
					break;
				case "atr":
					$gudang = $this->input->post("gudang");
					break;
				case "adr":
					$gudang = $this->input->post("gudang");
					break;
				default:
					$gudang = "KOSONG";
					break;
			}

			if ($pembeli == 'KULIT') {
				$jenispas = 1;
			} else if ($pembeli == 'LOKAL') {
				$jenispas = 2;
			} else if ($pembeli == 'KIRIM') {
				$jenispas = 4;
			} else if ($pembeli == 'SPA') {
				$jenispas = 4;
			} else if ($pembeli == 'GIGI') {
				$jenispas = 5;
			} else if ($pembeli == 'ONLINE') {
				$jenispas = 6;
			} else if ($pembeli == 'APOTIK') {
				$jenispas = 7;
			} else if($pembeli == 'RAJAL'){
				$jenispas = 8;
			} else if($pembeli == 'RANAP'){
				$jenispas = 9;
			} else if($pembeli == 'adr'){
				$jenispas = 10;
			} else if($pembeli == 'atr'){
				$jenispas = 11;
			}

			if ($param == 1) {
				$nobukti  = urut_transaksi('RESEP', 19);
			} else {
				$nobukti  = $this->input->post('noresep');

				$dresep = $this->db->get_where('tbl_apodresep', ['resepno' => $nobukti])->result();

				foreach ($dresep as $row) {
					$_qty        = $row->qty;
					$_kode       = $row->kodebarang;
					$stok        = $this->db->query("SELECT * FROM tbl_barangstock WHERE kodebarang = '$_kode' AND gudang = '$gudang' AND koders = '$cabang'")->num_rows();
					$date_now    = date('Y-m-d H:i:s');
					if($stok > 0){
						$this->db->query("UPDATE tbl_barangstock SET keluar = keluar + $_qty, saldoakhir = saldoakhir - $_qty, lasttr = '$date_now' WHERE kodebarang = '$_kode' AND koders = '$cabang' AND gudang = '$gudang'");
					} else {
						$datastock = [
							'koders'       => $cabang,
							'kodebarang'   => $_kode,
							'gudang'       => $gudang,
							'saldoawal'    => 0,
							'terima'       => 0,
							'keluar'       => $_qty,
							'saldoakhir'   => 0 - $_qty,
							'lasttr'       => $date_now,
						];
						$this->db->insert('tbl_barangstock', $datastock);
					}
				}
				$this->db->delete('tbl_apodresep', ['resepno' => $nobukti]);
			}

			$kode    = $this->input->post('kode');
			$nama    = $this->input->post('nama');
			$qty     = $this->input->post('qty');
			$sat     = $this->input->post('sat');
			$harga   = $this->input->post('harga');
			$ppn     = $this->input->post('ppn');
			$disc    = $this->input->post('disc');
			$disc2   = $this->input->post('disc2');
			$jumlah  = $this->input->post('jumlah');


			if($kode == '' | $kode == null) {
				$jumdata = 0;
			} else {
				$jumdata = count($kode);
			}

			$nourut            = 1;
			$tot               = 0;
			$tdisc             = 0;
			$tothpp            = 0;
			$disc_resep_ins    = 0;

			for ($i = 0; $i <= ($jumdata - 1); $i++) {
				$_kode            = $kode[$i];
				$_qty             = $qty[$i];
				$_jumlah          = preg_replace('/[,]/', '', $jumlah[$i]);
				$_harga           = preg_replace('/[,]/', '', $harga[$i]);
				$_discrp          = preg_replace('/[,]/', '', $disc2[$i]);
				$vjum             = (preg_replace('/[,]/', '', $qty[$i]) * $_harga) - preg_replace('/[,]/', '', $disc2[$i]);
				$tot              += $vjum;
				$disc_resep_ins   += $_discrp;
				$dpp_resep_ins    = $tot - $disc_resep_ins;
				$hpp1             = $this->db->get_where('tbl_barang', ['kodebarang' => $_kode])->row();
				$hpp              = $hpp1->hpp;
				$hna1             = $this->db->get_where('tbl_barang', ['kodebarang' => $_kode])->row();
				$hna              = $hna1->hargabeli;
				$tothpp           = $tothpp + ($hpp * $qty[$i]);
				$_ppn             = 1;
				$_ppnrp           = (($vjum) / (111 / 100)) * $ppn_pajak_ins;

				$datad = [
					'koders'        => $unit,
					'resepno'       => $nobukti,
					'eresepno'      => $noeresep,
					'kodebarang'    => $_kode,
					'namabarang'    => $nama[$i],
					'qty'           => $qty[$i],
					'satuan'        => $sat[$i],
					'ppn'           => $_ppn,
					'ppnrp'         => $_ppnrp,
					'hpp'           => $hpp,
					'hna'           => $hna,
					'price'         => $_harga,
					'discount'      => $disc[$i],
					'discrp'        => $_discrp,
					'totalrp'       => $vjum,
					'atpakai'				=> $aturpakai[$i],
					'ket'						=> $ketpakai[$i],
					'exp_date'			=> $expire[$i],
				];

				if ($_kode != "") {
					$this->db->insert('tbl_apodresep', $datad);
					if($eresepstatus == 1){
						$this->db->query("UPDATE tbl_eresep SET qtyproses = $qty[$i] WHERE kodeobat = '$_kode' AND orderno = '$noeresep'");
					}
					$stokcek   = $this->db->query("SELECT * from tbl_barangstock where kodebarang = '$_kode' and gudang = '$gudang' and koders = '$unit'")->num_rows();
					$date_now  = date('Y-m-d H:i:s');
					if($stokcek > 0){
						$this->db->query("UPDATE tbl_barangstock set keluar = keluar+ $_qty, saldoakhir = saldoakhir - $_qty, lasttr = '$date_now' where kodebarang = '$_kode' and koders = '$unit' and gudang = '$gudang'");
					} else {
						$datastock = [
							'koders'       => $unit,
							'kodebarang'   => $_kode,
							'gudang'       => $gudang,
							'saldoawal'    => 0,
							'terima'       => 0,
							'keluar'			 => $_qty,
							'saldoakhir'   => 0 - $_qty,
							'lasttr'       => $date_now,
						];
						$this->db->insert('tbl_barangstock', $datastock);
					}
				}
			}

			if ($noreg) {
				$vnoreg = $noreg;
			}

			$noreg = $vnoreg;

			$data = [
				'koders'    => $this->session->userdata('unit'),
				'resepno'   => $nobukti,
				'eresepno'	=> $noeresep,
				'antrino'   => 1,
				'noreg'     => $noreg,
				'rekmed'    => $rekmed,
				'pro'    		=> $nama_pas,
				'kodokter'  => $dokter,
				'jenisjual' => 1,
				'jenispas'  => $jenispas,
				'kodepel'   => $this->input->post('pembeli'),
				'alamat'    => $this->input->post('alamat'),
				'nohp'      => $this->input->post('phone'),
				'tgllahir'  => $this->input->post('tgllahir'),
				'bb'        => $this->input->post('bb'),
				'jkel'      => $this->input->post('jkel'),
				'tglresep'  => date('Y-m-d', strtotime($this->input->post('tanggal'))),
				'jam'       => date('H:i:s', strtotime($this->input->post('jam'))),
				'gudang'    => $gudang,
				'username'  => $userid,
				'shift'  		=> $shift,
			];

			if ($param == 1) {
				$this->db->insert('tbl_apohresep', $data);
			} else {
				$data['tgledit']   = date('Y-m-d');
				$data['jamedit']   = date('H:i:s');
				$data['useredit']  = $userid;
				$this->db->update('tbl_apohresep', $data, ['resepno' => $nobukti]);
			}

			//posting
			$namapasx = $this->input->post('nama_pas');
			if($namapasx == ''){
				$namapas = "UMUM";
			}else{
				$namapas = $this->input->post('nama_pas');
			}

			$data2 = [
				'koders'    => $this->session->userdata('unit'),
				'resepno'   => $nobukti,
				'eresepno'	=> $noeresep,
				'tglresep'  => date('Y-m-d', strtotime($this->input->post('tanggal'))),
				'noreg'     => $noreg,
				'rekmed'    => $rekmed,
				'namapas'   => $namapas,
				'umurpas'   => '-',
				'gudang'    => $gudang,
				'posting'   => 1,
				'poscredit' => str_replace(",","", $this->input->get('vtotal')),
				'username'  => $userid,
			];

			if($eresepstatus == 1){
				$this->db->query("UPDATE tbl_orderperiksa SET resep = 0, resepok = 1, proses = 'terproses' WHERE orderno = '$noeresep'");
			}

			if ($param == 1) {
				$this->db->insert('tbl_apoposting', $data2);
			} else {
				$this->db->update('tbl_apoposting', $data2, ['resepno' => $nobukti]);
			}
			$sql = $this->db->query("SELECT * FROM tbl_apohresep WHERE koders = '$cabang' order by id desc limit 1")->result();
			foreach ($sql as $s) {
				$checkiing = $this->db->get_where('tbl_aporacik', ['resepno' => $s->resepno])->num_rows();
				if ($checkiing == 0) {
					echo json_encode(['status' => 2, 'nobukti' => $nobukti]);
				} else {
					echo json_encode(['status' => 1, 'nobukti' => $nobukti]);
				}
			}
		} else {
			header('location:' . base_url());
		}
	}

	public function delete($nomor, $noedit = null){
		$cek        = $this->session->userdata('level');
		$unit       = $this->session->userdata('unit');
		$hmutasi    = $this->db->query("SELECT * from tbl_apohresep where resepno = '$nomor'")->row();
		$cabang     = $hmutasi->koders;
		$gudang     = $hmutasi->gudang;
		if (!empty($cek)) {
			$datamutasi = $this->db->get_where('tbl_apodresep', array('resepno' => $nomor))->result();
			foreach ($datamutasi as $row) {
				$_qty 	= $row->qty;
				$_kode 	= $row->kodebarang;
				$this->db->query("UPDATE tbl_barangstock set keluar=keluar- $_qty, saldoakhir= saldoakhir+ $_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang'");
			}
			
			$dataracik = $this->db->get_where('tbl_apodetresep', array('resepno' => $nomor))->result();
			foreach($dataracik as $dr) {
				$_qty 	= $dr->qty;
				$_kode 	= $dr->kodebarang;
				$this->db->query("UPDATE tbl_barangstock set keluar=keluar- $_qty, saldoakhir= saldoakhir+ $_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang'");
			}

			$this->db->query("UPDATE tbl_orderperiksa SET resep = 1, resepok = 0, proses = 'proses' WHERE orderno = '$hmutasi->eresepno'");
			$this->db->query("DELETE from tbl_apohresep where resepno = '$nomor' and koders='$unit'");
			$this->db->query("DELETE from tbl_apodresep where resepno = '$nomor' and koders='$unit'");
			$this->db->query("DELETE from tbl_apoposting where resepno = '$nomor' and koders='$unit'");
			$this->db->query("DELETE from tbl_aporacik where resepno = '$nomor' and koders='$unit'");
			$this->db->query("DELETE from tbl_apodetresep where resepno = '$nomor' and koders='$unit'");
			echo json_encode(array("status" => 1));
		} else {
			header('location:' . base_url());
		}
	}

	public function hapus($nomor){
		$cek = $this->session->userdata('level');
		$hmutasi = $this->db->query("select * from tbl_apohresep where resepno = '$nomor'")->row();
		$cabang  = $hmutasi->koders;
		$gudang  = $hmutasi->gudang;
		if (!empty($cek)) {

			//$this->M_global->_hapusjurnal($nomor, 'JJ');

			$datamutasi = $this->db->get_where('tbl_apodresep', array('resepno' => $nomor))->result();
			foreach ($datamutasi as $row) {
				$_qty = $row->qty;
				$_kode = $row->kodebarang;

				$this->db->query("update tbl_barangstock set keluar=keluar- $_qty, saldoakhir= saldoakhir+ $_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang'");
			}

			$this->db->delete('tbl_apohresep', array('resepno' => $nomor));
			$this->db->delete('tbl_apodresep', array('resepno' => $nomor));
			$this->db->delete('tbl_apoposting', array('resepno' => $nomor));
		} else {

			header('location:' . base_url());
		}
	}

	public function edit($nomor, $noedit = null)
	{
		$cek    = $this->session->userdata('level');
		$unit   = $this->session->userdata('unit');
		if (!empty($cek)) {
			$posting         = $this->db->get_where('tbl_apoposting', array('resepno' => $nomor));
			$header          = $this->db->get_where('tbl_apohresep', array('resepno' => $nomor));
			$detil           = $this->db->select('tbl_apodresep.*, (SELECT aponame from tbl_barangsetup where  apocode=tbl_apodresep.atpakai LIMIT 1)nm_atpakai, tbl_barang.namabarang as namabarang1')->join('tbl_barang', 'tbl_barang.kodebarang=tbl_apodresep.kodebarang')->get_where('tbl_apodresep', array('resepno' => $nomor));
			$header_r        = $this->db->get_where('tbl_aporacik', array('resepno' => $nomor));
			$detil_r         = $this->db->get_where('tbl_apodetresep', array('resepno' => $nomor));
			$dresep          = $this->db->get_where('tbl_apodresep', array('resepno' => $nomor));
			$dataRacik       = $this->db->query("SELECT * FROM tbl_aporacik where resepno ='$nomor'");
			$total           = $this->db->query("SELECT totalrp FROM tbl_apodetresep WHERE resepno = '$nomor'");
			$d['header']     = $header->row();
			$d['detil']      = $detil->result();
			$d['posting']    = $posting->row();
			$d['jumdata']    = $detil->num_rows();
			$d['dracikan']   = $detil_r->num_rows();
			$d['noedit']     = $noedit;
			$d['racik']      = $dataRacik->row();
			$d['header_r']   = $header_r->row();
			$d['detil_r']    = $detil_r->result();
			$d['ttl']        = $total->result();
			$d['ppn']        = $this->db->get_where('tbl_pajak', ['kodetax' => 'PPN'])->row_array();
			$this->load->view('penjualan/v_penjualan_faktur_edit2', $d);
		} else {
			header('location:' . base_url());
		}
	}

	public function edit2($nomor, $noedit = null)
	{
		$unit   = $this->session->userdata('unit');
		$cek    = $this->session->userdata('level');
		if (!empty($cek)) {
			$posting           = $this->db->get_where('tbl_apoposting', ['resepno' => $nomor]);
			$header            = $this->db->get_where('tbl_apohresep', ['resepno' => $nomor]);
			$detil             = $this->db->select('tbl_apodresep.*, (SELECT aponame from tbl_barangsetup where  apocode=tbl_apodresep.atpakai LIMIT 1)nm_atpakai, tbl_barang.namabarang as namabarang1')->join('tbl_barang', 'tbl_barang.kodebarang=tbl_apodresep.kodebarang')->get_where('tbl_apodresep', ['resepno' => $nomor]);
			$header_r          = $this->db->get_where('tbl_aporacik', ['resepno' => $nomor]);
			$detil_r           = $this->db->get_where('tbl_apodetresep', ['resepno' => $nomor]);
			$detil_r1          = $this->db->get_where('tbl_apodetresep', ['resepno' => $nomor, "resepid" => 1]);
			$detil_r2          = $this->db->get_where('tbl_apodetresep', ['resepno' => $nomor, "resepid" => 2]);
			$detil_r3          = $this->db->get_where('tbl_apodetresep', ['resepno' => $nomor, "resepid" => 3]);
			$detil_r4          = $this->db->get_where('tbl_apodetresep', ['resepno' => $nomor, "resepid" => 4]);
			$detil_r5          = $this->db->get_where('tbl_apodetresep', ['resepno' => $nomor, "resepid" => 5]);
			$dresep            = $this->db->get_where('tbl_apodresep', ['resepno' => $nomor]);
			$dataRacik1        = $this->db->query("SELECT * FROM tbl_aporacik where resepno ='$nomor' AND noracik = 1");
			$dataRacik2        = $this->db->query("SELECT * FROM tbl_aporacik where resepno ='$nomor' AND noracik = 2");
			$dataRacik3        = $this->db->query("SELECT * FROM tbl_aporacik where resepno ='$nomor' AND noracik = 3");
			$dataRacik4        = $this->db->query("SELECT * FROM tbl_aporacik where resepno ='$nomor' AND noracik = 4");
			$dataRacik5        = $this->db->query("SELECT * FROM tbl_aporacik where resepno ='$nomor' AND noracik = 5");
			$racikansem        = $this->db->query("SELECT * FROM tbl_aporacik where resepno ='$nomor'")->num_rows();
			$total             = $this->db->query("SELECT totalrp FROM tbl_apodetresep WHERE resepno = '$nomor'");
			$d['header']       = $header->row();
			$d['detil']        = $detil->result();
			$d['posting']      = $posting->row();
			$d['jumdata']      = $detil->num_rows();
			$d['dracikan']     = $detil_r->num_rows();
			$d['noedit']       = $noedit;
			$d['racikansem']   = $racikansem;
			$d['racik1']       = $dataRacik1->row();
			$d['racik2']       = $dataRacik2->row();
			$d['racik3']       = $dataRacik3->row();
			$d['racik4']       = $dataRacik4->row();
			$d['racik5']       = $dataRacik5->row();
			$d['header_r']     = $header_r->row();
			$d['detil_r']      = $detil_r->result();
			$d['detil_r1']     = $detil_r1->num_rows();
			$d['detil_r2']     = $detil_r2->num_rows();
			$d['detil_r3']     = $detil_r3->num_rows();
			$d['detil_r4']     = $detil_r4->num_rows();
			$d['detil_r5']     = $detil_r5->num_rows();
			$d['detil_r1x']    = $detil_r1->result();
			$d['detil_r2x']    = $detil_r2->result();
			$d['detil_r3x']    = $detil_r3->result();
			$d['detil_r4x']    = $detil_r4->result();
			$d['detil_r5x']    = $detil_r5->result();
			$d['ttl']          = $total->result();
			$d['ppn']          = $this->db->get_where('tbl_pajak', ['kodetax' => 'PPN'])->row();
			$d['atpakaix']     = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='ATURANPAKAI' ")->result();
			$this->load->view('penjualan/v_penjualan_faktur_edit_', $d);
		} else {
			header('location:' . base_url());
		}
	}

	public function bayar($resepno){
			$unit = $this->session->userdata('unit');
			$data         = $this->db->query("SELECT date(c.tgllahir) as tanggallahir,date(a.tglresep) as tglresep1,(select cust_nama from tbl_penjamin z where z.cust_id=c.penjamin)nm_penjamin,a.resepno as resepno2,a.nokwitansi as nokwitansi2,c.namapas as namapas2,a.rekmed as rekmed2, a.* ,b.*,c.*,d.*,e.*
			from tbl_apoposting a 
			join tbl_apohresep b ON a.resepno=b.resepno
			join tbl_pasien c ON a.rekmed=c.rekmed
			left join tbl_pap d ON d.nokwitansi=a.nokwitansi
			left join tbl_kasir e ON e.nokwitansi=a.nokwitansi
			where a.koders='$unit' and a.resepno = '$resepno' ")->row();


			echo json_encode($data);
	}

	public function ajax_add_bayar()
	{
		$cabang       = $this->session->userdata('unit');
		$userid       = $this->session->userdata('username');
		$shift        = $this->session->userdata('shift');
		$noresep      = $this->input->post('resepno');
		$rekmed       = $this->input->post('rekmed');
		$totalresep   = str_replace(',', '', $this->input->post('total_resep'));
		$total_semua  = str_replace(',', '', $this->input->post('total_semua'));
		$nil_aptk     = str_replace(',', '', $this->input->post('nil_aptk'));
		$jumhutang    = str_replace(',', '', $this->input->post('juklaim'));
		// $totalnet     = str_replace(',', '', $this->input->post('totalnet'));

		$tanggal      = date('Y-m-d');
		$jam          = date('H:i:s');

		$kwitansi     = urut_transaksi('URUTKWT', 19);

		$cekhresep    = $this->db->get_where("tbl_apohresep", ['resepno' => $noresep])->num_rows();
		if($cekhresep > 0){
			$this->db->set("nokwitansi", $kwitansi);
			$this->db->where("resepno", $noresep);
			$this->db->update("tbl_apohresep");
		}


		//$this->_validate();
		if ($totalresep > 0) {
			$ttlresep = (int)$totalresep;
		} else {
			$ttlresep = 0 - (int)$totalresep;
		}
		$data = array(
			'koders'               => $cabang,
			'nokwitansi'           => $kwitansi,
			'fakturpajak'          => '',
			'rekmed'               => $rekmed,
			'noreg'                => '',
			'tglbayar'             => $tanggal,
			'jambayar'             => $jam,
			'totalpoli'            => 0,
			'totalresep'           => $total_semua,
			'uangmuka'             => 0,
			'adm'                  => 0,
			'diskon'               => 0,
			'diskonrp'             => 0,
			'admcredit'            => 0,
			'totalsemua'           => $total_semua,
			'bayarcash'            => $nil_aptk,
			'bayarcard'            => 0,
			'refund'               => 0,
			// 'voucherrp' => $voucherrp,
			'voucherrp1'           => 0,
			'voucherrp2'           => 0,
			'voucherrp3'           => 0,
			// 'novoucher' => $this->input->post('vouchercode'), saya ganti
			'novoucher1'           => '',
			'novoucher2'           => '',
			'novoucher3'           => '',
			'cust_id'              => '',
			'totalbayar'           => $total_semua,
			'kembali'              => '',
			'posbayar'             => 'APTK',
			'dibayaroleh'          => $this->input->post('lupnamapasien'),
			'jenisbayar'           => 1,
			'username'             => $userid,
			'kembalikeuangmuka'    => 0,
			'namapasien'           => $this->input->post('lupnamapasien'),
			'shift'                => $shift,
		);

		$insert = $this->db->insert('tbl_kasir', $data);


		$this->db->query("UPDATE tbl_apohresep set nokwitansi='$kwitansi', keluar=1 where koders = '$cabang' and resepno = '$noresep'");
		$this->db->query("UPDATE tbl_apoposting set nokwitansi='$kwitansi', keluar=1 where koders = '$cabang' and resepno = '$noresep'");

		$datapap = [
			'koders' => $cabang,
			'noreg' => "",
			'rekmed' => $rekmed,
			'tglposting' => $tanggal,
			'tgljatuhtempo' => $tanggal,
			'cust_id' => $this->input->post('vpenjamin'),
			'nokwitansi' => $kwitansi,
			'bayarcash' => $nil_aptk,
			// 'bayarcash' => $bayarcash,
			'adm' => 0,
			'totalpoli' => 0,
			'totalradio' => 0,
			'totallab' => 0,
			'totalbedah' => 0,
			'totalresep' => $total_semua,
			// 'jumlahhutang' => str_replace(',', '', $this->input->post('tercover_rp')),
			'jumlahhutang' => $jumhutang,
			'username' => $userid,
			'namapas' => $this->input->post('lupnamapasien'),
			'nik' => $this->input->post('lupidentitas'),
			'cust_id2' => $this->input->post('vpenjamin'),
			// 'nilaiklaim2' => str_replace(',', '', $this->input->post('tercover_rp2'))
			'nilaiklaim2' => 0
		];

		$insert = $this->db->insert('tbl_pap', $datapap);

		
		$this->db->set("ada", 0);
		$this->db->where('rekmed', $rekmed);
		$this->db->update('tbl_pasien');

		echo json_encode(array("status" => TRUE, "nomor" => $kwitansi, "total" => $total_semua));
	}

	public function update_aporacik(){
		$cek    = $this->session->userdata('level');
		$cabang = $this->session->userdata('unit');
		$gudang = $this->input->get('gudang');
		if (!empty($cek)) {
			$resepno = $this->input->get('resepno');
			$atpakaix = $this->input->get('atpakai');
			if ($atpakaix == 'null' || $atpakaix == '') {
				$atpakai = null;
			} else {
				$atpakai = $atpakaix;
			}
			$subtotal        = $this->input->get('subtotal');
			$diskon          = $this->input->get('diskon');
			$diskonrp        = $this->input->get('diskonrp');
			$ppn             = $this->input->get('ppn');
			$ppnrp           = $this->input->get('ppnrp');
			$ongkosracik     = $this->input->get('ongkosracik');
			$totalrp         = $this->input->get('totalrp');
			$carapakai       = $this->input->get('carapakai');
			$jenisracik      = $this->input->get('jenisracik');
			$jumlahracik     = $this->input->get('jumlahracik');
			$namaracik       = $this->input->get('namaracik');
			$kemasanracik    = $this->input->get('stajum');
			$resepmanual     = $this->input->get('resepmanual');
			$harga_manual    = $this->input->get('harga_manual');
			$cek_rm          = $this->input->get('cek_rm');
			$where = [
				'koders' => $cabang,
				'resepno' => $resepno,
			];
			$data = [
				'carapakai' => $carapakai,
				'jenisracik' => $jenisracik,
				'jumlahracik' => $jumlahracik,
				'namaracikan' => $namaracik,
				'kemasanracik' => $kemasanracik,
				'subtotal' => $subtotal,
				'diskon' => $diskon,
				'diskonrp' => $diskonrp,
				'aturanpakai' => $atpakai,
				'ppn' => $ppn,
				'ppnrp' => $ppnrp,
				'ongkosracik' => $ongkosracik,
				'totalrp' => $totalrp,
				'resep_manual' => $resepmanual,
				'cek_rm' => $cek_rm,
				'harga_manual' => $harga_manual,
			];
			$datazz = [
				'koders' => $cabang,
				'resepno' => $resepno,
				'carapakai' => $carapakai,
				'jenisracik' => $jenisracik,
				'jumlahracik' => $jumlahracik,
				'namaracikan' => $namaracik,
				'kemasanracik' => $kemasanracik,
				'subtotal' => $subtotal,
				'diskon' => $diskon,
				'diskonrp' => $diskonrp,
				'aturanpakai' => $atpakai,
				'ppn' => $ppn,
				'ppnrp' => $ppnrp,
				'ongkosracik' => $ongkosracik,
				'totalrp' => $totalrp,
				'resep_manual' => $resepmanual,
			];
			
			$check_racik = $this->db->get_where('tbl_aporacik', $where)->num_rows();
			if ($check_racik != 0) {
				$this->db->update('tbl_aporacik', $data, $where);
				$cek = $this->db->get_where('tbl_aporacik', $where)->num_rows();
			} else {
				$this->db->insert('tbl_aporacik', $datazz);
				$cek = $this->db->get_where('tbl_aporacik', $where)->num_rows();
			}
			if ($cek != 0) {
				$check_dresep = $this->db->get_where('tbl_apodetresep', $where)->num_rows();
				if ($check_dresep != 0) {
					$this->db->where($where);
					$this->db->delete('tbl_apodetresep');
				}
				echo json_encode(['status' => 1]);
			} else {
				echo json_encode(['status' => 2]);
			}
		} else {
			header('location:' . base_url());
		}
	}

	public function update_apodetresep(){
		$cek = $this->session->userdata('level');
		$cabang  = $this->session->userdata('unit');
		if (!empty($cek)) {
			$resepno = $this->input->get('resepno');
			$kodebarang = $this->input->get('kodebarang');
			$namabarang = $this->input->get('namabarang');
			$qty = $this->input->get('qty');
			$qtyr = $this->input->get('qtyr');
			$satuan = $this->input->get('satuan');
			$hpp1 = $this->db->get_where('tbl_barang', ['kodebarang' => $kodebarang])->row_array();
			$hpp = $hpp1['hpp'];
			$price = $this->input->get('price');
			$uangr = $this->input->get('uangr');
			$totalrp = $this->input->get('totalrp');
			$exp_date = date('Y-m-d', strtotime($this->input->get("exp1")));
			$where = [
				'resepno' => $resepno,
				'koders' => $cabang,
			];
			$datazz = [
				'resepno' => $resepno,
				'koders' => $cabang,
				'kodebarang' => $kodebarang,
				'namabarang' => $namabarang,
				'qty' => $qtyr,
				'qtyr' => $qty,
				'satuan' => $satuan,
				'hpp' => $hpp,
				'price' => $price,
				'uangr' => $uangr,
				'totalrp' => $totalrp,
				'exp_date' => $exp_date,
			];
			$data = [
				'resepno' => $resepno,
				'koders' => $cabang,
				'kodebarang' => $kodebarang,
				'namabarang' => $namabarang,
				'qty' => $qtyr,
				'qtyr' => $qty,
				'satuan' => $satuan,
				'hpp' => $hpp,
				'price' => $price,
				'uangr' => $uangr,
				'totalrp' => $totalrp,
				'exp_date' => $exp_date,
			];
			$this->db->insert('tbl_apodetresep', $datazz);
			// $check = $this->db->get_where('tbl_apodetresep', $where)->num_rows();
			// if ($check == 0) {
			// } else {
			// 	$this->db->update('tbl_apodetresep', $data, $where);
			// }
			echo json_encode($datazz);
		} else {
			header('location:' . base_url());
		}
	}

	public function update_apoposting(){
		$cek        = $this->session->userdata('level');
		$cabang     = $this->session->userdata('unit');
		$gudang     = $this->input->get('gudang');
		$hasil      = 0;
		$vnoreg     = '';
		$rekmed     = '';
		$userid     = $this->session->userdata('username');
		$racikan    = $this->input->get('racikan');
		$total      = $this->input->get('total');
		$noregx     = $this->input->get('noreg');
		if ($noregx == null || $noregx == 'null' || $noregx == '') {
			$noreg = '';
		} else {
			$noreg = '';
		}
		$rekmed   = $this->input->get('rekmed');
		$pembeli  = $this->input->post('pembeli');
		$dokter   = $this->input->post('dokter');
		$resepno  = $this->input->get('resepno');
		$tanggal  = $this->input->post('tanggal');
		$namapas  = $this->input->post('namapasien');
		$poscredit = (int)$total;
		if (!empty($cek)) {
			$where = [
				'koders'    => $cabang,
				'gudang'    => $gudang,
				'resepno'   => $resepno,
			];
			$data = [
				'koders'    => $cabang,
				'resepno'   => $resepno,
				'tglresep'  => date('Y-m-d', strtotime($tanggal)),
				'noreg'     => $noreg,
				'rekmed'    => $rekmed,
				'namapas'   => $namapas,
				'gudang'    => $gudang,
				'posting'   => 1,
				'poscredit' => $poscredit,
				'username'  => $userid,
			];
			$this->db->where($where);
			$this->db->delete('tbl_apoposting');
			$this->db->insert('tbl_apoposting', $data);

			$datamutasi = $this->db->get_where('tbl_apodresep', array('resepno' => $resepno))->result();
			foreach ($datamutasi as $row) {
				$_qty = $row->qty;
				$_kode = $row->kodebarang;
				$this->db->query("UPDATE tbl_barangstock SET keluar = keluar-$_qty, saldoakhir = saldoakhir-$_qty WHERE kodebarang = '$_kode' AND koders = '$cabang' AND gudang = '$gudang'");
			}
			$this->db->delete('tbl_apodresep', array('resepno' => $resepno));
			echo json_encode(['status' => 1]);
		} else {
			header('location:' . base_url());
		}
	}

	public function update_apohresep(){
		$cek = $this->session->userdata('level');
		$cabang  = $this->session->userdata('unit');
		if (!empty($cek)) {
			$gudang    = $this->input->get('gudang');
			$resepno   = $this->input->get('resepno');
			$eresepno  = $this->input->get('eresepno');
			$jenispas  = $this->input->get('jenispas');
			$userid    = $this->session->userdata('username');
			$noregx    = $this->input->get('noreg');
			if ($noregx == null || $noregx == 'null' || $noregx == '') {
				$noreg = '';
			} else {
				$noreg = '';
			}
			$rekmed    = $this->input->get('rekmed');
			$pembeli   = $this->input->post('pembeli');
			$dokter    = $this->input->get('dokter');
			$resepno   = $this->input->get('resepno');
			$tanggal   = $this->input->post('tanggal');
			$pembeli   = $this->input->get('pembeli');
			$jam       = $this->input->post('jam');

			$this->db->query("DELETE FROM tbl_apohresep WHERE koders = '$cabang' AND resepno = '$resepno' AND gudang = '$gudang'");

			// $where = [
			// 	'koders'    => $cabang,
			// 	'resepno'   => $resepno,
			// 	'gudang'    => $gudang,
			// ];
			$data = [
				'koders'    => $cabang,
				'resepno'   => $resepno,
				'eresepno'	=> $eresepno,
				'antrino'   => 1,
				'noreg'     => $noreg,
				'rekmed'    => $rekmed,
				'kodokter'  => $dokter,
				'jenisjual' => 1,
				'jenispas'  => $jenispas,
				'kodepel'   => $pembeli,
				'tgllahir'  => $this->input->post('tgllahir'),
				'bb'        => $this->input->post('bb'),
				'jkel'      => $this->input->post('jkel'),
				'tglresep'  => date('Y-m-d', strtotime($tanggal)),
				'jam'       => date('H:i:s', strtotime($jam)),
				'gudang'    => $gudang,
				'username'  => $userid,
				'alamat'    => $this->input->post('alamat'),
				'nohp'      => $this->input->post('phone'),

			];
			// $this->db->where($where);
			// $this->db->delete('tbl_apohresep');

			$this->db->insert('tbl_apohresep', $data);
			echo json_encode(['status' => 1, 'resepno' => $resepno]);
		} else {
			header('location:' . base_url());
		}
	}

	public function update_apodresep(){
		$cek = $this->session->userdata('level');
		$cabang  = $this->session->userdata('unit');
		if (!empty($cek)) {
			$gudang        = $this->input->get('gudang');
			$tanggal       = $this->input->post('tanggal');
			$resepno       = $this->input->get('resepno');
			$kodebarang    = $this->input->get('kodebarang');
			$namabarang    = $this->input->get('namabarang');
			$qty           = $this->input->get('qty');
			$satuan        = $this->input->get('satuan');
			$discount      = $this->input->get('discount');
			$discrp        = $this->input->get('discrp');
			$price         = $this->input->get('price');
			$totalrp       = $this->input->get('totalrp');
			$aturpakai     = $this->input->get("aturanpakai");
			$keterangan    = $this->input->get("keterangan");
			$ppn           = $this->input->get('ppn');
			$ppnrp         = $this->input->get('ppnrp');
			$expire		   = $this->input->get("expire");
			$data = [
				'koders' => $cabang,
				'resepno' => $resepno,
				'kodebarang' => $kodebarang,
				'namabarang' => $namabarang,
				'qty' => $qty,
				'satuan' => $satuan,
				'discount' => $discount,
				'discrp' => $discrp,
				'price' => $price,
				'totalrp' => $totalrp,
				'ppn' => $ppn,
				'ppnrp' => $ppnrp,
				'atpakai' => $aturpakai,
				'ket' => $keterangan,
				'exp_date' => $expire
			];

			$this->db->insert('tbl_apodresep', $data);
			$stokcek   = $this->db->query("SELECT * FROM tbl_barangstock WHERE kodebarang = '$kodebarang' and koders='$cabang' and gudang='$gudang'")->num_rows();
			if ($stokcek > 0) {
				$this->db->query("UPDATE tbl_barangstock set keluar=keluar+$qty, saldoakhir= saldoakhir-$qty where kodebarang = '$kodebarang' and koders = '$cabang' and gudang = '$gudang'");
			} else {
				$datastock = array(
					'koders'       => $cabang,
					'kodebarang'   => $kodebarang,
					'gudang'       => $gudang,
					'saldoawal'    => 0,
					'terima'       => $qty,
					'saldoakhir'   => $qty,
					'tglso'        => $tanggal,
					'lasttr'       => $tanggal,
				);
				$insert_detil = $this->db->insert('tbl_barangstock', $datastock);
			}
		} else {
			header('location:' . base_url());
		}
	}
}
