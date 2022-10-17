<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_order extends CI_Controller {

	/**
	 * @author : Enjang RK
	 * @web : http://e-soft.comli.com
	 * @keterangan : Controller untuk modul saldo awal keuangan
	 **/
	
	
	
	
	public function __construct()
	{
		parent::__construct();
		$this->session->set_userdata('menuapp', '300');
		$this->session->set_userdata('submenuapp', '320');
		$this->load->helper('simkeu_rpt');
		//$this->load->model('M_keuangan_transfer','M_keuangan_transfer');
	}

	public function index()
	{
		$unit = $this->session->userdata('unit');	
		//if(!empty($unit))
		{
		  $q1 = 
				 "select nomor, tanggal,  bank_sumber, bank_tujuan, uraian, jumlah
					from
					   tr_transfer
					where
					   year(tanggal)= (select periode_tahun from ms_identity) and
					   month(tanggal)= (select periode_bulan from ms_identity)
					order by
					   nomor desc";

	      $d['keu'] = $this->db->query($q1)->result();		
		  $this->load->view('pembelian/v_pembelian_order',$d);			   
		}
	}
	
	
	public function cetak($param)
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');		
		if(!empty($cek))
		{				  		 
          
		    $query = "select * from tr_transfer where nomor = '$param'";
			$data  = $this->db->query($query)->result();
			foreach($data as $row){
				$tanggal = date('d-m-Y',strtotime($row->tanggal));
				$ket     = $row->uraian;
				$sumber  = $row->bank_sumber;
				$tujuan  = $row->bank_tujuan;
				$jumlah  = $row->jumlah;
			}
			
			$query = "select bank_nama from ms_bank where bank_kode = '$sumber'";
			$data  = $this->db->query($query)->result();
			foreach($data as $row){
			$sumbernm = $row->bank_nama;
			}
		
			$query = "select bank_nama from ms_bank where bank_kode = '$tujuan'";
			$data  = $this->db->query($query)->result();
			foreach($data as $row){
			$tujuannm = $row->bank_nama;
			}
			$d['nama_usaha']=$this->config->item('nama_perusahaan');
			$d['ketditerimadari'] = 'Sumber/Tujuan';
			$d['diterimadari'] = $sumbernm." ke ".$tujuannm;

			$d['judul']='BUKTI TRANSFER';
	
		    $d['ket']=$ket;
			$d['jumlah']=$jumlah;
			$d['terbilang']=ucwords($this->M_global->terbilang($jumlah)).' Rupiah';
			
		    $this->load->view('keuangan/v_keuangan_transfer_voucher',$d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
	
	public function entri()
	{
		$cek = $this->session->userdata('level');		
		$uid = $this->session->userdata('unit');		
		
		if(!empty($cek))
		{				  
          $d['bank'] = $this->db->get_where('ms_akun',array('kelompok' => 'BANK','akuninduk != ' => ''))->result();
          $d['nomor']= $this->M_keuangan_transfer->nomor_register();		  
		  $this->load->view('keuangan/v_keuangan_transfer_add',$d);				
		}
		else
		{
			header('location:'.base_url());
		}
	}
	
	public function hapus($nomor)
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{				  
		   $this->M_keuangan_transfer->hapus_transfer($nomor);				
		   
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	public function transfer_save()
	{
		$cek = $this->session->userdata('level');
		if(!empty($cek))
		{			
           
			$nobukti  = $this->input->post('nomorbukti');
			$userid   = $this->session->userdata('username');
			$uid      = $this->session->userdata('unit');
			$data = array(
				'nomor' => $nobukti,
				'tanggal' => date('Y-m-d',strtotime($this->input->post('tanggal'))),
				'bank_sumber' => $this->input->post('sumber'),
				'bank_tujuan' => $this->input->post('tujuan'),
				'uraian' => $this->input->post('keterangan'),
				'jumlah' => str_replace(',','',$this->input->post('jumlah')),
				'kodepasar' => $uid,
				'userid' => $userid,
				
				
			);
			
			$where = array(
		    'nomor' => $nobukti
	        );
			
	        $query = "select * from tr_transfer where nomor = '$nobukti'";
			if($this->db->query($query)->nuM_rows()==0)
			{
			  $this->M_keuangan_transfer->input_data($data,"tr_transfer");	
			  $this->M_keuangan_transfer->update();
			} else {
			  $this->M_keuangan_transfer->update_data($where,$data,'tr_transfer');				  
			}
								
			
		}
		else
		{
			header('location:'.base_url());
		}
	}
	
	public function edit($nomor)
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{	
			$unit = $this->session->userdata('unit');	
					
			$qheader ="select * from tr_transfer where nomor = '$nomor'"; 		
			
			$d['header'] = $this->db->query($qheader);
            $data=$this->db->query($qheader);
			foreach($data->result() as $row){
			$d['tanggal']=$row->tanggal;
			$d['sumber']=$row->bank_sumber;
		    $d['tujuan']=$row->bank_tujuan;
			$d['uraian']=$row->uraian;
			$d['jumlah']=$row->jumlah;
			
			
            }

          
			$d['bank'] = $this->db->query("select * from ms_bank where bank_pasar ='$unit' order by bank_kode")->result();
			$d['nomor']=$nomor;
			
			$this->load->view('keuangan/v_keuangan_transfer_edit',$d);
			} else
		{
			header('location:'.base_url());
		}	
	}
}
/* End of file keuangan_saldo.php */
/* Location: ./application/controllers/keuangan_saldo.php */