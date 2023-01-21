<?php

function tglsystem(){
	$tanggal = date('Y-m-d H:i:s');
	return $tanggal;
}

function user_login(){
	$CI =& get_instance();
	return $CI->session->userdata('username');	
}

function hitung_umur($tanggal_lahir){
	$birthDate = new DateTime($tanggal_lahir);
	$today = new DateTime("today");
	if ($birthDate > $today) { 
		exit("0 tahun 0 bulan 0 hari");
	}
	$y = $today->diff($birthDate)->y;
	$m = $today->diff($birthDate)->m;
	$d = $today->diff($birthDate)->d;
	return $y." tahun ".$m." bulan ".$d." hari";
}

function pasien_rekmed_baru($nama){	
	$CI =& get_instance();	
	$kode_cabang = $CI->session->userdata('unit');	  
	$namadepan = substr($nama, 0,1);
	//   $dataurut = $CI->db->query("select urut as nomor from tbl_urutmr where mrkey='$namadepan'")->row();
	$dataurut = $CI->db->query("select urut as nomor from tbl_urutmr where koders = '$kode_cabang' AND mrkey='$namadepan'")->row();

	if($dataurut){
		$nourut = $dataurut->nomor+1;
		//   $CI->db->query("update tbl_urutmr set urut=urut+1 where mrkey='$namadepan'");
		$CI->db->query("update tbl_urutmr set urut=urut+1 where koders='$kode_cabang' AND mrkey='$namadepan'");
	} else {
		$nourut = 1;	
		//   $CI->db->query("insert into tbl_urutmr(mrkey, urut) values('$namadepan', 1)");
		$CI->db->query("insert into tbl_urutmr(koders, mrkey, urut) values('$kode_cabang','$namadepan', 1)");
	}
				
	$rekmed = $kode_cabang.$namadepan.str_pad( $nourut, 6, '0', STR_PAD_LEFT );	
	return $rekmed;
}  

function stok_farmasi($barang, $gudang){	
	$CI =& get_instance();	
	$kode_cabang = $CI->session->userdata('unit');	  
	$datastok = $CI->db->query("select * from tbl_barangstock where koders='$kode_cabang' and gudang='$gudang' and kodebarang='$barang'")->row();
	if($datastok){
		$stok = $datastok->saldoakhir;	  
	} else {
		$stok = 0;	  
	}
				
	return $stok;
} 

function stok_logistik($barang, $gudang){	
	$CI =& get_instance();	
	$kode_cabang = $CI->session->userdata('unit');	  
	$datastok = $CI->db->query("select * from tbl_apostocklog where koders='$kode_cabang' and gudang='$gudang' and kodebarang='$barang'")->row();
	if($datastok){
		$stok = $datastok->saldoakhir;	  
	} else {
		$stok = 0;	  
	}
				
	return $stok;
} 


function history_log($shipt='' ,$modul='' ,$aksi='' ,$trno='' ,$ket='')
{
	/* CONTOH PENGGUNAAN NYA */ 
	/* history_log(0 ,'MASTER_DOKTER' ,'EDIT' ,$kodokter ,'-'); */

	$CI              = & get_instance();
	$username        = $CI->session->userdata('username');
	$nama_lengkap    = $CI->session->userdata('nama_lengkap');
	$tgl             = date('Y-m-d');
	$jam             = date('H:i:s');
	$masuk           = $CI->db->query("INSERT into tbl_logindata(username ,shipt ,tanggal ,modul ,aksi ,trno ,ket ,jam ,namauser) values('$username' ,'$shipt' ,'$tgl' ,'$modul' ,'$aksi' ,'$trno' ,'$ket' ,'$jam' ,'$nama_lengkap')");

}

/* Rizki */
/* Urut : Mengambil Last No urut Tapi Ketika Reload Akan Terinsert */
function urut_transaksi( $trkode, $lebar){
	$CI =& get_instance();
	$kode_cabang = $CI->session->userdata('unit');
	$CI->db->query("UPDATE tbl_urutrs set nourut=nourut+1 where kode_urut='$trkode' and koders='$kode_cabang' ");
	$data_urut = $CI->db->query("SELECT * from tbl_urutrs where kode_urut='$trkode' and koders='$kode_cabang'")->row();
	$nomor_urut = $data_urut->nourut;
	$param1 = trim($data_urut->param1);
	$param2 = trim($data_urut->param2);
	$param3 = trim($data_urut->param3);
		
	if($param1=='TH'){
		$param1 = date('Y'); 
	} 

	if($param2=='BL'){
		$param2 = date('m'); 
	} elseif($param2=='TH'){	  
		$param2 = date('Y');   
	} 

	if($param3=='BL'){
		$param3 = date('m'); 
	} 


	$kode_transaksi1 = $kode_cabang.trim($param1).trim($param2).trim($param3);
	$kode_transaksi2 = $kode_transaksi1.str_pad( $nomor_urut, $lebar-strlen($kode_transaksi1), '0', STR_PAD_LEFT );
	return $kode_transaksi2;	
}

