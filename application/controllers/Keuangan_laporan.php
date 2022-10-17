<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Keuangan_laporan extends CI_Controller {

	
	
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('M_global','M_global');
		$this->load->helper('simkeu_rpt');		
		$this->session->set_userdata('menuapp', '5000');
		$this->session->set_userdata('submenuapp', '5107');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{		
			$unit = $this->session->userdata('unit');				
			$this->load->helper('url');		
			
			// $d['cabang']= $this->db->get('tbl_namers')->result();
			
			$d['cabang']= $this->db->get_where('tbl_namers', array("koders" => $unit))->row();
            
			// print_r($d['cabang']);

			$this->load->view('keuangan/v_keuangan_laporan',$d);
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
		  $bank  = $data[3];
		  $cab   = $data[4];
		  
		  $_tgl1 = date('Y-m-d',strtotime($tgl1));
		  $_tgl2 = date('Y-m-d',strtotime($tgl2));
		  $_peri = 'Dari '.date('d-m-Y',strtotime($tgl1)).' s/d '.date('d-m-Y',strtotime($tgl2));
		  $_peri1= 'Per Tgl. '.date('d',strtotime($tgl2)).' '.$this->M_global->_namabulan(date('n',strtotime($tgl2))).' '.date('Y',strtotime($tgl2));
		  
		  if($idlp==101){	
		    $bulan = date('n',strtotime($tgl1)); 
			$tahun = date('Y',strtotime($tgl2)); 
            $query = 
            "select * from ms_akunsaldo right outer join ms_akun on ms_akunsaldo.kodeakun=ms_akun.kodeakun 
			 and ms_akunsaldo.tahun = '$tahun' and ms_akunsaldo.bulan = '$bulan'
			 where ms_akun.kelompok = 'BANK' and ms_akun.tx='Y'
			 ";
			
            if($cab!=""){
			$query.= "and tbl_acsaldo.koders = '$cab'";	
			} 			
			$query.= "order by tbl_acsaldo.kodeakun";
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Daftar Kas');
			$pdf->setsubjudul($_peri1);
			$pdf->addpage("L","A4");   
			$pdf->setsize("L","A4");
			$pdf->SetWidths(array(10,30,70,50,50,50));
			$pdf->SetAligns(array('C','C','C','C','C','C'));
			$judul=array('No.','Kode Perkiraan ','Nama Perkiraan','Saldo Awal','Mutasi','Saldo Akhir');
			$pdf->setfont('Times','B',10);
			$pdf->row($judul);
			
			$pdf->SetWidths(array(10,30,70,25,25,25,25,25,25));
			$pdf->SetAligns(array('C','C','C','C','C','C','C','C','C'));
			$judul=array('',' ','','Debet','Kredit','Debet','Kredit','Debet','Kredit');
			$pdf->row($judul);
			$pdf->SetWidths(array(10,30,70,25,25,25,25,25,25));
			$pdf->SetAligns(array('C','C','L','R','R','R','R','R','R'));
			$pdf->setfont('Times','',9);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;
            $tot1d = $tot1k = $tot2d = $tot2k = $tot3d = $tot3k = 0;
			foreach($lap as $db)
			{
			  $tot1d += $db->debet;
			  $tot1k += $db->kredit;
			  
			  
		      $saldoawal = $db->debet+$db->kredit;
			  $akun = $db->kodeakun;
			  $jur = $this->db->query("select sum(debet) as debet, sum(kredit) as kredit from tr_jurnal where kodeakun='$akun' and tanggal between '$_tgl1' and '$_tgl2'")->row();	
			  $tdb = $jur->debet;
			  $tkr = $jur->kredit;
			  $mutasi = $tdb-$tkr;
			  
			  $tot2d += $tdb;
			  $tot2k += $tkr;
			  
              $akhir  = $saldoawal+$mutasi;
              if($akhir>0){
				  $akhirdb=abs($akhir);
				  $akhirkr=0;
			  } else {
				  $akhirkr=abs($akhir);
				  $akhirdb=0;
			  }			  
			  
			  $tot3d += $akhirdb;
			  $tot3k += $akhirkr;
			  
			  $pdf->row(array($nourut, 
			  $db->kodeakun, 
			  $db->namaakun, 
			  number_format($db->debet,0,'.',','),
			  number_format($db->kredit,0,'.',','),
			  number_format($tdb,0,'.',','),
			  number_format($tkr,0,'.',','),
			  number_format($akhirdb,0,'.',','),
			  number_format($akhirkr,0,'.',',')));
			  
			  $nourut++;
			}
			$pdf->SetWidths(array(110,25,25,25,25,25,25));
			$pdf->SetAligns(array('C','R','R','R','R','R','R'));
			$pdf->row(array('TOTAL',
			  number_format($tot1d,0,'.',','),
			  number_format($tot1k,0,'.',','),
			  number_format($tot2d,0,'.',','),
			  number_format($tot2k,0,'.',','),
			  number_format($tot3d,0,'.',','),
			  number_format($tot3k,0,'.',',')));

            $pdf->SetTitle('LAPORAN DAFTAR KAS/BANK');
			$pdf->AliasNbPages();
			$pdf->output('Kas_101.PDF','I');
		  }	else 
		  if($idlp==102){				
            $query = 
            "select a.terima_tanggal as tanggal, a.terima_nomor as nomor, a.terima_uraian as ket, sum(b.terimad_jumlah) as jumlah  from tr_penerimaan a,
			 tr_penerimaand b where a.terima_nomor=b.terimad_nomor
			 and a.terima_tanggal between '$_tgl1' and '$_tgl2'"; 
			 
			
            if($bank!=""){
			$query.= "and a.terima_kasbank = '$bank'";	
			} 			
			if($cab!=""){
			$query.= "and a.terima_kodecbg = '$cab'";	
			} 			
			$query.= " group by a.terima_nomor "; 			
			$query.= " order by a.terima_tanggal, a.terima_nomor";
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Daftar Penerimaan Kas & Bank');
			$pdf->setsubjudul($_peri);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(10,30,20,90,35));
			$pdf->SetAligns(array('C','C','C','C','C'));
			$judul=array('No.','Nomor ','Tanggal','Keterangan', 'Jumlah');
			$pdf->setfont('Times','B',10);
			$pdf->row($judul);
			$pdf->SetWidths(array(10,30,20,90,35));
			$pdf->SetAligns(array('C','C','C','L','R'));
			$pdf->setfont('Times','',10);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;
            $tot = 0; 
			foreach($lap as $db)
			{
			  $tot = $tot + $db->jumlah;	
			  $pdf->row(array($nourut, $db->nomor, date('d-m-Y',strtotime($db->tanggal)), $db->ket, number_format($db->jumlah,0,',','.')));
			  $nourut++;
			}
            $pdf->SetWidths(array(150,35));
			$pdf->SetAligns(array('C','R'));
			$pdf->setfont('Times','B',10); 
			$pdf->row(array('TOTAL', number_format($tot,0,',','.')));

			$pdf->SetTitle('LAPORAN PENERIMAAN KAS/BANK');
			$pdf->AliasNbPages();
			$pdf->output('kasbank_penerimaan.PDF','I');
		  }	else 
          if($idlp==103){				
            $query = 
            "select a.keluar_tanggal as tanggal, a.keluar_nomor as nomor, a.keluar_uraian as ket, sum(b.keluard_jumlah) as jumlah  from tr_pengeluaran a,
			 tr_pengeluarand b where a.keluar_nomor=b.keluard_nomor
			 and a.keluar_tanggal between '$_tgl1' and '$_tgl2'"; 
			 
			
            if($bank!=""){
			$query.= "and a.keluar_kasbank = '$bank'";	
			} 			
			if($cab!=""){
			$query.= "and a.keluar_kodecbg = '$cab'";	
			} 			
			$query.= " group by a.keluar_nomor "; 			
			$query.= " order by a.keluar_tanggal, a.keluar_nomor";
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Daftar Pengeluaran Kas & Bank');
			$pdf->setsubjudul($_peri);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(10,30,20,90,35));
			$pdf->SetAligns(array('C','C','C','C','C'));
			$judul=array('No.','Nomor ','Tanggal','Keterangan', 'Jumlah');
			$pdf->setfont('Times','B',10);
			$pdf->row($judul);
			$pdf->SetWidths(array(10,30,20,90,35));
			$pdf->SetAligns(array('C','C','C','L','R'));
			$pdf->setfont('Times','',10);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;
            $tot = 0; 
			foreach($lap as $db)
			{
			  $tot = $tot + $db->jumlah;	
			  $pdf->row(array($nourut, $db->nomor, date('d-m-Y',strtotime($db->tanggal)), $db->ket, number_format($db->jumlah,0,',','.')));
			  $nourut++;
			}
            $pdf->SetWidths(array(150,35));
			$pdf->SetAligns(array('C','R'));
			$pdf->setfont('Times','B',10); 
			$pdf->row(array('TOTAL', number_format($tot,0,',','.')));

			$pdf->SetTitle('LAPORAN PENGELUARAN KAS/BANK');
			$pdf->AliasNbPages();
			$pdf->output('kasbank_pengeluaran.PDF','I');
		  }	else 
          if($idlp==104){				
            $query = 
            "select * from tr_transfer where tanggal between '$_tgl1' and '$_tgl2'"; 
			
            if($bank!=""){
			$query.= "and bank_sumber = '$bank'";	
			} 			
			if($cab!=""){
			$query.= "and cabang = '$cab'";	
			} 			
			$query.= " order by tanggal, nomor";
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Daftar Transfer Kas & Bank');
			$pdf->setsubjudul($_peri);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(10,30,20,50,20,20,35));
			$pdf->SetAligns(array('C','C','C','C','C','C','C'));
			$judul=array('No.','Nomor ','Tanggal','Keterangan', 'Dari Kas/Bank','Ke Kas/Bank','Jumlah');
			$pdf->setfont('Times','B',10);
			$pdf->row($judul);
			$pdf->SetWidths(array(10,30,20,50,20,20,35));
			$pdf->SetAligns(array('C','C','C','L','L','L','R'));
			$pdf->setfont('Times','',10);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;
            $tot = 0; 
			foreach($lap as $db)
			{
			  $sumber = $this->M_global->_namaakun($db->bank_sumber);
			  $tujuan = $this->M_global->_namaakun($db->bank_tujuan);
			  
			  $tot = $tot + $db->jumlah;	
			  $pdf->row(array($nourut, $db->nomor, date('d-m-Y',strtotime($db->tanggal)), $db->uraian, $sumber, $tujuan, number_format($db->jumlah,0,',','.')));
			  $nourut++;
			}
            $pdf->SetWidths(array(150,35));
			$pdf->SetAligns(array('R','R'));
			$pdf->setfont('Times','B',10); 
			$pdf->row(array('TOTAL', number_format($tot,0,',','.')));

			$pdf->SetTitle('LAPORAN TRANSFER KAS/BANK');
			$pdf->AliasNbPages();
			$pdf->output('kasbank_transfer.PDF','I');
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