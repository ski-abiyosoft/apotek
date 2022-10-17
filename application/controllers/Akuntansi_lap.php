<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Akuntansi_lap extends CI_Controller {

	
	
	// Inisiasi class
	// coba lagi
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('M_global','M_global');
		$this->load->helper('simkeu_rpt');		
		$this->session->set_userdata('menuapp', '5000');
		$this->session->set_userdata('submenuapp', '5503');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{		
			$unit = $this->session->userdata('unit');				
			$this->load->helper('url');		
			$d['cabang']= $this->db->get('tbl_namers')->result();
            $this->load->view('akuntansi/v_akuntansi_lap',$d);
		} else
		{
			header('location:'.base_url());
		}			
	}
	
	
	
	public function cetak($x)
	{
		$cek  = $this->session->userdata('level');
        $unit = $this->session->userdata('unit');		
		if(!empty($cek))
		{				 
          $profile = $this->M_global->_LoadProfileLap();
		  $nama_usaha=$profile->nama_usaha;
		  $motto = $profile->alamat1;
		  $alamat= $profile->alamat2;
		  
		  $data  = explode("~",$x);
		  $idlp  = $data[0];
		  $tgl1  = $data[1];
		  $tgl2  = $data[2];
		  $akun  = $data[3];
		  $cab   = $data[4];
		  //$namaunit = $this->M_global->_namaunit($unit);
		  if($cab!=""){
		  $namaunit = $this->M_global->_namaunit($cab);
		  } else {
		  $namaunit = "";	  
		  }
		  $_tgl1 = date('Y-m-d',strtotime($tgl1));
		  $_tgl2 = date('Y-m-d',strtotime($tgl2));
		  $_peri = 'Dari '.date('d-m-Y',strtotime($tgl1)).' s/d '.date('d-m-Y',strtotime($tgl2));
		  $_peri1= 'Per Tgl. '.date('d',strtotime($tgl2)).' '.$this->M_global->_namabulan(date('n',strtotime($tgl2))).' '.date('Y',strtotime($tgl2));
		  
		  if($idlp==101){				
            $query = 
            "select * from tbl_jurnalh inner join tbl_jurnald on tbl_jurnalh.nobukti=tbl_jurnald.nobukti
			 inner join tbl_accounting on tbl_jurnald.accountno = tbl_accounting.accountno
			 where tbl_jurnalh.tgljurnal between '$_tgl1' and '$_tgl2' "; 			
			
            if($akun!=""){
			$query.= "and tbl_jurnald.accountno = '$akun'";	
			} 			
			if($cab!=""){
			$query.= "and tbl_jurnalh.koders = '$cab'";	
			} 			
			$query.= "order by tbl_jurnalh.tgljurnal, tbl_jurnalh.nobukti";
			
			$judul=array('NO','TANGGAL','NO. BUKTI','KODE AKUN','NAMA AKUN','URAIAN','DEBET','KREDIT');	  
			
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Daftar Jurnal');
			$pdf->setsubjudul($_peri);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(10,19,30,18,30,40,24,24));
			$pdf->SetAligns(array('C','C','C','C','C','C','C','C'));
			$pdf->setfont('Times','B',9);
			$pdf->row($judul);
			$pdf->SetWidths(array(10,19,30,18,30,40,24,24));
			$pdf->SetAligns(array('C','C','C','C','L','L','R','R'));
			$pdf->setfont('Times','',9);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;
            $totdb  = 0;
            $totkr  = 0;			
			foreach($lap as $db)
			{
			  $pdf->Row(array(
			  $nourut, 
			  date('d-m-Y',strtotime($db->tgljurnal)),
			  $db->nobukti,
			  $db->accountno,
			  $db->acname,
			  $db->dketerangan,
			  number_format($db->debet,2,'.',','),
			  number_format($db->credit,2,'.',',')));
			  $totdb = $totdb + $db->debet;
			  $totkr = $totkr + $db->credit;
			  $nourut++;
			}
			$pdf->SetWidths(array(147,24,24));
			$pdf->SetAligns(array('R','R','R'));
			 
			$pdf->setfont('Times','B',9);
			$pdf->Row(array(
			  'JUMLAH  ',
			  number_format($totdb,2,'.',','),
			  number_format($totkr,2,'.',',')));
			$pdf->ln();
			
			$pdf->AliasNbPages();
			$pdf->SetTitle('LAPORAN JURNAL');
			$pdf->output('gl_jurnal.PDF','I');
		  }	else 
		  if($idlp==102){				
            if($akun!=""){
			$bulan  = date('n',strtotime($tgl1));
			$tahun  = date('Y',strtotime($tgl1));
            $qsaldo = $this->db->select('debet, credit')->get_where('tbl_coa_saldo', array('accountno' => $akun, 'bulan' => $bulan, 'tahun' => $tahun))->row();
			if ($qsaldo){
			$saldo_db = $qsaldo->debet;
			$saldo_kr = $qsaldo->credit;
			} else {
			$saldo_db = 0;
			$saldo_kr = 0;
			}
			$jenis_akun = $this->M_global->_jenisakun($akun);
			
			$saldo  = $saldo_db+$saldo_kr;
				
			$query = 
			 "select * from tbl_jurnalh inner join tbl_jurnald on tbl_jurnalh.nobukti=tbl_jurnald.nobukti
			 inner join tbl_accounting on tbl_jurnald.accountno = tbl_accounting.accountno
			 where tbl_jurnalh.tgljurnal between '$_tgl1' and '$_tgl2' "; 			
			
            if($akun!=""){
			$query.= "and tbl_jurnald.accountno = '$akun'";	
			} 			
			if($cab!=""){
			$query.= "and tbl_jurnalh.koders = '$cab'";	
			} 			
			$query.= "order by tbl_jurnalh.tgljurnal, tbl_jurnalh.nobukti";
			
			$judul=array('NO','TANGGAL','NO. BUKTI','URAIAN','DEBET','KREDIT','SALDO');	  
			$akunbb = ' ('.$this->M_global->_namaakun($akun).' - '.$akun. ')';
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Buku Besar '.$akunbb);
			$pdf->setsubjudul($_peri);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			
			
			$border= array('BT','BT','BT','BT','BT','BT','BT');
			$align = array('C','C','C','C','R','R','R');
			
			$pdf->SetWidths(array(10,19,25,70,24,24,24));
			$pdf->SetAligns(array('C','C','C','C','C','R','R','R'));
			$pdf->setfont('Times','B',9);
			$pdf->FancyRow($judul, $border, $align);
			$pdf->SetWidths(array(124,24,24,24));
			$pdf->SetAligns(array('L','R','R','R'));
			 
			$pdf->setfont('Times','B',9);
			$pdf->RowB(array(
			  'Saldo Awal  ',
			  '','',
			  number_format($saldo,2,'.',',')));
			  
			$pdf->SetWidths(array(10,19,25,70,24,24,24));
			$pdf->SetAligns(array('C','C','C','L','R','R','R'));
			$pdf->setfont('Times','',9);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;
            $totdb  = 0;
            $totkr  = 0;			
			foreach($lap as $db)
			{
		      if($jenis_akun=="D"){
				  $saldo = $saldo + $db->debet - $db->credit;
			  } else {
				  $saldo = $saldo - $db->debet + $db->credit;
			  }
			  
			  $pdf->RowB(array(
			  $nourut, 
			  date('d-m-Y',strtotime($db->tgljurnal)),
			  $db->nobukti,
			  $db->dketerangan,
			  number_format($db->debet,2,'.',','),
			  number_format($db->credit,2,'.',','),
			  number_format($saldo,2,'.',',')));
			  $totdb = $totdb + $db->debet;
			  $totkr = $totkr + $db->credit;
			  
			  $nourut++;
			}
			$pdf->SetWidths(array(124,24,24,24));
			$pdf->SetAligns(array('R','R','R','R'));
			$align = array('R','R','R','R'); 
			$border= array('BT','BT','BT','TB');
			$pdf->setfont('Times','B',9);
			$pdf->FancyRow(array(
			  'JUMLAH  ',
			  number_format($totdb,2,'.',','),
			  number_format($totkr,2,'.',','),
			  number_format($saldo,2,'.',',')), $border, $align);
			$pdf->ln();
			
			$pdf->SetTitle('LAPORAN BUKU BESAR');
			$pdf->AliasNbPages();
			$pdf->output('gl_bukubesar.PDF','I');	
			}
		  }	else 
          if($idlp==103){				
         	$bulan  = date('n',strtotime($tgl1));
			$tahun  = date('Y',strtotime($tgl1));
        	
			$query = 
            "select tbl_coa_saldo.*, tbl_accounting.accountno as kodeakun1, 
			tbl_accounting.acname from tbl_coa_saldo right outer join tbl_accounting on 
			 tbl_coa_saldo.accountno = tbl_accounting.accountno
			 and tahun = '$tahun' where tbl_accounting.aclevel=4  order by tbl_accounting.accountno"; 			
			
			
			$judul0=array('','','','SALDO AWAL','MUTASI','NERACA','LABA RUGI');	  
			$judul=array('NO','KODE AKUN','NAMA AKUN','DEBET','KREDIT','DEBET','KREDIT','DEBET','KREDIT','DEBET','KREDIT');	  
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Neraca Saldo ');
			$pdf->setsubjudul($_peri);
			$pdf->addpage("L","A4");   
			$pdf->setsize("L","A4");
			
			$pdf->SetWidths(array(10,20,50,50,50,50,50));
			$pdf->SetAligns(array('C','C','C','C','C','C','C'));
			$pdf->setfont('Times','B',9);
			$pdf->row($judul0);
			$pdf->SetWidths(array(10,20,50,25,25,25,25,25,25,25,25));
			$pdf->SetAligns(array('C','C','C','C','C','C','C','C','C','C','C'));
			$pdf->setfont('Times','B',9);
			$pdf->row($judul);
			
			$pdf->SetWidths(array(10,20,50,25,25,25,25,25,25,25,25));
			$pdf->SetAligns(array('C','C','L','R','R','R','R','R','R','R','R'));
			$pdf->setfont('Times','',9);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;
            $totdba  = 0;
            $totkra  = 0;			
			$totdbm  = 0;
            $totkrm  = 0;			
			$totdbn  = 0;
            $totkrn  = 0;			
			$totdbl  = 0;
            $totkrl  = 0;			
			
			foreach($lap as $db)
			{
			  $akun= $db->kodeakun1;
			  $jenis_akun = $this->M_global->_jenisakun($akun);
			  $lap_akun   = $this->M_global->_lapakun($akun);
			  if(empty($db->debet) || empty($db->kredit)){
			  $dba = 0;
              $kra = 0;			  
			  } else {
			  $dba = $db->debet;
			  $kra = $db->kredit;
			  }
			  
			  
			 $query = 
			 "select sum(tbl_jurnald.debet) as debet, sum(tbl_jurnald.credit) as kredit  from tbl_jurnalh inner join tbl_jurnald on tbl_jurnalh.nobukti=tbl_jurnald.nobukti
			 inner join tbl_accounting on tbl_jurnald.accountno = tbl_accounting.accountno
			 where tbl_jurnalh.tgljurnal between '$_tgl1' and '$_tgl2' and tbl_jurnald.accountno='$akun'"; 			
			
           	
			  if($cab!=""){
			     $query.= "and tbl_jurnalh.koders = '$cab'";	
			  } 			
			  
			  $jur = $this->db->query($query)->row();
			  
			  $dbm = $jur->debet;
			  $krm = $jur->kredit;
			  $dbakhir = 0;
			  $krakhir = 0;
			  if($jenis_akun=="D"){
				  $dbsaldo = ($dba + $kra) + $dbm - $krm;
				  if($dbsaldo>0){
					  $dbakhir = abs($dbsaldo);
					  $krakhir = 0;
				  } else {
					  $dbakhir = 0;
					  $krakhir = abs($dbsaldo);
				  }
			  } else {
				  $dbsaldo = ($dba + $kra) - $dbm + $krm;
				  if($dbsaldo>0){
					  $dbakhir = 0;
					  $krakhir = abs($dbsaldo);
				  }	else {
					  $dbakhir = abs($dbsaldo);
					  $krakhir = 0;
				  }  
			  }
			  
			  if($lap_akun!="A3"){
				  $dbn = $dbakhir;
				  $krn = $krakhir;
				  $dbl = 0;
				  $krl = 0;
			  } else {
				  $dbl = $dbakhir;
				  $krl = $krakhir;
				  $dbn = 0;
				  $krn = 0;
			  }
			  
			  
			  
			  
			  
			  $pdf->Row(array(
			  $nourut, 
			  $db->kodeakun1,
			  $db->acname,
			  number_format($dba,2,'.',','),
			  number_format($kra,2,'.',','),
			  number_format($dbm,2,'.',','),
			  number_format($krm,2,'.',','),
			  number_format($dbn,2,'.',','),
			  number_format($krn,2,'.',','),
			  number_format($dbl,2,'.',','),
			  number_format($krl,2,'.',',')
			  
			  ));
			  $totdba = $totdba + $dba;
			  $totkra = $totkra + $kra;
			  $totdbm = $totdbm + $dbm;
			  $totkrm = $totkrm + $krm;
			  $totdbn = $totdbn + $dbn;
			  $totkrn = $totkrn + $krn;
			  $totdbl = $totdbl + $dbl;
			  $totkrl = $totkrl + $krl;
			  
			  $nourut++;
			}
			$pdf->SetWidths(array(80,25,25,25,25,25,25,25,25));
			$pdf->SetAligns(array('C','R','R','R','R','R','R','R','R'));
			 
			$pdf->setfont('Times','B',9);
			$pdf->Row(array(
			  'JUMLAH  ',
			  number_format($totdba,2,'.',','),
			  number_format($totkra,2,'.',','),
			  number_format($totdbm,2,'.',','),
			  number_format($totkrm,2,'.',','),
			  number_format($totdbn,2,'.',','),
			  number_format($totkrn,2,'.',','),
			  number_format($totdbl,2,'.',','),
			  number_format($totkrl,2,'.',',')));
			  
			$pdf->ln();
			
			$pdf->SetTitle('LAPORAN TRIAL BALANCE');
			$pdf->AliasNbPages();
			$pdf->output('gl_neracasaldo.PDF','I');	
			
		  }	else 
          if($idlp==104){				
         	$bulan  = date('n',strtotime($tgl1));
			$tahun  = date('Y',strtotime($tgl1));
			
			$query0 = "select * from tbl_ac_type where ackode='A1' order by nokel1";
			
			
			$lap = $this->db->query($query0)->result();
			
			$judul=array('Deskripsi','','Nilai');	  
			$border= array('B','','B');
			$align = array('L','','R');
			
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Neraca');
			$pdf->setsubjudul($_peri1);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			
			$pdf->SetWidths(array(120,10,60));
			$pdf->SetAligns(array('C','C','R'));
			$pdf->setfont('Times','B',10);
			$pdf->Fancyrow($judul, $border, $align);
			$pdf->SetWidths(array(120,10,60));
			$pdf->SetAligns(array('L','C','R'));
			$pdf->setfont('Times','B',9);
			
			$pdf->SetWidths(array(10,20,50,25,25,25,25,25,25,25,25));
			$pdf->SetAligns(array('C','C','L','R','R','R','R','R','R','R','R'));
			$pdf->setfont('Times','',10);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;
            		
			$pdf->SetWidths(array(120,10,60));
			foreach($lap as $db)
			{
			  $pdf->setfont('Times','B',10);	
			  $border= array('','','');
			  $align = array('L','','R');	
			  $font  = array('B','','B');	
			  $pdf->Fancyrow(array($db->typename,'',''), $border, $align);
			  
			  
			  $query2 = "select distinct kelompok from ms_akun_kelompok where kelompok1 = '".$db->kelompok1."' order by nokel";
			  $lap1   = $this->db->query($query2)->result();
			  $tot1   = 0;
			  $totk   = 0;
			  foreach($lap1 as $db1)
			  {
				      $pdf->setfont('Times','B',10);	
					  $border= array('','','');
					  $align = array('L','','R');	
					  $font  = array('B','','B');	
					  $pdf->Fancyrow(array('     '.$db1->kelompok,'',''), $border, $align);
						  
					  $query3 = "select nama, kode from ms_akun_kelompok where kelompok = '".$db1->kelompok."' order by nu";
			          $lap2   = $this->db->query($query3)->result();

					 $tot2 = 0; 
					 foreach($lap2 as $db2)
			         {
						  $pdf->setfont('Times','',10);	
						  $pdf->Fancyrow(array('     '.'     '.$db2->nama,'',''), $border, $align);
							  
						  $query4 = "select kodeakun, namaakun from ms_akun where kelompok = '".$db2->kode."' order by kodeakun";
			              $lap3   = $this->db->query($query4)->result();	  
						  
						  $tot3   = 0;
						  foreach($lap3 as $db3)
			              {
							  $pdf->setfont('Times','',10);	
							  $jenis_akun = $this->M_global->_jenisakun($db3->kodeakun);
							  $saldo_awal = $this->M_global->_saldoakun($db3->kodeakun, $bulan, $tahun);
							  $mutasi     = $this->M_global->_totjurnal($db3->kodeakun, $jenis_akun, $tgl1, $tgl2);
							  $nilai      = $saldo_awal + $mutasi;
							  $tot3       = $tot3+$nilai;
							  
							  if($db3->kodeakun==$this->M_global->_akunlrberjalan()){
								  $lrj = $this->M_global->_labarugiberjalan($tgl1, $tgl2);
								  $tot3= $tot3 + $lrj;
								  $pdf->Fancyrow(array('     '.'     '.'     '.$db3->namaakun,'',number_format($lrj,2,',','.')), $border, $align);
								  
						     }
							 
							  if($nilai!=0){
							  $pdf->Fancyrow(array('     '.'     '.'     '.$db3->namaakun,'',number_format($nilai,2,',','.')), $border, $align);
							  }
						  }
						   
						  
						  $pdf->setfont('Times','B',10);
                          $border= array('','','B');						  
					      $pdf->Fancyrow(array('     '.'     '.'Jumlah '.$db2->nama,'',number_format($tot3,2,',','.')), $border, $align); 
						  $pdf->ln(5);	  
						  $tot2 = $tot2 + $tot3;
						  
						  
					 }
					 
					  $pdf->setfont('Times','B',10);	
					 	
					  $pdf->Fancyrow(array('     '.'Jumlah '.$db1->kelompok,'',number_format($tot2,2,',','.')), $border, $align);
                      $tot1 = $tot1 + $tot2;	

                      if($db->nokel1!=1){
						 $totk = $totk + $tot2;	 
					  }					  
						  
			  }
			  $pdf->setfont('Times','B',10);	
					  
			  $pdf->Fancyrow(array('Jumlah   '.$db->kelompok1,'',number_format($tot1,2,',','.')), $border, $align);
			  
			  
			 
			  $nourut++;
			}
			$pdf->Fancyrow(array('JUMLAH KEWAJIBAN DAN EKUITAS   ','',number_format($totk,2,',','.')), $border, $align);
			  
			$pdf->ln();
			$pdf->SetTitle('LAPORAN NERACA');
			$pdf->AliasNbPages();
			$pdf->output('gl_neraca.PDF','I');	
			
		  }	else 
          if($idlp==105){				
         	$bulan  = date('n',strtotime($tgl1));
			$tahun  = date('Y',strtotime($tgl1));
			
			$query0 = "select distinct kelompok1, nokel1 from ms_akun_kelompok where lap='L' order by nokel1";
			
			
			$lap = $this->db->query($query0)->result();
			
			$judul=array('Deskripsi','','Nilai');	  
			$border= array('B','','B');
			$align = array('L','','R');
			
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Laba/Rugi');
			$pdf->setsubjudul($_peri);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			
			$pdf->SetWidths(array(120,10,60));
			$pdf->SetAligns(array('C','C','R'));
			$pdf->setfont('Times','B',10);
			$pdf->Fancyrow($judul, $border, $align);
			$pdf->SetWidths(array(120,10,60));
			$pdf->SetAligns(array('L','C','R'));
			$pdf->setfont('Times','B',9);
			
			$pdf->SetWidths(array(10,20,50,25,25,25,25,25,25,25,25));
			$pdf->SetAligns(array('C','C','L','R','R','R','R','R','R','R','R'));
			$pdf->setfont('Times','',10);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;
            		
			$pdf->SetWidths(array(120,10,60));
			foreach($lap as $db)
			{
			  $pdf->setfont('Times','B',10);	
			  $border= array('','','');
			  $align = array('L','','R');	
			  $font  = array('B','','B');	
			  $pdf->Fancyrow(array($db->kelompok1,'',''), $border, $align);
			  
			  
			  $query2 = "select distinct kelompok from ms_akun_kelompok where kelompok1 = '".$db->kelompok1."' order by nokel";
			  $lap1   = $this->db->query($query2)->result();
			  $tot1   = 0;
			  $totp   = 0;
			  $totb   = 0;
			  foreach($lap1 as $db1)
			  {
				      $pdf->setfont('Times','B',10);	
					  $border= array('','','');
					  $align = array('L','','R');	
					  $font  = array('B','','B');	
					  $pdf->Fancyrow(array('     '.$db1->kelompok,'',''), $border, $align);
						  
					  $query3 = "select nama, kode from ms_akun_kelompok where kelompok = '".$db1->kelompok."' order by nu";
			          $lap2   = $this->db->query($query3)->result();

					 $tot2 = 0; 
					 foreach($lap2 as $db2)
			         {
						  $pdf->setfont('Times','',10);	
						  $pdf->Fancyrow(array('     '.'     '.$db2->nama,'',''), $border, $align);
							  
						  $query4 = "select kodeakun, namaakun from ms_akun where kelompok = '".$db2->kode."' order by kodeakun";
			              $lap3   = $this->db->query($query4)->result();	  
						  
						  $tot3   = 0;
						  foreach($lap3 as $db3)
			              {
							  $pdf->setfont('Times','',10);	
							  $jenis_akun = $this->M_global->_jenisakun($db3->kodeakun);
							  $mutasi     = $this->M_global->_totjurnal($db3->kodeakun, $jenis_akun, $tgl1, $tgl2);
							  $nilai      = $mutasi;
							  $tot3       = $tot3+$nilai;
							  
							  if($nilai!=0){
							  $pdf->Fancyrow(array('     '.'     '.'     '.$db3->namaakun,'',number_format($nilai,2,',','.')), $border, $align);
							  }
						  }
						   
						  
						  $pdf->setfont('Times','B',10);
                          $border= array('','','B');						  
					      $pdf->Fancyrow(array('     '.'     '.'Jumlah '.$db2->nama,'',number_format($tot3,2,',','.')), $border, $align); 
						  $pdf->ln(5);	  
						  $tot2 = $tot2 + $tot3;
						  
						  
					 }
					 
					  $pdf->setfont('Times','B',10);	
					 	
					  $pdf->Fancyrow(array('     '.'Jumlah '.$db1->kelompok,'',number_format($tot2,2,',','.')), $border, $align);
                      $tot1 = $tot1 + $tot2;	
                       $pdf->ln(5);	
                     			  
						  
			  }
			  $pdf->setfont('Times','B',10);	
					  
			  $pdf->Fancyrow(array('Jumlah   '.$db->kelompok1,'',number_format($tot1,2,',','.')), $border, $align);
			  $pdf->ln(5);	
			  
			 
			  $nourut++;
			}
			$pdf->Fancyrow(array('LABA BERSIH   ','',number_format($this->M_global->_labarugiberjalan($tgl1, $tgl2),2,',','.')), $border, $align);
			  
			$pdf->ln();
			$pdf->SetTitle('LAPORAN LABA RUGI');
			$pdf->AliasNbPages();
			$pdf->output('gl_labarugi.PDF','I');	
			
		  }					  
          		  		  
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	public function export()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{				  
		  $page=$this->uri->segment(3);
		  $limit=$this->config->item('limit_data');	
          $d['master_bank'] = $this->db->get("ms_bank");
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  
		  //$this->load->view('master/bank/v_master_bank_exp',$d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
	
	
}

/* End of file akuntansi_jurnal.php */
/* Location: ./application/controllers/akuntansi_jurnal.php */