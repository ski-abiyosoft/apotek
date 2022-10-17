<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Keuangan_transfer extends CI_Controller {

	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_keuangan_transfer','M_keuangan_transfer');
		$this->load->helper('simkeu_nota');
		$this->session->set_userdata('menuapp', '5000');
		$this->session->set_userdata('submenuapp', '5102');
	}

	public function index()
	{
		$unit = $this->session->userdata('unit');	
		//if(!empty($unit))
		{
		  $q1 = 
				 "select m.*, a.`acname` AS acdari_name, ac.`acname` AS acke_name
					from
					   tbl_mutasikas m 
					   LEFT JOIN tbl_accounting a ON m.`acdari` = a.`accountno`
					   LEFT JOIN tbl_accounting ac ON m.`acke` = ac.`accountno`
					where
					   koders = '$unit' AND
					   DATE_FORMAT(tglmutasi, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d')
					order by
					   id desc";

		  $level=$this->session->userdata('level');		
		  $akses= $this->M_global->cek_menu_akses($level, 5102);
		  $d['akses']= $akses;				   
	      $d['keu'] = $this->db->query($q1)->result();		
		  $this->load->view('keuangan/v_keuangan_transfer',$d);			   
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
		   $q1 = "select m.*, a.`acname` AS acdari_name, ac.`acname` AS acke_name
					from
						tbl_mutasikas m 
						LEFT JOIN tbl_accounting a ON m.`acdari` = a.`accountno`
						LEFT JOIN tbl_accounting ac ON m.`acke` = ac.`accountno`
					where
					   koders = '$unit' and
					   tglmutasi between '$_tgl1' and '$_tgl2'
					order by
					   id desc";
				   

		  $level=$this->session->userdata('level');		
		  $akses= $this->M_global->cek_menu_akses($level, 5102);
		  $d['akses']= $akses;				   
	      $d['keu'] = $this->db->query($q1)->result();		
		  $periode= 'Periode '.date('d-m-Y',strtotime($tgl1)).' s/d '.date('d-m-Y',strtotime($tgl2));	   
	      $d['periode'] = $periode;		  
		  $this->load->view('keuangan/v_keuangan_transfer',$d);		
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
		  
		    $queryh = "select * from  tbl_mutasikas where nomutasi = '$param'";
			 
		    $header = $this->db->query($queryh)->row();
			$pdf=new simkeu_nota();
			$pdf->setID($nama_usaha,$alamat1,$alamat2);
			$pdf->setjudul('');
			$pdf->setsubjudul('');
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(70,30,90));
			$border = array('','','BT');
			$size   = array('','','');
			$pdf->setfont('Arial','B',18);
			$pdf->SetAligns(array('C','C','C'));
			$align = array('L','C','L');
			$style = array('','','B');
			$size  = array('12','','18');
			$max   = array(5,5,20);
			$judul=array('','','Transfer Bank');
			$fc     = array('0','0','0');
			$hc     = array('20','20','20');
			$pdf->FancyRow2(10,$judul, $fc,  $border, $align, $style, $size, $max);
			$pdf->ln(1);
			$pdf->setfont('Arial','B',10);
			$pdf->SetWidths(array(70,30,30,5,55));
			$border = array('','','','','');
			$fc     = array('0','0','1','1','1');
			$pdf->SetFillColor(230,230,230);
			//$pdf->settextcolor(10,20,200);
			$pdf->setfont('Arial','',10);
		
			$pdf->FancyRow(array('','','Nomor',':',$header->nomutasi), $fc, $border);
			$pdf->FancyRow(array('','','Tanggal',':',date('d-m-Y',strtotime($header->tglmutasi))), $fc, $border);			
			$fc     = array('1','0','0','0','0');			
			$pdf->ln(3);
			$border = array('T','T','T','T','T');
			$fc     = array('0','0','0','0','0');
			$pdf->SetLineWidth(1);
			$pdf->FancyRow(array('','','','',''), $fc, $border);			
			$pdf->SetLineWidth(0);
			
			$pdf->SetWidths(array(90,10,90));
			$border = array('TB','','TB');
			$fc     = array('0','0','0');
			$pdf->FancyRow(array('Dari Kas/Bank','','Ke Kas/Bank'), $fc, $border);
			//$pdf->ln(3);
			$pdf->SetWidths(array(30,60,10,30,60));
			$align  = array('L','L','','L','L');
			$border = array('','','','','');
			$fc     = array('1','1','0','1','1');
			$pdf->SetFillColor(230,230,230);
			$terbilang = ucwords($this->M_global->terbilang($header->mutasirp)).' Rupiah';
			$banks     = $this->M_global->_namaakun($header->acdari);
			$bankt     = $this->M_global->_namaakun($header->acke);
			$pdf->FancyRow(array('Kas/Bank',$banks,'','Kas/Bank',$bankt), $fc, $border);
			$pdf->FancyRow(array('Nilai Transfer',number_format($header->mutasirp,0,'.',','),'','Nilai Transfer',number_format($header->mutasirp,0,'.',',')), $fc, $border);
			$pdf->FancyRow(array('Terbilang',$terbilang,'','Terbilang',$terbilang), $fc, $border);
			$pdf->FancyRow(array('','','','',''), $fc, $border);
			
			$pdf->ln(5);			
			$pdf->SetWidths(array(90,10,30,60));
			$border = array('TB','','TB','TB');
			$fc     = array('0','0','1','1');
			$align  = array('L','','L','R');
			$pdf->SetFillColor(230,230,230);
			$pdf->settextcolor(0);
			
			$pdf->FancyRow(array('Keterangan', '', 'Biaya Transfer',number_format(0,0,'.',',')),$fc, $border, $align,0);			
			$border = array('','','','');
			$pdf->FancyRow(array($header->keterangan,'', '', ''),$fc, $border, $align);
			$fc = array('0','0','0','0');
			$pdf->FancyRow(array('','', '', ''),$fc, $border, $align);
			$pdf->FancyRow(array('','', '', ''),$fc, $border, $align);
			
			$style = array('','','B','B');
			$size  = array('','','','');
			$border = array('T','','','');
			$pdf->SetFillColor(0,0,139);
			$pdf->settextcolor(255,255,255);
			$fc = array('0','0','0','0');
			$pdf->FancyRow(array('','', '', ''),$fc, $border, $align, $style, $size);
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
          $d['nomor']= 'Auto';		  
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
		   $this->db->delete('tbl_mutasikas', array('nomutasi' => $nomor));	
		   //$this->db->delete('tr_jurnal',array('noref' => $nomor,'jenis' => 'KT', 'wbs' => 'KT'));	           
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	public function transfer_save( $jenis )
	{
		$cek = $this->session->userdata('level');
		if(!empty($cek))
		{			
			
		    if($jenis==1){
			  $nobukti  = urut_transaksi('URUT_MUTASI',19);
			} else {
			  $nobukti  = $this->input->post('nomorbukti');
			}
			$userid   = $this->session->userdata('username');
			$unit      = $this->session->userdata('unit');
			$data = array(
			    'koders' => $unit,
				'nomutasi'  => $nobukti,
				'tglmutasi' => date('Y-m-d',strtotime($this->input->post('tglmutasi'))),
				'acdari' => $this->input->post('acdari'),
				'acke' => $this->input->post('acke'),
				'cekno' => $this->input->post('cekno'),
				'mutasirp' => str_replace(',','',$this->input->post('mutasirp')),
				'admrp' => str_replace(',','',$this->input->post('admrp')),
				'acbiaya' => $this->input->post('acbiaya'),
				'keterangan' => $this->input->post('keterangan'),
				'username' => $userid,			
			);
			
			$where = array(
		    'nomutasi' => $nobukti
	        );
			
	        if($jenis==1)
			{
			  $this->db->insert("tbl_mutasikas", $data);	
			} else {
			  $this->db->update('tbl_mutasikas', $data, $where);
			}
			
			echo $nobukti;
			
			/*
		    
			$this->db->delete('tr_jurnal',array('noref' => $nobukti,'jenis' => 'KT', 'wbs' => 'KT'));	
			$data_jurnal = array (
			   'novoucher' => $this->M_global->_Autonomor('JU'),
			   'noref' => $nobukti,
			   'tanggal' => date('Y-m-d',strtotime($this->input->post('tanggal'))),
			   'keterangan' => $this->input->post('keterangan'),
			   'nourut' => 1,
			   'jenis' => 'KT',
			   'kodeakun' => $this->input->post('tujuan'),
			   'debet' => str_replace(',','',$this->input->post('jumlah')),
			   'kredit' => 0,
			   'userid' => $userid,			
			);
			$this->db->insert('tr_jurnal',$data_jurnal);
			
			$data_jurnal = array (
			   'novoucher' => $this->M_global->_Autonomor('JU'),
			   'noref' => $nobukti,
			   'tanggal' => date('Y-m-d',strtotime($this->input->post('tanggal'))),
			   'keterangan' => $this->input->post('keterangan'),
			   'nourut' => 2,
			   'jenis' => 'KT',
			   'kodeakun' => $this->input->post('sumber'),
			   'kredit' => str_replace(',','',$this->input->post('jumlah')),
			   'debet' => 0,
			   'userid' => $userid,			
			);
			$this->db->insert('tr_jurnal',$data_jurnal);
			*/
			
			
								
			
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
					
			$qheader ="select * from tbl_mutasikas where nomutasi = '$nomor'"; 		
			
			$d['header'] = $this->db->query($qheader);
            $data=$this->db->query($qheader);
			
			if(count($data->result()) != 0){
				foreach($data->result() as $row){
					$d['tglmutasi']	=$row->tglmutasi;
					$d['acdari']	=$row->acdari;
					$d['acke']		=$row->acke;
					$d['cekno']		=$row->cekno;
					$d['mutasirp']	=$row->mutasirp;
					$d['admrp']		=$row->admrp;
					$d['acbiaya']	=$row->acbiaya;
					$d['keterangan']=$row->keterangan;	
				}
			
				$d['nomorbukti']=$nomor;
				
				$this->load->view('keuangan/v_keuangan_transfer_edit',$d);
			} else {
				header('location:'.base_url());
			}
		} else
		{
			header('location:'.base_url());
		}	
	}

	public function export()
	{
		
		$cek	= $this->session->userdata('level');	
		$unit	= $this->session->userdata('unit');	
		
		$url	= explode("/",$_SERVER['REQUEST_URI']);
		
		// print_r($url);
		// echo count($url);
		
		$parstgl= explode("~",$url[count($url)-1]);
		
		$tgl = "and DATE_FORMAT(tglmutasi, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d')";
		if(count($url) == 6){
			$tgl = "and DATE_FORMAT(tglmutasi, '%Y-%m-%d') >= '".$parstgl[1]."' AND DATE_FORMAT(tglmutasi, '%Y-%m-%d') <= '".$parstgl[2]."'";
		} 

		if(!empty($cek))
		{				  
			$query = "
				select *
				from
					tbl_mutasikas
				where
					koders = '$unit'
					$tgl
					order by
					id desc
			";

        	$d['data'] = $this->db->query($query)->result(); 
			// print_r($d['master_akun']);
			$this->load->view('keuangan/v_keuangan_transfer_exp',$d);
		}
		else
		{
			header('location:'.base_url());
		}
	}

}
/* End of file keuangan_saldo.php */
/* Location: ./application/controllers/keuangan_saldo.php */