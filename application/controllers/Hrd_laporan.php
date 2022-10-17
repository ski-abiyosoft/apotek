<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hrd_laporan extends CI_Controller {

	
	
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('M_global','M_global');
		$this->load->helper('simkeu_rpt');		
		$this->session->set_userdata('menuapp', '700');
		$this->session->set_userdata('submenuapp', '730');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{		
			$unit = $this->session->userdata('unit');				
			$this->load->helper('url');		
			$d['karyawan']= $this->db->get_where('hrd_karyawan',array('nama !=' => ''))->result();
            
			$this->load->view('hrd/v_hrd_laporan',$d);
		} else
		{
			header('location:'.base_url());
		}			
	}
	
	
	
	public function cetak()
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
		  
		  $idlp  = $this->input->get('idlap');
		  $bulan = $this->input->get('bulan');
		  $tahun = $this->input->get('tahun');
		  $peg   = $this->input->get('karyawan');
		  
		  $_peri1= 'Periode '.$this->M_global->_namabulan($bulan).' '.$tahun;
		  
		  if($idlp==101){				
            $query = 
            "select hrd_transpayroll.*, hrd_karyawan.nip, hrd_karyawan.nama 
			 from hrd_transpayroll inner join hrd_karyawan on  hrd_transpayroll.id_karyawan =  hrd_karyawan.id 
			 where hrd_transpayroll.tahun = '$tahun' and hrd_transpayroll.bulan='$bulan' "; 			
			
            if($peg!=""){
			$query.= " and hrd_transpayroll.id_karyawan = '$peg'";	
			} 			
			
			$query.= " order by hrd_transpayroll.id";
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Daftar Gaji Karyawan');
			$pdf->setsubjudul($_peri1);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(10,20,50,20,20,25,25,25));
			$pdf->SetAligns(array('C','C','C','C','C','C','C','C'));
			$judul=array('No.','NIK','Nama Karyawan','Gaji Pokok', 'Tunjangan','Potongan','PPH','Gaji Diterima');
			$pdf->setfont('Times','B',10);
			$pdf->row($judul);
			$pdf->SetWidths(array(10,20,50,20,20,25,25,25));
			$pdf->SetAligns(array('C','L','L','R','R','R','R','R'));
			$pdf->setfont('Times','',9);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;

			foreach($lap as $db)
			{
			  $tunjangan = $db->tunjanganpph+$db->uanglembur+$db->uangtransport+$db->uangmakan+$db->uangpulsa+$db->bonus;	
			  $potongan  = $db->potongan+$db->jkk+$db->jkm+$db->askes;
			  
			  $pdf->row(array($nourut, $db->nip, $db->nama, 
			  number_format($db->gapok,0,'.',','),
			  number_format($tunjangan,0,'.',','),
			  number_format($potongan,0,'.',','),
			  number_format($db->pph,0,'.',','),
			  number_format($db->thp,0,'.',',')));
			  $nourut++;
			}

            $pdf->SetTitle('DAFTAR GAJI KARYAWAN');
			$pdf->AliasNbPages();
			$pdf->output('GAJI-KARYAWAN.PDF','I');
		  }	 elseif($idlp==102){				
            $query = 
            "select hrd_transpayroll.*, hrd_karyawan.nip, hrd_karyawan.nama 
			 from hrd_transpayroll inner join hrd_karyawan on  hrd_transpayroll.id_karyawan =  hrd_karyawan.id 
			 where hrd_transpayroll.tahun = '$tahun' and hrd_transpayroll.bulan='$bulan' "; 			
			
            if($peg!=""){
			$query.= " and hrd_transpayroll.id_karyawan = '$peg'";	
			} 			
			
			$query.= " order by hrd_transpayroll.id";
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Slip Gaji Karyawan');
			$pdf->setsubjudul($_peri1);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(50,5,100));
			$pdf->SetAligns(array('L','C','L'));
			$pdf->setfont('Times','B',10);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;

			foreach($lap as $db)
			{
			  $tunjangan = $db->tunjanganpph+$db->uanglembur+$db->uangtransport+$db->uangmakan+$db->uangpulsa+$db->bonus;	
			  $potongan  = $db->potongan+$db->jkk+$db->jkm+$db->askes;
			  
			  
			  $pdf->rowB(array('NIP',':', $db->nip)); 
			  $pdf->rowB(array('Nama Karyawan',':', $db->nama)); 
			  $pdf->rowB(array('','', '')); 
			  $pdf->SetWidths(array(50,5,30));
			  $pdf->SetAligns(array('L','C','R'));
			  $pdf->rowB(array('Gaji Pokok',':', number_format($db->gapok,0,'.',','))); 
			  $pdf->rowB(array('Tunjangan',':', number_format($tunjangan,0,'.',','))); 
			  $pdf->rowB(array('Potongan',':', number_format($potongan,0,'.',','))); 
			  $pdf->rowB(array('PPH',':', number_format($db->pph,0,'.',','))); 
			  $pdf->rowB(array('Gaji Terima',':', number_format($db->thp,0,'.',','))); 
			  $pdf->ln(10);
			  
			  
			  
			  $nourut++;
			}

            $pdf->SetTitle('DAFTAR GAJI KARYAWAN');
			$pdf->AliasNbPages();
			$pdf->output('SLIP-GAJI-KARYAWAN.PDF','I');
		  }	 elseif($idlp==103){				
            $query = 
            "
                select hrd_departemen.nama, 
				sum(hrd_transpayroll.gapok) as gapok,
				sum(hrd_transpayroll.tunjanganpph+hrd_transpayroll.uangmakan+
				hrd_transpayroll.uangtransport+hrd_transpayroll.uanglembur+hrd_transpayroll.uangpulsa+
				hrd_transpayroll.bonus) as tunjangan,
				sum(hrd_transpayroll.potongan+hrd_transpayroll.jkk+hrd_transpayroll.jkm+hrd_transpayroll.askes) as potongan,
				sum(hrd_transpayroll.pph) as pph,
				sum(hrd_transpayroll.thp) as thp
				from hrd_transpayroll inner join hrd_karyawan on  hrd_transpayroll.id_karyawan =  hrd_karyawan.id 
				inner join hrd_departemen on hrd_karyawan.departemen_id=hrd_departemen.kode
				where hrd_transpayroll.tahun = '$tahun' and hrd_transpayroll.bulan='$bulan'
				group by hrd_departemen.kode    
			"; 			
			
            if($peg!=""){
			$query.= " and hrd_transpayroll.id_karyawan = '$peg'";	
			} 			
			
			$query.= " order by hrd_transpayroll.id";
			 
		    $lap = $this->db->query($query)->result();
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('Rekap Gaji Karyawan');
			$pdf->setsubjudul($_peri1);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(10,50,25,25,25,25,25));
			$pdf->SetAligns(array('C','C','C','C','C','C','C'));
			$judul=array('No.','Nama Departemen','Gaji Pokok', 'Tunjangan','Potongan','PPH','Gaji Diterima');
			$pdf->setfont('Times','B',10);
			$pdf->row($judul);
			$pdf->SetWidths(array(10,50,25,25,25,25,25));
			$pdf->SetAligns(array('C','L','R','R','R','R','R'));
			$pdf->setfont('Times','',9);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;

			foreach($lap as $db)
			{
			  
			  $pdf->row(array($nourut, 
			  $db->nama, 
			  number_format($db->gapok,0,'.',','),
			  number_format($db->tunjangan,0,'.',','),
			  number_format($db->potongan,0,'.',','),
			  number_format($db->pph,0,'.',','),
			  number_format($db->thp,0,'.',',')));
			  $nourut++;
			}

            $pdf->SetTitle('REKAP GAJI KARYAWAN');
			$pdf->AliasNbPages();
			$pdf->output('REKAP-GAJI-KARYAWAN.PDF','I');
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