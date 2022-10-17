<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_lap extends CI_Controller {

	
	
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('M_global','M_global');
		$this->load->helper('simkeu_rpt');		
		$this->session->set_userdata('menuapp', '300');
		$this->session->set_userdata('submenuapp', '330');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{		
			$unit = $this->session->userdata('unit');				
			$this->load->helper('url');		
			$d['cabang']= $this->db->get_where('ms_unit',array('nama !=' => ''))->result();
            $d['supp']  = $this->db->get_where('ap_supplier',array('nama !=' => ''))->result();
			$d['barang']= $this->db->get_where('inv_barang',array('namabarang !=' => ''))->result();
			
			$this->load->view('pembelian/v_pembelian_laporan',$d);
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
		  $motto = '';
		  $alamat= '';
		  $namaunit = $this->M_global->_namaunit($unit);
		  $data  = explode("~",$x);
		  $idlp  = $data[0];
		  $tgl1  = $data[1];
		  $tgl2  = $data[2];
		  $supp  = $data[3];
		  $brg   = $data[4];
		  $cab   = $data[5];
		  
		  $_tgl1 = date('Y-m-d',strtotime($tgl1));
		  $_tgl2 = date('Y-m-d',strtotime($tgl2));
		  $_peri = 'Dari '.date('d-m-Y',strtotime($tgl1)).' s/d '.date('d-m-Y',strtotime($tgl2));
		  $_peri1= 'Per Tgl. '.date('d',strtotime($tgl2)).' '.$this->M_global->_namabulan(date('n',strtotime($tgl2))).' '.date('Y',strtotime($tgl2));
		  
		  if($idlp==101){				
            $query = 
            "select ap_pofile.*, ap_supplier.nama as namapemasok from ap_pofile inner join ap_supplier on ap_pofile.kodesup=ap_supplier.kode 
			 where tglpo between '$_tgl1' and '$_tgl2' "; 			
			
            if($supp!=""){
			$query.= "and ap_pofile.kodesup = '$supp'";	
			} 			
			if($cab!=""){
			$query.= "and ap_pofile.kodecbg = '$cab'";	
			} 			
			$query.= "order by tglpo, kodepo";
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Daftar Pesanan Pembelian');
			$pdf->setsubjudul($_peri);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(10,30,20,90,35));
			$pdf->SetAligns(array('C','C','C','C','C'));
			$judul=array('No.','Nomor ','Tanggal','Pemasok', 'Total');
			$pdf->setfont('Times','B',10);
			$pdf->row($judul);
			$pdf->SetWidths(array(10,30,20,90,35));
			$pdf->SetAligns(array('C','C','C','L','R'));
			$pdf->setfont('Times','',9);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;

			foreach($lap as $db)
			{
			  $pdf->row(array($nourut, $db->kodepo, date('d-m-Y',strtotime($db->tglpo)), $db->namapemasok, number_format($db->totalpo,0,'.',',')));
			  $nourut++;
			}

			$pdf->SetTitle('LAPORAN PESANAN PEMBELIAN');
			$pdf->AliasNbPages();
			$pdf->output('pembelian_101.PDF','I');
		  }	else 
		  if($idlp==102){				
            $query = 
            "select ap_lpb.*, ap_supplier.nama as namapemasok from ap_lpb inner join ap_supplier on ap_lpb.kodesup=ap_supplier.kode 
			 where tgllpb between '$_tgl1' and '$_tgl2' "; 			
			
            if($supp!=""){
			$query.= "and ap_lpb.kodesup = '$supp'";	
			} 			
			if($cab!=""){
			$query.= "and ap_lpb.kodecbg = '$cab'";	
			} 			
			$query.= "order by tgllpb, kodepo";
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Daftar Penerimaan Barang');
			$pdf->setsubjudul($_peri);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(10,30,20,90,35));
			$pdf->SetAligns(array('C','C','C','C','C'));
			$judul=array('No.','Nomor ','Tanggal','Pemasok', 'Pengiriman');
			$pdf->setfont('Times','B',10);
			$pdf->row($judul);
			$pdf->SetWidths(array(10,30,20,90,35));
			$pdf->SetAligns(array('C','C','C','L','R'));
			$pdf->setfont('Times','',9);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;

			foreach($lap as $db)
			{
			  $pdf->row(array($nourut, $db->nolpb, date('d-m-Y',strtotime($db->tgllpb)), $db->namapemasok, ''));
			  $nourut++;
			}

            $pdf->SetTitle('LAPORAN PENERIMAAN BARANG');
			$pdf->AliasNbPages();
			$pdf->output('pembelian_102.PDF','I');
		  }	else 
          if($idlp==103){				
            $query = 
            "select ap_pufile.*, ap_supplier.nama as namapemasok from ap_pufile inner join ap_supplier on ap_pufile.kodesup=ap_supplier.kode 
			 where tglpu between '$_tgl1' and '$_tgl2' "; 			
			
            if($supp!=""){
			$query.= "and ap_pufile.kodesup = '$supp'";	
			} 			
			if($cab!=""){
			$query.= "and ap_pufile.kodecbg = '$cab'";	
			} 			
			$query.= "order by tglpu, kodepo";
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Daftar Faktur Pembelian');
			$pdf->setsubjudul($_peri);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(10,30,20,90,35));
			$pdf->SetAligns(array('C','C','C','C','C'));
			$judul=array('No.','Nomor ','Tanggal','Pemasok', 'Total');
			$pdf->setfont('Times','B',10);
			$pdf->row($judul);
			$pdf->SetWidths(array(10,30,20,90,35));
			$pdf->SetAligns(array('C','C','C','L','R'));
			$pdf->setfont('Times','',9);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;

			foreach($lap as $db)
			{
			  $pdf->row(array($nourut, $db->kodepu, date('d-m-Y',strtotime($db->tglpu)), $db->namapemasok, number_format($db->totalpu,0,'.',',')));
			  $nourut++;
			}

            $pdf->SetTitle('LAPORAN FAKTUR PEMBELIAN');
			$pdf->AliasNbPages();
			$pdf->output('pembelian_103.PDF','I');
		  }	else 
		  //uang muka pembelian	  
		  if($idlp==104){				
            $query = 
            "select ap_uangmuka.*, ap_supplier.nama as namapemasok from ap_uangmuka inner join ap_supplier on ap_uangmuka.kodesup=ap_supplier.kode 
			 where tglum between '$_tgl1' and '$_tgl2' "; 			
			
            if($supp!=""){
			$query.= "and ap_uangmuka.kodesup = '$supp'";	
			} 			
			if($cab!=""){
			$query.= "and ap_uangmuka.kodecbg = '$cab'";	
			} 			
			$query.= "order by tglum, kodepo";
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Uang Muka Pembelian');
			$pdf->setsubjudul($_peri);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(10,30,20,60,35,30));
			$pdf->SetAligns(array('C','C','C','C','C','C'));
			$judul=array('No.','Nomor ','Tanggal','Pemasok', 'Total', 'No Pesanan');
			$pdf->setfont('Times','B',10);
			$pdf->row($judul);
			$pdf->SetWidths(array(10,30,20,60,35,30));
			$pdf->SetAligns(array('C','C','C','L','R','C'));
			$pdf->setfont('Times','',9);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;

			foreach($lap as $db)
			{
			  $pdf->row(array($nourut, $db->kodeum, date('d-m-Y',strtotime($db->tglum)), $db->namapemasok, number_format($db->jumlahum,0,'.',','), $db->kodepo));
			  $nourut++;
			}

			$pdf->SetTitle('LAPORAN UANG MUKA PEMBELIAN');
			$pdf->AliasNbPages();
			$pdf->output('pembelian_104.PDF','I');
		  }	else 	  
		  if($idlp==105){				
            $query = 
            "select ap_retur.*, ap_supplier.nama as namapemasok from ap_retur inner join ap_supplier on ap_retur.kodesup=ap_supplier.kode 
			 where tglretur between '$_tgl1' and '$_tgl2' "; 			
			
            if($supp!=""){
			$query.= "and ap_retur.kodesup = '$supp'";	
			} 			
			if($cab!=""){
			$query.= "and ap_retur.kodecbg = '$cab'";	
			} 			
			$query.= "order by tglretur, koderetur";
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Daftar Retur Pembelian');
			$pdf->setsubjudul($_peri);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(10,30,20,90,35));
			$pdf->SetAligns(array('C','C','C','C','C'));
			$judul=array('No.','Nomor ','Tanggal','Pemasok', 'Total');
			$pdf->setfont('Times','B',10);
			$pdf->row($judul);
			$pdf->SetWidths(array(10,30,20,90,35));
			$pdf->SetAligns(array('C','C','C','L','R'));
			$pdf->setfont('Times','',9);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;

			foreach($lap as $db)
			{
			  $pdf->row(array($nourut, $db->koderetur, date('d-m-Y',strtotime($db->tglretur)), $db->namapemasok, number_format($db->totalretur,0,'.',',')));
			  $nourut++;
			}

			$pdf->SetTitle('LAPORAN RETUR PEMBELIAN');
			$pdf->AliasNbPages();
			$pdf->output('pembelian_103.PDF','I');
		  }	else 	  
		  //pembelian per pemasok	  
          if($idlp==106){				
            $query = 
            "select ap_supplier.nama, sum(ap_pufile.totalpu) as jumlah from ap_pufile inner join ap_supplier on ap_pufile.kodesup=ap_supplier.kode 
			 where tglpu between '$_tgl1' and '$_tgl2' "; 			
			
            if($supp!=""){
			$query.= "and ap_pufile.kodesup = '$supp'";	
			} 			
			if($cab!=""){
			$query.= "and ap_pufile.kodecbg = '$cab'";	
			} 			
			$query.= "order by tglpu, kodepo";
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Pembelian per Pemasok');
			$pdf->setsubjudul($_peri);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(10,100,60));
			$pdf->SetAligns(array('C','C','C'));
			$judul=array('No.','Nama Pemasok','Pembelian');
			$pdf->setfont('Times','B',10);
			$pdf->row($judul);
			$pdf->SetWidths(array(10,100,60));
			$pdf->SetAligns(array('C','L','R'));
			$pdf->setfont('Times','',9);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;

			foreach($lap as $db)
			{
			  $pdf->row(array($nourut, $db->nama, number_format($db->jumlah,0,'.',',')));
			  $nourut++;
			}

			$pdf->SetTitle('LAPORAN PEMBELIAN PER PEMASOK');
			$pdf->AliasNbPages();
			$pdf->output('pembelian_106.PDF','I');
		  }	else 
		  //pembelian per barang
          if($idlp==107){				
            $query = 
            "select inv_barang.namabarang, sum(ap_pudetail.qtypu) as qty, sum(ap_pufile.totalpu) as total 
			 from ap_pufile inner join ap_pudetail on ap_pufile.kodepu=ap_pudetail.kodepu inner join
			 inv_barang on ap_pudetail.kodeitem=inv_barang.kodeitem
			 where ap_pufile.tglpu between '$_tgl1' and '$_tgl2' "; 			
			
            if($supp!=""){
			$query.= "and ap_pufile.kodesup = '$supp'";	
			} 			
			if($cab!=""){
			$query.= "and ap_pufile.kodecbg = '$cab'";	
			} 		
            
            if($brg!=""){
			$query.= "and ap_pudetail.kodeitem = '$brg'";	
			} 
			
			$query.= "order by tglpu, kodepo";
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Pembelian per Barang');
			$pdf->setsubjudul($_peri);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(10,100,20,60));
			$pdf->SetAligns(array('C','C','C','C'));
			$judul=array('No.','Nama Barang','Kuantitas','Pembelian');
			$pdf->setfont('Times','B',10);
			$pdf->row($judul);
			$pdf->SetWidths(array(10,100,20,60));
			$pdf->SetAligns(array('C','L','R','R'));
			$pdf->setfont('Times','',9);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;

			foreach($lap as $db)
			{
			  $pdf->row(array($nourut, $db->namabarang, $db->qty, number_format($db->total,0,'.',',')));
			  $nourut++;
			}

			$pdf->SetTitle('LAPORAN PEMBELIAN PER BARANG');
			$pdf->AliasNbPages();
			$pdf->output('pembelian_107.PDF','I');
		  }		
          else 
		  //Faktur belum lunas
          if($idlp==201){				
            $query = 
            "select ap_pufile.*, ap_supplier.nama as namapemasok from ap_pufile inner join ap_supplier on ap_pufile.kodesup=ap_supplier.kode 
			 where tglpu between '$_tgl1' and '$_tgl2' and totalpu>jumlahbayar "; 				
			
            if($supp!=""){
			$query.= " and ap_pufile.kodesup = '$supp'";	
			} 			
			if($cab!=""){
			$query.= " and ap_pufile.kodecbg = '$cab'";	
			} 		
            
           
			
			$query.= " order by tglpu, kodepo";
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Faktur Belum Lunas');
			$pdf->setsubjudul($_peri1);
			$pdf->addpage("L","A4");   
			$pdf->setsize("L","A4");
			$pdf->SetWidths(array(10,60,35,20,20,25,25,25,20));
			$pdf->SetAligns(array('C','C','C','C','C','C','C','C','C'));
			$judul=array('No.','Pemasok','Nomor','Tanggal','Jatuh Tempo','Total','Hutang','Hutang Pajak','Umur (hr)');
			$pdf->setfont('Times','B',10);
			$pdf->row($judul);
			$pdf->SetWidths(array(10,60,35,20,20,25,25,25,20));
			$pdf->SetAligns(array('C','L','C','C','C','R','R','R','R'));
			$pdf->setfont('Times','',9);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;

			foreach($lap as $db)
			{
			  $hutang    = $db->totalpu-$db->jumlahbayar;	
			  $hutangpjk = $db->ppn-$db->ppnbayar;	
			  //$umur      = date('Y-m-d',strtotime($tgl2))-date('Y-m-d',strtotime($db->tglpu));
			  $date1 = strtotime($tgl2);
			  $date2 = strtotime($db->tglpu);
			  $umur  = ($date1-$date2)/(60*60*24);

			  //$umur = 0;
			  $pdf->row(array($nourut, $db->namapemasok, 
			  $db->kodepu,
			  date('d-m-Y',strtotime($db->tglpu)),
			  date('d-m-Y',strtotime($db->tgljthtempo)),
			  number_format($db->totalpu,0,'.',','),
			  number_format($hutang,0,'.',','),
			  number_format($hutangpjk,0,'.',','),
			  $umur));
			  $nourut++;
			}

			$pdf->SetTitle('LAPORAN FAKTUR BELUM LUNAS');
			$pdf->AliasNbPages();
			$pdf->output('pembelian_201.PDF','I');
		  }				  
          
		  else 
		  //Umur Hutang
          if($idlp==203){				
            $query = "select * from ap_supplier order by nama";				
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Umur Hutang');
			$pdf->setsubjudul($_peri1);
			$pdf->addpage("L","A4");   
			$pdf->setsize("L","A4");
			$pdf->SetWidths(array(10,60,25,25,25,25,25,25,25));
			$pdf->SetAligns(array('C','C','C','C','C','C','C','C','C'));
			$judul=array('No.','Pemasok','Hutang','Blm Tempo','1 - 30','31 - 60','61- 90','91 - 120','> 120');
			$pdf->setfont('Times','B',10);
			$pdf->row($judul);
			$pdf->SetWidths(array(10,60,25,25,25,25,25,25,25));
			$pdf->SetAligns(array('C','L','R','R','R','R','R','R','R'));
			$pdf->setfont('Times','',9);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;

			$tothutang = 0;
			$totbelum  = 0;
			$tottempo1 = 0;
			$tottempo2 = 0;
			$tottempo3 = 0;
			$tottempo4 = 0;
			$tottempo5 = 0;
			foreach($lap as $db)
			{
			  $supp = $db->kode;
			  
              $query ="select sum(totalpu-jumlahbayar) as total from ap_pufile
				       where totalpu>jumlahbayar and kodesup = '$supp'";
			  $data = $this->db->query($query)->row();		   
			  $hutang  = $data->total;
			  
			  $query ="select sum(totalpu-jumlahbayar) as total from ap_pufile
				       where totalpu>jumlahbayar and datediff('$_tgl2',tglpu) < 1 and kodesup = '$supp'";
			  $data = $this->db->query($query)->row();		   
			  $blmtempo  = $data->total;
			  
			  $query ="select sum(totalpu-jumlahbayar) as total from ap_pufile
				       where totalpu>jumlahbayar and datediff('$_tgl2',tglpu) between 1 and 30 and kodesup = '$supp'";
			  $data = $this->db->query($query)->row();		   
			  $tempo1  = $data->total;
			  
			  $query ="select sum(totalpu-jumlahbayar) as total from ap_pufile
				       where totalpu>jumlahbayar and datediff('$_tgl2',tglpu) between 31 and 60 and kodesup = '$supp'";
			  $data = $this->db->query($query)->row();		   
			  $tempo2  = $data->total;
			  
			  $query ="select sum(totalpu-jumlahbayar) as total from ap_pufile
				       where totalpu>jumlahbayar and datediff('$_tgl2',tglpu) between 61 and 90 and kodesup = '$supp'";
			  $data = $this->db->query($query)->row();		   
			  $tempo3  = $data->total;
			  
			  $query ="select sum(totalpu-jumlahbayar) as total from ap_pufile
				       where totalpu>jumlahbayar and datediff('$_tgl2',tglpu) between 91 and 120 and kodesup = '$supp'";
			  $data = $this->db->query($query)->row();		   
			  $tempo4  = $data->total;
			  
			  $query ="select sum(totalpu-jumlahbayar) as total from ap_pufile
				       where totalpu>jumlahbayar and datediff('$_tgl2',tglpu) > 120 and kodesup = '$supp'";
			  $data = $this->db->query($query)->row();		   
			  $tempo5  = $data->total;
			  
			  
			  $tothutang = $tothutang + $hutang;
			  $totbelum  = $totbelum + $blmtempo;
			  $tottempo1 = $tottempo1 + $tempo1;
			  $tottempo2 = $tottempo2 + $tempo2;
			  $tottempo3 = $tottempo3 + $tempo3;
			  $tottempo4 = $tottempo4 + $tempo4;
			  $tottempo5 = $tottempo5 + $tempo5;
			  
			  //$umur = 0;
			  $pdf->row(array($nourut, $db->nama, 
			  number_format($hutang,0,'.',','),
			  number_format($blmtempo,0,'.',','),
			  number_format($tempo1,0,'.',','),
			  number_format($tempo2,0,'.',','),
			  number_format($tempo3,0,'.',','),
			  number_format($tempo4,0,'.',','),
			  number_format($tempo5,0,'.',',')));
			  $nourut++;
			}
			$pdf->SetWidths(array(70,25,25,25,25,25,25,25));
			$pdf->SetAligns(array('C','R','R','R','R','R','R','R'));
			$pdf->setfont('Times','B',9);
			$pdf->row(array('TOTAL', 
			  number_format($tothutang,0,'.',','),
			  number_format($totbelum,0,'.',','),
			  number_format($tottempo1,0,'.',','),
			  number_format($tottempo2,0,'.',','),
			  number_format($tottempo3,0,'.',','),
			  number_format($tottempo4,0,'.',','),
			  number_format($tottempo5,0,'.',',')));

			$pdf->SetTitle('LAPORAN UMUR HUTANG');
			$pdf->AliasNbPages();
			$pdf->output('pembelian_203.PDF','I');
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