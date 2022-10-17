<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Keuangan_rep extends CI_Controller {

	/**
	 * @author : Enjang RK
	 * @web : http://e-soft.comli.com
	 * @keterangan : Controller untuk modul saldo awal keuangan
	 **/
	
	
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_keuangan_rep','M_keuangan_rep');
		$this->load->model('M_global');
	}

	public function index()
	{
		$unit = $this->session->userdata('level');	
		if(!empty($unit))
		{
		 
			  $q1 =
				  "select nomor_register, nomor_bukti, tanggal, penerima, jumlah, posting
					from
					   tr_repre
					   
					where
					   
					   year(tanggal)= (select periode_tahun from ms_identity) and
					   month(tanggal)= (select periode_bulan from ms_identity) 
					order by
					   nomor_register desc";

	      $d['keu'] = $this->db->query($q1)->result();		
		  $this->load->view('keuangan/v_keuangan_rep',$d);			   
		} else
		{
			header('location:'.base_url());	
		}
		
	}
		
	
	
	
	public function cetak($param)
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');		
		if(!empty($cek))
		{				  		 
		  $d['master_bank'] = $this->db->get("ms_bank");
		  $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  $d['nomor']=$param;
		  $d['unit'] = $this->session->userdata('unit');
		  $d['userid'] = $this->session->userdata('username');  
		 
		    $sql  = "select * from tr_repre where nomor_register='$param'";
			$data = $this->db->query($sql)->result();
			foreach($data as $row){
				$penerima = $row->penerima;
				$jumlah   = $row->jumlah;			
				$ket      = $row->keterangan;
				$tanggal  = $row->tanggal;
			}
			$d['terbilang']  = $this->M_global->terbilang($jumlah)." rupiah";
			$tgl = date('d',strtotime($tanggal));
			$thn = date('Y',strtotime($tanggal));
			$bln = date('n',strtotime($tanggal));
			$nbln=  ucwords(strtolower($this->M_global->_namabulan($bln)));
            $d['jumlah']=$jumlah;
			$d['ket']=$ket;
			$d['tanggalc'] = $tgl.' '.$nbln.' '.$thn;
			$d['penerima']=$penerima;
			$d['_tahun']=$thn;

			if($penerima=='DIRUT')
			{
			  $d['vpenerima'] = 'Direktur Utama';
			  $d['vnama'] = 'ERVAN MAKSUM, ST.,M.Sc.';  
			} else
			if($penerima=='DIRUM')
			{
			  $d['vpenerima'] = 'Direktur Administrasi dan Umum';
			  $d['vnama'] = 'ANDRI SALMAN, ST.';  
			} else
			if($penerima=='DIROP')
			{
			  $d['vpenerima'] = 'Direktur Operasional';
			  $d['vnama'] = 'Ir. PANCA SAKTIADI S';  
			}
		  
		  $this->load->view('keuangan/v_keuangan_rep_voucher',$d);				
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
		 
          $d['bank'] = $this->db->query("select * from ms_bank where bank_pasar ='$uid'")->result();
		  $d['unit'] = $this->db->query("select * from ms_pasar where pasar_kode='$uid'")->result();
		  $d['_tahun'] = $this->M_global->_periodetahun();
		  $this->load->view('keuangan/v_keuangan_rep_add',$d);				
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
		   $this->M_keuangan_rep->hapus_rep($nomor);				
		   
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
	
	
	public function getnobukti()
	{
		//if(!empty($kode))
		{	
            $tahun = $this->M_global->_periodetahun();
			$unit  = $this->session->userdata('unit');
	       
			$query = "select max(nomor) as nomor from v_nomorbkk where  tahun='$tahun' and unit='$unit'";	
			
			$data  = $this->db->query($query);
			foreach($data->result() as $row)
			{
			 
			  $no = $row->nomor;				
			}
			if($no=='')
			{
			  $nomor = 0;	
			} else
			{
			  $nomor = (int)$no;	
			}
			$nomor++;
			$nourut = str_pad($nomor, 4, "0", STR_PAD_LEFT);
			echo $nourut;
			
		} 
	}
	
	public function rep_save($kode)
	{
		$cek = $this->session->userdata('level');
		if(!empty($cek))
		{			
            $jenis    = trim($this->input->post('jenis'));
			if($kode==1){
			  $register = $this->M_keuangan_rep->nomor_register($jenis);
			} else {
			  $register = $this->input->post('register');	
			}
			$nobukti  = $this->input->post('nomorbukti');
			
			$userid   = $this->session->userdata('username');
			$kasbank  = $this->input->post('bank');
			
			$qkas = $this->db->query("select bank_kodeakun from ms_bank where bank_kode = '$kasbank'")->result();
			foreach($qkas as $row){
				$akunkas = $row->bank_kodeakun;
			}
			$data = array(
				'nomor_register' => $register,
				'nomor_bukti' => $nobukti,
				'tanggal' => date('Y-m-d',strtotime($this->input->post('tanggal'))),
				'penerima' => $this->input->post('penerima'),
				'keterangan' => $this->input->post('keterangan'),
				'kode_bank' => $this->input->post('bank'),
				'jumlah' => str_replace(',','',$this->input->post('jumlah')),
				'kode_pasar' => $this->session->userdata('unit'),
				'userid' => $userid,
				'posting' => 'T',
				'kode_akun' => '5021204',
				'kode_akun_bank' => '$akunkas'
				
			);
		    
			$where = array(
		    'nomor_register' => $register
	        );
		
	        $query = "select * from tr_repre where nomor_register = '$register'";
			if($this->db->query($query)->nuM_rows()==0)
			{
			  $this->M_keuangan_rep->input_data($data,"tr_repre");	
			  $this->M_keuangan_rep->update();
			} else {
			  $this->M_keuangan_rep->update_data($where,$data,'tr_repre');				  
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
					
			$qheader ="select * from tr_repre where nomor_register = '$nomor'"; 		
			
			$d['header'] = $this->db->query($qheader);
		    $data=$this->db->query($qheader);
			foreach($data->result() as $row){
			$d['tanggal']=$row->tanggal;
			$d['register']=$row->nomor_register;
		    $d['nomor']=$row->nomor_bukti;
			$d['keterangan']=$row->keterangan;
			$d['kasbank']=$row->kode_bank;
			$d['penerima']=$row->penerima;	
			$d['userentry']=$row->userid;	
			$d['jumlah']=$row->jumlah;	
			
		    }
           
			$d['bank'] = $this->db->query("select * from ms_bank where bank_pasar ='$unit'")->result();
		    $d['unit'] = $this->db->query("select * from ms_pasar where pasar_kode='$unit'")->result();
			$d['_tahun']=$this->M_global->_periodetahun();	
			$this->load->view('keuangan/v_keuangan_rep_edit',$d);
		} else
		{
			header('location:'.base_url());
		}	
	}
	
	public function posting($nomor)
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{	
			$unit = $this->session->userdata('unit');	
					
			$qheader ="select * from tr_bdd where nomor_register = '$nomor'"; 		
			$qdetil ="select * from tr_bddd where nomor_register = '$nomor'"; 		
			
			$d['header'] = $this->db->query($qheader);
			$d['detil'] = $this->db->query($qdetil)->result();
			$d['jumdata'] = $this->db->query($qdetil)->nuM_rows();	

            $data=$this->db->query($qheader);
			foreach($data->result() as $row){
			$d['tanggal']=$row->tanggal;
			$d['register']=$row->nomor_register;
		    $d['nomor']=$row->nomor_bukti;
			$d['bidang']=$row->bidang;
			$d['uraian']=$row->keterangan;
			$d['unit']=$row->kode_pasar;
			$d['kasbank']=$row->kode_bank;
			$d['penerima']=$row->bidang;	
			$d['userentry']=$row->userid;	
			$d['pembayaran']=$row->pembayaran;	
			$d['pembayaran_tgl']=$row->tanggal_cekgiro;
            $d['pembayaran_nomor']=$row->nomor_cekgiro;
            $d['nond']=$row->nd;			
		    }

           
			$d['bank'] = $this->db->query("select * from ms_bank where bank_pasar ='$unit'")->result();
		    $d['unit'] = $this->db->query("select * from ms_pasar where pasar_kode='$unit'")->result();
			$d['dtbidang'] = $this->db->get('ms_bidang')->result();
		   
			$this->load->view('keuangan/v_keuangan_bdd_edit',$d);
			} else
		{
			header('location:'.base_url());
		}	
	}
}
/* End of file keuangan_saldo.php */
/* Location: ./application/controllers/keuangan_saldo.php */