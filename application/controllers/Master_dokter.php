<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_dokter extends CI_Controller {

	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_dokter','M_dokter');
		$this->load->model('M_dokter_poli','M_dokter_poli');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '1000');
		$this->session->set_userdata('submenuapp', '1105');
	}

	public function index2()
	{
		$cek = $this->session->userdata('level');				
		if(!empty($cek))
		{
			$data['cabang'] = $this->db->select('koders, namars')->get('tbl_namers')->result();
			$data['unit'] = $this->db->select('kodepos, namapost')->get('tbl_namapos')->result();
			$this->load->view('master/v_master_dokter', $data);
		} else
		{
			header('location:'.base_url());
		}			
	}

	// husain add
	public function index()
	{
		$cek = $this->session->userdata('level');				
		if(!empty($cek))
		{
			$data['cabang'] = $this->db->select('koders, namars')->get('tbl_namers')->result();
			$data['unit'] = $this->db->select('kodepos, namapost')->get('tbl_namapos')->result();
			$data['dokter'] = $this->db->get_where("tbl_dokter", ['jenispegawai' => 1])->result();
			$data['poli'] = $this->db->get("tbl_namapos")->result();
			$data['namers'] = $this->db->get("tbl_namers")->result();
			$this->load->view('master/v_master_dokter2', $data);
		} else {
			header('location:'.base_url());
		}			
	}

	public function get_dokter_poli(){
		$kodokter = $this->input->get('kodokter');
		$data = $this->db->query("SELECT d.*, (SELECT namapost FROM tbl_namapos WHERE kodepos = d.kopoli) as namapost, n.nadokter FROM tbl_drpoli d JOIN tbl_dokter n ON n.kodokter=d.kodokter WHERE d.kodokter = '$kodokter'")->result();
		$jum = $this->db->get_where("tbl_drpoli", ['kodokter' => $kodokter])->num_rows();
		$dokter = $this->db->get_where("tbl_dokter", ['kodokter' => $kodokter])->row();
		if($jum > 0){
			echo json_encode([$data, $dokter]);
		} else {
			echo json_encode(['status' => 0]);
		}
	}

	public function master_poli(){
		$kodepos = $this->input->get('kodepos');
		$data = $this->db->get_where('tbl_namapos', ['kodepos' => $kodepos])->row();
		echo json_encode($data);
	}

	public function tambah_dokter(){
		$cek = $this->session->userdata('username');
		$cabang = $this->session->userdata('unit');
		if (!empty($cek)) {
			$kodokter = urut_transaksi('MASTER_DR',10);
			$namadokter = $this->input->get("namadokter");
			$alamat = $this->input->get("alamat");
			$tglmasuk = $this->input->get("tglmasuk");
			$tglberhenti = $this->input->get("tglberhenti");
			$noidentitas = $this->input->get("noidentitas");
			$sip = $this->input->get("sip");
			$npwp = $this->input->get("npwp");
			$nohp = $this->input->get("nohp");
			$status = $this->input->get("status");
			$data = [
				'koders' => $cabang,
				'kodokter' => $kodokter,
				'nik' => $noidentitas,
				'jenispegawai' => 1,
				'nadokter' => $namadokter,
				'tglmasuk' => $tglmasuk,
				'alamat' => $alamat,
				'hp' => $nohp,
				'status' => $status,
				'nosip' => $sip,
				'npwp' => $npwp,
				'tglberhenti' => $tglberhenti,
			];
			$this->db->insert("tbl_dokter", $data);
			echo json_encode(['status' => 1, 'kodokter' => $kodokter]);
		} else {
			header('location:' . base_url());
		}
	}

	public function update_dokter(){
		$cek = $this->session->userdata('username');
		$cabang = $this->session->userdata('unit');
		if (!empty($cek)) {
			$kodokter = $this->input->get("kodokter");
			$namadokter = $this->input->get("namadokter");
			$alamat = $this->input->get("alamat");
			$tglmasuk = $this->input->get("tglmasuk");
			$tglberhenti = $this->input->get("tglberhenti");
			$noidentitas = $this->input->get("noidentitas");
			$sip = $this->input->get("sip");
			$npwp = $this->input->get("npwp");
			$nohp = $this->input->get("nohp");
			$status = $this->input->get("status");

			$this->db->where("kodokter", $kodokter);
			$this->db->delete("tbl_dokter");

			$this->db->where("kodokter", $kodokter);
			$this->db->delete("tbl_drpoli");

			$this->db->where("kodokter", $kodokter);
			$this->db->delete("tbl_doktercabang");
			$data = [
				'koders' => $cabang,
				'kodokter' => $kodokter,
				'nik' => $noidentitas,
				'jenispegawai' => 1,
				'nadokter' => $namadokter,
				'tglmasuk' => $tglmasuk,
				'alamat' => $alamat,
				'hp' => $nohp,
				'status' => $status,
				'nosip' => $sip,
				'npwp' => $npwp,
				'tglberhenti' => $tglberhenti,
			];
			$this->db->insert("tbl_dokter", $data);
			echo json_encode(['status' => 1, 'kodokter' => $kodokter]);
		} else {
			header('location:' . base_url());
		}
	}

	public function simpan_drpoli(){
		$kopoli = $this->input->get("status_unit");
		$kodokter = $this->input->get("kodokter");
		$data = [
			'kodokter' => $kodokter,
			'kopoli' => $kopoli,
		];
		$this->db->insert("tbl_drpoli", $data);
		echo json_encode($data);
	}

	public function hapus_dlama(){
		$kodokter = $this->input->get("kodokter");
		$this->db->where("kodokter", $kodokter);
		$this->db->delete("tbl_drpoli");
		echo json_encode(['status' => 1]);
	}

	public function update_drpoli(){
		$kopoli = $this->input->get("kopoli");
		$kodokter = $this->input->get("kodokter");
		$data = [
			'kodokter' => $kodokter,
			'kopoli' => $kopoli,
		];
		$this->db->insert("tbl_drpoli", $data);
		echo json_encode(['status' => 1]);
	}

	public function simpan_doktercabang(){
		$koders = $this->input->get("status_lokasi");
		$kodokter = $this->input->get("kodokter");
		$data = [
			'koders' => $koders,
			'kodokter' => $kodokter,
		];
		$this->db->insert("tbl_doktercabang", $data);
		echo json_encode($data);
	}

	public function hapus_dokter(){
		$id = $this->input->get('id');
		$dokter = $this->db->get_where("tbl_dokter", ['id' => $id])->row();
		$this->db->where('kodokter', $dokter->kodokter);
		$this->db->delete('tbl_doktercabang');

		$this->db->where('kodokter', $dokter->kodokter);
		$this->db->delete('tbl_drpoli');

		$this->db->where('id', $id);
		$this->db->delete('tbl_dokter');
		echo json_encode(['status' => 1]);
	}

	public function update_data_dokter(){
		$id = $this->input->get('id');
		$cek = $this->session->userdata('level');				
		if(!empty($cek)){
			$dokter = $this->db->get_where("tbl_dokter", ['id' => $id])->row();
			$kodokter = $dokter->kodokter;
			$drpoli = $this->db->query("SELECT d.*, (SELECT namapost FROM tbl_namapos WHERE kodepos = d.kopoli) AS namapoli FROM tbl_drpoli d WHERE kodokter = '$kodokter'")->result();
			$drcabang = $this->db->query("SELECT d.*, (SELECT namars FROM tbl_namers WHERE koders = d.koders) AS namars FROM tbl_doktercabang d WHERE kodokter = '$kodokter'")->result();
			echo json_encode([$dokter, $drpoli, $drcabang]);
		} else {
			header('location:' . base_url());
		}
	}
	// end husain

	public function hapus_lokasi ()
	{
		return $this->db->delete('tbl_doktercabang', ['kodokter' => $this->input->get('kodokter'), 'koders' => $this->input->get('koders')]);
	}

	public function hapus_unit ()
	{
		return $this->db->delete('tbl_drpoli', ['kodokter' => $this->input->get('kodokter'), 'kopoli' => $this->input->get('kopoli')]);
	}

	public function ajax_list()
	{
		$list = $this->M_dokter->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit) {
			$no++;
			$row = array();
			$row[] = $unit->koders;
			$row[] = $unit->kodokter;
			$row[] = '<a href="javascript:void(0)" onclick="detil_dokter('."'".$unit->kodokter."'".",'".$unit->nadokter."'".')">'.$unit->nadokter.'</a>';
			
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$unit->id."'".')"><i class="glyphicon glyphicon-edit"></i> </a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$unit->id."'".')"><i class="glyphicon glyphicon-trash"></i> </a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_dokter->count_all(),
						"recordsFiltered" => $this->M_dokter->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

    public function poli_list( $dokter= "" )
	{
		$list = $this->M_dokter_poli->get_datatables( $dokter );
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit) {
			$datapoli = data_master('tbl_namapos',array('kodepos' => $unit->kopoli));
			$no++;
			$row = array();
			$row[] = $unit->kodokter;
			$row[] = $unit->kopoli;
			$row[] = $datapoli->namapost;
			$row[] = '
					 <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data_poli('."'".$unit->id."'".')"><i class="glyphicon glyphicon-trash"></i> </a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_dokter_poli->count_all(),
						"recordsFiltered" => $this->M_dokter_poli->count_filtered( $dokter ),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	
	public function ajax_edit($id)
	{
		$data = $this->M_dokter->get_by_id($id);		
		echo json_encode($data);
	}


	public function ajax_add()
	{
		// $this->_validate();
		$koders = $this->session->userdata('unit');  
		$kodokter = urut_transaksi('MASTER_DR',10);           
				$data = array(  
				'jenispegawai' => 1,
				'kodokter' =>$kodokter,
				'koders' => $koders,
				'nadokter' => $this->input->post('NamaDokter'),
				'nik' => $this->input->post('NIKIdentitas'),
				'nosip' => $this->input->post('SIP'),
				'npwp' => $this->input->post('NPWP'),
				'hp' => $this->input->post('NoHP'),
				'alamat' => $this->input->post('Alamat'),
				'tglmasuk' => $this->input->post('tglmasuk'),
				'status' => $this->input->post('Status'),
				'tglberhenti' => $this->input->post('tglberhenti'),
				
			);
		$insert = $this->M_dokter->save($data);

		if($insert){
			/* Tambahan Harimas */
			$c_status_unit = $this->input->post('status_unit');
			$c_status_lokasi  = $this->input->post('status_lokasi');
			$jumdata          = count($c_status_lokasi);
			$jumdata1          = count($c_status_unit);
			
			for($i=0;$i<=$jumdata-1;$i++){
				$data_unit = array(
					'kodokter' => $kodokter,
					'koders' => $c_status_lokasi[$i],
				);

				if($c_status_lokasi[$i]!=""){

					$cek = $this->db->query("SELECT * FROM tbl_doktercabang WHERE koders = '$c_status_lokasi[$i]' and kodokter='$kodokter'")->row();

					if (!$cek) {
						$insert = $this->db->insert('tbl_doktercabang',$data_unit);					
					}
				}else{
					echo json_encode(array("status" => false));
					exit;
				}
				/* End Tambahan Harimas */
			}
			if($insert){
				for($i=0;$i<=$jumdata1-1;$i++){
					$data_unit = array(
						'kodokter' => $kodokter,
						'kopoli' => $c_status_unit[$i],
					);
	
					if($c_status_unit[$i]!=""){
	
						$cek = $this->db->query("SELECT * FROM tbl_drpoli WHERE kopoli = '$c_status_unit[$i]' and kodokter='$kodokter'")->row();
	
						if (!$cek) {
							$insert = $this->db->insert('tbl_drpoli',$data_unit);					
						}
					}else{
						echo json_encode(array("status" => false));
						exit;
					}
				}
			}else{
				echo json_encode(array("status" => false));
				exit;	
			}
		}else{
			echo json_encode(array("status" => false));
			exit;
		}
		echo json_encode(array("status" => $insert));
		exit;
	}
	
	
	public function ajax_add_poli()
	{
		$this->_validate_poli();
		$data = array(
		        'kodokter' => $this->input->post('kode_dokter'),
				'kopoli' => $this->input->post('poli'),
				
			);
		$insert = $this->M_dokter_poli->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		// $this->_validate();
		
		$koders = $this->session->userdata('unit');
		$kodokter = $this->input->post('KodeDokter');       
		$data = array(  
				'jenispegawai' => 1,
				'koders' => $koders,
				'nadokter' => $this->input->post('NamaDokter'),
				'nik' => $this->input->post('NIKIdentitas'),
				'nosip' => $this->input->post('SIP'),
				'npwp' => $this->input->post('NPWP'),
				'hp' => $this->input->post('NoHP'),
				'alamat' => $this->input->post('Alamat'),
				'tglmasuk' => $this->input->post('tglmasuk'),
				'status' => $this->input->post('Status'),
				'tglberhenti' => $this->input->post('tglberhenti'),
				
			);
		 $update = $this->M_dokter->update(array('kodokter' => $this->input->post('KodeDokter')), $data);

		 if($update){
			/* Tambahan Harimas */
			$c_status_unit = $this->input->post('status_unit');
			$c_status_lokasi  = $this->input->post('status_lokasi');
			$jumdata          = count($c_status_lokasi);
			$jumdata1          = count($c_status_unit);
			
			for($i=0;$i<=$jumdata-1;$i++){
				$data_unit = array(
					'kodokter' => $kodokter,
					'koders' => $c_status_lokasi[$i],
				);

				if($c_status_lokasi[$i]!=""){

					$cek = $this->db->query("SELECT * FROM tbl_doktercabang WHERE koders = '$c_status_lokasi[$i]' and kodokter='$kodokter'")->row();

					if (!$cek) {
						$update = $this->db->insert('tbl_doktercabang',$data_unit);					
					}
				}else{
					echo json_encode(array("status" => 'Hello'));
					exit;
				}
				/* End Tambahan Harimas */
			}
			if($update){
				for($i=0;$i<=$jumdata1-1;$i++){
					$data_unit = array(
						'kodokter' => $kodokter,
						'kopoli' => $c_status_unit[$i],
					);
	
					if($c_status_unit[$i]!=""){
	
						$cek = $this->db->query("SELECT * FROM tbl_drpoli WHERE kopoli = '$c_status_unit[$i]' and kodokter='$kodokter'")->row();
	
						if (!$cek) {
							$update = $this->db->insert('tbl_drpoli',$data_unit);					
						}
					}else{
						echo json_encode(array("status" => "world"));
						exit;
					}
				}
			}else{
				echo json_encode(array("status" => 'Hey!'));
				exit;	
			}
		}else{
			echo json_encode(array("status" => $update));
			exit;
		}
		echo json_encode(array("status" => $update));
		exit;
	}

	public function ajax_delete($id)
	{
		$this->M_dokter->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

    public function ajax_delete_poli($id)
	{
		$this->M_dokter_poli->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}
	
	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('KodeDokter') == '')
		{
			$data['inputerror'][] = 'KodeDokter';
			$data['error_string'][] = 'Kode harus diisi';
			$data['status'] = FALSE;
		}

		if($this->input->post('NamaDokter') == '')
		{
			$data['inputerror'][] = 'NamaDokter';
			$data['error_string'][] = 'Nama masih kosong';
			$data['status'] = FALSE;
		}

		if($this->input->post('NIKIdentitas') == '')
		{
			$data['inputerror'][] = 'NIKIdentitas';
			$data['error_string'][] = 'NIK masih kosong';
			$data['status'] = FALSE;
		}

		
		if($this->input->post('SIP') == '')
		{
			$data['inputerror'][] = 'SIP';
			$data['error_string'][] = 'SIP masih kosong';
			$data['status'] = FALSE;
		}

		
		if($this->input->post('NPWP') == '')
		{
			$data['inputerror'][] = 'NPWP';
			$data['error_string'][] = 'NPWP masih kosong';
			$data['status'] = FALSE;
		}

		
		if($this->input->post('NoHP') == '')
		{
			$data['inputerror'][] = 'NoHP';
			$data['error_string'][] = 'No HP masih kosong';
			$data['status'] = FALSE;
		}

		
		if($this->input->post('Alamat') == '')
		{
			$data['inputerror'][] = 'Alamat';
			$data['error_string'][] = 'Alamat masih kosong';
			$data['status'] = FALSE;
		}

		
		if($this->input->post('tglmasuk') == '')
		{
			$data['inputerror'][] = 'tglmasuk';
			$data['error_string'][] = 'Tanggal Masuk masih kosong';
			$data['status'] = FALSE;
		}

		
		if($this->input->post('Status') == '')
		{
			$data['inputerror'][] = 'Status';
			$data['error_string'][] = 'Status masih kosong';
			$data['status'] = FALSE;
		}

		
		if($this->input->post('tglberhenti') == '')
		{
			$data['inputerror'][] = 'tglberhenti';
			$data['error_string'][] = 'Tanggal Berhenti masih kosong';
			$data['status'] = FALSE;
		}
		
	
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	
	private function _validate_poli()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('kode_dokter') == '')
		{
			$data['inputerror'][] = 'kode_dokter';
			$data['error_string'][] = 'Kode harus diisi';
			$data['status'] = FALSE;
		}

		if($this->input->post('poli') == '')
		{
			$data['inputerror'][] = 'poli';
			$data['error_string'][] = 'Poli masih kosong';
			$data['status'] = FALSE;
		}
		
	
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	
	public function cetak()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{				  
		  $page=$this->uri->segment(3);
		  $limit=$this->config->item('limit_data');
          $d['unit'] = '';          
          $d['master'] = $this->db->get("ms_unit")->result();
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  
		  $this->load->view('master/unit/v_master_unit_prn',$d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
	public function export()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{				  
		  $page=$this->uri->segment(3);
		  $limit=$this->config->item('limit_data');	
          $d['master'] = $this->db->get("ms_unit");
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  
		  $this->load->view('master/unit/v_master_unit_exp',$d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
}

/* End of file master_unit.php */
/* Location: ./application/controllers/master_unit.php */