<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_harga extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_harga_barang','M_harga_barang');
		$this->session->set_userdata('menuapp', '500');
		$this->session->set_userdata('submenuapp', '518');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{
			$data['cabang'] = $this->db->get("ms_unit");
			$data['rak'] = $this->db->get("inv_rak");
			$data['satuan'] = $this->db->get("inv_satuan");
			$data['gudang'] = $this->db->get("inv_gudang");
			$data['kateg'] = $this->db->get("inv_kategori");
			$data['subkateg'] = $this->db->get("inv_subkategori");
			$data['merk'] = $this->db->get("inv_merk");
			$data['supplier'] = $this->db->get("ap_supplier");
			$level=$this->session->userdata('level');		
		    $akses= $this->M_global->cek_menu_akses($level, 519);
		    $data['akses']= $akses;
			$this->load->view('inventory/v_inventory_harga_barang',$data);
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function getinfobarang( $kode )
	{
		$data = $this->M_global->_data_barang( $kode );
		echo json_encode($data);
	}
	
	public function entri()
	{
		$cek = $this->session->userdata('level');		
		$uid = $this->session->userdata('unit');		
		if(!empty($cek))
		{				  
		  $d['pic'] = $this->session->userdata('username');
		  $this->load->view('inventory/v_inventory_harga_barang_add', $d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	public function edit()
	{
		$cek = $this->session->userdata('level');		
		$uid = $this->session->userdata('unit');		
		if(!empty($cek))
		{				  
		  $d['pic'] = $this->session->userdata('username');
		  $d['barang'] = $this->input->get('barang');
		  $d['tanggal'] = $this->input->get('tanggal');
		  $this->load->view('inventory/v_inventory_harga_barang_edit', $d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	public function ajax_list()
	{
		$level=$this->session->userdata('level');		
		$akses= $this->M_global->cek_menu_akses($level, 519);
		$list = $this->M_harga_barang->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $barang) {
			$no++;
			$row = array();
			$row[] = $barang->kodeitem;
			$row[] = $barang->namabarang;
			$row[] = $barang->nama;
			$row[] = number_format($barang->hargajual_net,0,',','.');
			
			if($akses->uedit==1 && $akses->udel==1){
		    $row[] = '<a class="btn btn-sm btn-primary" href="'.base_url("inventory_harga/edit/?barang=".$barang->kodeitem."&tanggal=".$barang->tanggal."").'" title="Edit" ><i class="glyphicon glyphicon-edit"></i> Edit </a>
				      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$barang->kodeitem."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';					
			} else 
			if($akses->uedit==1 && $akses->udel==0){
			$row[] = '<a class="btn btn-sm btn-primary" href="'.base_url("inventory_harga/edit/?barang=".$barang->kodeitem."&tanggal=".$barang->tanggal."").'" title="Edit" ><i class="glyphicon glyphicon-edit"></i> Edit </a>';
				      
            } else 
			if($akses->uedit==0 && $akses->udel==1){
			$row[] = 
				     ' <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$barang->kodeitem."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';						
			} else {
			$row[] = '';	
			}	
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_harga_barang->count_all(),
						"recordsFiltered" => $this->M_harga_barang->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_harga_barang->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}
	
	public function getmargin()
	{
		$data = $this->M_harga_barang->getmarginsetting();		
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		
		$kode = $this->input->post('kode');
		$margin = $this->input->post('margin');
		$partisipasi = $this->input->post('partisipasi');
		$hargagros = $this->input->post('hargagros');
		$diskon = $this->input->post('diskon');
		$harganet = $this->input->post('harganet');
		$hargamodal = $this->input->post('hargamodal');
		$tanggal = $this->input->post('tanggal');
		$barang   = $this->input->post('barang');
		
		$_hargamodal  =  str_replace(',','',$hargamodal);
		
		$jumdata  = count($kode);
		$_tanggal = date('Y-m-d',strtotime($tanggal));
		
		$nourut = 1;
		for($i=0;$i<=$jumdata;$i++)
		{
			$_kode   = $kode[$i];
			$_hargagros  =  str_replace(',','',$hargagros[$i]);
			$_harganet   =  str_replace(',','',$harganet[$i]);
			
			if($_kode!="")
			{
				$this->db->query("delete from inv_barang_harga where kodeitem = '$barang' and kode_customer='$_kode'");
				$data = array(
				  'kodeitem' => $barang,
                  'kode_customer' => $_kode,
				  'hargamodal' => $_hargamodal,	
				  'margin_pr' => $margin[$i],
				  'margin_rp' => $_hargamodal*($margin[$i]/100),
				  'partisipasi_pr' => $partisipasi[$i],
				  'partisipasi_rp' => $_hargamodal*($partisipasi[$i]/100),
				  'harga_gros' => $_hargagros,
				  'diskon_pr' => $diskon[$i],
				  'diskon_rp' => $_hargagros*($diskon[$i]/100),
				  'hargajual_net' => $_harganet,
				  'tanggal' => $_tanggal,
				  'tglrekam' => date('Y-m-d H:i:s'),
				  'userid' => $this->session->userdata('username'),
				);
				
				$this->db->insert('inv_barang_harga', $data);
				
				
			}
			
		}
			
		
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'kdkategori' => $this->input->post('kateg'),
				'kdcbg' => $this->session->userdata('unit'),
				'kodeitem' => $this->input->post('kodeitem'),
				'namabarang' => $this->input->post('namabarang'),
				'stok_min' => $this->input->post('stokmin'),
				'stok_max' => $this->input->post('stokmax'),
				'stok' => $this->input->post('stok'),
				'satuan' => $this->input->post('satuan'),
				'kdgudang' => $this->input->post('gudang'),
				'kdrak' => $this->input->post('rak'),
				'hargajual' => $this->input->post('hargajual'),
				'hargabeli' => $this->input->post('hargabeli'),
				'ppn' => $this->input->post('ppn'),
				'kdmerk' => $this->input->post('merk'),
				
				
			);
		$this->M_harga_barang->update(array('kodeitem' => $this->input->post('kodeitem')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->db->query("delete from inv_barang_harga where kodeitem = '$id'");
		echo json_encode(array("status" => TRUE));
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		
		if($this->input->post('barang') == '')
		{
			$data['inputerror'][] = 'barang';
			$data['error_string'][] = 'Harus Diisi';
			$data['status'] = FALSE;
		}

	
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	
	
	
}

