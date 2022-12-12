<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Logistik_keluar extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_logistik_keluar');
		$this->load->helper(array("app_global"));
		$this->load->helper('simkeu_rpt');
		$this->load->helper('simkeu_nota');
		$this->session->set_userdata('menuapp', '4000');
		$this->session->set_userdata('submenuapp', '4201');
	}

	// public function index(){
	// 	$cek = $this->session->userdata('level');		

	// 	if(!empty($cek)){				
	// 		$this->load->view('logistik/v_logistik_keluar');
	// 	} else {
	// 		header('location:'.base_url());
	// 	}			
	// }

	public function index()
	{
		$cek		 	 = $this->session->userdata("level");
		$unit            = $this->session->userdata('unit');
		$date	 		 = date("Y-m-d");
		$bulan           = $this->M_global->_periodebulan();
		$nbulan   	     = $this->M_global->_namabulan($bulan);

		$list = $this->db->query("SELECT a.id, a.koders, a.username, a.pakaino, a.pakaidate, b.keterangan AS gudang, a.keterangan FROM tbl_pakaihlog AS a LEFT JOIN tbl_depo AS b ON b.depocode = a.gudang WHERE a.koders = '$unit' and a.pakaidate = '$date' ORDER BY a.id DESC")->result();

		$data["periode"] = 'Periode ' . $nbulan . '-' . $this->M_global->_periodetahun();
		$data["listv"]   = $list;
		$data["unit"]    = $unit;

		if (!empty($cek)) {
			$this->load->view("logistik/v_logistik_keluar", $data);
		} else {
			header("location:/");
		}
	}

	public function cekbarang()
	{
		$cabang = $this->session->userdata("unit");
		$gudang = $this->input->get("gud");
		$kodebarang = $this->input->get("kodebarang");
		$data = $this->db->query("SELECT b.*, (SELECT satuan1 FROM tbl_logbarang WHERE kodebarang = b.kodebarang) AS satuan FROM tbl_apostocklog b WHERE gudang = '$gudang' AND kodebarang = '$kodebarang' AND koders = '$cabang'")->row();
		echo json_encode($data);
	}

	// public function ajax_list( $param ){

	// 	$dat   = explode("~",$param);
	// 	if($dat[0]==1){
	// 		$bulan = date('m');
	// 		$tahun = date('Y');
	// 		$list = $this->M_logistik_keluar->get_datatables( 1, $bulan, $tahun );
	// 	} else {
	// 		$bulan  = date('Y-m-d',strtotime($dat[1]));
	// 	    $tahun  = date('Y-m-d',strtotime($dat[2]));
	// 	    $list = $this->M_logistik_keluar->get_datatables( 2, $bulan, $tahun );	
	// 	}


	// 	$data = array();
	// 	$no = $_POST['start'];
	// 	foreach ($list as $item) {
	// 		$no++;
	// 		$row = array();
	// 		$row[] = $item->koders;
	// 		$row[] = $item->logno;
	// 		$row[] = date('d-m-Y',strtotime($item->logdate));			
	// 		$row[] = $item->gudang;
	// 		$row[] = $item->keterangan;
	// 		$row[] = 
	// 		     '<a class="btn btn-sm btn-primary" href="'.base_url("logistik_keluar/edit/".$item->id."").'" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a>

	// 			 <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$item->id."'".')"><i class="glyphicon glyphicon-trash"></i></a>';

	// 		$data[] = $row;
	// 	}

	// 	$output = array(
	// 					"draw" => $_POST['draw'],
	// 					"recordsTotal" => $this->M_logistik_keluar->count_all($dat[0], $bulan, $tahun),
	// 					"recordsFiltered" => $this->M_logistik_keluar->count_filtered($dat[0], $bulan, $tahun),
	// 					"data" => $data,
	// 			);
	// 	//output to json format
	// 	echo json_encode($output);
	// }

	// public function ajax_edit($id){
	// 	$data = $this->M_logistik_keluar->get_by_id($id);		
	// 	echo json_encode($data);
	// }

	public function getinfobarang()
	{
		$kode = $this->input->get('kode');
		$data = $this->M_global->_data_barang_log($kode);
		echo json_encode($data);
	}

	public function save($param)
	{
		$cek 	= $this->session->userdata('level');
		$unit 	= $this->session->userdata('unit');
		if (!empty($cek)) {
			$userid   = $this->session->userdata('username');
			$nomorbukti = $this->input->post('hidenomorbukti');
			$gudang_asal  = $this->input->post('gudang_asal');
			$tanggal  = $this->input->post('tanggal');
			$kode  = $this->input->post('kode');
			$qty   = $this->input->post('qty');
			$sat   = $this->input->post('sat');
			$harga = str_replace(".00", "", $this->input->post('harga'));
			$total   = str_replace(".00", "", $this->input->post('total'));
			$jumdata  = count($kode);
			if($param == 1){
				$id_mutasi = urut_transaksi("PAKAI_LOG", 19);
			} else {
				$id_mutasi = $this->input->post('nomorbukti');
			}
			if($param == 2 || $param != 1){
				$datamutasi = $this->db->get_where('tbl_pakaidlog', array('pakaino' => $id_mutasi))->result();
				foreach ($datamutasi as $row) {
					$_qty = $row->qty;
					$_kode = $row->kodebarang;
					$this->db->query("UPDATE tbl_apostocklog SET keluar=keluar- $_qty, saldoakhir= saldoakhir+ $_qty WHERE kodebarang = '$_kode' AND koders = '$unit' AND gudang = '$gudang_asal'");
				}
				$this->db->where('pakaino', $id_mutasi);
				$this->db->delete('tbl_pakaihlog');
				$this->db->where('pakaino', $id_mutasi);
				$this->db->delete('tbl_pakaidlog');
				$this->db->query("delete from tbl_apodlogistik where logno = '$id_mutasi'");
			}
			$data_header = [
				'koders' => $unit,
				'pakaino' => $id_mutasi,
				'pakaidate' => $tanggal,
				'gudang' => $gudang_asal,
				'keterangan' => $this->input->post('ket'),
				'username' => $userid,
				'jampakai' => date('h:i:s')
			];
			$this->db->insert('tbl_pakaihlog', $data_header);
			foreach ($kode as $kkey => $kval) {
				$data_detail	= array(
					"koders" => $unit,
					"pakaino" => $id_mutasi,
					"kodebarang" => $kval,
					"satuan" => $sat[$kkey],
					"qty" => $qty[$kkey],
					"harga" => str_replace(",", "", $harga[$kkey]),
					"total" => str_replace(",", "", $total[$kkey])
				);
				$this->db->query("UPDATE tbl_apostocklog SET keluar = keluar + " . $qty[$kkey] . ", saldoakhir = saldoakhir - " . $qty[$kkey] . " WHERE koders = '$unit' AND kodebarang = '$kval' AND gudang = '$gudang_asal'");
				$this->db->insert('tbl_pakaidlog', $data_detail);
			}
			echo $id_mutasi;
		} else {
			header('location:' . base_url());
		}
	}

	function savex($param)
	{
		$cek 	= $this->session->userdata('level');
		$unit 	= $this->session->userdata('unit');

		if (!empty($cek)) {
			$userid   = $this->session->userdata('username');
			$nomorbukti = $this->input->post('hidenomorbukti');
			$nomorbukti_edt = $this->input->post('nomorbukti');
			$gudang_asal  = $this->input->post('gudang_asal');
			$tanggal  = $this->input->post('tanggal');
			$keterangan = $this->input->post('ket');


			$kode  = $this->input->post('kode');
			$qty   = $this->input->post('qty');
			$sat   = $this->input->post('sat');
			$harga = str_replace(".00", "", $this->input->post('harga'));
			// $harga =  $this->input->post('harga');
			$total   = str_replace(".00", "", $this->input->post('total'));
			// $total   = $this->input->post('total');


			$jumdata  = count($kode);
			if ($param == 1) {
				$data_header = array(
					'koders' => $unit,
					'pakaino' => $nomorbukti,
					'pakaidate' => $tanggal,
					'gudang' => $gudang_asal,
					'keterangan' => $keterangan,
					'username' => $userid,
				);
				$this->db->insert('tbl_pakaihlog', $data_header);
				// $nourut = 1;	
				for ($i = 0; $i <= $jumdata - 1; $i++) {
					$_kode   = $kode[$i];
					$_harga  =  str_replace(',', '', $harga[$i]);
					$_total  =  str_replace(',', '', $total[$i]);

					$datad = array(
						'koders' => $unit,
						'logno' => $nomorbukti,
						'kodebarang' => $_kode,
						'satuan' => $sat[$i],
						'qty' => $qty[$i],
						'harga' => $_harga,
						'totalharga' => $_total,
					);

					if ($_kode != "") {
						$this->db->insert('tbl_apodlogistik', $datad);
					}
				}
				urut_transaksi("PAKAI_LOG", 19);
				echo $nomorbukti;
			} else {
				$data_header = array(
					'koders' => $unit,
					'pakaino' => $nomorbukti_edt,
					'pakaidate' => $tanggal,
					'gudang' => $gudang_asal,
					'keterangan' => $keterangan,
					'username' => $userid,
				);
				$id_mutasi = $nomorbukti_edt;
				$this->db->update('tbl_pakaihlog', $data_header);
				$this->db->query("delete from tbl_apodlogistik where logno = '$id_mutasi'");

				for ($i = 0; $i <= $jumdata - 1; $i++) {
					$_kode   = $kode[$i];
					$_harga  =  str_replace(',', '', $harga[$i]);
					$_total  =  str_replace(',', '', $total[$i]);

					$datad = array(
						'koders' => $unit,
						'logno' => $nomorbukti_edt,
						'kodebarang' => $_kode,
						'satuan' => $sat[$i],
						'qty' => $qty[$i],
						'harga' => $_harga,
						'totalharga' => $_total,
					);

					if ($_kode != "") {
						$this->db->insert('tbl_apodlogistik', $datad);
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
		$header = $this->db->query("SELECT * FROM tbl_pakaihlog WHERE id = '$id'")->row();

		$nobukti = $header->pakaino;
		$gudang = $header->gudang;

		$cabang   = $this->session->userdata('unit');
		$datamutasi = $this->db->get_where('tbl_pakaidlog', array('pakaino' => $nobukti))->result();
		foreach ($datamutasi as $row) {
			$_qty = $row->qty;
			$_kode = $row->kodebarang;

			$this->db->query(
				"UPDATE tbl_apostocklog SET keluar=keluar- $_qty, saldoakhir= saldoakhir+ $_qty WHERE kodebarang = '$_kode'
		  		AND koders = '$cabang' AND gudang = '$gudang'"
			);
		}

		$this->db->query("delete from tbl_pakaihlog where id = '$id'");
		$this->db->query("delete from tbl_pakaidlog where pakaino = '$nobukti'");
		echo json_encode(array("status" => TRUE));
	}

	public function entri()
	{
		$cek 			= $this->session->userdata('level');
		$unit			= $this->session->userdata('unit');

		if (!empty($cek)) {
			$d['pic'] 	= $this->session->userdata('username');
			$d['nomor'] 	= temp_urut_transaksi('PAKAI_LOG', $unit, 19);
			$this->load->view('logistik/v_logistik_keluar_add', $d);
		} else {
			header('location:' . base_url());
		}
	}

	public function detail($id)
	{
		$cek 	= $this->session->userdata('level');
		$unit	= $this->session->userdata('unit');
		if (!empty($cek)) {

			$d['pic'] = $this->session->userdata('username');
			$header   = $this->db->query("select * from tbl_pakaihlog where pakaino = '$id'");
			$nomohon  = $header->row()->pakaino;
			$detil    = $this->db->query("select * from tbl_pakaidlog where pakaino = '$nomohon'");
			$d['jumdata'] = $detil->num_rows();
			$d['header'] = $header->row();
			$d['detil']  = $detil->result();
			$this->load->view('logistik/v_logistik_keluar_edit', $d);
		} else {

			header('location:' . base_url());
		}
	}

	public function filter($param)
	{
		$cek                = $this->session->userdata('level');
		$unit               = $this->session->userdata('unit');

		$period_length      = explode("~", $param);
		$dari_Periode       = date("Y-m-d", strtotime($period_length[0]));
		$ke_periode         = date("Y-m-d", strtotime($period_length[1]));
		$bulan_dari_priode  = date("n", strtotime($period_length[0]));
		$bulan              = $this->M_global->_periodebulan();

		$tahun1             =  explode("-", $period_length[0]);
		$tahun2             =  explode("-", $period_length[1]);

		// $data["listv"]      = $this->db->query("SELECT a.koders, a.nokwitansi, a.nojual, a.rekmed, a.namauser, a.tgljual, b.nojual, (SUM(b.netrp)) AS total, c.rekmed, c.namapas 
		// FROM tbl_vocjualh AS a 
		// LEFT JOIN tbl_vocjual AS b ON b.nojual = a.nojual
		// LEFT JOIN tbl_pasien AS c ON c.rekmed = a.rekmed
		// WHERE (a.koders = '$unit') AND (a.tgljual BETWEEN '$dari_Periode' AND '$ke_periode')
		// GROUP BY a.nojual")->result();

		$data["listv"]		= $this->db->query("SELECT a.id, a.koders, a.username, a.pakaino, a.pakaidate, b.keterangan AS gudang, a.keterangan
		FROM tbl_pakaihlog AS a
		LEFT JOIN tbl_depo AS b ON b.depocode = a.gudang
		WHERE a.koders = '$unit' and a.pakaidate BETWEEN '$dari_Periode' AND '$ke_periode' 
		ORDER BY a.id DESC")->result();

		$dari_bulan = $this->M_global->_namabulan($bulan_dari_priode);
		$ke_bulan = $this->M_global->_namabulan($bulan);
		$data["periode"] = 'Periode ' . $dari_bulan . '-' . $tahun1[0] . ' s/d ' . $ke_bulan . '-' . $tahun2[0] . "&emsp; <a href='/logistik_keluar' type='button' class='btn btn-danger btn-sm'><i class='fa fa-refresh'></i> Kembali</a>";
		$data["unit"] = $unit;
		if (!empty($cek)) {
			$this->load->view("logistik/v_logistik_keluar", $data);
		} else {
			header("location:" . base_url());
		}
	}

	public function checkstock()
	{
		$unit 	= $this->session->userdata("unit");
		$gudang	= $this->input->get("gudang");
		$kode	= $this->input->get("kode");

		$queryq = $this->db->query("SELECT saldoakhir FROM tbl_apostocklog WHERE kodebarang = '$kode' AND koders = '$unit' AND gudang = '$gudang'");

		if ($queryq->num_rows() != 0) {
			$getq = $queryq->row();
			echo json_encode(array("stock" => $getq->saldoakhir));
		} else {
			echo json_encode(array("status" => 0));
		}
	}
	public function cetak($id)
	{
		$judul = 'PEMAKAIAN LOG';
		$query = $this->db->query("SELECT a.*, b.*, (select namabarang from tbl_logbarang where kodebarang = b.kodebarang) as namabarang FROM tbl_pakaihlog a JOIN tbl_pakaidlog b ON a.pakaino=b.pakaino WHERE a.pakaino = '$id'")->result();
		$profile = $this->M_global->_LoadProfileLap();
		$unit = $this->session->userdata('unit');
		$nama_usaha = $profile->nama_usaha;
		$alamat1  = $profile->alamat1;
		$alamat2  = $profile->alamat2;
		$profile = data_master('tbl_namers', array('koders' => $unit));
		$nama_usaha = $profile->namars;
		$alamat1 = $profile->alamat;
		$alamat2 = $profile->kota;
		$pdf = new simkeu_nota();
		$pdf->setID($nama_usaha, $alamat1, $alamat2);
		$pdf->setjudul($judul . ' CABANG ' . $unit);
		$pdf->setsubjudul('');
		$pdf->addpage("P", "A4");
		$pdf->setsize("P", "A4");
		$pdf->SetFont('Arial', 'B', 16);
		$pdf->ln(2);
		$pdf->SetFillColor(0, 0, 139);
		$pdf->settextcolor(0);
		$pdf->setfont('Arial', 'B', 6);
		$pdf->Cell(5, 6, 'No', 1, 0, 'C');
		$pdf->Cell(25, 6, 'No Pakai', 1, 0, 'C');
		$pdf->Cell(17, 6, 'Tanggal', 1, 0, 'C');
		$pdf->Cell(29, 6, 'Keterangan', 1, 0, 'C');
		$pdf->Cell(19, 6, 'Kode Barang', 1, 0, 'C');
		$pdf->Cell(36, 6, 'Nama Barang', 1, 0, 'C');
		$pdf->Cell(10, 6, 'Qty', 1, 0, 'C');
		$pdf->Cell(10, 6, 'Satuan', 1, 0, 'C');
		$pdf->Cell(19, 6, 'Harga Sat', 1, 0, 'C');
		$pdf->Cell(19, 6, 'Jumlah', 1, 0, 'C');
		$pdf->ln();
		$pdf->setfont('Arial', '', 6);
		$no = 1;
		$total = 0;
		foreach ($query as $q) {
			$pdf->Cell(5, 6, $no++, 1, 0, 'C');
			$pdf->Cell(25, 6, $q->pakaino, 1, 0, 'L');
			$pdf->Cell(17, 6, date('d-m-Y', strtotime($q->pakaidate)), 1, 0, 'C');
			$pdf->Cell(29, 6, $q->keterangan, 1, 0, 'C');
			$pdf->Cell(19, 6, $q->kodebarang, 1, 0, 'L');
			$pdf->Cell(36, 6, $q->namabarang, 1, 0, 'L');
			$pdf->Cell(10, 6, number_format($q->qty, 2), 1, 0, 'R');
			$pdf->Cell(10, 6, $q->satuan, 1, 0, 'L');
			$pdf->Cell(19, 6, number_format($q->harga, 2), 1, 0, 'R');
			$pdf->Cell(19, 6, number_format($q->total, 2), 1, 0, 'R');
			$pdf->ln();
			$total += $q->total;
		}
		$pdf->Cell(170, 6, 'Total', 1, 0, 'C');
		$pdf->Cell(19, 6, number_format($total, 2), 1, 0, 'R');
		$pdf->Output();
	}

	public function cetak2($id){
		$judul = 'PEMAKAIAN LOG';
		$query = $this->db->query("SELECT a.*, b.*, (select namabarang from tbl_logbarang where kodebarang = b.kodebarang) as namabarang FROM tbl_pakaihlog a JOIN tbl_pakaidlog b ON a.pakaino=b.pakaino WHERE a.pakaino = '$id'")->result();
		$profile = $this->M_global->_LoadProfileLap();
		$unit = $this->session->userdata('unit');
		$body       = '';
		$date       = "";
		$profile    = data_master('tbl_namers', array('koders' => $unit));
		$kota       = $profile->kota;
		$position   = 'L';
		$body .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"5\" cellpadding=\"5\">";
		$body .= "<tr>
								<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">No</td>
								<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">No. Pakai</td>
								<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Tanggal</td>
								<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Keterangan</td>
								<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Kode Barang</td>
								<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Nama Barang</td>
								<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Qty</td>
								<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Satuan</td>
								<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Harga Sat</td>
								<td bgcolor=\"#cccccc\" style=\"text-align: center; font-weight: bold; padding: 10px;\">Jumlah</td>
						</tr>";
		$no = 1;
		$total = 0;
		foreach($query as $q){
			$body .=  "<tr>
										<td style=\"text-align: right;\">" . $no++ . "</td>
										<td>$q->pakaino</td>
										<td style=\"text-align: center;\">" . date("d-m-Y", strtotime($q->pakaidate)) . "</td>
										<td>$q->keterangan</td>
										<td>$q->kodebarang</td>
										<td>$q->namabarang</td>
										<td style=\"text-align: right;\">" . number_format($q->qty) . "</td>
										<td>$q->satuan</td>
										<td style=\"text-align: right;\">" . number_format($q->harga) . "</td>
										<td width=\"15%\" style=\"text-align: right;\">" . number_format($q->total) . "</td>
								</tr>";
			$total += $q->total;
		}
		$body .= "<tr>
								<td colspan=\"9\" style=\"text-align: center\">TOTAL</td>
								<td style=\"text-align: right;\">".number_format($total)."</td>
							</tr>";
		$body .= "</table>";
		$this->M_template_cetak->template($judul, $body, $position, $date, $cekpdf=1);
	}
}
