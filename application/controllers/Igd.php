<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Igd extends CI_Controller{
  public function __construct(){
		parent::__construct();
		$this->load->model('M_igd');
		$this->load->helper('simkeu_nota');
		$this->load->model('M_cetak');
		$this->load->helper('app_global_helper');
		$this->session->set_userdata('menuapp', '2000');
		$this->session->set_userdata('submenuapp', '2550');
		$this->load->helper('simkeu_nota1');		
		$this->load->helper('simkeu_nota');		
	}

  public function index(){
    $cek 		= $this->session->userdata('username');
		$unit		= $this->session->userdata('unit');
		if(!empty($cek)){
			$average_wait	= $this->db->query("SELECT jam, jamkeluar FROM tbl_rekammedisrs WHERE koders = '$unit'");
			$data	=[
				$id = $this->uri->segment(3),
				$bulan  						= $this->M_global->_periodebulan(),
				$tahun 		    			= $this->M_global->_periodetahun(),
				$nbulan 						= $this->M_global->_namabulan($bulan),
				$periode						= 'Periode '.ucwords(strtolower($nbulan)).' '.$this->M_global->_periodetahun(),
				'total_pasien'  		=> $this->M_igd->total_pasien(),
				'diperiksa_perawat' => $this->M_igd->diperiksa_perawat()->num_rows(),
				'diperiksa_dokter'  => $this->M_igd->diperiksa_dokter()->num_rows(),
				'atime'							=> $average_wait->result(),
				'periode'						=> $periode,
				'menu'							=> 'e-HMS',
				'title'							=> 'IGD',
				'vendor' 	    			=> '',
				'namapos' 					=> $this->db->get('tbl_namapos')->result(),
			];
			$this->load->view('igd/v_igd', $data);
		} else {
			header('location:'.base_url());
		}
  }

  public function ajax_list( $param ){
    setlocale(LC_ALL, 'id_ID.utf8');
    $user_level   = $this->session->userdata('user_level');
		$cabang       = $this->session->userdata("unit");
		$dat          = explode("~",$param);
		if($dat[0] == 1){
			$bulan       = date('m');
			$tahun       = date('Y');
			$poli        = '';
			$kodokter    = '';
			$list        = $this->M_igd->get_datatables( 1, $bulan, $tahun );
		} else {
			$bulan       = date('Y-m-d',strtotime($dat[1]));
			$tahun       = date('Y-m-d',strtotime($dat[2]));
		  $list        = $this->M_igd->get_datatables( 2, $bulan, $tahun);
		}
    $data       = [];
		$no         = $_POST['start'];
		$jenisbayar = ['','Cash','Credit Card','Debet Card','Transfer','Online'];
		foreach ($list as $unit) {
			$query_cek_kasir	= $this->db->query("SELECT * FROM tbl_kasir WHERE noreg = '$unit->noreg' AND koders = '$cabang'")->num_rows();
			$status_kasir		  = ($query_cek_kasir == 0)? "<span class='btn btn-primary btn-sm'>Open</span>" : "<span class='btn btn-danger btn-sm'>Close</span>";

			if($unit->jkel == "P"){
				$jkell = 'Pria';
			}else{
				$jkell = 'Wanita';
			}

			if($unit->jenispas == "PAS1"){
				$jpas = 'UMUM';
			}else{
				$jpas = $unit->cust_nama;
			}

			$no++;
			$row = [];

			if($user_level == 0){	
				$row[]  = '<div class="text-center"><i class="fa fa-solid fa-address-card"></i></div>';
					
			}else{
				$row[]  = '<div class="text-center"><i class="fa fa-solid fa-address-card" onclick="add_list('."'".$unit->noreg."'".', '."'".$unit->rekmed."'".');" data-toggle="modal"></i></div>';
			}
			$row[]    = $unit->noreg;
			$row[]    = $unit->rekmed;
      $tglmasuk = new DateTime($unit->tglmasuk);
			// $row[]    = date("d-m-Y", strtotime($unit->tglmasuk)) ." ". $unit->jam;
			$row[]    = strftime('%A, %d %B %Y', $tglmasuk->getTimestamp()) ."<br>Jam : ". $unit->jam;
			$row[]    = $unit->namapasien_lengkap. ' - [ '. $jkell.' ]';
			$row[]    = $unit->nadokter;
			$row[]    = $jpas;
			$data[]   = $row;
		}

		$output = [
      "draw"            => $_POST['draw'],
      "recordsTotal"    => $this->M_igd->count_all( $dat[0], $bulan, $tahun),
      "recordsFiltered" => $this->M_igd->count_filtered( $dat[0], $bulan, $tahun),
      "data"            => $data,
    ];
    
		echo json_encode($output);
	}

  public function get_detail(){
		$ceknoreg   = $this->input->post('ceknoreg');
		$cekrekmed	= $this->input->post('cekrekmed');
		$koders     = $this->session->userdata('unit');
		$data       = $this->db->query("SELECT * FROM pasien_rajal where koders='$koders' AND kodepos = 'PUGD' AND noreg = '$ceknoreg' AND rekmed = '$cekrekmed'")->row();
		echo json_encode($data);
  }

	public function pemeriksaan_perawat(){
		$cek        = $this->session->userdata("level");
		$koders     = $this->session->userdata("unit");
		$username   = $this->session->userdata("username");
		$ceknoreg   = $this->input->get('noreg');
		$rekmed     = $this->input->get('rekmed');
		if(isset($ceknoreg) && isset($rekmed)){
			$data_pas = $this->db->query("SELECT *FROM pasien_rajal where koders='$koders' and noreg='$ceknoreg'")->row();
			$qcek = $this->db->query("SELECT * FROM tbl_rekammedisrs WHERE noreg = '$ceknoreg' and rekmed = '$rekmed' and koders='$koders'")->result_array();
			$qcek2 = count($qcek);
			if ($qcek2 > 0) {
				$ttv = $this->db->query("SELECT * FROM tbl_rekammedisrs WHERE noreg = '$ceknoreg' and rekmed = '$rekmed' and koders='$koders'")->row();
			}else{
				$ttv = $this->db->query("SELECT '-' as id ,'-' as koders ,'-' as noreg ,'-' as rekmed ,'-' as tglperiksa ,'-' as tglkeluar ,'-' as jam ,'-' as tujuan ,'-' as kodepos ,'-' as koderuang ,'-' as keluhanawal ,'-' as pfisik ,'-' as diagnosa ,'-' as simpul ,'-' as anjuran ,'-' as resep ,'-' as kodeicd ,'-' as kodeicd2 ,'-' as kodeicd3 ,'-' as keadaan_pulang ,'-' as ketpulang ,'-' as kodokter ,'-' as tglkonsul ,'-' as rcounter ,'' as nadi ,'-' as nadi2 ,'-' as nafas ,'-' as tdarah ,'-' as tdarah1 ,'-' as suhu ,'-' as tinggibadan ,'-' as beratbadan ,'-' as bmi ,'-' as bmiresult ,'-' as tglkembali ,'-' as jamkembali ,'-' as nojanji ,'-' as dikonsulkepoli ,'-' as dikonsuldr ,'-' as jamdikonsul ,'-' as tgldikonsul ,'-' as noregkonsul ,'-' as surat1 ,'-' as surat2 ,'-' as surat3 ,'-' as surat4 ,'-' as alasanpulang ,'-' as urutkonsul ,'-' as oksigen ,'-' as kejiwaan ,'-' as lokalis ,'-' as rencana ,'-' as eye ,'-' as verbal ,'-' as movement ,'-' as nyeri ,'-' as urutin ,'-' as rmutama ,'-' as diagu ,'-' as diags ,'-' as tindu ,'-' as tinds ,'-' as asal ,'-' as gambar1 ,'-' as gambar2 ,'-' as pasienvk ,'-' as dead from tbl_rekammedisrs limit 1")->row();
			}
			$query_kasir		= $this->db->query("SELECT * FROM tbl_kasir WHERE noreg = '$ceknoreg' AND koders = '$koders' ")->num_rows();
			if(!empty($cek)){
				$data	= [
					"menu" 		=> "Bedah Central",
					"submenu" 	=> "Persetujuan Umum",
					"link"		=> "Bedah_Central",
					"unit"		=> $koders,
					"data_pas"	=> $data_pas,
					"ttv"		=> $ttv,
					"status_kasir"	=> $query_kasir,
				];
				$this->load->view("igd/v_igd_p_perawat", $data);
			} else {
				redirect("/");
			}
		} else {
			$this->session->set_flashdata("session", "Sesi sudah berakhir, silahkan pilih kembali");
			redirect("/Igd");
		}
	}

	public function ajax_add_per($param){
		$cabang       	= $this->session->userdata('unit');
		$userid       	= $this->session->userdata('username');
		$noreg_per    	= $this->input->post('noreg_per');
		$rekmed_per   	= $this->input->post('rekmed_per');
		$pfisik					= "Nadi ". $this->input->post("nadi") ."/Menit, Pernafasan ". $this->input->post("nafas") ."/Menit, SPO2 ". $this->input->post("oksi") ."%, Suhu ". $this->input->post("suhu") ."° Celcius, Tekanan Darah ". $this->input->post("tekanan") ." / ". $this->input->post("tekanan1") .", Berat Badan ". $this->input->post("berat") ." kg, Tinggi Badan ". $this->input->post("tinggi") ." cm, BMI ". $this->input->post("bmi") ." - ". $this->input->post("bmi_result");
		$data = [
			'koders'       	=> $cabang,
			'noreg'        	=> $this->input->post('noreg_per'),
			'rekmed'       	=> $this->input->post('rekmed_per'),
			'tglperiksa'   	=> $this->input->post('tgl_per'),
			'tglkonsul'    	=> $this->input->post('tgl_per'),
			'kodepos'      	=> $this->input->post('poli_per'),
			'keluhanawal'  	=> $this->input->post('kelawal_per'),
			'nadi'         	=> $this->input->post('nadi'),
			'tinggibadan'  	=> $this->input->post('tinggi'),
			'beratbadan'   	=> $this->input->post('berat'),
			'nafas'        	=> $this->input->post('nafas'),
			'oksigen'      	=> $this->input->post('oksi'),
			'bmi'          	=> $this->input->post('bmi'),
			'bmiresult'    	=> $this->input->post('bmi_result'),
			'suhu'         	=> $this->input->post('suhu'),
			'tdarah'       	=> $this->input->post('tekanan'),
			'tdarah1'      	=> $this->input->post('tekanan1'),
			'dead'         	=> $this->input->post('doa_hidden'),
			'pfisik'       	=> $pfisik,
			'jam'          	=> date('H:i:s'),
			'jamdikonsul'		=> date('H:i:s'),
			'alergi'				=> $this->input->post('alergi_per'),
		];

		if(!empty($userid)){
			$alergi	= $this->input->post("alergi_per");
			$this->db->query("UPDATE tbl_pasien SET alergi = '$alergi' WHERE rekmed = '$rekmed_per' AND koders = '$cabang'");
			$this->db->query("UPDATE tbl_regist SET diperiksa_perawat = 1 WHERE noreg = '$noreg_per' AND koders = '$cabang'");
			$qcek = $this->db->query("SELECT * FROM tbl_rekammedisrs WHERE noreg = '$noreg_per' and rekmed = '$rekmed_per' and koders='$cabang'")->result();
			$cek = count($qcek);
			if ($cek > 0) {
				$update = $this->db->update('tbl_rekammedisrs',$data,
				[
					'noreg'   => $noreg_per,
					'rekmed'  => $rekmed_per,
					'koders'  => $cabang
				]);
				history_log(0 ,'POLI_PERIKSA_PERAWAT' ,'EDIT' ,$noreg_per ,'-');
				echo json_encode(["status" => 1,"nomor" => $noreg_per]);
			} else {
				$insert = $this->db->insert('tbl_rekammedisrs',$data);
				history_log(0 ,'POLI_PERIKSA_PERAWAT' ,'ADD' ,$noreg_per ,'-');
				echo json_encode(["status" => 2,"nomor" => $noreg_per]);
			}
		} else {
			header('location:'.base_url());
		}
	}

	public function pemeriksaan_dokter(){
		$cek        = $this->session->userdata("level");
		$koders     = $this->session->userdata("unit");
		$username   = $this->session->userdata("username");
		$ceknoreg   = $this->input->get('noreg');
		$rekmed     = $this->input->get('rekmed');
		if(isset($ceknoreg) && isset($rekmed)){
			$data_pas = $this->db->query("SELECT (select nadokter from tbl_dokter a where a.kodokter=pasien_rajal.kodokter and a.koders=pasien_rajal.koders limit 1)nadokter, pasien_rajal.* FROM pasien_rajal where koders='$koders' and noreg='$ceknoreg'")->row();
			$qcek = $this->db->query("SELECT * FROM tbl_rekammedisrs WHERE noreg = '$ceknoreg' and rekmed = '$rekmed' and koders='$koders'")->num_rows();
			$ttv = $this->db->query("SELECT * FROM tbl_rekammedisrs WHERE noreg = '$ceknoreg' and rekmed = '$rekmed' and koders='$koders'")->row();
			$data_regist	= $this->db->query("SELECT * FROM pasien_rajal WHERE noreg = '$ceknoreg' AND rekmed = '$rekmed'");

			// ICDTR //
			$icd = $this->db->query("SELECT * FROM tbl_icdtr WHERE noreg = '$ceknoreg' and koders='$koders'")->num_rows();
			if ($icd > 0) {
				$detilicd    = $this->db->query("SELECT a.*,b.sab, concat( b.code,' | ', b.str,' | ', b.code2 )nmdiag, case when b.sab='ICD10_1998' then 'DG01' else 'DG02' end as jns  FROM tbl_icdtr a left join tbl_icdinb b on a.icdcode=b.code WHERE noreg = '$ceknoreg' and koders='$koders'")->result();
			}else{
				$detilicd = "";
			}

			// BILLING //
			$billing = $this->db->query("SELECT * FROM tbl_dpoli WHERE noreg = '$ceknoreg' and koders='$koders'")->num_rows();
			if ($billing > 0) {
				$detilbilling    = $this->db->query("SELECT concat( ' [',b.kodetarif,'] ',' - ',' [',b.tindakan,'] ' ) as nm_tin, (select c.nadokter from tbl_dokter c where a.kodokter=c.kodokter limit 1)nadok, (select c.nadokter from tbl_dokter c where a.koperawat=c.kodokter limit 1)naper ,a.* FROM tbl_dpoli a left join daftar_tarif_nonbedah b on a.koders=b.koders and a.kodetarif=b.kodetarif WHERE a.noreg = '$ceknoreg' and a.koders='$koders'")->result();
			}else{
				$detilbilling = "";
			}

			// ALKES BHP //
			$alkes = $this->db->query("SELECT * FROM tbl_alkestransaksi WHERE notr = '$ceknoreg' and koders='$koders'")->num_rows();
			if ($alkes > 0) {
				$detilalkes    = $this->db->query("SELECT REPLACE(concat(' [ ', kodebarang ,' ] ',' - ',' [ ', namabarang ,' ] ', ' - ',' [ ', satuan1 ,' ] '),'   ','') as nm_brg, a.* FROM tbl_alkestransaksi a LEFT JOIN tbl_barang b ON a.kodeobat=b.kodebarang WHERE notr = '$ceknoreg' and koders='$koders' ")->result();
			}else{
				$detilalkes = "";
			}

			// ORDER PERIKSA //
			$eresep			= $this->db->query("SELECT * FROM tbl_orderperiksa WHERE noreg = '$ceknoreg' AND koders = '$koders'");
			$eresep_row	= $eresep->num_rows();
			if($eresep_row != 0){
				$eresep_val			= "done";
				$order_periksa	= $eresep->row();
			} else {
				$eresep_val			= "undone";
				$order_periksa	= "";
			}

			// ERESEP //
			$query_eresep	= $this->db->query("SELECT concat(' [ ', kodeobat ,' ] ',' - ',' [ ', namaobat ,' ] ',' - ',' [ ', satuan ,' ] ',' - ',' [ ', harga ,' ]') as namaobatnya, tbl_eresep.* FROM tbl_eresep WHERE noreg = '$ceknoreg' AND koders = '$koders'");
			$eresep_row	= $query_eresep->num_rows();
			if($eresep_row != 0){
				$status_eresep	= "done";
				$detileresep		= $query_eresep->result();
			} else {
				$status_eresep	= "undone";
				$detileresep		= "";
			}

			// RACIKAN //
			$query_racikan1	= $this->db->query("SELECT CONCAT(' [ ', kodeobat ,' ] ',' - ',' [ ', namaobat ,' ] ',' - ',' [ ', satuan ,' ] ',' - ',' [ ', harga ,' ]') as namaobatr1, tbl_eracik.* FROM tbl_eracik WHERE racikid = 'RACIK1' AND noreg = '$ceknoreg' AND koders = '$koders'");
			$query_racikan2	= $this->db->query("SELECT CONCAT(' [ ', kodeobat ,' ] ',' - ',' [ ', namaobat ,' ] ',' - ',' [ ', satuan ,' ] ',' - ',' [ ', harga ,' ]') as namaobatr2, tbl_eracik.* FROM tbl_eracik WHERE racikid = 'RACIK2' AND noreg = '$ceknoreg' AND koders = '$koders'");
			$query_racikan3	= $this->db->query("SELECT CONCAT(' [ ', kodeobat ,' ] ',' - ',' [ ', namaobat ,' ] ',' - ',' [ ', satuan ,' ] ',' - ',' [ ', harga ,' ]') as namaobatr3, tbl_eracik.* FROM tbl_eracik WHERE racikid = 'RACIK3' AND noreg = '$ceknoreg' AND koders = '$koders'");
			$racik1_row	= $query_racikan1->num_rows();
			$racik2_row	= $query_racikan2->num_rows();
			$racik3_row	= $query_racikan3->num_rows();

			if($racik1_row == 0){
				$status_racik1	= "undone";
				$detilracik1		= "";
			} else {
				$status_racik1	= "done";
				$detilracik1		= $query_racikan1->result();
			}

			if($racik2_row == 0){
				$status_racik2	= "undone";
				$detilracik2		= "";
			} else {
				$status_racik2	= "done";
				$detilracik2		= $query_racikan2->result();
			}

			if($racik3_row == 0){
				$status_racik3	= "undone";
				$detilracik3		= "";
			} else {
				$status_racik3	= "done";
				$detilracik3		= $query_racikan3->result();
			}

			// STATUS ORDER //
			$data_order_periksa	= $this->db->query("SELECT orderno, tglorder, proses FROM tbl_orderperiksa WHERE orderno LIKE '%ER%' AND orderno NOT LIKE '%ERD%' AND orderno NOT LIKE '%ERM%' AND noreg = '$ceknoreg' AND koders = '$koders'");
			if($data_order_periksa->num_rows() == 0){
				$status_dop		= "undone";
				$data_dop			= "";
				$dop_row			= "";
			} else {
				$status_dop		= "done";
				$data_dop			= $data_order_periksa->result();
				$dop_row			= $data_order_periksa->row();
			}

			// HISTORY PASIEN
			$query_hispas	= $this->db->query("SELECT * FROM tbl_rekammedisrs WHERE rekmed = '$rekmed'");
			if($query_hispas->num_rows() == 0){
				$status_hispas	= "undone";
				$data_hispas		= "";
				$total_hispas		= "0";
			} else {
				$status_hispas	= "done";
				$data_hispas		= $query_hispas->result();
				$total_hispas		= $query_hispas->num_rows();
			}

			//E_LAB
			$query_list	= $this->db->query("SELECT CONCAT('[ ', kodetarif ,' ] - [ ', tindakan ,' ]') AS text, kodetarif AS kodeid FROM daftar_tarif_nonbedah WHERE kodepos = 'LABOR' AND koders = '$koders' ORDER BY tindakan ASC")->result();
			$query_orderelab	= $this->db->query("SELECT * FROM tbl_orderperiksa WHERE koders = '$koders' AND noreg = '$ceknoreg' AND orderno LIKE '%EL%'");
			if($query_orderelab->num_rows() == 0){
				$status_elab		= "undone";
				$data_orderlab	= "";
				$total_elab			= "0";
			} else {
				$status_elab		= "done";
				$data_orderlab	= $query_orderelab->result();
				$total_elab			= $query_orderelab->num_rows();
			}
			$query_emed_unit	= $this->db->query("SELECT kodepos AS id, namapost AS text FROM tbl_namapos")->result();

			//E_ERM
			$query_orderemed	= $this->db->query("SELECT * FROM tbl_orderperiksa WHERE koders = '$koders' AND noreg = '$ceknoreg' AND orderno LIKE '%ERM%'");
			if($query_orderemed->num_rows() == 0){
				$status_emed		= "undone";
				$data_ordermed	= "";
				$total_emed			= "0";
			} else {
				$status_emed		= "done";
				$data_ordermed	= $query_orderemed->result();
				$total_emed			= $query_orderemed->num_rows();
			}

			//E-RAD
			$query_ordererad	= $this->db->query("SELECT * FROM tbl_orderperiksa WHERE koders = '$koders' AND noreg = '$ceknoreg' AND orderno LIKE '%ERD%'");
			if($query_ordererad->num_rows() == 0){
				$status_erad		= "undone";
				$data_ordererad	= "";
				$total_erad			= "0";
			} else {
				$status_erad		= "done";
				$data_ordererad	= $query_ordererad->result();
				$total_erad			= $query_ordererad->num_rows();
			}

			// STATUS CLOSE BILL
			$query_kasir		= $this->db->query("SELECT * FROM tbl_kasir WHERE noreg = '$ceknoreg' AND koders = '$koders' ")->num_rows();

			// RIWAYAT PASIEN
			$riwayat_pasien	= $this->db->query("SELECT * FROM tbl_pasien_catatan WHERE rekmed = '$rekmed'");

			if($riwayat_pasien->num_rows() == 0){
				$data_riwayat_pasien	= (object) [
					"riwayat_penyakit"	=> "-",
					"riwayat_keluarga"	=> "-"
				];
			} else {
				$data_riwayat_pasien	= $riwayat_pasien->row();
			}

			// PCARE
			$pcarepoli					= $this->db->get_where("bpjs_pcare_poli", ["poliSakit" => "1"]);
			$pcaredr						= $this->db->get("bpjs_pcare_dokter");
			$pcarestatuspulang	= $this->db->get("bpjs_pcare_status_pulang");
			$pcarekesadaran     = $this->db->get("bpjs_pcare_kesadaran");

			// VALUE
			if(!empty($cek)){
				$data	= [
					"menu" 					=> "Bedah Central",
					"submenu" 			=> "Persetujuan Umum",
					"link"					=> "Bedah_Central",
					"unit"					=> $koders,
					"data_pas"			=> $data_pas,
					"ttv"						=> $ttv,
					"detilicd"  		=> $detilicd,
					"detilbilling"  => $detilbilling,
					"detilalkes"    => $detilalkes,
					"statuspu"			=> ($qcek > 0)? "done" : "undone",
					"statusicd"			=> ($icd > 0)? "done" : "undone",
					"jumdataicd"		=> $icd,
					"statusbill"		=> ($billing > 0)? "done" : "undone",
					"jumdatabill"		=> $billing,
					"statusalkes"		=> ($alkes > 0)? "done" : "undone",
					"jumdataalkes"	=> $alkes,
					"eresep"				=> $eresep_val,
					"orderperiksa"	=> $order_periksa,
					"status_eresep"	=> $status_eresep,
					"detileresep"		=> $detileresep,
					"jumdataer"			=> $eresep_row,
					"status_racik1"	=> $status_racik1,
					"status_racik2"	=> $status_racik2,
					"status_racik3"	=> $status_racik3,
					"detil_racik1"	=> $detilracik1,
					"detil_racik2"	=> $detilracik2,
					"detil_racik3"	=> $detilracik3,
					"jumdataracik1" => $racik1_row,
					"jumdataracik2"	=> $racik2_row,
					"jumdataracik3"	=> $racik3_row,
					"status_dop"		=> $status_dop,
					"data_dop"			=> $data_dop,
					"status_hispas"	=> $status_hispas,
					"data_hispas"		=> $data_hispas,
					"total_hispas"	=> $total_hispas,
					"list_elab"			=> $query_list,
					"status_elab"		=> $status_elab,
					"data_orderlab"	=> $data_orderlab,
					"total_elab"		=> $total_elab,
					"emed_unit"			=> $query_emed_unit,
					"status_emed"		=> $status_emed,
					"data_orderemed"=> $data_ordermed,
					"total_emed"		=> $total_emed,
					"status_erad"		=> $status_erad,
					"data_ordererad"=> $data_ordererad,
					"total_erad"		=> $total_erad,
					"status_kasir"	=> ($query_kasir == 0)? 0 : 1,
					"riwayat_pasien"=> $data_riwayat_pasien,
					"data_regist"		=> $data_regist->row(),
					"pcare_poli"		=> $pcarepoli,
					"pcare_dr"			=> $pcaredr,
					"pcare_sp"			=> $pcarestatuspulang,
					"pcare_sadar"   => $pcarekesadaran,	
					"kodokter"			=> $data_pas->kodokter,
				];				
				$this->load->view("igd/v_poliklinik_p_dokter_add", $data);
			} else {
				redirect("/");
			}
		} else {
			$this->session->set_flashdata("session", "Sesi sudah berakhir, silahkan pilih kembali");
			redirect("/Igd");
		}
	}

	public function ajax_add_dokter($param=""){
		$tanggal	  		= date("Y-m-d H:i:s");
		$cabang       	= $this->session->userdata('unit');
		$userid       	= $this->session->userdata('username');
		$noreg_dok    	= $this->input->post('noreg_dok');
		$rekmed_dok   	= $this->input->post('rekmed_dok');
		$poli_dok     	= $this->input->post('poli_dok');
		$gudang					= $this->input->post("gudang");
		$orderno				= $this->input->post("noeresephide");
		// $gudang_bhp			= $this->input->post("gudang_bhp");
		$gudang_bhp			= $this->input->post("gudang_bhp_x");
		$data_pasien_d	= $this->db->query("SELECT * FROM pasien_rajal WHERE noreg = '$noreg_dok' AND koders = '$cabang'")->row();

		$datarekmed = [
			'koders'       	=> $cabang,
			'noreg'        	=> $noreg_dok,
			'rekmed'       	=> $rekmed_dok,
			'kodepos'      	=> $poli_dok,
			'keluhanawal'  	=> $this->input->post('kelawal'),
			'pfisik'       	=> $this->input->post('pemeriksaan'),
			'diagnosa'     	=> $this->input->post('diagmas'),
			'resep'        	=> $this->input->post('teresep'),
			'kodokter'			=> $this->input->post('selectdr'),
			'diagu'        	=> $this->input->post('diagnosa'),
			'tindu'        	=> $this->input->post('tindu'),
			'anjuran'      	=> $this->input->post('anjuran'),
			'tglkeluar'    	=> date('Y-m-d'),
			'jamkeluar'			=> date('H:i:s'),
			'nyeri'					=> $this->input->post('ceknyeri'),
			'alergi'				=> $this->input->post('alergi_dok'),
			'ijinsakit'			=> $this->input->post('sakitselama'),
			'ijindari'			=> $this->input->post('ijindari'),
			'ijinsampai'		=> $this->input->post('ijinsampai'),
			'butawarna'			=> $this->input->post('butawarna'),
			'sehat'					=> $this->input->post('sehat'),
			'ketsehat'			=> $this->input->post('ketsehat'),
			'ketsehatuntuk'	=> $this->input->post('ketsehatuntuk')
		];

		$datahpoli = [
			'koders'       => $cabang,
			'noreg'        => $noreg_dok,
			'rekmed'       => $rekmed_dok,
			'tglperiksa'   => $this->input->post('tgl_dok'),
			'jam'          => $this->input->post('jam'),
			'username'     => $userid,
			'shipt'        => 0,
			'plcounter'    => 0,
			'jam'          => date('H:i:s'),
		];

		if(!empty($userid)){
			$alergi	= $this->input->post("alergi_dok");

			$this->db->query("UPDATE tbl_pasien SET alergi = '$alergi' WHERE rekmed = '$rekmed_dok' AND koders = '$cabang'");
			$this->db->query("UPDATE tbl_regist SET diperiksa_dokter = 1 WHERE noreg = '$noreg_dok' AND koders = '$cabang'");

			//-- Diagnosa ICD --//
			$c_jenis_diag    = $this->input->post('jenis_diag');
			$c_diag          = $this->input->post('diag');
			$c_utama         = $this->input->post('utama_hide');

			$jumdata = count($c_diag);
			
			for($i=0;$i<=$jumdata-1;$i++){
				$data_icdtr = [
					'koders'   => $cabang,
					'noreg'    => $noreg_dok,
					'rekmed'   => $rekmed_dok,
					'icdcode'  => $c_diag[$i],
					'kelompok' => $c_jenis_diag[$i],
					'utama'    => $c_utama[$i],
				];
				if($c_diag[$i]!=""){
					$qcek = $this->db->query("SELECT * FROM tbl_icdtr WHERE noreg = '$noreg_dok' and icdcode = '$c_diag[$i]' and koders='$cabang'")->result_array();
					$cek = count($qcek);
					if ($cek > 0) {
						$delicd = $this->db->query("DELETE from tbl_icdtr WHERE noreg = '$noreg_dok' and koders='$cabang' /*and icdcode = '$c_diag[$i]'*/ ");
						if($delicd){
							$this->db->insert('tbl_icdtr',$data_icdtr);
						}
					}else{
						$insert_detil = $this->db->insert('tbl_icdtr',$data_icdtr);
					}
				}
			}

			//-- Billing --//
			$kodetarif = $this->input->post('kode');
			$harga     = $this->input->post('hrg');
			$dokter    = $this->input->post('dokter');
			$perawat   = $this->input->post('paramedis');
			$this->db->query("DELETE from tbl_dpoli WHERE noreg = '$noreg_dok' and koders='$cabang'");
			$jumdatabilling = count($kodetarif);
			for($i=0;$i<=$jumdatabilling-1;$i++){
				$_harga   	= str_replace(',','',$harga[$i]);
				if($dokter[$i]){
					$_dokter 	= $dokter[$i];
				} else {
					$_dokter	= '';
				}
				if($perawat[$i]){
					$_perawat	= $perawat[$i];
				} else {
					$_perawat	= '';
				}
				$datatarif 	= $this->db->query("SELECT *	from daftar_tarif_nonbedah where daftar_tarif_nonbedah.koders='$cabang' and daftar_tarif_nonbedah.kodetarif ='$kodetarif[$i]'")->row();

				$data_rinci = [
					'clientid'    	=> $cabang,
					'koders'      	=> $cabang,
					'noreg'       	=> $noreg_dok,
					'pos'         	=> $poli_dok,
					'kodokter'    	=> $_dokter,
					'kodetarif'   	=> $kodetarif[$i],
					'qty'	      		=> 1,
					'tarifrs'     	=> $datatarif->tarifrspoli,
					'tarifdr'     	=> $datatarif->tarifdrpoli,
					'paramedis'			=> $datatarif->feemedispoli,
					'obatpoli'			=> $datatarif->obatpoli,
					'bahan'       	=> 0,
					'koperawat'   	=> $_perawat,
				];
				if($kodetarif[$i]!=""){
					$insert_detil = $this->db->insert('tbl_dpoli',$data_rinci);
				}
			}

			//-- ALKES --//
			$c_bbn       = $this->input->post('bbn_hide');
			$c_kdalkes   = $this->input->post('kdalkes');
			$c_satalkes  = $this->input->post('satalkes');
			$c_qtyalkes  = $this->input->post('qtyalkes');
			$c_hrgalkes  = $this->input->post('hrgalkes');
			$c_totalkes  = $this->input->post('totalkes');

			if(isset($c_kdalkes)){
				$this->db->query("DELETE from tbl_alkestransaksi WHERE notr = '$noreg_dok' and koders='$cabang'");
				$jumdataalkes = count($c_kdalkes);
				for($i=0;$i<=$jumdataalkes-1;$i++){
					$_c_hrgalkes   = str_replace(',','',$c_hrgalkes[$i]);
					$_c_totalkes   = str_replace(',','',$c_totalkes[$i]);
					$data_alkes = [
						'koders'       => $cabang,
						'notr'         => $noreg_dok,
						'kodeobat'     => $c_kdalkes[$i],
						'qty'          => $c_qtyalkes[$i],
						'satuan'       => $c_satalkes[$i],
						'harga'        => $_c_hrgalkes,
						'totalharga'   => $_c_totalkes,
						'tgltransaksi' => $tanggal,
						'gudang'	   	 => $gudang_bhp,
						'dibebankan'   => $c_bbn[$i],
					];
					if($c_kdalkes[$i]!=""){
						// Lose Stock
						$check_stock	= $this->db->query("SELECT * FROM tbl_barangstock WHERE kodebarang = '". $c_kdalkes[$i] ."' AND koders = '$cabang' AND gudang = '$gudang_bhp'");
						if($check_stock->num_rows() == 0){
							$this->db->query("INSERT INTO tbl_barangstock (koders,kodebarang,gudang,keluar,saldoakhir) VALUES ('$cabang', '". $c_kdalkes[$i] ."', '$gudang_bhp', '". $c_qtyalkes[$i] ."', '". $c_qtyalkes[$i] ."')");
						} else {
							$this->db->query("UPDATE tbl_barangstock SET keluar = keluar+". $c_qtyalkes[$i] .", saldoakhir = saldoakhir-". $c_qtyalkes[$i] ." WHERE kodebarang = '". $c_kdalkes[$i] ."' AND koders = '$cabang'  AND gudang = '$gudang_bhp'");
						}
						$insert_detil = $this->db->insert('tbl_alkestransaksi',$data_alkes);
					}
				}
			}

			// -- ERESEP -- //
			$er_kronishide		= $this->input->post("kronis_hide");
			$er_obat					= $this->input->post("obat_terapi");
			$er_satuan				= $this->input->post("satuan_ot");
			$er_jmlhobathari	= $this->input->post("jml_obat_hari");
			$er_qtymin				= $this->input->post("qty_minumum");
			$er_jmlhari				= $this->input->post("jml_hari");
			$er_jmlobat				= $this->input->post("jml_obat");
			$er_aturan				= $this->input->post("autran_pakai");
			$er_harga					= $this->input->post("harga_ot");
			$er_totalharga		= $this->input->post("totalharga_ot");
			$er_keterangan	 	= $this->input->post("keterangan");
			$check_eresep	= $this->db->query("SELECT * FROM tbl_eresep WHERE noreg = '$noreg_dok' AND koders = '$cabang'")->num_rows();
			if($check_eresep == 0){
				urut_transaksi("ERESEP", 20);
			}
			if(isset($er_obat)){
				$this->db->query("DELETE from tbl_eresep WHERE noreg = '$noreg_dok' and koders='$cabang'");
				$jumdataer			= count($er_obat);
				for($er=0;$er<=$jumdataer-1;$er++){
					$nama_obat		= data_master("tbl_barang", array("kodebarang" => $er_obat[$er]))->namabarang;
					$totalharga		= str_replace(",", "", $er_totalharga[$er]);
					$data_eresep_add	= [
						"koders"			=> $cabang,
						"noreg"				=> $noreg_dok,
						"orderno"			=> $orderno,
						"kodeobat"		=> $er_obat[$er],
						"namaobat"		=> $nama_obat,
						"satuan"			=> $er_satuan[$er],
						"qty"					=> $er_jmlobat[$er],
						"qty_perhari"	=> $er_jmlhobathari[$er],
						"jml_hari"		=> $er_jmlhari[$er],
						"qty_minum"		=> $er_qtymin[$er],
						"harga"				=> $er_harga[$er],
						"totalharga"	=> $totalharga,
						"aturanpakai"	=> $er_aturan[$er],
						"gudang"			=> $gudang,
						"resepno"			=> $orderno,
						"namauser"		=> $userid,
						"qtyawal"			=> $er_jmlobat[$er],
						"keterangan"	=> $er_keterangan[$er],
						"kronis"			=> $er_kronishide[$er],
					];
					$this->db->insert("tbl_eresep", $data_eresep_add);
				}
			}

			// -- RACIKAN -- //
			$ra_kodeobat1			= $this->input->post("r1nama_obat");
			$ra_kodeobat2			= $this->input->post("r2nama_obat");
			$ra_kodeobat3			= $this->input->post("r3nama_obat");
			$ra_satuan1				= $this->input->post("r1satuan");
			$ra_satuan2				= $this->input->post("r2satuan");
			$ra_satuan3				= $this->input->post("r3satuan");
			$ra_qtyracik1			= $this->input->post("r1qtyracik");
			$ra_qtyracik2			= $this->input->post("r2qtyracik");
			$ra_qtyracik3			= $this->input->post("r3qtyracik");
			$ra_qtyjual1			= $this->input->post("r1qtyjual");
			$ra_qtyjual2			= $this->input->post("r2qtyjual");
			$ra_qtyjual3			= $this->input->post("r3qtyjual");
			$ra_harga1				= $this->input->post("r1harga");
			$ra_harga2				= $this->input->post("r2harga");
			$ra_harga3				= $this->input->post("r3harga");
			$ra_totalharga1		= $this->input->post("r1totalharga");
			$ra_totalharga2		= $this->input->post("r2totalharga");
			$ra_totalharga3		= $this->input->post("r3totalharga");
			$ra_aturanpakai1	= $this->input->post("aturan_pakai1");
			$ra_aturanpakai2	= $this->input->post("aturan_pakai2");
			$ra_aturanpakai3	= $this->input->post("aturan_pakai3");

			$this->db->query("DELETE FROM tbl_eracik WHERE racikid = 'RACIK1' AND noreg = '$noreg_dok' AND koders = '$cabang'");
			$this->db->query("DELETE FROM tbl_eracik WHERE racikid = 'RACIK2' AND noreg = '$noreg_dok' AND koders = '$cabang'");
			$this->db->query("DELETE FROM tbl_eracik WHERE racikid = 'RACIK3' AND noreg = '$noreg_dok' AND koders = '$cabang'");

			if(isset($ra_kodeobat1)){
				$jumracik1	= count($ra_kodeobat1);
				for($ra1 = 0; $ra1 <= $jumracik1-1; $ra1++){
					$nama_obat_ra1	= data_master("tbl_barang", array("kodebarang" => $ra_kodeobat1[$ra1]))->namabarang;
					$harga_ra1			= str_replace(",", "", $ra_harga1[$ra1]);
					$totalharga_ra1 = str_replace(",", "", $ra_totalharga1[$ra1]);

					$data_racik1	= [
						"koders"			=> $cabang,
						"orderno"			=> $orderno,
						"racikid"			=> "RACIK1",
						"noreg"				=> $noreg_dok,
						"kodeobat"		=> $ra_kodeobat1[$ra1],
						"namaobat"		=> $nama_obat_ra1,
						"satuan"			=> $ra_satuan1[$ra1],
						"qty_racik"		=> $ra_qtyracik1[$ra1],
						"qty_jual"		=> $ra_qtyjual1[$ra1],
						"harga"				=> $harga_ra1,
						"totalharga"	=> $totalharga_ra1,
						"aturanpakai"	=> $ra_aturanpakai1,
						"proses"			=> 1,
					];
					$this->db->insert("tbl_eracik", $data_racik1);
				}
			}

			if(isset($ra_kodeobat2)){
				$jumracik2	= count($ra_kodeobat2);
				for($ra2 = 0; $ra2 <= $jumracik2-1; $ra2++){
					$nama_obat_ra2	= data_master("tbl_barang", array("kodebarang" => $ra_kodeobat2[$ra2]))->namabarang;
					$harga_ra2			= str_replace(",", "", $ra_harga2[$ra2]);
					$totalharga_ra2 = str_replace(",", "", $ra_totalharga2[$ra2]);

					$data_racik2	= [
						"koders"			=> $cabang,
						"orderno"			=> $orderno,
						"racikid"			=> "RACIK2",
						"noreg"				=> $noreg_dok,
						"kodeobat"		=> $ra_kodeobat2[$ra2],
						"namaobat"		=> $nama_obat_ra2,
						"satuan"			=> $ra_satuan2[$ra2],
						"qty_racik"		=> $ra_qtyracik2[$ra2],
						"qty_jual"		=> $ra_qtyjual2[$ra2],
						"harga"				=> $harga_ra2,
						"totalharga"	=> $totalharga_ra2,
						"aturanpakai"	=> $ra_aturanpakai2,
						"proses"			=> 1,
					];
					$query_insert_racik = $this->db->insert("tbl_eracik", $data_racik2);
				}
			}
			if(isset($ra_kodeobat3)){
				$jumracik3	= count($ra_kodeobat3);
				for($ra3 = 0; $ra3 <= $jumracik3-1; $ra3++){
					$nama_obat_ra3	= data_master("tbl_barang", array("kodebarang" => $ra_kodeobat3[$ra3]))->namabarang;
					$harga_ra3			= str_replace(",", "", $ra_harga3[$ra3]);
					$totalharga_ra3 = str_replace(",", "", $ra_totalharga3[$ra3]);
					$data_racik3	= [
						"koders"			=> $cabang,
						"orderno"			=> $orderno,
						"racikid"			=> "RACIK3",
						"noreg"				=> $noreg_dok,
						"kodeobat"		=> $ra_kodeobat3[$ra3],
						"namaobat"		=> $nama_obat_ra3,
						"satuan"			=> $ra_satuan3[$ra3],
						"qty_racik"		=> $ra_qtyracik3[$ra3],
						"qty_jual"		=> $ra_qtyjual3[$ra3],
						"harga"				=> $harga_ra3,
						"totalharga"	=> $totalharga_ra3,
						"aturanpakai"	=> $ra_aturanpakai3,
						"proses"			=> 1,
					];
					$query_insert_racik = $this->db->insert("tbl_eracik", $data_racik3);
				}
			}

			// -- ORDER PERIKSA -- //
			$opresepno					= $this->input->post("noeresephide");
			$opdok							= $this->input->post("doktereresep");
			$opgud							= $this->input->post("gudang");

			// racikan1
			$opracik1						= $this->input->post("nama_racik1");
			$opqtyjadi1					= $this->input->post("qty_jadi1");
			$opaturan1					= $this->input->post("aturan_pakai1");
			$opkemasanracik1		= $this->input->post("kemasan_racik1");
			$opjenispakai1			= $this->input->post("jenispakai1");
			$opcarapakai1				= $this->input->post("carapakai1");
			$opmanual1	  			= $this->input->post("r1manualracik");
			// end racikan 1
			
			// racikan 2
			$opracik2						= $this->input->post("nama_racik2");
			$opqtyjadi2					= $this->input->post("qty_jadi2");
			$opaturan2					= $this->input->post("aturan_pakai2");
			$opkemasanracik2		= $this->input->post("kemasan_racik2");
			$opjenispakai2			= $this->input->post("jenispakai2");
			$opcarapakai2				= $this->input->post("carapakai2");
			$opmanual2	  			= $this->input->post("r2manualracik");
			// end racikan 2

			// racikan 3
			$opracik3						= $this->input->post("nama_racik3");
			$opqtyjadi3					= $this->input->post("qty_jadi3");
			$opaturan3					= $this->input->post("aturan_pakai3");
			$opkemasanracik3		= $this->input->post("kemasan_racik3");
			$opjenispakai3			= $this->input->post("jenispakai3");
			$opcarapakai3				= $this->input->post("carapakai3");
			$opmanual3	  			= $this->input->post("r3manualracik");
			// end racikan 3


			// $opaturan1		= $this->input->post("aturan_pakai1");
			// $opmanual1	  = $this->input->post("r1manualracik");
			// $opracik2			= $this->input->post("nama_racik2");
			// $opqtyjadi2		= $this->input->post("qty_jadi2");
			// $opaturan2		= $this->input->post("aturan_pakai2");
			// $opmanual2	  = $this->input->post("r2manualracik");
			// $opracik3			= $this->input->post("nama_racik3");
			// $opqtyjadi3		= $this->input->post("qty_jadi3");
			// $opaturan3		= $this->input->post("aturan_pakai3");
			// $opmanual3	  = $this->input->post("r3manualracik");

			$data_op_add	= [
				"koders"							=> $cabang,
				"orderno"							=> $opresepno,
				"noreg"								=> $noreg_dok,
				"rekmed"							=> $rekmed_dok,
				"tglorder"						=> date("Y-m-d"),
				"proses"							=> "proses",
				"jamorder"						=> date("H:i:s"),
				"kodokter"						=> $opdok,
				"resep"								=> 1,
				"resepok"							=> 0,
				"username"						=> $userid,
				"gudang"							=> $opgud,
				// racik1
				"racik1"							=> $opracik1,
				"qtyjadi_racik1"			=> $opqtyjadi1,
				"aturan_pakai_racik1"	=> $opaturan1,
				"manual_racik1"				=> $opmanual1,
				"kemasan_racik1"			=> $opkemasanracik1,
				"jenispakai1"					=> $opjenispakai1,
				"carapakai1"					=> $opcarapakai1,
				// racik2
				"racik2"							=> $opracik2,
				"qtyjadi_racik2"			=> $opqtyjadi2,
				"aturan_pakai_racik2"	=> $opaturan2,
				"manual_racik2"				=> $opmanual2,
				"kemasan_racik2"			=> $opkemasanracik2,
				"jenispakai2"					=> $opjenispakai2,
				"carapakai2"					=> $opcarapakai2,
				// racik3
				"racik3"							=> $opracik3,
				"qtyjadi_racik3"			=> $opqtyjadi3,
				"aturan_pakai_racik3"	=> $opaturan3,
				"manual_racik3"				=> $opmanual3,
				"kemasan_racik3"			=> $opkemasanracik3,
				"jenispakai3"					=> $opjenispakai3,
				"carapakai3"					=> $opcarapakai3,
			];

			$data_op_edit	= [
				"tglorder"						=> date("Y-m-d"),
				"jamorder"						=> date("H:i:s"),
				"kodokter"						=> $opdok,
				"username"						=> $userid,
				"gudang"							=> $opgud,
				// racik1
				"racik1"							=> $opracik1,
				"qtyjadi_racik1"			=> $opqtyjadi1,
				"aturan_pakai_racik1"	=> $opaturan1,
				"manual_racik1"				=> $opmanual1,
				"kemasan_racik1"			=> $opkemasanracik1,
				"jenispakai1"					=> $opjenispakai1,
				"carapakai1"					=> $opcarapakai1,
				// racik2
				"racik2"							=> $opracik2,
				"qtyjadi_racik2"			=> $opqtyjadi2,
				"aturan_pakai_racik2"	=> $opaturan2,
				"manual_racik2"				=> $opmanual2,
				"kemasan_racik2"			=> $opkemasanracik2,
				"jenispakai2"					=> $opjenispakai2,
				"carapakai2"					=> $opcarapakai2,
				// racik3
				"racik3"							=> $opracik3,
				"qtyjadi_racik3"			=> $opqtyjadi3,
				"aturan_pakai_racik3"	=> $opaturan3,
				"manual_racik3"				=> $opmanual3,
				"kemasan_racik3"			=> $opkemasanracik3,
				"jenispakai3"					=> $opjenispakai3,
				"carapakai3"					=> $opcarapakai3,
			];

			if(!empty($er_obat)){
				$order_check	= $this->db->query("SELECT * FROM tbl_orderperiksa WHERE noreg = '$noreg_dok'");
				if($order_check->num_rows() == 0){
					$this->db->insert('tbl_orderperiksa', $data_op_add);
				} else {
					$this->db->update('tbl_orderperiksa', $data_op_edit, array("orderno" => $orderno,"noreg" => $noreg_dok, "koders" => $cabang));
				}
			}

			// -- HEADER -- //
			// Riwayat Pasien //
			$riwayat_penyakit	= $this->input->post("riwayat_penyakit");
			$riwayat_keluarga	= $this->input->post("riwayat_keluarga");
			$check_riwayat		= $this->db->query("SELECT * FROM tbl_pasien_catatan WHERE rekmed = '$rekmed_dok'")->num_rows();
			if($check_riwayat == 0){
				$data_insert_riwayat	= [
					"rekmed"						=> $rekmed_dok,
					"riwayat_penyakit"	=> $riwayat_penyakit,
					"riwayat_keluarga"	=> $riwayat_keluarga
				];
				$this->db->insert("tbl_pasien_catatan", $data_insert_riwayat);
			} else {
				$data_update_riwayat	= [
					"riwayat_penyakit"	=> $riwayat_penyakit,
					"riwayat_keluarga"	=> $riwayat_keluarga
				];
				$this->db->update("tbl_pasien_catatan", $data_update_riwayat, array("rekmed" => $rekmed_dok));
			}

			if ($param == 0) {
				/* tbl_rekammedisrs */
				$update = $this->db->update('tbl_rekammedisrs',$datarekmed,
				[
					'noreg'   => $noreg_dok,
					'rekmed'  => $rekmed_dok,
					'koders'  => $cabang
				]);

				/* tbl_hpoli */
				$update = $this->db->update('tbl_hpoli',$datahpoli,
				[
					'noreg'   => $noreg_dok,
					'rekmed'  => $rekmed_dok,
					'koders'  => $cabang
				]);
				history_log(0 ,'POLI_DOKTER_SOAP_RESEP' ,'EDIT' ,$noreg_dok ,'-');
				echo json_encode(array("status" => "0","nomor" => $noreg_dok));
			} else {
				$insert = $this->db->insert('tbl_rekammedisrs',$datarekmed);
				$insert = $this->db->insert('tbl_hpoli',$datahpoli);
				history_log(0 ,'POLI_DOKTER_SOAP_RESEP' ,'ADD' ,$noreg_dok ,'-');
				echo json_encode(array("status" =>"1","nomor" => $noreg_dok));
			}
		} else {
			header('location:'.base_url());
		}
	}

	public function get_last_number() {
    $unit = $this->session->userdata("unit");
    echo json_encode(["noresep" => temp_urut_transaksi("ERESEP", $unit, 20)]);
  }

	public function get_last_number_elab(){
		$unit = $this->session->userdata("unit");
		echo json_encode([
			"noelab" => urut_transaksi("ELAB", 19)
		]);
	}

	public function get_last_number_emed(){
		$unit = $this->session->userdata("unit");
		echo json_encode([
			"noemed" => urut_transaksi("ELEKTROMEDIS", 19)
		]);
	}

	public function data_order_elab($noreg){
		$koders					= $this->session->userdata("unit");
		$get_order_elab	= $this->db->query("SELECT * FROM tbl_orderperiksa WHERE koders = '$koders' AND noreg = '$noreg' AND orderno LIKE '%EL%'");
		$order_array		= [];
		if($get_order_elab->num_rows() == 0){
			echo json_encode(["status" => "null"]);
		} else {
			foreach($get_order_elab->result() as $key => $val){
				$order_array[]	= [
					"orderno"	=> $val->orderno,
					"proses"	=> $val->proses,
				];
			}
			echo json_encode([
				"status" 	=> "success",
				"result"	=> $order_array
			]); 
		}
	}

	public function data_order_erad($noreg){
		$koders					= $this->session->userdata("unit");
		$get_order_elab	= $this->db->query("SELECT * FROM tbl_orderperiksa WHERE koders = '$koders' AND noreg = '$noreg' AND orderno LIKE '%ERD%'");
		$order_array		= [];

		if($get_order_elab->num_rows() == 0){
			echo json_encode(["status" => "null"]);
		} else {
			foreach($get_order_elab->result() as $key => $val){
				$order_array[]	= [
					"orderno"	=> $val->orderno,
					"proses"	=> $val->proses,
				];
			}
			echo json_encode([
				"status" 	=> "success",
				"result"	=> $order_array
			]); 
		}
	}

	public function data_order_emed($noreg) {
    $koders    				= $this->session->userdata("unit");
    $get_order_elab  	= $this->db->query("SELECT * FROM tbl_orderperiksa WHERE koders = '$koders' AND noreg = '$noreg' AND orderno LIKE '%ERM%'");
    $order_array  		= [];
    if ($get_order_elab->num_rows() == 0) {
      echo json_encode(["status" => "null"]);
    } else {
      foreach ($get_order_elab->result() as $key => $val) {
        $order_array[]  = [
					"orderno"  	=> $val->orderno,
          "proses"  	=> $val->proses,
				];
      }
      echo json_encode([
				"status"   	=> "success",
        "result"  	=> $order_array
			]);
    }
  }

	public function getpoli_tin($kode){
		$unit 		= $this->session->userdata('unit');
		$kodepos	= $this->input->get("kodepos");
		$data 		= $this->db->query("SELECT * FROM daftar_tarif_nonbedah AS a WHERE a.koders = '$unit' AND a.kodetarif = '$kode' And a.kodepos = '$kodepos'")->row();
		echo json_encode($data);
	}

	public function getpoli_alkes(){
		$unit = $this->session->userdata('unit');
		$kode = $this->input->get('kode');
		$data	= $this->db->query("SELECT * FROM tbl_barang WHERE kodebarang = '$kode'")->row();
		echo json_encode($data);
	}

	public function get_icd_for_pcare($param){
		$query_icd	= $this->M_igd->get_icd_pcare($param);
		$get_icd	= $query_icd->row();
		if($query_icd){
			$status		= "success";
			$message	= "";
			$icd_str	= $get_icd->str;
		} else {
			$status		= "error";
			$message	= "ICD code not found";
			$icd_str	= "";
		}
		$data = [
			"status"	=> $status,
			"message"	=> $message,
			"string"	=> $icd_str
		];
		echo json_encode($data);
	}

	public function add_elab($noreg, $rekmed, $kodokter, $kodepos){
		$unit						= $this->session->userdata("unit");
		$orderno				= $this->input->post("elab_no");
		$tanggal				= $this->input->post("elab_tanggal");
		$jam						= $this->input->post("elab_jam");
		$status					= "";
		$elab_orderno		= $this->input->post("elabtin_orderno");
		$elab_kode			= $this->input->post("elabtin_kode");
		$elab_tindakan	= $this->input->post("elabtin_tindakan");
		$elab_tarifrs		= $this->input->post("elabtin_tarifrs");
		$elab_tarifdr		= $this->input->post("elabtin_tarifdr");
		$elab_harga			= $this->input->post("elabtin_harga");
		$elab_note			= $this->input->post("elabtin_catatan");
		foreach($orderno as $okey => $oval){
			$check_laborat	= $this->db->query("SELECT * FROM tbl_hlab WHERE orderno = '$oval'")->num_rows();
			if($check_laborat == 0){
				$check_order	= $this->db->query("SELECT * FROM tbl_orderperiksa WHERE koders = '$unit' AND orderno = '$oval' AND noreg = '$noreg'");
				$data_op			= [
					"koders"		=> $unit,
					"orderno"		=> $oval,
					"noreg"			=> $noreg,
					"rekmed"		=> $rekmed,
					"tglorder"	=> $tanggal[$okey],
					"proses"		=> "proses",
					"jamorder"	=> $jam[$okey],
					"kodokter"	=> $kodokter,
					"asal"			=> $kodepos,
					"lab"				=> "1",
					"labok"			=> "0",
				];
				// Insert Elab
				if(!empty($elab_kode)){
					$this->db->query("DELETE FROM tbl_elab WHERE notr = '$oval' AND noreg = '$noreg'");
					foreach($elab_kode as $ekkey => $ekval){
						if($elab_orderno[$ekkey] == $oval){
							$data_elab			= array(
								"notr"				=> $oval,
								"noreg"				=> $noreg,
								"kodetarif"		=> $elab_kode[$ekkey],
								"tindakan"		=> $elab_tindakan[$ekkey],
								"tarifrs"			=> $elab_tarifrs[$ekkey],
								"tarifdr"			=> $elab_tarifdr[$ekkey],
								"keterangan"	=> $elab_note[$ekkey]
							);
							$this->db->insert("tbl_elab", $data_elab);
						}
					}
				}
				if($check_order->num_rows() == 0){
					$query_order_elab	= $this->db->insert("tbl_orderperiksa", $data_op);
					history_log(0 ,'POLI_DOKTER_LAB' ,'ADD' ,$oval ,'-');
				} else {
					$query_order_elab	= $this->db->update("tbl_orderperiksa", $data_op, array("koders" => $unit,"orderno" => $oval, "noreg" => $noreg));
					history_log(0 ,'POLI_DOKTER_LAB' ,'EDIT' ,$oval ,'-');
				}
				if($query_order_elab){
					$status	= "success";
				} else {
					$status	= "failed";
				}
			}
		}
		$data_pasien	= $this->db->query("SELECT * FROM tbl_pasien WHERE rekmed = '$rekmed'")->row();
		echo json_encode([
			"status" 	=> $status,
			"nama"		=> $data_pasien->namapas,
			"noreg"		=> $noreg,
			"orderno"	=> $orderno,
		]);
	}

	public function getpoli_lab($kode){
		$unit 		= $this->session->userdata('unit');
		$kodepos	= $this->input->get("kodepos");
		$data = $this->db->query("SELECT * FROM daftar_tarif_nonbedah AS a WHERE a.koders = '$unit' AND a.kodetarif = '$kode' And a.kodepos = 'LABOR'")->row();
		echo json_encode($data);
	}

	public function add_emed($noreg, $rekmed, $kodokter, $kodepos){
		$unit			= $this->session->userdata("unit");
		$orderno	= $this->input->post("emed_no");
		$tanggal	= $this->input->post("emed_tanggal");
		$jam			= $this->input->post("emed_jam");
		// $unitpos	= $this->input->post("emed_unit");
		$unitpos	= "RADIO";
		$status		= "";
		$emed_orderno		= $this->input->post("emedtin_orderno");
		$emed_kode			= $this->input->post("emedtin_kode");
		$emed_tindakan	= $this->input->post("emedtin_tindakan");
		$emed_tarifrs		= $this->input->post("emedtin_tarifrs");
		$emed_tarifdr		= $this->input->post("emedtin_tarifdr");
		$emed_harga			= $this->input->post("emedtin_harga");
		$emed_note			= $this->input->post("emedtin_catatan");
		foreach($orderno as $okey => $oval){
			$check_order	= $this->db->query("SELECT * FROM tbl_orderperiksa WHERE koders = '$unit' AND orderno = '$oval' AND noreg = '$noreg'");
			$data_op			= [
				"koders"		=> $unit,
				"orderno"		=> $oval,
				"noreg"			=> $noreg,
				"rekmed"		=> $rekmed,
				"tglorder"	=> $tanggal[$okey],
				"proses"		=> "proses",
				"jamorder"	=> $jam[$okey],
				"kodokter"	=> $kodokter,
				"asal"			=> $kodepos,
				"medis"			=> "1",
				"medisok"		=> "0",
			];
			// Insert Emedis
			if(!empty($emed_kode)){
				$this->db->query("DELETE FROM tbl_emedis WHERE notr = '$oval' AND noreg = '$noreg'");
				foreach($emed_kode as $ekkey => $ekval){
					if($emed_orderno[$ekkey] == $oval){
						if($unitpos[$okey] == "-"){
							$nama_post	= "";
						} else {
							// $nama_post		= data_master("tbl_namapos", ["kodepos" => $unitpos[$okey]])->namapost;
							$nama_post		= data_master("tbl_namapos", ["kodepos" => $unitpos])->namapost;
						}
						$data_emed			= [
							"notr"				=> $oval,
							"noreg"				=> $noreg,
							"kodetarif"		=> $emed_kode[$ekkey],
							"tindakan"		=> $emed_tindakan[$ekkey],
							"tarifrs"			=> $emed_tarifrs[$ekkey],
							"tarifdr"			=> $emed_tarifdr[$ekkey],
							// "kodepos"			=> $unitpos[$okey],
							"kodepos"			=> "RADIO",
							"namapost"		=> $nama_post,
							"keterangan"	=> $emed_note[$ekkey]
						];
						$this->db->insert("tbl_emedis", $data_emed);
					}
				}
			}
			if($check_order->num_rows() == 0){
				$query_order_emed	= $this->db->insert("tbl_orderperiksa", $data_op);
			} else {
				$query_order_emed	= $this->db->update("tbl_orderperiksa", $data_op, ["koders" => $unit,"orderno" => $oval, "noreg" => $noreg]);
			}
			if($query_order_emed){
				$status	= "success";
			} else {
				$status	= "failed";
			}
		}
		$data_pasien	= $this->db->query("SELECT * FROM tbl_pasien WHERE rekmed = '$rekmed'")->row();
		echo json_encode([
			"status" 	=> $status,
			"nama"		=> $data_pasien->namapas,
			"noreg"		=> $noreg,
			"orderno"	=> $orderno,
		]);
	}

	public function getpoli_med($kode){
		$unit 		= $this->session->userdata('unit');
		// $kodepos	= $this->input->get("kodepos");
		$data = $this->db->query("SELECT * FROM daftar_tarif_nonbedah AS a WHERE a.koders = '$unit' AND a.kodetarif = '$kode' And a.kodepos = 'RADIO'")->row();
		echo json_encode($data);
	}

	public function getpoli_obatterapi(){
		$unit = $this->session->userdata('unit');
		$kode = $this->input->get('kode');
		$data	= $this->db->query("SELECT * FROM tbl_barang WHERE kodebarang = '$kode'")->row();
		echo json_encode($data);
	}

	public function delete_emed($noreg, $orderno){
		$unit	= $this->session->userdata("unit");
		$check_order	= $this->db->query("SELECT * FROM tbl_orderperiksa WHERE koders = '$unit' AND orderno = '$orderno' AND noreg = '$noreg'");
		if($check_order->num_rows() == 0){
			echo json_encode(["status" => "unregistered"]);
		} else {
			$query_delete	= $this->db->query("DELETE FROM tbl_orderperiksa WHERE koders = '$unit' AND orderno = '$orderno' AND noreg = '$noreg'");
			if($query_delete){
				$this->db->query("DELETE FROM tbl_emedis WHERE notr = '$orderno' AND noreg = '$noreg'");
				echo json_encode(["status" => "success", "orderno" => $orderno]);
			} else {
				echo json_encode(["status" => "failed", "orderno" => $orderno]);
			}
		}
	}

	public function delete_elab($noreg, $orderno){
		$unit	= $this->session->userdata("unit");
		$check_order	= $this->db->query("SELECT * FROM tbl_orderperiksa WHERE koders = '$unit' AND orderno = '$orderno' AND noreg = '$noreg'");
		if($check_order->num_rows() == 0){
			echo json_encode(["status" => "unregistered"]);
		} else {
			$query_delete	= $this->db->query("DELETE FROM tbl_orderperiksa WHERE koders = '$unit' AND orderno = '$orderno' AND noreg = '$noreg'");
			if($query_delete){
				$this->db->query("DELETE FROM tbl_elab WHERE notr = '$orderno' AND noreg = '$noreg'");
				
				history_log(0 ,'POLI_DOKTER_LAB' ,'DELETE' ,$orderno ,'-');
				echo json_encode(["status" => "success", "orderno" => $orderno]);
			} else {
				echo json_encode(["status" => "failed", "orderno" => $orderno]);
			}
		}
	}

	public function buat_triase() {
		$cek 		= $this->session->userdata('username');
		$unit		= $this->session->userdata('unit');
		if(!empty($cek)){
			$data	=[
				$id = $this->uri->segment(3),
				$bulan  						= $this->M_global->_periodebulan(),
				$tahun 		    			= $this->M_global->_periodetahun(),
				$nbulan 						= $this->M_global->_namabulan($bulan),
				$periode						= 'Periode '.ucwords(strtolower($nbulan)).' '.$this->M_global->_periodetahun(),
				'periode'						=> $periode,
				'menu'							=> 'e-HMS',
				'title'							=> 'IGD',
				'bed'								=> $this->db->query("SELECT * FROM tbl_setinghms WHERE lset = 'BIGD'")->result(),
				'dokter'						=> $this->db->query("SELECT d.* FROM tbl_dokter d JOIN tbl_doktercabang dc ON d.kodokter=dc.kodokter WHERE dc.koders = '$unit' AND d.status = 'ON' AND d.jenispegawai = '1' GROUP BY dc.kodokter")->result(),
				'perawat'						=> $this->db->query("SELECT d.* FROM tbl_dokter d JOIN tbl_doktercabang dc ON d.kodokter=dc.kodokter WHERE dc.koders = '$unit' AND d.status = 'ON' AND d.jenispegawai = '2' GROUP BY dc.kodokter")->result(),
			];
			$this->load->view('igd/v_triase', $data);
		} else {
			header('location:'.base_url());
		}
	}

	public function datapasien($rekmed) {
		$data = $this->db->query("SELECT * FROM tbl_pasien WHERE rekmed = '$rekmed'")->row();
		echo json_encode($data);
	}
}
?>