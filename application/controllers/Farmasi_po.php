<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Farmasi_po extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_farmasi_po', 'M_farmasi_po');
		$this->load->model('M_cetak');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '3000');
		$this->session->set_userdata('submenuapp', '3101');
		$this->load->helper('simkeu_nota');
		$this->load->helper('simkeu_nota1');
	}

	public function index()
	{
		$cek = $this->session->userdata('username');
		if (!empty($cek)) {
			$level = $this->session->userdata('level');
			$akses = $this->M_global->cek_menu_akses($level, 3101);
			$this->load->helper('url');
			$data['modul'] = 'FARMASI';
			$data['submodul'] = 'Purchase Order/PO';
			$data['link'] = 'Purchase Order/PO';
			$data['url'] = 'farmasi_po';
			$data['tanggal'] = date('d-m-Y');
			$data['akses'] = $akses;
			$this->load->view('farmasi/v_farmasi_po', $data);
		} else {
			header('location:' . base_url());
		}
	}

	public function add()
	{
		$cek = $this->session->userdata('username');
		if (!empty($cek)) {
			$level = $this->session->userdata('level');
			$akses = $this->M_global->cek_menu_akses($level, 3101);
			$this->load->helper('url');
			$data['modul']    = 'FARMASI';
			$data['submodul'] = 'Purchase Order/PO';
			$data['link']     = 'Purchase Order/PO';
			$data['url']      = 'farmasi_po';
			$data['tanggal']  = date('d-m-Y');
			$data['akses']    = $akses;
			//$data['nomorpo']=urut_transaksi('SETUP_APO', 19);
			$data['nomorpo'] = 'Auto';
			$this->load->view('farmasi/v_farmasi_po_add', $data);
		} else {
			header('location:' . base_url());
		}
	}

	public function edit($id)
	{
		$cek = $this->session->userdata('username');
		if (!empty($cek)) {
			$level            = $this->session->userdata('level');
			$akses            = $this->M_global->cek_menu_akses($level, 3101);
			$this->load->helper('url');
			$data['modul']    = 'FARMASI';
			$data['submodul'] = 'Purchase Order/PO';
			$data['link']     = 'Purchase Order/PO';
			$data['url']      = 'farmasi_po';
			$data['tanggal']  = date('d-m-Y');
			$data['akses']    = $akses;
			$header           = $this->db->query("SELECT * from tbl_baranghpo where id = $id")->row();
			$option           = $this->db->query("SELECT dikirimvia FROM  tbl_baranghpo  where id = $id")->result();
			$nomorpo          = $header->po_no;
			$detil            = $this->db->query("SELECT tbl_barangdpo.*, tbl_barang.namabarang from tbl_barangdpo inner join tbl_barang on tbl_barangdpo.kodebarang=tbl_barang.kodebarang where po_no = '$nomorpo' ");
			$data['nomorpo']  = $nomorpo;
			$data['header']   = $header;
			$data['option']	= $option;
			$data['detil']    = $detil->result();
			$data['jumdata']  = $detil->num_rows();
			//   var_dump($data['option']); die;
			$this->load->view('farmasi/v_farmasi_po_edit', $data);
		} else {
			header('location:' . base_url());
		}
	}

	public function ajax_list($param)
	{
		$lvx    = $this->session->userdata('user_level');
		$level  = $this->session->userdata('level');
		$akses  = $this->M_global->cek_menu_akses($level, 3101);
		$lock   = $this->M_global->close_app();
		$dat    = explode("~", $param);
		if ($dat[0] == 1) {
			$bulan = date('m');
			$tahun = date('Y');
			$list = $this->M_farmasi_po->get_datatables(1, $bulan, $tahun);
		} else {
			$bulan  = date('Y-m-d', strtotime($dat[1]));
			$tahun  = date('Y-m-d', strtotime($dat[2]));
			$list = $this->M_farmasi_po->get_datatables(2, $bulan, $tahun);
		}

		$data = array();
		$no = $_POST['start'];
		foreach ($list as $rd) {
			$no++;
			$row = array();
			$disetujui = $this->db->get_where('userlogin', ['uidlogin' => $rd->disetujuioleh])->row_array();
			$dx = $disetujui['username'];

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
			$row[] = '<span "font-weight:bold;"><b>' . date('d-m-Y', strtotime($rd->tglpo)) . '</b></span>';
			$row[] = '<span "font-weight:bold;"><b>' . $rd->vendor_name . '</b></span>';
			$row[] = '<span "font-weight:bold;"><b>' . date('d-m-Y', strtotime($rd->tglkirim)) . '</b></span>';
			$row[] = '<span "font-weight:bold;"><b>' . $rd->nama . '</b></span>';
			$row[] = '<span "font-weight:bold;"><b>' . $status . '</b></span>';

			if ($akses->uedit == 1 && $akses->udel == 1 && $lock == 0) {
				$email = $this->db->get_where('tbl_vendor', ['vendor_id' => $rd->vendor_id])->row_array();
				if ($rd->setuju == 0) {
					if ($lvx >= 2) {
						$row[] = '
							<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data(' . "'" . $rd->id . "'" . ",'" . $rd->po_no . "'" . ')">
								<i class="glyphicon glyphicon-trash"></i> 
							</a>
							<a class="btn btn-sm btn-primary" href="' . base_url("farmasi_po/edit/" . $rd->id . "") . '" title="Edit" >
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
							<a class="btn btn-sm btn-primary" href="' . base_url("farmasi_po/show/" . $rd->id . "") . '" title="Show" >
								<i class="glyphicon glyphicon-eye-open"></i> 
							</a>
						';
					}
				}
			} else if ($akses->uedit == 1 && $akses->udel == 0 && $lock == 0) {
				$row[] = '<a class="btn btn-sm btn-primary" href="' . base_url("farmasi_po/edit/" . $rd->id . "") . '" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a> ';
			} else if ($akses->uedit == 0 && $akses->udel == 1 && $lock == 0) {
				$row[] = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data(' . "'" . $rd->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i> </a>';
			} else {
				$row[] = '';
			}

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->M_farmasi_po->count_all($dat[0], $bulan, $tahun),
			"recordsFiltered" => $this->M_farmasi_po->count_filtered($dat[0],  $bulan, $tahun),
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
			$data['modul']    = 'FARMASI';
			$data['submodul'] = 'Purchase Order/PO';
			$data['link']     = 'Purchase Order/PO';
			$data['url']      = 'farmasi_po';
			$data['tanggal']  = date('d-m-Y');
			$data['akses']    = $akses;
			$header           = $this->db->query("SELECT * from tbl_baranghpo where id = $id")->row();
			$option           = $this->db->query("SELECT dikirimvia FROM  tbl_baranghpo  where id = $id")->result();
			$nomorpo          = $header->po_no;
			$detil            = $this->db->query("SELECT tbl_barangdpo.*, tbl_barang.namabarang from tbl_barangdpo inner join tbl_barang on tbl_barangdpo.kodebarang=tbl_barang.kodebarang where po_no = '$nomorpo' ");
			$data['nomorpo']  = $nomorpo;
			$data['header']   = $header;
			$data['option']	= $option;
			$data['detil']    = $detil->result();
			$data['jumdata']  = $detil->num_rows();
			$this->load->view('farmasi/v_farmasi_po_show', $data);
		} else {
			header('location:' . base_url());
		}
	}

	public function send_email()
	{
		$userid   = $this->session->userdata('inv_username');
		$unit = $this->session->userdata('unit');
		$profile = data_master('tbl_namers', array('koders' => $unit));
		$nama_usaha = $profile->namars;
		$email_usaha = $profile->email;

		$param = $this->input->post('id');

		$data = $this->db->query("SELECT h.*, d.*, v.email, v.vendor_name, v.alamat, v.contact from tbl_baranghpo h join tbl_barangdpo d ON h.po_no=d.po_no JOIN tbl_vendor v ON v.vendor_id=h.vendor_id where h.id = '$param'")->row();

		// $this->genpdf($data->po_no);
		$attched_file  = base_url() . "uploads/po/" . $data->po_no . ".PDF";
		$mobile = $data->contact;
		$ready_message   =
			"PO" . "\r\n" .
			"No. PO : " . $data->po_no . "\r\n" .
			"No. Ref : " . $data->ref_no . "\r\n" .
			"Nama Vendor : " . $data->vendor_name . "\r\n" .
			"Alamat : " . $data->alamat . "\r\n" .
			"Tanggal PO : " . date('d M Y', strtotime($data->tglpo)) . "\r\n" .
			"Tanggal Kirim : " . date('d M Y', strtotime($data->tglkirim)) . "\r\n" .
			"
		<table class='table table-striped table-bordered table-hover'>
			<thead>
				<tr>
					<th width='1%>No</th>
					<th>Kode Barang</th>
					<th>Nama Barang</th>
					<th width='7%'>Qty PO</th>
					<th width='10%'>Qty Terima</th>
					<th>Satuan</th>
					<th>Harga Sat</th>
					<th>Sub Total</th>
				</tr>
			</thead>
		</table>
		\r\n";
		// // 	"Pelayanan : ".data_master('tbl_namapos',array('kodepos' => $data->kodepost))->namapost."\r\n".
		// // 	"Dokter : ".data_master('tbl_dokter',array('kodokter' => $data->kodokter))->nadokter."\r\n";

		$email_tujuan = trim($data->email);

		$server_subject = "DATA PO ";

		$email_usaha = "support@gmail.com";
		$this->load->library('email');
		$this->email->from($email_usaha, $nama_usaha);
		$this->email->to($email_tujuan);
		$this->email->subject($server_subject);
		$this->email->message($ready_message);
		$this->email->attach($attched_file);

		if ($this->email->send()) {
			echo json_encode(array("status" => 1));
		} else {
			echo json_encode(array("status" => 0));
		}
	}


	public function getinfobarang()
	{
		$cabang = $this->session->userdata('unit');
		$param = $this->input->get('kode');
		$gudang = $this->input->get('gudang');
		$data = $this->db->query("SELECT a.*, (select saldoakhir from tbl_barangstock where koders = '$cabang' and gudang = '$gudang' and kodebarang=a.kodebarang) as saldoakhir FROM tbl_barang a WHERE kodebarang = '$param'")->row();
		echo json_encode($data);
	}

	public function load_wil()
	{

		$sql = "SELECT * FROM tbl_vendor order by vendor_id";

		$mas = $this->db->query($sql);
		$result = array();
		$ii = 0;
		foreach ($mas->result_array() as $resulte) {

			$result[] = array(
				'id'          => $ii,
				'vendor_id'   => $resulte['vendor_id'],
				'vendor_name' => $resulte['vendor_name']
			);
			$ii++;
		}

		echo json_encode($result);
	}

	public function load_barang($str)
	{
		$unit = $this->session->userdata('unit');

		$sql = "SELECT kodebarang as id, kodebarang , namabarang , satuan1 , salakhir , hargajual FROM(
			SELECT
			kodebarang,namabarang,satuan1,hargajual,
			IFNULL((select sum(saldoakhir)saldoakhir from tbl_barangstock b where koders='$unit' and gudang='%$str%' and b.kodebarang=a.kodebarang),0)salakhir
			from tbl_barang a where (kodebarang like '%%' or namabarang like '%%') 
			)c
			order by kodebarang";

		$mas = $this->db->query($sql);
		$result = array();
		$ii = 0;
		foreach ($mas->result_array() as $resulte) {

			$result[] = array(
				'id'            => $ii,
				'kodebarang'    => $resulte['kodebarang'],
				'namabarang'    => $resulte['namabarang'],
				'satuan1'       => $resulte['satuan1'],
				'salakhir'      => $resulte['salakhir'],
				'hargajual'     => $resulte['hargajual']
			);
			$ii++;
		}

		echo json_encode($result);
	}

	public function getlistbarang()
	{
		$str = $this->input->get('kode');
		$unit = $this->session->userdata('unit');


		$qry = "SELECT kodebarang as id, concat(' [ ', kodebarang ,' ] ',' - ',' [ ', namabarang ,' ] ',' - ',' [ ', satuan1 ,' ] ',' - ',' [ ', salakhir ,' ] ',' - ',' [ ', hargajual ,' ] ') as text FROM(
			SELECT
			kodebarang,namabarang,satuan1,hargajual,
			IFNULL((select sum(saldoakhir)saldoakhir from tbl_barangstock b where koders='$unit' and gudang='%$str%' and b.kodebarang=a.kodebarang),0)salakhir
			from tbl_barang a 
			)c
			order by kodebarang";

		$query = $this->db->query($qry)->result();

		if (!$query) {
			$response = [
				'message'	=> 'not found',
				'data'		=> [],
				'status'	=> false,
			];
		} else {
			$response = [
				'message'	=> 'Success',
				'data'		=> $query,
				'status'	=> true,
			];
		}
		$json = json_encode($response);
		print_r($json);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_akuntansi_sa->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}


	public function ajax_add($param)
	{
		$cabang = $this->session->userdata('unit');
		$userid = $this->session->userdata('username');

		$tanggal = date('Y-m-d');
		$jam     = date('H:i:s');

		if ($param == 1) {
			$nomorpo = urut_transaksi('SETUP_APO', 19);
		} else {
			$nomorpo = $this->input->post('nomorbukti');
		}
		//$this->_validate();
		$data = array(
			'koders'        => $cabang,
			'po_no'         => $nomorpo,
			'ref_no'        => $this->input->post('noref'),
			'tglpo'         => $this->input->post('tanggal'),
			'tglkirim'      => $this->input->post('tanggalkirim'),
			'vendor_id'     => $this->input->post('supp'),
			'username'      => $userid,
			'disetujuioleh'  => $userid,
			'kurs'          => $this->input->post('kurs'),
			'kursrate'      => $this->input->post('rate'),
			'dikirimvia'    => $this->input->post('dikirimvia'),
			'gudang'        => $this->input->post('gudang'),
			'internalpo'    => $this->input->get('ipo'),
		);

		if ($param == 1) {
			$insert = $this->db->insert('tbl_baranghpo', $data);
		} else {
			$this->db->query("DELETE from tbl_barangdpo where po_no = '$nomorpo'");
			$this->db->update('tbl_baranghpo', $data, array('po_no' => $nomorpo));
		}



		$kode   = $this->input->post('kode');
		$qty    = $this->input->post('qty');
		$sat    = $this->input->post('sat');
		$harga  = $this->input->post('harga');
		// $disc   = $this->input->post('disc');
		// $tax    = $this->input->post('tax');
		$jumlah = $this->input->post('jumlah');

		$jumdata    = count($kode);
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
				'discount'     => 0,
				'satuan'       => $sat[$i],
				'vat'          => 0,
				'total'        => $_jumlah,

			);

			if ($kode[$i] != "") {
				$insert_detil = $this->db->insert('tbl_barangdpo', $data_rinci);
				$pjk = $this->db->get_where("tbl_pajak", ['kodetax' => 'PPN'])->row();
				$persen_pjk = $pjk->prosentase / 100;
				$cekdata = $this->db->query("SELECT * FROM tbl_barang WHERE kodebarang = '".$kode[$i]."'")->result();
				foreach($cekdata as $cd){
					if($cd->vat == 1){
						$hargabelippn_new = $_harga + ($_harga * $persen_pjk);
					} else {
						$hargabelippn_new = $_harga;
					}
					$hargabeli_new = $_harga;
					$this->db->set("hargabeli", $hargabeli_new);
					$this->db->set("hargabelippn", $hargabelippn_new);
					$this->db->where("kodebarang", $cd->kodebarang);
					$this->db->update("tbl_barang");
				}
			}
		}


		echo json_encode(array("status" => TRUE, "nomor" => $nomorpo));
	}


	public function ajax_delete()
	{
		$cabang = $this->session->userdata('unit');
		$id = $this->input->post('id');
		$data = $this->db->get_where('tbl_baranghpo', array('id' => $id))->row();
		$nopo = $data->po_no;
		$this->db->delete('tbl_baranghpo', array('id' => $id));
		$this->db->delete('tbl_barangdpo', array('po_no' => $nopo, 'koders' => $cabang));
		echo json_encode(array("status" => 1));
	}

	public function approve()
	{
		$tgl = date('Y-m-d');
		$cabang = $this->session->userdata('unit');
		$id = $this->input->post('id');
		$userid = $this->session->userdata('username');
		$this->db->set('setuju', 1);
		$this->db->set('disetujuitgl', $tgl);
		$this->db->set('disetujuijam', date('H:i:s'));
		$this->db->set('disetujuioleh', $userid);
		$this->db->where('id', $id);
		$this->db->update('tbl_baranghpo');
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
          <td width="2%"><a class="btn btn-sm btn-danger" onclick="delete_data(<?php echo $id; ?>)"><i
                class="glyphicon glyphicon-trash"></i></a></td>
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

					public function cetak($idd, $noo, $cekstok, $cekhrg)
					{
						$cek        = $this->session->userdata('level');
						$unit       = $this->session->userdata('unit');
						$user       = $this->session->userdata('username');
						$avatar     = $this->session->userdata('avatar_cabang');
						$id         = $this->uri->segment(3);
						$param      = $this->uri->segment(4);
						$stokket    = $this->uri->segment(5);
						$hrgket     = $this->uri->segment(6);
						if (!empty($cek)) {
							$unit = $this->session->userdata('unit');
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
							$queryh = "SELECT * from tbl_baranghpo inner join tbl_vendor on tbl_baranghpo.vendor_id=tbl_vendor.vendor_id where tbl_baranghpo.po_no = '$param'";
							if ($stokket == '1') {
								$kett = ", IFNULL((select sum(saldoakhir)saldoakhir from tbl_barangstock b where koders='$unit' and b.kodebarang=tbl_barang.kodebarang),0)ket ";
							} else {
								$kett = ",''ket";
							}
							if ($hrgket == '1') {
								$hrgg = ", round(price_po) as price_po2 ";
							} else {
								$hrgg = ", ''price_po2";
							}
							$queryd = "SELECT tbl_barangdpo.*, tbl_barang.namabarang $kett $hrgg from tbl_barangdpo inner join tbl_barang on tbl_barangdpo.kodebarang=tbl_barang.kodebarang where po_no = '$param'";
							$detil  = $this->db->query($queryd)->result();
							$header = $this->db->query($queryh)->row();
							$chari .= "
                    <table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                         <thead>
                              <tr>
                                   <td rowspan=\"6\" align=\"center\">
                                        <img src=\"" . base_url() . "assets/img_user/$avatar\"  width=\"70\" height=\"70\" />
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
                                   <td width=\"15%\" style=\"text-align:left; border-bottom: none; border-left: none; border-top: none;\">" . date('d-m-Y', strtotime($header->tglpo)) . "</td>
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
                                   <td width=\"15%\" style=\"text-align:left; border-bottom: none; border-left: none; border-top: none;\">" . date('d-m-Y', strtotime($header->tglkirim)) . "</td>
															</tr>
															<tr>
																		<td width=\"9%\" style=\"text-align:left; border-bottom: none; border-right: none; border-top: none;\"></td>
                                   <td width=\"1%\" style=\"text-align:left; border-bottom: none; border-left: none; border-right: none; border-top: none;\"></td>
                                   <td width=\"15%\" style=\"text-align:left; border-bottom: none; border-left: none; border-right: none; border-top: none;\"></td>
                                   <td width=\"15%\" style=\"text-align:left; border-bottom: none; border-left: none; border-right: none; border-top: none;\">Kirim Via</td>
                                   <td width=\"1%\" style=\"text-align:left; border-bottom: none; border-left: none; border-right: none; border-top: none;\">:</td>
                                   <td width=\"15%\" style=\"text-align:left; border-bottom: none; border-left: none; border-top: none;\">$header->dikirimvia</td>
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
							$chari .= "
                         <table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
                              <thead>
                                   <tr>
                                        <td width=\"5%\" align=\"center\" style=\"text-align:center; border-right: none; border-left: none;\"><b>No</b></td>
                                        <td width=\"15%\" align=\"center\" style=\"text-align:center; border-right: none; border-left: none;\"><b>Kode Barang</b></td>
                                        <td width=\"15%\" align=\"center\" style=\"text-align:center; border-right: none; border-left: none;\"><b>Nama Obat/Produk</b></td>
                                        <td width=\"10%\" align=\"center\" style=\"text-align:center; border-right: none; border-left: none;\"><b>Qty</b></td>
                                        <td width=\"10%\" align=\"center\" style=\"text-align:center; border-right: none; border-left: none;\"><b>Harga</b></td>
                                        <td width=\"15%\" align=\"center\" style=\"text-align:center; border-right: none; border-left: none;\"><b>Qty Stock Akhir</b></td>
                                        <td width=\"15%\" align=\"center\" style=\"text-align:center; border-left: none; border-right: none;\"><b>Subtotal</b></td>
                                   </tr>
                              </thead>";
							$no = 1;
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
								$chari .= "<tbody><tr>
																<td style=\"text-align:center; border-right: none; border-left: none; border-top: none; border-bottom: none;\">" . $no++ . "</td>
																<td style=\"text-align:left; border-right: none; border-left: none; border-top: none; border-bottom: none;\">$db->kodebarang</td>
																<td style=\"text-align:left; border-right: none; border-left: none; border-top: none; border-bottom: none;\">$db->namabarang</td>
																<td style=\"text-align:right; border-right: none; border-left: none; border-top: none; border-bottom: none;\">" . number_format($db->qty_po) . "</td>
																<td style=\"text-align:right; border-right: none; border-left: none; border-top: none; border-bottom: none;\">" . $price_po2 . "</td>
																<td style=\"text-align:right; border-right: none; border-left: none; border-top: none; border-bottom: none;\">$db->ket</td>
																<td style=\"text-align:right; border-left: none; border-right: none; border-top: none; border-bottom: none;\">$subtotal1</td>
													 </tr></tbody>";
							}
							$chari .= "</table>";
							$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">
                              <tr>
                                   <td colspan=\"8\" style=\"text-align:right; border-right: none; border-left: none;\"><b>Total</b></td>
                                   <td width=\"15%\" style=\"text-align:right; border-left: none; border-right: none;\">$total1</td>
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
							$data['prev'] = $chari;
							$judul = $param;
							echo ("<title>$judul</title>");
							$this->M_cetak->mpdf('P', 'A4', $judul, $chari, '.PDF', 10, 10, 10, 2);
						} else {

							header('location:' . base_url());
						}
					}
				}

/* End of file master_bank.php */
/* Location: ./application/controllers/master_akun.php */