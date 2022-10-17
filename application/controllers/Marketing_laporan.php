<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marketing_laporan extends CI_Controller {

	
	
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('M_global','M_global');
		$this->load->helper('simkeu_rpt');		
		$this->session->set_userdata('menuapp', '2000');
		$this->session->set_userdata('submenuapp', '2403');
		
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{		
			$unit = $this->session->userdata('unit');				
			$this->load->helper('url');		
			$d['cabang']= $this->db->get('tbl_namers')->result();
            
			$this->load->view('klinik/v_marketing_lap',$d);
		} else
		{
			header('location:'.base_url());
		}			
	}
	
	
	
	public function cetak()
	{
		ini_set('memory_limit', '-1');
		
		$cek  = $this->session->userdata('level');
        $unit = $this->session->userdata('unit');		
		if(!empty($cek))
		{				 
          $profile = $this->M_global->_LoadProfileLap();
		  $nama_usaha=$profile->nama_usaha;
		  $motto = '';
		  $alamat= '';
		  $namaunit = $this->M_global->_namaunit($unit);
		  
		  $idlp  = $this->input->get('idlap');
		  $tgl1  = $this->input->get('tgl1');
		  $tgl2  = $this->input->get('tgl2');
		  $dokter= $this->input->get('dokter');
		  $cab   = $this->input->get('cab');
		  //$unit  = $this->input->get('unit');
		  
		  $_tgl1 = date('Y-m-d',strtotime($tgl1));
		  $_tgl2 = date('Y-m-d',strtotime($tgl2));
		  $_peri = 'Dari '.date('d-m-Y',strtotime($tgl1)).' s/d '.date('d-m-Y',strtotime($tgl2));
		  //$_peri1= 'Dari . '.date('d',strtotime($tgl2)).' sampai '.$this->M_global->_namabulan(date('n',strtotime($tgl2))).' '.date('Y',strtotime($tgl2));
		  
		  if($idlp==101){	
		    $bulan = date('n',strtotime($tgl1)); 
			$tahun = date('Y',strtotime($tgl2)); 
            $query = 
            "select tbl_kasir.*, tbl_pasien.* from tbl_kasir 
			inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
			where tglbayar between '$_tgl1' and '$_tgl2'
			 ";
			
             
			$query.= "order by tbl_kasir.tglbayar";
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('LAPORAN HARIAN KASIR PENDAFTARAN PER SHIPT ');
			$pdf->setsubjudul($_peri);
			$pdf->addpage("L","A3");   
			$pdf->setsize("L","A3");
			$pdf->SetWidths(array(10,22,20,30,15,15,15,15,15,15,15,15,15,20,20,20,20,20,20,20,20,20));
			$pdf->SetAligns(array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C'));
			$judul=array('No.','Kwitansi','Tangal','Nama Pasien','Adm','U.Muka','J.Kulit','Resep','R/Label','J.Gigi','Lain-lain','O.Kirim','Ongkir','J.Spa','R/Spa','Apotek','Total','Selisih','Diskon','Voucher','Total Aset','');
			$pdf->setfont('Arial','B',8);
			$pdf->row($judul);
			
			$pdf->SetWidths(array(10,22,20,30,15,15,15,15,15,15,15,15,15,20,20,20,20,20,20,20,20,20));
			$pdf->SetAligns(array('C','C','C','L','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R'));
			$pdf->setfont('Arial','',8);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;
            
			foreach($lap as $db)
			{
			  $noreg = $db->noreg;
			  
			  
			  $pdf->row(array($nourut, 
			  $db->nokwitansi, 
			  tanggal($db->tglbayar), 
			  $db->namapas, 
			  angka_rp($db->adm,0), 
			  angka_rp($db->uangmuka,0), 
			  angka_rp($db->totalpoli,0), 
			  angka_rp($db->totalresep,0), 
			  angka_rp(0,0), 
			  angka_rp($db->lain,0), 
			  angka_rp($db->lain,0), 
			  angka_rp($db->lain,0), 
			  angka_rp($db->lain,0), 
			  angka_rp($db->lain,0), 
			  angka_rp($db->lain,0), 
			  angka_rp($db->lain,0), 
			  angka_rp($db->totalsemua,0), 
			  angka_rp($db->selisihrp,0), 
			  angka_rp($db->diskonrp,0), 
			  angka_rp($db->voucherrp,0), 
			  angka_rp($db->totalsemua,0), 
			  '', 
			  ));
			  
			  $nourut++;
			}
			
            $pdf->SetTitle('LAPORAN HARIAN KASIR PENDAFTARAN PER SHIPT');
			$pdf->AliasNbPages();
			$pdf->output('KASIR-01.PDF','I');
		  }	else if($idlp==102){	
		    $bulan = date('n',strtotime($tgl1)); 
			$tahun = date('Y',strtotime($tgl2)); 
            $query = 
		    "
			 select 
			tbl_dpoli.kodetarif as kode,
			tbl_tarifh.tindakan as nama,
			coalesce(sum(case when tbl_pasien.jkel='P' then 1 end),0) as jmlpasienp, 
			coalesce(sum(case when tbl_pasien.jkel='W' then 1 end),0) as jmlpasienw, 
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 15 and 20 then 1 end),0) as jmlpasien1, 
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 21 and 25 then 1 end),0) as jmlpasien2, 
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 26 and 30 then 1 end),0) as jmlpasien3, 
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 31 and 35 then 1 end),0) as jmlpasien4, 
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 36 and 40 then 1 end),0) as jmlpasien5, 
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) >40 then 1 end),0) as jmlpasien6, 
			coalesce(count(tbl_kasir.rekmed),0) as jmlpasien, 
			coalesce(sum(tbl_dpoli.rsnet),0) as omset
			from tbl_kasir 
			inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
			inner join tbl_hpoli on tbl_kasir.noreg=tbl_hpoli.noreg
			inner join tbl_dpoli on tbl_hpoli.noreg=tbl_dpoli.noreg
			inner join tbl_tarifh on tbl_dpoli.kodetarif=tbl_tarifh.kodetarif
			where tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2' and tbl_kasir.koders='$unit'			
			group by tbl_dpoli.kodetarif			
			";
			
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('REKAP PENDAPATAN BERDASARKAN JASA');
			$pdf->setsubjudul($_peri);
			$pdf->addpage("L","A4");   
			$pdf->setsize("L","A4");
			$pdf->SetWidths(array(10,20,70,40,105,30));
			$pdf->SetAligns(array('L','L','L','C','C','R'));
			$judul=array('','','','JENIS KELAMIN','UMUR','');
			$pdf->setfont('Arial','B',10);
			$pdf->row($judul);
			$pdf->SetWidths(array(10,20,70,20,20,15,15,15,15,15,15,15,30));
			$pdf->SetAligns(array('L','L','L','C','C','C','C','C','C','C','C','C','C','R'));
			$judul=array('No.','Kode','Nama Tindakan','PRIA','WANITA','15-20','21-25','26-30','31-35','36-40','>40','Jumlah','Total Rp');
			$pdf->setfont('Arial','B',10);
			$pdf->row($judul);
			
			$pdf->SetWidths(array(10,20,70,20,20,15,15,15,15,15,15,15,30));
			$pdf->SetAligns(array('L','L','L','R','R','R','R','R','R','R','R','R','R','R'));
			
			$pdf->setfont('Arial','',9);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;
            $tot1 = $tot2 = $tot3 = $tot4= $tot5 = $tot6 = $tot7 = $tot8 = $tot9 = $tot10 =  0;
			foreach($lap as $db)
			{
			 
			  $pdf->row(array(
			  $nourut,
			  $db->kode, 
			  $db->nama, 
			  $db->jmlpasienp, 
			  $db->jmlpasienw, 
			  $db->jmlpasien1, 
			  $db->jmlpasien2, 
			  $db->jmlpasien3, 
			  $db->jmlpasien4, 
			  $db->jmlpasien5, 
			  $db->jmlpasien6, 
			  $db->jmlpasien, 
			  angka_rp($db->omset,0)
			  
			  ));
			  $tot1 += $db->jmlpasienp;
			  $tot2 += $db->jmlpasienw;
			  $tot3 += $db->jmlpasien1;
			  $tot4 += $db->jmlpasien2;
			  $tot5 += $db->jmlpasien3;
			  $tot6 += $db->jmlpasien4;
			  $tot7 += $db->jmlpasien5;
			  $tot8 += $db->jmlpasien6;
			  $tot9 += $db->jmlpasien;
			  $tot10 += $db->omset;
			  
			  $nourut++;
			}
			$pdf->SetWidths(array(100,20,20,15,15,15,15,15,15,15,30));
			$pdf->SetAligns(array('C','R','R','R','R','R','R','R','R','R','R','R'));
			
			$pdf->setfont('Arial','B',9);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			
			$pdf->row(array(
			  'TOTAL',
			  $tot1, 
			  $tot2, 
			  $tot3, 
			  $tot4, 
			  $tot5, 
			  $tot6, 
			  $tot7, 
			  $tot8, 
			  $tot9, 
			  angka_rp($tot10,0)));
			
            $pdf->SetTitle('REKAP PENJUALAN HARIAN');
			$pdf->AliasNbPages();
			$pdf->output('MARKETING-02.PDF','I');
		  } else if($idlp==103){	
		    $bulan = date('n',strtotime($tgl1)); 
			$tahun = date('Y',strtotime($tgl2)); 
            $query = 
			 "select tbl_kasir.*, tbl_pasien.*, tbl_apoposting.resepno from tbl_kasir 
			  inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
			  left outer join tbl_apoposting on tbl_kasir.nokwitansi=tbl_apoposting.nokwitansi
			  where tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2'
			 ";
			
             
			$query.= "order by tbl_kasir.tglbayar";		            
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('REKAP JASA DAN PENJUALAN');
			$pdf->setsubjudul($_peri);
			$pdf->addpage("L","A4");   
			$pdf->setsize("L","A4");
			$pdf->SetWidths(array(32,22,35,20,25,20,20,20,20,20,30,20));
			$pdf->SetAligns(array('C','C','C','C','C','C','C','C','C','C','C','C'));
			$judul=array('TR No','Tanggal','Pro','Adm+Jasa','Obat Tunai','Lokal','Kirim','Obat Spa','Apotek','Obat Gigi','Total','No. Resep');
			$pdf->setfont('Arial','B',8);
			$pdf->row($judul);
			
			$pdf->SetWidths(array(32,22,35,20,25,20,20,20,20,20,30,20));
			$pdf->SetAligns(array('C','C','L','R','R','R','R','R','R','R','R','L'));
			$pdf->setfont('Arial','',8);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;
            $tot1 = $tot2 = $tot3 = $tot4 = $tot5 = $tot6 = $tot7 = $tot8 = 0;
			foreach($lap as $db)
			{
			  $tot1 += $db->adm+$db->totalpoli;
			  $tot6 += $db->totalresep;
			  $tot8 += $db->totalsemua;
			  
			  $pdf->row(array(
			  $db->noreg, 
			  tanggal($db->tglbayar), 
			  $db->namapas, 
			  angka_rp($db->adm+$db->totalpoli,0), 
			  angka_rp(0,0), 
			  angka_rp(0,0), 
			  angka_rp(0,0), 
			  angka_rp(0,0), 
			  angka_rp($db->totalresep,0), 
			  angka_rp(0,0), 
			  angka_rp($db->totalsemua,0), 
			  $db->resepno
			 
			  
			  ));
			  
			  $nourut++;
			}
			$pdf->setfont('Arial','B',8);
			$pdf->SetWidths(array(89,20,25,20,20,20,20,20,30,20));
			$pdf->SetAligns(array('L','R','R','R','R','R','R','R','R','L'));
			$pdf->row(array(
			  'Total', 
			  angka_rp($tot1,0), 
			  angka_rp($tot2,0), 
			  angka_rp($tot3,0),
			  angka_rp($tot4,0),
			  angka_rp($tot5,0),
			  angka_rp($tot6,0),
			  angka_rp($tot7,0),
			  angka_rp($tot8,0),
			  $db->resepno
			 
			  
			  ));
            $pdf->SetTitle('REKAP JASA DAN PENJUALAN');
			$pdf->AliasNbPages();
			$pdf->output('KASIR-03.PDF','I');
		  } else if($idlp==104){	
		    $bulan = date('n',strtotime($tgl1)); 
			$tahun = date('Y',strtotime($tgl2)); 
            $query = 
			"
			 select 
			tbl_dpoli.kodokter as kode,
			tbl_dokter.nadokter as nama,
			coalesce(sum(case when tbl_pasien.jkel='P' then 1 end),0) as jmlpasienp, 
			coalesce(sum(case when tbl_pasien.jkel='W' then 1 end),0) as jmlpasienw, 
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 15 and 20 then 1 end),0) as jmlpasien1, 
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 21 and 25 then 1 end),0) as jmlpasien2, 
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 26 and 30 then 1 end),0) as jmlpasien3, 
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 31 and 35 then 1 end),0) as jmlpasien4, 
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 36 and 40 then 1 end),0) as jmlpasien5, 
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) >40 then 1 end),0) as jmlpasien6, 
			coalesce(count(tbl_kasir.rekmed),0) as jmlpasien, 
			coalesce(sum(tbl_dpoli.rsnet),0) as omset
			from tbl_kasir 
			inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
			inner join tbl_hpoli on tbl_kasir.noreg=tbl_hpoli.noreg
			inner join tbl_dpoli on tbl_hpoli.noreg=tbl_dpoli.noreg
			inner join tbl_dokter on tbl_dpoli.kodokter=tbl_dokter.kodokter
			where tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2' and tbl_kasir.koders='$unit'			
			group by tbl_dpoli.kodokter			
			";
			
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('REKAP PRAKTEK DOKTER');
			$pdf->setsubjudul($_peri);
			$pdf->addpage("L","A4");   
			$pdf->setsize("L","A4");
			$pdf->SetWidths(array(10,90,40,105,30));
			$pdf->SetAligns(array('L','L','C','C','R'));
			$judul=array('','','JENIS KELAMIN','UMUR','');
			$pdf->setfont('Arial','B',10);
			$pdf->row($judul);
			$pdf->SetWidths(array(10,90,20,20,15,15,15,15,15,15,15,30));
			$pdf->SetAligns(array('L','L','L','C','C','C','C','C','C','C','C','C','C','R'));
			$judul=array('No.','DOKTER','PRIA','WANITA','15-20','21-25','26-30','31-35','36-40','>40','Jumlah','Total Rp');
			$pdf->setfont('Arial','B',10);
			$pdf->row($judul);
			
			$pdf->SetWidths(array(10,90,20,20,15,15,15,15,15,15,15,30));
			$pdf->SetAligns(array('L','L','R','R','R','R','R','R','R','R','R','R','R'));
			
			$pdf->setfont('Arial','',9);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;
            $tot1 = $tot2 = $tot3 = $tot4= $tot5 = $tot6 = $tot7 = $tot8 = $tot9 = $tot10 =  0;
			foreach($lap as $db)
			{
			 
			  $pdf->row(array(
			  $nourut,
			  $db->nama, 
			  $db->jmlpasienp, 
			  $db->jmlpasienw, 
			  $db->jmlpasien1, 
			  $db->jmlpasien2, 
			  $db->jmlpasien3, 
			  $db->jmlpasien4, 
			  $db->jmlpasien5, 
			  $db->jmlpasien6, 
			  $db->jmlpasien, 
			  angka_rp($db->omset,0)
			  
			  ));
			  $tot1 += $db->jmlpasienp;
			  $tot2 += $db->jmlpasienw;
			  $tot3 += $db->jmlpasien1;
			  $tot4 += $db->jmlpasien2;
			  $tot5 += $db->jmlpasien3;
			  $tot6 += $db->jmlpasien4;
			  $tot7 += $db->jmlpasien5;
			  $tot8 += $db->jmlpasien6;
			  $tot9 += $db->jmlpasien;
			  $tot10 += $db->omset;
			  
			  $nourut++;
			}
			$pdf->SetWidths(array(100,20,20,15,15,15,15,15,15,15,30));
			$pdf->SetAligns(array('C','R','R','R','R','R','R','R','R','R','R','R'));
			
			$pdf->setfont('Arial','B',9);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			
			$pdf->row(array(
			  'TOTAL',
			  $tot1, 
			  $tot2, 
			  $tot3, 
			  $tot4, 
			  $tot5, 
			  $tot6, 
			  $tot7, 
			  $tot8, 
			  $tot9, 
			  angka_rp($tot10,0)));
			
            $pdf->SetTitle('REKAP PRAKTEK DOKTER');
			$pdf->AliasNbPages();
			$pdf->output('MARKETING-04.PDF','I');
		  } else if($idlp==105){	
		    $bulan = date('n',strtotime($tgl1)); 
			$tahun = date('Y',strtotime($tgl2)); 
            $query = 
		    "
			select 'Order Tunai' as keterangan, count(*) as jumlah, sum(bayarcash+bayarcard) as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalresep>0
			union all
			select '   Order Cash' as keterangan, count(*) as jumlah, sum(bayarcash) as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalresep>0
			union all
			select '   Order Card' as keterangan, count(*) as jumlah, sum(bayarcard) as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalresep>0
			union all
			select 'Tindakan Dokter' as keterangan, count(*) as jumlah, sum(totalpoli) as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select '   Order Cash' as keterangan, count(*) as jumlah, sum(bayarcash) as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select '   Order Card' as keterangan, count(*) as jumlah, sum(bayarcard) as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select 'Order Lokal' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select '   Order Cash' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select '   Order Card' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select 'Order Kirim' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select '   Order Cash' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select '   Order Card' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select 'Ongkos Kirim' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select '   Order Cash' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select '   Order Card' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select 'SPA' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select '   Order Cash' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select '   Order Card' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select 'Produk SPA' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select '   Order Cash' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select '   Order Card' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select 'APOTIK' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select '   Order Cash' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			union all
			select '   Order Card' as keterangan, 0 as jumlah, 0 as nilai
			from tbl_kasir where jenisbayar=1
			and koders='$unit' and tglbayar between '$_tgl1' and '$_tgl2' and totalpoli>0
			";
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('REKAP PENJUALAN HARIAN LENGKAP');
			$pdf->setsubjudul($_peri);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(50,50,50));
			$pdf->SetAligns(array('L','L','R'));
			$judul=array('Keterangan','Jumlah','Nilai');
			$pdf->setfont('Arial','B',10);
			$pdf->row($judul);
			
			$pdf->SetWidths(array(50,50,50));
			$pdf->SetAligns(array('L','L','R'));
			$pdf->setfont('Arial','',9);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;
            
			foreach($lap as $db)
			{
			 
			  $pdf->row(array(
			  $db->keterangan, 
			  $db->jumlah.' Transaksi', 
			  angka_rp($db->nilai,0)
			  
			  ));
			  
			  $nourut++;
			}
			
            $pdf->SetTitle('REKAP PENJUALAN HARIAN');
			$pdf->AliasNbPages();
			$pdf->output('KASIR-05.PDF','I');    
          }	else if($idlp==106){	
		    $bulan = date('n',strtotime($tgl1)); 
			$tahun = date('Y',strtotime($tgl2)); 
            $query = 
			"
			select 
			'15-20' as xrange,
			'UMUR 15-20' as keterangan,
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 15 and 20 then 1 end),0) as jmlpasien, 
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 15 and 20 then tbl_kasir.totalsemua end),0) as omset
			from tbl_kasir 
			inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
			where 
				tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2'
			union 
            select 
			'21-25' as xrange,
			'UMUR 21-25' as keterangan,
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 21 and 25 then 1 end),0) as jmlpasien, 
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 21 and 25 then tbl_kasir.totalsemua end),0) as omset
			from tbl_kasir 
			inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
			where 
				tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2'			
			union	
			select 
			'26-30' as xrange,
			'UMUR 26-30' as keterangan,
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 26 and 30 then 1 end),0) as jmlpasien, 
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 26 and 30 then tbl_kasir.totalsemua end),0) as omset
			from tbl_kasir 
			inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
			where 
				tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2'	
			union	
			select 
			'31-35' as xrange,
			'UMUR 31-35' as keterangan,
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 31 and 35 then 1 end),0) as jmlpasien, 
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 31 and 35 then tbl_kasir.totalsemua end),0) as omset
			from tbl_kasir 
			inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
			where 
				tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2'
            union	
			select 
			'36-40' as xrange,
			'UMUR 36-40' as keterangan,
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 36 and 40 then 1 end),0) as jmlpasien, 
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE()) between 46 and 40 then tbl_kasir.totalsemua end),0) as omset
			from tbl_kasir 
			inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
			where 
				tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2'
 			union	
			select 
			'>40' as xrange,
			'UMUR >40' as keterangan,
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE())>40 then 1 end),0) as jmlpasien, 
			coalesce(sum(case when TIMESTAMPDIFF(YEAR, tbl_pasien.tgllahir, CURDATE())>40 then tbl_kasir.totalsemua end),0) as omset
			from tbl_kasir 
			inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
			where 
				tbl_kasir.tglbayar between '$_tgl1' and '$_tgl2'	
		    ";		
             
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('LAPORAN OMSET PER KELOMPOK UMUR');
			$pdf->setsubjudul($_peri);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(50,50,30,50));
			$pdf->SetAligns(array('L','L','R','R'));
			$judul=array('RANGE','KETERANGAN','JM PASIEN','NILAI RUPIAH');
			$pdf->setfont('Arial','B',10);
			$pdf->row($judul);
			
			$pdf->SetWidths(array(50,50,30,50));
			$pdf->SetAligns(array('L','L','R','R'));
			$pdf->setfont('Arial','',9);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;
            $tot1 = $tot2 = 0;
			foreach($lap as $db)
			{
			  $tot1 += $db->jmlpasien;
			  $tot2 += $db->omset;
			  
			  $pdf->row(array(
			  $db->xrange, 
			  $db->keterangan, 
			  angka_rp($db->jmlpasien,0), 
			  angka_rp($db->omset,0), 
			  
			  ));
			  
			  $nourut++;
			}
			$pdf->setfont('Arial','B',9);
			$pdf->SetWidths(array(100,30,50));
			$pdf->SetAligns(array('C','R','R'));
			$pdf->row(array(
			  'TOTAL', 
			  angka_rp($tot1,0), 
			  angka_rp($tot2,0), 
			  
			 
			  
			  ));
            $pdf->SetTitle('LAPORAN OMSET PER KELOMPOK UMUR');
			$pdf->AliasNbPages();
			$pdf->output('KASIR-06.PDF','I');    
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
		  
		  $this->load->view('master/bank/v_master_bank_exp',$d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
	
	
}

/* End of file akuntansi_jurnal.php */
/* Location: ./application/controllers/akuntansi_jurnal.php */