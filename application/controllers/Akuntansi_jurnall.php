<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Akuntansi_jurnall extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('forM_validation'); 
        $this->load->database(); 
		$this->load->model('M_akuntansi_jurnall','M_akuntansi_jurnall');
		$this->session->set_userdata('menuapp', '200');
		$this->session->set_userdata('submenuapp', '205');
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
			
			$qj ="select jurnal_kode, jurnal_nama from ms_jurnal order by jurnal_kode"; 		
			$this->load->helper('url');		
			$d['unit']  = $this->db->query($qp);		
			$d['jenis'] = $this->db->query($qj);		
			$this->load->view('akuntansi/v_akuntansi_jurnall',$d);
			} else
		{
			header('location:'.base_url());
		}	
	}
	
	public function getakun($kode)
	{
		if(!empty($kode))
		{
		  
			$q = $kode;
			$query = "select * from ms_akun where kodeakun like '%$q%' or namaakun like '%$q%' and tipe=5";
			$data  = $this->db->query($query);
			?>
			
			<table id="myTable">
			  <tr class="header">
				<th style="width:20%;">Kode</th>
				<th style="width:80%;">Nama</th>	
			  </tr> 
			  
			<?php							
			foreach($data->result_array() as $row)
			{ ?>
			   <tr>
				 <td width="50" align="center">
					<a href="#" onclick="post_value('<?php echo $row['kodeakun'];?>','<?php echo $row['namaakun'];?>')">
					
					<?php echo $row['kodeakun'];?></a>
				 </td>	     
				 <td><?php echo $row['namaakun'];?></td>
			   </tr>
			   
			   <?php
			}
			echo "</table>";
		} else
        {
		  echo "";	
		}			
	}
	
	public function getakunname($kode)
	{
		if(!empty($kode))
		{			
			$query = "select namaakun from ms_akun where kodeakun = '$kode'";
			$data  = $this->db->query($query);
			foreach($data->result_array() as $row)
			{
              echo $row['namaakun'];				
			}
		} else
		{
		  echo "";	
		}
	}
	
	public function jurnallist($param)
	{
		if(!empty($param))
		{			
			
			$data  = explode("~",$param);
			$tgl1  = date('Y-m-d',strtotime($data[0]));
			$tgl2  = date('Y-m-d',strtotime($data[1]));
			
			$query = 			
			"select tr_jurnal.*, ms_akun.namaakun from tr_jurnal left outer join ms_akun on tr_jurnal.kodeakun=ms_akun.kodeakun                         
			 where  tr_jurnal.tanggal between '$tgl1' and '$tgl2'
			 order by
			 tr_jurnal.tanggal, tr_jurnal.novoucher ";
			
			
			$d['jurnal'] = $this->db->query($query);
		    $this->load->view('akuntansi/v_akuntansi_jurnallist',$d);	
			
		} else
		{
		  echo "";	
		}
	}
		
		
	public function cetak()
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
		  
		  $this->load->view('master/bank/v_master_bank_prn',$d);				
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
	
	public function simpan_data($x)
	{
		
		$cek = $this->session->userdata('level');
		if(!empty($cek))
		{						
	            				
				$data  = explode("~",$x);
				$kode  = $data[0];
				$debet = $data[1];				
				$kredit= $data[2];
				$debet = str_replace(',','',$debet);
				$kredit= str_replace(',','',$kredit);
							
				$bulan = $this->M_global->_periodebulan();
				$tahun = $this->M_global->_periodetahun();
				$this->M_akuntansi_saldo->update_saldo($tahun,$bulan,$kode,$debet,$kredit);			
		}
		else
		{
			header('location:'.base_url());
		}
	}
	
	public function hapus($x)
	{
		
		$cek = $this->session->userdata('level');
		if(!empty($cek))
		{							            								
		   $this->M_akuntansi_jurnall->hapus_jurnal($x);		   
		}
		else
		{
			header('location:'.base_url());
		}
	}
	
	
	
	
	
}

/* End of file akuntansi_jurnal.php */
/* Location: ./application/controllers/akuntansi_jurnal.php */