function urut_transaksi_rs( $trkode,$status, $lebar){
	$CI =& get_instance();
	$kode_cabang = $CI->session->userdata('unit');
	$CI->db->query("UPDATE tbl_urutrs set nourut=nourut+1 where kode_urut='$trkode' and koders='$kode_cabang' ");
	$data_urut = $CI->db->query("SELECT * from tbl_urutrs where kode_urut='$trkode' and koders='$kode_cabang'")->row();
	$nomor_urut = $data_urut->nourut;
	$param1 = trim($data_urut->param1);
	$param2 = trim($data_urut->param2);
	$param3 = trim($data_urut->param3);
	
	if($param1=='TH'){
	$param1 = date('Y'); 
	} 
	
	if($param2=='BL'){
	$param2 = date('m'); 
	} elseif($param2=='TH'){	  
	$param2 = date('Y');   
	} 
	
	if($param3=='BL'){
	$param3 = date('m'); 
	} 
	
	
	$kode_transaksi1 = $status.trim($param1).trim($param2).trim($param3);
	$kode_transaksi2 = $kode_transaksi1.str_pad( $nomor_urut, $lebar-strlen($kode_transaksi1), '0', STR_PAD_LEFT );
	return $kode_transaksi2;	
}

function urut_transaksi_igd($trkode, $lebar){
	$CI =& get_instance();
	$CI->db->query("UPDATE tbl_urutrs set nourut=nourut+1 where kode_urut='$trkode'");
	$data_urut = $CI->db->query("SELECT * from tbl_urutrs where kode_urut='$trkode'")->row();
	$nomor_urut = $data_urut->nourut;
	$kode_transaksi = str_pad( $nomor_urut, $lebar, '0', STR_PAD_LEFT );
	return $kode_transaksi;	
}

function urut_faktur_pajak($trkode, $lebar){
$CI =& get_instance();
$CI->db->query("UPDATE tbl_urutrs set nourut=nourut+1 where kode_urut='$trkode'");
$data_urut = $CI->db->query("SELECT * from tbl_urutrs where kode_urut='$trkode'")->row();
$nomor_urut = $data_urut->nourut;
$date = date("Y");
$urut = trim($date);
$kode_transaksi = $urut.str_pad( $nomor_urut, $lebar, '0', STR_PAD_LEFT );
return $kode_transaksi;	
}

function urut_tarif($trkode, $lebar){
	$CI =& get_instance();
	$CI->db->query("UPDATE tbl_urutrs set nourut=nourut+1 where kode_urut='$trkode'");
	$data_urut = $CI->db->query("SELECT * from tbl_urutrs where kode_urut='$trkode'")->row();
	$nomor_urut = $data_urut->nourut;
	$param1 = trim($data_urut->param1);
	$kode_transaksi = $param1.str_pad( $nomor_urut, $lebar-strlen($param1), '0', STR_PAD_LEFT );
	return $kode_transaksi;	
}

/* Temp Urut : Mengambil Last No urut Lalu + 1 No urut Tanpa Otomatis Insert Ketika Reload */
function temp_urut_transaksi($curutkode, $ckoders, $clength){
	$CI           = & get_instance();
	$data_urut    = $CI->db->query("SELECT * from tbl_urutrs where kode_urut='$curutkode' and koders='$ckoders'")->row();
	$nomor_urut   = $data_urut->nourut + 1;
	$param1       = trim($data_urut->param1);
	$param2       = trim($data_urut->param2);
	$param3       = trim($data_urut->param3);

	if($param1=='TH'){
		$param1 = date('Y'); 
	} 

	if($param2=='BL'){
		$param2 = date('m'); 
	} elseif($param2=='TH'){	  
		$param2 = date('Y');   
	} 

	if($param3=='BL'){
		$param3 = date('m'); 
	} 

	$kode_transaksi1 = $ckoders.trim($param1).trim($param2).trim($param3);
	$kode_transaksi2 = $kode_transaksi1.str_pad( $nomor_urut, $clength-strlen($kode_transaksi1), '0', STR_PAD_LEFT );
	return $kode_transaksi2;
}

