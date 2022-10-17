<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Master_tarif extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_tarif', 'M_tarif');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '1000');
		$this->session->set_userdata('submenuapp', '1103');
	}

	public function index2()
	{
		$cek    = $this->session->userdata('level');
		$userid = $this->session->userdata('unit');
		if (!empty($cek)) {
			$data['poli']            = $this->db->get('tbl_namapos')->result();

			$data['cab']             = $this->db->query("SELECT * from tbl_tarif where kodetarif = 'kodetarif'")->result();

			$data['bhp']             = $this->db->query("select tbl_masterbhp.*,tbl_barang.namabarang from tbl_masterbhp inner join tbl_barang on tbl_masterbhp.kodeobat=tbl_barang.kodebarang where tbl_masterbhp.kodetarif='kodetarif'")->result();
			$data['jumdatatarif']    = $this->db->query("SELECT * from tbl_tarif where kodetarif = 'kodetarif'")->num_rows();

			$data['jumdatabhp']      = $this->db->query("SELECT * from tbl_masterbhp where kodetarif = 'kodetarif'")->num_rows();
			$data['unit']            = $userid;

			$this->load->view('master/v_master_tarif', $data);
		} else {
			header('location:' . base_url());
		}
	}

	public function index()
	{
		$cek = $this->session->userdata('level');
		$userid = $this->session->userdata('unit');
		if (!empty($cek)) {
			$data = [
				'poli' => $this->db->get('tbl_namapos')->result(),
				'master_tarif' => $this->db->query("SELECT t.*, (SELECT acname FROM tbl_accounting where accountno=t.accountno) as akun FROM tbl_tarifh t")->result(),
				'pendapatan' => $this->db->query("SELECT accountno as id, concat(accountno, ' - ', acname) as text from tbl_accounting order by id")->result(),
				'cabang' => $this->db->query("SELECT koders as id, concat(namars) as text from tbl_namers order by koders")->result(),
				'tarif' => $this->db->query("SELECT cust_id as id, concat(cust_nama) as text from tbl_penjamin order by cust_id")->result(),
				'barang' => $this->db->query("SELECT kodebarang as id, concat(' [ ', kodebarang ,' ] ',' - ',' [ ', namabarang ,' ] ',' - ',' [ ', satuan1 ,' ] ') as text FROM(SELECT kodebarang,namabarang,satuan1, IFNULL((select sum(saldoakhir) as saldoakhir from tbl_barangstock b where b.kodebarang=a.kodebarang),0) as salakhir from tbl_logbarang a) as c order by kodebarang")->result(),
			];
			$this->load->view('master/v_master_tarif2', $data);
		} else {
			header('location:' . base_url());
		}
	}

	public function ubah_hal($id)
	{
		$cek = $this->session->userdata('level');
		$userid = $this->session->userdata('unit');
		if (!empty($cek)) {
			$tarifh = $this->db->get_where("tbl_tarifh", ['id' => $id])->row();
			$tarif = $this->db->get_where("tbl_tarif", ['kodetarif' => $tarifh->kodetarif])->result();
			$bhp = $this->db->query("SELECT m.*, (SELECT namabarang FROM tbl_logbarang WHERE kodebarang = m.kodeobat) as namabarang, (SELECT satuan1 FROM tbl_logbarang WHERE kodebarang = m.kodeobat) as satuan1, (SELECT saldoakhir FROM tbl_apostocklog WHERE kodebarang=m.kodeobat) as saldoakhir FROM tbl_masterbhp m WHERE kodetarif = '$tarifh->kodetarif'")->result();
			$jumdetail = $this->db->get_where("tbl_tarif", ['kodetarif' => $tarifh->kodetarif])->num_rows();
			$jumbhp = $this->db->get_where("tbl_masterbhp", ['kodetarif' => $tarifh->kodetarif])->num_rows();
			$data = [
				'header' => $tarifh,
				'detail' => $tarif,
				'bhp' => $bhp,
				'jumdetail' => $jumdetail,
				'jumbhp' => $jumbhp,
			];
			$this->load->view('master/v_ubah_master_tarif', $data);
		} else {
			header('location:' . base_url());
		}
	}

	public function ajax_list()
	{
		$list = $this->M_tarif->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit) {
			$no++;
			$row = array();
			$row[] = $unit->kodetarif;
			$row[] = $unit->tindakan;
			$row[] = $unit->kodepos;
			$row[] = $unit->accountno;

			$row[] =
				'
				<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data(' . "'" . $unit->id . "'" . ')"><i class="glyphicon glyphicon-edit"></i> </a>
				<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data(' . "'" . $unit->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i> </a>
				<a class="btn btn-sm btn-warning" href="' . base_url("master_tarif/master_bhp/?kode=" . $unit->id . "") . '" title="BHP" ><i class="glyphicon glyphicon-edit"></i> BHP </a>
			     ';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->M_tarif->count_all(),
			"recordsFiltered" => $this->M_tarif->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function master_bhp()
	{
		$id = $this->input->get('kode');
		$tarif = $this->db->query("select kodetarif, tindakan from tbl_tarifh where id = '$id'")->row();
		$kode = $tarif->kodetarif;
		$data['kode'] = $tarif->kodetarif;
		$data['nama'] = $tarif->tindakan;

		$data['jumdata'] = $this->db->query("SELECT * from tbl_masterbhp where kodetarif = '$kode'")->num_rows();

		$data['bhp']  = $this->db->query("select tbl_masterbhp.*,tbl_barang.namabarang from tbl_masterbhp
	    inner join tbl_barang on tbl_masterbhp.kodeobat=tbl_barang.kodebarang 
	    where tbl_masterbhp.kodetarif='$kode'")->result();

		$data['jumdatatarif'] = $this->db->query("SELECT * from tbl_tarif where kodetarif = '$kode'")->num_rows();

		$data['cab']  = $this->db->query("select tbl_tarif.* from tbl_tarif
	    inner join tbl_penjamin on tbl_tarif.cust_id=tbl_penjamin.cust_id 
	    where tbl_tarif.kodetarif='$kode'")->result();

		$this->load->view('master/v_master_tarif_bhp', $data);
	}

	public function save_bhp($param)
	{
		$hasil = 0;
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$kodetarif = $this->input->post('kodetarif');
			$userid   = $this->session->userdata('username');
			$unit     = $this->session->userdata('unit');

			$this->db->delete('tbl_tarif', array('kodetarif' => $kodetarif));
			$this->db->delete('tbl_masterbhp', array('kodetarif' => $kodetarif));

			$cabang  = $this->input->post('cabang');
			$keltarif   = $this->input->post('keltarif');
			$jasars   = $this->input->post('jasars');
			$jasadr = $this->input->post('jasadr');
			$jasaperawat = $this->input->post('jasaperawat');
			$bhp = $this->input->post('bhp');
			$total = $this->input->post('total');

			$jumdatatarif  = count($cabang);

			$nourut = 1;
			$tot = 0;

			for ($i = 0; $i <= $jumdatatarif - 1; $i++) {
				$_cabang  = $cabang[$i];
				$_keltarif   = $keltarif[$i];
				$_jasars   = str_replace(',', '', $jasars[$i]);
				$_jasadr = str_replace(',', '', $jasadr[$i]);
				$_jasaperawat = str_replace(',', '', $jasaperawat[$i]);
				$_bhp = str_replace(',', '', $bhp[$i]);
				$_total = str_replace(',', '', $total[$i]);

				$datatarif = array(
					'koders'		=> $_cabang,
					'kodetarif'		=> $kodetarif,
					'cust_id'		=> $_keltarif,
					'tarifrspoli'	=> $_jasars,
					'tarifdrpoli'	=> $_jasadr,
					'feemedispoli'	=> $_jasaperawat,
					'bhppoli'		=> $_bhp,
					'cost'			=> $_total,
				);

				if ($_cabang != "") {
					$this->db->insert('tbl_tarif', $datatarif);
				}
			}

			$kode  = $this->input->post('kode');
			$qty   = $this->input->post('qty');
			$sat   = $this->input->post('sat');
			$harga = $this->input->post('harga');
			$jumlah = $this->input->post('jumlah');

			$jumdata  = count($kode);

			$nourut = 1;
			$tot = 0;

			for ($i = 0; $i <= $jumdata - 1; $i++) {
				$_kode   = $kode[$i];
				$_qty    = $qty[$i];
				$_jumlah = str_replace(',', '', $jumlah[$i]);
				$_harga  = str_replace(',', '', $harga[$i]);


				$datad = array(
					'kodetarif' => $kodetarif,
					'kodeobat'  => $_kode,
					'qty'       => $qty[$i],
					'satuan'    => $sat[$i],
					'harga'     => $_harga,
					'totalharga'  => $_jumlah,
				);

				if ($_kode != "") {
					$this->db->insert('tbl_masterbhp', $datad);
				}
			}
		} else {
			header('location:' . base_url());
		}
	}

	public function ajax_edit($id)
	{
		$data = $this->M_tarif->get_by_id($id);
		echo json_encode($data);
	}

	public function simpan_tarif(){
		$cek = $this->session->userdata('username');
		$unit = $this->input->post('cabang');
		if (!empty($cek)) {
			$kode = urut_tarif("URUT_TARIF", 13);
			$poli = $this->input->post('poli');
			$tindakan = $this->input->post('tindakan');
			$akunpendapatan = $this->input->post('akunpendapatan');
			$tidakaktif = $this->input->get('tidakaktif');
			$data = [
				'kodetarif' => $kode,
				'tindakan' => $tindakan,
				'kodepos' => $poli,
				'accountno' => $akunpendapatan,
				'tidakaktif' => $tidakaktif,
			];
			$this->db->insert('tbl_tarifh', $data);
			echo json_encode(['status' => 1, 'kodetarif' => $kode]);
		} else {
			header('location:' . base_url());
		}
	}

	public function simpan_tarif_detail(){
		$kodetarif = $this->input->get('kodetarif');
		$cabang = $this->input->get('cabang');
		$keltarif = $this->input->get('keltarif');
		$jasars = $this->input->get('jasars');
		$jasadr = $this->input->get('jasadr');
		$jasaperawat = $this->input->get('jasaperawat');
		$bhp = $this->input->get('bhp');
		$total = $this->input->get('total');
		$data = [
			'koders' => $cabang,
			'cust_id' => $keltarif,
			'kodetarif' => $kodetarif,
			'tarifrspoli' => $jasars,
			'tarifdrpoli' => $jasadr,
			'feemedispoli' => $jasaperawat,
			'obatpoli' => $bhp,
		];
		$this->db->insert('tbl_tarif', $data);
		echo json_encode($data);
	}

	public function simpan_tarif_barang(){
		$kodetarif = $this->input->get('kodetarif');
		$kodebarang = $this->input->get('kodebarang');
		$qty = $this->input->get('qty');
		$satuan = $this->input->get('satuan');
		$harga = $this->input->get('harga');
		$jumlah = $this->input->get('jumlah');
		$data = [
			'kodetarif' => $kodetarif,
			'kodeobat' => $kodebarang,
			'satuan' => $satuan,
			'qty' => $qty,
			'harga' => $harga,
			'totalharga' => $jumlah,
		];
		$this->db->insert("tbl_masterbhp", $data);
		echo json_encode($data);
	}

	public function ubah_tarif(){
		$cek = $this->session->userdata('username');
		$unit = $this->input->post('cabang');
		if (!empty($cek)) {
			$kode = $this->input->post('kode');
			// hapus data lama
			$this->db->where("kodetarif", $kode);
			$this->db->delete("tbl_tarifh");

			$this->db->where("kodetarif", $kode);
			$this->db->delete("tbl_tarif");

			$this->db->where("kodetarif", $kode);
			$this->db->delete("tbl_masterbhp");

			$poli = $this->input->post('poli');
			$tindakan = $this->input->post('tindakan');
			$akunpendapatan = $this->input->post('akunpendapatan');
			$tidakaktif = $this->input->get('tidakaktif');
			$data = [
				'kodetarif' => $kode,
				'tindakan' => $tindakan,
				'kodepos' => $poli,
				'accountno' => $akunpendapatan,
				'tidakaktif' => $tidakaktif,
			];

			// insert data baru
			$this->db->insert('tbl_tarifh', $data);
			echo json_encode(['status' => 1, 'kodetarif' => $kode]);
		} else {
			header('location:' . base_url());
		}
	}

	public function ubah_tarif_detail(){
		$kodetarif = $this->input->get('lupkodetarif');
		$cabang = $this->input->get('lupcabang');
		$keltarif = $this->input->get('lupkeltarif');
		$jasars = $this->input->get('lupjasars');
		$jasadr = $this->input->get('lupjasadr');
		$jasaperawat = $this->input->get('lupjasaperawat');
		$bhp = $this->input->get('lupbhp');
		$total = $this->input->get('luptotal');
		$data = [
			'koders' => $cabang,
			'cust_id' => $keltarif,
			'kodetarif' => $kodetarif,
			'tarifrspoli' => $jasars,
			'tarifdrpoli' => $jasadr,
			'feemedispoli' => $jasaperawat,
			'obatpoli' => $bhp,
		];
		// hapus data lama
		$this->db->where("kodetarif", $kodetarif);
		$this->db->delete("tbl_tarif");

		// insert data baru
		$this->db->insert('tbl_tarif', $data);
		// echo json_encode($data);
	}

	public function ubah_tarif_barang(){
		$kodetarif = $this->input->get('lupkodetarif');
		$kodebarang = $this->input->get('lupkodebarang');
		$qty = $this->input->get('lupqty');
		$satuan = $this->input->get('lupsatuan');
		$harga = $this->input->get('lupharga');
		$jumlah = $this->input->get('lupjumlah');
		$data = [
			'kodetarif' => $kodetarif,
			'kodeobat' => $kodebarang,
			'satuan' => $satuan,
			'qty' => $qty,
			'harga' => $harga,
			'totalharga' => $jumlah,
		];
		// hapus data lama
		$this->db->where("kodetarif", $kodetarif);
		$this->db->delete("tbl_masterbhp");

		// insert data baru
		$this->db->insert("tbl_masterbhp", $data);
		// echo json_encode($data);
	}

	public function get_data_master(){
		$id       = $this->input->get('id');
		$header   = $this->db->get_where('tbl_tarifh', ['id' => $id])->row();
		$detail   = $this->db->query("SELECT t.*, (SELECT namars FROM tbl_namers WHERE koders = t.koders) as namacabang, (SELECT cust_nama FROM tbl_penjamin WHERE cust_id=t.cust_id) as keltarif FROM tbl_tarif t WHERE kodetarif = '$header->kodetarif'")->result();

		$jum_detail = $this->db->query("SELECT t.* FROM tbl_tarif t WHERE kodetarif = '$header->kodetarif'")->num_rows();

		$barang = $this->db->query("SELECT m.*, (SELECT namabarang FROM tbl_logbarang WHERE kodebarang = m.kodeobat) AS namabarang FROM tbl_masterbhp m WHERE m.kodetarif = '$header->kodetarif'")->result();
		
		echo json_encode([$header, $detail, $barang, $jum_detail]);
	}

	public function ajax_add()
	{
		$unit = $this->session->userdata('unit');
		$this->_validate();
		$kodetarifx = urut_tarif("URUT_TARIF", 13);
		$data = array(
			'kodetarif' => $kodetarifx,
			'kodepos' => $this->input->post('poli'),
			'tindakan' => $this->input->post('nama'),
			'accountno' => $this->input->post('akunpendapatan'),
			'tidakaktif' => $this->input->post('tidakaktif') ?? 0,
		);

		// Tambah Detail Tarif 
		if ($this->input->post('cabang')) {
			$kodetarif   = $kodetarifx;
			$cabang      = $this->input->post('cabang');
			$keltarif    = $this->input->post('keltarif');
			$jasars      = $this->input->post('jasars');
			$jasadr      = $this->input->post('jasadr');
			$jasaperawat = $this->input->post('jasaperawat');
			$bhp         = $this->input->post('bhp');
			$total       = $this->input->post('total');

			$jumdatatarif  = count($cabang);

			for ($i = 0; $i <= $jumdatatarif - 1; $i++) {
				$_cabang        = $cabang[$i];
				$_keltarif      = $keltarif[$i];
				$_jasars        = $jasars[$i];
				$_jasadr        = $jasadr[$i];
				$_jasaperawat   = $jasaperawat[$i];
				$_bhp           = $bhp[$i];
				$_total         = str_replace(',', '', $total[$i]);

				$datatarif = array(
					'kodetarif' => $kodetarif,
					'koders'  => $_cabang,
					'cust_id'       => $_keltarif,
					'tarifrspoli'    => $_jasars,
					'tarifdrpoli'     => $_jasadr,
					'feemedispoli'  => $_jasaperawat,
					'bhppoli'  => $_bhp,
					'cost' => $_total,
				);
				$this->db->insert('tbl_tarif', $datatarif);
			}
		}

		// Tambah Cost dan BHP
		if ($this->input->post('kodeobat')) {
			$kodetarif = $kodetarifx;
			$kode  = $this->input->post('kodeobat');
			$qty   = $this->input->post('qty');
			$sat   = $this->input->post('sat');
			$harga = $this->input->post('harga');
			$jumlah = $this->input->post('jumlah');

			$jumdatabhp  = count($kode);

			for ($i = 0; $i <= $jumdatabhp - 1; $i++) {
				$_kode   = $kode[$i];
				$_qty    = $qty[$i];
				$_jumlah = str_replace(',', '', $jumlah[$i]);
				$_harga  = str_replace(',', '', $harga[$i]);

				$databhp = array(
					'kodetarif' => $kodetarif,
					'kodeobat'  => $_kode,
					'qty'       => $qty[$i],
					'satuan'    => $sat[$i],
					'harga'     => $_harga,
					'totalharga'  => $_jumlah,
				);
				$this->db->insert('tbl_masterbhp', $databhp);
			}
		}

		$insert = $this->M_tarif->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function delete_tarif($id)
	{
		$result = $this->db->where('id', $id)->delete('tbl_tarif');
		echo json_encode((object) ['status' => $result]);
	}

	public function delete_bhp($id)
	{
		$result = $this->db->where('id', $id)->delete('tbl_masterbhp');
		echo json_encode((object) ['status' => $result]);
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
			'kodetarif' => $this->input->post('kode'),
			'kodepos' => $this->input->post('poli'),
			'tindakan' => $this->input->post('nama'),
			'accountno' => $this->input->post('akunpendapatan'),
			'tidakaktif' => $this->input->post('tidakaktif') ?? 0,
		);

		// Tambah Detail Tarif 
		if ($this->input->post('cabang')) {
			$idtarif = $this->input->post('idtarif');
			$kodetarif = $this->input->post('kode');
			$cabang  = $this->input->post('cabang');
			$keltarif   = $this->input->post('keltarif');
			$jasars   = $this->input->post('jasars');
			$jasadr = $this->input->post('jasadr');
			$jasaperawat = $this->input->post('jasaperawat');
			$bhp = $this->input->post('bhp');
			$total = $this->input->post('total');

			$jumdatatarif  = count($cabang);

			for ($i = 0; $i <= $jumdatatarif - 1; $i++) {
				$_idtarif = $idtarif[$i];
				$_cabang  = $cabang[$i];
				$_keltarif   = $keltarif[$i];
				$_jasars   = str_replace(',', '', $jasars[$i]);
				$_jasadr = str_replace(',', '', $jasadr[$i]);
				$_jasaperawat = str_replace(',', '', $jasaperawat[$i]);
				$_bhp = str_replace(',', '', $bhp[$i]);
				$_total = str_replace(',', '', $total[$i]);

				$datatarif = array(
					'koders'		=> $_cabang,
					'kodetarif'		=> $kodetarif,
					'cust_id'		=> $_keltarif,
					'tarifrspoli'	=> $_jasars,
					'tarifdrpoli'	=> $_jasadr,
					'feemedispoli'	=> $_jasaperawat,
					'bhppoli'		=> $_bhp,
					'cost'			=> $_total,
				);
				if ($_cabang != "") {
					if ($_idtarif > 0) {
						$this->db->where('id', $_idtarif)->update('tbl_tarif', $datatarif);
					} else {
						$this->db->insert('tbl_tarif', $datatarif);
					}
				}
			}
		}


		// Tambah Cost dan BHP
		if ($this->input->post('kodeobat')) {
			$idbhp = $this->input->post('idbhp');
			$kodetarif = $this->input->post('kode');
			$kode  = $this->input->post('kodeobat');
			$qty   = $this->input->post('qty');
			$sat   = $this->input->post('sat');
			$harga = $this->input->post('harga');
			$jumlah = $this->input->post('jumlah');

			$jumdatabhp  = count($kode);

			for ($i = 0; $i <= $jumdatabhp - 1; $i++) {
				$_idbhp = $idbhp[$i];
				$_kode   = $kode[$i];
				$_qty    = $qty[$i];
				$_jumlah = str_replace(',', '', $jumlah[$i]);
				$_harga  = str_replace(',', '', $harga[$i]);

				$databhp = array(
					'kodetarif' => $kodetarif,
					'kodeobat'  => $_kode,
					'qty'       => $_qty,
					'satuan'    => $sat[$i],
					'harga'     => $_harga,
					'totalharga'  => $_jumlah,
				);
				if ($_kode != "") {
					if ($_idbhp > 0) {
						$this->db->where('id', $_idbhp)->update('tbl_masterbhp', $databhp);
					} else {
						$this->db->insert('tbl_masterbhp', $databhp);
					}
				}
			}
		}

		$this->M_tarif->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id,$kdtr)
	{
		
		$cek    = $this->db->query(" SELECT*FROM tbl_dpoli where kodetarif='$kdtr' ")->num_rows();

		if($cek>0){
				
			echo json_encode(array("status" => 2));
			
		}else{

			$asc   = $this->M_tarif->delete_by_id($id);
			$asc1  = $this->db->query("DELETE FROM tbl_tarif where kodetarif='$kdtr'");
			$asc2  = $this->db->query("DELETE FROM tbl_masterbhp where kodetarif='$kdtr'");

			if($asc){
						
				echo json_encode(array("status" => 1));
			}else{
				echo json_encode(array("status" => 3));
				
			}

		}
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

		// if ($this->input->post('kode') == '') {
		// 	$data['inputerror'][] = 'kode';
		// 	$data['error_string'][] = 'Kode harus diisi';
		// 	$data['status'] = FALSE;
		// }

		if ($this->input->post('nama') == '') {
			$data['inputerror'][] = 'nama';
			$data['error_string'][] = 'Nama masih kosong';
			$data['status'] = FALSE;
		}

		if ($this->input->post('akunpendapatan') == '') {
			$data['inputerror'][] = 'akunpendapatan';
			$data['error_string'][] = 'Akun pendapatan masih kosong';
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
		if (!empty($cek)) {
			$page = $this->uri->segment(3);
			$limit = $this->config->item('limit_data');
			$d['unit'] = '';
			$d['master'] = $this->db->get("ms_unit")->result();
			$d['nama_usaha'] = $this->config->item('nama_perusahaan');
			$d['alamat'] = $this->config->item('alamat_perusahaan');
			$d['motto'] = $this->config->item('motto');

			$this->load->view('master/unit/v_master_unit_prn', $d);
		} else {

			header('location:' . base_url());
		}
	}

	public function export()
	{
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$page = $this->uri->segment(3);
			$limit = $this->config->item('limit_data');
			$d['master'] = $this->db->get("ms_unit");
			$d['nama_usaha'] = $this->config->item('nama_perusahaan');
			$d['alamat'] = $this->config->item('alamat_perusahaan');
			$d['motto'] = $this->config->item('motto');

			$this->load->view('master/unit/v_master_unit_exp', $d);
		} else {

			header('location:' . base_url());
		}
	}
}

/* End of file master_unit.php */
/* Location: ./application/controllers/master_unit.php */