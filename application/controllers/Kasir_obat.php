<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kasir_obat extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			"M_kasirobat",
			"M_global",
			"M_cetak",
			"M_pembuatan_voucher"
		));
		// $this->load->model('M_kasirobat','M_kasirobat');
		// $this->load->model('M_global','M_global');
		// $this->load->model('M_cetak');
		// $this->load->model('M_pembuatan_voucher');
		$this->load->helper('simkeu_nota');
		$this->session->set_userdata('menuapp', '2000');
		$this->session->set_userdata('submenuapp', '2303');
	}

	public function index()
	{
		$cek = $this->session->userdata('username');

		if (!empty($cek)) {
			$this->load->view('klinik/v_kasir_obat');
		} else {
			header('location:' . base_url());
		}
	}

	public function entri()
	{
		$cabang = $this->session->userdata('unit');
		$cek = $this->session->userdata('username');
		$tanggal = date('Y-m-d');

		if (!empty($cek)) {
			$resep = $this->db->query("SELECT (SELECT handphone FROM tbl_pasien b where a.rekmed=b.rekmed)hp,a.* from tbl_apoposting a where keluar=0 and koders='$cabang'")->result();
			//  AND resepno NOT IN (SELECT resepno FROM tbl_apodresep) and a.tglresep = '$tanggal'
			$data['resep'] = $resep;
			$this->load->view('klinik/v_kasirobat_add', $data);
		} else {
			header('location:' . base_url());
		}
	}

	public function filter($param)
	{
		$data  = explode("~", $param);
		$jns   = $data[0];
		$tgl1  = $data[1];
		$tgl2  = $data[2];
		$_tgl1 = date('Y-m-d', strtotime($tgl1));
		$_tgl2 = date('Y-m-d', strtotime($tgl2));

		$cek = $this->session->userdata('username');

		if (!empty($cek)) {
			$resep = $this->db->query("SELECT (SELECT handphone FROM tbl_pasien b where a.rekmed=b.rekmed)hp,a.* from tbl_apoposting a where keluar=0 and  tglresep
				between '$_tgl1' and '$_tgl2'")->result();
			$data['resep'] = $resep;
			$this->load->view('klinik/v_kasirobat_add', $data);
		} else {
			header('location:' . base_url());
		}
	}

	public function ajax_list($param)
	{
		$dat   = explode("~", $param);

		if ($dat[0] == 1) {
			$bulan = date('m');
			$tahun = date('Y');
			$list = $this->M_kasirobat->get_datatables(1, $bulan, $tahun);
		} else {
			$bulan  = date('Y-m-d', strtotime($dat[1]));
			$tahun  = date('Y-m-d', strtotime($dat[2]));
			$list = $this->M_kasirobat->get_datatables(2, $bulan, $tahun);
		}

		$data = array();
		$no = $_POST['start'];
		$jenisbayar = array('', 'Cash', 'Credit Card', 'Debet Card', 'Transfer', 'Online');

		foreach ($list as $unit) {
			$sisa = $unit->totalsemua - $unit->totalbayar;
			$data_pasien = $this->M_global->_datapasien($unit->rekmed);

			if ($data_pasien) {
				$namapas = $data_pasien->namapas;;
				$hp      = $data_pasien->handphone;
				$email   = $data_pasien->email;
			} else {
				$namapas = $hp = $email = '';
			}

			$nokwitansi = $unit->nokwitansi;

			$dataresep = $this->db->query("SELECT * from tbl_apoposting where nokwitansi='$nokwitansi'")->row();

			if ($dataresep) {
				$noresep = $dataresep->resepno;
			} else {
				$noresep = '';
			}

			$vcetak = '?kwitansi=' . $unit->nokwitansi . '&resep=' . $noresep;

			$no++;
			$row = array();
			$row[] = '<center>' . $unit->koders . '</center>';
			$row[] = '<center>' . $unit->username . '<center>';
			$row[] = $unit->noreg;
			$row[] = $unit->nokwitansi;
			$row[] = $namapas;
			$row[] = $unit->rekmed;
			$row[] = date('d-m-Y', strtotime($unit->tglbayar));
			$row[] = $jenisbayar[$unit->jenisbayar];
			$row[] = $unit->totalsemua;
			$row[] = $unit->lainket;
			//if($sisa>0){			
			$row[] =
				'<a class="btn btn-sm btn-primary" href="' . base_url("kasir_obat/edit/" . $unit->id . "") . '" title="Edit" ><i class="glyphicon glyphicon-edit"></i></a>
					
					<a class="btn btn-sm btn-warning" href="' . base_url("kasir_obat/cetak/" . $vcetak . "") . '" target="_blank" title="Cetak" ><i class="glyphicon glyphicon-print"></i></a>

					<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Kirim Email" onclick="send_email(' . "'" . $unit->id . "'" . ",'" . $email . "'" . ')"><i class="glyphicon glyphicon-envelope"></i> </a>
					
					<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Kirim Whatsapp" onclick="send_wa(' . "'" . $unit->id . "'" . ",'" . $hp . "'" . ')"><i class="fa fa-whatsapp"></i> </a>
									
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Batalkan" onclick="Batalkan(' . "'" . $unit->id . "'" . ')"><i class="glyphicon glyphicon-remove"></i> </a>';
			// }

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->M_kasirobat->count_all($dat[0], $bulan, $tahun),
			"recordsFiltered" => $this->M_kasirobat->count_filtered($dat[0], $bulan, $tahun),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_kasirobat->get_by_id($id);
		echo json_encode($data);
	}

	public function edit($id)
	{
		$cek = $this->session->userdata('username');

		if (!empty($cek)) {
			$data['id'] = $id;

			$qkasir = $this->db->query("SELECT tbl_kasir.*, tbl_pasien.namapas
				from tbl_kasir left outer join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
				where tbl_kasir.id = '$id'")->row();

			$data['kasir'] = $qkasir;

			$noreg = $qkasir->noreg;
			$kwitansi = $qkasir->nokwitansi;

			$qresep = $this->db->query("SELECT * from tbl_apoposting where nokwitansi = '$kwitansi'")->row_array();

			$data['kasir'] = $qkasir;
			$data['resep'] = $qresep;
			$this->load->view('klinik/v_kasirobat_edit', $data);
		} else {
			header('location:' . base_url());
		}
	}

	public function ajax_add_bayar()
	{
		$cabang = $this->session->userdata('unit');
		$userid = $this->session->userdata('username');

		$tanggal = date('Y-m-d');
		$jam     = date('H:i:s');

		$noreg = $this->input->post('noreg');
		$noresep = $this->input->post('noresep');
		$fakturpajak = $this->input->post('fakturpajak');
		$totalresep = str_replace(',', '', $this->input->post('reseprp'));
		$diskon = str_replace(',', '', $this->input->post('diskonpr'));
		$diskonrp = str_replace(',', '', $this->input->post('diskonrp'));
		$uangmuka = str_replace(',', '', $this->input->post('uangmuka'));
		$uangmukapakai = str_replace(',', '', $this->input->post('uangmukapakai'));
		$refund = str_replace(',', '', $this->input->post('refund'));
		$voucherrp1     = str_replace(',', '', $this->input->post('voucherrp1'));
		$voucherrp2     = str_replace(',', '', $this->input->post('voucherrp2'));
		$voucherrp3     = str_replace(',', '', $this->input->post('voucherrp3'));

		$totalnet = str_replace(',', '', $this->input->post('totalnet'));
		$bayarcash = str_replace(',', '', $this->input->post('totaltunairp'));
		$bayarcard = str_replace(',', '', $this->input->post('totalelectronicrp'));
		// $totalbayar = str_replace(',','',$this->input->post('uangpasienrp'));
		$kembali = str_replace(',', '', $this->input->post('kembalirp'));
		$admcredit = 0;
		$totalbayar     = $bayarcash + $bayarcard;

		if (empty($fakturpajak)) {
			$fakturpajak = '';
		}

		$kwitansi = urut_transaksi('URUTKWT', 19);

		//$this->_validate();
		if ($totalresep > 0) {
			$ttlresep = (int)$totalresep;
		} else {
			$ttlresep = 0 - (int)$totalresep;
		}
		$data = array(
			'koders' => $cabang,
			'nokwitansi' => $kwitansi,
			'fakturpajak' => $fakturpajak,
			'rekmed' => $this->input->post('rekmed'),
			'noreg' => $noreg,
			'tglbayar' => $tanggal,
			'jambayar' => $jam,
			'totalpoli' => 0,
			'totalresep' => $totalresep,
			'uangmuka' => $uangmukapakai,
			'adm' => 0,
			'diskon' => $diskon,
			'diskonrp' => $diskonrp,
			'admcredit' => $admcredit,
			'totalsemua' => $totalresep,
			'bayarcash' => $bayarcash,
			'bayarcard' => $bayarcard,
			'refund' => $refund,
			// 'voucherrp' => $voucherrp,
			'voucherrp1'    => $voucherrp1,
			'voucherrp2'    => $voucherrp2,
			'voucherrp3'    => $voucherrp3,
			// 'novoucher' => $this->input->post('vouchercode'), saya ganti
			'novoucher1'    => $this->input->post('vouchercode1'),
			'novoucher2'    => $this->input->post('vouchercode2'),
			'novoucher3'    => $this->input->post('vouchercode3'),
			'cust_id'       => $this->input->post('vouchersource'),
			'totalbayar' => $totalbayar,
			'kembali' => $kembali,
			'posbayar' => 'FARMASI',
			'dibayaroleh' => $this->input->post('terimadari'),
			'jenisbayar' => 1,
			'username' => $userid,
			'kembalikeuangmuka'      => $this->input->post('kembaliuang'),
			'namapasien' => $this->input->post('namapasien'),
		);

		$insert = $this->db->insert('tbl_kasir', $data);


		$bayar_bank = $this->input->post('bayar_bank');
		if ($bayar_bank) {
			$bayar_tipe = $this->input->post('bayar_tipe');
			$bayar_nokartu = $this->input->post('bayar_nokartu');
			$bayar_trvalid = $this->input->post('bayar_trvalid');
			$bayar_nilai = $this->input->post('bayar_nilai');
			$bayar_adm = $this->input->post('bayar_adm');
			$bayar_jumlah = $this->input->post('bayar_total');
			$jumdata_bayar = count($bayar_bank);

			for ($i = 0; $i <= $jumdata_bayar - 1; $i++) {
				$total  = str_replace(',', '', $bayar_nilai[$i]);
				$adm    = str_replace(',', '', $bayar_adm[$i]);
				$gtotal = str_replace(',', '', $bayar_jumlah[$i]);
				$admrp  = ($adm / 100) * $total;

				$data_detil   = array(
					'koders' => $cabang,
					'nokwitansi' => $kwitansi,
					'tanggal' => $tanggal,
					'kodebank' => $bayar_bank[$i],
					'nocard' => $bayar_nokartu[$i],
					'cardtype' => $bayar_tipe[$i],
					'nootorisasi' => $bayar_trvalid[$i],
					'jumlahbayar' => $total,
					'admrp' => $admrp,
					'admpersen' => $adm,
					'totalcardrp' => $gtotal,

				);
				if ($gtotal > 0) {
					$insertx = $this->db->insert('tbl_kartukredit', $data_detil);
				}
			}
		}

		$kwitansi_dp = $this->input->post('dp_td_data1');
		if ($kwitansi_dp) {
			$dp_jumlah = $this->input->post('dp_td_data5');
			$jumdata_dp = count($kwitansi_dp);

			//script saya
			for ($i = 0; $i <= $jumdata_dp - 1; $i++) {
				$this->db->query("update tbl_uangmuka set closed = 1 where nokwitansi='$kwitansi_dp[$i]'");
			}
		}

		$totdp_jumlah = str_replace(',', '', $this->input->post('dp_td_data3'));
		if ($totdp_jumlah != null) {
			$sum = array_sum($totdp_jumlah);
			if ($uangmukapakai < $sum) { //mencari apakah uang muka yang dimasukkan ada selisih dengan dp ?
				$rekmed = $this->input->post('rekmed');
				$sisa = $sum - $uangmukapakai;
				$cabang = $this->session->userdata('unit');
				$date = date("Y-m-d");
				$this->db->query("INSERT INTO tbl_uangmuka (koders, nokwitansi, rekmed, tglbayar, jmbayar, jenisbayar, dibayaroleh, posbayar, username, accountno, ket, closed, kwitansivalid)
					VALUES ('$cabang','$kwitansi', '$rekmed', '$date', '$sisa', '1', 'sisa uangmuka dari $rekmed', 'UANG_MUKA', '$userid', '-', 'sisa uangmuka dari $rekmed', 0, '')");
			}
		}

		//$this->db->query("update tbl_regist set keluar=1 where koders = '$cabang' and noreg = '$noreg'");
		$this->db->query("update tbl_apohresep set nokwitansi='$kwitansi', keluar=1 where koders = '$cabang' and resepno = '$noresep'");
		$this->db->query("update tbl_apoposting set nokwitansi='$kwitansi', keluar=1 where koders = '$cabang' and resepno = '$noresep'");

		// pengembalian uang muka dari uang kembalian rp
		$statusKembalian = $this->input->post('kembaliuang');
		$kembali        = str_replace(',', '', $this->input->post('kembalirp'));

		if ($statusKembalian == 1 && $kembali > 0) {
			$cabang = $this->session->userdata('unit');
			$rekmed = $this->input->post('rekmed');
			$kwitansi_uangmuka_kembalian = urut_transaksi('URUTKWT', 19);
			$date = date("Y-m-d");
			date_default_timezone_set('Asia/Jakarta');
			$jam =  date('H:i:s');
			// insert ke tabel uang muka
			$this->db->query("INSERT INTO tbl_uangmuka (koders, nokwitansi, rekmed, tglbayar, jmbayar, jenisbayar, dibayaroleh, posbayar, username, accountno, ket, closed, kwitansivalid)
				VALUES ('$cabang', '$kwitansi_uangmuka_kembalian', '$rekmed', '$date', '$kembali', '1', 'sisa kembalian dari $rekmed', 'UANG_MUKA', '$userid', '-', 'sisa kembalian dari $rekmed', 0, '')");

			// insert ke tabel kasir
			$this->db->query("INSERT INTO tbl_kasir (koders, nokwitansi, noreg, rekmed, tglbayar, jambayar, adm, totalpoli, totalresep, uangmuka, umuka, admcredit, totalsemua, voucherrp1, voucherrp2, voucherrp3, diskonrp, lain, bayarcash, bayarcard, totalbayar, kembali, dibayaroleh, posbayar, username, shipt, kasirtype, kodokter, diskon, prosenksr, lainket)
				VALUES ('$cabang', '$kwitansi_uangmuka_kembalian', '$noreg', '$rekmed', '$date', '$jam', '0', '0', '0', '$kembali', '0', '0', '0', 0, 0, 0, 0, 0, 0, 0, 0, 0, 'sisa kembalian dari $rekmed', 'UANG_MUKA', '$userid', 0, 0, '-', '0', '0', 'sisa kembalian dari $rekmed')");
		}
		// update retur pelanggan
		$retur = $this->input->post('noretur');
		if ($retur) {
			$this->db->query("UPDATE tbl_apodreturjual SET terpakai = 1 WHERE returno = '$retur'");
		}
 
		
		history_log(0 ,'KASIR_OBAT' ,'ADD' ,$kwitansi ,'-');
		echo json_encode(array("status" => TRUE, "nomor" => $kwitansi, "total" => $totalnet));
	}

	public function ajax_add()
	{
		$cabang = $this->session->userdata('unit');
		$userid = $this->session->userdata('username');
		$tanggal = $this->input->post('tanggal');
		$tahun = date('Y');
		$jumlah = str_replace(',', '', $this->input->post('jumlah'));

		$kwitansi = urut_transaksi('URUTKWT', 19);

		$this->_validate();
		$data = array(
			'koders' => $cabang,
			'nokwitansi' => $kwitansi,
			'rekmed' => $this->input->post('pasien'),
			'tglbayar' => $tanggal,
			'jambayar' => $this->input->post('jam'),
			'uangmuka' => $jumlah,
			'totalsemua' => $jumlah,
			'posbayar' => 'UANG_MUKA',
			'dibayaroleh' => $this->input->post('dibayaroleh'),
			'jenisbayar' => $this->input->post('pembayaran'),
			'username' => $userid,
		);

		$insert = $this->M_kasirobat->save($data);

		if ($this->input->post('pembayaran') != 1) {
			$total = str_replace(',', '', $this->input->post('total'));
			$adm = str_replace(',', '', $this->input->post('adm'));
			$gtotal = str_replace(',', '', $this->input->post('grandtotal'));
			$data = array(
				'koders' => $cabang,
				'nokwitansi' => $kwitansi,
				'nocard' => $this->input->post('nokartu'),
				'nootorisasi' => $this->input->post('nootorisasi'),
				'tanggal' => $tanggal,
				'jumlahbayar' => $total,
				'admrp' => $adm,
				'totalcardrp' => $gtotal,
				'kodebank' => $this->input->post('bank'),
			);
			$insert = $this->db->insert('tbl_kartukredit', $data);
		}
		echo json_encode(array("status" => TRUE, "nomor" => $kwitansi, "total" => $totalnet));
	}

	public function ajax_update()
	{
		$this->_validate();

		$data = array(
			'koders' => $this->input->post('kode'),
			'namars' => $this->input->post('nama'),
			'alamat' => $this->input->post('alamat'),
			'kota' => $this->input->post('kota'),
			'phone' => $this->input->post('telpon'),
		);

		$this->M_kasirobat->update(array('koders' => $this->input->post('kode')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function pembatalan($id)
	{
		$cek    = $this->db->query("SELECT * from tbl_kasir where id = '$id'")->row();
		$result = $this->db->query("DELETE from tbl_kasir where id = '$id'");

		if ($result) {
			echo json_encode(array("status" => 1));
		} else {
			echo json_encode(array("status" => 0));
		}
		
		history_log(0 ,'KASIR_OBAT' ,'BATAL' ,$cek->nokwitansi ,'-');
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('pasien') == '') {
			$data['inputerror'][] = 'pasien';
			$data['error_string'][] = 'Harus diisi';
			$data['status'] = FALSE;
		}

		if ($data['status'] === FALSE) {
			echo json_encode($data);
			exit();
		}
	}

	function ctk($cek = '', $thnn = '')
	{
		$cek    	  = '1';
		$chari  	  = '';
		$cekk         = $this->session->userdata('level');
		$unit   	  = $this->session->userdata('unit');
		$unit   	  = $this->session->userdata('unit');
		$profile      = $this->M_global->_LoadProfileLap();
		$nokwitansi   = $this->input->get('kwitansi');
		$noresep      = $this->input->get('resep');
		$nama_usaha   = $profile->nama_usaha;
		$motto        = '';
		$alamat       = '';
		$qkasir       = "SELECT * from tbl_kasir where nokwitansi = '$nokwitansi'";
		$qheader      = "SELECT * from tbl_apohresep where resepno = '$noresep'";
		$qdetil       = "SELECT * from tbl_apodresep where resepno = '$noresep'";
		$qposting     = "SELECT * from tbl_apoposting where resepno = '$noresep'";
		$qkartu       = "SELECT * from tbl_kartukredit where nokwitansi = '$nokwitansi'";

		$kasir        = $this->db->query($qkasir)->row();
		$header       = $this->db->query($qheader)->row();
		$posting      = $this->db->query($qposting)->row();
		$detil        = $this->db->query($qdetil)->result();
		$kartu        = $this->db->query($qkartu)->row();

		$_ket11 = $_ket1 = $_ket2 = $_ket3 = $_ket4 = '';
		if ($kartu) {
			if ($kartu->cardtype == 1) {
				$_ket1 = 'DEBIT NO';
			} elseif ($kartu->cardtype == 2) {
				$_ket1 = 'CREDIT CARD NO';
			} elseif ($kartu->cardtype == 3) {
				$_ket1 = 'TRANSFER NO';
			}

			$_ket4 = angka_rp($kartu->totalcardrp, 0);
			$_ket11 = $kartu->nootorisasi;
			$_ket3 = $kartu->nocard;

			$bank = data_master('tbl_edc', array('bankcode' => $kartu->kodebank));
			if ($bank) {
				$_ket2 = $bank->namabank;
			}

			$_ket4 = angka_rp($kartu->totalcardrp, 0);
			$_ket11 = $kartu->nootorisasi;
			$_ket3 = $kartu->nocard;
		}

		if ($_ket1 != '') {
			$kett1 = $_ket1;
			$tikdu1 = ':';
		} else {
			$kett1 = '';
			$tikdu1 = '';
		}
		if ($_ket2 != '') {
			$kett2 = 'Bank Penerbit';
			$tikdu2 = ':';
		} else {
			$kett2 = '';
			$tikdu2 = '';
		}
		if ($_ket3 != '') {
			$kett3 = 'No Otorisasi';
			$tikdu3 = ':';
		} else {
			$kett3 = '';
			$tikdu3 = '';
		}
		if ($_ket4 != '') {
			$kett4 = 'Total Rp';
			$tikdu4 = ':';
		} else {
			$kett4 = '';
			$tikdu4 = '';
		}

		if ($kasir->bayarcash != '' || $kasir->bayarcash != 0) {
			$kett5 = 'Cash';
			$tikdu5 = ':';
			$nil5 = angka_rp($kasir->bayarcash, 0);
		} else {
			$kett5    = '';
			$tikdu5    = '';
			$nil5     = '';
		}

		if ($kasir->kembali > 0) {

			if ($kasir->kembalikeuangmuka == 0) {
				$kettum   = 'Kembali ke pasien';
				$tikduum   = ':';
				$nilum    = angka_rp($kasir->kembali, 0);
			} else {
				$kettum   = 'Kembali ke Uang muka';
				$tikduum   = ':';
				$nilum    = angka_rp($kasir->kembali, 0);
			}
		} else {

			$kettum    = '';
			$nilum     = '';
			$tikduum    = '';
		}


		if (!empty($cekk)) {
			$kop       = $this->M_cetak->kop($unit);
			$namars    = $kop['namars'];
			$alamat    = $kop['alamat'];
			$alamat2   = $kop['alamat2'];
			$kota      = $kop['kota'];
			$phone     = $kop['phone'];
			$whatsapp  = $kop['whatsapp'];
			$npwp      = $kop['npwp'];


			$chari .= "<table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
						
				<thead>
				<tr>
					<td rowspan=\"6\" align=\"center\">
					<img src=\"" . base_url() . "assets/img/logo.png\"  width=\"70\" height=\"70\" /></td>

					<td colspan=\"20\"><b>
						<tr><td style=\"font-size:10px;border-bottom: none;\"><b><br>$namars</b></td></tr>
						<tr><td style=\"font-size:9px;\">$alamat</td></tr>
						<tr><td style=\"font-size:9px;\">$alamat2</td></tr>
						<tr><td style=\"font-size:9px;\">Wa :$whatsapp    Telp :$phone </td></tr>
						<tr><td style=\"font-size:9px;\">No. NPWP : $npwp</td></tr>
						</b></td>
				</tr> 
				
				
				</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
				<tr>
							<td> &nbsp;
							</td>
				</tr> 
				</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\">
						
				<tr>
							<td style=\"border-top: none;border-right: none;border-left: none;\">
							</td>
				</tr> 
				</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\">
						
				<tr>
							<td style=\"border-top: none;border-right: none;border-left: none;\">
							</td>
				</tr> 
				</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
				<tr>
							<td> &nbsp;
							</td>
				</tr> 
				</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
						
				<tr>
					<td width=\"15%\" style=\"text-align:left;border-bottom: none;\">
						No. Kwitansi</td>
					<td width=\"5%\" style=\"text-align:center;border-bottom: none;\">
						:</td>
					<td width=\"30%\" style=\"text-align:left;border-bottom: none;\">
						$nokwitansi</td>
					<td width=\"15%\" style=\"text-align:left;border-bottom: none;\">
						No. Registrasi</td>
					<td width=\"5%\" style=\"text-align:center;border-bottom: none;\">
						:</td>
					<td width=\"30%\" style=\"text-align:left;border-bottom: none;\">
						$kasir->noreg</td>
				</tr>
				<tr>
					<td width=\"15%\" style=\"text-align:left;border-bottom: none;\">
						Nama Pasien</td>
					<td width=\"5%\" style=\"text-align:center;border-bottom: none;\">
						:</td>
					<td width=\"30%\" style=\"text-align:left;border-bottom: none;\">
						$posting->namapas</td>
					<td width=\"15%\" style=\"text-align:left;border-bottom: none;\">
						No. Member</td>
					<td width=\"5%\" style=\"text-align:center;border-bottom: none;\">
						:</td>
					<td width=\"30%\" style=\"text-align:left;border-bottom: none;\">
						$kasir->rekmed</td>
				</tr>
				<tr>
					<td width=\"15%\" style=\"text-align:left;border-bottom: none;\">
						Jam</td>
					<td width=\"5%\" style=\"text-align:center;border-bottom: none;\">
						:</td>
					<td width=\"30%\" style=\"text-align:left;border-bottom: none;\">
						$kasir->jambayar</td>
				</tr>
				<tr>
					<td width=\"15%\" style=\"text-align:left;border-bottom: none;\">
						Dibayar Oleh</td>
					<td width=\"5%\" style=\"text-align:center;border-bottom: none;\">
						:</td>
					<td width=\"30%\" style=\"text-align:left;border-bottom: none;\">
						$kasir->dibayaroleh</td>
				</tr>
					
					</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">

			<thead>
				<tr>
					<td style=\"border:0\" align=\"center\"><br></td>                
				</tr>
				<tr>
					<td width=\"10%\" style=\"border-left: none;border-right: none;border-bottom: none;\" align=\"left\"><b><br>No.</b></td>
					<td width=\"35%\" style=\"border-left: none;border-right: none;border-bottom: none;\" align=\"left\"><b><br>KETERANGAN</b></td>
					<td width=\"15%\" style=\"border-left: none;border-right: none;border-bottom: none;\" align=\"center\"><b><br>Qty</b></td>
					<td width=\"15%\" style=\"border-left: none;border-right: none;border-bottom: none;\" align=\"center\"><b><br>Diskon</b></td>
					<td width=\"25%\" style=\"border-left: none;border-right: none;border-bottom: none;\" align=\"center\"><b><br>Jumlah Rp</b></td>
				</tr>

				<tr>
					<td width=\"10%\" style=\"border-left: none;border-right: none;border-top: none;\" align=\"center\"><b></b></td>
					<td width=\"35%\" style=\"border-left: none;border-right: none;border-top: none;\" align=\"center\"><b></b></td>
					<td width=\"15%\" style=\"border-left: none;border-right: none;border-top: none;\" align=\"center\"><b></b></td>
					<td width=\"15%\" style=\"border-left: none;border-right: none;border-top: none;\" align=\"center\"><b></b></td>
					<td width=\"25%\" style=\"border-left: none;border-right: none;border-top: none;\" align=\"center\"><b></b></td>
				</tr>
				
			</thead>";

			$lcno    = 0;
			$qty     = 0;
			$tot     = 0;
			$ppn     = 0;
			$dpp     = 0;

			foreach ($detil as $row) {
				$tot          = $tot + $row->totalrp;
				$ppn          = $ppn + $row->ppnrp;
				$dpp          = $dpp + $row->price;
				$namabarang   = $row->namabarang;
				$qty          = $row->qty;
				$discrp       = $row->discrp;
				$totalrp      = $row->totalrp;
				$ndiscrp      = number_format($discrp, 0, '.', ',');
				$ntotalrp     = number_format($totalrp, 0, '.', ',');

				$ctot         = number_format($tot, 2, '.', ',');
				$cppn         = number_format(($tot / 1.1) * 0.1, 2, '.', ',');
				$cdpp         = number_format($tot / 1.1, 2, '.', ',');
				$lcno++;


				$chari .= "<tr>
					<td style=\"border-left: none;border-right: none;border-bottom: none;border-top: none;\" align=\"left\">$lcno</td>
					<td style=\"border-left: none;border-right: none;border-bottom: none;border-top: none;\" align=\"left\">$namabarang  </td>
					<td style=\"border-left: none;border-right: none;border-bottom: none;border-top: none;\" align=\"right\">$qty    </td>
					<td style=\"border-left: none;border-right: none;border-bottom: none;border-top: none;\" align=\"right\">$ndiscrp    </td>
					<td style=\"border-left: none;border-right: none;border-bottom: none;border-top: none;\" align=\"right\">$ntotalrp    </td>
					</tr>";
			}


			$chari .= "</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">
			
			<tr>
					<td width=\"15%\" style=\"border-left: none;border-right: none;border-top: none;\" align=\"center\"><b></b></td>
					<td width=\"35%\" style=\"border-left: none;border-right: none;border-top: none;\" align=\"center\"><b></b></td>
					<td width=\"15%\" style=\"border-left: none;border-right: none;border-top: none;\" align=\"center\"><b></b></td>
					<td width=\"15%\" style=\"border-left: none;border-right: none;border-top: none;\" align=\"center\"><b></b></td>
					<td width=\"20%\" style=\"border-left: none;border-right: none;border-top: none;\" align=\"center\"><b></b></td>
				</tr>

			</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
			<tr><td> &nbsp;</td></tr> 
			</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\">
				<tr>
					<tr>
						<td width=\"15%\" style=\"border: none;\"><b>$kett1</b></td>
						<td width=\"5%\" style=\"border: none;\"><b>$tikdu1</b></td>
						<td width=\"40%\" style=\"border: none;\"><b>$_ket11</b></td>
						
						<td width=\"15%\" style=\"border-bottom: none;border-right: none;\" ><b>Total Rp</b></td>
						<td width=\"5%\" style=\"border-bottom: none;border-left: none;border-left: none;border-right: none;\" ><b>:</b></td>
						<td width=\"20%\" align=\"right\" style=\"border-bottom: none;border-left: none;\"><b>$ctot</b></td>
					</tr>
					<tr>
						<td width=\"15%\" style=\"border: none;\"><b>$kett2</b></td>
						<td width=\"5%\" style=\"border: none;\"><b>$tikdu2</b></td>
						<td width=\"40%\" style=\"border: none;\"><b>$_ket2</b></td>
						
						<td width=\"15%\" style=\"border-bottom: none;border-top: none;border-right: none;\" ><b>Pembulatan</b></td>
						<td width=\"5%\" style=\"border-bottom: none;border-top: none;border-left: none;border-left: none;border-right: none;\" ><b>:</b></td>
						<td width=\"20%\" align=\"right\" style=\"border-bottom: none;border-top: none;border-left: none;\"><b>$ctot</b></td>
					</tr>
					<tr>
						<td width=\"15%\" style=\"border: none;\"><b>$kett3</b></td>
						<td width=\"5%\" style=\"border: none;\"><b>$tikdu3</b></td>
						<td width=\"40%\" style=\"border: none;\"><b>$_ket3</b></td>
						
						<td width=\"15%\" style=\"border-bottom: none;border-top: none;border-right: none;\" ><b>Dpp</b></td>
						<td width=\"5%\" style=\"border-bottom: none;border-top: none;border-left: none;border-left: none;border-right: none;\" ><b>:</b></td>
						<td width=\"20%\" align=\"right\" style=\"border-bottom: none;border-top: none;border-left: none;\"><b>$cdpp</b></td>
					</tr>
					<tr>
						<td width=\"15%\" style=\"border: none;\"><b>$kett4</b></td>
						<td width=\"5%\" style=\"border: none;\"><b>$tikdu4</b></td>
						<td width=\"40%\" style=\"border: none;\"><b>$_ket4</b></td>
						
						<td width=\"15%\" style=\"border-top: none;border-right: none;\" ><b>Ppn</b></td>
						<td width=\"5%\" style=\"border-top: none;border-left: none;border-left: none;border-right: none;\" ><b>:</b></td>
						<td width=\"20%\" align=\"right\" style=\"border-top: none;border-left: none;\"><b>$cppn</b></td>
					</tr>

					<tr>
						<td width=\"15%\" style=\"border: none;\"><b>$kett5</b></td>
						<td width=\"5%\" style=\"border: none;\"><b>$tikdu5</b></td>
						<td width=\"40%\" style=\"border: none;\"><b>$nil5</b></td>
						
					</tr>

					<tr>
						<td width=\"15%\" style=\"border: none;\"><b>$kettum</b></td>
						<td width=\"5%\" style=\"border: none;\"><b>$tikduum</b></td>
						<td width=\"40%\" style=\"border: none;\"><b>$nilum</b></td>
						
					</tr>
					

				</tr>  
			</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
				<tr><td> &nbsp;</td></tr> 
			</table>";


			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
				<tr><td> &nbsp;</td></tr> 
				<tr><td> &nbsp;</td></tr> 
				<tr><td> &nbsp;</td></tr> 
			</table>";


			$chari .= "<table style=\"float:right;border-collapse:collapse;font-family: Times New Roman; font-size:11px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
				<tr>                    
					<td align=\"center\" width=\"20%\">&nbsp;</td>
					<td align=\"center\" width=\"25%\">&nbsp;</td>
					<td align=\"center\" width=\"30%\"><b>$kota," . date('d-m-Y', strtotime($kasir->tglbayar)) . "</b></td>
				</tr>
				<tr>                    
					<td align=\"center\" width=\"20%\"><b>Pasien</b></td>
					<td align=\"center\" width=\"25%\"><b>Ruang Obat</b></td>
					<td align=\"center\" width=\"30%\"><b>$namars <br>CASHIER</b></td>
				</tr>
				
				<tr>                   
					<td align=\"center\" width=\"20%\">&nbsp;</td>
					<td align=\"center\" width=\"25%\">&nbsp;</td>
					<td align=\"center\" width=\"30%\">&nbsp;</td>
				</tr>                              
				<tr>                    
					<td align=\"center\" width=\"20%\">&nbsp;</td>
					<td align=\"center\" width=\"25%\">&nbsp;</td>
					<td align=\"center\" width=\"30%\">&nbsp;</td>
				</tr>
				<tr>
					<td align=\"center\" width=\"20%\">$posting->namapas
					<td align=\"center\" width=\"25%\"><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
					<td align=\"center\" width=\"30%\">$kasir->username</u>
				</tr>                              
				<tr>                  
					<td align=\"center\" width=\"20%\">&nbsp;</td>
					<td align=\"center\" width=\"25%\">&nbsp;</td>
					<td align=\"center\" width=\"30%\">&nbsp;</td>
				</tr>
			</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
				<tr><td> &nbsp;</td></tr> 
				<tr><td> &nbsp;</td></tr> 
				<tr><td> &nbsp;</td></tr> 
			</table>";
			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">
				<tr>
					<td style=\"border-left: none;border-right: none;\" width=\"85%\"><b>*) SETIAP PEMBELIAN OBAT YANG TELAH DI PROSES BAYAR, 
					TIDAK DAPAT DIKEMBALIKAN ATAU DI RETUR</b></td>
				</tr> 
			</table>";

			$data['prev']   = $chari;
			$judul          = $nokwitansi;

			switch ($cek) {
				case 0;
					echo ("<title>$judul</title>");
					echo ($chari);
					break;

				case 1;

					$this->M_cetak->mpdf('P', 'A4', $judul, $chari, 'KWITANSI-OBAT-' . $nokwitansi . '.PDF', 10, 10, 10, 2);
					break;
			}
		} else {

			header('location:' . base_url());
		}
	}

	public function cetak()
	{
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');
		if (!empty($cek)) {
			$nokwitansi = $this->input->get('kwitansi');
			$noresep    = $this->input->get('resep');

			$profile = data_master('tbl_namers', array('koders' => $unit));
			$nama_usaha = $profile->namars;
			$alamat1  = $profile->alamat;
			$alamat2  = $profile->kota;

			$qkasir   = "SELECT * from tbl_kasir where nokwitansi = '$nokwitansi'";
			$qheader     = "SELECT * from tbl_apohresep where resepno = '$noresep'";
			$qdetil      = "SELECT * from tbl_apodresep where resepno = '$noresep'";
			$qposting    = "SELECT * from tbl_apoposting where resepno = '$noresep'";
			$qkartu      = "SELECT * from tbl_kartukredit where nokwitansi = '$nokwitansi'";
			$queryr        = "SELECT * from tbl_aporacik where resepno = '$noresep' AND koders='$unit'";
			$detil_r        = "SELECT * from tbl_apodetresep where resepno = '$noresep' AND koders='$unit'";
			$racikan 	   = $this->db->query($queryr)->row_array();
			$rck         = $this->db->query($detil_r)->result();
			$aporacik = $this->db->get_where('tbl_aporacik', ['resepno' => $noresep])->row();

			$qvoucher = $this->db->query("SELECT tbl_kasir.*, tbl_pasien.namapas
				from tbl_kasir left outer join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
				where nokwitansi = '$nokwitansi'")->row();

			$kasir       = $this->db->query($qkasir)->row();
			// var_dump($qvoucher);die;

			$header   = $this->db->query($qheader)->row();
			$posting     = $this->db->query($qposting)->row_array();
			$detilx       = $this->db->query($qdetil);
			if($detilx->num_rows() > 0){
				$detil = $detilx->result();
			} else {
				$detil = $this->db->query("SELECT d.*, d.qtyretur as qty, d.discountrp as discrp, (SELECT namabarang FROM tbl_barang WHERE kodebarang = d.kodebarang) as namabarang from tbl_apodreturjual d where d.returno = '$noresep'")->result();
			}
			$kartu       = $this->db->query($qkartu)->row();

			$query_kartu_card	= $this->db->query("SELECT * FROM tbl_kartukredit WHERE nokwitansi = '$nokwitansi'")->result();

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
			//$pdf->FancyRow2(10,$judul, $fc,  $border, $align, $style, $size, $max);
			//$pdf->ln(1);
			$pdf->setfont('Arial', 'B', 10);
			$pdf->SetWidths(array(25, 10, 50, 15, 25, 10, 55));
			$border = array('', '', '', '', '', '', '');
			$fc     = array('0', '0', '0', '0', '0', '0', '0');
			$pdf->SetFillColor(230, 230, 230);
			$pdf->setfont('Arial', '', 8);
			$pdf->FancyRow(array('No. Kwitansi', ':', $nokwitansi, '', 'No. Registrasi', ':', $kasir->noreg), $fc, $border);
			$pdf->FancyRow(array('Nama Pasien', ':', $posting['namapas'], '', 'No. Member', ':', $kasir->rekmed), $fc, $border);
			$pdf->FancyRow(array('Jam', ':', $kasir->jambayar, '', '', '', ''), $fc, $border);
			$pdf->FancyRow(array('Dibayar Oleh', ':', $kasir->dibayaroleh, '', '', '', ''), $fc, $border);

			$pdf->ln(2);

			$pdf->SetWidths(array(20, 60, 40, 35, 35));
			$border = array('TB', 'TB', 'TB', 'TB', 'TB');
			$align  = array('L', 'L', 'R', 'R', 'R');
			$pdf->setfont('Arial', 'B', 8);
			$pdf->SetAligns(array('L', 'C', 'R'));
			//$pdf->SetFillColor(0,0,139);
			//$pdf->settextcolor(255,255,255);
			$fc = array('0', '0', '0', '0', '0', '0');
			$judul = array('No.', 'Keterangan', 'Qty', 'Diskon', 'Jumlah Rp');
			$pdf->FancyRow2(8, $judul, $fc, $border, $align);
			$border = array('', '', '');
			$pdf->setfont('Arial', '', 8);
			$tot = 0;
			$subtot = 0;
			$tdisc  = 0;
			$border = array('', '', '', '', '', '');
			$align  = array('L', 'L', 'R', 'R', 'R', 'R');
			$fc = array('0', '0', '0', '0', '0', '0');
			$pdf->SetFillColor(0, 0, 139);
			$pdf->settextcolor(0);
			$nomor = 1;
			$ppn   = 0;
			$dpp   = 0;

			foreach ($detil as $db) {
				$tot = $tot + $db->totalrp;
				$ppn = $ppn + $db->ppnrp;
				$dpp = $dpp + $db->price;

				$pdf->FancyRow(array(
					$nomor,
					$db->namabarang,
					number_format($db->qty, 0),
					number_format($db->discrp, 0, '.', ','),
					number_format($db->totalrp, 0, '.', ',')
				), $fc, $border, $align);

				$nomor++;
			}
			if ($racikan != null && $rck != null) {
				// foreach ($rck as $rck) {


				// 	$diskon = $rck->qty * $rck->price * $racikan['diskon'] / 100;
				// 	$ttlrp = $rck->qty * $rck->price;
				// 	$pdf->FancyRow(array(
				// 		$nomor,
				// 		("** $rck->namabarang"),
				// 		number_format($rck->qty, 0, ',', '.'),
				// 		'-',
				// 		number_format((!isset($rck->totalrp) ? 0 : $rck->totalrp), 0, ',', '.')
				// 	), $fc, $border, $align);
				// 	$nomor++;
				// }
				if ($aporacik->harga_manual != null || $aporacik->harga_manual != '') {
					$hrgxx = $aporacik->harga_manual;
				} else {
					$hrgxx = $aporacik->totalrp;
				}
				$pdf->FancyRow(array(
					$nomor,
					("**$aporacik->namaracikan"),
					number_format($aporacik->jumlahracik, 0, ',', '.'),
					number_format($aporacik->diskonrp, 0, ',', '.'),
					number_format((!isset($hrgxx) ? 0 : $hrgxx), 0, ',', '.')
				), $fc, $border, $align);
				$nomor++;
			}

			$total_voucher  = $kasir->voucherrp1 + $kasir->voucherrp2 + $kasir->voucherrp3;
			// var_dump($total_voucher);die;
			$total_potongan = $total_voucher + $kasir->diskonrp;
			$actual_total	= $kasir->totalresep - $total_potongan;

			// if($kasir->totalrp < 1){
			// $pdf->FancyRow(array('', 'Adm', '', '' ,'' ),$fc, $border, $align);
			if ($kasir->voucherrp1 != 0) {
				$vc1 = $kasir->voucherrp1;
			} else {
				$vc1 = 0;
			}
			if ($kasir->voucherrp2 != 0) {
				$vc2 = $kasir->voucherrp2;
			} else {
				$vc2 = 0;
			}
			if ($kasir->voucherrp3 != 0) {
				$vc3 = $kasir->voucherrp3;
			} else {
				$vc3 = 0;
			}
			if ($vc1 != 0) {
				$pdf->FancyRow(array('Voucher ',  $kasir->novoucher1, '', '', '' . number_format($kasir->voucherrp1, 0, '.', ',')), $fc, $border, $align);
			}
			if ($vc2 != 0) {
				$pdf->FancyRow(array('Voucher',  $kasir->novoucher2, '', '', '' . number_format($kasir->voucherrp2, 0, '.', ',')), $fc, $border, $align);
			}
			if ($vc3 != 0) {
				$pdf->FancyRow(array('Voucher',  $kasir->novoucher3, '', '', '' . number_format($kasir->voucherrp3, 0, '.', ',')), $fc, $border, $align);
			}
			// if($kasir->voucherrp1 != "" || $kasir->voucherrp2 != "" || $kasir->voucherrp3 != ""){
			// 	$pdf->FancyRow(array('Voucher',  $kasir->novoucher1,'', '',''. number_format($total_voucher, 0, '.', ',')),$fc, $border, $align);
			// }

			// if($kasir->diskonrp != ""){
			// 	$pdf->FancyRow(array($nomor+1, 'Diskon', '', '','-'. number_format($kasir->diskonrp, 0, '.', ',')),$fc, $border, $align);
			// }
			// }
			// $pdf->FancyRow(array('', 'Diskon', '', '' ,''),$fc, $border, $align);

			// $_ket11 = $_ket1 = $_ket2 = $_ket3 = $_ket4 = '';
			// if($kartu){
			// if($kartu->cardtype==1){
			// 	$_ket1 = 'DEBIT NO'; 
			// } elseif($kartu->cardtype==2){
			// 	$_ket1 = 'CREDIT CARD NO'; 
			// }	elseif($kartu->cardtype==3){
			// 	$_ket1 = 'TRANSFER NO'; 
			// }	
			// $bank = data_master('tbl_edc',array('bankcode' => $kartu->kodebank));			  
			// if($bank){
			// 	$_ket2 = $bank->namabank;  
			// }

			// $_ket4 = angka_rp($kartu->totalcardrp,0);
			// $_ket11= $kartu->nootorisasi;
			// $_ket3 = $kartu->nocard;

			// }	

			$pdf->SetWidths(array(30, 15, 45, 30, 35, 35));

			$border    = array('', '', '', '', '', '');
			$align     = array('L', 'C', 'L', 'L', 'R', 'R');
			$fc        = array('0', '0', '0', '0', '0', '0');
			$style     = array('B', 'B', 'B', 'B', 'B', 'B');
			$size      = array('', '', '', '', '', '');
			$border    = array('', '', '', '', '', '');
			$border    = array('T', 'T', 'T', 'T', 'T', 'T');
			$pdf->FancyRow(array('', '', '', '', '', ''), $fc, $border, $align, $style, $size);

			$fc = array('0', '0', '0', '0', '0', '0');
			$border = array('', '', '', 'LT', 'T', 'RT');
			$lastborder = array('', '', '', 'LB', 'B', 'RB');
			$sideborder = array('', '', '', 'L', '', 'R');

			// $pdf->FancyRow(array(($_ket1==''?'':$_ket1),($_ket1==''?'':':'),$_ket11,'Total Rp',':',number_format($tot,2,'.',',')),$fc, $border, $align, $style, $size);
			// $border    = array('','','','L','','R');
			// $pdf->FancyRow(array(($_ket2==''?'':'Bank Penerbit'),($_ket2==''?'':':'),$_ket2,'Pembulatan',':',number_format($tot,2,'.',',')),$fc, $border, $align, $style, $size);
			// $pdf->FancyRow(array(($_ket3==''?'':'No Otorisasi'),($_ket3==''?'':':'),$_ket3,'Dpp',':',number_format($tot/1.1,2,'.',',')),$fc, $border, $align, $style, $size);
			// // $border = array('','','','LB','B','BR');
			// $pdf->FancyRow(array(($_ket4==''?'':'Total Rp'),($_ket4==''?'':':'),$_ket4,'Ppn',':',number_format(($tot/1.1)*0.1,2,'.',',')),$fc, $border, $align, $style, $size);
			// $border = array('','','','','','');
			// $pdf->FancyRow(array(($kasir->bayarcash>0?'Cash Rp':''),($kasir->bayarcash>0?':':''),angka_rp($kasir->bayarcash,0),'','',''),$fc, $border, $align, $style, $size);

			// $pdf->FancyRow(array(($kasir->voucherrp1 > 0)?'Voucher':'',($kasir->voucherrp1 > 0)?':':'',($kasir->voucherrp1 > 0)? angka_rp($kasir->voucherrp1,0) : "" ),$fc, $border, $align, $style, $size);
			$pdf->FancyRow(array(($kasir->bayarcash > 0) ? 'Cash Rp' : '', ($kasir->bayarcash > 0) ? ':' : '', ($kasir->bayarcash > 0) ? angka_rp($kasir->bayarcash, 0) : "", 'Total Rp', ':', number_format($actual_total, 2, '.', ',')), $fc, $border, $align, $style, $size);
			// $pdf->FancyRow(array(($kasir->uangmuka > 0) ? "Uang Muka Rp" : "", ($kasir->uangmuka > 0) ? ":" : "", ($kasir->uangmuka > 0) ? angka_rp($kasir->uangmuka, 0) : "", 'Pembulatan', ':', number_format($actual_total, 2, '.', ',')), $fc, $sideborder, $align, $style, $size); //saya rubah dari $_ket3 menjadi $pecah
			$pdf->FancyRow(array(($kasir->uangmuka > 0) ? "Uang Muka Rp" : "", ($kasir->uangmuka > 0) ? ":" : "", ($kasir->uangmuka > 0) ? angka_rp($kasir->uangmuka, 0) : "", 'Pembulatan', ':', number_format(0, 2, '.', ',')), $fc, $lastborder, $align, $style, $size); //saya rubah dari $_ket3 menjadi $pecah
			// $pdf->FancyRow(array('', '', '', 'Dpp', ':', number_format($actual_total / 1.1, 2, '.', ',')), $fc, $sideborder, $align, $style, $size);
			// $pdf->FancyRow(array('', '', '', 'Ppn', ':', number_format(($actual_total / 1.1) * 0.1, 2, '.', ',')), $fc, $lastborder, $align, $style, $size);
			$pdf->ln(5);

			foreach ($query_kartu_card as $cckey => $ccval) {
				$query_nama_bank	= $this->db->query("SELECT * FROM tbl_edc WHERE bankcode = $ccval->kodebank")->row();
				switch ($ccval->cardtype) {
					case 1:
						$cardType = "DEBIT NO";
						break;
					case 2:
						$cardType = "CREDIT NO";
						break;
					case 3:
						$cardType = "TRANSFER NO";
						break;
					case 4:
						$cardType = "ONLINE";
						break;
				}

				$nocard_length	= count($ccval->nocard) - 4;
				$nocard			= substr($ccval->nocard, 0, $nocard_length) . "XXXX";

				$pdf->FancyRow(array("Bank Penerbit", ":", $query_nama_bank->namabank), $fc, 0, $align, $style, $size);
				$pdf->FancyRow(array($cardType, ":", $nocard), $fc, 0, $align, $style, $size);
				$pdf->FancyRow(array("Approval Code", ":", $ccval->nootorisasi), $fc, 0, $align, $style, $size);
				$pdf->FancyRow(array("Nominal", ":", "Rp " . number_format($ccval->jumlahbayar, 0, ',', '.')), $fc, 0, $align, $style, $size);
				$pdf->ln(5);
			}
			$pdf->ln(5);

			if ($kasir->kembalikeuangmuka == 0) {
				$pdf->FancyRow(array(($kasir->kembali > 0 ? 'Kembali ke pasien' : ''), ($kasir->kembali > 0 ? ':' : ''), angka_rp($kasir->kembali, 0), '', '', ''), $fc, 0, $align, $style, $size);
			} else {
				$pdf->FancyRow(array(($kasir->kembali > 0 ? 'Kembali ke Uang muka' : ''), ($kasir->kembali > 0 ? ':' : ''), angka_rp($kasir->kembali, 0), '', '', ''), $fc, 0, $align, $style, $size);
			}

			$pdf->settextcolor(0);

			$pdf->SetWidths(array(63, 64, 63));
			$border = array('', '', '');
			$align  = array('C', 'C', 'C');
			$pdf->FancyRow(array('', '', $alamat2 . ', ' . date('d-m-Y', strtotime($kasir->tglbayar))), 0, $border, $align);
			$pdf->FancyRow(array('', '', $nama_usaha), 0, $border, $align);
			$pdf->FancyRow(array('Pasien', 'Ruang Obat', 'Cashier'), 0, $border, $align);
			$pdf->ln(20);
			$pdf->SetWidths(array(63, 64, 63));
			$pdf->SetFont('Arial', '', 8);
			$pdf->SetAligns(array('C', 'C', 'C'));
			$border = array('', '', '');
			$pdf->FancyRow(array('', '', ''), 0, $border, $align);
			$border = array('', '', '');
			$pdf->FancyRow(array($posting['namapas'], '', $kasir->username), 0, $border, $align);



			$pdf->AliasNbPages();
			$pdf->setTitle($nokwitansi);
			$pdf->output('./uploads/obat/' . $nokwitansi . '.PDF', 'F');
			$pdf->output($nokwitansi . '.PDF', 'I');
		} else {

			header('location:' . base_url());
		}
	}

	public function send_wa()
	{
		$userid   = $this->session->userdata('inv_username');
		$param = $this->input->post('id');

		$data = $this->db->query("select tbl_kasir.*, tbl_pasien.namapas, tbl_pasien.email, tbl_pasien.handphone from tbl_kasir 
			inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed 
			where tbl_kasir.id = '$param'")->row();
		$attched_file  = base_url() . "uploads/obat/" . $data->nokwitansi . ".PDF";
		$mobile = $data->handphone;
		$txtwa   = "KWITANSI OBAT" . "\r\n" .
			"No. Kwitansi : " . $data->nokwitansi . "\r\n" .
			"No. RM : " . $data->rekmed . "\r\n" .
			"Nama Pasien : " . $data->namapas . "\r\n" .
			"Tanggal : " . date('d M Y', strtotime($data->tglbayar)) . "\r\n" .
			"Jumlah  : " . angka_rp($data->totalsemua, 2) . "\r\n" .
			"Rincian Kwitansi klik link berikut: " . "\r\n";


		$txtwa2   = $attched_file;

		$result = send_wa_txt($mobile, $txtwa);
		$result = send_wa_txt($mobile, $txtwa2);
		echo json_encode(array("status" => 1));
	}

	public function send_email()
	{
		$userid   = $this->session->userdata('inv_username');
		$unit = $this->session->userdata('unit');
		$profile = data_master('tbl_namers', array('koders' => $unit));
		$nama_usaha = $profile->namars;
		$email_usaha = $profile->email;

		$param = $this->input->post('id');

		$data = $this->db->query("select tbl_kasir.*, tbl_pasien.namapas, tbl_pasien.email, tbl_pasien.handphone from tbl_kasir 
			inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed 
			where tbl_kasir.id = '$param'")->row();
		$attched_file  = base_url() . "uploads/obat/" . $data->nokwitansi . ".PDF";
		$mobile = $data->handphone;
		$ready_message   = "KWITANSI OBAT" . "\r\n" .
			"No. Kwitansi : " . $data->nokwitansi . "\r\n" .
			"No. RM : " . $data->rekmed . "\r\n" .
			"Nama Pasien : " . $data->namapas . "\r\n" .
			"Tanggal : " . date('d M Y', strtotime($data->tglbayar)) . "\r\n" .
			"Jumlah  : " . angka_rp($data->totalsemua, 2) . "\r\n";


		$email_tujuan = trim($data->email);

		$server_subject = "KWITANSI OBAT ";

		$email_usaha = "support@gmail.com";
		$this->load->library('email');
		$this->email->from($email_usaha, $nama_usaha);
		$this->email->to($email_tujuan);
		$this->email->subject($server_subject);
		$this->email->message($ready_message);
		$this->email->attach($attched_file);

		if ($this->email->send()) {
			echo json_encode(array("status" => 1));
		} else {
			echo json_encode(array("status" => 0));
		}
	}

	public function getdataretur()
	{
		$kode = $this->input->get('noreg');
		$data = $this->db->query("SELECT sum(totalrp) as totalrp, tbl_apohreturjual.returno FROM tbl_apohreturjual JOIN tbl_apodreturjual ON tbl_apodreturjual.returno = tbl_apohreturjual.returno WHERE rekmed = '$kode' AND terpakai = '0'")->row();

		echo json_encode($data);
	}
	public function getdataretur1()
	{
		$kode = $this->input->get('noresep');
		$data = $this->db->query("SELECT totalnet as totalrp, tbl_apohreturjual.returno FROM tbl_apohreturjual JOIN tbl_apodreturjual ON tbl_apodreturjual.returno = tbl_apohreturjual.returno WHERE resepno = '$kode' AND terpakai = '0'")->row();

		echo json_encode($data);
	}

	public function check_voucher()
	{
		$cust_id = $this->input->post("cust");
		$novoucher = $this->input->post("voucher");

		$check_voucher = $this->db->query("SELECT a.novoucher, a.nominal, a.nokir, b.nokir, b.cust_id FROM tbl_vocd AS a LEFT JOIN tbl_voch AS b ON b.nokir = a.nokir WHERE b.cust_id = '$cust_id' AND a.novoucher = '$novoucher' AND a.terpakai = 0");
		$get_voucher = $check_voucher->row();
		if ($check_voucher->num_rows() == 0) {
			echo json_encode(array("status" => 0));
		} else {
			$voucher_arr = array(
				"nominal" => number_format($get_voucher->nominal, 0, ',', '.')
			);
			echo json_encode($voucher_arr);
		}
	}

	public function check_group_voucher($param)
	{
		$query_cgv = $this->db->query("SELECT a.novoucher, a.nominal, a.nokir, b.nokir, b.cust_id FROM tbl_vocd AS a LEFT JOIN tbl_voch AS b ON b.nokir = a.nokir WHERE b.cust_id = '$param' AND a.terpakai = 0");

		if ($query_cgv->num_rows() == 0) {
			echo json_encode(array("status" => 0));
		} else {
			header("content-type:application/json");
			echo json_encode($query_cgv->result());
		}
	}
}