/* Recent Urut : Mengambil Recent No urut Tanpa Otomatis Insert Ketika Reload */
function recent_urut_transaksi($curutkode, $ckoders, $clength){
	$CI =& get_instance();
	$data_urut = $CI->db->query("SELECT * from tbl_urutrs where kode_urut='$curutkode' and koders='$ckoders'")->row();
	$nomor_urut = $data_urut->nourut;
	$param1 = trim($data_urut->param1);
	$param2 = trim($data_urut->param2);
	$param3 = trim($data_urut->param3);

	if($param1=='TH'){
		$param1 = date('Y'); 
	} 

	if($param2=='BL'){
		$param2 = date('m'); 
	} elseif($param2=='TH'){	  
		$param2 = date('Y');   
	} 

	if($param3=='BL'){
		$param3 = date('m'); 
	} 


	$kode_transaksi1 = $ckoders.trim($param1).trim($param2).trim($param3);
	$kode_transaksi2 = $kode_transaksi1.str_pad( $nomor_urut, $clength-strlen($kode_transaksi1), '0', STR_PAD_LEFT );
	return $kode_transaksi2;
}

function _rekamjurnal_header($cabang, $nobukti, $tanggal, $ketglobal, $userid, $tutup,$source){
	$CI =& get_instance();
	$CI->db->query("insert into tbl_jurnalh(koders, nobukti, tgljurnal, keterangan, user_name, tutup, sourceid)
	values('$cabang','$nobukti','$tanggal', '$ketglobal','$userid','$tutup','$source')");
}

function _rekamjurnal_detil($cabang, $nobukti, $akun, $debet,$kredit, $ket, $dept, $noreg){
	$CI =& get_instance();
	$CI->db->query("insert into tbl_jurnald(koders, nobukti, accountno, debet, credit, dketerangan, depid,noreg)
	values('$cabang','$nobukti','$akun', '$debet','$kredit','$ket','$dept','$noreg')");
}

function data_master( $tabel, $where){
	$CI =& get_instance();
	return $CI->db->get_where($tabel, $where)->row();
}

function tabel_master( $tabel, $where){
	$CI =& get_instance();
	return $CI->db->get_where($tabel, $where)->result();
}

function setinghms( $kode ){
	$CI =& get_instance();
	return $CI->db->query("SELECT*FROM tbl_setinghms WHERE lset='$kode' ORDER BY keterangan")->result();
}

function angka_rp( $rp, $digit ){
	if($rp == 0 || $rp == null){
		return;
	} else {
		return number_format($rp, $digit, '.',',');	
	}	
}

function tanggal( $tgl ){
	if($tgl=="" || $tgl=="1900-01-01 00:00:00"){
		return "";  
	}	else {
		return date('d-m-Y',strtotime($tgl));	
	}
}


function konversi ($x) {
	$abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	if ($x < 12)
	return " " . $abil[$x] ;
	elseif ($x < 20)
		return konversi($x - 10) . " belas";
	elseif ($x < 100)
		return konversi($x / 10) . " puluh" . konversi(fmod($x,10));
	elseif ($x < 200)
		return " seratus" . konversi($x - 100);
	elseif ($x < 1000)
		return konversi($x / 100) . " ratus" . konversi(fmod($x,100));
	elseif ($x < 2000)
		return " seribu" . konversi($x - 1000);
	elseif ($x < 1000000)
		return konversi($x / 1000) . " ribu" . konversi(fmod($x,1000));
	elseif ($x < 1000000000)
		return konversi($x / 1000000) . " juta" . konversi(fmod($x,1000000));
	elseif ($x < 1000000000000)
		return konversi($x / 1000000000) . " milyar" . konversi(fmod($x,1000000000));
	elseif ($x < 1000000000000000)
		return konversi($x / 1000000000000) . " trilyun" . konversi(fmod($x,1000000000000));
}

function tkoma($x){

	$x = stristr($x,'.');
	if ($x>0)
	{

	$string = array("nol", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan",   "sembilan","sepuluh", "sebelas");
	$temp = "";
	$pjg  = strlen($x);
	$pos = 1;

	while ($pos<$pjg)
	{
		$char = substr($x, $pos, 1);
		$pos++;
		$temp .= " ".$string[$char];
	}

	return $temp;
	} else
	{
	return "";
	}
}

function terbilang($x){
	$x = abs($x);	 
	if($x<0){
	$hasil = "minus ".trim(konversi($x));
	}else{
	$poin = trim(tkoma($x));
	$hasil = trim(konversi($x));
	}

	if($poin){
	$hasil = $hasil." koma ".$poin;
	}else{
	$hasil = $hasil;
	}
	return '# '. $hasil.' Rupiah #';
}

	
function send_wa_txt($mobile,$message){
	$CI =& get_instance();	
	$kode_cabang = $CI->session->userdata('unit');
	$q1=$CI->db->query("select * from tbl_namers where koders = '$kode_cabang'");
	if($q1->num_rows()>0){
		$host  = $q1->row()->wahost.'/api/send-message';
		$token = $q1->row()->watoken;
		$curl = curl_init();
		
		$data = [
			'phone'    => $mobile,
			'message'  => $message,
			'secret'   => false, // or true
			'priority' => false, // or true
		];

		curl_setopt($curl, CURLOPT_HTTPHEADER,
			array(
				"Authorization: $token",
			)
		);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($curl, CURLOPT_URL, $host);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		$result = curl_exec($curl);
		curl_close($curl);								
		return 'success';
	}
	else{
		return "API Not Available";
	}
}

function sequent_voucher($code){
	$code_alpha = substr($code, 0, 7);
	$code_num = substr($code, 7);

	$new_code_num = $code_num + 1;

	switch(strlen($new_code_num)){
		case 1 : $result_code_num = "0000".$new_code_num; break;
		case 2 : $result_code_num = "000".$new_code_num; break;
		case 3 : $result_code_num = "00".$new_code_num; break;
		case 4 : $result_code_num = "0".$new_code_num; break;
		case 5 : $result_code_num = $new_code_num; break;
	}

	$final_code = $code_alpha.$result_code_num;

	return $final_code;
}

function page_permission($password){
	$CI =& get_instance();
	$allowed_level	= array(1,2);
	$user_level		= $CI->session->userdata("user_level");
	$username		= $CI->session->userdata("uidlogin");
	$pass_enc		= md5($password);

	$query_user	= $CI->db->query("SELECT * FROM userlogin WHERE uidlogin = '$username' AND pwdlogin = '$pass_enc'")->row();

	if($query_user){
		if(in_array($user_level, $allowed_level) === true){
			setcookie("page_permission", "set", time()+86400, "/");
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

/**
 * Fungsi untuk mengkonversi tanggal
 * 
 * @param string $date
 * @return string 
 */
function local_date (string $date, string $format): string{
	$fmt = datefmt_create(
		'id_ID',
		IntlDateFormatter::FULL,
		IntlDateFormatter::FULL,
		'Asia/Jakarta',
		IntlDateFormatter::GREGORIAN,
		$format ?? 'dd MMMM yyyy'
	);

	return datefmt_format($fmt, strtotime($date));
}

/* UNIQUE FILE */
function unique_file($path, $filename) {
	$file_parts = explode(".", $filename);
	$ext = array_pop($file_parts);
	$name = implode(".", $file_parts);

	$i = 1;
	while (file_exists($path . $filename)) {
		$filename = $name . '-' . ($i++) . '.' . $ext;
	}
	return $filename;
}

function sumTime($time1, $time2) {

    $time1Exp = explode(':', $time1);
    $time2Exp = explode(':', $time2);
    $timeResult = array();
    $extraMinutes = $extraSeconds = 0;

    //sum milliseconds
    $timeResult[2] = $time1Exp[2] + $time2Exp[2];

    if($timeResult[2] >= 100) {
        $extraSeconds = floor($timeResult[2] / 100);
        $timeResult[2] -= $extraSeconds * 100;
    }

    $timeResult[1] = $time1Exp[1] + $time2Exp[1] + $extraSeconds;

    if($timeResult[1] >= 60) {
        $extraMinutes = floor($timeResult[1] / 60);
        $timeResult[1] -= $extraMinutes * 100;
    }

    $timeResult[0] = $time1Exp[0] + $time2Exp[0] + $extraMinutes;

    return implode(':', $timeResult);


}

/**
 * Method for finding PPK code from BPJS
 * 
 * @param string $koders
 * @return string
 */
function get_kdppk (string $koders): string {
    $CI = &get_instance();

    $result = $CI->db->select("koders")->where("kode_rs", $koders)->get("tbl_bpjsset")->row();

    if (isset($result->koders)) {
        return $result->koders;
    }

    return "";
}

/**
 * Function for reversing date string.
 * 
 * @param string $date
 * @return string
 */
function parse_local_date (string $date): string {
	$arrayDate 	= explode("-", $date);

	return implode("-", array_reverse($arrayDate));
}

/**
 * Method for checking srting is valid JSON or not
 * 
 * @param string $string
 * @return bool
 */
function isJson(string $string): bool {
	json_decode($string);
	return json_last_error() === JSON_ERROR_NONE;
 }

?>