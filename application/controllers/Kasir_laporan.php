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
	}

	public function index()
	{
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$unit = $this->session->userdata('unit');
			$this->load->helper('url');
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
				$query =
				"SELECT 'Order Tunai' as keterangan, count(*) as jumlah, sum(bayarcash+bayarcard) as nilai
			from tbl_kasir 
			where jenisbayar=1 and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and noreg is not null

			union all

			SELECT '   Order Cash' as keterangan, count(*) as jumlah, sum(bayarcash) as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and nokwitansi not in (select nokwitansi from tbl_kartukredit)

			union all

			SELECT '   Order Card' as keterangan, count(*) as jumlah, sum(bayarcard) as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2'  and nokwitansi in (select nokwitansi from tbl_kartukredit)

			union all

			SELECT 'Tindakan Dokter' as keterangan, count(*) as jumlah, sum(totalpoli) as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli > 0

			union all

			SELECT '   Order Cash' as keterangan, count(*) as jumlah, sum(bayarcash) as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli > 0 and nokwitansi not in (select nokwitansi from tbl_kartukredit)

			union all

			SELECT '   Order Card' as keterangan, count(*) as jumlah, sum(bayarcard) as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli > 0 and nokwitansi in (select nokwitansi from tbl_kartukredit)

			union all

			SELECT 'Order Lokal' AS keterangan, COUNT(*) AS jumlah, SUM(bayarcash+bayarcard) AS nilai
			FROM tbl_kasir
			JOIN tbl_apohresep ON tbl_kasir.nokwitansi = tbl_apohresep.nokwitansi
			WHERE tbl_kasir.koders = '$unit' and totalpoli > 0 AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2' AND tbl_kasir.noreg != '' AND tbl_apohresep.jenispas = '2'

			union all

			SELECT '   Order Cash' AS keterangan, COUNT(*) AS jumlah, SUM(bayarcash) AS nilai
			FROM tbl_kasir
			JOIN tbl_apohresep ON tbl_kasir.nokwitansi = tbl_apohresep.nokwitansi
			WHERE tbl_kasir.koders = '$unit' and totalpoli > 0 AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2' AND tbl_kasir.noreg != '' AND tbl_apohresep.jenispas = '2' and tbl_kasir.nokwitansi not in (select nokwitansi from tbl_kartukredit)

			union all

			SELECT '   Order Card' AS keterangan, COUNT(*) AS jumlah, SUM(bayarcard) AS nilai
			FROM tbl_kasir
			JOIN tbl_apohresep ON tbl_kasir.nokwitansi = tbl_apohresep.nokwitansi
			WHERE tbl_kasir.koders = '$unit' and totalpoli > 0 AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2' AND tbl_kasir.noreg != '' AND tbl_apohresep.jenispas = '2' and tbl_kasir.nokwitansi in (select nokwitansi from tbl_kartukredit)

			union all

			SELECT 'Order Kirim' AS keterangan, COUNT(*) AS jumlah, SUM(bayarcash+bayarcard) AS nilai
			FROM tbl_kasir
			JOIN tbl_apohresep ON tbl_kasir.nokwitansi = tbl_apohresep.nokwitansi
			WHERE tbl_kasir.koders = '$unit' and totalpoli > 0 AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2' AND tbl_kasir.noreg != '' AND tbl_apohresep.jenispas = '3'

			union all

			SELECT '   Order Cash' AS keterangan, COUNT(*) AS jumlah, SUM(bayarcash) AS nilai
			FROM tbl_kasir
			JOIN tbl_apohresep ON tbl_kasir.nokwitansi = tbl_apohresep.nokwitansi
			WHERE tbl_kasir.koders = '$unit' and totalpoli > 0 AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2' AND tbl_kasir.noreg != '' AND tbl_apohresep.jenispas = '3' and tbl_kasir.nokwitansi not in (select nokwitansi from tbl_kartukredit)

			union all

			SELECT '   Order Card' AS keterangan, COUNT(*) AS jumlah, SUM(bayarcard) AS nilai
			FROM tbl_kasir
			JOIN tbl_apohresep ON tbl_kasir.nokwitansi = tbl_apohresep.nokwitansi
			WHERE tbl_kasir.koders = '$unit' and totalpoli > 0 AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2' AND tbl_kasir.noreg != '' AND tbl_apohresep.jenispas = '3' and tbl_kasir.nokwitansi in (select nokwitansi from tbl_kartukredit)

			union all

			SELECT 'Ongkos Kirim' AS keterangan, COUNT(*) AS jumlah, SUM(bayarcash+bayarcard) AS nilai
			FROM tbl_kasir
			JOIN tbl_apohresep ON tbl_kasir.nokwitansi = tbl_apohresep.nokwitansi
			WHERE tbl_kasir.koders = '$unit' and totalpoli < 1 AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2' AND tbl_kasir.noreg != '' AND tbl_apohresep.jenispas = '3'

			union all

			SELECT '   Order Cash' AS keterangan, COUNT(*) AS jumlah, SUM(bayarcash) AS nilai
			FROM tbl_kasir
			JOIN tbl_apohresep ON tbl_kasir.nokwitansi = tbl_apohresep.nokwitansi
			WHERE tbl_kasir.koders = '$unit' and totalpoli < 1 AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2' AND tbl_kasir.noreg != '' AND tbl_apohresep.jenispas = '3' and tbl_kasir.nokwitansi not in (select nokwitansi from tbl_kartukredit)

			union all

			SELECT '   Order Card' AS keterangan, COUNT(*) AS jumlah, SUM(bayarcard) AS nilai
			FROM tbl_kasir
			JOIN tbl_apohresep ON tbl_kasir.nokwitansi = tbl_apohresep.nokwitansi
			WHERE tbl_kasir.koders = '$unit' and totalpoli < 1 AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2' AND tbl_kasir.noreg != '' AND tbl_apohresep.jenispas = '3' and tbl_kasir.nokwitansi in (select nokwitansi from tbl_kartukredit)

			union all

			SELECT 'SPA' AS keterangan, COUNT(*) AS jumlah, SUM(bayarcash+bayarcard) AS nilai
			FROM tbl_kasir
			JOIN tbl_apohresep ON tbl_kasir.nokwitansi = tbl_apohresep.nokwitansi
			WHERE tbl_kasir.koders = '$unit' and totalpoli > 0 AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2' AND tbl_kasir.noreg != '' AND tbl_apohresep.jenispas = '4'

			union all

			SELECT '   Order Cash' AS keterangan, COUNT(*) AS jumlah, SUM(bayarcash) AS nilai
			FROM tbl_kasir
			JOIN tbl_apohresep ON tbl_kasir.nokwitansi = tbl_apohresep.nokwitansi
			WHERE tbl_kasir.koders = '$unit' and totalpoli > 0 AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2' AND tbl_kasir.noreg != '' AND tbl_apohresep.jenispas = '4' and tbl_kasir.nokwitansi not in (select nokwitansi from tbl_kartukredit)

			union all

			SELECT '   Order Card' AS keterangan, COUNT(*) AS jumlah, SUM(bayarcard) AS nilai
			FROM tbl_kasir
			JOIN tbl_apohresep ON tbl_kasir.nokwitansi = tbl_apohresep.nokwitansi
			WHERE tbl_kasir.koders = '$unit' and totalpoli > 0 AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2' AND tbl_kasir.noreg != '' AND tbl_apohresep.jenispas = '4' and tbl_kasir.nokwitansi in (select nokwitansi from tbl_kartukredit)

			union all

			SELECT 'Produk SPA' AS keterangan, COUNT(*) AS jumlah, SUM(bayarcash+bayarcard) AS nilai
			FROM tbl_kasir
			JOIN tbl_apohresep ON tbl_kasir.nokwitansi = tbl_apohresep.nokwitansi
			WHERE tbl_kasir.koders = '$unit' and totalpoli < 1 AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2' AND tbl_kasir.noreg != '' AND tbl_apohresep.jenispas = '4'

			union all

			SELECT '   Order Cash' AS keterangan, COUNT(*) AS jumlah, SUM(bayarcash) AS nilai
			FROM tbl_kasir
			JOIN tbl_apohresep ON tbl_kasir.nokwitansi = tbl_apohresep.nokwitansi
			WHERE tbl_kasir.koders = '$unit' and totalpoli < 1 AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2' AND tbl_kasir.noreg != '' AND tbl_apohresep.jenispas = '4' and tbl_kasir.nokwitansi not in (select nokwitansi from tbl_kartukredit)

			union all

			SELECT '   Order Card' AS keterangan, COUNT(*) AS jumlah, SUM(bayarcard) AS nilai
			FROM tbl_kasir
			JOIN tbl_apohresep ON tbl_kasir.nokwitansi = tbl_apohresep.nokwitansi
			WHERE tbl_kasir.koders = '$unit' and totalpoli < 1 AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2' AND tbl_kasir.noreg != '' AND tbl_apohresep.jenispas = '4' and tbl_kasir.nokwitansi in (select nokwitansi from tbl_kartukredit)

			union all

			SELECT 'Apotik' AS keterangan, COUNT(*) AS jumlah, SUM(bayarcash+bayarcard) AS nilai
			FROM tbl_kasir
			JOIN tbl_apohresep ON tbl_kasir.nokwitansi = tbl_apohresep.nokwitansi
			WHERE tbl_kasir.koders = '$unit' and totalpoli > 0 AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2' AND tbl_kasir.noreg != '' AND tbl_apohresep.jenispas = '7'

			union all

			SELECT '   Order Cash' AS keterangan, COUNT(*) AS jumlah, SUM(bayarcash) AS nilai
			FROM tbl_kasir
			JOIN tbl_apohresep ON tbl_kasir.nokwitansi = tbl_apohresep.nokwitansi
			WHERE tbl_kasir.koders = '$unit' and totalpoli > 0 AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2' AND tbl_kasir.noreg != '' AND tbl_apohresep.jenispas = '7' and tbl_kasir.nokwitansi not in (select nokwitansi from tbl_kartukredit)

			union all

			SELECT '   Order Card' AS keterangan, COUNT(*) AS jumlah, SUM(bayarcard) AS nilai
			FROM tbl_kasir
			JOIN tbl_apohresep ON tbl_kasir.nokwitansi = tbl_apohresep.nokwitansi
			WHERE tbl_kasir.koders = '$unit' and totalpoli > 0 AND tbl_kasir.tglbayar BETWEEN '$_tgl1' AND '$_tgl2' AND tbl_kasir.noreg != '' AND tbl_apohresep.jenispas = '7' and tbl_kasir.nokwitansi in (select nokwitansi from tbl_kartukredit)
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
				$bulan = date('n', strtotime($tgl1));
				$tahun = date('Y', strtotime($tgl2));
				$query =
					"select tbl_kasir.*, tbl_pasien.*, tbl_apoposting.resepno from tbl_kasir 
			  inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
			  left outer join tbl_apoposting on tbl_kasir.nokwitansi=tbl_apoposting.nokwitansi
			  where tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2'
			 ";


				$query .= "order by tbl_kasir.tglbayar";
				$lap = $this->db->query($query)->result();
				$pdf = new simkeu_rpt();
				$pdf->setID($nama_usaha, $motto, $alamat);
				$pdf->setunit($namaunit);
				$pdf->setjudul('03 REKAP JASA DAN PENJUALAN');
				$pdf->setsubjudul($_peri);
				$pdf->addpage("L", "A4");
				$pdf->setsize("L", "A4");
				$pdf->SetWidths(array(32, 22, 35, 20,25, 25, 20, 20, 20, 20, 20, 30, 20));
				$pdf->SetAligns(array('C', 'C', 'C','C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
				$judul = array('TR No', 'Tanggal', 'Pro', 'Adm', 'Jasa', 'Obat Tunai', 'Lokal', 'Kirim', 'Obat Spa', 'Apotek', 'Obat Gigi', 'Total', 'No. Resep');
				$pdf->setfont('Arial', 'B', 8);
				$pdf->row($judul);

				$pdf->SetWidths(array(32, 22, 35, 20,25, 25, 20, 20, 20, 20, 20, 30, 20));
				$pdf->SetAligns(array('C', 'C', 'L', 'R','R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'L'));
				$pdf->setfont('Arial', '', 8);
				$pdf->SetFillColor(224, 235, 255);
				$pdf->SetTextColor(0);
				$pdf->SetFont('');

				$nourut = 1;
				$tot1 = $tot2 = $tot3 = $tot4 = $tot5 = $tot6 = $tot7 = $tot8 = 0;
				foreach ($lap as $db) {
					$tot1 += $db->adm + $db->totalpoli;
					$tot6 += $db->totalresep;
					$tot8 += $db->totalsemua;

					$pdf->row(array(
						$db->noreg,
						tanggal($db->tglbayar),
						$db->namapas,
						angka_rp($db->adm, 0),
						angka_rp($db->totalpoli, 0),
						angka_rp(0, 0),
						angka_rp(0, 0),
						angka_rp(0, 0),
						angka_rp(0, 0),
						angka_rp($db->totalresep, 0),
						angka_rp(0, 0),
						angka_rp($db->totalsemua, 0),
						$db->resepno


					));

					$nourut++;
				}
				$pdf->setfont('Arial', 'B', 8);
				$pdf->SetWidths(array(89, 20, 25, 20, 20, 20, 20, 20, 30, 20));
				$pdf->SetAligns(array('L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'L'));
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
					$db->resepno


				));
				$pdf->SetTitle('03 REKAP JASA DAN PENJUALAN');
				$pdf->AliasNbPages();
				$pdf->output('KASIR-03.PDF', 'I');
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

	function ctk_101($cek = '', $thnn = '')
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
                    <td colspan=\"21\" style=\"text-align:center;font-size:22px;border-bottom: none;color:#120292;\"><b>01 LAPORAN HARIAN KASIR PENDAPATAN PER SHIFT</b></td>
                </tr> 
                 
                <tr>
                    <td colspan=\"21\" style=\"text-align:center;font-size:15px;border-top: none;\">$_peri</td>
                </tr>
                
                </table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">

		<thead>
			<tr>
				<td style=\"border:0\" colspan=\"21\" align=\"center\"><br></td>                
			</tr>
			<tr>
                <td rowspan=\"2\" bgcolor=\"#cccccc\" width=\"2%\" align=\"center\"><b>No.</b></td>
                <td rowspan=\"2\" bgcolor=\"#cccccc\" width=\"5%\" align=\"center\"><b>Kwitansi</b></td>
                <td rowspan=\"2\" bgcolor=\"#cccccc\" width=\"5%\" align=\"center\"><b>Tangal</b></td>
                <td rowspan=\"2\" bgcolor=\"#cccccc\" width=\"7%\" align=\"center\"><b>Nama Pasien</b></td>
                <td rowspan=\"2\" bgcolor=\"#cccccc\" width=\"3%\" align=\"center\"><b>Adm</b></td>
                <td rowspan=\"2\" bgcolor=\"#cccccc\" width=\"4%\" align=\"center\"><b>U.Muka</b></td>
                <td rowspan=\"2\" bgcolor=\"#cccccc\" width=\"4%\" align=\"center\"><b>J.Kulit</b></td>
                <td rowspan=\"2\" bgcolor=\"#cccccc\" width=\"4%\" align=\"center\"><b>Resep</b></td>
                <td rowspan=\"2\" bgcolor=\"#cccccc\" width=\"4%\" align=\"center\"><b>R/Label</b></td>
                <td rowspan=\"2\" bgcolor=\"#cccccc\" width=\"4%\" align=\"center\"><b>J.Gigi</b></td>
                <td rowspan=\"2\" bgcolor=\"#cccccc\" width=\"4%\" align=\"center\"><b>Lain-lain</b></td>
                <td rowspan=\"2\" bgcolor=\"#cccccc\" width=\"4%\" align=\"center\"><b>Ord. Kirim</b></td>
                <td rowspan=\"2\" bgcolor=\"#cccccc\" width=\"4%\" align=\"center\"><b>Ongkir</b></td>
                <td rowspan=\"2\" bgcolor=\"#cccccc\" width=\"4%\" align=\"center\"><b>J.Spa</b></td>
                <td rowspan=\"2\" bgcolor=\"#cccccc\" width=\"4%\" align=\"center\"><b>R/Spa</b></td>
                <td rowspan=\"2\" bgcolor=\"#cccccc\" width=\"4%\" align=\"center\"><b>Apotek</b></td>
                <td rowspan=\"2\" bgcolor=\"#cccccc\" width=\"5%\" align=\"center\"><b>Total</b></td>
                <td rowspan=\"2\" bgcolor=\"#cccccc\" width=\"7%\" align=\"center\"><b>Selisih</b></td>
                <td colspan=\"2\"  bgcolor=\"#cccccc\" width=\"5%\" align=\"center\"><b>Diskon</b></td>
                <td rowspan=\"2\" bgcolor=\"#cccccc\" width=\"5%\" align=\"center\"><b>Voucher</b></td>
                <td rowspan=\"2\" bgcolor=\"#cccccc\" width=\"5%\" align=\"center\"><b>Total Net</b></td>
                <td rowspan=\"2\" bgcolor=\"#cccccc\" width=\"5%\" align=\"center\"><b>Cara Bayar</b></td>
            </tr>
			<tr>
                <td bgcolor=\"#cccccc\" width=\"5%\" align=\"center\"><b>Resep</b></td>
                <td bgcolor=\"#cccccc\" width=\"5%\" align=\"center\"><b>Tin Kon</b></td>
            </tr>
            
		</thead>";

			$sql = "
		SELECT 
			a.id,
			a.koders,
			nokwitansi,
			b.idtr,
			a.noreg,
			c.kodepos,
			a.rekmed,
			b.preposisi,
			b.namapas,
			CONCAT(left(tglbayar,10),' ',jambayar)tglbayar,
			jambayar,
			adm,
			totalresep as totalresep,
			totalpoli,
			(case when c.kodepos='KULIT' THEN totalpoli else 0 end)jkulit,
			(case when c.kodepos='GIGI' THEN totalpoli else 0 end)jgigi,
			(case when c.kodepos='SPA' THEN totalpoli else 0 end)jspa,
			(case when posbayar='UANG_MUKA' THEN uangmuka else ABS(uangmuka)*-1 end )uangmuka,
			totalsemua, IFNULL(diskonresep,0)diskonresep,
			voucherrp1,voucherrp2,voucherrp3,diskonrp,
			(case 
				when jenisbayar='1' THEN 'Cash'
				when jenisbayar='2' THEN 'Credit Card'
				when jenisbayar='3' THEN 'Debet Card'
				when jenisbayar='4' THEN 'Transfer'
				when jenisbayar='5' THEN 'Online' else 'Virtual' end 
			)nmjenisbayar,
			jenisbayar,
			lain,
			bayarcash,
			bayarcard,
			totalbayar,
			kembali,
			posbayar,
			selisihrp
		from tbl_kasir as a
		inner join tbl_pasien b on a.rekmed=b.rekmed 
		LEFT JOIN tbl_regist c on a.koders=c.koders and a.noreg=c.noreg
		where tglbayar between '$_tgl1' and '$_tgl2' and a.koders='$unit' and jenisbayar in ('1','2','3','4','5') 
		order by tglbayar, jambayar asc
		";


			// union all

			// select 
			// 	a.id as id,
			// 	a.koders as koders,
			// 	a.resepno as nokwitansi,
			// 	a.returno as idtr,
			// 	a.noreg as noreg,
			// 	0 as kodepos,
			// 	rekmed as rekmed,
			// 	0 as preposisi,
			// 	(select namapas from tbl_pasien where rekmed = a.rekmed) as namapas,
			// 	tglretur as tglbayar,
			// 	0 as jambayar,
			// 	0 as adm,
			// 	-(a.totalnet) as totalresep,
			// 	0 as totalpoli,
			// 	0 as jkulit,
			// 	0 as jgigi,
			// 	0 as jspa,
			// 	0 as uangmuka,
			// 	0 as totalsemua,
			// 	0 as diskonresep,
			// 	0 as voucherrp1,
			// 	0 as voucherrp2,
			// 	0 as voucherrp3,
			// 	0 as diskonrp,
			// 	'' as nmjenisbayar,
			// 	0 as jenisbayar,
			// 	0 as lain,
			// 	0 as bayarcash,
			// 	0 as bayarcard,
			// 	0 as totalbayar,
			// 	0 as kembali,
			// 	0 as posbayar,
			// 	0 as selisihrp
			// FROM tbl_apohreturjual as a
			// join tbl_apodreturjual as b on a.returno = b.returno
			// where b.terpakai = 0 AND tglretur BETWEEN '$_tgl1' and '$_tgl2' and a.koders='$unit' group by id

			$query1    = $this->db->query($sql);

			$lcno       = 0;
			$no         = 0;
			$kwitansi   = 0;
			$tangal     = 0;
			$nama       = 0;
			$pasien     = 0;
			$adm        = 0;
			$umuka      = 0;
			$jkulit     = 0;
			$resep      = 0;
			$rlabel     = 0;
			$jgigi      = 0;
			$lain       = 0;
			$okirim     = 0;
			$ongkir     = 0;
			$jspa       = 0;
			$rspa       = 0;
			$apotek     = 0;
			$total      = 0;
			$selisih    = 0;
			$diskon     = 0;
			$diskonr    = 0;
			$voucher    = 0;
			$total      = 0;
			$net        = 0;

			$jum_adm        = 0;
			$jum_umuka      = 0;
			$jum_jkulit     = 0;
			$jum_resep      = 0;
			$jum_rlabel     = 0;
			$jum_jgigi      = 0;
			$jum_lain       = 0;
			$jum_okirim     = 0;
			$jum_ongkir     = 0;
			$jum_jspa       = 0;
			$jum_rspa       = 0;
			$jum_apotek     = 0;
			$jum_total      = 0;
			$jum_totall     = 0;
			$jum_selisih    = 0;
			$jum_diskon     = 0;
			$jum_diskon_r    = 0;
			$jum_voucher    = 0;
			$jum_totalnet   = 0;

			$jum2_adm       = 0;
			$jum2_umuka     = 0;
			$jum2_jkulit    = 0;
			$jum2_resep     = 0;
			$jum2_rlabel    = 0;
			$jum2_jgigi     = 0;
			$jum2_lain      = 0;
			$jum2_okirim    = 0;
			$jum2_ongkir    = 0;
			$jum2_jspa      = 0;
			$jum2_rspa      = 0;
			$jum2_apotek    = 0;
			$jum2_total     = 0;
			$jum2_totall    = 0;
			$jum2_selisih   = 0;
			$jum2_diskon     = 0;
			$jum2_diskonr    = 0;
			$jum2_voucher   = 0;
			$jum_totalnet   = 0;



			foreach ($query1->result() as $row) {
				$lcno           = $lcno + 1;
				$kwitansi       = $row->nokwitansi;
				$tangal         = $row->tglbayar;
				$namapasien     = $row->namapas;
				$nmjenisbayar   = $row->nmjenisbayar;
				$a_adm          = $row->adm;
				$a_umuka        = $row->uangmuka;
				// $a_jkulit       = $row->jkulit;
				$a_jkulit       = $row->totalpoli;
				$a_resep        = $row->totalresep;
				$a_rlabel       = $row->lain;
				$a_jgigi        = $row->jgigi;
				$a_lain         = $row->lain;
				$a_okirim       = $row->lain;
				$a_ongkir       = $row->lain;
				$a_jspa         = $row->jspa;
				$a_rspa         = $row->lain;
				$a_apotek       = $row->lain;
				$a_total        = $row->adm + $row->uangmuka + $row->totalpoli + $row->totalresep + $row->lain;
				$a_totall       = ($row->adm + $row->uangmuka + $row->totalpoli + $row->totalresep + $row->lain);
				$a_selisih      = $row->selisihrp;
				$a_diskon       = $row->diskonrp;
				$a_diskonr      = $row->diskonresep;
				$a_voucher      = $row->voucherrp1 + $row->voucherrp2 + $row->voucherrp3;
				$a_totalnet     = $a_totall - ($row->selisihrp + $row->diskonrp + $row->diskonresep + $row->voucherrp1 + $row->voucherrp2 + $row->voucherrp3);

				$adm            = angka_rp($row->adm, 0);
				$umuka          = angka_rp($row->uangmuka, 0);
				// $jkulit         = angka_rp($row->jkulit, 0);
				$jkulit         = angka_rp($row->totalpoli, 0);
				$resep          = angka_rp($row->totalresep, 0);
				$rlabel         = angka_rp($row->lain, 0);
				$jgigi          = angka_rp($row->jgigi, 0);
				$lain           = angka_rp($row->lain, 0);
				$okirim         = angka_rp($row->lain, 0);
				$ongkir         = angka_rp($row->lain, 0);
				$jspa           = angka_rp($row->jspa, 0);
				$rspa           = angka_rp($row->lain, 0);
				$apotek         = angka_rp($row->lain, 0);
				$total          = angka_rp($row->adm + $row->uangmuka + $row->totalpoli + $row->totalresep + $row->lain, 0);
				$totall         = ($row->adm + $row->uangmuka + $row->totalpoli + $row->totalresep + $row->lain);
				$selisih        = angka_rp($row->selisihrp, 0);
				$diskon         = angka_rp($row->diskonrp, 0);
				$diskonr        = angka_rp($row->diskonresep, 0);
				$voucher        = angka_rp($row->voucherrp1 + $row->voucherrp2 + $row->voucherrp3, 0);
				$totalnet       = angka_rp($totall - ($row->selisihrp + $row->diskonrp + $row->diskonresep + $row->voucherrp1 + $row->voucherrp2 + $row->voucherrp3), 0);



				$chari .= "<tr>
				<td align=\"center\">$lcno      </td>
				<td align=\"center\">$kwitansi</td>
				<td align=\"center\">$tangal  </td>
				<td align=\"center\">$namapasien    </td>
				<td align=\"right\">$adm     </td>
				<td align=\"right\">$umuka   </td>
				<td align=\"right\">$jkulit  </td>
				<td align=\"right\">$resep   </td>
				<td align=\"right\">$rlabel  </td>
				<td align=\"right\">$jgigi   </td>
				<td align=\"right\">$lain    </td>
				<td align=\"right\">$okirim  </td>
				<td align=\"right\">$ongkir  </td>
				<td align=\"right\">$jspa    </td>
				<td align=\"right\">$rspa    </td>
				<td align=\"right\">$apotek  </td>
				<td align=\"right\">$total   </td>
				<td align=\"right\">$selisih </td>
				<td align=\"right\">$diskonr  </td>
				<td align=\"right\">$diskon  </td>
				<td align=\"right\">$voucher </td>
				<td align=\"right\">$totalnet</td>
				<td align=\"left\">$nmjenisbayar</td>
				</tr>";

				$jum_adm        += $a_adm;
				$jum_umuka      += $a_umuka;
				$jum_jkulit     += $a_jkulit;
				$jum_resep      += $a_resep;
				$jum_rlabel     += $a_rlabel;
				$jum_jgigi      += $a_jgigi;
				$jum_lain       += $a_lain;
				$jum_okirim     += $a_okirim;
				$jum_ongkir     += $a_ongkir;
				$jum_jspa       += $a_jspa;
				$jum_rspa       += $a_rspa;
				$jum_apotek     += $a_apotek;
				$jum_total      += $a_total;
				$jum_totall     += $a_totall;
				$jum_selisih    += $a_selisih;
				$jum_diskon     += $a_diskon;
				$jum_diskon_r   += $a_diskonr;
				$jum_voucher    += $a_voucher;
				$jum_totalnet   += $a_totalnet;
			}

			$jum2_adm        = angka_rp($jum_adm, 0);
			$jum2_umuka      = angka_rp($jum_umuka, 0);
			$jum2_jkulit     = angka_rp($jum_jkulit, 0);
			$jum2_resep      = angka_rp($jum_resep, 0);
			$jum2_rlabel     = angka_rp($jum_rlabel, 0);
			$jum2_jgigi      = angka_rp($jum_jgigi, 0);
			$jum2_lain       = angka_rp($jum_lain, 0);
			$jum2_okirim     = angka_rp($jum_okirim, 0);
			$jum2_ongkir     = angka_rp($jum_ongkir, 0);
			$jum2_jspa       = angka_rp($jum_jspa, 0);
			$jum2_rspa       = angka_rp($jum_rspa, 0);
			$jum2_apotek     = angka_rp($jum_apotek, 0);
			$jum2_total      = angka_rp($jum_total, 0);
			$jum2_totall     = angka_rp($jum_totall, 0);
			$jum2_selisih    = angka_rp($jum_selisih, 0);
			$jum2_diskon     = angka_rp($jum_diskon, 0);
			$jum2_diskonr    = angka_rp($jum_diskon_r, 0);
			$jum2_voucher    = angka_rp($jum_voucher, 0);
			$jum2_totalnet   = angka_rp($jum_totalnet, 0);


			$chari    .= "<tr>
            <td colspan=\"4\" bgcolor=\"#cccccc\" align=\"center\"><b>Total Pendapatan Cashier</b></td>
			<td bgcolor=\"#cccccc\" align=\"right\">$jum2_adm     </td>
			<td bgcolor=\"#cccccc\" align=\"right\">$jum2_umuka   </td>
			<td bgcolor=\"#cccccc\" align=\"right\">$jum2_jkulit  </td>
			<td bgcolor=\"#cccccc\" align=\"right\">$jum2_resep   </td>
			<td bgcolor=\"#cccccc\" align=\"right\">$jum2_rlabel  </td>
			<td bgcolor=\"#cccccc\" align=\"right\">$jum2_jgigi   </td>
			<td bgcolor=\"#cccccc\" align=\"right\">$jum2_lain    </td>
			<td bgcolor=\"#cccccc\" align=\"right\">$jum2_okirim  </td>
			<td bgcolor=\"#cccccc\" align=\"right\">$jum2_ongkir  </td>
			<td bgcolor=\"#cccccc\" align=\"right\">$jum2_jspa    </td>
			<td bgcolor=\"#cccccc\" align=\"right\">$jum2_rspa    </td>
			<td bgcolor=\"#cccccc\" align=\"right\">$jum2_apotek  </td>
			<td bgcolor=\"#cccccc\" align=\"right\">$jum2_total   </td>
			<td bgcolor=\"#cccccc\" align=\"right\">$jum2_selisih </td>
			<td bgcolor=\"#cccccc\" align=\"right\">$jum2_diskonr  </td>
			<td bgcolor=\"#cccccc\" align=\"right\">$jum2_diskon  </td>
			<td bgcolor=\"#cccccc\" align=\"right\">$jum2_voucher </td>
			<td bgcolor=\"#cccccc\" align=\"right\">$jum2_totalnet</td>
			<td bgcolor=\"#cccccc\" align=\"right\"><b></b></td>
                       
            </tr>";

			// $chari    .= "<tr>
			// <td colspan=\"4\" style=\"border-top:none\" align=\"right\"><b></b></td>
			// <td style=\"border-top:none\" align=\"right\"><b></b></td>
			// <td style=\"border-top:none\" align=\"right\"><b>0</b></td>
			// <td style=\"border-top:none\" align=\"right\"><b></b></td>
			// <td style=\"border-top:none\" align=\"right\"><b>0</b></td>
			// <td style=\"border-top:none\" align=\"right\"><b></b></td>
			// <td style=\"border-top:none\" align=\"right\"><b>0</b></td>
			// <td style=\"border-top:none\" align=\"right\"><b></b></td>
			// <td style=\"border-top:none\" align=\"right\"><b>0</b></td>
			// <td style=\"border-top:none\" align=\"right\"><b></b></td>
			// <td style=\"border-top:none\" align=\"right\"><b>0</b></td>
			// <td style=\"border-top:none\" align=\"right\"><b></b></td>
			// <td style=\"border-top:none\" align=\"right\"><b>0</b></td>
			// <td style=\"border-top:none\" align=\"right\"><b></b></td>
			// <td style=\"border-top:none\" align=\"right\"><b>0</b></td>
			// <td style=\"border-top:none\" align=\"right\"><b></b></td>
			// <td style=\"border-top:none\" align=\"right\"><b>0</b></td>
			// <td style=\"border-top:none\" align=\"right\"><b></b></td>
			// <td style=\"border-top:none\" align=\"right\"><b>0</b></td>
			// <td style=\"border-top:none\" align=\"right\"><b></b></td>

			// </tr>";

			$chari    .= "<tr>
            <td colspan=\"21\" style=\"border:none\" align=\"center\"><br></td>
                       
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
					from tbl_kasir a
					inner join tbl_pasien b on a.rekmed=b.rekmed 
					where tglbayar between '$_tgl1' and '$_tgl2' and a.koders='$unit'  
			)a";

			$sql2 = "SELECT 
			sum(case when kodebank='001' then jumlahbayar else 0 end )as bca_lokal ,
			sum(case when kodebank='002' then jumlahbayar else 0 end )as mandiri ,
			sum(case when kodebank='003' then jumlahbayar else 0 end )as bca_tunai 
			from tbl_kartukredit a
			where tanggal between '$_tgl1' and '$_tgl2' and a.koders='$unit'";

			$query1    = $this->db->query($sql);
			$query2    = $this->db->query($sql2);

			$lcno          = 0;
			$Cash          = 0;
			$CreditCard    = 0;
			$DebetCard     = 0;
			$Transfer      = 0;
			$Online        = 0;
			$bca_lokal     = 0;
			$mandiri       = 0;
			$bca_tunai     = 0;

			$cek_cash = $this->db->query("SELECT SUM(bayarcash) AS cash FROM tbl_kasir WHERE koders = '$unit' AND tglbayar BETWEEN '$_tgl1' AND '$_tgl2'")->row();
			$cek_debit = $this->db->query("SELECT SUM(jumlahbayar) AS debit FROM tbl_kartukredit WHERE cardtype = 1 AND koders = '$unit' AND tanggal BETWEEN '$_tgl1' AND '$_tgl2'")->row();
			$cek_credit = $this->db->query("SELECT SUM(jumlahbayar) AS credit FROM tbl_kartukredit WHERE cardtype = 2 AND koders = '$unit' AND tanggal BETWEEN '$_tgl1' AND '$_tgl2'")->row();
			$cek_transfer = $this->db->query("SELECT SUM(jumlahbayar) AS transfer FROM tbl_kartukredit WHERE cardtype = 3 AND koders = '$unit' AND tanggal BETWEEN '$_tgl1' AND '$_tgl2'")->row();
			$cek_online = $this->db->query("SELECT SUM(jumlahbayar) AS online FROM tbl_kartukredit WHERE cardtype = 4 AND koders = '$unit' AND tanggal BETWEEN '$_tgl1' AND '$_tgl2'")->row();

			foreach ($query1->result() as $row) {
				$lcno         = $lcno + 1;
				$Cash         = angka_rp($row->Cash, 0);
				$CreditCard   = angka_rp($row->CreditCard, 0);
				$DebetCard    = angka_rp($row->DebetCard, 0);
				$Transfer     = angka_rp($row->Transfer, 0);
				$Online       = angka_rp($row->Online, 0);

				foreach ($query2->result() as $row2) {
					$lcno         = $lcno + 1;
					$bca_lokal    = angka_rp($row2->bca_lokal, 0);
					$mandiri      = angka_rp($row2->mandiri, 0);
					$bca_tunai    = angka_rp($row2->bca_tunai, 0);

					// cash old $Cash
					// credit old $CreditCard
					// debit old $DebetCard
					// transfer old $Transfer
					// online old $Online

					$chari    .= "
			<tr>
				<td colspan=\"2\" style=\"font-size:12px; border-top:none;border-left:none;border-right:none\" align=\"LEFT\"><b>TOTAL TUNAI</b></td>
				<td style=\"border-top:none;border-left:none;border-right:none\" align=\"right\">" . number_format($cek_cash->cash, 0) . "</td>
				<td style=\"border:none\"></td>
				<td colspan=\"3\" style=\"border:none\" align=\"LEFT\"><b>BANK BCA LOKAL</b></td>
				<td colspan=\"2\" style=\"border:none\" align=\"right\">$bca_lokal</td>                       
            </tr>
			<tr>
				<td colspan=\"2\" style=\"font-size:12px; border-top:none;border-left:none;border-right:none\" align=\"LEFT\"><b>TOTAL KREDIT</b></td>
				<td style=\"border-top:none;border-left:none;border-right:none\" align=\"right\">".number_format($cek_credit->credit,0)."</td>
				<td style=\"border:none\"></td>
				<td colspan=\"3\" style=\"border:none\" align=\"LEFT\"><b>BANK MANDIRI</b></td>
				<td colspan=\"2\" style=\"border:none\" align=\"right\">$mandiri</td>                       
            </tr>
			<tr>
				<td colspan=\"2\" style=\"font-size:12px; border-top:none;border-left:none;border-right:none\" align=\"LEFT\"><b>TOTAL DEBIT</b></td>
				<td style=\"border-top:none;border-left:none;border-right:none\" align=\"right\">".number_format($cek_debit->debit,0)."</td>
				<td style=\"border:none\"></td>
				<td colspan=\"3\" style=\"border:none\" align=\"LEFT\"><b>BANK BCA TUNAI</b></td>
				<td colspan=\"2\" style=\"border:none\" align=\"right\">$bca_tunai</td>                       
            </tr>
			<tr>
				<td colspan=\"2\" style=\"font-size:12px; border-top:none;border-left:none;border-right:none\" align=\"LEFT\"><b>TOTAL TRANSFER</b></td>
				<td style=\"border-top:none;border-left:none;border-right:none\" align=\"right\">" . number_format($cek_transfer->transfer, 0) . "</td>                       
            </tr>
			<tr>
				<td colspan=\"2\" style=\"font-size:12px; border-top:none;border-left:none;border-right:none\" align=\"LEFT\"><b>TOTAL ONLINE</b></td>
				<td style=\"border-top:none;border-left:none;border-right:none\" align=\"right\">" . number_format($cek_online->online, 0) . "</td>                       
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
			$judul          = 'LAPORAN HARIAN KASIR PENDAFTARAN PER SHIFT';
			switch ($cek) {
				case 0;
					echo ("<title>DATA GLOBAL SKI</title>");
					echo ($chari);
					echo "<script>window.print();</script>";
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
				<img src=\"" . base_url() . "assets/img_usrt/".$this->session->userdata("avatar_cabang")."\"  width=\"100\" height=\"100\" /></td>

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
				<img src=\"" . base_url() . "assets/img_usrt/".$this->session->userdata("avatar_cabang")."\"  width=\"100\" height=\"100\" /></td>

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
				<img src=\"" . base_url() . "assets/img_usrt/".$this->session->userdata("avatar_cabang")."\"  width=\"100\" height=\"100\" /></td>

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
				<img src=\"" . base_url() . "assets/img_usrt/".$this->session->userdata("avatar_cabang")."\"  width=\"100\" height=\"100\" /></td>

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