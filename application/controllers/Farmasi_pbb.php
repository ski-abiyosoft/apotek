	<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Farmasi_pbb extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('M_farmasi_pbb');
		$this->load->model('M_global');
		$this->load->helper('simkeu_rpt');
		$this->load->helper('simkeu_nota');
		$this->load->helper('app_global');
		$this->session->set_userdata('menuapp', '3000');
		$this->session->set_userdata('submenuapp', '3302');
	}

	public function index(){
		$cek 				= $this->session->userdata('level');
		$bulan          	= $this->M_global->_periodebulan();
		$nbulan   	  		= $this->M_global->_namabulan($bulan);
		$data["periode"] 	= "Periode ". $nbulan ." ". date("Y");
		$data["list"]		= $this->M_farmasi_pbb->list()->result();
		if(!empty($cek)){
			$this->load->view('farmasi/v_farmasi_pbb', $data);
		} else{
			header('location:'.base_url());
		}			
	}

	// public function ajax_list( $param ){
		
	// 	$dat   = explode("~",$param);
	// 	if($dat[0]==1){
	// 		$bulan = date('m');
	// 		$tahun = date('Y');
	// 		$list = $this->M_farmasi_pbb->get_datatables( 1, $bulan, $tahun );
	// 	} else {
	// 		$bulan  = date('Y-m-d',strtotime($dat[1]));
	// 	    $tahun  = date('Y-m-d',strtotime($dat[2]));
	// 	    $list = $this->M_farmasi_pbb->get_datatables( 2, $bulan, $tahun );	
	// 	}
		
	// 	$data = array();
	// 	$no = $_POST['start'];
	// 	foreach ($list as $item) {
	// 		$dari = $this->db->query("SELECT * FROM tbl_depo WHERE depocode = '$item->dari'")->row();
	// 		$ke = $this->db->query("SELECT * FROM tbl_depo WHERE depocode = '$item->ke'")->row();
	// 		$no++;
	// 		$row = array();
	// 		$row[] = $item->koders;
	// 		$row[] = $item->username;
	// 		$row[] = $item->mohonno;
	// 		$row[] = date('d-m-Y',strtotime($item->tglmohon));			
	// 		$row[] = $dari->keterangan;
	// 		$row[] = $ke->keterangan;
	// 		$row[] = $item->keterangan;
	// 		$row[] = 
	// 		     '<a class="btn btn-sm btn-primary" href="'.base_url("farmasi_pbb/edit/".$item->id."").'" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a>
	// 			  <a target="_blank" class="btn btn-sm btn-warning" href="'.base_url("farmasi_pbb/cetak/?id=".$item->mohonno."").'" title="Cetak" ><i class="glyphicon glyphicon-print"></i> </a>
	// 			  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$item->id."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
		
	// 		$data[] = $row;
	// 	}

	// 	$output = array(
	// 					"draw" => $_POST['draw'],
	// 					"recordsTotal" => $this->M_farmasi_pbb->count_all($dat[0], $bulan, $tahun),
	// 					"recordsFiltered" => $this->M_farmasi_pbb->count_filtered($dat[0], $bulan, $tahun),
	// 					"data" => $data,
	// 			);
	// 	//output to json format
	// 	echo json_encode($output);
	// }

	public function ajax_edit($id){
		$data = $this->M_farmasi_pbb->get_by_id($id);		
		echo json_encode($data);
	}

	public function save($param){
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');
		$uid = $this->session->userdata("username");

		if(!empty($cek)){
			$userid        = $this->session->userdata('username');
			$nomorbukti    = $this->input->post('hidenomorbukti');
			$gudang_asal   = $this->input->post('gudang_asal');
			$gudang_tujuan = $this->input->post('gudang_tujuan');
			$tanggal       = $this->input->post('tanggal');
			$ket           = $this->input->post('ket');
			$kode          = $this->input->post('kode');
			$qty           = $this->input->post('qty');
			$sat           = $this->input->post('sat');
			$harga         = str_replace(",", "", $this->input->post('harga'));
			$total         = str_replace(",", "", $this->input->post('total'));
			$note          = $this->input->post('note');
			$jumdata       = count($kode);
			
			$data_header = array (
				'koders' => $unit,
				'mohonno' => $nomorbukti,
				'tglmohon' => $tanggal,
				'dari' => $gudang_asal,
				'ke' => $gudang_tujuan,
				'keterangan' => $this->input->post('ket'),	
				'username' => $uid
			);
			
			if($param==1){	
				$this->db->query("INSERT INTO tbl_apohmohon (koders,mohonno,tglmohon,dari,ke,keterangan,username) 
				VALUES ('$unit','$nomorbukti','$tanggal','$gudang_asal','$gudang_tujuan','$ket','$uid')");
				urut_transaksi("NO_MOHON", 16);
			} else {
				$id_mutasi = $this->input->post('nomorbukti');	
				$this->db->update('tbl_apohmohon',$data_header, array('mohonno' => $id_mutasi));	
				$this->db->query("delete from tbl_apodmohon where mohonno = '$id_mutasi'");
			}
					
			$nourut = 1;	
			for($i=0;$i<=$jumdata-1;$i++){
				$_kode   = $kode[$i];
				$_harga  =  str_replace(',','',$harga[$i]);
				$_total  =  str_replace(',','',$total[$i]);
				
				$datad = array(
					'koders' => $unit,
					'mohonno' => $nomorbukti,
					'kodebarang' => $_kode,
					'satuan' => $sat[$i],
					'qtymohon' => $qty[$i],
					'harga' => $_harga,
					'totalharga' => $_total,
					'keterangan' => $note[$i],
				);
				
				if($_kode!=""){
					if($param==1){	
						$this->db->insert('tbl_apodmohon', $datad);
					} else {
						$this->db->insert('tbl_apodmohon', $datad);
					}
				}
			}
			echo $nomorbukti;
		} else {
			header('location:'.base_url());
		}
	}

	public function ajax_delete($id){
		$nobukti = $this->db->query("SELECT * FROM tbl_apohmohon WHERE id = '$id'")->row()->mohonno;
		$this->db->query("delete from tbl_apohmohon where id = '$id'");
		$this->db->query("delete from tbl_apodmohon where mohonno = '$nobukti'");
		echo json_encode(array("status" => TRUE));
	}

	public function entri(){
		$cek = $this->session->userdata('level');		
		$uid = $this->session->userdata('unit');		
		if(!empty($cek)){				  
			$d['pic'] = $this->session->userdata('username');
			$this->load->view('farmasi/v_farmasi_pbb_add', $d);				
		} else {
			header('location:'.base_url());
		}
	}

	public function edit( $id ){
		$cek = $this->session->userdata('level');		
		$uid = $this->session->userdata('unit');
		if(!empty($cek)){				  
			$d['pic'] = $this->session->userdata('username');
			$header   = $this->db->query("select * from tbl_apohmohon where id = '$id'");
			$nomohon  = $header->row()->mohonno;
			$detil    = $this->db->query("select * from tbl_apodmohon where mohonno = '$nomohon'");
			$d['jumdata']= $detil->num_rows();
			$d['header'] = $header->row();
			$d['detil']  = $detil->result();
			$this->load->view('farmasi/v_farmasi_pbb_edit', $d);				
		} else {
			header('location:'.base_url());
		}
	}

	public function checkstock(){
		$unit 	= $this->session->userdata("unit");
		$gudang	= $this->input->get("gudang");
		$kode	= $this->input->get("kode");

		$queryq = $this->db->query("SELECT saldoakhir FROM tbl_barangstock WHERE kodebarang = '$kode' AND koders = '$unit' AND gudang = '$gudang'");

		if($queryq->num_rows() != 0){
			$getq = $queryq->row();
			echo json_encode(array("stock" => $getq->saldoakhir));
		} else {
			echo json_encode(array("status" => 0));
		}
	}

	public function cetak(){
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');
		$user= $this->session->userdata('username');		
		if(!empty($cek)){				  		 
			
			$unit= $this->session->userdata('unit');	 
			$profile = data_master('tbl_namers', array('koders' => $unit));
			$nama_usaha=$profile->namars;
			$alamat1  = $profile->alamat;
			$alamat2  = $profile->kota;
			
			$param = $this->input->get('id');
			
			$queryh = "select * from tbl_apohmohon
			where mohonno = '$param'";
			
			$queryd = "select tbl_apodmohon.*, tbl_barang.namabarang from tbl_apodmohon inner join 
			tbl_barang on tbl_apodmohon.kodebarang=tbl_barang.kodebarang
			where tbl_apodmohon.mohonno = '$param'";
			
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
			$judul=array('PERMOHONAN PENGAMBILAN BARANG/OBAT');
			$pdf->FancyRow2(10,$judul, $fc,  $border, $align, $style, $size, $max);
			$size  = array('10');
			$align = array('L');
			$border = array('');	
			
			$pdf->ln(1);
			$pdf->setfont('Arial','B',10);
			$pdf->SetWidths(array(30,5,70,20,5,60));
			$border = array('T','T','T','T','T','T');
			$fc     = array('0','0','0','0','0','0');
			$pdf->SetFillColor(230,230,230);			
			$pdf->setfont('Arial','',9);
			
			$nama_dari = data_master('tbl_depo', array('depocode' => $header->dari))->keterangan;
			$nama_ke   = data_master('tbl_depo', array('depocode' => $header->ke))->keterangan;
			
			$pdf->FancyRow(array('Dari Gudang/Depo',':',$nama_dari,'Mohon No.',':',$header->mohonno), $fc, $border);
			$border = array('','','','','','');
			$pdf->FancyRow(array('Ke Gudang/Depo',':',$nama_ke,'Tanggal',':',date('d-m-Y', strtotime($header->tglmohon))), $fc, $border);
			$pdf->FancyRow(array('Catatan',':',$header->keterangan,'','',''), $fc, $border);
			
			$pdf->ln(2);
			$pdf->SetWidths(array(10,25,50,20,20,20,25,20));
			$border = array('LTB','TB','TB','TB','TB','TB','TB' ,'TBR');
			$align  = array('C','L','L','R','L','R','L', 'L');
			$pdf->setfont('Arial','B',9);
			$pdf->SetAligns(array('L','C','R'));
			$fc = array('0','0','0','0','0','0','0', '0');
			$judul=array('No.','Kode','Nama Barang','Qty','Unit','HPP','Catatan', 'Total');
			$pdf->FancyRow2(8,$judul,$fc, $border,$align);
			$border = array('','','', '','', '', '');
			$pdf->setfont('Arial','',9);
			$tot = 0;
			$subtot = 0;
			$tdisc  = 0;
			$border = array('L','','','','','','','R');
			
			$align  = array('C','L','L','R','L','R','L', 'L');
			$fc = array('0','0','0','0','0','0','0', '0');
			$max= array(8,8,8,8,8,8,8,8);
			$pdf->SetFillColor(0,0,139);
			$pdf->settextcolor(0);
			$no =1 ;
			$totitem = 0;
			$tot = 0;
			$tot1 = 0;
			$totalharga =0;
			foreach($detil as $db){
				$tot += $db->totalharga;
					$tot1 += $tot;
				$pdf->FancyRow2(5,array(
				$no,
				$db->kodebarang, 
				$db->namabarang,
				$db->qtymohon,
				$db->satuan,
				number_format($db->harga,2,',','.'),
				$db->keterangan,
				number_format($db->totalharga),
			),$fc, $border, $align); 
				$no++;
			}
			
			$pdf->SetWidths(array(165,25));
			$border = array('LB','BR');
			$align  = array('R','R');
			$style = array('B','B');
			$size = array('','');
			$max = array('','');
			$pdf->FancyRow2(5,array('',''),$fc,  $border, $align, $style, $size, $max);
			
			$pdf->ln();
			$pdf->SetWidths(array(47.5,50,47.5,47.5));
			$border = array('','','','','', '', '', '');
			$align  = array('C','C','C','C','C','C', 'R', 'R');
			$pdf->setfont('Arial','',8);			
			$fc = array('0','0','0','0','0', '0' , '0','0');
			$style = array('','','','','', '', '','');
			$size  = array('9','9','9','9');
			$judul=array('',',',',','Total:                   '.number_format($tot));
			$pdf->FancyRow2(4,$judul, $fc,  $border, $align, $style, $size, $max);
			$judul=array('','','','');
			$pdf->FancyRow2(4,$judul, $fc,  $border, $align, $style, $size, $max);
			$pdf->ln(15);

			
			$pdf->ln();
			$pdf->SetWidths(array(47.5,50,47.5,47.5));
			$border = array('','','','','');
			$align  = array('C','C','C','C','C');
			$pdf->setfont('Arial','',8);			
			$fc = array('0','0','0','0','0');
			$style = array('','','','','');
			$size  = array('9','9','9','9');
			$judul=array('','Disetujui Oleh,',',','Dibuat Oleh,');
			$pdf->FancyRow2(4,$judul, $fc,  $border, $align, $style, $size, $max);
			$judul=array('','','','');
			$pdf->FancyRow2(4,$judul, $fc,  $border, $align, $style, $size, $max);
			$pdf->ln(15);
			
			$style = array('B','B','B','B');
			$border = array('','B','','B');
			$judul=array('','','',$user);			
			$pdf->FancyRow2(4,$judul, $fc,  $border, $align, $style, $size, $max);
			$judul=array('','Date : '.date('d-m-Y',strtotime($header->tglmohon)),'','Date : '.date('d-m-Y',strtotime($header->tglmohon)));			
			$border = array('','','','');			
			$pdf->FancyRow2(5,$judul, $fc,  $border, $align, $style, $size, $max);
			
			
			$pdf->setTitle($param);
			$pdf->AliasNbPages();
			$pdf->output($param.'.PDF','I');			
		} else {
			header('location:'.base_url());
		}
	}

	public function get_last_number($jenis_urut){
		$unit = $this->session->userdata("unit");
		echo json_encode(array(
			"lastno" => temp_urut_transaksi($jenis_urut, $unit, 16)
		));
	}

	public function get_recent_number($jenis_urut){
		$unit = $this->session->userdata("unit");
		echo json_encode(array(
			"notr" => recent_urut_transaksi($jenis_urut, $unit, 16)
		));
	}

	public function filter($param){
		$cek                = $this->session->userdata('level');
		$unit               = $this->session->userdata('unit');

		$period_length      = explode("~", $param);
		$dari_Periode       = date("Y-m-d", strtotime($period_length[0]));
		$ke_periode         = date("Y-m-d", strtotime($period_length[1]));
		$bulan_dari_priode  = date("n", strtotime($period_length[0]));
		$bulan              = $this->M_global->_periodebulan();

		$tahun1             =  explode("-", $period_length[0]);
		$tahun2             =  explode("-", $period_length[1]);

		$data["list"]		= $this->M_farmasi_pbb->list_by_date($dari_Periode, $ke_periode)->result();

		$dari_bulan   	    = $this->M_global->_namabulan($bulan_dari_priode);

		$ke_bulan           = $this->M_global->_namabulan($bulan);
		$data["periode"]    = 'Periode '.$dari_bulan.'-'.$tahun1[0].' s/d '.$ke_bulan.'-'.$tahun2[0];

		if(!empty($cek)){
			$this->load->view("farmasi/v_farmasi_pbb", $data);
		} else {
			header("location:".base_url());
		}
	}
			
	}