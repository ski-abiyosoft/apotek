<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pendaftaranVRS extends CI_Model {

     public function __construct()
	{
		parent::__construct();
          date_default_timezone_set("Asia/Jakarta");
		$this->load->database();				
	}

	function get_dokter($str)
	{
		$query = $this->db->query("SELECT d.kodokter AS id, CONCAT(d.kodokter,' | ',d.nadokter) AS text FROM tbl_dokter d join tbl_drpoli p on d.kodokter=p.kodokter WHERE d.koders = '".$this->session->userdata('unit')."' and p.kopoli = 'PUGD' and (d.nadokter LIKE '%$str%' OR d.kodokter LIKE '$str%');");
		return $query->result();
	}

	function get_dokter_rj($str)
	{
		$query = $this->db->query("SELECT d.kodokter AS id, CONCAT(d.kodokter,' | ',d.nadokter) AS text FROM tbl_dokter d join tbl_drpoli p on d.kodokter=p.kodokter WHERE d.koders = '".$this->session->userdata('unit')."' and (d.nadokter LIKE '%$str%' OR d.kodokter LIKE '$str%');");
		return $query->result();
	}

	function get_kota2($str)
	{        
		$query = $this->db->query('select * from tbl_kabupaten where namakab like "%'.$str.'%"');
		return $query->result();
	}

	public function datapasien($kode){
		// $query_old = $this->db->query("SELECT tbl_namers.namars, tbl_regist.keluar as keluar, tbl_pasien.*, tbl_propinsi.namaprop, agama.keterangan as namaagama, pendidikan.keterangan as namapendidikan, pekerjaan.keterangan as namapekerjaan, tbl_kabupaten.namakab, tbl_kabupaten.kodekab, tbl_kecamatan.namakec, tbl_desa.namadesa, date(tbl_pasien.tgllahir) as tanggallahir, status.keterangan as namastatus, tbl_pasien.iklinik, tbl_pasien.cekiklinik, tbl_regist.mjkn_token, tbl_regist.jenispas, (select cust_nama from tbl_penjamin where tbl_penjamin.cust_id=tbl_regist.cust_id) as cust_nama, tbl_regist.cust_id, tbl_pasien.wn as wn, tbl_kecamatan.namakec, tbl_kecamatan.kodekec as kecamatan, tbl_regist.jenispas, tbl_regist.titip, tbl_regist.ruangtitip, tbl_regist.kamartitip, (select keterangan from tbl_setinghms where tbl_setinghms.kodeset=tbl_regist.jenispas) as jpas, (select namaruang from tbl_ruangpoli where tbl_ruangpoli.koderuang=tbl_regist.koderuang) as namaruang, tbl_regist.koderuang, tbl_regist.drpengirim, tbl_regist.antrino, tbl_pasien.kodepos as kodepos1, tbl_desa.kodedesa as kelurahan, tbl_regist.nobpjs,tbl_regist.noreg, tbl_regist.kodokter, (select nadokter from tbl_dokter where tbl_dokter.kodokter = tbl_regist.kodokter) as nadokter, (select nadokter from tbl_dokter where tbl_dokter.kodokter = tbl_regist.drpengirim) as pengirim, tbl_pasien.rekmed, tbl_pasien.suku, tbl_regist.kodepos, tbl_regist.norujukan, tbl_regist.nosep from tbl_pasien left join tbl_regist on tbl_regist.rekmed=tbl_pasien.rekmed  left outer join tbl_propinsi on tbl_pasien.propinsi=tbl_propinsi.kodeprop left outer join tbl_kabupaten on tbl_pasien.kabupaten=tbl_kabupaten.kodekab left outer join tbl_kecamatan on tbl_pasien.kecamatan=tbl_kecamatan.kodekec left outer join tbl_desa on tbl_pasien.kelurahan=tbl_desa.kodedesa left outer join tbl_namers on tbl_pasien.koders=tbl_namers.koders left outer join tbl_setinghms as agama on tbl_pasien.agama=agama.kodeset left outer join tbl_setinghms as pendidikan on tbl_pasien.pendidikan=pendidikan.kodeset left outer join tbl_setinghms as pekerjaan on tbl_pasien.pekerjaan=pekerjaan.kodeset left outer join tbl_setinghms as status on tbl_pasien.status=status.kodeset where tbl_pasien.rekmed = '$kode'");
		$query_old = $this->db->query("SELECT tbl_namers.namars, tbl_regist.keluar as keluar, tbl_pasien.*, tbl_propinsi.namaprop, agama.keterangan as namaagama, pendidikan.keterangan as namapendidikan, pekerjaan.keterangan as namapekerjaan, tbl_kabupaten.namakab, tbl_kabupaten.kodekab, tbl_kecamatan.namakec, tbl_desa.namadesa, date(tbl_pasien.tgllahir) as tanggallahir, status.keterangan as namastatus, tbl_pasien.iklinik, tbl_pasien.cekiklinik, tbl_regist.mjkn_token, tbl_regist.jenispas, (select cust_nama from tbl_penjamin where tbl_penjamin.cust_id=tbl_regist.cust_id) as cust_nama, tbl_regist.cust_id, tbl_pasien.wn as wn, tbl_kecamatan.namakec, tbl_kecamatan.kodekec as kecamatan, tbl_regist.jenispas, tbl_regist.titip, tbl_regist.ruangtitip, tbl_regist.tglmasuk, tbl_regist.kamartitip, (select keterangan from tbl_setinghms where tbl_setinghms.kodeset=tbl_regist.jenispas) as jpas, (select namaruang from tbl_ruangpoli where tbl_ruangpoli.koderuang=tbl_regist.koderuang) as namaruang, tbl_regist.koders as koders_regist, tbl_regist.koderuang, tbl_regist.drpengirim, tbl_regist.antrino, tbl_pasien.kodepos as kodepos1, tbl_desa.kodedesa as kelurahan, tbl_regist.nobpjs,tbl_regist.noreg, tbl_regist.kodokter, tbl_pasien.rekmed, tbl_pasien.suku, tbl_regist.kodepos, tbl_regist.norujukan, tbl_regist.nosep from tbl_pasien left join tbl_regist on tbl_regist.rekmed=tbl_pasien.rekmed  left outer join tbl_propinsi on tbl_pasien.propinsi=tbl_propinsi.kodeprop left outer join tbl_kabupaten on tbl_pasien.kabupaten=tbl_kabupaten.kodekab left outer join tbl_kecamatan on tbl_pasien.kecamatan=tbl_kecamatan.kodekec left outer join tbl_desa on tbl_pasien.kelurahan=tbl_desa.kodedesa left outer join tbl_namers on tbl_pasien.koders=tbl_namers.koders left outer join tbl_setinghms as agama on tbl_pasien.agama=agama.kodeset left outer join tbl_setinghms as pendidikan on tbl_pasien.pendidikan=pendidikan.kodeset left outer join tbl_setinghms as pekerjaan on tbl_pasien.pekerjaan=pekerjaan.kodeset left outer join tbl_setinghms as status on tbl_pasien.status=status.kodeset where tbl_pasien.rekmed = '$kode' ORDER BY tglmasuk desc,jam desc");
		if($query_old == false){
			$query = $this->db->query("SELECT p.*, date(p.tgllahir) as tanggallahir, r.koders as koders_regist, p.kodepos as kodepos1, (SELECT s.keterangan FROM tbl_setinghms s WHERE s.kodeset=p.agama) AS namaagama, (SELECT s.keterangan FROM tbl_setinghms s WHERE s.kodeset=p.status) AS STATUS, (SELECT s.keterangan FROM tbl_setinghms s WHERE s.kodeset=p.pekerjaan) AS namapekerjaan, prop.*, kab.*, kec.*, kel.* 
			FROM tbl_pasien p 
			JOIN tbl_regist r ON p.rekmed=r.rekmed 
			JOIN tbl_propinsi prop ON prop.kodeprop=p.propinsi JOIN tbl_kabupaten kab ON kab.kodekab=p.kabupaten JOIN tbl_kecamatan kec ON kec.kodekec=p.kecamatan JOIN tbl_desa kel ON kel.kodedesa=p.kelurahan WHERE p.rekmed = '".$kode."' ");
		} else {
			$query = $query_old;
		}
		$row = $query->row();
		if($row){
          	return $row;
		} else {
		  	return '';	
		}
	}

	// pasien_rajal
	var $tablerj = 'pasien_rajal';
	var $column_orderrj = array('id', 'koders','uidlogin','antrino1','antrino', 'noreg', 'rekmed', 'tglmasuk', 'namapas', 'tujuan', 'nadokter', 'cust_nama', 'jenispas', 'batal', 'keluar', 'nobpjs');
	var $column_searchrj = array('id', 'koders','uidlogin','antrino1','antrino', 'noreg', 'rekmed', 'tglmasuk', 'namapas', 'tujuan', 'nadokter', 'cust_nama', 'jenispas', 'batal', 'keluar', 'nobpjs');
	var $orderrj = array('pasien_rajal.noreg' => 'desc'); 

	private function _get_datatables_query($jns, $bulan, $tahun)
	{
		$cabang = $this->session->userdata('unit');	
		$this->db->select($this->column_orderrj);
		$this->db->from($this->tablerj);
		$this->db->join('userlogin','userlogin.uidlogin=pasien_rajal.username');
		$this->db->where('pasien_rajal.koders', $cabang);
		// $this->db->where('pasien_rajal.ada', 1);
		$this->db->where('pasien_rajal.tujuan', 1);
		// $this->db->where('pasien_rajal.batal', 0);
		$this->db->where('pasien_rajal.kodepos !=', 'PUGD');
		$this->db->group_by('pasien_rajal.id');
		// $this->db->group_by('id');
		if($jns == 1){
			$tanggal = date('Y-m-d');
			$this->db->where(array('pasien_rajal.tglmasuk' => $tanggal, 'pasien_rajal.batal' => 0));
		} else {
		    $this->db->where(array('pasien_rajal.tglmasuk >=' => $bulan,'tglmasuk<= ' => $tahun));
		}
		$i = 0;
		foreach ($this->column_searchrj as $item) 
		{
			if($_POST['search']['value']) 
			{
				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column_searchrj) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_orderrj[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->orderrj))
		{
			$order = $this->orderrj;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
	

     function get_datatables( $jns, $bulan, $tahun)
	{
		$this->_get_datatables_query( $jns, $bulan, $tahun);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $this->input->post('start'));
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($jns, $bulan, $tahun)
	{
		$this->_get_datatables_query($jns, $bulan, $tahun);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all( $jns, $bulan, $tahun )
	{
		$cabang = $this->session->userdata('unit');	
		$this->db->from($this->tablerj);
		$this->db->where('koders', $cabang);
		
		if($jns==1){
			$this->db->where(array('year(tglmasuk)' => $tahun,'month(tglmasuk)' => $bulan));
		} else {
			$this->db->where(array('tglmasuk >=' => $bulan,'tglmasuk<= ' => $tahun));
			
		}
		
		
		return $this->db->count_all_results();
	}

	// pasien_igd
	var $tableigd = 'pasien_rajal';
	var $column_orderigd = array('id', 'koders','uidlogin','antrino', 'noreg', 'rekmed', 'tglmasuk', 'namapas', 'tujuan', 'nadokter', 'cust_nama', 'jenispas', 'batal', 'keluar');
	var $column_searchigd = array('id', 'koders','uidlogin','antrino', 'noreg', 'rekmed', 'tglmasuk', 'namapas', 'tujuan', 'nadokter', 'cust_nama', 'jenispas', 'batal', 'keluar');
	var $orderigd = array('pasien_rajal.koders' => 'asc'); 

	private function _get_datatables_query_igd($jns, $bulan, $tahun)
	{
		$cabang = $this->session->userdata('unit');	
		$this->db->select($this->column_orderigd);
		$this->db->from($this->tableigd);
		$this->db->join('userlogin','userlogin.uidlogin=pasien_rajal.username');
		$this->db->where('pasien_rajal.koders', $cabang);
		$this->db->where('pasien_rajal.tujuan', 1);
		$this->db->where('pasien_rajal.kodepos', 'PUGD');
		$this->db->group_by('pasien_rajal.id');
		if($jns == 1){
			$tanggal = date('Y-m-d');
			$this->db->where(array('pasien_rajal.tglmasuk' => $tanggal));
		} else {
		    $this->db->where(array('pasien_rajal.tglmasuk >=' => $bulan,'tglmasuk<= ' => $tahun));
		}
		$i = 0;
		foreach ($this->column_searchigd as $item) 
		{
			if($_POST['search']['value']) 
			{
				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column_searchigd) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_orderigd[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->orderigd))
		{
			$order = $this->orderigd;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}	

     function get_datatables_igd( $jns, $bulan, $tahun){
		$this->_get_datatables_query_igd( $jns, $bulan, $tahun);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $this->input->post('start'));
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered_igd($jns, $bulan, $tahun)
	{
		$this->_get_datatables_query_igd($jns, $bulan, $tahun);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_igd( $jns, $bulan, $tahun )
	{
		$cabang = $this->session->userdata('unit');	
		$this->db->from($this->tableigd);
		$this->db->where('koders', $cabang);
		
		if($jns==1){
			$this->db->where(array('year(tglmasuk)' => $tahun,'month(tglmasuk)' => $bulan));
		} else {
			$this->db->where(array('tglmasuk >=' => $bulan,'tglmasuk<= ' => $tahun));
			
		}
		
		
		return $this->db->count_all_results();
	}

	// RI
	var $tableri = 'pasien_ranap';
	var $column_orderri = array('koders', 'noreg', 'rekmed', 'tglmasuk', 'jam', 'jenispas', 'tujuan', 'kodepos', 'cust_id', 'kodokter', 'antrino', 'namapas', 'preposisi', 'tgllahir', 'jkel', 'keluar', 'nadokter', 'cust_nama', 'diperiksa_perawat', 'diperiksa_dokter', 'batal', 'baru', 'tglkeluar', 'jamkeluar', 'drpengirim', 'username', 'shipt', 'id', 'handphone', 'namaruang', 'kelas', 'namakamar', 'namakelas');
	var $column_searchri = array('koders', 'noreg', 'rekmed', 'tglmasuk', 'jam', 'jenispas', 'tujuan', 'kodepos', 'cust_id', 'kodokter', 'antrino', 'namapas', 'preposisi', 'tgllahir', 'jkel', 'keluar', 'nadokter', 'cust_nama', 'diperiksa_perawat', 'diperiksa_dokter', 'batal', 'baru', 'tglkeluar', 'jamkeluar', 'drpengirim', 'username', 'shipt', 'id', 'handphone', 'namaruang', 'kelas', 'namakamar', 'namakelas');
	// var $column_orderri = array('id', 'koders','uidlogin','antrino', 'noreg', 'rekmed', 'tglmasuk', 'namapas', 'tujuan', 'nadokter', 'cust_nama', 'jenispas', 'batal', 'keluar');
	// var $column_searchri = array('id', 'koders','uidlogin','antrino', 'noreg', 'rekmed', 'tglmasuk', 'namapas', 'tujuan', 'nadokter', 'cust_nama', 'jenispas', 'batal', 'keluar');
	var $orderri = array('koders' => 'asc'); 

	private function _get_datatables_query_ri($jns, $bulan, $tahun)
	{
		$cabang = $this->session->userdata('unit');	
		$this->db->select($this->column_orderri);
		$this->db->from($this->tableri);
		$this->db->where('koders', $cabang);
		$this->db->where('keluar', 0);
		if($jns == 1){
			$tanggal = date('Y-m-d');
			$this->db->where(array('tglmasuk' => $tanggal));
		} else {
		    $this->db->where(array('tglmasuk >=' => $bulan,'tglmasuk<= ' => $tahun));
		}
		$i = 0;
		foreach ($this->column_searchri as $item) 
		{
			if($_POST['search']['value']) 
			{
				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column_searchri) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_orderri[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->orderri))
		{
			$order = $this->orderri;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}	

     function get_datatables_ri( $jns, $bulan, $tahun){
		$this->_get_datatables_query_ri( $jns, $bulan, $tahun);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $this->input->post('start'));
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered_ri($jns, $bulan, $tahun)
	{
		$this->_get_datatables_query_ri($jns, $bulan, $tahun);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_ri( $jns, $bulan, $tahun )
	{
		$cabang = $this->session->userdata('unit');	
		$this->db->from($this->tableri);
		$this->db->where('koders', $cabang);
		
		if($jns==1){
			$this->db->where(array('year(tglmasuk)' => $tahun,'month(tglmasuk)' => $bulan));
		} else {
			$this->db->where(array('tglmasuk >=' => $bulan,'tglmasuk<= ' => $tahun));
			
		}
		
		
		return $this->db->count_all_results();
	}

	public function save($data)
	{
		$this->db->insert('tbl_pasien', $data);
		return $this->db->insert_id();
	}
}