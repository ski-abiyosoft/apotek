<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_mutasi_gudang_log extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->model('M_mutasi_gudang_log');
		$this->load->model('M_global');
		$this->load->helper('simkeu_rpt');
		$this->load->helper('simkeu_nota');
		$this->session->set_userdata('menuapp', '4000');
		$this->session->set_userdata('submenuapp', '4303');
	}

	public function index(){
		$cek = $this->session->userdata('level');		
		if(!empty($cek)){					
			$this->load->view('inventory/v_inventory_mutasi_gudang_log');
		} else {
			header('location:'.base_url());
		}			
	}

    public function getinfobarang(){
		$kode = $this->input->get('kode');		
		$data = $this->M_global->_data_barang_log( $kode );
		echo json_encode($data);
	}
	
	public function ajax_list( $param ){
		$dat   = explode("~",$param);
		$level	= $this->session->userdata("user_level");
		if($dat[0]==1){
			$bulan = date('m');
			$tahun = date('Y');
			$list = $this->M_mutasi_gudang_log->get_datatables( 1, $bulan, $tahun );
		} else {
			$bulan  = date('Y-m-d',strtotime($dat[1]));
		    $tahun  = date('Y-m-d',strtotime($dat[2]));
		    $list = $this->M_mutasi_gudang_log->get_datatables( 2, $bulan, $tahun );	
		}
		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $item) {
			$depodari	= $this->db->query("SELECT * FROM tbl_depo WHERE depocode = '$item->dari'")->row();
			$depoke		= $this->db->query("SELECT * FROM tbl_depo WHERE depocode = '$item->ke'")->row();
			$no++;
			$row = array();
			$row[] = $item->koders;
			$row[] = $item->username;
			$row[] = $item->moveno;
			$row[] = $item->mohonno;
			$row[] = date('d-m-Y',strtotime($item->movedate));			
			$row[] = $depodari->keterangan;
			$row[] = $depoke->keterangan;
			$row[] = $item->keterangan;
			
			if($level == 1){
				$row[] = 
			     '<a class="btn btn-sm btn-warning" target="_blank" href="/inventory_mutasi_gudang_log/cetak/'. $item->moveno .'" title="Cetak"><i class="glyphicon glyphicon-print"></i></a>
				 <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$item->id."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
			} else {
				$row[] = 
			     '<a class="btn btn-sm btn-warning" target="_blank" href="/inventory_mutasi_gudang_log/cetak/'. $item->moveno .'" title="Cetak"><i class="glyphicon glyphicon-print"></i></a>';
			}
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_mutasi_gudang_log->count_all($dat[0], $bulan, $tahun),
						"recordsFiltered" => $this->M_mutasi_gudang_log->count_filtered($dat[0], $bulan, $tahun),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function edit( $id ){
		$cek = $this->session->userdata('level');		
		$uid = $this->session->userdata('unit');		
		if(!empty($cek)){				  
		  $d['pic'] = $this->session->userdata('username');
		  $header   = $this->db->query("select * from tbl_apohmovelog where id = '$id'");
		  $nomohon  = $header->row()->moveno;
		  $detil    = $this->db->query("select * from tbl_apodmovelog where moveno = '$nomohon'");
		  $d['jumdata']= $detil->num_rows();
		  $d['header'] = $header->row();
		  $d['detil']  = $detil->result();
		  $this->load->view('inventory/v_inventory_mutasi_gudang_log_edit', $d);				
		} else {
			header('location:'.base_url());
		}
	}

	public function save($param){
		$cek	= $this->session->userdata("level");
		$unit	= $this->session->userdata("unit");
		$userid	= $this->session->userdata("username");

		if(!empty($cek)){
			// header
			$nobukti		= $this->input->post("hidenobukti");
			$gudang_asal 	= $this->input->post("gudang_asal");
			$gudang_tujuan	= $this->input->post("gudang_tujuan");
			$tanggal		= $this->input->post("tanggal");
			$keterangan		= $this->input->post("ket");
			$nomohon = $this->input->post('nomohon');
			
			// detail
			$kode_arr	    = array();
			$kode  			= $this->input->post('kode');
			$qty   			= str_replace(".00", "", $this->input->post('qty'));
		    $sat   			= $this->input->post('sat');
			$harga 			= str_replace(",", "", $this->input->post('harga'));
			$total   		= str_replace(",", "", $this->input->post('total'));
			$expire   		= $this->input->post('expire');
			$note   		= $this->input->post('note');

			foreach($kode as $kv){
                $kode_arr[] = $kv;
			}

			if($param == 1){
				$this->db->query("INSERT INTO tbl_apohmovelog (koders,moveno,mohonno,movedate,dari,ke,keterangan,proses,diterima,username) 
				VALUES ('$unit','$nobukti','$nomohon','$tanggal','$gudang_asal','$gudang_tujuan','$keterangan','','','$userid')");

				foreach($kode_arr as $kakey => $kaval){
					$loop_kode		= $kode[$kakey];
					$loop_qty		= $qty[$kakey];
					$loop_sat		= $sat[$kakey];
					$loop_harga		= $harga[$kakey];
					$loop_total		= $total[$kakey];
					$loop_exp		= $expire[$kakey];
					$loop_note		= $expire[$kakey];

					$this->db->query("INSERT INTO tbl_apodmovelog (koders,moveno,mohonno,kodebarang,satuan,qtymove,harga,totalharga,keterangan,exp_date) 
					VALUES ('$unit','$nobukti','$nomohon','$loop_kode','$loop_sat','$loop_qty','$loop_harga','$loop_total','$loop_note','$loop_exp')");

						// $this->db->insert("tbl_apodmovelog", $data_detail);

					$check_stock	= $this->db->query("SELECT * FROM tbl_apostocklog 
					WHERE koders = '$unit' AND kodebarang = '$loop_kode' AND gudang = '$gudang_tujuan'");

					if($check_stock->num_rows() == 0){
						$this->db->query("INSERT INTO tbl_apostocklog (koders,kodebarang,gudang,terima,saldoakhir,periodedate) 
					VALUES ('$unit','$loop_kode','$gudang_tujuan','".$loop_qty."','".$loop_qty."','$tanggal')");
					} else {
						$this->db->query("UPDATE tbl_apostocklog SET terima = terima + ".$loop_qty.", saldoakhir = saldoakhir + ".$loop_qty.", periodedate = '$tanggal' WHERE kodebarang = '$loop_kode' and koders = '$unit' and gudang = '$gudang_tujuan'");
					}

					$this->db->query("UPDATE tbl_apostocklog SET keluar = keluar + ".$loop_qty.", saldoakhir = saldoakhir - ".$loop_qty.", periodedate = '$tanggal' WHERE kodebarang = '$loop_kode' and koders = '$unit' and gudang = '$gudang_asal'"); 
					
				}

				urut_transaksi('APO_MOVE', 19);
			} else {
				// EDIT
			}
		} else {
			redirect("/home");
		}

		echo $nobukti;
	}
	
	public function ajax_delete($id){
		$hmutasi = $this->db->query("select * from tbl_apohmovelog where id = '$id'")->row();
		$nobukti = $hmutasi->moveno;
		$gudang_asal = $hmutasi->dari;
		$gudang_tujuan = $hmutasi->ke;
		
		$cabang   = $this->session->userdata('unit');
		$datamutasi = $this->db->get_where('tbl_apodmovelog', array('moveno' => $nobukti))->result();
	    foreach($datamutasi as $row){	
		 $_qty = $row->qtymove;
		 $_kode= $row->kodebarang;
		 
		 $this->db->query("update tbl_apostocklog set terima=terima- $_qty, saldoakhir= saldoakhir- $_qty where kodebarang = '$_kode'
		  and koders = '$cabang' and gudang = '$gudang_tujuan'"
	     );
		
		 $this->db->query("update tbl_apostocklog set keluar=keluar- $_qty, saldoakhir= saldoakhir+ $_qty where kodebarang = '$_kode'
		  and koders = '$cabang' and gudang = '$gudang_asal'"
	     );
	    }
				   
		$this->db->query("delete from tbl_apohmovelog where id = '$id'");
		$this->db->query("delete from tbl_apodmovelog where moveno = '$nobukti'");
		echo json_encode(array("status" => TRUE));
	}
	
	public function entri(){
		$cek 	= $this->session->userdata('level');		
		$unit 	= $this->session->userdata('unit');
		$level	= $this->session->userdata("user_level");

		if(!empty($cek)){
			$d['level'] = $level;	  
		  	$d['pic'] = $this->session->userdata('username');
		  	$d['nomor'] = temp_urut_transaksi('APO_MOVE', $unit, 19);
		  $this->load->view('inventory/v_inventory_mutasi_gudang_log_add', $d);				
		} else {
			header('location:'.base_url());
		}
	}
	
	public function getpermohonan($nomor){   	    
        $data = $this->db->query("SELECT a.*, b.namabarang FROM tbl_apodmohonlog AS a 
		LEFT JOIN tbl_logbarang AS b ON b.kodebarang = a.kodebarng 
		WHERE a.mohonno = '$nomor'")->result();
		echo json_encode($data);
	}

	public function getgudang($nomor){
		$data	= $this->db->query("SELECT a.dari, a.keterangan AS ket, b.keterangan
		FROM tbl_apohmohonlog AS a 
		LEFT JOIN tbl_depo AS b ON b.depocode = a.dari
		WHERE mohonno = '$nomor'")->row();

		$data2	= $this->db->query("SELECT a.ke, b.keterangan
		FROM tbl_apohmohonlog AS a 
		LEFT JOIN tbl_depo AS b ON b.depocode = a.ke
		WHERE mohonno = '$nomor'")->row();

		$datac	= $this->db->query("SELECT * FROM tbl_apodmohonlog WHERE mohonno = '$nomor'")->num_rows();

		echo json_encode(array(
			"keterangan"=> $data->ket,
			"dariid"	=> $data->dari,
			"dari" 		=> $data->keterangan,
			"keid"		=> $data2->ke,
			"ke"		=> $data2->keterangan,
			"total"		=> $datac
		));
	}
	
	public function checkstock(){
		$unit 	= $this->session->userdata("unit");
		$gudang	= $this->input->get("gudang");
		$kode	= $this->input->get("kode");

		$queryq = $this->db->query("SELECT saldoakhir FROM tbl_apostocklog WHERE kodebarang = '$kode' AND koders = '$unit' AND gudang = '$gudang'");

		if($queryq->num_rows() != 0){
			$getq = $queryq->row();
			echo json_encode(array("stock" => $getq->saldoakhir));
		} else {
			echo json_encode(array("status" => 0));
		}
	}

	public function cetak($param){
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');
        $user= $this->session->userdata('username');

		if(!empty($cek)){				  		     
		    $unit= $this->session->userdata('unit');

			$profile = data_master('tbl_namers', array('koders' => $unit));
		    $nama_usaha=$profile->namars;
			$alamat1  = $profile->alamat;
			$alamat2  = $profile->kota;
		  
		    $queryh = "select * from tbl_apohmove
			where moveno = '$param'";
			
			$queryd = "select tbl_apodmove.*, tbl_barang.namabarang from tbl_apodmove inner join 
			tbl_barang on tbl_apodmove.kodebarang=tbl_barang.kodebarang
			where tbl_apodmove.moveno = '$param'";
			
		    $detil  = $this->db->query($queryd)->result();
			$header = $this->db->query($queryh)->row();

			$header	= $this->db->query("SELECT * FROM tbl_apohmovelog WHERE moveno = '$param'")->row();
			$detil	= $this->db->query("SELECT a.*, b.* FROM tbl_apodmovelog AS a
			LEFT JOIN tbl_logbarang AS b ON b.kodebarang = a.kodebarang
			WHERE moveno = '$param'")->result();
			$detilpp	= $this->db->query("SELECT a.koders FROM tbl_apodmovelog AS a
			LEFT JOIN tbl_logbarang AS b ON b.kodebarang = a.kodebarang
			WHERE moveno = '$param' limit 1")->result();

			$dari 	= $this->db->query("SELECT * FROM tbl_depo WHERE depocode = '$header->dari'")->row();
			$ke 	= $this->db->query("SELECT * FROM tbl_depo WHERE depocode = '$header->ke'")->row();
						
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
			$judul=array('MUTASI BARANG ANTAR DEPO/GUDANG');
			$pdf->FancyRow2(10,$judul, $fc,  $border, $align, $style, $size, $max);
			$size  = array('10');
			$align = array('L');
			$border = array('');

			$pdf->ln(5);
			$pdf->setfont('Arial','B',10);
			$pdf->SetWidths(array(30,5,70,20,5,60));
			// $border = array('T','T','T','T','T','T');
			$border = array("","","","","",""); 	
			$fc     = array('0','0','0','0','0','0');
			$pdf->SetFillColor(230,230,230);			
			$pdf->setfont('Arial','',9);
			
			$pdf->FancyRow(array('Dari Gudang/Depo',':',$dari->keterangan,'Transfer No.',':',$header->moveno), $fc, $border);
			$border = array('','','','','','');
			$pdf->FancyRow(array('Ke Gudang/Depo',':',$ke->keterangan,'Tanggal',':',date('d-m-Y', strtotime($header->movedate))), $fc, $border);
			$pdf->FancyRow(array('Catatan',':',$header->keterangan,'','',''), $fc, $border);
			
			$pdf->ln(5);
			if($unit == false){
				$pdf->SetWidths(array(10,15,35,15,15,20,20,20,20,20));
				$border = array('LTB','TB','TB','TB','TB','TB','TB','TB','TB','TBR');
				$align  = array('C','L','L','L','R','R','R','R','R','L');
				$pdf->setfont('Arial','B',9);
				$pdf->SetAligns(array('L','C','R'));
				$fc = array('0','0','0','0','0','0','0','0','0','0');
				$judul=array('No.','Kode','Nama Barang','Satuan','Qty','HNA','Total HNA','HPP','Total HPP','Keterangan');
				$pdf->FancyRow2(8,$judul,$fc, $border,$align);
				$border = array('','','');
				$pdf->setfont('Arial','',9);
				$tot = 0;
				$subtot = 0;
				$tdisc  = 0;
				$border = array('L','','','','','','','','','R');
				
				$align  = array('C','C','C','C','C','C','C','C','C','C');
				$fc = array('0','0','0','0','0','0','0','0','0','0');
				$max= array(8,8,8,8,8,8,8,8,8,8,8);
				$pdf->SetFillColor(0,0,139);
				$pdf->settextcolor(0);
				$no =1 ;
				$totitem = 0;
				$tot = 0;
				foreach($detil as $db)
				{
				//   $tot += ($db->hpp*$db->qtymove);
					$pdf->FancyRow2(5,array(
					$no,
					$db->kodebarang, 
					$db->namabarang,
					$db->satuan,
					str_replace(".00", "", $db->qtymove),
					
					number_format($db->harga,0,'.',','),
					number_format($db->totalharga,0,'.',','),
					number_format($db->hpp,0,'.',','),
					number_format($db->hpp*$db->qtymove,0,'.',','),
					$db->keterangan,
					
					),$fc, $border, $align);
					
					$no++;
					$tot += $db->totalharga;
				}
				
				$pdf->SetWidths(array(150,20, 20));
				$border = array('LTB','TBR', 'TBR');
				$align  = array('R','R', '');
				$style = array('B','B', 'B');
				$size = array('','' , '');
				$max = array('','', '');
				$pdf->FancyRow2(5,array('Total:   ',
					number_format($tot,0,',','.'),
					''
					),$fc,  $border, $align, $style, $size, $max);
			} else {
				$pdf->SetWidths(array(10,25,45,35,35,40));
				$border = array('LTB','TB','TB','TB','TB','TBR');
				$align  = array('C','C','C','C','C','C');
				$pdf->setfont('Arial','B',9);
				$pdf->SetAligns(array('L','C','R'));
				$fc = array('0','0','0','0','0','0');
				$judul=array('No.','Kode','Nama Barang','Satuan','Qty','Keterangan');
				$pdf->FancyRow2(8,$judul,$fc, $border,$align);
				$border = array('','','');
				$pdf->setfont('Arial','',9);
				$tot = 0;
				$subtot = 0;
				$tdisc  = 0;
				$border = array('LB','B','B','B','B','BR');
				
				$align  = array('C','C','C','C','C','C');
				$fc = array('0','0','0','0','0','0');
				$max= array(8,8,8,8,8,8);
				$pdf->SetFillColor(0,0,139);
				$pdf->settextcolor(0);
				$no =1 ;
				$totitem = 0;
				$tot = 0;
				foreach($detil as $db)
				{
				//   $tot += ($db->hpp*$db->qtymove);
					$pdf->FancyRow2(5,array(
					$no,
					$db->kodebarang, 
					$db->namabarang,
					$db->satuan,
					str_replace(".00", "", $db->qtymove),
					$db->keterangan,
					),$fc, $border, $align);
					$no++;
				}
			}
			

			// $pdf->ln();
			// $pdf->SetWidths(array(47.5,50,47.5,47.5));
			// $border = array('','','','','', '', 'TBR', 'TBR');
			// $align  = array('C','C','C','C','C','C', 'R', 'R');
			// $pdf->setfont('Arial','',8);			
			// $fc = array('0','0','0','0','0', '0' , '0','0');
			// // $style = array('','','','','', '', '','');
			// $style = array('B','B');
			// $size  = array('9','9','9','9');
			// $judul=array('',',',',','Total:                   '.number_format($tot));
			// $pdf->FancyRow2(4,$judul, $fc,  $border, $align, $style, $size, $max);
			// $judul=array('','','','');
			// $pdf->FancyRow2(4,$judul, $fc,  $border, $align, $style, $size, $max);
			// $pdf->ln(15);
			

			$pdf->ln(5);
			$pdf->SetWidths(array(45,5,40,5,40,5,50));
			$border = array('','','','','','','');
			$align  = array('C','C','C','C','C','C','C');
			$pdf->setfont('Arial','',8);			
			$fc = array('0','0','0','0','0','0','0');
			$style = array('','','','','','','');
			$size  = array('9','9','9','9','9','9','9');
			// $alamat2.', '.date('d-m-Y',strtotime($header->movedate));
			// $pdf->FancyRow2("","","","","","",$alamat2.', '.date('d-m-Y',strtotime($header->movedate)));
			$judul=array('','','','','','',$alamat2.' '.date('d-m-Y',strtotime($header->movedate)));
			$pdf->FancyRow2(4,$judul, $fc,  $border, $align, $style, $size, $max);
			$pdf->ln(5);
			$judul=array('','','','','','','');
			$pdf->FancyRow2(4,$judul, $fc,  $border, $align, $style, $size, $max);
			$judul=array('Pemohon','','Keuangan','','Penanggung Jawab','','Pembukuan');
			$pdf->FancyRow2(4,$judul, $fc,  $border, $align, $style, $size, $max);
			$judul=array('','','','','','','');
			$pdf->FancyRow2(4,$judul, $fc,  $border, $align, $style, $size, $max);
			$pdf->ln(15);
			
			$judul=array($user,'','','','','','');
			$style = array('B','','','','','','');
			$border = array('B','','B','','B','','B');
			$pdf->FancyRow2(4,$judul, $fc,  $border, $align, $style, $size, $max);
			$judul=array($user,'','','','','','');
			$border = array('','','','','','','');
			$size  = array('7','7','7','7','7','7','7');
			$style = array('I','','','','','','I');
			$judul=array('HOSPITAL MANAGEMENT SYSTEM','','','','','','Print Date : '.date('d-m-Y'));
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

	public function hapus_item(){
		$unit 		= $this->session->userdata("unit");
		$cek		= $this->session->userdata("level");

		$kodebarang	= $this->input->post("kodebarang");
		$nomohon	= $this->input->post("nomohon");

		if(!empty($cek)){
			$check_item	= $this->db->query("SELECT * FROM tbl_apodmohonlog WHERE mohonno = '$nomohon' AND kodebarng = '$kodebarang'");
			if($check_item){
				$this->db->query("DELETE FROM tbl_apodmohonlog WHERE mohonno = '$nomohon' AND kodebarng = '$kodebarang'");
				echo json_encode(array("status" => 1));
			} else {
				echo json_encode(array("status" => 0));
			}
		} else {
			echo json_encode(array("status" => 0));
		}
	}

}