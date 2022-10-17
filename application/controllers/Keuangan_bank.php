<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Keuangan_bank extends CI_Controller {

	/**
	 * @author : Enjang RK
	 * @web : http://e-soft.comli.com
	 * @keterangan : Controller untuk manajemen bank (CRUD master bank)
	 **/
	
	
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_bank','M_bank');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '100');
		$this->session->set_userdata('submenuapp', '108');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{
			$this->load->helper('url');
			$d['coa'] = $this->db->get("ms_akun");
			$d['uid'] = $this->db->get("ms_unit");
			$this->load->view('keuangan/v_keuangan_bank',$d);
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list()
	{
		$list = $this->M_bank->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $bank) {
			$no++;
			$row = array();
			$row[] = $bank->bank_kode;
			$row[] = $bank->bank_nama;
			$row[] = $bank->bank_jenis;
			$row[] = $bank->bank_kodeakun;
			$row[] = $bank->bank_norek;
			$row[] = $bank->bank_pasar;
			

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_bank('."'".$bank->bank_kode."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_bank('."'".$bank->bank_kode."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_bank->count_all(),
						"recordsFiltered" => $this->M_bank->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_bank->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'bank_kode' => $this->input->post('bank_kode'),
				'bank_nama' => $this->input->post('bank_nama'),
				'bank_jenis' => $this->input->post('bank_jenis'),
				'bank_kodeakun' => $this->input->post('bank_kodeakun'),
				'bank_norek' => $this->input->post('bank_norek'),
				'bank_pasar' => $this->input->post('bank_pasar'),
			);
		$insert = $this->M_bank->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'bank_kode' => $this->input->post('bank_kode'),
				'bank_nama' => $this->input->post('bank_nama'),
				'bank_jenis' => $this->input->post('bank_jenis'),
				'bank_kodeakun' => $this->input->post('bank_kodeakun'),
				'bank_norek' => $this->input->post('bank_norek'),
				'bank_pasar' => $this->input->post('bank_pasar'),
			);
		$this->M_bank->update(array('bank_kode' => $this->input->post('bank_kode')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_bank->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('bank_kode') == '')
		{
			$data['inputerror'][] = 'bank_kode';
			$data['error_string'][] = 'Kode bank harus diisi';
			$data['status'] = FALSE;
		}

		if($this->input->post('bank_nama') == '')
		{
			$data['inputerror'][] = 'bank_nama';
			$data['error_string'][] = 'nama bank masih kosong';
			$data['status'] = FALSE;
		}

		if($this->input->post('bank_jenis') == '')
		{
			$data['inputerror'][] = 'bank_jenis';
			$data['error_string'][] = 'Silahkan pilih jenis';
			$data['status'] = FALSE;
		}

		if($this->input->post('bank_kodeakun') == '')
		{
			$data['inputerror'][] = 'bank_kodeakun';
			$data['error_string'][] = 'Silahkan pilih kode akun';
			$data['status'] = FALSE;
		}

		if($this->input->post('bank_norek') == '')
		{
			$data['inputerror'][] = 'bank_norek';
			$data['error_string'][] = 'no. rek masing kosong';
			$data['status'] = FALSE;
		}
		if($this->input->post('bank_pasar') == '')
		{
			$data['inputerror'][] = 'bank_pasar';
			$data['error_string'][] = 'unit masing kosong';
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
          $d['master_bank'] = $this->db->get("ms_bank");
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  
		  $this->load->view('master/bank/v_master_bank_prn',$d);				
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
          $d['master_bank'] = $this->db->get("ms_bank");
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  
		  $this->load->view('master/bank/v_master_bank_exp',$d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
}

/* End of file master_bank.php */
/* Location: ./application/controllers/master_bank.php */