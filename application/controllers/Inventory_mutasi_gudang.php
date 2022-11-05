<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_mutasi_gudang extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_mutasi_gudang');
		$this->load->helper('simkeu_rpt');
		$this->load->helper('simkeu_nota');
		$this->session->set_userdata('menuapp', '3000');
		$this->session->set_userdata('submenuapp', '3303');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$this->load->view('inventory/v_inventory_mutasi_gudang');
		} else {
			header('location:' . base_url());
		}
	}

	public function ajax_list($param)
	{
		$dat   = explode("~", $param);
		if ($dat[0] == 1) {
			$bulan = date('m');
			$tahun = date('Y');
			$list = $this->M_mutasi_gudang->get_datatables(1, $bulan, $tahun);
		} else {
			$bulan  = date('Y-m-d', strtotime($dat[1]));
			$tahun  = date('Y-m-d', strtotime($dat[2]));
			$list = $this->M_mutasi_gudang->get_datatables(2, $bulan, $tahun);
		}

		$data = array();
		$no = $_POST['start'];
		foreach ($list as $item) {
			$dari = $this->db->query("SELECT * FROM tbl_depo WHERE depocode = '$item->dari'")->row();
			$ke = $this->db->query("SELECT * FROM tbl_depo WHERE depocode = '$item->ke'")->row();
			$no++;
			$row = array();
			$row[] = $item->koders;
			$row[] = $item->username;
			$row[] = $item->moveno;
			$row[] = $item->mohonno;
			$row[] = date('d-m-Y', strtotime($item->movedate));
			$row[] = $dari->keterangan;
			$row[] = $ke->keterangan;
			$row[] = $item->keterangan;
			$row[] =
				'
				<a class="btn btn-sm btn-primary" href="' . base_url("inventory_mutasi_gudang/edit/" . $item->id . "") . '" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a>
				<a target="_blank" class="btn btn-sm btn-warning" href="' . base_url("inventory_mutasi_gudang/cetak/?id=" . $item->moveno . "") . '" title="Cetak" ><i class="glyphicon glyphicon-print"></i> </a>
				 <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data(' . "'" . $item->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>';

			$data[] = $row;
		}

		// echo '<a class="btn btn-sm btn-primary" href="'.base_url("inventory_mutasi_gudang/edit/".$item->id."").'" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a>';

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->M_mutasi_gudang->count_all($dat[0], $bulan, $tahun),
			"recordsFiltered" => $this->M_mutasi_gudang->count_filtered($dat[0], $bulan, $tahun),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function edit($id)
	{
		$cek = $this->session->userdata('level');
		$uid = $this->session->userdata('unit');
		if (!empty($cek)) {
			$d['pic'] = $this->session->userdata('username');
			$header   = $this->db->query("select * from tbl_apohmove where id = '$id'");
			$nomohon  = $header->row()->moveno;
			$detil    = $this->db->query("select * from tbl_apodmove where moveno = '$nomohon'");
			$d['jumdata'] = $detil->num_rows();
			$d['header'] = $header->row();
			$d['detil']  = $detil->result();
			$this->load->view('inventory/v_inventory_mutasi_gudang_edit', $d);
		} else {
			header('location:' . base_url());
		}
	}

	public function save_one()
	{
		$cek = $this->session->userdata('level');
		$cabang   = $this->session->userdata('unit');
		$userid   = $this->session->userdata('username');
		$gudang_asal  = $this->input->post('gudang_asal');
		$gudang_tujuan  = $this->input->post('gudang_tujuan');
		$tanggal  = $this->input->post('tanggal');
		$nomorbukti = urut_transaksi('APO_MOVE', 20);
		$keterangan = $this->input->post('ket');
		$nomohonx = $this->input->post('nomohon');
		if ($nomohonx == '') {
			$nomohon = '';
		} else {
			$nomohon = $nomohonx;
		}
		if (!empty($cek)) {
			$data = [
				'koders' => $cabang,
				'moveno' => $nomorbukti,
				'mohonno' => $nomohon,
				'movedate' => $tanggal,
				'dari' => $gudang_asal,
				'ke' => $gudang_tujuan,
				'keterangan' => $keterangan,
				'diterima' => '',
				'username' => $userid,
				'jammove' => date('H:i:s')
			];
			$this->db->insert('tbl_apohmove', $data);
			echo json_encode(['status' => 1, 'nomorbukti' => $nomorbukti, 'keterangan' => $keterangan]);
		} else {
			header('location:' . base_url());
		}
	}

	public function save_multi()
	{
		$cek = $this->session->userdata('level');
		$cabang   = $this->session->userdata('unit');
		$userid   = $this->session->userdata('username');
		$gudang_asal  = $this->input->post('gudang_asal');
		$gudang_tujuan  = $this->input->post('gudang_tujuan');
		$tanggal  = $this->input->post('tanggal');
		$nomorbukti  = $this->input->get('nomorbukti');
		$kode  = $this->input->get('kode');
		$qty   = $this->input->get('qty');
		$sat   = $this->input->get('sat');
		$harga = $this->input->get('harga');
		$total   = $this->input->get('total');
		$expire   = $this->input->get('expire');
		$note   = $this->input->get('note');
		$keterangan = $this->input->get('keterangan');
		$nomohonx = $this->input->post('nomohon');
		if ($nomohonx == '') {
			$nomohon = '';
		} else {
			$nomohon = $nomohonx;
		}
		$hpp1 = $this->db->get_where('tbl_barang', ['kodebarang' => $kode])->row_array();
		$hpp = $hpp1['hpp'];
		$data = [
			'koders' => $cabang,
			'moveno' => $nomorbukti,
			'kodebarang' => $kode,
			'satuan' => $sat,
			'qtymove' => $qty,
			'harga' => $harga,
			'totalharga' => $total,
			'hpp' => $hpp,
			'keterangan' => $note,
			'exp_date' => $expire,
		];
		if (!empty($cek)) {
			$date_now = date('Y-m-d H:i:s');
			$this->db->insert('tbl_apodmove', $data);
			$check = $this->db->query("SELECT * FROM tbl_barangstock WHERE koders = '$cabang' AND kodebarang = '$kode' AND gudang = '$gudang_asal'");
			if ($check->num_rows() == 0) {
				$datas = [
					"koders" 			=> $cabang,
					"kodebarang" 	=> $kode,
					"gudang" 			=> $gudang_asal,
					"keluar" 			=> $qty,
					"terima" 			=> 0,
					"saldoakhir"  => 0-$qty,
					"lasttr" 			=> $date_now
				];
				$this->db->insert('tbl_barangstock', $datas);
			} else {
				$this->db->query("UPDATE tbl_barangstock set keluar=keluar+$qty, saldoakhir= saldoakhir-$qty, lasttr = '$date_now' where kodebarang = '$kode' and koders = '$cabang' and gudang = '$gudang_asal'");
			}
			$check2 = $this->db->query("SELECT * FROM tbl_barangstock WHERE koders = '$cabang' AND kodebarang = '$kode' AND gudang = '$gudang_tujuan'");
			if($check2->num_rows() == 0){
				$datas2 = [
					"koders" 			=> $cabang,
					"kodebarang" 	=> $kode,
					"gudang" 			=> $gudang_tujuan,
					"terima" 			=> $qty,
					"keluar" 			=> 0,
					"saldoakhir"  => $qty,
					"lasttr" 			=> $date_now
				];
				$this->db->insert('tbl_barangstock', $datas2);
			} else {
				$this->db->query("UPDATE tbl_barangstock set terima=terima+$qty, saldoakhir=saldoakhir+$qty, lasttr = '$date_now' where kodebarang = '$kode' and koders = '$cabang' and gudang = '$gudang_tujuan'");
			}
		} else {
			header('location:' . base_url());
		}
	}

	public function update_one()
	{
		$cek = $this->session->userdata('level');
		$cabang   = $this->session->userdata('unit');
		$userid   = $this->session->userdata('username');
		$gudang_asal  = $this->input->post('gudang_asal');
		$gudang_tujuan  = $this->input->post('gudang_tujuan');
		$tanggal  = $this->input->post('tanggal');
		$nomorbukti = $this->input->post('nomorbukti');
		$keterangan = $this->input->post('ket');
		$nomohonx = $this->input->post('nomohon');
		if ($nomohonx == '') {
			$nomohon = '';
		} else {
			$nomohon = $nomohonx;
		}
		if (!empty($cek)) {
			$this->db->set('koders', $cabang);
			$this->db->set('mohonno', $nomohon);
			$this->db->set('movedate', $tanggal);
			$this->db->set('dari', $gudang_asal);
			$this->db->set('ke', $gudang_tujuan);
			$this->db->set('keterangan', $keterangan);
			$this->db->set('username', $userid);
			$this->db->where('moveno', $nomorbukti);
			$this->db->update('tbl_apohmove');
			$datamutasi = $this->db->get_where('tbl_apodmove', array('moveno' => $nomorbukti))->result();
			foreach ($datamutasi as $row) {
				$_qty = $row->qtymove;
				$_kode = $row->kodebarang;
				$this->db->query("update tbl_barangstock set terima=terima- $_qty, saldoakhir= saldoakhir- $_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang_tujuan'");
				$this->db->query("update tbl_barangstock set keluar=keluar- $_qty, saldoakhir= saldoakhir+ $_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang_asal'");
			}
			$this->db->where('moveno', $nomorbukti);
			$this->db->delete('tbl_apodmove');
			echo json_encode(['status' => 1, 'nomorbukti' => $nomorbukti, 'keterangan' => $keterangan]);
		} else {
			header('location:' . base_url());
		}
	}
	public function update_multi()
	{
		$cek = $this->session->userdata('level');
		$cabang   = $this->session->userdata('unit');
		$userid   = $this->session->userdata('username');
		$gudang_asal  = $this->input->post('gudang_asal');
		$gudang_tujuan  = $this->input->post('gudang_tujuan');
		$tanggal  = $this->input->post('tanggal');
		$nomorbukti  = $this->input->get('nomorbukti');
		$kode  = $this->input->get('kode');
		$qty   = $this->input->get('qty');
		$sat   = $this->input->get('sat');
		$harga = $this->input->get('harga');
		$total   = $this->input->get('total');
		$expire   = $this->input->get('expire');
		$note   = $this->input->get('note');
		$keterangan = $this->input->get('keterangan');
		$nomohonx = $this->input->post('nomohon');
		if ($nomohonx == '') {
			$nomohon = '';
		} else {
			$nomohon = $nomohonx;
		}
		$hpp1 = $this->db->get_where('tbl_barang', ['kodebarang' => $kode])->row_array();
		$hpp = $hpp1['hpp'];
		if (!empty($cek)) {
			$data = [
				'koders' => $cabang,
				'moveno' => $nomorbukti,
				'kodebarang' => $kode,
				'satuan' => $sat,
				'qtymove' => $qty,
				'harga' => $harga,
				'totalharga' => $total,
				'hpp' => $hpp,
				'keterangan' => $note,
				'exp_date' => $expire,
			];
			$this->db->insert('tbl_apodmove', $data);
			$date_now = date('Y-m-d H:i:s');
			$check = $this->db->query("SELECT * FROM tbl_barangstock WHERE koders = '$cabang' AND kodebarang = '$kode' AND gudang = '$gudang_asal'");
			if ($check->num_rows() == 0) {
				$datas = [
					"koders" => $cabang,
					"kodebarang" => $kode,
					"gudang" => $gudang_asal,
					"keluar" => $qty,
					"terima" => 0,
					"saldoakhir"  => 0-$qty,
					"tglso" => $tanggal,
					"lasttr" => $tanggal
				];
				$this->db->insert('tbl_barangstock', $datas);
			} else {
				$this->db->query("update tbl_barangstock set keluar=keluar+$qty, saldoakhir= saldoakhir-$qty, lasttr = '$date_now' where kodebarang = '$kode' and koders = '$cabang' and gudang = '$gudang_asal'");
			}
			$check2 = $this->db->query("SELECT * FROM tbl_barangstock WHERE koders = '$cabang' AND kodebarang = '$kode' AND gudang = '$gudang_tujuan'");
			if($check2->num_rows() == 0) {
				$datas2 = [
					"koders" => $cabang,
					"kodebarang" => $kode,
					"gudang" => $gudang_tujuan,
					"terima" => $qty,
					"saldoakhir"  => $qty,
					"tglso" => $tanggal,
					"lasttr" => $tanggal
				];
				$this->db->insert('tbl_barangstock', $datas2);
			} else {
				$this->db->query("update tbl_barangstock set terima=terima+$qty, saldoakhir= saldoakhir+$qty, lasttr = '$date_now' where kodebarang = '$kode' and koders = '$cabang' and gudang = '$gudang_tujuan'");
			}
		} else {
			header('location:' . base_url());
		}
	}

	public function save($param)
	{
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');
		$uid = $this->session->userdata("username");

		if (!empty($cek)) {
			$cabang   = $this->session->userdata('unit');
			$userid   = $this->session->userdata('username');

			$gudang_asal  = $this->input->post('gudang_asal');
			$gudang_tujuan  = $this->input->post('gudang_tujuan');
			$tanggal  = $this->input->post('tanggal');
			$kode  = $this->input->post('hidekode');
			$qty   = $this->input->post('qty');
			$sat   = $this->input->post('sat');
			$harga = str_replace(",", "", $this->input->post('harga'));
			$total   = str_replace(",", "", $this->input->post('total'));
			$expire   = $this->input->post('expire');
			$note   = $this->input->post('note');
			$nomohonx = $this->input->post('nomohon');

			// $kode_array = array();

			// foreach($kode as $k){
			// 	$kode_array[] = $k;
			// }

			if ($nomohonx == '') {
				$nomohon = '';
			} else {
				$nomohon = $nomohonx;
			}

			if ($param == 1) {
				$nomorbukti = urut_transaksi('APO_MOVE', 20);
			} else {
				$nomorbukti = $this->input->post('nomorbukti');
			}

			$data_header = array(
				'koders' => $unit,
				'moveno' => $nomorbukti,
				'mohonno' => $nomohon,
				'movedate' => $tanggal,
				'dari' => $gudang_asal,
				'ke' => $gudang_tujuan,
				'keterangan' => $this->input->post('ket'),
				'diterima' => '',
				'username' => $uid

			);

			if ($param == 1) {
				$this->db->insert('tbl_apohmove', $data_header);

				// $id_mutasi = $this->db->insert_id();

				$jumdata = count($kode);
				for ($i = 0; $i <= $jumdata - 1; $i++) {
					$_kode   = $kode[$i];
					$_qty    = $qty[$i];
					$_harga  =  str_replace(',', '', $harga[$i]);
					$_total  =  str_replace(',', '', $total[$i]);

					// $hpp = $this->M_global->_data_barang($_kode)->hpp;
					$hpp1 = $this->db->get_where('tbl_barang', ['kodebarang' => $_kode])->row_array();
					$hpp = $hpp1['hpp'];


					$datad = array(
						'koders' => $unit,
						'moveno' => $nomorbukti,
						'kodebarang' => $_kode,
						'satuan' => $sat[$i],
						'qtymove' => $qty[$i],
						'harga' => $_harga,
						'totalharga' => $_total,
						'exp_date' => $expire[$i],
						'keterangan' => $note[$i],
						'hpp' => $hpp,
					);

					$datas = array(
						"koders" => $cabang,
						"kodebarang" => $_kode,
						"gudang" => $gudang_tujuan,
						"terima" => $_qty,
						"saldoakhir"  => $_qty,
						"tglso" => $tanggal,
						"lasttr" => $tanggal
					);

					if ($_kode != "") {
						$this->db->insert('tbl_apodmove', $datad);

						$check = $this->db->query("SELECT * FROM tbl_barangstock WHERE koders = '$cabang' AND kodebarang = '$_kode' AND gudang = '$gudang_tujuan'");
						if ($check->num_rows() == 0) {
							$this->db->insert('tbl_barangstock', $datas);
						} else {
							$this->db->query("update tbl_barangstock set terima=terima+ $_qty, saldoakhir= saldoakhir+ $_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang_tujuan'");
						}

						$this->db->query("update tbl_barangstock set keluar=keluar+ $_qty, saldoakhir= saldoakhir- $_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang_asal'");
					}
				}
			} else {
				$id_mutasi = $this->input->post('nomorbukti');
				$this->db->update('tbl_apohmove', $data_header, array('moveno' => $id_mutasi));

				for ($i = 1; $i <= count($kode) - 1; $i++) {
					$_kode   = $kode[$i];
					$_qty    = $qty[$i];
					$_harga  =  str_replace(',', '', $harga[$i]);
					$_total  =  str_replace(',', '', $total[$i]);

					$hpp = $this->M_global->_data_barang($_kode)->hpp;
					$datad = array(
						'koders' => $unit,
						'moveno' => $nomorbukti,
						'kodebarang' => $_kode,
						'satuan' => $sat[$i],
						'qtymove' => $qty[$i],
						'harga' => $_harga,
						'totalharga' => $_total,
						'exp_date' => $expire[$i],
						'keterangan' => $note[$i],
						'hpp' => $hpp,
					);

					$check_move = $this->db->query("SELECT * FROM tbl_apodmove WHERE koders = '$cabang', moveno = '$id_mutasi', kodebarang = '$_kode'");

					if ($check_move->num_rows() == 0) {
						$this->db->insert('tbl_apodmove', $datad);
						$this->db->query("update tbl_barangstock set terima=terima+ $_qty, saldoakhir= saldoakhir+ $_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang_tujuan'");

						$this->db->query("update tbl_barangstock set keluar=keluar+ $_qty, saldoakhir= saldoakhir- $_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang_asal'");
					}
				}
				// $datamutasi = $this->db->get_where('tbl_apodmove', array('moveno' => $id_mutasi))->result();
				// foreach($datamutasi as $key => $row){
				// 	$hpp = $this->M_global->_data_barang($row->kodebarang)->hpp;
				// 	$datad = array(
				// 			'koders' => $unit,
				// 			'moveno' => $nomorbukti,
				// 			'kodebarang' => $kaval,
				// 			'satuan' => $sat[$key],
				// 			'qtymove' => $qty[$key],
				// 			'harga' => $harga[$key],
				// 			'totalharga' => $total[$key],
				// 			'exp_date' => $expire[$key],
				// 			'keterangan' => $note[$key],
				// 			'hpp' => $key,
				// 			);
				// 	$this->db->insert('tbl_apodmove', $datad);

				// 	if($row->kodebarang == 0){
				// 		$_kode   = $kode[$i];
				// 		$_qty    = $qty[$i];
				// 		$_harga  =  str_replace(',','',$harga[$i]);
				// 		$_total  =  str_replace(',','',$total[$i]);


				// 	} else {
				// 		$_qty = $row->qtymove;
				// 		$_kode= $row->kodebarang;

				// 		$this->db->query("update tbl_barangstock set keluar = keluar+$_qty, saldoakhir = saldoakhir-$_qty where kodebarang = '$_kode'
				// 		and koders = '$cabang' and gudang = '$gudang_asal'");

				// 		$this->db->query("update tbl_barangstock set terima = terima+$_qty, saldoakhir = saldoakhir+$_qty where kodebarang = '$_kode'
				// 		and koders = '$cabang' and gudang = '$gudang_tujuan'");
				// 	}
				// }

				// foreach($datamutasi as $row){	
				// 	$_qty = $row->qtymove;
				// 	$_kode= $row->kodebarang;

				// 	$this->db->query("update tbl_barangstock set terima = terima+$_qty, saldoakhir = saldoakhir+$_qty where kodebarang = '$_kode'
				//       and koders = '$cabang' and gudang = '$gudang_tujuan'"); 
				// }   
				// $this->db->delete('tbl_apodmove', array('moveno' => $this->input->post('nomorbukti'))); 

			}

			echo $nomorbukti;
		} else {
			header('location:' . base_url());
		}
	}

	public function ajax_delete($id)
	{
		$hmutasi = $this->db->query("select * from tbl_apohmove where id = '$id'")->row();
		$nobukti = $hmutasi->moveno;
		$gudang_asal = $hmutasi->dari;
		$gudang_tujuan = $hmutasi->ke;

		$cabang   = $this->session->userdata('unit');
		$datamutasi = $this->db->get_where('tbl_apodmove', array('moveno' => $nobukti))->result();
		foreach ($datamutasi as $row) {
			$_qty = $row->qtymove;
			$_kode = $row->kodebarang;

			$this->db->query(
				"update tbl_barangstock set terima=terima- $_qty, saldoakhir= saldoakhir- $_qty where kodebarang = '$_kode'
		  and koders = '$cabang' and gudang = '$gudang_tujuan'"
			);

			$this->db->query(
				"update tbl_barangstock set keluar=keluar- $_qty, saldoakhir= saldoakhir+ $_qty where kodebarang = '$_kode'
		  and koders = '$cabang' and gudang = '$gudang_asal'"
			);
		}

		$this->db->query("delete from tbl_apohmove where id = '$id'");
		$this->db->query("delete from tbl_apodmove where moveno = '$nobukti'");
		echo json_encode(array("status" => TRUE));
	}

	public function cekqty()
	{
		$kode = $this->input->get('kode');
		$gudang = $this->input->get('gudang');
		$cabang = $this->session->userdata('unit');
		$cek = $this->db->query('select * from tbl_barangstock where kodebarang = "' . $kode . '" and koders = "' . $cabang . '" and gudang = "' . $gudang . '"')->row_array();
		// $cek = $this->db->get_where('tbl_barangstock', ['kodebarang' => $kode, 'koders' => $cabang, 'gudang' => $cabang])->row_array();
		$data = [
			'kodebarang' => $cek['kodebarang'],
			'gudang' => $cek['gudang'],
			'saldoakhir' => $cek['saldoakhir'],
		];
		echo json_encode($data);
	}

	public function entri()
	{
		$cek = $this->session->userdata('level');
		$uid = $this->session->userdata('unit');
		if (!empty($cek)) {
			$d['pic'] = $this->session->userdata('username');
			$d['nomor'] = 'AUTO';
			$this->load->view('inventory/v_inventory_mutasi_gudang_add', $d);
		} else {
			header('location:' . base_url());
		}
	}

	public function getpermohonan($nomor)
	{
		$data = $this->db->query("SELECT a.*, b.namabarang FROM tbl_apodmohon AS a 
		LEFT JOIN tbl_barang AS b ON b.kodebarang = a.kodebarang 
		WHERE a.mohonno = '$nomor'")->result();
		echo json_encode($data);
	}

	public function getgudang($nomor)
	{
		$data	= $this->db->query("SELECT a.dari, a.keterangan AS ket, b.keterangan
		FROM tbl_apohmohon AS a 
		LEFT JOIN tbl_depo AS b ON b.depocode = a.dari
		WHERE mohonno = '$nomor'")->row();

		$data2	= $this->db->query("SELECT a.ke, b.keterangan
		FROM tbl_apohmohon AS a 
		LEFT JOIN tbl_depo AS b ON b.depocode = a.ke
		WHERE mohonno = '$nomor'")->row();

		$datac	= $this->db->query("SELECT * FROM tbl_apodmohon WHERE mohonno = '$nomor'")->num_rows();

		echo json_encode(array(
			"keterangan" => $data->ket,
			"dariid"	=> $data->dari,
			"dari" 		=> $data->keterangan,
			"keid"		=> $data2->ke,
			"ke"		=> $data2->keterangan,
			"total"		=> $datac
		));
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

			$queryh = "select * from tbl_apohmove
			where moveno = '$param'";

			if ($param == '') {
				$queryd = "select tbl_apodmove.*, tbl_barang.namabarang from tbl_apodmove inner join 
				tbl_barang on tbl_apodmove.kodebarang=tbl_barang.kodebarang";
			} else {
				$queryd = "select tbl_apodmove.*, tbl_barang.namabarang from tbl_apodmove inner join 
				tbl_barang on tbl_apodmove.kodebarang=tbl_barang.kodebarang
				where tbl_apodmove.moveno = '$param'";
			}

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
			$judul = array('MUTASI BARANG ANTAR DEPO/GUDANG');
			$pdf->FancyRow2(10, $judul, $fc,  $border, $align, $style, $size, $max);
			$size  = array('10');
			$align = array('L');
			$border = array('');


			$pdf->ln(1);
			$pdf->setfont('Arial', 'B', 10);
			$pdf->SetWidths(array(30, 5, 70, 20, 5, 60));
			$border = array('T', 'T', 'T', 'T', 'T', 'T');
			$fc     = array('0', '0', '0', '0', '0', '0');
			$pdf->SetFillColor(230, 230, 230);
			$pdf->setfont('Arial', '', 9);

			$nama_dari = data_master('tbl_depo', array('depocode' => $header->dari))->keterangan;
			$nama_ke   = data_master('tbl_depo', array('depocode' => $header->ke))->keterangan;

			$pdf->FancyRow(array('Dari Gudang/Depo', ':', $nama_dari, 'Transfer No.', ':', $header->moveno), $fc, $border);
			$border = array('', '', '', '', '', '');
			$pdf->FancyRow(array('Ke Gudang/Depo', ':', $nama_ke, 'Tanggal', ':', date('d-m-Y', strtotime($header->movedate))), $fc, $border);
			$pdf->FancyRow(array('Catatan', ':', $header->keterangan, '', '', ''), $fc, $border);

			$pdf->ln(2);
			$pdf->SetWidths(array(10, 15, 40, 15, 7.5, 15.5, 18.5, 15, 27, 26.5));
			$border = array('LTB', 'TB', 'TB', 'TB', 'TB', 'TB', 'TB', 'TB', 'TB', 'TBR');
			$align  = array('C', 'L', 'L', 'L', 'R', 'R', 'R', 'R', 'R', 'L');
			$pdf->setfont('Arial', 'B', 9);
			$pdf->SetAligns(array('L', 'C', 'R'));
			$fc = array('0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
			$judul = array('No.', 'Kode', 'Nama Barang', 'Satuan', 'Qty', 'HNA', 'Total HNA', 'HPP', 'Total HPP', 'Keterangan');
			$pdf->FancyRow2(8, $judul, $fc, $border, $align);
			$border = array('', '', '');
			$pdf->setfont('Arial', '', 9);
			$tot = 0;
			$subtot = 0;
			$tdisc  = 0;
			$border = array('L', '', '', '', '', '', '', '', '', 'R');

			$align  = array('C', 'L', 'L', 'L', 'R', 'R', 'R', 'R', 'R', 'L');
			$fc = array('0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
			$max = array(8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8);
			$pdf->SetFillColor(0, 0, 139);
			$pdf->settextcolor(0);
			$no = 1;
			$totitem = 0;
			$tot = 0;
			foreach ($detil as $db) {
				$tot += ($db->hpp * $db->qtymove);
				$pdf->FancyRow2(5, array(
					$no,
					$db->kodebarang,
					$db->namabarang,
					$db->satuan,
					number_format($db->qtymove),

					number_format($db->harga),
					number_format($db->totalharga),
					number_format($db->hpp),
					number_format($db->hpp * $db->qtymove),
					$db->keterangan,

				), $fc, $border, $align);

				$no++;
			}

			$pdf->SetWidths(array(170, 20));
			$border = array('LTB', 'TBR');
			$align  = array('R', 'R');
			$style = array('B', 'B');
			$size = array('', '');
			$max = array('', '');
			$pdf->FancyRow2(5, array(
				number_format($tot, 2, ',', '.'),
				''
			), $fc,  $border, $align, $style, $size, $max);

			$pdf->ln();
			$pdf->SetWidths(array(45, 5, 40, 5, 40, 5, 50));
			$border = array('', '', '', '', '', '', '');
			$align  = array('C', 'C', 'C', 'C', 'C', 'C', 'C');
			$pdf->setfont('Arial', '', 4);
			$fc = array('0', '0', '0', '0', '0', '0', '0');
			$style = array('', '', '', '', '', '', '');
			$size  = array('9', '9', '9', '9', '9', '9', '9');
			$judul = array('', '', '', '', '', '', $alamat2 . ', ' . date('d-m-Y', strtotime($header->movedate)));
			$pdf->FancyRow2(10, $judul, $fc,  $border, $align, $style, $size, $max);
			$judul = array('Pemohon,', '', 'Keuangan,', '', 'Penanggung Jawab,', '', 'Pembukuan,');
			$pdf->FancyRow2(10, $judul, $fc,  $border, $align, $style, $size, $max);
			$judul = array('', '', '', '', '', '', '');
			$pdf->FancyRow2(10, $judul, $fc,  $border, $align, $style, $size, $max);
			$pdf->ln(15);

			$judul = array($user, '', '', '', '', '', '');
			$style = array('B', '', '', '', '', '', '');
			$border = array('B', '', 'B', '', 'B', '', 'B');
			$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
			$judul = array($user, '', '', '', '', '', '');
			$border = array('', '', '', '', '', '', '');
			$size  = array('7', '7', '7', '7', '7', '7', '7');
			$style = array('I', '', '', '', '', '', 'I');
			$judul = array('HOSPITAL MANAGEMENT SYSTEM', '', '', '', '', '', 'Print Date : ' . date('d-m-Y'));
			$pdf->FancyRow2(5, $judul, $fc,  $border, $align, $style, $size, $max);



			$pdf->setTitle($param);
			$pdf->AliasNbPages();
			$pdf->output($param . '.PDF', 'I');
		} else {

			header('location:' . base_url());
		}
	}

	public function checkstock()
	{
		$unit 	= $this->session->userdata("unit");
		$gudang	= $this->input->get("gudang");
		$kode	= $this->input->get("kode");

		$queryq = $this->db->query("SELECT saldoakhir FROM tbl_barangstock WHERE kodebarang = '$kode' AND koders = '$unit' AND gudang = '$gudang'");

		if ($queryq->num_rows() != 0) {
			$getq = $queryq->row();
			echo json_encode(array("stock" => $getq->saldoakhir));
		} else {
			echo json_encode(array("status" => 0));
		}
	}
}
