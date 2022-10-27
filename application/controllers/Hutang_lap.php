<?php 

	if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Hutang_lap extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->session->set_userdata('menuapp', '5200');
		$this->session->set_userdata('submenuapp', '5205');
		// $this->load->helper('simkeu_nota');
		// $this->load->model('M_hutang');
		// $this->load->model('M_cetak');
		// $this->load->model('M_rs');
		$this->load->model(array("M_bedah","M_pasien_global","M_cetak"));
		$this->load->helper(array("app_global","rsreport"));
	}

	// public function index(){
	// 	$cek = $this->session->userdata('level');		
	// 	$unit= $this->session->userdata('unit');
        		
	// 	if(!empty($cek)){	
			
	// 	  $tahun 		= $this->M_global->_periodetahun();
  
	// 	  $bulan  		= $this->M_global->_periodebulan();
	// 	  $nbulan 		= $this->M_global->_namabulan($bulan);

	// 	  $bln 			= count($bulan) == 1 ? '0'.$bulan : $bulan;
	// 	  $jmlhari 		= cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
		  
	// 	  $d['startdate'] 	= $tahun.'-'.$bln.'-01';
	// 	  $d['enddate'] 	= $tahun.'-'.$bln.'-'.$jmlhari;

	// 	  $dt1						= $this->M_hutang->totalHutang($unit, '', $d['startdate'], $d['enddate']);
	// 	  $d['total_hutang'] 		= $dt1[0]->total_hutang;
	// 	  $dt2						= $this->M_hutang->hutangJatuhTempo($unit, '', $d['startdate'], $d['enddate']);
	// 	  $d['hutang_jatuh_tempo']	= $dt2[0]->hutang_jatuh_tempo;
	// 	  $dt3						= $this->M_hutang->rencanaBayar($unit, '', $d['startdate'], $d['enddate']);
	// 	  $d['rencana_bayar']		= $dt3[0]->rencana_bayar;
	// 	  $dt4						= $this->M_hutang->realisasiPembayaran($unit, '', $d['startdate'], $d['enddate']);
	// 	  $d['realisasi_pembayaran']= $dt4[0]->realisasi_pembayaran;

		  	  
		  
	// 	  $q1 = 
	// 			"select *, a.id AS idtr
	// 			from
	// 			   tbl_apoap a left join
	// 			   tbl_vendor b
	// 			   on a.vendor_id=b.vendor_id
	// 			where
	// 			   a.koders = '$unit' and
	// 			   a.tglinvoice between '".$d['startdate']."' and '".$d['enddate']."'
	// 			order by
	// 			   a.tglinvoice, a.terima_no desc";

	// 	  $periode		= 'Periode '.ucwords(strtolower($nbulan)).' '.$this->M_global->_periodetahun();
	// 	  $d['vendor'] 	= '';	 
	// 	  $d['vendorid'] = ''; 
	// 	  $d['keu'] = '';
	// 	  $d['list_vendor'] 	= $this->M_global->getListVendor(); 
	//       $d['keu'] 	= $this->db->query($q1)->result();
    //       $d['periode'] = $periode;		
    //       $level=$this->session->userdata('level');		
	// 	  $akses= $this->M_global->cek_menu_akses($level, 5201);
	// 	  $d['akses']= $akses;
	// 	  $d['unit']	= $unit;
	// 	  $this->load->view('pembelian/v_hutang_lap',$d);			   
		
	// 	} else
	// 	{
	// 		header('location:'.base_url());
			
	// 	}
	// }

	public function index(){
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');

		if(!empty($cek)){

			$query_vendor	= $this->db->query("SELECT * FROM tbl_vendor");

			$data	=	[
				"unit"		=> $unit,
				"vendor"	=> $query_vendor->result(),
			];
			
			$this->load->view('pembelian/v_hutang_lap', $data);

		} else {
			redirect("/");
		}
	}

	//Print Header

	public function cetak($param){
		$cek 	= $this->session->userdata('level');
		$unit	= $this->session->userdata('unit');

		if(!empty($cek)){
			$allcabang		= ($this->input->post("allcabang") != null)? $this->input->post("allcabang") : 0;
			$cabang			= ($this->input->post("cabang") != null)? $this->input->post("cabang") : 0;
			$allvendor		= ($this->input->post("allvendor") != null)? $this->input->post("allvendor") : 0;
			$vendor			= ($this->input->post("vendor") != null)? $this->input->post("vendor") : 0;
			$fromdate		= $this->input->post("fromdate");
			$todate			= $this->input->post("todate");
			$laporan		= $this->input->post("laporan");

			switch($param){
				case 1 : $url	= "/hutang_lap/pdf/"; break;
				case 2 : $url	= "/hutang_lap/excel/"; break;
			}

			$data		= (object) array(
				"allcabang"	=> $allcabang,
				"cabang"	=> $cabang,
				"allvendor"	=> $allvendor,
				"vendor"	=> $vendor,
				"fromdate"	=> $fromdate,
				"todate"	=> $todate,
				"laporan"	=> $laporan,
			);

			$result		= array(
				"url" 	=> $url,
				"res"	=> $data,
			);

			echo json_encode($result, JSON_UNESCAPED_SLASHES);
		} else {
			redirect("/");
		}
	}

	public function pdf(){
		$cek 	= $this->session->userdata('level');
		$unit	= $this->session->userdata('unit');

		if(!empty($cek)){
			$data 		= "";
			$printinit	= $this->M_cetak->kop2();
			$allcabang	= $this->input->get("allcabang");
			$cabang		= $this->input->get("cabang");
			$allvendor	= $this->input->get("allvendor");
			$vendor		= $this->input->get("vendor");
			$fromdate	= $this->input->get("fromdate");
			$todate		= $this->input->get("todate");
			$laporan	= $this->input->get("laporan");

			switch($laporan){
				case 1 : $title = "LAPORAN UMUR HUTANG RS"; break;
				case 2 : $title = "REKAP UMUR HUTANG RS"; break;
				case 5 : $title = "KARTU HUTANG"; break;
				case 6 : $title = ""; break;
				case 7 : $title = ""; break;
				default : redirect("/hutang_lap/"); break;
			}

			$comp_name	= $printinit->namars;
			$comp_addr	= $printinit->alamat;
			$comp_phone	= $printinit->phone;
			$comp_image	= base_url().$printinit->image;

			if($allvendor == 1 && $allcabang == 1){
				$query_list_01	= $this->db->query("SELECT a.koders, b.vendor_name, a.invoice_no, a.tglinvoice, a.duedate, a.totaltagihan, a.notukar, a.terima_no 
				FROM tbl_apoap AS a 
				LEFT JOIN tbl_vendor AS b ON b.vendor_id = a.vendor_id
				WHERE a.tglinvoice BETWEEN '$fromdate' AND '$todate'
				GROUP BY a.invoice_no
				ORDER BY a.tglinvoice DESC")->result();

				// $query_list_02	= $this->db->query("SELECT a.koders, a.vendor_id, b.vendor_name, a.invoice_no, a.tglinvoice, a.duedate, a.totaltagihan, a.notukar, a.terima_no 
				// FROM tbl_apoap AS a 
				// LEFT JOIN tbl_vendor AS b ON b.vendor_id = a.vendor_id
				// WHERE a.tglinvoice BETWEEN '$fromdate' AND '$todate'
				// GROUP BY a.vendor_id 
				// ORDER BY a.tglinvoice DESC")->result();

				$fromdate = strtotime($fromdate);

				$_1 = date("Y-m-d", strtotime('+1 day', $fromdate));
				$_30 = date("Y-m-d", strtotime('+1 month', $fromdate));
				$_31 = date("Y-m-d", strtotime('+31 day', $fromdate));
				$_60 = date("Y-m-d", strtotime('+2 month', $fromdate));
				$_61 = date("Y-m-d", strtotime('+61 day', $fromdate));
				$_90 = date("Y-m-d", strtotime('+3 month', $fromdate));
				$_181 = date("Y-m-d", strtotime('+181 day', $fromdate));
				$_365 = date("Y-m-d", strtotime('+1 year', $fromdate));

				$query_list_02	= $this->db->query("SELECT a.vendor_id,b.vendor_name,
				sum(CASE WHEN a.duedate < '$fromdate' then a.totaltagihan else 0 end) as belum_jt,
				sum(CASE WHEN a.duedate BETWEEN '$fromdate' AND '$todate' then a.totaltagihan else 0 end) as jatuh_tempo,
				sum(CASE WHEN a.duedate BETWEEN '$_1' AND '$_30' then a.totaltagihan else 0 end) as b1_30,
				sum(CASE WHEN a.duedate BETWEEN '$_31' AND '$_60' then a.totaltagihan else 0 end) as b31_60,
				sum(CASE WHEN a.duedate BETWEEN '$_61' AND '$_90' then a.totaltagihan else 0 end) as b61_90,
				sum(CASE WHEN a.duedate BETWEEN '$_181' AND '$_365' then a.totaltagihan else 0 end) as b181_365,
				sum(CASE WHEN a.duedate > '$_365' then a.totaltagihan else 0 end) as lebih_1th
				FROM tbl_apoap AS a 
				LEFT JOIN tbl_vendor AS b ON b.vendor_id = a.vendor_id 
				group by a.vendor_id,b.vendor_name
				ORDER BY a.vendor_id")->result();

				$query_list_03	= $this->db->query("SELECT invoice_no AS nobukti, tglinvoice AS tanggal, CONCAT('TAGIHAN') AS ketarangan, 0 AS saldoawal, totaltagihan AS pembelian, 0 AS pembayaran, 0 AS saldoakhir 
				FROM tbl_apoap
				WHERE tglinvoice BETWEEN '$fromdate' AND '$todate'
				
				UNION ALL 
				
				SELECT ap_id AS nobukti, pay_date AS tanggal, ket AS keterangan, 0 AS saldoawal, 0 AS pembelian, totalbayar AS pembayaran, 0 AS saldoakhir 
				FROM tbl_hap
				WHERE pay_date BETWEEN '$fromdate' AND '$todate'")->result();
			} else 
			if($allcabang == 1){
				$getvendor	= $this->db->query("SELECT * FROM tbl_vendor WHERE vendor_id = '$vendor'")->row();

				$query_list_01	= $this->db->query("SELECT a.koders, b.vendor_name, a.invoice_no, a.tglinvoice, a.duedate, a.totaltagihan, a.notukar, a.terima_no 
				FROM tbl_apoap AS a 
				LEFT JOIN tbl_vendor AS b ON b.vendor_id = a.vendor_id
				WHERE a.tglinvoice BETWEEN '$fromdate' AND '$todate' 
				AND a.vendor_id = '$vendor'
				GROUP BY a.invoice_no
				ORDER BY a.tglinvoice DESC")->result();

				$fromdate = strtotime($fromdate);

				$_1 = date("Y-m-d", strtotime('+1 day', $fromdate));
				$_30 = date("Y-m-d", strtotime('+1 month', $fromdate));
				$_31 = date("Y-m-d", strtotime('+31 day', $fromdate));
				$_60 = date("Y-m-d", strtotime('+2 month', $fromdate));
				$_61 = date("Y-m-d", strtotime('+61 day', $fromdate));
				$_90 = date("Y-m-d", strtotime('+3 month', $fromdate));
				$_181 = date("Y-m-d", strtotime('+181 day', $fromdate));
				$_365 = date("Y-m-d", strtotime('+1 year', $fromdate));

				$query_list_02	= $this->db->query("SELECT a.vendor_id,b.vendor_name,
				sum(CASE WHEN a.duedate < '$fromdate' then a.totaltagihan else 0 end) as belum_jt,
				sum(CASE WHEN a.duedate BETWEEN '$fromdate' AND '$todate' then a.totaltagihan else 0 end) as jatuh_tempo,
				sum(CASE WHEN a.duedate BETWEEN '$_1' AND '$_30' then a.totaltagihan else 0 end) as b1_30,
				sum(CASE WHEN a.duedate BETWEEN '$_31' AND '$_60' then a.totaltagihan else 0 end) as b31_60,
				sum(CASE WHEN a.duedate BETWEEN '$_61' AND '$_90' then a.totaltagihan else 0 end) as b61_90,
				sum(CASE WHEN a.duedate BETWEEN '$_181' AND '$_365' then a.totaltagihan else 0 end) as b181_365,
				sum(CASE WHEN a.duedate > '$_365' then a.totaltagihan else 0 end) as lebih_1th
				FROM tbl_apoap AS a 
				LEFT JOIN tbl_vendor AS b ON b.vendor_id = a.vendor_id 
				WHERE a.tglinvoice BETWEEN '$fromdate' AND '$todate' 
				AND a.vendor_id = '$vendor'
				GROUP BY a.vendor_id,b.vendor_name  
				ORDER BY a.tglinvoice DESC")->result();

				$query_list_03	= $this->db->query("SELECT invoice_no AS nobukti, tglinvoice AS tanggal, CONCAT('TAGIHAN') AS ketarangan, 0 AS saldoawal, totaltagihan AS pembelian, 0 AS pembayaran, 0 AS saldoakhir 
				FROM tbl_apoap
				WHERE tglinvoice BETWEEN '$fromdate' AND '$todate' 
				AND vendor_id = '$vendor'

				UNION ALL 

				SELECT ap_id AS nobukti, pay_date AS tanggal, ket AS keterangan, 0 AS saldoawal, 0 AS pembelian, totalbayar AS pembayaran, 0 AS saldoakhir 
				FROM tbl_hap
				WHERE pay_date BETWEEN '$fromdate' AND '$todate' 
				AND vendor_id = '$vendor'")->result();
			} else 
			if($allvendor == 1){
				$query_list_01	= $this->db->query("SELECT a.koders, b.vendor_name, a.invoice_no, a.tglinvoice, a.duedate, a.totaltagihan, a.notukar, a.terima_no 
				FROM tbl_apoap AS a 
				LEFT JOIN tbl_vendor AS b ON b.vendor_id = a.vendor_id
				WHERE a.tglinvoice BETWEEN '$fromdate' AND '$todate' 
				AND a.koders = '$unit'
				GROUP BY a.invoice_no
				ORDER BY a.tglinvoice DESC")->result();

				$fromdate = strtotime($fromdate);

				$_1 = date("Y-m-d", strtotime('+1 day', $fromdate));
				$_30 = date("Y-m-d", strtotime('+1 month', $fromdate));
				$_31 = date("Y-m-d", strtotime('+31 day', $fromdate));
				$_60 = date("Y-m-d", strtotime('+2 month', $fromdate));
				$_61 = date("Y-m-d", strtotime('+61 day', $fromdate));
				$_90 = date("Y-m-d", strtotime('+3 month', $fromdate));
				$_181 = date("Y-m-d", strtotime('+181 day', $fromdate));
				$_365 = date("Y-m-d", strtotime('+1 year', $fromdate));

				$query_list_02	= $this->db->query("SELECT a.vendor_id,b.vendor_name,
				sum(CASE WHEN a.duedate < '$fromdate' then a.totaltagihan else 0 end) as belum_jt,
				sum(CASE WHEN a.duedate BETWEEN '$fromdate' AND '$todate' then a.totaltagihan else 0 end) as jatuh_tempo,
				sum(CASE WHEN a.duedate BETWEEN '$_1' AND '$_30' then a.totaltagihan else 0 end) as b1_30,
				sum(CASE WHEN a.duedate BETWEEN '$_31' AND '$_60' then a.totaltagihan else 0 end) as b31_60,
				sum(CASE WHEN a.duedate BETWEEN '$_61' AND '$_90' then a.totaltagihan else 0 end) as b61_90,
				sum(CASE WHEN a.duedate BETWEEN '$_181' AND '$_365' then a.totaltagihan else 0 end) as b181_365,
				sum(CASE WHEN a.duedate > '$_365' then a.totaltagihan else 0 end) as lebih_1th
				FROM tbl_apoap AS a 
				LEFT JOIN tbl_vendor AS b ON b.vendor_id = a.vendor_id 
				WHERE a.tglinvoice BETWEEN '$fromdate' AND '$todate' 
				AND a.koders = '$unit'
				GROUP BY a.vendor_id,b.vendor_name 
				ORDER BY a.tglinvoice DESC")->result();

				$query_list_03	= $this->db->query("SELECT invoice_no AS nobukti, tglinvoice AS tanggal, CONCAT('TAGIHAN') AS ketarangan, 0 AS saldoawal, totaltagihan AS pembelian, 0 AS pembayaran, 0 AS saldoakhir 
				FROM tbl_apoap
				WHERE tglinvoice BETWEEN '$fromdate' AND '$todate' 
				AND koders = '$unit'

				UNION ALL 

				SELECT ap_id AS nobukti, pay_date AS tanggal, ket AS keterangan, 0 AS saldoawal, 0 AS pembelian, totalbayar AS pembayaran, 0 AS saldoakhir 
				FROM tbl_hap
				WHERE pay_date BETWEEN '$fromdate' AND '$todate' 
				AND koders = '$unit'")->result();
			} else {
				$getvendor	= $this->db->query("SELECT * FROM tbl_vendor WHERE vendor_id = '$vendor'")->row();

				$query_list_01	= $this->db->query("SELECT a.koders, b.vendor_name, a.invoice_no, a.tglinvoice, a.duedate, a.totaltagihan, a.notukar, a.terima_no 
				FROM tbl_apoap AS a 
				LEFT JOIN tbl_vendor AS b ON b.vendor_id = a.vendor_id
				WHERE a.tglinvoice BETWEEN '$fromdate' AND '$todate' 
				AND a.koders = '$cabang' 
				AND a.vendor_id = '$vendor'
				GROUP BY a.invoice_no
				ORDER BY a.tglinvoice DESC")->result();

				$fromdate = strtotime($fromdate);

				$_1 = date("Y-m-d", strtotime('+1 day', $fromdate));
				$_30 = date("Y-m-d", strtotime('+1 month', $fromdate));
				$_31 = date("Y-m-d", strtotime('+31 day', $fromdate));
				$_60 = date("Y-m-d", strtotime('+2 month', $fromdate));
				$_61 = date("Y-m-d", strtotime('+61 day', $fromdate));
				$_90 = date("Y-m-d", strtotime('+3 month', $fromdate));
				$_181 = date("Y-m-d", strtotime('+181 day', $fromdate));
				$_365 = date("Y-m-d", strtotime('+1 year', $fromdate));

				$query_list_02	= $this->db->query("SELECT a.vendor_id,b.vendor_name,
				sum(CASE WHEN a.duedate < '$fromdate' then a.totaltagihan else 0 end) as belum_jt,
				sum(CASE WHEN a.duedate BETWEEN '$fromdate' AND '$todate' then a.totaltagihan else 0 end) as jatuh_tempo,
				sum(CASE WHEN a.duedate BETWEEN '$_1' AND '$_30' then a.totaltagihan else 0 end) as b1_30,
				sum(CASE WHEN a.duedate BETWEEN '$_31' AND '$_60' then a.totaltagihan else 0 end) as b31_60,
				sum(CASE WHEN a.duedate BETWEEN '$_61' AND '$_90' then a.totaltagihan else 0 end) as b61_90,
				sum(CASE WHEN a.duedate BETWEEN '$_181' AND '$_365' then a.totaltagihan else 0 end) as b181_365,
				sum(CASE WHEN a.duedate > '$_365' then a.totaltagihan else 0 end) as lebih_1th
				FROM tbl_apoap AS a 
				LEFT JOIN tbl_vendor AS b ON b.vendor_id = a.vendor_id 
				WHERE a.tglinvoice BETWEEN '$fromdate' AND '$todate' 
				AND a.koders = '$cabang' 
				AND a.vendor_id = '$vendor'
				GROUP BY a.vendor_id,b.vendor_name 
				ORDER BY a.tglinvoice DESC")->result();

				$query_list_03	= $this->db->query("SELECT invoice_no AS nobukti, tglinvoice AS tanggal, CONCAT('TAGIHAN') AS ketarangan, 0 AS saldoawal, totaltagihan AS pembelian, 0 AS pembayaran, 0 AS saldoakhir 
				FROM tbl_apoap
				WHERE tglinvoice BETWEEN '$fromdate' AND '$todate' 
				AND koders = '$cabang' 
				AND vendor_id = '$vendor'

				UNION ALL 

				SELECT ap_id AS nobukti, pay_date AS tanggal, ket AS keterangan, 0 AS saldoawal, 0 AS pembelian, totalbayar AS pembayaran, 0 AS saldoakhir 
				FROM tbl_hap
				WHERE pay_date BETWEEN '$fromdate' AND '$todate' 
				AND koders = '$cabang' 
				AND vendor_id = '$vendor'")->result();
			}

			if($laporan == 1){
				$data .= "<style>
					.table {border-collapse:collapse;font-family: Century Gothic;font-size:14px;color:#000;width:100%;margin:auto}
					.bordered {padding:5px;border:1px solid #222}
					.centered {text-align:center;margin:auto}
					.bold {font-weight:bold}
					.subtitle {font-size:12px}
					.title {font-size:18px;margin-top:10px}
					.separator {border:115px solid #222;}
					.date {font-size:12px}
				</style>";

				$data .= "<table class='table'>	
					<thead>
						<tr>
							<td rowspan='6' align='center'>
								<img src='" . $comp_image . "'  width='100' height='70' />
							</td>
							<td colspan='20'>
								<tr><td style='font-size:14px;border-bottom: none;'><b>$comp_name</b></td></tr>
								<tr><td style='font-size:13px;'>$comp_addr</td></tr>
								<tr><td style='font-size:13px;'>Telp :$comp_phone </td></tr>
							</td>
						</tr> 
					</thead>
				</table>
				
				<br />

				<div class='title centered'>". $title ."</div>
				<div class='date centered'>Dari&emsp;<b>". date("d-m-Y", strtotime($this->input->get("fromdate"))) ."</b>&emsp;s/d&emsp;<b>". date("d-m-Y", strtotime($this->input->get("todate"))) ."</b></div>
				
				<br />";

				$data .= '<table class="table">
				<thead>
					<tr>
						<th class="bordered centered">Nama Pemasok</th>
						<th class="bordered centered">No Invoice</th>
						<th class="bordered centered">Tgl Invoice</th>
						<th class="bordered centered">Jatuh Tempo</th>
						<th class="bordered centered">Umur</th>
						<th class="bordered centered">Jumlah Tagihan</th>
						<th class="bordered centered">Jumlah Yang Dibayar</th>
						<th class="bordered centered">No Tukar</th>
						<th class="bordered centered">Saldo Akhir</th>
					</tr>
				</thead>
				<tbody>';
				foreach($query_list_01 as $rkey => $rval){
					$dob = date("Y-m-d",strtotime($rval->tglinvoice));
					$don = date("Y-m-d",strtotime($rval->duedate));
					$dobObject = new DateTime($dob);
					$nowObject = new DateTime($don);
					$diff = $dobObject->diff($nowObject);
					$umur = $diff->y ." tahun, ". $diff->m ." bulan";

					$terima	= $this->db->query("SELECT SUM(dibayar) as total FROM tbl_dap WHERE terima_no = '$rval->terima_no'")->row();

					$saldoakhir	= round($rval->totaltagihan, 0) - round($terima->total, 0);

					$data	.= "<tr>
						<td class='bordered'>". $rval->vendor_name ."</td>
						<td class='bordered centered'>". $rval->invoice_no ."</td>
						<td class='bordered centered'>". date("d-m-y", strtotime($rval->tglinvoice)) ."</td>
						<td class='bordered centered'>". date("d-m-y", strtotime($rval->duedate)) ."</td>
						<td class='bordered centered'>". $umur ."</td>
						<td class='bordered'>". number_format($rval->totaltagihan, 0, ',', '.') ."</td>
						<td class='bordered'>". number_format($terima->total, 0, ',', '.') ."</td>
						<td class='bordered'>". $rval->notukar ."</td>
						<td class='bordered'>". number_format($saldoakhir, 0, ',', '.') ."</td>
					</tr>";
				}
				$data .= '<tbody></table>';
			} else 
			if($laporan	== 2){
				$data .= "<style>
					.table {border-collapse:collapse;font-family: Century Gothic;font-size:14px;color:#000;width:100%;margin:auto}
					.bordered {padding:5px;border:1px solid #222}
					.centered {text-align:center;margin:auto}
					.bold {font-weight:bold}
					.subtitle {font-size:12px}
					.title {font-size:18px;margin-top:10px}
					.separator {border:115px solid #222;}
					.date {font-size:12px}
				</style>";

				$data .= "<table class='table'>	
					<thead>
						<tr>
							<td rowspan='6' align='center'>
								<img src='" . $comp_image . "'  width='100' height='70' />
							</td>
							<td colspan='20'>
								<tr><td style='font-size:14px;border-bottom: none;'><b>$comp_name</b></td></tr>
								<tr><td style='font-size:13px;'>$comp_addr</td></tr>
								<tr><td style='font-size:13px;'>Telp :$comp_phone </td></tr>
							</td>
						</tr> 
					</thead>
				</table>
				
				<br />

				<div class='title centered'>". $title ."</div>
				<div class='date centered'>Dari&emsp;<b>". date("d-m-Y", strtotime($this->input->get("fromdate"))) ."</b>&emsp;s/d&emsp;<b>". date("d-m-Y", strtotime($this->input->get("todate"))) ."</b></div>
				
				<br />";

				$data .= '<table class="table">
				<thead>
					<tr>
						<th class="bordered centered">Vendor</th>
						<th class="bordered centered">Belum Jt Tempo</th>
						<th class="bordered centered">1 s/d 30 Hari</th>
						<th class="bordered centered">31 s/d 60 Hari</th>
						<th class="bordered centered">61 s/d 90 Hari</th>
						<th class="bordered centered">181 s/d 365 Hari</th>
						<th class="bordered centered">> 1 Tahun</th>
						<th class="bordered centered">Total Hutang</th>
					</tr>
				</thead>
				<tbody>';

				foreach($query_list_02 as $rkey => $rval){
					// $tanggalsekarang	= new DateTime($todate);
					// $tanggaljt			= new DateTime($rval->duedate);
					
					// $betweendate		= $tanggaljt->diff($tanggalsekarang)->format("%r%a");

					// if($lapora == 1){
					// 	$belumjt	= $this->db->query("");
					// }

					// $belum_jt	= $this->db->query("SELECT SUM(totaltagihan) AS total FROM tbl_apoap 
					// WHERE duedate BETWEEN '". date("Y-m-d", strtotime($fromdate)) ."'")->row();

					// if($)

					// $data		.= "<tr>
					// 	<td class='bordered'>". $rval->vendor_name ."</td>
					// 	<td align='right' class='bordered'>". $belum_jt->total ."</td>
					// 	<td align='right' class='bordered'></td>
					// 	<td align='right' class='bordered'></td>
					// 	<td align='right' class='bordered'></td>
					// 	<td align='right' class='bordered'></td>
					// 	<td align='right' class='bordered'></td>
					// 	<td align='right' class='bordered'></td>
					// </tr>";

					$total	= $rval->belum_jt + $rval->b1_30 + $rval->b31_60 + $rval->b61_90 + $rval->b181_365 + $rval->lebih_1th;
					
					$data		.= "<tr>
						<td class='bordered'>". $rval->vendor_name ."</td>
						<td align=\"right\" class='bordered'>". number_format($rval->belum_jt, 0, ',', '.') ."</td>
						<td align=\"right\" class='bordered'>". number_format($rval->b1_30, 0, ',', '.') ."</td>
						<td align=\"right\" class='bordered'>". number_format($rval->b31_60, 0, ',', '.') ."</td>
						<td align=\"right\" class='bordered'>". number_format($rval->b61_90, 0, ',', '.') ."</td>
						<td align=\"right\" class='bordered'>". number_format($rval->b181_365, 0, ',', '.') ."</td>
						<td align=\"right\" class='bordered'>". number_format($rval->lebih_1th, 0, ',', '.') ."</td>
						<td align=\"right\" class='bordered'>". number_format($total, 0, ',', '.') ."</td>
					</tr>";
				}
				$data	.= "</tbody></table>";
			} else 
			if($laporan == 5){
				$data .= "<style>
					.table {border-collapse:collapse;font-family: Century Gothic;font-size:14px;color:#000;width:100%;margin:auto}
					.bordered {padding:5px;border:1px solid #222}
					.centered {text-align:center;margin:auto}
					.bold {font-weight:bold}
					.subtitle {font-size:12px}
					.title {font-size:18px;margin-top:10px}
					.separator {border:115px solid #222;}
					.date {font-size:12px}
				</style>";

				$data .= "<table class='table'>	
					<thead>
						<tr>
							<td rowspan='6' align='center'>
								<img src='" . $comp_image . "'  width='100' height='70' />
							</td>
							<td colspan='20'>
								<tr><td style='font-size:14px;border-bottom: none;'><b>$comp_name</b></td></tr>
								<tr><td style='font-size:13px;'>$comp_addr</td></tr>
								<tr><td style='font-size:13px;'>Telp :$comp_phone </td></tr>
							</td>
						</tr> 
					</thead>
				</table>
				
				<br />

				<div class='title centered'>". $title ."</div>
				<div class='date centered'>Dari&emsp;<b>". date("d-m-Y", strtotime($this->input->get("fromdate"))) ."</b>&emsp;s/d&emsp;<b>". date("d-m-Y", strtotime($this->input->get("todate"))) ."</b></div>
				
				<br />";

				$data .= '
				<p>Pemasok/Supplier : <b>'. data_master("tbl_vendor", array("vendor_id" => $vendor))->vendor_name .'</b></p><br /><table class="table">
				<thead>
					<tr>
						<th class="bordered centered" style="width:15%">No Bukti</th>
						<th class="bordered centered" style="width:10%">Tanggal</th>
						<th class="bordered centered" style="width:15%">Keterangan</th>
						<th class="bordered centered" style="width:15%">Saldo Awal</th>
						<th class="bordered centered" style="width:15%">Pembelian</th>
						<th class="bordered centered" style="width:15%">Pembayaran</th>
						<th class="bordered centered" style="width:15%">Saldo Akhir</th>
					</tr>
				</thead>
				<tbody>';
				foreach($query_list_03 as $rkey => $rval){
					$data	.= "<tr>
						<td class='bordered'>". $rval->nobukti ."</td>
						<td class='bordered'>". date("Y-m-d", strtotime($rval->tanggal)) ."</td>
						<td class='bordered'>". $rval->ketarangan ."</td>
						<td class='bordered'>". number_format($rval->saldoawal, 0, ',', '.') ."</td>
						<td class='bordered'>". number_format($rval->pembelian, 0, ',', '.') ."</td>
						<td class='bordered'>". number_format($rval->pembayaran, 0, ',', '.') ."</td>
						<td class='bordered'>". number_format($rval->saldoakhir, 0, ',', '.') ."</td>
					</tr>";
				}
				$data	.= "</tbody></table>";
			} else {
				redirect("/hutang_lap/");
			}

			$this->M_cetak->mpdf('L','LEGAL',$title ." ". date("dmy") ."_". date("His"), $data,'LAPORAN_'. date("Ymd") .'.PDF', 0, 0, 9, 2);
		} else {
			redirect("/");
		}
	}

	public function excel(){
		$cek 	= $this->session->userdata('level');
		$unit	= $this->session->userdata('unit');

		if(!empty($cek)){
			$data 		= "";
			$printinit	= $this->M_cetak->kop2();
			$allcabang	= $this->input->get("allcabang");
			$cabang		= $this->input->get("cabang");
			$allvendor	= $this->input->get("allvendor");
			$vendor		= $this->input->get("vendor");
			$fromdate	= $this->input->get("fromdate");
			$todate		= $this->input->get("todate");
			$laporan	= $this->input->get("laporan");

			switch($laporan){
				case 1 : $title = "LAPORAN UMUR HUTANG RS"; break;
				case 2 : $title = "REKAP UMUR HUTANG RS"; break;
				case 5 : $title = "KARTU HUTANG"; break;
				case 6 : $title = ""; break;
				case 7 : $title = ""; break;
				default : redirect("/hutang_lap/"); break;
			}

			$comp_name	= $printinit->namars;
			$comp_addr	= $printinit->alamat;
			$comp_phone	= $printinit->phone;
			$comp_image	= base_url().$printinit->image;

			if($allvendor == 1 && $allcabang == 1){
				$query_list_01	= $this->db->query("SELECT a.koders, b.vendor_name, a.invoice_no, a.tglinvoice, a.duedate, a.totaltagihan, a.notukar, a.terima_no 
				FROM tbl_apoap AS a 
				LEFT JOIN tbl_vendor AS b ON b.vendor_id = a.vendor_id
				WHERE a.tglinvoice BETWEEN '$fromdate' AND '$todate'
				GROUP BY a.invoice_no
				ORDER BY a.tglinvoice DESC")->result();

				// $query_list_02	= $this->db->query("SELECT a.koders, a.vendor_id, b.vendor_name, a.invoice_no, a.tglinvoice, a.duedate, a.totaltagihan, a.notukar, a.terima_no 
				// FROM tbl_apoap AS a 
				// LEFT JOIN tbl_vendor AS b ON b.vendor_id = a.vendor_id
				// WHERE a.tglinvoice BETWEEN '$fromdate' AND '$todate'
				// GROUP BY a.vendor_id 
				// ORDER BY a.tglinvoice DESC")->result();

				$fromdate = strtotime($fromdate);

				$_1 = date("Y-m-d", strtotime('+1 day', $fromdate));
				$_30 = date("Y-m-d", strtotime('+1 month', $fromdate));
				$_31 = date("Y-m-d", strtotime('+31 day', $fromdate));
				$_60 = date("Y-m-d", strtotime('+2 month', $fromdate));
				$_61 = date("Y-m-d", strtotime('+61 day', $fromdate));
				$_90 = date("Y-m-d", strtotime('+3 month', $fromdate));
				$_181 = date("Y-m-d", strtotime('+181 day', $fromdate));
				$_365 = date("Y-m-d", strtotime('+1 year', $fromdate));

				$query_list_02	= $this->db->query("SELECT a.vendor_id,b.vendor_name,
				sum(CASE WHEN a.duedate < '$fromdate' then a.totaltagihan else 0 end) as belum_jt,
				sum(CASE WHEN a.duedate BETWEEN '$fromdate' AND '$todate' then a.totaltagihan else 0 end) as jatuh_tempo,
				sum(CASE WHEN a.duedate BETWEEN '$_1' AND '$_30' then a.totaltagihan else 0 end) as b1_30,
				sum(CASE WHEN a.duedate BETWEEN '$_31' AND '$_60' then a.totaltagihan else 0 end) as b31_60,
				sum(CASE WHEN a.duedate BETWEEN '$_61' AND '$_90' then a.totaltagihan else 0 end) as b61_90,
				sum(CASE WHEN a.duedate BETWEEN '$_181' AND '$_365' then a.totaltagihan else 0 end) as b181_365,
				sum(CASE WHEN a.duedate > '$_365' then a.totaltagihan else 0 end) as lebih_1th
				FROM tbl_apoap AS a 
				LEFT JOIN tbl_vendor AS b ON b.vendor_id = a.vendor_id 
				group by a.vendor_id,b.vendor_name
				ORDER BY a.vendor_id")->result();

				$query_list_03	= $this->db->query("SELECT invoice_no AS nobukti, tglinvoice AS tanggal, CONCAT('TAGIHAN') AS ketarangan, 0 AS saldoawal, totaltagihan AS pembelian, 0 AS pembayaran, 0 AS saldoakhir 
				FROM tbl_apoap
				WHERE tglinvoice BETWEEN '$fromdate' AND '$todate'
				
				UNION ALL 
				
				SELECT ap_id AS nobukti, pay_date AS tanggal, ket AS keterangan, 0 AS saldoawal, 0 AS pembelian, totalbayar AS pembayaran, 0 AS saldoakhir 
				FROM tbl_hap
				WHERE pay_date BETWEEN '$fromdate' AND '$todate'")->result();
			} else 
			if($allcabang == 1){
				$getvendor	= $this->db->query("SELECT * FROM tbl_vendor WHERE vendor_id = '$vendor'")->row();

				$query_list_01	= $this->db->query("SELECT a.koders, b.vendor_name, a.invoice_no, a.tglinvoice, a.duedate, a.totaltagihan, a.notukar, a.terima_no 
				FROM tbl_apoap AS a 
				LEFT JOIN tbl_vendor AS b ON b.vendor_id = a.vendor_id
				WHERE a.tglinvoice BETWEEN '$fromdate' AND '$todate' 
				AND a.vendor_id = '$vendor'
				GROUP BY a.invoice_no
				ORDER BY a.tglinvoice DESC")->result();

				$fromdate = strtotime($fromdate);

				$_1 = date("Y-m-d", strtotime('+1 day', $fromdate));
				$_30 = date("Y-m-d", strtotime('+1 month', $fromdate));
				$_31 = date("Y-m-d", strtotime('+31 day', $fromdate));
				$_60 = date("Y-m-d", strtotime('+2 month', $fromdate));
				$_61 = date("Y-m-d", strtotime('+61 day', $fromdate));
				$_90 = date("Y-m-d", strtotime('+3 month', $fromdate));
				$_181 = date("Y-m-d", strtotime('+181 day', $fromdate));
				$_365 = date("Y-m-d", strtotime('+1 year', $fromdate));

				$query_list_02	= $this->db->query("SELECT a.vendor_id,b.vendor_name,
				sum(CASE WHEN a.duedate < '$fromdate' then a.totaltagihan else 0 end) as belum_jt,
				sum(CASE WHEN a.duedate BETWEEN '$fromdate' AND '$todate' then a.totaltagihan else 0 end) as jatuh_tempo,
				sum(CASE WHEN a.duedate BETWEEN '$_1' AND '$_30' then a.totaltagihan else 0 end) as b1_30,
				sum(CASE WHEN a.duedate BETWEEN '$_31' AND '$_60' then a.totaltagihan else 0 end) as b31_60,
				sum(CASE WHEN a.duedate BETWEEN '$_61' AND '$_90' then a.totaltagihan else 0 end) as b61_90,
				sum(CASE WHEN a.duedate BETWEEN '$_181' AND '$_365' then a.totaltagihan else 0 end) as b181_365,
				sum(CASE WHEN a.duedate > '$_365' then a.totaltagihan else 0 end) as lebih_1th
				FROM tbl_apoap AS a 
				LEFT JOIN tbl_vendor AS b ON b.vendor_id = a.vendor_id 
				WHERE a.tglinvoice BETWEEN '$fromdate' AND '$todate' 
				AND a.vendor_id = '$vendor'
				GROUP BY a.vendor_id,b.vendor_name  
				ORDER BY a.tglinvoice DESC")->result();

				$query_list_03	= $this->db->query("SELECT invoice_no AS nobukti, tglinvoice AS tanggal, CONCAT('TAGIHAN') AS ketarangan, 0 AS saldoawal, totaltagihan AS pembelian, 0 AS pembayaran, 0 AS saldoakhir 
				FROM tbl_apoap
				WHERE tglinvoice BETWEEN '$fromdate' AND '$todate' 
				AND vendor_id = '$vendor'

				UNION ALL 

				SELECT ap_id AS nobukti, pay_date AS tanggal, ket AS keterangan, 0 AS saldoawal, 0 AS pembelian, totalbayar AS pembayaran, 0 AS saldoakhir 
				FROM tbl_hap
				WHERE pay_date BETWEEN '$fromdate' AND '$todate' 
				AND vendor_id = '$vendor'")->result();
			} else 
			if($allvendor == 1){
				$query_list_01	= $this->db->query("SELECT a.koders, b.vendor_name, a.invoice_no, a.tglinvoice, a.duedate, a.totaltagihan, a.notukar, a.terima_no 
				FROM tbl_apoap AS a 
				LEFT JOIN tbl_vendor AS b ON b.vendor_id = a.vendor_id
				WHERE a.tglinvoice BETWEEN '$fromdate' AND '$todate' 
				AND a.koders = '$unit'
				GROUP BY a.invoice_no
				ORDER BY a.tglinvoice DESC")->result();

				$fromdate = strtotime($fromdate);

				$_1 = date("Y-m-d", strtotime('+1 day', $fromdate));
				$_30 = date("Y-m-d", strtotime('+1 month', $fromdate));
				$_31 = date("Y-m-d", strtotime('+31 day', $fromdate));
				$_60 = date("Y-m-d", strtotime('+2 month', $fromdate));
				$_61 = date("Y-m-d", strtotime('+61 day', $fromdate));
				$_90 = date("Y-m-d", strtotime('+3 month', $fromdate));
				$_181 = date("Y-m-d", strtotime('+181 day', $fromdate));
				$_365 = date("Y-m-d", strtotime('+1 year', $fromdate));

				$query_list_02	= $this->db->query("SELECT a.vendor_id,b.vendor_name,
				sum(CASE WHEN a.duedate < '$fromdate' then a.totaltagihan else 0 end) as belum_jt,
				sum(CASE WHEN a.duedate BETWEEN '$fromdate' AND '$todate' then a.totaltagihan else 0 end) as jatuh_tempo,
				sum(CASE WHEN a.duedate BETWEEN '$_1' AND '$_30' then a.totaltagihan else 0 end) as b1_30,
				sum(CASE WHEN a.duedate BETWEEN '$_31' AND '$_60' then a.totaltagihan else 0 end) as b31_60,
				sum(CASE WHEN a.duedate BETWEEN '$_61' AND '$_90' then a.totaltagihan else 0 end) as b61_90,
				sum(CASE WHEN a.duedate BETWEEN '$_181' AND '$_365' then a.totaltagihan else 0 end) as b181_365,
				sum(CASE WHEN a.duedate > '$_365' then a.totaltagihan else 0 end) as lebih_1th
				FROM tbl_apoap AS a 
				LEFT JOIN tbl_vendor AS b ON b.vendor_id = a.vendor_id 
				WHERE a.tglinvoice BETWEEN '$fromdate' AND '$todate' 
				AND a.koders = '$unit'
				GROUP BY a.vendor_id,b.vendor_name 
				ORDER BY a.tglinvoice DESC")->result();

				$query_list_03	= $this->db->query("SELECT invoice_no AS nobukti, tglinvoice AS tanggal, CONCAT('TAGIHAN') AS ketarangan, 0 AS saldoawal, totaltagihan AS pembelian, 0 AS pembayaran, 0 AS saldoakhir 
				FROM tbl_apoap
				WHERE tglinvoice BETWEEN '$fromdate' AND '$todate' 
				AND koders = '$unit'

				UNION ALL 

				SELECT ap_id AS nobukti, pay_date AS tanggal, ket AS keterangan, 0 AS saldoawal, 0 AS pembelian, totalbayar AS pembayaran, 0 AS saldoakhir 
				FROM tbl_hap
				WHERE pay_date BETWEEN '$fromdate' AND '$todate' 
				AND koders = '$unit'")->result();
			} else {
				$getvendor	= $this->db->query("SELECT * FROM tbl_vendor WHERE vendor_id = '$vendor'")->row();

				$query_list_01	= $this->db->query("SELECT a.koders, b.vendor_name, a.invoice_no, a.tglinvoice, a.duedate, a.totaltagihan, a.notukar, a.terima_no 
				FROM tbl_apoap AS a 
				LEFT JOIN tbl_vendor AS b ON b.vendor_id = a.vendor_id
				WHERE a.tglinvoice BETWEEN '$fromdate' AND '$todate' 
				AND a.koders = '$cabang' 
				AND a.vendor_id = '$vendor'
				GROUP BY a.invoice_no
				ORDER BY a.tglinvoice DESC")->result();

				$fromdate = strtotime($fromdate);

				$_1 = date("Y-m-d", strtotime('+1 day', $fromdate));
				$_30 = date("Y-m-d", strtotime('+1 month', $fromdate));
				$_31 = date("Y-m-d", strtotime('+31 day', $fromdate));
				$_60 = date("Y-m-d", strtotime('+2 month', $fromdate));
				$_61 = date("Y-m-d", strtotime('+61 day', $fromdate));
				$_90 = date("Y-m-d", strtotime('+3 month', $fromdate));
				$_181 = date("Y-m-d", strtotime('+181 day', $fromdate));
				$_365 = date("Y-m-d", strtotime('+1 year', $fromdate));

				$query_list_02	= $this->db->query("SELECT a.vendor_id,b.vendor_name,
				sum(CASE WHEN a.duedate < '$fromdate' then a.totaltagihan else 0 end) as belum_jt,
				sum(CASE WHEN a.duedate BETWEEN '$fromdate' AND '$todate' then a.totaltagihan else 0 end) as jatuh_tempo,
				sum(CASE WHEN a.duedate BETWEEN '$_1' AND '$_30' then a.totaltagihan else 0 end) as b1_30,
				sum(CASE WHEN a.duedate BETWEEN '$_31' AND '$_60' then a.totaltagihan else 0 end) as b31_60,
				sum(CASE WHEN a.duedate BETWEEN '$_61' AND '$_90' then a.totaltagihan else 0 end) as b61_90,
				sum(CASE WHEN a.duedate BETWEEN '$_181' AND '$_365' then a.totaltagihan else 0 end) as b181_365,
				sum(CASE WHEN a.duedate > '$_365' then a.totaltagihan else 0 end) as lebih_1th
				FROM tbl_apoap AS a 
				LEFT JOIN tbl_vendor AS b ON b.vendor_id = a.vendor_id 
				WHERE a.tglinvoice BETWEEN '$fromdate' AND '$todate' 
				AND a.koders = '$cabang' 
				AND a.vendor_id = '$vendor'
				GROUP BY a.vendor_id,b.vendor_name 
				ORDER BY a.tglinvoice DESC")->result();

				$query_list_03	= $this->db->query("SELECT invoice_no AS nobukti, tglinvoice AS tanggal, CONCAT('TAGIHAN') AS ketarangan, 0 AS saldoawal, totaltagihan AS pembelian, 0 AS pembayaran, 0 AS saldoakhir 
				FROM tbl_apoap
				WHERE tglinvoice BETWEEN '$fromdate' AND '$todate' 
				AND koders = '$cabang' 
				AND vendor_id = '$vendor'

				UNION ALL 

				SELECT ap_id AS nobukti, pay_date AS tanggal, ket AS keterangan, 0 AS saldoawal, 0 AS pembelian, totalbayar AS pembayaran, 0 AS saldoakhir 
				FROM tbl_hap
				WHERE pay_date BETWEEN '$fromdate' AND '$todate' 
				AND koders = '$cabang' 
				AND vendor_id = '$vendor'")->result();
			}

			if($laporan == 1){
				$data	.= "<table style='border-collapse:collapse;font-family: Century Gothic;font-size:12px;color:#000;width:100%;margin:auto'>	
					<thead>
						<tr>
							<th style='border-bottom:1px solid #222;width:10%' colspan='1'>
								<center><img src='" . $comp_image . "'  width='100' height='75'></center>
							</th>
							<th style='border-bottom:1px solid #222;width:90%;font-weight:normal;text-align:left' colspan='8'>
								<b>$comp_name</b><br />$comp_addr<br />Telp :$comp_phone
							</th>
						</tr> 
					</thead>

					<tbody>
						<tr>
							<td colspan='9'>&nbsp;</td>
						</tr>
						<tr>
							<td colspan='9' style='font-size:16px;text-align:center'><br /><b>". $title ."</b><br /><b>". $title ."</b><br />Dari&emsp;<b>". date("d-m-Y", strtotime($this->input->get("fromdate"))) ."</b>&emsp;s/d&emsp;<b>". date("d-m-Y", strtotime($this->input->get("todate"))) ."</b><br /></td>
						</tr>
						<tr>
							<td colspan='9'>&nbsp;</td>
						</tr>
					</tbody>
				</table>";
				
				$data .= "<table style='border-collapse:collapse;font-family: Century Gothic;font-size:12px;color:#000;width:100%;margin:auto'>
				<thead>
					<tr>
						<th style='border:1px solid #222'>Nama Pemasok</th>
						<th style='border:1px solid #222'>No Invoice</th>
						<th style='border:1px solid #222'>Tgl Invoice</th>
						<th style='border:1px solid #222'>Jatuh Tempo</th>
						<th style='border:1px solid #222'>Umur</th>
						<th style='border:1px solid #222'>Jumlah Tagihan</th>
						<th style='border:1px solid #222'>Jumlah Yang Dibayar</th>
						<th style='border:1px solid #222'>No Tukar</th>
						<th style='border:1px solid #222'>Saldo Akhir</th>
					</tr>
				</thead>
				<tbody>";
				foreach($query_list_01 as $rkey => $rval){
					$dob = date("Y-m-d",strtotime($rval->tglinvoice));
					$don = date("Y-m-d",strtotime($rval->duedate));
					$dobObject = new DateTime($dob);
					$nowObject = new DateTime($don);
					$diff = $dobObject->diff($nowObject);
					$umur = $diff->y ." tahun, ". $diff->m ." bulan";

					$terima	= $this->db->query("SELECT SUM(dibayar) as total FROM tbl_dap WHERE terima_no = '$rval->terima_no'")->row();

					$saldoakhir	= round($rval->totaltagihan, 0) - round($terima->total, 0);

					$data	.= "<tr>
						<td style='border:1px solid #222'>". $rval->vendor_name ."</td>
						<td style='border:1px solid #222;text-align:center'>". $rval->invoice_no ."</td>
						<td style='border:1px solid #222;text-align:center'>". date("d-m-y", strtotime($rval->tglinvoice)) ."</td>
						<td style='border:1px solid #222;text-align:center'>". date("d-m-y", strtotime($rval->duedate)) ."</td>
						<td style='border:1px solid #222'>". $umur ."</td>
						<td style='border:1px solid #222'>". number_format($rval->totaltagihan, 0, ',', '.') ."</td>
						<td style='border:1px solid #222'>". number_format($terima->total, 0, ',', '.') ."</td>
						<td style='border:1px solid #222'>". $rval->notukar ."</td>
						<td style='border:1px solid #222'>". number_format($saldoakhir, 0, ',', '.') ."</td>
					</tr>";
				}
				$data .= '<tbody></table>';
			} else 
			if($laporan == 2){
				$data	.= "<table style='border-collapse:collapse;font-family: Century Gothic;font-size:12px;color:#000;width:100%;margin:auto'>	
					<thead>
						<tr>
							<th style='border-bottom:1px solid #222;width:10%' colspan='1'>
								<center><img src='" . $comp_image . "'  width='100' height='75'></center>
							</th>
							<th style='border-bottom:1px solid #222;width:90%;font-weight:normal;text-align:left' colspan='7'>
								<b>$comp_name</b><br />$comp_addr<br />Telp :$comp_phone
							</th>
						</tr> 
					</thead>

					<tbody>
						<tr>
							<td colspan='8'>&nbsp;</td>
						</tr>
						<tr>
							<td colspan='8' style='font-size:16px;text-align:center'><br /><b>". $title ."</b><br />Dari&emsp;<b>". date("d-m-Y", strtotime($this->input->get("fromdate"))) ."</b>&emsp;s/d&emsp;<b>". date("d-m-Y", strtotime($this->input->get("todate"))) ."</b><br /></td>
						</tr>
						<tr>
							<td colspan='8'>&nbsp;</td>
						</tr>
					</tbody>
				</table>";
				$data .= "<table style='border-collapse:collapse;font-family: Century Gothic;font-size:12px;color:#000;width:100%;margin:auto'>
				<thead>
					<tr>
						<th style='border:1px solid #222'>Vendor</th>
						<th style='border:1px solid #222'>Belum Jt Tempo</th>
						<th style='border:1px solid #222'>1 s/d 30 Hari</th>
						<th style='border:1px solid #222'>31 s/d 60 Hari</th>
						<th style='border:1px solid #222'>61 s/d 90 Hari</th>
						<th style='border:1px solid #222'>181 s/d 365 Hari</th>
						<th style='border:1px solid #222'>> 1 Tahun</th>
						<th style='border:1px solid #222'>Total Hutang</th>
					</tr>
				</thead>
				<tbody>";
				foreach($query_list_02 as $rkey => $rval){
					$total	= $rval->belum_jt + $rval->b1_30 + $rval->b31_60 + $rval->b61_90 + $rval->b181_365 + $rval->lebih_1th;

					$data		.= "<tr>
						<td style='border:1px solid #222'>". $rval->vendor_name ."</td>
						<td style='border:1px solid #222;text-align:right'>". number_format($rval->belum_jt, 0, ',', '.') ."</td>
						<td style='border:1px solid #222;text-align:right'>". number_format($rval->b1_30, 0, ',', '.') ."</td>
						<td style='border:1px solid #222;text-align:right'>". number_format($rval->b31_60, 0, ',', '.') ."</td>
						<td style='border:1px solid #222;text-align:right'>". number_format($rval->b61_90, 0, ',', '.') ."</td>
						<td style='border:1px solid #222;text-align:right'>". number_format($rval->b181_365, 0, ',', '.') ."</td>
						<td style='border:1px solid #222;text-align:right'>". number_format($rval->lebih_1th, 0, ',', '.') ."</td>
						<td style='border:1px solid #222;text-align:right'>". number_format($total, 0, ',', '.') ."</td>
					</tr>";
				}
				$data .= '<tbody></table>';
			} else
			if($laporan == 5){
				$data	.= "<table style='border-collapse:collapse;font-family: Century Gothic;font-size:12px;color:#000;width:100%;margin:auto'>	
					<thead>
						<tr>
							<th style='border-bottom:1px solid #222;width:10%' colspan='1'>
								<center><img src='" . $comp_image . "'  width='100' height='75'></center>
							</th>
							<th style='border-bottom:1px solid #222;width:90%;font-weight:normal;text-align:left' colspan='6'>
								<b>$comp_name</b><br />$comp_addr<br />Telp :$comp_phone
							</th>
						</tr> 
					</thead>

					<tbody>
						<tr>
							<td colspan='7'>&nbsp;</td>
						</tr>
						<tr>
							<td colspan='7' style='font-size:16px;text-align:center'><br /><b>". $title ."</b><br />Dari&emsp;<b>". date("d-m-Y", strtotime($this->input->get("fromdate"))) ."</b>&emsp;s/d&emsp;<b>". date("d-m-Y", strtotime($this->input->get("todate"))) ."</b><br /></td>
						</tr>
						<tr>
							<td colspan='7'>&nbsp;</td>
						</tr>
					</tbody>
				</table>";
				
				$data .= "<table>
					<tr>
						<td colspan='7'>Pemasok/Supplier : <b>". data_master("tbl_vendor", array("vendor_id" => $vendor))->vendor_name ."</b><br /></td>
					</tr>
				</table><table style='border-collapse:collapse;font-family: Century Gothic;font-size:12px;color:#000;width:100%;margin:auto'>
				<thead>
					<tr>
						<th style='border:1px solid #222'>No Bukti</th>
						<th style='border:1px solid #222'>Tanggal</th>
						<th style='border:1px solid #222'>Keterangan</th>
						<th style='border:1px solid #222'>Saldo Awal</th>
						<th style='border:1px solid #222'>Pembelian</th>
						<th style='border:1px solid #222'>Pembayaran</th>
						<th style='border:1px solid #222'>Saldo Akhir</th>
					</tr>
				</thead>
				<tbody>";
				foreach($query_list_03 as $rkey => $rval){
					$data	.= "<tr>
						<td  style='border:1px solid #222;text-align:left'>". $rval->nobukti ."</td>
						<td  style='border:1px solid #222;text-align:center'>". date("Y-m-d", strtotime($rval->tanggal)) ."</td>
						<td  style='border:1px solid #222'>". $rval->ketarangan ."</td>
						<td  style='border:1px solid #222'>". number_format($rval->saldoawal, 0, ',', '.') ."</td>
						<td  style='border:1px solid #222'>". number_format($rval->pembelian, 0, ',', '.') ."</td>
						<td  style='border:1px solid #222'>". number_format($rval->pembayaran, 0, ',', '.') ."</td>
						<td  style='border:1px solid #222'>". number_format($rval->saldoakhir, 0, ',', '.') ."</td>
					</tr>";
				}
				$data .= '<tbody></table>';
			} else {
				redirect("/hutang_lap/");
			}

			$excel['prev'] = $data;
			header("Cache-Control: no-cache, no-store, must-revalidate");
			header("Content-Type: application/vnd-ms-excel");
			header("Content-Disposition: attachment; filename= $title ". date("dmy") ."_". date("His") .".xls");
			$this->load->view('app/master_cetak', $excel);
		} else {
			redirect("/");
		}
	}

}
/* End of file Hutang_lap.php */
/* Location: ./application/controllers/Hutang_lap.php */