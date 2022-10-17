<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payroll_lap extends CI_Controller {

	/**
	 * @author : Enjang RK
	 * @web : http://e-soft.comli.com
	 * @keterangan : Controller untuk modul laporan anggaran
	 **/
	
	public function __construct()
	{
		parent::__construct();				
		$this->session->set_userdata('menuapp', '700');
		$this->session->set_userdata('submenuapp', '703');
		$this->load->helper('simkeu_rpt');
		
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{		
	        $bulan= date('n');
			$tahun= date('Y');
			$unit = $this->session->userdata('unit');							
			$d['unit']  = $this->db->get('ms_pasar')->result();	
			$d['tahun'] = $tahun;			
			$d['bulan'] = $bulan;	            
			$this->load->view('payroll/v_payroll_lap',$d);
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
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  
		  $data  = explode("~",$x);
		  $jns   = $data[0];		  
		  if($jns==1)
		  {		
	        $query = " select * from ms_pegawai where nama is not null";
		  
			if($data[1]!='NONE')		  
			{
			 $query.=" and kode_pasar='$data[1]'";
			} 
					  
			$query.=" order by nama";
			 
			 $d['pegawai']=$this->db->query($query)->result();
			 $d['namaunit']=$this->M_global->_namaunit($this->session->userdata('unit'));
		     $this->load->view('payroll/v_payroll_lap1',$d);				 
		  } else
		  if($jns==2)
		  {
			$query = " select * from ms_pegawai where nama is not null";
		  
			if($data[1]!='NONE')		  
			{
			 $query.=" and kode_pasar='$data[1]'";
			} 
					  
			$query.=" order by nama";
			 
			$d['pegawai']=$this->db->query($query)->result();
			 
			$d['bulan']=$data[2];
		    $d['tahun']=$data[3];
			$d['periode']='PERIODE : '.$this->M_global->_namabulan($data[2]).' '.$data[3];
			$d['namaunit']=$this->M_global->_namaunit($data[1]);
			$this->load->view('payroll/v_payroll_lap2',$d);				 
		  } 
		  
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	public function export($x)
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{				  		            
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  
		  $data  = explode("~",$x);
		  $jns   = $data[0];
		  	
		  if($jns==1)
		  {		
	        $query = " select * from ms_pegawai where nama is not null";
		  
			if($data[1]!='NONE')		  
			{
			 $query.=" and kode_pasar='$data[1]'";
			} 
					  
			$query.=" order by nama";
			 
			 $d['pegawai']=$this->db->query($query)->result();
			 $d['namaunit']=$this->M_global->_namaunit($this->session->userdata('unit'));
		     $this->load->view('payroll/v_payroll_lap1x',$d);				 
		  } else
		  if($jns==2)
		  {
			$query = " select * from ms_pegawai where nama is not null";
		  
			if($data[1]!='NONE')		  
			{
			 $query.=" and kode_pasar='$data[1]'";
			} 
					  
			$query.=" order by nama";
			 
			$d['pegawai']=$this->db->query($query)->result();
			 
			$d['bulan']=$data[2];
		    $d['tahun']=$data[3];
			$d['periode']='PERIODE : '.$this->M_global->_namabulan($data[2]).' '.$data[3];
			$d['namaunit']=$this->M_global->_namaunit($data[1]);
			$this->load->view('payroll/v_payroll_lap2x',$d);				 
		  }  
		  
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
	
	
	
	
	
}

/* End of file akuntansi_jurnal.php */
/* Location: ./application/controllers/akuntansi_jurnal.php */