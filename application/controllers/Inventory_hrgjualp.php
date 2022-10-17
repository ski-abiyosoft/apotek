<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_hrgjualp extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_hargajualp','M_hargajualp');
		$this->load->model('M_barang','M_barang');
		$this->load->model('M_param','M_param');
		$this->session->set_userdata('menuapp', '500');
		$this->session->set_userdata('submenuapp', '523');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{
			$data['merk']= $this->db->get('inv_merk')->result_array();			
			$this->load->view('inventory/v_inventory_hargajualp',$data);
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list()
	{
		$list = $this->M_hargajualp->get_datatables();
		
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
			$row[] = date('d-m-Y',strtotime($item->tglberlaku));			
			$row[] = number_format($item->disc11,0,',','.');
			$row[] = number_format($item->disc12,0,',','.');
			$row[] = number_format($item->disc13,0,',','.');
			$row[] = number_format($item->disc21,0,',','.');
			$row[] = number_format($item->disc22,0,',','.');
			$row[] = number_format($item->disc23,0,',','.');
			$row[] = number_format($item->disc31,0,',','.');
			$row[] = number_format($item->disc32,0,',','.');
			$row[] = number_format($item->disc33,0,',','.');			
			$row[] = 
			     ' <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$item->id."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_hargajualp->count_all(),
						"recordsFiltered" => $this->M_hargajualp->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_hargajualp->get_by_id($id);		
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$tanggal  =$this->input->post('tglberlaku');
		$kodeitem =$this->input->post('kodeitem');
		$satuan   =$this->input->post('satuan');
		$pricelist=$this->input->post('pricelist');
		$disc11   =$this->input->post('disc11');
		$disc12   =$this->input->post('disc12');
		$disc13   =$this->input->post('disc13');
		$disc21   =$this->input->post('disc21');
		$disc22   =$this->input->post('disc22');
		$disc23   =$this->input->post('disc23');
		$disc31   =$this->input->post('disc31');
		$disc32   =$this->input->post('disc32');
		$disc33   =$this->input->post('disc33');
		
		
		$jumdata  = count($kodeitem);
		$_tanggal = date('Y-m-d',strtotime($tanggal));
						
		
		for($i=0;$i<=$jumdata-1;$i++)
		{
			$_item     = $kodeitem[$i];
			$_satuan   = $satuan[$i];
			$_pricelist= $pricelist[$i];
			$_disc11   = $disc11[$i];
			$_disc12   = $disc12[$i];
			$_disc13   = $disc13[$i];
			$_disc21   = $disc21[$i];
			$_disc22   = $disc22[$i];
			$_disc23   = $disc23[$i];
			$_disc31   = $disc31[$i];
			$_disc32   = $disc32[$i];
			$_disc33   = $disc33[$i];
											
			$data = array(
			'tglberlaku'  => $_tanggal,
			'kodeitem'    => $_item,	
			'satuan'      => $_satuan,	
			'pricelist'   => $_pricelist,	
			'disc11'      => $_disc11,	
			'disc12'      => $_disc12,	
			'disc13'      => $_disc13,	
			'disc21'      => $_disc21,	
			'disc22'      => $_disc22,	
			'disc23'      => $_disc23,	
			'disc31'      => $_disc31,	
			'disc32'      => $_disc32,	
			'disc33'      => $_disc33,	
			'kodeuser'    => $this->session->userdata('username'),
			);
			
			$insert = $this->M_hargajualp->save($data);
			
			if(date('Y-m-d')==$_tanggal){
				$hrg1   = $_pricelist - ($_pricelist * ($_disc11/100));
				if($_disc12!=0){
				$hrg1   = $hrg1 - ($hrg1*($_disc12/100));
				}
				if($_disc13!=0){
				$hrg1   = $hrg1 - ($hrg1*($_disc13/100));
				}
				
				$hrg2   = $_pricelist - ($_pricelist * ($_disc21/100));
				if($_disc22!=0){
				$hrg2   = $hrg2 - ($hrg2*($_disc22/100));
				}
				if($_disc23!=0){
				$hrg2   = $hrg2 - ($hrg2*($_disc23/100));
				}
				
				$hrg3   = $_pricelist - ($_pricelist * ($_disc31/100));
				if($_disc32!=0){
				$hrg3   = $hrg3 - ($hrg3*($_disc32/100));
				}
				if($_disc33!=0){
				$hrg3   = $hrg3 - ($hrg3*($_disc33/100));
				}
							
				$data_barang = array(
				'kodeitem'    => $_item,			
				'hargajual1'  => $hrg1,
				'hargajual2'  => $hrg2,
				'hargajual3'  => $hrg3,
				);
				
				$update = $this->M_barang->update(array('kodeitem' => $_item), $data_barang);
							
				
			} 			
		}
		if(date('Y-m-d')==$_tanggal){
			$data_setting = array(
				'tglpro'      => date('Y-m-d'),
				'rubahhrg'    => 'T',			
				);
													
			$this->M_param->updatedata_id($data_setting);
		} else {
			$data_setting = array(
				'tglpro'      => $_tanggal,
				'rubahhrg'    => 'Y',			
			);
											
			$this->M_param->updatedata_id($data_setting);
					
		}	
		echo json_encode(array("status" => TRUE));
			
		
	}

	
	public function ajax_delete($id)
	{
		$this->M_hargajualp->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		
		if($this->input->post('merk') == '')
		{
			$data['inputerror'][] = 'merk';
			$data['error_string'][] = 'Merk belum dipilih';
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
			$param  = explode("~",$kode);
			$merk = $param[0];
			$d11  = $param[1];
			$d12  = $param[2];
			$d13  = $param[3];
			$d21  = $param[4];
			$d22  = $param[5];
			$d23  = $param[6];
			$d31  = $param[7];
			$d32  = $param[8];
			$d33  = $param[9];
			
			$data = $this->db->order_by('kodeitem')->get_where('inv_barang',array('kdmerk'=>$merk,'rumushrgjl'=>'P'))->result();			
			?>			
			<div class="modal-body form">
			<form action="#" id="form" class="form-horizontal">                    
            <div class="form-body">
			<div id="tableContainer" class="tableContainer">
             <table border="0" cellpadding="0" cellspacing="0" width="100%" class="scrollTable table table-striped table-bordered">
			 <thead class="fixedHeader">			
			  <tr>
				<th width="120" style="text-align:center;width:120">Kode Item</th>				
				<th width="300" style="text-align:center">Nama Barang</th>	
				<th width="70"  style="text-align:center">Satuan</th>	
				<th width="105" style="text-align:center">Harga</th>	
				<th width="210" colspan="3" style="text-align:center">Disc. Retail</th>					
				<th width="10"></th>
				<th width="210" colspan="3" style="text-align:center">Disc. Pemborong</th>					
				<th width="10"></th>
				<th width="210" colspan="3" style="text-align:center">Disc. Toko</th>	
				
				<th width="70"></th>
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
			      
				 <td align="center" width="120">					
					<?php echo $row->kodeitem;?></a>					
				 </td>	     
				 <td width="300"><?php echo $row->namabarang;?></td>
				 <td width="70"><?php echo $row->satuan;?></td>
				 <td width="105" align="right"><?php echo number_format($row->pricelist,0,',','.');?></td>
				 <td width="70"><input type="text" name="disc11[]" class="form-control" value="<?php echo $d11;?>"></td>
				 <td width="70"><input type="text" name="disc12[]" class="form-control" value="<?php echo $d12;?>"></td>
				 <td width="73"><input type="text" name="disc13[]" class="form-control" value="<?php echo $d13;?>"></td>
				 <td width="10"></td>
				 <td width="70"><input type="text" name="disc21[]" class="form-control" value="<?php echo $d21;?>"></td>
				 <td width="70"><input type="text" name="disc22[]" class="form-control" value="<?php echo $d22;?>"></td>
				 <td width="73"><input type="text" name="disc23[]" class="form-control" value="<?php echo $d23;?>"></td>
				 <td width="10"></td>
				 <td width="70"><input type="text" name="disc31[]" class="form-control" value="<?php echo $d31;?>"></td>
				 <td width="70"><input type="text" name="disc32[]" class="form-control" value="<?php echo $d32;?>"></td>
				 <td width="73"><input type="text" name="disc33[]" class="form-control" value="<?php echo $d33;?>"></td>				 
				 <td ></td>
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
