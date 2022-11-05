<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class App extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Jakarta");
	}

	public function index()
	{
		$qry = "SELECT koders FROM tbl_namers ORDER BY kodeurut";
		$d['data'] = $this->db->query($qry)->result();

		$this->load->library('user_agent');
		
		if (key_exists('username', $this->session->userdata())) {
			if ($this->agent->is_referral()) {
				redirect($this->agent->referrer());
			}else {
				redirect(base_url('/home'));
			}
		}
		
		$this->load->view('login/v_login_page', $d);
	}
	
	function search_akunpendapatan()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getakunpendapatan($q));
	}

	function search_cabangg()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getcabangg($q));
	}

	function search_tarif()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->gettarif($q));
	}

	public function lock()
	{
		$sesi = $this->input->get('sesi');
		$this->session->set_userdata('referred_from', $sesi);
		$data['sesi'] = $sesi;
		$this->load->view('app/v_login_lock', $data);
	}

	public function lock_all()
	{
		$data['data'] = '';
		// session_destroy();
		$this->load->view('app/v_login_lock_all', $data);
	}

	function authlock()
	{
		$this->load->model('M_login');
		$userid   = $this->session->userdata('username');
		$password = $this->input->post('password');
		$cabang   = $this->input->post('cabang');
		$sesi     = $this->input->post('sesi');
		if ($userid && $password && $this->M_login->validate_user($userid, $password, $cabang)) {

			$loggedinuserid    = $this->session->username;
			$referred_from     = $this->session->userdata('referred_from');
			redirect($referred_from, 'refresh');
		} else {
			$this->session->set_flashdata('ntf1', 'Nama atau Password yang dimasukan salah, Silahkan coba lagi, hubungi administrator untuk mendaftarkan user dan password aplikasi');
			redirect(base_url('app/lock/?sesi=' . $sesi));
		}
	}

	function auth()
	{
		$this->load->model('M_login');
		$userid   = $this->input->post('username');
		$password = $this->input->post('password');
		$cabang = $this->input->post('cabang');

		if ($userid && $password && $this->M_login->validate_user($userid, $password, $cabang)) {
			if ($this->M_login->cek_cabang($userid, $cabang) > 0) {
				$loggedinuserid = $this->session->username;
				redirect(base_url('home'));
			} else {
				$this->session->set_flashdata("ntf0", "hak akses tidak sesuai dengan cabang " . $cabang . ", Silahkan pilih cabang sesuai hak akses");
				$this->index();
			}
		} else {
			$this->session->set_flashdata('ntf1', 'Nama atau Password yang dimasukan salah, Silahkan coba lagi, hubungi administrator untuk mendaftarkan user dan password aplikasi');
			$this->index();
		}
	}

	function logout()
	{
		$this->session->sess_destroy();
		$this->index();
	}


	function search()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getcoa($q));
	}

	function search_barang()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getbarang($q));
	}

	function search_penjamin()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getpenjamin($q));
	}

	function search_pos()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getpos($q));
	}

	function search_kasbank()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getkasbank($q));
	}

	function search_akundiskonadjust()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getakundiskonadjust($q));
	}

	function search_costcentre()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getcostcentre($q));
	}

	function search_promo()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getpromo($q));
	}

	function search_vouchersource()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getvouchersource($q));
	}

	function search_voucher()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getvoucher($q));
	}

	function search_penmain()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getpenjamin($q));
	}

	function search_hadiah()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->gethadiah($q));
	}

	function search_bankedc()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getkasbankedc($q));
	}

	function search_cabang()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getcabang($q));
	}

	function search_cabang2()
	{
		// $q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getcabang2());
	}

	function search_cabang_all()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getcabang_all($q));
	}

	function search_pendapatan()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getpendapatan($q));
	}

	function search_dept()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getdept($q));
	}

	function search_depo()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getdepo($q));
	}

	function search_provinsi()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getprovinsi($q));
	}

	function search_akunbiaya()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getAkunBiaya($q));
	}

	function namaprovinsi()
	{
		$kode = $this->input->get('kode');
		$data = $this->db->query("select * from tbl_propinsi where kodeprop='$kode'")->row();
		if ($data) {
			echo $data->namaprop;
		} else {
			echo "";
		}
	}

	function namakota()
	{
		$kode = $this->input->get('kode');
		$data = $this->db->query("select * from tbl_kabupaten where kodekab='$kode'")->row();
		if ($data) {
			echo $data->namakab;
		} else {
			echo "";
		}
	}

	function namakecamatan()
	{
		$kode = $this->input->get('kode');
		$data = $this->db->query("SELECT * from tbl_kecamatan where kodekec='$kode'")->row();
		if ($data) {
			echo $data->namakec;
		} else {
			echo "";
		}
	}

	function getvaluesetinghms()
	{
		$kode  = $this->input->get('kode');
		$query = $this->db->query("select * from tbl_setinghms where kodeset='$kode'")->row();
		if (!$query) {
			$response = [
				'message'	=> 'not found',
				'data'		=> [],
				'status'	=> false,
			];
		} else {
			$response = [
				'message'	=> 'Success',
				'data'		=> $query,
				'status'	=> true,
			];
		}
		$json = json_encode($response);
		print_r($json);
	}

	function search_tindakan_erad(){
		$unit	= $this->input->get("unit");
		$cabang	= $this->session->userdata("unit");

		if($unit == "-"){
			$tindakan_erad_content 	= (object) [
				"id"	=> 0,
				"text"	=> "--- PILIH UNIT DAHULU ---",
			];

			$query_tindakan_erad	= array(
				$tindakan_erad_content
			);
		} else {
			$query_tindakan_erad	= $this->db->query("SELECT CONCAT('[ ', kodetarif ,' ] - [ ', tindakan ,' ] - [ ', FORMAT(tarifrspoli + tarifdrpoli, 2, '.') ,' ]') AS text, kodetarif AS id FROM daftar_tarif_nonbedah WHERE kodepos = '$unit' AND koders = '$cabang'")->result();
		}

		echo json_encode($query_tindakan_erad);
	}

	function search_tindakan_erad2(){
		// $unit	= $this->input->get("unit");
		$cabang	= $this->session->userdata("unit");
		
		$query_tindakan_erad2	= $this->db->query("SELECT CONCAT('[ ', kodetarif ,' ] - [ ', tindakan ,' ] - [ ', FORMAT(tarifrspoli + tarifdrpoli, 2, '.') ,' ]') AS text, kodetarif AS id FROM daftar_tarif_nonbedah WHERE kodepos = 'RADIO' AND koders = '$cabang'")->result();
		
		echo json_encode($query_tindakan_erad2);
	}

	function search_kota()
	{
		$q = $this->input->post('searchTerm');
		$p = $this->input->get('kode');
		echo json_encode($this->M_global->getkota($q, $p));
	}

	function search_kecamatan()
	{
		$q = $this->input->post('searchTerm');
		$p = $this->input->get('kode');
		echo json_encode($this->M_global->getkecamatan($q, $p));
	}


	function search_agama()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getagama($q));
	}

	function search_pendidikan()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getpendidikan($q));
	}

	function search_preposition()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getpreposition($q));
	}

	function search_statuspasien()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getstatuspasien($q));
	}

	function search_jenispasien()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getjenispasien($q));
	}

	function search_pekerjaan()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getpekerjaan($q));
	}

	function search_goldarah()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getgoldarah($q));
	}

	function search_pasien()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getpasien($q));
	}

	function search_poli()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getpoli($q));
	}

	function search_warnao()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getsearch_warnao($q));
	}

	function search_dokter($poli)
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getdokter($q, $poli));
	}

	function search_perawat($poli)
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getperawat($q, $poli));
	}

	function search_register()
	{
		$q = $this->input->post('searchTerm');
		$p = $this->input->get('poli');
		echo json_encode($this->M_global->getregistrasi($q, $p));
	}

	function search_register_resep()
	{
		$q = $this->input->post('searchTerm');
		$p = $this->input->get('poli');
		echo json_encode($this->M_global->getregistrasi_resep($q, $p));
	}

	function search_resep_obat()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getresep_obat($q));
	}

	function search_all_resep_obat()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getAllresep_obat($q));
	}

	function search_tarif_tindakan()
	{
		$q = $this->input->post('searchTerm');
		$p = $this->input->get('poli');
		echo json_encode($this->M_global->get_tarif_tindakan($q, $p));
	}

	function search_seting_hms()
	{
		$q = $this->input->post('searchTerm');
		$p = $this->input->get('kode');
		echo json_encode($this->M_global->get_seting_hms($q, $p));
	}


	function search_vendor()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getvendor($q));
	}

	function search_rekening_vendor()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getrekeningvendor($q));
	}

	function search_jenis_faktur()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getJenisFaktur($q));
	}

	function search_farmasi_barang()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getfarmasibarang($q));
	}

	function search_farmasi_baranggud()
	{
		$q = $this->input->post('searchTerm');
		$g = $this->input->get('gud');
		echo json_encode($this->M_global->getfarmasibaranggud($q, $g));
	}

	function search_farmasi_barang_alkes($gudang = "")
	{
		if($gudang == ""){
			$alkes_bhp 	= (object) [
				"id"	=> 0,
				"text"	=> "--- PILIH GUDANG DAHULU ---",
			];

			$query_alkes	= array(
				$alkes_bhp
			);

			echo json_encode($query_alkes);
		} else {
			$q = $this->input->post('searchTerm');
			echo json_encode($this->M_global->getfarmasibarang_alkes($q, $gudang));
			// $query_tindakan_erad	= $this->db->query("SELECT CONCAT('[ ', kodetarif ,' ] - [ ', tindakan ,' ] - [ ', FORMAT(tarifrspoli + tarifdrpoli, 2, '.') ,' ]') AS text, kodetarif AS id FROM daftar_tarif_nonbedah WHERE kodepos = '$unit' AND koders = '$cabang'")->result();
		}
	}

	function databarang()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getfarmasidatabarang($q));
	}

	function databaranglog()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getfarmasidatabaranglog($q));
	}

	function search_farmasi_barang2()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getfarmasibarang2($q));
	}

	function search_poli_tindakan()
	{
		$q = $this->input->post('searchTerm');
		$p = $this->input->get('kodpos');
		echo json_encode($this->M_global->getpoli_tindakan($q, $p));
	}

	function search_farmasi_baranggudso()
	{
		$q = $this->input->post('searchTerm');
		$g = $this->input->get('gud');
		echo json_encode($this->M_global->getfarmasibaranggudso($q, $g));
	}

	function search_farmasi_permohonan()
	{
		$q = $this->input->post('searchTerm');
		$d = $this->input->get('dari');
		$k = $this->input->get('ke');
		echo json_encode($this->M_global->getfarmasipermohonan($q));
	}

	function search_logistik_permohonan()
	{
		$q = $this->input->post('searchTerm');
		$d = $this->input->get('dari');
		$k = $this->input->get('ke');
		echo json_encode($this->M_global->getlogistikpermohonan($q));
	}

	function search_log_barang()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getlogbarang($q));
	}

	function search_log_baranggud()
	{
		$q = $this->input->post('searchTerm');
		$g = $this->input->get('gud');
		echo json_encode($this->M_global->getlogbaranggud($q, $g));
	}

	function search_farmasi_depo()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getfarmasidepo($q));
	}
	function search_user_2()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getfarmasiuser2($q));
	}

	function search_user()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getfarmasiuser($q));
	}

	function search_logistik_depo()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->getlogistikdepo($q));
	}

	function search_farmasi_po()
	{
		$q = $this->input->post('searchTerm');
		$p = $this->input->get('vendor');
		echo json_encode($this->M_global->get_farmasi_po($q, $p));
	}

	function search_icdind()
	{
		$q = $this->input->post('searchTerm');
		$p = $this->input->get('sab');
		echo json_encode($this->M_global->get_icdind($q, $p));
	}

	function search_jnsicd()
	{
		$q = $this->input->post('searchTerm');
		echo json_encode($this->M_global->get_jnsicd($q));
	}



	function search_farmasi_po2()
	{
		$q = $this->input->post('searchTerm');
		$p = $this->input->get('vendor');
		echo json_encode($this->M_global->get_farmasi_po2($q, $p));
	}

	function search_logistik_po()
	{
		$q = $this->input->post('searchTerm');
		$p = $this->input->get('vendor');
		echo json_encode($this->M_global->get_logistik_po($q, $p));
	}

	function search_logistik_po2()
	{
		$q = $this->input->post('searchTerm');
		$p = $this->input->get('vendor');
		echo json_encode($this->M_global->get_logistik_po2($q, $p));
	}



	public function getKota($id_provinsi)
	{
		$qry = "select * from tbl_kabupaten where kodeprop = '$id_provinsi'";

		$query = $this->db->query($qry)->result();
		if (!$query) {
			$response = [
				'message'	=> 'not found',
				'data'		=> [],
				'status'	=> false,
			];
		} else {
			$response = [
				'message'	=> 'Success',
				'data'		=> $query,
				'status'	=> true,
			];
		}
		$json = json_encode($response);
		print_r($json);
	}

	public function getKecamatan($id_kota)
	{
		$qry = "select * from tbl_kecamatan where kodekab = '$id_kota'";

		$query = $this->db->query($qry)->result();
		if (!$query) {
			$response = [
				'message'	=> 'not found',
				'data'		=> [],
				'status'	=> false,
			];
		} else {
			$response = [
				'message'	=> 'Success',
				'data'		=> $query,
				'status'	=> true,
			];
		}
		$json = json_encode($response);
		print_r($json);
	}

	public function getDesa($id_kecamatan)
	{
		$qry = "select * from tbl_desa where kodekec = '$id_kecamatan'";

		$query = $this->db->query($qry)->result();
		if (!$query) {
			$response = [
				'message'	=> 'not found',
				'data'		=> [],
				'status'	=> false,
			];
		} else {
			$response = [
				'message'	=> 'Success',
				'data'		=> $query,
				'status'	=> true,
			];
		}
		$json = json_encode($response);
		print_r($json);
	}

	public function getKodepos($id_desa)
	{
		$qry = "select * from tbl_desa where kodedesa = '$id_desa'";

		$query = $this->db->query($qry)->row();
		if (!$query) {
			$response = [
				'message'	=> 'not found',
				'data'		=> [],
				'status'	=> false,
			];
		} else {
			$response = [
				'message'	=> 'Success',
				'data'		=> $query->kodepos,
				'status'	=> true,
			];
		}
		$json = json_encode($response);
		print_r($json);
	}


	public function getDokterpoli($id_poli)
	{
		$cabang = $this->session->userdata('unit');
		//saya hilangkan where cabang
		if ($cabang == 'DTI' || $cabang == 'DPK' || $cabang == 'BSD' || $cabang == 'BKS' || $cabang == 'KBJ' || $cabang == 'CBR' || $cabang == 'KGD') {

			$qry = "SELECT tbl_dokter.* from tbl_dokter inner join tbl_drpoli
		on tbl_dokter.kodokter=tbl_drpoli.kodokter
		where tbl_drpoli.kopoli = '$id_poli'  and tbl_dokter.koders in ('DTI','DPK','BSD','BKS','KBJ','CBR','KGD')";
		} else {

			$qry = "SELECT tbl_dokter.* from tbl_dokter inner join tbl_drpoli
		on tbl_dokter.kodokter=tbl_drpoli.kodokter
		where tbl_drpoli.kopoli = '$id_poli'  and tbl_dokter.koders = '$cabang'";
		}


		$query = $this->db->query($qry)->result();
		if (!$query) {
			$response = [
				'message'	=> 'not found',
				'data'		=> [],
				'status'	=> false,
			];
		} else {
			$response = [
				'message'	=> 'Success',
				'data'		=> $query,
				'status'	=> true,
			];
		}
		$json = json_encode($response);
		print_r($json);
	}

	public function getcabang()
	{
		$kode = $this->input->get('uid');
		$user = $this->db->query("SELECT cabang, uidlogin FROM userlogin WHERE uidlogin = '$kode'")->row();
		/*$user = $this->db->query("select tbl_namers.namars as namacabang
		from userlogin inner join tbl_namers on userlogin.cabang=tbl_namers.koders
		where userlogin.uidlogin = '$kode'")->row(); */
		if ($user) {
			// $data = $user->cabang;
			$data = [
				'message'	=> 'Success',
				'data'		=> $user->cabang,
				'status'	=> true,
			];
		} else {
			$data = [
				'cabang'	=> '***',

			];
		}

		echo json_encode($data);
	}

	public function getnm()
	{
		$id       = $this->input->get('id');
		$query    = $this->db->query("SELECT*FROM tbl_namers where koders='$id'")->row();
		
		if($query){
			echo json_encode(array("status" => "1", "nm" => $query->namars));
		}else{
			echo json_encode(array("status" => "2", "nm" => "Nama Tidak Di Temukan"));
			
		}
	}
}
