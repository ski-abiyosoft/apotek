<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_nourut extends CI_Controller {

	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_nourut','M_nourut');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '1000');
		$this->session->set_userdata('submenuapp', '1107');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');				
		if(!empty($cek))
		{
			$this->load->view('master/v_master_penjamin');
		} else
		{
			header('location:'.base_url());
		}			
	}

    function cek_master( $cabang ){
		$data = $this->db->query("select count(*) as jumlah from tbl_urutrs where koders = '$cabang'")->row();
		if($data->jumlah<1){
			$this->db->query("insert into tbl_urutrs(koders, kode_urut, hedket, param1, param2, param3) 
			select distinct '$cabang', kode_urut, hedket, param1, param2, param3 from tbl_urutrs");
		} else {
			$this->db->query("insert into tbl_urutrs(koders, kode_urut, hedket, param1, param2, param3) select '$cabang', 
			kode_urut, hedket, param1, param2, param3 from tbl_urutrs
			where kode_urut not in(select distinct kode_urut from tbl_urutrs)
			");
		}
		
	}
	
	public function ajax_list( $cabang )
	{
		$this->cek_master( $cabang );
		$list = $this->M_nourut->get_datatables( $cabang );
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit) {
			$no++;
			$row = array();
			$row[] = $unit->koders;
			$row[] = $unit->kode_urut;
			$row[] = $unit->hedket;
			$row[] = $unit->param1;
			$row[] = $unit->param2;
			$row[] = $unit->param3;
			$row[] = $unit->nourut;
						
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$unit->id."'".')"><i class="glyphicon glyphicon-edit"></i> </a>';
				  
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_nourut->count_all(),
						"recordsFiltered" => $this->M_nourut->count_filtered( $cabang ),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_nourut->get_by_id($id);		
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'koders' => $this->input->post('cabang'),
				'kode_urut' => $this->input->post('kode'),
				'hedket' => $this->input->post('nama'),
				'param1' => $this->input->post('param1'),
				'param2' => $this->input->post('param2'),
				'param3' => $this->input->post('param3'),
				'nourut' => $this->input->post('nourut'),								
			);
		$insert = $this->M_nourut->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'koders' => $this->input->post('cabang'),
				'kode_urut' => $this->input->post('kode'),
				'hedket' => $this->input->post('nama'),
				'param1' => $this->input->post('param1'),
				'param2' => $this->input->post('param2'),
				'param3' => $this->input->post('param3'),
				'nourut' => $this->input->post('nourut'),	
				
			);
		$this->M_nourut->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_nourut->delete_by_id($id);
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