<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_perawat extends CI_Controller {

	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_perawat','M_perawat');
		$this->load->model('M_perawat_poli','M_perawat_poli');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '1000');
		$this->session->set_userdata('submenuapp', '1106');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');				
		if(!empty($cek))
		{
			$data['cabang'] = $this->db->get('tbl_namers')->result();
			$this->load->view('master/v_master_perawat', $data);
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list()
	{
		$list = $this->M_perawat->get_datatables();
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
						"recordsTotal" => $this->M_perawat->count_all(),
						"recordsFiltered" => $this->M_perawat->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	
	public function poli_list( $prt= "" )
	{
		$list = $this->M_perawat_poli->get_datatables( $prt );
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
						"recordsTotal" => $this->M_perawat_poli->count_all(),
						"recordsFiltered" => $this->M_perawat_poli->count_filtered( $prt ),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_perawat->get_by_id($id);		
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$kode = urut_transaksi('MASTER_PR',10); 
		$data = array(
		        'koders' => $this->input->post('cabang'),    
				'kodokter' => $kode,
				'nadokter' => $this->input->post('nama'),
				'jenispegawai' => 2,
				
			);
		$insert = $this->M_perawat->save($data);
		echo json_encode(array("status" => TRUE));
	}

    public function ajax_add_poli()
	{
		$this->_validate_poli();
		$data = array(
		        'kodokter' => $this->input->post('kode_dokter'),
				'kopoli' => $this->input->post('poli'),
				
			);
		$insert = $this->M_perawat_poli->save($data);
		echo json_encode(array("status" => TRUE));
	}
	
	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'koders' => $this->input->post('cabang'),    
				'kodokter' => $this->input->post('kode'),
				'nadokter' => $this->input->post('nama'),
				
			);
		$this->M_perawat->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

    public function ajax_delete_poli($id)
	{
		$this->M_perawat_poli->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}
	
	public function ajax_delete($id)
	{
		$this->M_perawat->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('cabang') == '')
		{
			$data['inputerror'][] = 'cabang';
			$data['error_string'][] = 'Cabang harus diisi';
			$data['status'] = FALSE;
		}
		
		if($this->input->post('kode') == '')
		{
			$data['inputerror'][] = 'kode';
			$data['error_string'][] = 'Kode harus diisi';
			$data['status'] = FALSE;
		}

		if($this->input->post('nama') == '')
		{
			$data['inputerror'][] = 'nama';
			$data['error_string'][] = 'Nama masih kosong';
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