<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lab extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->session->set_userdata('menuapp', '2000');
		$this->session->set_userdata('submenuapp', '2700');
		$this->load->model(array("M_bedah", "M_pasien_global", "M_cetak", "M_lab", "M_barang", "M_alkes_transaksi"));
		$this->load->helper(array("rsreport"));
	}

	public function index(){
		if(isset($_GET["filterdate"])){
			$extract	= explode("~", $_GET["filterdate"]);

			$date_extarct	= (object) [
				"start"	=> $extract[1],
				"end"	=> $extract[2]
			];

			if($extract[0] == "1"){
				$list_eorder	= $this->M_lab->get_list_eorder($date_extarct)->result();
				$list_order	= $this->M_lab->get_list_order()->result();
			} else
			if($extract[0] == "2"){
				$list_eorder	= $this->M_lab->get_list_eorder()->result();
				$list_order	= $this->M_lab->get_list_order($date_extarct)->result();
			} else {
				header("Location:/Lab/");
			}
		} else {
			$list_eorder	= $this->M_lab->get_list_eorder()->result();
			$list_order	= $this->M_lab->get_list_order()->result();
		}

		$total_eorder	= $this->M_lab->get_list_eorder()->num_rows();
		$total_order	= $this->M_lab->get_list_order()->num_rows();

		$data = [
			'title'				=> 'Labolatorium',
			'menu'				=> 'Labolatorium',
			'order'				=> $list_order,
			'orderUnit'			=> $list_eorder,
			'total_eorder'		=> $total_eorder,
			'total_order'		=> $total_order,
		];

		$this->load->view('Lab/index', $data);
	}

	public function getDataPemeriksaan(){
		// $columns = array('nolaborat', 'noreg');
		// $queryData = "SELECT tbl_hlab.*,tbl_dokter.nadokter,tbl_tarifh.tindakan FROM tbl_hlab
		// join tbl_dokter on tbl_dokter.kodokter =tbl_hlab.drpengirim
		// left join tbl_dlab on tbl_dlab.nolaborat =tbl_hlab.nolaborat
		// left join tbl_tarifh on tbl_tarifh.kodetarif =tbl_dlab.kodetarif WHERE";
		$queryData = "SELECT tbl_hlab.*,tbl_dokter.nadokter FROM tbl_hlab
		join tbl_dokter on tbl_dokter.kodokter =tbl_hlab.drpengirim WHERE";
		if ($_POST["is_date_search"] == "yes") {
			$dateNowStart = $_POST["start_date"] . ' 00:00:00';
			$dateNowEns = $_POST["end_date"] . ' 23:59:59';
			$queryData .= ' tgllab >= "' . $dateNowStart . '" and tgllab  <= "' . $dateNowEns . '" AND ';
		} else {
			$dateNowStart = date('Y-m-d') . ' 00:00:00';
			$dateNowEns = date('Y-m-d') . ' 23:59:59';
			$queryData .= ' tgllab >= "' . $dateNowStart . '" and tgllab  <= "' . $dateNowEns . '" AND ';
		}

		if (isset($_POST["search"]["value"])) {
			$queryData .= '
			(tbl_hlab.nolaborat LIKE "%' . $_POST["search"]["value"] . '%"
			OR noreg LIKE "%' . $_POST["search"]["value"] . '%"
			OR rekmed LIKE "%' . $_POST["search"]["value"] . '%"
			OR namapas LIKE "%' . $_POST["search"]["value"] . '%")
			';
		}

		$queryData.= 'GROUP BY tbl_hlab.nolaborat';

		if (isset($_POST["order"])) {
			// $queryData .= ' ORDER BY ' . $columns[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . '';
		} else {
			$queryData .= ' ORDER BY tbl_hlab.id DESC ';
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
			$sub_array[] = '<a href="' . base_url() . 'lab/getData/' . $value->id . '" class="btn green btn-sm">Edit</a>';
			$sub_array[] = '<button class="btn green btn-sm">Isi</button> <button class="btn green btn-sm">Serahkan</button>';
			$sub_array[] = $value->nolaborat;
			$sub_array[] = $value->noreg;
			$sub_array[] = substr($value->tgllab, 0, 10) . ' ' . $value->jam;
			$sub_array[] = $value->rekmed;
			$sub_array[] = $value->namapas;
			$dataBilling = $this->db->query("SELECT tbl_dlab.*,daftar_tarif_nonbedah.tindakan,daftar_tarif_nonbedah.koders from tbl_dlab
			join daftar_tarif_nonbedah on daftar_tarif_nonbedah.kodetarif = tbl_dlab.kodetarif
			where nolaborat='$value->nolaborat' and daftar_tarif_nonbedah.koders ='$unit'")->result();
			$sub_array[] = $this->listArr($dataBilling);
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

	function listArr($arr){
		$html = '<ul>';
		foreach ($arr as $item) {
				$html .= '<li>' . $item->tindakan . '</li>';
		}
		$html .= '</ul>';
		return $html;
	}

	function count_all($starDate, $endDate){
		$this->db->from("tbl_hlab");
		$this->db->where('tgllab >=', $starDate);
		$this->db->where('tgllab <=', $endDate);
		return $this->db->count_all_results();
	}

	function number_filter_row($queryData){
		$query = $this->db->query($queryData);
		return $query->num_rows();
	}

	public function addDataPemeriksaan($nolab = ""){
		$unit			= $this->session->userdata("unit");
		$list_reg		= $this->M_pasien_global->list();

		// TINDAKAN LIST
		$query_tindakan	= $this->db->query("SELECT CONCAT('[ ', kodetarif ,' ] - [ ', tindakan ,' ]') AS text,
		kodetarif AS kodeid
		FROM daftar_tarif_nonbedah
		WHERE kodepos = 'LABOR' 
		AND koders = '$unit' 
		ORDER BY tindakan ASC")->result();

		// HEADER
		$query_header	= $this->db->query("SELECT * FROM tbl_hlab WHERE nolaborat = '$nolab'");
		$query_dhasil	= $this->db->query("SELECT * FROM tbl_dhasillabnew WHERE nolaborat = '$nolab'");
		$query_catatan	= $this->db->query("SELECT * FROM tbl_hlabnotes WHERE nolaborat = '$nolab'");
		$query_file		= $this->db->query("SELECT * FROM tbl_dhasilfile WHERE nolaborat = '$nolab'");

		if($query_header->num_rows() == 0){
			$data_header	= "";
			$status		= "save";
			$title		= "Laboratorium";
			$menu		= "Tambah Pemeriksaan";
			$data_hasil	= "";
			$data_notes	= "";
			$data_files	= "";
		} else {
			$data_header	= $query_header->row();
			$status		= "update";
			$title		= "Laboratorium";
			$menu		= "Edit Pemeriksaan";
			$data_hasil	= $query_dhasil->result();
			$data_notes	= $query_catatan->row();
			$data_files	= $query_file->result();
		}

		$data = [
			'title'				=> $title,
			'menu'				=> $menu,
			'kodeNolaborat'		=> $this->M_lab->generatekode(),
			'dataDokter'		=> $this->M_lab->dataDokter(),
			'petugas'			=> $this->M_lab->dataPetugas(),
			'listreg'			=> $list_reg,
			'listtindakan'		=> $query_tindakan,
			'status'			=> $status,
			'data_header'		=> $data_header,
			'data_hasil'		=> $data_hasil,
			'data_notes'		=> $data_notes,
			'data_file'			=> $data_files
		];

		$this->load->view('Lab/create', $data);
	}

	public function ___simpanDataPemeriksaan(){
		$replace_umur	= str_replace(array( " Tahun", " Bulan", " Hari"), array("","",""), $this->input->post("umur"));
		$explode_umur	= explode(" ", $replace_umur);

		$fieldData = array(
			'nolaborat' => $this->input->post('nolaborat', TRUE),
			'tgllab' => $this->input->post('tgllab', TRUE),
			'noreg' => $this->input->post('noreg', TRUE),
			'namapas' => $this->input->post('namapas', TRUE),
			'rekmed' => $this->input->post('rekmed', TRUE),
			'tgllahir' => $this->input->post('tgllahir', TRUE),
			'umurth' => $explode_umur[0],
			'umurbl' => $explode_umur[1],
			'umurhr' => $explode_umur[2],
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
		$this->M_lab->insert($fieldData);
		$id = $this->db->insert_id();
		redirect('lab/getData/' . $id);
	}

	private function preg_grep_keys($pattern, $input, $flags = 0) {
		return array_intersect_key($input, array_flip(preg_grep($pattern, array_keys($input), $flags)));
	}

	public function updateDataPemeriksaan(){
		//update data pemeriksaan
		// $fieldData = array(
		// 	'nolaborat' => $this->input->post('nolaborat', TRUE),
		// 	'tgllab' => $this->input->post('tgllab', TRUE),
		// 	'noreg' => $this->input->post('noreg', TRUE),
		// 	'namapas' => $this->input->post('namapas', TRUE),
		// 	'rekmed' => $this->input->post('rekmed', TRUE),
		// 	'tgllahir' => $this->input->post('tgllahir', TRUE),
		// 	'umurth' => $this->input->post('umurth', TRUE),
		// 	'umurbl' => $this->input->post('umurbl', TRUE),
		// 	'umurhr' => $this->input->post('umurhr', TRUE),
		// 	'jkel' => $this->input->post('jkel', TRUE),
		// 	'orderno' => $this->input->post('orderno', TRUE),
		// 	'jpas' => $this->input->post('jpas', TRUE),
		// 	'jenisperiksa' => $this->input->post('jenisperiksa', TRUE),
		// 	'rujuk' => $this->input->post('rujuk', TRUE),
		// 	'diagnosa' => $this->input->post('diagnosa', TRUE),
		// 	'drperiksa' => $this->input->post('drperiksa', TRUE),
		// 	'drpengirim' => $this->input->post('drpengirim', TRUE),
		// 	'kodepetugas' => $this->input->post('kodepetugas', TRUE),
		// 	// 'username' => $this->session->userdata('username'),
		// 	'jam' => date('H:i:s'),
		// 	'editby' => $this->session->userdata('username'),
		// 	'tgledit' => date('Y-m-d'),
		// );
		// $this->M_lab->update($this->input->post('id'), $fieldData);

		$rilis = (  $this->input->post("check_final_oleh") === 'on' ) ? 1 : 0;
		$nolaborat = $this->input->post('nolaborat');

		$fieldHlabData= array(
			"tglsampel" => $this->input->post('tanggal_sampel_diambil')." 00:00:00",
			"jamsampel" => $this->input->post( "jam_sampel_diambil"),
			"tglselesai" => $this->input->post("tanggal_selesai_periksa")." 00:00:00",
			"jamselesai" => $this->input->post("jam_selesai_periksa"),
			"sampeloleh" => $this->input->post("oleh_petugas"),
			"rilis" =>$rilis,
			"kodepemeriksa" => $this->input->post("kode_pemeriksaan"),
			"nolaborat" => $nolaborat
		);

		$hasilc = $this->input->post('hasilc');
		$keterangan = $this->input->post("keterangan");
		$kodeperiksa = $this->input->post("kodeperiksa");
		$kodelab = $this->input->post("kodelab");

		foreach ($hasilc as $i => $data) {
			$payload = [
				'hasilc' => $hasilc[$i],
				'keterangan' => $keterangan[$i],
			];
			$thasilnew = $this->db->where('nolaborat', $nolaborat)
								  ->where('kodeperiksa', $kodeperiksa[$i])
								  ->where('kodelab', $kodelab[$i])
								  ->update('tbl_dhasillabnew', $payload);
		}

		$fieldCatatan = array(
			"catatan" => $this->input->post('catatan_hasil'),
			"nolaborat" => $nolaborat
		);

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

		$this->handleHlabDataHasil( $nolaborat, $fieldHlabData );
		$this->handleCatatanHasil( $nolaborat, $fieldCatatan );

		if (count($file_berkas) > 0) {
			$this->handleBerkasHasil( $nolaborat, $file_berkas );
		}
		$this->session->set_flashdata('success', 'Berhasil Menyimpan data');
		redirect("lab/addDataPemeriksaan/". $nolaborat);
		// redirect('lab/getData/' . $this->input->post('id'));
	}

	private function handleHlabDataHasil( $nolaborat, $data ){
		$cekExists = $this->M_lab->getHlabByNolaborat( $nolaborat )->row();
		if( $cekExists ){
			$this->M_lab->update( $cekExists->id, $data );
		}else{
			$this->M_lab->insert( $data );
		}
	}

	private function handleCatatanHasil( $nolaborat, $data ){
		$cekExists = $this->M_lab->getHlabNotesByNolaborat( $nolaborat )->row();
		if( $cekExists ){
			$this->M_lab->updateHlabNotes( $cekExists->id, $data );
		}else{
			$this->M_lab->insertHlabNotes( $data );
		}
	}

	private function handleBerkasHasil( $nolaborat, $datas ){
		$path = "./uploads/berkas/";
		$config = array(
            'upload_path'   => $path,
            'allowed_types' => 'jpg|gif|png|pdf|csv|jpeg',
            'overwrite'     => 1,
        );

		$this->load->library('upload', $config);

		foreach ($datas as $i => $data) {
			$current_file = $this->db->where('namafile', $data['old_file'])->get('tbl_dhasilfile')->row();

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
				'nolaborat' => $nolaborat,
				'namaFile' => $filename,
				'keteranganfile' => $data['keterangan'],
				'lokasifile' => $path.$filename,
			];

			if (!$current_file) {
				$this->M_lab->insertDhasilFile( $payload );
			} else {
				$this->db->where('id', $current_file->id);
				$this->db->update('tbl_dhasilfile', $payload);
			}
		}
	}

	function hapuslabfile($id,$nolaborat){
		$this->db->where('id', $id);
		$this->db->where('nolaborat', $nolaborat);
		$data = $this->db->get('tbl_dhasilfile')->row();

		unlink($data->lokasifile);

		$this->db->where('id', $id);
		$this->db->where('nolaborat', $nolaborat);
		$this->db->delete('tbl_dhasilfile');

		echo json_encode(['success' => true]);
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

	public function getData($id){
		$data = [
			'title'				=> 'Edit ',
			'menu'				=> 'Edit Pemeriksaan',
			'row'				=> $this->M_lab->get_by_id($id),
			'noReg'				=> $this->M_lab->noReg(),
			'dataDokter'		=> $this->M_lab->dataDokter(),
			'tindakan'			=> $this->M_lab->get_tindakan(),
			'petugas'			=> $this->M_lab->dataPetugas(),
			'barang'			=> $this->M_barang->getAll(),
			// 'alkes_transaksi'   => $this->M_alkes_transaksi->getByNotr(),
		];
		$this->load->view('Lab/edit', $data);
	}

	public function pasien_daftar(){
		$noreg = $this->input->post('noreg');
		$query = $this->db->query("SELECT * from pasien_daftar where noreg='$noreg'")->row();
		echo json_encode($query);
	}

	public function  get_daftar_tarif_nonbedah(){
		$kodetarif = $this->input->post('kodetarif');
		$query = $this->db->query("SELECT * from daftar_tarif_nonbedah where kodetarif='$kodetarif'")->row();
		echo json_encode($query);
	}

	public function  get_pemeriksaan(){
		$nolaborat = $this->input->post('nolaborat');
		$jenis_kelamin = $this->input->post('jenis_kelamin');

		$datas = $this->db->query("
		SELECT tbl_dlab.*, tbl_tarifh.kodetindak, tbl_tarifh.kodetarif , tbl_labmashasil.*
		FROM tbl_dlab
		JOIN tbl_tarifh ON tbl_dlab.kodetarif=tbl_tarifh.kodetarif
		JOIN tbl_labmashasil ON tbl_tarifh.kodetindak=tbl_labmashasil.kodeperiksa
		WHERE tbl_dlab.nolaborat='".$nolaborat."'")->result();

		foreach( $datas as $i => $data ){
			$kodeperiksa = $data->kodeperiksa;
			$kodetindak = $data->kodetindak;
			$kodelab= $data->kodelab;
			$nmperiksa = $data->nmperiksa;
			$normal1 = ( $jenis_kelamin === 1 ) ? $data->nilainormalp1 : $data->nilainormalw1;
			$normal2 = ( $jenis_kelamin === 1 ) ? $data->nilainormalp2 : $data->nilainormalw2;
			$normalc = ( $data->nilainormalc ) ? $data->nilainormalc : $normal1.'-'.$normal2;
			$satuan  = $data->satuan;

			$payload = [
				"nolaborat" => $nolaborat,
				"kodeperiksa" => $data->kodetindak,
				"kodelab" => $kodelab,
				"pemeriksaan" => $nmperiksa,
				"satuan" => $satuan,
				"normal1" => $normal1,
				"normal2" => $normal2,
				"normalc" => $normalc
			];

			$cek_thasillabnew = $this->db->where('nolaborat', $nolaborat)
										->where('kodeperiksa', $kodetindak)
										->where('kodelab', $kodelab)
										->get('tbl_dhasillabnew')->row();
			if( $cek_thasillabnew ){
			 	$this->M_lab->updateDhasillabnew(  $cek_thasillabnew->id, $payload );
			}else{
				$this->M_lab->insertDhasillabnew( $payload );
			}
		}

		$data = $this->M_lab->getDhasillabnewByNolab( $nolaborat )->result();
		$output = [ "success" => true, "data" => $data  ];
		echo json_encode( $output );
	}

	public function saveBilling(){
		$cito = $this->input->post('cito', TRUE);

		if ($cito) {
			$jenis = 1;
		} else {
			$jenis = 0;
		}
		$data = array(
			'nolaborat' => $this->input->post('nolaborat', TRUE),
			'kodetarif' => $this->input->post('tindakan_id', TRUE),
			'qty' => $this->input->post('qty', TRUE),
			'tarifrs' => $this->input->post('tarif_rs', TRUE),
			'tarifdr' => $this->input->post('tarif_dr', TRUE),
			'jenis' => $jenis,
			'cito_rp' => $this->input->post('citorp', TRUE),
			'total_biaya' => $this->input->post('total_biaya', TRUE),
		);
		$this->db->insert('tbl_dlab', $data);
		redirect('lab/getData/' . $this->input->post('id'));
	}

	public function saveBhp(){
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
		redirect('lab/getData/' . $this->input->post('id'));
	}

	public function updateBilling(){
		$cito = $this->input->post('cito', TRUE);

		if ($cito) {
			$jenis = 1;
		} else {
			$jenis = 0;
		}

		$data = array(
			'nolaborat' => $this->input->post('nolaborat', TRUE),
			'kodetarif' => $this->input->post('tindakan_id', TRUE),
			'qty' => $this->input->post('qty', TRUE),
			'tarifrs' => $this->input->post('tarif_rs', TRUE),
			'tarifdr' => $this->input->post('tarif_dr', TRUE),
			'jenis' => $jenis,
			'cito_rp' => $this->input->post('citorp', TRUE),
			'total_biaya' => $this->input->post('total_biaya', TRUE),
		);
		$this->db->where('id',$this->input->post('id_billing'));
		$this->db->update('tbl_dlab', $data);
		redirect('lab/getData/' . $this->input->post('id'));
	}

	public function updateBhp(){


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
		redirect('lab/getData/' . $this->input->post('id_pemeriksaan'));
	}

	public function delDataBilling($id){
		$this->db->where('id', $id);
        $this->db->delete('tbl_dlab');
		redirect($_SERVER['HTTP_REFERER']);
	}

	public function delDataBhp($id){
		$this->db->where('id', $id);
        $this->db->delete('tbl_alkestransaksi');
		redirect($_SERVER['HTTP_REFERER']);
	}

	// Rizki

	public function get_tindakan($kode){
		$unit = $this->session->userdata('unit');

		$data = $this->db->query("SELECT *	FROM daftar_tarif_nonbedah AS a
		WHERE a.koders = '$unit'
		AND a.kodetarif ='$kode'
		AND a.kodepos = 'LABOR'")->row();

		echo json_encode($data);
	}

	public function get_barang($kode){
		$unit = $this->session->userdata('unit');

		$data = $this->db->query("SELECT *	FROM tbl_barang AS a
		WHERE a.kodebarang = '$kode'
		AND a.icgroup = 'BR-4'")->row();

		echo json_encode($data);
	}

	public function simpanDataPemeriksaan($param){
		$unit			= $this->session->userdata("unit");
		$replace_umur	= str_replace(array( " Tahun", " Bulan", " Hari"), array("","",""), $this->input->post("umur"));
		$explode_umur	= explode(" ", $replace_umur);

		$nolaborat		= $this->input->post('nolaborat');
		$jenis_kelamin	= $this->input->post('jkel');
		$orderno		= $this->input->post('orderno');
		$tanggal		= $this->input->post('tgllab');

		$data_header	= array(
			'koders'		=> $unit,
			'nolaborat' 	=> $nolaborat,
			'tgllab' 		=> $tanggal ." ". date("H:i:s"),
			'noreg' 		=> $this->input->post('noreg'),
			'namapas' 		=> $this->input->post('namapas'),
			'rekmed' 		=> $this->input->post('rekmed'),
			'tgllahir' 		=> $this->input->post('tgllahir'),
			'umurth' 		=> $explode_umur[0],
			'umurbl' 		=> $explode_umur[1],
			'umurhr' 		=> $explode_umur[2],
			'jkel' 			=> $this->input->post('jkel'),
			'orderno' 		=> $this->input->post('orderno'),
			'jpas' 			=> $this->input->post('jpas'),
			'jenisperiksa' 	=> $this->input->post('jenisperiksa'),
			'rujuk' 		=> $this->input->post('rujuk'),
			'diagnosa' 		=> $this->input->post('diagnosa'),
			'drperiksa' 	=> $this->input->post('drperiksa'),
			'drpengirim' 	=> $this->input->post('drpengirim'),
			'kodepetugas' 	=> $this->input->post('kodepetugas'),
			'username' 		=> $this->session->userdata('username'),
			'jam' 			=> date('H:i:s'),
		);

		$data_header_up	= array(
			'koders'		=> $unit,
			'nolaborat' 	=> $nolaborat,
			'tgllab' 		=> $tanggal ." ". date("H:i:s"),
			'noreg' 		=> $this->input->post('noreg'),
			'namapas' 		=> $this->input->post('namapas'),
			'rekmed' 		=> $this->input->post('rekmed'),
			'tgllahir' 		=> $this->input->post('tgllahir'),
			'umurth' 		=> $explode_umur[0],
			'umurbl' 		=> $explode_umur[1],
			'umurhr' 		=> $explode_umur[2],
			'jkel' 			=> $this->input->post('jkel'),
			'orderno' 		=> $this->input->post('orderno'),
			'jpas'			=> $this->input->post('jpas'),
			'jenisperiksa' 	=> $this->input->post('jenisperiksa'),
			'rujuk' 		=> $this->input->post('rujuk'),
			'diagnosa' 		=> $this->input->post('diagnosa'),
			'drperiksa' 	=> $this->input->post('drperiksa'),
			'drpengirim' 	=> $this->input->post('drpengirim'),
			'kodepetugas' 	=> $this->input->post('kodepetugas'),
			// 'jam' => date('H:i:s'),
			'tglselesai'	=> $this->input->post('tglselesai'),
			'jamselesai'	=> $this->input->post('jamselesai'),
			'tglsampel'		=> $this->input->post('tglsampel'),
			'jamsampel'		=> $this->input->post('jamsampel'),
			'editby' 		=> $this->session->userdata('username'),
			'tgledit' 		=> date('Y-m-d'),
			"sampeloleh" 	=> $this->input->post("sampeloleh"),
			"kodepemeriksa" => $this->input->post("kodepemeriksa"),
			"rilis" 		=> ($this->input->post("rilis") === 'on')? 1 : 0
		);

		$data_catatan	= array(
			"nolaborat"		=> $nolaborat,
			"catatan"		=> $this->input->post("catatan"),
			"tglselesai"	=> $this->input->post("tglselesai"),
			"jamselesai"	=> $this->input->post("jamselesai")
		);

		$data_catatan_up	= array(
			"catatan"		=> $this->input->post("catatan"),
			"tglselesai"	=> $this->input->post("tglselesai"),
			"jamselesai"	=> $this->input->post("jamselesai")
		);

		// BILLING
		$billing_tindakan	= $this->input->post("billing_tindakan");
		$billing_qty		= $this->input->post("billing_qty");
		$billing_cito		= $this->input->post("billing_cito");
		$billing_tarifrs	= str_replace(",", "", $this->input->post("billing_tarifrs"));
		$billing_tarifdr	= str_replace(",", "", $this->input->post("billing_tarifdr"));
		$billing_citorp		= str_replace(",", "", $this->input->post("billing_citorp"));
		$billing_tarifrp	= str_replace(",", "", $this->input->post("billing_tarifrp"));
		$billing_totalbiaya	= str_replace(",", "", $this->input->post("billing_totalbiaya"));

		if(!empty($billing_tindakan)){
			$this->db->query("DELETE FROM tbl_dlab WHERE nolaborat = '$nolaborat'");

			foreach($billing_tindakan as $btkey => $btval){
				$data_insert	= array(
					"nolaborat"		=> $nolaborat,
					"kodetarif"		=> $btval,
					"qty"			=> $billing_qty[$btkey],
					"jenis"			=> $billing_cito[$btkey],
					"tarifrs"		=> $billing_tarifrs[$btkey],
					"tarifdr"		=> $billing_tarifdr[$btkey],
					"citorp"		=> $billing_citorp[$btkey],
					"tarifrp"		=> $billing_tarifrp[$btkey],
					"totalrp"		=> $billing_totalbiaya[$btkey],
				);

				$this->db->insert("tbl_dlab", $data_insert);
			}
		}

		// BHP
		$bhp_barang			= $this->input->post("bhp_barang");
		$bhp_qty			= $this->input->post("bhp_qty");
		$bhp_satuan			= $this->input->post("bhp_satuan");
		$bhp_harga			= str_replace(",", "", $this->input->post("bhp_harga"));
		$bhp_total			= str_replace(",", "", $this->input->post("bhp_total"));
		$bhp_gudang			= $this->input->post("bhp_gudang");
		$bhp_bill			= $this->input->post("bhp_bill");

		if(!empty($bhp_barang)){
			$this->db->query("DELETE FROM tbl_alkestransaksi WHERE notr = '$nolaborat'");

			foreach($bhp_barang as $bbkey => $bbval){

				$data_insert	= array(
					"koders"		=> $unit,
					"notr"			=> $nolaborat,
					"kodeobat"		=> $bbval,
					"qty"			=> $bhp_qty[$bbkey],
					"satuan"		=> $bhp_satuan[$bbkey],
					"harga"			=> $bhp_harga[$bbkey],
					"totalharga"    => $bhp_total[$bbkey],
					"tgltransaksi"	=> $tanggal,
					"gudang"		=> $bhp_gudang[$bbkey],
					"dibebankan"	=> $bhp_bill[$bbkey]
				);

				$this->db->insert("tbl_alkestransaksi", $data_insert);
			}
		}

		// HASIL
		// $datas = $this->db->query("SELECT tbl_dlab.*, tbl_tarifh.kodetindak, tbl_tarifh.kodetarif , tbl_labmashasil.*
		// FROM tbl_dlab
		// JOIN tbl_tarifh ON tbl_dlab.kodetarif=tbl_tarifh.kodetarif
		// JOIN tbl_labmashasil ON tbl_tarifh.kodetindak=tbl_labmashasil.kodeperiksa
		// WHERE tbl_dlab.nolaborat='".$nolaborat."'")->result();

		// foreach($datas as $i => $data){
		// 	$kodeperiksa = $data->kodeperiksa;
		// 	$kodetindak = $data->kodetindak;
		// 	$kodelab= $data->kodelab;
		// 	$nmperiksa = $data->nmperiksa;
		// 	$normal1 = ( $jenis_kelamin === 1 ) ? $data->nilainormalp1 : $data->nilainormalw1;
		// 	$normal2 = ( $jenis_kelamin === 1 ) ? $data->nilainormalp2 : $data->nilainormalw2;
		// 	$normalc = ( $data->nilainormalc ) ? $data->nilainormalc : $normal1.'-'.$normal2;
		// 	$satuan  = $data->satuan;

		// 	$payload = [
		// 		"nolaborat" => $nolaborat,
		// 		"kodeperiksa" => $data->kodetindak,
		// 		"kodelab" => $kodelab,
		// 		"pemeriksaan" => $nmperiksa,
		// 		"satuan" => $satuan,
		// 		"normal1" => $normal1,
		// 		"normal2" => $normal2,
		// 		"normalc" => $normalc
		// 	];

		// 	$cek_thasillabnew = $this->db->where('nolaborat', $nolaborat)->where('kodeperiksa', $kodetindak)->where('kodelab', $kodelab)->get('tbl_dhasillabnew')->row();
		// 	if($cek_thasillabnew){
		// 	 	$this->M_lab->updateDhasillabnew(  $cek_thasillabnew->id, $payload );
		// 	} else {
		// 		$this->M_lab->insertDhasillabnew( $payload );
		// 	}
		// }
		// if($param == "save"){
		// 	if(!empty($billing_tindakan)){
		// 		foreach($billing_tindakan as $btkey => $btval){
		// 			$tarifh			= $this->db->query("SELECT * FROM tbl_tarifh WHERE kodetarif = '$btval'")->row();
		// 			$labmashasil	= $this->db->query("SELECT * FROM tbl_labmashasil WHERE kodeperiksa = '$btval'")->row();

		// 			$normal1 		= ($jenis_kelamin == "1")? str_replace(".0000", "", $labmashasil->nilainormalp1) : str_replace(".0000", "", $labmashasil->nilainormalw1);
		// 			$normal2 		= ($jenis_kelamin == "1")? str_replace(".0000", "", $labmashasil->nilainormalp2) : str_replace(".0000", "", $labmashasil->nilainormalw2);

		// 			$data_hasil		= [
		// 				"nolaborat"		=> $nolaborat,
		// 				"kodeperiksa"	=> $labmashasil->kodeperiksa,
		// 				"kodelab"		=> $labmashasil->kodelab,
		// 				"pemeriksaan"	=> $labmashasil->nmperiksa,
		// 				"satuan" 		=> $labmashasil->satuan,
		// 				"normal1" 		=> $normal1,
		// 				"normal2" 		=> $normal2,
		// 				"normalc" 		=> ($jenis_kelamin == "1")? str_replace(".0000", "", $labmashasil->nilainormalc)	: $normal1.'-'.$normal2,
		// 			];

		// 			$this->db->insert("tbl_dhasillabnew", $data_hasil);
		// 		}
		// 	}
		// } else {
		// 	if(!empty($billing_tindakan)){
		// 		$this->db->delete("tbl_dhasillabnew", array("nolaborat" => $nolaborat));

		// 		$hasil_c		= $this->input->post("hasil_c");
		// 		$hasil_catatan	= $this->input->post("hasil_catatan");
		// 		foreach($billing_tindakan as $btkey => $btval){
		// 			$tarifh			= $this->db->query("SELECT * FROM tbl_tarifh WHERE kodetarif = '$btval'")->row();
		// 			$labmashasil	= $this->db->query("SELECT * FROM tbl_labmashasil WHERE kodeperiksa = '$btval'")->row();

		// 			$normal1 		= ($jenis_kelamin == "1")? str_replace(".0000", "", $labmashasil->nilainormalp1) : str_replace(".0000", "", $labmashasil->nilainormalw1);
		// 			$normal2 		= ($jenis_kelamin == "1")? str_replace(".0000", "", $labmashasil->nilainormalp2) : str_replace(".0000", "", $labmashasil->nilainormalw2);

		// 			$data_hasil		= [
		// 				"nolaborat"		=> $nolaborat,
		// 				"kodeperiksa"	=> $tarifh->kodetindak,
		// 				"kodelab"		=> $labmashasil->kodelab,
		// 				"pemeriksaan"	=> $labmashasil->nmperiksa,
		// 				"hasilc"		=> $hasil_c[$btkey],
		// 				"satuan" 		=> $labmashasil->satuan,
		// 				"normal1" 		=> $normal1,
		// 				"normal2" 		=> $normal2,
		// 				"normalc" 		=> ($jenis_kelamin == "1")? str_replace(".0000", "", $labmashasil->nilainormalc)	: $normal1.'-'.$normal2,
		// 				"keterangan"	=> $hasil_catatan[$btkey]
		// 			];

		// 			$this->db->insert("tbl_dhasillabnew", $data_hasil);
		// 		}
		// 	}
		// }

		// CATATAN
		if($param == "update"){
			$check_catatan	= $this->db->query("SELECT * FROM tbl_hlabnotes WHERE nolaborat = '$nolaborat'")->num_rows();

			if($check_catatan == 0){
				$this->db->insert("tbl_hlabnotes", $data_catatan);
			} else {
				$this->db->update("tbl_hlabnotes", $data_catatan_up, array("nolaborat" => $nolaborat));
			}
		}

		// FILE
		if($param == "update"){
			$file_key			= $this->input->post("file_key");
			$file_keterangan 	= $this->input->post("file_keterangan");
			$file				= $_FILES["file"]["name"];

			if($file != ""){
				foreach($file_key as $fkey => $fval){
					$file_allowed   = array("pdf", "png", "jpg", "jpeg", "webp");
					$file_ext       = explode(".", $file[$fkey]);
					$file_extension = strtolower(end($file_ext));
					$file_tmp       = $_FILES["file"]["tmp_name"][$fkey];

					if(in_array($file_extension, $file_allowed) === true){
						$filename = unique_file("assets/img/laboratorium/", basename($file[$fkey]));
						
						move_uploaded_file($file_tmp, "assets/img/laboratorium/". $filename);

						$data_insert	= [
							"nolaborat"			=> $nolaborat,
							"namafile"			=> $filename,
							"keteranganfile"	=> $file_keterangan[$fkey],
							"lokasifile"		=> "assets/img/laboratorium/",
						];

						$query_update = $this->db->insert("tbl_dhasilfile", $data_insert);

						if($query_update){
							$status		= "success";
							$message	= "File berhasil di upload";
						} else {
							$status		= "error";
							$message	= "File gagal diupload";
						}
					} else {
						$status		= "error";
						$message	= "Ekstensi file tidak valid";
					}
				}
			}
		}

		if($param == "save"){
			urut_transaksi("TR_LABORATORIUM", 19);
			if(!empty($orderno)){
				$this->db->query("UPDATE tbl_orderperiksa SET lab = 0, labok = 1 WHERE orderno = '$orderno'");
			}
			$query_laboratorium	= $this->db->insert("tbl_hlab", $data_header);
		} else {
			$query_laboratorium	= $this->db->update("tbl_hlab", $data_header_up, array("nolaborat" => $nolaborat));
		}

		if($query_laboratorium){
			$status		= "success";
			$message	= "Berhasil disimpan";
		} else {
			$status		= "error";
			$message	= "Gagal disimpan";
		}

		echo json_encode(array(
			"status" => $status,
			"nolab" => $nolaborat,
			"message" => $message),
		JSON_UNESCAPED_SLASHES);
	}

	public function get_last_laborat(){
		$unit	= $this->session->userdata("unit");
		echo json_encode(array(
			"nolab" => temp_urut_transaksi("TR_LABORATORIUM", $unit, 19)
		));
	}

	public function delete_files($id){
		if($id == ""){
			$status     = "error";
			$message    = "ID atau No Radiologi tidak ditemukan";
		} else {
			
			$file           = data_master("tbl_dhasilfile", array("id" => $id))->namafile;
			$delete_file    = $this->db->delete("tbl_dhasilfile", array("id" => $id));

			if($delete_file){
				unlink("assets/img/laboratorium/". $file);

				$status     = "success";
				$message    = "File berhasil dihapus";
			} else {
				$status     = "error";
				$message    = "Gagal menghapus file<br />Kesalahan sistem (server)";
			}

		}

		echo json_encode(array(
			"status"    => $status,
			"message"   => $message
		), JSON_UNESCAPED_SLASHES);
	}

	// PRINT

	public function cetak($param){
		
		$chari      = "";
		$unit       = $this->session->userdata("unit");
		$head		= $this->db->query("SELECT * FROM tbl_namers WHERE koders = '$unit'")->row();
		$avatar   	= $this->session->userdata('avatar_cabang');
		$header		= $this->db->query("SELECT * FROM tbl_hlab WHERE nolaborat = '$param'");
		$data		= $header->row();
		$hasil		= $this->db->query("SELECT * FROM tbl_dhasillabnew WHERE nolaborat = '$param'");

		
		$judul		= "HASIL LABORATORIUM ". $data->nolaborat;

		$comp_name	= $head->namars;
		$comp_addr	= $head->alamat;
		$comp_addr2	= $head->alamat2;
		$comp_phone	= $head->phone;
		$comp_wa	= $head->whatsapp;
		$comp_npwp	= $head->npwp;
		$comp_image	= base_url()."assets/img_user/$avatar";

		if($header->num_rows() == 0){
			$this->session->set_flashdata("error", "Data tidak ditemukan<br />Cetakan gagal dibuka");
			redirect(base_url("lab"));
		} else {

			$chari  .= "<style>
				.table {border-collapse:collapse;font-family: Century Gothic;font-size:12px;color:#000;width:100%;margin:auto}
				.bordered {padding:5px;border:1px solid #222}
				.bt {padding-top:5px}
				.bm {padding-bottom:5px}
				.centered {text-align:center;margin:auto}
				.bold {font-weight:bold}
				.subtitle {font-size:12px;padding-bottom:15px !important}
				.title {font-size:16px;margin-top:10px;margin-bottom:20px}
				.separator {border:115px solid #222}
			</style>";

			$chari  .= '<table class="table" align="center">	
				<thead>
					<tr>
						<td rowspan="4">
							<img src="'. $comp_image .'"  width="100" height="70" />
						</td>
						<td>
							<tr><td style="font-size:14px;border-bottom: none;"><b>'. $comp_name .'</b></td></tr>
							<tr><td style="font-size:13px;">'. $comp_addr .'</td></tr>
							<tr><td style="font-size:13px;">Telp : '. $comp_phone .'</td></tr>
						</td>
					</tr> 
				</thead>
			</table>';

			$chari  .= "<hr class=\"separator\">"; 

			$chari	.= "<table class='table'>
				<tbody>
					<tr>
						<td style='width:15%'>Nama Pasien</td>
						<td style='width:35%'>: <b>". $data->namapas ."</b></td>
						<td style='width:15%'>Dokter</td>
						<td style='width:35%'>: <b>". data_master("dokter", array("kodokter" => $data->drpengirim, "koders" => $data->koders, "kopoli" => $data->asal))->nadokter ."</b></td>
					</tr>
					<tr>
						<td style='width:15%'>No Rekam Medis</td>
						<td style='width:35%'>: <b>". $data->rekmed ."</b></td>
						<td style='width:15%'>Umur</td>
						<td style='width:35%'>: <b>". $data->umurth ." Tahun ". $data->umurbl ." Bulan ". $data->umurhr ." Hari</b></td>
					</tr>
					<tr>
						<td style='width:15%'>No Registrasi</td>
						<td style='width:35%'>: <b>". $data->noreg ."</b></td>
						<td style='width:15%'>Asal / Dari</td>
						<td style='width:35%'>: <b>". ($data->asal == "" ? "-" : data_master("tbl_namapos", array("kodepos" => $data->asal))->namapost) ."</b></td>
					</tr>
					<tr>
						<td style='width:15%'>No Lab</td>
						<td style='width:35%'>: <b>". $data->nolaborat ."</b></td>
						<td style='width:15%'>Tgl Transaksi</td>
						<td style='width:35%'>: <b>". date("d/m/Y H:i:s", strtotime($data->tgllab)) ."</b></td>
					</tr>
					<tr>
						<td style='width:15%'>Alamat</td>
						<td style='width:35%'>: <b>". data_master("tbl_pasien", array("rekmed" => $data->rekmed))->alamat .", ". data_master("tbl_pasien", array("rekmed" => $data->rekmed))->alamat2 ."</b></td>
						<td style='width:15%'>Tgl Bhn Diterima</td>
						<td style='width:35%'>: <b>". date("d/m/Y H:i:s", strtotime($data->tglambil)) ."</b></td>
					</tr>
					<tr>
						<td style='width:15%'></td>
						<td style='width:35%'> <b></b></td>
						<td style='width:15%'>Tgl Hasil Selesai</td>
						<td style='width:35%'>: <b>". date("d/m/Y H:i:s", strtotime($data->tglselesai)) ."</b></td>
					</tr>
				</tbody>
			</table>
			<hr class=\"separator\">";

			$chari	.= "<table class='table'>
				<thead>
					<tr style='background:#f5f5f5'>
						<th class='bordered' style='text-align:left'>Pemeriksaan</th>
						<th class='bordered'>Hasil Tes</th>
						<th class='bordered'>Nilai Normal</th>
						<th class='bordered'>Satuan</th>
						<th class='bordered'>Keterangan</th>
					</tr>
				</thead>
				<tbody>";
				if($hasil->num_rows() != 0){
					foreach($hasil->result() as $fh){
						$chari	.= "<tr>
							<td class='bordered'>". $fh->pemeriksaan."</td>
							<td class='bordered'>". $fh->hasilc ."</td>
							<td class='bordered'>". $fh->normalc ."</td>
							<td class='bordered'>". $fh->satuan ."</td>
							<td class='bordered'>". $fh->keterangan ."</td>
						</tr>";
					}
				} else {
					$chari	.= "<tr><td colspan='5' class='bordered centered' style='background:#f5f5f5'>Belum Isi Hasil</td></tr>";
				}
			$chari	.= "</tbody></table>";

			$this->M_cetak->mpdf('P','A4',$judul, $chari, $judul . '.PDF', 0, 0, 10, 2);

		}

	}
}
