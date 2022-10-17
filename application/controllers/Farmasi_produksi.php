<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Farmasi_produksi extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_farmasi_produksi');
		$this->load->helper('simkeu_rpt');
		$this->load->helper('simkeu_nota');
		$this->session->set_userdata('menuapp', '3000');
		$this->session->set_userdata('submenuapp', '3304');
	}

	public function index()
	{
		$cek 				= $this->session->userdata('level');
		$bulan          	= $this->M_global->_periodebulan();
		$nbulan   	  		= $this->M_global->_namabulan($bulan);
		$data["periode"] 	= "Periode " . $nbulan . " " . date("Y");
		$data["list"]		= $this->M_farmasi_produksi->list()->result();
		if (!empty($cek)) {
			$this->load->view('farmasi/v_farmasi_produksi', $data);
		} else {
			header('location:' . base_url());
		}
	}

	// public function ajax_list( $param )
	// {

	// 	$dat   = explode("~",$param);
	// 	if($dat[0]==1){
	// 		$bulan = date('m');
	// 		$tahun = date('Y');
	// 		$list = $this->M_farmasi_produksi->get_datatables( 1, $bulan, $tahun );
	// 	} else {
	// 		$bulan  = date('Y-m-d',strtotime($dat[1]));
	// 	    $tahun  = date('Y-m-d',strtotime($dat[2]));
	// 	    $list = $this->M_farmasi_produksi->get_datatables( 2, $bulan, $tahun );	
	// 	}


	// 	$data = array();
	// 	$no = $_POST['start'];
	// 	foreach ($list as $item) {
	// 		$namabarang = data_master('tbl_barang', array('kodebarang' => $item->kodebarang))->namabarang;
	// 		$dari = $this->db->query("SELECT * FROM tbl_depo WHERE depocode = '$item->gudang'")->row();
	// 		$no++;
	// 		$row = array();
	// 		$row[] = $item->koders;
	// 		$row[] = $item->username;
	// 		$row[] = $item->prdno;
	// 		$row[] = date('d-m-Y',strtotime($item->tglproduksi));			
	// 		$row[] = $namabarang;
	// 		$row[] = $dari->keterangan;

	// 		$row[] = 
	// 		     '<a class="btn btn-sm btn-warning" target="_blank" href="'.base_url("farmasi_produksi/cetak/?id=".$item->prdno."").'" title="Edit" ><i class="glyphicon glyphicon-print"></i> </a>

	// 			 <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$item->id."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
	// 	echo '<a class="btn btn-sm btn-primary" href="'.base_url("farmasi_produksi/edit/".$item->id."").'" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a>';
	// 		$data[] = $row;
	// 	}

	// 	$output = array(
	// 					"draw" => $_POST['draw'],
	// 					"recordsTotal" => $this->M_farmasi_produksi->count_all($dat[0], $bulan, $tahun),
	// 					"recordsFiltered" => $this->M_farmasi_produksi->count_filtered($dat[0], $bulan, $tahun),
	// 					"data" => $data,
	// 			);
	// 	//output to json format
	// 	echo json_encode($output);
	// }

	// public function ajax_edit($id)
	// {
	// 	$data = $this->M_farmasi_produksi->get_by_id($id);		
	// 	echo json_encode($data);
	// }

	public function getinfobarang()
	{
		$kode = $this->input->get('kode');
		$data = $this->M_global->_data_barang($kode);
		echo json_encode($data);
	}

	public function save($param)
	{
		$cek    = $this->session->userdata('level');
		$unit   = $this->session->userdata('unit');
		$uid    = $this->session->userdata("username");

		if (!empty($cek)) {
			$cabang        = $this->session->userdata('unit');
			$userid        = $this->session->userdata('username');

			$gudang_asal   = $this->input->post('gudang_asal');
			$tanggal       = $this->input->post('tanggal');
			$kodebarang    = $this->input->post('kodebarang');
			$qtyjadi       = $this->input->post('qtyjadi');
			$hna           = str_replace(',', '', $this->input->post('hna'));
			$hpp           = str_replace(',', '', $this->input->post('hpp'));
			$hargajualjadi = str_replace(',', '', $this->input->post('hargajualjadi'));
			// $margin  = str_replace(',','',$this->input->post('margin'));			

			$kode          = $this->input->post('kode');
			$qty           = $this->input->post('qty');
			$sat           = $this->input->post('sat');
			$harga         = $this->input->post('harga');
			$total         = $this->input->post('total');
			$note          = $this->input->post('note');
			$expire        = $this->input->post('expire');

			if ($param == 1) {
				$nomorbukti = urut_transaksi('URUT_PRODUKSI', 19);
			} else {
				$nomorbukti = $this->input->post('nomorbukti');
			}

			$jumdata  = count($kode);

			$data_header = array(
				'koders'        => $unit,
				'prdno'         => $nomorbukti,
				'tglproduksi'   => $tanggal,
				'gudang'        => $gudang_asal,
				'kodebarang'    => $kodebarang,
				'qtyjadi'       => $qtyjadi,
				'hna'           => $hna,
				'hnappn'        => $hargajualjadi,
				'hpp'           => $hpp,
				'keterangan'    => '',
				'username'      => $uid,
				'jamproduksi'   => date('h:i:s')
			);

			if ($param == 1) {
				$this->db->insert('tbl_apohproduksi', $data_header);

				/* CHECK STOCK DAN HARGA PER CABANG */
				$check_stock = $this->db->query("SELECT * FROM tbl_barangstock WHERE koders = '$cabang' AND kodebarang = '$kodebarang' AND gudang = '$gudang_asal'");
				$check_harga_cabang = $this->db->query("SELECT * FROM tbl_barangcabang WHERE kodebarang = '$kodebarang' AND koders = '$unit'");

				/* STOK, JIKA ADA = UPDATE dan JIKA TIDAK ADA = INSERT */
				if ($check_stock->num_rows() == 0) {
					$this->db->query("INSERT INTO tbl_barangstock (koders,kodebarang,gudang,terima,saldoakhir,lasttr) 
					VALUES ('$unit','$kodebarang','$gudang_asal','$qtyjadi','$qtyjadi','$tanggal')");
				} else {
					$this->db->query("UPDATE tbl_barangstock SET terima = terima + $qtyjadi, saldoakhir = saldoakhir + $qtyjadi WHERE kodebarang = '$kodebarang' AND koders = '$cabang' AND gudang = '$gudang_asal'");
				}

				/* HARGA CABANG, JIKA ADA = UPDATE dan JIKA TIDAK ADA = INSERT */
				if ($check_harga_cabang->num_rows() == 0) {
					$this->db->query("INSERT INTO tbl_barangcabang (koders,kodebarang,margin,hargajual) 
					VALUES ('$unit','$kodebarang','0','$hargajualjadi')");
				} else {
					$this->db->query("UPDATE tbl_barangcabang SET hargajual = $hargajualjadi, margin = 0 WHERE koders = '$unit' AND kodebarang = '$kodebarang'");
				}
				$this->db->query("UPDATE tbl_barang SET hargabeli = $hpp, hargabelippn = $hna, hargajual = $hargajualjadi, hpp = $hpp WHERE kodebarang = '$kodebarang'");

				// $id_mutasi = $this->db->insert_id();
				for ($i = 0; $i <= $jumdata - 1; $i++) {
					$_kode   = $kode[$i];
					$_qty    = $qty[$i];
					$_harga  =  str_replace(',', '', $harga[$i]);
					$_total  =  str_replace(',', '', $total[$i]);

					$datad = array(
						'koders'       => $unit,
						'prdno'        => $nomorbukti,
						'kodebarang'   => $_kode,
						'satuan'       => $sat[$i],
						'qty'          => $qty[$i],
						'hpp'          => $_harga,
						'harga'        => $_harga,
						'totalharga'   => $_total,
						'exp_date'     => date('Y-m-d', strtotime($expire[$i])),
						'keterangan'   => $note[$i],

					);

					if ($_kode != "") {
						$this->db->insert('tbl_apodproduksi', $datad);

						$this->db->query("update tbl_barangstock set keluar=keluar+ $_qty, saldoakhir= saldoakhir - $_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang_asal'");
					}
				}
			} else {
				$id_mutasi 	   = $this->input->post('nomorbukti');
				$kode          = $this->input->post('kode');
				$qty           = $this->input->post('qty');
				$sat           = $this->input->post('sat');
				$harga         = $this->input->post('harga');
				$total         = $this->input->post('total');
				$note          = $this->input->post('note');
				$expire        = $this->input->post('expire');
				$jumdata  = count($kode);
				for ($i = 0; $i <= $jumdata - 1; $i++) {
					$_kode   = $kode[$i];
					$_qty    = $qty[$i];
					$_harga  =  str_replace(',', '', $harga[$i]);
					$_total  =  str_replace(',', '', $total[$i]);


					$datad = array(
						'koders'       => $unit,
						'prdno'        => $nomorbukti,
						'kodebarang'   => $_kode,
						'satuan'       => $sat[$i],
						'qty'          => $qty[$i],
						'hpp'          => $_harga,
						'harga'        => $_harga,
						'totalharga'   => $_total,
						'exp_date'     => date('Y-m-d', strtotime($expire[$i])),
						'keterangan'   => $note[$i],

					);
					if ($_kode != "") {
						$this->db->update('tbl_apodproduksi', $datad, array('prdno' => $id_mutasi, 'kodebarang' => $kode[$i]));
					}
				}

				$this->db->update('tbl_apohproduksi', $data_header, array('prdno' => $id_mutasi));

				$datamutasi = $this->db->get_where('tbl_apodproduksi', array('prdno' => $id_mutasi))->result();

				foreach ($datamutasi as $row) {
					$_qty = $row->qty;
					$_kode = $row->kodebarang;

					$this->db->query("UPDATE tbl_barangstock set keluar=keluar- $_qty, saldoakhir= saldoakhir+ $_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang_asal'");
				}

				// $this->db->query("DELETE from tbl_apodproduksi where prdno = '$id_mutasi'");
				// $this->db->query("UPDATE from tbl_apodproduksi where prdno = '$id_mutasi'");
				$this->db->query("UPDATE tbl_barangstock set terima=terima+ $qtyjadi, saldoakhir= saldoakhir+ $qtyjadi where kodebarang = '$kodebarang' and koders = '$cabang' and gudang = '$gudang_asal'");
			}

			// $this->db->query("update tbl_barangstock set terima=terima+ $qtyjadi, saldoakhir= saldoakhir+ $qtyjadi where kodebarang = '$kodebarang'
			//    and koders = '$cabang' and gudang = '$gudang_asal'");
			echo $nomorbukti;
		} else {
			header('location:' . base_url());
		}
	}

	public function save2()
	{
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');
		$uid = $this->session->userdata("username");
		if (!empty($cek)) {
			$cabang   = $this->session->userdata('unit');
			$userid   = $this->session->userdata('username');

			$gudang_asal  = $this->input->post('gudang_asal');
			$tanggal  = $this->input->post('tanggal');
			$kodebarang  = $this->input->post('kodebarang');
			$qtyjadi  = $this->input->post('qtyjadi');
			$hna  = str_replace(',', '', $this->input->post('hna'));
			$hpp  = str_replace(',', '', $this->input->post('hpp'));
			$hargajualjadi  = str_replace(',', '', $this->input->post('hargajualjadi'));
			// $margin  = str_replace(',','',$this->input->post('margin'));			

			$kode  = $this->input->post('kode');
			$qty   = $this->input->post('qty');
			$sat   = $this->input->post('sat');
			$harga = $this->input->post('harga');
			$total  = $this->input->post('total');
			$note   = $this->input->post('note');
			$expire = $this->input->post('expire');

			$jumdata  = count($kode);

			$data_header = array(
				'koders' => $unit,
				'prdno' => $nomorbukti,
				'tglproduksi' => $tanggal,
				'gudang' => $gudang_asal,
				'kodebarang' => $kodebarang,
				'qtyjadi' => $qtyjadi,
				'hna' => $hna,
				'hnappn' => $hargajualjadi,
				'hpp' => $hpp,
				'keterangan' => '',
				'username' => $uid
			);
			if ($param == 1) {
				$this->db->insert('tbl_apohproduksi', $data_header);


				foreach ($kode as $kkey => $kval) {
					$_kode   = $kode[$i];
					$_qty    = $qty[$i];
					$_harga  =  str_replace(',', '', $harga[$i]);
					$_total  =  str_replace(',', '', $total[$i]);

					$data_detail = [
						'koders' => $unit,
						'prdno' => $nomorbukti,
						'kodebarang' => $_kode,
						'satuan' => $sat[$kkey],
						'qty' => $qty[$kkey],
						'hpp' => $_harga,
						'harga' => str_replace(",", "", $_harga[$kkey]),
						'totalharga' => str_replace(",", "", $_total[$kkey]),
						'exp_date' => date('Y-m-d', strtotime($expire[$i])),
						'keterangan' => $note[$i],
					];

					$this->db->query("UPDATE tbl_barangstock SET terima = terima + $qtyjadi, saldoakhir = saldoakhir + $qtyjadi WHERE kodebarang = '$kodebarang' AND koders = '$cabang' AND gudang = '$gudang_asal'");

					// $this->db->query("UPDATE tbl_barangstock WHERE kodebarang = '$kodebarang' AND koders = '$cabang' AND gudang = '$gudang_asal'");


					urut_transaksi('URUT_PRODUKSI', 19);
				}
			} else {
				$id_mutasi = $this->input->post('nomorbukti');

				$data_header = array(
					'koders' => $unit,
					'prdno' => $nomorbukti,
					'tglproduksi' => $tanggal,
					'gudang' => $gudang_asal,
					'kodebarang' => $kodebarang,
					'qtyjadi' => $qtyjadi,
					'hna' => $hna,
					'hnappn' => $hargajualjadi,
					'hpp' => $hpp,
					'keterangan' => '',
					'username' => $uid
				);
			}
		}
	}

	public function ajax_delete($id)
	{
		$header = $this->db->query("select * from tbl_apohproduksi where id = '$id'")->row();
		$nobukti = $header->prdno;
		$cabang = $header->koders;
		$gudang = $header->gudang;
		$kodebarang = $header->kodebarang;
		$qtyjadi = $header->qtyjadi;

		$this->db->query("update tbl_barangstock set terima=terima- $qtyjadi, saldoakhir= saldoakhir- $qtyjadi where kodebarang = '$kodebarang' and koders = '$cabang' and gudang = '$gudang'");

		$datamutasi = $this->db->get_where('tbl_apodproduksi', array('prdno' => $nobukti))->result();
		foreach ($datamutasi as $row) {
			$_qty = $row->qty;
			$_kode = $row->kodebarang;

			$this->db->query("update tbl_barangstock set keluar=keluar- $_qty, saldoakhir= saldoakhir+ $_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang'");
		}

		$this->db->query("delete from tbl_apohproduksi where id = '$id'");
		$this->db->query("delete from tbl_apodproduksi where prdno = '$nobukti'");
		echo json_encode(array("status" => TRUE));
	}

	public function entri()
	{
		$cek = $this->session->userdata('level');
		$uid = $this->session->userdata('unit');

		$query_pajak	= $this->db->query("SELECT * FROM tbl_pajak");

		if (!empty($cek)) {
			$d['pic'] = $this->session->userdata('username');
			$d['nomor'] = 'AUTO';
			$d['ppn'] = $query_pajak->row();
			$this->load->view('farmasi/v_farmasi_produksi_add', $d);
		} else {
			header('location:' . base_url());
		}
	}

	public function edit($id)
	{
		$cek = $this->session->userdata('level');
		$uid = $this->session->userdata('unit');

		$query_pajak	= $this->db->query("SELECT * FROM tbl_pajak");
		if (!empty($cek)) {
			$d['pic']        = $this->session->userdata('username');
			$header          = $this->db->query("SELECT * from tbl_apohproduksi where prdno = '$id'");
			$noex            = $header->row()->prdno;
			$detil           = $this->db->query("SELECT * from tbl_apodproduksi where prdno = '$id'");
			$d['jumdata']    = $detil->num_rows();
			$d['header']     = $header->row();
			$d['detil']      = $detil->result();
			$d['ppn']        = $query_pajak->row();
			// $this->load->view('farmasi/v_farmasi_produksi_edit', $d);	
			// var_dump($d['header']);die;			
			$this->load->view('farmasi/v_farmasi_produksi_edit', $d);
		} else {
			header('location:' . base_url());
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

			$queryh = "select tbl_apohproduksi.*, tbl_barang.namabarang from tbl_apohproduksi inner join tbl_barang
			on tbl_apohproduksi.kodebarang=tbl_barang.kodebarang 
			where tbl_apohproduksi.prdno = '$param'";

			$queryd = "select tbl_apodproduksi.*, tbl_barang.namabarang from tbl_apodproduksi inner join 
			tbl_barang on tbl_apodproduksi.kodebarang=tbl_barang.kodebarang
			where tbl_apodproduksi.prdno = '$param'";

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
			$judul = array('PRODUKSI FARMASI');
			$pdf->FancyRow2(10, $judul, $fc,  $border, $align, $style, $size, $max);
			$size  = array('10');
			$align = array('L');
			$border = array('');


			$pdf->ln(1);
			$pdf->setfont('Arial', 'B', 10);
			$pdf->SetWidths(array(30, 5, 50, 35, 5, 65));
			$border = array('T', 'T', 'T', 'T', 'T', 'T');
			$fc     = array('0', '0', '0', '0', '0', '0');
			$pdf->SetFillColor(230, 230, 230);
			$pdf->setfont('Arial', '', 9);

			$nama_dari = data_master('tbl_depo', array('depocode' => $header->gudang))->keterangan;

			$pdf->FancyRow(array('DEPO PRODUKSI', ':', $nama_dari, 'HASIL BARANG JADI', '', ''), $fc, $border);
			$border = array('', '', '', '', '', '');
			$pdf->FancyRow(array('NO PRODUKSI', ':', $header->prdno, 'KODE BARANG', ':', $header->kodebarang), $fc, $border);
			$pdf->FancyRow(array('TGL PRODUKSI', ':', date('d-m-Y', strtotime($header->tglproduksi)), 'NAMA BARANG', ':', trim($header->namabarang)), $fc, $border);
			$pdf->FancyRow(array('', '', '', 'QTY JADI', ':', angka_rp($header->qtyjadi, 2)), $fc, $border);
			$pdf->FancyRow(array('', '', '', 'HNA', ':', angka_rp($header->hna, 2)), $fc, $border);
			$pdf->FancyRow(array('', '', '', 'HNA PPN', ':', angka_rp($header->hnappn, 2)), $fc, $border);
			$pdf->FancyRow(array('', '', '', 'HPP SATUAN', ':', angka_rp($header->hpp, 2)), $fc, $border);
			$pdf->SetWidths(array(85, 35, 5, 70));
			//$border = array('T','T','T','T','T','T');
			$fc     = array('0', '0', '0', '0', '0', '0');
			$pdf->FancyRow(array('BAHAN PRODUKSI TERLAMPIR DI BAWAH INI', 'TOTAL HPP', ':', angka_rp($header->hpp * $header->qtyjadi, 2)), $fc, $border);

			$pdf->ln(2);
			$pdf->SetWidths(array(10, 25, 40, 15, 20, 20, 20, 40));
			$border = array('LTB', 'TB', 'TB', 'TB', 'TB', 'TB', 'TB', 'TBR');
			$align  = array('C', 'L', 'L', 'L', 'R', 'R', 'R', 'L');
			$pdf->setfont('Arial', 'B', 9);
			$pdf->SetAligns(array('L', 'C', 'R'));
			$fc = array('0', '0', '0', '0', '0', '0', '0', '0');
			$judul = array('No.', 'Kode Barang', 'Nama Barang', 'Satuan', 'Qty', 'HPP', 'Total HPP', 'Keterangan');
			$pdf->FancyRow2(8, $judul, $fc, $border, $align);
			$border = array('', '', '');
			$pdf->setfont('Arial', '', 9);
			$tot = 0;
			$subtot = 0;
			$tdisc  = 0;
			$border = array('L', '', '', '', '', '', '', 'R');

			$align  = array('C', 'L', 'L', 'L', 'R', 'R', 'R', 'L');
			$fc = array('0', '0', '0', '0', '0', '0', '0', '0');
			$max = array(8, 8, 8, 8, 8, 8, 8, 8, 8);
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
					$db->satuan,
					$db->qty,
					number_format($db->hpp, 2, ',', '.'),
					number_format($db->totalharga, 2, ',', '.'),
					$db->keterangan,
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

			$pdf->setTitle($param);
			$pdf->AliasNbPages();
			$pdf->output($param . '.PDF', 'I');
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

		$data["list"]		= $this->M_farmasi_produksi->list_by_date($dari_Periode, $ke_periode)->result();

		$dari_bulan   	    = $this->M_global->_namabulan($bulan_dari_priode);

		$ke_bulan           = $this->M_global->_namabulan($bulan);
		$data["periode"]    = 'Periode ' . $dari_bulan . '-' . $tahun1[0] . ' s/d ' . $ke_bulan . '-' . $tahun2[0];

		if (!empty($cek)) {
			$this->load->view("farmasi/v_farmasi_produksi", $data);
		} else {
			header("location:" . base_url());
		}
	}

	public function checkstock()
	{
		$unit 	= $this->session->userdata("unit");
		$gudang	= $this->input->get("gudang");
		$kode	= $this->input->get("kode");

		$qq = $this->db->query("SELECT saldoakhir FROM tbl_barangstock WHERE kodebarang = '$kode' AND koders = '$unit' AND gudang = '$gudang'")->row();

		if ($qq) {
			if ($qq->saldoakhir <> 0) {
				echo json_encode(array("stock" => $qq->saldoakhir));
			} else {
				echo json_encode(array("status" => 0));
			}
		} else {
			echo json_encode(array("status" => 2));
		}
	}
}
