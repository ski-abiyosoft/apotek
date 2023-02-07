<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Logistik_po extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_logistik_po', 'M_logistik_po');
		$this->load->model('M_cetak', 'M_cetak');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '4000');
		$this->session->set_userdata('submenuapp', '4101');
		$this->load->helper('simkeu_nota');
	}

	public function index()
	{
		$cek = $this->session->userdata('username');
		if (!empty($cek)) {
			$level = $this->session->userdata('level');
			$akses = $this->M_global->cek_menu_akses($level, 4101);
			$this->load->helper('url');
			$data['modul'] = 'LOGISTIK';
			$data['submodul'] = 'Purchase Order/PO';
			$data['link'] = 'Purchase Order/PO';
			$data['url'] = 'logistik_po';
			$data['tanggal'] = date('d-m-Y');
			$data['akses'] = $akses;
			$this->load->view('logistik/v_logistik_po', $data);
		} else {
			header('location:' . base_url());
		}
	}

	public function add()
	{
		$cek = $this->session->userdata('username');
		if (!empty($cek)) {
			$level = $this->session->userdata('level');
			$akses = $this->M_global->cek_menu_akses($level, 4101);
			$this->load->helper('url');
			$data['modul'] = 'LOGISTIK';
			$data['submodul'] = 'Purchase Order/PO';
			$data['link'] = 'Purchase Order/PO';
			$data['url'] = 'logistik_po';
			$data['tanggal'] = date('d-m-Y');
			$data['akses'] = $akses;
			$data['nomorpo'] = 'Auto';
			$this->load->view('logistik/v_logistik_po_add', $data);
		} else {
			header('location:' . base_url());
		}
	}

	public function cekstock(){
		$cabang = $this->session->userdata("unit");
		$gudang = $this->input->get("gudang");
		$kode = $this->input->get("kode");
		$data = $this->db->get_where("tbl_apostocklog", ['gudang' => $gudang, 'kodebarang' => $kode, 'koders' => $cabang])->row();
		echo json_encode($data);
	}

	public function ajax_list($param)
	{
		$lvx = $this->session->userdata('user_level');
		$level = $this->session->userdata('level');
		$akses = $this->M_global->cek_menu_akses($level, 4101);
		$dat   = explode("~", $param);
		if ($dat[0] == 1) {
			$bulan = date('m');
			$tahun = date('Y');
			$list = $this->M_logistik_po->get_datatables(1, $bulan, $tahun);
		} else {
			$bulan  = date('Y-m-d', strtotime($dat[1]));
			$tahun  = date('Y-m-d', strtotime($dat[2]));
			$list = $this->M_logistik_po->get_datatables(2, $bulan, $tahun);
		}

		$data = array();
		$no = $_POST['start'];
		foreach ($list as $rd) {
			$no++;
			$row = array();
			// $disetujui = $this->db->get_where('userlogin', ['uidlogin' => $rd->disetujuioleh])->row();
			// $dx = $disetujui->username;

			if ($rd->closed == '0' && $rd->setuju == 0) {
				$status = '<span class="label label-sm label-warning">Open</span>';
			} else if ($rd->closed == '0' && $rd->setuju == 1) {
				$status = '<span class="label label-sm label-success">Approved</span>';
			} else {
				$status = '<span class="label label-sm label-danger">Closed</span> ';
			}

			$row[] = '<span "font-weight:bold;"><b>' . $rd->koders . '</b></span>';
			$row[] = '<span "font-weight:bold;"><b>' . $rd->username . '</b></span>';
			$row[] = '<span "font-weight:bold;"><b>' . $rd->po_no . '</b></span>';
			$row[] = '<span "font-weight:bold;"><b>' . date('d-m-Y', strtotime($rd->po_date)) . '</b></span>';
			$row[] = '<span "font-weight:bold;"><b>' . $rd->vendor_name . '</b></span>';
			$row[] = '<span "font-weight:bold;"><b>' . date('d-m-Y', strtotime($rd->ship_date)) . '</b></span>';
			$row[] = '<span "font-weight:bold;"><b>' . $rd->nama . '</b></span>';
			$row[] = '<span "font-weight:bold;"><b>' . $status . '</b></span>';

			if ($akses->uedit == 1 && $akses->udel == 1) {
				$email = $this->db->get_where('tbl_vendor', ['vendor_id' => $rd->vendor_id])->row_array();
				if ($rd->setuju == 0) {
					if ($lvx <= 2) {
						// <a class="btn btn-sm btn-danger" style="margin-bottom:5px;" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$rd->id."'".",'".$rd->po_no."'".')">
						// 	<i class="glyphicon glyphicon-trash"></i>
						// </a>
						$row[] = '
						<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data(' . "'" . $rd->id . "'" . ",'" . $rd->po_no . "'" . ')">
								<i class="glyphicon glyphicon-trash"></i> 
							</a>
						<a class="btn btn-sm btn-primary" href="' . base_url("logistik_po/edit/" . $rd->id . "") . '" title="Edit" >
							<i class="glyphicon glyphicon-edit"></i> 
						</a>
						<a class="btn btn-sm btn-info" href="javascript:void(0)" title="Approve" onclick="approve(' . "'" . $rd->id . "'" . ",'" . $rd->po_no . "'" . ')">
							<i class="glyphicon glyphicon-check"></i>
						</a>
						';
					} else {
						$row[] = '';
					}
				} else {
					if ($lvx >= 2) {
						$row[] = '
							<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Kirim Email" onclick="send_email(' . "'" . $rd->id . "'" . ",'" . $email['email'] . "'" . ')">
								<i class="glyphicon glyphicon-envelope"></i>
							</a>
							<a class="btn btn-sm btn-warning" onclick="javascript:cekctk(' . "'" . $rd->id . "'" . ",'" . $rd->po_no . "'" . ');" title="Cetak1" >
								<i class="glyphicon glyphicon-print"></i>
							</a>
						';
					} else {
						$row[] = '
							<a class="btn btn-sm btn-primary" href="' . base_url("logistik_po/show/" . $rd->id . "") . '" title="Show" >
								<i class="glyphicon glyphicon-eye-open"></i> 
							</a>
						';
					}
				}
				// <a class="btn btn-sm btn-success" style="margin-bottom:5px;" onclick="javascript:cekctkpsn('."'".$rd->id."'".",'".$rd->po_no."'".');" title="Cetak pesanan" >
				// 	<i class="glyphicon glyphicon-print"></i>
				// </a>
			} else if ($akses->uedit == 1 && $akses->udel == 0) {
				$row[] = '
				<a class="btn btn-sm btn-primary" href="' . base_url("logistik_po/edit/" . $rd->id . "") . '" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a> ';
			} else if ($akses->uedit == 0 && $akses->udel == 1) {
				$row[] = '
				<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data(' . "'" . $rd->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i> </a>';
			} else {
				$row[] = '';
			}
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->M_logistik_po->count_all($dat[0], $bulan, $tahun),
			"recordsFiltered" => $this->M_logistik_po->count_filtered($dat[0],  $bulan, $tahun),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function show($id)
	{
		$cek = $this->session->userdata('username');
		if (!empty($cek)) {
			$level            = $this->session->userdata('level');
			$akses            = $this->M_global->cek_menu_akses($level, 3101);
			$this->load->helper('url');
			$data['modul']    = 'LOGISTIK';
			$data['submodul'] = 'Purchase Order/PO';
			$data['link']     = 'Purchase Order/PO';
			$data['url']      = 'Logistik_po';
			$data['tanggal']  = date('d-m-Y');
			$data['akses']    = $akses;
			$header           = $this->db->query("SELECT * from tbl_apohpolog where id = $id")->row();
			$nomorpo          = $header->po_no;
			$detil            = $this->db->query("SELECT tbl_apodpolog.*, tbl_logbarang.namabarang from tbl_apodpolog inner join tbl_logbarang on tbl_apodpolog.kodebarang=tbl_logbarang.kodebarang where po_no = '$nomorpo' ");
			$data['nomorpo']  = $nomorpo;
			$data['header']   = $header;
			$data['detil']    = $detil->result();
			$data['jumdata']  = $detil->num_rows();
			$this->load->view('logistik/v_logistik_po_show', $data);
		} else {
			header('location:' . base_url());
		}
	}

	public function approve()
	{
		$tgl = date('Y-m-d');
		$jam = date('H:i:s');
		$cabang = $this->session->userdata('unit');
		$id = $this->input->post('id');
		$userid = $this->session->userdata('username');
		$this->db->set('setuju', 1);
		$this->db->set('disetujuitgl', $tgl);
		$this->db->set('disetujuijam', $jam);
		$this->db->set('disetujuioleh', $userid);
		$this->db->where('id', $id);
		$this->db->update('tbl_apohpolog');
		echo json_encode(array("status" => 1));
	}

	public function getinfobarang()
	{
		$cabang = $this->session->userdata('unit');
		$kode = $this->input->get('kode');
		$gudang = $this->input->get('gudang');
		// $data = $this->M_global->_data_barang_log( $kode );
		$data = $this->db->query("SELECT a.*, (select saldoakhir from tbl_apostocklog where koders = '$cabang' and gudang = '$gudang' and kodebarang=a.kodebarang) as saldoakhir FROM tbl_logbarang a WHERE kodebarang = '$kode'")->row();
		echo json_encode($data);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_akuntansi_sa->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function edit($id)
	{
		$cek = $this->session->userdata('username');
		if (!empty($cek)) {
			$level            = $this->session->userdata('level');
			$akses            = $this->M_global->cek_menu_akses($level, 3101);
			$this->load->helper('url');
			$data['modul']    = 'LOGISTIK';
			$data['submodul'] = 'Purchase Order/PO';
			$data['link']     = 'Purchase Order/PO';
			$data['url']      = 'Logistik_po';
			$data['tanggal']  = date('d-m-Y');
			$data['akses']    = $akses;
			$header           = $this->db->query("SELECT * from tbl_apohpolog where id = $id")->row();
			$nomorpo          = $header->po_no;
			$detil            = $this->db->query("SELECT tbl_apodpolog.*, tbl_logbarang.namabarang from tbl_apodpolog inner join tbl_logbarang on tbl_apodpolog.kodebarang=tbl_logbarang.kodebarang where po_no = '$nomorpo' ");
			$data['nomorpo']  = $nomorpo;
			$data['header']   = $header;
			$data['detil']    = $detil->result();
			$data['jumdata']  = $detil->num_rows();
			$this->load->view('logistik/v_logistik_po_edit', $data);
		} else {
			header('location:' . base_url());
		}
	}


	public function ajax_add($param)
	{
		$cabang   = $this->session->userdata('unit');
		$userid   = $this->session->userdata('username');
		$faktur   = $this->input->post('nofaktur');

		$tanggal = date('Y-m-d');
		$jam     = date('H:i:s');

		if ($param == 1) {
			$nomorpo = urut_transaksi('SETUP_APO', 19);
		} else {
			$nomorpo = $this->input->post('nomorbukti');
		}
		$data = array(
			'koders'    => $cabang,
			'po_no'     => $nomorpo,
			'ref_no'    => $this->input->post('noref'),
			'po_date'   => $this->input->post('tanggal'),
			'ship_date' => $this->input->post('tanggalkirim'),
			'vendor_id' => $this->input->post('supp'),
			'username'  => $userid,
			'kurs'      => $this->input->post('kurs'),
			'kursrate'  => $this->input->post('rate'),
			'ship_via'  => $this->input->post('dikirimvia'),
			'gudang'    => $this->input->post('gudang'),
			'internalpo'    => $this->input->get('ipo'),

		);

		if ($param == 1) {
			$insert = $this->db->insert('tbl_apohpolog', $data);
		} else {
			$this->db->query("DELETE from tbl_apodpolog where po_no = '$nomorpo'");
			$this->db->update('tbl_apohpolog', $data, array('po_no' => $nomorpo));
		}


		$kode   = $this->input->post('kode');
		$qty    = $this->input->post('qty');
		$sat    = $this->input->post('sat');
		$harga  = $this->input->post('harga');
		// $disc   = $this->input->post('disc');
		// $tax    = $this->input->post('tax');
		$jumlah = $this->input->post('jumlah');

		$jumdata = count($kode);
		for ($i = 0; $i <= $jumdata - 1; $i++) {
			$_harga    = str_replace(',', '', $harga[$i]);
			// $_disc     = str_replace(',', '', $disc[$i]);
			$_jumlah   = str_replace(',', '', $jumlah[$i]);

			// if ($tax[$i]) {
			// 	$_tax = 1;
			// } else {
			// 	$_tax = 0;
			// }

			$data_rinci = array(
				'koders'       => $cabang,
				'po_no'        => $nomorpo,
				'kodebarang'   => $kode[$i],
				'qty_po'       => $qty[$i],
				'price_po'     => $_harga,
				// 'discount'     => $_disc,
				'satuan'       => $sat[$i],
				// 'vat'          => $_tax,
				'total'        => $_jumlah,

			);

			if ($kode[$i] != "") {
				$insert_detil = $this->db->insert('tbl_apodpolog', $data_rinci);
			}
		}


		echo json_encode(array("status" => TRUE, "nomor" => $nomorpo));
	}


	public function ajax_delete()
	{
		$cabang = $this->session->userdata('unit');
		$id = $this->input->post('id');
		$data = $this->db->get_where('tbl_apohpolog', array('id' => $id))->row();
		$nopo = $data->po_no;
		$this->db->delete('tbl_apohpolog', array('id' => $id));
		$this->db->delete('tbl_apodpolog', array('po_no' => $nopo, 'koders' => $cabang));
		echo json_encode(array("status" => 1));
	}



	public function getentry($nomor)
	{
		if (!empty($nomor)) {
			$data = $this->db->select('tr_jurnal.nomor, tr_jurnal.kodeakun, ms_akun.namaakun, tr_jurnal.debet, tr_jurnal.kredit')->join('ms_akun', 'ms_akun.kodeakun=tr_jurnal.kodeakun')->order_by('nomor')->get_where('tr_jurnal', array('novoucher' => $nomor))->result();
			//$data = $this->db->get_where('tr_jurnal',array('novoucher' => $nomor))->result();
?>
			<div>
				<form action="#" id="formx" class="form-horizontal">
					<div class="form-body">
						<div id="tableContainer" class="tableContainer">
							<!--table border="0" cellpadding="0" cellspacing="0" width="100%" class="scrollTable tables table-stripeds table-bordereds"-->



							<!--tbody class="scrollContent"-->
							<?php
							$i = 1;
							foreach ($data as $row) {
								$id     = $row->nomor;
							?>
								<tr>
									<td align="center" width="10%">
										<?php echo $row->kodeakun; ?></a>
									</td>
									<td width="19%"><?php echo $row->namaakun; ?></td>
									<td width="10%" align="right"><?php echo number_format($row->debet, 0, ',', '.'); ?></td>
									<td width="10%" align="right"><?php echo number_format($row->kredit, 0, ',', '.'); ?></td>
									<td width="2%"><a class="btn btn-sm btn-danger" onclick="delete_data(<?php echo $id; ?>)"><i class="glyphicon glyphicon-trash"></i></a></td>
									<td width="32%"></td>
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

					public function cetak($idd = '', $noo = '', $cekstk = '', $cekhrg = '')
					{
						$cek       = $this->session->userdata('level');
						$unit      = $this->session->userdata('unit');
						$user      = $this->session->userdata('username');
						$id        = $this->uri->segment(3);
						$param     = $this->uri->segment(4);
						$stokket   = $this->uri->segment(5);
						$hrgket    = $this->uri->segment(6);

						if (!empty($cek)) {

							$unit = $this->session->userdata('unit');
							$profile = data_master('tbl_namers', array('koders' => $unit));
							$nama_usaha = $profile->namars;
							$alamat1  = $profile->alamat;
							$alamat2  = $profile->kota;

							// $param = $this->input->get('idd'); 

							// query mungkin bener
							// select a.*, b.*, c.namabarang from tbl_apostocklog a
							// join tbl_apodpolog b on a.`kodebarang`=b.`kodebarang`
							// join tbl_logbarang c on c.`kodebarang`=a.`kodebarang`
							// WHERE b.po_no = 'SKAPO2022-000000741' AND a.koders = 'ska'

							$queryh = "SELECT * from tbl_apohpolog inner join 
			tbl_vendor on tbl_apohpolog.vendor_id=tbl_vendor.vendor_id 
			where tbl_apohpolog.po_no = '$param'";

							if ($stokket == '1') {
								$kett = ",IFNULL((select sum(saldoakhir)saldoakhir from tbl_apostocklog b where koders='$unit' and b.kodebarang=tbl_logbarang.kodebarang),0)ket ";
							} else {
								$kett = ",''ket";
							}

							if ($hrgket == '1') {
								$hrgg = ", round(price_po) as price_po2 ";
							} else {
								$hrgg = ", ''price_po2";
							}

							$queryd = "SELECT tbl_apodpolog.*, tbl_logbarang.namabarang $kett $hrgg from tbl_apodpolog  
			inner join tbl_logbarang on tbl_apodpolog.kodebarang=tbl_logbarang.kodebarang
			where po_no = '$param'";


							$detil  = $this->db->query($queryd)->result();
							$header = $this->db->query($queryh)->row();

							$pdf    = new simkeu_nota();
							$pdf->setID($nama_usaha, $alamat1, $alamat2);
							$pdf->setjudul('');
							$pdf->setsubjudul('');
							$pdf->addpage("P", "A4");
							$pdf->setsize("P", "A4");

							$pdf->SetWidths(array(190));

							$pdf->setfont('Arial', 'B', 18);
							$pdf->SetAligns(array('C', 'C', 'C'));
							$border    = array('BTLR');
							$size      = array('');
							$align     = array('C');
							$style     = array('B');
							$size      = array('18');
							$max       = array(20);
							$fc        = array('0');
							$hc        = array('20');
							$judul     = array('SURAT PESANAN/PURCHASE ORDER (PO)');
							$pdf->FancyRow2(10, $judul, $fc,  $border, $align, $style, $size, $max);
							$size      = array('10');
							$align     = array('L');
							$border    = array('');
							$judul     = array('Kepada Yth:');
							$pdf->FancyRow2(10, $judul, $fc,  $border, $align, $style, $size, $max);



							$pdf->ln(1);
							$pdf->setfont('Arial', 'B', 10);
							$pdf->SetWidths(array(10, 5, 90, 20, 5, 60));
							$border    = array('LT', 'T', 'T', 'T', 'T', 'TR');
							$fc        = array('0', '0', '0', '0', '0', '0');
							$pdf->SetFillColor(230, 230, 230);
							$pdf->setfont('Arial', '', 9);


							$pdf->FancyRow(array('(to)', ':', $header->vendor_name, 'PO No.', ':', $header->po_no), $fc, $border);
							$border    = array('L', '', '', '', '', 'R');
							$pdf->FancyRow(array('', '', $header->alamat, 'Tgl PO', ':', date('d-m-Y', strtotime($header->po_date))), $fc, $border);
							$pdf->FancyRow(array('', '', '', 'Referensi', ':', $header->ref_no), $fc, $border);
							$pdf->FancyRow(array('', '', '', 'Tgl Kirim', ':', date('d-m-Y', strtotime($header->ship_date))), $fc, $border);
							$pdf->FancyRow(array('', '', '', 'Kirim Via', ':', $header->ship_via), $fc, $border);
							$border    = array('LB', 'B', 'B', 'B', 'B', 'BR', 'BR');
							$pdf->FancyRow(array('Attn', '', $header->contact, '', '', ''), $fc, $border);

							$pdf->ln(2);
							// $pdf->SetWidths(array(10,25,60,20,20,20,35));
							$pdf->SetWidths(array(10, 25, 60, 20, 20, 30, 25));
							$border    = array('LTB', 'TB', 'TB', 'TB', 'TB', 'TB', 'TBR');
							$align     = array('C', 'C', 'C', 'C', 'C', 'C', 'C');
							$pdf->setfont('Arial', 'B', 9);
							$pdf->SetAligns(array('L', 'C', 'R'));
							$fc        = array('0', '0', '0', '0', '0', '0', '0');
							// $judul     = array('No.','Kode Barang','Nama Obat/Produk','QTY','Satuan','Harga','Ket (Qty Stock Akhir)');
							$judul     = array('No.', 'Kode Barang', 'Nama Obat/Produk', 'QTY', 'Harga', 'Qty Stock Akhir', 'Subtotal');
							$pdf->FancyRow2(8, $judul, $fc, $border, $align);
							$border    = array('', '', '');
							$pdf->setfont('Arial', '', 9);
							$tot       = 0;
							$subtot    = 0;
							$tdisc     = 0;
							// $border    = array('','','','','','','');
							$border    = array('', '', '', '', '', '', '');
							$border    = array('', '', '', '', '', '', '');
							$align     = array('L', 'L', 'L', 'R', 'C', 'R', 'R');
							$fc        = array('0', '0', '0', '0', '0', '0', '0');
							$max       = array(8, 8, 8, 8, 8, 8, 8);
							$pdf->SetFillColor(0, 0, 139);
							$pdf->settextcolor(0);
							$no        = 1;
							$totitem   = 0;
							$total = 0;
							foreach ($detil as $db) {
								if ($db->price_po2 != '') {
									$subtotal = $db->qty_po * $db->price_po2;
									$price_po2 = number_format($db->price_po2);
									$total += $subtotal;
									$total1 = number_format($total, 0);
								} else {
									$price_po2 = $db->price_po2;
									$subtotal = '';
									$total = '';
									$total1 = '';
								}
								if ($subtotal != '') {
									$subtotal1 = number_format($subtotal);
								} else {
									$subtotal1 = '';
								}

								$pdf->FancyRow2(5, array(
									$no,
									$db->kodebarang,
									$db->namabarang,
									$db->qty_po,
									//   $db->satuan,
									$price_po2,
									$db->ket,
									$subtotal1
								), $fc, $border, $align);

								$no++;
							}
							$pdf->SetWidths(array(160, 30));
							$pdf->setfont('Arial', '', 9);
							$judul = array('TOTAL', $total1);
							$fc = array('0', '0');
							$border = array('TB', 'TB');
							$align  = array('R', 'R');
							$style = array('B', '');
							$size  = array('8', '8');
							$max = array(8, 8);
							$pdf->FancyRow2(10, $judul, $fc,  $border, $align, $style, $size, $max);



							$pdf->SetWidths(array(190));
							$judul = array('Syarat Pembayaran :');
							$border = array('T');
							$pdf->FancyRow2(10, $judul, $fc,  $border, $align, $style, $size, $max);

							$pdf->SetWidths(array(4, 70, 30, 50, 40));
							$border = array('LTBR', '', '', '', '');
							$align  = array('L', 'L', 'C', 'C', 'C');
							$pdf->setfont('Arial', '', 8);
							$fc = array('0', '0', '0', '0', '0');
							$style = array('', '', '', '', '');
							$size  = array('7', '8', '8', '8', '8');
							$judul = array('', '14 hari setelah barang diterima dalam keadaan baik', 'Disetujui oleh:', 'Disetujui oleh:', $alamat2 . ',' . date('d-m-Y'));
							$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
							$judul = array('', '28 hari setelah barang diterima dalam keadaan baik', 'Kepala Cabang,', 'Apoteker Penanggung Jawab,', 'Dibuat Oleh,');
							$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
							$judul = array('', '30 hari setelah barang diterima dalam keadaan baik', '', '', '');
							$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
							$pdf->ln(5);
							$pdf->SetWidths(array(4, 40, 30, 50, 40));
							$border = array('', 'LTR', '', '', '');
							$align  = array('L', 'C', 'C', 'C', 'C');
							$style = array('', 'B', '', '', '');
							$judul = array('', 'Form Rangkap 2', '', '', '');
							$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
							$border = array('', 'LR', '', '', '');
							$style = array('', '', '', '', '');
							$judul = array('', 'Merah : untuk supplier', '', '', '');
							$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
							$judul = array('', 'Putih : untuk keuangan', '', '', '');
							$border = array('', 'LRB', '', '', '');
							$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
							$pdf->SetWidths(array(4, 70, 30, 50, 40));
							$border = array('', '', '', '', '');
							$style = array('', 'B', 'B', 'B', 'B');
							$judul = array('', '', $profile->pejabat2, $profile->apoteker, $header->username);
							$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);
							$style = array('', '', '', '', '');
							$judul = array('', '', '', '', 'Ass. Apoteker');
							$pdf->FancyRow2(4, $judul, $fc,  $border, $align, $style, $size, $max);



							$pdf->setTitle($param);
							$pdf->AliasNbPages();
							$pdf->output($param . '.PDF', 'I');
						} else {

							header('location:' . base_url());
						}
					}

	public function cetak2($idd = '', $noo = '', $cekstk = '', $cekhrg = ''){
		$cekpdf   = 1;
		$cek      = $this->session->userdata('level');
		$unit     = $this->session->userdata('unit');
		$user     = $this->session->userdata('username');
		$id       = $this->uri->segment(3);
		$param    = $this->uri->segment(4);
		$stokket  = $this->uri->segment(5);
		$hrgket   = $this->uri->segment(6);
		if (!empty($cek)) {
			$unit 			= $this->session->userdata('unit');
			$kop       	= $this->M_cetak->kop($unit);
			$profile 		= data_master('tbl_namers', array('koders' => $unit));
			$namars    	= $kop['namars'];
			$alamat    	= $kop['alamat'];
			$alamat2   	= $kop['alamat2'];
			$alamat3  	= $profile->kota;
			$kota      	= $kop['kota'];
			$phone     	= $kop['phone'];
			$whatsapp  	= $kop['whatsapp'];
			$npwp      	= $kop['npwp'];
			$header = $this->db->query("SELECT * from tbl_apohpolog inner join tbl_vendor on tbl_apohpolog.vendor_id=tbl_vendor.vendor_id where tbl_apohpolog.po_no = '$param'")->row();
			if ($stokket == '1') {
				$kett = ",IFNULL((select sum(saldoakhir)saldoakhir from tbl_apostocklog b where koders='$unit' and b.kodebarang=tbl_logbarang.kodebarang),0)ket ";
			} else {
				$kett = ",''ket";
			}

			if ($hrgket == '1') {
				$hrgg = ", round(price_po) as price_po2 ";
			} else {
				$hrgg = ", ''price_po2";
			}
			$detil = $this->db->query("SELECT tbl_apodpolog.*, tbl_logbarang.namabarang ,IFNULL((select sum(saldoakhir)saldoakhir from tbl_apostocklog b where koders='$unit' and b.kodebarang=tbl_logbarang.kodebarang),0)ket , round(price_po) as price_po2  from tbl_apodpolog inner join tbl_logbarang on tbl_apodpolog.kodebarang=tbl_logbarang.kodebarang where po_no = '$param'")->result();
			$chari  = '';
			$chari .= "
                    <table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                         <thead>
                              <tr>
                                   <td rowspan=\"6\" align=\"center\">
                                        <img src=\"" . base_url() . "assets/img/logo.png\"  width=\"70\" height=\"70\" />
                                   </td>
                                   <td colspan=\"20\">
                                        <b>
                                             <tr>
                                                  <td style=\"font-size:10px;border-bottom: none;\"><b><br>$namars</b></td>
                                             </tr>
                                             <tr>
                                                  <td style=\"font-size:9px;\">$alamat</td>
                                             </tr>
                                             <tr>
                                                  <td style=\"font-size:9px;\">$alamat2</td>
                                             </tr>
                                             <tr>
                                                  <td style=\"font-size:9px;\">Wa :$whatsapp    Telp :$phone </td>
                                             </tr>
                                             <tr>
                                                  <td style=\"font-size:9px;\">No. NPWP : $npwp</td>
                                             </tr>
                                        </b>
                                   </td>
                              </tr>
                         </table>";
			$chari .= "
                              <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                                   <tr>
                                        <td> &nbsp; </td>
                                   </tr> 
                              </table>";
			$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\">     
                              <tr>
                                   <td style=\"border-top: none;border-right: none;border-left: none;\"></td>
                              </tr> 
                         </table>";
			$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\">     
                              <tr>
                                   <td style=\"border-top: none;border-right: none;border-left: none;\"></td>
                              </tr> 
                         </table>";
			$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                              <tr>
                                   <td> &nbsp; </td>
                              </tr> 
                         </table>";
			$chari .= "
                         <table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
                              <tr>
                                   <td width=\"15%\" style=\"text-align:center; font-size:20px;\"><b>SURAT PESANAN/PURCHASE ORDER (PO)</b></td>
                              </tr>
                         </table>";
			$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                              <tr>
                                   <td> &nbsp; </td>
                              </tr> 
                         </table>";
			$chari .= "
                         <table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
                              <tr>
                                   <td width=\"15%\" style=\"text-align:left;\"><b>Kepada Yth:</b></td>
                              </tr>
                         </table>";
			$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                              <tr>
                                   <td> &nbsp; </td>
                              </tr> 
                         </table>";
			$chari .= "
                         <table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
                              <tr>
                                   <td width=\"9%\" style=\"text-align:left; border-bottom: none; border-right: none;\">(to)</td>
                                   <td width=\"1%\" style=\"text-align:left; border-bottom: none; border-left: none; border-right: none;\">:</td>
                                   <td width=\"15%\" style=\"text-align:left; border-bottom: none; border-left: none; border-right: none;\">$header->vendor_name</td>
                                   <td width=\"15%\" style=\"text-align:left; border-bottom: none; border-left: none; border-right: none;\">PO No.</td>
                                   <td width=\"1%\" style=\"text-align:left; border-bottom: none; border-left: none; border-right: none;\">:</td>
                                   <td width=\"15%\" style=\"text-align:left; border-bottom: none; border-left: none;\">$header->po_no</td>
                              </tr>
															<tr>
																		<td width=\"9%\" style=\"text-align:left; border-bottom: none; border-right: none; border-top: none;\"></td>
                                   <td width=\"1%\" style=\"text-align:left; border-bottom: none; border-left: none; border-right: none; border-top: none;\"></td>
                                   <td width=\"15%\" style=\"text-align:left; border-bottom: none; border-left: none; border-right: none; border-top: none;\">$header->alamat</td>
                                   <td width=\"15%\" style=\"text-align:left; border-bottom: none; border-left: none; border-right: none; border-top: none;\">Tgl PO.</td>
                                   <td width=\"1%\" style=\"text-align:left; border-bottom: none; border-left: none; border-right: none; border-top: none;\">:</td>
                                   <td width=\"15%\" style=\"text-align:left; border-bottom: none; border-left: none; border-top: none;\">" . date('d-m-Y', strtotime($header->po_date)) . "</td>
															</tr>
															<tr>
																		<td width=\"9%\" style=\"text-align:left; border-bottom: none; border-right: none; border-top: none;\"></td>
                                   <td width=\"1%\" style=\"text-align:left; border-bottom: none; border-left: none; border-right: none; border-top: none;\"></td>
                                   <td width=\"15%\" style=\"text-align:left; border-bottom: none; border-left: none; border-right: none; border-top: none;\"></td>
                                   <td width=\"15%\" style=\"text-align:left; border-bottom: none; border-left: none; border-right: none; border-top: none;\">Referensi</td>
                                   <td width=\"1%\" style=\"text-align:left; border-bottom: none; border-left: none; border-right: none; border-top: none;\">:</td>
                                   <td width=\"15%\" style=\"text-align:left; border-bottom: none; border-left: none; border-top: none;\">$header->ref_no</td>
															</tr>
															<tr>
																		<td width=\"9%\" style=\"text-align:left; border-bottom: none; border-right: none; border-top: none;\"></td>
                                   <td width=\"1%\" style=\"text-align:left; border-bottom: none; border-left: none; border-right: none; border-top: none;\"></td>
                                   <td width=\"15%\" style=\"text-align:left; border-bottom: none; border-left: none; border-right: none; border-top: none;\"></td>
                                   <td width=\"15%\" style=\"text-align:left; border-bottom: none; border-left: none; border-right: none; border-top: none;\">Tgl Kirim</td>
                                   <td width=\"1%\" style=\"text-align:left; border-bottom: none; border-left: none; border-right: none; border-top: none;\">:</td>
                                   <td width=\"15%\" style=\"text-align:left; border-bottom: none; border-left: none; border-top: none;\">" . date('d-m-Y', strtotime($header->ship_date)) . "</td>
															</tr>
															<tr>
																		<td width=\"9%\" style=\"text-align:left; border-bottom: none; border-right: none; border-top: none;\"></td>
                                   <td width=\"1%\" style=\"text-align:left; border-bottom: none; border-left: none; border-right: none; border-top: none;\"></td>
                                   <td width=\"15%\" style=\"text-align:left; border-bottom: none; border-left: none; border-right: none; border-top: none;\"></td>
                                   <td width=\"15%\" style=\"text-align:left; border-bottom: none; border-left: none; border-right: none; border-top: none;\">Kirim Via</td>
                                   <td width=\"1%\" style=\"text-align:left; border-bottom: none; border-left: none; border-right: none; border-top: none;\">:</td>
                                   <td width=\"15%\" style=\"text-align:left; border-bottom: none; border-left: none; border-top: none;\">$header->ship_via</td>
															</tr>
															<tr>
																		<td width=\"9%\" style=\"text-align:left; border-right: none; border-top: none;\">Attn</td>
                                   <td width=\"1%\" style=\"text-align:left; border-left: none; border-right: none; border-top: none;\">:</td>
                                   <td width=\"15%\" style=\"text-align:left; border-left: none; border-right: none; border-top: none;\">$header->contact</td>
                                   <td width=\"15%\" style=\"text-align:left; border-left: none; border-right: none; border-top: none;\"></td>
                                   <td width=\"1%\" style=\"text-align:left; border-left: none; border-right: none; border-top: none;\"></td>
                                   <td width=\"15%\" style=\"text-align:left; border-left: none; border-top: none;\"></td>
															</tr>
                         </table>";
			$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                              <tr>
                                   <td> &nbsp; </td>
                              </tr> 
                         </table>";

			if ($stokket == '1' && $hrgket <> '1') {
				$chari .= "
				<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
				<thead>
					<tr>
						<td bgcolor=\"#cccccc\"  width=\"10%\" align=\"center\" style=\"text-align:center;\"><b>No</b></td>
						<td bgcolor=\"#cccccc\"  width=\"20%\" align=\"center\" style=\"text-align:center; \"><b>Kode Barang</b></td>
						<td bgcolor=\"#cccccc\"  width=\"20%\" align=\"center\" style=\"text-align:center; \"><b>Nama Obat/Produk</b></td>
						<td bgcolor=\"#cccccc\"  width=\"15%\" align=\"center\" style=\"text-align:center; \"><b>Qty</b></td>
						<td bgcolor=\"#cccccc\"  width=\"15%\" align=\"center\" style=\"text-align:center; \"><b>Qty Stock Akhir</b></td>
						<td bgcolor=\"#cccccc\"  width=\"20%\" align=\"center\" style=\"text-align:center; \"><b>Subtotal</b></td>
					</tr>
				</thead>";
			} elseif ($hrgket == '1' && $stokket <> '1') {
				$chari .= "
				<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
				<thead>
					<tr>
						<td bgcolor=\"#cccccc\"  width=\"10%\" align=\"center\" style=\"text-align:center;\"><b>No</b></td>
						<td bgcolor=\"#cccccc\"  width=\"20%\" align=\"center\" style=\"text-align:center; \"><b>Kode Barang</b></td>
						<td bgcolor=\"#cccccc\"  width=\"20%\" align=\"center\" style=\"text-align:center; \"><b>Nama Obat/Produk</b></td>
						<td bgcolor=\"#cccccc\"  width=\"15%\" align=\"center\" style=\"text-align:center; \"><b>Qty</b></td>
						<td bgcolor=\"#cccccc\"  width=\"15%\" align=\"center\" style=\"text-align:center; \"><b>Harga</b></td>
						<td bgcolor=\"#cccccc\"  width=\"20%\" align=\"center\" style=\"text-align:center; \"><b>Subtotal</b></td>
					</tr>
				</thead>";
			}elseif ($hrgket <> '1' && $stokket <> '1') {
				$chari .= "
				<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
				<thead>
					<tr>
						<td bgcolor=\"#cccccc\"  width=\"5%\" align=\"center\" style=\"text-align:center;\"><b>No</b></td>
						<td bgcolor=\"#cccccc\"  width=\"25%\" align=\"center\" style=\"text-align:center; \"><b>Kode Barang</b></td>
						<td bgcolor=\"#cccccc\"  width=\"25%\" align=\"center\" style=\"text-align:center; \"><b>Nama Obat/Produk</b></td>
						<td bgcolor=\"#cccccc\"  width=\"20%\" align=\"center\" style=\"text-align:center; \"><b>Qty</b></td>
						<td bgcolor=\"#cccccc\"  width=\"25%\" align=\"center\" style=\"text-align:center; \"><b>Subtotal</b></td>
					</tr>
				</thead>";
			}else{
				$chari .= "
				<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
					 <thead>
						  <tr>
							   <td bgcolor=\"#cccccc\"  width=\"5%\" align=\"center\" style=\"text-align:center;\"><b>No</b></td>
							   <td bgcolor=\"#cccccc\"  width=\"15%\" align=\"center\" style=\"text-align:center; \"><b>Kode Barang</b></td>
							   <td bgcolor=\"#cccccc\"  width=\"15%\" align=\"center\" style=\"text-align:center; \"><b>Nama Obat/Produk</b></td>
							   <td bgcolor=\"#cccccc\"  width=\"10%\" align=\"center\" style=\"text-align:center; \"><b>Qty</b></td>
							   <td bgcolor=\"#cccccc\"  width=\"10%\" align=\"center\" style=\"text-align:center; \"><b>Harga</b></td>
							   <td bgcolor=\"#cccccc\"  width=\"15%\" align=\"center\" style=\"text-align:center; \"><b>Qty Stock Akhir</b></td>
							   <td bgcolor=\"#cccccc\"  width=\"15%\" align=\"center\" style=\"text-align:center; \"><b>Subtotal</b></td>
						  </tr>
					 </thead>";
			}
			
			$no = 1;
			$total = 0;
			$total1 = '';
			foreach ($detil as $db) {
				if ($db->price_po2 != '') {
					$subtotal = $db->qty_po * $db->price_po2;
					$price_po2 = number_format($db->price_po2);
					$total += $subtotal;
					$total1 = number_format($total, 0);
				} else {
					$price_po2 = $db->price_po2;
					$subtotal = $db->qty_po * $db->price_po2;
					$subtotal = '';
					$total = '';
					$total1 = '';
				}
				if ($subtotal != '') {
					$subtotal1 = number_format($subtotal);
				} else {
					$subtotal1 = '';
				}

				if ($stokket == '1' && $hrgket <> '1') {
					$chari .= "
					<tbody>
					<tr>
						<td style=\"text-align:center;\">" . $no++ . "</td>
						<td style=\"text-align:center; \">$db->kodebarang</td>
						<td style=\"text-align:left; \">$db->namabarang</td>
						<td style=\"text-align:center; \">" . number_format($db->qty_po,0) . "</td>
						<td style=\"text-align:right; \">" . number_format($db->ket,0) . "</td>
						<td style=\"text-align:right; \">$subtotal1</td>
					</tr>
					</tbody>";
				} elseif ($hrgket == '1' && $stokket <> '1') {
					$chari .= "
					<tbody>
					<tr>
						<td style=\"text-align:center;\">" . $no++ . "</td>
						<td style=\"text-align:center; \">$db->kodebarang</td>
						<td style=\"text-align:left; \">$db->namabarang</td>
						<td style=\"text-align:center; \">" . number_format($db->qty_po,0) . "</td>
						<td style=\"text-align:right; \">" . $price_po2 . "</td>
						<td style=\"text-align:right; \">$subtotal1</td>
					</tr>
					</tbody>";
				}elseif ($hrgket <> '1' && $stokket <> '1') {

					$chari .= "
					<tbody>
					<tr>
						<td style=\"text-align:center;\">" . $no++ . "</td>
						<td style=\"text-align:center; \">$db->kodebarang</td>
						<td style=\"text-align:left; \">$db->namabarang</td>
						<td style=\"text-align:center; \">" . number_format($db->qty_po,0) . "</td>
						<td style=\"text-align:right; \">$subtotal1</td>
					</tr>
					</tbody>";
				}else{
					$chari .= "
					<tbody>
					<tr>
						<td style=\"text-align:center;\">" . $no++ . "</td>
						<td style=\"text-align:center; \">$db->kodebarang</td>
						<td style=\"text-align:left; \">$db->namabarang</td>
						<td style=\"text-align:center; \">" . number_format($db->qty_po,0) . "</td>
						<td style=\"text-align:right; \">" . $price_po2 . "</td>
						<td style=\"text-align:right; \">" . number_format($db->ket,0) . "</td>
						<td style=\"text-align:right; \">$subtotal1</td>
					</tr>
					</tbody>";
				}
				
			}
			$chari .= "</table>";
			$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">
                              <tr>
                                   <td colspan=\"8\" style=\"text-align:right; border-right: none; border-top: none;\"><b>Total</b></td>
                                   <td width=\"15%\" style=\"text-align:right; border-left: none; border-top: none;\">$total1</td>
                              </tr> 
                         </table>";
			$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                              <tr>
                                   <td> &nbsp; </td>
                              </tr> 
                         </table><hr>";
			$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                              <tr>
                                   <td> &nbsp; </td>
                              </tr> 
                         </table>";
			$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                              <tr>
                                   <td width=\"3%\"> &nbsp; </td>
                                   <td width=\"37%\"> &nbsp; </td>
                                   <td width=\"20%\"> &nbsp; </td>
                                   <td width=\"20%\"> &nbsp; </td>
                                   <td width=\"20%\"><b>Syarat Pembayaran : </b></td>
                              </tr> 
                         </table>";
			$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:10px\" width=\"100%\" align=\"center\" border=\"1\">
                              <tr>
                                   <td width=\"3%\"> &nbsp; </td>
                                   <td width=\"37%\" style=\"border-right: none; border-left: none; border-top: none; border-bottom: none;\">14 hari setelah barang diterima dalam keadaan baik</td>
                                   <td width=\"20%\" style=\"text-align:center;border-right: none; border-left: none; border-top: none; border-bottom: none;\">Disetujui oleh:</td>
                                   <td width=\"20%\" style=\"text-align:center;border-right: none; border-left: none; border-top: none; border-bottom: none;\">Disetujui oleh:</td>
                                   <td width=\"20%\" style=\"text-align:center;border-right: none; border-left: none; border-top: none; border-bottom: none;\">$alamat3, " . date('d-m-Y') . "</td>
                              </tr> 
                              <tr>
                                   <td width=\"3%\"> &nbsp; </td>
                                   <td width=\"37%\" style=\"border-right: none; border-left: none; border-top: none; border-bottom: none;\">28 hari setelah barang diterima dalam keadaan baik</td>
                                   <td width=\"20%\" style=\"text-align:center;border-right: none; border-left: none; border-top: none; border-bottom: none;\">Kepala Cabang.</td>
                                   <td width=\"20%\" style=\"text-align:center;border-right: none; border-left: none; border-top: none; border-bottom: none;\">Apoteker Penanggung Jawab,</td>
                                   <td width=\"20%\" style=\"text-align:center;border-right: none; border-left: none; border-top: none; border-bottom: none;\">Dibuat Oleh,</td>
                              </tr> 
                              <tr>
                                   <td width=\"3%\"> &nbsp; </td>
                                   <td width=\"37%\" style=\"border-right: none; border-left: none; border-top: none; border-bottom: none;\">30 hari setelah barang diterima dalam keadaan baik</td>
                                   <td width=\"20%\" style=\"text-align:center;border-right: none; border-left: none; border-top: none; border-bottom: none;\"></td>
                                   <td width=\"20%\" style=\"text-align:center;border-right: none; border-left: none; border-top: none; border-bottom: none;\"></td>
                                   <td width=\"20%\" style=\"text-align:center;border-right: none; border-left: none; border-top: none; border-bottom: none;\"></td>
                              </tr> 
                         </table>";
			$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                              <tr>
                                   <td> &nbsp; </td>
                              </tr> 
                         </table>";
			$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"25%\" border=\"1\">
                              <tr>
                                   <td style=\"text-align:center;border-bottom: none;\"><b>Form Rangkap 2</b></td>
                              </tr> 
                              <tr>
                                   <td style=\"text-align:center;border-bottom: none;border-top: none;\">Merah : untuk supplier</td>
                              </tr> 
                              <tr>
                                   <td style=\"text-align:center; border-top: none;\">Putih : untuk keuangan</td>
                              </tr> 
                         </table>";
			$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                              <tr>
                                   <td width=\"40%\" style=\"text-align:center;\"> &nbsp; </td>
                                   <td width=\"20%\" style=\"text-align:center;\"><b> $profile->pejabat2</b> </td>
                                   <td width=\"20%\" style=\"text-align:center;\"><b> $profile->apoteker</b> </td>
                                   <td width=\"20%\" style=\"text-align:center;\"><b> $header->username</b> </td>
                              </tr> 
                         </table>";
			$data['prev']    = $chari;
			$judul           = $param;
			echo ("<title>$judul</title>");
			// $this->M_template_cetak->template($judul, $body, $position, $date, $cekpdf);
			$data['prev'] = $chari;
			switch ($cekpdf) {
				case 0;
					echo ("<title>DATA GLOBAL SKI</title>");
					echo ($chari);
					break;
				case 1;
				// $this->M_cetak->mpdf('L', 'A3', $judul, $chari, 'KASIR-01.PDF', 10, 10, 10, 1);
				$this->M_cetak->mpdf('P', 'A4', $judul, $chari, '.PDF', 10, 10, 10, 2);
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

	public function laporan_po($dari, $sampai)
					{
						$cek        = $this->session->userdata('level');
						$unit       = $this->session->userdata('unit');
						$user       = $this->session->userdata('username');
						if (!empty($cek)) {
							$cabang = $this->session->userdata('unit');
							$gudang = $this->input->get('gudang');
							$kop       = $this->M_cetak->kop($unit);
							$profile = data_master('tbl_namers', array('koders' => $unit));
							$namars    = $kop['namars'];
							$alamat    = $kop['alamat'];
							$alamat2   = $kop['alamat2'];
							$alamat3  = $profile->kota;
							$kota      = $kop['kota'];
							$phone     = $kop['phone'];
							$whatsapp  = $kop['whatsapp'];
							$npwp      = $kop['npwp'];
							$chari  = '';
							if($gudang == ''){
								$header = $this->db->query("SELECT * FROM tbl_apohpolog WHERE po_date BETWEEN '$dari' AND '$sampai' AND koders = '$cabang'")->result();
							} else {
								$header = $this->db->query("SELECT * FROM tbl_apohpolog WHERE po_date BETWEEN '$dari' AND '$sampai' AND gudang = '$gudang' AND koders = '$cabang' LIMIT 1")->result();
							}
							$chari .= "
                    <table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                         <thead>
                              <tr>
                                   <td rowspan=\"6\" align=\"center\">
                                        <img src=\"" . base_url() . "assets/img/logo.png\"  width=\"70\" height=\"70\" />
                                   </td>
                                   <td colspan=\"20\">
                                        <b>
                                             <tr>
                                                  <td style=\"font-size:10px;border-bottom: none;\"><b><br>$namars</b></td>
                                             </tr>
                                             <tr>
                                                  <td style=\"font-size:9px;\">$alamat</td>
                                             </tr>
                                             <tr>
                                                  <td style=\"font-size:9px;\">$alamat2</td>
                                             </tr>
                                             <tr>
                                                  <td style=\"font-size:9px;\">Wa :$whatsapp    Telp :$phone </td>
                                             </tr>
                                             <tr>
                                                  <td style=\"font-size:9px;\">No. NPWP : $npwp</td>
                                             </tr>
                                        </b>
                                   </td>
                              </tr>
                         </table>";
							$chari .= "
                              <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                                   <tr>
                                        <td> &nbsp; </td>
                                   </tr> 
                              </table>";
							$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\">     
                              <tr>
                                   <td style=\"border-top: none;border-right: none;border-left: none;\"></td>
                              </tr> 
                         </table>";
							$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\">     
                              <tr>
                                   <td style=\"border-top: none;border-right: none;border-left: none;\"></td>
                              </tr> 
                         </table>";
							$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                              <tr>
                                   <td> &nbsp; </td>
                              </tr> 
                         </table>";
							$chari .= "
                         <table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
                              <tr>
                                   <td width=\"15%\" style=\"text-align:center; font-size:20px;\"><b>LAPORAN PO (Purchase Order) CABANG ".$cabang."</b></td>
                              </tr>
                         </table>";
							$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                              <tr>
                                   <td> &nbsp; </td>
                              </tr> 
                         </table>";
							$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"20%\" align=\"left\" border=\"0\">
                              <tr>
                                   <td>Dari Tanggal</td>
                                   <td>:</td>
                                   <td>".date("d-m-Y", strtotime($dari))."</td>
                              </tr> 
                              <tr>
                                   <td>Sampai Tanggal</td>
                                   <td>:</td>
                                   <td>".date("d-m-Y", strtotime($sampai))."</td>
                              </tr> 
                         </table>";
							$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                              <tr>
                                   <td> &nbsp; </td>
                              </tr> 
                         </table>";
							$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellpadding=\"10\" cellspacing=\"10\">
                              <thead style=\"background-color: grey;\">
																<tr>
																		<td style=\"text-align: center; background-color: grey; color: white;\"><b>No</b></td>
																		<td style=\"text-align: center; background-color: grey; color: white;\"><b>No PO</b></td>
																		<td style=\"text-align: center; background-color: grey; color: white;\"><b>Kode Barang</b></td>
																		<td style=\"text-align: center; background-color: grey; color: white;\"><b>Nama Barang</b></td>
																		<td style=\"text-align: center; background-color: grey; color: white;\"><b>Satuan</b></td>
																		<td style=\"text-align: center; background-color: grey; color: white;\"><b>Qty</b></td>
																		<td style=\"text-align: center; background-color: grey; color: white;\"><b>Harga</b></td>
																		<td style=\"text-align: center; background-color: grey; color: white;\"><b>Diskon</b></td>
																		<td style=\"text-align: center; background-color: grey; color: white;\"><b>Total</b></td>
																</tr>
															</thead>";
							foreach($header as $h) {
								$gudangnya = $this->db->get_where("tbl_depo", ["depocode" => $h->gudang])->row();
								$total = $this->db->query("SELECT SUM(total) AS total FROM tbl_apodpolog WHERE po_no = '$h->po_no' LIMIT 1")->result();
								foreach($total as $t){
									$ttl = $t->total;
								}
								$chari .= "<tbody>
														<tr>
															<td colspan=\"3\">Gudang : <b>".$gudangnya->keterangan."</b></td>
															<td colspan=\"5\">Tgl PO : ".date("d-m-Y", strtotime($h->po_date))."</td>
															<td style=\"text-align: right;\"><b>".number_format($ttl)."</b></td>
														</tr>";
								$detail = $this->db->query("SELECT d.po_no, d.kodebarang, b.namabarang, d.satuan, d.qty_po, d.price_po, d.discount, d.total FROM tbl_apodpolog d JOIN tbl_logbarang b ON d.kodebarang = b.kodebarang WHERE d.po_no = '$h->po_no'")->result();
								$no = 1;
								foreach($detail as $d){
									if($d->discount == '' || $d->discount == null){
										$disc = 0;
									} else {
										$disc = number_format($d->discount);
									}
									$chari .= "<tr>
															<td>".$no++."</td>
															<td>".$d->po_no."</td>
															<td>".$d->kodebarang."</td>
															<td>".$d->namabarang."</td>
															<td>".$d->satuan."</td>
															<td style=\"text-align: right;\">".number_format($d->qty_po)."</td>
															<td style=\"text-align: right;\">".number_format($d->price_po)."</td>
															<td style=\"text-align: right;\">".$disc."</td>
															<td style=\"text-align: right;\">".number_format($d->total)."</td>
														</tr>
													</tbody>";
								}
							}
							$chari .= "</table>";
							$data['prev'] = $chari;
							$judul = "LAPORAN PO (Purchase Order) Dari : ".$dari." Sampai : ".$sampai;
							echo ("<title>$judul</title>");
							$this->M_cetak->mpdf('L', 'A4', $judul, $chari, '.PDF', 10, 10, 10, 2);
						} else {
							header('location:' . base_url());
						}
					}
}

/* End of file master_bank.php */
/* Location: ./application/controllers/master_akun.php */