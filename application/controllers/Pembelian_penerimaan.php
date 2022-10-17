<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_penerimaan extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->session->set_userdata('menuapp', '3000');
		$this->session->set_userdata('submenuapp', '3102');
		$this->load->helper('simkeu_nota');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        		
		if(!empty($cek))
		{	
		  	 
		  $q1 = 
				"select *
				from
				   tbl_baranghterima a,
				   tbl_vendor b
				where
				   a.vendor_id=b.vendor_id
				order by
				   a.terima_date, a.terima_no desc";

		  $bulan  = $this->M_global->_periodebulan();
          $nbulan = $this->M_global->_namabulan($bulan);
		  $periode= 'Periode '.$nbulan.'-'.$this->M_global->_periodetahun();	   
	      $d['keu']     = $this->db->query($q1)->result();		  
          $d['periode'] = $periode;		  
		  $level=$this->session->userdata('level');		
		  $akses= $this->M_global->cek_menu_akses($level, 3102);		  
		  $d['akses']= $akses;
		  $this->load->view('pembelian/v_pembelian_penerimaan',$d);			   
		
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
				"select *
				from
				   tbl_baranghterima a,
				   tbl_vendor b
				where
				   a.vendor_id=b.vendor_id and
				   a.terima_date between '$_tgl1' and '$_tgl2' and
				   a.koders = '$unit'
				order by
				   a.terima_date, a.terima_no desc";
				   				
		  
		  $periode= 'Periode '.date('d-m-Y',strtotime($tgl1)).' s/d '.date('d-m-Y',strtotime($tgl2));	   
	      $d['keu'] = $this->db->query($q1)->result();
          $d['periode'] = $periode;	
          $level=$this->session->userdata('level');		
		  $akses= $this->M_global->cek_menu_akses($level, 3102);
		  $d['akses']= $akses;		  
		  $this->load->view('pembelian/v_pembelian_penerimaan',$d);			   
		}
		} else
		{
			
			header('location:'.base_url());
			
		}
			
		
	}
		
	
	
	
	public function cetak()
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');
        $user= $this->session->userdata('username');		
		if(!empty($cek))
		{				  		 
            
		    $unit= $this->session->userdata('unit');	 
			$profile = data_master('tbl_namers', array('koders' => $unit));
		    $nama_usaha=$profile->namars;
			$alamat1  = $profile->alamat;
			$alamat2  = $profile->kota;
			
			$param = $this->input->get('id');
		  
		    $queryh = "select * from tbl_baranghterima inner join 
			tbl_vendor on tbl_baranghterima.vendor_id=tbl_vendor.vendor_id 
			where tbl_baranghterima.terima_no = '$param'";
			
			$queryd = "select tbl_barangdterima.*, tbl_barang.namabarang from tbl_barangdterima inner join 
			tbl_barang on tbl_barangdterima.kodebarang=tbl_barang.kodebarang
			where terima_no = '$param'";
			
		    $detil  = $this->db->query($queryd)->result();
			$header = $this->db->query($queryh)->row();
						
		    $pdf=new simkeu_nota();
			$pdf->setID($nama_usaha,$alamat1,$alamat2);
			$pdf->setjudul('');
			$pdf->setsubjudul('');
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			
			$pdf->SetWidths(array(190));
			
			$pdf->setfont('Arial','B',18);
			$pdf->SetAligns(array('C','C','C'));
			$border = array('BTLR');
			$size   = array('');
			$align = array('C');
			$style = array('B');
			$size  = array('18');
			$max   = array(20);
			$fc     = array('0');
			$hc     = array('20');
			$judul=array('SURAT PERNYATAAN & PEMBELIAN BARANG');
			$pdf->FancyRow2(10,$judul, $fc,  $border, $align, $style, $size, $max);
			$size  = array('10');
			$align = array('L');
			$border = array('');
			
			
			
			$pdf->ln(1);
			$pdf->setfont('Arial','B',10);
			$pdf->SetWidths(array(20,5,80,30,5,50));
			$border = array('LT','T','T','T','T','TR');
			$fc     = array('0','0','0','0','0','0');
			$pdf->SetFillColor(230,230,230);			
			$pdf->setfont('Arial','',9);
			
			
			$pdf->FancyRow(array('Terima dari',':',$header->vendor_name,'BAPB No.',':',$header->terima_no), $fc, $border);
			$border = array('L','','','','','R');
			$pdf->FancyRow(array('','',$header->alamat,'Tgl Faktur',':',date('d-m-Y',strtotime($header->terima_date))), $fc, $border);
			$pdf->FancyRow(array('','','','Tgl Penerimaan',':',date('d-m-Y',strtotime($header->terima_date))), $fc, $border);
			$pdf->FancyRow(array('','','','No. Faktur',':',$header->invoice_no), $fc, $border);
			$pdf->FancyRow(array('','','','No. Surat Jalan',':',$header->sj_no), $fc, $border);
			$border = array('LB','B','B','B','B','BR');
			$pdf->FancyRow(array('','',$header->phone,'Gudang',':',$header->gudang), $fc, $border);
			
			
			$pdf->ln(2);
			$pdf->SetWidths(array(10,25,40,15,15,20,20,20,25));
			$border = array('LTB','TB','TB','TB','TB','TB','TB','TB','TBR');
			$align  = array('C','C','C','C','C','C','C','C','C');
			$pdf->setfont('Arial','B',9);
			$pdf->SetAligns(array('L','C','R'));
			$fc = array('0','0','0','0','0','0','0','0','0');
			$judul=array('No.','Kode Barang','Nama Barang','Qty','Satuan','HPP','Disc','Total','Po No');
			$pdf->FancyRow2(8,$judul,$fc, $border,$align);
			$pdf->setfont('Arial','',9);
			$tot = 0;
			$subtot = 0;
			$tdisc  = 0;
			$border = array('L','','','','','','','','R');			
			$align  = array('L','L','L','R','R','R','R','R','L');
			$style = array('','','','','','','','','');
			$size  = array('8','8','8','8','8','8','8','8','8');
			$max   = array(2,2,2,2,2,2,2,2,2);
			$fc     = array('0','0','0','0','0','0','0','0','0');			
			$no =1 ;
			$totitem = 0;
			$tot = 0;
			foreach($detil as $db)
			{
			  $hpp = data_master('tbl_barang', array('kodebarang' => $db->kodebarang))->hpp;
			  $tot += $db->totalrp;
			  $pdf->FancyRow(array(
			  $no,
			  $db->kodebarang, 
			  $db->namabarang,
			  $db->qty_terima,
			  $db->satuan,
			  number_format($hpp,2,',','.'),
			  $db->discount,
			  number_format($db->totalrp,2,',','.'),
			  $db->po_no
			  ),$fc,  $border, $align, $style, $size, $max);
			  
			  $no++;
			}
			$discount = $header->diskontotal;
			$ppn = $header->vatrp;
			$materai = $header->materai;
			$totalnet = $tot+$ppn+$materai-$discount;
			$pdf->SetWidths(array(10,25,40,15,15,20,20,20,25)); 			
			$pdf->SetWidths(array(4,40,30,50,20,20,25));
			$border = array('T','T','T','T','T','T','T');
			$align  = array('L','C','C','C','R','R');
			$style = array('','B','','','','');
			$judul=array('','','','','TOTAL',number_format($tot,2,',','.'),'');
			$pdf->FancyRow2(4,$judul, $fc,  $border, $align, $style, $size, $max);
			$judul=array('','Form Rangkap 2','','','Discount',number_format($discount,2,',','.'));
			$border = array('','LTR','','','','');
			$pdf->FancyRow2(4,$judul, $fc,  $border, $align, $style, $size, $max);
			$border = array('T','LTR','T','T','T','T');
			$judul=array('','Merah : untuk supplier','','','PPN',number_format($ppn,2,',','.'));
			$border = array('','LR','','','','');
			$pdf->FancyRow2(4,$judul, $fc,  $border, $align, $style, $size, $max);
			$judul=array('','Putih : untuk keuangan','','','Materai',number_format($materai,2,',','.'));
			//$border = array('','LRB','','','');
			$pdf->FancyRow2(4,$judul, $fc,  $border, $align, $style, $size, $max);
			$judul=array('','','','','Total Net',number_format($totalnet,2,',','.'),'');
			$border = array('','T','','','B','B','B',);
			$style = array('','B','','','B','B','');
			$pdf->FancyRow2(4,$judul, $fc,  $border, $align, $style, $size, $max);
			
			$pdf->SetWidths(array(63.3,63.3,63.3)); 			
			$border = array('TBLR','TBLR','TBLR');
			$align  = array('C','C','C');
			$style = array('','','');
			$align  = array('C','C','C');
			$border = array('','','');
			$judul=array('','',$alamat2.', '.date('d-m-Y'));
			$pdf->ln();
			$pdf->FancyRow2(3,$judul, $fc,  $border, $align, $style, $size, $max);
			$align  = array('C','C','C');
			$border = array('TBLR','TBLR','TBLR');
			$judul=array('Diketahui oleh,','Diterima Oleh,','Dibuat Oleh,');
			$pdf->ln();
			$pdf->FancyRow2(5,$judul, $fc,  $border, $align, $style, $size, $max);
			$judul=array('','','');
			$pdf->FancyRow2(20,$judul, $fc,  $border, $align, $style, $size, $max);
			$judul=array('','','');
			$pdf->FancyRow2(5,$judul, $fc,  $border, $align, $style, $size, $max);
			$judul=array('KEPALA APOTEKTER','','PENANGGUNG JAWAB ADM');
			$pdf->FancyRow2(5,$judul, $fc,  $border, $align, $style, $size, $max);
			
			
			
			
            $pdf->setTitle($param);
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
        $this->db->select('tbl_barangdpo.*, tbl_barang.namabarang, tbl_barangdpo.qty_po-tbl_barangdpo.qty_terima as sisa', false);
		$this->db->from('tbl_barangdpo');
		$this->db->join('tbl_barang','tbl_barang.kodebarang=tbl_barangdpo.kodebarang','left');
		$this->db->where('tbl_barangdpo.po_no',$po);
		$this->db->where('tbl_barangdpo.qty_po > tbl_barangdpo.qty_terima');		
		$data = $this->db->get()->result();	
		
		echo json_encode($data);
	}
	
	public function getlistpo( $supp )
	{
		if(!empty($supp))
		{
		    $po  = $this->db->get_where('tbl_baranghpo',array('vendor_id' => $supp, 'closed' => 0))->result();	
			?>						
			<select name="kodepo" id="kodepo" class="form-control  input-medium select2me"  >            											
			  <option value="">-- Tanpa PO ---</option>
			  <?php 			    
				foreach($po  as $row){	
				?>
				<option value="<?php echo $row->po_no;?>"><?php echo $row->po_no;?></option>
				
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
          //$d['nomor']= urut_transaksi('URUT_BHP', 19);		  
		  $d['nomor']= 'Auto';
		  $this->load->view('pembelian/v_pembelian_penerimaan_add',$d);				
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
	       $hbeli  = $this->db->query("select * from tbl_baranghterima where terima_no = '$nomor'")->row();	
		   $cabang  = $hbeli->koders;
		   $gudang  = $hbeli->gudang;
		
	       $databeli = $this->db->get_where('tbl_barangdterima', array('terima_no' => $nomor))->result();
		   foreach($databeli as $row){					 
			 
			 $_qty  = $row->qty_terima;
			 $_kode = $row->kodebarang;
			 
			 $this->db->query("update tbl_barangstock set terima=terima- $_qty, saldoakhir= saldoakhir- $_qty where kodebarang = '$_kode'
			 and koders = '$cabang' and gudang = '$gudang'"
		   );
		  
								 
		   }
		   $this->db->delete('tbl_barangdterima',array('terima_no' => $nomor));
		   $this->db->delete('tbl_baranghterima',array('terima_no' => $nomor));
		   		   		   
		   
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
	        $cabang   = $this->session->userdata('unit');
			
			$gudang   = $this->input->post('gudang');			
			$userid   = $this->session->userdata('username');
			$kodepo   = $this->input->post('kodepo');			
				
			if($param==1){	
			  $nobukti  = urut_transaksi('SJ_TERIMA', 19);
			} else {
			  $nobukti  = $this->input->post('nomorbukti');				
			}
			
            //if($kodepo!="")
			{				
				if($param==2){
				   $datalpb = $this->db->get_where('tbl_barangdterima', array('terima_no' => $nobukti))->result();
				   foreach($datalpb as $row){					 
					 $this->db->query('update tbl_barangdpo set qty_terima = qty_terima - '.$row->qty_terima.' where kodebarang = "'.$row->kodebarang.'" and po_no = "'.$kodepo.'"');				    
					 
					 $_qty  = $row->qty_terima;
					 $_kode = $row->kodebarang;
					 
					 $this->db->query("update tbl_barangstock set terima=terima- $_qty, saldoakhir= saldoakhir- $_qty where kodebarang = '$_kode'
				     and koders = '$cabang' and gudang = '$gudang'"
				   );
				  
					 					 
				   }	
                   $this->db->delete('tbl_barangdterima',array('terima_no' => $nobukti));				   
				}   		
			}
				  			
			
			$kode  = $this->input->post('kode');
			$qty   = $this->input->post('qty');
		    $sat   = $this->input->post('sat');
			
			$jumdata  = count($kode);
				
			$nourut = 1;	
            $total	= 0;		
			for($i=0;$i<=$jumdata-1;$i++)
			{
			    $_kode   = $kode[$i];
				
				$_qty    = $qty[$i];
				
				$hpp = $this->db->get_where('tbl_barang', array('kodebarang' => $_kode))->row()->hpp;
				
				$total += $hpp*$qty[$i];
				
			    $datad = array(
				'koders'      => $cabang,
				'terima_no'   => $nobukti,
				'po_no'       => $kodepo,
				'kodebarang'  => $_kode,
				'qty_terima'  => $qty[$i],
				'satuan'      => $sat[$i]				
			    );
								
				if($_kode!=""){
			      $this->db->insert('tbl_barangdterima', $datad);	
				  
                  $this->db->query("update tbl_barangstock set terima=terima+ $_qty, saldoakhir= saldoakhir+ $_qty where kodebarang = '$_kode'
				    and koders = '$cabang' and gudang = '$gudang'"
				   );
				  
				  
				  				  
				  if($kodepo!=""){					  				  
				    $this->db->query('update tbl_barangdpo set qty_terima = qty_terima + '.$qty[$i].' where kodebarang = "'.$_kode.'" and po_no = "'.$kodepo.'"');
					
					 
				  }
				}
			}
			
			$data = array(
			    'koders'    => $this->session->userdata('unit'),
				'vendor_id' => $this->input->post('supp'),
				'terima_no' => $nobukti,
				'invoice_no' => $this->input->post('kodepo'),
				'sj_no'     => $this->input->post('noterima'),
				'gudang'    => $this->input->post('gudang'),
				'terima_date' => date('Y-m-d',strtotime($this->input->post('tanggal'))),
				//'due_date'=> date('Y-m-d',strtotime($this->input->post('tanggalkirim'))),				
			);
				
            /*				
			$this->M_global->_hapusjurnal($this->input->post('nomorbukti'), 'JP');	
			
			$profile = $this->M_global->_LoadProfileLap();			
			$akun_kredit     = $profile->akun_hutangbeli;
			$akun_debet      = $profile->akun_persediaan;
			
			$this->M_global->_rekamjurnal(
			date('Y-m-d',strtotime($this->input->post('tanggal'))),
			$this->input->post('nomorbukti'),
			'JP',
			$this->input->post('kodepo'),
			1,
			$akun_debet,
			'Penerimaan Barang',
			'Penerimaan Barang',
			$total,
			0
			);
			
			$this->M_global->_rekamjurnal(
			date('Y-m-d',strtotime($this->input->post('tanggal'))),
			$this->input->post('nomorbukti'),
			'JP',
			$this->input->post('kodepo'),
			2,
			$akun_kredit,
			'Penerimaan Barang',
			'Penerimaan Barang',
			0,
			$total
			);
			*/
			
			//update status po menjadi 2 
			
			/*
			$total = $this->db->query("select sum(qtyorder-qtykirim) as total from ap_podetail where kodepo = '$kodepo' ")->row()->total;
			
			if($total<=0){
			  $this->db->query("update ap_pofile set statusid=2 where kodepo = '$kodepo'");	
			}
			*/
			
			
			if($param==1)
			{
			  $this->db->insert('tbl_baranghterima',$data);				  
			} else {
			  $this->db->update('tbl_baranghterima',$data, array('terima_no' => $nobukti));	
			}
			
		     echo json_encode(array("status" => TRUE,"nomor" => $nobukti)); 
			
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
					
			$header = $this->db->get_where('tbl_baranghterima', array('terima_no' => $nomor));
			$detil  = $this->db->join('tbl_barang','tbl_barang.kodebarang=tbl_barangdterima.kodebarang')->get_where('tbl_barangdterima', array('terima_no' => $nomor));
		
			$d['header']  = $header->result();
			$d['detil']   = $detil->result();			
			$d['jumdata1']= $detil->num_rows();	
			$d['supp'] = $this->db->order_by('vendor_name')->get_where('tbl_vendor',array('vendor_name !=' => ''))->result();
		    $d['unit'] = $this->db->get('tbl_namers')->result();
			$d['gudang']  = $this->db->get('tbl_depo')->result();
			$this->load->view('pembelian/v_pembelian_penerimaan_edit',$d);
			} else
		{
			header('location:'.base_url());
		}	
	}
}
/* End of file keuangan_saldo.php */
/* Location: ./application/controllers/keuangan_saldo.php */