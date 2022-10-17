<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_akun extends CI_Controller {

	/**
	 * @author : Enjang RK
	 * @web : http://e-soft.comli.com
	 * @keterangan : Controller untuk manajemen coa (CRUD master coa)
	 **/
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_akun','M_akun');
		$this->load->helper('simkeu_rpt');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{
		  $this->load->helper('url');		  
		  $this->load->view('master/akun/v_master_akun');
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list()
	{
		$list = $this->M_akun->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $rd) {
			$no++;
			$row = array();
			$row[] = $rd->kodeakun;
			$row[] = $rd->namaakun;
			$row[] = $rd->jenis;
			$row[] = $rd->tipe;
			

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$rd->kodeakun."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$rd->kodeakun."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';
		
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
		$data = $this->M_akun->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'kodeakun' => $this->input->post('kodeakun'),
				'namaakun' => $this->input->post('namaakun'),
				'jenis' => $this->input->post('jenis'),
				'tipe' => $this->input->post('tipe'),
				);
		$insert = $this->M_akun->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'kodeakun' => $this->input->post('kodeakun'),
				'namaakun' => $this->input->post('namaakun'),
				'jenis' => $this->input->post('jenis'),
				'tipe' => $this->input->post('tipe'),
			);
		$this->M_akun->update(array('kodeakun' => $this->input->post('kodeakun')), $data);
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

		if($this->input->post('tipe') == '')
		{
			$data['inputerror'][] = 'tipe';
			$data['error_string'][] = 'level harus diisi';
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
          $d['master_akun'] = $this->db->get("ms_akun");
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  
		  $this->load->view('master/akun/v_master_akun_prn',$d);				
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
          $d['master_akun'] = $this->db->get("ms_akun");
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  
		  $this->load->view('master/akun/v_master_akun_exp',$d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
}

/* End of file master_bank.php */
/* Location: ./application/controllers/master_akun.php */