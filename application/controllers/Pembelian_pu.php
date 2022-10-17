<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_pu extends CI_Controller {

	
	
	public function __construct()
	{
		
		parent::__construct();
		$this->load->library('form_validation'); 
        $this->load->database(); 
		$this->load->model('M_pembelian');
		$this->load->model('M_supplier');
		$this->load->model('M_barang');
		$this->session->set_userdata('menuapp', '300');
		$this->session->set_userdata('submenuapp', '323');		
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
			$d['unit']  = $this->db->query($qp);		
			$d['supp']  = $this->db->get('ap_supplier')->result();
            $d['item']  = $this->db->get('inv_barang')->result();			
			$d['addr']  = $this->db->get('ms_identity')->row();		
            $d['po']    = $this->db->get('pofile')->result();				
			
			
			$this->load->view('pembelian/v_pembelian_pu',$d);
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
			   
			   $namabrg= $this->M_global->_namabarang($row->kodeitem);
			   $qtytrm = $row->qtyorder-$row->qtykirim;
			   $harga  = ($row->hargabeli*$qtytrm);
			   $id     = $row->id;
			   
			    ?>			   
			   <input type="hidden" name="kodeitem[]" value=<?php echo $row->kodeitem;?>>	
			   <input type="hidden" name="qtypo[]" value=<?php echo $row->qtyorder;?>>
			   <input type="hidden" name="satuan[]" value=<?php echo $row->satuan;?>>
			   <input type="hidden" name="hargabeli[]" value=<?php echo $row->hargabeli;?>>
			   
			   <tr>
			     <td align="center" width="10%">					
					<?php echo $row->kodeitem;?></a>
					
				 </td>	     
				 <td width="20%"><?php echo $namabrg;?></td>
				 <td width="6%"><?php echo $row->satuan;?></td>
				 <td width="6%"  align="right"><?php echo $row->qtyorder;?></td>
				 <td width="10%" align="right"><?php echo number_format($row->hargabeli,0,',','.');?></td>
				 <td width="6%"  align="right"><?php echo $row->qtykirim;?></td>
				 <td width="6%"  align="right" size="2"><input style="text-align:right" type="text" class="form-controls input-xsmall" name="qtyterima[]" value="<?php echo $qtytrm;?>"></td>
				 <td width="8%" align="right"><?php echo number_format($harga,0,',','.');?></td>
				 <td width="27%"></td>
				 
				 			 				 
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
		$data=$this->M_global->_LoadProfile();
		foreach($data as $row){			
				$akunpenjualan   = $row->akun_penjualan;
				$akunppn         = $row->akun_ppn;
				$akunongkir      = $row->akun_ongkir;
				$akunuangmuka    = $row->akun_uangmuka;
				$akunhpp         = $row->akun_hpp;
				$akunpersediaan  = $row->akun_persediaan;
				$akunhutang      = $row->akun_hutang;				
				
				$margin1         = $row->margin1;
				$margin2         = $row->margin2;
				$margin3         = $row->margin3;				
		}
		
		$kodepo    = $this->input->post('kodepo');
		$datapo    = $this->db->get_where('pofile',array('kodepo' => $kodepo))->row();		
		$supp      = $datapo->kodesup;
		$tipeppn   = $datapo->typeppn;
		$kodepu    = $this->input->post('nopu');
		
		$kodeitem  = $this->input->post('kodeitem');
		$qtyterima = $this->input->post('qtyterima');
		$qtypo     = $this->input->post('qtypo');
		$satuan    = $this->input->post('satuan');
		$hargabeli = $this->input->post('hargabeli');
		$uangmuka  = $this->input->post('uangmuka');
		
		
		$dpp       = $this->input->post('dpp');
		$ppn       = $this->input->post('ppn');
		$ongkir    = $this->input->post('ongkir');
		
		$total     = $dpp+$ppn+$ongkir;
		$total_beli= $dpp+$ppn+$uangmuka;
		
		
		$jumdata  = count($kodeitem);
		
		for($i=0;$i<=$jumdata-1;$i++)
		{	
            if ($qtypo[$i]==$qtyterima[$i]){
			  $bof = 'C';	
			} else {
			  $bof = '';	
			}	 
			
			$jumlah = $qtyterima[$i];
			$item   = $kodeitem[$i];
			$sat    = $satuan[$i];
			$hrgbeli= $hargabeli[$i];
			
			
			
			$this->db->query('update podetail set bof = "'.$bof.'", qtykirim= if(qtykirim is null, '.$jumlah.',qtykirim + '.$jumlah.') where kodepo = "'.$kodepo.'" and kodeitem = "'.$item.'"');
			
			$data_pu = array(
			'kodepu'      => $kodepu,
			'kodeitem'    => $item,
			'qtypu'       => $jumlah,
			'satuan'      => $sat,	
			'hargabeli'   => $hrgbeli,
			'disc'        => 0,				
			);
			
			$this->db->insert('pudetail', $data_pu);
			
			if($tipeppn=='E'){
				$share_ongkir  = (((($jumlah * $hrgbeli) / $dpp) * (100/100)) * $ongkir ) / $jumlah;
				$hrgbeli_baru  = ($hrgbeli * (110/100)) + $share_ongkir;
			} else 
			if($tipeppn=='I'){
				$share_ongkir  = (((($jumlah * ($hrgbeli *10/11)) / $dpp) * (100/100)) * $ongkir ) / $jumlah;
				$hrgbeli_baru  = $hrgbeli + $share_ongkir;
			} else 	
			if($tipeppn=='T'){
				$share_ongkir  = (((($jumlah * ($hrgbeli *10/11)) / $dpp) * (100/100)) * $ongkir ) / $jumlah;
				$hrgbeli_baru  = $hrgbeli + $share_ongkir;
			} 

			$databarang = $this->db->get_where('inv_barang',array('kodeitem' => $item))->row();
			
			$hpp = (( $databarang->qty * $databarang->hpp ) + ( $jumlah * $hrgbeli_baru )) / ( $databarang->qty + $jumlah );
			$newqty = $databarang->qty + $jumlah;
			$ponew  = $databarang->po - $jumlah;
			
			$data_barang = array(
			   'hpp' => $hpp,
			   'qty' => $newqty,
			   'hargabeliakhir' => $hrgbeli_baru,
			   'po' => $ponew,
			   'tglbeliakhir' =>  date('Y-m-d',strtotime($this->input->post('tanggal')))	
			);
			
			$this->db->update('inv_barang', $data_barang, array('kodeitem' => $item));
			$databarang = $this->db->get_where('inv_barang',array('kodeitem' => $item))->row();
			
            if( $databarang->rumushrgjl == 'N' ){
			  $data_barang = array(
			   'hargajual1' => $databarang->hargabeliakhir * (100+ $margin1)/100,
			   'hargajual2' => $databarang->hargabeliakhir * (100+ $margin2)/100,
			   'hargajual3' => $databarang->hargabeliakhir * (100+ $margin3)/100,
			   'margin1'    => $margin1,
			   'margin2'    => $margin2,
			   'margin3'    => $margin3
			   );
			
			   $this->db->update('inv_barang', $data_barang, array('kodeitem' => $item));
			}			
			
			
			
		}
		
		
		$top    = $this->db->get_where('ap_supplier',array('kode' => $supp))->row()->top;
		
        //purchasing		
		$data  = array(
			    'kodecbg'=>$this->session->userdata('unit'),
		        'kodesup'=> $supp,
				'tglpu'=> date('Y-m-d',strtotime($this->input->post('tanggal'))),				
				'kodepu'=> $kodepu,   
				'kodepo'=> $kodepo,   
				'tgljthtempo'=> date('Y-m-d',strtotime('+'.$top.' days',strtotime($this->input->post('tanggal')))),								
				'namakirim'=> $datapo->namakirim,
				'alamat1'=> $datapo->alamat1,
				'alamat2'=> $datapo->alamat2,
				'kota'=> $datapo->kota,				
				'kodepos'=> $datapo->kodepos,
				'telp'=> $datapo->telp,
				'typeppn'=> $datapo->typeppn,
				'dpp'=> $this->input->post('dpp'),
				'ppn'=> $this->input->post('ppn'),
				'ongkir'=> $this->input->post('ongkir'),				
				'kodeuser' => $this->session->userdata('username'),
			);
		$this->db->insert('pufile',$data);
		
		//apbyr
		if($uangmuka>$total){
			$byr1 = $total;
			$kodebyr1 = $datapo->kodetrans;
			$os = 0;
		} else 
		if($uangmuka<$total){
			$byr1 = $uangmuka;
			$kodebyr1 = $datapo->kodetrans;
			$os = $total - $uangmuka;
		} else 
		if($uangmuka==0){	
	        $os = $total;			
			$kodebyr1='';
			$byr1 = 0;
		}	
		
		$data  = array(
			    'kodesup'=> $supp,
				'kodepu'=> $kodepu,   
				'tanggal'=> date('Y-m-d',strtotime($this->input->post('tanggal'))),	
                'tgljthtempo'=> date('Y-m-d',strtotime('+'.$top.' days',strtotime($this->input->post('tanggal')))),				
				'jumlah'=> $total,
				'bayar1'=> $byr1,
				'kodebyr1'=> $kodebyr1,
				'outstanding'=> $os,				
			);
		$this->db->insert('aptrn',$data);
		
		//no. 12
		$this->db->query('update ap_supplier set saldojln= if(saldojln is null, '.$total.',saldojln + '.$total.') where kode = "'.$supp.'"');	
		$this->db->query('update ap_supplier set jmlbelithn= if(jmlbelithn is null, '.$total.',jmlbelithn + '.$total.') where kode = "'.$supp.'"');	
		$this->db->query('update ap_supplier set tglbeliakhir = "'.date('Y-m-d',strtotime($this->input->post('tanggal'))).'" where kode = "'.$supp.'"');	
		
		//no. 13
		if($uangmuka > $total){
		   $this->db->query('update apbyr set sisa = sisa - '.$total.' where kodebyr = "'.$datapo->kodetrans.'"');		
		} else 
		if($uangmuka < $total){
		   $this->db->query('update apbyr set sisa = 0 where kodebyr = "'.$datapo->kodetrans.'"');		
		}	
		
		
			
			
		
		
		//create jurnal
		$data_jurnal = array(
			  'novoucher' => $kodepu,
			  'tanggal' => date('Y-m-d',strtotime($this->input->post('tanggal'))),
			  'nourut' => 1,
			  'kodeakun' => $akunpersediaan,
			  'debet' => $total,
			  'kredit' => 0,
			  'keterangan' => 'Pembelian ',
			  'jenis' => 'JU',
			  'userid'   => $this->session->userdata('username'), 				
			);
		$this->db->insert('tr_jurnal', $data_jurnal);
        $data_jurnal = array(
			  'novoucher' => $kodepu,
			  'tanggal' => date('Y-m-d',strtotime($this->input->post('tanggal'))),
			  'nourut' => 2,
			  'kodeakun' => $akunhutang,
			  'debet' => 0,
			  'kredit' => $total,
			  'keterangan' => 'Pembelian ',
			  'jenis' => 'JU',
			  'userid'   => $this->session->userdata('username'), 				
			);
		$this->db->insert('tr_jurnal', $data_jurnal);
		
		
		
		
		if($uangmuka!=0){
        $data_jurnal = array(
			  'novoucher' => $kodepu,
			  'tanggal' => date('Y-m-d',strtotime($this->input->post('tanggal'))),
			  'nourut' => 3,
			  'kodeakun' => $akunuangmuka,
			  'debet' => $uangmuka,
			  'kredit' => 0,
			  'keterangan' => 'UM Pembelian ',
			  'jenis' => 'JU',
			  'userid'   => $this->session->userdata('username'), 				
			);
		$this->db->insert('tr_jurnal', $data_jurnal);		
		
		$data_jurnal = array(
			  'novoucher' => $kodepu,
			  'tanggal' => date('Y-m-d',strtotime($this->input->post('tanggal'))),
			  'nourut' => 4,
			  'kodeakun' => $akunhutang,
			  'debet' => 0,
			  'kredit' => $uangmuka,
			  'keterangan' => 'UM Pembelian ',
			  'jenis' => 'JU',
			  'userid'   => $this->session->userdata('username'), 				
			);
		$this->db->insert('tr_jurnal', $data_jurnal);		
				
		}
				
		
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
	
	public function getpo($po)
	{   
        $data = $this->db->get_where('pofile',array('kodepo' => $po))->row();		
		echo json_encode($data);
	}
	
	public function getum( $sup )
	{   
        $data = $this->db->get_where('ap_supplier',array('kode' => $sup))->row();		
		echo json_encode($data);
	}
	
	
}

/* End of file akuntansi_jurnal.php */
/* Location: ./application/controllers/akuntansi_jurnal.php */