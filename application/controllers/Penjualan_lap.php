<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penjualan_lap extends CI_Controller {

	
	
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('M_global','M_global');
		$this->load->helper('simkeu_rpt');		
		$this->session->set_userdata('menuapp', '400');
		$this->session->set_userdata('submenuapp', '430');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{		
			$unit = $this->session->userdata('unit');				
			$this->load->helper('url');		
			$d['cabang']= $this->db->get_where('ms_unit',array('nama !=' => ''))->result();
            $d['cust']  = $this->db->get_where('ar_customer',array('nama !=' => ''))->result();
			$d['barang']= $this->db->get_where('inv_barang',array('namabarang !=' => ''))->result();
			
			$this->load->view('penjualan/v_penjualan_laporan',$d);
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
		  $cust  = $data[3];
		  $brg   = $data[4];
		  $cab   = $data[5];
		  
		  $_tgl1 = date('Y-m-d',strtotime($tgl1));
		  $_tgl2 = date('Y-m-d',strtotime($tgl2));
		  $_peri = 'Dari '.date('d-m-Y',strtotime($tgl1)).' s/d '.date('d-m-Y',strtotime($tgl2));
		  $_peri1= 'Per Tgl. '.date('d',strtotime($tgl2)).' '.$this->M_global->_namabulan(date('n',strtotime($tgl2))).' '.date('Y',strtotime($tgl2));
		  
		  if($idlp==101){				
            $query = 
            "select ar_sofile.*, ar_customer.nama as namapelanggan from ar_sofile inner join ar_customer on ar_sofile.kodecust=ar_customer.kode 
			 where tglso between '$_tgl1' and '$_tgl2' "; 			
			
            if($cust!=""){
			$query.= " and ar_sofile.kodecust = '$cust'";	
			} 			
			if($cab!=""){
			$query.= " and ar_sofile.kodecbg = '$cab'";	
			} 			
			$query.= " order by tglso, kodeso";
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Daftar Pesanan Penjualan');
			$pdf->setsubjudul($_peri);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(10,30,20,90,35));
			$pdf->SetAligns(array('C','C','C','C','C'));
			$judul=array('No.','Nomor ','Tanggal','Pelanggan', 'Total');
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
			  $pdf->row(array($nourut, $db->kodeso, date('d-m-Y',strtotime($db->tglso)), $db->namapelanggan, number_format($db->totalso,0,'.',',')));
			  $nourut++;
			}
            $pdf->SetTitle('LAPORAN PESANAN PENJUALAN');
			$pdf->AliasNbPages();
			$pdf->output('Penjualan_101.PDF','I');
		  }	else 
		  if($idlp==102){				
            $query = 
            "select ar_kirim.*, ar_customer.nama as namapelanggan from ar_kirim inner join ar_customer on ar_kirim.kodecust=ar_customer.kode 
			 where tglkirim between '$_tgl1' and '$_tgl2' "; 			
			
            if($cust!=""){
			$query.= " and ar_kirim.kodecust = '$cust'";	
			} 			
			if($cab!=""){
			$query.= " and ar_kirim.kodecbg = '$cab'";	
			} 			
			$query.= " order by tglkirim, kodeso";
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Daftar Pengiriman Barang');
			$pdf->setsubjudul($_peri);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(10,30,20,90,35));
			$pdf->SetAligns(array('C','C','C','C','C'));
			$judul=array('No.','Nomor ','Tanggal','Pelanggan', 'Pengiriman');
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
			  $pdf->row(array($nourut, $db->kodekirim, date('d-m-Y',strtotime($db->tglkirim)), $db->namapelanggan, ''));
			  $nourut++;
			}

            $pdf->SetTitle('LAPORAN PENGIRIMAN BARANG');
			$pdf->AliasNbPages();
			$pdf->output('Penjualan_102.PDF','I');
		  }	else 
          if($idlp==103){				
            $query = 
            "select ar_sifile.*, ar_customer.nama as namapelanggan from ar_sifile inner join ar_customer on ar_sifile.kodecust=ar_customer.kode 
			 where tglkirim between '$_tgl1' and '$_tgl2' "; 			
			
            if($cust!=""){
			$query.= " and ar_sifile.kodecust = '$cust'";	
			} 			
			if($cab!=""){
			$query.= " and ar_sifile.kodecbg = '$cab'";	
			} 			
			$query.= " order by tglkirim, kodeso";
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Daftar Faktur Penjualan');
			$pdf->setsubjudul($_peri);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(10,30,20,90,35));
			$pdf->SetAligns(array('C','C','C','C','C'));
			$judul=array('No.','Nomor ','Tanggal','Pelanggan', 'Total');
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
			  $pdf->row(array($nourut, $db->kodesi, 
			  date('d-m-Y',strtotime($db->tglsi)), $db->namapelanggan, number_format($db->totalsi,0,'.',',')));
			  $nourut++;
			}

            $pdf->SetTitle('LAPORAN FAKTUR PENJUALAN');
			$pdf->AliasNbPages();
			$pdf->output('Penjualan_103.PDF','I');
		  }	else 
		  //uang muka penjualan  
		  if($idlp==104){				
            $query = 
            "select ar_uangmuka.*, ar_customer.nama as namapelanggan from ar_uangmuka inner join ar_customer on ar_uangmuka.kodecust=ar_customer.kode 
			 where tglum between '$_tgl1' and '$_tgl2' "; 			
			
            if($cust!=""){
			$query.= " and ar_uangmuka.kodecust = '$cust'";	
			} 			
			if($cab!=""){
			$query.= " and ar_uangmuka.kodecbg = '$cab'";	
			} 			
			$query.= " order by tglum, kodeso";
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Uang Muka Penjualan');
			$pdf->setsubjudul($_peri);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(10,30,20,60,35,30));
			$pdf->SetAligns(array('C','C','C','C','C','C'));
			$judul=array('No.','Nomor ','Tanggal','Pelanggan', 'Total', 'No Pesanan');
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
			  $pdf->row(array($nourut, $db->kodeum, date('d-m-Y',strtotime($db->tglum)), $db->namapelanggan, number_format($db->jumlahum,0,'.',','), $db->kodeso));
			  $nourut++;
			}

            $pdf->SetTitle('LAPORAN UANG MUKA PENJUALAN');
			$pdf->AliasNbPages();
			$pdf->output('penjualan_104.PDF','I');
		  }	else 	  
		  if($idlp==105){				
            $query = 
            "select ar_retur.*, ar_customer.nama as namapelanggan from ar_retur inner join ar_customer on ar_retur.kodecust=ar_customer.kode 
			 where tglretur between '$_tgl1' and '$_tgl2' "; 			
			
            if($cust!=""){
			$query.= " and ar_retur.kodecust = '$cust'";	
			} 			
			if($cab!=""){
			$query.= " and ar_retur.kodecbg = '$cab'";	
			} 			
			$query.= " order by tglretur, koderetur";
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Daftar Retur Penjualan');
			$pdf->setsubjudul($_peri);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(10,30,20,90,35));
			$pdf->SetAligns(array('C','C','C','C','C'));
			$judul=array('No.','Nomor ','Tanggal','Pelanggan', 'Total');
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
			  $pdf->row(array($nourut, $db->koderetur, date('d-m-Y',strtotime($db->tglretur)), $db->namapelanggan, number_format($db->totalretur,0,'.',',')));
			  $nourut++;
			}

            $pdf->SetTitle('LAPORAN RETUR PENJUALAN');
			$pdf->AliasNbPages();
			$pdf->output('penjualan_105.PDF','I');
		  }	else 	  
		  //pembelian per pelanggan	  
          if($idlp==106){				
            $query = 
            "select ar_customer.nama, sum(ar_sifile.totalsi) as jumlah from ar_sifile inner join ar_customer on ar_sifile.kodecust=ar_customer.kode 
			 where tglsi between '$_tgl1' and '$_tgl2' "; 			
			
            if($cust!=""){
			$query.= " and ar_sifile.kodecust = '$cust'";	
			} 			
			if($cab!=""){
			$query.= " and ar_sifile.kodecbg = '$cab'";	
			} 			
			$query.= " order by tglsi, kodeso";
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Penjualan per Pelanggan');
			$pdf->setsubjudul($_peri);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(10,100,60));
			$pdf->SetAligns(array('C','C','C'));
			$judul=array('No.','Nama Pelanggan','Penjualan');
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

            $pdf->SetTitle('LAPORAN PENJUALAN PER PELANGGAN');
			$pdf->AliasNbPages();
			$pdf->output('penjualan_106.PDF','I');
		  }	else 
		  //penjualan per barang
          if($idlp==107){				
            $query = 
            "select inv_barang.namabarang, sum(ar_sidetail.qtysi) as qty, sum(ar_sifile.totalsi) as total 
			 from ar_sifile inner join ar_sidetail on ar_sifile.kodesi=ar_sidetail.kodesi inner join
			 inv_barang on ar_sidetail.kodeitem=inv_barang.kodeitem
			 where ar_sifile.tglsi between '$_tgl1' and '$_tgl2' "; 			
			
            if($cust!=""){
			$query.= " and ar_sifile.kodecust = '$cust'";	
			} 			
			if($cab!=""){
			$query.= " and ar_sifile.kodecbg = '$cab'";	
			} 		
            
            if($brg!=""){
			$query.= " and ar_sidetail.kodeitem = '$brg'";	
			} 
			
			$query.= " order by tglsi, ar_sifile.kodesi";
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Penjualan per Barang');
			$pdf->setsubjudul($_peri);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(10,100,20,60));
			$pdf->SetAligns(array('C','C','C','C'));
			$judul=array('No.','Nama Barang','Kuantitas','Penjualan');
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

            $pdf->SetTitle('LAPORAN PENJUALAN PER BARANG');
			$pdf->AliasNbPages();
			$pdf->output('penjualan_107.PDF','I');
		  }		
          else 
		  //Faktur belum lunas
          if($idlp==201){				
            $query = 
            "select ar_sifile.*, ar_customer.nama as namapelanggan from ar_sifile inner join ar_customer on ar_sifile.kodecust=ar_customer.kode 
			 where tglsi between '$_tgl1' and '$_tgl2' and totalsi>jumlahbayar"; 				
			
            if($cust!=""){
			$query.= " and ar_sifile.kodecust = '$cust'";	
			} 			
			if($cab!=""){
			$query.= " and ar_sifile.kodecbg = '$cab'";	
			} 		
            
           
			
			$query.= " order by tglsi, kodesi";
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Faktur Belum Lunas');
			$pdf->setsubjudul($_peri1);
			$pdf->addpage("L","A4");   
			$pdf->setsize("L","A4");
			$pdf->SetWidths(array(10,60,25,20,20,25,25,25,20));
			$pdf->SetAligns(array('C','C','C','C','C','C','C','C','C'));
			$judul=array('No.','Pemasok','Nomor','Tanggal','Jatuh Tempo','Total','Piutang','Piutang Pajak','Umur (hr)');
			$pdf->setfont('Times','B',10);
			$pdf->row($judul);
			$pdf->SetWidths(array(10,60,25,20,20,25,25,25,20));
			$pdf->SetAligns(array('C','L','C','C','C','R','R','R','R'));
			$pdf->setfont('Times','',9);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;

			foreach($lap as $db)
			{
			  $piutang    = $db->totalsi-$db->jumlahbayar;	
			  $piutangpjk = $db->ppn-$db->ppnbayar;	
			  //$umur      = date('Y-m-d',strtotime($tgl2))-date('Y-m-d',strtotime($db->tglsi));
			  $date1 = strtotime($tgl2);
			  $date2 = strtotime($db->tglsi);
			  $umur  = ($date1-$date2)/(60*60*24);

			  //$umur = 0;
			  $pdf->row(array($nourut, $db->namapelanggan, 
			  $db->kodesi,
			  date('d-m-Y',strtotime($db->tglsi)),
			  date('d-m-Y',strtotime($db->tgljthtempo)),
			  number_format($db->totalsi,0,'.',','),
			  number_format($piutang,0,'.',','),
			  number_format($piutangpjk,0,'.',','),
			  $umur));
			  $nourut++;
			}

			$pdf->SetTitle('FAKTUR BELUM LUNAS');
			$pdf->AliasNbPages();
			$pdf->output('penjualan_201.PDF','I');
		  }				  
          
		  else 
		  //Umur Piutang
          if($idlp==203){				
            $query = "select * from ar_customer order by nama";				
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Umur Piutang');
			$pdf->setsubjudul($_peri1);
			$pdf->addpage("L","A4");   
			$pdf->setsize("L","A4");
			$pdf->SetWidths(array(10,60,25,25,25,25,25,25,25));
			$pdf->SetAligns(array('C','C','C','C','C','C','C','C','C'));
			$judul=array('No.','Pelanggan','Piutang','Blm Tempo','1 - 30','31 - 60','61- 90','91 - 120','> 120');
			$pdf->setfont('Times','B',10);
			$pdf->row($judul);
			$pdf->SetWidths(array(10,60,25,25,25,25,25,25,25));
			$pdf->SetAligns(array('C','L','R','R','R','R','R','R','R'));
			$pdf->setfont('Times','',9);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;

			$totpiutang = 0;
			$totbelum  = 0;
			$tottempo1 = 0;
			$tottempo2 = 0;
			$tottempo3 = 0;
			$tottempo4 = 0;
			$tottempo5 = 0;
			foreach($lap as $db)
			{
			  $cust = $db->kode;
			  
              $query ="select sum(totalsi-jumlahbayar) as total from ar_sifile
				       where totalsi>jumlahbayar and kodecust = '$cust'";
			  $data = $this->db->query($query)->row();		   
			  $piutang  = $data->total;
			  
			  $query ="select sum(totalsi-jumlahbayar) as total from ar_sifile
				       where totalsi>jumlahbayar and datediff('$_tgl2',tglsi) < 1 and kodecust = '$cust'";
			  $data = $this->db->query($query)->row();		   
			  $blmtempo  = $data->total;
			  
			  $query ="select sum(totalsi-jumlahbayar) as total from ar_sifile
				       where totalsi>jumlahbayar and datediff('$_tgl2',tglsi) between 1 and 30 and kodecust = '$cust'";
			  $data = $this->db->query($query)->row();		   
			  $tempo1  = $data->total;
			  
			  $query ="select sum(totalsi-jumlahbayar) as total from ar_sifile
				       where totalsi>jumlahbayar and datediff('$_tgl2',tglsi) between 31 and 60 and kodecust = '$cust'";
			  $data = $this->db->query($query)->row();		   
			  $tempo2  = $data->total;
			  
			  $query ="select sum(totalsi-jumlahbayar) as total from ar_sifile
				       where totalsi>jumlahbayar and datediff('$_tgl2',tglsi) between 61 and 90 and kodecust = '$cust'";
			  $data = $this->db->query($query)->row();		   
			  $tempo3  = $data->total;
			  
			  $query ="select sum(totalsi-jumlahbayar) as total from ar_sifile
				       where totalsi>jumlahbayar and datediff('$_tgl2',tglsi) between 91 and 120 and kodecust = '$cust'";
			  $data = $this->db->query($query)->row();		   
			  $tempo4  = $data->total;
			  
			  $query ="select sum(totalsi-jumlahbayar) as total from ar_sifile
				       where totalsi>jumlahbayar and datediff('$_tgl2',tglsi) > 120 and kodecust = '$cust'";
			  $data = $this->db->query($query)->row();		   
			  $tempo5  = $data->total;
			  
			  
			  $totpiutang = $totpiutang + $piutang;
			  $totbelum  = $totbelum + $blmtempo;
			  $tottempo1 = $tottempo1 + $tempo1;
			  $tottempo2 = $tottempo2 + $tempo2;
			  $tottempo3 = $tottempo3 + $tempo3;
			  $tottempo4 = $tottempo4 + $tempo4;
			  $tottempo5 = $tottempo5 + $tempo5;
			  
			  //$umur = 0;
			  $pdf->row(array($nourut, $db->nama, 
			  number_format($piutang,0,'.',','),
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
			  number_format($totpiutang,0,'.',','),
			  number_format($totbelum,0,'.',','),
			  number_format($tottempo1,0,'.',','),
			  number_format($tottempo2,0,'.',','),
			  number_format($tottempo3,0,'.',','),
			  number_format($tottempo4,0,'.',','),
			  number_format($tottempo5,0,'.',',')));


			$pdf->AliasNbPages();
			$pdf->SetTitle('UMUR PIUTANG');
			$pdf->output('penjualan_203.PDF','I');
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