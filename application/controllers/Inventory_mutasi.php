<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_mutasi extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_mutasi');
		$this->load->model('M_konversi');
		$this->load->model('M_barang');
		$this->session->set_userdata('menuapp', '500');
		$this->session->set_userdata('submenuapp', '527');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{
			$data['item']= $this->db->get("inv_barang")->result_array();			
			$this->load->view('inventory/v_inventory_mutasi',$data);
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list()
	{
		$list = $this->M_mutasi->get_datatables();
		
		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $item) {
			$no++;
			$row = array();
			$kode1 = $item->kodeitem1;
			$kode2 = $item->kodeitem2;
			$nama1 = $this->M_global->_namabarang($kode1);
			$nama2 = $this->M_global->_namabarang($kode2);
			$row[] = $kode1;
			$row[] = $nama1;
			$row[] = $item->qty1;
			$row[] = $item->satuan1;
			$row[] = $kode2;
			$row[] = $nama2;
			$row[] = $item->konversi;
			$row[] = $item->qty2;
			$row[] = $item->satuan2;
			
			
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$item->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$item->id."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_mutasi->count_all(),
						"recordsFiltered" => $this->M_mutasi->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_mutasi->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$data=$this->M_global->_LoadProfile();
		foreach($data as $row){			
			$akunpersediaantransit = $row->akun_persediaan_transit;
			$akunpersediaan        = $row->akun_persediaan;
			$akunbiayakerugianlain = $row->akun_biaya_kerugian_lain;
			$akunpendapatanlain    = $row->akun_pendapatan_lain;
		}
					
		$kode1 = $this->input->post('kodeitem1');
		$kode2 = $this->input->post('kodeitem2');
		$qty1  = $this->input->post('qty1');
		$qty2  = $this->input->post('qty2');
		$konv  = $this->input->post('konversi');
		
		$this->_validate();
		$data = array(
				'kodeitem1'=> $kode1,
				'kodeitem2'=> $kode2,
				'satuan1'  => $this->input->post('satuan1'),
				'satuan2'  => $this->input->post('satuan2'),				
				'qty1'     => $qty1,
				'konversi' => $konv,
				'qty2'     => $qty2,	
                'userid'   => $this->session->userdata('username'), 				
			);
		$insert = $this->M_mutasi->save($data);
		
        $qtyawal1 = $this->M_barang->get_by_id($kode1)->qty;
		$qtyupdate= $qtyawal1-$qty1;
		$data_barang = array(
				'kodeitem' => $kode1,			
				'qty'      => $qtyupdate,				
		);
				
		$update = $this->M_barang->update(array('kodeitem' => $kode1), $data_barang);
		
		//penambahan ke barang 2
		
		
		//hitung hpp
		
		
		
		$qtyawal2      = $this->M_barang->get_by_id($kode2)->qty;		
		$hpp2          = $this->M_barang->get_by_id($kode2)->hpp;				
		$hpp1          = $this->M_barang->get_by_id($kode1)->hpp;				
		$hargabelikonv = ( $qty1 * $hpp1 ) / $konv;
		$qtyawal       = $qtyawal2 * $hpp2;				
		$qtymutasi     = $qty2 * $hargabelikonv;						
		$saldostock    = $qtyawal + $qtymutasi;
		$hppNew        = $saldostock / ($qtyawal2+$qty2);
		
		$qtyupdate     = $qtyawal2+$qty2;
		$data_barang = array(
				'kodeitem' => $kode2,			
				'qty'      => $qtyupdate,			
                'hpp'      => $hppNew,				
		);
				
		$update = $this->M_barang->update(array('kodeitem' => $kode2), $data_barang);	
		
		

        //jurnal ke buku besar
		//persediaan intransit pada persediaan
				
        $data_jurnal = array(
		  'novoucher' => 'KONV'.date('dmY'),
		  'tanggal' => date('Y-m-d'),
		  'nourut' => 1,
		  'kodeakun' => $akunpersediaantransit,
		  'debet' => $qty1*$hpp1,
		  'kredit' => 0,
		  'keterangan' => 'Mutasi Inv. ',
		  'jenis' => 'JU',
		  'userid'   => $this->session->userdata('username'), 				
		);
		$this->M_global->input_data($data_jurnal,'tr_jurnal');
		
		$data_jurnal = array(
		  'novoucher' => 'KONV'.date('dmY'),
		  'tanggal' => date('Y-m-d'),
		  'nourut' => 2,
		  'kodeakun' => $akunpersediaan,//persediaan
		  'debet' => 0,
		  'kredit' => $qty1*$hpp1,
		  'keterangan' => 'Mutasi Inv. ',
		  'jenis' => 'JU',
		  'userid'   => $this->session->userdata('username'), 				
		);
		$this->M_global->input_data($data_jurnal,'tr_jurnal');
		
		//persediaan pada persediaan intransit
		$data_jurnal = array(
		  'novoucher' => 'KONV'.date('dmY'),
		  'tanggal' => date('Y-m-d'),
		  'nourut' => 3,
		  'kodeakun' => $akunpersediaan,//persediaan
		  'debet' => $qty2*$hargabelikonv,
		  'kredit' => 0,
		  'keterangan' => 'Mutasi Inv. ',
		  'jenis' => 'JU',
		  'userid'   => $this->session->userdata('username'), 				
		);
		$this->M_global->input_data($data_jurnal,'tr_jurnal');
		
		$data_jurnal = array(
		  'novoucher' => 'KONV'.date('dmY'),
		  'tanggal' => date('Y-m-d'),
		  'nourut' => 4,
		  'kodeakun' => $akunpersediaantransit,//persediaan intransit
		  'debet' => 0,
		  'kredit' => $qty2*$hargabelikonv,
		  'keterangan' => 'Mutasi Inv. ',
		  'jenis' => 'JU',
		  'userid'   => $this->session->userdata('username'), 				
		);
		$this->M_global->input_data($data_jurnal,'tr_jurnal');
		
		
		//biaya kerugiaan lain2 pada persediaan
		if($qty2<$konv){
			$data_jurnal = array(
			  'novoucher' => 'KONV'.date('dmY'),
			  'tanggal' => date('Y-m-d'),
			  'nourut' => 5,
			  'kodeakun' => $akunbiayakerugianlain,//biaya kerugiaan lain-lain
			  'debet' => ($konv-$qty2)*$hargabelikonv,
			  'kredit' => 0,
			  'keterangan' => 'Mutasi Inv. ',
			  'jenis' => 'JU',
			  'userid'   => $this->session->userdata('username'), 				
			);
			$this->M_global->input_data($data_jurnal,'tr_jurnal');
			
			$data_jurnal = array(
			  'novoucher' => 'KONV'.date('dmY'),
			  'tanggal' => date('Y-m-d'),
			  'nourut' => 6,
			  'kodeakun' => $akunpersediaan,//persediaan
			  'debet' => 0,
			  'kredit' => ($konv-$qty2)*$hargabelikonv,
			  'keterangan' => 'Mutasi Inv. ',
			  'jenis' => 'JU',
			  'userid'   => $this->session->userdata('username'), 				
			);
			$this->M_global->input_data($data_jurnal,'tr_jurnal');
		}
		
		//persediaan pada pendapatan lain2
		if($qty2>$konv){
			$data_jurnal = array(
			  'novoucher' => 'KONV'.date('dmY'),
			  'tanggal' => date('Y-m-d'),
			  'nourut' => 5,
			  'kodeakun' => $akunpersediaan,
			  'debet' => ($qty2-$konv)*$hargabelikonv,
			  'kredit' => 0,
			  'keterangan' => 'Mutasi Inv. ',
			  'jenis' => 'JU',
			  'userid'   => $this->session->userdata('username'), 				
			);
			$this->M_global->input_data($data_jurnal,'tr_jurnal');
			
			$data_jurnal = array(
			  'novoucher' => 'KONV'.date('dmY'),
			  'tanggal' => date('Y-m-d'),
			  'nourut' => 6,
			  'kodeakun' => $akunpendapatanlain,
			  'debet' => 0,
			  'kredit' => ($qty2-$konv)*$hargabelikonv,
			  'keterangan' => 'Mutasi Inv. ',
			  'jenis' => 'JU',
			  'userid'   => $this->session->userdata('username'), 				
			);
			$this->M_global->input_data($data_jurnal,'tr_jurnal');
		}
		
		
		
		echo json_encode(array("status" => TRUE));
		
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'kodeitem1'=> $this->input->post('kodeitem1'),
				'kodeitem2'=> $this->input->post('kodeitem2'),
				'satuan1'  => $this->input->post('satuan1'),
				'satuan2'  => $this->input->post('satuan2'),				
				'qty1'     => $this->input->post('qty1'),
				'konversi' => $this->input->post('konversi'),	
				'qty2'     => $this->input->post('qty2'),
				
			);
		$this->M_mutasi->update(array('kodeitem1' => $this->input->post('kodeitem1'), 'kodeitem2' => $this->input->post('kodeitem2')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_mutasi->delete_by_id($id);
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
			$data['error_string'][] = 'Nama barang belum dipilih';
			$data['status'] = FALSE;
		}
		
		if($this->input->post('kodeitem2') == '')
		{
			$data['inputerror'][] = 'kodeitem2';
			$data['error_string'][] = 'Nama barang belum dipilih';
			$data['status'] = FALSE;
		}

	
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	
	public function getinfoitem1($id)
	{
		$data = $this->M_konversi->get_by_item1($id);		
		echo json_encode($data);
	}
	
	public function getinfoitem2($id)
	{
		$data = $this->M_konversi->get_by_item2($id);		
		echo json_encode($data);
	}
	
	
	
}

/* End of file master_data.php */
/* Location: ./application/controllers/master_data.php */