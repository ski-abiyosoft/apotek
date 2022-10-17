<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Akuntansi_laporan extends CI_Controller {

	
	
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('M_global','M_global');
		$this->load->helper('simkeu_rpt');	
		$this->session->set_userdata('menuapp', '200');
		$this->session->set_userdata('submenuapp', '208');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{		
			$unit = $this->session->userdata('unit');	
			if(!empty($unit)){
			  $qp ="select kode, nama from ms_unit where kode = '$unit'"; 
			} else {
			  $qp ="select kode, nama from ms_unit order by kode"; 		
			}
			
			$qakun ="select kodeakun, namaakun from ms_akun order by kodeakun"; 
			$qformatn ="select kode  from ms_format where jenis='N' order by kode"; 		
			$this->load->helper('url');		
			$d['unit']  = $this->db->query($qp);	
            $d['akuninduk']  = $this->db->get_where('ms_akun',array('akuninduk' => ''));
			$d['akundetil']  = $this->db->get_where('ms_akun',array('akuninduk != ' => ''));
			$d['formatn']  = $this->db->query($qformatn);	
			
			$this->load->view('akuntansi/v_akuntansi_laporan',$d);
		} else
		{
			header('location:'.base_url());
		}			
	}
	
	
	
	public function cetak($x)
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{
          $namaunit = $this->M_global->_namaunit($unit);			
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  
		  
		  $data  = explode("~",$x);
		  $jns   = $data[0];
				
		  if($jns==1)
		  {			  	
             $judul=array('NO','TANGGAL','NO. BUKTI','KODE AKUN','NAMA AKUN','URAIAN','DEBET','KREDIT');	  
             $_peri = 'Dari '.date('d-m-Y',strtotime($data[1])).' s/d '.date('d-m-Y',strtotime($data[2]));
           
             if($data[3]!=""){
				 $qlap = $this->db->order_by('nourut')->get_where('tr_jurnal',array('novoucher' => $data[3]))->result();
			 }  			 
			 $d['tgl1']=$data[1];
		     $d['tgl2']=$data[2];			 			 
			 $d['nobukti']=$data[3];			 	
             $d['judul']=$judul;
			 $d['_peri']=$_peri;
			 $d['lap']=$qlap;
		     $this->load->view('akuntansi/v_akuntansi_lap1',$d);				 
		  } else
		  if($jns==2)
		  {
			 $d['tgl1']=$data[1];
		     $d['tgl2']=$data[2];			 			 
			 $d['akun']=$data[3];		
             $d['saldo_awal']=0;			 
		     $this->load->view('akuntansi/v_akuntansi_lap2',$d);				 
		  } else
		  if($jns==3)
		  {
			 $d['tgl1']=$data[1];
		     $d['tgl2']=$data[2];			 			 
             $d['saldoawal']=0;				 
		     $this->load->view('akuntansi/v_akuntansi_lap3',$d);				 
		  } else
		  if($jns==4)
		  {
			 $d['tgl1']=$data[1];
		     $d['tgl2']=$data[2];			 			 
			 $d['format']=$data[3];	
			 $d['clk']=$data[4];	
			 $d['__akunlrberjalan']=$this->M_global->_akunlrberjalan();	
			 
			 
             if($data[4]=='true')
			 {				 
		       $this->load->view('akuntansi/v_akuntansi_lap41',$d);	
             } else
			 {
			   $this->load->view('akuntansi/v_akuntansi_lap4',$d);	 
			 }				 
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