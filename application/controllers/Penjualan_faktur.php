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

	public function index(){
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');
		$tanggal = date('Y-m-d');
		if (!empty($cek)) {
			$q1 = "SELECT a.*
				FROM tbl_apoposting AS a 
				INNER JOIN tbl_apohresep AS b ON a.resepno=b.resepno
				WHERE a.koders  = '$unit' 
				AND b.jenisjual = 1 
				AND a.tglresep  = '$tanggal' 
				ORDER BY a.tglresep DESC, a.resepno DESC";

			$bulan   = $this->M_global->_periodebulan();
			$nbulan  = $this->M_global->_namabulan($bulan);
			$periode = 'Periode ' . $nbulan . '-' . $this->M_global->_periodetahun();
			$level   = $this->session->userdata('level');
			$akses   = $this->M_global->cek_menu_akses($level, 3201);

			if(isset($_GET["filter-eresep"])){
				$tanggal 		= $_GET["filter-eresep"];
				$extract_tgl	= explode("~", $tanggal);

				$query_eresep	= $this->db->query("SELECT c.kodepos, a.id, a.koders, a.tglorder, a.orderno, a.noreg, a.rekmed, b.namapas, c.kodokter, '-' AS keterangan, a.resep, a.resepok 
				FROM tbl_orderperiksa AS a 
				LEFT JOIN tbl_pasien AS b ON a.rekmed = b.rekmed 
				LEFT JOIN tbl_rekammedisrs AS c ON c.noreg = a.noreg
				WHERE a.koders = '$unit' 
				AND a.tglorder BETWEEN '". $extract_tgl[0] ."' AND '". $extract_tgl[1] ."' 
				AND a.resep = 1 
				AND a.resepok = 0 
				ORDER BY a.id DESC")->result();
			} else {
				$tanggal = date("Y-m-d");

				$query_eresep	= $this->db->query("SELECT c.kodepos, a.id, a.koders, a.tglorder, a.orderno, a.noreg, a.rekmed, b.namapas, c.kodokter, '-' AS keterangan, a.resep, a.resepok 
				FROM tbl_orderperiksa AS a 
				LEFT JOIN tbl_pasien AS b ON a.rekmed = b.rekmed 
				LEFT JOIN tbl_rekammedisrs AS c ON c.noreg = a.noreg
				WHERE a.koders = '$unit' 
				AND a.tglorder LIKE '%$tanggal%' 
				AND a.resep = 1 
				AND a.resepok = 0 
				ORDER BY a.id DESC")->result();
			}

			$data	=	[
				"keu"		=> $this->db->query($q1)->result(),
				"eresep"	=> $query_eresep,
				"akses"		=> $akses,
				"periode"	=> $periode,
			];

			$this->load->view('penjualan/v_penjualan_faktur', $data);
		} else {
			header('location:' . base_url());
		}
	}

	public function filter($param){
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');
		$tanggal = date("Y-m-d");

		if (!empty($cek)) {
			$data  = explode("~", $param);
			$jns   = $data[0];
			$tgl1  = $data[1];
			$tgl2  = $data[2];
			$_tgl1 = date('Y-m-d', strtotime($tgl1));
			$_tgl2 = date('Y-m-d', strtotime($tgl2));

			if (!empty($jns)) {
				$query_eresep	= $this->db->query("SELECT a.id, a.koders, a.tglorder, a.orderno, a.noreg, a.rekmed, b.namapas, a.kodokter, '-' AS keterangan, a.resep, a.resepok 
				FROM tbl_orderperiksa AS a 
				LEFT JOIN tbl_pasien AS b ON a.rekmed = b.rekmed 
				WHERE a.koders = '$unit' 
				AND a.tglorder LIKE '%$tanggal%' 
				AND a.resep = 1 
				AND a.resepok = 0 
				ORDER BY a.id DESC")->result();

				$q1 = "SELECT a.* FROM tbl_apoposting AS a 
						INNER JOIN tbl_apohresep AS b ON a.resepno = b.resepno
						WHERE a.koders = '$unit' 
						AND b.jenisjual = 1 
						AND a.tglresep BETWEEN '$_tgl1' AND '$_tgl2' 
						ORDER BY a.tglresep desc, a.resepno DESC";

				$periode = 'Periode ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2));
				$level = $this->session->userdata('level');
				$akses = $this->M_global->cek_menu_akses($level, 3201);
				$d['keu'] = $this->db->query($q1)->result();
				$d['akses'] = $akses;
				$d['periode'] = $periode;
				$d['eresep']	= $query_eresep;
				$this->load->view('penjualan/v_penjualan_faktur', $d);
			}
		} else {
			header('location:' . base_url());
		}
	}

	public function cetak()
	{
		$cek        = $this->session->userdata('level');
		$unit       = $this->session->userdata('unit');
		$user       = $this->session->userdata('username');
		$nobukti    = $this->input->get('nobukti');
		if (!empty($cek)) {

			// $unit          = $this->session->userdata('unit');
			$profile       = data_master('tbl_namers', array('koders' => $unit));
			$nama_usaha    = $profile->namars;
			$alamat1       = $profile->alamat;
			$alamat2       = $profile->kota;

			$param         = $this->input->get('nobukti');

			$queryh        = "SELECT * from tbl_apohresep where resepno = '$param'";
			$queryd        = "SELECT * from tbl_apodresep where resepno = '$param' AND koders='$unit'";
			$detil_r       = "SELECT *, (SELECT aturanpakai FROM tbl_aporacik WHERE resepno = tbl_apodetresep.resepno) as aturanpakai,
			(SELECT namaracikan FROM tbl_aporacik WHERE resepno = tbl_apodetresep.resepno) as namaracikan from tbl_apodetresep where resepno = '$param' AND koders='$unit'";
			$queryr        = "SELECT * from tbl_aporacik where resepno = '$param' AND koders='$unit'";
			// $queryd =$this->db->query("SELECT a.diskonrp, a.totalrp , b.namabarang, b.qty, b.price, b.totalrp FROM tbl_aporacik AS a
			// JOIN tbl_apodresep AS b ON a.resepno = b.resepno
			// WHERE b.resepno ='$param'");
			// var_dump($queryd);die;

			$queryb        = "SELECT * from tbl_apoposting  where resepno = '$param'";
			$detil         = $this->db->query($queryd)->result();
			$rck           = $this->db->query($detil_r)->result();
			// var_dump($rck);die;
			$racikan       = $this->db->query($queryr)->row_array();
			$header        = $this->db->query($queryh)->row();
			$posting       = $this->db->query($queryb)->row();
			$data_pasien   = data_master('tbl_pasien', array('rekmed' => $header->rekmed));
			if($data_pasien){
				$alamat=$data_pasien->alamat;
			}else{
				$alamat='-';
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
			//$pdf->SetFillColor(0,0,139);
			//$pdf->settextcolor(255,255,255);
			$fc = array('0', '0', '0', '0', '0', '0', '0','0', '0');
			$judul = array('No.', 'Kode Obat', 'Nama Obat', 'Jenis Tr', 'Jumlah', 'Harga Sat', 'Diskon', 'Total Rp', 'Pakai');
			$pdf->FancyRow2(8, $judul, $fc, $border, $align);
			$border = array('', '', '');
			$pdf->setfont('Arial', '', 9);
			// $Subtot = 0;
			// $tdisc  = 0;
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
			$racik = $this->db->query("SELECT a.namaracikan, a.jumlahracik, a.kemasanracik, a.aturanpakai ,
			a.totalrp, a.diskonrp, a.ongkosracik, b.namabarang , b.resepno FROM tbl_aporacik AS a
			JOIN tbl_apodresep AS b ON a.resepno = b.resepno
			WHERE b.resepno ='$param'")->result();

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

				// right
				$cekdpp       += 111 / 100;
				$jumlahObat   += $db1->qty;
				$Totdisc      += $diskon;
				$Totdiscx     += $diskonx;
				// $DiskonPersen += $db1->discount;

				$ttlharga     += $db1->qty * $db1->price;
				$tot          = ($ttlharga - $Totdisc);
				$ppn          = ($ttlharga - $Totdisc) * $cekppn2;
				$TOTAL        = $tot  + $ppn;
				$dpp          = ($TOTAL - $Totdisc) / (111 / 100);

				$td           += $ttlrp;
				//   $dx = $db1->discount/100;
				//   $dxx = $dx*$db1->price;
				if($header->kodepel == 'adr'){
					$kodepel = 'Apotik Dengan Resep';
				} else if ($header->kodepel == 'atr') {
					$kodepel = 'Apotik Tanpa Resep';
				} else {
					$kodepel = $header->kodepel;
				}
				$atx = $this->db->query("SELECT * from tbl_barangsetup where  apocode='$db1->atpakai'")->row();
				$pdf->FancyRow(array(
					$no,
					$db1->kodebarang,
					$db1->namabarang,
					$kodepel,
					number_format($db1->qty, 0, ',', '.'),
					number_format($db1->price, 0, ',', '.'),
					number_format($diskonx, 0, ',', '.'),
					number_format($ttlrp, 0, ',', '.'),
					$atx->aponame,
				), $fc, $border, $align);

				$no++;
			}


			// var_dump($racikan);
			// die;

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
					$pdf->FancyRow(array(
						$no,
						$rck->kodebarang,
						("** $rck->namaracikan"),
						$kodepel,
						number_format($rck->qty, 0, ',', '.'),
						number_format($rck->price, 0, ',', '.'),
						//   number_format($racikan->diskonrp, 0, ',', '.'),
						//   number_format($racikan->totalrp, 0, ',', '.')),$fc, $border, $align);
						// number_format((!isset($diskon) ? 0 :$diskon), 0, ',', '.'),
						'-',
						number_format((!isset($rck->totalrp) ? 0 : $rck->totalrp), 0, ',', '.'),
						$atx->aponame,
					), $fc, $border, $align);

					$ongkos = ($racikan['ongkosracik']);


					$cekdpp += 111 / 100;
					$jumlahObat +=  $rck->qty;
					$Totdisc  += $diskon;
					$ttlhargax +=   $rck->qty * $rck->price;
					$tot  = ($ttlhargax - $Totdisc);
					// $dpp +=  $rck->totalrp - $Totdisc / (111/100);
					$ppn = ($ttlharga - $Totdisc) *  $cekppn2;
					$TOTAL =  $tot + $ppn;
					// $TOTAL += $tot + $ppn;
					// $dpp = ($TOTAL - $Totdisc) / (111/100);
					$no++;
				}
				$diskonracik   = $racikan['diskonrp'];
				$ppnx          = $td * $cekppn2;
				$ppnxx         = $racikan['ppnrp'];
				$ttlracikan    = $racikan['totalrp'];

			}else{
				
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

			// // $pdf->SetWidths(array(10,40,20,20,20,20,20, 20,20));
			// $border    = array('LTBR','LTBR','LTBR','LTBR','LTBR','LTBR','LTBR','LTBR','LTBR');
			// $align     = array('C','C','C','C','C','C','C','C','C');
			// $pdf->setfont('Arial','B',6);
			// $pdf->SetAligns(array('L','C','R'));
			// $fc        = array('0','0','0','0','0','0','0','0','0');
			// // $judul     = array('No.','NAMA RACIK','JENIS RACIK','JUMLAH RACIK','KEMASAN RACIK','ATURAN PAKAI','DISKON', 'TOTAL RP', 'ONGKOS RACIK');
			// $pdf->FancyRow2(9,$judul,$fc, $border,$align);
			// $border    = array('','','');
			// $pdf->setfont('Arial','',9);
			// $border    = array('','','','','','','','','');
			// $border    = array('LTBR','LTBR','LTBR','LTBR','LTBR','LTBR','LTBR','LTBR','LTBR');
			// $align     = array('C','L','L','C','C','C','C','R','R');
			// $fc        = array('0','0','0','0','0','0','0','0','0');
			// $pdf->SetFillColor(0,0,139);
			// $pdf->settextcolor(0);
			// $nomor 	  =1 ;
			// $racikan  = $this->db->query("SELECT * FROM tbl_aporacik WHERE resepno = '$nobukti' ")->result();
			// // $racikan  = $this->db->query("SELECT (SELECT aponame from tbl_barangsetup b where a.jenisracik=b.apocode)nmjnsracik,
			// // (SELECT aponame from tbl_barangsetup b where a.kemasanracik=b.apocode)nmkemas,
			// // (SELECT aponame from tbl_barangsetup b where a.aturanpakai=b.apocode)nmaturan,
			// // a.* 
			// // FROM tbl_aporacik a WHERE resepno = '$nobukti'")->result();

			// $racik = $this->db->query("SELECT a.namaracikan, a.jumlahracik, a.kemasanracik, a.aturanpakai ,
			// a.totalrp, a.diskonrp, a.ongkosracik, b.namabarang , b.resepno FROM tbl_aporacik AS a
			// JOIN tbl_apodresep AS b ON a.resepno = b.resepno
			// WHERE b.resepno ='$param'")->result();
			// KEMAS 1= SACHET
			// KEMAS 2 = TUBE
			// AT_PAKAI3= 3x1
			// foreach($racik as $db)
			// {
			//   $pdf->FancyRow(array(
			//   $nomor++,
			//   $db->namaracikan,
			//   ("**$db->namabarang"),
			// //    $db->kemasanracik,
			//    "SACHET",
			//   $db->jumlahracik,
			// //   $db->aturanpakai,
			// " 3 X 1",
			//   $db->diskonrp,
			//   $db->totalrp,
			//   number_format($db->ongkosracik, 0, ',', '.'),
			//  ),$fc, $border, $align);

			//   $no++;
			// }
			// foreach($racikan as $r)
			// {
			//   $pdf->FancyRow(array(
			// 		$nomor++,
			// 		$r->namaracikan,
			// 		// ("**Sabun, Tirai Sinar Matahari, 45E"),
			// 		// ("** $r->nmjnsracik"),
			// 		("** $r->nmjnsracik"),
			// 		$r->jumlahracik,
			// 		$r->nmkemas,
			// 		$r->nmaturan,
			// 		$r->diskonrp,
			// 		$r->totalrp,

			// 		number_format($r->ongkosracik, 0, ',', '.'),
			// 	), $fc, $border, $align);

			// }
			// $border = array('','','');
			// $align  = array('C','C','C');
			// $pdf->FancyRow(array('                          ','                                ','                  Jumlah Obat'),0,$border, $align);

			$pdf->SetWidths(array(140, 50));
			$border = array('', '');
			$align  = array('R', 'R');
			//$pdf->SetFillColor(230,230,230);
			//$pdf->settextcolor(0);
			$fc = array('0', '0');
			$style = array('B', 'B');
			$size  = array('', '');
			$border = array('', '');


			//$pdf->SetFillColor(0,0,139);
			//$pdf->settextcolor(255,255,255);
			$fc = array('0', '0');
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
			// $pdf->FancyRow(array('DPP',number_format($dpp ,0,',','.')),$fc, $border, $align, $style, $size);
			$pdf->FancyRow(array('Total Net', number_format(($td) + ($harga_asli) + ($ongkir), 0, ',', '.')), $fc, $border, $align, $style, $size);
			// var_dump($totsub);die;
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
			// echo json_encode($racikan);
		} else {

			header('location:' . base_url());
		}
	}

	public function cetak2() {
		setlocale(LC_ALL, 'id_ID.utf8');
			$cek 				= $this->session->userdata('level');
			$unit 			= $this->session->userdata('unit');
			$user 			= $this->session->userdata('username');
			$nobukti 		= $this->input->get('nobukti');
			$cekpdf     = 1;
			$body       = '';
			$date       = '';
			$judul      = 'Nota Pengeluaran';
			$profile    = data_master('tbl_namers', array('koders' => $unit));
			$kota       = $profile->kota;
			$position   = 'P';
			if (!empty($cek)) {
				$param        = $this->input->get('nobukti');
				$hresep				= $this->db->query("SELECT * FROM tbl_apohresep WHERE resepno = '$param'")->row();
				$posting			= $this->db->query("SELECT * FROM tbl_apoposting WHERE resepno = '$param'")->row();
				$pasien				= $this->db->query("SELECT * FROM tbl_pasien WHERE rekmed = '$hresep->rekmed'")->row();
				$dresep				= $this->db->query("SELECT * FROM tbl_apodresep WHERE resepno = '$param' AND koders = '$unit'")->result();
				$racik				= $this->db->query("SELECT * FROM tbl_aporacik WHERE resepno = '$param' AND koders = '$unit'")->row();
				$cekracik			= $this->db->query("SELECT * FROM tbl_aporacik WHERE resepno = '$param' AND koders = '$unit'")->num_rows();
				$detresep			= $this->db->query("SELECT * FROM tbl_apodetresep WHERE resepno = '$param' AND koders = '$unit'")->result();
				if($pasien){

					$namapas= $pasien->namapas;
					$rekmed = $pasien->rekmed;
					$alamat = $pasien->alamat;
				}else{
					$namapas= $posting->namapas;
					$rekmed = '<b>Non Member</b>';
					$alamat = $hresep->alamat;
				}
				// header
				// $date 				= new DateTime($hresep->tglresep);
				// $tglresep 		= strftime('%A, %d %B %Y', $date->getTimestamp());
				$date 			= $hresep->tglresep;
				$tglresep 		= $date;
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
						<td width=\"17%\">Nama Pasien</td>
						<td width=\"3%\">:</td>
						<td width=\"25%\">$namapas</td>
						<td width=\"10%\">&nbsp;</td>
						<td width=\"17%\">No. Registrasi</td>
						<td width=\"3%\">:</td>
						<td width=\"25%\">$hresep->noreg</td>
					</tr>
					<tr>
						<td width=\"17%\">No. Member</td>
						<td width=\"3%\">:</td>
						<td width=\"25%\">$rekmed</td>
						<td width=\"10%\">&nbsp;</td>
						<td width=\"17%\">Alamat Kirim</td>
						<td width=\"3%\">:</td>
						<td width=\"25%\">$alamat</td>
					</tr>
				</table>";
				// space
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"5\" cellmargin=\"5\">
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>";
				// detail 
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellpadding=\"5\" cellmargin=\"5\">";
				$body .= "<tr>
					<th bgcolor=\"#cccccc\">No</th>
					<th bgcolor=\"#cccccc\">Kode Obat</th>
					<th bgcolor=\"#cccccc\">Nama Obat</th>
					<th bgcolor=\"#cccccc\">Jenis Tr</th>
					<th bgcolor=\"#cccccc\">Jumlah</th>
					<th bgcolor=\"#cccccc\">Harga Satuan</th>
					<th bgcolor=\"#cccccc\">Diskon</th>
					<th bgcolor=\"#cccccc\">Total Rp</th>
				</tr>";
				// isi resep
				$no = 1;
				$jum_resep 			= 0;
				$sub_resep1 		= 0;
				$diskon_resep1 	= 0;
				$total_resep1 	= 0;
				foreach($dresep as $d) {
					$jum_resep 			+= $d->qty;
					$sub_resep1 		+= ($d->qty * $d->price);
					$diskon_resep1 	+= ($d->discrp);
					$total_resep1 	+= ($d->totalrp);
					if($cekpdf == 1){
						$qty 			= number_format($d->qty);
						$price 		= number_format($d->price);
						$discrp 	= number_format($d->discrp);
						$totalrp 	= number_format($d->totalrp);
					} else {
						$qty 			= ceil($d->qty);
						$price 		= ceil($d->price);
						$discrp 	= ceil($d->discrp);
						$totalrp 	= ceil($d->totalrp);
					}
					$body .= "<tr>
						<td style=\"text-align: right;\">".$no++."</td>
						<td>$d->kodebarang</td>
						<td>$d->namabarang</td>
						<td>$hresep->kodepel</td>
						<td style=\"text-align: right;\">$qty</td>
						<td style=\"text-align: right;\">$price</td>
						<td style=\"text-align: right;\">$discrp</td>
						<td style=\"text-align: right;\">$totalrp</td>
					</tr>";
				}
				$ppn = $this->db->query("SELECT * FROM tbl_pajak WHERE kodetax = 'PPN'")->row();
				$pajak = (int)$ppn->prosentase / 100;
				$dpp_resep1 = $total_resep1 / (111 / 100);
				$ppn_resep1 = $dpp_resep1 * $pajak;
				// resep racik
				// jika ada
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
						<td>$racik->resepno</td>
						<td>** $racik->namaracikan</td>
						<td>$hresep->kodepel</td>
						<td style=\"text-align: right;\">$racik->jumlahracik</td>
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
	
				// space
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"5\" cellmargin=\"5\">
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>";
				// aritmatika
				if($cekpdf == 1) {
					$dpp_resep = number_format($dpp_resep1);
					$ppn_resep = number_format($ppn_resep1);
				} else {
					$dpp_resep = ceil($dpp_resep1);
					$ppn_resep = ceil($ppn_resep1);
				}
	
				if($hresep->rekmed=="Non Member"){
					if($posting->nokwitansi<>'' || $posting->nokwitansi<> null){
						$link = "<b style='color: green; font-size: 20px; border: solid 3px green;'>LUNAS</b>";
					} else {
						$link = "<b style='color: red; font-size: 20px; border: solid 3px red;'>BELUM LUNAS</b>";
					}
				}else{
					$link="";
				}
				
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
				// space
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"5\" cellmargin=\"5\">
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>";
				// total
				if($cekracik > 0){
					if((int)$racik->harga_manual > 0){
						$racikan = $racik->harga_manual;
					} else {
						$racikan = $racik->totalrp;
					}
				} else {
					$racikan = 0;
				}
				$resepan = $total_resep1;
				$total_semua1 = $racikan + $resepan;
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
	
				// space
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
	
				// tanda tangan
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"5\" cellmargin=\"5\">
					<tr>
						<td width=\"33%\" style=\"text-align: center;\">(............................)</td>
						<td width=\"34%\" style=\"text-align: center;\">(............................)</td>
						<td width=\"33%\" style=\"text-align: center;\">(............................)</td>
					</tr>
					<tr>
						<td width=\"33%\" style=\"text-align: center;\">Bagian Stok</td>
						<td width=\"34%\" style=\"text-align: center;\">Bagian Penerima</td>
						<td width=\"33%\" style=\"text-align: center;\">Yang Menyetujui</td>
					</tr>
				</table>";
	
				// space
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
				// pencetak
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"right\" border=\"0\" cellpadding=\"5\" cellmargin=\"5\">";
				$body .= "<tr>
						<td style=\"text-align: right;\">Dicetak pada : ".strftime('%A, %d %B %Y', $now->getTimestamp())."</td>
					</tr>
					<tr>
						<td style=\"text-align: right;\">$user ( Jam ".date("H:i:s")." )</td>
					</tr>
				</table>";
				$this->M_template_cetak->template($judul, $body, $position, $date, $cekpdf);
			} else {
				header('location:' . base_url());
			}
		}

	public function email($param){
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');

		if (!empty($cek)) {
			$profile = $this->M_global->_LoadProfileLap();
			$unit = $this->session->userdata('unit');
			$nama_usaha = $profile->nama_usaha;
			$alamat1  = $profile->alamat1;
			$alamat2  = $profile->alamat2;
			$email_usaha = $profile->email;

			$queryh = "select * from ar_sifile inner join ar_customer on ar_sifile.kodecust=ar_customer.kode where kodesi = '$param'";
			$queryd = "select ar_sidetail.*, inv_barang.namabarang from ar_sidetail inner join inv_barang on ar_sidetail.kodeitem=inv_barang.kodeitem where ar_sidetail.kodesi = '$param'";
			$queryb = "select sum(jumlah) as total from ar_sibiaya  where ar_sibiaya.kodesi = '$param'";

			$detil  = $this->db->query($queryd)->result();
			$header = $this->db->query($queryh)->row();
			$biaya  = $this->db->query($queryb)->row();
			$pdf = new simkeu_nota();
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
			//$pdf->SetFillColor(0,0,139);
			//$pdf->settextcolor(255,255,255);
			$fc = array('0', '0', '0', '0', '0', '0');
			$judul = array('Kode Barang', 'Nama Barang', 'Qty', 'Harga', 'Diskon', 'Total Harga');
			$pdf->FancyRow2(8, $judul, $fc, $border, $align);
			$border = array('', '', '');
			$pdf->setfont('Arial', '', 10);
			$tot = 0;
			$subtot = 0;
			$tdisc  = 0;
			$border = array('', '', '', '', '', '');
			$align  = array('L', 'L', 'R', 'R', 'R', 'R');
			$fc = array('0', '0', '0', '0', '0', '0');
			$pdf->SetFillColor(0, 0, 139);
			$pdf->settextcolor(0);
			foreach ($detil as $db) {
				$dpp = $db->qtysi * $db->hargajual;
				$dis = ($db->disc / 100) * $dpp;
				$jum = $dpp - $dis;
				$tot = $tot + $jum;

				$subtot = $subtot + $dpp;
				$tdisc  = $tdisc + $dis;

				$pdf->FancyRow(array(
					$db->kodeitem,
					$db->namabarang,
					$db->qtysi,
					number_format($db->hargajual, 0, '.', ','),
					$db->disc,
					number_format($jum, 0, '.', ',')
				), $fc, $border, $align);
			}


			if ($header->sppn == "Y") {
				$ppn = $subtot * 0.1;
			} else {
				$ppn = 0;
			}
			$biayalain = $biaya->total;
			$tot = $tot + $ppn + $biayalain;
			$pdf->SetFillColor(230);
			$border = array('B', 'B', 'B', 'B', 'B', 'B');
			$pdf->FancyRow(array('', '', '', '', '', ''), 0, $border);
			$pdf->ln(2);
			$pdf->SetWidths(array(100, 20, 30, 40));
			$border = array('TB', '', 'T', 'T');
			$align  = array('L', '', 'L', 'R');
			//$pdf->SetFillColor(230,230,230);
			//$pdf->settextcolor(0);
			$fc = array('0', '0', '0', '0');
			$pdf->FancyRow(array('Keterangan', '', 'Sub Total', number_format($subtot, 0, '.', ',')), $fc, $border, $align, 0);
			$border = array('', '', '', '');
			$pdf->FancyRow(array($header->ket, '', 'Diskon', number_format($tdisc, 0, '.', ',')), $fc, $border, $align);
			$pdf->FancyRow(array('', '', 'PPN (10%)', number_format($ppn, 0, '.', ',')), $fc, $border, $align);
			$pdf->FancyRow(array('', '', 'Biaya Lain-lain', number_format($biayalain, 0, '.', ',')), $fc, $border, $align);
			$style = array('', '', 'B', 'B');
			$size  = array('', '', '', '');
			$border = array('T', '', 'BT', 'BT');
			//$pdf->SetFillColor(0,0,139);
			//$pdf->settextcolor(255,255,255);
			$fc = array('0', '0', '0', '0');
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

			//send email

			$email_tujuan = trim($header->email);
			$nama_tujuan  = $header->nama;
			$server_subject = "Faktur Penjualan ";
			$ready_message = "
				Kepada Yth " . $nama_tujuan . ",
				Berikut ini kami lampirkan File Faktur Penjualan ";

			$attched_file = './uploads/si/' . $param . '.PDF';
			$this->load->library('email');

			$config['protocol'] = "smtp";
			$config['smtp_host'] = $profile->smtp_host;
			$config['smtp_port'] = $profile->smtp_port;
			$config['smtp_user'] = $profile->email;
			$config['smtp_pass'] = $profile->pwdemail;
			$config['smtp_crypto'] = 'tls';
			$config['charset'] = "utf-8";
			$config['mailtype'] = "html";
			$config['newline'] = "\r\n";
			//$this->email->initialize($config);

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

	public function cekstok(){
		$barang = $this->input->get('kode');
		$gudang = $this->input->get('gudang');
		$cabang = $this->session->userdata('unit');
		$data = $this->db->query('select * from tbl_barangstock where kodebarang = "' . $barang . '" and koders = "' . $cabang . '" and gudang = "' . $gudang . '"')->row_array();
		if ($data) {
			$jumlah = $data['saldoakhir'];
			echo $jumlah;
		} else {
			echo json_encode(['status' => 2]);
		}
	}

	public function cekharga(){
		$barang = $this->input->get('kode');
		$jumlah = data_master('tbl_barang', array('kodebarang' => $barang))->hargabelippn;
		echo $jumlah;
	}

	public function getpo($po){
		$data = $this->db->select('ar_sodetail.*, inv_barang.namabarang')->join('inv_barang', 'inv_barang.kodeitem=ar_sodetail.kodeitem', 'left')->get_where('ar_sodetail', array('kodeso' => $po))->result();
		echo json_encode($data);
	}

	public function getsd($po){
		$data = $this->db->select('ar_kirimdetil.*, inv_barang.namabarang')->join('inv_barang', 'inv_barang.kodeitem=ar_kirimdetil.kodeitem', 'left')->get_where('ar_kirimdetil', array('kodekirim' => $po))->result();
		echo json_encode($data);
	}

	public function getbiaya($po){
		$data = $this->db->select('ar_sobiaya.*, ms_akun.namaakun')->join('ms_akun', 'ms_akun.kodeakun=ar_sobiaya.kodeakun', 'left')->get_where('ar_sobiaya', array('kodeso' => $po))->result();
		echo json_encode($data);
	}

	public function getlistpo($supp){
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
<?php } else { echo ""; } }

	public function getlistsd($supp){
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

	public function entri(){
		$cek = $this->session->userdata('level');
		$uid = $this->session->userdata('unit');
		if (!empty($cek)) {
			$page = $this->uri->segment(3);
			$limit = $this->config->item('limit_data');
			$d['nomor'] = urut_transaksi('URUT_BHP', 19);
			$d['ppn'] = $this->db->get_where('tbl_pajak', ['kodetax' => 'PPN'])->row_array();
			$d['atpakaix'] = $this->db->query("SELECT * from tbl_barangsetup where  apogroup='ATURANPAKAI' ")->result();
			$this->load->view('penjualan/v_penjualan_faktur_add', $d);
		} else {

			header('location:' . base_url());
		}
	}

	public function getakun($kode){
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

				<?php
				foreach ($data->result_array() as $row) { ?>
					<tr>
						<td width="50" align="center">
							<a href="#" onclick="post_akun('<?php echo $row['kodeakun']; ?>','<?php echo $row['namaakun']; ?>')">

								<?php echo $row['kodeakun']; ?></a>
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

	public function getbarang($kode){
			if (!empty($kode)) {
				$data  = explode("~", $kode);
				$q = $data[0];

				$cust = $data[1];
				$jenis = $this->db->get_where('ar_customer', array('kode' => $cust))->row()->tipe;

				$query = "select * from inv_barang where kodeitem like '%$q%' or namabarang like '%$q%' order by namabarang";
				$data  = $this->db->query($query);
				?>

				<table id="myTable">
					<tr class="header">
						<th style="width:20%;">Kode</th>
						<th style="width:60%;">Nama</th>
						<th style="width:10%;">Stok</th>
						<th style="width:10%;">Satuan</th>
					</tr>

					<?php
					foreach ($data->result_array() as $row) {
						if ($jenis == 1) {
							$harga = $row['hargajual1'];
						} else 
			   if ($jenis == 2) {
							$harga = $row['hargajual2'];
						} else 
			   if ($jenis == 3) {
							$harga = $row['hargajual3'];
						}
					?>
						<tr>
							<td width="50" align="center">
								<a href="#" onclick="post_value('<?php echo $row['kodeitem']; ?>','<?php echo $row['namabarang']; ?>','<?php echo $row['satuan']; ?>','<?php echo $harga; ?>')">

									<?php echo $row['kodeitem']; ?></a>
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

	public function getharga($kode){
				if (!empty($kode)) {
					$data  = explode("~", $kode);
					$supp  = $data[0];
					$item  = $data[1];

					$query = "select * from ar_sidetail inner join ar_sifile on ar_sifile.kodesi=ar_sidetail.kodesi where ar_sifile.kodecust = '$supp' and ar_sidetail.kodeitem = '$item' order by ar_sifile.tglsi desc";
					$data  = $this->db->query($query)->result();
					?>

					<table class="table" id="myTable">
						<tr class="headerx">
							<th style="width:20%;">No. Faktur</th>
							<th style="width:20%;">Tanggal</th>
							<th style="width:20%;">Harga</th>
							<th style="width:10%;">Disc</th>
							<th style="width:10%;">Satuan</th>
						</tr>

						<?php
						foreach ($data  as $row) { ?>
							<tr>
								<td width="50" align="centerx">
									<a href="#" onclick="post_harga('<?php echo $row->hargajual; ?>','<?php echo $row->satuan; ?>')">

										<?php echo $row->kodesi; ?></a>
								</td>
								<td><?php echo date('d-m-Y', strtotime($row->tglsi)); ?></td>
								<td><?php echo $row->hargajual; ?></td>
								<td><?php echo $row->disc; ?></td>
								<td><?php echo $row->satuan; ?></td>
							</tr>

			<?php
						}
						echo "</table>";
					} else {
						echo "";
					}
	}

	public function getinfobarang()
	{		
		$cabang = $this->session->userdata("unit");
		$kode   = $this->input->get('kode');
		$gudang = $this->input->get('gudang');
		// $data = $this->M_global->_data_barang($kode);
		$data = $this->db->query("SELECT b.*, (SELECT saldoakhir FROM tbl_barangstock WHERE kodebarang = '$kode' AND gudang = '$gudang' AND koders = '$cabang') as saldoakhir FROM tbl_barang b 
		WHERE b.kodebarang = '$kode'")->row();
		echo json_encode($data);
	}

	public function getinfobarang_cbg(){
		$cabang = $this->session->userdata("unit");
		$kode = $this->input->get('kode');
		$gudang = $this->input->get('gudang');
		$cek = $this->db->query("SELECT * FROM tbl_barang WHERE kodebarang = '$kode'");
		if($cek->num_rows() > 0){
			$data = $cek->row();
		} else {
			$data = $this->db->query("SELECT * FROM tbl_logbarang WHERE kodebarang = '$kode'")->row();
		} 
		echo json_encode($data);
	}

	public function gethargapenjamin(){
		$cust_id = $this->input->get("cust_id");
		$data = $this->db->query("SELECT farmasirj AS harga FROM tbl_penjamin WHERE cust_id = '$cust_id'")->row();
		echo json_encode($data);
	}

	public function getinfoakun($kode){
		$data = $this->M_global->_data_akun($kode);
		echo json_encode($data);
	}

	public function getpoheader($kodepo){
		$data = $this->db->get_where('ar_sifile', array('kodesi' => $kodepo))->row();
		echo json_encode($data);
	}

	public function getbarangname($kode){
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

	public function norespauto(){
		$kode_cabang = $this->session->userdata('unit');
		$nobukti  = temp_urut_transaksi('RESEP', $kode_cabang, 19);
		echo json_encode($nobukti);
	}

	public function save_pasien()
	{
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
		// echo '<pre>';
		// print_r($q);
		// die;
		
		$qry = "SELECT * from tbl_pasien where namapas = '$c_lupnamapasien' and  penjamin='$c_vpenjamin' and nocard='$c_no_bpjs' ";
		$jumdata = $this->db->query($qry)->num_rows();
		
		if($jumdata>0){
			echo json_encode(array("status" => true,"value" => 0));	
		}else {
		// date_default_timezone_set("Asia/Jakarta");
		
			$rekmed     = pasien_rekmed_baru($c_lupnamapasien);	 // time();
			
			// $this->_validate();
			$data = array(
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

				);

			// print_r($data);
			$insert = $this->M_pasien->save($data);
		
	  	  echo json_encode(array("status" => true,"value" => 1, "idtr" => $insert,"rekmed" => $rekmed,"nama" => $c_lupnamapasien, "alamat" => $c_lupalamat));
		} 
	}

	public function saveracik(){
		$param = 1;
		$hasil    = 0;
		$cek      = $this->session->userdata('level');
		$userid   = $this->session->userdata('username');
		$cabang   = $this->session->userdata('unit');
		$gudang			= $this->input->post("gudang");

		$kode_cabang = $this->session->userdata('unit');
		$nobukti  = temp_urut_transaksi('RESEP', $kode_cabang, 19);
		$ppn_pajak_insx = $this->db->get_where('tbl_pajak', ['kodetax' => 'PPN'])->row_array();
		$ppn_pajak_ins = $ppn_pajak_insx['prosentase'] / 100;
		if (!empty($cek)) {
			// begin header harimas
			// $noresepo    = $this->input->post('noresep');
			$noresepo    = $nobukti;
			$jenis_1     = $this->input->post('jenis_1');
			$namaracik_1 = $this->input->post('namaracik_1');
			$jumracik_1  = $this->input->post('jumracik_1');
			$stajum_1    = $this->input->post('stajum_1');
			$atpakai_1   = $this->input->post('atpakai_1');
			$toto_1      = $this->input->get('toto_1');
			$disknom_1   = $this->input->post('disknom_1');
			$disk_1      = $this->input->get('disk_1');
			// $ppn_1       = $this->input->get('ppn_1');
			$ongra_1     = $this->input->post('ongra_1');
			$carapakai_1     = $this->input->post('carapakai');
			$totp_1      = $this->input->get('totp_1');
			$carapakai      = $this->input->post('carapakai');
			$resep_manual      = $this->input->get('resman_1');
			$cek_rm      = $this->input->get('cek_rm');
			$harga_manual      = $this->input->get('harga_manual');

			// if($ppn_1 != null || $ppn_1 != ''){
			// 	$cekppn_1=1;
			// }else{
			// 	$cekppn_1=0;
			// }
			$cekppn_1 = 1;
			$ppn_1 = (($totp_1) / (111 / 100)) * $ppn_pajak_ins;

			$cekid = $this->db->query("SELECT id from tbl_aporacik order by id desc limit 1")->result();
			foreach ($cekid as $row) {
				$idd    = $row->id;
				if ($idd == null) {
					$idd = 0;
				} else {
					$idd = $idd;
				}
				$id_ok  = $idd + 1;
			}

			$data = array(
				'id'		   => $id_ok,
				'noracik'      => $cabang,
				'koders'       => $cabang,
				'resepno'      => $noresepo,
				'jenisracik'   => $jenis_1,
				'namaracikan'  => $namaracik_1,
				'aturanpakai'  => $carapakai_1,
				'jumlahracik'  => $jumracik_1,
				'kemasanracik' => $stajum_1,
				'aturanpakai'  => $atpakai_1,
				'carapakai'  => $carapakai,
				'subtotal'     => $toto_1,
				'diskon'       => $disknom_1,
				'resep_manual' => $resep_manual,
				'diskonrp'     =>  str_replace(',', '', $disk_1),
				'ppn'          =>  $cekppn_1,
				'ppnrp'        =>  str_replace(',', '', $ppn_1),
				'ongkosracik'  => str_replace(',', '', $ongra_1),
				'totalrp'      => str_replace(',', '', $totp_1),
				'cek_rm'      => $cek_rm,
				'harga_manual'      => $harga_manual,
			);

			if ($param == 1) {
				$this->db->insert('tbl_aporacik', $data);
				// echo json_encode($dataxx);
			} else {
				$data['tgledit']   = date('Y-m-d');
				$data['jamedit']   = date('H:i:s');
				$data['useredit']  = $userid;
				$this->db->update('tbl_aporacik', $data, array('resepno' => $noresepo));
			}

			// end header harimas

			// begin detail harimas
			// $kodeo_1       = $this->input->get('kodeo');
			// $namao_1       = $this->input->get('namao');
			// $sato_1        = $this->input->get('satuan');
			// $qty_racik_1   = $this->input->get('qty_racik');
			// $qty_jual_1    = $this->input->get('qty_jual');
			// $hargaoju1     = $this->input->get('harga');
			// $uangr1        = $this->input->get('uang');
			// $total_hrg1    = $this->input->get('total');
			$kodeo_1       = $this->input->post('kodeo_1');
			$namao_1       = $this->input->post('namao_1');
			$sato_1        = $this->input->post('sato_1');
			$qty_racik_1   = $this->input->post('qty_racik_1');
			$qty_jual_1    = $this->input->post('qty_jual_1');
			$hargaoju1     = $this->input->post('hargaoju1');
			$uangr1        = $this->input->post('uangr1');
			$total_hrg1    = $this->input->post('total_hrg1');
			$exp1    = $this->input->post('exp1');




			// $jumdata   = (int)count($kodeo_1);
			// $jumdata   = $this->input->get('jml');
			// $jumdataa    = count($kodeo_1);
			// $jumdata     = $jumdataa-1;
			$jumdata   = $this->input->get('jml');
			$nourut    = 1;
			$tot       = 0;
			$tdisc     = 0;
			$tothpp    = 0;

			// for($i=0;$i<=$jumdata-1;$i++)


			// print_r($qty_racik_1);
			for ($i = 0; $i <= ($jumdata - 1); $i++) {
				$_kodeo_1       = $kodeo_1[$i];
				// $_namao_1       = $namao_1[$i];
				$_sato_1        = $sato_1[$i];
				$_qty_racik_1   = $qty_racik_1[$i];
				$_qty_jual_1    = $qty_jual_1[$i];
				$_uangr1        = $uangr1[$i];
				$_exp1        = $exp1[$i];
				// $_hargaoju1     = str_replace(',','',$hargaoju1[$i]);
				// $_total_hrg1    = str_replace(',','',$total_hrg1[$i]);
				$_hargaoju1     = preg_replace('/[,]/', '', $hargaoju1[$i]);
				$_total_hrg1     = preg_replace('/[,]/', '', $total_hrg1[$i]);
				// $hpp            = $this->M_global->_data_barang($_kodeo_1)->hpp;
				$hpp1 = $this->db->get_where('tbl_barang', ['kodebarang' => $_kodeo_1])->row_array();
				$hpp = $hpp1['hpp'];
				// $cekidnya = $this->db->query("SELECT * FROM tbl_apodetresep ORDER BY resepno DESC LIMIT 1")->result();
				// foreach ($cekidnya as $row) {
				// 	$idnya = $row->id;
				// 	if ($idnya == null) {
				// 		$idnya = 0;
				// 	} else {
				// 		$idnya = $idnya;
				// 	}
				// 	$id_nya = $idnya + 1;
				// }
				$barang = $this->db->query("SELECT * FROM tbl_barang WHERE kodebarang = '$_kodeo_1'")->row();

				$datad = array(
					// 'Resepid'     => $id_nya,
					'koders'      => $cabang,
					'hpp'      	  => $hpp,
					'resepno'     => $noresepo,
					'kodebarang'  => $_kodeo_1,
					'namabarang'  => $barang->namabarang,
					'satuan'      => $_sato_1,
					'qtyr'        => $_qty_racik_1,
					'qty'         => $_qty_jual_1,
					'uangr'       => $_uangr1,
					'price'       => $_hargaoju1,
					'totalrp'     => $_total_hrg1,
					'exp_date'    => date('Y-m-d', strtotime($_exp1)),
					'jamdresep'    => date('H-i-s'),
				);

				// echo json_encode($datad, JSON_PRETTY_PRINT);
				if ($_kodeo_1 != "") {
					$this->db->insert('tbl_apodetresep', $datad);
					$cekstok = $this->db->query("SELECT * FROM tbl_barangstock WHERE kodebarang = '$_kodeo_1' AND koders = '$cabang'  AND gudang = '$gudang'")->num_rows();
					$date_now = date('Y-m-d H:i:s');
					if($cekstok > 0){
						$this->db->query("UPDATE tbl_barangstock SET keluar = keluar+$_qty_racik_1, saldoakhir = saldoakhir-$_qty_racik_1, lasttr = '$date_now' WHERE kodebarang = '$_kodeo_1' AND koders = '$cabang'  AND gudang = '$gudang'");
					} else {
						$datastock = [
							'koders'       => $cabang,
							'kodebarang'   => $_kodeo_1,
							'gudang'       => $gudang,
							'saldoawal'    => 0,
							'terima'    	 => 0,
							'keluar'       => $_qty_racik_1,
							'saldoakhir'   => 0-$_qty_racik_1,
							'lasttr'       => $date_now,
						];
						$this->db->insert('tbl_barangstock', $datastock);
					}
				}
			}
			echo json_encode(["status" => 1]);
			// end detail harimas

		} else {
			header('location:' . base_url());
		}
	}

	public function save($param){
		$hasil    		= 0;
		$vnoreg   		= '';
		$rekmed   		= '';
		$unit     		= $this->session->userdata('unit');
		$cabang   		= $this->session->userdata('unit');
		$cek      		= $this->session->userdata('level');
		$userid   		= $this->session->userdata('username');
		$racikanxx    	= $this->input->get('racikan');
		$noreg    		= $this->input->post('noreg');
		$rekmed   		= $this->input->post('pasien');
		if($rekmed){
			$rekmed =$rekmed;
		}else{
			$rekmed = 'Non Member';

		}
		$pembeli  		= $this->input->post('pembeli');
		$dokter   		= $this->input->post('dokter');
		$nokwi    		= urut_transaksi('URUTKWT', 19);
		$eresepstatus	= $this->input->post("eresepstatus");
		$gudang			= $this->input->post("gudang");
		$ketpakai		= $this->input->post("keterangan");
		$aturpakai		= $this->input->post("aturan_pakai");
		$expire			= $this->input->post("expire");

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
				$jenispas = 3;
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

				$datamutasi = $this->db->get_where('tbl_apodresep', array('resepno' => $nobukti))->result();

				foreach ($datamutasi as $row) {
					$_qty = $row->qty;
					$_kode = $row->kodebarang;
					$stok = $this->db->query("select * from tbl_barangstock where kodebarang = '$_kode' and gudang = '$gudang' and koders = '$cabang'")->num_rows();
					$date_now = date('Y-m-d H:i:s');
					if($stok > 0){
						$this->db->query("UPDATE tbl_barangstock set keluar = keluar+ $_qty, saldoakhir = saldoakhir - $_qty, lasttr = '$date_now' where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang'");
					} else {
						$datastock = array(
							'koders'       => $cabang,
							'kodebarang'   => $_kode,
							'gudang'       => $gudang,
							'saldoawal'    => 0,
							'terima'       => 0,
							'keluar'       => $_qty,
							'saldoakhir'   => 0-$_qty,
							'lasttr'       => $date_now,
						);
						$this->db->insert('tbl_barangstock', $datastock);
					}
				}
				$this->db->delete('tbl_apodresep', array('resepno' => $nobukti));
			}


			$kode    = $this->input->post('kode');
			$nama    = $this->input->post('nama');
			$qty     = $this->input->post('qty');
			$sat     = $this->input->post('sat');
			$harga   = $this->input->post('harga');
			$ppn     = $this->input->post('ppn');
			// $ppn     = $this->input->post('_vppn');
			$disc    = $this->input->post('disc');
			$disc2   = $this->input->post('disc2');
			$jumlah  = $this->input->post('jumlah');


			if($kode == '' | $kode == null) {
				$jumdata = 0;
			} else {
				$jumdata = count($kode);
			}

			$nourut  = 1;
			$tot     = 0;
			$tdisc   = 0;
			$tothpp  = 0;
			$disc_resep_ins  = 0;


			for ($i = 0; $i <= $jumdata - 1; $i++) {
				$_kode    = $kode[$i];
				$_qty     = $qty[$i];
				// $_jumlah  = str_replace(',','',$jumlah[$i]);
				// $_harga   = str_replace(',','',$harga[$i]);
				// $_discrp  = str_replace(',','',$disc2[$i]);
				$_jumlah  = preg_replace('/[,]/', '', $jumlah[$i]);
				$_harga   = preg_replace('/[,]/', '', $harga[$i]);
				$_discrp  = preg_replace('/[,]/', '', $disc2[$i]);

				// $tot      += $_jumlah;

				// $vjum     = ($qty[$i] * $_harga) - $disc2[$i] ;
				$vjum     = (preg_replace('/[,]/', '', $qty[$i]) * $_harga) - preg_replace('/[,]/', '', $disc2[$i]);
				$tot += $vjum;
				$disc_resep_ins += $_discrp;
				$dpp_resep_ins = $tot - $disc_resep_ins;
				//$vdisc = $vjum * ($disc[$i]/100);
				//$tot   = $tot + $vjum;
				// $tdisc    = $tdisc + $_discrp;

				// $hpp      = $this->M_global->_data_barang($_kode)->hpp;
				$hpp1 = $this->db->get_where('tbl_barang', ['kodebarang' => $_kode])->row();
				$hpp = $hpp1->hpp;
				$hna1 = $this->db->get_where('tbl_barang', ['kodebarang' => $_kode])->row();
				$hna = $hna1->hargabeli;
				//$namabarang = $this->M_global->_data_barang($_kode)->namabarang;
				$tothpp   = $tothpp + ($hpp * $qty[$i]);

				// if($ppn[$i]){
				// $_ppn    = 1;
				// $_ppnrp  = $vjum*$ppn_pajak;
				// 	$_ppnrpx  = $dpp_resep_ins*$ppn_pajak;
				// } else {
				// 	$_ppn   = 0;
				// 	$_ppnrp = 0;
				// 	$_ppnrpx = 0;
				// }

				$_ppn    = 1;
				$_ppnrp  = (($vjum) / (111 / 100)) * $ppn_pajak_ins;

				$datad = array(
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
					'atpakai'		=> $aturpakai[$i],
					'ket'			=> $ketpakai[$i],
					'exp_date'		=> $expire[$i],
				);


				if ($_kode != "") {
					$this->db->insert('tbl_apodresep', $datad);

					if($eresepstatus == 1){
						$this->db->query("UPDATE tbl_eresep SET qtyproses = $qty[$i] WHERE kodeobat = '$_kode' AND orderno = '$noeresep'");
					}

					$stokcek = $this->db->query("select * from tbl_barangstock where kodebarang = '$_kode' and gudang = '$gudang' and koders = '$unit'")->num_rows();
					$date_now = date('Y-m-d H:i:s');
					if($stokcek > 0){
						$this->db->query("UPDATE tbl_barangstock set keluar = keluar+ $_qty, saldoakhir = saldoakhir - $_qty, lasttr = '$date_now' where kodebarang = '$_kode' and koders = '$unit' and gudang = '$gudang'");
					} else {
						$datastock = array(
							'koders'       => $unit,
							'kodebarang'   => $_kode,
							'gudang'       => $gudang,
							'saldoawal'    => 0,
							'terima'       => 0,
							'keluar'			 => $_qty,
							'saldoakhir'   => 0-$_qty,
							'lasttr'       => $date_now,
						);
						$this->db->insert('tbl_barangstock', $datastock);
					}
				}
			}

			if ($noreg) {
				$vnoreg = $noreg;
			}

			$noreg = $vnoreg;

			$data = array(
				'koders'    => $this->session->userdata('unit'),
				'resepno'   => $nobukti,
				'eresepno'	=> $noeresep,
				'antrino'   => 1,
				'noreg'     => $noreg,
				'rekmed'    => $rekmed,
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
				//'gudang'  => $this->input->post('gudang'),
				'gudang'    => $gudang,
				'username'  => $userid,

			);

			if ($param == 1) {
				$this->db->insert('tbl_apohresep', $data);
			} else {
				$data['tgledit']   = date('Y-m-d');
				$data['jamedit']   = date('H:i:s');
				$data['useredit']  = $userid;
				$this->db->update('tbl_apohresep', $data, array('resepno' => $nobukti));
			}

			//posting
			$namapas = $this->input->post('namapasien');
			if($namapas){
				$namapas=$namapas;
			}else{
				$namapas = $this->input->post('nama_pas');
			}

			$data2 = array(
				'koders'    => $this->session->userdata('unit'),
				'resepno'   => $nobukti,
				'eresepno'	=> $noeresep,
				'tglresep'  => date('Y-m-d', strtotime($this->input->post('tanggal'))),
				'noreg'     => $noreg,
				'rekmed'    => $rekmed,
				'namapas'   => $namapas,
				'umurpas'   => '-',
				// 'umurpas'   => $this->input->post('umurpas'),
				//'gudang'  => $this->input->post('gudang'),
				'gudang'    => $gudang,
				'posting'   => 1,
				// 'poscredit' => $tot,  // str_replace(",","",$this->input->post('totp_1')),
				// 'poscredit' => str_replace(",", "", $this->input->get('vtotal')) + $racikanxx,
				'poscredit' => $this->input->get('vtotal'),
				'username'  => $userid,

			);

			if($eresepstatus == 1){
				$this->db->query("UPDATE tbl_orderperiksa SET resep = 0, resepok = 1, proses = 'terproses' WHERE orderno = '$noeresep'");
			}

			if ($param == 1) {
				$this->db->insert('tbl_apoposting', $data2);
			} else {
				$this->db->update('tbl_apoposting', $data2, array('resepno' => $nobukti));
			}
			$sql = $this->db->query('select * from tbl_apohresep where koders = "' . $cabang . '" order by id desc limit 1')->result();
			foreach ($sql as $s) {
				$checkiing = $this->db->get_where('tbl_aporacik', ['resepno' => $s->resepno])->num_rows();
				if ($checkiing == 0) {
					echo json_encode(array('status' => 2, 'nobukti' => $nobukti));
				} else {
					echo json_encode(array('status' => 1, 'nobukti' => $nobukti));
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
				$_qty = $row->qty;
				$_kode = $row->kodebarang;

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

	public function edit($nomor, $noedit = null){
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$unit = $this->session->userdata('unit');

			$posting   = $this->db->get_where('tbl_apoposting', array('resepno' => $nomor));
			$header    = $this->db->get_where('tbl_apohresep', array('resepno' => $nomor));
			$detil     = $this->db->select('tbl_apodresep.*, tbl_barang.namabarang as namabarang1')->join('tbl_barang', 'tbl_barang.kodebarang=tbl_apodresep.kodebarang')->get_where('tbl_apodresep', array('resepno' => $nomor));

			$header_r  = $this->db->get_where('tbl_aporacik', array('resepno' => $nomor));
			$detil_r   = $this->db->get_where('tbl_apodetresep', array('resepno' => $nomor));
			$dresep    = $this->db->get_where('tbl_apodresep', array('resepno' => $nomor));
			$dataRacik = $this->db->query("SELECT * FROM tbl_aporacik where resepno ='$nomor'");
			$total     = $this->db->query("SELECT totalrp FROM tbl_apodetresep WHERE resepno = '$nomor'");
			$d['header']   = $header->row();
			$d['detil']    = $detil->result();
			$d['posting']  = $posting->row();
			$d['jumdata']  = $detil->num_rows();
			$d['dracikan'] = $detil_r->num_rows();
			$d['noedit']   = $noedit;
			$d['racik']    = $dataRacik->row();
			$d['header_r'] = $header_r->row();
			$d['detil_r']  = $detil_r->result();
			$d['ttl']      = $total->result();
			$d['ppn']      = $this->db->get_where('tbl_pajak', ['kodetax' => 'PPN'])->row_array();

			$this->load->view('penjualan/v_penjualan_faktur_edit2', $d);
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
		$noresep      = $this->input->post('resepno');
		$rekmed       = $this->input->post('rekmed');
		$totalresep   = str_replace(',', '', $this->input->post('total_resep'));
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
			'totalresep'           => $totalresep,
			'uangmuka'             => 0,
			'adm'                  => 0,
			'diskon'               => 0,
			'diskonrp'             => 0,
			'admcredit'            => 0,
			'totalsemua'           => $totalresep,
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
			'totalbayar'           => $nil_aptk,
			'kembali'              => '',
			'posbayar'             => 'APTK',
			'dibayaroleh'          => $this->input->post('lupnamapasien'),
			'jenisbayar'           => 1,
			'username'             => $userid,
			'kembalikeuangmuka'    => 0,
			'namapasien'           => $this->input->post('lupnamapasien'),
			'shift'                => 0,
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
			'totalresep' => $nil_aptk,
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

		echo json_encode(array("status" => TRUE, "nomor" => $kwitansi, "total" => $totalresep));
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
