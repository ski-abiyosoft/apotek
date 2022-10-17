<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_po extends CI_Controller {

	
	
	public function __construct()
	{
		
		parent::__construct();
		$this->load->library('form_validation'); 
        $this->load->database(); 
		$this->load->model('M_pembelian');
		$this->load->model('M_supplier');
		$this->load->model('M_barang');
		$this->session->set_userdata('menuapp', '200');
		$this->session->set_userdata('submenuapp', '204');		
	}
	
	public function userdata($nama,$moto,$alamat,$unit,$nojurnal,$tanggal){
        $this->CI->session->set_userdata('nama', $nama);
		$this->CI->session->set_userdata('moto', $moto);
		$this->CI->session->set_userdata('alamat', $alamat);
        $this->CI->session->set_userdata('kode_unit', $unit);
		$this->CI->session->set_userdata('nojurnal', $nojurnal);
		$this->CI->session->set_userdata('tanggal', $tanggal);
				
    }

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{	
			$unit = $this->session->userdata('unit');	
			if(!empty($unit)){
			  $qp ="select kode, nama from ms_unit where kode = '$unit'"; 
			} else {
			  $qp ="select kode, nama from ms_unit order by kode"; 		
			}
			
			$this->load->helper('url');		
			$d['nopo']  = $this->M_global->_autonomor('PO');
			$d['unit']  = $this->db->query($qp);		
			$d['supp']  = $this->db->get('ap_supplier')->result();
            $d['item']  = $this->db->get('inv_barang')->result();			
			$d['addr']  = $this->db->get('ms_identity')->row();			
			
			
			$this->load->view('pembelian/v_pembelian_po',$d);
			} else
		{
			header('location:'.base_url());
		}	
	}
	
	public function next(){
		$d['nosi']  = $this->M_global->_autonomor('SI');			
		$this->load->view('penjualan/v_penjualan_sl2',$d);
	}
	
	
	public function getentry($nomor)
	{
		if(!empty($nomor))
		{			
			$data = $this->db->order_by('id')->get_where('podetail',array('kodepo'=>$nomor))->result();			
			?>			
			<div>
			<form action="#" id="form" class="form-horizontal">                    
            <div class="form-body">
			<div id="tableContainer" class="tableContainer">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="scrollTable tables table-stripeds table-bordereds">
           			
			<tbody class="scrollContent">  
			<?php							
			$i=1;
			foreach($data as $row)
			{ 
			   $harga  = ($row->hargabeli*$row->qtyorder);
			   $namabrg= $this->M_global->_namabarang($row->kodeitem);
			   $id     = $row->id;
			   
			    ?>			   
			   <tr>
			     <td align="center" width="10%">					
					<?php echo $row->kodeitem;?></a>					
				 </td>	     
				 <td width="20%"><?php echo $namabrg;?></td>
				 <td width="6%"><?php echo $row->satuan;?></td>
				 <td width="6%"  align="right"><?php echo $row->qtyorder;?></td>
				 <td width="10%" align="right"><?php echo number_format($row->hargabeli,0,',','.');?></td>
				 <td width="10%" align="right"><?php echo number_format($harga,0,',','.');?></td>
				 <td width="2%"><a class="btn btn-sm btn-danger" onclick="delete_data(<?php echo $id;?>)"><i class="glyphicon glyphicon-trash"></i></a></td>
				 <td width="32%"></td>
				 
				 			 				 
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
	
	public function getakun($kode)
	{
		if(!empty($kode))
		{
		  
			$q = $kode;
			$query = "select * from ms_akun where kodeakun like '%$q%' or namaakun like '%$q%' and tipe=5";
			$data  = $this->db->query($query);
			?>
			
			<table id="myTable">
			  <tr class="header">
				<th style="width:20%;">Kode</th>
				<th style="width:80%;">Nama</th>	
			  </tr> 
			  
			<?php							
			foreach($data->result_array() as $row)
			{ ?>
			   <tr>
				 <td width="50" align="center">
					<a href="#" onclick="post_value('<?php echo $row['kodeakun'];?>','<?php echo $row['namaakun'];?>')">
					
					<?php echo $row['kodeakun'];?></a>
				 </td>	     
				 <td><?php echo $row['namaakun'];?></td>
			   </tr>
			   
			   <?php
			}
			echo "</table>";
		} else
        {
		  echo "";	
		}			
	}
	
	public function ajax_add()
	{
		$this->_validate();
		$kodeitem = $this->input->post('kodeitem');
		$qty      = $this->input->post('qty');
		$data = array(
		        'kodepo'=> $this->input->post('kodepo'),   
				'kodeitem'=> $this->input->post('kodeitem'),
				'satuan'=> $this->input->post('sat'),
				'qtyorder'=> $this->input->post('qty'),
				'hargabeli'  => $this->input->post('harga'),
			);
		$insert = $this->M_pembelian->save_detail($data);
		
		$this->db->query('update inv_barang set po = if(po is null,'.$qty.', po + '.$qty.') where kodeitem = "'.$kodeitem.'"');
		echo json_encode(array("status" => TRUE));
	}
	
	public function ajax_add_promo()
	{		
	    $hpp_promo = $this->M_global->_data_barang($this->input->post('kodeitem_promo'))->hpp;
		$hpp_bonus = $this->M_global->_data_barang($this->input->post('kodeitem_bonus'))->hpp;
	    $data = array(
		        'kodesi'=> $this->input->post('kodesi'),   
				'kodeitem'=> $this->input->post('kodeitem_promo'),
				'satuan'=> $this->input->post('sat_promo'),
				'qtysi'=> $this->input->post('qty_promo'),
				'hpp'=> $hpp_promo,
				'kirim'=> 'T',
				'hargajual'  => $this->input->post('harga_promo')				
			);
		$insert = $this->M_penjualan->save_detail($data);
		$data = array(
		        'kodesi'=> $this->input->post('kodesi'),   
				'kodeitem'=> $this->input->post('kodeitem_bonus'),
				'satuan'=> $this->input->post('sat_bonus'),
				'qtysi'=> $this->input->post('qty_bonus'),
				'kirim'=> 'T',
				'hpp'=> $hpp_bonus,
				'hargajual'  => $this->input->post('harga_bonus')				
			);
		$insert = $this->M_penjualan->save_detail($data);
		echo json_encode(array("status" => TRUE));
	}
	
	public function ajax_add_header()
	{
		$kodepo= $this->input->post('kodepo');
		$supp  = $this->input->post('supp');
		$data  = array(
			'kodepo'=> $this->input->post('kodepo'),   
			'kodesup'=> $supp,   
			'tglpo'=> date('Y-m-d'),
			'dpp'=> $this->input->post('dpp'),
			'typeppn'=> $this->input->post('cppn'),
			'ppn'=> $this->input->post('ppn'),
			'ongkir'=> $this->input->post('ongkir'),
			'namakirim'=> $this->input->post('namae'),
			'alamat1'=> $this->input->post('alamat1e'),
			'alamat2'=> $this->input->post('alamat2e'),
			'kodepos'=> $this->input->post('kdpose'),
			'telp'=> $this->input->post('telpe'),
			'kodeuser' => $this->session->userdata('username'),
		);
		
		$this->M_pembelian->save_header($data);
		$this->update_counter('PO');
		
		echo json_encode(array("status" => TRUE));
	}
	
	public function ajax_edit_kirim()
	{
		$kodepo= $this->input->post('kodepo');
		$data  = array(
			'kodepo'=> $kodepo,   
			'kota'=> $this->input->post('kota'),
			'namakirim'=> $this->input->post('namae'),
			'alamat1'=> $this->input->post('alamat1e'),
			'alamat2'=> $this->input->post('alamat2e'),
			'kodepos'=> $this->input->post('kdpose'),
			'telp'=> $this->input->post('telpe'),
		);
		
		$this->M_pembelian->update(array('kodepo' => $kodepo),$data);	
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
			$data['error_string'][] = 'kode item belum dipilih';
			$data['status'] = FALSE;
		}
		
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	
	public function getakunname($kode)
	{
		if(!empty($kode))
		{			
			$query = "select namaakun from ms_akun where kodeakun = '$kode'";
			$data  = $this->db->query($query);
			foreach($data->result_array() as $row)
			{
              echo $row['namaakun'];				
			}
		} else
		{
		  echo "";	
		}
	}
	
	public function _totalsi($nosi)
	{
		if(!empty($nosi)) {			
			$query = "select sum(qtyorder*hargabeli) as total from podetail where kodepo = '$nosi'";
			$data  = $this->db->query($query)->row();
			return $data->total;
		} else {
		    return 0;	
		}
	}
	
	public function gettotalsi($nosi)
	{
		if(!empty($nosi)) {			
			$query = "select ifnull(sum(qtyorder*hargabeli),0) as total from podetail where kodepo = '$nosi'";
			$data  = $this->db->query($query);
			foreach($data->result_array() as $row)
			{
              echo $row['total'];				
			}
			
		} else {
		  echo "";	
		}
	}
		
		
	public function cetak($param)
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');		
		if(!empty($cek))
		{				  		 
          $d['master_bank'] = $this->db->get("ms_bank");
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  $d['nojurnal']=$param;
		  $d['unit'] = $this->session->userdata('unit');
            
		  $qjurnalh ="select * from tr_jurnalh where nojurnal = '$param'"; 	
		  $data=$this->db->query($qjurnalh);
		  foreach($data->result() as $row){
		  $d['tanggal']=$row->tanggal;
		  }					  
		  
		  $this->load->view('akuntansi/v_akuntansi_jurnal_memo',$d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	public function ajax_delete($id)
	{
		$this->M_pembelian->delete_detail_by_id($id);
		
		echo json_encode(array("status" => TRUE));
	}
	
	public function ajax_kirim($param)
	{
		$data  = explode("~",$param);
		$id    = $data[0];
		$kirim = $data[1];
		 
		$data_kirim = array(
				'kirim' => $kirim,			
		);
				
		$this->M_penjualan->update_kirim(array('id' => $id), $data_kirim);
		echo json_encode(array("status" => TRUE));
	}
	
	public function ajax_delete_all($id)
	{
		$this->M_pembelian->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}
	
	function update_counter($kode){
		$this->M_global->_updatecounter1($kode);
	}
	
	function autonomor($kode){
		echo $this->M_global->_autonomor($kode);
		
	}
	
    public function getsuplier($id)
	{
		$data = $this->M_supplier->get_by_id($id);		
		echo json_encode($data);
	}
	
	public function getproduk($id)
	{
		$data = $this->M_barang->get_by_id($id);		
		echo json_encode($data);
	}
	
	public function getpromo($id)
	{			    
	    $tgl  = date('Y-m-d');			
		$this->db->select('inv_promo.*, inv_barang.namabarang');
        $this->db->join('inv_barang','inv_promo.bnsitem=inv_barang.kodeitem','left');		
		$data = $this->db->get_where('inv_promo',array('inv_promo.kodeitem' => $id, 'tglawal<=' => $tgl,'tglakhir>=' => $tgl ))->row();
		echo json_encode($data);
	}
	
	public function statuspromo($id)
	{			    
	    $tgl  = date('Y-m-d');			
		$this->db->select('count(*) as jumlah');
        $this->db->join('inv_barang','inv_promo.bnsitem=inv_barang.kodeitem','left');		
		$data = $this->db->get_where('inv_promo',array('inv_promo.kodeitem' => $id, 'tglawal<=' => $tgl,'tglakhir>=' => $tgl ))->row();
		echo $data->jumlah;
	}
	
	
}

/* End of file akuntansi_jurnal.php */
/* Location: ./application/controllers/akuntansi_jurnal.php */