<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_pembelian_log extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_global', 'M_global');
		$this->load->model('M_cetak');
		$this->load->model('M_Lap_Farm');
		$this->load->helper('simkeu_rpt');
		$this->load->helper('simkeu_nota');
		$this->session->set_userdata('menuapp', '4000');
		$this->session->set_userdata('submenuapp', '5504');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$unit = $this->session->userdata('unit');
			$this->load->helper('url');
			$d['cabang'] = $this->db->get('tbl_namers')->result();
			$this->load->view('logistik/v_laporan_pembelian', $d);
		} else {
			header('location:' . base_url());
		}
	}

	public function tampil()
	{
		$cekk = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');
		$profile = $this->M_global->_LoadProfileLap();
		$nama_usaha = $profile->nama_usaha;
		$motto = '';
		$alamat = '';
		$idlap = $this->input->get('idlap');
		$tgl1 = $this->input->get('tgl1');
		$tgl2 = $this->input->get('tgl2');
		$vendor = $this->input->get('vendor');
		$cek = $this->input->get('cekk');
		$_tgl1 = date('Y-m-d', strtotime($tgl1));
		$_tgl2 = date('Y-m-d', strtotime($tgl2));
		$_peri = 'Dari ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2));

		$kop       = $this->M_cetak->kop($unit);
		$namars    = $kop['namars'];
		$alamat    = $kop['alamat'];
		$alamat2   = $kop['alamat2'];
		$phone     = $kop['phone'];
		$whatsapp  = $kop['whatsapp'];
		$npwp      = $kop['npwp'];
		if (!empty($cekk)) {
			if ($idlap == 101) {
				$x = "SELECT a.terima_no,a.terima_date,a.invoice_no,a.sj_no,b.kodebarang, c.namabarang,b.qty_terima,b.satuan,b.price,(b.totalrp + b.vatrp) as totalnet,b.discountrp,b.vat, b.vatrp As vatrp1, b.po_no, a.diskontotal, (b.qty_terima * b.price) as totalrp FROM tbl_apohterimalog a JOIN tbl_apodterimalog b ON b.terima_no = a.terima_no JOIN tbl_logbarang c ON c.kodebarang = b.kodebarang JOIN tbl_vendor d ON d.vendor_id = a.vendor_id  WHERE a.vendor_id='$vendor' and a.koders='$unit' and a.terima_date between '$tgl1' and '$tgl2' ORDER BY a.terima_date, a.terima_no";
				$data = [
					'judul' => 'LAPORAN PEMBELIAN BARANG (DETAIL INVOICE)',
					'query' => $this->db->query($x)->result(),
					'namars' => $namars,
					'alamat' => $alamat,
					'alamat2' => $alamat2,
					'phone' => $phone,
					'whatsapp' => $whatsapp,
					'npwp' => $npwp,
					'_peri' => $_peri,
				];
				$this->load->view('logistik/Pembelian/v_lap_01', $data);
			} else if ($idlap == 102) {
				$x = "SELECT a.terima_date , a.sj_no, a.vatrp , a.materai , a.koders, a.terima_no, (sum(c.discountrp)) as diskontotal, b.vendor_name, b.vendor_id, (sum(c.qty_terima * c.price)) as totalrp, c.vat, a.vatrp, (sum(c.totalrp) + a.vatrp + a.materai) as totalnet FROM tbl_apohterimalog AS a JOIN tbl_vendor AS b ON a.vendor_id = b.vendor_id JOIN tbl_apodterimalog AS c ON a.terima_no = c.terima_no WHERE b.vendor_id ='$vendor' AND a.koders = '$unit' AND a.terima_date BETWEEN '$tgl1' AND '$tgl2' group by a.terima_no";
				$data = [
					'judul' => 'LAPORAN PEMBELIAN BARANG (REKAP INVOICE)',
					'query' => $this->db->query($x)->result(),
					'namars' => $namars,
					'alamat' => $alamat,
					'alamat2' => $alamat2,
					'phone' => $phone,
					'whatsapp' => $whatsapp,
					'npwp' => $npwp,
					'_peri' => $_peri,
				];
				$this->load->view('logistik/Pembelian/v_lap_02', $data);
			} else if ($idlap == 103) {
				$x = "SELECT b.kodebarang, 
				(select namabarang from tbl_logbarang where kodebarang = b.`kodebarang`) as namabarang, 
				b.satuan, 
				b.qty_terima, 
				b.totalrp, 
				(b.totalrp / b.qty_terima ) AS ratarata, 
				b.koders, c.vendor_name 
				FROM tbl_apodterimalog AS b
				JOIN tbl_apohterimalog AS d ON b.`terima_no`= d.terima_no 
				JOIN tbl_vendor AS c ON d.vendor_id = c.vendor_id 
				WHERE d.vendor_id='$vendor' 
				and b.koders = '$unit' 
				and d.terima_date between '$tgl1' and '$tgl2'";
				$vendorx = $this->db->query("SELECT c.vendor_name FROM tbl_logbarang AS a JOIN tbl_apodterimalog AS b ON a.kodebarang = b.kodebarang JOIN tbl_vendor AS c ON a.vendor_id = c.vendor_id WHERE a.vendor_id='$vendor' and b.koders = '$unit' limit 1")->result();
				$query_vendor = $this->db->query("SELECT * FROM tbl_vendor WHERE vendor_id ='$vendor'")->row();
				$data = [
					'judul' => 'REKAP PEMBELIAN BARANG PERSUPPLIER DAN ITEM',
					'query' => $this->db->query($x)->result(),
					'namars' => $namars,
					'alamat' => $alamat,
					'alamat2' => $alamat2,
					'phone' => $phone,
					'whatsapp' => $whatsapp,
					'npwp' => $npwp,
					'_peri' => $_peri,
					'vendorx' => $vendorx,
					'query_vendor' => $query_vendor,
				];
				$this->load->view('logistik/Pembelian/v_lap_03', $data);
			} else if ($idlap == 104) {
				$x = "SELECT a.vatrp, a.materai, b.discountrp, b.qty_terima , b.discountrp, b.totalrp, (b.totalrp / b.qty_terima ) AS ratarata, c.vendor_name , c.vendor_id FROM tbl_apohterimalog as a JOIN tbl_apodterimalog AS b ON a.terima_no = b.terima_no JOIN tbl_vendor AS c ON a.vendor_id = c.vendor_id WHERE a.vendor_id ='$vendor' AND a.koders = '$unit' and a.terima_date between '$tgl1' and '$tgl2'";
				$data = [
					'judul' => 'REKAP PEMBELIAN BARANG PER SUPPLIER TOTAL',
					'query' => $this->db->query($x)->result(),
					'namars' => $namars,
					'alamat' => $alamat,
					'alamat2' => $alamat2,
					'phone' => $phone,
					'whatsapp' => $whatsapp,
					'npwp' => $npwp,
					'_peri' => $_peri,
				];
				$this->load->view('logistik/Pembelian/v_lap_04', $data);
			} else if ($idlap == 105) {
				$x = "SELECT b.kodebarang, 
				(select namabarang from tbl_logbarang where kodebarang = b.kodebarang) as namabarang, 
				d.vendor_id, 
				b.satuan, 
				b.qty_terima, 
				(b.totalrp / b.qty_terima ) AS ratarata, 
				b.koders, 
				c.vendor_id, 
				c.vendor_name 
				FROM tbl_apodterimalog AS b
				JOIN tbl_apohterimalog AS d on b.terima_no = d.terima_no 
				JOIN tbl_vendor AS c ON d.vendor_id = c.vendor_id 
				WHERE d.vendor_id = '$vendor' 
				AND b.koders = '$unit' 
				and d.terima_date between '$tgl1' and '$tgl2'";
				$data = [
					'judul' => 'LAPORAN PEMBELIAN BARANG PER ITEM (TOTAL)',
					'query' => $this->db->query($x)->result(),
					'namars' => $namars,
					'alamat' => $alamat,
					'alamat2' => $alamat2,
					'phone' => $phone,
					'whatsapp' => $whatsapp,
					'npwp' => $npwp,
					'_peri' => $_peri,
				];
				$this->load->view('logistik/Pembelian/v_lap_05', $data);
			} else if ($idlap == 106) {
				$x  = "SELECT a.terima_date , a.koders,  b.price, b.satuan , b.qty_terima, b.po_no, c.vendor_id , c.vendor_name, d.kodebarang ,d.namabarang FROM tbl_apohterimalog AS a JOIN  tbl_apodterimalog AS b ON a.terima_no = b.terima_no JOIN tbl_vendor AS c ON a.vendor_id = c.vendor_id JOIN tbl_logbarang AS d ON b.kodebarang = d.kodebarang WHERE a.vendor_id = '$vendor' AND  a.koders = '$unit' and a.terima_date between '$tgl1' and '$tgl2'";
				$data = [
					'judul' => 'LAPORAN STATUS ORDER PEMBELIAN',
					'query' => $this->db->query($x)->result(),
					'namars' => $namars,
					'alamat' => $alamat,
					'alamat2' => $alamat2,
					'phone' => $phone,
					'whatsapp' => $whatsapp,
					'npwp' => $npwp,
					'_peri' => $_peri,
				];
				$this->load->view('logistik/Pembelian/v_lap_06', $data);
			} else if ($idlap == 107) {
				$x = 'select a.retur_no, a.retur_date, a.terima_no, b.kodebarang, (select namabarang from tbl_logbarang where kodebarang = b.kodebarang) as namabarang, b.qty_retur, b.satuan, b.price, b.totalrp, c.po_no from tbl_apohreturbelilog a join tbl_apodreturbelilog b on a.retur_no=b.retur_no JOIN tbl_apodterimalog c ON c.terima_no = a.terima_no join tbl_apohterimalog d on c.terima_no = d.terima_no where a.koders = "' . $unit . '" and d.vendor_id = "' . $vendor . '" and d.terima_date between "' . $tgl1 . '" and "' . $tgl2 . '" group by b.kodebarang';
				$data = [
					'judul' => 'LAPORAN RETURN PEMBELIAN',
					'query' => $this->db->query($x)->result(),
					'namars' => $namars,
					'alamat' => $alamat,
					'alamat2' => $alamat2,
					'phone' => $phone,
					'whatsapp' => $whatsapp,
					'npwp' => $npwp,
					'_peri' => $_peri,
				];
				$this->load->view('logistik/Pembelian/v_lap_07', $data);
			} else if ($idlap == 108) {
				$x = "
                    SELECT 
                         (SELECT vendor_name FROM tbl_vendor WHERE vendor_id = tbl_apohpolog.vendor_id) AS rekanan,
                         tbl_apohpolog.ref_no AS no_faktur,
                         0 AS obat,
                         0 AS alkes_rutin,
                         0 AS alk_investasi,
                         0 AS bahan_kimia,
                         0 AS gas_medik,
                         0 AS pemeliharaan,
                         0 AS sewa,
                         0 AS pelengkapan,
                         0 AS materai,
                         tbl_apodpolog.vatrp AS lain2,
                         tbl_apodpolog.total AS jumlah
                    FROM tbl_apohpolog
                    JOIN tbl_apodpolog ON tbl_apohpolog.po_no=tbl_apodpolog.po_no
                    WHERE vendor_id = '$vendor' AND po_date BETWEEN '$tgl1' AND '$tgl2'

                    UNION ALL

                    SELECT
                         (SELECT vendor_name FROM tbl_vendor WHERE vendor_id = tbl_apohterimalog.vendor_id) AS rekanan,
                         invoice_no AS no_faktur,
                         0 AS obat,
                         0 AS alkes_rutin,
                         0 AS alk_investasi,
                         0 AS bahan_kimia,
                         0 AS gas_medik,
                         0 AS pemeliharaan,
                         0 AS sewa,
                         0 AS pelengkapan,
                         materai,
                         tbl_apodterimalog.vatrp AS lain2,
                         tbl_apodterimalog.totalrp AS jumlah
                    FROM tbl_apohterimalog
                    JOIN tbl_apodterimalog ON tbl_apohterimalog.terima_no=tbl_apodterimalog.terima_no
                    WHERE vendor_id = '$vendor' AND terima_date BETWEEN '$tgl1' AND '$tgl2'

                    UNION ALL

                    SELECT
                         (SELECT vendor_name FROM tbl_vendor WHERE vendor_id = tbl_apohreturbelilog.vendor_id) AS rekanan,
                         invoice_no AS no_faktur,
                         0 AS obat,
                         0 AS alkes_rutin,
                         0 AS alk_investasi,
                         0 AS bahan_kimia,
                         0 AS gas_medik,
                         0 AS pemeliharaan,
                         0 AS sewa,
                         0 AS pelengkapan,
                         0 AS materai,
                         tbl_apodreturbelilog.taxrp AS lain2,
                         tbl_apodreturbelilog.totalrp AS jumlah
                    FROM tbl_apohreturbelilog
                    JOIN tbl_apodreturbelilog ON tbl_apohreturbelilog.retur_no=tbl_apodreturbelilog.retur_no
                    WHERE vendor_id = '$vendor' AND retur_date BETWEEN '$tgl1' AND '$tgl2'
                    ";
				$data = [
					'judul' => 'LAPORAN HUTANG GUDANG LOGISTIK',
					'query' => $this->db->query($x)->result(),
					'namars' => $namars,
					'alamat' => $alamat,
					'alamat2' => $alamat2,
					'phone' => $phone,
					'whatsapp' => $whatsapp,
					'npwp' => $npwp,
					'_peri' => $_peri,
				];
				$this->load->view('logistik/Pembelian/v_lap_08', $data);
			}
		}
	}

	public function cetak()
	{
		$cek = $this->session->userdata('level');
		$idlap = $this->input->get('idlap');
		$cabang = $this->input->get('cabang');
		$tanggal1 = $this->input->get('tanggal1');
		$tanggal2 = $this->input->get('tanggal2');
		$vendor = $this->input->get('vendor');
		$unit = $cabang;
		if (!empty($cek)) {
			if ($idlap == 101) {
				$judul = '01 LAPORAN PEMBELIAN BARANG (DETAIL INVOICE)';
				if ($vendor != '') {
					$vendorx = "a.vendor_id='$vendor' and ";
				} else {
					$vendorx = "";
				}
				$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE $vendorx a.koders='$unit' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->result();
			} else if ($idlap == 102) {
				$judul = '02 LAPORAN PEMBELIAN BARANG (REKAP INVOICE)';
				if ($vendor != '') {
					$vendorx = "a.vendor_id ='$vendor' AND ";
				} else {
					$vendorx = "";
				}
				$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE $vendorx a.koders='$unit' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->result();
			} else if ($idlap == 103) {
				$judul = '03 REKAP PEMBELIAN BARANG PERSUPPLIER DAN ITEM';
				if ($vendor != '') {
					$vendorz = "a.vendor_id='$vendor' and ";
				} else {
					$vendorz = "";
				}
				$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE $vendorz a.koders='$unit' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->result();
				$vendorx = $this->db->query("SELECT c.vendor_name FROM tbl_logbarang AS a JOIN tbl_apodterimalog AS b ON a.kodebarang = b.kodebarang JOIN tbl_vendor AS c ON a.vendor_id = c.vendor_id WHERE a.vendor_id='$vendor' and b.koders = '$unit' limit 1")->result();
			} else if ($idlap == 104) {
				$judul = '04 REKAP PEMBELIAN BARANG PER SUPPLIER TOTAL';
				if ($vendor != '') {
					$vendorx = "a.vendor_id ='$vendor' AND";
				} else {
					$vendorx = "";
				}
				$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE $vendorx a.koders='$unit' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->result();
			} else if ($idlap == 105) {
				$judul = '05 LAPORAN PEMBELIAN BARANG PER ITEM (TOTAL)';
				if ($vendor != '') {
					$vendorx = "a.vendor_id = '$vendor' AND ";
				} else {
					$vendorx = "";
				}
				$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE $vendorx a.koders='$unit' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->result();
			} else if ($idlap == 106) {
				$judul = '06 LAPORAN STATUS ORDER PEMBELIAN';
				if ($vendor != '') {
					$vendorx = "a.vendor_id = '$vendor' AND ";
				} else {
					$vendorx = "";
				}
				$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE $vendorx a.koders='$unit' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->result();
			} else if ($idlap == 107) {
				$judul = '07 LAPORAN RETURN PEMBELIAN';
				if ($vendor != '') {
					$vendorx = "d.vendor_id = '$vendor' and ";
				} else {
					$vendorx = "";
				}
				$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE $vendorx a.koders='$unit' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->result();
			} else if ($idlap == 108) {
				$judul = '08 LAPORAN HUTANG GUDANG LOGISTIK';
				if ($vendor != '') {
					$vendorx = "vendor_id = '$vendor' AND";
				} else {
					$vendorx = "";
				}
				$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE $vendorx a.koders='$unit' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->result();
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
			$pdf->setsubjudul('');

			if ($idlap == 101) {
				$pdf->addpage("L", "A4");
				$pdf->setsize("L", "A4");
				$pdf->SetFont('Arial', 'B', 16);
				$pdf->ln(2);
				foreach ($vdr as $v) {
					$query = $this->db->query("SELECT a.terima_no,a.terima_date,a.invoice_no,a.sj_no,b.kodebarang, a.materai, b.discountrp, c.namabarang, b.qty_terima, b.satuan, b.price, (b.totalrp + b.vatrp + a.materai) as totalnet, (b.qty_terima * b.price) as totalrp, b.discount, b.vat, b.vatrp As vatrp1, b.po_no FROM tbl_apohterimalog a JOIN tbl_apodterimalog b ON b.terima_no = a.terima_no JOIN tbl_logbarang c ON c.kodebarang = b.kodebarang JOIN tbl_vendor d ON d.vendor_id = a.vendor_id WHERE a.vendor_id = '$v->vendor_id' and a.koders='$unit' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY a.terima_date, a.terima_no")->result();
					$pdf->SetFillColor(0, 0, 139);
					$pdf->settextcolor(0);
					$pdf->setfont('Arial', 'B', 8);
					$pdf->Cell(30, 6, 'Nama Vendor', 0, 0, 'L');
					$pdf->Cell(50, 6, ' : ' . $v->vendor_name, 0, 0, 'L');
					$pdf->ln();
					$pdf->Cell(30, 6, 'Dari Tanggal', 0, 0, 'L');
					$pdf->Cell(50, 6, ' : ' . date('d-m-Y', strtotime($tanggal1)), 0, 0, 'L');
					$pdf->ln();
					$pdf->Cell(30, 6, 'Sampai Tanggal', 0, 0, 'L');
					$pdf->Cell(50, 6, ' : ' . date('d-m-Y', strtotime($tanggal2)), 0, 0, 'L');
					$pdf->ln();
					$pdf->SetFillColor(0, 0, 139);
					$pdf->settextcolor(0);
					$pdf->setfont('Arial', 'B', 6);
					$pdf->Cell(5, 6, 'No', 1, 0, 'C');
					$pdf->Cell(25, 6, 'No BAPB', 1, 0, 'C');
					$pdf->Cell(17, 6, 'Tanggal', 1, 0, 'C');
					$pdf->Cell(19, 6, 'No Invoice/SJ', 1, 0, 'C');
					$pdf->Cell(19, 6, 'Kode Barang', 1, 0, 'C');
					$pdf->Cell(36, 6, 'Nama Barang', 1, 0, 'C');
					$pdf->Cell(15, 6, 'Qty', 1, 0, 'C');
					$pdf->Cell(15, 6, 'Satuan', 1, 0, 'C');
					$pdf->Cell(19, 6, 'Harga Sat', 1, 0, 'C');
					$pdf->Cell(19, 6, 'Total', 1, 0, 'C');
					$pdf->Cell(19, 6, 'Diskon', 1, 0, 'C');
					$pdf->Cell(19, 6, 'Vat', 1, 0, 'C');
					$pdf->Cell(19, 6, 'Total Net', 1, 0, 'C');
					$pdf->Cell(30, 6, 'Po No', 1, 0, 'C');
					$pdf->ln();
					$pdf->setfont('Arial', '', 6);
					$no = 1;
					$qty = 0;
					$harga = 0;
					$total = 0;
					$diskon = 0;
					$vat = 0;
					$total_net = 0;
					foreach ($query as $q) {
						$pdf->Cell(5, 6, $no++, 1, 0, 'C');
						$pdf->Cell(25, 6, $q->terima_no, 1, 0, 'C');
						$pdf->Cell(17, 6, date('d-m-Y', strtotime($q->terima_date)), 1, 0, 'C');
						$pdf->Cell(19, 6, $q->sj_no, 1, 0, 'C');
						$pdf->Cell(19, 6, $q->kodebarang, 1, 0, 'C');
						$pdf->Cell(36, 6, $q->namabarang, 1, 0, 'L');
						$pdf->Cell(15, 6, number_format($q->qty_terima, 2), 1, 0, 'R');
						$pdf->Cell(15, 6, $q->satuan, 1, 0, 'C');
						$pdf->Cell(19, 6, number_format($q->price, 2), 1, 0, 'R');
						$pdf->Cell(19, 6, number_format($q->totalrp, 2), 1, 0, 'R');
						$pdf->Cell(19, 6, number_format($q->discountrp, 2), 1, 0, 'R');
						$materai = $q->materai;
						$totalnet = $q->totalnet + $q->materai;
						$pdf->Cell(19, 6, number_format($q->vatrp1, 2), 1, 0, 'R');
						$pdf->Cell(19, 6, number_format($totalnet, 2), 1, 0, 'R');
						$pdf->Cell(30, 6, $q->po_no, 1, 0, 'L');
						$pdf->ln();
						$qty += $q->qty_terima;
						$diskon += $q->discountrp;
						$vat += $q->vatrp1;
						$total_net += $totalnet;
						$harga += $q->price;
						$total += $q->totalrp;
					}
					$pdf->Cell(121, 6, 'TOTAL', 1, 0, 'C');
					$pdf->Cell(15, 6, number_format($qty, 2), 1, 0, 'R');
					$pdf->Cell(15, 6, '', 1, 0, 'R');
					$pdf->Cell(19, 6, number_format($harga, 2), 1, 0, 'R');
					$pdf->Cell(19, 6, number_format($total, 2), 1, 0, 'R');
					$pdf->Cell(19, 6, number_format($diskon, 2), 1, 0, 'R');
					$pdf->Cell(19, 6, number_format($vat, 2), 1, 0, 'R');
					$pdf->Cell(19, 6, number_format($total_net, 2), 1, 0, 'R');
					$pdf->Cell(30, 6, '', 1, 0, 'R');
					$pdf->ln();
					$pdf->ln();
				}
			} else if ($idlap == '102') {
				$pdf->addpage("L", "A4");
				$pdf->setsize("L", "A4");
				$pdf->SetFont('Arial', 'B', 16);
				$pdf->ln(2);
				foreach ($vdr as $v) {
					$query = $this->db->query("SELECT a.terima_date , a.sj_no, a.vatrp , a.materai , a.koders, a.terima_no, (sum(c.discountrp)) as diskontotal, b.vendor_name, b.vendor_id, (sum(c.qty_terima * c.price)) as totalrp, c.vat, a.vatrp, (sum(c.totalrp) + a.vatrp + a.materai) as totalnet FROM tbl_apohterimalog AS a JOIN tbl_vendor AS b ON a.vendor_id = b.vendor_id JOIN tbl_apodterimalog AS c ON a.terima_no = c.terima_no WHERE a.vendor_id = '$v->vendor_id' and a.koders = '$unit' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2' group by a.terima_no")->result();
					$pdf->SetFillColor(0, 0, 139);
					$pdf->settextcolor(0);
					$pdf->setfont('Arial', 'B', 8);
					$pdf->Cell(30, 6, 'Nama Vendor', 0, 0, 'L');
					$pdf->Cell(50, 6, ' : ' . $v->vendor_name, 0, 0, 'L');
					$pdf->ln();
					$pdf->Cell(30, 6, 'Dari Tanggal', 0, 0, 'L');
					$pdf->Cell(50, 6, ' : ' . date('d-m-Y', strtotime($tanggal1)), 0, 0, 'L');
					$pdf->ln();
					$pdf->Cell(30, 6, 'Sampai Tanggal', 0, 0, 'L');
					$pdf->Cell(50, 6, ' : ' . date('d-m-Y', strtotime($tanggal2)), 0, 0, 'L');
					$pdf->ln();
					$pdf->SetFillColor(0, 0, 139);
					$pdf->settextcolor(0);
					$pdf->setfont('Arial', 'B', 6);
					$pdf->Cell(5, 6, 'No', 1, 0, 'C');
					$pdf->Cell(50.4, 6, 'Supplier', 1, 0, 'C');
					$pdf->Cell(40.2, 6, 'No BAPB', 1, 0, 'C');
					$pdf->Cell(25.2, 6, 'Tanggal', 1, 0, 'C');
					$pdf->Cell(28.2, 6, 'No Invoice/SJ', 1, 0, 'C');
					$pdf->Cell(25.2, 6, 'Total', 1, 0, 'C');
					$pdf->Cell(25.2, 6, 'Diskon', 1, 0, 'C');
					$pdf->Cell(25.2, 6, 'Vat', 1, 0, 'C');
					$pdf->Cell(25.2, 6, 'Materai', 1, 0, 'C');
					$pdf->Cell(25.2, 6, 'Total Net', 1, 0, 'C');
					$pdf->ln();
					$pdf->setfont('Arial', '', 6);
					$no = 1;
					$Discount = 0;
					$vatRp = 0;
					$materai = 0;
					$total = 0;
					$totalrp = 0;
					foreach ($query as $q) {
						$pdf->Cell(5, 6, $no++, 1, 0, 'C');
						$pdf->Cell(50.4, 6, $q->vendor_name, 1, 0, 'L');
						$pdf->Cell(40.2, 6, $q->terima_no, 1, 0, 'L');
						$pdf->Cell(25.2, 6, date('d-m-Y', strtotime($q->terima_date)), 1, 0, 'L');
						$pdf->Cell(28.2, 6, $q->sj_no, 1, 0, 'L');
						$pdf->Cell(25.2, 6, number_format($q->totalrp, 2), 1, 0, 'R');
						$pdf->Cell(25.2, 6, number_format($q->diskontotal, 2), 1, 0, 'R');
						$pdf->Cell(25.2, 6, number_format($q->vatrp, 2), 1, 0, 'R');
						$pdf->Cell(25.2, 6, number_format($q->materai, 2), 1, 0, 'R');
						$pdf->Cell(25.2, 6, number_format($q->totalnet, 2), 1, 0, 'R');
						$pdf->ln();
						$Discount += ($q->diskontotal);
						$vatRp += ($q->vatrp);
						$materai += ($q->materai);
						$total += ($q->totalnet);
						$totalrp += ($q->totalrp);
					}
					$pdf->Cell(149, 6, 'TOTAL', 1, 0, 'C');
					$pdf->Cell(25.2, 6, number_format($totalrp, 2), 1, 0, 'R');
					$pdf->Cell(25.2, 6, number_format($Discount, 2), 1, 0, 'R');
					$pdf->Cell(25.2, 6, number_format($vatRp, 2), 1, 0, 'R');
					$pdf->Cell(25.2, 6, number_format($materai, 2), 1, 0, 'R');
					$pdf->Cell(25.2, 6, number_format($total, 2), 1, 0, 'R');
					$pdf->ln();
					$pdf->ln();
				}
			} else if (($idlap == '103')) {
				// paper type and orientattion
				$pdf->addpage("P", "A4");
				$pdf->setsize("P", "A4");
				// set font title and size
				$pdf->SetFont('Arial', 'B', 16);
				$pdf->ln(2);
				foreach ($vdr as $v) {
					$query = $this->db->query("
					SELECT b.kodebarang, 
					(select namabarang from tbl_logbarang where kodebarang = b.`kodebarang`) as namabarang, 
					b.satuan, 
					b.qty_terima, 
					b.totalrp, 
					(b.totalrp / b.qty_terima ) AS ratarata, 
					b.koders, c.vendor_name 
					FROM tbl_apodterimalog AS b
					JOIN tbl_apohterimalog AS d ON b.`terima_no`= d.terima_no 
					JOIN tbl_vendor AS c ON d.vendor_id = c.vendor_id 
					WHERE d.vendor_id = '$v->vendor_id' and b.koders = '$unit' 
					and d.terima_date between '$tanggal1' and '$tanggal2'")->result();
					$pdf->SetFillColor(0, 0, 139);
					$pdf->settextcolor(0);
					$pdf->setfont('Arial', 'B', 8);
					$pdf->Cell(30, 6, 'Nama Vendor', 0, 0, 'L');
					$pdf->Cell(50, 6, ' : ' . $v->vendor_name, 0, 0, 'L');
					$pdf->ln();
					$pdf->Cell(30, 6, 'Dari Tanggal', 0, 0, 'L');
					$pdf->Cell(50, 6, ' : ' . date('d-m-Y', strtotime($tanggal1)), 0, 0, 'L');
					$pdf->ln();
					$pdf->Cell(30, 6, 'Sampai Tanggal', 0, 0, 'L');
					$pdf->Cell(50, 6, ' : ' . date('d-m-Y', strtotime($tanggal2)), 0, 0, 'L');
					$pdf->ln();
					$pdf->SetFillColor(0, 0, 139);
					$pdf->settextcolor(0);
					$pdf->setfont('Arial', 'B', 6);
					$pdf->Cell(8, 6, 'No', 1, 0, 'C');
					$pdf->Cell(30.3, 6, 'Kode Barang', 1, 0, 'C');
					$pdf->Cell(35.3, 6, 'Nama Barang', 1, 0, 'C');
					$pdf->Cell(25.3, 6, 'Satuan', 1, 0, 'C');
					$pdf->Cell(30.3, 6, 'Qty', 1, 0, 'C');
					$pdf->Cell(30.3, 6, 'Harga Rata-rata', 1, 0, 'C');
					$pdf->Cell(30.3, 6, 'Total', 1, 0, 'C');
					$pdf->ln();
					$pdf->setfont('Arial', '', 6);
					$no = 1;
					$total_qty = 0;
					$totalSemua = 0;
					$totalrata = 0;
					foreach ($query as $q) {
						$pdf->Cell(8, 6, $no++, 1, 0, 'C');
						$pdf->Cell(30.3, 6, $q->kodebarang, 1, 0, 'L');
						$pdf->Cell(35.3, 6, $q->namabarang, 1, 0, 'L');
						$pdf->Cell(25.3, 6, $q->satuan, 1, 0, 'C');
						$pdf->Cell(30.3, 6, number_format($q->qty_terima, 2), 1, 0, 'R');
						$pdf->Cell(30.3, 6, number_format($q->ratarata, 2), 1, 0, 'R');
						$total = $q->ratarata * $q->qty_terima;
						$pdf->Cell(30.3, 6, number_format($total, 2), 1, 0, 'R');
						$pdf->ln();
						$total_qty += ($q->qty_terima);
						$totalrata += ($q->ratarata);
						$totalSemua += ($total);
					}
					$pdf->Cell(98.9, 6, 'SUBTOTAL SUPPLIER', 1, 0, 'C');
					$pdf->Cell(30.3, 6, number_format($total_qty, 2), 1, 0, 'R');
					$pdf->Cell(30.3, 6, number_format($totalrata, 2), 1, 0, 'R');
					$pdf->Cell(30.3, 6, number_format($totalSemua, 2), 1, 0, 'R');
					$pdf->ln();
					$pdf->ln();
				}
			} else if ($idlap == '104') {
				// paper type and orientattion
				$pdf->addpage("L", "A4");
				$pdf->setsize("L", "A4");
				// set font title and size
				$pdf->SetFont('Arial', 'B', 16);
				$pdf->ln(2);
				foreach ($vdr as $v) {
					$query = $this->db->query("SELECT a.vatrp, a.materai, b.discountrp, b.qty_terima , b.discountrp, b.totalrp, (b.totalrp / b.qty_terima ) AS ratarata, c.vendor_name , c.vendor_id FROM tbl_apohterimalog as a JOIN tbl_apodterimalog AS b ON a.terima_no = b.terima_no JOIN tbl_vendor AS c ON a.vendor_id = c.vendor_id WHERE a.vendor_id = '$v->vendor_id' and a.koders = '$unit' and a.terima_date between '$tanggal1' and '$tanggal2'")->result();
					$pdf->SetFillColor(0, 0, 139);
					$pdf->settextcolor(0);
					$pdf->setfont('Arial', 'B', 8);
					$pdf->Cell(30, 6, 'Nama Vendor', 0, 0, 'L');
					$pdf->Cell(50, 6, ' : ' . $v->vendor_name, 0, 0, 'L');
					$pdf->ln();
					$pdf->Cell(30, 6, 'Dari Tanggal', 0, 0, 'L');
					$pdf->Cell(50, 6, ' : ' . date('d-m-Y', strtotime($tanggal1)), 0, 0, 'L');
					$pdf->ln();
					$pdf->Cell(30, 6, 'Sampai Tanggal', 0, 0, 'L');
					$pdf->Cell(50, 6, ' : ' . date('d-m-Y', strtotime($tanggal2)), 0, 0, 'L');
					$pdf->ln();
					$pdf->SetFillColor(0, 0, 139);
					$pdf->settextcolor(0);
					$pdf->setfont('Arial', 'B', 6);
					$pdf->Cell(8, 6, 'No', 1, 0, 'C');
					$pdf->Cell(70, 6, 'Supplier', 1, 0, 'C');
					$pdf->Cell(32, 6, 'Qty', 1, 0, 'C');
					$pdf->Cell(32, 6, 'Total', 1, 0, 'C');
					$pdf->Cell(32, 6, 'Diskon', 1, 0, 'C');
					$pdf->Cell(32, 6, 'Vat Rp', 1, 0, 'C');
					$pdf->Cell(32, 6, 'Materai', 1, 0, 'C');
					$pdf->Cell(32, 6, 'Total Net', 1, 0, 'C');
					$pdf->ln();
					$pdf->setfont('Arial', '', 6);
					$no = 1;
					$qty = 0;
					$diskon = 0;
					$vat = 0;
					$materai = 0;
					$total = 0;
					foreach ($query as $q) {
						$pdf->Cell(8, 6, $no++, 1, 0, 'C');
						$pdf->Cell(70, 6, $q->vendor_name, 1, 0, 'L');
						$pdf->Cell(32, 6, number_format($q->qty_terima, 2), 1, 0, 'R');
						$pdf->Cell(32, 6, number_format($q->totalrp, 2), 1, 0, 'R');
						$pdf->Cell(32, 6, number_format($q->discountrp, 2), 1, 0, 'R');
						$pdf->Cell(32, 6, number_format($q->vatrp, 2), 1, 0, 'R');
						$pdf->Cell(32, 6, number_format($q->materai, 2), 1, 0, 'R');
						$pdf->Cell(32, 6, number_format($q->totalrp, 2), 1, 0, 'R');
						$pdf->ln();
						$qty += ($q->qty_terima);
						$diskon += ($q->discountrp);
						$vat += ($q->vatrp);
						$materai += ($q->materai);
						$total += ($q->totalrp);
					}
					$pdf->Cell(78, 6, 'TOTAL', 1, 0, 'C');
					$pdf->Cell(32, 6, number_format($qty, 2), 1, 0, 'R');
					$pdf->Cell(32, 6, number_format($diskon, 2), 1, 0, 'R');
					$pdf->Cell(32, 6, number_format($total, 2), 1, 0, 'R');
					$pdf->Cell(32, 6, number_format($vat, 2), 1, 0, 'R');
					$pdf->Cell(32, 6, number_format($materai, 2), 1, 0, 'R');
					$pdf->Cell(32, 6, number_format($total, 2), 1, 0, 'R');
					$pdf->ln();
					$pdf->ln();
				}
				$pdf->setfont('Arial', 'B', 10);
				$pdf->Cell(270, 6, 'HOSPITAL MANAGEMENT SIMTEM', 0, 0, 'C');
				$pdf->ln();
				$pdf->setfont('Arial', '', 6);
				$pdf->Cell(270, 6, 'Tanggal Cetak : ' . date('d-m-Y'), 0, 0, 'C');
				$pdf->ln();
				$pdf->ln();
				$pdf->setfont('Arial', '', 10);
				$pdf->Cell(90, 6, 'Diketahui Oleh :', 0, 0, 'C');
				$pdf->Cell(90, 6, 'Diserahkan Oleh :', 0, 0, 'C');
				$pdf->Cell(90, 6, 'Dibuat Oleh :', 0, 0, 'C');
				$pdf->ln();
				$pdf->ln();
				$pdf->ln();
				$pdf->ln();
				$pdf->Cell(90, 6, '', 0, 0, 'C');
				$pdf->Cell(90, 6, '', 0, 0, 'C');
				$pdf->Cell(90, 6, 'Haryanto', 0, 0, 'C');
				$pdf->ln();
			} else if ($idlap == '105') {
				// paper type and orientattion
				$pdf->addpage("P", "A4");
				$pdf->setsize("P", "A4");
				// set font title and size
				$pdf->SetFont('Arial', 'B', 16);
				$pdf->ln(2);
				foreach ($vdr as $v) {
					$query = $this->db->query("
					SELECT b.kodebarang, 
					(select namabarang from tbl_logbarang where kodebarang = b.kodebarang) as namabarang, 
					d.vendor_id, 
					b.satuan, 
					b.qty_terima, 
					(b.totalrp / b.qty_terima ) AS ratarata, 
					b.koders, 
					c.vendor_id, 
					c.vendor_name 
					FROM tbl_apodterimalog AS b
					JOIN tbl_apohterimalog AS d on b.terima_no = d.terima_no 
					JOIN tbl_vendor AS c ON d.vendor_id = c.vendor_id 
					WHERE d.vendor_id = '$v->vendor_id' and b.koders = '$unit' 
					and d.terima_date between '$tanggal1' and '$tanggal2'")->result();
					$pdf->SetFillColor(0, 0, 139);
					$pdf->settextcolor(0);
					$pdf->setfont('Arial', 'B', 8);
					$pdf->Cell(30, 6, 'Nama Vendor', 0, 0, 'L');
					$pdf->Cell(50, 6, ' : ' . $v->vendor_name, 0, 0, 'L');
					$pdf->ln();
					$pdf->Cell(30, 6, 'Dari Tanggal', 0, 0, 'L');
					$pdf->Cell(50, 6, ' : ' . date('d-m-Y', strtotime($tanggal1)), 0, 0, 'L');
					$pdf->ln();
					$pdf->Cell(30, 6, 'Sampai Tanggal', 0, 0, 'L');
					$pdf->Cell(50, 6, ' : ' . date('d-m-Y', strtotime($tanggal2)), 0, 0, 'L');
					$pdf->ln();
					$pdf->SetFillColor(0, 0, 139);
					$pdf->settextcolor(0);
					$pdf->setfont('Arial', 'B', 6);
					$pdf->Cell(10, 6, 'No', 1, 0, 'C');
					$pdf->Cell(30, 6, 'Kode Barang', 1, 0, 'C');
					$pdf->Cell(40, 6, 'Nama Barang', 1, 0, 'C');
					$pdf->Cell(30, 6, 'Satuan', 1, 0, 'C');
					$pdf->Cell(20, 6, 'Qty', 1, 0, 'C');
					$pdf->Cell(30, 6, 'Harga Rata-rata', 1, 0, 'C');
					$pdf->Cell(30, 6, 'Total', 1, 0, 'C');
					$pdf->ln();
					$pdf->setfont('Arial', '', 6);
					$no = 1;
					$qty = 0;
					$ratarata = 0;
					$tot = 0;
					foreach ($query as $q) {
						$pdf->Cell(10, 6, $no++, 1, 0, 'C');
						$pdf->Cell(30, 6, $q->kodebarang, 1, 0, 'L');
						$pdf->Cell(40, 6, $q->namabarang, 1, 0, 'L');
						$pdf->Cell(30, 6, $q->satuan, 1, 0, 'L');
						$pdf->Cell(20, 6, number_format($q->qty_terima, 2), 1, 0, 'R');
						$pdf->Cell(30, 6, number_format($q->ratarata, 2), 1, 0, 'R');
						$total = $q->qty_terima * $q->ratarata;
						$pdf->Cell(30, 6, number_format($total, 2), 1, 0, 'R');
						$pdf->ln();
						$qty += $q->qty_terima;
						$ratarata += $q->ratarata;
						$tot += $total;
					}
					$pdf->Cell(110, 6, 'TOTAL', 1, 0, 'C');
					$pdf->Cell(20, 6, number_format($qty, 2), 1, 0, 'R');
					$pdf->Cell(30, 6, number_format($ratarata, 2), 1, 0, 'R');
					$pdf->Cell(30, 6, number_format($tot, 2), 1, 0, 'R');
					$pdf->ln();
					$pdf->ln();
				}
			} else if ($idlap == '106') {
				// paper type and orientattion
				$pdf->addpage("P", "A4");
				$pdf->setsize("P", "A4");
				// set font title and size
				$pdf->SetFont('Arial', 'B', 16);
				$pdf->ln(2);
				foreach ($vdr as $v) {
					$query = $this->db->query("SELECT a.terima_date , a.koders,  b.price, b.satuan , b.qty_terima, b.po_no, c.vendor_id , c.vendor_name, d.kodebarang ,d.namabarang FROM tbl_apohterimalog AS a JOIN  tbl_apodterimalog AS b ON a.terima_no = b.terima_no JOIN tbl_vendor AS c ON a.vendor_id = c.vendor_id JOIN tbl_logbarang AS d ON b.kodebarang = d.kodebarang WHERE a.vendor_id = '$v->vendor_id' and a.koders = '$unit' and a.terima_date between '$tanggal1' and '$tanggal2'")->result();
					$pdf->SetFillColor(0, 0, 139);
					$pdf->settextcolor(0);
					$pdf->setfont('Arial', 'B', 8);
					$pdf->Cell(30, 6, 'Nama Vendor', 0, 0, 'L');
					$pdf->Cell(50, 6, ' : ' . $v->vendor_name, 0, 0, 'L');
					$pdf->ln();
					$pdf->Cell(30, 6, 'Dari Tanggal', 0, 0, 'L');
					$pdf->Cell(50, 6, ' : ' . date('d-m-Y', strtotime($tanggal1)), 0, 0, 'L');
					$pdf->ln();
					$pdf->Cell(30, 6, 'Sampai Tanggal', 0, 0, 'L');
					$pdf->Cell(50, 6, ' : ' . date('d-m-Y', strtotime($tanggal2)), 0, 0, 'L');
					$pdf->ln();
					$pdf->SetFillColor(0, 0, 139);
					$pdf->settextcolor(0);
					$pdf->setfont('Arial', 'B', 6);
					$pdf->Cell(29, 6, 'PO No', 1, 0, 'C');
					$pdf->Cell(23, 6, 'Tanggal', 1, 0, 'C');
					$pdf->Cell(29, 6, 'Kode Barang', 1, 0, 'C');
					$pdf->Cell(37, 6, 'Nama Barang', 1, 0, 'C');
					$pdf->Cell(15, 6, 'Qty', 1, 0, 'C');
					$pdf->Cell(19, 6, 'Satuan', 1, 0, 'C');
					$pdf->Cell(19, 6, 'Harga Set', 1, 0, 'C');
					$pdf->Cell(19, 6, 'Total', 1, 0, 'C');
					$pdf->ln();
					$pdf->setfont('Arial', '', 6);
					$total_akhir = 0;
					foreach ($query as $q) {
						$pdf->Cell(29, 6, $q->po_no, 1, 0, 'C');
						$pdf->Cell(23, 6, $q->terima_date, 1, 0, 'C');
						$pdf->Cell(29, 6, $q->kodebarang, 1, 0, 'C');
						$pdf->Cell(37, 6, $q->namabarang, 1, 0, 'L');
						$pdf->Cell(15, 6, number_format($q->qty_terima, 2), 1, 0, 'R');
						$pdf->Cell(19, 6, $q->satuan, 1, 0, 'C');
						$pdf->Cell(19, 6, number_format($q->price, 2), 1, 0, 'R');
						$total = $q->price * $q->qty_terima;
						$pdf->Cell(19, 6, number_format($total, 2), 1, 0, 'R');
						$pdf->ln();
						$total_akhir += $total;
					}
					$pdf->Cell(171, 6, 'Total', 1, 0, 'C');
					$pdf->Cell(19, 6, number_format($total_akhir, 2), 1, 0, 'R');
					$pdf->ln();
					$pdf->ln();
				}
			} else if ($idlap == '107') {
				// paper type and orientattion
				$pdf->addpage("L", "A4");
				$pdf->setsize("L", "A4");
				// set font title and size
				$pdf->SetFont('Arial', 'B', 16);
				$pdf->ln(2);
				foreach ($vdr as $v) {
					$query = $this->db->query("select a.retur_no, a.retur_date, a.terima_no, b.kodebarang, (select namabarang from tbl_logbarang where kodebarang = b.kodebarang) as namabarang, b.qty_retur, b.satuan, b.price, b.totalrp, c.po_no from tbl_apohreturbelilog a join tbl_apodreturbelilog b on a.retur_no=b.retur_no JOIN tbl_apodterimalog c ON c.terima_no = a.terima_no join tbl_apohterimalog d on c.terima_no = d.terima_no where a.koders = '$unit' and a.vendor_id = '$v->vendor_id' and d.terima_date between '$tanggal1' and '$tanggal2' group by b.kodebarang")->result();
					$pdf->SetFillColor(0, 0, 139);
					$pdf->settextcolor(0);
					$pdf->setfont('Arial', 'B', 8);
					$pdf->Cell(30, 6, 'Nama Vendor', 0, 0, 'L');
					$pdf->Cell(50, 6, ' : ' . $v->vendor_name, 0, 0, 'L');
					$pdf->ln();
					$pdf->Cell(30, 6, 'Dari Tanggal', 0, 0, 'L');
					$pdf->Cell(50, 6, ' : ' . date('d-m-Y', strtotime($tanggal1)), 0, 0, 'L');
					$pdf->ln();
					$pdf->Cell(30, 6, 'Sampai Tanggal', 0, 0, 'L');
					$pdf->Cell(50, 6, ' : ' . date('d-m-Y', strtotime($tanggal2)), 0, 0, 'L');
					$pdf->ln();
					$pdf->SetFillColor(0, 0, 139);
					$pdf->settextcolor(0);
					$pdf->setfont('Arial', 'B', 6);
					$pdf->Cell(40, 6, 'Retur No', 1, 0, 'C');
					$pdf->Cell(20, 6, 'Tanggal', 1, 0, 'C');
					$pdf->Cell(40, 6, 'No BAPB', 1, 0, 'C');
					$pdf->Cell(20, 6, 'Kode Barang', 1, 0, 'C');
					$pdf->Cell(40, 6, 'Nama Barang', 1, 0, 'C');
					$pdf->Cell(20, 6, 'Qty', 1, 0, 'C');
					$pdf->Cell(20, 6, 'Satuan', 1, 0, 'C');
					$pdf->Cell(20, 6, 'Harga Set', 1, 0, 'C');
					$pdf->Cell(20, 6, 'Total', 1, 0, 'C');
					$pdf->Cell(40, 6, 'PO No', 1, 0, 'C');
					$pdf->ln();
					$pdf->setfont('Arial', '', 6);
					$no = 1;
					$total_akhir = 0;
					foreach ($query as $q) {
						$pdf->Cell(40, 6, $q->retur_no, 1, 0, 'L');
						$pdf->Cell(20, 6, date('d-m-Y', strtotime($q->retur_date)), 1, 0, 'C');
						$pdf->Cell(40, 6, $q->terima_no, 1, 0, 'L');
						$pdf->Cell(20, 6, $q->kodebarang, 1, 0, 'L');
						$pdf->Cell(40, 6, $q->namabarang, 1, 0, 'L');
						$pdf->Cell(20, 6, number_format($q->qty_retur, 2), 1, 0, 'R');
						$pdf->Cell(20, 6, $q->satuan, 1, 0, 'L');
						$pdf->Cell(20, 6, number_format($q->price, 2), 1, 0, 'R');
						// $total = $q->qty_terima*$q->price;
						$pdf->Cell(20, 6, number_format($q->totalrp, 2), 1, 0, 'R');
						$pdf->Cell(40, 6, $q->po_no, 1, 0, 'L');
						$pdf->ln();
						$total_akhir += $q->totalrp;
					}
					$pdf->Cell(220, 6, 'Total', 1, 0, 'C');
					$pdf->Cell(20, 6, number_format($total_akhir, 2), 1, 0, 'R');
					$pdf->Cell(40, 6, '', 1, 0, 'C');
					$pdf->ln();
					$pdf->ln();
				}
			} else if ($idlap == '108') {
				// paper type and orientattion
				$pdf->addpage("L", "A4");
				$pdf->setsize("L", "A4");
				// set font title and size
				$pdf->SetFont('Arial', 'B', 16);
				$pdf->ln(2);
				foreach ($vdr as $v) {
					$query = $this->db->query("
					SELECT 
						(SELECT vendor_name FROM tbl_vendor WHERE vendor_id = tbl_apohpolog.vendor_id) AS rekanan,
						tbl_apohpolog.ref_no AS no_faktur,
						0 AS obat,
						0 AS alkes_rutin,
						0 AS alk_investasi,
						0 AS bahan_kimia,
						0 AS gas_medik,
						0 AS pemeliharaan,
						0 AS sewa,
						0 AS pelengkapan,
						0 AS materai,
						tbl_apodpolog.vatrp AS lain2,
						tbl_apodpolog.total AS jumlah
					FROM tbl_apohpolog
					JOIN tbl_apodpolog ON tbl_apohpolog.po_no=tbl_apodpolog.po_no
					WHERE tbl_apohpolog.vendor_id = '$v->vendor_id' and po_date BETWEEN '$tanggal1' AND '$tanggal2'

					UNION ALL

					SELECT
						(SELECT vendor_name FROM tbl_vendor WHERE vendor_id = tbl_apohterimalog.vendor_id) AS rekanan,
						invoice_no AS no_faktur,
						0 AS obat,
						0 AS alkes_rutin,
						0 AS alk_investasi,
						0 AS bahan_kimia,
						0 AS gas_medik,
						0 AS pemeliharaan,
						0 AS sewa,
						0 AS pelengkapan,
						materai,
						tbl_apodterimalog.vatrp AS lain2,
						tbl_apodterimalog.totalrp AS jumlah
					FROM tbl_apohterimalog
					JOIN tbl_apodterimalog ON tbl_apohterimalog.terima_no=tbl_apodterimalog.terima_no
					WHERE tbl_apohterimalog.vendor_id = '$v->vendor_id' and terima_date BETWEEN '$tanggal1' AND '$tanggal2'

					UNION ALL

					SELECT
						(SELECT vendor_name FROM tbl_vendor WHERE vendor_id = tbl_apohreturbelilog.vendor_id) AS rekanan,
						invoice_no AS no_faktur,
						0 AS obat,
						0 AS alkes_rutin,
						0 AS alk_investasi,
						0 AS bahan_kimia,
						0 AS gas_medik,
						0 AS pemeliharaan,
						0 AS sewa,
						0 AS pelengkapan,
						0 AS materai,
						tbl_apodreturbelilog.taxrp AS lain2,
						tbl_apodreturbelilog.totalrp AS jumlah
					FROM tbl_apohreturbelilog
					JOIN tbl_apodreturbelilog ON tbl_apohreturbelilog.retur_no=tbl_apodreturbelilog.retur_no
					WHERE tbl_apohreturbelilog.vendor_id = '$v->vendor_id' and retur_date BETWEEN '$tanggal1' AND '$tanggal2'
					")->result();
					$pdf->SetFillColor(0, 0, 139);
					$pdf->settextcolor(0);
					$pdf->setfont('Arial', 'B', 8);
					$pdf->Cell(30, 6, 'Nama Vendor', 0, 0, 'L');
					$pdf->Cell(50, 6, ' : ' . $v->vendor_name, 0, 0, 'L');
					$pdf->ln();
					$pdf->Cell(30, 6, 'Dari Tanggal', 0, 0, 'L');
					$pdf->Cell(50, 6, ' : ' . date('d-m-Y', strtotime($tanggal1)), 0, 0, 'L');
					$pdf->ln();
					$pdf->Cell(30, 6, 'Sampai Tanggal', 0, 0, 'L');
					$pdf->Cell(50, 6, ' : ' . date('d-m-Y', strtotime($tanggal2)), 0, 0, 'L');
					$pdf->ln();
					$pdf->SetFillColor(0, 0, 139);
					$pdf->settextcolor(0);
					$pdf->setfont('Arial', 'B', 6);
					$pdf->Cell(50, 6, 'Rekanan', 1, 0, 'C');
					$pdf->Cell(40, 6, 'No Faktur', 1, 0, 'C');
					$pdf->Cell(17, 6, 'Obat', 1, 0, 'C');
					$pdf->Cell(17, 6, 'Alkes Rutin', 1, 0, 'C');
					$pdf->Cell(17, 6, 'Alkes Inves', 1, 0, 'C');
					$pdf->Cell(17, 6, 'Bahan Kimia', 1, 0, 'C');
					$pdf->Cell(17, 6, 'Gas Medik', 1, 0, 'C');
					$pdf->Cell(17, 6, 'Pemeliharaan', 1, 0, 'C');
					$pdf->Cell(17, 6, 'Sewa', 1, 0, 'C');
					$pdf->Cell(17, 6, 'Pelengkap', 1, 0, 'C');
					$pdf->Cell(17, 6, 'Lain-lain', 1, 0, 'C');
					$pdf->Cell(17, 6, 'Materai', 1, 0, 'C');
					$pdf->Cell(20, 6, 'Jumlah', 1, 0, 'C');
					$pdf->ln();
					$pdf->setfont('Arial', '', 6);
					$no = 1;
					$obat = 0;
					$alkes_rutin = 0;
					$alk_investasi = 0;
					$bahan_kimia = 0;
					$gas_medik = 0;
					$pemeliharaan = 0;
					$sewa = 0;
					$pelengkapan = 0;
					$lain2 = 0;
					$materai = 0;
					$jumlah = 0;
					foreach ($query as $q) {
						$pdf->Cell(50, 6, $q->rekanan, 1, 0, 'L');
						$pdf->Cell(40, 6, $q->no_faktur, 1, 0, 'L');
						$pdf->Cell(17, 6, number_format($q->obat, 2), 1, 0, 'R');
						$pdf->Cell(17, 6, number_format($q->alkes_rutin, 2), 1, 0, 'R');
						$pdf->Cell(17, 6, number_format($q->alk_investasi, 2), 1, 0, 'R');
						$pdf->Cell(17, 6, number_format($q->bahan_kimia, 2), 1, 0, 'R');
						$pdf->Cell(17, 6, number_format($q->gas_medik, 2), 1, 0, 'R');
						$pdf->Cell(17, 6, number_format($q->pemeliharaan, 2), 1, 0, 'R');
						$pdf->Cell(17, 6, number_format($q->sewa, 2), 1, 0, 'R');
						$pdf->Cell(17, 6, number_format($q->pelengkapan, 2), 1, 0, 'R');
						$pdf->Cell(17, 6, number_format($q->lain2, 2), 1, 0, 'R');
						$pdf->Cell(17, 6, number_format($q->materai, 2), 1, 0, 'R');
						$pdf->Cell(20, 6, number_format($q->jumlah, 2), 1, 0, 'R');
						$pdf->ln();
						$obat += $q->obat;
						$alkes_rutin += $q->alkes_rutin;
						$alk_investasi += $q->alk_investasi;
						$bahan_kimia += $q->bahan_kimia;
						$gas_medik += $q->gas_medik;
						$pemeliharaan += $q->pemeliharaan;
						$sewa += $q->sewa;
						$pelengkapan += $q->pelengkapan;
						$lain2 += $q->lain2;
						$materai += $q->materai;
						$jumlah += $q->jumlah;
					}
					$pdf->Cell(90, 6, 'Total', 1, 0, 'C');
					$pdf->Cell(17, 6, number_format($obat, 2), 1, 0, 'R');
					$pdf->Cell(17, 6, number_format($alkes_rutin, 2), 1, 0, 'R');
					$pdf->Cell(17, 6, number_format($alk_investasi, 2), 1, 0, 'R');
					$pdf->Cell(17, 6, number_format($bahan_kimia, 2), 1, 0, 'R');
					$pdf->Cell(17, 6, number_format($gas_medik, 2), 1, 0, 'R');
					$pdf->Cell(17, 6, number_format($pemeliharaan, 2), 1, 0, 'R');
					$pdf->Cell(17, 6, number_format($sewa, 2), 1, 0, 'R');
					$pdf->Cell(17, 6, number_format($pelengkapan, 2), 1, 0, 'R');
					$pdf->Cell(17, 6, number_format($lain2, 2), 1, 0, 'R');
					$pdf->Cell(17, 6, number_format($materai, 2), 1, 0, 'R');
					$pdf->Cell(20, 6, number_format($jumlah, 2), 1, 0, 'R');
					$pdf->ln();
					$pdf->ln();
				}
			}
			$pdf->Output();
		}
	}

	public function excel()
	{
		$cek = $this->session->userdata('level');
		$idlap = $this->input->get('idlap');
		$unit = $this->session->userdata('unit');
		$tanggal1 = $this->input->get('tgl1');
		$tanggal2 = $this->input->get('tgl2');
		$vendor = $this->input->get('vendor');
		if (!empty($cek)) {
			if ($idlap == 101) {
				$judul = '01 LAPORAN PEMBELIAN BARANG (DETAIL INVOICE)';
				if ($vendor != '') {
					$vendorx = "a.vendor_id='$vendor' and ";
				} else {
					$vendorx = "";
				}
				$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE $vendorx a.koders='$unit' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->result();
				$data = [
					'judul' => $judul,
					'vdr' => $vdr,
					'unit' => $unit,
					'tanggal1' => $tanggal1,
					'tanggal2' => $tanggal2,
				];
				$this->load->view('logistik/Pembelian/v_excel_01.php', $data);
			} else if ($idlap == 102) {
				$judul = '02 LAPORAN PEMBELIAN BARANG (REKAP INVOICE)';
				if ($vendor != '') {
					$vendorx = "a.vendor_id ='$vendor' AND ";
				} else {
					$vendorx = "";
				}
				$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE $vendorx a.koders='$unit' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->result();
				$data = [
					'judul' => $judul,
					'vdr' => $vdr,
					'unit' => $unit,
					'tanggal1' => $tanggal1,
					'tanggal2' => $tanggal2,
				];
				$this->load->view('logistik/Pembelian/v_excel_02.php', $data);
			} else if ($idlap == 103) {
				$judul = '03 REKAP PEMBELIAN BARANG PERSUPPLIER DAN ITEM';
				if ($vendor != '') {
					$vendorz = "a.vendor_id='$vendor' and ";
				} else {
					$vendorz = "";
				}
				$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE $vendorz a.koders='$unit' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->result();
				$data = [
					'judul' => $judul,
					'vdr' => $vdr,
					'unit' => $unit,
					'tanggal1' => $tanggal1,
					'tanggal2' => $tanggal2,
				];
				$this->load->view('logistik/Pembelian/v_excel_03.php', $data);
			} else if ($idlap == 104) {
				$judul = '04 REKAP PEMBELIAN BARANG PER SUPPLIER TOTAL';
				if ($vendor != '') {
					$vendorx = "a.vendor_id ='$vendor' AND";
				} else {
					$vendorx = "";
				}
				$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE $vendorx a.koders='$unit' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->result();
				$data = [
					'judul' => $judul,
					'vdr' => $vdr,
					'unit' => $unit,
					'tanggal1' => $tanggal1,
					'tanggal2' => $tanggal2,
				];
				$this->load->view('logistik/Pembelian/v_excel_04.php', $data);
			} else if ($idlap == 105) {
				$judul = '05 LAPORAN PEMBELIAN BARANG PER ITEM (TOTAL)';
				if ($vendor != '') {
					$vendorx = "a.vendor_id = '$vendor' AND ";
				} else {
					$vendorx = "";
				}
				$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE $vendorx a.koders='$unit' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->result();
				$data = [
					'judul' => $judul,
					'vdr' => $vdr,
					'unit' => $unit,
					'tanggal1' => $tanggal1,
					'tanggal2' => $tanggal2,
				];
				$this->load->view('logistik/Pembelian/v_excel_05.php', $data);
			} else if ($idlap == 106) {
				$judul = '06 LAPORAN STATUS ORDER PEMBELIAN';
				if ($vendor != '') {
					$vendorx = "a.vendor_id = '$vendor' AND ";
					$vendorz = "WHERE vendor_id = '$vendor' ";
				} else {
					$vendorx = "";
					$vendorz = "";
				}
				$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE $vendorx a.koders='$unit' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->result();
				$data = [
					'judul' => $judul,
					'vdr' => $vdr,
					'unit' => $unit,
					'tanggal1' => $tanggal1,
					'tanggal2' => $tanggal2,
				];
				$this->load->view('logistik/Pembelian/v_excel_06.php', $data);
			} else if ($idlap == 107) {
				$judul = '07 LAPORAN RETURN PEMBELIAN';
				if ($vendor != '') {
					$vendorx = "a.vendor_id = '$vendor' and ";
				} else {
					$vendorx = "";
				}
				$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE $vendorx a.koders='$unit' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->result();
				$data = [
					'judul' => $judul,
					'vdr' => $vdr,
					'unit' => $unit,
					'tanggal1' => $tanggal1,
					'tanggal2' => $tanggal2,
				];
				$this->load->view('logistik/Pembelian/v_excel_07.php', $data);
			} else if ($idlap == 108) {
				$judul = '08 LAPORAN HUTANG GUDANG LOGISTIK';
				if ($vendor != '') {
					$vendorx = "a.vendor_id = '$vendor' AND";
				} else {
					$vendorx = "";
				}
				$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE $vendorx a.koders='$unit' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->result();
				$data = [
					'judul' => $judul,
					'vdr' => $vdr,
					'unit' => $unit,
					'tanggal1' => $tanggal1,
					'tanggal2' => $tanggal2,
				];
				$this->load->view('logistik/Pembelian/v_excel_08.php', $data);
			}
		}
	}
}
