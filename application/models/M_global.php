<?php if (!defined('BASEPATH')) exit('No direct script access allowed');



class M_global extends CI_Model
{

	function __construct()

	{
		parent::__construct();
		date_default_timezone_set("Asia/Jakarta");
	}

	function getakunpendapatan($str)
	{
		$query = $this->db->query("SELECT accountno as id, concat(accountno, ' - ', acname) as text from tbl_accounting where acname like '%$str%' order by id");

		return $query->result();
	}

	function getcabangg($str)
	{
		$query = $this->db->query("SELECT koders as id, concat(koders) as text from tbl_namers order by koders");

		return $query->result();
	}

	function gettarif($str)
	{
		$query = $this->db->query("SELECT cust_id as id, concat(cust_id) as text from tbl_penjamin order by cust_id");

		return $query->result();
	}


	public function cek_menu($level, $modul)

	{

		$query = $this->db->get_where('ms_modul_grupd', array('nomor_grup' => $level, 'nomor_modul' => $modul));

		return $query->num_rows();
	}


	public function cek_menu_akses($level, $modul)
	{

		$query = $this->db->get_where('ms_modul_grupd', array('nomor_grup' => $level, 'nomor_modul' => $modul));

		return $query->row();
	}

	function close_app()
	{
		$cabang   = $this->session->userdata('unit');
		$tgl      = date('Y-m-d');
		// $tgl      = '2022-12-25';
		$sql      = $this->db->query("SELECT status from ms_close_app WHERE koders = '$cabang' and statustgl='$tgl' ")->row();
<<<<<<< HEAD
		if ($sql) {
			return $sql->status;
		} else {
			return 0;
		}
	}
=======
		if($sql){
			return $sql->status;
		}else{
			return 0;
		}
    }
	
