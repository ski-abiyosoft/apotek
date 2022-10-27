<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pasien extends CI_Controller {

	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_pasien','M_pasien');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '2000');
		$this->session->set_userdata('submenuapp', '2101');
	}

	public function index()
	{
		$cek = $this->session->userdata('username');				
		if(!empty($cek))
		{
			$this->load->view('klinik/v_pasien');
		} else
		{
			header('location:'.base_url());
		}			
	}
	
	
	public function entri()
	{
		$cek = $this->session->userdata('username');				
		if(!empty($cek))
		{
			$this->load->view('klinik/v_pasien_add');
		} else
		{
			header('location:'.base_url());
		}			
	}
	
	public function update( $id )
	{
		$cek = $this->session->userdata('username');				
		if(!empty($cek))
		{
			$data['data'] = $this->db->query("select * from tbl_pasien where idtr = '$id'")->row();
			$data['id']   = $id;
			$this->load->view('klinik/v_pasien_edit', $data);
		} else
		{
			header('location:'.base_url());
		}			
	}
	
	public function update_lup( $id )
	{
		$cek = $this->session->userdata('username');				
		if(!empty($cek))
		{
			$data['data'] = $this->db->query("select * from tbl_pasien where idtr = '$id'")->row();
			$data['id']   = $id;
			$this->load->view('klinik/v_pasien_edit_1', $data);
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list()
	{
		$jenis = $this->input->get('jenis');		
		if($jenis==1){
		  $filter = '';
		} else {
		  $nama   = $this->input->get('nama');
		  $alamat = $this->input->get('alamat');
		  $noid   = $this->input->get('noid');	
		  $rekmed = $this->input->get('rekmed');
		  $tglLahir = $this->input->get('tglLahir');
		  $noTlp = $this->input->get('noTlp');	
		  $nocard = $this->input->get('nocard');	
		  $filter = $nama.'~'.$alamat.'~'.$noid.'~'.$rekmed.'~'.$tglLahir.'~'.$noTlp.'~'.$nocard;	
		}  
				
		$list = $this->M_pasien->get_datatables( $filter );
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit) {
			$no++;
			$row = array();
			$row[] = $unit->koders;
			$row[] = $unit->rekmed;
			$row[] = $unit->namapas;
			$row[] = $unit->jkel;
			$row[] = $unit->alamat;		
			$row[] = $unit->tempatlahir;			
			$row[] = $unit->tgllahir;				
			$row[] = $unit->handphone;
			// $row[] = $unit->phone;
			$row[] = $unit->noidentitas;
						
			$row[] = 
			     ' <a class="btn btn-sm btn-primary" title="Edit Record ?" href="'.base_url('pasien/update/'.$unit->idtr).'">
				 <i class="glyphicon glyphicon-edit"> Edit</i>
				 </a>';
			     
				 /*
				 '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$unit->idtr."'".')"><i class="glyphicon glyphicon-edit"></i> </a>
	          	  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$unit->idtr."'".')"><i class="glyphicon glyphicon-trash"></i> </a>';
		         */
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_pasien->count_all( $filter ),
						"recordsFiltered" => $this->M_pasien->count_filtered( $filter ),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_pasien->get_by_id($id);		
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$user     = $this->session->userdata('username');
        $cabang   = $this->session->userdata('unit');
		$namax     = $this->input->post('lupnamapasien');
		$nama = preg_replace('/\s+/', ' ', $namax);
		$tgllahir = $this->input->post('luptgllahir');
		$tmptlhr = $this->input->post('luptempatlahir');
		$q = $this->input->post('searchTerm');
		// echo '<pre>';
		// print_r($q);
		// die;
		
		// $jumdata = $this->db->query("SELECT count(*) as jum from tbl_pasien where namapas = '$nama' and tempatlahir = '$tmptlhr' and date(tgllahir)='$tgllahir'")->row();
		
		$qry = "SELECT count(*) as jum from tbl_pasien where namapas = '$nama' and tempatlahir = '$tmptlhr' and date(tgllahir)='$tgllahir'";
		$jumdata = $this->db->query($qry)->row();
		if($jumdata->jum<1){
		// date_default_timezone_set("Asia/Jakarta");
		
		$rekmed     = pasien_rekmed_baru($nama);	 // time();
		
		$this->_validate();
		$data = array(
		        'rekmed'      => $rekmed,
				'koders'      => $cabang,
				'preposisi'   => $this->input->post('luppreposition'),
				'namapas'     => $nama,
				'handphone'   => $this->input->post('luphp'),
				'phone'       => $this->input->post('luptelp'),
				'tempatlahir' => $this->input->post('luptempatlahir'),
				'tgllahir'    => $this->input->post('luptgllahir'),
				'noidentitas' => $this->input->post('lupnoidentitas'),
				'status'      => $this->input->post('lupstatus'),
				'alamat'      => $this->input->post('lupalamat'),
				'jkel'        => $this->input->post('lupkelamin'),
				'wn'          => $this->input->post('lupwarganegara'),
				'idpas'       => $this->input->post('lupidentitas'),
			);

		// print_r($data);
		$insert = $this->M_pasien->save($data);
		
	  	  echo json_encode(array("status" => true,"value" => 1, "idtr" => $insert,"rekmed" => $rekmed,"nama" => $nama, "alamat" => $this->input->post('lupalamat')));
		} else {
		  echo json_encode(array("status" => true,"value" => 0));	
		}
	}

    public function ajax_add_pasien()
	{
		$user       = $this->session->userdata('username');
        $cabang     = $this->session->userdata('unit');						
		$nama       = $this->input->post('namapasien');
		$tgllahir   = $this->input->post('tgllahir');
				
		$rekmed     = pasien_rekmed_baru($nama);
		//$this->_validate();
		if($rekmed){
		$data = array(
			'rekmed'       => $rekmed,
			'koders'       => $this->input->post('cabang'),
			'preposisi'    => $this->input->post('preposition'),
			'namapas'      => $this->input->post('namapasien'),
			'namapanggilan'=> $this->input->post('namapanggilan'),
			'namakeluarga' => $this->input->post('namakeluarga'),
			'pendidikan'   => $this->input->post('pendidikan'),
			'agama'        => $this->input->post('agama'),
			'pekerjaan'    => $this->input->post('pekerjaan'),
			'goldarah'     => $this->input->post('goldarah'),
			'hoby'         => $this->input->post('hobby'),
			'handphone'    => $this->input->post('hp'),
			'phone'        => $this->input->post('phone'),
			'tempatlahir'  => $this->input->post('tempatlahir'),
			'tgllahir'     => $this->input->post('tgllahir'),
			'noidentitas'  => $this->input->post('noidentitas'),
			'status'       => $this->input->post('identitas'),
			'alamat'       => $this->input->post('alamat1'),
			'propinsi'     => $this->input->post('provinsi'),
			'kabupaten'    => $this->input->post('kota'),
			'kecamatan'    => $this->input->post('kecamatan'),
			'kelurahan'    => $this->input->post('kelurahan'),
			'jkel'         => $this->input->post('jeniskelamin'),
			'email'        => $this->input->post('email'),
			'twit'            => $this->input->post('twitter'),
			'fb'           => $this->input->post('fb'),
			'ig'           => $this->input->post('ig'),
			
			'orhub'        => $this->input->post('namakel'),
			'hubungan'     => $this->input->post('hubungan'),
			'alamathub'    => $this->input->post('alamatkel'),
			'emailhub'     => $this->input->post('emailkeluarga'),
			'phonehub'     => $this->input->post('phonekeluarga'),
			'hphub'        => $this->input->post('hpkeluarga'),
			'kodepos'         => $this->input->post('kodepos'),
			'rt'              => $this->input->post('rt'),
			'rw'           => $this->input->post('rw'),
			'pekerjaanhub'    => $this->input->post('pekerjaankel'),
			'jkelhub'      => $this->input->post('jkkeluarga'),
			'iklinik'      => $this->input->post('clupinfoklinik'),
			'cekiklinik'   => $this->input->post('clupinfopas'),
				
				
			);
		$insert = $this->M_pasien->save($data);
          		
	  	  echo json_encode(array("status" => true,"value" => 1, "rekmed" => $rekmed,"nama" => $nama, "alamat" => $this->input->post('alamat1')));
		} else {
		  echo json_encode(array("status" => true,"value" => 0));	
		}
	}
	
	public function ajax_update()
	{
		//$this->_validate();
		$data = array(
			'koders'              => $this->input->post('cabang'),
			'preposisi'           => $this->input->post('preposition'),
			'namapas'             => $this->input->post('namapasien'),
			'namapanggilan'       => $this->input->post('namapanggilan'),
			'namakeluarga'        => $this->input->post('namakeluarga'),
			'pendidikan'          => $this->input->post('pendidikan'),
			'agama'               => $this->input->post('agama'),
			'pekerjaan'           => $this->input->post('pekerjaan'),
			'goldarah'            => $this->input->post('goldarah'),
			'hoby'                => $this->input->post('hobby'),
			'handphone'           => $this->input->post('hp'),
			'phone'               => $this->input->post('phone'),
			'tempatlahir'         => $this->input->post('tempatlahir'),
			'tgllahir'            => $this->input->post('tgllahir'),
			'idpas'               => $this->input->post('identitas'),
			'wn'                  => $this->input->post('warganegara'),
			'noidentitas'         => $this->input->post('noidentitas'),
			'status'              => $this->input->post('status'),
			'alamat'              => $this->input->post('alamat1'),
			'alamat2'             => $this->input->post('alamat2'),
			'propinsi'            => $this->input->post('provinsi'),
			'kabupaten'           => $this->input->post('kota'),
			'kecamatan'           => $this->input->post('kecamatan'),
			'kelurahan'           => $this->input->post('kelurahan'),
			'jkel'                => $this->input->post('jeniskelamin'),
			'email'               => $this->input->post('email'),
			'twit'    			  => $this->input->post('twitter'),
			'fb'                  => $this->input->post('fb'),
			'ig'                  => $this->input->post('ig'),
			'rt'                  => $this->input->post('rt'),
			'rw'                  => $this->input->post('rw'),
			'orhub'               => $this->input->post('namakel'),
			'hubungan'            => $this->input->post('hubungan'),
			'alamathub'           => $this->input->post('alamatkel'),
			'emailhub'            => $this->input->post('emailkeluarga'),
			'phonehub'            => $this->input->post('phonekeluarga'),
			'hphub'               => $this->input->post('hpkeluarga'),
			'kodepos'             => $this->input->post('kodepos'),
			'pekerjaanhub'        => $this->input->post('pekerjaankel'),
			'jkelhub'             => $this->input->post('jkkeluarga'),
			'iklinik'        	  => $this->input->post('lupinfoklinik'),
			'cekiklinik'          => $this->input->post('lupinfopas'),
			'alasan'              => $this->input->post('alasan'),
			'nocard'              => $this->input->post('nocard'),
				
			);
		
		$status = $this->M_pasien->update(
			array(
				'idtr' => $this->input->post('idpasien')
			), $data
		);
		
		history_log(0 ,'EDIT DATA PASIEN' ,'EDIT' ,$this->input->post('idpasien') ,'-');
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_pasien->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

        
		if($this->input->post('lupnamapasien') == '')
		{
			$data['inputerror'][] = 'lupnamapasien';
			$data['error_string'][] = 'Nama harus diisi';
			$data['status'] = FALSE;
		}	
		
		if($this->input->post('luptgllahir') == '')
		{
			$data['inputerror'][] = 'luptgllahir';
			$data['error_string'][] = 'Tanggal harus diisi';
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
	
	function getinfopasien(){
	  $id = $this->input->get('id');	
	  
	  $result = $this->M_global->_datapasien($id);

	  echo json_encode($result);
	}

	function getinfopasien_directRegist(){
		$id = $this->input->get('id');	
		
		$result = $this->M_global->_datapasien_directRegist($id);
		
		echo json_encode($result);
	  }
	
	
}

/* End of file master_unit.php */
/* Location: ./application/controllers/master_unit.php */