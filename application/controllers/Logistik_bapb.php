<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Logistik_bapb extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_logistik_bapb', 'M_logistik_bapb');
		$this->session->set_userdata('menuapp', '4000');
		$this->session->set_userdata('submenuapp', '4102');
		$this->load->helper('simkeu_rpt');
		$this->load->helper('simkeu_nota');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');

		if (!empty($cek)) {
			$q1 = "SELECT * from tbl_apohterimalog a, tbl_vendor b where a.vendor_id=b.vendor_id and a.koders='$unit' order by a.terima_date desc, a.terima_no desc";

			$bulan           = $this->M_global->_periodebulan();
			$nbulan          = $this->M_global->_namabulan($bulan);
			$periode         = 'Periode ' . $nbulan . '-' . $this->M_global->_periodetahun();
			$d['keu']        = $this->db->query($q1)->result();
			$d['periode']    = $periode;
			$level           = $this->session->userdata('level');
			$akses           = $this->M_global->cek_menu_akses($level, 4102);
			$d['akses']      = $akses;
			$this->load->view('pembelian/v_logistik_bapb', $d);
		} else {
			header('location:' . base_url());
		}
	}

	public function filter($param)
	{
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');

		if (!empty($cek)) {
			$data  = explode("~", $param);
			$jns   = $data[0];
			$tgl1  = $data[1];
			$tgl2  = $data[2];
			$_tgl1 = date('Y-m-d', strtotime($tgl1));
			$_tgl2 = date('Y-m-d', strtotime($tgl2));

			if (!empty($jns)) {
				// $q1 = 
				$q1 = "select * from tbl_apohterimalog a, tbl_vendor b where a.vendor_id=b.vendor_id and a.terima_date between '$_tgl1' and '$_tgl2' and a.koders = '$unit' order by a.terima_date, a.terima_no desc";
				$periode = 'Periode ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2));
				$d['keu'] = $this->db->query($q1)->result();
				$d['periode'] = $periode;
				$level = $this->session->userdata('level');
				$akses = $this->M_global->cek_menu_akses($level, 4102);
				$d['akses'] = $akses;
				$this->load->view('pembelian/v_logistik_bapb', $d);
			}
		} else {
			header('location:' . base_url());
		}
	}

	public function cetak($param)
	{
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');
		if (!empty($cek)) {
			$profile = $this->M_global->_LoadProfileLap();
			$unit = $this->session->userdata('unit');
			$nama_usaha = $profile->nama_usaha;
			$alamat1  = $profile->alamat1;
			$alamat2  = $profile->alamat2;
			$queryh = "select * from ap_lpb inner join ap_supplier on ap_lpb.kodesup=ap_supplier.kode where nolpb = '$param'";
			$queryd = "select * from ap_lpbdetil inner join inv_barang on ap_lpbdetil.kodeitem=inv_barang.kodeitem where ap_lpbdetil.nolpb = '$param'";
			$detil  = $this->db->query($queryd)->result();
			$header = $this->db->query($queryh)->row();
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
			$judul = array('Kepada :', '', 'Penerimaan Barang');
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


			$pdf->FancyRow(array($header->nama, '', 'Nomor', ':', $header->nolpb), $fc, $border);
			$pdf->FancyRow(array($header->alamat1, '', 'No. Faktur', ':', $header->kodepo), $fc, $border);
			$pdf->FancyRow(array($header->alamat2, '', 'Tanggal', ':', date('d-m-Y', strtotime($header->tglkirim))), $fc, $border);
			$fc     = array('0', '0', '0', '0', '0');
			$pdf->FancyRow(array($header->telp, '', '', '', ''), $fc, $border);
			$pdf->ln(2);

			$pdf->SetAligns(array('L', 'C', 'R'));
			//$pdf->SetFillColor(0,0,139);
			//$pdf->settextcolor(255,255,255);
			$fc = array('0', '0', '0', '0', '0', '0');

			$pdf->SetWidths(array(30, 105, 25, 30));
			$border = array('TB', 'TB', 'TB', 'TB');
			$align  = array('L', 'L', 'R', 'L');
			$pdf->setfont('Arial', 'B', 10);

			$judul = array('Kode Barang', 'Nama Barang', 'Qty', 'Satuan');
			$pdf->FancyRow2(8, $judul, $fc, $border, $align);
			$border = array('', '', '');
			$pdf->setfont('Arial', '', 10);
			$tot = 0;
			$subtot = 0;
			$tdisc  = 0;
			$border = array('', '', '', '');
			$align  = array('L', 'L', 'R', 'L');
			$fc = array('0', '0', '0', '0');
			$pdf->SetFillColor(0, 0, 139);
			$pdf->settextcolor(0);
			$tot = 0;
			$qty = 0;
			foreach ($detil as $db) {
				$qty = $qty + $db->qtyterima;
				$pdf->FancyRow(array(
					$db->kodeitem,
					$db->namabarang,
					$db->qtyterima,
					$db->satuan
				), $fc, $border, $align);
				$tot++;
			}
			$pdf->SetFillColor(230);
			$border = array('B', 'B', 'B', 'B');
			$pdf->FancyRow(array('', '', '', ''), 0, $border);
			$pdf->ln(2);
			$pdf->SetWidths(array(100, 20, 30, 40));
			$border = array('TB', '', 'T', 'T');
			$align  = array('L', '', 'L', 'R');
			$pdf->SetFillColor(230, 230, 230);
			$pdf->settextcolor(0);
			$fc = array('0', '0', '0', '0');
			$border = array('TB', '', 'T', 'T');
			$align  = array('L', '', 'L', 'R');
			$pdf->FancyRow(array('Keterangan', '', 'Total Kuantitas', $qty), $fc, $border, $align);
			$border = array('', '', 'B', 'B');
			$pdf->FancyRow(array($header->keterangan, '', 'Jumlah Barang', $tot), $fc, $border, $align);
			$border = array('B', '', '', '');
			$pdf->FancyRow(array('', '', '', ''), $fc, $border, $align);
			$pdf->SetWidths(array(50, 50, 50));
			$pdf->SetFont('Arial', '', 9);
			$pdf->SetAligns(array('C', 'C', 'C'));
			$pdf->ln(5);
			$border = array('', '', '');
			$align  = array('C', 'C', 'C');
			$fc = array('0', '0', '0');
			$pdf->FancyRow(array('Diterima Oleh,', 'Disetujui Oleh,', ''), $fc, $border, $align);
			$pdf->ln(1);
			$pdf->ln(15);
			$pdf->SetWidths(array(49, 2, 49, 2, 49));
			$pdf->SetFont('Arial', '', 8);
			$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C'));
			$border = array('B', '', 'B', '', '');
			$pdf->FancyRow(array('', '', '', '', ''), 0, $border, $align);
			$border = array('', '', '', '', '');
			$align  = array('L', 'C', 'L', 'C', 'L');
			$pdf->FancyRow(array('Tgl.', '', 'Tgl.', '', ''), 0, $border, $align);

			$pdf->AliasNbPages();
			$pdf->output($param . '.PDF', 'I');
		} else {
			header('location:' . base_url());
		}
	}

	public function cetak2($param)
	{
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');
		$user = $this->session->userdata('username');
		if (!empty($cek)) {
			$unit = $this->session->userdata('unit');
			$profile = data_master('tbl_namers', array('koders' => $unit));
			$nama_usaha = $profile->namars;
			$alamat1  = $profile->alamat;
			$alamat2  = $profile->kota;

			// $param = $this->input->get('id');
			$queryh = "SELECT * from tbl_apohterimalog inner join 
			tbl_vendor on tbl_apohterimalog.vendor_id=tbl_vendor.vendor_id 
			where tbl_apohterimalog.terima_no = '$param'";

			$queryd = "SELECT tbl_apodterimalog.*, tbl_logbarang.namabarang from tbl_apodterimalog inner join 
			tbl_logbarang on tbl_apodterimalog.kodebarang=tbl_logbarang.kodebarang
			where terima_no = '$param'";
			$detil  = $this->db->query($queryd)->result();
			$header = $this->db->query($queryh)->row();
			$pdf = new simkeu_nota();
			$pdf->setID($nama_usaha, $alamat1, $alamat2);
			$pdf->setjudul('');
			$pdf->setsubjudul('');
			$pdf->addpage("P", "A4");
			$pdf->setsize("P", "A4");

			$pdf->SetWidths(array(190));

			$pdf->setfont('Arial', 'B', 18);
			$pdf->SetAligns(array('C', 'C', 'C'));
			$border = array('BTLR');
			$size   = array('');
			$align = array('C');
			$style = array('B');
			$size  = array('18');
			$max   = array(20);
			$fc     = array('0');
			$hc     = array('20');
			$judul = array('SURAT PERNYATAAN & PEMBELIAN BARANG');
			$pdf->FancyRow2(10, $judul, $fc,  $border, $align, $style, $size, $max);
			$size  = array('10');
			$align = array('L');
			$border = array('');



			$pdf->ln(1);
			$pdf->setfont('Arial', 'B', 10);
			$pdf->SetWidths(array(20, 5, 80, 30, 5, 50));
			$border = array('LT', 'T', 'T', 'T', 'T', 'TR');
			$fc     = array('0', '0', '0', '0', '0', '0');
			$pdf->SetFillColor(230, 230, 230);
			$pdf->setfont('Arial', '', 9);


			$pdf->FancyRow(array('Terima dari', ':', $header->vendor_name, 'BAPB No.', ':', $header->terima_no), $fc, $border);
			$border = array('L', '', '', '', '', 'R');
			$pdf->FancyRow(array('', '', $header->alamat, 'Tgl Faktur', ':', date('d-m-Y', strtotime($header->terima_date))), $fc, $border);
			$pdf->FancyRow(array('', '', '', 'Tgl Penerimaan', ':', date('d-m-Y', strtotime($header->terima_date))), $fc, $border);
			$pdf->FancyRow(array('', '', '', 'No. Faktur', ':', $header->invoice_no), $fc, $border);
			$pdf->FancyRow(array('', '', '', 'No. Surat Jalan', ':', $header->sj_no), $fc, $border);
			$border = array('LB', 'B', 'B', 'B', 'B', 'BR');
			$pdf->FancyRow(array('', '', $header->phone, 'Gudang', ':', $header->gudang), $fc, $border);


			$pdf->ln(2);
			$pdf->SetWidths(array(10, 25, 40, 15, 15, 20, 20, 20, 25));
			$border = array('LTB', 'TB', 'TB', 'TB', 'TB', 'TB', 'TB', 'TB', 'TBR');
			$align  = array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C');
			$pdf->setfont('Arial', 'B', 9);
			$pdf->SetAligns(array('L', 'C', 'R'));
			$fc = array('0', '0', '0', '0', '0', '0', '0', '0', '0');
			$judul = array('No.', 'Kode Barang', 'Nama Barang', 'Qty', 'Satuan', 'HPP', 'Disc', 'Total', 'Po No');
			$pdf->FancyRow2(8, $judul, $fc, $border, $align);
			$pdf->setfont('Arial', '', 9);
			$tot = 0;
			$subtot = 0;
			$tdisc  = 0;
			$border = array('L', '', '', '', '', '', '', '', 'R');
			$align  = array('L', 'L', 'L', 'R', 'R', 'R', 'R', 'R', 'L');
			$style = array('', '', '', '', '', '', '', '', '');
			$size  = array('8', '8', '8', '8', '8', '8', '8', '8', '8');
			$max   = array(2, 2, 2, 2, 2, 2, 2, 2, 2);
			$fc     = array('0', '0', '0', '0', '0', '0', '0', '0', '0');
			$no = 1;
			$totitem = 0;
			$tot = 0;
			$diskon = 0;
			$ppnrp = 0;
			foreach ($detil as $db) {
				$hpp = data_master('tbl_logbarang', array('kodebarang' => $db->kodebarang))->hpp;
				$tot += $db->price * $db->qty_terima;
				$ppnrp += $db->vatrp;
				if ($db->discount == '') {
					$diskon += 0;
				} else {
					$diskon += $db->discountrp;
				}
				$pdf->FancyRow(array(

					$no,
					$db->kodebarang,
					$db->namabarang,
					$db->qty_terima,
					$db->satuan,
					number_format($db->price, 2, ',', '.'),
					number_format($db->discountrp, 2),
					number_format($db->totalrp, 2, ',', '.'),
					$db->po_no
				), $fc,  $border, $align, $style, $size, $max);
				$no++;
			}
			$discount = $header->diskontotal;
			$ppn = $header->vatrp;
			$materai = $header->materai;
			$totalnet = (int)$tot - (int)$discount + $ppnrp + (int)$materai;
			$pdf->SetWidths(array(10, 25, 40, 15, 15, 20, 20, 20, 25));
			$pdf->SetWidths(array(4, 40, 30, 50, 20, 20, 25));
			$border = array('T', 'T', 'T', 'T', 'T', 'T', 'T');
			$align  = array('L', 'C', 'C', 'C', 'R', 'R');
			$style = array('', 'B', '', '', '', '');
			$judul = array('', '', '', '', 'TOTAL', number_format($tot, 2, ',', '.'), '');
			$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
			$judul = array('', 'Form Rangkap 2', '', '', 'Discount', number_format($diskon, 2, ',', '.'));
			$border = array('', 'LTR', '', '', '', '');
			$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
			$border = array('T', 'LTR', 'T', 'T', 'T', 'T');
			$judul = array('', 'Merah : untuk supplier', '', '', 'PPN', number_format($ppnrp, 2, ',', '.'));
			$border = array('', 'LR', '', '', '', '');
			$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
			$judul = array('', 'Putih : untuk keuangan', '', '', 'Materai', number_format($materai, 2, ',', '.'));
			//$border = array('','LRB','','','');
			$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
			$judul = array('', '', '', '', 'Total Net', number_format($totalnet, 2, ',', '.'), '');
			$border = array('', 'T', '', '', 'B', 'B', 'B',);
			$style = array('', 'B', '', '', 'B', 'B', '');
			$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);

			$pdf->SetWidths(array(63.3, 63.3, 63.3));
			$border = array('TBLR', 'TBLR', 'TBLR');
			$align  = array('C', 'C', 'C');
			$style = array('', '', '');
			$align  = array('C', 'C', 'C');
			$border = array('', '', '');
			$judul = array('', '', $alamat2 . ', ' . date('d-m-Y'));
			$pdf->ln();
			$pdf->FancyRow2(3, $judul, $fc,  $border, $align, $style, $size, $max);
			$align  = array('C', 'C', 'C');
			$border = array('TBLR', 'TBLR', 'TBLR');
			$judul = array('Diketahui oleh,', 'Diterima Oleh,', 'Dibuat Oleh,');
			$pdf->ln();
			$pdf->FancyRow2(5, $judul, $fc,  $border, $align, $style, $size, $max);
			$judul = array('', '', '');
			$pdf->FancyRow2(20, $judul, $fc,  $border, $align, $style, $size, $max);
			$judul = array('', '', '');
			$pdf->FancyRow2(5, $judul, $fc,  $border, $align, $style, $size, $max);
			$judul = array('KEPALA APOTEKTER', $header->vendor_name, 'PENANGGUNG JAWAB ADM');
			$pdf->FancyRow2(5, $judul, $fc,  $border, $align, $style, $size, $max);

			$pdf->setTitle($param);
			$pdf->AliasNbPages();
			$pdf->output($param . '.PDF', 'I');
		} else {
			header('location:' . base_url());
		}
	}

	public function getpo($po)
	{
		// $this->db->select('tbl_apodpolog.*, tbl_logbarang.namabarang, tbl_apodpolog.qty_po-tbl_apodpolog.qty_terima as sisa', false);
		// $this->db->from('tbl_apodpolog');
		// $this->db->join('tbl_logbarang','tbl_logbarang.kodebarang=tbl_apodpolog.kodebarang','left');
		// $this->db->where('tbl_apodpolog.po_no',$po);
		// $this->db->where('tbl_apodpolog.qty_po > tbl_apodpolog.qty_terima');	

		// $data = $this->db->get()->result();	

		$data = $this->db->query("SELECT tbl_apodpolog.*, tbl_logbarang.namabarang, tbl_apodpolog.qty_po-tbl_apodpolog.qty_terima as sisa 
		FROM tbl_apodpolog
		LEFT JOIN tbl_logbarang ON tbl_logbarang.kodebarang=tbl_apodpolog.kodebarang 
		WHERE tbl_apodpolog.po_no = '$po' AND tbl_apodpolog.qty_po > tbl_apodpolog.qty_terima")->result();

		echo json_encode($data);
	}

	public function gethpo($po)
	{
		$data = $this->db->query("SELECT *,(select keterangan from tbl_depo b where a.gudang=b.depocode )gud_name FROM tbl_apohpolog a WHERE a.po_no = '$po'")->row();
		echo json_encode($data);
	}

	public function cekppn()
	{
		$query_ppn = $this->db->query("SELECT * FROM tbl_pajak where kodetax='PPN'");
		if ($query_ppn->num_rows() == 0) {
			echo json_encode(array("status" => 0));
		} else {
			echo json_encode($query_ppn->row());
		}
	}

	public function getlistpo($supp)
	{
		if (!empty($supp)) {
			$po  = $this->db->get_where('tbl_apohpolog', array('vendor_id' => $supp, 'closed' => 0))->result();
?>
			<select name="kodepo" id="kodepo" class="form-control  input-medium select2me">
				<option value="">-- Tanpa PO ---</option>
				<?php
				foreach ($po  as $row) {
				?>
					<option value="<?php echo $row->po_no; ?>"><?php echo $row->po_no; ?></option>

				<?php } ?>
			</select>

<?php

		} else {
			echo "";
		}
	}

	public function entri()
	{
		$cek = $this->session->userdata('level');
		$uid = $this->session->userdata('unit');
		if (!empty($cek)) {
			$level = $this->session->userdata('level');
			$akses = $this->M_global->cek_menu_akses($level, 3102);
			$this->load->helper('url');
			$data['modul']    = 'LOGISTIK';
			$data['submodul'] = 'BAPB';
			$data['link']     = 'BA Penerimaan Barang';
			$data['url']      = 'logistik_bapb';
			$data['tanggal']  = date('d-m-Y');
			$data['akses']    = $akses;
			$data['nomorpo']  = urut_transaksi('URUT_BHP', 19);
			$data['ppn'] = $this->db->get_where('tbl_pajak', ['kodetax' => 'PPN'])->row_array();
			$this->load->view('pembelian/v_logistik_bapb_add', $data);

			//   $page=$this->uri->segment(3);
			//   $limit=$this->config->item('limit_data');	
			//   $d['nomor']= urut_transaksi('URUT_BHP', 19);		  
			//   $this->load->view('pembelian/v_logistik_bapb_add',$d);				
		} else {

			header('location:' . base_url());
		}
	}

	public function hapus()
	{
		$cabang   = $this->session->userdata('unit');
		$id       = $this->input->post('id');
		$terima_no = $this->input->get('terima_no');
		$data     = $this->db->get_where('tbl_apohterimalog', array('terima_no' => $terima_no))->row();
		$nomor    = $data->terima_no;
		$gudang   = $data->gudang;
		$cek      = $this->session->userdata('level');
		if (!empty($cek)) {
			$databeli = $this->db->get_where('tbl_apodterimalog', array('terima_no' => $nomor))->result();
			foreach ($databeli as $row) {
				$_qty  = $row->qty_terima;
				$_kode = $row->kodebarang;
				$this->db->query("UPDATE tbl_apostocklog set terima=terima- $_qty, saldoakhir= saldoakhir- $_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang'");
			}
			$this->db->delete('tbl_apohterimalog', array('terima_no' => $terima_no));
			$this->db->delete('tbl_apodterimalog', array('terima_no' => $terima_no, 'koders' => $cabang));
			$this->db->query("DELETE from tbl_apoap where terima_no = '$terima_no'");
			// $this->db->delete('tr_jurnal',array('' => $nomor));
			echo json_encode(array("status" => 1));
		} else {
			header('location:' . base_url());
		}

		// $cabang   = $this->session->userdata('unit');
		// $cek      = $this->session->userdata('level');
		// $nomor    = $this->input->post('id');
		// if(!empty($cek))
		// {			
		//   if($nomor!=""){				
		// 		   $datalpb = $this->db->query("SELECT (SELECT gudang from tbl_apohterimalog b where b.terima_no=a.terima_no)gudang,a.* FROM tbl_apodterimalog a WHERE terima_no = '$nomor' ")->result();

		// 		   foreach($datalpb as $row){	
		// 			$gudangg    = $row->gudang;
		// 			$_po_no    = $row->po_no;
		// 			$_kode     = $row->kodebarang;
		// 			$_qty      = $row->qty_terima;

		// 			 $this->db->query("UPDATE tbl_apodpolog set qty_terima = qty_terima - $_qty where kodebarang = '$_kode' and po_no ='$_po_no' ");	


		// 			 $this->db->query("UPDATE tbl_apostocklog set terima=terima- $_qty, saldoakhir= saldoakhir- $_qty where kodebarang = '$_kode'
		// 			and koders = '$cabang' and gudang = '$gudangg'");
		// 		   }				   				   				   		     	
		// 	}

		//    $this->db->delete('tbl_apohterimalog',array('terima_no' => $nomor));
		//    $this->db->delete('tbl_apodterimalog',array('terima_no' => $nomor));
		//    $this->db->delete('tbl_baranghterima',array('terima_no' => $nomor));
		//    //$this->db->delete('inv_transaksi',array('nobukti' => $nomor));
		//    //$this->db->delete('tr_jurnal',array('' => $nomor));	
		//    echo json_encode(array("status" => 1));	   		   

		// }else{

		// 	header('location:'.base_url());

		// }
	}

	function cekharga()
	{
		$kode = $this->input->get('kode');
		$harga = $this->input->get('harga');
		$cek = $this->db->query('select * from tbl_logbarang where kodebarang = "' . $kode . '"')->row_array();
		if ($harga < $cek['hargabeli']) {
			$data = [
				'harga' => $cek['hargabeli'],
				'status' => 1
			];
			echo json_encode($data);
		} else {
			$data = [
				'harga' => $harga,
				'status' => 2
			];
			echo json_encode($data);
		}
	}

	function ubahharga()
	{
		$kode = $this->input->get('kode');
		$harga = $this->input->get('harga');
		$cek = $this->db->query('select * from tbl_logbarang where kodebarang = "' . $kode . '"')->row_array();
		$data = [
			'harga' => $harga,
			'status' => 1
		];
		echo json_encode($data);
	}


	public function getinfobarang()
	{

		$kode = $this->input->get('kode');
		$data = $this->M_global->_data_barang_log($kode);
		echo json_encode($data);
	}

	public function cekhari()
	{
		$kode = $this->input->get('kode');
		$data = $this->db->select('tbl_setinghms.valuerp');
		$data = $this->db->get_where('tbl_setinghms', array('kodeset' => $kode))->row();
		echo json_encode($data);
	}

	public function getbarangname($kode)
	{
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

	public function ajax_list($param)
	{

		$level = $this->session->userdata('level');
		$akses = $this->M_global->cek_menu_akses($level, 4102);
		$dat   = explode("~", $param);
		if ($dat[0] == 1) {
			$bulan       = date('m');
			$tahun       = date('Y');
			$list = $this->M_logistik_bapb->get_datatables(1, $bulan, $tahun);
		} else {
			$bulan       = date('Y-m-d', strtotime($dat[1]));
			$tahun       = date('Y-m-d', strtotime($dat[2]));
			$list = $this->M_logistik_bapb->get_datatables(2, $bulan, $tahun);
		}


		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit) {

			$no++;
			$row = array();
			$row[] = $unit->koders;
			$row[] = $unit->userid;
			$row[] = $unit->terima_no;
			$row[] = $unit->terima_date;
			$row[] = $unit->vendor_name;
			$row[] = $unit->sj_no;
			$row[] = $unit->gudang;
			$row[] = $unit->terima_no;
			if ($akses->uedit == 1 && $akses->udel == 1) {
				$row[] = '
				<a class="btn btn-sm btn-primary" href="' . base_url("logistik_bapb/edit/" . $unit->terima_no . "") . '" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a>
				<a target="_blank" class="btn btn-sm btn-warning" href="' . base_url("logistik_bapb/cetak2/" . $unit->terima_no . "") . '" title="Edit" ><i class="glyphicon glyphicon-print"></i> </a>
				<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data(' . "'" . $unit->terima_no . "'" . ')"><i class="glyphicon glyphicon-trash"></i> </a>';
			} else if ($akses->uedit == 1 && $akses->udel == 0) {
				$row[] = '<a class="btn btn-sm btn-primary" href="' . base_url("logistik_bapb/edit/" . $unit->id . "") . '" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a> ';
			} else if ($akses->uedit == 0 && $akses->udel == 1) {
				$row[] = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data(' . "'" . $unit->terima_no . "'" . ')"><i class="glyphicon glyphicon-trash"></i> </a>';
			} else {
				$row[] = '';
			}
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->M_logistik_bapb->count_all($dat[0], $bulan, $tahun),
			"recordsFiltered" => $this->M_logistik_bapb->count_filtered($dat[0], $bulan, $tahun),
			"data" => $data,

		);
		//output to json format
		echo json_encode($output);
	}

	public function cekbarangori()
	{
		$kodebarang = $this->input->get('kode');
		$harga = $this->input->get('harga');
		$cek = $this->db->get_where('tbl_logbarang', ['kodebarang' => $kodebarang])->row_array();
		echo json_encode($cek);
	}

	public function save_one()
	{
		$cek        = $this->session->userdata('level');
		$cabang     = $this->session->userdata('unit');
		$userid     = $this->session->userdata('username');
		$nosj    = $this->input->post('nomorsj');
		$gudang     = $this->input->post('gudang');
		$nomorpo    = $this->input->post('nomorpo');
		$faktur     = $this->input->post('nofaktur');
		$nobukti  = urut_transaksi('URUT_BHP', 19);
		if ($this->input->post('pembayaran') == 'CASH') {
			$jenisbeli = 0;
		} else {
			$jenisbeli = 1;
		}
		$qcek = $this->db->query("SELECT * FROM tbl_apoap WHERE invoice_no = '$faktur' and koders='$cabang'")->result_array();
		$cek = count($qcek);
		if ($cek > 0) {
			echo json_encode(array("status" => "1", "nomor" => $nobukti));
		} else {
			$qcek1 = $this->db->query("SELECT * FROM tbl_apohterimalog WHERE invoice_no = '$faktur' and koders='$cabang' ")->result_array();
			$cek1 = count($qcek1);
			if ($cek1 > 0) {
				echo json_encode(array("status" => "1", "nomor" => $nobukti));
			} else {
				$data = array(
					'koders'      => $cabang,
					'terima_no'   => $nobukti,
					'terima_date' => $this->input->post('tanggal'),
					'due_date'    => $this->input->post('jatuhtempo'),
					'tgltukar'    => $this->input->post('tanggaltukar'),
					'vendor_id'   => $this->input->post('supp'),
					'invoice_no'  => $this->input->post('nofaktur'),
					'sj_no'       => $this->input->post('nomorsj'),
					'term'        => $this->input->post('pembayaran'),
					'gudang'      => $this->input->post('gudang'),
					'diskontotal' => $this->input->post('diskonrp'),
					'ppn'         => '',
					'vatrp'       => 0,
					'jenisbeli'   => $jenisbeli,
					'materai'     => $this->input->post('materai'),
					'ongkir'      => $this->input->post('ongkir'),
					'bkemasan'    => $this->input->post('kemasan'),
					'kurs'        => $this->input->post('kurs'),
					'kursrate'    => $this->input->post('rate'),
					'userid'      => $userid,
					'vatrp'      => 0,
				);
				$this->db->insert('tbl_apohterimalog', $data);
			}
			$ppn = $this->db->get_where('tbl_pajak', ['kodetax' => 'PPN'])->row_array();
			$data_ap = array(
				'koders'        => $cabang,
				'terima_no'     => $nobukti,
				'invoice_no'    => $this->input->post('nofaktur'),
				'tglinvoice'    => $this->input->post('tanggal'),
				'duedate'       => $this->input->post('jatuhtempo'),
				'vendor_id'     => $this->input->post('supp'),
				'totaltagihan'  => 0,
				'totalbayar'    => 0,
				'username'      => $userid,
				'term'          => $this->input->post('pembayaran'),
				'ppn'		 => $ppn['prosentase'],
			);
			$this->db->insert('tbl_apoap', $data_ap);
			echo json_encode(['status' => 2, 'nomor' => $nobukti]);
		}
	}

	function save_multi()
	{
		$cabang = $this->session->userdata('unit');
		$gudang   = $this->input->post('gudang');
		$kode = $this->input->get('kode');
		$qty = $this->input->get('qty');
		$sat = $this->input->get('sat');
		$harga = $this->input->get('harga');
		$disc = $this->input->get('disc');
		$discrp = $this->input->get('discrp');
		$vat = $this->input->get('vat');
		$jumlah = $this->input->get('jumlah');
		$expire = $this->input->get('expire');
		$po = $this->input->get('po');
		$vatrp = $this->input->get('vatrp');
		$po_no  = $this->input->post('nomorpo');
		if ($harga != '' && $kode != '') {
			$ppn = $this->db->get_where('tbl_pajak', ['kodetax' => 'PPN'])->row_array();
			$cekq = $this->db->query('select * from tbl_logbarang where kodebarang = "' . $kode . '"')->result();
			foreach ($cekq as $cq) {
				if ($cq->vat == 1) {
					$hargabarang = $harga;
					$hargappn = $harga * $ppn['prosentase'] / 100 + $harga;
				} else {
					$hargabarang = $harga;
					$hargappn = $harga;
				}
				$this->db->set('hargabeli', $hargabarang);
				$this->db->set('hargabelippn', $hargappn);
				$this->db->where('kodebarang', $kode);
				$this->db->update('tbl_logbarang');
			}
			$sql = $this->db->query('select terima_no from tbl_apohterimalog where koders = "' . $cabang . '" and gudang = "' . $gudang . '" order by id desc limit 1')->result();
			foreach ($sql as $s) {
				if (count($s->terima_no) == 1) {
					if ($po_no == '') {
						$pox = '';
					} else {
						$pox = $po_no;
					}
					$data = [
						'koders' => $cabang,
						'terima_no' => $s->terima_no,
						'kodebarang' => $kode,
						'qty_terima' => $qty,
						'satuan' => $sat,
						'price' => $harga,
						'discount' => $disc,
						'discountrp' => $discrp,
						'vat' => $vat,
						'vatrp' => $vatrp,
						'totalrp' => $jumlah,
						'po_no' => $pox,
						'exp_date' => date('Y-m-d H:i:s', strtotime($expire)),
					];
					$this->db->insert('tbl_apodterimalog', $data);
					$stokcek = $this->db->query("SELECT * FROM tbl_apostocklog WHERE kodebarang = '$kode' and koders='$cabang' and gudang='$gudang' ");
					if ($stokcek->num_rows() > 0) {
						$stok = $stokcek->result();
						foreach ($stok as $key => $value) {
							$terima = (int)$value->terima + $qty;
							$saldoakhir = (int)$value->saldoakhir + $qty;
						}
						$this->db->query("UPDATE tbl_apostocklog set terima=$terima, saldoakhir=$saldoakhir where kodebarang='$kode' and koders='$cabang' and gudang='$gudang'");
						if ($po_no != "") {
							$this->db->query("UPDATE tbl_apodpolog set qty_terima = qty_terima + $qty WHERE kodebarang = '$kode' and po_no = '$nomorpo' and koders='$cabang'");
						}
					} else {
						$datastock = array(
							'koders'       => $cabang,
							'kodebarang'   => $kode,
							'gudang'       => $gudang,
							'saldoawal'    => 0,
							'terima'       => $qty,
							'saldoakhir'   => $qty,
							'tglso'        => $this->input->post('tanggal'),
							'periodedate'  => $this->input->post('tanggal'),
						);
						$this->db->insert('tbl_apostocklog', $datastock);
					}
				}
			}
		}
	}

	public function save_one_u()
	{
		$totvatrp = $this->input->get('totvatrp');
		$totaltagihan = $this->input->get('totaltagihan');
		$diskontotal = $this->input->get('diskontotal');
		$ppnrp = $this->input->get('ppnrp');
		$cabang = $this->session->userdata('unit');
		$gudang   = $this->input->post('gudang');
		$sql = $this->db->query('select * from tbl_apohterimalog where koders = "' . $cabang . '" and gudang = "' . $gudang . '" order by id desc limit 1')->result();
		foreach ($sql as $s) {
			if ($sql != null) {
				$this->db->set('vatrp', $totvatrp);
				$this->db->set('diskontotal', $diskontotal);
				$this->db->where('koders', $cabang);
				$this->db->where('terima_no', $s->terima_no);
				$this->db->update('tbl_apohterimalog');

				$this->db->set('totaltagihan', $totaltagihan);
				$this->db->set('ppnrp', $ppnrp);
				$this->db->where('koders', $cabang);
				$this->db->where('terima_no', $s->terima_no);
				$this->db->update('tbl_apoap');
			}
		}
	}

	public function update_one()
	{
		$cabang   = $this->session->userdata('unit');
		$userid   = $this->session->userdata('username');
		$gudang   = $this->input->post('gudang');
		$faktur   = $this->input->post('nofaktur');
		$terimano   = $this->input->get('terimano');
		$tanggal  = date('Y-m-d');
		$jam      = date('H:i:s');
		$nomorpo  = $this->input->post('nomorpo');
		if ($this->input->post('pembayaran') == 'CASH') {
			$jenisbeli = 0;
		} else {
			$jenisbeli = 1;
		}
		$ppn = $this->db->get_where('tbl_pajak', ['kodetax' => 'PPN'])->row_array();
		$this->db->set('terima_date', $this->input->post('tanggal'));
		$this->db->set('due_date', $this->input->post('jatuhtempo'));
		$this->db->set('tgltukar', $this->input->post('tanggaltukar'));
		$this->db->set('vendor_id', $this->input->post('supp'));
		$this->db->set('sj_no', $this->input->post('nomorsj'));
		$this->db->set('invoice_no', $this->input->post('nofaktur'));
		$this->db->set('gudang', $this->input->post('gudang'));
		$this->db->set('kurs', $this->input->post('kurs'));
		$this->db->set('kursrate', $this->input->post('rate'));
		$this->db->set('materai', $this->input->post('materai'));
		$this->db->set('ongkir', $this->input->post('ongkir'));
		$this->db->set('bkemasan', $this->input->post('kemasan'));
		$this->db->set('diskontotal', $this->input->post('diskonrp'));
		$this->db->set('term', $this->input->post('pembayaran'));
		$this->db->set('jenisbeli', $jenisbeli);
		$this->db->set('userid', $userid);
		$this->db->set('ppn', $ppn['prosentase']);
		$this->db->where('koders', $cabang);
		$this->db->where('terima_no', $terimano);
		$this->db->update('tbl_apohterimalog');

		$dterima = $this->db->get_where('tbl_apodterimalog', ['terima_no' => $terimano])->result();
		foreach ($dterima as $row) {
			$_qty  = $row->qty_terima;
			$_kode = $row->kodebarang;
			$this->db->query("UPDATE tbl_apostocklog set terima = terima - $_qty, saldoakhir = saldoakhir - $_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang'");
		}
		$this->db->query("DELETE from tbl_apodterimalog where terima_no = '$terimano'");
		$this->db->query("DELETE from tbl_apoap where terima_no = '$terimano'");
		$this->db->query("UPDATE tbl_apohterimalog set vatrp = '0' where terima_no='$terimano'");
		$data_ap = array(
			'koders'        => $cabang,
			'terima_no'     => $terimano,
			'invoice_no'    => $this->input->post('nofaktur'),
			'tglinvoice'    => $this->input->post('tanggal'),
			'duedate'       => $this->input->post('jatuhtempo'),
			'materai'       => $this->input->post('materai'),
			'vendor_id'     => $this->input->post('supp'),
			'totaltagihan'  => 0,
			'totalbayar'    => 0,
			'ppn'    => $ppn['prosentase'],
			'username'      => $userid,
			'term'          => $this->input->post('pembayaran'),
		);
		$this->db->insert('tbl_apoap', $data_ap);
		echo json_encode(["status" => 2, "nomor" => $terimano]);
	}

	function update_multi()
	{
		$cabang = $this->session->userdata('unit');
		$gudang   = $this->input->post('gudang');
		$kode = $this->input->get('kode');
		$qty = $this->input->get('qty');
		$sat = $this->input->get('sat');
		$harga = $this->input->get('harga');
		$disc = $this->input->get('disc');
		$discrp = $this->input->get('discrp');
		$vat = $this->input->get('vat');
		$jumlah = $this->input->get('jumlah');
		$expire = $this->input->get('expire');
		$po = $this->input->get('po');
		$vatrp = $this->input->get('vatrp');
		$po_no  = $this->input->post('nomorpo');
		$terimano   = $this->input->get('terimano');

		if ($harga != '' && $kode != '') {
			$ppn = $this->db->get_where('tbl_pajak', ['kodetax' => 'PPN'])->row_array();
			$cekq = $this->db->query('select * from tbl_logbarang where kodebarang = "' . $kode . '"')->result();
			foreach ($cekq as $cq) {
				if ($cq->vat == 1) {
					$hargabarang = $harga;
					$hargappn = $harga * $ppn['prosentase'] / 100 + $harga;
				} else {
					$hargabarang = $harga;
					$hargappn = $harga;
				}
				$this->db->set('hargabeli', $hargabarang);
				$this->db->set('hargabelippn', $hargappn);
				$this->db->where('kodebarang', $kode);
				$this->db->update('tbl_logbarang');
			}
			$data = [
				'koders' => $cabang,
				'terima_no' => $terimano,
				'kodebarang' => $kode,
				'qty_terima' => $qty,
				'satuan' => $sat,
				'price' => $harga,
				'discount' => $disc,
				'discountrp' => $discrp,
				'vat' => $vat,
				'vatrp' => $vatrp,
				'totalrp' => $jumlah,
				'exp_date' => date('Y-m-d H:i:s', strtotime($expire)),
				'po_no' => $po_no,
			];
			$this->db->insert('tbl_apodterimalog', $data);
			$stokcek = $this->db->query("SELECT * FROM tbl_apostocklog WHERE kodebarang = '$kode' and koders='$cabang' and gudang='$gudang' ")->result_array();
			$scek = count($stokcek);
			if ($scek > 0) {
				$this->db->query("UPDATE tbl_apostocklog set terima = terima+ $qty, saldoakhir = saldoakhir + $qty where kodebarang = '$kode' and koders = '$cabang' and gudang = '$gudang'");
			} else {
				$datastock = array(
					'koders'       => $cabang,
					'kodebarang'   => $kode,
					'gudang'       => $gudang,
					'saldoawal'    => 0,
					'terima'       => $qty,
					'saldoakhir'   => $qty,
					'tglso'        => $this->input->post('tanggal'),
					'periodedate'       => $this->input->post('tanggal'),
				);
				$this->db->insert('tbl_apostocklog', $datastock);
			}
		}
	}

	public function update_one_u()
	{
		$totvatrp = $this->input->get('totvatrp');
		$totaltagihan = $this->input->get('totaltagihan');
		$diskontotal = $this->input->get('diskontotal');
		$ppnrp = $this->input->get('ppnrp');
		$cabang = $this->session->userdata('unit');
		$gudang   = $this->input->post('gudang');
		$terimano   = $this->input->get('terimano');
		$ppn = $this->db->get_where('tbl_pajak', ['kodetax' => 'PPN'])->row_array();
		$this->db->set('vatrp', $totvatrp);
		$this->db->set('diskontotal', $diskontotal);
		$this->db->where('koders', $cabang);
		$this->db->where('terima_no', $terimano);
		$this->db->update('tbl_apohterimalog');

		$this->db->set('totaltagihan', $totaltagihan);
		$this->db->set('ppnrp', $ppnrp);
		$this->db->where('koders', $cabang);
		$this->db->where('terima_no', $terimano);
		$this->db->update('tbl_apoap');
	}

	public function save($param)
	{
		$cek        = $this->session->userdata('level');
		$cabang     = $this->session->userdata('unit');
		$userid     = $this->session->userdata('username');

		$nobukti    = $this->input->post('nomorsj');

		$gudang     = $this->input->post('gudang');
		$nomorpo    = $this->input->post('nomorpo');
		$faktur     = $this->input->post('nofaktur');
		$hargaxx     = $this->input->get('harga');
		$kodexx     = $this->input->get('kode');
		// $qty     = $this->input->get('qty');
		// $sat     = $this->input->get('sat');
		// $disc     = $this->input->get('disc');
		// $po     = $this->input->get('po');
		// $expire     = $this->input->get('expire');
		if (!empty($cek)) {
			$cek = $this->db->query('select * from tbl_logbarang where kodebarang = "' . $kodexx . '"')->result();
			foreach ($cek as $c) {
				if ($c->notax == 1) {
					$hargabarang = $hargaxx;
					$hargappn = $hargaxx * 11 / 100 + $hargaxx;
				} else {
					$hargabarang = $hargaxx;
					$hargappn = $hargaxx;
				}
				$this->db->set('hargabeli', $hargabarang);
				$this->db->set('hargabelippn', $hargappn);
				$this->db->where('kodebarang', $kodexx);
				$this->db->update('tbl_logbarang');
			}
			if ($param == 1) {
				$nobukti  = urut_transaksi('URUT_BHP', 19);
			} else {
				$nobukti = $this->input->post('noterima');
			}

			if ($nomorpo != "") {
				if ($param == 2) {
				}
			}
			if ($this->input->post('pembayaran') == 'CASH') {
				$jenisbeli = 0;	//tunai
			} else {
				$jenisbeli = 1;	//kredit	
			}
			$qcek = $this->db->query("SELECT * FROM tbl_apohterimalog WHERE invoice_no = '$faktur' and koders='$cabang' ")->result_array();
			$cek = count($qcek);
			if ($cek > 0) {
				echo json_encode(array("status" => "1", "nomor" => $nobukti));
			} else {
				$data = array(
					'koders'      => $cabang,
					'terima_no'   => $nobukti,
					'terima_date' => date('Y-m-d', strtotime($this->input->post('tanggal'))),
					'terima_date' => $this->input->post('tanggal'),
					'due_date'    => $this->input->post('jatuhtempo'),
					'tgltukar'    => $this->input->post('tanggaltukar'),
					'vendor_id'   => $this->input->post('supp'),
					'sj_no'       => $this->input->post('nomorsj'),
					'invoice_no'  => $this->input->post('nofaktur'),
					'gudang'      => $this->input->post('gudang'),
					'kurs'        => $this->input->post('kurs'),
					'kursrate'    => $this->input->post('rate'),
					'materai'     => $this->input->post('materai'),
					'ongkir'      => $this->input->post('ongkir'),
					'bkemasan'    => $this->input->post('kemasan'),
					'diskontotal' => $this->input->post('diskonrp'),
					'term'        => $this->input->post('pembayaran'),
					'jenisbeli'   => $jenisbeli,
					'userid'      => $userid,
					'ppn'         => '',
					'vatrp'       => 0,
					//'due_date'=> date('Y-m-d',strtotime($this->input->post('tanggalkirim'))),				
				);
				if ($param == 1) {
					// $this->db->insert('tbl_apohterimalog',$data);		
					echo "cek data";
				} else {
					$this->db->update('tbl_apohterimalog', $data, array('terima_no' => $nobukti));
					$datalpb = $this->db->get_where('tbl_apodterimalog', array('terima_no' => $nobukti))->result();
					foreach ($datalpb as $row) {
						$_kode = $row->kodebarang;
						$_qty  = $row->qty_terima;
						$this->db->query("UPDATE tbl_apostocklog set terima=terima- $_qty, saldoakhir= saldoakhir- $_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang'");
					}
					$this->db->query("DELETE from tbl_apodterimalog where terima_no = '$nobukti'");
					$this->db->query("DELETE from tbl_apoap where terima_no = '$nobukti'");
				}
				// $this->db->delete('tbl_apodterimalog',array('terima_no' => $nobukti));
				$kode   = $this->input->post('kode');
				$qty    = $this->input->post('qty');
				$sat    = $this->input->post('sat');
				$harga  = $this->input->post('harga');
				$disc   = $this->input->post('disc');
				$discrp = $this->input->post('discrp');
				$jumlah = $this->input->post('jumlah');
				$expire = $this->input->post('expire');
				$po     = $this->input->post('po');
				$tax    = $this->input->post('tax');
				$vatrp    = $this->input->post('vatrp');
				$jumdata  = count($kode);
				$nourut    = 1;
				$total     = 0;
				$totdis    = 0;
				$tot       = 0;
				$totppn    = 0;
				$totvatrp  = 0;
				for ($i = 0; $i <= $jumdata - 1; $i++) {
					$_harga    = str_replace(',', '', $harga[$i]);
					$_disc     = str_replace(',', '', $disc[$i]);
					$_jumlah   = str_replace(',', '', $jumlah[$i]);
					$_discrp   = str_replace(',', '', $discrp[$i]);
					$_vatrp    = $vatrp[$i];
					$_qty      = $qty[$i];
					$_kode     = $kode[$i];
					$diskon    = $_jumlah * ($_disc / 100);
					$totdis    += $diskon;
					$tot       += $_jumlah;
					// echo $tax[$i];
					if ($tax[$i] == "") {
						$_vat = 0;
					} else {
						$_vat = 1;
						$totppn += $_jumlah * (11 / 100);
					}
					$hpp = $this->db->get_where('tbl_logbarang', array('kodebarang' => $_kode))->row()->hpp;
					$total    += $hpp * $qty[$i];
					$totvatrp += $_vatrp;

					$datad = array(
						'koders'     => $cabang,
						'terima_no'  => $nobukti,
						'po_no'      => $nomorpo,
						'kodebarang' => $kode[$i],
						'qty_terima' => $qty[$i],
						'price'      => $_harga,
						'satuan'     => $sat[$i],
						'discount'   => $disc[$i],
						'discountrp' => $_discrp,
						'vat'        => $_vat,
						'vatrp'      => $_vatrp,
						'po_no'      => $po[$i],
						'exp_date'   => date('Y-m-d', strtotime($expire[$i])),
						'totalrp'    => $_jumlah,
					);
					if ($_kode != "") {
						$this->db->insert('tbl_apodterimalog', $datad);

						$this->db->query("UPDATE tbl_apostocklog set terima=terima+ $_qty, saldoakhir= saldoakhir+ $_qty where kodebarang = '$_kode'
						and koders = '$cabang' and gudang = '$gudang'");

						if ($nomorpo != "") {
							$this->db->query("UPDATE tbl_apodpolog set qty_terima = qty_terima + $_qty WHERE kodebarang = '$_kode' and po_no = '$nomorpo' ");
						}
					}
				}
				$this->db->query("UPDATE tbl_apohterimalog set vatrp='$totvatrp' where terima_no = '$nobukti' and koders = '$cabang'");
				echo json_encode(array("status" => "2", "nomor" => $nobukti));
			}
		} else {
			header('location:' . base_url());
		}
	}

	public function ajax_add($param)
	{
		$cek        = $this->session->userdata('level');
		$cabang     = $this->session->userdata('unit');
		$userid     = $this->session->userdata('username');

		$nobukti    = $this->input->post('nomorsj');
		$gudang     = $this->input->post('gudang');
		$nomorpo    = $this->input->post('nomorpo');
		$faktur     = $this->input->post('nofaktur');
		$hargaxx     = $this->input->get('harga');
		$kodexx     = $this->input->get('kode');

		if (!empty($cek)) {
			$cek = $this->db->query('select * from tbl_logbarang where kodebarang = "' . $kodexx . '"')->result();
			foreach ($cek as $c) {
				if ($c->notax == 1) {
					$hargabarang = $hargaxx;
					$hargappn = $hargaxx * 11 / 100 + $hargaxx;
				} else {
					$hargabarang = $hargaxx;
					$hargappn = $hargaxx;
				}
				$this->db->set('hargabeli', $hargabarang);
				$this->db->set('hargabelippn', $hargappn);
				$this->db->where('kodebarang', $kodexx);
				$this->db->update('tbl_logbarang');
			}
			if ($param == 1) {
				$nobukti  = urut_transaksi('URUT_BHP', 19);
			} else {
				$nobukti = $this->input->post('noterima');
			}

			if ($nomorpo != "") {
				if ($param == 2) {
				}
			}
			if ($this->input->post('pembayaran') == 'CASH') {
				$jenisbeli = 0;	//tunai
			} else {
				$jenisbeli = 1;	//kredit	
			}
			$qcek = $this->db->query("SELECT * FROM tbl_apohterimalog WHERE invoice_no = '$faktur' and koders='$cabang' ")->result_array();
			$cek = count($qcek);
			if ($cek > 0) {
				echo json_encode(array("status" => "1", "nomor" => $nobukti));
			} else if ($cek == 0) {
				$qcek1 = $this->db->query('select * from tbl_logbarang where kodebarang = "' . $kodexx . '"')->result();
				$cek1 = count($qcek1);
				if ($cek1 > 0) {
					echo json_encode(array("status" => "1", "nomor" => $nobukti));
				} else {
					$data = array(
						'koders'      => $cabang,
						'terima_no'   => $nobukti,
						'terima_date' => date('Y-m-d', strtotime($this->input->post('tanggal'))),
						'terima_date' => $this->input->post('tanggal'),
						'due_date'    => $this->input->post('jatuhtempo'),
						'tgltukar'    => $this->input->post('tanggaltukar'),
						'vendor_id'   => $this->input->post('supp'),
						'sj_no'       => $this->input->post('nomorsj'),
						'invoice_no'  => $this->input->post('nofaktur'),
						'gudang'      => $this->input->post('gudang'),
						'kurs'        => $this->input->post('kurs'),
						'kursrate'    => $this->input->post('rate'),
						'materai'     => $this->input->post('materai'),
						'ongkir'      => $this->input->post('ongkir'),
						'bkemasan'    => $this->input->post('kemasan'),
						'diskontotal' => $this->input->post('diskonrp'),
						'term'        => $this->input->post('pembayaran'),
						'jenisbeli'   => $jenisbeli,
						'userid'      => $userid,
						'ppn'         => '',
						'vatrp'       => 0,
						//'due_date'=> date('Y-m-d',strtotime($this->input->post('tanggalkirim'))),				
					);
					if ($param == 1) {
						$insert =  $this->db->insert('tbl_apohterimalog', $data);
						// echo "cek data";		  
					} else {
						$update = $this->db->update('tbl_apohterimalog', $data, array('terima_no' => $nobukti));
						$datalpb = $this->db->get_where('tbl_apodterimalog', array('terima_no' => $nobukti))->result();
						foreach ($datalpb as $row) {
							$_kode = $row->kodebarang;
							$_qty  = $row->qty_terima;
							$this->db->query("UPDATE tbl_apostocklog set terima=terima- $_qty, saldoakhir= saldoakhir- $_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang'");
						}
						$this->db->query("DELETE from tbl_apodterimalog where terima_no = '$nobukti'");
						$this->db->query("DELETE from tbl_apoap where terima_no = '$nobukti'");
					}
					$kode   = $this->input->post('kode');
					$qty    = $this->input->post('qty');
					$sat    = $this->input->post('sat');
					$harga  = $this->input->post('harga');
					$disc   = $this->input->post('disc');
					$discrp = $this->input->post('discrp');
					$jumlah = $this->input->post('jumlah');
					$expire = $this->input->post('expire');
					$po     = $this->input->post('po');
					$tax    = $this->input->post('tax');
					$vatrp    = $this->input->post('vatrp');
					$jumdata  = count($kode);
					$nourut    = 1;
					$total     = 0;
					$totdis    = 0;
					$tot       = 0;
					$totppn    = 0;
					$totvatrp  = 0;
					for ($i = 0; $i <= $jumdata - 1; $i++) {
						$_harga    = str_replace(',', '', $harga[$i]);
						$_disc     = str_replace(',', '', $disc[$i]);
						$_jumlah   = str_replace(',', '', $jumlah[$i]);
						$_discrp   = str_replace(',', '', $discrp[$i]);
						$_vatrp    = $vatrp[$i];
						$_qty      = $qty[$i];
						$_kode     = $kode[$i];
						$diskon    = $_jumlah * ($_disc / 100);
						$totdis    += $diskon;
						$tot       += $_jumlah;
						// echo $tax[$i];
						if ($tax[$i] == "") {
							$_vat = 0;
						} else {
							$_vat = 1;
							$totppn += $_jumlah * (11 / 100);
						}
						// diferent
						$hpp = $this->db->get_where('tbl_logbarang', array('kodebarang' => $_kode))->row()->hpp;
						$total    += $hpp * $qty[$i];
						$totvatrp += $_vatrp;
						// end
						$datad = array(
							'koders'     => $cabang,
							'terima_no'  => $nobukti,
							'po_no'      => $nomorpo,
							'kodebarang' => $kode[$i],
							'qty_terima' => $qty[$i],
							'price'      => $_harga,
							'satuan'     => $sat[$i],
							'discount'   => $disc[$i],
							'discountrp' => $_discrp,
							'vat'        => $_vat,
							'vatrp'      => $_vatrp,
							'po_no'      => $po[$i],
							'exp_date'   => date('Y-m-d', strtotime($expire[$i])),
							'totalrp'    => $_jumlah,
						);
						if ($_kode != "") {
							$this->db->insert('tbl_apodterimalog', $datad);

							$this->db->query("UPDATE tbl_apostocklog set terima=terima+ $_qty, saldoakhir= saldoakhir+ $_qty where kodebarang = '$_kode'
						and koders = '$cabang' and gudang = '$gudang'");

							if ($nomorpo != "") {
								$this->db->query("UPDATE tbl_apodpolog set qty_terima = qty_terima + $_qty WHERE kodebarang = '$_kode' and po_no = '$nomorpo' ");
							}
						}
					}
					$this->db->query("UPDATE tbl_apohterimalog set vatrp='$totvatrp' where terima_no = '$nobukti' and koders = '$cabang'");
					// $this->db->insert('tbl_apodterimalog');
					echo json_encode([
						'status' => 2,
						'nomor' => $nobukti,
					]);


					// $this->db->query("UPDATE tbl_apohterimalog set vatrp='$totvatrp' where terima_no = '$nobukti' and koders = '$cabang'");
					// echo json_encode(array("status" =>"2","nomor" => $nobukti));
				}
			}
		}
	}

	public function edit($nomor)
	{
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$unit          = $this->session->userdata('unit');
			$level         = $this->session->userdata('level');
			$akses         = $this->M_global->cek_menu_akses($level, 3102);
			$this->load->helper('url');
			$d['modul']    = 'LOGISTIK';
			$d['submodul'] = 'BAPB';
			$d['link']     = 'BAPB';
			$d['url']      = 'logistik_bapb';
			$d['tanggal']  = date('d-m-Y');
			$d['akses']    = $akses;
			$header        = $this->db->query("SELECT (select po_no from tbl_apodterimalog b where tbl_apohterimalog.terima_no=b.terima_no limit 1)po_no,tbl_apohterimalog.* FROM tbl_apohterimalog  WHERE terima_no = '$nomor'");
			$detil         = $this->db->query("SELECT * FROM tbl_apodterimalog 
			JOIN tbl_logbarang ON tbl_logbarang.kodebarang=tbl_apodterimalog.kodebarang 
			WHERE terima_no = '$nomor'");
			$d['header']   = $header->row();
			$d['detil']    = $detil->result();
			$d['jumdata']  = $detil->num_rows();
			$d['supp']     = $this->db->order_by('vendor_name')->get_where('tbl_vendor', array('vendor_name !=' => ''))->result();
			$d['unit']     = $this->db->get('tbl_namers')->result();
			$d['gudang']   = $this->db->get('tbl_depo')->result();
			$d['ppn2']       = $this->db->query("SELECT * FROM tbl_pajak where kodetax='PPN'")->row();
			$this->load->view('pembelian/v_pembelian_penerimaan_edit', $d);
		} else {
			header('location:' . base_url());
		}
	}
	public function data_awal_terima()
	{
		$kode = $this->input->get('kode');
		$gudang = $this->input->get('gudang');
		$terima_no = $this->input->get('terima_no');
		$cabang = $this->session->userdata('unit');
		$data_barang = $this->db->query("SELECT * FROM tbl_apodterimalog WHERE kodebarang = '$kode' AND koders = '$cabang' AND terima_no = '$terima_no'")->row_array();
		echo json_encode($data_barang);
	}
}
/* End of file keuangan_saldo.php */
/* Location: ./application/controllers/keuangan_saldo.php */