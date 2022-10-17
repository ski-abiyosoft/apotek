<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_Poliklinik extends CI_Model 
{
    var $table = 'pasien_rajal';
	public function __construct(){
        parent::__construct();
		$this->load->database();
    }
	
    public function insert($table,$data){
        return $this->db->insert($table, $data);
    }

	function get_datatables($jns, $bulan, $tahun, $poli, $kodokter)
	{    
		$query = $this->db->query($this->data( $jns, $bulan, $tahun, $poli, $kodokter ));
		return $query->result();
	}

    function data( $jns='' , $bulan='', $tahun='',  $poli='', $kodokter='')
	{

		$cabang = $this->session->userdata('unit');	
		if($jns==1){
			$tanggal = date('Y-m-d');
			$tgll= "and tglmasuk = '$tanggal'";			
		} else {
		    $tgll= "and tglmasuk >= '$bulan' AND tglmasuk<= '$tahun'";
			
		}

		if($poli==''){
			$polii= "";			
		} else {
		    $polii= "and kodepos = '$poli'";
		}		
		
		if($kodokter==''){
			$kodokteri= "";			
		} else {
		    $kodokteri= "and kodokter = '$kodokter'";
		}		

		if($_POST['search']['value']) 
		{
				$search = $_POST['search']['value'];
				$search2= 
				"AND ( diperiksa_perawat LIKE '%$search%' OR
				 diperiksa_dokter LIKE '%$search%' OR
				 jkel LIKE '%$search%' OR
				 jenispas LIKE '%$search%' OR
				 antrino LIKE '%$search%' OR
				 noreg LIKE '%$search%' OR
				 rekmed LIKE '%$search%' OR
				 tglmasuk LIKE '%$search%' OR
				 namapas LIKE '%$search%' OR
				 preposisi LIKE '%$search%' OR
				 preposisi LIKE '%$search%' OR
				 tgllahir LIKE '%$search%' OR
				 nadokter LIKE '%$search%'
				  )";				
		}else{
			$search2='';
		}

		if($_POST['length'] != -1)
		{
			$lim1 = $_POST['start'];
			$lim2 = ','.$_POST['length'];
			$limm = "LIMIT $lim1 $lim2";
		}else{
			$limm = "";
		}
		

		$sql = "SELECT *, CONCAT(namapas, ' ' , preposisi, ' ', DATE_FORMAT(tgllahir, '%d-%m-%Y') ) AS namapasien_lengkap
		FROM $this->table WHERE koders ='$cabang' $polii $kodokteri $tgll $search2 and batal = '0' GROUP BY noreg	DESC $limm
		";
		return $sql;
    
    }

	
	public function getListKodePoli(){
		$query = "SELECT namapost FROM tbl_namapos
		GROUP BY namapost
		order by namapost";
		return $this->db->query($query)->result();
	}
	
	public function getListDokter(){
		$query = "SELECT kodokter 
			FROM pasien_rajal GROUP BY kodokter 
		";
		return $this->db->query($query)->result();
	}
	public function naDokter(){
		$query = "SELECT nadokter 
			FROM pasien_rajal GROUP BY nadokter 
		";
		return $this->db->query($query)->result();
	}
	
	public function getListKoders(){
		$query = "SELECT koders FROM pasien_rajal 
			GROUP BY kodepos
		";
		return $this->db->query($query)->result();
	}
	public function gettglmasuk(){
		$query = "
		SELECT tglmasuk FROM pasien_rajal
		GROUP BY tglmasuk
		";
		return $this->db->query($query)->result();
	}

	public function get_data_byid($id){
	   return $this->db->get_where('pasien_rajal', ['koders' => $id]);
	}
	public function data_filter($tglmasuk, $kodepos, $nadokter, $tgln, $unit){
		$tgln = date('Y-m-d');
		$this->db->limit(200,5);
		$query = $this->db->query("SELECT *, CONCAT(namapas, ' ' , preposisi, ' ', DATE_FORMAT(tgllahir, '%d-%m-%Y') ) AS namapasien_lengkap
        FROM pasien_rajal where
        tglmasuk BETWEEN '$tglmasuk' AND '$tgln' AND kodepos LIKE'%$kodepos%' AND nadokter LIKE'%$nadokter%' AND koders ='$unit' GROUP BY noreg");
		return $query->result();
	}
	public function tampil_data_filter($koders,$unit)
	{
		$this->db->limit(100,5);
		$query = $this->db->query("SELECT * FROM pasien_rajal where koders = '$unit' AND namapost = '$namapost' AND nadokter = '$nadokter'  GROUP BY noreg");
		return $query->result();
	}
    public function total_pasien(){

		$cabang = $this->session->userdata('unit');	
        $tgln   = date('Y-m-d');
        $query = "SELECT count(*)jum
        FROM pasien_rajal
        WHERE koders = '$cabang' AND tglmasuk = '$tgln' and batal = '0'";
        $qry = $this->db->query($query)->result();
		return $qry;
       
    }
    public function diperiksa_perawat(){ 
        $cabang = $this->session->userdata('unit');	
        $tgln   = date('Y-m-d');
        $query = "SELECT count(*)jum
        FROM pasien_rajal
        WHERE koders = '$cabang' AND tglmasuk = '$tgln' AND diperiksa_perawat = 1;";
        $qry = $this->db->query($query)->result();
		return $qry;
    }
    public function diperiksa_dokter(){
		
		$cabang = $this->session->userdata('unit');	
        $tgln   = date('Y-m-d');
        $query = "SELECT count(*)jum
        FROM pasien_rajal
        WHERE koders = '$cabang' AND tglmasuk = '$tgln' AND diperiksa_dokter = 1;";
        $qry = $this->db->query($query)->result();
		return $qry;
    }

	function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
	
		function count_filtered( $jns, $bulan, $tahun, $poli, $kodokter)
	{
		
		$cabang = $this->session->userdata('unit');	
		if($jns==1){
			$tanggal = date('Y-m-d');
			$tgll= "and tglmasuk = '$tanggal'";			
		} else {
		    $tgll= "and tglmasuk >= '$bulan' AND tglmasuk<= '$tahun'";
			
		}

		if($poli==''){
			$polii= "";			
		} else {
		    $polii= "and kodepos = '$poli'";
		}		
		
		if($kodokter==''){
			$kodokteri= "";			
		} else {
		    $kodokteri= "and kodokter = '$kodokter'";
		}		

		if($_POST['search']['value']) 
		{
				$search = $_POST['search']['value'];
				$search2= 
				"AND ( diperiksa_perawat LIKE '%$search%' OR
				diperiksa_dokter LIKE '%$search%' OR
				jkel LIKE '%$search%' OR
				jenispas LIKE '%$search%' OR
				antrino LIKE '%$search%' OR
				noreg LIKE '%$search%' OR
				rekmed LIKE '%$search%' OR
				tglmasuk LIKE '%$search%' OR
				namapas LIKE '%$search%' OR
				preposisi LIKE '%$search%' OR
				preposisi LIKE '%$search%' OR
				tgllahir LIKE '%$search%' OR
				namapost LIKE '%$search%' OR
				nadokter LIKE '%$search%' )";				
		}else{
			$search2='';
		}

		if($_POST['length'] != -1)
		{
			$lim1 = $_POST['start'];
			$lim2 = ','.$_POST['length'];
			$limm = "LIMIT $lim1 $lim2";
		}else{
			$limm = "";
		}
		

		$sql = "SELECT *, CONCAT(namapas, ' ' , preposisi, ' ', DATE_FORMAT(tgllahir, '%d-%m-%Y') ) AS namapasien_lengkap
		FROM $this->table WHERE koders ='$cabang' $polii $kodokteri  $tgll $search2 GROUP BY noreg	
		";
		
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

}


//count