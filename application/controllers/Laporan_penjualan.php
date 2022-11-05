<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Laporan_penjualan extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->session->set_userdata('menuapp', '5000');
		$this->session->set_userdata('submenuapp', '5201');
		$this->load->helper('simkeu_nota');
		$this->load->model('M_laporan');
		$this->load->model('M_rs');
		$this->load->model('M_cetak');
		$this->load->model('M_template_cetak');
	}
	public function index()
	{
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');
		if (!empty($cek)) {
			$d['startdate'] = null;
			$d['enddate'] = null;
			$d['jenis_kunjungan'] = 'frekuensi';
			$d['keu'] = array();
			$d['akses'] = $this->M_global->cek_menu_akses($cek, 5301);
			$this->load->view('penjualan/v_laporan_penjualan', $d);
		} else {
			header('location:' . base_url());
		}
	}

	public function cetak2(){
		$cek = $this->session->userdata('level');
		$dari = $this->input->get('dari');
		$sampai = $this->input->get('sampai');
		$dari_jam = $this->input->get('dari_jam');
		$sampai_jam = $this->input->get('sampai_jam');
		$cekpdf     = $this->input->get('pdf');
		$cabang     = $this->session->userdata('unit');
		$unit       = $cabang;
		$body       = '';
		$date       = "Dari Tgl : " . date("d-m-Y", strtotime($dari)) . " S/D " . date("d-m-Y", strtotime($sampai));
		$profile    = data_master('tbl_namers', array('koders' => $unit));
		$kota       = $profile->kota;
		$jenisx = $this->input->get('jenis');
		if ($jenisx == 3) {
			$jenis = '';
		} else if ($jenisx == 1) {
			$jenis = ' AND jenisjual = 1';
		} else {
			$jenis = ' AND jenisjual = 2';
		}
		$depo = $this->input->get('depo');
		$laporan = $this->input->get('laporan');
		$cabang = $this->session->userdata('unit');
		$unit = $cabang;
		if (!empty($cek)) {
			if ($laporan == 1) {
				$position   = 'L';
				$judul = '01 LAPORAN PENJUALAN RESEP PERDOKTER';
				if ($depo != '') {
					$query = $this->db->query("SELECT resepno, tglresep, view_penjualan_barang.noreg, view_penjualan_barang.gudang, nadokter, (SELECT namapas FROM tbl_pasien WHERE rekmed = view_penjualan_barang.rekmed) AS namapas, namabarangoriginal AS namabarang, qty, satuan, discount, price, totalrp FROM view_penjualan_barang JOIN tbl_regist ON tbl_regist.rekmed = view_penjualan_barang.rekmed JOIN tbl_dokter ON tbl_dokter.kodokter=tbl_regist.kodokter WHERE view_penjualan_barang.koders = '$unit' AND tglresep BETWEEN '$dari' AND '$sampai' $jenis and view_penjualan_barang.gudang = '$depo' GROUP BY resepno")->result();
				} else {
					$query = $this->db->query("SELECT resepno, tglresep, view_penjualan_barang.noreg, view_penjualan_barang.gudang, nadokter, (SELECT namapas FROM tbl_pasien WHERE rekmed = view_penjualan_barang.rekmed) AS namapas, namabarangoriginal AS namabarang, qty, satuan, discount, price, totalrp FROM view_penjualan_barang JOIN tbl_regist ON tbl_regist.rekmed = view_penjualan_barang.rekmed JOIN tbl_dokter ON tbl_dokter.kodokter=tbl_regist.kodokter WHERE view_penjualan_barang.koders = '$unit' AND tglresep BETWEEN '$dari' AND '$sampai' $jenis GROUP BY resepno")->result();
				}
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">
					<tr>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\">No</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">No. Tr</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Tanggal</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Noreg / Rekmed</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Nama Pasien</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Resep Dari</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Nama Obat</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\">Qty</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Satuan</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\">Disc %</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"8%\">Harga Sat</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"8%\">Total</td>
					</tr>";
				$no = 1;
				$total = 0;
				foreach ($query as $q) {
					if ($q->noreg != '') {
						$noreg = $q->noreg;
					} else {
						$noreg = 'LOKAL';
					}
					if($cekpdf == 1){
						$qty = number_format($q->qty);
						$discount = number_format($q->discount);
						$price = number_format($q->price);
						$totalrp = number_format($q->totalrp);
					} else {
						$qty = $q->qty;
						$discount = $q->discount;
						$price = $q->price;
						$totalrp = $q->totalrp;
					}
					$body .=  "<tr>
						<td style=\"text-align: right; padding: 5px;\" width=\"5%\">" . $no++ . "</td>
						<td style=\"text-align: left; padding: 5px;\">$q->resepno</td>
						<td style=\"text-align: center; padding: 5px;\">".date("d-m-Y", strtotime($q->tglresep))."</td>
						<td style=\"text-align: left; padding: 5px;\">$noreg</td>
						<td style=\"text-align: left; padding: 5px;\">$q->namapas</td>
						<td style=\"text-align: left; padding: 5px;\">$q->nadokter</td>
						<td style=\"text-align: left; padding: 5px;\">$q->namabarang</td>
						<td style=\"text-align: right; padding: 5px;\" width=\"5%\">$qty</td>
						<td style=\"text-align: center; padding: 5px;\">$q->satuan</td>
						<td style=\"text-align: right; padding: 5px;\" width=\"5%\">$discount</td>
						<td style=\"text-align: right; padding: 5px;\" width=\"8%\">$price</td>
						<td style=\"text-align: right; padding: 5px;\" width=\"8%\">$totalrp</td>
					</tr>";
					$total += $q->totalrp;
				}
				if($cekpdf == 1){
					$ttl = number_format($total);
				} else {
					$ttl = $total;
				}
				$body .= "<tr>
					<td style=\"text-align: center; font-weight: bold; padding: 5px;\" colspan=\"11\">TOTAL</td>
					<td style=\"text-align: right; padding: 5px;\">$ttl</td>
				</tr>
				</table>";
			} else if ($laporan == 2) {
				$judul = '02 LAPORAN PENJUALAN OBAT';
				if ($depo != '') {
					$depox = " and gudang = '$depo'";
				} else {
					$depox = '';
				}
				$query = $this->db->query("SELECT kodebarang, satuan, namabarang, tglresep, jenispas, SUM(qty_tunai) AS qty_tunai, SUM(qty_lokal) AS qty_lokal, SUM(qty_kirim) AS qty_kirim, SUM(qty_spa) AS qty_spa, SUM(qty_apotik) AS qty_apotik, SUM(qty_cabang) AS qty_cabang, (rp_lokal * SUM(qty_lokal)) AS rp_lokal, (rp_tunai * SUM(qty_tunai)) AS rp_tunai, (rp_kirim * SUM(qty_kirim)) AS rp_kirim, (rp_spa * SUM(qty_spa)) AS rp_spa, (rp_apotik * SUM(qty_apotik)) AS rp_apotik, (rp_cabang * SUM(qty_cabang)) AS rp_cabang,
				(SUM(xx.qty_tunai) + SUM(xx.qty_lokal) + SUM(xx.qty_kirim) + SUM(xx.qty_spa) + SUM(xx.qty_apotik) + SUM(xx.qty_cabang)) AS jualtotal_qty, 
				(SUM(xx.rp_tunai) + SUM(xx.rp_lokal) + SUM(xx.rp_kirim) + SUM(xx.rp_spa) + SUM(xx.rp_apotik) + SUM(xx.rp_cabang)) AS jualtotal_rp  
				FROM
					(
						SELECT d.kodebarang, d.satuan, d.namabarang, h.tglresep, h.jenispas,
						( CASE  WHEN jenispas = 1 THEN qty ELSE 0 END) AS qty_tunai,
						( CASE WHEN jenispas = 1 THEN price ELSE 0 END) AS rp_tunai,
						( CASE  WHEN jenispas = 2 THEN qty ELSE 0 END) AS qty_lokal,
						( CASE WHEN jenispas = 2 THEN price ELSE 0 END) AS rp_lokal,
						( CASE  WHEN jenispas = 3 THEN qty ELSE 0 END) AS qty_kirim,
						( CASE WHEN jenispas = 3 THEN price ELSE 0 END) AS rp_kirim,
						( CASE  WHEN jenispas = 4 THEN qty ELSE 0 END) AS qty_spa,
						( CASE WHEN jenispas = 4 THEN price ELSE 0 END) AS rp_spa,
						( CASE  WHEN jenispas = 7 THEN qty ELSE 0 END) AS qty_apotik,
						( CASE WHEN jenispas = 7 THEN price ELSE 0 END) AS rp_apotik,
						( CASE  WHEN jenispas = 0 THEN qty ELSE 0 END) AS qty_cabang,
						( CASE WHEN jenispas = 0 THEN price ELSE 0 END) AS rp_cabang
						FROM tbl_apohresep h 
						JOIN tbl_apodresep d ON h.resepno = d.resepno 
						WHERE h.koders = '$unit' AND tglresep BETWEEN '$dari' AND '$sampai' $depox $jenis
						ORDER BY h.jam, h.tglresep ASC
					) AS xx GROUP BY kodebarang
				")->result();
				$gudang = $this->db->query("SELECT depocode, keterangan FROM tbl_depo WHERE depocode = '$depo'")->row();
				if ($depo != '') {
					$gdx = $gudang->keterangan;
				} else {
					$gdx = 'SEMUA GUDANG';
				}
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
					<tr>
							<td width=\"15%\" style=\"text-align:left;border-bottom: none;\">Dari Gudang</td>
							<td width=\"5%\" style=\"text-align:center;border-bottom: none;\">:</td>
							<td width=\"30%\" style=\"text-align:left;border-bottom: none;\">" . $gdx . "</td>
							<td width=\"15%\" style=\"text-align:left;border-bottom: none;\">Tanggal</td>
							<td width=\"5%\" style=\"text-align:center;border-bottom: none;\">:</td>
							<td width=\"30%\" style=\"text-align:left;border-bottom: none;\">" . date('d-m-Y', strtotime($dari)) . ' / ' . date('d-m-Y', strtotime($sampai)) . "</td>
					</tr>
				</table>";
				$body .= "<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
					<tr>
							<td style=\"border:0\" align=\"center\"><br></td>
					</tr>
					<tr>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\" align=\"center\" rowspan=\"2\"><br>No</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\" align=\"center\" rowspan=\"2\"><br>Kode Barang</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"30%\" align=\"center\" rowspan=\"2\"><br>Nama Barang</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\" align=\"center\" rowspan=\"2\"><br>Satuan</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\" align=\"center\" colspan=\"2\"><br>Tunai</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\" align=\"center\" colspan=\"2\"><br>Lokal</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\" align=\"center\" colspan=\"2\"><br>Kirim</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\" align=\"center\" colspan=\"2\"><br>Spa</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\" align=\"center\" colspan=\"2\"><br>Apotik</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\" align=\"center\" colspan=\"2\"><br>Cabang</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\" align=\"center\" colspan=\"2\"><br>Total</td>
					</tr>
					<tr>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\" align=\"center\"><br>Qty</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\" align=\"center\"><br>Rp</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\" align=\"center\"><br>Qty</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\" align=\"center\"><br>Rp</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\" align=\"center\"><br>Qty</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\" align=\"center\"><br>Rp</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\" align=\"center\"><br>Qty</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\" align=\"center\"><br>Rp</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\" align=\"center\"><br>Qty</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\" align=\"center\"><br>Rp</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\" align=\"center\"><br>Qty</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\" align=\"center\"><br>Rp</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\" align=\"center\"><br>Qty</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\" align=\"center\"><br>Rp</td>
					</tr>";
				$no = 1;
				foreach ($query as $q) {
					$kodebarang = $q->kodebarang;
					$namabarang = $q->namabarang;
					$satuan = $q->satuan;
					if($cekpdf == 1){
						$qty_tunai = number_format($q->qty_tunai);
						$rp_tunai = number_format($q->rp_tunai);
						$qty_lokal = number_format($q->qty_lokal);
						$rp_lokal = number_format($q->rp_lokal);
						$qty_kirim = number_format($q->qty_kirim);
						$rp_kirim = number_format($q->rp_kirim);
						$qty_spa = number_format($q->qty_spa);
						$rp_spa = number_format($q->rp_spa);
						$qty_apotik = number_format($q->qty_apotik);
						$rp_apotik = number_format($q->rp_apotik);
						$qty_cabang = number_format($q->qty_cabang);
						$rp_cabang = number_format($q->rp_cabang);
						$jualtotal_qty = number_format($q->jualtotal_qty);
						$jualtotal_rp = number_format($q->jualtotal_rp);
					} else {
						$qty_tunai = $q->qty_tunai;
						$rp_tunai = $q->rp_tunai;
						$qty_lokal = $q->qty_lokal;
						$rp_lokal = $q->rp_lokal;
						$qty_kirim = $q->qty_kirim;
						$rp_kirim = $q->rp_kirim;
						$qty_spa = $q->qty_spa;
						$rp_spa = $q->rp_spa;
						$qty_apotik = $q->qty_apotik;
						$rp_apotik = $q->rp_apotik;
						$qty_cabang = $q->qty_cabang;
						$rp_cabang = $q->rp_cabang;
						$jualtotal_qty = $q->jualtotal_qty;
						$jualtotal_rp = $q->jualtotal_rp;
					}
					$body .= "<tr>
						<td align=\"right\">" . $no++ . "</td>
						<td align=\"left\">$kodebarang</td>
						<td align=\"left\">$namabarang</td>
						<td align=\"left\">$satuan</td>
						<td align=\"right\">$qty_tunai</td>
						<td align=\"right\">$rp_tunai</td>
						<td align=\"right\">$qty_lokal</td>
						<td align=\"right\">$rp_lokal</td>
						<td align=\"right\">$qty_kirim</td>
						<td align=\"right\">$rp_kirim</td>
						<td align=\"right\">$qty_spa</td>
						<td align=\"right\">$rp_spa</td>
						<td align=\"right\">$qty_apotik</td>
						<td align=\"right\">$rp_apotik</td>
						<td align=\"right\">$qty_cabang</td>
						<td align=\"right\">$rp_cabang</td>
						<td align=\"right\">$jualtotal_qty</td>
						<td align=\"right\">$jualtotal_rp</td>
					</tr>";
				}
			$body .= "</table>";
			} else if ($laporan == 3) {
				$judul = '03 Laporan Rincian Penjualan Resep';
				if ($depo != '') {
					$depox = " and gudang = '$depo'";
				} else {
					$depox = '';
				}
				$query = $this->db->query("
				SELECT a.`resepno`, 
				a.rekmed,
				(SELECT namapas FROM tbl_pasien WHERE rekmed=a.rekmed) AS namapas,
				b.totalrp,
				b.`kodebarang`, b.`namabarang`, b.`satuan`, b.`qty`, b.`hna`
				FROM tbl_apohresep a
				JOIN tbl_apodresep b ON a.`resepno`=b.`resepno`
				WHERE a.tglresep between '$dari' and '$sampai' $jenis $depox and a.koders = '$unit'
				")->result();
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">
					<tr>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\">No</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">No. Resep</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Nama</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Rekmed</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Kode Obat</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Nama Obat</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Satuan</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\">Qty</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"8%\">Total</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"8%\">Total HNA</td>
					</tr>";
				$no = 1;
				foreach ($query as $q) {
					if($cekpdf == 1){
						$qty = number_format($q->qty);
						$totalrp = number_format($q->totalrp);
						$hna = number_format($q->hna);
					} else {
						$qty = $q->qty;
						$totalrp = $q->totalrp;
						$hna = $q->hna;
					}
					$body .=  "<tr>
						<td style=\"text-align: right; padding: 5px;\" width=\"5%\">" . $no++ . "</td>
						<td style=\"text-align: left; padding: 5px;\" width=\"10%\">$q->resepno</td>
						<td style=\"text-align: left; padding: 5px;\">$q->namapas</td>
						<td style=\"text-align: left; padding: 5px;\">$q->rekmed</td>
						<td style=\"text-align: left; padding: 5px;\">$q->kodebarang</td>
						<td style=\"text-align: left; padding: 5px;\">$q->namabarang</td>
						<td style=\"text-align: right; padding: 5px;\">$q->satuan</td>
						<td style=\"text-align: right; padding: 5px;\" width=\"5%\">$qty</td>
						<td style=\"text-align: right; padding: 5px;\" width=\"8%\">$totalrp</td>
						<td style=\"text-align: right; padding: 5px;\" width=\"8%\">$hna</td>
					</tr>";
				}
				$body .= "</table>";
			} else if ($laporan == 4) {
				$judul = '04 ANALISA PENJUALAN OBAT';
				if ($depo != '') {
					$depox = " and h.gudang = '$depo'";
				} else {
					$depox = '';
				}
				$query = $this->db->query(
					"SELECT * FROM ( 
						SELECT kodebarang, namabarang, 
							satuan, harga_jual, SUM(totalnet) AS totalnet, SUM(qty) AS qty, 
							SUM(harga_pokok) AS harga_pokok, SUM(total_hpp) AS total_hpp, SUM(ppnrp) AS ppnrp, 
							SUM(rugi_laba) AS rugi_laba, SUM(persen) AS persen
						FROM (
							SELECT d.kodebarang, b.namabarang, b.satuan1 AS satuan, b.hargajual AS harga_jual, d.qty,
							(d.qty*b.hargajual) AS totalnet, b.hargabelippn AS harga_pokok, (d.qty*b.hargabelippn) AS total_hpp, d.ppnrp,
							((d.qty*hargajual)-(d.qty*b.hargabelippn)) AS rugi_laba,
							((((d.qty*hargajual)-(d.qty*b.hargabelippn))/(d.qty*hargajual)) * 100) AS persen
							FROM tbl_apohresep h 
							JOIN tbl_apodresep d ON h.resepno=d.resepno
							JOIN tbl_barang b ON b.kodebarang=d.kodebarang
							WHERE h.tglresep BETWEEN '$dari' AND '$sampai' AND d.koders = '$unit' $depox
						) AS resep GROUP BY kodebarang
					) semua ORDER BY qty DESC"
				)->result();
				// UNION ALL
				// SELECT CONCAT('[RACIKAN] - ',kodebarang) kodebarang, CONCAT('[RACIKAN] - ',namabarang) AS namabarang, 
				// 	satuan, harga_jual, SUM(totalnet) AS totalnet, SUM(qty) AS qty,
				// 	SUM(harga_pokok) AS harga_pokok, SUM(total_hpp) AS total_hpp, SUM(ppnrp) AS ppnrp, 
				// 	SUM(rugi_laba) AS rugi_laba, SUM(persen) AS persen
				// FROM (
				// 	SELECT d.kodebarang, b.namabarang, b.satuan1 AS satuan, b.hargajual AS harga_jual, d.qty,
				// 	(d.qty*b.hargajual) AS totalnet, b.hargabelippn AS harga_pokok, (d.qty*b.hargabelippn) AS total_hpp, d.ppnrp,
				// 	((d.qty*hargajual)-(d.qty*b.hargabelippn)) AS rugi_laba,
				// 	((((d.qty*hargajual)-(d.qty*b.hargabelippn))/(d.qty*hargajual)) * 100) AS persen
				// 	FROM tbl_apohresep h 
				// 	JOIN tbl_apodresep d ON h.resepno=d.resepno
				// 	JOIN tbl_barang b ON b.kodebarang=d.kodebarang
				// 	WHERE h.tglresep BETWEEN '$dari' AND '$sampai' AND d.koders = '$unit' $depox
				// ) AS racik GROUP BY kodebarang
				$body .= "<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
					<tr>
							<td style=\"border:0\" align=\"center\"><br></td>
					</tr>
					<tr>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\" align=\"center\" rowspan=\"2\"><br>No</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"15%\" align=\"center\" rowspan=\"2\"><br>Kode Barang</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"30%\" align=\"center\" rowspan=\"2\"><br>Nama Barang</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\" align=\"center\" rowspan=\"2\"><br>Satuan</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\" align=\"center\" rowspan=\"2\"><br>Qty</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\" align=\"center\" colspan=\"2\"><br>Penjualan</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\" align=\"center\" colspan=\"2\"><br>Harga Pokok Pembelian</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\" align=\"center\" colspan=\"3\"><br>Analisa Laba Rugi</td>
					</tr>
					<tr>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\" align=\"center\"><br>Harga Jual</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\" align=\"center\"><br>Total Net Rp</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\" align=\"center\"><br>Harga Pokok</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\" align=\"center\"><br>Total Hpp</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\" align=\"center\"><br>PPN Rp</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\" align=\"center\"><br>Laba / Rugi</td>
						<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\" align=\"center\"><br>Persentase %</td>
					</tr>";
				$no = 1;
				foreach ($query as $q) {
					if($cekpdf == 1){
						$qty = number_format($q->qty);
						$harga_jual = number_format($q->harga_jual);
						$totalnet = number_format($q->totalnet);
						$harga_pokok = number_format($q->harga_pokok);
						$total_hpp = number_format($q->total_hpp);
						$ppnrp = number_format($q->ppnrp);
						$rugi_laba = number_format($q->rugi_laba);
						$persen = number_format($q->persen);
					} else {
						$qty = $q->qty;
						$harga_jual = $q->harga_jual;
						$totalnet = $q->totalnet;
						$harga_pokok = $q->harga_pokok;
						$total_hpp = $q->total_hpp;
						$ppnrp = $q->ppnrp;
						$rugi_laba = $q->rugi_laba;
						$persen = $q->persen;
					}
					$body .= "<tr>
						<td align=\"right\">" . $no++ . "</td>
						<td align=\"left\">$q->kodebarang</td>
						<td align=\"left\">$q->namabarang</td>
						<td align=\"left\">$q->satuan</td>
						<td align=\"right\">$qty</td>
						<td align=\"right\">$harga_jual</td>
						<td align=\"right\">$totalnet</td>
						<td align=\"right\">$harga_pokok</td>
						<td align=\"right\">$total_hpp</td>
						<td align=\"right\">$ppnrp</td>
						<td align=\"right\">$rugi_laba</td>
						<td align=\"right\">$persen</td>
					</tr>";
				}
				$body .= "</table>";
			}
			$this->M_template_cetak->template($judul, $body, $position, $date, $cekpdf);
		} else {
			header('location:' . base_url());
		}
	}

	public function cetak()
	{
		$cek = $this->session->userdata('level');
		$dari = $this->input->get('dari');
		$sampai = $this->input->get('sampai');
		$dari_jam = $this->input->get('dari_jam');
		$sampai_jam = $this->input->get('sampai_jam');
		$jenisx = $this->input->get('jenis');
		if ($jenisx == 3) {
			$jenis = '';
		} else if ($jenisx == 1) {
			$jenis = ' AND jenisjual = 1';
		} else {
			$jenis = ' AND jenisjual = 2';
		}
		$depo = $this->input->get('depo');
		$laporan = $this->input->get('laporan');
		$cabang = $this->session->userdata('unit');
		$unit = $cabang;
		if (!empty($cek)) {
			if ($laporan == 1) {
				$judul = '01 LAPORAN PENJUALAN RESEP PERDOKTER';
				if ($depo != '') {
					$query = $this->db->query("SELECT resepno, tglresep, view_penjualan_barang.noreg, view_penjualan_barang.gudang, nadokter, (SELECT namapas FROM tbl_pasien WHERE rekmed = view_penjualan_barang.rekmed) AS namapas, namabarangoriginal AS namabarang, qty, satuan, discount, price, totalrp FROM view_penjualan_barang JOIN tbl_regist ON tbl_regist.rekmed = view_penjualan_barang.rekmed JOIN tbl_dokter ON tbl_dokter.kodokter=tbl_regist.kodokter WHERE view_penjualan_barang.koders = '$unit' AND tglresep BETWEEN '$dari' AND '$sampai' $jenis and view_penjualan_barang.gudang = '$depo' GROUP BY resepno")->result();
				} else {
					$query = $this->db->query("SELECT resepno, tglresep, view_penjualan_barang.noreg, view_penjualan_barang.gudang, nadokter, (SELECT namapas FROM tbl_pasien WHERE rekmed = view_penjualan_barang.rekmed) AS namapas, namabarangoriginal AS namabarang, qty, satuan, discount, price, totalrp FROM view_penjualan_barang JOIN tbl_regist ON tbl_regist.rekmed = view_penjualan_barang.rekmed JOIN tbl_dokter ON tbl_dokter.kodokter=tbl_regist.kodokter WHERE view_penjualan_barang.koders = '$unit' AND tglresep BETWEEN '$dari' AND '$sampai' $jenis GROUP BY resepno")->result();
				}
			} else if ($laporan == 2) {
				$judul = '02 LAPORAN PENJUALAN OBAT';
				if ($depo != '') {
					$depox = " and gudang = '$depo'";
				} else {
					$depox = '';
				}
				$query = $this->db->query("SELECT kodebarang, satuan, namabarang, tglresep, jenispas, SUM(qty_tunai) AS qty_tunai, SUM(qty_lokal) AS qty_lokal, SUM(qty_kirim) AS qty_kirim, SUM(qty_spa) AS qty_spa, SUM(qty_apotik) AS qty_apotik, SUM(qty_cabang) AS qty_cabang, (rp_lokal * SUM(qty_lokal)) AS rp_lokal, (rp_tunai * SUM(qty_tunai)) AS rp_tunai, (rp_kirim * SUM(qty_kirim)) AS rp_kirim, (rp_spa * SUM(qty_spa)) AS rp_spa, (rp_apotik * SUM(qty_apotik)) AS rp_apotik, (rp_cabang * SUM(qty_cabang)) AS rp_cabang,
				(SUM(xx.qty_tunai) + SUM(xx.qty_lokal) + SUM(xx.qty_kirim) + SUM(xx.qty_spa) + SUM(xx.qty_apotik) + SUM(xx.qty_cabang)) AS jualtotal_qty, 
				(SUM(xx.rp_tunai) + SUM(xx.rp_lokal) + SUM(xx.rp_kirim) + SUM(xx.rp_spa) + SUM(xx.rp_apotik) + SUM(xx.rp_cabang)) AS jualtotal_rp  
				FROM
					(
						SELECT d.kodebarang, d.satuan, d.namabarang, h.tglresep, h.jenispas,
						( CASE  WHEN jenispas = 1 THEN qty ELSE 0 END) AS qty_tunai,
						( CASE WHEN jenispas = 1 THEN price ELSE 0 END) AS rp_tunai,
						( CASE  WHEN jenispas = 2 THEN qty ELSE 0 END) AS qty_lokal,
						( CASE WHEN jenispas = 2 THEN price ELSE 0 END) AS rp_lokal,
						( CASE  WHEN jenispas = 3 THEN qty ELSE 0 END) AS qty_kirim,
						( CASE WHEN jenispas = 3 THEN price ELSE 0 END) AS rp_kirim,
						( CASE  WHEN jenispas = 4 THEN qty ELSE 0 END) AS qty_spa,
						( CASE WHEN jenispas = 4 THEN price ELSE 0 END) AS rp_spa,
						( CASE  WHEN jenispas = 7 THEN qty ELSE 0 END) AS qty_apotik,
						( CASE WHEN jenispas = 7 THEN price ELSE 0 END) AS rp_apotik,
						( CASE  WHEN jenispas = 0 THEN qty ELSE 0 END) AS qty_cabang,
						( CASE WHEN jenispas = 0 THEN price ELSE 0 END) AS rp_cabang
						FROM tbl_apodresep d 
						JOIN tbl_apohresep h ON h.resepno = d.resepno 
						WHERE h.koders = '$unit' AND tglresep BETWEEN '$dari' AND '$sampai' $depox $jenis
						ORDER BY h.jam, h.tglresep ASC
					) AS xx GROUP BY kodebarang
				")->result();
			} else if ($laporan == 3) {
				$judul = '03 Laporan Rincian Penjualan Resep';
				if ($depo != '') {
					$depox = " and gudang = '$depo'";
				} else {
					$depox = '';
				}
				$query = $this->db->query("
				SELECT a.`resepno`, 
				a.rekmed,
				(SELECT namapas FROM tbl_pasien WHERE rekmed=a.rekmed) AS namapas,
				b.totalrp,
				b.`kodebarang`, b.`namabarang`, b.`satuan`, b.`qty`, b.`hna`
				FROM tbl_apohresep a
				JOIN tbl_apodresep b ON a.`resepno`=b.`resepno`
				WHERE a.tglresep between '$dari' and '$sampai' $jenis $depox and a.koders = '$unit'
				")->result();
			} else if ($laporan == 4) {
				$judul = '04 ANALISA PENJUALAN OBAT';
				if ($depo != '') {
					$depox = " and h.gudang = '$depo'";
				} else {
					$depox = '';
				}
				$query = $this->db->query(
					"SELECT * FROM ( 
						SELECT kodebarang, namabarang, 
							satuan, harga_jual, SUM(totalnet) AS totalnet, SUM(qty) AS qty, 
							SUM(harga_pokok) AS harga_pokok, SUM(total_hpp) AS total_hpp, SUM(ppnrp) AS ppnrp, 
							SUM(rugi_laba) AS rugi_laba, SUM(persen) AS persen
						FROM (
							SELECT d.kodebarang, b.namabarang, b.satuan1 AS satuan, b.hargajual AS harga_jual, d.qty,
							(d.qty*b.hargajual) AS totalnet, b.hargabelippn AS harga_pokok, (d.qty*b.hargabelippn) AS total_hpp, d.ppnrp,
							((d.qty*hargajual)-(d.qty*b.hargabelippn)) AS rugi_laba,
							((((d.qty*hargajual)-(d.qty*b.hargabelippn))/(d.qty*hargajual)) * 100) AS persen
							FROM tbl_apohresep h 
							JOIN tbl_apodresep d ON h.resepno=d.resepno
							JOIN tbl_barang b ON b.kodebarang=d.kodebarang
							WHERE h.tglresep BETWEEN '$dari' AND '$sampai' AND d.koders = '$unit' $depox
						) AS resep GROUP BY kodebarang
						UNION ALL
						SELECT CONCAT('[RACIKAN] - ',kodebarang) kodebarang, CONCAT('[RACIKAN] - ',namabarang) AS namabarang, 
							satuan, harga_jual, SUM(totalnet) AS totalnet, SUM(qty) AS qty,
							SUM(harga_pokok) AS harga_pokok, SUM(total_hpp) AS total_hpp, SUM(ppnrp) AS ppnrp, 
							SUM(rugi_laba) AS rugi_laba, SUM(persen) AS persen
						FROM (
							SELECT d.kodebarang, b.namabarang, b.satuan1 AS satuan, b.hargajual AS harga_jual, d.qty,
							(d.qty*b.hargajual) AS totalnet, b.hargabelippn AS harga_pokok, (d.qty*b.hargabelippn) AS total_hpp, d.ppnrp,
							((d.qty*hargajual)-(d.qty*b.hargabelippn)) AS rugi_laba,
							((((d.qty*hargajual)-(d.qty*b.hargabelippn))/(d.qty*hargajual)) * 100) AS persen
							FROM tbl_apohresep h 
							JOIN tbl_apodresep d ON h.resepno=d.resepno
							JOIN tbl_barang b ON b.kodebarang=d.kodebarang
							WHERE h.tglresep BETWEEN '$dari' AND '$sampai' AND d.koders = '$unit' $depox
						) AS racik GROUP BY kodebarang
					) semua ORDER BY qty DESC"
				)->result();
			}
		}
		$profile = $this->M_global->_LoadProfileLap();
		$unit = $this->session->userdata('unit');
		$nama_usaha = $profile->nama_usaha;
		$alamat1  = $profile->alamat1;
		$alamat2  = $profile->alamat2;
		$profile = data_master('tbl_namers', array('koders' => $unit));
		$nama_usaha = $profile->namars;
		$alamat1 = $profile->alamat;
		$alamat2 = $profile->kota;
		$pdf = new simkeu_nota();
		$pdf->setID($nama_usaha, $alamat1, $alamat2);
		$pdf->setjudul($judul . ' CABANG ' . $unit);
		$pdf->setsubjudul('Dari tgl ' . date('d-m-Y', strtotime($dari)) . ' Sampai tgl ' . date('d-m-Y', strtotime($sampai)));
		if ($laporan == '1') {
			$pdf->addpage("L", "A4");
			$pdf->setsize("L", "A4");
			$pdf->SetFont('Arial', 'B', 16);
			$pdf->ln(2);
			$pdf->SetFillColor(0, 0, 139);
			$pdf->settextcolor(0);
			$pdf->setfont('Arial', 'B', 6);
			$pdf->Cell(5, 6, 'No', 1, 0, 'C');
			$pdf->Cell(30, 6, 'Tr No', 1, 0, 'C');
			$pdf->Cell(15, 6, 'Tanggal', 1, 0, 'C');
			$pdf->Cell(30, 6, 'Noreg/Rekmed', 1, 0, 'C');
			$pdf->Cell(40, 6, 'Nama Pasien', 1, 0, 'C');
			$pdf->Cell(40, 6, 'Resep Dari', 1, 0, 'C');
			$pdf->Cell(40, 6, 'Nama Obat', 1, 0, 'C');
			$pdf->Cell(10, 6, 'Qty', 1, 0, 'C');
			$pdf->Cell(10, 6, 'Satuan', 1, 0, 'C');
			$pdf->Cell(10, 6, 'Disc%', 1, 0, 'C');
			$pdf->Cell(30, 6, 'Harga Sat', 1, 0, 'C');
			$pdf->Cell(20, 6, 'Total', 1, 0, 'C');
			$pdf->ln();
			$no = 1;
			$total = 0;
			foreach ($query as $q) {
				$pdf->Cell(5, 6, $no++, 1, 0, 'L');
				$pdf->Cell(30, 6, $q->resepno, 1, 0, 'L');
				$pdf->Cell(15, 6, date('Y-m-d', strtotime($q->tglresep)), 1, 0, 'C');
				if ($q->noreg != '') {
					$noreg = $q->noreg;
				} else {
					$noreg = 'LOKAL';
				}
				$pdf->Cell(30, 6, $noreg, 1, 0, 'L');
				$pdf->Cell(40, 6, $q->namapas, 1, 0, 'L');
				$pdf->Cell(40, 6, $q->nadokter, 1, 0, 'L');
				$pdf->Cell(40, 6, $q->namabarang, 1, 0, 'L');
				$pdf->Cell(10, 6, number_format($q->qty, 2), 1, 0, 'R');
				$pdf->Cell(10, 6, $q->satuan, 1, 0, 'L');
				$pdf->Cell(10, 6, $q->discount, 1, 0, 'R');
				$pdf->Cell(30, 6, number_format($q->price, 2), 1, 0, 'R');
				$pdf->Cell(20, 6, number_format($q->totalrp, 2), 1, 0, 'R');
				$pdf->ln();
				$total += $q->totalrp;
			}
			$pdf->Cell(260, 6, 'TOTAL', 1, 0, 'C');
			$pdf->Cell(20, 6, number_format($total, 2), 1, 0, 'R');
		} else if ($laporan == 2) {
			$gudang = $this->db->query("SELECT depocode, keterangan FROM tbl_depo WHERE depocode = '$depo'")->row();
			$avatar = $this->session->userdata("avatar_cabang");
			if ($depo != '') {
				$gdx = $gudang->keterangan;
			} else {
				$gdx = 'SEMUA GUDANG';
			}
			$kop       = $this->M_cetak->kop($unit);
			$namars    = $kop['namars'];
			$alamat    = $kop['alamat'];
			$alamat2   = $kop['alamat2'];
			$phone     = $kop['phone'];
			$whatsapp  = $kop['whatsapp'];
			$npwp      = $kop['npwp'];
			$chari  = '';
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
					<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
						<tr>
							<td width=\"15%\" style=\"text-align:left;border-bottom: none;\">Dari Gudang</td>
							<td width=\"5%\" style=\"text-align:center;border-bottom: none;\">:</td>
							<td width=\"30%\" style=\"text-align:left;border-bottom: none;\">" . $gdx . "</td>
							<td width=\"15%\" style=\"text-align:left;border-bottom: none;\">Tanggal</td>
							<td width=\"5%\" style=\"text-align:center;border-bottom: none;\">:</td>
							<td width=\"30%\" style=\"text-align:left;border-bottom: none;\">" . date('d-m-Y', strtotime($dari)) . ' / ' . date('d-m-Y', strtotime($sampai)) . "</td>
						</tr>
					</table>";
			$chari .= "
					<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
						<thead>
							<tr>
								<td style=\"border:0\" align=\"center\"><br></td>
							</tr>
							<tr>
								<td width=\"3%\" align=\"center\" rowspan=\"2\"><br>No</td>
								<td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Kode Barang</td>
								<td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Nama Barang</td>
								<td width=\"4%\" align=\"center\" rowspan=\"2\"><br>Satuan</td>
								<td width=\"24%\" align=\"center\" colspan=\"2\"><br>Tunai</td>
								<td width=\"24%\" align=\"center\" colspan=\"2\"><br>Lokal</td>
								<td width=\"24%\" align=\"center\" colspan=\"2\"><br>Kirim</td>
								<td width=\"24%\" align=\"center\" colspan=\"2\"><br>Spa</td>
								<td width=\"24%\" align=\"center\" colspan=\"2\"><br>Apotik</td>
								<td width=\"24%\" align=\"center\" colspan=\"2\"><br>Cabang</td>
								<td width=\"24%\" align=\"center\" colspan=\"2\"><br>Total</td>
							</tr>
							<tr>
								<td width=\"4%\" align=\"center\"><br>Qty</td>
								<td width=\"4%\" align=\"center\"><br>Rp</td>
								<td width=\"4%\" align=\"center\"><br>Qty</td>
								<td width=\"4%\" align=\"center\"><br>Rp</td>
								<td width=\"4%\" align=\"center\"><br>Qty</td>
								<td width=\"4%\" align=\"center\"><br>Rp</td>
								<td width=\"4%\" align=\"center\"><br>Qty</td>
								<td width=\"4%\" align=\"center\"><br>Rp</td>
								<td width=\"4%\" align=\"center\"><br>Qty</td>
								<td width=\"4%\" align=\"center\"><br>Rp</td>
								<td width=\"4%\" align=\"center\"><br>Qty</td>
								<td width=\"4%\" align=\"center\"><br>Rp</td>
								<td width=\"4%\" align=\"center\"><br>Qty</td>
								<td width=\"4%\" align=\"center\"><br>Rp</td>
							</tr>
						</thead>";
			$no = 1;
			foreach ($query as $q) {
				$kodebarang = $q->kodebarang;
				$namabarang = $q->namabarang;
				$satuan = $q->satuan;
				$qty_tunai = number_format($q->qty_tunai);
				$rp_tunai = number_format($q->rp_tunai);
				$qty_lokal = number_format($q->qty_lokal);
				$rp_lokal = number_format($q->rp_lokal);
				$qty_kirim = number_format($q->qty_kirim);
				$rp_kirim = number_format($q->rp_kirim);
				$qty_spa = number_format($q->qty_spa);
				$rp_spa = number_format($q->rp_spa);
				$qty_apotik = number_format($q->qty_apotik);
				$rp_apotik = number_format($q->rp_apotik);
				$qty_cabang = number_format($q->qty_cabang);
				$rp_cabang = number_format($q->rp_cabang);
				$jualtotal_qty = number_format($q->jualtotal_qty);
				$jualtotal_rp = number_format($q->jualtotal_rp);

				$chari .= "<tr>
								<td align=\"left\">" . $no++ . "</td>
								<td align=\"left\">$kodebarang</td>
								<td align=\"left\">$namabarang</td>
								<td align=\"left\">$satuan</td>
								<td align=\"right\">$qty_tunai</td>
								<td align=\"right\">$rp_tunai</td>
								<td align=\"right\">$qty_lokal</td>
								<td align=\"right\">$rp_lokal</td>
								<td align=\"right\">$qty_kirim</td>
								<td align=\"right\">$rp_kirim</td>
								<td align=\"right\">$qty_spa</td>
								<td align=\"right\">$rp_spa</td>
								<td align=\"right\">$qty_apotik</td>
								<td align=\"right\">$rp_apotik</td>
								<td align=\"right\">$qty_cabang</td>
								<td align=\"right\">$rp_cabang</td>
								<td align=\"right\">$jualtotal_qty</td>
								<td align=\"right\">$jualtotal_rp</td>
							</tr>";
			}
			$chari .= "</table>";
			$judul = '02 LAPORAN PENJUALAN OBAT';
			echo ("<title>$judul</title>");
			$this->M_cetak->mpdf('L', 'A4', $judul, $chari, '.PDF', 10, 10, 10, 2);
		} else if ($laporan == 3) {
			$pdf->addpage("L", "A4");
			$pdf->setsize("L", "A4");
			$pdf->SetFont('Arial', 'B', 16);
			$pdf->ln(2);
			$pdf->SetFillColor(0, 0, 139);
			$pdf->settextcolor(0);
			$pdf->setfont('Arial', 'B', 6);
			$pdf->Cell(5, 6, 'No', 1, 0, 'C');
			$pdf->Cell(30, 6, 'No Resep', 1, 0, 'C');
			$pdf->Cell(50, 6, 'Nama', 1, 0, 'C');
			$pdf->Cell(30, 6, 'Rekmed', 1, 0, 'C');
			$pdf->Cell(50, 6, 'Kode Obat', 1, 0, 'C');
			$pdf->Cell(50, 6, 'Nama Obat', 1, 0, 'C');
			$pdf->Cell(15, 6, 'Satuan', 1, 0, 'C');
			$pdf->Cell(10, 6, 'Qty', 1, 0, 'C');
			$pdf->Cell(20, 6, 'Total', 1, 0, 'C');
			$pdf->Cell(20, 6, 'Total HNA', 1, 0, 'C');
			$pdf->ln();
			$no = 1;
			foreach ($query as $q) {
				$pdf->Cell(5, 6, $no++, 1, 0, 'R');
				$pdf->Cell(30, 6, $q->resepno, 1, 0, 'L');
				$pdf->Cell(50, 6, $q->namapas, 1, 0, 'L');
				$pdf->Cell(30, 6, $q->rekmed, 1, 0, 'L');
				$pdf->Cell(50, 6, $q->kodebarang, 1, 0, 'L');
				$pdf->Cell(50, 6, $q->namabarang, 1, 0, 'L');
				$pdf->Cell(15, 6, $q->satuan, 1, 0, 'L');
				$pdf->Cell(10, 6, number_format($q->qty, 2), 1, 0, 'R');
				$pdf->Cell(20, 6, number_format($q->totalrp, 2), 1, 0, 'R');
				$pdf->Cell(20, 6, number_format($q->hna, 2), 1, 0, 'R');
				$pdf->ln();
			}
		} else {
			$pdf->addpage("L", "A4");
			$pdf->setsize("L", "A4");
			$pdf->SetFont('Arial', 'B', 16);
			$pdf->ln(2);
			$pdf->SetFillColor(0, 0, 139);
			$pdf->settextcolor(0);
			$pdf->setfont('Arial', 'B', 6);
			$pdf->Cell(30, 6, '', 'TLR', 0, 'C');
			$pdf->Cell(55, 6, '', 'TLR', 0, 'C');
			$pdf->Cell(25, 6, '', 'TLR', 0, 'C');
			$pdf->Cell(30, 6, '', 'TLR', 0, 'C');
			$pdf->Cell(40, 6, 'Penjualan', 'TLR', 0, 'C');
			$pdf->Cell(40, 6, 'Harga Pokok Pembelian', 'TLR', 0, 'C');
			$pdf->Cell(60, 6, 'Analisa Rugi Laba', 'TLR', 1, 'C');
			$pdf->Cell(30, 6, 'Kode', 'BLR', 0, 'C');
			$pdf->Cell(55, 6, 'Nama Barang', 'BLR', 0, 'C');
			$pdf->Cell(25, 6, 'Satuan', 'BLR', 0, 'C');
			$pdf->Cell(30, 6, 'Qty', 'BLR', 0, 'C');
			$pdf->Cell(20, 6, 'Harga Jual', 1, 0, 'C');
			$pdf->Cell(20, 6, 'Total Net Rp', 1, 0, 'C');
			$pdf->Cell(20, 6, 'Harga Pokok', 1, 0, 'C');
			$pdf->Cell(20, 6, 'Total HPP', 1, 0, 'C');
			$pdf->Cell(20, 6, 'PPN Rp', 1, 0, 'C');
			$pdf->Cell(20, 6, 'Rugi/Laba', 1, 0, 'C');
			$pdf->Cell(20, 6, '%', 1, 0, 'C');
			$pdf->ln();
			foreach ($query as $q) {
				$pdf->Cell(30, 6, $q->kodebarang, 1, 0, 'L');
				$pdf->Cell(55, 6, $q->namabarang, 1, 0, 'L');
				$pdf->Cell(25, 6, $q->satuan, 1, 0, 'R');
				$pdf->Cell(30, 6, number_format($q->qty, 2), 1, 0, 'R');
				$pdf->Cell(20, 6, number_format($q->harga_jual, 2), 1, 0, 'R');
				$pdf->Cell(20, 6, number_format($q->totalnet, 2), 1, 0, 'R');
				$pdf->Cell(20, 6, number_format($q->harga_pokok, 2), 1, 0, 'R');
				$pdf->Cell(20, 6, number_format($q->total_hpp, 2), 1, 0, 'R');
				$pdf->Cell(20, 6, number_format($q->ppnrp, 2), 1, 0, 'R');
				$pdf->Cell(20, 6, number_format($q->rugi_laba, 2), 1, 0, 'R');
				$pdf->Cell(20, 6, number_format($q->persen, 2) . ' %', 1, 0, 'R');
				$pdf->ln();
			}
		}
		if ($laporan != 2) {
			$pdf->Output();
		}
	}
	public function excel()
	{
		$cek = $this->session->userdata('level');
		$cabang = $this->session->userdata('unit');
		$unit = $cabang;

		$dari = $this->input->get('dari');
		$sampai = $this->input->get('sampai');
		$jenisx = $this->input->get('jenis');
		if ($jenisx == 3) {
			$jenis = '';
		} else if ($jenisx == 1) {
			$jenis = 'AND jenisjual = 1';
		} else {
			$jenis = 'AND jenisjual = 2';
		}
		$depo = $this->input->get('depo');
		$laporan = $this->input->get('laporan');

		if (!empty($cek)) {
			if ($laporan == 1) {
				if ($depo != '') {
					$query = $this->db->query("SELECT resepno, tglresep, view_penjualan_barang.noreg, view_penjualan_barang.gudang, nadokter, (SELECT namapas FROM tbl_pasien WHERE rekmed = view_penjualan_barang.rekmed) AS namapas, namabarangoriginal AS namabarang, qty, satuan, discount, price, totalrp FROM view_penjualan_barang JOIN tbl_regist ON tbl_regist.rekmed = view_penjualan_barang.rekmed JOIN tbl_dokter ON tbl_dokter.kodokter=tbl_regist.kodokter WHERE view_penjualan_barang.koders = '$unit' AND tglresep BETWEEN '$dari' AND '$sampai' $jenis and view_penjualan_barang.gudang = '$depo' GROUP BY resepno")->result();
				} else {
					$query = $this->db->query("SELECT resepno, tglresep, view_penjualan_barang.noreg, view_penjualan_barang.gudang, nadokter, (SELECT namapas FROM tbl_pasien WHERE rekmed = view_penjualan_barang.rekmed) AS namapas, namabarangoriginal AS namabarang, qty, satuan, discount, price, totalrp FROM view_penjualan_barang JOIN tbl_regist ON tbl_regist.rekmed = view_penjualan_barang.rekmed JOIN tbl_dokter ON tbl_dokter.kodokter=tbl_regist.kodokter WHERE view_penjualan_barang.koders = '$unit' AND tglresep BETWEEN '$dari' AND '$sampai' $jenis GROUP BY resepno")->result();
				}
				$data = [
					'judul' => '01 LAPORAN PENJUALAN RESEP PERDOKTER',
					'query' => $query,
				];

				$this->load->view('penjualan/v_lap1', $data);
				// var_dump($data);die;
			} else if ($laporan == 2) {
				if ($depo != '') {
					$depox = " and gudang = '$depo'";
				} else {
					$depox = '';
				}
				$query = $this->db->query("SELECT p.*,
				qty_tunai +qty_lokal +qty_kirim +qty_spa +qty_apotik as jualtotal_qty, 
				rp_tunai +rp_lokal +rp_kirim +rp_spa +rp_apotik as jualtotal_rp 
				FROM(
				SELECT kodebarang,namabarang, tglresep, jenispas,
				sum( case when jenispas='1' then qty else 0 end) as qty_tunai,
				sum( case when jenispas='1' then price else 0 end) as rp_tunai,
				sum( case when jenispas='2' then qty else 0 end) as qty_lokal,
				sum( case when jenispas='2' then price else 0 end) as rp_lokal,
				sum( case when jenispas='3' then qty else 0 end) as qty_kirim,
				sum( case when jenispas='3' then price else 0 end) as rp_kirim,
				sum( case when jenispas='4' then qty else 0 end) as qty_spa,
				sum( case when jenispas='4' then price else 0 end) as rp_spa,
				sum( case when jenispas='7' then qty else 0 end) as qty_apotik,
				sum( case when jenispas='7' then price else 0 end) as rp_apotik
				FROM vie_penjualan_barang_racikan where koders = '$unit' $jenis $depox
				group by kodebarang,namabarang
				)p where tglresep between '$dari' and '$sampai'
				order by p.kodebarang
				")->result();
				$data = [
					'judul' => '02 LAPORAN PENJUALAN OBAT',
					'query' => $query,
				];
				$this->load->view('penjualan/v_lap2', $data);
			} else if ($laporan == 3) {
				if ($depo != '') {
					$depox = " and gudang = '$depo'";
				} else {
					$depox = '';
				}
				$query = $this->db->query("
				SELECT a.`resepno`, 
				a.rekmed,
				(SELECT namapas FROM tbl_pasien WHERE rekmed=a.rekmed) AS namapas,
				b.totalrp,
				b.`kodebarang`, b.`namabarang`, b.`satuan`, b.`qty`, b.`hna`
				FROM tbl_apohresep a
				JOIN tbl_apodresep b ON a.`resepno`=b.`resepno`
				WHERE a.tglresep between '$dari' and '$sampai' $jenis $depox and a.koders = '$unit'
				")->result();
				$data = [
					'judul' => 'LAPORAN PENJUALAN RESEP PERDOKTER',
					'query' => $query,
				];
				$this->load->view('penjualan/v_lap3', $data);
			} else if ($laporan == 4) {
				if ($depo != '') {
					$depox = " and gudang = '$depo'";
				} else {
					$depox = '';
				}
				$query = $this->db->query("
				SELECT x.* FROM (
				SELECT b.kodebarang, b.namabarang, b.satuan1 as satuan, a.qty,
				b.hargajual AS harga_jual,
				(a.qty*hargajual) as totalnet,
				b.hargabelippn AS harga_pokok,
				(a.qty*b.hargabelippn) AS total_hpp,
				c.ppnrp,
				((a.qty*hargajual)-(a.qty*b.hargabelippn)) AS rugi_laba, 
				((((a.qty*hargajual)-(a.qty*b.hargabelippn))/(a.qty*hargajual)) * 100) as persen
				FROM tbl_apodetresep a
				JOIN tbl_apohresep d ON a.resepno = d.resepno
				JOIN tbl_aporacik c ON a.resepno=c.resepno
				JOIN tbl_barang b ON a.kodebarang=b.kodebarang
				WHERE d.tglresep between '$dari' AND '$sampai' AND d.koders = '$unit' $depox
				GROUP BY a.kodebarang
				) x ORDER BY x.qty DESC
				")->result();
				$data = [
					'judul' => 'ANALISA PENJUALAN OBAT',
					'query' => $query,
				];
				$this->load->view('penjualan/v_lap4', $data);
			}
		}
	}
}
