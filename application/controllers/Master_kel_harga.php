<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_kel_harga extends CI_Controller {

	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_kel_harga','M_kel_harga');
		$this->load->model('M_kel_harga_detail','M_kel_harga_detail');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '1000');
		$this->session->set_userdata('submenuapp', '1207');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');				
		if(!empty($cek))
		{
			$this->load->view('master/v_kel_harga');
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list()
	{
		$list = $this->M_kel_harga->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit) {
			$no++;
			$row = array();
			
			$row[] = '<div class="text-center">' . $unit->kodeharga . '</b></div>';
			$row[] = $unit->kelompok;
						
			$cek= $this->db->query("SELECT*FROM tbl_marginkelompokdetail WHERE kodeharga='$unit->kodeharga' and (umum<>0 or member<>0)")->num_rows();
			if($cek>0){

				$row[] = '<div class="text-center">
						<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$unit->kodeharga."'".')"><i class="glyphicon glyphicon-edit"></i> </a></div>';
			}else{

				$row[] = '<div class="text-center">
						<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$unit->kodeharga."'".')"><i class="glyphicon glyphicon-edit"></i> </a>
						<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$unit->kodeharga."'".')"><i class="glyphicon glyphicon-trash"></i> </a></div>';
			}
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_kel_harga->count_all(),
						"recordsFiltered" => $this->M_kel_harga->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	
	public function ajax_list2()
	{
		$list   = $this->M_kel_harga_detail->get_datatables();
		$data   = array();
		$no     = $_POST['start'];
		foreach ($list as $unit) {
			$no++;
			$row = array();
			$row[] = '<div class="text-center">' . $unit->koders . '</div>';
			$row[] = '<div class="text-center">' .$unit->kodeharga. '</div>';
			$row[] = $unit->kelompok;
			$row[] = '<div class="text-center">' .$unit->umum. '</div>';
			$row[] = '<div class="text-center">' .$unit->member. '</div>';
						
			$row[] = '
			<div class="text-center"><a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data_detail('."'".$unit->kodeharga."'".','."'".$unit->koders."'".')"><i class="glyphicon glyphicon-edit"></i></a></div>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_kel_harga_detail->count_all(),
						"recordsFiltered" => $this->M_kel_harga_detail->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_kel_harga->get_by_id($id);		
		echo json_encode($data);
	}

	public function ajax_edit_detail($id,$koders)
	{
		$data = $this->M_kel_harga_detail->get_by_id($id,$koders);		
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		// $kodeharga    = $this->input->post('kode');
		
		$kodeharga = urut_transaksi_kel('URUT_KELOMPOK', 3);
		// header
		$data = array(
				'kodeharga' => $kodeharga,
				'kelompok' => $this->input->post('nama'),
				
		);
		$insert       = $this->M_kel_harga->save($data);

		// detail
		$sql          = $this->db->query("SELECT koders,$kodeharga kodeharga, 0 umum,0 member  from tbl_namers")->result();

		foreach($sql as $d){
			
			$datad = array(
				'koders'    => $d->koders,
				'kodeharga' => $kodeharga,
				'umum'      => 0,
				'member'    => 0,
			);
			$insertd    = $this->db->insert('tbl_marginkelompokdetail', $datad);
		}

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'kelompok' => $this->input->post('nama'),
			);
		$this->M_kel_harga->update(array('kodeharga' => $this->input->post('kode')), $data);
		echo json_encode(array("status" => TRUE));
	}


	public function ajax_update_detail()
	{
		$koders       = $this->input->post('koded');
		$kodeharga    = $this->input->post('kodehargar');
		$kelompok     = $this->input->post('namad');
		$umum         = $this->input->post('umum');
		$member       = $this->input->post('member');

		$cek= $this->db->query("SELECT*FROM tbl_marginkelompokdetail WHERE koders='$koders' and kodeharga='$kodeharga' ");

		if($cek){
			$datad = array(
					'umum' => $umum,
					'member' => $member,
				);
			$this->M_kel_harga_detail->update(array('kodeharga' => $kodeharga,'koders' => $koders), $datad);
			echo json_encode(array("status" => TRUE));
		}
	}

	public function ajax_delete($id)
	{
		// $header   = $this->M_kel_harga->delete_by_id($id);
		$header   = $this->db->query("DELETE FROM tbl_marginkelompok WHERE kodeharga = '$id'");

		if($header){
			$detail   = $this->db->query("DELETE from tbl_marginkelompokdetail where kodeharga = '$id'");
			if ($detail) {
				echo json_encode(array("status" => TRUE));

			} else {
				// echo json_encode(array("status" => FALSE));

			}
		}else{
			// echo json_encode(array("status" => TRUE));
			
		}

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