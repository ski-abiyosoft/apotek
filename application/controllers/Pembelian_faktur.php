<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_faktur extends CI_Controller {

	/**
	 * @author : Enjang RK
	 * @web : http://e-soft.comli.com
	 * @keterangan : Controller untuk modul saldo awal keuangan
	 **/
	
	
	
	
	public function __construct()
	{
		parent::__construct();
		$this->session->set_userdata('menuapp', '300');
		$this->session->set_userdata('submenuapp', '323');
		$this->load->helper('simkeu_nota');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        		
		if(!empty($cek))
		{	
		  	 
		  $q1 = 
				"select a.kodepu, a.nomorfaktur, a.tglpu, b.nama, a.ket, a.statusid, a.tgljthtempo, a.totalpu as total, a.jumlahbayar
				from
				   ap_pufile a,
				   ap_supplier b
				where
				   a.kodesup=b.kode and
				   year(a.tglpu)= (select periode_tahun from ms_identity) and
				   month(a.tglpu)= (select periode_bulan from ms_identity) and
				   a.kodecbg = '$unit'
				order by
				   a.tglpu, a.kodepu desc";

		  $bulan  = $this->M_global->_periodebulan();
          $nbulan = $this->M_global->_namabulan($bulan);
		  $periode= 'Periode '.$nbulan.'-'.$this->M_global->_periodetahun();	   
	      $d['keu'] = $this->db->query($q1)->result();
          $d['periode'] = $periode;		
          $level=$this->session->userdata('level');		
		  $akses= $this->M_global->cek_menu_akses($level, 323);
		  $d['akses']= $akses;		  
		  $this->load->view('pembelian/v_pembelian_faktur',$d);			   
		
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
				"select a.kodepu, a.nomorfaktur, a.tglpu, b.nama, a.ket, a.statusid, a.tgljthtempo, a.totalpu as total, a.jumlahbayar
				from
				   ap_pufile a,
				   ap_supplier b
				where
				   a.kodesup=b.kode and
				   a.tglpu between '$_tgl1' and '$_tgl2' and
				   a.kodecbg = '$unit'
				order by
				   a.tglpu, a.kodepu desc";

		  
		  $periode= 'Periode '.date('d-m-Y',strtotime($tgl1)).' s/d '.date('d-m-Y',strtotime($tgl2));	   
	      $d['keu'] = $this->db->query($q1)->result();
          $d['periode'] = $periode;
          $level=$this->session->userdata('level');		
		  $akses= $this->M_global->cek_menu_akses($level, 323);
		  $d['akses']= $akses;		  
		  $this->load->view('pembelian/v_pembelian_faktur',$d);			   
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
		  
		    $queryh = "select * from ap_pufile inner join ap_supplier on ap_pufile.kodesup=ap_supplier.kode where kodepu = '$param'";
			$queryd = "select * from ap_pudetail inner join inv_barang on ap_pudetail.kodeitem=inv_barang.kodeitem where ap_pudetail.kodepu = '$param'";
			$queryb = "select sum(jumlah) as total from ap_pubiaya  where ap_pubiaya.kodepu = '$param'";
			 
		    $detil  = $this->db->query($queryd)->result();
			$header = $this->db->query($queryh)->row();
			$biaya  = $this->db->query($queryb)->row();
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
			$judul=array('Dari :','','Faktur Pembelian');
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
			$pdf->FancyRow(array($header->nama,'','Nomor',':',$header->kodepu), $fc, $border);
			$pdf->FancyRow(array($header->alamat1,'','Tanggal',':',date('d M Y',strtotime($header->tglpu))), $fc, $border);
			$pdf->FancyRow(array($header->alamat2,'','Tgl. Kirim',':',date('d M Y',strtotime($header->tglkirim))), $fc, $border);
			$pdf->FancyRow(array($header->telp,'','','',''), $fc, $border);
			
			$pdf->ln(2);
			
			$pdf->SetWidths(array(30,60,20,25,25,30));
			$border = array('TB','TB','TB','TB','TB','TB');
			$align  = array('L','L','R','R','R','R');
			$pdf->setfont('Arial','B',10);
			$pdf->SetAligns(array('L','C','R'));
			//$pdf->SetFillColor(0,0,139);
			//$pdf->settextcolor(255,255,255);
			$fc = array('0','0','0','0','0','0');
			$judul=array('Kode Barang','Nama Barang','Qty','Harga','Diskon','Total Harga');
			$pdf->FancyRow2(8,$judul,$fc, $border,$align);
			$border = array('','','');
			$pdf->setfont('Arial','',10);
			$tot = 0;
			$subtot = 0;
			$tdisc  = 0;
			$border = array('','','','','','');
			$align  = array('L','L','R','R','R','R');
			$fc = array('0','0','0','0','0','0');
			$pdf->SetFillColor(0,0,139);
			$pdf->settextcolor(0);
			foreach($detil as $db)
			{
			  $dpp = $db->qtypu*$db->hargabeli;	
			  $dis = ($db->disc/100)*$dpp;
			  $jum = $dpp-$dis;
			  $tot = $tot + $jum; 	
			  
			  $subtot = $subtot + $dpp; 	
			  $tdisc  = $tdisc + $dis; 	
			  
			  $pdf->FancyRow(array(
			  $db->kodeitem, 
			  $db->namabarang,
			  $db->qtypu,
			  number_format($db->hargabeli,0,'.',','),
			  $db->disc, 
			  number_format($jum,0,'.',',')),$fc, $border, $align);
			  
			}
			
			
			if($header->sppn=="Y"){
			    $ppn = $subtot * 0.1;
			} else {
				$ppn = 0;
			}
			$biayalain = $biaya->total;
			$tot = $tot + $ppn + $biayalain;
			$pdf->SetFillColor(230);
			$border = array('B','B','B','B','B','B');
			$pdf->FancyRow(array('', '', '','','',''),0,$border);
			$pdf->ln(2);
			$pdf->SetWidths(array(100,20,30,40));
			$border = array('TB','','T','T');
			$align  = array('L','','L','R');
			$pdf->SetFillColor(230,230,230);
			$pdf->settextcolor(0);
			$fc = array('0','0','0','0');
			$pdf->FancyRow(array('Keterangan', '', 'Sub Total',number_format($subtot,0,'.',',')),$fc, $border, $align,0);
			$border = array('','','','');
			$pdf->FancyRow(array($header->ket,'', 'Diskon', number_format($tdisc,0,'.',',')),$fc, $border, $align);
			$pdf->FancyRow(array('','', 'PPN (10%)', number_format($ppn,0,'.',',')),$fc, $border, $align);
			$pdf->FancyRow(array('','', 'Biaya Lain-lain', number_format($biayalain,0,'.',',')),$fc, $border, $align);
			$style = array('','','B','B');
			$size  = array('','','','');
			$border = array('T','','BT','BT');
			//$pdf->SetFillColor(0,0,139);
			//$pdf->settextcolor(255,255,255);
			$fc = array('0','0','0','0');
			$pdf->FancyRow(array('','', 'Total', number_format($tot,0,'.',',')),$fc, $border, $align, $style, $size);
			$pdf->settextcolor(0);
			$pdf->SetWidths(array(50,50));
			$pdf->SetFont('Arial','',9);
			$pdf->SetAligns(array('C','C'));
			$pdf->ln(5);
			
			$border = array('','');
			$align  = array('C','C');
			$pdf->FancyRow(array('Bagian Pembelian',''),0,$border, $align);
			$pdf->ln(1);
			$pdf->ln(15);
			$pdf->SetWidths(array(49,2,49));
			$pdf->SetFont('Arial','',8);
			$pdf->SetAligns(array('C','C','C'));
			$border = array('B','','');	
			$pdf->FancyRow(array('','',''),0,$border,$align);
			$border = array('','','');	
			$align  = array('L','C','L');
			$pdf->FancyRow(array('Tgl.','',''),0,$border,$align);
	
		

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
        $data = $this->db->select('ap_podetail.*, inv_barang.namabarang')->join('inv_barang','inv_barang.kodeitem=ap_podetail.kodeitem','left')->get_where('ap_podetail',array('kodepo' => $po, 'statusid' =>1))->result();
		echo json_encode($data);
	}
	
	public function getpb($po)
	{   	    
        $data = $this->db->select('ap_lpbdetil.*, inv_barang.namabarang')->join('inv_barang','inv_barang.kodeitem=ap_lpbdetil.kodeitem','left')->get_where('ap_lpbdetil',array('nolpb' => $po))->result();
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
		    $po  = $this->db->get_where('ap_pofile',array('kodesup' => $supp, 'statusid' => 1))->result();	
			?>						
			<select name="kodepo" id="kodepo" class="form-control  input-small select2me"  >            											
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
	
	public function getlistpb( $supp )
	{
		if(!empty($supp))
		{
		    //$po  = $this->db->get_where('ap_lpb',array('kodesup' => $supp, 'statusid' => 1))->result();	
			$po  = $this->db->query("select * from ap_lpb where nolpb not in(select kodepb from ap_pufile where kodesup='$supp') and kodesup='$supp'")->result();	
			?>						
			<select name="kodepb" id="kodepb" class="form-control  input-small select2me"  >            											
			  <option value="">-- Tanpa PB ---</option>
			  <?php 			    
				foreach($po  as $row){	
				?>
				<option value="<?php echo $row->nolpb;?>"><?php echo $row->nolpb;?></option>
				
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
		  $d['unit'] = $this->db->get('ms_unit')->result();
		  $d['nomor']= $this->M_global->_Autonomor('PU');
		  $this->load->view('pembelian/v_pembelian_faktur_add',$d);				
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
	       $this->db->delete('ap_pufile',array('kodepu' => $nomor));
		   $this->db->delete('ap_pudetail',array('kodepu' => $nomor));
		   $this->db->delete('ap_pubiaya',array('kodepu' => $nomor));
		   $this->M_global->_hapusjurnal($nomor, 'JB');	
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
			$query = "select * from ms_akun where (kodeakun like '%$q%' or namaakun like '%$q%') and kodeakun like '6%' and akuninduk<>''";
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
					<a href="#" onclick="post_akun('<?php echo $row['kodeakun'];?>','<?php echo $row['namaakun'];?>')">
					
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
	
	public function getbarang($kode)
	{
		if(!empty($kode))
		{
		  
			$q = $kode;
			$query = "select * from inv_barang where kodeitem like '%$q%' or namabarang like '%$q%' order by namabarang";
			$data  = $this->db->query($query);
			?>
			
			<table id="myTable">
			  <tr class="header">
				<th style="width:20%;">Kode</th>
				<th style="width:60%;">Nama</th>	
				<th style="width:10%;">Stok</th>	
				<th style="width:10%;">Satuan</th>	
			  </tr> 
			  
			<?php							
			foreach($data->result_array() as $row)
			{ ?>
			   <tr>
				 <td width="50" align="center">
					<a href="#" onclick="post_value('<?php echo $row['kodeitem'];?>','<?php echo $row['namabarang'];?>','<?php echo $row['satuan'];?>','<?php echo $row['hargabeliakhir'];?>')">
					
					<?php echo $row['kodeitem'];?></a>
				 </td>	     
				 <td><?php echo $row['namabarang'];?></td>
				 <td><?php echo $row['qty'];?></td>
				 <td><?php echo $row['satuan'];?></td>
			   </tr>
			   
			   <?php
			}
			echo "</table>";
		} else
        {
		  echo "";	
		}			
	}
	
	public function getharga($kode)
	{
		if(!empty($kode))
		{
		    $data  = explode("~",$kode);
		    $supp  = $data[0];		
		    $item  = $data[1];
			
			$query = "select * from ap_pudetail inner join ap_pufile on ap_pufile.kodepu=ap_pudetail.kodepu where ap_pufile.kodesup = '$supp' and ap_pudetail.kodeitem = '$item' order by ap_pufile.tglpu desc";
	   	    $data  = $this->db->query($query)->result();
			?>
			
			<table class="table" id="myTablex">
			  <tr class="headerx">
				<th style="width:20%;">No. Faktur</th>
				<th style="width:20%;">Tanggal</th>
				<th style="width:20%;">Harga</th>	
				<th style="width:10%;">Disc</th>	
				<th style="width:10%;">Satuan</th>		
			  </tr> 
			  
			<?php							
			foreach($data  as $row)
			{ ?>
			   <tr>
				 <td width="50" align="center">
					<a href="#" onclick="post_harga('<?php echo $row->hargabeli;?>','<?php echo $row->satuan;?>')">
					
					<?php echo $row->kodepu;?></a>
				 </td>	     
				 <td><?php echo date('d-m-Y',strtotime($row->tglpu));?></td>
				 <td><?php echo $row->hargabeli;?></td>
				 <td><?php echo $row->disc;?></td>
				 <td><?php echo $row->satuan;?></td>
			   </tr>
			   
			   <?php
			}
			echo "</table>";
		} else
        {
		  echo "";	
		}			
	}
	
	public function getinfobarang( $kode )
	{
		$data = $this->M_global->_data_barang( $kode );
		echo json_encode($data);
	}
	
	public function getinfoakun( $kode )
	{
		$data = $this->M_global->_data_akun( $kode );
		echo json_encode($data);
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
			
			//$jumdata = $this->db->get_where('ap_pufile', array('kodepu' => $nobukti))->num_rows();
			//if($jumdata<1){			
			$this->db->delete('ap_pudetail',array('kodepu' => $nobukti));
			$kode  = $this->input->post('kode');
			$qty   = $this->input->post('qty');
		    $sat   = $this->input->post('sat');
			$harga = $this->input->post('harga');
			$disc  = $this->input->post('disc');
		   
			$jumdata  = count($kode);
			
			
			$nourut = 1;
			$tot = 0;
			$tdisc = 0;
			
			for($i=0;$i<=$jumdata;$i++)
			{
			    $_kode   = $kode[$i];
				//$tot = $tot + str_replace(',','',$jumlah[$i]);
				
				$vjum  = $qty[$i] * $harga[$i];
				$vdisc = $vjum * ($disc[$i]/100);
				$tot   = $tot + $vjum;
				$tdisc = $tdisc + $vdisc;
				
			    $datad = array(
				'kodepu'   => $nobukti,
				'kodeitem' => $_kode,
				'qtypu' => $qty[$i],
				'satuan' => $sat[$i],
				'hargabeli' => $harga[$i],
				'disc' => $disc[$i],
				
				
			    );
				if($_kode!=""){
			      $this->db->insert('ap_pudetail', $datad);	
				  $this->db->query('update inv_barang set hargabeli = '.$harga[$i].' where kodeitem = "'.$_kode.'"');
				}
			}
			
			if($this->input->post('sppn')=="Y"){
				$tppn = ($tot - $tdisc) * 0.1;
			} else {
				$tppn = 0;
			}
			//rincian biaya lain-lain
			$kode  = $this->input->post('lkode');
			$jumlah= $this->input->post('ljumlah');
		    $ket   = $this->input->post('lket');
			$jumdata  = count($kode);
			
			$this->db->delete('ap_pubiaya',array('kodepu' => $nobukti));
			$totbiaya = 0;
			for($i=0;$i<=$jumdata;$i++)
			{
				$totbiaya = $totbiaya + $jumlah[$i];
			    $datad = array(
				'kodepu'   => $nobukti,
				'kodeakun' => $kode[$i],
				'jumlah' => $jumlah[$i],
				'keterangan' => $ket[$i],
							
			    );
				if ($kode[$i]!=""){
			    $this->db->insert('ap_pubiaya', $datad);	
				}
			}
			
			
			if($param==1){
				if($this->input->post('pembayaran')=='T'){
				  $tgljthtempo = date('Y-m-d',strtotime($this->input->post('tanggal')));
				  $status = 2;
				} else {
				  $top  = $this->db->get_where('ap_supplier',array('kode' => $this->input->post('supp')))->row()->top;			  
				  $tgljthtempo = date('Y-m-d',strtotime($this->input->post('tanggal').' + '.$top.' days'));
				  $status = 1;
				}
			} else {
				if($this->input->post('pembayaran')=='T'){
				  $tgljthtempo = date('Y-m-d',strtotime($this->input->post('tanggal')));
				  $status = 2;
				} else {
			      $tgljthtempo = date('Y-m-d',strtotime($this->input->post('tanggaljt')));	
				  $status = 1;
				}
			}
			
			
			$datapo = $this->db->get_where('ap_lpb', array('nolpb' => $this->input->post('kodepb')))->row();
			if($datapo){
				$nomorpo = $datapo->kodepo;
			} else {
				$nomorpo = '';
			}
			
			
			$data = array(
			    'kodecbg' => $this->session->userdata('unit'),
				'kodesup' => $this->input->post('supp'),
				'kodepu'  => $this->input->post('nomorbukti'),
				'nomorfaktur'  => $this->input->post('nomorfaktur'),
				'pembayaran'  => $this->input->post('pembayaran'),
				'kodepo'  => $nomorpo,
				'kodepb'  => $this->input->post('kodepb'),
				'tglpu'   => date('Y-m-d',strtotime($this->input->post('tanggal'))),
				'tglkirim'=> date('Y-m-d',strtotime($this->input->post('tanggalkirim'))),
				'tgljthtempo'=> $tgljthtempo,
				'kodeuser'=> $userid,
				'ket'     => $this->input->post('keterangan'),
				'dpp'     => $tot,
				'ppn'     => $tppn,
				'diskon'  => $tdisc,
				'biayalain'=> $totbiaya,
				'totalpu' => $tot+$tppn+$totbiaya-$tdisc,
				'sppn'    => $this->input->post('sppn'),
				'statusid'=> $status,			
                'alamat1' => $this->input->post('alamat'),
			);
						
			$this->M_global->_hapusjurnal($this->input->post('nomorbukti'), 'JP');	
			
			$profile = $this->M_global->_LoadProfileLap();			
			$akun_debet     = $profile->akun_hutangbeli;
			$akun_kredit    = $profile->akun_hutang;
			
				
			
			$this->M_global->_rekamjurnal(
			date('Y-m-d',strtotime($this->input->post('tanggal'))),
			$this->input->post('nomorbukti'),
			'JP',
			$this->input->post('kodepb'),
			1,
			$akun_debet,
			'Faktur Pembelian',
			'Faktur Pembelian',
			$tot+$tppn+$totbiaya-$tdisc,
			0
			);
			
			$this->M_global->_rekamjurnal(
			date('Y-m-d',strtotime($this->input->post('tanggal'))),
			$this->input->post('nomorbukti'),
			'JP',
			$this->input->post('kodepb'),
			2,
			$akun_kredit,
			'Faktur Pembelian',
			'Faktur Pembelian',
			0,
			$tot+$tppn+$totbiaya-$tdisc
			);
			
			
			
			
			
			if($param==1)
			{
			  $this->db->insert('ap_pufile',$data);	
			  //$this->db->update('ap_lpb',array('statusid' => 2), array('nolpb' => $this->input->post('kodepb')));
			  $this->M_global->_updatecounter1('PU');		  
			} else {
			  $this->db->update('ap_pufile',$data, array('kodepu' => $nobukti));				 
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
					
			$header = $this->db->get_where('ap_pufile', array('kodepu' => $nomor));
			$detil  = $this->db->join('inv_barang','inv_barang.kodeitem=ap_pudetail.kodeitem')->get_where('ap_pudetail', array('kodepu' => $nomor));
			$biaya  = $this->db->join('ms_akun','ms_akun.kodeakun=ap_pubiaya.kodeakun')->get_where('ap_pubiaya', array('kodepu' => $nomor));
		
			$d['header']  = $header->result();
			$d['detil']   = $detil->result();
			$d['biaya']   = $biaya->result();
			$d['jumdata1']= $detil->num_rows();	
			$d['jumdata2']= $biaya->num_rows();	
			$d['supp'] = $this->db->order_by('nama')->get_where('ap_supplier',array('nama !=' => ''))->result();
		    $d['unit'] = $this->db->get('ms_unit')->result();
			$this->load->view('pembelian/v_pembelian_faktur_edit',$d);
			} else
		{
			header('location:'.base_url());
		}	
	}
}
/* End of file keuangan_saldo.php */
/* Location: ./application/controllers/keuangan_saldo.php */