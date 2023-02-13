<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Penjualan_retur extends CI_Controller
{



	public function __construct()
	{
		parent::__construct();
		$this->session->set_userdata('menuapp', '3000');
		$this->session->set_userdata('submenuapp', '3204');
		$this->load->helper('simkeu_nota');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');

		if (!empty($cek)) {

			$q1 = "SELECT c.returno, a.*, b.namapas, a.totalnet AS totalrp 
			FROM tbl_apohreturjual a 
			-- LEFT OUTER JOIN tbl_pasien b ON a.rekmed=b.rekmed 
			LEFT OUTER JOIN tbl_apoposting b ON a.resepno=b.resepno 
			JOIN tbl_apodreturjual AS c ON c.returno = a.returno 
			WHERE a.koders = '$unit' 
			GROUP BY a.returno 
			ORDER BY a.returno DESC";
			//    $total ="";
			$bulan           = $this->M_global->_periodebulan();
			$nbulan          = $this->M_global->_namabulan($bulan);
			$periode         = 'Periode ' . $nbulan . '-' . $this->M_global->_periodetahun();
			$d['keu']        = $this->db->query($q1)->result();
			$level           = $this->session->userdata('level');
			$akses           = $this->M_global->cek_menu_akses($level, 3204);
			$d['akses']      = $akses;
			$d['periode']    = $periode;
			$d['ppn']        = $this->db->query("SELECT * FROM tbl_pajak where kodetax='PPN'")->row();
			$this->load->view('penjualan/v_penjualan_retur', $d);
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
				// 	"select a.*, b.namapas, c.totalrp,
				// 	from
				// 	tbl_apohreturjual a left outer join
				// 	tbl_pasien b on a.rekmed=b.rekmed
				// 	join tbl_apodreturjual c on a.returno = c.returno
				// 	where
				// 	a.koders = '$unit' and 
				// 	a.tglretur between '$_tgl1' and '$_tgl2' 
				// 	order by
				// 	a.tglretur desc";
				$q1 = "select c.returno, a.*, b.namapas, sum(c.totalrp) as totalrp
			from tbl_apohreturjual a left outer join tbl_pasien b on a.rekmed=b.
			rekmed JOIN tbl_apodreturjual AS c ON c.returno = a.returno where a.koders = '$unit'
			AND a.tglretur BETWEEN '$_tgl1' AND '$_tgl2' GROUP BY a.returno 
  			order by a.tglretur desc ";


				$periode = 'Periode ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2));
				$d['keu'] = $this->db->query($q1)->result();
				$level = $this->session->userdata('level');
				$akses = $this->M_global->cek_menu_akses($level, 3204);
				$d['akses'] = $akses;
				$d['periode'] = $periode;
				$this->load->view('penjualan/v_penjualan_retur', $d);
			}
		} else {

			header('location:' . base_url());
		}
	}




	public function cetak()
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

			$param = $this->input->get('id');

			$queryh = "SELECT * from tbl_apohreturjual inner join 
			tbl_pasien on tbl_apohreturjual.rekmed=tbl_pasien.rekmed 
			where tbl_apohreturjual.returno = '$param'";


			$queryd = "SELECT tbl_apodreturjual.*, tbl_barang.namabarang from tbl_apodreturjual inner join 
			tbl_barang on tbl_apodreturjual.kodebarang=tbl_barang.kodebarang
			where returno = '$param'";
			$detilx  = $this->db->query($queryd)->result();
			if ($detilx) {
				$detil = $detilx;
			} else {
				$detil = $this->db->query("SELECT tbl_apodreturjual.*, tbl_logbarang.namabarang from tbl_apodreturjual inner join 
			tbl_logbarang on tbl_apodreturjual.kodebarang=tbl_logbarang.kodebarang
			where returno = '$param'")->result();
			}

			$headerx = $this->db->query($queryh)->row();
			if ($headerx) {
				$header = $headerx;
				$namapas = $header->namapas;
			} else {
				$header = $this->db->query("SELECT * from tbl_apohreturjual where tbl_apohreturjual.returno = '$param'")->row();
				$ceknama = $this->db->get_where("tbl_apoposting", ['resepno' => $param])->row();
				$namapas = $ceknama->namapas;
			}

			$pdf = new simkeu_nota();
			$pdf->setID($nama_usaha, $alamat1, $alamat2);
			$pdf->setjudul('');
			$pdf->setsubjudul('');
			$pdf->addpage("P", "A4");
			$pdf->setsize("P", "A4");

			$pdf->SetWidths(array(190));

			$pdf->setfont('Arial', 'B', 18);
			$pdf->SetAligns(array('C', 'C', 'C'));
			$border    = array('BTLR');
			$size      = array('');
			$align     = array('C');
			$style     = array('B');
			$size      = array('18');
			$max       = array(20);
			$fc        = array('0');
			$hc        = array('20');
			$judul     = array('RETURN PENJUALAN RESEP');
			$pdf->FancyRow2(10, $judul, $fc,  $border, $align, $style, $size, $max);
			$size      = array('10');
			$align     = array('L');
			$border    = array('');
			$judul     = array('Kepada Yth:');
			$pdf->FancyRow2(10, $judul, $fc,  $border, $align, $style, $size, $max);



			$pdf->ln(1);
			$pdf->setfont('Arial', 'B', 10);
			$pdf->SetWidths(array(20, 5, 80, 20, 5, 60));
			$border = array('T', 'T', 'T', 'T', 'T', 'T');
			$fc     = array('0', '0', '0', '0', '0', '0');
			$pdf->SetFillColor(230, 230, 230);
			$pdf->setfont('Arial', '', 9);


			$pdf->FancyRow(array('No. TR', ':', $header->returno, 'Dokter.', ':', $header->kodokter), $fc, $border);
			$border = array('', '', '', '', '', '');
			$pdf->FancyRow(array('Resep', ':', $header->resepno, 'Dari', ':', ''), $fc, $border);

			$pdf->FancyRow(array('Pro', ':', $namapas, 'No.RM', ':', $header->rekmed), $fc, $border);

			$pdf->ln(2);
			$pdf->SetWidths(array(10, 25, 50, 20, 20, 20, 20, 25));
			$border    = array('LRTB', 'LRTB', 'LRTB', 'LRTB', 'LRTB', 'LRTB', 'LRTB', 'LRTB');
			$align     = array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C');
			$pdf->setfont('Arial', 'B', 9);
			$pdf->SetAligns(array('L', 'C', 'R'));
			$fc        = array('0', '0', '0', '0', '0', '0', '0', '0');
			$judul     = array('No.', 'Kode', 'Nama Barang', 'Qty', 'Satuan', 'Harga', 'Discount', 'Total');
			$pdf->FancyRow2(8, $judul, $fc, $border, $align);
			$border    = array('', '', '');
			$pdf->setfont('Arial', '', 9);
			$tot       = 0;
			$subtot    = 0;
			$tdisc     = 0;
			$border    = array('LRTB', 'LRTB', 'LRTB', 'LRTB', 'LRTB', 'LRTB', 'LRTB', 'LRTB');

			$align     = array('L', 'L', 'L', 'R', 'C', 'R', 'R', 'R');
			$fc        = array('0', '0', '0', '0', '0', '0', '0', '0');
			$max       = array(8, 8, 8, 8, 8, 8, 8, 8);
			$pdf->SetFillColor(0, 0, 139);
			$pdf->settextcolor(0);
			$no        = 1;
			$totitem   = 0;
			$tot       = 0;
			$tot1      = 0;
			$totppn    = 0;
			$totdis    = 0;
			foreach ($detil as $db) {
				$totdis += $db->discountrp;
				$totppn += $db->ppnrp;
				$tot += ($db->qtyretur * $db->price) - $db->discountrp + $db->ppnrp;
				$tot1 += ($db->qtyretur * $db->price);
				$pdf->FancyRow2(5, array(
					$no,
					$db->kodebarang,
					$db->namabarang,
					$db->qtyretur,
					$db->satuan,
					number_format($db->price, 2, ',', '.'),
					number_format($db->discountrp, 2),
					number_format(($db->qtyretur * $db->price) - $db->discountrp, 2, ',', '.'),

				), $fc, $border, $align);

				$no++;
			}

			$pdf->SetWidths(array(165, 25));
			$border = array('T', 'T');
			$align  = array('R', 'R');
			$style = array('B', 'B');
			$size = array('', '');
			$max = array('', '');
			$pdf->FancyRow2(5, array(
				'Subtotal',
				number_format($tot1, 2, ',', '.'),

			), $fc,  $border, $align, $style, $size, $max);
			$pdf->SetWidths(array(165, 25));
			$border = array('', '');
			$align  = array('R', 'R');
			$style = array('B', 'B');
			$size = array('', '');
			$max = array('', '');
			$pdf->FancyRow2(5, array(
				'Diskon',
				number_format($totdis, 2, ',', '.'),

			), $fc,  $border, $align, $style, $size, $max);
			$pdf->SetWidths(array(165, 25));
			$border = array('', '');
			$align  = array('R', 'R');
			$style = array('B', 'B');
			$size = array('', '');
			$max = array('', '');
			$pdf->FancyRow2(5, array(
				'PPN',
				number_format($totppn, 2, ',', '.'),

			), $fc,  $border, $align, $style, $size, $max);

			$pdf->SetWidths(array(165, 25));
			$border = array('B', 'B');
			$align  = array('R', 'R');
			$style = array('B', 'B');
			$size = array('', '');
			$max = array('', '');
			$pdf->FancyRow2(5, array(
				'Total Net',
				number_format($tot, 2, ',', '.'),

			), $fc,  $border, $align, $style, $size, $max);

			$pdf->ln();
			$pdf->SetWidths(array(47.5, 50, 34.5, 60.5));
			$border = array('', '', '', '', '');
			$align  = array('C', 'C', 'C', 'C', 'C');
			$pdf->setfont('Arial', '', 8);
			$fc = array('0', '0', '0', '0', '0');
			$style = array('', '', '', '', '');
			$size  = array('10', '10', '10', '10');
			$judul = array('', '', '', $alamat2 . ', ' . date('d-m-Y'));
			$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
			$judul = array('', '', '', 'Petugas');
			$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
			$judul = array('VALIDASI KASIR:', '', '', 'Cap & Tanda Tangan');
			$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
			$judul = array('', '', '', '');
			$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
			$pdf->ln(15);

			$style = array('B', 'B', 'B', 'B');
			$border = array('', '', '', '');
			$judul = array('', '', '', $user);
			$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);

			$border = array('Tb');
			$size   = array('');
			$align = array('C');
			$style = array('i');
			$size  = array('8');
			$max   = array(5);
			$fc     = array('0');
			$hc     = array('0');
			$pdf->SetWidths(array(190));
			$pdf->ln();
			$judul = array('HARAP SIMPAN STRUK RETURN OBAT INI, AGAR DIPERLIHATKAN PADA SAAT PASIEN AKAN PULANG DI CASHIER');
			$pdf->FancyRow2(10, $judul, $fc,  $border, $align, $style, $size, $max);


			$pdf->setTitle($param);
			$pdf->AliasNbPages();
			$pdf->output($param . '.PDF', 'I');
		} else {

			header('location:' . base_url());
		}
	}


	public function getpo($po)
	{
		$data = $this->db->select('tbl_apodresep.*, tbl_barang.namabarang')->join('tbl_barang', 'tbl_barang.kodebarang=tbl_apodresep.kodebarang', 'left')->get_where('tbl_apodresep', array('resepno' => $po))->result();
		echo json_encode($data);
	}

	public function getbiaya($po)
	{
		$data = $this->db->select('ap_pobiaya.*, ms_akun.namaakun')->join('ms_akun', 'ms_akun.kodeakun=ap_pobiaya.kodeakun', 'left')->get_where('ap_pobiaya', array('kodepo' => $po))->result();
		echo json_encode($data);
	}

	public function getlistpo($supp)
	{
		if (!empty($supp)) {
			$po  = $this->db->query('select * from tbl_apohresep')->result();

?>
<select name="kodesi" id="kodesi" class="form-control  input-medium select2me">
  <option value="">-- Tanpa Faktur ---</option>
  <?php
				foreach ($po  as $row) {
				?>
  <option value="<?php echo $row->resepno; ?>"><?php echo $row->resepno; ?></option>

  <?php } ?>
</select>
<span class="input-group-btn">
  <a class="btn blue" onclick="getpoheader();getpo();"><i class="fa fa-download"></i></a>
</span>

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
			$page = $this->uri->segment(3);
			$limit = $this->config->item('limit_data');
			$this->load->view('penjualan/v_penjualan_retur_add');
		} else {

			header('location:' . base_url());
		}
	}

	public function hapus($nomor)
	{
		$cek = $this->session->userdata('level');
		$hmutasi = $this->db->query("select * from tbl_apohreturjual where returno = '$nomor'")->row();
		$cabang  = $hmutasi->koders;
		$gudang  = $hmutasi->gudang;
		if (!empty($cek)) {

			$datamutasi = $this->db->get_where('tbl_apodreturjual', array('returno' => $nomor))->result();
			foreach ($datamutasi as $row) {
				$_qty = $row->qtyretur;
				$_kode = $row->kodebarang;

				$this->db->query("update tbl_barangstock set terima=terima- $_qty, saldoakhir= saldoakhir- $_qty where kodebarang = '$_kode'
			  and koders = '$cabang' and gudang = '$gudang'");
			}

			$this->db->delete('tbl_apohreturjual', array('returno' => $nomor));
			$this->db->delete('tbl_apodreturjual', array('returno' => $nomor));
			$this->db->delete('tbl_apoposting', array('resepno' => $nomor));
		} else {

			header('location:' . base_url());
		}
	}


	public function getakun($kode)
	{
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

		public function getbarang($kode)
		{
			if (!empty($kode)) {

				$q = $kode;
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
					foreach ($data->result_array() as $row) { ?>
    <tr>
      <td width="50" align="center">
        <a href="#"
          onclick="post_value('<?php echo $row['kodeitem']; ?>','<?php echo $row['namabarang']; ?>','<?php echo $row['satuan']; ?>','<?php echo $row['hargabeliakhir']; ?>')">

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

			public function getharga($kode)
			{
				if (!empty($kode)) {
					$data  = explode("~", $kode);
					$supp  = $data[0];
					$item  = $data[1];

					$query = "select * from ar_sidetail inner join ar_sifile on ar_sifile.kodesi=ar_sidetail.kodesi where ar_sifile.kodecust = '$supp' and ar_sidetail.kodeitem = '$item' order by ar_sifile.tglsi desc";
					$data  = $this->db->query($query)->result();
					?>

    <table id="myTable" class="table">
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
        <td width="50" align="center">
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
					$kode = $this->input->get('kode');
					$data = $this->M_global->_data_barang($kode);
					echo json_encode($data);
				}

				public function getinfoakun($kode)
				{
					$data = $this->M_global->_data_akun($kode);
					echo json_encode($data);
				}

				public function getpoheader($kodepo)
				{
					$data = $this->db->get_where('ar_sofile', array('kodeso' => $kodepo))->row();
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


				// public function save1($param)
				// {
				// 	$hasil = 0;
				// 	$cek = $this->session->userdata('level');
				// 	if(!empty($cek))
				// 	{			            

				// 		$userid    = $this->session->userdata('username');
				// 		$unit      = $this->session->userdata('unit');
				// 		$gudang    = $this->input->post('gudang');
				// 		$cabang    = $this->session->userdata('unit');
				//        	if($param==1){			
				// 		  	$nobukti  = urut_transaksi('APORETURJUAL', 16);
				// 		} else {
				// 		  	$nobukti  = $this->input->post('nomorbukti');	
				// 		}
				//        	if($param==2){
				// 			$dataretur = $this->db->get_where('tbl_apodreturjual', array('returno' => $nobukti))->result();
				// 			foreach($dataretur as $row){					 
				// 			     $_qty  = $row->qtyretur;
				// 			     $_kode = $row->kodebarang;

				// 				$this->db->query("UPDATE tbl_barangstock set terima=terima- $_qty, saldoakhir= saldoakhir- $_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang'");
				// 			}
				// 			$this->db->delete('tbl_apodreturjual',array('returno' => $nobukti));
				// 		}
				// 		$kode          = $this->input->post('kode');
				// 		$qty           = $this->input->post('qty');
				// 		$sat           = $this->input->post('sat');
				// 		$harga         = $this->input->post('harga');
				// 		$disc          = $this->input->post('disc');
				// 		$jumlah        = $this->input->post('jumlah');
				// 		//cek data yang di checklist
				// 		$rowchecked    = $this->input->post('cek');
				// 		$jumdata       = count($kode);
				// 		$nourut    = 1;
				// 		$tot       = 0;
				// 		$tdisc     = 0;
				// 		for ($i=0; $i <count($rowchecked) ; $i++) { 
				// 			$_kode   = $kode[$rowchecked[$i]];
				// 			$_qty    = $qty[$rowchecked[$i]];
				// 			$_harga  = str_replace(',','',$harga[$rowchecked[$i]]);

				// 			$vjum    = $qty[$i] * $_harga; //ini perlu
				// 			$vdisc   = $vjum * ($disc[$i]/100);
				// 			$tot     = $tot + $vjum;
				// 			$tdisc   = $tdisc + $vdisc;
				// 			$total   = $vjum-$vdisc;

				// 			$datad = array(
				// 				'koders'     => $unit,
				// 				'returno'    => $nobukti,
				// 				'kodebarang' => $_kode,
				// 				'qtyretur'   => $qty[$i],
				// 				'satuan'     => $sat[$i],
				// 				'price'      => $_harga,
				// 				'discount'   => $disc[$i],
				// 				'totalrp'    => $jumlah[$i],
				// 			);
				// 			if($qty[$rowchecked[$i]]!="0" && $_kode!=""){
				// 				$this->db->insert('tbl_apodreturjual', $datad);
				// 				// $stokcek = $this->db->query("SELECT * FROM tbl_barangstock WHERE kodebarang = '$_kode' and koders='$cabang' and gudang='$gudang' ")->result_array();
				// 				// $scek = count($stokcek);
				// 				// if ($scek > 0){
				// 					$noresep = $this->input->post('kwiobat');
				// 					$this->db->query("UPDATE tbl_barangstock set terima=terima+ $_qty, saldoakhir= saldoakhir+ $_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang'");
				// 					// $this->db->query("UPDATE tbl_apodresep SET qty = qty - $_qty WHERE resepno = '$noresep' AND kodebarang = '$_kode'");
				// 				// } else {
				// 				// 	$datastock = array(
				// 				// 		'koders'       => $cabang,
				// 				// 		'kodebarang'   => $_kode,
				// 				// 		'gudang'       => $pembeli,
				// 				// 		'saldoawal'    => 0,
				// 				// 		'terima'       => $_qty,
				// 				// 		'saldoakhir'   => $_qty,
				// 				// 		'tglso'        => $this->input->post('tanggal'),
				// 				// 		'lasttr'       => $this->input->post('tanggal'),	
				// 				// 	);
				// 				// 	$insert_detil = $this->db->insert('tbl_barangstock',$datastock);
				// 				// }
				// 			}
				// 		}
				// 		// die;

				// 		// saya ganti
				// 		// for($i=0;$i<=$jumdata-1;$i++) {
				// 			//  	$_kode   = $kode[$i];
				// 			// 	$_qty    = $qty[$i];
				// 			// 	//$hpp = $this->db->get_where('tbl_barang', array('kodebarang' => $_kode))->row()->hpp;
				// 			// 	$_harga  = str_replace(',','',$harga[$i]);

				// 			// 	$vjum  = $qty[$i] * $_harga;
				// 			// 	$vdisc = $vjum * ($disc[$i]/100);
				// 			// 	$tot   = $tot + $vjum;
				// 			// 	$tdisc = $tdisc + $vdisc;
				// 			// 	$total = $vjum-$vdisc;

				// 			// 	$datad = array(
				// 				// 	'koders' => $unit,
				// 				// 	'returno'   => $nobukti,
				// 				// 	'kodebarang' => $_kode,
				// 				// 	'qtyretur' => $qty[$i],
				// 				// 	'satuan' => $sat[$i],
				// 				// 	'price' => $_harga,
				// 				// 	'discount' => $disc[$i],
				// 				// 	'totalrp' => $total,
				// 			//   );


				// 			// 	if($qty[$i]!="0" && $_kode!=""){
				// 			//       $this->db->insert('tbl_apodreturjual', $datad);

				// 			// 	   $this->db->query("update tbl_barangstock set terima=terima+ $_qty, saldoakhir= saldoakhir+ $_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang'");
				// 			// 	}
				// 		// }

				// 		$data = array(
				// 			'username' => $userid,
				// 			'koders'  => $this->session->userdata('unit'),
				// 			'returno' => $nobukti,
				// 			'resepno' => $this->input->post('kwiobat'),
				// 			'rekmed'  => $this->input->post('rekmed'),
				// 			'tglretur' => date('Y-m-d',strtotime($this->input->post('tanggal'))),
				// 			'gudang' => $this->input->post('gudang'),	
				// 		);

				// 		/*
				// 		$this->M_global->_hapusjurnal($this->input->post('nomorbukti'), 'JJ');	

				// 		$profile = $this->M_global->_LoadProfileLap();			
				// 		$akun_debet     = $profile->akun_retjual;
				// 		$akun_kredit    = $profile->akun_persediaan;

				// 		$total = $tot+$tppn-$tdisc;

				// 		$this->M_global->_rekamjurnal(
				// 		date('Y-m-d',strtotime($this->input->post('tanggal'))),
				// 		$this->input->post('nomorbukti'),
				// 		'JJ',
				// 		$this->input->post('kodesi'),
				// 		1,
				// 		$akun_debet,
				// 		'Retur Penjualan '.$this->input->post('kodesi'),
				// 		'Retur Penjualan '.$this->input->post('kodesi'),
				// 		$total,
				// 		0
				// 		);

				// 		$this->M_global->_rekamjurnal(
				// 		date('Y-m-d',strtotime($this->input->post('tanggal'))),
				// 		$this->input->post('nomorbukti'),
				// 		'JJ',
				// 		$this->input->post('kodesi'),
				// 		2,
				// 		$akun_kredit,
				// 		'Retur Penjualan '.$this->input->post('kodesi'),
				// 		'Retur Penjualan '.$this->input->post('kodesi'),
				// 		0,
				// 		$total
				// 		);
				// 		*/

				// 		if($param==1) {
				// 		  	$this->db->insert('tbl_apohreturjual',$data);	
				// 		} else {
				// 		  	$this->db->update('tbl_apohreturjual',$data, array('returno' => $nobukti));				 
				// 		}
				// 		echo $nobukti;
				// 	} else {
				// 		header('location:'.base_url());
				// 	}
				// }

				public function save_one()
				{
					$cabang    = $this->session->userdata('unit');
					$nobukti   = urut_transaksi('APORETURJUAL', 16);
					$noresep   = $this->input->post('kwiobat');
					$rekmed    = $this->input->post('rekmed');
					$tanggal   = $this->input->post('tanggal');
					$gudang    = $this->input->post('gudang');
					$alasan    = $this->input->post('alasan');
					$userid    = $this->session->userdata('username');
					$total     = $this->input->get('vtotal');
					$cek = $this->session->userdata('level');
					if (!empty($cek)) {
						if($rekmed=='Non Member'){
							$noreg='';
						}else{
							$data_regist = $this->db->get_where('tbl_regist', ['rekmed' => $rekmed])->row_array();
							$noreg = $data_regist['noreg'];
						}
						if ($noresep != '' && $cabang != '' && $nobukti != '') {
							$data = [
								'koders'  	=> $cabang,
								'returno' 	=> $nobukti,
								'resepno' 	=> $noresep,
								'noreg'   	=> $noreg,
								'rekmed'  	=> $rekmed,
								'tglretur' 	=> date('Y-m-d', strtotime($tanggal)),
								'gudang' 	=> $gudang,
								'username' 	=> $userid,
								'totalnet' 	=> $total,
								'alasan' 	=> $alasan,
								'jamreturjual' => date('H:i:s'),
								// str_replace(",","",$this->input->get('vtotal'))
							];
							$this->db->insert('tbl_apohreturjual', $data);
						}
						$getnamapas = $this->db->query("SELECT date(tglresep)as tgll,tbl_apoposting.* from tbl_apoposting where resepno = '$noresep'")->row();

						if($rekmed=='Non Member'){
							$namapas = $getnamapas->namapas;
						}else{
							$psn = $this->db->get_where('tbl_pasien', ['rekmed' => $rekmed])->row_array();
							$namapas = $psn['namapas'];

						}
						$data_apoposting = [
							'koders' => $cabang,
							'resepno' => $nobukti,
							'tglresep' => date('Y-m-d', strtotime($tanggal)),
							'noreg' => $noreg,
							'rekmed' => $rekmed,
							'namapas' => $namapas,
							'gudang' => $gudang,
							'poscredit' => (-$total),
							'username' => $userid,
							'posting' => 1,
						];
						$this->db->insert('tbl_apoposting', $data_apoposting);
						echo json_encode(['nomor' => $nobukti, 'status' => 1]);
					} else {
						header('location:' . base_url());
					}
				}

				public function save_multi()
				{
					$kodebarang    = $this->input->get('kodebarang');
					$resepno       = $this->input->get('resepno');
					$loop          = $this->input->get('loop');
					$satuan        = $this->input->get('satuan');
					$jumlah        = $this->input->get('jumlah');
					$dsc           = $this->input->get('dsc');
					$ppn           = $this->input->get('ppn');
					$apodresep     = $this->db->get_where('tbl_apodresep', ['resepno' => $resepno, 'kodebarang' => $kodebarang])->row_array();
					$cabang        = $this->session->userdata('unit');
					$gudang        = $this->input->post('gudang');
					$rekmed        = $this->input->post('rekmed');
					$tanggal       = $this->input->post('tanggal');
					$userid        = $this->session->userdata('username');
					$noresep       = $this->input->post('kwiobat');
					$qty           = (int)$this->input->get('qty');
					$harga         = (int)$this->input->get('harga');
					$cek           = $this->session->userdata('level');
					$ppnxx         = $this->db->query("SELECT * FROM tbl_pajak where kodetax='PPN'")->row();
					if (!empty($cek)) {
						if ($qty <= (int)$apodresep['qty']) {
							$qty_new = $qty;
						} else {
							$qty_new = (int)$apodresep['qty'];
						}
						$data = [
							'koders'     => $cabang,
							'returno'    => $this->input->get('nobukti'),
							'kodebarang' => $kodebarang,
							'qtyretur'   => $qty_new,
							'satuan'     => $satuan,
							'price'      => $harga,
							'discountrp'   => $dsc,
							'discount'   => 0,
							'ppnrp'      => ((($qty_new * $harga) - $dsc) * $ppnxx->prosentase / 100),
							'ppn' => 1,
							'totalrp'    => $jumlah,
						];
						if ($kodebarang != '') {
							$this->db->insert('tbl_apodreturjual', $data);
							$stok = $this->db->query("select * from tbl_barangstock where kodebarang = '$kodebarang' and gudang = '$gudang' and koders = '$cabang'")->num_rows();
							$date_now = date('Y-m-d H:i:s');
							if($stok > 0){
								$this->db->query("UPDATE tbl_barangstock set terima = terima+ $qty, saldoakhir = saldoakhir + $qty, lasttr = '$date_now' where kodebarang = '$kodebarang' and koders = '$cabang' and gudang = '$gudang'");
							} else {
								$datastock = array(
									'koders'       => $cabang,
									'kodebarang'   => $kodebarang,
									'gudang'       => $gudang,
									'saldoawal'    => 0,
									'terima'       => $qty,
									'keluar'       => 0,
									'saldoakhir'   => $qty,
									'lasttr'       => $date_now,
								);
								$this->db->insert('tbl_barangstock', $datastock);
							}
						}
						echo json_encode(['nomor' => $this->input->get('nobukti'), 'status' => 1]);
						// echo json_encode($data);
					} else {
						header('location:' . base_url());
					}
				}

				public function ajax_add($param)
				{
					$cabang = $this->session->userdata('unit');
					$gudang = $this->input->post('gudang');
					$userid = $this->session->userdata('username');
				}

				public function update_one()
				{
					$userid = $this->session->userdata('username');
					$nobukti  = $this->input->post('nomorbukti');
					$resepno = $this->input->post('kodesi');
					$cabang = $this->session->userdata('unit');
					$gudang = $this->input->post('gudang');
					$rekmed = $this->input->post('cust');
					$tanggal = $this->input->post('tanggal');
					$alasan = $this->input->post('alasan');
					$total = $this->input->get('total');
					$data_regist = $this->db->get_where('tbl_regist', ['rekmed' => $rekmed])->row_array();
					$noreg = $data_regist['noreg'];
					$data = [
						'username' => $userid,
						'resepno' => $resepno,
						'rekmed' => $rekmed,
						'noreg' => $noreg,
						'alasan' => $alasan,
						'tglretur' => date('Y-m-d', strtotime($tanggal)),
						'gudang' => $gudang,
						'totalnet' => $total,
					];
					$where = [
						'koders' => $cabang,
						'returno' => $nobukti,
					];

					$this->db->update('tbl_apohreturjual', $data, $where);

					$where_apoposting = [
						'koders' => $cabang,
						'resepno' => $nobukti,
					];

					$psn = $this->db->get_where('tbl_pasien', ['rekmed' => $rekmed])->row_array();
					$data_apoposting = [
						'tglresep' => date('Y-m-d', strtotime($tanggal)),
						'noreg' => $noreg,
						'rekmed' => $rekmed,
						'namapas' => $psn['namapas'],
						'gudang' => $gudang,
						'poscredit' => (-$total),
						'username' => $userid,
					];
					$this->db->update('tbl_apoposting', $data_apoposting, $where_apoposting);

					$apodereturjual = $this->db->query('select * from tbl_apodreturjual where returno = "' . $nobukti . '"')->result();
					foreach ($apodereturjual as $row) {
						$_qty  = $row->qtyretur;
						$_kode = $row->kodebarang;
						$this->db->query("UPDATE tbl_barangstock set terima=terima- $_qty, saldoakhir= saldoakhir- $_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang'");
					}
					$this->db->delete('tbl_apodreturjual', array('returno' => $nobukti));
					echo json_encode(['nomor' => $nobukti]);
				}

				public function update_multi()
				{
					$userid = $this->session->userdata('username');
					$nobukti  = $this->input->get('nobukti');
					$kode = $this->input->get('kode');
					$qty = $this->input->get('qty');
					$sat = $this->input->get('sat');
					$resepno = $this->input->post('kodesi');
					$harga = $this->input->get('harga');
					$disc = $this->input->get('disc');
					$jumlah = $this->input->get('jumlah');
					$cabang = $this->session->userdata('unit');
					$gudang = $this->input->post('gudang');
					$rekmed = $this->input->post('rekmed');
					$tanggal = $this->input->post('tanggal');
					$ppn = $this->db->query("SELECT * FROM tbl_pajak where kodetax='PPN'")->row();

					$data = [
						'koders'     => $cabang,
						'returno'    => $this->input->get('nobukti'),
						'kodebarang' => $kode,
						'qtyretur'   => $qty,
						'satuan'     => $sat,
						'price'      => $harga,
						'discountrp'   => $disc,
						'discount'   => 0,
						'ppnrp'      => ((($qty * $harga) - $disc) * $ppn->prosentase / 100),
						'ppn' => 1,
						'totalrp'    => $jumlah,
					];
					$this->db->insert('tbl_apodreturjual', $data);

					$stok = $this->db->query("select * from tbl_barangstock where kodebarang = '$kode' and gudang = '$gudang' and koders = '$cabang'")->num_rows();
					$date_now = date('Y-m-d H:i:s');
					if($stok > 0){
						$this->db->query("UPDATE tbl_barangstock set terima = terima+ $qty, saldoakhir = saldoakhir + $qty, lasttr = '$date_now' where kodebarang = '$kode' and koders = '$cabang' and gudang = '$gudang'");
					} else {
						$datastock = array(
							'koders'       => $cabang,
							'kodebarang'   => $kode,
							'gudang'       => $gudang,
							'saldoawal'    => 0,
							'terima'       => $qty,
							'keluar'       => 0,
							'saldoakhir'   => $qty,
							'lasttr'       => $date_now,
						);
						$this->db->insert('tbl_barangstock', $datastock);
					}
					// echo json_encode($data);
				}

				public function save($param)
				{
					$kodebarang = $this->input->get('kodebarang');
					$resepno = $this->input->get('resepno');
					$loop = $this->input->get('loop');
					$satuan = $this->input->get('satuan');
					$jumlah = $this->input->get('jumlah');
					$dsc = $this->input->get('dsc');
					$apodresep = $this->db->get_where('tbl_apodresep', ['resepno' => $resepno, 'kodebarang' => $kodebarang])->row_array();
					$cabang = $this->session->userdata('unit');
					$gudang = $this->input->post('gudang');
					$rekmed = $this->input->post('rekmed');
					$tanggal = $this->input->post('tanggal');
					$userid = $this->session->userdata('username');
					$noresep = $this->input->post('kwiobat');
					$qty = (int)$this->input->get('qty');
					$harga = (int)$this->input->get('harga');
					$ppn = $this->db->query("SELECT * FROM tbl_pajak where kodetax='PPN'")->row();
					$hasil = 0;
					$tdisc = 0;
					$cek = $this->session->userdata('level');
					if (!empty($cek)) {
						if ($qty <= (int)$apodresep['qty']) {
							$qty_new = $qty;
						} else {
							$qty_new = (int)$apodresep['qty'];
						}
						if ($param == 1) {
							$nobukti  = urut_transaksi('APORETURJUAL', 16);
						} else if ($param == 2) {
							$nobukti  = $this->input->post('nomorbukti');
							$apodereturjual = $this->db->get_where('tbl_apodreturjual', array('returno' => $nobukti))->result();
							$barang = $this->db->query('select * from tbl_barangstock where koders = "' . $cabang . '" and gudang = "' . $gudang . '" and kodebarang ="' . $kodebarang . '"')->row();
							$keluar_upd = (int)$barang->keluar - (int)$qty_new;
							$saldoakhir_upd = (int)$barang->saldoakhir - (int)$qty_new;
							$this->db->set('keluar', $keluar_upd);
							$this->db->set('saldoakhir', $saldoakhir_upd);
							$this->db->where('koders', $cabang);
							$this->db->where('kodebarang', $kodebarang);
							$this->db->where('gudang', $gudang);
							$this->db->update('tbl_barangstock');

							$this->db->where('returno', $nobukti);
							$this->db->delete('tbl_apodreturjual');
						}

						$datareturjual = array(
							'koders'     => $cabang,
							'returno'    => $nobukti,
							'kodebarang' => $kodebarang,
							'qtyretur'   => $qty_new,
							'satuan'     => $satuan,
							'price'      => $harga,
							'discountrp'   => $dsc,
							'totalrp'    => $jumlah,
							'ppnrp'    => ($jumlah * $ppn->prosentase / 100),
						);
						$this->db->insert('tbl_apodreturjual', $datareturjual);

						$barang = $this->db->query('select * from tbl_barangstock where koders = "' . $cabang . '" and gudang = "' . $gudang . '" and kodebarang ="' . $kodebarang . '"')->row();
						$keluar_upd = (int)$barang->keluar + (int)$qty_new;
						$saldoakhir_upd = (int)$barang->saldoakhir + (int)$qty_new;
						$this->db->set('keluar', $keluar_upd);
						$this->db->set('saldoakhir', $saldoakhir_upd);
						$this->db->where('koders', $cabang);
						$this->db->where('kodebarang', $kodebarang);
						$this->db->where('gudang', $gudang);
						$this->db->update('tbl_barangstock');

						$datahreturjual = array(
							'username' => $userid,
							'koders'  => $cabang,
							'returno' => $nobukti,
							'resepno' => $noresep,
							'rekmed'  => $rekmed,
							'tglretur' => date('Y-m-d', strtotime($tanggal)),
							'gudang' => $gudang,
							'jamreturjual' => date('H:i:s'),
						);
						if ($param == 1) {
							$this->db->insert('tbl_apohreturjual', $datahreturjual);
						} else {
							$this->db->set('username', $userid);
							$this->db->set('koders', $cabang);
							$this->db->set('resepno', $resepno);
							$this->db->set('rekmed', $rekmed);
							$this->db->set('tglretur', date('Y-m-d', strtotime($tanggal)));
							$this->db->set('gudang', $gudang);
							$this->db->where('returno', $nobukti);
							$this->db->update('tbl_apohreturjual');
						}

						echo $nobukti;
					} else {
						header('location:' . base_url());
					}
				}

				public function edit($nomor)
				{
					$cek = $this->session->userdata('level');
					if (!empty($cek)) {
						$unit = $this->session->userdata('unit');

						$header = $this->db->get_where('tbl_apohreturjual', array('returno' => $nomor));
						
						$ceking = $this->db->query("SELECT * FROM tbl_apodreturjual d WHERE returno = '$nomor' and kodebarang IN (SELECT kodebarang FROM tbl_barang)")->result();

						if (empty($ceking)) {
							$detil = $this->db->query("SELECT (select namabarang from tbl_logbarang where kodebarang=d.kodebarang) as namabarang, (SELECT satuan1 FROM tbl_logbarang WHERE kodebarang=d.kodebarang) AS satuan1, d.* from tbl_apohreturjual h join tbl_apodreturjual d on h.returno=d.returno where d.returno = '$nomor'");
						} else {
							$detil = $this->db->query("SELECT (select namabarang from tbl_barang where kodebarang=d.kodebarang) as namabarang, (SELECT satuan1 FROM tbl_barang WHERE kodebarang=d.kodebarang) AS satuan1, d.* from tbl_apohreturjual h join tbl_apodreturjual d on h.returno=d.returno where d.returno = '$nomor'");
						}
						// var_dump($detil); die;

						$d['header']    = $header->result();
						$d['detil']     = $detil->result();
						$d['jumdata1']  = $detil->num_rows();
						$this->load->view('penjualan/v_penjualan_retur_edit', $d);
					} else {
						header('location:' . base_url());
					}
				}

				public function getdataresep()
				{
					$nokwitansiobat  = $this->input->get('noresep');
					// $query = "SELECT * from tbl_apodresep where resepno = '$nokwitansiobat'";


					// $query = "SELECT sum(p.qtyretur)qtyretur2,p.* from(
					// SELECT (SELECT sum(qtyretur) FROM tbl_apodreturjual c WHERE c.returno=b.returno)qtyretur,a.* 
					// from tbl_apodresep a 
					// LEFT JOIN tbl_apohreturjual b on a.resepno=b.resepno 
					// where a.resepno = '$nokwitansiobat' )p
					// group by p.resepno ";

					$query = "SELECT * FROM tbl_apodresep WHERE resepno = '" . $nokwitansiobat . "'";

					$data  = $this->db->query($query)->result();
					$no = 1;
					foreach ($data as $key => $value) {
						?>

      <tr>
        <td width="5%">
          <!-- <button type='button' onclick="deleteRow(this)" class='btn red'><i class='fa fa-trash-o'> -->
          <input type="checkbox" name="cek[]" value="<?= $key ?>" onclick="total_retur();" style="font-weight: bold-;"
            class="form-control no-padding">
        </td>
        <td width="5%">
          <input name="kode[]" value="<?= $value->kodebarang ?>" id="kode<?= $no ?>" type="text" class="form-control">
        </td>
        <td width="35%">
          <input name="namabarang[]" value="<?= $value->namabarang ?>" id="namabarang<?= $no ?>" type="text"
            class="form-control">
        </td>

        <td width="5%">
          <!-- <input name="qty[]" onchange="totalline(1);total()"
                        value="<?= str_replace(',', '', $value->qty - $value->qtyretur2); ?>" id="qty1" type="text"
                        class="form-control rightJustified"> -->
          <input name="qty[]" onchange="totalline(<?= $no ?>);total();changeqty(<?= $no ?>)"
            value="<?= str_replace('.00', '', $value->qty); ?>" id="qty<?= $no ?>" type="text"
            class="form-control rightJustified">
        </td>

        <td width="10%">
          <input name="sat[]" id="sat<?= $no ?>" type="text" class="form-control " onkeypress="return tabE(this,event)"
            value="<?= $value->satuan ?>" readonly>
        </td>
        <td width="10%">
          <input name="harga[]" onchange="totalline(<?= $no ?>)" value="<?= str_replace('.00', '', $value->price) ?>"
            id="harga<?= $no ?>" type="text" class="form-control rightJustified" readonly>
        </td>
        <td width="5%"><a class="btn default" id="lupharga<?= $no ?>" data-toggle="modal" href="#lupharga"
            onclick="getidharga(this.id)"><i class="fa fa-search"></i></a></td>
        <td width="10%">
          <input name="disc[]" onchange="totalline(<?= $no ?>);total()"
            value="<?= str_replace('.00', '', $value->discrp) ?>" id="disc<?= $no ?>" type="text"
            class="form-control rightJustified ">
        </td>
        <td width="15%">
          <input name="jumlah[]" id="jumlah<?= $no ?>" type="text"
            value="<?= str_replace('.00', '', $value->totalrp) ?>" class="form-control rightJustified" size="40%"
            onchange="total()">
        </td>

      </tr>
      <?php $no++;
					}
				}

				public function data_awal()
				{
					$kode = $this->input->get('kode');
					$gudang = $this->input->get('gudang');
					$resepno = $this->input->get('resepno');
					$cabang = $this->session->userdata('unit');
					$data = [
						'kode' => $kode,
						'gudang' => $gudang,
						'cabang' => $cabang,
					];
					$data_barang = $this->db->query("SELECT * FROM tbl_apodresep WHERE kodebarang = '$kode' AND koders = '$cabang' AND resepno = '$resepno'")->row_array();
					echo json_encode($data_barang);
				}

				public function data_awal_retur()
				{
					$kode = $this->input->get('kode');
					$gudang = $this->input->get('gudang');
					$returno = $this->input->get('returno');
					$cabang = $this->session->userdata('unit');
					$data_barang = $this->db->query("SELECT * FROM tbl_apodreturjual WHERE kodebarang = '$kode' AND koders = '$cabang' AND returno = '$returno'")->row_array();
					echo json_encode($data_barang);
				}

				public function getdataresep2()
				{
					$nokwitansiobat  = $this->input->get('noresep');
					$query = "SELECT date(tglresep)as tgll,
					(select d.keterangan from tbl_depo d where d.depocode=tbl_apoposting.gudang)nm_gud,tbl_apoposting.* from tbl_apoposting where resepno = '$nokwitansiobat'";
					$data  = $this->db->query($query)->row();

					echo json_encode($data);
				}
			}
/* End of file penjualan_retur.php */
/* Location: ./application/controllers/penjualan_retur.php */