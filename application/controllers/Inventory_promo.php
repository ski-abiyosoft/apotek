<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_promo extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_promo');
		$this->load->model('M_barang');
		$this->load->model('M_param');
		$this->session->set_userdata('menuapp', '500');
		$this->session->set_userdata('submenuapp', '517');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{
			$data['item']= $this->db->get('inv_barang')->result_array();			
			$this->load->view('inventory/v_inventory_promo',$data);
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list()
	{
		$list = $this->M_promo->get_datatables();
		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $item) {
			$no++;
			$row = array();
			$kode  = $item->kodeitem;
			$nama  = $this->M_global->_namabarang($kode);			
			$row[] = date('d-m-Y',strtotime($item->tglawal));			
			$row[] = date('d-m-Y',strtotime($item->tglakhir));			
			$row[] = $item->kodeitem;
			$row[] = $nama;
			$row[] = $item->qty;
			$row[] = $item->satuan;
			$row[] = number_format($item->hrg1,0,',','.');						
			$row[] = number_format($item->hrg2,0,',','.');						
			$row[] = number_format($item->hrg3,0,',','.');						
			$row[] = 
			     '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$item->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
			      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$item->id."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_promo->count_all(),
						"recordsFiltered" => $this->M_promo->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_promo->get_by_id($id);		
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		
		$data = array(
			'tglawal'     => date('Y-m-d',strtotime($this->input->post('tglawal'))),
			'tglakhir'    => date('Y-m-d',strtotime($this->input->post('tglakhir'))),
			'kodeitem'    => $this->input->post('kodeitem1'),
			'qty'         => $this->input->post('qty1'),
			'satuan'      => $this->input->post('satuan1'),
			'hrg1'        => $this->input->post('harga11'),
			'hrg2'        => $this->input->post('harga21'),
			'hrg3'        => $this->input->post('harga31'),
			'bnsitem'     => $this->input->post('kodeitem2'),
			'bnsqty'      => $this->input->post('qty2'),
			'bnssat'      => $this->input->post('satuan2'),
			'bnshrg1'     => $this->input->post('harga12'),
			'bnshrg2'     => $this->input->post('harga22'),
			'bnshrg3'     => $this->input->post('harga32'),
			'kodeuser'    => $this->session->userdata('username'),
		);
			
		$insert = $this->M_promo->save($data);
		echo json_encode(array("status" => TRUE));
			
		
	}

	
	public function ajax_update()
	{
		$this->_validate();
		$data = array(	
		    'tglawal'     => date('Y-m-d',strtotime($this->input->post('tglawal'))),
			'tglakhir'    => date('Y-m-d',strtotime($this->input->post('tglakhir'))),
			'kodeitem'    => $this->input->post('kodeitem1'),
			'qty'         => $this->input->post('qty1'),
			'satuan'      => $this->input->post('satuan1'),
			'hrg1'        => $this->input->post('harga11'),
			'hrg2'        => $this->input->post('harga21'),
			'hrg3'        => $this->input->post('harga31'),
			'bnsitem'     => $this->input->post('kodeitem2'),
			'bnsqty'      => $this->input->post('qty2'),
			'bnssat'      => $this->input->post('satuan2'),
			'bnshrg1'     => $this->input->post('harga12'),
			'bnshrg2'     => $this->input->post('harga22'),
			'bnshrg3'     => $this->input->post('harga32'),
			'kodeuser'    => $this->session->userdata('username'),
	    );	
		$this->M_promo->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}
	
	public function ajax_delete($id)
	{
		$this->M_promo->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		
		if($this->input->post('kodeitem1') == '')
		{
			$data['inputerror'][] = 'kodeitem1';
			$data['error_string'][] = 'Kode Barang belum dipilih';
			$data['status'] = FALSE;
		}
	
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	
	public function getinfoitem($id)
	{
		$data = $this->M_barang->get_by_id($id);		
		echo json_encode($data);
	}
	
	
}
