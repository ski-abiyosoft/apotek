<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Keuangan_masuk extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_keuangan_masuk','M_keuangan_masuk');
		$this->load->helper('simkeu_rpt');		
		$this->load->helper('simkeu_nota');
		$this->session->set_userdata('menuapp', '5000');
		$this->session->set_userdata('submenuapp', '5103');
		
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		
		
				
		if(!empty($cek))
		{
		  $level=$this->session->userdata('level');		
		  $akses= $this->M_global->cek_menu_akses($level, 5103);		
		  $this->load->helper('url');		  
		  $data['tanggal'] = date('d-m-Y');
		  $data['akses']= $akses;	
		  $this->load->view('keuangan/v_keuangan_masuk',$data);
		} else
		{
			header('location:'.base_url());
		}			
	}
	
	public function entri()
	{
	    $cek = $this->session->userdata('level');		
		if(!empty($cek))
		{	
			$unit = $this->session->userdata('unit');	
			if(!empty($unit)){
			  $qp ="select koders, namars from tbl_namers where koders = '$unit'"; 
			} else {
			  $qp ="select koders, namars from tbl_namers order by koders"; 		
			}
					
			$d['nomor']= 'Auto';		 	
			$d['unit']  = $this->db->query($qp);		
			$this->load->view('keuangan/v_keuangan_masuk_add',$d);
			} else
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
					
			$qheader ="select * from tbl_kasmasuk where notr = '$nomor'"; 		
			
			$d['header'] = $this->db->query($qheader);
            $data=$this->db->query($qheader);
			
			if(count($data->result()) != 0){
				foreach($data->result() as $row){
					$d['tanggal']		= $row->tglkas;
					$d['kasbank']		= $row->accountno;
					$d['pos']			= $row->accountpos;
					$d['keterangan']	= $row->keterangan;
					$d['jumlah']		= $row->nilairp;	
				}
			
				$d['nomorbukti']=$nomor;
				
				$this->load->view('keuangan/v_keuangan_masuk_edit',$d);
			} else {
				header('location:'.base_url());
			}
		} else
		{
			header('location:'.base_url());
		}	
	}

	public function edit2( $id )
	{
	    $cek = $this->session->userdata('level');		
		if(!empty($cek))
		{	
			$unit = $this->session->userdata('unit');	
			if(!empty($unit)){
			  $qp ="select koders, namars from tbl_namers where koders = '$unit'"; 
			} else {
			  $qp ="select koders, namars from tbl_namers order by koders"; 		
			}
						
			$d['register']= $id;			
			$d['unit']    = $this->db->query($qp);		
			$d['jumdata'] = $this->db->query("select * from tbl_kasmasuk where id = '$id'")->num_rows();		
			$d['header']  = $this->db->query("select tbl_kasmasuk.*,tbl_accounting.acname, tbl_namers.namars 
			from tbl_kasmasuk inner join tbl_accounting on tbl_kasmasuk.accountno=tbl_accounting.accountno
			inner join tbl_namers on tbl_kasmasuk.koders=tbl_namers.koders
			where tbl_kasmasuk.id = '$id' limit 1")->row();		
			$d['detil']   = $this->db->query("select tbl_kasmasuk.*, tbl_accounting.acname from tbl_kasmasuk 
			inner join tbl_accounting on tbl_kasmasuk.accountpos=tbl_accounting.accountno
			where tbl_kasmasuk.id = '$id' ")->result();		
			
			$this->load->view('keuangan/v_keuangan_masuk_edit',$d);
			} else
		{
			header('location:'.base_url());
		}		
	}
	
	public function ajax_list( $param )
	{
		$level=$this->session->userdata('level');		
		//$akses= $this->M_global->cek_menu_akses($level, 903);			
		$dat   = explode("~",$param);
		if($dat[0]==1){
			$bulan = date('n');
			$tahun = date('Y');
			$list = $this->M_keuangan_masuk->get_datatables( 1, $bulan, $tahun );
		} else {
			$bulan  = date('Y-m-d',strtotime($dat[1]));
		    $tahun  = date('Y-m-d',strtotime($dat[2]));
		    $list = $this->M_keuangan_masuk->get_datatables( 2, $bulan, $tahun );	
		}
		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $rd) {
			$no++;
					   
			$row   = array();
			$row[] = $rd->koders;
			$row[] = $rd->username;
			$row[] = $rd->notr;
			$row[] = date('d-m-Y',strtotime($rd->tglkas));
			$row[] = $rd->accountno." | ".$rd->kas;
			$row[] = $rd->accountno." | ".$rd->pos;
			$row[] = $rd->keterangan;
			$row[] = $rd->nilairp;
			
			// <a class="btn btn-sm btn-warning" target="_blank" href="'.base_url("keuangan_masuk/cetak/".$rd->notr."").'" title="Cetak" ><i class="glyphicon glyphicon-print"></i> </a>
			// <a class="btn btn-sm btn-warning" target="_blank" href="'.base_url("keuangan_masuk/cetak/".$rd->notr."").'" title="Cetak" ><i class="glyphicon glyphicon-print"></i> </a>
				  
			//if($akses->uedit==1 && $akses->udel==1)
			{
			$row[] = '<a class="btn btn-sm btn-primary" href="'.base_url("keuangan_masuk/edit/".$rd->notr."").'" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$rd->id."'".",'".$rd->notr."'".')"><i class="glyphicon glyphicon-trash"></i> </a>
				  <a class="btn btn-sm btn-warning" id="'.$rd->notr.'" href="#report" onclick="id_cetak('."'".$rd->notr."'".')" data-toggle="modal"><i class="glyphicon glyphicon-print"></i></a>';
			} 
			/*
			else 
			if($akses->uedit==1 && $akses->udel==0){
			$row[] = '<a class="btn btn-sm btn-primary" href="'.base_url("keuangan_masuk/edit/".$rd->id."").'" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a> ';				  
			} else 	
			if($akses->uedit==0 && $akses->udel==1){
			$row[] = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$rd->id."'".')"><i class="glyphicon glyphicon-trash"></i> </a>';
			} else 	{
			$row[] = '';	
			}
			*/
			
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_keuangan_masuk->count_all( $dat[0], $bulan, $tahun),
						"recordsFiltered" => $this->M_keuangan_masuk->count_filtered( $dat[0],  $bulan, $tahun ),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	
	public function ajax_delete()
	{
		$id = $this->input->post('id');
		$this->db->delete('tbl_kasmasuk', array('id' => $id));
		//$this->db->delete('tr_jurnal', array('nomor' => $id));
		echo json_encode(array("status" => TRUE));
	}
	
	
	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('kodeakun') == '')
		{
			$data['inputerror'][] = 'kodeakun';
			$data['error_string'][] = 'Kode  harus diisi';
			$data['status'] = FALSE;
		}
		
		if($this->input->post('debet') == '')
		{
			$data['inputerror'][] = 'debet';
			$data['error_string'][] = 'Tidak boleh kosong';
			$data['status'] = FALSE;
		}
		
		if($this->input->post('kredit') == '')
		{
			$data['inputerror'][] = 'kredit';
			$data['error_string'][] = 'Tidak boleh kosong';
			$data['status'] = FALSE;
		}
		
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
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
		  
		    $queryh = "
				SELECT k.*, a.`acname` AS kas_bank, ac.`acname` AS pos
				FROM  tbl_kasmasuk k 
					LEFT JOIN tbl_accounting a ON k.`accountno` = a.`accountno`
					LEFT JOIN tbl_accounting ac ON k.`accountpos` = ac.`accountno`
				WHERE notr = '$param'";
			 
		    $header = $this->db->query($queryh)->row();
			$pdf=new simkeu_nota();
			$pdf->setID($nama_usaha,$alamat1,$alamat2);
			$pdf->setjudul('');
			$pdf->setsubjudul('');
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(70,30,90));
			
			$pdf->ln(5);
			$pdf->setfont('Arial','B',18);
			$pdf->Cell(0,0,'BUKTI PENERIMAAN KAS/BANK',0,1,'C');
			$pdf->ln(12);
			$pdf->setfont('Arial','',10);
			
			$pdf->Cell(30 ,5,'No Transaksi',0,0);
			$pdf->Cell(5 ,5,':',0,0);
			$pdf->Cell(90 ,5,$header->notr,0,1);

			$pdf->Cell(30 ,5,'Tanggal',0,0);
			$pdf->Cell(5 ,5,':',0,0);
			$pdf->Cell(90 ,5,date('d-m-Y',strtotime($header->tglkas)),0,1);

			$pdf->Cell(30 ,5,'Kas/Bank',0,0);
			$pdf->Cell(5 ,5,':',0,0);
			$pdf->Cell(30 ,5,$header->accountno,0,0);
			$pdf->Cell(50 ,5,$header->kas_bank.",",0,1);

			$pdf->Cell(30 ,5,'POS',0,0);
			$pdf->Cell(5 ,5,':',0,0);
			$pdf->Cell(30 ,5,$header->accountpos,0,0);
			$pdf->Cell(50 ,5,$header->pos,0,1);

			$pdf->Cell(30 ,5,'Jumlah Bayar',0,0);
			$pdf->Cell(5 ,5,':',0,0);
			$pdf->setfont('Arial','B',10);
			$pdf->Cell(90 ,5,number_format($header->nilairp,0,'.',','),0,1);
			
			$pdf->setfont('Arial','',10);
			$pdf->Cell(30 ,5,'Terbilang',0,0);
			$pdf->Cell(5 ,5,':',0,0);
			$pdf->setfont('Arial','B',10);
			$terbilang = ucwords($this->M_global->terbilang($header->nilairp)).' Rupiah';
			$pdf->MultiCell(120 ,5,$terbilang,0,1);
			$pdf->ln(20);

			$pdf->SetLineWidth(0.5);
			$pdf->Line(10,95,200,95);
			
			$pdf->setfont('Arial','',10);
			$pdf->Cell(30 ,5,'Jakarta, '.date('d-m-Y'),0,1);
			$pdf->ln(2);

			$pdf->Cell(50 ,5,'       Pemohon,',0,0);
			$pdf->Cell(50 ,5,'  Keuangan,',0,0);
			$pdf->Cell(50 ,5,'Penanggung Jawab,',0,0);
			$pdf->Cell(50 ,5,'         Pembukuan,',0,0);
			$pdf->ln(20);

			$pdf->setfont('Arial','B',10);
			$pdf->Cell(45 ,5,'     HARYANTO',0,0);
			$pdf->Cell(50 ,5,'  ANDARA MULYA',0,0);
			$pdf->Cell(50 ,5,'        DARMAWAN H',0,0);
			$pdf->Cell(50 ,5,'            SITI FATIMAH',0,0);
			$pdf->ln(3);

			$pdf->setfont('Arial','I',7);
			$pdf->Cell(104 ,5,'HOSPITAL MANAGEMENT',0,0);

			$pdf->setfont('Arial','I',9);
			$pdf->Cell(45 ,5,'Kepala Cabang',0,0);
			$pdf->ln(3);
			$pdf->setfont('Arial','I',7);
			$pdf->Cell(104 ,5,'            SYSTEM',0,0);

			// -------------------------------------------

			// $border = array('','','BT');
			// $size   = array('','','');
			// $pdf->setfont('Arial','B',18);
			// $pdf->SetAligns(array('C','C','C'));
			// $align = array('L','C','L');
			// $style = array('','','B');
			// $size  = array('12','','18');
			// $max   = array(5,5,20);
			// $judul=array('','','Transfer Bank');
			// $fc     = array('0','0','0');
			// $hc     = array('20','20','20');
			// $pdf->FancyRow2(10,$judul, $fc,  $border, $align, $style, $size, $max);
			// $pdf->ln(1);
			// $pdf->setfont('Arial','B',10);
			// $pdf->SetWidths(array(70,30,30,5,55));
			// $border = array('','','','','');
			// $fc     = array('0','0','1','1','1');
			// $pdf->SetFillColor(230,230,230);
			// //$pdf->settextcolor(10,20,200);
			// $pdf->setfont('Arial','',10);
		
			// $pdf->FancyRow(array('','','Nomor',':',$header->notr), $fc, $border);
			// $pdf->FancyRow(array('','','Tanggal',':',date('d-m-Y',strtotime($header->tglkas))), $fc, $border);			
			// $fc     = array('1','0','0','0','0');			
			// $pdf->ln(3);
			// $border = array('T','T','T','T','T');
			// $fc     = array('0','0','0','0','0');
			// $pdf->SetLineWidth(1);
			// $pdf->FancyRow(array('','','','',''), $fc, $border);			
			// $pdf->SetLineWidth(0);
			
			// $pdf->SetWidths(array(90,10,90));
			// $border = array('TB','','TB');
			// $fc     = array('0','0','0');
			// $pdf->FancyRow(array('Dari Kas/Bank','','Ke Kas/Bank'), $fc, $border);
			// //$pdf->ln(3);
			// $pdf->SetWidths(array(30,60,10,30,60));
			// $align  = array('L','L','','L','L');
			// $border = array('','','','','');
			// $fc     = array('1','1','0','1','1');
			// $pdf->SetFillColor(230,230,230);
			// $terbilang = ucwords($this->M_global->terbilang($header->nilairp)).' Rupiah';
			// $banks     = $this->M_global->_namaakun($header->accountno);
			// $bankt     = $this->M_global->_namaakun($header->accountpos);
			// $pdf->FancyRow(array('Kas/Bank',$banks,'','Kas/Bank',$bankt), $fc, $border);
			// $pdf->FancyRow(array('Nilai Transfer',number_format($header->nilairp,0,'.',','),'','Nilai Transfer',number_format($header->nilairp,0,'.',',')), $fc, $border);
			// $pdf->FancyRow(array('Terbilang',$terbilang,'','Terbilang',$terbilang), $fc, $border);
			// $pdf->FancyRow(array('','','','',''), $fc, $border);
			
			// $pdf->ln(5);			
			// $pdf->SetWidths(array(90,10,30,60));
			// $border = array('TB','','TB','TB');
			// $fc     = array('0','0','1','1');
			// $align  = array('L','','L','R');
			// $pdf->SetFillColor(230,230,230);
			// $pdf->settextcolor(0);
			
			// $pdf->FancyRow(array('Keterangan', '', 'Biaya Transfer',number_format(0,0,'.',',')),$fc, $border, $align,0);			
			// $border = array('','','','');
			// $pdf->FancyRow(array($header->keterangan,'', '', ''),$fc, $border, $align);
			// $fc = array('0','0','0','0');
			// $pdf->FancyRow(array('','', '', ''),$fc, $border, $align);
			// $pdf->FancyRow(array('','', '', ''),$fc, $border, $align);
			
			// $style = array('','','B','B');
			// $size  = array('','','','');
			// $border = array('T','','','');
			// $pdf->SetFillColor(0,0,139);
			// $pdf->settextcolor(255,255,255);
			// $fc = array('0','0','0','0');
			// $pdf->FancyRow(array('','', '', ''),$fc, $border, $align, $style, $size);
			// $pdf->settextcolor(0);
			$pdf->AliasNbPages();
			$pdf->output($param.'.PDF','I');			
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}

	public function cetak2()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{				  
		    $page=$this->uri->segment(3);
		    $limit=$this->config->item('limit_data');	
		  
		    $nama_usaha =  $this->config->item('nama_perusahaan');
			$motto = $this->config->item('motto');
			$alamat =$this->config->item('alamat_perusahaan');
		    $bulan = $this->M_global->_periodebulan();
	        $tahun = $this->M_global->_periodetahun();
			$unit  = '';
		
		    $this->db->select('ms_akun.kodeakun, ms_akun.namaakun, ms_akunsaldo.debet, ms_akunsaldo.kredit, ms_akunsaldo.id')->from('ms_akun');
		    $this->db->join('ms_akunsaldo','ms_akunsaldo.kodeakun=ms_akun.kodeakun','left');
		    $this->db->where(array('ms_akunsaldo.tahun' => $tahun,'ms_akunsaldo.bulan' => $bulan));
			$saldoawal = $this->db->get()->result();
		
		    $pdf=new simkeu_rpt();
			$pdf->setID($nama_usaha,$motto,$alamat);
			$pdf->setunit($unit);
			$pdf->setjudul('SALDO AWAL '.$this->M_global->_namabulan($bulan).'  '.$tahun);
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(10,25,90,30,30));
			$pdf->SetAligns(array('C','C','C','C','C'));
			$judul=array('NO','KODE PERKIRAAN','NAMA','DEBET','KREDIT');
			$pdf->setfont('Times','B',10);
			$pdf->row($judul);
			$pdf->SetWidths(array(10,25,90,30,30));
			$pdf->SetAligns(array('C','L','L','R','R'));
			$pdf->setfont('Times','',10);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
				
			$nourut = 1;
            $tdb = 0;
            $tkr = 0;			
			foreach($saldoawal as $db)
			{
			  $tdb += $db->debet;
			  $tkr += $db->kredit;
			  $pdf->row(array($nourut, $db->kodeakun, $db->namaakun, number_format($db->debet,'2',',','.'),  number_format($db->kredit,'2',',','.')));
			  $nourut++;
			}
			$pdf->setfont('Times','B',10);
			$pdf->SetWidths(array(125,30,30));
			$pdf->SetAligns(array('C','R','R'));
            $pdf->row(array('TOTAL', number_format($tdb,'2',',','.'),  number_format($tkr,'2',',','.')));

			$pdf->AliasNbPages();
			$pdf->output('saldoakun.PDF','I');


		  
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
		    $nama_usaha =  $this->config->item('nama_perusahaan');
			$motto = $this->config->item('motto');
			$alamat =$this->config->item('alamat_perusahaan');
		    $bulan = $this->M_global->_periodebulan();
	        $tahun = $this->M_global->_periodetahun();
			$unit  = '';
		    
		    $this->db->select('ms_akun.kodeakun, ms_akun.namaakun, ms_akunsaldo.debet, ms_akunsaldo.kredit, ms_akunsaldo.id')->from('ms_akun');
		    $this->db->join('ms_akunsaldo','ms_akunsaldo.kodeakun=ms_akun.kodeakun','left');
		    $this->db->where(array('ms_akunsaldo.tahun' => $tahun,'ms_akunsaldo.bulan' => $bulan));
			$saldoawal = $this->db->get()->result();
		  
		     header("Content-type: application/vnd-ms-excel");
			 header("Content-Disposition: attachment; filename=saldoawal.xls");
			 header("Pragma: no-cache");
			 header("Expires: 0");
			?>
			<h2><?php echo $nama_usaha;?></h2>
			<h4>SALDO AWAL  <?php echo $this->M_global->_namabulan($bulan).'  '.$tahun;?> </h4>
			<table border="1" >
				<thead>
					 <tr>
						 <th style="text-align: center">Kode Perkiraan</th>
						 <th style="text-align: center">Nama</th>
						 <th style="text-align: center">Debet</th>
						 <th style="text-align: center">Kredit</th>
					 </tr>
				 </thead>
				 <tbody>
				 <?php
				   foreach($saldoawal  as $db) { ?>
					 <tr>
						 <td><?php echo $db->kodeakun;?></td>
						 <td><?php echo $db->namaakun;?></td>
						 <td><?php echo $db->debet;?></td>
						 <td><?php echo $db->kredit;?></td>
					 </tr>
				 <?php } ?>
				 </tbody>
			</table>
           <?php
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	public function penerimaan_simpan($kode)
	{
		$cek = $this->session->userdata('level');
		if(!empty($cek))
		{			
            $cabang   = $this->session->userdata('unit');
			$userid   = $this->session->userdata('username');
			
			if($kode==2){				
				$nobukti  = $this->input->post('nomorbukti');		  
			} else {
				$nobukti  = urut_transaksi('URUT_KASMASUK',19);				
			}
			
			$data = array(
				'koders' => $cabang,
				'tglkas' => $this->input->post('tanggal'),
				'notr'   => $nobukti,
				'accountno'  => $this->input->post('kasbank'),
				'accountpos' => $this->input->post('pos'),
				'keterangan' => $this->input->post('keterangan'),
				'nilairp' => str_replace(',','',$this->input->post('jumlah')),
				'username' => $userid,
			);
			
			
			$where = array(
				'notr' => $nobukti
			);
			
			if($kode==1)
			{
				$this->db->insert("tbl_kasmasuk", $data);	
			} else {
				$this->db->update('tbl_kasmasuk', $data, $where);
			}
			
				
			// $this->db->insert('tbl_kasmasuk',$data);
			echo $nobukti;
		}
		else
		{
			header('location:'.base_url());
		}
	}

	public function penerimaan_save($kode)
	{
		$cek = $this->session->userdata('level');
		if(!empty($cek))
		{			
            $cabang   = $this->session->userdata('unit');
			$userid   = $this->session->userdata('username');
			
			$tanggal  = $this->input->post('tanggal');
			$tahun    = date('Y',strtotime($tanggal));
			$bulan    = date('m',strtotime($tanggal));
			
			if($kode==2){
			  $register = $this->input->post('register');			  	
			  $nobukti  = $this->input->post('nomorbukti');
              $this->db->query("delete from tbl_kasmasuk where notr = '$nobukti'");			  
			} else {
			  $nobukti  = urut_transaksi('URUT_KASMASUK',19);				
			}
			
			$kodeakun = $this->input->post('akun');
			$ket      = $this->input->post('ket');
		    $jumlah   = $this->input->post('jumlah');
		    $jumdata  = count($kodeakun);
			
			for($i=0;$i<=$jumdata-1;$i++)
			{
			    $_akun   = $kodeakun[$i];
				$data = array(
				'koders' => $cabang,
				'tglkas' => $tanggal,
				'notr'   => $nobukti,
				'keterangan' => $ket[$i],
				'accountno'  => $this->input->post('kasbank'),
				'accountpos' => $_akun,
				'nilairp' => str_replace(',','',$jumlah[$i]),
				'username' => $userid,
				
			   );
			
				if ($_akun!="")
				{
					$this->db->insert('tbl_kasmasuk',$data);
				}
			}
			
			echo $nobukti;
		}
		else
		{
			header('location:'.base_url());
		}
	}
	
	
	
}

/* End of file master_bank.php */
/* Location: ./application/controllers/master_akun.php */