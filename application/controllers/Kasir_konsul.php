<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kasir_konsul extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_kasirkonsul', 'M_kasirkonsul');
		$this->load->model('M_global', 'M_global');
		$this->load->model('M_cetak');
		$this->load->model('M_pembuatan_voucher');
		$this->load->helper('simkeu_nota');
		$this->session->set_userdata('menuapp', '2000');
		$this->session->set_userdata('submenuapp', '2302');
	}

	public function index()
	{
		$cek = $this->session->userdata('username');
		if (!empty($cek)) {
			$this->load->view('klinik/v_kasir_konsul');
		} else {
			header('location:' . base_url());
		}
	}

	public function entri()
	{
		$cek = $this->session->userdata('username');
		if (!empty($cek)) {
			$this->load->view('klinik/v_kasirkonsul_add');
		} else {
			header('location:' . base_url());
		}
	}

	public function edit($id)
	{
		$cek = $this->session->userdata('username');
		$cabang = $this->session->userdata('unit');
		$level = $this->session->userdata('user_level');
		// var_dump($level);die;
		if (!empty($cek)) {
			$data['id'] = $id;


			$qkasir = $this->db->query("SELECT tbl_kasir.*, tbl_pasien.namapas,tbl_pasien.handphone
			from tbl_kasir inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
			where tbl_kasir.id = '$id'")->row();

			$data['kasir'] = $qkasir;
			$noreg = $qkasir->noreg;


			$data['kasir'] = $qkasir;

			$data['konsul'] = $this->db->query("SELECT * from tbl_hpoli inner join tbl_dpoli on tbl_hpoli.noreg=tbl_dpoli.noreg where tbl_hpoli.noreg = '$noreg' and tbl_dpoli.rsnet not like '0.00'")->row();

			$qkonsuld = $this->db->query("SELECT tbl_dpoli.*, tbl_tarif.*, tbl_tarifh.*
			from tbl_dpoli left outer join tbl_tarif on tbl_dpoli.kodetarif=tbl_tarif.kodetarif
			inner join tbl_tarifh on tbl_tarifh.kodetarif=tbl_tarif.kodetarif
			where tbl_tarif.koders='$cabang' and tbl_dpoli.noreg = '$noreg'");

			$qresep = $this->db->query("SELECT * from tbl_apoposting where noreg = '$noreg'");

			// husain add
			$hresep = $this->db->query("SELECT * FROM tbl_apohresep where noreg = '$noreg'")->row();
			if($hresep){
				$dresep = $this->db->query("SELECT sum(discrp) AS diskon_rp FROM tbl_apodresep WHERE resepno = '$hresep->resepno' limit 1")->result();
				foreach($dresep as $dr){
					$data['discr'] = $dr->diskon_rp;
				}
			} else {
				$dresep = 0;
				$data['discr'] = $dresep;
			}
			// end husain

			$pap = $this->db->get_where('tbl_pap', ['noreg' => $noreg, 'koders' => $cabang])->row();

			$data['vs'] = $this->db->query("SELECT cust_nama FROM tbl_penjamin WHERE cust_id = '$qkasir->cust_id'")->row();
			$data['hargavoucher1'] = $this->db->query("SELECT nominal FROM tbl_vocd WHERE novoucher = '$qkasir->novoucher1'")->row();
			$data['hargavoucher2'] = $this->db->query("SELECT nominal FROM tbl_vocd WHERE novoucher = '$qkasir->novoucher2'")->row();
			$data['hargavoucher3'] = $this->db->query("SELECT nominal FROM tbl_vocd WHERE novoucher = '$qkasir->novoucher3'")->row();

			$data['jumdata'] = $qkonsuld->num_rows();
			$data['konsuld'] = $qkonsuld->result();
			$data['resepd']  = $qresep->result();
			$data['register'] = $this->db->query("SELECT * from tbl_regist where noreg = '$noreg'")->row();
			$data['level'] = $level;
			$data['pap'] = $pap;
			$data['jumlah'] = $this->db->get_where("tbl_dpoli", ['noreg' => $noreg])->num_rows();
			$data['kredit'] = $this->db->query("SELECT k.*, b.* FROM tbl_kartukredit k join tbl_edc b on k.kodebank=b.bankcode WHERE k.nokwitansi = '$qkasir->nokwitansi'")->result();

			$this->load->view('klinik/v_biayakonsul_edit', $data);
		} else {
			header('location:' . base_url());
		}
	}


	public function entri_konsul()
	{
		$cek = $this->session->userdata('username');
		if (!empty($cek)) {
			$this->load->view('klinik/v_biayakonsul_add');
		} else {
			header('location:' . base_url());
		}
	}

	public function cek_isiobat()
	{
		$nokwitansi   = $this->input->post("kwi");
		$noreg        = $this->input->post("regg");

		$cek_obat     = $this->db->query("SELECT * from tbl_apoposting where noreg='$noreg' and nokwitansi = '$nokwitansi'");
		$cek_obat2    = $cek_obat->row();

		if ($cek_obat->num_rows() == 0) {
			echo json_encode(array("status" => 0));
		} else {
			echo json_encode(array("status" => 1));
		}
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

	public function getinfotindakan()
	{
		$kode = $this->input->get('kode');
		$data = $this->M_global->_data_tindakan($kode);
		echo json_encode($data);
	}

	public function getinfotadm()
	{
		$rekmed = $this->input->get('rekmed');
		$data = $this->db->query("SELECT COUNT(id) AS adm FROM tbl_kasir WHERE rekmed = '$rekmed' and posbayar<>'UANG_MUKA'")->row();
		// print_r($data);
		echo json_encode($data);
	}

	public function getdataregistrasi()
	{
		$unit = $this->session->userdata('unit');
		$kode = $this->input->get('noreg');
		$data = $this->M_global->_data_registrasi($kode);
		$barang = $this->db->query("SELECT b.kodetarif FROM tbl_dpoli a LEFT JOIN daftar_tarif_nonbedah b ON a.koders=b.koders AND a.kodetarif=b.kodetarif  WHERE noreg = '$kode' AND a.koders='$unit'")->result();
		echo json_encode($data);
	}
	public function getdataregistrasi2()
	{
		$unit = $this->session->userdata('unit');
		$kode = $this->input->get('noreg');
		// $barang = $this->db->query("SELECT a.kodetarif, b.tindakan, a.kodokter, a.koperawat, (SELECT nadokter FROM tbl_dokter WHERE kodokter=a.koperawat AND koders = '$unit' limit 1) as naperawat, (SELECT nadokter FROM tbl_dokter WHERE kodokter=a.kodokter AND koders = '$unit' limit 1) as nadokter FROM tbl_dpoli a LEFT JOIN tbl_tarifh b ON a.kodetarif = b.kodetarif WHERE a.noreg = '$kode' AND a.koders='$unit'")->result();
		$barang = $this->db->query("SELECT 
			a.*,
			a.kodetarif, 
			b.tindakan, 
			a.kodokter, 
			a.koperawat, 
			(SELECT nadokter FROM perawat WHERE kodokter=a.koperawat AND koders = a.koders AND kopoli=a.pos) AS naperawat, 
			(SELECT nadokter FROM dokter WHERE kodokter=a.kodokter AND koders = a.koders AND kopoli=a.pos) AS nadokter 
		FROM tbl_dpoli a JOIN tbl_tarifh b ON a.kodetarif = b.kodetarif 
		WHERE a.noreg = '$kode' AND a.koders='$unit'")->result();
		echo json_encode($barang);
	}

	public function get_eresep(){
		$noreg = $this->input->get('noreg');
		$data = $this->db->query("SELECT sum(totalharga) as total FROM tbl_eresep WHERE noreg = '$noreg'")->result();
		echo json_encode($data);
	}

	public function getuangmuka()
	{
		$kode = $this->input->get('noreg');
		$data = $this->M_global->_data_uangmuka_pasien($kode);
		echo json_encode($data);
	}

	public function getdataresep()
	{
		$noreg = $this->input->get('noreg');
		echo $this->M_kasirkonsul->_dataresep($noreg);
		// echo json_encode($noreg);
	}

	public function getdatadp()
	{
		$rekmed = $this->input->get('rekmed');
		echo $this->M_kasirkonsul->_datadp($rekmed);
	}

	public function ajax_list($param)
	{
		
		$user_level   = $this->session->userdata('user_level');
		$dat   = explode("~", $param);
		if ($dat[0] == 1) {
			$bulan = date('m');
			$tahun = date('Y');
			$list = $this->M_kasirkonsul->get_datatables(1, $bulan, $tahun);
		} else {
			$bulan  = date('Y-m-d', strtotime($dat[1]));
			$tahun  = date('Y-m-d', strtotime($dat[2]));
			$list = $this->M_kasirkonsul->get_datatables(2, $bulan, $tahun);
		}

		$data = array();
		$no = $_POST['start'];
		$jenisbayar = array('', 'Cash', 'Credit Card', 'Debet Card', 'Transfer', 'Online');

		foreach ($list as $unit) {
			$sisa = $unit->totalsemua - $unit->totalbayar;
			$data_pasien = $this->M_global->_datapasien($unit->rekmed);
			$namapas = $data_pasien->namapas;
			$hp      = $data_pasien->handphone;
			$email   = $data_pasien->email;
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

			$vcetak  = 'kwitansi=' . $unit->nokwitansi . '&noreg=' . $unit->noreg;

			//if($sisa>0)
			
				  //  <a class="btn btn-sm btn-warning" href="#" onclick="_urlcetak(' . "'" . $unit->nokwitansi . "'" . ",'" . $unit->noreg . "'" . ')" title="Cetak" ><i class="glyphicon glyphicon-print"></i></a>				   
			{
				// '<a class="btn btn-sm btn-primary" href="' . base_url("kasir_konsul/edit/" . $unit->id . "") . '" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a>
				if($user_level==0){
				
					$row[] = 
					'<a class="btn btn-sm btn-primary" href="' . base_url("kasir_konsul/edit/" . $unit->id . "") . '" title="Lihat" ><i class="glyphicon glyphicon-eye-open"></i> </a>';
						
				}else{
					$row[] =
					'<div class="text-center"><a class="btn btn-sm btn-primary" href="' . base_url("kasir_konsul/edit/" . $unit->id . "") . '" title="Lihat" ><i class="glyphicon glyphicon-eye-open"></i> </a>

					<a class="btn btn-sm btn-warning" href="javascript:void(0)" title="Kirim Email" onclick="send_email(' . "'" . $unit->id . "'" . ",'" . $email . "'" . ')"><i class="glyphicon glyphicon-envelope"></i> </a>

					<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Kirim Whatsapp" onclick="send_wa(' . "'" . $unit->id . "'" . ",'" . $hp . "'" . ')"><i class="fa fa-whatsapp"></i> </a></div>';

					// <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Batalkan" onclick="Batalkan(' . "'" . $unit->id . "'" . ')"><i class="glyphicon glyphicon-remove"></i> </a>
				//<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Batalkan" onclick="Batalkan('."'".$unit->id."'".')"><i class="glyphicon glyphicon-remove"></i> </a>';
				}
				
			}

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->M_kasirkonsul->count_all($dat[0], $bulan, $tahun),
			"recordsFiltered" => $this->M_kasirkonsul->count_filtered($dat[0], $bulan, $tahun),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_kasirkonsul->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$cabang = $this->session->userdata('unit');
		$userid = $this->session->userdata('username');
		//$tanggal = date('Y-m-d',strtotime($this->input->post('tanggal')));
		//$jam     = date('H:i:s',strtotime($this->input->post('tanggal')));

		$tanggal = date('Y-m-d');
		$jam     = date('H:i:s');

		$noreg = $this->input->post('noreg');
		$poli  = $this->input->post('reg_klinik');

		$this->_validate();
		//saya tambahkan ini 
		// original
		// $qcek = $this->db->query("SELECT * FROM tbl_hpoli WHERE noreg = '$noreg'")->result_array();
		// $cek = count($qcek);
		// husain changer
		$qcek = $this->db->query("SELECT * FROM tbl_dpoli WHERE noreg = '$noreg'")->num_rows();
		if ($qcek > 0) {
			$this->db->query("DELETE FROM tbl_hpoli WHERE noreg = '$noreg'");
			$this->db->query("DELETE FROM tbl_dpoli WHERE noreg = '$noreg'");

			$data = array(
				'koders' => $cabang,
				'noreg'  => $noreg,
				'rekmed' => $this->input->post('pasien'),
				'tglperiksa' => $tanggal,
				'jam' => $jam,
				'username' => $userid,
			);
			$insert    = $this->db->insert('tbl_hpoli', $data);

			$kodetarif = $this->input->post('kode');
			$dokter    = $this->input->post('dokter');
			$perawat   = $this->input->post('perawat');
			$harga     = $this->input->post('harga');
			$feemedis     = $this->input->post('feemedis');
			$bhp     = $this->input->post('bhp');
			$tarifrs     = $this->input->post('tarifrs');
			$tarifdr     = $this->input->post('tarifdr');
			$disc1     = $this->input->post('disc1');
			$disc2     = $this->input->post('disc2');
			$jumlah    = $this->input->post('jumlah');

			$jumdata = count($kodetarif);
			for ($i = 0; $i <= $jumdata - 1; $i++) {
				$_harga   = str_replace(',', '', $harga[$i]);
				$_feemedis   = str_replace(',', '', $feemedis[$i]);
				$_bhp   = str_replace(',', '', $bhp[$i]);
				$_tarifrs   = str_replace(',', '', $tarifrs[$i]);
				$_tarifdr   = str_replace(',', '', $tarifdr[$i]);
				$_disc1   = str_replace(',', '', $disc1[$i]);
				$_disc2   = str_replace(',', '', $disc2[$i]);
				$_jumlah  = str_replace(',', '', $jumlah[$i]);

				if ($dokter[$i]) {
					$_dokter = $dokter[$i];
				} else {
					$_dokter = '';
				}

				if ($perawat[$i]) {
					$_perawat = $perawat[$i];
				} else {
					$_perawat = '';
				}

				$data_rinci = array(
					'clientid'    => $cabang,
					'koders'      => $cabang,
					'noreg'       => $noreg,
					'pos'         => $poli,
					'kodokter'    => $_dokter,
					'kodetarif'   => $kodetarif[$i],
					'qty'					=> 1,
					'tarifrs'     => $_tarifrs,
					'tarifdr'     => $_tarifdr,
					'paramedis'   => $_feemedis,
					'diskpr'   => $_disc1,
					'diskrp'   => $_disc2,
					'obatpoli'    => $_bhp,
					'bahan'				=> 0,
					'koperawat'   => $_perawat,
				);

				if ($kodetarif[$i] != "") {
					$insert_detil = $this->db->insert('tbl_dpoli', $data_rinci);
				}
			}
		} else {
			$data = array(
				'koders' => $cabang,
				'noreg'  => $noreg,
				'rekmed' => $this->input->post('pasien'),
				'tglperiksa' => $tanggal,
				'jam' => $jam,
				'username' => $userid,
			);
			$insert    = $this->db->insert('tbl_hpoli', $data);

			$kodetarif = $this->input->post('kode');
			$dokter    = $this->input->post('dokter');
			$perawat   = $this->input->post('perawat');
			$harga     = $this->input->post('harga');
			$feemedis     = $this->input->post('feemedis');
			$bhp     = $this->input->post('bhp');
			$tarifrs     = $this->input->post('tarifrs');
			$tarifdr     = $this->input->post('tarifdr');
			$disc1     = $this->input->post('disc1');
			$disc2     = $this->input->post('disc2');
			$jumlah    = $this->input->post('jumlah');

			
			$jumdata   = count($kodetarif);
			for ($i = 0; $i <= $jumdata - 1; $i++) {
				// $datax = $this->db->query("SELECT * FROM tbl_tarif WHERE koders = '$cabang' AND (kodetarif = '".$kodetarif[$i]."')")->result();
				$_harga   = str_replace(',', '', $harga[$i]);
				$_feemedis   = str_replace(',', '', $feemedis[$i]);
				$_bhp   = str_replace(',', '', $bhp[$i]);
				$_tarifrs   = str_replace(',', '', $tarifrs[$i]);
				$_tarifdr   = str_replace(',', '', $tarifdr[$i]);
				$_disc1   = str_replace(',', '', $disc1[$i]);
				$_disc2   = str_replace(',', '', $disc2[$i]);
				$_jumlah  = str_replace(',', '', $jumlah[$i]);

				if ($dokter[$i]) {
					$_dokter = $dokter[$i];
				} else {
					$_dokter = '';
				}

				if ($perawat[$i]) {
					$_perawat = $perawat[$i];
				} else {
					$_perawat = '';
				}

				$data_rinci = array(
					'clientid'    => $cabang,
					'koders'      => $cabang,
					'noreg'       => $noreg,
					'pos'         => $poli,
					'kodokter'    => $_dokter,
					'kodetarif'   => $kodetarif[$i],
					'qty'					=> 1,
					'tarifrs'     => $_tarifrs,
					'tarifdr'     => $_tarifdr,
					'paramedis'   => $_feemedis,
					'obatpoli'    => $_bhp,
					'diskpr'   => $_disc1,
					'diskrp'   => $_disc2,
					'bahan'				=> 0,
					'koperawat'   => $_perawat,
				);

				$this->db->insert('tbl_dpoli', $data_rinci);
				// if ($kodetarif[$i] != "") {
				// 	$insert_detil = $this->db->insert('tbl_dpoli', $data_rinci);
				// }
			}
		}

		// die;

		// $this->_validate();
		// 	$data = array(
		// 		'koders' => $cabang,
		// 		'noreg'  => $noreg,
		// 		'rekmed' => $this->input->post('pasien'),
		// 		'tglperiksa' => $tanggal,
		// 		'jam' => $jam,
		// 		'username' => $userid,

		// 	);
		// 	$insert = $this->db->insert('tbl_hpoli',$data);


		// $kodetarif = $this->input->post('kode');
		// $dokter = $this->input->post('dokter');
		// $perawat = $this->input->post('perawat');
		// $harga = $this->input->post('harga');
		// $disc1 = $this->input->post('disc1');
		// $disc2 = $this->input->post('disc2');
		// $jumlah = $this->input->post('jumlah');

		// $jumdata = count($kodetarif);
		// for($i=0;$i<=$jumdata-1;$i++)
		// {
		// 	$_harga  =  str_replace(',','',$harga[$i]);
		// 	$_disc1 =  str_replace(',','',$disc1[$i]);
		// 	$_disc2 =  str_replace(',','',$disc2[$i]);
		// 	$_jumlah =  str_replace(',','',$jumlah[$i]);

		// 	if($dokter[$i]){
		// 	  $_dokter= $dokter[$i];
		// 	} else {
		// 	  $_dokter= '';	
		// 	}

		// 	if($perawat[$i]){
		// 	  $_perawat= $perawat[$i];
		// 	} else {
		// 	  $_perawat= '';	
		// 	}

		// 	$data_rinci = array(
		// 	  'clientid' => $cabang,
		// 	  'koders' => $cabang,
		// 	  'noreg' => $noreg,
		// 	  'kodetarif' => $kodetarif[$i],
		// 	  'kodokter' => $_dokter,
		// 	  'koperawat' => $_perawat,
		// 	  'pos' => $poli,
		// 	  'tarifrs' => $_harga,
		// 	  'rsnet' => $_jumlah,
		// 	  'diskpr' => $_disc1,
		// 	  'diskrp' => $_disc2,


		// 	);

		// 	if($kodetarif[$i]!=""){
		// 		$insert_detil = $this->db->insert('tbl_dpoli',$data_rinci);
		// 	}

		// }


		history_log(0 ,'KASIR_KONSUL_TINDAKAN' ,'ADD' ,$noreg ,'-');
		echo json_encode(array("status" => TRUE, "nomor" => $noreg));
	}

	// husain add function
	public function edit_hpoli(){
		$rekmed = $this->input->post('pasien');
		$cabang = $this->session->userdata('unit');
		$userid = $this->session->userdata('username');
		$kondisi = $this->input->get('kondisi');
		$get_kasir = $this->db->get_where("tbl_kasir", ['id' => $kondisi])->row();
		$noreg = $get_kasir->noreg;
		$get_polix = $this->db->query("SELECT * FROM tbl_hpoli WHERE noreg = '$noreg'");
		if($get_polix->num_rows() > 0){
			// $this->db->query("DELETE FROM tbl_hpoli WHERE noreg = '$noreg'");
		}
		$get_poli = $get_polix->row();
		$tanggal = $get_poli->tglperiksa;
		$jam = $get_poli->jam;
		$data = [
			'koders' => $cabang,
			'noreg'  => $noreg,
			'rekmed' => $rekmed,
			'tglperiksa' => $tanggal,
			'jam' => $jam,
			'username' => $userid,
		];
		// $this->db->insert("tbl_kasir", $data);
		echo json_encode(['status' => 1]);
	}


	public function edit_kasir(){
		$kondisi = $this->input->get('kondisi');
		$get_kasir = $this->db->get_where("tbl_kasir", ['id' => $kondisi])->row();
		$cabang = $this->session->userdata('unit');
		$userid = $this->session->userdata('username');
		$noreg = $get_kasir->noreg;
		$poli = $this->input->post("reg_klinik");
		$get_poli = $this->db->query("SELECT * FROM tbl_hpoli WHERE noreg = '$noreg'")->row();
		$cek_poli = $this->db->query("SELECT * FROM tbl_dpoli WHERE noreg = '$noreg'")->num_rows();
		if($cek_poli > 0){
			$tanggal = $get_poli->tglperiksa;
			$jam = $get_poli->jam;
			// $this->db->query("DELETE FROM tbl_dpoli WHERE noreg = '$noreg'");
			$kodetarif = $this->input->get('kode');
			$dokter = $this->input->get('dokter');
			$perawat = $this->input->get('perawat');
			$harga = $this->input->get('harga');
			$feemedis = $this->input->get('feemedis');
			$bhp = $this->input->get('bhp');
			$tarifrs = $this->input->get('tarifrs');
			$tarifdr = $this->input->get('tarifdr');
			$disc1 = $this->input->get('disc1');
			$disc2 = $this->input->get('disc2');
			$jumlah = $this->input->get('jumlah');
			$data_rinci = [
				'clientid'    => $cabang,
				'koders'      => $cabang,
				'noreg'       => $noreg,
				'pos'         => $poli,
				'kodokter'    => $dokter,
				'kodetarif'   => $kodetarif,
				'qty'					=> 1,
				'tarifrs'     => $tarifrs,
				'tarifdr'     => $tarifdr,
				'paramedis'   => $feemedis,
				'diskpr'   => $disc1,
				'diskrp'   => $disc2,
				'obatpoli'    => $bhp,
				'bahan'				=> 0,
				'koperawat'   => $perawat,
			];	
			if ($kodetarif != "") {
				// $insert_detil = $this->db->insert('tbl_dpoli', $data_rinci);
			}
			echo json_encode(['status' => 1]);
		}
	}
	// end husain

	public function ajax_add_bayar()
	{
		
		// // echo "<pre>";
		// // print_r($this->input->post());
		// // die;
		$cabang   = $this->session->userdata('unit');
		$userid   = $this->session->userdata('username');

		$tanggal  = date('Y-m-d');
		$jam      = date('H:i:s');

		$noreg    = $this->input->post('noreg');
		$tercover_rp    = $this->input->post('tercover_rp');
		$tercover_rp2   = $this->input->post('tercover_rp2');
		$jaminan  = $this->input->get('jaminan');
		// sql untuk update keluar tbl_apoposting
		$this->db->query("UPDATE tbl_apoposting SET keluar = 1 WHERE noreg ='$noreg'");
		
		
		$fakturpajak    = $this->input->post('fakturpajak');
		$totalpoli      = str_replace(',', '', $this->input->post('tindakanrp'));
		$totalresep     = str_replace(',', '', $this->input->post('reseprp_1'));
		$adm            = str_replace(',', '', $this->input->post('admrp'));
		$totalrp        = str_replace(',', '', $this->input->post('totalrp'));
		$diskon         = str_replace(',', '', $this->input->post('diskonpr'));
		$diskonrp       = str_replace(',', '', $this->input->post('diskonrp'));
		$uangmuka       = str_replace(',', '', $this->input->post('uangmuka'));
		$uangmukapakai  = str_replace(',', '', $this->input->post('uangmukapakai'));
		$refund         = str_replace(',', '', $this->input->post('refund'));
		// $voucherrp = str_replace(',','',$this->input->post('voucherrp')); //saya ganti
		$voucherrp1     = str_replace(',', '', $this->input->post('voucherrp1'));
		$voucherrp2     = str_replace(',', '', $this->input->post('voucherrp2'));
		$voucherrp3     = str_replace(',', '', $this->input->post('voucherrp3'));
		$totalnet       = str_replace(',', '', $this->input->post('totalnet'));
		$bayarcash      = str_replace(',', '', $this->input->post('totaltunairp'));
		$selisihrp      = str_replace(',', '', $this->input->post('selisihrp'));
		$bayarcard      = str_replace(',', '', $this->input->post('totalelectronicrp'));
		//$totalbayar = str_replace(',','',$this->input->post('uangpasienrp'));
		$kembali        = str_replace(',', '', $this->input->post('kembalirp'));
		$promoid        = $this->input->post('promo');
		$admcredit      = 0;
		$totalbayar     = $bayarcash + $bayarcard;
		//diskon resep
		$diskonresep    = str_replace(',', '', $this->input->post('diskonresep'));
		
		// promo
		$promoid    = $this->input->post('promo');
		$hadiah     = $this->input->post('hadiah');
		$qtyhadiah  = $this->input->post('qtyhadiah');
		
		if (empty($fakturpajak)) {
			$fakturpajak = '';
		}
		
		$kwitansi_kasir = urut_transaksi('URUTKWT', 19);
		// sql untuk update no kwitansi tbl_apoposting
		// $this->db->query("UPDATE tbl_apoposting SET nokwitansi = '$kwitansi_kasir' WHERE noreg ='$noreg'");

		$eresep = $this->db->query("SELECT * FROM tbl_eresep WHERE noreg = '$noreg' LIMIT 1");
		if($eresep->num_rows() > 0){
			$abc = $eresep->result();
			foreach($abc as $ab){
				$eresepz = $ab->resepno;
			}
		} else {
			$eresepz = 0;
		}
		
		$pas = $this->db->get_where('tbl_pasien', ['rekmed' => $this->input->post('pasien')])->row();

		// update pasien
		$this->db->set('ada', 0);
		$this->db->where('lastnoreg', $noreg);
		$this->db->update('tbl_pasien');
		// end update pasien

		$this->_validate();
		$data = [
				'koders'        => $cabang,
				'nokwitansi'    => $kwitansi_kasir,
				'fakturpajak'   => $fakturpajak,
				'rekmed'        => $this->input->post('pasien'),
				'noreg'         => $noreg,
				'tglbayar'      => $tanggal,
				'jambayar'      => $jam,
				'totalpoli'     => $totalpoli,
				'totalresep'    => $totalresep,
				'uangmuka'      => $uangmukapakai,
				'adm'           => $adm,
				'diskon'        => $diskon,
				'diskonrp'      => $diskonrp,
				'admcredit'     => $admcredit,
				'totalsemua'    => $totalnet,
				'bayarcash'     => $bayarcash,
				'selisihrp'     => $selisihrp,
				'bayarcard'     => $bayarcard,
				'refund'        => $refund,
				// 'voucherrp' => $voucherrp,
				'voucherrp1'    => $voucherrp1,
				'voucherrp2'    => $voucherrp2,
				'voucherrp3'    => $voucherrp3,
				// 'novoucher' => $this->input->post('vouchercode'), saya ganti
				'novoucher1'    => $this->input->post('vouchercode1'),
				'novoucher2'    => $this->input->post('vouchercode2'),
				'novoucher3'    => $this->input->post('vouchercode3'),
				//promoid
				'promoid'       => $promoid[0],
				'promoid2'      => $promoid[1],
				'promoid3'      => $promoid[2],
				'promoid4'      => $promoid[3],
				'promoid5'      => $promoid[4],
				// hadiah
				'hadiah1'       => $hadiah[0],
				'hadiah2'       => $hadiah[1],
				'hadiah3'       => $hadiah[2],
				'hadiah4'       => $hadiah[3],
				'hadiah5'       => $hadiah[4],
				// qtyhad1
				'qtyhad1'       => $qtyhadiah[0],
				'qtyhad2'       => $qtyhadiah[1],
				'qtyhad3'       => $qtyhadiah[2],
				'qtyhad4'       => $qtyhadiah[3],
				'qtyhad5'       => $qtyhadiah[4],
				// cust_id
				// script original
				'cust_id'       => $this->input->post('vouchersource'),
				// husain change
				// 'cust_id'       => $this->input->post('vpenjamin'),
				// end husain
				'totalbayar'    => $totalbayar,
				'kembali'       => $kembali,
				'posbayar'      => 'RAWAT_JALAN',
				'dibayaroleh'   => $this->input->post('terimadari'),
				'jenisbayar'    => 1,
				'username'      => $userid,
				'kembalikeuangmuka'      => $this->input->post('kembaliuang'),
				'adapromo'		=> $this->input->post('adapromo'),
				'diskonresep'	=> $diskonresep,
				'namapasien' => $pas->namapas,
				'resepno' => $eresepz,
			];
		$insert = $this->db->insert('tbl_kasir', $data);
		// husain add
		$datapap = [
			'koders' => $cabang,
			'noreg' => $noreg,
			'rekmed' => $this->input->post('pasien'),
			'tglposting' => $tanggal,
			'tgljatuhtempo' => $tanggal,
			'cust_id' => $this->input->post('vpenjamin'),
			'nokwitansi' => $kwitansi_kasir,
			'bayarcash' => $bayarcash,
			'adm' => $adm,
			'totalpoli' => $totalpoli,
			'totalradio' => 0,
			'totallab' => 0,
			'totalbedah' => 0,
			'totalresep' => $totalresep,
			'jumlahhutang' => str_replace(',', '', $this->input->post('tercover_rp')),
			'username' => $userid,
			'namapas' => $pas->namapas,
			'nik' => $pas->noidentitas,
			'cust_id2' => $this->input->post('vpenjamin2'),
			'nilaiklaim2' => str_replace(',', '', $this->input->post('tercover_rp2'))
		];

		if($jaminan == 1){
			$insert = $this->db->insert('tbl_pap', $datapap);
		}
		// end husain
		
		$bayar_bank = $this->input->post('bayar_bank');
		if ($bayar_bank) {
			$bayar_tipe       = $this->input->post('bayar_tipe');
			$bayar_nokartu    = $this->input->post('bayar_nokartu');
			$bayar_trvalid    = $this->input->post('bayar_trvalid');
			$bayar_nilai      = $this->input->post('bayar_nilai');
			$bayar_adm        = $this->input->post('bayar_adm');
			$bayar_jumlah     = $this->input->post('bayar_total');
			$jumdata_bayar    = count($bayar_bank);

			for ($i = 0; $i <= $jumdata_bayar - 1; $i++) {
				$total  = str_replace(',', '', $bayar_nilai[$i]);
				$adm    = str_replace(',', '', $bayar_adm[$i]);
				$gtotal = str_replace(',', '', $bayar_jumlah[$i]);
				$admrp  = ($adm / 100) * $total;

				$data_detil   = array(
					'koders' => $cabang,
					'nokwitansi' => $kwitansi_kasir,
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
		
		// script lama
		// for($i=0;$i<=$jumdata_dp-1;$i++)
		// {
		// 	$total_dp     = str_replace(',','',$dp_jumlah[$i]);
		// 	// $kwitansi_dp  = $kwitansi_dp[$i];

		// 	// $this->db->query("update tbl_kasir set umuka=umuka+'$total_dp' where nokwitansi='$kwitansi_dp' ");
		// 	$this->db->query("update tbl_kasir set umuka=umuka+'$total_dp' where nokwitansi='$kwitansi_dp[$i]' ");
		
		//   }		
		// }
		
		$totdp_jumlah = str_replace(',', '', $this->input->post('dp_td_data3'));
		if ($totdp_jumlah != null) {
			$sum = array_sum($totdp_jumlah);
			if ($uangmukapakai < $sum) { //mencari apakah uang muka yang dimasukkan ada selisih dengan dp ?
				$rekmed = $this->input->post('pasien');
				$sisa = $sum - $uangmukapakai;
				$cabang = $this->session->userdata('unit');
				$date = date("Y-m-d");
				$this->db->query("INSERT INTO tbl_uangmuka (koders, nokwitansi, rekmed, tglbayar, jmbayar, jenisbayar, dibayaroleh, posbayar, username, accountno, ket, closed, kwitansivalid)
				VALUES ('$cabang','$kwitansi_kasir', '$rekmed', '$date', '$sisa', '1', 'sisa uangmuka dari $rekmed', 'UANG_MUKA', '$userid', '-', 'sisa uangmuka dari $rekmed', 0, '')");
			}
		}
		
		$this->db->query("update tbl_regist set keluar=1 where koders = '$cabang' and noreg = '$noreg'");
		$this->db->query("update tbl_hpoli set nokwitansi='$kwitansi_kasir' where koders = '$cabang' and noreg = '$noreg'");
		
		// pengembalian uang muka dari uang kembalian rp
		$statusKembalian = $this->input->post('kembaliuang');
		$kembali        = str_replace(',', '', $this->input->post('kembalirp'));
		if ($statusKembalian == 1 && $kembali > 0) {
			$cabang = $this->session->userdata('unit');
			$rekmed = $this->input->post('pasien');
			$kwitansi_uangmuka_kembalian = urut_transaksi('URUTKWT', 19);
			$date = date("Y-m-d");
			date_default_timezone_set('Asia/Jakarta');
			$jam =  date('H:i:s');
			// insert ke tabel kasir
			$this->db->query("INSERT INTO tbl_kasir (koders, nokwitansi, noreg, rekmed, tglbayar, jambayar, adm, totalpoli, totalresep, uangmuka, umuka, admcredit, totalsemua, voucherrp1, voucherrp2, voucherrp3, diskonrp, lain, bayarcash, bayarcard, totalbayar, kembali, dibayaroleh, posbayar, username, shipt, kasirtype, kodokter, diskon, prosenksr, lainket)
			VALUES ('$cabang', '$kwitansi_uangmuka_kembalian', '$noreg', '$rekmed', '$date', '$jam', '0', '0', '0', '$kembali', '0', '0', '0', 0, 0, 0, 0, 0, 0, 0, 0, 0, 'sisa kembalian dari $rekmed', 'UANG_MUKA', '$userid', 0, 0, '-', '0', '0', 'sisa kembalian dari $rekmed')");
			$get_id = $this->db->query("SELECT * FROM tbl_kasir where koders = '$cabang' ORDER BY id DESC LIMIT 1")->result();
			foreach ($get_id as $gi) {
				$id_nya = (int)$gi->id - 1;
			}
			// $kasirx = $this->db->get_where("tbl_kasir", ['koders' => $cabang, 'nokwitansi' => $kwitansi_uangmuka_kembalian])->row();
			$kasirx = $this->db->query("SELECT * FROM tbl_kasir WHERE koders = '$cabang' AND id = '$id_nya'")->row();
			if ($kasirx->bayarcash != 0) {
				// insert ke tabel uang muka
				$this->db->query("INSERT INTO tbl_uangmuka (koders, nokwitansi, rekmed, tglbayar, jmbayar, jenisbayar, dibayaroleh, posbayar, username, accountno, ket, closed, kwitansivalid)
				VALUES ('$cabang', '$kwitansi_uangmuka_kembalian', '$rekmed', '$date', '$kembali', '1', 'sisa kembalian dari $rekmed', 'UANG_MUKA', '$userid', '-', 'sisa kembalian dari $rekmed', 0, '')");
			}
		}
		
		// Update Voucher
		$rekmed_voc = $this->input->post('pasien');
		$vouchercode1 = $this->input->post("vouchercode1");
		$vouchercode2 = $this->input->post("vouchercode2");
		$vouchercode3 = $this->input->post("vouchercode3");
		
		$this->db->query("UPDATE tbl_vocd SET cabangpakai = '$cabang', tglpakai = '$tanggal', rekmedpakai = '$rekmed_voc', terpakai = '1' WHERE novoucher = '$vouchercode1'");
		$this->db->query("UPDATE tbl_vocd SET cabangpakai = '$cabang', tglpakai = '$tanggal', rekmedpakai = '$rekmed_voc', terpakai = '1' WHERE novoucher = '$vouchercode2'");
		$this->db->query("UPDATE tbl_vocd SET cabangpakai = '$cabang', tglpakai = '$tanggal', rekmedpakai = '$rekmed_voc', terpakai = '1' WHERE novoucher = '$vouchercode3'");
		
		history_log(0 ,'KASIR_KONSUL_PEMBAYARAN' ,'ADD' ,$kwitansi_kasir ,'-');
		echo json_encode(["status" => 1, "nomor" => $kwitansi_kasir]);
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
		$this->M_kasirkonsul->update(array('koders' => $this->input->post('kode')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function pembatalan($id)
	{
		$data = array(
			'batal' => 1,
			'keluar' => 1,
			'batalkarena' => $this->input->post('alasan'),
		);

		$cek    = $this->db->query("SELECT * from tbl_kasir where id = '$id'")->row();
		$result = $this->db->query("DELETE from tbl_kasir where id = '$id'");
		if ($result) {
			echo json_encode(array("status" => 1));
		} else {
			echo json_encode(array("status" => 0));
		}
		
		history_log(0 ,'KASIR_KONSUL' ,'BATAL' ,$cek->nokwitansi ,'-');
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('noreg') == '') {
			$data['inputerror'][] = 'pasien';
			$data['error_string'][] = 'Harus diisi';
			$data['status'] = FALSE;
		}



		if ($data['status'] === FALSE) {
			echo json_encode($data);
			exit();
		}
	}

	public function cetak_kwitansi(){
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');
		$user = $this->session->userdata('username');
		$avatar = $this->session->userdata('avatar_cabang');
		if (!empty($cek)) {
			$nokwitansi = $this->input->get('kwitansi');
			$noreg = $this->input->get('noreg');
			$regist = $this->db->query("SELECT tbl_regist.*, tbl_pasien.namapas from tbl_regist inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed where noreg = '$noreg'")->row();
			$kasir = $this->db->query("select * from tbl_kasir where nokwitansi = '$nokwitansi'")->row();
			$detil = $this->db->query("select * from (select tbl_tarifh.tindakan as ket, (tbl_dpoli.tarifrs + tbl_dpoli.tarifdr + tbl_dpoli.paramedis + tbl_dpoli.obatpoli) as jumlah from tbl_dpoli inner join tbl_tarifh on tbl_dpoli.kodetarif=tbl_tarifh.kodetarif where noreg = '$noreg' union	all select 'Adm' as ket, tbl_kasir.adm as jumlah from tbl_kasir where nokwitansi = '$nokwitansi' union	all select 'Diskon Total' as ket, tbl_kasir.diskonrp*-1 as jumlah from tbl_kasir where nokwitansi = '$nokwitansi') kasir where jumlah<>0")->result();
			$dresep = $this->db->query("SELECT * from tbl_apohresep where noreg = '$noreg'")->row();
			if ($dresep) {
				$eresep = $dresep->eresepno;
				$param = $dresep->resepno;
			} else {
				$eresep = '';
				$param = '';
			}
			$totxx = 0;
			foreach($detil as $d){
				$totxx += $d->jumlah;
			}
			$apodresepxx = $this->db->query("SELECT * FROM tbl_apodresep WHERE resepno = '$param'")->result();
			$abcxx=0;
			foreach($apodresepxx as $ap){
				$abcxx += $ap->totalrp;
			}
			$semuanya_ = $totxx + $abcxx;
			if ($kasir->bayarcash == 0) {
				$kembalianx = 0;
			} else {
				$kembalianx = angka_rp($kasir->kembali, 2);
			}
			$xxx = 0;
			foreach ($detil as $key => $value) {
				$xxx += $value->jumlah;
			}
			// $terbilang = terbilang($xxx);
			$terbilang = terbilang($semuanya_);
			$voucher = $this->db->query("select 'Voucher' as ket, (tbl_kasir.voucherrp1 + tbl_kasir.voucherrp2 + tbl_kasir.voucherrp3)*-1 as jumlah from tbl_kasir 
			where nokwitansi = '$nokwitansi'")->result();
			foreach ($voucher as $v) {
				$vcr1 = 0 - (int)$v->jumlah;
				$vcr = number_format($vcr1);
			}

			$jml_harga = $this->db->query("SELECT sum(tbl_dpoli.tarifrs) AS jumlah FROM tbl_dpoli INNER JOIN tbl_tarifh ON tbl_dpoli.kodetarif=tbl_tarifh.kodetarif WHERE noreg = '$noreg'")->result();
			foreach ($jml_harga as $jh) {
				$jh1 = 0 - (int)$jh->jumlah;
				$jh = number_format($jh1);
			}
			$unit = $this->session->userdata('unit');
			$kop = $this->M_cetak->kop($unit);
			$profile = data_master('tbl_namers', array('koders' => $unit));
			$namars = $kop['namars'];
			$alamat = $kop['alamat'];
			$alamat2 = $kop['alamat2'];
			$alamat3 = $profile->kota;
			$kota = $kop['kota'];
			$phone = $kop['phone'];
			$whatsapp = $kop['whatsapp'];
			$npwp = $kop['npwp'];
			$chari = '';
			$chari .= "
						<table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
									<thead>
											<tr>
														<td rowspan=\"6\" align=\"center\">
																<img src=\"" . base_url() . "assets/img_user/$avatar\"  width=\"70\" height=\"70\" /></td>
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
									<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
											<tr>
														<td width=\"15%\" style=\"text-align:center; font-size:20px;\"><b>KWITANSI PEMBAYARAN</b></td>
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
									<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
											<tr>
														<td width=\"20%\">Sudah terima dari</td>
														<td width=\"5%\"> : </td>
														<td>$kasir->dibayaroleh</td>
											</tr> 
											<tr>
														<td width=\"20%\">No. Member</td>
														<td width=\"5%\"> : </td>
														<td>$kasir->rekmed</td>
											</tr> 
											<tr>
														<td width=\"20%\">Banyaknya Uang</td>
														<td width=\"5%\"> : </td>
														<td>$terbilang</td>
											</tr> 
											<tr>
														<td width=\"20%\">Untuk Pembayaran</td>
														<td width=\"5%\"> : </td>
														<td>Pemeriksaan dokter & Resep</td>
											</tr> 
											<tr>
														<td width=\"20%\">Pasien</td>
														<td width=\"5%\"> : </td>
														<td>$regist->namapas</td>
											</tr> 
									</table>";
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
											<tr>
														<td> &nbsp; </td>
											</tr> 
									</table>";
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: tahoma; font-size:16px\" width=\"100%\" align=\"center\" border=\"0\">
											<tr>
														<td><b>KONSULTASI</b></td>
											</tr> 
									</table>";
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
											<thead>
														<tr>
																<td width=\"5%\" align=\"center\" style=\"text-align:center; border-right: none; border-left: none;\"><b>No</b></td>
																<td width=\"75%\" align=\"center\" style=\"text-align:center; border-right: none; border-left: none;\"><b>Nama Tindakan</b></td>
																<td width=\"20%\" align=\"center\" style=\"text-align:center; border-left: none; border-right: none;\"><b>Jumlah</b></td>
														</tr>
											</thead>";
			$no = 1;
			$tot = 0;
			foreach ($detil as $db) {
				$tot = $tot + $db->jumlah;
				$chari .= "<tbody><tr>
												<td style=\"text-align:center; border-right: none; border-left: none; border-top: none; border-bottom: none;\">" . $no++ . "</td>
												<td style=\"text-align:left; border-right: none; border-left: none; border-top: none; border-bottom: none;\">$db->ket</td>
												<td style=\"text-align:right; border-left: none; border-right: none; border-top: none; border-bottom: none;\">".number_format($db->jumlah, 2)."</td>
										</tr>";
				
				if($vcr != 0){
					$chari .= "<tbody><tr>
													<td style=\"text-align:center; border-right: none; border-left: none; border-top: none; border-bottom: none;\">" . $no++ . "</td>
													<td style=\"text-align:left; border-right: none; border-left: none; border-top: none; border-bottom: none;\">Voucher $vcr</td>
													<td style=\"text-align:right; border-left: none; border-right: none; border-top: none; border-bottom: none;\">".number_format($jh, 2)."</td>
											</tr>";
				}
				$chari .= "</tbody>";
			}
			$chari .= "<tbody><tr>
											<td style=\"text-align:right; border-right: none; border-left: none;\" colspan=\"2\"><b>Total Konsul</b></td>
											<td style=\"text-align:right; border-left: none; border-right: none;\">".number_format($tot, 2)."</td>
									</tr></tbody>";
			$chari .= "</table>";
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
											<tr>
														<td> &nbsp; </td>
											</tr> 
									</table>";
			$header = $this->db->query("SELECT * from tbl_apohresep where resepno = '$param'")->row();
			$detil = $this->db->query("SELECT * from tbl_apodresep where resepno = '$param'")->result();
			$posting = $this->db->query("SELECT * from tbl_apoposting  where resepno = '$param'")->row();
			$kasir = $this->db->query("SELECT * from tbl_kasir where nokwitansi = '$nokwitansi'")->row();
			$querypjk = $this->db->query("SELECT * FROM tbl_pajak where kodetax='PPN'")->row();
			$linediskon = $this->db->query("SELECT SUM(discrp) AS total FROM tbl_apodresep WHERE resepno = '$param'")->row();
			$linetotal = $this->db->query("SELECT SUM(totalrp) AS total FROM tbl_apodresep WHERE resepno = '$param'")->row();
			$konsul = $this->db->query("SELECT * from (select tbl_tarifh.tindakan as ket, tbl_dpoli.tarifrs as jumlah from tbl_dpoli inner join tbl_tarifh on tbl_dpoli.kodetarif=tbl_tarifh.kodetarif where noreg = '$noreg' union	all select 'Adm' as ket, tbl_kasir.adm as jumlah from tbl_kasir where nokwitansi = '$nokwitansi' union	all select 'Diskon Total' as ket, tbl_kasir.diskonrp*-1 as jumlah from tbl_kasir where nokwitansi = '$nokwitansi') kasir where jumlah<>0")->result();
			$bk = 0;
			foreach ($konsul as $k) {
				$bk += $k->jumlah;
			}
			$vcr = $this->db->query("select 'Voucher' as ket, (tbl_kasir.voucherrp1 + tbl_kasir.voucherrp2 + tbl_kasir.voucherrp3)*-1 as jumlah from tbl_kasir where nokwitansi = '$nokwitansi'")->result();
			foreach ($vcr as $vc) {
				$v = $vc->jumlah;
				$vn = $vc->ket;
			}
			$sisa_voucher = (int)$v + $bk;
			$detail_resep = $this->db->query("SELECT SUM(totalrp) AS totalrp FROM tbl_apodresep WHERE eresepno = '$eresep'")->row();
			$data_pasien = data_master('tbl_pasien', array('rekmed' => $kasir->rekmed));
			$rck = $this->db->query("SELECT * from tbl_apodetresep where resepno = '$param' AND koders='$unit'")->result();
			$racikan = $this->db->query("SELECT * from tbl_aporacik where resepno = '$param' AND koders='$unit'")->row();
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
											<tr>
														<td> &nbsp; </td>
											</tr> 
									</table>";
			if ($racikan != null && $rck != null) {
				$chari .= "
										<table style=\"border-collapse:collapse;font-family: tahoma; font-size:16px\" width=\"100%\" align=\"center\" border=\"0\">
												<tr>
															<td><b>RESEP & RACIKAN</b></td>
												</tr> 
										</table>";
				$chari .= "
										<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
												<thead>
															<tr>
																	<td width=\"5%\" align=\"center\" style=\"text-align:center; border-right: none; border-left: none;\"><b>No</b></td>
																	<td width=\"35%\" align=\"center\" style=\"text-align:center; border-right: none; border-left: none;\"><b>Keterangan</b></td>
																	<td width=\"10%\" align=\"center\" style=\"text-align:center; border-left: none; border-right: none;\"><b>Qty</b></td>
																	<td width=\"20%\" align=\"center\" style=\"text-align:center; border-left: none; border-right: none;\"><b>Diskon</b></td>
																	<td width=\"20%\" align=\"center\" style=\"text-align:center; border-left: none; border-right: none;\"><b>Jumlah</b></td>
															</tr>
												</thead>";
				$aporacik = $racikan;
				if ($aporacik->harga_manual != 0) {
					$abc = $aporacik->harga_manual;
				} else {
					$abc = $aporacik->totalrp; 
				}
				$no = 1;
				$actualtotal	= ($detail_resep->totalrp + $abc) - (0 - $sisa_voucher);
				$detotal = 0;
				foreach($detil as $dt){
					$detotal += $dt->totalrp;
					$chari .= "<tr>
													<td style=\"text-align:center; border-right: none; border-left: none; border-top: none; border-bottom: none;\">" . $no++ . "</td>
													<td style=\"text-align:left; border-right: none; border-left: none; border-top: none; border-bottom: none;\">$dt->namabarang</td>
													<td style=\"text-align:right; border-left: none; border-right: none; border-top: none; border-bottom: none;\">".number_format($dt->qty, 0)."</td>
													<td style=\"text-align:right; border-left: none; border-right: none; border-top: none; border-bottom: none;\">".number_format($dt->discrp, 2)."</td>
													<td style=\"text-align:right; border-left: none; border-right: none; border-top: none; border-bottom: none;\">".number_format($dt->totalrp, 2)."</td>
											</tr>";
				}
				$chari .= "<tr>
												<td style=\"text-align:center; border-right: none; border-left: none; border-top: none; border-bottom: none;\">" . $no++ . "</td>
												<td style=\"text-align:left; border-right: none; border-left: none; border-top: none; border-bottom: none;\">** $aporacik->namaracikan</td>
												<td style=\"text-align:right; border-left: none; border-right: none; border-top: none; border-bottom: none;\">".number_format($aporacik->jumlahracik, 0)."</td>
												<td style=\"text-align:right; border-left: none; border-right: none; border-top: none; border-bottom: none;\">".number_format($aporacik->diskonrp, 2)."</td>
												<td style=\"text-align:right; border-left: none; border-right: none; border-top: none; border-bottom: none;\">".number_format((!isset($abc) ? 0 : $abc), 2)."</td>
										</tr>";
				$chari .= "</tbody><tfoot><tr>
										<td style=\"text-align:right; border-right: none; border-left: none;\" colspan=\"4\"><b>Total Resep & Racik</b></td>
										<td style=\"text-align:right; border-left: none; border-right: none;\">".number_format((!isset($abc) ? 0 : $abc + $detotal), 2)."</td>
								</tr></tfoot>";
								$chari .= "</table>";
				// $chari .= "</table>";
			} else {
				$chari .= "";
				$apodresep = $this->db->query("SELECT * FROM tbl_apodresep WHERE resepno = '$param'")->result();
				$abc=0;
				foreach($apodresep as $ap){
					$abc += $ap->totalrp;
				}
				$actualtotal	= ($detail_resep->totalrp + $abc) - (0 - $sisa_voucher);
				if($dresep){
					$chari .= "
											<table style=\"border-collapse:collapse;font-family: tahoma; font-size:16px\" width=\"100%\" align=\"center\" border=\"0\">
													<tr>
																<td><b>RESEP & RACIKAN</b></td>
													</tr> 
											</table>";
					$chari .= "
											<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
													<thead>
																<tr>
																		<td width=\"5%\" align=\"center\" style=\"text-align:center; border-right: none; border-left: none;\"><b>No</b></td>
																		<td width=\"35%\" align=\"center\" style=\"text-align:center; border-right: none; border-left: none;\"><b>Nama Obat</b></td>
																		<td width=\"10%\" align=\"center\" style=\"text-align:center; border-left: none; border-right: none;\"><b>Qty</b></td>
																		<td width=\"20%\" align=\"center\" style=\"text-align:center; border-left: none; border-right: none;\"><b>Diskon</b></td>
																		<td width=\"20%\" align=\"center\" style=\"text-align:center; border-left: none; border-right: none;\"><b>Jumlah</b></td>
																</tr>
													</thead>";
					$detotal = 0;
					foreach($detil as $dt){
						$detotal += $dt->totalrp;
						$chari .= "<tr>
														<td style=\"text-align:center; border-right: none; border-left: none; border-top: none; border-bottom: none;\">" . $no++ . "</td>
														<td style=\"text-align:left; border-right: none; border-left: none; border-top: none; border-bottom: none;\">$dt->namabarang</td>
														<td style=\"text-align:right; border-left: none; border-right: none; border-top: none; border-bottom: none;\">".number_format($dt->qty, 0)."</td>
														<td style=\"text-align:right; border-left: none; border-right: none; border-top: none; border-bottom: none;\">".number_format($dt->discrp, 2)."</td>
														<td style=\"text-align:right; border-left: none; border-right: none; border-top: none; border-bottom: none;\">".number_format($dt->totalrp, 2)."</td>
												</tr>";
					}
					if($racikan){
						$chari .= "<tr>
														<td style=\"text-align:center; border-right: none; border-left: none; border-top: none; border-bottom: none;\">" . $no . "</td>
														<td style=\"text-align:left; border-right: none; border-left: none; border-top: none; border-bottom: none;\">** $racikan->namaracikan</td>
														<td style=\"text-align:right; border-left: none; border-right: none; border-top: none; border-bottom: none;\">".number_format($racikan->jumlahracikan, 0)."</td>
														<td style=\"text-align:right; border-left: none; border-right: none; border-top: none; border-bottom: none;\">".number_format($racikan->discrp, 2)."</td>
														<td style=\"text-align:right; border-left: none; border-right: none; border-top: none; border-bottom: none;\">".number_format($racikan->totalrp, 2)."</td>
												</tr>";
					} else {
						$chari .= "</tbody>";
					}
					$chari .= "<tfoot><tr>
											<td style=\"text-align:right; border-right: none; border-left: none;\" colspan=\"4\"><b>Total Resep & Racik</b></td>
											<td style=\"text-align:right; border-left: none; border-right: none;\">".number_format((!isset($abc) ? 0 : $abc + $detotal), 2)."</td>
									</tr></tfoot>";
									$chari .= "</table>";
				} else {
					$chari .= "";
				}
			}
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
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
											<tr>
														<td> &nbsp; </td>
											</tr> 
									</table>";
			if($dresep){
				$totalin = $abc;
			} else {
				$totalin = 0;
			}
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
											<tr>
												<td width=\"50%\">
													<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
														<tr>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
														</tr>
														<tr>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
															<td>&nbsp;</td>
														</tr>
													</table>
												</td>
												<td width=\"50%\">
													<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">
														<tr>
															<td style=\"text-align:left; border-right: none;\"><b>Total Keseluruhan</b></td>
															<td style=\"text-align:center; border-right: none; border-left: none;\"><b> : </b></td>
															<td style=\"text-align:right; border-left: none;\"><b>".number_format($abc + $detotal + $tot, 2)."</b></td>
														</tr>
													</table>
												</td>
											</tr>
									</table>";
			$uangmuka 		= $this->db->query("SELECT * FROM tbl_kasir WHERE nokwitansi = '$nokwitansi'")->row();
			$dsc = $this->db->query("SELECT 'Diskon Total' as ket, tbl_kasir.diskonrp*-1 as jumlah from tbl_kasir where nokwitansi = '$nokwitansi'")->row();
			$ceksisa = $tot - (0 - $jh1) - (int)$dsc->jumlah;
			if($kasir->bayarcash > 0){
				$bayarcash = number_format($kasir->bayarcash, 2);
			} else {
				$bayarcash = number_format(0, 2);
			}
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
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"40%\" align=\"left\" border=\"0\">
											<tr>
														<td width=\"55%\">Cash Rp</td>
														<td width=\"5%\"> : </td>
														<td width=\"40%\" style=\"text-align: right;\">$bayarcash</td>
											</tr> 
									</table>";
			$query_kartu_card	= $this->db->query("SELECT * FROM tbl_kartukredit WHERE nokwitansi = '$nokwitansi'")->result();
			foreach ($query_kartu_card as $cckey => $ccval) {
				$query_nama_bank	= $this->db->query("SELECT * FROM tbl_edc WHERE bankcode = '$ccval->kodebank' ")->row();
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
				$nocard			= substr($ccval->nocard, 0, $nocard_length) . "XXX";
				$chari .= "
										<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"40%\" align=\"left\" border=\"0\">
												<tr>
															<td width=\"55%\">Bank Penerbit</td>
															<td width=\"5%\"> : </td>
															<td width=\"40%\">$query_nama_bank->namabank</td>
												</tr> 
												<tr>
															<td width=\"55%\">$cardType</td>
															<td width=\"5%\"> : </td>
															<td width=\"40%\">$nocard</td>
												</tr> 
												<tr>
															<td width=\"55%\">Approval Code</td>
															<td width=\"5%\"> : </td>
															<td width=\"40%\" style=\"text-align: right;\">$ccval->nootorisasi</td>
												</tr> 
												<tr>
															<td width=\"55%\">Nominal</td>
															<td width=\"5%\"> : </td>
															<td width=\"40%\" style=\"text-align: right;\">".number_format($ccval->jumlahbayar, 2)."</td>
												</tr>";
			}
			if ($kasir->kembalikeuangmuka > 0) { 
				// $kembalikeuangmuka = number_format($kasir->uangmuka, 2);
				$kembalikeuangmuka = number_format($kasir->kembali, 2);
				$chari .= "<tr>
																<td width=\"55%\">Kembali ke Uang muka</td>
																<td width=\"5%\"> : </td>
																<td width=\"40%\" style=\"text-align: right;\">$kembalikeuangmuka</td>
													</tr> 
											</table>";
			} else {
				$kembali = number_format($kasir->kembali, 2);
				$chari .= "<tr>
																<td width=\"55%\">Kembali ke Pasien</td>
																<td width=\"5%\"> : </td>
																<td width=\"40%\" style=\"text-align: right;\">$kembali</td>
													</tr> 
											</table>";
			}
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
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"right\" border=\"0\">
											<tr>
													<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
													<td width=\"50%\" style=\"text-align:center;\"><b>$kota, ".date('d-m-Y', strtotime($kasir->tglbayar))."</b></td>
											</tr> 
											<tr>
													<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
													<td width=\"50%\" style=\"text-align:center; font-size:14px;\"><b>$namars</b></td>
											</tr> 
											<tr>
													<td width=\"50%\" style=\"text-align:center;\"><b>Pasien</b></td>
													<td width=\"50%\" style=\"text-align:center;\"><b>Cashier</b></td>
											</tr> 
											<tr>
													<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
													<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
											</tr> 
											<tr>
													<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
													<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
											</tr> 
											<tr>
													<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
													<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
											</tr> 
											<tr>
													<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
													<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
											</tr> 
											<tr>
													<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
													<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
											</tr> 
											<tr>
													<td width=\"50%\" style=\"text-align:center;\">$regist->namapas</td>
													<td width=\"50%\" style=\"text-align:center;\">$kasir->username</td>
											</tr> 
									</table>";
			$data['prev'] = $chari;
			$judul = 'KWITANSI PEMBAYARAN';
			echo ("<title>$judul</title>");
			$this->M_cetak->mpdf('P', 'A4', $judul, $chari, '.PDF', 10, 10, 10, 2);
		} else {
			header('location:' . base_url());
		}
	}

	public function cetak_kwitansi2(){
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');
		$user = $this->session->userdata('username');
		$avatar = $this->session->userdata('avatar_cabang');
		$nokwitansi = $this->input->get('kwitansi');
		$noreg = $this->input->get('noreg');
		if(!empty($cek)) {

			$regist = $this->db->query("SELECT tbl_regist.*, tbl_pasien.namapas from tbl_regist inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed where noreg = '$noreg'")->row();

			$kasir = $this->db->query("SELECT * from tbl_kasir where nokwitansi = '$nokwitansi'")->row();

			$detail = $this->db->query("SELECT * from (select tbl_tarifh.tindakan as ket, (tbl_dpoli.tarifrs + tbl_dpoli.tarifdr + tbl_dpoli.paramedis + tbl_dpoli.obatpoli) as jumlah from tbl_dpoli inner join tbl_tarifh on tbl_dpoli.kodetarif=tbl_tarifh.kodetarif where noreg = '$noreg' union	all select 'Adm' as ket, tbl_kasir.adm as jumlah from tbl_kasir where nokwitansi = '$nokwitansi' union	all select 'Diskon Total' as ket, tbl_kasir.diskonrp*-1 as jumlah from tbl_kasir where nokwitansi = '$nokwitansi') kasir where jumlah<>0")->result();

			$hresep = $this->db->query("SELECT * from tbl_apohresep where noreg = '$noreg'")->row();
			
			$voucher = $this->db->query("select 'Voucher' as ket, (tbl_kasir.voucherrp1 + tbl_kasir.voucherrp2 + tbl_kasir.voucherrp3)*-1 as jumlah from tbl_kasir 
			where nokwitansi = '$nokwitansi'")->result();

			$jml_harga = $this->db->query("SELECT sum(tbl_dpoli.tarifrs) AS jumlah FROM tbl_dpoli INNER JOIN tbl_tarifh ON tbl_dpoli.kodetarif=tbl_tarifh.kodetarif WHERE noreg = '$noreg'")->result();

			$uangmuka = $this->db->query("SELECT * FROM tbl_kasir WHERE nokwitansi = '$nokwitansi'")->row();

			$diskon = $this->db->query("SELECT 'Diskon Total' as ket, tbl_kasir.diskonrp*-1 as jumlah from tbl_kasir where nokwitansi = '$nokwitansi'")->row();

			if($kasir->bayarcash > 0){
				$bayarcash = number_format($kasir->bayarcash, 2);
			} else {
				$bayarcash = number_format(0, 2);
			}

			if ($hresep) {
				$eresep = $hresep->eresepno;
				$param = $hresep->resepno;
			} else {
				$eresep = '';
				$param = '';
			}

			// dresep sintaks
			$dresep = $this->db->query("SELECT * FROM tbl_apodresep WHERE resepno = '$param'")->result();

			// posting sintaks
			$posting = $this->db->query("SELECT * from tbl_apoposting  where resepno = '$param'")->row();

			// pajak sintaks
			$pajak = $this->db->query("SELECT * FROM tbl_pajak where kodetax='PPN'")->row();

			// pasien
			$pasien = data_master('tbl_pasien', array('rekmed' => $kasir->rekmed));
			$dracik = $this->db->query("SELECT * from tbl_apodetresep where resepno = '$param' AND koders='$unit'")->result();
			$hracik = $this->db->query("SELECT * from tbl_aporacik where resepno = '$param' AND koders='$unit'")->row();

			// total detail
			$tot_detail = 0;
			foreach($detail as $d){
				$tot_detail += $d->jumlah;
			}

			//total dresep
			$tot_dresep=0;
			foreach($dresep as $ap){
				$tot_dresep += $ap->totalrp;
			}

			// cek kembalian
			if ($kasir->bayarcash == 0) {
				$kembalianx = 0;
			} else {
				$kembalianx = angka_rp($kasir->kembali, 2);
			}

			// sum jumlah detail
			$jum_detail = 0;
			foreach ($detail as $key => $value) {
				$jum_detail += $value->jumlah;
			}

			if($hracik == true && $dracik == true){
				if ($hracik->harga_manual != 0) {
					$hargatotalracik = $hracik->harga_manual;
				} else {
					$hargatotalracik = $hracik->totalrp; 
				}
			} else {
				$hargatotalracik = 0;
			}

			if($dresep){
				$detotal = 0;
				foreach($dresep as $drp){
					$detotal += $drp->totalrp;
				}
			} else {
				$detotal = 0;
			}

			// total semua
			$semuanya_ = $tot_detail + $hargatotalracik + $detotal;

			// sebut total semua
			$terbilang = terbilang($semuanya_);

			// cek voucher
			foreach ($voucher as $v) {
				$vcr1 = 0 - (int)$v->jumlah;
				$vcr = number_format($vcr1);
			}

			// cek jharga
			foreach ($jml_harga as $jh) {
				$jh1 = 0 - (int)$jh->jumlah;
				$jh = number_format($jh1);
			}

			// cek sisa
			$ceksisa = $tot_detail - (0 - $jh1) - (int)$diskon->jumlah;

			// mpdf
			$unit = $this->session->userdata('unit');
			$kop = $this->M_cetak->kop($unit);
			$profile = data_master('tbl_namers', array('koders' => $unit));
			$namars = $kop['namars'];
			$alamat = $kop['alamat'];
			$alamat2 = $kop['alamat2'];
			$alamat3 = $profile->kota;
			$kota = $kop['kota'];
			$phone = $kop['phone'];
			$whatsapp = $kop['whatsapp'];
			$npwp = $kop['npwp'];
			$chari = '';

			// header
			
			$chari .= "<table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
				<tr>
					<td rowspan=\"6\" align=\"center\">
						<img src=\"" . base_url() . "assets/img_user/$avatar\"  width=\"70\" height=\"70\"/>
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
			$chari .= "<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
				<tr>
					<td> &nbsp; </td>
				</tr> 
				<tr>
					<td width=\"15%\" style=\"text-align:center; font-size:20px;\"><b>KWITANSI PEMBAYARAN</b></td>
				</tr>
				<tr>
					<td> &nbsp; </td>
				</tr> 
			</table>";
			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:1px\" width=\"100%\" align=\"center\" border=\"1\">
				<tr>
					<td style=\"border-top: none; border-right: none; border-left: none;\"></td>
				</tr> 
				<tr>
					<td style=\"border-top: none; border-bottom: none; border-right: none; border-left: none;\"> &nbsp; </td>
				<tr>
				</tr>
				<tr>
					<td style=\"border-top: none; border-right: none; border-left: none;\"></td>
				</tr>
			</table>";
			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
				<tr>
					<td> &nbsp; </td>
				</tr> 
				<tr>
					<td> &nbsp; </td>
				</tr> 
			</table>";

			// isi header
			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
				<tr>
					<td width=\"20%\">Sudah terima dari</td>
					<td width=\"5%\"> : </td>
					<td>$kasir->dibayaroleh</td>
				</tr> 
				<tr>
					<td width=\"20%\">No. Member</td>
					<td width=\"5%\"> : </td>
					<td>$kasir->rekmed</td>
				</tr> 
				<tr>
					<td width=\"20%\">Banyaknya Uang</td>
					<td width=\"5%\"> : </td>
					<td>$terbilang</td>
				</tr> 
				<tr>
					<td width=\"20%\">Untuk Pembayaran</td>
					<td width=\"5%\"> : </td>
					<td>Pemeriksaan dokter & Resep</td>
				</tr> 
				<tr>
					<td width=\"20%\">Pasien</td>
					<td width=\"5%\"> : </td>
					<td>$regist->namapas</td>
				</tr>
				<tr colspan=\"3\">
					<td> &nbsp; </td>
				</tr>
			</table>";

			// isi konsul
			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:16px\" width=\"100%\" align=\"center\" border=\"0\">
				<tr>
					<td><b>KONSULTASI</b></td>
				</tr> 
			</table>";
			$chari .= "<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"5\" cellpadding=\"5\">
				<tr>
					<td width=\"5%\" align=\"center\" style=\"text-align:center; border-left: none;\"><b>No</b></td>
					<td width=\"75%\" align=\"center\" style=\"text-align:center; border-right: none; border-left: none;\"><b>Nama Tindakan</b></td>
					<td width=\"20%\" align=\"center\" style=\"text-align:center; border-left: none; border-right: none;\"><b>Jumlah</b></td>
				</tr>";

			// isi detail konsul
			$no = 1;
			$tot = 0;
			foreach ($detail as $db) {
				$tot = $tot + $db->jumlah;
				$chari .= "<tr>
					<td style=\"text-align:center; border-left: none; border-top: none; border-bottom: none;\">" . $no++ . "</td>
					<td style=\"text-align:left; border-right: none; border-left: none; border-top: none; border-bottom: none;\">$db->ket</td>
					<td style=\"text-align:right; border-left: none; border-right: none; border-top: none; border-bottom: none;\">".number_format($db->jumlah, 2)."</td>
				</tr>";

				// jika ada voucher
				if($vcr != 0){
					$chari .= "<tr>
						<td style=\"text-align:center; border-right: none; border-left: none; border-top: none; border-bottom: none;\">" . $no++ . "</td>
						<td style=\"text-align:left; border-right: none; border-left: none; border-top: none; border-bottom: none;\">Voucher $vcr</td>
						<td style=\"text-align:right; border-left: none; border-right: none; border-top: none; border-bottom: none;\">".number_format($jh, 2)."</td>
					</tr>";
				}
			}
			$chari .= "<tr>
				<td style=\"text-align:right; border-right: none; border-left: none;\" colspan=\"2\"><b>Total Konsul</b></td>
				<td style=\"text-align:right; border-left: none; border-right: none;\">".number_format($jum_detail, 2)."</td>
			</tr>";
			$chari .= "</table>";
			// end konsul
			
			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
				<tr>
					<td> &nbsp; </td>
				</tr> 
				<tr>
					<td> &nbsp; </td>
				</tr> 
			</table>";
			
			if($hresep){
				// isi resep racik
				$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:16px\" width=\"100%\" align=\"center\" border=\"0\">
					<tr>
						<td><b>RESEP & RACIKAN</b></td>
					</tr> 
				</table>";
				$chari .= "<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"5\" cellpadding=\"5\">
					<tr>
						<td width=\"5%\" align=\"center\" style=\"text-align:center; border-left: none;\"><b>No</b></td>
						<td width=\"35%\" align=\"center\" style=\"text-align:center; border-right: none; border-left: none;\"><b>Keterangan</b></td>
						<td width=\"10%\" align=\"center\" style=\"text-align:center; border-left: none; border-right: none;\"><b>Qty</b></td>
						<td width=\"20%\" align=\"center\" style=\"text-align:center; border-left: none; border-right: none;\"><b>Diskon</b></td>
						<td width=\"20%\" align=\"center\" style=\"text-align:center; border-left: none; border-right: none;\"><b>Jumlah</b></td>
					</tr>";

				// detail resep
				$no = 1;
				$detotal = 0;
				foreach($dresep as $drp){
					$detotal += $drp->totalrp;
					$chari .= "<tr>
						<td style=\"text-align:center; border-left: none; border-top: none; border-bottom: none;\">" . $no++ . "</td>
						<td style=\"text-align:left; border-right: none; border-left: none; border-top: none; border-bottom: none;\">$drp->namabarang</td>
						<td style=\"text-align:right; border-left: none; border-right: none; border-top: none; border-bottom: none;\">".number_format($drp->qty, 0)."</td>
						<td style=\"text-align:right; border-left: none; border-right: none; border-top: none; border-bottom: none;\">".number_format($drp->discrp, 2)."</td>
						<td style=\"text-align:right; border-left: none; border-right: none; border-top: none; border-bottom: none;\">".number_format($drp->totalrp, 2)."</td>
					</tr>";
				}

				// jika ada racik
				if($hracik == true && $dracik == true){
					// jika menggunakan harga manual
					if ($hracik->harga_manual != 0) {
						$hargatotalracik = $hracik->harga_manual;
					} else {
						$hargatotalracik = $hracik->totalrp; 
					}
					$chari .= "<tr>
						<td style=\"text-align:center; border-left: none; border-top: none; border-bottom: none;\">" . $no++ . "</td>
						<td style=\"text-align:left; border-right: none; border-left: none; border-top: none; border-bottom: none;\">** $hracik->namaracikan</td>
						<td style=\"text-align:right; border-left: none; border-right: none; border-top: none; border-bottom: none;\">".number_format($hracik->jumlahracik, 0)."</td>
						<td style=\"text-align:right; border-left: none; border-right: none; border-top: none; border-bottom: none;\">".number_format($hracik->diskonrp, 2)."</td>
						<td style=\"text-align:right; border-left: none; border-right: none; border-top: none; border-bottom: none;\">".number_format((!isset($hargatotalracik) ? 0 : $hargatotalracik), 2)."</td>
					</tr>";
				} else {
					$hargatotalracik = 0;
				}
				
				$chari .= "<tr>
					<td style=\"text-align:right; border-right: none; border-left: none;\" colspan=\"4\"><b>Total Resep & Racik</b></td>
					<td style=\"text-align:right; border-left: none; border-right: none;\">".number_format((!isset($hargatotalracik) ? 0 : $hargatotalracik + $detotal), 2)."</td>
				</tr>";
				$chari .= "</table>";
				// end resep racik
			} else {
				$hargatotalracik = 0;
				$detotal = 0;
			}
			
			// total keseluruhan (konsul + resep + racik)
			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:16px\" width=\"100%\" align=\"center\" border=\"0\">
				<tr>
					<td width=\"80%\"> &nbsp; </td>
					<td width=\"20%\"> &nbsp; </td>
				</tr>
				<tr>
					<td width=\"80%\" style=\"text-align:right; border-right: none;\"><b>TOTAL KESELURUHAN</b></td>
					<td width=\"20%\" style=\"text-align:right; border-left: none;\"><b>".number_format($hargatotalracik + $detotal + $tot_detail, 2)."</b></td>
				</tr>
			</table>";

			
			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
				<tr>
					<td> &nbsp; </td>
				</tr> 
				<tr>
					<td> &nbsp; </td>
				</tr> 
			</table>";

			// bayar cash
			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"50%\" align=\"left\" border=\"0\">
				<tr>
					<td width=\"40%\">Cash Rp</td>
					<td width=\"10%\"> : Rp.</td>
					<td width=\"50%\" style=\"text-align: right;\">$bayarcash</td>
				</tr> 
				<tr>
					<td width=\"40%\">&nbsp;</td>
					<td width=\"5%\">&nbsp;</td>
					<td width=\"55%\" style=\"text-align: right;\">&nbsp;</td>
				</tr>";

			// cek kartu approved
			$query_kartu_card	= $this->db->query("SELECT * FROM tbl_kartukredit WHERE nokwitansi = '$nokwitansi'")->result();

			foreach ($query_kartu_card as $cckey => $ccval) {
				$query_nama_bank	= $this->db->query("SELECT * FROM tbl_edc WHERE bankcode = '$ccval->kodebank' ")->row();
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
				$nocard = substr($ccval->nocard, 0, $nocard_length) . "XXX";
				$chari .= "<tr>
					<td width=\"40%\">Bank Penerbit</td>
					<td width=\"5%\"> : </td>
					<td width=\"55%\">$query_nama_bank->namabank</td>
				</tr> 
				<tr>
					<td width=\"40%\">$cardType</td>
					<td width=\"5%\"> : </td>
					<td width=\"55%\">$nocard</td>
				</tr> 
				<tr>
					<td width=\"40%\">Approval Code</td>
					<td width=\"5%\"> : </td>
					<td width=\"55%\" style=\"text-align: right;\">$ccval->nootorisasi</td>
				</tr> 
				<tr>
					<td width=\"40%\">Nominal</td>
					<td width=\"5%\"> : Rp.</td>
					<td width=\"55%\" style=\"text-align: right;\">".number_format($ccval->jumlahbayar, 2)."</td>
				</tr>
				<tr>
					<td width=\"40%\">&nbsp;</td>
					<td width=\"5%\">&nbsp;</td>
					<td width=\"55%\" style=\"text-align: right;\">&nbsp;</td>
				</tr>";
			}

			// cek kembali
			if ($kasir->kembalikeuangmuka > 0) {
				$kembalikeuangmuka = number_format($kasir->kembali, 2);
				$chari .= "<tr>
					<td width=\"40%\">Kembali ke Uang muka</td>
					<td width=\"5%\"> : Rp.</td>
					<td width=\"55%\" style=\"text-align: right;\">$kembalikeuangmuka</td>
				</tr>";
			} else {
				$kembali = number_format($kasir->kembali, 2);
				$chari .= "<tr>
					<td width=\"40%\">Kembali ke Pasien</td>
					<td width=\"5%\"> : Rp.</td>
					<td width=\"55%\" style=\"text-align: right;\">$kembali</td>
				</tr>";
			}

			$chari .= "<tr>
				<td width=\"40%\">&nbsp;</td>
				<td width=\"5%\">&nbsp;</td>
				<td width=\"55%\" style=\"text-align: right;\">&nbsp;</td>
			</tr>";

			$chari .= "</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"right\" border=\"0\">
				<tr>
					<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
					<td width=\"50%\" style=\"text-align:center;\"><b>$kota, ".date('d-m-Y', strtotime($kasir->tglbayar))."</b></td>
				</tr> 
				<tr>
					<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
					<td width=\"50%\" style=\"text-align:center; font-size:14px;\"><b>$namars</b></td>
				</tr> 
				<tr>
					<td width=\"50%\" style=\"text-align:center;\"><b>Pasien</b></td>
					<td width=\"50%\" style=\"text-align:center;\"><b>Cashier</b></td>
				</tr> 
				<tr>
					<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
					<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
				</tr> 
				<tr>
					<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
					<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
				</tr> 
				<tr>
					<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
					<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
				</tr> 
				<tr>
					<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
					<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
				</tr> 
				<tr>
					<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
					<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
				</tr> 
				<tr>
					<td width=\"50%\" style=\"text-align:center;\">$regist->namapas</td>
					<td width=\"50%\" style=\"text-align:center;\">$kasir->username</td>
				</tr> 
			</table>";

			// print
			$data['prev'] = $chari;
			$judul = 'KWITANSI PEMBAYARAN';
			echo ("<title>$judul</title>");
			$this->M_cetak->mpdf('P', 'A4', $judul, $chari, '.PDF', 10, 10, 10, 2);
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
			$noreg = $this->input->get('noreg');
			$profile = data_master('tbl_namers', array('koders' => $unit));
			$nama_usaha = $profile->namars;
			$alamat1  = $profile->alamat;
			$alamat2  = $profile->kota;

			$cardType = "";

			$qkasir = "select * from tbl_kasir where nokwitansi = '$nokwitansi'";
			$qheader = "select * from tbl_hpoli where noreg = '$noreg'";
			$qdetil = "select tbl_dpoli.*, tbl_tarifh.tindakan from tbl_dpoli 
			inner join tbl_tarifh on tbl_dpoli.kodetarif=tbl_tarifh.kodetarif
			where noreg = '$noreg'";
			$qregis = "select tbl_regist.*, tbl_pasien.namapas from tbl_regist 
			inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed
			where noreg = '$noreg'";

			//saya ganti
			// $qdetil = "
			// select * from (
			// select tbl_tarifh.tindakan as ket, tbl_dpoli.tarifrs as jumlah from tbl_dpoli 
			// inner join tbl_tarifh on tbl_dpoli.kodetarif=tbl_tarifh.kodetarif
			// where noreg = '$noreg'
			// union all
			// select 'Diskon' as ket, sum(tbl_dpoli.diskrp)*-1 as jumlah from tbl_dpoli 
			// inner join tbl_tarifh on tbl_dpoli.kodetarif=tbl_tarifh.kodetarif
			// where noreg = '$noreg'								
			// union	all 			
			// select 'Adm' as ket, tbl_kasir.adm as jumlah from tbl_kasir
			// where nokwitansi = '$nokwitansi'						
			// union	all 		
			// select 'Diskon Total' as ket, tbl_kasir.diskonrp*-1 as jumlah from tbl_kasir
			// where nokwitansi = '$nokwitansi'			
			// union	all 		
			// select '- Uang Muka' as ket, tbl_kasir.uangmuka*-1 as jumlah from tbl_kasir
			// where nokwitansi = '$nokwitansi'			

			// ) kasir
			// where jumlah<>0
			// ";



			$voucher = $this->db->query("select 'Voucher' as ket, (tbl_kasir.voucherrp1 + tbl_kasir.voucherrp2 + tbl_kasir.voucherrp3)*-1 as jumlah from tbl_kasir 
			where nokwitansi = '$nokwitansi'")->result();
			foreach ($voucher as $v) {
				$vcr1 = 0 - (int)$v->jumlah;
				$vcr = number_format($vcr1);
			}

			$jml_harga = $this->db->query("SELECT sum(tbl_dpoli.tarifrs) AS jumlah FROM tbl_dpoli INNER JOIN tbl_tarifh ON tbl_dpoli.kodetarif=tbl_tarifh.kodetarif WHERE noreg = '$noreg'")->result();
			foreach ($jml_harga as $jh) {
				$jh1 = 0 - (int)$jh->jumlah;
				$jh = number_format($jh1);
			}
			$qdetil = "
			select * from (
			select tbl_tarifh.tindakan as ket, (tbl_dpoli.tarifrs + tbl_dpoli.tarifdr + tbl_dpoli.paramedis + tbl_dpoli.obatpoli) as jumlah from tbl_dpoli 
			inner join tbl_tarifh on tbl_dpoli.kodetarif=tbl_tarifh.kodetarif
			where noreg = '$noreg'
			union	all 			
			select 'Adm' as ket, tbl_kasir.adm as jumlah from tbl_kasir
			where nokwitansi = '$nokwitansi'						
			union	all 		
			select 'Diskon Total' as ket, tbl_kasir.diskonrp*-1 as jumlah from tbl_kasir
			where nokwitansi = '$nokwitansi'
			) kasir
			where jumlah<>0
			";

			$uangmuka 		= $this->db->query("SELECT * FROM tbl_kasir WHERE nokwitansi = '$nokwitansi'")->row();
			$dresep 		= $this->db->query("select * from tbl_apoposting where noreg='$noreg'")->row();
			if ($dresep) {
				$param = $dresep->resepno;
			} else {
				$param = '';
			}

			$query_obat = "select * from tbl_apodresep where resepno = '$param'";
			$check_obat	= $this->db->query($query_obat)->num_rows();
			$detil_obat = $this->db->query($query_obat)->result();
			$tot_obat 	= 0;
			foreach ($detil_obat as $db) {
				$tot_obat = $tot_obat + $db->totalrp;
			}

			$kasir  = $this->db->query($qkasir)->row();
			if ($kasir->bayarcash == 0) {
				$kembalianx = 0;
			} else {
				$kembalianx = angka_rp($kasir->kembali, 2);
			}
			$header = $this->db->query($qheader)->row();
			$regist = $this->db->query($qregis)->row();
			$detil  = $this->db->query($qdetil)->result();

			// $qkartu = "select * from tbl_kartukredit where nokwitansi = '$nokwitansi'";
			// $kartu = $this->db->query($qkartu)->row();

			$query_kartu_card	= $this->db->query("SELECT * FROM tbl_kartukredit WHERE nokwitansi = '$nokwitansi'")->result();

			$pdf = new simkeu_nota();
			$pdf->setID($nama_usaha, $alamat1, $alamat2);
			$pdf->setjudul('KWITANSI ' . $regist->kodepos);
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
			//$judul=array('Kwitansi ','','Faktur Penjualan');
			$fc     = array('0', '0', '0');
			$hc     = array('20', '20', '20');
			//$pdf->FancyRow2(10,$judul, $fc,  $border, $align, $style, $size, $max);
			//$pdf->ln(1);
			$pdf->setfont('Arial', 'B', 10);
			$pdf->SetWidths(array(30, 5, 150, 10, 25, 5, 55));
			$border = array('', '', '', '', '', '', '');
			$fc     = array('0', '0', '0', '0', '0', '0', '0');
			$pdf->SetFillColor(230, 230, 230);
			$pdf->setfont('Arial', '', 8);
			$xxx = 0;
			foreach ($detil as $key => $value) {
				$xxx += $value->jumlah;
			}
			// $terbilang = terbilang($kasir->totalsemua);
			$terbilang = terbilang($xxx);
			$pdf->FancyRow(array('Sudah terima dari', ':', $kasir->dibayaroleh, '', '', '', ''), $fc, $border);
			$pdf->FancyRow(array('No. Member', ':', $kasir->rekmed, '', '', '', ''), $fc, $border);
			$pdf->FancyRow(array('Banyaknya Uang', ':', $terbilang, '', '', '', ''), $fc, $border);
			$pdf->FancyRow(array('Untuk Pembayaran', ':', 'Pemeriksaan dokter & Resep', '', '', '', ''), $fc, $border);
			$pdf->FancyRow(array('Pasien', ':', $regist->namapas, '', '', '', ''), $fc, $border);

			$pdf->ln(2);

			$pdf->SetWidths(array(10, 130, 50));
			$border = array('TB', 'TB', 'TB');
			$align  = array('L', 'L', 'R');
			$pdf->setfont('Arial', 'B', 8);
			$pdf->SetAligns(array('L', 'L', 'R'));
			$fc = array('0', '0', '0', '0', '0', '0');
			$judul = array('No.', 'Keterangan', 'Jumlah');
			$pdf->FancyRow2(8, $judul, $fc, $border, $align);
			$border = array('', '', '');
			$pdf->setfont('Arial', '', 8);
			$tot = 0;
			$subtot = 0;
			$tdisc  = 0;
			$pdf->SetFillColor(0, 0, 139);
			$pdf->settextcolor(0);
			$nomor = 1;

			// $_ket11 = $_ket1 = $_ket2 = $_ket3 = $_ket4 = '';
			// if($kartu){
			//   if($kartu->cardtype==1){
			// 	 $_ket1 = 'DEBIT NO'; 
			//   } elseif($kartu->cardtype==2){
			// 	 $_ket1 = 'CREDIT CARD NO'; 
			//   }	elseif($kartu->cardtype==3){
			// 	 $_ket1 = 'TRANSFER NO'; 
			//   }	
			//   $bank = data_master('tbl_edc',array('bankcode' => $kartu->kodebank));			  
			//   if($bank){
			// 	$_ket2 = $bank->namabank;  
			//   }

			//   $_ket4 = angka_rp($kartu->totalcardrp,0);
			//   $_ket11= $kartu->nootorisasi;
			//   $_ket3 = $kartu->nocard;

			// }
			// if (!empty($_ket3)) {
			// 	$pecah = str_split($_ket3);
			// 	$pecah[13] = "X";
			// 	$pecah[14] = "X";
			// 	$pecah[15] = "X";
			// 	$pecah[16] = "X";
			// 	$pecah = implode("",$pecah);
			// }else{
			// 	$pecah = "";
			// }	

			$dsc = $this->db->query("SELECT 'Diskon Total' as ket, tbl_kasir.diskonrp*-1 as jumlah from tbl_kasir
			where nokwitansi = '$nokwitansi'")->row();

			foreach ($detil as $db) {
				$tot = $tot + $db->jumlah;
				$pdf->FancyRow(array(
					$nomor,
					$db->ket,
					number_format($db->jumlah, 0, '.', ',')
				), $fc, $border, $align);

				$nomor++;
			}
			if($vcr != 0){
				$pdf->FancyRow(array(
					$nomor,
					'Voucher ' . $vcr,
					$jh
				), $fc, $border, $align);
			}
			$border = array('T', 'T', 'T');
			$pdf->FancyRow(array('', '', ''), $fc, $border, $align);

			$align  = array('L', 'L', 'L', 'L', 'C', 'R');
			$pdf->setfont('Arial', 'B', 10);

			/*
			$pdf->SetWidths(array(25,5,55,25,5,25));
			$border = array('','','','LT','T','TR');
			$fc     = array('0','0','0','0','0','0');
			$pdf->SetFillColor(230,230,230);			
			$pdf->setfont('Arial','',8);
			$pdf->ln(1);
			$pdf->FancyRow(array('','','','Total Rp',':',number_format($tot,0,'.',',')), $fc, $border, $align);
			$border = array('','','','LB','B','BR');
			$pdf->FancyRow(array('','','','Pembulatan',':',number_format($tot,0,'.',',')), $fc, $border, $align);
			
			$border = array('','','','','','');
			//$pdf->FancyRow(array('Cash Rp',':',number_format($kasir->bayarcash,0,'.',','),'','',''), $fc, $border, $align);
			*/

			$pdf->SetWidths(array(50, 5, 60, 35, 5, 35));

			$border = array('', '', '', '', '', '');
			$align  = array('L', 'C', 'L', 'L', 'R', 'R');
			$fc = array('0', '0', '0', '0', '0', '0');
			$style = array('', '', '', 'B', 'B', 'B');
			$size  = array('', '', '', '', '', '');
			$border = array('', '', '', '', '', '');
			$border = array('T', 'T', 'T', 'T', 'T', 'T');
			$pdf->FancyRow(array('', '', '', '', '', ''), $fc, $border, $align, $style, $size);

			$fc = array('0', '0', '0', '0', '0', '0');
			$border = array('', '', '', 'LT', 'T', 'RT');
			$lastborder = array('', '', '', 'LB', 'B', 'RB');

			// yang di komen card pay

			$ceksisa = $tot - (0 - $jh1) - (int)$dsc->jumlah;

			// $pdf->FancyRow(array(($uangmuka->uangmuka > 0) ? 'Uang Muka' : '', ($uangmuka->uangmuka > 0) ? ':' : '', ($uangmuka->uangmuka > 0) ? number_format($uangmuka->uangmuka, 0, ',', '.') : '', 'Total Rp', ':', number_format($tot, 2, '.', ',')), $fc, $border, $align, $style, $size);

			// script original
			// $pdf->FancyRow(array(($uangmuka->uangmuka > 0) ? 'Uang Muka' : '', ($uangmuka->uangmuka > 0) ? ':' : '', ($uangmuka->uangmuka > 0) ? number_format($uangmuka->uangmuka, 0, ',', '.') : '', 'Total Rp', ':', number_format($ceksisa, 2, '.', ',')), $fc, $border, $align, $style, $size);
			// $pdf->FancyRow(array(($_ket1==''?'':$_ket1),($_ket1==''?'':':'),$pecah,'Pembulatan',':',number_format($tot,2,'.',',')),$fc, $lastborder, $align, $style, $size); //saya rubah dari $_ket3 menjadi $pecah
			// $pdf->FancyRow(array(($kasir->bayarcash > 0 ? 'Cash Rp' : ''), ($kasir->bayarcash > 0 ? ':' : ''), angka_rp($kasir->bayarcash, 0), 'Pembulatan', ':', number_format($ceksisa, 2, '.', ',')), $fc, $lastborder, $align, $style, $size); //saya rubah dari $_ket3 menjadi $pecah
			
			// husain change
			$pdf->FancyRow(array(($uangmuka->uangmuka > 0) ? 'Uang Muka' : '', ($uangmuka->uangmuka > 0) ? ':' : '', ($uangmuka->uangmuka > 0) ? number_format($uangmuka->uangmuka, 0, ',', '.') : '', 'Total Rp', ':', number_format(0, 2, '.', ',')), $fc, $border, $align, $style, $size);
			$pdf->FancyRow(array(($kasir->bayarcash > 0 ? 'Cash Rp' : ''), ($kasir->bayarcash > 0 ? ':' : ''), angka_rp($kasir->bayarcash, 0), 'Pembulatan', ':', number_format(0, 2, '.', ',')), $fc, $lastborder, $align, $style, $size);
			// end husain

			// $border = array('','','','L','','R');
			// $pdf->FancyRow(array(($_ket2==''?'':'Bank Penerbit'),($_ket2==''?'':':'),$_ket2,'','',''),$fc, 0, $align, $style, $size);
			// $pdf->FancyRow(array(($_ket3==''?'':'Approval Code'),($_ket11==''?'':':'),$_ket11,'','',''),$fc, 0, $align, $style, $size);

			// $border = array('','','','LB','B','BR');
			// $pdf->FancyRow(array(($_ket4==''?'':'Total Rp'),($_ket4==''?'':':'),$_ket4,'','',''),$fc, 0, $align, $style, $size);
			// $border = array('','','','','','');
			// $pdf->FancyRow(array(($kasir->bayarcash>0?'Cash Rp':'Cash Rp'),($kasir->bayarcash>0?':':':'),angka_rp($kasir->bayarcash,0),'','',''),$fc, 0, $align, $style, $size);
			$pdf->ln(5);
			// $_ket11 = $_ket1 = $_ket2 = $_ket3 = $_ket4 = '';
			// if($kartu){
			//   if($kartu->cardtype==1){
			// 	 $_ket1 = 'DEBIT NO'; 
			//   } elseif($kartu->cardtype==2){
			// 	 $_ket1 = 'CREDIT CARD NO'; 
			//   }	elseif($kartu->cardtype==3){
			// 	 $_ket1 = 'TRANSFER NO'; 
			//   }	
			//   $bank = data_master('tbl_edc',array('bankcode' => $kartu->kodebank));			  
			//   if($bank){
			// 	$_ket2 = $bank->namabank;  
			//   }

			//   $_ket4 = angka_rp($kartu->totalcardrp,0);
			//   $_ket11= $kartu->nootorisasi;
			//   $_ket3 = $kartu->nocard;

			// }
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
				$nocard			= substr($ccval->nocard, 0, $nocard_length) . "XXX";

				$pdf->FancyRow(array("Bank Penerbit", ":", $query_nama_bank->namabank), $fc, 0, $align, $style, $size);
				$pdf->FancyRow(array($cardType, ":", $nocard), $fc, 0, $align, $style, $size);
				$pdf->FancyRow(array("Approval Code", ":", $ccval->nootorisasi), $fc, 0, $align, $style, $size);
				$pdf->FancyRow(array("Nominal", ":", "Rp " . number_format($ccval->jumlahbayar, 0, ',', '.')), $fc, 0, $align, $style, $size);
				$pdf->ln(5);
			}
			$pdf->ln(5);
			// $pdf->FancyRow(array(($check_obat > 0) ? 'Pembayaran Obat' : '', ($check_obat > 0) ? ':' : '', ($check_obat > 0) ? number_format($tot_obat, 0, '.', ',') : '', '', '', ''), $fc, 0, $align, $style, $size);
			if ($kasir->kembalikeuangmuka == 0) {
				$pdf->FancyRow(array(($kasir->kembali > 0 ? 'Kembali ke pasien' : ''), ($kasir->kembali > 0 ? ':' : ''), angka_rp($kasir->kembali, 0), '', '', ''), $fc, 0, $align, $style, $size);
			} else {
				$pdf->FancyRow(array(($kasir->kembali > 0 ? 'Kembali ke Uang muka' : ''), ($kasir->kembali > 0 ? ':' : ''), $kembalianx, '', '', ''), $fc, 0, $align, $style, $size);
			}


			$pdf->settextcolor(0);

			$pdf->ln(5);
			$pdf->SetWidths(array(65, 65, 60));
			$border = array('', '', '');
			$align  = array('C', 'C', 'C');
			$pdf->FancyRow(array('', '', $alamat2 . ', ' . date('d-m-Y', strtotime($kasir->tglbayar))), 0, $border, $align);
			$pdf->ln(5);
			$pdf->FancyRow(array('', '', $nama_usaha), 0, $border, $align);
			$pdf->FancyRow(array('Pasien', '', 'Cashier'), 0, $border, $align);
			$pdf->ln(1);
			$pdf->ln(8);
			$pdf->SetWidths(array(65, 65, 60));
			$pdf->SetFont('Arial', '', 8);
			$pdf->SetAligns(array('C', 'C', 'C'));
			$border = array('', '', '');
			$pdf->FancyRow(array('', '', ''), 0, $border, $align);
			$border = array('', '', '');
			$pdf->FancyRow(array($regist->namapas, '', $kasir->username), 0, $border, $align);


			$pdf->setTitle($nokwitansi);
			$pdf->AliasNbPages();
			$pdf->output('./uploads/konsul/' . $kasir->nokwitansi . '.PDF', 'F');
			$pdf->output($nokwitansi . '.PDF', 'I');
		} else {
			header('location:' . base_url());
		}
	}

	public function cetak_jaminan(){
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');
		$user = $this->session->userdata('username');
		$nokwitansi = $this->input->get('kwitansi');
		$noreg = $this->input->get('noreg');
		if (!empty($cek)) {
			$kop       = $this->M_cetak->kop($unit);
			$avatar = $this->session->userdata('avatar_cabang');
			$profile = data_master('tbl_namers', array('koders' => $unit));
			$namars    = $kop['namars'];
			$alamat    = $kop['alamat'];
			$alamat2   = $kop['alamat2'];
			$alamat3  = $profile->kota;
			$kota      = $kop['kota'];
			$phone     = $kop['phone'];
			$whatsapp  = $kop['whatsapp'];
			$npwp      = $kop['npwp'];
			$chari  = '';
			$kasir = $this->db->query("select * from tbl_kasir where nokwitansi = '$nokwitansi'")->row();
			$pasien = $this->db->get_where("tbl_pasien", ['rekmed' => $kasir->rekmed])->row();
			$jaminan = $this->db->query("SELECT * FROM tbl_pap WHERE koders = '$unit' AND nokwitansi = '$nokwitansi'")->result();
			$xxx = 0;
			foreach ($jaminan as $key => $value) {
				$xxx += (int)$value->jumlahhutang + (int)$value->nilaiklaim2;
			}
			$terbilang = terbilang($xxx);
			$regist = $this->db->query("select tbl_regist.*, tbl_pasien.namapas from tbl_regist inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed where noreg = '$noreg'")->row();
			$chari .= "
						<table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
									<thead>
											<tr>
														<td rowspan=\"6\" align=\"center\">
																<img src=\"" . base_url() . "assets/img_user/$avatar\"  width=\"70\" height=\"70\" /></td>
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
									<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
											<tr>
														<td width=\"15%\" style=\"text-align:center; font-size:20px;\"><b>DOKUMEN JAMINAN</b></td>
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
									<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"left\" border=\"0\">     
											<tr>
														<td width=\"18%\" style=\"border-top: none;border-right: none;border-left: none;\">Sudah terima dari</td>
														<td width=\"2%\" style=\"border-top: none;border-right: none;border-left: none;\"> : </td>
														<td style=\"border-top: none;border-right: none;border-left: none;\">$kasir->dibayaroleh</td>
											</tr> 
											<tr>
														<td style=\"border-top: none;border-right: none;border-left: none;\">No. Member</td>
														<td style=\"border-top: none;border-right: none;border-left: none;\"> : </td>
														<td style=\"border-top: none;border-right: none;border-left: none;\">$kasir->rekmed</td>
											</tr> 
											<tr>
														<td style=\"border-top: none;border-right: none;border-left: none;\">Banyaknya Uang</td>
														<td style=\"border-top: none;border-right: none;border-left: none;\"> : </td>
														<td style=\"border-top: none;border-right: none;border-left: none;\">$terbilang</td>
											</tr> 
											<tr>
														<td style=\"border-top: none;border-right: none;border-left: none;\">Untuk Jaminan</td>
														<td style=\"border-top: none;border-right: none;border-left: none;\"> : </td>
														<td style=\"border-top: none;border-right: none;border-left: none;\">Pemeriksaan dokter & Resep</td>
											</tr> 
											<tr>
														<td style=\"border-top: none;border-right: none;border-left: none;\">Pasien</td>
														<td style=\"border-top: none;border-right: none;border-left: none;\"> : </td>
														<td style=\"border-top: none;border-right: none;border-left: none;\">$regist->namapas</td>
											</tr> 
											<tr>
														<td style=\"border-top: none;border-right: none;border-left: none;\">No. Kartu</td>
														<td style=\"border-top: none;border-right: none;border-left: none;\"> : </td>
														<td style=\"border-top: none;border-right: none;border-left: none;\">$pasien->nocard</td>
											</tr> 
											<tr>
														<td style=\"border-top: none;border-right: none;border-left: none;\">NIK</td>
														<td style=\"border-top: none;border-right: none;border-left: none;\"> : </td>
														<td style=\"border-top: none;border-right: none;border-left: none;\">$pasien->noidentitas</td>
											</tr> 
											<tr>
														<td style=\"border-top: none;border-right: none;border-left: none;\">Layanan</td>
														<td style=\"border-top: none;border-right: none;border-left: none;\"> : </td>
														<td style=\"border-top: none;border-right: none;border-left: none;\">RAWAT JALAN</td>
											</tr> 
									</table>";
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
											<tr>
														<td> &nbsp; </td>
											</tr> 
									</table>";
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">     
											<thead>		
												<tr>
														<td width=\"5%\" style=\"text-align:center;\">No</td>
														<td style=\"text-align:center;\">Penjamin</td>
														<td style=\"text-align:center;\">Tercover Rp</td>
														<td style=\"text-align:center;\">COB</td>
														<td style=\"text-align:center;\">Tercover Rp</td>
												</tr> 
											</thead>";
			$no = 1;
			foreach ($jaminan as $j) {
				$xx = $this->db->get_where("tbl_penjamin", ['cust_id' => $j->cust_id])->row();
				$xxx = $this->db->get_where("tbl_penjamin", ['cust_id' => $j->cust_id2])->row();
				$penjamin = $xx->cust_nama;
				$tercover = number_format($j->jumlahhutang, 2);
				$cob = $xxx->cust_nama;
				$tercover2 = number_format($j->nilaiklaim2, 2);
				$chari .= "<tbody><tr>
												<td width=\"5%\" style=\"text-align:center;\">".$no++."</td>
												<td style=\"text-align:left;\">$penjamin</td>
												<td style=\"text-align:right;\">".$tercover."</td>
												<td style=\"text-align:left;\">$cob</td>
												<td style=\"text-align:right;\">".$tercover2."</td>
										</tr></tbody>";
				$totalnya = $j->jumlahhutang + $j->nilaiklaim2;
			}
			$chari .= "</table>";
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
											<tr>
														<td> &nbsp; </td>
											</tr> 
									</table>";
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
											<tr>
														<td>
															<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"50%\" align=\"left\" border=\"0\">
																<tr>
																	<td> &nbsp; </td>
																	<td> &nbsp; </td>
																	<td> &nbsp; </td>
																</tr>
																<tr>
																	<td>&nbsp;</td>
																	<td>&nbsp;</td>
																	<td>&nbsp;</td>
																</t>
																<tr>
																	<td> &nbsp; </td>
																	<td> &nbsp; </td>
																	<td> &nbsp; </td>
																</t>
															</table>
														</td>
														<td>
															<table style=\"border-collapse:collapse;font-family: tahoma; font-size:14px\" width=\"50%\" align=\"right\" border=\"1\">
																<tr>
																	<td width=\"30%\" style=\"border-right: none; border-bottom: none;\"><b>Total Rp</b></td>
																	<td width=\"5%\" style=\"border-right: none; border-left: none; border-bottom: none;\"> : </td>
																	<td width=\"65%\" style=\"text-align:right; border-left: none; border-bottom: none;\"><b>".number_format($totalnya,2)."</b></td>
																</tr>
																<tr>
																	<td width=\"30%\" style=\"border-right: none; border-left: none; border-bottom: none;\">&nbsp;</td>
																	<td width=\"5%\" style=\"border-right: none; border-left: none; border-bottom: none;\">&nbsp;</td>
																	<td width=\"65%\" style=\"text-align:right; border-right: none; border-left: none; border-bottom: none;\">&nbsp;</td>
																</tr>
															</table>
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
									<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
											<tr>
														<td> &nbsp; </td>
											</tr> 
									</table>";
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"right\" border=\"0\">
											<tr>
													<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
													<td width=\"50%\" style=\"text-align:center;\"><b>$kota, ".date('d-m-Y', strtotime($kasir->tglbayar))."</b></td>
											</tr> 
											<tr>
													<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
													<td width=\"50%\" style=\"text-align:center; font-size:14px;\"><b>$namars</b></td>
											</tr> 
											<tr>
													<td width=\"50%\" style=\"text-align:center;\"><b>Pasien</b></td>
													<td width=\"50%\" style=\"text-align:center;\"><b>Cashier</b></td>
											</tr> 
											<tr>
													<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
													<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
											</tr> 
											<tr>
													<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
													<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
											</tr> 
											<tr>
													<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
													<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
											</tr> 
											<tr>
													<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
													<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
											</tr> 
											<tr>
													<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
													<td width=\"50%\" style=\"text-align:center;\"> &nbsp; </td>
											</tr> 
											<tr>
													<td width=\"50%\" style=\"text-align:center;\">$regist->namapas</td>
													<td width=\"50%\" style=\"text-align:center;\">$kasir->username</td>
											</tr> 
									</table>";
			$data['prev'] = $chari;
			$judul = "CETAK DOKUMEN JAMINAN NO. KWITANSI : ".$nokwitansi;
			echo ("<title>$judul</title>");
			$this->M_cetak->mpdf('P', 'A4', $judul, $chari, '.PDF', 10, 10, 10, 2);
		} else {
			header('location:' . base_url());
		}
	}

	public function cetak2()
	{
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');
		$user = $this->session->userdata('username');
		if (!empty($cek)) {

			$unit          = $this->session->userdata('unit');
			$profile       = data_master('tbl_namers', array('koders' => $unit));
			$nama_usaha    = $profile->namars;
			$alamat1       = $profile->alamat;
			$alamat2       = $profile->kota;

			$noreg         = $this->input->get('noreg');
			$kwitansi      = $this->input->get('kwitansi');

			// $dresep = $this->db->query("SELECT * from tbl_apoposting where noreg='$noreg'")->row();
			$dresep = $this->db->query("SELECT * from tbl_apoposting where nokwitansi='$kwitansi' and noreg = '$noreg' order by id desc limit 1")->row();
			if ($dresep) {
				// ex param = DIYR202208000000452
				$param = $dresep->resepno;
			} else {
				$param = '';
			}

			$queryh        = "SELECT * from tbl_apohresep where resepno = '$param'";
			// $queryd        = "SELECT namabarang, qty, discrp, totalrp from tbl_apodresep where resepno = '$param'
			// 				union all
			// 				Select namabarang, qtyr as qty, 0 as discrp, totalrp
			// 				from tbl_apodetresep where resepno = '$param'

			// ";
			$queryd = "SELECT * from tbl_apodresep where resepno = '$param'";
			// $queryz        = "SELECT * from tbl_apodetresep where resepno = '$param'";
			$queryb        = "SELECT * from tbl_apoposting  where resepno = '$param'";

			$qkasir        = "SELECT * from tbl_kasir where nokwitansi = '$kwitansi'";

			$detil         = $this->db->query($queryd)->result();
			// $detilracik    = $this->db->query($queryz)->result();
			$header        = $this->db->query($queryh)->row();
			$posting     = $this->db->query($queryb)->row();
			$kasir       = $this->db->query($qkasir)->row();
			$querypjk    = $this->db->query("SELECT * FROM tbl_pajak where kodetax='PPN'")->row();

			$linediskon		= $this->db->query("SELECT SUM(discrp) AS total FROM tbl_apodresep WHERE resepno = '$param'")->row();
			$linetotal		= $this->db->query("SELECT SUM(totalrp) AS total FROM tbl_apodresep WHERE resepno = '$param'")->row();


			$aporacik = $this->db->get_where('tbl_aporacik', ['resepno' => $param])->row();

			$konsul = $this->db->query("
			select * from (
			select tbl_tarifh.tindakan as ket, tbl_dpoli.tarifrs as jumlah from tbl_dpoli 
			inner join tbl_tarifh on tbl_dpoli.kodetarif=tbl_tarifh.kodetarif
			where noreg = '$noreg'
			union	all 			
			select 'Adm' as ket, tbl_kasir.adm as jumlah from tbl_kasir
			where nokwitansi = '$kwitansi'						
			union	all 		
			select 'Diskon Total' as ket, tbl_kasir.diskonrp*-1 as jumlah from tbl_kasir
			where nokwitansi = '$kwitansi'
			) kasir
			where jumlah<>0
			")->result();
			$bk = 0;
			foreach ($konsul as $k) {
				$bk += $k->jumlah;
			}
			$vcr = $this->db->query("select 'Voucher' as ket, (tbl_kasir.voucherrp1 + tbl_kasir.voucherrp2 + tbl_kasir.voucherrp3)*-1 as jumlah from tbl_kasir 
			where nokwitansi = '$kwitansi'")->result();
			// $v = 0;
			foreach ($vcr as $vc) {
				$v = $vc->jumlah;
				$vn = $vc->ket;
			}
			$sisa_voucher = (int)$v + $bk;

			$detail_resep = $this->db->query("SELECT SUM(totalrp) AS totalrp FROM tbl_apodresep WHERE resepno = '$param'")->row();

			if ((int)$aporacik->harga_manual != 0) {
				$abc = $aporacik->harga_manual;
			} else {
				$abc = $aporacik->totalrp;
			}
			$actualtotal	= ($detail_resep->totalrp + $abc) - (0 - $sisa_voucher);

			if ($header) {
				$data_pasien = data_master('tbl_pasien', array('rekmed' => $header->rekmed));
				$pdf = new simkeu_nota();
				$pdf->setID($nama_usaha, $alamat1, $alamat2);
				$pdf->setjudul('KWITANSI OBAT');
				$pdf->setsubjudul('');
				$pdf->addpage("P", "A4");
				$pdf->setsize("P", "A4");
				$pdf->SetWidths(array(70, 30, 90));
				$border = array('', '', '');
				$size   = array('', '', '');
				$pdf->setfont('Arial', 'B', 18);
				$pdf->SetAligns(array('C', 'C', 'C'));
				$align = array('L', 'C', 'L');
				$style = array('U', '', '');
				$size  = array('12', '', '18');
				$max   = array(5, 5, 20);
				$judul = array('', '', '');
				$fc     = array('0', '0', '0');
				$hc     = array('20', '20', '20');
				//$pdf->FancyRow2(10,$judul, $fc,  $border, $align, $style, $size, $max);
				//$pdf->ln(1);
				$pdf->setfont('Arial', 'B', 10);
				$pdf->SetWidths(array(25, 5, 40, 30, 5, 40, 50));
				$border = array('', '', '', '', '', '', '');
				$fc     = array('0', '0', '0', '0', '0', '0', '0');
				$pdf->SetFillColor(230, 230, 230);
				$pdf->setfont('Arial', '', 9);
				$pdf->FancyRow(array('No. Kwitansi', ':', $kasir->nokwitansi, 'No. Registrasi', ':', $header->noreg, ''), $fc, $border);
				$pdf->FancyRow(array('Nama Pasien', ':', $posting->namapas, 'No. Member', ':', $header->rekmed), $fc, $border);
				$pdf->FancyRow(array('Jam', ':', $kasir->jambayar, '', '', ''), $fc, $border);
				$pdf->FancyRow(array('Dibayar Oleh', ':', $kasir->dibayaroleh, '', '', ''), $fc, $border);
				$pdf->ln(2);

				$pdf->SetWidths(array(80, 30, 30, 50));
				$border = array('LTB', 'TB', 'TB', 'TBR');
				$align  = array('L', 'R', 'R', 'R');
				$pdf->setfont('Arial', 'B', 9);
				$pdf->SetAligns(array('L', 'C', 'R'));
				//$pdf->SetFillColor(0,0,139);
				//$pdf->settextcolor(255,255,255);
				$fc = array('0', '0', '0', '0', '0', '0', '0');
				$judul = array('Keterangan', 'Qty', 'Diskon', 'Jumlah Rp');
				$pdf->FancyRow2(8, $judul, $fc, $border, $align);
				$border = array('', '', '');
				$pdf->setfont('Arial', '', 9);
				$tot = 0;
				$subtot = 0;
				$tdisc  = 0;
				$border = array('', '', '', '', '', '', '');
				$border = array('', '', '', '', '', '', '');
				$align  = array('L', 'R', 'R', 'R', 'R', 'R', 'R');
				$fc = array('0', '0', '0', '0', '0', '0', '0');
				$pdf->SetFillColor(0, 0, 139);
				$pdf->settextcolor(0);
				$no = 1;
				$totitem = 0;
				$nomor = 1;
				$queryr = "SELECT * from tbl_aporacik where resepno = '$param' AND koders='$unit'";
				$detil_r = "SELECT * from tbl_apodetresep where resepno = '$param' AND koders='$unit'";
				$rck = $this->db->query($detil_r)->result();
				$racikan = $this->db->query($queryr)->row_array();
				if ($racikan != null && $rck != null) {
					foreach ($detil as $db) {
						$tot = $tot + $db->totalrp;
						$totitem = $totitem + $db->qty;
						$pdf->FancyRow(array(
							$db->namabarang,
							number_format($db->qty, 0, '.', ','),
							number_format($db->discrp, 2, '.', ','),
							number_format($db->totalrp, 2, '.', ',')
						), $fc, $border, $align);
					}
					$pdf->FancyRow(array(
						("** $aporacik->namaracikan"),
						number_format($aporacik->jumlahracik, 0, ',', '.'),
						number_format($aporacik->diskonrp, 2, ',', '.'),
						number_format((!isset($abc) ? 0 : $abc), 2, ',', '.')
					), $fc, $border, $align);
				}
				// $pdf->FancyRow(
				// 	array("Diskon", "", "", "-" . number_format($linediskon->total, 2, '.', ',')),
				// 	$fc,
				// 	$border,
				// 	$align
				// );
				$pdf->FancyRow(array(
					("$vn"),
					"",
					"",
					number_format($sisa_voucher, 2, ',', '.')
				), $fc, $border, $align);
				$pdf->SetWidths(array(30, 35, 45, 20, 30, 30));

				$border    = array('', '', '', '', '', '');
				$align     = array('L', 'C', 'L', 'L', 'R', 'R');
				$fc        = array('0', '0', '0', '0', '0', '0');
				$style     = array('B', 'B', 'B', 'B', 'B', 'B');
				$size      = array('', '', '', '', '', '');
				$border    = array('', '', '', '', '', '');
				$border    = array('T', 'T', 'T', 'T', 'T', 'T');
				$pdf->FancyRow(array('', '', '', '', '', ''), $fc, $border, $align, $style, $size);
				$fc        = array('0', '0', '0', '0', '0', '0');
				$border    = array('', '', '', 'LT', 'T', 'TR');
				// $pdf->FancyRow(array('', '', '', 'Total Rp', ':', number_format($actualtotal, 2, '.', ',')), $fc, $border, $align, $style, $size);
				$pdf->FancyRow(array('', '', '', 'Total Rp', ':', number_format(0, 2, '.', ',')), $fc, $border, $align, $style, $size);
				$border    = array('', '', '', 'LB', 'B', 'BR');
				// $pdf->FancyRow(array('', '', '', 'Pembulatan', ':', number_format($tot, 2, '.', ',')), $fc, $border, $align, $style, $size);
				$pdf->FancyRow(array('', '', '', 'Pembulatan', ':', number_format(0, 2, '.', ',')), $fc, $border, $align, $style, $size);
				$dppkl = ceil($tot / 1.11);
				// $pdf->FancyRow(array('', '', '', 'Dpp', ':', number_format($dppkl, 2, '.', ',')), $fc, $border, $align, $style, $size);
				// $ppnkl = ceil(($tot / 1.11) * ($querypjk->prosentase / 100));
				// $border    = array('', '', '', 'LB', 'B', 'BR');
				// $pdf->FancyRow(array('', '', '', 'Ppn', ':', number_format($ppnkl, 2, '.', ',')), $fc, $border, $align, $style, $size);
				// $border    = array('', '', '', '', '', '');
				// $pdf->FancyRow(array('', '', '', '', '', ''), $fc, $border, $align, $style, $size);
				$pdf->settextcolor(0);
				$pdf->SetWidths(array(90, 90, 90));
				$pdf->SetFont('Arial', '', 9);
				$pdf->SetAligns(array('C', 'C', 'C'));
				$pdf->ln(6);
				$pdf->ln(5);
				$pdf->SetWidths(array(60, 60, 60));
				$border    = array('', '', '');
				$align     = array('C', 'C', 'C');
				$pdf->FancyRow(array('', '', $alamat2 . ', ' . date('d-m-Y', strtotime($kasir->tglbayar))), 0, $border, $align);
				$pdf->FancyRow(array('', '', $nama_usaha), 0, $border, $align);
				$pdf->FancyRow(array('Pasien', 'Ruang Obat', 'Cashier'), 0, $border, $align);
				$pdf->ln(1);
				$pdf->ln(8);
				$pdf->SetWidths(array(60, 60, 60));
				$pdf->SetFont('Arial', '', 8);
				$pdf->SetAligns(array('C', 'C', 'C'));
				$border    = array('', '', '');
				$pdf->FancyRow(array('', '', ''), 0, $border, $align);
				$border    = array('', '', '');
				$pdf->FancyRow(array($posting->namapas, '', $kasir->username), 0, $border, $align);

				$pdf->SetTitle($param);
				$pdf->AliasNbPages();
				$pdf->output('./uploads/konsul/' . $param . '.PDF', 'F');
				$pdf->output($param . '.PDF', 'I');
			}
		} else {

			header('location:' . base_url());
		}
	}

	function ctk_obt($cek = '', $thnn = '')
	{

		$cek    = '1';
		$chari  = '';
		$cekk         = $this->session->userdata('level');
		$unit   = $this->session->userdata('unit');
		$profile      = data_master('tbl_namers', array('koders' => $unit));
		$nama_usaha   = $profile->namars;
		$alamat1      = $profile->alamat;
		$alamat2      = $profile->kota;

		$noreg        = $this->input->get('noreg');
		$kwitansi     = $this->input->get('kwitansi');

		$dresep       = $this->db->query("select * from tbl_apoposting where noreg='$noreg'")->row();
		if ($dresep) {
			$param = $dresep->resepno;
		} else {
			$param = '';
		}

		$queryh = "SELECT * from tbl_apohresep where resepno = '$param'";
		$queryd = "SELECT * from tbl_apodresep where resepno = '$param'";
		$queryb = "SELECT * from tbl_apoposting  where resepno = '$param'";

		$qkasir = "SELECT * from tbl_kasir where nokwitansi = '$kwitansi'";

		$detil    = $this->db->query($queryd)->result();
		$header   = $this->db->query($queryh)->row();
		$posting  = $this->db->query($queryb)->row();
		$kasir    = $this->db->query($qkasir)->row();

		$cardType = "";
		$query_kartu_card	= $this->db->query("SELECT * FROM tbl_kartukredit WHERE nokwitansi = '$kasir->nokwitansi'")->row();

		$_ket11 = $_ket1 = $_ket2 = $_ket3 = $_ket4 = '';
		if ($query_kartu_card) {
			if ($query_kartu_card->cardtype == 1) {
				$_ket1 = 'DEBIT NO';
			} elseif ($query_kartu_card->cardtype == 2) {
				$_ket1 = 'CREDIT CARD NO';
			} elseif ($query_kartu_card->cardtype == 3) {
				$_ket1 = 'TRANSFER NO';
			}

			$_ket4 = angka_rp($query_kartu_card->totalcardrp, 0);
			$_ket11 = $query_kartu_card->nootorisasi;
			$_ket3 = $query_kartu_card->nocard;

			$bank = data_master('tbl_edc', array('bankcode' => $query_kartu_card->kodebank));
			if ($bank) {
				$_ket2 = $bank->namabank;
			}

			$_ket4 = angka_rp($query_kartu_card->totalcardrp, 0);
			$_ket11 = $query_kartu_card->nootorisasi;
			$_ket3 = $query_kartu_card->nocard;
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
					$param</td>
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
					<td width=\"15%\" style=\"border: none;\">	$kett2</b></td>
					<td width=\"5%\" style=\"border: none;\">	$tikdu2</b></td>
					<td width=\"40%\" style=\"border: none;\">	$_ket2</b></td>
					
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
			$judul          = $param;

			switch ($cek) {
				case 0;
					echo ("<title>$judul</title>");
					echo ($chari);
					break;

				case 1;

					// $this->M_cetak->mpdf('P', 'A4', $judul, $chari, 'KWITANSI-OBAT-' . $nokwitansi . '.PDF', 10, 10, 10, 2);
					$this->M_cetak->mpdf('P', 'A4', $judul, $chari, 'KWITANSI-OBAT.PDF', 10, 10, 10, 2);
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
			$d['master'] = $this->db->get("ms_unit");
			$d['nama_usaha'] = $this->config->item('nama_perusahaan');
			$d['alamat'] = $this->config->item('alamat_perusahaan');
			$d['motto'] = $this->config->item('motto');

			$this->load->view('master/unit/v_master_unit_exp', $d);
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
		$attched_file  = base_url() . "uploads/konsul/" . $data->nokwitansi . ".PDF";
		$mobile = $data->handphone;
		$txtwa   = "KWITANSI KONSUL" . "\r\n" .
			"No. Kwitansi : " . $data->nokwitansi . "\r\n" .
			"No. RM : " . $data->rekmed . "\r\n" .
			"Nama Pasien : " . $data->namapas . "\r\n" .
			"Tanggal : " . date('d M Y', strtotime($data->tglbayar)) . "\r\n" .
			"Jumlah  : " . angka_rp($data->totalsemua, 2) . "\r\n" .
			"Rincian Kwitansi klik link berikut: " . "\r\n" . $attched_file;

		$result = send_wa_txt($mobile, $txtwa);
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
		$attched_file  = base_url() . "uploads/konsul/" . $data->nokwitansi . ".PDF";
		$mobile = $data->handphone;

		$ready_message   = "KWITANSI KONSUL" . "\r\n" .
			"No. Kwitansi : " . $data->nokwitansi . "\r\n" .
			"No. RM : " . $data->rekmed . "\r\n" .
			"Nama Pasien : " . $data->namapas . "\r\n" .
			"Tanggal : " . date('d M Y', strtotime($data->tglbayar)) . "\r\n" .
			"Jumlah  : " . angka_rp($data->totalsemua, 2) . "\r\n";


		$email_tujuan = trim($data->email);

		$server_subject = "KWITANSI KONSUL ";
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
}