<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Akuntansi_akun extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_akun','M_akun');
		$this->load->helper('simkeu_rpt');		
		$this->session->set_userdata('menuapp', '1000');
		$this->session->set_userdata('submenuapp', '1402');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');	
		// echo $this->session->userdata('unit');
		if(!empty($cek))
		{
		  $level=$this->session->userdata('level');		
		  $akses= $this->M_global->cek_menu_akses($level, 1402);
		  $data['akses']= $akses;	
		  $this->load->helper('url');		  
		  $data['tipeakun']= $this->db->order_by('id')->get('tbl_actype')->result_array();	
		  $data['kodeakun']= $this->db->order_by('id')->get('tbl_accounting')->result_array();
		  $this->load->view('akuntansi/v_akuntansi_akun',$data);
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list()
	{
		$level=$this->session->userdata('level');		
		$akses= $this->M_global->cek_menu_akses($level, 1402);	
		$list = $this->M_akun->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $rd) {
			$no++;
			$row = array();
			$tipe  = $this->M_global->_tipeakun($rd->actype);
			// echo $this->db->last_query();
			
			$row[] = $rd->accountno;
			$row[] = $rd->acname;
			$row[] = $tipe;
			$row[] = $rd->aclevel;
			
			//add html for action
			
			if($akses->uedit==1 && $akses->udel==1){
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$rd->id."'".','."'".$rd->accountno."'".')"><i class="glyphicon glyphicon-edit"></i></a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$rd->id."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
		    } else 
			if($akses->uedit==1 && $akses->udel==0){
				$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$rd->id."'".','."'".$rd->accountno."'".')><i class="glyphicon glyphicon-edit"></i></a>';				  
		    } else 
			if($akses->uedit==0 && $akses->udel==1){
				$row[] = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$rd->id."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
			} else {
				$row[] = '';
			}	  
				
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_akun->count_all(),
						"recordsFiltered" => $this->M_akun->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		// isi tabel data
		$data = $this->M_akun->get_by_id($id);
		
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_edit_perkiraan($id)
	{
		// isi tabel data
		$koders = $this->session->userdata('unit');
		$accountno = $this->input->get('accountno');
		$data['data'] = $this->M_akun->get_by_id($id);
		$data['saldo_awal'] = $this->M_akun->get_saldo_awal($koders, $accountno);
		
		// print_r($saldo_awal);

		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'accountno' => $this->input->post('kodeakun'),
				'acname' => $this->input->post('namaakun'),
				'actype' => $this->input->post('jenis'),
				'aclevel' => $this->input->post('level'),
				'kasbank' => $this->input->post('kasbank'),
				'aktif' => $this->input->post('aktif'),
				
				);
		$insert = $this->M_akun->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'accountno' => $this->input->post('kodeakun'),
				'acname' => $this->input->post('namaakun'),
				'actype' => $this->input->post('jenis'),
				'aclevel' => $this->input->post('level'),
				'kasbank' => $this->input->post('kasbank'),
				'aktif' => $this->input->post('aktif'),
			);
		
		$this->M_akun->update(array('id' => $this->input->post('id')), $data);

		if($this->input->post('tahunSaldoAwal') != '' || $this->input->post('tahunSaldoAwal') != NULL){
			// echo "masuk tahun saldo";
			$data_acsaldo = array(
				'saldo01' => $this->input->post('saldo01'),
				'saldo02' => $this->input->post('saldo02'),
				'saldo03' => $this->input->post('saldo03'),
				'saldo04' => $this->input->post('saldo04'),
				'saldo05' => $this->input->post('saldo05'),
				'saldo06' => $this->input->post('saldo06'),
				'saldo07' => $this->input->post('saldo07'),
				'saldo08' => $this->input->post('saldo08'),
				'saldo09' => $this->input->post('saldo09'),
				'saldo10' => $this->input->post('saldo10'),
				'saldo11' => $this->input->post('saldo11'),
				'saldo12' => $this->input->post('saldo12'),

				'bg01' => $this->input->post('bg01'),
				'bg02' => $this->input->post('bg02'),
				'bg03' => $this->input->post('bg03'),
				'bg04' => $this->input->post('bg04'),
				'bg05' => $this->input->post('bg05'),
				'bg06' => $this->input->post('bg06'),
				'bg07' => $this->input->post('bg07'),
				'bg08' => $this->input->post('bg08'),
				'bg09' => $this->input->post('bg09'),
				'bg10' => $this->input->post('bg10'),
				'bg11' => $this->input->post('bg11'),
				'bg12' => $this->input->post('bg12'),
			);
		
			$where_acsaldo = array(
				'koders' 	=> $this->session->userdata('unit'),
				'tahun' 	=> $this->input->post('tahunSaldoAwal'),
				'accountno' => $this->input->post('kodeakun'),
			);
			$this->M_akun->update_acsaldo($where_acsaldo, $data_acsaldo);
		
			// print_r($data_acsaldo);
			// print_r($where_acsaldo);
		}
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_akun->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('kodeakun') == '')
		{
			$data['inputerror'][] = 'kodeakun';
			$data['error_string'][] = 'Kode  harus diisi';
			$data['status'] = FALSE;
		}

		if($this->input->post('namaakun') == '')
		{
			$data['inputerror'][] = 'namaakun';
			$data['error_string'][] = 'nama  masih kosong';
			$data['status'] = FALSE;
		}

		if($this->input->post('jenis') == '')
		{
			$data['inputerror'][] = 'jenis';
			$data['error_string'][] = 'Silahkan pilih jenis';
			$data['status'] = FALSE;
		}

		
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	public function get_data_tahun_saldo_awal(){
		$koders = $this->session->userdata('unit');
		$accountno = $this->input->get('accountno');
		$tahun = $this->input->get('tahun');
		$data['data'] = $this->M_akun->get_saldo_awal_by_tahun($koders, $accountno, $tahun);
		echo json_encode($data);
	}
	
	public function cek_tahun(){
		$koders = $this->session->userdata('unit');
		$accountno = $this->input->get('accountno');
		$tahun = $this->input->get('tahun');
		$data['jml'] = $this->M_akun->cek_tahun($koders, $accountno, $tahun);
		echo json_encode($data);
	}

	public function add_saldo_budget_baru(){
		
		$koders 	= $this->session->userdata('unit');
		$tahun 		= $this->input->post('tahun');
		$accountno 	= $this->input->post('kodeakun');

		$dt = array(			
			'koders' 	=> $koders,
			'tahun' 	=> $tahun,
			'accountno' => $accountno,

			'saldo01' => $this->input->post('saldo01'),
			'saldo02' => $this->input->post('saldo02'),
			'saldo03' => $this->input->post('saldo03'),
			'saldo04' => $this->input->post('saldo04'),
			'saldo05' => $this->input->post('saldo05'),
			'saldo06' => $this->input->post('saldo06'),
			'saldo07' => $this->input->post('saldo07'),
			'saldo08' => $this->input->post('saldo08'),
			'saldo09' => $this->input->post('saldo09'),
			'saldo10' => $this->input->post('saldo10'),
			'saldo11' => $this->input->post('saldo11'),
			'saldo12' => $this->input->post('saldo12'),

			'bg01' => $this->input->post('bg01'),
			'bg02' => $this->input->post('bg02'),
			'bg03' => $this->input->post('bg03'),
			'bg04' => $this->input->post('bg04'),
			'bg05' => $this->input->post('bg05'),
			'bg06' => $this->input->post('bg06'),
			'bg07' => $this->input->post('bg07'),
			'bg08' => $this->input->post('bg08'),
			'bg09' => $this->input->post('bg09'),
			'bg10' => $this->input->post('bg10'),
			'bg11' => $this->input->post('bg11'),
			'bg12' => $this->input->post('bg12'),
		);
	
		$insert_id = $this->M_akun->insert_acsaldo($dt);
		$data['status'] = true;
		if($insert_id == null || $insert_id == 0){
			$status = false;
		} else {
			$data['saldo_awal'] = $this->M_akun->get_saldo_awal($koders, $accountno);
		}
		echo json_encode($data);
	}

	public function cetak()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{				  
		  $page=$this->uri->segment(3);
		  $limit=$this->config->item('limit_data');	
		  $d['unit'] = '';  
          $d['master_akun'] = $this->db->select('ms_akun.*,ms_akun_kelompok.nama as kel')->join('ms_akun_kelompok','ms_akun_kelompok.kode=ms_akun.kelompok','left')->get("ms_akun");
		  $d['master'] = $this->db->get("ms_unit")->result();
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  
		  $this->load->view('akuntansi/v_master_akun_prn',$d);				
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
		//   $page=$this->uri->segment(3);
		//   $limit=$this->config->item('limit_data');	
        //   $d['master_akun'] = $this->db->select('ms_akun.*,ms_akun_kelompok.nama as kel')->join('ms_akun_kelompok','ms_akun_kelompok.kode=ms_akun.kelompok','left')->get("ms_akun");
		  
		//   $d['nama_usaha']=$this->config->item('nama_perusahaan');
		//   $d['alamat']=$this->config->item('alamat_perusahaan');
		//   $d['motto']=$this->config->item('motto');
		  
		//   $this->load->view('akuntansi/v_master_akun_exp',$d);				
		
			$query = "
				SELECT a.accountno, a.acname, act.typename, a.aclevel
				FROM tbl_accounting a LEFT JOIN tbl_actype act ON a.`actype` = act.`actype`
			";

        	$d['master_akun'] = $this->db->query($query)->result(); 
			// print_r($d['master_akun']);
			$this->load->view('akuntansi/v_master_akun_exp',$d);				
			// echo "hhehe";
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
}

/* End of file master_bank.php */
/* Location: ./application/controllers/master_akun.php */