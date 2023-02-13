<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_penjamin extends CI_Controller {

	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_penjamin','M_penjamin');
		$this->load->model('M_template_cetak','M_template_cetak');
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

	public function ajax_list()
	{
		$list = $this->M_penjamin->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit) {
			$no++;
			$row = array();
			$row[] = $unit->cust_id;
			$row[] = $unit->cust_nama;
						
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$unit->id."'".')"><i class="glyphicon glyphicon-edit"></i> </a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$unit->id."'".')"><i class="glyphicon glyphicon-trash"></i> </a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_penjamin->count_all(),
						"recordsFiltered" => $this->M_penjamin->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_penjamin->get_by_id($id);		
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'cust_id' => $this->input->post('kode'),
				'cust_nama' => $this->input->post('nama'),
				
			);
		$insert = $this->M_penjamin->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'cust_id' => $this->input->post('kode'),
				'cust_nama' => $this->input->post('nama'),
				
			);
		$this->M_penjamin->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_penjamin->delete_by_id($id);
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
	
	function ctk($cekpdf=0)
	{

		// $cekpdf   = 1;
		$position = 'P';
		$date     = '-';
		$body     = '';

		$body.= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
			<tr>
				<td style=\"border:0\" colspan=\"29\" align=\"center\"><br></td>                
			</tr>
			<tr>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>NO</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>Kode</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>Nama</b></td>
			</tr>";

			$sql =$this->db->query(
				"SELECT*FROM tbl_penjamin order by cust_id")->result();


			$lcno        = 0;
			$id          = '';
			$cust_id     = '';
			$cust_nama   = '';

			foreach ($sql as $row) {
				$lcno       = $lcno + 1;
				$id         = $row->id;
				$cust_id    = $row->cust_id;
				$cust_nama  = $row->cust_nama;

				$body .= "<tr>

						<td align=\"center\">$lcno</td>
						<td align=\"center\">$cust_id</td>
						<td align=\"center\">$cust_nama</td>
						</tr>"; 
			
			}

			$body  .= "</table>";
			$judul  = 'DATA MASTER PENJAMIN';

			$this->M_template_cetak->template($judul, $body, $position, $date, $cekpdf);
		
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