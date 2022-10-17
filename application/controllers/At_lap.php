<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class At_lap extends CI_Controller {

	/**
	 * @author : Enjang RK
	 * @web : http://e-soft.comli.com
	 * @keterangan : Controller untuk modul laporan anggaran
	 **/
	
	
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_global');		
		$this->session->set_userdata('menuapp', '5000');
		$this->session->set_userdata('submenuapp', '5403');
		$this->load->helper('simkeu_rpt');
		$this->load->model('M_cetak');
		$this->load->model('M_at_at');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{		
			$unit = $this->session->userdata('unit');							
			$d['unit']  = $this->db->get('tbl_namers')->result();	
			$d['tahun'] = date('Y');
			$d['bulan'] = date('n');
			
			$this->load->view('at/v_at_lap',$d);
		} else
		{
			header('location:'.base_url());
		}			
	}
	
	
	
	public function cetak(string $laporan)
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{				  		            
			$unit = $this->session->userdata('unit');
			$kop  = $this->M_cetak->kop($unit);

			switch ($laporan) {
				case 'laporan_daftar_aktiva_tetap':
					$data = $this->M_at_at->get_fa_list_report_data($unit);
					$judul = "Laporan Daftar Aktiva Tetap";
					$this->M_cetak->mpdf('P', 'A4', $judul, $this->load->view('laporan_akuntansi/laporan_daftar_aktiva_tetap', ['kop' => $kop, 'data' => $data], true), 'Laporan_Daftar_Aktiva.PDF', 10, 10, 10, 2);		
					break;
				case 'laporan_daftar_aktiva_yang_telah_habis':
					$data = $this->M_at_at->get_disposable_fa_report_data($unit);
					$judul = "Laporan Daftar Aktiva Tetap";
					$this->M_cetak->mpdf('P', 'A4', $judul, $this->load->view('laporan_akuntansi/laporan_daftar_aktiva_habis', ['kop' => $kop, 'data' => $data], true), 'Laporan_Daftar_Aktiva_yang_Telah_Habis.PDF', 10, 10, 10, 2);		
					break;
				case 'laporan_histori_penyusutan_aktiva_tetap':
					$data = $this->M_at_at->get_fa_list_report_data($unit);
					$judul = "Laporan Histori Penyusutan Aktiva Tetap";
					$this->M_cetak->mpdf('P', 'A4', $judul, $this->load->view('laporan_akuntansi/laporan_histori_penyusutan', ['kop' => $kop, 'data' => $data], true), 'Laporan_Daftar_Aktiva.PDF', 10, 10, 10, 2);
					break;
				default:
					echo 'Jenis laporan tidak ditemukan!';
					break;
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
		  $qpagu = "  select a.kodeakun, b.namaakun, a.pagu, b.jenis from ms_anggaran_pagu a, ms_akun b
					  where a.kodeakun=b.kodeakun  and b.status='A' and a.tahun = '$data[1]'
					  order by a.kodeakun";		
		  if($jns==1)
		  {			  			 
			 $d['tahun']=$data[1];
			 $d['pagu']=$this->db->query($qpagu)->result();
			 $d['unit']=$this->session->userdata('unit');
		     $this->load->view('anggaran/v_anggaran_exp1',$d);				 
		  } else
		  if($jns==2)
		  {
			 $d['unit']=$data[1];
		     $d['tahun']=$data[2];			 
			 $this->load->view('anggaran/v_anggaran_exp2',$d);				 
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