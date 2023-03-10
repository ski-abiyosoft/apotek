<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Penjualan_cabang extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->session->set_userdata('menuapp', '4000');
<<<<<<< HEAD
		$this->session->set_userdata('submenuapp', '4400');
=======
		$this->session->set_userdata('submenuapp', '4402');
>>>>>>> development
		$this->load->helper('simkeu_nota1');
		$this->load->helper('simkeu_nota');
		$this->load->model('M_farmasi_po');
		$this->load->model('M_cetak');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');

		if (!empty($cek)) {
			$kondisi = date("Y-m");
			$q1 =
				"SELECT tbl_apoposting.*
				FROM
				   tbl_apoposting inner join tbl_apohresep on
				   tbl_apoposting.resepno=tbl_apohresep.resepno
				WHERE
				   tbl_apoposting.koders = '$unit' and
				   tbl_apohresep.jenisjual=2 and
					 tbl_apohresep.tglresep like '%$kondisi%' and
					 tbl_apoposting.poscredit > 0
				ORDER BY
				   tbl_apoposting.tglresep, tbl_apoposting.resepno desc";
			$bulan  = $this->M_global->_periodebulan();
			$nbulan = $this->M_global->_namabulan($bulan);
			$periode = 'Periode ' . $nbulan . '-' . $this->M_global->_periodetahun();
			$d['keu'] = $this->db->query($q1)->result();
			$level = $this->session->userdata('level');
			$akses = $this->M_global->cek_menu_akses($level, 4400);
			$d['akses'] = $akses;
			$d['periode'] = $periode;
			$this->load->view('penjualan/v_penjualan_faktur_cabang2', $d);
		} else {

			header('location:' . base_url());
		}
	}

	public function filter($param)
	{
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');

		if (!empty($cek)) {
			$data  = explode("~", $param);
			$jns   = $data[0];
			$tgl1  = $data[1];
			$tgl2  = $data[2];
			$_tgl1 = date('Y-m-d', strtotime($tgl1));
			$_tgl2 = date('Y-m-d', strtotime($tgl2));

			if (!empty($jns)) {
				$q1 =
					"select tbl_apoposting.*
				from
				   tbl_apoposting inner join tbl_apohresep on
				   tbl_apoposting.resepno=tbl_apohresep.resepno
				  
				where
				   tbl_apoposting.koders = '$unit' and
				   tbl_apohresep.jenisjual=2 and
				   tbl_apoposting.tglresep between '$_tgl1' and '$_tgl2'
				order by
				   tbl_apoposting.tglresep, tbl_apoposting.resepno desc";



				$periode = 'Periode ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2));
				$d['keu'] = $this->db->query($q1)->result();
				$level = $this->session->userdata('level');
				$akses = $this->M_global->cek_menu_akses($level, 3202);
				$d['akses'] = $akses;
				$d['periode'] = $periode;
				$this->load->view('penjualan/v_penjualan_faktur_cabang2', $d);
			}
		} else {

			header('location:' . base_url());
		}
	}

	public function cetakcab()
	{
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');
		$user = $this->session->userdata('username');
		$nobukti = $this->input->get('nobukti');
		$avatar = $this->session->userdata('avatar_cabang');
		if (!empty($cek)) {
			$profile       = data_master('tbl_namers', array('koders' => $unit));
			$nama_usaha    = $profile->namars;
			$alamat1       = $profile->alamat;
			$alamat3       = $profile->kota;
			$param         = $this->input->get('id');
			$queryh        = "SELECT * from tbl_apohresep where resepno = '$param'";
			$queryd        = "SELECT * from tbl_apodresep where resepno = '$param' AND koders='$unit'";
			$detil_r        = "SELECT * from tbl_apodetresep where resepno = '$param' AND koders='$unit'";
			$queryr        = "SELECT * from tbl_aporacik where resepno = '$param' AND koders='$unit'";
			$queryb        = "SELECT * from tbl_apoposting  where resepno = '$param'";
			$detil         = $this->db->query($queryd)->result();
			$rck         = $this->db->query($detil_r)->result();
			$racikan 	   = $this->db->query($queryr)->row_array();
			$header        = $this->db->query($queryh)->row();
			$posting       = $this->db->query($queryb)->row();
			$penjamin = $this->db->query("SELECT farmasirj AS harga FROM tbl_penjamin WHERE cust_id = '$header->gudang'")->row();
			$hargapenjamin = $penjamin->farmasirj;

			$kop = $this->M_cetak->kop($unit);
			$namars = $kop['namars'];
			$alamat = $kop['alamat'];
			$alamat2 = $kop['alamat2'];
			$kota = $kop['kota'];
			$phone = $kop['phone'];
			$whatsapp = $kop['whatsapp'];
			$npwp = $kop['npwp'];
			$chari = '';
			$chari .= "
                              <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                                   <tr>
                                        <td width=\"50%\">
																					<table>
																						<tr>
																							<td width=\"20%\" style=\"text-align:center;\"><img src=\"" . base_url() . "assets/img_user/$avatar\"  width=\"70\" height=\"70\" /></td>
																							<td width=\"80%\" style=\"text-align:left;\">
																								<table width=\"100%\">
																									<tr>
																										<td style=\"font-size:10px;border-bottom: none;\"><b><br>$namars</b></td>
																									</tr>
																									<tr>
																										<td><b>$alamat</b></td>
																									</tr>
																									<tr>
																										<td><b>$alamat2</b></td>
																									</tr>
																									<tr>
																										<td>Wa :$whatsapp    Telp :$phone </td>
																									</tr>
																									<tr>
																										<td>No. NPWP : $npwp</td>
																									</tr>
																								</table>
																							</td>
																						</tr>
																					</table>
																				</td>
																				<td width=\"50%\" style=\"text-align:left;\">
																					<table>
																						<tr>
																							<td>Tanggal</td>
																							<td> : </td>
																							<td>" . date('d-m-Y') . "</td>
																						</tr>
																						<tr>
																							<td>Nama</td>
																							<td> : </td>
																							<td></td>
																						</tr>
																						<tr>
																							<td>No. Faktur</td>
																							<td> : </td>
																							<td><b>$param</b></td>
																						</tr>
																						<tr>
																							<td>A/R</td>
																							<td> : </td>
																							<td></td>
																						</tr>
																					</table>
																				</td>
                                   </tr> 
                              </table>";
			$chari .= "
                         <table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
                              <tr>
                                   <td width=\"15%\" style=\"text-align:center; font-size:20px;\"><b>FAKTUR PENJUALAN</b></td>
                              </tr>
                         </table>";
			$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                              <tr>
                                   <td> &nbsp; </td>
                              </tr> 
                         </table>";
			$chari .= "
                         <table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
                              <thead>
                                   <tr>
                                        <td width=\"5%\" align=\"center\"><b>NO</b></td>
                                        <td width=\"45%\" align=\"center\"><b>NAMA OBAT</b></td>
                                        <td width=\"10%\" align=\"center\"><b>QTY</b></td>
                                        <td width=\"20%\" align=\"center\"><b>HARGA</b></td>
                                        <td width=\"20%\" align=\"center\"><b>TOTAL RP</b></td>
                                   </tr>
                              </thead>";
			$no = 1;
			$cekdpp = 0;
			$jumlahObat = 0;
			$Totdisc = 0;
			$Totdiscx = 0;
			$ttlharga = 0;
			$td = 0;
			$tpricing = 0;
			$query_ppn = $this->db->query("SELECT * FROM tbl_pajak where kodetax='PPN'")->row();
			$cekppn2 = ($query_ppn->prosentase / 100)  + 5 / 100;
			foreach ($detil as $db1) {
				$cekbarangx = $this->db->get_where("tbl_barang", ["kodebarang" => $db1->kodebarang]);
				if($cekbarangx->num_rows() > 0){
					$cekbarang = $cekbarangx->row();
				} else {
					$cekbarang = $this->db->get_where("tbl_logbarang", ["kodebarang" => $db1->kodebarang])->row();
				}
				$diskon = (int)$db1->qty * (int)$db1->price * (int)$db1->discount / 100;
				$diskonx = $db1->discrp;
				$ttlrp = $db1->qty * $db1->price - $diskonx;
				$cekdpp += 111 / 100;
				$jumlahObat +=  $db1->qty;
				$Totdisc  += $diskon;
				$Totdiscx  += $diskonx;
				$ttlharga +=  $db1->qty * $db1->price;
				$tot      = ($ttlharga - $Totdisc);
				$ppn      = ($ttlharga - $Totdisc) * $cekppn2;
				$td += $ttlrp + $diskonx;
				$chari .= "<tbody><tr>
																<td style=\"text-align:center;\">" . $no++ . "</td>
																<td style=\"text-align:left;\">$db1->namabarang</td>
																<td style=\"text-align:right;\">" . number_format($db1->qty, 0) . "</td>
																<td style=\"text-align:right;\">" . number_format($db1->price, 0) . "</td>
																<td style=\"text-align:right;\">" . number_format($db1->qty * $db1->price - $diskonx, 0) . "</td>
													 </tr></tbody>";
				$testing = $db1->qty * $db1-> price - $diskonx;
				$tpricing += $testing * $cekppn2;
			}
			$chari .= "</table>";
			$tr = 0;
			$totaluangr = 0;
			$ttlhargax = 0;
			$tprice = 0;
			if ($racikan != null && $rck != null) {
				foreach ($rck as $rck) {
					$diskon = $rck->qty * $rck->price * $racikan['diskon'] / 100;
					$ttlrp = $rck->qty * $rck->price;
					$tr += $rck->totalrp;
					$totaluangr += $rck->uangr;
					$chari .= "
														 <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">
																	<tr>
																		<td style=\"text-align:center;\"></td>
																		<td style=\"text-align:left;\">$rck->kodebarang</td>
																		<td style=\"text-align:left;\">('** $rck->namabarang')</td>
																		<td style=\"text-align:right;\">" . number_format($rck->qty, 0) . "</td>
																		<td style=\"text-align:right;\">" . number_format($rck->price, 0) . "</td>
																	</tr> 
														 </table>";
					$ongkos = ($racikan['ongkosracik']);
					$cekdpp += 111 / 100;
					$coba = $rck->price * 5 / 100;
					$tprice += $coba;
					$jumlahObat +=  $rck->qty;
					$Totdisc  += $diskon;
					$ttlhargax +=   $rck->qty * $rck->price;
					$no++;
				}
			}
			$diskonracik = $racikan['diskonrp'];
			$ppnxx = $racikan['ppnrp'];
			$dpp_done = $td;
			$ppn_done = $tpricing;
			$chari .= "
														 <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
																	<tr>
																		<td width=\"80%\" style=\"text-align:right;\"><b>Jumlah Rp</b></td>
																		<td width=\"20%\" style=\"text-align:right;\">" . number_format($ttlharga, 0) . "</td>
																	</tr> 
																	<tr>
																		<td width=\"80%\" style=\"text-align:right;\"><b>Diskon Rp</b></td>
																		<td width=\"20%\" style=\"text-align:right;\">" . number_format($Totdiscx, 0) . "</td>
																	</tr> 
																	<tr>
																		<td width=\"80%\" style=\"text-align:right;\"><b>DPP Rp</b></td>
																		<td width=\"20%\" style=\"text-align:right;\">" . number_format($dpp_done, 0) . "</td>
																	</tr> 
																	<tr>
																		<td width=\"80%\" style=\"text-align:right;\"><b>PPN Rp</b></td>
																		<td width=\"20%\" style=\"text-align:right;\">" . number_format($ppn_done, 0) . "</td>
																	</tr> 
																	<tr>
																		<td width=\"80%\" style=\"text-align:right;\"><b>Total Rp</b></td>
																		<td width=\"20%\" style=\"text-align:right;\">" . number_format($td - $Totdiscx, 0) . "</td>
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
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                              <tr>
                                   <td> &nbsp; </td>
                              </tr> 
                         </table>";
			$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                              <tr>
                                  	<td width=\"80%\" style=\"text-align:right;\"> &nbsp; </td>
																		<td width=\"20%\" style=\"text-align:center;\">Cap & Tanda Tangan</td>
                              </tr> 
                              <tr>
                                  	<td width=\"80%\" style=\"text-align:right;\"> &nbsp; </td>
																		<td width=\"20%\" style=\"text-align:center;\">Petugas</td>
                              </tr> 
                              <tr>
                                  	<td width=\"80%\" style=\"text-align:right;\"> &nbsp; </td>
																		<td width=\"20%\" style=\"text-align:center;\"> &nbsp; </td>
                              </tr> 
                              <tr>
                                  	<td width=\"80%\" style=\"text-align:right;\"> &nbsp; </td>
																		<td width=\"20%\" style=\"text-align:center;\"> &nbsp; </td>
                              </tr> 
                              <tr>
                                  	<td width=\"80%\" style=\"text-align:right;\"> &nbsp; </td>
																		<td width=\"20%\" style=\"text-align:center;\"> &nbsp; </td>
                              </tr> 
                              <tr>
                                  	<td width=\"80%\" style=\"text-align:right;\"> &nbsp; </td>
																		<td width=\"20%\" style=\"text-align:center;\">...........................</td>
                              </tr> 
                              <tr>
																		<td width=\"80%\" style=\"text-align:left;\">Cetak : " . date('d-m-Y') . " jam " . date('H:i:s') . "</td>
                                  	<td width=\"20%\" style=\"text-align:right;\"> &nbsp; </td>
                              </tr> 
                         </table>";
			$data['prev'] = $chari;
			$judul = $param;
			echo ("<title>$judul</title>");
			$this->M_cetak->mpdf('P', 'A4', $judul, $chari, '.PDF', 10, 10, 10, 2);
		} else {
			header('location:' . base_url());
		}
	}

	public function cetakjalan()
	{
		$resepno = $this->input->get('id');
		$stockx = $this->input->get('stock');
		$hargax = $this->input->get('harga');
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');
		$user = $this->session->userdata('username');
		$avatar = $this->session->userdata('avatar_cabang');
		if (!empty($cek)) {
			$kop = $this->M_cetak->kop($unit);
			$namars = $kop['namars'];
			$alamat = $kop['alamat'];
			$alamat2 = $kop['alamat2'];
			$kota = $kop['kota'];
			$phone = $kop['phone'];
			$whatsapp = $kop['whatsapp'];
			$npwp = $kop['npwp'];
			$chari = '';
			$header = $this->db->get_where("tbl_apohresep", ['resepno' => $resepno])->row();
			$rs = $this->db->get_where('tbl_namers', ['koders' => $header->rekmed])->row();
			$detail = $this->db->get_where("tbl_apodresep", ['resepno' => $resepno])->result();
			$chari .= "
                              <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                                   <tr>
                                        <td width=\"30%\" style=\"text-align:center;\"><img src=\"" . base_url() . "assets/img_user/$avatar\"  width=\"100\" height=\"100\" /></td>
																				<td width=\"40%\">&nbsp;</td>
																				<td width=\"30%\" style=\"text-align:left;\">
																					<table>
																						<tr>
																							<td>Tanggal, " . date('d-m-Y') . "</td>
																						</tr>
																						<tr>
																							<td><b><i>Kepada Yth,</i></b></td>
																						</tr>
																					</table>
																				</td>
                                   </tr> 
                              </table>";
			$chari .= "
                              <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                                   <tr>
                                        <td width=\"30%\" style=\"text-align:left;\">
																					<table>
																						<tr>
																							<td>$alamat</td>
																						</tr>
																						<tr>
																							<td>$alamat2</td>
																						</tr>
																						<tr>
																							<td>Wa :$whatsapp    Telp :$phone </td>
																						</tr>
																						<tr>
																							<td>No. NPWP : $npwp</td>
																						</tr>
																					</table>
																				</td>
																				<td width=\"40%\" style=\"text-align:center; font-size:20px;\"><b><u>SURAT JALAN</u></b></td>
																				<td width=\"30%\" style=\"text-align:left;\">
																					<table>
																						<tr>
																							<td>...............................................................</td>
																							</tr>
																							<tr>
																							<td>...............................................................</td>
																							</tr>
																							<tr>
																							<td>...............................................................</td>
																						</tr>
																					</table>
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
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:16px\" width=\"100%\" align=\"center\" border=\"0\">
                              <tr>
                                   <td width=\"5%\"> NO. </td>
                                   <td width=\"2%\"> : </td>
                                   <td> $resepno </td>
                              </tr> 
                         </table>";
			$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"0\">
                              <tr>
                                   <td>Kami kirim barang - barang tersebut dibawah</td>
                              </tr> 
                         </table>";
			$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                              <tr>
                                   <td> &nbsp; </td>
                              </tr> 
                         </table>";
			if ($stockx == 1 && $hargax == 1) {
				$chari .= "
                         <table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
                              <thead>
                                   <tr>
                                        <td width=\"5%\" align=\"center\"><b>NO</b></td>
                                        <td width=\"15%\" align=\"center\"><b>NAMA & JENIS BARANG</b></td>
                                        <td width=\"10%\" align=\"center\"><b>KEMASAN</b></td>
                                        <td width=\"10%\" align=\"center\"><b>BANYAKNYA</b></td>
                                        <td width=\"15%\" align=\"center\"><b>KETERANGAN</b></td>
                                   </tr>
                              </thead>";
			} else if ($stockx == 2 && $hargax == 1) {
				$chari .= "
																							<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
																									 <thead>
																												<tr>
																														 <td width=\"5%\" align=\"center\"><b>NO</b></td>
																														 <td width=\"15%\" align=\"center\"><b>NAMA & JENIS BARANG</b></td>
																														 <td width=\"10%\" align=\"center\"><b>KEMASAN</b></td>
																														 <td width=\"15%\" align=\"center\"><b>KETERANGAN</b></td>
																												</tr>
																									 </thead>";
			} else if ($stockx == 1 && $hargax == 2) {
				$chari .= "
																							<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
																									 <thead>
																												<tr>
																														 <td width=\"5%\" align=\"center\"><b>NO</b></td>
																														 <td width=\"15%\" align=\"center\"><b>NAMA & JENIS BARANG</b></td>
																														 <td width=\"10%\" align=\"center\"><b>KEMASAN</b></td>
																														<td width=\"10%\" align=\"center\"><b>BANYAKNYA</b></td>
																												</tr>
																									 </thead>";
			} else if ($stockx == 2 && $hargax == 2) {
				$chari .= "
																							<table style=\"border-collapse:collapse;font-family: Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
																									 <thead>
																												<tr>
																														 <td width=\"5%\" align=\"center\"><b>NO</b></td>
																														 <td width=\"15%\" align=\"center\"><b>NAMA & JENIS BARANG</b></td>
																														 <td width=\"10%\" align=\"center\"><b>KEMASAN</b></td>
																												</tr>
																									 </thead>";
			}
			$no = 1;
			foreach ($detail as $d) {
				if ($stockx == 1 && $hargax == 1) {
					$chari .= "<tbody><tr>
																													<td style=\"text-align:center;\">" . $no++ . "</td>
																													<td style=\"text-align:left;\">$d->namabarang [ $d->kodebarang ]</td>
																													<td style=\"text-align:left;\">$d->satuan</td>
																													<td style=\"text-align:right;\">" . number_format($d->qty) . "</td>
																													<td style=\"text-align:right;\">" . number_format($d->price) . "</td>
																										 </tr></tbody>";
				} else if ($stockx == 1 && $hargax == 2) {
					$chari .= "<tbody><tr>
																													<td style=\"text-align:center;\">" . $no++ . "</td>
																													<td style=\"text-align:left;\">$d->namabarang [ $d->kodebarang ]</td>
																													<td style=\"text-align:left;\">$d->satuan</td>
																													<td style=\"text-align:right;\">" . number_format($d->qty) . "</td>
																										 </tr></tbody>";
				} else if ($stockx == 2 && $hargax == 2) {
					$chari .= "<tbody><tr>
																													<td style=\"text-align:center;\">" . $no++ . "</td>
																													<td style=\"text-align:left;\">$d->namabarang [ $d->kodebarang ]</td>
																													<td style=\"text-align:left;\">$d->satuan</td>
																										 </tr></tbody>";
				} else if ($stockx == 2 && $hargax == 1) {
					$chari .= "<tbody><tr>
																													<td style=\"text-align:center;\">" . $no++ . "</td>
																													<td style=\"text-align:left;\">$d->namabarang [ $d->kodebarang ]</td>
																													<td style=\"text-align:left;\">$d->satuan</td>
																													<td style=\"text-align:right;\">" . number_format($d->price) . "</td>
																										 </tr></tbody>";
				}
			}
			$chari .= "</table>";
			$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\">
                              <tr>
                                   <td> &nbsp; </td>
                              </tr> 
                         </table>";
			$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">
                              <tr>
                                   <td style=\"text-align:center; border-right: none; border-bottom: none;\">Tanda Terima,</td>
                                   <td style=\"text-align:center; border-right: none; border-bottom: none; border-left: none;\">&nbsp;</td>
                                   <td style=\"text-align:center; border-left: none; border-bottom: none;\">Hormat Kami,</td>
                              </tr> 
                              <tr>
                                   <td style=\"text-align:center; border-right: none; border-top: none; border-bottom: none;\">&nbsp;</td>
                                   <td width=\"30%\" style=\"text-align:center; border-right: none; border-top: none; border-bottom: none; border-left: none;\">";
			$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:11px;\" width=\"100%\" align=\"center\" border=\"1\">
                              <tr>
                                   <td style=\"text-align:center; border-bottom: none;\"><b>Catatan</b></td>
                              </tr> 
                              <tr>
                                   <td style=\"text-align:center; border-top: none;\">Barang diterima dengan kondisi baik</td>
                              </tr> 
                         </table>";
			$chari .= "</td>
                                   <td style=\"text-align:center; border-left: none; border-top: none; border-bottom: none;\">&nbsp;</td>
                              </tr> 
                         </table>";
			$chari .= "
                         <table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\">
                              <tr>
                                   <td style=\"text-align:center; border-top: none; border-right: none;\">(..........................................)</td>
                                   <td width=\"30%\" style=\"text-align:center; border-top: none; border-right: none;  border-left: none;\">&nbsp;</td>
                                   <td style=\"text-align:center; border-top: none;  border-left: none;\">(..........................................)</td>
                              </tr> 
                         </table>";
			$data['prev'] = $chari;
			$judul = $resepno;
			echo ("<title>$judul</title>");
			$this->M_cetak->mpdf('P', 'A4', $judul, $chari, '.PDF', 10, 10, 10, 2);
		} else {
			header('location:' . base_url());
		}
	}

	public function cetak($param)
	{
		$cek = $this->session->userdata('level');
		$unit = $this->session->userdata('unit');
		if (!empty($cek)) {
			$profile = $this->M_global->_LoadProfileLap();
			$unit = $this->session->userdata('unit');
			$nama_usaha = $profile->nama_usaha;
			$alamat1  = $profile->alamat1;
			$alamat2  = $profile->alamat2;

			$queryh = "select * from ar_sifile inner join ar_customer on ar_sifile.kodecust=ar_customer.kode where kodesi = '$param'";
			$queryd = "select ar_sidetail.*, inv_barang.namabarang from ar_sidetail inner join inv_barang on ar_sidetail.kodeitem=inv_barang.kodeitem where ar_sidetail.kodesi = '$param'";
			$queryb = "select sum(jumlah) as total from ar_sibiaya  where ar_sibiaya.kodesi = '$param'";

			$detil  = $this->d->query($queryd)->result();
			$header = $this->db->query($queryh)->row();
			$biaya  = $this->db->query($queryb)->row();
			$pdf = new simkeu_nota();
			$pdf->setID($nama_usaha, $alamat1, $alamat2);
			$pdf->setjudul('');
			$pdf->setsubjudul('');
			$pdf->addpage("P", "A4");
			$pdf->setsize("P", "A4");
			$pdf->SetWidths(array(70, 30, 90));
			$border = array('T', '', 'BT');
			$size   = array('', '', '');
			$pdf->setfont('Arial', 'B', 18);
			$pdf->SetAligns(array('C', 'C', 'C'));
			$align = array('L', 'C', 'L');
			$style = array('', '', 'B');
			$size  = array('12', '', '18');
			$max   = array(5, 5, 20);
			$judul = array('Kepada :', '', 'Faktur Penjualan');
			$fc     = array('0', '0', '0');
			$hc     = array('20', '20', '20');
			$pdf->FancyRow2(10, $judul, $fc,  $border, $align, $style, $size, $max);
			$pdf->ln(1);
			$pdf->setfont('Arial', 'B', 10);
			$pdf->SetWidths(array(70, 30, 30, 5, 55));
			$border = array('', '', '', '', '');
			$fc     = array('0', '0', '0', '0', '0');
			$pdf->SetFillColor(230, 230, 230);
			$pdf->setfont('Arial', '', 9);
			$pdf->FancyRow(array($header->nama, '', 'Nomor', ':', $header->kodesi), $fc, $border);
			$pdf->FancyRow(array($header->alamat1, '', 'Tanggal', ':', date('d M Y', strtotime($header->tglsi))), $fc, $border);
			$pdf->FancyRow(array($header->alamat2, '', 'Tgl. Kirim', ':', date('d M Y', strtotime($header->tglkirim))), $fc, $border);
			$pdf->FancyRow(array($header->telp, '', 'Jatuh Tempo', ':', date('d M Y', strtotime($header->tgljthtempo))), $fc, $border);

			$pdf->ln(2);

			$pdf->SetWidths(array(30, 60, 20, 25, 25, 30));
			$border = array('TB', 'TB', 'TB', 'TB', 'TB', 'TB');
			$align  = array('L', 'L', 'R', 'R', 'R', 'R');
			$pdf->setfont('Arial', 'B', 10);
			$pdf->SetAligns(array('L', 'C', 'R'));
			//$pdf->SetFillColor(0,0,139);
			//$pdf->settextcolor(255,255,255);
			$fc = array('0', '0', '0', '0', '0', '0');
			$judul = array('Kode Barang', 'Nama Barang', 'Qty', 'Harga', 'Diskon', 'Total Harga');
			$pdf->FancyRow2(8, $judul, $fc, $border, $align);
			$border = array('', '', '');
			$pdf->setfont('Arial', '', 10);
			$tot = 0;
			$subtot = 0;
			$tdisc  = 0;
			$border = array('', '', '', '', '', '');
			$align  = array('L', 'L', 'R', 'R', 'R', 'R');
			$fc = array('0', '0', '0', '0', '0', '0');
			$pdf->SetFillColor(0, 0, 139);
			$pdf->settextcolor(0);
			foreach ($detil as $db) {
				$dpp = $db->qtysi * $db->hargajual;
				$dis = ($db->disc / 100) * $dpp;
				$jum = $dpp - $dis;
				$tot = $tot + $jum;

				$subtot = $subtot + $dpp;
				$tdisc  = $tdisc + $dis;

				$pdf->FancyRow(array(
					$db->kodeitem,
					$db->namabarang,
					$db->qtysi,
					number_format($db->hargajual, 0, '.', ','),
					$db->disc,
					number_format($jum, 0, '.', ',')
				), $fc, $border, $align);
			}


			if ($header->sppn == "Y") {
				$ppn = $subtot * 0.1;
			} else {
				$ppn = 0;
			}
			$biayalain = $biaya->total;
			$tot = $tot + $ppn + $biayalain;
			$pdf->SetFillColor(230);
			$border = array('B', 'B', 'B', 'B', 'B', 'B');
			$pdf->FancyRow(array('', '', '', '', '', ''), 0, $border);
			$pdf->ln(2);
			$pdf->SetWidths(array(100, 20, 30, 40));
			$border = array('TB', '', 'T', 'T');
			$align  = array('L', '', 'L', 'R');
			//$pdf->SetFillColor(230,230,230);
			//$pdf->settextcolor(0);
			$fc = array('0', '0', '0', '0');
			$pdf->FancyRow(array('Keterangan', '', 'Sub Total', number_format($subtot, 0, '.', ',')), $fc, $border, $align, 0);
			$border = array('', '', '', '');
			$pdf->FancyRow(array($header->ket, '', 'Diskon', number_format($tdisc, 0, '.', ',')), $fc, $border, $align);
			$pdf->FancyRow(array('', '', 'PPN (10%)', number_format($ppn, 0, '.', ',')), $fc, $border, $align);
			$pdf->FancyRow(array('', '', 'Biaya Lain-lain', number_format($biayalain, 0, '.', ',')), $fc, $border, $align);
			$style = array('', '', 'B', 'B');
			$size  = array('', '', '', '');
			$border = array('T', '', 'BT', 'BT');
			//$pdf->SetFillColor(0,0,139);
			//$pdf->settextcolor(255,255,255);
			$fc = array('0', '0', '0', '0');
			$pdf->FancyRow(array('', '', 'Total', number_format($tot, 0, '.', ',')), $fc, $border, $align, $style, $size);
			$pdf->settextcolor(0);
			$pdf->SetWidths(array(50, 50));
			$pdf->SetFont('Arial', '', 9);
			$pdf->SetAligns(array('C', 'C'));
			$pdf->ln(5);

			$border = array('', '');
			$align  = array('C', 'C');
			$pdf->FancyRow(array('Disiapkan Oleh', 'Disetujui Oleh'), 0, $border, $align);
			$pdf->ln(1);
			$pdf->ln(15);
			$pdf->SetWidths(array(49, 2, 49));
			$pdf->SetFont('Arial', '', 8);
			$pdf->SetAligns(array('C', 'C', 'C'));
			$border = array('B', '', 'B');
			$pdf->FancyRow(array('', '', ''), 0, $border, $align);
			$border = array('', '', '');
			$align  = array('L', 'C', 'L');
			$pdf->FancyRow(array('Tgl.', '', 'Tgl.'), 0, $border, $align);



			$pdf->AliasNbPages();
			$pdf->output($param . '.PDF', 'I');
		} else {

			header('location:' . base_url());
		}
	}

	public function entri()
	{
		$cek = $this->session->userdata('level');
		$uid = $this->session->userdata('unit');
		$cbg = $this->input->get('cabang');
		// if (!empty($cek)) {
			$page              = $this->uri->segment(3);
			$limit             = $this->config->item('limit_data');
			$d['nomor']        = urut_transaksi('URUT_JUALCABANG', 19);
			if ($cbg != '') {
				$d['baranghpo']    = $this->db->query("select * from tbl_baranghpo where koders = '$cbg' and internalpo = 1 and internalproses = 0")->result();
				$d['apohpolog']    = $this->db->query("select * from tbl_apohpolog where koders = '$cbg' and internalpo = 1 and internalproses = 0")->result();
				$d['cbg'] = $cbg;
			} else {
				$d['cbg'] = '';
				$d['baranghpo']    = $this->db->query("select * from tbl_baranghpo where internalpo = 1 and internalproses = 0")->result();
				$d['apohpolog']    = $this->db->query("select * from tbl_apohpolog where internalpo = 1 and internalproses = 0")->result();
			}
			$d['namers']       = $this->db->get('tbl_namers')->result();
			$ppxx = $this->db->get_where('tbl_pajak', ['kodetax' => 'PPN'])->row();
			$ppx = $ppxx->prosentase / 100;
			$d['ppn'] = $ppx;
			$this->load->view('penjualan/v_penjualan_faktur_add_cabang', $d);
		// } else {
		// 	header('location:' . base_url());
		// }
	}

	public function getpo($po)
	{
		$cabang = $this->session->userdata("unit");
		$data = $this->db->query("SELECT a.*, (SELECT gudang FROM tbl_baranghpo WHERE po_no = a.po_no) AS gudang, (SELECT keterangan FROM tbl_depo WHERE depocode = (SELECT gudang FROM tbl_baranghpo WHERE po_no = a.po_no)) AS namagudang, b.namabarang, b.satuan1, IFNULL(((SELECT saldoakhir from tbl_barangstock where kodebarang = a.kodebarang and koders = '$cabang' and gudang = c.gudang)),0) as saldo FROM tbl_barangdpo a JOIN tbl_baranghpo c ON c.po_no=a.po_no LEFT JOIN tbl_barang b ON b.kodebarang=a.kodebarang WHERE a.po_no = '$po'")->result();
		echo json_encode($data);
	}

	public function getpo_l($po)
	{
		$cabang = $this->session->userdata("unit");
		$data = $this->db->query("SELECT a.*, (SELECT gudang FROM tbl_apohpolog WHERE po_no = a.po_no) AS gudang, b.namabarang, b.satuan1, (SELECT keterangan FROM tbl_depo WHERE depocode = (SELECT gudang FROM tbl_apohpolog WHERE po_no = a.po_no)) AS namagudang, IFNULL(((SELECT saldoakhir from tbl_apostocklog where kodebarang = a.kodebarang and koders = '$cabang' and gudang = c.gudang)),0) as saldo FROM tbl_apodpolog a JOIN tbl_apohpolog c ON c.po_no=a.po_no LEFT JOIN tbl_logbarang b ON b.kodebarang=a.kodebarang WHERE a.po_no = '$po'")->result();
		echo json_encode($data);
	}

	public function hapus($nomor)
	{
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$unit			= $this->session->userdata("unit");
			$hmutasi 	= $this->db->query("select * from tbl_apohresep where resepno = '$nomor'")->row();
			$cabang  	= $hmutasi->koders;
			$gudang  	= $hmutasi->gudang;
			$data_bhterima 	= $this->db->get_where("tbl_baranghpo", ['po_no' => $hmutasi->pono]);
			if ($data_bhterima->num_rows() > 0) {
				$this->db->set("internalproses", 0);
				$this->db->set("cek", 0);
				$this->db->where("po_no", $hmutasi->pono);
				$this->db->update("tbl_baranghpo");
			} else {
				$this->db->set("internalproses", 0);
				$this->db->set("cek", 0);
				$this->db->where("po_no", $hmutasi->pono);
				$this->db->update("tbl_apohpolog");
			}
			$datamutasi 		= $this->db->get_where('tbl_apodresep', ['resepno' => $nomor])->result();
			foreach ($datamutasi as $row) {
				$_qty = $row->qty;
				$_kode = $row->kodebarang;
				$cek = $this->db->get_where("tbl_barangstock", ["kodebarang" => $_kode, "gudang" => $gudang, "koders" => $unit]);
				if ($cek->num_rows() > 0) {
					$this->db->query("UPDATE tbl_barangstock set keluar=keluar- $_qty, saldoakhir= saldoakhir+ $_qty where kodebarang = '$_kode' and koders = '$unit' and gudang = '$gudang'");
				} else {
					$this->db->query("UPDATE tbl_apostocklog set keluar=keluar- $_qty, saldoakhir= saldoakhir+ $_qty where kodebarang = '$_kode' and koders = '$unit' and gudang = '$gudang'");
				}
			}
			$this->db->delete('tbl_apodresep', array('resepno' => $nomor));
			$this->db->delete('tbl_apohresep', array('resepno' => $nomor));
			$this->db->delete('tbl_apoposting', array('resepno' => $nomor));
			$this->db->delete('tbl_pap', array('noreg' => $nomor));
			echo json_encode(array("status" => 1, "nomor" => $nomor));
		} else {
			header('location:' . base_url());
		}
	}


	public function getinfobarang()
	{
		$kode = $this->input->get('kode');
		$data = $this->M_global->_data_barang($kode);
		echo json_encode($data);
	}

	function search_cust()
	{
		$q = $this->input->post('searchTerm');
		$query = $this->db->query("select cust_id as id, concat(cust_nama) as text from tbl_penjamin  order by cust_nama")->result();
		echo json_encode($query);
	}

	public function ceksaldo()
	{
		$gudang = $this->input->get("gudang");
		$kodebarang = $this->input->get("kodebarang");
		$cabang = $this->session->userdata("unit");
		$data = $this->db->get_where("tbl_barangstock", ["kodebarang" => $kodebarang, "gudang" => $gudang, "koders" => $cabang]);
		if ($data->num_rows() < 1 || empty($data->row()) || $data->row() == null || $data->row() == '') {
			$datax = $this->db->get_where("tbl_apostocklog", ["kodebarang" => $kodebarang, "gudang" => $gudang, "koders" => $cabang]);
		} else {
			$datax = $data;
		}
		echo json_encode($datax->row());
	}

	public function save_x()
	{
		$cek = $this->session->userdata('level');
		// if (!empty($cek)) {
			$userid        = $this->session->userdata('username');
			$unit          = $this->session->userdata('unit');
			$cabang        = $unit;
			$gudang        = $this->input->post('gudang');
			$po_no         = $this->input->post('po_cabang');
			$tgl           = $this->input->post('tanggal');
			$jam           = $this->input->post('jam');
			$cust_id       = $this->input->post('cust_id');
			$diskon       = $this->input->get('diskon');
			$ppn       = $this->input->get('ppn');
			$fakturpajak   = urut_faktur_pajak("URUT_FAKTUR_PAJAK", 4);
			$noprimary  = urut_transaksi('URUT_JUALCABANG', 19);
			$datax 		= [
				'internalproses' 		=> 1,
				'cek' 							=> 1
			];
			$where_po = ['po_no' 	=> $po_no];
			$cek_baranghpo 	= $this->db->get_where('tbl_baranghpo', ['po_no' => $po_no]);
			if ($cek_baranghpo->num_rows() > 0) {
				$this->db->update('tbl_baranghpo', $datax, $where_po);
				$tujuan = $cek_baranghpo->row();
			} else {
				$cek_apohpolog 	= $this->db->get_where('tbl_apohpolog', ['po_no' => $po_no]);
				if ($cek_apohpolog->num_rows() > 0) {
					$this->db->update('tbl_apohpolog', $datax, $where_po);
					$tujuan = $cek_apohpolog->row();
				}
			}
			$kode  		= $this->input->post('kode');
			$qty   		= $this->input->post('qty');
			$sat   		= $this->input->post('sat');
			$harga 		= $this->input->post('harga');
			$ppn   		= $this->input->post('ppn');
			$disc  		= $this->input->post('disc');
			$discrp  	= $this->input->post('discrp');
			$jumlah 	= $this->input->post('jumlah');
			$exp   		= $this->input->post('tanggal');
			// $exp   = date("Y-m-d");
			$aturan   = $this->input->post('aturan');
			$norak   	= $this->input->post('norak');
			$jumdata  = count($kode);
			$tot 			= 0;
			$tdisc 		= 0;
			$tothpp 	= 0;
			for ($i = 0; $i <= $jumdata - 1; $i++) {
				$_kode        = $kode[$i];
				$_qty         = str_replace(',', '', $qty[$i]);
				$_exp         = $exp[$i];
				$_sat         = $sat[$i];
				$_aturan      = $aturan[$i];
				$_norak       = $norak[$i];
				$tot          = $tot + str_replace(',', '', $jumlah[$i]);
				$_jumlah      = str_replace(',', '', $jumlah[$i]);
				$_harga       = str_replace(',', '', $harga[$i]);
				$_discrp       = str_replace(',', '', $discrp[$i]);
				$vjum         = $_qty * $_harga;
				$vdisc        = $vjum * ($disc[$i] / 100);
				$tdisc        = $tdisc + $vdisc;
				$hpp          = $this->M_global->_data_barang($_kode)->hpp;
				$namabarang   = $this->M_global->_data_barang($_kode)->namabarang;
				$tothpp       = $tothpp + ($hpp * $_qty);
				$pajak        = $this->db->get_where("tbl_pajak", ['kodetax' => 'PPN'])->row()->prosentase / 100;
				$_ppn 	= 1;
				$_ppnrp = $vjum * $pajak;
				$jual = [
					'koders'    	=> $cabang,
					'resepno'   	=> $noprimary,
					'kodebarang' 	=> $_kode,
					'namabarang' 	=> $namabarang,
					'qty' 				=> $_qty,
					'satuan' 			=> $_sat,
					'ppn' 				=> $_ppn,
					'ppnrp' 			=> $_ppnrp,
					'hpp' 				=> $hpp,
					'price' 			=> $_harga,
					'discount' 		=> $disc[$i],
					'discrp' 			=> $_discrp,
					'totalrp' 		=> $_jumlah,
					'exp_date' 		=> $_exp,
					'atpakai' 		=> $_aturan,
					'rakno'  			=> $_norak,
				];
				$this->db->insert('tbl_apodresep', $jual);
				if ($cek_baranghpo->num_rows() > 0){
					$farmasi = $cek_baranghpo->row();
					$barangstok = $this->db->query("SELECT * FROM tbl_barangstock WHERE koders = '$cabang' AND gudang = '$farmasi->gudang' AND kodebarang = '$_kode'");
					if($barangstok->num_rows() > 0){
						$this->db->query("UPDATE tbl_barangstock SET keluar = keluar+$_qty, saldoakhir = saldoakhir-$_qty WHERE kodebarang = '$_kode' AND koders = '$cabang'  AND gudang = '$farmasi->gudang'");
					} else {
						$datastock = array(
							'koders'       => $cabang,
							'kodebarang'   => $_kode,
							'gudang'       => $farmasi->gudang,
							'saldoawal'    => 0,
							'keluar'       => $_qty,
							'saldoakhir'   => 0-(int)$_qty,
							'tglso'        => $this->input->post('tanggal'),
							'lasttr'       => $this->input->post('tanggal'),
						);
						$this->db->insert('tbl_barangstock', $datastock);
					}
				} else {
					$logistik 	= $this->db->get_where('tbl_apohpolog', ['po_no' => $po_no])->row();
					$log_stok = $this->db->query("SELECT * FROM tbl_apostocklog WHERE koders = '$cabang' AND gudang = '$logistik->gudang' AND kodebarang = '$_kode'");
					if($log_stok->num_rows() > 0){
						$this->db->query("UPDATE tbl_apostocklog SET keluar = keluar+$_qty, saldoakhir = saldoakhir-$_qty WHERE kodebarang = '$_kode' AND koders = '$cabang'  AND gudang = '$logistik->gudang'");
					} else {
						$datastock = array(
							'koders'       => $cabang,
							'kodebarang'   => $_kode,
							'gudang'       => $logistik->gudang,
							'saldoawal'    => 0,
							'keluar'       => $_qty,
							'saldoakhir'   => 0-(int)$_qty,
							'tglso'        => $this->input->post('tanggal'),
						);
						$this->db->insert('tbl_apostocklog', $datastock);
					}
				}
			}
			$pajak = 1;
			$data = [
				'koders' 				=> $cabang,
				'resepno' 			=> $noprimary,
				'eresepno'			=> '',
				'antrino'  			=> 1,
				'cust_id'  			=> $cust_id,
				'jenisjual'  		=> 2,
				'kodepel'  			=> $this->input->post('pembeli'),
				'rekmed'  			=> $this->input->post('cabang'),
				'pro'  					=> $this->input->post('namapasien'),
				'tglresep'   		=> date('Y-m-d', strtotime($this->input->post('tanggal'))),
				'jam' 					=> date('H:i:s', strtotime($this->input->post('jam'))),
				'gudang'  			=> $this->input->post('gudang'),
				'pajak'  				=> $pajak,
				'fakturpajak'  	=> $fakturpajak,
				'username' 			=> $userid,
				'pono' 					=> $this->input->post('po_cabang'),
			];

			$this->db->insert('tbl_apohresep', $data);

			//posting

			$data = [
				'koders' 		=> $cabang,
				'resepno' 	=> $noprimary,
				'tglresep'  => date('Y-m-d', strtotime($this->input->post('tanggal'))),
				'rekmed'  	=> $tujuan->koders,
				'namapas' 	=> $this->input->post('namapasien'),
				'gudang'  	=> $this->input->post('gudang'),
				'posting' 	=> 1,
				'poscredit' => $this->input->get('totalnet'),
				'username' 	=> $userid,
				'diskonrp' 	=> $diskon,
				'kodepel'		=> $this->input->post('pembeli'),
			];
			$data_pap = [
				'noreg' 				=> $noprimary,
				'koders' 				=> $unit,
				'rekmed' 				=> $tujuan->koders,
				'tglposting' 		=> date('Y-m-d', strtotime($this->input->post('tanggal'))),
				'cust_id' 			=> $this->input->post('cust_id'),
				'jumlahhutang' 	=> $this->input->get('totalnet'),
				'asal' 					=> 'POLI',
				'namapas' 			=> $this->input->post('namapasien'),
				'username' 			=> $userid,
			];

			$this->db->insert('tbl_apoposting', $data);
			$this->db->insert('tbl_pap', $data_pap);
			echo json_encode($noprimary);
		// } else {
		// 	header('location:' . base_url());
		// }
	}

	public function save($param)
	{
		$hasil = 0;
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$userid   = $this->session->userdata('username');
			$unit     = $this->session->userdata('unit');
			$cabang   = $this->session->userdata('unit');
			$gudang   = $this->session->userdata('gudang');

			if ($param == 1) {
				$datax = [
					'internalproses' => 1,
					'cek' => 1
				];
				$where_po = [
					'po_no' => $this->input->post('po_cabang')
				];
				$cek_baranghpo = $this->db->get_where('tbl_baranghpo', ['po_no' => $this->input->post('po_cabang')]);
				if ($cek_baranghpo->num_rows() > 0) {
					$this->db->update('tbl_baranghpo', $datax, $where_po);
				} else {
					$cek_apohpolog = $this->db->get_where('tbl_apohpolog', ['po_no' => $this->input->post('po_cabang')]);
					if ($cek_apohpolog->num_rows() > 0) {
						$this->db->update('tbl_apohpolog', $datax, $where_po);
					}
				}
				$nobukti  = urut_transaksi('URUT_JUALCABANG', 19);
			} else {
				$nobukti  = $this->input->post('noresep');

				$datamutasi = $this->db->get_where('tbl_apodresep', array('resepno' => $nobukti))->result();
				foreach ($datamutasi as $row) {
					$_qty = $row->qty;
					$_kode = $row->kodebarang;

					$this->db->query("update tbl_barangstock set keluar=keluar- $_qty, saldoakhir= saldoakhir+ $_qty where kodebarang = '$_kode' and koders = '$cabang' and gudang = '$gudang'");
				}

				$this->db->delete('tbl_apodresep', array('resepno' => $nobukti));
			}

			$kode  = $this->input->post('kode');
			$qty   = $this->input->post('qty');
			$sat   = $this->input->post('sat');
			$harga = $this->input->post('harga');
			$ppn   = $this->input->post('ppn');
			$disc  = $this->input->post('disc');
			$jumlah = $this->input->post('jumlah');

			$exp   = $this->input->post('expire');
			$aturan   = $this->input->post('aturan');
			$norak   = $this->input->post('norak');

			$jumdata  = count($kode);


			$nourut = 1;
			$tot = 0;
			$tdisc = 0;

			$tothpp = 0;
			for ($i = 0; $i <= $jumdata - 1; $i++) {
				$_kode   = $kode[$i];
				$_qty    = $qty[$i];

				$_exp = $exp[$i];
				$_aturan = $aturan[$i];
				$_norak   = $norak[$i];

				$tot = $tot + str_replace(',', '', $jumlah[$i]);
				$_jumlah = str_replace(',', '', $jumlah[$i]);
				$_harga  = str_replace(',', '', $harga[$i]);

				$vjum  = $qty[$i] * $_harga;
				$vdisc = $vjum * ($disc[$i] / 100);
				$tdisc = $tdisc + $vdisc;

				$hpp   = $this->M_global->_data_barang($_kode)->hpp;
				$namabarang = $this->M_global->_data_barang($_kode)->namabarang;
				$tothpp = $tothpp + ($hpp * $qty[$i]);

				if ($ppn[$i]) {
					$_ppn = 1;
					$_ppnrp = $vjum * 0.1;
				} else {
					$_ppn = 0;
					$_ppnrp = 0;
				}

				$datad = array(
					'koders'    => $unit,
					'resepno'   => $nobukti,
					'kodebarang' => $_kode,
					'namabarang' => $namabarang,
					'qty' => $qty[$i],
					'satuan' => $sat[$i],
					'ppn' => $_ppn,
					'ppnrp' => $_ppnrp,
					'hpp' => $hpp,
					'price' => $_harga,
					'discount' => $disc[$i],
					'totalrp' => $_jumlah,
					'exp_date' => $_exp,
					'atpakai' => $_aturan,
					'rakno'  => $_norak,
				);


				if ($_kode != "") {
					$this->db->insert('tbl_apodresep', $datad);

					$this->db->query(
						"update tbl_barangstock set keluar=keluar+ $_qty, saldoakhir= saldoakhir- $_qty where kodebarang = '$_kode'
				    and koders = '$cabang' and gudang = '$gudang'"
					);
				}
			}


			if ($this->input->post('vat')) {
				$pajak = 1;
			} else {
				$pajak = 0;
			}
			$data = array(
				'koders' => $this->session->userdata('unit'),
				'resepno' => $nobukti,
				'antrino'  => 1,
				'cust_id'  => $this->input->post('cust_id'),
				'jenisjual'  => 2,
				'kodepel'  => $this->input->post('pembeli'),
				'rekmed'  => $this->input->post('cabang'),
				'pro'  => $this->input->post('namapasien'),
				'tglresep'   => date('Y-m-d', strtotime($this->input->post('tanggal'))),
				'jam' => date('H:i:s', strtotime($this->input->post('jam'))),
				'gudang'  => $this->input->post('gudang'),
				'pajak'  => $pajak,
				'fakturpajak'  => $this->input->post('fakturpajak'),
				'username' => $userid,
				'pono' => $this->input->post('po_cabang'),

			);

			if ($param == 1) {
				$this->db->insert('tbl_apohresep', $data);
			} else {
				$data['tgledit'] = date('Y-m-d');
				$data['jamedit'] = date('H:i:s');
				$data['useredit'] = $userid;
				$this->db->update('tbl_apohresep', $data, array('resepno' => $nobukti));
			}

			//posting

			$data = array(
				'koders' => $this->session->userdata('unit'),
				'resepno' => $nobukti,
				'tglresep'   => date('Y-m-d', strtotime($this->input->post('tanggal'))),
				'rekmed'  => '',
				'namapas' => $this->input->post('namapasien'),
				'gudang'  => $this->input->post('gudang'),
				'posting' => 1,
				'poscredit' => $tot,
				'username' => $userid,

			);

			$data_pap = [
				'noreg' => $nobukti,
				'koders' => $unit,
				'rekmed' => '',
				'tglposting' => date('Y-m-d', strtotime($this->input->post('tanggal'))),
				'cust_id' => $this->input->post('cust_id'),
				'jumlahhutang' => $this->input->get('totalnet'),
				'asal' => 'POLI',
				'namapas' => $this->input->post('namapasien'),
				'username' => $userid,
			];

			if ($param == 1) {
				$this->db->insert('tbl_apoposting', $data);
				$this->db->insert('tbl_pap', $data_pap);
			} else {
				$this->db->update('tbl_apoposting', $data, array('resepno' => $nobukti));
				$this->db->update('tbl_pap', $data_pap, array('noreg' => $nobukti));
			}
			echo json_encode($nobukti);
		} else {
			header('location:' . base_url());
		}
	}

	public function edit($nomor)
	{
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$unit = $this->session->userdata('unit');
			$posting = $this->db->get_where('tbl_apoposting', array('resepno' => $nomor));
			$header = $this->db->get_where('tbl_apohresep', array('resepno' => $nomor));
			$detil = $this->db->query("SELECT tbl_apodresep.*, (SELECT namabarang FROM tbl_barang WHERE kodebarang=tbl_apodresep.kodebarang) AS namabarang FROM tbl_apodresep WHERE resepno = '$nomor'");
			$d['baranghpo']    = $this->db->query('select * from tbl_baranghpo')->result();
			$d['apohpolog']    = $this->db->query('select * from tbl_apohpolog')->result();
			$d['header']  = $header->row();
			$d['detil']   = $detil->result();
			$d['posting'] = $posting->row();
			$d['jumdata'] = $detil->num_rows();
			$ppxx = $this->db->get_where('tbl_pajak', ['kodetax' => 'PPN'])->row();
			$ppx = $ppxx->prosentase / 100;
			$d['ppn'] = $ppx;
			$this->load->view('penjualan/v_penjualan_faktur_edit_cabang2', $d);
		} else {
			header('location:' . base_url());
		}
	}

	public function update_data(){
		$unit					= $this->session->userdata("unit");
		$userid       = $this->session->userdata('username');
		$nomor 				= $this->input->post("noresep");
		$hmutasi 			= $this->db->query("select * from tbl_apohresep where resepno = '$nomor'")->row();
		$cabang  			= $hmutasi->koders;
		$gudang  			= $hmutasi->gudang;
		$datamutasi 	= $this->db->get_where('tbl_apodresep', ['resepno' => $nomor])->result();
		$data_bhterima 	= $this->db->get_where("tbl_baranghpo", ['po_no' => $hmutasi->pono]);
		if ($data_bhterima->num_rows() > 0) {
			$this->db->set("internalproses", 0);
			$this->db->set("cek", 0);
			$this->db->where("po_no", $hmutasi->pono);
			$this->db->update("tbl_baranghpo");
		} else {
			$this->db->set("internalproses", 0);
			$this->db->set("cek", 0);
			$this->db->where("po_no", $hmutasi->pono);
			$this->db->update("tbl_apohpolog");
		}
		foreach ($datamutasi as $row) {
			$_qty 	= $row->qty;
			$_kode 	= $row->kodebarang;
			$cek = $this->db->get_where("tbl_barangstock", ["kodebarang" => $_kode, "gudang" => $gudang, "koders" => $unit]);
			if($cek->num_rows() > 0){
				$this->db->query("UPDATE tbl_barangstock set keluar=keluar- $_qty, saldoakhir= saldoakhir+ $_qty where kodebarang = '$_kode' and koders = '$unit' and gudang = '$gudang'");
			} else {
				$this->db->query("UPDATE tbl_apostocklog set keluar=keluar- $_qty, saldoakhir= saldoakhir+ $_qty where kodebarang = '$_kode' and koders = '$unit' and gudang = '$gudang'");
			}
		}
		$this->db->delete('tbl_apodresep', array('resepno' => $nomor));
		$this->db->delete('tbl_apohresep', array('resepno' => $nomor));
		$this->db->delete('tbl_apoposting', array('resepno' => $nomor));
		$this->db->delete('tbl_pap', array('noreg' => $nomor));

		$gudang        	= $this->input->post('gudang');
		$po_no         	= $this->input->post('po_cabang');
		$tgl           	= $this->input->post('tanggal');
		$jam           	= $this->input->post('jam');
		$cust_id       	= $this->input->post('cust_id');
		$diskon       	= $this->input->get('diskon');
		$ppn       			= $this->input->get('ppn');
		$fakturpajak   	= $this->input->post("fakturpajak");
		$noprimary  		= $nomor;
		$tujuan					= $this->input->post("cabang");
		$kode  					= $this->input->post('kode');
		$qty   					= $this->input->post('qty');
		$sat   					= $this->input->post('sat');
		$harga 					= $this->input->post('harga');
		$ppn   					= $this->input->post('ppn');
		$disc  					= $this->input->post('disc');
		$discrp  				= $this->input->post('discrp');
		$jumlah 				= $this->input->post('jumlah');
		$exp   					= $this->input->post('tanggal');
		// $exp   = date("Y-m-d");
		$aturan   			= $this->input->post('aturan');
		$norak   				= $this->input->post('norak');
		$jumdata  			= count($kode);
		$tot 						= 0;
		$tdisc 					= 0;
		$tothpp 				= 0;
		for ($i = 0; $i <= $jumdata - 1; $i++) {
			$_kode        = $kode[$i];
			$_qty         = str_replace(',', '', $qty[$i]);
			$_exp         = $exp[$i];
			$_sat         = $sat[$i];
			$_aturan      = $aturan[$i];
			$_norak       = $norak[$i];
			$tot          = $tot + str_replace(',', '', $jumlah[$i]);
			$_jumlah      = str_replace(',', '', $jumlah[$i]);
			$_harga       = str_replace(',', '', $harga[$i]);
			$_discrp      = str_replace(',', '', $discrp[$i]);
			$vjum         = $qty[$i] * $_harga;
			$vdisc        = $vjum * ($disc[$i] / 100);
			$tdisc        = $tdisc + $vdisc;
			$hpp          = $this->M_global->_data_barang($_kode)->hpp;
			$namabarang   = $this->M_global->_data_barang($_kode)->namabarang;
			$tothpp       = $tothpp + ($hpp * $qty[$i]);
			$pajak        = $this->db->get_where("tbl_pajak", ['kodetax' => 'PPN'])->row()->prosentase / 100;
			$_ppn 	= 1;
			$_ppnrp = $vjum * $pajak;
			$jual = [
				'koders'    	=> $cabang,
				'resepno'   	=> $noprimary,
				'kodebarang' 	=> $_kode,
				'namabarang' 	=> $namabarang,
				'qty' 				=> $_qty,
				'satuan' 			=> $_sat,
				'ppn' 				=> $_ppn,
				'ppnrp' 			=> $_ppnrp,
				'hpp' 				=> $hpp,
				'price' 			=> $_harga,
				'discount' 		=> $disc[$i],
				'discrp' 			=> $_discrp,
				'totalrp' 		=> $_jumlah,
				'exp_date' 		=> $_exp,
				'atpakai' 		=> $_aturan,
				'rakno'  			=> $_norak,
			];
			$this->db->insert('tbl_apodresep', $jual);
				$barangstok = $this->db->query("SELECT * FROM tbl_barangstock WHERE koders = '$unit' AND gudang = '$gudang' AND kodebarang = '$_kode'");
				if ($barangstok->num_rows() > 0) {
					$this->db->query("UPDATE tbl_barangstock SET keluar = keluar+$_qty, saldoakhir = saldoakhir-$_qty WHERE kodebarang = '$_kode' AND koders = '$unit'  AND gudang = '$gudang'");
				} else {
					$this->db->query("UPDATE tbl_apostocklog SET keluar = keluar+$_qty, saldoakhir = saldoakhir-$_qty WHERE kodebarang = '$_kode' AND koders = '$unit'  AND gudang = '$gudang'");
				}
		}
		$pajak = 1;
		$data = [
			'koders' 				=> $cabang,
			'resepno' 			=> $noprimary,
			'antrino'  			=> 1,
			'cust_id'  			=> $cust_id,
			'jenisjual'  		=> 2,
			'kodepel'  			=> $this->input->post('pembeli'),
			'rekmed'  			=> $this->input->post('cabang'),
			'pro'  					=> $this->input->post('namapasien'),
			'tglresep'   		=> date('Y-m-d', strtotime($this->input->post('tanggal'))),
			'jam' 					=> date('H:i:s', strtotime($this->input->post('jam'))),
			'gudang'  			=> $this->input->post('gudang'),
			'pajak'  				=> $pajak,
			'fakturpajak'  	=> $fakturpajak,
			'username' 			=> $userid,
			'pono' 					=> $po_no,
		];
		$this->db->insert('tbl_apohresep', $data);
		//posting
		$data = [
			'koders' 		=> $cabang,
			'resepno' 	=> $noprimary,
			'tglresep'  => date('Y-m-d', strtotime($this->input->post('tanggal'))),
			'rekmed'  	=> $tujuan,
			'namapas' 	=> $this->input->post('namapasien'),
			'gudang'  	=> $this->input->post('gudang'),
			'posting' 	=> 1,
			'poscredit' => $this->input->get('totalnet'),
			'username' 	=> $userid,
			'diskonrp' 	=> $diskon,
			'kodepel'		=> $this->input->post('pembeli'),
		];
		$data_pap = [
			'noreg' 				=> $noprimary,
			'koders' 				=> $unit,
			'rekmed' 				=> $tujuan,
			'tglposting' 		=> date('Y-m-d', strtotime($this->input->post('tanggal'))),
			'cust_id' 			=> $this->input->post('cust_id'),
			'jumlahhutang' 	=> $this->input->get('totalnet'),
			'asal' 					=> 'POLI',
			'namapas' 			=> $this->input->post('namapasien'),
			'username' 			=> $userid,
		];
		$this->db->insert('tbl_apoposting', $data);
		$this->db->insert('tbl_pap', $data_pap);
		echo json_encode($nomor);
	}
}
