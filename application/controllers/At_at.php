<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class At_at extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_at_at','M_at_at');
		$this->load->model('M_penyusutan', 'penyusutan');
		$this->load->model('M_at_jenis', 'jenis');
		$this->load->helper('simkeu_rpt');
		$this->load->helper('kode');
		$this->session->set_userdata('menuapp', '5000');
		$this->session->set_userdata('submenuapp', '5402');
		$this->load->library('accounting');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{
			$this->load->helper('url');
			//$d['at'] = $this->db->get("ms_at");			
			$d['atjenis'] = $this->db->get("tbl_fixed")->result();
			$d['cabang']  = $this->session->userdata('unit');
			$this->load->view('at/v_at_at',$d);
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list()
	{
		$list = $this->M_at_at->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $at) {
			$no++;
			$row = array();
			$row[] = $at->koders;
			$row[] = $at->kodefix;
			$row[] = $at->serialno;
			$row[] = $at->namafix;
			$row[] = $at->lokasi;
			$row[] = $at->pemakai;
			$row[] = tanggal($at->tglaku);
			$row[] = tanggal($at->tglpakai);
			$row[] = tanggal($at->tglkalibrasi);
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$at->id."'".')"><i class="glyphicon glyphicon-edit"></i> </a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$at->id."'".')"><i class="glyphicon glyphicon-trash"></i></a>
				  <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Hapus" onclick="view_data('."'".$at->id."'".')"><i class="glyphicon glyphicon-eye-open"></i></a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_at_at->count_all(),
						"recordsFiltered" => $this->M_at_at->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_at_at->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();

		// Generate the fixed asset code
		$code = generate_fixed_asset_code($this->M_at_at->get_auto_increment());

		$data = array(
		        'koders' => $this->input->post('cabang'),
				'kodefix' => $code,
				'serialno' => $this->input->post('serial'),
				'namafix' => $this->input->post('nama'),
				'fixid' => $this->input->post('jenis'),
				'tglaku' => $this->input->post('tanggalbeli'),
				'tglpakai' => $this->input->post('tanggalpakai'),
				'tglkalibrasi' => $this->input->post('tanggalkalibrasi'),
				'jumlah' => $this->input->post('jumlah'),
				'nilaiaktiva' => $this->input->post('hargaperolehan'),
				'lokasi' => $this->input->post('lokasi'),
				'pemakai' => $this->input->post('pemakai'),
			);

		$jenis = $this->jenis->get_jenis($data['fixid']);

		$insert = $this->M_at_at->save($data);
		$this->accounting->create_depreciation ([
            'kode_rs' => $data['koders'],
            'kode_fix' => $data['kodefix'],
            'bulan_pakai' => $data['tglpakai'],
            'depreciation_year' => $jenis->tahunsusut,
            'accuisition_value' => $data['nilaiaktiva']
        ]);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();

		$data = array(
				'namafix' => $this->input->post('nama'),
				'fixid' => $this->input->post('jenis'),
				'nilaiaktiva' => $this->input->post('hargaperolehan'),
				'jumlah' => $this->input->post('jumlah'),
				'serialno' => $this->input->post('serial'),
				'lokasi' => $this->input->post('lokasi'),
				'pemakai' => $this->input->post('pemakai'),
				'tglaku' => $this->input->post('tanggalbeli'),
				'tglpakai' => $this->input->post('tanggalpakai'),
				'tglkalibrasi' => $this->input->post('tanggalkalibrasi'),	
			);

		$this->M_at_at->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_at_at->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('kode') == '')
		{
			$data['inputerror'][] = 'kode';
			$data['error_string'][] = 'Kode jenis at harus diisi';
			$data['status'] = FALSE;
		}

		if($this->input->post('nama') == '')
		{
			$data['inputerror'][] = 'nama';
			$data['error_string'][] = 'nama jenis masih kosong';
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
          $d['at'] = $this->db->get("ms_at");
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  
		  $this->load->view('at/at_at_prn',$d);				
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
          $d['at'] = $this->db->get("ms_at");
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  
		  $this->load->view('at/at_at_exp',$d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	/**
	 * Method untuk mendapatkan data detail aktiva tetap
	 * 
	 * @return JSON
	 */
	public function ajax_detail($id)
	{
		$data = $this->M_at_at->get_by_id($id);
		$data->depreciation_list = $this->penyusutan->get_susut($data->kodefix)->depreciation_list;
		$data->group_detail = $this->jenis->get_jenis($data->fixid);

		echo json_encode($data);
	}
	
	/**
	 * Method untuk download barcode aktiva tetap
	 */
	public function barcode ()
	{
		$data['nama'] = $this->input->get('nama');
		$data['serial'] = $this->input->get('serial');
		$data['tglpakai'] = $this->input->get('tglpakai');
		$data['tglkalibrasi'] = $this->input->get('tglkalibrasi');

		return $this->load->view('laporan_akuntansi/barcode', $data);
	}
}

/* End of file master_bank.php */
/* Location: ./application/controllers/master_bank.php */