<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_kunjungan extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct();
		$this->session->set_userdata('menuapp', '5000');
		$this->session->set_userdata('submenuapp', '5201');
		$this->load->helper('simkeu_nota');
		$this->load->model('M_laporan');
		$this->load->model('M_rs');
		$this->load->model('M_cetak');
	}

    
	public function index($id=""){
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        
		if(!empty($cek))
		{	
				
			$d['startdate'] = null;
			$d['enddate'] = null;
			$d['jenis_kunjungan'] = 'frekuensi';
				
			$d['keu'] = array();
			// $d['keu'] = $this->db->query($q1)->result();	
			$d['akses'] = $this->M_global->cek_menu_akses($cek, 5301);

            $this->load->view('laporan_kunjungan/laporan_kunjungan',$d);
		} else
		{
			header('location:'.base_url());
		}
	}

	public function index2($id=""){
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        
		if(!empty($cek))
		{	
						
				
			$d['startdate'] = null;
			$d['enddate'] = null;
			$d['jenis_kunjungan'] = 'frekuensi';
				
			$d['keu'] = array();
			// $d['keu'] = $this->db->query($q1)->result();	
			$d['akses'] = $this->M_global->cek_menu_akses($cek, 5301);

            $this->load->view('laporan_kunjungan/laporan_kunjungan2',$d);
			
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
            $d['jenis_kunjungan'] 	= 'frekuensi';
			$d['keu'] = array();

            $startdate				= $this->input->post('startdate');
            $enddate				= $this->input->post('enddate');
            $jenis_kunjungan		= $this->input->post('jenis_kunjungan');
            $d['startdate']			= $startdate;
            $d['enddate']			= $enddate;
            $d['jenis_kunjungan']	= $jenis_kunjungan;

            $cek_data = $this->M_laporan->get_kunjungan_pasien($startdate, $enddate, $jenis_kunjungan);
		  
			
			// echo "<pre>";
			// print_r($cek_data);
			// echo "</pre>";

			if($cek_data) $d['keu'] = $cek_data;

			$this->load->view('laporan_kunjungan/laporan_kunjungan2',$d);
			
		} else
		{
			header('location:'.base_url());
		}
	}

	
	public function filter2(){
		$level = $this->session->userdata('level');	
        if(!empty($level))
		{			
			$unit= $this->session->userdata('unit');	
			$akses= $this->M_global->cek_menu_akses($level, 5203);

            $d['startdate'] = '';
            $d['enddate'] 	= '';
            $d['jenis_kunjungan'] 	= 'frekuensi';
			$d['keu'] = array();

            $startdate				= $this->input->post('startdate');
            $enddate				= $this->input->post('enddate');
            $jenis_kunjungan		= $this->input->post('jenis_kunjungan');
            $d['startdate']			= $startdate;
            $d['enddate']			= $enddate;
            $d['jenis_kunjungan']	= $jenis_kunjungan;

            $cek_data = $this->M_laporan->get_kunjungan_pasien($startdate, $enddate, $jenis_kunjungan);
			
			// echo "<pre>";
			// print_r($cek_data);
			// echo "</pre>";

			if($cek_data) $d['keu'] = $cek_data;

			$this->load->view('laporan_kunjungan/laporan_kunjungan2',$d);
			
		} else
		{
			header('location:'.base_url());
		}
	}

	
	public function export($id){
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        
		if(!empty($cek))
		{	
			$unit= $this->session->userdata('unit');	
			$akses= $this->M_global->cek_menu_akses($cek, 5203);

            $d['startdate'] = '';
            $d['enddate'] 	= '';
            $d['jenis_kunjungan'] 	= 'frekuensi';
			$d['keu'] = array();

            // $startdate				= $this->input->post('startdate');
            // $enddate				= $this->input->post('enddate');
            // $jenis_kunjungan		= $this->input->post('jenis_kunjungan');

			$startdate				= $this->input->get('startdate');
            $enddate				= $this->input->get('enddate');
            $jenis_kunjungan		= $this->input->get('jenis_kunjungan');
			
            $d['startdate']			= $startdate;
            $d['enddate']			= $enddate;
            $d['jenis_kunjungan']	= $jenis_kunjungan;

            $d['keu'] = $this->M_laporan->get_kunjungan_pasien($startdate, $enddate, $jenis_kunjungan);
			
			
			// $this->load->view('penjualan/v_detailPiutang_exp',$d);

			$judul = "Laporan Kunjungan Pasien";
			$chari = "";
			
			// $chari .= $judul;
			$chari .= "<table style='border-collapse:collapse;font-family: tahoma; font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='3'>
				<thead>
					<tr>
						<th style='text-align: center'>No. Member</th>
						<th style='text-align: center'>Cabang Awal</th>
						<th style='text-align: center'>Nama</th>
						<th style='text-align: center'>Jenis Kelamin</th>
						<th style='text-align: center'>Tanggal Lahir</th>
						<th style='text-align: center'>Umur</th>
						<th style='text-align: center'>Alamat</th>
						<th style='text-align: center'>No. Telepon</th>
						<th style='text-align: center; width: 10px !important;' >Konsultasi & Tindakan</th>
						<th style='text-align: center'>Dokter</th>
						<th style='text-align: center'>Paramedis</th>
						<th style='text-align: center'>Tgl. Kunjungan Pertama</th>
						<th style='text-align: center'>Tgl. Kunjungan Terakhir</th>
						<th style='text-align: center'>Frekuensi Kunjungan</th>
						<th style='text-align: center'>Nilai Total Pembelanjaan</th>					
					</tr>
				</thead>
				<tbody>";
										
				$nomor = 1;
				foreach($d['keu'] as $row)
				{                       
					
					$tgllahir = date('d-m-Y',strtotime($row->tgllahir));
					// $konsultasi_tindakan = wordwrap($row->konsultasi_tindakan, 50,'<br>\n');
					$konsultasi_tindakan = $row->konsultasi_tindakan;
					// $nadokter = wordwrap($row->nadokter, 50,'<br>\n');
					$nadokter = $row->nadokter;
					$frekuensi = number_format($row->frekuensi,2,'.',',');
					$nilai_pembelanjaan = number_format($row->nilai_pembelanjaan,2,'.',',');

					
					$chari .= "<tr>
						<td align='center'>$row->rekmed</td>
						<td align='center'>$row->koders</td>
						<td align='center'>$row->namapas</td>
						<td align='center'>$row->jkel</td>
						<td align='center'>$tgllahir</td>
						<td>$row->umur</td>
						<td>$row->alamat</td>
						<td>$row->telp</td>
						<td>$konsultasi_tindakan</td>
						<td>$nadokter</td>
						<td>-</td>
						<td>$row->tgl_kunjungan_pertama</td>
						<td>$row->tgl_kunjungan_terakhir</td>
						<td align='right'>$frekuensi</td>
						<td align='right'>$nilai_pembelanjaan</td>
					</tr>";

					$nomor++;
				} 
			$chari .= "</tbody>
					";

			// $chari .= "
			// 	<tr>
			// 		<td colspan='9' style='text-align:right'>Total:</td>
			// 		<td style='text-align:right'>$ttl_jumlahhutang</td>
			// 		<td style='text-align:right'>$ttl_inacbg</td>
			// 		<td style='text-align:right'>$ttl_jumlahbayar</td>
			// 		<td style='text-align:right'>$ttl_sisa</td>
			// 		<td></td>
			// 	</tr>";
			$chari .= "
				</table>";

			// if($this->input->post('proses') == 'Print'){
			if($id == '1'){
				$this->M_cetak->mpdf('L','A4',$judul, $chari,'laporan_kunjungan.PDF', 10, 10, 10, 0);
				
				// echo $chari;
			} else {
				header('Content-type: application/vnd-ms-excel');
				header('Content-Disposition: attachment; filename=laporan_kunjungan.xls');
				header('Pragma: no-cache');
				header('Expires: 0');
				
				echo $chari;
			}
		
		} else
		{
			header('location:'.base_url());
		}
	}

	
	public function export2($id){
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        
		if(!empty($cek))
		{	
			$unit= $this->session->userdata('unit');	
			$akses= $this->M_global->cek_menu_akses($cek, 5203);

            $d['startdate'] = '';
            $d['enddate'] 	= '';
            $d['jenis_kunjungan'] 	= 'frekuensi';
			$d['keu'] = array();

            // $startdate				= $this->input->post('startdate');
            // $enddate				= $this->input->post('enddate');
            // $jenis_kunjungan		= $this->input->post('jenis_kunjungan');

			$startdate				= $this->input->get('startdate');
            $enddate				= $this->input->get('enddate');
            $jenis_kunjungan		= $this->input->get('jenis_kunjungan');
			
            // $jenis_kunjungan		= $id;
			
            $d['startdate']			= $startdate;
            $d['enddate']			= $enddate;
            $d['jenis_kunjungan']	= $jenis_kunjungan;

            $d['keu'] = $this->M_laporan->get_kunjungan_pasien($startdate, $enddate, $jenis_kunjungan);
			
			
			// $this->load->view('penjualan/v_detailPiutang_exp',$d);

			$judul = "Laporan Kunjungan Pasien";
			$chari = "";
			
			// $chari .= $judul;
			$chari .= "<table style='border-collapse:collapse;font-family: tahoma; font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='3'>
				<thead>
					<tr>
						<th style='text-align: center'>No. Member</th>
						<th style='text-align: center'>Cabang Awal</th>
						<th style='text-align: center'>Nama</th>
						<th style='text-align: center'>Jenis Kelamin</th>
						<th style='text-align: center'>Tanggal Lahir</th>
						<th style='text-align: center'>Umur</th>
						<th style='text-align: center'>Alamat</th>
						<th style='text-align: center'>No. Telepon</th>
						<th style='text-align: center; width: 10px !important;' >Konsultasi & Tindakan</th>
						<th style='text-align: center'>Dokter</th>
						<th style='text-align: center'>Paramedis</th>
						<th style='text-align: center'>Tgl. Kunjungan Pertama</th>
						<th style='text-align: center'>Tgl. Kunjungan Terakhir</th>
						<th style='text-align: center'>Frekuensi Kunjungan</th>
						<th style='text-align: center'>Nilai Total Pembelanjaan</th>					
					</tr>
				</thead>
				<tbody>";
										
				$nomor = 1;
				foreach($d['keu'] as $row)
				{                       
					
					$tgllahir = date('d-m-Y',strtotime($row->tgllahir));
					// $konsultasi_tindakan = wordwrap($row->konsultasi_tindakan, 50,'<br>\n');
					$konsultasi_tindakan = $row->konsultasi_tindakan;
					// $nadokter = wordwrap($row->nadokter, 50,'<br>\n');
					$nadokter = $row->nadokter;
					$frekuensi = number_format($row->frekuensi,2,'.',',');
					$nilai_pembelanjaan = number_format($row->nilai_pembelanjaan,2,'.',',');

					
					$chari .= "<tr>
						<td align='center'>$row->rekmed</td>
						<td align='center'>$row->koders</td>
						<td align='center'>$row->namapas</td>
						<td align='center'>$row->jkel</td>
						<td align='center'>$tgllahir</td>
						<td>$row->umur</td>
						<td>$row->alamat</td>
						<td>$row->telp</td>
						<td>$konsultasi_tindakan</td>
						<td>$nadokter</td>
						<td>-</td>
						<td>$row->tgl_kunjungan_pertama</td>
						<td>$row->tgl_kunjungan_terakhir</td>
						<td align='right'>$frekuensi</td>
						<td align='right'>$nilai_pembelanjaan</td>
					</tr>";

					$nomor++;
				} 
			$chari .= "</tbody>
					";

			// $chari .= "
			// 	<tr>
			// 		<td colspan='9' style='text-align:right'>Total:</td>
			// 		<td style='text-align:right'>$ttl_jumlahhutang</td>
			// 		<td style='text-align:right'>$ttl_inacbg</td>
			// 		<td style='text-align:right'>$ttl_jumlahbayar</td>
			// 		<td style='text-align:right'>$ttl_sisa</td>
			// 		<td></td>
			// 	</tr>";
			$chari .= "
				</table>";

			// if($this->input->post('proses') == 'Print'){
			if($id == '1'){
				$this->M_cetak->mpdf('L','A4',$judul, $chari,'laporan_kunjungan.PDF', 10, 10, 10, 0);
				
				// echo $chari;
			} else {
				header('Content-type: application/vnd-ms-excel');
				header('Content-Disposition: attachment; filename=laporan_kunjungan.xls');
				header('Pragma: no-cache');
				header('Expires: 0');
				
				echo $chari;
			}
		
		} else
		{
			header('location:'.base_url());
		}
	}

}