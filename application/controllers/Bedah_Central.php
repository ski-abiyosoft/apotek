<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bedah_Central extends CI_Controller{

    public function __construct(){
		parent::__construct();
		$this->session->set_userdata('menuapp', '2000');
		$this->session->set_userdata('submenuapp', '2651');
		$this->load->model(array("M_bedah","M_pasien_global","M_cetak"));
		$this->load->helper(array("app_global","rsreport"));
	}

    public function index(){
		$cek 	= $this->session->userdata('level');
		$unit	= $this->session->userdata('unit');

		$query_dok		= $this->db->query("SELECT * FROM tbl_dokter ORDER BY nadokter ASC");
		$query_dok2		= $this->db->query("SELECT * FROM tbl_drpengirim ORDER BY nadokter ASC");
		$query_bius		= $this->db->query("SELECT * FROM tbl_setinghms WHERE lset  = 'BIUS'");
		// $query_rok	= $this->db->query("SELECT * FROM tbl_ruangpoli WHERE jenis = 'OK'");
		$query_rok		= $this->db->query("SELECT * FROM tbl_ruangpoli");
		// $query_tarif	= $this->db->query("SELECT * FROM tbl_tarifrs WHERE kodepos = 'BEDAH'");
		$query_tarif	= $this->db->query("SELECT CONCAT(tindakan,' - [ Rp ', REPLACE(FORMAT(tarifa, 0), ',', '.') ,' ]') AS ket, daftar_tarif_bedah.* FROM daftar_tarif_bedah WHERE jenis_bedah = 'BEDAH MATA' AND bedahid = '00' GROUP BY kodetarif");
		$list_reg		= $this->M_pasien_global->list();
		$jadwaloperasi	= $this->db->query("SELECT COUNT(id) AS total, keluar FROM tbl_hbedahjadwal WHERE keluar = 0")->row();
		$selesaioperasi	= $this->db->query("SELECT COUNT(id) AS total, keluar FROM tbl_hbedahjadwal WHERE NOT keluar = 0")->row();
        
        $data=[
			'title'				=> 'Bedah Central',
			'menu'				=> 'Bedah Central',
			'dokter'		    => $query_dok->result(),
			'dokter2'		    => $query_dok2->result(),
			'bius'				=> $query_bius->result(),
			'ruangok'			=> $query_rok->result(),
			'tarifrs'			=> $query_tarif->result(),
			'listreg'			=> $list_reg,
			'getData'			=> $this->M_bedah->get_data(),
			'cekData'			=> $this->M_bedah->get_data_cek(),
			'totaljop'			=> $jadwaloperasi->total,
			'totalsop'			=> $selesaioperasi->total
        ];
		// var_dump($data['cekData']);die;
        $this->load->view('Bedah_Central/v_bedahCentral', $data);
    }

	// Collect Data

	public function get_last_number(){
		$unit = $this->session->userdata("unit");
		echo json_encode(array(
			"lastno" => temp_urut_transaksi("JADWAL_BEDAH", $unit, 19)
		));
	}

	public function get_recent_number(){
		$unit = $this->session->userdata("unit");
		echo json_encode(array(
			"recnno" => recent_urut_transaksi("JADWAL_BEDAH", $unit, 19)
		));
	}

	public function filter_data(){
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');

        $tgloperasi         = $this->input->get('tgloperasi');
		$tglakhir			= $this->input->get('tglakhir');
		$getData			= $this->M_bedah->get_data_filter($tgloperasi, $tglakhir);

		$cek 	= $this->session->userdata('level');
		$unit	= $this->session->userdata('unit');

		$query_dok		= $this->db->query("SELECT * FROM tbl_dokter ORDER BY nadokter ASC");
		$query_dok2		= $this->db->query("SELECT * FROM tbl_drpengirim ORDER BY nadokter ASC");
		$query_bius		= $this->db->query("SELECT * FROM tbl_setinghms WHERE lset = 'BIUS'");
		// $query_rok	= $this->db->query("SELECT * FROM tbl_ruangpoli WHERE jenis = 'OK'");
		$query_rok		= $this->db->query("SELECT * FROM tbl_ruangpoli");
		$query_tarif	= $this->db->query("SELECT * FROM daftar_tarif_bedah WHERE igroup = 'BD07'");
		$list_reg		= $this->M_pasien_global->list();
		$jadwaloperasi	= $this->db->query("SELECT COUNT(id) AS total, keluar FROM tbl_hbedahjadwal WHERE keluar = 0")->row();
		$selesaioperasi	= $this->db->query("SELECT COUNT(id) AS total, keluar FROM tbl_hbedahjadwal WHERE NOT keluar = 0")->row();

		$data=[
			'title'				=> 'Bedah Central',
			'menu'				=> 'Bedah Central',
			'dokter'		    => $query_dok->result(),
			'dokter2'		    => $query_dok2->result(),
			'bius'				=> $query_bius->result(),
			'ruangok'			=> $query_rok->result(),
			'tarifrs'			=> $query_tarif->result(),
			'listreg'			=> $list_reg,
			'cekData'			=> $this->M_bedah->get_data_cek(),
            'getData'           => $getData,
			'totaljop'			=> $jadwaloperasi->total,
			'totalsop'			=> $selesaioperasi->total
		];
		
		$tglakhir	= $this->input->get("tglakhir");

		if(!$tglakhir){
			redirect("/Bedah_Central");
		} else {
			$this->load->view('Bedah_Central/v_bedahCentral', $data);
		}
	}

	public function modaltrigger($param){
		$logedin	= $this->session->userdata("is_logged_in");

		if($logedin){
			$hjb = $this->db->query("SELECT * FROM tbl_hbedahjadwal WHERE nojadwal = '$param'")->row();

			$check_pu		= $this->db->query("SELECT * FROM tbl_persetujuanumum WHERE nojadwal = '$param'")->num_rows();
			$check_ptd		= $this->db->query("SELECT * FROM tbl_persetujuantindakdokter WHERE nojadwal = '$param'")->num_rows();
			$check_cpo		= $this->db->query("SELECT * FROM tbl_checklistpppo WHERE nojadwal = '$param'")->num_rows();
			$check_psc		= $this->db->query("SELECT * FROM tbl_prosedursafetycheck WHERE nojadwal = '$param'")->num_rows();
			$check_rpo		= $this->db->query("SELECT * FROM tbl_resumepppo WHERE nojadwal = '$param'")->num_rows();
			$check_ckb	 	= $this->db->query("SELECT * FROM tbl_catatankeperawatanbedah WHERE nojadwal = '$param'")->num_rows();
			$check_cs	 	= $this->db->query("SELECT * FROM tbl_cataractsurgery WHERE nojadwal = '$param'")->num_rows();
			$check_lo	 	= $this->db->query("SELECT * FROM tbl_laporanoperasi WHERE nojadwal = '$param'")->num_rows();

			$cpu			= ($check_pu != 0)? "&nbsp; <i class='fa fa-check-circle'></i>" : "";
			$cptd			= ($check_ptd != 0)? "&nbsp; <i class='fa fa-check-circle'></i>" : "";
			$ccpo			= ($check_cpo != 0)? "&nbsp; <i class='fa fa-check-circle'></i>" : "";
			$cpsc			= ($check_psc != 0)? "&nbsp; <i class='fa fa-check-circle'></i>" : "";
			$crpo			= ($check_rpo != 0)? "&nbsp; <i class='fa fa-check-circle'></i>" : "";
			$cckb			= ($check_ckb != 0)? "&nbsp; <i class='fa fa-check-circle'></i>" : "";
			$ccs			= ($check_cs != 0)? "&nbsp; <i class='fa fa-check-circle'></i>" : "";
			$clo			= ($check_lo != 0)? "&nbsp; <i class='fa fa-check-circle'></i>" : "";

			// echo json_encode($hjb);

			echo "<div class='modal-header' id='taskhead'>
				<table style='overflow-x:auto;border-collapse: collapse;border-spacing:0;width:100%;border:1px solid #fff'>
					<tr><td style='padding:5px'>No Jadwal</td><td>:</td><td> <b>". $hjb->nojadwal ."</b></td></tr>
					<tr><td style='padding:5px'>Tanggal Operasi</td><td>:</td><td> <b>". str_replace(" 00:00:00", "", $hjb->tgloperasi) ."</b></td></tr>
					<tr><td style='padding:5px'>Nama Pasien</td><td>:</td><td> <b>". $hjb->namapas ."</b></td></tr>
					<tr><td style='padding:5px'>No Rekmed</td><td>:</td><td> <b>". $hjb->rekmed ."</b></td></tr>
					<tr><td style='padding:5px'>No Regist</td><td>:</td><td> <b>". $hjb->noreg ."</b></td></tr>
				</table>
			</div>
			<div class='modal-body' id='taskslist'>
			<a href='/Bedah_Central/persetujuan_umum/". $hjb->nojadwal ."' target='_blank'><button type='button' class='btn green btn-sm'><b>Persetujuan Umum". $cpu ."</b></button></a>
			<a href='/Bedah_Central/persetujuan_tindakan_dokter/". $hjb->nojadwal ."' target='_blank'><button type='button' class='btn green btn-sm'><b>Persetujuan Tindakan Dokter". $cptd ."</b></button></a>
			<a href='/Bedah_Central/checklist_persiapan_pasien_preoperasi/". $hjb->nojadwal ."' target='_blank'><button type='button' class='btn green btn-sm'><b>Checklist Persiapan Pra Operasi". $ccpo ."</b></button></a>
			<a href='/Bedah_Central/prosedur_safety_checklist/". $hjb->nojadwal ."' target='_blank'><button type='button' class='btn green btn-sm'><b>Prosedur Safety Checklist". $cpsc ."</b></button></a>
			<a href='/Bedah_Central/laporan_anestasi/". $hjb->nojadwal ."' target='_blank'><button type='button' class='btn green btn-sm'><b>Laporan Anestasi</b></button></a>
			<a href='/Bedah_Central/laporan_operasi/". $hjb->nojadwal ."' target='_blank'><button type='button' class='btn green btn-sm'><b>Laporan Operasi". $clo ."</b></button></a>
			<a href='/Bedah_Central/resume_pasien_pulang_post_operasi/". $hjb->nojadwal ."' target='_blank'><button type='button' class='btn green btn-sm'><b>Resume Pasien Pulang Post Operasi". $crpo ."</b></button></a>
			<a href='/Bedah_Central/catatan_keperawatan_bedah/". $hjb->nojadwal ."' target='_blank'><button type='button' class='btn green btn-sm'><b>Catatan Keperawatan Bedah". $cckb ."</b></button></a>
			<a href='/Bedah_Central/cataract_surgery/". $hjb->nojadwal ."' target='_blank'><button type='button' class='btn green btn-sm'><b>Cataract Surgery". $ccs ."</b></button></a>
			<br />
			<b><i class='fa fa-check-circle' style='color:#fff;background:#35aa47;padding:5px 10px;font-size:12px;line-height:1.5;border-radius:3px'></i> : Sudah dilakukan input</b>
			</div>";
		} else {
			echo "You Have No Authority";
		}
	}

	public function billing($param){
		$cek		= $this->session->userdata("level");
		$unit		= $this->session->userdata("unit");
		$username	= $this->session->userdata("username");

		$query_jadwal	= $this->db->query("SELECT a.*, b.* FROM tbl_hbedahjadwal AS a 
		LEFT JOIN pasien_daftar AS b ON b.noreg = a.noreg 
		WHERE a.nojadwal = '$param'");
		$data_jadwal	= $query_jadwal->row();

		$query_header	= $this->db->query("SELECT * FROM tbl_hbedah WHERE nooperasi = '$data_jadwal->nojadwal'");
		$query_tindakan	= $this->db->query("SELECT * FROM tbl_dbedahdetail WHERE nooperasi = '$data_jadwal->nojadwal'");
		$query_bhp		= $this->db->query("SELECT * FROM tbl_bedahobat WHERE nooperasi = '$data_jadwal->nojadwal'");
		$query_deftindak= $query_tindakan->row();

		$query_ruangop	= $this->db->query("SELECT * FROM tbl_ruangpoli");
		$query_dokter	= $this->db->query("SELECT * FROM tbl_dokter ORDER BY nadokter ASC");
		$query_tarif	= $this->db->query("SELECT CONCAT(tindakan,' - [ Rp ', REPLACE(FORMAT(tarifa, 0), ',', '.') ,' ]') AS ket, daftar_tarif_bedah.* FROM daftar_tarif_bedah WHERE jenis_bedah = 'BEDAH MATA' AND bedahid = '00' GROUP BY kodetarif");

		if(!empty($cek)){
			$data	= [
				"menu" 		=> "Bedah Central",
				"submenu" 	=> "Billing",
				"link"		=> "Bedah_Central",
				"unit"		=> $unit,
				"data"		=> $query_jadwal->row(),
				"ruangop"	=> $query_ruangop->result(),
				"listdokter"=> $query_dokter->result(),
				"tarif"		=> $query_tarif->result(),
				"status"	=> ($query_header->num_rows() > 0)? "done" : "undone",
				"header"	=> $query_header->row(),
				"tindakan"	=> $query_tindakan->result(),
				"bhp"		=> $query_bhp->result(),
				"deftindak"	=> ($query_deftindak)? $query_deftindak->kodetarif : 0,
				"jumdata"	=> $query_bhp->num_rows(),
			];

			$this->load->view("Bedah_Central/v_billing", $data);
		} else {
			redirect("/Bedah_Central");
		}
	}

	public function getinfobarang(){
		$kode = $this->input->get('kode');		
		$data = $this->db->get_where('tbl_barang', ['kodebarang' => $kode])->row();
		echo json_encode($data);
	}

	public function getinfotindakan($param){

		$basetarif		= $this->db->query("SELECT * FROM daftar_tarif_bedah WHERE kodetarif = '$param' AND bedahid = '00'")->row();
		$tarifdrop		= $this->db->query("SELECT * FROM daftar_tarif_bedah WHERE kodetarif = '$param' AND bedahid = '01'")->row();
		$tarifasdrop	= $this->db->query("SELECT * FROM daftar_tarif_bedah WHERE kodetarif = '$param' AND bedahid = '05'")->row();
		$tarifdran		= $this->db->query("SELECT * FROM daftar_tarif_bedah WHERE kodetarif = '$param' AND bedahid = '02'")->row();
		$tarifasdran	= $this->db->query("SELECT * FROM daftar_tarif_bedah WHERE kodetarif = '$param' AND bedahid = '03'")->row();
		$tarifkamar		= $this->db->query("SELECT * FROM daftar_tarif_bedah WHERE kodetarif = '$param' AND bedahid = '04'")->row();

		$result		= array(
			"tarif"		=> number_format($basetarif->tarifa, 0, ',', '.'),
			"drop"	  	=> number_format($tarifdrop->tarifa, 0, ',', '.'),
			"asdrop"	=> number_format($tarifasdrop->tarifa, 0, ',', '.'),
			"dran"		=> number_format($tarifdran->tarifa, 0, ',', '.'),
			"asdran"	=> number_format($tarifasdran->tarifa, 0, ',', '.'),
			"jasakamar"	=> number_format($tarifkamar->tarifa, 0, ',', '.'),
			"bedahtarif1"	=> $tarifdrop->kodetarif,
			"bedahtarif2"	=> $tarifasdrop->kodetarif,
			"bedahtarif3"	=> $tarifdran->kodetarif,
			"bedahtarif4"	=> $tarifasdran->kodetarif,
			"bedahtarif5"	=> $tarifkamar->kodetarif,
			"bedahkey1"	=> $tarifdrop->bedahidkey,
			"bedahkey2"	=> $tarifasdrop->bedahidkey,
			"bedahkey3"	=> $tarifdran->bedahidkey,
			"bedahkey4"	=> $tarifasdran->bedahidkey,
			"bedahkey5"	=> $tarifkamar->bedahidkey,
			"citocheck1"	=> ($tarifdrop->cito == 20.00)? true : false,
			"citocheck2"	=> ($tarifasdrop->cito == 20.00)? true : false,
			"citocheck3"	=> ($tarifdran->cito == 20.00)? true : false,
			"citocheck4"	=> ($tarifasdran->cito == 20.00)? true : false,
			"citocheck5"	=> ($tarifkamar->cito == 20.00)? true : false,
			"citorp1"	=> number_format($tarifdrop->citorp, 0 ,',', '.'),
			"citorp2"	=> number_format($tarifasdrop->citorp, 0 ,',', '.'),
			"citorp3"	=> number_format($tarifdran->citorp, 0 ,',', '.'),
			"citorp4"	=> number_format($tarifasdran->citorp, 0 ,',', '.'),
			"citorp5"	=> number_format($tarifkamar->citorp, 0 ,',', '.'),
		);

		echo json_encode($result);
	}

	public function checkstock(){
		$unit 	= $this->session->userdata("unit");
		$gudang	= $this->input->get("gudang");
		$kode	= $this->input->get("kode");

		$qq = $this->db->query("SELECT saldoakhir FROM tbl_barangstock WHERE kodebarang = '$kode' AND koders = '$unit' AND gudang = '$gudang'")->row();

		if($qq){
			if($qq->saldoakhir <> 0){
				echo json_encode(array("stock" => $qq->saldoakhir));
			} else {
				echo json_encode(array("status" => 0));
			}
		} else {
			echo json_encode(array("status" => 2));
		}
	}

	// Save

	public function save(){
		$unit		= $this->session->userdata("unit");
		$check		= $this->session->userdata("level");
		$username	= $this->session->userdata("username");
		$tanggal	= date("Y-m-d");

		if(!empty($check)){

			// Catatan
			// Dari, Kelas dan DRPENGEREM belum ada kolom untuk databasenya

			$datah	= array(
				'koders' 		=> $unit,
				'nojadwal'		=> $this->input->post("hideno_urut"),
				'tgloperasi'	=> $this->input->post("tgl_bedah"),
				'noreg'			=> $this->input->post("noReg"),
				'rekmed'		=> $this->input->post("rekMedis"),
				'namapas'		=> $this->input->post("namaPas"),
				'droperator'	=> $this->input->post("operator"),
				'asdroperator'	=> $this->input->post("asisten1"),
				'asdroperatar'	=> $this->input->post("asisten2"),
				'asdrinstrum'	=> $this->input->post("instrumen"),
				'asdrsirkule'	=> $this->input->post("sirkuler"),
				'dranestasi'	=> $this->input->post("anestasi"),
				'asdranestasi'	=> $this->input->post("asanestasi"),
				// 'asoperasi'		=> $this->input->post(""),
				'drpengirim'	=> $this->input->post("drpengirimint"),
				'drpengerem'	=> $this->input->post("drpengirimext"),
				'kodetarif'		=> $this->input->post("jenis_operasi"),
				'jam'			=> $this->input->post("jam"),
				'keluar'		=> 0,
				'uraian'		=> $this->input->post("uraian"),
				'jenisok'		=> $this->input->post("jenis"),
				'jenisbius'		=> $this->input->post("jenisbius"),
				'ruangok'		=> $this->input->post("ruangok"),
				'userbuat'		=> $this->input->post("userbuat"),
				'tglbuat'		=> $this->input->post("tglbuat"),
				'diagnosabedah'	=> $this->input->post("diagsona")
			);

			$query_insert = $this->db->insert("tbl_hbedahjadwal", $datah);

			// $query_insert 	= $this->db->query("INSERT INTO tbl_hbedahjadwal (koders,nojadwal,tgloperasi,noreg,rekmed,namapas,droperator,asdroperator,asdroperatar,asdrinstrum,asdrsirkule,dranestasi,asdranestasi,drpengirim,drpengerem,kodetarif,jam,keluar,uraian,jenisok,jenisbius,ruangok,userbuat,tglbuat,diagnosabedah) 
			// VALUES ('$unit','$nojadwal','$tgloperasi','$noreg','$rekmed','$namapas','$droperator','$asdroperator','$asdroperatar','$asdrinstrum','$asdrsirkule','$dranestasi','$asdranestasi','$drpengirim','$drpengerem','$kodetarif','$jam','$keluar','$uraian','$jenisok','$jenisbius','$ruangok','$userbuat','$tglbuat','$diagnosabedah')");

			if($query_insert){
				urut_transaksi("JADWAL_BEDAH", 19);
				echo json_encode(array("status" => 1));
			} else {
				echo json_encode(array("status" => 0));
			}
		} else {
			echo redirect("/");
		}
	}

	public function save_persetujuan($param = ""){
		$unit		= $this->session->userdata("unit");
		$check		= $this->session->userdata("level");
		$username	= $this->session->userdata("username");
		$tanggal	= date("Y-m-d H:i:s");
		$nojadwal	= $this->input->post("nojadwal");

		if(!empty($check)){
			$data = array(
				"koders"		=> $unit,
				"nojadwal"		=> $this->input->post("nojadwal"),
				"noreg"			=> $this->input->post("noreg"),
				"rekmed"		=> $this->input->post("rekmed"),
				"nama_p"		=> $this->input->post("fpudnama"),
				"tgllahir_p"	=> $this->input->post("fpudttl"),
				"alamat_p"		=> $this->input->post("fpudalamat"),
				"phone_p"		=> $this->input->post("fpudtlp"),
				"nama_w"		=> $this->input->post("fpuwnama"),
				"jkel_w"		=> $this->input->post("fpuwjk"),
				"hubungan_w"	=> $this->input->post("fpuwhub"),
				"alamat_w"		=> $this->input->post("fpuwalamat"),
				"userbuat"		=> $username,
				"tglbuat"		=> $tanggal
			);

			$dataedit = array(
				"nama_p"		=> $this->input->post("fpudnama"),
				"tgllahir_p"	=> $this->input->post("fpudttl"),
				"alamat_p"		=> $this->input->post("fpudalamat"),
				"phone_p"		=> $this->input->post("fpudtlp"),
				"nama_w"		=> $this->input->post("fpuwnama"),
				"jkel_w"		=> $this->input->post("fpuwjk"),
				"hubungan_w"	=> $this->input->post("fpuwhub"),
				"alamat_w"		=> $this->input->post("fpuwalamat"),
				"useredit"		=> $username,
				"tgledit"		=> $tanggal
			);

			if($param == 1){
				$query_insert = $this->db->update('tbl_persetujuanumum',$dataedit, array('nojadwal' => $nojadwal));	
			} else {
				$query_insert = $this->db->insert("tbl_persetujuanumum", $data);
			}
			
			if($query_insert){
				echo json_encode(array("status" => 1));
			} else {
				echo json_encode(array("status" => 0));
			}
		} else {
			redirect("/");
		}
	}

	public function save_persetujuan_tindakan_dokter($param = ""){
		$unit		= $this->session->userdata("unit");
		$check		= $this->session->userdata("level");
		$username	= $this->session->userdata("username");
		$tanggal	= date("Y-m-d H:i:s");
		$nojadwal	= $this->input->post("nojadwal");

		if(!empty($check)){
			$knowing	= $this->input->post("knowing");

			if($knowing == "1"){
				$pasname			= $this->input->post("pasname");
				$pasbirth			= $this->input->post("pasbirth");
				$pasgender			= $this->input->post("pasgender");
				$pasaddress			= $this->input->post("pasaddress");
			} else {
				$pasname			= $this->input->post("pasname2");
				$pasbirth			= $this->input->post("pasbirth2");
				$pasgender			= $this->input->post("pasgender2");
				$pasaddress			= $this->input->post("pasaddress2");
			}
			
			$data	= array(
				"koders"			=> $unit,
				"nojadwal"			=> $nojadwal,
				"noreg"				=> $this->input->post("noreg"),
				"rekmed"			=> $this->input->post("rekmed"),
				"edudate"		    => $this->input->post("edudate"),
				"edutime"		    => $this->input->post("edutime"),
				"edudoc"			=> $this->input->post("edudoc"),
				"eduinformant"		=> $this->input->post("eduinformant"),
				"eduinformed"		=> $this->input->post("eduinformed"),
				"eduwddd"			=> $this->input->post("eduwddd"),
				"edudiagnose"		=> $this->input->post("edudiagnose"),
				"edumedtreat"		=> $this->input->post("edumedtreat"),
				"eduindication"		=> $this->input->post("eduindication"),
				"edutypeanes"		=> $this->input->post("edutypeanes"),
				"edupurmedtreat"	=> $this->input->post("edupurmedtreat"),
				"eduriskcomp"		=> $this->input->post("eduriskcomp"),
				"eduprognosis"		=> $this->input->post("eduprognosis"),
				"edualtrisk"		=> $this->input->post("edualtrisk"),
				"edutechnique"		=> $this->input->post("edutechnique"),
				"eduestcost"		=> str_replace(",", "", $this->input->post("eduestcost")),
				"edupaymethod"		=> $this->input->post("edupaymethod"),
				"check1"			=> $this->input->post("check1"),
				"check2"			=> $this->input->post("check2"),
				"check3"			=> $this->input->post("check3"),
				"check4"			=> $this->input->post("check4"),
				"check5"			=> $this->input->post("check5"),
				"check6"			=> $this->input->post("check6"),
				"check7"			=> $this->input->post("check7"),
				"check8"			=> $this->input->post("check8"),
				"check9"			=> $this->input->post("check9"),
				"check10"			=> $this->input->post("check10"),
				"check11"			=> $this->input->post("check11"),
				"check12"			=> $this->input->post("check12"),
				"famname"			=> $this->input->post("famname"),
				"famgender"			=> $this->input->post("famgender"),
				"famrelate"			=> $this->input->post("famrelate"),
				"famphone"			=> $this->input->post("famphone"),
				"famaddress"		=> $this->input->post("famaddress"),
				"knowing"			=> $this->input->post("knowing"),
				"pasname"			=> $pasname,
				"pasbirth"			=> $pasbirth,
				"pasgender"			=> $pasgender,
				"pasaddress"		=> $pasaddress,
				"pas2name"			=> $this->input->post("pas2name"),
				"pas2jkel"			=> $this->input->post("pas2jkel"),
				"pas2birth"			=> $this->input->post("pas2birth"),
				"pas2address"		=> $this->input->post("pas2address"),
				"userbuat"			=> $username,
				"tglbuat"			=> $tanggal
			);

			$dataedit	= array(
				"edudate"		    => $this->input->post("edudate"),
				"edutime"		    => $this->input->post("edutime"),
				"edudoc"			=> $this->input->post("edudoc"),
				"eduinformant"		=> $this->input->post("eduinformant"),
				"eduinformed"		=> $this->input->post("eduinformed"),
				"eduwddd"			=> $this->input->post("eduwddd"),
				"edudiagnose"		=> $this->input->post("edudiagnose"),
				"edumedtreat"		=> $this->input->post("edumedtreat"),
				"eduindication"		=> $this->input->post("eduindication"),
				"edutypeanes"		=> $this->input->post("edutypeanes"),
				"edupurmedtreat"	=> $this->input->post("edupurmedtreat"),
				"eduriskcomp"		=> $this->input->post("eduriskcomp"),
				"eduprognosis"		=> $this->input->post("eduprognosis"),
				"edualtrisk"		=> $this->input->post("edualtrisk"),
				"edutechnique"		=> $this->input->post("edutechnique"),
				"eduestcost"		=> str_replace(",", "", $this->input->post("eduestcost")),
				"edupaymethod"		=> $this->input->post("edupaymethod"),
				"check1"			=> $this->input->post("check1"),
				"check2"			=> $this->input->post("check2"),
				"check3"			=> $this->input->post("check3"),
				"check4"			=> $this->input->post("check4"),
				"check5"			=> $this->input->post("check5"),
				"check6"			=> $this->input->post("check6"),
				"check7"			=> $this->input->post("check7"),
				"check8"			=> $this->input->post("check8"),
				"check9"			=> $this->input->post("check9"),
				"check10"			=> $this->input->post("check10"),
				"check11"			=> $this->input->post("check11"),
				"check12"			=> $this->input->post("check12"),
				"famname"			=> $this->input->post("famname"),
				"famgender"			=> $this->input->post("famgender"),
				"famrelate"			=> $this->input->post("famrelate"),
				"famphone"			=> $this->input->post("famphone"),
				"famaddress"		=> $this->input->post("famaddress"),
				"knowing"			=> $this->input->post("knowing"),
				"pasname"			=> $pasname,
				"pasbirth"			=> $pasbirth,
				"pasgender"			=> $pasgender,
				"pasaddress"		=> $pasaddress,
				"pas2name"			=> $this->input->post("pas2name"),
				"pas2jkel"			=> $this->input->post("pas2jkel"),
				"pas2birth"			=> $this->input->post("pas2birth"),
				"pas2address"		=> $this->input->post("pas2address"),
				"useredit"			=> $username,
				"tgledit"			=> $tanggal
			);

			if($param == 1){
				$query_insert = $this->db->update('tbl_persetujuantindakdokter',$dataedit, array('nojadwal' => $nojadwal));	
			} else {
				$query_insert = $this->db->insert("tbl_persetujuantindakdokter", $data);
			}
			
			if($query_insert){
				echo json_encode(array("status" => 1));
			} else {
				echo json_encode(array("status" => 0));
			}
		} else {
			redirect("/");
		}
	}

	public function save_ceklist_persiapan_pasien_preoperasi($param = ""){
		$unit		= $this->session->userdata("unit");
		$check		= $this->session->userdata("level");
		$username	= $this->session->userdata("username");
		$tanggal	= date("Y-m-d H:i:s");
		$nojadwal	= $this->input->post("nojadwal");

		if(!empty($check)){

			$data	= array(
				"koders"			=> $unit,
				"nojadwal"			=> $nojadwal,
				"noreg"				=> $this->input->post("noreg"),
				"rekmed"			=> $this->input->post("rekmed"),
				"droperator"		=> $this->input->post("droperator"),
				"perawat"			=> $this->input->post("perawat"),
				"kodetarif"			=> $this->input->post("kodetarif"),
				"jampuasa"			=> $this->input->post("jampuasa"),
				"ket1"				=> $this->input->post("ket1"),
				"ket2"				=> $this->input->post("ket2"),
				"check1"			=> $this->input->post("check1"),
				"check2"			=> $this->input->post("check2"),
				"check3"			=> $this->input->post("check3"),
				"check4"			=> $this->input->post("check4"),
				"check5"			=> $this->input->post("check5"),
				"check6"			=> $this->input->post("check6"),
				"check7"			=> $this->input->post("check7"),
				"check8"			=> $this->input->post("check8"),
				"check9"			=> $this->input->post("check9"),
				"check10"			=> $this->input->post("check10"),
				"check11"			=> $this->input->post("check11"),
				"check12"			=> $this->input->post("check12"),
				"userbuat"			=> $username,
				"tglbuat"			=> $tanggal
			);

			$dataedit	= array(
				"droperator"		=> $this->input->post("droperator"),
				"perawat"			=> $this->input->post("perawat"),
				"kodetarif"			=> $this->input->post("kodetarif"),
				"jampuasa"			=> $this->input->post("jampuasa"),
				"ket1"				=> $this->input->post("ket1"),
				"ket2"				=> $this->input->post("ket2"),
				"check1"			=> $this->input->post("check1"),
				"check2"			=> $this->input->post("check2"),
				"check3"			=> $this->input->post("check3"),
				"check4"			=> $this->input->post("check4"),
				"check5"			=> $this->input->post("check5"),
				"check6"			=> $this->input->post("check6"),
				"check7"			=> $this->input->post("check7"),
				"check8"			=> $this->input->post("check8"),
				"check9"			=> $this->input->post("check9"),
				"check10"			=> $this->input->post("check10"),
				"check11"			=> $this->input->post("check11"),
				"check12"			=> $this->input->post("check12"),
				"useredit"			=> $username,
				"tgledit"			=> $tanggal
			);

			if($param == 1){
				$query_insert = $this->db->update('tbl_checklistpppo',$dataedit, array('nojadwal' => $nojadwal));	
			} else {
				$query_insert = $this->db->insert("tbl_checklistpppo", $data);
			}
			
			if($query_insert){
				echo json_encode(array("status" => 1));
			} else {
				echo json_encode(array("status" => 0));
			}
		} else {
			redirect("/");
		}
	}

	public function save_prosedur_safety_checklist($param = ""){
		$unit		= $this->session->userdata("unit");
		$check		= $this->session->userdata("level");
		$username	= $this->session->userdata("username");
		$tanggal	= date("Y-m-d H:i:s");
		$nojadwal	= $this->input->post("nojadwal");

		if(!empty($check)){

			$data		= array(
				"koders"			=> $unit,
				"nojadwal"			=> $nojadwal,
				"noreg"				=> $this->input->post("noreg"),
				"rekmed"			=> $this->input->post("rekmed"),
				"droperator"		=> $this->input->post("droperator"),
				"tanggal"			=> $this->input->post("tanggal"),
				"timeout_text1"		=> $this->input->post("timeout_text1"),
				"signout_text1"		=> $this->input->post("signout_text1"),
				"signout_text2"		=> $this->input->post("signout_text2"),
				"jam1"				=> $this->input->post("jam1"),
				"jam2"				=> $this->input->post("jam2"),
				"jam3"				=> $this->input->post("jam3"),
				"jam4"				=> $this->input->post("jam4"),
				"jam5"				=> $this->input->post("jam5"),
				"signin_check1"		=> $this->input->post("signin_check1"),
				"signin_check2"		=> $this->input->post("signin_check2"),
				"signin_check3"		=> $this->input->post("signin_check3"),
				"signin_check4"		=> $this->input->post("signin_check4"),
				"signin_check5"		=> $this->input->post("signin_check5"),
				"signin_check6"		=> $this->input->post("signin_check6"),
				"signin_check7"		=> $this->input->post("signin_check7"),
				"signin_check8"		=> $this->input->post("signin_check8"),
				"signin_check9"		=> $this->input->post("signin_check9"),
				"signin_check10"	=> $this->input->post("signin_check10"),
				"signin_check11"	=> $this->input->post("signin_check11"),
				"signin_check12"	=> $this->input->post("signin_check12"),
				"signin_check13"	=> $this->input->post("signin_check13"),
				"signin_check14"	=> $this->input->post("signin_check14"),
				"signin_check15"	=> $this->input->post("signin_check15"),
				"signin_check16"	=> $this->input->post("signin_check16"),
				"signin_check17"	=> $this->input->post("signin_check17"),
				"signin_check18"	=> $this->input->post("signin_check18"),
				"signin_check19"	=> $this->input->post("signin_check19"),
				"signin_check20"	=> $this->input->post("signin_check20"),
				"signin_check21"	=> $this->input->post("signin_check21"),
				"signin_check22"	=> $this->input->post("signin_check22"),
				"signin_check23"	=> $this->input->post("signin_check23"),
				"signin_check24"	=> $this->input->post("signin_check24"),
				"signin_check25"	=> $this->input->post("signin_check25"),
				"timeout_check1"	=> $this->input->post("timeout_check1"),
				"timeout_check2"	=> $this->input->post("timeout_check2"),
				"timeout_check3"	=> $this->input->post("timeout_check3"),
				"timeout_check4"	=> $this->input->post("timeout_check4"),
				"timeout_check5"	=> $this->input->post("timeout_check5"),
				"timeout_check6"	=> $this->input->post("timeout_check6"),
				"timeout_check7"	=> $this->input->post("timeout_check7"),
				"timeout_check8"	=> $this->input->post("timeout_check8"),
				"timeout_check9"	=> $this->input->post("timeout_check9"),
				"timeout_check10"	=> $this->input->post("timeout_check10"),
				"timeout_check11"	=> $this->input->post("timeout_check11"),
				"timeout_check12"	=> $this->input->post("timeout_check12"),
				"timeout_check13"	=> $this->input->post("timeout_check13"),
				"timeout_check14"	=> $this->input->post("timeout_check14"),
				"timeout_check15"	=> $this->input->post("timeout_check15"),
				"timeout_check16"	=> $this->input->post("timeout_check16"),
				"timeout_check17"	=> $this->input->post("timeout_check17"),
				"signout_check1"	=> $this->input->post("signout_check1"),
				"signout_check2"	=> $this->input->post("signout_check2"),
				"signout_check3"	=> $this->input->post("signout_check3"),
				"signout_check4"	=> $this->input->post("signout_check4"),
				"signout_check5"	=> $this->input->post("signout_check5")
			);

			$dataedit	= array(
				"droperator"		=> $this->input->post("droperator"),
				"tanggal"			=> $this->input->post("tanggal"),
				"timeout_text1"		=> $this->input->post("timeout_text1"),
				"signout_text1"		=> $this->input->post("signout_text1"),
				"signout_text2"		=> $this->input->post("signout_text2"),
				"jam1"				=> $this->input->post("jam1"),
				"jam2"				=> $this->input->post("jam2"),
				"jam3"				=> $this->input->post("jam3"),
				"jam4"				=> $this->input->post("jam4"),
				"jam5"				=> $this->input->post("jam5"),
				"signin_check1"		=> $this->input->post("signin_check1"),
				"signin_check2"		=> $this->input->post("signin_check2"),
				"signin_check3"		=> $this->input->post("signin_check3"),
				"signin_check4"		=> $this->input->post("signin_check4"),
				"signin_check5"		=> $this->input->post("signin_check5"),
				"signin_check6"		=> $this->input->post("signin_check6"),
				"signin_check7"		=> $this->input->post("signin_check7"),
				"signin_check8"		=> $this->input->post("signin_check8"),
				"signin_check9"		=> $this->input->post("signin_check9"),
				"signin_check10"	=> $this->input->post("signin_check10"),
				"signin_check11"	=> $this->input->post("signin_check11"),
				"signin_check12"	=> $this->input->post("signin_check12"),
				"signin_check13"	=> $this->input->post("signin_check13"),
				"signin_check14"	=> $this->input->post("signin_check14"),
				"signin_check15"	=> $this->input->post("signin_check15"),
				"signin_check16"	=> $this->input->post("signin_check16"),
				"signin_check17"	=> $this->input->post("signin_check17"),
				"signin_check18"	=> $this->input->post("signin_check18"),
				"signin_check19"	=> $this->input->post("signin_check19"),
				"signin_check20"	=> $this->input->post("signin_check20"),
				"signin_check21"	=> $this->input->post("signin_check21"),
				"signin_check22"	=> $this->input->post("signin_check22"),
				"signin_check23"	=> $this->input->post("signin_check23"),
				"signin_check24"	=> $this->input->post("signin_check24"),
				"signin_check25"	=> $this->input->post("signin_check25"),
				"signin_check26"	=> $this->input->post("signin_check26"),
				"signin_check27"	=> $this->input->post("signin_check27"),
				"timeout_check1"	=> $this->input->post("timeout_check1"),
				"timeout_check2"	=> $this->input->post("timeout_check2"),
				"timeout_check3"	=> $this->input->post("timeout_check3"),
				"timeout_check4"	=> $this->input->post("timeout_check4"),
				"timeout_check5"	=> $this->input->post("timeout_check5"),
				"timeout_check6"	=> $this->input->post("timeout_check6"),
				"timeout_check7"	=> $this->input->post("timeout_check7"),
				"timeout_check8"	=> $this->input->post("timeout_check8"),
				"timeout_check9"	=> $this->input->post("timeout_check9"),
				"timeout_check10"	=> $this->input->post("timeout_check10"),
				"timeout_check11"	=> $this->input->post("timeout_check11"),
				"timeout_check12"	=> $this->input->post("timeout_check12"),
				"timeout_check13"	=> $this->input->post("timeout_check13"),
				"timeout_check14"	=> $this->input->post("timeout_check14"),
				"timeout_check15"	=> $this->input->post("timeout_check15"),
				"timeout_check16"	=> $this->input->post("timeout_check16"),
				"timeout_check17"	=> $this->input->post("timeout_check17"),
				"signout_check1"	=> $this->input->post("signout_check1"),
				"signout_check2"	=> $this->input->post("signout_check2"),
				"signout_check3"	=> $this->input->post("signout_check3"),
				"signout_check4"	=> $this->input->post("signout_check4"),
				"signout_check5"	=> $this->input->post("signout_check5")
			);

			if($param == 1){
				$query_insert = $this->db->update('tbl_prosedursafetycheck',$dataedit, array('nojadwal' => $nojadwal));
			} else {
				$query_insert = $this->db->insert("tbl_prosedursafetycheck", $data);
			}

			if($query_insert){
				echo json_encode(array("status" => 1));
			} else {
				echo json_encode(array("status" => 0));
			}

		} else {
			redirect("/");
		}
	}

	public function save_resume_pasien_pulang_post_operasi($param = ""){
		$unit		= $this->session->userdata("unit");
		$check		= $this->session->userdata("level");
		$username	= $this->session->userdata("username");
		$tanggal	= date("Y-m-d H:i:s");
		$nojadwal	= $this->input->post("nojadwal");

		if(!empty($check)){

			$data		= array(
				"koders"				=> $unit,
				"nojadwal"				=> $nojadwal,
				"noreg"					=> $this->input->post("noreg"),
				"rekmed"				=> $this->input->post("rekmed"),
				"kodetarif"				=> $this->input->post("kodetarif"),
				"tingkatkesadaran"		=> $this->input->post("tingkatkesadaran"),
				"tingkatkesadaranket"	=> $this->input->post("tingkatkesadaranket"),
				"tekanandarah"			=> $this->input->post("tekanandarah"),
				"nadi"					=> $this->input->post("nadi"),
				"pernapasan"			=> $this->input->post("pernapasan"),
				"skalanyeri"			=> $this->input->post("skalanyeri"),
				"aktivitas" 			=> $this->input->post("aktivitas"),
				"aktivitasket"			=> $this->input->post("aktivitasket"),
				"pendidikankes"			=> $this->input->post("pendidikankes"),
				"pendidikankes2"		=> $this->input->post("pendidikankes2"),
				"terapidiet"			=> $this->input->post("terapidiet"),
				"terapidietket"			=> $this->input->post("terapidietket"),
				"dokter"				=> $this->input->post("dokter"),
				"tanggal"				=> $this->input->post("tanggal"),
				"nopendaftaran"			=> $this->input->post("nopendaftaran"),
				"evaluasipemahaman"		=> $this->input->post("evaluasipemahaman"),
				"hambatanpadapasien"	=> $this->input->post("hambatanpadapasien"),
				"hambatanpadapasienket"	=> $this->input->post("hambatanpadapasienket"),
				"intervensihambatan"	=> $this->input->post("intervensihambatan"),
				"intervensihambatanket"	=> $this->input->post("intervensihambatanket"),
				"userbuat"				=> $username,
				"tglbuat"				=> $tanggal
			);

			$dataedit	= array(
				"tingkatkesadaran"		=> $this->input->post("tingkatkesadaran"),
				"tingkatkesadaranket"	=> $this->input->post("tingkatkesadaranket"),
				"tekanandarah"			=> $this->input->post("tekanandarah"),
				"nadi"					=> $this->input->post("nadi"),
				"pernapasan"			=> $this->input->post("pernapasan"),
				"skalanyeri"			=> $this->input->post("skalanyeri"),
				"aktivitas" 			=> $this->input->post("aktivitas"),
				"aktivitasket"			=> $this->input->post("aktivitasket"),
				"pendidikankes"			=> $this->input->post("pendidikankes"),
				"terapidiet"			=> $this->input->post("terapidiet"),
				"terapidietket"			=> $this->input->post("terapidietket"),
				"dokter"				=> $this->input->post("dokter"),
				"tanggal"				=> $this->input->post("tanggal"),
				"nopendaftaran"			=> $this->input->post("nopendaftaran"),
				"evaluasipemahaman"		=> $this->input->post("evaluasipemahaman"),
				"hambatanpadapasien"	=> $this->input->post("hambatanpadapasien"),
				"hambatanpadapasienket"	=> $this->input->post("hambatanpadapasienket"),
				"intervensihambatan"	=> $this->input->post("intervensihambatan"),
				"intervensihambatanket"	=> $this->input->post("intervensihambatanket"),
				"useredit"				=> $username,
				"tgledit"				=> $tanggal
			);

			if($param == 1){
				$query_insert = $this->db->update('tbl_resumepppo',$dataedit, array('nojadwal' => $nojadwal));
			} else {
				$query_insert = $this->db->insert("tbl_resumepppo", $data);
			}

			if($query_insert){
				echo json_encode(array("status" => 1));
			} else {
				echo json_encode(array("status" => 0));
			}

		} else {
			redirect("/");
		}
	}

	public function save_catatan_keperawatan_bedah($param = ""){
		$unit		= $this->session->userdata("unit");
		$check		= $this->session->userdata("level");
		$username	= $this->session->userdata("username");
		$tanggal	= date("Y-m-d H:i:s");
		$nojadwal	= $this->input->post("nojadwal");

		if(!empty($check)){

			$data		= array(
				"koders"				=> $unit,
				"nojadwal"				=> $nojadwal,
				"noreg"					=> $this->input->post("noreg"),
				"rekmed"				=> $this->input->post("rekmed"),
				"tgloperasi"			=> $this->input->post("tgloperasi"),
				"droperator"			=> $this->input->post("droperator"),
				"dranestesi"			=> $this->input->post("dranestesi"),
				"jenis_anestesi"		=> $this->input->post("jenis_anestesi"),
				"persiapandokumen1"		=> $this->input->post("persiapandokumen1"),
				"persiapandokumen2"		=> $this->input->post("persiapandokumen2"),
				"persiapandokumen4"		=> $this->input->post("persiapandokumen4"),
				"persiapandokumen5"		=> $this->input->post("persiapandokumen5"),
				"persiapandokumen6"		=> $this->input->post("persiapandokumen6"),
				"persiapandokumen7"		=> $this->input->post("persiapandokumen7"),
				"persiapandokumen9"		=> $this->input->post("persiapandokumen9"),
				"persiapandokumen10"	=> $this->input->post("persiapandokumen10"),
				"persiapandokumen11"	=> $this->input->post("persiapandokumen11"),
				"persiapandokumen12"	=> $this->input->post("persiapandokumen12"),
				"persiapanpasien1"		=> $this->input->post("persiapanpasien1"),
				"persiapanpasien2"		=> $this->input->post("persiapanpasien2"),
				"persiapanpasien3"		=> $this->input->post("persiapanpasien3"),
				"persiapanpasien4"		=> $this->input->post("persiapanpasien4"),
				"persiapanpasien5"		=> $this->input->post("persiapanpasien5"),
				"tekanandarah1"			=> $this->input->post("tekanandarah1"),
				"nadi1"					=> $this->input->post("nadi1"),
				"suhu1"					=> $this->input->post("suhu1"),
				"pernapasan1"			=> $this->input->post("pernapasan1"),
				"skalanyeri1"			=> $this->input->post("skalanyeri1"),
				"persiapanpasien6"		=> $this->input->post("persiapanpasien6"),
				"riwayatalergi"			=> $this->input->post("riwayatalergi"),
				"persiapanpasien7"		=> $this->input->post("persiapanpasien7"),
				"persiapanpasien8"		=> $this->input->post("persiapanpasien8"),
				"persiapanpasien9"		=> $this->input->post("persiapanpasien9"),
				"persiapanpasien10"		=> $this->input->post("persiapanpasien10"),
				"riwayatpenyakit"		=> $this->input->post("riwayatpenyakit"),
				"persiapanpasien11"		=> $this->input->post("persiapanpasien11"),
				"tetesantibiotik01"		=> $this->input->post("tetesantibiotik01"),
				"persiapanpasien12"		=> $this->input->post("persiapanpasien12"),
				"tetesantibiotik151"	=> $this->input->post("tetesantibiotik151"),
				"persiapanpasien13"  	=> $this->input->post("persiapanpasien13"),
				"tetesantibiotik301"	=> $this->input->post("tetesantibiotik301"),
				"persiapanpasien14"		=> $this->input->post("persiapanpasien14"),
				"tetesantibiotik451"	=> $this->input->post("tetesantibiotik451"),
				"persiapanpasien15"		=> $this->input->post("persiapanpasien15"),
				"tetesantibiotik601"	=> $this->input->post("tetesantibiotik601"),
				"persiapanpasien16"		=> $this->input->post("persiapanpasien16"),
				"persiapanpasien17"		=> $this->input->post("persiapanpasien17"),
				"mydriacyl11"			=> $this->input->post("mydriacyl11"),
				"persiapanpasien18"		=> $this->input->post("persiapanpasien18"),
				"mydriacyl21"			=> $this->input->post("mydriacyl21"),
				"persiapanpasien19"		=> $this->input->post("persiapanpasien19"),
				"mydriacyl31"			=> $this->input->post("mydriacyl31"),
				"persiapanpasien20"		=> $this->input->post("persiapanpasien20"),
				"persiapanpasien21"		=> $this->input->post("persiapanpasien21"),
				"persiapanpasien22"		=> $this->input->post("persiapanpasien22"),
				"persiapanpasien_intruksi"	=> $this->input->post("persiapanpasien_intruksi"),
				"persiapanpasien23"		=> $this->input->post("persiapanpasien23"),
				"dranestesi1"			=> $this->input->post("dranestesi1"),
				"jamsuntikanestesi"		=> $this->input->post("jamsuntikanestesi"),
				"anestesilokal1"		=> $this->input->post("anestesilokal1"),
				"anestesilokal2"		=> $this->input->post("anestesilokal2"),
				"berat_badan"			=> $this->input->post("berat_badan"),
				"anestesiumum1"			=> $this->input->post("anestesiumum1"),
				"anestesiumum2"			=> $this->input->post("anestesiumum2"),
				"anestesiumum3"			=> $this->input->post("anestesiumum3"),
				"anestesiumum4"			=> $this->input->post("anestesiumum4"),
				"jampuasa"				=> $this->input->post("jampuasa"),
				"anestesiumum5"			=> $this->input->post("anestesiumum5"),
				"anestesiumum6"			=> $this->input->post("anestesiumum6"),
				"iol1"					=> $this->input->post("iol1"),
				"iol2"					=> $this->input->post("iol2"),
				"intraoperasi1"			=> $this->input->post("intraoperasi1"),
				"intraoperasi2"			=> $this->input->post("intraoperasi2"),
				"intraoperasi3"			=> $this->input->post("intraoperasi3"),
				"intraoperasi4"			=> $this->input->post("intraoperasi4"),
				"intraoperasi6"			=> $this->input->post("intraoperasi6"),
				"intraoperasi7"			=> $this->input->post("intraoperasi7"),
				"intraoperasi8"			=> $this->input->post("intraoperasi8"),
				"intraoperasi10"		=> $this->input->post("intraoperasi10"),
				"intraoperasi12"		=> $this->input->post("intraoperasi12"),
				"intraoperasi13"		=> $this->input->post("intraoperasi13"),
				"nadi2"					=> $this->input->post("nadi2"),
				"spo2"					=> $this->input->post("spo2"),
				"intraoperasi14"		=> $this->input->post("intraoperasi14"),
				"intraoperasi15"		=> $this->input->post("intraoperasi15"),
				"intraoperasi16"		=> $this->input->post("intraoperasi16"),
				"intraoperasi17"		=> $this->input->post("intraoperasi17"),
				"postoperasi1"			=> $this->input->post("postoperasi1"),
				"postoperasi2"			=> $this->input->post("postoperasi2"),
				"postoperasi4"			=> $this->input->post("postoperasi4"),
				"tekanandarah2"			=> $this->input->post("tekanandarah2"),
				"nadi3"					=> $this->input->post("nadi3"),
				"suhu2"					=> $this->input->post("suhu2"),
				"pernapasan2"			=> $this->input->post("pernapasan2"),
				"skalanyeri2"			=> $this->input->post("skalanyeri2"),
				"tetesantibiotik02"		=> $this->input->post("tetesantibiotik02"),
				"tetesantibiotik152"	=> $this->input->post("tetesantibiotik152"),
				"tetesantibiotik302"	=> $this->input->post("tetesantibiotik302"),
				"tetesantibiotik452"	=> $this->input->post("tetesantibiotik452"),
				"tetesantibiotik602"	=> $this->input->post("tetesantibiotik602"),
				"postoperasi5"			=> $this->input->post("postoperasi5"),
				"postoperasi6"			=> $this->input->post("postoperasi6"),
				"postoperasi7"			=> $this->input->post("postoperasi7"),
				"postoperasi8"			=> $this->input->post("postoperasi8"),
				"postoperasi9"			=> $this->input->post("postoperasi9"),
				"postoperasi10"			=> $this->input->post("postoperasi10"),
				"postoperasi11"			=> $this->input->post("postoperasi11"),
				"postoperasi12"			=> $this->input->post("postoperasi12"),
				"catatan"				=> $this->input->post("catatan"),
				"userbuat"				=> $username,
				"tglbuat"				=> $tanggal
			);

			$dataedit	= array(
				"droperator"			=> $this->input->post("droperator"),
				"dranestesi"			=> $this->input->post("dranestesi"),
				"jenis_anestesi"		=> $this->input->post("jenis_anestesi"),
				"persiapandokumen1"		=> $this->input->post("persiapandokumen1"),
				"persiapandokumen2"		=> $this->input->post("persiapandokumen2"),
				"persiapandokumen4"		=> $this->input->post("persiapandokumen4"),
				"persiapandokumen5"		=> $this->input->post("persiapandokumen5"),
				"persiapandokumen6"		=> $this->input->post("persiapandokumen6"),
				"persiapandokumen7"		=> $this->input->post("persiapandokumen7"),
				"persiapandokumen9"		=> $this->input->post("persiapandokumen9"),
				"persiapandokumen10"	=> $this->input->post("persiapandokumen10"),
				"persiapandokumen11"	=> $this->input->post("persiapandokumen11"),
				"persiapandokumen12"	=> $this->input->post("persiapandokumen12"),
				"persiapanpasien1"		=> $this->input->post("persiapanpasien1"),
				"persiapanpasien2"		=> $this->input->post("persiapanpasien2"),
				"persiapanpasien3"		=> $this->input->post("persiapanpasien3"),
				"persiapanpasien4"		=> $this->input->post("persiapanpasien4"),
				"persiapanpasien5"		=> $this->input->post("persiapanpasien5"),
				"tekanandarah1"			=> $this->input->post("tekanandarah1"),
				"nadi1"					=> $this->input->post("nadi1"),
				"suhu1"					=> $this->input->post("suhu1"),
				"pernapasan1"			=> $this->input->post("pernapasan1"),
				"skalanyeri1"			=> $this->input->post("skalanyeri1"),
				"persiapanpasien6"		=> $this->input->post("persiapanpasien6"),
				"riwayatalergi"			=> $this->input->post("riwayatalergi"),
				"persiapanpasien7"		=> $this->input->post("persiapanpasien7"),
				"persiapanpasien8"		=> $this->input->post("persiapanpasien8"),
				"persiapanpasien9"		=> $this->input->post("persiapanpasien9"),
				"persiapanpasien10"		=> $this->input->post("persiapanpasien10"),
				"riwayatpenyakit"		=> $this->input->post("riwayatpenyakit"),
				"persiapanpasien11"		=> $this->input->post("persiapanpasien11"),
				"tetesantibiotik01"		=> $this->input->post("tetesantibiotik01"),
				"persiapanpasien12"		=> $this->input->post("persiapanpasien12"),
				"tetesantibiotik151"	=> $this->input->post("tetesantibiotik151"),
				"persiapanpasien13"  	=> $this->input->post("persiapanpasien13"),
				"tetesantibiotik301"	=> $this->input->post("tetesantibiotik301"),
				"persiapanpasien14"		=> $this->input->post("persiapanpasien14"),
				"tetesantibiotik451"	=> $this->input->post("tetesantibiotik451"),
				"persiapanpasien15"		=> $this->input->post("persiapanpasien15"),
				"tetesantibiotik601"	=> $this->input->post("tetesantibiotik601"),
				"persiapanpasien16"		=> $this->input->post("persiapanpasien16"),
				"persiapanpasien17"		=> $this->input->post("persiapanpasien17"),
				"mydriacyl11"			=> $this->input->post("mydriacyl11"),
				"persiapanpasien18"		=> $this->input->post("persiapanpasien18"),
				"mydriacyl21"			=> $this->input->post("mydriacyl21"),
				"persiapanpasien19"		=> $this->input->post("persiapanpasien19"),
				"mydriacyl31"			=> $this->input->post("mydriacyl31"),
				"persiapanpasien20"		=> $this->input->post("persiapanpasien20"),
				"persiapanpasien21"		=> $this->input->post("persiapanpasien21"),
				"persiapanpasien22"		=> $this->input->post("persiapanpasien22"),
				"persiapanpasien_intruksi"	=> $this->input->post("persiapanpasien_intruksi"),
				"persiapanpasien23"		=> $this->input->post("persiapanpasien23"),
				"dranestesi1"			=> $this->input->post("dranestesi1"),
				"jamsuntikanestesi"		=> $this->input->post("jamsuntikanestesi"),
				"anestesilokal1"		=> $this->input->post("anestesilokal1"),
				"anestesilokal2"		=> $this->input->post("anestesilokal2"),
				"berat_badan"			=> $this->input->post("berat_badan"),
				"anestesiumum1"			=> $this->input->post("anestesiumum1"),
				"anestesiumum2"			=> $this->input->post("anestesiumum2"),
				"anestesiumum3"			=> $this->input->post("anestesiumum3"),
				"anestesiumum4"			=> $this->input->post("anestesiumum4"),
				"jampuasa"				=> $this->input->post("jampuasa"),
				"anestesiumum5"			=> $this->input->post("anestesiumum5"),
				"anestesiumum6"			=> $this->input->post("anestesiumum6"),
				"iol1"					=> $this->input->post("iol1"),
				"iol2"					=> $this->input->post("iol2"),
				"intraoperasi1"			=> $this->input->post("intraoperasi1"),
				"intraoperasi2"			=> $this->input->post("intraoperasi2"),
				"intraoperasi3"			=> $this->input->post("intraoperasi3"),
				"intraoperasi4"			=> $this->input->post("intraoperasi4"),
				"intraoperasi6"			=> $this->input->post("intraoperasi6"),
				"intraoperasi7"			=> $this->input->post("intraoperasi7"),
				"intraoperasi8"			=> $this->input->post("intraoperasi8"),
				"intraoperasi10"		=> $this->input->post("intraoperasi10"),
				"intraoperasi12"		=> $this->input->post("intraoperasi12"),
				"intraoperasi13"		=> $this->input->post("intraoperasi13"),
				"nadi2"					=> $this->input->post("nadi2"),
				"spo2"					=> $this->input->post("spo2"),
				"intraoperasi14"		=> $this->input->post("intraoperasi14"),
				"intraoperasi15"		=> $this->input->post("intraoperasi15"),
				"intraoperasi16"		=> $this->input->post("intraoperasi16"),
				"intraoperasi17"		=> $this->input->post("intraoperasi17"),
				"postoperasi1"			=> $this->input->post("postoperasi1"),
				"postoperasi2"			=> $this->input->post("postoperasi2"),
				"postoperasi4"			=> $this->input->post("postoperasi4"),
				"tekanandarah2"			=> $this->input->post("tekanandarah2"),
				"nadi3"					=> $this->input->post("nadi3"),
				"suhu2"					=> $this->input->post("suhu2"),
				"pernapasan2"			=> $this->input->post("pernapasan2"),
				"skalanyeri2"			=> $this->input->post("skalanyeri2"),
				"tetesantibiotik02"		=> $this->input->post("tetesantibiotik02"),
				"tetesantibiotik152"	=> $this->input->post("tetesantibiotik152"),
				"tetesantibiotik302"	=> $this->input->post("tetesantibiotik302"),
				"tetesantibiotik452"	=> $this->input->post("tetesantibiotik452"),
				"tetesantibiotik602"	=> $this->input->post("tetesantibiotik602"),
				"postoperasi5"			=> $this->input->post("postoperasi5"),
				"postoperasi6"			=> $this->input->post("postoperasi6"),
				"postoperasi7"			=> $this->input->post("postoperasi7"),
				"postoperasi8"			=> $this->input->post("postoperasi8"),
				"postoperasi9"			=> $this->input->post("postoperasi9"),
				"postoperasi10"			=> $this->input->post("postoperasi10"),
				"postoperasi11"			=> $this->input->post("postoperasi11"),
				"postoperasi12"			=> $this->input->post("postoperasi12"),
				"catatan"				=> $this->input->post("catatan"),
				"useredit"				=> $username,
				"tgledit"				=> $tanggal
			);

			if($param == 1){
				$query_insert = $this->db->update('tbl_catatankeperawatanbedah',$dataedit, array('nojadwal' => $nojadwal));
			} else {
				$query_insert = $this->db->insert("tbl_catatankeperawatanbedah", $data);
			}

			if($query_insert){
				echo json_encode(array("status" => 1));
			} else {
				echo json_encode(array("status" => 0));
			}

		} else {
			redirect("/");
		}
	}

	public function save_cataract_surgery($param = ""){
		$unit		= $this->session->userdata("unit");
		$check		= $this->session->userdata("level");
		$username	= $this->session->userdata("username");
		$tanggal	= date("Y-m-d H:i:s");
		$nojadwal	= $this->input->post("nojadwal");

		$post		= $this->input->post();

		if(!empty($check)){

			$data		= array(
				"koders"				=> $unit,
				"nojadwal"				=> $nojadwal,
				"noreg"					=> $post["noreg"],
				"rekmed"				=> $post["rekmed"],
				"cataract_surgery"		=> $post["cataract_surgery"],
				"diabetics"				=> $post["diabetics"],
				"notes"					=> $post["notes"],
				"ucva"					=> $post["ucva"],
				"axl"					=> $post["axl"],
				"acd"					=> $post["acd"],
				"lt"					=> $post["lt"],
				"formula"				=> $post["formula"],
				"bcva"					=> $post["bcva"],
				"retinometri"			=> $post["retinometri"],
				"k1"					=> $post["k1"],
				"k2"					=> $post["k2"],
				"iolpowerconstant"		=> $post["iolpowerconstant"],
				"intraoperativedate"	=> $post["intraoperativedate"],
				"oproom"				=> $post["oproom"],
				"scrub"					=> $post["scrub"],
				"circulator"			=> $post["circulator"],
				"intraoperativetime"	=> $post["intraoperativetime"],
				"typeofsurgery"			=> $post["typeofsurgery"],
				"anesthesiologist"		=> $post["anesthesiologist"],
				"anesthesia"			=> $post["anesthesia"],
				"approach"				=> $post["approach"],
				"intracoa"				=> $post["intracoa"],
				"capsulatomy"			=> $post["capsulatomy"],
				"hydrodissection"		=> $post["hydrodissection"],
				"nucleus_management"	=> $post["nucleus_management"],
				"pacho_technique"		=> $post["pacho_technique"],
				"iol_placement"			=> $post["iol_placement"],
				"stitch"				=> $post["stitch"],
				"final_incision"		=> $post["final_incision"],
				"irigating_solution"	=> $post["irigating_solution"],
				"viscoelastic"			=> $post["viscoelastic"],
				"typeofiol"				=> $post["typeofiol"],
				"pacho_machine"			=> $post["pacho_machine"],
				"pacho_time"			=> $post["pacho_time"],
				"ept"					=> $post["ept"],
				"complication"			=> $post["complication"],
				"posterion_capsule_rupture"		=> $post["posterion_capsule_rupture"],
				"vitreous_prolapse"		=> $post["vitreous_prolapse"],
				"vitrectomy"			=> $post["vitrectomy"],
				"retained_lens_material"		=> $post["retained_lens_material"],
				"cortex_left"			=> $post["cortex_left"],
				"post_operative_diagnose"		=> $post["post_operative_diagnose"],
				"therapy"				=> $post["therapy"],
				"kodeicd"				=> $post["kodeicd"],
				"instruction_1"			=> $post["instruction_1"],
				"instruction_2"			=> $post["instruction_2"],
				"instruction_3"			=> $post["instruction_3"],
				"instruction_4"			=> $post["instruction_4"],
				"speciment"				=> $post["speciment"],
				"speciment_ket"			=> $post["speciment_ket"],
				"userbuat"				=> $username,
				"tglbuat"				=> $tanggal
			);

			$dataedit	= array(
				"cataract_surgery"		=> $post["cataract_surgery"],
				"diabetics"				=> $post["diabetics"],
				"notes"					=> $post["notes"],
				"ucva"					=> $post["ucva"],
				"axl"					=> $post["axl"],
				"acd"					=> $post["acd"],
				"lt"					=> $post["lt"],
				"formula"				=> $post["formula"],
				"bcva"					=> $post["bcva"],
				"retinometri"			=> $post["retinometri"],
				"k1"					=> $post["k1"],
				"k2"					=> $post["k2"],
				"iolpowerconstant"		=> $post["iolpowerconstant"],
				"intraoperativedate"	=> $post["intraoperativedate"],
				"oproom"				=> $post["oproom"],
				"scrub"					=> $post["scrub"],
				"circulator"			=> $post["circulator"],
				"intraoperativetime"	=> $post["intraoperativetime"],
				"typeofsurgery"			=> $post["typeofsurgery"],
				"anesthesiologist"		=> $post["anesthesiologist"],
				"anesthesia"			=> $post["anesthesia"],
				"approach"				=> $post["approach"],
				"intracoa"				=> $post["intracoa"],
				"capsulatomy"			=> $post["capsulatomy"],
				"hydrodissection"		=> $post["hydrodissection"],
				"nucleus_management"	=> $post["nucleus_management"],
				"pacho_technique"		=> $post["pacho_technique"],
				"iol_placement"			=> $post["iol_placement"],
				"stitch"				=> $post["stitch"],
				"final_incision"		=> $post["final_incision"],
				"irigating_solution"	=> $post["irigating_solution"],
				"viscoelastic"			=> $post["viscoelastic"],
				"typeofiol"				=> $post["typeofiol"],
				"pacho_machine"			=> $post["pacho_machine"],
				"pacho_time"			=> $post["pacho_time"],
				"ept"					=> $post["ept"],
				"complication"			=> $post["complication"],
				"posterion_capsule_rupture"		=> $post["posterion_capsule_rupture"],
				"vitreous_prolapse"		=> $post["vitreous_prolapse"],
				"vitrectomy"			=> $post["vitrectomy"],
				"retained_lens_material"		=> $post["retained_lens_material"],
				"cortex_left"			=> $post["cortex_left"],
				"post_operative_diagnose"		=> $post["post_operative_diagnose"],
				"therapy"				=> $post["therapy"],
				"kodeicd"				=> $post["kodeicd"],
				"instruction_1"			=> $post["instruction_1"],
				"instruction_2"			=> $post["instruction_2"],
				"instruction_3"			=> $post["instruction_3"],
				"instruction_4"			=> $post["instruction_4"],
				"speciment"				=> $post["speciment"],
				"speciment_ket"			=> $post["speciment_ket"],
				"useredit"				=> $username,
				"tgledit"				=> $tanggal
			);

			if($param == 1){
				$query_insert = $this->db->update('tbl_cataractsurgery',$dataedit, array('nojadwal' => $nojadwal));
			} else {
				$query_insert = $this->db->insert("tbl_cataractsurgery", $data);
			}

			if($query_insert){
				echo json_encode(array("status" => 1));
			} else {
				echo json_encode(array("status" => 0));
			}

		} else {
			redirect("/");
		}
	}

	public function save_laporan_operasi($param = ""){
		$unit		= $this->session->userdata("unit");
		$check		= $this->session->userdata("level");
		$username	= $this->session->userdata("username");
		$tanggal	= date("Y-m-d H:i:s");
		$nojadwal	= $this->input->post("nojadwal");

		$post		= $this->input->post();

		if(!empty($check)){

			$data		= array(
				"koders"				=> $unit,
				"nojadwal"				=> $nojadwal,
				"noreg"					=> $post["noreg"],
				"rekmed"				=> $post["rekmed"],
				"droperator"			=> $post["droperator"],
				"pembiayaan"			=> $post["pembiayaan"],
				"dranestesi"			=> $post["dranestesi"],
				"mulai_jam"				=> $post["mulai_jam"],
				"perawat_asisten1"		=> $post["perawat_asisten1"],
				"perawat_asisten2"		=> $post["perawat_asisten2"],
				"selesai_jam"			=> $post["selesai_jam"],
				"perawat_anestesi"		=> $post["perawat_anestesi"],
				"tanggal"				=> $post["tanggal"],
				"diagnosa_pra_operative"	=> $post["diagnosa_pra_operative"],
				"vod"					=> $post["vod"],
				"tod"					=> $post["tod"],
				"diagnosa_post_operative"	=> $post["diagnosa_post_operative"],
				"vos"					=> $post["vos"],
				"tos"					=> $post["tos"],
				"metode_operasi"		=> $post["metode_operasi"],
				"anestesi"				=> $post["anestesi"],
				"pelaksanaan_operasi"	=> $post["pelaksanaan_operasi"],
				"kondisi_mata"			=> $post["kondisi_mata"],
				"pengiriman_specimen"	=> $post["pengiriman_specimen"],
				"msics"					=> $post["msics"],
				"riwayat_penyakit"		=> $post["riwayat_penyakit"],
				"pachoemulsifikasi"		=> $post["pachoemulsifikasi"],
				"komplikasi_operasi"	=> $post["komplikasi_operasi"],
				"pterigium"				=> $post["pterigium"],
				"catatan"				=> $post["catatan"],
				"trabeculektomy"		=> $post["trabeculektomy"],
				"terapi"				=> $post["terapi"],
				"eviscerasi"			=> $post["eviscerasi"],
				"k1"					=> $post["k1"],
				"k2"					=> $post["k2"],
				"resimco"				=> $post["resimco"],
				"implantasi"			=> $post["implantasi"],
				"jahitan"				=> $post["jahitan"],
				"nomor"					=> $post["nomor"],
				"jumlah"				=> $post["jumlah"],
				"userbuat"				=> $username,
				"tglbuat"				=> $tanggal
			);

			$dataedit	= array(
				"droperator"			=> $post["droperator"],
				"pembiayaan"			=> $post["pembiayaan"],
				"dranestesi"			=> $post["dranestesi"],
				"mulai_jam"				=> $post["mulai_jam"],
				"perawat_asisten1"		=> $post["perawat_asisten1"],
				"perawat_asisten2"		=> $post["perawat_asisten2"],
				"selesai_jam"			=> $post["selesai_jam"],
				"perawat_anestesi"		=> $post["perawat_anestesi"],
				"tanggal"				=> $post["tanggal"],
				"diagnosa_pra_operative"	=> $post["diagnosa_pra_operative"],
				"vod"					=> $post["vod"],
				"tod"					=> $post["tod"],
				"diagnosa_post_operative"	=> $post["diagnosa_post_operative"],
				"vos"					=> $post["vos"],
				"tos"					=> $post["tos"],
				"metode_operasi"		=> $post["metode_operasi"],
				"anestesi"				=> $post["anestesi"],
				"pelaksanaan_operasi"	=> $post["pelaksanaan_operasi"],
				"kondisi_mata"			=> $post["kondisi_mata"],
				"pengiriman_specimen"	=> $post["pengiriman_specimen"],
				"msics"					=> $post["msics"],
				"riwayat_penyakit"		=> $post["riwayat_penyakit"],
				"pachoemulsifikasi"		=> $post["pachoemulsifikasi"],
				"komplikasi_operasi"	=> $post["komplikasi_operasi"],
				"pterigium"				=> $post["pterigium"],
				"catatan"				=> $post["catatan"],
				"trabeculektomy"		=> $post["trabeculektomy"],
				"terapi"				=> $post["terapi"],
				"eviscerasi"			=> $post["eviscerasi"],
				"k1"					=> $post["k1"],
				"k2"					=> $post["k2"],
				"resimco"				=> $post["resimco"],
				"implantasi"			=> $post["implantasi"],
				"jahitan"				=> $post["jahitan"],
				"nomor"					=> $post["nomor"],
				"jumlah"				=> $post["jumlah"],
				"useredit"				=> $username,
				"tgledit"				=> $tanggal
			);

			if($param == 1){
				$query_insert = $this->db->update('tbl_laporanoperasi',$dataedit, array('nojadwal' => $nojadwal));
			} else {
				$query_insert = $this->db->insert("tbl_laporanoperasi", $data);
			}

			if($query_insert){
				echo json_encode(array("status" => 1));
			} else {
				echo json_encode(array("status" => 0));
			}
		} else {
			redirect("/");
		}
	}

	public function save_billing($param = ""){
		$unit		= $this->session->userdata("unit");
		$check		= $this->session->userdata("level");
		$username	= $this->session->userdata("username");
		$tanggal	= date("Y-m-d H:i:s");

		$nojadwal	= $this->input->post("nojadwal");
		$noreg		= $this->input->post("noreg");
		$rekmed		= $this->input->post("rekmed");
		$paket		= $this->input->post("paket");

		$tarifkey	= $this->input->post("tarifkey");
		$kodetarif	= $this->input->post("kodetarif");
		$kodokter	= $this->input->post("kodokter");
		$tarifrp	= str_replace(".", "", $this->input->post("tarifrp"));
		$ok			= $this->input->post("ok");
		$cito		= $this->input->post("cito");
		$citorp		= str_replace(".", "", $this->input->post("citorp"));

		$bill		= $this->input->post("bill");
		$barang		= $this->input->post("barang");
		$satuan		= $this->input->post("satuan");
		$qty		= $this->input->post("qty");
		$harga		= str_replace(".", "", $this->input->post("harga"));
		$totalharga	= str_replace(".", "", $this->input->post("totalharga"));
		$lokasi		= $this->input->post("lokasi");

		$query_pasien	= $this->db->query("SELECT * FROM pasien_daftar WHERE noreg = '$noreg' OR rekmed = '$rekmed'")->row();
		$query_jadwal	= $this->db->query("SELECT * FROM tbl_hbedahjadwal WHERE nojadwal = '$nojadwal'")->row();
		$query_bhp		= $this->db->query("SELECT * FROM tbl_bedahobat WHERE nooperasi = '$nojadwal'")->result();

		if(!empty($check)){
			
			$data_header	= array(
				"koders"		=> $unit,
				"nooperasi"		=> $nojadwal,
				"tgloperasi"	=> $this->input->post("tgloperasi"),
				"jam"			=> $this->input->post("jamoperasi"),
				"noreg"			=> $noreg,
				"rekmed"		=> $rekmed,
				"asal"			=> $query_pasien->koderuang,
				"kelas"			=> $query_pasien->kelas,
				"bayar"			=> 0,
				"keluar"		=> 0,
				"posting"		=> 0,
				"cntbedah"		=> 0,
				"nojadwal"		=> $nojadwal,
				"username"		=> $username,
				"ship"			=> 0,
				"penyulit"		=> 0,
				"ruangoperasi"	=> $this->input->post("ruangop"),
				"paket"			=> $this->input->post("paket"),
				"kondisi"		=> 0,
				"noantri"		=> 0
			);

			$data_header_edit	= array(
				"username"		=> $username,
				"tgledit"		=> $tanggal,
				"ship"			=> 0,
				"penyulit"		=> 0,
				"ruangoperasi"	=> $this->input->post("ruangop"),
				"paket"			=> $this->input->post("paket"),
				"kondisi"		=> 0,
				"noantri"		=> 0
			);

			// Restock
			foreach($query_bhp as $bkey => $bval){
				$check_stock	= $this->db->query("SELECT * FROM tbl_barangstock WHERE kodebarang = '$bval->kodeobat'");

				if($check_stock->num_rows() != 0){
					$this->db->query("UPDATE tbl_barangstock SET 
					keluar = keluar-$qty[$bkey], 
					saldoakhir = saldoakhir+$qty[$bkey] 
					WHERE kodebarang = '$bval->kodeobat' 
					AND koders = '$unit'
					AND gudang = '$lokasi[$bkey]'");
				} else {
					$this->db->query("INSERT INTO tbl_barangstock (koders,kodebarang,gudang,keluar,saldoakhir,lasttr) 
					VALUES ('$unit','$bval->kodeobat','$lokasi[$bkey]','$qty[$bkey]','$qty[$bkey]','$tanggal')");
				}
			}

			// Delete Before
			if($param == 1){
				$this->db->query("DELETE FROM tbl_dbedahdetail WHERE nooperasi = '$nojadwal'");
				$this->db->query("DELETE FROM tbl_bedahobat WHERE nooperasi = '$nojadwal'");
			}

			// Then Insert Again
			foreach($tarifkey as $tkkey => $tkval){
				$data_detail	= array(
					"koders"		=> $unit,
					"nooperasi"		=> $nojadwal,
					"bedahkey"		=> $nojadwal,
					"kodetarif"		=> $kodetarif[$tkkey],
					"bedahidkey"	=> $tkval,
					"kodokter"		=> $kodokter[$tkkey],
					"tarifrp"		=> $tarifrp[$tkkey],
					"ok"			=> ($ok[$tkkey] == "on")? 1 : 0,
					"cito"			=> $cito[$tkkey],
					"citorp"		=> $citorp[$tkkey],
					"basetarif"		=> $tarifrp[$tkkey]
				);
					
				$this->db->insert("tbl_dbedahdetail", $data_detail);
			}

			foreach($barang as $bkey => $bval){
				$data_detail	= array(
					"nooperasi"		=> $nojadwal,
					"noreg"			=> $noreg,
					"rekmed"		=> $rekmed,
					"bill"			=> $bill[$bkey],
					"kodeobat"		=> $bval,
					"qty"			=> $qty[$bkey],
					"oldqty"		=> $qty[$bkey],
					"satuan"		=> $satuan[$bkey],
					"harga"			=> $harga[$bkey],
					"totalharga"	=> $totalharga[$bkey],
					"keyoperasi"	=> $nojadwal,
					"hpp"			=> 0,
					"gudang"		=> $lokasi[$bkey],
					"dibebankan"	=> 0,
					"diskon"		=> 0,
					"diskonrp"		=> 0,
					"harga2"		=> 0
				);

				$this->db->insert("tbl_bedahobat", $data_detail);

				$check_stock	= $this->db->query("SELECT * FROM tbl_barangstock WHERE kodebarang = '$bval'");

				if($check_stock->num_rows() != 0){
					$this->db->query("UPDATE tbl_barangstock SET 
					keluar = keluar+$qty[$bkey], 
					saldoakhir = saldoakhir-$qty[$bkey] 
					WHERE kodebarang = '$bval' 
					AND koders = '$unit'
					AND gudang = '$lokasi[$bkey]'");
				} else {
					$this->db->query("INSERT INTO tbl_barangstock (koders,kodebarang,gudang,keluar,saldoakhir,lasttr) 
					VALUES ('$unit','$bval','$lokasi[$bkey]','$qty[$bkey]','$qty[$bkey]','$tanggal')");
				}
			}

			if($param == 1){
				$query_insert = $this->db->update('tbl_hbedah',$data_header_edit, array('nojadwal' => $nojadwal));
			} else {
				$query_insert = $this->db->insert("tbl_hbedah", $data_header);
			}

			if($query_insert){
				echo json_encode(array("status" => 1));
			} else {
				echo json_encode(array("status" => 0));
			}
		} else {
			redirect("/");
		}
	}

	// Tasks

	public function persetujuan_umum($param){
		$cek		= $this->session->userdata("level");
		$unit		= $this->session->userdata("unit");
		$username	= $this->session->userdata("username");

		$query_jadwal	= $this->db->query("SELECT a.nojadwal, a.noreg, a.rekmed, a.namapas, b.jkel, b.tgllahir, b.alamat, b.phone 
		FROM tbl_hbedahjadwal AS a 
		LEFT JOIN pasien_daftar AS b ON b.noreg = a.noreg
		WHERE a.nojadwal = '$param'");

		$query_checkpu	= $this->db->query("SELECT * FROM tbl_persetujuanumum WHERE nojadwal = '$param'");
		$query_pu		= $this->db->query("SELECT * FROM tbl_persetujuanumum WHERE nojadwal = '$param'")->row();

		$query_hubk		= $this->db->query("SELECT * FROM tbl_setinghms WHERE lset = 'SILS' ORDER BY keterangan ASC");

		if(!empty($cek)){
			$data	= [
				"menu" 		=> "Bedah Central",
				"submenu" 	=> "Persetujuan Umum",
				"link"		=> "Bedah_Central",
				"unit"		=> $unit,
				"jadwal"	=> $query_jadwal->row(),
				"hubungank" => $query_hubk->result(),
				"idcetak"	=> $param,
				"statuspu"	=> ($query_checkpu->num_rows() > 0)? "done" : "undone",
				"datapu"	=> $query_pu,
			];

			$this->load->view("Bedah_Central/v_persetujuan_umum", $data);
		} else {
			redirect("/");
		}
	}

	public function persetujuan_tindakan_dokter($param){
		$cek		= $this->session->userdata("level");
		$unit		= $this->session->userdata("unit");
		$username	= $this->session->userdata("username");

		$query_dokter	= $this->db->query("SELECT * FROM tbl_dokter ORDER BY nadokter ASC");

		$query_jadwal	= $this->db->query("SELECT a.nojadwal, a.noreg, a.rekmed, a.namapas, b.jkel, b.tgllahir, b.alamat, b.phone 
		FROM tbl_hbedahjadwal AS a 
		LEFT JOIN pasien_daftar AS b ON b.noreg = a.noreg
		WHERE a.nojadwal = '$param'");

		$query_hubk		= $this->db->query("SELECT * FROM tbl_setinghms WHERE lset = 'SILS' ORDER BY keterangan ASC");

		$query_ptd		= $this->db->query("SELECT * FROM tbl_persetujuantindakdokter WHERE nojadwal = '$param'")->row();
		$query_checkptd	= $this->db->query("SELECT * FROM tbl_persetujuantindakdokter WHERE nojadwal = '$param'");

		if(!empty($cek)){
			$data	= [
				"menu" 		=> "Bedah Central",
				"submenu" 	=> "Persetujuan Tindakan Dokter",
				"link"		=> "Bedah_Central",
				"unit"		=> $unit,
				"jadwal"	=> $query_jadwal->row(),
				"hubungank" => $query_hubk->result(),
				"dokter"	=> $query_dokter->result(),
				"idcetak"	=> $param,
				"statusptd"	=> ($query_checkptd->num_rows() > 0)? "done" : "undone",
				"dataptd"	=> $query_ptd,
			];

			$this->load->view("Bedah_Central/v_persetujuan_tindakan_dokter", $data);
		} else {
			redirect("/");
		}
	}

	public function checklist_persiapan_pasien_preoperasi($param){
		$cek		= $this->session->userdata("level");
		$unit		= $this->session->userdata("unit");
		$username	= $this->session->userdata("username");

		$query_jadwal	= $this->db->query("SELECT a.nojadwal, a.noreg, a.rekmed, a.namapas, a.kodetarif, a.droperator, b.jkel, b.tgllahir, b.alamat, b.phone 
		FROM tbl_hbedahjadwal AS a 
		LEFT JOIN pasien_daftar AS b ON b.noreg = a.noreg
		WHERE a.nojadwal = '$param'");

		$query_dokter	= $this->db->query("SELECT * FROM tbl_dokter ORDER BY nadokter ASC");

		$query_cpo			= $this->db->query("SELECT * FROM tbl_checklistpppo WHERE nojadwal = '$param'")->row();
		$query_checkcpo		= $this->db->query("SELECT * FROM tbl_checklistpppo WHERE nojadwal = '$param'");

		if(!empty($cek)){

			$data	= [
				"menu" 		=> "Bedah Central",
				"submenu" 	=> "Cek List Persiapan Pasien Pre-Operasi",
				"link"		=> "Bedah_Central",
				"unit"		=> $unit,
				"jadwal"	=> $query_jadwal->row(),
				"dokter"	=> $query_dokter->result(),
				"idcetak"	=> $param,
				"statuscpo"	=> ($query_checkcpo->num_rows() > 0)? "done" : "undone",
				"datacpo"	=> $query_cpo,
			];

			$this->load->view("Bedah_Central/v_checklist_persiapan_pasien_preoperasi",$data);
		} else {
			redirect("/");
		}
	}

	public function prosedur_safety_checklist($param){
		$cek		= $this->session->userdata("level");
		$unit		= $this->session->userdata("unit");
		$username	= $this->session->userdata("username");

		$query_jadwal	= $this->db->query("SELECT a.nojadwal, a.tgloperasi, a.noreg, a.rekmed, a.namapas, a.kodetarif, a.droperator, b.jkel, b.tgllahir, b.alamat, b.phone 
		FROM tbl_hbedahjadwal AS a 
		LEFT JOIN pasien_daftar AS b ON b.noreg = a.noreg
		WHERE a.nojadwal = '$param'");
		$data_jadwal	= $query_jadwal->row();

		$query_dokter	= $this->db->query("SELECT * FROM tbl_dokter ORDER BY nadokter ASC");
		$dokter			= data_master("tbl_dokter", array("kodokter" => $data_jadwal->droperator))->nadokter;

		$query_psc			= $this->db->query("SELECT * FROM tbl_prosedursafetycheck WHERE nojadwal = '$param'")->row();
		$query_checkpsc		= $this->db->query("SELECT * FROM tbl_prosedursafetycheck WHERE nojadwal = '$param'");

		if(!empty($cek)){
			$data	= [
				"menu" 		=> "Bedah Central",
				"submenu" 	=> "Prosedur Safety Checklist",
				"link"		=> "Bedah_Central",
				"unit"		=> $unit,
				"idcetak"	=> $param,
				"statuspsc"	=> ($query_checkpsc->num_rows() > 0)? "done" : "undone",
				"datapsc"	=> $query_psc,
				"dokter"	=> $dokter,
				"listdokter"=> $query_dokter->result(),
				"kodokter"	=> $data_jadwal->droperator,
				"jadwal"	=> $query_jadwal->row(),
			];

			$this->load->view("Bedah_Central/v_prosedur_safety_checklist", $data);
		}
	}

	public function resume_pasien_pulang_post_operasi($param){
		$cek		= $this->session->userdata("level");
		$unit		= $this->session->userdata("unit");
		$username	= $this->session->userdata("username");

		$query_jadwal	= $this->db->query("SELECT a.nojadwal, a.tgloperasi, a.noreg, a.rekmed, a.namapas, a.kodetarif, a.droperator, b.jkel, b.tgllahir, b.alamat, b.phone 
		FROM tbl_hbedahjadwal AS a 
		LEFT JOIN pasien_daftar AS b ON b.noreg = a.noreg
		WHERE a.nojadwal = '$param'");
		$data_jadwal	= $query_jadwal->row();

		$jenis_operasi 	= data_master("tbl_tarifrs", array("kodetarif" => $data_jadwal->kodetarif))->tindakan;

		$query_dokter	= $this->db->query("SELECT * FROM tbl_dokter ORDER BY nadokter ASC");

		$query_rpo			= $this->db->query("SELECT * FROM tbl_resumepppo WHERE nojadwal = '$param'")->row();
		$query_checkrpo		= $this->db->query("SELECT * FROM tbl_resumepppo WHERE nojadwal = '$param'");

		if(!empty($cek)){
			$data	= [
				"menu" 		=> "Bedah Central",
				"submenu" 	=> "Resume Pasien Pulang Post Operasi",
				"link"		=> "Bedah_Central",
				"unit"		=> $unit,
				"idcetak"	=> $param,
				"statusrpo"	=> ($query_checkrpo->num_rows() > 0)? "done" : "undone",
				"datarpo"	=> $query_rpo,
				"dokter"	=> $query_dokter->result(),
				"jadwal"	=> $query_jadwal->row(),
				"jenisop"	=> $jenis_operasi,
			];

			$this->load->view("Bedah_Central/v_resume_pasien_pulang_post_operasi", $data);
		}
	}

	public function catatan_keperawatan_bedah($param){
		$cek		= $this->session->userdata("level");
		$unit		= $this->session->userdata("unit");
		$username	= $this->session->userdata("username");

		$query_jadwal	= $this->db->query("SELECT a.nojadwal, a.tgloperasi, a.noreg, a.rekmed, a.namapas, a.kodetarif, a.droperator, a.dranestasi, b.jkel, b.tgllahir, b.alamat, b.phone 
		FROM tbl_hbedahjadwal AS a 
		LEFT JOIN pasien_daftar AS b ON b.noreg = a.noreg
		WHERE a.nojadwal = '$param'");
		$data_jadwal	= $query_jadwal->row();

		$query_ckb		= $this->db->query("SELECT * FROM tbl_catatankeperawatanbedah WHERE nojadwal = '$param'")->row();
		$query_ptd		= $this->db->query("SELECT * FROM tbl_persetujuantindakdokter WHERE nojadwal = '$param'")->row();

		$query_dokter	= $this->db->query("SELECT * FROM tbl_dokter ORDER BY nadokter ASC");

		$dokter_operator	= data_master("tbl_dokter", array("kodokter" => $data_jadwal->droperator))->nadokter;
		$dokter_anestesi	= data_master("tbl_dokter", array("kodokter" => $data_jadwal->dranestasi))->nadokter;

		$query_checkckb		= $this->db->query("SELECT * FROM tbl_catatankeperawatanbedah WHERE nojadwal = '$param'");

		$item_cpo			= $this->db->query("SELECT * FROM tbl_checklistpppo WHERE nojadwal = '$param'");
		$item_ptd			= $this->db->query("SELECT * FROM tbl_persetujuantindakdokter WHERE nojadwal = '$param'");
		$item_cpo2			= $item_cpo->row();

		if(!empty($cek) && $query_jadwal->num_rows() != null){
			$data	= [
				"menu" 		=> "Bedah Central",
				"submenu" 	=> "Catatan Keperawatan Bedah",
				"link"		=> "Bedah_Central",
				"unit"		=> $unit,
				"idcetak"	=> $param,
				"statusckb"	=> ($query_checkckb->num_rows() > 0)? "done" : "undone",
				"datackb"	=> $query_ckb,
				"dokter"	=> $query_dokter->result(),
				"jenisan"	=> ($query_ptd)? $query_ptd->edutypeanes : "",
				"dokterop"	=> $dokter_operator,
				"dokteran"	=> $dokter_anestesi,
				"jadwal"	=> $query_jadwal->row(),
				"item_cpo"	=> ($item_cpo->num_rows() != 0)? 1 : 0,
				"item_ptd"	=> ($item_ptd->num_rows() != 0)? 1 : 0,
				"biometri"	=> ($item_cpo2->check1 == 1)? 1 : 0,
				"retinomt"	=> ($item_cpo2->check3 == 1)? 1 : 0,
				"laborato"	=> ($item_cpo2->check4 == 1)? 1 : 0,
				"biusumum"	=> ($item_cpo2->check10 == 1)? 1 : 0,
			];

			$this->load->view("Bedah_Central/v_catatan_keperawatan_bedah", $data);
		} else {
			redirect("/");
		}
	}

	public function cataract_surgery($param){
		$cek		= $this->session->userdata("level");
		$unit		= $this->session->userdata("unit");
		$username	= $this->session->userdata("username");

		$query_jadwal	= $this->db->query("SELECT a.nojadwal, a.tgloperasi, a.noreg, a.rekmed, a.namapas, a.kodetarif, a.droperator, a.dranestasi, b.jkel, b.tgllahir, b.alamat, b.phone 
		FROM tbl_hbedahjadwal AS a 
		LEFT JOIN pasien_daftar AS b ON b.noreg = a.noreg
		WHERE a.nojadwal = '$param'");
		$data_jadwal	= $query_jadwal->row();

		$query_cs		= $this->db->query("SELECT * FROM tbl_cataractsurgery WHERE nojadwal = '$param'")->row();
		$query_checkcs  = $this->db->query("SELECT * FROM tbl_cataractsurgery WHERE nojadwal = '$param'");

		if(!empty($cek)){
			$data	= [
				"menu" 		=> "Bedah Central",
				"submenu" 	=> "Cataract Surgery",
				"link"		=> "Bedah_Central",
				"unit"		=> $unit,
				"idcetak"	=> $param,
				"statuscs"	=> ($query_checkcs->num_rows() > 0)? "done" : "undone",
				"datacs"	=> $query_cs,
				"jadwal"	=> $query_jadwal->row(),
			];

			$this->load->view("Bedah_Central/v_cataract_surgery", $data);
		} else {
			redirect("/");
		}
	}

	public function laporan_operasi($param){
		$cek		= $this->session->userdata("level");
		$unit		= $this->session->userdata("unit");
		$username	= $this->session->userdata("username");

		$query_jadwal	= $this->db->query("SELECT b.kelas, a.nojadwal, a.tgloperasi, a.noreg, a.rekmed, a.namapas, a.kodetarif, a.droperator, a.dranestasi, a.asdroperator, a.asdroperatar, a.asdranestasi, b.jkel, b.tgllahir, b.alamat, b.phone 
		FROM tbl_hbedahjadwal AS a 
		LEFT JOIN pasien_daftar AS b ON b.noreg = a.noreg
		WHERE a.nojadwal = '$param'");
		$data_jadwal	= $query_jadwal->row();

		$dokter				= $this->db->query("SELECT * FROM tbl_dokter ORDER BY nadokter ASC")->result();
		$query_ptd		= $this->db->query("SELECT * FROM tbl_persetujuantindakdokter WHERE nojadwal = '$param'")->row();

		$query_cs		= $this->db->query("SELECT * FROM tbl_cataractsurgery WHERE nojadwal = '$param'")->row();
		$query_lo		= $this->db->query("SELECT * FROM tbl_laporanoperasi WHERE nojadwal = '$param'")->row();
		$query_checklo  	= $this->db->query("SELECT * FROM tbl_laporanoperasi WHERE nojadwal = '$param'");

		if(!empty($cek)){
			$data	= [
				"menu" 			=> "Bedah Central",
				"submenu" 		=> "Laporan Operasi",
				"link"			=> "Bedah_Central",
				"unit"			=> $unit,
				"idcetak"		=> $param,
				"dokter"		=> $dokter,
				"jenisan"		=> ($query_ptd)? $query_ptd->edutypeanes : "",
				"praoperative"	=> ($query_cs)? $query_cs->notes : "",
				"postoperative"	=> ($query_cs)? $query_cs->post_operative_diagnose : "",
				"speciment"		=> ($query_cs)? $query_cs->speciment : "",
				"therapi"		=> ($query_cs)? $query_cs->therapy : "",
				"k1"			=> ($query_cs)? $query_cs->k1 : "",
				"k2"			=> ($query_cs)? $query_cs->k2 : "",
				"statuslo"		=> ($query_checklo->num_rows() > 0)? "done" : "undone",
				"datalo"		=> $query_lo,
				"jadwal"		=> $query_jadwal->row(),
			];

			$this->load->view("Bedah_Central/v_laporan_operasi", $data);
		} else {
			redirect("/");
		}
	}

	// Cetak

	public function cetakpu($id){
		$check		 	= $this->session->userdata("is_logged_in");

		$query_check 	= $this->db->query("SELECT * FROM tbl_persetujuanumum WHERE nojadwal = '$id'");

		if($query_check->num_rows() == 0 || empty($check)){
			redirect("/");
		} else {

			$query		= $this->db->query("SELECT * FROM tbl_persetujuanumum WHERE nojadwal = '$id'");

			$data_row	= $query->row();

			$pdf=new rsreport();
			$pdf->header = 1;
		
			// $pdf->header = 0;
			// $pdf->AddPage();
			
			$pdf->setID("RS MATA M77", "", "");
			$pdf->setfont('arial');
			$pdf->setjudul('');
			$pdf->setsubjudul('');
			$pdf->addpage("P","A4");
			$pdf->setsize("P","A4");
			$pdf->ln(2);

			$pdf->SetWidths(array(190));
			$pdf->setfont('Arial','B','14');
			$pdf->FancyRow(array('PERSETUJUAN UMUM'),0,0,"C");
			$pdf->ln(5);

			$pdf->SetWidths(array(95,95));
			$pdf->setfont('arial','B','12');
			$align	= array("L","L");
			$pdf->FancyRow(array("Yang bertanda tangan dibawah ini :","Wali atau Keluarga terderkat :"),0,0,$align);
			$pdf->ln(1);

			$pdf->SetWidths(array(30,5,60,30,5,60));
			$pdf->setfont('arial','','10');
			$align	= array("L","L","L");
			$pdf->FancyRow(array("No Rekam Medis",":",$data_row->rekmed,"Nama",":",$data_row->nama_w),0,0,$align);
			$pdf->FancyRow(array("Nama",":",$data_row->nama_p,"Jenis Kelamin",":",($data_row->jkel_w == "P")? "PRIA" : "WANITA"),0,0,$align);
			$pdf->FancyRow(array("Tanggal Lahir",":",date("Y-m-d", strtotime($data_row->tgllahir_p)),"Hubungan",":",$data_row->hubungan_w),0,0,$align);
			$pdf->FancyRow(array("Alamat",":",$data_row->alamat_p,"Alamat",":",$data_row->alamat_w),0,0,$align);
			$pdf->FancyRow(array("No Handphone",":",$data_row->phone_p,"","",""),0,0,$align);
			$pdf->ln(5);

			$pdf->SetWidths(array(190));
			$pdf->setfont('arial','','10');
			$align	= array("L");
			$pdf->FancyRow(array("Dengan ini menyatakan persetujuan :"),0,0,$align);
			$pdf->ln(1);

			$pdf->SetWidths(array(190));
			$pdf->setfont('arial','B','10');
			$align	= array("L");
			$pdf->FancyRow(array("I. PERSETUJUAN UMUM UNTUK PERAWATAN DAN PENGOBATAN"),0,0,$align);
			$pdf->ln(1);

			$pdf->SetWidths(array(190));
			$pdf->setfont('arial','','10');
			$align	= array("L");
			$pdf->FancyRow(array("    1. Saya mengetahui bahwa saya kondisi yang membutuhkan perawatan medis, saya mengizinkan dokter dan profesional kesehatan lainnya untuk melakukan prosedur diagnostik dan untuk memberikan pengobatan medis tidak terbatas pada keratometri, biometri, tonometri, USG, foto fondus, Indirect, tes darah dan pemberian obat.
			 2. Saya sadar bahwa praktik kedokteran dan bedah bukanlah ilmu pasti dan saya mengakui bahwa tidak ada jaminan atas hasil apapun, terhadap perawatan prosedur atau pemeriksaan apapun yang dilakukan terhadap saya.
			 3. Saya mengerti dan memahami bahwa :
			 	 a. Saya memiliki hak untuk mengajukan pertanyaan tentang pengobatan yang diusulkan (termasuk identitas setiap orang yang memberikan atau mengamati pengobatan) setiap saat.
		 		 b. Saya mengerti dan memahami bahwa saya memiliki hak untuk persetujuaan, atau menolak persetujuan untuk setiap prosedur terapi.
				 c. Saya mengerti bahwa banyak dokter pada staff medis rumah sakit yang bukan karyawan, tetapi staff tamu yang terlah diberikan hak untuk menggunakan fasilitas untuk perawatan dan pengobatan pasien mereka."),0,0,$align);
			$pdf->ln(2);

			$pdf->SetWidths(array(190));
			$pdf->setfont('arial','B','10');
			$align	= array("L");
			$pdf->FancyRow(array("II. BARANG-BARANG MILIK PASIEN"),0,0,$align);
			$pdf->ln(1);

			$pdf->SetWidths(array(190));
			$pdf->setfont('arial','','10');
			$align	= array("L");
			$pdf->FancyRow(array("    Saya telah memahami bahwa rumah sakit tidak bertanggung jawab atas semua kehilangan barang-barang milik saya dan saya secara pribadi bertanggung jawab atas barang-barang berharga yang saya miliki, tidak terbatas pada uang, perhiasan, buku cek, kartu kredit, handphone atau barang lainnya. Dan apabila saya membutuhkan maka saya akan menitipkan barang-barang saya kepada rumah sakit.\n
			 Saya juga mengetahui bahwa saya harus memberitahu/menitipkan pada RS jika saya memiliki gigi palsu, kacamata, lensa kontak, prothestics atau barang lainnya yang saya butuhkan untuk diamankan."),0,0,$align);
			$pdf->ln(2);

			$pdf->SetWidths(array(190));
			$pdf->setfont('arial','B','10');
			$align	= array("L");
			$pdf->FancyRow(array("III. PERSETUJUAN PELEPASAN INFORMASI"),0,0,$align);
			$pdf->ln(1);

			$pdf->SetWidths(array(190));
			$pdf->setfont('arial','','10');
			$align	= array("L");
			$pdf->FancyRow(array("    Saya memahami informasi yang ada di dalam diri saya termasuk diagnosis, hasil laboratorium dan hasil tes diagnostik yang akan digunakan untuk perawatan medis di RS MATA M77 akan menjaminkan kerahasiannya.
			 Saya memberi wewenang kepada rumah sakit untuk memberikan informasi tentang rahasia kedokteran saya bila diperlukan untuk memproses klaim asuransi/BPJS/Jamkesmas/Perusahaan dan atau lembaga Pemerintah.
			 Saya tidak memberikan/memberikan (coret salah satu) wewenang kepada Rumah Sakit untuk memberikan tentang data dan informasi kesehatan saya kepada keluarga terdekat saya, yaitu :
			 1.    ..............................................................................................................
			 2.    ..............................................................................................................
			 3.    .............................................................................................................."),0,0,$align);
			$pdf->ln(2);

			$pdf->SetWidths(array(190));
			$pdf->setfont('arial','B','10');
			$align	= array("L");
			$pdf->FancyRow(array("IV. HAK DAN TANGGUNG JAWAB PASIEN"),0,0,$align);
			$pdf->ln(1);

			$pdf->SetWidths(array(190));
			$pdf->setfont('arial','','10');
			$align	= array("L");
			$pdf->FancyRow(array("     Saya memiliki hak untuk mengambil bagian dalam keputusan mengenai penyakit saya dan dalam hal keperawatan medis dan rencana pengobatan."),0,0,$align);
			$pdf->ln(2);

			$pdf->header = 0;
			$pdf->AddPage();

			$pdf->SetWidths(array(190));
			$pdf->setfont('arial','B','10');
			$align	= array("L");
			$pdf->FancyRow(array("V. KEINGINAN PRIVASI PASIEN"),0,0,$align);
			$pdf->ln(1);

			$pdf->SetWidths(array(190));
			$pdf->setfont('arial','','10');
			$align	= array("L");
			$pdf->FancyRow(array("    Saya mengizinjan/tidak mengizinkan (coret salah satu) Rumah Sakit memberi akses bagi keluarga atau handai taluan serta orang-orang yang akan melihat / menumui saya. (Sebutkan narna/profesi bila ada permintaan khusus)
			 .............................................................................................................................................................................................
			 menginginkan / atau tidak menginginkan (coret salah satu) privasi khusus. Sebutkan bila ada permintaan privasi khusus
			 ............................................................................................................................................................................................."),0,0,$align);
			$pdf->ln(2);

			$pdf->SetWidths(array(190));
			$pdf->setfont('arial','B','10');
			$align	= array("L");
			$pdf->FancyRow(array("VI . INFORMASI RAWAT INAP"),0,0,$align);
			$pdf->ln(1);

			$pdf->SetWidths(array(190));
			$pdf->setfont('arial','','10');
			$align	= array("L");
			$pdf->FancyRow(array("    Saya telah menerima informasi tentang peraturan yang diberlakukan oleh rumah sakit dan saya beserta keluarga bersedia untuk mematuhinya, termasuk akan mematuhi jam berkunjung pasien sesuai dengan aturan di Rumah Sakit.
			 Anggota keluarga saya yang menunggu saya bersedia untuk selalu mamakai tanda pengenal khusus yang diberikan oleh Rumah Sakit dan demi keamanan seluruh pasien setiap keluarga dan siapapun yang akan mengunjungi saya diluar jam berkunjung bersedia untuk diminta/diperiksa identitasnya."),0,0,$align);
			$pdf->ln(2);

			$pdf->SetWidths(array(190));
			$pdf->setfont('arial','B','10');
			$align	= array("L");
			$pdf->FancyRow(array("VII . INFORMASI BIAYA"),0,0,$align);
			$pdf->ln(1);

			$pdf->SetWidths(array(190));
			$pdf->setfont('arial','','10');
			$align	= array("L");
			$pdf->FancyRow(array("    Saya memahami tentang informasi biaya pengobatan atau biaya tindakan yang dijelaskan oleh petugas Rumah Sakit.\n
			 Dengan tanda tangan saya dibawah ini, saya menyatakan bahwa saya telah membaca dan memahami item pada Persetujuan Umum / General Consent."),0,0,$align);
			$pdf->ln(10);

			$pdf->SetWidths(array(95,95));
			$pdf->setfont('arial','B','10');
			$align	= array("C","C");
			$pdf->FancyRow(array("Petugas RS MATA M77","Pasien / Wali / Keluarga"), 0, 0, $align);
			$pdf->ln(15);
			$pdf->SetWidths(array(95,95));
			$pdf->setfont('arial','','10');
			$align	= array("C","C");
			$pdf->FancyRow(array("(........................................)","(........................................)"), 0, 0, $align);

			$pdf->SetTitle("Persetujuan Umum ". $data_row->nama_p);
			$pdf->AliasNbPages();
			$pdf->output($id.'.PDF','I');
		}
	}

	public function cetakptd($id){
		$check		 	= $this->session->userdata("is_logged_in");

		$query_check 	= $this->db->query("SELECT * FROM tbl_persetujuantindakdokter WHERE nojadwal = '$id'");

		if($query_check->num_rows() == null || $check != true){
			redirect("/");
		} else {
			$query		= $this->db->query("SELECT * FROM tbl_persetujuantindakdokter WHERE nojadwal = '$id'");
			$data_row	= $query->row();

			$dokter		= data_master('tbl_dokter', array('kodokter' => $data_row->edudoc))->nadokter;

			$nomor 		= 1; 

			$table1		= array(
				array("Diagnosis (WD & DD)\n(Working Diagnose & Different Diagnose)",$data_row->eduwddd,($data_row->check1 == "on")? "check" : "uncheck"),
				array("Dasar Diagnosis (Early Diagnose)",$data_row->edudiagnose,($data_row->check2 == "on")? "check" : "uncheck"),
				array("Tindakan Kedokteran (Medical Treatment)",$data_row->edumedtreat,($data_row->check3 == "on")? "check" : "uncheck"),
				array("Indikasi Tindakan (Indication)",$data_row->eduindication,($data_row->check4 == "on")? "check" : "uncheck"),
				array("Jenis Anestesi (Type of Anethesia)",$data_row->edutypeanes,($data_row->check5 == "on")? "check" : "uncheck"),
				array("Tujuan Tindakan Kedokteran\n(Purpose of Medical Treatment)",$data_row->edupurmedtreat,($data_row->check6 == "on")? "check" : "uncheck"),
				array("Resiko & Kompilkasi (Risk & Complication)",$data_row->eduriskcomp,($data_row->check7 == "on")? "check" : "uncheck"),
				array("Prognosis (Prognosis)",$data_row->eduprognosis,($data_row->check8 == "on")? "check" : "uncheck"),
				array("Alternatif & Resiko (Alternative & Risk)",$data_row->edualtrisk,($data_row->check9 == "on")? "check" : "uncheck"),
				array("Teknik Edukasi yang Digunakan\n(Education Technique)",$data_row->edutechnique,($data_row->check10 == "on")? "check" : "uncheck"),
				array("Perkiraan Biaya (Estimated Cost)",number_format($data_row->eduestcost, 0, '.', ','),($data_row->check11 == "on")? "check" : "uncheck"),
				array("Jenis Pembayaran (Type of Payment)",$data_row->edupaymethod,($data_row->check12 == "on")? "check" : "uncheck"),
			);

			$pdf=new rsreport();
			// $pdf->SetCellMargin(4);
			$pdf->header = 1;
		
			// $pdf->header = 0;
			// $pdf->AddPage();
			
			$pdf->setID("RS MATA M77", "", "");
			$pdf->setfont('arial');
			$pdf->setjudul('');
			$pdf->setsubjudul('');
			$pdf->addpage("P","A4");
			$pdf->setsize("P","A4");
			$pdf->ln(5);

			$pdf->SetWidths(array(190));
			$pdf->setfont('Arial','B','12');
			$pdf->FancyRow(array('PERSETUJUAN TINDAKAN DOKTER'),0,0,"C");
			$pdf->ln(5);

			$pdf->SetWidths(array(30,5,60,30,5,60));
			$pdf->setfont('arial','','9');
			$align	= array("L","L","L","L","L","L");
			$pdf->FancyRow(array("No Rekam Medis",":",$data_row->rekmed,"Tanggal Lahir",":",$data_row->pas2birth),0,0,$align);
			$pdf->FancyRow(array("Nama",":",$data_row->pas2name,"Alamat",":",$data_row->pas2address),0,0,$align);
			$pdf->FancyRow(array("Jenis Kelamin",":",($data_row->pas2jkel == "W")? "Wanita" : "Pria","","",""),0,0,$align);
			$pdf->ln(5);

			$pdf->SetWidths(array(190));
			$pdf->setfont('arial','B','9');
			$align	= array("C");
			$border = array("LBTR");
			$pdf->FancyRow(array("PEMBERIAN INFORMASI (EDUCATION)"),0,$border,$align);

			$pdf->SetWidths(array(75,115));
			$pdf->setfont('arial','','9');
			$align	= array("L","L");
			$border = array("LBTR","LBTR");
			$pdf->FancyRow(array("Tanggal & Pukul (Date & Time)", $data_row->edudate ." ". $data_row->edutime),0,$border,$align);
			$pdf->FancyRow(array("Dokter Pelaksana Tindakan (Primary Physician)", $dokter),0,$border,$align);
			$pdf->FancyRow(array("Pemberi Informasi (Informant)", $data_row->eduinformant),0,$border,$align);
			$pdf->FancyRow(array("Penerima Informasi s(Informed Person)", $data_row->eduinformed),0,$border,$align);

			$pdf->SetWidths(array(10,65,85,30));
			$pdf->setfont('arial','B','9');
			$align	= array("C","L","L","C");
			$border = array("LBTR","LBTR","LBTR","LBTR");
			$pdf->FancyRow(array("No","Jenis Informasi (Type of Information)","Isi Informasi (Content)","Tanda (Mark)"),0,$border,$align);

			$pdf->SetWidths(array(10,65,85,30));
			$pdf->setfont('arial','','9');
			$align	= array("C","L","L","C");
			$border = array("LBTR","LBTR","LBTR","LBTR");
			foreach($table1 as $tkey){
				$pdf->FancyRow(array($nomor++, $tkey[0], $tkey[1], $tkey[2]),0,$border,$align);
			}

			$pdf->SetWidths(array(160,30));
			$pdf->setfont('arial','B','9');
			$align	= array("L","C");
			$border = array("LBTR","LBTR");
			$pdf->FancyRow(array("Dengan ini menyatakn bahwa saya telah menerangkan hal-hal diatas secara benar dan jelas dan memberikan kesempatan untuk bertanya dan/atau berdikusi\n\n(Hereby I acknowledged that i have explained matters mentioned above details and providedopportunity for question and/or discussion)","Tanda Tangan Dokter\n\n\n\n(Physician Signature)"),0,$border,$align);
			$pdf->FancyRow(array("Dengan ini menyatakan bahwa saya telah menerima informasi sebagaimana diatas yang saya beri tanda/paraf dikolom kanannya, dan telah memahaminya\n\n(Hereby I acknowledge that i have been informed matters as mentioned above that i have marked in it's right column, and fully understood)","Tanda Tangan Pasien/Keluarga\n\n\n\n(Patient/Relative Signature)"),0,$border,$align);

			$pdf->SetWidths(array(190));
			$pdf->setfont('arial','B','9');
			$align	= array("L");
			$border = array("LBTR",);
			$pdf->FancyRow(array("*Bila pasien tidak kompeten atau tidak mau menerima informasi maka penerima informasi adalah wali atau keluarga terdekat\n(If patient is competent or unwilling to be informed, the cutodain and relatives should be informed on his / her behalf)"),0,$border,$align);

			$pdf->header = 0;
			$pdf->AddPage();

			$pdf->SetWidths(array(190));
			$pdf->setfont('arial','B','9');
			$align	= array("L");
			$border = array("");
			$pdf->FancyRow(array("Saya yang bertanda tangan dibawah ini:\nI, who have signed below:"),0,$border,$align);
			$pdf->ln(1);

			$pdf->SetWidths(array(70,10,110));
			$pdf->setfont('arial','','9');
			$align	= array("L","L","L");
			$pdf->FancyRow(array("Nama (Name)",":",$data_row->famname),0,0,$align);
			$pdf->FancyRow(array("Jenis Kelamin (Gender)",":",($data_row->famgender == "P")? "Pria (Male)" : "Wanita (Female)"),0,0,$align);
			$pdf->FancyRow(array("Hubungan Keluarga (Relationship to patient)",":",$data_row->famrelate),0,0,$align);
			$pdf->FancyRow(array("No Telepon (Phon Number)",":",$data_row->famphone),0,0,$align);
			$pdf->FancyRow(array("Alamat (Address)",":",$data_row->famaddress),0,0,$align);
			$pdf->ln(2);

			$pdf->SetWidths(array(190));
			$pdf->setfont('arial','B','9');
			$align	= array("L");
			$border = array("");
			$pdf->FancyRow(array("Dengan ini menyatakan PERSETUJUAN untuk dilakukan tindakan:\nHereby give my permission to undergo/be performed:"),0,$border,$align);
			$pdf->ln(6);

			$pdf->SetWidths(array(190));
			$pdf->setfont('arial','B','9');
			$align	= array("C");
			$border = array("T");
			$pdf->FancyRow(array(""),0,$border,$align);
			$pdf->ln(2);

			$pdf->SetWidths(array(70,10,110));
			$pdf->setfont('arial','','9');
			$align	= array("L","L","L");
			$pdf->FancyRow(array("Terhadap",":",($data_row->knowing == "1")? "Saya Sendiri/Pasien (My Self/Patient)" : "Wali/Keluarga (Relation)"),0,0,$align);
			$pdf->FancyRow(array("No Rekam Medik (Medical Record Number)",":",$data_row->rekmed),0,0,$align);
			$pdf->FancyRow(array("Nama (Name)",":",$data_row->pasname),0,0,$align);
			$pdf->FancyRow(array("Tanggal Lahir (Birth of Date)",":",$data_row->pasbirth),0,0,$align);
			$pdf->FancyRow(array("Jenis Kelamin (Gender)",":",($data_row->pasgender == "P")? "Pria (Male)" : "Wanita (Female)"),0,0,$align);
			$pdf->FancyRow(array("Alamat (Address)",":",$data_row->pasaddress),0,0,$align);
			$pdf->ln(6);

			$pdf->SetWidths(array(190));
			$pdf->setfont('arial','','9');
			$align	= array("L");
			$pdf->FancyRow(array("Saya memahami perlunya dan manfaat tindakan tersebut sebagaimana telah dijelaskan seperti diatas kepada saya, termasuk resiko dan komplikasi yang mungkin timbul.
			Saya juga menyadari bahwa oleh karena ilmu kedokteran bukanlah ilmu pasti, maka keberhasilan tindakan kedokteran bukanlah keniscayaan, melainkan sangat tergantung kepada izin Tuhan Yang Maha Esa.\n
			I have fully understood necessity and benefit of the treatment for me as explained above, including the risk and complication that may occur later.
			I am also aware that practice of medicines is not an exact science, therefore makes medical treatment is uncertain too, but depend on The Almighty's will"),0,0,$align);
			$pdf->ln(10);

			$pdf->SetWidths(array(62,2,62,2,62));
			$pdf->setfont('arial','','9');
			$align	= array("L","L","L","L","C");
			$pdf->FancyRow(array("","","","","Medan, ". date("d/m/Y") ." - ". date("H:i:s")),0,0,$align);
			$pdf->ln(5);

			$pdf->SetWidths(array(62,2,62,2,62));
			$pdf->setfont('arial','','9');
			$align	= array("C","","C","","C");
			$pdf->FancyRow(array("Dokter\n(Physician)","","Yang Menyatakan Persetujuan\n(Consented by)","","Saksi\n(Witness)"),0,0,$align);
			$pdf->ln(20);

			$pdf->SetWidths(array(62,2,62,2,62));
			$pdf->setfont('arial','','9');
			$align	= array("C","","C","","C");
			$border = array("B","","B","","B");
			$pdf->FancyRow(array($dokter,"",$data_row->pasname,"",$data_row->famname),0,$border,$align);

			$pdf->SetWidths(array(62,2,62,2,62));
			$pdf->setfont('arial','','9');
			$align	= array("C","","C","","C");
			$pdf->FancyRow(array("TTD & Nama Dokter\n(Physician's Signature & Full Name)","","TTD & Nama Pasien/Keluarga\n(Patient/Relative's Signature & Full Name)","","TTD & Nama Keluarga\n(Relative's Signature & Full Name)"),0,0,$align);

			$pdf->SetTitle("Persetujuan Tindakan Dokter ". $data_row->pas2name);
			$pdf->AliasNbPages();
			$pdf->output($id.'.PDF','I');
		}
	}

	public function cetakcpo($id){
		$check		 	= $this->session->userdata("is_logged_in");

		$query_check 	= $this->db->query("SELECT * FROM tbl_checklistpppo WHERE nojadwal = '$id'");

		if($query_check->num_rows() == null || $check != true){
			redirect("/");
		} else {
			$query		= $this->db->query("SELECT * FROM tbl_checklistpppo WHERE nojadwal = '$id'");
			$data_row	= $query->row();

			$pasien		= $this->db->query("SELECT a.nojadwal, a.noreg, a.rekmed, a.namapas, b.jkel, b.tgllahir, b.alamat, b.phone 
			FROM tbl_hbedahjadwal AS a 
			LEFT JOIN pasien_daftar AS b ON b.noreg = a.noreg
			WHERE a.nojadwal = '$data_row->nojadwal'")->row();
			$dokter		= data_master('tbl_dokter', array('kodokter' => $data_row->droperator))->nadokter;
			$kodetarif	= data_master('tbl_tarifrs', array('kodetarif' => $data_row->kodetarif))->tindakan;

			$nomor 		= 1;

			$table1		= array(
				array("Hasil Biometri",($data_row->check1 == 1)? "check" : "", ($data_row->check1 == 2)? "check" : ""),
				array("Hasil Noncon Robo",($data_row->check2 == 1)? "check" : "", ($data_row->check2 == 2)? "check" : ""),
				array("Hasil Retinometri",($data_row->check3 == 1)? "check" : "", ($data_row->check3 == 2)? "check" : ""),
				array("Hasil Laboratorium",($data_row->check4 == 1)? "check" : "", ($data_row->check4 == 2)? "check" : ""),
				array("Hasil Radiologi",($data_row->check5 == 1)? "check" : "", ($data_row->check5 == 2)? "check" : ""),
				array("Hasil Oculyer",($data_row->check6 == 1)? "check" : "", ($data_row->check6 == 2)? "check" : ""),
				array("Puasa Mulai Jam ". date("H:i:s", strtotime($data_row->jampuasa)),($data_row->check7 == 1)? "check" : "", ($data_row->check7 == 2)? "check" : ""),
				array("Instrukti Khusus Dari Dokter",($data_row->check8 == 1)? "check" : "", ($data_row->check8 == 2)? "check" : ""),
				array("Lensa",($data_row->check9 == 1)? "check" : "", ($data_row->check9 == 2)? "check" : ""),
				array("Bius Umum :\nLab\nRontgen\nECG, Usia > 40 Tahun\nHasil konsul Dr. Anak/Internist",($data_row->check10 == 1)? "check" : "", ($data_row->check10 == 2)? "check" : ""),
				array("Cek File : Hepatitis\nBila Hepatitis(+), Jadwal Paling Akhir\nPeny. Lainnya :\n".$data_row->ket1,($data_row->check11 == 1)? "check" : "", ($data_row->check11 == 2)? "check" : ""),
				array("Lain-Lain :\n". $data_row->ket2,($data_row->check12 == 1)? "check" : "", ($data_row->check12 == 2)? "check" : ""),
			);

			$pdf=new rsreport();
			// $pdf->SetCellMargin(4);
			$pdf->header = 1;
		
			// $pdf->header = 0;
			// $pdf->AddPage();
			
			$pdf->setID("RS MATA M77", "", "");
			$pdf->setfont('arial');
			$pdf->setjudul('');
			$pdf->setsubjudul('');
			$pdf->addpage("P","A4");
			$pdf->setsize("P","A4");
			$pdf->ln(5);

			$pdf->SetWidths(array(190));
			$pdf->setfont('Arial','B','12');
			$pdf->FancyRow(array('CEK LIST PERSIAPAN PASIEN PRE-OPERASI'),0,0,"C");
			$pdf->ln(5);

			$pdf->SetWidths(array(30,5,60,30,5,60));
			$pdf->setfont('arial','','9');
			$align	= array("L","L","L","L","L","L");
			$pdf->FancyRow(array("No Rekam Medis",":",$data_row->rekmed,"Tanggal Lahir",":",date("Y-m-d", strtotime($pasien->tgllahir))),0,0,$align);
			$pdf->FancyRow(array("Nama",":",$pasien->namapas,"Alamat",":",$pasien->alamat),0,0,$align);
			$pdf->FancyRow(array("Jenis Kelamin",":",($pasien->jkel == "W")? "Wanita" : "Pria","","",""),0,0,$align);
			$pdf->ln(5);

			$pdf->SetWidths(array(30,5,155));
			$pdf->setfont('arial','','9');
			$align	= array("L","L","L");
			$pdf->FancyRow(array("Dokter Operator",":",$dokter),0,0,$align);
			$pdf->FancyRow(array("Perawat",":",$data_row->perawat),0,0,$align);
			$pdf->FancyRow(array("Jenis Operasi",":",$kodetarif),0,0,$align);
			$pdf->ln(5);

			$pdf->SetWidths(array(10,72,36,36,36));
			$pdf->setfont('arial','B','9');
			$align	= array("C","L","C","C","C");
			$border = array("LBTR","LBTR","LBTR","LBTR","LBTR");
			$pdf->FancyRow(array("No","Hal-Hal Yang Perlu Diperhatikan","Ada","Tidak","Paraf"),0,$border,$align);

			$pdf->SetWidths(array(10,72,36,36,36));
			$pdf->setfont('arial','','9');
			$align	= array("C","L","C","C","C");
			$border = array("LBTR","LBTR","LBTR","LBTR","LBTR");
			foreach($table1 as $tkey){
				$pdf->FancyRow(array($nomor++, $tkey[0], $tkey[1], $tkey[2], ""),0,$border,$align);
			}
			$pdf->ln(5);

			$pdf->SetWidths(array(190));
			$pdf->setfont('Arial','','9');
			$pdf->FancyRow(array('Catatan:
			1. Nomor & untuk pasien LASIK
			2. Bila kasus Retina, Glaukoma, Keratoplasty, Okulaplasty dan Strabismus sesuai instruksi dokter
			3. Apabila hasil pemeriksaan Poli / Lab / Rontgen / ECG / Konsultasi Dr. Anak / Internist kurang lengkap, agar dilengkapi terlebih dahulu
			4. Apabila hasil pemeriksaan tidak sesuai, lapor Dokter'),0,0,"L");
			$pdf->ln(5);

			$pdf->SetTitle("Cek List Persiapan Pasien Pre-Operasi ". $pasien->namapas);
			$pdf->AliasNbPages();
			$pdf->output($id.'.PDF','I');
		}
	}

	public function cetakpsc($id){
		$check			= $this->session->userdata("is_logged_in");

		$query_check	= $this->db->query("SELECT * FROM tbl_prosedursafetycheck WHERE nojadwal = '$id'");

		if($query_check->num_rows() == null || $check != true){
			redirect("/");
		} else {
			$head		= $this->M_cetak->kop2();
			$judul		= "PROSEDUR SAFETY CHECK";
			$data		= $query_check->row();

			$pasien		= $this->db->query("SELECT a.nojadwal, a.noreg, a.rekmed, a.namapas, b.jkel, b.tgllahir, b.alamat, b.phone 
			FROM tbl_hbedahjadwal AS a 
			LEFT JOIN pasien_daftar AS b ON b.noreg = a.noreg
			WHERE a.nojadwal = '$data->nojadwal'")->row();
			$jenis_kelamin	= ($pasien->jkel == "P")? "Pria" : "Wanita";

			$dokter		= data_master("tbl_dokter", array("kodokter" => $data->droperator))->nadokter;

			$comp_name	= $head->namars;
			$comp_addr	= $head->alamat;
			$comp_phone	= $head->phone;
			$comp_image	= base_url().$head->image;

			$data_jam	= array(
				"Perawat Verifikasi"	=> $data->jam1,
				"Anestesi"				=> $data->jam2,
				"Perawat Sirkuler"		=> $data->jam3,
				"Perawat Asisten"		=> $data->jam4,
				"Operator"				=> $data->jam5,
			);

			$checked	= '<input type="checkbox" class="checkbox" checked="checked">';
			$uncheck	= '<input type="checkbox" class="checkbox">';

			$table1		= array(
				array("","<b>Apakah pasien (atau orang tua pasien) * sudah dipastikan :</b>",""),
				array(($data->signin_check1 == "on")? $checked .' Ya' : $uncheck .' Tidak',"Nama pasien",($data->signin_check2 == "on")?  $checked .' Ya' : $uncheck .' Tidak'),
				array(($data->signin_check3 == "on")? $checked .' Ya' : $uncheck .' Tidak',"Tanggal lahir dan alamat",($data->signin_check4 == "on")?  $checked .' Ya' : $uncheck .' Tidak'),
				array(($data->signin_check5 == "on")? $checked .' Ya' : $uncheck .' Tidak',"Prosedur operasi",($data->signin_check6 == "on")?  $checked .' Ya' : $uncheck .' Tidak'),
				array(($data->signin_check7 == "on")? $checked .' Ya' : $uncheck .' Tidak',"Sisi mata yang akan dioperasi",($data->signin_check8 == "on")?  $checked .' Ya' : $uncheck .' Tidak'),
				array(($data->signin_check9 == "on")? $checked .' Ya' : $uncheck .' Tidak',"jenis anestesi",($data->signin_check10 == "on")?  $checked .' Ya' : $uncheck .' Tidak'),
				array(($data->signin_check11 == "on")? $checked .' Ya' : $uncheck .' Tidak',"Surat persetujuan operasi sudah ditanda tangani dan sesuai",($data->signin_check12 == "on")?  $checked .' Ya' : $uncheck .' Tidak'),
				array(($data->signin_check13 == "on")? $checked .' Ya' : $uncheck .' Tidak',"Apakah sisi yang dioperasi sudah ditandai",($data->signin_check14 == "on")?  $checked .' Ya' : $uncheck .' Tidak'),
				array(($data->signin_check15 == 1)? $checked .' Ya' : $uncheck .' Tidak',"Alergi",($data->signin_check16 == 1)?  $checked .' Ya' : $uncheck .' Tidak'),
				array(($data->signin_check17 == 1)? $checked .' Ya' : $uncheck .' Tidak',"Apakah ada resiko kehilangan darah > 500cc (7 cc/kg bb anak)",($data->signin_check18 == 1)?  $checked .' Ya' : $uncheck .' Tidak'),
				array(($data->signin_check19 == 1)? $checked .' Ya' : $uncheck .' Tidak',"Memastikan apakah ada darah pengganti",($data->signin_check20 == 1)?  $checked .' Ya' : $uncheck .' Tidak'),
				array(($data->signin_check21 == 1)? $checked .' Ya' : $uncheck .' Tidak',"Apakah foto rontgen/CT Scan perlu dipajang",($data->signin_check22 == 1)?  $checked .' Ya' : $uncheck .' Tidak'),
				array(($data->signin_check23 == 1)? $checked .' Ya' : $uncheck .' Tidak',"Apakah pasien sudah dipuasakan",($data->signin_check24 == 1)?  $checked .' Ya' : $uncheck .' Tidak'),
				array("","Apakah mesin anestesi dan obat-obatan sudah di cek komplit",($data->signin_check25 == 1)?  $checked .' Ya' : $uncheck .' Tidak'),
				array("","Apakah ada hal yang spesifik berhubungan dengan anestesi",($data->signin_check26 == 1)?  $checked .' Ya' : $uncheck .' Tidak'),
				array("","Jika ya, apakah alat/asisten sudah tersedia ?",($data->signin_check27 == 1)?  $checked .' Ya' : $uncheck .' Tidak'),
			);

			$tc2	= ($data->timeout_check2 == "on")? $checked ." Siapa nama pasien ?<br />" : $uncheck ." Siapa nama pasien ?<br />";
			$tc3	= ($data->timeout_check3 == "on")? $checked ." Tanggal lahir dan alamat ?<br />" : $uncheck ." Tanggal lahir dan alamat ?<br />";
			$tc4	= ($data->timeout_check4 == "on")? $checked ." Apa prosedur operasi ?<br />" : $uncheck ." Apa prosedur operasi ?<br />";
			$tc5	= ($data->timeout_check5 == "on")? $checked ." Sisi mata yang akan dioperasi ?<br />" : $uncheck ." Sisi mata yang akan dioperasi ?<br />";
			$tc6	= ($data->timeout_check6 == "on")? $checked ." Informasi Lain : <i>". $data->timeout_text1 ."</i><br />" : $uncheck ." Informasi Lain : <i>". $data->timeout_text1 ."</i><br />";
			$tc16	= ($data->timeout_check16 == "on")? $checked ." Apakah sterilisasi alat sudah dipastikan (termasuk hasil indikator) ?" : $uncheck . " Apakah sterilisasi alat sudah dipastikan (termasuk hasil dikator) ?<br />";
			$tc17	= ($data->timeout_check17 == "on")? $checked ." Apakah instrumen sudah lengkap atau ada hal yang spesifik ?" : $uncheck . " Apakah instrumen sudah lengkap atau ada hal yang spesifik ?";
			$tc13	= ($data->timeout_check13 == "1")? $checked ." Antibiotik profilaksis dalam 60 menit terakhir<br />" : $uncheck ." Antibiotik profilaksis dalam 60 menit terakhir<br />";
			$tc13a	= ($data->timeout_check13 == "2")? $checked ." Kendali Hiperglikemia<br /><br />" : $uncheck ." Kendali Hiperglikemia<br /><br />";
			$tc14	= ($data->timeout_check14 == "1")? $checked ." Ya" : $uncheck ." Tidak";
			$sc2	= ($data->signout_check2 == "on")? $checked ." Ya" : $uncheck ." Tidak";
			$sc3	= ($data->signout_check3 == "on")? $checked ." Ya" : $uncheck ." Tidak";
			$sc4	= ($data->signout_check4 == "on")? $checked ." Ya" : $uncheck ." Tidak";
			$sc5	= ($data->signout_check5 == "on")? $checked ." Ya" : $uncheck ." Tidak";

			$table2		= array(
				array("Apakah semua anggota tim sudah memperkenalkan dirinya dan apa perannya ?", ($data->timeout_check1 == "on")? $checked ." Sudah Dilakukan" : $uncheck ." Sudah Dilakukan"),
				array(
					"Operator, Anestesi dan Perawat Sirkuler mencek pasien dan memastikan secara verbal :", $tc2.$tc3.$tc4.$tc5.$tc6
				),
				array(
					"Alergi ?",
					($data->timeout_check7 == "on")? $checked ." Ya" : $uncheck . " Tidak"
				),
				array(
					"Apakah mata yang tidak dioperasi sudah dipasang DOP ?",
					($data->timeout_check8 == "on")? $checked ." Ya" : $uncheck . " Tidak dipasang"
				),
				array(
					"Operator memastikan :<br />jenis dan power IOL/Implant lainnya",
					($data->timeout_check9 == "on")? $checked ." Digunakan" : $uncheck ." Tidak Digunakan"
				),
				array(
					"Sebutkan IOL yang dipiih dan target yang diinginkan dan implant lainnya sudah tersedia di kamar operasi",
					($data->timeout_check10 == "on")? $checked ." Digunakan" : $uncheck ." Tidak Digunakan"
				),
				array(
					"Operator memastikan :<br />apakah ada alat khusus yang dibutuhkan",
					($data->timeout_check11 == "on")? $checked ." Ya" : $uncheck ." Tidak"
				),
				array(
					"Apakah ada langkah yang tidak rutin dilakukan untuk diketahui oleh tim OK ?",
					($data->timeout_check12 == "on")? $checked ." Ya" : $uncheck ." Tidak"
				),
				array(
					"Perawat OK :",$tc16.$tc17
				),
				array(
					"Apakah hal dibawah ini dibutuhkan untuk mengurangi resiko infeksi pembedahan ?",
					$tc13.$tc13a.$tc14
				),
				array(
					"Doa Bersama",
					($data->timeout_check15 == "on")? $checked ." Ya/Sudah Dilakukan" : $uncheck . " Ya/Sudah Dilakukan"
				)
			);

			$table3		= array(
				array(
					"Operator/Perawat OK Secara verbal :<br />Apakakah nama prosedur sudah tercatat dalam status ?",
					($data->signout_check1 == "on")? $checked ." Ya" : $uncheck ." Tidak"
				),
				array(
					"Apakah ada spesimen ?<br /><br />". $sc2 ."",
					"Jika ada, apakah spesimen sudah diberi label (Termasuk nama pasien, tanggal dan nama dokter) ?<br /><br />". $sc3
				),
				array(
					"Apakah ada masalah alat dan sudah diidentifikasi untuk dilaporkan ke kepala OK ?",
					$sc4. "<br /><br />Jika ya, nuat laporan terperinci terpisah dan pastikan alat tersebut dikirim untuk diperbaiki."
				),
				array(
					"Operator dan Dr Operator",""
				),
				array(
					"Apakah Ada instruksi khusus sehubungan dengan perawatan pasien diruang pulih dan/atau diruang rawat ?<br /><br />". $sc5,
					"Jika ya :<br /><br />". $data->signout_text1
				),
				array(
					"Hal-hal penting lainnya ?",
					$data->signout_text2
				)
			);

			$jam		= "<table class='table'>
				<tbody>
					<tr>
						<td class='bold centered' style='width:20%'>
							<p>Perawat Verifikasi</p>
							( ". $data->jam1 ." Jam )
						</td>
						<td class='bold centered' style='width:20%'>
							<p>Anestesi</p>
							( ". $data->jam2 ." Jam )
						</td>
						<td class='bold centered' style='width:20%'>
							<p>Perawat Sirkuler</p>
							( ". $data->jam3 ." Jam )
						</td>
						<td class='bold centered' style='width:20%'>
							<p>Perawat Asisten</p>
							( ". $data->jam4 ." Jam )
						</td>
						<td class='bold centered' style='width:20%'>
							<p>Operator</p>
							( ". $data->jam5 ." Jam )
						</td>
					</tr>
				</tbody>
			</table>";

			$notes		= "<table class='table'>
				<tbody>
					<tr>
						<td class='bold'>
							<p>Catatan :</p>
							<ol>
								<li>Untuk Anak-Anak (< 16 Tahun)</li>
								<li>Abaikan kolom anestesi  jika operasi tidak memerlukan pembiusan atau sedasi</li>
								<li> &emsp;(<input type='checkbox' class='checkbox' checked='checked'>)&emsp; Sudah Ditandai</li>
							</ol>
						</td>
					</tr>
				</tbody>
			</table>";

			$chari .= "<style>
				.table {border-collapse:collapse;font-family: Century Gothic;font-size:12px;color:#000;width:100%;margin:auto}
				.bordered {padding:5px;border:1px solid #222}
				.centered {text-align:center;margin:auto}
				.bold {font-weight:bold}
				.subtitle {font-size:16px;margin-bottom:10px}
				.title {font-size:18px;margin-top:10px;margin-bottom:30px}
				.separator {border:115px solid #222;}
			</style>";

			$chari .= "<table class=\"table\">	
				<thead>
					<tr>
						<td rowspan=\"6\" align=\"center\">
							<img src=\"" . base_url() . "assets/img/rskm77.png\"  width=\"100\" height=\"70\" />
						</td>
						<td colspan=\"20\">
							<tr><td style=\"font-size:14px;border-bottom: none;\"><b>$comp_name</b></td></tr>
							<tr><td style=\"font-size:13px;\">$comp_addr</td></tr>
							<tr><td style=\"font-size:13px;\">Telp :$comp_phone </td></tr>
						</td>
					</tr> 
				</thead>
			</table>";

			$chari .= "<hr class=\"separator\">";

			$chari .= "<div class='title centered'>PROSEDUR SAFETY CHECKLIST</div>";

			$chari .= '<table class="table" style="margin-bottom:30px">
				<tbody>
					<tr>
						<td class="bold" style="width:20%">No Rekam medis</td>
						<td style="width:30%">: '. $data->rekmed .'</td>
						<td class="bold" style="width:20%">Tanggal Lahir</td>
						<td style="width:30%">: '. date("Y-m-d", strtotime($pasien->tgllahir)) .'</td>
					</tr>
					<tr>
						<td class="bold" style="width:20%">Nama</td>
						<td style="width:30%">: '. $pasien->namapas .'</td>
						<td class="bold" style="width:20%">Alamat</td>
						<td style="width:30%">: '. $pasien->alamat .'</td>
					</tr>
					<tr>
						<td class="bold" style="width:20%">Jenis kelamin</td>
						<td style="width:30%">: '. $jenis_kelamin .'</td>
					</tr>
				</tbody>
			</table>';

			$chari .= '<table class="table" style="margin-bottom:30px">
				<tbody>
					<tr>
						<td class="bold" style="width:20%">Nama Dokter</td>
						<td style="width:80%">: '. $dokter .'</td>
					</tr>
					<tr>
						<td class="bold" style="width:20%">Tanggal</td>
						<td style="width:80%">: '. $data->tanggal .'</td>
					</tr>
				</tbody>
			</table>';

			$chari .= '<div class="subtitle bold">SIGN IN > SEBELUM INDUKSI ANESTESI</div>
			<table class="table" style="margin-bottom:30px"><tbody>';
			$chari .= '<tr>
				<td class="bordered" style="width:10%"></td>
				<td class="bordered centered bold" style="width:40%">Perawat Verifikasi</td>
				<td class="bordered centered bold" style="width:40%">Anestesi</td>
				<td class="bordered" style="width:10%"></td>
			</tr>';
			foreach($table1 as $tkey){
				$chari .= '<tr>
					<td class="bordered" style="width:10%">'.$tkey[0].'</td>
					<td class="bordered" colspan="2" style="width:80%">'.$tkey[1].'</td>
					<td class="bordered" style="width:10%">'.$tkey[2].'</td>
				</tr>';
			}
			$chari .= '</tbody></table>';

			$chari .= $jam ."<br /><br />". $notes ."<br /><br /><br />";

			$chari .= '<div class="subtitle bold" style="margin-top:0px !important">TIME OUT > SEBELUM PEMBEDAHAN DIMULAI</div>
			<table class="table" style="margin-bottom:30px"><tbody>';
			foreach($table2 as $t2key){
				$chari .= '<tr>
					<td class="bordered">
						'.$t2key[0].'<br /><br />
						'.$t2key[1].'
					</td>
				</tr>';
			}
			$chari .= '</tbody></table>';

			$chari .= $jam ."<br /><br />". $notes ."<br /><br /><br />";

			$chari .= '<div class="subtitle bold" style="margin-top:0px !important">SIGN OUT > SEBELUM TIM MENINGGALKAN KAMAR OPERASI</div>
			<table class="table" style="margin-bottom:30px"><tbody>';
			foreach($table3 as $t3key){
				$chari .= '<tr>
					<td class="bordered">
						'.$t3key[0].'<br /><br />
						'.$t3key[1].'
					</td>
				</tr>';
			}
			$chari .= '</tbody></table>';
			$chari .= $jam ."<br /><br />". $notes ."<br /><br /><br />";


			$this->M_cetak->mpdf('P','A4',$judul, $chari,'PROSEDUR_SAFETY_CHECK_'. $pasien->namapas .'.PDF', 0, 0, 10, 2);
		}
	}

	public function cetakrpo($id){
		$check			= $this->session->userdata("is_logged_in");

		$query_check	= $this->db->query("SELECT * FROM tbl_resumepppo WHERE nojadwal = '$id'");

		if($query_check->num_rows() == null || $check != true){
			redirect("/");
		} else {
			$head		= $this->M_cetak->kop2();
			$judul		= "RESUME PASIEN PULANG POST OPERASI DARI KAMAR BEDAH";
			$data		= $query_check->row();

			$pasien		= $this->db->query("SELECT  b.kelas, a.nojadwal, a.noreg, a.rekmed, a.namapas, b.jkel, b.tgllahir, b.alamat, b.phone 
			FROM tbl_hbedahjadwal AS a 
			LEFT JOIN pasien_daftar AS b ON b.noreg = a.noreg
			WHERE a.nojadwal = '$data->nojadwal'")->row();
			$jenis_kelamin	= ($pasien->jkel == "P")? "Pria" : "Wanita";

			$jenis_operasi	= data_master("tbl_tarifrs", array("kodetarif" => $data->kodetarif))->tindakan;
			$dokter			= data_master("tbl_dokter", array("kodokter" => $data->dokter))->nadokter;

			$comp_name	= $head->namars;
			$comp_addr	= $head->alamat;
			$comp_phone	= $head->phone;
			$comp_image	= base_url().$head->image;

			$checked	= '<input type="checkbox" class="checkbox" checked="checked">';
			$uncheck	= '<input type="checkbox" class="checkbox">';

			$chari  .= "<style>
				.table {border-collapse:collapse;font-family: Century Gothic;font-size:10px;color:#000;width:100%;margin:auto}
				.bordered {padding:5px;border:1px solid #222}
				.centered {text-align:center;margin:auto}
				.bold {font-weight:bold}
				.subtitle {font-size:12px;padding-bottom:15px !important}
				.title {font-size:16px;margin-top:10px;margin-bottom:20px}
				.separator {border:115px solid #222}
			</style>";

			$chari  .= "<table class=\"table\" align=\"center\">	
				<thead>
					<tr>
						<td rowspan=\"6\">
							<img src=\"" . base_url() . "assets/img/rskm77.png\"  width=\"100\" height=\"70\" />
						</td>
						<td colspan=\"20\">
							<tr><td style=\"font-size:14px;border-bottom: none;\"><b>$comp_name</b></td></tr>
							<tr><td style=\"font-size:13px;\">$comp_addr</td></tr>
							<tr><td style=\"font-size:13px;\">Telp :$comp_phone </td></tr>
						</td>
					</tr> 
				</thead>
			</table>";

			$chari  .= "<hr class=\"separator\">";

			$chari  .= "<div class='title centered'>". $judul ."</div>";

			$chari	.= '<table class="table" style="margin-bottom:30px">
			<tbody>
				<tr>
					<td class="bold" style="width:20%">No Rekam medis</td>
					<td style="width:30%">: '. $data->rekmed .'</td>
					<td class="bold" style="width:20%">Tanggal Lahir</td>
					<td style="width:30%">: '. date("Y-m-d", strtotime($pasien->tgllahir)) .'</td>
				</tr>
				<tr>
					<td class="bold" style="width:20%">Nama</td>
					<td style="width:30%">: '. $pasien->namapas .'</td>
					<td class="bold" style="width:20%">Alamat</td>
					<td style="width:30%">: '. $pasien->alamat .'</td>
				</tr>
				<tr>
					<td class="bold" style="width:20%">Jenis kelamin</td>
					<td style="width:30%">: '. $jenis_kelamin .'</td>
				</tr>
			</tbody>
		</table>';

		$chari	.= '<table class="table" style="margin-bottom:30px">
			<tbody>
				<tr>
					<td class="bold" style="width:20%">Jenis Operasi</td>
					<td style="width:80%">: '. $jenis_operasi .'</td>
				</tr>
			</tbody>
		</table>';

		$chari	.= '<table class="table">
			<tbody>
				<tr>
					<td class="subtitle bold">I. KEADAAN UMUM PASIEN SAAT PINDAH</td>
				</tr>
			<tbody>
		</table>
		
		<table class="table" style="margin-bottom:20px">
			<tbody>
				<tr>
					<td class="bold bordered" style="width:30%">Tingkat Kesadaran</td>
					<td class="bordered" style="width:70%">
					'. (($data->tingkatkesadaran == "1")? $checked ." Compos Mentis" : $uncheck ." Compos Mentis") .'&emsp;
					'. (($data->tingkatkesadaran == "2")? $checked ." Apatis" : $uncheck ." Apatis") .'&emsp;
					'. (($data->tingkatkesadaran == "3")? $checked ." ". $data->tingkatkesadaranket : $uncheck ." ". $data->tingkatkesadaranket) .'&emsp;
					</td>
				</tr>
				<tr>
					<td class="bold bordered" style="width:30%">Observasi Terakhir</td>
					<td class="bordered" style="width:70%">
					<p>Tekanan Darah	:	<b>'. $data->tekanandarah .'</b> mmhg</p>
					<p>Nadi				:	<b>'. $data->nadi .'</b> x/menit</p>
					Pernapasan			:	<b>'. $data->pernapasan .'</b> x/menit
					</td>
				</tr>
				<tr>
					<td class="bold bordered" style="width:30%">Skala Nyeri</td>
					<td class="bordered" style="width:70%">
						<center>
							<img src="'. base_url(). '/assets/img/skalanyeri.png" width="80%">
						</center>
						<br />
						<center>
							<label class="checkbox-inline chbw"><input type="checkbox" class="chb" name="skalanyeri" value="1" '. (($data->skalanyeri == "1")? "checked='checked'" : "") .'></label>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
							<label class="checkbox-inline chbw"><input type="checkbox" class="chb" name="skalanyeri" value="2" '. (($data->skalanyeri == "2")? "checked='checked'" : "") .'></label>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
							<label class="checkbox-inline chbw"><input type="checkbox" class="chb" name="skalanyeri" value="3" '. (($data->skalanyeri == "3")? "checked='checked'" : "") .'></label>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
							<label class="checkbox-inline chbw"><input type="checkbox" class="chb" name="skalanyeri" value="4" '. (($data->skalanyeri == "4")? "checked='checked'" : "") .'></label>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
							<label class="checkbox-inline chbw"><input type="checkbox" class="chb" name="skalanyeri" value="5" '. (($data->skalanyeri == "5")? "checked='checked'" : "") .'></label>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
							<label class="checkbox-inline chbw"><input type="checkbox" class="chb" name="skalanyeri" value="6" '. (($data->skalanyeri == "6")? "checked='checked'" : "") .'></label>
						</center>
					</td>
				</tr>
			</tbody>
		</table>
		
		<table class="table">
			<tbody>
				<tr>
					<td class="subtitle bold">II. PENDIDIKAN PASIEN DIRUMAH</td>
				</tr>
			<tbody>
		</table>
		
		<table class="table" style="margin-bottom:30px">
			<tbody>
				<tr>
					<td class="bordered bold" style="width:30%">Aktivitas</td>
					<td class="bordered" style="width:35%">
						'. (($data->aktivitas == "1")? $checked ." Tidak Terbatas" : $uncheck ." Tidak Terbatas") .'
					</td>
					<td class="bordered" style="width:35%">
						'. (($data->aktivitas == "2")? $checked ." Terbatas, Jelaskan : <br /><b><u>". $data->aktivitasket ."</u></b>" : $uncheck ." Terbatas, Jelaskan : <br /><b><u>". $data->aktivitasket ."</u></b>") .'<br />
						'. (($data->aktivitas == "3")? $checked ." Posisi Wajah Menghadap Keatas/Kebawah" : $uncheck ." Posisi Wajah Menghadap Keatas/Kebawah") .'<br />
						'. (($data->aktivitas == "4")? $checked ." Posisi Kepala Miring Kekanan/Kekiri" : $uncheck ." Posisi Kepala Miring Kekanan/Kekiri") .'<br />
						'. (($data->aktivitas == "5")? $checked ." Posisi Kepala 45°/90°" : $uncheck ." Posisi Kepala 45°/90°") .'
					</td>
				</tr>
				<tr>
					<td class="bordered bold" style="width:30%">Pendidikan Kesehatan</td>
					<td class="bordered" style="width:35%">
						'. (($data->pendidikankes == "1")? $checked ." Kompres Dingin" : $uncheck ." Kompres Dingin") .'<br />
						'. (($data->pendidikankes == "2")? $checked ." Ganti Perban" : $uncheck ." Ganti Perban") .'<br />
						'. (($data->pendidikankes == "3")? $checked ." Membersihkan Mata" : $uncheck ." Membersihkan Mata") .'
					</td>
					<td class="bordered" style="width:35%">
						'. (($data->pendidikankes2 == "1")? $checked ." Menggunakan Obat Tetes" : $uncheck ." Menggunakan Obat Tetes") .'<br />
						'. (($data->pendidikankes2 == "2")? $checked ." Menggunakan Obat Salep" : $uncheck ." Menggunakan Obat Salep") .'<br />
						'. (($data->pendidikankes2 == "3")? $checked ." Menggunakan Obat Oral" : $uncheck ." Menggunakan Obat Oral") .'
					</td>
				</tr>
				<tr>
					<td class="bordered bold" style="width:30%">Terapi Diet</td>
					<td class="bordered" style="width:70%" colspan="2">
						'. (($data->terapidiet == "1")? $checked ." Normal" : $uncheck ." Normal") .'<br />
						'. (($data->terapidiet == "2")? $checked ." Lain-Lain, Jelaksan :<br /><b><u>". $data->terapidietket ."</u></b>" : $uncheck ." Lain-Lain, Jelaksan :<br /><b><u>". $data->terapidietket ."</u></b>") .'
					</td>
				</tr>
				<tr>
					<td class="bordered bold" style="width:30%">Instruksi Kontrol</td>
					<td class="bordered" style="width:70%" colspan="2">
						Dokter : <b>'. $dokter .'</b><br />
						Tanggal : <b>'. $data->tanggal .'</b><br />
						No Pendaftaran : <b>'. $data->nopendaftaran .'</b>
					</td>
				</tr>
			</tbody>
		</table>
		
		<table class="table">
			<tbody>
				<tr>
					<td class="subtitle bold">III. EVALUASI PENDIDIKAN KEPADA PASIEN DAN KELUARGA</td>
				</tr>
			<tbody>
		</table>
		
		<table class="table">
			<tbody>
				<tr>
					<td class="bold bordered" style="width:25%">Evaluasi Pemahaman</td>
					<td class="bold bordered" style="width:25%">Hambatan Pada Pasien</td>
					<td class="bold bordered" style="width:25%">Intervensi Hambatan</td>
					<td class="bold bordered" style="width:25%">Pemberi dan Penerima Pendidikan</td>
				</tr>
				<tr>
					<td class="bordered">
						'. (($data->evaluasipemahaman == "1")? $checked ." Mengerti" : $uncheck ." Mengerti") .'<br />
						'. (($data->evaluasipemahaman == "2")? $checked ." Perlu Diulang" : $uncheck ." Perlu Diulang") .'
					</td>
					<td class="bordered">
						'.(($data->hambatanpadapasien == "1")? $checked ." Tidak Ada" : $uncheck ." Tidak Ada").'<br />
						'.(($data->hambatanpadapasien == "2")? $checked ." Penglihatan Terganggu" : $uncheck ." Penglihatan Terganggu").'<br />
						'.(($data->hambatanpadapasien == "3")? $checked ." Pendengaran" : $uncheck ." Pendengaran").'<br />
						'.(($data->hambatanpadapasien == "4")? $checked ." Bahasa" : $uncheck ." Bahasa").'<br />
						'.(($data->hambatanpadapasien == "5")? $checked ." Lainnya:" : $uncheck ." Lainnya:").'<br />
						<b><u>'. $data->hambatanpadapasienket .'</u></b>
					</td>
					<td class="bordered">
						'.(($data->intervensihambatan == "1")? $checked ." Pendidikan Diberikan Kepada Keluarga" : $uncheck ." Pendidikan Diberikan Kepada Keluarga").'<br />
						'.(($data->intervensihambatan == "2")? $checked ." Lisan" : $uncheck ." Lisan").'<br />
						'.(($data->intervensihambatan == "3")? $checked ." Brosur" : $uncheck ." Brosur").'<br />
						'.(($data->intervensihambatan == "4")? $checked ." Demonstrasi" : $uncheck ." Demonstrasi").'<br />
						'.(($data->intervensihambatan == "5")? $checked ." Lainnya :" : $uncheck ." Lainnya :").'<br />
						<b><u>'. $data->intervensihambatanket .'</u></b>
					</td>
					<td class="bordered">
						<p>Tanggal : '. date("Y-m-d") .'</p><br /><br />
						(..............................)<br />
						Perawat<br /><br /><br />
						(..............................)<br />
						Pasien/Keluarga/Perawat
					</td>
				</tr>
			</tbody>
		</table>';

			$this->M_cetak->mpdf('P','A4',$judul, $chari,'RPPPO.PDF', 0, 0, 10, 2);
		}
	}

	public function cetakckp ($id){
		$check			= $this->session->userdata("is_logged_in");

		$query_check	= $this->db->query("SELECT * FROM tbl_catatankeperawatanbedah WHERE nojadwal = '$id'");

		$no1 = $no2 = $no3 = $no4 = $no5 = $no6 = $no7 = 1;

		if($query_check->num_rows() == null || $check != true){
			redirect("/");
		} else {
			$head		= $this->M_cetak->kop2();
			$judul		= "CATATAN KEPERAWATAN BEDAH";
			$data		= $query_check->row();

			$pasien		= $this->db->query("SELECT b.kelas, a.nojadwal, a.noreg, a.rekmed, a.namapas, b.jkel, b.tgllahir, b.alamat, b.phone 
			FROM tbl_hbedahjadwal AS a 
			LEFT JOIN pasien_daftar AS b ON b.noreg = a.noreg
			WHERE a.nojadwal = '$data->nojadwal'")->row();
			$jenis_kelamin	= ($pasien->jkel == "P")? "Pria" : "Wanita";

			/* Definition */
			$checked	= '<input type="checkbox" class="checkbox" checked="checked">';
			$uncheck	= '<input type="checkbox" class="checkbox">';

			$comp_name	= $head->namars;
			$comp_addr	= $head->alamat;
			$comp_phone	= $head->phone;
			$comp_image	= base_url().$head->image;

			$chari .= "<style>
				.table {border-collapse:collapse;font-family: Century Gothic;font-size:10px !important;color:#000;width:100%;margin:auto}
				.bordered {padding:5px;border:1px solid #222}
				.centered {text-align:center;margin:auto}
				.bold {font-weight:bold}
				.subtitle {font-size:12px}
				.title {font-size:16px;margin-top:10px;margin-bottom:20px}
				.separator {border:115px solid #222;}
				p, td, div {font-size:10px}
			</style>";

			$chari .= "<table class=\"table\">	
				<thead>
					<tr>
						<td rowspan=\"6\" align=\"center\">
							<img src=\"" . base_url() . "assets/img/rskm77.png\"  width=\"100\" height=\"70\" />
						</td>
						<td colspan=\"20\">
							<tr><td style=\"font-size:14px;border-bottom: none;\"><b>$comp_name</b></td></tr>
							<tr><td style=\"font-size:13px;\">$comp_addr</td></tr>
							<tr><td style=\"font-size:13px;\">Telp :$comp_phone </td></tr>
						</td>
					</tr> 
				</thead>
			</table>";

			$chari .= '<hr class="separator">

			<div class="title centered">'. $judul .'</div>
			
			<table class="table" style="margin-bottom:30px">
				<tbody>
					<tr>
						<td class="bold" style="width:20%">No Rekam medis</td>
						<td style="width:30%">: '. $data->rekmed .'</td>
						<td class="bold" style="width:20%">Tanggal Lahir</td>
						<td style="width:30%">: '. $pasien->tgllahir .'</td>
					</tr>
					<tr>
						<td class="bold" style="width:20%">Nama</td>
						<td style="width:30%">: '. $pasien->namapas .'</td>
						<td class="bold" style="width:20%">Alamat</td>
						<td style="width:30%">: '. $pasien->alamat .'</td>
					</tr>
					<tr>
						<td class="bold" style="width:20%">Jenis kelamin</td>
						<td style="width:30%">: '. $jenis_kelamin .'</td>
					</tr>
				</tbody>
			</table>
			
			<table class="table" style="margin-bottom:30px">
				<tbody>
					<tr>
						<td class="bold">Tanggal Operasi</td>
						<td>: '. $data->tgloperasi .'</td>
						<td class="bold">Nama Dokter Bedah</td>
						<td>: '. data_master("tbl_dokter", array("kodokter" => $data->droperator))->nadokter .'</td>
					</tr>
					<tr>
						<td class="bold">Nama Dokter Anestesi</td>
						<td>: '.  data_master("tbl_dokter", array("kodokter" => $data->dranestesi))->nadokter .'</td>
						<td class="bold">Jenis Anestesi</td>
						<td>: '. $data->jenis_anestesi .'</td>
					</tr>
				</tbody>
			</table>
			
			<table class="table" style="width:100%;margin:auto">
				<thead>
					<tr>
						<th class="bordered bold centered" colspan="2">Aktivitas</th>
						<th class="bordered bold centered" colspan="2">Pelaksanaan</th>
						<th class="bordered bold centered">Paraf/Nama Petugas</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="5" class="bordered bold"><p>PRE OPERASI</p><br />A. PERSIAPAN DOKUMEN</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no1++ .'</td>
						<td class="bordered">Cek list persiapan pre operasi dai pliklinik</td>
						<td class="bordered">'. (($data->persiapandokumen1 == "1")? $checked ." Ada" : $uncheck ." Ada") .'</td>
						<td class="bordered">'. (($data->persiapandokumen1 == "2")? $checked ." Tidak" : $uncheck ." Tidak") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no1++ .'</td>
						<td class="bordered">Persetujuan tindakan dokter (pemberian informasi)</td>
						<td class="bordered">'. (($data->persiapandokumen2 == "1")? $checked ." Ada" : $uncheck ." Ada") .'</td>
						<td class="bordered">'. (($data->persiapandokumen2 == "2")? $checked ." Tidak" : $uncheck ." Tidak") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no1++ .'</td>
						<td class="bordered" colspan="4">Untuk katarak</td>
					</tr>
					<tr>
						<td class="bordered"></td>
						<td class="bordered">Biometri</td>
						<td class="bordered">'. (($data->persiapandokumen4 == "1")? $checked ." Ada" : $uncheck ." Ada") .'</td>
						<td class="bordered">'. (($data->persiapandokumen4 == "2")? $checked ." Tidak" : $uncheck ." Tidak") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered"></td>
						<td class="bordered">Sel Endothel</td>
						<td class="bordered">'. (($data->persiapandokumen5 == "1")? $checked ." Ada" : $uncheck ." Ada") .'</td>
						<td class="bordered">'. (($data->persiapandokumen5 == "2")? $checked ." Tidak" : $uncheck ." Tidak") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered"></td>
						<td class="bordered">Retinometri</td>
						<td class="bordered">'. (($data->persiapandokumen6 == "1")? $checked ." Ada" : $uncheck ." Ada") .'</td>
						<td class="bordered">'. (($data->persiapandokumen6 == "2")? $checked ." Tidak" : $uncheck ." Tidak") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no1++ .'</td>
						<td class="bordered">Hasil laboratorium</td>
						<td class="bordered">'. (($data->persiapandokumen7 == "1")? $checked ." Ada" : $uncheck ." Ada") .'</td>
						<td class="bordered">'. (($data->persiapandokumen7 == "2")? $checked ." Tidak" : $uncheck ." Tidak") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no1++ .'</td>
						<td class="bordered" colspan="4">Untuk bius umum dan hasil</td>
					</tr>
					<tr>
						<td class="bordered centered"></td>
						<td class="bordered">Rontgen</td>
						<td class="bordered">'. (($data->persiapandokumen9 == "1")? $checked ." Ada" : $uncheck ." Ada") .'</td>
						<td class="bordered">'. (($data->persiapandokumen9 == "2")? $checked ." Tidak" : $uncheck ." Tidak") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered"></td>
						<td class="bordered">EKG</td>
						<td class="bordered">'. (($data->persiapandokumen10 == "1")? $checked ." Ada" : $uncheck ." Ada") .'</td>
						<td class="bordered">'. (($data->persiapandokumen10 == "2")? $checked ." Tidak" : $uncheck ." Tidak") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered"></td>
						<td class="bordered">Hasil konsul dokter anak/penyakit dalam</td>
						<td class="bordered">'. (($data->persiapandokumen11 == "1")? $checked ." Ada" : $uncheck ." Ada") .'</td>
						<td class="bordered">'. (($data->persiapandokumen11 == "2")? $checked ." Tidak" : $uncheck ." Tidak") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no1++ .'</td>
						<td class="bordered">Persetujuan tindakan dokter</td>
						<td class="bordered">'. (($data->persiapandokumen12 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->persiapandokumen12 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td colspan="5" class="bordered bold">B. PERSIAPAN PASIEN</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no2++ .'</td>
						<td class="bordered">Melepaskan perhiasan, cuci muka dan ganti pakaian</td>
						<td class="bordered">'. (($data->persiapanpasien1 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->persiapanpasien1 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no2++ .'</td>
						<td class="bordered">Memasang gelang identitas pasien</td>
						<td class="bordered">'. (($data->persiapanpasien2 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->persiapanpasien2 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no2++ .'</td>
						<td class="bordered">Cek peradangan disekitar mata dan muka</td>
						<td class="bordered">'. (($data->persiapanpasien3 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->persiapanpasien3 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no2++ .'</td>
						<td class="bordered">Tanda mata yang akan dioperasi</td>
						<td class="bordered">'. (($data->persiapanpasien4 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->persiapanpasien4 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no2++ .'</td>
						<td class="bordered">Tanda-tanda vital</td>
						<td class="bordered">'. (($data->persiapanpasien5 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->persiapanpasien5 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered"></td>
						<td class="bordered">Tekanan darah</td>
						<td class="bordered" colspan="2"><b><u>'. $data->tekanandarah1 .'</u></b> mmHg</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered"></td>
						<td class="bordered">Nadi</td>
						<td class="bordered" colspan="2"><b><u>'. $data->nadi1 .'</u></b> x/menit</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered"></td>
						<td class="bordered">Suhu</td>
						<td class="bordered" colspan="2"><b><u>'. $data->suhu1 .'</u></b> °C</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered"></td>
						<td class="bordered">Pernapasan</td>
						<td class="bordered" colspan="2"><b><u>'. $data->pernapasan1 .'</u></b> x/menit</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no2++ .'</td>
						<td class="bordered">Skala nyeri</td>
						<td class="bordered" colspan="2"><b><u>'. $data->skalanyeri1 .'</u></b></td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no2++ .'</td>
						<td class="bordered">Riwayat alergi</td>
						<td class="bordered">'. (($data->persiapanpasien6 == "1")? $checked ." Ada" : $uncheck ." Ada") .'</td>
						<td class="bordered">'. (($data->persiapanpasien6 == "2")? $checked ." Tidak" : $uncheck ." Tidak") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered"></td>
						<td class="bordered">Bila ada alergi, sebutkan :</td>
						<td class="bordered" colspan="2">'. $data->riwayatalergi .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
				</tbody>
			</table>

			<br /><br /><br />
			
			<table class="table" style="width:100%;margin:auto">
				<thead>
					<tr>
						<th class="bordered bold centered" colspan="2">Aktivitas</th>
						<th class="bordered bold centered" colspan="2">Pelaksanaan</th>
						<th class="bordered bold centered">Paraf/Nama Petugas</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="bordered centered">'. $no2++ .'</td>
						<td class="bordered" colspan="4">Riwayat penyakit</td>
					</tr>
					<tr>
						<td class="bordered"></td>
						<td class="bordered">DM</td>
						<td class="bordered">'. (($data->persiapanpasien7 == "1")? $checked ." Ada" : $uncheck ." Ada") .'</td>
						<td class="bordered">'. (($data->persiapanpasien7 == "2")? $checked ." Tidak" : $uncheck ." Tidak") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered"></td>
						<td class="bordered">Hypertensi</td>
						<td class="bordered">'. (($data->persiapanpasien8 == "1")? $checked ." Ada" : $uncheck ." Ada") .'</td>
						<td class="bordered">'. (($data->persiapanpasien8 == "2")? $checked ." Tidak" : $uncheck ." Tidak") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered"></td>
						<td class="bordered">Jantung</td>
						<td class="bordered">'. (($data->persiapanpasien9 == "1")? $checked ." Ada" : $uncheck ." Ada") .'</td>
						<td class="bordered">'. (($data->persiapanpasien9 == "2")? $checked ." Tidak" : $uncheck ." Tidak") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered"></td>
						<td class="bordered">Hepatitis</td>
						<td class="bordered">'. (($data->persiapanpasien10 == "1")? $checked ." Ada" : $uncheck ." Ada") .'</td>
						<td class="bordered">'. (($data->persiapanpasien10 == "2")? $checked ." Tidak" : $uncheck ." Tidak") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered"></td>
						<td class="bordered">Lain-lain : &emsp;<b><u>'. $data->riwayatpenyakit .'</u></b></td>
						<td class="bordered">'. (($data->riwayatpenyakit != "")? $checked ." Ada" : $uncheck ." Ada") .'</td>
						<td class="bordered">'. (($data->riwayatpenyakit == "")? $checked ." Tidak" : $uncheck ." Tidak") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no2++ .'</td>
						<td class="bordered">Tetes pantocain</td>
						<td class="bordered">'. (($data->persiapanpasien11 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->persiapanpasien11 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no2++ .'</td>
						<td class="bordered" colspan="4">Tetes antibiotik setiap 15 menit (untuk operasi intra okular)</td>
					</tr>
					<tr>
						<td class="bordered"></td>
						<td class="bordered">0&#039; diberikan jam :&emsp;<b><u>'. $data->tetesantibiotik01 .'</u></b></td>
						<td class="bordered">'. (($data->persiapanpasien12 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->persiapanpasien12 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered"></td>
						<td class="bordered">15&#039; diberikan jam :&emsp;<b><u>'. $data->tetesantibiotik151 .'</u></b></td>
						<td class="bordered">'. (($data->persiapanpasien13 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->persiapanpasien13 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered"></td>
						<td class="bordered">30&#039; diberikan jam :&emsp;<b><u>'. $data->tetesantibiotik301 .'</u></b></td>
						<td class="bordered">'. (($data->persiapanpasien14 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->persiapanpasien14 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered"></td>
						<td class="bordered">45&#039; diberikan jam :&emsp;<b><u>'. $data->tetesantibiotik451 .'</u></b></td>
						<td class="bordered">'. (($data->persiapanpasien15 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->persiapanpasien15 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered"></td>
						<td class="bordered">60&#039; diberikan jam :&emsp;<b><u>'. $data->tetesantibiotik601 .'</u></b></td>
						<td class="bordered">'. (($data->persiapanpasien16 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->persiapanpasien16 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no2++ .'</td>
						<td class="bordered">Cukur bulu mata (untuk operasi retina & karatoplasty)</td>
						<td class="bordered">'. (($data->persiapanpasien17 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->persiapanpasien17 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no2++ .'</td>
						<td class="bordered">Tetes mydriacyl 1% ke 1 " diberikan jam <b><u>'. $data->mydriacyl11 .'</u></b></td>
						<td class="bordered">'. (($data->persiapanpasien18 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->persiapanpasien18 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no2++ .'</td>
						<td class="bordered">Tetes mydriacyl 1% ke 2 " diberikan jam <b><u>'. $data->mydriacyl21 .'</u></b></td>
						<td class="bordered">'. (($data->persiapanpasien19 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->persiapanpasien19 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no2++ .'</td>
						<td class="bordered">Tetes mydriacyl 1% ke 3 " diberikan jam <b><u>'. $data->mydriacyl31 .'</u></b></td>
						<td class="bordered">'. (($data->persiapanpasien20 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->persiapanpasien20 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no2++ .'</td>
						<td class="bordered">Tetes Xyfocaine 2%</td>
						<td class="bordered">'. (($data->persiapanpasien21 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->persiapanpasien21 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no2++ .'</td>
						<td class="bordered">Desinfeksi</td>
						<td class="bordered">'. (($data->persiapanpasien22 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->persiapanpasien22 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no2++ .'</td>
						<td class="bordered">Instruksi khusus :&emsp;<b><u>'. $data->persiapanpasien_intruksi .'</u></b></td>
						<td class="bordered">'. (($data->persiapanpasien23 == "1")? $checked ." ada" : $uncheck ." ada") .'</td>
						<td class="bordered">'. (($data->persiapanpasien23 == "2")? $checked ." Tidak" : $uncheck ." Tidak") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td colspan="5" class="bordered bold">C. PERSIAPAN TAMBAHAN UNTUK ANESTESI LOKAL</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no3++ .'</td>
						<td class="bordered">Suntik anestesi oleh dokter <b><u>'. data_master("tbl_dokter", array("kodokter" => $data->dranestesi1))->nadokter .'<u></b> jam <b><u>'. $data->jamsuntikanestesi .'</u></b></td>
						<td class="bordered">'. (($data->anestesilokal1 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->anestesilokal1 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no3++ .'</td>
						<td class="bordered">Check hasil suntikan anestesi lokal</td>
						<td class="bordered">'. (($data->anestesilokal2 == "1")? $checked ." Baik" : $uncheck ." Baik") .'</td>
						<td class="bordered">'. (($data->anestesilokal2 == "2")? $checked ." Tidak" : $uncheck ." Tidak") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td colspan="5" class="bordered bold">D. PERSIAPAN TAMBAHAN UNTUK ANESTESI UMUM</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no4++ .'</td>
						<td class="bordered">Timbangan berat badan :&emsp;<b><u>'. $data->berat_badan .'</u></b> kg</td>
						<td class="bordered">'. (($data->anestesiumum1 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->anestesiumum1 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no4++ .'</td>
						<td class="bordered">Gigi palsu</td>
						<td class="bordered">'. (($data->anestesiumum2 == "1")? $checked ." Ada" : $uncheck ." Ada") .'</td>
						<td class="bordered">'. (($data->anestesiumum2 == "2")? $checked ." Tidak" : $uncheck ." Tidak") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered"></td>
						<td class="bordered">Bila ada lepas</td>
						<td class="bordered">'. (($data->anestesiumum3 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->anestesiumum3 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no4++ .'</td>
						<td class="bordered">Melepaskan pakaian dalam atas</td>
						<td class="bordered">'. (($data->anestesiumum4 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->anestesiumum4 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no4++ .'</td>
						<td class="bordered">Puasa mulai jam</td>
						<td class="bordered" colspan="2"><b><u>'. $data->jampuasa .'</u></b></td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no4++ .'</td>
						<td class="bordered">Cat kuku</td>
						<td class="bordered">'. (($data->anestesiumum5 == "1")? $checked ." Ada" : $uncheck ." Ada") .'</td>
						<td class="bordered">'. (($data->anestesiumum5 == "2")? $checked ." Tidak" : $uncheck ." Tidak") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered"></td>
						<td class="bordered">Bila ada bersihkan</td>
						<td class="bordered">'. (($data->anestesiumum6 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->anestesiumum6 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td colspan="5" class="bordered bold">E. PERSIAPAN TAMBAHAN BILA MENGGUNAKAN IOL</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no5++ .'</td>
						<td class="bordered">Dokter menentukan jenis power IOL</td>
						<td class="bordered">'. (($data->iol1 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->iol1 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no5++ .'</td>
						<td class="bordered">Dokter memeastikan kembali sisi mata yang akan dioperasi</td>
						<td class="bordered">'. (($data->iol2 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->iol2 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td colspan="5" class="bordered bold">F. INTRA OPERASI</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no6++ .'</td>
						<td class="bordered">Menyambut pasien</td>
						<td class="bordered">'. (($data->intraoperasi1 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->intraoperasi1 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no6++ .'</td>
						<td class="bordered">Menganjurkan pasien untuk rileks dan mengikuti saran dokter/perawat</td>
						<td class="bordered">'. (($data->intraoperasi2 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->intraoperasi2 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no6++ .'</td>
						<td class="bordered">Monitor dan oksigen terpasang</td>
						<td class="bordered">'. (($data->intraoperasi3 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->intraoperasi3 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no6++ .'</td>
						<td class="bordered">Melakukan sign in</td>
						<td class="bordered">'. (($data->intraoperasi4 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->intraoperasi4 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no6++ .'</td>
						<td class="bordered">Tetes pantocain</td>
						<td class="bordered">'. (($data->intraoperasi6 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->intraoperasi6 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no6++ .'</td>
						<td class="bordered">Tetes antibiotik (intra okular)</td>
						<td class="bordered">'. (($data->intraoperasi7 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->intraoperasi7 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no6++ .'</td>
						<td class="bordered">Disenfeksi</td>
						<td class="bordered">'. (($data->intraoperasi8 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->intraoperasi8 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no6++ .'</td>
						<td class="bordered">Untuk endothalmitis kultur sudah disiapkan</td>
						<td class="bordered">'. (($data->intraoperasi10 == "1")? $checked ." Ya" : $uncheck ." Ya") .'</td>
						<td class="bordered">'. (($data->intraoperasi10 == "2")? $checked ." Tidak" : $uncheck ." Tidak") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no6++ .'</td>
						<td class="bordered">Melakukan time-out</td>
						<td class="bordered">'. (($data->intraoperasi12 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->intraoperasi12 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no6++ .'</td>
						<td class="bordered">Mengukur tanda-tanda vital</td>
						<td class="bordered">'. (($data->intraoperasi13 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->intraoperasi13 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered"></td>
						<td class="bordered">Nadi</td>
						<td class="bordered" colspan="2"><b><u>'. $data->nadi2 .'</u></b> x/menit</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered"></td>
						<td class="bordered">SpO2</td>
						<td class="bordered" colspan="2"><b><u>'. $data->spo2 .' %</u></b></td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no6++ .'</td>
						<td class="bordered">Saat operasi luka dijahit apabila</td>
						<td class="bordered">'. (($data->intraoperasi14 == "1")? $checked ." Ya" : $uncheck ." Ya") .'</td>
						<td class="bordered">'. (($data->intraoperasi14 == "2")? $checked ." Tidak" : $uncheck ." Tidak") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered"></td>
						<td class="bordered" colspan="4">One eye</td>
					</tr>
					<tr>
						<td class="bordered centered"></td>
						<td class="bordered" colspan="4">Grade IV-V</td>
					</tr>
					<tr>
						<td class="bordered centered"></td>
						<td class="bordered" colspan="4">Ruptur capsul</td>
					</tr>
					<tr>
						<td class="bordered centered"></td>
						<td class="bordered" colspan="4">Umur > 80 tahun</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no6++ .'</td>
						<td class="bordered">Melakukan sign out</td>
						<td class="bordered">'. (($data->intraoperasi15 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->intraoperasi15 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no6++ .'</td>
						<td class="bordered">Menghitung jumlah instrumen/kassa/jarum</td>
						<td class="bordered">'. (($data->intraoperasi16 == "1")? $checked ." Lengkap" : $uncheck ." Lengkap") .'</td>
						<td class="bordered">'. (($data->intraoperasi16 == "2")? $checked ." Tidak" : $uncheck ." Tidak") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no6++ .'</td>
						<td class="bordered">Dokter menginformasikan jam kontrol kepada perawat atau pasien</td>
						<td class="bordered">'. (($data->intraoperasi17 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->intraoperasi17 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td colspan="5" class="bordered bold">G. POST OPERASI</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no7++ .'</td>
						<td class="bordered">Menganjurkan pasien beristirahat dan memberikan posisi nyaman</td>
						<td class="bordered">'. (($data->postoperasi1 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->postoperasi1 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no7++ .'</td>
						<td class="bordered">Memberikan minum/snack</td>
						<td class="bordered">'. (($data->postoperasi2 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->postoperasi2 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no7++ .'</td>
						<td class="bordered">Tanda-tanda vital</td>
						<td class="bordered">'. (($data->postoperasi4 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->postoperasi4 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered"></td>
						<td class="bordered">Tekanan darah</td>
						<td class="bordered" colspan="2"><b><u>'. $data->tekanandarah2 .'</u></b> mmHg</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered"></td>
						<td class="bordered">Nadi</td>
						<td class="bordered" colspan="2"><b><u>'. $data->nadi3 .'</u></b> x/menit</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered"></td>
						<td class="bordered">Suhu</td>
						<td class="bordered" colspan="2"><b><u>'. $data->suhu2 .'</u></b> °C</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered"></td>
						<td class="bordered">Pernafasan</td>
						<td class="bordered" colspan="2"><b><u>'. $data->pernapasan2 .'</u></b> x/menit</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no7++ .'</td>
						<td class="bordered">Skala nyeri</td>
						<td class="bordered" colspan="2"><b><u>'. $data->skalanyeri2 .'</u></b></td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no7++ .'</td>
						<td class="bordered" colspan="4">Tetes antibiotik setiap 15 menit (untuk operasi intra okular)</td>
					</tr>
					<tr>
						<td class="bordered"></td>
						<td class="bordered">0&#039; diberikan jam :&emsp;<b><u>'. $data->tetesantibiotik02 .'</u></b></td>
						<td class="bordered">'. (($data->postoperasi5 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->postoperasi5 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered"></td>
						<td class="bordered">15&#039; diberikan jam :&emsp;<b><u>'. $data->tetesantibiotik152 .'</u></b></td>
						<td class="bordered">'. (($data->postoperasi6 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->postoperasi6 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered"></td>
						<td class="bordered">30&#039; diberikan jam :&emsp;<b><u>'. $data->tetesantibiotik302 .'</u></b></td>
						<td class="bordered">'. (($data->postoperasi7 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->postoperasi7 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered"></td>
						<td class="bordered">45&#039; diberikan jam :&emsp;<b><u>'. $data->tetesantibiotik452 .'</u></b></td>
						<td class="bordered">'. (($data->postoperasi8 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->postoperasi8 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered"></td>
						<td class="bordered">60&#039; diberikan jam :&emsp;<b><u>'. $data->tetesantibiotik602 .'</u></b></td>
						<td class="bordered">'. (($data->postoperasi9 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->postoperasi9 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no7++ .'</td>
						<td class="bordered">Keluarga pasien diberitahu bila operasi sudah selesai</td>
						<td class="bordered">'. (($data->postoperasi10 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->postoperasi10 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no7++ .'</td>
						<td class="bordered">Melepas tanda mata yang dioperasi</td>
						<td class="bordered">'. (($data->postoperasi11 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->postoperasi11 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered">'. $no7++ .'</td>
						<td class="bordered">Memberikan penyuluhan dan perawatan dirumah, posisi tidur, obat, kontrol, berikutnya dan hal-hal yang harus diwaspadai</td>
						<td class="bordered">'. (($data->postoperasi12 == "1")? $checked ." Sudah" : $uncheck ." Sudah") .'</td>
						<td class="bordered">'. (($data->postoperasi12 == "2")? $checked ." Belum" : $uncheck ." Belum") .'</td>
						<td class="bordered centered">'. $this->session->userdata('username') .'</td>
					</tr>
					<tr>
						<td class="bordered centered"></td>
						<td class="bordered" colspan="4">Catatan :<br /><b><u>'. $data->catatan .'</u></b></td>
					</tr>
				</tbody>
			</table>';

			$this->M_cetak->mpdf('P','A4',$judul, $chari,'CATATAN_KEPERAWATAN_BEDAH_'. $pasien->namapas .'.PDF', 0, 0, 9, 2);
		}
	}

	public function cetakcs($id){
		$check			= $this->session->userdata("is_logged_in");

		$query_check	= $this->db->query("SELECT * FROM tbl_cataractsurgery WHERE nojadwal = '$id'");

		if($query_check->num_rows() == null || $check != true){
			redirect("/");
		} else {
			$head		= $this->M_cetak->kop2();
			$judul		= "CATARACT SURGERY";
			$data		= $query_check->row();

			$pasien		= $this->db->query("SELECT b.kelas, a.nojadwal, a.noreg, a.rekmed, a.namapas, b.jkel, b.tgllahir, b.alamat, b.phone 
			FROM tbl_hbedahjadwal AS a 
			LEFT JOIN pasien_daftar AS b ON b.noreg = a.noreg
			WHERE a.nojadwal = '$data->nojadwal'")->row();
			$jenis_kelamin	= ($pasien->jkel == "P")? "Pria" : "Wanita";

			/* Definition */
			$checked	= '<input type="checkbox" class="checkbox" checked="checked">';
			$uncheck	= '<input type="checkbox" class="checkbox">';

			$comp_name	= $head->namars;
			$comp_addr	= $head->alamat;
			$comp_phone	= $head->phone;
			$comp_image	= base_url().$head->image;

			$chari .= "<style>
				.table {border-collapse:collapse;font-family: Century Gothic;font-size:11px;color:#000;width:100%;margin:auto}
				.bordered {padding:5px;border:1px solid #222}
				.centered {text-align:center;margin:auto}
				.bold {font-weight:bold}
				.subtitle {font-size:12px}
				.title {font-size:16px;margin-top:10px;margin-bottom:20px}
				.separator {border:115px solid #222;}
			</style>";

			$chari .= "<table class=\"table\">	
				<thead>
					<tr>
						<td rowspan=\"6\" align=\"center\">
							<img src=\"" . base_url() . "assets/img/rskm77.png\"  width=\"100\" height=\"70\" />
						</td>
						<td colspan=\"20\">
							<tr><td style=\"font-size:14px;border-bottom: none;\"><b>$comp_name</b></td></tr>
							<tr><td style=\"font-size:13px;\">$comp_addr</td></tr>
							<tr><td style=\"font-size:13px;\">Telp :$comp_phone </td></tr>
						</td>
					</tr> 
				</thead>
			</table>";

			$chari .= '<hr class="separator">

			<div class="title centered">'. $judul .'</div>
			
			<table class="table" style="margin-bottom:30px">
				<tbody>
					<tr>
						<td class="bold" style="width:20%">No Rekam medis</td>
						<td style="width:30%">: '. $data->rekmed .'</td>
						<td class="bold" style="width:20%">Tanggal Lahir</td>
						<td style="width:30%">: '. $pasien->tgllahir .'</td>
					</tr>
					<tr>
						<td class="bold" style="width:20%">Nama</td>
						<td style="width:30%">: '. $pasien->namapas .'</td>
						<td class="bold" style="width:20%">Alamat</td>
						<td style="width:30%">: '. $pasien->alamat .'</td>
					</tr>
					<tr>
						<td class="bold" style="width:20%">Jenis kelamin</td>
						<td style="width:30%">: '. $jenis_kelamin .'</td>
					</tr>
				</tbody>
			</table>
			
			<table class="table" style="width:100%;margin:0 0 15px 0">
				<tbody>
					<tr>
						<td colspan="2" class="bold">Cataract Surgery</td>
						<td colspan="2">: 
							'. (($data->cataract_surgery == "OD")? $checked ." OD" : $uncheck ." OD") .'&emsp;
							'. (($data->cataract_surgery == "OS")? $checked ." OS" : $uncheck ." OS") .'
						</td>
					</tr>
					<tr>
						<td colspan="2" class="bold">Diabetics</td>
						<td colspan="2">: 
							'. (($data->diabetics == "1")? $checked ." Yes" : $uncheck ." Yes") .'&emsp;
							'. (($data->diabetics == "2")? $checked ." No" : $uncheck ." No") .'
						</td>
					</tr>
					<tr>
						<td colspan="2" class="bold">Pre-Operative Grade of Cataract Notes</td>
						<td colspan="2">: '. $data->notes .'
						</td>
					</tr>
				</tbody>
			</table>

			<table class="table" style="width:100%;margin:0 0 15px 0">
				<tbody>
					<tr>
						<td class="bold">UCVA</td>
						<td>: '. $data->ucva .'</td>
						<td class="bold">BCVA</td>
						<td>: '. $data->bcva .'</td>
					</tr>
					<tr>
						<td class="bold">AXL</td>
						<td>: '. $data->axl .'</td>
						<td class="bold">Retinometri</td>
						<td>: '. $data->retinometri .'</td>
					</tr>
					<tr>
						<td class="bold">ACD</td>
						<td>: '. $data->acd .'</td>
						<td class="bold">Dencity Cell Count</td>
						<td></td>
					</tr>
					<tr>
						<td class="bold">LT</td>
						<td>: '. $data->lt .'</td>
						<td class="bold">K1</td>
						<td>: '. $data->k1 .'</td>
					</tr>
					<tr>
						<td class="bold">Formula</td>
						<td>: '. $data->formula .'</td>
						<td class="bold">K2</td>
						<td>: '. $data->k2 .'</td>
					</tr>
					<tr>
						<td colspan="2" class="bold">Target Emmetropia with IOL Power ⟶ A Constant</td>
						<td colspan="2">: '. $data->iolpowerconstant .'</td>
					</tr>
				</tbody>
			</table>
			<table class="table" style="width:100%;margin:0 0 15px 0">
				<tbody>
					<tr>
						<td class="bold">Intra-Operative Date</td>
						<td>: '. $data->intraoperativedate .'</td>
						<td class="bold">Time</td>
						<td>: '. $data->intraoperativetime .'</td>
					</tr>
					<tr>
						<td class="bold">OP Room</td>
						<td>: '. $data->oproom .'</td>
						<td class="bold">Type of Surgery</td>
						<td>: '. $data->typeofsurgery .'</td>
					</tr>
					<tr>
						<td class="bold">Scrub</td>
						<td>: '. $data->scrub .'</td>
						<td class="bold">Anesthesiologist</td>
						<td>: '. $data->anesthesiologist .'</td>
					</tr>
					<tr>
						<td class="bold">Circulator</td>
						<td>: '. $data->circulator .'</td>
					</tr>
				</tbody>
			</table>

			<table class="table" style="width:100%;margin:0 0 15px 0">
				<tbody>
					<tr>
						<td class="bold">Anesthesia</td>
						<td>: 
							'. (($data->anesthesia == "Topical")? $checked ." Topical" : $uncheck ." Topical") .'&emsp;
							'. (($data->anesthesia == "Sub-con/Tenon")? $checked ." Sub-con/Tenon" : $uncheck ." Sub-con/Tenon") .'&emsp;
							'. (($data->anesthesia == "Blok")? $checked ." Blok" : $uncheck ." Blok") .'&emsp;
							'. (($data->anesthesia == "General")? $checked ." General" : $uncheck ." General") .'
						</td>
					</tr>
					<tr>
						<td class="bold">Approach</td>
						<td>: 
							'. (($data->approach == "Temporal")? $checked ." Temporal" : $uncheck ." Temporal") .'&emsp;
							'. (($data->approach == "Superior")? $checked ." Superior" : $uncheck ." Superior") .'&emsp;
							'. (($data->approach == "Scleral Tunnel")? $checked ." Scleral Tunnel" : $uncheck ." Scleral Tunnel") .'&emsp;
							'. (($data->approach == "Limbal")? $checked ." Limbal" : $uncheck ." Limbal") .'
						</td>
					</tr>
					<tr>
						<td class="bold">Intra COA</td>
						<td>: 
							'. (($data->intracoa == "Xylocard")? $checked ." Xylocard" : $uncheck ." Xylocard") .'&emsp;
							'. (($data->intracoa == "Ephinephrine")? $checked ." Ephinephrine" : $uncheck ." Ephinephrine") .'&emsp;
							'. (($data->intracoa == "Trypan Blue")? $checked ." Trypan Blue" : $uncheck ." Trypan Blue") .'
						</td>
					</tr>
					<tr>
						<td class="bold">Capsulatomy (CCC)</td>
						<td>: 
							'. (($data->capsulatomy == "Complete")? $checked ." Complete" : $uncheck ." Complete") .'&emsp;
							'. (($data->capsulatomy == "Incomplete")? $checked ." Incomplete" : $uncheck ." Incomplete") .'&emsp;
							'. (($data->capsulatomy == "Can Opener")? $checked ." Can Opener" : $uncheck ." Can Opener") .'
						</td>
					</tr>
					<tr>
						<td class="bold">Hydrodissection</td>
						<td>: 
							'. (($data->hydrodissection == "1")? $checked ." Yes" : $uncheck ." Yes") .'&emsp;
							'. (($data->hydrodissection == "2")? $checked ." No" : $uncheck ." No") .'
						</td>
					</tr>
					<tr>
						<td class="bold">Nucleus Management</td>
						<td>: 
							'. (($data->nucleus_management == "Phaco")? $checked ." Phaco" : $uncheck ." Phaco") .'&emsp;
							'. (($data->nucleus_management == "SICS")? $checked ." SICS" : $uncheck ." SICS") .'&emsp;
							'. (($data->nucleus_management == "Manual ECCE")? $checked ." Manual ECCE" : $uncheck ." Manual ECCE") .'&emsp;
							'. (($data->nucleus_management == "ECCE")? $checked ." ECCE" : $uncheck ." ECCE") .'&emsp;
						</td>
					</tr>
					<tr>
						<td class="bold">Pacho Technique</td>
						<td>: 
							'. (($data->pacho_technique == "Stop & Chop")? $checked ." Stop & Chop" : $uncheck ." Stop & Chop") .'&emsp;
							'. (($data->pacho_technique == "Horizontal Chop")? $checked ." Horizontal Chop" : $uncheck ." Horizontal Chop") .'&emsp;
							'. (($data->pacho_technique == "Vertical Chop")? $checked ." Vertical Chop" : $uncheck ." Vertical Chop") .'&emsp;
							'. (($data->pacho_technique == "D & C")? $checked ." D & C" : $uncheck ." D & C") .'
						</td>
					</tr>
					<tr>
						<td class="bold">IOL Placement</td>
						<td>: 
							'. (($data->iol_placement == "Bag")? $checked ." Bag" : $uncheck ." Bag") .'&emsp;
							'. (($data->iol_placement == "Sulcus")? $checked ." Sulcus" : $uncheck ." Sulcus") .'&emsp;
							'. (($data->iol_placement == "Aphakia")? $checked ." Aphakia" : $uncheck ." Aphakia") .'&emsp;
							'. (($data->iol_placement == "Other")? $checked ." Other" : $uncheck ." Other") .'
						</td>
					</tr>
					<tr>
						<td class="bold">Stitch</td>
						<td>: 
							'. (($data->stitch == "2")? $checked ." No" : $uncheck ." No") .'&emsp;
							'. (($data->stitch == "1")? $checked ." Yes (1-2-3-4-5-6-)" : $uncheck ." Yes (1-2-3-4-5-6-)") .'
						</td>
					</tr>
				</tbody>
			</table>

			<table class="table" style="width:100%;margin:0 0 15px 0">
				<tbody>
					<tr>
						<td class="bold" style="width:26%">Final Incision</td>
						<td>:
							'. $data->final_incision .' (mm)
						</td>
					</tr>
					<tr>
						<td class="bold" style="width:26%">Irigating Solution</td>
						<td>:
							'. $data->irigating_solution .'
						</td>
					</tr>
					<tr>
						<td class="bold" style="width:26%">Viscoelastic</td>
						<td>:
							'. $data->viscoelastic .'
						</td>
					</tr>
					<tr>
						<td class="bold" style="width:26%">Type of IOL</td>
						<td>:
							'. $data->typeofiol .'
						</td>
					</tr>
					<tr>
						<td class="bold" style="width:26%">Pachoe Machine</td>
						<td>:
							'. $data->pacho_machine .'
						</td>
					</tr>
					<tr>
						<td class="bold" style="width:26%">Pacho Time</td>
						<td>:
							'. $data->pacho_time .'
						</td>
					</tr>
					<tr>
						<td class="bold" style="width:26%">EPT</td>
						<td>:
							'. $data->ept .'
						</td>
					</tr>
				</tbody>
			</table>
			
			<table class="table" style="width:100%;margin:0 0 30px 0">
				<tbody>
					<tr>
						<td style="width:50%">
							<p><b>Complications :</b></p><br />
							'. (($data->complication == "1")? $checked ." Yes" : $uncheck ." Yes") .'&emsp;
							'. (($data->complication == "2")? $checked ." No" : $uncheck ." No") .'
						</td>
						<td class="bordered centered" style="padding:30px">IOL LABEL</td>
					</tr>
				</tbody>
			</table>
			
			<table class="table">
				<tbody>
					<tr>
						<td class="bold" style="width:70%" colspan="2">'. (($data->posterion_capsule_rupture == "on")? $checked ." Posterior Capsule Rupture" : $uncheck ." Posterior Capsule Rupture") .'</td>
					</tr>
					<tr>
						<td class="bold" style="width:70%">'. (($data->vitreous_prolapse == "on")? $checked ." Vitreous Prolapse" : $uncheck ." Vitreous Prolapse") .'</td>
						<td>(during phaco/cortex aspiration/IOL implantation)</td>
					</tr>
					<tr>
						<td class="bold" style="width:70%">'. (($data->vitrectomy == "on")? $checked ." Vitrectomy" : $uncheck ." Vitrectomy") .'</td>
						<td>(manual/machine)</td>
					</tr>
					<tr>
						<td class="bold" style="width:70%">'. (($data->retained_lens_material == "on")? $checked ." Retained Lens Material" : $uncheck ." Retained Lens Material") .'</td>
						<td>(whole/more than half/less then half)</td>
					</tr>
					<tr>
						<td class="bold" style="width:70%" colspan="2">'. (($data->cortex_left == "on")? $checked ." Cortex Left" : $uncheck ." Cortex Left") .'</td>
					</tr>
				</tbody>
			</table>
			<br /><br /><br />
			<table class="table" style="margin-bottom:20px">
				<tbody>
					<tr>
						<td class="bold">Post-Operative</td>
					</tr>
				</tbody>
			</table>
			
			<table class="table" style="width:100%;margin:auto">
				<tbody>
					<tr>
						<td class="bold">Post-Operative Diagnose</td>
						<td>: '. $data->post_operative_diagnose .'</td>
					</tr>
					<tr>
						<td class="bold">Kode ICD</td>
						<td>: '. $data->kodeicd .'</td>
					</tr>
					<tr>
						<td class="bold">Theraphy</td>
						<td>: '. $data->therapy .'</td>
					</tr>
				</tbody>
			</table>
			
			<br />
			
			<p class="subtitle"><b>Instructions</b></p>
			
			<ol style="font-size:11px">
				<li>Hindari dari air dan sabun selama <b><u>'. $data->instruction_1 .'</u></b> hari</li>
				<li>Jangan menggosok atau menekan mata</li>
				<li>Gunakan pelindung mata (dop) selama 1 minggu atau kacamata pelindung selama 1 bulan pada waktu tidur, termasuk tidur siang</li>
				<li>Cuci tangan sebelum meneteskan obat mata</li>
				<li>Hindari daerah berdebu dan hewan peliharaan selama 2 minggu</li>
				<li>Kontrol hari : <b><u>'. $data->instruction_2 .'</u></b></li>
				<li>Tanggal : <b><u>'. $data->instruction_3 .'</u></b></li>
				<li>Tempat : <b><u>'. $data->instruction_4 .'</u></b></li>
			</ol>
			
			<br />
			
			<p style="font-size:11px">Speciment&emsp;&emsp; : '. (($data->speciment == "1")? $checked ." Yes" : $uncheck ." Yes") .'&emsp;'. (($data->speciment == "2")? $checked ." No" : $uncheck ." No") .'</p>
			<p style="font-size:11px">Jika Ya&emsp;&emsp; : <b><u>'. $data->speciment_ket .'</u></b></p>
			
			<br /><br />

			<div style="font-size:11px;text-align:right">
				'. date("d-m-Y H:i:s") .'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
				Tanda Tangan Dokter&emsp;&nbsp;
				<br /><br /><br /><br /><br /><br />
				(........................................)
			</div>
			';

			$this->M_cetak->mpdf('P','A4',$judul, $chari,'CATARACT_USERGERY_'. $pasien->namapas .'.PDF', 0, 0, 9, 2);
		}
	}

	public function cetaklo($id){
		$check			= $this->session->userdata("is_logged_in");

		$query_check	= $this->db->query("SELECT * FROM tbl_laporanoperasi WHERE nojadwal = '$id'");

		if($query_check->num_rows() == null || $check != true){
			redirect("/");
		} else {
			$head		= $this->M_cetak->kop2();
			$judul		= "LAPORAN OPERASI";
			$data		= $query_check->row();

			$pasien		= $this->db->query("SELECT b.kelas, a.nojadwal, a.noreg, a.rekmed, a.namapas, b.jkel, b.tgllahir, b.alamat, b.phone 
			FROM tbl_hbedahjadwal AS a 
			LEFT JOIN pasien_daftar AS b ON b.noreg = a.noreg
			WHERE a.nojadwal = '$data->nojadwal'")->row();
			$jenis_kelamin	= ($pasien->jkel == "P")? "Pria" : "Wanita";

			/* Definition */
			$checked	= '<input type="checkbox" class="checkbox" checked="checked">';
			$uncheck	= '<input type="checkbox" class="checkbox">';

			$dokterop		= data_master("tbl_dokter", array("kodokter" => $data->droperator))->nadokter;
			$dokteran		= data_master("tbl_dokter", array("kodokter" => $data->dranestesi))->nadokter;
			$dokteropas1	= data_master("tbl_dokter", array("kodokter" => $data->perawat_asisten1))->nadokter;
			$dokteropas2	= data_master("tbl_dokter", array("kodokter" => $data->perawat_asisten2))->nadokter;
			$dokteranas		= data_master("tbl_dokter", array("kodokter" => $data->perawat_anestesi))->nadokter;
			
			$comp_name	= $head->namars;
			$comp_addr	= $head->alamat;
			$comp_phone	= $head->phone;
			$comp_image	= base_url().$head->image;

			$birth  = ($pasien->tgllahir == "")? date("Y") : $pasien->tgllahir;
			$today  = date("Y");
			$age    = $today - $birth;

			$msics				= ($data->msics == "on")? $checked : $uncheck;
			$pachoemulsifikasi	= ($data->pachoemulsifikasi == "on")? $checked : $uncheck;
			$pterigium			= ($data->pterigium == "on")? $checked : $uncheck;
			$trabeculektomy		= ($data->trabeculektomy == "on")? $checked : $uncheck;
			$eviscerasi			= ($data->eviscerasi == "on")? $checked : $uncheck;
			$resimco			= ($data->resimco == "on")? $checked : $uncheck;
			$jahitan			= ($data->jahitan == "Ya")? $checked ." Ya": $uncheck ." Tidak";
			
			/* Content */
			$chari .= "<style>
				.table {border-collapse:collapse;font-family: Century Gothic;font-size:9px;color:#000;width:100%;margin:auto}
				.bordered {padding:5px;border:1px solid #222}
				.centered {text-align:center;margin:auto}
				.bold {font-weight:bold}
				.subtitle {font-size:12px}
				.title {font-size:16px;margin-top:10px;margin-bottom:20px}
				.separator {border:115px solid #222;}
			</style>";

			$chari .= "<table class=\"table\">	
				<thead>
					<tr>
						<td rowspan=\"6\" align=\"center\">
							<img src=\"" . base_url() . "assets/img/rskm77.png\"  width=\"100\" height=\"70\" />
						</td>
						<td colspan=\"20\">
							<tr><td style=\"font-size:14px;border-bottom: none;\"><b>$comp_name</b></td></tr>
							<tr><td style=\"font-size:13px;\">$comp_addr</td></tr>
							<tr><td style=\"font-size:13px;\">Telp :$comp_phone </td></tr>
						</td>
					</tr> 
				</thead>
			</table>";

			$chari .= "<hr class=\"separator\">";

			$chari .= "<div class='title centered'>". $judul ."</div>";

			$chari .= '<table class="table" style="margin-bottom:30px">
				<tbody>
					<tr>
						<td class="bold" style="width:20%">No Rekam medis</td>
						<td style="width:30%">: '. $data->rekmed .'</td>
						<td class="bold" style="width:20%">Umur</td>
						<td style="width:30%">: '. $age .' Tahun</td>
					</tr>
					<tr>
						<td class="bold" style="width:20%">Nama</td>
						<td style="width:30%">: '. $pasien->namapas .'</td>
						<td class="bold" style="width:20%">Kelas</td>
						<td style="width:30%">: '. $pasien->kelas .'</td>
					</tr>
					<tr>
						<td class="bold" style="width:20%">Jenis kelamin</td>
						<td style="width:30%">: '. $jenis_kelamin .'</td>
					</tr>
				</tbody>
			</table>';

			$chari	.= '<table class="table">
				<tbody>
					<tr>
						<td class="bordered bold" style="width:20%">Dokter Operator</td>
						<td class="bordered" style="width:30%">'. $dokterop .'</td>
						<td class="bordered bold" style="width:20%">Pembiayaan</td>
						<td class="bordered" style="width:30%">Rp '. number_format($data->pembiayaan, 0, '.', ',') .'</td>
					</tr>
					<tr>
						<td class="bordered bold" style="width:20%">Dokter Anestesi</td>
						<td class="bordered" style="width:30%">'. $dokteran .'</td>
						<td class="bordered bold" style="width:20%">Mulai jam</td>
						<td class="bordered" style="width:30%">'. $data->mulai_jam .'</td>
					</tr>
					<tr>
						<td class="bordered bold" style="width:20%">Perawat Asisten</td>
						<td class="bordered" style="width:30%">1. '. $dokteropas1 .'<br />2. '. $dokteropas2 .'</td>
						<td class="bordered bold" style="width:20%">Selesai Jam</td>
						<td class="bordered" style="width:30%">'. $data->selesai_jam .'</td>
					</tr>
					<tr>
						<td class="bordered bold" style="width:20%">Perawat Anestesi</td>
						<td class="bordered" style="width:30%">'. $dokteranas .'</td>
						<td class="bordered bold" style="width:20%">Tanggal</td>
						<td class="bordered" style="width:30%">'. date("d/m/Y", strtotime($data->tanggal)) .'</td>
					</tr>
					<tr>
						<td class="bordered bold" style="width:20%">Diagnosa Pra Operatif</td>
						<td class="bordered" style="width:30%">'. $data->diagnosa_pra_operative .'</td>
						<td class="bordered bold" style="width:20%">1. VOD<br />2. TOD</td>
						<td class="bordered" style="width:30%">1. '. $data->vod .'<br />2. '. $data->tod .'</td>
					</tr>
					<tr>
						<td class="bordered bold" style="width:20%">Diagnosa Post Operatif</td>
						<td class="bordered" style="width:30%">'. $data->diagnosa_post_operative .'</td>
						<td class="bordered bold" style="width:20%">1. VOS<br />2. TOS</td>
						<td class="bordered" style="width:30%">1. '. $data->vos .'<br />2. '. $data->tos .'</td>
					</tr>
					<tr>
						<td class="bordered bold" style="width:20%">Metode Operasif</td>
						<td class="bordered" style="width:30%">'. $data->metode_operasi .'</td>
						<td class="bordered bold" style="width:20%">Anestesi</td>
						<td class="bordered" style="width:30%">'. $data->anestesi .'</td>
					</tr>
				</tbody>
			</table>
			
			<table class="table">
				<tbody>
					<tr>
						<td class="bordered bold" style="width:33.3333333333%;border-bottom:none !important;border-top:none !important">Pelaksanaan Operasi :</td>
						<td class="bordered bold" style="width:33.3333333333%;border-bottom:none !important;border-top:none !important">Kondisi Mata :</td>
						<td class="bordered bold" style="width:33.3333333333%;border-bottom:none !important;border-top:none !important">Pengiriman Specimen :</td>
					</tr>
					<tr>
						<td class="bordered" style="width:33.3333333333%;border-top:none !important">'. $data->pelaksanaan_operasi .'</td>
						<td class="bordered" style="width:33.3333333333%;border-top:none !important">'. $data->kondisi_mata .'</td>
						<td class="bordered" style="width:33.3333333333%;border-top:none !important">'. $data->pengiriman_specimen .'</td>
					</tr>
				</tbody>
			</table>
			
			<table class="table">
				<tbody>
					<tr>
						<td class="bordered subtitle bold" style="width:50%">Uraian Pembedahan</td>
						<td class="bordered" rowspan="2" style="width:50%">
							<p><b>Riwayat Penyakit Lain :</b></p><br />
							'. $data->riwayat_penyakit .'
						</td>
					</tr>
					<tr>
						<td class="bordered">
							<p><b>'. $msics .'&emsp;M. SICS</b></p>
							<div style="font-size:7px">
							1. Bersihkan Lap. Operasi
							2. Pasang Eye Speculum
							3. Irigasi dengan RL
							4. Anestasi dengan Lidocain 2%
							5. Buat Konjungtiva flap ⟶ Kauter Sampai Darah Berhenti
							6. Buat Frwan Insisi di Sklera Lanjutkan dengan Tannel, Tembus Kornea Untuk Side Port
							7. Beri Vision Blue, Irigasi dengan RL
							8. Beri Metil Celulose atau Visco Elastic Baru Capsuleroksis ⟶ Tembus Sklera Hydrodissection ⟶ Dengan Keratome Baru Luksir Nucleus Dengan Vectis
							9. Aspirasi Irigasi dengan Simcoe
							10. Injeksi IOL, Bersihkan Kamera Oculi Anterior dengan Simcoe
							11. Odemkan Kornea di Daerah Side Port
							12. injeksi Genta + Dexa Subkonjungtiva ⟶ Kauter Konjungtiva
							13Beri Bethadine ⟶ Tutup Kain Kassa Steril / Kacamata Pelindung Tembus Pandang
							</div>
						</td>
					</tr>
					<tr>
						<td class="bordered">
							<p><b>'. $pachoemulsifikasi .'&emsp;Phacoemulsifikasi</b></p>
							<div style="font-size:7px">
								1. Bersihkan Lap. Operasi
								2. Pasang Eye Speculum
								3. Insisi Kornea
								4. Lakukan Operasi Katarak Pachoemulsifikasi Sesuai dengan Standar Prosedur + IOL (Intra Okular Lensa)
								5. Beri Bethadine Tetes
								6. Tutup Kain Kassa Steril / Kacamata Pelindung Tembus Pandang
							</div>
						</td>
						<td class="bordered">
							<p><b>Komplikasi/Operasi :</b></p><br />
							'. $data->komplikasi_operasi .'
						</td>
					</tr>
					<tr>
						<td class="bordered">
							<p><b>'. $pterigium .'&emsp;Pterigium</b></p>
							<div style="font-size:7px">
								1. Bersihkan Lap. Operasi
								2. Pasang Eye Speculum
								3. Anestesi Topikal dengan Lidocain 2 %
								4. Gunting Pterigium di Daerah Limbus, dan Extirpati Pt, Sampai Kornea Sehingga Bersih
								5. Hentikan Jika Ada Pendarahan
								6. Beri Tetes Bethadine dan Zalf Chlorampenicol
								7. Tutup Kain Kassa Steril
							</div>
						</td>
						<td class="bordered">
							<p><b>Catatan</b></p><br />
							'. $data->catatan .'
						</td>
					</tr>
					<tr>
						<td class="bordered">
							<p><b>'. $trabeculektomy .'&emsp;Trabeculektomy</b></p>
							<div style="font-size:7px">
								1. Bersihkan Lap. Operasi
								2. Pasang Eye Speculum
								3. Buat Flap Conjungtiva ⟶ Cauter
								4. Buat Flap Segi 4, ½ Tebal Sklera
								5. Gunting Trabekula Segitiga △
								6. Iridektomi
								7. Hecting Sklera dengan Nylon 10-0
								8. Hecting Conjungtiva Sampai Rapat
								9. Beri Bethadine
								10. Tutup Kassa Steril
							</div>
						</td>
						<td class="bordered">
							<p><b>Therapi :</b></p><br />
							'. $data->terapi .'
						</td>
					</tr>
					<tr>
						<td class="bordered">
							<p><b>'. $eviscerasi .'&emsp;Eviscerasi</b></p>
							<div style="font-size:7px">
								1. Bersihkan Lap. Operasi
								2. Injeksi Peribulbar dengan Lidocain 2%
								3. Pasang Eye Speculum, Tembus Bola Mata di Daerah Limbus, Kemudian Gunting Kornea 360°
								4. Curetage Isi Bola Mata Sampai Bersih
								5. Pasang Tampon Sampai Darah Berhenti
								6. Hecting Sklera 3 Buah dengan Benang Slik
								7. Beri Bethadine dan Zalf Chlorampenicol Tampon
								8. Tutup dengan Kassa Steril
							</div>
						</td>
						<td class="bordered">
							<p><b>Keratometri & Biometri</b></p><br />
							<b>K1</b> : '. $data->k1 .'<br /><br />
							<b>K2</b> : '. $data->k2 .'
						</td>
					</tr>
					<tr>
						<td class="bordered">
							<p><b>'. $resimco .'&emsp;Resimco</b></p>
							<div style="font-size:7px">
								1. Bersihkan Lap. Operasi dengan Bethadine
								2. Insisi Clear Cornea 12 Jam dengan Keratom
								3. Beri Adrenalin + RL ke COA ⟶ Sampai Pupil Lebar
								4. Masukkan Visco Elastic di Atas dan di Bawah IOL
								5. Lakukan Resimco / Polish Pada Capsul Posterior Sampai Bersih dari PCO
								6. Bentuk COA Oedemkan Corne dengan RL
								7. Beri Bethadine dan Cylown Leles
								8. Beri Kacamata Pelindung
							</div>
						</td>
						<td class="bordered">
							<p><b>Implantasi/IOL</b> : 16, 17, 18, 19, 20, 21, <u>'. $data->implantasi .'</u></p><br />
							<p><b>Jahitan</b> : '. $jahitan .'</p><br />
							<p><b>Nomor</b> : '. $data->nomor .'</p><br />
							<p><b>Jumlah</b> : '. $data->jumlah .'</p><br />
						</td>
					</tr>
				</tbody>
			</table>
			<br /><br />
			<table class="table">
				<tbody>
					<tr>
						<td style="width:50%;margin:auto" class=""></td>
						<td style="width:50%;margin:auto" class="bold centered">
							<b>Operator</b><br /><br /><br /><br /><br /><br />
							'. $dokterop .'
						</td>
					</tr>
				</tbody>
			</table>';

			$this->M_cetak->mpdf('P','A4',$judul, $chari,'LAPORAN_OPERASI_'. $pasien->namapas .'.PDF', 0, 0, 9, 2);
		}
	}

}