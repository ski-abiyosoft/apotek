<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Akuntansi_jurnal extends CI_Controller {

	
	
	public function __construct()
	{
		
		parent::__construct();
		$this->load->library('form_validation'); 
        $this->load->database(); 
		$this->load->helper('simkeu_nota');
		$this->load->model('M_akuntansi_jurnal','M_akuntansi_jurnal');
		$this->load->model('M_akuntansi_jurnall','M_akuntansi_jurnall');
		$this->session->set_userdata('menuapp', '5000');
		$this->session->set_userdata('submenuapp', '5502');		
	}
	
	
	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{	
			$unit = $this->session->userdata('unit');	
			if(!empty($unit)){
			  $qp ="select koders, namars from tbl_namers where koders = '$unit'"; 
			} else {
			  $qp ="select koders, namars from tbl_namers order by koders"; 		
			}
						
			$this->load->helper('url');		
			$d['unit']  = $this->db->query($qp);		
			$d['nojurnal'] = 'Otomatis';
			$this->load->view('akuntansi/v_akuntansi_jurnal',$d);
			} else
		{
			header('location:'.base_url());
		}	
	}
	
	public function edit($nojurnal)
	{
		$userid =  $this->session->userdata('username');				
		if(!empty($userid))
		{	
			$unit = $this->session->userdata('unit');	
			if(!empty($unit)){
			  $qp ="select koders, namars from tbl_namers where koders = '$unit'"; 
			} else {
			  $qp ="select koders, namars from tbl_namers order by koders"; 		
			}
			
			$datajurnal = $this->db->query("select * from tbl_jurnalh where id = '$nojurnal'")->row();
			if($datajurnal){
			  $nobukti = $datajurnal->nobukti;	
			} else {
			  $nobukti = '';	
			}
			$qjurnalh ="select * from tbl_jurnalh where id = '$nojurnal'"; 					
			$qjurnald ="select tbl_jurnald.*, tbl_accounting.acname, tbl_accostcentre.namadep 
			from tbl_jurnald inner join tbl_accounting on tbl_jurnald.accountno=tbl_accounting.accountno 
			left outer join tbl_accostcentre on tbl_jurnald.depid=tbl_accostcentre.depid	
			where tbl_jurnald.nobukti = '$nobukti' order by tbl_jurnald.id"; 					
			$d['unit']  = $this->db->query($qp);		
			$d['jurnald'] = $this->db->query($qjurnald)->result();
			$d['jurnalh'] = $this->db->query($qjurnalh)->row();
			$d['nojurnal'] = $nojurnal;
			$d['nobukti'] = $nobukti;
			$d['jumdata'] = $this->db->query($qjurnald)->num_rows();	

            
			$this->load->view('akuntansi/v_akuntansi_jurnal_edit',$d);
			} else
		{
			header('location:'.base_url());
		}	
	}
	
	
	public function cetak()
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
		$userid =  $this->session->userdata('username');				
		if(!empty($userid))
		{				  
            $id = $this->input->get('id');
			$nobukti = $this->input->get('nobukti');
			
            $profile = $this->M_global->_LoadProfileLap();	
		    $unit= $this->session->userdata('unit');	 
		    $nama_usaha=$profile->nama_usaha;
			$alamat1  = $profile->alamat1;
			$alamat2  = $profile->alamat2;
		  
		    $qjurnalh ="select * from tbl_jurnalh where id = '$id'"; 					
			$qjurnald ="select tbl_jurnald.*, tbl_accounting.acname, tbl_accostcentre.namadep 
			from tbl_jurnald inner join tbl_accounting on tbl_jurnald.accountno=tbl_accounting.accountno 
			left outer join tbl_accostcentre on tbl_jurnald.depid=tbl_accostcentre.depid	
			where tbl_jurnald.nobukti = '$nobukti' order by tbl_jurnald.id"; 					
			
		    $header = $this->db->query($qjurnalh)->row();
			$detil  = $this->db->query($qjurnald)->result();
		    $pdf=new simkeu_nota();
			$pdf->setID($nama_usaha,$alamat1,$alamat2);
			$pdf->setjudul('');
			$pdf->setsubjudul('');
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(70,30,90));
			$border = array('','','BT');
			$size   = array('','','');
			$pdf->setfont('Arial','B',18);
			$pdf->SetAligns(array('C','C','C'));
			$align = array('L','C','L');
			$style = array('','','B');
			$size  = array('12','','18');
			$max   = array(5,5,20);
			$judul=array('','','Jurnal Umum');
			$fc     = array('0','0','0');
			$hc     = array('20','20','20');
			$pdf->FancyRow2(10,$judul, $fc,  $border, $align, $style, $size, $max);
			$pdf->ln(1);
			$pdf->setfont('Arial','B',10);
			$pdf->SetWidths(array(70,30,30,5,55));
			$border = array('','','','','');
			$fc     = array('0','0','0','0','0');
			$pdf->SetFillColor(230,230,230);
			//$pdf->settextcolor(10,20,200);
			$pdf->setfont('Arial','',9);
		
			$pdf->FancyRow(array('','','Nomor',':',$header->nobukti), $fc, $border);
			$pdf->FancyRow(array('','','Tanggal',':',date('d-m-Y',strtotime($header->tgljurnal))), $fc, $border);
			//$pdf->FancyRow(array('','','Tipe Transaksi',':',$header->jurnal_nama), $fc, $border);
			//$pdf->FancyRow(array('','','Nomor Ref.',':',$header->noref), $fc, $border);
			$fc     = array('1','0','0','0','0');			
			$pdf->ln(3);
			
			$pdf->SetWidths(array(30,80,30,30,20));
			$border = array('TB','TB','TB','TB','TB');
			$align  = array('L','L','R','R','L');
			$pdf->setfont('Arial','B',10);
			//$pdf->SetFillColor(0,0,139);
			//$pdf->settextcolor(255,255,255);
			$fc = array('0','0','0','0','0');
			$judul=array('Kode Akun','Uraian','Debet','Kredit','Dept');
			$pdf->FancyRow2(8,$judul,$fc, $border,$align);
			$pdf->setfont('Arial','',10);
			$tot = 0;
			$subtot = 0;
			$tdisc  = 0;
			$border = array('','','','','');
			$align  = array('L','L','R','R','L');
			$fc = array('0','0','0','0','0');
			$pdf->SetFillColor(0,0,139);
			$pdf->settextcolor(0);
			$totd = 0;
			$totk = 0;
			foreach($detil as $db)
			{
			  $totd = $totd + $db->debet;
			  $totk = $totk + $db->credit;
			  $pdf->FancyRow(array(
			  $db->accountno, 
			  $db->dketerangan,
			  number_format($db->debet,2,'.',','),
			  number_format($db->credit,2,'.',','),
			  $db->namadep),$fc, $border, $align);			  
			}
			
					
			$pdf->SetFillColor(230);
			$border = array('B','B','B','B');
			$pdf->FancyRow(array('', '', '',''),0,$border);
			$pdf->ln(2);
			$pdf->SetWidths(array(90,20,30,30));
			$border = array('','','TB','TB');
			$align  = array('L','C','R','R');
			$pdf->SetFillColor(230,230,230);
			$pdf->settextcolor(0);
			$fc = array('0','0','0','0');
			$pdf->setfont('Arial','B',10);
			$pdf->FancyRow(array('', 'Total', number_format($totd,2,'.',','), number_format($totk,2,'.',',')),$fc, $border, $align,0);
			$border = array('','','','');
			$pdf->FancyRow(array('','', '', ''),$fc, $border, $align);
			$fc = array('0','0','0','0');
			$pdf->FancyRow(array('','', '', ''),$fc, $border, $align);
			$pdf->FancyRow(array('','', '', ''),$fc, $border, $align);
			
			$style = array('','','','');
			$size  = array('','','','');
			$border = array('','','','');
			$pdf->SetFillColor(0,0,139);
			$pdf->settextcolor(255,255,255);
			$fc = array('0','0','0','0');
			$pdf->FancyRow(array('','', '', ''),$fc, $border, $align, $style, $size);
			$pdf->settextcolor(0);
			$pdf->setTitle($nobukti);
			$pdf->AliasNbPages();
			$pdf->output($nobukti.'.PDF','I');			
		}
		else
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
          $d['master_bank'] = $this->db->get("ms_bank");
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  $d['nojurnal']=$param;
		  $d['unit'] = $this->session->userdata('unit');
		  
		  $profile = $this->M_global->_LoadProfileLap();
		  $nama_usaha=$profile->nama_usaha;
          $namaunit = $this->M_global->_namaunit($unit);  
		  $qjurnalh = $this->db->get_where('tr_jurnal', 'novoucher = "'.$param.'"')->row();
		  $tanggal  = $qjurnalh->tanggal;
		  $rjurnal  = 
		  
		  $this->db->select('tr_jurnal.*, ms_akun.namaakun');
		  $this->db->join('ms_akun','tr_jurnal.kodeakun=ms_akun.kodeakun','left');
		  $this->db->order_by('tr_jurnal.nourut');
		  $rjurnal = $this->db->get_where('tr_jurnal', array('novoucher' => $param))->result();
		  	  
		    $pdf=new simkeu_vou();
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			
			$pdf->SetFont('Times','I',10);
            $pdf->cell(0,3,$nama_usaha);   
		    $pdf->ln(2);
		    $pdf->SetFont('Times','BI',9);
			$pdf->SetTextColor(128);  
		    if($namaunit!='')
		    {
			 $pdf->cell(0,5,strtoupper($namaunit),0,1,'L');
		    }
		    $pdf->ln(5);
		    $pdf->SetTextColor(0);
		    $pdf->SetFont('Times','B',12);
		   
		 		 
		    $pdf->cell(0,5,'MEMO JURNAL',0,1,'C');
		   
		   
		   $pdf->ln();
		   $pdf->SetFont('Times','B',10);
		   $pdf->SetWidths(array(95,95));
		   $pdf->SetAligns(array('L','R'));
		   $pdf->Row(array('NOMOR BUKTI : '.$param,'TANGGAL : '.date('d-m-Y',strtotime($tanggal))),5);
		   $pdf->ln(1);

		   $pdf->SetWidths(array(10,25,50,55,25,25));
		   $pdf->SetAligns(array('C','C','C','C','C','C'));

		  
		   $judul=array('NO','KODE AKUN','NAMA AKUN','URAIAN','DEBET','KREDIT');
		  
		   $pdf->RowL($judul,7);
		   $pdf->SetWidths(array(10,25,50,55,25,25));
		   $pdf->SetAligns(array('C','C','L','L','R','R'));
   
			
				
			$nourut = 1;
            $tot1=0;
			$tot2=0;
			$pdf->setfont('Times','',10);
			foreach($rjurnal as $rowd)
			{
			  $tot1=$tot1+$rowd->debet;
			  $tot2=$tot2+$rowd->kredit;

			  $pdf->RowL(array(
			  $nourut,
			  $rowd->kodeakun,
			  $rowd->namaakun,
			  $rowd->keterangan,
			  number_format($rowd->debet,2,'.',','),
			  number_format($rowd->kredit,2,'.',',')),5);
			  
			  $nourut++;
			}
              $pdf->SetWidths(array(140,25,25));
			  $pdf->SetAligns(array('R','R','R'));
			 
			  $pdf->setfont('Times','B',10);
			  $pdf->RowL(array(
			  'JUMLAH  ',
			  number_format($tot1,2,'.',','),
			  number_format($tot2,2,'.',',')),7);
			  $pdf->ln();
			  
			  
			  $pdf->setfont('Times','',10);
			  $pdf->SetWidths(array(50,90,50 ));
			  $pdf->SetAligns(array('C','C','C'));
			  $pdf->Row(array('Menyetujui','','Bagian Akuntansi'),5);
			  $pdf->ln(20);
			  $pdf->setfont('Times','BU',8);
			  $border = array('B','','B');
			  $pdf->FancyRow(array('','',''), $border); 
			  

			$pdf->AliasNbPages();
			$pdf->output('memo_jurnal.PDF','I');
		  				  
		  
		  				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	public function jurnal_add()
	{
		$userid =  $this->session->userdata('username');				
		if(!empty($userid))
		{			
            $ketglobal= $this->input->post('keterangan');
			$kodeakun = $this->input->post('akun');
			$ket      = $this->input->post('ket');
		    $debet    = $this->input->post('debet');
		    $kredit   = $this->input->post('kredit');
            $tanggal  = $this->input->post('tanggal');
			$cabang   = $this->input->post('cabang');
			$dept     = $this->input->post('dept');
			$jumdata  = count($kodeakun);
			$_tanggal = date('Y-m-d',strtotime($tanggal));
			$bulan    = date('m',strtotime($tanggal));
			$tahun    = date('Y',strtotime($tanggal));
			
			$nourut = 1;
			$nobukti = urut_transaksi('JURNAL_TRANSAKSI',19);
			_rekamjurnal_header($cabang, $nobukti, $_tanggal, $ketglobal, $userid, 0,0);
			for($i=0;$i<=$jumdata-1;$i++)
			{
			    $_akun   = $kodeakun[$i];
				$_ket    = $ket[$i];
				$_dept   = $dept[$i];
				$_debet  =  str_replace(',','',$debet[$i]);
				$_kredit =  str_replace(',','',$kredit[$i]);
				
				if($_akun!=""){
					_rekamjurnal_detil($cabang, $nobukti, $_akun, $_debet, 
					$_kredit, $_ket, $_dept, '');
					
					$nourut++;
				}
			    
			}
			echo $nobukti;
		}
		else
		{
			header('location:'.base_url());
		}
	}
	
	public function jurnal_edit($nojurnal)
	{
		$userid =  $this->session->userdata('username');				
		if(!empty($userid))
		{		
            $nobukti  = $this->input->post('nomorbukti');	
			$cabang   = $this->input->post('cabang');
			
	        $this->db->delete('tbl_jurnalh', array('id' => $nojurnal, 'nobukti' => $nobukti));
			$this->db->delete('tbl_jurnald', array('nobukti' => $nobukti,'koders' => $cabang));
			
			
			$ketglobal= $this->input->post('keterangan');
			$kodeakun = $this->input->post('akun');
			$ket      = $this->input->post('ket');
		    $debet    = $this->input->post('debet');
		    $kredit   = $this->input->post('kredit');
            $tanggal  = $this->input->post('tanggal');
			
			$dept     = $this->input->post('dept');
			$jumdata  = count($kodeakun);
			$_tanggal = date('Y-m-d',strtotime($tanggal));
			$bulan    = date('m',strtotime($tanggal));
			$tahun    = date('Y',strtotime($tanggal));
			
			$nourut = 1;
			_rekamjurnal_header($cabang, $nobukti, $_tanggal, $ketglobal, $userid, 0,0);
			for($i=0;$i<=$jumdata-1;$i++)
			{
			    $_akun   = $kodeakun[$i];
				$_ket    = $ket[$i];
				$_dept   = $dept[$i];
				$_debet  =  str_replace(',','',$debet[$i]);
				$_kredit =  str_replace(',','',$kredit[$i]);
				
				if($_akun!=""){
					_rekamjurnal_detil($cabang, $nobukti, $_akun, $_debet, 
					$_kredit, $_ket, $_dept, '');
					
					$nourut++;
				}
			    
			}
			
						
			
		}
		else
		{
			header('location:'.base_url());
		}
	}
	
	
}
