<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ro extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		// $this->session->set_userdata('menuapp', '2000');
		// $this->session->set_userdata('submenuapp', '2651');
		$this->load->model(array("M_ro",'M_barang'));
		$this->load->helper(array("app_global", "rsreport"));
		$this->load->library("session");
	}

	public function index()
	{
		$data = [
			'title'				=> 'Radiologi',
			'menu'				=> 'Radiologi',
			'orderUnit'			=> $this->M_ro->get_all(),

		];

		$this->load->view('Ro/index', $data);
	}

	public function getDataPemeriksaan()
	{
		// $columns = array('noradio', 'noreg');
		// $queryData = "SELECT tbl_hradio.*,tbl_dokter.nadokter,tbl_tarifh.tindakan FROM tbl_hradio
		// join tbl_dokter on tbl_dokter.kodokter =tbl_hradio.drpengirim 
		// left join tbl_hlab on tbl_hlab.noradio =tbl_hradio.noradio 
		// left join tbl_tarifh on tbl_tarifh.kodetarif =tbl_hlab.kodetarif WHERE";
		$queryData = "SELECT tbl_hradio.*,tbl_dokter.nadokter FROM tbl_hradio
		join tbl_dokter on tbl_dokter.kodokter =tbl_hradio.drpengirim WHERE";

	
		if ($_POST['is_date_search'] == "yes") {
			$dateNowStart = $_POST["start_date"];
			$dateNowEns = $_POST["end_date"];
			$queryData .= ' tglradio >= "' . $dateNowStart . '" and tglradio  <= "' . $dateNowEns . '" AND ';
		} else {
			$dateNowStart = date('Y-m-d');
			$dateNowEns = date('Y-m-d');
			$queryData .= ' tglradio >= "' . $dateNowStart . '" and tglradio  <= "' . $dateNowEns . '" AND ';
		}

		if (isset($_POST["search"]["value"])) {
			$queryData .= '
			(tbl_hradio.noradio LIKE "%' . $_POST["search"]["value"] . '%" 
			OR noreg LIKE "%' . $_POST["search"]["value"] . '%" 
			OR rekmed LIKE "%' . $_POST["search"]["value"] . '%" 
			OR namapas LIKE "%' . $_POST["search"]["value"] . '%")
			';
		}

		$queryData .= 'GROUP BY tbl_hradio.noradio';

		if (isset($_POST["order"])) {
			// $queryData .= ' ORDER BY ' . $columns[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . '';
		} else {
			$queryData .= ' ORDER BY tbl_hradio.id DESC ';
		}

		$queryData1 = '';

		if ($_POST["length"] != -1) {
			$queryData1 = ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$result = $this->db->query($queryData . $queryData1);
		$unit = $this->session->userdata("unit");
		$data = array();
		foreach ($result->result() as $value) {
			$sub_array = array();
			$sub_array[] = '<a href="' . base_url() . 'ro/getData/' . $value->id . '" class="btn green btn-sm">Edit</a>';
			$sub_array[] = '<button class="btn green btn-sm">Isi</button> <button class="btn green btn-sm">Serahkan</button>';
			$sub_array[] = $value->noradio;
			$sub_array[] = $value->noreg;
			$sub_array[] = substr($value->tglradio, 0, 10) . ' ' . $value->jam;
			$sub_array[] = $value->rekmed;
			$sub_array[] = $value->namapas;
			// $dataBilling = $this->db->query("SELECT tbl_hlab.*,daftar_tarif_nonbedah.tindakan,daftar_tarif_nonbedah.koders from tbl_hlab
			// join daftar_tarif_nonbedah on daftar_tarif_nonbedah.kodetarif = tbl_hlab.kodetarif
			// where noradio='$value->noradio' and daftar_tarif_nonbedah.koders ='$unit'")->result();
			// $sub_array[] = $this->listArr($dataBilling);
			$sub_array[] = $value->nadokter;
			$data[] = $sub_array;
		}

		$output = array(
			"draw"    => intval($_POST["draw"]),
			"recordsTotal"  =>  $this->count_all($dateNowStart, $dateNowEns),
			"recordsFiltered" => $this->number_filter_row($queryData),
			"data"    => $data
		);

		echo json_encode($output);
	}

	function listArr($arr) {
		$html = '<ul>';
		foreach ($arr as $item) {
				$html .= '<li>' . $item->tindakan . '</li>';
		}
		$html .= '</ul>';
		return $html;
	}

	function count_all($starDate, $endDate)
	{
		$this->db->from("tbl_hradio");
		$this->db->where('tglradio >=', $starDate);
		$this->db->where('tglradio <=', $endDate);
		return $this->db->count_all_results();
	}
	function number_filter_row($queryData)
	{
		$query = $this->db->query($queryData);
		return $query->num_rows();
	}


	public function addDataPemeriksaan()
	{
		$data = [
			'title'				=> 'Create',
			'menu'				=> 'Tambah Pemeriksaan',
			'kodenoradio'		=> $this->M_ro->generatekode(),
			'dataDokter'		=> $this->M_ro->dataDokter(),
			'petugas'			=> $this->M_ro->dataPetugas(),
			'noReg'				=> $this->M_ro->noReg(),

		];
	
		$this->load->view('Ro/create', $data);
	}

	public function getData($id)
	{
		$data = [
			'title'				=> 'Edit ',
			'menu'				=> 'Edit Pemeriksaan',
			'row'				=> $this->M_ro->get_by_id($id),
			'noReg'				=> $this->M_ro->noReg(),
			'dataDokter'		=> $this->M_ro->dataDokter(),
			'tindakan'			=> $this->M_ro->get_tindakan(),
			'petugas'			=> $this->M_ro->dataPetugas(),
			'barang'			=> $this->M_barang->getAll(),
		];
		$this->load->view('Ro/edit', $data);
	}

	public function simpanDataPemeriksaan()
	{
		$fieldData = array(
			'noradio' => $this->input->post('noradio', TRUE),
			'tglradio' => $this->input->post('tglradio', TRUE),
			'noreg' => $this->input->post('noreg', TRUE),
			'namapas' => $this->input->post('namapas', TRUE),
			'rekmed' => $this->input->post('rekmed', TRUE),
			'tgllahir' => $this->input->post('tgllahir', TRUE),
			'umurth' => $this->input->post('umurth', TRUE),
			'umurbl' => $this->input->post('umurbl', TRUE),
			'umurhr' => $this->input->post('umurhr', TRUE),
			'jkel' => $this->input->post('jkel', TRUE),
			'orderno' => $this->input->post('orderno', TRUE),
			'jpas' => $this->input->post('jpas', TRUE),
			'jenisperiksa' => $this->input->post('jenisperiksa', TRUE),
			'rujuk' => $this->input->post('rujuk', TRUE),
			'diagnosa' => $this->input->post('diagnosa', TRUE),
			'drperiksa' => $this->input->post('drperiksa', TRUE),
			'drpengirim' => $this->input->post('drpengirim', TRUE),
			'kodepetugas' => $this->input->post('kodepetugas', TRUE),
			'username' => $this->session->userdata('username'),
			'jam' => date('H:i:s'),

		);
		$this->M_ro->insert($fieldData);
		$id = $this->db->insert_id();
		redirect('ro/getData/' . $id);
	}

	public function saveBilling()
	{
		$cito = $this->input->post('cito', TRUE);

		if ($cito) {
			$jenis = 1;
		} else {
			$jenis = 0;
		}
		$data = array(
			'noradio' => $this->input->post('noradio', TRUE),
			'kodetarif' => $this->input->post('tindakan_id', TRUE),
			'qty' => $this->input->post('qty', TRUE),
			'tarifrs' => $this->input->post('tarif_rs', TRUE),
			'tarifdr' => $this->input->post('tarif_dr', TRUE),
			'jenis' => $jenis,
			'cito_rp' => $this->input->post('citorp', TRUE),
			'total_biaya' => $this->input->post('total_biaya', TRUE),
		);
		$this->db->insert('tbl_dradio', $data);
		redirect('ro/getData/' . $this->input->post('id'));
	}

	public function updateBilling()
	{
		$cito = $this->input->post('cito', TRUE);

		if ($cito) {
			$jenis = 1;
		} else {
			$jenis = 0;
		}

		$data = array(
			'noradio' => $this->input->post('noradio', TRUE),
			'kodetarif' => $this->input->post('tindakan_id', TRUE),
			'qty' => $this->input->post('qty', TRUE),
			'tarifrs' => $this->input->post('tarif_rs', TRUE),
			'tarifdr' => $this->input->post('tarif_dr', TRUE),
			'jenis' => $jenis,
			'cito_rp' => $this->input->post('citorp', TRUE),
			'total_biaya' => $this->input->post('total_biaya', TRUE),
		);
		$this->db->where('id',$this->input->post('id_billing'));
		$this->db->update('tbl_dradio', $data);
		redirect('ro/getData/' . $this->input->post('id'));
	}

	public function delDataBilling($id){
		$this->db->where('id', $id);
        $this->db->delete('tbl_dradio');
		redirect($_SERVER['HTTP_REFERER']);
	}

	public function saveBhp()
	{
		$dibebankan = ( $this->input->post('bill', TRUE ) === 'on' ) ? 1 : 0;

		$data = array(
			'notr' => $this->input->post('notr', TRUE),
			'koders' => $this->input->post('koders', TRUE),
			'dibebankan' => $dibebankan,
			'kodeobat' => $this->input->post('kodeobat', TRUE),
			'satuan' => $this->input->post('satuan', TRUE),
			'qty' => $this->input->post('qty', TRUE),
			'harga' => $this->input->post('harga', TRUE ),
			'totalharga' => $this->input->post('totalharga', TRUE),
			'gudang' => $this->input->post('gudang', TRUE),
			'tgltransaksi' => date("Y-m-d H:i:s")
		);

		
		$this->db->insert('tbl_alkestransaksi', $data);
		redirect('ro/getData/' . $this->input->post('id'));
	}

	public function updateBhp()
	{


		$dibebankan = ( $this->input->post('bill', TRUE ) === 'on' ) ? 1 : 0;

		$data = array(
			'notr' => $this->input->post('notr', TRUE),
			'koders' => $this->input->post('koders', TRUE),
			'dibebankan' => $dibebankan,
			'kodeobat' => $this->input->post('kodeobat', TRUE),
			'satuan' => $this->input->post('satuan', TRUE),
			'qty' => $this->input->post('qty', TRUE),
			'harga' => $this->input->post('harga', TRUE ),
			'totalharga' => $this->input->post('totalharga', TRUE),
			'gudang' => $this->input->post('gudang', TRUE),
		);

	
		$this->db->where('id',$this->input->post('id'));
		$this->db->update('tbl_alkestransaksi', $data);
		redirect('ro/getData/' . $this->input->post('id_pemeriksaan'));
	}
	
	public function delDataBhp($id){
		$this->db->where('id', $id);
        $this->db->delete('tbl_alkestransaksi');
		redirect($_SERVER['HTTP_REFERER']);
	}

	public function updateDataPemeriksaan()
	{
		$rilis = (  $this->input->post("dibaca_oleh") === 'on' ) ? 1 : 0;
		$noradio = $this->input->post('noradio');

		//update data pemeriksaan
		$fieldData = array(
			'noradio' => $this->input->post('noradio', TRUE),
			'tglradio' => $this->input->post('tglradio', TRUE),
			'noreg' => $this->input->post('noreg', TRUE),
			'namapas' => $this->input->post('namapas', TRUE),
			'rekmed' => $this->input->post('rekmed', TRUE),
			'tgllahir' => $this->input->post('tgllahir', TRUE),
			'umurth' => $this->input->post('umurth', TRUE),
			'umurbl' => $this->input->post('umurbl', TRUE),
			'umurhr' => $this->input->post('umurhr', TRUE),
			'jkel' => $this->input->post('jkel', TRUE),
			'orderno' => $this->input->post('orderno', TRUE),
			'jpas' => $this->input->post('jpas', TRUE),
			'jenisperiksa' => $this->input->post('jenisperiksa', TRUE),
			'rujuk' => $this->input->post('rujuk', TRUE),
			'diagnosa' => $this->input->post('diagnosa', TRUE),
			'drperiksa' => $this->input->post('drperiksa', TRUE),
			'drpengirim' => $this->input->post('drpengirim', TRUE),
			'kodepetugas' => $this->input->post('kodepetugas', TRUE),
			// 'username' => $this->session->userdata('username'),
			'jam' => date('H:i:s'),
			'useredit' => $this->session->userdata('username'),
			'tgledit' => date('Y-m-d'),
			"tglambil" => $this->input->post('tanggal_foto_diambil')." 00:00:00",
			"jamambil" => $this->input->post( "jam_foto_diambil"),
			"tglselesai" => $this->input->post("tanggal_selesai_periksa")." 00:00:00",
			"jamselesai" => $this->input->post("jam_selesai_periksa"),
			"kodepetugas" => $this->input->post("oleh_petugas"),
			"keluar" =>$rilis,
			"drperiksa" => $this->input->post("kode_pemeriksaan"),
			"noradio" => $noradio
		);
		$this->M_ro->update($this->input->post('id'), $fieldData);

		$fieldCatatan = array(
			"catatan" => $this->input->post('catatan_hasil'),
			"noradio" => $noradio
		);

		$cek_hasil_radio = $this->db->where('noradio', $noradio)->get('tbl_expertise')->row();

		if ($cek_hasil_radio) {
			$this->db->where('noradio', $noradio)->update('tbl_expertise', $fieldCatatan);
		} else {
			$this->db->insert('tbl_expertise', $fieldCatatan);
		}
		
		$keterangan_berkas = $this->preg_grep_keys('/^keterangan_berkas+(?:.+)/m',  $_POST);
	
		$keterangan_berkas_key = array_keys($keterangan_berkas);
		
		$file_berkas = [];

		foreach ($keterangan_berkas_key as $i => $keterangan) {
			$keterangans = explode('-', $keterangan);
			$data = [
				'field_name' => 'file_berkas-'.$keterangans[1],
				'keterangan' => $_POST['keterangan_berkas-'.$keterangans[1]],
				'file' => $_FILES['file_berkas-'.$keterangans[1]],
				'old_file' => $_POST['old_file-'.$keterangans[1]],
			];

			array_push($file_berkas, $data);
		}

		if (count($file_berkas) > 0) {
			$this->handleBerkasHasil( $noradio, $file_berkas );
		}
		$this->session->set_flashdata('success', 'Berhasil Menyimpan data');
		redirect('ro/getData/' . $this->input->post('id'));
	}
	
	private function handleBerkasHasil( $noradio, $datas )
	{
		$path = "./uploads/radiologi/";
		$config = array(
            'upload_path'   => $path,
            'allowed_types' => 'jpg|gif|png|pdf|csv|jpeg',
            'overwrite'     => 1,                       
        );

		$this->load->library('upload', $config);

		foreach ($datas as $i => $data) {
			$current_file = $this->db->where('namafile', $data['old_file'])->get('tbl_dradiofile')->row();

			if ($current_file) {
				unlink($current_file->lokasifile);
			}

			$file = $data['file']['name'];
			$extension = pathinfo($file, PATHINFO_EXTENSION);

			$filename = $this->generateRandomString(10).'.'.$extension;

            $config['file_name'] = $filename;

            $this->upload->initialize($config);

			if ($this->upload->do_upload($data['field_name'])) {
                $this->upload->data();
            } else {
				$error = array('error' => $this->upload->display_errors());
                return false;
            }

			$payload = [
				'noradio' => $noradio,
				'namaFile' => $filename,
				'keteranganfile' => $data['keterangan'],
				'lokasifile' => $path.$filename,
			];

			if (!$current_file) {
				$this->db->insert('tbl_dradiofile', $payload);
			} else {
				$this->db->where('id', $current_file->id);
				$this->db->update('tbl_dradiofile', $payload);
			}
		}
	}

	function hapusRadiofile($id,$noradio){
		$this->db->where('id', $id);
		$this->db->where('noradio', $noradio);
		$data = $this->db->get('tbl_dradiofile')->row();

		unlink($data->lokasifile);

		$this->db->where('id', $id);
		$this->db->where('noradio', $noradio);
		$this->db->delete('tbl_dradiofile');

		echo json_encode(['success' => true]);
	}

	private function preg_grep_keys($pattern, $input, $flags = 0) {
		return array_intersect_key($input, array_flip(preg_grep($pattern, array_keys($input), $flags)));
	}

	private function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}
