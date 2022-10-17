<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penjualan_pengiriman extends CI_Controller {

	/**
	 * @author : Enjang RK	 
	 * @keterangan : Controller untuk modul pengiriman penjualan
	 **/
	
	
	
	
	public function __construct()
	{
		parent::__construct();
		$this->session->set_userdata('menuapp', '400');
		$this->session->set_userdata('submenuapp', '422');
		$this->load->helper('simkeu_nota');
		
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        		
		if(!empty($cek))
		{	
		  	 
		  $q1 = 
				"select a.kodekirim, a.tglkirim, b.nama, a.keterangan, a.statusid
				from
				   ar_kirim a,
				   ar_customer b
				where
				   a.kodecust=b.kode and
				   year(a.tglkirim)= (select periode_tahun from ms_identity) and
				   month(a.tglkirim)= (select periode_bulan from ms_identity) and
				   a.kodecbg = '$unit'
				order by
				   a.tglkirim, a.kodekirim desc";

		  $bulan  = $this->M_global->_periodebulan();
          $nbulan = $this->M_global->_namabulan($bulan);
		  $periode= 'Periode '.$nbulan.'-'.$this->M_global->_periodetahun();	   
	      $d['keu']     = $this->db->query($q1)->result();		  
          $d['periode'] = $periode;
          $level=$this->session->userdata('level');		
		  $akses= $this->M_global->cek_menu_akses($level, 422);
		  $d['akses']= $akses;		  
		  $this->load->view('penjualan/v_penjualan_pengiriman',$d);			   
		
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
				"select a.kodekirim, a.tglkirim, b.nama, a.keterangan, a.statusid
				from
				   ar_kirim a,
				   ar_customer b
				where
				   a.kodecust=b.kode and
				   a.tglkirim between '$_tgl1' and '$_tgl2' and				   
				   a.kodecbg = '$unit'
				order by
				   a.tglkirim, a.kodekirim desc";
		  
		  $periode= 'Periode '.date('d-m-Y',strtotime($tgl1)).' s/d '.date('d-m-Y',strtotime($tgl2));	   
	      $d['keu'] = $this->db->query($q1)->result();
		  $level=$this->session->userdata('level');		
		  $akses= $this->M_global->cek_menu_akses($level, 422);
		  $d['akses']= $akses;
          $d['periode'] = $periode;		  
		  $this->load->view('penjualan/v_penjualan_pengiriman',$d);			   
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
		  
		    $queryh = "select * from ar_kirim inner join ar_customer on ar_kirim.kodecust=ar_customer.kode where kodekirim = '$param'";
			$queryd = "select * from ar_kirimdetil inner join inv_barang on ar_kirimdetil.kodeitem=inv_barang.kodeitem where ar_kirimdetil.kodekirim = '$param'";
			 
		    $detil  = $this->db->query($queryd)->result();
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
			$judul=array('Kepada :','','SURAT JALAN');
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
			
			
			$pdf->FancyRow(array($header->nama,'','Nomor',':',$header->kodekirim), $fc, $border);
			$pdf->FancyRow(array($header->alamat1,'','Tanggal',':',date('d-m-Y',strtotime($header->tglkirim))), $fc, $border);
			$pdf->FancyRow(array($header->alamat2,'','Pengiriman',':',''), $fc, $border);
			$fc     = array('0','0','0','0','0');
			$pdf->FancyRow(array($header->telp,'','','',''), $fc, $border);
			$pdf->ln(2);
						
			$pdf->SetAligns(array('L','C','R'));
			//$pdf->SetFillColor(0,0,139);
			//$pdf->settextcolor(255,255,255);
			$fc = array('0','0','0','0','0','0');
			
			$pdf->SetWidths(array(30,105, 25,30));
			$border = array('TB','TB','TB','TB');
			$align  = array('L','L','R','L');
			$pdf->setfont('Arial','B',10);
			
			$judul=array('Kode Barang','Nama Barang','Qty','Satuan');						
			$pdf->FancyRow2(8,$judul,$fc, $border,$align);
			$border = array('','','');
			$pdf->setfont('Arial','',10);
			$tot = 0;
			$subtot = 0;
			$tdisc  = 0;
			$border = array('','','','');
			$align  = array('L','L','R','L');
			$fc = array('0','0','0','0');
			$pdf->SetFillColor(0,0,139);
			$pdf->settextcolor(0);			
			$tot = 0;
			$qty = 0;
			foreach($detil as $db)
			{
			  $qty = $qty + $db->qtykirim;
			  
			  $pdf->FancyRow(array(
			  $db->kodeitem, 
			  $db->namabarang,
			  $db->qtykirim,
			  $db->satuan),$fc, $border, $align);
			  $tot++;
			}
			$pdf->SetFillColor(230);
			$border = array('B','B','B','B');
			$pdf->FancyRow(array('', '', '',''),0, $border);
			$pdf->ln(2);
			$pdf->SetWidths(array(100,20,30,40));
			$border = array('TB','','T','T');
			$align  = array('L','','L','R');
			//$pdf->SetFillColor(230,230,230);
			//$pdf->settextcolor(0);
			$fc = array('0','0','0','0');
			$border = array('TB','','T','T');
			$align  = array('L','','L','R');
			$pdf->FancyRow(array('Keterangan', '','Total Kuantitas', $qty),$fc, $border, $align);
			$border = array('','','B','B');
			$pdf->FancyRow(array($header->keterangan,'', 'Jumlah Barang', $tot),$fc, $border, $align);
			$border = array('B','','','');
			$pdf->FancyRow(array('','', '', ''),$fc,$border, $align);
			$pdf->SetWidths(array(50,50,50));
			$pdf->SetFont('Arial','',9);
			$pdf->SetAligns(array('C','C','C'));
			$pdf->ln(5);
			$border = array('','','');
			$align  = array('C','C','C');
			$fc = array('0','0','0');
			$pdf->FancyRow(array('Finance','Gudang','Penerima'),$fc, $border, $align);
			$pdf->ln(1);
			$pdf->ln(15);
			$pdf->SetWidths(array(49,2,49,2,49));
			$pdf->SetFont('Arial','',8);
			$pdf->SetAligns(array('C','C','C','C','C'));
			$border = array('B','','B','','B');	
			$pdf->FancyRow(array('','','','',''),0, $border,$align);
			$border = array('','','','','');	
			$align  = array('L','C','L','C','L');
			$pdf->FancyRow(array('Tgl.','','Tgl.','','Tgl.'),0,$border,$align);
						
			$pdf->AliasNbPages();
			$pdf->output($param.'.PDF','I');			
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	public function getpo($so)
	{   	            
		$this->db->select('ar_sodetail.*, inv_barang.namabarang, , ar_sodetail.qtyorder-ar_sodetail.qtykirim as sisa', false);
		$this->db->from('ar_sodetail');
		$this->db->join('inv_barang','inv_barang.kodeitem=ar_sodetail.kodeitem','left');
		$this->db->where('ar_sodetail.kodeso',$so);
		$this->db->where('ar_sodetail.qtyorder > ar_sodetail.qtykirim');		
		$data = $this->db->get()->result();		
		echo json_encode($data);
	}
	
	public function getlistpo( $cust )
	{
		if(!empty($cust))
		{
		    $po  = $this->db->get_where('ar_sofile',array('kodecust' => $cust, 'statusid' => 1))->result();	
			?>						
			<select name="kodeso" id="kodeso" class="form-control  input-medium select2me"  >            											
			  <option value="">-- Tanpa SO ---</option>
			  <?php 			    
				foreach($po  as $row){	
				?>
				<option value="<?php echo $row->kodeso;?>"><?php echo $row->kodeso;?></option>
				
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
          $d['cust'] = $this->db->order_by('nama')->get_where('ar_customer',array('nama !=' => ''))->result();
		  $d['unit'] = $this->db->get('ms_unit')->result();
		  $d['gudang']  = $this->db->get('inv_gudang')->result();
		  $d['nomor']= $this->M_global->_Autonomor('SD');
		  $this->load->view('penjualan/v_penjualan_pengiriman_add',$d);				
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
           $kodeso = $this->db->get_where('ar_kirim', array('kodekirim' => $nomor))->row()->kodeso; 	
	       if($kodeso!=""){				
				   $datalpb = $this->db->get_where('ar_kirimdetil', array('kodekirim' => $nomor))->result();
				   foreach($datalpb as $row){					 
					 $this->db->query('update ar_sodetail set qtykirim = qtykirim - '.$row->qtykirim.' where kodeitem = "'.$row->kodeitem.'" and kodeso = "'.$kodeso.'"');				    
				   }				   				   				   		     	
			}
				
		   $this->db->delete('ar_kirim',array('kodekirim' => $nomor));
		   $this->db->delete('ar_kirimdetil',array('kodekirim' => $nomor));	

		   $total = $this->db->query("select sum(qtyorder-qtykirim) as total from ar_sodetail where kodeso = '$kodeso' ")->row()->total;
			
		   if($total<=0){
			  $this->db->query("update ar_sofile set statusid=2 where kodeso = '$kodeso'");	
		   } else {
			  $this->db->query("update ar_sofile set statusid=1 where kodeso = '$kodeso'");	 
		   }		
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
	
	public function getinfobarang( $kode )
	{
		$data = $this->M_global->_data_barang( $kode );
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
		$cek = $this->session->userdata('level');
		if(!empty($cek))
		{			            
			$nobukti  = $this->input->post('nomorbukti');			
			$gudang   = $this->input->post('gudang');			
			$userid   = $this->session->userdata('username');
			$kodeso   = $this->input->post('kodeso');			
				
            if($kodeso!=""){				
				if($param==2){
				   $datalpb = $this->db->get_where('ar_kirimdetil', array('kodekirim' => $nobukti))->result();
				   foreach($datalpb as $row){					 
					 $this->db->query('update ar_sodetail set qtykirim = qtykirim - '.$row->qtykirim.' where kodeitem = "'.$row->kodeitem.'" and kodeso = "'.$kodeso.'"');				    
					 $this->db->query('update inv_barang set stok = stok + '.$row->qtykirim.' where kodeitem = "'.$row->kodeitem.'"');	
					 $this->db->query('update inv_barang_gudang set qty = qty + '.$row->qtykirim.' where gudang = "'.$gudang.'" and kodeitem = "'.$row->kodeitem.'"');	
					 $this->db->delete('inv_transaksi',array('nobukti' => $nobukti, 'kodeitem' => $row->kodeitem));
				   }				   				   				   		  
				}   		
			}
				  			
			$this->db->delete('ar_kirimdetil',array('kodekirim' => $nobukti));
			$kode  = $this->input->post('kode');
			$qty   = $this->input->post('qty');
		    $sat   = $this->input->post('sat');
			
			$jumdata  = count($kode);
				
			$nourut = 1;			
			for($i=0;$i<=$jumdata;$i++)
			{
			    $_kode   = $kode[$i];
				
				$hpp = $this->db->get_where('inv_barang', ['kodeitem' => $_kode])->row()->hargabeli;
			    $datad = array(
				'kodekirim'   => $nobukti,
				'kodeitem' => $_kode,
				'qtykirim' => $qty[$i],
				'satuan' => $sat[$i],
				'gudang' => $gudang,
			    );
				
				$data_inv = array(
				'tanggal'   => date('Y-m-d'),
				'kodeitem' => $_kode,
				'penerimaan' => 0,
				'pengeluaran' => $qty[$i],
				'satuan' => $sat[$i],
				'jenis' => 'Penjualan',
				'nobukti' => $nobukti,
				'tglrekam'   => date('Y-m-d H:i:s'),
				'hpp'   => $hpp,
				'hargajual'   => 0,
				'gudang' => $gudang,
			    );
				
				if($_kode!=""){
			      $this->db->insert('ar_kirimdetil', $datad);
				  $this->db->insert('inv_transaksi', $data_inv);	                   		 		   
				  $this->db->query('update inv_barang set stok = stok - '.$qty[$i].' where kodeitem = "'.$_kode.'"');		
		 		  $this->db->query('update inv_barang_gudang set qty = qty - '.$qty[$i].' where gudang = "'.$gudang.'" and kodeitem = "'.$_kode.'"');	                  		
				  if($kodeso!=""){					  				  
				    $this->db->query('update ar_sodetail set qtykirim = qtykirim + '.$qty[$i].' where kodeitem = "'.$_kode.'" and kodeso = "'.$kodeso.'"');				  
				  }
				}
			}
			
			$data = array(
			    'kodecbg' => $this->session->userdata('unit'),
				'kodecust' => $this->input->post('cust'),
				'kodekirim'  => $this->input->post('nomorbukti'),
				'kodeso'  => $this->input->post('kodeso'),
				'gudang'  => $this->input->post('gudang'),
				'tglkirim'   => date('Y-m-d',strtotime($this->input->post('tanggal'))),
				'kodeuser'=> $userid,
				'keterangan' => $this->input->post('keterangan'),				
				'alamat' => $this->input->post('alamat'),				
				'statusid'=> 1,				
			);
						
			$total = $this->db->query("select sum(qtyorder-qtykirim) as total from ar_sodetail where kodeso = '$kodeso' ")->row()->total;
			
			if($total<=0){
			  $this->db->query("update ar_sofile set statusid=2 where kodeso = '$kodeso'");	
			}			
			
			if($param==1)
			{
			  $this->db->insert('ar_kirim',$data);	
			  $this->M_global->_updatecounter1('SD');
			} else {
			  $this->db->update('ar_kirim',$data, array('kodekirim' => $nobukti));	
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
					
			$header = $this->db->get_where('ar_kirim', array('kodekirim' => $nomor));
			$detil  = $this->db->join('inv_barang','inv_barang.kodeitem=ar_kirimdetil.kodeitem')->get_where('ar_kirimdetil', array('kodekirim' => $nomor));
		
			$d['header']  = $header->result();
			$d['detil']   = $detil->result();			
			$d['jumdata1']= $detil->num_rows();	
			$d['cust'] = $this->db->order_by('nama')->get_where('ar_customer',array('nama !=' => ''))->result();
		    $d['unit'] = $this->db->get('ms_unit')->result();
			$d['gudang']  = $this->db->get('inv_gudang')->result();
			$this->load->view('penjualan/v_penjualan_pengiriman_edit',$d);
			} else
		{
			header('location:'.base_url());
		}	
	}
}
/* End of file keuangan_saldo.php */
/* Location: ./application/controllers/keuangan_saldo.php */