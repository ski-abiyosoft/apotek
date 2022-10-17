<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_akuntansi_sa extends CI_Model {

   
	var $table = 'tbl_coa_saldo';
	var $column_order = array('accountno', 'debet','credit',null); 
	var $column_search = array('accountno','debet','credit'); 
	var $order = array('accountno' => 'asc'); 
		
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		
	}

	private function _get_datatables_query( $bulan, $tahun )
	{
		//$this->db->from($this->table);		
		$unit =  $this->session->userdata('unit');				
		$this->db->select('tbl_accounting.accountno, tbl_accounting.acname, tbl_coa_saldo.debet, tbl_coa_saldo.credit, tbl_coa_saldo.id')->from('tbl_accounting');
		$this->db->join('tbl_coa_saldo','tbl_coa_saldo.accountno=tbl_accounting.accountno','left');
		$this->db->where(array('tbl_coa_saldo.tahun' => $tahun,'tbl_coa_saldo.bulan' => $bulan, 'tbl_coa_saldo.koders' => $unit));
		
		$i = 0;
		
	    
		foreach ($this->column_search as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				
				if($i===0) // first loop
				{
					
					//$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

			
					
			}
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	
	
	function get_datatables( $bulan, $tahun )
	{
		
		$this->_get_datatables_query( $bulan, $tahun );
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);	
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered( $bulan, $tahun )
	{
		$this->_get_datatables_query( $bulan, $tahun );
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('id',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where('id', $id);
		$this->db->delete($this->table);
	}

	public function kasperiode(){
		$query = "
			SELECT kasperiode
			FROM tbl_periode
			;";
	
		$qry = $this->db->query($query)->num_rows();

		if($qry > 0){
			return $this->db->query($query)->result();
		} else {
			return false;
		}
	}
	
    public function total_kas_masuk(string $start_date = null, string $end_date = null){
        $koders =  $this->session->userdata('unit');
		$start_date = $start_date ?? $this->kasperiode()[0]->kasperiode;
		$end_date = $end_date ?? date('Y-m-d');
        
		$query = 
            "SELECT (
				SELECT COALESCE(SUM(nilairp),0)
				FROM tbl_kasmasuk
				WHERE koders = '$koders' 
					AND tglkas >= '$start_date, $end_date' AND tglkas <= '$end_date'
			) + (
				SELECT COALESCE(SUM(totalterima),0)
				FROM tbl_harpas
				WHERE koders = '$koders'
					AND ardate >= '$start_date, $end_date' AND ardate <= '$end_date'
			) + (
				SELECT COALESCE(SUM(mutasirp),0)
				FROM tbl_mutasikas
				WHERE koders = '$koders'
					AND tglmutasi >= '$start_date, $end_date' AND tglmutasi <= '$end_date'
			) AS kas_bank_masuk
			;
        ";

		$qry = $this->db->query($query)->num_rows();

		if($qry > 0){
			return $this->db->query($query)->result();
		} else {
			return false;
		}
	}

	
    public function total_kas_keluar($start_date = null, $end_date = null){
        $koders =  $this->session->userdata('unit');
		$start_date = $start_date ?? $this->kasperiode()[0]->kasperiode;	
		$end_date = $end_date ?? date('Y-m-d');	

        $query = 
            "SELECT (
				SELECT COALESCE(SUM(jmbayar),0)
				FROM tbl_hbayar
				WHERE koders = '$koders'
					AND tglbayar >= '$start_date = null, $end_date = null' AND tglbayar <= '$end_date'
			) + (
				SELECT COALESCE(SUM(totalbayar),0)
				FROM tbl_hap
				WHERE koders = '$koders'
					AND pay_date >= '$start_date = null, $end_date = null' AND pay_date <= '$end_date'
			) + (
				SELECT COALESCE(SUM(mutasirp),0)
				FROM tbl_mutasikas
				WHERE koders = '$koders'
					AND tglmutasi >= '$start_date = null, $end_date = null' AND tglmutasi <= '$end_date'
			) AS kas_bank_keluar;
        ";

		$qry = $this->db->query($query)->num_rows();

		if($qry > 0){
			return $this->db->query($query)->result();
		} else {
			return false;
		}
	}
    
	public function getDataKasBank($kasperiode, $startdate, $enddate){
		$koders =  $this->session->userdata('unit');	

        $query = 
            "SELECT a.accountno, CONCAT(a.accountno, ' ', a.acname) AS akun_kas_bank,
				COALESCE(masuk.nilairp,0) AS masuk,
				COALESCE(keluar.nilairp,0) AS keluar,
				COALESCE(masuk.nilairp,0) - COALESCE(keluar.nilairp,0) + COALESCE(sa.saldo_awal,0) AS saldo_awal,
				-- COALESCE(masuk.nilairp,0) - COALESCE(keluar.nilairp,0) AS saldo_awal,
				COALESCE(sa.saldo_awal,0) AS saldo_awal_backup,
				COALESCE(km.kas_masuk,0) AS kas_masuk,
				COALESCE(kk.kas_keluar,0) AS kas_keluar,
				COALESCE(masuk.nilairp,0) - COALESCE(keluar.nilairp,0) + COALESCE(sa.saldo_awal,0) + COALESCE(km.kas_masuk,0) - COALESCE(kk.kas_keluar,0) AS saldo_akhir,
				COALESCE(sa.saldo_awal,0) + COALESCE(km.kas_masuk,0) - COALESCE(kk.kas_keluar,0) AS saldo_akhir_backup
			FROM tbl_accounting a 
				LEFT JOIN (
					SELECT accountno, SUM(nilairp) AS nilairp
					FROM (
						SELECT accountno, COALESCE(SUM(nilairp),0) AS nilairp
						FROM tbl_kasmasuk
						WHERE koders = '$koders'
							AND tglkas >= '$kasperiode' AND tglkas < '$startdate'
						GROUP BY accountno

						UNION ALL
						
						SELECT accountno, COALESCE(SUM(totalterima),0) AS nilairp
						FROM tbl_harpas
						WHERE koders = '$koders'
							AND ardate >= '$kasperiode' AND ardate < '$startdate'
						GROUP BY accountno
						
						UNION ALL
						
						SELECT acke AS accountno, COALESCE(SUM(mutasirp),0) AS nilairp
						FROM tbl_mutasikas
						WHERE koders = '$koders'
							AND tglmutasi >= '$kasperiode' AND tglmutasi < '$startdate'
						GROUP BY acke
					) AS kas_bank_masuk
					GROUP BY accountno
				) AS masuk ON a.accountno = masuk.accountno
				LEFT JOIN (			
					SELECT accountno, SUM(nilairp) AS nilairp
					FROM (
						SELECT accountno, COALESCE(SUM(jmbayar),0) AS nilairp
						FROM tbl_hbayar
						WHERE koders = '$koders'
							AND tglbayar >= '$kasperiode' AND tglbayar < '$startdate'
						GROUP BY accountno

						UNION ALL

						SELECT accountno, COALESCE(SUM(totalbayar),0) AS nilairp
						FROM tbl_hap
						WHERE koders = '$koders'
							AND pay_date >= '$kasperiode' AND pay_date < '$startdate'
						GROUP BY accountno

						UNION ALL

						SELECT acdari AS accountno, COALESCE(SUM(mutasirp),0) AS nilairp
						FROM tbl_mutasikas
						WHERE koders = '$koders'
							AND tglmutasi >= '$kasperiode' AND tglmutasi < '$startdate'
						GROUP BY acdari
					) AS kas_bank_keluar
					GROUP BY accountno
				) AS keluar ON a.accountno = keluar.accountno
				LEFT JOIN (
					SELECT accountno, SUM(nilairp) AS saldo_awal
					FROM tbl_kasmasuk
					WHERE koders = '$koders' 
						AND tglkas >= '$startdate' AND tglkas <= '$enddate'
					GROUP BY accountno
				) AS sa ON a.`accountno` = sa.accountno
				LEFT JOIN (
					SELECT accountno, SUM(nominal) AS kas_masuk
					FROM (
						-- SELECT accountno, COALESCE(SUM(nilairp),0) AS nominal
						-- FROM tbl_kasmasuk
						-- WHERE koders = '$koders'
						-- 	AND tglkas >= '$kasperiode' AND tglkas < '$startdate'
						-- GROUP BY accountno

						-- UNION ALL

						SELECT acke AS accountno, SUM(mutasirp) AS nominal
						FROM tbl_mutasikas
						WHERE koders = '$koders' 
							AND tglmutasi >= '$startdate' AND tglmutasi <= '$enddate'
						GROUP BY acke

						UNION ALL

						SELECT accountno, SUM(totalterima) AS nominal
						FROM tbl_harpas
						WHERE koders = '$koders'
							AND ardate >= '$startdate' AND ardate <= '$enddate'
						GROUP BY accountno
					) AS dt
					GROUP BY accountno
				) AS km ON a.`accountno` = km.accountno
				LEFT JOIN (		
					SELECT accountno, SUM(nominal) AS kas_keluar
					FROM (
						SELECT acdari AS accountno, SUM(mutasirp) AS nominal
						FROM tbl_mutasikas
						WHERE koders = '$koders' 
							AND tglmutasi >= '$startdate' AND tglmutasi <= '$enddate'
						GROUP BY acdari

						UNION ALL

						SELECT accountno, SUM(jmbayar) AS nominal
						FROM tbl_hbayar
						WHERE koders = '$koders'
							AND tglbayar >= '$startdate' AND tglbayar <= '$enddate'
						GROUP BY accountno
						
						UNION ALL
						
						SELECT accountno, SUM(totalbayar) AS nominal
						FROM tbl_hap
						WHERE koders = '$koders'
							AND pay_date >= '$startdate' AND pay_date <= '$enddate'
						GROUP BY accountno
					) AS dt
					GROUP BY accountno
				) AS kk ON a.`accountno` = kk.accountno
			WHERE a.kasbank = 1
			;
        ";

		$qry = $this->db->query($query)->num_rows();

		if($qry > 0){
			return $this->db->query($query)->result();
		} else {
			return false;
		}
	}

	public function getDataKasBank_backup($startdate, $enddate){
		$koders =  $this->session->userdata('unit');	

        $query = 
            "SELECT a.accountno, CONCAT(a.accountno, ' ', a.acname) AS akun_kas_bank,
				COALESCE(sa.saldo_awal,0) AS saldo_awal,
				COALESCE(km.kas_masuk,0) AS kas_masuk,
				COALESCE(kk.kas_keluar,0) AS kas_keluar,
				COALESCE(sa.saldo_awal,0) + COALESCE(km.kas_masuk,0) - COALESCE(kk.kas_keluar,0) AS saldo_akhir
			FROM tbl_accounting a 
				LEFT JOIN (
					SELECT accountno, SUM(nilairp) AS saldo_awal
					FROM tbl_kasmasuk
					WHERE koders = '$koders' 
						AND tglkas >= '$startdate' AND tglkas <= '$enddate'
					GROUP BY accountno
				) AS sa ON a.`accountno` = sa.accountno
				LEFT JOIN (
					SELECT accountno, SUM(nominal) AS kas_masuk
					FROM (
						SELECT acke AS accountno, SUM(mutasirp) AS nominal
						FROM tbl_mutasikas
						WHERE koders = '$koders' 
							AND tglmutasi >= '$startdate' AND tglmutasi <= '$enddate'
						GROUP BY acke

						UNION ALL

						SELECT accountno, SUM(totalterima) AS nominal
						FROM tbl_harpas
						WHERE koders = '$koders'
							AND ardate >= '$startdate' AND ardate <= '$enddate'
						GROUP BY accountno
					) AS dt
					GROUP BY accountno
				) AS km ON a.`accountno` = km.accountno
				LEFT JOIN (		
					SELECT accountno, SUM(nominal) AS kas_keluar
					FROM (
						SELECT acdari AS accountno, SUM(mutasirp) AS nominal
						FROM tbl_mutasikas
						WHERE koders = '$koders' 
							AND tglmutasi >= '$startdate' AND tglmutasi <= '$enddate'
						GROUP BY acdari

						UNION ALL

						SELECT accountno, SUM(jmbayar) AS nominal
						FROM tbl_hbayar
						WHERE koders = '$koders'
							AND tglbayar >= '$startdate' AND tglbayar <= '$enddate'
						GROUP BY accountno
						
						UNION ALL
						
						SELECT accountno, SUM(totalbayar) AS nominal
						FROM tbl_hap
						WHERE koders = '$koders'
							AND pay_date >= '$startdate' AND pay_date <= '$enddate'
						GROUP BY accountno
					) AS dt
					GROUP BY accountno
				) AS kk ON a.`accountno` = kk.accountno
			WHERE a.kasbank = 1
			;
        ";

		$qry = $this->db->query($query)->num_rows();

		if($qry > 0){
			return $this->db->query($query)->result();
		} else {
			return false;
		}
	}

	
	public function getDetailDataKasBank($accountno, $startdate, $enddate){
		$koders =  $this->session->userdata('unit');	

        $query = "CALL mutasi_kas('$koders','$accountno', '$startdate','$enddate')";
		$result = $this->db->query($query)->result();
		$this->db->close();

		if(count($result) != 0){
			return $result;
		} else {
			return false;
		}
	}

}

