<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penjualan_pos extends CI_Controller {

	/**
	 * @author : Enjang RK
	 * @web : http://e-soft.comli.com
	 * @keterangan : Controller untuk modul POS
	 **/
	
	
	
	
	public function __construct()
	{
		parent::__construct();
		$this->session->set_userdata('menuapp', '400');
		$this->session->set_userdata('submenuapp', '490');
		$this->load->helper('simkeu_nota');		
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		$uid = $this->session->userdata('unit');		
		if(!empty($cek))
		{				  
		  $page=$this->uri->segment(3);
		  $limit=$this->config->item('limit_data');	
          $d['cust'] = $this->db->order_by('nama')->get_where('ar_customer',array('nama !=' => ''))->result();
		  $d['unit'] = $this->db->get('ms_unit')->result();
		  $d['nomor']= $this->M_global->_Autonomor('SI');
		  $this->load->view('penjualan/v_penjualan_pos',$d);				
		}
		else
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
				"select a.kodesi, a.tglsi, b.nama, a.ket, a.statusid, a.tgljthtempo, a.totalsi as total
				from
				   ar_sifile a,
				   ar_customer b
				where
				   a.kodecust=b.kode and
				   a.tglsi between '$_tgl1' and '$_tgl2' and 				 
				   a.kodecbg = '$unit'
				order by
				   a.tglsi, a.kodeso desc";
		  
		  $periode= 'Periode '.date('d-m-Y',strtotime($tgl1)).' s/d '.date('d-m-Y',strtotime($tgl2));	   
	      $d['keu'] = $this->db->query($q1)->result();
          $d['periode'] = $periode;		  
		  $this->load->view('penjualan/v_penjualan_faktur',$d);			   
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
		  
		    $queryh = "select * from ar_sifile inner join ar_customer on ar_sifile.kodecust=ar_customer.kode where kodesi = '$param'";
			$queryd = "select * from ar_sidetail inner join inv_barang on ar_sidetail.kodeitem=inv_barang.kodeitem where ar_sidetail.kodesi = '$param'";
			$queryb = "select sum(jumlah) as total from ar_sibiaya  where ar_sibiaya.kodesi = '$param'";
			 
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
			$judul=array('Kepada :','','Faktur Penjualan');
			$fc     = array('0','0','0');
			$hc     = array('20','20','20');
			$pdf->FancyRow2(10,$judul, $fc,  $border, $align, $style, $size, $max);
			$pdf->ln(1);
			$pdf->setfont('Arial','B',10);
			$pdf->SetWidths(array(70,30,30,5,55));
			$border = array('','','','','');
			$fc     = array('1','0','1','1','1');
			$pdf->SetFillColor(230,230,230);			
			$pdf->setfont('Arial','',9);
			$pdf->FancyRow(array($header->nama,'','Nomor',':',$header->kodeso), $fc, $border);
			$pdf->FancyRow(array($header->alamat1,'','Tanggal',':',date('d M Y',strtotime($header->tglsi))), $fc, $border);
			$pdf->FancyRow(array($header->alamat2,'','Tgl. Kirim',':',date('d M Y',strtotime($header->tglkirim))), $fc, $border);
			$pdf->FancyRow(array($header->telp,'','Jatuh Tempo',':',date('d M Y',strtotime($header->tgljthtempo))), $fc, $border);
			
			$pdf->ln(2);
			
			$pdf->SetWidths(array(30,60,20,25,25,30));
			$border = array('TB','TB','TB','TB','TB','TB');
			$align  = array('L','L','R','R','R','R');
			$pdf->setfont('Arial','B',10);
			$pdf->SetAligns(array('L','C','R'));
			$pdf->SetFillColor(0,0,139);
			$pdf->settextcolor(255,255,255);
			$fc = array('1','1','1','1','1','1');
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
			  $dpp = $db->qtysi*$db->hargajual;	
			  $dis = ($db->disc/100)*$dpp;
			  $jum = $dpp-$dis;
			  $tot = $tot + $jum; 	
			  
			  $subtot = $subtot + $dpp; 	
			  $tdisc  = $tdisc + $dis; 	
			  
			  $pdf->FancyRow(array(
			  $db->kodeitem, 
			  $db->namabarang,
			  $db->qtysi,
			  number_format($db->hargajual,0,'.',','),
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
			$fc = array('0','0','1','1');
			$pdf->FancyRow(array('Keterangan', '', 'Sub Total',number_format($subtot,0,'.',',')),$fc, $border, $align,0);
			$border = array('','','','');
			$pdf->FancyRow(array($header->ket,'', 'Diskon', number_format($tdisc,0,'.',',')),$fc, $border, $align);
			$pdf->FancyRow(array('','', 'PPN (10%)', number_format($ppn,0,'.',',')),$fc, $border, $align);
			$pdf->FancyRow(array('','', 'Biaya Lain-lain', number_format($biayalain,0,'.',',')),$fc, $border, $align);
			$style = array('','','B','B');
			$size  = array('','','','');
			$border = array('T','','BT','BT');
			$pdf->SetFillColor(0,0,139);
			$pdf->settextcolor(255,255,255);
			$fc = array('0','0','1','1');
			$pdf->FancyRow(array('','', 'Total', number_format($tot,0,'.',',')),$fc, $border, $align, $style, $size);
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
	
	public function getterbilang( $jumlah )
	{
		$data = $this->M_global->terbilang( $jumlah );
		echo ucwords($data);
	}
	
	public function getlistpo( $supp )
	{
		if(!empty($supp))
		{
		    $po  = $this->db->get_where('ar_sofile',array('kodecust' => $supp, 'statusid' => 1))->result();	
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
				<a class="btn blue" onclick="getpoheader();getpo();getbiaya()"><i class="fa fa-download"></i></a>
			</span>	
			
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
		  $d['unit'] = $this->db->get('ms_unit')->result();
		  $d['nomor']= $this->M_global->_Autonomor('SI');
		  $this->load->view('penjualan/v_penjualan_faktur_add',$d);				
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
		   $this->db->delete('ar_sifile',array('kodesi' => $nomor));
		   $this->db->delete('ar_sidetail',array('kodesi' => $nomor));
		   $this->db->delete('ar_sibiaya',array('kodesi' => $nomor));
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
		    $q     = $kode;
			$jenis = 1;
			$query = "select * from inv_barang where kodeitem like '%$q%' or namabarang like '%$q%' order by namabarang";
			$data  = $this->db->query($query);
			?>
			
			<table id="myTable">
			  <tr class="header">
				<th style="width:20%;">Kode</th>
				<th style="width:40%;">Nama</th>	
				<th style="width:20%;text-align:right">Harga</th>	
				<th style="width:10%;text-align:right">Stok</th>	
				<th style="width:20%;">Satuan</th>	
			  </tr> 
			  
			<?php							
			foreach($data->result_array() as $row)
			{ 
			   if($jenis==1){
				   $harga = $row['hargajual1'];
			   } else 
			   if($jenis==2){
				   $harga = $row['hargajual2'];
			   } else 
			   if($jenis==3){
				   $harga = $row['hargajual3'];
			   }	   
			   ?>
			   <tr>
				 <td width="50" align="center">
					<a href="#" onclick="post_value('<?php echo $row['kodeitem'];?>')"><?php echo $row['kodeitem'];?></a>
				 </td>	     
				 <td><?php echo $row['namabarang'];?></td>
				 <td style="text-align:right"><?php echo number_format($harga,0,',','.');?></td>
				 <td style="text-align:right"><?php echo number_format($row['qty'],0, ',','.');?></td>
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
			
			$query = "select * from ar_sidetail inner join ar_sifile on ar_sifile.kodesi=ar_sidetail.kodesi where ar_sifile.kodecust = '$supp' and ar_sidetail.kodeitem = '$item' order by ar_sifile.tglsi desc";
	   	    $data  = $this->db->query($query)->result();
			?>
			
			<table id="myTable">
			  <tr class="header">
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
					<a href="#" onclick="post_harga('<?php echo $row->hargajual;?>','<?php echo $row->satuan;?>')">
					
					<?php echo $row->kodesi;?></a>
				 </td>	     
				 <td><?php echo date('d-m-Y',strtotime($row->tglsi));?></td>
				 <td><?php echo $row->hargajual;?></td>
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
		$data = $this->db->get_where('ar_sifile',array('kodesi' => $kodepo))->row();
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
			$this->db->delete('ar_sidetail',array('kodesi' => $nobukti));
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
				
				$hpp   = $this->M_global->_hpp($_kode);
			    $datad = array(
				'kodesi'   => $nobukti,
				'kodeitem' => $_kode,
				'qtysi' => $qty[$i],
				'satuan' => $sat[$i],
				'hpp' => $hpp,
				'hargajual' => $harga[$i],
				'disc' => $disc[$i],
			    );
				if($_kode!=""){
			      $this->db->insert('ar_sidetail', $datad);	
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
			
			$this->db->delete('ar_sibiaya',array('kodesi' => $nobukti));
			$totbiaya = 0;
			for($i=0;$i<=$jumdata;$i++)
			{
				$totbiaya = $totbiaya + $jumlah[$i];
			    $datad = array(
				'kodesi'   => $nobukti,
				'kodeakun' => $kode[$i],
				'jumlah' => $jumlah[$i],
				'keterangan' => $ket[$i],
							
			    );
				if ($kode[$i]!=""){
			    $this->db->insert('ar_sibiaya', $datad);	
				}
			}
			
			
			
			if($this->input->post('pembayaran')=='T'){
              $tgljthtempo = date('Y-m-d',strtotime($this->input->post('tanggal')));
			} else {
			  $top  = $this->db->get_where('ar_customer',array('kode' => $this->input->post('cust')))->row()->top;			  
			  $tgljthtempo = date('Y-m-d',strtotime($this->input->post('tanggal').' + '.$top.' days'));
			}
			
			
			$datacustomer = $this->M_global->_data_customer($this->input->post('cust'));
			
			
			$data = array(
			    'kodecbg' => $this->session->userdata('unit'),
				'kodecust' => $this->input->post('cust'),
				'kodesi'  => $this->input->post('nomorbukti'),
				'pembayaran'  => $this->input->post('pembayaran'),
				'kodeso'  => $this->input->post('kodeso'),
				'tglsi'   => date('Y-m-d',strtotime($this->input->post('tanggal'))),
				'tglkirim'=> date('Y-m-d',strtotime($this->input->post('tanggalkirim'))),
				'tgljthtempo'=> $tgljthtempo,
				'kodeuser'=> $userid,
				'ket'     => $this->input->post('keterangan'),
				'alamat'  => $this->input->post('alamat'),
				'dpp'     => $tot,
				'ppn'     => $tppn,
				'diskon'  => $tdisc,
				'totalsi' => $tot+$tppn-$tdisc+$totbiaya,
				'biayalain'=> $totbiaya,
				'sppn'    => $this->input->post('sppn'),
				'statusid'=> 1,			
                'alamat1' => $datacustomer->alamat1,
				'alamat2' => $datacustomer->alamat2,
				'namakirim' => $datacustomer->contactname,
				
			);
						
			
			if($param==1)
			{
			  $this->db->insert('ar_sifile',$data);	
			  $this->M_global->_updatecounter1('SI');		  
			} else {
			  $this->db->update('ar_sifile',$data, array('kodesi' => $nobukti));				 
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
					
			$header = $this->db->get_where('ar_sifile', array('kodesi' => $nomor));
			$detil  = $this->db->join('inv_barang','inv_barang.kodeitem=ar_sidetail.kodeitem')->get_where('ar_sidetail', array('kodesi' => $nomor));
			$biaya  = $this->db->join('ms_akun','ms_akun.kodeakun=ar_sibiaya.kodeakun')->get_where('ar_sibiaya', array('kodesi' => $nomor));
		
			$d['header']  = $header->result();
			$d['detil']   = $detil->result();
			$d['biaya']   = $biaya->result();
			$d['jumdata1']= $detil->num_rows();	
			$d['jumdata2']= $biaya->num_rows();	
			$d['cust'] = $this->db->order_by('nama')->get_where('ar_customer',array('nama !=' => ''))->result();
		    $d['unit'] = $this->db->get('ms_unit')->result();
			$this->load->view('penjualan/v_penjualan_faktur_edit',$d);
			} else
		{
			header('location:'.base_url());
		}	
	}
}
/* End of file keuangan_saldo.php */
/* Location: ./application/controllers/keuangan_saldo.php */