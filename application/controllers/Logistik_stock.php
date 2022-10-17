<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Logistik_stock extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_logistik_stock', 'M_logistik_stock');
		$this->load->model('M_KartuStock', 'M_KartuStock');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '4000');
		$this->load->helper('simkeu_nota');
		$this->session->set_userdata('submenuapp', '4301');
	}

	// public function index(){
	// 	$cek = $this->session->userdata('level');				
	// 	if(!empty($cek)){
	// 		$data['poli'] = $this->db->get('tbl_namapos')->result();
	// 		$cabang = $this->session->userdata('unit');
	// 		$data['cabang' ] = $cabang;
	// 		$this->load->view('logistik/v_logistik_stock', $data);
	// 	} else {
	// 		header('location:'.base_url());
	// 	}			
	// }

	public function index()
	{
		$cek				= $this->session->userdata("level");
		$gudang				= $this->input->get("gudang");
		$cabang				= $this->session->userdata("unit");
		if (!empty($cek)) {
			$data["cabang"] = $cabang;
			$data["gudang"]	= $gudang;

			$data["list"] 	= $this->db->query("SELECT a.*, b.kodebarang, b.namabarang, b.satuan1 FROM tbl_apostocklog AS a
			LEFT JOIN tbl_logbarang AS b ON a.kodebarang = b.kodebarang
			WHERE a.koders = '$cabang'
			AND a.gudang = '$gudang'")->result();

			$this->load->view("logistik/v_logistik_stock", $data);
		} else {
			header("location:/");
		}
	}

	public function cetak($gudang)
	{
		$gudang = $gudang;
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');
		if (!empty($cek)) {
			$judul = 'LAPORAN DAFTAR STOK GUDANG' . strtoupper($gudang);
			$data_stok = $this->db->query("SELECT a.*, b.kodebarang, b.namabarang, b.satuan1 FROM tbl_apostocklog AS a LEFT JOIN tbl_logbarang AS b ON a.kodebarang = b.kodebarang WHERE a.koders = '$unit' AND a.gudang = '$gudang'")->result();

			$profile = $this->M_global->_LoadProfileLap();
			$nama_usaha = $profile->nama_usaha;
			$alamat1  = $profile->alamat1;
			$alamat2  = $profile->alamat2;
			$profile = data_master('tbl_namers', array('koders' => $unit));
			$nama_usaha = $profile->namars;
			$alamat1 = $profile->alamat;
			$alamat2 = $profile->kota;

			$pdf = new simkeu_nota();
			// header perusahaan
			$pdf->setID($nama_usaha, $alamat1, $alamat2);
			// header judul
			$pdf->setjudul($judul . ' CABANG ' . $unit);
			// header sub judul
			$pdf->setsubjudul('');


			$pdf->addpage("P", "A4");
			$pdf->setsize("P", "A4");
			// set font title and size
			$pdf->SetFont('Arial', 'B', 16);
			$pdf->ln(2);
			$pdf->SetFillColor(0, 0, 139);
			$pdf->settextcolor(0);
			$pdf->setfont('Arial', 'B', 6);
			$pdf->Cell(5, 6, 'No', 1, 0, 'C');
			$pdf->Cell(10, 6, 'Cabang', 1, 0, 'C');
			$pdf->Cell(20, 6, 'Kode Barang', 1, 0, 'C');
			$pdf->Cell(35, 6, 'Nama Barang', 1, 0, 'C');
			$pdf->Cell(15, 6, 'Saldo Awal', 1, 0, 'C');
			$pdf->Cell(15, 6, 'Sesuai', 1, 0, 'C');
			$pdf->Cell(15, 6, 'Terima', 1, 0, 'C');
			$pdf->Cell(15, 6, 'Keluar', 1, 0, 'C');
			$pdf->Cell(15, 6, 'Hasil SO', 1, 0, 'C');
			$pdf->Cell(15, 6, 'Saldo Akhir', 1, 0, 'C');
			$pdf->Cell(15, 6, 'Satuan', 1, 0, 'C');
			$pdf->Cell(15, 6, 'Gudang', 1, 0, 'C');
			$pdf->ln();
			$pdf->setfont('Arial', '', 6);
			$no = 1;
			foreach ($data_stok as $q) {
				$pdf->Cell(5, 6, $no++, 1, 0, 'C');
				$pdf->Cell(10, 6, $q->koders, 1, 0, 'C');
				$pdf->Cell(20, 6, $q->kodebarang, 1, 0, 'L');
				$pdf->Cell(35, 6, $q->namabarang, 1, 0, 'L');
				$pdf->Cell(15, 6, number_format($q->saldoawal, 2), 1, 0, 'R');
				$pdf->Cell(15, 6, number_format($q->sesuai, 2), 1, 0, 'R');
				$pdf->Cell(15, 6, number_format($q->terima, 2), 1, 0, 'R');
				$pdf->Cell(15, 6, number_format($q->keluar, 2), 1, 0, 'R');
				$pdf->Cell(15, 6, number_format($q->hasilso, 2), 1, 0, 'R');
				$pdf->Cell(15, 6, number_format($q->saldoakhir, 2), 1, 0, 'R');
				$pdf->Cell(15, 6, $q->satuan1, 1, 0, 'L');
				$pdf->Cell(15, 6, $q->gudang, 1, 0, 'L');
				$pdf->ln();
			}
			$pdf->Output('Laporan gudang ' . $gudang . '.PDF', 'I');
		} else {

			header('location:' . base_url());
		}
	}

	public function export()
	{
		$cek	= $this->session->userdata('level');
		$cabang	= $this->session->userdata('unit');
		$gudang = $this->input->get('gudang');
		if (!empty($cek)) {
			$query = "SELECT a.*, b.kodebarang, b.namabarang, b.satuan1 FROM tbl_apostocklog AS a LEFT JOIN tbl_logbarang AS b ON a.kodebarang = b.kodebarang WHERE a.koders = '$cabang' AND a.gudang = '$gudang'";
			$d = [
				'data'  => $this->db->query($query)->result(),
				'judul' => $this->input->get('gudang'),

			];
			$this->load->view('logistik/v_logistik_export', $d);
		} else {
			header('location:' . base_url());
		}
	}

	public function index_tarif($cabang)
	{
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$data['poli'] = $this->db->get('tbl_namapos')->result();
			$data['cabang'] = $cabang;
			$this->load->view('master/v_master_tarif_cabang', $data);
		} else {
			header('location:' . base_url());
		}
	}


	function cek_master($cabang)
	{
		$data = $this->db->query("select count(*) as jumlah from tbl_tarif where koders = '$cabang'")->row();
		if ($data->jumlah < 1) {
			$this->db->query("insert into tbl_tarif(koders, kodetarif) select '$cabang', kodetarif from tbl_tarifh");
		} else {
			$this->db->query("insert into tbl_tarif(koders, kodetarif) select '$cabang', kodetarif from tbl_tarifh
			where kodetarif not in(select kodetarif from tbl_tarif where koders = '$cabang')
			");
		}
	}

	public function show()
	{
		$cek  = $this->session->userdata('level');
		$cabang = $this->session->userdata('unit');
		$id = $this->input->get('id');
		if (!empty($cek)) {
			$judul = 'KARTU STOK';
			$profile = $this->M_global->_LoadProfileLap();
			$nama_usaha = $profile->nama_usaha;
			$alamat1  = $profile->alamat1;
			$alamat2  = $profile->alamat2;
			$profile = data_master('tbl_namers', array('koders' => $cabang));
			$nama_usaha = $profile->namars;
			$alamat1 = $profile->alamat;
			$alamat2 = $profile->kota;
			$pdf = new simkeu_nota();
			// $pdf->setID($nama_usaha,$alamat1,$alamat2);
			$pdf->setjudul($judul . ' CABANG ' . $cabang);
			$pdf->setsubjudul('');
			// $pdf->ln();
			$pdf->addpage("L", "A4");
			$pdf->setsize("L", "A4");
			$pdf->SetFont('Arial', 'B', 9);
			$query_saldo = "SELECT * from tbl_apostocklog where id = '$id'";
			$lap = $this->db->query($query_saldo)->row_array();
			if ($lap) {
				$_tanggalawal = $lap['tglso'];
				$saldo = $lap['saldoawal'];
			} else {
				$_tanggalawal = '';
				$saldo = 0;
			}
			$queryx = $this->M_KartuStock->logistikstok($id);
			// echo json_encode($queryx);
			$gudangx = $this->db->get_where('tbl_depo', ['depocode' => $lap['gudang']])->row_array();
			$brg = $this->db->get_where('tbl_logbarang', ['kodebarang' => $lap['kodebarang']])->row_array();
			$pdf->setfont('Arial', '', 10);
			$pdf->Cell(280, 6, 'Cabang : ' . $cabang, 0, 1, 'L');
			$pdf->Cell(280, 6, 'Gudang : ' . $gudangx['keterangan'], 0, 1, 'L');
			$pdf->Cell(280, 6, 'Kode Barang : ' . $lap['kodebarang'], 0, 1, 'L');
			$pdf->Cell(280, 6, 'Nama Barang : ' . $brg['namabarang'], 0, 1, 'L');
			$pdf->ln();
			$pdf->SetWidths(array(40, 20, 30, 30, 30, 20, 20, 20, 30, 30));
			$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
			$judul = array('No. Bukti', 'Tanggal', 'Keterangan', 'Rekanan', 'Nilai Pembelian', 'Terima', 'Keluar', 'Saldo Akhir', 'Nilai Persediaan', 'Total Nilai Persediaan');
			$pdf->setfont('Times', 'B', 10);
			$pdf->row($judul);
			$pdf->SetWidths(array(40, 20, 30, 30, 30, 20, 20, 20, 30, 30));
			$pdf->SetAligns(array('C', 'C', 'L', 'L', 'R', 'R', 'R', 'R', 'R', 'R'));
			$pdf->setfont('Times', '', 9);
			$pdf->SetFillColor(224, 235, 255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
			$pdf->row(array(
				'SALDO',
				date('d-m-Y', strtotime($_tanggalawal)),
				'SALDO AWAL ' . date('d-m-Y', strtotime($_tanggalawal)),
				'',
				number_format(0, 0, '.', ','),
				number_format(0, 0, '.', ','),
				number_format(0, 0, '.', ','),
				number_format($saldo, 0, '.', ','),
				number_format(0, 0, '.', ','),
				number_format(0, 0, '.', ',')
			));
			$pdf->ln();
			$nourut = 1;
			foreach ($queryx as $db) {
				$saldo = $saldo + $db->terima - $db->keluar;

				$nilai = $db->qty * $db->harga;
				$pdf->row(array(
					$db->nomor,
					date('d-m-Y', strtotime($db->tanggal)),
					$db->keterangan,
					$db->rekanan,
					number_format($nilai, 0, '.', ','),
					number_format($db->terima, 0, '.', ','),
					number_format($db->keluar, 0, '.', ','),
					number_format($saldo, 0, '.', ','),
					number_format($db->hpp, 2, '.', ','),
					number_format($db->totalhpp, 2, '.', ',')
				));

				$nourut++;
			}
			$pdf->output();
		} else {
			header('location:' . base_url());
		}
	}

	// public function ajax_list(){
	// 	$cabang = $this->input->get('cabang');
	// 	$gudang = $this->input->get('gudang');
	// 	$list = $this->M_logistik_stock->get_datatables( $cabang, $gudang );
	// 	$data = array();
	// 	$no = $_POST['start'];
	// 	foreach ($list as $unit) {
	// 		$no++;
	// 		$row = array();
	// 		$row[] = $unit->koders;
	// 		$row[] = $unit->kodebarang;
	// 		$row[] = $unit->namabarang;
	// 		$row[] = angka_rp($unit->saldoakhir,2);
	// 		$row[] = $unit->satuan1;
	// 		$row[] = $unit->gudang;
	// 		$data[] = $row;
	// 	}

	// 	$output = array(
	// 					"draw" => $_POST['draw'],
	// 					"recordsTotal" => $this->M_logistik_stock->count_all(),
	// 					"recordsFiltered" => $this->M_logistik_stock->count_filtered( $cabang ),
	// 					"data" => $data,
	// 			);
	// 	//output to json format
	// 	echo json_encode($output);
	// }

	// public function ajax_edit($id){
	// 	$data = $this->M_logistik_stock->get_by_id($id);	        
	// 	echo json_encode($data);
	// }

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
			'kodetarif' => $this->input->post('kode'),
			'tindakan' => $this->input->post('nama'),
			'kodepos' => $this->input->post('poli'),
		);
		$insert = $this->M_logistik_stock->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		//$this->_validate();

		$poli    =  str_replace(',', '', $this->input->post('klinik'));
		$dokter  =  str_replace(',', '', $this->input->post('dokter'));
		$obat    =  str_replace(',', '', $this->input->post('bhp'));
		$perawat =  str_replace(',', '', $this->input->post('perawat'));

		$total   = $poli + $dokter + $obat + $perawat;
		$data = array(
			'kodetarif' => $this->input->post('kode'),
			'tarifrspoli' => $poli,
			'tarifdrpoli' => $dokter,
			'obatpoli' => $obat,
			'feemedispoli' => $perawat,
			'cost' => $total,

		);
		$this->M_logistik_stock->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_logistik_stock->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('poli') == '') {
			$data['inputerror'][] = 'poli';
			$data['error_string'][] = 'Poli harus diisi';
			$data['status'] = FALSE;
		}

		if ($this->input->post('kode') == '') {
			$data['inputerror'][] = 'kode';
			$data['error_string'][] = 'Kode harus diisi';
			$data['status'] = FALSE;
		}

		if ($this->input->post('nama') == '') {
			$data['inputerror'][] = 'nama';
			$data['error_string'][] = 'Nama masih kosong';
			$data['status'] = FALSE;
		}


		if ($data['status'] === FALSE) {
			echo json_encode($data);
			exit();
		}
	}
}

/* End of file master_unit.php */
/* Location: ./application/controllers/master_unit.php */