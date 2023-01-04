<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_pembelian_log extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_global', 'M_global');
		$this->load->model('M_cetak');
		$this->load->model('M_template_cetak');
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
					$query = $this->db->query(
						"SELECT 
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

	public function cetak2()
	{
		$cek = $this->session->userdata('level');
		$idlap = $this->input->get('idlap');
		$cabang = $this->input->get('cabang');
		$tanggal1 = $this->input->get('tgl1');
		$tanggal2 = $this->input->get('tgl2');
		$vendor = $this->input->get('vendor');
		$cekpdf = $this->input->get('pdf');
		$body = '';
		$date = "Dari Tgl : " . date("d-m-Y", strtotime($tanggal1)) . " S/D " . date("d-m-Y", strtotime($tanggal2));
		$profile  = data_master('tbl_namers', array('koders' => $cabang));
		$kota = $profile->kota;
		if (!empty($cek)) {
			if ($idlap == 101) {
				$position = 'L';
				$judul = '01 LAPORAN PEMBELIAN BARANG (DETAIL INVOICE)';
				if ($vendor != '') {
					$vendorx = "a.vendor_id='$vendor' and ";
				} else {
					$vendorx = "";
				}
				$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE $vendorx a.koders='$cabang' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->result();
				$no = 1;
				$tqty2 = 0;
				$thargaset2 = 0;
				$ttotal2 = 0;
				$tdiskon2 = 0;
				$tvatrp2 = 0;
				$ttotalnet2 = 0;
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"10\" cellpadding=\"10\">";
				$body .= "<tr>
										<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\">No</td>
										<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"7%\">No. BAPB</td>
										<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"7%\">PO No</td>
										<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"7%\">Tanggal</td>
										<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"7%\">No. Invoice / SJ</td>
										<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"8%\">Kode Barang</td>
										<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Nama Barang</td>
										<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"7%\">Satuan</td>
										<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"7%\">Qty</td>
										<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"7%\">Harga set</td>
										<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"7%\">Total</td>
										<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"7%\">Diskon</td>
										<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"7%\">Vat Rp</td>
										<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"7%\">Total Net</td>
									</tr>";
				foreach($vdr as $v){
					$query = $this->db->query("SELECT a.terima_no,a.terima_date,a.invoice_no,a.sj_no,b.kodebarang, a.materai, b.discountrp, c.namabarang, b.qty_terima, b.satuan, b.price, (b.totalrp + b.vatrp + a.materai) as totalnet, (b.qty_terima * b.price) as totalrp, b.discount, b.vat, b.vatrp As vatrp1, b.po_no FROM tbl_apohterimalog a JOIN tbl_apodterimalog b ON b.terima_no = a.terima_no JOIN tbl_logbarang c ON c.kodebarang = b.kodebarang JOIN tbl_vendor d ON d.vendor_id = a.vendor_id WHERE a.vendor_id = '$v->vendor_id' and a.koders='$cabang' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY a.terima_date, a.terima_no")->result();
					$tqty = 0;
					$thargaset = 0;
					$ttotal = 0;
					$tdiskon = 0;
					$tvatrp = 0;
					$ttotalnet = 0;
					foreach ($query as $q) {
						if ($q->po_no == '') {
							$po_no = '-';
						} else {
							$po_no = $q->po_no;
						}
						if($cekpdf == 1){
							$qty_terima = number_format($q->qty_terima);
							$price = number_format($q->price);
							$totalrp = number_format($q->totalrp);
							$discountrp = number_format($q->discountrp);
							$vatrp1 = number_format($q->vatrp1);
							$totalnet = number_format($q->totalnet);
						} else {
							$qty_terima = round($q->qty_terima);
							$price = round($q->price);
							$totalrp = round($q->totalrp);
							$discountrp = round($q->discountrp);
							$vatrp1 = round($q->vatrp1);
							$totalnet = round($q->totalnet);
						}
						$body .= "<tr>
												<td width=\"5%\" style=\"text-align: right;\">" . $no++ . "</td>
												<td width=\"7%\">$q->terima_no</td>
												<td width=\"7%\">$po_no</td>
												<td width=\"7%\" style=\"text-align: center;\">" . date("d-m-Y", strtotime($q->terima_date)) . "</td>
												<td width=\"7%\">$q->sj_no</td>
												<td width=\"8%\">$q->kodebarang</td>
												<td width=\"10%\">$q->namabarang</td>
												<td width=\"7%\">$q->satuan</td>
												<td width=\"7%\" style=\"text-align: right;\">" . $qty_terima . "</td>
												<td width=\"7%\" style=\"text-align: right;\">" . $price . "</td>
												<td width=\"7%\" style=\"text-align: right;\">" . $totalrp . "</td>
												<td width=\"7%\" style=\"text-align: right;\">" . $discountrp . "</td>
												<td width=\"7%\" style=\"text-align: right;\">" . $vatrp1 . "</td>
												<td width=\"7%\" style=\"text-align: right;\">" . $totalnet . "</td>
											</tr>";
						$tqty += $q->qty_terima;
						$thargaset += $q->price;
						$ttotal += $q->totalrp;
						$tdiskon += $q->discountrp;
						$tvatrp += $q->vatrp1;
						$ttotalnet += $q->totalnet;
					};
					$tqty2 += $tqty;
					$thargaset2 += $thargaset;
					$ttotal2 += $ttotal;
					$tdiskon2 += $tdiskon;
					$tvatrp2 += $tvatrp;
					$ttotalnet2 += $ttotalnet;
				}
				if($cekpdf == 1){
					$tqtyx = number_format($tqty2);
					$thargasetx = number_format($thargaset2);
					$ttotalx = number_format($ttotal2);
					$tdiskonx = number_format($tdiskon2);
					$tvatrpx = number_format($tvatrp2);
					$ttotalnetx = number_format($ttotalnet2);
				} else {
					$tqtyx = round($tqty);
					$thargasetx = round($thargaset);
					$ttotalx = round($ttotal);
					$tdiskonx = round($tdiskon);
					$tvatrpx = round($tvatrp);
					$ttotalnetx = round($ttotalnet);
				}
				$body .= 	"<tr>
										<td style=\"text-align: center;\" colspan=\"8\">TOTAL</td>
										<td style=\"text-align: right;\">" . $tqtyx . "</td>
										<td style=\"text-align: right;\">" . $thargasetx . "</td>
										<td style=\"text-align: right;\">" . $ttotalx . "</td>
										<td style=\"text-align: right;\">" . $tdiskonx . "</td>
										<td style=\"text-align: right;\">" . $tvatrpx . "</td>
										<td style=\"text-align: right;\">" . $ttotalnetx . "</td>
									</tr>";
				$body .=	"</table>";
			} else if($idlap == 102){
				$position = "L";
				$judul = '02 LAPORAN PEMBELIAN BARANG (REKAP INVOICE)';
				if ($vendor != '' || $vendor != null) {
					$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE a.vendor_id ='$vendor' AND  a.koders='$cabang' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->row();
					$query = $this->db->query("SELECT a.terima_date , a.sj_no, a.vatrp , a.materai , a.koders, a.gudang, a.terima_no, (sum(c.discountrp)) as diskontotal, b.vendor_name, b.vendor_id, (sum(c.qty_terima * c.price)) as totalrp, c.vat, a.vatrp, (sum(c.totalrp) + a.vatrp + a.materai) as totalnet FROM tbl_apohterimalog AS a JOIN tbl_vendor AS b ON a.vendor_id = b.vendor_id JOIN tbl_apodterimalog AS c ON a.terima_no = c.terima_no WHERE a.vendor_id = '$vdr->vendor_id' and a.koders = '$cabang' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2' group by a.terima_no")->result();
					$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"5\" cellpadding=\"5\">";
					foreach($query as $q) {
						$gudangx = $this->db->get_where("tbl_depo", ["depocode" => $q->gudang])->row();
						$body .= "<tr>
													<td width=\"40%\" style=\"text-align: center; border-top: none; border-left: none; border-right: none;\" colspan=\"10\">GUDANG : <b style=\"color: red;\">$gudangx->keterangan</b></td>
												</tr>";
					}
					$body .= "<tr>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\">No</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Supplier</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">No. BAPB</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Tanggal</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">No. Invoice / SJ</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Total</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Diskon</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Vat Rp</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Materai</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Total Net</td>
											</tr>";
					$no = 1;
					$ttotalrp = 0;
					$tdiskontotal = 0;
					$tvatrp = 0;
					$tmaterai = 0;
					$ttotalnet = 0;
					foreach ($query as $q) {
						if($cekpdf == 1){
							$totalrp = number_format($q->totalrp);
							$diskontotal = number_format($q->diskontotal);
							$vatrp = number_format($q->vatrp);
							$materai = number_format($q->materai);
							$totalnet = number_format($q->totalnet);
						} else {
							$totalrp = round($q->totalrp);
							$diskontotal = round($q->diskontotal);
							$vatrp = round($q->vatrp);
							$materai = round($q->materai);
							$totalnet = round($q->totalnet);
						}
						$body .= 	"<tr>
													<td width=\"5%\" style=\"text-align: right;\">" . $no++ . "</td>
													<td width=\"10%\">$vdr->vendor_name</td>
													<td width=\"10%\">$q->terima_no</td>
													<td width=\"10%\" style=\"text-align: center;\">" . date("d-m-Y", strtotime($q->terima_date)) . "</td>
													<td width=\"10%\">$q->sj_no</td>
													<td width=\"10%\" style=\"text-align: right;\">" . $totalrp . "</td>
													<td width=\"10%\" style=\"text-align: right;\">" . $diskontotal . "</td>
													<td width=\"10%\" style=\"text-align: right;\">" . $vatrp . "</td>
													<td width=\"10%\" style=\"text-align: right;\">" . $materai . "</td>
													<td width=\"10%\" style=\"text-align: right;\">" . $totalnet . "</td>
												</tr>";
						$ttotalrp += $q->totalrp;
						$tdiskontotal += $q->diskontotal;
						$tvatrp += $q->vatrp;
						$tmaterai += $q->materai;
						$ttotalnet += $q->totalnet;
					};
					if ($cekpdf == 1) {
						$xtotalrp = number_format($ttotalrp);
						$xdiskontotal = number_format($tdiskontotal);
						$xvatrp = number_format($tvatrp);
						$xmaterai = number_format($tmaterai);
						$xtotalnet = number_format($ttotalnet);
					} else {
						$xtotalrp = round($ttotalrp);
						$xdiskontotal = round($tdiskontotal);
						$xvatrp = round($tvatrp);
						$xmaterai = round($tmaterai);
						$xtotalnet = round($ttotalnet);
					}
					$body .= 	"<tr>
											<td width=\"40%\" style=\"text-align: center;\" colspan=\"5\">TOTAL</td>
											<td width=\"10%\" style=\"text-align: right;\">" . $xtotalrp . "</td>
											<td width=\"10%\" style=\"text-align: right;\">" . $xdiskontotal . "</td>
											<td width=\"10%\" style=\"text-align: right;\">" . $xvatrp . "</td>
											<td width=\"10%\" style=\"text-align: right;\">" . $xmaterai . "</td>
											<td width=\"10%\" style=\"text-align: right;\">" . $xtotalnet . "</td>
										</tr>";
					$body .=	"</table>";
				} else {
					$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE a.koders='$cabang' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->result();
					$getgud = $this->db->query("SELECT a.gudang, d.keterangan FROM tbl_apohterimalog AS a JOIN tbl_depo d ON d.depocode=a.gudang WHERE a.koders = '$cabang' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2' GROUP BY a.gudang")->result();
					foreach($getgud as $gg){
						$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"5\" cellpadding=\"5\">";
						$body .= "<tr>
												<td style=\"text-align: center; border-top: none; border-bottom: none; border-left: none; border-right: none;\" colspan=\"10\">&nbsp;</td>
											</tr>
											<tr>
												<td style=\"text-align: center; border-top: none; border-left: none; border-bottom: none; border-right: none;\" colspan=\"10\">GUDANG : <b style=\"color: red;\">$gg->keterangan</b></td>
											</tr>";
						$body .= "<tr>
													<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\">No</td>
													<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Supplier</td>
													<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">No. BAPB</td>
													<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\">Tanggal</td>
													<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">No. Invoice / SJ</td>
													<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Total</td>
													<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Diskon</td>
													<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Vat Rp</td>
													<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Materai</td>
													<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Total Net</td>
												</tr>";
						$ttotalrp1 = 0;
						$tdiskontotal1 = 0;
						$tvatrp1 = 0;
						$tmaterai1 = 0;
						$ttotalnet1 = 0;
						$no = 1;
						foreach($vdr as $v){
							$query = $this->db->query("SELECT a.terima_date, d.keterangan, a.sj_no, a.vatrp , a.materai, a.gudang, a.koders, a.terima_no, (sum(c.discountrp)) as diskontotal, b.vendor_name, b.vendor_id, (sum(c.qty_terima * c.price)) as totalrp, c.vat, a.vatrp, (sum(c.totalrp) + a.vatrp + a.materai) as totalnet FROM tbl_apohterimalog AS a JOIN tbl_vendor AS b ON a.vendor_id = b.vendor_id JOIN tbl_depo d ON d.depocode=a.gudang JOIN tbl_apodterimalog AS c ON a.terima_no = c.terima_no WHERE a.vendor_id = '$v->vendor_id' and a.koders = '$cabang' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2' group by a.terima_no")->result();
							$ttotalrp = 0;
							$tdiskontotal = 0;
							$tvatrp = 0;
							$tmaterai = 0;
							$ttotalnet = 0;
							foreach ($query as $q){
								if($cekpdf == 1){
									$totalrp = number_format($q->totalrp);
									$diskontotal = number_format($q->diskontotal);
									$vatrp = number_format($q->vatrp);
									$materai = number_format($q->materai);
									$totalnet = number_format($q->totalnet);
								} else {
									$totalrp = round($q->totalrp);
									$diskontotal = round($q->diskontotal);
									$vatrp = round($q->vatrp);
									$materai = round($q->materai);
									$totalnet = round($q->totalnet);
								}
								$body .= "<tr>
														<td width=\"5%\" style=\"text-align: center;\">" . $no++ . "</td>
														<td width=\"10%\">$v->vendor_name</td>
														<td width=\"10%\">$q->terima_no</td>
														<td width=\"5%\" style=\"text-align: center;\">" . date("d-m-Y", strtotime($q->terima_date)) . "</td>
														<td width=\"10%\">$q->sj_no</td>
														<td width=\"10%\" style=\"text-align: right;\">" . $totalrp . "</td>
														<td width=\"10%\" style=\"text-align: right;\">" . $diskontotal . "</td>
														<td width=\"10%\" style=\"text-align: right;\">" . $vatrp . "</td>
														<td width=\"10%\" style=\"text-align: right;\">" . $materai . "</td>
														<td width=\"10%\" style=\"text-align: right;\">" . $totalnet . "</td>
													</tr>";
								$ttotalrp += $q->totalrp;
								$tdiskontotal += $q->diskontotal;
								$tvatrp += $q->vatrp;
								$tmaterai += $q->materai;
								$ttotalnet += $q->totalnet;
							}
							$ttotalrp1 += $ttotalrp;
							$tdiskontotal1 += $tdiskontotal;
							$tvatrp1 += $tvatrp;
							$tmaterai1 += $tmaterai;
							$ttotalnet1 += $ttotalnet;
						}
						if($cekpdf == 1){
							$xttotalrp1 = number_format($ttotalrp1);
							$xtdiskontotal1 = number_format($tdiskontotal1);
							$xtvatrp1 = number_format($tvatrp1);
							$xtmaterai1 = number_format($tmaterai1);
							$xttotalnet1 = number_format($ttotalnet1);
						} else {
							$xttotalrp1 = round($ttotalrp1);
							$xtdiskontotal1 = round($tdiskontotal1);
							$xtvatrp1 = round($tvatrp1);
							$xtmaterai1 = round($tmaterai1);
							$xttotalnet1 = round($ttotalnet1);
						}
						$body .= 	"<tr>
												<td width=\"40%\" style=\"text-align: center; font-weight: bold;\" colspan=\"5\">TOTAL</td>
												<td width=\"10%\" style=\"text-align: right;\">" . $xttotalrp1 . "</td>
												<td width=\"10%\" style=\"text-align: right;\">" . $xtdiskontotal1 . "</td>
												<td width=\"10%\" style=\"text-align: right;\">" . $xtvatrp1 . "</td>
												<td width=\"10%\" style=\"text-align: right;\">" . $xtmaterai1 . "</td>
												<td width=\"10%\" style=\"text-align: right;\">" . $xttotalnet1 . "</td>
											</tr>";
						$body .=	"</table>";
					}
				}
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                    <tr>
											<td> &nbsp; </td>
										</tr> 
                  </table>";
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
										<tr>
											<td> &nbsp; </td>
										</tr> 
									</table>";
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"20%\" align=\"center\" border=\"0\">
										<tr>
											<td style=\"text-align:center;\"><i>HOSPITAL MANAGEMENT SYSTEM</i></td>
										</tr> 
										<tr>
											<td style=\"text-align:center;\">$kota ," . date("d-m-Y") . "</td>
										</tr> 
									</table>";
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
										<tr>
											<td> &nbsp; </td>
										</tr> 
									</table>";
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"70%\" align=\"center\" border=\"1\">
										<tr>
											<td style=\"text-align:center;\" width=\"33%\">Diketahui oleh,</td>
											<td style=\"text-align:center;\" width=\"33%\">Diserahkan oleh,</td>
											<td style=\"text-align:center;\" width=\"33%\">Dibuat oleh,</td>
										</tr> 
										<tr>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
										</tr> 
										<tr>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
										</tr> 
										<tr>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
										</tr> 
										<tr>
											<td width=\"33%\" style=\"text-align:center;\">2</td>
											<td width=\"33%\" style=\"text-align:center;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center;\">HARYANTO</td>
										</tr> 
										<tr>
											<td width=\"33%\" style=\"text-align:center;\">Kepala Apoteker</td>
											<td width=\"33%\" style=\"text-align:center;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center;\">PENANGGUNG JAWAB ADMINISTRASI</td>
										</tr> 
									</table>";
			} else if($idlap == 103){
				$judul = '03 REKAP PEMBELIAN BARANG PERSUPPLIER DAN ITEM';
				if ($vendor != '') {
					$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE a.vendor_id='$vendor' and  a.koders='$cabang' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->result();
				} else {
					$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE a.koders='$cabang' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->result();
				}
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"10\" cellpadding=\"10\">
                    <tr>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">No</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Kode Barang</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Nama Barang</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Satuan</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Qty</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Harga rata-rata</td>
											<td width=\"15%\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Total</td>
										</tr>";
				$totalsemua = 0;
				foreach($vdr as $v){
					$query = $this->db->query("SELECT b.kodebarang, (select namabarang from tbl_logbarang where kodebarang = b.`kodebarang`) as namabarang, b.satuan, b.qty_terima, b.totalrp, (b.totalrp / b.qty_terima ) AS ratarata, b.koders, c.vendor_name FROM tbl_apodterimalog AS b JOIN tbl_apohterimalog AS d ON b.`terima_no`= d.terima_no JOIN tbl_vendor AS c ON d.vendor_id = c.vendor_id WHERE d.vendor_id = '$v->vendor_id' and b.koders = '$cabang' and d.terima_date between '$tanggal1' and '$tanggal2'")->result();
					$body .= "<tr>
											<td colspan=\"7\">SUPPLIER : $v->vendor_name</td>
										</tr>";
					$no = 1;
					$tqty_terima = 0;
					$tratarata = 0;
					$ttotal = 0;
					foreach ($query as $q) {
						$total = $q->ratarata * $q->qty_terima;
						if($cekpdf == 1){
							$qty_terima = number_format($q->qty_terima);
							$ratarata = number_format($q->ratarata);
							$totalx = number_format($total);
						} else {
							$qty_terima = round($q->qty_terima);
							$ratarata = round($q->ratarata);
							$totalx = round($total);
						}
						$body .= 			"<tr>
														<td style=\"text-align: right;\">" . $no++ . "</td>
														<td>$q->kodebarang</td>
														<td>$q->namabarang</td>
														<td>$q->satuan</td>
														<td style=\"text-align: right;\">" . $qty_terima . "</td>
														<td style=\"text-align: right;\">" . $ratarata . "</td>
														<td width=\"15%\" style=\"text-align: right;\">" . $totalx . "</td>
													</tr>";
						$tqty_terima += $q->qty_terima;
						$tratarata += $q->ratarata;
						$ttotal += $total;
					}
					if($cekpdf == 1){
						$xtqty_terima = number_format($tqty_terima);
						$xtratarata = number_format($tratarata);
						$xttotal = number_format($ttotal);
					} else {
						$xtqty_terima = round($tqty_terima);
						$xtratarata = round($tratarata);
						$xttotal = round($ttotal);
					}
					$body .= "<tr>
											<td colspan=\"4\">SUBTOTAL PERSUPLIER</td>
											<td style=\"text-align: right; font-weight: bold; color:red;\">" . $xtqty_terima . "</td>
											<td style=\"text-align: right; font-weight: bold; color:red;\">" . $xtratarata . "</td>
											<td width=\"15%\" style=\"text-align: right; font-weight: bold; color:red;\">" . $xttotal . "</td>
										</tr>";
					$totalsemua += $ttotal;
				}
				if($cekpdf == 1){
					$xtotalsemua = number_format($totalsemua);
				} else {
					$xtotalsemua = round($totalsemua);
				}
				$body .= "<tr>
										<td colspan=\"7\" style=\"border-left: none; border-right: none;\">&nbsp;</td>
									</tr>
									<tr>
										<td colspan=\"6\" style=\"text-align: center; font-weight: bold; color:red;\">TOTAL KESELURUHAN</td>
										<td width=\"15%\" style=\"text-align: right; font-weight: bold; color:red;\">" . $xtotalsemua . "</td>
									</tr> ";
				$body .= "</table>";
			} else if($idlap == 104){
				$position = 'L';
				$judul = '04 REKAP PEMBELIAN BARANG PER SUPPLIER TOTAL';
				if ($vendor != '') {
					$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE a.vendor_id ='$vendor' AND a.koders='$cabang' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->row();
					$query = $this->db->query("SELECT a.terima_no, d.keterangan, a.terima_date, a.vatrp, a.materai, b.vat, b.discountrp, b.qty_terima , b.discountrp, b.totalrp, (b.totalrp / b.qty_terima ) AS ratarata, c.vendor_name , c.vendor_id FROM tbl_apohterimalog as a JOIN tbl_depo as d ON a.gudang=d.depocode JOIN tbl_apodterimalog AS b ON a.terima_no = b.terima_no JOIN tbl_vendor AS c ON a.vendor_id = c.vendor_id WHERE a.vendor_id = '$vdr->vendor_id' and a.koders = '$cabang' and a.terima_date between '$tanggal1' and '$tanggal2'")->result();
					$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"5\" cellpadding=\"5\">";
					foreach($query as $q) {
						$body .= "<tr>
												<td width=\"40%\" style=\"text-align: center; border-top: none; border-left: none; border-right: none;\" colspan=\"9\">GUDANG : <b style=\"color: red;\">$q->keterangan</b></td>
											</tr>";
					}
					$body .= "<tr>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\">No</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Supplier</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Tanggal</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Qty</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Total</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Diskon</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Vat Rp</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Materai</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Total Net</td>
										</tr>";
					$no = 1;
					$tqty_terima = 0;
					$ttotalrp = 0;
					$ttotalrp2 = 0;
					$tdiscountrp = 0;
					$tvatrp = 0;
					$tmaterai = 0;
					$ttotalrp = 0;
					$sql = $this->db->get_where("tbl_pajak", ["kodetax" => "PPN"])->row();
					$pajak = $sql->prosentase / 100;
					foreach($query as $q){
							if ($q->vat == 1) {
								$vatrp = ($q->totalrp * $pajak);
							} else {
								$vatrp = 0;
							}
							$totalrp = ($q->totalrp + $q->discountrp);
							$totall = $q->totalrp + $q->vatrp + $q->materai;
							if($cekpdf == 1){
								$qty_terima = number_format($q->qty_terima);
								$xtotalrp = number_format($totalrp);
								$discountrp = number_format($q->discountrp);
								$xvatrp = number_format($vatrp);
								$materai = number_format($q->materai);
								$xtotall = number_format($totall);
							} else {
								$qty_terima = round($q->qty_terima);
								$xtotalrp = round($totalrp);
								$discountrp = round($q->discountrp);
								$xvatrp = round($vatrp);
								$materai = round($q->materai);
								$xtotall = round($totall);
							}
							$body .= "<tr>
													<td width=\"5%\" style=\"text-align: center;\">" . $no++ . "</td>
													<td width=\"5%\" style=\"text-align: left;\">" . $vdr->vendor_name . "</td>
													<td width=\"5%\" style=\"text-align: center;\">" . date("d-m-Y", strtotime($q->terima_date)) . "</td>
													<td width=\"10%\" style=\"text-align: right;\">" . $qty_terima . "</td>
													<td width=\"10%\" style=\"text-align: right;\">" . $xtotalrp . "</td>
													<td width=\"10%\" style=\"text-align: right;\">" . $discountrp . "</td>
													<td width=\"10%\" style=\"text-align: right;\">" . $xvatrp . "</td>
													<td width=\"10%\" style=\"text-align: right;\">" . $materai . "</td>
													<td width=\"10%\" style=\"text-align: right;\">" . $xtotall . "</td>
												</tr>";
							$tqty_terima += $q->qty_terima;
							$ttotalrp += $totalrp;
							$tdiscountrp += $q->discountrp;
							$tvatrp += $vatrp;
							$tmaterai += $q->materai;
							$ttotalrp2 += ($q->totalrp + $q->vatrp + $q->materai);
						}
						if($cekpdf == 1){
							$xtqty_terima = number_format($tqty_terima);
							$xttotalrp = number_format($ttotalrp);
							$xtdiscountrp = number_format($tdiscountrp);
							$xtvatrp = number_format($tvatrp);
							$xtmaterai = number_format($tmaterai);
							$xttotalrp2 = number_format($ttotalrp2);
						} else {
							$xtqty_terima = round($tqty_terima);
							$xttotalrp = round($ttotalrp);
							$xtdiscountrp = round($tdiscountrp);
							$xtvatrp = round($tvatrp);
							$xtmaterai = round($tmaterai);
							$xttotalrp2 = round($ttotalrp2);
						}
						$body .= 	"<tr>
												<td style=\"text-align: center;\" colspan=\"3\">TOTAL</td>
												<td style=\"text-align: right;\">" . $xtqty_terima . "</td>
												<td style=\"text-align: right;\">" . $xttotalrp . "</td>
												<td style=\"text-align: right;\">" . $xtdiscountrp . "</td>
												<td style=\"text-align: right;\">" . $xtvatrp . "</td>
												<td style=\"text-align: right;\">" . $xtmaterai . "</td>
												<td style=\"text-align: right;\">" . $xttotalrp2 . "</td>
											</tr>";
					$body .= "</table>";
				} else {
					$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE a.koders='$cabang' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->result();
					$getgud = $this->db->query("SELECT a.gudang, d.keterangan FROM tbl_apohterimalog AS a JOIN tbl_depo d ON d.depocode=a.gudang WHERE a.koders = 'KBJ' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2' GROUP BY a.gudang")->result();
					foreach($getgud as $gg){
						$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"5\" cellpadding=\"5\">";
						$body .= "<tr>
														<td style=\"text-align: center; border-top: none; border-bottom: none; border-left: none; border-right: none;\" colspan=\"9\">&nbsp;</td>
													</tr>
													<tr>
														<td style=\"text-align: center; border-top: none; border-left: none; border-bottom: none; border-right: none;\" colspan=\"9\">GUDANG : <b style=\"color: red;\">$gg->keterangan</b></td>
													</tr>";
						$body .= "<tr>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\">No</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Supplier</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\">Tanggal</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Qty</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Total</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Diskon</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Vat Rp</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Materai</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Total Net</td>
											</tr>";
						$no = 1;
						$tqty_terima1 = 0;
						$ttotalrp1 = 0;
						$ttotalrp21 = 0;
						$tdiscountrp1 = 0;
						$tvatrp1 = 0;
						$tmaterai1 = 0;
						$ttotalrp1 = 0;
						foreach($vdr as $v){
							$query = $this->db->query("SELECT a.terima_no, a.terima_date, a.vatrp, a.materai, b.vat, b.discountrp, b.qty_terima , b.discountrp, b.totalrp, (b.totalrp / b.qty_terima ) AS ratarata, c.vendor_name , c.vendor_id FROM tbl_apohterimalog as a JOIN tbl_apodterimalog AS b ON a.terima_no = b.terima_no JOIN tbl_vendor AS c ON a.vendor_id = c.vendor_id WHERE a.vendor_id = '$v->vendor_id' and a.koders = '$cabang' and a.terima_date between '$tanggal1' and '$tanggal2'")->result();
							$tqty_terima = 0;
							$ttotalrp = 0;
							$ttotalrp2 = 0;
							$tdiscountrp = 0;
							$tvatrp = 0;
							$tmaterai = 0;
							$ttotalrp = 0;
							$sql = $this->db->get_where("tbl_pajak", ["kodetax" => "PPN"])->row();
							$pajak = $sql->prosentase / 100;
							foreach ($query as $q) {
								if ($q->vat == 1) {
									$vatrp = ($q->totalrp * $pajak);
								} else {
									$vatrp = 0;
								}
								$totalrp = ($q->totalrp + $q->discountrp);
								$totall = $q->totalrp + $q->vatrp + $q->materai;
								if($cekpdf == 1){
									$qty_terima = number_format($q->qty_terima);
									$discountrp = number_format($q->discountrp);
									$materai = number_format($q->materai);
									$xtotalrp = number_format($totalrp);
									$xvatrp = number_format($vatrp);
									$xtotall = number_format($totall);
								} else {
									$qty_terima = round($q->qty_terima);
									$discountrp = round($q->discountrp);
									$materai = round($q->materai);
									$xtotalrp = round($totalrp);
									$xvatrp = round($vatrp);
									$xtotall = round($totall);
								}
								$body .= "<tr>
															<td width=\"5%\" style=\"text-align: center;\">" . $no++ . "</td>
															<td width=\"10%\" style=\"text-align: left;\">" . $v->vendor_name . "</td>
															<td width=\"5%\" style=\"text-align: center;\">" . date("d-m-Y", strtotime($q->terima_date)) . "</td>
															<td width=\"10%\" style=\"text-align: right;\">" . $qty_terima . "</td>
															<td width=\"10%\" style=\"text-align: right;\">" . $xtotalrp . "</td>
															<td width=\"10%\" style=\"text-align: right;\">" . $discountrp . "</td>
															<td width=\"10%\" style=\"text-align: right;\">" . $xvatrp . "</td>
															<td width=\"10%\" style=\"text-align: right;\">" . $materai . "</td>
															<td width=\"10%\" style=\"text-align: right;\">" . $xtotall . "</td>
														</tr>";
								$tqty_terima += $q->qty_terima;
								$ttotalrp += $totalrp;
								$tdiscountrp += $q->discountrp;
								$tvatrp += $vatrp;
								$tmaterai += $q->materai;
								$ttotalrp2 += ($q->totalrp + $q->vatrp + $q->materai);
							}
							$tqty_terima1 += $tqty_terima;
							$ttotalrp1 += $ttotalrp;
							$tdiscountrp1 += $tdiscountrp;
							$tvatrp1 += $tvatrp;
							$tmaterai1 += $tmaterai;
							$ttotalrp21 += $ttotalrp2;
						}
						if($cekpdf == 1){
							$xtqty_terima1 = number_format($tqty_terima1);
							$xtdiscountrp1 = number_format($tdiscountrp1);
							$xttotalrp1 = number_format($ttotalrp1);
							$xtvatrp1 = number_format($tvatrp1);
							$xtmaterai1 = number_format($tmaterai1);
							$xttotalrp21 = number_format($ttotalrp21);
						} else {
							$xtqty_terima1 = round($tqty_terima1);
							$xtdiscountrp1 = round($tdiscountrp1);
							$xttotalrp1 = round($ttotalrp1);
							$xtvatrp1 = round($tvatrp1);
							$xtmaterai1 = round($tmaterai1);
							$xttotalrp21 = round($ttotalrp21);
						}
						$body .= 	"<tr>
													<td style=\"text-align: center;\" colspan=\"3\">TOTAL</td>
													<td style=\"text-align: right;\">" . $xtqty_terima1 . "</td>
													<td style=\"text-align: right;\">" . $xttotalrp1 . "</td>
													<td style=\"text-align: right;\">" . $xtdiscountrp1 . "</td>
													<td style=\"text-align: right;\">" . $xtvatrp1 . "</td>
													<td style=\"text-align: right;\">" . $xtmaterai1 . "</td>
													<td style=\"text-align: right;\">" . $xttotalrp21 . "</td>
												</tr>";
						$body .= "</table><br><br>";
					}
				}
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
										<tr>
											<td> &nbsp; </td>
										</tr> 
										<tr>
											<td style=\"text-align:center;\"><i>HOSPITAL MANAGEMENT SYSTEM</i></td>
										</tr> 
										<tr>
											<td style=\"text-align:center;\">$kota ," . date("d-m-Y") . "</td>
										</tr> 
									</table>";
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
										<tr>
											<td> &nbsp; </td>
										</tr> 
									</table>";
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"70%\" align=\"center\" border=\"1\">
										<tr>
											<td style=\"text-align:center;\" width=\"33%\">Diketahui oleh,</td>
											<td style=\"text-align:center;\" width=\"33%\">Diserahkan oleh,</td>
											<td style=\"text-align:center;\" width=\"33%\">Dibuat oleh,</td>
										</tr> 
										<tr>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
										</tr> 
										<tr>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
										</tr> 
										<tr>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center; border-bottom:none; border-top:none;\">&nbsp;</td>
										</tr> 
										<tr>
											<td width=\"33%\" style=\"text-align:center;\">2</td>
											<td width=\"33%\" style=\"text-align:center;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center;\">HARYANTO</td>
										</tr> 
										<tr>
											<td width=\"33%\" style=\"text-align:center;\">Kepala Apoteker</td>
											<td width=\"33%\" style=\"text-align:center;\">&nbsp;</td>
											<td width=\"33%\" style=\"text-align:center;\">PENANGGUNG JAWAB ADMINISTRASI</td>
										</tr> 
									</table>";
			} else if($idlap == 105){
				$judul = '05 LAPORAN PEMBELIAN BARANG PER ITEM (TOTAL)';
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">";
				if ($vendor != '') {
					$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE a.vendor_id = '$vendor' AND a.koders='$cabang' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->row();
					$query = $this->db->query("SELECT b.kodebarang, (select namabarang from tbl_logbarang where kodebarang = b.kodebarang) as namabarang, d.vendor_id, b.satuan, b.qty_terima, (b.totalrp / b.qty_terima ) AS ratarata, b.koders, c.vendor_id, c.vendor_name FROM tbl_apodterimalog AS b JOIN tbl_apohterimalog AS d on b.terima_no = d.terima_no JOIN tbl_vendor AS c ON d.vendor_id = c.vendor_id WHERE d.vendor_id = '$vdr->vendor_id' and b.koders = '$cabang' and d.terima_date between '$tanggal1' and '$tanggal2'")->result();
					$body .= "<tr>
											<td style=\"text-align: center; border-top: none; border-bottom: none; border-left: none; border-right: none;\" colspan=\"7\">&nbsp;</td>
										</tr>
										<tr>
											<td style=\"text-align: center; border-top: none; border-left: none; border-bottom: none; border-right: none;\" colspan=\"7\">SUPPLIER : <b style=\"color: red;\">$vdr->vendor_name</b></td>
										</tr>";
					$body .= "<tr>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">No</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Kode Barang</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Nama Barang</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Satuan</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Qty</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Harga rata-rata</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Total</td>
											</tr>";
					$no = 1;
					$tqty_terima = 0;
					$tratarata = 0;
					$ttotal = 0;
					foreach ($query as $q) {
						$total = $q->qty_terima * $q->ratarata;
						if($cekpdf == 1){
							$qty_terima = number_format($q->qty_terima);
							$ratarata = number_format($q->ratarata);
							$xtotal = number_format($total);
						} else {
							$qty_terima = round($q->qty_terima);
							$ratarata = round($q->ratarata);
							$xtotal = round($total);
						}
						$body .= 	"<tr>
												<td style=\"text-align: right;\">" . $no++ . "</td>
												<td>$q->kodebarang</td>
												<td>$q->namabarang</td>
												<td>$q->satuan</td>
												<td style=\"text-align: right;\">" . $qty_terima . "</td>
												<td style=\"text-align: right;\">" . $ratarata . "</td>
												<td style=\"text-align: right;\">" . $xtotal . "</td>
											</tr>";
						$tqty_terima += $q->qty_terima;
						$tratarata += $q->ratarata;
						$ttotal += $total;
					}
					if($cekpdf == 1){
						$xtqty_terima = number_format($tqty_terima);
						$xtratarata = number_format($tratarata);
						$xttotal = number_format($ttotal);
					} else {
						$xtqty_terima = round($tqty_terima);
						$xtratarata = round($tratarata);
						$xttotal = round($ttotal);
					}
					$body .= 	"<tr>
											<td style=\"text-align: center;\" colspan=\"4\">TOTAL</td>
											<td style=\"text-align: right;\">" . $xtqty_terima . "</td>
											<td style=\"text-align: right;\">" . $xtratarata . "</td>
											<td style=\"text-align: right;\">" . $xttotal . "</td>
										</tr>";
				} else {
					$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE a.koders='$cabang' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->result();
					foreach($vdr as $v){
						$query = $this->db->query("SELECT b.kodebarang, (select namabarang from tbl_logbarang where kodebarang = b.kodebarang) as namabarang, d.vendor_id, b.satuan, b.qty_terima, (b.totalrp / b.qty_terima ) AS ratarata, b.koders, c.vendor_id, c.vendor_name FROM tbl_apodterimalog AS b JOIN tbl_apohterimalog AS d on b.terima_no = d.terima_no JOIN tbl_vendor AS c ON d.vendor_id = c.vendor_id WHERE d.vendor_id = '$v->vendor_id' and b.koders = '$cabang' and d.terima_date between '$tanggal1' and '$tanggal2'")->result();
						$body .= "<tr>
												<td style=\"text-align: center; border-top: none; border-bottom: none; border-left: none; border-right: none;\" colspan=\"7\">&nbsp;</td>
											</tr>
											<tr>
												<td style=\"text-align: center; border-top: none; border-left: none; border-bottom: none; border-right: none;\" colspan=\"7\">SUPPLIER : <b style=\"color: red;\">$v->vendor_name</b></td>
											</tr>";
						$body .= "<tr>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">No</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Kode Barang</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Nama Barang</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Satuan</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Qty</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Harga rata-rata</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Total</td>
											</tr>";
						$no = 1;
						$tqty_terima = 0;
						$tratarata = 0;
						$ttotal = 0;
						foreach ($query as $q) {
							$total = $q->qty_terima * $q->ratarata;
							if($cekpdf == 1){
								$qty_terima = number_format($q->qty_terima);
								$ratarata = number_format($q->ratarata);
								$xtotal = number_format($total);
							} else {
								$qty_terima = round($q->qty_terima);
								$ratarata = round($q->ratarata);
								$xtotal = round($total);
							}
							$body .= 	"<tr>
												<td style=\"text-align: right;\">" . $no++ . "</td>
												<td>$q->kodebarang</td>
												<td>$q->namabarang</td>
												<td>$q->satuan</td>
												<td style=\"text-align: right;\">" . $qty_terima . "</td>
												<td style=\"text-align: right;\">" . $ratarata . "</td>
												<td style=\"text-align: right;\">" . $xtotal . "</td>
											</tr>";
							$tqty_terima += $q->qty_terima;
							$tratarata += $q->ratarata;
							$ttotal += $total;
						}
						if($cekpdf == 1){
							$xtqty_terima = number_format($tqty_terima);
							$xtratarata = number_format($tratarata);
							$xttotal = number_format($ttotal);
						} else {
							$xtqty_terima = round($tqty_terima);
							$xtratarata = round($tratarata);
							$xttotal = round($ttotal);
						}
						$body .= 	"<tr>
											<td style=\"text-align: center;\" colspan=\"4\">TOTAL</td>
											<td style=\"text-align: right;\">" . $xtqty_terima . "</td>
											<td style=\"text-align: right;\">" . $xtratarata . "</td>
											<td style=\"text-align: right;\">" . $xttotal . "</td>
										</tr>";
					}
				}
				$body .= "</table>";
			} else if($idlap == 106){
				$position = 'L';
				$judul = '06 LAPORAN STATUS ORDER PEMBELIAN';
				if ($vendor != '') {
					$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">";
					$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE a.vendor_id = '$vendor' AND  a.koders='$cabang' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->row();
					$query = $this->db->query("SELECT a.terima_date , a.koders,  b.price, b.satuan , b.qty_terima, b.po_no, c.vendor_id , c.vendor_name, d.kodebarang ,d.namabarang FROM tbl_apohterimalog AS a JOIN  tbl_apodterimalog AS b ON a.terima_no = b.terima_no JOIN tbl_vendor AS c ON a.vendor_id = c.vendor_id JOIN tbl_logbarang AS d ON b.kodebarang = d.kodebarang WHERE a.vendor_id = '$vdr->vendor_id' and a.koders = '$cabang' and a.terima_date between '$tanggal1' and '$tanggal2'")->result();
					$body .= "<tr>
											<td style=\"text-align: center; border-top: none; border-bottom: none; border-left: none; border-right: none;\" colspan=\"7\">&nbsp;</td>
										</tr>
										<tr>
											<td style=\"text-align: center; border-top: none; border-left: none; border-bottom: none; border-right: none;\" colspan=\"9\">SUPPLIER : <b style=\"color: red;\">$vdr->vendor_name</b></td>
										</tr>";
					$body .= "<tr>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\">No</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"15%\">PO No.</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Tanggal</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"15%\">Kode Barang</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"15%\">Nama Barang</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Satuan</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Qty</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Harga sat</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Total</td>
											</tr>";
					$no = 1;
					$tqty_terima = 0;
					$tprice = 0;
					$ttotal = 0;
					foreach ($query as $q) {
						$total = $q->price * $q->qty_terima;
						if ($q->po_no == '') {
							$po_no = '-';
						} else {
							$po_no = $q->po_no;
						}
						if($cekpdf == 1){
							$qty_terima = number_format($q->qty_terima);
							$price = number_format($q->price);
							$xtotal = number_format($total);
						} else {
							$qty_terima = round($q->qty_terima);
							$price = round($q->price);
							$xtotal = round($total);
						}
						$body .= 		"<tbody>
											<tr>
												<td style=\"text-align: right;\">" . $no++ . "</td>
												<td>$po_no</td>
												<td style=\"text-align: center;\">" . date("d-m-Y", strtotime($q->terima_date)) . "</td>
												<td>$q->kodebarang</td>
												<td>$q->namabarang</td>
												<td>$q->satuan</td>
												<td style=\"text-align: right;\">" . $qty_terima . "</td>
												<td style=\"text-align: right;\">" . $price . "</td>
												<td style=\"text-align: right;\">" . $xtotal . "</td>
											</tr>
										</tbody>";
						$tqty_terima += $q->qty_terima;
						$tprice += $q->price;
						$ttotal += $total;
					}
					if($cekpdf == 1){
						$xtqty_terima = number_format($tqty_terima);
						$xtprice = number_format($tprice);
						$xttotal = number_format($ttotal);
					} else {
						$xtqty_terima = round($tqty_terima);
						$xtprice = round($tprice);
						$xttotal = round($ttotal);
					}
					$body .= 	"<tfoot>
										<tr>
											<td style=\"text-align: center;\" colspan=\"6\">TOTAL</td>
											<td style=\"text-align: right;\">" . $xtqty_terima . "</td>
											<td style=\"text-align: right;\">" . $xtprice . "</td>
											<td style=\"text-align: right;\">" . $xttotal . "</td>
										</tr>
									</tfoot>";
					$body .= "</table>";
				} else {
					$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE a.koders='$cabang' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->result();
					foreach($vdr as $v){
						$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">";
						$query = $this->db->query("SELECT a.terima_date , a.koders,  b.price, b.satuan , b.qty_terima, b.po_no, c.vendor_id , c.vendor_name, d.kodebarang ,d.namabarang FROM tbl_apohterimalog AS a JOIN  tbl_apodterimalog AS b ON a.terima_no = b.terima_no JOIN tbl_vendor AS c ON a.vendor_id = c.vendor_id JOIN tbl_logbarang AS d ON b.kodebarang = d.kodebarang WHERE a.vendor_id = '$v->vendor_id' and a.koders = '$cabang' and a.terima_date between '$tanggal1' and '$tanggal2'")->result();
						$body .= "<tr>
											<td style=\"text-align: center; border-top: none; border-bottom: none; border-left: none; border-right: none;\" colspan=\"9\">&nbsp;</td>
										</tr>
										<tr>
											<td style=\"text-align: center; border-top: none; border-left: none; border-bottom: none; border-right: none;\" colspan=\"9\">SUPPLIER : <b style=\"color: red;\">$v->vendor_name</b></td>
										</tr>";
						$body .= "<tr>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\">No</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"15%\">PO No.</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Tanggal</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"15%\">Kode Barang</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"15%\">Nama Barang</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Satuan</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Qty</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Harga sat</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Total</td>
											</tr>";
						$no = 1;
						$tqty_terima = 0;
						$tprice = 0;
						$ttotal = 0;
						foreach ($query as $q) {
							$total = $q->price * $q->qty_terima;
							if ($q->po_no == '') {
								$po_no = '-';
							} else {
								$po_no = $q->po_no;
							}
							if ($cekpdf == 1) {
								$qty_terima = number_format($q->qty_terima);
								$price = number_format($q->price);
								$xtotal = number_format($total);
							} else {
								$qty_terima = round($q->qty_terima);
								$price = round($q->price);
								$xtotal = round($total);
							}
							$body .= 		"<tbody>
											<tr>
												<td style=\"text-align: right;\">" . $no++ . "</td>
												<td>$po_no</td>
												<td style=\"text-align: center;\">" . date("d-m-Y", strtotime($q->terima_date)) . "</td>
												<td>$q->kodebarang</td>
												<td>$q->namabarang</td>
												<td>$q->satuan</td>
												<td style=\"text-align: right;\">" . $qty_terima . "</td>
												<td style=\"text-align: right;\">" . $price . "</td>
												<td style=\"text-align: right;\">" . $xtotal . "</td>
											</tr>
										</tbody>";
							$tqty_terima += $q->qty_terima;
							$tprice += $q->price;
							$ttotal += $total;
						}
						if ($cekpdf == 1) {
							$xtqty_terima = number_format($tqty_terima);
							$xtprice = number_format($tprice);
							$xttotal = number_format($ttotal);
						} else {
							$xtqty_terima = round($tqty_terima);
							$xtprice = round($tprice);
							$xttotal = round($ttotal);
						}
						$body .= 	"<tfoot>
										<tr>
											<td style=\"text-align: center;\" colspan=\"6\">TOTAL</td>
											<td style=\"text-align: right;\">" . $xtqty_terima . "</td>
											<td style=\"text-align: right;\">" . $xtprice . "</td>
											<td style=\"text-align: right;\">" . $xttotal . "</td>
										</tr>
									</tfoot>";
						$body .= "</table>";
					}
				}
			} else if($idlap == 107){
				$judul = '07 LAPORAN RETURN PEMBELIAN';
				$position = "L";
				if ($vendor != '') {
					$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE a.vendor_id = '$vendor' and  a.koders='$cabang' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->row();
					$query = $this->db->query("SELECT a.retur_no, a.retur_date, a.terima_no, b.kodebarang, (select namabarang from tbl_logbarang where kodebarang = b.kodebarang) as namabarang, b.qty_retur, b.satuan, b.price, b.totalrp, c.po_no from tbl_apohreturbelilog a join tbl_apodreturbelilog b on a.retur_no=b.retur_no JOIN tbl_apodterimalog c ON c.terima_no = a.terima_no join tbl_apohterimalog d on c.terima_no = d.terima_no where a.koders = '$cabang' and a.vendor_id = '$vdr->vendor_id' and d.terima_date between '$tanggal1' and '$tanggal2' group by b.kodebarang")->result();
					$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">";
					$body .= "<tr>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">No</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Retur No.</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Tanggal</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">No. BAPB</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">PO No.</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Kode Barang</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Nama Barang</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Satuan</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Qty</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Harga sat</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Total</td>
										</tr>
										<tr>
											<td style=\"text-align: left;\" colspan=\"11\">SUPPLIER : <b style=\"color: red;\">$vdr->vendor_name</b></td>
										</tr>";
					$no = 1;
					$tqty_retur = 0;
					$tprice = 0;
					$ttotalrp = 0;
					$totalsemua = 0;
					foreach ($query as $q) {
						if ($q->po_no == '') {
							$po_no = '-';
						} else {
							$po_no = $q->po_no;
						}
						if($cekpdf == 1){
							$qty_retur = number_format($q->qty_retur);
							$price = number_format($q->price);
							$totalrp = number_format($q->totalrp);
						} else {
							$qty_retur = round($q->qty_retur);
							$price = round($q->price);
							$totalrp = round($q->totalrp);
						}
						$body .= "<tr>
												<td style=\"text-align: right;\">" . $no++ . "</td>
												<td>$q->retur_no</td>
												<td style=\"text-align: center;\">" . date("d-m-Y", strtotime($q->retur_date)) . "</td>
												<td>$q->terima_no</td>
												<td>$po_no</td>
												<td>$q->kodebarang</td>
												<td>$q->namabarang</td>
												<td>$q->satuan</td>
												<td style=\"text-align: right;\">" . $qty_retur . "</td>
												<td style=\"text-align: right;\">" . $price . "</td>
												<td width=\"15%\" style=\"text-align: right;\">" . $totalrp . "</td>
											</tr>";
						$tqty_retur += $q->qty_retur;
						$tprice += $q->price;
						$ttotalrp += $q->totalrp;
					}
					if($cekpdf == 1){
						$xtqty_retur = number_format($tqty_retur);
						$xtprice = number_format($tprice);
						$xttotalrp = number_format($ttotalrp);
					} else {
						$xtqty_retur = round($tqty_retur);
						$xtprice = round($tprice);
						$xttotalrp = round($ttotalrp);
					}
					$body .= "<tr>
											<td colspan=\"8\">SUBTOTAL</td>
											<td style=\"text-align: right; font-weight: bold; color:red;\">" . $xtqty_retur . "</td>
											<td style=\"text-align: right; font-weight: bold; color:red;\">" . $xtprice . "</td>
											<td width=\"15%\" style=\"text-align: right; font-weight: bold; color:red;\">" . $xttotalrp . "</td>
										</tr>";
					$totalsemua += $ttotalrp;
					if($cekpdf == 1){
						$xtotalsemua = number_format($totalsemua);
					} else {
						$xtotalsemua = round($totalsemua);
					}
					$body .= "<tr>
											<td style=\"text-align: center; border-top: none; border-bottom: none; border-left: none; border-right: none;\" colspan=\"11\">&nbsp;</td>
										</tr>
										<tr>
											<td style=\"text-align: center; color: red; font-weight: bold;\" colspan=\"10\">TOTAL KESELURUHAN</td>
											<td style=\"text-align: right;\"><b style=\"color: red;\">" . $xtotalsemua . "</b></td>
										</tr>";
					$body .= "</table>";
				} else {
					$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE a.koders='$cabang' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->result();
					$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">";
					$body .= "<tr>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\">No</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Retur No.</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Tanggal</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">No. BAPB</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">PO No.</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Kode Barang</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Nama Barang</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\">Satuan</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"5%\">Qty</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Harga sat</td>
												<td style=\"text-align: center; font-weight: bold; padding: 10px;\" width=\"10%\">Total</td>
										</tr>";
					$totalsemua = 0;
					foreach($vdr as $v){
						$query = $this->db->query("SELECT a.retur_no, a.retur_date, a.terima_no, b.kodebarang, (select namabarang from tbl_logbarang where kodebarang = b.kodebarang) as namabarang, b.qty_retur, b.satuan, b.price, b.totalrp, c.po_no from tbl_apohreturbelilog a join tbl_apodreturbelilog b on a.retur_no=b.retur_no JOIN tbl_apodterimalog c ON c.terima_no = a.terima_no join tbl_apohterimalog d on c.terima_no = d.terima_no where a.koders = '$cabang' and a.vendor_id = '$v->vendor_id' and d.terima_date between '$tanggal1' and '$tanggal2' group by b.kodebarang")->result();
						$body .= "<tr>
											<td style=\"text-align: left;\" colspan=\"11\">SUPPLIER : <b style=\"color: red;\">$v->vendor_name</b></td>
										</tr>";
						$no = 1;
						$tqty_retur = 0;
						$tprice = 0;
						$ttotalrp = 0;
						foreach ($query as $q) {
							if ($q->po_no == '') {
								$po_no = '-';
							} else {
								$po_no = $q->po_no;
							}
							$tqty_retur += $q->qty_retur;
							$tprice += $q->price;
							$ttotalrp += $q->totalrp;
							if($cekpdf == 1){
								$qty_retur = number_format($q->qty_retur);
								$price = number_format($q->price);
								$totalrp = number_format($q->totalrp);
							} else {
								$qty_retur = round($q->qty_retur);
								$price = round($q->price);
								$totalrp = round($q->totalrp);
							}
							$body .= "<tr>
												<td width=\"5%\" style=\"text-align: right;\">" . $no++ . "</td>
												<td width=\"10%\">$q->retur_no</td>
												<td width=\"10%\" style=\"text-align: center;\">" . date("d-m-Y", strtotime($q->retur_date)) . "</td>
												<td width=\"10%\">$q->terima_no</td>
												<td width=\"10%\">$po_no</td>
												<td width=\"10%\">$q->kodebarang</td>
												<td width=\"10%\">$q->namabarang</td>
												<td width=\"5%\">$q->satuan</td>
												<td width=\"5%\" style=\"text-align: right;\">" . $qty_retur . "</td>
												<td width=\"10%\" style=\"text-align: right;\">" . $price . "</td>
												<td width=\"10%\" style=\"text-align: right;\">" . $totalrp . "</td>
											</tr>";
						}
						if($cekpdf == 1){
							$xtqty_retur = number_format($tqty_retur);
							$xtprice = number_format($tprice);
							$xttotalrp = number_format($ttotalrp);
						} else {
							$xtqty_retur = round($tqty_retur);
							$xtprice = round($tprice);
							$xttotalrp = round($ttotalrp);
						}
						$body .= "<tr>
											<td colspan=\"8\">SUBTOTAL</td>
											<td style=\"text-align: right; font-weight: bold; color:red;\">" . $xtqty_retur . "</td>
											<td style=\"text-align: right; font-weight: bold; color:red;\">" . $xtprice . "</td>
											<td style=\"text-align: right; font-weight: bold; color:red;\">" . $xttotalrp . "</td>
										</tr>";
						$totalsemua += $ttotalrp;
					}
					if($cekpdf == 1){
						$xtotalsemua = number_format($totalsemua);
					} else {
						$xtotalsemua = round($totalsemua);
					}
					$body .= "<tr>
											<td style=\"text-align: center; border-top: none; border-bottom: none; border-left: none; border-right: none;\" colspan=\"11\">&nbsp;</td>
										</tr>
										<tr>
											<td style=\"text-align: center; color: red; font-weight: bold;\" colspan=\"10\">TOTAL KESELURUHAN</td>
											<td style=\"text-align: right;\"><b style=\"color: red;\">" . $xtotalsemua . "</b></td>
										</tr>";
					$body .= "</table>";
				}
			} else if($idlap == 108){
				$position = "L";
				$judul = '08 LAPORAN HUTANG GUDANG LOGISTIK';
				if ($vendor != '') {
					$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE a.vendor_id = '$vendor' AND a.koders='$cabang' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->row();
					$query = $this->db->query("SELECT 
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
						WHERE tbl_apohpolog.vendor_id = '$vdr->vendor_id' and po_date BETWEEN '$tanggal1' AND '$tanggal2'

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
						WHERE tbl_apohterimalog.vendor_id = '$vdr->vendor_id' and terima_date BETWEEN '$tanggal1' AND '$tanggal2'
					")->result();
					$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">";
					$body .= "<tr>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">No</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Rekanan</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">No. Faktur</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Obat</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Alkes Rutin</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Alk. Investasi</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Bahan Kimia</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Gas Medik</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Pemeliharaan</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Sewa</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Pelengkap</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Lain-lain</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Materai</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Jumlah</td>
										</tr>";
					$no = 1;
					$tobat = 0;
					$talkes_rutin = 0;
					$talk_investasi = 0;
					$tbahan_kimia = 0;
					$tgas_medik = 0;
					$tpemeliharaan = 0;
					$tsewa = 0;
					$tpelengkapan = 0;
					$tlain2 = 0;
					$tmaterai = 0;
					$tjumlah = 0;
					foreach ($query as $q) {
						if($cekpdf == 1){
							$obat = number_format($q->obat);
							$alkes_rutin = number_format($q->alkes_rutin);
							$alk_investasi = number_format($q->alk_investasi);
							$bahan_kimia = number_format($q->bahan_kimia);
							$gas_medik = number_format($q->gas_medik);
							$pemeliharaan = number_format($q->pemeliharaan);
							$sewa = number_format($q->sewa);
							$pelengkapan = number_format($q->pelengkapan);
							$lain2 = number_format($q->lain2);
							$materai = number_format($q->materai);
							$jumlah = number_format($q->jumlah);
						} else {
							$obat = round($q->obat);
							$alkes_rutin = round($q->alkes_rutin);
							$alk_investasi = round($q->alk_investasi);
							$bahan_kimia = round($q->bahan_kimia);
							$gas_medik = round($q->gas_medik);
							$pemeliharaan = round($q->pemeliharaan);
							$sewa = round($q->sewa);
							$pelengkapan = round($q->pelengkapan);
							$lain2 = round($q->lain2);
							$materai = round($q->materai);
							$jumlah = round($q->jumlah);
						}
						$body .= "<tr>
												<td style=\"text-align: right;\">" . $no++ . "</td>
												<td>$q->rekanan</td>
												<td>$q->no_faktur</td>
												<td style=\"text-align: right;\">" . $obat . "</td>
												<td style=\"text-align: right;\">" . $alkes_rutin . "</td>
												<td style=\"text-align: right;\">" . $alk_investasi . "</td>
												<td style=\"text-align: right;\">" . $bahan_kimia . "</td>
												<td style=\"text-align: right;\">" . $gas_medik . "</td>
												<td style=\"text-align: right;\">" . $pemeliharaan . "</td>
												<td style=\"text-align: right;\">" . $sewa . "</td>
												<td style=\"text-align: right;\">" . $pelengkapan . "</td>
												<td style=\"text-align: right;\">" . $lain2 . "</td>
												<td style=\"text-align: right;\">" . $materai . "</td>
												<td style=\"text-align: right;\">" . $jumlah . "</td>
											</tr>";
						$tobat += $q->obat;
						$talkes_rutin += $q->alkes_rutin;
						$talk_investasi += $q->alk_investasi;
						$tbahan_kimia += $q->bahan_kimia;
						$tgas_medik += $q->gas_medik;
						$tpemeliharaan += $q->pemeliharaan;
						$tsewa += $q->sewa;
						$tpelengkapan += $q->pelengkapan;
						$tlain2 += $q->lain2;
						$tmaterai += $q->materai;
						$tjumlah += $q->jumlah;
					}
					if($cekpdf == 1){
						$xtobat = number_format($tobat);
						$xtalkes_rutin = number_format($talkes_rutin);
						$xtalk_investasi = number_format($talk_investasi);
						$xtbahan_kimia = number_format($tbahan_kimia);
						$xtgas_medik = number_format($tgas_medik);
						$xtpemeliharaan = number_format($tpemeliharaan);
						$xtsewa = number_format($tsewa);
						$xtpelengkapan = number_format($tpelengkapan);
						$xtlain2 = number_format($tlain2);
						$xtmaterai = number_format($tmaterai);
						$xtjumlah = number_format($tjumlah);
					} else {
						$xtobat = round($tobat);
						$xtalkes_rutin = round($talkes_rutin);
						$xtalk_investasi = round($talk_investasi);
						$xtbahan_kimia = round($tbahan_kimia);
						$xtgas_medik = round($tgas_medik);
						$xtpemeliharaan = round($tpemeliharaan);
						$xtsewa = round($tsewa);
						$xtpelengkapan = round($tpelengkapan);
						$xtlain2 = round($tlain2);
						$xtmaterai = round($tmaterai);
						$xtjumlah = round($tjumlah);
					}
					$body .= 	"<tr>
											<td style=\"text-align: center;\" colspan=\"3\">TOTAL</td>
											<td style=\"text-align: right;\">" . $xtobat . "</td>
											<td style=\"text-align: right;\">" . $xtalkes_rutin . "</td>
											<td style=\"text-align: right;\">" . $xtalk_investasi . "</td>
											<td style=\"text-align: right;\">" . $xtbahan_kimia . "</td>
											<td style=\"text-align: right;\">" . $xtgas_medik . "</td>
											<td style=\"text-align: right;\">" . $xtpemeliharaan . "</td>
											<td style=\"text-align: right;\">" . $xtsewa . "</td>
											<td style=\"text-align: right;\">" . $xtpelengkapan . "</td>
											<td style=\"text-align: right;\">" . $xtlain2 . "</td>
											<td style=\"text-align: right;\">" . $xtmaterai . "</td>
											<td style=\"text-align: right;\">" . $xtjumlah . "</td>
										</tr>";
					$body .= "</table>";
				} else {
					$vdr = $this->db->query("SELECT vendor_name, vendor_id FROM tbl_vendor d WHERE d.vendor_id IN (SELECT a.vendor_id FROM tbl_apohterimalog a WHERE a.koders='$cabang' AND a.terima_date BETWEEN '$tanggal1' AND '$tanggal2')")->result();
					$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">";
					$body .= "<tr>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">No</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Rekanan</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">No. Faktur</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Obat</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Alkes Rutin</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Alk. Investasi</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Bahan Kimia</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Gas Medik</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Pemeliharaan</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Sewa</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Pelengkap</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Lain-lain</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Materai</td>
											<td style=\"text-align: center; font-weight: bold; padding: 10px;\">Jumlah</td>
										</tr>";
					$no = 1;
					$tobat1 = 0;
					$talkes_rutin1 = 0;
					$talk_investasi1 = 0;
					$tbahan_kimia1 = 0;
					$tgas_medik1 = 0;
					$tpemeliharaan1 = 0;
					$tsewa1 = 0;
					$tpelengkapan1 = 0;
					$tlain21 = 0;
					$tmaterai1 = 0;
					$tjumlah1 = 0;
					foreach($vdr as $v){
						$query = $this->db->query("SELECT 
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
						")->result();
						$tobat = 0;
						$talkes_rutin = 0;
						$talk_investasi = 0;
						$tbahan_kimia = 0;
						$tgas_medik = 0;
						$tpemeliharaan = 0;
						$tsewa = 0;
						$tpelengkapan = 0;
						$tlain2 = 0;
						$tmaterai = 0;
						$tjumlah = 0;
						foreach ($query as $q) {
							if ($cekpdf == 1) {
								$obat = number_format($q->obat);
								$alkes_rutin = number_format($q->alkes_rutin);
								$alk_investasi = number_format($q->alk_investasi);
								$bahan_kimia = number_format($q->bahan_kimia);
								$gas_medik = number_format($q->gas_medik);
								$pemeliharaan = number_format($q->pemeliharaan);
								$sewa = number_format($q->sewa);
								$pelengkapan = number_format($q->pelengkapan);
								$lain2 = number_format($q->lain2);
								$materai = number_format($q->materai);
								$jumlah = number_format($q->jumlah);
							} else {
								$obat = round($q->obat);
								$alkes_rutin = round($q->alkes_rutin);
								$alk_investasi = round($q->alk_investasi);
								$bahan_kimia = round($q->bahan_kimia);
								$gas_medik = round($q->gas_medik);
								$pemeliharaan = round($q->pemeliharaan);
								$sewa = round($q->sewa);
								$pelengkapan = round($q->pelengkapan);
								$lain2 = round($q->lain2);
								$materai = round($q->materai);
								$jumlah = round($q->jumlah);
							}
							$body .= "<tr>
												<td style=\"text-align: right;\">" . $no++ . "</td>
												<td>$q->rekanan</td>
												<td>$q->no_faktur</td>
												<td style=\"text-align: right;\">" . $obat . "</td>
												<td style=\"text-align: right;\">" . $alkes_rutin . "</td>
												<td style=\"text-align: right;\">" . $alk_investasi . "</td>
												<td style=\"text-align: right;\">" . $bahan_kimia . "</td>
												<td style=\"text-align: right;\">" . $gas_medik . "</td>
												<td style=\"text-align: right;\">" . $pemeliharaan . "</td>
												<td style=\"text-align: right;\">" . $sewa . "</td>
												<td style=\"text-align: right;\">" . $pelengkapan . "</td>
												<td style=\"text-align: right;\">" . $lain2 . "</td>
												<td style=\"text-align: right;\">" . $materai . "</td>
												<td style=\"text-align: right;\">" . $jumlah . "</td>
											</tr>";
							$tobat += $q->obat;
							$talkes_rutin += $q->alkes_rutin;
							$talk_investasi += $q->alk_investasi;
							$tbahan_kimia += $q->bahan_kimia;
							$tgas_medik += $q->gas_medik;
							$tpemeliharaan += $q->pemeliharaan;
							$tsewa += $q->sewa;
							$tpelengkapan += $q->pelengkapan;
							$tlain2 += $q->lain2;
							$tmaterai += $q->materai;
							$tjumlah += $q->jumlah;
						}
						$tobat1 += $tobat;
						$talkes_rutin1 += $talkes_rutin;
						$talk_investasi1 += $talk_investasi;
						$tbahan_kimia1 += $tbahan_kimia;
						$tgas_medik1 += $tgas_medik;
						$tpemeliharaan1 += $tpemeliharaan;
						$tsewa1 += $tsewa;
						$tpelengkapan1 += $tpelengkapan;
						$tlain21 += $tlain2;
						$tmaterai1 += $tmaterai;
						$tjumlah1 += $tjumlah;
					}

					if ($cekpdf == 1) {
						$xtobat = number_format($tobat);
						$xtalkes_rutin = number_format($talkes_rutin);
						$xtalk_investasi = number_format($talk_investasi);
						$xtbahan_kimia = number_format($tbahan_kimia);
						$xtgas_medik = number_format($tgas_medik);
						$xtpemeliharaan = number_format($tpemeliharaan);
						$xtsewa = number_format($tsewa);
						$xtpelengkapan = number_format($tpelengkapan);
						$xtlain2 = number_format($tlain2);
						$xtmaterai = number_format($tmaterai);
						$xtjumlah = number_format($tjumlah);
					} else {
						$xtobat = round($tobat);
						$xtalkes_rutin = round($talkes_rutin);
						$xtalk_investasi = round($talk_investasi);
						$xtbahan_kimia = round($tbahan_kimia);
						$xtgas_medik = round($tgas_medik);
						$xtpemeliharaan = round($tpemeliharaan);
						$xtsewa = round($tsewa);
						$xtpelengkapan = round($tpelengkapan);
						$xtlain2 = round($tlain2);
						$xtmaterai = round($tmaterai);
						$xtjumlah = round($tjumlah);
					}
					$body .= 	"<tr>
											<td style=\"text-align: center;\" colspan=\"3\">TOTAL</td>
											<td style=\"text-align: right;\">" . $xtobat . "</td>
											<td style=\"text-align: right;\">" . $xtalkes_rutin . "</td>
											<td style=\"text-align: right;\">" . $xtalk_investasi . "</td>
											<td style=\"text-align: right;\">" . $xtbahan_kimia . "</td>
											<td style=\"text-align: right;\">" . $xtgas_medik . "</td>
											<td style=\"text-align: right;\">" . $xtpemeliharaan . "</td>
											<td style=\"text-align: right;\">" . $xtsewa . "</td>
											<td style=\"text-align: right;\">" . $xtpelengkapan . "</td>
											<td style=\"text-align: right;\">" . $xtlain2 . "</td>
											<td style=\"text-align: right;\">" . $xtmaterai . "</td>
											<td style=\"text-align: right;\">" . $xtjumlah . "</td>
										</tr>";
					$body .= "</table>";
				}
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
										<tr>
											<td> &nbsp; </td>
										</tr> 
									</table>";
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
										<tr>
											<td> &nbsp; </td>
										</tr> 
									</table>";
				$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"70%\" align=\"center\" border=\"1\">
										<tr>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\">Petugas,</td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\">Mengetahui,</td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\">Disetujui,</td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\">Diterima oleh,</td>
										</tr> 
										<tr>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\"> &nbsp; </td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\"> &nbsp; </td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\"> &nbsp; </td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\"> &nbsp; </td>
										</tr> 
										<tr>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\"> &nbsp; </td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\"> &nbsp; </td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\"> &nbsp; </td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\"> &nbsp; </td>
										</tr> 
										<tr>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\"> &nbsp; </td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\"> &nbsp; </td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\"> &nbsp; </td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\"> &nbsp; </td>
										</tr> 
										<tr>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\">----------------------------</td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\">----------------------------</td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\">----------------------------</td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\">----------------------------</td>
										</tr> 
										<tr>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\">HARYANTO</td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\">(Sie Farmasi)</td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\">(Ka. Bid. Farmasi)</td>
											<td style=\"text-align:center; border-top:none; border-bottom:none; border-left:none; border-right:none;\">(Keuangan)</td>
										</tr> 
									</table>";
			}
			$this->M_template_cetak->template($judul, $body, $position, $date, $cekpdf);
		} else {
			header('location:' . base_url());
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
