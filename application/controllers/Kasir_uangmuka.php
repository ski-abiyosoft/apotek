<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kasir_uangmuka extends CI_Controller {

	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_kasirum','M_kasirum');
		$this->load->model('M_global','M_global');
		$this->load->helper('simkeu_rpt');
		$this->load->helper('simkeu_nota');
		$this->session->set_userdata('menuapp', '2000');
		$this->session->set_userdata('submenuapp', '2301');
		
	}

	public function index()
	{
		$cek = $this->session->userdata('username');				
		if(!empty($cek))
		{
			$this->load->view('klinik/v_kasir_uangmuka');
		} else
		{
			header('location:'.base_url());
		}			
	}
	
	public function entri()
	{
		$cek = $this->session->userdata('username');				
		if(!empty($cek))
		{
			$this->load->view('klinik/v_kasirum_add');
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function cariNotorisasi($no)
	{
		echo $no;
	}

	//saya rubah karena salah konsep
	// public function edit( $id )
	// {
	// 	$cek = $this->session->userdata('username');				
	// 	if(!empty($cek))
	// 	{
	// 		$data['id'] = $id;
	// 		$data['data'] = $this->db->query("select tbl_kasir.*, tbl_pasien.namapas
	// 		from tbl_kasir inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
	// 		where tbl_kasir.id = '$id'")->row();
			
	// 		$this->load->view('klinik/v_kasirum_edit', $data);
	// 	} else
	// 	{
	// 		header('location:'.base_url());
	// 	}			
	// }

	public function edit( $nokwitansi )
	{
		$cek = $this->session->userdata('username');		
		if(!empty($cek))
		{
			$data['id']    = $nokwitansi;
			$data['level'] = $this->input->get('level');
			$data['datas'] = $this->db->query("SELECT tbl_kasir.*, tbl_pasien.namapas
			from tbl_kasir inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
			where tbl_kasir.nokwitansi = '$nokwitansi'")->result();
			// echo "<pre>";
			// print_r($data);
			// die;
			
			$this->load->view('klinik/v_kasirum_edit', $data);
		} else
		{
			header('location:'.base_url());
		}			
	}
	
	public function ajax_list( $param )
	{
		
		$user_level   = $this->session->userdata('user_level');
		$dat          = explode("~",$param);
		if($dat[0]==1){
			$bulan = date('m');
			$tahun = date('Y');
			$list = $this->M_kasirum->get_datatables( 1, $bulan, $tahun );
		} else {
			$bulan  = date('Y-m-d',strtotime($dat[1]));
		    $tahun  = date('Y-m-d',strtotime($dat[2]));
		    $list = $this->M_kasirum->get_datatables( 2, $bulan, $tahun );
		}

		$data = array(); 
		$no = $_POST['start'];
		$jenisbayar = array('','Cash','Credit Card','Debet Card','Transfer','Online');
		
		foreach ($list as $unit) {
			$sisa = $unit->totalsemua-$unit->totalbayar;
			// $sisa = $unit->semua - $unit->umuka;
			$data_pasien = $this->M_global->_datapasien($unit->rekmed);
			$namapas = $data_pasien->namapas;
			$hp      = $data_pasien->handphone;
			$email   = $data_pasien->email;
			
			$no++;
			$row = array();
			$row[] = '<center>'.$unit->koders.'<center>';
			$row[] = '<center>'.$unit->username.'<center>';
			$row[] = $unit->nokwitansi;
			$row[] = $namapas;
			$row[] = $unit->rekmed;
            $row[] = $unit->noreg;			
			$row[] = date('d-m-Y',strtotime($unit->tglbayar));
			// $row[] = $unit->jenisbayar;			
			$row[] = $unit->totalsemua;
			// $row[] = $unit->semua;
			$row[] = $sisa;
						//saya mengganti line 120 semula $unit->id
			if($sisa>0){					
				$row[] = 
					'<a class="btn btn-sm btn-primary" href="'.base_url("kasir_uangmuka/edit/".$unit->nokwitansi."?level=".$user_level."").'" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a>
					
					<a class="btn btn-sm btn-warning" href="'.base_url("kasir_uangmuka/cetak/?id=".$unit->nokwitansi."").'" target="_blank" title="Cetak" ><i class="glyphicon glyphicon-print"></i></a>				   
					<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Kirim Email" onclick="send_email('."'".$unit->id."'".",'".$email."'".')"><i class="glyphicon glyphicon-envelope"></i> </a>
					<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Kirim Whatsapp" onclick="send_wa('."'".$unit->id."'".",'".$hp."'".')"><i class="fa fa-whatsapp"></i> </a>
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Batalkan" onclick="Batalkan('."'".$unit->nokwitansi."'".')"><i class="glyphicon glyphicon-remove"></i> </a>';
				   
			} else {
			  $row[] = '';	
			}	  
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_kasirum->count_all( $dat[0], $bulan, $tahun ),
						"recordsFiltered" => $this->M_kasirum->count_filtered( $dat[0], $bulan, $tahun ),
						"data" => $data,

				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_kasirum->get_by_id($id);		
		echo json_encode($data);
	}
   
	public function ajax_add()
	{
		// echo "<pre>";
		// print_r($this->input->post());
		// die;
		$cabang = $this->session->userdata('unit');	
		$userid = $this->session->userdata('username');
		$tanggal = $this->input->post('tanggal');
		
		$tahun = date('Y');
		$jumlah = str_replace(',','',$this->input->post('jumlah'));
		
		$kwitansi = urut_transaksi('URUTKWT', 19);

		if($this->input->post('noreg')){
			$_noreg = $this->input->post('noreg');
			
			$_dokter = $this->db->query("select kodokter from tbl_regist where noreg = '$_noreg'")->row()->kodokter;
		} else {
			$_noreg = '';
			$_dokter = '';
		}
		
		$this->_validate();

		if ($this->input->post('jumlah') != '') {
			$jumlah = str_replace(',','',$this->input->post('jumlah'));
			$datatbluangmuka = array(
				'koders' => $cabang,
				'nokwitansi' => $kwitansi,
				'rekmed' => $_noreg,
				'rekmed' => $this->input->post('pasien'),
				'tglbayar' => $tanggal,
				'jmbayar' => $jumlah,
				'jenisbayar' => '-',
				'dibayaroleh' => $this->input->post('dibayaroleh'),
				'posbayar' => 'UANG_MUKA',
				'username' => $userid,
				'accountno' => '-',
				'ket' => $this->input->post('ketbayar'),
				'closed' => 0,
				'kwitansivalid' => '-',
				
			);
			$insert = $this->db->insert('tbl_uangmuka', $datatbluangmuka);
		}

		if ($this->input->post('grandtotalcash') != 0) {
			$jumlah = str_replace(',','',$this->input->post('grandtotalcash'));
			$datacash = array(
				'koders' => $cabang,
				'nokwitansi' => $kwitansi,
				'noreg' => $_noreg,
				'kodokter' => $_dokter,
				'rekmed' => $this->input->post('pasien'),
				'tglbayar' => $tanggal,
				'jambayar' => $this->input->post('jam'),
				'uangmuka' => $jumlah,
				'totalsemua' => $jumlah,
				'posbayar' => 'UANG_MUKA',
				'dibayaroleh' => $this->input->post('dibayaroleh'),
				'lainket' => $this->input->post('ketbayar'),
				'jenisbayar' => $this->input->post('pembayaran_pilih_cash'),
				'username' => $userid,
			);
			// echo "<pre>";
			// print_r($datacash);
			// die;
		$insert = $this->M_kasirum->save($datacash);// save to tbl_kasir
		}
		if ($this->input->post('grandtotalcredit') != 0) {
			$jumlah = str_replace(',','',$this->input->post('grandtotalcredit'));
			$datacredit = array(
				'koders' => $cabang,
				'nokwitansi' => $kwitansi,
				'noreg' => $_noreg,
				'kodokter' => $_dokter,
				'rekmed' => $this->input->post('pasien'),
				'tglbayar' => $tanggal,
				'jambayar' => $this->input->post('jam'),
				'uangmuka' => $jumlah,
				'totalsemua' => $jumlah,
				'posbayar' => 'UANG_MUKA',
				'dibayaroleh' => $this->input->post('dibayaroleh'),
				'lainket' => $this->input->post('ketbayar'),
				'jenisbayar' => $this->input->post('pembayaran_pilih_credit'),
				'username' => $userid,
			);
		$insert = $this->M_kasirum->save($datacredit);// save to tbl_kasir

		$datatblcredit = array(
					'koders' => $cabang,
					'nokwitansi' => $kwitansi,
					'nocard' => $this->input->post('nokartu_credit'),
					'nootorisasi' => $this->input->post('nootorisasi_credit'),
					'tanggal' => $tanggal,
					'jenisbayar' => $this->input->post('pembayaran_pilih_credit'),
					'jumlahbayar' => $jumlah,
					'admrp' => '',
					'totalcardrp' => $jumlah,
					'kodebank' => $this->input->post('bank_credit'),
				);
				$insert = $this->db->insert('tbl_kartukredit', $datatblcredit);
		}

		if ($this->input->post('grandtotaldebet') != 0) {
			$jumlah = str_replace(',','',$this->input->post('grandtotaldebet'));
			$datadebet = array(
				'koders' => $cabang,
				'nokwitansi' => $kwitansi,
				'noreg' => $_noreg,
				'kodokter' => $_dokter,
				'rekmed' => $this->input->post('pasien'),
				'tglbayar' => $tanggal,
				'jambayar' => $this->input->post('jam'),
				'uangmuka' => $jumlah,
				'totalsemua' => $jumlah,
				'posbayar' => 'UANG_MUKA',
				'dibayaroleh' => $this->input->post('dibayaroleh'),
				'lainket' => $this->input->post('ketbayar'),
				'jenisbayar' => $this->input->post('pembayaran_pilih_debet'),
				'username' => $userid,
			);
			// echo "<pre>";
			// print_r($datadebet);
		// 	// die;
		$insert = $this->M_kasirum->save($datadebet);// save to tbl_kasir

		$datatbldebet = array(
			'koders' => $cabang,
			'nokwitansi' => $kwitansi,
			'nocard' => $this->input->post('nokartu_debet'),
			'nootorisasi' => $this->input->post('nootorisasi_debet'),
			'tanggal' => $tanggal,
			'jenisbayar' => $this->input->post('pembayaran_pilih_debet'),
			'jumlahbayar' => $jumlah,
			'admrp' => '',
			'totalcardrp' => $jumlah,
			'kodebank' => $this->input->post('bank_debet'),
		);
		$insert = $this->db->insert('tbl_kartukredit', $datatbldebet);

		}

		if ($this->input->post('grandtotaltransfer') != 0) {
			$jumlah = str_replace(',','',$this->input->post('grandtotaltransfer'));
			$datatransfer = array(
				'koders' => $cabang,
				'nokwitansi' => $kwitansi,
				'noreg' => $_noreg,
				'kodokter' => $_dokter,
				'rekmed' => $this->input->post('pasien'),
				'tglbayar' => $tanggal,
				'jambayar' => $this->input->post('jam'),
				'uangmuka' => $jumlah,
				'totalsemua' => $jumlah,
				'posbayar' => 'UANG_MUKA',
				'dibayaroleh' => $this->input->post('dibayaroleh'),
				'lainket' => $this->input->post('ketbayar'),
				'jenisbayar' => $this->input->post('pembayaran_pilih_transfer'),
				'username' => $userid,
			);
			// echo "<pre>";
			// print_r($datatransfer);
		// 	// die;
		$insert = $this->M_kasirum->save($datatransfer);// save to tbl_kasir

		$datatbltransfer = array(
			'koders' => $cabang,
			'nokwitansi' => $kwitansi,
			'nocard' => $this->input->post('nokartu_transfer'),
			'nootorisasi' => $this->input->post('nootorisasi_transfer'),
			'tanggal' => $tanggal,
			'jenisbayar' => $this->input->post('pembayaran_pilih_transfer'),
			'jumlahbayar' => $jumlah,
			'admrp' => '',
			'totalcardrp' => $jumlah,
			'kodebank' => $this->input->post('bank_transfer'),
		);
		$insert = $this->db->insert('tbl_kartukredit', $datatbltransfer);
		
		}

		if ($this->input->post('grandtotalonline') != 0) {
			$jumlah = str_replace(',','',$this->input->post('grandtotalonline'));
			$dataonline = array(
				'koders' => $cabang,
				'nokwitansi' => $kwitansi,
				'noreg' => $_noreg,
				'kodokter' => $_dokter,
				'rekmed' => $this->input->post('pasien'),
				'tglbayar' => $tanggal,
				'jambayar' => $this->input->post('jam'),
				'uangmuka' => $jumlah,
				'totalsemua' => $jumlah,
				'posbayar' => 'UANG_MUKA',
				'dibayaroleh' => $this->input->post('dibayaroleh'),
				'lainket' => $this->input->post('ketbayar'),
				'jenisbayar' => $this->input->post('pembayaran_pilih_online'),
				'username' => $userid,
			);
			// echo "<pre>";
			// print_r($dataonline);
		$insert = $this->M_kasirum->save($dataonline);// save to tbl_kasir

		$datatblonline = array(
			'koders' => $cabang,
			'nokwitansi' => $kwitansi,
			'nocard' => $this->input->post('nokartu_online'),
			'nootorisasi' => $this->input->post('nootorisasi_online'),
			'jenisbayar' => $this->input->post('pembayaran_pilih_online'),
			'tanggal' => $tanggal,
			'jumlahbayar' => $jumlah,
			'admrp' => '',
			'totalcardrp' => $jumlah,
			'kodebank' => $this->input->post('bank_online'),
		);
		$insert = $this->db->insert('tbl_kartukredit', $datatblonline);
		}
		// die;
		// $data = array(
		// 		'koders' => $cabang,
		// 		'nokwitansi' => $kwitansi,
		// 		'noreg' => $_noreg,
		// 		'kodokter' => $_dokter,
		// 		'rekmed' => $this->input->post('pasien'),
		// 		'tglbayar' => $tanggal,
		// 		'jambayar' => $this->input->post('jam'),
		// 		'uangmuka' => $jumlah,
		// 		'totalsemua' => $jumlah,
		// 		'posbayar' => 'UANG_MUKA',
		// 		'dibayaroleh' => $this->input->post('dibayaroleh'),
		// 		'lainket' => $this->input->post('ketbayar'),
		// 		'jenisbayar' => $this->input->post('pembayaran'),
		// 		'username' => $userid,
		// 	);
		// $insert = $this->M_kasirum->save($data);// save to tbl_kasir
		
		// if($this->input->post('pembayaran') !=1){
		// 	$total = str_replace(',','',$this->input->post('total'));
		// 	$adm = str_replace(',','',$this->input->post('adm'));
		// 	$gtotal = str_replace(',','',$this->input->post('grandtotal'));
		// 	$data = array(
		// 		'koders' => $cabang,
		// 		'nokwitansi' => $kwitansi,
		// 		'nocard' => $this->input->post('nokartu'),
		// 		'nootorisasi' => $this->input->post('nootorisasi'),
		// 		'tanggal' => $tanggal,
		// 		'jumlahbayar' => $total,
		// 		'admrp' => $adm,
		// 		'totalcardrp' => $gtotal,
		// 		'kodebank' => $this->input->post('bank'),
		// 	);
		// 	$insert = $this->db->insert('tbl_kartukredit', $data);
		// }
		echo json_encode(array("status" => TRUE,"nomor" => $kwitansi));
	}
	
	public function ajax_update()
	{
		$cabang = $this->session->userdata('unit');	
		$userid = $this->session->userdata('username');
		$tanggal = $this->input->post('tanggal');
		$tahun = date('Y');
		$jumlah = str_replace(',','',$this->input->post('jumlah'));
		$kwitansi = $this->input->post('nokwitansi');
		
		if($this->input->post('noreg')){
			$_noreg = $this->input->post('noreg');
			
			$_dokter = $this->db->query("select kodokter from tbl_regist where noreg = '$_noreg'")->row()->kodokter;
		} else {
			$_noreg = '';
			$_dokter = '';
		}
		
		$this->_validate();
		$data = array(
				'koders' => $cabang,
				'noreg' => $_noreg,
				'kodokter' => $_dokter,
				'rekmed' => $this->input->post('pasien'),
				'tglbayar' => $tanggal,
				'jambayar' => $this->input->post('jam'),
				'uangmuka' => $jumlah,
				'totalsemua' => $jumlah,
				'posbayar' => 'UANG_MUKA',
				'dibayaroleh' => $this->input->post('dibayaroleh'),
				'lainket' => $this->input->post('ketbayar'),
				'jenisbayar' => $this->input->post('pembayaran'),
				'username' => $userid,
				
			);
		
		$this->M_kasirum->update(array('id' => $this->input->post('q_id')), $data);
		
		if($this->input->post('pembayaran') !=1){
			
			$this->db->query("delete from tbl_kartukredit where nokwitansi = '$kwitansi'");
			$total = str_replace(',','',$this->input->post('total'));
			$adm = str_replace(',','',$this->input->post('adm'));
			$gtotal = str_replace(',','',$this->input->post('grandtotal'));
			$data = array(
				'koders' => $cabang,
				'nokwitansi' => $kwitansi,
				'nocard' => $this->input->post('nokartu'),
				'nootorisasi' => $this->input->post('nootorisasi'),
				'tanggal' => $tanggal,
				'jumlahbayar' => $total,
				'admrp' => $adm,
				'totalcardrp' => $gtotal,
				'kodebank' => $this->input->post('bank'),
			);
			$insert = $this->db->insert('tbl_kartukredit', $data);
		}
		echo json_encode(array("status" => TRUE,"nomor" => $kwitansi));
	}

	
	public function pembatalan($id)
	{
		/*
		$data = array(
		  'batal' => 1,
		  'keluar' => 1,
		  'batalkarena' => $this->input->post('alasan'),
		);
		*/
		
		//$result = $this->M_kasirum->update(array('id' => $id), $data);
		$result = $this->db->query("DELETE from tbl_kasir where nokwitansi = '$id'");
		if($result){
		  echo json_encode(array("status" => 1));
		} else {
		  echo json_encode(array("status" => 0));	
		}
	}
	


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('pasien') == '')
		{
			$data['inputerror'][] = 'pasien';
			$data['error_string'][] = 'Harus diisi';
			$data['status'] = FALSE;
		}

		
	
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	
	public function cetak()
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');		
		if(!empty($cek))
		{				  
            $nokwitansi = $this->input->get('id');	
			
            $profile = data_master('tbl_namers', array('koders' => $unit));
		    $nama_usaha=$profile->namars;
			$alamat1  = $profile->alamat;
			$alamat2  = $profile->kota;
		  
		    // $qkasir = "select * from tbl_kasir where id = '$nokwitansi'";
		    $qkasir = "select * from tbl_kasir where nokwitansi = '$nokwitansi'";
			$kasir  = $this->db->query($qkasir)->row(); 

			$qtot = "SELECT SUM(totalsemua) as jlhsemua FROM tbl_kasir where nokwitansi = '$nokwitansi'";
			$tot  = $this->db->query($qtot)->row(); 

			$qtblkrtkredit = "SELECT nocard, nootorisasi, jenisbayar FROM tbl_kartukredit WHERE nokwitansi = '$nokwitansi'";
			$tblkrtkredit = $this->db->query($qtblkrtkredit)->result_array(); 
			$jlhkk = count($tblkrtkredit);
			// echo "<pre>";
			// print_r($tblkrtkredit);
			
			$qbank = "SELECT jenisbayar, totalsemua FROM tbl_kasir WHERE nokwitansi = '$nokwitansi'";
			$bank = $this->db->query($qbank)->result_array(); 

			$pdf=new simkeu_nota();
			$pdf->setID($nama_usaha,$alamat1,$alamat2);
			$pdf->setjudul('KWITANSI UANG MUKA');
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
			$size  = array('8','','8');
			$max   = array(5,5,20);
			$fc     = array('0','0','0');
			$hc     = array('5','5','5');
			$pdf->setfont('Arial','B',10);
			$pdf->SetWidths(array(30,5,100));
			$border = array('','','');
			$fc     = array('0','0','0');
			$pdf->SetFillColor(230,230,230);			
			$pdf->setfont('Arial','',8);
			$terbilang = terbilang($tot->jlhsemua);
			$pdf->FancyRow(array('No. Kwitansi',':',$kasir->nokwitansi), $fc, $border,$align,$style,  $size, $max);
			$pdf->FancyRow(array('No. Registrasi',':',$kasir->noreg), $fc, $border,$align,$style,  $size, $max);
			$pdf->FancyRow(array('Tanggal',':',tanggal($kasir->tglbayar)), $fc, $border,$align,$style,  $size, $max);
			$pdf->FancyRow(array('Sudah Terima Dari',':',data_master('tbl_pasien', array('rekmed' =>$kasir->rekmed))->namapas), $fc, $border,$align,$style,  $size, $max);
			$pdf->FancyRow(array('Uang Sebesar',':',$terbilang), $fc, $border,$align,$style,  $size, $max);
			$pdf->FancyRow(array('Untuk Pembayaran',':',$kasir->lainket), $fc, $border,$align,$style,  $size, $max);
			$pdf->FancyRow(array('Nama Pasien',':',data_master('tbl_pasien', array('rekmed' =>$kasir->rekmed))->namapas), $fc, $border,$align,$style,  $size, $max);
			$pdf->FancyRow(array('No. Rek Med',':',$kasir->rekmed), $fc, $border,$align,$style,  $size, $max);
			$pdf->FancyRow(array('Pasien dari Dokter',':',($kasir->kodokter==''?'':data_master('tbl_dokter', array('kodokter' => $kasir->kodokter))->nadokter)), $fc, $border,$align,$style,  $size, $max);
			// $pdf->FancyRow(array('Bank',':',($kasir->jenisbayar==1?'KAS/TUNAI':'')), $fc, $border,$align,$style,  $size, $max); //awalnya ini saya rubah
			$jlh = count($bank);
			for ($i=0; $i <$jlh ; $i++) { 
				if ($bank[$i]['jenisbayar'] == '1') {
					$jnsPembayaran = 'KAS/TUNAI';
				}else if($bank[$i]['jenisbayar'] == '2'){
					$jnsPembayaran = 'CREDIT CARD';
				}else if($bank[$i]['jenisbayar'] == '3'){
					$jnsPembayaran = 'DEBET CARD';
				} elseif ($bank[$i]['jenisbayar'] == '4') {
					$jnsPembayaran = 'TRANSFER';
				}else{
					$jnsPembayaran = 'ONLINE';
				}
				$pdf->FancyRow(array($jnsPembayaran,':',($bank[$i]['totalsemua'])), $fc, $border,$align,$style,  $size, $max);
			}

			$pdf->FancyRow(array('Dibayar Oleh',':',$kasir->dibayaroleh), $fc, $border,$align,$style,  $size, $max);
			$pdf->ln();

			$border = array('LTB','TB','RTB');
			$size  = array('12','','12');
			$max   = array(20,5,20);
			$style = array('B','','B');
			$align = array('L','C','R');
			$pdf->SetWidths(array(30,5,40));
			// $pdf->FancyRow2(10,array('Rp.','',angka_rp($kasir->totalsemua,2)), $fc, $border,$align,$style,  $size, $max);//saya ganti
			$pdf->FancyRow2(10,array('Rp.','',angka_rp($tot->jlhsemua,2)), $fc, $border,$align,$style,  $size, $max);
			
			$pdf->ln(2);

			// ini saya baru coba
			$pdf->SetWidths(array(70,30,90));
			$border = array('T','','BT');
			$size   = array('','','');
			$pdf->setfont('Arial','B',18);
			$pdf->SetAligns(array('C','C','C'));
			$align = array('L','C','L');
			$style = array('','','B');
			$size  = array('8','','8');
			$max   = array(5,5,20);
			$fc     = array('0','0','0');
			$hc     = array('5','5','5');
			$pdf->setfont('Arial','B',10);
			$pdf->SetWidths(array(30,5,100));
			$border = array('','','');
			$fc     = array('0','0','0');
			$pdf->SetFillColor(230,230,230);			
			$pdf->setfont('Arial','',8);
			$terbilang = terbilang($tot->jlhsemua);
			for ($i=0; $i <$jlhkk ; $i++) { 
				if ($tblkrtkredit[$i]['jenisbayar'] == '1') {
					$jnsPembayaran = 'KAS/TUNAI';
				}else if($tblkrtkredit[$i]['jenisbayar'] == '2'){
					$jnsPembayaran = 'CREDIT CARD';
				}else if($tblkrtkredit[$i]['jenisbayar'] == '3'){
					$jnsPembayaran = 'DEBET CARD';
				} elseif ($tblkrtkredit[$i]['jenisbayar'] == '4') {
					$jnsPembayaran = 'TRANSFER';
				}else{
					$jnsPembayaran = 'ONLINE';
				}
				$arr = str_split($tblkrtkredit[$i]['nocard']);
				$arr[12] = "X";
				$arr[13] = "X";
				$arr[14] = "X";
				$arr[15] = "X";
				// echo "<pre>";
				// print_r($arr[0]);
				$pdf->FancyRow(array($jnsPembayaran,':',$arr[0].$arr[1].$arr[2].$arr[3].$arr[4].$arr[5].$arr[6].$arr[7].$arr[8].$arr[9].$arr[10].$arr[11].$arr[12].$arr[13].$arr[14].$arr[15]), $fc, $border,$align,$style,  $size, $max);
				$pdf->FancyRow(array('No Approval Code',':',$tblkrtkredit[$i]['nootorisasi']), $fc, $border,$align,$style,  $size, $max);
			}
			// coba
			
			$pdf->SetWidths(array(10,60,20,25,25));
			$border = array('TB','TB','TB','TB','TB');
			$align  = array('L','L','R','R','R');
			$pdf->setfont('Arial','B',8);
			$pdf->SetAligns(array('L','C','R'));
			//$pdf->SetFillColor(0,0,139);
			//$pdf->settextcolor(255,255,255);
			$fc = array('0','0','0','0','0','0');
			
			$border = array('','','');
			$pdf->setfont('Arial','',8);
			$tot = 0;
			$subtot = 0;
			$tdisc  = 0;
			$border = array('','','','','','');
			$align  = array('L','L','R','R','R','R');
			$fc = array('0','0','0','0','0','0');
			$pdf->SetFillColor(0,0,139);
			$pdf->settextcolor(0);
			$nomor = 1;
			$ppn   = 0;
			$dpp   = 0;
			
			$border = array('T','T','T','T','T','T');
			
			$align  = array('L','L','L','L','C','R');
			$pdf->setfont('Arial','B',10);
			$pdf->SetWidths(array(25,5,55,25,5,25));
			$border = array('','','','LT','T','TR');
			$fc     = array('0','0','0','0','0','0');
			$pdf->SetFillColor(230,230,230);			
			$pdf->setfont('Arial','',8);
			$pdf->ln(1);
			
			//$pdf->ln(5);
			$pdf->SetWidths(array(40,40,60));
			$border = array('','','');
			$align  = array('C','C','C');
			$pdf->FancyRow(array('','',$profile->kota.', '.date('d-m-Y',strtotime($kasir->tglbayar))),0,$border, $align);
			$pdf->FancyRow(array('','',$nama_usaha),0,$border, $align);
			$pdf->FancyRow(array('Pasien','','Cashier,'),0,$border, $align);
			$pdf->ln(1);
			$pdf->ln(8);
			$pdf->SetWidths(array(40,40,60));
			$pdf->SetFont('Arial','',8);
			$pdf->SetAligns(array('C','C','C'));
			$border = array('','','');	
			$pdf->FancyRow(array('','',''),0,$border,$align);
			$border = array('','','');	
			$pdf->FancyRow(array(data_master('tbl_pasien', array('rekmed' =>$kasir->rekmed))->namapas,'',$kasir->username),0,$border,$align);
	
		
            $pdf->SetTitle($kasir->nokwitansi);  
			$pdf->AliasNbPages();
			$pdf->output('./uploads/uangmuka/'.$kasir->nokwitansi.'.PDF','F');
			$pdf->output($kasir->nokwitansi.'.PDF','I');			
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
	public function send_wa(){		 
         $userid   = $this->session->userdata('inv_username');
		 $param = $this->input->post('id');		 
		 
	     $data = $this->db->query("select tbl_kasir.*, tbl_pasien.namapas, tbl_pasien.email, tbl_pasien.handphone from tbl_kasir 
		 inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed 
		 where tbl_kasir.id = '$param'")->row();
		 $attched_file  = base_url()."uploads/uangmuka/".$data->nokwitansi.".PDF";
		 $mobile = $data->handphone;		 		 
		 $txtwa   = "KWITANSI UANG MUKA"."\r\n".
		            "No. Kwitansi : ".$data->nokwitansi."\r\n".
					"No. RM : ".$data->rekmed."\r\n".
					"Nama Pasien : ".$data->namapas."\r\n".
					"Tanggal : ".date('d M Y',strtotime($data->tglbayar))."\r\n".
					"Jumlah Uang Muka : ".angka_rp($data->totalsemua,2)."\r\n".
					"Rincian Kwitansi klik link berikut: "."\r\n";
					
		 
		 $txtwa2   = $attched_file;
		 
         $result= send_wa_txt($mobile,$txtwa);		 		 		 		
		 $result= send_wa_txt($mobile,$txtwa2);		 		 		 		
		 echo json_encode(array("status" => 1));
	}
	
	public function send_email(){		 
         $userid   = $this->session->userdata('inv_username');
		 $unit= $this->session->userdata('unit');
		 $profile = data_master('tbl_namers', array('koders' => $unit));
		 $nama_usaha=$profile->namars;
		 $email_usaha= $profile->email;
			
		 $param = $this->input->post('id');		 
		 
	     $data = $this->db->query("select tbl_kasir.*, tbl_pasien.namapas, tbl_pasien.email, tbl_pasien.handphone from tbl_kasir 
		 inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed 
		 where tbl_kasir.id = '$param'")->row();
		 $attched_file  = base_url()."uploads/uangmuka/".$data->nokwitansi.".PDF";
		 $mobile = $data->handphone;		 		 
		 $ready_message   = "KWITANSI UANG MUKA"."\r\n".
		            "No. Kwitansi : ".$data->nokwitansi."\r\n".
					"No. RM : ".$data->rekmed."\r\n".
					"Nama Pasien : ".$data->namapas."\r\n".
					"Tanggal : ".date('d M Y',strtotime($data->tglbayar))."\r\n".
					"Jumlah Uang Muka : ".angka_rp($data->totalsemua,2)."\r\n";
				
		    
			$email_tujuan = trim($data->email);
						
			$server_subject = "KWITANSI UANG MUKA ";
			
			$email_usaha = "support@gmail.com";
			$this->load->library('email');
			$this->email->from($email_usaha, $nama_usaha);
			$this->email->to($email_tujuan);
			$this->email->subject($server_subject);
			$this->email->message($ready_message);
			$this->email->attach($attched_file);
			
			if($this->email->send()){
				echo json_encode(array("status" => 1));
		
			}
			else{
				echo json_encode(array("status" => 0));
			}
			
        
	}
	
}
