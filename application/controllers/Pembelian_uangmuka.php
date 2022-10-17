<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_uangmuka extends CI_Controller {

	/**
	 * @author : Enjang RK
	 * @web : http://e-soft.comli.com
	 * @keterangan : Controller untuk modul saldo awal keuangan
	 **/
	
	
	
	
	public function __construct()
	{
		parent::__construct();
		$this->session->set_userdata('menuapp', '300');
		$this->session->set_userdata('submenuapp', '324');
		$this->load->helper('simkeu_nota');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        		
		if(!empty($cek))
		{	
		  	 
		  $q1 = 
				"select a.kodeum, a.nomorfaktur, a.tglum, b.nama, a.keterangan, a.statusid, a.tgljthtempo, a.jumlahum as total
				from
				   ap_uangmuka a,
				   ap_supplier b
				where
				   a.kodesup=b.kode and
				   year(a.tglum)= (select periode_tahun from ms_identity) and
				   month(a.tglum)= (select periode_bulan from ms_identity) and
				   a.kodecbg = '$unit'
				order by
				   a.tglum, a.kodepo desc";

		  $bulan  = $this->M_global->_periodebulan();
          $nbulan = $this->M_global->_namabulan($bulan);
		  $periode= 'Periode '.$nbulan.'-'.$this->M_global->_periodetahun();	   
	      $d['keu'] = $this->db->query($q1)->result();
          $d['periode'] = $periode;		  
		  $level=$this->session->userdata('level');		
		  $akses= $this->M_global->cek_menu_akses($level, 324);
		  $d['akses']= $akses;
		  $this->load->view('pembelian/v_pembelian_uangmuka',$d);			   
		
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
				"select a.kodeum, a.nomorfaktur, a.tglum, b.nama, a.keterangan, a.statusid, a.tgljthtempo, a.jumlahum as total
				from
				   ap_uangmuka a,
				   ap_supplier b
				where
				   a.kodesup=b.kode and
				   a.tglum between '$_tgl1' and '$_tgl2' and 				   
				   a.kodecbg = '$unit'
				order by
				   a.tglum, a.kodepo desc";

		  
		  $periode= 'Periode '.date('d-m-Y',strtotime($tgl1)).' s/d '.date('d-m-Y',strtotime($tgl2));	   
	      $d['keu'] = $this->db->query($q1)->result();
          $d['periode'] = $periode;		  
		  $level=$this->session->userdata('level');		
		  $akses= $this->M_global->cek_menu_akses($level, 324);
		  $d['akses']= $akses;
		  $this->load->view('pembelian/v_pembelian_uangmuka',$d);			   
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
		  
		    $queryh = "select * from ap_uangmuka inner join ap_supplier on ap_uangmuka.kodesup=ap_supplier.kode where kodeum = '$param'";
			 
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
			$judul=array('Kepada :','','Uang Muka Pembelian');
			$fc     = array('0','0','0');
			$hc     = array('20','20','20');
			$pdf->FancyRow2(10,$judul, $fc,  $border, $align, $style, $size, $max);
			$pdf->ln(1);
			$pdf->setfont('Arial','B',10);
			$pdf->SetWidths(array(70,30,30,5,55));
			$border = array('','','','','');
			$fc     = array('0','0','0','0','0');
			$pdf->SetFillColor(230,230,230);			
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
			//$pdf->SetFillColor(230,230,230);
			//$pdf->settextcolor(0);
			$fc = array('0','0','0','0');
			//$tot = $header->jumlahum+$header->ppn;
			$tot = $header->jumlahum;
			$pdf->FancyRow(array('Keterangan', '', 'Uang Muka',number_format($header->jumlahum,0,'.',',')),$fc, $border, $align,0);
			$border = array('','','','');
			//$pdf->FancyRow(array($header->keterangan, '', 'PPN 10%',number_format($header->ppn,0,'.',',')),$fc, $border, $align,0);
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
        $data = $this->db->select('ap_podetail.*, inv_barang.namabarang')->join('inv_barang','inv_barang.kodeitem=ap_podetail.kodeitem','left')->get_where('ap_podetail',array('kodepo' => $po))->result();
		echo json_encode($data);
	}
	
	public function getbiaya($po)
	{   	    
        $data = $this->db->select('ap_pobiaya.*, ms_akun.namaakun')->join('ms_akun','ms_akun.kodeakun=ap_pobiaya.kodeakun','left')->get_where('ap_pobiaya',array('kodepo' => $po))->result();
		echo json_encode($data);
	}
	
	public function getlistpo( $supp )
	{
		if(!empty($supp))
		{
		    //$po  = $this->db->get_where('ap_pofile',array('kodesup' => $supp, 'statusid' => 1))->result();	
			$po = $this->db->query("select * from ap_pofile where kodepo not in(select kodepo from ap_uangmuka)")->result();
			?>	
           <select name="kodepo" id="kodepo" class="form-control input-large select2me"  >            											
			  <option value="">-- Tanpa PO ---</option>
			  <?php 			    
				foreach($po  as $row){	
				?>
				<option value="<?php echo $row->kodepo;?>"><?php echo $row->kodepo;?></option>
				
			  <?php } ?>
			</select>
			
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
          $d['supp'] = $this->db->order_by('nama')->get_where('ap_supplier',array('nama !=' => ''))->result();
		  $d['bank'] = $this->db->get_where('ms_akun',array('akuninduk !=' => '','kelompok' => 'BANK'))->result();
		  $d['unit'] = $this->db->get('ms_unit')->result();
		  $d['nomor']= $this->M_global->_Autonomor('PM');
		  $this->load->view('pembelian/v_pembelian_uangmuka_add',$d);				
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
		   $this->db->delete('ap_uangmuka',array('kodeum' => $nomor));
		   
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
	
	
	public function getpoheader( $kodepo )
	{
		$data = $this->db->get_where('ap_pofile',array('kodepo' => $kodepo))->row();
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
				'kodesup' => $this->input->post('supp'),
				'kodeum'  => $this->input->post('nomorbukti'),
				'nomorfaktur'  => $this->input->post('nomorfaktur'),
				'kodepo'  => $this->input->post('kodepo'),
				'tglum'   => date('Y-m-d',strtotime($this->input->post('tanggal'))),
				'kodeuser'=> $userid,
				'keterangan'=> $this->input->post('keterangan'),
				'jumlahum'=> $this->input->post('uangmuka'),
				'jumlahpo'=> $this->input->post('jumlahpo'),
				'kodebank'=> $this->input->post('kasbank'),
				'norektujuan'=> $this->input->post('norek'),
				'statusid'=> 1,			
			);
						
			$profile = $this->M_global->_LoadProfileLap();	
			$akun_debet    = $profile->akun_uangmuka;
			$akun_kredit   = $this->input->post('kasbank');
						
			$this->M_global->_hapusjurnal($this->input->post('nomorbukti'), 'UB');	
			
			$this->M_global->_rekamjurnal(
			date('Y-m-d',strtotime($this->input->post('tanggal'))),
			$this->input->post('nomorbukti'),
			'UB',
			$this->input->post('kodepo'),
			1,
			$akun_debet,
			'UM Pembelian ',
			'UM Pembelian '.$this->input->post('kodepo'),
			$this->input->post('uangmuka'),
			0
			);
			
			$this->M_global->_rekamjurnal(
			date('Y-m-d',strtotime($this->input->post('tanggal'))),
			$this->input->post('nomorbukti'),
			'UB',
			$this->input->post('kodepo'),
			2,
			$akun_kredit,
			'UM Pembelian',
			'UM Pembelian '.$this->input->post('kodepo'),
			0,
			$this->input->post('uangmuka')			
			);
			
			if($param==1)
			{
			  $this->db->insert('ap_uangmuka',$data);	
			  $this->M_global->_updatecounter1('PM');		  
			} else {
			  $this->db->update('ap_uangmuka',$data, array('kodeum' => $nobukti));				 
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
			$header = $this->db->get_where('ap_uangmuka', array('kodeum' => $nomor));
			
			$d['header']  = $header->result();
			$d['supp'] = $this->db->order_by('nama')->get_where('ap_supplier',array('nama !=' => ''))->result();
			$d['bank'] = $this->db->get_where('ms_akun',array('akuninduk !=' => '','kelompok' => 'BANK'))->result();
		    $d['unit'] = $this->db->get('ms_unit')->result();
			$this->load->view('pembelian/v_pembelian_uangmuka_edit',$d);
			} else
		{
			header('location:'.base_url());
		}	
	}
}
/* End of file keuangan_saldo.php */
/* Location: ./application/controllers/keuangan_saldo.php */