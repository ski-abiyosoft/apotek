<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Author: tripletTrouble (DPS)
 * Github: https://github.com/triplettrouble
 * 
 * Class untuk menghadle saldo kas & bank, dibuat untuk PT. Sistem Kesehatan Indonesia.
 * All right reserved.
 */

class Akuntansi_sa extends CI_Controller 
{	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_akuntansi_sa','M_akuntansi_sa');
		$this->load->model('M_global');
		$this->load->helper('simkeu_rpt');	
		$this->load->library('Cash_and_Bank');	
		$this->session->set_userdata('menuapp', '5000');
		$this->session->set_userdata('submenuapp', '5101');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{  
			$level=$this->session->userdata('level');

			$akses= $this->M_global->cek_menu_akses($level, 5101);
			$d['akses']= $akses;

			$d['startdate'] 	= $this->input->get('tanggal1') ?? date('Y-m') . '-01';
			$d['enddate'] 		= $this->input->get('tanggal2') ?? date('Y-m-d');
			$d['periode'] 		= date('d/m/Y',strtotime($d['startdate']))." - ".date('d/m/Y',strtotime($d['enddate']));

			$d['kas_masuk'] 	= 0;
			$d['kas_keluar']	= 0;
			$d['saldo_kas'] 	= 0;

			$kasperiode = $this->M_akuntansi_sa->kasperiode();

			$kas_masuk 	= $this->M_akuntansi_sa->total_kas_masuk($d['startdate'], $d['enddate']);
			$kas_keluar = $this->M_akuntansi_sa->total_kas_keluar($d['startdate'], $d['enddate']);

			$d['kas_masuk']		= floatval($kas_masuk[0]->kas_bank_masuk);
			$d['kas_keluar']	= floatval($kas_keluar[0]->kas_bank_keluar);
			$d['mutasi'] 		= floatval($kas_masuk[0]->kas_bank_masuk) - floatval($kas_keluar[0]->kas_bank_keluar);

			$d['keu'] = array();
			$dk = $this->M_akuntansi_sa->getDataKasBank($kasperiode[0]->kasperiode, $d['startdate'], $d['enddate']);
			if($dk) $d['keu'] = $dk;

			$this->load->view('akuntansi/v_akuntansi_sa', $d);
		}else {
			header('location:'.base_url());
		}			
	}

	
	public function filter($param)
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	

		if(!empty($cek))
		{	
			
			$d['startdate'] = '';
			$d['enddate'] 	= '';
			$d['vendorid'] 	= '';

			$data  = explode("~",$param);
			$jns   = $data[0];		
			$tgl1  = $data[1];
			$tgl2  = $data[2];
			$_tgl1 = date('Y-m-d',strtotime($tgl1));
			$_tgl2 = date('Y-m-d',strtotime($tgl2));
			
			
			if(!empty($jns))
			{			
				$kasperiode = $this->M_akuntansi_sa->kasperiode();
					
				$kas_masuk = $this->M_akuntansi_sa->total_kas_masuk($kasperiode[0]->kasperiode);
				$kas_keluar = $this->M_akuntansi_sa->total_kas_keluar($kasperiode[0]->kasperiode);

				$d['kas_masuk'] = $kas_masuk[0]->kas_bank_masuk;
				$d['kas_keluar'] = $kas_keluar[0]->kas_bank_keluar;
				$d['mutasi'] = $kas_masuk[0]->kas_bank_masuk - $kas_keluar[0]->kas_bank_keluar;

				$d['keu'] = array();
				$dk = $this->M_akuntansi_sa->getDataKasBank($kasperiode[0]->kasperiode, $tgl1, $tgl2);
				if($dk) $d['keu'] = $dk;

				$d['startdate'] = $_tgl1;
				$d['enddate'] 	= $_tgl2;
				$d['periode'] = date('d/m/Y',strtotime($d['startdate']))." - ".date('d/m/Y',strtotime($d['enddate']));
				$level=$this->session->userdata('level');		
				$akses= $this->M_global->cek_menu_akses($level, 5301);
				$d['akses']= $akses;		  
				$this->load->view('akuntansi/v_akuntansi_sa',$d);			   
			}
		} else
		{
			header('location:'.base_url());
		}
	}

	public function detail()
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        
		if(!empty($cek))
		{	
			$d['keu'] = array();
			$d['accountno'] = $this->input->get('accountno');
			$d['startdate'] = $this->input->get('startdate');
			$d['enddate'] = $this->input->get('enddate');
			$d['akun_kas_bank'] = urldecode($this->input->get('akun_kas_bank'));
			$data_kas = $this->M_akuntansi_sa->getDataKasBank($this->M_akuntansi_sa->kasperiode()[0]->kasperiode, $d['startdate'], $d['enddate']);
			$filtered_data_kas = array_values(array_filter($data_kas, function ($item) use ($d) {
				return $item->accountno == $d['accountno'];
			}));
			$d['saldo_awal'] = $filtered_data_kas[0]->saldo_awal;
			$d['saldo_akhir'] = $filtered_data_kas[0]->saldo_akhir;
			
			$keu = $this->M_akuntansi_sa->getDetailDataKasBank($d['accountno'], $d['startdate'], $d['enddate']);
			if($keu != '' || $keu != null) $d['keu'] = $keu;
			
			// print_r($keu);
			$this->load->view('akuntansi/v_akuntansi_sa_detail',$d);			   
		} else
		{
			header('location:'.base_url());
		}
	}
	
	public function cetak()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek)) {				  
			$page=$this->uri->segment(3);
			$limit=$this->config->item('limit_data');	

			$nama_usaha =  $this->config->item('nama_perusahaan');
			$motto = $this->config->item('motto');
			$alamat =$this->config->item('alamat_perusahaan');
			$bulan = $this->M_global->_periodebulan();
			$tahun = $this->M_global->_periodetahun();
			$unit  = '';
		
			$this->db->select('ms_akun.kodeakun, ms_akun.namaakun, ms_akunsaldo.debet, ms_akunsaldo.kredit, ms_akunsaldo.id')->from('ms_akun');
			$this->db->join('ms_akunsaldo','ms_akunsaldo.kodeakun=ms_akun.kodeakun','left');
			$this->db->where(array('ms_akunsaldo.tahun' => $tahun,'ms_akunsaldo.bulan' => $bulan));
			$saldoawal = $this->db->get()->result();
		
			$pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($unit);
			$pdf->setsubjudul('');
			$pdf->setjudul('SALDO AWAL '.$this->M_global->_namabulan($bulan).'  '.$tahun);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(10,25,90,30,30));
			$pdf->SetAligns(array('C','C','C','C','C'));
			$judul=array('NO','KODE PERKIRAAN','NAMA','DEBET','KREDIT');
			$pdf->setfont('Times','B',10);
			$pdf->row($judul);
			$pdf->SetWidths(array(10,25,90,30,30));
			$pdf->SetAligns(array('C','L','L','R','R'));
			$pdf->setfont('Times','',10);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;
            $tdb = 0;
            $tkr = 0;			
			foreach($saldoawal as $db)
			{
				$tdb += $db->debet;
				$tkr += $db->kredit;
				$pdf->row(array($nourut, $db->kodeakun, $db->namaakun, number_format($db->debet,'2',',','.'),  number_format($db->kredit,'2',',','.')));
				$nourut++;
			}
			$pdf->setfont('Times','B',10);
			$pdf->SetWidths(array(125,30,30));
			$pdf->SetAligns(array('C','R','R'));
            $pdf->row(array('TOTAL', number_format($tdb,'2',',','.'),  number_format($tkr,'2',',','.')));

			$pdf->AliasNbPages();
			$pdf->output('saldoakun.PDF','I');

		}else{
			header('location:'.base_url());
		}
	}
	
	public function export()
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        
		if(!empty($cek))
		{	
			// Akuntansi_sa/export?accountno='.($accountno).'&startdate='.$startdate.'&enddate='.$enddate.'&akun_kas_bank='.$akun_kas_bank
			
			$accountno = $this->input->get('accountno');
			$startdate = $this->input->get('startdate');
			$enddate   = $this->input->get('enddate');
			$akun_kas_bank = urldecode($this->input->get('akun_kas_bank'));
			
			$keu = $this->M_akuntansi_sa->getDetailDataKasBank($accountno, $startdate, $enddate);
			$data_kas = $this->M_akuntansi_sa->getDataKasBank($this->M_akuntansi_sa->kasperiode()[0]->kasperiode, $startdate, $enddate);
			$filtered_data_kas = array_values(array_filter($data_kas, function ($item) use ($accountno) {
				return $item->accountno == $accountno;
			}));
			$saldo_awal = $filtered_data_kas[0]->saldo_awal;
			$saldo_akhir = $filtered_data_kas[0]->saldo_akhir;
			
			// $this->load->view('penjualan/v_detailPiutang_exp',$d);

			$periode = date('d-m-Y', strtotime($startdate))." s.d. ".date('d-m-Y', strtotime($enddate));

			$judul = "Kas & Bank";
			$chari = "";
			$chari .= "<table style='border-collapse:collapse;font-family: tahoma; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='3'>
					<thead class='breadcrumb'>
					<tr>
						<td colspan='8' style='text-align: center'>Mutasi Kas & Bank</td>
					</tr>
					<tr>
						<td colspan='8' style='text-align: center'>Akun $akun_kas_bank</td>
					</tr>
					<tr>
						<td colspan='8' style='text-align: center'>Periode $periode</td>
					</tr>
					</table>
					";

			$chari .= "<br/>";
			
			$chari .= "<table style='border-collapse:collapse;font-family: tahoma; font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='3'>";
			$chari .="<thead class='breadcrumb'>
                        <tr>
                            <th style='text-align: center'>Tanggal</th>
                            <th style='text-align: center'>No Bukti</th>
                            <th style='text-align: center'>Cek/Giro</th>
                            <th style='text-align: center'>Kelompok</th>
                            <th style='text-align: center'>Ketarangan</th>
                            <th style='text-align: center'>Kas Masuk</th>
                            <th style='text-align: center'>Kas Keluar</th>
                            <th style='text-align: center'>Saldo Kas</th>
                        </tr>
                    </thead>
                    <tbody>";
				
				$tanggal = '';
				$kasmasuk = 0;
				$kaskeluar = 0;
				$saldokas = 0;

				$chari .= "<tr>
									<td align='center' colspan='7'>Saldo Awal</td>
									<td align='right'>$saldo_awal</td>
								</tr>";

				foreach($keu as $row)
				{                       
					$saldo_awal += floatval($row->kasmasuk);
					$saldo_awal -= floatval($row->kaskeluar);
					$tanggal = date('d-m-Y',strtotime($row->tanggal)); 
					$kasmasuk = floatval($row->kasmasuk); 
					$kaskeluar = floatval($row->kaskeluar); 
					$saldokas = floatval($saldo_awal); 
					
					$chari .= "<tr>
									<td align='center'>$tanggal</td>
									<td align='center'>$row->nobukti</td>
									<td align='center'>$row->cekgiro</td>
									<td align='center'>$row->kelompok</td>
									<td align='center'>$row->keterangan</td>
									<td align='right'>$kasmasuk</td>
									<td align='right'>$kaskeluar</td>
									<td align='right'>$saldokas</td>
								</tr>";

				} 
				$chari .= "<tr>
									<td align='center' colspan='7'>Saldo Akhir</td>
									<td align='right'>$saldo_awal</td>
								</tr>";
			$chari .= "</tbody>
					</table>";

			// echo $chari;

			// if($id == 1){
			// 	$this->M_cetak->mpdf('P','A4',$judul, $chari,'Piutang.PDF', 10, 10, 10, 1);
				
			// } else {
				header('Content-type: application/vnd-ms-excel');
				header('Content-Disposition: attachment; filename=kas_dan_bank.xls');
				header('Pragma: no-cache');
				header('Expires: 0');
				
				echo $chari;
			// }
		
		} else
		{
			header('location:'.base_url());
		}
	}

	/**
	 * Method untuk menampilkan data transaksi kas masuk selama periode
	 * 
	 */
	public function rincian_kas_masuk ()
	{
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');

		if(!empty($cek)) {
			$start_date = $this->input->get("from");
			$end_date = $this->input->get("to");
			$transactions = $this->cash_and_bank->get_cash_in_detail($start_date, $end_date, $unit);

			$data = [
				"periode" => date('d/m/Y',strtotime($start_date))." - ".date('d/m/Y',strtotime($end_date)),
				"transactions" => $transactions
			];

			return $this->load->view('akuntansi/v_rincian_kas_masuk', $data);
		}else {
			header('location:'.base_url());
		}
	}

	/**
	 * Method untuk menampilkan data transaksi kas keluar selama periode
	 * 
	 */
	public function rincian_kas_keluar ()
	{
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');

		if(!empty($cek)) {
			$start_date = $this->input->get("from");
			$end_date = $this->input->get("to");
			$transactions = $this->cash_and_bank->get_cash_out_detail($start_date, $end_date, $unit);

			$data = [
				"periode" => date('d/m/Y',strtotime($start_date))." - ".date('d/m/Y',strtotime($end_date)),
				"transactions" => $transactions
			];

			return $this->load->view('akuntansi/v_rincian_kas_keluar', $data);
		}else {
			header('location:'.base_url());
		}
	}

	/**
	 * Method untuk menampilkan data transaksi perubahan kas selama periode
	 * 
	 */
	public function rincian_perubahan_kas ()
	{
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');

		if(!empty($cek)) {
			$start_date = $this->input->get("from");
			$end_date = $this->input->get("to");
			$transactions = $this->cash_and_bank->get_cash_flow_detail($start_date, $end_date, $unit);

			$data = [
				"periode" => date('d/m/Y',strtotime($start_date))." - ".date('d/m/Y',strtotime($end_date)),
				"transactions" => $transactions
			];

			return $this->load->view('akuntansi/v_rincian_perubahan_kas', $data);
		}else {
			header('location:'.base_url());
		}
	}
}

/* End of file master_bank.php */
/* Location: ./application/controllers/master_akun.php */