<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Poliklinik extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->session->set_userdata('menuapp', '2000');
		$this->session->set_userdata('submenuapp', '2502');
		$this->load->model('M_Poliklinik');
		$this->load->helper('simkeu_nota');
		$this->load->model('M_cetak');

	}
	
	public function index(){
		$cek = $this->session->userdata('username');
		$unit= $this->session->userdata('unit');
		if(!empty($cek))
		{
			$data=[
				$id = $this->uri->segment(3),
				$bulan  			= $this->M_global->_periodebulan(),
				$tahun 		    	= $this->M_global->_periodetahun(),
				$nbulan 			= $this->M_global->_namabulan($bulan),
				$periode			= 'Periode '.ucwords(strtolower($nbulan)).' '.$this->M_global->_periodetahun(),
				// 'listDokter'        => $this->M_Poliklinik->getListDokter(),
				// 'listKoders'		=> $this->M_Poliklinik->getListKoders(),
				// 'listKodePoli'		=> $this->M_Poliklinik->getListKodePoli(),
				// 'tglmasuk'			=> $this->M_Poliklinik->gettglmasuk(),
				'total_pasien'  	=> $this->M_Poliklinik->total_pasien(),
				'diperiksa_perawat' => $this->M_Poliklinik->diperiksa_perawat(),
				'diperiksa_dokter'  => $this->M_Poliklinik->diperiksa_dokter(),
				'periode'			=> $periode,
				// 'naDokter'			=> $this->M_Poliklinik->naDokter(),
				'menu'				=> 'e-HMS',
				'title'				=> 'Poliklinik',
				'vendor' 	    	=> '',
				// 'nadokter' => $this->M_Poliklinik->namapost(),
				'namapos' => $this->db->get('tbl_namapos')->result(),
				// 'dataDokter' => $this->M_Poliklinik->namapost(),

			];
			$this->load->view('poliklinik/v_poliklinik', $data);
		} else
		{
			header('location:'.base_url());
		}
	}

	public function entri(){
		$cek = $this->session->userdata('username');
		if(!empty($cek))
		{
			$this->load->view('poliklinik/v_kasirum_add');
		} else
		{
			header('location:'.base_url());
		}
	}

	public function cariNotorisasi($no){
		echo $no;
	}

	public function edit( $nokwitansi ){
		$cek = $this->session->userdata('username');
		if(!empty($cek))
		{
			$data['id'] = $nokwitansi;
			$data['datas'] = $this->db->query("select tbl_kasir.*, tbl_pasien.namapas
			from tbl_kasir inner join tbl_pasien on tbl_kasir.rekmed=tbl_pasien.rekmed
			where tbl_kasir.nokwitansi = '$nokwitansi'")->result();
			// echo "<pre>";
			// print_r($data);
			// die;

			$this->load->view('poliklinik/v_kasirum_edit', $data);
		} else
		{
			header('location:'.base_url());
		}
	}

	public function ajax_list( $param ){
		$cabang	= $this->session->userdata("unit");
		$dat   = explode("~",$param);
		if($dat[0]==1){
			$bulan       = date('m');
			$tahun       = date('Y');
			$poli        = '';
			$kodokter    = '';
			$list = $this->M_Poliklinik->get_datatables( 1, $bulan, $tahun,$poli,$kodokter );
		} else {
			$bulan       = date('Y-m-d',strtotime($dat[1]));
			$tahun       = date('Y-m-d',strtotime($dat[2]));
			$poli        = ($dat[3]);
			$kodokter    = ($dat[4]);
		    $list = $this->M_Poliklinik->get_datatables( 2, $bulan, $tahun,$poli,$kodokter );
		}


		$data = array();
		$no = $_POST['start'];
		$jenisbayar = array('','Cash','Credit Card','Debet Card','Transfer','Online');

		foreach ($list as $unit) {
			
			$query_cek_kasir	= $this->db->query("SELECT * FROM tbl_kasir WHERE noreg = '$unit->noreg' AND koders = '$cabang'")->num_rows();
			$status_kasir		= ($query_cek_kasir == 0)? "<span class='btn btn-primary btn-sm'>Open</span>" : "<span class='btn btn-danger btn-sm'>Close</span>";
			
			if($unit->diperiksa_perawat=="1"){
				// $periksa_perawat="<td><input type='checkbox' checked='checked'></td>";
			}else{
				// $periksa_perawat="<td><input type='checkbox' disabled='disabled'></td>";
			}

			if($unit->diperiksa_dokter=="1"){
				// $periksa_dokter="<td><input type='checkbox' checked='checked'></td>";
			}else{
				// $periksa_dokter="<td><input type='checkbox' disabled='disabled'></td>";
			}

			if($unit->jkel=="P"){
				$jkell='Pria';
			}else{
				$jkell='Wanita';
			}

			if($unit->jenispas=="PAS1"){
				$jpas='UMUM';
			}else{
				$jpas=$unit->cust_nama;
			}

			$no++;
			$row = array();
			$row[] = '
			<i class="fa fa-solid fa-address-card" onclick="add_list('."'".$unit->noreg."'".');" data-toggle="modal"></i>';
			// $row[] = $periksa_perawat;
			// $row[] = $periksa_dokter;
			$row[] = $unit->antrino. '<button type="submit">call</button>';
			$row[] = $status_kasir;
			$row[] = $unit->noreg;
			$row[] = $unit->rekmed;
			$row[] = $unit->tglmasuk;
			$row[] = $unit->namapasien_lengkap. '&nbsp;'. $jkell;
			$row[] = $unit->namapost;
			$row[] = $unit->nadokter;
			$row[] = $jpas;
						//saya mengganti line 120 semula $unit->id


			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_Poliklinik->count_all( $dat[0], $bulan, $tahun ,$poli,$kodokter),
						"recordsFiltered" => $this->M_Poliklinik->count_filtered( $dat[0], $bulan, $tahun ,$poli,$kodokter),
						"data" => $data,

				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_add_per($param){
		$cabang       	= $this->session->userdata('unit');
		$userid       	= $this->session->userdata('username');
		$noreg_per    	= $this->input->post('noreg_per');
		$rekmed_per   	= $this->input->post('rekmed_per');

		$pfisik			= "Nadi ". $this->input->post("nadi") ."/Menit, Pernafasan ". $this->input->post("nafas") ."/Menit, SPO2 ". $this->input->post("oksi") ."%, Suhu ". $this->input->post("suhu") ."° Celcius, Tekanan Darah ". $this->input->post("tekanan") ." / ". $this->input->post("tekanan1") .", Berat Badan". $this->input->post("berat") ." kg, Tinggi Badan". $this->input->post("tinggi") ." cm, BMI ". $this->input->post("bmi") ." - ". $this->input->post("bmi_result");

		$data = array(
			'koders'       	=> $cabang,
			'noreg'        	=> $this->input->post('noreg_per'),
			'rekmed'       	=> $this->input->post('rekmed_per'),
			'tglperiksa'   	=> $this->input->post('tgl_per'),
			'tglkonsul'    	=> $this->input->post('tgl_per'),
			'kodepos'      	=> $this->input->post('poli_per'),
			'keluhanawal'  	=> $this->input->post('kelawal_per'),
			'nadi'         	=> $this->input->post('nadi'),
			'tinggibadan'  	=> $this->input->post('tinggi'),
			'beratbadan'   	=> $this->input->post('berat'),
			'nafas'        	=> $this->input->post('nafas'),
			'oksigen'      	=> $this->input->post('oksi'),
			'bmi'          	=> $this->input->post('bmi'),
			'bmiresult'    	=> $this->input->post('bmi_result'),
			'suhu'         	=> $this->input->post('suhu'),
			'tdarah'       	=> $this->input->post('tekanan'),
			'tdarah1'      	=> $this->input->post('tekanan1'),
			'dead'         	=> $this->input->post('doa_hidden'),
			'pfisik'       	=> $pfisik,
			'tglkeluar'    	=> date('Y-m-d'),
			'jam'          	=> date('H:i:s'),
			'alergi'		=> $this->input->post('alergi_per'),

			// 'coba'  => $this->input->post('tgl_l_per'),
			// 'coba'  => $this->input->post('umur'),
			// 'coba'  => $this->input->post('jenkel_per'),
			// 'coba'  => $this->input->post('alergi_per'),

		);

		if(!empty($userid)){
			$alergi	= $this->input->post("alergi_per");

			$this->db->query("UPDATE tbl_pasien SET alergi = '$alergi' WHERE rekmed = '$rekmed_per' AND koders = '$cabang'");

			$qcek = $this->db->query("SELECT * FROM tbl_rekammedisrs WHERE noreg = '$noreg_per' and rekmed = '$rekmed_per' and koders='$cabang'")->result_array();
			$cek = count($qcek);

			if ($cek > 0) {

				$update = $this->db->update('tbl_rekammedisrs',$data,
				array(
					'noreg'   => $noreg_per,
					'rekmed'  => $rekmed_per,
					'koders'  => $cabang
				));
				
				history_log(0 ,'POLI_PERIKSA_PERAWAT' ,'EDIT' ,$noreg_per ,'-');
				echo json_encode(array("status" => "1","nomor" => $noreg_per));

			}else{

				$insert = $this->db->insert('tbl_rekammedisrs',$data);
				history_log(0 ,'POLI_PERIKSA_PERAWAT' ,'ADD' ,$noreg_per ,'-');
				echo json_encode(array("status" =>"2","nomor" => $noreg_per));
			}


		}else{
			header('location:'.base_url());
		}

	}

	public function ajax_add_dokter($param=""){
		$tanggal	  	= date("Y-m-d H:i:s");
		$cabang       	= $this->session->userdata('unit');
		$userid       	= $this->session->userdata('username');
		$noreg_dok    	= $this->input->post('noreg_dok');
		$rekmed_dok   	= $this->input->post('rekmed_dok');
		$poli_dok     	= $this->input->post('poli_dok');
		$gudang			= $this->input->post("gudang");
		$orderno		= $this->input->post("noeresephide");
		$gudang_bhp		= $this->input->post("gudang_bhp");

		$data_pasien_d	= $this->db->query("SELECT * FROM pasien_rajal WHERE noreg = '$noreg_dok' AND koders = '$cabang'")->row();

		$datarekmed = array(
			'koders'       	=> $cabang,
			'noreg'        	=> $noreg_dok,
			'rekmed'       	=> $rekmed_dok,
			'tglperiksa'   	=> $this->input->post('tgl_dok'),
			'tglkonsul'    	=> $this->input->post('tgl_dok'),
			'kodepos'      	=> $poli_dok,
			'keluhanawal'  	=> $this->input->post('kelawal'),
			'pfisik'       	=> $this->input->post('pemeriksaan'),
			'diagnosa'     	=> $this->input->post('diagmas'),
			'resep'        	=> $this->input->post('teresep'),
			'kodokter'		=> $this->input->post('selectdr'),
			'diagu'        	=> $this->input->post('diagnosa'),
			'tindu'        	=> $this->input->post('tindu'),
			'anjuran'      	=> $this->input->post('anjuran'),
			'tglkeluar'    	=> date('Y-m-d'),
			'jam'          	=> date('H:i:s'),
			'nyeri'			=> $this->input->post('ceknyeri'),
			'alergi'		=> $this->input->post('alergi_dok'),
			'ijinsakit'		=> $this->input->post('sakitselama'),
			'ijindari'		=> $this->input->post('ijindari'),
			'ijinsampai'	=> $this->input->post('ijinsampai'),
			'butawarna'		=> $this->input->post('butawarna'),
			'sehat'			=> $this->input->post('sehat'),
			'ketsehat'		=> $this->input->post('ketsehat'),
			'ketsehatuntuk'	=> $this->input->post('ketsehatuntuk')
		);

		$datahpoli = array(
			'koders'       => $cabang,
			'noreg'        => $noreg_dok,
			'rekmed'       => $rekmed_dok,
			'tglperiksa'   => $this->input->post('tgl_dok'),
			'jam'          => $this->input->post('jam'),
			'username'     => $userid,
			'shipt'        => 0,
			'plcounter'    => 0,
			'jam'          => date('H:i:s'),
		);

		if(!empty($userid)){
			$alergi	= $this->input->post("alergi_dok");

			$this->db->query("UPDATE tbl_pasien SET alergi = '$alergi' WHERE rekmed = '$rekmed_dok' AND koders = '$cabang'");

			//-- Diagnosa ICD --//
			$c_jenis_diag    = $this->input->post('jenis_diag');
			$c_diag          = $this->input->post('diag');
			$c_utama         = $this->input->post('utama_hide');

			$jumdata = count($c_diag);
			
			for($i=0;$i<=$jumdata-1;$i++){
				$data_icdtr = array(
					'koders'   => $cabang,
					'noreg'    => $noreg_dok,
					'rekmed'   => $rekmed_dok,
					'icdcode'  => $c_diag[$i],
					'kelompok' => $c_jenis_diag[$i],
					'utama'    => $c_utama[$i],
				);

				if($c_diag[$i]!=""){

					$qcek = $this->db->query("SELECT * FROM tbl_icdtr WHERE noreg = '$noreg_dok' and icdcode = '$c_diag[$i]' and koders='$cabang'")->result_array();
					$cek = count($qcek);

					if ($cek > 0) {
						// $update = $this->db->update('tbl_icdtr',$data_icdtr,
						// array(
						// 	'noreg'   => $noreg_dok,
						// 	'koders'  => $cabang,
						// 	'icdcode' => $c_diag[$i],
						// ));
						$delicd = $this->db->query("DELETE from tbl_icdtr WHERE noreg = '$noreg_dok' and koders='$cabang' /*and icdcode = '$c_diag[$i]'*/ ");

						if($delicd){
							$this->db->insert('tbl_icdtr',$data_icdtr);
						}

					}else{
						$insert_detil = $this->db->insert('tbl_icdtr',$data_icdtr);

					}
				}
			}

			//-- Billing --//
			$kodetarif = $this->input->post('kode');
			$harga     = $this->input->post('hrg');
			$dokter    = $this->input->post('dokter');
			$perawat   = $this->input->post('paramedis');

			$this->db->query("DELETE from tbl_dpoli WHERE noreg = '$noreg_dok' and koders='$cabang'");

			$jumdatabilling = count($kodetarif);
			for($i=0;$i<=$jumdatabilling-1;$i++){
				$_harga   = str_replace(',','',$harga[$i]);

				if($dokter[$i]){
					$_dokter= $dokter[$i];
				} else {
					$_dokter= '';
				}

				if($perawat[$i]){
					$_perawat= $perawat[$i];
				} else {
					$_perawat= '';
				}

				$datatarif = $this->db->query("SELECT *	from daftar_tarif_nonbedah where daftar_tarif_nonbedah.koders='$cabang' and daftar_tarif_nonbedah.kodetarif ='$kodetarif[$i]'")->row();

				$data_rinci = array(
					'clientid'    	=> $cabang,
					'koders'      	=> $cabang,
					'noreg'       	=> $noreg_dok,
					'pos'         	=> $poli_dok,
					'kodokter'    	=> $_dokter,
					'kodetarif'   	=> $kodetarif[$i],
					'qty'	      	=> 1,
					'tarifrs'     	=> $datatarif->tarifrspoli,
					'tarifdr'     	=> $datatarif->tarifdrpoli,
					'paramedis'		=> $datatarif->feemedispoli,
					'obatpoli'		=> $datatarif->bhppoli,
					// 'obatpoli'		=> $datatarif->obatpoli,
					'bahan'       	=> 0,
					'koperawat'   	=> $_perawat,
				);


				if($kodetarif[$i]!=""){

					// $qcek = $this->db->query("SELECT * FROM tbl_dpoli WHERE noreg = '$noreg_dok' /*and kodetarif = '$kodetarif[$i]'*/ and koders='$cabang'")->result_array();
					// $cek = count($qcek);
					$insert_detil = $this->db->insert('tbl_dpoli',$data_rinci);

					// if ($cek > 0) {
					// 	// $update = $this->db->update('tbl_dpoli',$data_rinci,
					// 	// array(
					// 	// 	'noreg'   => $noreg_dok,
					// 	// 	'koders'  => $cabang,
					// 	// 	'kodetarif'  => $kodetarif[$i],
					// 	// ));

					// 	$delbill = $this->db->query("DELETE from tbl_dpoli WHERE noreg = '$noreg_dok' /*and kodetarif = '$kodetarif[$i]'*/ and koders='$cabang'");
					// 	if($delbill){
					// 		$insert_detil = $this->db->insert('tbl_dpoli',$data_rinci);
					// 	}

					// }else{
					// 	$insert_detil = $this->db->insert('tbl_dpoli',$data_rinci);
					// }
				}
			}

			// -- ALKES --//
			$c_bbn       = $this->input->post('bbn_hide');
			$c_kdalkes   = $this->input->post('kdalkes');
			$c_satalkes  = $this->input->post('satalkes');
			$c_qtyalkes  = $this->input->post('qtyalkes');
			$c_hrgalkes  = $this->input->post('hrgalkes');
			$c_totalkes  = $this->input->post('totalkes');

			$this->db->query("DELETE from tbl_alkestransaksi WHERE notr = '$noreg_dok' and koders='$cabang'");

			$jumdataalkes = count($c_kdalkes);
			for($i=0;$i<=$jumdataalkes-1;$i++){
				$_c_hrgalkes   = str_replace(',','',$c_hrgalkes[$i]);
				$_c_totalkes   = str_replace(',','',$c_totalkes[$i]);

				$data_alkes = array(
					'koders'       => $cabang,
					'notr'         => $noreg_dok,
					'kodeobat'     => $c_kdalkes[$i],
					'qty'          => $c_qtyalkes[$i],
					'satuan'       => $c_satalkes[$i],
					'harga'        => $_c_hrgalkes,
					'totalharga'   => $_c_totalkes,
					'tgltransaksi' => $tanggal,
					'gudang'	   => $gudang_bhp,
					'dibebankan'   => $c_bbn[$i],
				);

				if($c_kdalkes[$i]!=""){
					// Lose Stock
					$check_stock	= $this->db->query("SELECT * FROM tbl_barangstock WHERE kodebarang = '". $c_kdalkes[$i] ."' AND koders = '$cabang' AND gudang = '$gudang_bhp'");

					if($check_stock->num_rows() == 0){
						$this->db->query("INSERT INTO tbl_barangstock (koders,kodebarang,gudang,keluar,saldoakhir) 
						VALUES ('$cabang', '". $c_kdalkes[$i] ."', '$gudang_bhp', '". $c_qtyalkes[$i] ."', '". $c_qtyalkes[$i] ."')");
					} else {
						$this->db->query("UPDATE tbl_barangstock SET keluar = keluar+". $c_qtyalkes[$i] .", saldoakhir = saldoakhir-". $c_qtyalkes[$i] ." WHERE kodebarang = '". $c_kdalkes[$i] ."' AND koders = '$cabang'  AND gudang = '$gudang_bhp'");
					}

					// $this->db->query("UPDATE tbl_barangstock SET keluar = keluar+". $c_qtyalkes[$i] .", saldoakhir = saldoakhir-". $c_qtyalkes[$i] ." WHERE kodebarang = '". $c_kdalkes[$i] ."' AND koders = '$cabang'  AND gudang = '$gudang_bhp'");

					$insert_detil = $this->db->insert('tbl_alkestransaksi',$data_alkes);
				}

			}

			// -- ERESEP -- //
			$er_kronishide		= $this->input->post("kronis_hide");
			$er_obat			= $this->input->post("obat_terapi");
			$er_satuan			= $this->input->post("satuan_ot");
			$er_jmlhobathari	= $this->input->post("jml_obat_hari");
			$er_qtymin			= $this->input->post("qty_minumum");
			$er_jmlhari			= $this->input->post("jml_hari");
			$er_jmlobat			= $this->input->post("jml_obat");
			$er_aturan			= $this->input->post("autran_pakai");
			$er_harga			= $this->input->post("harga_ot");
			$er_totalharga		= $this->input->post("totalharga_ot");
			$er_keterangan	 	= $this->input->post("keterangan");
			// $er_validfar		= $this->input->post("valid_farmasi");

			$check_eresep	= $this->db->query("SELECT * FROM tbl_eresep WHERE noreg = '$noreg_dok' AND koders = '$cabang'")->num_rows();
			if($check_eresep == 0){
				urut_transaksi("ERESEP", 20);
			}

			$this->db->query("DELETE from tbl_eresep WHERE noreg = '$noreg_dok' and koders='$cabang'");
			$jumdataer			= count($er_obat);
			for($er=0;$er<=$jumdataer-1;$er++){
				$nama_obat		= data_master("tbl_barang", array("kodebarang" => $er_obat[$er]))->namabarang;
				$totalharga		= str_replace(",", "", $er_totalharga[$er]);

				$data_eresep_add	= array(
					"koders"		=> $cabang,
					"noreg"			=> $noreg_dok,
					"orderno"		=> $orderno,
					"kodeobat"		=> $er_obat[$er],
					"namaobat"		=> $nama_obat,
					"satuan"		=> $er_satuan[$er],
					"qty"			=> $er_jmlobat[$er],
					"qty_perhari"	=> $er_jmlhobathari[$er],
					"jml_hari"		=> $er_jmlhari[$er],
					"qty_minum"		=> $er_qtymin[$er],
					"harga"			=> $er_harga[$er],
					"totalharga"	=> $totalharga,
					"aturanpakai"	=> $er_aturan[$er],
					"gudang"		=> $gudang,
					"resepno"		=> $orderno,
					"namauser"		=> $userid,
					"qtyawal"		=> $er_jmlobat[$er],
					"keterangan"	=> $er_keterangan[$er],
					"kronis"		=> $er_kronishide[$er],
					// "validfarmasi"	=> $er_validfar[$er],
				);

				$this->db->insert("tbl_eresep", $data_eresep_add);
			}

			// -- RACIKAN -- //
			$ra_kodeobat1			= $this->input->post("r1nama_obat");
			$ra_kodeobat2			= $this->input->post("r2nama_obat");
			$ra_kodeobat3			= $this->input->post("r3nama_obat");
			$ra_satuan1				= $this->input->post("r1satuan");
			$ra_satuan2				= $this->input->post("r2satuan");
			$ra_satuan3				= $this->input->post("r3satuan");
			$ra_qtyracik1			= $this->input->post("r1qtyracik");
			$ra_qtyracik2			= $this->input->post("r2qtyracik");
			$ra_qtyracik3			= $this->input->post("r3qtyracik");
			$ra_qtyjual1			= $this->input->post("r1qtyjual");
			$ra_qtyjual2			= $this->input->post("r2qtyjual");
			$ra_qtyjual3			= $this->input->post("r3qtyjual");
			$ra_harga1				= $this->input->post("r1harga");
			$ra_harga2				= $this->input->post("r2harga");
			$ra_harga3				= $this->input->post("r3harga");
			$ra_totalharga1			= $this->input->post("r1totalharga");
			$ra_totalharga2			= $this->input->post("r2totalharga");
			$ra_totalharga3			= $this->input->post("r3totalharga");
			$ra_aturanpakai1		= $this->input->post("aturan_pakai1");
			$ra_aturanpakai2		= $this->input->post("aturan_pakai2");
			$ra_aturanpakai3		= $this->input->post("aturan_pakai3");

			$this->db->query("DELETE FROM tbl_eracik WHERE racikid = 'RACIK1' AND noreg = '$noreg_dok' AND koders = '$cabang'");
			$this->db->query("DELETE FROM tbl_eracik WHERE racikid = 'RACIK2' AND noreg = '$noreg_dok' AND koders = '$cabang'");
			$this->db->query("DELETE FROM tbl_eracik WHERE racikid = 'RACIK3' AND noreg = '$noreg_dok' AND koders = '$cabang'");

			$jumracik1	= count($ra_kodeobat1);
			for($ra1 = 0; $ra1 <= $jumracik1-1; $ra1++){
				$nama_obat_ra1	= data_master("tbl_barang", array("kodebarang" => $ra_kodeobat1[$ra1]))->namabarang;
				$harga_ra1		= str_replace(",", "", $ra_harga1[$ra1]);
				$totalharga_ra1 = str_replace(",", "", $ra_totalharga1[$ra1]);

				$data_racik1	= array(
					"koders"		=> $cabang,
					"orderno"		=> $orderno,
					"racikid"		=> "RACIK1",
					"noreg"			=> $noreg_dok,
					"kodeobat"		=> $ra_kodeobat1[$ra1],
					"namaobat"		=> $nama_obat_ra1,
					"satuan"		=> $ra_satuan1[$ra1],
					"qty_racik"		=> $ra_qtyracik1[$ra1],
					"qty_jual"		=> $ra_qtyjual1[$ra1],
					"harga"			=> $harga_ra1,
					"totalharga"	=> $totalharga_ra1,
					"aturanpakai"	=> $ra_aturanpakai1,
					"proses"		=> 1,
				);

				$this->db->insert("tbl_eracik", $data_racik1);
			}

			$jumracik2	= count($ra_kodeobat2);
			for($ra2 = 0; $ra2 <= $jumracik2-1; $ra2++){
				$nama_obat_ra2	= data_master("tbl_barang", array("kodebarang" => $ra_kodeobat2[$ra2]))->namabarang;
				$harga_ra2		= str_replace(",", "", $ra_harga2[$ra2]);
				$totalharga_ra2 = str_replace(",", "", $ra_totalharga2[$ra2]);

				$data_racik2	= array(
					"koders"		=> $cabang,
					"orderno"		=> $orderno,
					"racikid"		=> "RACIK2",
					"noreg"			=> $noreg_dok,
					"kodeobat"		=> $ra_kodeobat2[$ra2],
					"namaobat"		=> $nama_obat_ra2,
					"satuan"		=> $ra_satuan2[$ra2],
					"qty_racik"		=> $ra_qtyracik2[$ra2],
					"qty_jual"		=> $ra_qtyjual2[$ra2],
					"harga"			=> $harga_ra2,
					"totalharga"	=> $totalharga_ra2,
					"aturanpakai"	=> $ra_aturanpakai2,
					"proses"		=> 1,
				);

				$query_insert_racik = $this->db->insert("tbl_eracik", $data_racik2);
			}

			$jumracik3	= count($ra_kodeobat3);
			for($ra3 = 0; $ra3 <= $jumracik3-1; $ra3++){
				$nama_obat_ra3	= data_master("tbl_barang", array("kodebarang" => $ra_kodeobat3[$ra3]))->namabarang;
				$harga_ra3		= str_replace(",", "", $ra_harga3[$ra3]);
				$totalharga_ra3 = str_replace(",", "", $ra_totalharga3[$ra3]);

				$data_racik3	= array(
					"koders"		=> $cabang,
					"orderno"		=> $orderno,
					"racikid"		=> "RACIK3",
					"noreg"			=> $noreg_dok,
					"kodeobat"		=> $ra_kodeobat3[$ra3],
					"namaobat"		=> $nama_obat_ra3,
					"satuan"		=> $ra_satuan3[$ra3],
					"qty_racik"		=> $ra_qtyracik3[$ra3],
					"qty_jual"		=> $ra_qtyjual3[$ra3],
					"harga"			=> $harga_ra3,
					"totalharga"	=> $totalharga_ra3,
					"aturanpakai"	=> $ra_aturanpakai3,
					"proses"		=> 1,
				);

				$query_insert_racik = $this->db->insert("tbl_eracik", $data_racik3);
			}

			// -- ORDER PERIKSA -- //

			$opresepno		= $this->input->post("noeresephide");
			$opdok			= $this->input->post("doktereresep");
			$opgud			= $this->input->post("gudang");
			$opracik1		= $this->input->post("nama_racik1");
			$opqtyjadi1		= $this->input->post("qty_jadi1");
			$opaturan1		= $this->input->post("aturan_pakai1");
			$opmanual1	   	= $this->input->post("r1manualracik");
			$opracik2		= $this->input->post("nama_racik2");
			$opqtyjadi2		= $this->input->post("qty_jadi2");
			$opaturan2		= $this->input->post("aturan_pakai2");
			$opmanual2	   	= $this->input->post("r2manualracik");
			$opracik3		= $this->input->post("nama_racik3");
			$opqtyjadi3		= $this->input->post("qty_jadi3");
			$opaturan3		= $this->input->post("aturan_pakai3");
			$opmanual3	  	= $this->input->post("r3manualracik");

			$data_op_add	= array(
				"koders"			=> $cabang,
				"orderno"			=> $opresepno,
				"noreg"				=> $noreg_dok,
				"rekmed"			=> $rekmed_dok,
				"tglorder"			=> date("Y-m-d"),
				"proses"			=> "proses",
				"jamorder"			=> date("H:i:s"),
				"kodokter"			=> $opdok,
				"resep"				=> 1,
				"resepok"			=> 0,
				"username"			=> $userid,
				"gudang"			=> $opgud,
				"racik1"			=> $opracik1,
				"qtyjadi_racik1"	=> $opqtyjadi1,
				"aturan_pakai_racik1"	=> $opaturan1,
				"manual_racik1"		=> $opmanual1,
				"racik2"			=> $opracik2,
				"qtyjadi_racik2"	=> $opqtyjadi2,
				"aturan_pakai_racik2"	=> $opaturan2,
				"manual_racik2"		=> $opmanual2,
				"racik3"			=> $opracik3,
				"qtyjadi_racik3"	=> $opqtyjadi3,
				"aturan_pakai_racik3"	=> $opaturan3,
				"manual_racik3"		=> $opmanual3,
			);

			$data_op_edit	= array(
				"tglorder"			=> date("Y-m-d"),
				"proses"			=> "proses",
				"jamorder"			=> date("H:i:s"),
				"kodokter"			=> $opdok,
				"username"			=> $userid,
				"gudang"			=> $opgud,
				"racik1"			=> $opracik1,
				"qtyjadi_racik1"	=> $opqtyjadi1,
				"aturan_pakai_racik1"	=> $opaturan1,
				"manual_racik1"		=> $opmanual1,
				"racik2"			=> $opracik2,
				"qtyjadi_racik2"	=> $opqtyjadi2,
				"aturan_pakai_racik2"	=> $opaturan2,
				"manual_racik2"		=> $opmanual2,
				"racik3"			=> $opracik3,
				"qtyjadi_racik3"	=> $opqtyjadi3,
				"aturan_pakai_racik3"	=> $opaturan3,
				"manual_racik3"		=> $opmanual3,
			);

			if(!empty($er_obat)){
				$order_check	= $this->db->query("SELECT * FROM tbl_orderperiksa WHERE noreg = '$noreg_dok'");
				if($order_check->num_rows() == 0){
					$this->db->insert('tbl_orderperiksa', $data_op_add);
				} else {
					$this->db->update('tbl_orderperiksa', $data_op_edit, array("orderno" => $orderno,"noreg" => $noreg_dok, "koders" => $cabang));
				}
			}

			// -- HEADER -- //

			if ($param == 0) {
				/* tbl_rekammedisrs */
				$update = $this->db->update('tbl_rekammedisrs',$datarekmed,
				array(
					'noreg'   => $noreg_dok,
					'rekmed'  => $rekmed_dok,
					'koders'  => $cabang
				));

				/* tbl_hpoli */
				$update = $this->db->update('tbl_hpoli',$datahpoli,
				array(
					'noreg'   => $noreg_dok,
					'rekmed'  => $rekmed_dok,
					'koders'  => $cabang
				));

				history_log(0 ,'POLI_DOKTER_SOAP_RESEP' ,'EDIT' ,$noreg_dok ,'-');
				echo json_encode(array("status" => "0","nomor" => $noreg_dok));
			} else {

				$insert = $this->db->insert('tbl_rekammedisrs',$datarekmed);
				$insert = $this->db->insert('tbl_hpoli',$datahpoli);

				history_log(0 ,'POLI_DOKTER_SOAP_RESEP' ,'ADD' ,$noreg_dok ,'-');
				echo json_encode(array("status" =>"1","nomor" => $noreg_dok));
			}


		}else{
			header('location:'.base_url());
		}
	}

	public function add_suket($param_type){
		if($param_type == "sakit" || $param_type == "sehat"){
			$suket_noreg	= $this->input->post("noreg");
			$suket_rekmed	= $this->input->post("rekmed");
			$suket_koders	= $this->input->post("koders");
			$suket_tglprks	= $this->input->post("koders");
			$suket_kodokter	= $this->input->post("kodokter");
			$suket_sakit	= $this->input->post("ijinsakit");
			$suket_dari		= $this->input->post("ijindari");
			$suket_sampai	= $this->input->post("ijinsampai");

			$data_insert	= array(
				"koders"		=> $suket_koders,
				"noreg"			=> $suket_noreg,
				"rekmed"		=> $suket_rekmed,
				"tglperiksa"	=> $suket_tglprks,
				"kodokter"		=> $suket_kodokter,
				"ijinsakit"		=> $suket_sakit,
				"ijindari"		=> $suket_dari,
				"ijinsampai"	=> $suket_sampai,
			);

			$data_update	= array(
				"kodokter"		=> $suket_kodokter,
				"ijinsakit"		=> $suket_sakit,
				"ijindari"		=> $suket_dari,
				"ijinsampai"	=> $suket_sampai,
			);

			if($param_type == "sakit"){
				$cek_rekmed	= $this->db->query("SELECT * FROM tbl_rekammedisrs WHERE noreg = '$suket_noreg' AND koders = '$suket_koders'");
				if(empty($cek_rekmed)){
					$query_suket	= $this->db->insert("tbl_rekammedisrs", $data_insert);
				} else {
					$query_suket	= $this->db->update("tbl_rekammedisrs", $data_update, array("koders" => $suket_koders, "noreg" => $suket_noreg));
				}

				if($query_suket){
					echo json_encode(array("status" => "success"));
				} else {
					echo json_encode(array("status" => "failed"));
				}
			} else 
			if($param_type == "sehat"){
				$suket_noreg			= $this->input->post("noreg");
				$suket_rekmed			= $this->input->post("rekmed");
				$suket_koders			= $this->input->post("koders");
				$suket_tglprks			= $this->input->post("koders");
				$suket_kodokter			= $this->input->post("kodokter");
				$suket_butawarna		= $this->input->post("butawarna");
				$suket_sehat			= $this->input->post("sehat");
				$suket_ketsehat			= $this->input->post("ketsehat");
				$suket_ketsehatuntuk	= $this->input->post("ketsehatuntuk");

				$data_insert	= array(
					"koders"		=> $suket_koders,
					"noreg"			=> $suket_noreg,
					"rekmed"		=> $suket_rekmed,
					"tglperiksa"	=> $suket_tglprks,
					"kodokter"		=> $suket_kodokter,
					"butawarna"		=> $suket_butawarna,
					"sehat"			=> $suket_sehat,
					"ketsehat"		=> $suket_ketsehat,
					"ketsehatuntuk"	=> $suket_ketsehatuntuk,
				);

				$data_update	= array(
					"kodokter"		=> $suket_kodokter,
					"butawarna"		=> $suket_butawarna,
					"sehat"			=> $suket_sehat,
					"ketsehat"		=> $suket_ketsehat,
					"ketsehatuntuk"	=> $suket_ketsehatuntuk,
				);

				$cek_rekmed	= $this->db->query("SELECT * FROM tbl_rekammedisrs WHERE noreg = '$suket_noreg' AND koders = '$suket_koders'");
				if(empty($cek_rekmed)){
					$query_suket	= $this->db->insert("tbl_rekammedisrs", $data_insert);
				} else {
					$query_suket	= $this->db->update("tbl_rekammedisrs", $data_update, array("koders" => $suket_koders, "noreg" => $suket_noreg));
				}

				if($query_suket){
					echo json_encode(array("status" => "success"));
				} else {
					echo json_encode(array("status" => "failed"));
				}
			}
		} else {
			echo json_encode(array("status" => "type"));
		}
	}

	public function add_elab($noreg, $rekmed, $kodokter, $kodepos){
		$unit		= $this->session->userdata("unit");
		$orderno	= $this->input->post("elab_no");
		$tanggal	= $this->input->post("elab_tanggal");
		$jam		= $this->input->post("elab_jam");
		$status		= "";

		$elab_orderno	= $this->input->post("elabtin_orderno");
		$elab_kode		= $this->input->post("elabtin_kode");
		$elab_tindakan	= $this->input->post("elabtin_tindakan");
		$elab_tarifrs	= $this->input->post("elabtin_tarifrs");
		$elab_tarifdr	= $this->input->post("elabtin_tarifdr");
		$elab_harga		= $this->input->post("elabtin_harga");
		$elab_note		= $this->input->post("elabtin_catatan");

		foreach($orderno as $okey => $oval){
			$check_laborat	= $this->db->query("SELECT * FROM tbl_hlab WHERE orderno = '$oval'")->num_rows();

			if($check_laborat == 0){
				$check_order	= $this->db->query("SELECT * FROM tbl_orderperiksa WHERE koders = '$unit' AND orderno = '$oval' AND noreg = '$noreg'");

				$data_op		= array(
					"koders"	=> $unit,
					"orderno"	=> $oval,
					"noreg"		=> $noreg,
					"rekmed"	=> $rekmed,
					"tglorder"	=> $tanggal[$okey],
					"proses"	=> "proses",
					"jamorder"	=> $jam[$okey],
					"kodokter"	=> $kodokter,
					"asal"		=> $kodepos,
					"lab"		=> "1",
					"labok"		=> "0",
				);

				// Insert Elab
				if(!empty($elab_kode)){
					$this->db->query("DELETE FROM tbl_elab WHERE notr = '$oval' AND noreg = '$noreg'");

					foreach($elab_kode as $ekkey => $ekval){
						if($elab_orderno[$ekkey] == $oval){
							$data_elab			= array(
								"notr"			=> $oval,
								"noreg"			=> $noreg,
								"kodetarif"		=> $elab_kode[$ekkey],
								"tindakan"		=> $elab_tindakan[$ekkey],
								"tarifrs"		=> $elab_tarifrs[$ekkey],
								"tarifdr"		=> $elab_tarifdr[$ekkey],
								"keterangan"	=> $elab_note[$ekkey]
							);

							$this->db->insert("tbl_elab", $data_elab);
						}
					}
				}

				if($check_order->num_rows() == 0){
					$query_order_elab	= $this->db->insert("tbl_orderperiksa", $data_op);
					history_log(0 ,'POLI_DOKTER_LAB' ,'ADD' ,$oval ,'-');

				} else {
					$query_order_elab	= $this->db->update("tbl_orderperiksa", $data_op, array("koders" => $unit,"orderno" => $oval, "noreg" => $noreg));
					history_log(0 ,'POLI_DOKTER_LAB' ,'EDIT' ,$oval ,'-');
				}

				if($query_order_elab){
					$status	= "success";
				} else {
					$status	= "failed";
				}
			}
		}

		$data_pasien	= $this->db->query("SELECT * FROM tbl_pasien WHERE rekmed = '$rekmed'")->row();

		echo json_encode(array(
			"status" 	=> $status,
			"nama"		=> $data_pasien->namapas,
			"noreg"		=> $noreg,
			"orderno"	=> $orderno,
		));
	}

	public function add_emed($noreg, $rekmed, $kodokter){
		$unit		= $this->session->userdata("unit");
		$orderno	= $this->input->post("emed_no");
		$tanggal	= $this->input->post("emed_tanggal");
		$jam		= $this->input->post("emed_jam");
		$unitpos	= $this->input->post("emed_unit");
		$status		= "";

		$emed_orderno	= $this->input->post("emedtin_orderno");
		$emed_kode		= $this->input->post("emedtin_kode");
		$emed_tindakan	= $this->input->post("emedtin_tindakan");
		$emed_tarifrs	= $this->input->post("emedtin_tarifrs");
		$emed_tarifdr	= $this->input->post("emedtin_tarifdr");
		$emed_harga		= $this->input->post("emedtin_harga");
		$emed_note		= $this->input->post("emedtin_catatan");

		foreach($orderno as $okey => $oval){
			$check_order	= $this->db->query("SELECT * FROM tbl_orderperiksa WHERE koders = '$unit' AND orderno = '$oval' AND noreg = '$noreg'");

			$data_op		= array(
				"koders"	=> $unit,
				"orderno"	=> $oval,
				"noreg"		=> $noreg,
				"rekmed"	=> $rekmed,
				"tglorder"	=> $tanggal[$okey],
				"proses"	=> "proses",
				"jamorder"	=> $jam[$okey],
				"kodokter"	=> $kodokter,
				"medis"		=> "1",
				"medisok"	=> "0",
			);

			// Insert Emedis
			if(!empty($emed_kode)){
				$this->db->query("DELETE FROM tbl_emedis WHERE notr = '$oval' AND noreg = '$noreg'");

				foreach($emed_kode as $ekkey => $ekval){
					if($emed_orderno[$ekkey] == $oval){
						if($unitpos[$okey] == "-"){
							$nama_post	= "";
						} else {
							$nama_post		= data_master("tbl_namapos", array("kodepos" => $unitpos[$okey]))->namapost;
						}

						$data_emed			= array(
							"notr"			=> $oval,
							"noreg"			=> $noreg,
							"kodetarif"		=> $emed_kode[$ekkey],
							"tindakan"		=> $emed_tindakan[$ekkey],
							"tarifrs"		=> $emed_tarifrs[$ekkey],
							"tarifdr"		=> $emed_tarifdr[$ekkey],
							"kodepos"		=> $unitpos[$okey],
							"namapost"		=> $nama_post,
							"keterangan"	=> $emed_note[$ekkey]
						);

						$this->db->insert("tbl_emedis", $data_emed);
					}
				}
			}

			if($check_order->num_rows() == 0){
				$query_order_emed	= $this->db->insert("tbl_orderperiksa", $data_op);
			} else {
				$query_order_emed	= $this->db->update("tbl_orderperiksa", $data_op, array("koders" => $unit,"orderno" => $oval, "noreg" => $noreg));
			}

			if($query_order_emed){
				$status	= "success";
			} else {
				$status	= "failed";
			}
		}

		$data_pasien	= $this->db->query("SELECT * FROM tbl_pasien WHERE rekmed = '$rekmed'")->row();

		echo json_encode(array(
			"status" 	=> $status,
			"nama"		=> $data_pasien->namapas,
			"noreg"		=> $noreg,
			"orderno"	=> $orderno,
		));
	}

	public function add_erad($noreg, $rekmed, $kodokter){
		$unit		= $this->session->userdata("unit");
		$orderno	= $this->input->post("erad_no");
		$tanggal	= $this->input->post("erad_tanggal");
		$jam		= $this->input->post("erad_jam");
		$unitpos	= $this->input->post("erad_unit");
		$status		= "";

		$erad_orderno	= $this->input->post("eradtin_orderno");
		$erad_kode		= $this->input->post("eradtin_kode");
		$erad_tindakan	= $this->input->post("eradtin_tindakan");
		$erad_tarifrs	= $this->input->post("eradtin_tarifrs");
		$erad_tarifdr	= $this->input->post("eradtin_tarifdr");
		$erad_harga		= $this->input->post("eradtin_harga");
		$erad_note		= $this->input->post("eradtin_catatan");

		foreach($orderno as $okey => $oval){
			$check_order	= $this->db->query("SELECT * FROM tbl_orderperiksa WHERE koders = '$unit' AND orderno = '$oval' AND noreg = '$noreg'");

			$data_op		= array(
				"koders"	=> $unit,
				"orderno"	=> $oval,
				"noreg"		=> $noreg,
				"rekmed"	=> $rekmed,
				"tglorder"	=> $tanggal[$okey],
				"proses"	=> "proses",
				"jamorder"	=> $jam[$okey],
				"kodokter"	=> $kodokter,
				"radio"		=> "1",
				"radiook"	=> "0",
			);

			$this->db->query("DELETE FROM tbl_eradio WHERE notr = '$oval' AND noreg = '$noreg'");

			// Insert Elab
			if(!empty($erad_kode)){
				foreach($erad_kode as $ekkey => $ekval){
					if($erad_orderno[$ekkey] == $oval){
						$data_erad			= array(
							"notr"			=> $oval,
							"noreg"			=> $noreg,
							"kodetarif"		=> $erad_kode[$ekkey],
							"tindakan"		=> $erad_tindakan[$ekkey],
							"tarifrs"		=> $erad_tarifrs[$ekkey],
							"tarifdr"		=> $erad_tarifdr[$ekkey],
							"keterangan"	=> $erad_note[$ekkey]
						);

						$this->db->insert("tbl_eradio", $data_erad);
					}
				}
			}

			if($check_order->num_rows() == 0){
				$query_order_erad	= $this->db->insert("tbl_orderperiksa", $data_op);
				history_log(0 ,'POLI_DOKTER_RAD' ,'ADD' ,$oval ,'-');
				
			} else {
				$query_order_erad	= $this->db->update("tbl_orderperiksa", $data_op, array("koders" => $unit,"orderno" => $oval, "noreg" => $noreg));
				history_log(0 ,'POLI_DOKTER_RAD' ,'EDIT' ,$oval ,'-');
			}

			if($query_order_erad){
				$status	= "success";
			} else {
				$status	= "failed";
			}
		}

		$data_pasien	= $this->db->query("SELECT * FROM tbl_pasien WHERE rekmed = '$rekmed'")->row();

		echo json_encode(array(
			"status" 	=> $status,
			"nama"		=> $data_pasien->namapas,
			"noreg"		=> $noreg,
			"orderno"	=> $orderno,
		));
	}

	public function delete_elab($noreg, $orderno){
		$unit	= $this->session->userdata("unit");

		$check_order	= $this->db->query("SELECT * FROM tbl_orderperiksa WHERE koders = '$unit' AND orderno = '$orderno' AND noreg = '$noreg'");

		if($check_order->num_rows() == 0){
			echo json_encode(array("status" => "unregistered"));
		} else {
			$query_delete	= $this->db->query("DELETE FROM tbl_orderperiksa WHERE koders = '$unit' AND orderno = '$orderno' AND noreg = '$noreg'");
			if($query_delete){
				$this->db->query("DELETE FROM tbl_elab WHERE notr = '$orderno' AND noreg = '$noreg'");
				
				history_log(0 ,'POLI_DOKTER_LAB' ,'DELETE' ,$orderno ,'-');
				echo json_encode(array("status" => "success", "orderno" => $orderno));
			} else {
				echo json_encode(array("status" => "failed", "orderno" => $orderno));
			}
		}
	}

	public function delete_emed($noreg, $orderno){
		$unit	= $this->session->userdata("unit");

		$check_order	= $this->db->query("SELECT * FROM tbl_orderperiksa WHERE koders = '$unit' AND orderno = '$orderno' AND noreg = '$noreg'");

		if($check_order->num_rows() == 0){
			echo json_encode(array("status" => "unregistered"));
		} else {
			$query_delete	= $this->db->query("DELETE FROM tbl_orderperiksa WHERE koders = '$unit' AND orderno = '$orderno' AND noreg = '$noreg'");
			if($query_delete){
				$this->db->query("DELETE FROM tbl_emedis WHERE notr = '$orderno' AND noreg = '$noreg'");
				echo json_encode(array("status" => "success", "orderno" => $orderno));
			} else {
				echo json_encode(array("status" => "failed", "orderno" => $orderno));
			}
		}
	}

	public function delete_erad($noreg, $orderno){
		$unit	= $this->session->userdata("unit");

		$check_order	= $this->db->query("SELECT * FROM tbl_orderperiksa WHERE koders = '$unit' AND orderno = '$orderno' AND noreg = '$noreg'");

		if($check_order->num_rows() == 0){
			echo json_encode(array("status" => "unregistered"));
		} else {
			$query_delete	= $this->db->query("DELETE FROM tbl_orderperiksa WHERE koders = '$unit' AND orderno = '$orderno' AND noreg = '$noreg'");
			if($query_delete){
				$this->db->query("DELETE FROM tbl_eradio WHERE notr = '$orderno' AND noreg = '$noreg'");
				
				history_log(0 ,'POLI_DOKTER_RAD' ,'DELETE' ,$orderno ,'-');
				echo json_encode(array("status" => "success", "orderno" => $orderno));
			} else {
				echo json_encode(array("status" => "failed", "orderno" => $orderno));
			}
		}
	}

	public function data_order_elab($noreg){
		$koders		= $this->session->userdata("unit");

		$get_order_elab	= $this->db->query("SELECT * FROM tbl_orderperiksa WHERE koders = '$koders' AND noreg = '$noreg' AND orderno LIKE '%EL%'");
		$order_array	= array();

		if($get_order_elab->num_rows() == 0){
			echo json_encode(array("status" => "null"));
		} else {
			foreach($get_order_elab->result() as $key => $val){
				$order_array[]	= array(
					"orderno"	=> $val->orderno,
					"proses"	=> $val->proses,
				);
			}
			echo json_encode(array(
				"status" 	=> "success",
				"result"	=> $order_array
			)); 
		}
	}

	public function data_order_emed($noreg){
		$koders		= $this->session->userdata("unit");

		$get_order_elab	= $this->db->query("SELECT * FROM tbl_orderperiksa WHERE koders = '$koders' AND noreg = '$noreg' AND orderno LIKE '%ERM%'");
		$order_array	= array();

		if($get_order_elab->num_rows() == 0){
			echo json_encode(array("status" => "null"));
		} else {
			foreach($get_order_elab->result() as $key => $val){
				$order_array[]	= array(
					"orderno"	=> $val->orderno,
					"proses"	=> $val->proses,
				);
			}
			echo json_encode(array(
				"status" 	=> "success",
				"result"	=> $order_array
			)); 
		}
	}

	public function data_order_erad($noreg){
		$koders		= $this->session->userdata("unit");

		$get_order_elab	= $this->db->query("SELECT * FROM tbl_orderperiksa WHERE koders = '$koders' AND noreg = '$noreg' AND orderno LIKE '%ERD%'");
		$order_array	= array();

		if($get_order_elab->num_rows() == 0){
			echo json_encode(array("status" => "null"));
		} else {
			foreach($get_order_elab->result() as $key => $val){
				$order_array[]	= array(
					"orderno"	=> $val->orderno,
					"proses"	=> $val->proses,
				);
			}
			echo json_encode(array(
				"status" 	=> "success",
				"result"	=> $order_array
			)); 
		}
	}

	// Ajax GET

	public function get_dokter_ajax(){
		$unit   = $this->input->post('cekunit');
		$koders = $this->session->userdata('unit');

		$data = $this->db->query(
			"SELECT d.kodokter, d.nadokter from tbl_drpoli p join tbl_dokter d on p.kodokter=d.kodokter where p.kopoli = '$unit' and koders = '$koders' "
			)->result();
		echo json_encode($data);
    }

	public function cek_tgl(){
		$koders       = $this->session->userdata('unit');
		$ijindari     = $this->input->post('ijindari');
		$ijinsampai   = $this->input->post('ijinsampai');

		$tgl1         = strtotime($ijindari);
		$tgl2         = strtotime($ijinsampai);
		$jarak        = ($tgl2 - $tgl1)/ 60 / 60 / 24;

		echo json_encode(array("jarak" => $jarak));
    }

	public function getpoli_tin(){
		$unit = $this->session->userdata('unit');
		$kode = $this->input->get('kode');

		$data = $this->db->query("SELECT *	from daftar_tarif_nonbedah
		-- where daftar_tarif_nonbedah.koders='$unit'
		where daftar_tarif_nonbedah.kodetarif ='$kode'")->row();
		echo json_encode($data);
	}

	public function getpoli_alkes(){
		$unit = $this->session->userdata('unit');
		$kode = $this->input->get('kode');

		// $data = $this->db->query("SELECT IFNULL((select sum(saldoakhir) as saldoakhir from tbl_barangstock b where koders='$unit' and b.kodebarang=a.kodebarang),0) as salakhir, a.*
		// 	from tbl_barang a
		// 	where kodebarang='$kode'")->row();

		$data	= $this->db->query("SELECT * FROM tbl_barang WHERE kodebarang = '$kode'")->row();
		echo json_encode($data);
	}

	public function getpoli_obatterapi(){
		$unit = $this->session->userdata('unit');
		$kode = $this->input->get('kode');

		// $data = $this->db->query("SELECT IFNULL((select sum(saldoakhir) as saldoakhir from tbl_barangstock b where koders='$unit' and b.kodebarang=a.kodebarang),0) as salakhir, a.*
		// 	from tbl_barang a
		// 	where kodebarang='$kode'")->row();

		$data	= $this->db->query("SELECT * FROM tbl_barang WHERE kodebarang = '$kode'")->row();
		echo json_encode($data);
	}

	public function get_detail(){
		$ceknoreg   = $this->input->post('ceknoreg');
		$koders     = $this->session->userdata('unit');

		$data = $this->db->query("SELECT *FROM pasien_rajal where koders='$koders' and noreg='$ceknoreg'")->row();
		echo json_encode($data);
    }

	public function getdiag(){
		$koders   = $this->session->userdata('unit');
		$sab      = $this->input->post('cekjnss');

		$data = $this->db->query(
			"SELECT tbl_icdinb.code as id, concat( tbl_icdinb.code,' | ',
			tbl_icdinb.str,' | ', tbl_icdinb.code2 ) as text
			from tbl_icdinb
			where tbl_icdinb.sab='$sab'
			and (tbl_icdinb.code like '%%' or tbl_icdinb.str like '%%')
			order by code LIMIT 10"
			)->result();
		echo json_encode($data);
    }

	public function get_last_number(){
		$unit = $this->session->userdata("unit");
		echo json_encode(array(
			"noresep" => temp_urut_transaksi("ERESEP", $unit, 20)
		));
	}

	public function get_last_number_elab(){
		$unit = $this->session->userdata("unit");
		echo json_encode(array(
			"noelab" => urut_transaksi("ELAB", 19)
		));
	}

	public function get_last_number_emed(){
		$unit = $this->session->userdata("unit");
		echo json_encode(array(
			"noemed" => urut_transaksi("ELEKTROMEDIS", 19)
		));
	}
	
	public function get_last_number_erad(){
		$unit = $this->session->userdata("unit");
		echo json_encode(array(
			"noerad" => urut_transaksi("TR_RADIOLOGI", 19)
		));
	}

	public function __gakepake(){
		// public function get_recent_number(){
		// 	$unit = $this->session->userdata("unit");
		// 	echo json_encode(array(
		// 		"noresep" => recent_urut_transaksi("ERESEP", $unit, 20)
		// 	));
		// }
	}

	public function plklnkdrpoli(){
		$pl_cabang		  = $this->session->userdata("unit");
		$pl_noreg		  = $this->input->post("noreg");
		$pl_kodokter	  = $this->input->post("kodokter");
		$pl_nama_dokter   = data_master("tbl_dokter", array("kodokter" => $pl_kodokter, "koders" => $pl_cabang))->nadokter;

		$qpl_1	= $this->db->query("UPDATE tbl_regist SET kodokter = '$pl_kodokter' WHERE noreg = '$pl_noreg' AND koders = '$pl_cabang'");
		$qpl_2	= $this->db->query("UPDATE tbl_rekammedisrs SET kodokter = '$pl_kodokter' WHERE noreg = '$pl_noreg' AND koders = '$pl_cabang'");
		$qpl_3	= $this->db->query("UPDATE tbl_orderperiksa SET kodokter = '$pl_kodokter' WHERE noreg = '$pl_noreg' AND koders = '$pl_cabang'");

		if(!$qpl_1 || !$qpl_2 || !$qpl_3){
			echo json_encode(array(
				"status"	=> 0,
				"dokter"	=> "UNDEFINED",
			));
		} else {
			echo json_encode(array(
				"status"	=> 1,
				"dokter"	=> $pl_nama_dokter,
			));
		}
	}

	// View

	public function pemeriksaan_perawat(){
		$cek        = $this->session->userdata("level");
		$koders     = $this->session->userdata("unit");
		$username   = $this->session->userdata("username");
		$ceknoreg   = $this->input->get('noreg');
		$rekmed     = $this->input->get('rekmed');

		$data_pas = $this->db->query("SELECT *FROM pasien_rajal where koders='$koders' and noreg='$ceknoreg'")->row();

		$qcek = $this->db->query("SELECT * FROM tbl_rekammedisrs WHERE noreg = '$ceknoreg' and rekmed = '$rekmed' and koders='$koders'")->result_array();
		$qcek2 = count($qcek);

		if ($qcek2 > 0) {
			$ttv = $this->db->query("SELECT * FROM tbl_rekammedisrs WHERE noreg = '$ceknoreg' and rekmed = '$rekmed' and koders='$koders'")->row();
		}else{
			$ttv = $this->db->query("SELECT '-' as id ,'-' as koders ,'-' as noreg ,'-' as rekmed ,'-' as tglperiksa ,'-' as tglkeluar ,'-' as jam ,'-' as tujuan ,'-' as kodepos ,'-' as koderuang ,'-' as keluhanawal ,'-' as pfisik ,'-' as diagnosa ,'-' as simpul ,'-' as anjuran ,'-' as resep ,'-' as kodeicd ,'-' as kodeicd2 ,'-' as kodeicd3 ,'-' as keadaan_pulang ,'-' as ketpulang ,'-' as kodokter ,'-' as tglkonsul ,'-' as rcounter ,'0' as nadi ,'-' as nadi2 ,'-' as nafas ,'-' as tdarah ,'-' as tdarah1 ,'-' as suhu ,'-' as tinggibadan ,'-' as beratbadan ,'-' as bmi ,'-' as bmiresult ,'-' as tglkembali ,'-' as jamkembali ,'-' as nojanji ,'-' as dikonsulkepoli ,'-' as dikonsuldr ,'-' as jamdikonsul ,'-' as tgldikonsul ,'-' as noregkonsul ,'-' as surat1 ,'-' as surat2 ,'-' as surat3 ,'-' as surat4 ,'-' as alasanpulang ,'-' as urutkonsul ,'-' as oksigen ,'-' as kejiwaan ,'-' as lokalis ,'-' as rencana ,'-' as eye ,'-' as verbal ,'-' as movement ,'-' as nyeri ,'-' as urutin ,'-' as rmutama ,'-' as diagu ,'-' as diags ,'-' as tindu ,'-' as tinds ,'-' as asal ,'-' as gambar1 ,'-' as gambar2 ,'-' as pasienvk ,'-' as dead from tbl_rekammedisrs limit 1")->row();
		}
		if(!empty($cek)){
			$data	= [
				"menu" 		=> "Bedah Central",
				"submenu" 	=> "Persetujuan Umum",
				"link"		=> "Bedah_Central",
				"unit"		=> $koders,
				"data_pas"	=> $data_pas,
				"ttv"		=> $ttv,
				// "hubungank" => $query_hubk->result(),
				// "statuspu"	=> ($data_pas->jkel() > 0)? "done" : "undone",
				// "usia"   => new Date($data_pas->tgllahire),
				// "usia"   	=> $this->footer_all->hitung_usia($data_pas->tgllahire),
			];

			$this->load->view("poliklinik/v_poliklinik_p_perawat", $data);
		} else {
			redirect("/");
		}
	}

	public function pemeriksaan_dokter(){
		$cek        = $this->session->userdata("level");
		$koders     = $this->session->userdata("unit");
		$username   = $this->session->userdata("username");
		$ceknoreg   = $this->input->get('noreg');
		$rekmed     = $this->input->get('rekmed');

		if(isset($ceknoreg) && isset($rekmed)){
			$data_pas = $this->db->query("SELECT (select nadokter from tbl_dokter a where a.kodokter=pasien_rajal.kodokter and a.koders=pasien_rajal.koders limit 1)nadokter,
			pasien_rajal.* FROM pasien_rajal where koders='$koders' and noreg='$ceknoreg'")->row();

			$qcek = $this->db->query("SELECT * FROM tbl_rekammedisrs WHERE noreg = '$ceknoreg' and rekmed = '$rekmed' and koders='$koders'")->num_rows();
			// $qcek2 = count($qcek);
			// $ttv = $this->db->query("SELECT '-' as id ,'-' as koders ,'-' as noreg ,'-' as rekmed ,'-' as tglperiksa ,'-' as tglkeluar ,'-' as jam ,'-' as tujuan ,'-' as kodepos ,'-' as koderuang ,'-' as keluhanawal ,'-' as pfisik ,'-' as diagnosa ,'-' as simpul ,'-' as anjuran ,'-' as resep ,'-' as kodeicd ,'-' as kodeicd2 ,'-' as kodeicd3 ,'-' as keadaan_pulang ,'-' as ketpulang ,'-' as kodokter ,'-' as tglkonsul ,'-' as rcounter ,'0' as nadi ,'-' as nadi2 ,'-' as nafas ,'-' as tdarah ,'-' as tdarah1 ,'-' as suhu ,'-' as tinggibadan ,'-' as beratbadan ,'-' as bmi ,'-' as bmiresult ,'-' as tglkembali ,'-' as jamkembali ,'-' as nojanji ,'-' as dikonsulkepoli ,'-' as dikonsuldr ,'-' as jamdikonsul ,'-' as tgldikonsul ,'-' as noregkonsul ,'-' as surat1 ,'-' as surat2 ,'-' as surat3 ,'-' as surat4 ,'-' as alasanpulang ,'-' as urutkonsul ,'-' as oksigen ,'-' as kejiwaan ,'-' as lokalis ,'-' as rencana ,'-' as eye ,'-' as verbal ,'-' as movement ,'-' as nyeri ,'-' as urutin ,'-' as rmutama ,'-' as diagu ,'-' as diags ,'-' as tindu ,'-' as tinds ,'-' as asal ,'-' as gambar1 ,'-' as gambar2 ,'-' as pasienvk ,'-' as dead from tbl_rekammedisrs limit 1")->row();
			$ttv = $this->db->query("SELECT * FROM tbl_rekammedisrs WHERE noreg = '$ceknoreg' and rekmed = '$rekmed' and koders='$koders'")->row();

			// ICDTR //
			$icd = $this->db->query("SELECT * FROM tbl_icdtr WHERE noreg = '$ceknoreg' and koders='$koders'")->num_rows();
			if ($icd > 0) {
				$detilicd    = $this->db->query(
				"SELECT a.*,b.sab, concat( b.code,' | ', b.str,' | ', b.code2 )nmdiag, case when b.sab='ICD10_1998' then 'DG01' else 'DG02' end as jns  FROM tbl_icdtr a
				left join tbl_icdinb b on a.icdcode=b.code
				WHERE noreg = '$ceknoreg' and koders='$koders'")->result();
			}else{
				$detilicd = "";
			}

			// BILLING //
			$billing = $this->db->query("SELECT * FROM tbl_dpoli WHERE noreg = '$ceknoreg' and koders='$koders'")->num_rows();
			if ($billing > 0) {
				$detilbilling    = $this->db->query("SELECT concat( ' [',b.kodetarif,'] ',' - ',' [',b.tindakan,'] ' ) as nm_tin,
				(select c.nadokter from tbl_dokter c where a.kodokter=c.kodokter limit 1)nadok,
				(select c.nadokter from tbl_dokter c where a.koperawat=c.kodokter limit 1)naper
				,a.*
				FROM tbl_dpoli a left join daftar_tarif_nonbedah b on a.koders=b.koders and a.kodetarif=b.kodetarif WHERE a.noreg = '$ceknoreg' and a.koders='$koders'")->result();
			}else{
				$detilbilling = "";
			}

			// ALKES BHP //
			$alkes = $this->db->query("SELECT * FROM tbl_alkestransaksi WHERE notr = '$ceknoreg' and koders='$koders'")->num_rows();
			if ($alkes > 0) {
				$detilalkes    = $this->db->query("SELECT
				REPLACE(concat(' [ ', kodebarang ,' ] ',' - ',' [ ', namabarang ,' ] ',
				' - ',' [ ', satuan1 ,' ] '),'   ','') as nm_brg,
				a.*
				FROM tbl_alkestransaksi a
				LEFT JOIN tbl_barang b ON a.kodeobat=b.kodebarang WHERE notr = '$ceknoreg' and koders='$koders' ")->result();
			}else{
				$detilalkes = "";
			}

			// ORDER PERIKSA //

			$eresep		= $this->db->query("SELECT * FROM tbl_orderperiksa WHERE noreg = '$ceknoreg' AND koders = '$koders'");
			$eresep_row	= $eresep->num_rows();
			if($eresep_row != 0){
				$eresep_val		= "done";
				$order_periksa	= $eresep->row();
			} else {
				$eresep_val		= "undone";
				$order_periksa	= "";
			}

			// ERESEP //

			$query_eresep	= $this->db->query("SELECT concat(' [ ', kodeobat ,' ] ',' - ',' [ ', namaobat ,' ] ',' - ',' [ ', satuan ,' ] ',' - ',' [ ', harga ,' ]') as namaobatnya, tbl_eresep.* 
			FROM tbl_eresep 
			WHERE noreg = '$ceknoreg' 
			AND koders = '$koders'");
			$eresep_row	= $query_eresep->num_rows();
			if($eresep_row != 0){
				$status_eresep	= "done";
				$detileresep	= $query_eresep->result();
			} else {
				$status_eresep	= "undone";
				$detileresep	= "";
			}

			// RACIKAN //
			$query_racikan1	= $this->db->query("SELECT CONCAT(' [ ', kodeobat ,' ] ',' - ',' [ ', namaobat ,' ] ',' - ',' [ ', satuan ,' ] ',' - ',' [ ', harga ,' ]') as namaobatr1, tbl_eracik.*  
			FROM tbl_eracik 
			WHERE racikid = 'RACIK1' 
			AND noreg = '$ceknoreg' 
			AND koders = '$koders'");
			$query_racikan2	= $this->db->query("SELECT CONCAT(' [ ', kodeobat ,' ] ',' - ',' [ ', namaobat ,' ] ',' - ',' [ ', satuan ,' ] ',' - ',' [ ', harga ,' ]') as namaobatr2, tbl_eracik.*  
			FROM tbl_eracik 
			WHERE racikid = 'RACIK2' 
			AND noreg = '$ceknoreg' 
			AND koders = '$koders'");
			$query_racikan3	= $this->db->query("SELECT CONCAT(' [ ', kodeobat ,' ] ',' - ',' [ ', namaobat ,' ] ',' - ',' [ ', satuan ,' ] ',' - ',' [ ', harga ,' ]') as namaobatr3, tbl_eracik.*  
			FROM tbl_eracik 
			WHERE racikid = 'RACIK3' 
			AND noreg = '$ceknoreg' 
			AND koders = '$koders'");

			$racik1_row	= $query_racikan1->num_rows();
			$racik2_row	= $query_racikan2->num_rows();
			$racik3_row	= $query_racikan3->num_rows();

			if($racik1_row == 0){
				$status_racik1	= "undone";
				$detilracik1	= "";
			} else {
				$status_racik1	= "done";
				$detilracik1	= $query_racikan1->result();
			}

			if($racik2_row == 0){
				$status_racik2	= "undone";
				$detilracik2	= "";
			} else {
				$status_racik2	= "done";
				$detilracik2	= $query_racikan2->result();
			}

			if($racik3_row == 0){
				$status_racik3	= "undone";
				$detilracik3	= "";
			} else {
				$status_racik3	= "done";
				$detilracik3	= $query_racikan3->result();
			}

			// STATUS ORDER //
			$data_order_periksa	= $this->db->query("SELECT orderno, tglorder, proses FROM tbl_orderperiksa WHERE orderno LIKE '%ER%' AND orderno NOT LIKE '%ERD%' AND orderno NOT LIKE '%ERM%' AND noreg = '$ceknoreg' AND koders = '$koders'");
			if($data_order_periksa->num_rows() == 0){
				$status_dop		= "undone";
				$data_dop		= "";
				$dop_row		= "";
			} else {
				$status_dop		= "done";
				$data_dop		= $data_order_periksa->result();
				$dop_row		= $data_order_periksa->row();
			}

			// HISTORY PASIEN
			$query_hispas	= $this->db->query("SELECT * FROM tbl_rekammedisrs WHERE rekmed = '$rekmed'");
			if($query_hispas->num_rows() == 0){
				$status_hispas	= "undone";
				$data_hispas	= "";
				$total_hispas	= "0";
			} else {
				$status_hispas	= "done";
				$data_hispas	= $query_hispas->result();
				$total_hispas	= $query_hispas->num_rows();
			}

			//E_LAB
			$query_list	= $this->db->query("SELECT CONCAT('[ ', kodetarif ,' ] - [ ', tindakan ,' ]') AS text, kodetarif AS kodeid FROM daftar_tarif_nonbedah WHERE kodepos = 'LABOR' ORDER BY tindakan ASC")->result();

			$query_orderelab	= $this->db->query("SELECT * FROM tbl_orderperiksa WHERE koders = '$koders' AND noreg = '$ceknoreg' AND orderno LIKE '%EL%'");
			if($query_orderelab->num_rows() == 0){
				$status_elab	= "undone";
				$data_orderlab	= "";
				$total_elab		= "0";
			} else {
				$status_elab	= "done";
				$data_orderlab	= $query_orderelab->result();
				$total_elab		= $query_orderelab->num_rows();
			}

			$query_emed_unit	= $this->db->query("SELECT kodepos AS id, namapost AS text FROM tbl_namapos")->result();

			//E_ERM

			$query_orderemed	= $this->db->query("SELECT * FROM tbl_orderperiksa WHERE koders = '$koders' AND noreg = '$ceknoreg' AND orderno LIKE '%ERM%'");
			if($query_orderemed->num_rows() == 0){
				$status_emed	= "undone";
				$data_ordermed	= "";
				$total_emed		= "0";
			} else {
				$status_emed	= "done";
				$data_ordermed	= $query_orderemed->result();
				$total_emed		= $query_orderemed->num_rows();
			}

			//E-RAD

			$query_ordererad	= $this->db->query("SELECT * FROM tbl_orderperiksa WHERE koders = '$koders' AND noreg = '$ceknoreg' AND orderno LIKE '%ERD%'");
			if($query_ordererad->num_rows() == 0){
				$status_erad	= "undone";
				$data_ordererad	= "";
				$total_erad		= "0";
			} else {
				$status_erad	= "done";
				$data_ordererad	= $query_ordererad->result();
				$total_erad		= $query_ordererad->num_rows();
			}

			// VALUE
			if(!empty($cek)){
				$data	= [
					"menu" 			=> "Bedah Central",
					"submenu" 		=> "Persetujuan Umum",
					"link"			=> "Bedah_Central",
					"unit"			=> $koders,
					"data_pas"		=> $data_pas,
					"ttv"			=> $ttv,
					"detilicd"  	=> $detilicd,
					"detilbilling"  => $detilbilling,
					"detilalkes"    => $detilalkes,
					"statuspu"		=> ($qcek > 0)? "done" : "undone",
					"statusicd"		=> ($icd > 0)? "done" : "undone",
					"jumdataicd"	=> $icd,
					"statusbill"	=> ($billing > 0)? "done" : "undone",
					"jumdatabill"	=> $billing,
					"statusalkes"	=> ($alkes > 0)? "done" : "undone",
					"jumdataalkes"	=> $alkes,
					"eresep"		=> $eresep_val,
					"orderperiksa"	=> $order_periksa,
					"status_eresep"	=> $status_eresep,
					"detileresep"	=> $detileresep,
					"jumdataer"		=> $eresep_row,
					"status_racik1"	=> $status_racik1,
					"status_racik2"	=> $status_racik2,
					"status_racik3"	=> $status_racik3,
					"detil_racik1"	=> $detilracik1,
					"detil_racik2"	=> $detilracik2,
					"detil_racik3"	=> $detilracik3,
					"jumdataracik1" => $racik1_row,
					"jumdataracik2"	=> $racik2_row,
					"jumdataracik3"	=> $racik3_row,
					"status_dop"	=> $status_dop,
					"data_dop"		=> $data_dop,
					"status_hispas"	=> $status_hispas,
					"data_hispas"	=> $data_hispas,
					"total_hispas"	=> $total_hispas,
					"list_elab"		=> $query_list,
					"status_elab"	=> $status_elab,
					"data_orderlab"	=> $data_orderlab,
					"total_elab"	=> $total_elab,
					"emed_unit"		=> $query_emed_unit,
					"status_emed"	=> $status_emed,
					"data_orderemed"	=> $data_ordermed,
					"total_emed"	=> $total_emed,
					"status_erad"	=> $status_erad,
					"data_ordererad"	=> $data_ordererad,
					"total_erad"	=> $total_erad,
				];
				
				$this->load->view("poliklinik/v_poliklinik_p_dokter_add", $data);
			} else {
				redirect("/");
			}
		} else {
			redirect("/poliklinik/");
		}
	}

	public function pemeriksaan_odontogram(){
		$cek        = $this->session->userdata("level");
		$koders     = $this->session->userdata("unit");
		$username   = $this->session->userdata("username");
		$ceknoreg   = $this->input->get('noreg');
		$rekmed     = $this->input->get('rekmed');

		if(!empty($cek)){

		$data_pas = $this->db->query("SELECT * from tbl_pasien WHERE rekmed='$rekmed'") ->row();
			$data	= [
				"menu" 		=> "Odontogram",
				"submenu" 	=> "Odontogram",
				"unit"		=> $koders,
				"noreg"		=> $ceknoreg,
				"data_pas"	=> $data_pas,
				// "hubungank" => $query_hubk->result(),
				// "statuspu"	=> ($data_pas->jkel() > 0)? "done" : "undone",
				// "usia"   => new Date($data_pas->tgllahire),
				// "usia"   	=> $this->footer_all->hitung_usia($data_pas->tgllahire),
			];

			$this->load->view("poliklinik/v_poliklinik_p_odontogram", $data);
		} else {
			redirect("/");
		}
	}

	function add_medical_record($rekmed='',$gigi='',$noreg=''){

		$cek        = $this->session->userdata("level");
		$koders     = $this->session->userdata("unit");
		$username   = $this->session->userdata("username");
		// $rekmed     = $this->input->get('rekmed');
		if(!empty($cek)){
			$data_pas = $this->db->query("SELECT * from tbl_pasien WHERE rekmed='$rekmed'") ->row();
			
			$queryData = $this->db->query("SELECT * FROM kategori_warna WHERE status='Y'") ->result_array();
			
			$data    = array('rows' => $queryData);
			$data	= [
				"menu" 		=> "Odontogram",
				"submenu" 	=> "Odontogram",
				"unit"		=> $koders,
				"gigi"		=> $gigi,
				"noreg"		=> $noreg,
				"rows"		=> $queryData,
				"data_pas"	=> $data_pas,
				
			];
			$this->load->view("poliklinik/v_poliklinik_medical_record", $data);


		} else {
			redirect("/");
		}
	}

	function ajax_add_odon(){

		$cek                    = $this->session->userdata("level");
		$koders                 = $this->session->userdata("unit");
		$username               = $this->session->userdata("username");
		$tgl                    = $this->input->post('tgl');
		$jam                    = $this->input->post('jam');
		$id_odontogram          = $this->input->post('id_odontogram');
		$kode_medical_record    = temp_urut_transaksi("URUT_ODON", $koders, 15);

		if(!empty($cek)){
			
			$cekdata = $this->db->query("SELECT*from medical_record where tanggal='$tgl' and jam='$jam' and id_odontogram='$id_odontogram' ")->num_rows();

			if($cekdata>0){
				echo json_encode(array("status" => "2"));
			}else{
					
				$data = array(
					'id_medical_record'   => $kode_medical_record,
					'id_pasien'           => $this->input->post('id_pasien'),
					'id_odontogram'       => $this->input->post('id_odontogram'),
					'id_kategori_warna'   => $this->input->post('j_pen'),
					'remark'   			  => $this->input->post('remark'),
					'tanggal'             => $tgl,
					'jam'             	  => $jam
				);
										
				$ok = $this->M_Poliklinik->insert('medical_record',$data);

				if($ok){	
					echo json_encode(array("status" => "1"));
					
					$kode    = $this->db->query("SELECT * from tbl_urutrs where kode_urut='URUT_ODON' and koders='$koders'")->row();

					$this->db->query("UPDATE tbl_urutrs set nourut=$kode->nourut+1 WHERE kode_urut='URUT_ODON' and koders='$koders'  ");
				}else{
					echo json_encode(array("status" => "3"));
				}
			}

		} else {
			header('location:'.base_url());
		}
	}

	// Cetak
	
	public function cetak_odonto(){

		// $cek    	  = '1';
		$chari   	  = '';
		$cekk         = $this->session->userdata('level');
		$unit   	  = $this->session->userdata('unit');
		$avatar   	  = $this->session->userdata('avatar_cabang');
		$profile      = $this->M_global->_LoadProfileLap();
		$nama_usaha   = $profile->nama_usaha;
		$motto        = '';
		$alamat       = '';
		$rekmed       = $this->input->get('rekmed');
		$noreg        = $this->input->get('noreg');
		$cek          = $this->input->get('cekk');
		if (!empty($cekk)) {			
			$kop               = $this->M_cetak->kop($unit);
			$data['namars']    = $kop['namars'];
			$data['alamat']    = $kop['alamat'];
			$data['alamat2']   = $kop['alamat2'];
			$data['phone']     = $kop['phone'];
			$data['whatsapp']  = $kop['whatsapp'];
			$data['npwp']      = $kop['npwp'];
			$data['rekmed']    = $rekmed;
			$data['noreg']     = $rekmed;
			$data['org_logo']  = "".base_url()."assets/img_user/$avatar";

			$chari             = $this->load->view('poliklinik/report_odontogram', $data, TRUE);
			$data['prev']      = $chari;
			$judul             = 'LAPORAN ODONTOGRAM';

			switch ($cek) {
				case 0;
					echo ("<title>LAPORAN ODONTOGRAM</title>");
					echo ($chari);
					break;

				case 1;
					$this->M_cetak->mpdf('P', '', $judul, $chari, 'LAPORAN_FARMASI_PEMBELIAN_DETAIL.PDF', 10, 10, 10, 2);
					break;

				case 2;
					header("Cache-Control: no-cache, no-store, must-revalidate");
					header("Content-Type: application/vnd-ms-excel");
					header("Content-Disposition: attachment; filename= $judul.xls");
					$this->load->view('app/master_cetak', $data);
					break;
			}
		} else {

			header('location:' . base_url());
		}
	}

	public function cetak_suket($param_unit, $param_noreg, $param_rekmed, $param_type){
		$cek = '1';
		$chari ='';
		$check_suket	= $this->db->query("SELECT * FROM tbl_rekammedisrs WHERE koders = '$param_unit' AND noreg = '$param_noreg'");

		if($check_suket->num_rows() == 0){
			redirect("/poliklinik/pemeriksaan_dokter/?noreg=". $param_noreg ."&rekmed=". $param_rekmed);
		} else {
			$pasien		= $this->db->query("SELECT * FROM pasien_rajal WHERE noreg = '$param_noreg'")->row();
			$head		= $this->db->query("SELECT * FROM tbl_namers WHERE koders = '$param_unit'")->row();
			$judul		= ($param_type == "sakit")? "SURAT KETERANGAN SAKIT" : "SURAT KETERANGAN DOKTER";
			$data		= $check_suket->row();
			$avatar   	= $this->session->userdata('avatar_cabang');

			$comp_name	= $head->namars;
			$comp_addr	= $head->alamat;
			$comp_addr2	= $head->alamat2;
			$comp_phone	= $head->phone;
			$comp_wa	= $head->whatsapp;
			$comp_npwp	= $head->npwp;
			$comp_image	= base_url()."assets/img_user/$avatar";

			// Umur
			$age_date = new DateTime($pasien->tgllahir);
 			$age_now = new DateTime();
 			$age_interval = $age_now->diff($age_date);

			$jenis_kelamin	= ($pasien->jkel == "P")? "PRIA" : "WANITA";
			$umur			= $age_interval->y .' Tahun '. $age_interval->m .' Bulan '. $age_interval->d .' Hari';
			$alamat			= $pasien->alamat .", ". $pasien->alamat2;
			$sehat			= ($data->sehat == "1")? "Sehat" : "Tidak Sehat";

			// Suket Sakit
			if($param_type == "sakit"){
				$chari  .= "<style>
					.table {border-collapse:collapse;font-family: Century Gothic;font-size:12px;color:#000;width:100%;margin:auto}
					.bordered {padding:5px;border:1px solid #222}
					.bt {padding-top:5px}
					.bm {padding-bottom:5px}
					.centered {text-align:center;margin:auto}
					.bold {font-weight:bold}
					.subtitle {font-size:12px;padding-bottom:15px !important}
					.title {font-size:16px;margin-top:10px;margin-bottom:20px}
					.separator {border:115px solid #222}
				</style>";

				$chari  .= '<table class="table" align="center">	
					<thead>
						<tr>
							<td rowspan="4">
								<img src="'. $comp_image .'"  width="100" height="70" />
							</td>
							<td>
								<tr><td style="font-size:14px;border-bottom: none;"><b>'. $comp_name .'</b></td></tr>
								<tr><td style="font-size:13px;">'. $comp_addr .'</td></tr>
								<tr><td style="font-size:13px;">Telp : '. $comp_phone .'</td></tr>
							</td>
						</tr> 
					</thead>
				</table>';

				$chari  .= "<hr class=\"separator\">
				<div class='title centered' style='font-weight:bold'>". $judul ."</div>";

				$chari	.= '<table class="table">
					<tr>
						<td colspan="3" style="padding-bottom:20px">Yang bertanda tangan dibawah ini menerangkan bahwa :</td>
					</tr>
					<tr>
						<td style="width:30%;padding-bottom:5px">&emsp;Nama</td>
						<td style="width:5%;padding-bottom:5px">:</td>
						<td style="width:75%;padding-bottom:5px;font-weight:bold">'. $pasien->namapas .' '. data_master("tbl_setinghms", array("kodeset" => $pasien->preposisi))->keterangan .'</td>
					</tr>
					<tr>
						<td style="width:30%;padding-bottom:5px">&emsp;Umur</td>
						<td style="width:5%;padding-bottom:5px">:</td>
						<td style="width:75%;padding-bottom:5px;font-weight:bold">'. $umur .'</td>
					</tr>
					<tr>
						<td style="width:30%;padding-bottom:5px">&emsp;Jenis Kelamin</td>
						<td style="width:5%;padding-bottom:5px">:</td>
						<td style="width:75%;padding-bottom:5px;font-weight:bold">'. $jenis_kelamin .'</td>
					</tr>
					<tr>
						<td style="width:30%;padding-bottom:5px">&emsp;Alamat</td>
						<td style="width:5%;padding-bottom:5px">:</td>
						<td style="width:75%;padding-bottom:5px;font-weight:bold">'. $alamat .'</td>
					</tr>
					<tr>
						<td style="width:30%;padding-bottom:5px">&emsp;Tinggi Badan</td>
						<td style="width:5%;padding-bottom:5px">:</td>
						<td style="width:75%;padding-bottom:5px;font-weight:bold">'. $data->tinggibadan .' cm</td>
					</tr>
					<tr>
						<td style="width:30%;padding-bottom:5px">&emsp;Berat Badan</td>
						<td style="width:5%;padding-bottom:5px">:</td>
						<td style="width:75%;padding-bottom:5px;font-weight:bold">'. number_format($data->beratbadan, 0 ,',','.') .' kg</td>
					</tr>
					<tr>
						<td style="width:30%;padding-bottom:5px">&emsp;Golongan Darah</td>
						<td style="width:5%;padding-bottom:5px">:</td>
						<td style="width:75%;padding-bottom:5px;font-weight:bold">'. $pasien->goldarah .'</td>
					</tr>
					<tr>
						<td style="width:30%;padding-bottom:5px">&emsp;Tekanan Darah</td>
						<td style="width:5%;padding-bottom:5px">:</td>
						<td style="width:75%;padding-bottom:5px;font-weight:bold">'. $data->tdarah .' / '. $data->tdarah1 .' mm Hg</td>
					</tr>
					<tr>
						<td style="width:30%;padding-bottom:20px">&emsp;Suhu</td>
						<td style="width:5%;padding-bottom:20px">:</td>
						<td style="width:75%;padding-bottom:20px;font-weight:bold">'. number_format($data->suhu, 0, ',', '.') .' °C</td>
					</tr>
					<tr>
						<td colspan="3" style="padding-bottom:20px">Berhubung dengan penyakitnya maka yang bersangkutan memerlukan istirahat selama <b>'. $data->ijinsakit .' Hari</b></td>
					</tr>
					<tr>
						<td style="width:30%;padding-bottom:5px">&emsp;Terhitung Mulai Tanggal</td>
						<td style="width:5%;padding-bottom:5px">:</td>
						<td style="width:75%;padding-bottom:5px;font-weight:bold">'. date("d-m-Y", strtotime($data->ijindari)) .'</td>
					</tr>
					<tr>
						<td style="width:30%;padding-bottom:20px">&emsp;Sampai Dengan Tanggal</td>
						<td style="width:5%;padding-bottom:20px">:</td>
						<td style="width:75%;padding-bottom:20px;font-weight:bold">'. date("d-m-Y", strtotime($data->ijinsampai)) .'</td>
					</tr>
					<tr>
						<td colspan="3" style="padding-bottom:40px">Demikian, harap yang berkepentingan menjadi maklum. Terimakasih</td>
					</tr>
					<tr>
						<td>Diagnosa/Keterangan Pemeriksaan</td>
						<td colspan="2" align="center">'. $head->kota .', '. date("d-m-Y") .'<br />Dokter Pemeriksa,<br /><br /></td>
					</tr>
					<tr>
						<td><b>'. $data->diagnosa .'</b></td>
						<td colspan="2" align="center">
						<br /><br /><br /><br /><br />('. data_master("dokter", array("kodokter" => $data->kodokter, "koders" => $param_unit, "kopoli" => $data->kodepos))->nadokter .')</td>
					</tr>
				</table>';
				switch ($cek) {
					case 0;
						echo ("<title>SURAT KETERANGAN SAKIT</title>");
						echo ($chari);
						break;
	
					case 1;
					$this->M_cetak->mpdf('P','A4',$judul, $chari,($param_type == "sakit")? "SURAT KETERANGAN SAKIT" : "SURAT KETERANGAN DOKTER" . '.PDF', 0, 0, 10, 2);
						break;
					case 2;
						header("Cache-Control: no-cache, no-store, must-revalidate");
						header("Content-Type: application/vnd-ms-excel");
						header("Content-Disposition: attachment; filename= $judul.xls");
						$this->load->view('app/master_cetak', $data);
						break;
				}
			} else 

			// Suket Sehat
			if($param_type == "sehat"){
				$chari  .= "<style>
					.table {border-collapse:collapse;font-family: Century Gothic;font-size:12px;color:#000;width:100%;margin:auto}
					.bordered {padding:5px;border:1px solid #222}
					.bt {padding-top:5px}
					.bm {padding-bottom:5px}
					.centered {text-align:center;margin:auto}
					.bold {font-weight:bold}
					.subtitle {font-size:12px;padding-bottom:15px !important}
					.title {font-size:16px;margin-top:10px;margin-bottom:20px}
					.separator {border:115px solid #222}
				</style>";

				$chari  .= '<table class="table" align="center">	
					<thead>
						<tr>
							<td rowspan="4">
								<img src="'. $comp_image .'"  width="100" height="70" />
							</td>
							<td>
								<tr><td style="font-size:14px;border-bottom: none;"><b>'. $comp_name .'</b></td></tr>
								<tr><td style="font-size:13px;">'. $comp_addr .'</td></tr>
								<tr><td style="font-size:13px;">Telp : '. $comp_phone .'</td></tr>
							</td>
						</tr> 
					</thead>
				</table>';

				$chari  .= "<hr class=\"separator\">
				<div class='title centered' style='font-weight:bold'>". $judul ."</div>";

				$chari	.= '<table class="table">
					<tr>
						<td colspan="3" style="padding-bottom:20px">Yang bertanda tangan dibawah ini menerangkan bahwa :</td>
					</tr>
					<tr>
						<td style="width:30%;padding-bottom:5px">&emsp;Nama</td>
						<td style="width:5%;padding-bottom:5px">:</td>
						<td style="width:75%;padding-bottom:5px;font-weight:bold">'. $pasien->namapas .' '. data_master("tbl_setinghms", array("kodeset" => $pasien->preposisi))->keterangan .'</td>
					</tr>
					<tr>
						<td style="width:30%;padding-bottom:5px">&emsp;Umur</td>
						<td style="width:5%;padding-bottom:5px">:</td>
						<td style="width:75%;padding-bottom:5px;font-weight:bold">'. $umur .'</td>
					</tr>
					<tr>
						<td style="width:30%;padding-bottom:5px">&emsp;Jenis Kelamin</td>
						<td style="width:5%;padding-bottom:5px">:</td>
						<td style="width:75%;padding-bottom:5px;font-weight:bold">'. $jenis_kelamin .'</td>
					</tr>
					<tr>
						<td style="width:30%;padding-bottom:5px">&emsp;Alamat</td>
						<td style="width:5%;padding-bottom:5px">:</td>
						<td style="width:75%;padding-bottom:5px;font-weight:bold">'. $alamat .'</td>
					</tr>
					<tr>
						<td style="width:30%;padding-bottom:5px">&emsp;Tinggi Badan</td>
						<td style="width:5%;padding-bottom:5px">:</td>
						<td style="width:75%;padding-bottom:5px;font-weight:bold">'. $data->tinggibadan .' cm</td>
					</tr>
					<tr>
						<td style="width:30%;padding-bottom:5px">&emsp;Berat Badan</td>
						<td style="width:5%;padding-bottom:5px">:</td>
						<td style="width:75%;padding-bottom:5px;font-weight:bold">'. number_format($data->beratbadan, 0 ,',','.') .' kg</td>
					</tr>
					<tr>
						<td style="width:30%;padding-bottom:5px">&emsp;Golongan Darah</td>
						<td style="width:5%;padding-bottom:5px">:</td>
						<td style="width:75%;padding-bottom:5px;font-weight:bold">'. $pasien->goldarah .'</td>
					</tr>
					<tr>
						<td style="width:30%;padding-bottom:5px">&emsp;Tekanan Darah</td>
						<td style="width:5%;padding-bottom:5px">:</td>
						<td style="width:75%;padding-bottom:5px;font-weight:bold">'. $data->tdarah .' / '. $data->tdarah1 .' mm Hg</td>
					</tr>
					<tr>
						<td style="width:30%;padding-bottom:20px">&emsp;Suhu</td>
						<td style="width:5%;padding-bottom:20px">:</td>
						<td style="width:75%;padding-bottom:20px;font-weight:bold">'. number_format($data->suhu, 0, ',', '.') .' °C</td>
					</tr>
					<tr>
						<td colspan="3" style="padding-bottom:10px">Pada pemeriksaan jasmani saat ini, dalam keadaan :</td>
					</tr>
					<tr>
						<td colspan="3" style="padding-bottom:20px"><b>'. $sehat .', '. $data->ketsehat .'</b></td>
					</tr>
					<tr>
						<td colspan="3" style="padding-bottom:10px">Surat Keterangan Dokter ini dipergunakan untuk keperluan :</td>
					</tr>
					<tr>
						<td colspan="3" style="padding-bottom:40px"><b>'. $data->ketsehatuntuk .'</b></td>
					</tr>
					<tr>
						<td colspan="3" style="padding-bottom:40px">Demikian, harap yang berkepentingan menjadi maklum. Terimakasih</td>
					</tr>
					<tr>
						<td>Diagnosa/Keterangan Pemeriksaan</td>
						<td colspan="2" align="center">'. $head->kota .', '. date("d-m-Y") .'<br />Dokter Pemeriksa,<br /><br /></td>
					</tr>
					<tr>
						<td><b>'. $data->diagnosa .'</b></td>
						<td colspan="2" align="center">
						<br /><br /><br /><br /><br />('. data_master("dokter", array("kodokter" => $data->kodokter, "koders" => $param_unit, "kopoli" => $data->kodepos))->nadokter .')</td>
					</tr>
				</table>';
				switch ($cek) {
					case 0;
						echo ("<title>SURAT KETERANGAN SAKIT</title>");
						echo ($chari);
						break;
	
					case 1;
					$this->M_cetak->mpdf('P','A4',$judul, $chari,($param_type == "sakit")? "SURAT KETERANGAN SAKIT" : "SURAT KETERANGAN DOKTER" . '.PDF', 0, 0, 10, 2);
						break;
					case 2;
						header("Cache-Control: no-cache, no-store, must-revalidate");
						header("Content-Type: application/vnd-ms-excel");
						header("Content-Disposition: attachment; filename= $judul.xls");
						$this->load->view('app/master_cetak', $data);
						break;
				}
			} else

			// Suket Null
			{
				redirect("/poliklinik/pemeriksaan_dokter/?noreg=". $param_noreg ."&rekmed=". $param_rekmed);
			}
		}
	}

	public function return_bhp(){
		$cabang		= $this->session->userdata("unit");
		$kodebarang	= $this->input->post("kodebarang");
		$gudang		= $this->input->post("gudang");
		$qty		= $this->input->post("qty");
		$noreg		= $this->input->post("noreg");

		// Lose Stock
		$check_stock	= $this->db->query("SELECT * FROM tbl_barangstock WHERE kodebarang = '". $kodebarang ."' AND koders = '$cabang' AND gudang = '$gudang'");

		if($check_stock->num_rows() == 0){
			$return_bhp	= $this->db->query("INSERT INTO tbl_barangstock (koders,kodebarang,gudang,keluar,saldoakhir) 
			VALUES ('$cabang', '". $kodebarang ."', '$gudang', '". $qty ."', '". $qty ."')");
		} else {
			$return_bhp	= $this->db->query("UPDATE tbl_barangstock SET keluar = keluar-". $qty .", saldoakhir = saldoakhir+". $qty ." WHERE kodebarang = '". $kodebarang ."' AND koders = '$cabang'  AND gudang = '$gudang'");
		}

		// $this->db->query("UPDATE tbl_barangstock SET keluar = keluar+". $c_qtyalkes[$i] .", saldoakhir = saldoakhir-". $c_qtyalkes[$i] ." WHERE kodebarang = '". $c_kdalkes[$i] ."' AND koders = '$cabang'  AND gudang = '$gudang_bhp'");

		if($return_bhp){
			$this->db->delete("tbl_alkestransaksi", array("koders" => $cabang, "notr" => $noreg, "kodeobat" => $kodebarang));
			echo json_encode(array("status" => true));
		} else {
			echo json_encode(array("status" => false));
		}
	}

}