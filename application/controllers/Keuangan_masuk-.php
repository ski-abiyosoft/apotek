<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Keuangan_masuk extends CI_Controller {

	/**
	 * @author : Enjang RK
	 * @web : http://e-soft.comli.com
	 * @keterangan : Controller untuk modul saldo awal keuangan
	 **/
	
	
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_keuangan_masuk','M_keuangan_masuk');
		$this->load->helper('simkeu_nota');
		$this->session->set_userdata('menuapp', '900');
		$this->session->set_userdata('submenuapp', '903');
	}

	public function index()
	{
		$unit = $this->session->userdata('unit');	
		//if(!empty($unit))
		{
		  $q1 = 
				"select a.terima_register, a.terima_nomor, a.terima_tanggal,  a.terima_uraian, sum(b.terimad_jumlah) as jumlah, a.terima_status
				from
				   tr_penerimaan a,
				   tr_penerimaand b
				where
				   a.terima_nomor=b.terimad_nomor and
				   year(a.terima_tanggal)= (select periode_tahun from ms_identity) and
				   month(a.terima_tanggal)= (select periode_bulan from ms_identity) 
				group by
				   a.terima_tanggal, a.terima_nomor, a.terima_uraian, a.terima_status
				order by
				   a.terima_tanggal, a.terima_nomor desc";	
		}
		
		$level=$this->session->userdata('level');		
		$akses= $this->M_global->cek_menu_akses($level, 903);
		$d['akses']= $akses;	
		$bulan  = $this->M_global->_periodebulan();
        $nbulan = $this->M_global->_namabulan($bulan);
		$periode= 'Periode '.$nbulan.'-'.$this->M_global->_periodetahun();	   
	    $d['keu'] = $this->db->query($q1)->result();
        $d['periode'] = $periode;					
		$this->load->view('keuangan/v_keuangan_masuk',$d);
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
				"select a.terima_register, a.terima_nomor, a.terima_tanggal,  a.terima_uraian, sum(b.terimad_jumlah) as jumlah, a.terima_status
				from
				   tr_penerimaan a,
				   tr_penerimaand b
				where
				   a.terima_nomor=b.terimad_nomor and
				   a.terima_tanggal between '$_tgl1' and '$_tgl2' 
				group by
				   a.terima_tanggal, a.terima_nomor, a.terima_uraian, a.terima_status
				order by
				   a.terima_tanggal, a.terima_nomor desc";	

		  $level=$this->session->userdata('level');		
		  $akses= $this->M_global->cek_menu_akses($level, 903);
		  $d['akses']= $akses;
		  $periode= 'Periode '.date('d-m-Y',strtotime($tgl1)).' s/d '.date('d-m-Y',strtotime($tgl2));	   
	      $d['keu'] = $this->db->query($q1)->result();
          $d['periode'] = $periode;		  
		  $this->load->view('keuangan/v_keuangan_masuk',$d);			   
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
		  
		    $queryh = "select * from tr_penerimaan where terima_register = '$param'";
			$queryd = "select * from tr_penerimaand inner join ms_akun on tr_penerimaand.terimad_akun=ms_akun.kodeakun where terimad_register = '$param'";
			 
		    $header = $this->db->query($queryh)->row();
			$detil  = $this->db->query($queryd)->result();
		    $pdf=new simkeu_nota();
			$pdf->setID($nama_usaha,$alamat1,$alamat2);
			$pdf->setjudul('');
			$pdf->setsubjudul('');
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(70,30,90));
			$border = array('B','','BT');
			$size   = array('','','');
			$pdf->setfont('Arial','B',18);
			$pdf->SetAligns(array('C','C','C'));
			$align = array('L','C','L');
			$style = array('','','B');
			$size  = array('12','','18');
			$max   = array(5,5,20);
			$judul=array('Dari','','Penerimaan');
			$fc     = array('0','0','0');
			$hc     = array('20','20','20');
			$pdf->FancyRow2(10,$judul, $fc,  $border, $align, $style, $size, $max);
			$pdf->ln(1);
			$pdf->setfont('Arial','B',10);
			$pdf->SetWidths(array(70,30,30,5,55));
			$border = array('','','','','');
			$fc     = array('0','0','0','0','0');
			$pdf->SetFillColor(230,230,230);
			//$pdf->settextcolor(10,20,200);
			$pdf->setfont('Arial','',9);
		
			$pdf->FancyRow(array($header->terima_penerima,'','Nomor',':',$header->terima_nomor), $fc, $border);
			$pdf->FancyRow(array('','','Tanggal',':',date('d M Y',strtotime($header->terima_tanggal))), $fc, $border);
			$pdf->FancyRow(array('','','No. Cek',':',''), $fc, $border);			
			$fc     = array('1','0','0','0','0');			
			$pdf->ln(3);
			
			$pdf->SetWidths(array(70,80,5,35));
			$border = array('TB','TB','TB','TB');
			$align  = array('L','L','L','R');
			$pdf->setfont('Arial','B',10);
			//$pdf->SetFillColor(0,0,139);
			//$pdf->settextcolor(255,255,255);
			$fc = array('0','0','0','0');
			$judul=array('Nama Pendapatan','Uraian','','Nilai');
			$pdf->FancyRow2(8,$judul,$fc, $border,$align);
			$border = array('','','','');
			$pdf->setfont('Arial','',10);
			$tot = 0;
			$subtot = 0;
			$tdisc  = 0;
			$border = array('','','','');
			$align  = array('L','L','L','R');
			$fc = array('0','0','0','0');
			$pdf->SetFillColor(0,0,139);
			$pdf->settextcolor(0);
			$tot = 0;			
			foreach($detil as $db)
			{
			  $tot = $tot + $db->terimad_jumlah; 	
			  $pdf->FancyRow(array($db->namaakun, $db->terimad_uraian,'', number_format($db->terimad_jumlah,0,'.',',')),$fc, $border, $align);
			  
			}
			
					
			$pdf->SetFillColor(230);
			$border = array('B','B','B','B');
			$pdf->FancyRow(array('', '', '',''),0,$border);
			$pdf->ln(2);
			$pdf->SetWidths(array(100,20,35,35));
			$border = array('','','TB','TB');
			$align  = array('L','C','L','R');
			//$pdf->SetFillColor(0,0,139);
			//$pdf->settextcolor(255,255,255);
			$fc = array('0','0','0','0');
			$pdf->FancyRow2(7, array('', '','Total', number_format($tot,0,'.',',')),$fc, $border, $align,0);
			$pdf->settextcolor(0);
			$fc = array('0','0','0','0');
			$border = array('','','','');
			$border = array('','','','');
			$pdf->FancyRow(array('', '','', ''),$fc, $border, $align,0);
			$border = array('','','','','','');
			$pdf->SetWidths(array(50,10,50,10,60,10));
			$border = array('','','','','','');
			$align  = array('C','C','C','C','C','C');
			$fc = array('0','0','0','0','0','0');
			$pdf->FancyRow(array('Diserahkan Oleh,','','Diterima Oleh,', '','Diketahui Oleh, ',''),$fc, $border, $align);
			$fc = array('0','0','0','0','0','0');
			$pdf->FancyRow(array('','', '','','',''),$fc, $border, $align);
			$pdf->FancyRow(array('','', '','','',''),$fc, $border, $align);
			
			$style = array('','','','','','');
			$size  = array('','','','','','');
			$border = array('T','','B','','','');
			$pdf->SetFillColor(0,0,139);
			$pdf->settextcolor(255,255,255);
			$pdf->settextcolor(0);
			$fc = array('0','0','0','0','0','0');
			$align  = array('C','C','C','C','C','C');
			$pdf->setfont('Arial','',10);
			$pdf->SetWidths(array(50,10,50,10,60,10));
			$pdf->ln(5);
			$border = array('','','','','','');
			$pdf->FancyRow(array('','', '','','',''),$fc, $border, $align, $style, $size);
			$border = array('B','','B','','B','');
			$pdf->FancyRow(array('','', '','','',''),$fc, $border, $align, $style, $size);
			$border = array('','','','','','');
			$align  = array('L','C','L','C','L','C');
			$pdf->FancyRow(array('Tgl.','','Tgl.','', 'Tgl.',''),$fc, $border, $align, $style, $size);
			$pdf->settextcolor(0);
			$pdf->AliasNbPages();
			$pdf->output($param.'.PDF','I');			
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
		  $page=$this->uri->segment(3);
		  $limit=$this->config->item('limit_data');	
          $d['bank'] = $this->db->get_where('ms_akun',array('akuninduk !=' => '','kelompok' => 'BANK'))->result();
		  $d['unit'] = $this->db->get('ms_unit')->result();
		  //$d['nomor']= $this->M_global->_Autonomor('KM');
		  $this->load->view('keuangan/v_keuangan_masuk_add',$d);				
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
		   $this->M_keuangan_masuk->hapus_penerimaan($nomor);	
           $this->db->delete('tr_jurnal',array('novoucher' => $nomor,'jenis' => 'JK', 'wbs' => 'KM'));			   
		   
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
	public function getakun($kode)
	{
		if(!empty($kode))
		{
		  
			$q = $kode;
			$query = "select * from ms_akun where kodeakun like '%$q%' or namaakun like '%$q%' and akuninduk<>''";
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
	
	public function getnobukti($kode)
	{
		if(!empty($kode))
		{	
            $tahun = $this->M_global->_periodetahun();
			$unit  = $this->session->userdata('unit');
	        if($kode==1)
			{
			  $query = "select max(terima_nomor) as nomor from tr_penerimaan where year(terima_tanggal)=$tahun and terima_kodecbg = '$unit'";
			} else
			{
			  $query = "select max(nomor) as nomor from v_nomorbkk where  tahun='$tahun' and unit='$unit'";	
			} 	
			
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
			
		} else
		{
		  echo "";	
		}
	}
	
	public function penerimaan_save($kode)
	{
		$cek = $this->session->userdata('level');
		if(!empty($cek))
		{			
       	
			$tahun = date('Y',strtotime($this->input->post('tanggal')));
			$bulan = date('m',strtotime($this->input->post('tanggal')));
			$kodebukti = $this->db->get_where('ms_akun', array('kodeakun' => $this->input->post('kasbank')))->row()->kodem;
			$vbukti = $kodebukti.'%';
			$query = 'select * from tr_penerimaan where terima_nomor like "'.$vbukti.'" and year(terima_tanggal)= "'.$tahun.'" and month(terima_tanggal)="'.$bulan.'"';
			$max = $this->db->query($query)->nuM_rows();
			$nourut  = $max+1;
			$nobukti  = $kodebukti.''.$tahun.'.'.$bulan.'.'.str_pad( $nourut, 4, '0', STR_PAD_LEFT );	
			
			$userid   = $this->session->userdata('username');
			
			if($kode==2){
			  $register = $this->input->post('register');			  	
			  $nobukti  = $this->input->post('nomorbukti');	
			}
			
			$data = array(
				'terima_nomor' => $nobukti,
				'terima_tanggal' => date('Y-m-d',strtotime($this->input->post('tanggal'))),
				'terima_kasbank' => $this->input->post('kasbank'),
				'terima_kodecbg' => $this->input->post('unit'),
				'terima_penerima' => $this->input->post('penerima'),
				'terima_uraian' => $this->input->post('keterangan'),
				'terima_status' => 2,
				'terima_userentry' => $userid
				
			);
			
			
			
			
	
	       if($kode==1)
			{
			  $this->M_keuangan_masuk->input_data($data,"tr_penerimaan");	
			  $register = $this->db->insert_id();			  
			} else {
			  $whereh = array(
		       'terima_register' => $register
	          );
			
			  $whered = array(
		      'terimad_register' => $register
	          );	
			  
			  $this->M_keuangan_masuk->update_data($whereh,$data,'tr_penerimaan');	
              $this->M_keuangan_masuk->hapus_data($whered,'tr_penerimaand');              
			}
			
			$kodeakun = $this->input->post('akun');
			$ket      = $this->input->post('ket');
		    $jumlah   = $this->input->post('jumlah');
		   
			$jumdata  = count($kodeakun);
			
			$this->db->delete('tr_jurnal',array('novoucher' => $nobukti,'jenis' => 'JK', 'wbs' => 'KM'));			
			$nourut = 1;
			$total  = 0;
			for($i=0;$i<=$jumdata-1;$i++)
			{
			    $_akun   = $kodeakun[$i];
				$total   = $total + str_replace(',','',$jumlah[$i]);
				
			    $datad = array(
				'terimad_register' => $register,
				'terimad_nomor' => $nobukti,
				'terimad_nourut' => $nourut,
				'terimad_akun' => $kodeakun[$i],
				'terimad_uraian' => $ket[$i],
				'terimad_jumlah' => str_replace(',','',$jumlah[$i])
							
			    );
				if ($_akun!="")
			    {
			      $this->M_keuangan_masuk->input_data($datad,'tr_penerimaand');	
				  
				  $data_jurnal = array (
					   'novoucher' => $nobukti,
					   'noref' => $register,
					   'tanggal' => date('Y-m-d',strtotime($this->input->post('tanggal'))),
					   'keterangan' => $this->input->post('keterangan'),
					   'nourut' => $nourut+1,
					   'jenis' => 'JK',
					   'wbs' => 'KM',
					   'kodeakun' => $kodeakun[$i],
					   'debet' => 0,
					   'kredit' => str_replace(',','',str_replace(',','',$jumlah[$i])),
					   
					   'userid' => $userid,			
					);
					$this->db->insert('tr_jurnal',$data_jurnal);	
					
				  $nourut++;
				}  	
			}
			$data_jurnal = array (
			   'novoucher' => $nobukti,
			   'noref' => $register,
			   'tanggal' => date('Y-m-d',strtotime($this->input->post('tanggal'))),
			   'keterangan' => $this->input->post('keterangan'),
			   'nourut' => 1,
			   'jenis' => 'JK',
			   'wbs' => 'KM',
			   'kodeakun' => $this->input->post('kasbank'),
			   'debet' => $total,
			   'kredit' => 0,			   
			   'userid' => $userid,			
			);
			$this->db->insert('tr_jurnal',$data_jurnal);	
			//$this->M_global->_updatecounter1('JU');
						
			echo $nobukti;
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
			
			
					
			$qheader ="select * from tr_penerimaan where terima_register = '$nomor'"; 		
			$qdetil ="select * from tr_penerimaand where terimad_register = '$nomor'"; 		
					
			
			$d['header'] = $this->db->query($qheader);
			$d['detil'] = $this->db->query($qdetil)->result();
			$d['jumdata'] = $this->db->query($qdetil)->nuM_rows();	

            $data=$this->db->query($qheader);
			foreach($data->result() as $row){
			$kasbank = $row->terima_kasbank;
			$d['tanggal']=$row->terima_tanggal;
			$d['register']=$row->terima_register;
		    $d['nomor']=$row->terima_nomor;
			$d['uraian']=$row->terima_uraian;
			$d['unit']=$row->terima_kodecbg;
			$d['kasbank']=$row->terima_kasbank;
			$d['penerima']=$row->terima_penerima;	
			$d['userentry']=$row->terima_userentry;	
			
            }

           
			$d['bank'] = $this->db->get_where('ms_akun',array('akuninduk !=' => '','kelompok' => 'BANK'))->result();
		    $d['unit'] = $this->db->get('ms_unit')->result();
			$this->load->view('keuangan/v_keuangan_masuk_edit',$d);
			} else
		{
			header('location:'.base_url());
		}	
	}
}
/* End of file keuangan_saldo.php */
/* Location: ./application/controllers/keuangan_saldo.php */