	<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Farmasi_kartustock extends CI_Controller
	{




	public function __construct()
	{
		parent::__construct();

		$this->load->model('M_global', 'M_global');
		$this->load->model('M_cetak');
		$this->load->model('M_KartuStock');
		$this->load->helper('simkeu_rpt');
		$this->load->helper('simkeu_nota');
		$this->session->set_userdata('menuapp', '3000');
		$this->session->set_userdata('submenuapp', '3306');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$unit = $this->session->userdata('unit');
			$this->load->helper('url');
			$d['cabang'] = $this->db->get_where('tbl_namers', array("koders" => $unit))->row();

			$this->load->view('farmasi/v_farmasi_kartustock', $d);
		} else {
			header('location:' . base_url());
		}
	}

	public function tampil()
	{
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');

		$profile = $this->M_global->_LoadProfileLap();
		$nama_usaha = $profile->nama_usaha;
		$motto = '';
		$alamat = '';
		$namaunit = $this->M_global->_namaunit($unit);
		//$data  = explode("~",$x)

		$query_data = $this->db->query("SELECT a.terima , a.keluar , a.saldoakhir, a.periodedate, a.koders, a.maksimumstock,
		b.hargabeli FROM tbl_apostocklog AS a JOIN tbl_logbarang AS b ON a.kodebarang = b.kodebarang 
		WHERE a.koders = '$unit' 
		")->result();

		$barang  = $this->input->get('barang');
		$gudang  = $this->input->get('gudang');
		$cabang  = $this->input->get('cabang');
		$tgl1    = $this->input->get('tgl1');
		$tgl2    = $this->input->get('tgl2');
		$query_cab		= $this->db->query("SELECT kota FROM tbl_namers WHERE koders = '$cabang'")->row();
		$query_gud		= $this->db->query("SELECT keterangan FROM tbl_depo WHERE depocode = '$gudang'")->row();
		// $namabarang = data_master('tbl_barang', array('kodebarang' => $barang))->namabarang;
		// var_dump($namabarang);die;

		// $query_saldo = 
		// "SELECT * from tbl_barangstock 
		// where koders = '$cabang' and kodebarang='$barang' and gudang='$gudang'
		// ";
		$_tgl1 = date('Y-m-d', strtotime($tgl1));
		$_tgl2 = date('Y-m-d', strtotime($tgl2));
		$_peri = 'Dari ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2));
		$_peri1 = 'Per Tgl. ' . date('d', strtotime($tgl2)) . ' ' . $this->M_global->_namabulan(date('n', strtotime($tgl2))) . ' ' . date('Y', strtotime($tgl2));


		$query_saldo =
			"SELECT * from tbl_barangstock 
		where koders = '$cabang' and kodebarang='$barang' and gudang='$gudang' OR gudang = 'LOKAL'
		--  AND tglso BETWEEN '$tgl1' AND '$tgl2'
		AND tglso >'$_tgl1' ";



		// $lap = $this->db->query($query_saldo)->row();

		// $_tanggalawal = $tgl1;
		// $saldo = $lap->saldoawal;

		// if($lap){
		// 	$_tanggalawal = $lap->lasttr;
		// 	$saldo = $lap->saldoawal;
		//   } else {
		// 	$_tanggalawal = '';	
		// 	$saldo = 0;
		//   }


		$lap = $this->db->query($query_saldo)->row();
		// var_dump($lap);die;

		$_tanggalawal = $tgl1;
		$saldo = $lap->saldoawal;


		if ($lap) {
			$_tanggalawal = $lap->lasttr;
			$saldo = $lap->saldoawal;
		} else {
			$_tanggalawal = '';
			$saldo = 0;
		}

		$sql = $this->db->get_where('tbl_barang', ['kodebarang' => $barang])->row_array();
		$data = [
			'cekk' => $this->session->userdata('level'),
			'unit'   	  => $this->session->userdata('unit'),
			'profile'      => $this->M_global->_LoadProfileLap(),
			'nama_usaha'   => $profile->nama_usaha,
			'motto'        => '',
			'alamat'       => '',
			'peri1'		   => $_peri1,
			'query_cab'		=> $query_cab,
			'gudang'		=> $query_gud,
			'cabang'       => $cabang,
			'kop' => $this->M_cetak->kop($unit),
			'data_list' => $this->M_KartuStock->tampildata($cabang, $barang, $gudang, $tgl1, $tgl2),
			'saldo'	=> $saldo,
			'gudang2' => $gudang,
			'tanggalwal'	=> $_tanggalawal,
			'barang' => $barang,
			'namabrg' => $sql['namabarang'],
		];
		$this->load->view('farmasi/v_farmasi_tampil', $data);
	}

	public function cetak()
	{
		$cek  = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');
		if (!empty($cek)) {
			$profile = $this->M_global->_LoadProfileLap();
			$nama_usaha = $profile->nama_usaha;
			$motto = '';
			$alamat = '';
			$namaunit = $this->M_global->_namaunit($unit);
			$barang  = $this->input->get('barang');
			$gudang  = $this->input->get('gudang');
			$cabang  = $this->input->get('cabang');
			$tgl1    = $this->input->get('tgl1');
			$tgl2    = $this->input->get('tgl2');
			$query_cab = $this->db->query("SELECT * FROM tbl_namers WHERE koders = '$cabang'")->row();
			$query_gud = $this->db->query("SELECT * FROM tbl_depo WHERE depocode = '$gudang'")->row();
			$query_saldo = "SELECT * from tbl_barangstock where koders = '$cabang' and kodebarang='$barang' and gudang='$gudang' OR gudang = 'LOKAL'";
			$namabarang = data_master('tbl_barang', array('kodebarang' => $barang))->namabarang;
			$_tgl1 = date('Y-m-d', strtotime($tgl1));
			$_tgl2 = date('Y-m-d', strtotime($tgl2));
			$_peri = 'Dari ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2));
			$_peri1 = 'Per Tgl. ' . date('d', strtotime($tgl2)) . ' ' . $this->M_global->_namabulan(date('n', strtotime($tgl2))) . ' ' . date('Y', strtotime($tgl2));
			// {	
			$bulan = date('n', strtotime($tgl1));
			$tahun = date('Y', strtotime($tgl2));
			$query = "SELECT * from tbl_barangstock where koders = '$cabang' and kodebarang='$barang' and gudang='$gudang' OR gudang = 'LOKAL'
			--  AND tglso BETWEEN '$tgl1' AND '$tgl2'
			AND tglso >'$_tgl1' ";
			$lap = $this->db->query($query)->row();
			// var_dump($lap);die;
			$_tanggalawal = $tgl1;
			$saldo = $lap->saldoawal;
			if ($lap) {
				$_tanggalawal = $lap->lasttr;
				$saldo = $lap->saldoakhir;
			} else {
				$_tanggalawal = '';
				$saldo = 0;
			}
			$pdf = new simkeu_rpt();
			$pdf->setID($nama_usaha, $motto, $alamat);
			$pdf->setunit($namaunit);
			$pdf->setjudul('KARTU STOCK');
			$pdf->setsubjudul($_peri1);
			$pdf->addpage("L", "A4");
			$pdf->setsize("L", "A4");
			$pdf->SetWidths(array(30, 5, 100));
			$pdf->SetAligns(array('L', 'C', 'L'));
			$border = array('', '', '');
			$judul = array('Cabang', ':', str_replace("KLINIK ESTETIKA dr. Affandi ", "", $query_cab->namars));
			$pdf->FancyRow($judul, $border);
			$judul = array('Gudang', ':', ($query_gud) ? $query_gud->keterangan : "LOKAL");
			$pdf->FancyRow($judul, $border);
			$judul = array('Kode Barang', ':', $barang);
			$pdf->FancyRow($judul, $border);
			$judul = array('Nama Barang', ':', $namabarang);
			$pdf->FancyRow($judul, $border);
			$pdf->ln();
			$pdf->SetWidths(array(40, 20, 30, 30, 30, 20, 20, 20, 30, 30));
			$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
			$judul = array('No. Bukti', 'Tanggal', 'Keterangan', 'Rekanan', 'Nilai Pembelian', 'Terima', 'Keluar', 'Saldo Akhir', 'Total Nilai Persediaan', 'Nilai Persediaan');
			$pdf->setfont('Times', 'B', 10);
			$pdf->row($judul);
			$pdf->SetWidths(array(40, 20, 30, 30, 30, 20, 20, 20, 30, 30));
			$pdf->SetAligns(array('C', 'C', 'L', 'L', 'R', 'R', 'R', 'R', 'R', 'R'));
			$pdf->setfont('Times', '', 9);
			$pdf->SetFillColor(224, 235, 255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
			$pdf->row(array(
				'SALDO', date('d-m-Y', strtotime($_tanggalawal)), 'SALDO AWAL ' . date('d-m-Y', strtotime($_tanggalawal)), '',
				number_format(0, 0, '.', ','),
				number_format(0, 0, '.', ','),
				number_format(0, 0, '.', ','),
				number_format($saldo, 0, '.', ','),
				number_format(0, 0, '.', ','),
				number_format(0, 0, '.', ',')
			));
			$nourut = 1;
			// $lap = $this->db->query($mutasi)->result();
			$lap = $this->M_KartuStock->tampildata($cabang, $barang, $gudang, $_tgl1, $_tgl2);
			foreach ($lap as $db) {
				// $saldo = $saldo + $db->terima - $db->keluar ;      '
				if ($db->terima > 0) {
					$salakhir = number_format($saldo = $saldo - $db->keluar + $db->terima, 0, '.', ',');
				} else 
				if ($db->keluar > 0) {
					$salakhir = number_format($saldo = $saldo + $db->terima - $db->keluar, 0, '.', ',');
				}

				$nilai = $db->qty * $db->harga;
				$pdf->row(array(
					$db->nomor,
					date('d-m-Y', strtotime($db->tanggal)),
					$db->keterangan,
					$db->rekanan,
					number_format($nilai, 0, '.', ','),
					number_format($db->terima, 0, '.', ','),
					number_format($db->keluar, 0, '.', ','),
					$salakhir,
					number_format(0, 0, '.', ','),
					number_format(0, 0, '.', ',')

				));

				$nourut++;
			}

			$pdf->SetTitle('KARTU STOCK ');
			$pdf->AliasNbPages();
			$pdf->output('FARMASI_KARTUSTOCK.PDF', 'I');
			// }	 		      		  		  
		} else {

			header('location:' . base_url());
		}
	}

	public function cetak2()
	{
		$cek  = $this->session->userdata('level');
		$cabang = $this->session->userdata('unit');
		$avatar = $this->session->userdata('avatar_cabang');
		$barang = $this->input->get('barang');
		$gudang = $this->input->get('gudang');
		$dari = $this->input->get('tgl1');
		$sampai = $this->input->get('tgl2');
		if (!empty($cek)) {
			$kop       = $this->M_cetak->kop($cabang);
			$profile = data_master('tbl_namers', array('koders' => $cabang));
			$datagudang = $this->db->get_where("tbl_depo", ['depocode' => $gudang])->row();
			$databarang = $this->db->get_where("tbl_barang", ['kodebarang' => $barang])->row();
			$datars = $this->db->get_where("tbl_namers", ['koders' => $cabang])->row();
			$namars    = $kop['namars'];
			$alamat    = $kop['alamat'];
			$alamat2   = $kop['alamat2'];
			$alamat3  = $profile->kota;
			$kota      = $kop['kota'];
			$phone     = $kop['phone'];
			$whatsapp  = $kop['whatsapp'];
			$npwp      = $kop['npwp'];
			$chari  = '';
			$chari .= "
					<table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
							<thead>
								<tr>
									<td rowspan=\"6\" align=\"center\">
										<img src=\"" . base_url() . "assets/img_user/$avatar\"  width=\"70\" height=\"70\" />
									</td>
									<td colspan=\"20\">
										<b>
												<tr>
													<td style=\"font-size:10px;border-bottom: none;\"><b><br>$namars</b></td>
												</tr>
												<tr>
													<td style=\"font-size:9px;\">$alamat</td>
												</tr>
												<tr>
													<td style=\"font-size:9px;\">$alamat2</td>
												</tr>
												<tr>
													<td style=\"font-size:9px;\">Wa :$whatsapp    Telp :$phone </td>
												</tr>
												<tr>
													<td style=\"font-size:9px;\">No. NPWP : $npwp</td>
												</tr>
										</b>
									</td>
								</tr>
							</table>";
			$chari .= "
								<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
									<tr>
										<td> &nbsp; </td>
									</tr> 
								</table>";
			$chari .= "
							<table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\">     
								<tr>
									<td style=\"border-top: none;border-right: none;border-left: none;\"></td>
								</tr> 
							</table>";
			$chari .= "
							<table style=\"border-collapse:collapse;font-family: tahoma; font-size:2px\" width=\"100%\" align=\"center\" border=\"1\">     
								<tr>
									<td style=\"border-top: none;border-right: none;border-left: none;\"></td>
								</tr> 
							</table>";
			$chari .= "
							<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
								<tr>
									<td> &nbsp; </td>
								</tr> 
							</table>";
			$chari .= "
							<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
								<tr>
									<td width=\"15%\" style=\"text-align:center; font-size:20px;\"><b>KARTU STOK CABANG $cabang ($datars->kota)</b></td>
								</tr>
								<tr>
									<td width=\"15%\" style=\"text-align:center; font-size:11px;\">Dari tgl " . date("d-m-Y", strtotime($dari)) . " Sampai tgl " . date("d-m-Y", strtotime($sampai)) . "</td>
								</tr>
							</table>";
			$chari .= "
							<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
								<tr>
									<td> &nbsp; </td>
								</tr> 
							</table>";
			$chari .= "
							<table style=\"border-collapse:collapse;font-family: tahoma; font-size:14px\" width=\"100%\" align=\"center\" border=\"0\">
								<tr>
									<td width=\"10%\">Cabang</td>
									<td width=\"1%\"> : </td>
									<td> $cabang ($datars->kota) </td>
								</tr> 
								<tr>
									<td width=\"10%\">Gudang</td>
									<td width=\"1%\"> : </td>
									<td> $datagudang->keterangan </td>
								</tr> 
								<tr>
									<td width=\"10%\">Kode Barang</td>
									<td width=\"1%\"> : </td>
									<td> $databarang->kodebarang </td>
								</tr> 
								<tr>
									<td width=\"10%\">Nama Barang</td>
									<td width=\"1%\"> : </td>
									<td> $databarang->namabarang </td>
								</tr> 
							</table>";
			$chari .= "
							<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
								<tr>
									<td> &nbsp; </td>
								</tr> 
							</table>";
			$chari .= "
							<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
								<tr>
									<td> &nbsp; </td>
								</tr> 
							</table>";
			$chari .= "
							<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">
								<tr>
									<th width=\"5%\">No</th>
									<th width=\"20%\">No. Bukti</th>
									<th width=\"10%\">Tanggal</th>
									<th width=\"10%\">Keterangan</th>
									<th width=\"15%\">Rekanan</th>
									<th width=\"10%\">Nilai Pembelian</th>
									<th width=\"5%\">Terima</th>
									<th width=\"5%\">Keluar</th>
									<th width=\"5%\">Saldo Akhir</th>
									<th width=\"10%\">Nilai Persediaan</th>
									<th width=\"10%\">Total Nilai Persediaan</th>
								</tr> ";
			$date1 = str_replace('-', '/', $dari);
			$tomorrow = date('Y-m-d', strtotime($date1 . "-1 days"));
			$coba       = $this->M_KartuStock->cekstok($cabang, $barang, $gudang, $dari);
			$bs = $this->db->get_where("tbl_barangstock", ["koders"=>$cabang, "kodebarang"=>$barang, "gudang"=>$gudang])->row();
			$periode = $this->db->get_where("tbl_periode", ["koders" => $this->session->userdata("unit")])->row();
			$dateperiode = date("Y-m-d", strtotime($periode->apoperiode));
			$aposesuaix = $this->db->query("SELECT * FROM tbl_aposesuai WHERE koders = '".$this->session->userdata("unit")."' AND tglso = '$dateperiode' ORDER BY id DESC LIMIT 1")->result();
			// foreach($aposesuaix as $ax){
			// 	$aposesuai = $ax->sesuai;
			// 	$aposesuaii = $ax->hasilso;
			// 	$aposesuaiix = $ax->saldo;
			// }
			// if($dateperiode == $dari){
			// 	$_tanggalawal = $periode->apoperiode;
			// 	$saldo = $aposesuaiix;
			// } else {
				if($bs){
					$saldo_awal = $bs->saldoawal;
				} else {
					$saldo_awal = 0;
				}
				if ($coba) {
					$_tanggalawal = $tomorrow;
					$saldo = 0;
					foreach($coba as $c){
						$saldo2 = $saldo_awal + ($saldo += $c->terima - $c->keluar);
						$jam = $c->jam;
					}
				} else {
					$_tanggalawal    = $dari;
					$saldo2           = $saldo_awal;
					$jam          = date("H:i:s");
				}
			// }
			$chari .= "							<tr>
																<td width=\"5%\">#</td>
																<td width=\"20%\">SALDO</td>
																<td width=\"10%\">" . date("d-m-Y", strtotime($_tanggalawal)) . "</td>
																<td width=\"10%\">SALDO AWAL</td>
																<td width=\"15%\">SALDO AWAL</td>
																<td width=\"10%\" style=\"text-align:right;\">-</td>
																<td width=\"5%\" style=\"text-align:right;\">-</td>
																<td width=\"5%\" style=\"text-align:right;\">-</td>
																<td width=\"5%\" style=\"text-align:right;\">" . number_format($saldo2) . "</td>
																<td width=\"10%\" style=\"text-align:right;\">-</td>
																<td width=\"10%\" style=\"text-align:right;\">-</td>
															</tr>
															<tr>
																<td colspan=\"11\">&nbsp;</td>
															</tr>";
			$date1 = str_replace('-', '/', $sampai);
			$now = date('Y-m-d', strtotime($date1 . "+1 days"));
			$queryx = $this->M_KartuStock->tgl($cabang, $barang, $gudang, $dari, $now);
			$no = 1;
			$hgr = 0;
			foreach ($queryx as $db) {
				$rownya = $no++;
				$hgr += $db->harga;
			}
			$no = 1;
			$saldoxx= 0;
			foreach ($queryx as $db) {
				$nilai = $db->qty * $db->harga;
				$saldox = $saldo2 + ($saldoxx += $db->terima - $db->keluar);
				$chari .= "							<tr>
																	<td width=\"5%\">".$no++."</td>
																	<td width=\"20%\">$db->nomor</td>
																	<td width=\"10%\">" . date("d-m-Y", strtotime($db->tanggal)) . "</td>
																	<td width=\"10%\">$db->keterangan</td>
																	<td width=\"15%\">$db->rekanan</td>
																	<td width=\"10%\" style=\"text-align:right;\">" . number_format($nilai) . "</td>
																	<td width=\"5%\" style=\"text-align:right;\">" . number_format($db->terima) . "</td>
																	<td width=\"5%\" style=\"text-align:right;\">" . number_format($db->keluar) . "</td>
																	<td width=\"5%\" style=\"text-align:right;\">" . number_format($saldox) . "</td>
																	<td width=\"10%\" style=\"text-align:right;\">" . number_format($db->hpp) . "</td>
																	<td width=\"10%\" style=\"text-align:right;\">" . number_format($db->totalhpp) . "</td>
																</tr>";
			}
			$chari .= "</table>";
			$data['prev'] = $chari;
			$judul = "KARTU STOK CABANG $datars->kota";
			echo ("<title>$judul</title>");
			$this->M_cetak->mpdf('L', 'A4', $judul, $chari, '.PDF', 10, 10, 10, 2);
		} else {
			header('location:' . base_url());
		}
	}

	public function export()
	{
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$page = $this->uri->segment(3);
			$limit = $this->config->item('limit_data');
			$d['master_bank'] = $this->db->get("ms_bank");
			$d['nama_usaha'] = $this->config->item('nama_perusahaan');
			$d['alamat'] = $this->config->item('alamat_perusahaan');
			$d['motto'] = $this->config->item('motto');
			$this->load->view('master/bank/v_master_bank_exp', $d);
		} else {

			header('location:' . base_url());
		}
	}
}

	/* End of file akuntansi_jurnal.php */
	/* Location: ./application/controllers/akuntansi_jurnal.php */
