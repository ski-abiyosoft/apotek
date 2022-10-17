<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_hrgjualm extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_hargajualm','M_hargajualm');
		$this->load->model('M_barang','M_barang');
		$this->load->model('M_param','M_param');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{
			$data['merk']= $this->db->get('inv_merk')->result_array();			
			$this->load->view('inventory/v_inventory_hargajualm',$data);
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list()
	{
		$list = $this->M_hargajualm->get_datatables();
		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $item) {
			$no++;
			$row = array();
			$kode  = $item->kodeitem;
			$nama  = $this->M_global->_namabarang($kode);			
			$row[] = $item->kodeitem;
			$row[] = $nama;
			$row[] = $item->satuan;			
			$row[] = number_format($item->pricelist,0,',','.');
			$row[] = date('d-m-Y',strtotime($item->tglhrgjualm));
			$row[] = number_format($item->hargajual1,0,',','.');
			$row[] = number_format($item->hargajual2,0,',','.');
			$row[] = number_format($item->hargajual3,0,',','.');
			
			
			$row[] = 
			     ' <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$item->id."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_hargajualm->count_all(),
						"recordsFiltered" => $this->M_hargajualm->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_hargajualm->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		
		$kodeitem=$this->input->post('kodeitem');
		$satuan=$this->input->post('satuan');
		$pricelist=$this->input->post('pricelist');
		$harga1  =$this->input->post('harga1');
		$harga2  =$this->input->post('harga2');
		$harga3  =$this->input->post('harga3');
		$tanggal =$this->input->post('tglberlaku');
		
		$jumdata  = count($kodeitem);
		$_tanggal = date('Y-m-d',strtotime($tanggal));
						
		
		for($i=0;$i<=$jumdata-1;$i++)
		{
			$_item   = $kodeitem[$i];
			$_satuan = $satuan[$i];
			$_pricelist= $pricelist[$i];
			$_harga1 = $harga1[$i];
			$_harga2 = $harga2[$i];
			$_harga3 = $harga3[$i];
						
			$data = array(
			'kodeitem'    => $_item,	
			'satuan'      => $_satuan,	
			'pricelist'   => $_pricelist,	
			'tglhrgjualm' => $_tanggal,
			'hargajual1'  => $_harga1,
			'hargajual2'  => $_harga2,
			'hargajual3'  => $_harga3,
			'kodeuser'    => $this->session->userdata('username'),
			);
			
			$insert = $this->M_hargajualm->save($data);
			
			if(date('Y-m-d')==$_tanggal){
			$data_barang = array(
			'kodeitem'    => $_item,			
			'hargajual1'  => $_harga1,
			'hargajual2'  => $_harga2,
			'hargajual3'  => $_harga3,
			);
			
						
			$update = $this->M_barang->update(array('kodeitem' => $_item), $data_barang);
			
			}
			
		}
		if(date('Y-m-d')==$_tanggal){
			$data_setting = array(
				'tglhargajualm' => date('Y-m-d'),
				'rubahhrgm'     => 'T',			
				);
													
			$this->M_param->updatedata_id($data_setting);
		} else {
			$data_setting = array(
				'tglhargajualm'=> $_tanggal,
				'rubahhrgm'    => 'Y',			
			);
											
			$this->M_param->updatedata_id($data_setting);
					
		}	
		echo json_encode(array("status" => TRUE));
			
		
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'kodeitem' => $this->input->post('kodeitem'),
				'satuan1'  => $this->input->post('satuan1'),
				'satuan2'  => $this->input->post('satuan2'),
				'satuan3'  => $this->input->post('satuan3'),
				'satuan4'  => $this->input->post('satuan4'),
				'qty1'     => $this->input->post('qty1'),
				'qty2'     => $this->input->post('qty2'),
				'qty3'     => $this->input->post('qty3'),
				'qty4'     => $this->input->post('qty4')
				
			);
		$this->M_hargajualm->update(array('kodeitem' => $this->input->post('kodeitem')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_hargajualm->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		
		if($this->input->post('kodeitem') == '')
		{
			$data['inputerror'][] = 'kodeitem';
			$data['error_string'][] = 'Nama barang belum dipilih';
			$data['status'] = FALSE;
		}

	
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	
	public function getitem($kode)
	{
		if(!empty($kode))
		{
			$data = $this->db->order_by('kodeitem')->get_where('inv_barang',array('kdmerk'=>$kode,'rumushrgjl'=>'M'))->result();			
			?>
			<div class="modal-body form">
			<form action="#" id="form" class="form-horizontal">                    
            
			<div class="form-body" > 			
			<div id="tableContainer" class="tableContainer">
             <table border="0" cellpadding="0" cellspacing="0" width="100%" class="scrollTable table table-striped table-bordered">
			 <thead class="fixedHeader">
			  <tr>
			    <th style="text-align:center;width:100px">Kode Item</th>				
				<th style="text-align:center;width:350px">Nama Barang</th>	
				<th style="text-align:center;width:70px">Satuan</th>	
				<th style="text-align:center;width:120px">Harga</th>	
				<th style="text-align:center;width:120px">Hrg. Retail</th>					
				<th style="text-align:center;width:120px">Hrg. Pemborong</th>					
				<th style="text-align:center;width:118px">Hrg. Toko</th>	
			  </tr> 
			 </thead> 
			 
			<tbody class="scrollContent">
			<?php							
			foreach($data as $row)
			{ ?>
			   <input type="hidden" name="kodeitem[]" value=<?php echo $row->kodeitem;?>>
			   <input type="hidden" name="satuan[]" value=<?php echo $row->satuan;?>>
			   <input type="hidden" name="pricelist[]" value=<?php echo $row->pricelist;?>>
			   <tr>
				 <td style="width:137px" align="center">					
					<?php echo $row->kodeitem;?></a>
				 </td>	     
				 <td style="width:483px"><?php echo $row->namabarang;?></td>
				 <td width="97"><?php echo $row->satuan;?></td>
				 <td width="162" align="right"><?php echo number_format($row->pricelist,0,',','.');?></td>
				 <td width="162"><input type="text" name="harga1[]" class="form-control" value="0"></td>
				 <td width="162"><input type="text" name="harga2[]" class="form-control" value="0"></td>
				 <td width="150"><input type="text" name="harga3[]" class="form-control" value="0"></td>
				 

			   </tr>
			   
			   <?php
			}
			
			echo "</tbody>";
			echo "</table>";
			echo "</div>";
			
			echo "</form>";
			echo "</div>";
			
		} else
        {
		  echo "";	
		}			
	}
			
}
