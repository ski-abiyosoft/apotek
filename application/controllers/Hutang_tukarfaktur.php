<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hutang_tukarfaktur extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct();
		$this->session->set_userdata('menuapp', '5000');
		$this->session->set_userdata('submenuapp', '5201');
		$this->load->helper('simkeu_nota');
		$this->load->model('M_hutang');
		$this->load->model('M_rs');
		$this->load->model('M_cetak');
	}

	public function index()
	{
		$level = $this->session->userdata('level');	
        if(!empty($level))
		{		
			$unit= $this->session->userdata('unit');	
					
            $d['startdate'] = '';
            $d['enddate'] 	= '';
            $d['vendorid'] 	= '';
            $d['keu'] 	= '';

		    $d['list_vendor'] 	= $this->M_global->getListVendor(); 
            $akses= $this->M_global->cek_menu_akses($level, 5203);
            $d['akses']= $akses;	
			
			$cek_data = $this->M_hutang->getTukarFaktur($unit, '','','');
				
			$d['keu'] = $cek_data;
			$this->load->view('hutang/v_tukarfaktur',$d);
			
		} else
		{
			header('location:'.base_url());
		}
    }

	public function filter(){
		$level = $this->session->userdata('level');	
        if(!empty($level))
		{			
			$unit= $this->session->userdata('unit');	
			$akses= $this->M_global->cek_menu_akses($level, 5203);

            $d['startdate'] = '';
            $d['enddate'] 	= '';
            $d['vendorid'] 	= '';
            $d['keu'] 	= '';
            $d['akses']= $akses;
		    $d['list_vendor'] 	= $this->M_global->getListVendor(); 	
            
			$d['startdate'] = $this->input->get('startdate');
            $d['enddate'] 	= $this->input->get('enddate');
            $d['vendorid'] 	= $this->input->get('vendor');
			
			$cek_data = $this->M_hutang->getTukarFaktur($unit, $d['startdate'], $d['enddate'], $d['vendorid']);
				
			$d['keu'] = $cek_data;
			$this->load->view('hutang/v_tukarfaktur',$d);
			
		} else
		{
			header('location:'.base_url());
		}
	}

	public function entri(){
		$level = $this->session->userdata('level');	
        if(!empty($level))
		{
			$page=$this->uri->segment(3);
			$limit=$this->config->item('limit_data');	          
			$d['nomor']= 'Auto'; // urut_transaksi('AP_APO',19);        
			$d['nomortukarfaktur']= 'Auto'; // urut_transaksi('URUT_TUKAR',19);
			
			$this->load->view('hutang/v_tukarfaktur_add',$d);				
		} else
		{
			header('location:'.base_url());
		}
	}

	public function getTagihan(){
		$level = $this->session->userdata('level');	
        if(!empty($level))
		{
			$unit = $this->session->userdata('unit');	
			$page=$this->uri->segment(3);
			$limit=$this->config->item('limit_data');	 
			
			$vendor 	= $this->input->get('vendor');
			$startdate 	= $this->input->get('startdate');
			$enddate 	= $this->input->get('enddate');
			$dt = $this->M_hutang->getTagihan($unit, $vendor, $startdate, $enddate);

			echo json_encode($dt);
		} else
		{
			header('location:'.base_url());
		}
	}

	public function pilih_daftar_tagihan($vendor=''){
		$level = $this->session->userdata('level');	
		if(!empty($level))
		{
			$unit = $this->session->userdata('unit');
			$dt = $this->input->post('pilih');
			// $vendor = $this->input->post('vendor_id'); 

			$tghn = '';
			if(count($dt) > 0){
				$tghn = $this->M_hutang->getTagihanById($unit, array_keys($dt), $vendor);
					          
				// $tghn = $this->M_hutang->getTagihan($unit);
			}
			echo json_encode($tghn);
		} else
		{
			header('location:'.base_url());
		}
	}

	
	public function save($jenis)
	{
		$cek = $this->session->userdata('level');
		if(!empty($cek))
		{	
			$userid   	= $this->session->userdata('username');
			$unit      	= $this->session->userdata('unit');
			
			$terima_no		= $this->input->post('terima_no');
			$ttl			= $this->input->post('totaltagihan');
			$total_tagihan 	= array_sum($ttl);

			if($jenis == 1){
				$nomor_transaksi			= urut_transaksi('URUT_TUKAR',19);
				// $nomor_transaksi			= 1;        
			} else {
				$nomor_transaksi			= $this->input->post('nomor_transaksi');
			}

			$data = array(
			    'koders' 			=> $unit,
				'notukar'			=> $nomor_transaksi,
				'vendor_id' 		=> $this->input->post('vendor'),
				'tanggal' 			=> $this->input->post('tanggal_buat'),
				'diambil' 			=> $this->input->post('tanggal_ambil'),
				'keterangan' 		=> $this->input->post('catatan'),
				'tglbayar' 			=> $this->input->post('tanggal_rencana_bayar'),
				'norekening'		=> $this->input->post('rekening'),
				'totalsemua'		=> $total_tagihan,
				'username' 			=> $userid,			
			);
			
			// print_r($data);

	        if($jenis==1)
			{
				$this->db->insert("tbl_hfaktur", $data);
				if($this->db->affected_rows() > 0){
					$dt = array(
						'notukar' => $nomor_transaksi,
						'tukarfaktur' => 1,
						'tglrencanabayar' => $this->input->post('tanggal_rencana_bayar'),
					);
					$i = 0;
					foreach($terima_no as $key){	
						$where = array(
							'terima_no' => $key,
						);
						$this->db->update('tbl_apoap', $dt, $where);
						if($this->db->affected_rows() > 0) $i++; 
					}

					if($i != 0) echo $nomor_transaksi;
					else echo json_encode(array("status" => FALSE));
				} else {
					echo json_encode(array("status" => FALSE));					
				}
				// echo json_encode(array('rc' => false));
			} else {
				$where = array(
					'id' => $this->input->post('id'),
				);
				$this->db->update('tbl_hfaktur', $data, $where);
				echo $this->db->last_query();
				if($this->db->affected_rows() >= 0){
					$where2 = array(
						'notukar' => $nomor_transaksi,
					);	
					$data2 = array(
						'notukar' => '',
						'tukarfaktur' => 0,	
					);					
					$this->db->update('tbl_apoap', $data2, $where2);
					if($this->db->affected_rows() > 0){
						$dt3 = array(
							'notukar' => $nomor_transaksi,
							'tukarfaktur' => 1,
						);
						$i = 0;
						foreach($terima_no as $key){	
							$where3 = array(
								'terima_no' => $key,
							);
							$this->db->update('tbl_apoap', $dt3, $where3);
							if($this->db->affected_rows() > 0) $i++; 
						}
	
						if($i != 0) echo $nomor_transaksi;
						else echo json_encode(array("status1" => FALSE));
					} else {
						echo json_encode(array("status2" => FALSE));					
					} 
				} else {
					echo json_encode(array("status3" => FALSE));					
				}
			  //   echo $this->db->last_query();
			}
			
			// echo $nomor_transaksi;
		}
		else
		{
			header('location:'.base_url());
		}
	}

	public function ajax_delete()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{				  
			$id = $this->input->post('id');
			$del = $this->db->delete('tbl_hfaktur', array('id' => $id));
			if($del){
				$data = array(
					'notukar' => '',
					'tukarfaktur' => 0,							
				);
				$where =  array(
					'notukar' => $this->input->post('notukar'),			
					'koders' => $this->session->userdata('unit'),
				);
				$upt = $this->db->update('tbl_apoap',$data,$where);
				
				if($this->db->affected_rows() > 0) echo json_encode(array("status" => TRUE));
				else echo json_encode(array("status" => FALSE));
			} else {
				echo json_encode(array("status" => FALSE));
			}
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}

	
	public function edit($nomor)
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{	
			$unit = $this->session->userdata('unit');	
			$data['header'] = '';
			$data['detail'] = '';
			$data['id'] 	= $nomor;

			// $qheader ="
			// 	SELECT h.*, v.`vendor_name`
			// 	FROM tbl_hfaktur h 
			// 		LEFT JOIN tbl_vendor v ON BINARY h.`vendor_id` = v.`vendor_id`
			// 	WHERE h.id = '$nomor'"; 		
			
			$qheader ="
				SELECT h.*, v.`vendor_name`, rv.keterangan_rekening
				FROM tbl_hfaktur h 
					LEFT JOIN tbl_vendor v ON BINARY h.`vendor_id` = v.`vendor_id`
					LEFT JOIN (
						SELECT vr.id AS id, CONCAT(no_rekening, ' | ', atas_nama, ' | ', nama_bank, ' | ', vendor_name) AS keterangan_rekening
						FROM tbl_vendor_rekening vr 
							LEFT JOIN tbl_vendor v ON BINARY vr.`vendor_id` = BINARY v.`vendor_id`
					) rv ON h.`norekening` = rv.id
				WHERE h.id = '$nomor';
				";

			$dt = $this->db->query($qheader)->result();
			if(count($dt) != 0){
				foreach($dt as $key){
					// echo ($key->notukar);
					// echo "<pre>";
					// print_r($key);
					// echo "</pre>";

					$data['notukar'] 	= $key->notukar;
					$data['vendor_id'] 	= $key->vendor_id;
					$data['vendor'] 	= $key->vendor_name;
					$data['tanggal'] 	= $key->tanggal;
					$data['diambil'] 	= $key->diambil;
					$data['keterangan'] = $key->keterangan;
					$data['tglbayar'] 	= $key->tglbayar;
					$data['norekening'] = $key->norekening;
					$data['keterangan_rekening'] = $key->keterangan_rekening;
				}

				$qdetail ="				
					SELECT a.*, v.vendor_name
					FROM tbl_apoap a
						LEFT JOIN tbl_vendor v ON a.`vendor_id` = v.`vendor_id`
					WHERE BINARY a.notukar = (
						SELECT notukar
						FROM tbl_hfaktur
						WHERE id = '$nomor'
					)"; 		
				
				$data['detail'] =$this->db->query($qdetail)->result();
				
				if(count($data['detail']) != 0){
					$this->load->view('hutang/v_tukarfaktur_edit',$data);
				} else {
					header('location:'.base_url());
				}
			} else {
				header('location:'.base_url());
			}
		} else {
			header('location:'.base_url());
		}
	}

	public function cetak($id){
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{	
			$unit= $this->session->userdata('unit');
			$rs = $this->M_rs->getNamaRsById($unit);
			if($rs){				
				foreach($rs as $dtrs);

				$wa = '';$fax = '';
				if($dtrs->whatsapp != '') $wa = " / ".$dtrs->whatsapp;
				if($dtrs->fax != '') $fax = " / ".$dtrs->fax;

				$telp = ($dtrs->phone)."".$wa."".$fax;
				$dt = $this->M_hutang->getTukarFakturById($id);
				
				if($dt){
					foreach($dt as $data);
					$judul = '';$chari = '';
					// $chari = $this->load->view('pembelian/v_hutang_cetak',$d);

					$totaltagihan = number_format($data->totalsemua,0,'.',',');
					$terbilang = ucwords($this->M_global->terbilang($data->totalsemua))." Rupiah";
					$date_create = date_create($data->diambil);
					$diambil = date_format($date_create,'d-m-Y');
					$date = date('d-m-Y');
					$penerima = '_______________';
					
					$chari .= "

						<table style='border-collapse:collapse;font-family: tahoma; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
							<b>
								<tr >
									<td rowspan='4' align='center' width='20%'>
										<img src=\"" . base_url() . "assets/img/logo.png\"  width=\"75\" height=\"75\"  
										style='padding:-1px;' />
									</td>
									<td>$dtrs->namars</td>
								</tr>
								<tr  width='80%'>
									<td>$dtrs->alamat</td>
								</tr>
								<tr  width='80%'>
									<td>$dtrs->kota</td>
								</tr>
								<tr  width='80%'>
									<td>$telp</td>
								</tr>
							</b>
						</table>

						<br>

						<div width='100%' style='border-top:1px dashed black;'></div>
						<br>
						<table style='border-collapse:collapse;font-family: tahoma; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='3'>

						<tr class='show1'>
							<td align='left'  width='30%'>Telah Terima Dari</td>
							<td align='center'  width='5%'>:</td>
							<td align='left'  width='60%'>$data->vendor_name</td>
						</tr>
						
						<tr class='show1'>
							<td align='left' width='30%'>No. Kwitansi</td>
							<td align='center'  width='5%'>:</td>
							<td align='left'  width='60%'>$data->notukar</td>
						</tr>

						<tr class='show1'>
							<td align='left' width='30%'>Tagihan Sebesar</td>
							<td align='center'  width='5%'>:</td>
							<td align='left'  width='60%'>$totaltagihan</td>
						</tr>

						<tr class='show1'>
							<td align='left' width='30%'>Terbilang</td>
							<td align='center'  width='5%'>:</td>
							<td align='left'  width='60%'>$terbilang</td>
						</tr>";
					
					$detail = $this->M_hutang->getDetailTukarFakturById($id);
					if($detail){
						$i = 1;
						foreach($detail as $dtl){
							
							$ttlTagihan = number_format($dtl->totaltagihan,0,'.',',');
							$chari .= "
								<tr class='show1'>
									<td align='left' width='30%'></td>
									<td align='center'  width='5%'></td>
									<td align='left'  width='60%'>
										<table style='border-collapse:collapse;font-family: tahoma; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
											<tr class='show1'>
												<td align='center' width='5%'>$i.</td>
												<td width='60%'>$dtl->terima_no</td>
												<td align='right' width='30%'>$ttlTagihan</td>
											</tr>
										</table>
									</td>
								</tr>";
							$i++;
						}
					}

					$chari .= "
						<tr class='show1'>
							<td align='left' width='30%'>Guna Pembayaran</td>
							<td align='center'  width='5%'>:</td>
							<td align='left'  width='60%'>$data->keterangan</td>
						</tr>

						<tr class='show1'>
							<td align='left' width='30%'>Dapat diambil Tanggal</td>
							<td align='center'  width='5%'>:</td>
							<td align='left'  width='60%'>$diambil</td>
						</tr>

						</tbody>
						</table>

						<br>
						<div width='100%' style='border-top:1px dashed black;'></div>
						<br>

						<table style='border-collapse:collapse;font-family: tahoma; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
							<tr class='show1'>
								<td align='center' width='50%'></td>
								<td align='center' width='50%'>Jakarta, $date</td>
							</tr>
							<tr class='show1'>
								<td align='center' width='50%'>Penerima:</td>
								<td align='center' width='50%'>Keuangan</td>
							</tr>
						</table>

						<br><br><br>
						
						<table style='border-collapse:collapse;font-family: tahoma; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
							<tr class='show1'>
								<td align='center' width='50%'><b>$penerima</b></td>
								<td align='center' width='50%'><b>$data->username</b></td>
							</tr>
						</table>
					";

					$this->M_cetak->mpdf('P','A4',$judul, $chari,'Faktur_Baru_Manual.PDF', 10, 10, 10, 0);
				} else {
					header('location:'.base_url());
				}
			} else {
				header('location:'.base_url());
			}
		} else
		{
			header('location:'.base_url());
		}	
	}

}