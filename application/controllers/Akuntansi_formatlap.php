<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Akuntansi_formatlap extends CI_Controller {

	/**
	 * @author : Enjang RK
	 * @web : http://e-soft.comli.com
	 * @keterangan : Controller untuk modul entri jurnal umum
	 **/
	
	
	
	
	public function __construct()
	{
		
		parent::__construct();		
		$this->session->set_userdata('menuapp', '200');
		$this->session->set_userdata('submenuapp', '202');
		$this->load->model('akuntansi/m_akuntansi_formatlap');			
	}
	
	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{	
			$unit = $this->session->userdata('unit');	
			$query ="select kode, nama, jenis from ms_format order by kode";						
			$d['formatlap'] = $this->db->query($query)->result();		
			$this->load->view('akuntansi/v_akuntansi_formatlap',$d);
			} else
		{
			header('location:'.base_url());
		}	
	}
	
	public function save()
	{
		
		$cek = $this->session->userdata('level');
		if(!empty($cek))
		{										   
				$kode  = $this->input->post('kode');
				$nama  = $this->input->post('nama');
				$jenis = $this->input->post('jenis');
				$this->m_akuntansi_formatlap->update_formatlap($kode,$nama,$jenis);			
		}
		else
		{
			header('location:'.base_url());
		}
	}
	
	public function del()
	{		
		$cek = $this->session->userdata('level');
		if(!empty($cek))
		{										   
				$kode  = $this->input->post('nomor');				
				$this->m_akuntansi_formatlap->delete_by_id($kode);			
		}
		else
		{
			header('location:'.base_url());
		}
	}
	
	public function rincian($kode)
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{	
			$unit  = $this->session->userdata('unit');	
			$query = "select nomor, nourut, judul_lap, judul_cf, kelompok from ms_formatd where kode = '$kode' order by nourut";
			$d['formatlap'] = $this->db->query($query)->result();
			$d['kode']=$kode;
			$qnama = "select nama from ms_format where kode = '$kode'";
			$dnama = $this->db->query($qnama)->row();
			$d['nama']=$dnama->nama;
			$this->load->view('akuntansi/v_akuntansi_formatlapd',$d);
			} else
		{
			header('location:'.base_url());
		}	
	}
	
	public function rinciand($nomor)
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{	
			$unit  = $this->session->userdata('unit');	
			$query ="select a.nomor, a.akun, b.namaakun from ms_formatdd a inner join ms_akun b on a.akun=b.kodeakun where nomorlap = '$nomor' order by a.akun";
            $qket  ="select * from ms_formatd where nomor = $nomor";
			$rket  = $this->db->query($qket)->row();
			$d['kode'] = $rket->kode;
			$d['nomor'] = $nomor;
			$d['nama'] = '';
			$d['judul'] = $rket->judul_lap;
			$d['jumdat'] = 1;
			$d['formatlap'] = $this->db->query($query)->result();			
			$d['coa'] = $this->db->get('ms_akun')->result();			
			$this->load->view('akuntansi/v_akuntansi_formatlapdd',$d);
			} else
		{
			header('location:'.base_url());
		}	
	}
	
		
		
	
	
	
}

/* End of file akuntansi_jurnal.php */
/* Location: ./application/controllers/akuntansi_jurnal.php */