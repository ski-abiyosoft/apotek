<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Retur_jual_cabang extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->session->set_userdata('menuapp', '4000');
<<<<<<< HEAD
		$this->session->set_userdata('submenuapp', '6103');
=======
		$this->session->set_userdata('submenuapp', '4403');
>>>>>>> development
		$this->load->helper('simkeu_nota');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');
		$auth = key_exists('username', $this->session->userdata());
		$unit = $this->session->userdata('unit');

		if ($auth) {
			$q1 = "SELECT c.returno, a.*, b.namapas, a.totalnet AS totalrp FROM tbl_apohreturjual a LEFT OUTER JOIN tbl_pasien b ON a.rekmed=b.rekmed JOIN tbl_apodreturjual AS c ON c.returno = a.returno WHERE a.koders = '$unit' AND a.tglretur like '%" . date("Y-m") . "%' GROUP BY a.returno ORDER BY a.returno DESC";
			$bulan  = $this->M_global->_periodebulan();
			$nbulan = $this->M_global->_namabulan($bulan);
			$periode = 'Periode ' . $nbulan . ' - ' . $this->M_global->_periodetahun();
			$d['keu'] = $this->db->query($q1)->result();
			$level = $this->session->userdata('level');
			$akses = $this->M_global->cek_menu_akses($level, 4403);
			$d['akses'] = $akses;
			$d['periode'] = $periode;
			$d['ppn'] = $this->db->query("SELECT * FROM tbl_pajak where kodetax='PPN'")->row();
			$this->load->view('penjualan/v_retur_jual_cabang', $d);
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
				$ceknama = $this->db->get_where("tbl_apoposting", ['resepno' => $header->resepno])->row();
				$namapas = $ceknama->namapas;
			}

			$depo = $this->db->get_where("tbl_depo", ["depocode" => $header->gudang])->row();
			$depo_ket = $depo->keterangan;

			$nama = $this->db->get_where("tbl_namers", ["koders" => $header->rekmed])->row();

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
			$judul = array('RETURN PENJUALAN RESEP');
			$pdf->FancyRow2(10, $judul, $fc,  $border, $align, $style, $size, $max);
			$size  = array('10');
			$align = array('L');
			$border = array('');
			$judul = array('Kepada Yth:');
			$pdf->FancyRow2(10, $judul, $fc,  $border, $align, $style, $size, $max);



			$pdf->ln(1);
			$pdf->setfont('Arial', 'B', 10);
			$pdf->SetWidths(array(20, 5, 80, 20, 5, 60));
			$border = array('T', 'T', 'T', 'T', 'T', 'T');
			$fc     = array('0', '0', '0', '0', '0', '0');
			$pdf->SetFillColor(230, 230, 230);
			$pdf->setfont('Arial', '', 9);


			$pdf->FancyRow(array('No. TR', ':', $header->returno, 'Dari', ':', $nama->namars), $fc, $border);
			$border = array('', '', '', '', '', '');
			$pdf->FancyRow(array('Resep', ':', $header->resepno, 'Kode RS', ':', "$header->rekmed"), $fc, $border);

			$pdf->FancyRow(array('Pro', ':', $depo_ket, '', '', ""), $fc, $border);

			$pdf->ln(2);
			$pdf->SetWidths(array(10, 25, 50, 20, 20, 20, 20, 25));
			$border = array('LTB', 'TB', 'TB', 'TB', 'TB', 'TB', 'TB', 'TBR');
			$align  = array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C');
			$pdf->setfont('Arial', 'B', 9);
			$pdf->SetAligns(array('L', 'C', 'R'));
			$fc = array('0', '0', '0', '0', '0', '0', '0', '0');
			$judul = array('No.', 'Kode', 'Nama Barang', 'Qty', 'Satuan', 'Harga', 'Discount', 'Total');
			$pdf->FancyRow2(8, $judul, $fc, $border, $align);
			$border = array('', '', '');
			$pdf->setfont('Arial', '', 9);
			$tot = 0;
			$subtot = 0;
			$tdisc  = 0;
			$border = array('', '', '', '', '', '', '', '');

			$align  = array('L', 'L', 'L', 'R', 'C', 'R', 'R', 'R');
			$fc = array('0', '0', '0', '0', '0', '0', '0', '0');
			$max = array(8, 8, 8, 8, 8, 8, 8, 8);
			$pdf->SetFillColor(0, 0, 139);
			$pdf->settextcolor(0);
			$pjk = $this->db->query("SELECT * FROM tbl_pajak where kodetax='PPN'")->row();
			$ppn = $pjk->prosentase / 100;
			$ppny = $ppn + 5 / 100;
			$no = 1;
			$totitem = 0;
			$tot = 0;
			$tot1 = 0;
			$totppn = 0;
			$totdis = 0;
			foreach ($detil as $db) {
				$totdis += $db->discountrp;
				// $totppn += $db->ppnrp;
				$tot += ($db->qtyretur * $db->price) - $db->discountrp;
				$tot1 += ($db->qtyretur * $db->price);
				$pdf->FancyRow2(5, array(
					$no,
					$db->kodebarang,
					$db->namabarang,
					$db->qtyretur,
					$db->satuan,
					number_format($db->price),
					number_format($db->discountrp),
					number_format(($db->qtyretur * $db->price) - $db->discountrp),

				), $fc, $border, $align);

				$no++;
			}
			$totppn = ($tot1 * $ppny);

			$pdf->SetWidths(array(165, 25));
			$border = array('T', 'T');
			$align  = array('R', 'R');
			$style = array('B', 'B');
			$size = array('', '');
			$max = array('', '');
			$pdf->FancyRow2(5, array(
				'Subtotal',
				number_format($tot1),

			), $fc,  $border, $align, $style, $size, $max);
			$pdf->SetWidths(array(165, 25));
			$border = array('', '');
			$align  = array('R', 'R');
			$style = array('B', 'B');
			$size = array('', '');
			$max = array('', '');
			$pdf->FancyRow2(5, array(
				'Diskon',
				number_format($totdis),

			), $fc,  $border, $align, $style, $size, $max);
			$pdf->SetWidths(array(165, 25));
			$border = array('', '');
			$align  = array('R', 'R');
			$style = array('B', 'B');
			$size = array('', '');
			$max = array('', '');
			$pdf->FancyRow2(5, array(
				'PPN',
				number_format($totppn),

			), $fc,  $border, $align, $style, $size, $max);

			$pdf->SetWidths(array(165, 25));
			$border = array('B', 'B');
			$align  = array('R', 'R');
			$style = array('B', 'B');
			$size = array('', '');
			$max = array('', '');
			$pdf->FancyRow2(5, array(
				'Total Net',
				number_format($tot),

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
			$pdf->ln(5);
			$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
			$judul = array('', '', '', 'Cap & Tanda Tangan');
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


			$pdf->setTitle($param);
			$pdf->AliasNbPages();
			$pdf->output($param . '.PDF', 'I');
		} else {

			header('location:' . base_url());
		}
	}

	public function entri()
	{
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$pjk = $this->db->query("SELECT * FROM tbl_pajak where kodetax='PPN'")->row();
			$pajak = $pjk->prosentase / 100;
			$data = [
				'pajak' => $pajak,
			];
			$this->load->view('penjualan/v_penjualan_retur_cabang_add', $data);
		} else {
			header('location:' . base_url());
		}
	}

	public function data_awal()
	{
		$kode = $this->input->get('kode');
		$resepno = $this->input->get('resepno');
		$cabang = $this->session->userdata('unit');
		$data_barang = $this->db->query("SELECT * FROM tbl_apodresep WHERE kodebarang = '$kode' AND koders = '$cabang' AND resepno = '$resepno'")->row();
		echo json_encode($data_barang);
	}

	public function getdataresep()
	{
		$nokwitansiobat  = $this->input->get('noresep');
		$header = $this->db->get_where("tbl_apohresep", ["resepno"=>$nokwitansiobat])->row();
		$cust = $this->db->get_where("tbl_penjamin", ["cust_id"=>$header->cust_id])->row();
		$datax  = $this->db->query("SELECT *, (SELECT hargabelippn FROM tbl_barang WHERE kodebarang = tbl_apodresep.kodebarang) as hargabelippn FROM tbl_apodresep WHERE resepno = '$nokwitansiobat'");
		if($datax->num_rows() > 0){
			$data = $datax->result();
		} else {
			$data = $this->db->query("SELECT *, (SELECT hargabelippn FROM tbl_logbarang WHERE kodebarang = tbl_apodresep.kodebarang) as hargabelippn FROM tbl_apodresep WHERE resepno = '$nokwitansiobat'")->result();
		}
		$no = 1;
		$hargapembeli = $cust->farmasirj;
		foreach ($data as $key => $value) {
			$harganya = ($value->hargabelippn * 5 / 100) + $hargapembeli;
?>
			<tr>
				<td width="5%">
					<input type="checkbox" name="cek[]" value="<?= $key ?>" onclick="total_retur();" style="font-weight: bold-;" class="form-control no-padding">
				</td>
				<td width="10%">
					<input name="kode[]" value="<?= $value->kodebarang ?>" id="kode<?= $no ?>" type="text" class="form-control">
				</td>
				<td width="30%">
					<input name="namabarang[]" value="<?= $value->namabarang ?>" id="namabarang<?= $no ?>" type="text" class="form-control">
				</td>
				<td width="5%">
					<input name="qty[]" onchange="totalline(<?= $no ?>);total();" value="<?= number_format($value->qty); ?>" id="qty<?= $no ?>" type="text" class="form-control rightJustified">
				</td>
				<td width="10%">
					<input name="sat[]" id="sat<?= $no ?>" type="text" class="form-control " onkeypress="return tabE(this,event)" value="<?= $value->satuan ?>" readonly>
				</td>
				<td width="10%">
					<input name="harga[]" onchange="totalline(<?= $no ?>)" value="<?= number_format($value->price) ?>" id="harga<?= $no ?>" type="text" class="form-control rightJustified" readonly>
				</td>
				<td width="5%">
					<!-- <a class="btn default" id="lupharga<?= $no ?>" data-toggle="modal" href="#lupharga" onclick="getidharga(this.id)"><i class="fa fa-search"></i></a> -->
					<input class="form-control" type="checkbox" checked disabled id="ppn<?= $no ?>" name="ppn[]">
				</td>
				<td width="5%">
					<input class="form-control" type="text" id="disc<?= $no ?>" name="disc[]" value="<?= $value->discount; ?>" onchange="totalline_x(<?= $no; ?>);">
				</td>
				<td width="10%">
					<input name="discrp[]" onchange="totalline(<?= $no ?>);total()" value="<?= number_format($value->discrp) ?>" id="discrp<?= $no ?>" type="text" class="form-control rightJustified ">
				</td>
				<td width="15%">
					<input name="jumlah[]" id="jumlah<?= $no ?>" type="text" value="<?= number_format($value->totalrp) ?>" class="form-control rightJustified" size="40%" onchange="total()" readonly>
				</td>
			</tr>
<?php
			$no++;
		}
	}

	public function get_cust($cust_id){
		$data = $this->db->query("SELECT * FROM tbl_penjamin WHERE cust_id = '$cust_id'")->row();
		echo json_encode($data);
	}

	public function getdataresep2()
	{
		$nokwitansiobat  = $this->input->get('noresep');
		$query = "SELECT 
			(SELECT cust_nama FROM tbl_penjamin WHERE cust_id=tbl_apohresep.cust_id) AS cust_nama,
			(SELECT namars FROM tbl_namers WHERE koders = tbl_apoposting.rekmed) AS namars, 
			DATE(tbl_apoposting.tglresep)AS tgll,
			tbl_apoposting.*, tbl_apohresep.cust_id, 
			(SELECT keterangan FROM tbl_depo WHERE depocode=tbl_apoposting.gudang) AS gudangnya 
		FROM tbl_apoposting 
		JOIN tbl_apohresep ON tbl_apohresep.resepno=tbl_apoposting.resepno 
		WHERE tbl_apoposting.resepno = '$nokwitansiobat'";
		$data  = $this->db->query($query)->row();
		echo json_encode($data);
	}

	public function save_one()
	{
		$cabang = $this->session->userdata('unit');
		$nobukti  = urut_transaksi('APORETURJUAL', 16);
		$noresep = $this->input->post('kwiobat');
		$rekmed = $this->input->post('rekmed');
		$tanggal = $this->input->post('tanggal');
		$gudang = $this->input->post('gudang');
		$alasan = $this->input->post('alasan');
		$userid = $this->session->userdata('username');
		$total   = $this->input->get('vtotal');
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			if ($noresep != '' && $cabang != '' && $nobukti != '') {
				$data = [
					'koders'  => $cabang,
					'returno' => $nobukti,
					'resepno' => $noresep,
					'noreg'  => '',
					'rekmed'  => $rekmed,
					'tglretur' => date('Y-m-d', strtotime($tanggal)),
					'gudang' => $gudang,
					'username' => $userid,
					'totalnet' => $total,
					'alasan' => $alasan,
					'jamreturjual' => date('H:i:s'),
				];
				$this->db->insert('tbl_apohreturjual', $data);
			}
			$getnamapas = $this->db->query("SELECT date(tglresep)as tgll,tbl_apoposting.* from tbl_apoposting where resepno = '$noresep'")->row();
			$psn = $this->db->get_where('tbl_namers', ['koders' => $rekmed])->row();
			if ($psn->namars != null || $psn->namars != '') {
				$namapas = $psn->namars;
			} else {
				$namapas = $getnamapas->namapas;
			}
			$data_apoposting = [
				'koders' => $cabang,
				'resepno' => $noresep,
				'tglresep' => date('Y-m-d', strtotime($tanggal)),
				'noreg' => '',
				'rekmed' => $rekmed,
				'namapas' => $namapas,
				'gudang' => $gudang,
				'poscredit' => (-$total),
				'username' => $userid,
				'posting' => 1,
			];
			$this->db->insert('tbl_apoposting', $data_apoposting);
			$data_pap = [
				'noreg' 				=> $nobukti,
				'ref' 					=> $noresep,
				'koders' 				=> $cabang,
				'rekmed' 				=> '',
				'tglposting' 		=> date('Y-m-d', strtotime($this->input->post('tanggal'))),
				'cust_id' 			=> $this->input->post('cust_id'),
				'jumlahhutang' 	=> (-$total),
				'asal' 					=> 'POLI',
				'namapas' 			=> $this->input->post('nama_pembeli'),
				'username' 			=> $userid,
			];
			$this->db->insert('tbl_pap', $data_pap);
			echo json_encode(['nomor' => $nobukti, 'status' => 1]);
		} else {
			header('location:' . base_url());
		}
	}

	public function update_one()
	{
		$cabang = $this->session->userdata('unit');
		$nobukti  = $this->input->post('nomorbukti');
		$noresep = $this->input->post('kwiobat');
		$rekmed = $this->input->post('rekmed');
		$tanggal = $this->input->post('tanggal');
		$gudang = $this->input->post('gudang');
		$alasan = $this->input->post('alasan');
		$userid = $this->session->userdata('username');
		$total   = $this->input->get('vtotal');
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$header = $this->db->get_where("tbl_apohreturjual", ["returno" => $nobukti])->row();
			$detail = $this->db->get_where("tbl_apodreturjual", ["returno" => $nobukti])->result();
			$total = (int)$header->totalnet;
			$this->db->query("DELETE FROM tbl_pap WHERE noreg = '$nobukti'");
			foreach ($detail as $d) {
				$cekbarangx = $this->db->get_where("tbl_barang", ["kodebarang" => $d->kodebarang]);
				if ($cekbarangx->num_rows() > 0) {
					$_qty = (int)$d->qtyretur;
					$_kode = $d->kodebarang;
					$this->db->query("UPDATE tbl_barangstock set terima=terima-$_qty, saldoakhir=saldoakhir-$_qty where kodebarang = '$_kode' and koders = '$header->koders' and gudang = '$header->gudang'");
				} else {
					$_qty = (int)$d->qtyretur;
					$_kode = $d->kodebarang;
					$this->db->query("UPDATE tbl_apostocklog set terima=terima-$_qty, saldoakhir=saldoakhir-$_qty where kodebarang = '$_kode' and koders = '$header->koders' and gudang = '$header->gudang'");
				}
			}
			$this->db->query("DELETE FROM tbl_apoposting WHERE resepno = '$noresep' and poscredit < 0");
			$this->db->delete("tbl_apodreturjual", ["returno" => $nobukti]);
			$this->db->delete("tbl_apohreturjual", ["returno" => $nobukti]);
			if ($noresep != '' && $cabang != '' && $nobukti != '') {
				$data = [
					'koders'  => $cabang,
					'returno' => $nobukti,
					'resepno' => $noresep,
					'noreg'  => '',
					'rekmed'  => $rekmed,
					'tglretur' => date('Y-m-d', strtotime($tanggal)),
					'gudang' => $gudang,
					'username' => $userid,
					'totalnet' => $total,
					'alasan' => $alasan,
					'jamreturjual' => date('H:i:s'),
				];
				$this->db->insert('tbl_apohreturjual', $data);
			}
			$getnamapas = $this->db->query("SELECT date(tglresep)as tgll,tbl_apoposting.* from tbl_apoposting where resepno = '$noresep'")->row();
			$psn = $this->db->get_where('tbl_namers', ['koders' => $rekmed])->row();
			if ($psn->namars != null || $psn->namars != '') {
				$namapas = $psn->namars;
			} else {
				$namapas = $getnamapas->namapas;
			}
			$data_apoposting = [
				'koders' => $cabang,
				'resepno' => $noresep,
				'tglresep' => date('Y-m-d', strtotime($tanggal)),
				'noreg' => '',
				'rekmed' => $rekmed,
				'namapas' => $namapas,
				'gudang' => $gudang,
				'poscredit' => (-$total),
				'username' => $userid,
				'posting' => 1,
			];
			$this->db->insert('tbl_apoposting', $data_apoposting);
			$data_pap = [
				'noreg' 				=> $nobukti,
				'ref' 					=> $noresep,
				'koders' 				=> $cabang,
				'rekmed' 				=> '',
				'tglposting' 		=> date('Y-m-d', strtotime($this->input->post('tanggal'))),
				'cust_id' 			=> $this->input->post('cust'),
				'jumlahhutang' 	=> (-$total),
				'asal' 					=> 'POLI',
				'namapas' 			=> $this->input->post('nama_pembeli'),
				'username' 			=> $userid,
			];
			$this->db->insert('tbl_pap', $data_pap);
			echo json_encode(['nomor' => $nobukti, 'status' => 1]);
		} else {
			header('location:' . base_url());
		}
	}

	public function save_multi()
	{
		$kodebarang = $this->input->get('kodebarang');
		$resepno = $this->input->get('resepno');
		$loop = $this->input->get('loop');
		$satuan = $this->input->get('satuan');
		$jumlah = $this->input->get('jumlah');
		$dsc1 = $this->input->get('dsc1');
		$dsc = $this->input->get('dsc');
		$ppn = $this->input->get('ppn');
		$apodresep = $this->db->get_where('tbl_apodresep', ['resepno' => $resepno, 'kodebarang' => $kodebarang])->row_array();
		$cabang = $this->session->userdata('unit');
		$gudang = $this->input->post('gudang');
		$rekmed = $this->input->post('rekmed');
		$tanggal = $this->input->post('tanggal');
		$userid = $this->session->userdata('username');
		$noresep = $this->input->post('kwiobat');
		$qty = (int)$this->input->get('qty');
		$harga = (int)$this->input->get('harga');
		$cek = $this->session->userdata('level');
		$ppnxx = $this->db->query("SELECT * FROM tbl_pajak where kodetax='PPN'")->row();
		$barangx = $this->db->get_where("tbl_barang", ["kodebarang"=>$kodebarang]);
		if($barangx->num_rows() > 0){
			$barang = $barangx->row();
		} else {
			$barang = $this->db->get_where("tbl_logbarang", ["kodebarang" => $kodebarang])->row();
		}
		$hpp = $barang->hpp;
		if (!empty($cek)) {
			if ($qty <= (int)$apodresep['qty']) {
				$qty_new = $qty;
			} else {
				$qty_new = (int)$apodresep['qty'];
			}
			$data = [
				'koders'     	=> $cabang,
				'returno'    	=> $this->input->get('nobukti'),
				'kodebarang' 	=> $kodebarang,
				'qtyretur'   	=> $qty_new,
				'discount'   	=> $dsc1,
				'satuan'     	=> $satuan,
				'price'      	=> $harga,
				'hpp'      		=> $hpp,
				'discountrp'  => $dsc,
				'ppnrp'      	=> ((($qty_new * $harga) - $dsc) * $ppnxx->prosentase / 100),
				'ppn' 				=> 1,
				'totalrp'    	=> $jumlah,
			];
			if ($kodebarang != '') {
				$this->db->insert('tbl_apodreturjual', $data);
				$barangx = $this->db->query('SELECT * FROM tbl_barang WHERE kodebarang ="' . $kodebarang . '"');
				if ($barangx->num_rows() > 0) {
					$barangs = $this->db->query('select * from tbl_barangstock where koders = "' . $cabang . '" and gudang = "' . $gudang . '" and kodebarang ="' . $kodebarang . '"');
					$barang = $barangs->row();
					$terima_upd = (int)$barang->terima + (int)$qty_new;
					$saldoakhir_upd = (int)$barang->saldoakhir + (int)$qty_new;
					$this->db->set('terima', $terima_upd);
					$this->db->set('saldoakhir', $saldoakhir_upd);
					$this->db->where('koders', $cabang);
					$this->db->where('kodebarang', $kodebarang);
					$this->db->where('gudang', $gudang);
					$this->db->update('tbl_barangstock');
				} else {
					$barang_log = $this->db->query('select * from tbl_apostocklog where koders = "' . $cabang . '" and gudang = "' . $gudang . '" and kodebarang ="' . $kodebarang . '"')->row();
					$terima_log = (int)$barang_log->terima + (int)$qty_new;
					$saldoakhir_log = (int)$barang_log->saldoakhir + (int)$qty_new;
					$this->db->set('terima', $terima_log);
					$this->db->set('saldoakhir', $saldoakhir_log);
					$this->db->where('koders', $cabang);
					$this->db->where('kodebarang', $kodebarang);
					$this->db->where('gudang', $gudang);
					$this->db->update('tbl_apostocklog');
				}
			}
			echo json_encode(['nomor' => $this->input->get('nobukti'), 'status' => 1]);
		} else {
			header('location:' . base_url());
		}
	}

	public function hapus($returno, $resepno)
	{
		$header = $this->db->get_where("tbl_apohreturjual", ["returno" => $returno])->row();
		$detail = $this->db->get_where("tbl_apodreturjual", ["returno" => $returno])->result();
		$total = (int)$header->totalnet;
		$this->db->query("DELETE FROM tbl_pap WHERE noreg = '$returno'");
		foreach ($detail as $d) {
			$cekbarangx = $this->db->get_where("tbl_barang", ["kodebarang" => $d->kodebarang]);
			if ($cekbarangx->num_rows() > 0) {
				$_qty = (int)$d->qtyretur;
				$_kode = $d->kodebarang;
				$this->db->query("UPDATE tbl_barangstock set terima=terima-$_qty, saldoakhir=saldoakhir-$_qty where kodebarang = '$_kode' and koders = '$header->koders' and gudang = '$header->gudang'");
			} else {
				$_qty = (int)$d->qtyretur;
				$_kode = $d->kodebarang;
				$this->db->query("UPDATE tbl_apostocklog set terima=terima-$_qty, saldoakhir=saldoakhir-$_qty where kodebarang = '$_kode' and koders = '$header->koders' and gudang = '$header->gudang'");
			}
		}
		$this->db->query("DELETE FROM tbl_apoposting WHERE resepno = '$resepno' and poscredit < 0");
		$this->db->delete("tbl_apodreturjual", ["returno" => $returno]);
		$this->db->delete("tbl_apohreturjual", ["returno" => $returno]);
		echo json_encode(["status" => 1]);
	}

	public function edit($nomor)
	{
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');
		if (!empty($cek)) {
			$header = $this->db->get_where('tbl_apohreturjual', array('returno' => $nomor))->row();
			$ceking = $this->db->query("SELECT * FROM tbl_apodreturjual d WHERE returno = '$nomor' and kodebarang IN (SELECT kodebarang FROM tbl_barang)")->result();
			if (empty($ceking)) {
				$detil = $this->db->query("SELECT (select namabarang from tbl_logbarang where kodebarang=d.kodebarang) as namabarang, (SELECT satuan1 FROM tbl_logbarang WHERE kodebarang=d.kodebarang) AS satuan1, d.* from tbl_apohreturjual h join tbl_apodreturjual d on h.returno=d.returno where d.returno = '$nomor'");
			} else {
				$detil = $this->db->query("SELECT (select namabarang from tbl_barang where kodebarang=d.kodebarang) as namabarang, (SELECT satuan1 FROM tbl_barang WHERE kodebarang=d.kodebarang) AS satuan1, d.* from tbl_apohreturjual h join tbl_apodreturjual d on h.returno=d.returno where d.returno = '$nomor'");
			}
			$resep = $this->db->query("SELECT * FROM tbl_apohresep WHERE resepno = '$header->resepno'")->row();
			$d['namars'] = $this->db->get_where("tbl_namers", ["koders" => $header->rekmed])->row();
			$d["gudang"] = $this->db->get_where("tbl_depo", ["depocode" => $header->gudang])->row();
			$d['hresep'] = $resep;
			$d['header']  = $header;
			$d['detail']   = $detil->result();
			$d['cust'] = $this->db->get_where("tbl_penjamin", ["cust_id"=>$resep->cust_id])->row();
			$d['jumdata'] = $detil->num_rows();
			$pjk = $this->db->query("SELECT * FROM tbl_pajak where kodetax='PPN'")->row();
			$pajak = $pjk->prosentase / 100;
			$d['pajak'] = $pajak;
			$this->load->view('penjualan/v_retur_cabang_edit', $d);
		} else {
			header('location:' . base_url());
		}
	}
}
