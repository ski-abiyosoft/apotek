<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kasir_tunai extends CI_Controller {

	
	
	public function __construct()
	{
		
		parent::__construct();
		$this->load->library('form_validation'); 
        $this->load->database(); 
		$this->load->helper('simkeu_vou');		
		$this->load->model('M_kasir');
		$this->load->model('M_penjualan');
		$this->load->model('M_customer');
		$this->load->model('M_barang');
		$this->session->set_userdata('menuapp', '800');
		$this->session->set_userdata('submenuapp', '810');		
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
			$d['nosi']  = $this->M_global->_autonomor('SI');
			$d['unit']  = $this->db->query($qp);		
			$d['cust']  = $this->db->get('ar_customer')->result();
			$d['bank']  = $this->db->get_where('ms_bank',array('bank_jenis' => 'B'))->result();
			$d['kas']   = $this->db->get_where('ms_bank',array('bank_jenis' => 'K'))->result();
            $d['dafsi']  = $this->db->get_where('sihdrfile',array('kasirflag' => 'N'))->result();			
			
			$this->load->view('kasir/v_kasir_tunai',$d);
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
			$data = $this->db->order_by('id')->get_where('sidetail',array('kodesi'=>$nomor))->result();			
			?>			
			<div id="tableContainer" class="tableContainer CSSTableGenerators">            		
            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="scrollTable table table-stripeds table-bordereds">
           			
			<tbody class="scrollContent">  
			<?php							
			$i=1;
			foreach($data as $row)
			{ 
			   $harga  = ($row->hargajual*$row->qtysi)-(($row->hargajual*$row->qtysi)*($row->disc/100));
			   $namabrg= $this->M_global->_namabarang($row->kodeitem);
			   $id     = $row->id;
			   
			    ?>			   
			   <tr>
			     <td align="center" width="10%">					
					<?php echo $row->kodeitem;?></a>					
				 </td>	     
				 <td width="20%"><?php echo $namabrg;?></td>
				 <td width="6%"><?php echo $row->satuan;?></td>
				 <td width="6%"  align="right"><?php echo $row->qtysi;?></td>
				 <td width="10%" align="right"><?php echo number_format($row->hargajual,0,',','.');?></td>
				 <td width="6%"  align="right"><?php echo $row->disc;?></td>
				 <td width="10%" align="right"><?php echo number_format($harga,0,',','.');?></td>
				 <td width="2%"><input name="sj" type="checkbox" onclick="update_data(<?php echo $id;?>,this.checked)" <?php if($row->kirim=='Y'){echo 'checked';}?> </td>
				 <!--td width="0%"></td-->
				 			 				 
			   </tr>
			   
			   <?php
			  $i++;
			}
			echo "</tbody>";
			echo "</table>";
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
			}
		
		    if($this->input->post('namabank')!=""){
			$akunbank = $this->db->get_where('ms_bank',array('bank_kode' => $this->input->post('namabank')))->row()->bank_kodeakun;
			} else {
			$akunbank = "";	
			}
			
			if($this->input->post('kas')!=""){
			$akunkas = $this->db->get_where('ms_bank',array('bank_kode' => $this->input->post('kas')))->row()->bank_kodeakun;
			} else {
			$akunkas = "";	
			}
				
		    $subtotal = $this->input->post('subtotal');
            $total = $this->input->post('total');
			$umuka = $this->input->post('uangmuka');
			$subtotal = str_replace('.','',$subtotal);
            $total = str_replace('.','',$total);
			$umuka = str_replace('.','',$umuka);
            $sisaum= $umuka-$total;			
			$diskon=$this->input->post('disc');
			$penjualan = ($subtotal-$diskon)*(10/11);
			$sisa = $this->input->post('sisa');
			$tunai = $this->input->post('tunai');
			
		    $kodesi = $this->input->post('noinv');
			$data  = array(
			    'kodecbg'=>$this->session->userdata('unit'),
		        'kodesumber'=> $this->input->post('noinv'),
				'kodetrans'=> 'KM',   
				'kodekasbank'=> $this->input->post('kas'),
				'tglbayar'=> date('Y-m-d',strtotime($this->input->post('tanggal'))),
				'dbcr'=> 'D',
				'jumlah'=> $total,
				'jmlbayar'=> $this->input->post('tunai')+$umuka,
				'kodekasbank1'=> $this->input->post('namabank'),
                'jmlbayar1'=> $this->input->post('bank'),
				'nokartu'=> $this->input->post('nokartu'),
				'kembali'=> $this->input->post('sisa'),
				'ket'=> '',
				'closedate'=> 'N',
				'jamclosed'=> date('H:m'),
				'kodeuser' => $this->session->userdata('username'),
			);
		
		$this->M_kasir->save_data($data);
					
		$this->db->update('sihdrfile', array('kasirflag' => 'Y'), array('kodesi' => $kodesi));
		if($umuka>0){
		  $this->db->update('ar_customer', array('saldoawal' => $sisaum), array('kode' => $this->input->post('kodecust')));
		}
		
		//update ke tabel ar_customer
		
		$this->db->query('update ar_customer set jmljualthn= jmljualthn + '.$total.' where kode = "'.$this->input->post('kodecust').'"');
		
		
		
		//update item barang
		$data = $this->db->order_by('id')->get_where('sidetail',array('kodesi'=>$kodesi))->result();	
		$hpp  = 0;
		foreach($data as $row) {
			$kodeitem = $row->kodeitem;
			$qty      = $row->qtysi;
			$hpp      = $hpp + ($row->hpp * $qty);
		
		    $this->db->query('update inv_barang set qty = qty - '.$qty.' where kodeitem = "'.$kodeitem.'"');
			//insert to intrn (inadj)
			$data_trn = array(
			'tanggal'     => date('Y-m-d',strtotime($this->input->post('tanggal'))),
			'kodeitem'    => $kodeitem,
			'kodetrn'     => $kodesi,
			'satuan'      => $row->satuan,	
			'hpp'         => $row->hpp,	
			'hargabeli'   => 0,
			'hargajual'   => $row->hargajual,	
			'qty'         => $qty*-1,							
			'kodeuser'    => $this->session->userdata('username'),
			);
			
			$this->db->insert('inv_adj', $data_trn);
		}
		
		//update kasir
		$this->db->query('update ms_kasir set saldojln= saldojln + '.$total.' where kdkas = "'.$this->input->post('kas').'"');
		
		//update saldo kasbank
		$this->db->query('update ms_banksaldo set penerimaan= penerimaan + '.$total.' where bank_kode = "'.$this->input->post('kas').'"');
		
		//create jurnal
		$data_jurnal = array(
			  'novoucher' => $kodesi,
			  'tanggal' => date('Y-m-d',strtotime($this->input->post('tanggal'))),
			  'nourut' => 1,
			  'kodeakun' => $akunpenjualan,
			  'debet' => 0,
			  'kredit' => $penjualan,
			  'keterangan' => 'Penjualan ',
			  'jenis' => 'JU',
			  'userid'   => $this->session->userdata('username'), 				
			);
		$this->db->insert('tr_jurnal', $data_jurnal);
        $data_jurnal = array(
			  'novoucher' => $kodesi,
			  'tanggal' => date('Y-m-d',strtotime($this->input->post('tanggal'))),
			  'nourut' => 2,
			  'kodeakun' => $akunppn,
			  'debet' => 0,
			  'kredit' => $penjualan*0.1,
			  'keterangan' => 'Penjualan ',
			  'jenis' => 'JU',
			  'userid'   => $this->session->userdata('username'), 				
			);
		$this->db->insert('tr_jurnal', $data_jurnal);
		if($this->input->post('ongkir')!=0){
        $data_jurnal = array(
			  'novoucher' => $kodesi,
			  'tanggal' => date('Y-m-d',strtotime($this->input->post('tanggal'))),
			  'nourut' => 3,
			  'kodeakun' => $akunongkir,
			  'debet' => 0,
			  'kredit' => $this->input->post('ongkir'),
			  'keterangan' => 'Penjualan ',
			  'jenis' => 'JU',
			  'userid'   => $this->session->userdata('username'), 				
			);
		$this->db->insert('tr_jurnal', $data_jurnal);
		}
		
		if($umuka!=0){
        $data_jurnal = array(
			  'novoucher' => $kodesi,
			  'tanggal' => date('Y-m-d',strtotime($this->input->post('tanggal'))),
			  'nourut' => 4,
			  'kodeakun' => $akunuangmuka,
			  'debet' => $umuka,
			  'kredit' => 0,
			  'keterangan' => 'Penjualan ',
			  'jenis' => 'JU',
			  'userid'   => $this->session->userdata('username'), 				
			);
		$this->db->insert('tr_jurnal', $data_jurnal);		
		}
		
		if($this->input->post('tunai')!=0){
		$data_jurnal = array(
			  'novoucher' => $kodesi,
			  'tanggal' => date('Y-m-d',strtotime($this->input->post('tanggal'))),
			  'nourut' => 5,
			  'kodeakun' => $akunkas,
			  'debet' => $tunai - $sisa,
			  'kredit' => 0,
			  'keterangan' => 'Penjualan ',
			  'jenis' => 'JU',
			  'userid'   => $this->session->userdata('username'), 				
			);
		$this->db->insert('tr_jurnal', $data_jurnal);
		}
		
		if($this->input->post('bank')!=0){
		$data_jurnal = array(
			  'novoucher' => $kodesi,
			  'tanggal' => date('Y-m-d',strtotime($this->input->post('tanggal'))),
			  'nourut' => 6,
			  'kodeakun' => $akunbank,
			  'debet' => $this->input->post('bank'),
			  'kredit' => 0,
			  'keterangan' => 'Penjualan ',
			  'jenis' => 'JU',
			  'userid'   => $this->session->userdata('username'), 				
			);
		$this->db->insert('tr_jurnal', $data_jurnal);
		}
		
		//hpp pada persediaan
		$data_jurnal = array(
			  'novoucher' => $kodesi,
			  'tanggal' => date('Y-m-d',strtotime($this->input->post('tanggal'))),
			  'nourut' => 7,
			  'kodeakun' => $akunhpp,
			  'debet' => $hpp,
			  'kredit' => 0,
			  'keterangan' => 'Penjualan ',
			  'jenis' => 'JU',
			  'userid'   => $this->session->userdata('username'), 				
			);
		$this->db->insert('tr_jurnal', $data_jurnal);
		$data_jurnal = array(
			  'novoucher' => $kodesi,
			  'tanggal' => date('Y-m-d',strtotime($this->input->post('tanggal'))),
			  'nourut' => 8,
			  'kodeakun' => $akunpersediaan,
			  'debet' => 0,
			  'kredit' => $hpp,
			  'keterangan' => 'Penjualan ',
			  'jenis' => 'JU',
			  'userid'   => $this->session->userdata('username'), 				
			);
		$this->db->insert('tr_jurnal', $data_jurnal);	
		
		
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
			$query = "select sum(qtysi*hargajual-(qtysi*hargajual*(disc/100))) as total from sidetail where kodesi = '$nosi'";
			$data  = $this->db->query($query)->row();
			return $data->total;
		} else {
		    return 0;	
		}
	}
	
	public function gettotalsi($nosi)
	{
		if(!empty($nosi)) {			
			$query = "select sum(qtysi*hargajual-(qtysi*hargajual*(disc/100))) as total from sidetail where kodesi = '$nosi'";
			$data  = $this->db->query($query);
			foreach($data->result_array() as $row)
			{
              echo $row['total'];				
			}
			
		} else {
		  echo "";	
		}
	}
		
		
	
	
	public function ajax_delete($id)
	{
		$this->M_penjualan->delete_detail_by_id($id);
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
		$this->M_penjualan->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}
	
	function update_counter($kode){
		$this->M_global->_updatecounter1($kode);
	}
	
	function autonomor($kode){
		echo $this->M_global->_autonomor($kode);
		
	}
	
    public function getcustomer($id)
	{
		$data = $this->M_customer->get_by_id($id);		
		echo json_encode($data);
	}
	
	public function getinv($id)
	{
		$this->db->select('sihdrfile.*, ar_customer.nama, ar_customer.alamat1, ar_customer.alamat2, ar_customer.saldoawal');
		$this->db->join('ar_customer','sihdrfile.kodecust=ar_customer.kode','left');
		$data = $this->db->get_where('sihdrfile',array('kodesi' => $id))->row();
		echo json_encode($data);
	}
	
	public function cetak_inv( $kodesi )
	{		
		$cek = $this->session->userdata('level');
        $nofaktur = $kodesi;
        $nama = 'Cash'; 		
		if(!empty($cek))
		{		    
			$data = $this->db->select('inv_barang.namabarang, sidetail.qtysi as qty,sidetail.satuan')->join('inv_barang','sidetail.kodeitem=inv_barang.kodeitem','left')->order_by('sidetail.kodeitem')->get_where('sidetail',array('kodesi'=>$kodesi ,'kirim'=>'T'))->result();	
			
		    $pdf=new simkeu_vou();		
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->setfont('Times','',10);
			$pdf->SetWidths(array(20,5,25,35));
			$pdf->SetAligns(array('L','C','L','R'));			
			$judul0=array('No. Faktur',':',$nofaktur,'');
			$judul1=array('Nama',':',$nama,'AMBIL');			
			$border= array('','','','');
		    $align = array('L','C','L','R');		    
		    $pdf->FancyRow($judul0,$border,$align);
			$border= array('B','B','B','B');
            $pdf->FancyRow($judul1,$border,$align);
            $border= array('','','','');
						
			$pdf->SetWidths(array(65, 10,10));
			$pdf->SetAligns(array('L','L','L'));
			$pdf->setfont('Times','',10);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');			
			$nourut = 1;

			foreach($data as $db)
			{
			  $pdf->FancyRow(array($db->namabarang, $db->qty, $db->satuan),$border,$align);
			  $nourut++;
			}
						
			$pdf->AliasNbPages();
            $pdf->IncludeJS('print(true)');				
			$pdf->output();
		} else {			
			header('location:'.base_url());
			
		}
	}
	
	
	
	public function cetak_sj( $kodesi )
	{		
		$cek = $this->session->userdata('level');
        $nosj = $kodesi;
        $nama = 'Cash'; 		
		if(!empty($cek))
		{		    
			$data = $this->db->select('inv_barang.namabarang, sidetail.qtysi as qty,sidetail.satuan')->join('inv_barang','sidetail.kodeitem=inv_barang.kodeitem','left')->order_by('sidetail.kirim','desc')->get_where('sidetail',array('kodesi'=>$kodesi))->result();	
			
		    $pdf=new simkeu_vou();
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->setfont('Times','',10);
			$pdf->SetWidths(array(20,5,25,35));
			$pdf->SetAligns(array('L','C','L','R'));			
			$judul0=array('Surat Jalan',':',$nosj,'');
			$judul1=array('Nama',':',$nama,'KIRIM');			
			$judul2=array('Alamat',':','','');			
			$judul3=array('',':','','');			
			$judul4=array('Kota',':','','');			
			$judul5=array('HP',':','','');			
			$border= array('','','','');
		    $align = array('L','C','L','R');		    
		    $pdf->FancyRow($judul0,$border,$align);			
            $pdf->FancyRow($judul1,$border,$align);
			$pdf->FancyRow($judul2,$border,$align);
			$pdf->FancyRow($judul3,$border,$align);
			$pdf->FancyRow($judul4,$border,$align);
			$border= array('B','B','B','B');
			$pdf->FancyRow($judul5,$border,$align);
            $border= array('','','','');
						
			$pdf->SetWidths(array(65, 10,10));
			$pdf->SetAligns(array('L','L','L'));
			$pdf->setfont('Times','',10);
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');			
			$nourut = 1;

			foreach($data as $db)
			{
			  $pdf->FancyRow(array($db->namabarang, $db->qty, $db->satuan),$border,$align);
			  $nourut++;
			}
						
			$pdf->AliasNbPages();	
            $pdf->IncludeJS('print(true)');			
			$pdf->output();
		} else {			
			header('location:'.base_url());
			
		}
	}
	
	
	
	
	
}

/* End of file akuntansi_jurnal.php */
/* Location: ./application/controllers/akuntansi_jurnal.php */