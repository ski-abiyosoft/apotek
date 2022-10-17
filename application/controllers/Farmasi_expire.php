<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Farmasi_expire extends CI_Controller
{


	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_farmasi_expire');
		$this->load->helper('simkeu_rpt');
		$this->load->helper('simkeu_nota');
		$this->session->set_userdata('menuapp', '3000');
		$this->session->set_userdata('submenuapp', '3305');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {

			$this->load->view('farmasi/v_farmasi_expire');
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
			$list = $this->M_farmasi_expire->get_datatables(1, $bulan, $tahun);
		} else {
			$bulan  = date('Y-m-d', strtotime($dat[1]));
			$tahun  = date('Y-m-d', strtotime($dat[2]));
			$list = $this->M_farmasi_expire->get_datatables(2, $bulan, $tahun);
		}


		$data = array();
		$no = $_POST['start'];
		foreach ($list as $item) {
			$no++;
			$row = array();
			$row[] = $item->koders;
			$row[] = $item->ed_no;
			$row[] = date('d-m-Y', strtotime($item->tgl_ed));
			$row[] = $item->gudang;
			$row[] = $item->keterangan;
			$row[] = $item->approve_1;
			$row[] = $item->approve_2;
			$row[] = $item->approve_3;
			if ($item->approved == 0) {
				$color = 'style="background: #FFA500; color: white;"';
				$text = 'Approve 1';
				$row[] = '<a class="btn btn-sm" ' . $color . ' href="javascript:void(0)" title="Approve" onclick="approve(' . "'" . $item->id . "'" . ",'" . $item->ed_no . "'" . ')"><i class="glyphicon glyphicon-check"></i> ' . $text . '</a>';
			} else if ($item->approved == 1) {
				$color = 'style="background: #FF8C00; color: white;"';
				$text = 'Approve 2';
				$row[] = '<a class="btn btn-sm" ' . $color . ' href="javascript:void(0)" title="Approve" onclick="approve(' . "'" . $item->id . "'" . ",'" . $item->ed_no . "'" . ')"><i class="glyphicon glyphicon-check"></i> ' . $text . '</a>';
			} else if ($item->approved == 2) {
				$color = 'style="background: #FF6347; color: white;"';
				$text = 'Approve 3';
				$row[] = '<a class="btn btn-sm" ' . $color . ' href="javascript:void(0)" title="Approve" onclick="approve(' . "'" . $item->id . "'" . ",'" . $item->ed_no . "'" . ')"><i class="glyphicon glyphicon-check"></i> ' . $text . '</a>';
			} else {
				$text = 'Approved';
				$row[] = '<a class="btn btn-sm btn-success" title="Approve">' . $text . '</a>';
			}
			if ($item->approved != 3) {
				$row[] =
					'<a class="btn btn-sm btn-primary" href="' . base_url("farmasi_expire/edit/" . $item->id . "") . '" title="Edit" ><i class="glyphicon glyphicon-edit"></i></a>
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data(' . "'" . $item->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
					';
			} else {
				$row[] = '<a target="_blank" class="btn btn-sm btn-warning" href="' . base_url("Farmasi_expire/cetak/?id=" . $item->ed_no . "") . '" title="Cetak" ><i class="glyphicon glyphicon-print"></i> </a>';
			}
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->M_farmasi_expire->count_all($dat[0], $bulan, $tahun),
			"recordsFiltered" => $this->M_farmasi_expire->count_filtered($dat[0], $bulan, $tahun),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_farmasi_expire->get_by_id($id);
		echo json_encode($data);
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

			$queryh = "select * from tbl_apohex where ed_no = '$param'";

			if ($param == '') {
				$queryd = "select tbl_apodex.*, tbl_barang.namabarang from tbl_apodex inner join 
				tbl_barang on tbl_apodex.kodebarang=tbl_barang.kodebarang";
			} else {
				$queryd = "select tbl_apodex.*, tbl_barang.namabarang from tbl_apodex inner join 
				tbl_barang on tbl_apodex.kodebarang=tbl_barang.kodebarang
				where tbl_apodex.ed_no = '$param'";
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
			$judul = array('PEMUSNAHAN BARANG');
			$pdf->FancyRow2(10, $judul, $fc,  $border, $align, $style, $size, $max);
			$size  = array('10');
			$align = array('L');
			$border = array('');


			$pdf->ln(1);
			$pdf->setfont('Arial', 'B', 10);
			$pdf->SetWidths(array(30, 5, 70, 30, 5, 50));
			$border = array('T', 'T', 'T', 'T', 'T', 'T');
			$fc     = array('0', '0', '0', '0', '0', '0');
			$pdf->SetFillColor(230, 230, 230);
			$pdf->setfont('Arial', '', 9);

			$nama_dari = $this->db->get_where("tbl_depo", ['depocode' => $header->gudang])->row();

			$pdf->FancyRow(array('Gudang/Depo', ':', $nama_dari->keterangan, 'No. Pemusnahan', ':', $header->ed_no), $fc, $border);
			$border = array('', '', '', '', '', '');
			$pdf->FancyRow(array('Catatan', ':', $header->keterangan, '', '', ''), $fc, $border);

			$pdf->ln(2);
			$pdf->SetWidths(array(10, 40, 50, 20, 10, 30, 30));
			$border = array('1', '1', '1', '1', '1', '1', '1');
			$align  = array('C', 'C', 'C', 'C', 'C', 'C', 'C');
			$pdf->setfont('Arial', 'B', 9);
			$pdf->SetAligns(array('L', 'C', 'R'));
			$fc = array('0', '0', '0', '0', '0', '0', '0');
			$judul = array('No.', 'Kode', 'Nama Barang', 'Satuan', 'Qty', 'HNA', 'Total HPP');
			$pdf->FancyRow2(9, $judul, $fc, $border, $align);
			$border = array('', '', '');
			$pdf->setfont('Arial', '', 9);
			$tot = 0;
			$subtot = 0;
			$tdisc  = 0;
			$border = array('1', '1', '1', '1', '1', '1', '1');

			$align  = array('C', 'L', 'L', 'L', 'R', 'R', 'R');
			$fc = array('0', '0', '0', '0', '0', '0', '0');
			$max = array(8, 8, 8, 8, 8, 8, 8, 8);
			$pdf->SetFillColor(0, 0, 139);
			$pdf->settextcolor(0);
			$no = 1;
			$tot = 0;
			foreach ($detil as $db) {
				$tot += ($db->totalrp);
				$pdf->FancyRow2(5, array(
					$no,
					$db->kodebarang,
					$db->namabarang,
					$db->satuan,
					number_format($db->qty),
					number_format($db->hpp),
					number_format($db->totalrp),
				), $fc, $border, $align);
				$no++;
			}
			$pdf->SetWidths(array(160, 30));
			$border = array('1', '1');
			$align  = array('C', 'R');
			$style = array('B', 'B');
			$size = array('', '');
			$max = array('', '');
			$pdf->FancyRow2(5, array(
				'Total',
				number_format($tot, 0, ',', '.')
			), $fc,  $border, $align, $style, $size, $max);

			$pdf->ln();
			$pdf->SetWidths(array(45, 5, 40, 5, 40, 5, 50));
			$border = array('', '', '', '', '', '', '');
			$align  = array('C', 'C', 'C', 'C', 'C', 'C', 'C');
			$pdf->setfont('Arial', '', 4);
			$fc = array('0', '0', '0', '0', '0', '0', '0');
			$style = array('', '', '', '', '', '', '');
			$size  = array('9', '9', '9', '9', '9', '9', '9');
			$judul = array('', '', '', '', '', '', $alamat2 . ', ' . date('d-m-Y', strtotime($header->tgl_ed)));
			$pdf->FancyRow2(10, $judul, $fc,  $border, $align, $style, $size, $max);
			$judul = array('Pemohon,', '', 'Approval 1,', '', 'Approval 2,', '', 'Approval 3,');
			$pdf->FancyRow2(10, $judul, $fc,  $border, $align, $style, $size, $max);
			$judul = array('', '', $header->tgl_ap1, '', $header->tgl_ap2, '', $header->tgl_ap3);
			$pdf->FancyRow2(10, $judul, $fc,  $border, $align, $style, $size, $max);
			$judul = array('', '', '', '', '', '', '');
			$pdf->FancyRow2(10, $judul, $fc,  $border, $align, $style, $size, $max);
			$pdf->ln(15);

			$judul = array($user, '', $header->approve_1, '', $header->approve_2, '', $header->approve_3);
			$style = array('B', '', '', '', '', '', '');
			$border = array('B', '', 'B', '', 'B', '', 'B');
			$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
			$judul = array($user, '', $header->approve_1, '', $header->approve_2, '', $header->approve_3);
			$border = array('', '', '', '', '', '', '');
			$size  = array('7', '7', '7', '7', '7', '7', '7');
			$style = array('I', '', '', '', '', '', 'I');
			$judul = array('HOSPITAL MANAGEMENT SYSTEM', '', '', '', '', '', '');
			$pdf->FancyRow2(5, $judul, $fc,  $border, $align, $style, $size, $max);



			$pdf->setTitle($param);
			$pdf->AliasNbPages();
			$pdf->output($param . '.PDF', 'I');
		} else {

			header('location:' . base_url());
		}
	}

	public function getinfobarang()
	{
		$kode = $this->input->get('kode');
		$data = $this->M_global->_data_barang($kode);
		echo json_encode($data);
	}

	public function save($param)
	{
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');
		if (!empty($cek)) {
			$cabang   = $this->session->userdata('unit');
			$userid   = $this->session->userdata('username');
			$gudang_asal  = $this->input->post('gudang_asal');
			$tanggal  = $this->input->post('tanggal');
			$kode  = $this->input->post('kode');
			$qty   = $this->input->post('qty');
			$sat   = $this->input->post('sat');
			$harga = $this->input->post('harga');
			$total  = $this->input->post('total');
			$note   = $this->input->post('note');
			$expire = $this->input->post('expire');

			if ($param == 1) {
				$nomorbukti = urut_transaksi('URUT_EXPIRED', 16);
			} else {
				$nomorbukti = $this->input->post('nomorbukti');
			}

			$jumdata  = count($kode);

			$data_header = array(
				'koders' => $unit,
				'ed_no' => $nomorbukti,
				'tgl_ed' => $tanggal,
				'gudang' => $gudang_asal,
				'jam' => date('H:i:s'),
				'username' => $userid,
				'keterangan' => $this->input->post('ket'),

			);

			if ($param == 1) {
				$this->db->insert('tbl_apohex', $data_header);
				$id_mutasi = $this->db->insert_id();
			} else {
				$id_mutasi = $this->input->post('nomorbukti');
				$this->db->update('tbl_apohex', $data_header, array('ed_no' => $id_mutasi));
				// $datamutasi = $this->db->get_where('tbl_apodex', array('ed_no' => $id_mutasi))->result();
				// foreach ($datamutasi as $row) {
				// 	$_qty = $row->qty;
				// 	$_kode = $row->kodebarang;
				// 	$this->db->query("update tbl_barangstock set keluar=keluar- $_qty, saldoakhir= saldoakhir+ $_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang_asal'");
				// }
				$this->db->query("delete from tbl_apodex where ed_no = '$id_mutasi'");
			}
			$nourut = 1;
			for ($i = 0; $i <= $jumdata - 1; $i++) {
				$_kode   = $kode[$i];
				$_qty    = $qty[$i];
				$_harga  =  str_replace(',', '', $harga[$i]);
				$_total  =  str_replace(',', '', $total[$i]);
				$datad = array(
					'koders' => $unit,
					'ed_no' => $nomorbukti,
					'kodebarang' => $_kode,
					'satuan' => $sat[$i],
					'qty' => $qty[$i],
					'hpp' => $_harga,
					'totalrp' => $_total,
					'exp_date' => date('Y-m-d', strtotime($expire[$i])),
					'rakno' => $note[$i],
				);
				if ($_kode != "") {
					$this->db->insert('tbl_apodex', $datad);
					// 	$this->db->query("UPDATE tbl_barangstock set keluar=keluar+ $_qty, saldoakhir= saldoakhir- $_qty where kodebarang = '$_kode'
					//     and koders = '$cabang' and gudang = '$gudang_asal'");
				}
			}
			echo $nomorbukti;
		} else {
			header('location:' . base_url());
		}
	}

	public function approve()
	{
		$unit = $this->session->userdata('unit');
		$ed_no = $this->input->get("ed_no");
		$id = $this->input->get("id");
		$cek_data = $this->db->get_where('tbl_apohex', ['ed_no' => $ed_no, 'id' => $id])->row();
		$userid = $this->session->userdata('username');
		$tgl_ap = date('Y-m-d');
		$jam_ap = date('H:i:s');
		if ($cek_data->approve_1 == null || $cek_data->approve_1 == '') {
			$this->db->set('approve_1', $userid);
			$this->db->set('tgl_ap1', $tgl_ap);
			$this->db->set('jam_ap1', $jam_ap);
			$this->db->set('approved', 1);
		} else {
			if ($cek_data->approve_2 == '' || $cek_data->approve_2 == null) {
				$this->db->set('approve_2', $userid);
				$this->db->set('tgl_ap2', $tgl_ap);
				$this->db->set('jam_ap2', $jam_ap);
				$this->db->set('approved', 2);
			} else {
				$cek_d = $this->db->get_where('tbl_apodex', ['ed_no' => $ed_no])->result();
				foreach ($cek_d as $cd) {
					$barangstock = $this->db->query("SELECT * FROM tbl_barangstock WHERE gudang = '$cek_data->gudang' AND koders = '$unit' AND (kodebarang = '$cd->kodebarang')")->row();
					// print_r((int)$cd->qty);
					$keluar = (int)$barangstock->keluar + (int)$cd->qty;
					$saldoakhir = (int)$barangstock->saldoakhir - (int)$cd->qty;
					$data = [
						'keluar' => $keluar,
						'saldoakhir' => $saldoakhir,
					];
					$where = [
						'kodebarang' => $cd->kodebarang,
						'koders' => $cd->koders,
					];
					$this->db->update('tbl_barangstock', $data, $where);
				}
				$this->db->set('approve_3', $userid);
				$this->db->set('tgl_ap3', $tgl_ap);
				$this->db->set('jam_ap3', $jam_ap);
				$this->db->set('approved', 3);
			}
		}
		$this->db->where('id', $id);
		$this->db->update('tbl_apohex');
		echo json_encode(['status' => 1]);
	}

	public function ajax_delete($id)
	{
		$header = $this->db->query("select * from tbl_apohex where id = '$id'")->row();
		$nobukti = $header->ed_no;
		$cabang = $header->koders;
		$gudang = $header->gudang;

		$datamutasi = $this->db->get_where('tbl_apodex', array('ed_no' => $nobukti))->result();
		foreach ($datamutasi as $row) {
			$_qty = $row->qty;
			$_kode = $row->kodebarang;

			$this->db->query("update tbl_barangstock set keluar=keluar- $_qty, saldoakhir= saldoakhir+ $_qty where kodebarang = '$_kode'
		  and koders = '$cabang' and gudang = '$gudang'");
		}

		$this->db->query("delete from tbl_apohex where id = '$id'");
		$this->db->query("delete from tbl_apodex where ed_no = '$nobukti'");
		echo json_encode(array("status" => TRUE));
	}



	public function entri()
	{
		$cek = $this->session->userdata('level');
		$uid = $this->session->userdata('unit');
		if (!empty($cek)) {
			$d['pic'] = $this->session->userdata('username');
			$d['nomor'] = 'AUTO';
			$this->load->view('farmasi/v_farmasi_expire_add', $d);
		} else {

			header('location:' . base_url());
		}
	}

	public function edit($id)
	{
		$cek = $this->session->userdata('level');
		$uid = $this->session->userdata('unit');
		if (!empty($cek)) {
			$d['pic'] = $this->session->userdata('username');
			$header   = $this->db->query("select * from tbl_apohex where id = '$id'");
			$noex     = $header->row()->ed_no;
			$detil    = $this->db->query("select * from tbl_apodex where ed_no = '$noex'");
			$d['jumdata'] = $detil->num_rows();
			$d['header'] = $header->row();
			$d['detil']  = $detil->result();
			$this->load->view('farmasi/v_farmasi_expire_edit', $d);
		} else {

			header('location:' . base_url());
		}
	}
}