<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Piutang extends CI_Controller {

	
	
	public function __construct()
	{
		parent::__construct();
		$this->session->set_userdata('menuapp', '5000');
		$this->session->set_userdata('submenuapp', '5301');
		$this->load->helper('simkeu_nota');
		$this->load->model('M_piutang');
		$this->load->model('M_cetak');
		$this->load->model('M_rs'); 
		$this->load->model('M_global'); 
	}

	
	public function index()
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        		
		setlocale(LC_ALL, 'id_ID');
		if(!empty($cek)){	
			$d['startdate'] = $this->input->get('fromdate') ?? date('Y-m-01');
			$d['enddate'] 	= $this->input->get('todate') ?? date('Y-m-d');

			$bulan =  $this->M_global->_periodebulan();
			$tahun =  $this->M_global->_periodetahun();

			$ttlPiutang = $this->M_piutang->getTotalPiutang($unit);
			$ttlAsuransi = $this->M_piutang->getTotalAsuransi($unit);
			$ttlBpjs = $this->M_piutang->getTotalBpjs($unit);

			$d['totalPiutang'] = $ttlPiutang[0]->total;
			$d['totalAsuransi'] = $ttlAsuransi[0]->asuransi;
			$d['totalSimrs'] = $ttlBpjs[0]->simrs;
			$d['totalInacbg'] = $ttlBpjs[0]->inacbg;

			$d['keu'] = $this->M_piutang->get_ar_summary($unit, $d['startdate'], $d['enddate']);

			$nbulan = $this->M_global->_namabulan($bulan);
			$periode= 'Periode '.strftime('%e %B %Y', strtotime($d['startdate'])).' - '.strftime('%e %B %Y', strtotime($d['enddate']));

			$level=$this->session->userdata('level');		
			$akses= $this->M_global->cek_menu_akses($level, 5104);
			$d['akses']= $akses;		  
			$d['periode'] = $periode;	  

			$this->load->view('penjualan/v_piutang',$d);			   
		} else{		
			header('location:'.base_url());
			
		}
	}

	public function invoice($cust_id){
		setlocale(LC_ALL, 'id_ID');
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        
		if(!empty($cek))
		{	
			if($cust_id != ""){
				$d['asal'] = '';
				$d['penjamin'] = $this->M_piutang->get_vendors($cust_id);
				
				$fromdate = $this->input->get('fromdate');
				$todate = $this->input->get('todate');
				$d['periode']= 'Periode <b>'.strftime('%e %B %Y',strtotime($fromdate)).'</b> s/d <b>'.strftime('%e %B %Y',strtotime($todate)).'</b>';
				$d['fromdate'] = $fromdate;
				$d['todate'] = $todate;
				$d['keu'] = $this->M_piutang->get_invoices($unit, $cust_id, $fromdate, $todate);	
				$d['akses'] = $this->M_global->cek_menu_akses($cek, 5301);
			} 

			$this->load->view('penjualan/v_invoicePiutang',$d);
		} else
		{
			header('location:'.base_url());
		}
	}

	/**
	 * Method untuk menampilkan form pembuatan invoice
	 * 
	 * @param string $cust_id
	 */
	public function tagihan_pelanggan_entry(string $cust_id){
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        
		if(!empty($cek)){
			$d['vendor'] = $this->M_piutang->get_vendors($cust_id);
			$d['fromdate'] = $this->input->get('startdate');
			$d['todate'] = $this->input->get('enddate');
			$d['cabang'] = $unit;

			$this->load->view('penjualan/v_buatTagihanPelanggan_piutang',$d);
		} else
		{
			header('location:'.base_url());
		}
	}

	/**
	 * Method untuk menampilkan form edit invoice
	 * 
	 * @param string $invoiceno
	 */
	public function tagihan_pelanggan_edit(string $invoiceno){
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        
		if(!empty($cek))
		{
			$data = [
				'invoice' => $this->M_piutang->get_invoice_detail($invoiceno),
				'cabang' => $unit
			];
			$this->load->view('penjualan/v_editTagihanPelanggan_piutang',$data);
		} else
		{
			header('location:'.base_url());
		}
	}

	/**
	 * Method untuk menhapus invoice
	 * 
	 * @param string $invoiceno
	 */
	public function tagihan_pelanggan_hapus(string $invoiceno)
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        
		if(!empty($cek))
		{
			$result = $this->M_piutang->delete_invoice($invoiceno);
			if ($result) return redirect($_SERVER['HTTP_REFERER']);
		} else
		{
			header('location:'.base_url());
		}
	}
		
	/**
	 * Method untuk mendapatkan data detail piutang dari vendor tertentu
	 * 
	 * @param string $cust_id
	 * @return object
	 */

	public function detail_piutang(string $cust_id)
	{
		$cek = $this->session->userdata('level');	
		$unit= $this->session->userdata('unit');

		$param = (object) [
			'jenis' => $this->input->get('jenis') ?? 'all',
			'cabang' => $unit,
			'fromdate' => $this->input->get('fromdate'),
			'todate' => $this->input->get('todate'),
			'vendor' => $cust_id
		];
		$data = $this->M_piutang->get_ar_detail_data($param)[0];
        
		if(!empty($cek))
		{	
			$this->load->view('penjualan/v_detailPiutang',['input' => $param, 'data' => $data]);
		} else
		{
			header('location:'.base_url());
		}
	}

	/**
	 * Method untuk mendapatkan data transaksi piutang dengan AJAX
	 * 
	 * @return array
	 */
	public function transactions ()
	{
		$unit = $this->session->userdata('unit');
		$param = (object) $this->input->get();
		$param->cabang = $unit;

		$result = $this->M_piutang->get_ar_transactions ($param);

		echo json_encode($result);
	}

	/**
	 * Method untuk menghapus pasien
	 * 
	 * @param int $id
	 */
	public function hapus_pasien(int $id)
	{
		$result =  $this->db->update('tbl_pap', ['invoiceno' => ''], ['id' => $id]);

		echo json_encode((object) ['status' => $result]);
	}


	/**
	 * Method untuk menampilkan daftar pasien yang akan ditagihkan
	 * 
	 * @param string $cust_d
	 * @return array
	 */
	public function pilih_pasien(string $cust_id){
		$cek = $this->session->userdata('level');		
		$unit = $this->session->userdata('unit');	
        $param = (object) $this->input->get();
		$param->vendor = $cust_id;
		$param->cabang = $unit;

		if(!empty($cek))
		{	
			$transactions = $this->M_piutang->get_billable_patient ($param);
			echo json_encode($transactions);
		} else
		{
			header('location:'.base_url());
		}
	}

	/**
	 * Method untuk menyimpan tagihan pelanggan
	 * 
	 * @param string $cust_id
	 */
	public function save_tagihan_pelanggan($cust_id)
	{
		$cek = $this->session->userdata('level');
		if(!empty($cek))
		{			
			$id_pasien = $this->input->post('id_pasien');
			$diskon = strlen($this->input->post('diskon')) > 0 ? $this->input->post('diskon') : 0;
			$diskonrp = $diskon / 100 * $this->input->post('jumlahrp');
			$totalnetrp = $this->input->post('jumlahrp') - $diskonrp;
			$invoiceno = urut_transaksi('INVOICE_KLAIM',16);
			
			if ($this->input->post('jenis') == 'INAP'){
				$invoiceno = str_replace('RJ', 'RI', $invoiceno);
			}

			if ($this->input->post('jenis') == 'all'){
				$invoiceno = str_replace('RJ', 'AL', $invoiceno);
			}

			$data = [
				'koders' => $this->session->userdata('unit'),
				'invoiceno' => $invoiceno,
				'invoicedate' => $this->input->post('invoicedate'),
				'duedate' => $this->input->post('duedate'),
				'dariperiode' => $this->input->post('dariperiode'),
				'sampaiperiode' => $this->input->post('sampaiperiode'),
				'cust_id' => $cust_id,
				'jumlahrp' => $this->input->post('jumlahrp'),
				'jenis' => $this->input->post('jenis') == 'POLI' ? '1' : ($this->input->post('jenis') == 'INAP' ? '2' : '3'),
				'diskon' => $diskon,
				'diskonrp' => $diskonrp,
				'totalnetrp' => $totalnetrp,
				'keterangan' => $this->input->post('keterangan'),
				'lunas' => 0,
				'ditagihkan' => $cust_id,
				'username' => $this->session->userdata('username'),
				'tglentry' => date('Y-m-d H:i:s'),
			];

			$status = $this->M_piutang->save_invoice($data);

			for ($i=0; $i < count($id_pasien); $i++) { 
				$value = [
					'id' => $id_pasien[$i],
					'invoiceno' => $data['invoiceno']
				];

				$status = $this->M_piutang->update_ar($value);

				if ($status == false) {
					echo json_encode((object) ['status' => $status]);
					exit;
				}
			}

			echo json_encode((object) ['status' => $status]);
		}
		else
		{
			header('location:'.base_url());
		}
	}

	/**
	 * Method untuk update invoice
	 * 
	 * @param string $invoiceno
	 */
	public function update_tagihan_pelanggan(string $invoiceno)
	{
		$cek = $this->session->userdata('level');
		if(!empty($cek))
		{			
			$id_pasien = $this->input->post('id_pasien') ?? [];
			$diskon = strlen($this->input->post('diskon')) > 0 ? $this->input->post('diskon') : 0;
			$diskonrp = $diskon / 100 * $this->input->post('jumlahrp');
			$totalnetrp = $this->input->post('jumlahrp') - $diskonrp;

			$data = [
				'invoiceno' => $invoiceno,
				'invoicedate' => $this->input->post('invoicedate'),
				'duedate' => $this->input->post('duedate'),
				'dariperiode' => $this->input->post('dariperiode'),
				'sampaiperiode' => $this->input->post('sampaiperiode'),
				'jumlahrp' => $this->input->post('jumlahrp'),
				'jenis' => $this->input->post('jenis') == 'POLI' ? '1' : ($this->input->post('jenis') == 'INAP' ? '2' : '3'),
				'diskon' => $diskon,
				'diskonrp' => $diskonrp,
				'totalnetrp' => $totalnetrp,
				'keterangan' => $this->input->post('keterangan'),
				'lunas' => 0,
				'useredit' => $this->session->userdata('username'),
				'tgledit' => date('Y-m-d H:i:s'),
			];

			$status = $this->M_piutang->save_invoice($data);

			for ($i=0; $i < count($id_pasien); $i++) { 
				$value = [
					'id' => $id_pasien[$i],
					'invoiceno' => $data['invoiceno'],
					'tgljatuhtempo' => $data['duedate']
				];

				$status = $this->M_piutang->update_ar($value);

				if ($status == false) {
					echo json_encode((object) ['status' => $status]);
					exit;
				}
			}

			echo json_encode((object) ['status' => $status]);
		}
		else
		{
			header('location:'.base_url());
		}
	}

	public function export_invoice($id){
		$cek 	= $this->session->userdata('level');
		if(!empty($cek))
		{				
			$unit		= $this->session->userdata('unit');	
			$userid 	= $this->session->userdata('username');
			$cust_id 	= $this->input->get('cust_id');
			$cust_nama 	= $this->input->get('cust_nama');
			$startdate 	= $this->input->get('startdate');
			$enddate 	= $this->input->get('enddate');
			$invoiceno 	= $this->input->get('invoiceno');

			$rs = $this->M_rs->getNamaRsById($unit);

			if($rs){
				foreach($rs as $dtrs);

				$wa = '';$fax = '';
				if($dtrs->whatsapp != '') $wa = " / ".$dtrs->whatsapp;
				if($dtrs->fax != '') $fax = " / ".$dtrs->fax;

				$telp = ($dtrs->phone)."".$wa."".$fax;

				
				// $qry = "					
				// 		SELECT *
				// 		FROM tbl_papinvoice
				// 		WHERE invoiceno = '$invoiceno';
				// ";

				// $dt = $this->db->query($qry)->result();

				// foreach($dt as $row);

				// $d['jenis'] = $row->jenis;
				// $d['dariperiode'] = $row->dariperiode;
				// $d['invoicedate'] = $row->invoicedate;
				// $d['sampaiperiode'] = $row->sampaiperiode;
				// $d['duedate'] = $row->duedate;
				// $d['keterangan'] = $row->keterangan;

				// $d['diskon'] = $row->diskon;
				// $d['jumlahrp'] = $row->jumlahrp;
				// $d['diskonrp'] = $row->diskonrp;
				// $d['totalnetrp'] = $row->totalnetrp;

				
				// $dt = true; // $this->M_kas_bank->getPembayaranHutanById($id, $unit);
				
				$qry = "					
					SELECT *
					FROM tbl_papinvoice
					WHERE invoiceno = '$invoiceno';
				";

				$dt = $this->db->query($qry)->result();

				
				$qry2 = "
					SELECT *
					FROM pasien_piutang
					WHERE invoiceno = '$invoiceno';
				";

				$dt2 = $this->db->query($qry2)->result();
				$jumlah_kunjungan = $this->db->query($qry2)->num_rows();

				if($dt && $dt2){
					foreach($dt as $data);
					$judul = '';$chari = '';
					// $chari = $this->load->view('pembelian/v_hutang_cetak',$d);

					// $totalbayar = number_format($data->totalbayar,0,'.',',');
					// // $terbilang = ucwords($this->M_global->terbilang($data->totalsemua))." Rupiah";
					// $date_create = date_create($data->pay_date);
					// $tanggal = date_format($date_create,'d-m-Y');
					$arr = array(" TIMUR", " BARAT", " SELATAN", " UTARA");
					$kota = $dtrs->kota;
					$kota = str_replace("KOTA ", "", $kota);
					$daerah = $kota;
					for($i = 0; $i < count($arr); $i++){
						$kota = str_replace($arr[$i], "", $kota);
					}
			
					if($data->jenis == 1) $jns = 'Rawat Jalan';
					else if($data->jenis == 2) $jns = 'Rawat Inap';
					else $jns = "Rawat jalan dan Rawat Inap";

					$tgl_dari = date('d', strtotime($data->dariperiode));
					$bln = date('m', strtotime($data->dariperiode));
					if(substr($bln,0,1) == '0') $bln = str_replace('0','',$bln);
					$bln_dari = $this->M_global->_namabulan($bln);
					$thn_dari = date('Y', strtotime($data->dariperiode));

					$tgl_sampai = date('d', strtotime($data->sampaiperiode));
					$bln2 = date('m', strtotime($data->sampaiperiode));
					if(substr($bln2,0,1) == '0') $bln2 = str_replace('0','',$bln2);
					$bln_sampai = $this->M_global->_namabulan($bln2);
					$thn_sampai = date('Y', strtotime($data->sampaiperiode));


					// $dariperiode = date('d-m-Y', strtotime($data->dariperiode));
					// $sampaiperiode = date('d-m-Y', strtotime($data->sampaiperiode));
					$dariperiode = $tgl_dari." ".ucwords(strtolower($bln_dari))." ".$thn_dari;
					$sampaiperiode = $tgl_sampai." ".ucwords(strtolower($bln_sampai))." ".$thn_sampai;

					$periode = $dariperiode;
					if($dariperiode != $sampaiperiode) $periode = $dariperiode." sampai ".$sampaiperiode;

					// $jns = 'Rawat Jalan';
					// echo "<pre>";
					// print_r($dt);
					// echo "</pre>";
					$tglSekarang = date('d-m-Y');

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
						<br>";

					$chari .= "<div width='100%' style='border-top:1px solid black;'></div><br>";

					$chari .= "
					<table style='border-collapse:collapse;font-family: tahoma; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='3'>
						<tr rowspan='6' class='show1'>
							<td align='left'  width='5%'>Nomor</td>
							<td align='center'  width='5%'>:</td>
							<td align='left'  width='40%'>$invoiceno</td>
						
							<td align='left'  width='15%'></td>
							<td align='center'  width='5%'></td>
							<td align='left'  width='30%'>$kota, $tglSekarang</td>
						</tr>		
						
						<tr class='show1'>
							<td align='left'  width='15%'>Lampiran</td>
							<td align='center'  width='5%'>:</td>
							<td align='left'  width='30%'>1 (satu) Bundel</td>
						
							<td align='left'  width='15%'></td>
							<td align='center'  width='5%'></td>
							<td align='left'  width='30%'>Kepada Yang Terhormat,</td>
						</tr>		
						<tr class='show1'>
							<td align='left'  width='15%'>Perihal</td>
							<td align='center'  width='5%'>:</td>
							<td align='left'  width='30%'><b><u>Tagihan</u></b></td>
						
							<td align='left'  width='15%'></td>
							<td align='center'  width='5%'></td>
							<td align='left'  width='30%'>Pimpinan</td>
						</tr>		
						<tr class='show1'>
							<td align='left'  width='15%'></td>
							<td align='center'  width='5%'></td>
							<td align='left'  width='30%'></td>
						
							<td align='left'  width='15%'></td>
							<td align='center'  width='5%'></td>
							<td align='left'  width='30%'><b>$cust_nama</b></td>
						</tr>		
						<tr class='show1'>
							<td align='left'  width='15%'></td>
							<td align='center'  width='5%'></td>
							<td align='left'  width='30%'></td>
						
							<td align='left'  width='15%'></td>
							<td align='center'  width='5%'></td>
							<td align='left'  width='30%'>D.a: $daerah</td>
						</tr>	
					</table>";
					
					$chari .= "<br>";
					$chari .= "<div width='100%' style='font-family: tahoma; font-size:12px'>Dengan Hormat,</div>
					<div style='font-family: tahoma; font-size:12px; text-align: justify;'>Bersama dengan ini kami kirimkan tagihan biaya pelayanan kesehatan karyawan <b>$cust_nama</b> yang dirawat di <b>$dtrs->namars</b>, periode dari <b>$periode</b> sebanyak <b>$jumlah_kunjungan</b> kunjungan pasien <b>$jns</b> dengan rincian sebagai berikut:</div><br>";

					$chari .= "<table style='border-collapse:collapse;font-family: tahoma; font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='3'>
					<tr>
						<th>NO.</th>
						<th>TANGGAL</th>
						<th>NAMA PASIEN</th>
						<th>NO. DOKUMEN</th>
						<th>NO. PESERTA</th>
						<th>BIAYA</th>
					</tr>";
					
					$no = 0;
					$ttl = 0;
					foreach ($dt2 as $data2) {
						$tglposting = date('d-m-Y', strtotime($data2->tglposting));
						$jumlahhutang = number_format($data2->jumlahhutang,0,',','.');

						$no = ($no+1);
						$chari .= "<tr>
							<td align='center'>$no</td>
							<td align='center'>$tglposting</td>
							<td align='left'>$data2->namapas</td>
							<td align='left'>$data2->nosep</td>
							<td align='left'>$data2->nocard</td>
							<td align='right'>$jumlahhutang</td>
						</tr>";
						
						$ttl = $ttl + $data2->jumlahhutang;
						
					}

					$diskon = 0;
					$totalnet = $ttl - $diskon;
					$ttl = number_format($ttl,0,',','.');
					$totalnet_format = number_format($totalnet,0,',','.');
					
					
					$chari .= "
					<tr>
						<td colspan='5' align='right' style='border:none;border-left: 1px solid black;'>JUMLAH</td>
						<td align='right' style='border:none;border-right: 1px solid black;'><b>$ttl</b></td>
					</tr>
					<tr>
						<td colspan='5' align='right' style='border:none;border-left: 1px solid black;'>DISKON</td>
						<td align='right' style='border:none;border-bottom: 1px solid black;border-right: 1px solid black;'><b>$diskon</b></td>
					</tr>
					<tr>
						<td colspan='5' align='right' style='border:none;border-left: 1px solid black;border-bottom: 1px solid black;'>TOTALNET</td>
						<td align='right' style='border:none;border-bottom: 1px solid black;border-right: 1px solid black;'><b>$totalnet_format</b></td>
					</tr>";
					$chari .= "</table>";
					
					$chari .= "<br>";
					
					$terbilang = ucwords($this->M_global->terbilang($totalnet))." Rupiah";
					$chari .= "<div width='100%' style='font-family: tahoma; font-size:12px'><i>Terbilang: #($terbilang)#</i></div>";

					$chari .= "<div width='100%' style='font-family: tahoma; font-size:12px'>dengan rincian, vide terlampir.</div>";
					$chari .= "<div width='100%' style='font-family: tahoma; font-size:12px'>Dan melalui surat ini kami cantumkan No. Rekening dan atas nama:</div><br>";
					$chari .= "<div width='50%' style='font-family: tahoma; font-size:12px; border:1px solid black; border-radius:5px;padding:5px;'>UNTUK REALISASI PENAGIHAN: BANK BNI CABANG GATOR SUBROTO NO. REK: 8817777797 A/N: PT BHAKTI RAHAYU PUTRA DEWATA</div><br>";
					$chari .= "<div width='100%' style='font-family: tahoma; font-size:12px'>Demikian surat tagihan ini kami sampaikan, atas segala perhatian dan kerjasamanya, kami haturkan terimakasih.</div><br>";
					$chari .= "<div width='100%' style='display: flex!important;justify-content: space-between!important;'>";
					$chari .= "<div width='40%' style='float:left;font-family: tahoma; font-size:10px; border:1px solid black; border-radius:5px;padding:5px;'><i>NB:<br>Harap dicantumkan berita:<br>- Nomor surat tagihan dari RS.<br>- Nama Perusahaan Pengirim<br>Untuk Pembayaran melalui transfer</div>";
					$chari .= "<div width='30%' style='float:right;font-family: tahoma; font-size:10px; padding:5px;text-align:center;'>$kota, $tglSekarang<br><br><br><br><b>$userid</b></div>";
					$chari .= "</div>";

					// echo $chari;
					$this->M_cetak->mpdf('P','A4',$judul, $chari,'Invoice_Piutang.PDF', 10, 10, 10, 0);
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

	public function export($id){
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        
		if(!empty($cek))
		{	
			// ?cust_id=".$cust_id."&asal=".$asal."&startdate=".$startdate."&enddate=".$enddate;
			$cust_id = $this->input->get('cust_id');
			$asal = $this->input->get('asal');
			$startdate = $this->input->get('startdate');
			$enddate = $this->input->get('enddate');

			if($asal != '' && $asal != 'semua') $asal = "AND asal = '".$asal."'";
			else $asal = '';

			$sdate = '';
			$edate = '';
			if($sdate != '') $sdate = "AND tglposting >= '$startdate'";
			if($edate != '') $edate = "AND tglposting <= '$startdate'";
			
			$q1 = "
					SELECT *
					FROM pasien_piutang
					WHERE koders = '$unit'
						AND cust_id = '$cust_id'
						$sdate
						$edate
						$asal
					;";

			$d['keu'] = $this->db->query($q1)->result();	
			$d['akses'] = $this->M_global->cek_menu_akses($cek, 5301);	
			
			// $this->load->view('penjualan/v_detailPiutang_exp',$d);

			$judul = "Daftar Piutang";
			$chari = "";
			$chari .= "<table style='border-collapse:collapse;font-family: tahoma; font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='3'>
				<thead>
					<tr>
						<th style='text-align: center'>No. Faktur</th>
						<th style='text-align: center'>No. Reg</th>
						<th style='text-align: center'>Rekmed</th>
						<th style='text-align: center'>Tgl AR</th>
						<th style='text-align: center'>No Kartu</th>
						<th style='text-align: center'>No Sep</th>
						<th style='text-align: center'>Nama Pasien</th>
						<th style='text-align: center'>Asal</th>
						<th style='text-align: center'>Tujuan</th>
						<th style='text-align: center'>Jumlah Piutang</th>
						<th style='text-align: center'>Inacbg Grouper</th>
						<th style='text-align: center'>Dibayar</th>
						<th style='text-align: center'>Saldo Piutang</th>
						<th style='text-align: center'>Invoice</th>
					</tr>
				</thead>
				<tbody id='dataPiutang'>";
										
				$nomor = 1;
				$total_jumlahhutang = 0;
				$total_inacbg = 0;
				$total_jumlahbayar = 0;
				$total_sisa = 0;

				$ttl_jumlahhutang = 0;
				$ttl_inacbg = 0;
				$ttl_jumlahbayar = 0;
				$ttl_sisa = 0;
				foreach($d['keu'] as $row)
				{                       
					$total_jumlahhutang += $row->jumlahhutang;
					$total_inacbg += $row->inacbg;
					$total_jumlahbayar += $row->jumlahbayar;
					$total_sisa += ($row->jumlahhutang - $row->jumlahbayar);
			
					$tglposting = date('d-m-Y',strtotime($row->tglposting));
					$asl = $row->asal == 'POLI' ? 'RAJAL' : 'RANAP';
					$tjn = $row->asal == 'POLI' ? $row->namapost : 'RAWAT INAP';
					$jmlhutang = number_format($row->jumlahhutang,2,'.',','); 
					$inacbg = number_format($row->inacbg,2,'.',',');
					$jmlbayar = number_format($row->jumlahbayar,2,'.',',');
					$sisa = number_format(($row->jumlahhutang - $row->jumlahbayar),2,'.',','); 
					$invoiceno = $row->invoiceno;

					$ttl_jumlahhutang = number_format($total_jumlahhutang,2,'.',','); 
					$ttl_inacbg = number_format($total_inacbg,2,'.',','); 
					$ttl_jumlahbayar = number_format($total_jumlahbayar,2,'.',','); 
					$ttl_sisa = number_format($total_sisa,2,'.',','); 

					$chari .= "<tr class='show1' id='$row->noreg'>
						<td align='center'>$row->fakturno</td>
						<td align='center'>$row->noreg</td>
						<td align='center'>$row->rekmed</td>
						<td align='center'>$tglposting</td>
						<td>$row->nocard</td>
						<td>$row->nosep</td>
						<td>$row->namapas</td>
						<td>$asl</td>
						<td>$tjn</td>
						<td align='right'>$jmlhutang</td>
						<td align='right'>$inacbg</td>
						<td align='right'>$jmlbayar</td>
						<td align='right'>$sisa</td>
						<td>$invoiceno</td>
					</tr>";

					$nomor++;
				} 
			$chari .= "</tbody>
					";

			$chari .= "
				<tr>
					<td colspan='9' style='text-align:right'>Total:</td>
					<td style='text-align:right'>$ttl_jumlahhutang</td>
					<td style='text-align:right'>$ttl_inacbg</td>
					<td style='text-align:right'>$ttl_jumlahbayar</td>
					<td style='text-align:right'>$ttl_sisa</td>
					<td></td>
				</tr>
			</table>";

			if($id == 1){
				$this->M_cetak->mpdf('P','A4',$judul, $chari,'Piutang.PDF', 10, 10, 10, 1);
				
				// echo $chari;
			} else {
				header('Content-type: application/vnd-ms-excel');
				header('Content-Disposition: attachment; filename=piutang.xls');
				header('Pragma: no-cache');
				header('Expires: 0');
				
				echo $chari;
			}
		
		} else
		{
			header('location:'.base_url());
		}
	}

	public function detailPiutangTerbentuk(){
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        
		if(!empty($cek))
		{	

			$d['akses'] = $this->M_global->cek_menu_akses($cek, 5301);		  
			$this->load->view('penjualan/v_detailPiutangTerbentuk',$d);
		} else
		{
			header('location:'.base_url());
		}
	}

	public function piutangTerbentuk(){
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        
		if(!empty($cek))
		{			
			$postData = $this->input->post();

			// Get data
			$data = $this->M_piutang->detailPiutangTerbentuk($postData);

			echo json_encode($data);
		} else
		{
			header('location:'.base_url());
		}
	}

	public function detailPiutangTerbentuk2(){
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        
		if(!empty($cek))
		{	
			$q1 = 
				"select *
				from
				   tbl_pap a left outer join
				   tbl_penjamin b
				   on a.cust_id=b.cust_id
				where
					-- a.koders = '$unit'
					a.koders = '$unit'
				order by
				   a.tglposting, a.noreg desc
				limit 10";
				
			$d['keu'] = $this->db->query($q1)->result();	
			$d['akses'] = $this->M_global->cek_menu_akses($cek, 5301);		  
			$this->load->view('penjualan/v_detailPiutangTerbentuk2',$d);
		} else
		{
			header('location:'.base_url());
		}
	}
	
	
	public function detailPiutangAsuransi_pt(){
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        
		if(!empty($cek))
		{	
			$d['akses'] = $this->M_global->cek_menu_akses($cek, 5301);		  
			$this->load->view('penjualan/v_detailPiutangAsuransi_pt',$d);
		} else
		{
			header('location:'.base_url());
		}
	}	

	public function piutangAsuransi_pt(){
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        
		if(!empty($cek))
		{			
			$postData = $this->input->post();
			// Get data
			$data = $this->M_piutang->detailPiutangAsuransi_pt($postData);
			echo json_encode($data);
		} else
		{
			header('location:'.base_url());
		}
	}

	public function detailPiutangAsuransi_pt2(){
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        
		if(!empty($cek))
		{	
			$q1 = 
				"select *
				from
				   tbl_pap a left outer join
				   tbl_penjamin b
				   on a.cust_id=b.cust_id
				where
					-- a.koders = '$unit'
					a.cust_id <> 'BPJS' AND 
					a.koders = 'DTI'
				order by
				   a.tglposting, a.noreg desc
				limit 10";
				
			$d['keu'] = $this->db->query($q1)->result();	
			$d['akses'] = $this->M_global->cek_menu_akses($cek, 5301);		  
			$this->load->view('penjualan/v_detailPiutangAsuransi_pt',$d);
		} else
		{
			header('location:'.base_url());
		}
	}


	public function detailPiutang_bpjs(){
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        
		if(!empty($cek))
		{	
			$d['akses'] = $this->M_global->cek_menu_akses($cek, 5301);		  
			$this->load->view('penjualan/v_detailPiutangBpjs',$d);
		} else
		{
			header('location:'.base_url());
		}
	}	

	public function piutang_bpjs(){
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        
		if(!empty($cek))
		{			
			$postData = $this->input->post();
			// Get data
			$data = $this->M_piutang->detailPiutang_bpjs($postData);
			echo json_encode($data);
		} else
		{
			header('location:'.base_url());
		}
	}

	public function detailPiutang_bpjs2(){
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');	
        
		if(!empty($cek))
		{	
			$q1 = 
				"select *
				from
				   tbl_pap a left outer join
				   tbl_penjamin b
				   on a.cust_id=b.cust_id
				where
					-- a.koders = '$unit'
					a.cust_id = 'BPJS' AND 
					a.koders = 'DTI'
				order by
				   a.tglposting, a.noreg desc
				limit 10";
				
			$d['keu'] = $this->db->query($q1)->result();	
			$d['akses'] = $this->M_global->cek_menu_akses($cek, 5301);		  
			$this->load->view('penjualan/v_detailPiutangBpjs',$d);
		} else
		{
			header('location:'.base_url());
		}
	}
	
	/**
	 * Method untuk mencetak invoice
	 * 
	 * @param string $invoiceno
	 */
	public function cetak_invoice (string $invoiceno)
	{
		$cek = $this->session->userdata('level');		
		$unit= $this->session->userdata('unit');		
		if(!empty($cek))
		{				  		 
			$cek = $this->session->userdata('level');
			$unit = $this->session->userdata('unit');
			$kop = (object) $this->M_cetak->kop($unit);
			$kop->detail_rs = $this->M_rs->get_hospital_info($unit);
	
			if (!empty($cek)) {
				$data = $this->M_piutang->get_invoice_detail($invoiceno);

				$judul = "Laporan Detail Piutang";
				$this->M_cetak->mpdf('P', 'A4', $judul, $this->load
							->view('laporan_akuntansi/invoice', ['kop' => $kop, 'data' => $data,], true), 'Invoice_' . $invoiceno . '.PDF', 10, 10, 10, 2);
			}
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
	
	public function getpo($po)
	{   	    
        $data = $this->db->select('ap_podetail.*, inv_barang.namabarang')->join('inv_barang','inv_barang.kodeitem=ap_podetail.kodeitem','left')->get_where('ap_podetail',array('kodepo' => $po, 'statusid' =>1))->result();
		echo json_encode($data);
	}
	
	public function getpb($po)
	{   	    
        $data = $this->db->select('ap_lpbdetil.*, inv_barang.namabarang')->join('inv_barang','inv_barang.kodeitem=ap_lpbdetil.kodeitem','left')->get_where('ap_lpbdetil',array('nolpb' => $po))->result();
		echo json_encode($data);
	}
	
	public function getbiaya($po)
	{   	    
        $data = $this->db->select('ap_pobiaya.*, ms_akun.namaakun')->join('ms_akun','ms_akun.kodeakun=ap_pobiaya.kodeakun','left')->get_where('ap_pobiaya',array('kodepo' => $po))->result();
		echo json_encode($data);
	}
}
/* End of file keuangan_saldo.php */
/* Location: ./application/controllers/keuangan_saldo.php */