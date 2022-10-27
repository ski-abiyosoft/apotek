<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penjualan_penerimaan extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct();
		$this->session->set_userdata('menuapp', '5000');
		$this->session->set_userdata('submenuapp', '5104');
		$this->load->helper('simkeu_nota');
		$this->load->model('M_keuangan_keluar');
		$this->load->model('M_penjualan_penerimaan');
		$this->load->model('M_rs');
		$this->load->model('M_cetak');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        		
		if(!empty($cek))
		{	
	       
			$d['startdate'] = '';
			$d['enddate'] 	= '';
			$d['vendorid'] 	= '';

			$bulan =  $this->M_global->_periodebulan();
			$tahun =  $this->M_global->_periodetahun();

			$bln = $bulan;
			if(strlen($bln) == 1) $bln = '0'.$bln;

			// $q1 = 
			// 	"select a.*, b.cust_nama
			// 	from
			// 		tbl_harpas a	left outer join
			// 		tbl_penjamin b on a.cust_id=b.cust_id				   
			// 	where				   
			// 		a.koders = '$unit'
			// 		and DATE_FORMAT(ardate,'%Y-%m') = '".$tahun."-".$bln."' 
			// 	order by
			// 		a.ardate, a.arno desc";
			
			$q1 = "SELECT h.id, h.`koders`, h.username, h.arno, h.ardate, p.cust_nama, 
					h.jenisbayar, c.`jenis` AS jenis_bayar,
					CONCAT(h.accountno,' ',a.acname) AS kas_bank, 
					h.totalterima, h.posting
				FROM tbl_harpas h 
					INNER JOIN tbl_penjamin p ON h.cust_id = p.`cust_id`
					INNER JOIN tbl_accounting a ON h.`accountno` = a.`accountno`
					LEFT JOIN tbl_ceque c ON h.`jenisbayar` = c.`id`
				WHERE 				   
			 		h.koders = '$unit'
			 		and DATE_FORMAT(ardate,'%Y-%m') = '".$tahun."-".$bln."' 
			;";
					

			$nbulan = $this->M_global->_namabulan($bulan);
			$periode= 'Periode '.ucwords(strtolower($nbulan)).'-'.$this->M_global->_periodetahun();	   
			$d['keu'] = $this->db->query($q1)->result();
			$level=$this->session->userdata('level');		
			$akses= $this->M_global->cek_menu_akses($level, 5104);
			$d['akses']= $akses;		  
			$d['periode'] = $periode;		  
			$this->load->view('penjualan/v_penjualan_penerimaan',$d);			   

		} else
		{
			
			header('location:'.base_url());
			
		}
	}

	public function index2()
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        		
		if(!empty($cek))
		{	
	       
			$d['startdate'] = '';
			$d['enddate'] 	= '';
			$d['vendorid'] 	= '';

			$bulan =  $this->M_global->_periodebulan();
			$tahun =  $this->M_global->_periodetahun();

			$q1 = 
				"select a.*, b.cust_nama
				from
					tbl_harpas a	left outer join
					tbl_penjamin b on a.cust_id=b.cust_id				   
				where				   
					a.koders = '$unit'
				order by
					a.ardate, a.arno desc";

			$nbulan = $this->M_global->_namabulan($bulan);
			$periode= 'Periode '.$nbulan.'-'.$this->M_global->_periodetahun();	   
			$d['keu'] = $this->db->query($q1)->result();
			$level=$this->session->userdata('level');		
			$akses= $this->M_global->cek_menu_akses($level, 5104);
			$d['akses']= $akses;		  
			$d['periode'] = $periode;		  
			$this->load->view('penjualan/v_penjualan_penerimaan',$d);			   

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
				
			// $q1 = 
			// 		"select a.*, b.cust_nama
			// 		from
			// 		tbl_harpas a	left outer join
			// 		tbl_penjamin b on a.cust_id=b.cust_id				   
			// 		where				   
			// 		a.koders = '$unit' and
			// 		a.ardate between '$_tgl1' and '$_tgl2'
			// 		order by
			// 		a.ardate, a.arno desc";

			$q1 = "SELECT h.id, h.`koders`, h.username, h.arno, h.ardate, p.cust_nama, 
					h.jenisbayar, c.`jenis` AS jenis_bayar,
					CONCAT(h.accountno,' ',a.acname) AS kas_bank, 
					h.totalterima, h.posting
				FROM tbl_harpas h 
					INNER JOIN tbl_penjamin p ON h.cust_id = p.`cust_id`
					INNER JOIN tbl_accounting a ON h.`accountno` = a.`accountno`
					LEFT JOIN tbl_ceque c ON h.`jenisbayar` = c.`id`
				WHERE 				   
			 		h.koders = '$unit'
			 		and ardate >= '$_tgl1' AND ardate  <= '$_tgl2' 
			;";
							
			$periode= 'Periode '.date('d-m-Y',strtotime($tgl1)).' s/d '.date('d-m-Y',strtotime($tgl2));	   
			$d['keu'] = $this->db->query($q1)->result();
			$level=$this->session->userdata('level');		
			$akses= $this->M_global->cek_menu_akses($level, 5104);
			$d['akses']= $akses;
			$d['periode'] = $periode;		  
			$this->load->view('penjualan/v_penjualan_penerimaan',$d);			   
			}
		} else
		{
			
			header('location:'.base_url());
			
		}
			
		
	}
		
	
	public function cetak($nomor){
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');		
		if(!empty($cek))
		{		
			$rs = $this->M_rs->getNamaRsById($unit);
			if($rs){				
				foreach($rs as $dtrs);

				$wa = '';$fax = '';
				if($dtrs->whatsapp != '') $wa = " / ".$dtrs->whatsapp;
				if($dtrs->fax != '') $fax = " / ".$dtrs->fax;

				$telp = ($dtrs->phone)."".$wa."".$fax;
				// $dt = $this->M_kas_bank->getPembayaranHutanById($id, $unit);
				
				// echo $nomor;

				$this->db->select('tbl_harpas.*, tbl_accounting.acname, tbl_penjamin.cust_nama');
				$this->db->join('tbl_accounting', 'tbl_accounting.accountno = tbl_harpas.accountno', 'left');
				$this->db->join('tbl_penjamin', 'tbl_penjamin.cust_id = tbl_harpas.cust_id', 'left');
				$header = $this->db->get_where('tbl_harpas', array('arno' => $nomor))->result();
				$this->db->select('tbl_darpas.*, tbl_pap.namapas, tbl_accounting.acname');
				$this->db->join('tbl_pap', 'tbl_pap.noreg = tbl_darpas.noreg', 'left');
				$this->db->join('tbl_accounting', 'tbl_accounting.accountno = tbl_darpas.daccountno', 'left');
				$detail  = $this->db->get_where('tbl_darpas', array('arno' => $nomor))->result();
				
				// print_r($header);
				
				$chari = "";
				if($header && $detail){
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
							<td align='center'>Penerimaan Piutang</td>
						</tr>
					</table>
					<br>
					";

					
					foreach($header as $data);
					
					$tanggal = date('d-m-Y', strtotime($data->ardate));
					$totalterima = number_format($data->totalterima,0,'.',',');

					$chari .= "
					<table style='border-collapse:collapse;font-family: tahoma; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='3'>
						<tr rowspan='6' class='show1'>
							<td align='left'  width='5%'>Vendor</td>
							<td align='center'  width='5%'>:</td>
							<td align='left'  width='40%'>$data->cust_nama</td>
						
							<td align='left'  width='15%'>No. Urut</td>
							<td align='center'  width='5%'>:</td>
							<td align='left'  width='30%'>$data->arno</td>
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
							<td align='left'  width='30%'>$data->keterangan</td>
						</tr>		

					</table>";

					
					$chari .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">

					<thead>
						<tr>
							<td style=\"border:0\" align=\"center\"><br></td>                
						</tr>
						<tr>
							<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>No.</b> </td>
							<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>Invoice No.</b> </td>
							<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>Tanggal Invoice</b> </td>
							<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>Jumlah Tagihan</b> </td>
							<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>Total Bayar</b> </td>
							<td widft=\"%\" bgcolor=\"#cccccc\" align=\"center\"><b>Penyesuaian</b> </td>
						</tr>
						
					</thead>";
					
					$total = 0;
					$totalDiskon = 0;
					if($detail){
						$i = 1;
						
						foreach($detail as $dtl){

							$tglinvoice = date('d-m-Y', strtotime($dtl->tgltagihan));
							$ttlHutang = number_format($dtl->jumlahtagihan,0,'.',',');
							$ttlBayar = number_format($dtl->jumlahbayar,0,'.',',');
							$ttlDiskon = number_format($dtl->diskonrp,0,'.',',');

							$chari .= "
								<tr class='show1'>
									<td align='center'>$i.</td>
									<td >$dtl->arno</td>
									<td align='right'>$tglinvoice</td>
									<td align='right'>$ttlHutang</td>
									<td align='right'>$ttlBayar</td>
									<td align='right'>$ttlDiskon</td>
								</tr>";
							
								$total += ($dtl->jumlahbayar);
								$totalDiskon += ($dtl->diskonrp);
							$i++;
						}
					}

					$chari .= "
								<tr class='show1'>
									<td align='right' colspan='4'>Total</td>
									<td align='right'>$total</td>
									<td align='right'>$total</td>
								</tr>";
					
					$chari .= "</table>";

					// echo $chari;
					$this->M_cetak->mpdf('P','A4',$judul, $chari,'Penerimaan_piutang.pdf', 10, 10, 10, 0);
				} else {					
					header('location:'.base_url());
				}
			} else {
				header('location:'.base_url());
			}
		} else {
			header('location:'.base_url());
		}
	}
	
	public function cetak2($param)
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');		
		if(!empty($cek))
		{		
            $unit= $this->session->userdata('unit');	 
			/*	
            $profile = $this->M_global->_LoadProfileLap();	
		    
		    $nama_usaha=$profile->nama_usaha;
			$alamat1  = $profile->alamat1;
			$alamat2  = $profile->alamat2;
		    */
			
			$nama_usaha='';
			$alamat1  = '';
			$alamat2  = '';
			
		    $queryh = "select * from tbl_harpas inner join tbl_penjamin on 
			tbl_harpas.cust_id=tbl_penjamin.cust_id where arno = '$param'";
			 
		    $header = $this->db->query($queryh)->row();			
		    $pdf=new simkeu_nota();
			$pdf->setID($nama_usaha,$alamat1,$alamat2);
			$pdf->setjudul('');
			$pdf->setsubjudul('');
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(190));			
			$size   = array('','','');
			$pdf->setfont('Arial','B',18);
			$border = array('');
			$align = array('C');
			$style = array('');
			$size  = array('18');
			$pdf->SetFillColor(230,230,230);			
			$judul=array('Tanda Terima Pembayaran');
			$fc     = array('1');
			$hc     = array('20','20','20');
			$pdf->FancyRow2(10,$judul, $fc,  $border, $align, $style, $size);
			$pdf->ln(5);
			$pdf->setfont('Arial','',10);
			$pdf->SetWidths(array(15,30,5,100));
			$border = array('','','','');
			$fc     = array('0','0','0','0');						
			$align  = array('L','L',':','L');
			$pdf->FancyRow(array('','Nomor',':',$header->arno), $fc, $border, $align);
			$pdf->FancyRow(array('','Tanggal',':',date('d M Y',strtotime($header->ardate))), $fc, $border);
			
			$pdf->ln(2);
			$pdf->SetWidths(array(10,100));	
			$align = array('C','L');
			$pdf->FancyRow(array('','Telah menerima pembayaran / Cek / Giro sebagai berikut :'), $fc, $border, $align);
			$pdf->ln(2);
			$pdf->SetWidths(array(15,30,5,100));
			$border = array('','','','');
			$fc     = array('0','0','0','0');						
			$align  = array('L','L','C','L');
			$style  = array('','','','');
			$size  = array('','','','');
			$pdf->FancyRow(array('','Diterima Dari',':',$header->cust_nama), $fc, $border, $align,  $style, $size);
			$style = array('','','B','B');
			$pdf->FancyRow(array('','Jumlah',':','Rp '.number_format($header->totalterima,0,'.',',')), $fc, $border, $align, $style, $size);
			$style = array('','','','');
			$pdf->FancyRow(array('','Terbilang',':',ucwords($this->M_global->terbilang($header->totalterima))), $fc, $border, $align, $style, $size);
			
			
			$pdf->settextcolor(0);
			$pdf->SetWidths(array(80, 50,2,50));
			$pdf->SetFont('Arial','',10);
			$pdf->SetAligns(array('C','C','C','C'));
			$pdf->ln(10);
			
			$border = array('','','','');
			$align  = array('C','C','C','C');
			$pdf->FancyRow(array('','Mengetahui','','Penerima'),0,$border, $align);
			$pdf->ln(1);
			$pdf->ln(15);
		
			$pdf->SetFont('Arial','',8);		
            $fc     = array('0','0','0','0');				
			$align  = array('L','C','L','L');
			$border = array('','B','','B');	
			$pdf->FancyRow(array('* Pembayaran akan dianggap sah','','',''),$fc,$border,$align);
			$border = array('','','','');	
			$align  = array('L','L','L','L');
			$pdf->FancyRow(array('setelah cek/giro dicairkan','Tgl.','','Tgl.'),$fc,$border,$align);
	
		

			$pdf->AliasNbPages();
			$pdf->output($param.'.PDF','I');			
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
	public function getfaktur($cust)
	{   	    	   
		$data = $this->db->query("select tbl_pap.*, jumlahpiutang as total, (jumlahpiutang-jumlahbayar) as hutang from tbl_pap where cust_id='$cust' and jumlahpiutang>jumlahbayar")->result();
		$vdata = array();
		foreach($data as $row){
			//$kodeso = $row->kodeso;
			
            $uangmuka = 0;			
			
			$vdata[] = array(
			 'kodesi' => $row->invoiceno,
			 'tglsi' => date('d-m-Y',strtotime($row->tglposting)),
			 'totalsi' => $row->jumlahpiutang,
			 'uangmuka' => $uangmuka,
			 'hutang' => $row->hutang,
			 'total' => $row->total,
			);
		}
		echo json_encode($vdata);
		
	}
	
	public function getfaktur_entry($nomor)
	{   	    
        $data = $this->db->select('tbl_darpas.*')->get_where('tbl_darpas',array('arno' => $nomor))->result();		
		echo json_encode($data);
	}
	
	public function getbiaya($po)
	{   	    
        $data = $this->db->select('ap_pobiaya.*, ms_akun.namaakun')->join('ms_akun','ms_akun.kodeakun=ap_pobiaya.kodeakun','left')->get_where('ap_pobiaya',array('kodepo' => $po))->result();
		echo json_encode($data);
	}
	
	public function getlistfaktur( $supp )
	{
		if(!empty($supp))
		{
		    $po  = $this->db->get_where('ar_sifile',array('kodecust' => $supp))->result();	
			?>	
            <div class="input-group">
			<select name="kodesi" id="kodesi" class="form-control  input-medium select2me"  >            											
			  <option value="">-- Tanpa Faktur --</option>
			  <?php 			    
				foreach($po  as $row){	
				?>
				<option value="<?php echo $row->kodesi;?>"><?php echo $row->kodesi;?></option>
				
			  <?php } ?>
			</select>
			
			<span class="input-group-btn">
				<a class="btn blue" onclick="getfaktur();"><i class="fa fa-download"></i></a>
			</span>	
			</div>
			<?php											  
			
		} else
        {
		  echo "";	
		}			
	}
	
	
	public function entri()
	{
		$cek = $this->session->userdata('level');		
		$uid = $this->session->userdata('unit');		
		if(!empty($cek))
		{				  
		  $page=$this->uri->segment(3);
		  $limit=$this->config->item('limit_data');	
          $d['arno'] = 'Auto'; // urut_transaksi('AR_PASIEN',19);
		  
		  $d['jenis_pembayaran'] = $this->M_keuangan_keluar->getJenisPembayaran();
		  $this->load->view('penjualan/v_penjualan_penerimaan_add',$d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
	public function getTagihan(){
		$level = $this->session->userdata('level');	
        if(!empty($level))
		{
			$unit = $this->session->userdata('unit');	
			$page=$this->uri->segment(3);
			$limit=$this->config->item('limit_data');	 
			
			$vendor 	= $this->input->get('vendor');
			// $startdate 	= $this->input->get('startdate');
			// $enddate 	= $this->input->get('enddate');
			$dt = $this->M_penjualan_penerimaan->getTagihan($unit, $vendor, '', '');

			echo json_encode($dt);
		} else
		{
			header('location:'.base_url());
		}
	}

	
	public function pilih_daftar_tagihan(){
		$level = $this->session->userdata('level');	
		if(!empty($level))
		{
			$unit = $this->session->userdata('unit');
			$dt = $this->input->post('pilih');
			$vendor = $this->input->post('vendor_id'); 

			// print_r($dt);
			// echo $vendor;

			$tghn = '';
			if(count($dt) > 0){
				$tghn = $this->M_penjualan_penerimaan->getTagihanById($unit, array_keys($dt), $vendor);
					          
				// $tghn = $this->M_hutang->getTagihan($unit);
			}
			echo json_encode($tghn);
		} else
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
          $d['nomor']= urut_transaksi('AR_PASIEN',19);
		  $this->load->view('penjualan/v_penjualan_penerimaan_add',$d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	public function hapus($nomor)
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{				  
	       //update faktur				
			  $bayar = $this->db->get_where('tbl_darpas', array('arno' => $nomor))->result();				  
			  foreach($bayar as $row){
				  $_bayar = $row->jumlahbayar;
				  $faktur = $row->invoiceno;
				  $this->db->set('jumlahbayar', 'jumlahbayar - '.$_bayar,FALSE); 
				  $this->db->where('invoiceno', $faktur); 
				  $this->db->update('tbl_pap'); 				  				
			  }
				  
		   $this->db->delete('tbl_darpas',array('arno' => $nomor));
		   $this->db->delete('tbl_harpas',array('arno' => $nomor));	
		   //$this->M_global->_hapusjurnal($nomor, 'JT');	
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
	public function getakun($kode)
	{
		if(!empty($kode))
		{
		  
			$q = $kode;
			$query = "select * from ms_akun where (kodeakun like '%$q%' or namaakun like '%$q%') and kodeakun like '6%' and akuninduk<>''";
			$data  = $this->db->query($query);
			?>
			
			<table id="myTable">
			  <tr class="header">
				<th style="width:20%;">Kode</th>
				<th style="width:80%;">Nama</th>	
			  </tr> 
			  
			<?php							
			foreach($data->result_array() as $row)
			{ ?>
			   <tr>
				 <td width="50" align="center">
					<a href="#" onclick="post_akun('<?php echo $row['kodeakun'];?>','<?php echo $row['namaakun'];?>')">
					
					<?php echo $row['kodeakun'];?></a>
				 </td>	     
				 <td><?php echo $row['namaakun'];?></td>
			   </tr>
			   
			   <?php
			}
			echo "</table>";
		} else
        {
		  echo "";	
		}			
	}
	
	public function getbarang($kode)
	{
		if(!empty($kode))
		{
		  
			$q = $kode;
			$query = "select * from inv_barang where kodeitem like '%$q%' or namabarang like '%$q%' order by namabarang";
			$data  = $this->db->query($query);
			?>
			
			<table id="myTable">
			  <tr class="header">
				<th style="width:20%;">Kode</th>
				<th style="width:60%;">Nama</th>	
				<th style="width:10%;">Stok</th>	
				<th style="width:10%;">Satuan</th>	
			  </tr> 
			  
			<?php							
			foreach($data->result_array() as $row)
			{ ?>
			   <tr>
				 <td width="50" align="center">
					<a href="#" onclick="post_value('<?php echo $row['kodeitem'];?>','<?php echo $row['namabarang'];?>','<?php echo $row['satuan'];?>','<?php echo $row['hargabeliakhir'];?>')">
					
					<?php echo $row['kodeitem'];?></a>
				 </td>	     
				 <td><?php echo $row['namabarang'];?></td>
				 <td><?php echo $row['qty'];?></td>
				 <td><?php echo $row['satuan'];?></td>
			   </tr>
			   
			   <?php
			}
			echo "</table>";
		} else
        {
		  echo "";	
		}			
	}
	
	public function getharga($kode)
	{
		if(!empty($kode))
		{
		    $data  = explode("~",$kode);
		    $supp  = $data[0];		
		    $item  = $data[1];
			
			$query = "select * from ar_sidetail inner join ar_sifile on ar_sifile.kodesi=ar_sidetail.kodesi where ar_sifile.kodecust = '$supp' and ar_sidetail.kodeitem = '$item' order by ar_sifile.tglsi desc";
	   	    $data  = $this->db->query($query)->result();
			?>
			
			<table id="myTable">
			  <tr class="header">
				<th style="width:20%;">Tanggal</th>
				<th style="width:60%;">Harga</th>	
				<th style="width:10%;">Disc</th>	
				<th style="width:10%;">Satuan</th>	
			  </tr> 
			  
			<?php							
			foreach($data  as $row)
			{ ?>
			   <tr>
				 <td width="50" align="center">
					<a href="#" onclick="post_harga('<?php echo $row->hargajual;?>','<?php echo $row->satuan;?>')">
					
					<?php echo date('d-m-Y',strtotime($row->tglsi));?></a>
				 </td>	     
				 <td><?php echo $row->hargajual;?></td>
				 <td><?php echo $row->disc;?></td>
				 <td><?php echo $row->satuan;?></td>
			   </tr>
			   
			   <?php
			}
			echo "</table>";
		} else
        {
		  echo "";	
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
		$data = $this->db->get_where('ar_sofile',array('kodeso' => $kodepo))->row();
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
	
	
	public function save($param)
	{		
		$username = $this->session->userdata('username');
		$cabang   = $this->session->userdata('unit');
		$cek 	  = $this->session->userdata('level');
		
		if(!empty($cek)){
			
			if($param == 1){
				$arno = urut_transaksi('AR_PASIEN',19);
			} else {
				$arno = $this->input->post('arno');
			}

			$cust_id  	= $this->input->post('cust_id');
			$arno 		= $arno;
		    $accountno  = $this->input->post('accountno');
			$ardate 	= $this->input->post('ardate');
			$jenisbayar = $this->input->post('jenisbayar');
			$cek_no  	= $this->input->post('cek_no');
			$keterangan = $this->input->post('keterangan');
			$totalterima= $this->input->post('totalterima');

			$invoiceno  	= $this->input->post('invoiceno');
			$tglposting  	= $this->input->post('tglposting');
			$fakturno  		= $this->input->post('fakturno');
			$namapas  		= $this->input->post('namapas');
			$noreg  		= $this->input->post('noreg');
			$rekmed  		= $this->input->post('rekmed');
			$jumlahhutang  	= $this->input->post('jumlahhutang');
			$jumlahbayar  	= $this->input->post('jumlahbayar');
			$diskontotal  	= $this->input->post('diskontotal');
			$akundiskon  	= $this->input->post('akundiskon');
			$lunas  		= $this->input->post('lunas');

			$data = array(
				'koders' => $cabang,
				'arno' => $arno,
				'ardate' => $ardate,
				'cust_id' => $cust_id,
				'totalterima' => $totalterima,
				'accountno' => $accountno,
				'jenisbayar' => $jenisbayar,
				'cek_no' => $cek_no,
				'keterangan' => $keterangan,
				'username' => $username
			);

			// print_r($data);

			if($param == 1){
				$this->db->insert('tbl_harpas',$data);
				if($this->db->affected_rows() > 0){
						
					for($i=0; $i < count($invoiceno); $i++){
						$data2 = array(
							'koders' => $cabang,
							'arno' => $arno,
							'tgltagihan' => $tglposting[$i],
							'noreg' => $noreg[$i],
							'rekmed' => $rekmed[$i],
							'invoiceno' => $invoiceno[$i],
							'fakturno' => $fakturno[$i],
							'jumlahtagihan' => $jumlahhutang[$i],
							'jumlahbayar' => $jumlahbayar[$i],
							'diskonrp' => $diskontotal[$i],
							'daccountno' => $akundiskon[$i],
							'lunas' => $lunas[$i]
						);
						
						$this->db->insert('tbl_darpas',$data2);
					}

					if($this->db->affected_rows() > 0){
						echo json_encode(array("status" => TRUE, "data" => $arno));
					} else {
						echo json_encode(array("status" => FALSE, "data" => 'Failed 2'));
					}
				} else {
					echo json_encode(array("status" => FALSE, "data" => 'Failed 1'));
				}
			} else {
				$where = array(
							'arno' => $arno,
						);
				
				$this->db->update('tbl_harpas',$data, $where);

				if($this->db->affected_rows() >= 0){
					$this->db->delete('tbl_darpas', $where);
					if($this->db->affected_rows() > 0){		
						for($i=0; $i < count($invoiceno); $i++){
							$data2 = array(
								'koders' => $cabang,
								'arno' => $arno,
								'tgltagihan' => $tglposting[$i],
								'noreg' => $noreg[$i],
								'rekmed' => $rekmed[$i],
								'invoiceno' => $invoiceno[$i],
								'fakturno' => $fakturno[$i],
								'jumlahtagihan' => $jumlahhutang[$i],
								'jumlahbayar' => $jumlahbayar[$i],
								'diskonrp' => $diskontotal[$i],
								'daccountno' => $akundiskon[$i],
								'lunas' => $lunas[$i]
							);
							
							$this->db->insert('tbl_darpas',$data2);
						}
							
						if($this->db->affected_rows() > 0){
							echo json_encode(array("status" => TRUE, "data" => $arno));
						} else {
							echo json_encode(array("status" => FALSE, "data" => 'Failed I#3'));
						}
					} else {
						echo json_encode(array("status" => FALSE, "data" => 'Failed D#2'));
					}
				} else {
					echo json_encode(array("status" => FALSE, "data" => 'Failed U#1'));
				}
			}

		} else {
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
			$cabang   = $this->session->userdata('unit');
			
			//$jumdata = $this->db->get_where('ap_pufile', array('kodepu' => $nobukti))->num_rows();
			//if($jumdata<1){			
			
			if($param==2){
				 
				  //update faktur				
                  $bayar = $this->db->get_where('tbl_darpas', array('arno' => $nobukti))->result();				  
				  foreach($bayar as $row){
					  $_bayar = $row->jumlahbayar;
					  
					  $faktur = $row->invoiceno;
					  $this->db->set('jumlahbayar', 'jumlahbayar - '.$_bayar,FALSE); 
					  $this->db->where('invoiceno', $faktur); 
					  $this->db->update('tbl_pap'); 					
				  }
				  
			}
			
			$this->db->delete('tbl_darpas',array('arno' => $nobukti));
			$faktur  = $this->input->post('faktur');
			$tglfaktur   = $this->input->post('tglfaktur');
		    $totfaktur   = $this->input->post('totfaktur');
			$hutang = $this->input->post('hutang');
			$bayar = $this->input->post('bayar');
			$disc  = $this->input->post('disc');
			$uangmuka  = $this->input->post('uangmuka');
		   
			$jumdata  = count($faktur);
						
			$nourut = 1;
			
			/*
			$profile = $this->M_global->_LoadProfileLap();				
			$akun_debet     = $profile->akun_piutang;
			$akun_kredit    = $this->input->post('kasbank');
						
			if($param==2){
			   $this->M_global->_hapusjurnal($nobukti, 'JT');	
			}
			*/
			
			for($i=0;$i<=$jumdata-1;$i++)
			{
			    $_faktur   = $faktur[$i];
				$_totfaktur =str_replace(',','',$totfaktur[$i]);
				$_hutang    =str_replace(',','',$hutang[$i]);
				$_bayar     =str_replace(',','',$bayar[$i]);
				$_disc      =str_replace(',','',$disc[$i]);
				$_uangmuka  =str_replace(',','',$uangmuka[$i]);
				
				$_total = $_bayar - $_disc + $_uangmuka;
				
			    $datad = array(
				'koders' => $cabang,
				'arno'   => $nobukti,
				'noreg'  => '',
				'rekmed' => '',
				'invoiceno' => $_faktur,
				'fakturno' => '',
				'jumlahtagihan' => $_hutang,
				'jumlahbayar' => $_bayar,
                'lunas' => 0, 				
			    );
				if($_total!="0"){
			      $this->db->insert('tbl_darpas', $datad);	
				  
				  //update faktur				  				  				 
				  $this->db->set('jumlahbayar', 'jumlahbayar + '.$_bayar,FALSE);				  
				  $this->db->set('tanggalbayar', date('Y-m-d',strtotime($this->input->post('tanggal'))));
				  $this->db->where('invoiceno', $_faktur); 
				  $this->db->update('tbl_pap'); 				  				  
				  
				  /*
				  $this->M_global->_rekamjurnal(
					date('Y-m-d',strtotime($this->input->post('tanggal'))),
					$this->input->post('nomorbukti'),
					'JT',
					$_faktur,
					1,
					$akun_debet,
					'Pembayaran Piutang',
					'Pembayaran Piutang Faktur '.$_faktur,
					$_total,
					0
					);
					
					$this->M_global->_rekamjurnal(
					date('Y-m-d',strtotime($this->input->post('tanggal'))),
					$this->input->post('nomorbukti'),
					'JT',
					$_faktur,
					2,
					$akun_kredit,
					'Pembayaran Piutang',
					'Pembayaran Piutang Faktur '.$_faktur,
					0,
					$_total					
					);	
                  */
				  
				}
			}
			
			$_jumlahbayar =str_replace(',','',$this->input->post('jumlahbayar'));
			$data = array(
			    'koders'  => $this->session->userdata('unit'),
				'cust_id' => $this->input->post('cust'),
				'arno'    => $this->input->post('nomorbukti'),
				'ardate'  => date('Y-m-d',strtotime($this->input->post('tanggal'))),				
				'accountno'  => $this->input->post('kasbank'),
				//'metodebayar'  => $this->input->post('metodebayar'),				
				'vat' => 0,
				'cek_no'  => $this->input->post('nomorcek'),				
				'totalterima'  => $_jumlahbayar,				
				'username'=> $userid,
				'keterangan'     => $this->input->post('keterangan'),				
				'posting'=> 0,			                
				'proses'=> 0,			                
			);
						
			
			
			if($param==1)
			{
			  $this->db->insert('tbl_harpas',$data);				  
			} else {
			  $this->db->update('tbl_harpas',$data, array('arno' => $nobukti));				 
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
			
		  	$d['jenis_pembayaran'] = $this->M_keuangan_keluar->getJenisPembayaran();
			$this->db->select('tbl_harpas.*, tbl_accounting.acname');
			$this->db->join('tbl_accounting', 'tbl_accounting.accountno = tbl_harpas.accountno', 'left');
			$header = $this->db->get_where('tbl_harpas', array('arno' => $nomor));
			$this->db->select('tbl_darpas.*, tbl_pap.namapas, tbl_accounting.acname');
			$this->db->join('tbl_pap', 'tbl_pap.noreg = tbl_darpas.noreg', 'left');
			$this->db->join('tbl_accounting', 'tbl_accounting.accountno = tbl_darpas.daccountno', 'left');
			$detil  = $this->db->get_where('tbl_darpas', array('arno' => $nomor));
			
			$d['arno']    = $nomor;
			$d['header']  = $header->result();
			$d['detil']   = $detil->result();

			
			$d['jumdata1']= $detil->num_rows();				
			$this->load->view('penjualan/v_penjualan_penerimaan_edit',$d);
		} else
		{
			header('location:'.base_url());
		}	
	}

}
