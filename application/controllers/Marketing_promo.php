<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marketing_promo extends CI_Controller {

	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_marketing_promo','M_marketing_promo');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '2000');
		$this->session->set_userdata('submenuapp', '2402');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');				
		if(!empty($cek))
		{
			$data['cabang'] = $this->db->get('tbl_namers')->result();
			$this->load->view('klinik/v_marketing_promo', $data);
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list()
	{
		$list = $this->M_marketing_promo->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit) {
			$kode = $unit->kodepromo;
			$cabangpromo = $this->db->query("select * from tbl_promocabang where kodepromo = '$kode' and promo=1")->result();
			$_cabangpromo= "";
			foreach($cabangpromo as $row){
			  if($_cabangpromo==""){	
                $_cabangpromo = $row->koders; 			  	
			  } else {
				$_cabangpromo .= ", ".$row->koders; 			  	  
			  }
			}
			$no++;
			$row = array();
			$row[] = $unit->kodepromo;
			$row[] = $unit->namapromo;
			$row[] = $unit->ketpromo;
			$row[] = $unit->jenispromo;
			$row[] = $_cabangpromo;
			
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$unit->id."'".')"><i class="glyphicon glyphicon-edit"></i> </a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$unit->id."'".')"><i class="glyphicon glyphicon-trash"></i> </a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_marketing_promo->count_all(),
						"recordsFiltered" => $this->M_marketing_promo->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
    
	
	public function ajax_edit($id)
	{
		$data = $this->M_marketing_promo->get_by_id($id);		
		echo json_encode($data);
	}


	public function ajax_add()
	{
		$this->_validate();
		//$kode = urut_transaksi('MASTER_DR',10);
		
        $kodepromo =  $this->input->post('kode');
		$data = array(
		        'kodepromo' => $this->input->post('kode'),
				'namapromo' => $this->input->post('nama'),
				'ketpromo' => $this->input->post('ket'),
				'tglmulai' => $this->input->post('tglawal'),
				'tglselesai' => $this->input->post('tglakhir'),
				'jenispromo' => $this->input->post('jenis'),
				'stpromo' => $this->input->post('status'),
				
			);
		$insert = $this->M_marketing_promo->save($data);
		
		$this->db->query("insert into tbl_promocabang(kodepromo, koders, promo) select '$kodepromo',
		koders, 0 from tbl_namers");
		
		$cabang_promo = $this->input->post('cabangpromo');
		$cabang_pilih = $this->input->post('cabangpilih');
		$jumdata = count($cabang_promo);
		for($i=0;$i<=$jumdata-1;$i++){
		  $_cabang	 = $cabang_pilih[$i];
		  if($cabang_promo[$i])	{
			  $this->db->query("update tbl_promocabang set promo=1 where kodepromo = '$kodepromo' and koders='$_cabang'");
		  }
		}
		
		echo json_encode(array("status" => TRUE));
	}
		

	public function ajax_update()
	{
		$this->_validate();
		$kodepromo =  $this->input->post('kode');
		$data = array(
				'kodepromo' => $this->input->post('kode'),
				'namapromo' => $this->input->post('nama'),
				'ketpromo' => $this->input->post('ket'),
				'tglmulai' => $this->input->post('tglawal'),
				'tglselesai' => $this->input->post('tglakhir'),
				'jenispromo' => $this->input->post('jenis'),
				'stpromo' => $this->input->post('status'),
				
			);
		$this->M_marketing_promo->update(array('id' => $this->input->post('id')), $data);
		
		$cabang_promo = $this->input->post('cabangpromo');
		$cabang_pilih = $this->input->post('cabangpilih');
		
		$jumdata = count($cabang_promo);
				
		for($i=0;$i<=$jumdata-1;$i++){
		  $_cabang	 = $cabang_pilih[$i];
		  if($cabang_promo[$i] !="")	{
			  $this->db->query("update tbl_promocabang set promo=1 where kodepromo = '$kodepromo' and koders='$_cabang'");
		  } else {
			  $this->db->query("update tbl_promocabang set promo=0 where kodepromo = '$kodepromo' and koders='$_cabang'");
		  }
		}
		
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_marketing_promo->delete_by_id($id);
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
	
	
	
	
}
