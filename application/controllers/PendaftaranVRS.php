<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PendaftaranVRS extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->model('M_pendaftaranVRS');
		$this->load->model('M_pasien','M_pasien');
		$this->load->model('M_global','M_global');
		$this->load->model('M_cetak','M_cetak');
		$this->load->helper('simkeu_rpt');
		$this->load->helper('app_global_helper');
		$this->session->set_userdata('menuapp', '2000');
		$this->session->set_userdata('submenuapp', '2105');
		$this->load->helper('simkeu_nota1');		
		$this->load->helper('simkeu_nota');		
	}

	public function index(){
		$cek = $this->session->userdata('username');				
		if(!empty($cek))
		{
			$cabang = $this->session->userdata('unit');
			$data= [
				'pasienrjtoday' => $this->db->query('SELECT*from pasien_rajal where tglmasuk like "%'.date("Y-m-d").'%" and kodepos != "PUGD" and koders = "'.$cabang.'" AND batal = 0')->num_rows(),
				
				'pasienigdtoday' => $this->db->query('SELECT*from pasien_rajal where tglmasuk like "%'.date("Y-m-d").'%" and kodepos = "PUGD" and koders = "'.$cabang.'" group by id')->num_rows(),
				
				'pasienritoday' => $this->db->query('SELECT*from pasien_ranap where tglmasuk like "%'.date("Y-m-d").'%" and koders = "'.$cabang.'"')->num_rows(),
				
				'pasien_igd' => $this->db->query('SELECT id, koders, uidlogin, antrino, noreg, rekmed, tglmasuk, namapas, tujuan, nadokter, cust_nama, jenispas, batal, keluar from pasien_rajal join userlogin on pasien_rajal.username=userlogin.uidlogin where koders = "'.$cabang.'" and tujuan = 1 and kodepos = "PUGD" and tglmasuk like "%'.date('Y-m-d').'%"')->result(),
			];
			$this->load->view('PendaftaranVRS/v_pendaftaran', $data);
		} else
		{
			header('location:'.base_url());
		}		
	}

	// master
	public function get_kota(){
		$provinsi = $this->input->post('lupprovinsi');
		$sql = $this->db->query('select * from tbl_kabupaten where kodeprop = "'.$provinsi.'"')->result();
		echo json_encode($sql, JSON_PRETTY_PRINT);
	}

	public function get_kecamatan(){
		$kecamatan = $this->input->post('kabkota');
		$sql = $this->db->query('select * from tbl_kecamatan where kodekab = "'.$kecamatan.'"')->result();
		echo json_encode($sql, JSON_PRETTY_PRINT);
	}

	public function get_desa(){
		$kecamatan = $this->input->post('lupkecamatan');
		$sql = $this->db->query('select * from tbl_desa where kodekec = "'.$kecamatan.'"')->result();
		echo json_encode($sql, JSON_PRETTY_PRINT);
	}

	public function getKP(){
		$kecamatan = $this->input->post('lupkecamatan');
		$sql = $this->db->query('select kodepos from tbl_desa where kodekec = "'.$kecamatan.'"')->row_array();
		echo json_encode($sql, JSON_PRETTY_PRINT);
	}

	function namaprovinsi()
    	{        
	    	$kode = $this->input->get('kode');
		$data = $this->db->query("select * from tbl_propinsi where kodeprop='$kode'")->row_array();
		echo json_encode($data);
	}
	
	function namaprovinsi_all()
	{
		$data = $this->db->query("select * from tbl_propinsi")->result();
		echo json_encode($data);
	}
	
	function namakota()
	{        
		$kode = $this->input->get('kode');
		$data = $this->db->query("select * from tbl_kabupaten where kodekab='$kode'")->row();
		echo json_encode($data);
	}
	
	function namakecamatan()
	{        
		$kode = $this->input->get('kode');
		$data = $this->db->query("SELECT * from tbl_kecamatan where kodekec='$kode'")->row();
		echo json_encode($data);
    	}

	function getdesa()
    	{        
	    	$kode = $this->input->get('kode');
		$data = $this->db->query("select * from tbl_desa where kodekec='$kode'")->result();
		echo json_encode($data);
    	}

	public function getinfopasien(){
		$id = $this->input->get('id');
		$result = $this->M_pendaftaranVRS->datapasien($id);
		echo json_encode($result);
	}

	// end master

	// pasien_igd
	public function entri_igd(){
		$cek = $this->session->userdata('username');				
		if(!empty($cek))
		{
			$data = [
				'propinsi' => $this->db->get('tbl_propinsi')->result(),
			];
			$this->load->view('PendaftaranVRS/v_add_igd', $data);
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function igd($param){
		$dat   = explode("~",$param);
		if($dat[0]==1){
			$bulan = $this->M_global->_periodebulan();
			$tahun = $this->M_global->_periodetahun();
			$list = $this->M_pendaftaranVRS->get_datatables_igd( 1, $bulan, $tahun );
		} else {
			$bulan  = date('Y-m-d',strtotime($dat[1]));
			$tahun  = date('Y-m-d',strtotime($dat[2]));
			$list = $this->M_pendaftaranVRS->get_datatables_igd( 2, $bulan, $tahun );
		}
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit) {
		$data_pasien = $this->M_global->_datapasien($unit->rekmed);
		$namapas = $data_pasien->namapas;
		$hp      = $data_pasien->handphone;
		$email   = $data_pasien->email;
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $unit->koders;
			$row[] = $unit->uidlogin;
			$row[] = $unit->antrino;
			$row[] = $unit->noreg;
			$row[] = $unit->rekmed;			
			$row[] = date('d-m-Y',strtotime($unit->tglmasuk));
			$row[] = $unit->namapas;
			if($unit->tujuan == 1){
				$tujuan = 'I G D';
			} else {
				$tujuan = 'Rawat Inap';
			}
			if ($unit->keluar == 0) {
                	$status = '<span class="label label-sm label-warning">Register</span>';
			} elseif ($unit->keluar == 1) {
				$status = '<span class="label label-sm label-success">Closed</span> '; 
			}
			
			if ($unit->batal == 1) {
                	$status = '<span class="label label-sm label-danger">Batal</span>';
			} 
			$row[] = $tujuan;			
			$row[] = $unit->nadokter;
			$row[] = $unit->cust_nama;
			$nocard = $this->db->query('select nobpjs from tbl_regist where rekmed = "'.$unit->rekmed.'" and noreg = "'.$unit->noreg.'"')->row_array();
			$row[] = $nocard['nobpjs'];
			$row[] = $status;
			if($unit->keluar == 0 && $unit->batal == 0){			
			  $row[] = 
					'<a class="btn btn-sm btn-primary" href="'.base_url("pendaftaranVRS/edit_igd/".$unit->id."").'" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a> 				  
					<a class="btn btn-sm btn-warning" href="'.base_url("pendaftaranVRS/cetak_igd2/?noreg=".$unit->noreg."").'" target="_blank" title="Cetak" ><i class="glyphicon glyphicon-print"></i></a>				   
					<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Kirim Email" onclick="send_email('."'".$unit->id."'".",'".$email."'".')"><i class="glyphicon glyphicon-envelope"></i> </a>
					<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Kirim Whatsapp" onclick="send_wa('."'".$unit->id."'".",'".$hp."'".')"><i class="fa fa-whatsapp"></i> </a>
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Batalkan" onclick="Batalkan('."'".$unit->id."'".",'igd'".')"><i class="glyphicon glyphicon-remove"></i> </a>';
			} else {
			  $row[] = '';	
			}
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->M_pendaftaranVRS->count_all_igd( $dat[0], $bulan, $tahun ),
			"recordsFiltered" => $this->M_pendaftaranVRS->count_filtered_igd( $dat[0], $bulan, $tahun ),
			"data" => $data,
		);
		//data to json format
		echo json_encode($output);
	}

	// cetak igd 2
	public function cetak_igd2()
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');		
		if(!empty($cek))
		{				  
            $noreg = $this->input->get('noreg');	
            $profile = $this->M_global->_LoadProfileLap();	
		    $unit= $this->session->userdata('unit');	 
		    $nama_usaha=$profile->nama_usaha;
			$alamat1  = $profile->alamat1;
			$alamat2  = $profile->telpon;
		  
		    $qregis = "select tbl_regist.*, tbl_pasien.*, tbl_regist.kodepos as kodepost from tbl_regist 
			inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed
			where noreg = '$noreg'";
			 
			$regist = $this->db->query($qregis)->row();
			
			$pdf=new simkeu_nota1();
			$pdf->setID($nama_usaha,$alamat1,$alamat2);
			$pdf->setjudul(' ');
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
			//$judul=array('Kwitansi ','','Faktur Penjualan');
			$fc     = array('0','0','0');
			$hc     = array('20','20','20');
			//$pdf->FancyRow2(10,$judul, $fc,  $border, $align, $style, $size, $max);
			//$pdf->ln(1);
			$pdf->setfont('Arial','B',10);
			$pdf->SetWidths(array(30,5,50,10,25,5,55));
			$border = array('','','','','','','');
			$fc     = array('0','0','0','0','0','0','0');
			$pdf->SetFillColor(230,230,230);			
			
			
			$pdf->setfont('Arial','',8);
			$pdf->FancyRow(array('','','No. RM :','','','',''), $fc, $border);
			$pdf->setfont('Arial','B',21);
			$pdf->FancyRow(array('','',$regist->rekmed,'','','',''), $fc, $border);
			$pdf->setfont('Arial','',8);
			$pdf->FancyRow(array('No. Reg',':',$regist->noreg,'','','',''), $fc, $border);
			$pdf->FancyRow(array('Nama Pasien',':',$regist->namapas,'','','',''), $fc, $border);
			$pdf->FancyRow(array('Tempat/Tgl. Lahir',':',$regist->tempatlahir.' / '.tanggal($regist->tgllahir),'','','',''), $fc, $border);
			$pdf->FancyRow(array('Umur',':',hitung_umur($regist->tgllahir),'','','',''), $fc, $border);
			$pdf->FancyRow(array('Alamat',':',$regist->alamat,'','','',''), $fc, $border);
			$pdf->FancyRow(array('Tgl. Masuk',':',tanggal($regist->tglmasuk).' Jam '.$regist->jam,'','','',''), $fc, $border);
			$pdf->FancyRow(array('Pasien',':',($regist->baru==1?'Baru':'Lama'),'','','',''), $fc, $border);
			$pdf->FancyRow(array('Pelayanan',':',data_master('tbl_namapos',array('kodepos' => $regist->kodepost))->namapost,'','','',''), $fc, $border);
			$pdf->FancyRow(array('Dokter',':',data_master('tbl_dokter',array('kodokter' => $regist->kodokter))->nadokter,'','','',''), $fc, $border);
			
			
			$pdf->ln(2);
			
			
            $pdf->setTitle($noreg); 
			$pdf->AliasNbPages();
			$pdf->output('./uploads/regist/'.$noreg.'.PDF','F');
			$pdf->output($noreg.'.PDF','I');			
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	// end cetak igd 2

	public function edit_igd( $id )
	{
		$cek = $this->session->userdata('username');				
		if(!empty($cek))
		{
			$data['id'] = $id;
			$data['data'] = $this->db->query("select tbl_regist.*, tbl_pasien.namapas, tbl_pasien.noidentitas, tbl_pasien.idpas, tbl_pasien.jkel, tbl_pasien.handphone, tbl_pasien.preposisi, tbl_pasien.namapanggilan, tbl_pasien.namakeluarga, tbl_pasien.tempatlahir, tbl_pasien.tgllahir, tbl_pasien.status, tbl_pasien.wn, tbl_pasien.agama, tbl_pasien.pendidikan, tbl_pasien.goldarah, tbl_pasien.hoby, tbl_pasien.pekerjaan, tbl_pasien.alamat, tbl_pasien.rt, tbl_pasien.rw, tbl_pasien.alamat2, tbl_pasien.handphone, tbl_pasien.propinsi, tbl_pasien.phone, tbl_pasien.kabupaten, tbl_pasien.email, tbl_pasien.kecamatan, tbl_pasien.fb, tbl_pasien.ig, tbl_pasien.twit, tbl_pasien.kelurahan, tbl_pasien.kodepos as kdpos
			from tbl_regist inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed
			where tbl_regist.id = '$id'")->row();
			
			$this->load->view('PendaftaranVRS/v_pendaftaranrs_edit_igd', $data);
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function dokter_igd(){
		$str = $this->input->post('searchTerm');
		echo json_encode($this->M_pendaftaranVRS->get_dokter($str));
	}

	function get_dokter_igd(){
		$data = $this->db->query('select d.kodokter, d.nadokter from tbl_drpoli p join tbl_dokter d on p.kodokter=d.kodokter where p.kopoli = "PUGD" and koders = "'.$this->session->userdata('unit').'"')->result();
		echo json_encode($data, JSON_PRETTY_PRINT);
	}

	// igd add and regist
	function tambah_pasien_igd(){
		$cabang = $this->session->userdata('unit');
		$norm = urut_transaksi_igd('URUT_RS_IGD', 6);
		$noregori = $this->input->post('noreg');
		$normori = $this->input->post('lupnorm');
		$namapasienx = $this->input->post('lupnamapasien');
		$namapasien = preg_replace('/\s+/', ' ', $namapasienx);
		$namadepan = '';
		$namabelakang = '';
		$namakeluarga = $this->input->post('lupnamakeluarga');
		$namapanggilan = $this->input->post('lupnamapanggilan');
		$tempatlahir = $this->input->post('luptempatlahir');
		$tgllahir = $this->input->post('luptgllahir');
		$jeniskelamin = $this->input->post('lupjeniskelamin');
		$jkelhub = '';
		$status = $this->input->post('lupstatus');
		$noidentitas = $this->input->post('lupnoidentitas');
		$identitas = $this->input->post('lupidentitas');
		$preposisi = $this->input->post('luppreposition');
		$nocard = '';
		$pendidikan = $this->input->post('luppendidikan');
		$suku = $this->input->post('lupwarganegara');
		$agama = $this->input->post('lupagama');
		$pekerjaan = $this->input->post('luppekerjaan');
		$goldarah = $this->input->post('lupgoldarah');
		$hoby = $this->input->post('luphobby');
		$alamat1 = $this->input->post('lupalamat1');
		$alamat2 = $this->input->post('lupalamat2');
		$propinsi = $this->input->post('lupprovinsi');
		$kabupaten = $this->input->post('kabkota');
		$kecamatan = $this->input->post('lupkecamatan');
		$kelurahan = $this->input->post('lupkelurahan');
		$handphone = $this->input->post('luphp');
		$phone = $this->input->post('lupphone');
		$email = $this->input->post('lupemail');
		$twit = $this->input->post('luptwitter');
		$fb = $this->input->post('lupfb');
		$ig = $this->input->post('lupig');
		$orhub = '';
		$hubungan = '';
		$alamathub = '';
		$emailhub = '';
		$phonehub = '';
		$pekerjaanhub = '';
		$hphub = '';
		$lastnoreg = '';
		$ada = '';
		$blokir = '';
		$ketblokir = '';
		$alergi = '';
		$rt = $this->input->post('luprt');
		$rw = $this->input->post('luprw');
		$kodepos1 = $this->input->post('lupkodepos1');
		$wn = '';
		$iklinik = '';
		$cek = $this->db->query('select * from tbl_pasien where rekmed = "'.$normori.'"')->row_array();
		$cekpasien = $this->db->query('select * from tbl_pasien where namapas = "'.$namapasien.'" and tempatlahir = "'.$tempatlahir.'" and tgllahir like "%'.$tgllahir.'%"')->row_array();
		if($cek['rekmed']){
			$this->db->set('namapas', $namapasien);
			$this->db->set('idpas', $identitas);
			$this->db->set('noidentitas', $noidentitas);
			$this->db->set('namapanggilan', $namapanggilan);
			$this->db->set('namakeluarga', $namakeluarga);
			$this->db->set('tempatlahir', $tempatlahir);
			$this->db->set('tgllahir', $tgllahir);
			$this->db->set('jkel', $jeniskelamin);
			$this->db->set('status', $status);
			$this->db->set('suku', $suku);
			$this->db->set('wn', $suku);
			$this->db->set('agama', $agama);
			$this->db->set('pendidikan', $pendidikan);
			$this->db->set('goldarah', $goldarah);
			$this->db->set('hoby', $hoby);
			$this->db->set('pekerjaan', $pekerjaan);
			$this->db->set('alamat', $alamat1);
			$this->db->set('rt', $rt);
			$this->db->set('rw', $rw);
			$this->db->set('alamat2', $alamat2);
			$this->db->set('handphone', $handphone);
			$this->db->set('propinsi', $propinsi);
			$this->db->set('phone', $phone);
			$this->db->set('kabupaten', $kabupaten);
			$this->db->set('email', $email);
			$this->db->set('kecamatan', $kecamatan);
			$this->db->set('fb', $fb);
			$this->db->set('kelurahan', $kelurahan);
			$this->db->set('twit', $twit);
			$this->db->set('kodepos', $kodepos1);
			$this->db->set('ig', $ig);
			$this->db->set('koders', $cabang);
			$this->db->where('rekmed', $cek['rekmed']);
			$this->db->update('tbl_pasien');
			echo json_encode(['status' => 1]);
		} else if($cekpasien){
			$this->db->set('idpas', $identitas);
			$this->db->set('noidentitas', $noidentitas);
			$this->db->set('namapanggilan', $namapanggilan);
			$this->db->set('namakeluarga', $namakeluarga);
			$this->db->set('jkel', $jeniskelamin);
			$this->db->set('status', $status);
			$this->db->set('suku', $suku);
			$this->db->set('wn', $suku);
			$this->db->set('agama', $agama);
			$this->db->set('pendidikan', $pendidikan);
			$this->db->set('goldarah', $goldarah);
			$this->db->set('hoby', $hoby);
			$this->db->set('pekerjaan', $pekerjaan);
			$this->db->set('alamat', $alamat1);
			$this->db->set('rt', $rt);
			$this->db->set('rw', $rw);
			$this->db->set('alamat2', $alamat2);
			$this->db->set('handphone', $handphone);
			$this->db->set('propinsi', $propinsi);
			$this->db->set('phone', $phone);
			$this->db->set('kabupaten', $kabupaten);
			$this->db->set('email', $email);
			$this->db->set('kecamatan', $kecamatan);
			$this->db->set('fb', $fb);
			$this->db->set('kelurahan', $kelurahan);
			$this->db->set('twit', $twit);
			$this->db->set('kodepos', $kodepos1);
			$this->db->set('ig', $ig);
			$this->db->set('koders', $cabang);
			$this->db->where('namapas', $namapasien);
			$this->db->where('tempatlahir', $tempatlahir);
			$this->db->where('tgllahir', $tgllahir);
			$this->db->update('tbl_pasien');
			echo json_encode(['status' => 1]);
		} else {
			$datapasien = [
				'koders' => $cabang,
				'rekmed' => $norm,
				'namapas' => $namapasien,
				'namadepan' => $namadepan,
				'namabelakang' => $namabelakang,
				'preposisi' => $preposisi,
				'namakeluarga' => $namakeluarga,
				'namapanggilan' => $namapanggilan,
				'tempatlahir' => $tempatlahir,
				'tgllahir' => $tgllahir,
				'jkel' => $jeniskelamin,
				'status' => $status,
				'noidentitas' => $noidentitas,
				'idpas' => $identitas,
				'nocard' => $nocard,
				'pendidikan' => $pendidikan,
				'suku' => $suku,
				'agama' => $agama,
				'pekerjaan' => $pekerjaan,
				'goldarah' => $goldarah,
				'hoby' => $hoby,
				'alamat' => $alamat1,
				'alamat2' => $alamat2,
				'propinsi' => $propinsi,
				'kabupaten' => $kabupaten,
				'kecamatan' => $kecamatan,
				'kelurahan' => $kelurahan,
				'handphone' => $handphone,
				'phone' => $phone,
				'email' => $email,
				'twit' => $twit,
				'fb' => $fb,
				'ig' => $ig,
				'orhub' => $orhub,
				'alamathub' => $alamathub,
				'emailhub' => $emailhub,
				'phonehub' => $phonehub,
				'hphub' => $hphub,
				'lastnoreg' => $lastnoreg,
				'ada' => $ada,
				'blokir' => $blokir,
				'ketblokir' => $ketblokir,
				'alergi' => $alergi,
				'rt' => $rt,
				'rw' => $rw,
				'kodepos' => $kodepos1,
				'wn' => $suku,
			];
			$this->db->insert('tbl_pasien', $datapasien);
			echo json_encode(['status' => 0]);
		}
	}
	
	function tambah_pasien_register_igd(){
		$cabang = $this->session->userdata('unit');
		$poli = 'PUGD';
		$mjkn_token = $this->input->post('booking');
		$dokter = $this->input->post('dokter');
		$noreg = urut_transaksi('REGISTRASI', 16);
		$ruang = $this->input->post('ruang');
		$antrino = $this->input->post('antrino');
		$tanggal = $this->input->post('tanggal');
		$jam = $this->input->post('jam');
		$pengirim = $this->input->post('pengirim');
		$norm = $this->input->post('norm');
		$username = $this->session->userdata('username');
		$jenispasien = $this->input->post('jenispasien');
		$penjamin = $this->input->post('vpenjamin');
		$nocard = $this->input->post('nocard');
		$norujukan = $this->input->post('norujukan');
		$nosep = $this->input->post('nosep');
		$tujuan = 1;
		$kelas = '';
		$baru = 1;
		$keluar = 0;
		// $tglkeluar;
		$jamkeluar = '';
		$shipt = 0;
		$batal = 0;
		$batalkarena = '';
		$diperiksa_perawat = 0;
		if($jenispasien == 'PAS1'){
			$cust = '';
			$bpjs = null;
			$rujukan = null;
			$sep = null;
		} else {
			$cust = $penjamin;
			$bpjs = $nocard;
			$rujukan = $norujukan;
			$sep = $nosep;
		}
		$cek = $this->db->query('select * from tbl_regist where rekmed = "'.$norm.'" and keluar = 0')->row_array();
		if($cek){
			$this->db->set('username', $username);
			$this->db->set('koders', $cabang);
			$this->db->set('mjkn_token', $mjkn_token);
			$this->db->set('kodokter', $dokter);
			$this->db->set('koderuang', $ruang);
			$this->db->set('tujuan', $tujuan);
			$this->db->set('kodepos', $poli);
			$this->db->set('baru', $baru);
			$this->db->set('antrino', $antrino);
			$this->db->set('tglmasuk', $tanggal);
			$this->db->set('tglmasuk', $tanggal);
			$this->db->set('jam', $jam);
			$this->db->set('drpengirim', $pengirim);
			$this->db->set('jenispas', $jenispasien);
			$this->db->set('cust_id', $cust);
			$this->db->set('nobpjs', $bpjs);
			$this->db->set('norujukan', $rujukan);
			$this->db->set('nosep', $sep);
			$this->db->where('keluar', 0);
			$this->db->where('rekmed', $cek['rekmed']);
			$this->db->update('tbl_regist');
			echo json_encode(['status' => 1]);
		} else {
			$dataregist = [
				'username' => $username,
				'mjkn_token' => $mjkn_token,
				'rekmed' => $norm,
				'noreg' => $noreg,
				'kodokter' => $dokter,
				'koderuang' => $ruang,
				'tujuan' => $tujuan,
				'kodepos' => $poli,
				'baru' => $baru,
				'antrino' => $antrino,
				'tglmasuk' => $tanggal,
				'jam' => $jam,
				'drpengirim' => $pengirim,
				'jenispas' => $jenispasien,
				'cust_id' => $cust,
				'nobpjs' => $bpjs,
				'norujukan' => $rujukan,
				'nosep' => $sep,
				'koders' => $cabang,
			];
			$this->db->insert('tbl_regist', $dataregist);
			echo json_encode(['status' => 0]);
		}
	}
	// end igd add and regist

	public function pembatalan($id)
	{
		$cabang = $this->session->userdata("unit");
		$regist = $this->db->get_where("tbl_regist", ['id' => $id])->row();
		$noreg = $regist->noreg;
		$cek = $this->db->get_where("tbl_rekammedisrs", ['noreg' => $noreg, 'koders' => $cabang])->num_rows();
		if($cek > 0){
			echo json_encode(["status" => 2]);
		} else {
			$alasan = $this->input->post('alasan');
			$this->db->set('batal', 1);
			$this->db->set('keluar', 1);
			$this->db->set('batalkarena', $alasan);
			$this->db->where('id', $id);
			$this->db->update('tbl_regist');
	
			$get_data = $this->db->get_where('tbl_regist', ['id' => $id])->row();
	
			$this->db->set('ada', 0);
			$this->db->where('rekmed', $get_data->rekmed);
			$this->db->update('tbl_pasien');
			
			echo json_encode(["status" => 1]);
			history_log(0 ,'REGISTRASI_RAJAL' ,'BATAL' ,$noreg ,'-');
		}
	}
	
	// entri rawat jalan
	public function entri_rj(){
		$cek = $this->session->userdata('username');				
		if(!empty($cek))
		{
			$data = [
				'propinsi' => $this->db->get('tbl_propinsi')->result(),
				'namapos' => $this->db->get('tbl_namapos')->result(),
			];
			$this->load->view('PendaftaranVRS/v_add_rawat_jalan', $data);
		} else
		{
			header('location:'.base_url());
		}			
	}
	// end entri rawat jalan

	// rawat jalan add and regist
	function tambah_pasien_rawat_jalan(){
		$cabang         = $this->session->userdata('unit');
		$norm           = urut_transaksi_igd('URUT_RS_IGD', 6);
		$noregori       = $this->input->post('noreg');
		$normori        = $this->input->post('lupnorm');
		$namapasienx    = $this->input->post('lupnamapasien');
		$namapasien     = preg_replace('/\s+/', ' ', $namapasienx);
		$namadepan      = '';
		$namabelakang   = '';
		$namakeluarga   = $this->input->post('lupnamakeluarga');
		$namapanggilan  = $this->input->post('lupnamapanggilan');
		$tempatlahir    = $this->input->post('luptempatlahir');
		$tgllahir       = $this->input->post('luptgllahir');
		$jeniskelamin   = $this->input->post('lupjeniskelamin');
		$jkelhub        = '';
		$status         = $this->input->post('lupstatus');
		$noidentitas    = $this->input->post('lupnoidentitas');
		$identitas      = $this->input->post('lupidentitas');
		$preposisi      = $this->input->post('luppreposition');
		$nocard         = '';
		$pendidikan     = $this->input->post('luppendidikan');
		$suku           = $this->input->post('lupwarganegara');
		$agama          = $this->input->post('lupagama');
		$pekerjaan      = $this->input->post('luppekerjaan');
		$goldarah       = $this->input->post('lupgoldarah');
		$hoby           = $this->input->post('luphobby');
		$alamat1        = $this->input->post('lupalamat1');
		$alamat2        = $this->input->post('lupalamat2');
		$propinsi       = $this->input->post('lupprovinsi');
		$kabupaten      = $this->input->post('kabkota');
		$kecamatan      = $this->input->post('lupkecamatan');
		$kelurahan      = $this->input->post('lupkelurahan');
		$handphone      = $this->input->post('luphp');
		$phone          = $this->input->post('lupphone');
		$email          = $this->input->post('lupemail');
		$twit           = $this->input->post('luptwitter');
		$fb             = $this->input->post('lupfb');
		$ig             = $this->input->post('lupig');
		$orhub        = '';
		$hubungan     = '';
		$alamathub    = '';
		$emailhub     = '';
		$phonehub     = '';
		$pekerjaanhub = '';
		$hphub        = '';
		$lastnoreg    = '';
		$ada          = '';
		$blokir       = '';
		$ketblokir    = '';
		$alergi       = '';
		$rt = $this->input->post('luprt');
		$rw = $this->input->post('luprw');
		$kodepos1 = $this->input->post('lupkodepos1');
		$wn = '';
		$iklinik = '';
		$cek = $this->db->query('select * from tbl_pasien where rekmed = "'.$normori.'"')->row_array();
		if($noidentitas == '-'){
			$datapasien = [
					'koders' => $cabang,
					'rekmed' => $norm,
					'namapas' => $namapasien,
					'namadepan' => $namadepan,
					'namabelakang' => $namabelakang,
					'preposisi' => $preposisi,
					'namakeluarga' => $namakeluarga,
					'namapanggilan' => $namapanggilan,
					'tempatlahir' => $tempatlahir,
					'tgllahir' => $tgllahir,
					'jkel' => $jeniskelamin,
					'status' => $status,
					'noidentitas' => $noidentitas,
					'idpas' => $identitas,
					'nocard' => $nocard,
					'pendidikan' => $pendidikan,
					'suku' => $suku,
					'agama' => $agama,
					'pekerjaan' => $pekerjaan,
					'goldarah' => $goldarah,
					'hoby' => $hoby,
					'alamat' => $alamat1,
					'alamat2' => $alamat2,
					'propinsi' => $propinsi,
					'kabupaten' => $kabupaten,
					'kecamatan' => $kecamatan,
					'kelurahan' => $kelurahan,
					'handphone' => $handphone,
					'phone' => $phone,
					'email' => $email,
					'twit' => $twit,
					'fb' => $fb,
					'ig' => $ig,
					'orhub' => $orhub,
					'alamathub' => $alamathub,
					'emailhub' => $emailhub,
					'phonehub' => $phonehub,
					'hphub' => $hphub,
					'lastnoreg' => $lastnoreg,
					'ada' => $ada,
					'blokir' => $blokir,
					'ketblokir' => $ketblokir,
					'alergi' => $alergi,
					'rt' => $rt,
					'rw' => $rw,
					'kodepos' => $kodepos1,
					'wn' => $suku,
				];
				$this->db->insert('tbl_pasien', $datapasien);
				history_log(0 ,'PENDAFTARAN PASIEN' ,'ADD' ,$norm ,'-');
				echo json_encode(['status' => 0, 'norm' => $norm]);
		} else {
			$ceknik = $this->db->query("SELECT * FROM tbl_pasien WHERE noidentitas = '$noidentitas'")->num_rows();
			if($ceknik < 1){
				$cekpasien = $this->db->query('select * from tbl_pasien where namapas = "'.$namapasien.'" and tempatlahir = "'.$tempatlahir.'" and tgllahir like "%'.$tgllahir.'%"')->num_rows();
				if($cekpasien > 0){
					$this->db->set('idpas', $identitas);
					$this->db->set('noidentitas', $noidentitas);
					$this->db->set('namapanggilan', $namapanggilan);
					$this->db->set('namakeluarga', $namakeluarga);
					$this->db->set('jkel', $jeniskelamin);
					$this->db->set('status', $status);
					$this->db->set('suku', $suku);
					$this->db->set('wn', $suku);
					$this->db->set('agama', $agama);
					$this->db->set('pendidikan', $pendidikan);
					$this->db->set('goldarah', $goldarah);
					$this->db->set('hoby', $hoby);
					$this->db->set('pekerjaan', $pekerjaan);
					$this->db->set('alamat', $alamat1);
					$this->db->set('rt', $rt);
					$this->db->set('rw', $rw);
					$this->db->set('alamat2', $alamat2);
					$this->db->set('handphone', $handphone);
					$this->db->set('propinsi', $propinsi);
					$this->db->set('phone', $phone);
					$this->db->set('kabupaten', $kabupaten);
					$this->db->set('email', $email);
					$this->db->set('kecamatan', $kecamatan);
					$this->db->set('fb', $fb);
					$this->db->set('kelurahan', $kelurahan);
					$this->db->set('twit', $twit);
					$this->db->set('kodepos', $kodepos1);
					$this->db->set('ig', $ig);
					$this->db->set('koders', $cabang);
					$this->db->where('namapas', $namapasien);
					$this->db->where('tempatlahir', $tempatlahir);
					$this->db->where('tgllahir', $tgllahir);
					$this->db->update('tbl_pasien');
					echo json_encode(['status' => 1]);
				} else {
					$datapasien = [
						'koders' => $cabang,
						'rekmed' => $norm,
						'namapas' => $namapasien,
						'namadepan' => $namadepan,
						'namabelakang' => $namabelakang,
						'preposisi' => $preposisi,
						'namakeluarga' => $namakeluarga,
						'namapanggilan' => $namapanggilan,
						'tempatlahir' => $tempatlahir,
						'tgllahir' => $tgllahir,
						'jkel' => $jeniskelamin,
						'status' => $status,
						'noidentitas' => $noidentitas,
						'idpas' => $identitas,
						'nocard' => $nocard,
						'pendidikan' => $pendidikan,
						'suku' => $suku,
						'agama' => $agama,
						'pekerjaan' => $pekerjaan,
						'goldarah' => $goldarah,
						'hoby' => $hoby,
						'alamat' => $alamat1,
						'alamat2' => $alamat2,
						'propinsi' => $propinsi,
						'kabupaten' => $kabupaten,
						'kecamatan' => $kecamatan,
						'kelurahan' => $kelurahan,
						'handphone' => $handphone,
						'phone' => $phone,
						'email' => $email,
						'twit' => $twit,
						'fb' => $fb,
						'ig' => $ig,
						'orhub' => $orhub,
						'alamathub' => $alamathub,
						'emailhub' => $emailhub,
						'phonehub' => $phonehub,
						'hphub' => $hphub,
						'lastnoreg' => $lastnoreg,
						'ada' => $ada,
						'blokir' => $blokir,
						'ketblokir' => $ketblokir,
						'alergi' => $alergi,
						'rt' => $rt,
						'rw' => $rw,
						'kodepos' => $kodepos1,
						'wn' => $suku,
					];
					$this->db->insert('tbl_pasien', $datapasien);
					history_log(0 ,'PENDAFTARAN PASIEN' ,'ADD' ,$norm ,'-');
					echo json_encode(['status' => 0, 'norm' => $norm]);
				}
			} else {
				echo json_encode(['status' => 2]);
			}
		}
	}
	
	function tambah_pasien_register_rawat_jalan(){
		$cabang               = $this->session->userdata('unit');
		$poli                 = $this->input->post('poliklinik1');
		$mjkn_token           = $this->input->post('booking');
		$dokter               = $this->input->post('dokter');
		// $noreg        = urut_transaksi('REGISTRASI', 16);
		$ruang                = $this->input->post('ruang');
		$antrino1             = $this->input->post('antrino1');
		$antrino              = $this->input->post('antrino');
		$tanggal              = $this->input->post('tanggal');
		$jam                  = $this->input->post('jam');
		$pengirim             = $this->input->post('pengirim');
		$norm                 = $this->input->post('norm');
		$username             = $this->session->userdata('username');
		$jenispasien          = $this->input->post('jenispasien');
		$penjamin             = $this->input->post('vpenjamin');
		$nocard               = $this->input->post('nocard');
		$norujukan            = $this->input->post('norujukan');
		$nosep                = $this->input->post('nosep');
		$tujuan               = 1;
		$kelas                = '';
		$baru                 = 1;
		$keluar               = 0;
		// $tglkeluar;
		$jamkeluar            = '';
		$shipt                = 0;
		$batal                = 0;
		$batalkarena          = '';
		$diperiksa_perawat    = 0;

		if($jenispasien == 'PAS1'){
			$cust    = '';
			$bpjs    = null;
			$rujukan = null;
			$sep     = null;
		} else {
			$cust    = $penjamin;
			$bpjs    = $nocard;
			$rujukan = $norujukan;
			$sep     = $nosep;
		}
		
		$noreg = urut_transaksi('REGISTRASI', 16);
		
		$cekdata=$this->db->query("SELECT*from tbl_pasien where rekmed='$norm'")->row();
		if($cekdata->ada==1){
			echo json_encode(['status' => 2, 'noreg' => $noreg, 'nm' => $cekdata->namapas]);			
		}else{
		
			// husain change
			$dataregist = [
				'username'    => $username,
				'mjkn_token'  => $mjkn_token,
				'rekmed'      => $norm,
				'noreg'       => $noreg,
				'kodokter'    => $dokter,
				'koderuang'   => $ruang,
				'tujuan'      => $tujuan,
				'kodepos'     => $poli,
				'baru'        => $baru,
				'antrino'     => $antrino,
				'antrino1'    => $antrino1,
				'tglmasuk'    => $tanggal,
				'jam'         => $jam,
				'drpengirim'  => $pengirim,
				'jenispas'    => $jenispasien,
				'cust_id'     => $cust,
				'nobpjs'      => $bpjs,
				'norujukan'   => $rujukan,
				'nosep'       => $sep,
				'koders'      => $cabang,
			];
			$this->db->insert('tbl_regist', $dataregist);
			// husain add
			$this->db->set('lastnoreg', $noreg);
			if($nocard != '' || $nocard != null){
				$this->db->set('nocard', $nocard);
			}
			$this->db->set('ada', 1);
			$this->db->where('rekmed', $norm);
			$this->db->update('tbl_pasien');
			// end husain
			history_log(0 ,'REGISTRASI_RAJAL' ,'SAVE' ,$noreg ,'-');
			echo json_encode(['status' => 0, 'noreg' => $noreg]);
			// end husain
		}
	}
	// end rawat jalan add and regist
	public function edit_rawatjalan(){
		$cabang               = $this->session->userdata('unit');
		$poli                 = $this->input->post('poliklinik1');
		$mjkn_token           = $this->input->post('booking');
		$dokter               = $this->input->post('dokter');
		$noreg                = $this->input->get('noreg');
		$ruang                = $this->input->post('ruang');
		$antrino              = $this->input->post('antrino');
		$antrino1             = $this->input->post('antrino1');
		$tanggal              = $this->input->post('tanggal');
		$jam                  = $this->input->post('jam');
		// $pengirim     = $this->input->post('pengirim');
		$pengirim             = '';
		$norm                 = $this->input->post('norm');
		$username             = $this->session->userdata('username');
		$jenispasien          = $this->input->post('jenispasien');
		$penjamin             = $this->input->post('vpenjamin');
		$nocard               = $this->input->post('nocard');
		$norujukan            = $this->input->post('norujukan');
		$nosep                = $this->input->post('nosep');
		$tujuan               = 1;
		$kelas                = '';
		$baru                 = 1;
		$keluar               = 0;
		// $tglkeluar;
		$jamkeluar            = '';
		$shipt                = 0;
		$batal                = 0;
		$batalkarena          = '';
		$diperiksa_perawat    = 0;
		if($jenispasien == 'PAS1'){
			$cust    = '';
			$bpjs    = null;
			$rujukan = null;
			$sep     = null;
		} else {
			$cust    = $penjamin;
			$bpjs    = $nocard;
			$rujukan = $norujukan;
			$sep     = $nosep;
		}

		$data = [
			'username'   => $username,
			'koders'     => $cabang,
			'mjkn_token' => $mjkn_token,
			'kodokter'   => $dokter,
			'koderuang'  => $ruang,
			'baru'       => $baru,
			'antrino'    => $antrino,
			'antrino1'   => $antrino1,
			'tglmasuk'   => $tanggal,
			'jam'        => $jam,
			'drpengirim' => $pengirim,
			'jenispas'   => $jenispasien,
			'cust_id'    => $cust,
			'nobpjs'     => $bpjs,
			'norujukan'  => $rujukan,
			'nosep'      => $sep,
			'kodepos'    => $poli,
		];
		$where = [
			'noreg' => $noreg,
			'keluar' => 0,
		];

		$this->db->update('tbl_regist', $data, $where);
		$cek_pasien = $this->db->get_where("tbl_pasien", ['rekmed' => $norm])->row();
		if($cek_pasien->nocard != $nocard){
			$this->db->set('nocard', $nocard);
			$this->db->where('rekmed', $norm);
			$this->db->update('tbl_pasien');
		}
		
		history_log(0 ,'REGISTRASI_RAJAL' ,'EDIT' ,$noreg ,'-');
		echo json_encode(['status' => 0, 'noreg' => $noreg]);
		// end husain
	}
	// edit rawat_jalan
	
	// end edit rajal

	// list rawat jalan
	public function rawatjalan($param){
		
		$cek_user   = $this->session->userdata('user_level');
		$dat        = explode("~",$param);
		if($dat[0]==1){
			$bulan   = $this->M_global->_periodebulan();
			$tahun   = $this->M_global->_periodetahun();
			$list    = $this->M_pendaftaranVRS->get_datatables( 1, $bulan, $tahun );
		} else {
			$bulan   = date('Y-m-d',strtotime($dat[1]));
			$tahun   = date('Y-m-d',strtotime($dat[2]));
			$list    = $this->M_pendaftaranVRS->get_datatables( 2, $bulan, $tahun );
		}
		$data   = array();
		$no     = $_POST['start'];
		foreach ($list as $unit) {
		$data_pasien    = $this->M_global->_datapasien($unit->rekmed);
		$namapas        = $data_pasien->namapas;
		$hp             = $data_pasien->handphone;
		$email          = $data_pasien->email;
			$no++;
			$row   = array();
			$row[] = $no;
			$row[] = $unit->koders;
			$row[] = $unit->uidlogin;
			$row[] = $unit->antrino1.'.'.$unit->antrino;
			$row[] = $unit->noreg;
			$row[] = $unit->rekmed;
			$row[] = date('d-m-Y',strtotime($unit->tglmasuk));
			$row[] = $unit->namapas;
			if($unit->tujuan == 1){
				$tujuan = 'Rawat Jalan';
			} else {
				$tujuan = 'Rawat Inap';
			}
			if ($unit->keluar == 0) {
                	$status = '<span class="label label-sm label-warning">Register</span>';
			} elseif ($unit->keluar == 1) {
				$status = '<span class="label label-sm label-success">Closed</span> '; 
			}
			
			if ($unit->batal == 1) {
                	$status = '<span class="label label-sm label-danger">Batal</span>';
			}
			$row[] = $tujuan;			
			$row[] = $unit->nadokter;
			$row[] = $unit->cust_nama;
			$row[] = $unit->nobpjs;
			$row[] = $status;
			if($unit->keluar == 0 && $unit->batal == 0){			
				// <a class="btn btn-sm btn-primary" href="'.base_url("pendaftaranVRS/edit_rj/".$unit->id."").'" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a>
				
				if($cek_user==0){
				
					$row[] = 
					'
					
					<a class="btn btn-sm btn-warning" href="'.base_url("pendaftaranVRS/cetak_rj2/?noreg=".$unit->noreg."").'" target="_blank" title="Cetak" ><i class="glyphicon glyphicon-print"></i></a>';
						
				}else{
					$row[] = 
					'<a class="btn btn-sm btn-primary" href="'.base_url("pendaftaranVRS/edit_rj/".$unit->id."").'" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a>
					<a class="btn btn-sm btn-warning" href="'.base_url("pendaftaranVRS/cetak_rj2/?noreg=".$unit->noreg."").'" target="_blank" title="Cetak" ><i class="glyphicon glyphicon-print"></i></a>		

					<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Kirim Email" onclick="send_email('."'".$unit->id."'".",'".$email."'".')"><i class="glyphicon glyphicon-envelope"></i> </a>

					<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Kirim Whatsapp" onclick="send_wa('."'".$unit->id."'".",'".$hp."'".')"><i class="fa fa-whatsapp"></i> </a>

					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Batalkan" onclick="Batalkan('."'".$unit->id."'".",'rajal'".')"><i class="glyphicon glyphicon-remove"></i> </a>';
				}
			} else {
			  $row[] = '';	
			}
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->M_pendaftaranVRS->count_all( $dat[0], $bulan, $tahun ),
			"recordsFiltered" => $this->M_pendaftaranVRS->count_filtered( $dat[0], $bulan, $tahun ),
			"data" => $data,
		);
		//data to json format
		echo json_encode($output);
	}
	// end list rawat jalan

	// cetak rj 2
	public function cetak_rj2()
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');		
		if(!empty($cek))
		{				  
            $noreg = $this->input->get('noreg');	
            $profile = $this->M_global->_LoadProfileLap();	
		    $unit= $this->session->userdata('unit');	 
		    $nama_usaha=$profile->nama_usaha;
			$alamat1  = $profile->alamat1;
			$alamat2  = $profile->telpon;
		  
		    $qregis = "select tbl_regist.*, tbl_pasien.*, tbl_regist.kodepos as kodepost from tbl_regist 
			inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed
			where noreg = '$noreg'";
			 
			$regist = $this->db->query($qregis)->row();
			
			$pdf=new simkeu_nota1();
			$pdf->setID($nama_usaha,$alamat1,$alamat2);
			$pdf->setjudul(' ');
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
			//$judul=array('Kwitansi ','','Faktur Penjualan');
			$fc     = array('0','0','0');
			$hc     = array('20','20','20');
			//$pdf->FancyRow2(10,$judul, $fc,  $border, $align, $style, $size, $max);
			//$pdf->ln(1);
			$pdf->setfont('Arial','B',10);
			$pdf->SetWidths(array(30,5,50,10,25,5,55));
			$border = array('','','','','','','');
			$fc     = array('0','0','0','0','0','0','0');
			$pdf->SetFillColor(230,230,230);			
			
			
			$pdf->setfont('Arial','',8);
			$pdf->FancyRow(array('','','No. RM :','','','',''), $fc, $border);
			$pdf->setfont('Arial','B',21);
			$pdf->FancyRow(array('','',$regist->rekmed,'','','',''), $fc, $border);
			$pdf->setfont('Arial','',8);
			$pdf->FancyRow(array('No. Reg',':',$regist->noreg,'','','',''), $fc, $border);
			$pdf->FancyRow(array('Nama Pasien',':',$regist->namapas,'','','',''), $fc, $border);
			$pdf->FancyRow(array('Tempat/Tgl. Lahir',':',$regist->tempatlahir.' / '.tanggal($regist->tgllahir),'','','',''), $fc, $border);
			$pdf->FancyRow(array('Umur',':',hitung_umur($regist->tgllahir),'','','',''), $fc, $border);
			$pdf->FancyRow(array('Alamat',':',$regist->alamat,'','','',''), $fc, $border);
			$pdf->FancyRow(array('Tgl. Masuk',':',tanggal($regist->tglmasuk).' Jam '.$regist->jam,'','','',''), $fc, $border);
			$pdf->FancyRow(array('Pasien',':',($regist->baru==1?'Baru':'Lama'),'','','',''), $fc, $border);
			$pdf->FancyRow(array('Pelayanan',':',data_master('tbl_namapos',array('kodepos' => $regist->kodepost))->namapost,'','','',''), $fc, $border);
			$pdf->FancyRow(array('Dokter',':',data_master('tbl_dokter',array('kodokter' => $regist->kodokter))->nadokter,'','','',''), $fc, $border);
			$pdf->ln(2);
			$pdf->setTitle($noreg); 
			$pdf->AliasNbPages();
			$pdf->output('', $noreg.'.PDF','F');
			// $pdf->output($noreg.'.PDF','I');			
		} else {
			header('location:'.base_url());
		}
	}
	// end cetak rj 2

	// cetak rj 3
	public function cetak_rj3()
	{
		$cek        = $this->session->userdata('level');
		$unit       = $this->session->userdata('unit');
		$user       = $this->session->userdata('username');	
		$noreg      = $this->input->get('noreg');
		$umur       = $this->input->get('umur');
		if(!empty($cek))
		{				  
			$avatar        = $this->session->userdata('avatar_cabang');
			$get_regist = $this->db->get_where("tbl_regist", ['noreg' => $noreg])->row();
			$get_pasien = $this->db->get_where("tbl_pasien", ['rekmed' => $get_regist->rekmed])->row();
			$get_dokter = $this->db->get_where("tbl_dokter", ['kodokter' => $get_regist->kodokter])->row();
			$get_hms = $this->db->get_where("tbl_setinghms", ['kodeset' => $get_regist->jenispas])->row();
			$kop       = $this->M_cetak->kop($unit);
			$profile   = data_master('tbl_namers', array('koders' => $unit));
			$namars    = $kop['namars'];
			$alamat    = $kop['alamat'];
			$alamat2   = $kop['alamat2'];
			$alamat3   = $profile->kota;
			$kota      = $kop['kota'];
			$phone     = $kop['phone'];
			$whatsapp  = $kop['whatsapp'];
			$npwp      = $kop['npwp'];
			$chari     = '';
			$chari .= " <table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:16px; color:#000;\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
				<thead>
					<tr>
						<td rowspan=\"6\" align=\"center\">
						<img src=\"" . base_url() . "assets/img_user/$avatar\"  width=\"70\" height=\"70\" /></td>
						</td>
						<td colspan=\"20\">
							<b>
								<tr>
									<td style=\"font-size:16px;border-bottom: none;\"><b><br>$namars</b></td>
								</tr>
								<tr>
									<td style=\"font-size:14px;\">$alamat</td>
								</tr>
								<tr>
									<td style=\"font-size:14px;\">$alamat2</td>
								</tr>
								<tr>
									<td style=\"font-size:14px;\">Wa :$whatsapp    Telp :$phone </td>
								</tr>
								<tr>
									<td style=\"font-size:14px;\">No. NPWP : $npwp</td>
								</tr>
							</b>
						</td>
					</tr>
				</table>";
			$chari .= "
											<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
														<tr>
																<td> &nbsp; </td>
														</tr> 
											</table>";
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\">     
											<tr>
														<td style=\"border-top: none;border-right: none;border-left: none;\"></td>
											</tr> 
									</table>";
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\">     
											<tr>
														<td style=\"border-top: none;border-right: none;border-left: none;\"></td>
											</tr> 
									</table>";
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
											<tr>
														<td> &nbsp; </td>
											</tr> 
									</table>";
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
											<tr>
														<td width=\"15%\" style=\"text-align:center; font-size:30px;\"><b>TRACER NO. RM</b></td>
											</tr>
											<tr>
														<td width=\"15%\" style=\"text-align:center; font-size:30px;\"><b>".$get_regist->rekmed."</b></td>
											</tr>
											<tr>
														<td width=\"15%\" style=\"text-align:center; font-size:14px;\">TGL. MASUK : ".date('d-m-Y', strtotime($get_regist->tglmasuk))." ".date('H:i:s', strtotime($get_regist->jam))."</td>
											</tr>
									</table>";
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
											<tr>
														<td> &nbsp; </td>
											</tr> 
									</table>";	
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\">     
											<tr>
														<td style=\"border-top: none;border-right: none;border-left: none;\"></td>
											</tr> 
									</table>";
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\">     
											<tr>
														<td style=\"border-top: none;border-right: none;border-left: none;\"></td>
											</tr> 
									</table>";
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
											<tr>
														<td> &nbsp; </td>
											</tr> 
									</table>";
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:14px; text-transform: uppercase;\" width=\"60%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
											<tr>
														<td width=\"15%\" style=\"text-align:center; text-transform: uppercase;\">NAMA PASIEN : $get_pasien->namapas</td>
											</tr>
											<tr>
														<td width=\"15%\" style=\"text-align:center; text-transform: uppercase;\">USIA/UMUR : $umur</td>
											</tr>
											<tr>
														<td width=\"15%\" style=\"text-align:center; text-transform: uppercase;\">NO. KUNJUNGAN : -</td>
											</tr>
											<tr>
														<td width=\"15%\" style=\"text-align:center; text-transform: uppercase;\">DOKTER POLIKLINIK : <b>$get_dokter->nadokter</b></td>
											</tr>
											<tr>
														<td width=\"15%\" style=\"text-align:center; text-transform: uppercase;\">POLIKLINIK : $get_regist->kodepos</td>
											</tr>
											<tr>
														<td width=\"15%\" style=\"text-align:center; text-transform: uppercase;\">CARA BAYAR : $get_hms->keterangan</td>
											</tr>
											<tr>
														<td width=\"15%\" style=\"text-align:center; text-transform: uppercase;\">USER : $get_regist->username</td>
											</tr>
											<tr>
														<td width=\"15%\" style=\"text-align:center; text-transform: uppercase;\">USER REGISTRASI : $get_regist->username</td>
											</tr>
									</table>";
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
											<tr>
														<td> &nbsp; </td>
											</tr> 
									</table>";	
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\">     
											<tr>
														<td style=\"border-top: none;border-right: none;border-left: none;\"></td>
											</tr> 
									</table>";
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\">     
											<tr>
														<td style=\"border-top: none;border-right: none;border-left: none;\"></td>
											</tr> 
									</table>";
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
											<tr>
														<td> &nbsp; </td>
											</tr> 
									</table>";
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"60%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
											<tr>
														<td width=\"15%\" style=\"text-align:center; font-size:25px;\"><b>SISIPKAN SEBAGAI PENGGANTI BERKAS RM YANG KELUAR</b></td>
											</tr>
									</table>";
			$chari .= "
									<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"60%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
											<tr>
														<td width=\"15%\" style=\"text-align:center; font-size:14px;\">TGL. CETAK : ".date('d-m-Y')." ".date("H:i:s")."</td>
											</tr>
									</table>";
			$data['prev'] = $chari;
			$judul = "TRACER NO. RM ".$get_regist->rekmed;
			echo ("<title>$judul</title>");
			$this->M_cetak->mpdf('P', 'A4', $judul, $chari, '.PDF', 10, 10, 10, 2);
		} else {	
			header('location:'.base_url());	
		}
	}
	// end cetak rj 3

	// edit rawat jalan
	public function edit_rj( $id )
	{
		$cek = $this->session->userdata('username');				
		if(!empty($cek))
		{
			$data['id'] = $id;
			$data['data'] = $this->db->query("SELECT tbl_regist.*, tbl_pasien.namapas, tbl_pasien.noidentitas, tbl_pasien.idpas, tbl_pasien.jkel, tbl_pasien.handphone, tbl_pasien.preposisi, tbl_pasien.namapanggilan, tbl_pasien.namakeluarga, tbl_pasien.tempatlahir, tbl_pasien.tgllahir, tbl_pasien.status, tbl_pasien.wn, tbl_pasien.agama, tbl_pasien.pendidikan, tbl_pasien.goldarah, tbl_pasien.hoby, tbl_pasien.pekerjaan, tbl_pasien.alamat, tbl_pasien.rt, tbl_pasien.rw, tbl_pasien.alamat2, tbl_pasien.handphone, tbl_pasien.propinsi, tbl_pasien.phone, tbl_pasien.kabupaten, tbl_pasien.email, tbl_pasien.kecamatan, tbl_pasien.fb, tbl_pasien.ig, tbl_pasien.twit, tbl_pasien.kelurahan, tbl_pasien.kodepos as kdpos
			from tbl_regist inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed
			where tbl_regist.id = '$id'")->row();
			
			$this->load->view('PendaftaranVRS/v_pendaftaranrs_edit', $data);
		} else
		{
			header('location:'.base_url());
		}			
	}
	// end edit rawat jalan

	// rawat inap add and regist
	function tambah_pasien_rawat_inap(){
		$cabang = $this->session->userdata('unit');
		$norm = urut_transaksi_igd('URUT_RS_IGD', 6);
		$noregori = $this->input->post('noreg');
		$normori = $this->input->post('lupnorm');
		$namapasienx = $this->input->post('lupnamapasien');
		$namapasien = preg_replace('/\s+/', ' ', $namapasienx);
		$namadepan = '';
		$namabelakang = '';
		$namakeluarga = $this->input->post('lupnamakeluarga');
		$namapanggilan = $this->input->post('lupnamapanggilan');
		$tempatlahir = $this->input->post('luptempatlahir');
		$tgllahir = $this->input->post('luptgllahir');
		$jeniskelamin = $this->input->post('lupjeniskelamin');
		$jkelhub = '';
		$status = $this->input->post('lupstatus');
		$noidentitas = $this->input->post('lupnoidentitas');
		$identitas = $this->input->post('lupidentitas');
		$preposisi = $this->input->post('luppreposition');
		$nocard = '';
		$pendidikan = $this->input->post('luppendidikan');
		$suku = $this->input->post('lupwarganegara');
		$agama = $this->input->post('lupagama');
		$pekerjaan = $this->input->post('luppekerjaan');
		$goldarah = $this->input->post('lupgoldarah');
		$hoby = $this->input->post('luphobby');
		$alamat1 = $this->input->post('lupalamat1');
		$alamat2 = $this->input->post('lupalamat2');
		$propinsi = $this->input->post('lupprovinsi');
		$kabupaten = $this->input->post('kabkota');
		$kecamatan = $this->input->post('lupkecamatan');
		$kelurahan = $this->input->post('lupkelurahan');
		$handphone = $this->input->post('luphp');
		$phone = $this->input->post('lupphone');
		$email = $this->input->post('lupemail');
		$twit = $this->input->post('luptwitter');
		$fb = $this->input->post('lupfb');
		$ig = $this->input->post('lupig');
		$orhub = '';
		$hubungan = '';
		$alamathub = '';
		$emailhub = '';
		$phonehub = '';
		$pekerjaanhub = '';
		$hphub = '';
		$lastnoreg = '';
		$ada = '';
		$blokir = '';
		$ketblokir = '';
		$alergi = '';
		$rt = $this->input->post('luprt');
		$rw = $this->input->post('luprw');
		$kodepos1 = $this->input->post('lupkodepos1');
		$wn = '';
		$iklinik = '';
		$cek = $this->db->query('select * from tbl_pasien where rekmed = "'.$normori.'"')->row_array();
		$cekpasien = $this->db->query('select * from tbl_pasien where namapas = "'.$namapasien.'" and tempatlahir = "'.$tempatlahir.'" and tgllahir like "%'.$tgllahir.'%"')->row_array();
		if($cek['rekmed']){
			$this->db->set('namapas', $namapasien);
			$this->db->set('idpas', $identitas);
			$this->db->set('noidentitas', $noidentitas);
			$this->db->set('namapanggilan', $namapanggilan);
			$this->db->set('namakeluarga', $namakeluarga);
			$this->db->set('tempatlahir', $tempatlahir);
			$this->db->set('tgllahir', $tgllahir);
			$this->db->set('jkel', $jeniskelamin);
			$this->db->set('status', $status);
			$this->db->set('suku', $suku);
			$this->db->set('wn', $suku);
			$this->db->set('agama', $agama);
			$this->db->set('pendidikan', $pendidikan);
			$this->db->set('goldarah', $goldarah);
			$this->db->set('hoby', $hoby);
			$this->db->set('pekerjaan', $pekerjaan);
			$this->db->set('alamat', $alamat1);
			$this->db->set('rt', $rt);
			$this->db->set('rw', $rw);
			$this->db->set('alamat2', $alamat2);
			$this->db->set('handphone', $handphone);
			$this->db->set('propinsi', $propinsi);
			$this->db->set('phone', $phone);
			$this->db->set('kabupaten', $kabupaten);
			$this->db->set('email', $email);
			$this->db->set('kecamatan', $kecamatan);
			$this->db->set('fb', $fb);
			$this->db->set('kelurahan', $kelurahan);
			$this->db->set('twit', $twit);
			$this->db->set('kodepos', $kodepos1);
			$this->db->set('ig', $ig);
			$this->db->set('koders', $cabang);
			$this->db->where('rekmed', $cek['rekmed']);
			$this->db->update('tbl_pasien');
			echo json_encode(['status' => 1]);
		} else if($cekpasien){
			$this->db->set('idpas', $identitas);
			$this->db->set('noidentitas', $noidentitas);
			$this->db->set('namapanggilan', $namapanggilan);
			$this->db->set('namakeluarga', $namakeluarga);
			$this->db->set('jkel', $jeniskelamin);
			$this->db->set('status', $status);
			$this->db->set('suku', $suku);
			$this->db->set('wn', $suku);
			$this->db->set('agama', $agama);
			$this->db->set('pendidikan', $pendidikan);
			$this->db->set('goldarah', $goldarah);
			$this->db->set('hoby', $hoby);
			$this->db->set('pekerjaan', $pekerjaan);
			$this->db->set('alamat', $alamat1);
			$this->db->set('rt', $rt);
			$this->db->set('rw', $rw);
			$this->db->set('alamat2', $alamat2);
			$this->db->set('handphone', $handphone);
			$this->db->set('propinsi', $propinsi);
			$this->db->set('phone', $phone);
			$this->db->set('kabupaten', $kabupaten);
			$this->db->set('email', $email);
			$this->db->set('kecamatan', $kecamatan);
			$this->db->set('fb', $fb);
			$this->db->set('kelurahan', $kelurahan);
			$this->db->set('twit', $twit);
			$this->db->set('kodepos', $kodepos1);
			$this->db->set('ig', $ig);
			$this->db->set('koders', $cabang);
			$this->db->where('namapas', $namapasien);
			$this->db->where('tempatlahir', $tempatlahir);
			$this->db->where('tgllahir', $tgllahir);
			$this->db->update('tbl_pasien');
			echo json_encode(['status' => 1]);
		} else {
			$datapasien = [
				'koders' => $cabang,
				'rekmed' => $norm,
				'namapas' => $namapasien,
				'namadepan' => $namadepan,
				'namabelakang' => $namabelakang,
				'preposisi' => $preposisi,
				'namakeluarga' => $namakeluarga,
				'namapanggilan' => $namapanggilan,
				'tempatlahir' => $tempatlahir,
				'tgllahir' => $tgllahir,
				'jkel' => $jeniskelamin,
				'status' => $status,
				'noidentitas' => $noidentitas,
				'idpas' => $identitas,
				'nocard' => $nocard,
				'pendidikan' => $pendidikan,
				'suku' => $suku,
				'agama' => $agama,
				'pekerjaan' => $pekerjaan,
				'goldarah' => $goldarah,
				'hoby' => $hoby,
				'alamat' => $alamat1,
				'alamat2' => $alamat2,
				'propinsi' => $propinsi,
				'kabupaten' => $kabupaten,
				'kecamatan' => $kecamatan,
				'kelurahan' => $kelurahan,
				'handphone' => $handphone,
				'phone' => $phone,
				'email' => $email,
				'twit' => $twit,
				'fb' => $fb,
				'ig' => $ig,
				'orhub' => $orhub,
				'alamathub' => $alamathub,
				'emailhub' => $emailhub,
				'phonehub' => $phonehub,
				'hphub' => $hphub,
				'lastnoreg' => $lastnoreg,
				'ada' => $ada,
				'blokir' => $blokir,
				'ketblokir' => $ketblokir,
				'alergi' => $alergi,
				'rt' => $rt,
				'rw' => $rw,
				'kodepos' => $kodepos1,
				'wn' => $suku,
			];
			$this->db->insert('tbl_pasien', $datapasien);
			echo json_encode(['status' => 0]);
		}
	}
	
	function tambah_pasien_register_rawat_inap(){
		$cabang = $this->session->userdata('unit');
		$poli = null;
		$mjkn_token = $this->input->post('booking');
		$dokter = $this->input->post('dokter');
		$noreg = urut_transaksi('REGISTRASI', 16);
		$ruang = $this->input->post('ruang');
		$kodekamar = $this->input->post('bed');
		$kelas = $this->input->post('kelas');
		$antrino = 0;
		$tanggal = $this->input->post('tanggal');
		$jam = $this->input->post('jam');
		$pengirim = $this->input->post('pengirim');
		$norm = $this->input->post('norm');
		$username = $this->session->userdata('username');
		$jenispasien = $this->input->post('jenispasien');
		$penjamin = $this->input->post('vpenjamin');
		$nocard = $this->input->post('nocard');
		$norujukan = $this->input->post('norujukan');
		$nosep = $this->input->post('nosep');
		$titipx = $this->input->post('titip');
		if($titipx == 'on'){
			$titip = 1;
			$ruangtitip = $this->input->post('rtitip');
			$kamartitip = $this->input->post('ruangtitip');
		} else {
			$titip = 0;
			$ruangtitip = null;
			$kamartitip = null;
		}
		$tujuan = 2;
		$baru = 1;
		$keluar = 0;
		// $tglkeluar;
		$jamkeluar = '';
		$shipt = 0;
		$batal = 0;
		$batalkarena = '';
		$diperiksa_perawat = 0;
		if($jenispasien == 'PAS1'){
			$cust = '';
			$bpjs = null;
			$rujukan = null;
			$sep = null;
		} else {
			$cust = $penjamin;
			$bpjs = $nocard;
			$rujukan = $norujukan;
			$sep = $nosep;
		}
		$cek = $this->db->query('select * from tbl_regist where rekmed = "'.$norm.'" and keluar = 0')->row_array();
		$cek2 = $this->db->get_where('tbl_ruangtransaksi', ['rekmed' => $norm])->row_array();
		if($cek){
			$this->db->set('username', $username);
			// $this->db->set('koders', $cabang);
			$this->db->set('mjkn_token', $mjkn_token);
			$this->db->set('kodokter', $dokter);
			$this->db->set('koderuang', $ruang);
			$this->db->set('tujuan', $tujuan);
			// $this->db->set('kodepos', $poli);
			$this->db->set('baru', $baru);
			$this->db->set('antrino', $antrino);
			$this->db->set('tglmasuk', $tanggal);
			$this->db->set('tglmasuk', $tanggal);
			$this->db->set('jam', $jam);
			$this->db->set('kelas', $kelas);
			$this->db->set('drpengirim', $pengirim);
			$this->db->set('jenispas', $jenispasien);
			$this->db->set('cust_id', $cust);
			$this->db->set('nobpjs', $bpjs);
			$this->db->set('norujukan', $rujukan);
			$this->db->set('nosep', $sep);
			$this->db->set('kodekamar', $kodekamar);
			$this->db->set('titip', $titip);
			$this->db->set('ruangtitip', $ruangtitip);
			$this->db->set('kamartitip', $kamartitip);
			$this->db->set('koders', $cabang);
			$this->db->set('noreg', $noreg);
			$this->db->where('rekmed', $cek['rekmed']);
			$this->db->update('tbl_regist');
			echo json_encode(['status' => 1]);
		} else if($norm == $cek2['rekmed']) {
			$this->db->set('koders', $cabang);
			$this->db->set('noreg', $noreg);
			$this->db->set('koderuang', $ruang);
			$this->db->set('kelas', $kelas);
			$this->db->set('tglmasuk', $tanggal);
			$this->db->set('jammasuk', $jam);
			$this->db->set('pindah', $titip);
			$this->db->set('kodokter', $dokter);
			$this->db->where('rekmed', $$cek2['rekmed']);
			$this->db->update('tbl_ruangtransaksi');
			echo json_encode(['status' => 1]);
		} else {
			$dataregist = [
				'username' => $username,
				'mjkn_token' => $mjkn_token,
				'rekmed' => $norm,
				'noreg' => $noreg,
				'kodokter' => $dokter,
				'koderuang' => $ruang,
				'tujuan' => $tujuan,
				'kodepos' => $poli,
				'baru' => $baru,
				'antrino' => $antrino,
				'tglmasuk' => $tanggal,
				'jam' => $jam,
				'kelas' => $kelas,
				'drpengirim' => $pengirim,
				'jenispas' => $jenispasien,
				'cust_id' => $cust,
				'nobpjs' => $bpjs,
				'norujukan' => $rujukan,
				'nosep' => $sep,
				'koders' => $cabang,
				'kodekamar' => $kodekamar,
				'titip' => $titip,
				'ruangtitip' => $ruangtitip,
				'kamartitip' => $kamartitip,
			];
			$this->db->insert('tbl_regist', $dataregist);
			$sql_tarif = $this->db->query('select tarif from tbl_ruang where koders = "'.$cabang.'" and koderuang = "'.$ruang.'"')->row_array();
			$dataruangtransaksi = [
				'koders' => $cabang,
				'noreg' => $noreg,
				'rekmed' => $norm,
				'koderuang' => $ruang,
				'kodekamar' => $kodekamar,
				'kelas' => $kelas,
				'tglmasuk' => $tanggal,
				'jammasuk' => $jam,
				// 'tglkeluar' => null,
				// 'jamkeluar' => null,
				'tarifhari' => $sql_tarif['tarif'],
				// 'tarifjam' => null,
				// 'tariftotal' => null,
				// 'lamajam' => null,
				// 'lamamenit' => null,
				// 'ruangkey' => null,
				'keluar' => 0,
				'pindah' => $titip,
				'kodokter' => $dokter,
				'askes' => 0,
				'jpk' => 0,
				'jpk2' => 0,
				'promosi' => 0,
				'diskon' => 0,
				'jasaperawat' => 0,
				'ruangtitip' => '',
			];
			$this->db->insert('tbl_ruangtransaksi', $dataruangtransaksi);
			echo json_encode(['status' => 0]);
		}
	}
	// end rawat inap add and regist

	public function send_wa(){		          
		$userid   = $this->session->userdata('inv_username');
		$param = $this->input->post('id');		 
		
		$data = $this->db->query("
		select tbl_regist.*, tbl_pasien.*, tbl_regist.kodepos as kodepost from tbl_regist 
		inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed
		where tbl_regist.id = '$param'")->row();
		
		// $this->genpdf($data->noreg);
		
		$pelayanan = data_master('tbl_namapos',array('kodepos' => $data->kodepost));
		$attched_file  = base_url()."uploads/regist/".$data->noreg.".PDF";
		$mobile = $data->handphone;				
		$txtwa   = "REGISTRASI"."\r\n".
				"No. Reg : ".$data->noreg."\r\n".
				"No. RM : ".$data->rekmed."\r\n".
				"Nama Pasien : ".$data->namapas."\r\n".
				"umur : ".hitung_umur($data->tgllahir)."\r\n".
				"Alamat : ".$data->alamat."\r\n".
				"Tanggal Masuk : ".date('d M Y',strtotime($data->tglmasuk))."\r\n".
				"Pelayanan : ".data_master('tbl_namapos',array('kodepos' => $data->kodepost))->namapost."\r\n".
				"Dokter : ".data_master('tbl_dokter',array('kodokter' => $data->kodokter))->nadokter."\r\n";
												
		$txtwa2   = $attched_file;
		
		$result= send_wa_txt($mobile,$txtwa);		 		 		 		
		$result= send_wa_txt($mobile,$txtwa2);		 		 		 		
		echo json_encode(array("status" => 1));
	}
	
	public function send_wa_selamat( $param ){		          
		 $userid   = $this->session->userdata('inv_username');
		 $unit= $this->session->userdata('unit');
		 $profile = data_master('tbl_namers', array('koders' => $unit));
		 
	     $data = $this->db->query("SELECT tbl_regist.*, tbl_pasien.*, tbl_regist.kodepos as kodepost from tbl_regist 
		 inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed
		 where tbl_regist.id = '$param'")->row();		 
		 $mobile = $data->handphone;	
		 $kodpos = $data->kodepost;	
		 $dpreposisi = data_master('tbl_setinghms', array('kodeset' => $data->preposisi));
		 if($dpreposisi){
		   $preposisi = $dpreposisi->keterangan;	  
		 } else {
		   $preposisi = 'Bpk/Ibu';	 
		 }
		 
         $namapasien = 	$preposisi.' '.$data->namapas;	 
		 $nomorhub = " WA: ".$profile->whatsapp."/Telp:".$profile->phone;
		 
		 $txtwa   = "Pendaftaran Pasien, ".$namapasien."\r\n".
		            " "."\r\n".
		            "Selamat Datang di Klinik Estetika dr, Affandi"."\r\n".
					"Cabang : *".$profile->koders."*\r\n".
					"Terima kasih telah mempercayakan perawatan *".$kodpos."* Anda di klinik kami"."\r\n".
					"Untuk mendapatkan informasi & pelayanan lebih lanjut dapat menghubungi ".$nomorhub."\r\n";
												 		 
         $result= send_wa_txt($mobile,$txtwa);
		 
		 $txtpm   = "Pendaftaran Pasien, ".$namapasien."<br>".
				" "."<br>".
				"Selamat Datang di Klinik Estetika dr, Affandi"."<br>".
				"Cabang : ".$profile->koders."<br>".
				"Terima kasih telah mempercayakan perawatan ".$kodpos." Anda di klinik kami"."<br>".
				"Untuk mendapatkan informasi & pelayanan lebih lanjut dapat menghubungi ".$nomorhub."<br>";
											  
		return $txtpm;
		 
	}

	public function get_dokter_rj(){
		$poli = $this->input->post('poliklinik1');
		$cabang = $this->session->userdata('unit');
		// $data = $this->db->query(
		// 'SELECT DISTINCT d.kodokter, d.nadokter 
		// from tbl_drpoli p join tbl_dokter d on p.kodokter=d.kodokter where nadokter="-" and p.kopoli = "'.$poli.'" and koders = "'.$this->session->userdata('unit').'" 
		// UNION ALL (SELECT DISTINCT d.kodokter, d.nadokter 
		// from tbl_drpoli p join tbl_dokter d on p.kodokter=d.kodokter where nadokter<>"-" AND p.kopoli = "'.$poli.'" and koders = "'.$this->session->userdata('unit').'"
		// order by nadokter)')->result();

		$data = $this->db->query(
			"(SELECT*from dokter where koders='$cabang' and kopoli='$poli' and nadokter='-' and koders='$cabang' limit 1) union all
			SELECT*from dokter where koders='$cabang' and kopoli='$poli' and nadokter <> '-' and koders='$cabang' and status='on'
			order by nadokter")->result();

		
		// $query = $this->db->query("SELECT DISTINCT d.kodokter, dr.nadokter FROM tbl_drpoli p JOIN tbl_doktercabang d ON p.kodokter=d.kodokter JOIN tbl_dokter dr ON d.kodokter=dr.kodokter WHERE nadokter='-' AND p.kopoli = '$poli' AND d.koders = '$cabang' UNION ALL (SELECT DISTINCT d.kodokter, dr.nadokter FROM tbl_drpoli p JOIN tbl_doktercabang d ON p.kodokter=d.kodokter JOIN tbl_dokter dr ON d.kodokter=dr.kodokter WHERE nadokter<>'-' AND p.kopoli = '$poli' AND d.koders = '$cabang' ORDER BY nadokter)")->result();
		echo json_encode($data, JSON_PRETTY_PRINT);
  }

	public function get_dokter_ri(){
		$data = $this->db->query('select d.kodokter, d.nadokter from tbl_drpoli p join tbl_dokter d on p.kodokter=d.kodokter where koders = "'.$this->session->userdata('unit').'"')->result();
		echo json_encode($data, JSON_PRETTY_PRINT);
  }

	public function dokter_rj(){
		$str = $this->input->post('searchTerm');
		echo json_encode($this->M_pendaftaranVRS->get_dokter_rj($str));
	}

	// RI
	public function ri($param){
		$dat   = explode("~",$param);
		if($dat[0]==1){
			$bulan = $this->M_global->_periodebulan();
			$tahun = $this->M_global->_periodetahun();
			$list = $this->M_pendaftaranVRS->get_datatables_ri( 1, $bulan, $tahun );
		} else {
			$bulan  = date('Y-m-d',strtotime($dat[1]));
			$tahun  = date('Y-m-d',strtotime($dat[2]));
			$list = $this->M_pendaftaranVRS->get_datatables_ri( 2, $bulan, $tahun );
		}
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit) {
		$data_pasien = $this->M_global->_datapasien($unit->rekmed);
		$namapas = $data_pasien->namapas;
		$hp      = $data_pasien->handphone;
		$email   = $data_pasien->email;
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $unit->koders;
			$row[] = $unit->username;
			$row[] = $unit->antrino;
			$row[] = $unit->noreg;
			$row[] = $unit->rekmed;			
			$row[] = date('d-m-Y',strtotime($unit->tglmasuk));
			$row[] = $unit->namapas;
			if($unit->tujuan == 1){
				$tujuan = 'Rawat Jalan';
			} else {
				$tujuan = 'Rawat Inap';
			}
			if ($unit->keluar == 0) {
                	$status = '<span class="label label-sm label-warning">Register</span>';
			} elseif ($unit->keluar == 1) {
				$status = '<span class="label label-sm label-success">Closed</span> '; 
			}
			
			if ($unit->batal == 1) {
                	$status = '<span class="label label-sm label-danger">Batal</span>';
			}
			$row[] = $tujuan;			
			$row[] = $unit->nadokter;
			$row[] = $unit->cust_nama;
			$nocard = $this->db->query('select nobpjs from tbl_regist where rekmed = "'.$unit->rekmed.'" and noreg = "'.$unit->noreg.'"')->row_array();
			$row[] = $nocard['nobpjs'];
			$row[] = $status;
			if($unit->keluar == 0 && $unit->batal == 0){			
			  $row[] = 
			     '<a class="btn btn-sm btn-primary" href="'.base_url("pendaftaranVRS/edit_ri/".$unit->id."").'" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a> 				  
				<a class="btn btn-sm btn-warning" href="'.base_url("pendaftaranVRS/cetak_ri2/?noreg=".$unit->noreg."").'" target="_blank" title="Cetak" ><i class="glyphicon glyphicon-print"></i></a>				   
				<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Kirim Email" onclick="send_email('."'".$unit->id."'".",'".$email."'".')"><i class="glyphicon glyphicon-envelope"></i> </a>
				<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Kirim Whatsapp" onclick="send_wa('."'".$unit->id."'".",'".$hp."'".')"><i class="fa fa-whatsapp"></i> </a>
				<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Batalkan" onclick="Batalkan('."'".$unit->id."'".",'ri'".')"><i class="glyphicon glyphicon-remove"></i> </a>';
			} else {
			  $row[] = '';	
			}
			// $row[] = '';	
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->M_pendaftaranVRS->count_all_ri( $dat[0], $bulan, $tahun ),
			"recordsFiltered" => $this->M_pendaftaranVRS->count_filtered_ri( $dat[0], $bulan, $tahun ),
			"data" => $data,
		);
		//data to json format
		echo json_encode($output);
	}

	// cetak rawat inap 2
	public function cetak_ri2()
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');		
		if(!empty($cek))
		{				  
            $noreg = $this->input->get('noreg');	
            $profile = $this->M_global->_LoadProfileLap();	
		    $unit= $this->session->userdata('unit');	 
		    $nama_usaha=$profile->nama_usaha;
			$alamat1  = $profile->alamat1;
			$alamat2  = $profile->telpon;
		  
		    $qregis = "select tbl_regist.*, tbl_pasien.*, tbl_regist.kodepos as kodepost from tbl_regist 
			inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed
			where noreg = '$noreg'";
			 
			$regist = $this->db->query($qregis)->row();
			
			$pdf=new simkeu_nota1();
			$pdf->setID($nama_usaha,$alamat1,$alamat2);
			$pdf->setjudul(' ');
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
			//$judul=array('Kwitansi ','','Faktur Penjualan');
			$fc     = array('0','0','0');
			$hc     = array('20','20','20');
			//$pdf->FancyRow2(10,$judul, $fc,  $border, $align, $style, $size, $max);
			//$pdf->ln(1);
			$pdf->setfont('Arial','B',10);
			$pdf->SetWidths(array(30,5,50,10,25,5,55));
			$border = array('','','','','','','');
			$fc     = array('0','0','0','0','0','0','0');
			$pdf->SetFillColor(230,230,230);			
			
			
			$pdf->setfont('Arial','',8);
			$pdf->FancyRow(array('','','No. RM :','','','',''), $fc, $border);
			$pdf->setfont('Arial','B',21);
			$pdf->FancyRow(array('','',$regist->rekmed,'','','',''), $fc, $border);
			$pdf->setfont('Arial','',8);
			$pdf->FancyRow(array('No. Reg',':',$regist->noreg,'','','',''), $fc, $border);
			$pdf->FancyRow(array('Nama Pasien',':',$regist->namapas,'','','',''), $fc, $border);
			$pdf->FancyRow(array('Tempat/Tgl. Lahir',':',$regist->tempatlahir.' / '.tanggal($regist->tgllahir),'','','',''), $fc, $border);
			$pdf->FancyRow(array('Umur',':',hitung_umur($regist->tgllahir),'','','',''), $fc, $border);
			$pdf->FancyRow(array('Alamat',':',$regist->alamat,'','','',''), $fc, $border);
			$pdf->FancyRow(array('Tgl. Masuk',':',tanggal($regist->tglmasuk).' Jam '.$regist->jam,'','','',''), $fc, $border);
			$pdf->FancyRow(array('Pasien',':',($regist->baru==1?'Baru':'Lama'),'','','',''), $fc, $border);
			$pdf->FancyRow(array('Pelayanan',':',data_master('tbl_namapos',array('kodepos' => $regist->kodepost))->namapost,'','','',''), $fc, $border);
			$pdf->FancyRow(array('Dokter',':',data_master('tbl_dokter',array('kodokter' => $regist->kodokter))->nadokter,'','','',''), $fc, $border);
			
			
			$pdf->ln(2);
			
			
            $pdf->setTitle($noreg); 
			$pdf->AliasNbPages();
			$pdf->output('./uploads/regist/'.$noreg.'.PDF','F');
			$pdf->output($noreg.'.PDF','I');			
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	// end cetak rawat inap 2

	public function entri_ri(){
		$cek = $this->session->userdata('username');				
		$cabang = $this->session->userdata('unit');				
		if(!empty($cek))
		{
			$data = [
				'propinsi' => $this->db->get('tbl_propinsi')->result(),
				'namapos' => $this->db->get('tbl_namapos')->result(),
				'dokter' => $this->db->get_where('tbl_dokter', ['koders' => $cabang])->result(),
				'ruang' => $this->db->query('select k.*,r.* from tbl_ruang r join tbl_kamar k on r.koderuang=k.koderuang')->result(),
				'daftar_ruang_inap' => $this->db->query('select * from daftar_ruang_inap where koders = "'.$cabang.'"')->result(),
			];
			$this->load->view('PendaftaranVRS/v_add_ri', $data);
		} else
		{
			header('location:'.base_url());
		}			
	}

	function gantiruang(){
		$ruang = $this->input->get('ruang');
		$sql = $this->db->query('select r.koderuang, k.kodekamar, k.namakamar, r.kelas, k.penuh from tbl_ruang r join tbl_kamar k on r.koderuang=k.koderuang where r.koderuang ="'.$ruang.'"')->row_array();
		$data = [
			'koderuang' => $sql['koderuang'],
			'kodekamar' => $sql['kodekamar'],
			'namakamar' => $sql['namakamar'],
			'kelas' => $sql['kelas'],
			'penuh' => $sql['penuh'],
		];
		echo json_encode($data, JSON_PRETTY_PRINT);
	}

	public function cekruang($param)
	{
		if($param=='PGIGI'){
			$query = $this->db->query("SELECT * FROM tbl_ruangpoli where koderuang in('1','2')");
		}else if($param=='PUGD'){
			// $query = $this->db->query("SELECT * FROM tbl_ruangpoli where koderuang in('6','7','8')");
			$query = $this->db->query("SELECT * FROM tbl_ruangpoli where koderuang in('6')");
		}else{
			$query = $this->db->query("SELECT * FROM tbl_ruangpoli where koderuang in('1','2','3','4','5')");
		}

		if ($query->num_rows() == 0) {
			echo json_encode(array("status" => 0));
		} else {
			header("content-type:application/json");
			echo json_encode($query->result());
		}
	}
}