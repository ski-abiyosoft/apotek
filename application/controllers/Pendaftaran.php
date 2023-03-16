<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pendaftaran extends CI_Controller {

	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_pendaftaran','M_pendaftaran');
		$this->load->model('M_pasien','M_pasien');
		$this->load->model('M_global','M_global');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '2000');
		$this->session->set_userdata('submenuapp', '2104');
		$this->load->helper('simkeu_nota1');		
		$this->load->helper('simkeu_nota');				
	}

	public function index()
	{
		$cek = $this->session->userdata('username');				
		if(!empty($cek))
		{
			$this->load->view('klinik/v_pendaftaran');
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function cek_noidentitas($param) {
		$data = $this->db->query("SELECT * FROM tbl_pasien WHERE noidentitas = '$param'")->num_rows();
		if($data > 0) {
			echo json_encode(["status" => 1]);
		} else {
			echo json_encode(["status" => 0]);
		}
	}
	
	public function edit( $id )
	{
		$cek = $this->session->userdata('username');				
		if(!empty($cek))
		{
			$data['id'] = $id;
			$data['data'] = $this->db->query("select tbl_regist.*, tbl_pasien.namapas
			from tbl_regist inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed
			where tbl_regist.id = '$id'")->row();
			
			$this->load->view('klinik/v_pendaftaran_edit', $data);
		} else
		{
			header('location:'.base_url());
		}			
	}
	
	public function entri()
	{
		$cek = $this->session->userdata('username');				
		if(!empty($cek))
		{
			$this->load->view('klinik/v_pendaftaran_add');
		} else
		{
			header('location:'.base_url());
		}			
	}

	public function ajax_list( $param )
	{
		$dat   = explode("~",$param);
		if($dat[0]==1){
			$bulan = $this->M_global->_periodebulan();
			$tahun = $this->M_global->_periodetahun();
			$list = $this->M_pendaftaran->get_datatables( 1, $bulan, $tahun );
		} else {
			$bulan  = date('Y-m-d',strtotime($dat[1]));
		    $tahun  = date('Y-m-d',strtotime($dat[2]));
		    $list = $this->M_pendaftaran->get_datatables( 2, $bulan, $tahun );
			
			// print_r($this->db->last_query());

		}
		
		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit) {
			$data_pasien = $this->M_global->_datapasien($unit->rekmed);
			$namapas = $data_pasien->namapas;
			$hp      = $data_pasien->handphone;
			$email   = $data_pasien->email;
			
			if ($unit->keluar=='0') {
                $status = '<span class="label label-sm label-warning">Register</span>';
			} elseif ($unit->keluar=='1') {
				$status = '<span class="label label-sm label-success">Closed</span> '; 
			}
			
			if ($unit->batal=='1') {
                $status = '<span class="label label-sm label-danger">Batal</span>';
			} 
			
			 
			$no++;
			$row = array();
			$row[] = $unit->koders;
			
			$row[] = $unit->username;

			$row[] = $unit->noreg;
			$row[] = $unit->namapas;
			$row[] = $unit->rekmed;			
			$row[] = date('d-m-Y',strtotime($unit->tglmasuk));
			$row[] = $unit->jam;			
			$row[] = $unit->kodepos;
			$row[] = $unit->nadokter;
			$row[] = $unit->antrino;
			$row[] = $status;
									
			if($unit->keluar==0 && $unit->batal==0){			
			  $row[] = 
			     '<a class="btn btn-sm btn-primary" href="'.base_url("pendaftaran/edit/".$unit->id."").'" title="Edit" ><i class="glyphicon glyphicon-edit"></i> </a> 				  
				  <a class="btn btn-sm btn-warning" href="'.base_url("pendaftaran/cetak2/?noreg=".$unit->noreg."").'" target="_blank" title="Cetak" ><i class="glyphicon glyphicon-print"></i></a>				   
				  <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Kirim Email" onclick="send_email('."'".$unit->id."'".",'".$email."'".')"><i class="glyphicon glyphicon-envelope"></i> </a>
				  <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Kirim Whatsapp" onclick="send_wa('."'".$unit->id."'".",'".$hp."'".')"><i class="fa fa-whatsapp"></i> </a>
			      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Batalkan" onclick="Batalkan('."'".$unit->id."'".')"><i class="glyphicon glyphicon-remove"></i> </a>';
			} else {
			  $row[] = '';	
			}	  
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->M_pendaftaran->count_all( $dat[0], $bulan, $tahun ),
						"recordsFiltered" => $this->M_pendaftaran->count_filtered( $dat[0], $bulan, $tahun ),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->M_pendaftaran->get_by_id($id);		
		echo json_encode($data);
	}
   
    public function gethistori(){
		$rekmed = $this->input->get('rekmed');		
		echo $this->M_pendaftaran->_datahistori($rekmed);
	}
	
	public function get_histori_with_datatable(){
		$rekmed = $this->input->get('rekmed');	
		$dt = $this->M_pendaftaran->_datahistori_datatable($rekmed);
		
		$data = array();
		foreach ($dt as $res1) {			
		
			$row = array();
			$row[] = $res1->koders;
			$row[] = $res1->tglmasuk;
			$row[] = $res1->jam;
			$row[] = "<a href=\"#\" onclick=\"getHistory('".($res1->noreg)."','".$rekmed."')\">".($res1->noreg)."<a/>";
			$row[] = $res1->namapost;
			$row[] = $res1->nadokter;

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->M_pendaftaran->_datahistori_datatable_count_all($rekmed),
			"recordsFiltered" => $this->M_pendaftaran->_datahistori_datatable_count_filtered($rekmed),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
		
	}
	
	public function history_pasien(){
		$noreg    = $this->input->get('noreg');
		$rekmed    = $this->input->get('rekmed');
		$cabang   = $this->session->userdata('unit');
		
		$datareg = $this->db->query("SELECT tbl_regist.*,tbl_pasien.namapas, tbl_dokter.nadokter  from tbl_regist 
		inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed 
		inner join tbl_dokter on tbl_regist.kodokter=tbl_dokter.kodokter	  
		where tbl_regist.noreg='$noreg'")->row();
		
		$carbay = $this->db->query("SELECT DISTINCT typecard FROM(
			SELECT CASE when cardtype is null and a.bayarcash<>'0.00' then 'TUNAI'
			when b.cardtype='1' then 'DEBIT'  
			when b.cardtype='2' then 'CREDIT CARD'  
			when b.cardtype='3' then 'TRANFER'  
			when b.cardtype='4' then 'ONLINE'  end as typecard ,b.cardtype,a.* from tbl_kasir a 
			left join tbl_kartukredit b on a.nokwitansi=b.nokwitansi 
			where noreg='$noreg' and a.koders='$cabang'
			)P WHERE typecard is not null order by typecard")->result();
		
		$konsul = $this->db->query("SELECT tbl_dpoli.*, tbl_tarifh.tindakan
		from tbl_dpoli inner join tbl_tarifh on tbl_dpoli.kodetarif=tbl_tarifh.kodetarif
		where tbl_dpoli.noreg = '$noreg' and tbl_dpoli.koders='$cabang'")->result();
		
		$resep = $this->db->query("SELECT tbl_apodresep.*
		from tbl_apodresep inner join tbl_apohresep on tbl_apodresep.resepno=tbl_apohresep.resepno
		where tbl_apohresep.noreg = '$noreg' and tbl_apohresep.rekmed='$rekmed'")->result();

		$jumkonsul = $this->db->query("SELECT sum(rsnet)rsnet
		from tbl_dpoli 
		inner join tbl_tarifh on tbl_dpoli.kodetarif=tbl_tarifh.kodetarif 
		where tbl_dpoli.noreg = '$noreg' and tbl_dpoli.koders='$cabang'")->row();

		$jumresep = $this->db->query("SELECT sum(totalrp)totalrp from tbl_apodresep inner join tbl_apohresep on tbl_apodresep.resepno=tbl_apohresep.resepno where tbl_apohresep.noreg = '$noreg' and tbl_apohresep.rekmed='$rekmed' ")->row();

		$totall=$jumkonsul->rsnet+$jumresep->totalrp;
	  
	  
	  $hasil = 
	  '<div class="row">	    
		<div class="col-md-4">	
          <div class="portlet">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-reorder"></i>
					<span class="label label-success"><b>PENDAFTARAN</b></span>												
				</div>								
			</div>		
			  <table class="table">
			    <tr>
				<th>No.Reg</th>
				<th>:</th>
				<th>'.$datareg->noreg.'</th>
				</tr>
				<tr>
				<th>Nama Pasien</th>
				<th>:</th>
				<th>'.$datareg->namapas.'</th>
				</tr>
				<tr>
				<th>Tgl. Masuk/Jam Masuk</th>
				<th>:</th>
				<th>'.tanggal($datareg->tglmasuk).' / '.$datareg->jam.'</th>
				</tr>
				<tr>
				<th>Poli</th>
				<th>:</th>
				<th>'.$datareg->kodepos.'</th>
				</tr>
				<tr>
				<th>Dokter</th>
				<th>:</th>
				<th>'.$datareg->nadokter.'</th>
				</tr>
				
			  </table>
		  </div>
			<br><br>
		  <div class="portlet">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-reorder"></i>
					<span class="label label-primary"><b>JUMLAH DARI KONSUL DAN RESEP</b></span>												
				</div>								
			</div>		
			  <table class="table">
			    <tr>
				<th class="text-center" bgcolor="#cccccc">'.angka_rp($totall,2).'</th>
				</tr>
			  </table>
		  </div>
		  <br><br>
		  <div class="portlet">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-reorder"></i>
					<span class="label label-warning"><b>CARA BAYAR</b></span>												
				</div>								
			</div>';
			$no       = 1 ;
		  foreach($carbay as $row){
			$hasil .= '
			  <table class="table">
			    <tr>
				<th width="10%" class="text-center" >'.$no.'.</th>
				<th class="text-left" >'.$row->typecard.'</th>
				</tr>
			  </table>';
		   };	
		   $hasil .= '
		 
		   </div>
		   </div>
		 
		 <div class="col-md-8">	
          <div class="portlet">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-reorder"></i>
					<span class="label label-warning"><b>KONSULTASI</b></span>												
				</div>								
			</div>		
			  <table class="table">			    			  
				<th class="text-center">No</th>
				<th class="text-center">Tindakan</th>
				<th class="text-center" colspan="2">Jumlah Rp</th>				
				<tbody>';
				$no       = 1 ;
				$haskon   = 0;
				foreach($konsul as $row){
				 $hasil .= '
				 
				 <tr>
				 <td>'.$no.'</td>
				 <td>'.$row->tindakan.'</td>
				 <td align="right">'.angka_rp($row->rsnet,2).'</td>
				 <td></td>
				 </tr>

				 ';
				 $ceknilkonsul    = $row->rsnet;
				 $haskon          += $ceknilkonsul;
				 $no++;
                };				
				  
				
		        $hasil.='<tr>
				<td colspan="2"><b>TOTAL</b></td>
				<td align="right"><b>'.angka_rp($haskon,2).'</b></td>
				<td></td>
				</tr>
				</tbody>
			  </table>
		  </div>
		  
		  <div class="portlet">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-reorder"></i>
					<span class="label label-danger"><b>RESEP</b></span>												
				</div>								
			</div>		
			  <table class="table">			    			  
				<th class="text-center">No</th>
				<th class="text-center">Nama Barang</th>
				<th class="text-center">Qty</th>				
				<th class="text-center">Harga Rp</th>				
				<th class="text-center">Diskon Rp</th>				
				<th class="text-center" colspan="2">Jumlah Rp</th>				
				<tbody>';
				$no       = 1;
				$hasres   = 0;
				foreach($resep as $row){
				 $hasil .= '
				 
				 <tr>
				 <td align="center">'.$no.'</td>
				 <td>'.$row->namabarang.'</td>
				 <td align="center">'.$row->qty.'</td>
				 <td align="right">'.angka_rp($row->price,2).'</td>
				 <td align="right">'.angka_rp($row->discrp,2).'</td>
				 <td align="right">'.angka_rp($row->totalrp,2).'</td>
				 <td></td>
				 </tr>

				 ';
				 $ceknilresep    = $row->totalrp;
				 $hasres          += $ceknilresep;
				 $no++;
                };				
				  
				$hasil.='<tr>
				<td colspan="5"><b>TOTAL</b></td>
				<td align="right"><b>'.angka_rp($hasres,2).'</b></td>
				<td></td>
				</tr>
				</tbody>
			  </table>
		  </div>
		 </div>
		
		
		
       </div>'	;
	  echo $hasil;
	}
	
	public function ajax_add()
	{
		$cabang = $this->session->userdata('unit');	
		$userid = $this->session->userdata('username');
		// $tanggal = $this->input->post('tanggal');
		$tanggal = date('Y-m-d');
		$tahun = date('Y');
		$penjamin = $this->input->post('penjamin');
		
		$noreg = urut_transaksi('REGISTRASI', 16);
		
		if($penjamin){
		  $_penjamin = $penjamin;	
		} else {
		  $_penjamin = '';	
		}
		
		if($this->input->post('pengirim')){
		  $_drpengirim = $this->input->post('pengirim');	
		} else {
		  $_drpengirim = '';	 
		}
		
		$rekmed = $this->input->post('pasien');
		// if(preg_match("/[a-zA-Z]/i", $this->input->post('pasien'))){
		// 	$rekmed = $this->input->post('pasien');
		// } else {
		// 	$rekmed = pasien_rekmed_baru($this->input->post('namapasien_hidden'));
		// 	$id_pasien_baru = $this->input->post('pasien');
		// }
		
		// echo $query = "select count(*) as total from tbl_regist where rekmed='$rekmed' AND koders='$cabang'";   // tidak diganti
		$query = "select count(*) as total from tbl_regist where rekmed='$rekmed'";
		
		$dregist=$this->db->query($query)->row();

		if($dregist->total>0){
			$baru=0;	
		} else {
			$baru=1;
		}
		
		$this->_validate();
		$data = array(
			'koders' => $cabang,
			'noreg' => $noreg,
			'rekmed' => $rekmed, // $this->input->post('pasien'),
			'tglmasuk' => $tanggal,
			'jam' => $this->input->post('jam'),
			'kodokter' => $this->input->post('dokter'),
			'antrino' => $this->input->post('noantri'),
			'kodepos' => $this->input->post('poli'),
			'jenispas' => $this->input->post('jenispasien'),
			'drpengirim' => $_drpengirim,
			'cust_id' => $_penjamin,
			'tujuan' => $this->input->post('ruang'),
			'username' => $userid,
			'baru' => $baru
				
			);
		$insert = $this->M_pendaftaran->save($data);
		// if($insert && ($baru == 1)){
		// 	$update_rekmed_baru = $this->M_pasien->update(
		// 	array(
		// 		'rekmed' => $id_pasien_baru
		// 	), array(
		// 		'rekmed' => $rekmed
		// 	));
		// }
		$pesan  = $this->send_wa_selamat($insert);
		echo json_encode(array("status" => TRUE, "noreg" => $noreg,"pesan" => $pesan));
	}

	public function ajax_update()
	{
		$cabang = $this->session->userdata('unit');	
		$userid = $this->session->userdata('username');
		$tanggal = $this->input->post('tanggal');
		$noreg   = $this->input->post('nodaftar');
		$penjamin = $this->input->post('penjamin');
		$tahun = date('Y');
		
		if($penjamin){
		  $_penjamin = $penjamin;	
		} else {
		  $_penjamin = '';	
		}
		$this->_validate();
		
		if($this->input->post('pengirim')){
		  $_drpengirim = $this->input->post('pengirim');	
		} else {
		  $_drpengirim = '';	 
		}
		
		$data = array(
				'rekmed' => $this->input->post('pasien'),
				'tglmasuk' => $tanggal,
				'jam' => $this->input->post('jam'),
				'kodokter' => $this->input->post('dokter'),
				'antrino' => $this->input->post('noantri'),
				'kodepos' => $this->input->post('poli'),
				'jenispas' => $this->input->post('jenispasien'),
				'drpengirim' => $_drpengirim,
				'cust_id' => $_penjamin,
				'tujuan' => $this->input->post('ruang'),
			);
		$this->M_pendaftaran->update(array('id' => $this->input->post('q_id'),'koders' => $cabang), $data);
		echo json_encode(array("status" => TRUE, "noreg" => $noreg));
	}

	
	public function pembatalan($id)
	{
		$data = array(
		  'batal' => 1,
		  'keluar' => 1,
		  'batalkarena' => $this->input->post('alasan'),
		);
		
		$result = $this->M_pendaftaran->update(array('id' => $id), $data);
		if($result){
		  echo json_encode(array("status" => 1));
		} else {
		  echo json_encode(array("status" => 0));	
		}
	}
	


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('pasien') == '')
		{
			$data['inputerror'][] = 'pasien';
			$data['error_string'][] = 'Harus diisi';
			$data['status'] = FALSE;
		}

		
	
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	
	public function cetak3()
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');
        $user= $this->session->userdata('username');		
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');		
		if(!empty($cek))
		{				  
            $noreg = $this->input->get('noreg');	
			$profile = data_master('tbl_namers', array('koders' => $unit));
		    $nama_usaha=$profile->namars;
			$alamat1  = $profile->alamat;
			$alamat2  = $profile->kota;
			$kota     = $profile->kota;
			$kacab    = $profile->pejabat2;
            
		    $unit= $this->session->userdata('unit');	 
		    
		    $qregis = "select tbl_regist.*, tbl_pasien.* from tbl_regist 
			inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed
			where noreg = '$noreg'";
			 
			$regist = $this->db->query($qregis)->row();
			
			$pdf=new simkeu_nota1();
			$pdf->setID($nama_usaha,$alamat1,$alamat2);
			$pdf->setjudul(' ');
			$pdf->setsubjudul('');
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(70,30,90));
			$border = array('T','','BT');
			$size   = array('','','');
			$pdf->setfont('Arial','B',18);
			$pdf->SetAligns(array('C','C','C'));
			$align = array('L','C','L');
			$style = array('','','B');
			$size  = array('12','','18');
			$max   = array(5,5,20);
			$fc     = array('0','0','0');
			$hc     = array('20','20','20');
			$pdf->setfont('Arial','B',10);
			$pdf->SetWidths(array(50,140));
			$border = array('LRBT','LRBT');
			$fc     = array('0','0');
			$pdf->SetFillColor(230,230,230);			
			$pdf->image('./assets/img/logoi.png',25,18,20,20);
			$pdf->setfont('Arial','B',14);
			$pdf->FancyRow(array('','DATA PASIEN RAWAT INAP / RAWAT JALAN'), $fc, $border);
			$pdf->SetWidths(array(50,60,15,65));
			$border = array('LR','LR','LRBT','LRBT');
			$fc     = array('0','0','0','0');
			$pdf->SetFillColor(230,230,230);			
			$size  = array('10','8','12','14');
			//$pdf->setfont('Arial','B',12);
			$style = array('','','B','B');
			$align = array('L','C','C','C');
			$max   = array(5,5,5,5);
			$pdf->FancyRow(array('','NAMA LENGKAP PASIEN ','RM',$regist->rekmed), $fc, $border,$align,$style,  $size, $max);
			$size  = array('10','12','8','8');
			$style = array('','B','','');
			$border = array('LR','LR','L','R');
			$pdf->FancyRow(array('',$regist->namapas,'UMUR : ',hitung_umur($regist->tgllahir)), $fc, $border,$align,$style,  $size, $max);
			$size  = array('10','8','8','8');
			
			$pdf->SetWidths(array(50,25,5,30,25,5,50));
			$border = array('LR','L','','','L','','R');
			$size  = array('8','8','8','8','8','8','8');
			$max   = array(5,5,5,5,5,5,5);
			$fc     = array('0','0','0','0','0','0','0');
			$style = array('B','','','','','','');
			$align = array('L','L','L','L','L','L','L');
			$pdf->FancyRow(array('','JENIS KELAMIN',':',($regist->jkel=='P'?'Pria':'Wanita'),'GOL DARAH',':',$regist->goldarah), $fc, $border,$align,$style,  $size, $max);
			$pdf->FancyRow(array($nama_usaha,'TEMPAT LAHIR',':',$regist->tempatlahir,'AGAMA',':',$regist->agama), $fc, $border,$align,$style,  $size, $max);
			$pdf->FancyRow(array('','TGL. LAHIR',':',tanggal($regist->tgllahir),'ALAMAT',':',$regist->alamat), $fc, $border,$align,$style,  $size, $max);
			$style = array('','','','','','','');
			$pdf->FancyRow(array($alamat1,'PENDIDIKAN',':',$regist->pendidikan,'','',''), $fc, $border,$align,$style,  $size, $max);
			$pdf->FancyRow(array($alamat2,'STATUS',':',$regist->status,'','',''), $fc, $border,$align,$style,  $size, $max);
			$pdf->FancyRow(array('','PEKERJAAN',':',$regist->pekerjaan,'TELPON',':',$regist->handphone), $fc, $border,$align,$style,  $size, $max);
			$pdf->SetWidths(array(190));
			$border = array('LRBT');
			$fc     = array('0');			
			$pdf->setfont('Arial','B',12);
			$pdf->FancyRow(array('TUJUAN : '.data_master('tbl_namapos',array('kodepos' =>$regist->kodepos))->namapost), $fc, $border);			
			$pdf->setfont('Arial','B',8);
			$border = array('LR');
			$pdf->FancyRow(array('NAMA ORANGTUA / SUAMI / ISTRI / WALI : '), $fc, $border);			
            $pdf->SetWidths(array(20,5,50,115));
			$border = array('L','','','R');
			$size  = array('8','8','8','8');
			$max   = array(5,5,5,5);
			$fc     = array('0','0','0','0');
			$style = array('','','','');
			$align = array('L','L','L','L');
			$pdf->FancyRow(array('PEKERJAAN',':','',''), $fc, $border,$align,$style,  $size, $max); 			
			$pdf->FancyRow(array('ALAMAT',':','',''), $fc, $border,$align,$style,  $size, $max); 			
			$pdf->FancyRow(array('TELPON',':','',''), $fc, $border,$align,$style,  $size, $max); 			
			$pdf->SetWidths(array(50,5,50,85));
			$border = array('LT','T','T','TR');
			$pdf->FancyRow(array('CARA PEMBAYARAN',':',data_master('tbl_setinghms',array('kodeset' =>$regist->jenispas))->keterangan,''), $fc, $border,$align,$style,  $size, $max); 			
			$border = array('L','','','R');
			$pdf->FancyRow(array('NAMA PENANGGUNG JAWAB',':','',''), $fc, $border,$align,$style,  $size, $max); 			
			$pdf->FancyRow(array('ALAMAT PENANGGUNG JAWAB',':','',''), $fc, $border,$align,$style,  $size, $max); 			
			$pdf->FancyRow(array('TELEPON',':','',''), $fc, $border,$align,$style,  $size, $max); 			
			$pdf->SetWidths(array(85,55,50));
			$border = array('LT','TL','LTR');
			$align = array('C','C','C');
			$style = array('B','B','B');
			$pdf->FancyRow(array('CATATAN LAIN-LAIN YANG PERLU DISAMPAIKAN','MENGETAHUI PASIEN / KELUARGA','PETUGAS RM-PENDAFTARAN'), $fc, $border,$align,$style,  $size, $max); 						
			$style = array('','','');
			$pdf->FancyRow(array('','',$kota.','.tanggal($regist->tglmasuk)), $fc, $border,$align,$style,  $size, $max); 						
			$border = array('L','L','LR');
			$style = array('','','');
			$pdf->FancyRow(array('','',''), $fc, $border,$align,$style,  $size, $max); 						
			$pdf->FancyRow(array('','',''), $fc, $border,$align,$style,  $size, $max); 						
			$pdf->FancyRow(array('','',''), $fc, $border,$align,$style,  $size, $max); 						
			$style = array('B','BU','BU');
			$border = array('LB','BL','LBR');
			$pdf->FancyRow(array('',$regist->namapas,$kacab), $fc, $border,$align,$style,  $size, $max); 						
			$pdf->ln(2);
			
			
            $pdf->setTitle($noreg); 
			$pdf->AliasNbPages();
			$pdf->output($noreg.'.PDF','I');			
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	public function cetak2()
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');		
		if(!empty($cek))
		{				  
            $noreg = $this->input->get('noreg');	
            $profile = $this->M_global->_LoadProfileLap();	
		    $unit= $this->session->userdata('unit');	 
		    $nama_usaha=$profile->nama_usaha;
			$alamat1  = $profile->alamat1;
			$alamat2  = $profile->telpon;
		  
		    $qregis = "select tbl_regist.*, tbl_pasien.*, tbl_regist.kodepos as kodepost from tbl_regist 
			inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed
			where noreg = '$noreg'";
			 
			$regist = $this->db->query($qregis)->row();
			
			$pdf=new simkeu_nota1();
			$pdf->setID($nama_usaha,$alamat1,$alamat2);
			$pdf->setjudul(' ');
			$pdf->setsubjudul('');
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(70,30,90));
			$border = array('T','','BT');
			$size   = array('','','');
			$pdf->setfont('Arial','B',18);
			$pdf->SetAligns(array('C','C','C'));
			$align = array('L','C','L');
			$style = array('','','B');
			$size  = array('12','','18');
			$max   = array(5,5,20);
			//$judul=array('Kwitansi ','','Faktur Penjualan');
			$fc     = array('0','0','0');
			$hc     = array('20','20','20');
			//$pdf->FancyRow2(10,$judul, $fc,  $border, $align, $style, $size, $max);
			//$pdf->ln(1);
			$pdf->setfont('Arial','B',10);
			$pdf->SetWidths(array(30,5,50,10,25,5,55));
			$border = array('','','','','','','');
			$fc     = array('0','0','0','0','0','0','0');
			$pdf->SetFillColor(230,230,230);			
			
			
			$pdf->setfont('Arial','',8);
			$pdf->FancyRow(array('','','No. RM :','','','',''), $fc, $border);
			$pdf->setfont('Arial','B',21);
			$pdf->FancyRow(array('','',$regist->rekmed,'','','',''), $fc, $border);
			$pdf->setfont('Arial','',8);
			$pdf->FancyRow(array('No. Reg',':',$regist->noreg,'','','',''), $fc, $border);
			$pdf->FancyRow(array('Nama Pasien',':',$regist->namapas,'','','',''), $fc, $border);
			$pdf->FancyRow(array('Tempat/Tgl. Lahir',':',$regist->tempatlahir.' / '.tanggal($regist->tgllahir),'','','',''), $fc, $border);
			$pdf->FancyRow(array('Umur',':',hitung_umur($regist->tgllahir),'','','',''), $fc, $border);
			$pdf->FancyRow(array('Alamat',':',$regist->alamat,'','','',''), $fc, $border);
			$pdf->FancyRow(array('Tgl. Masuk',':',tanggal($regist->tglmasuk).' Jam '.$regist->jam,'','','',''), $fc, $border);
			$pdf->FancyRow(array('Pasien',':',($regist->baru==1?'Baru':'Lama'),'','','',''), $fc, $border);
			$pdf->FancyRow(array('Pelayanan',':',data_master('tbl_namapos',array('kodepos' => $regist->kodepost))->namapost,'','','',''), $fc, $border);
			$pdf->FancyRow(array('Dokter',':',data_master('tbl_dokter',array('kodokter' => $regist->kodokter))->nadokter,'','','',''), $fc, $border);
			
			
			$pdf->ln(2);
			
			
            $pdf->setTitle($noreg); 
			$pdf->AliasNbPages();
			$pdf->output('./uploads/regist/'.$noreg.'.PDF','F');
			$pdf->output($noreg.'.PDF','I');			
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	public function genpdf( $noreg )
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');		
		if(!empty($cek))
		{				              
            $profile = $this->M_global->_LoadProfileLap();	
		    $unit= $this->session->userdata('unit');	 
		    $nama_usaha=$profile->nama_usaha;
			$alamat1  = $profile->alamat1;
			$alamat2  = $profile->telpon;
		  
		    $qregis = "select tbl_regist.*, tbl_pasien.*, tbl_regist.kodepos as kodepost from tbl_regist 
			inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed
			where noreg = '$noreg'";
			 
			$regist = $this->db->query($qregis)->row();
			
			$pdf=new simkeu_nota1();
			$pdf->setID($nama_usaha,$alamat1,$alamat2);
			$pdf->setjudul(' ');
			$pdf->setsubjudul('');
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(70,30,90));
			$border = array('T','','BT');
			$size   = array('','','');
			$pdf->setfont('Arial','B',18);
			$pdf->SetAligns(array('C','C','C'));
			$align = array('L','C','L');
			$style = array('','','B');
			$size  = array('12','','18');
			$max   = array(5,5,20);
			//$judul=array('Kwitansi ','','Faktur Penjualan');
			$fc     = array('0','0','0');
			$hc     = array('20','20','20');
			//$pdf->FancyRow2(10,$judul, $fc,  $border, $align, $style, $size, $max);
			//$pdf->ln(1);
			$pdf->setfont('Arial','B',10);
			$pdf->SetWidths(array(30,5,50,10,25,5,55));
			$border = array('','','','','','','');
			$fc     = array('0','0','0','0','0','0','0');
			$pdf->SetFillColor(230,230,230);			
			
			
			$pdf->setfont('Arial','',8);
			$pdf->FancyRow(array('','','No. RM :','','','',''), $fc, $border);
			$pdf->setfont('Arial','B',21);
			$pdf->FancyRow(array('','',$regist->rekmed,'','','',''), $fc, $border);
			$pdf->setfont('Arial','',8);
			$pdf->FancyRow(array('No. Reg',':',$regist->noreg,'','','',''), $fc, $border);
			$pdf->FancyRow(array('Nama Pasien',':',$regist->namapas,'','','',''), $fc, $border);
			$pdf->FancyRow(array('Tempat/Tgl. Lahir',':',$regist->tempatlahir.' / '.tanggal($regist->tgllahir),'','','',''), $fc, $border);
			$pdf->FancyRow(array('Umur',':',hitung_umur($regist->tgllahir),'','','',''), $fc, $border);
			$pdf->FancyRow(array('Alamat',':',$regist->alamat,'','','',''), $fc, $border);
			$pdf->FancyRow(array('Tgl. Masuk',':',tanggal($regist->tglmasuk).' Jam '.$regist->jam,'','','',''), $fc, $border);
			$pdf->FancyRow(array('Pasien',':',($regist->baru==1?'Baru':'Lama'),'','','',''), $fc, $border);
			$pdf->FancyRow(array('Pelayanan',':',data_master('tbl_namapos',array('kodepos' => $regist->kodepost))->namapost,'','','',''), $fc, $border);
			$pdf->FancyRow(array('Dokter',':',data_master('tbl_dokter',array('kodokter' => $regist->kodokter))->nadokter,'','','',''), $fc, $border);
			
			
			$pdf->ln(2);
			
			
            $pdf->setTitle($noreg); 
			$pdf->AliasNbPages();
			$pdf->output('./uploads/regist/'.$noreg.'.PDF','F');			
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
	public function cetak1()
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');		
		if(!empty($cek))
		{				  
            $noreg = $this->input->get('noreg');	
            $profile = $this->M_global->_LoadProfileLap();	
		    $unit= $this->session->userdata('unit');	 
		    $nama_usaha=$profile->nama_usaha;
			$alamat1  = $profile->alamat1;
			$alamat2  = $profile->telpon;
		  
		    $qregis = "select tbl_regist.*, tbl_pasien.* from tbl_regist 
			inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed
			where noreg = '$noreg'";
			 
			$regist = $this->db->query($qregis)->row();
			
			$pdf=new simkeu_nota1();
			$pdf->setID($nama_usaha,$alamat1,$alamat2);
			$pdf->setjudul(' ');
			$pdf->setsubjudul('');
			$pdf->addpage("P","A4");   
			$pdf->setsize("P","A4");
			$pdf->SetWidths(array(70,30,90));
			$border = array('T','','BT');
			$size   = array('','','');
			$pdf->setfont('Arial','B',18);
			$pdf->SetAligns(array('C','C','C'));
			$align = array('L','C','L');
			$style = array('','','B');
			$size  = array('12','','18');
			$max   = array(5,5,20);
			$fc     = array('0','0','0');
			$hc     = array('20','20','20');
			$pdf->setfont('Arial','B',10);
			$pdf->SetWidths(array(15,5,50,10,25,5,55));
			$border = array('','','','','','','');
			$fc     = array('0','0','0','0','0','0','0');
			$pdf->SetFillColor(230,230,230);			
			
			
			$pdf->setfont('Arial','',8);
			$pdf->FancyRow(array('No',':',$regist->rekmed,'','','',''), $fc, $border);
			$pdf->FancyRow(array('Nama ',':',$regist->namapas,'','','',''), $fc, $border);
			$pdf->FancyRow(array('Alamat',':',$regist->alamat,'','','',''), $fc, $border);
			$pdf->FancyRow(array('Telpon',':',$regist->phone.' / '.$regist->handphone,'','','',''), $fc, $border);
			
			$pdf->ln(2);
			
			
            $pdf->setTitle($noreg); 
			$pdf->AliasNbPages();
			$pdf->output($noreg.'.PDF','I');			
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	public function send_wa(){		          
		 $userid   = $this->session->userdata('inv_username');
		 $param = $this->input->post('id');		 
		 
	     $data = $this->db->query("
		 select tbl_regist.*, tbl_pasien.*, tbl_regist.kodepos as kodepost from tbl_regist 
		 inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed
		 where tbl_regist.id = '$param'")->row();
		 
		 $this->genpdf($data->noreg);
		 
		 $pelayanan = data_master('tbl_namapos',array('kodepos' => $data->kodepost));
		 $attched_file  = base_url()."uploads/regist/".$data->noreg.".PDF";
		 $mobile = $data->handphone;				
		 $txtwa   = "REGISTRASI"."\r\n".
		            "No. Reg : ".$data->noreg."\r\n".
					"No. RM : ".$data->rekmed."\r\n".
					"Nama Pasien : ".$data->namapas."\r\n".
					"umur : ".hitung_umur($data->tgllahir)."\r\n".
					"Alamat : ".$data->alamat."\r\n".
					"Tanggal Masuk : ".date('d M Y',strtotime($data->tglmasuk))."\r\n".
					"Pelayanan : ".data_master('tbl_namapos',array('kodepos' => $data->kodepost))->namapost."\r\n".
					"Dokter : ".data_master('tbl_dokter',array('kodokter' => $data->kodokter))->nadokter."\r\n";
												 
		 $txtwa2   = $attched_file;
		 
         $result= send_wa_txt($mobile,$txtwa);		 		 		 		
		 $result= send_wa_txt($mobile,$txtwa2);		 		 		 		
		 echo json_encode(array("status" => 1));
	}
	
	public function send_wa_selamat( $param ){		          
		 $userid   = $this->session->userdata('inv_username');
		 $unit= $this->session->userdata('unit');
		 $profile = data_master('tbl_namers', array('koders' => $unit));
		 
	     $data = $this->db->query("SELECT tbl_regist.*, tbl_pasien.*, tbl_regist.kodepos as kodepost from tbl_regist 
		 inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed
		 where tbl_regist.id = '$param'")->row();		 
		 $mobile = $data->handphone;	
		 $kodpos = $data->kodepost;	
		 $dpreposisi = data_master('tbl_setinghms', array('kodeset' => $data->preposisi));
		 if($dpreposisi){
		   $preposisi = $dpreposisi->keterangan;	  
		 } else {
		   $preposisi = 'Bpk/Ibu';	 
		 }
		 
         $namapasien = 	$preposisi.' '.$data->namapas;	 
		 $nomorhub = " WA: ".$profile->whatsapp."/Telp:".$profile->phone;
		 
		 $txtwa   = "Pendaftaran Pasien, ".$namapasien."\r\n".
		            " "."\r\n".
		            "Selamat Datang di Klinik Estetika dr, Affandi"."\r\n".
					"Cabang : *".$profile->koders."*\r\n".
					"Terima kasih telah mempercayakan perawatan *".$kodpos."* Anda di klinik kami"."\r\n".
					"Untuk mendapatkan informasi & pelayanan lebih lanjut dapat menghubungi ".$nomorhub."\r\n";
												 		 
         $result= send_wa_txt($mobile,$txtwa);
		 
		 $txtpm   = "Pendaftaran Pasien, ".$namapasien."<br>".
				" "."<br>".
				"Selamat Datang di Klinik Estetika dr, Affandi"."<br>".
				"Cabang : ".$profile->koders."<br>".
				"Terima kasih telah mempercayakan perawatan ".$kodpos." Anda di klinik kami"."<br>".
				"Untuk mendapatkan informasi & pelayanan lebih lanjut dapat menghubungi ".$nomorhub."<br>";
											  
		return $txtpm;
		 
	}
	
	
	public function send_email(){		 
         $userid   = $this->session->userdata('inv_username');
		 $unit= $this->session->userdata('unit');
		 $profile = data_master('tbl_namers', array('koders' => $unit));
		 $nama_usaha=$profile->namars;
		 $email_usaha= $profile->email;
			
		 $param = $this->input->post('id');		 
		 
	     $data = $this->db->query("SELECT tbl_regist.*, tbl_pasien.*, tbl_regist.kodepos as kodepost from tbl_regist 
		 inner join tbl_pasien on tbl_regist.rekmed=tbl_pasien.rekmed
		 where tbl_regist.id = '$param'")->row();
		 
		 $this->genpdf($data->noreg);
		 $attched_file  = base_url()."uploads/regist/".$data->noreg.".PDF";
		 $mobile = $data->handphone;		 		 
		 $ready_message   = 
		            "REGISTRASI"."\r\n".
		            "No. Reg : ".$data->noreg."\r\n".
					"No. RM : ".$data->rekmed."\r\n".
					"Nama Pasien : ".$data->namapas."\r\n".
					"umur : ".hitung_umur($data->tgllahir)."\r\n".
					"Alamat : ".$data->alamat."\r\n".
					"Tanggal Masuk : ".date('d M Y',strtotime($data->tglmasuk))."\r\n".
					"Pelayanan : ".data_master('tbl_namapos',array('kodepos' => $data->kodepost))->namapost."\r\n".
					"Dokter : ".data_master('tbl_dokter',array('kodokter' => $data->kodokter))->nadokter."\r\n";
					
			$email_tujuan = trim($data->email);
						
			$server_subject = "REGISTRASI ";
			
			$email_usaha = "support@gmail.com";
			$this->load->library('email');
			$this->email->from($email_usaha, $nama_usaha);
			$this->email->to($email_tujuan);
			$this->email->subject($server_subject);
			$this->email->message($ready_message);
			$this->email->attach($attched_file);
			
			if($this->email->send()){
				echo json_encode(array("status" => 1));
		
			}
			else{
				echo json_encode(array("status" => 0));
			}
			
        
	}
	
	
}