>>>>>>> development

	public function _periodebulan2()

	{

		//$query = $this->db->get('ms_identity');

		//$row = $query->row();

		//return $row->periode_bulan;				

		$bulan = date('n');

		$nbln = array('', 'Januari', 'Pebruari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember');

		return  $nbln[$bulan];
	}

	public function _periodebulan()

	{

		//$query = $this->db->get('ms_identity');

		//$row = $query->row();

		//return $row->periode_bulan;				

		return date('n');
	}

	public function queryKartuStock()
	{
		$mutasi =
			"SELECT * from 
			    (select 
				a.terima_date as tanggal,
				a.koders,
				a.terima_no as nomor,
				a.gudang,
				b.kodebarang,
				b.qty_terima as terima,
				0 keluar,
				b.qty_terima as qty,
				b.price as harga,
				tbl_vendor.vendor_name as rekanan,
				'PEMBELIAN' as keterangan
				from tbl_apohterimalog AS a
				inner join tbl_apodterimalog AS b on a.terima_no=b.terima_no
				inner join tbl_vendor on a.vendor_id=tbl_vendor.vendor_id
				
				union all
				
				select 
				a.movedate as tanggal,
				a.koders,
				a.moveno as nomor,
				a.ke as gudang,
				b.kodebarang,
				b.qtymove as terima,
				0 as keluar,
				b.qtymove as qty,
				b.harga as harga,
				a.mohonno as rekanan,
				'MUTASI' as keterangan
				from tbl_apohmovelog AS a 
				inner join tbl_apodmovelog AS b on a.moveno=b.moveno

				union all
				
				select 
				tbl_apohmovelog.movedate as tanggal,
				tbl_apohmovelog.koders,
				tbl_apohmovelog.moveno as nomor,
				tbl_apohmovelog.dari as gudang,
				tbl_apodmovelog.kodebarang,
				0 as terima,
				tbl_apodmovelog.qtymove as keluar,				
				tbl_apodmovelog.qtymove as qty,
				tbl_apodmovelog.harga as harga,
				tbl_apohmovelog.mohonno as rekanan,
				'MUTASI' as keterangan
				from tbl_apohmovelog inner join
				tbl_apodmovelog on tbl_apohmovelog.moveno=tbl_apodmovelog.moveno
				
				union all
				
				SELECT
				a.pakaidate AS tanggal,
				a.koders,
				a.pakaino AS nomor,
				a.username AS gudang,
				b.kodebarang,
				0 AS terima,
				b.qty AS keluar,
				b.qty AS qty,
				b.harga,
				'-' AS rekanan,
				'PEMAKAIAN' AS keterangan
				FROM tbl_pakaihlog AS a
				INNER JOIN tbl_pakaidlog AS b
				ON a.pakaino = b.pakaino
				) mutasi
			
				order by tanggal
				
				";
		$nourut = 1;
		$lap = $this->db->query($mutasi)->result();
	}



	public function _hutangjatuhtempo()

	{

		/*

        $query = $this->db->get('ms_identity');

        $row = $query->row();

        return $row->periode_bulan;				

		*/

		return 500000;
	}



	public function _LoadProfile()

	{

		$query = $this->db->get('ms_identity')->result();

		return $query;
	}



	public function _LoadProfileLap()

	{

		$query = $this->db->get('ms_identity')->row();

		return $query;
	}



	public function _periodetahun()

	{

		//$query = $this->db->get('ms_identity');

		//$row = $query->row();

		//return $row->periode_tahun;		

		return date('Y');
	}



	public function _statusrubahharga()

	{

		$query = $this->db->get('ms_identity');

		$row = $query->row();

		return $row->rubahhrg;
	}



	public function _statusrubahhargam()

	{

		$query = $this->db->get('ms_identity');

		$row = $query->row();

		return $row->rubahhrgm;
	}



	public function _namabulan($bln)

	{

		$nbln = array('', 'JANUARI', 'PEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI', 'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOPEMBER', 'DESEMBER');

		return $nbln[$bln];
	}



	public function _namaunit($kode)

	{

		$query = $this->db->get_where('tbl_namers', array('koders' => $kode));

		$row = $query->row();

		return $row->namars;
	}



	public function _namabarang($kode)

	{

		$query = $this->db->get_where('inv_barang', array('kodeitem' => $kode));

		$row = $query->row();

		if ($row) {

			return $row->namabarang;
		} else {

			return '';
		}
	}



	public function _datapasien($kode)

	{

		//$query = $this->db->get_where( 'tbl_pasien', array( 'rekmed' => $kode ) );

		$query = $this->db->query(

			"SELECT tbl_namers.namars, tbl_pasien.*, tbl_propinsi.namaprop, agama.keterangan as namaagama,

		pendidikan.keterangan as namapendidikan,pekerjaan.keterangan as namapekerjaan,

		tbl_kabupaten.namakab, tbl_kecamatan.namakec, tbl_desa.namadesa, date(tbl_pasien.tgllahir) as tanggallahir,
		status.keterangan as namastatus,iklinik,cekiklinik

		from tbl_pasien 

		left outer join tbl_propinsi on tbl_pasien.propinsi=tbl_propinsi.kodeprop		

		left outer join tbl_kabupaten on tbl_pasien.kabupaten=tbl_kabupaten.kodekab		

		left outer join tbl_kecamatan on tbl_pasien.kecamatan=tbl_kecamatan.kodekec		

		left outer join tbl_desa on tbl_pasien.kelurahan=tbl_desa.kodedesa		

		left outer join tbl_namers   on tbl_pasien.koders=tbl_namers.koders

		left outer join tbl_setinghms as agama on tbl_pasien.agama=agama.kodeset

		left outer join tbl_setinghms as pendidikan on tbl_pasien.pendidikan=pendidikan.kodeset

		left outer join tbl_setinghms as pekerjaan on tbl_pasien.pekerjaan=pekerjaan.kodeset
		left outer join tbl_setinghms as status on tbl_pasien.status=status.kodeset

		where tbl_pasien.rekmed = '$kode'

		

		"
		);


		$row = $query->row();

		if ($row) {

			return $row;
		} else {

			return '';
		}
	}


	public function _datapasien_directRegist($kode)

	{

		//$query = $this->db->get_where( 'tbl_pasien', array( 'rekmed' => $kode ) );

		$query = $this->db->query(

			"SELECT tbl_namers.namars, tbl_pasien.*, tbl_propinsi.namaprop, agama.keterangan as namaagama,

		pendidikan.keterangan as namapendidikan,pekerjaan.keterangan as namapekerjaan,

		tbl_kabupaten.namakab, tbl_kecamatan.namakec, tbl_desa.namadesa, date(tbl_pasien.tgllahir) as tanggallahir,
		status.keterangan as namastatus,iklinik,cekiklinik

		from tbl_pasien 

		left outer join tbl_propinsi on tbl_pasien.propinsi=tbl_propinsi.kodeprop		

		left outer join tbl_kabupaten on tbl_pasien.kabupaten=tbl_kabupaten.kodekab		

		left outer join tbl_kecamatan on tbl_pasien.kecamatan=tbl_kecamatan.kodekec		

		left outer join tbl_desa on tbl_pasien.kelurahan=tbl_desa.kodedesa		

		left outer join tbl_namers   on tbl_pasien.koders=tbl_namers.koders

		left outer join tbl_setinghms as agama on tbl_pasien.agama=agama.kodeset

		left outer join tbl_setinghms as pendidikan on tbl_pasien.pendidikan=pendidikan.kodeset

		left outer join tbl_setinghms as pekerjaan on tbl_pasien.pekerjaan=pekerjaan.kodeset
		left outer join tbl_setinghms as status on tbl_pasien.status=status.kodeset

		where tbl_pasien.idtr = '$kode'

		

		"
		);



		$row = $query->row();

		if ($row) {

			return $row;
		} else {

			return '';
		}
	}



	public function _data_registrasi($kode)

	{
		$cabang = $this->session->userdata('unit');
		$query = $this->db->query("SELECT tbl_regist.*, tbl_pasien.namapas, tbl_pasien.alamat, tbl_pasien.idtr, tbl_dokter.nadokter,tbl_pasien.handphone, (select cust_nama from tbl_penjamin where cust_id = tbl_regist.cust_id) as penjamin
		from tbl_regist
		inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed 
		inner join tbl_dokter on tbl_regist.kodokter=tbl_dokter.kodokter
		where tbl_regist.noreg = '$kode' and tbl_regist.koders='$cabang'");
		//  and tbl_dokter.koders='$cabang'
		$row = $query->row();

		if ($row) {

			return $row;
		} else {

			return '';
		}
	}



	public function _data_uangmuka($kode)

	{

		$query = $this->db->get_where('tbl_regist', array('noreg' => $kode));

		$row = $query->row();

		if ($row) {

			$rekmed = $row->rekmed;



			$duangmuka = $this->db->query("SELECT * from tbl_kasir where posbayar='UANG_MUKA' and rekmed = '$rekmed' ")->row();



			return $duangmuka;
		} else {

			return '';
		}
	}



	public function _data_uangmuka_pasien($rekmed)

	{

		$duangmuka = $this->db->query("SELECT sum(totalsemua) as totalsemua from tbl_kasir where posbayar='UANG_MUKA' and rekmed = '$rekmed' and uangmuka-umuka>0 ")->row();

		return $duangmuka;
	}





	public function _data_tindakan($kode)

	{

		$cabang = $this->session->userdata('unit');

		$sql = "

		    select tbl_tarifh.kodetarif, tbl_tarif.cost as harga, tindakan, tbl_tarif.obatpoli as obatpoli, tbl_tarif.bhppoli as bhp, tbl_tarif.tarifrspoli, tbl_tarif.tarifdrpoli, tbl_tarif.feemedispoli, (tbl_tarif.tarifrspoli + tbl_tarif.tarifdrpoli + tbl_tarif.feemedispoli + tbl_tarif.bhppoli + tbl_tarif.obatpoli) as totaljasa

			from tbl_tarifh inner join tbl_tarif

			on tbl_tarifh.kodetarif=tbl_tarif.kodetarif

            where

			  tbl_tarif.koders='$cabang' and

			  tbl_tarifh.kodetarif = '$kode'

			  

		";

		$query = $this->db->query($sql);

		$row = $query->row();

		if ($row) {

			return $row;
		} else {

			return '';
		}
	}



	public function _namagudang($kode)

	{

		$query = $this->db->get_where('inv_gudang', array('kode' => $kode));

		$row = $query->row();

		if ($row) {

			return $row->nama;
		} else {

			return '';
		}
	}



	public function _hpp($kode)

	{

		$query = $this->db->get_where('inv_barang', array('kodeitem' => $kode));

		$row = $query->row();

		return $row->hargabeli;
	}



	public function _tipeakun($kode)
	{

		$query = $this->db->get_where('tbl_actype', array('actype' => $kode));

		$row = $query->row();

		return $row->typename;
	}

	function cek_internet()
	{
		$connected = @fsockopen("www.google.com", 80);

		if ($connected) {
			$is_conn = true; //jika koneksi tersambung
			fclose($connected);
		} else {
			$is_conn = false; //jika koneksi gagal
		}
		return $is_conn;
	}


	public function _jenisakun($kode)

	{

		$query = $this->db->query("SELECT tbl_actype.* from tbl_accounting inner join tbl_actype 

		on tbl_accounting.actype=tbl_actype.actype 

		where tbl_accounting.accountno = '$kode'");

		$row = $query->row();

		return $row->jenisac;
	}



	public function _lapakun($kode)

	{

		$query = $this->db->query("SELECT tbl_actype.* from tbl_accounting inner join tbl_actype 

		on tbl_accounting.actype=tbl_actype.actype 

		where tbl_accounting.accountno = '$kode'");

		$row = $query->row();

		return $row->ackode;
	}



	public function _namaakun($kode)

	{

		$query = $this->db->get_where('tbl_accounting', array('accountno' => $kode));

		$row = $query->row();

		return $row->acname;
	}



	public function _satbarang($kode)

	{

		$query = $this->db->get_where('inv_barang', array('kodeitem' => $kode));

		$row = $query->row();

		return $row->satuan;
	}

	
	function get_satuan($str)
	{
		$query = $this->db->query("SELECT * from tbl_barangsetup where apogroup='SATUAN' AND apocode<>''");

		return $query->result();
	}



	public function _namabank($kode)

	{

		$query = $this->db->get_where('ms_bank', array('bank_kode' => $kode));

		$row = $query->row();

		return $row->bank_nama;
	}





	public function _akunlrberjalan()

	{

		$query = $this->db->get('ms_identity');

		$row = $query->row();

		return $row->akunlrberjalan;
	}



	public function _namakaryawan($id)

	{

		$query = $this->db->get_where('hrd_karyawan', array('id' => $id));

		$row = $query->row();

		return $row->nama;
	}





	public function _saldoakun($akun, $bulan, $tahun)

	{



		$query = $this->db->select('*')->get_where('ms_akunsaldo', array('kodeakun' => $akun, 'tahun' => $tahun, 'bulan' => $bulan));

		$data  = $query->row();

		$jumdata = $query->num_rows();



		if ($jumdata > 0) {

			$saldo = $data->debet + $data->kredit;

			return $saldo;
		} else {

			return 0;
		}
	}



	public function _totjurnal($akun, $jenis, $tgl1, $tgl2)

	{

		$_tgl1 = date('Y-m-d', strtotime($tgl1));

		$_tgl2 = date('Y-m-d', strtotime($tgl2));



		$query = $this->db->query("SELECT sum(debet) as debet, sum(kredit) as kredit from tr_jurnal where kodeakun='$akun' and tanggal between '$_tgl1' and '$_tgl2'");

		$data  = $query->row();

		if ($jenis == "D") {

			return $data->debet - $data->kredit;
		} else {

			return $data->kredit - $data->debet;
		}
	}



	public function _labarugiberjalan_tahun()

	{

		$tahun = $this->_periodetahun();



		$pend  = 0;

		$biaya = 0;

		$qry =

			"select sum(kredit-debet) as total from tr_jurnal inner join ms_akun

			on tr_jurnal.kodeakun=ms_akun.kodeakun inner join

			ms_akun_kelompok on ms_akun.kelompok=ms_akun_kelompok.kode

			where ms_akun_kelompok.lap='L' and ms_akun_kelompok.nokel=1

			and year(tr_jurnal.tanggal) = '$tahun'";



		$query = $this->db->query($qry);

		$data  = $query->row();

		$pend  = $data->total;



		$qry =

			"select sum(debet-kredit) as total from tr_jurnal inner join ms_akun

			on tr_jurnal.kodeakun=ms_akun.kodeakun inner join

			ms_akun_kelompok on ms_akun.kelompok=ms_akun_kelompok.kode

			where ms_akun_kelompok.lap='L' and ms_akun_kelompok.nokel=2

			and year(tr_jurnal.tanggal) = '$tahun'";



		$query = $this->db->query($qry);

		$data  = $query->row();

		$biaya = $data->total;



		return $pend - $biaya;
	}



	public function _labarugiberjalan($tgl1, $tgl2)

	{

		$_tgl1 = date('Y-m-d', strtotime($tgl1));

		$_tgl2 = date('Y-m-d', strtotime($tgl2));



		$pend  = 0;

		$biaya = 0;

		$qry =

			"select sum(kredit-debet) as total from tr_jurnal inner join ms_akun

			on tr_jurnal.kodeakun=ms_akun.kodeakun inner join

			ms_akun_kelompok on ms_akun.kelompok=ms_akun_kelompok.kode

			where ms_akun_kelompok.lap='L' and ms_akun_kelompok.nokel=1

			and tr_jurnal.tanggal between '$_tgl1' and '$_tgl2'";



		$query = $this->db->query($qry);

		$data  = $query->row();

		$pend  = $data->total;



		$qry =

			"select sum(debet-kredit) as total from tr_jurnal inner join ms_akun

			on tr_jurnal.kodeakun=ms_akun.kodeakun inner join

			ms_akun_kelompok on ms_akun.kelompok=ms_akun_kelompok.kode

			where ms_akun_kelompok.lap='L' and ms_akun_kelompok.nokel=2

			and tr_jurnal.tanggal between '$_tgl1' and '$_tgl2'";



		$query = $this->db->query($qry);

		$data  = $query->row();

		$biaya = $data->total;



		return $pend - $biaya;
	}







	public function _totjurnalakunbln($akun, $jenis, $bln, $thn)

	{

		$q = $this->db->query("SELECT sum(debet) as debet, sum(kredit) as kredit from tr_jurnal where kodeakun='$akun' and year(tanggal)='$thn' and month(tanggal)='$bln'");

		foreach ($q->result() as $row) {

			$debet = $row->debet;

			$kredit = $row->kredit;
		}

		if ($jenis == 'D') {

			return $debet - $kredit;
		} else {

			return $kredit - $debet;
		}
	}



	public function _akunbank($kode)

	{

		$query = $this->db->get_where('ms_bank', array('bank_kode' => $kode));

		$row = $query->row();

		return $row->bank_kodeakun;
	}



	public function _data_merk($id)

	{

		$query = $this->db->get_where('inv_merk', array('kode' => $id));

		return $query->row();
	}



	public function _data_rak($id)

	{

		$query = $this->db->get_where('inv_rak', array('kode' => $id));

		return $query->row();
	}



	public function _data_barang($id)
	{

		$query = $this->db->get_where('tbl_barang', array('kodebarang' => $id));
		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			$queryl = $this->db->get_where('tbl_logbarang', array('kodebarang' => $id));
			return $queryl->row();
		}
	}

	// public function get_list( $str )

	// {



	// }



	public function _data_barang_log($id)

	{

		$query = $this->db->get_where('tbl_logbarang', array('kodebarang' => $id));

		return $query->row();
	}



	public function _data_barang_customer($id, $customer)

	{

		$query = $this->db->query("SELECT inv_barang_harga.*, 

		inv_barang.namabarang, inv_barang.hargabeli, inv_barang.satuan

		from inv_barang inner join inv_barang_harga on inv_barang.kodeitem=inv_barang_harga.kodeitem

		where inv_barang.kodeitem= '$id' and inv_barang_harga.kode_customer = '$customer'

		");

		return $query->row();
	}



	public function _data_akun($id)

	{

		$query = $this->db->get_where('ms_akun', array('kodeakun' => $id));

		return $query->row();
	}



	public function _data_customer($id)

	{

		$query = $this->db->get_where('ar_customer', array('kode' => $id));

		return $query->row();
	}





	function update_data($where, $data, $table)
	{

		$this->db->where($where);

		$this->db->update($table, $data);
	}



	function input_data($data, $table)
	{

		$this->db->insert($table, $data);
	}



	function hapus_data($where, $table)
	{

		$this->db->where($where);

		$this->db->delete($table);
	}



	public function update_idjurnal()

	{

		$this->db->query('update nomor_auto set nilai=nilai+1 where id=1');
	}



	public function nomor_jurnal($unit, $jenis, $thn, $bln)

	{

		$q = $this->db->query("SELECT nilai from nomor_auto where id=1");

		$kd = "";

		if ($q->num_rows() > 0) {

			foreach ($q->result() as $k) {

				$tmp = ((int)$k->nilai) + 1;

				$kd = sprintf("%06s", $tmp);
			}
		} else {

			$kd = "000001";
		}

		return "$unit$jenis$thn$bln$kd";
	}



	function manualQuery($q)

	{

		return $this->db->query($q);
	}



	function tgln()
	{
		$hari   = date('d');
		$hari1  = date('N');
		$bulan  = date('n');
		$tahun  = date('Y');

		$nbln = array('', 'Januari', 'Pebruari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember');

		$nhari = array('', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu');

		return  $nhari[$hari1] . ", " . $hari . " " . $nbln[$bulan] . " " . $tahun . " ";
	}



	public function _counter1($kode)

	{

		$query = $this->db->get_where('ms_counter1', array('kdtr' => $kode));

		$row = $query->row();

		return $row->cdno;
	}



	public function _updatecounter1($kode)

	{

		$this->db->where('kdtr', $kode);

		$this->db->set('cdno', 'cdno+ 1', FALSE);

		$this->db->update('ms_counter1');
	}



	public function _Autonomor($kode)

	{

		$nourut = $this->_counter1($kode);

		$nomor = $kode . '.' . date('Y') . '.' . date('m') . '.' . str_pad($nourut, 4, '0', STR_PAD_LEFT);

		//$nomor = $kode.date('y').'-'.$nourut;

		return $nomor;
	}



	function getcoa($str)

	{

		if ($str != "") {

			$query = $this->db->query("SELECT accountno as id, concat(accountno,'-',acname) as text from tbl_accounting where aclevel='4' and (acname like '%$str%' or accountno like '$str%')");
		} else {

			$query = $this->db->query("SELECT accountno as id, concat(accountno,'-',acname) as text from tbl_accounting where aclevel='4'");
		}



		return $query->result();
	}

	function getpenjamin($str)

	{

		$query = '';

		if ($str != "") {

			$query = $this->db->query("SELECT cust_id as id, cust_nama as text from tbl_penjamin where cust_nama like '%$str%'");
		} else {

			$query = $this->db->query("SELECT cust_id as id, cust_nama as text from tbl_penjamin");
		}



		return $query->result();
	}





	function getpromo($str)

	{

		$cabang = $this->session->userdata('unit');

		$tanggal = date('Y-m-d');

		if ($str != "") {

			$query = $this->db->query("SELECT kodepromo as id, namapromo as text from tbl_promo where stpromo=1 and namapromo like '%$str%'

		  and kodepromo in(select kodepromo from tbl_promocabang where koders='$cabang' and promo=1)

		  and '$tanggal'>=date(tglmulai) and date(tglselesai)<='$tanggal'

		  ");
		} else {

			$query = $this->db->query("SELECT kodepromo as id, namapromo as text from tbl_promo where stpromo=1

		  and kodepromo in(select kodepromo from tbl_promocabang where koders='$cabang' and promo=1

		  and '$tanggal'>=date(tglmulai) and date(tglselesai)<='$tanggal'

		  )

		  ");
		}



		return $query->result();
	}

	function getvouchersource($str)

	{
		$cabang = $this->session->userdata('unit');
		if ($str != "") {
			$query = $this->db->query("SELECT cust_id as id, cust_nama as text from tbl_penjamin where isvoucher=1 and cust_nama like '%$str%'");
		} else {
			$query = $this->db->query("SELECT cust_id as id, cust_nama as text from tbl_penjamin where isvoucher=1");
		}
		return $query->result();
	}

	function getvoucher($param)
	{
		$unit = $this->session->userdata("unit");
		// $query = $this->db->query("SELECT CONCAT(novoucher,' - ',nominal) AS text, novoucher AS id FROM tbl_vocd WHERE koders = '$unit' AND NOT nokir LIKE '%deleted%'");
		$query = $this->db->query("SELECT CONCAT(a.novoucher ,' - ', a.nominal) AS text, a.novoucher AS id 
		FROM tbl_vocd AS a 
		LEFT JOIN tbl_vocjual AS b
		ON b.novoucher = a.novoucher
		WHERE a.koders = '$unit'
		AND a.terjual = 0");
		return $query->result();
	}

	function gethadiah($str)

	{

		$query = '';

		if ($str != "") {

			$query = $this->db->query("SELECT kohadiah as id, namahadiah as text from tbl_promohadiah where namahadiah like '%$str%'");
		} else {

			$query = $this->db->query("SELECT kohadiah as id, namahadiah as text from tbl_promohadiah");
		}



		return $query->result();
	}


	function getpos($str)

	{
		$query = $this->db->query("SELECT accountno as id, concat(accountno,' | ',acname) as text from tbl_accounting where aktif=1");
		return $query->result();
	}

	function getkasbank($str)

	{

		//$query = $this->db->query("SELECT accr as id, namabank as text from tbl_edc");		

		// $query = $this->db->query("SELECT accountno as id, concat(accountno,' | ',acname) as text from tbl_accounting where kasbank=1 and aclevel=4 and (accountno like '%$str%' or acname like '%$str%')");		

		$query = $this->db->query("SELECT accountno as id, concat(accountno,' | ',acname) as text from tbl_accounting where kasbank=1 
					-- and aclevel=4 
					and (accountno like '%$str%' or acname like '%$str%')
					and aktif=1");

		return $query->result();
	}

	function getakundiskonadjust($str)
	{

		//$query = $this->db->query("SELECT accr as id, namabank as text from tbl_edc");	


		if ($str == '') {
			$query = $this->db->query("SELECT accountno as id, concat(accountno,' | ',acname) as text from tbl_accounting");
		} else {
			$query = $this->db->query("SELECT accountno as id, concat(accountno,' | ',acname) as text from tbl_accounting WHERE (accountno like '%$str%' or acname like '%$str%')");
		}
		return $query->result();
	}


	function getcostcentre($str)
	{

		//$query = $this->db->query("SELECT accr as id, namabank as text from tbl_edc");		

		$query = $this->db->query("SELECT depid as id, concat(depid,' | ',namadep)  as text FROM tbl_accostcentre");

		return $query->result();
	}

	function getkasbankedc($str)

	{

		$query = $this->db->query("SELECT bankcode as id, namabank as text from tbl_edc");

		return $query->result();
	}



	function getdept($str)

	{

		$query = $this->db->query("SELECT depid as id, namadep as text from tbl_accostcentre where (namadep like '%$str%' or depid like '$str%')");



		return $query->result();
	}

	function getdepo($str)

	{

		$query = $this->db->query("SELECT depocode as id, keterangan as text from tbl_depo where (depocode like '%$str%' or keterangan like '$str%')");



		return $query->result();
	}



	function getprovinsi($str)

	{

		$query = $this->db->query("SELECT kodeprop as id, namaprop as text from tbl_propinsi where (namaprop like '%$str%' or kodeprop like '$str%')");



		return $query->result();
	}

	function getAkunBiaya($str)
	{
		$query = $this->db->query("SELECT accountno AS id, CONCAT(accountno,' | ',acname) AS text FROM tbl_accounting WHERE (acname LIKE '%$str%' OR accountno LIKE '$str%');");
		return $query->result();
	}

	function getkota($str, $p)

	{

		if ($p != "") {

			$param = " and kodeprop= '$p'";
		} else {

			$param = "";
		}



		$query = $this->db->query("SELECT kodekab as id, namakab as text from tbl_kabupaten where namakab is not null $param and (namakab like '%$str%' or kodekab like '$str%')");



		return $query->result();
	}



	function getkecamatan($str, $p)

	{

		if ($p != "") {

			$param = " and kodekab= '$p'";
		} else {

			$param = "";
		}



		$query = $this->db->query("SELECT kodekec as id, namakec as text from tbl_kecamatan where namakec is not null $param and (namakec like '%$str%' or kodekec like '$str%')");



		return $query->result();
	}



	function getagama($str)

	{

		$query = $this->db->query("SELECT kodeset as id, keterangan as text from tbl_setinghms where lset='AGAM' and (keterangan like '%$str%' or kodeset like '$str%')");

		return $query->result();
	}



	function getpendidikan($str)

	{

		$query = $this->db->query("SELECT kodeset as id, keterangan as text from tbl_setinghms where lset='PEND' and (keterangan like '%$str%' or kodeset like '$str%')");

		return $query->result();
	}



	function getpreposition($str)

	{

		$query = $this->db->query("SELECT kodeset as id, keterangan as text from tbl_setinghms where lset='PREP' and (keterangan like '%$str%' or kodeset like '$str%')");

		return $query->result();
	}







	function getgoldarah($str)

	{

		$query = $this->db->query("SELECT kodeset as id, keterangan as text from tbl_setinghms where lset='GOLD' and (keterangan like '%$str%' or kodeset like '$str%')");

		return $query->result();
	}



	function getstatuspasien($str)

	{

		$query = $this->db->query("SELECT kodeset as id, keterangan as text from tbl_setinghms where lset='STAT' and (keterangan like '%$str%' or kodeset like '$str%')");

		return $query->result();
	}



	function getpekerjaan($str)

	{

		$query = $this->db->query("SELECT kodeset as id, keterangan as text from tbl_setinghms where lset='PEKE' and (keterangan like '%$str%' or kodeset like '$str%')");

		return $query->result();
	}



	function getjenispasien($str)

	{

		$query = $this->db->query("SELECT kodeset as id, keterangan as text from tbl_setinghms where lset='JPAS' and (keterangan like '%$str%' or kodeset like '$str%')");

		return $query->result();
	}



	function getpasien($str)

	{

		$query = $this->db->query("SELECT rekmed as id, concat(rekmed,' | ',namapas,' | ',alamat,' | ',noidentitas,' | ',handphone) as text from tbl_pasien where (rekmed like '%$str%' or namapas like '%$str%' or alamat like '%$str%' or tgllahir like '%$str%' or handphone like '%$str%' or noidentitas like '%$str%' or nocard like '%$str%')");

		return $query->result();
	}


	function getpvpasien($str)
	{

		return $this->db->query("SELECT rekmed AS id, CONCAT(namapas) AS nama FROM tbl_pasien WHERE rekmed = '$str'");
	}


	function get_seting_hms($str, $kode)

	{

		$cabang = $this->session->userdata('unit');

		$sql =
			"SELECT kodeset as id, keterangan as text 
			from tbl_setinghms
            where lset = '$kode' and 
			(kodeset like '%$str%' or keterangan like '$str%')";

		$query = $this->db->query($sql);

		return $query->result();
	}



	function get_tarif_tindakan($str, $poli)

	{

		$cabang = $this->session->userdata('unit');

		$sql = "SELECT tbl_tarifh.kodetarif as id, concat(tbl_tarifh.kodetarif,' | ',tbl_tarifh.tindakan,' | ',format(tbl_tarif.cost,0)) as text 
			from tbl_tarifh inner join tbl_tarif
			on tbl_tarifh.kodetarif=tbl_tarif.kodetarif
            where
			  tbl_tarif.koders='$cabang' and
			  tbl_tarifh.kodepos='$poli' and 
			  (tbl_tarifh.kodetarif like '%$str%' or tbl_tarifh.tindakan like '$str%')			

		";



		$query = $this->db->query($sql);

		return $query->result();
	}



	function get_farmasi_po($str, $vendor)

	{

		$cabang = $this->session->userdata('unit');

		$sql = "SELECT po_no as id, concat(po_no,' | ',date_format(tglpo,'%d-%m-%Y')) as text 
			from tbl_baranghpo
            where koders='$cabang' and po_no not in (select po_no from tbl_barangdterima ) and vendor_id='$vendor' and closed=0 AND setuju = 1 and (po_no like '%$str%')	ORDER BY tglpo asc
		";

		$query = $this->db->query($sql);

		return $query->result();
	}

	function get_farmasi_po2($str, $vendor)

	{
		$cabang = $this->session->userdata('unit');

		$sql = "SELECT po_no as id, concat(po_no,' | ',date_format(tglpo,'%d-%m-%Y')) as text 
			from tbl_baranghpo
            where koders='$cabang' and vendor_id='$vendor' and closed=0 and (po_no like '%$str%') and po_no not in(SELECT po_no FROM tbl_barangdterima) 
			ORDER BY tglpo asc";
		$query = $this->db->query($sql);

		return $query->result();
	}

	function get_logistik_po($str, $vendor)

	{
		$cabang = $this->session->userdata('unit');

		$sql = "SELECT po_no as id, concat(po_no,' | ',date_format(po_date,'%d-%m-%Y')) as text 
			from tbl_apohpolog
            where setuju = 1 and koders='$cabang' and vendor_id='$vendor' and closed=0 and (po_no like '%$str%') and po_no not in(SELECT po_no FROM tbl_apodterimalog) 
			ORDER BY po_date asc";
		$query = $this->db->query($sql);

		return $query->result();
	}

	function get_logistik_po2($str, $vendor)

	{
		$cabang = $this->session->userdata('unit');

		$sql = "SELECT po_no as id, concat(po_no,' | ',date_format(po_date,'%d-%m-%Y')) as text 
			from tbl_apohpolog
            where koders='$cabang' and vendor_id='$vendor' and closed=0 and (po_no like '%$str%') and po_no not in(SELECT po_no FROM tbl_apodterimalog) 
			ORDER BY po_date asc";
		$query = $this->db->query($sql);

		return $query->result();
	}

	function getregistrasi($str, $poli)

	{
		$cabang = $this->session->userdata('unit');
		// $sql 	= "SELECT tbl_regist.noreg as id, 
		// concat(		
		// tbl_regist.noreg,' | ',
		// date_format(tbl_regist.tglmasuk,'%d-%m-%Y'),' | ',
		// tbl_regist.jam,' | ',
		// tbl_pasien.namapas,' | ',
		// tbl_regist.kodepos,' | ',
		// tbl_dokter.nadokter,' | '		
		// ) as text 
		// from tbl_regist 
		// inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed 
		// inner join tbl_dokter on tbl_regist.kodokter=tbl_dokter.kodokter 
		// -- AND tbl_dokter.koders=tbl_regist.koders
		// where tbl_regist.keluar=0 and tbl_regist.koders = '$cabang' 
		// and (tbl_regist.noreg like '%$str%' or tbl_pasien.namapas like '%$str%')";		
		// 	if($poli!=''){
		// 	  $sql .=" and tbl_regist.kodepos = '$poli' ";	
		// 	}
		// 	$sql .= " order by tbl_regist.id ASC ";
		//     $query = $this->db->query($sql);		
		//    return $query->result();
		// if ($poli != '') {
		// 	$sqlx = " and tbl_regist.kodepos = '$poli' ";
		// } else {
		// 	$sqlx = "";
		// }
		// $sql = "SELECT tbl_regist.noreg as id, concat( tbl_regist.noreg,' | ', date_format(tbl_regist.tglmasuk,'%d-%m-%Y'),' | ', tbl_regist.jam,' | ', tbl_pasien.namapas,' | ', tbl_regist.kodepos,' | ', tbl_dokter.nadokter,' | ' ) as text from tbl_regist inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed inner join tbl_dokter on tbl_regist.kodokter=tbl_dokter.kodokter where tbl_regist.keluar=0 and tbl_regist.koders = '$cabang' and (tbl_regist.noreg like '%$str%' or tbl_pasien.namapas like '%$str%') $sqlx order by tbl_regist.id ASC ";


		// script original
		// $sql = "SELECT tbl_regist.noreg as id, concat( tbl_regist.noreg,' | ', date_format(tbl_regist.tglmasuk,'%d-%m-%Y'),' | ', tbl_regist.jam,' | ', tbl_pasien.namapas,' | ', tbl_regist.kodepos,' | ', tbl_dokter.nadokter,' | ' ) as text from tbl_regist inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed inner join tbl_dokter on tbl_regist.kodokter=tbl_dokter.kodokter where tbl_regist.keluar=0 and tbl_regist.koders = '$cabang' and (tbl_regist.noreg like '%$str%' or tbl_pasien.namapas like '%$str%') order by tbl_regist.id ASC ";
		// husain change
		$sql = "SELECT tbl_regist.noreg as id, concat( tbl_regist.noreg,' | ', date_format(tbl_regist.tglmasuk,'%d-%m-%Y'),' | ', tbl_regist.jam,' | ', tbl_pasien.namapas,' | ', tbl_regist.kodepos) as text from tbl_regist inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed where tbl_regist.keluar=0 and tbl_regist.koders = '$cabang' and (tbl_regist.noreg like '%$str%' or tbl_pasien.namapas like '%$str%') and tbl_regist.noreg in (select noreg from tbl_dpoli) order by tbl_regist.id ASC ";
		// end husain
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_icdind($str, $sab)
	{
		if ($str != "") {
			$sql 	= "SELECT tbl_icdinb.code as id, concat( tbl_icdinb.code,' | ',
			tbl_icdinb.str,' | ', tbl_icdinb.code2 ) as text 
			from tbl_icdinb 
			where tbl_icdinb.sab='$sab' 
			and (tbl_icdinb.code like '%$str%' or tbl_icdinb.str like '%$str%') 
			order by code";
		} else {

			$sql 	= "SELECT tbl_icdinb.code as id, concat( tbl_icdinb.code,' | ',
			tbl_icdinb.str,' | ', tbl_icdinb.code2 ) as text 
			from tbl_icdinb 
			where tbl_icdinb.sab='$sab' 
			and (tbl_icdinb.code like '%$str%' or tbl_icdinb.str like '%$str%') 
			order by code LIMIT 1000";
		}

		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_jnsicd($str)

	{

		$sql 	= "SELECT tbl_setinghms.kodeset as id, tbl_setinghms.keterangan as text 
		from tbl_setinghms
		where lset='JNDG'
		order by kodeset";

		$query = $this->db->query($sql);
		return $query->result();
	}

	function getregistrasi_resep($str, $poli)

	{
		$cabang = $this->session->userdata('unit');
		if ($poli != '') {
			$sqlx = " and tbl_regist.kodepos = '$poli' ";
		} else {
			$sqlx = "";
		}
		$sql = "SELECT tbl_regist.noreg as id, 
		concat(		
		tbl_regist.noreg,' | ',
		date_format(tbl_regist.tglmasuk,'%d-%m-%Y'),' | ',
		tbl_regist.jam,' | ',
		tbl_pasien.namapas,' | ',
		tbl_regist.kodepos,' | ',
		tbl_dokter.nadokter,' | '		
		) as text 
		from tbl_regist 
		inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed 
		inner join tbl_dokter on tbl_regist.kodokter=tbl_dokter.kodokter 
		-- AND tbl_dokter.koders=tbl_regist.koders
		where tbl_regist.keluar=0 and tbl_regist.koders = '$cabang' 
		/*and tbl_regist.noreg not in (select noreg from tbl_apohresep)*/
		and (tbl_regist.noreg like '%$str%' or tbl_pasien.namapas like '%$str%') $sqlx order by tbl_regist.id ASC";
		$query = $this->db->query($sql);
		return $query->result();
	}



	function getpoli($str)

	{

		$query = $this->db->query("SELECT kodepos as id, namapost as text from tbl_namapos where (kodepos like '%$str%' or namapost like '$str%' )");

		return $query->result();
	}



	function getsearch_warnao($str)
	{

		$query = $this->db->query("SELECT id_kategori_warna id,
		jenis_penyakit as text FROM kategori_warna WHERE status='Y' and (id_kategori_warna like '%$str%' or jenis_penyakit like '$str%' or nama_kategori_warna like '$str%')");

		return $query->result();
	}

	function getdokter($str, $poli)
	{
		$unit	= $this->session->userdata("unit");
		$query	= $this->db->query("SELECT a.kodokter AS id, CONCAT(a.kodokter ,' - ', a.nadokter) AS text 
		FROM dokter AS a 
		WHERE a.koders = '$unit' 
		AND a.kopoli = '$poli' AND a.status='ON'
		AND (a.kodokter LIKE '%$str%' OR a.nadokter LIKE '%$str%') 
		ORDER BY a.nadokter");
		// $query = $this->db->query("SELECT kodokter as id, concat(kodokter,' | ',nadokter) as text from tbl_dokter where jenispegawai =1 and (kodokter like '%$str%' or nadokter like '$str%' or alamat like '$str%') order by nadokter");

		return $query->result();
	}


	function getperawat($str, $poli)
	{
		$unit	= $this->session->userdata("unit");
		$query	= $this->db->query("SELECT a.kodokter AS id, CONCAT(a.kodokter ,' - ', a.nadokter) AS text 
		FROM perawat AS a 
		WHERE a.koders = '$unit' 
		AND a.kopoli = '$poli' 
		AND (a.kodokter LIKE '%$str%' OR a.nadokter LIKE '%$str%') 
		ORDER BY a.nadokter");
		// $query = $this->db->qu
		// $cabang = $this->session->userdata('unit');
		// $query = $this->db->query("SELECT kodokter as id, concat(kodokter,' | ',nadokter) as text from tbl_dokter where koders='$cabang' and jenispegawai =2 and (kodokter like '%$str%' or nadokter like '$str%' or alamat like '$str%')");

		return $query->result();
	}

	function getfarmasidepo($str)
	{
		$query = $this->db->query("SELECT depocode as id, keterangan as text from tbl_depo where (depocode like '%$str%' or keterangan like '$str%')");
		return $query->result();
	}

	function getfarmasiuser2($str)
	{
		$query = $this->db->query("SELECT uidlogin as id, username as text from userlogin where (uidlogin like '%$str%' or username like '$str%') and user_level >= 2");
		return $query->result();
	}

	function getfarmasiuser($str)
	{
		$query = $this->db->query("SELECT uidlogin as id, username as text from userlogin where (uidlogin like '%$str%' or username like '$str%')");
		return $query->result();
	}

	function getlogistikdepo($str)
	{
		$query = $this->db->query("SELECT depocode as id, keterangan as text from tbl_depo where keterangan not like '%farmasi%' and (depocode like '%$str%' or keterangan like '$str%')");
		return $query->result();
	}

	function getresep_obat($str)

	{

		$unit = $this->session->userdata('unit');



		$query = $this->db->query("

		select resepno as id, concat(resepno,' | ',namapas,' | ',date_format(tglresep,'%d-%m-%Y')) as text from tbl_apoposting 

		where keluar=0 and koders= '$unit' and (namapas like '%$str%' or resepno like '$str%')");



		return $query->result();
	}

	function getAllresep_obat($str)

	{

		$unit = $this->session->userdata('unit');


		$tgl = date('Y-m-d');
		$query = $this->db->query("SELECT resepno as id, concat(resepno,' | ',namapas,' | ',date_format(tglresep,'%d-%m-%Y')) as text from tbl_apoposting where koders= '$unit' and (namapas like '%$str%' or resepno like '$str%') and tglresep = '$tgl' and resepno not in (select resepno from tbl_apohreturjual)");

		// $query = $this->db->query("SELECT resepno as id, concat(resepno,' | ',date_format(tglretur,'%d-%m-%Y')) as text from tbl_apohreturjual where koders= '$unit' and (resepno like '$str%') and tglretur = '$tgl'");


		return $query->result();
	}

	function getAllresep_obat_retur($str)

	{

		$unit = $this->session->userdata('unit');


		$tgl = date('Y-m-d');
		$query = $this->db->query("SELECT resepno as id, concat(resepno,' | ',namapas,' | ',date_format(tglresep,'%d-%m-%Y')) as text from tbl_apoposting where koders= '$unit' AND noreg IN (SELECT noreg FROM tbl_kasir) and (namapas like '%$str%' or resepno like '$str%') and tglresep = '$tgl' and resepno not in (select resepno from tbl_apohreturjual)");


		return $query->result();
	}





	function getcabang($str)

	{

		$unit = $this->session->userdata('unit');



		if ($unit != "") {

			$query = $this->db->query("SELECT koders as id, concat(namars) as text from tbl_namers where koders= '$unit' order by namars");
		} else {

			$query = $this->db->query("SELECT koders as id, concat(namars) as text from tbl_namers order by namars");
		}



		return $query->result();
	}

	function getcabang2()

	{

		$unit = $this->session->userdata('unit');



		if ($unit != "") {

			$query = $this->db->query("SELECT koders as id, concat(namars) as text from tbl_namers where koders= '$unit' order by namars");
		} else {

			$query = $this->db->query("SELECT koders as id, concat(namars) as text from tbl_namers order by namars");
		}



		return $query->row();
	}



	function getcabang_all($str)
	{

		$query = $this->db->query("SELECT koders as id, concat(namars) as text from tbl_namers order by namars");

		return $query->result();
	}

	function getcabang_all_sess($str)
	{
		$unit = $this->session->userdata('cabb');
		$data  = explode(",",$unit);
		$query = $this->db->select("koders as id, namars as text")
		->where_in("koders", $data)
		->order_by("namars")
		->get("tbl_namers");

		return $query->result();
	}

	function getpendapatan($str)
	{

		$query = $this->db->query("SELECT accountno as id, concat(accountno, ' - ', acname) as text from tbl_accounting  where acname like '%$str%' or  accountno like '%$str%' order by acname ASC");

		return $query->result();
	}

	function getcabang_selected($unit)

	{

		return $this->db->query("SELECT * FROM tbl_namers WHERE koders = '$unit'");
	}





	function getvendor($str)

	{

		$unit = $this->session->userdata('unit');



		if ($str != "") {

			$query = $this->db->query("SELECT vendor_id as id, concat(vendor_name) as text from tbl_vendor where (vendor_id  like '%$str%' or vendor_name like '%$str%') order by vendor_id");
		} else {

			$query = $this->db->query("SELECT vendor_id as id, concat(vendor_name) as text from tbl_vendor order by vendor_id");
		}



		return $query->result();
	}


	function getrekeningvendor($str)

	{

		$unit = $this->session->userdata('unit');



		if ($str != "") {
			$qry = "SELECT vr.id AS id, CONCAT(no_rekening, ' | ', atas_nama, ' | ', nama_bank, ' | ', vendor_name) AS text
					FROM tbl_vendor_rekening vr 
						LEFT JOIN tbl_vendor v ON BINARY vr.`vendor_id` = BINARY v.`vendor_id`
					WHERE (vr.vendor_id  LIKE '%$str%' OR no_rekening LIKE '%$str%' OR atas_nama LIKE '%$str%' OR nama_bank LIKE '%$str%' OR vendor_name LIKE '%$str%')
					;";

			$query = $this->db->query($qry);
		} else {
			$qry = "SELECT vr.id AS id, CONCAT(no_rekening, ' | ', atas_nama, ' | ', nama_bank, ' | ', vendor_name) AS text
					FROM tbl_vendor_rekening vr 
						LEFT JOIN tbl_vendor v ON BINARY vr.`vendor_id` = BINARY v.`vendor_id`
					;";
			$query = $this->db->query($qry);
		}



		return $query->result();
	}


	function getfarmasibarang($str)
	{
		//-- saya ganti --//
		$unit = $this->session->userdata('unit');

		if ($unit != "") {

			//  $query = $this->db->query("SELECT kodebarang as id, concat(' [ ', kodebarang ,' ] ',' - ',' [ ', namabarang ,' ] ',' - ',' [ ', satuan1 ,' ] ',' - ',' [ ', salakhir ,' ] ',' - ',' [ ', hargajual ,' ] ') as text FROM(
			// 	SELECT
			// 	kodebarang,namabarang,satuan1,hargajual,
			// 	IFNULL((select sum(saldoakhir) as saldoakhir from tbl_barangstock b where koders='$unit' and gudang='$str' and b.kodebarang=a.kodebarang),0) as salakhir
			// 	from tbl_barang a where (kodebarang like '%$str%' or namabarang like '$str%') 
			// 	) as c
			// 	order by kodebarang");
			$query = $this->db->query("SELECT a.kodebarang AS id, CONCAT('[',a.kodebarang,'] - [',b.namabarang,'] - [',b.satuan1,'] - [',format(if(a.kodebarang is null, 0, a.saldoakhir), 0),'] - [',FORMAT(b.hargabeli, 0),']') as text FROM tbl_barangstock a JOIN tbl_barang b ON a.kodebarang=b.kodebarang WHERE a.koders = '$unit' and (b.kodebarang LIKE '%$str%' OR b.namabarang LIKE '%$str%')");
		} else {

			$query = $this->db->query("SELECT kodebarang as id, concat(kodebarang,' | ',namabarang,' | ',satuan1,' | ',salakhir) as text FROM(
			SELECT
			kodebarang,namabarang,satuan1,hargajual,
			IFNULL((select sum(saldoakhir)saldoakhir from tbl_barangstock b where koders='$unit' and b.kodebarang=a.kodebarang),0)salakhir
			from tbl_barang a 
			)c
			order by kodebarang");
		}

		// if($unit != ""){

		//   $query = $this->db->query("SELECT kodebarang as id, concat(kodebarang,' | ',namabarang,' | ',satuan1) as text from tbl_barang where (kodebarang  like '%$str%' or namabarang like '$str%') order by kodebarang");

		// } else {

		//   $query = $this->db->query("SELECT kodebarang as id, concat(kodebarang,' | ',namabarang,' | ',satuan1) as text from tbl_barang order by kodebarang");	

		// }



		return $query->result();
	}

	function getfarmasibarang2($str)
	{
		//-- saya ganti --//
		$unit = $this->session->userdata('unit');

		if ($unit != "") {

			$query = $this->db->query("SELECT kodebarang as id, concat(' [ ', kodebarang ,' ] ',' - ',' [ ', namabarang ,' ] ',' - ',' [ ', satuan1 ,' ] ') as text FROM(
			SELECT kodebarang,namabarang,satuan1,
			IFNULL((select sum(saldoakhir) as saldoakhir from tbl_barangstock b where koders='$unit' and b.kodebarang=a.kodebarang),0) as salakhir
			from tbl_barang a where kodebarang like '%$str%' or namabarang like '$str%'
			) as c
			order by kodebarang");
		} else {
			$query = $this->db->query("SELECT kodebarang as id, concat(kodebarang,' | ',namabarang,' | ',satuan1) as text FROM(
			SELECT
			kodebarang,namabarang,satuan1,
			IFNULL((select sum(saldoakhir) as saldoakhir from tbl_barangstock b where koders='$unit' and b.kodebarang=a.kodebarang),0)salakhir
			from tbl_barang a 
			) as c
			order by kodebarang");
		}

		return $query->result();
	}

	function getfarmasibarang_cbg($str)
	{
		$unit = $this->session->userdata("unit");
		if ($str == "") {
			$limm = "LIMIT 10";
			$kondisi = "";
		} else {
			$limm = "";
			$kondisi = "WHERE semua.kodebarang LIKE '%$str%' OR semua.namabarang LIKE '%$str%'";
		}
		$data = $this->db->query("SELECT semua.kodebarang AS id, concat(' [ kode : ', kodebarang ,' ] ',' - ',' [ nama : ', namabarang ,' ] ',' - ',' [ satuan : ', satuan1 ,' ] ',' - ',' [ barang : ', statusnya ,' ] ') AS text FROM (
			SELECT kodebarang, namabarang, satuan1, 'Farmasi' as statusnya FROM tbl_barang
			UNION ALL
			SELECT kodebarang, namabarang, satuan1, 'Logistik' as statusnya FROM tbl_logbarang
		) AS semua $kondisi $limm");
		return $data->result();
	}

	function getpoli_tindakan($str, $kodpos)
	{

		$unit = $this->session->userdata('unit');

		if ($unit != "") {

			if ($str != "") {
				$sql 	= "SELECT daftar_tarif_nonbedah.kodetarif as id, 
				concat( ' [',daftar_tarif_nonbedah.kodetarif,'] ',' - ',' [',daftar_tarif_nonbedah.tindakan,'] ' ) as text 
				from daftar_tarif_nonbedah where daftar_tarif_nonbedah.kodepos='$kodpos' AND tindakan<>'' and koders='$unit'
				and (daftar_tarif_nonbedah.tindakan like '%$str%' or daftar_tarif_nonbedah.accountno like '%$str%') 
				order by tindakan ";
			} else {

				$sql 	= "SELECT daftar_tarif_nonbedah.kodetarif as id, 
				concat( ' [',daftar_tarif_nonbedah.kodetarif,'] ',' - ',' [',daftar_tarif_nonbedah.tindakan,'] ' ) as text 
				from daftar_tarif_nonbedah where daftar_tarif_nonbedah.kodepos='$kodpos' AND tindakan<>'' and koders='$unit'
				and (daftar_tarif_nonbedah.tindakan like '%%' or daftar_tarif_nonbedah.accountno like '%%') 
				order by tindakan";
			}
		} else {

			$query = $this->db->query("SELECT 0 as id, concat('-- SILAHKAN LOGIN ULANG DAHULU --') as text 
			from tbl_barang limit 1");
		}

		$query = $this->db->query($sql);
		return $query->result();
	}

	function getfarmasidatabarang($str)
	{
		$unit = $this->session->userdata('unit');
		if ($str == "") {
			$limm = "LIMIT 10";
		} else {
			$limm = "";
		}
		if ($unit != "") {
			$query = $this->db->query("SELECT tbl_barang.kodebarang AS id, 
			CONCAT(' 
				[ ', tbl_barang.kodebarang ,' ] ',' - ',' 
				[ ', tbl_barang.namabarang ,' ] ',' - ',' 
				[ ', tbl_barang.satuan1 ,' ] ',' - ',' 
				[ ', tbl_barang.hargajual ,' ]
			') AS text 
			FROM tbl_barang
			WHERE (tbl_barang.kodebarang like '%$str%' OR tbl_barang.namabarang like '%$str%' OR tbl_barang.satuan1 like '%$str%' OR tbl_barang.hargajual like '%$str%') order by tbl_barang.id,tbl_barang.kodebarang $limm
			");
		} else {
			$query = $this->db->query("SELECT kodebarang AS id, 
			CONCAT(' 
				[ ', kodebarang ,' ] ',' - ',' 
				[ ', namabarang ,' ] ',' - ',' 
				[ ', satuan1 ,' ] ',' - ',' 
				[ ', hargajual ,' ] 
			') AS TEXT 
			FROM tbl_barang 
			WHERE (kodebarang like '%$str%' OR namabarang like '%$str%' OR satuan1 like '%$str%' OR hargajual like '%$str%') order by kodebarang LIMIT 1
			");
		}
		return $query->result();
	}

	function getfarmasidatabaranglog($str)
	{
		$unit = $this->session->userdata('unit');
		if ($str == "") {
			$limm = "LIMIT 10";
		} else {
			$limm = "";
		}
		if ($unit != "") {
			$query = $this->db->query("
			SELECT tbl_logbarang.kodebarang AS id, 
			CONCAT(' 
				[ ', tbl_logbarang.kodebarang ,' ] ',' - ',' 
				[ ', tbl_logbarang.namabarang ,' ] ',' - ',' 
				[ ', tbl_logbarang.satuan1 ,' ] ',' - ',' 
				[ ', tbl_logbarang.hargabeli ,' ]
			') AS text 
			FROM tbl_logbarang
			WHERE (tbl_logbarang.kodebarang like '%$str%' OR tbl_logbarang.namabarang like '%$str%' OR tbl_logbarang.satuan1 like '%$str%' OR tbl_logbarang.hargajual like '%$str%') order by tbl_logbarang.kodebarang $limm
			");
		} else {
			$query = $this->db->query("
			SELECT kodebarang AS id, 
			CONCAT(' 
				[ ', kodebarang ,' ] ',' - ',' 
				[ ', namabarang ,' ] ',' - ',' 
				[ ', satuan1 ,' ] ',' - ',' 
				[ ', hargabeli ,' ] 
			') AS TEXT 
			FROM tbl_barang 
			WHERE (kodebarang like '%$str%' OR namabarang like '%$str%' OR satuan1 like '%$str%' OR hargajual like '%$str%') order by kodebarang LIMIT 1
			");
		}
		return $query->result();
	}

	function getfarmasibarang_alkes($str, $gudang)
	{

		$unit = $this->session->userdata('unit');
		if ($str == "") {
			$limm = "LIMIT 10";
		} else {
			$limm = "";
		}

		if ($unit != "") {

			$query = $this->db->query("SELECT kodebarang as id, concat(' [ ', kodebarang ,' ] ',' - ',' [ ', namabarang ,' ] ',' - ',' [ ', satuan1 ,' ] ',' - ',' [ ', salakhir ,' ] ',' - ',' [ ', hargabeli ,' ]',' - ',' [ ', hargajual ,' ]') as text FROM(
			SELECT
			kodebarang,namabarang,satuan1,hargabeli, hargajual,
			IFNULL((select sum(saldoakhir)saldoakhir from tbl_barangstock b where koders='$unit' and b.kodebarang=a.kodebarang and b.gudang = '$gudang'),0)salakhir
			from tbl_barang a WHERE (kodebarang like '%$str%' or namabarang like '$str%') and icgroup='BR-4'
			)c
			order by kodebarang ");
		} else {

			$query = $this->db->query("SELECT kodebarang as id, concat('-- PILIH GUDANG DAHULU --') as text FROM(
				SELECT
				kodebarang,namabarang,satuan1,hargabeli,hargajual,
				IFNULL((select sum(saldoakhir)saldoakhir from tbl_barangstock b where koders='$unit' and b.kodebarang=a.kodebarang and b.gudang = '$gudang'),0)salakhir
				from tbl_barang a where (kodebarang like '%$str%' or namabarang like '$str%') and icgroup='BR-4'
				)c
				order by kodebarang LIMIT 1");
		}

		return $query->result();
	}

	function getfarmasibaranggud($str, $gudang)
	{
		//-- saya ganti --//
		$unit = $this->session->userdata('unit');
		if ($str == "") {
			$limm = "LIMIT 10";
		} else {
			$limm = "";
		}

		if ($unit != "" && $gudang != "" && $gudang != "null") {

			// $query = $this->db->query("SELECT kodebarang as id, concat(' [ ', kodebarang ,' ] ',' - ',' [ ', namabarang ,' ] ',' - ',' [ ', satuan1 ,' ] ',' - ',' [ ', salakhir ,' ] ',' - ',' [ ', hargabeli ,' ]',' - ',' [ ', hargajual ,' ]') as text FROM(
			// SELECT
			// kodebarang,namabarang,satuan1,hargabeli, hargajual,
			// IFNULL((select sum(saldoakhir)saldoakhir from tbl_barangstock b where koders='$unit' and gudang='$gudang' and b.kodebarang=a.kodebarang),0)salakhir
			// from tbl_barang a WHERE (kodebarang like '%$str%' or namabarang like '$str%')
			// )c
			// order by kodebarang $limm");

			$query	= $this->db->query("SELECT a.kodebarang AS id, CONCAT('[ ', a.kodebarang ,' ] - [ ', b.namabarang ,' ] - [ ', b.satuan1 ,' ] - [ ', FORMAT(a.saldoakhir, 0) ,' ] - [ ', FORMAT(b.hargabeli, 0) ,' ] - [ ', FORMAT(b.hargajual, 0) ,' ]') AS text 
			FROM tbl_barangstock AS a 
			LEFT JOIN tbl_barang AS b ON b.kodebarang = a.kodebarang 
			WHERE a.koders ='$unit' 
			AND a.gudang ='$gudang' 
			AND (b.namabarang LIKE '%$str%' OR b.kodebarang LIKE '%$str%') 
			AND b.namabarang IS NOT NULL 
			GROUP BY b.kodebarang ASC $limm");

			// $query	= $this->db->query('SELECT a.kodebarang AS id, CONCAT("[ ", a.kodebarang," ] - [ ", a.namabarang," ] - [", a.satuan1," ] - [ ", FORMAT(IFNULL((
			// 	SELECT SUM(saldoakhir)
			// 	FROM tbl_barangstock
			// 	WHERE koders = "'. $unit .'" AND gudang = "'. $gudang .'" AND kodebarang = a.kodebarang),0), 0)," ] - [ ", FORMAT(a.hargabeli, 0)," ] - [ ", FORMAT(a.hargajual, 0)," ]") AS TEXT
			// 	FROM tbl_barang AS a
			// 	LEFT JOIN tbl_barangstock AS b ON b.kodebarang = a.kodebarang
			// 	WHERE b.saldoakhir > 0 
			// 	AND a.kodebarang LIKE "%'. $str .'%" 
			// 	OR a.namabarang LIKE "%'. $str .'%"
			// 	ORDER BY a.kodebarang '. $limm);
			return $query->result();
		} else {

			$query = $this->db->query("SELECT kodebarang as id, concat('-- PILIH GUDANG DAHULU --') as text FROM(
				SELECT
				kodebarang,namabarang,satuan1,hargabeli,hargajual,
				IFNULL((select sum(saldoakhir)saldoakhir from tbl_barangstock b where koders='$unit' and gudang='' and b.kodebarang=a.kodebarang),0)salakhir
				from tbl_barang a where (kodebarang like '%$str%' or namabarang like '$str%') 
				)c
				order by kodebarang LIMIT 1");

			return $query->result();
		}

		// if($unit != ""){

		//   $query = $this->db->query("SELECT kodebarang as id, concat(kodebarang,' | ',namabarang,' | ',satuan1) as text from tbl_barang where (kodebarang  like '%$str%' or namabarang like '$str%') order by kodebarang");

		// } else {

		//   $query = $this->db->query("SELECT kodebarang as id, concat(kodebarang,' | ',namabarang,' | ',satuan1) as text from tbl_barang order by kodebarang");	

		// }
	}

	function getfarmasibaranggudso($str, $gudang)
	{
		$unit = $this->session->userdata('unit');

		// if($unit != "" && $gudang!="" && $gudang!="null"){

		// 	$query = $this->db->query("SELECT kodebarang as id, concat(' [ ', kodebarang ,' ] ',' - ',' [ ', namabarang ,' ] ',' - ',' [ ', satuan1 ,' ] ',' - ',' [ ', salakhir ,' ] ',' - ',' [ ', hargajual ,' ]') as text FROM(
		// 	  SELECT
		// 	  kodebarang,namabarang,satuan1,hargajual,
		// 	  IFNULL((select sum(saldoakhir)saldoakhir from tbl_barangstock b where koders='$unit' and gudang='$gudang' and b.kodebarang=a.kodebarang and saldoakhir > 0.00),0)salakhir
		// 	  from tbl_barang a where (kodebarang like '%$str%' or namabarang like '$str%') 
		// 	  )c
		// 	  order by kodebarang");

		//   } else {

		// 	  $query = $this->db->query("SELECT kodebarang as id, concat('-- PILIH GUDANG DAHULU --') as text FROM(
		// 		  SELECT
		// 		  kodebarang,namabarang,satuan1,hargajual,
		// 		  IFNULL((select sum(saldoakhir)saldoakhir from tbl_barangstock b where koders='$unit' and gudang='' and b.kodebarang=a.kodebarang),0)salakhir
		// 		  from tbl_barang a where (kodebarang like '%$str%' or namabarang like '$str%') 
		// 		  )c
		// 		  order by kodebarang LIMIT 1");	

		//   }
		// return $query->result();

		if ($gudang != "" && $gudang != null) {
			if ($str != "" || $str != null) {
				$query = $this->db->query("SELECT a.kodebarang AS id, CONCAT('[ ', a.kodebarang ,' ] - [ ', a.namabarang ,' ] - [ ', a.satuan1 ,' ] - [ ', REPLACE(FORMAT(b.saldoakhir, 0), '.00', '') ,' ] - [ Rp ', REPLACE(FORMAT(a.hargajual, 0), ',', '.') ,' ]') AS text 
				FROM tbl_barangstock AS b
				LEFT JOIN tbl_barang AS a ON a.kodebarang = b.kodebarang 
				WHERE (a.kodebarang LIKE '%$str%' OR a.namabarang LIKE '%$str%') 
				AND b.saldoakhir > 0 
				AND b.koders = '$unit' 
				AND b.gudang = '$gudang'");
			} else {
				$query = $this->db->query("SELECT a.kodebarang AS id, CONCAT('[ ', a.kodebarang ,' ] - [ ', a.namabarang ,' ] - [ ', a.satuan1 ,' ] - [ ', REPLACE(b.saldoakhir, '.00', '') ,' ] - [ Rp ', REPLACE(FORMAT(a.hargajual, 0), ',', '.') ,' ]') AS text 
				FROM tbl_barangstock AS b
				LEFT JOIN tbl_barang AS a ON a.kodebarang = b.kodebarang 
				WHERE b.saldoakhir > 0 
				AND b.koders = '$unit' 
				AND b.gudang = '$gudang'");
			}
		} else {
			$query = $this->db->query("SELECT CONCAT(0) AS id, CONCAT('--- PILIH GUDANG DAHULU ---') AS text 
			FROM tbl_barang
			LIMIT 1");
		}

		return $query->result();
	}



	function getfarmasipermohonan($str)
	{

		$unit = $this->session->userdata("unit");

		/*if(!empty($str)){
			$query = $this->db->query("SELECT mohonno AS id,  CONCAT(mohonno,' | ',tglmohon,' | ',keterangan) AS text 
			FROM tbl_apohmohon
			WHERE dari = '$dari'
			AND ke = '$ke'");
		} else {
			$query = $this->db->query("SELECT mohonno AS id,  CONCAT(mohonno,' | ',tglmohon,' | ',keterangan) AS text 
			FROM tbl_apohmohon
			WHERE mohonno LIKE '%$str%'
			AND dari = '$dari'
			AND ke = '$ke'
			order by mohonno");
		}*/

		if ($unit != "") {
			$query = $this->db->query("SELECT a.mohonno AS id, CONCAT(a.mohonno,' | ',REPLACE(a.tglmohon, ' 00:00:00', ''),' | ',a.keterangan) AS text
		  FROM tbl_apohmohon AS a
		  LEFT JOIN tbl_apohmove AS b ON b.mohonno = a.mohonno
		  WHERE a.mohonno LIKE '%$str%'
		  AND a.koders = '$unit' 
		  AND b.mohonno IS NULL 
		  ORDER BY a.mohonno");
		} else {
			$query = $this->db->query("SELECT a.mohonno AS id, CONCAT(a.mohonno,' | ',REPLACE(a.tglmohon, ' 00:00:00', ''),' | ',a.keterangan) AS text
		  FROM tbl_apohmohon AS a
		  LEFT JOIN tbl_apohmove AS b ON b.mohonno = a.mohonno
		  WHERE a.mohonno LIKE '%str%'
		  AND b.mohonno IS NULL 
		  ORDER BY a.mohonno");
		}
		return $query->result();
	}



	function getlogistikpermohonan($str)

	{

		$unit = $this->session->userdata('unit');



		// if($unit != ""){

		//   $query = $this->db->query("SELECT mohonno as id, concat(mohonno,' | ',tglmohon,' | ',keterangan) as text from tbl_apohmohonlog where (mohonno like '%$str%' and koders = '$unit') order by mohonno ");

		// } else {

		//   $query = $this->db->query("SELECT mohonno as id, concat(mohonno,' | ',tglmohon,' | ',keterangan) as text from tbl_apohmohonlog where (koders = '$unit')");

		// }

		if ($unit != "") {
			$query = $this->db->query("SELECT a.mohonno AS id, CONCAT(a.mohonno,' | ',REPLACE(a.tglmohon, ' 00:00:00', ''),' | ',a.keterangan) AS text
			FROM tbl_apohmohonlog AS a
			LEFT JOIN tbl_apohmovelog AS b ON b.mohonno = a.mohonno
			WHERE a.mohonno LIKE '%$str%'
			AND a.koders = '$unit' 
			AND b.mohonno IS NULL 
			ORDER BY a.mohonno");
		} else {
			$query = $this->db->query("SELECT a.mohonno AS id, CONCAT(a.mohonno,' | ',REPLACE(a.tglmohon, ' 00:00:00', ''),' | ',a.keterangan) AS text
			FROM tbl_apohmohonlog AS a
			LEFT JOIN tbl_apohmovelog AS b ON b.mohonno = a.mohonno
			WHERE a.mohonno LIKE '%str%'
			AND b.mohonno IS NULL 
			ORDER BY a.mohonno");
		}
		return $query->result();



		return $query->result();
	}


	function getlogbaranggud($str, $gudang)
	{
		$unit = $this->session->userdata('unit');
		if ($str == "") {
			$limm = "LIMIT 10";
		} else {
			$limm = "";
		}
		if ($unit != "" && $gudang != "" && $gudang != "null") {

			$query = $this->db->query("SELECT kodebarang as id, concat(' [ ', kodebarang ,' ] ',' - ',' [ ', namabarang ,' ] ',' - ',' [ ', satuan1 ,' ] ',' - ',' [ ', salakhir ,' ] ',' - ',' [ ', hargabelippn ,' ]') as text FROM(
			SELECT
			kodebarang,namabarang,satuan1,hargabelippn,
			IFNULL((select sum(saldoakhir)saldoakhir from tbl_apostocklog b where koders='$unit' and gudang='$gudang' and b.kodebarang=a.kodebarang),0)salakhir
			from tbl_logbarang a WHERE (kodebarang like '%$str%' or namabarang like '$str%')
			)c
			order by kodebarang $limm");
		} else {
			$query = $this->db->query("SELECT kodebarang as id, concat('-- PILIH GUDANG DAHULU --') as text FROM(
			SELECT
			kodebarang,namabarang,satuan1,hargabelippn,
			IFNULL((select sum(saldoakhir)saldoakhir from tbl_apostocklog b where koders='$unit' and gudang='$gudang' and b.kodebarang=a.kodebarang),0)salakhir
			from tbl_logbarang a where (kodebarang like '%$str%' or namabarang like '$str%') 
			)c
			order by kodebarang LIMIT 1");
		}
		return $query->result();
	}

	function getlogbarang($str)
	{
		$unit = $this->session->userdata('unit');
		if ($str == "") {
			$limm = "LIMIT 10";
		} else {
			$limm = "";
		}

		if ($unit != "") {
			//  $query = $this->db->query("SELECT a.kodebarang AS id,  CONCAT('[', a.kodebarang ,'] - [',a.namabarang,'] - [',a.satuan1,'] - [',IF(b.kodebarang IS NULL, 0, b.saldoakhir),'] - [',FORMAT(a.hargabelippn, 0),']') AS text FROM tbl_logbarang AS a LEFT JOIN tbl_apostocklog AS b ON b.kodebarang = a.kodebarang WHERE b.koders and a.kodebarang LIKE '%$str%' OR a.namabarang LIKE '%$str%' ORDER BY a.kodebarang");
			$query = $this->db->query("SELECT a.kodebarang AS id, CONCAT('[',a.kodebarang,'] - [',a.namabarang,'] - [',a.satuan1,'] - [',if(b.kodebarang is null, 0, b.saldoakhir),'] - [',FORMAT(a.hargabelippn, 0),']') as text FROM tbl_logbarang a JOIN tbl_apostocklog b ON a.kodebarang=b.kodebarang WHERE a.kodebarang LIKE '%$str%' OR a.namabarang LIKE '%$str%' $limm");
		} else {
			$query = $this->db->query("SELECT a.kodebarang AS id, CONCAT('[', a.kodebarang ,'] - [',a.namabarang,'] - [',a.satuan1,'] - [',IF(b.kodebarang IS NULL, 0, b.saldoakhir),'] - [',FORMAT(a.hargabelippn, 0),']') AS text 
		  FROM tbl_logbarang AS a 
		  LEFT JOIN tbl_apostocklog AS b ON b.kodebarang = a.kodebarang
		  ORDER BY a.kodebarang $limm");
		}
		return $query->result();
	}







	public function _nomorkasmasuk($cabang, $bulan, $tahun)

	{

		$query = "select ifnull(max(substr(notr,10,7)),0)+1 as no from tbl_kasmasuk 

		where koders = '$cabang' and year(tglkas)='$tahun'";

		$qdata = $this->db->query($query)->row();



		if ($qdata) {

			$nourut = $qdata->no;
		} else {

			$nourut = 1;
		}

		$nomor = 'KLM' . $tahun . $bulan . str_pad($nourut, 7, '0', STR_PAD_LEFT);

		return $nomor;
	}



	public function _nomorkaskeluar($cabang, $bulan, $tahun)

	{

		$query = "select ifnull(max(substr(bayarno,10,7)),0)+1 as no from tbl_hbayar 

		where koders = '$cabang' and year(tglbayar)='$tahun'";

		$qdata = $this->db->query($query)->row();



		if ($qdata) {

			$nourut = $qdata->no;
		} else {

			$nourut = 1;
		}

		$nomor = 'KLK' . $tahun . $bulan . str_pad($nourut, 7, '0', STR_PAD_LEFT);

		return $nomor;
	}





	public function _nomorregister($cabang, $tahun)

	{

		$query = "select ifnull(max(substr(noreg,5,8)),0)+1 as no from tbl_regist

		where koders = '$cabang' and year(tglmasuk)='$tahun'";

		$qdata = $this->db->query($query)->row();



		if ($qdata) {

			$nourut = $qdata->no;
		} else {

			$nourut = 1;
		}

		$nomor = $tahun . str_pad($nourut, 8, '0', STR_PAD_LEFT);

		return $nomor;
	}



	public function _nomorkasirum($cabang, $tahun)

	{

		$query = "select ifnull(max(substr(nokwitansi,6,8)),0)+1 as no from tbl_kasir

		where koders = '$cabang' and year(tglbayar)='$tahun'";

		$qdata = $this->db->query($query)->row();



		if ($qdata) {

			$nourut = $qdata->no;
		} else {

			$nourut = 1;
		}

		$nomor = $tahun . 'E' . str_pad($nourut, 8, '0', STR_PAD_LEFT);

		return $nomor;
	}


	public function penyebut($nilai)
	{
		$nilai = abs($nilai);
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = " " . $huruf[$nilai];
		} else if ($nilai < 20) {
			$temp = $this->M_global->penyebut($nilai - 10) . " belas";
		} else if ($nilai < 100) {
			$temp = $this->M_global->penyebut($nilai / 10) . " puluh" . $this->M_global->penyebut($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " seratus" . $this->M_global->penyebut($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = $this->M_global->penyebut($nilai / 100) . " ratus" . $this->M_global->penyebut($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu" . $this->M_global->penyebut($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = $this->M_global->penyebut($nilai / 1000) . " ribu" . $this->M_global->penyebut($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = $this->M_global->penyebut($nilai / 1000000) . " juta" . $this->M_global->penyebut($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = $this->M_global->penyebut($nilai / 1000000000) . " milyar" . $this->M_global->penyebut(fmod($nilai, 1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = $this->M_global->penyebut($nilai / 1000000000000) . " trilyun" . $this->M_global->penyebut(fmod($nilai, 1000000000000));
		}
		return $temp;
	}

	public function terbilang($nilai)
	{
		if ($nilai < 0) {
			$hasil = "minus " . trim($this->M_global->penyebut($nilai));
		} else {
			$hasil = trim($this->M_global->penyebut($nilai));
		}
		return $hasil;
	}


	public function getListVendor()
	{
		$query = "SELECT id, vendor_id, vendor_name
			FROM tbl_vendor
		";
		return $this->db->query($query)->result();
	}

	public function getListVendorById($id)
	{
		$query = "SELECT id, vendor_id, vendor_name
			FROM tbl_vendor
			WHERE vendor_id = '$id'
		";
		return $this->db->query($query)->result();
	}



	function getJenisFaktur($str)
	{
		$query = $this->db->query("SELECT id, nama as text from ms_jenis_faktur");
		return $query->result();
	}

	public function getProsentasePpn()
	{
		$query = $this->db->query("SELECT * FROM tbl_pajak where kodetax='PPN'");
		return $query->result();
	}

	public function getJenisPpn()
	{
		$arr = array(
			'exclude' => 'Exclude',
			'include' => 'Include',
		);
		return $arr;
	}
}