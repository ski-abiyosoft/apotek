<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Akuntansi_jurnal_edit extends CI_Controller {

	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation'); 
		$this->load->helper('simkeu_rpt');		
        $this->load->database(); 
		//$this->load->model('akuntansi/m_akuntansi_jurnal','m_akuntansi_jurnal');
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
			$this->load->view('akuntansi/v_akuntansi_jurnal_edit',$d);
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
							
				$bulan = $this->m_global->_periodebulan();
				$tahun = $this->m_global->_periodetahun();
				$this->m_akuntansi_saldo->update_saldo($tahun,$bulan,$kode,$debet,$kredit);			
		}
		else
		{
			header('location:'.base_url());
		}
	}
	
	
	public function jurnal_add()
	{
		$cek = $this->session->userdata('level');
		if(!empty($cek))
		{			
            $nojurnal = $this->m_akuntansi_jurnal->nomor_jurnal($this->session->userdata('unit'),'JU',date('y'),date('m'));	
			$nobukti  = $this->input->post('nomorbukti');
			$data = array(
				'nojurnal' => $nojurnal,
				'novoucher' => $nobukti,
				'tanggal' => date('Y-m-d',strtotime($this->input->post('tanggal'))),
				'noref' => $this->input->post('noref'),
				'keterangan' => $this->input->post('keterangan'),
				'jenis' => $this->input->post('jenis'),
				'kodepasar' => $this->input->post('unit'),				
			);
			
			$this->m_akuntansi_jurnal->insertData("tr_jurnalh",$data);	
            $this->m_akuntansi_jurnal->update();			
			
			$kodeakun = $this->input->post('akun');
			$ket      = $this->input->post('ket');
		    $debet    = $this->input->post('debet');
		    $kredit   = $this->input->post('kredit');
            $tanggal  = $this->input->post('tanggal');
			$jumdata  = count($kodeakun);
			$_tanggal = date('Y-m-d',strtotime($tanggal));
			
			$nourut = 1;
			for($i=0;$i<=$jumdata;$i++)
			{
			   $_debet  = $debet[$i];
			   $_kredit = $kredit[$i];			   
			   $_akun   = $kodeakun[$i];
			   $_ket    = $ket[$i];			   
			   $_debet  = str_replace(',','',$_debet);
			   $_kredit = str_replace(',','',$_kredit);
       
			   if ($_akun!="")
			   {
				   $query = "insert into tr_jurnald(nojurnal, novoucher, nourut, kodeakun, keterangan, debet, kredit, tanggal)
							 values('$nojurnal','$nobukti',$nourut, '$_akun','$_ket',$_debet,$_kredit,'$_tanggal')";				   
				   $this->m_akuntansi_jurnal->manualQuery($query);	
				   $nourut++;
				   
			   }	
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