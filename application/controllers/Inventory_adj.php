<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_adj extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_invadj');
		$this->load->model('M_barang');
		$this->load->model('M_param');		
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '500');
		$this->session->set_userdata('submenuapp', '526');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{
			$data['merk']= $this->db->get('inv_merk')->result_array();			
			$data['rak']= $this->db->get('inv_rak')->result_array();			
			//$data['pic']= $this->db->get('hrd_karyawan')->result_array();			
			$this->load->view('inventory/v_inventory_adj',$data);
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list()
	{
		$list = $this->M_invadj->get_datatables();
		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $item) {
			$no++;
			$row = array();
			$kode  = $item->kodeitem;
			$nama  = $this->M_global->_namabarang($kode);			
			$row[] = date('d-m-Y',strtotime($item->tanggal));			
			$row[] = $item->kodeitem;
			$row[] = $nama;
			$row[] = $item->satuan;
			$row[] = number_format($item->qty,0,',','.');						
			$row[] = number_format($item->hpp,0,',','.');						
			$row[] = 
			     ' <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$item->id."'".')"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_invadj->count_all(),
						"recordsFiltered" => $this->M_invadj->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_invadj->get_by_id($id);		
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$tanggal  =$this->input->post('tanggal');
		$kodeitem =$this->input->post('kodeitem');
		$satuan   =$this->input->post('satuan');
		$hpp      =$this->input->post('hpp');		
		$qty      =$this->input->post('selisih');
		$qty_opm  =$this->input->post('qtyopm');
		$nilai    =$this->input->post('nilai_selisih');
		
		
		$jumdata  = count($kodeitem);
		$_tanggal = date('Y-m-d',strtotime($tanggal));
						
		$totnilai = 0;
		for($i=0;$i<=$jumdata-1;$i++)
		{
			$totnilai = $totnilai + $nilai[$i];
		    $_kode = $kodeitem[$i];
			
			$data = array(
			'tanggal'     => $_tanggal,
			'kodeitem'    => $kodeitem[$i],
			'satuan'      => $satuan[$i],	
			'hpp'         => $hpp[$i],	
			'hargabeli'   => $hpp[$i],	
			'qty'         => $qty[$i],							
			'kodeuser'    => $this->session->userdata('username'),
			);
			
			$insert = $this->M_invadj->save($data);
			
			
			//
			
			$data_barang = array(
				'kodeitem' => $_kode,			
				'qty'      => $qty_opm[$i],				
			);
				
		    $update = $this->M_barang->update(array('kodeitem' => $_kode), $data_barang);
		}
		
		//jurnal ke gl
		$data=$this->M_global->_LoadProfile();
		foreach($data as $row){			
			$akunpersediaan        = $row->akun_persediaan;
			$akunbiayakerugianlain = $row->akun_biaya_kerugian_lain;
			$akunpendapatanlain    = $row->akun_pendapatan_lain;
		}
		
		if($totnilai>0){
			$data_jurnal = array(
			  'novoucher' => 'ADJ'.date('dmY'),
			  'tanggal' => date('Y-m-d'),
			  'nourut' => 1,
			  'kodeakun' => $akunpersediaan,
			  'debet' => $totnilai,
			  'kredit' => 0,
			  'keterangan' => 'Adjusment Inv. ',
			  'jenis' => 'JU',
			  'userid'   => $this->session->userdata('username'), 				
			);
			$this->M_global->input_data($data_jurnal,'tr_jurnal');
			
			$data_jurnal = array(
			  'novoucher' => 'ADJ'.date('dmY'),
			  'tanggal' => date('Y-m-d'),
			  'nourut' => 2,
			  'kodeakun' => $akunpendapatanlain,
			  'debet' => 0,
			  'kredit' => $totnilai,
			  'keterangan' => 'Adjusment Inv. ',
			  'jenis' => 'JU',
			  'userid'   => $this->session->userdata('username'), 				
			);
			$this->M_global->input_data($data_jurnal,'tr_jurnal');
			
		
		} else {
			$data_jurnal = array(
			  'novoucher' => 'ADJ'.date('dmY'),
			  'tanggal' => date('Y-m-d'),
			  'nourut' => 1,
			  'kodeakun' => $akunbiayakerugianlain,
			  'debet' => $totnilai,
			  'kredit' => 0,
			  'keterangan' => 'Adjusment Inv. ',
			  'jenis' => 'JU',
			  'userid'   => $this->session->userdata('username'), 				
			);
			$this->M_global->input_data($data_jurnal,'tr_jurnal');
			
			$data_jurnal = array(
			  'novoucher' => 'ADJ'.date('dmY'),
			  'tanggal' => date('Y-m-d'),
			  'nourut' => 2,
			  'kodeakun' => $akunpersediaan,
			  'debet' => 0,
			  'kredit' => $totnilai,
			  'keterangan' => 'Adjusment Inv. ',
			  'jenis' => 'JU',
			  'userid'   => $this->session->userdata('username'), 				
			);
			$this->M_global->input_data($data_jurnal,'tr_jurnal');
		}
		
		echo json_encode(array("status" => TRUE));
			
		
	}

	
	public function ajax_delete($id)
	{
		$this->M_invadj->delete_by_id($id);
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
		
		if($this->input->post('rak') == '')
		{
			$data['inputerror'][] = 'rak';
			$data['error_string'][] = 'Rak belum dipilih';
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
		//$this->_validate();
		if(!empty($kode))
		{
			$param  = explode("~",$kode);
			$merk = $param[0];
			$rak  = $param[1];
			$tgl  = $param[2];
			$_tanggal= date('Y-m-d',strtotime($tgl));
			
			$this->db->select('inv_stockopname.*, inv_barang.namabarang');
			$this->db->from('inv_stockopname');
			$this->db->join('inv_barang','inv_stockopname.kodeitem=inv_barang.kodeitem','left');
			$this->db->where(array('kdmerk'=>$merk,'kdrak'=>$rak,'tgl' => $_tanggal));
			$this->db->where('selisih<>0');
			
			$this->db->order_by('inv_stockopname.pic','asc');
			$data = $this->db->get()->result();
			
			?>			
			<div class="modal-body form">
			<form action="#" id="form" class="form-horizontal">                    
            <div class="form-body">
			<div id="tableContainer" class="tableContainer">
             <table border="0" cellpadding="0" cellspacing="0" width="100%" class="scrollTable table table-striped table-bordered">
			 <thead class="fixedHeader">			
			  <tr>
			    <th width="20" style="text-align:center;width:120">No.</th>				
				<th width="120" style="text-align:center;width:120">Kode Item</th>				
				<th width="300" style="text-align:center">Nama Barang</th>	
				<th width="70"  style="text-align:center">Satuan</th>	
				<th width="70"  style="text-align:center">Qty (+/-)</th>					
				<th width="70"  style="text-align:center">Nilai</th>	
				
			  </tr> 
			  </thead>
			<tbody class="scrollContent">  
			<?php							
			$i=1;
			foreach($data as $row)
			{ ?>
			   <input type="hidden" name="kodeitem[]" value=<?php echo $row->kodeitem;?>>
			   <input type="hidden" name="satuan[]" value=<?php echo $row->sat;?>>
			   <input type="hidden" name="kdsubkat[]" value=<?php echo $row->kdsubkat;?>>
			   <input type="hidden" name="qtyopm[]" value=<?php echo $row->qty_opname;?>>
			   <input type="hidden" name="hpp[]" value=<?php echo $row->hpp;?>>
			   <tr>
			     <td width="90" align="center"><?php echo $i;?></td> 
				 <td align="center" width="265">					
					<?php echo $row->kodeitem;?></a>					
				 </td>	     
				 <td width="670"><?php echo $row->namabarang;?></td>
				 <td width="130" align="center"><?php echo $row->sat;?></td>
				 <td width="155"><input type="text" name="selisih[]" class="form-control" value=<?php echo $row->selisih;?>></td>				 
				 <td width="155"><input type="text" name="nilai_selisih[]" class="form-control" value=<?php echo $row->nilai_selisih;?>></td>
				 			 
				 <td ></td>
			   </tr>
			   
			   <?php
			  $i++;
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
