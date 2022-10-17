<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_wilayah extends CI_Controller {

	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_provinsi','M_provinsi');
		$this->load->model('M_kota','M_kota');
		$this->load->model('M_kecamatan','M_kecamatan');
		$this->load->model('M_desa','M_desa');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '1000');
		$this->session->set_userdata('submenuapp', '1112');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');				
		if(!empty($cek))
		{
			$this->load->view('master/v_master_wilayah');
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function provinsi_list()
	{
		$list = $this->M_provinsi->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit) {
			$no++;
			$row = array();
			$row[] = $unit->kodeprop;
			$row[] = '<a href="javascript:void(0)" onclick="detil_provinsi('."'".$unit->kodeprop."'".",'".$unit->namaprop."'".')">'.$unit->namaprop.'</a>';
						
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$unit->id."'".')"><i class="glyphicon glyphicon-edit"></i> </a>				     
					  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$unit->id."'".')"><i class="glyphicon glyphicon-trash"></i> </a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_provinsi->count_all(),
						"recordsFiltered" => $this->M_provinsi->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	
	public function kota_list( $prop= "" )
	{
		$list = $this->M_kota->get_datatables( $prop );
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit) {
			$no++;
			$row = array();
			$row[] = $unit->kodekab;
			$row[] = '<a href="javascript:void(0)" onclick="detil_kota('."'".$unit->kodekab."'".",'".$unit->namakab."'".')">'.$unit->namakab.'</a>';
						
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data_kota('."'".$unit->id."'".')"><i class="glyphicon glyphicon-edit"></i> </a>
				     
					 <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data_kota('."'".$unit->id."'".')"><i class="glyphicon glyphicon-trash"></i> </a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_kota->count_all(),
						"recordsFiltered" => $this->M_kota->count_filtered( $prop ),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	
	public function kecamatan_list( $kota= "" )
	{
		$list = $this->M_kecamatan->get_datatables( $kota );
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit) {
			$no++;
			$row = array();
			$row[] = $unit->kodekec;
			$row[] = '<a href="javascript:void(0)" onclick="detil_kecamatan('."'".$unit->kodekec."'".",'".$unit->namakec."'".')">'.$unit->namakec.'</a>';
						
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data_kecamatan('."'".$unit->id."'".')"><i class="glyphicon glyphicon-edit"></i> </a>
				     
					 <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data_kecamatan('."'".$unit->id."'".')"><i class="glyphicon glyphicon-trash"></i> </a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_kecamatan->count_all(),
						"recordsFiltered" => $this->M_kecamatan->count_filtered( $kota ),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	
	public function desa_list( $kec= "" )
	{
		$list = $this->M_desa->get_datatables( $kec );
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit) {
			$no++;
			$row = array();
			$row[] = $unit->kodedesa;
			$row[] = $unit->namadesa;
						
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data_desa('."'".$unit->id."'".')"><i class="glyphicon glyphicon-edit"></i> </a>
				     
					 <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data_desa('."'".$unit->id."'".')"><i class="glyphicon glyphicon-trash"></i> </a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_desa->count_all(),
						"recordsFiltered" => $this->M_desa->count_filtered( $kec ),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_provinsi->get_by_id($id);		
		echo json_encode($data);
	}
	
	public function ajax_edit_kota($id)
	{
		$data = $this->M_kota->get_by_id($id);		
		echo json_encode($data);
	}
	
	public function ajax_edit_kecamatan($id)
	{
		$data = $this->M_kecamatan->get_by_id($id);		
		echo json_encode($data);
	}
	
	public function ajax_edit_desa($id)
	{
		$data = $this->M_desa->get_by_id($id);		
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'kodeprop' => $this->input->post('kode'),
				'namaprop' => $this->input->post('nama'),
				
			);
		$insert = $this->M_provinsi->save($data);
		echo json_encode(array("status" => TRUE));
	}
	
	public function ajax_add_kota()
	{
		$this->_validate_kota();
		$data = array(
		        'kodeprop' => $this->input->post('kode_prop'),
				'kodekab' => $this->input->post('kode_kota'),
				'namakab' => $this->input->post('nama_kota'),
				
			);
		$insert = $this->M_kota->save($data);
		echo json_encode(array("status" => TRUE));
	}
	
	public function ajax_add_kecamatan()
	{
		$this->_validate_kecamatan();
		$data = array(
		        'kodekab' => $this->input->post('kode_kota_kec'),
				'kodekec' => $this->input->post('kode_kecamatan'),
				'namakec' => $this->input->post('nama_kecamatan'),
				
			);
		$insert = $this->M_kecamatan->save($data);
		echo json_encode(array("status" => TRUE));
	}
	
	public function ajax_add_desa()
	{
		$this->_validate_desa();
		$data = array(
		        'kodekec' => $this->input->post('kode_kecamatan_desa'),
				'kodedesa' => $this->input->post('kode_desa'),
				'namadesa' => $this->input->post('nama_desa'),
				
			);
		$insert = $this->M_desa->save($data);
		echo json_encode(array("status" => TRUE));
	}
	

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'kodeprop' => $this->input->post('kode'),
				'namaprop' => $this->input->post('nama'),
			);
		$this->M_provinsi->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}
	
	public function ajax_update_kota()
	{
		$this->_validate_kota();
		$data = array(
				'kodeprop' => $this->input->post('kode_prop'),
				'kodekab' => $this->input->post('kode_kota'),
				'namakab' => $this->input->post('nama_kota'),
			);
		$this->M_kota->update(array('id' => $this->input->post('id_kota')), $data);
		echo json_encode(array("status" => TRUE));
	}
	
	public function ajax_update_kecamatan()
	{
		$this->_validate_kecamatan();
		$data = array(
				'kodekab' => $this->input->post('kode_kota_kec'),
				'kodekec' => $this->input->post('kode_kecamatan'),
				'namakec' => $this->input->post('nama_kecamatan'),
			);
		$this->M_kecamatan->update(array('id' => $this->input->post('id_kecamatan')), $data);
		echo json_encode(array("status" => TRUE));
	}
	
	public function ajax_update_desa()
	{
		$this->_validate_desa();
		$data = array(
				'kodekec' => $this->input->post('kode_kecamatan_desa'),
				'kodedesa' => $this->input->post('kode_desa'),
				'namadesa' => $this->input->post('nama_desa'),
			);
		$this->M_desa->update(array('id' => $this->input->post('id_desa')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_provinsi->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}
	
	public function ajax_delete_kota($id)
	{
		$this->M_kota->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}
	
	public function ajax_delete_kecamatan($id)
	{
		$this->M_kecamatan->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}
	
	public function ajax_delete_desa($id)
	{
		$this->M_desa->delete_by_id($id);
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
	
	private function _validate_kota()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('kode_kota') == '')
		{
			$data['inputerror'][] = 'kode_kota';
			$data['error_string'][] = 'Kode harus diisi';
			$data['status'] = FALSE;
		}

		if($this->input->post('nama_kota') == '')
		{
			$data['inputerror'][] = 'nama_kota';
			$data['error_string'][] = 'Nama masih kosong';
			$data['status'] = FALSE;
		}
		
	
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	
	private function _validate_kecamatan()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

        if($this->input->post('kode_kota_kec') == '')
		{
			$data['inputerror'][] = 'kode_kota_kec';
			$data['error_string'][] = 'Kabupaten/Kota Harus diisi';
			$data['status'] = FALSE;
		}
		
		if($this->input->post('kode_kecamatan') == '')
		{
			$data['inputerror'][] = 'kode_kecamatan';
			$data['error_string'][] = 'Kode harus diisi';
			$data['status'] = FALSE;
		}

		if($this->input->post('nama_kecamatan') == '')
		{
			$data['inputerror'][] = 'nama_kecamatan';
			$data['error_string'][] = 'Nama masih kosong';
			$data['status'] = FALSE;
		}
		
	
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	
	private function _validate_desa()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

        if($this->input->post('kode_kecamatan_desa') == '')
		{
			$data['inputerror'][] = 'kode_kecamatan_desa';
			$data['error_string'][] = 'Kecamatan Harus diisi';
			$data['status'] = FALSE;
		}
		
		if($this->input->post('kode_desa') == '')
		{
			$data['inputerror'][] = 'kode_desa';
			$data['error_string'][] = 'Kode harus diisi';
			$data['status'] = FALSE;
		}

		if($this->input->post('nama_desa') == '')
		{
			$data['inputerror'][] = 'nama_desa';
			$data['error_string'][] = 'Nama masih kosong';
			$data['status'] = FALSE;
		}
		
	
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	
	
	
	
	
}

