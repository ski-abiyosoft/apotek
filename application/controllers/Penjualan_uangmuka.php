<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penjualan_uangmuka extends CI_Controller {

	/**
	 * @author : Enjang RK	 
	 * @keterangan : Controller untuk modul Uang Muka Penjualan
	 **/
	
	
	
	
	public function __construct()
	{
		parent::__construct();
		$this->session->set_userdata('menuapp', '400');
		$this->session->set_userdata('submenuapp', '424');
		$this->load->helper('simkeu_nota');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        		
		if(!empty($cek))
		{	
		  	 
		  $q1 = 
				"select a.kodeum,  a.tglum, b.nama, a.keterangan, a.statusid, a.tgljthtempo, a.jumlahum as total
				from
				   ar_uangmuka a,
				   ar_customer b
				where
				   a.kodecust=b.kode and
				   year(a.tglum)= (select periode_tahun from ms_identity) and
				   month(a.tglum)= (select periode_bulan from ms_identity) and
				   a.kodecbg = '$unit'
				order by
				   a.tglum, a.kodeso desc";

		  $bulan  = $this->M_global->_periodebulan();
          $nbulan = $this->M_global->_namabulan($bulan);
		  $periode= 'Periode '.$nbulan.'-'.$this->M_global->_periodetahun();	   
	      $d['keu'] = $this->db->query($q1)->result();
		  $level=$this->session->userdata('level');		
		  $akses= $this->M_global->cek_menu_akses($level, 424);
		  $d['akses']= $akses;
          $d['periode'] = $periode;		  
		  $this->load->view('penjualan/v_penjualan_uangmuka',$d);			   
		
		} else
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
				$q1 = 
				"select a.kodeum,  a.tglum, b.nama, a.keterangan, a.statusid, a.tgljthtempo, a.jumlahum as total
				from
				   ar_uangmuka a,
				   ar_customer b
				where
				   a.kodecust=b.kode and
				   a.tglum between '$_tgl1' and '$_tgl2' and 				   
				   a.kodecbg = '$unit'
				order by
				   a.tglum, a.kodeso desc";

		  
		  $periode= 'Periode '.date('d-m-Y',strtotime($tgl1)).' s/d '.date('d-m-Y',strtotime($tgl2));	   
	      $d['keu'] = $this->db->query($q1)->result();
		  $level=$this->session->userdata('level');		
		  $akses= $this->M_global->cek_menu_akses($level, 424);
		  $d['akses']= $akses;
          $d['periode'] = $periode;		  
		  $this->load->view('penjualan/v_penjualan_uangmuka',$d);			   
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
            $profile = $this->M_global->_LoadProfileLap();	
		    $unit= $this->session->userdata('unit');	 
		    $nama_usaha=$profile->nama_usaha;
			$alamat1  = $profile->alamat1;
			$alamat2  = $profile->alamat2;
		  
		    $queryh = "select * from ar_uangmuka inner join ar_customer on ar_uangmuka.kodecust=ar_customer.kode where kodeum = '$param'";
			 
		    $header = $this->db->query($queryh)->row();			
		    $pdf=new simkeu_nota();
			$pdf->setID($nama_usaha,$alamat1,$alamat2);
			$pdf->setjudul('');
			$pdf->setsubjudul('');
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(70,30,90));
			$border = array('T','','BT');
			$size   = array('','','');
			$pdf->setfont('Arial','B',18);
			$pdf->SetAligns(array('C','C','C'));
			$align = array('L','C','L');
			$style = array('','','B');
			$size  = array('12','','18');
			$max   = array(5,5,20);
			$judul=array('Kepada :','','Uang Muka Penjualan');
			$fc     = array('0','0','0');
			$hc     = array('20','20','20');
			$pdf->FancyRow2(10,$judul, $fc,  $border, $align, $style, $size, $max);
			$pdf->ln(1);
			$pdf->setfont('Arial','B',10);
			$pdf->SetWidths(array(70,30,30,5,55));
			$border = array('','','','','');
			$fc     = array('0','0','0','0','0');
			//$pdf->SetFillColor(230,230,230);			
			$pdf->setfont('Arial','',9);
			$pdf->FancyRow(array($header->nama,'','Nomor',':',$header->kodeum), $fc, $border);
			$pdf->FancyRow(array($header->alamat1,'','Tanggal',':',date('d M Y',strtotime($header->tglum))), $fc, $border);
			$fc     = array('0','0','0','0','0');
			$pdf->FancyRow(array($header->alamat2,'','','',''), $fc, $border);
			$pdf->FancyRow(array($header->telp,'','','',''), $fc, $border);
			
			$pdf->ln(2);
									
			$pdf->SetWidths(array(100,20,30,40));
			$border = array('TB','','T','T');
			$align  = array('L','','L','R');
			$pdf->SetFillColor(230,230,230);
			$pdf->settextcolor(0);
			$fc = array('0','0','1','1');
			$tot = $header->jumlahum+$header->ppn;
			$pdf->FancyRow(array('Keterangan', '', 'Uang Muka',number_format($header->jumlahum,0,'.',',')),$fc, $border, $align,0);
			$border = array('','','','');
			$pdf->FancyRow(array($header->keterangan, '', 'PPN 10%',number_format($header->ppn,0,'.',',')),$fc, $border, $align,0);
			$style = array('','','B','B');
			$size  = array('','','','');
			$border = array('','','BT','BT');
			//$pdf->SetFillColor(0,0,139);
			//$pdf->settextcolor(255,255,255);
			$fc = array('0','0','0','0');
			$pdf->FancyRow(array('','', 'Total', number_format($tot,0,'.',',')),$fc, $border, $align, $style, $size);
			$fc = array('0','0','0','0');
			$border = array('B','','','');
			$pdf->FancyRow(array('','', '', ''),$fc, $border, $align, $style, $size);
			$pdf->settextcolor(0);
			$pdf->SetWidths(array(50,50));
			$pdf->SetFont('Arial','',9);
			$pdf->SetAligns(array('C','C'));
			$pdf->ln(5);
			
			$border = array('','');
			$align  = array('C','C');
			$pdf->FancyRow(array('Disiapkan Oleh','Disetujui Oleh'),0,$border, $align);
			$pdf->ln(1);
			$pdf->ln(15);
			$pdf->SetWidths(array(49,2,49));
			$pdf->SetFont('Arial','',8);
			$pdf->SetAligns(array('C','C','C'));
			$border = array('B','','B');	
			$pdf->FancyRow(array('','',''),0,$border,$align);
			$border = array('','','');	
			$align  = array('L','C','L');
			$pdf->FancyRow(array('Tgl.','','Tgl.'),0,$border,$align);
	
		

			$pdf->AliasNbPages();
			$pdf->output($param.'.PDF','I');			
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	public function getpo($po)
	{   	    
        $data = $this->db->select('ar_sodetail.*, inv_barang.namabarang')->join('inv_barang','inv_barang.kodeitem=ar_sodetail.kodeitem','left')->get_where('ar_sodetail',array('kodeso' => $po))->result();
		echo json_encode($data);
	}
	
	public function getbiaya($po)
	{   	    
        $data = $this->db->select('ar_sobiaya.*, ms_akun.namaakun')->join('ms_akun','ms_akun.kodeakun=ar_sobiaya.kodeakun','left')->get_where('ar_sobiaya',array('kodeso' => $po))->result();
		echo json_encode($data);
	}
	
	public function getlistpo( $supp )
	{
		if(!empty($supp))
		{
		    $po = $this->db->query("select * from ar_sofile where kodeso not in(select kodeso from ar_uangmuka)")->result();
			?>						
			<select name="kodeso" id="kodeso" class="form-control  input-medium select2me"  >            											
			  <option value="">-- Tanpa SO ---</option>
			  <?php 			    
				foreach($po  as $row){	
				?>
				<option value="<?php echo $row->kodeso;?>"><?php echo $row->kodeso;?></option>
				
			  <?php } ?>
			</select>
			<span class="input-group-btn">
				<a class="btn blue" onclick="getpoheader();"><i class="fa fa-download"></i></a>
			</span>	
			</div>
			<?php											  
			
		} else
        {
		  echo "";	
		}			
	}
	
	public function entri()
	{
		$cek = $this->session->userdata('level');		
		$uid = $this->session->userdata('unit');		
		if(!empty($cek))
		{				  
		  $page=$this->uri->segment(3);
		  $limit=$this->config->item('limit_data');	
          $d['cust'] = $this->db->order_by('nama')->get_where('ar_customer',array('nama !=' => ''))->result();
		  $d['bank'] = $this->db->get_where('ms_akun',array('akuninduk !=' => '','kelompok' => 'BANK'))->result();
		  $d['unit'] = $this->db->get('ms_unit')->result();
		  $d['nomor']= $this->M_global->_Autonomor('SM');
		  $this->load->view('penjualan/v_penjualan_uangmuka_add',$d);				
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
		   $this->db->delete('ar_uangmuka',array('kodeum' => $nomor));
		   
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
	
	
	public function getpoheader( $kodepo )
	{
		$data = $this->db->get_where('ar_sofile',array('kodeso' => $kodepo))->row();
		echo json_encode($data);
	}
	
	public function getbarangname($kode)
	{
		if(!empty($kode))
		{			
	        $query = "select namabarang from inv_barang where kodeitem = '$kode'";
			$data  = $this->db->query($query);
			foreach($data->result_array() as $row)
			{
              echo $row['namabarang'];				
			}
		} else
		{
		  echo "";	
		}
	}
	
	
	public function save($param)
	{
		$hasil = 0;
		$cek = $this->session->userdata('level');
		if(!empty($cek))
		{			            
			$nobukti  = $this->input->post('nomorbukti');			
			$userid   = $this->session->userdata('username');
					
			
			$data = array(
			    'kodecbg' => $this->session->userdata('unit'),
				'kodecust' => $this->input->post('cust'),
				'kodeum'  => $this->input->post('nomorbukti'),				
				'kodeso'  => $this->input->post('kodeso'),
				'tglum'   => date('Y-m-d',strtotime($this->input->post('tanggal'))),
				'tgljthtempo'   => date('Y-m-d',strtotime($this->input->post('tanggal'))),
				'kodeuser'=> $userid,
				'keterangan'=> $this->input->post('keterangan'),
				'jumlahum'=> $this->input->post('uangmuka'),
				'jumlahso'=> $this->input->post('jumlahso'),
				'kodebank'=> $this->input->post('kasbank'),
				'nomorkartu'=> $this->input->post('nokartu'),
				'statusid'=> 1,			
			);
			
			
			$profile = $this->M_global->_LoadProfileLap();			
			$akun_kredit    = $profile->akun_uangmukajual;
			$akun_debet     = $this->input->post('kasbank');
						
			if($param==2){
			   $this->M_global->_hapusjurnal($this->input->post('nomorbukti'), 'UJ');	
			}
			
			$this->M_global->_rekamjurnal(
			date('Y-m-d',strtotime($this->input->post('tanggal'))),
			$this->input->post('nomorbukti'),
			'UJ',
			$this->input->post('kodeso'),
			1,
			$akun_debet,
			'UM Penjualan ',
			'UM Penjualan '.$this->input->post('kodeso'),
			$this->input->post('uangmuka'),
			0
			);
			
			$this->M_global->_rekamjurnal(
			date('Y-m-d',strtotime($this->input->post('tanggal'))),
			$this->input->post('nomorbukti'),
			'UJ',
			$this->input->post('kodeso'),
			2,
			$akun_kredit,
			'UM Penjualan',
			'UM Penjualan '.$this->input->post('kodeso'),
			0,
			$this->input->post('uangmuka')			
			);
						
			
			if($param==1)
			{
			  $this->db->insert('ar_uangmuka',$data);	
			  $this->M_global->_updatecounter1('SM');		  
			} else {
			  $this->db->update('ar_uangmuka',$data, array('kodeum' => $nobukti));				 
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
			$header = $this->db->get_where('ar_uangmuka', array('kodeum' => $nomor));			
			$d['header']  = $header->result();
			$d['cust'] = $this->db->order_by('nama')->get_where('ar_customer',array('nama !=' => ''))->result();
			$d['bank'] = $this->db->get_where('ms_akun',array('akuninduk !=' => '','kelompok' => 'BANK'))->result();
		    $d['unit'] = $this->db->get('ms_unit')->result();
			$this->load->view('penjualan/v_penjualan_uangmuka_edit',$d);
			} else
		{
			header('location:'.base_url());
		}	
	}
}
/* End of file keuangan_saldo.php */
/* Location: ./application/controllers/keuangan_saldo.php */