<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_lap extends CI_Controller {

	
	
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('M_global','M_global');
		$this->load->helper('simkeu_rpt');		
		$this->session->set_userdata('menuapp', '500');
		$this->session->set_userdata('submenuapp', '530');
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
			$d['kateg']= $this->db->get_where('inv_kategori',array('nama !=' => ''))->result();
			
			$this->load->view('inventory/v_inventory_laporan',$d);
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
		  $kat   = $data[3];
		  $brg   = $data[4];
		  $cab   = $data[5];
		  $gud   = $data[6];
		  
		  $_tgl_awal = date('Y-m-d',strtotime($tgl1)-1);
		  $_tgl1 = date('Y-m-d',strtotime($tgl1));
		  $_tgl2 = date('Y-m-d',strtotime($tgl2));
		  $_peri = 'Dari '.date('d-m-Y',strtotime($tgl1)).' s/d '.date('d-m-Y',strtotime($tgl2));
		  $_peri1= 'Per Tgl. '.date('d',strtotime($tgl2)).' '.$this->M_global->_namabulan(date('n',strtotime($tgl2))).' '.date('Y',strtotime($tgl2));
		  
		  if($idlp==101){	
            if($gud==""){ 		  
            $query = 
            "select * from inv_barang where kodeitem is not null"; 			
			
            if($kat!=""){
			$query.= " and kdkategori = '$kat'";	
			} 			
			if($cab!=""){
			$query.= " and kdcbg = '$cab'";	
			} 			
			$query.= " order by kodeitem";
			} else {
			  
              $query = 
				"select inv_barang.kodeitem, inv_barang.namabarang, inv_barang.satuan, inv_barang.hargabeli,
				inv_barang_gudang.qty as stok, inv_barang_harga.hargajual_net as hargajual,
				inv_barang_harga.kode_customer
				from inv_barang inner join inv_barang_gudang on
				inv_barang.kodeitem=inv_barang_gudang.kodeitem inner join
				inv_barang_harga on inv_barang.kodeitem=inv_barang_harga.kodeitem
				where inv_barang_gudang.gudang= '$gud'"; 			
				
				if($kat!=""){
				$query.= " and inv_barang.kdkategori = '$kat'";	
				} 			
				if($cab!=""){
				$query.= " and inv_barang.kdcbg = '$cab'";	
				} 			
				$query.= " order by inv_barang.kodeitem";			  
			}
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Daftar Barang');
			$pdf->setsubjudul($_peri1);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(10,20,50,20,20,25,25,25,25));
			$pdf->SetAligns(array('C','C','C','C','C','C','C','C','C'));
			$judul=array('No.','Kode ','Nama Barang','Kuantitas', 'Satuan','Harga Jual','Harga Beli','Customer');
			$pdf->setfont('Times','B',10);
			$pdf->row($judul);
			$pdf->SetWidths(array(10,20,50,20,20,25,25,25,25));
			$pdf->SetAligns(array('C','L','L','R','L','R','R','C','C'));
			$pdf->setfont('Times','',9);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;

			foreach($lap as $db)
			{
			  $pdf->row(array($nourut, $db->kodeitem, $db->namabarang, $db->stok, $db->satuan, number_format($db->hargajual,0,'.',','),
			  number_format($db->hargabeli,0,'.',','),
			  $db->kode_customer));
			  $nourut++;
			}

            $pdf->SetTitle('DAFTAR BARANG');
			$pdf->AliasNbPages();
			$pdf->output('INV_101.PDF','I');
		  }	 else 
          if($idlp==102){				
            $query = 
            "select * from inv_transaksi where kodeitem = '$brg' and tanggal between '$_tgl1' and '$_tgl2' "; 			
			    
            if($gud!=""){
				$query.= " and gudang = '$gud'";
			} 				
			$query.= " order by tanggal"; 			
						 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Kartu Stok Barang');
			$pdf->setsubjudul($_peri);
			$pdf->addpage("L","A4");   
			$pdf->setsize("L","A4");
			$pdf->SetWidths(array(30,5,100));
			$pdf->SetAligns(array('C','C','C'));
			$pdf->setfont('Times','B',10);
			$border = array('','','');
			$pdf->FancyRow(array('Kode Barang ',':',$brg), $border);
			$pdf->FancyRow(array('Nama Barang ',':',$this->M_global->_namabarang($brg)), $border);
			if($gud!=""){
			  $pdf->FancyRow(array('Gudang ',':',$this->M_global->_namagudang($gud)), $border);	
			}
			$pdf->ln(2);
			$pdf->SetWidths(array(10,20,26,12,15,25,30,15,25,30,15,25,30));
			$pdf->SetAligns(array('C','C','C','C','C','C','C','C','C','C','C','C','C'));
			$judul=array('No.','Tanggal ','Nomor','Unit','Masuk','Harga','Nilai','Keluar','Harga','Nilai','Saldo','Harga','Nilai');
			$pdf->setfont('Times','B',10);
			$pdf->row($judul);
			$pdf->SetWidths(array(10,20,26,12,15,25,30,15,25,30,15,25,30));
			$pdf->SetAligns(array('C','C','C','C','R','R','R','R','R','R','R','R','R'));
			$pdf->setfont('Times','',9);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;
			
			//hitung saldo awal
			
			if($gud!=""){
			  $vgud = " and gudang = '$gud'";	
			} else {
			  $vgud = "";	
			}
			
			$so = $this->db->query("select max(tgl) as tanggal from inv_stockopname where tgl < '$_tgl1'")->row();
			if($so->tanggal !=''){
			  $tawal = 	$so->tanggal;
			  $so = $this->db->query("select sum(qty_opname) as qty_opname ,sum(qty_opname*hpp) as nilaisaldo from inv_stockopname where tgl='$tawal' and kodeitem = '$brg' $vgud")->row();
			  if($so){
			  $saldo = $so->qty_opname;
              //$harga = $so->hpp;
              $nilaisaldo =  $so->nilaisaldo;
			  } else {
			  $saldo = 0;
			  $harga = 0;
			  $nilaisaldo = 0;	  
			  }
			} else {
			  $tawal =  $_tgl_awal;	
			  $saldo = 0;
			  $harga = 0;
			  $nilaisaldo = 0;
			}
			
			
			$qmutasi = "select * from inv_transaksi where kodeitem = '$brg' and tanggal >= '$tawal' and tanggal < '$_tgl1' $vgud "; 			
			$lapmutasi = $this->db->query($qmutasi)->result();            
			foreach($lapmutasi as $db)
			{
			  $jenis = $db->jenis;
			  $nobukti = $db->nobukti;

              if($jenis=='Pembelian'){
				  $datapembelian = $this->db->query("select * from ap_pufile inner join ap_pudetail on 
				  ap_pufile.kodepu=ap_pudetail.kodepu where ap_pufile.kodepb = '$nobukti' and 
				  ap_pudetail.kodeitem='$brg'
				  ")->row();
				  if($datapembelian){
					 $hargabeli = $datapembelian->hargabeli; 
				  } else {
					 $hargabeli = 0; 
				  }
				  
			  }              
			  
			  $harga = $db->hpp;				  
			  if($db->penerimaan>0){
				 $hargamasuk= $hargabeli;
				 $nilaimasuk= $harga * $db->penerimaan;
				 $hargakeluar= 0;				 
				 $hargaakhir = ($nilaisaldo+$nilaimasuk)/($saldo+$db->penerimaan);
			  };
			  
			  if($db->pengeluaran>0){
				 $hargamasuk= 0;
				 $hargakeluar= $hargaakhir;
				 $hargaakhir = $hargaakhir;
			  };
			  
			  $saldo = $saldo + $db->penerimaan - $db->pengeluaran;	
			  $nilaisaldo = $saldo * $hargaakhir;
			  
			 
			}
			
			
			
			
			$pdf->row(array($nourut, date('d-m-Y',strtotime($_tgl1)), 
			  'SALDO AWAL','',
			  '',
			  '',
			  '',
			  '',
			  '',
			  '',
			  number_format($saldo,0,'.',','),
			  number_format($harga,0,'.',','),
			  number_format($nilaisaldo,0,'.',',')));
			  
			$hargaakhir = $harga;  
            foreach($lap as $db)
			{
			  $jenis = $db->jenis;
			  $nobukti = $db->nobukti;

              if($jenis=='Pembelian'){
				  $datapembelian = $this->db->query("select * from ap_pufile inner join ap_pudetail on 
				  ap_pufile.kodepu=ap_pudetail.kodepu where ap_pufile.kodepb = '$nobukti' and 
				  ap_pudetail.kodeitem='$brg'
				  ")->row();
				  if($datapembelian){
					 $hargabeli = $datapembelian->hargabeli; 
				  } else {
					 $hargabeli = 0; 
				  }
				  
			  }              
			  
			  $harga = $db->hpp;				  
			  if($db->penerimaan>0){
				 $hargamasuk= $hargabeli;
				 $nilaimasuk= $harga * $db->penerimaan;
				 $hargakeluar= 0;				 
				 $hargaakhir = ($nilaisaldo+$nilaimasuk)/($saldo+$db->penerimaan);
			  };
			  
			  if($db->pengeluaran>0){
				 $hargamasuk= 0;
				 $hargakeluar= $hargaakhir;
				 $hargaakhir = $hargaakhir;
			  };
			  
			  $saldo = $saldo + $db->penerimaan - $db->pengeluaran;	
			  $nilaisaldo = $saldo * $hargaakhir;
			  
			  $pdf->row(array($nourut, date('d-m-Y',strtotime($db->tanggal)), 
			  $db->nobukti,$db->satuan,
			  number_format($db->penerimaan,0,'.',','),
			  number_format($hargamasuk,0,'.',','),
			  number_format($db->penerimaan*$hargamasuk,0,'.',','),
			  number_format($db->pengeluaran,0,'.',','),
			  number_format($hargakeluar,0,'.',','),
			  number_format($db->pengeluaran*$hargakeluar,0,'.',','),
			  number_format($saldo,0,'.',','),
			  number_format($hargaakhir,0,'.',','),
			  number_format($hargaakhir*$saldo,0,'.',',')));
			  $nourut++;
			}
            
            $pdf->SetTitle('KARTU STOK');
			$pdf->AliasNbPages();
			$pdf->output('INV_102.PDF','I');
		  }	else
		  if($idlp==103){				
            $query = 
            "select * from inv_barang where kodeitem is not null"; 			
			
            if($kat!=""){
			$query.= " and kdkategori = '$kat'";	
			} 			
			if($cab!=""){
			$query.= " and kdcbg = '$cab'";	
			} 			
			$query.= " order by kodeitem";
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Umur Persediaan');
			$pdf->setsubjudul($_peri1);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(10,20,50,15,15,15,15,15,15,15));
			$pdf->SetAligns(array('C','C','C','C','C','C','C','C','C','C'));
			$judul=array('No.','Kode Barang ','Nama Barang','Stok', 'Satuan',' 1 - 30','31 - 60','61 - 90','91 - 120','> 120');
			$pdf->setfont('Times','B',10);
			$pdf->row($judul);
			$pdf->SetWidths(array(10,20,50,15,15,15,15,15,15,15));
			$pdf->SetAligns(array('C','C','L','R','L','R','R','R','R','R'));
			$pdf->setfont('Times','',9);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;

			foreach($lap as $db)
			{
			  
			  $stok1 = 0;
			  $stok2 = 0;
			  $stok3 = 0;
			  $stok4 = 0;
			  $stok5 = 0;
			  
			  $pdf->row(array($nourut, $db->kodeitem, $db->namabarang, $db->stok, $db->satuan, 
			  $stok1,$stok2, $stok3, $stok4, $stok5));
			  
			  $nourut++;
			}

            $pdf->SetTitle('UMUR PERSEDIAAN BARANG');
			$pdf->AliasNbPages();
			$pdf->output('INV_103.PDF','I');
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