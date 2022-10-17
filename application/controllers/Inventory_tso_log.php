<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_tso_log extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_stockopname_log');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '4000');
		$this->session->set_userdata('submenuapp', '4304');
	}

	public function index()
	{
		$cek	= $this->session->userdata("level");
		$unit	= $this->session->userdata("unit");
		$gudang	= $this->input->get("gudang");

		if (!empty($cek)) {
			$data["list"] 			= $this->db->query("SELECT a.type, a.id, a.koders, b.keterangan AS gudang, a.tglso, a.tglso, a.kodeobat, c.namabarang, a.saldo, a.hasilso, a.sesuai, a.username, a.yangubah, a.approve
			FROM tbl_aposesuailog AS a
			LEFT JOIN tbl_depo AS b ON b.depocode = a.gudang
			LEFT JOIN tbl_logbarang AS c ON c.kodebarang = a.kodeobat
			WHERE a.koders = '$unit' 
			AND a.gudang = '$gudang'
			AND a.saldo !=0
			ORDER BY a.id DESC")->result();

			$this->load->view('inventory/v_inventory_stockopname_log', $data);
		} else {
			header("location:/");
		}
	}

	public function validate()
	{
		$username = $this->session->userdata('username');
		$sql = $this->db->get_where('userlogin', ['uidlogin' => $username])->row_array();
		$password = md5($this->input->post('password'));
		if ($sql['pwdlogin'] == $password) {
			redirect('Inventory_tso_log/entri');
		} else {
			redirect('Inventory_tso_log');
		}
	}

	public function entri()
	{
		$cek = $this->session->userdata('level');
		$uid = $this->session->userdata('unit');
		if (!empty($cek)) {
			$d['pic'] = $this->session->userdata('username');
			$this->load->view('inventory/v_inventory_so_log_add', $d);
		} else {
			header('location:' . base_url());
		}
	}

	public function save($param)
	{
		$cek = $this->session->userdata('level');

		if (!empty($cek)) {

			$userid   	= $this->session->userdata('username');
			$pic      	= $this->input->post('pic');
			$typestock  = $this->input->post('typestock');
			$tanggal  	= $this->input->post('tanggal');
			$kode  		= $this->input->post('kode');
			$qty   		= $this->input->post('qty');
			$sat   		= $this->input->post('sat');
			$gudang  	= $this->input->post('gudang');
			$cabang 	= $this->session->userdata('unit');
			$yangubah  = $this->input->post('yangubah');
			$yangsetuju  = $this->input->get('setuju');
			$saldoakhir = $this->input->post('saldoakhir');
			$plusminus = $this->input->post('plusminus');

			$jumdata  	= count($kode);
			$nourut = 1;

			for ($i = 0; $i <= $jumdata - 1; $i++) {
				$_kode   	= $kode[$i];
				$_qty   	= $qty[$i];
				$_saldoakhir   	= $saldoakhir[$i];
				$_plusminus   	= $plusminus[$i];
				$_yangubah   	= $yangubah[$i];
				$barang 	= $this->db->get_where('tbl_logbarang', array('kodebarang' => $_kode))->row();
				$saldo 		= $this->db->query("SELECT saldoakhir FROM tbl_apostocklog WHERE kodebarang = '$_kode' AND koders = '$cabang'  AND gudang = '$gudang'")->row();

				if ($saldo) {
					$_saldo = $saldo->saldoakhir;
				} else {
					$_saldo = 0;
					$this->db->query("INSERT INTO tbl_apostocklog (koders, kodebarang, gudang, periodedate, sesuai, menyetujui) 
					VALUES ('$cabang','$_kode','$gudang','$tanggal', '$_plusminus', '$yangsetuju')");
				}

				if ($typestock == "so") {
					$datad = array(
						'koders' 		=> $this->session->userdata('unit'),
						'type'		=> $typestock,
						'kodeobat' 		=> $_kode,
						'gudang' 		=> $gudang,
						'tglso' 		=> date('Y-m-d', strtotime($tanggal)),
						'saldo' 		=> $_saldo,
						'sesuai' => $_plusminus,
						'hasilso' 		=> $qty[$i],
						'username' 		=> $userid,
						'tglentry' 		=> date('Y-m-d'),
						'jamentry' 		=> date('H:i:s'),
						'hna' 			=> $barang->hargabelippn,
						'hpp' 			=> $barang->hpp,
						'yangubah' => $_yangubah,
						'approve' => 0,
					);
				} else {
					$datad = array(
						'koders' 		=> $this->session->userdata('unit'),
						'type'		=> $typestock,
						'kodeobat' 		=> $_kode,
						'gudang' 		=> $gudang,
						'tglsesuai' 	=> date('Y-m-d', strtotime($tanggal)),
						'saldo' 		=> $_saldo,
						'sesuai' 		=> $_plusminus,
						'username' 		=> $userid,
						'hasilso' 		=> $qty[$i],
						'tglentry' 		=> date('Y-m-d'),
						'jamentry' 		=> date('H:i:s'),
						'hna' 			=> $barang->hargabelippn,
						'hpp' 			=> $barang->hpp,
						'yangubah' => $_yangubah,
						'approve' => 0,
					);
				}
				$xxx = $_saldo + $_plusminus;
				if ($_kode != "") {
					if ($param == 1) {
						$this->db->insert('tbl_aposesuailog', $datad);
					} else {
						$this->db->update('tbl_aposesuailog', $datad, array('kodebarang' => $_kode, 'kogudang' => $gudang));
					}

					// if ($typestock == "so") {
					// 	// $this->db->query(
					// 	// 	"update tbl_apostocklog set hasilso = $_qty, menyetujui = $yangsetuju, terima=0, keluar=0, saldoakhir= $xxx, tglso = '$tanggal' where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang'"
					// 	// );
					// 	$this->db->query(
					// 		"update tbl_apostocklog set sesuai = $_plusminus, menyetujui = '$yangsetuju', saldoakhir = $xxx, tglso = '$tanggal' where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang'"
					// 	);
					// } else {
					$this->db->query(
						"update tbl_apostocklog set sesuai = $_plusminus, menyetujui = '$yangsetuju', saldoakhir = $xxx, tglso = '$tanggal' where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang'"
					);
					// }
				}
			}
		} else {
			header('location:' . base_url());
		}
	}

	public function approve($id)
	{
		$id = $this->input->post('id');
		$this->db->set('approve', 1);
		$this->db->where('id', $id);
		$this->db->update('tbl_aposesuailog');
		echo json_encode(array("status" => 1));
	}

	public function ajax_delete($id)
	{
		$this->M_stockopname_log->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function getinfobarang($param)
	{
		$check	= $this->session->userdata("is_logged_in");

		if ($check == true) {
			$cabang = $this->session->userdata('unit');
			$prm = urldecode($param); //remove spaci
			$gudang = $this->input->get('gudang');
			$qry = "SELECT tbl_logbarang.*, (select saldoakhir from tbl_apostocklog where gudang = '$gudang' and koders= '$cabang' and kodebarang = tbl_logbarang.`kodebarang`) as salakhir FROM tbl_logbarang WHERE kodebarang = '$prm'";
			echo json_encode($this->db->query($qry)->row());
		} else {
			echo "You Have No Authority";
		}
	}

	// public function ajax_list(){
	// 	$list = $this->M_stockopname_log->get_datatables();

	// 	$data = array();
	// 	$no = $_POST['start'];
	// 	foreach ($list as $item) {
	// 		$no++;
	// 		$row = array();
	// 		$row[] = $item->koders;
	// 		$row[] = $item->gudang;
	// 		$row[] = date('d-m-Y',strtotime($item->tglso));			
	// 		$kode  = $item->kodeobat;
	// 		$nama  = $this->M_global->_data_barang_log($item->kodeobat)->namabarang;
	// 		$row[] = $item->kodeobat;
	// 		$row[] = $nama;
	// 		$row[] = number_format($item->saldo,0,',','.');						
	// 		$row[] = number_format($item->hasilso,0,',','.');						
	// 		$row[] = $item->username;
	// 		$row[] = 
	// 		     ' <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$item->id."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';

	// 		$data[] = $row;
	// 	}

	// 	$output = array(
	// 					"draw" => $_POST['draw'],
	// 					"recordsTotal" => $this->M_stockopname_log->count_all(),
	// 					"recordsFiltered" => $this->M_stockopname_log->count_filtered(),
	// 					"data" => $data,
	// 			);
	// 	//output to json format
	// 	echo json_encode($output);
	// }

	// public function ajax_edit($id){
	// 	$data = $this->M_stockopname_log->get_by_id($id);		
	// 	echo json_encode($data);
	// }

	// public function ajax_add(){
	// 	$this->_validate();
	// 	$tanggal  =$this->input->post('tanggal');
	// 	$koderak  =$this->input->post('rak');
	// 	$kdsubkat =$this->input->post('kdsubkat');
	// 	$kodemerk =$this->input->post('merk');
	// 	$kodeitem =$this->input->post('kodeitem');
	// 	$satuan   =$this->input->post('satuan');
	// 	$qtyopm   =$this->input->post('qty');

	// 	$jumdata  = count($kodeitem);
	// 	$_tanggal = date('Y-m-d',strtotime($tanggal));


	// 	for($i=0;$i<=$jumdata-1;$i++)
	// 	{
	// 		$item = $kodeitem[$i];
	// 		$qty  = $this->M_barang->get_by_id($item)->qty;
	// 		$hpp  = $this->M_barang->get_by_id($item)->hpp;
	// 		$sel  = $qtyopm[$i] - $qty;

	// 		$data = array(
	// 		'tgl'         => $_tanggal,
	// 		'koderak'     => $koderak,
	// 		'kodemerk'    => $kodemerk,
	// 		'kdsubkat'    => $kdsubkat[$i],
	// 		'kodeitem'    => $kodeitem[$i],
	// 		'sat'         => $satuan[$i],	
	// 		'hpp'         => $hpp,	
	// 		'qty'         => $qty,				
	// 		'qty_opname'  => $qtyopm[$i],				
	// 		'selisih'     => $sel,
	// 		'nilai_selisih' => $sel*$hpp,
	// 		'kodeuser'    => $this->session->userdata('username'),
	// 		);

	// 		$insert = $this->M_stockopname_log->save($data);
	// 	}

	// 	echo json_encode(array("status" => TRUE));


	// }

	// private function _validate(){
	// 	$data = array();
	// 	$data['error_string'] = array();
	// 	$data['inputerror'] = array();
	// 	$data['status'] = TRUE;


	// 	if($this->input->post('merk') == '')
	// 	{
	// 		$data['inputerror'][] = 'merk';
	// 		$data['error_string'][] = 'Merk belum dipilih';
	// 		$data['status'] = FALSE;
	// 	}

	// 	if($this->input->post('rak') == '')
	// 	{
	// 		$data['inputerror'][] = 'rak';
	// 		$data['error_string'][] = 'Rak belum dipilih';
	// 		$data['status'] = FALSE;
	// 	}


	// 	if($data['status'] === FALSE)
	// 	{
	// 		echo json_encode($data);
	// 		exit();
	// 	}
	// }

	// public function cetakformulir( $params ){		
	// 	$cek = $this->session->userdata('level');		
	// 	if(!empty($cek))
	// 	{		    
	// 		$param  = explode("~",$params);
	// 		$rak  = $param[0];
	// 		$tgl  = $param[1];

	// 		$data = $this->db->order_by('kodeitem')->get_where('inv_barang',array('kdrak'=>$rak))->result();	

	// 		$nrak  = $this->M_global->_data_rak( $rak )->nama;

	// 	    $pdf=new simkeu_rpt();
	// 		$pdf->setID('','','');
	// 		$pdf->setunit('');
	// 	    $pdf->setjudul('DAFTAR STOK OPNAME');
	// 		$pdf->setsubjudul('');
	// 		$pdf->addpage("P","A4");   
	// 		$pdf->setsize("P","A4");
	// 		$pdf->setfont('Times','B',10);
	// 		$pdf->SetWidths(array(30,5,40,35,30,5,30));
	// 		$pdf->SetAligns(array('L','C','L','C','L','C','L'));			
	// 		$judul0=array('Kode Rak',':',$nrak,'','Tanggal',':',$tgl);
	// 		$border= array('','','','','','','');
	// 	    $align = array('L','C','L','C','L','C','L');		    
	// 	    $pdf->FancyRow($judul0,$border,$align);

	// 		$pdf->ln(10);
	// 		$pdf->SetWidths(array(10,25,100,20,20));
	// 		$pdf->SetAligns(array('C','C','C','C','C'));
	// 		$judul=array('No.','Kode Item','Nama Item','Qty','Satuan');

	// 		$pdf->row($judul);
	// 		$pdf->SetWidths(array(10,25,100,20,20));
	// 		$pdf->SetAligns(array('C','L','L','C','C'));
	// 		$pdf->setfont('Times','',10);
	// 		$pdf->SetFillColor(224,235,255);
	// 		$pdf->SetTextColor(0);
	// 		$pdf->SetFont('');

	// 		$nourut = 1;

	// 		foreach($data as $db)
	// 		{
	// 		  $pdf->row(array($nourut, $db->kodeitem, $db->namabarang, '', $db->satuan));
	// 		  $nourut++;
	// 		}

	// 		$pdf->ln(10);
	// 		$pdf->SetWidths(array(40,40,30,40,40));
	// 		$border= array('','','','','');
	// 	    $align = array('C','C','C','C','C');		    
	// 		$foot1 = array('','PIC','','Saksi','');
	// 		$foot2 = array('','','','','');			

	// 	    $pdf->FancyRow($foot1,$border,$align);
	// 		$pdf->ln(10);
	// 		$border= array('','B','','B','');
	//         $pdf->FancyRow($foot2,$border,$align);


	// 		$pdf->AliasNbPages();
	// 		$pdf->output('FORMULIRSO.PDF','I');
	// 	} else {			
	// 		header('location:'.base_url());

	// 	}
	// }

	// public function hasilso( $params ){		
	// 	$cek = $this->session->userdata('level');		
	// 	if(!empty($cek))
	// 	{		    
	// 		$param  = explode("~",$params);
	// 		$rak  = $param[0];
	// 		$tgl  = $param[1];

	// 		$_tanggal= date('Y-m-d',strtotime($tgl));

	// 		$this->db->select('inv_stockopname.*, inv_barang.namabarang');
	// 		$this->db->from('inv_stockopname');
	// 		$this->db->join('inv_barang','inv_stockopname.kodeitem=inv_barang.kodeitem','left');
	// 		$this->db->where(array('kdrak'=>$rak,'tgl' => $_tanggal));
	// 		$this->db->order_by('inv_stockopname.pic','asc');
	// 		$data = $this->db->get()->result();



	// 		$nrak  = $this->M_global->_data_rak( $rak )->nama;

	// 	    $pdf=new simkeu_rpt();
	// 		$pdf->setID('','','');
	// 		$pdf->setunit('');
	// 	    $pdf->setjudul('HASIL STOK OPNAME');
	// 		$pdf->setsubjudul('');
	// 		$pdf->addpage("P","A4");   
	// 		$pdf->setsize("P","A4");
	// 		$pdf->setfont('Times','B',10);
	// 		$pdf->SetWidths(array(30,5,40,35,30,5,30));
	// 		$pdf->SetAligns(array('L','C','L','C','L','C','L'));			
	// 		$judul0=array('Kode Rak',':',$nrak,'','Tanggal',':',$tgl);
	// 		$judul1=array('PIC',':','','','','','');			
	// 		$border= array('','','','','','','');
	// 	    $align = array('L','C','L','C','L','C','L');		    
	// 	    $pdf->FancyRow($judul0,$border,$align);
	//         $pdf->FancyRow($judul1,$border,$align);

	// 		$pdf->ln(10);
	// 		$pdf->SetWidths(array(10,25,80,20,20,20));
	// 		$pdf->SetAligns(array('C','C','C','C','C','C'));
	// 		$judul=array('No.','Kode Item','Nama Item','Qty','Qty Opm','Satuan');

	// 		$pdf->row($judul);
	// 		$pdf->SetWidths(array(10,25,80,20,20,20));
	// 		$pdf->SetAligns(array('C','L','L','R','R','C'));
	// 		$pdf->setfont('Times','',10);
	// 		$pdf->SetFillColor(224,235,255);
	// 		$pdf->SetTextColor(0);
	// 		$pdf->SetFont('');

	// 		$nourut = 1;

	// 		foreach($data as $db)
	// 		{
	// 		  $pdf->row(array($nourut, $db->kodeitem, $db->namabarang, $db->qty, $db->qty_opname, $db->sat));
	// 		  $nourut++;
	// 		}


	// 		$pdf->AliasNbPages();
	// 		$pdf->output('HASILSO.PDF','I');
	// 	} else {			
	// 		header('location:'.base_url());

	// 	}
	// }

	// public function hasilsosel( $params ){		
	// 	$cek = $this->session->userdata('level');		
	// 	if(!empty($cek))
	// 	{		    
	// 		$param  = explode("~",$params);
	// 		$rak  = $param[0];
	// 		$tgl  = $param[1];
	// 		//$pic  = $param[3];

	// 		$_tanggal= date('Y-m-d',strtotime($tgl));

	// 		$this->db->select('inv_stockopname.*, inv_barang.namabarang');
	// 		$this->db->from('inv_stockopname');
	// 		$this->db->join('inv_barang','inv_stockopname.kodeitem=inv_barang.kodeitem','left');
	// 		$this->db->where(array('kdrak'=>$rak,'tgl' => $_tanggal));
	// 		$this->db->where('selisih<>0');

	// 		$this->db->order_by('inv_stockopname.pic','asc');
	// 		$data = $this->db->get()->result();



	// 		$nrak  = $this->M_global->_data_rak( $rak )->nama;

	// 	    $pdf=new simkeu_rpt();
	// 		$pdf->setID('','','');
	// 		$pdf->setunit('');
	// 	    $pdf->setjudul('HASIL STOCK OPNAME SELISIH');
	// 		$pdf->setsubjudul('');
	// 		$pdf->addpage("P","A4");   
	// 		$pdf->setsize("P","A4");
	// 		$pdf->setfont('Times','B',10);
	// 		$pdf->SetWidths(array(30,5,40,35,30,5,30));
	// 		$pdf->SetAligns(array('L','C','L','C','L','C','L'));			
	// 		$judul0=array('Kode Rak',':',$nrak,'','Tanggal',':',$tgl);
	// 		$judul1=array('PIC',':','','','','','');			
	// 		$border= array('','','','','','','');
	// 	    $align = array('L','C','L','C','L','C','L');		    
	// 	    $pdf->FancyRow($judul0,$border,$align);
	//         $pdf->FancyRow($judul1,$border,$align);

	// 		$pdf->ln(10);
	// 		$pdf->SetWidths(array(10,25,80,20,20,20));
	// 		$pdf->SetAligns(array('C','C','C','C','C','C'));
	// 		$judul=array('No.','Kode Item','Nama Item','Qty','Qty Opm','Satuan');

	// 		$pdf->row($judul);
	// 		$pdf->SetWidths(array(10,25,80,20,20,20));
	// 		$pdf->SetAligns(array('C','L','L','R','R','C'));
	// 		$pdf->setfont('Times','',10);
	// 		$pdf->SetFillColor(224,235,255);
	// 		$pdf->SetTextColor(0);
	// 		$pdf->SetFont('');

	// 		$nourut = 1;

	// 		foreach($data as $db)
	// 		{
	// 		  $pdf->row(array($nourut, $db->kodeitem, $db->namabarang, $db->qty, $db->qty_opname, $db->sat));
	// 		  $nourut++;
	// 		}


	// 		$pdf->AliasNbPages();
	// 		$pdf->output('HASILSO_SELISIH.PDF','I');
	// 	} else {			
	// 		header('location:'.base_url());

	// 	}
	// }			
}