<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kasir_laporan extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('M_global', 'M_global');
		$this->load->model('M_cetak');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '2000');
		$this->session->set_userdata('submenuapp', '2308');
		$this->load->model('M_template_cetak');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$unit = $this->session->userdata('unit');
			$this->load->helper('url');
			$d['sh'] = $this->db->query("SELECT *FROM tbl_setinghms WHERE lset='SHF' ")->result();
			$d['cabang'] = $this->db->get('tbl_namers')->result();

			$this->load->view('klinik/v_kasir_lap', $d);
		} else {
			header('location:' . base_url());
		}
	}

	public function namars($kode){
		$data = $this->db->get_where("tbl_namers", ["koders"=>$kode])->row();
		echo json_encode($data);
	}

	public function cetak()
	{
		ini_set('memory_limit', '-1');

		$cek  = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');
		if (!empty($cek)) {
			$profile = $this->M_global->_LoadProfileLap();
			$nama_usaha = $profile->nama_usaha;
			$motto = '';
			$alamat = '';
			$namaunit = $this->M_global->_namaunit($unit);

			$idlp  = $this->input->get('idlap');
			$tgl1  = $this->input->get('tgl1');
			$tgl2  = $this->input->get('tgl2');
			$dokter = $this->input->get('dokter');
			$cab   = $this->input->get('cabang');
			//$unit  = $this->input->get('unit');

			$_tgl1 = date('Y-m-d', strtotime($tgl1));
			$_tgl2 = date('Y-m-d', strtotime($tgl2));
			$_peri = 'Dari ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2));
			//$_peri1= 'Dari . '.date('d',strtotime($tgl2)).' sampai '.$this->M_global->_namabulan(date('n',strtotime($tgl2))).' '.date('Y',strtotime($tgl2));

			if ($idlp == 101) {
				$bulan = date('n', strtotime($tgl1));
				$tahun = date('Y', strtotime($tgl2));
				$query =
				"SELECT tbl_kasir.*, tbl_pasien.* 
				from tbl_kasir 
				inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
				where tglbayar between '$_tgl1' and '$_tgl2' and tbl_kasir.koders='$unit'
				";

				$query .= "ORDER BY tbl_kasir.tglbayar";

				$lap = $this->db->query($query)->result();
				$pdf = new simkeu_rpt();
				$pdf->setID($nama_usaha, $motto, $alamat);
				$pdf->setunit($namaunit);
				$pdf->setjudul('01 LAPORAN HARIAN KASIR PENDAPATAN PER SHIFT ');
				$pdf->setsubjudul($_peri);
				$pdf->addpage("L", "A3");
				$pdf->setsize("L", "A3");
				$pdf->SetWidths(array(10, 22, 20, 30, 15, 15, 15, 15, 15, 15, 15, 15, 15, 20, 20, 20, 20, 20, 20, 20, 20, 20));
				$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
				$judul = array('No.', 'Kwitansi', 'Tangal', 'Nama Pasien', 'Adm', 'U.Muka', 'J.Kulit', 'Resep', 'R/Label', 'J.Gigi', 'Lain-lain', 'O.Kirim', 'Ongkir', 'J.Spa', 'R/Spa', 'Apotek', 'Total', 'Selisih', 'Diskon', 'Voucher', 'Total Net', '');
				$pdf->setfont('Arial', 'B', 8);
				$pdf->row($judul);

				$pdf->SetWidths(array(10, 22, 20, 30, 15, 15, 15, 15, 15, 15, 15, 15, 15, 20, 20, 20, 20, 20, 20, 20, 20, 20));
				$pdf->SetAligns(array('C', 'C', 'C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
				$pdf->setfont('Arial', '', 8);
				$pdf->SetFillColor(224, 235, 255);
				$pdf->SetTextColor(0);
				$pdf->SetFont('');

				$nourut = 1;

				foreach ($lap as $db) {
					$noreg = $db->noreg;

					$umuka = $db->umuka == 0 ?  $db->uangmuka : "-" . $db->umuka;  //saya nambah ini
					$pdf->row(array(
						$nourut,
						$db->nokwitansi,
						tanggal($db->tglbayar),
						$db->namapas,
						angka_rp($db->adm, 0),
						//   angka_rp($db->umuka,0), saya ganti
						angka_rp($umuka, 0),
						angka_rp($db->totalpoli, 0),
						angka_rp($db->totalresep, 0),
						angka_rp(0, 0),
						angka_rp($db->lain, 0),
						angka_rp($db->lain, 0),
						angka_rp($db->lain, 0),
						angka_rp($db->lain, 0),
						angka_rp($db->lain, 0),
						angka_rp($db->lain, 0),
						angka_rp($db->lain, 0),
						//   angka_rp($db->totalsemua,0),  saya ganti
						angka_rp($db->totalpoli - $db->umuka, 0),
						angka_rp($db->selisihrp, 0),
						angka_rp($db->diskonrp, 0),
						angka_rp($db->voucherrp1 + $db->voucherrp2 + $db->voucherrp3, 0),
						//   angka_rp($db->totalsemua-$db->selisihrp-$db->diskonrp-$db->voucherrp1,0),  saya ganti
						angka_rp($db->totalpoli - $db->umuka - $db->selisihrp - $db->diskonrp - $db->voucherrp1 - $db->voucherrp2 - $db->voucherrp3 - $db->totalresep, 0),
						'',
					));

					$nourut++;
				}

				$pdf->SetTitle('01 LAPORAN HARIAN KASIR PENDAFTARAN PER SHIFT');
				$pdf->AliasNbPages();
				$pdf->output('KASIR-01.PDF', 'I');
			} else if ($idlp == 102) {
				$bulan = date('n', strtotime($tgl1));
				$tahun = date('Y', strtotime($tgl2));
				// 	$query =
				// 		"SELECT 'Order Tunai' as keterangan, count(*) as jumlah, sum(bayarcash+bayarcard) as nilai
				// from tbl_kasir 
				// where jenisbayar=1 and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalresep>0
				// union all
				// SELECT '   Order Cash' as keterangan, count(*) as jumlah, sum(bayarcash) as nilai
				// from tbl_kasir where jenisbayar=1
				// and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalresep>0
				// union all
				// SELECT '   Order Card' as keterangan, count(*) as jumlah, sum(bayarcard) as nilai
				// from tbl_kasir where jenisbayar=1
				// and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalresep>0
				// union all
				// SELECT 'Tindakan Dokter' as keterangan, count(*) as jumlah, sum(totalpoli) as nilai
				// from tbl_kasir where jenisbayar=1
				// and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
				// union all
				// SELECT '   Order Cash' as keterangan, count(*) as jumlah, sum(bayarcash) as nilai
				// from tbl_kasir where jenisbayar=1
				// and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
				// union all
				// SELECT '   Order Card' as keterangan, count(*) as jumlah, sum(bayarcard) as nilai
				// from tbl_kasir where jenisbayar=1
				// and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
				// union all
				// SELECT 'Order Lokal' as keterangan, 0 as jumlah, 0 as nilai
				// from tbl_kasir where jenisbayar=1
				// and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
				// union all
				// SELECT '   Order Cash' as keterangan, 0 as jumlah, 0 as nilai
				// from tbl_kasir where jenisbayar=1
				// and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
				// union all
				// SELECT '   Order Card' as keterangan, 0 as jumlah, 0 as nilai
				// from tbl_kasir where jenisbayar=1
				// and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
				// union all
				// SELECT 'Order Kirim' as keterangan, 0 as jumlah, 0 as nilai
				// from tbl_kasir where jenisbayar=1
				// and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
				// union all
				// SELECT '   Order Cash' as keterangan, 0 as jumlah, 0 as nilai
				// from tbl_kasir where jenisbayar=1
				// and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
				// union all
				// SELECT '   Order Card' as keterangan, 0 as jumlah, 0 as nilai
				// from tbl_kasir where jenisbayar=1
				// and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
				// union all
				// SELECT 'Ongkos Kirim' as keterangan, 0 as jumlah, 0 as nilai
				// from tbl_kasir where jenisbayar=1
				// and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
				// union all
				// SELECT '   Order Cash' as keterangan, 0 as jumlah, 0 as nilai
				// from tbl_kasir where jenisbayar=1
				// and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
				// union all
				// SELECT '   Order Card' as keterangan, 0 as jumlah, 0 as nilai
				// from tbl_kasir where jenisbayar=1
				// and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
				// union all
				// SELECT 'SPA' as keterangan, 0 as jumlah, 0 as nilai
				// from tbl_kasir where jenisbayar=1
				// and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
				// union all
				// SELECT '   Order Cash' as keterangan, 0 as jumlah, 0 as nilai
				// from tbl_kasir where jenisbayar=1
				// and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
				// union all
				// SELECT '   Order Card' as keterangan, 0 as jumlah, 0 as nilai
				// from tbl_kasir where jenisbayar=1
				// and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
				// union all
				// SELECT 'Produk SPA' as keterangan, 0 as jumlah, 0 as nilai
				// from tbl_kasir where jenisbayar=1
				// and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
				// union all
				// SELECT '   Order Cash' as keterangan, 0 as jumlah, 0 as nilai
				// from tbl_kasir where jenisbayar=1
				// and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
				// union all
				// SELECT '   Order Card' as keterangan, 0 as jumlah, 0 as nilai
				// from tbl_kasir where jenisbayar=1
				// and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
				// union all
				// select 'APOTIK' as keterangan, 0 as jumlah, 0 as nilai
				// from tbl_kasir where jenisbayar=1
				// and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
				// union all
				// select '   Order Cash' as keterangan, 0 as jumlah, 0 as nilai
				// from tbl_kasir where jenisbayar=1
				// and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
				// union all
				// select '   Order Card' as keterangan, 0 as jumlah, 0 as nilai
				// from tbl_kasir where jenisbayar=1
				// and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
				// ";
				
				// $query =
				// 	"SELECT 'RAWAT JALAN' as keterangan, sum(jumlah) as jumlah, sum(nilai) as nilai FROM (
				// 		SELECT * FROM (
				// 			SELECT COUNT(*) AS jumlah, SUM(bayarcash) AS nilai
				// 			FROM tbl_kasir 
				// 			JOIN tbl_apohresep ON tbl_kasir.noreg = tbl_apohresep.noreg
				// 			WHERE jenisbayar = 1 AND jenispas=8 AND tbl_kasir.koders='$unit' AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2' AND bayarcash > 0
				// 		) AS cash
				// 		UNION ALL
				// 		SELECT * FROM (
				// 			SELECT COUNT(*) AS jumlah, SUM(bayarcard) AS nilai
				// 			FROM tbl_kasir 
				// 			JOIN tbl_apohresep ON tbl_kasir.noreg = tbl_apohresep.noreg
				// 			WHERE jenisbayar=1 AND jenispas=8 AND tbl_kasir.koders='$unit' AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2' AND bayarcard > 0 AND tbl_kasir.nokwitansi IN (SELECT nokwitansi FROM tbl_kartukredit)
				// 		) AS card
				// 		UNION ALL
				// 		SELECT * FROM (
				// 			SELECT COUNT(*) AS jumlah, SUM(jumlahhutang) AS nilai
				// 			FROM tbl_kasir 
				// 			JOIN tbl_pap ON tbl_kasir.noreg = tbl_pap.noreg
				// 			JOIN tbl_apohresep ON tbl_apohresep.noreg=tbl_kasir.noreg
				// 			WHERE jenisbayar=1 and jenispas=8 AND tbl_kasir.koders='$unit' 
				// 			AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2'
				// 		) as penjamin
				// 	) as order_tunai

				// 	union all

				// 	SELECT '   Order Cash' as keterangan, count(*) as jumlah, sum(bayarcash) as nilai
				// 	from tbl_kasir 
				// 	JOIN tbl_apohresep ON tbl_kasir.noreg = tbl_apohresep.noreg
				// 	where jenisbayar=1 AND jenispas=8 AND tbl_kasir.koders='$unit' AND tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2' AND bayarcash > 0

				// 	union all

				// 	SELECT '   Order Card' as keterangan, count(*) as jumlah, sum(bayarcard) as nilai
				// 	from tbl_kasir 
				// 	JOIN tbl_apohresep ON tbl_kasir.noreg = tbl_apohresep.noreg
				// 	where jenisbayar=1 AND jenispas=8 AND tbl_kasir.koders='$unit' AND tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2' and bayarcard > 0 and tbl_kasir.nokwitansi in (select nokwitansi from tbl_kartukredit)

				// 	union all

				// 	SELECT '   Penjamin' AS keterangan, COUNT(*) AS jumlah, SUM(jumlahhutang) AS nilai
				// 	FROM tbl_kasir 
				// 	JOIN tbl_pap ON tbl_kasir.noreg = tbl_pap.noreg
				// 	JOIN tbl_apohresep ON tbl_apohresep.noreg=tbl_kasir.noreg
				// 	WHERE jenisbayar=1 and jenispas=8 AND tbl_kasir.koders='$unit' 
				// 	AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2'

				// 	UNION ALL

				// 	SELECT 'RAWAT INAP' as keterangan, sum(jumlah) as jumlah, sum(nilai) as nilai FROM (
				// 		SELECT * FROM (
				// 			SELECT COUNT(*) AS jumlah, SUM(bayarcash) AS nilai
				// 			FROM tbl_kasir 
				// 			JOIN tbl_apohresep ON tbl_kasir.noreg = tbl_apohresep.noreg
				// 			WHERE jenisbayar = 1 AND jenispas=9 AND tbl_kasir.koders='$unit' AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2' AND bayarcash > 0
				// 		) AS cash
				// 		UNION ALL
				// 		SELECT * FROM (
				// 			SELECT COUNT(*) AS jumlah, SUM(bayarcard) AS nilai
				// 			FROM tbl_kasir 
				// 			JOIN tbl_apohresep ON tbl_kasir.noreg = tbl_apohresep.noreg
				// 			WHERE jenisbayar=1 AND jenispas=9 AND tbl_kasir.koders='$unit' AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2' AND bayarcard > 0 AND tbl_kasir.nokwitansi IN (SELECT nokwitansi FROM tbl_kartukredit)
				// 		) AS card
				// 		UNION ALL
				// 		SELECT * FROM (
				// 			SELECT COUNT(*) AS jumlah, SUM(jumlahhutang) AS nilai
				// 			FROM tbl_kasir 
				// 			JOIN tbl_pap ON tbl_kasir.noreg = tbl_pap.noreg
				// 			JOIN tbl_apohresep ON tbl_apohresep.noreg=tbl_kasir.noreg
				// 			WHERE jenisbayar=1 and jenispas=9 AND tbl_kasir.koders='$unit' 
				// 			AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2'
				// 		) as penjamin
				// 	) as order_tunai

				// 	union all

				// 	SELECT '   Order Cash' as keterangan, count(*) as jumlah, sum(bayarcash) as nilai
				// 	from tbl_kasir 
				// 	JOIN tbl_apohresep ON tbl_kasir.noreg = tbl_apohresep.noreg
				// 	where jenisbayar=1 AND jenispas=9 AND tbl_kasir.koders='$unit' AND tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2' AND bayarcash > 0

				// 	union all

				// 	SELECT '   Order Card' as keterangan, count(*) as jumlah, sum(bayarcard) as nilai
				// 	from tbl_kasir 
				// 	JOIN tbl_apohresep ON tbl_kasir.noreg = tbl_apohresep.noreg
				// 	where jenisbayar=1 AND jenispas=9 AND tbl_kasir.koders='$unit' AND tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2' and bayarcard > 0 and tbl_kasir.nokwitansi in (select nokwitansi from tbl_kartukredit)
					
				// 	union all

				// 	SELECT '   Penjamin' AS keterangan, COUNT(*) AS jumlah, SUM(jumlahhutang) AS nilai
				// 	FROM tbl_kasir 
				// 	JOIN tbl_pap ON tbl_kasir.noreg = tbl_pap.noreg
				// 	JOIN tbl_apohresep ON tbl_apohresep.noreg=tbl_kasir.noreg
				// 	WHERE jenisbayar=1 and jenispas=9 AND tbl_kasir.koders='$unit' 
				// 	AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2'

				// 	UNION ALL

				// 	SELECT 'APOTIK' as keterangan, sum(jumlah) as jumlah, sum(nilai) as nilai FROM (
				// 		SELECT * FROM (
				// 			SELECT COUNT(*) AS jumlah, SUM(bayarcash) AS nilai
				// 			FROM tbl_kasir 
				// 			JOIN tbl_apohresep ON tbl_kasir.noreg = tbl_apohresep.noreg
				// 			WHERE jenisbayar = 1 AND jenispas = 7 AND tbl_kasir.koders='$unit' AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2' AND bayarcash > 0
				// 		) AS cash
				// 		UNION ALL
				// 		SELECT * FROM (
				// 			SELECT COUNT(*) AS jumlah, SUM(bayarcard) AS nilai
				// 			FROM tbl_kasir 
				// 			JOIN tbl_apohresep ON tbl_kasir.noreg = tbl_apohresep.noreg
				// 			WHERE jenisbayar=1 AND jenispas = 7 AND tbl_kasir.koders='$unit' AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2' AND bayarcard > 0 AND tbl_kasir.nokwitansi IN (SELECT nokwitansi FROM tbl_kartukredit)
				// 		) AS card
				// 		UNION ALL
				// 		SELECT * FROM (
				// 			SELECT COUNT(*) AS jumlah, SUM(jumlahhutang) AS nilai
				// 			FROM tbl_kasir 
				// 			JOIN tbl_pap ON tbl_kasir.noreg = tbl_pap.noreg
				// 			JOIN tbl_apohresep ON tbl_apohresep.noreg=tbl_kasir.noreg
				// 			WHERE jenisbayar=1 and jenispas=7 AND tbl_kasir.koders='$unit' 
				// 			AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2'
				// 		) as penjamin
				// 	) as order_tunai

				// 	union all

				// 	SELECT '   Order Cash' as keterangan, count(*) as jumlah, sum(bayarcash) as nilai
				// 	from tbl_kasir 
				// 	JOIN tbl_apohresep ON tbl_kasir.noreg = tbl_apohresep.noreg
				// 	where jenisbayar=1 AND jenispas = 7 AND tbl_kasir.koders='$unit' AND tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2' AND bayarcash > 0

				// 	union all

				// 	SELECT '   Order Card' as keterangan, count(*) as jumlah, sum(bayarcard) as nilai
				// 	from tbl_kasir 
				// 	JOIN tbl_apohresep ON tbl_kasir.noreg = tbl_apohresep.noreg
				// 	where jenisbayar=1 AND jenispas = 7 AND tbl_kasir.koders='$unit' AND tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2' and bayarcard > 0 and tbl_kasir.nokwitansi in (select nokwitansi from tbl_kartukredit)

				// 	union all

				// 	SELECT '   Penjamin' AS keterangan, COUNT(*) AS jumlah, SUM(jumlahhutang) AS nilai
				// 	FROM tbl_kasir 
				// 	JOIN tbl_pap ON tbl_kasir.noreg = tbl_pap.noreg
				// 	JOIN tbl_apohresep ON tbl_apohresep.noreg=tbl_kasir.noreg
				// 	WHERE jenisbayar=1 and jenispas=7 AND tbl_kasir.koders='$unit' 
				// 	AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2'
				// ";
				
				$query =
					"SELECT 'APOTIK' as keterangan, sum(jumlah) as jumlah, sum(nilai) as nilai FROM (
						SELECT * FROM (
							SELECT COUNT(*) AS jumlah, SUM(bayarcash) AS nilai
							FROM tbl_kasir 
							JOIN tbl_apohresep ON tbl_kasir.noreg = tbl_apohresep.noreg
							WHERE jenisbayar = 1 AND jenispas = 7 AND tbl_kasir.koders='$unit' AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2' AND bayarcash > 0
						) AS cash
						UNION ALL
						SELECT * FROM (
							SELECT COUNT(*) AS jumlah, SUM(bayarcard) AS nilai
							FROM tbl_kasir 
							JOIN tbl_apohresep ON tbl_kasir.noreg = tbl_apohresep.noreg
							WHERE jenisbayar=1 AND jenispas = 7 AND tbl_kasir.koders='$unit' AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2' AND bayarcard > 0 AND tbl_kasir.nokwitansi IN (SELECT nokwitansi FROM tbl_kartukredit)
						) AS card
						UNION ALL
						SELECT * FROM (
							SELECT COUNT(*) AS jumlah, SUM(jumlahhutang) AS nilai
							FROM tbl_kasir 
							JOIN tbl_pap ON tbl_kasir.noreg = tbl_pap.noreg
							JOIN tbl_apohresep ON tbl_apohresep.noreg=tbl_kasir.noreg
							WHERE jenisbayar=1 and jenispas=7 AND tbl_kasir.koders='$unit' 
							AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2'
						) as penjamin
					) as order_tunai

					union all

					SELECT '   Order Cash' as keterangan, count(*) as jumlah, sum(bayarcash) as nilai
					from tbl_kasir 
					JOIN tbl_apohresep ON tbl_kasir.noreg = tbl_apohresep.noreg
					where jenisbayar=1 AND jenispas = 7 AND tbl_kasir.koders='$unit' AND tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2' AND bayarcash > 0

					union all

					SELECT '   Order Card' as keterangan, count(*) as jumlah, sum(bayarcard) as nilai
					from tbl_kasir 
					JOIN tbl_apohresep ON tbl_kasir.noreg = tbl_apohresep.noreg
					where jenisbayar=1 AND jenispas = 7 AND tbl_kasir.koders='$unit' AND tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2' and bayarcard > 0 and tbl_kasir.nokwitansi in (select nokwitansi from tbl_kartukredit)

					union all

					SELECT '   Penjamin' AS keterangan, COUNT(*) AS jumlah, SUM(jumlahhutang) AS nilai
					FROM tbl_kasir 
					JOIN tbl_pap ON tbl_kasir.noreg = tbl_pap.noreg
					JOIN tbl_apohresep ON tbl_apohresep.noreg=tbl_kasir.noreg
					WHERE jenisbayar=1 and jenispas=7 AND tbl_kasir.koders='$unit' 
					AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2'
				";

				$lap = $this->db->query($query)->result();
				$pdf = new simkeu_rpt();
				$pdf->setID($nama_usaha, $motto, $alamat);
				$pdf->setunit($namaunit);
				$pdf->setjudul('02 REKAP PENJUALAN HARIAN');
				$pdf->setsubjudul($_peri);
				$pdf->addpage("P", "A4");
				$pdf->setsize("P", "A4");
				$pdf->SetWidths(array(90, 50, 50));
				$pdf->SetAligns(array('L', 'C', 'R'));
				$judul = array('Keterangan', 'Jumlah', 'Nilai');
				$pdf->setfont('Arial', 'B', 10);
				$pdf->row($judul);

				$pdf->SetWidths(array(90, 50, 50));
				$pdf->SetAligns(array('L', 'C', 'R'));
				$pdf->setfont('Arial', '', 9);
				$pdf->SetFillColor(224, 235, 255);
				$pdf->SetTextColor(0);
				$pdf->SetFont('');

				$nourut = 1;

				foreach ($lap as $db) {

					$pdf->row(array(
						$db->keterangan,
						$db->jumlah . ' Transaksi',
						angka_rp($db->nilai, 0)

					));

					$nourut++;
				}

				$pdf->SetTitle('02 REKAP PENJUALAN HARIAN');
				$pdf->AliasNbPages();
				$pdf->output('KASIR-02.PDF', 'I');
			} else if ($idlp == 103) {
				$bulan    = date('n', strtotime($tgl1));
				$tahun    = date('Y', strtotime($tgl2));
				$cekpdf   = 3;
				$date       = "Dari Tgl : " . date("d-m-Y", strtotime($tgl1)) . " S/D " . date("d-m-Y", strtotime($tgl2));

				$position = 'L';
				$judul    = '03 REKAP JASA DAN PENJUALAN';

			$body = "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
			<tr>
				<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\">No</td>
				<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"9%\">No. Tr</td>
				<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"7%\">Tanggal</td>
				<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"7%\">Pro</td>
				<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"7%\">Adm</td>
				<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"7%\">Jasa</td>
				<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"7%\">Obat Tunai</td>
				<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"7%\">Lokal</td>
				<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"7%\">Kirim</td>
				<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"7%\">Obat Spa</td>
				<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"7%\">Apotek</td>
				<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"7%\">Obat Gigi</td>
				<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"7%\">Total</td>
				<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"7%\">No. Resep</td>
			</tr>";

					
			// 	$query =
			// 		"select tbl_kasir.*, tbl_pasien.*, tbl_apoposting.resepno from tbl_kasir 
			//   inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
			//   left outer join tbl_apoposting on tbl_kasir.nokwitansi=tbl_apoposting.nokwitansi
			//   where tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2' and koders='$unit'
			//  ";

				$query =
					"SELECT tbl_kasir.*, tbl_pasien.* ,
					(select resepno from tbl_apoposting b where b.nokwitansi=tbl_kasir.nokwitansi)resepno
					from tbl_kasir 
			  inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
			  where tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2' and tbl_kasir.koders='$unit'
			  order by tbl_kasir.tglbayar
			 ";
				
				$lap = $this->db->query($query)->result();

				$no = 1;
				$tot1 = $tot2 = $tot3 = $tot4 = $tot5 = $tot6 = $tot7 = $tot8 =$total= 0 ;

				foreach ($lap as $db) {
					$tot1 += $db->adm + $db->totalpoli;
					$tot6 += $db->totalresep;
					$tot8 += $db->totalsemua;

					$body .=  "<tr>
						<td style=\"text-align: center; padding: 5px;\" width=\"5%\">" . $no++ . "</td>
						<td style=\"text-align: left; padding: 5px;\">$db->noreg</td>
						<td style=\"text-align: center; padding: 5px;\">".date("d-m-Y", strtotime($db->tglbayar))."</td>
						<td style=\"text-align: left; padding: 5px;\">$db->namapas</td>
						<td style=\"text-align: right; padding: 5px;\">$db->adm</td>
						<td style=\"text-align: right; padding: 5px;\">".angka_rp($db->totalpoli, 0)."</td>
						<td style=\"text-align: right; padding: 5px;\" width=\"5%\">".angka_rp(0, 0)."</td>
						<td style=\"text-align: center; padding: 5px;\">".angka_rp(0, 0)."</td>
						<td style=\"text-align: right; padding: 5px;\" width=\"5%\">".angka_rp(0, 0)."</td>
						<td style=\"text-align: right; padding: 5px;\" width=\"8%\">".angka_rp(0, 0)."</td>
						<td style=\"text-align: right; padding: 5px;\" width=\"8%\">".angka_rp($db->totalresep, 0)."</td>
						<td style=\"text-align: right; padding: 5px;\" width=\"8%\">".angka_rp(0, 0)."</td>
						<td style=\"text-align: right; padding: 5px;\" width=\"8%\">".angka_rp($db->totalsemua, 0)."</td>
						<td style=\"text-align: right; padding: 5px;\" width=\"8%\">$db->resepno</td>
					</tr>";
					
					$total += $db->totalsemua;
					$no++;
				}

				if($cekpdf == 3){
					$ttl = number_format($total);
				} else {
					$ttl = $total;
				}
				$body .= "<tr>
					<td style=\"text-align: center; font-weight: bold; padding: 5px;\" colspan=\"4\">TOTAL</td>
					<td style=\"text-align: right; padding: 5px;\">".angka_rp($db->adm, 0)."</td>
					<td style=\"text-align: right; padding: 5px;\">".angka_rp($tot1, 0)."</td>
					<td style=\"text-align: right; padding: 5px;\">".angka_rp($tot2, 0)."</td>
					<td style=\"text-align: right; padding: 5px;\">".angka_rp($tot3, 0)."</td>
					<td style=\"text-align: right; padding: 5px;\">".angka_rp($tot4, 0)."</td>
					<td style=\"text-align: right; padding: 5px;\">".angka_rp($tot5, 0)."</td>
					<td style=\"text-align: right; padding: 5px;\">".angka_rp($tot6, 0)."</td>
					<td style=\"text-align: right; padding: 5px;\">".angka_rp($tot7, 0)."</td>
					<td style=\"text-align: right; padding: 5px;\">".angka_rp($tot8, 0)."</td>
				</tr>
				</table>";
							
			$this->M_template_cetak->template($judul, $body, $position, $date, $cekpdf);

				// $pdf = new simkeu_rpt();
				// $pdf->setID($nama_usaha, $motto, $alamat);
				// $pdf->setunit($namaunit);
				// $pdf->setjudul('03 REKAP JASA DAN PENJUALAN');
				// $pdf->setsubjudul($_peri);
				// $pdf->addpage("L", "A4");
				// $pdf->setsize("L", "A4");
				// $pdf->SetWidths(array(32, 22, 35, 20,25, 25, 20, 20, 20, 20, 20, 30, 20));
				// $pdf->SetAligns(array('C', 'C', 'C','C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
				// $judul = array('TR No', 'Tanggal', 'Pro', 'Adm', 'Jasa', 'Obat Tunai', 'Lokal', 'Kirim', 'Obat Spa', 'Apotek', 'Obat Gigi', 'Total', 'No. Resep');
				// $pdf->setfont('Arial', 'B', 8);
				// $pdf->row($judul);

				// $pdf->SetWidths(array(32, 22, 35, 20,25, 25, 20, 20, 20, 20, 20, 30, 20));
				// $pdf->SetAligns(array('C', 'C', 'L', 'R','R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'L'));
				// $pdf->setfont('Arial', '', 8);
				// $pdf->SetFillColor(224, 235, 255);
				// $pdf->SetTextColor(0);
				// $pdf->SetFont('');

				// $nourut = 1;
				// $tot1 = $tot2 = $tot3 = $tot4 = $tot5 = $tot6 = $tot7 = $tot8 = 0;
				// foreach ($lap as $db) {
				// 	$tot1 += $db->adm + $db->totalpoli;
				// 	$tot6 += $db->totalresep;
				// 	$tot8 += $db->totalsemua;

				// 	$pdf->row(array(
				// 		$db->noreg,
				// 		tanggal($db->tglbayar),
				// 		$db->namapas,
				// 		angka_rp($db->adm, 0),
				// 		angka_rp($db->totalpoli, 0),
				// 		angka_rp(0, 0),
				// 		angka_rp(0, 0),
				// 		angka_rp(0, 0),
				// 		angka_rp(0, 0),
				// 		angka_rp($db->totalresep, 0),
				// 		angka_rp(0, 0),
				// 		angka_rp($db->totalsemua, 0),
				// 		$db->resepno


				// 	));

				// 	$nourut++;
				// }
				// $pdf->setfont('Arial', 'B', 8);
				// $pdf->SetWidths(array(89, 20, 25, 20, 20, 20, 20, 20, 30, 20));
				// $pdf->SetAligns(array('L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'L'));
				// $pdf->row(array(
				// 	'Total',
				// 	angka_rp($tot1, 0),
				// 	angka_rp($tot2, 0),
				// 	angka_rp($tot3, 0),
				// 	angka_rp($tot4, 0),
				// 	angka_rp($tot5, 0),
				// 	angka_rp($tot6, 0),
				// 	angka_rp($tot7, 0),
				// 	angka_rp($tot8, 0),
				// 	$db->resepno


				// ));
				// $pdf->SetTitle('03 REKAP JASA DAN PENJUALAN');
				// $pdf->AliasNbPages();
				// $pdf->output('KASIR-03.PDF', 'I');


			} else if ($idlp == 104) {
				$bulan = date('n', strtotime($tgl1));
				$tahun = date('Y', strtotime($tgl2));
				$query =
					"select tbl_kasir.*, tbl_pasien.* from tbl_kasir 
			  inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
			  where tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2'
			 ";


				$query .= "order by tbl_kasir.tglbayar";
				$lap = $this->db->query($query)->result();
				$pdf = new simkeu_rpt();
				$pdf->setID($nama_usaha, $motto, $alamat);
				$pdf->setunit($namaunit);
				$pdf->setjudul('04 LAPORAN HARIAN BERDASARKAN JENIS TRANSAKSI');
				$pdf->setsubjudul($_peri);
				$pdf->addpage("L", "A4");
				$pdf->setsize("L", "A4");
				$pdf->SetWidths(array(10, 35, 20, 45, 20, 20, 20, 20, 20, 20, 20, 30));
				$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
				$judul = array('No.', 'No. Kwitansi', 'Tanggal', 'Nama Pasien', 'ADM', 'Uang Muka', 'Jasa Kulit', 'Resep', 'Trx Lokal', 'O. Kirim', 'Ongkir', 'Total');
				$pdf->setfont('Arial', 'B', 8);
				$pdf->row($judul);

				$pdf->SetWidths(array(10, 35, 20, 45, 20, 20, 20, 20, 20, 20, 20, 30));
				$pdf->SetAligns(array('C', 'C', 'C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
				$pdf->setfont('Arial', '', 8);
				$pdf->SetFillColor(224, 235, 255);
				$pdf->SetTextColor(0);
				$pdf->SetFont('');

				$nourut = 1;
				$tot1 = $tot2 = $tot3 = $tot4 = $tot5 = $tot6 = $tot7 = $tot8 = 0;
				foreach ($lap as $db) {
					$tot1 += $db->adm;
					$tot2 += $db->uangmuka;
					$tot4 += $db->totalresep;
					$tot8 += $db->totalsemua;

					$pdf->row(array(
						$nourut,
						$db->nokwitansi,
						tanggal($db->tglbayar),
						$db->namapas,
						angka_rp($db->adm, 0),
						angka_rp($db->uangmuka, 0),

						angka_rp(0, 0),
						angka_rp($db->totalresep, 0),
						angka_rp(0, 0),
						angka_rp(0, 0),

						angka_rp(0, 0),
						angka_rp($db->totalsemua, 0),


					));

					$nourut++;
				}
				$pdf->setfont('Arial', 'B', 8);
				$pdf->SetWidths(array(110, 20, 20, 20, 20, 20, 20, 20, 30));
				$pdf->SetAligns(array('L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
				$pdf->row(array(
					'Total',
					angka_rp($tot1, 0),
					angka_rp($tot2, 0),
					angka_rp($tot3, 0),
					angka_rp($tot4, 0),
					angka_rp($tot5, 0),
					angka_rp($tot6, 0),
					angka_rp($tot7, 0),
					angka_rp($tot8, 0),


				));
				$pdf->SetTitle('04 LAPORAN HARIAN BERDASARKAN JENIS TRANSAKSI');
				$pdf->AliasNbPages();
				$pdf->output('KASIR-04.PDF', 'I');
			} else if ($idlp == 105) {
				$bulan = date('n', strtotime($tgl1));
				$tahun = date('Y', strtotime($tgl2));
				$query =
					"SELECT 'Order Tunai' as keterangan, count(*) as jumlah, sum(bayarcash+bayarcard) as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalresep>0
			union all
			select '   Order Cash' as keterangan, count(*) as jumlah, sum(bayarcash) as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalresep>0
			union all
			select '   Order Card' as keterangan, count(*) as jumlah, sum(bayarcard) as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalresep>0
			union all
			select 'Tindakan Dokter' as keterangan, count(*) as jumlah, sum(totalpoli) as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select '   Order Cash' as keterangan, count(*) as jumlah, sum(bayarcash) as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select '   Order Card' as keterangan, count(*) as jumlah, sum(bayarcard) as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select 'Order Lokal' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select '   Order Cash' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select '   Order Card' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select 'Order Kirim' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select '   Order Cash' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select '   Order Card' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select 'Ongkos Kirim' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select '   Order Cash' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select '   Order Card' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select 'SPA' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select '   Order Cash' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select '   Order Card' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select 'Produk SPA' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select '   Order Cash' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select '   Order Card' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select 'APOTIK' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select '   Order Cash' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select '   Order Card' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			";

				$lap = $this->db->query($query)->result();
				$pdf = new simkeu_rpt();
				$pdf->setID($nama_usaha, $motto, $alamat);
				$pdf->setunit($namaunit);
				$pdf->setjudul('05 REKAP PENJUALAN HARIAN LENGKAP');
				$pdf->setsubjudul($_peri);
				$pdf->addpage("P", "A4");
				$pdf->setsize("P", "A4");
				$pdf->SetWidths(array(50, 50, 50));
				$pdf->SetAligns(array('L', 'L', 'R'));
				$judul = array('Keterangan', 'Jumlah', 'Nilai');
				$pdf->setfont('Arial', 'B', 10);
				$pdf->row($judul);

				$pdf->SetWidths(array(50, 50, 50));
				$pdf->SetAligns(array('L', 'L', 'R'));
				$pdf->setfont('Arial', '', 9);
				$pdf->SetFillColor(224, 235, 255);
				$pdf->SetTextColor(0);
				$pdf->SetFont('');

				$nourut = 1;

				foreach ($lap as $db) {

					$pdf->row(array(
						$db->keterangan,
						$db->jumlah . ' Transaksi',
						angka_rp($db->nilai, 0)

					));

					$nourut++;
				}

				$pdf->SetTitle('05 REKAP PENJUALAN HARIAN');
				$pdf->AliasNbPages();
				$pdf->output('KASIR-05.PDF', 'I');
			} else if ($idlp == 106) {
				$bulan = date('n', strtotime($tgl1));
				$tahun = date('Y', strtotime($tgl2));
				$query =
					"SELECT 
			'<14' as xrange,
			'UMUR <14' as keterangan,
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 0 and 14 then 1 end),0) as jmlpasien, 
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 0 and 14 then tbl_kasir.totalsemua end),0) as omset
			from tbl_kasir 
			inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
			where 
				tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2'
			union 
			SELECT 
			'15-20' as xrange,
			'UMUR 15-20' as keterangan,
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 15 and 20 then 1 end),0) as jmlpasien, 
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 15 and 20 then tbl_kasir.totalsemua end),0) as omset
			from tbl_kasir 
			inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
			where 
				tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2'
			union 
            select 
			'21-25' as xrange,
			'UMUR 21-25' as keterangan,
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 21 and 25 then 1 end),0) as jmlpasien, 
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 21 and 25 then tbl_kasir.totalsemua end),0) as omset
			from tbl_kasir 
			inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
			where 
				tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2'			
			union	
			select 
			'26-30' as xrange,
			'UMUR 26-30' as keterangan,
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 26 and 30 then 1 end),0) as jmlpasien, 
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 26 and 30 then tbl_kasir.totalsemua end),0) as omset
			from tbl_kasir 
			inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
			where 
				tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2'	
			union	
			select 
			'31-35' as xrange,
			'UMUR 31-35' as keterangan,
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 31 and 35 then 1 end),0) as jmlpasien, 
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 31 and 35 then tbl_kasir.totalsemua end),0) as omset
			from tbl_kasir 
			inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
			where 
				tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2'
            	union	
			select 
			'36-40' as xrange,
			'UMUR 36-40' as keterangan,
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 36 and 40 then 1 end),0) as jmlpasien, 
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 46 and 40 then tbl_kasir.totalsemua end),0) as omset
			from tbl_kasir 
			inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
			where 
				tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2'
 			union	
			select 
			'>40' as xrange,
			'UMUR >40' as keterangan,
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE())>40 then 1 end),0) as jmlpasien, 
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE())>40 then tbl_kasir.totalsemua end),0) as omset
			from tbl_kasir 
			inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
			where 
				tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2'	
		    ";


				$lap = $this->db->query($query)->result();
				$pdf = new simkeu_rpt();
				$pdf->setID($nama_usaha, $motto, $alamat);
				$pdf->setunit($namaunit);
				$pdf->setjudul('06 LAPORAN OMSET PER KELOMPOK UMUR');
				$pdf->setsubjudul($_peri);
				$pdf->addpage("P", "A4");
				$pdf->setsize("P", "A4");
				$pdf->SetWidths(array(50, 50, 30, 50));
				$pdf->SetAligns(array('L', 'L', 'R', 'R'));
				$judul = array('RANGE', 'KETERANGAN', 'JM PASIEN', 'NILAI RUPIAH');
				$pdf->setfont('Arial', 'B', 10);
				$pdf->row($judul);

				$pdf->SetWidths(array(50, 50, 30, 50));
				$pdf->SetAligns(array('L', 'L', 'R', 'R'));
				$pdf->setfont('Arial', '', 9);
				$pdf->SetFillColor(224, 235, 255);
				$pdf->SetTextColor(0);
				$pdf->SetFont('');

				$nourut = 1;
				$tot1 = $tot2 = 0;
				foreach ($lap as $db) {
					$tot1 += $db->jmlpasien;
					$tot2 += $db->omset;

					$pdf->row(array(
						$db->xrange,
						$db->keterangan,
						angka_rp($db->jmlpasien, 0),
						angka_rp($db->omset, 0),

					));

					$nourut++;
				}
				$pdf->setfont('Arial', 'B', 9);
				$pdf->SetWidths(array(100, 30, 50));
				$pdf->SetAligns(array('C', 'R', 'R'));
				$pdf->row(array(
					'TOTAL',
					angka_rp($tot1, 0),
					angka_rp($tot2, 0),



				));
				$pdf->SetTitle('06 LAPORAN OMSET PER KELOMPOK UMUR');
				$pdf->AliasNbPages();
				$pdf->output('KASIR-06.PDF', 'I');
			}
		} else {

			header('location:' . base_url());
		}
	}

	function cetak_101($cek = '', $thnn = '') {
		$shiftx          = $this->input->get('shift');
		if($shiftx == 0 || $shiftx == null || $shiftx == 'null' || $shiftx == ''){
			$shift = "";
		} else if($shiftx == 1){
			$shift = "and k.shift = 1";
		} else {
			$shift = "and k.shift = 2";
		}
		$cetak          = $this->input->get('cekk');
		$chari   	  	= '';
		$cekk         = $this->session->userdata('level');
		$cabang   	  = $this->session->userdata('unit');
		$avatar   	  = $this->session->userdata('avatar_cabang');
   
		$profile      = $this->M_global->_LoadProfileLap();
		$nama_usaha   = $profile->nama_usaha;
		$motto        = '';
		$alamat       = '';
		$idlp         = $this->input->get('idlap');
		$dari         = $this->input->get('tgl1');
		$sampai       = $this->input->get('tgl2');
		$dokter       = $this->input->get('dokter');
		$cab          = $this->input->get('cabang');
		$_peri        = 'Dari ' . date('d-m-Y', strtotime($dari)) . ' s/d ' . date('d-m-Y', strtotime($sampai));
		if (!empty($cekk)) {
			$kop       = $this->M_cetak->kop($cabang);
			$namars    = $kop['namars'];
			$alamat    = $kop['alamat'];
			$alamat2   = $kop['alamat2'];
			$phone     = $kop['phone'];
			$whatsapp  = $kop['whatsapp']; 
			$npwp      = $kop['npwp'];

			$chari .= "<table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
				<tr>
					<td rowspan=\"6\" align=\"center\">
						
						<img src=\"" . base_url() . "assets/img_user/$avatar\"  width=\"70\" height=\"70\" />
					</td>
					<td colspan=\"20\">
						<b>
							<tr><td style=\"font-size:14px;border-bottom: none;\"><b>$namars</b></td></tr>
							<tr><td style=\"font-size:13px;\">$alamat</td></tr>
							<tr><td style=\"font-size:13px;\">$alamat2</td></tr>
							<tr><td style=\"font-size:13px;\">Wa :$whatsapp    Telp :$phone </td></tr>
							<tr><td style=\"font-size:13px;\">No. NPWP : $npwp</td></tr>
						</b>
					</td>
				</tr>
				<tr>
					<td>&nbsp;
					</td>
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
               <table style=\"border-collapse:collapse;font-family: tahoma; font-size:10px\" width=\"100%\" align=\"center\" border=\"0\">     
                    <tr>
                         <td>&nbsp;</td>
                    </tr> 
               </table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
				<tr>
					<td colspan=\"21\" style=\"text-align:center;font-size:22px;border-bottom: none;color:#120292;\"><b>01 LAPORAN HARIAN KASIR PENDAPATAN PER SHIFT</b></td>
				</tr> 
				<tr>
					<td colspan=\"21\" style=\"text-align:center;font-size:15px;border-top: none;\">$_peri</td>
				</tr>
			</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
				<thead><tr>
					<td bgcolor=\"#cccccc\" width=\"5%\" align=\"center\" rowspan=\"2\"><b>No.</b></td>
					<td bgcolor=\"#cccccc\" width=\"5%\" align=\"center\" rowspan=\"2\"><b>Shift</b></td>
					<td bgcolor=\"#cccccc\" width=\"8%\" align=\"center\" rowspan=\"2\"><b>Kwitansi</b></td>
					<td bgcolor=\"#cccccc\" width=\"10%\" align=\"center\" rowspan=\"2\"><b>Tanggal</b></td>
					<td bgcolor=\"#cccccc\" width=\"4%\" align=\"center\" rowspan=\"2\"><b>Nama Pasien</b></td>
					<td bgcolor=\"#cccccc\" width=\"4%\" align=\"center\" rowspan=\"2\"><b>Adm</b></td>
					<td bgcolor=\"#cccccc\" width=\"4%\" align=\"center\" rowspan=\"2\"><b>U.Muka</b></td>
					<td bgcolor=\"#cccccc\" width=\"12%\" align=\"center\" rowspan=\"2\"><b>Apotik</b></td>
					<td bgcolor=\"#cccccc\" width=\"4%\" align=\"center\" rowspan=\"2\"><b>Total Resep</b></td>
					<td bgcolor=\"#cccccc\" width=\"4%\" align=\"center\" rowspan=\"2\"><b>Selisih</b></td>
					<td bgcolor=\"#cccccc\" width=\"8%\" align=\"center\" colspan=\"2\"><b>Diskon</b></td>
					<td bgcolor=\"#cccccc\" width=\"4%\" align=\"center\" rowspan=\"2\"><b>Voucher</b></td>
					<td bgcolor=\"#cccccc\" width=\"20%\" align=\"center\" colspan=\"6\"><b>Cara Bayar</b></td>
					<td bgcolor=\"#cccccc\" width=\"4%\" align=\"center\" rowspan=\"2\"><b>Total Net</b></td>
				</tr>
				<tr>
					<td bgcolor=\"#cccccc\" width=\"4%\" align=\"center\"><b>Tindakan</b></td>
					<td bgcolor=\"#cccccc\" width=\"4%\" align=\"center\"><b>Resep</b></td>
					<td bgcolor=\"#cccccc\" width=\"4%\" align=\"center\"><b>CS</b></td>
					<td bgcolor=\"#cccccc\" width=\"4%\" align=\"center\"><b>DB</b></td>
					<td bgcolor=\"#cccccc\" width=\"4%\" align=\"center\"><b>CC</b></td>
					<td bgcolor=\"#cccccc\" width=\"4%\" align=\"center\"><b>TF</b></td>
					<td bgcolor=\"#cccccc\" width=\"4%\" align=\"center\"><b>OL</b></td>
					<td bgcolor=\"#cccccc\" width=\"4%\" align=\"center\"><b>Penjamin</b></td>
				</tr></thead>";
			$kondisi = $this->db->query("SELECT k.* FROM tbl_kasir k WHERE k.tglbayar >= '$dari' and k.tglbayar <= '$sampai' and k.koders='$cabang' $shift")->result();
			if($kondisi) {
				$no = 1;
				foreach($kondisi as $kds) {
					$query = $this->db->query(
						"SELECT IF(k.shift = '', '-', k.shift) AS shift, k.nokwitansi, k.tglbayar, k.namapasien as namapas, k.adm, k.noreg, k.jambayar,
							(CASE WHEN posbayar='UANG_MUKA' THEN uangmuka ELSE uangmuka END )uangmuka,
							(CASE WHEN r.kodepos='KULIT' THEN totalpoli ELSE 0 END)jkulit,
							(CASE WHEN r.kodepos='GIGI' THEN totalpoli ELSE 0 END)jgigi,
							(CASE WHEN r.kodepos='SPA' THEN totalpoli ELSE 0 END)jspa,
							(CASE WHEN r.kodepos='APOTIK' THEN totalpoli ELSE 0 END)japotik,
							totalresep AS resepnya,
							selisihrp AS selisih,
							(SELECT totalresep FROM tbl_kasir WHERE noreg = '' AND koders = k.koders AND nokwitansi = k.nokwitansi) as lokal,
							0 as kirim,
							k.voucherrp1 as vc1,
							k.voucherrp2 as vc2,
							k.voucherrp3 as vc3,
							k.diskonrp as dis_tin,
							k.diskonresep as dis_res,
							k.bayarcash - k.kembali as cash,
							k.totalbayar - k.bayarcash - k.bayarcard as jamin,
							IF(bayarcard > 0, (SELECT SUM(jumlahbayar) FROM tbl_kartukredit WHERE cardtype = 1 AND nokwitansi = k.nokwitansi), 0) as db,
							IF(bayarcard > 0, (SELECT SUM(jumlahbayar) FROM tbl_kartukredit WHERE cardtype = 2 AND nokwitansi = k.nokwitansi), 0) as cc,
							IF(bayarcard > 0, (SELECT SUM(jumlahbayar) FROM tbl_kartukredit WHERE cardtype = 3 AND nokwitansi = k.nokwitansi), 0) as tf,
							IF(bayarcard > 0, (SELECT SUM(jumlahbayar) FROM tbl_kartukredit WHERE cardtype = 4 AND nokwitansi = k.nokwitansi), 0) as ol
						FROM tbl_kasir k
						LEFT JOIN tbl_pasien p ON p.rekmed = k.rekmed
						LEFT JOIN tbl_regist r ON k.koders = r.koders AND k.noreg = r.noreg
						WHERE k.tglbayar >= '$dari' and k.tglbayar <= '$sampai' and k.koders='$cabang' $shift
						ORDER BY k.tglbayar, k.jambayar ASC")->result();
				}
				$adm1         = 0;
				$uangmuka1    = 0;
				$jkulit1      = 0;
				$jgigi1       = 0;
				$jspa1        = 0;
				$japotik1     = 0;
				$resepnya1    = 0;
				$selisih1     = 0;
				$vcc1         = 0;
				$dis_tin1     = 0;
				$dis_res1     = 0;
				$totalsemua1  = 0;
				$cash1        = 0;
				$jamin1       = 0;
				$db1          = 0;
				$cc1          = 0;
				$tf1          = 0;
				$ol1          = 0;
				$lokal1       = 0;
				$kirim1       = 0;
				foreach($query as $query) {
					$adm1        += $query->adm;
					$uangmuka1   += $query->uangmuka;
					$jkulit1     += $query->jkulit;
					$jgigi1      += $query->jgigi;
					$japotik1    += $query->japotik;
					$jspa1       += $query->jspa;
					$resepnya1   += $query->resepnya;
					$selisih1    += $query->selisih;
					$vcc1        += ($query->vc1 + $query->vc2 + $query->vc3);
					$dis_tin1    += $query->dis_tin;
					$dis_res1    += $query->dis_res;
					$totalsemua1 += ($query->db + $query->cc + $query->tf + $query->ol + $query->cash+$query->jamin);
					$cash1       += $query->cash;
					$jamin1      += $query->jamin;
					$db1         += $query->db;
					$cc1         += $query->cc;
					$tf1         += $query->tf;
					$ol1         += $query->ol;
					$lokal1      += $query->lokal;
					$kirim1      += $query->kirim;
					if($cetak == 1) {
						$adm            = number_format($query->adm);
						$uangmuka       = number_format($query->uangmuka);
						$jkulit         = number_format($query->jkulit);
						$jgigi          = number_format($query->jgigi);
						$jspa           = number_format($query->jspa);
						$japotik        = number_format($query->japotik);
						$lokal          = number_format($query->lokal);
						$kirim          = number_format($query->kirim);
						$resepnya       = number_format($query->resepnya);
						$selisih        = number_format($query->selisih);
						$vc1            = number_format($query->vc1);
						$vc2            = number_format($query->vc2);
						$vc3            = number_format($query->vc3);
						$vc             = number_format($query->vc1 + $query->vc2 + $query->vc3);
						$dis_tin        = number_format($query->dis_tin);
						$dis_res        = number_format($query->dis_res);
						$totalsemua     = number_format($query->db + $query->cc + $query->tf + $query->ol + $query->cash+$query->jamin);
						$cash           = number_format($query->cash);
						$jamin          = number_format($query->jamin);
						$db             = number_format($query->db);
						$cc             = number_format($query->cc);
						$tf             = number_format($query->tf);
						$ol             = number_format($query->ol);
						$adm2           = number_format($adm1);
						$uangmuka2      = number_format($uangmuka1);
						$jkulit2        = number_format($jkulit1);
						$jgigi2         = number_format($jgigi1);
						$jspa2          = number_format($jspa1);
						$japotik2       = number_format($japotik1);
						$resepnya2      = number_format($resepnya1);
						$selisih2       = number_format($selisih1);
						$vcc2           = number_format($vcc1);
						$dis_tin2       = number_format($dis_tin1);
						$dis_res2       = number_format($dis_res1);
						$totalsemua2    = number_format($totalsemua1);
						$cash2          = number_format($cash1);
						$jamin2         = number_format($jamin1);
						$db2            = number_format($db1);
						$cc2            = number_format($cc1);
						$tf2            = number_format($tf1);
						$ol2            = number_format($ol1);
						$lokal2         = number_format($lokal1);
						$kirim2         = number_format($kirim1);
					} else {
						$adm            = round($query->adm);
						$uangmuka       = round($query->uangmuka);
						$jkulit         = round($query->jkulit);
						$jgigi          = round($query->jgigi);
						$jspa           = round($query->jspa);
						$japotik        = round($query->japotik);
						$lokal          = round($query->lokal);
						$resepnya       = round($query->resepnya);
						$selisih        = round($query->selisih);
						$vc1            = round($query->vc1);
						$vc2            = round($query->vc2);
						$vc3            = round($query->vc3);
						$vc             = round($query->vc1 + $query->vc2 + $query->vc3);
						$dis_tin        = round($query->dis_tin);
						$dis_res        = round($query->dis_res);
						$totalsemua     = round($query->db + $query->cc + $query->tf + $query->ol + $query->cash + $query->jamin);
						$cash           = round($query->cash);
						$jamin          = round($query->jamin);
						$db             = round($query->db);
						$cc             = round($query->cc);
						$tf             = round($query->tf);
						$ol             = round($query->ol);
						$adm2           = round($adm1);
						$uangmuka2      = round($uangmuka1);
						$jkulit2        = round($jkulit1);
						$jgigi2         = round($jgigi1);
						$jspa2          = round($jspa1);
						$japotik2       = round($japotik1);
						$resepnya2      = round($resepnya1);
						$selisih2       = round($selisih1);
						$vcc2           = round($vcc1);
						$dis_tin2       = round($dis_tin1);
						$dis_res2       = round($dis_res1);
						$totalsemua2    = round($totalsemua1);
						$cash2          = round($cash1);
						$db2            = round($db1);
						$cc2            = round($cc1);
						$tf2            = round($tf1);
						$ol2            = round($ol1);
						$lokal2         = round($lokal1);
						$kirim2         = round($kirim1);
					}
					$chari .= "<tbody><tr>
						<td style=\"text-align: center;\">$no</td>
						<td style=\"text-align: center;\">$query->shift</td>
						<td>$query->nokwitansi</td>
						<td style=\"text-align: center;\">".date("Y-m-d", strtotime($query->tglbayar))."<br>".date("H:i:s", strtotime($query->jambayar))."</td>
						<td>$query->namapas</td>
						<td style=\"text-align: right;\">$adm</td>
						<td style=\"text-align: right;\">$uangmuka</td>
						<td style=\"text-align: right;\">$lokal</td>
						<td style=\"text-align: right;\">$resepnya</td>
						<td style=\"text-align: right;\">$selisih</td>
						<td style=\"text-align: right;\">$dis_tin</td>
						<td style=\"text-align: right;\">$dis_res</td>
						<td style=\"text-align: right;\">$vc</td>
						<td style=\"text-align: right;\">$cash</td>
						<td style=\"text-align: right;\">$db</td>
						<td style=\"text-align: right;\">$cc</td>
						<td style=\"text-align: right;\">$tf</td>
						<td style=\"text-align: right;\">$ol</td>
						<td style=\"text-align: right;\">$jamin</td>
						<td style=\"text-align: right;\">$totalsemua</td>
					</tr></tbody>";
					$no++;
				}
				// total
				$chari .= "<tfoot><tr>
					<td bgcolor=\"#cccccc\" align=\"center\" colspan=\"5\" ><b>Total Pendapatan Kasir</b></td>
					<td bgcolor=\"#cccccc\" style=\"text-align: right\"><b>$adm2</b></td>
					<td bgcolor=\"#cccccc\" style=\"text-align: right\"><b>$uangmuka2</b></td>
					<td bgcolor=\"#cccccc\" style=\"text-align: right\"><b>$lokal2</b></td>
					<td bgcolor=\"#cccccc\" style=\"text-align: right\"><b>$resepnya2</b></td>
					<td bgcolor=\"#cccccc\" style=\"text-align: right\"><b>$selisih2</b></td>
					<td bgcolor=\"#cccccc\" style=\"text-align: right\"><b>$dis_tin2</b></td>
					<td bgcolor=\"#cccccc\" style=\"text-align: right\"><b>$dis_res2</b></td>
					<td bgcolor=\"#cccccc\" style=\"text-align: right\"><b>$vcc2</b></td>
					<td bgcolor=\"#cccccc\" style=\"text-align: right\"><b>$cash2</b></td>
					<td bgcolor=\"#cccccc\" style=\"text-align: right\"><b>$db2</b></td>
					<td bgcolor=\"#cccccc\" style=\"text-align: right\"><b>$cc2</b></td>
					<td bgcolor=\"#cccccc\" style=\"text-align: right\"><b>$tf2</b></td>
					<td bgcolor=\"#cccccc\" style=\"text-align: right\"><b>$ol2</b></td>
					<td bgcolor=\"#cccccc\" style=\"text-align: right\"><b>$jamin2</b></td>
					<td bgcolor=\"#cccccc\" style=\"text-align: right\"><b>$totalsemua2</b></td>
				</tr>";
			} else {
				// $chari .= "<tfoot><tr>
				// 	<td bgcolor=\"#cccccc\" align=\"center\" colspan=\"5\" rowspan=\"2\"><b>Total Pendapatan Kasir</b></td>";
			}
			// $chari .= "<tr>
			// 	<td bgcolor=\"#cccccc\" align=\"center\"><b>Adm</b></td>
			// 	<td bgcolor=\"#cccccc\" align=\"center\"><b>U.Muka</b></td>
			// 	<td bgcolor=\"#cccccc\" align=\"center\"><b>Apotik</b></td>
			// 	<td bgcolor=\"#cccccc\" align=\"center\"><b>Total Resep</b></td>
			// 	<td bgcolor=\"#cccccc\" align=\"center\"><b>Selisih</b></td>
			// 	<td bgcolor=\"#cccccc\" align=\"center\"><b>Tindakan</b></td>
			// 	<td bgcolor=\"#cccccc\" align=\"center\"><b>Resep</b></td>
			// 	<td bgcolor=\"#cccccc\" align=\"center\"><b>Voucher</b></td>
			// 	<td bgcolor=\"#cccccc\" align=\"center\"><b>CS</b></td>
			// 	<td bgcolor=\"#cccccc\" align=\"center\"><b>DB</b></td>
			// 	<td bgcolor=\"#cccccc\" align=\"center\"><b>CC</b></td>
			// 	<td bgcolor=\"#cccccc\" align=\"center\"><b>TF</b></td>
			// 	<td bgcolor=\"#cccccc\" align=\"center\"><b>OL</b></td>
			// 	<td bgcolor=\"#cccccc\" align=\"center\"><b>Penjamin</b></td>
			// 	<td bgcolor=\"#cccccc\" align=\"center\"><b>Total Net</b></td>
			// </tr></tfoot>";
			$chari .= "</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;\" width=\"60%\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
			<tr>
				<td colspan=\"22\" style=\"border:none\" align=\"center\"><br></td>
			</tr>";

			$sql = "SELECT
			SUM(case when jenisbayar='1' THEN (adm+totalpoli+totalresep+selisihrp+uangmuka+lain)-(voucherrp1+voucherrp2+voucherrp3+diskonrp) else 0 end )as'Cash',
			SUM(case when jenisbayar='2' THEN (adm+totalpoli+totalresep+selisihrp+uangmuka+lain)-(voucherrp1+voucherrp2+voucherrp3+diskonrp) else 0 end )as'CreditCard',
			SUM(case when jenisbayar='3' THEN (adm+totalpoli+totalresep+selisihrp+uangmuka+lain)-(voucherrp1+voucherrp2+voucherrp3+diskonrp) else 0 end )as'DebetCard',
			SUM(case when jenisbayar='4' THEN (adm+totalpoli+totalresep+selisihrp+uangmuka+lain)-(voucherrp1+voucherrp2+voucherrp3+diskonrp) else 0 end )as'Transfer',
			SUM(case when jenisbayar='5' THEN (adm+totalpoli+totalresep+selisihrp+uangmuka+lain)-(voucherrp1+voucherrp2+voucherrp3+diskonrp) else 0 end )as'Online' 
			FROM(
			SELECT adm,totalpoli,totalresep,selisihrp,lain,
					(case when posbayar='UANG_MUKA' THEN uangmuka else ABS(uangmuka)*-1 end )uangmuka,
					voucherrp1,voucherrp2,voucherrp3,diskonrp,
					jenisbayar,bayarcash,bayarcard,totalbayar,kembali,posbayar
					from tbl_kasir k
					inner join tbl_pasien b on k.rekmed=b.rekmed 
					where tglbayar between '$dari' and '$sampai' and k.koders='$cabang'  
			)a";

			$sql2 = "SELECT 
			IF(sum(case when kodebank='BCA' then jumlahbayar else 0 end ) > 0, sum(case when kodebank='BCA' then jumlahbayar else 0 end ), 0) as BCA ,
			IF(sum(case when kodebank='BSI' then jumlahbayar else 0 end ) > 0, sum(case when kodebank='BSI' then jumlahbayar else 0 end ), 0) as BSI ,
			IF(sum(case when kodebank='MNDR' then jumlahbayar else 0 end ) > 0, sum(case when kodebank='MNDR' then jumlahbayar else 0 end ), 0) as MNDR ,
			IF(sum(case when kodebank='BNI' then jumlahbayar else 0 end ) > 0, sum(case when kodebank='BNI' then jumlahbayar else 0 end ), 0) as BNI ,
			IF(sum(case when kodebank='ABI' then jumlahbayar else 0 end ) > 0, sum(case when kodebank='ABI' then jumlahbayar else 0 end ), 0) as ABI ,
			IF(sum(case when kodebank='QRIS' then jumlahbayar else 0 end ) > 0, sum(case when kodebank='QRIS' then jumlahbayar else 0 end ), 0) as QRIS ,
			IF(sum(case when kodebank='001' then jumlahbayar else 0 end ) > 0, sum(case when kodebank='001' then jumlahbayar else 0 end ), 0) as bca_lokal ,
			IF(sum(case when kodebank='002' then jumlahbayar else 0 end ) > 0, sum(case when kodebank='002' then jumlahbayar else 0 end ), 0) as mandiri ,
			IF(sum(case when kodebank='003' then jumlahbayar else 0 end ) > 0, sum(case when kodebank='003' then jumlahbayar else 0 end ), 0) as bca_tunai 
			from tbl_kartukredit k
			where tanggal between '$dari' and '$sampai' and k.koders='$cabang' $shift";

			$query1    = $this->db->query($sql);
			$query2    = $this->db->query($sql2);

			$lcno          = 0;
			$Cash          = 0;
			$CreditCard    = 0;
			$DebetCard     = 0;
			$Transfer      = 0;
			$Online        = 0;
			$BCA           = 0;
			$BSI           = 0;
			$MNDR          = 0;
			$BNI           = 0;
			$ABI           = 0;
			$QRIS          = 0;
			$bca_lokal     = 0;
			$mandiri       = 0;
			$bca_tunai     = 0;

			$shiftt = $this->input->get('shift');
			if($shiftt == '' || $shiftt == null){
				$cek_cash = $this->db->query("SELECT SUM(bayarcash - kembali) AS cash FROM tbl_kasir WHERE koders = '$cabang' AND tglbayar BETWEEN '$dari' AND '$sampai'")->row();
				$cek_jaminan = $this->db->query("SELECT SUM(totalbayar-bayarcash-bayarcard) AS jamin FROM tbl_kasir WHERE koders = '$cabang' AND tglbayar BETWEEN '$dari' AND '$sampai'")->row();
				$cek_debit = $this->db->query("SELECT SUM(jumlahbayar) AS debit FROM tbl_kartukredit WHERE cardtype = 1 AND koders = '$cabang' AND tanggal BETWEEN '$dari' AND '$sampai'")->row();
				$cek_credit = $this->db->query("SELECT SUM(jumlahbayar) AS credit FROM tbl_kartukredit WHERE cardtype = 2 AND koders = '$cabang' AND tanggal BETWEEN '$dari' AND '$sampai'")->row();
				$cek_transfer = $this->db->query("SELECT SUM(jumlahbayar) AS transfer FROM tbl_kartukredit WHERE cardtype = 3 AND koders = '$cabang' AND tanggal BETWEEN '$dari' AND '$sampai'")->row();
				$cek_online = $this->db->query("SELECT SUM(jumlahbayar) AS online FROM tbl_kartukredit WHERE cardtype = 4 AND koders = '$cabang' AND tanggal BETWEEN '$dari' AND '$sampai'")->row();
			} else {
				$cek_cash = $this->db->query("SELECT SUM(bayarcash - kembali) AS cash FROM tbl_kasir WHERE koders = '$cabang' AND tglbayar BETWEEN '$dari' AND '$sampai' AND shift = '$shiftt'")->row();
				$cek_jaminan = $this->db->query("SELECT SUM(totalbayar-bayarcash-bayarcard) AS jamin FROM tbl_kasir WHERE koders = '$cabang' AND tglbayar BETWEEN '$dari' AND '$sampai' AND shift = '$shiftt'")->row();
				$cek_debit = $this->db->query("SELECT SUM(jumlahbayar) AS debit FROM tbl_kartukredit WHERE cardtype = 1 AND koders = '$cabang' AND tanggal BETWEEN '$dari' AND '$sampai' AND shift = '$shiftt'")->row();
				$cek_credit = $this->db->query("SELECT SUM(jumlahbayar) AS credit FROM tbl_kartukredit WHERE cardtype = 2 AND koders = '$cabang' AND tanggal BETWEEN '$dari' AND '$sampai' AND shift = '$shiftt'")->row();
				$cek_transfer = $this->db->query("SELECT SUM(jumlahbayar) AS transfer FROM tbl_kartukredit WHERE cardtype = 3 AND koders = '$cabang' AND tanggal BETWEEN '$dari' AND '$sampai' AND shift = '$shiftt'")->row();
				$cek_online = $this->db->query("SELECT SUM(jumlahbayar) AS online FROM tbl_kartukredit WHERE cardtype = 4 AND koders = '$cabang' AND tanggal BETWEEN '$dari' AND '$sampai' AND shift = '$shiftt'")->row();
			}

			foreach ($query1->result() as $row) {
				$lcno         = $lcno + 1;
				$Cash         = angka_rp($row->Cash, 0);
				$CreditCard   = angka_rp($row->CreditCard, 0);
				$DebetCard    = angka_rp($row->DebetCard, 0);
				$Transfer     = angka_rp($row->Transfer, 0);
				$Online       = angka_rp($row->Online, 0);

				foreach ($query2->result() as $row2) {
					IF($row2->bca_lokal > 0) 
					{ 
						$bca_lokal = $row2->bca_lokal; 
					} else { 
						$bca_lokal = 0; 
					}

					IF($row2->mandiri > 0) 
					{ 
						$mandiri = $row2->mandiri; 
					} else { 
						$mandiri = 0; 
					}

					IF($row2->bca_tunai > 0) 
					{ 
						$bca_tunai = $row2->bca_tunai; 
					} else { 
						$bca_tunai = 0; 
					}
					IF($row2->BCA > 0) 
					{ 
						$BCA = $row2->BCA; 
					} else { 
						$BCA = 0; 
					}
					IF($row2->BSI > 0) 
					{ 
						$BSI = $row2->BSI; 
					} else { 
						$BSI = 0; 
					}
					IF($row2->MNDR > 0) 
					{ 
						$MNDR = $row2->MNDR; 
					} else { 
						$MNDR = 0; 
					}
					IF($row2->BNI > 0) 
					{ 
						$BNI = $row2->BNI; 
					} else { 
						$BNI = 0; 
					}
					IF($row2->ABI > 0) 
					{ 
						$ABI = $row2->ABI; 
					} else { 
						$ABI = 0; 
					}
					IF($row2->QRIS > 0) 
					{ 
						$QRIS = $row2->QRIS; 
					} else { 
						$QRIS = 0; 
					}
					
					$lcno        = $lcno + 1;
					$bca_lokal   = number_format($bca_lokal, 0, '.',',');
					$mandiri     = number_format($mandiri, 0, '.',',');
					$bca_tunai   = number_format($bca_tunai, 0, '.',',');
					$BCA         = number_format($BCA, 0, '.',',');
					$BSI         = number_format($BSI, 0, '.',',');
					$MNDR        = number_format($MNDR, 0, '.',',');
					$BNI         = number_format($BNI, 0, '.',',');
					$ABI         = number_format($ABI, 0, '.',',');
					$QRIS        = number_format($QRIS, 0, '.',',');
					$chari    .= "
					<tr>
						<td colspan=\"3\" style=\"font-size:12px; border-top:none;border-left:none;border-right:none\" align=\"LEFT\"><b>TOTAL TUNAI</b></td>
						<td style=\"border-top:none;border-left:none;border-right:none\" align=\"right\">" . number_format($cek_cash->cash, 0) . "</td>
						<td style=\"border:none\"></td>
						<td colspan=\"3\" style=\"border:none\" align=\"LEFT\"><b>BANK BCA</b></td>
						<td colspan=\"2\" style=\"border:none\" align=\"right\">$BCA</td>                       
					</tr>
					<tr>
						<td colspan=\"3\" style=\"font-size:12px; border-top:none;border-left:none;border-right:none\" align=\"LEFT\"><b>TOTAL DEBIT</b></td>
						<td style=\"border-top:none;border-left:none;border-right:none\" align=\"right\">".number_format($cek_debit->debit,0)."</td>
						<td style=\"border:none\"></td>
						<td colspan=\"3\" style=\"border:none\" align=\"LEFT\"><b>BANK BSI</b></td>
						<td colspan=\"2\" style=\"border:none\" align=\"right\">$BSI</td>                       
					</tr>
					<tr>
						<td colspan=\"3\" style=\"font-size:12px; border-top:none;border-left:none;border-right:none\" align=\"LEFT\"><b>TOTAL KREDIT</b></td>
						<td style=\"border-top:none;border-left:none;border-right:none\" align=\"right\">".number_format($cek_credit->credit,0)."</td>
						<td style=\"border:none\"></td>
						<td colspan=\"3\" style=\"border:none\" align=\"LEFT\"><b>BANK Mandiri</b></td>
						<td colspan=\"2\" style=\"border:none\" align=\"right\">$MNDR</td>                       
					</tr>
					<tr>
						<td colspan=\"3\" style=\"font-size:12px; border-top:none;border-left:none;border-right:none\" align=\"LEFT\"><b>TOTAL TRANSFER</b></td>
						<td style=\"border-top:none;border-left:none;border-right:none\" align=\"right\">" . number_format($cek_transfer->transfer, 0) . "</td>
						<td style=\"border:none\"></td>
						<td colspan=\"3\" style=\"border:none\" align=\"LEFT\"><b>BANK BNI</b></td>
						<td colspan=\"2\" style=\"border:none\" align=\"right\">$BNI</td>                       
					</tr>
					<tr>
						<td colspan=\"3\" style=\"font-size:12px; border-top:none;border-left:none;border-right:none\" align=\"LEFT\"><b>TOTAL ONLINE</b></td>
						<td style=\"border-top:none;border-left:none;border-right:none\" align=\"right\">" . number_format($cek_online->online, 0) . "</td>
						<td style=\"border:none\"></td>
						<td colspan=\"3\" style=\"border:none\" align=\"LEFT\"><b>BANK ABI</b></td>
						<td colspan=\"2\" style=\"border:none\" align=\"right\">$ABI</td>                        
					</tr>
					<tr>
						<td colspan=\"3\" style=\"font-size:12px; border-top:none;border-left:none;border-right:none\" align=\"LEFT\"><b>TOTAL JAMINAN</b></td>
						<td style=\"border-top:none;border-left:none;border-right:none\" align=\"right\">" . number_format($cek_jaminan->jamin, 0) . "</td> 
						<td style=\"border:none\"></td>
						<td colspan=\"3\" style=\"border:none\" align=\"LEFT\"><b>QRIS</b></td>
						<td colspan=\"2\" style=\"border:none\" align=\"right\">$QRIS</td>                      
					</tr>
					<tr><td style=\"border:none;\" colspan=\"21\"><br></td></tr>";
				}
			}

			$chari .= "</table>";

			$chari .= "<table style=\"float:right;border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
			<tr>
				<td align=\"center\" width=\"25%\">&nbsp;</td>                    
				<td align=\"center\" width=\"25%\"><b>Keuangan</b></td>
				<td align=\"center\" width=\"25%\"><b>Ruang Obat</b></td>
				<td align=\"center\" width=\"25%\"> $_peri</td>
			</tr>
			<tr>
				<td align=\"center\" width=\"25%\">&nbsp;</td>                    
				<td align=\"center\" width=\"25%\">&nbsp;</td>
				<td align=\"center\" width=\"25%\">&nbsp;</td>
				<td align=\"center\" width=\"25%\"><b>CASHIER $namars</b></td>
			</tr>
			<tr>
				<td align=\"center\" width=\"25%\">&nbsp;</td>                    
				<td align=\"center\" width=\"25%\">&nbsp;</td>
				<td align=\"center\" width=\"25%\">&nbsp;</td>
				<td align=\"center\" width=\"25%\">&nbsp;</td>
			</tr>
			<tr>
				<td align=\"center\" width=\"25%\">&nbsp;</td>                    
				<td align=\"center\" width=\"25%\">&nbsp;</td>
				<td align=\"center\" width=\"25%\">&nbsp;</td>
				<td align=\"center\" width=\"25%\">&nbsp;</td>
			</tr>                              
			<tr>
				<td align=\"center\" width=\"25%\">&nbsp;</td>                    
				<td align=\"center\" width=\"25%\">&nbsp;</td>
				<td align=\"center\" width=\"25%\">&nbsp;</td>
				<td align=\"center\" width=\"25%\">&nbsp;</td>
			</tr>
			<tr>
				<td align=\"center\" width=\"25%\"> </td>                    
				<td align=\"center\" width=\"25%\"><b><u>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</u></b>
				<td align=\"center\" width=\"25%\"><b><u>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</u></b>
				<td align=\"center\" width=\"25%\"><b><u>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</u></b>
			</tr>                              
			<tr>
				<td align=\"center\" width=\"25%\">&nbsp;</td>                    
				<td align=\"center\" width=\"25%\">&nbsp;</td>
				<td align=\"center\" width=\"25%\">&nbsp;</td>
				<td align=\"center\" width=\"25%\">&nbsp;</td>
			</tr>
		</table>";

			$data['prev'] = $chari;
			$judul = 'LAPORAN HARIAN KASIR PENDAFTARAN PER SHIFT';
			switch ($cetak) {
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
		} else {
			header('location:' . base_url());
		}
	}

	function ctk_105($cek = '', $thnn = '')
	{

		$cek          = $this->input->get('cekk');
		$chari   	  = '';
		$cekk         = $this->session->userdata('level');
		$unit   	  = $this->session->userdata('unit');
		$unit   	  = $this->session->userdata('unit');
		$profile      = $this->M_global->_LoadProfileLap();
		$nama_usaha   = $profile->nama_usaha;
		$motto        = '';
		$alamat       = '';
		$idlp         = $this->input->get('idlap');
		$tgl1         = $this->input->get('tgl1');
		$tgl2         = $this->input->get('tgl2');
		$dokter       = $this->input->get('dokter');
		$cab          = $this->input->get('cabang');
		$_tgl1        = date('Y-m-d', strtotime($tgl1));
		$_tgl2        = date('Y-m-d', strtotime($tgl2));
		$_peri        = 'Dari ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2));
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
				<img src=\"" . base_url() . "assets/img_user/".$this->session->userdata("avatar_cabang")."\"  width=\"100\" height=\"100\" /></td>

				<td colspan=\"20\"><b>
					<tr><td style=\"font-size:14px;border-bottom: none;\"><b>$namars</b></td></tr>
					<tr><td style=\"font-size:13px;\">$alamat</td></tr>
					<tr><td style=\"font-size:13px;\">$alamat2</td></tr>
					<tr><td style=\"font-size:13px;\">Wa :$whatsapp    Telp :$phone </td></tr>
					<tr><td style=\"font-size:13px;\">No. NPWP : $npwp</td></tr>

				</b></td>
			</tr> 
			 
			
			</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
                     
                <thead>
				<tr>
                    <td colspan=\"21\" style=\"text-align:center;font-size:22px;border-bottom: none;color:#120292;\"><b><br></b></td>
                </tr> 
                <tr>
                    <td colspan=\"21\" style=\"text-align:center;font-size:22px;border-bottom: none;color:#120292;\"><b>05 REKAP PENJUALAN HARIAN LENGKAP</b></td>
                </tr> 
                 
                <tr>
                    <td colspan=\"21\" style=\"text-align:center;font-size:15px;border-top: none;\">$_peri</td>
                </tr>
                
                </table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">

		<thead>
			<tr>
				<td style=\"border:0\" align=\"center\"><br></td>                
			</tr>
			<tr>
                <td bgcolor=\"#cccccc\" width=\"25%\" align=\"center\"><b>KETERANGAN</b></td>
                <td bgcolor=\"#cccccc\" width=\"25%\" align=\"center\"><b>JUMLAH</b></td>
                <td bgcolor=\"#cccccc\" width=\"25%\" align=\"center\"><b>NILAI</b></td>
            </tr>
            
		</thead>";

			$sql =
				"SELECT 'Order tunai' as keterangan,'1' as ket2, count(*) as jumlah, sum(bayarcash+bayarcard) as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalresep>0
		
		union all
		select 'Obat Cash' as keterangan,'2' as ket2, count(*) as jumlah, sum(bayarcash) as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalresep>0
		
		union all
		select 'Obat Card' as keterangan,'3' as ket2, count(*) as jumlah, sum(bayarcard) as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalresep>0
		
		union all
		select 'Tindakan Dokter' as keterangan,'1' as ket2, count(*) as jumlah, sum(totalpoli) as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
		
		union all
		select 'Biaya Cash' as keterangan,'2' as ket2, count(*) as jumlah, sum(bayarcash) as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
		
		union all
		select 'Biaya Card' as keterangan,'3' as ket2, count(*) as jumlah, sum(bayarcard) as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
		
		union all
		select 'Order Lokal' as keterangan,'1' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
		
		union all
		select 'Order Cash' as keterangan,'2' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
		
		union all
		select 'Order Card' as keterangan,'3' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
		
		union all
		select 'Order Kirim' as keterangan,'1' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
		
		union all
		select 'Kirim Cash' as keterangan,'2' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
		
		union all
		select 'Kirim Card' as keterangan,'3' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
		
		union all
		select 'Ongkos Kirim' as keterangan,'1' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
		
		union all
		select 'Ongkos Tunai' as keterangan,'2' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
		
		union all
		select 'Ongkos Card' as keterangan,'3' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
		
		union all
		select 'Spa' as keterangan,'1' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
		
		union all
		select 'Spa Cash' as keterangan,'2' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
		
		union all
		select 'Spa Card' as keterangan,'3' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
		
		union all
		select 'Produk Spa' as keterangan,'1' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
		
		union all
		select 'Produk Spa Cash' as keterangan,'2' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
		
		union all
		select 'Produk spa Card' as keterangan,'3' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
		
		union all
		select 'APOTIK' as keterangan,'1' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
		
		union all
		select 'Apotik Cash' as keterangan,'2' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
		
		union all
		select 'Apotik Card' as keterangan,'3' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0

	
		union all
		select 'Poli Gigi' as keterangan,'1' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
		
		union all
		select 'Gigi Cash' as keterangan,'2' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
		
		union all
		select 'Gigi Card' as keterangan,'3' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0

		union all
		select 'Obat Gigi' as keterangan,'1' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
		
		union all
		select 'obat Gigi Cash' as keterangan,'2' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
		
		union all
		select 'Obat Gigi Card' as keterangan,'3' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0

		union all
		select 'Biaya Administrasi' as keterangan,'1' as ket2, count(*), adm as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
		
		union all
		select 'Administrasi Cash' as keterangan,'2' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
		
		union all
		select 'Administrasi Card' as keterangan,'3' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0

		union all
		select 'Total Tunai' as keterangan,'1' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
		
		union all
		select 'Total Card' as keterangan,'2' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
		
		union all
		select 'Total Klaim Uang Muka' as keterangan,'3' as ket2, count(*), 0 as nilai
		from tbl_kasir where jenisbayar=1
		and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0

		
		";

			$query1    = $this->db->query($sql);

			$lcno          = 0;
			$keterangan    = 0;
			$jumlah        = 0;
			$nilai         = 0;
			$jum_nil       = 0;





			foreach ($query1->result() as $row) {
				$lcno         = $lcno + 1;
				$ket2         = $row->ket2;
				$keterangan   = $row->keterangan;
				$jumlah       = $row->jumlah;
				$nilai        = $row->nilai;
				$nil2         = angka_rp($row->nilai, 0);

				if ($ket2 == '2') {

					$chari .= "<tr>
				<td style=\"border-bottom:none;\" align=\"left\">&nbsp;&nbsp;&nbsp;&nbsp;$keterangan  </td>
				<td style=\"border-bottom:none;\" align=\"right\">$jumlah Transaksi  </td>
				<td style=\"border-bottom:none;\" align=\"right\">$nil2</td>
				</tr>
				";
				} else if ($ket2 == '3') {

					$chari .= "<tr>
				<td style=\"border-top:none;\" align=\"left\">&nbsp;&nbsp;&nbsp;&nbsp;$keterangan  </td>
				<td style=\"border-top:none;\" align=\"right\">$jumlah Transaksi  </td>
				<td style=\"border-top:none;\" align=\"right\">$nil2    </td>
				</tr>
				";
				} else {

					$chari .= "
				<tr>
					<td style=\"border-top:none;border-bottom:none;\" align=\"left\"></td>
					<td style=\"border-top:none;border-bottom:none;\" align=\"right\"></td>
					<td style=\"border-top:none;border-bottom:none;\" align=\"right\"></td>
				</tr>
				<tr>
					<td style=\"border-top:none;border-bottom:none;\" align=\"left\"><b>$keterangan</b></td>
					<td style=\"border-top:none;border-bottom:none;\" align=\"right\"><b>$jumlah Transaksi   </b></td>
					<td style=\"border-top:none;border-bottom:none;\" align=\"right\"><b>$nil2    </b></td>
				</tr>";
				}


				$jum_nil    += $nilai;
			}

			$jum2_nil        = angka_rp($jum_nil, 0);

			$chari .= "<tr>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>GRAND TOTAL </b></td>
				<td bgcolor=\"#cccccc\" align=\"right\"><b>$jumlah</b></td>
				<td bgcolor=\"#cccccc\" align=\"right\"><b>$jum2_nil</b></td>
				</tr>";
			$chari .= "
				<tr>
					<td style=\"border:0\" colspan=\"3\" align=\"center\">&nbsp;</td>
				</tr>";

			$chari .= "</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;\" width=\"100%\"  border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
			<tr>
				<td style=\"border-right:none;\" bgcolor=\"#cccccc\" align=\"center\"><b>Pasien Riil </b></td>
				<td style=\"border-left:none;\" bgcolor=\"#cccccc\" align=\"center\"><b>$jumlah ORANG</b></td>
				<td style=\"border-right:none;\" bgcolor=\"#cccccc\" align=\"center\"><b>Pasien Baru</b></td>
				<td style=\"border-left:none;\" bgcolor=\"#cccccc\" align=\"center\"><b>$jumlah ORANG</b></td>
				<td style=\"border-right:none;\" bgcolor=\"#cccccc\" align=\"center\"><b>Pasien Lama</b></td>
				<td style=\"border-left:none;\" bgcolor=\"#cccccc\" align=\"center\"><b>$jumlah ORANG</b></td>
			</tr>
			<tr>
				<td align=\"left\">- Kulit </td>
				<td align=\"right\">$jumlah ORANG</td>
				<td align=\"left\">- Kulit</td>
				<td align=\"right\">$jumlah ORANG</td>
				<td align=\"left\">- Kulit</td>
				<td align=\"right\">$jumlah ORANG</td>
			</tr>
			<tr>
				<td align=\"left\">- Gigi </td>
				<td align=\"right\">$jumlah ORANG</td>
				<td align=\"left\">- Gigi</td>
				<td align=\"right\">$jumlah ORANG</td>
				<td align=\"left\">- Gigi</td>
				<td align=\"right\">$jumlah ORANG</td>
			</tr>
			<tr>
				<td align=\"left\">- Spa </td>
				<td align=\"right\">$jumlah ORANG</td>
				<td align=\"left\">- Spa</td>
				<td align=\"right\">$jumlah ORANG</td>
				<td align=\"left\">- Spa</td>
				<td align=\"right\">$jumlah ORANG</td>
			</tr>
				";
			$chari .= "
				<tr>
					<td style=\"border:0\" colspan=\"6\" align=\"center\">&nbsp;</td>
				</tr>";

			$chari .= "</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;\" width=\"100%\"  border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
			<tr>
				<td colspan=\"4\" bgcolor=\"#cccccc\" align=\"center\"><b>Pasien Kulit berdasarkan lokal / tunai /kirim 
				<td colspan=\"2\" bgcolor=\"#cccccc\" align=\"center\"><b>$jumlah ORANG</b></td>
			</tr>
			<tr>
				<td align=\"left\">- Tunai </td>
				<td align=\"right\">$jumlah ORANG</td>
				<td align=\"left\">- Tunai Baru</td>
				<td align=\"right\">$jumlah ORANG</td>
				<td align=\"left\">- Tunai Lokal</td>
				<td align=\"right\">$jumlah ORANG</td>
			</tr>
			<tr>
				<td align=\"left\">- Kirim </td>
				<td align=\"right\">$jumlah ORANG</td>
				<td align=\"left\">- Kirim Baru</td>
				<td align=\"right\">$jumlah ORANG</td>
				<td align=\"left\">- Kirim Lokal</td>
				<td align=\"right\">$jumlah ORANG</td>
			</tr>
			<tr>
				<td align=\"left\">- Lokal </td>
				<td align=\"right\">$jumlah ORANG</td>
				<td align=\"left\">- Lokal Baru</td>
				<td align=\"right\">$jumlah ORANG</td>
				<td align=\"left\">- Lokal Lokal</td>
				<td align=\"right\">$jumlah ORANG</td>
			</tr>
				";
			$chari .= "</table>";

			$data['prev'] = $chari;
			$judul        = 'REKAP PENJUALAN HARIAN';

			switch ($cek) {
				case 0;
					echo ("<title>REKAP PENJUALAN HARIAN</title>");
					echo ($chari);
					break;

				case 1;
					$this->M_cetak->mpdf('P', 'A4', $judul, $chari, 'LAPORAN-KASIR-05.PDF', 10, 10, 10, 2);
					break;
				case 2;
					header("Cache-Control: no-cache, no-store, must-revalidate");
					header("Content-Type: application/vnd-ms-excel");
					header("Content-Disposition: attachment; filename= $judul.xlx");
					$this->load->view('app/master_cetak', $data);
					break;
			}
		} else {

			header('location:' . base_url());
		}
	}

	function ctk_106($cek = '', $thnn = '')
	{

		$cek          = $this->input->get('cekk');
		$chari   	  = '';
		$cekk         = $this->session->userdata('level');
		$unit   	  = $this->session->userdata('unit');
		$unit   	  = $this->session->userdata('unit');
		$profile      = $this->M_global->_LoadProfileLap();
		$nama_usaha   = $profile->nama_usaha;
		$motto        = '';
		$alamat       = '';
		$idlp         = $this->input->get('idlap');
		$tgl1         = $this->input->get('tgl1');
		$tgl2         = $this->input->get('tgl2');
		$dokter       = $this->input->get('dokter');
		$cab          = $this->input->get('cabang');
		$_tgl1        = date('Y-m-d', strtotime($tgl1));
		$_tgl2        = date('Y-m-d', strtotime($tgl2));
		$_peri        = 'Dari ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2));
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
				<img src=\"" . base_url() . "assets/img_user/".$this->session->userdata("avatar_cabang")."\"  width=\"100\" height=\"100\" /></td>

				<td colspan=\"20\"><b>
					<tr><td style=\"font-size:10px;border-bottom: none;\"><b>$namars</b></td></tr>
					<tr><td style=\"font-size:9px;\">$alamat</td></tr>
					<tr><td style=\"font-size:9px;\">$alamat2</td></tr>
					<tr><td style=\"font-size:9px;\">Wa :$whatsapp    Telp :$phone </td></tr>
					<tr><td style=\"font-size:9px;\">No. NPWP : $npwp</td></tr>

				</b></td>
			</tr> 
			 
			
			</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
                     
                <thead>
				<tr>
                    <td colspan=\"21\" style=\"text-align:center;font-size:22px;border-bottom: none;color:#120292;\"><b><br></b></td>
                </tr> 
                <tr>
                    <td colspan=\"21\" style=\"text-align:center;font-size:22px;border-bottom: none;color:#120292;\"><b>06 LAPORAN OMSET PER KELOMPOK UMUR</b></td>
                </tr> 
                 
                <tr>
                    <td colspan=\"21\" style=\"text-align:center;font-size:15px;border-top: none;\">$_peri</td>
                </tr>
                
                </table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">

		<thead>
			<tr>
				<td style=\"border:0\" align=\"center\"><br></td>                
			</tr>
			<tr>
                <td bgcolor=\"#cccccc\" width=\"25%\" align=\"center\"><b>RANGE</b></td>
                <td bgcolor=\"#cccccc\" width=\"25%\" align=\"center\"><b>KETERANGAN</b></td>
                <td bgcolor=\"#cccccc\" width=\"25%\" align=\"center\"><b>JML PASIEN</b></td>
                <td bgcolor=\"#cccccc\" width=\"25%\" align=\"center\"><b>NILAI RUPIAH</b></td>
            </tr>
            
		</thead>";

			$sql = 
				"SELECT 
					'<15' as xrange, 
					'UMUR <15' as keterangan, 
					coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 0 and 14 then 1 end),0) as jmlpasien, 
					coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 0 and 14 then tbl_kasir.totalsemua end),0) as omset
				from tbl_kasir 
				inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
				where tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2' and tbl_kasir.koders='$unit'

				union 
				
				SELECT 
					'15-20' as xrange,
					'UMUR 15-20' as keterangan,
					coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 15 and 20 then 1 end),0) as jmlpasien, 
					coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 15 and 20 then tbl_kasir.totalsemua end),0) as omset
				from tbl_kasir 
				inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
				where tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2' and tbl_kasir.koders='$unit'
				
				union 
				
				select 
					'21-25' as xrange,
					'UMUR 21-25' as keterangan,
					coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 21 and 25 then 1 end),0) as jmlpasien, 
					coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 21 and 25 then tbl_kasir.totalsemua end),0) as omset
				from tbl_kasir 
				inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
				where tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2' and tbl_kasir.koders='$unit'			
				
				union	
				
				select 
					'26-30' as xrange,
					'UMUR 26-30' as keterangan,
					coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 26 and 30 then 1 end),0) as jmlpasien, 
					coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 26 and 30 then tbl_kasir.totalsemua end),0) as omset
				from tbl_kasir 
				inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
				where tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2' and tbl_kasir.koders='$unit'	
				
				union	
				
				select 
					'31-35' as xrange,
					'UMUR 31-35' as keterangan,
					coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 31 and 35 then 1 end),0) as jmlpasien, 
					coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 31 and 35 then tbl_kasir.totalsemua end),0) as omset
				from tbl_kasir 
				inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
				where tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2' and tbl_kasir.koders='$unit'
				
				union	
				
				select 
					'36-40' as xrange,
					'UMUR 36-40' as keterangan,
					coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 36 and 40 then 1 end),0) as jmlpasien, 
					coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 46 and 40 then tbl_kasir.totalsemua end),0) as omset
				from tbl_kasir 
				inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
				where tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2' and tbl_kasir.koders='$unit'
				
				union	
				
				select 
					'>40' as xrange,
					'UMUR >40' as keterangan,
					coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE())>40 then 1 end),0) as jmlpasien, 
					coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE())>40 then tbl_kasir.totalsemua end),0) as omset
				from tbl_kasir 
				inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
				where tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2' and tbl_kasir.koders='$unit'	
		";

			$query1    = $this->db->query($sql);

			$lcno        = 0;
			$range       = 0;
			$ket         = 0;
			$jml_pas     = 0;
			$nil         = 0;
			$jum_nil     = 0;
			$jmll_pas    = 0;




			foreach ($query1->result() as $row) {
				$lcno       = $lcno + 1;
				$range      = $row->xrange;
				$ket        = $row->keterangan;
				$jml_pas    = $row->jmlpasien;
				$nil        = $row->omset;
				$nil2        = angka_rp($row->omset, 0);

				$chari .= "<tr>
				<td align=\"center\">$range</td>
				<td align=\"left\">$ket  </td>
				<td align=\"right\">$jml_pas    </td>
				<td align=\"right\">$nil2    </td>
				</tr>";

				$jum_nil    += $nil;
				$jmll_pas   += $jml_pas;
			}

			$jum2_nil        = angka_rp($jum_nil, 0);

			$chari .= "<tr>
				<td bgcolor=\"#cccccc\" colspan=\"2\" align=\"center\"><b>TOTAL </b></td>
				<td bgcolor=\"#cccccc\" align=\"right\"><b>$jmll_pas</b></td>
				<td bgcolor=\"#cccccc\" align=\"right\"><b>$jum2_nil</b></td>
				</tr>";



			$chari .= "</table>";

			$data['prev'] = $chari;
			$judul        = 'LAPORAN OMSET PER KELOMPOK UMUR';

			switch ($cek) {
				case 0;
					echo ("<title>LAPORAN OMSET PER KELOMPOK UMUR</title>");
					echo ($chari);
					break;

				case 1;
					$this->M_cetak->mpdf('P', 'A4', '', $chari, 'LAPORAN-KASIR-06.PDF', 10, 10, 10, 2);
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

	function ctk_107($cek = '', $thnn = '')
	{

		$cek          = $this->input->get('cekk');
		$chari   	  = '';
		$cekk         = $this->session->userdata('level');
		$unit   	  = $this->session->userdata('unit');
		$unit   	  = $this->session->userdata('unit');
		$profile      = $this->M_global->_LoadProfileLap();
		$nama_usaha   = $profile->nama_usaha;
		$motto        = '';
		$alamat       = '';
		$idlp         = $this->input->get('idlap');
		$tgl1         = $this->input->get('tgl1');
		$tgl2         = $this->input->get('tgl2');
		$dokter       = $this->input->get('dokter');
		$cab          = $this->input->get('cabang');
		$_tgl1        = date('Y-m-d', strtotime($tgl1));
		$_tgl2        = date('Y-m-d', strtotime($tgl2));
		$_peri        = 'Dari ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2));
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
				<img src=\"" . base_url() . "assets/img_user/".$this->session->userdata("avatar_cabang")."\"  width=\"100\" height=\"100\" /></td>

				<td colspan=\"20\"><b>
					<tr><td style=\"font-size:14px;border-bottom: none;\"><b>$namars</b></td></tr>
					<tr><td style=\"font-size:13px;\">$alamat</td></tr>
					<tr><td style=\"font-size:13px;\">$alamat2</td></tr>
					<tr><td style=\"font-size:13px;\">Wa :$whatsapp    Telp :$phone </td></tr>
					<tr><td style=\"font-size:13px;\">No. NPWP : $npwp</td></tr>

				</b></td>
			</tr> 
			 
			
			</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
                     
                <thead>
				<tr>
                    <td colspan=\"21\" style=\"text-align:center;font-size:22px;border-bottom: none;color:#120292;\"><b><br></b></td>
                </tr> 
                <tr>
                    <td colspan=\"21\" style=\"text-align:center;font-size:22px;border-bottom: none;color:#120292;\"><b>07 LAPORAN PENDAPATAN REKAP UANG MUKA</b></td>
                </tr> 
                 
                <tr>
                    <td colspan=\"21\" style=\"text-align:center;font-size:15px;border-top: none;\">$_peri</td>
                </tr>
                
                </table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">

		<thead>
			<tr>
				<td style=\"border:0\" align=\"center\"><br></td>                
			</tr>
			<tr>
                <td rowspan=\"2\" bgcolor=\"#cccccc\" width=\"3%\" align=\"center\"><b>NO</b></td>                
                <td rowspan=\"2\"  bgcolor=\"#cccccc\" width=\"7%\" align=\"center\"><b>#MEMBER</b></td>
                <td rowspan=\"2\"  bgcolor=\"#cccccc\" width=\"30%\" align=\"center\"><b>NAMA PX</b></td>
				
                <td colspan=\"3\" bgcolor=\"#cccccc\" width=\"60%\" align=\"center\"><b>UANG MUKA</b></td>
            </tr>
			<tr>
                <td bgcolor=\"#cccccc\" width=\"15%\" align=\"center\"><b>BELUM TERPAKAI</b></td>
                <td bgcolor=\"#cccccc\" width=\"15%\" align=\"center\"><b>BEBAN TRX</b></td>
                <td bgcolor=\"#cccccc\" width=\"10%\" align=\"center\"><b>SISA DP</b></td>
            </tr>
            
		</thead>";

			$sql = $query =
				"SELECT rekmed,sum(masuk+kembali)masuk,sum(keluar)keluar,koders,namapas FROM( 
		SELECT a.rekmed,a.kembali, a.bayarcash,
		case when posbayar='UANG_MUKA' then uangmuka else 0 end masuk , 
		case when posbayar<>'UANG_MUKA' then uangmuka else 0 end	keluar,
		a.koders,(select b.namapas from tbl_pasien b where a.rekmed=b.rekmed)namapas 
		from tbl_kasir a
		where tglbayar between '$_tgl1' and '$_tgl2' and a.koders='$unit' and a.bayarcash != 0
		)a where a.masuk<>0 or a.keluar<>0
		GROUP BY rekmed,koders,namapas
		ORDER BY rekmed
		";

			$query1    = $this->db->query($sql);

			$lcno        = 0;
			$tgl         = 0;
			$rekmed      = 0;
			$nm          = 0;
			$nokwi       = 0;
			$masuk       = 0;
			$digunakan   = 0;
			$jum_in      = 0;
			$jum_out     = 0;
			$jum_sisa    = 0;
			$sisa1       = 0;




			foreach ($query1->result() as $row) {
				$lcno         = $lcno + 1;
				$rekmed       = $row->rekmed;
				$nm           = $row->namapas;
				$masuk        = angka_rp($row->masuk, 0);
				$digunakan    = angka_rp($row->keluar, 0);
				$sisa    	  = angka_rp($row->masuk - $row->keluar, 0);

				$chari .= "<tr>
				<td align=\"center\">$lcno</td>
				<td align=\"center\">$rekmed</td>
				<td align=\"left\">$nm</td>
				<td align=\"right\">$masuk</td>
				<td align=\"right\">$digunakan</td>
				<td align=\"right\">$sisa </td>
				</tr>";

				$jum_in   += $row->masuk;
				$jum_out  += $row->keluar;
				$jum_sisa += $row->masuk - $row->keluar;
			}

			$jum2_in   = angka_rp($jum_in, 0);
			$jum2_out  = angka_rp($jum_out, 0);
			$jum2_sisa = angka_rp($jum_sisa, 0);

			$chari .= "<tr>
				<td bgcolor=\"#cccccc\" colspan=\"3\" align=\"center\"><b>TOTAL </b></td>
				<td bgcolor=\"#cccccc\" align=\"right\"><b>$jum2_in</b></td>
				<td bgcolor=\"#cccccc\" align=\"right\"><b>$jum2_out</b></td>
				<td bgcolor=\"#cccccc\" align=\"right\"><b>$jum2_sisa</b></td>
				</tr>";



			$chari .= "</table>";

			$data['prev'] = $chari;
			$judul        = 'REKAP UANG MUKA';


			switch ($cek) {
				case 0;
					echo ("<title>REKAP UANG MUKA</title>");
					echo ($chari);
					break;

				case 1;
					$this->M_cetak->mpdf('L', 'A3', $judul, $chari, 'LAPORAN-KASIR-07.PDF', 10, 10, 10, 2);
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

	function ctk_108($cek = '', $thnn = '')
	{

		$cek          = $this->input->get('cekk');
		$chari   	  = '';
		$cekk         = $this->session->userdata('level');
		$unit   	  = $this->session->userdata('unit');
		$unit   	  = $this->session->userdata('unit');
		$profile      = $this->M_global->_LoadProfileLap();
		$nama_usaha   = $profile->nama_usaha;
		$motto        = '';
		$alamat       = '';
		$idlp         = $this->input->get('idlap');
		$tgl1         = $this->input->get('tgl1');
		$tgl2         = $this->input->get('tgl2');
		$dokter       = $this->input->get('dokter');
		$cab          = $this->input->get('cabang');
		$_tgl1        = date('Y-m-d', strtotime($tgl1));
		$_tgl2        = date('Y-m-d', strtotime($tgl2));
		$_peri        = 'Dari ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2));
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
				<img src=\"" . base_url() . "assets/img_user/".$this->session->userdata("avatar_cabang")."\"  width=\"100\" height=\"100\" /></td>

				<td colspan=\"20\"><b>
					<tr><td style=\"font-size:14px;border-bottom: none;\"><b>$namars</b></td></tr>
					<tr><td style=\"font-size:13px;\">$alamat</td></tr>
					<tr><td style=\"font-size:13px;\">$alamat2</td></tr>
					<tr><td style=\"font-size:13px;\">Wa :$whatsapp    Telp :$phone </td></tr>
					<tr><td style=\"font-size:13px;\">No. NPWP : $npwp</td></tr>

				</b></td>
			</tr> 
			 
			
			</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
                     
                <thead>
				<tr>
                    <td colspan=\"21\" style=\"text-align:center;font-size:22px;border-bottom: none;color:#120292;\"><b><br></b></td>
                </tr> 
                <tr>
                    <td colspan=\"21\" style=\"text-align:center;font-size:22px;border-bottom: none;color:#120292;\"><b>08 LAPORAN PENDAPATAN DETAIL UANG MUKA</b></td>
                </tr> 
                 
                <tr>
                    <td colspan=\"21\" style=\"text-align:center;font-size:15px;border-top: none;\">$_peri</td>
                </tr>
                
                </table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">

		<thead>
			<tr>
				<td style=\"border:0\" align=\"center\"><br></td>                
			</tr>
			<tr>
				<td rowspan=\"2\" bgcolor=\"#cccccc\" width=\"5%\" align=\"center\"><b>NO</b></td>                
				<td rowspan=\"2\"  bgcolor=\"#cccccc\" width=\"10%\" align=\"center\"><b>#MEMBER</b></td>
				<td rowspan=\"2\"  bgcolor=\"#cccccc\" width=\"20%\" align=\"center\"><b>NAMA PX</b></td><td rowspan=\"2\"  bgcolor=\"#cccccc\" width=\"10%\" align=\"center\"><b>TGL TRX</b></td>
				<td rowspan=\"2\"  bgcolor=\"#cccccc\" width=\"15%\" align=\"center\"><b>NO. KWITANSI</b></td>
				<td colspan=\"2\" bgcolor=\"#cccccc\" width=\"30%\" align=\"center\"><b>UANG MUKA</b></td>
				<td rowspan=\"2\" bgcolor=\"#cccccc\" width=\"10%\" align=\"center\"><b>KETERANGAN</b></td>
			</tr>
			<tr>
                <td bgcolor=\"#cccccc\" width=\"10%\" align=\"center\"><b>BELUM TERPAKAI</b></td>
                <td bgcolor=\"#cccccc\" width=\"10%\" align=\"center\"><b>BEBAN TRX</b></td>
                
            </tr>
            
		</thead>";

			$sql = "SELECT koders,
					namapas,
					rekmed,
					concat(left(tglbayar,10),' ',jambayar) tglbayar,
					jambayar,
					nokwitansi,
					sum(masuk) masuk,
					sum(keluar) keluar,
					ket FROM(SELECT (select b.namapas from tbl_pasien b where a.rekmed=b.rekmed) namapas,
					a.rekmed,
					a.nokwitansi,
					a.tglbayar,
					a.kembali,
					jambayar,
					case when posbayar = 'UANG_MUKA' then uangmuka else 0 end masuk, 
					0 keluar,
					'UM. ASLI' ket,
					a.koders
				from tbl_kasir a
				where tglbayar between '$_tgl1' and '$_tgl2' and a.koders='$unit' and lainket not like'%sisa kembalian%' AND nokwitansi IN (SELECT nokwitansi FROM tbl_uangmuka)
				
				union ALL
				
				SELECT 
					(select b.namapas from tbl_pasien b where a.rekmed=b.rekmed) namapas,
					a.rekmed,
					a.nokwitansi,
					a.tglbayar,
					a.kembali,
					jambayar,
					case when posbayar = 'UANG_MUKA' and lainket like '%sisa kembalian%' then uangmuka else 0 end masuk,
					0 keluar,
					'UM. KEMBALIAN' ket,
					a.koders
				from tbl_kasir a
				where tglbayar between '$_tgl1' and '$_tgl2' and a.koders='$unit'  and lainket like'%sisa kembalian%' AND nokwitansi IN (SELECT nokwitansi FROM tbl_uangmuka)
				
				union ALL
				
				SELECT 
					(select b.namapas from tbl_pasien b where a.rekmed=b.rekmed) namapas,
					a.rekmed,
					a.nokwitansi,
					a.tglbayar,
					a.kembali,
					jambayar,
					0 masuk,
					case when posbayar <> 'UANG_MUKA' then uangmuka else 0 end	keluar,
					'UM. KELUAR'ket,
					a.koders
				from tbl_kasir a
				where tglbayar between '$_tgl1' and '$_tgl2' and a.koders='$unit' and lainket not like'%sisa kembalian%' AND nokwitansi IN (SELECT nokwitansi FROM tbl_uangmuka)
			) a 
			where (a.masuk <> 0 or a.keluar <> 0) 
			group by koders,namapas,rekmed,tglbayar,jambayar,nokwitansi,ket
			ORDER BY rekmed,tglbayar,nokwitansi,jambayar
			";

			$query1    = $this->db->query($sql);

			$lcno        = 0;
			$tgl         = 0;
			$rekmed      = 0;
			$nm          = 0;
			$nokwi       = 0;
			$ket         = 0;
			$masuk       = 0;
			$digunakan   = 0;
			$jum_in      = 0;
			$jum_out     = 0;
			$jum_sisa    = 0;
			$sisa1       = 0;




			foreach ($query1->result() as $row) {
				$lcno         = $lcno + 1;
				$tgl          = $row->tglbayar;
				$rekmed       = $row->rekmed;
				$nm           = $row->namapas;
				$nokwi        = $row->nokwitansi;
				$kett         = $row->ket;
				// $sisa1    	  = $row->masuk-$row->keluar;
				$masuk        = angka_rp($row->masuk, 0);
				$digunakan    = angka_rp($row->keluar, 0);
				$sisa    	  = angka_rp($row->masuk - $row->keluar, 0);

				$chari .= "<tr>
				<td align=\"center\">$lcno</td>
				<td align=\"center\">$rekmed</td>
				<td align=\"left\">$nm</td>
				<td align=\"center\">$tgl</td>
				<td align=\"center\">$nokwi</td>
				<td align=\"right\">$masuk</td>
				<td align=\"right\">$digunakan</td>
				<td align=\"left\"><b>&nbsp;&nbsp;&nbsp;$kett </b></td>
				</tr>";

				$jum_in   += $row->masuk;
				$jum_out  += $row->keluar;
				$jum_sisa += $row->masuk - $row->keluar;
			}

			$jum2_in   = angka_rp($jum_in, 0);
			$jum2_out  = angka_rp($jum_out, 0);
			$jum2_sisa = angka_rp($jum_sisa, 0);

			$chari .= "<tr>
				<td style=\"font-size:15px\" bgcolor=\"#cccccc\" colspan=\"5\" align=\"center\"><b>TOTAL </b></td>
				<td style=\"font-size:15px\" bgcolor=\"#cccccc\" align=\"right\"><b>$jum2_in</b></td>
				<td style=\"font-size:15px\" bgcolor=\"#cccccc\" align=\"right\"><b>$jum2_out</b></td>
				<td bgcolor=\"#cccccc\" align=\"right\"><b></b></td>
				</tr>";



			$chari .= "</table>";

			$data['prev'] = $chari;
			$judul        = 'DETAIL UANG MUKA';


			switch ($cek) {
				case 0;
					echo ("<title>DETAIL UANG MUKA</title>");
					echo ($chari);
					break;

				case 1;
					$this->M_cetak->mpdf('L', 'A3', $judul, $chari, 'LAPORAN-KASIR-08.PDF', 10, 10, 10, 2);
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