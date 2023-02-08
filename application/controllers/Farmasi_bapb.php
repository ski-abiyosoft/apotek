<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Farmasi_bapb extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_farmasi_bapb', 'M_farmasi_bapb');
		$this->load->helper('simkeu_rpt');
		$this->load->helper('simkeu_nota');
		$this->session->set_userdata('menuapp', '3000');
		$this->session->set_userdata('submenuapp', '3102');
	}

	public function index()
	{
		$cek = $this->session->userdata('username');
		if (!empty($cek)) {
			$level = $this->session->userdata('level');
			$akses = $this->M_global->cek_menu_akses($level, 3102);
			$this->load->helper('url');
			$data['modul'] = 'APOTEK';
			$data['submodul'] = 'BAPB';
			$data['link'] = 'Penerimaan Barang';
			$data['url'] = 'farmasi_bapb';
			$data['tanggal'] = date('d-m-Y');
			$data['akses'] = $akses;
			$this->load->view('farmasi/v_farmasi_bapb', $data);
		} else {
			header('location:' . base_url());
		}
	}

	public function getdatapo()
	{
		$nomorpo = $this->input->get('nopo');
		echo $this->M_farmasi_bapb->_datapo($nomorpo);
	}

	public function gethpo2($po)
	{
		$data = $this->db->query("SELECT *,(select keterangan from tbl_depo b where a.gudang=b.depocode )gud_name FROM tbl_baranghpo a WHERE a.po_no = '$po'")->row();
		echo json_encode($data);
	}

	/*public function cekppn()
	{   	    

		$sql      = "SELECT * FROM tbl_pajak where kodetax='PPN'";
        $query1   = $this->db->query($sql);
        $result   = array();
        $ii       = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kodetax' => $resulte['kodetax'],
                'prosentase' => $resulte['prosentase']

            );
            $ii++;
        }

        echo json_encode($result);
		$query1->free_result();
		
	}*/

	public function cekppn()
	{
		$query_ppn = $this->db->query("SELECT * FROM tbl_pajak where kodetax='PPN'");
		if ($query_ppn->num_rows() == 0) {
			echo json_encode(array("status" => 0));
		} else {
			echo json_encode($query_ppn->row());
		}
	}

	public function getpo($po)
	{
		$data = $this->db->query("SELECT a.*, b.namabarang ,b.het
		FROM tbl_barangdpo a
		LEFT JOIN tbl_barang b ON b.kodebarang=a.kodebarang WHERE po_no = '$po'")->result();
		echo json_encode($data);
	}

	public function add()
	{
		$cek = $this->session->userdata('username');
		if (!empty($cek)) {
			$level = $this->session->userdata('level');
			$akses = $this->M_global->cek_menu_akses($level, 3102);
			$this->load->helper('url');
			$data['modul']    = 'APOTEK';
			$data['submodul'] = 'Berita Acara Penerimaan Barang';
			$data['link']     = 'BA Penerimaan Barang';
			$data['url']      = 'farmasi_bapb';
			$data['tanggal']  = date('d-m-Y');
			$data['akses']    = $akses;
			$data['nomorpo']  = urut_transaksi('SETUP_APO', 19);
			$data['ppn'] = $this->db->get_where('tbl_pajak', ['kodetax' => 'PPN'])->row_array();
			$this->load->view('farmasi/v_farmasi_bapb_add', $data);
		} else {
			header('location:' . base_url());
		}
	}

	function cekharga()
	{
		$kode = $this->input->get('kode');
		$harga = $this->input->get('harga');
		$cek = $this->db->query('select * from tbl_barang where kodebarang = "' . $kode . '"')->row_array();
		if ($harga < (int)$cek['hargabeli']) {
			$data = [
				'harga' => $cek['hargabeli'],
				'status' => 1
			];
			echo json_encode($data);
		} else {
			$data = [
				'harga' => $harga,
				'status' => 2
			];
			echo json_encode($data);
		}
	}



	public function ubahdata($param)
	{
		$cek = $this->session->userdata('username');
		$cabang   = $this->session->userdata('unit');
		$gudang   = $this->input->post('gudang');
		$faktur   = $this->input->post('nofaktur');

		$tanggal  = date('Y-m-d');
		$jam      = date('H:i:s');
		$nomorpo  = $this->input->post('nomorpo');

		if ($param == 1) {
			$nobukti  = urut_transaksi('SJ_TERIMA', 19);
		} else {
			$nobukti = $this->input->post('noterima');
		}
		if ($this->input->post('pembayaran') == 'CASH') {
			$jenisbeli = 0;	//tunai
		} else {
			$jenisbeli = 1;	//kredit	
		}
		$data = array(
			'koders'      => $cabang,
			'terima_no'   => $nobukti,
			'terima_date' => $this->input->post('tanggal'),
			'due_date'    => $this->input->post('jatuhtempo'),
			'tgltukar'    => $this->input->post('tanggaltukar'),
			'vendor_id'   => $this->input->post('supp'),
			'sj_no'       => $this->input->post('nomorsj'),
			'invoice_no'  => $this->input->post('nofaktur'),
			'gudang'      => $this->input->post('gudang'),
			'kurs'        => $this->input->post('kurs'),
			'kursrate'    => $this->input->post('rate'),
			'materai'     => $this->input->post('materai'),
			'ongkir'      => $this->input->post('ongkir'),
			'bkemasan'    => $this->input->post('kemasan'),
			'diskontotal' => $this->input->post('diskonrp'),
			'term'        => $this->input->post('pembayaran'),
			'jenisbeli'   => $jenisbeli,
			// 'userid'      => $userid,
			'ppn'         => '',
			'vatrp'       => 0,
			'useredit'    => $this->input->post('alasan'),
		);
		if ($param == 1) {
			$insert = $this->db->insert('tbl_baranghterima', $data);
		} else {
			$this->db->query("DELETE from tbl_barangdterima where terima_no = '$nobukti'");
			$this->db->query("DELETE from tbl_apoap where terima_no = '$nobukti'");
		}

		$kode   = $this->input->post('kode');
		$qty    = $this->input->post('qty');
		$sat    = $this->input->post('sat');
		$harga  = $this->input->post('harga');
		$disc   = $this->input->post('disc');
		$discrp = $this->input->post('discrp');
		$tax    = $this->input->post('tax');
		// if($taxx == null){
		// 	$tax = 0;
		// } else {
		// 	$tax = 1;
		// }

		$jumlah = $this->input->post('jumlah');
		$expire = $this->input->post('expire');
		$po     = $this->input->post('po');

		$jumdata    = count($kode);
		$totppn     = 0;
		$totdis     = 0;
		$tot        = 0;
		// print_r ($tax); tetap mati
		for ($i = 0; $i <= $jumdata - 1; $i++) {
			$_harga    = str_replace(',', '', $harga[$i]);
			$_disc     = str_replace(',', '', $disc[$i]);
			$_jumlah   = str_replace(',', '', $jumlah[$i]);
			$_discrp   = str_replace(',', '', $discrp[$i]);
			$_qty      = $qty[$i];
			$_kode     = $kode[$i];

			$diskon    = $_jumlah * ($_disc / 100);

			$totdis    += $diskon;
			$tot       += $_jumlah;
			// if($tax[$i] == ''){
			// 	$_vat = 0;		
			// } else {
			// 	$_vat = 1;
			// 	$totppn += $_jumlah*(11/100);
			// }
			if ($tax[$i] != 0) {
				$_vat = 0;
			} else {
				$_vat = 1;
				$totppn += $_jumlah * (11 / 100);
			}
			// echo json_encode($nomorpo, JSON_PRETTY_PRINT);
			$data_rinci = array(
				'terima_no'  => $nobukti,
				'koders'     => $cabang,
				// 'po_no'      => $nomorpo, tetap mati
				'kodebarang' => $kode[$i],
				'qty_terima' => $qty[$i],
				'price'      => $_harga,
				'satuan'     => $sat[$i],
				'discount'   => $disc[$i],
				'discountrp' => $_discrp,
				'vat'        => $_vat,
				'po_no'      => $po[$i],
				'exp_date'   => date('Y-m-d', strtotime($expire[$i])),
				'totalrp'    => $_jumlah,
				'vatrp' => 0,
			);
		}
		// if($this->input->post('pembayaran')=='CASH'){
		// 	$jenisbeli = 0;	//tunai
		// } else {
		// 	$jenisbeli = 1;	//kredit	
		// }
		// $qcek = $this->db->query("SELECT * FROM tbl_apoap WHERE invoice_no = '$faktur' and koders='$cabang'")->result_array();
		// $cek = count($qcek);
		// // echo json_encode($qcek);
		// if ($cek > 0) {
		// 	echo json_encode(array("status" => "1","nomor" => $nobukti));
		// } else if($cek == 0) {
		// 	$qcek1 = $this->db->query("SELECT * FROM tbl_baranghterima WHERE invoice_no = '$faktur' and koders='$cabang' ")->result_array();
		// 	$cek1 = count($qcek1);
		// 	if ($cek1 > 0) {
		// 		echo json_encode(array("status" => "1","nomor" => $nobukti));
		// 	} else {
		// 		$data = array(
		// 			'koders'      => $cabang,
		// 			'terima_no'   => $nobukti,
		// 			'terima_date' => $this->input->post('tanggal'),
		// 			'due_date'    => $this->input->post('jatuhtempo'),
		// 			'tgltukar'    => $this->input->post('tanggaltukar'),
		// 			'vendor_id'   => $this->input->post('supp'),
		// 			'sj_no'       => $this->input->post('nomorsj'),
		// 			'invoice_no'  => $this->input->post('nofaktur'),
		// 			'gudang'      => $this->input->post('gudang'),
		// 			'kurs'        => $this->input->post('kurs'),
		// 			'kursrate'    => $this->input->post('rate'),
		// 			'materai'     => $this->input->post('materai'),
		// 			'ongkir'      => $this->input->post('ongkir'),
		// 			'bkemasan'    => $this->input->post('kemasan'),
		// 			'diskontotal' => $this->input->post('diskonrp'),
		// 			'term'        => $this->input->post('pembayaran'),
		// 			'jenisbeli'   => $jenisbeli,
		// 			'userid'      => $userid,
		// 			'ppn'         => '',
		// 			'vatrp'       => 0,
		// 			// 'alasan' => '',
		// 		);




	}

	public function edit($id)
	{
		$cek = $this->session->userdata('username');
		if (!empty($cek)) {
			$level    = $this->session->userdata('level');
			$akses    = $this->M_global->cek_menu_akses($level, 3102);
			$this->load->helper('url');
			$data['modul']    = 'APOTEK';
			$data['submodul'] = 'Berita Acara Penerimaan Barang';
			$data['link']     = 'BA Penerimaan Barang';
			$data['url']      = 'farmasi_bapb';
			$data['tanggal']  = date('d-m-Y');
			$data['akses']    = $akses;
			$header   = $this->db->query("SELECT (select po_no from tbl_barangdterima b where a.terima_no=b.terima_no limit 1)po_no,a.* from tbl_baranghterima a where terima_no = '$id'")->row();

			$detil    = $this->db->query("SELECT tbl_barangdterima.*, tbl_barang.namabarang,tbl_barang.het from tbl_barangdterima
		  inner join tbl_barang on tbl_barangdterima.kodebarang=tbl_barang.kodebarang
		  where terima_no = '$id'");

			$data['nobukti']    = $id;
			$data['header']     = $header;
			$data['ppn2']       = $this->db->query("SELECT * FROM tbl_pajak where kodetax='PPN'")->row();
			//   $data['header2']    = $header->result();
			$data['detil']      = $detil->result();
			$data['jumdata']    = $detil->num_rows();
			$this->load->view('farmasi/v_farmasi_bapb_edit', $data);
		} else {
			header('location:' . base_url());
		}
	}


	public function ajax_list($param)
	{
		$user_level   = $this->session->userdata('user_level');
		$level        = $this->session->userdata('level');
		$akses        = $this->M_global->cek_menu_akses($level, 3102);
		$lock         = $this->M_global->close_app();
		$dat          = explode("~", $param);
		if ($dat[0] == 1) {
			$bulan = date('m');
			$tahun = date('Y');
			$list = $this->M_farmasi_bapb->get_datatables(1, $bulan, $tahun);
		} else {
			$bulan  = date('Y-m-d', strtotime($dat[1]));
			$tahun  = date('Y-m-d', strtotime($dat[2]));
			$list = $this->M_farmasi_bapb->get_datatables(2, $bulan, $tahun);
		}

		$data = array();
		$no = $_POST['start'];
		foreach ($list as $rd) {
			$no++;

			if ($rd->closed == '0') {
				$status = '<span class="label label-sm label-warning">Open</span>';
			} else {
				$status = '<span class="label label-sm label-danger">Closed</span> ';
			}

			$cek_apo = $this->db->query("SELECT * FROM tbl_apoap WHERE terima_no = '$rd->terima_no'")->row();

			$row   = array();
			$row[] = '<span "font-weight:bold;"><b>' . $rd->koders . '</b></span>';
			$row[] = '<span "font-weight:bold;"><b>' . $rd->username . '</b></span>';
			$row[] = '<span "font-weight:bold;"><b>' . $rd->terima_no . '</b></span>';
			$row[] = '<span "font-weight:bold;"><b>' . date('d-m-Y', strtotime($rd->terima_date)) . '</b></span>';
			$row[] = '<span "font-weight:bold;"><b>' . $rd->vendor_name . '</b></span>';
			$row[] = '<span "font-weight:bold;"><b>' . $rd->sj_no . '</b></span>';
			$row[] = '<span "font-weight:bold;"><b>' . $rd->invoice_no . '</b></span>';
			$gd = $this->db->get_where("tbl_depo", ['depocode' => $rd->gudang])->row();
			$row[] = '<span "font-weight:bold;"><b>' . $gd->keterangan . '</b></span>';

			if($cek_apo->tukarfaktur < 1) {
				if($user_level==0){
					$row[] = '<div class="text-center">
					<a target="_blank" class="btn btn-sm btn-warning" href="' . base_url("farmasi_bapb/cetak/?id=" . $rd->terima_no . "") . '" title="Cetak" ><i class="glyphicon glyphicon-print"></i> </a></div>';
				}else{
					if ($akses->uedit == 1 && $akses->udel == 1 && $lock == 0) {
						$row[] = '<div class="text-center">
						<a class="btn btn-sm btn-primary" href="' . base_url("farmasi_bapb/edit/" . $rd->terima_no . "") . '" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a>
						<a target="_blank" class="btn btn-sm btn-warning" href="' . base_url("farmasi_bapb/cetak/?id=" . $rd->terima_no . "") . '" title="Cetak" ><i class="glyphicon glyphicon-print"></i> </a>
						<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data(' . "'" . $rd->id . "'" . ",'" . $rd->terima_no . "'" . ')"><i class="glyphicon glyphicon-trash"></i> </a></div>';
					} else if ($akses->uedit == 1 && $akses->udel == 0) {
						$row[] = '<a class="btn btn-sm btn-primary" href="' . base_url("farmasi_bapb/edit/" . $rd->id . "") . '" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a> ';
					} else if ($akses->uedit == 0 && $akses->udel == 1) {
						$row[] = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data(' . "'" . $rd->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i> </a>';
					} else {
						$row[] = '';
					}
				}
			} else {
				if($user_level==0){
					$row[] = '<div class="text-center">
					<a target="_blank" class="btn btn-sm btn-warning" href="' . base_url("farmasi_bapb/cetak/?id=" . $rd->terima_no . "") . '" title="Cetak" ><i class="glyphicon glyphicon-print"></i> </a></div>';
				}else{
					if ($akses->uedit == 1 && $akses->udel == 1 && $lock == 0) {
						$row[] = '<div class="text-center">
						<button disabled type="button" class="btn btn-sm btn-primary" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </button>
						<a target="_blank" class="btn btn-sm btn-warning" href="' . base_url("farmasi_bapb/cetak/?id=" . $rd->terima_no . "") . '" title="Cetak" ><i class="glyphicon glyphicon-print"></i> </a>
						<button class="btn btn-sm btn-danger" type="button" title="Hapus" disabled><i class="glyphicon glyphicon-trash"></i> </button></div>';
					} else if ($akses->uedit == 1 && $akses->udel == 0) {
						$row[] = '<button class="btn btn-sm btn-primary" type="button" disabled title="Edit" ><i class="glyphicon glyphicon-edit"></i> </button> ';
					} else if ($akses->uedit == 0 && $akses->udel == 1) {
						$row[] = '<button type="button" class="btn btn-sm btn-danger" title="Hapus" disabled><i class="glyphicon glyphicon-trash"></i> </button>';
					} else {
						$row[] = '';
					}
				}
			}
			

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->M_farmasi_bapb->count_all($dat[0], $bulan, $tahun),
			"recordsFiltered" => $this->M_farmasi_bapb->count_filtered($dat[0],  $bulan, $tahun),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function getinfobarang()
	{
		$kode = $this->input->get('kode');
		// $data = $this->M_global->_data_barang( $kode );
		$data = $this->db->get_where('tbl_barang', ['kodebarang' => $kode])->row();
		echo json_encode($data);
	}

	public function cekhari()
	{
		$kode = $this->input->get('kode');
		$data = $this->db->select('tbl_setinghms.valuerp');
		$data = $this->db->get_where('tbl_setinghms', array('kodeset' => $kode))->row();
		echo json_encode($data);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_akuntansi_sa->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function save_one()
	{
		$cabang   = $this->session->userdata('unit');
		$userid   = $this->session->userdata('username');
		$gudang   = $this->input->post('gudang');
		$faktur   = $this->input->post('nofaktur');
		$tanggal  = date('Y-m-d');
		$jam      = date('H:i:s');
		$nomorpo  = $this->input->post('nomorpo');
		$nobukti  = urut_transaksi('SJ_TERIMA', 19);
		if ($this->input->post('pembayaran') == 'CASH') {
			$jenisbeli = 0;
		} else {
			$jenisbeli = 1;
		}
		$qcek = $this->db->query("SELECT * FROM tbl_apoap WHERE invoice_no = '$faktur' and koders='$cabang'")->result_array();
		$cek = count($qcek);
		if ($cek > 0) {
			echo json_encode(array("status" => "1", "nomor" => $nobukti));
		} else {
			$qcek1 = $this->db->query("SELECT * FROM tbl_baranghterima WHERE invoice_no = '$faktur' and koders='$cabang' ")->result_array();
			$cek1 = count($qcek1);
			if ($cek1 > 0) {
				echo json_encode(array("status" => "1", "nomor" => $nobukti));
			} else {
				$data = array(
					'koders'      => $cabang,
					'terima_no'   => $nobukti,
					'terima_date' => $this->input->post('tanggal'),
					'due_date'    => $this->input->post('jatuhtempo'),
					'tgltukar'    => $this->input->post('tanggaltukar'),
					'vendor_id'   => $this->input->post('supp'),
					'sj_no'       => $this->input->post('nomorsj'),
					'invoice_no'  => $this->input->post('nofaktur'),
					'gudang'      => $this->input->post('gudang'),
					'kurs'        => $this->input->post('kurs'),
					'kursrate'    => $this->input->post('rate'),
					'materai'     => $this->input->post('materai'),
					'ongkir'      => $this->input->post('ongkir'),
					'bkemasan'    => $this->input->post('kemasan'),
					'diskontotal' => $this->input->post('diskonrp'),
					'term'        => $this->input->post('pembayaran'),
					'jenisbeli'   => $jenisbeli,
					'userid'      => $userid,
					'ppn'         => '',
					'vatrp'       => 0,
					'jamterima'   => date('H:i:s'),
				);
				$insert = $this->db->insert('tbl_baranghterima', $data);
			}
			$ppn = $this->db->get_where('tbl_pajak', ['kodetax' => 'PPN'])->row_array();
			$data_ap = array(
				'koders'        => $cabang,
				'terima_no'     => $nobukti,
				'invoice_no'    => $this->input->post('nofaktur'),
				'tglinvoice'    => $this->input->post('tanggal'),
				'duedate'       => $this->input->post('jatuhtempo'),
				'vendor_id'     => $this->input->post('supp'),
				'totaltagihan'  => 0,
				'totalbayar'    => 0,
				'username'      => $userid,
				'term'          => $this->input->post('pembayaran'),
				'ppn'		 => $ppn['prosentase'],
			);
			$this->db->insert('tbl_apoap', $data_ap);
			echo json_encode(['status' => 2, 'nomor' => $nobukti]);
		}
	}

	function save_multi()
	{
		$cabang       = $this->session->userdata('unit');
		$gudang       = $this->input->post('gudang');
		$terima_no    = $this->input->get('terima_no');
		$kode         = $this->input->get('kode');
		$qty          = $this->input->get('qty');
		$sat          = $this->input->get('sat');
		$harga        = $this->input->get('harga');
		$het          = $this->input->get('het');
		$disc         = $this->input->get('disc');
		$discrp       = $this->input->get('discrp');
		$vat          = $this->input->get('vat');
		$jumlah       = $this->input->get('jumlah');
		$expire       = $this->input->get('expire');
		$po           = $this->input->get('po');
		$vatrp        = $this->input->get('vatrp');
		$po_no        = $this->input->post('nomorpo');

		if ($harga != '' && $kode != '') {
			$ppn   = $this->db->get_where('tbl_pajak', ['kodetax' => 'PPN'])->row_array();
			$cekq  = $this->db->query('select * from tbl_barang where kodebarang = "' . $kode . '"')->result();
			foreach ($cekq as $cq) {
				if($harga > $cq->hargabeli){
					if ($cq->vat == 1) {
						$hargabarang = $harga;
						$hargappn = $harga * $ppn['prosentase'] / 100 + $harga;
					} else {
						$hargabarang = $harga;
						$hargappn = $harga;
					}
					$this->db->set('hargabeli', $hargabarang);
					$this->db->set('hargabelippn', $hargappn);
					$this->db->where('kodebarang', $kode);
					$this->db->update('tbl_barang');
				}
			}
			$this->db->set('het', $het);
			$this->db->where('kodebarang', $kode);
			$this->db->update('tbl_barang');
			// $sql = $this->db->query('select terima_no from tbl_baranghterima where koders = "'.$cabang.'" and gudang = "'.$gudang.'" order by id desc limit 1')->result();
			// foreach($sql as $s){
			if ($po_no == '') {
				$pox = '';
			} else {
				$pox = $po_no;
			}
			$data = [
				'koders' => $cabang,
				'terima_no' => $terima_no,
				'kodebarang' => $kode,
				'qty_terima' => $qty,
				'satuan' => $sat,
				'price' => $harga,
				'discount' => $disc,
				'discountrp' => $discrp,
				'vat' => $vat,
				'vatrp' => $vatrp,
				'totalrp' => $jumlah,
				'exp_date' => date('Y-m-d H:i:s', strtotime($expire)),
				'po_no' => $pox,
			];
			$this->db->insert('tbl_barangdterima', $data);
			if($pox != ''){
				$this->db->set('cek', 1);
				$this->db->where("po_no", $pox);
				$this->db->update("tbl_baranghpo");
			}
			$stokcek = $this->db->query("SELECT * FROM tbl_barangstock WHERE kodebarang = '$kode' and koders='$cabang' and gudang='$gudang'");
			$date_now = date('Y-m-d H:i:s');
			if ($stokcek->num_rows() > 0) {
				$stok = $stokcek->result();
				foreach ($stok as $key => $value) {
					$terima = (int)$value->terima + $qty;
					$saldoakhir = (int)$value->saldoakhir + $qty;
					$this->db->query("UPDATE tbl_barangstock set terima=$terima, saldoakhir=$saldoakhir, lasttr = '$date_now' where kodebarang='$kode' and koders='$cabang' and gudang='$gudang'");
				}
			} else {
				$datastock = array(
					'koders'       => $cabang,
					'kodebarang'   => $kode,
					'gudang'       => $gudang,
					'saldoawal'    => 0,
					'terima'       => $qty,
					'saldoakhir'   => $qty,
					'lasttr'       => $date_now,
				);
				$this->db->insert('tbl_barangstock', $datastock);
			}
			// }
		}
	}

	public function data_awal_terima()
	{
		$kode = $this->input->get('kode');
		$gudang = $this->input->get('gudang');
		$terima_no = $this->input->get('terima_no');
		$cabang = $this->session->userdata('unit');
		$data_barang = $this->db->query("SELECT * FROM tbl_barangdterima WHERE kodebarang = '$kode' AND koders = '$cabang' AND terima_no = '$terima_no'")->row_array();
		echo json_encode($data_barang);
	}

	public function save_one_u()
	{
		$totvatrp = $this->input->get('totvatrp');
		$totaltagihan = $this->input->get('totaltagihan');
		$diskontotal = $this->input->get('diskontotal');
		$ppnrp = $this->input->get('ppnrp');
		$cabang = $this->session->userdata('unit');
		$gudang   = $this->input->post('gudang');
		$sql = $this->db->query('select * from tbl_baranghterima where koders = "' . $cabang . '" and gudang = "' . $gudang . '" order by id desc limit 1')->result();
		foreach ($sql as $s) {
			if ($sql != null) {
				$this->db->set('vatrp', $totvatrp);
				$this->db->set('diskontotal', $diskontotal);
				$this->db->where('koders', $cabang);
				$this->db->where('terima_no', $s->terima_no);
				$this->db->update('tbl_baranghterima');

				$this->db->set('totaltagihan', $totaltagihan);
				$this->db->set('ppnrp', $ppnrp);
				$this->db->where('koders', $cabang);
				$this->db->where('terima_no', $s->terima_no);
				$this->db->update('tbl_apoap');
			}
		}
	}

	public function update_one()
	{
		$cabang   = $this->session->userdata('unit');
		$userid   = $this->session->userdata('username');
		$gudang   = $this->input->post('gudang');
		$faktur   = $this->input->post('nofaktur');
		$terimano   = $this->input->get('terimano');
		$tanggal  = date('Y-m-d');
		$jam      = date('H:i:s');
		$nomorpo  = $this->input->post('nomorpo');
		if ($this->input->post('pembayaran') == 'CASH') {
			$jenisbeli = 0;
		} else {
			$jenisbeli = 1;
		}
		$ppn = $this->db->get_where('tbl_pajak', ['kodetax' => 'PPN'])->row_array();
		$this->db->set('terima_date', $this->input->post('tanggal'));
		$this->db->set('due_date', $this->input->post('jatuhtempo'));
		$this->db->set('tgltukar', $this->input->post('tanggaltukar'));
		$this->db->set('vendor_id', $this->input->post('supp'));
		$this->db->set('sj_no', $this->input->post('nomorsj'));
		$this->db->set('invoice_no', $this->input->post('nofaktur'));
		$this->db->set('gudang', $this->input->post('gudang'));
		$this->db->set('kurs', $this->input->post('kurs'));
		$this->db->set('kursrate', $this->input->post('rate'));
		$this->db->set('materai', $this->input->post('materai'));
		$this->db->set('ongkir', $this->input->post('ongkir'));
		$this->db->set('bkemasan', $this->input->post('kemasan'));
		$this->db->set('diskontotal', $this->input->post('diskonrp'));
		$this->db->set('term', $this->input->post('pembayaran'));
		$this->db->set('jenisbeli', $jenisbeli);
		$this->db->set('userid', $userid);
		$this->db->set('ppn', $ppn['prosentase']);
		$this->db->set('jamterima', $jam);
		$this->db->where('koders', $cabang);
		$this->db->where('terima_no', $terimano);
		$this->db->update('tbl_baranghterima');

		$dterima = $this->db->get_where('tbl_barangdterima', ['terima_no' => $terimano])->result();
		foreach ($dterima as $row) {
			$_qty  = $row->qty_terima;
			$_kode = $row->kodebarang;
			$this->db->query("UPDATE tbl_barangstock set terima = terima - $_qty, saldoakhir = saldoakhir - $_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang'");
		}
		$this->db->query("DELETE from tbl_barangdterima where terima_no = '$terimano'");
		$this->db->query("DELETE from tbl_apoap where terima_no = '$terimano'");
		$this->db->query("UPDATE tbl_baranghterima set vatrp = '0' where terima_no='$terimano'");
		$data_ap = array(
			'koders'        => $cabang,
			'terima_no'     => $terimano,
			'invoice_no'    => $this->input->post('nofaktur'),
			'tglinvoice'    => $this->input->post('tanggal'),
			'duedate'       => $this->input->post('jatuhtempo'),
			'materai'       => $this->input->post('materai'),
			'vendor_id'     => $this->input->post('supp'),
			'totaltagihan'  => 0,
			'totalbayar'    => 0,
			'ppn'    => $ppn['prosentase'],
			'username'      => $userid,
			'term'          => $this->input->post('pembayaran'),
		);
		$this->db->insert('tbl_apoap', $data_ap);
		echo json_encode(["status" => 2, "nomor" => $terimano]);
	}

	function update_multi()
	{
		$cabang = $this->session->userdata('unit');
		$gudang   = $this->input->post('gudang');
		$kode = $this->input->get('kode');
		$qty = $this->input->get('qty');
		$sat = $this->input->get('sat');
		$harga = $this->input->get('harga');
		$disc = $this->input->get('disc');
		$discrp = $this->input->get('discrp');
		$vat = $this->input->get('vat');
		$jumlah = $this->input->get('jumlah');
		$expire = $this->input->get('expire');
		$po = $this->input->get('po');
		$vatrp = $this->input->get('vatrp');
		$po_no  = $this->input->post('nomorpo');
		$terimano   = $this->input->get('terimano');

		if ($harga != '' && $kode != '') {
			$ppn = $this->db->get_where('tbl_pajak', ['kodetax' => 'PPN'])->row_array();
			$cekq = $this->db->query('select * from tbl_barang where kodebarang = "' . $kode . '"')->result();
			foreach ($cekq as $cq) {
				if ($cq->vat == 1) {
					$hargabarang = $harga;
					$hargappn = $harga * $ppn['prosentase'] / 100 + $harga;
				} else {
					$hargabarang = $harga;
					$hargappn = $harga;
				}
				$this->db->set('hargabeli', $hargabarang);
				$this->db->set('hargabelippn', $hargappn);
				$this->db->where('kodebarang', $kode);
				$this->db->update('tbl_barang');
			}
			$data = [
				'koders' => $cabang,
				'terima_no' => $terimano,
				'kodebarang' => $kode,
				'qty_terima' => $qty,
				'satuan' => $sat,
				'price' => $harga,
				'discount' => $disc,
				'discountrp' => $discrp,
				'vat' => $vat,
				'vatrp' => $vatrp,
				'totalrp' => $jumlah,
				'exp_date' => date('Y-m-d H:i:s', strtotime($expire)),
				'po_no' => $po_no,
			];
			$this->db->insert('tbl_barangdterima', $data);
			$stokcekx = $this->db->query("SELECT * FROM tbl_barangstock WHERE kodebarang = '$kode' and koders='$cabang' and gudang='$gudang' ")->num_rows();
			$date_now = date('Y-m-d H:i:s');
			if ($stokcekx > 0) {
				$this->db->query("UPDATE tbl_barangstock set terima = terima+ $qty, saldoakhir = saldoakhir + $qty, lasttr = '$date_now' where kodebarang = '$kode' and koders = '$cabang' and gudang = '$gudang'");
			} else {
				$datastock = array(
					'koders'       => $cabang,
					'kodebarang'   => $kode,
					'gudang'       => $gudang,
					'saldoawal'    => 0,
					'terima'       => $qty,
					'saldoakhir'   => $qty,
					'lasttr'       => $date_now,
				);
				$this->db->insert('tbl_barangstock', $datastock);
			}
		}
	}

	public function update_one_u()
	{
		$totvatrp = $this->input->get('totvatrp');
		$totaltagihan = $this->input->get('totaltagihan');
		$diskontotal = $this->input->get('diskontotal');
		$ppnrp = $this->input->get('ppnrp');
		$cabang = $this->session->userdata('unit');
		$gudang   = $this->input->post('gudang');
		$terimano   = $this->input->get('terimano');
		$ppn = $this->db->get_where('tbl_pajak', ['kodetax' => 'PPN'])->row_array();
		$this->db->set('vatrp', $totvatrp);
		$this->db->set('diskontotal', $diskontotal);
		$this->db->where('koders', $cabang);
		$this->db->where('terima_no', $terimano);
		$this->db->update('tbl_baranghterima');

		$this->db->set('totaltagihan', $totaltagihan);
		$this->db->set('ppnrp', $ppnrp);
		$this->db->where('koders', $cabang);
		$this->db->where('terima_no', $terimano);
		$this->db->update('tbl_apoap');
	}

	public function ajax_add($param)
	{
		$cabang   = $this->session->userdata('unit');
		$userid   = $this->session->userdata('username');
		$gudang   = $this->input->post('gudang');
		$faktur   = $this->input->post('nofaktur');


		$tanggal  = date('Y-m-d');
		$jam      = date('H:i:s');
		$nomorpo  = $this->input->post('nomorpo');


		if ($param == 1) {
			$nobukti  = urut_transaksi('SJ_TERIMA', 19);
		} else {
			$nobukti = $this->input->post('noterima');
		}
		//$this->_validate(); tetap mati
		if ($this->input->post('pembayaran') == 'CASH') {
			$jenisbeli = 0;	//tunai
		} else {
			$jenisbeli = 1;	//kredit	
		}
		$qcek = $this->db->query("SELECT * FROM tbl_apoap WHERE invoice_no = '$faktur' and koders='$cabang'")->result_array();
		$cek = count($qcek);
		// echo json_encode($qcek);
		if ($cek > 0) {
			echo json_encode(array("status" => "1", "nomor" => $nobukti));
		} else if ($cek == 0) {
			$qcek1 = $this->db->query("SELECT * FROM tbl_baranghterima WHERE invoice_no = '$faktur' and koders='$cabang' ")->result_array();
			$cek1 = count($qcek1);
			if ($cek1 > 0) {
				echo json_encode(array("status" => "1", "nomor" => $nobukti));
			} else {
				$data = array(
					'koders'      => $cabang,
					'terima_no'   => $nobukti,
					'terima_date' => $this->input->post('tanggal'),
					'due_date'    => $this->input->post('jatuhtempo'),
					'tgltukar'    => $this->input->post('tanggaltukar'),
					'vendor_id'   => $this->input->post('supp'),
					'sj_no'       => $this->input->post('nomorsj'),
					'invoice_no'  => $this->input->post('nofaktur'),
					'gudang'      => $this->input->post('gudang'),
					'kurs'        => $this->input->post('kurs'),
					'kursrate'    => $this->input->post('rate'),
					'materai'     => $this->input->post('materai'),
					'ongkir'      => $this->input->post('ongkir'),
					'bkemasan'    => $this->input->post('kemasan'),
					'diskontotal' => $this->input->post('diskonrp'),
					'term'        => $this->input->post('pembayaran'),
					'jenisbeli'   => $jenisbeli,
					'userid'      => $userid,
					'ppn'         => '',
					'vatrp'       => 0,
				);
				if ($param == 1) {
					$insert = $this->db->insert('tbl_baranghterima', $data);
				} else {
					$update = $this->db->update('tbl_baranghterima', $data, array('terima_no' => $nobukti));
					$datalpb = $this->db->get_where('tbl_barangdterima', array('terima_no' => $nobukti))->result();
					foreach ($datalpb as $row) {
						$_qty  = $row->qty_terima;
						$_kode = $row->kodebarang;
						$this->db->query("UPDATE tbl_barangstock set terima=terima- $_qty, saldoakhir= saldoakhir-$_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang'");
					}
					$this->db->query("DELETE from tbl_barangdterima where terima_no = '$nobukti'");
					$this->db->query("DELETE from tbl_apoap where terima_no = '$nobukti'");
				}
				// $tax    = 0; tetap mati
				$kode   = $this->input->post('kode');
				$qty    = $this->input->post('qty');
				$sat    = $this->input->post('sat');
				$harga  = $this->input->post('harga');
				$disc   = $this->input->post('disc');
				$discrp = $this->input->post('discrp');
				$tax    = $this->input->post('tax');
				// if($taxx == null){
				// 	$tax = 0;
				// } else {
				// 	$tax = 1;
				// }

				$jumlah = $this->input->post('jumlah');
				$expire = $this->input->post('expire');
				$po     = $this->input->post('po');

				$jumdata    = count($kode);
				$totppn     = 0;
				$totdis     = 0;
				$tot        = 0;
				// print_r ($tax); tetap mati
				for ($i = 0; $i <= $jumdata - 1; $i++) {
					$_harga    = str_replace(',', '', $harga[$i]);
					$_disc     = str_replace(',', '', $disc[$i]);
					$_jumlah   = str_replace(',', '', $jumlah[$i]);
					$_discrp   = str_replace(',', '', $discrp[$i]);
					$_qty      = $qty[$i];
					$_kode     = $kode[$i];

					$diskon    = $_jumlah * ($_disc / 100);

					$totdis    += $diskon;
					$tot       += $_jumlah;
					// if($tax[$i] == ''){
					// 	$_vat = 0;		
					// } else {
					// 	$_vat = 1;
					// 	$totppn += $_jumlah*(11/100);
					// }
					if ($tax[$i] != 0) {
						$_vat = 0;
					} else {
						$_vat = 1;
						$totppn += $_jumlah * (11 / 100);
					}
					// echo json_encode($nomorpo, JSON_PRETTY_PRINT);
					$data_rinci = array(
						'terima_no'  => $nobukti,
						'koders'     => $cabang,
						// 'po_no'      => $nomorpo, tetap mati
						'kodebarang' => $kode[$i],
						'qty_terima' => $qty[$i],
						'price'      => $_harga,
						'satuan'     => $sat[$i],
						'discount'   => $disc[$i],
						'discountrp' => $_discrp,
						'vat'        => $_vat,
						'po_no'      => $po[$i],
						'exp_date'   => date('Y-m-d', strtotime($expire[$i])),
						'totalrp'    => $_jumlah,
						'vatrp' => 0,
					);
					if ($kode[$i] != "") {
						$insert_detil = $this->db->insert('tbl_barangdterima', $data_rinci);
						$stokcek = $this->db->query("SELECT * FROM tbl_barangstock WHERE kodebarang = '$_kode' and koders='$cabang' and gudang='$gudang' ")->result_array();
						$scek = count($stokcek);
						if ($scek > 0) {
							$this->db->query("UPDATE tbl_barangstock set terima=terima+ $_qty, saldoakhir= saldoakhir+ $_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang'");
						} else {
							$datastock = array(
								'koders'       => $cabang,
								'kodebarang'   => $_kode,
								'gudang'       => $gudang,
								'saldoawal'    => 0,
								'terima'       => $_qty,
								'saldoakhir'   => $_qty,
								'tglso'        => $this->input->post('tanggal'),
								'lasttr'       => $this->input->post('tanggal'),
							);
							$insert_detil = $this->db->insert('tbl_barangstock', $datastock);
						}
					}
				}
				$this->db->query("UPDATE tbl_baranghterima set vatrp = '$totppn' where terima_no='$nobukti'");
				$data_ap = array(
					'koders'        => $cabang,
					'terima_no'     => $nobukti,
					'invoice_no'    => $this->input->post('nofaktur'),
					'tglinvoice'    => $this->input->post('tanggal'),
					'duedate'       => $this->input->post('jatuhtempo'),
					'vendor_id'     => $this->input->post('supp'),
					'totaltagihan'  => $tot,
					'totalbayar'    => 0,
					'term'          => $this->input->post('pembayaran'),
				);
				$this->db->insert('tbl_apoap', $data_ap);
				echo json_encode([
					'status' => 2,
					'nomor' => $nobukti
				]);
			}
		}
	}


	public function ajax_delete()
	{
		$cabang   = $this->session->userdata('unit');
		$id       = $this->input->post('id');
		$terima_no = $this->input->get('terima_no');
		$data     = $this->db->get_where('tbl_baranghterima', array('terima_no' => $terima_no))->row();
		$nomor    = $data->terima_no;
		$gudang   = $data->gudang;

		$databeli = $this->db->get_where('tbl_barangdterima', array('terima_no' => $nomor))->result();
		foreach ($databeli as $row) {

			$_qty  = $row->qty_terima;
			$_kode = $row->kodebarang;

			$this->db->query("UPDATE tbl_barangstock set terima=terima- $_qty, saldoakhir= saldoakhir- $_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang'");
			$nomor_po = $row->po_no;
		}

		if ($nomor_po != '' || $nomor_po != null) {
			$this->db->set('cek', 0);
			$this->db->where("po_no", $nomor_po);
			$this->db->update("tbl_baranghpo");
		}

		$this->db->delete('tbl_baranghterima', array('terima_no' => $terima_no));
		$this->db->delete('tbl_barangdterima', array('terima_no' => $terima_no, 'koders' => $cabang));
		$this->db->query("DELETE from tbl_apoap where terima_no = '$terima_no'");
		echo json_encode(array("status" => 1));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('nomorbukti') == '') {
			$data['inputerror'][] = 'nomorbukti';
			$data['error_string'][] = 'Kode  harus diisi';
			$data['status'] = FALSE;
		}


		if ($data['status'] === FALSE) {
			echo json_encode($data);
			exit();
		}
	}

	public function cetak()
	{
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');
		$user = $this->session->userdata('username');
		if (!empty($cek)) {

			$unit = $this->session->userdata('unit');
			$profile = data_master('tbl_namers', array('koders' => $unit));
			$nama_usaha = $profile->namars;
			$alamat1  = $profile->alamat;
			$alamat2  = $profile->kota;

			$param = $this->input->get('id');

			$queryh = "SELECT * from tbl_baranghterima inner join 
			tbl_vendor on tbl_baranghterima.vendor_id=tbl_vendor.vendor_id 
			where tbl_baranghterima.terima_no = '$param'";

			$queryd = "SELECT tbl_barangdterima.*, tbl_barang.namabarang from tbl_barangdterima inner join 
			tbl_barang on tbl_barangdterima.kodebarang=tbl_barang.kodebarang
			where terima_no = '$param'";

			$detil  = $this->db->query($queryd)->result();
			$header = $this->db->query($queryh)->row();

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
			$judul = array('SURAT PERNYATAAN & PEMBELIAN BARANG');
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
			$pdf->FancyRow(array('', '', $header->alamat, 'Tgl Faktur', ':', date('d-m-Y', strtotime($header->terima_date))), $fc, $border);
			$pdf->FancyRow(array('', '', '', 'Tgl Penerimaan', ':', date('d-m-Y', strtotime($header->terima_date))), $fc, $border);
			$pdf->FancyRow(array('', '', '', 'No. Faktur', ':', $header->invoice_no), $fc, $border);
			$pdf->FancyRow(array('', '', '', 'No. Surat Jalan', ':', $header->sj_no), $fc, $border);
			$border = array('LB', 'B', 'B', 'B', 'B', 'BR');
			$gd = $this->db->get_where('tbl_depo', ['depocode' => $header->gudang])->row();
			$pdf->FancyRow(array('', '', $header->phone, 'Gudang', ':', $gd->keterangan), $fc, $border);


			$pdf->ln(2);
			$pdf->SetWidths(array(10, 20, 32, 15, 15, 15, 15, 15, 18, 35));
			$border = array('LTBR', 'LTBR', 'LTBR', 'LTBR', 'LTBR', 'LTBR', 'LTBR', 'LTBR', 'LTBR', 'LTBR');
			$align  = array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C');
			$pdf->setfont('Arial', 'B', 8);
			$pdf->SetAligns(array('L', 'C', 'R'));
			$fc = array('0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
			$judul = array('No.', 'Kode Barang', 'Nama Barang', 'Qty', 'Satuan', 'HPP', 'Disc', 'Total', 'Expired', 'Po No');
			$pdf->FancyRow2(8, $judul, $fc, $border, $align);
			$pdf->setfont('Arial', '', 8);
			$tot = 0;
			$subtot = 0;
			$tdisc  = 0;
			$border = array('LTBR', 'LTBR', 'LTBR', 'LTBR', 'LTBR', 'LTBR', 'LTBR', 'LTBR','LTBR', 'LTBR');
			$align  = array('C', 'L', 'L', 'R', 'R', 'R', 'R', 'R', 'L', 'L');
			$style = array('', '', '', '', '', '', '', '', '', '');
			$size  = array('8', '8', '8', '8', '8', '8', '8', '8','8', '8');
			$max   = array(2, 2, 2, 2, 2, 2, 2, 2,2, 2);
			$fc     = array('0', '0', '0', '0', '0', '0', '0', '0','0', '0');
			$no = 1;
			$totitem = 0;
			$tot = 0;
			$diskon = 0;
			foreach ($detil as $db) {
				$hpp = data_master('tbl_barang', array('kodebarang' => $db->kodebarang))->hpp;
				$xxx = $db->qty_terima * $db->price;
				$tot += $xxx;
				if ($db->discount == '') {
					$diskon += 0;
				} else {
					$diskon += ($db->discount / 100) * ($db->qty_terima * $hpp);
				}
				$pdf->FancyRow(array(
					$no,
					$db->kodebarang,
					$db->namabarang,
					number_format($db->qty_terima),
					$db->satuan,
					number_format($db->price),
					number_format($db->discountrp),
					number_format($db->totalrp),
					date('d-m-Y', strtotime($db->exp_date)),
					$db->po_no
				), $fc,  $border, $align, $style, $size, $max);

				$no++;
			}
			$discount = $header->diskontotal;
			$dpp = ($tot - $discount) / (111 / 100);
			$ppn = str_replace(',', '.', $header->vatrp);;

			$materai = $header->materai;
			$totalnet = $tot + $ppn + $materai - $discount;
			// $totalnet = str_replace(',', '.', $header->vatrp);
			$pdf->SetWidths(array(10, 25, 30, 15, 15, 20, 20, 20, 35));
			$pdf->SetWidths(array(4, 40, 30, 40, 20, 20, 35));
			$border = array('T', 'T', 'T', 'T', 'T', 'T', 'T');
			$align  = array('L', 'C', 'C', 'C', 'R', 'R');
			$style = array('', 'B', '', '', '', '');
			$judul = array('', '', '', '', 'TOTAL', number_format($tot), '');
			$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
			$judul = array('', 'Form Rangkap 2', '', '', 'Discount', number_format($discount),);
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
			$judul = array('KEPALA APOTEKTER', $header->vendor_name, 'PENANGGUNG JAWAB ADM');
			$pdf->FancyRow2(5, $judul, $fc,  $border, $align, $style, $size, $max);




			$pdf->setTitle($param);
			$pdf->AliasNbPages();
			$pdf->output($param . '.PDF', 'I');
		} else {

			header('location:' . base_url());
		}
	}
}