<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hutang extends CI_Controller {

	
	
	public function __construct()
	{
		parent::__construct();
		$this->session->set_userdata('menuapp', '5000');
		$this->session->set_userdata('submenuapp', '5201');
		$this->load->helper('simkeu_nota');
		$this->load->model('M_hutang');
		$this->load->model('M_cetak');
		$this->load->model('M_rs');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        		
		if(!empty($cek))
		{	
			
		//   $tahun 		= $this->M_global->_periodetahun();
  
		//   $bulan  		= $this->M_global->_periodebulan();
		//   $nbulan 		= $this->M_global->_namabulan($bulan);

		//   $bln 			= count($bulan) == 1 ? '0'.$bulan : $bulan;
		//   $jmlhari 		= cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
		  
		  $d['startdate'] 	= $this->input->get('start_date') ?? date("Y-m").'-01';
		  $d['enddate'] 	= $this->input->get('end_date') ?? date("Y-m-t", strtotime("now"));

		  $dt1						= $this->M_hutang->totalHutang($unit, '', $d['startdate'], $d['enddate']);
		  $d['total_hutang'] 		= $dt1[0]->total_hutang;
		  $dt2						= $this->M_hutang->hutangJatuhTempo($unit, '', $d['startdate'], $d['enddate']);
		  $d['hutang_jatuh_tempo']	= $dt2[0]->hutang_jatuh_tempo;
		  $dt3						= $this->M_hutang->rencanaBayar($unit, $d['startdate'], $d['enddate']);
		  $d['rencana_bayar']		= $dt3[0]->rencana_bayar;
		  $dt4						= $this->M_hutang->realisasiPembayaran($unit, '', $d['startdate'], $d['enddate']);
		  $d['realisasi_pembayaran']= $dt4[0]->realisasi_pembayaran;

		  $periode		= 'Periode '.local_date($d['startdate'], "dd MMMM yyyy") . " - ".local_date($d['enddate'], "dd MMMM yyyy");
		  $d['vendor'] 	= '';	 
		  $d['vendorid'] = ''; 
		  $d['keu'] = '';
		  $d['list_vendor'] 	= $this->M_global->getListVendor(); 
	      $d['keu'] 	= $this->M_hutang->get_ap_summary($d['startdate'], $d['enddate'], $unit);

          $d['periode'] = $periode;
          $level=$this->session->userdata('level');		
		  $akses= $this->M_global->cek_menu_akses($level, 5201);
		  $d['akses']= $akses;	
		  $this->load->view('pembelian/v_hutang',$d);			   
		
		} else
		{
			
			header('location:'.base_url());
			
		}
	}

	/**
	 * Method for showing vendors ap_details
	 * 
	 * @param string $vendor_id
	 */
	public function details ($vendor_id)
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        		
		if(!empty($cek))
		{	  
		  $d['startdate'] 	= $this->input->get('startdate');
		  $d['enddate'] 	= $this->input->get('enddate');
		  $periode		= 'Periode '.local_date($d['startdate'], "dd MMMM yyyy") . " - ".local_date($d['enddate'], "dd MMMM yyyy");
	      $d['keu'] 	= $this->M_hutang->get_vendor_details($d['startdate'], $d['enddate'], urldecode($vendor_id), $unit);
          $d['periode'] = $periode;
		  $d['vendor'] = $this->M_hutang->get_vendor(urldecode($vendor_id));
          $level=$this->session->userdata('level');		
		  $akses= $this->M_global->cek_menu_akses($level, 5201);
		  $d['akses']= $akses;	
		  $d['unit'] = $unit;

		  $this->load->view('pembelian/v_detail_hutang',$d);			   
		} else
		{
			
			header('location:'.base_url());
			
		}
	}

	public function detailTotalHutang(){
		$d['subtitle']	= 'Total Hutang';
		$d['periode'] 	= '';	
		
		$cek = $this->session->userdata('level');	
		if(!empty($cek))
		{
			$d['startdate'] = $this->input->get('startdate');
			$d['enddate'] = $this->input->get('enddate');
			$d['vendor'] = $this->input->get('vendor');	
			$d['jenis'] = 'all';
			$d['unit'] = $this->session->userdata('unit');

			$this->load->view('pembelian/v_detailhutang',$d);	
		} else
		{
			header('location:'.base_url());	
		}
	}

	public function detailHutangJatuhTempo(){
		$d['subtitle']	= 'Hutang Jatuh Tempo';
		// $d['vendor'] 	= 'Semua Vendor';	
		$d['periode'] 	= '';
		
		$cek = $this->session->userdata('level');	
		if(!empty($cek))
		{		
			$d['startdate'] = $this->input->get('startdate');
			$d['enddate'] = $this->input->get('enddate');
			$d['vendor'] = $this->input->get('vendor');	
			$d['jenis'] = 'expired';
			$d['unit'] = $cek = $this->session->userdata('unit');

			$this->load->view('pembelian/v_detailhutang',$d);	
		} else
		{
			header('location:'.base_url());	
		}
	}
	
	public function detailRencanaBayar(){
		$d['subtitle']	= 'Rencana Bayar';
		$d['periode'] 	= 'expired';
		
		$cek = $this->session->userdata('level');	
		if(!empty($cek))
		{			
			$d['startdate'] = $this->input->get('startdate');
			$d['enddate'] = $this->input->get('enddate');
			$d['vendor'] = $this->input->get('vendor');
			$d['unit'] = $cek = $this->session->userdata('unit');

			$d['jenis'] = 'payment_schedule';
			$this->load->view('pembelian/v_detailhutang',$d);	
		} else
		{
			header('location:'.base_url());	
		}
	}

	public function detailRealisasiPembayaran(){
		$d['subtitle']	= 'Realisasi Pembayaran';
		$d['periode'] 	= '';
		
		$cek = $this->session->userdata('level');	
		if(!empty($cek))
		{		
			$d['startdate'] = $this->input->get('startdate');
			$d['enddate'] = $this->input->get('enddate');
			$d['vendor'] = $this->input->get('vendor');
			$d['unit'] = $cek = $this->session->userdata('unit');	

			$d['jenis'] = 'realization';
			$this->load->view('pembelian/v_detailhutang',$d);	
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
		 $vendor= $this->input->get('vendor');	
		 
		 $d['vendorid'] = $vendor;

			$d['startdate'] 			= $_tgl1;
			$d['enddate'] 				= $_tgl2;
			$d['list_vendor'] 			= $this->M_global->getListVendor(); 
			$dt1						= $this->M_hutang->totalHutang($unit, $vendor, $d['startdate'], $d['enddate']);
			$d['total_hutang'] 			= $dt1[0]->total_hutang;
			$dt2						= $this->M_hutang->hutangJatuhTempo($unit, $vendor, $d['startdate'], $d['enddate']);
			$d['hutang_jatuh_tempo']	= $dt2[0]->hutang_jatuh_tempo;
			
			$dt3						= $this->M_hutang->rencanaBayar($unit, $vendor, $d['startdate'], $d['enddate']);
			$d['rencana_bayar']		= $dt3[0]->rencana_bayar;
			$dt4						= $this->M_hutang->realisasiPembayaran($unit, $vendor, $d['startdate'], $d['enddate']);
			$d['realisasi_pembayaran']= $dt4[0]->realisasi_pembayaran;
		 
		 if(!empty($jns))
		 {
			
			$d['vendor'] = '';
			if($vendor != '') {
				$dt_vendor = $this->M_global->getListVendorById($vendor);
				$d['vendor'] = $dt_vendor[0]->vendor_name; 
				$vendor = "AND b.vendor_id = '$vendor'";
			}

		  	$q1 = 
				"select *, a.id as idtr
				from
				   tbl_apoap a left outer join
				   tbl_vendor b
				   on a.vendor_id=b.vendor_id
				where
				   a.koders = '$unit' and
				   a.tglinvoice between '$_tgl1' and '$_tgl2'
				   $vendor 
				order by
				   a.tglinvoice, a.terima_no desc";
				
				
		  $periode= 'Periode '.date('d-m-Y',strtotime($tgl1)).' s/d '.date('d-m-Y',strtotime($tgl2));	   
	      $d['keu'] = $this->db->query($q1)->result();
          $d['periode'] = $periode;
          $level=$this->session->userdata('level');		
		  $akses= $this->M_global->cek_menu_akses($level, 5201);
		  $d['akses']= $akses;		  
		  $this->load->view('pembelian/v_hutang',$d);			   
		}
		} else
		{
			
			header('location:'.base_url());
			
		}
			
		
	}
		
	
	
	public function cetak2($param)
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
<select name="kodepo" id="kodepo" class="form-control  input-small select2me">
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
<select name="kodepb" id="kodepb" class="form-control  input-small select2me">
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
		  $d['nomor']= 'Auto'; // urut_transaksi('AP_APO',19);        
		  $d['nomortukarfaktur']= 'Auto'; // urut_transaksi('URUT_TUKAR',19);
		  $ppn = $this->M_global->getProsentasePpn();

		  $d['prosentase_ppn'] = $ppn[0]->prosentase;
		  $d['vendors'] = $this->db->select('vendor_id, vendor_name')->get('tbl_vendor')->result();
		  
		  $this->load->view('pembelian/v_hutang_add',$d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}

	public function ajax_delete()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{				  
			$id = $this->input->post('id');
			$del = $this->db->delete('tbl_hfaktur', array('id' => $id));
			if($del){
				
				echo json_encode(array("status" => TRUE));
			} else {
				echo json_encode(array("status" => FALSE));
			}
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}

	public function hapus($nomor){

		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{				  
			$qry_d = $this->db->delete('tbl_apoap',array('id' => $nomor));
			
			if($qry_d) echo json_encode(array("status" => TRUE));
			else echo json_encode(array("status" => FALSE));
		}
		else
		{	
			header('location:'.base_url());	
		}
	}

	public function hapus2($nomor)
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
                <a href="#"
                    onclick="post_value('<?php echo $row['kodeitem'];?>','<?php echo $row['namabarang'];?>','<?php echo $row['satuan'];?>','<?php echo $row['hargabeliakhir'];?>')">

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
	
	public function save($jenis)
	{
		$cek = $this->session->userdata('level');
		if(!empty($cek))
		{	
			$userid   	= $this->session->userdata('username');
			$unit      	= $this->session->userdata('unit');

			if($jenis == 1){
				$nomortukarfaktur	= urut_transaksi('URUT_TUKAR',19);
				$nomorbukti			= urut_transaksi('AP_APO',19);        
			} else {
				$nomortukarfaktur	= $this->input->post('nomortukarfaktur');
				$nomorbukti			= $this->input->post('nomorbukti');
			}

			$data = array(
			    'koders' 			=> $unit,
				'terima_no'			=> $nomorbukti,
				'invoice_no' 		=> $this->input->post('nomorfaktur'),
				'tukarfaktur' 		=> 0,
				'jenis' 			=> $this->input->post('jenis_faktur'),
				'vendor_id' 		=> $this->input->post('supplier'),
				'acbiaya' 			=> $this->input->post('acbiaya'),
				'tglinvoice' 		=> $this->input->post('tanggal_invoice'),
				'diambil'		 	=> $this->input->post('tanggal_ambil'),
				'duedate'	 		=> $this->input->post('jatuh_tempo'),
				'tglrencanabayar' 	=> $this->input->post('tanggal_rencana_bayar'),
				'totaltagihan'	 	=> str_replace(',','',$this->input->post('dpp')) + str_replace(',','',$this->input->post('ppnrp')) + str_replace(',','',$this->input->post('pph')) + str_replace(',','',$this->input->post('biayalain')) + str_replace(',','',$this->input->post('materai')),
				'materai' 			=> str_replace(',','',$this->input->post('materai')),
				'keterangan' 		=> $this->input->post('keterangan'),
				'dpp' 				=> str_replace(',','',$this->input->post('dpp')),
				'biayalain' 		=> str_replace(',','',$this->input->post('biayalain')),
				// 'jenis_ppn' 		=> $this->input->post('jenis_ppn'),
				'ppn' 				=> $this->input->post('ppn'),
				'ppnrp' 			=> str_replace(',','',$this->input->post('ppnrp')),
				'pph' 				=> str_replace(',','',$this->input->post('pph')),
				
				'username' => $userid,			
			);
			
			$where = array(
		    	'id' => $this->input->post('id'),
	        );
			
	        if($jenis==1)
			{
			  $this->db->insert("tbl_apoap", $data);

			  if ($this->input->post("bapb-aktiva") == "true") {
				$this->db->update("tbl_fixedasset", ["terima_no" => NULL], ["terima_no" => $data["terima_no"]]);
				$this->db->update("tbl_fixedasset", ["terima_no" => $data["terima_no"]], ["kodefix" => $this->input->post("kodefix")]);
			  }
			} else {
			  $this->db->update('tbl_apoap', $data, $where);

			  if ($this->input->post("bapb-aktiva") == "true") {
				$this->db->update("tbl_fixedasset", ["terima_no" => NULL], ["terima_no" => $data["terima_no"]]);
				$this->db->update("tbl_fixedasset", ["terima_no" => $data["terima_no"]], ["kodefix" => $this->input->post("kodefix")]);
			  }
			}
			
			echo $nomorbukti;
		}
		else
		{
			header('location:'.base_url());
		}
	}

	public function save2($param)
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
			$ppn = $this->M_global->getProsentasePpn();
			$d = [
				"faktur" => $this->db->where("terima_no", $nomor)->get("tbl_apoap")->row(),
				"aktiva" => $this->db->where("terima_no", $nomor)->get("tbl_fixedasset")->row(),
				'prosentase_ppn' => $ppn[0]->prosentase,
				'vendors' => $this->db->select('vendor_id, vendor_name')->get('tbl_vendor')->result()
			];

			$this->load->view('pembelian/v_hutang_edit',$d);
		} else {
			header('location:'.base_url());
		}
	}

	public function edit2($nomor)
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

	
	public function cetak($param){
		$cek  = $this->session->userdata('level');		
		if(!empty($cek))
		{	
			$unit = $this->session->userdata('unit');
			$profile = data_master('tbl_namers', array('koders' => $unit));
			$nama_usaha = $profile->namars;
			$alamat1  = isset($profile->alamat) ? $profile->alamat : $profile->alamat1;
			$alamat2  = $profile->kota;

			$header = $this->db->where("terima_no", $param)->join("tbl_vendor tv", "TRIM(ta.vendor_id) = TRIM(tv.vendor_id)", )->get("tbl_apoap ta")->row();

			$pdf = new simkeu_nota();
			$pdf->setID($nama_usaha, $alamat1, $alamat2);
			$pdf->setjudul('');
			$pdf->setsubjudul('');
			$pdf->addpage("P", "A4");
			$pdf->setsize("P", "A4");

			$pdf->SetWidths(array(190));

			$pdf->setfont('Arial', 'B', 18);
			$pdf->SetAligns(array('C', 'C', 'C'));
			$border = array('BTLR');
			$size   = array('');
			$align = array('C');
			$style = array('B');
			$size  = array('18');
			$max   = array(20);
			$fc     = array('0');
			$hc     = array('20');
			$judul = array('SURAT PERNYATAAN & PEMBELIAN AKTIVA');
			$pdf->FancyRow2(10, $judul, $fc,  $border, $align, $style, $size, $max);
			$size  = array('10');
			$align = array('L');
			$border = array('');



			$pdf->ln(1);
			$pdf->setfont('Arial', 'B', 10);
			$pdf->SetWidths(array(20, 5, 80, 30, 5, 50));
			$border = array('LT', 'T', 'T', 'T', 'T', 'TR');
			$fc     = array('0', '0', '0', '0', '0', '0');
			$pdf->SetFillColor(230, 230, 230);
			$pdf->setfont('Arial', '', 9);


			$pdf->FancyRow(array('Terima dari', ':', $header->vendor_name, 'BAPB No.', ':', $header->terima_no), $fc, $border);
			$border = array('L', '', '', '', '', 'R');
			$pdf->FancyRow(array('', '', (isset($header->alamat) ? trim($header->alamat) : trim($header->alamat1)), 'Tgl Faktur', ':', date('d-m-Y', strtotime($header->tglinvoice))), $fc, $border);
			$pdf->FancyRow(array('', '', '', 'Tgl Penerimaan', ':', date('d-m-Y', strtotime($header->tglinvoice))), $fc, $border);
			$pdf->FancyRow(array('', '', '', 'No. Faktur', ':', $header->invoice_no), $fc, $border);
			$border = array('LB', 'B', 'B', 'B', 'B', 'BR');
			$pdf->FancyRow(array('', '', $header->phone, 'Gudang', ':', "AKTIVA"), $fc, $border);


			$pdf->ln(2);
			$pdf->SetWidths(array(10, 25, 30, 15, 15, 20, 20, 20, 35));
			$border = array('LTB', 'TB', 'TB', 'TB', 'TB', 'TB', 'TB', 'TB', 'TBR');
			$align  = array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C');
			$pdf->setfont('Arial', 'B', 9);
			$pdf->SetAligns(array('L', 'C', 'R'));
			$fc = array('0', '0', '0', '0', '0', '0', '0', '0', '0');
			$judul = array('No.', 'Kode Barang', 'Nama Barang', 'Qty', 'Satuan', 'DPP', 'PPN', 'Materai', "Total");
			$pdf->FancyRow2(8, $judul, $fc, $border, $align);
			$pdf->setfont('Arial', '', 9);
			$tot = 0;
			$subtot = 0;
			$tdisc  = 0;
			$border = array('L', '', '', '', '', '', '', '', 'R');
			$align  = array('L', 'L', 'L', 'R', 'R', 'R', 'R', 'R', 'R');
			$style = array('', '', '', '', '', '', '', '', '');
			$size  = array('8', '8', '8', '8', '8', '8', '8', '8', '8');
			$max   = array(2, 2, 2, 2, 2, 2, 2, 2, 2);
			$fc     = array('0', '0', '0', '0', '0', '0', '0', '0', '0');
			$no = 1;
			$totitem = 0;
			$tot = 0;
			$diskon = 0;

			$detil = $this->db->where("terima_no", $param)->get("tbl_fixedasset")->result();
			foreach ($detil as $db) {
				$pdf->FancyRow(array(
					$no,
					$db->kodefix,
					$db->namafix,
					number_format($db->jumlah),
					"",
					number_format($header->dpp),
					number_format($header->ppnrp),
					number_format($header->materai),
					number_format($db->nilaiaktiva)
				), $fc,  $border, $align, $style, $size, $max);

				$no++;
			}
			$dpp = $header->dpp;
			$ppn = $header->ppnrp;;

			$materai = $header->materai;
			$totalnet = $header->totaltagihan;
			// $totalnet = str_replace(',', '.', $header->vatrp);
			$pdf->SetWidths(array(10, 25, 30, 15, 15, 20, 20, 20, 35));
			$pdf->SetWidths(array(4, 40, 30, 40, 20, 20, 35));
			$border = array('T', 'T', 'T', 'T', 'T', 'T', 'T');
			$align  = array('L', 'C', 'C', 'C', 'R', 'R');
			$style = array('', 'B', '', '', '', '');
			$judul = array('', '', '', '', 'TOTAL', number_format($tot), '');
			$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
			$judul = array('', 'Form Rangkap 2', '', '', 'Discount', "0",);
			$border = array('', 'LTR', '', '', '', '');
			$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
			$border = array('T', 'LTR', 'T', 'T', 'T', 'T');
			$judul = array('', 'Merah : untuk supplier', '', '', 'DPP', number_format($dpp));
			$border = array('', 'LTR', '', '', '', '');
			$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
			$border = array('T', 'LTR', 'T', 'T', 'T', 'T');
			$judul = array('', 'Putih : untuk keuangan', '', '', 'PPN', number_format($ppn),);
			$border = array('', 'LRB', '', '', '', '', '', '');
			$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
			$judul = array('', '', '', '', 'Materai', number_format($materai));
			$border = array('', '', '', '', '', '', '');
			$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
			$judul = array('', '', '', '', 'Total Net', number_format($totalnet), '');
			$border = array('', '', '', '', 'B', 'B', 'B');
			$style = array('', 'B', '', '', 'B', 'B', '');
			$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);

			$pdf->SetWidths(array(63.3, 63.3, 63.3));
			$border = array('TBLR', 'TBLR', 'TBLR');
			$align  = array('C', 'C', 'C');
			$style = array('', '', '');
			$align  = array('C', 'C', 'C');
			$border = array('', '', '');
			$judul = array('', '', $alamat2 . ', ' . date('d-m-Y'));
			$pdf->ln();
			$pdf->FancyRow2(3, $judul, $fc,  $border, $align, $style, $size, $max);
			$align  = array('C', 'C', 'C');
			$border = array('TBLR', 'TBLR', 'TBLR');
			$judul = array('Diketahui oleh,', 'Diterima Oleh,', 'Dibuat Oleh,');
			$pdf->ln();
			$pdf->FancyRow2(5, $judul, $fc,  $border, $align, $style, $size, $max);
			$judul = array('', '', '');
			$pdf->FancyRow2(20, $judul, $fc,  $border, $align, $style, $size, $max);
			$judul = array('', '', '');
			$pdf->FancyRow2(5, $judul, $fc,  $border, $align, $style, $size, $max);
			$judul = array($profile->pejabat3, $header->vendor_name, $this->session->userdata("nama_lengkap"));
			$pdf->FancyRow2(5, $judul, $fc,  $border, $align, $style, $size, $max);




			$pdf->setTitle($param);
			$pdf->AliasNbPages();
			$pdf->output($param . '.PDF', 'I');
		} else
		{
			header('location:'.base_url());
		}	
	}
	

	public function export(){
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{	
			$unit= $this->session->userdata('unit');	
			$_tgl1 = $this->input->get('startdate');
			$_tgl2 = $this->input->get('enddate');
			$vendor = $this->input->get('vendor');
		 
			$d['vendorid'] = $vendor;

			$d['startdate'] 			= $_tgl1;
			$d['enddate'] 				= $_tgl2;
			$d['list_vendor'] 			= $this->M_global->getListVendor(); 
			$dt1						= $this->M_hutang->totalHutang($unit, $vendor, $d['startdate'], $d['enddate']);
			$d['total_hutang'] 			= $dt1[0]->total_hutang;
			$dt2						= $this->M_hutang->hutangJatuhTempo($unit, $vendor, $d['startdate'], $d['enddate']);
			$d['hutang_jatuh_tempo']	= $dt2[0]->hutang_jatuh_tempo;
			
			$dt3						= $this->M_hutang->rencanaBayar($unit, $vendor, $d['startdate'], $d['enddate']);
			$d['rencana_bayar']			= $dt3[0]->rencana_bayar;
			$dt4						= $this->M_hutang->realisasiPembayaran($unit, $vendor, $d['startdate'], $d['enddate']);
			$d['realisasi_pembayaran']	= $dt4[0]->realisasi_pembayaran;
		 
			
			$d['vendor'] = '';
			if($vendor != '') {
				$dt_vendor = $this->M_global->getListVendorById($vendor);
				$d['vendor'] = $dt_vendor[0]->vendor_name; 
				$vendor = "AND b.vendor_id = '$vendor'";
			}

		  	$q1 = 
				"select *
				from
				   tbl_apoap a left outer join
				   tbl_vendor b
				   on a.vendor_id=b.vendor_id
				where
				   a.koders = '$unit' and
				   a.tglinvoice between '$_tgl1' and '$_tgl2'
				   $vendor 
				order by
				   a.tglinvoice, a.terima_no desc";

			$periode= 'Periode '.date('d-m-Y',strtotime($_tgl1)).' s/d '.date('d-m-Y',strtotime($_tgl2));	   
			$d['keu'] = $this->db->query($q1)->result();
			$d['periode'] = $periode;
			$level=$this->session->userdata('level');		
			$akses= $this->M_global->cek_menu_akses($level, 5201);
			$d['akses']= $akses;		  
		
			$this->load->view('pembelian/v_hutang_exp1',$d);
		} else
		{
			header('location:'.base_url());
		}	
	}

	public function export_detailhutang(){
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{	
			$d['periode'] 	= '';
			$d['vendor'] = ''; 
			$d['keu'] = '';
			
			$unit= $this->session->userdata('unit');
			$d['subtitle']  = $this->input->get('subtitle');
			$d['startdate'] = $this->input->get('startdate');
			$d['enddate'] = $this->input->get('enddate');
			$vendor = $this->input->get('vendor');	
			$d['periode'] 	= 'Periode '.date('d-m-Y',strtotime($d['startdate'])).' s/d '.date('d-m-Y',strtotime($d['enddate']));
			
			if($vendor != ""){
				$vname 			= $this->M_global->getListVendorById($vendor);
				$d['vendor'] 	= $vname[0]->vendor_name;
			}

			if($d['subtitle'] == 'Total Hutang'){	
				$d['keu'] 		= $this->M_hutang->detailTotalHutang($unit, $vendor, $d['startdate'], $d['enddate']);
				$this->load->view('pembelian/v_hutang_exp2',$d);
			} else if($d['subtitle'] == 'Hutang Jatuh Tempo'){	
				$d['keu'] 		= $this->M_hutang->detailHutangJatuhTempo($unit, $vendor, $d['startdate'], $d['enddate']);
				$this->load->view('pembelian/v_hutang_exp2',$d);
			}  else if($d['subtitle'] == 'Rencana Bayar'){	
				$d['keu'] 		= $this->M_hutang->detailRencanaBayar($unit, $vendor, $d['startdate'], $d['enddate']);
				$this->load->view('pembelian/v_hutang_exp2',$d);
			}  else if($d['subtitle'] == 'Realisasi Pembayaran'){	
				$d['keu'] 		= $this->M_hutang->detailRealisasiPembayaran($unit, $vendor, $d['startdate'], $d['enddate']);
				$this->load->view('pembelian/v_hutang_exp2',$d);
			} else
			{
				header('location:'.base_url());
			}	
		} else
		{
			header('location:'.base_url());
		}		
	}
}
/* End of file keuangan_saldo.php */
/* Location: ./application/controllers/keuangan_saldo.php */