<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_bayar extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct();
		$this->session->set_userdata('menuapp', '5000');
		$this->session->set_userdata('submenuapp', '5105');
		$this->load->helper('simkeu_nota');
		$this->load->model('M_kas_bank');
		$this->load->model('M_keuangan_keluar');
		$this->load->model('M_rs');
		$this->load->model('M_cetak');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        		
		if(!empty($cek))
		{	
		  $d['keu'] = ''; 
		  $bulan =  $this->M_global->_periodebulan();
		  $tahun =  $this->M_global->_periodetahun();
		  $nbulan = $this->M_global->_namabulan($bulan);
	        
		  $select = $this->M_kas_bank->getPembayaranHutang($unit,'','');       
		  
		  if($select){
		  	$d['keu'] = $select;	
		  }
		  
		  $periode= 'Periode '.$nbulan.'-'.$this->M_global->_periodetahun();	   
		  $d['periode'] = $periode;		  
		  $level=$this->session->userdata('level');		
		  $akses= $this->M_global->cek_menu_akses($level, 5105);
		  $d['akses']= $akses;
		
		  //   echo "<pre>";
		//   print_r($d);
		//   echo "</pre>";
		
			$this->load->view('pembelian/v_pembelian_bayar',$d);			   
		
		} else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	public function filter($param)
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        		
		if(!empty($cek))
		{	
			$data  = explode("~",$param);
			$jns   = $data[0];		
			$tgl1  = $data[1];
			$tgl2  = $data[2];
			$_tgl1 = date('Y-m-d',strtotime($tgl1));
			$_tgl2 = date('Y-m-d',strtotime($tgl2));
			
			if(!empty($jns))
			{					
				// $q1 = 
				// 	"select a.*, b.vendor_name
				// 	from
				// 		tbl_hap a left outer join
				// 		tbl_vendor b on a.vendor_id=b.vendor_id
				// 	where				   
				// 		a.koders = '$unit' and 
				// 		a.pay_date between '$_tgl1' and '$_tgl2'
				// 	order by
				// 		a.pay_date, a.ap_id desc";
				
						
		  		// $d['keu'] = $this->db->query($q1)->result();
				$d['keu'] = '';
				$select = $this->M_kas_bank->getPembayaranHutang($unit, $_tgl1, $_tgl2);       
								
				if($select){
					$d['keu'] = $select;	
				}

				$periode= 'Periode '.date('d-m-Y',strtotime($tgl1)).' s/d '.date('d-m-Y',strtotime($tgl2));	   
				$d['periode'] = $periode;		  
				
				$level=$this->session->userdata('level');		
				$akses= $this->M_global->cek_menu_akses($level, 5105);
				$d['akses']= $akses;
				$this->load->view('pembelian/v_pembelian_bayar',$d);			   
			} else {
				header('location:'.base_url());
			}
		} else
		{
			header('location:'.base_url());	
		}
			
		
	}
		
	public function getData()
	{
		$level = $this->session->userdata('level');	
        if(!empty($level))
		{
			$unit = $this->session->userdata('unit');	
			$page=$this->uri->segment(3);
			$limit=$this->config->item('limit_data');	 
			
			$vendor = $this->input->get('vendor');
			$startdate = $this->input->get('startdate');
			$enddate = $this->input->get('enddate');
			$dt = $this->M_kas_bank->getHutang($unit, $vendor, $startdate, $enddate);

			echo json_encode($dt);
		} else
		{
			header('location:'.base_url());
		}
	}
	
	public function cetak($id){
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{	
			$unit= $this->session->userdata('unit');
			$userid= $this->session->userdata('username');
			$rs = $this->M_rs->getNamaRsById($unit);
			if($rs){				
				foreach($rs as $dtrs);

				$wa = '';$fax = '';
				if($dtrs->whatsapp != '') $wa = " / ".$dtrs->whatsapp;
				if($dtrs->fax != '') $fax = " / ".$dtrs->fax;

				$telp = ($dtrs->phone)."".$wa."".$fax;
				// $dt = $this->M_hutang->getTukarFakturById($id);
				$dt = $this->M_kas_bank->getPembayaranHutanById($id, $unit);

				if($dt){
					foreach($dt as $data);
					$judul = '';$chari = '';
					// $chari = $this->load->view('pembelian/v_hutang_cetak',$d);

					$totalbayar = number_format($data->totalbayar,0,'.',',');
					// $terbilang = ucwords($this->M_global->terbilang($data->totalsemua))." Rupiah";
					$date_create = date_create($data->pay_date);
					$tanggal = date_format($date_create,'d-m-Y');
					// $date = date('d-m-Y');
					// $penerima = '_______________';
					
					$chari .= "

						<table style='border-collapse:collapse;font-family: tahoma; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
							<b>
								<tr >
									<td rowspan='4' align='center' width='20%'>
										<img src=\"" . base_url() . "assets/img/logo.png\"  width=\"75\" height=\"75\"  
										style='padding:-1px;' />
									</td>
									<td>$dtrs->namars</td>
								</tr>
								<tr  width='80%'>
									<td>$dtrs->alamat</td>
								</tr>
								<tr  width='80%'>
									<td>$dtrs->kota</td>
								</tr>
								<tr  width='80%'>
									<td>$telp</td>
								</tr>
							</b>
						</table>
						<br>";

					$chari .= "<div width='100%' style='border-top:1px solid black;'></div>";
					
					$chari .= "<table style='border-collapse:collapse;font-family: tahoma; font-weight: bold;font-size:20px' width='100%' align='center' border='0' cellspacing='0' cellpadding='3'>
						<tr class='show1'>
							<td align='center'>Pembayaran Tagihan</td>
						</tr>
					</table>
					<br>
					";


					$chari .= "
					<table style='border-collapse:collapse;font-family: tahoma; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='3'>
						<tr rowspan='6' class='show1'>
							<td align='left'  width='5%'>Vendor</td>
							<td align='center'  width='5%'>:</td>
							<td align='left'  width='40%'>$data->vendor_name</td>
						
							<td align='left'  width='15%'>No. Urut</td>
							<td align='center'  width='5%'>:</td>
							<td align='left'  width='30%'>$data->ap_id</td>
						</tr>		
						
						<tr class='show1'>
							<td align='left'  width='15%'></td>
							<td align='center'  width='5%'></td>
							<td align='left'  width='30%'></td>
						
							<td align='left'  width='15%'>Tanggal</td>
							<td align='center'  width='5%'>:</td>
							<td align='left'  width='30%'>$tanggal</td>
						</tr>		
						<tr class='show1'>
							<td align='left'  width='15%'></td>
							<td align='center'  width='5%'></td>
							<td align='left'  width='30%'></td>
						
							<td align='left'  width='15%'>Bank</td>
							<td align='center'  width='5%'>:</td>
							<td align='left'  width='30%'>$data->acname</td>
						</tr>		
						<tr class='show1'>
							<td align='left'  width='15%'></td>
							<td align='center'  width='5%'></td>
							<td align='left'  width='30%'></td>
						
							<td align='left'  width='15%'>Cek No.</td>
							<td align='center'  width='5%'>:</td>
							<td align='left'  width='30%'>$data->cek_no</td>
						</tr>		
						<tr class='show1'>
							<td align='left'  width='15%'></td>
							<td align='center'  width='5%'></td>
							<td align='left'  width='30%'></td>
						
							<td align='left'  width='15%'>Keterangan</td>
							<td align='center'  width='5%'>:</td>
							<td align='left'  width='30%'>$data->ket</td>
						</tr>	
						<tr class='show1'>
							<td align='left'  width='15%'></td>
							<td align='center'  width='5%'></td>
							<td align='left'  width='30%'></td>
						
							<td align='left'  width='15%'>Total Rp</td>
							<td align='center'  width='5%'>:</td>
							<td align='left'  width='30%'>$totalbayar</td>
						</tr>			

					</table>";

					$chari .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">

					<thead>
						<tr>
							<td style=\"border:0\" align=\"center\"><br></td>                
						</tr>
						<tr>
							<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>No.</b> </td>
							<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>Invoice</b> </td>
							<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>Tanggal</b> </td>
							<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>Jatuh Tempo</b> </td>
							<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>Total Tagihan</b> </td>
							<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>Total Bayar</b> </td>
						</tr>
						
					</thead>";

					$detail = $this->M_kas_bank->getDetailPembayaranHutangById($id, $unit);
					$date_now = date('d-m-Y');
					if($detail){
						$i = 1;
						foreach($detail as $dtl){
							
							$ttlHutang = number_format($dtl->totalhutang,0,'.',',');
							$ttlBayar = number_format($dtl->dibayar,0,'.',',');
							$tglinvoice = date('d-m-Y', strtotime($dtl->tglinvoice));
							$due_date = date('d-m-Y', strtotime($dtl->due_date));
							$chari .= "
								<tr class='show1'>
									<td align='center'>$i.</td>
									<td >$dtl->invoice_no</td>
									<td align='right'>$tglinvoice</td>
									<td align='right'>$due_date</td>
									<td align='right'>$ttlHutang</td>
									<td align='right'>$ttlBayar</td>
								</tr>";
							$i++;
						}
					}
					
				$chari .= "</table>";

				$chari .= "				
					<br><br>
					<table style='border-collapse:collapse;font-family: tahoma; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
						<tr class='show1'>
							<td align='center' width='25%'></td>
							<td align='center' width='25%'></td>
							<td align='center' width='25%'></td>
							<td align='center' width='25%'>Jakarta, $date_now</td>
						</tr>
						<tr class='show1'>
							<td align='center' width='25%'>Pemohon</td>
							<td align='center' width='25%'>Keuangan</td>
							<td align='center' width='25%'>Penanggung Jawab</td>
							<td align='center' width='25%'>Pembukuan</td>
						</tr>
					</table>

					<br><br><br>
					
					<table style='border-collapse:collapse;font-family: tahoma; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
						<tr class='show1'>
							<td align='center' width='25%'><b>$userid</b></td>
							<td align='center' width='25%'><b>ANDARA MULYA</b></td>
							<td align='center' width='25%'><b>ERIKA</b></td>
							<td align='center' width='25%'><b>Pembukuan</b></td>
						</tr>
					</table>
					<table style='border-collapse:collapse;font-family: tahoma; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
						<tr class='show1'>
							<td align='center' width='25%'>Hospital Management System</td>
							<td align='center' width='25%'></td>
							<td align='center' width='25%'>Kepala Cabang</td>
							<td align='center' width='25%'></td>
						</tr>
					</table>
				";
					// $chari .= "
					// 	<tr class='show1'>
					// 		<td align='left' width='30%'>Guna Pembayaran</td>
					// 		<td align='center'  width='5%'>:</td>
					// 		<td align='left'  width='60%'>$data->keterangan</td>
					// 	</tr>

					// 	<tr class='show1'>
					// 		<td align='left' width='30%'>Dapat diambil Tanggal</td>
					// 		<td align='center'  width='5%'>:</td>
					// 		<td align='left'  width='60%'>$diambil</td>
					// 	</tr>

					// 	</tbody>
					// 	</table>

					// 	<br>
					// 	<div width='100%' style='border-top:1px dashed black;'></div>
					// 	<br>

					// 	<table style='border-collapse:collapse;font-family: tahoma; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
					// 		<tr class='show1'>
					// 			<td align='center' width='50%'></td>
					// 			<td align='center' width='50%'>Jakarta, $date</td>
					// 		</tr>
					// 		<tr class='show1'>
					// 			<td align='center' width='50%'>Penerima:</td>
					// 			<td align='center' width='50%'>Keuangan</td>
					// 		</tr>
					// 	</table>

					// 	<br><br><br>
						
					// 	<table style='border-collapse:collapse;font-family: tahoma; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
					// 		<tr class='show1'>
					// 			<td align='center' width='50%'><b>$penerima</b></td>
					// 			<td align='center' width='50%'><b>$data->username</b></td>
					// 		</tr>
					// 	</table>
					// ";

					$this->M_cetak->mpdf('P','A4',$judul, $chari,'Faktur_Baru_Manual.PDF', 10, 10, 10, 0);
				} else {
					header('location:'.base_url());
				}
			} else {
				header('location:'.base_url());
			}
		} else
		{
			header('location:'.base_url());
		}	
	}

	public function cetak2($param)
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');		
		if(!empty($cek))
		{				  		 
            
			//$profile = $this->M_global->_LoadProfileLap();	
		    $unit= $this->session->userdata('unit');	 
		    /*
			$nama_usaha=$profile->nama_usaha;
			$alamat1  = $profile->alamat1;
			$alamat2  = $profile->alamat2;
		    */
			
			$nama_usaha='';
			$alamat1  = '';
			$alamat2  = '';
			
		    $queryh = "select * from tbl_hap inner join tbl_vendor on 
			tbl_hap.vendor_id=tbl_vendor.vendor_id where ap_id = '$param'";
			$queryd = "select * from tbl_dap where ap_id = '$param'";
			 
		    $detil  = $this->db->query($queryd)->result();
			$header = $this->db->query($queryh)->row();
			$pdf=new simkeu_nota();
			
			$pdf->setID($nama_usaha,$alamat1,$alamat2);
			$pdf->setjudul('');
			$pdf->setsubjudul('');
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(70,30,90));
			$border = array('T','','BT');
			$size   = array('','','');
			$pdf->setfont('Arial','B',18);
			$pdf->SetAligns(array('C','C','C'));
			$align = array('L','C','L');
			$style = array('','','B');
			$size  = array('12','','18');
			$max   = array(5,5,20);
			$judul=array('Kepada :','','Pembayaran Vendor');
			$fc     = array('0','0','0');
			$hc     = array('20','20','20');
			$pdf->FancyRow2(10,$judul, $fc,  $border, $align, $style, $size, $max);
			$pdf->ln(1);
			$pdf->setfont('Arial','B',10);
			$pdf->SetWidths(array(70,30,30,5,55));
			$border = array('','','','','');
			$fc     = array('0','0','0','0','0');
			$pdf->SetFillColor(230,230,230);			
			$pdf->setfont('Arial','',9);
			$pdf->FancyRow(array($header->vendor_name,'','Nomor',':',$header->ap_id), $fc, $border);
			$pdf->FancyRow(array($header->alamat,'','Tanggal',':',date('d M Y',strtotime($header->pay_date))), $fc, $border);
			$pdf->FancyRow(array($header->contact,'','Pembayaran',':',number_format($header->totalbayar,0)), $fc, $border);
			$pdf->FancyRow(array($header->phone,'','','',''), $fc, $border);
			
			$pdf->ln(2);
			
			$pdf->SetWidths(array(40,25,25,25,25,25,25));
			$border = array('TB','TB','TB','TB','TB','TB','TB');
			$align  = array('L','L','R','R','R','R','R');
			$pdf->setfont('Arial','B',10);
			$pdf->SetAligns(array('L','C','R'));
			//$pdf->SetFillColor(0,0,139);
			//$pdf->settextcolor(255,255,255);
			$fc = array('0','0','0','0','0','0','0');
			$judul=array('No. Faktur','Tgl. Faktur','Total Faktur','Diskon','PPH 23','Uang Muka','Pembayaran');
			$pdf->FancyRow2(8,$judul,$fc, $border,$align);
			$border = array('','','');
			$pdf->setfont('Arial','',10);
			$tot = 0;
			$subtot = 0;
			$tdisc  = 0;
			$border = array('','','','','','','');
			$align  = array('L','L','R','R','R','R','R');
			$fc = array('0','0','0','0','0','0','0');
			$pdf->SetFillColor(0,0,139);
			$pdf->settextcolor(0);
			foreach($detil as $db)
			{
			 
			  $tot = $tot + $db->bayar; 	
			  
			  $pdf->FancyRow(array(
			  $db->invoice_no, 
			  date('d-m-Y',strtotime($db->tglinvoice)),
			  number_format($db->totalhutang,0,'.',','),
			  number_format(0,0,'.',','),
			  0,
			  number_format(0,0,'.',','),
			  number_format($db->dibayar,0,'.',',')),$fc, $border, $align);
			  
			}
			
			
			
			$pdf->SetWidths(array(190));
			$border = array('TB');
			$align  = array('L');
			$pdf->SetFillColor(230,230,230);
			$pdf->settextcolor(0);
			$pdf->SetFillColor(230,230,230);
			$fc = array('0');
			$pdf->FancyRow(array(''),0,$border);
			$fc = array('0');
			$pdf->FancyRow(array(ucwords($this->M_global->terbilang($tot))),$fc, $border, $align,0);
			
			$pdf->SetFillColor(230);
			$border = array('B','B','B','B','B','B');
			//$pdf->FancyRow(array('', '', '','','',''),0,$border);
			$pdf->ln(2);
			$pdf->SetWidths(array(100,20,50,20));
			$border = array('TB','','','');
			$align  = array('L','','C','');
			$pdf->SetFillColor(230,230,230);
			$pdf->settextcolor(0);
			$fc = array('0','0','0','0');
			
			$pdf->FancyRow(array('Keterangan', '', 'Mengetahui',''),$fc, $border, $align,0);
			
			$border = array('','','','');
			$pdf->FancyRow(array($header->ket, '', '',''),$fc, $border, $align,0);
			
			
			$pdf->ln(1);
			$pdf->ln(15);
			$border = array('','','B','');
			$pdf->FancyRow(array('', '', '',''),$fc, $border, $align,0);
			$border = array('','','','');
			$align  = array('L','','L','');
			$pdf->FancyRow(array('', '', 'Tgl.',''),$fc, $border, $align,0);
			
			$pdf->AliasNbPages();
			$pdf->output($param.'.PDF','I');			
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
	public function getfaktur($supp)
	{   	    
        $data = $this->db->query("select tbl_apoap.*, totaltagihan as total, (totaltagihan-totalbayar) as hutang from tbl_apoap where vendor_id='$supp' and totaltagihan>totalbayar")->result();
		$vdata = array();
		foreach($data as $row){			
			$vdata[] = array(
			 'nomorfaktur' => $row->invoice_no,
			 'kodepu' => $row->terima_no,
			 'tglpu' => date('d-m-Y',strtotime($row->tglinvoice)),
			 'totalpu' => $row->totaltagihan,
			 'uangmuka' => 0,
			 'hutang' => $row->hutang,
			 'total' => $row->total,
			);
		}
		echo json_encode($vdata);
	}
	
	public function getfaktur_entry($nomor)
	{   	    
        $data = $this->db->select('tbl_dap.*, date_format(tglinvoice, "%d-%m-%Y") as tanggal')->
		get_where('tbl_dap',array('ap_id' => $nomor))->result();		
		echo json_encode($data);
	}
	
	
	public function entri()
	{
		$cek = $this->session->userdata('level');		
		$uid = $this->session->userdata('unit');		
		if(!empty($cek))
		{				  
		  $d['nomorbukti'] = 'Auto';
		  $page=$this->uri->segment(3);
		  $limit=$this->config->item('limit_data');	
        //   $d['nomor']= urut_transaksi('AP_APO',19);
		  $d['jenis_pembayaran'] = $this->M_keuangan_keluar->getJenisPembayaran();

		  $this->load->view('pembelian/v_pembelian_bayar_add',$d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}

	public function entri2()
	{
		$cek = $this->session->userdata('level');		
		$uid = $this->session->userdata('unit');		
		if(!empty($cek))
		{				  
		  $page=$this->uri->segment(3);
		  $limit=$this->config->item('limit_data');	
          $d['nomor']= urut_transaksi('AP_APO',19);
		  $this->load->view('pembelian/v_pembelian_bayar_add2',$d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	public function hapus($nomor)
	{
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');		
		if(!empty($cek))
		{				  
		   
		//    $bayar = $this->db->get_where('tbl_dap', array('ap_id' => $nomor))->result();				  
		//    foreach($bayar as $row){
		// 	  $_bayar = $row->bayar;			  
		// 	  $faktur = $row->terima_no;
		// 	  $this->db->set('totalbayar', 'totalbayar - '.$_bayar,FALSE);
		// 	  $this->db->where('terima_no', $faktur); 
		// 	  $this->db->update('tbl_apoap'); 	  		      
		//    }
			
			$bayar = $this->db->get_where('tbl_dap', array('ap_id' => $nomor))->result();
			foreach($bayar as $row){
				$sum_dibayar = ($row->dibayar) + ($row->adjustment) + ($row->diskonrp);
											
				$this->db->set('totalbayar', 'totalbayar - '.$sum_dibayar,FALSE);
				$this->db->where('terima_no', $row->terima_no); 
				$this->db->update('tbl_apoap'); 	
				
				$cek_ttl_byr = $this->M_kas_bank->getTotalBayar($row->terima_no, $unit);

				if($cek_ttl_byr[0]->totaltagihan >= 0){
					if($cek_ttl_byr[0]->totalbayar < $cek_ttl_byr[0]->totaltagihan){
						$this->db->where('terima_no', $row->terima_no);
						$this->db->set('lunas', 0, FALSE); 
						$this->db->update('tbl_apoap');
					}	
				} else {
					if($cek_ttl_byr[0]->totalbayar > $cek_ttl_byr[0]->totaltagihan){
						$this->db->where('terima_no', $row->terima_no);
						$this->db->set('lunas', 0, FALSE); 
						$this->db->update('tbl_apoap');
					}
				}
			}
		
			$qry_d = $this->db->delete('tbl_dap',array('ap_id' => $nomor));
			$qry_h = $this->db->delete('tbl_hap',array('ap_id' => $nomor));

			////    $this->M_global->_hapusjurnal($nomor, 'JP');	

		    if($qry_d && $qry_h) echo json_encode(array("status" => TRUE, "data" => $nomor));
			else echo json_encode(array("status" => FALSE));
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}

	public function hapus2($nomor)
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{				  
		   
		   $bayar = $this->db->get_where('tbl_dap', array('ap_id' => $nomor))->result();				  
		   foreach($bayar as $row){
			  $_bayar = $row->bayar;			  
			  $faktur = $row->terima_no;
			  $this->db->set('totalbayar', 'totalbayar - '.$_bayar,FALSE);
			  $this->db->where('terima_no', $faktur); 
			  $this->db->update('tbl_apoap'); 
			  		      
		   }
		   $this->db->delete('tbl_dap',array('ap_id' => $nomor));
		   $this->db->delete('tbl_hap',array('ap_id' => $nomor));
		   //$this->M_global->_hapusjurnal($nomor, 'JP');	
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
	
	public function getinfobarang( $kode )
	{
		$data = $this->M_global->_data_barang( $kode );
		echo json_encode($data);
	}
	
	public function getinfoakun( $kode )
	{
		$data = $this->M_global->_data_akun( $kode );
		echo json_encode($data);
	}
	
	public function getpoheader( $kodepo )
	{
		$data = $this->db->get_where('ap_pofile',array('kodepo' => $kodepo))->row();
		echo json_encode($data);
	}
	
	public function getbarangname($kode)
	{
		if(!empty($kode))
		{			
	        $query = "select namabarang from inv_barang where kodeitem = '$kode'";
			$data  = $this->db->query($query);
			foreach($data->result_array() as $row)
			{
              echo $row['namabarang'];				
			}
		} else
		{
		  echo "";	
		}
	}
	
	public function save($jenis)
	{
		$cek = $this->session->userdata('level');
		if(!empty($cek))
		{
			$userid   	= $this->session->userdata('username');
			$unit      	= $this->session->userdata('unit');
			
			if($jenis == 1){
				$nomor_transaksi = urut_transaksi('AP_APO',19);
				// $nomor_transaksi			= 1;        
			} else {
				$nomor_transaksi			= $this->input->post('nomorbukti');
			}

			$data = array(
			    'koders' 			=> $unit,
				'vendor_id' 		=> $this->input->post('vendor'),
				'ap_id'			    => $nomor_transaksi,
				'pay_date' 			=> $this->input->post('tanggal_bayar'),
				'cek_no' 			=> $this->input->post('cekgiro'),
				'ket' 				=> $this->input->post('keterangan'),
				'accountno'			=> $this->input->post('kasbank'),
				'pay_type'			=> $this->input->post('jenis_bayar'),
				'totalbayar'		=> str_replace(',','',$this->input->post('jumlah_bayar')),
				'username' 			=> $userid,			
			);
			
			// print_r($data);

			$terima_no		= $this->input->post('terima_no');
			$invoice_no		= $this->input->post('invoice_no');
			$notukar		= $this->input->post('notukar');
			$tglinvoice		= $this->input->post('tglinvoice');
			$duedate		= $this->input->post('duedate');
			$totaltagihan	= $this->input->post('totaltagihan');
			$dibayar		= $this->input->post('dibayar');
			$adjustment		= $this->input->post('adjustment');
			$diskonrp		= $this->input->post('diskonrp');
			$akunjust		= $this->input->post('akunjust');
			$akundiskon		= $this->input->post('akundiskon');

	        if($jenis==1)
			{
				$this->db->insert("tbl_hap", $data);
				if($this->db->affected_rows() > 0){
					
					$x = 0;
					for($i = 0; $i < count($terima_no); $i++){	
						
						$data2 = array(
							'koders' => $unit,
							'ap_id' => $nomor_transaksi,
							'terima_no' => $terima_no[$i],
							'invoice_no' => $invoice_no[$i],
							'notukar' => $notukar[$i],
							'tglinvoice' => $tglinvoice[$i],
							'due_date' => $duedate[$i],
							'totalhutang' => $totaltagihan[$i],
							'dibayar' => str_replace(',','',$dibayar[$i]), 
							'adjustment' => str_replace(',','',$adjustment[$i]),
							'diskonrp' => str_replace(',','',$diskonrp[$i]),
							'akunjust' => $akunjust[$i] != null ? $akunjust[$i] : '0',
							'akundiskon' => $akundiskon[$i] != null ? $akundiskon[$i] : '0',
						);	

						// print_r($data2);

						$this->db->insert('tbl_dap', $data2);
						if($this->db->affected_rows() > 0) {
							// $sum_dibayar = (str_replace(',','',$dibayar[$i]) + str_replace(',','',$adjustment[$i]) + str_replace(',','',$diskonrp[$i]));
							$sum_dibayar = str_replace(',','',$dibayar[$i]);
							
							$query = "
								UPDATE tbl_apoap SET totalbayar = totalbayar+".$sum_dibayar." WHERE terima_no = '".$terima_no[$i]."'";

							$this->db->query($query);

							$lunas = 0;
							$cek_ttl_byr = $this->M_kas_bank->getTotalBayar($terima_no[$i], $unit);
							if($cek_ttl_byr[0]->totalbayar >= $cek_ttl_byr[0]->totaltagihan){
								$lunas = 1;
							}	
							
							$data = array(
								'lunas' => $lunas,
							);
							
							$where = array(
								'terima_no' => $terima_no[$i],
							);
							$this->db->update('tbl_apoap', $data, $where);
							
							$x++;
						} 
					}	

					
					if($x != 0) {
						echo json_encode(array("status" => TRUE, "data" => $nomor_transaksi));
					} else echo json_encode(array("status" => FALSE, "data" => 'Failed 2'));
				} else {
					echo json_encode(array("status" => FALSE, "data" => 'Failed 1'));					
				}
			} else {
				$where = array(
					'ap_id' => $nomor_transaksi,
				);
				$this->db->update('tbl_hap', $data, $where);
				// echo $this->db->last_query();
				// if($this->db->affected_rows() >= 0){
					$qry = "SELECT * FROM tbl_dap WHERE ap_id = '".$nomor_transaksi."'";
					$dap_before = $this->db->query($qry)->result();
							
					$where2 = array(
						'ap_id' => $nomor_transaksi,
					);	
					$this->db->delete('tbl_dap', $where2);		
					
					
					$x = 0;
					for($i = 0; $i < count($terima_no); $i++){	
						
						$data2 = array(
							'koders' => $unit,
							'ap_id' => $nomor_transaksi,
							'terima_no' => $terima_no[$i],
							'invoice_no' => $invoice_no[$i],
							'notukar' => $notukar[$i],
							'tglinvoice' => $tglinvoice[$i],
							'due_date' => $duedate[$i],
							'totalhutang' => $totaltagihan[$i],
							'dibayar' => str_replace(',','',$dibayar[$i]), 
							'adjustment' => str_replace(',','',$adjustment[$i]),
							'diskonrp' => str_replace(',','',$diskonrp[$i]),
							'akunjust' => $akunjust[$i] != null ? $akunjust[$i] : '0',
							'akundiskon' => $akundiskon[$i] != null ? $akundiskon[$i] : '0',
						);	

						// print_r($data2);

						$this->db->insert('tbl_dap', $data2);
						if($this->db->affected_rows() > 0) {
							// $sum_dibayar = (str_replace(',','',$dibayar[$i]) + str_replace(',','',$adjustment[$i]) + str_replace(',','',$diskonrp[$i]));
							$sum_dibayar = str_replace(',','',$dibayar[$i]);

							foreach ($dap_before as $key) {
								if($key->terima_no == $terima_no[$i]){							
									$sum_dibayar_before = ($key->dibayar) + ($key->adjustment) + ($key->diskonrp);
								}
							}
							$query = "
								UPDATE tbl_apoap SET totalbayar = ((totalbayar-".$sum_dibayar_before.")+".$sum_dibayar.") WHERE terima_no = '".$terima_no[$i]."'";

							$this->db->query($query);

							$lunas = 0;
							$cek_ttl_byr = $this->M_kas_bank->getTotalBayar($terima_no[$i], $unit);
							if($cek_ttl_byr[0]->totalbayar >= $cek_ttl_byr[0]->totaltagihan){
								$lunas = 1;
							}	
							
							$data = array(
								'lunas' => $lunas,
							);
							
							$where = array(
								'terima_no' => $terima_no[$i],
							);
							$this->db->update('tbl_apoap', $data, $where);
							
							$x++;
						} 
					}	
					
					if($x != 0) echo json_encode(array("status" => TRUE, "data" => $nomor_transaksi));
					else echo json_encode(array("status" => FALSE));
				// } else {
				// 	echo json_encode(array("status3" => FALSE));					
				// }

			    // echo $this->db->last_query();
			}

		}
		else
		{
			header('location:'.base_url());
		}	
	}
	
	public function save2($param)
	{
		$hasil = 0;
		$cek = $this->session->userdata('level');
		if(!empty($cek))
		{			            
			$nobukti  = $this->input->post('nomorbukti');			
			$userid   = $this->session->userdata('username');
			
			//$jumdata = $this->db->get_where('ap_pufile', array('kodepu' => $nobukti))->num_rows();
			//if($jumdata<1){			
			if($param==2){
				 
				  //update faktur				
                  $bayar = $this->db->get_where('tbl_dap', array('ap_id' => $nobukti))->result();				  
				  foreach($bayar as $row){
					  $_bayar = $row->bayar;
					  $faktur = $row->terima_no;
					  $this->db->set('totalbayar', 'totalbayar - '.$_bayar,FALSE); 
					  $this->db->where('terima_no', $faktur); 
					  $this->db->update('tbl_apoap'); 
					  					
					 
				  }
				  
			}
			$this->db->delete('tbl_dap',array('ap_id' => $nobukti));
			$faktur  = $this->input->post('faktur');
			$tglfaktur   = $this->input->post('tglfaktur');
		    $totfaktur   = $this->input->post('totfaktur');
			$hutang = $this->input->post('hutang');
			$bayar = $this->input->post('bayar');
			$disc  = $this->input->post('disc');
			$uangmuka  = $this->input->post('uangmuka');
		   
			$jumdata  = count($faktur);
						
			$nourut = 1;
			$_totuangmuka = 0;
			
			for($i=0;$i<=$jumdata;$i++)
			{
			    $_faktur   = $faktur[$i];
				$_totfaktur =str_replace(',','',$totfaktur[$i]);
				$_hutang    =str_replace(',','',$hutang[$i]);
				$_bayar     =str_replace(',','',$bayar[$i]);
				$_disc      =str_replace(',','',$disc[$i]);
				$_uangmuka  =str_replace(',','',$uangmuka[$i]);
				
				$_total = $_bayar - $_disc + $_uangmuka;
				
				$_totuangmuka = $_totuangmuka + $_uangmuka;
				
			    $datad = array(
				'koders'   => $this->session->userdata('unit'),
				'ap_id'   => $nobukti,
				'terima_no' => $_faktur,
				'invoice_no' => $_faktur,
				'tglinvoice' => date('Y-m-d',strtotime($tglfaktur[$i])),
				'totalhutang' => $_totfaktur,
				'dibayar' => $_bayar,				
			    );
				if($_total!="0"){
			      $this->db->insert('tbl_dap', $datad);	
				  
				  //update faktur				  				  				 
				  $this->db->set('totalbayar', 'totalbayar + '.$_bayar,FALSE); 
				  $this->db->where('invoice_no', $_faktur); 
				  $this->db->update('tbl_apoap'); 				  				  
				  
				 
				  				 
				}
			}
			
			$_jumlahbayar =str_replace(',','',$this->input->post('jumlahbayar'));
			$data = array(
			    'koders' => $this->session->userdata('unit'),
				'vendor_id' => $this->input->post('supp'),
				'ap_id'  => $this->input->post('nomorbukti'),
				'accountno'  => $this->input->post('kasbank'),
				//'metodebayar'  => $this->input->post('metodebayar'),				
				'pay_date'   => date('Y-m-d',strtotime($this->input->post('tanggal'))),
				'cek_no'  => $this->input->post('nomorcek'),				
				//'tglcek'   => date('Y-m-d',strtotime($this->input->post('tanggalcek'))),
				'totalbayar'  => $_jumlahbayar,				
				'username'=> $userid,
				'ket'     => $this->input->post('keterangan'),				
				'proses'=> 0,			                
			);
					
			/*		
			$this->M_global->_hapusjurnal($this->input->post('nomorbukti'), 'JP');	
			
			$profile = $this->M_global->_LoadProfileLap();			
			$akun_debet     = $profile->akun_hutang;
			$akun_uangmuka  = $profile->akun_uangmuka;
			
			$akun_kredit    = $this->input->post('kasbank');
			
			
			$this->M_global->_rekamjurnal(
			date('Y-m-d',strtotime($this->input->post('tanggal'))),
			$this->input->post('nomorbukti'),
			'JP',
			$this->input->post('supp'),
			1,
			$akun_debet,
			'Pembayaran ke Supplier '.$this->input->post('supp'),
			'Pembayaran ke Supplier',
			$_jumlahbayar+$_totuangmuka,
			0
			);
			
			$this->M_global->_rekamjurnal(
			date('Y-m-d',strtotime($this->input->post('tanggal'))),
			$this->input->post('nomorbukti'),
			'JP',
			$this->input->post('supp'),
			2,
			$akun_kredit,
			'Pembayaran ke Supplier '.$this->input->post('supp'),
			'Pembayaran ke Supplier',
			0,
			$_jumlahbayar
			);
			
			if($_totuangmuka>0){
			$this->M_global->_rekamjurnal(
			date('Y-m-d',strtotime($this->input->post('tanggal'))),
			$this->input->post('nomorbukti'),
			'JP',
			$this->input->post('supp'),
			3,
			$akun_uangmuka,
			'Pembayaran ke Supplier '.$this->input->post('supp'),
			'Pembayaran ke Supplier',
			0,
			$_totuangmuka
			);	
			}
			*/
			
			if($param==1)
			{
			  $this->db->insert('tbl_hap',$data);				  
			} else {
			  $this->db->update('tbl_hap',$data, array('ap_id' => $nobukti));				 
			}
			
			
		}
		else
		{
			header('location:'.base_url());
		}
	}
	
	public function edit($nomor)
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{	
			$unit = $this->session->userdata('unit');	
					
			// $header = $this->db->get_where('tbl_hap', array('ap_id' => $nomor));
			$qry = "SELECT h.*, v.vendor_name, a.acname
					from tbl_hap h
						LEFT JOIN tbl_vendor v ON h.vendor_id = v.vendor_id
						LEFT JOIN tbl_accounting a ON h.accountno = a.accountno
					WHERE h.ap_id = '".$nomor."'";
			$header = $this->db->query($qry);
			
			$qry2 = "SELECT *
				FROM tbl_dap
				WHERE ap_id = '".$nomor."'";
			
			$detail  = $this->db->query($qry2); // = $this->db->get_where('tbl_dap', array('ap_id' => $nomor));
			
			$d['nomorbukti'] = $nomor;
			$d['header']  = $header->result();
			if(count($d['header']) != 0){
				foreach($d['header'] as $key){
					// echo ($key->notukar);
					// echo "<pre>";
					// print_r($key);
					// echo "</pre>";

					$d['vendor_id'] 	= $key->vendor_id;
					$d['vendor'] 	= $key->vendor_name;
					$d['tanggal_bayar'] 	= $key->pay_date;
					$d['accountno'] 	= $key->accountno;
					$d['acname'] 	= $key->acname;
					$d['accountno'] 	= $key->accountno;
					$d['pay_type'] 	= $key->pay_type;
					$d['cekgiro'] = $key->cek_no;			
					$d['keterangan'] = $key->ket;					
					$d['jumlah_bayar'] 	= $key->totalbayar;
				}	

				$d['jenis_pembayaran'] = $this->M_keuangan_keluar->getJenisPembayaran();
				$d['detail']   = $detail->result();			
				// $d['jumdata1']= $detil->num_rows();
				
				if(count($d['detail']) != 0){
					$this->load->view('pembelian/v_pembelian_bayar_edit',$d);
				} else {
					header('location:'.base_url());					
				}
			} else
			{
				header('location:'.base_url());
			} 
		} else
		{
			header('location:'.base_url());
		}	
	}

	public function edit2($nomor)
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{	
			$unit = $this->session->userdata('unit');	
					
			$header = $this->db->get_where('tbl_hap', array('ap_id' => $nomor));
			$detil  = $this->db->get_where('tbl_dap', array('ap_id' => $nomor));
			
			$d['header']  = $header->result();
			$d['detil']   = $detil->result();			
			$d['jumdata1']= $detil->num_rows();				
			$this->load->view('pembelian/v_pembelian_bayar_edit2',$d);
			} else
		{
			header('location:'.base_url());
		}	
	}

	public function getAkunDiskon(){
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{	
			$qry = "SELECT *
					FROM tbl_accounting";
			$dt = $this->db->query($qry)->result();
			
			echo json_encode($dt);
		} else
		{
			header('location:'.base_url());
		}
	}
}