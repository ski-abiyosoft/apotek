<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public  function __construct(){
	parent::__construct();
	   $this->is_logged_in();
	   $this->load->model('M_dashboard');
	   $this->load->model('M_template_cetak');
	
	}
	
	public function is_logged_in(){
	$is_logged_in=$this->session->userdata('is_logged_in');
		if(!isset($is_logged_in)||$is_logged_in!= true) {
		redirect(base_url());
		} 
	}
	
	public function index()
	{
		$cabang = $this->session->userdata('unit');
		$now = date("Y-m-d");
		$p1 = '';
		$p2 = '';
		$data['report']    = $this->M_dashboard->report();
		$pasien    		   = $this->M_dashboard->jcustomer();        
		$data['pasien']	   = $pasien[0]->jml;
		$data['periode']   = $this->M_global->_periodebulan().'-'.$this->M_global->_periodetahun();
		$data['tahun']     = 'Periode Tahun '.$this->M_global->_periodetahun();
		$data['hutang']    = $this->M_dashboard->dshutang();        
		$data['piutang']   = $this->M_dashboard->dspiutang();  
		$data['aset']      = $this->M_dashboard->dsaset();  
		$data['lr']        = 0;  
		// master
		$par = $this->input->get("par");
		$fokus = $this->input->get("fokus");
		$dari = $this->input->get("dari");
		$sampai = $this->input->get("sampai");
		$data['dari'] = $dari;
		$data['sampai'] = $sampai;
		$data['fokus'] = $fokus;

		// untuk lap_pen
		$isi = $this->input->get('isi');

		if($dari != '' || $dari != null && $sampai != '' || $sampai != null) {
			// kun_pas
			$agama = $this->db->query("SELECT s.keterangan AS agama, COUNT(r.rekmed) AS jumlah FROM tbl_pasien p JOIN tbl_regist r ON p.rekmed = r.rekmed JOIN tbl_setinghms s ON p.agama = s.kodeset WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' GROUP BY p.agama ORDER BY COUNT(r.rekmed) DESC")->result();
			$jeniskelamin = $this->db->query("SELECT IF(p.jkel = 'P', 'Pria', 'Wanita') AS jk, COUNT(r.rekmed) AS jumlah FROM tbl_pasien p JOIN tbl_regist r ON p.rekmed = r.rekmed WHERE r.koders = '$cabang'  AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' GROUP BY p.jkel ORDER BY COUNT(r.rekmed) DESC")->result();
			$pendidikan = $this->db->query("SELECT s.keterangan AS pen, COUNT(r.rekmed) AS jumlah FROM tbl_pasien p JOIN tbl_regist r ON p.rekmed = r.rekmed JOIN tbl_setinghms s ON p.pendidikan = s.kodeset WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' GROUP BY p.pendidikan ORDER BY COUNT(r.rekmed) DESC")->result();
			$pekerjaan = $this->db->query("SELECT s.keterangan AS pek, COUNT(r.rekmed) AS jumlah, p.pekerjaan FROM tbl_pasien p JOIN tbl_regist r ON p.rekmed = r.rekmed JOIN tbl_setinghms s ON p.pekerjaan = s.kodeset WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' GROUP BY p.pekerjaan ORDER BY COUNT(r.rekmed) DESC")->result();
			$status = $this->db->query("SELECT s.keterangan AS stat, COUNT(r.rekmed) AS jumlah FROM tbl_pasien p JOIN tbl_regist r ON p.rekmed = r.rekmed JOIN tbl_setinghms s ON p.status = s.kodeset WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' GROUP BY p.status ORDER BY COUNT(r.rekmed) DESC")->result();
			$carabayar = $this->db->query("SELECT IF(r.jenispas = 'PAS1', 'Perorangan', (SELECT cust_nama FROM tbl_penjamin WHERE cust_id = r.cust_id)) AS cara, COUNT(r.rekmed) AS jumlah FROM tbl_pasien p JOIN tbl_regist r ON p.rekmed = r.rekmed JOIN tbl_setinghms s ON r.jenispas = s.kodeset WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' GROUP BY r.jenispas, r.cust_id ORDER BY COUNT(r.rekmed) DESC")->result();
			$poli = $this->db->query("SELECT s.namapost AS pol, COUNT(r.rekmed) AS jumlah FROM tbl_pasien p JOIN tbl_regist r ON p.rekmed = r.rekmed JOIN tbl_namapos s ON r.kodepos = s.kodepos WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' GROUP BY r.kodepos ORDER BY COUNT(r.rekmed) DESC")->result();
			$dokter = $this->db->query("SELECT s.nadokter AS dok, COUNT(r.rekmed) AS jumlah FROM tbl_pasien p JOIN tbl_regist r ON p.rekmed = r.rekmed JOIN tbl_dokter s ON r.kodokter = s.kodokter WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' GROUP BY r.kodokter ORDER BY COUNT(r.rekmed) DESC")->result();
			$kecamatan = $this->db->query("SELECT s.namakec AS kec, COUNT(r.rekmed) AS jumlah FROM tbl_pasien p JOIN tbl_regist r ON p.rekmed = r.rekmed JOIN tbl_kecamatan s ON p.kecamatan = s.kodekec WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' GROUP BY p.kecamatan ORDER BY COUNT(r.rekmed) DESC")->result();

			// lap_pen
			if($par == '' || $par == null) {
				$penyakit10 = $this->db->query("SELECT i.icdcode AS kode, b.str AS ket, COUNT(r.noreg) AS jumlah FROM tbl_icdtr i JOIN tbl_icdinb b ON i.icdcode = b.code JOIN tbl_regist r ON r.noreg = i.noreg WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' AND b.sab = 'ICD10_1998' GROUP BY i.icdcode ORDER BY COUNT(r.noreg) DESC LIMIT 10")->result();
				$tindakan = $this->db->query("SELECT i.icdcode AS kode, b.str AS ket, COUNT(r.noreg) AS jumlah FROM tbl_icdtr i JOIN tbl_icdinb b ON i.icdcode = b.code JOIN tbl_regist r ON r.noreg = i.noreg WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' AND b.sab = 'ICD9CM_2005' GROUP BY i.icdcode ORDER BY COUNT(r.noreg) DESC LIMIT 10")->result();
				$statistik = $this->db->query("SELECT i.icdcode AS kode, b.str AS ket, COUNT(r.noreg) AS jumlah, SUM(IF(p.jkel = 'p', 1, 0)) AS pria, SUM(IF(p.jkel = 'w', 1, 0)) AS wanita, SUM(IF(r.rekmed > 1, 1, 0)) AS ulang, SUM(IF(r.rekmed < 2, 1, 0)) AS baru FROM tbl_icdtr i JOIN tbl_icdinb b ON i.icdcode = b.code JOIN tbl_regist r ON r.noreg = i.noreg JOIN tbl_pasien p ON r.rekmed = p.rekmed WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' AND b.sab = 'ICD10_1998' GROUP BY i.icdcode ORDER BY COUNT(r.noreg) DESC LIMIT 10")->result();
			} else if ($par == 1) {
				if ($isi == '' || $isi == null) {
					$isix = 10;
				} else {
					$isix = $isi;
				}
				$penyakit10 = $this->db->query("SELECT i.icdcode AS kode, b.str AS ket, COUNT(r.noreg) AS jumlah FROM tbl_icdtr i JOIN tbl_icdinb b ON i.icdcode = b.code JOIN tbl_regist r ON r.noreg = i.noreg WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' AND b.sab = 'ICD10_1998' GROUP BY i.icdcode ORDER BY COUNT(r.noreg) DESC LIMIT $isix")->result();
				$tindakan = $this->db->query("SELECT i.icdcode AS kode, b.str AS ket, COUNT(r.noreg) AS jumlah FROM tbl_icdtr i JOIN tbl_icdinb b ON i.icdcode = b.code JOIN tbl_regist r ON r.noreg = i.noreg WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' AND b.sab = 'ICD9CM_2005' GROUP BY i.icdcode ORDER BY COUNT(r.noreg) DESC LIMIT 10")->result();
				$statistik = $this->db->query("SELECT i.icdcode AS kode, b.str AS ket, COUNT(r.noreg) AS jumlah, SUM(IF(p.jkel = 'p', 1, 0)) AS pria, SUM(IF(p.jkel = 'w', 1, 0)) AS wanita, SUM(IF(r.rekmed > 1, 1, 0)) AS ulang, SUM(IF(r.rekmed < 2, 1, 0)) AS baru FROM tbl_icdtr i JOIN tbl_icdinb b ON i.icdcode = b.code JOIN tbl_regist r ON r.noreg = i.noreg JOIN tbl_pasien p ON r.rekmed = p.rekmed WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' AND b.sab = 'ICD10_1998' GROUP BY i.icdcode ORDER BY COUNT(r.noreg) DESC LIMIT 10")->result();
			} else if ($par == 2) {
				if ($isi == '' || $isi == null) {
					$isix = 10;
				} else {
					$isix = $isi;
				}
				$penyakit10 = $this->db->query("SELECT i.icdcode AS kode, b.str AS ket, COUNT(r.noreg) AS jumlah FROM tbl_icdtr i JOIN tbl_icdinb b ON i.icdcode = b.code JOIN tbl_regist r ON r.noreg = i.noreg WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' AND b.sab = 'ICD10_1998' GROUP BY i.icdcode ORDER BY COUNT(r.noreg) DESC LIMIT 10")->result();
				$tindakan = $this->db->query("SELECT i.icdcode AS kode, b.str AS ket, COUNT(r.noreg) AS jumlah FROM tbl_icdtr i JOIN tbl_icdinb b ON i.icdcode = b.code JOIN tbl_regist r ON r.noreg = i.noreg WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' AND b.sab = 'ICD9CM_2005' GROUP BY i.icdcode ORDER BY COUNT(r.noreg) DESC LIMIT $isix")->result();
				$statistik = $this->db->query("SELECT i.icdcode AS kode, b.str AS ket, COUNT(r.noreg) AS jumlah, SUM(IF(p.jkel = 'p', 1, 0)) AS pria, SUM(IF(p.jkel = 'w', 1, 0)) AS wanita, SUM(IF(r.rekmed > 1, 1, 0)) AS ulang, SUM(IF(r.rekmed < 2, 1, 0)) AS baru FROM tbl_icdtr i JOIN tbl_icdinb b ON i.icdcode = b.code JOIN tbl_regist r ON r.noreg = i.noreg JOIN tbl_pasien p ON r.rekmed = p.rekmed WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' AND b.sab = 'ICD10_1998' GROUP BY i.icdcode ORDER BY COUNT(r.noreg) DESC LIMIT 10")->result();
			} else if($par == 3){
				if ($isi == '' || $isi == null) {
					$isix = 10;
				} else {
					$isix = $isi;
				}
				$penyakit10 = $this->db->query("SELECT i.icdcode AS kode, b.str AS ket, COUNT(r.noreg) AS jumlah FROM tbl_icdtr i JOIN tbl_icdinb b ON i.icdcode = b.code JOIN tbl_regist r ON r.noreg = i.noreg WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' AND b.sab = 'ICD10_1998' GROUP BY i.icdcode ORDER BY COUNT(r.noreg) DESC LIMIT 10")->result();
				$tindakan = $this->db->query("SELECT i.icdcode AS kode, b.str AS ket, COUNT(r.noreg) AS jumlah FROM tbl_icdtr i JOIN tbl_icdinb b ON i.icdcode = b.code JOIN tbl_regist r ON r.noreg = i.noreg WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' AND b.sab = 'ICD9CM_2005' GROUP BY i.icdcode ORDER BY COUNT(r.noreg) DESC LIMIT 10")->result();
				$statistik = $this->db->query("SELECT i.icdcode AS kode, b.str AS ket, COUNT(r.noreg) AS jumlah, SUM(IF(p.jkel = 'p', 1, 0)) AS pria, SUM(IF(p.jkel = 'w', 1, 0)) AS wanita, SUM(IF(r.rekmed > 1, 1, 0)) AS ulang, SUM(IF(r.rekmed < 2, 1, 0)) AS baru FROM tbl_icdtr i JOIN tbl_icdinb b ON i.icdcode = b.code JOIN tbl_regist r ON r.noreg = i.noreg JOIN tbl_pasien p ON r.rekmed = p.rekmed WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' AND b.sab = 'ICD10_1998' GROUP BY i.icdcode ORDER BY COUNT(r.noreg) DESC LIMIT $isix")->result();
			}
		} else {
			// kun_pas
			$agama = $this->db->query("SELECT s.keterangan AS agama, COUNT(r.rekmed) AS jumlah FROM tbl_pasien p JOIN tbl_regist r ON p.rekmed = r.rekmed JOIN tbl_setinghms s ON p.agama = s.kodeset WHERE r.koders = '$cabang' AND r.tglmasuk like '%$now%' GROUP BY p.agama ORDER BY COUNT(r.rekmed) DESC")->result();
			$jeniskelamin = $this->db->query("SELECT IF(p.jkel = 'P', 'Pria', 'Wanita') AS jk, COUNT(r.rekmed) AS jumlah FROM tbl_pasien p JOIN tbl_regist r ON p.rekmed = r.rekmed WHERE r.koders = '$cabang'  AND r.tglmasuk like '%$now%' GROUP BY p.jkel ORDER BY COUNT(r.rekmed) DESC")->result();
			$pendidikan = $this->db->query("SELECT s.keterangan AS pen, COUNT(r.rekmed) AS jumlah FROM tbl_pasien p JOIN tbl_regist r ON p.rekmed = r.rekmed JOIN tbl_setinghms s ON p.pendidikan = s.kodeset WHERE r.koders = '$cabang' AND r.tglmasuk like '%$now%' GROUP BY p.pendidikan ORDER BY COUNT(r.rekmed) DESC")->result();
			$pekerjaan = $this->db->query("SELECT s.keterangan AS pek, COUNT(r.rekmed) AS jumlah, p.pekerjaan FROM tbl_pasien p JOIN tbl_regist r ON p.rekmed = r.rekmed JOIN tbl_setinghms s ON p.pekerjaan = s.kodeset WHERE r.koders = '$cabang' AND r.tglmasuk like '%$now%' GROUP BY p.pekerjaan ORDER BY COUNT(r.rekmed) DESC")->result();
			$status = $this->db->query("SELECT s.keterangan AS stat, COUNT(r.rekmed) AS jumlah FROM tbl_pasien p JOIN tbl_regist r ON p.rekmed = r.rekmed JOIN tbl_setinghms s ON p.status = s.kodeset WHERE r.koders = '$cabang' AND r.tglmasuk like '%$now%' GROUP BY p.status ORDER BY COUNT(r.rekmed) DESC")->result();
			$carabayar = $this->db->query("SELECT IF(r.jenispas = 'PAS1', 'Perorangan', (SELECT cust_nama FROM tbl_penjamin WHERE cust_id = r.cust_id)) AS cara, COUNT(r.rekmed) AS jumlah FROM tbl_pasien p JOIN tbl_regist r ON p.rekmed = r.rekmed JOIN tbl_setinghms s ON r.jenispas = s.kodeset WHERE r.koders = '$cabang' AND r.tglmasuk like '%$now%' GROUP BY r.jenispas, r.cust_id ORDER BY COUNT(r.rekmed) DESC")->result();
			$poli = $this->db->query("SELECT s.namapost AS pol, COUNT(r.rekmed) AS jumlah FROM tbl_pasien p JOIN tbl_regist r ON p.rekmed = r.rekmed JOIN tbl_namapos s ON r.kodepos = s.kodepos WHERE r.koders = '$cabang' AND r.tglmasuk like '%$now%' GROUP BY r.kodepos ORDER BY COUNT(r.rekmed) DESC")->result();
			$dokter = $this->db->query("SELECT s.nadokter AS dok, COUNT(r.rekmed) AS jumlah FROM tbl_pasien p JOIN tbl_regist r ON p.rekmed = r.rekmed JOIN tbl_dokter s ON r.kodokter = s.kodokter WHERE r.koders = '$cabang' AND r.tglmasuk like '%$now%' GROUP BY r.kodokter ORDER BY COUNT(r.rekmed) DESC")->result();
			$kecamatan = $this->db->query("SELECT s.namakec AS kec, COUNT(r.rekmed) AS jumlah FROM tbl_pasien p JOIN tbl_regist r ON p.rekmed = r.rekmed JOIN tbl_kecamatan s ON p.kecamatan = s.kodekec WHERE r.koders = '$cabang' AND r.tglmasuk like '%$now%' GROUP BY p.kecamatan ORDER BY COUNT(r.rekmed) DESC")->result();

			// lap_pen
			$penyakit10 = $this->db->query("SELECT i.icdcode AS kode, b.str AS ket, COUNT(r.noreg) AS jumlah FROM tbl_icdtr i JOIN tbl_icdinb b ON i.icdcode = b.code JOIN tbl_regist r ON r.noreg = i.noreg WHERE r.koders = '$cabang' AND r.tglmasuk like '%$now%' AND b.sab = 'ICD10_1998' GROUP BY i.icdcode ORDER BY COUNT(r.noreg) DESC LIMIT 10")->result();
			$tindakan = $this->db->query("SELECT i.icdcode AS kode, b.str AS ket, COUNT(r.noreg) AS jumlah FROM tbl_icdtr i JOIN tbl_icdinb b ON i.icdcode = b.code JOIN tbl_regist r ON r.noreg = i.noreg WHERE r.koders = '$cabang' AND r.tglmasuk like '%$now%' AND b.sab = 'ICD9CM_2005' GROUP BY i.icdcode ORDER BY COUNT(r.noreg) DESC LIMIT 10")->result();
			$statistik = $this->db->query("SELECT i.icdcode AS kode, b.str AS ket, COUNT(r.noreg) AS jumlah, SUM(IF(p.jkel = 'p', 1, 0)) AS pria, SUM(IF(p.jkel = 'w', 1, 0)) AS wanita, SUM(IF(r.rekmed > 1, 1, 0)) AS ulang, SUM(IF(r.rekmed < 2, 1, 0)) AS baru FROM tbl_icdtr i JOIN tbl_icdinb b ON i.icdcode = b.code JOIN tbl_regist r ON r.noreg = i.noreg JOIN tbl_pasien p ON r.rekmed = p.rekmed WHERE r.koders = '$cabang' AND r.tglmasuk like '%$now%' AND b.sab = 'ICD10_1998' GROUP BY i.icdcode ORDER BY COUNT(r.noreg) DESC LIMIT 10")->result();
		}

		if ($par == 1) {
			$isi = $isix;
			$isi2 = 10;
			$isi3 = 10;
		} else if ($par == 2) {
			$isi = 10;
			$isi2 = $isix;
			$isi3 = 10;
		} else if ($par == 3) {
			$isi = 10;
			$isi2 = 10;
			$isi3 = $isix;
		} else {
			$isi = 10;
			$isi2 = 10;
			$isi3 = 10;
		}

		$data['isi'] = $isi;
		$data['isi2'] = $isi2;
		$data['isi3'] = $isi3;

		// kun_pas
		$data['agama']					= $agama;
		$data['jeniskelamin']		= $jeniskelamin;
		$data['pendidikan']			= $pendidikan;
		$data['pekerjaan']			= $pekerjaan;
		$data['status']					= $status;
		$data['carabayar']			= $carabayar;
		$data['poli']						= $poli;
		$data['dokter']					= $dokter;
		$data['kecamatan']			= $kecamatan;
		$data['penyakit10']			= $penyakit10;
		$data['tindakan']				= $tindakan;
		$data['statistik']			= $statistik;
		
		$this->load->view('template',$data);
		$this->load->view('template/dashboard',$data);
		
	}

	public function unduh()
	{
		$cabang = $this->session->userdata('unit');
		$dari = $this->input->get("dari");
		$sampai = $this->input->get("sampai");
		$cek = $this->input->get("cek");
		$body = '';
		if($cek == 1){
			$data = $this->db->query("SELECT s.keterangan AS nama, COUNT(r.rekmed) AS jumlah FROM tbl_pasien p JOIN tbl_regist r ON p.rekmed = r.rekmed JOIN tbl_setinghms s ON p.agama = s.kodeset WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' GROUP BY p.agama ORDER BY COUNT(r.rekmed) DESC")->result();
		} else if ($cek == 2){
			$data = $this->db->query("SELECT IF(p.jkel = 'P', 'Pria', 'Wanita') AS jk, COUNT(r.rekmed) AS jumlah FROM tbl_pasien p JOIN tbl_regist r ON p.rekmed = r.rekmed WHERE r.koders = '$cabang'  AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' GROUP BY p.jkel ORDER BY COUNT(r.rekmed) DESC")->result();
		} else if($cek == 3){
			$data = $this->db->query("SELECT s.keterangan AS nama, COUNT(r.rekmed) AS jumlah FROM tbl_pasien p JOIN tbl_regist r ON p.rekmed = r.rekmed JOIN tbl_setinghms s ON p.pendidikan = s.kodeset WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' GROUP BY p.pendidikan ORDER BY COUNT(r.rekmed) DESC")->result();
		} else if($cek == 4) {
			$data = $this->db->query("SELECT s.keterangan AS nama, COUNT(r.rekmed) AS jumlah, p.pekerjaan FROM tbl_pasien p JOIN tbl_regist r ON p.rekmed = r.rekmed JOIN tbl_setinghms s ON p.pekerjaan = s.kodeset WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' GROUP BY p.pekerjaan ORDER BY COUNT(r.rekmed) DESC")->result();
		} else if($cek == 5) {
			$data = $this->db->query("SELECT s.keterangan AS nama, COUNT(r.rekmed) AS jumlah FROM tbl_pasien p JOIN tbl_regist r ON p.rekmed = r.rekmed JOIN tbl_setinghms s ON p.status = s.kodeset WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' GROUP BY p.status ORDER BY COUNT(r.rekmed) DESC")->result();
		} else if($cek == 6) {
			$data = $this->db->query("SELECT IF(r.jenispas = 'PAS1', 'Perorangan', (SELECT cust_nama FROM tbl_penjamin WHERE cust_id = r.cust_id)) AS cara, COUNT(r.rekmed) AS jumlah FROM tbl_pasien p JOIN tbl_regist r ON p.rekmed = r.rekmed JOIN tbl_setinghms s ON r.jenispas = s.kodeset WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' GROUP BY r.jenispas, r.cust_id ORDER BY COUNT(r.rekmed) DESC")->result();
		} else if($cek == 7) {
			$data = $this->db->query("SELECT s.namapost AS nama, COUNT(r.rekmed) AS jumlah FROM tbl_pasien p JOIN tbl_regist r ON p.rekmed = r.rekmed JOIN tbl_namapos s ON r.kodepos = s.kodepos WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' GROUP BY r.kodepos ORDER BY COUNT(r.rekmed) DESC")->result();
		} else if($cek == 8) {
			$data = $this->db->query("SELECT s.nadokter AS nama, COUNT(r.rekmed) AS jumlah FROM tbl_pasien p JOIN tbl_regist r ON p.rekmed = r.rekmed JOIN tbl_dokter s ON r.kodokter = s.kodokter WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' GROUP BY r.kodokter ORDER BY COUNT(r.rekmed) DESC")->result();
		} else if($cek == 9){
			$data = $this->db->query("SELECT s.namakec AS nama, COUNT(r.rekmed) AS jumlah FROM tbl_pasien p JOIN tbl_regist r ON p.rekmed = r.rekmed JOIN tbl_kecamatan s ON p.kecamatan = s.kodekec WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' GROUP BY p.kecamatan ORDER BY COUNT(r.rekmed) DESC")->result();
		}
		$date       = "Dari Tgl : " . date("d-m-Y", strtotime($dari)) . " S/D " . date("d-m-Y", strtotime($sampai));
		$profile    = data_master('tbl_namers', array('koders' => $cabang));
		$kota       = $profile->kota;
		$position   = 'P';
		$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">";
		if($cek == 1) {
			$judul = "KUNJUNGAN PASIEN BERDASARKAN AGAMA";
			$body .= "<thead>
				<tr>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Agama</td>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Jumlah</td>
				</tr>
			</thead>";
		} else if ($cek == 2) {
			$judul = "KUNJUNGAN PASIEN BERDASARKAN JENIS KELAMIN";
			$body .= "<thead>
				<tr>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Jenis Kelamin</td>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Jumlah</td>
				</tr>
			</thead>";
		} else if ($cek == 3) {
			$judul = "KUNJUNGAN PASIEN BERDASARKAN PENDIDIKAN";
			$body .= "<thead>
				<tr>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Pendidikan</td>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Jumlah</td>
				</tr>
			</thead>";
		} else if ($cek == 4) {
			$judul = "KUNJUNGAN PASIEN BERDASARKAN PEKERJAAN";
			$body .= "<thead>
				<tr>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Pekerjaan</td>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Jumlah</td>
				</tr>
			</thead>";
		} else if ($cek == 5) {
			$judul = "KUNJUNGAN PASIEN BERDASARKAN STATUS";
			$body .= "<thead>
				<tr>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Status</td>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Jumlah</td>
				</tr>
			</thead>";
		} else if ($cek == 6) {
			$judul = "KUNJUNGAN PASIEN BERDASARKAN CARA BAYAR";
			$body .= "<thead>
				<tr>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Cara Bayar</td>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Jumlah</td>
				</tr>
			</thead>";
		} else if ($cek == 7) {
			$judul = "KUNJUNGAN PASIEN BERDASARKAN POLI / UNIT";
			$body .= "<thead>
				<tr>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Poli/Unit</td>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Jumlah</td>
				</tr>
			</thead>";
		} else if ($cek == 8) {
			$judul = "KUNJUNGAN PASIEN BERDASARKAN DOKTER";
			$body .= "<thead>
				<tr>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Dokter</td>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Jumlah</td>
				</tr>
			</thead>";
		} else if ($cek == 9) {
			$judul = "KUNJUNGAN PASIEN BERDASARKAN KECAMATAN";
			$body .= "<thead>
				<tr>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Kecamatan</td>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Jumlah</td>
				</tr>
			</thead>";
		}
		foreach($data as $d){
			$nama = $d->nama;
			$jumlah = round($d->jumlah);
			$body .= "<tbody>
				<tr>
					<td style=\"text-align: left; padding: 10px;\">" . $nama . "</td>
					<td style=\"text-align: right; padding: 10px;\">" . $jumlah . "</td>
				</tr>
			</tbody>";
		}
		$body .= "</table>";
		$this->M_template_cetak->template($judul, $body, $position, $date, 2);
	}

	public function unduh_p()
	{
		$cabang = $this->session->userdata('unit');
		$dari = $this->input->get("dari");
		$sampai = $this->input->get("sampai");
		$cek = $this->input->get("cek");
		$isi = $this->input->get("isi");
		if ($isi == '' || $isi == null) {
			$isix = 10;
		} else {
			$isix = $isi;
		}
		$body = '';
		if($cek == 1){
			$data = $this->db->query("SELECT i.icdcode AS kode, b.str AS ket, COUNT(r.noreg) AS jumlah FROM tbl_icdtr i JOIN tbl_icdinb b ON i.icdcode = b.code JOIN tbl_regist r ON r.noreg = i.noreg WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' AND b.sab = 'ICD10_1998' GROUP BY i.icdcode ORDER BY COUNT(r.noreg) DESC LIMIT $isix")->result();
		} else if ($cek == 2){
			$data = $this->db->query("SELECT i.icdcode AS kode, b.str AS ket, COUNT(r.noreg) AS jumlah FROM tbl_icdtr i JOIN tbl_icdinb b ON i.icdcode = b.code JOIN tbl_regist r ON r.noreg = i.noreg WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' AND b.sab = 'ICD9CM_2005' GROUP BY i.icdcode ORDER BY COUNT(r.noreg) DESC LIMIT $isix")->result();
		} else if($cek == 3){
			$data = $this->db->query("SELECT i.icdcode AS kode, b.str AS ket, COUNT(r.noreg) AS jumlah, SUM(IF(p.jkel = 'p', 1, 0)) AS pria, SUM(IF(p.jkel = 'w', 1, 0)) AS wanita, SUM(IF(r.rekmed > 1, 1, 0)) AS ulang, SUM(IF(r.rekmed < 2, 1, 0)) AS baru FROM tbl_icdtr i JOIN tbl_icdinb b ON i.icdcode = b.code JOIN tbl_regist r ON r.noreg = i.noreg JOIN tbl_pasien p ON r.rekmed = p.rekmed WHERE r.koders = '$cabang' AND r.tglmasuk >= '$dari' AND r.tglmasuk <= '$sampai' AND b.sab = 'ICD10_1998' GROUP BY i.icdcode ORDER BY COUNT(r.noreg) DESC LIMIT $isix")->result();
		}
		$date       = "Dari Tgl : " . date("d-m-Y", strtotime($dari)) . " S/D " . date("d-m-Y", strtotime($sampai));
		$profile    = data_master('tbl_namers', array('koders' => $cabang));
		$kota       = $profile->kota;
		$position   = 'P';
		$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">";
		if($cek == 1) {
			$judul = $isix ." Besar Penyakit Code ICD";
			$body .= "<thead>
				<tr>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">No</td>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Kode</td>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Nama Penyakit</td>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Jumlah</td>
				</tr>
			</thead>";
		} else if ($cek == 2) {
			$judul = $isix . " Besar Tindakan Prosedur Prosedur ICD";
			$body .= "<thead>
				<tr>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">No</td>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Kode</td>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Nama Penyakit</td>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Jumlah</td>
				</tr>
			</thead>";
		} else if ($cek == 3) {
			$judul = $isix . " Statistik ICD";
			$body .= "<thead>
				<tr>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">No</td>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Kode</td>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Nama Penyakit</td>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Jumlah</td>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Ulang</td>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Baru</td>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Pria</td>
					<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Wanita</td>
				</tr>
			</thead>";
		}
		$no = 1;
		if($cek == 1){
			foreach($data as $d){
				$kode = $d->kode;
				$ket = $d->ket;
				$jumlah = round($d->jumlah);
				$body .= "<tbody>
					<tr>
						<td style=\"text-align: left; padding: 10px;\">" . $no++ . "</td>
						<td style=\"text-align: left; padding: 10px;\">" . $kode . "</td>
						<td style=\"text-align: left; padding: 10px;\">" . $ket . "</td>
						<td style=\"text-align: right; padding: 10px;\">" . $jumlah . "</td>
					</tr>
				</tbody>";
			}
		} else if ($cek == 2) {
			foreach ($data as $d) {
				$kode = $d->kode;
				$ket = $d->ket;
				$jumlah = round($d->jumlah);
				$body .= "<tbody>
					<tr>
						<td style=\"text-align: left; padding: 10px;\">" . $no++ . "</td>
						<td style=\"text-align: left; padding: 10px;\">" . $kode . "</td>
						<td style=\"text-align: left; padding: 10px;\">" . $ket . "</td>
						<td style=\"text-align: right; padding: 10px;\">" . $jumlah . "</td>
					</tr>
				</tbody>";
			}
		} else if($cek == 3){
			foreach($data as $d){
				$kode = $d->kode;
				$ket = $d->ket;
				$jumlah = round($d->jumlah);
				$ulang = round($d->ulang);
				$baru = round($d->baru);
				$pria = round($d->pria);
				$wanita = round($d->wanita);
				$body .= "<tbody>
					<tr>
						<td style=\"text-align: left; padding: 10px;\">" . $no++ . "</td>
						<td style=\"text-align: left; padding: 10px;\">" . $kode . "</td>
						<td style=\"text-align: left; padding: 10px;\">" . $ket . "</td>
						<td style=\"text-align: right; padding: 10px;\">" . $jumlah . "</td>
						<td style=\"text-align: right; padding: 10px;\">" . $ulang . "</td>
						<td style=\"text-align: right; padding: 10px;\">" . $baru . "</td>
						<td style=\"text-align: right; padding: 10px;\">" . $pria . "</td>
						<td style=\"text-align: right; padding: 10px;\">" . $wanita . "</td>
					</tr>
				</tbody>";
			}
		}
		$body .= "</table>";
		$this->M_template_cetak->template($judul, $body, $position, $date, 2);
	}
	
}

/* End of file site.php */
/* Location: ./application/controllers/site.php */