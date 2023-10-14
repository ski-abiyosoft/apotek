<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Logistik_kartustock extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_global', 'M_global');
		$this->load->model('M_cetak', 'M_cetak');
		$this->load->model('M_KartuStock');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '4000');
		$this->session->set_userdata('submenuapp', '4305');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');

		if (!empty($cek)) {
			$unit = $this->session->userdata('unit');
			$this->load->helper('url');
			$data['cabang'] = $this->db->get_where('tbl_namers', array("koders" => $unit))->row();

			$this->load->view('logistik/v_logistik_kartustock', $data);
		} else {
			header('location:' . base_url());
		}
	}

	public function tampil()
	{
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');


		$tanggal1 = $this->input->get('tanggal1');
		$tanggal2 = $this->input->get('tanggal2');
		$cabang = $this->input->get('cabang');
		$barang = $this->input->get('barang');
		$gudang = $this->input->get('gudang');
		$kodebarang = $this->input->get('kodebarang');
		$query_cab		= $this->db->query("SELECT * FROM tbl_namers WHERE koders = '$cabang'")->row();
		$query_gud		= $this->db->query("SELECT * FROM tbl_depo WHERE depocode = '$gudang'")->row();
		$namabarang     = data_master('tbl_logbarang', array('kodebarang' => $kodebarang))->namabarang;

		$profile = $this->M_global->_LoadProfileLap();
		$nama_usaha = $profile->nama_usaha;
		$motto = '';
		$alamat = '';
		$namaunit = $this->M_global->_namaunit($unit);

		$_tgl1          = date('Y-m-d', strtotime($tanggal1));
		$_tgl2          = date('Y-m-d', strtotime($tanggal2));
		$_peri          = 'Dari ' . date('d-m-Y', strtotime($tanggal1)) . ' s/d ' . date('d-m-Y', strtotime($tanggal2));
		$_peri1         = 'Per Tgl. ' . date('d', strtotime($tanggal2)) . ' ' . $this->M_global->_namabulan(date('n', strtotime($tanggal2))) . ' ' . date('Y', strtotime($tanggal2)); {
			$bulan          = date('n', strtotime($tanggal1));
			$tahun          = date('Y', strtotime($tanggal2));
			//     $query          =  "SELECT * from tbl_barangstock 
			// 	where koders = '$cabang' and kodebarang='$barang' and gudang='$gudang' OR gudang = 'LOKAL'
			//    --  AND tglso BETWEEN '$tanggal1' AND '$tanggal2'
			// 	AND tglso >'$_tgl1' ";


			$query_saldo =
				"SELECT * from tbl_apostocklog 
			 where koders = '$cabang' and kodebarang='$barang' and gudang='$gudang' OR gudang = 'LOKAL'
	
			 AND tglso >'$_tgl1' ";


			$lap = $this->db->query($query_saldo)->row();
			$_tanggalawal = $tanggal1;
			// $saldo = $lap->saldoawal;

			if ($lap) {
				$_tanggalawal = $lap->tglso;
				$saldo = $lap->saldoawal;
			} else {
				$_tanggalawal = '';
				$saldo = 0;
			}


			$mutasi =
				"SELECT * from 
		 (select 
		 a.terima_date as tanggal,
		 a.koders,
		 a.terima_no as nomor,
		 a.gudang,
		 b.kodebarang,
		 b.qty_terima as terima,
		 0 keluar,
		 b.qty_terima as qty,
		 b.price as harga,
		 tbl_vendor.vendor_name as rekanan,
		 'PEMBELIAN' as keterangan
		 from tbl_apohterimalog AS a
		 inner join tbl_apodterimalog AS b on a.terima_no=b.terima_no
		 inner join tbl_vendor on a.vendor_id=tbl_vendor.vendor_id
		 
		 union all

		 SELECT 
				tbl_apohreturbelilog.retur_date AS tanggal,
				tbl_apohreturbelilog.koders,
				tbl_apohreturbelilog.terima_no AS nomor,
				tbl_apohreturbelilog.gudang AS gudang,
				tbl_apodreturbelilog.kodebarang,
				0 AS terima,
				tbl_apodreturbelilog.qty_retur AS keluar,
				tbl_apodreturbelilog.qty_retur AS qty,
				tbl_apodreturbelilog.price AS harga,
				tbl_apohreturbelilog.vendor_id AS rekanan,
				'RETUR PEMBELIAN' AS keterangan 
				FROM tbl_apohreturbelilog INNER JOIN tbl_apodreturbelilog
				ON tbl_apohreturbelilog.retur_no = tbl_apodreturbelilog.retur_no 
		
		union all

		SELECT 
		tbl_pakaihlog.pakaidate AS tanggal,
		tbl_pakaihlog.koders,
		tbl_pakaihlog.pakaino AS nomor,
		tbl_pakaihlog.gudang AS gudang,
		tbl_pakaidlog.kodebarang,
		0 AS terima,
		tbl_pakaidlog.qty AS keluar,
		0 AS qty,
		tbl_pakaidlog.harga AS harga,
		tbl_pakaihlog.keterangan AS rekanan,
		'PEMAKAIAN LOG' AS keterangan 
		FROM tbl_pakaihlog INNER JOIN tbl_pakaidlog
		ON tbl_pakaihlog.pakaino = tbl_pakaidlog.pakaino
				
				
				union all
		 
		 select 
		 a.movedate as tanggal,
		 a.koders,
		 a.moveno as nomor,
		 a.ke as gudang,
		 b.kodebarang,
		 b.qtymove as terima,
		 0 as keluar,
		 b.qtymove as qty,
		 b.harga as harga,
		 a.mohonno as rekanan,
		 'MUTASI' as keterangan
		 from tbl_apohmovelog AS a 
		 inner join tbl_apodmovelog AS b on a.moveno=b.moveno

		 union all
		 
		 select 
		 tbl_apohmovelog.movedate as tanggal,
		 tbl_apohmovelog.koders,
		 tbl_apohmovelog.moveno as nomor,
		 tbl_apohmovelog.dari as gudang,
		 tbl_apodmovelog.kodebarang,
		 0 as terima,
		 tbl_apodmovelog.qtymove as keluar,				
		 tbl_apodmovelog.qtymove as qty,
		 tbl_apodmovelog.harga as harga,
		 tbl_apohmovelog.mohonno as rekanan,
		 'MUTASI' as keterangan
		 from tbl_apohmovelog inner join
		 tbl_apodmovelog on tbl_apohmovelog.moveno=tbl_apodmovelog.moveno
		 
		 union all
		 
		 SELECT
		 a.pakaidate AS tanggal,
		 a.koders,
		 a.pakaino AS nomor,
		 a.username AS gudang,
		 b.kodebarang,
		 0 AS terima,
		 b.qty AS keluar,
		 b.qty AS qty,
		 b.harga,
		 '-' AS rekanan,
		 'PEMAKAIAN' AS keterangan
		 FROM tbl_pakaihlog AS a
		 INNER JOIN tbl_pakaidlog AS b
		 ON a.pakaino = b.pakaino
		 ) mutasi
	 
		 where kodebarang = '$kodebarang' and
		 koders = '$cabang' and
		 gudang = '$gudang' and
		 tanggal between '$tanggal1' and '$tanggal2'
		 order by tanggal
		 
		 ";


			$nourut = 1;
			$data = $this->db->query($mutasi)->result();

			$data = [
				'judul' => 'KARTU STOCK LOGISTIK',
				'data' => $data,
				'tanggalwal'	=> $_tanggalawal,
				'saldo' => $saldo,
				'peri1' => $_peri1,
				'kop' => $this->M_cetak->kop($unit),
				'query_cab'		=> $query_cab,
				'barang' 		=> $kodebarang,
				'nama_barang' 	=> $namabarang,
				'gudang'		=> $query_gud,

			];

			$this->load->view('logistik/v_logistiktampil', $data);
		}
	}
	function excel()
	{
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');

		if (!empty($cek)) {
			$tanggal1 = $this->input->get('tanggal1');
			$tanggal2 = $this->input->get('tanggal2');
			$cabang = $this->input->get('cabang');
			$barang = $this->input->get('barang');
			$gudang = $this->input->get('gudang');
			$kodebarang = $this->input->get('kodebarang');
			$query_cab		= $this->db->query("SELECT * FROM tbl_namers WHERE koders = '$cabang'")->row();
			$query_gud		= $this->db->query("SELECT * FROM tbl_depo WHERE depocode = '$gudang'")->row();
			$namabarang     = data_master('tbl_logbarang', array('kodebarang' => $kodebarang))->namabarang;

			$profile = $this->M_global->_LoadProfileLap();
			$nama_usaha = $profile->nama_usaha;
			$motto = '';
			$alamat = '';
			$namaunit = $this->M_global->_namaunit($unit);

			$_tgl1          = date('Y-m-d', strtotime($tanggal1));
			$_tgl2          = date('Y-m-d', strtotime($tanggal2));
			$_peri          = 'Dari ' . date('d-m-Y', strtotime($tanggal1)) . ' s/d ' . date('d-m-Y', strtotime($tanggal2));
			$_peri1         = 'Per Tgl. ' . date('d', strtotime($tanggal2)) . ' ' . $this->M_global->_namabulan(date('n', strtotime($tanggal2))) . ' ' . date('Y', strtotime($tanggal2)); {
				$bulan          = date('n', strtotime($tanggal1));
				$tahun          = date('Y', strtotime($tanggal2));
				// $query          = "SELECT * FROM tbl_apostocklog
				// WHERE koders = '$cabang' 
				// AND kodebarang = '$barang' 
				// AND gudang = '$gudang'

				// AND tglso > '$_tgl1'";

				$periode_awal = $this->db->get('tbl_periode')->result();
				foreach ($periode_awal as $pa) {
					$query_saldo = "SELECT * from tbl_apostocklog where koders = '$cabang' and kodebarang='$barang' and gudang='$gudang' AND tglso between '$pa->apoperiode' and '$tanggal1'";
				}


				$lap = $this->db->query($query_saldo)->row();
				$_tanggalawal = $_tgl1;

				if ($lap) {
					$_tanggalawal = $lap->tglso;
					$saldo = $lap->saldoakhir;
				} else {
					$_tanggalawal = '';
					$saldo = 0;
				}

				$mutasi =
					"SELECT * from 
		 (select 
		 a.terima_date as tanggal,
		 a.koders,
		 a.terima_no as nomor,
		 a.gudang,
		 b.kodebarang,
		 b.qty_terima as terima,
		 0 keluar,
		 b.qty_terima as qty,
		 b.price as harga,
		 tbl_vendor.vendor_name as rekanan,
		 'PEMBELIAN' as keterangan
		 from tbl_apohterimalog AS a
		 inner join tbl_apodterimalog AS b on a.terima_no=b.terima_no
		 inner join tbl_vendor on a.vendor_id=tbl_vendor.vendor_id
		 
		 union all
		 SELECT 
				tbl_apohreturbelilog.retur_date AS tanggal,
				tbl_apohreturbelilog.koders,
				tbl_apohreturbelilog.terima_no AS nomor,
				tbl_apohreturbelilog.gudang AS gudang,
				tbl_apodreturbelilog.kodebarang,
				0 AS terima,
				tbl_apodreturbelilog.qty_retur AS keluar,
				tbl_apodreturbelilog.qty_retur AS qty,
				tbl_apodreturbelilog.price AS harga,
				tbl_apohreturbelilog.vendor_id AS rekanan,
				'RETUR PEMBELIAN' AS keterangan 
				FROM tbl_apohreturbelilog INNER JOIN tbl_apodreturbelilog
				ON tbl_apohreturbelilog.retur_no = tbl_apodreturbelilog.retur_no 
				
				
				union all
		 
		 select 
		 a.movedate as tanggal,
		 a.koders,
		 a.moveno as nomor,
		 a.ke as gudang,
		 b.kodebarang,
		 b.qtymove as terima,
		 0 as keluar,
		 b.qtymove as qty,
		 b.harga as harga,
		 a.mohonno as rekanan,
		 'MUTASI' as keterangan
		 from tbl_apohmovelog AS a 
		 inner join tbl_apodmovelog AS b on a.moveno=b.moveno

		 union all
		 
		 select 
		 tbl_apohmovelog.movedate as tanggal,
		 tbl_apohmovelog.koders,
		 tbl_apohmovelog.moveno as nomor,
		 tbl_apohmovelog.dari as gudang,
		 tbl_apodmovelog.kodebarang,
		 0 as terima,
		 tbl_apodmovelog.qtymove as keluar,				
		 tbl_apodmovelog.qtymove as qty,
		 tbl_apodmovelog.harga as harga,
		 tbl_apohmovelog.mohonno as rekanan,
		 'MUTASI' as keterangan
		 from tbl_apohmovelog inner join
		 tbl_apodmovelog on tbl_apohmovelog.moveno=tbl_apodmovelog.moveno
		 
		 union all
		 
		 SELECT
		 a.pakaidate AS tanggal,
		 a.koders,
		 a.pakaino AS nomor,
		 a.username AS gudang,
		 b.kodebarang,
		 0 AS terima,
		 b.qty AS keluar,
		 b.qty AS qty,
		 b.harga,
		 '-' AS rekanan,
		 'PEMAKAIAN' AS keterangan
		 FROM tbl_pakaihlog AS a
		 INNER JOIN tbl_pakaidlog AS b
		 ON a.pakaino = b.pakaino
		 ) mutasi
	 
		 where kodebarang = '$kodebarang' and
		 koders = '$cabang' and
		 gudang = '$gudang' and
		 tanggal between '$tanggal1' and '$tanggal2'
		 order by tanggal
		 
		 ";


				$nourut = 1;
				$data = $this->db->query($mutasi)->result();

				$data = [
					'judul' => 'KARTU STOCK LOGISTIK',
					'data' => $data,
					'tanggalwal'	=> $_tanggalawal,
					'saldo' => $saldo,
					'peri1' => $_peri1,
					'kop' => $this->M_cetak->kop($unit),
					'query_cab'		=> $query_cab,
					'barang' 		=> $kodebarang,
					'nama_barang' 	=> $namabarang,
					'gudang'		=> $query_gud,

				];
				$this->load->view('logistik/v_logistik1excel', $data);
			}
		}
	}

	// script original
	// public function cetak()
	// {
	// 	$cek  = $this->session->userdata('level');
	// 	$unit = $this->session->userdata('unit');

	// 	if (!empty($cek)) {
	// 		$profile        = $this->M_global->_LoadProfileLap();

	// 		$nama_usaha     = $profile->nama_usaha;
	// 		$motto          = '';
	// 		$alamat         = '';
	// 		$namaunit       = $this->M_global->_namaunit($unit);

	// 		//$data  = explode("~",$x);

	// 		$barang         = $this->input->get('barang');
	// 		$gudang         = $this->input->get('gudang');
	// 		$cabang         = $this->input->get('cabang');
	// 		$tgl1           = $this->input->get('tgl1');
	// 		$tgl2           = $this->input->get('tgl2');

	// 		$query_cab		= $this->db->query("SELECT * FROM tbl_namers WHERE koders = '$cabang'")->row();
	// 		$query_gud		= $this->db->query("SELECT * FROM tbl_depo WHERE depocode = '$gudang'")->row();

	// 		$namabarang     = data_master('tbl_logbarang', array('kodebarang' => $barang))->namabarang;
	// 		//$namabarang   = '';
	// 		$_tgl1          = date('Y-m-d', strtotime($tgl1));
	// 		$_tgl2          = date('Y-m-d', strtotime($tgl2));
	// 		$_peri          = 'Dari ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2));
	// 		$_peri1         = 'Per Tgl. ' . date('d', strtotime($tgl2)) . ' ' . $this->M_global->_namabulan(date('n', strtotime($tgl2))) . ' ' . date('Y', strtotime($tgl2)); {
	// 			$bulan          = date('n', strtotime($tgl1));
	// 			$tahun          = date('Y', strtotime($tgl2));
	// 			$query          = "SELECT * FROM tbl_apostocklog
	//               WHERE koders = '$cabang' 
	//               AND kodebarang = '$barang' 
	//               AND gudang = '$gudang'
	//               -- AND tglso BETWEEN '$tgl1' AND '$tgl2'
	//               AND tglso > '$_tgl1'";


	// 			// $query_saldo = "SELECT * from tbl_apostocklog where koders = '$cabang' and kodebarang='$barang' and gudang='$gudang' OR gudang = 'LOKAL' AND tglso >'$_tgl1' ";



	// 			// $lap = $this->db->query($query_saldo)->row();
	// 			// if ($lap == true) {
	// 			// 	$sql = $this->db->query("SELECT sum(d.qty) as qty FROM tbl_pakaidlog d JOIN tbl_pakaihlog h ON d.pakaino=h.pakaino WHERE h.gudang = '$lap->gudang' and h.koders = '$lap->koders' and d.kodebarang = '$lap->kodebarang' AND h.pakaidate >= '$_tgl1' order by d.id limit 1")->row();
	// 			// }
	// 			// $_tanggalawal = $tgl1;

	// 			// if ($lap) {
	// 			// 	$_tanggalawal = $lap->tglso;
	// 			// 	$saldo = $lap->saldoawal;
	// 			// } else {
	// 			// 	$_tanggalawal = '';
	// 			// 	$saldo = 0;
	// 			// }
	// 			$periode_awal = $this->db->get('tbl_periode')->result();
	// 			foreach ($periode_awal as $pa) {
	// 				$tgl1 = $pa->apoperiode;
	// 				$tgl2 = $_tgl1;
	// 				$coba = $this->M_KartuStock->cekstok_log($cabang, $barang, $gudang, $tgl1, $tgl2);
	// 				if ($coba) {
	// 					$_tanggalawal = $pa->apoperiode;
	// 					// $terima = 0;
	// 					// $keluar = 0;
	// 					foreach ($coba as $c) {
	// 						$saldo = $c->total;
	// 					}
	// 				} else {
	// 					$_tanggalawal = '';
	// 					$saldo = 0;
	// 				}
	// 				// $query_saldo = "SELECT * from tbl_apostocklog where koders = '$cabang' and kodebarang='$barang' and gudang='$gudang' AND tglso < '$tgl1'";
	// 				// $lap = $this->db->query($query_saldo)->row();
	// 				// $_tanggalawal = $tgl1;
	// 				// if ($lap) {
	// 				// 	$_tanggalawal = $lap->periodedate;
	// 				// 	// $saldo = $lap->saldoakhir;
	// 				// 	$saldo = (int)$lap->hasilso + ((int)$lap->sesuai);
	// 				// } else {
	// 				// 	$_tanggalawal = '';
	// 				// 	$saldo = 0;
	// 				// }
	// 			}
	// 			$pdf = new simkeu_rpt();
	// 			$pdf->setID($nama_usaha, $motto, $alamat);
	// 			$pdf->setunit($namaunit);
	// 			$pdf->setjudul('KARTU STOCK');
	// 			$pdf->setsubjudul($_peri1);
	// 			$pdf->addpage("L", "A4");
	// 			$pdf->setsize("L", "A4");

	// 			$pdf->SetWidths(array(30, 5, 100));
	// 			$pdf->SetAligns(array('L', 'C', 'L'));
	// 			$border = array('', '', '');

	// 			$judul = array('Cabang', ':', str_replace("KLINIK ESTETIKA dr. Affandi ", "", $query_cab->namars));
	// 			$pdf->FancyRow($judul, $border);

	// 			$judul = array('Gudang', ':', $query_gud->keterangan);
	// 			$pdf->FancyRow($judul, $border);

	// 			$judul = array('Kode Barang', ':', $barang);
	// 			$pdf->FancyRow($judul, $border);

	// 			$judul = array('Nama Barang', ':', $namabarang);
	// 			$pdf->FancyRow($judul, $border);

	// 			$pdf->ln();

	// 			$pdf->SetWidths(array(40, 20, 30, 30, 30, 20, 20, 20, 30, 30));
	// 			$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
	// 			$judul = array('No. Bukti', 'Tanggal', 'Keterangan', 'Rekanan', 'Nilai Pembelian', 'Terima', 'Keluar', 'Saldo Akhir', 'Nilai Persediaan', 'Total Nilai Persediaan');
	// 			$pdf->setfont('Times', 'B', 10);
	// 			$pdf->row($judul);

	// 			$pdf->SetWidths(array(40, 20, 30, 30, 30, 20, 20, 20, 30, 30));
	// 			$pdf->SetAligns(array('C', 'C', 'L', 'L', 'R', 'R', 'R', 'R', 'R', 'R'));
	// 			$pdf->setfont('Times', '', 9);
	// 			$pdf->SetFillColor(224, 235, 255);
	// 			$pdf->SetTextColor(0);
	// 			$pdf->SetFont('');
	// 			$pdf->row(array(
	// 				'SALDO',
	// 				date('d-m-Y', strtotime($_tanggalawal)),
	// 				'SALDO AWAL',
	// 				'',
	// 				number_format(0, 0, '.', ','),
	// 				number_format(0, 0, '.', ','),
	// 				number_format(0, 0, '.', ','),
	// 				number_format($saldo, 0, '.', ','),
	// 				number_format(0, 0, '.', ','),
	// 				number_format(0, 0, '.', ',')
	// 			));
	// 			$pdf->ln();

	// 			$mutasi =
	// 			"SELECT * from 
	//  (select 
	//  a.terima_date as tanggal,
	//  a.koders,
	//  a.terima_no as nomor,
	//  a.gudang,
	//  b.kodebarang,
	//  b.qty_terima as terima,
	//  0 keluar,
	//  b.qty_terima as qty,
	//  b.price as harga,
	//  tbl_vendor.vendor_name as rekanan,
	//  'PEMBELIAN' as keterangan,
	//  (select hpp from tbl_logbarang where kodebarang=b.kodebarang) as hpp,
	//  (b.qty_terima*(select hpp from tbl_logbarang where kodebarang=b.kodebarang)) as totalhpp
	//  from tbl_apohterimalog AS a
	//  inner join tbl_apodterimalog AS b on a.terima_no=b.terima_no
	//  inner join tbl_vendor on a.vendor_id=tbl_vendor.vendor_id

	//  union all

	//  SELECT 
	// tbl_apohreturbelilog.retur_date AS tanggal,
	// tbl_apohreturbelilog.koders,
	// tbl_apohreturbelilog.terima_no AS nomor,
	// tbl_apohreturbelilog.gudang AS gudang,
	// tbl_apodreturbelilog.kodebarang,
	// 0 AS terima,
	// tbl_apodreturbelilog.qty_retur AS keluar,
	// tbl_apodreturbelilog.qty_retur AS qty,
	// tbl_apodreturbelilog.price AS harga,
	// tbl_apohreturbelilog.vendor_id AS rekanan,
	// 'RETUR PEMBELIAN' AS keterangan,
	// (select hpp from tbl_logbarang where kodebarang=tbl_apodreturbelilog.kodebarang) as hpp,
	// (tbl_apodreturbelilog.qty_retur*(select hpp from tbl_logbarang where kodebarang=tbl_apodreturbelilog.kodebarang)) as totalhpp
	// FROM tbl_apohreturbelilog INNER JOIN tbl_apodreturbelilog
	// ON tbl_apohreturbelilog.retur_no = tbl_apodreturbelilog.retur_no 

	// union all

	// select 
	// tbl_apohresep.tglresep as tanggal,
	// tbl_apohresep.koders,
	// tbl_apohresep.resepno as nomor,
	// tbl_apohresep.gudang as gudang,
	// tbl_apodresep.kodebarang,
	// 0 as terima,
	// tbl_apodresep.qty as keluar,
	// tbl_apodresep.qty as qty,
	// tbl_apodresep.hpp as harga,
	// (select namapas from tbl_pasien where rekmed = tbl_apohresep.rekmed) as rekanan,
	// 'PENJUALAN' as keterangan,
	// (Select hpp from tbl_logbarang where kodebarang=tbl_apodresep.kodebarang) as hpp,
	// (tbl_apodresep.qty*(Select hpp from tbl_logbarang where kodebarang=tbl_apodresep.kodebarang)) as totalhpp
	// from tbl_apohresep inner join
	// tbl_apodresep on tbl_apohresep.resepno=tbl_apodresep.resepno

	// union all

	// SELECT
	// tbl_apohresep.tglresep as tanggal,
	// tbl_apohresep.koders,
	// tbl_apohresep.resepno as nomor,
	// tbl_apohresep.gudang as gudang,
	// tbl_apodresep.kodebarang,
	// tbl_apodresep.qty as terima,
	// 0 as keluar,
	// tbl_apodresep.qty as qty,
	// tbl_apodresep.price as harga,
	// tbl_apohresep.pro as rekanan,
	// 'RETUR PENJUALAN' as keterangan,
	// (select hpp from tbl_logbarang where kodebarang=tbl_apodresep.kodebarang) as hpp,
	// (tbl_apodresep.qty*(select hpp from tbl_logbarang where kodebarang=tbl_apodresep.kodebarang)) as totalhpp
	// FROM tbl_apohresep INNER JOIN tbl_apodresep
	// ON tbl_apohresep.resepno = tbl_apodresep.resepno

	// union all

	// SELECT 
	// tbl_pakaihlog.pakaidate AS tanggal,
	// tbl_pakaihlog.koders,
	// tbl_pakaihlog.pakaino AS nomor,
	// tbl_pakaihlog.gudang AS gudang,
	// tbl_pakaidlog.kodebarang,
	// 0 AS terima,
	// tbl_pakaidlog.qty AS keluar,
	// 0 AS qty,
	// tbl_pakaidlog.harga AS harga,
	// tbl_pakaihlog.keterangan AS rekanan,
	// 'PEMAKAIAN LOG' AS keterangan,
	//  (select hpp from tbl_logbarang where kodebarang=tbl_pakaidlog.kodebarang) as hpp,
	//  (tbl_pakaidlog.qty*(select hpp from tbl_logbarang where kodebarang=tbl_pakaidlog.kodebarang)) as totalhpp
	// FROM tbl_pakaihlog INNER JOIN tbl_pakaidlog
	// ON tbl_pakaihlog.pakaino = tbl_pakaidlog.pakaino

	// union all

	//  select 
	//  a.movedate as tanggal,
	//  a.koders,
	//  a.moveno as nomor,
	//  a.ke as gudang,
	//  b.kodebarang,
	//  b.qtymove as terima,
	//  0 as keluar,
	//  b.qtymove as qty,
	//  b.harga as harga,
	//  a.mohonno as rekanan,
	//  'MUTASI' as keterangan,
	//  (select hpp from tbl_logbarang where kodebarang=b.kodebarang) as hpp,
	//  (b.qtymove*(select hpp from tbl_logbarang where kodebarang=b.kodebarang)) as totalhpp
	//  from tbl_apohmovelog AS a 
	//  inner join tbl_apodmovelog AS b on a.moveno=b.moveno

	//  union all

	//  select 
	//  tbl_apohmovelog.movedate as tanggal,
	//  tbl_apohmovelog.koders,
	//  tbl_apohmovelog.moveno as nomor,
	//  tbl_apohmovelog.dari as gudang,
	//  tbl_apodmovelog.kodebarang,
	//  0 as terima,
	//  tbl_apodmovelog.qtymove as keluar,				
	//  tbl_apodmovelog.qtymove as qty,
	//  tbl_apodmovelog.harga as harga,
	//  tbl_apohmovelog.mohonno as rekanan,
	//  'MUTASI' as keterangan,
	//  (select hpp from tbl_logbarang where kodebarang=tbl_apodmovelog.kodebarang) as hpp,
	//  (tbl_apodmovelog.qtymove*(select hpp from tbl_logbarang where kodebarang=tbl_apodmovelog.kodebarang)) as totalhpp
	//  from tbl_apohmovelog inner join
	//  tbl_apodmovelog on tbl_apohmovelog.moveno=tbl_apodmovelog.moveno

	// union all

	// SELECT 
	// tbl_aposesuailog.tglso AS tanggal,
	// tbl_aposesuailog.koders,
	// '-' AS nomor,
	// tbl_aposesuailog.gudang AS gudang,
	// tbl_aposesuailog.kodeobat as kodebarang,
	// tbl_aposesuailog.sesuai AS terima,
	// 0 AS keluar,
	// tbl_aposesuailog.sesuai AS qty,
	// tbl_aposesuailog.hpp AS harga,
	// tbl_aposesuailog.type AS rekanan,
	// CONCAT(
	// ' ', tbl_aposesuailog.type ,' [ ', tbl_aposesuailog.saldo ,' - ', tbl_aposesuailog.hasilso ,' ] 
	// ') AS keterangan,
	// (Select hpp from tbl_logbarang where kodebarang=tbl_aposesuailog.kodeobat) as hpp,
	// (tbl_aposesuailog.sesuai*(Select hpp from tbl_logbarang where kodebarang=tbl_aposesuailog.kodeobat)) as totalhpp
	// FROM tbl_aposesuailog
	// where approve = 1
	//  ) mutasi

	//  where kodebarang = '$barang' and
	//  koders = '$cabang' and
	//  gudang = '$gudang' and
	//  tanggal between '$_tgl1' and '$_tgl2'
	// 	 ";

	// 			//    	"SELECT * from 
	// 			//     (select 
	// 			// 	a.terima_date as tanggal,
	// 			// 	a.koders,
	// 			// 	a.terima_no as nomor,
	// 			// 	a.gudang,
	// 			// 	b.kodebarang,
	// 			// 	b.qty_terima as terima,
	// 			// 	0 keluar,
	// 			// 	b.qty_terima as qty,
	// 			// 	b.price as harga,
	// 			// 	tbl_vendor.vendor_name as rekanan,
	// 			// 	'PEMBELIAN' as keterangan
	// 			// 	from tbl_apohterimalog AS a
	// 			// 	inner join tbl_apodterimalog AS b on a.terima_no=b.terima_no
	// 			// 	inner join tbl_vendor on a.vendor_id=tbl_vendor.vendor_id

	// 			// 	union all

	// 			// 	SELECT 
	// 			// 	tbl_apohreturbelilog.retur_date AS tanggal,
	// 			// 	tbl_apohreturbelilog.koders,
	// 			// 	tbl_apohreturbelilog.terima_no AS nomor,
	// 			// 	tbl_apohreturbelilog.gudang AS gudang,
	// 			// 	tbl_apodreturbelilog.kodebarang,
	// 			// 	0 AS terima,
	// 			// 	tbl_apodreturbelilog.qty_retur AS keluar,
	// 			// 	tbl_apodreturbelilog.qty_retur AS qty,
	// 			// 	tbl_apodreturbelilog.price AS harga,
	// 			// 	tbl_apohreturbelilog.vendor_id AS rekanan,
	// 			// 	'RETUR PEMBELIAN' AS keterangan 
	// 			// 	FROM tbl_apohreturbelilog INNER JOIN tbl_apodreturbelilog
	// 			// 	ON tbl_apohreturbelilog.retur_no = tbl_apodreturbelilog.retur_no 


	// 			// 	union all

	// 			// 	select 
	// 			// 	a.movedate as tanggal,
	// 			// 	a.koders,
	// 			// 	a.moveno as nomor,
	// 			// 	a.ke as gudang,
	// 			// 	b.kodebarang,
	// 			// 	b.qtymove as terima,
	// 			// 	0 as keluar,
	// 			// 	b.qtymove as qty,
	// 			// 	b.harga as harga,
	// 			// 	a.mohonno as rekanan,
	// 			// 	'MUTASI' as keterangan
	// 			// 	from tbl_apohmovelog AS a 
	// 			// 	inner join tbl_apodmovelog AS b on a.moveno=b.moveno

	// 			// 	union all



	// 			// 	select 
	// 			// 	tbl_apohmovelog.movedate as tanggal,
	// 			// 	tbl_apohmovelog.koders,
	// 			// 	tbl_apohmovelog.moveno as nomor,
	// 			// 	tbl_apohmovelog.dari as gudang,
	// 			// 	tbl_apodmovelog.kodebarang,
	// 			// 	0 as terima,
	// 			// 	tbl_apodmovelog.qtymove as keluar,				
	// 			// 	tbl_apodmovelog.qtymove as qty,
	// 			// 	tbl_apodmovelog.harga as harga,
	// 			// 	tbl_apohmovelog.mohonno as rekanan,
	// 			// 	'MUTASI' as keterangan
	// 			// 	from tbl_apohmovelog inner join
	// 			// 	tbl_apodmovelog on tbl_apohmovelog.moveno=tbl_apodmovelog.moveno

	// 			// 	union all

	// 			// 	SELECT
	// 			// 	a.pakaidate AS tanggal,
	// 			// 	a.koders,
	// 			// 	a.pakaino AS nomor,
	// 			// 	a.username AS gudang,
	// 			// 	b.kodebarang,
	// 			// 	0 AS terima,
	// 			// 	b.qty AS keluar,
	// 			// 	b.qty AS qty,
	// 			// 	b.harga,
	// 			// 	'-' AS rekanan,
	// 			// 	'PEMAKAIAN' AS keterangan
	// 			// 	FROM tbl_pakaihlog AS a
	// 			// 	INNER JOIN tbl_pakaidlog AS b
	// 			// 	ON a.pakaino = b.pakaino
	// 			// 	) mutasi

	// 			// 	where kodebarang = '$barang' and
	// 			// 	koders = '$cabang' and
	// 			// 	gudang = '$gudang' and
	// 			// 	tanggal between '$_tgl1' and '$_tgl2'
	// 			// 	order by tanggal ASC, keterangan

	// 			// ";

	// 			$nourut = 1;

	// 			$lap = $this->db->query($mutasi)->result();
	// 			foreach ($lap as $db) {
	// 				$saldo = $saldo + $db->terima - $db->keluar;

	// 				$nilai = $db->qty * $db->harga;
	// 				$pdf->row(array(
	// 					$db->nomor,
	// 					date('d-m-Y', strtotime($db->tanggal)),
	// 					$db->keterangan,
	// 					$db->rekanan,
	// 					number_format($nilai, 0, '.', ','),
	// 					number_format($db->terima, 0, '.', ','),
	// 					number_format($db->keluar, 0, '.', ','),
	// 					number_format($saldo, 0, '.', ','),
	// 					number_format($db->hpp, 2, '.', ','),
	// 					number_format($db->totalhpp, 2, '.', ',')

	// 				));

	// 				$nourut++;
	// 			}

	// 			$pdf->SetTitle('KARTU STOCK ');
	// 			$pdf->AliasNbPages();
	// 			$pdf->output('FARMASI_KARTUSTOCK.PDF', 'I');
	// 		}
	// 	} else {

	// 		header('location:' . base_url());
	// 	}
	// }

	// husain chnage
	public function cetak2()
	{
		$cek    = $this->session->userdata('level');
		$cabang = $this->session->userdata('unit');
		$barang = $this->input->get('barang');
		$gudang = $this->input->get('gudang');
		$dari   = $this->input->get('tgl1');
		$sampai = $this->input->get('tgl2');
		$avatar = $this->session->userdata('avatar_cabang');
		if (!empty($cek)) {
			$kop           = $this->M_cetak->kop($cabang);
			$profile       = data_master('tbl_namers', array('koders' => $cabang));
			$datagudang    = $this->db->get_where("tbl_depo", ['depocode' => $gudang])->row();
			$databarang    = $this->db->get_where("tbl_logbarang", ['kodebarang' => $barang])->row();
			$datars        = $this->db->get_where("tbl_namers", ['koders' => $cabang])->row();
			$namars        = $kop['namars'];
			$alamat        = $kop['alamat'];
			$alamat2       = $kop['alamat2'];
			$alamat3       = $profile->kota;
			$kota          = $kop['kota'];
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
								<tr>
									<td width=\"15%\" style=\"text-align:center; font-size:11px;\">Dari tgl " . date("d-m-Y", strtotime($dari)) . " Sampai tgl " . date("d-m-Y",
				strtotime($sampai)
			) . "</td>
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
			<thead>
				<tr>
					<th width=\"20%\">No. Bukti</th>
					<th width=\"10%\">Tanggal</th>
					<th width=\"10%\">Keterangan</th>
					<th width=\"15%\">Rekanan</th>
					<th width=\"10%\">Nilai Pembelian</th>
					<th width=\"5%\">Terima</th>
					<th width=\"5%\">Keluar</th>
					<th width=\"5%\">Saldo Akhir</th>
					<th width=\"10%\">Nilai Persediaan</th>
					<th width=\"10%\">Total Nilai Persediaan</th>
				</tr> 
			</thead>";
			$date1 = str_replace('-', '/', $dari);
			$tomorrow = date('Y-m-d', strtotime($date1 . "-1 days"));
			$coba       = $this->M_KartuStock->cekstoklog($cabang, $barang, $gudang, $dari);
			if ($coba) {
				$_tanggalawal = $tomorrow;
				$saldo = 0;
				foreach ($coba as $c) {
					$saldo += $c->terima - $c->keluar;
					$jam = $c->jam;
				}
			} else {
				$_tanggalawal    = $dari;
				$saldo           = 0;
				$jam          = date("H:i:s");
			}
			$chari .= "<tr>
					<td width=\"20%\">SALDO</td>
					<td width=\"10%\" style=\"text-align: center;\">" . date("d-m-Y", strtotime($_tanggalawal)) ."</td>
					<td width=\"10%\">SALDO AWAL</td>
					<td width=\"15%\">SALDO AWAL</td>
					<td width=\"10%\"></td>
					<td width=\"5%\" style=\"text-align:right;\">0</td>
					<td width=\"5%\" style=\"text-align:right;\">0</td>
					<td width=\"5%\" style=\"text-align:right;\">" . number_format($saldo) . "</td>
					<td width=\"10%\" style=\"text-align:right;\">0</td>
					<td width=\"10%\" style=\"text-align:right;\">0</td>
				</tr>
				<tr>
					<td colspan=\"10\">&nbsp;</td>
				</tr>";
			$queryx    = $this->M_KartuStock->tgllog($cabang, $barang, $gudang, $dari, $sampai);
			$no        = 1;
			$hgr       = 0;
			foreach ($queryx as $db) {
				$rownya = $no++;
				$hgr += $db->harga;
			}
			foreach ($queryx as $db) {
				$nilai = $db->qty * $db->harga;
				$saldo = $saldo + $db->terima - $db->keluar;
				$chari .= "<tr>
						<td width=\"20%\">$db->nomor</td>
						<td width=\"10%\" style=\"text-align: center;\">" . date("d-m-Y", strtotime($db->tanggal)) . "</td>
						<td width=\"10%\">$db->keterangan</td>
						<td width=\"15%\">$db->rekanan</td>
						<td width=\"10%\" style=\"text-align:right;\">" . number_format($nilai) . "</td>
						<td width=\"5%\" style=\"text-align:right;\">" . number_format($db->terima) . "</td>
						<td width=\"5%\" style=\"text-align:right;\">" . number_format($db->keluar) . "</td>
						<td width=\"5%\" style=\"text-align:right;\">" . number_format($saldo) . "</td>
						<td width=\"10%\" style=\"text-align:right;\">" . number_format($db->hpp) . "</td>
						<td width=\"10%\" style=\"text-align:right;\">" . number_format($db->totalhpp) . "</td>
					</tr>";
			}
			$chari .= "		</table>";
			$data['prev'] = $chari;
			$judul = "KARTU STOK CABANG $datars->kota";
			echo ("<title>$judul</title>");
			// echo $chari;
			$this->M_cetak->mpdf('L', 'A4', $judul, $chari, '.PDF', 10, 10, 10, 2);
		} else {
			header('location:' . base_url());
		}
	}
	// end husain

	public function cekbarang(){
		$cabang = $this->session->userdata("unit");
		$gudang = $this->input->get("gudang");
		$kodebarang = $this->input->get("kodebarang");
		$data = $this->db->get_where("tbl_apostocklog", ["koders"=>$cabang, "gudang"=>$gudang, "kodebarang"=>$kodebarang])->num_rows();
		if($data > 0){
			echo json_encode(["status" => 1]);
		} else {
			echo json_encode(["status" => 0]);
		}
	}

	public function export()
	{
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$page = $this->uri->segment(3);
			$limit = $this->config->item('limit_data');
			$d['master_bank'] = $this->db->get("ms_bank");
			$d['nama_usaha'] = $this->config->item('nama_perusahaan');
			$d['alamat'] = $this->config->item('alamat_perusahaan');
			$d['motto'] = $this->config->item('motto');

			$this->load->view('master/bank/v_master_bank_exp', $d);
		} else {

			header('location:' . base_url());
		}
	}
}

/* End of file akuntansi_jurnal.php */
/* Location: ./application/controllers/akuntansi_jurnal.php */