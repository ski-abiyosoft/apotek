<?php defined('BASEPATH') OR exit('No direct script access allowed');
class M_igd extends CI_Model {
  var $table = 'pasien_rajal';
	protected $_icd	= "tbl_icdinb";

	public function __construct(){
    parent::__construct();
		$this->load->database();
  }
	
  public function insert($table,$data){
    return $this->db->insert($table, $data);
  }

	public function get_datatables($jns, $bulan, $tahun) {    
		$query = $this->db->query($this->data( $jns, $bulan, $tahun ));
		return $query->result();
	}

  public function data( $jns='' , $bulan='', $tahun='') {
		$cabang = $this->session->userdata('unit');	
		if($jns==1){
			$tanggal = date('Y-m-d');
			$tgll= "AND tglmasuk = '$tanggal'";			
		} else {
      $tgll= "AND tglmasuk >= '$bulan' AND tglmasuk <= '$tahun'";
		}

		if($_POST['search']['value']) {
      $search = $_POST['search']['value'];
      $search2 = "AND ( diperiksa_perawat LIKE '%$search%' OR diperiksa_dokter LIKE '%$search%' OR jkel LIKE '%$search%' OR jenispas LIKE '%$search%' OR antrino LIKE '%$search%' OR noreg LIKE '%$search%' OR rekmed LIKE '%$search%' OR tglmasuk LIKE '%$search%' OR namapas LIKE '%$search%' OR preposisi LIKE '%$search%' OR nadokter LIKE '%$search%' OR tgllahir LIKE '%$search%')";
		} else {
			$search2='';
		}

		if($_POST['length'] != -1) {
			$lim1 = $_POST['start'];
			$lim2 = ','.$_POST['length'];
			$limm = "LIMIT $lim1 $lim2";
		} else {
			$limm = "";
		}

		$sql = "SELECT *, CONCAT('[ ', (SELECT keterangan FROM tbl_setinghms WHERE kodeset = $this->table.preposisi), ' ] - [ ' , namapas, ' ] - [ ', DATE_FORMAT(tgllahir, '%d-%m-%Y'), ' ]') AS namapasien_lengkap FROM $this->table WHERE kodepos = 'PUGD' AND koders ='$cabang' $search2 and batal = '0' $tgll GROUP BY noreg ORDER BY noreg DESC $limm";
		return $sql;
  }

	public function gettglmasuk(){
		$query = "SELECT tglmasuk FROM pasien_rajal WHERE kodepos = 'PUGD' GROUP BY tglmasuk";
		return $this->db->query($query)->result();
	}

	public function get_data_byid($id){
	  return $this->db->get_where('pasien_rajal', ['koders' => $id, "kodepos" => "PUGD"]);
	}

	public function data_filter($tglmasuk, $kodepos, $nadokter, $tgln, $unit){
		$tgln = date('Y-m-d');
		// $this->db->limit(200,5);
		$query = $this->db->query("SELECT *, CONCAT('[ ', (SELECT keterangan FROM tbl_setinghms WHERE kodeset = $this->table.preposisi), ' ] - [ ' , namapas, ' ] - [ ', DATE_FORMAT(tgllahir, '%d-%m-%Y'), ' ]') AS namapasien_lengkap FROM pasien_rajal WHERE kodepos = 'PUGD' AND tglmasuk BETWEEN '$tglmasuk' AND '$tgln' AND nadokter LIKE '%$nadokter%' AND koders ='$unit' and batal = '0' GROUP BY noreg");
		return $query->result();
	}

	public function tampil_data_filter($koders,$unit) {
		$this->db->limit(100,5);
		$query = $this->db->query("SELECT * FROM pasien_rajal where kodepos = 'PUGD' AND koders = '$unit' AND namapost = '$namapost' AND nadokter = '$nadokter' and batal = '0' GROUP BY noreg");
		return $query->result();
	}

  public function total_pasien(){
		$cabang = $this->session->userdata('unit');	
    $tgln   = date('Y-m-d');
    $query = "SELECT count(*)jum FROM pasien_rajal WHERE kodepos = 'PUGD' AND koders = '$cabang' AND tglmasuk = '$tgln' and batal = '0'";
    $qry = $this->db->query($query)->result();
		return $qry;
  }
    
  public function diperiksa_perawat(){
		$cabang = $this->session->userdata('unit');	
    $tgl    = date('Y-m-d');
    $query 	= "SELECT * FROM pasien_rajal WHERE kodepos = 'PUGD' AND koders = '$cabang' AND tglmasuk LIKE '%$tgl%' AND diperiksa_perawat = 1 and batal = '0'";
    $qry = $this->db->query($query);
    return $qry;
  }

  public function diperiksa_dokter(){
    $cabang = $this->session->userdata('unit');	
    $tgl    = date('Y-m-d');
    $query 	= "SELECT * FROM pasien_rajal WHERE kodepos = 'PUGD' AND koders = '$cabang' AND tglmasuk LIKE '%$tgl%' AND diperiksa_dokter = 1 and batal = '0'";
    $qry = $this->db->query($query);
		return $qry;
  }

	public function count_all() {
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
	
	public function count_filtered( $jns, $bulan, $tahun) {
		$cabang = $this->session->userdata('unit');	
		if($jns==1){
			$tanggal = date('Y-m-d');
			$tgll= "and tglmasuk = '$tanggal'";			
		} else {
      $tgll= "and tglmasuk >= '$bulan' AND tglmasuk<= '$tahun'";
		}

		if($_POST['search']['value']) {
      $search = $_POST['search']['value'];
      $search2 = "AND ( diperiksa_perawat LIKE '%$search%' OR diperiksa_dokter LIKE '%$search%' OR jkel LIKE '%$search%' OR jenispas LIKE '%$search%' OR antrino LIKE '%$search%' OR noreg LIKE '%$search%' OR rekmed LIKE '%$search%' OR tglmasuk LIKE '%$search%' OR namapas LIKE '%$search%' OR preposisi LIKE '%$search%' OR nadokter LIKE '%$search%' OR tgllahir LIKE '%$search%' OR namapost LIKE '%$search%' OR nadokter LIKE '%$search%' )";
		}else{
			$search2='';
		}

		if($_POST['length'] != -1) {
			$lim1 = $_POST['start'];
			$lim2 = ','.$_POST['length'];
			$limm = "LIMIT $lim1 $lim2";
		}else{
			$limm = "";
		}

		$sql = "SELECT *, CONCAT('[ ', (SELECT keterangan FROM tbl_setinghms WHERE kodeset = $this->table.preposisi), ' ] - [ ' , namapas, ' ] - [ ', DATE_FORMAT(tgllahir, '%d-%m-%Y'), ' ]') AS namapasien_lengkap FROM $this->table WHERE kodepos = 'PUGD' AND koders ='$cabang' and batal = '0' $tgll $search2 GROUP BY noreg";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

	public function get_icd_pcare($param){
		$this->db->select("*");
		$this->db->from($this->_icd);
		$this->db->where("code", $param);
		return $this->db->get();
	}
}