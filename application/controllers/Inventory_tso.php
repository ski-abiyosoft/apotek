<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_tso extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_stockopname');
		$this->load->model('M_barang');
		$this->load->model('M_cetak');
		$this->load->model('M_param');
		$this->load->helper('simkeu_rpt');
		$this->load->helper('simkeu_nota');
		$this->load->helper('app_global');
		$this->load->model('M_KartuStock');
		$this->session->set_userdata('menuapp', '3000');
		$this->session->set_userdata('submenuapp', '3301');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$cabang = $this->session->userdata('unit');
			$data['cabang'] = $cabang;
			$data['lv_user'] = $this->session->userdata('level');
			$this->load->view('inventory/v_inventory_stockopname', $data);
		} else {
			header('location:' . base_url());
		}
	}

	public function validate()
	{
		$password	= $this->input->post("password");

		if (page_permission($password)) {
			// redirect('Inventory_tso/entri');
			echo 'sukses';
		} else {
			// redirect('Inventory_tso');
			echo 'gagal';
		}
	}

	function gudang()
	{
		$str = $this->input->post('searchTerm');
		$sql = $this->db->query("SELECT depocode as id, keterangan as text from tbl_depo where (depocode like '%$str%' or keterangan like '$str%')")->result();
		echo json_encode($sql);
	}

	public function ajax_list()
	{	
		$user_level   = $this->session->userdata('user_level');
		$level        = $this->session->userdata('level');
		$userid       = $this->session->userdata('username');
		$akses        = $this->M_global->cek_menu_akses($level, 3301);
		$cabang       = $this->session->userdata("unit");
		$gudang       = $this->input->get('gudang');
		$tampilsaldo  = $this->input->get('tampilsaldo');

		$list         = $this->M_stockopname->get_datatables($cabang, $gudang);
		$tgl          = date('Y-m-d');
		$data         = array();
		$no           = $_POST['start'];


		foreach ($list as $item) {
			if ($tampilsaldo != 0) {
				if ($item->saldo != 0) {
					$namagud	= $this->db->query("SELECT * FROM tbl_depo WHERE depocode = '$item->gudang'")->row();
					$no++;
					$row = array();
					$row[] = $item->koders;
					$row[] = $item->username;
					$row[] = $namagud->keterangan;
					$row[] = date('d/m/Y', strtotime($item->tglso));

					$kode  		= $item->kodebarang;
					$nama  		= $this->M_global->_data_barang($item->kodebarang)->namabarang;
					$satuanbrg  = $this->M_global->_data_barang($item->kodebarang)->satuan1;

					$row[] = $item->kodebarang;
					$row[] = $nama;
					$row[] = $satuanbrg;
					// if($item->saldo !=0){
					// 	$row[] = number_format($item->saldo,0,',','.');						
					// }
					$row[] = number_format($item->saldo, 0, ',', '.');
					$row[] = number_format($item->hasilso, 0, ',', '.');
					if ($item->approve == 1) {
						$status = '<span class="label label-sm label-success">Approved</span>';
					} else {
						$status = '<span class="label label-sm label-warning">Waiting</span>';
					}
					$row[] = $status;
					if ($akses->uedit == 1 && $akses->udel == 1) {
						// $row[] = '
						// <a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data(' . "'" . $item->id . "'" . ')"><i class="glyphicon glyphicon-edit"></i></a>
						// ';
						if ($userid != $item->yangubah) {
							$cek = $this->db->query("SELECT * FROM tbl_barangstock JOIN tbl_aposesuai ON tbl_barangstock.kodebarang=tbl_aposesuai.kodebarang WHERE tbl_barangstock.kodebarang = '$item->kodebarang' AND tbl_barangstock.koders = '$cabang' and menyetujui = '$userid' GROUP BY tbl_barangstock.id DESC")->row_array();
							if ($userid == $cek['menyetujui']) {
								if ($item->approve != 1) {
									
									if($user_level==0){
				
										$row[] = 
										'';
											
									}else{
										$row[] = '<a class="btn btn-sm btn-info" href="javascript:void(0)" title="Approve" onclick="approve(' . "'" . $item->id . "'" . ",'" . $item->kodebarang . "'" . ')"><i class="glyphicon glyphicon-check"></i></a>';
									}
									
								} else {
									$row[] = '';
								}
							} else {
								$row[] = '';
							}
							// 	$row[] = '
							// <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Show" onclick="Show(' . "'" . $item->id . "'" . ')"><i class="glyphicon glyphicon-eye-open"></i></a>
							// ';
							// <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data(' . "'" . $item->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
						} else {
							$row[] = '';
						}
					} else {
						$row[] = '';
					}

					$data[] = $row;
				}
			} else {
				$namagud	= $this->db->query("SELECT * FROM tbl_depo WHERE depocode = '$item->gudang'")->row();
				$no++;
				$row = array();
				$row[] = $item->koders;
				$row[] = $item->username;
				$row[] = $namagud->keterangan;
				$row[] = date('d/m/Y', strtotime($item->tglso));

				$kode  		= $item->kodebarang;
				$nama  		= $this->M_global->_data_barang($item->kodebarang)->namabarang;
				$satuanbrg  = $this->M_global->_data_barang($item->kodebarang)->satuan1;

				$row[] = $item->kodebarang;
				$row[] = $nama;
				$row[] = $satuanbrg;
				// if($item->saldo !=0){
				// 	$row[] = number_format($item->saldo,0,',','.');						
				// }
				$row[] = number_format($item->saldo, 0, ',', '.');
				$row[] = number_format($item->hasilso, 0, ',', '.');
				if ($item->approve == 1) {
					$status = '<span class="label label-sm label-success">Approved</span>';
				} else {
					$status = '<span class="label label-sm label-warning">Waiting</span>';
				}
				$row[] = $status;
				if ($akses->uedit == 1 && $akses->udel == 1) {
					// $row[] = '
					// <a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data(' . "'" . $item->id . "'" . ')"><i class="glyphicon glyphicon-edit"></i></a>
					// ';
					if ($userid != $item->yangubah) {
						$cek = $this->db->query("SELECT * FROM tbl_barangstock JOIN tbl_aposesuai ON tbl_barangstock.kodebarang=tbl_aposesuai.kodebarang WHERE tbl_barangstock.kodebarang = '$item->kodebarang' AND tbl_barangstock.koders = '$cabang' and menyetujui = '$userid' GROUP BY tbl_barangstock.id DESC")->row_array();
						if ($userid == $cek['menyetujui']) {
							if ($item->approve != 1) {
								if($user_level==0){
				
									$row[] = 
									'';
										
								}else{
									$row[] = '<a class="btn btn-sm btn-info" href="javascript:void(0)" title="Approve" onclick="approve(' . "'" . $item->id . "'" . ",'" . $item->kodebarang . "'" . ')"><i class="glyphicon glyphicon-check"></i></a> ';
								}
								
							} else {
								$row[] = '';
							}
						} else {
							$row[] = '';
						}
						// 	$row[] = '
						// <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Show" onclick="Show(' . "'" . $item->id . "'" . ')"><i class="glyphicon glyphicon-eye-open"></i></a>
						// ';
						// <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data(' . "'" . $item->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
					} else {
						$cek = $this->db->query("SELECT * FROM tbl_barangstock JOIN tbl_aposesuai ON tbl_barangstock.kodebarang=tbl_aposesuai.kodebarang WHERE tbl_barangstock.kodebarang = '$item->kodebarang' AND tbl_barangstock.koders = '$cabang' and menyetujui = '$userid' GROUP BY tbl_barangstock.id DESC")->row_array();
						if ($userid == $cek['menyetujui']) {
							if ($item->approve != 1) {
								$row[] = '<a class="btn btn-sm btn-info" href="javascript:void(0)" title="Approve" onclick="approve(' . "'" . $item->id . "'" . ",'" . $item->kodebarang . "'" . ')"><i class="glyphicon glyphicon-check"></i></a>
							';
							} else {
								$row[] = '';
							}
						} else {
							$row[] = '';
						}
					}
				} else {
					$row[] = '';
				}

				$data[] = $row;
			}
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->M_stockopname->count_all(),
			"recordsFiltered" => $this->M_stockopname->count_filtered($cabang, $gudang),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function cek_password()
	{
		$username = $this->session->userdata('username');
		$sql = $this->db->get_where('userlogin', ['uidlogin' => $username])->row_array();
		$password = md5($this->input->get('pass'));
		if ($sql['pwdlogin'] == $password) {
			echo json_encode(['status' => 1]);
		} else {
			echo json_encode(['status' => 2]);
		}
	}

	public function edit_data($id)
	{
		$data = $this->db->get_where('tbl_aposesuai', ['id' => $id])->result();
	}

	public function ajax_edit($id)
	{
		$data = $this->M_stockopname->get_by_id($id);
		echo json_encode($data);
	}

	public function approve($id)
	{
		$id = $this->input->post('id');
		$this->db->set('approve', 1);
		$this->db->where('id', $id);
		$this->db->update('tbl_aposesuai');
		$sesuai = $this->db->get_where("tbl_aposesuai", ['id' => $id])->row();
		if($sesuai->type == 'so'){
			$this->db->set("apoperiode", date('Y-m-d'));
			$this->db->where("koders", $this->session->userdata('unit'));
			$this->db->update("tbl_periode");
		}
		$xxx = $sesuai->saldo + $sesuai->sesuai;
		$tanggal = date("Y-m-d");
		$tanggal_now = date("Y-m-d H:i:s");
		$this->db->query("UPDATE tbl_barangstock set hasilso = $sesuai->hasilso, sesuai = $sesuai->sesuai, saldoakhir= $xxx, tglso = '$tanggal', lasttr = '$tanggal_now' where kodebarang = '$sesuai->kodebarang' and koders = '$sesuai->koders' and gudang = '$sesuai->gudang'");
		echo json_encode(array("status" => 1));
	}

	public function approveall()
	{
		$userid   = $this->session->userdata('username');
		$cabang   = $this->session->userdata('unit');

		$sesuaix  = $this->db->get_where("tbl_aposesuai", ['approve' => 0,'koders' => $cabang])->result();
		foreach($sesuaix as $sesuai){
			
			$xxx       = $sesuai->saldo + $sesuai->sesuai;
			$tanggal   = date("Y-m-d");
			$tanggal_now   = date("Y-m-d H:i:s");

			$this->db->query("UPDATE tbl_barangstock set hasilso = $sesuai->hasilso, sesuai = $sesuai->sesuai, saldoakhir= $xxx, tglso = '$tanggal', lasttr = '$tanggal_now' where kodebarang = '$sesuai->kodebarang' and koders = '$sesuai->koders' and gudang = '$sesuai->gudang'");
		}
		$this->db->set('approve', 1);
		$this->db->where('approve', 0);
		$this->db->where('koders', $cabang);
		$this->db->update('tbl_aposesuai');

		$sesuain = $this->db->get_where("tbl_aposesuai", ["koders"=>$cabang, "approve"=>1])->result();
		foreach($sesuain as $sn){
			if($sn->type == 'so'){
				$this->db->set("apoperiode", date('Y-m-d'));
				$this->db->where("koders", $this->session->userdata('unit'));
				$this->db->update("tbl_periode");
			}
		}

		$this->db->set('menyetujui', $userid);
		$this->db->where('koders', $cabang);
		$this->db->where('menyetujui', null);
		$this->db->update('tbl_barangstock');
		echo json_encode(array("status" => 1));
	}

	public function save($param)
	{
		$cek = $this->session->userdata('level');

		if (!empty($cek)) {
			$userid   	= $this->session->userdata('username');
			$pic      	= $this->input->post('pic');
			$tanggal  	= $this->input->post('tanggal');
			$kode  		= $this->input->post('kode');
			$saldoakhir = $this->input->post('saldoakhir');
			$plusminus = $this->input->post('plusminus');
			$qty   		= $this->input->post('qty');
			$sat   		= $this->input->post('sat');
			$gudang  	= $this->input->post('gudang');
			$cabang 	= $this->session->userdata('unit');
			$typestock  = $this->input->post('typestock');
			$yangubah  = $this->input->post('yangubah');
			$yangsetuju  = $this->input->get('setuju');

			$jumdata  	= count($kode);

			$nourut = 1;
			for ($i = 0; $i <= $jumdata - 1; $i++) {
				$_kode   	= $kode[$i];
				$_qty   	= $qty[$i];
				$_saldoakhir   	= $saldoakhir[$i];
				$_plusminus   	= $plusminus[$i];
				$_yangubah   	= $yangubah[$i];

				$barang  = $this->db->get_where('tbl_barang', array('kodebarang' => $_kode))->row();
				$datad = array(
					'koders' => $this->session->userdata('unit'),
					'gudang' => $gudang,
					'type' => $typestock,
					'tglso' => date('Y-m-d', strtotime($tanggal)),
					'tglentry' => date('Y-m-d'),
					'jamentry' => date('H:i:s'),
					'kodebarang' => $_kode,
					'hasilso' => $_qty,
					'sesuai' => $_plusminus,
					'saldo' => $_saldoakhir,
					'hpp' => $barang->hpp,
					'username' => $pic,
					'yangubah' => $_yangubah,
					'approve' => 0,
				);
				$xxx = $_saldo + $_plusminus;
				if ($_kode != "") {
					if ($param == 1) {
						$this->db->insert('tbl_aposesuai', $datad);
					} else {
						$this->db->update('tbl_aposesuai', $datad, array('kodebarang' => $_kode, 'gudang' => $gudang));
					}
					$this->db->query("update tbl_barangstock set menyetujui = '$yangsetuju' where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang'");
					// $this->db->query("UPDATE tbl_barangstock set menyetujui = '$yangsetuju' where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang'");
				}
			}
		} else {
			header('location:' . base_url());
		}
	}

	public function ajax_add()
	{
		$this->_validate();
		$tanggal  = $this->input->post('tanggal');
		$koderak  = $this->input->post('rak');
		$kdsubkat = $this->input->post('kdsubkat');
		$kodemerk = $this->input->post('merk');
		$kodeitem = $this->input->post('kodeitem');
		$satuan   = $this->input->post('satuan');
		$qtyopm   = $this->input->post('qty');

		$jumdata  = count($kodeitem);
		$_tanggal = date('Y-m-d', strtotime($tanggal));


		for ($i = 0; $i <= $jumdata - 1; $i++) {
			$item = $kodeitem[$i];
			$qty  = $this->M_barang->get_by_id($item)->qty;
			$hpp  = $this->M_barang->get_by_id($item)->hpp;
			$sel  = $qtyopm[$i] - $qty;

			$data = array(
				'tgl'         => $_tanggal,
				'koderak'     => $koderak,
				'kodemerk'    => $kodemerk,
				'kdsubkat'    => $kdsubkat[$i],
				'kodeitem'    => $kodeitem[$i],
				'sat'         => $satuan[$i],
				'hpp'         => $hpp,
				'qty'         => $qty,
				'qty_opname'  => $qtyopm[$i],
				'selisih'     => $sel,
				'nilai_selisih' => $sel * $hpp,
				'kodeuser'    => $this->session->userdata('username'),
			);

			$insert = $this->M_stockopname->save($data);
		}

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_stockopname->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;


		if ($this->input->post('merk') == '') {
			$data['inputerror'][] = 'merk';
			$data['error_string'][] = 'Merk belum dipilih';
			$data['status'] = FALSE;
		}

		if ($this->input->post('rak') == '') {
			$data['inputerror'][] = 'rak';
			$data['error_string'][] = 'Rak belum dipilih';
			$data['status'] = FALSE;
		}


		if ($data['status'] === FALSE) {
			echo json_encode($data);
			exit();
		}
	}

	public function getitem($kode)
	{
		//$this->_validate();
		if (!empty($kode)) {
			$rak  = $kode;

			$data = $this->db->order_by('kodeitem')->get_where('inv_barang', array('kdrak' => $rak))->result();
?>
<div class="modal-body form">
  <form action="#" id="form" class="form-horizontal">
    <div class="form-body">
      <div id="tableContainer" class="tableContainer">
        <table border="0" cellpadding="0" cellspacing="0" width="100%"
          class="scrollTable table table-striped table-bordered">
          <thead class="fixedHeader">
            <tr>
              <th width="20" style="text-align:center;width:120">No.</th>
              <th width="120" style="text-align:center;width:120">Kode Item</th>
              <th width="300" style="text-align:center">Nama Barang</th>
              <th width="70" style="text-align:center">Qty</th>
              <th width="70" style="text-align:center">Satuan</th>

            </tr>
          </thead>
          <tbody class="scrollContent">
            <?php
			$i = 1;
			foreach ($data as $row) { ?>
            <input type="hidden" name="kodeitem[]" value=<?php echo $row->kodeitem; ?>>
            <input type="hidden" name="satuan[]" value=<?php echo $row->satuan; ?>>
            <tr>
              <td width="90" align="center"><?php echo $i; ?></td>
              <td align="center" width="265">
                <?php echo $row->kodeitem; ?></a>
              </td>
              <td width="670"><?php echo $row->namabarang; ?></td>

              <td width="155"><input type="text" name="qty[]" class="form-control" value="0"></td>
              <td width="130" align="center"><?php echo $row->satuan; ?></td>

              <td></td>
            </tr>

            <?php
										$i++;
			}
			echo "</tbody>";
			echo "</table>";
			echo "</div>";
			echo "</form>";
			echo "</div>";
		} else {
			echo "";
		}
	}


	public function validate_1()
	{
		$username = $this->session->userdata('username');
		$sql = $this->db->get_where('userlogin', ['uidlogin' => $username])->row_array();
		$password = md5($this->input->post('password'));
		if ($sql['pwdlogin'] == $password) {
			redirect('Inventory_tso/entri');
		} else {
			redirect('Inventory_tso');
		}
	}	

	public function validkan($kode_barang, $gudang){
		$cabang = $this->session->userdata('unit');
		$seting = $this->M_KartuStock->update_stok($kode_barang, $gudang, $cabang);
		$cek = $this->db->get_where("tbl_barangstock", ["kodebarang"=>$kode_barang, "gudang"=>$gudang, "koders"=>$cabang])->row();
		$saldoawal = $cek->saldoawal;
		if($seting){
			$terima = 0;
			$keluar = 0;
			$saldo = 0;
			foreach($seting as $c){
				$terima += $c->terima;
				$keluar += $c->keluar;
				$saldo += $c->terima - $c->keluar;
			}
			$data = [
				'terima' => $terima,
				'keluar' => $keluar,
				'saldoakhir' => $saldo + $saldoawal,
			];
			$this->db->update("tbl_barangstock", $data, ["kodebarang"=>$kode_barang, "gudang"=>$gudang, "koders"=>$cabang]);
			echo json_encode(['status'=>1]);
		} else {
			echo json_encode(['status'=>0]);
		}
	}

	public function validkan($kode_barang, $gudang){
		$cabang = $this->session->userdata('unit');
		$seting = $this->M_KartuStock->update_stok($kode_barang, $gudang, $cabang);
		$cek = $this->db->get_where("tbl_barangstock", ["kodebarang"=>$kode_barang, "gudang"=>$gudang, "koders"=>$cabang])->row();
		$saldoawal = $cek->saldoawal;
		if($seting){
			$terima = 0;
			$keluar = 0;
			$saldo = 0;
			foreach($seting as $c){
				$terima += $c->terima;
				$keluar += $c->keluar;
				$saldo += $c->terima - $c->keluar;
			}
			$data = [
				'terima' => $terima,
				'keluar' => $keluar,
				'saldoakhir' => $saldo + $saldoawal,
			];
			$this->db->update("tbl_barangstock", $data, ["kodebarang"=>$kode_barang, "gudang"=>$gudang, "koders"=>$cabang]);
			echo json_encode(['status'=>1]);
		} else {
			echo json_encode(['status'=>0]);
		}
	}

	public function entri()
	{
		$cek = $this->session->userdata('level');
		$uid = $this->session->userdata('unit');
		if (!empty($cek)) {
			$d['pic'] = $this->session->userdata('username');
			$this->load->view('inventory/v_inventory_so_add', $d);
		} else {
			header('location:' . base_url());
		}
	}

	public function cetak($gudang)
	{
		$gudang   = $gudang;
		$unit     = $this->session->userdata('unit');
		$cek      = $this->session->userdata('level');
		if (!empty($cek)) {
			$profile       = $this->M_global->_LoadProfileLap();
			$unit          = $this->session->userdata('unit');
			$nama_usaha    = $profile->nama_usaha;
			$alamat1       = $profile->alamat1;
			$alamat2       = $profile->alamat2;
			$profile       = data_master('tbl_namers', array('koders' => $unit));
			$nama_usaha    = $profile->namars;
			$alamat1       = $profile->alamat;
			$alamat2       = $profile->kota;
			$pdf           = new simkeu_nota();
			$judul         = 'LAPORAN STOK GUDANG ' . $gudang;

			$pdf           = new simkeu_nota();
			// header perusahaan
			$pdf->setID($nama_usaha, $alamat1, $alamat2);
			// header judul
			$pdf->setjudul($judul . ' CABANG ' . $unit);
			// header sub judul
			$pdf->setsubjudul('');

			$query = $this->db->query('SELECT tbl_aposesuai.*, (select namabarang from tbl_barang where kodebarang = tbl_aposesuai.kodebarang) as namabarang, (select satuan1 from tbl_barang where kodebarang = tbl_aposesuai.kodebarang) as satuan from tbl_aposesuai where koders = "' . $unit . '" and gudang = "' . $gudang . '"')->result();

			$pdf->addpage("P", "A4");
			$pdf->setsize("P", "A4");
			// set font title and size
			$pdf->SetFont('Arial', 'B', 16);
			$pdf->ln(2);
			// $judul=array('No', 'CABANG','USERNAME', 'NAMA GUDANG', 'TANGGAL', 'KODE BARANG', 'NAMA BARANG', 'SATUAN', 'QTY', 'QTYSO');
			$pdf->SetFillColor(0, 0, 139);
			$pdf->settextcolor(0);
			$pdf->setfont('Arial', 'B', 6);
			$pdf->Cell(5, 6, 'No', 1, 0, 'C');
			$pdf->Cell(10, 6, 'Cabang', 1, 0, 'C');
			$pdf->Cell(25, 6, 'Username', 1, 0, 'C');
			$pdf->Cell(25, 6, 'Nama Gudang', 1, 0, 'C');
			$pdf->Cell(25, 6, 'Tanggal', 1, 0, 'C');
			$pdf->Cell(25, 6, 'Kode Barang', 1, 0, 'C');
			$pdf->Cell(25, 6, 'Nama Barang', 1, 0, 'C');
			$pdf->Cell(25, 6, 'Satuan', 1, 0, 'C');
			$pdf->Cell(10, 6, 'Qty', 1, 0, 'C');
			$pdf->Cell(10, 6, 'Qty SO', 1, 0, 'C');
			$pdf->ln();
			$pdf->setfont('Arial', '', 6);
			$no = 1;
			foreach ($query as $q) {
				$pdf->Cell(5, 6, $no++, 1, 0, 'R');
				$pdf->Cell(10, 6, $q->koders, 1, 0, 'L');
				$pdf->Cell(25, 6, $q->username, 1, 0, 'L');
				$pdf->Cell(25, 6, $q->gudang, 1, 0, 'L');
				$pdf->Cell(25, 6, $q->tglso, 1, 0, 'L');
				$pdf->Cell(25, 6, $q->kodebarang, 1, 0, 'L');
				$pdf->Cell(25, 6, $q->namabarang, 1, 0, 'L');
				$pdf->Cell(25, 6, $q->satuan, 1, 0, 'L');
				$pdf->Cell(10, 6, $q->saldo, 1, 0, 'R');
				$pdf->Cell(10, 6, $q->hasilso, 1, 0, 'R');
				$pdf->ln();
			}
			$pdf->Output('Laporan gudang ' . $gudang . '.PDF', 'I');
		}
	}

	public function cetak2($gudang)
	{

		$gudang   	  = $gudang;
		$cek          = 1;
		$chari   	  = '';
		$cekk         = $this->session->userdata('level');
		$unit   	  = $this->session->userdata('unit');
		$avatar       = $this->session->userdata('avatar_cabang');
		$profile      = $this->M_global->_LoadProfileLap();
		$nama_usaha   = $profile->nama_usaha;
		$motto        = '';
		$alamat       = '';
		$idlp         = $this->input->get('idlap');
		$tgl1         = $this->input->get('tgl1');
		$tgl2         = $this->input->get('tgl2');
		$dokter       = $this->input->get('dokter');
		$cab          = $this->input->get('cabang');
		$_tgl1        = date('Y-m-d', strtotime($tgl1));
		$_tgl2        = date('Y-m-d', strtotime($tgl2));
		$_peri        = 'Dari ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2));
		if (!empty($cekk)) {
			$kop       = $this->M_cetak->kop($unit);
			$namars    = $kop['namars'];
			$alamat    = $kop['alamat'];
			$alamat2   = $kop['alamat2'];
			$phone     = $kop['phone'];
			$whatsapp  = $kop['whatsapp'];
			$npwp      = $kop['npwp'];


			$chari .= "<table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                     
			<thead>
			<tr>
				<td rowspan=\"6\" align=\"center\">
				<img src=\"" . base_url() . "assets/img_user/$avatar\"  width=\"70\" height=\"70\" /></td>

				<td colspan=\"20\"><b>
					<tr><td style=\"font-size:12px;border-bottom: none;\"><b>$namars</b></td></tr>
					<tr><td style=\"font-size:11px;\">$alamat</td></tr>
					<tr><td style=\"font-size:11px;\">$alamat2</td></tr>
					<tr><td style=\"font-size:11px;\">Wa :$whatsapp    Telp :$phone </td></tr>
					<tr><td style=\"font-size:11px;\">No. NPWP : $npwp</td></tr>

				</b></td>
			</tr> 
			 
			
			</table>";

		$chari .= "
			<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">     
				<tr>
						<td colspan=\"20\" style=\"border-top: none;border-right: none;border-left: none;\">&nbsp;</td>
				</tr> 
			</table>";

		$chari .= "
			<table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\">     
				<tr>
						<td colspan=\"20\" style=\"border-top: none;border-right: none;border-left: none;\"></td>
				</tr> 
			</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
                     
                <thead>
				<tr>
                    <td colspan=\"21\" style=\"text-align:center;font-size:22px;border-bottom: none;color:#120292;\"><b><br></b></td>
                </tr> 
                <tr>
                    <td colspan=\"21\" style=\"text-align:center;font-size:20px;border-bottom: none;color:#120292;\"><b>LAPORAN STOK GUDANG FARMASI CABANG $unit</b></td>
                </tr> 
                 
                
                </table>";

		$chari .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"2\">

		<thead>
			<tr>
				<td style=\"border:0\" align=\"center\"><br></td>                
			</tr>
			<tr>
                <td bgcolor=\"#cccccc\" width=\"5%\" align=\"center\"><b>No</b></td>
                <td bgcolor=\"#cccccc\" width=\"7%\" align=\"center\"><b>Cabang</b></td>
                <td bgcolor=\"#cccccc\" width=\"13%\" align=\"center\"><b>Username</b></td>
                <td bgcolor=\"#cccccc\" width=\"10%\" align=\"center\"><b>Gudang</b></td>
                <td bgcolor=\"#cccccc\" width=\"10%\" align=\"center\"><b>Tanggal</b></td>
                <td bgcolor=\"#cccccc\" width=\"10%\" align=\"center\"><b>Kode Barang</b></td>
                <td bgcolor=\"#cccccc\" width=\"18%\" align=\"center\"><b>Nama Barang</b></td>
                <td bgcolor=\"#cccccc\" width=\"10%\" align=\"center\"><b>Satuan</b></td>
                <td bgcolor=\"#cccccc\" width=\"7%\" align=\"center\"><b>Qty</b></td>
                <td bgcolor=\"#cccccc\" width=\"10%\" align=\"center\"><b>Qty SO</b></td>
            </tr>
            
		</thead>";

			$sql ="SELECT tbl_aposesuai.*, (select namabarang from tbl_barang where kodebarang = tbl_aposesuai.kodebarang) as namabarang, (select satuan1 from tbl_barang where kodebarang = tbl_aposesuai.kodebarang) as satuan from tbl_aposesuai where koders = '$unit' and gudang = '$gudang'" ;

			$query1    = $this->db->query($sql)->result();
			$lcno=0;

			foreach ($query1 as $row) {
				$lcno           = $lcno + 1;
				$cabang         = $row->koders;
				$username       = $row->username;
				$gudang         = $row->gudang;
				$tanggal        = date('d-m-Y', strtotime(substr($row->tglso,0,10)));
				$jamentry       = $row->jamentry;
				$kode_barang    = $row->kodebarang ;
				$nama_barang    = $row->namabarang;
				$satuan         = $row->satuan;
				$qty            = $row->saldo;
				$so             = $row->hasilso;
				$tgl            = $tanggal .' | '. $jamentry;


			$chari .= "
				<tr>
					<td align=\"center\">$lcno</td>
					<td align=\"left\">$cabang</td>
					<td align=\"left\">$username</td>
					<td align=\"left\">$gudang</td>
					<td align=\"left\">$tgl</td>
					<td align=\"left\">$kode_barang</td>
					<td align=\"left\">$nama_barang</td>
					<td align=\"left\">$satuan</td>
					<td align=\"left\">$qty</td>
					<td align=\"left\">$so</td>
				</tr>";


			}

			$chari .= "</table>";

			$data['prev'] = $chari;
			$judul        = 'LAPORAN STOCK OPNAME';

			switch ($cek) {
				case 0;
					echo ("<title>LAPORAN STOCK OPNAME</title>");
					echo ($chari);
					break;

				case 1;
					$this->M_cetak->mpdf('P', 'A4', $judul, $chari, 'LAPORAN_STOCK_OPNAME.PDF', 10, 10, 10, 2);
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

	public function cetakformulir($params)
	{
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$param  = explode("~", $params);
			$rak  = $param[0];
			$tgl  = $param[1];

			$data = $this->db->order_by('kodeitem')->get_where('inv_barang', array('kdrak' => $rak))->result();

			$nrak  = $this->M_global->_data_rak($rak)->nama;

			$pdf = new simkeu_rpt();
			$pdf->setID('', '', '');
			$pdf->setunit('');
			$pdf->setjudul('DAFTAR STOK OPNAME');
			$pdf->setsubjudul('');
			$pdf->addpage("P", "A4");
			$pdf->setsize("P", "A4");
			$pdf->setfont('Times', 'B', 10);
			$pdf->SetWidths(array(30, 5, 40, 35, 30, 5, 30));
			$pdf->SetAligns(array('L', 'C', 'L', 'C', 'L', 'C', 'L'));
			$judul0 = array('Kode Rak', ':', $nrak, '', 'Tanggal', ':', $tgl);
			$border = array('', '', '', '', '', '', '');
			$align = array('L', 'C', 'L', 'C', 'L', 'C', 'L');
			$pdf->FancyRow($judul0, $border, $align);

			$pdf->ln(10);
			$pdf->SetWidths(array(10, 25, 100, 20, 20));
			$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C'));
			$judul = array('No.', 'Kode Item', 'Nama Item', 'Qty', 'Satuan');

			$pdf->row($judul);
			$pdf->SetWidths(array(10, 25, 100, 20, 20));
			$pdf->SetAligns(array('C', 'L', 'L', 'C', 'C'));
			$pdf->setfont('Times', '', 10);
			$pdf->SetFillColor(224, 235, 255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');

			$nourut = 1;

			foreach ($data as $db) {
				$pdf->row(array($nourut, $db->kodeitem, $db->namabarang, '', $db->satuan));
				$nourut++;
			}

			$pdf->ln(10);
			$pdf->SetWidths(array(40, 40, 30, 40, 40));
			$border = array('', '', '', '', '');
			$align = array('C', 'C', 'C', 'C', 'C');
			$foot1 = array('', 'PIC', '', 'Saksi', '');
			$foot2 = array('', '', '', '', '');

			$pdf->FancyRow($foot1, $border, $align);
			$pdf->ln(10);
			$border = array('', 'B', '', 'B', '');
			$pdf->FancyRow($foot2, $border, $align);


			$pdf->AliasNbPages();
			$pdf->output('FORMULIRSO.PDF', 'I');
		} else {
			header('location:' . base_url());
		}
	}

	public function hasilso($params)
	{
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$param  = explode("~", $params);
			$rak  = $param[0];
			$tgl  = $param[1];

			$_tanggal = date('Y-m-d', strtotime($tgl));

			$this->db->select('inv_stockopname.*, inv_barang.namabarang');
			$this->db->from('inv_stockopname');
			$this->db->join('inv_barang', 'inv_stockopname.kodeitem=inv_barang.kodeitem', 'left');
			$this->db->where(array('kdrak' => $rak, 'tgl' => $_tanggal));
			$this->db->order_by('inv_stockopname.pic', 'asc');
			$data = $this->db->get()->result();



			$nrak  = $this->M_global->_data_rak($rak)->nama;

			$pdf = new simkeu_rpt();
			$pdf->setID('', '', '');
			$pdf->setunit('');
			$pdf->setjudul('HASIL STOK OPNAME');
			$pdf->setsubjudul('');
			$pdf->addpage("P", "A4");
			$pdf->setsize("P", "A4");
			$pdf->setfont('Times', 'B', 10);
			$pdf->SetWidths(array(30, 5, 40, 35, 30, 5, 30));
			$pdf->SetAligns(array('L', 'C', 'L', 'C', 'L', 'C', 'L'));
			$judul0 = array('Kode Rak', ':', $nrak, '', 'Tanggal', ':', $tgl);
			$judul1 = array('PIC', ':', '', '', '', '', '');
			$border = array('', '', '', '', '', '', '');
			$align = array('L', 'C', 'L', 'C', 'L', 'C', 'L');
			$pdf->FancyRow($judul0, $border, $align);
			$pdf->FancyRow($judul1, $border, $align);

			$pdf->ln(10);
			$pdf->SetWidths(array(10, 25, 80, 20, 20, 20));
			$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C'));
			$judul = array('No.', 'Kode Item', 'Nama Item', 'Qty', 'Qty Opm', 'Satuan');

			$pdf->row($judul);
			$pdf->SetWidths(array(10, 25, 80, 20, 20, 20));
			$pdf->SetAligns(array('C', 'L', 'L', 'R', 'R', 'C'));
			$pdf->setfont('Times', '', 10);
			$pdf->SetFillColor(224, 235, 255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');

			$nourut = 1;

			foreach ($data as $db) {
				$pdf->row(array($nourut, $db->kodeitem, $db->namabarang, $db->qty, $db->qty_opname, $db->sat));
				$nourut++;
			}


			$pdf->AliasNbPages();
			$pdf->output('HASILSO.PDF', 'I');
		} else {
			header('location:' . base_url());
		}
	}

	public function hasilsosel($params)
	{
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$param  = explode("~", $params);
			$rak  = $param[0];
			$tgl  = $param[1];
			//$pic  = $param[3];

			$_tanggal = date('Y-m-d', strtotime($tgl));

			$this->db->select('inv_stockopname.*, inv_barang.namabarang');
			$this->db->from('inv_stockopname');
			$this->db->join('inv_barang', 'inv_stockopname.kodeitem=inv_barang.kodeitem', 'left');
			$this->db->where(array('kdrak' => $rak, 'tgl' => $_tanggal));
			$this->db->where('selisih<>0');

			$this->db->order_by('inv_stockopname.pic', 'asc');
			$data = $this->db->get()->result();



			$nrak  = $this->M_global->_data_rak($rak)->nama;

			$pdf = new simkeu_rpt();
			$pdf->setID('', '', '');
			$pdf->setunit('');
			$pdf->setjudul('HASIL STOCK OPNAME SELISIH');
			$pdf->setsubjudul('');
			$pdf->addpage("P", "A4");
			$pdf->setsize("P", "A4");
			$pdf->setfont('Times', 'B', 10);
			$pdf->SetWidths(array(30, 5, 40, 35, 30, 5, 30));
			$pdf->SetAligns(array('L', 'C', 'L', 'C', 'L', 'C', 'L'));
			$judul0 = array('Kode Rak', ':', $nrak, '', 'Tanggal', ':', $tgl);
			$judul1 = array('PIC', ':', '', '', '', '', '');
			$border = array('', '', '', '', '', '', '');
			$align = array('L', 'C', 'L', 'C', 'L', 'C', 'L');
			$pdf->FancyRow($judul0, $border, $align);
			$pdf->FancyRow($judul1, $border, $align);

			$pdf->ln(10);
			$pdf->SetWidths(array(10, 25, 80, 20, 20, 20));
			$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C'));
			$judul = array('No.', 'Kode Item', 'Nama Item', 'Qty', 'Qty Opm', 'Satuan');

			$pdf->row($judul);
			$pdf->SetWidths(array(10, 25, 80, 20, 20, 20));
			$pdf->SetAligns(array('C', 'L', 'L', 'R', 'R', 'C'));
			$pdf->setfont('Times', '', 10);
			$pdf->SetFillColor(224, 235, 255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');

			$nourut = 1;

			foreach ($data as $db) {
				$pdf->row(array($nourut, $db->kodeitem, $db->namabarang, $db->qty, $db->qty_opname, $db->sat));
				$nourut++;
			}


			$pdf->AliasNbPages();
			$pdf->output('HASILSO_SELISIH.PDF', 'I');
		} else {
			header('location:' . base_url());
		}
	}

	public function getinfobarang($param)
	{
		$cabang = $this->session->userdata('unit');
		$prm = urldecode($param); //remove spaci
		$gudang = $this->input->get('gudang');
		$qry = "SELECT tbl_barang.*, (select saldoakhir from tbl_barangstock where gudang = '$gudang' and koders= '$cabang' and kodebarang = tbl_barang.`kodebarang`) as salakhir FROM tbl_barang WHERE kodebarang = '$prm'";
		echo json_encode($this->db->query($qry)->row());
	}
}