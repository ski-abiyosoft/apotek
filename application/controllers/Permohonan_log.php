<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Permohonan_log extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_logistik_mohon');
		$this->session->set_userdata('menuapp', '4000');
		$this->session->set_userdata('submenuapp', '4302');
		$this->load->helper(array("app_global", "simkeu_nota1", "simkeu_nota"));
	}

	public function index()
	{
		$cek = $this->session->userdata('level');

		$bulan          	= $this->M_global->_periodebulan();
		$nbulan   	  		= $this->M_global->_namabulan($bulan);
		$data["periode"] 	= "Periode " . $nbulan . " " . date("Y");

		if (!empty($cek)) {
			$this->load->view('logistik/v_logistik_mohon', $data);
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
			$list = $this->M_logistik_mohon->get_datatables(1, $bulan, $tahun);
		} else {
			$bulan  = date('Y-m-d', strtotime($dat[1]));
			$tahun  = date('Y-m-d', strtotime($dat[2]));
			$list = $this->M_logistik_mohon->get_datatables(2, $bulan, $tahun);
		}

		$data = array();
		$no = $_POST['start'];
		foreach ($list as $item) {
			$status_mutasi	= $this->db->query("SELECT * FROM tbl_apohmovelog WHERE mohonno = '$item->mohonno'")->num_rows();
			$depodari	= $this->db->query("SELECT * FROM tbl_depo WHERE depocode = '$item->dari'")->row();
			$depoke		= $this->db->query("SELECT * FROM tbl_depo WHERE depocode = '$item->ke'")->row();

			$no++;
			$row = array();
			$row[] = $item->koders;
			$row[] = $item->username;
			$row[] = $item->mohonno;
			$row[] = date('d-m-Y', strtotime($item->tglmohon));
			$row[] = $depodari->keterangan;
			$row[] = $depoke->keterangan;
			$row[] = $item->keterangan;
			if ($status_mutasi == null) {
				$row[] =
					'<a class="btn btn-sm btn-primary" href="' . base_url("permohonan_log/edit/" . $item->id . "") . '" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a>
				 <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data(' . "'" . $item->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
				 <a href="/permohonan_log/cetak/' . $item->mohonno . '" target="_blank" title="cetak"><button class="btn btn-warning btn-sm"><i class="fa fa-print"></i></button></a>';
			} else {
				$row[] =
					'<a class="btn btn-sm btn-primary" href="' . base_url("permohonan_log/edit/" . $item->id . "") . '" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a>
				<a href="/permohonan_log/cetak/' . $item->mohonno . '" target="_blank" title="cetak"><button class="btn btn-warning btn-sm"><i class="fa fa-print"></i></button></a>';
			}
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->M_logistik_mohon->count_all($dat[0], $bulan, $tahun),
			"recordsFiltered" => $this->M_logistik_mohon->count_filtered($dat[0], $bulan, $tahun),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_logistik_mohon->get_by_id($id);
		echo json_encode($data);
	}

	public function getinfobarang()
	{
		$cabang = $this->session->userdata('unit');
		$kode = $this->input->get('str');
		$gudang = $this->input->get('gudang_asal');
		// $data = $this->M_global->_data_barang_log( $kode);
		// $data = $this->db->query('select a.*, b.* from tbl_apostocklog a join tbl_logbarang b on a.kodebarang=b.kodebarang where a.koders = "'.$cabang.'" and b.kodebarang = "'.$kode.'" and a.gudang = "'.$gudang.'"')->row_array();
		$data = $this->db->query("SELECT a.*, (SELECT saldoakhir from tbl_apostocklog where koders = '$cabang' and gudang = '$gudang' and kodebarang = a.kodebarang) as saldoakhir FROM tbl_logbarang a WHERE kodebarang = '$kode'")->row();
		// $data = $this->db->query("SELECT * FROM tbl_logbarang WHERE kodebarang = '$kode'")->row();
		if ($data->saldoakhir < 1) {
			echo json_encode(['status' => 1]);
		} else {
			echo json_encode($data);
		}
	}

	public function save($param)
	{
		$cek 			= $this->session->userdata('level');
		$unit 			= $this->session->userdata('unit');

		if (!empty($cek)) {
			$userid   = $this->session->userdata('username');
			$nomorbukti_edt	= $this->input->post("nomorbukti");
			$nomorbukti_add = $this->input->post('hidenomorbukti');
			$gudang_asal  = $this->input->post('gudang_asal');
			$gudang_tujuan  = $this->input->post('gudang_tujuan');
			$tanggal  = $this->input->post('tanggal');
			$keterangan = $this->input->post('ket');

			$kode  = $this->input->post('kode');
			$qty   = $this->input->post('qty');
			$sat   = $this->input->post('sat');
			$harga = $this->input->post('harga');
			$total   = $this->input->post('total');
			$note   = $this->input->post('note');


			$jumdata  = count($kode);

			if ($param == 1) {
				$data_header = array(
					'koders' => $unit,
					'mohonno' => $nomorbukti_add,
					'tglmohon' => $tanggal,
					'dari' => $gudang_asal,
					'ke' => $gudang_tujuan,
					'keterangan' => $keterangan,
					'username' => $userid
				);

				$this->db->insert('tbl_apohmohonlog', $data_header);
				$id_mutasi = $this->db->insert_id();

				for ($i = 0; $i <= $jumdata - 1; $i++) {
					$_kode   = $kode[$i];
					$_harga  =  str_replace(',', '', $harga[$i]);
					$_total  =  str_replace(',', '', $total[$i]);

					$datad = array(
						'koders' => $unit,
						'mohonno' => $nomorbukti_add,
						'kodebarng' => $_kode,
						'satuan' => $sat[$i],
						'qtymohon' => $qty[$i],
						'harga' => $_harga,
						'totalharga' => $_total,
						'keterangan' => $note[$i],
					);

					if ($_kode != "") {
						$this->db->insert('tbl_apodmohonlog', $datad);
					}
				}

				urut_transaksi("NO_MOHON", 19);

				echo $nomorbukti_add;
			} else {
				$data_header = array(
					'koders' => $unit,
					'mohonno' => $nomorbukti_edt,
					'tglmohon' => $tanggal,
					'dari' => $gudang_asal,
					'ke' => $gudang_tujuan,
					'keterangan' => $keterangan,
					'username' => $userid
				);

				$id_mutasi = $nomorbukti_edt;
				$this->db->update('tbl_apohmohonlog', $data_header, array('mohonno' => $id_mutasi));
				$this->db->query("DELETE FROM tbl_apodmohonlog WHERE mohonno = '$id_mutasi'");

				for ($i = 0; $i <= $jumdata - 1; $i++) {
					$_kode   = $kode[$i];
					$_harga  =  str_replace(',', '', $harga[$i]);
					$_total  =  str_replace(',', '', $total[$i]);

					$datad = array(
						'koders' => $unit,
						'mohonno' => $nomorbukti_edt,
						'kodebarng' => $_kode,
						'satuan' => $sat[$i],
						'qtymohon' => $qty[$i],
						'harga' => $_harga,
						'totalharga' => $_total,
						'keterangan' => $note[$i],
					);

					if ($_kode != "") {
						$this->db->insert('tbl_apodmohonlog', $datad);
					}
				}

				echo $nomorbukti_edt;
			}
		} else {
			header('location:' . base_url());
		}
	}

	public function ajax_delete($id)
	{
		$nobukti = $this->db->query("select * from tbl_apohmohonlog where id = '$id'")->row()->mohonno;
		$this->db->query("delete from tbl_apohmohonlog where id = '$id'");
		$this->db->query("delete from tbl_apodmohonlog where mohonno = '$nobukti'");
		echo json_encode(array("status" => TRUE));
	}

	public function entri()
	{
		$cek 			= $this->session->userdata('level');
		$cabang 		= $this->session->userdata('unit');
		if (!empty($cek)) {
			$d['pic'] 	= $this->session->userdata('username');
			$d['nomor'] 	= temp_urut_transaksi('NO_MOHON', $cabang, 19);
			$d['nomor2'] 	= recent_urut_transaksi('NO_MOHON', $cabang, 19);
			$this->load->view('logistik/v_logistik_mohon_add', $d);
		} else {
			header('location:' . base_url());
		}
	}

	public function edit($id)
	{
		$cek 	= $this->session->userdata('level');
		$uid 	= $this->session->userdata('username');
		$unit	= $this->session->userdata('unit');

		if (!empty($cek)) {
			$header			= $this->db->query("SELECT * FROM tbl_apohmohonlog WHERE id = '$id'");
			$header_get		= $header->row();
			$detail			= $this->db->query("SELECT * FROM tbl_apodmohonlog WHERE mohonno = '$header_get->mohonno'");
			$status_mutasi	= $this->db->query("SELECT * FROM tbl_apohmovelog WHERE mohonno = '$header_get->mohonno'");

			$data["jumdata"]	= $detail->num_rows();
			$data["header"]		= $header_get;
			$data["detail"]		= $detail->result();
			$data["status_mutasi"]	= $status_mutasi->num_rows();

			$this->load->view('logistik/v_logistik_mohon_edit', $data);
		} else {
			header('location:' . base_url());
		}
	}

	public function cetak($paramcetak)
	{
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');
		$user = $this->session->userdata('username');
		if (!empty($cek)) {

			$unit = $this->session->userdata('unit');

			$profile 	= data_master('tbl_namers', array('koders' => $unit));
			$nama_usaha	= $profile->namars;
			$alamat1  	= $profile->alamat;
			$alamat2  	= $profile->kota;

			$header  	= $this->db->query("SELECT * FROM tbl_apohmohonlog WHERE mohonno = '$paramcetak'")->row();
			$detil 		= $this->db->query("SELECT a.*, b.* FROM tbl_apodmohonlog AS a
			LEFT JOIN tbl_logbarang AS b ON a.kodebarng = b.kodebarang
			WHERE a.mohonno = '$paramcetak'")->result();

			$nama_dari 	= $this->db->query("SELECT * FROM tbl_depo WHERE depocode = '$header->dari'")->row();
			$nama_ke 	= $this->db->query("SELECT * FROM tbl_depo WHERE depocode = '$header->ke'")->row();

			$pdf = new simkeu_nota();
			$pdf->setID($nama_usaha, $alamat1, $alamat2);
			$pdf->setjudul('');
			$pdf->setsubjudul('');
			$pdf->addpage("P", "A4");
			$pdf->setsize("P", "A4");

			$pdf->SetWidths(array(190));

			$pdf->setfont('Arial', 'B', 15);
			$pdf->SetAligns(array('C', 'C', 'C'));
			$border = array('BTLR');
			$size   = array('');
			$align = array('C');
			$style = array('B');
			$size  = array('18');
			$max   = array(20);
			$fc     = array('0');
			$hc     = array('20');
			$judul = array('PERMOHONAN PENGAMBILAN BARANG LOGISTIK');
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

			$pdf->FancyRow(array('Dari Gudang/Depo', ':', $nama_dari->keterangan, 'Mohon No.', ':', $header->mohonno), $fc, $border);
			$border = array('', '', '', '', '', '');
			$pdf->FancyRow(array('Ke Gudang/Depo', ':', $nama_ke->keterangan, 'Tanggal', ':', date('d-m-Y', strtotime($header->tglmohon))), $fc, $border);
			$pdf->FancyRow(array('Catatan', ':', $header->keterangan, '', '', ''), $fc, $border);

			$pdf->ln(2);
			$pdf->SetWidths(array(10, 25, 50, 20, 20, 20, 25, 20));
			$border = array('LTB', 'TB', 'TB', 'TB', 'TB', 'TB', 'TB', 'TBR');
			$align  = array('C', 'L', 'L', 'R', 'L', 'R', 'L', 'L');
			$pdf->setfont('Arial', 'B', 9);
			$pdf->SetAligns(array('L', 'C', 'R'));
			$fc = array('0', '0', '0', '0', '0', '0', '0', '0');
			$judul = array('No.', 'Kode', 'Nama Barang', 'Qty', 'Unit', 'HPP', 'Catatan', 'Total');
			$pdf->FancyRow2(8, $judul, $fc, $border, $align);
			$border = array('', '', '', '', '', '', '');
			$pdf->setfont('Arial', '', 9);
			$tot = 0;
			$subtot = 0;
			$tdisc  = 0;
			$border = array('L', '', '', '', '', '', '', 'R');

			$align  = array('C', 'L', 'L', 'R', 'L', 'R', 'L', 'L');
			$fc = array('0', '0', '0', '0', '0', '0', '0', '0');
			$max = array(8, 8, 8, 8, 8, 8, 8, 8);
			$pdf->SetFillColor(0, 0, 139);
			$pdf->settextcolor(0);
			$no = 1;
			$totitem = 0;
			$tot = 0;
			foreach ($detil as $db) {
				$tot += $db->totalharga;
				$pdf->FancyRow2(5, array(
					$no,
					$db->kodebarang,
					$db->namabarang,
					$db->qtymohon,
					$db->satuan,
					number_format($db->hargabeli, 2, ',', '.'),
					$db->keterangan,
					$db->totalharga,
				), $fc, $border, $align);
				$no++;
			}

			$pdf->SetWidths(array(165, 25));
			$border = array('LB', 'BR');
			$align  = array('R', 'R');
			$style = array('B', 'B');
			$size = array('', '');
			$max = array('', '');
			$pdf->FancyRow2(5, array('', ''), $fc,  $border, $align, $style, $size, $max);

			$pdf->ln();
			$pdf->SetWidths(array(47.5, 50, 47.5, 47.5));
			$border = array('', '', '', '', '', '', '', '');
			$align  = array('C', 'C', 'C', 'C', 'C', 'C', 'R', 'R');
			$pdf->setfont('Arial', '', 8);
			$fc = array('0', '0', '0', '0', '0', '0', '0', '0');
			$style = array('', '', '', '', '', '', '', '');
			$size  = array('9', '9', '9', '9');
			$judul = array('', ',', ',', 'Total:          ' . number_format($tot));
			$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
			$judul = array('', '', '', '');
			$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
			$pdf->ln(15);


			$pdf->ln();
			$pdf->SetWidths(array(90, 10, 90));
			$border = array('', '', '');
			$align  = array('C', '', 'C');
			$pdf->setfont('Arial', '', 8);
			$fc = array('0', '0', '0');
			$style = array('', '', '');
			$size  = array('9', '', '9');
			$judul = array('Disetujui Oleh,', '', 'Dibuat Oleh,');
			$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
			$judul = array('', '', '');
			$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
			$pdf->ln(15);

			$style = array('B', '', 'B');
			$border = array('B', '', 'B');
			$judul = array('', '', $user);
			$pdf->ln(4);
			$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
			$pdf->ln(4);
			$judul = array(date('d-m-Y', strtotime($header->tglmohon)), '', date('d-m-Y', strtotime($header->tglmohon)));
			$border = array('', '', '');
			$pdf->FancyRow2(5, $judul, $fc,  $border, $align, $style, $size, $max);


			$pdf->SetTitle($paramcetak);
			$pdf->AliasNbPages();
			$pdf->Output($paramcetak . '.PDF', 'I');
		} else {
			header('location:' . base_url());
		}
	}
}