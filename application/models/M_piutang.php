<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_piutang extends CI_Model {

	var $table = 'tbl_pap';

	public function __construct()
	{
		parent::__construct();
		$this->load->database();	
	}

	/**
	 * Method untuk mendapatkan data piutang yang belum ditagihkan
	 * 
	 * @param string
	 * @return object
	 */
	private function unbilled (string $koders = null, string $asal = null, string $date = '')
	{
		$end_period = strtotime($date ?? 'now');
		
		$query = $this->db->select('cust_id, sum(jumlahhutang) as jumlah_piutang, count(*) as jumlah_transaksi')->from('tbl_pap')->where('tgljatuhtempo', 0)->where('jumlahbayar', 0)->where('tglposting <', date('Y-m-d', $end_period));

		if ($asal == 'POLI' || $asal == 'INAP' || $asal == 'JUAL') $query = $query->where('asal', $asal);

		if ($koders) $query = $query->where('koders', $koders);

		$query_result = $query->group_by('cust_id')->get()->result();

		$result = (object) [
			'total_unbilled' => array_reduce($query_result, function ($carry, $item) {
				return $carry += $item->jumlah_piutang;
			}),
			'vendor_list' => $query_result,
		];

		return $result;
	}

	/**
	 * Method untuk mendapatkan data piutang yang belum jatuh tempo
	 * 
	 * @param string
	 * @return object
	 */
	private function unexpired (string $koders = null, string $asal = null, string $date = '')
	{
		$end_period = strtotime($date ?? 'now');
		
		$query = $this->db->select('cust_id, sum(jumlahhutang) as jumlah_piutang, count(*) as jumlah_transaksi')->from('tbl_pap')->where('tgljatuhtempo >', date('Y-m-d', $end_period))->where('jumlahbayar', 0)->where('tglposting <', date('Y-m-d', $end_period));

		if ($asal == 'POLI' || $asal == 'INAP' || $asal == 'JUAL') $query = $query->where('asal', $asal);

		if ($koders) $query = $query->where('koders', $koders);

		$query_result = $query->group_by('cust_id')->get()->result();

		$result = (object) [
			'total_unexpired' => array_reduce($query_result, function ($carry, $item) {
				return $carry += $item->jumlah_piutang;
			}),
			'vendor_list' => $query_result,
		];

		return $result;
	}

	/**
	 * Method untuk mendapatkan data piutang dengan umur kurang dari 30 hari
	 * 
	 * @param string
	 * @return object
	 */
	private function less_than_30_days (string $koders = null, string $asal = null, string $date = null)
	{
		$end_period = strtotime($date ?? 'now');

		$date = date('Y-m-d', strtotime('-30days', $end_period));
		
		$query = $this->db->select('cust_id, sum(jumlahhutang) as jumlah_piutang, count(*) as jumlah_transaksi')->from('tbl_pap')->where('tgljatuhtempo >=', $date)->where('tgljatuhtempo <=', date('Y-m-d', $end_period))->where('jumlahbayar', 0)->where('tglposting <', date('Y-m-d', $end_period));

		if ($asal == 'POLI' || $asal == 'INAP' || $asal == 'JUAL'){
			$query = $query->where('asal', $asal);
		}

		if ($koders){
			$query = $query->where('koders', $koders);
        }

		$query_result = $query->group_by('cust_id')->get()->result();

		$result = (object) [
			'total_less_than_30_days' => array_reduce($query_result, function ($carry, $item) {
				return $carry += $item->jumlah_piutang;
			}),
			'vendor_list' => $query_result,
		];

		return $result;
	}

	/**
	 * Method untuk mendapatkan data piutang dengan umur antara 31 hingga 60 hari
	 * 
	 * @param string
	 * @return object
	 */
	private function between_30_and_60_days (string $koders = null, string $asal = null, string $date = null)
	{
		$end_period = strtotime($date ?? 'now');
		
		$date_30 = date('Y-m-d', strtotime('-30days', $end_period));
		$date_60 = date('Y-m-d', strtotime('-60days', $end_period));

		
		$query = $this->db->select('cust_id, sum(jumlahhutang) as jumlah_piutang, count(*) as jumlah_transaksi')->from('tbl_pap')->where('tgljatuhtempo >=', $date_60)->where('tgljatuhtempo <', $date_30)->where('jumlahbayar', 0)->where('tglposting <', date('Y-m-d', $end_period));

		if ($asal == 'POLI' || $asal == 'INAP' || $asal == 'JUAL'){
			$query = $query->where('asal', $asal);
		}

		if ($koders){
			$query = $query->where('koders', $koders);
        }

		$query_result = $query->group_by('cust_id')->get()->result();

		$result = (object) [
			'total_between_30_and_60_days' => array_reduce($query_result, function ($carry, $item) {
				return $carry += $item->jumlah_piutang;
			}),
			'vendor_list' => $query_result,
		];

		return $result;
	}

	/**
	 * Method untuk mendapatkan data piutang dengan umur antarara 61 hingga 90 hari
	 * 
	 * @param string
	 * @return object
	 */
	private function between_60_and_90_days (string $koders = null, string $asal = null, string $date = null)
	{
		$end_period = strtotime($date ?? 'now');
		$date_60 = date('Y-m-d', strtotime('-60days', $end_period));
		$date_90 = date('Y-m-d', strtotime('-90days', $end_period));
		
		$query = $this->db->select('cust_id, sum(jumlahhutang) as jumlah_piutang, count(*) as jumlah_transaksi')->from('tbl_pap')->where('tgljatuhtempo >=', $date_90)->where('tgljatuhtempo <', $date_60)->where('jumlahbayar', 0)->where('tglposting <', date('Y-m-d', $end_period));

		if ($asal == 'POLI' || $asal == 'INAP' || $asal == 'JUAL'){
			$query = $query->where('asal', $asal);
		}

		if ($koders){
			$query = $query->where('koders', $koders);
        }

		$query_result = $query->group_by('cust_id')->get()->result();

		$result = (object) [
			'total_between_60_and_90_days' => array_reduce($query_result, function ($carry, $item) {
				return $carry += $item->jumlah_piutang;
			}),
			'vendor_list' => $query_result,
		];

		return $result;
	}

	/**
	 * Method untuk mendapatkan data piutang dengan umur antarara 61 hingga 90 hari
	 * 
	 * @param string
	 * @return object
	 */
	private function more_than_90_days (string $koders = null, string $asal = null, string $date = null)
	{
		$end_period = strtotime($date ?? 'now');
		$date_90 = date('Y-m-d', strtotime('-90days', $end_period));
		
		$query = $this->db->select('cust_id, sum(jumlahhutang) as jumlah_piutang, count(*) as jumlah_transaksi')->from('tbl_pap')->where('tgljatuhtempo <', $date_90)->where('jumlahbayar', 0)->where('tgljatuhtempo <>', 0)->where('tglposting <', date('Y-m-d', $end_period));

		if ($asal == 'POLI' || $asal == 'INAP' || $asal == 'JUAL'){
			$query = $query->where('asal', $asal);
		}

		if ($koders){
			$query = $query->where('koders', $koders);
        }

		$query_result = $query->group_by('cust_id')->get()->result();

		$result = (object) [
			'total_more_than_90_days' => array_reduce($query_result, function ($carry, $item) {
				return $carry += $item->jumlah_piutang;
			}),
			'vendor_list' => $query_result,
		];

		return $result;
	}

	/**
	 * Method untuk mengecek keberadaan nomer invoice
	 * 
	 * @param string $invoiceno
	 * @return bool
	 */
	private function exists(string $invoiceno): bool
	{
		$result = $this->db->select('invoiceno')->from('tbl_papinvoice')->where('invoiceno', $invoiceno)->get()->row();

		if ($result) return true;
		else return false;
	}

	/**
	 * Method untuk menyimpan data invoice
	 * 
	 * @param array $data
	 */
	public function save_invoice(array $data)
	{
		if ($this->exists($data['invoiceno'])){
			return $this->db->update('tbl_papinvoice', $data, ['invoiceno' => $data['invoiceno']]);
		}

		return $this->db->insert('tbl_papinvoice', $data);
	}

	/**
	 * Method untuk menghapus invoice
	 * 
	 * @param string $invoiceno
	 * @return bool
	 */
	public function delete_invoice(string $invoiceno): bool
	{
		return $this->db->delete('tbl_papinvoice', ['invoiceno' => $invoiceno]);
	}

	/**
	 * Method untuk update data piutang
	 * 
	 * @param array $data
	 */
	public function update_ar (array $data)
	{
		return $this->db->where('id', $data['id'])->update('tbl_pap', $data);
	}

	public function getTotalPiutang($unit){
		$query = "
			SELECT COALESCE(SUM(jumlahhutang-jumlahbayar),0) AS total
            FROM tbl_pap
            WHERE koders = '$unit'
		";
		$qry = $this->db->query($query)->num_rows();

		if($qry > 0){
			return $this->db->query($query)->result();
		} else {
			return false;
		}
	}

    
	public function getTotalAsuransi($unit){
		$query = "
			SELECT COALESCE(SUM(jumlahhutang-jumlahbayar),0) AS asuransi
            FROM tbl_pap
            WHERE cust_id <> 'BPJS' AND koders = '$unit'
		";
		$qry = $this->db->query($query)->num_rows();

		if($qry > 0){
			return $this->db->query($query)->result();
		} else {
			return false;
		}
	}

    
	public function getTotalBpjs($unit){
		$query = "
			SELECT COALESCE(SUM(p.jumlahhutang-p.jumlahbayar),0) AS simrs,
                COALESCE(SUM(p.inacbg-p.jumlahbayar),0) AS inacbg
            FROM tbl_pap p INNER JOIN tbl_penjamin pj ON p.cust_id = pj.cust_id
            WHERE p.cust_id = 'BPJS' AND p.koders = '$unit'
		";
		$qry = $this->db->query($query)->num_rows();

		if($qry > 0){
			return $this->db->query($query)->result();
		} else {
			return false;
		}
	}

	/**
	 * Method untuk mendapatkan data ringkasan piutang
	 * 
	 * @param string $unit
	 * @param string $period
	 * @return array
	 */
    public function get_ar_summary (string $unit, string $fromdate, string $todate): array
	{
        $vendor = $this->db->select('cust_id, cust_nama')->from('tbl_penjamin')->get()->result();
		$ar_summary = $this->db->select('cust_id, COALESCE(SUM(jumlahhutang), 0) as total_piutang, COALESCE(SUM(inacbg), 0) as total_casemix, COALESCE(SUM(jumlahbayar), 0) as total_bayar')->from('tbl_pap')->where('koders', $unit)->where('tglposting >=', $fromdate)->where('tglposting <=', $todate)->group_by('cust_id')->get()->result();
		$ar_starting_balances = $this->db->select('cust_id, COALESCE(SUM(jumlahhutang), 0) as starting_balance')->from('tbl_pap')->where('koders', $unit)
								->where('tglposting <=', $fromdate)->group_by('cust_id')->get()->result();

		for ($i = 0; $i < count($vendor); $i++){
			foreach ($ar_summary as $ar_data){
				if ($vendor[$i]->cust_id == $ar_data->cust_id){
					$vendor[$i]->total_piutang = $ar_data->total_piutang;
					$vendor[$i]->total_casemix = $ar_data->total_casemix;
					$vendor[$i]->total_bayar = $ar_data->total_bayar;
					$vendor[$i]->mutation = $ar_data->total_piutang + $ar_data->total_casemix - $ar_data->total_bayar;
				}
			}
			foreach ($ar_starting_balances as $ar_starting_balance){
				if($vendor[$i]->cust_id == $ar_starting_balance->cust_id){
					$vendor[$i]->starting_balance = $ar_starting_balance->starting_balance;
				}
			}
			if (!property_exists($vendor[$i], 'total_piutang')){
				$vendor[$i]->total_piutang = 0;
				$vendor[$i]->total_casemix = 0;
				$vendor[$i]->total_bayar = 0;
				$vendor[$i]->mutation = 0;
			}
			if (!property_exists($vendor[$i], 'starting_balance')){
				$vendor[$i]->starting_balance = 0;
			}
		}

		return $vendor;
	}
    
	/**
	 * Method untuk mendapatkan data invoice yang telah dibuat
	 * 
	 * @return array
	 */
	public function get_invoices(string $koders, string $cust_id, string $startdate, string $enddate)
	{
		$vendors = $this->get_ar_summary($koders, $startdate, $enddate);
		$vendor = array_values(array_filter($vendors, function ($item) use ($cust_id) {
			return $item->cust_id == $cust_id;
		}))[0];

		setlocale(LC_ALL, 'id_ID');

		$invoices = $this->db->select('invoiceno, invoicedate, cust_id, jenis, dariperiode, sampaiperiode, totalnetrp')
		->from('tbl_papinvoice as p')->where(['cust_id' => $cust_id, 'koders' => $koders, 'invoicedate >' => $startdate, 'invoicedate <=' => $enddate])
		->get()->result();

		for ($i = 0; $i < count($invoices); $i++){
			$invoices[$i]->periode = strftime('%e %b %Y',strtotime($invoices[$i]->dariperiode)) . ' - ' . strftime('%e %b %Y',strtotime($invoices[$i]->sampaiperiode));
		}

		$vendor->invoices = $invoices;

		return $vendor;
	}

	/**
	 * Method untuk mendapatkan detail invoice
	 * 
	 * @param string $invoiceno
	 * @return object
	 */
	public function get_invoice_detail(string $invoiceno)
	{
		$result = $this->db->select('tbl_papinvoice.*, tbl_penjamin.cust_nama')->join('tbl_penjamin', 'tbl_papinvoice.cust_id = tbl_penjamin.cust_id')
					->where('invoiceno', $invoiceno)->get('tbl_papinvoice')->row();
		
		$result->patients = $this->db->select('id, fakturno, noreg, nopolis, rekmed, dokument, namapas, cust_id, asal, tglposting, tgljatuhtempo, jumlahhutang, inacbg, jumlahbayar, invoiceno')
								->from('tbl_pap')->where('invoiceno', $invoiceno)->get()->result();

		return $result;
	}

	/**
	 * Method untuk mendaptkan daftar vendor
	 * 
	 * @return mixed
	 */
	public function get_vendors(string $cust_id = null)
	{
		if ($cust_id == null) return $this->db->select('cust_id, cust_nama')->from('tbl_penjamin')->get()->result();

		return $this->db->select('cust_id, cust_nama')->from('tbl_penjamin')->where('cust_id', $cust_id)->get()->row();
	}

	public function detailPiutangTerbentuk($postData=null){
		
		$response = array();
		$koders = $this->session->userdata('unit');

		## Read value
		$draw = $postData['draw'];
		$start = $postData['start'];
		$rowperpage = $postData['length']; // Rows display per page
		$columnIndex = $postData['order'][0]['column']; // Column index
		$columnName = $postData['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
		$searchValue = $postData['search']['value']; // Search value

		$stat = "";
		if(strtolower($searchValue) == "lunas") $stat = "tbl_pap.lunas = 1 or";
		else if(strtolower($searchValue) == "belum lunas") $stat = "tbl_pap.lunas = 0 or";
		## Search 
		$searchQuery = "";
		if($searchValue != ''){
			$searchQuery = " (
					tbl_pap.noreg like '%".$searchValue."%' or 
					tbl_pap.tglposting like '%".$searchValue."%' or 
					tbl_penjamin.cust_nama like '%".$searchValue."%' or 
					tbl_pap.invoiceno like '%".$searchValue."%' or 
					tbl_pap.tgljatuhtempo like '%".$searchValue."%' or 
					tbl_pap.jumlahhutang like '%".$searchValue."%' or 
					tbl_pap.jumlahbayar like '%".$searchValue."%' or 
					tbl_pap.lunas like '%".$searchValue."%' or 
					$stat
					tbl_pap.invoiceno like '%".$searchValue."%' )  
					AND tbl_pap.koders = '$koders'";
		}


		## Total number of records without filtering
		// $this->db->select('count(*) as allcount');
		// $this->db->where("tbl_pap.koders = '$koders'");
		// $records = $this->db->get('tbl_pap')->result();

		$q1 = 
			"select count(*) as allcount
			from
				tbl_pap a left outer join
				tbl_penjamin b
				on a.cust_id=b.cust_id
			where
				a.koders = '$koders'
			order by
				a.tglposting, a.noreg desc";
		$records = $this->db->query($q1)->result();
		$totalRecords = $records[0]->allcount;

		## Total number of record with filtering
		$this->db->select('count(*) as allcount');
		$this->db->join('tbl_penjamin','tbl_pap.cust_id=tbl_penjamin.cust_id','left');    
		if($searchQuery != '')
			$this->db->where($searchQuery);
		else
			$this->db->where("tbl_pap.koders = '$koders'");
		$records = $this->db->get('tbl_pap')->result();
		$totalRecordwithFilter = $records[0]->allcount;

		
		## Fetch records
		$this->db->select('*');
		$this->db->join('tbl_penjamin','tbl_pap.cust_id=tbl_penjamin.cust_id','left');
		if($searchQuery != '') 
			$this->db->where($searchQuery);
		else 
			$this->db->where("tbl_pap.koders = '$koders'");
		$this->db->order_by($columnName, $columnSortOrder);
		$this->db->limit($rowperpage, $start);
		$records = $this->db->get('tbl_pap')->result();

		$data = array();

		// foreach($records as $record ){
			
		// 	$data[] = array( 
		// 		"koders"=>$koders,
		// 		"noreg"=>$record->noreg,
		// 		"tglposting"=>$record->tglposting,
		// 		"cust_nama"=>$record->cust_nama,
		// 		"invoiceno"=>$record->invoiceno,
		// 		"tgljatuhtempo"=>$record->tgljatuhtempo,
		// 		"jumlahhutang"=>$record->jumlahhutang,
		// 		"jumlahbayar"=>$record->jumlahbayar,
		// 		"total"=>$record->total
		// 	); 
		// }
		
			// $data[] = array( 
			// 	"koders"=>0,
			// 	"noreg"=>'-',
			// 	"tglposting"=>'-',
			// 	"cust_nama"=>'-',
			// 	"invoiceno"=>'-',
			// 	"tgljatuhtempo"=>'-',
			// 	"jumlahhutang"=>'-',
			// 	"jumlahbayar"=>'-',
			// 	"lunas"=>'-'
			// );

		foreach($records as $record ){
			$row = array();
			$row[] = $koders;
			$row[] = $record->noreg;
			$row[] = date('d-m-Y',strtotime($record->tglposting));
			$row[] = $record->cust_nama;
			$row[] = $record->invoiceno;			
			$row[] = date('d-m-Y',strtotime($record->tgljatuhtempo));
			$row[] = number_format($record->jumlahhutang,2,'.',',');			
			$row[] = $record->jumlahbayar;
			$row[] = $record->lunas == 0 ? 
					"<span class='label label-sm label-warning'>Belum Lunas</span>" : 
					"<span class='label label-sm label-success'>Lunas</span>";

			$data[] = $row;
		}

		## Response
		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data
		);

		return $response; 
	}

	
	public function detailPiutangAsuransi_pt($postData=null){
		
		$response = array();
		$koders = $this->session->userdata('unit');

		## Read value
		$draw = $postData['draw'];
		$start = $postData['start'];
		$rowperpage = $postData['length']; // Rows display per page
		$columnIndex = $postData['order'][0]['column']; // Column index
		$columnName = $postData['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
		$searchValue = $postData['search']['value']; // Search value

		$stat = "";
		if(strtolower($searchValue) == "lunas") $stat = "tbl_pap.lunas = 1 or";
		else if(strtolower($searchValue) == "belum lunas") $stat = "tbl_pap.lunas = 0 or";
		## Search 
		$searchQuery = "";
		if($searchValue != ''){
			$searchQuery = " (
					tbl_pap.noreg like '%".$searchValue."%' or 
					tbl_pap.tglposting like '%".$searchValue."%' or 
					tbl_penjamin.cust_nama like '%".$searchValue."%' or 
					tbl_pap.invoiceno like '%".$searchValue."%' or 
					tbl_pap.tgljatuhtempo like '%".$searchValue."%' or 
					tbl_pap.jumlahhutang like '%".$searchValue."%' or 
					tbl_pap.jumlahbayar like '%".$searchValue."%' or 
					tbl_pap.lunas like '%".$searchValue."%' or 
					$stat
					tbl_pap.invoiceno like '%".$searchValue."%' )  
					AND tbl_pap.koders = '$koders'
					AND tbl_pap.cust_id <> 'BPJS'";
		}

		$q1 = 
			"select count(*) as allcount
			from
				tbl_pap a left outer join
				tbl_penjamin b
				on a.cust_id=b.cust_id
			where
				a.koders = '$koders'
				AND a.cust_id <> 'BPJS'
			order by
				a.tglposting, a.noreg desc";
		$records = $this->db->query($q1)->result();
		$totalRecords = $records[0]->allcount;

		## Total number of record with filtering
		$this->db->select('count(*) as allcount');
		$this->db->join('tbl_penjamin','tbl_pap.cust_id=tbl_penjamin.cust_id','left');    
		if($searchQuery != '') $this->db->where($searchQuery);
		else $this->db->where("tbl_pap.koders = '$koders' AND tbl_pap.cust_id <> 'BPJS'");
		$records = $this->db->get('tbl_pap')->result();
		$totalRecordwithFilter = $records[0]->allcount;

		
		## Fetch records
		$this->db->select('*');
		$this->db->join('tbl_penjamin','tbl_pap.cust_id=tbl_penjamin.cust_id','left');
		if($searchQuery != '') 
			$this->db->where($searchQuery);
		else 
			$this->db->where("tbl_pap.koders = '$koders' AND tbl_pap.cust_id <> 'BPJS'");
		$this->db->order_by($columnName, $columnSortOrder);
		$this->db->limit($rowperpage, $start);
		$records = $this->db->get('tbl_pap')->result();

		$data = array();

		foreach($records as $record ){
			$row = array();
			$row[] = $koders;
			$row[] = $record->noreg;
			$row[] = date('d-m-Y',strtotime($record->tglposting));
			$row[] = $record->cust_nama;
			$row[] = $record->invoiceno;			
			$row[] = date('d-m-Y',strtotime($record->tgljatuhtempo));
			$row[] = number_format($record->jumlahhutang,2,'.',',');			
			$row[] = $record->jumlahbayar;
			$row[] = $record->lunas == 0 ? 
					"<span class='label label-sm label-warning'>Belum Lunas</span>" : 
					"<span class='label label-sm label-success'>Lunas</span>";

			$data[] = $row;
		}

		## Response
		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data
		);

		return $response; 
	}

	
	public function detailPiutang_bpjs($postData=null){
		
		$response = array();
		$koders = $this->session->userdata('unit');

		## Read value
		$draw = $postData['draw'];
		$start = $postData['start'];
		$rowperpage = $postData['length']; // Rows display per page
		$columnIndex = $postData['order'][0]['column']; // Column index
		$columnName = $postData['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
		$searchValue = $postData['search']['value']; // Search value

		$stat = "";
		if(strtolower($searchValue) == "lunas") $stat = "tbl_pap.lunas = 1 or";
		else if(strtolower($searchValue) == "belum lunas") $stat = "tbl_pap.lunas = 0 or";
		## Search 
		$searchQuery = "";
		if($searchValue != ''){
			$searchQuery = " (
					tbl_pap.noreg like '%".$searchValue."%' or 
					tbl_pap.tglposting like '%".$searchValue."%' or 
					tbl_penjamin.cust_nama like '%".$searchValue."%' or 
					tbl_pap.invoiceno like '%".$searchValue."%' or 
					tbl_pap.tgljatuhtempo like '%".$searchValue."%' or 
					tbl_pap.jumlahhutang like '%".$searchValue."%' or 
					tbl_pap.jumlahbayar like '%".$searchValue."%' or 
					tbl_pap.lunas like '%".$searchValue."%' or 
					$stat
					tbl_pap.invoiceno like '%".$searchValue."%' )  
					AND tbl_pap.koders = '$koders'
					AND tbl_pap.cust_id = 'BPJS'";
		}

		$q1 = 
			"select count(*) as allcount
			from
				tbl_pap a left outer join
				tbl_penjamin b
				on a.cust_id=b.cust_id
			where
				a.koders = '$koders'
				AND a.cust_id = 'BPJS'
			order by
				a.tglposting, a.noreg desc";
		$records = $this->db->query($q1)->result();
		$totalRecords = $records[0]->allcount;

		## Total number of record with filtering
		$this->db->select('count(*) as allcount');
		$this->db->join('tbl_penjamin','tbl_pap.cust_id=tbl_penjamin.cust_id','left');    
		if($searchQuery != '') $this->db->where($searchQuery);
		else $this->db->where("tbl_pap.koders = '$koders' AND tbl_pap.cust_id = 'BPJS'");
		$records = $this->db->get('tbl_pap')->result();
		$totalRecordwithFilter = $records[0]->allcount;

		
		## Fetch records
		$this->db->select('*');
		$this->db->join('tbl_penjamin','tbl_pap.cust_id=tbl_penjamin.cust_id','left');
		if($searchQuery != '') 
			$this->db->where($searchQuery);
		else 
			$this->db->where("tbl_pap.koders = '$koders' AND tbl_pap.cust_id = 'BPJS'");
		$this->db->order_by($columnName, $columnSortOrder);
		$this->db->limit($rowperpage, $start);
		$records = $this->db->get('tbl_pap')->result();

		$data = array();

		foreach($records as $record ){
			$row = array();
			$row[] = $koders;
			$row[] = $record->noreg;
			$row[] = date('d-m-Y',strtotime($record->tglposting));
			$row[] = $record->cust_nama;
			$row[] = $record->invoiceno;			
			$row[] = date('d-m-Y',strtotime($record->tgljatuhtempo));
			$row[] = number_format($record->jumlahhutang,2,'.',',');			
			$row[] = $record->jumlahbayar;
			$row[] = $record->lunas == 0 ? 
					"<span class='label label-sm label-warning'>Belum Lunas</span>" : 
					"<span class='label label-sm label-success'>Lunas</span>";

			$data[] = $row;
		}

		## Response
		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data
		);

		return $response; 
	}

	/**
	 * Method untuk mendapatkan data untuk laporan detail piutang
	 * 
	 * @param object
	 * @return object
	 */
	public function get_ar_detail_data ($param)
	{
		$starting_balances = $this->db->where('tglposting <=', $param->fromdate)->select('piutang.cust_id, SUM(piutang.jumlahhutang) as starting_balance')
							->from('tbl_pap as piutang')->group_by('cust_id');

		if ($param->jenis == 'POLI' || $param->jenis == 'INAP' || $param->jenis == 'JUAL'){
			$starting_balances = $starting_balances->where('asal', $param->jenis);
		}

        if (property_exists($param, 'cabang')){
            $starting_balances = $starting_balances->where('koders', $param->cabang);
        }

        $starting_balances = $starting_balances->get()->result();

		$ar_data = $this->db->select('cust_id, cust_nama')->get('tbl_penjamin')->result();

		for ($i = 0; $i < count($ar_data); $i++){
			foreach($starting_balances as $starting_balance){
				if($starting_balance->cust_id == $ar_data[$i]->cust_id){
					$ar_data[$i]->starting_balance = $starting_balance->starting_balance;
				}
			}
		}

        if (property_exists($param, 'vendor')){
            $result = array_values(array_filter($ar_data, function ($item) use($param) {
                return $item->cust_id == $param->vendor;
             }));
        }

		return $result ?? $ar_data;
	}

	/**
	 * Method untuk mendapatkan data transaksi piutang dari database
	 * 
	 * @param object
	 * @return array
	 */
	public function get_ar_transactions ($param)
	{
		$perpage = 1000;
		$page = $param->page ?? 1;
		$data_count = $this->db->select('count(*) as data_count')->from('tbl_pap')->where('cust_id', $param->vendor)
						->where('tglposting >', $param->fromdate)->where('tglposting <=', $param->todate)->where('koders', $param->cabang);
		
		if ($param->jenis == 'POLI' || $param->jenis == 'INAP' || $param->jenis == 'JUAL'){
			$data_count = $data_count->where('asal', $param->jenis)->get()->row()->data_count;
		}else{
			$data_count = $data_count->get()->row()->data_count;
		}

		$total_page = ceil($data_count / $perpage);

		$transactions = $this->db->select('id, fakturno, noreg, rekmed, dokument, namapas, cust_id, asal, tglposting, tgljatuhtempo, jumlahhutang, inacbg, jumlahbayar, invoiceno')->from('tbl_pap')->where('cust_id', $param->vendor)
		->where('tglposting >', $param->fromdate)->where('tglposting <=', $param->todate)->limit($perpage, $perpage * ($page -1))->where('koders', $param->cabang);

		if ($param->jenis == 'POLI' || $param->jenis == 'INAP' || $param->jenis == 'JUAL') $transactions = $transactions->where('asal', $param->jenis);


		$result = (object) [
			'data' => $transactions->get()->result()
		];

		$result->total_page = $total_page;
		
		$result->total_data = $data_count;
		
		return $result;
	}

	/**
	 * Method untuk mendapatkan data pasien yang dapat ditagihkan
	 * 
	 * @return array
	 */
	public function get_billable_patient ($param)
	{
		$perpage = 1000;
		$page = $param->page ?? 1;
		$data_count = $this->db->select('count(*) as data_count')->from('tbl_pap')->where('cust_id', $param->vendor)
						->where('tglposting >', $param->fromdate)->where('tglposting <=', $param->todate)->where('koders', $param->cabang);
		
		if ($param->jenis == 'POLI' || $param->jenis == 'INAP' || $param->jenis == 'JUAL'){
			$data_count = $data_count->where('asal', $param->jenis)->get()->row()->data_count;
		}else{
			$data_count = $data_count->get()->row()->data_count;
		}

		$total_page = ceil($data_count / $perpage);

		$transactions = $this->db->select('id, fakturno, noreg, rekmed, dokument, namapas, cust_id, asal, tglposting, tgljatuhtempo, jumlahhutang, inacbg, jumlahbayar, invoiceno')->from('tbl_pap')->where('cust_id', $param->vendor)
		->where('tglposting >', $param->fromdate)->where('tglposting <=', $param->todate)->limit($perpage, $perpage * ($page -1))->where('koders', $param->cabang)
		->where('jumlahbayar =', 0);

		if ($param->jenis == 'POLI' || $param->jenis == 'INAP' || $param->jenis == 'JUAL') $transactions = $transactions->where('asal', $param->jenis);


		$result = (object) [
			'data' => $transactions->get()->result()
		];

		$result->total_page = $total_page;
		
		$result->total_data = $data_count;
		
		return $result;
	}

	/**
	 * Method untuk mendapatkan data untuk laporan detail piutang
	 * 
	 * @param object
	 * @return mixed
	 */
	public function get_ar_report_data($param)
	{
		ini_set('memory_limit', '-1');

		$starting_balances = $this->db->where('tglposting <=', $param->fromdate)->select('piutang.cust_id, SUM(piutang.jumlahhutang) as starting_balance')
							->from('tbl_pap as piutang')->group_by('cust_id');

		if ($param->jenis == 'POLI' || $param->jenis == 'INAP' || $param->jenis == 'JUAL'){
			$starting_balances = $starting_balances->where('asal', $param->jenis);
		}

        if (property_exists($param, 'cabang')){
            $starting_balances = $starting_balances->where('koders', $param->cabang);
        }

        $starting_balances = $starting_balances->get()->result();

		$ar_data = $this->db->select('cust_id, cust_nama')->get('tbl_penjamin')->result();

		for ($i = 0; $i < count($ar_data); $i++){
			foreach($starting_balances as $starting_balance){
				if($starting_balance->cust_id == $ar_data[$i]->cust_id){
					$ar_data[$i]->starting_balance = $starting_balance->starting_balance;
				}
			}
		}

		for ($i = 0; $i < count($ar_data); $i++){
			$query = $this->db->select('id, fakturno, noreg, rekmed, dokument, namapas, cust_id, asal, tglposting, tgljatuhtempo, jumlahhutang, inacbg, jumlahbayar, invoiceno')->from('tbl_pap')->where('cust_id', $ar_data[$i]->cust_id)
							->where('tglposting >', $param->fromdate)->where('tglposting <=', $param->todate)->where('jumlahbayar =', 0);
			
			if (property_exists($param, 'cabang')) $query->where('koders', $param->cabang);

			$ar_data[$i]->transactions = $query->get()->result();
		}

        if (property_exists($param, 'vendor')){
            $result = array_values(array_filter($ar_data, function ($item) use($param) {
                return $item->cust_id == $param->vendor;
             }));
        }

		return $result ?? $ar_data;
	}

	/**
	 * Method untuk mendapatkan detail umur piutang
	 * 
	 * @param object
	 * @return object
	 */
	public function get_ar_aging_data($param)
	{
		$unbilled = $this->unbilled(($param->cabang ?? ''), ($param->jenis ?? ''), ($param->todate ?? ''));
		$unexpired = $this->unexpired(($param->cabang ?? ''), ($param->jenis ?? ''), ($param->todate ?? ''));
		$less_than_30_days = $this->less_than_30_days(($param->cabang ?? ''), ($param->jenis ?? ''), ($param->todate ?? ''));
		$between_30_and_60_days = $this->between_30_and_60_days(($param->cabang ?? ''), ($param->jenis ?? ''), ($param->todate ?? ''));
		$between_60_and_90_days = $this->between_60_and_90_days(($param->cabang ?? ''), ($param->jenis ?? ''), ($param->todate ?? ''));
		$more_than_90_days = $this->more_than_90_days(($param->cabang ?? ''), ($param->jenis ?? ''), ($param->todate ?? ''));

		$ar_data = $this->db->select('cust_id, cust_nama')->get('tbl_penjamin')->result();

		for ($i = 0; $i < count($ar_data); $i++){
			$ar_data[$i]->total_ar = 0;
			foreach($unbilled->vendor_list as $vendor){
				if($vendor->cust_id == $ar_data[$i]->cust_id){
					$ar_data[$i]->unbilled = floatval($vendor->jumlah_piutang);
					$ar_data[$i]->total_ar += floatval($vendor->jumlah_piutang);
				}
			}
			foreach($unexpired->vendor_list as $vendor){
				if($vendor->cust_id == $ar_data[$i]->cust_id){
					$ar_data[$i]->unexpired = floatval($vendor->jumlah_piutang);
					$ar_data[$i]->total_ar += floatval($vendor->jumlah_piutang);
				}
			}

			foreach($less_than_30_days->vendor_list as $vendor){
				if($vendor->cust_id == $ar_data[$i]->cust_id){
					$ar_data[$i]->less_than_30_days = floatval($vendor->jumlah_piutang);
					$ar_data[$i]->total_ar += floatval($vendor->jumlah_piutang);
				}
			
			}

			foreach($between_30_and_60_days->vendor_list as $vendor){
				if($vendor->cust_id == $ar_data[$i]->cust_id){
					$ar_data[$i]->between_30_and_60_days = floatval($vendor->jumlah_piutang);
					$ar_data[$i]->total_ar += floatval($vendor->jumlah_piutang);
				}
			}

			foreach($between_60_and_90_days->vendor_list as $vendor){
				if($vendor->cust_id == $ar_data[$i]->cust_id){
					$ar_data[$i]->between_60_and_90_days = floatval($vendor->jumlah_piutang);
					$ar_data[$i]->total_ar += floatval($vendor->jumlah_piutang);
				}
			}

			foreach($more_than_90_days->vendor_list as $vendor){
				if($vendor->cust_id == $ar_data[$i]->cust_id){
					$ar_data[$i]->more_than_90_days = floatval($vendor->jumlah_piutang);
					$ar_data[$i]->total_ar += floatval($vendor->jumlah_piutang);
				}
			}
		}

		if (property_exists($param, 'vendor')){
			$filtered_ar_list = array_values(array_filter($ar_data, function ($item) use ($param) {
				return $item->cust_id == $param->vendor;
			}))[0];
			$result = (object) [
				'vendor_list' => $filtered_ar_list,
				'total_ar_this_period' => $filtered_ar_list->total_ar,
			];

			return $result;
		}

		$cek = floatval($this->db->select('sum(jumlahhutang) as jumlah')->where('tglposting <', $param->todate)->where('jumlahbayar', 0)->get('tbl_pap')->row()->jumlah);

		$result = (object) [
			'vendor_list' => $ar_data,
			'total_ar' => array_reduce($ar_data, function ($carry, $item) {
				$carry += $item->total_ar;
				return $carry;
			}),
			'total_unbilled' => $unbilled->total_unbilled,
			'total_unexpired' => $unexpired->total_unexpired,
			'total_less_than_30_days' => $less_than_30_days->total_less_than_30_days,
			'total_between_30_and_60_days' => $between_30_and_60_days->total_between_30_and_60_days,
			'total_between_60_and_90_days' => $between_60_and_90_days->total_between_60_and_90_days,
			'total_more_than_90_days' => $more_than_90_days->total_more_than_90_days,
		];

		return $result;
	}
}