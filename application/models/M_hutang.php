<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_hutang extends CI_Model {

	var $table = 'tbl_apoap';

	public function __construct()
	{
		parent::__construct();
		// $this->load->database();
		$this->load->library('collection');
		$this->load->library('timefication');
	}

	public function getHutangById($id){
		$query = "
			SELECT a.*, v.vendor_name
			FROM tbl_apoap a LEFT JOIN tbl_vendor v ON a.vendor_id = v.vendor_id
			WHERE a.terima_no = '$id'
		";
		$qry = $this->db->query($query)->num_rows();

		if($qry > 0){
			return $this->db->query($query)->result();
		} else {
			return false;
		}
	}

	public function getTukarFakturById($id){
		$query = "
			SELECT a.*, v.vendor_name
			FROM tbl_hfaktur a LEFT JOIN tbl_vendor v ON a.vendor_id = v.vendor_id
			WHERE a.notukar = '$id'
		";
		$qry = $this->db->query($query)->num_rows();

		if($qry > 0){
			return $this->db->query($query)->result();
		} else {
			return false;
		}
	}

	
	public function getDetailTukarFakturById($id){
		$query = "
			SELECT a.*, v.vendor_name
			FROM tbl_apoap a
				LEFT JOIN tbl_vendor v ON a.vendor_id = v.vendor_id
			WHERE a.notukar = '$id'
		";
		$qry = $this->db->query($query)->num_rows();

		if($qry > 0){
			return $this->db->query($query)->result();
		} else {
			return false;
		}
	}

	public function totalHutang($koders, $vendor, $startdate="", $enddate="")
	{
		$vndr = '';
		$tgl = '';
		if($vendor != ''){
			$vndr = "AND vendor_id = '$vendor'";
		}
		if($startdate != '' && $enddate != ''){
			$tgl = "AND tglinvoice >= '$startdate' AND tglinvoice <= '$enddate'";
		}

        $query = "SELECT COALESCE(SUM(totaltagihan - totalbayar),0) AS total_hutang
            FROM tbl_apoap
            WHERE koders = '$koders' AND lunas = 0 $vndr $tgl
            ;";
        return $this->db->query($query)->result();
    }

	public function detailTotalHutang($koders, $vendor, $startdate="", $enddate="")
	{
		$vndr = '';
		$tgl = '';
		if($vendor != ''){
			$vndr = "AND a.vendor_id = '$vendor'";
		}
		if($startdate != '' && $enddate != ''){
			$tgl = "AND DATE(tglinvoice) >= '$startdate' AND DATE(tglinvoice) <= '$enddate'";
		}

        $query = "SELECT a.*, v.vendor_name
            FROM tbl_apoap a LEFT JOIN tbl_vendor v ON a.vendor_id = v.vendor_id
            WHERE koders = '$koders' AND lunas = 0 $vndr 
            ;";
        return $this->db->query($query)->result();
    }

    public function hutangJatuhTempo($koders, $vendor, $startdate="", $enddate="")
	{
		$vndr = '';
		$tgl = '';
		$now = date("Y-m-d");
		if($vendor != ''){
			$vndr = "AND vendor_id = '$vendor'";
		}
		if($startdate != '' && $enddate != ''){
			$tgl = "AND duedate <= '$enddate'";
		}

        $query = "SELECT COALESCE(SUM(COALESCE(totaltagihan,0) - COALESCE(totalbayar,0)),0) AS hutang_jatuh_tempo
            FROM tbl_apoap
            WHERE koders = '$koders' AND lunas = 0 $vndr $tgl AND lunas = 0
            ;";
        return $this->db->query($query)->result();
    }

	public function detailHutangJatuhTempo($koders, $vendor, $startdate="", $enddate="")
	{
		$vndr = '';
		$tgl = '';
		$now = date('Y-m-d');
		if($vendor != ''){
			$vndr = "AND a.vendor_id = '$vendor'";
		}
		if($startdate != '' && $enddate != ''){
			$tgl = "AND DATE(tglinvoice) >= '$startdate' AND DATE(tglinvoice) <= '$enddate'";
		}

        $query = "SELECT a.*, v.vendor_name
		FROM tbl_apoap a LEFT JOIN tbl_vendor v ON a.vendor_id = v.vendor_id
            WHERE koders = '$koders' AND lunas = 0 $vndr 
                AND duedate <= '$now'
            ;";
        return $this->db->query($query)->result();
    }

    public function rencanaBayar(string $koders, string $startdate, string $enddate): array
	{
		return $this->db->select_sum('totalsemua', 'rencana_bayar')->where([
			'tanggal >=' => $startdate,
			'tanggal <=' => $enddate,
			'koders' => $koders
		])->get('tbl_hfaktur')->result();
    }

	public function detailRencanaBayar($koders, $vendor, $startdate, $enddate)
	{
		$vndr = '';
		$tgl = '';
		$tgl_rencana_bayar = '';
		if($vendor != ''){
			$vndr = "AND a.vendor_id = '$vendor'";
		}
		if($startdate != '' && $enddate != ''){
			$tgl 				= "AND tglinvoice >= '$startdate' AND tglinvoice <= '$enddate'";
			// $tgl_rencana_bayar  = "AND tglrencanabayar >= '$startdate' AND tglrencanabayar <= '$enddate'";
		}

        $query = "SELECT a.*, v.vendor_name
			FROM tbl_apoap a LEFT JOIN tbl_vendor v ON a.vendor_id = v.vendor_id
            WHERE koders = '$koders' AND lunas = 0 $vndr $tgl_rencana_bayar $tgl";
        return $this->db->query($query)->result();
    }

    public function realisasiPembayaran($koders, $vendor, $startdate, $enddate)
	{
		$vndr = '';
		$tgl = '';
		if($vendor != ''){
			$vndr = "AND vendor_id = '$vendor'";
		}
		if($startdate != '' && $enddate != ''){
			$tgl = "AND pay_date >= '$startdate' AND pay_date <= '$enddate'";
		}
		
        $query = "SELECT SUM(totalbayar) as realisasi_pembayaran
            FROM tbl_hap
            WHERE koders = '$koders' $vndr $tgl";
        
        return $this->db->query($query)->result();
    }

	
    public function detailRealisasiPembayaran($koders, $vendor, $startdate, $enddate)
	{
		$vndr = '';
		$tgl = '';
		if($vendor != ''){
			$vndr = "AND h.vendor_id = '$vendor'";
		}
		if($startdate != '' && $enddate != ''){
			$tgl = "AND pay_date >= '$startdate' AND pay_date <= '$enddate'";
		}
		
        $query = "SELECT h.*, h.ket AS keterangan, d.*, d.due_date AS duedate, d.totalhutang AS totaltagihan, v.vendor_name 
			FROM tbl_hap h 
				LEFT JOIN tbl_dap d ON h.ap_id = d.ap_id
				LEFT JOIN tbl_vendor v ON h.vendor_id = v.vendor_id
            WHERE h.koders = '$koders' $vndr $tgl";
        
        return $this->db->query($query)->result();
    }

	public function getTukarFaktur($koders, $startdate, $enddate, $vendor){
		$vndr = '';
		$tgl = '';
		$now = date('Y-m-d');
		if($vendor != ''){
			$vndr = "AND h.vendor_id = '$vendor'";
		}
		if($startdate != '' && $enddate != ''){
			$tgl = "AND tanggal >= '$startdate' AND tanggal <= '$enddate'";
		} else {
			$tgl = "AND tanggal <= '$now'";
		}
		
        $query = "SELECT h.*, v.vendor_name
			FROM tbl_hfaktur h INNER JOIN tbl_vendor v ON h.vendor_id =  v.vendor_id
			WHERE koders = '$koders' $tgl $vndr
			;";
		return $this->db->query($query)->result();
		
	}

	public function getTagihan($koders, $vendor, $startdate, $enddate){	
		$vndr = '';
		if($vendor != ''){
			$vndr = "AND a.vendor_id = '$vendor'";
		}
		$tgl = '';
		if($startdate != '' && $enddate != ''){
			$tgl = "AND tglinvoice >= '$startdate' AND tglinvoice <= '$enddate'";
		}

        $query = "SELECT *
			FROM tbl_apoap a INNER JOIN tbl_vendor v ON BINARY a.vendor_id =  v.vendor_id
			WHERE koders = '$koders' $vndr $tgl
			;";
			// AND tukarfaktur = 0
		return $this->db->query($query)->result();	
	}

	public function getTagihanById($unit, $dt, $vendor){		
		$terima_no = '';
		for($i=0; $i<count($dt); $i++){
			if($i==0) $terima_no .= "'$dt[$i]'"; 
			else $terima_no .= ",'$dt[$i]'";
		}

		$terima_no = " AND terima_no IN (".$terima_no.")";

		$vndr = '';
		if($vendor != ''){
			$vndr = "AND a.vendor_id = '$vendor'";
		}

		$query = "SELECT *
		FROM tbl_apoap a INNER JOIN tbl_vendor v ON BINARY a.vendor_id =  v.vendor_id
		WHERE koders = '$unit'  $terima_no $vndr
		;";
		// AND tukarfaktur = 0
		return $this->db->query($query)->result();	
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

	/**
	 * Method untuk mendapatakan data saldo hutang masing-masing vendor
	 * 
	 * @param string $startperiod
	 * @param array $koders
	 * @return array
	 */
	public function vendor_balances (string $startperiod, array $koders): array
	{
		$vendors = $this->db->select("vendor_id, vendor_name, CONCAT(TRIM(alamat1), ' ', TRIM(alamat2), ' ', TRIM(alamat3)) as alamat")->from('tbl_vendor')->get()->result();

		$query = $this->db->select('vendor_id, SUM(totaltagihan) as starting_balance')
					->where(['tglinvoice <' => $startperiod])->where_in('koders', $koders);

		$starting_balances = $this->collection->collect($query->group_by('vendor_id')->get('tbl_apoap')->result());

		for ($i = 0; $i < count($vendors); $i++){
			$vendors[$i]->starting_balance = $starting_balances->where('vendor_id', '=', $vendors[$i]->vendor_id)->first()->starting_balance ?? 0;
		}

		return $vendors;
	}

	/**
	 * Method untuk mendapatkan data transaksi hutang selama periode
	 * 
	 * @param string $startdate $enddate
	 * @param array $koders
	 * @return Collection
	 */
	public function vendor_transactions (string $startperiod, string $endperiod, array $koders)
	{
		ini_set('memory_limit', '-1');

		$query = $this->db->from('tbl_apoap')->where('tglinvoice >=', $startperiod)->where('tglinvoice <=', $endperiod)
					->where_in('koders', $koders);

		return $this->collection->collect($query->get()->result());
	}

	/**
     * Method for getting payable card report
     * 
     * @param string $start_date, $end_date, $vendor
     * @param array koders
     * @return array
     */
    public function get_mutation_records (string $start_date, string $end_date, string $vendor, array $koders) 
    {	
		$unit_list = array_map(function ($item) {
			return "'$item'";
		}, $koders);

		$unit_str = implode(',', $unit_list);
		
		return $this->db->query(
			"SELECT 
				*
			FROM (
				SELECT 
					tglinvoice as tanggal,
					terima_no as no_bukti,
					CASE WHEN totaltagihan < 0 THEN (totaltagihan * -1) ELSE 0 END as debet,
					CASE WHEN totaltagihan > 0 THEN totaltagihan ELSE 0 END as kredit
				FROM 
					tbl_apoap
				WHERE
					tglinvoice >= '$start_date'
					AND
					tglinvoice <= '$end_date'
					AND
					koders IN ($unit_str)
					AND
					vendor_id = '$vendor'
				UNION ALL
				SELECT 
					pay_date as tanggal,
					head.ap_id as no_bukti,
					dibayar as debet,
					0 as kredit
					FROM
						tbl_hap as head
					JOIN
						tbl_dap as detail
							ON
						head.ap_id = detail.ap_id
					WHERE 
						pay_date >= '$start_date'
						AND
						pay_date <= '$end_date'
						AND
						koders IN ($unit_str)
						AND
						vendor_id = '$vendor'
			) as mutation
			ORDER BY
				tanggal ASC
			"
		)->result();
    }

	/**
	 * Method untuk mendapatkan data aging hutang
	 * 
	 * @param string $endperiod
	 * @param array $koders
	 * @return Collection
	 */
	public function ap_aging (string $endperiod, array $koders)
	{
		$endperiod = $this->timefication->init($endperiod);
		$now = $endperiod->format('Y-m-d');
		$_30_days = $endperiod->reduce('30 days')->format('Y-m-d');
		$_60_days = $endperiod->reduce('60 days')->format('Y-m-d');
		$_90_days = $endperiod->reduce('90 days')->format('Y-m-d');

		$vendors = $this->db->select('vendor_id, vendor_name')->get('tbl_vendor')->result();

		$unexpired = $this->db->select('vendor_id, SUM(totaltagihan - totalbayar) as jumlah')->from('tbl_apoap')->where(['duedate >' => $now])->where_in('koders', $koders)->group_by('vendor_id')->get()->result();
		$less_than_30_days = $this->db->select('vendor_id, SUM(totaltagihan - totalbayar) as jumlah')->from('tbl_apoap')->where(['duedate <=' => $now, 'duedate >=' => $_30_days])->where_in('koders', $koders)->group_by('vendor_id')->get()->result();
		$between_30_and_60_days = $this->db->select('vendor_id, SUM(totaltagihan - totalbayar) as jumlah')->from('tbl_apoap')->where(['duedate <' => $_30_days, 'duedate >=' => $_60_days])->where_in('koders', $koders)->group_by('vendor_id')->get()->result();
		$between_60_and_90_days = $this->db->select('vendor_id, SUM(totaltagihan - totalbayar) as jumlah')->from('tbl_apoap')->where(['duedate <' => $_60_days, 'duedate >=' => $_90_days])->where_in('koders', $koders)->group_by('vendor_id')->get()->result();
		$more_than_90_days = $this->db->select('vendor_id, SUM(totaltagihan - totalbayar) as jumlah')->from('tbl_apoap')->where(['duedate <' => $_90_days])->where_in('koders', $koders)->group_by('vendor_id')->get()->result();

		for ($i = 0; $i < count($vendors); $i++){
			$vendors[$i]->unexpired = $this->collection->collect($unexpired)->where('vendor_id', '=', $vendors[$i]->vendor_id)->first()->jumlah ?? 0;
			$vendors[$i]->less_than_30_days = $this->collection->collect($less_than_30_days)->where('vendor_id', '=', $vendors[$i]->vendor_id)->first()->jumlah ?? 0;
			$vendors[$i]->between_30_and_60_days = $this->collection->collect($between_30_and_60_days)->where('vendor_id', '=', $vendors[$i]->vendor_id)->first()->jumlah ?? 0;
			$vendors[$i]->between_60_and_90_days = $this->collection->collect($between_60_and_90_days)->where('vendor_id', '=', $vendors[$i]->vendor_id)->first()->jumlah ?? 0;
			$vendors[$i]->more_than_90_days = $this->collection->collect($more_than_90_days)->where('vendor_id', '=', $vendors[$i]->vendor_id)->first()->jumlah ?? 0;
		}

		return $this->collection->collect($vendors);
	}

	/**
	 * Method untuk mendapatkan data transaksi
	 * 
	 * @param string $fromdate $todate $koders
	 * @return Collection
	 */
	public function ap_transaction (string $fromdate, string $todate, string $koders = '')
	{
		// ini_set('memory_limit', -1);
		// $vendors = $this->db->select('vendor_id, vendor_name')->get('tbl_vendor')->result();
		// $ap_transactions = $this->db->select('tgl_invoice, SUM(total)')

		// return $this->collection->collect($vendors);
	}

	/**
	 * Method untuk mendapatkan vendor yang sudah bertransaksi
	 * 
	 * @return array
	 */
	public function vendors (): array
	{
		return $this->db->select('DISTINCT(tbl_apoap.vendor_id), vendor_name')->from('tbl_apoap')->join('tbl_vendor', 'tbl_vendor.vendor_id = tbl_apoap.vendor_id')
				->get()->result();
	}

	/**
	 * Method for getting  ap summary
	 * 
	 * @param string $start_date, $end_date, $koders
	 */
	public function get_ap_summary (string $start_date, string $end_date, string $koders)
	{
		$vendors = $this->db->select('vendor_id, vendor_name')->get('tbl_vendor')->result();

		for ($i = 0; $i < count($vendors); $i++) {
			$vendors[$i]->saldo_awal = $this->db->select('SUM(totaltagihan - totalbayar) as saldo_awal')->where([
				'vendor_id' => trim($vendors[$i]->vendor_id),
				'koders' => $koders,
				'tglinvoice <' => $start_date
			])->get('tbl_apoap')->row()->saldo_awal ?? 0;

			$vendors[$i]->totaltagihan = $this->db->select('ta.vendor_id')->select_sum('totaltagihan')->from('tbl_apoap as ta')
			->where([
			  'koders' => $koders,
			  'tglinvoice >=' => $start_date,
			  'tglinvoice <=' => $end_date,
			  'vendor_id' => trim($vendors[$i]->vendor_id)
		  ])->group_by('ta.vendor_id')->get()->row()->totaltagihan ?? 0;
			$vendors[$i]->totalbayar = $this->db->select('ta.vendor_id')->select_sum('totalbayar')->from('tbl_apoap as ta')
			->where([
			  'koders' => $koders,
			  'tglinvoice >=' => $start_date,
			  'tglinvoice <=' => $end_date,
			  'vendor_id' => trim($vendors[$i]->vendor_id)
		  ])->group_by('ta.vendor_id')->get()->row()->totalbayar ?? 0;
		}

		return $vendors;
	}

	/**
	 * Method for getting vendor detail transactions
	 * 
	 * @param string $start_date, $end_date, $koders
	 */
	public function get_vendor_details (string $start_date, string $end_date, string $vendor_id, string $koders)
	{
		$vendor = (object) [
			"vendor_id" => $vendor_id
		];

		$saldo_awal = $this->db->select('vendor_id, COALESCE(SUM(totaltagihan - totalbayar), 0) as saldo_awal')->where([
			'vendor_id' => $vendor_id,
			'koders' => $koders,
			'tglinvoice <' => $start_date
		])->group_by('vendor_id')->get('tbl_apoap')->row();
		
		$vendor->saldo_awal = $saldo_awal ? $saldo_awal->saldo_awal : 0;

		$vendor->transactions = $this->db->where([
			'koders' => $koders,
			'tglinvoice >=' => $start_date,
			'tglinvoice <=' => $end_date,
			'vendor_id' => trim($vendor_id)
		])->order_by('tglinvoice')->get('tbl_apoap')->result();

		return $vendor;
	}

	/**
	 * Method for getting vendor indentity
	 * 
	 * @param string $vendor_id
	 */
	public function get_vendor(string $vendor_id)
    {
        return $this->db->select('vendor_id, vendor_name')->where('vendor_id', $vendor_id)->get('tbl_vendor')->row();
    }
}
