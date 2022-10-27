<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Keuangan_bdd extends CI_Controller {

	/**
	 * @author : Enjang RK
	 * @web : http://e-soft.comli.com
	 * @keterangan : Controller untuk modul saldo awal keuangan
	 **/
	
	
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_keuangan_bdd','M_keuangan_bdd');
	}

	public function index()
	{
		
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        		
		if(!empty($cek))
		{
		  $q1 =
			  "select a.nomor_register, a.nomor_bukti, a.tanggal, a.bidang, a.keterangan,  sum(b.jumlah) as jumlah, a.status, a.tanggal_cekgiro
				from
				   tr_bdd a,
				   tr_bddd b
				where
				   a.nomor_register=b.nomor_register and
				   year(a.tanggal)= (select periode_tahun from ms_identity) and
				   month(a.tanggal)= (select periode_bulan from ms_identity) and
				   a.kode_pasar = '$unit'
				group by
				   a.tanggal, a.nomor_register, a.nomor_bukti, a.bidang, a.keterangan, a.status
				order by
				   a.nomor_register desc";

				   
		  $bulan  = $this->M_global->_periodebulan();
          $nbulan = $this->M_global->_namabulan($bulan);
		  $periode= 'Periode '.$nbulan.'-'.$this->M_global->_periodetahun();	   
	      $d['keu'] = $this->db->query($q1)->result();
          $d['periode'] = $periode;		  		
		  $this->load->view('keuangan/v_keuangan_bdd',$d);			   
		}else
		{
			
			header('location:'.base_url());
			
		}
		
	}
	
	public function filter($param)
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        		
		if(!empty($cek))
		{	
         $data  = explode("~",$param);
		 $jns   = $data[0];		
		 $tgl1  = $data[1];
		 $tgl2  = $data[2];
		 $_tgl1 = date('Y-m-d',strtotime($tgl1));
		 $_tgl2 = date('Y-m-d',strtotime($tgl2));
		 
		 if(!empty($jns))
		 {
		  	 
		  $q1 = 
				"select a.nomor_register, a.nomor_bukti, a.tanggal, a.bidang, a.keterangan,  sum(b.jumlah) as jumlah, a.status, a.tanggal_cekgiro
				from
				   tr_bdd a,
				   tr_bddd b
				where
				   a.nomor_register=b.nomor_register and
				   a.tanggal between '$_tgl1' and '$_tgl2' and
				   a.kode_pasar = '$unit'
				group by
				   a.tanggal, a.nomor_register, a.nomor_bukti, a.bidang, a.keterangan, a.status
				order by
				   a.nomor_register desc";

		  
		  $periode= 'Periode '.date('d-m-Y',strtotime($tgl1)).' s/d '.date('d-m-Y',strtotime($tgl2));	   
	      $d['keu'] = $this->db->query($q1)->result();
          $d['periode'] = $periode;		  
		  $this->load->view('keuangan/v_keuangan_bdd',$d);			   
		}
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
		 
		  $qheader = "select * from tr_bdd inner join ms_bank on tr_bdd.kode_bank=ms_bank.bank_kode where nomor_register = '$param'";
		  $header=$this->db->query($qheader)->result();
		  
		  $query = "select sum(jumlah) as jumlah from tr_bddd where nomor_register = '$param'";
		  $data = $this->db->query($query)->result();
		  foreach($data as $row){
		   $jumlah=$row->jumlah;	  
		  }
		  $d['jumlah']=$jumlah;
		  $d['terbilang']=ucwords($this->M_global->terbilang($jumlah)).' Rupiah';
		  
		  foreach($header as $row)
			{
			$d['kasbank'] = $row->kode_bank;
			$kasbank = $row->kode_bank;
			$d['ket']= $row->keterangan;
			$d['tanggal']= date('d-m-Y',strtotime($row->tanggal));
			$d['penerima']= $row->bidang;
			$d['pasar']= $row->kode_pasar;
			$bidang= $row->bidang;
			$d['nond']= $row->nd;
			$d['nobukti']= $row->nomor_bukti;
			if($row->pembayaran=='T')
			{ $pembayaran = 'Tunai'; } else
			if($row->pembayaran=='C')	
			{ $pembayaran = 'Cek'; } else
			if($row->pembayaran=='G')	
			{ $pembayaran = 'Giro'; };

		    $d['pembayaran']=$pembayaran;
			$d['nogiro']= $row->nomor_cekgiro;
			$tglgiro= $row->tanggal_cekgiro;
			}
			
			if($tglgiro=='1970-01-01')
			{
				$tglgiro='';
			} else
			{
				$tglgiro=date('d-m-Y',strtotime($tglgiro));
			}
			$d['tglgiro']=$tglgiro;

			$query="select nama from ms_bidang where kode = '$bidang'";
			$data = $this->db->query($query);
			foreach($data->result() as $row){
			$bidang=$row->nama;
			}
			$d['bidang']=$bidang;
			
			$query="select bank_nama from ms_bank where bank_kode = '$kasbank'";
			$data = $this->db->query($query);
			foreach($data->result() as $row){
			$namabank=$row->bank_nama;
			}
			$d['namabank']=$namabank;

		  
		 
		  
		  $qdetil ="select * from tr_bddd where nomor_register = '$param'"; 	
		  $d['detil']=$this->db->query($qdetil)->result();
		  
		  $this->load->view('keuangan/v_keuangan_bdd_voucher',$d);				
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
		  $d['bidang'] = $this->db->get("ms_bidang")->result();
		  
		  $this->load->view('keuangan/v_keuangan_bdd_add',$d);				
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
		   $this->M_keuangan_bdd->hapus_bdd($nomor);				
		   
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
	
	public function bdd_save($kode)
	{
		$cek = $this->session->userdata('level');
		if(!empty($cek))
		{			
            $jenis    = trim($this->input->post('jenis'));
			if($kode==1){
			  $register = $this->M_keuangan_bdd->nomor_register($jenis);
			} else {
			  $register = $this->input->post('register');	
			}
			$nobukti  = $this->input->post('nomorbukti');
			
			$userid   = $this->session->userdata('username');
			
			$data = array(
				'nomor_register' => $register,
				'nomor_bukti' => $nobukti,
				'tanggal' => date('Y-m-d',strtotime($this->input->post('tanggal'))),
				'kode_bank' => $this->input->post('kasbank'),
				'pembayaran' => $this->input->post('pembayaran'),
				'tanggal_cekgiro' => date('Y-m-d',strtotime($this->input->post('tanggalcek'))),
				'nomor_cekgiro' => $this->input->post('nomorcek'),
				'nd' => $this->input->post('nond'),
				'penerima' => $this->input->post('bidang'),
				'bidang' => $this->input->post('bidang'),
				'keterangan' => $this->input->post('keterangan'),
				'kode_pasar' => $this->input->post('unit'),
				'userid' => $userid,
				'status' => '1',
				
			);
		    
			$whereh = array(
		    'nomor_register' => $register
	        );
			
			$whered = array(
		    'nomor_register' => $register
	        );
	
	        $query = "select * from tr_bdd where nomor_register = '$register'";
			if($this->db->query($query)->nuM_rows()==0)
			{
			  $this->M_keuangan_bdd->input_data($data,"tr_bdd");	
			  $this->M_keuangan_bdd->update();
			} else {
			  $this->M_keuangan_bdd->update_data($whereh,$data,'tr_bdd');	
              $this->M_keuangan_bdd->hapus_data($whered,'tr_bddd');				  
			}
			
			$ket      = $this->input->post('ket');
		    $jumlah   = $this->input->post('jumlah');
		   
			$jumdata  = count($kodeakun);
			
			
			$nourut = 1;
			for($i=0;$i<=$jumdata;$i++)
			{
			    $_ket   = $ket[$i];
				
			    $datad = array(
				'nomor_register' => $register,
				'nourut' => $nourut,
				'kode_akun' => '10106',
				'uraian' => $ket[$i],
				'jumlah' => str_replace(',','',$jumlah[$i])
							
			    );
				if ($_ket!="")
			    {
			      $this->M_keuangan_bdd->input_data($datad,'tr_bddd');	
				}  	
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