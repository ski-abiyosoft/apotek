	<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Absen_laporan extends CI_Controller
	{




	public function __construct()
	{
		parent::__construct();

		$this->load->model('M_global', 'M_global');
		$this->load->model('M_cetak');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '6000');
		$this->session->set_userdata('submenuapp', '6102');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$unit = $this->session->userdata('unit');
			$this->load->helper('url');
			$d['cabang'] = $this->db->get('tbl_namers')->result();

			$this->load->view('master/v_master_absen_lap', $d);
		} else {
			header('location:' . base_url());
		}
	}


	function cetak_absen($cek = '', $thnn = '')
	{

		$chari   	  = '';
		$cekk         = $this->session->userdata('level');
		$unit   	  = $this->session->userdata('unit');
		$unit   	  = $this->session->userdata('unit');
		$profile      = $this->M_global->_LoadProfileLap();
		$nama_usaha   = $profile->nama_usaha;
		$motto        = '';
		$alamat       = '';
		$cek          = $this->input->get('cek');
		$tgl1         = $this->input->get('tgl1');
		$tgl2         = $this->input->get('tgl2');
		$_tgl1        = date('Y-m-d', strtotime($tgl1));
		$_tgl2        = date('Y-m-d', strtotime($tgl2));
		$_peri        = 'Dari ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2));
		if (!empty($cekk)) {
			$kop       = $this->M_cetak->kop($unit);
			$namars    = $kop['namars'];
			$alamat    = $kop['alamat'];
			$alamat2   = $kop['alamat2'];
			$phone     = $kop['phone'];
			$whatsapp  = $kop['whatsapp'];
			$npwp      = $kop['npwp'];


			$chari .= "<table style=\"border-collapse:collapse;font-family: Century Gothic; font-size:12px; color:#000;\" width=\"100%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
						
			<thead>
			<tr>
				<td rowspan=\"6\" align=\"center\">
				<img src=\"" . base_url() . "assets/img/abiyosoft.png\"  width=\"100\" height=\"50\" /></td>

				<td colspan=\"20\"><b>
					<tr><td align=\"center\" style=\"font-size:25px;color:#120292;\"><b>PT SISTEM KESEHATAN INDONESIA (SKI) DIY ABIYOSOFT</b></td></tr>
					<tr><td align=\"center\" style=\"font-size:13px;\">Ideazone, Jalan Magelang St No.188, Karangwaru, Tegalrejo, Yogyakarta City</td></tr>
					<tr><td align=\"center\" style=\"font-size:13px;\">Special Region of Yogyakarta 55242</td></tr>
					<tr><td align=\"center\" style=\"font-size:13px;\">Wa :+6281389158889   |  E-mail :ski.abiyosoft@gmail.com | Website : https://abiyosoft.com/</td></tr>

				</b></td>
			</tr> 
				
			
			</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
						
				<thead>
				<tr>
					<td colspan=\"21\" style=\"text-align:center;font-size:22px;border-bottom: none;color:#120292;\"><b><br></b></td>
				</tr> 
				<tr>
					<td colspan=\"21\" style=\"text-align:center;font-size:22px;border-bottom: none;\"><b>DAFTAR TINGKAT KEHADIRAN KARYAWAN/PEGAWAI </b></td>
				</tr> 
				<tr>
					<td colspan=\"21\" style=\"text-align:center;font-size:15px;border-bottom: none;\"><b>UNIT KERJA SISTEM KLINIK INDONESIA DIY </b></td>
				</tr> 
					
				<tr>
					<td colspan=\"21\" style=\"text-align:center;font-size:15px;border-top: none;\">$_peri</td>
				</tr>
				
				</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">

		<thead>
			<tr>
				<td style=\"border:0\" align=\"center\"><br></td>                
			</tr>
			<tr>
				<td rowspan=\"2\" bgcolor=\"#cccccc\" width=\"3%\" align=\"center\"><b>NO</b></td>                
				<td rowspan=\"2\"  bgcolor=\"#cccccc\" width=\"7%\" align=\"center\"><b>NIK</b></td>
				<td rowspan=\"2\"  bgcolor=\"#cccccc\" width=\"30%\" align=\"center\"><b>NAMA LENGKAP</b></td>
				
				<td rowspan=\"2\"  bgcolor=\"#cccccc\" width=\"60%\" align=\"center\"><b>L/P</b></td>
				<td colspan=\"3\" bgcolor=\"#cccccc\" width=\"60%\" align=\"center\"><b>Agustus</b></td>
			</tr>
			<tr>
				<td bgcolor=\"#cccccc\" width=\"15%\" align=\"center\"><b>BELUM TERPAKAI</b></td>
				<td bgcolor=\"#cccccc\" width=\"15%\" align=\"center\"><b>BEBAN TRX</b></td>
				<td bgcolor=\"#cccccc\" width=\"10%\" align=\"center\"><b>SISA DP</b></td>
			</tr>
			
		</thead>";

			$sql = $query =
				"SELECT rekmed,sum(masuk+kembali)masuk,sum(keluar)keluar,koders,namapas FROM( 
		SELECT a.rekmed,a.kembali,
		case when posbayar='UANG_MUKA' then uangmuka else 0 end masuk , 
		case when posbayar<>'UANG_MUKA' then uangmuka else 0 end	keluar,
		a.koders,(select b.namapas from tbl_pasien b where a.rekmed=b.rekmed  )namapas 
		from tbl_kasir a
		where tglbayar between '$_tgl1' and '$_tgl2' and a.koders='$unit'
		)a where a.masuk<>0 or a.keluar<>0
		GROUP BY rekmed,koders,namapas
		ORDER BY rekmed
		";

			$query1    = $this->db->query($sql);

			$lcno        = 0;
			$tgl         = 0;
			$rekmed      = 0;
			$nm          = 0;
			$nokwi       = 0;
			$masuk       = 0;
			$digunakan   = 0;
			$jum_in      = 0;
			$jum_out     = 0;
			$jum_sisa    = 0;
			$sisa1       = 0;




			foreach ($query1->result() as $row) {
				$lcno         = $lcno + 1;
				$rekmed       = $row->rekmed;
				$nm           = $row->namapas;
				$masuk        = angka_rp($row->masuk, 0);
				$digunakan    = angka_rp($row->keluar, 0);
				$sisa    	  = angka_rp($row->masuk - $row->keluar, 0);

				$chari .= "<tr>
				<td align=\"center\">$lcno</td>
				<td align=\"center\">$rekmed</td>
				<td align=\"left\">$nm</td>
				<td align=\"right\">$masuk</td>
				<td align=\"right\">$digunakan</td>
				<td align=\"right\">$sisa </td>
				</tr>";

				$jum_in   += $row->masuk;
				$jum_out  += $row->keluar;
				$jum_sisa += $row->masuk - $row->keluar;
			}

			$jum2_in   = angka_rp($jum_in, 0);
			$jum2_out  = angka_rp($jum_out, 0);
			$jum2_sisa = angka_rp($jum_sisa, 0);

			$chari .= "<tr>
				<td bgcolor=\"#cccccc\" colspan=\"3\" align=\"center\"><b>TOTAL </b></td>
				<td bgcolor=\"#cccccc\" align=\"right\"><b>$jum2_in</b></td>
				<td bgcolor=\"#cccccc\" align=\"right\"><b>$jum2_out</b></td>
				<td bgcolor=\"#cccccc\" align=\"right\"><b>$jum2_sisa</b></td>
				</tr>";



			$chari .= "</table>";

			$data['prev'] = $chari;
			$judul        = 'REKAP UANG MUKA';


			switch ($cek) {
				case 0;
					echo ("<title>REKAP UANG MUKA</title>");
					echo ($chari);
					break;

				case 1;
					$this->M_cetak->mpdf('L', 'A3', $judul, $chari, 'LAPORAN-KASIR-07.PDF', 10, 10, 10, 2);
					break;
				case 2;
					header("Cache-Control: no-cache, no-store, must-revalidate");
					header("Content-Type: application/vnd-ms-excel");
					header("Content-Disposition: attachment; filename= $judul.xls");
					$this->load->view('app/master_cetak', $data);
					break;
			}
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