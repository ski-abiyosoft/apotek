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
		$d['bulan']  = $this->db->query("SELECT*FROM ms_bln order by id")->result();

		$this->load->view('master/v_master_absen_lap', $d);
	} else {
		header('location:' . base_url());
	}
}



function cetak_absen_h($cek = '', $thnn = '')
{

	$chari    = '';
	$tglh     = $this->input->get('tglh');
	$_tglh    = date('Y-m-d', strtotime($tglh));
	$hari     = $this->M_cetak->hari_indo($tglh);
	$tgll     = $this->M_cetak->tanggal_format_indonesia($tglh);
	$_peri    = 'Tanggal ' . date('d-M-Y', strtotime($tglh)) ;

	$dari   = '2022-02-14';
	$tgl1   = strtotime($dari);
	$tgl2   = strtotime($tglh);
	$jarak  = ($tgl2 - $tgl1)/ 60 / 60 / 24;


	$kop      = $this->M_cetak->kop('abi');
	$namars   = $kop['namars'];
	$alamat   = $kop['alamat'];
	$alamat2  = $kop['alamat2'];
	$phone    = $kop['phone'];
	$whatsapp = $kop['whatsapp'];
	$npwp     = $kop['npwp'];


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

		

		$chari .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">

	<thead>
		<tr>
			<td colspan=\"4\" align=\"center\"><br></td>                
		</tr>
		<tr>
			<td colspan=\"4\" ><b>#$jarak Daftar Kehadiran</b></td>  
		</tr>
		<tr>
			<td colspan=\"4\" ><b>$hari, $tgll</b></td>  
		</tr>
		<tr>
			<td colspan=\"4\" ><b>(Diisi pada jam awal memulai)</b></td>  
		</tr>
		<tr>
			<td colspan=\"4\" ><b>--------------------------------</b></td>  
		</tr>
		
	</thead>";

		$sql =
			"SELECT*,(select keterangan from tbl_setinghms s where s.kodeset=p.status_absen)nm_stat from(
			SELECT a.*,
			COALESCE((select status from tbl_absen_ski b where a.nik=b.nik and tgl_absen='$_tglh' and status_masuk='1' ),'-')status_absen,
			COALESCE((select left(jam,5) from tbl_absen_ski b where a.nik=b.nik and tgl_absen='$_tglh' and status_masuk='1' ),'00:00')jam_m, 
			COALESCE((select left(jam,5) from tbl_absen_ski b where a.nik=b.nik and tgl_absen='$_tglh' and status_masuk='2' ),'00:00')jam_k 
			from tbl_kary_ski a where pos='DIY'
			)p
			order by namakary ";

		$query1    = $this->db->query($sql);

		$lcno            = 0;
		$nm              = 0;
		$jam_m           = 0;
		$jam_k           = 0;
		$status_absen    = '';
		$nm_stat	     = '';


		foreach ($query1->result() as $row) {
			$lcno           = $lcno + 1;
			$namakary       = $row->namakary;
			$status_absen   = $row->status_absen;
			$nm_stat        = $row->nm_stat;
			$jam_m          = $row->jam_m;
			$jam_k          = $row->jam_k;

			if($status_absen=='AB1'){
				$chari .= "<tr>
				<td width=\" 1%\" align=\"left\">$lcno.</td>
				<td width=\" 12%\" align=\"left\">$namakary</td>
				<td width=\" 15%\" align=\"left\">: $jam_m - $jam_k</td>
				<td width=\" 72%\" align=\"left\"></td>
				</tr>"; 
			}else if($status_absen=='AB2'){
				$chari .= "<tr>
				<td width=\" 1%\" align=\"left\">$lcno.</td>
				<td width=\" 12%\" align=\"left\">$namakary</td>
				<td width=\" 15%\" align=\"left\">: $jam_m - $jam_k ( $nm_stat )</td>
				<td width=\" 72%\" align=\"left\"></td>
				</tr>"; 
			}else{
				$chari .= "<tr>
				<td width=\" 1%\" align=\"left\">$lcno.</td>
				<td width=\" 12%\" align=\"left\">$namakary</td>
				<td width=\" 15%\" align=\"left\">: $nm_stat</td>
				<td width=\" 72%\" align=\"left\"></td>
				</tr>"; 
			}
			
		}




		$chari .= "</table>";

		$data['prev'] = $chari;
		$judul        = 'ABSENSI';


		switch ($cek) {
			case 0;
				echo ("<title>ABSENSI</title>");
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
	
}

function cetak_absen($cek_pdf = '', $thnn = '')
{

	$chari        = '';
	$cekk         = $this->session->userdata('level');
	$unit         = $this->session->userdata('unit');
	$unit         = $this->session->userdata('unit');
	$profile      = $this->M_global->_LoadProfileLap();
	$nama_usaha   = $profile->nama_usaha;
	$motto        = '';
	$alamat       = '';
	$cek_pdf      = $this->input->get('cek');
	$tgl1         = $this->input->get('tgl1');
	$tgl2         = $this->input->get('tgl2');
	$_tgl1        = date('d', strtotime($tgl1));
	$bull         = $this->M_cetak->getBulan(date('m', strtotime($tgl1)));
	$_tgl2        = date('d', strtotime($tgl2));
	$_peri        = 'Dari ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2));

	$dtStart      = new DateTime($tgl1);
	$dtEnd        = new DateTime($tgl2);
	$interval     = $dtStart->diff($dtEnd);
	$nDays        = intval($interval->format('%a'));
	$workDateA    = new DateTime( $tgl1 );
	$workDateC    = new DateTime( $tgl1 );
	$workDateD    = new DateTime( $tgl1 );
	$harijum      = $nDays;
	$harijum1     = $nDays+1;

	
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
				<td colspan=\"21\" style=\"text-align:center;font-size:15px;border-top: none;\">$_peri<br><br></td>
			</tr>
			
			</table>";

			$chari .= "<table style=\"border-collapse:collapse;font-family: tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
					
			<tr>
				<td align=\"center\"><b>Periode</b></td>                
				<td align=\"center\">$bull</td>                
				<td align=\"center\"></td>                
				<td align=\"center\"><b>Total</b></td>                
				<td align=\"center\">:</td>                
				<td align=\"center\">10</td>                
				<td align=\"center\"></td>                
				<td align=\"center\"><b>Laki- Laki</b></td>                
				<td align=\"center\">:</td>                
				<td align=\"center\">7</td>                
				<td align=\"center\"></td>                
				<td align=\"center\"><b>Perempuan</b></td>                
				<td align=\"center\">:</td>                
				<td align=\"center\">3</td>                
			</tr>
			
			</table>";

		$chari .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">

	<thead>
		
		<tr>
			<td style=\"border:0\" align=\"center\"><br></td>                
		</tr>
		<tr>
			<td rowspan=\"3\" bgcolor=\"#cccccc\" width=\"3%\" align=\"center\"><b>NO</b></td>                
			<td rowspan=\"3\" bgcolor=\"#cccccc\" width=\"5%\" align=\"center\"><b>NIK</b></td>
			<td rowspan=\"3\" bgcolor=\"#cccccc\" width=\"5%\" align=\"center\"><b>NAMA LENGKAP</b></td>
			<td rowspan=\"3\" bgcolor=\"#cccccc\" width=\"3%\" align=\"center\"><b>L/P</b></td>
			<td rowspan=\"3\" bgcolor=\"#cccccc\" width=\"7%\" align=\"center\"><b>JAB/TFWT</b></td>
			<td colspan=\"$harijum1\" bgcolor=\"#cccccc\" width=\"75%\" align=\"center\"> <b>$bull</b></td>
			<td rowspan=\"3\" bgcolor=\"#cccccc\" width=\"2%\" align=\"center\"><b>Rkp</b></td>
		</tr>
		<tr>";
		for ($i = 0; $i <= $harijum; $i++) {
			$hariindo=$this->M_cetak->hari_indo($workDateA->format('D'));
			if($hariindo=='Minggu'){
				$bgg='#12d1ed';
			}else{
				$bgg='#cccccc';

			}
			$chari .="<td bgcolor=\"$bgg\" align=\"center\"> $hariindo </td>";
			$workDateA->modify('+1 day');
			}
			$workDateA    = new DateTime( $tgl1 );

		$chari .="
		</tr>
		<tr>";
		for ($i = 0; $i <= $harijum; $i++) {
			$hariindo=$this->M_cetak->hari_indo($workDateA->format('D'));
			if($hariindo=='Minggu'){
				$bgg='#12d1ed';
			}else{
				$bgg='#cccccc';

			}

			$chari .="<td bgcolor=\"$bgg\" align=\"center\"><b> ".$workDateA->format('d') ."/".$workDateA->format('m')." </b></td>";
			$workDateA->modify('+1 day');
		}
		$workDateA    = new DateTime( $tgl1 );
		
		$chari .="</tr>
		
	</thead>";

	$sql =
	"SELECT a.*,
	(select count(*)jum from tbl_absen_ski b where b.nik=a.nik and tgl_absen between '$tgl1' and '$tgl2' and status='ab1' and status_masuk='1')rekap
	 from tbl_kary_ski a  where resign=0  order by namakary ";

	$query1   = $this->db->query($sql)->result();
	$lcno     = 0;
	$nm       = 0;


	foreach ($query1 as $row) {
		$lcno            = $lcno + 1;
		$nik             = $row->nik;
		$namakary        = $row->namakary;
		$jkel            = $row->jkel;
		$jab             = $row->jab;
		$rekap           = $row->rekap;
		
		

			$chari .= 
			"<tr>
			<td rowspan=\"2\" align=\"center\">$lcno</td>
			<td rowspan=\"2\" align=\"center\">$nik</td>
			<td rowspan=\"2\" align=\"left\">$namakary</td>
			<td rowspan=\"2\" align=\"center\">$jkel</td>
			<td rowspan=\"2\" align=\"left\">$jab</td>";
			for ($i = 0; $i <= $harijum; $i++) {
				
				$workDateC_    = $workDateC->format('Y-m-d');
				$cek           = $this->db->query("SELECT * from tbl_absen_ski b where  tgl_absen = '$workDateC_' AND NIK='$nik'")->num_rows();
				
				if($cek>0){
					$query = $this->db->query("SELECT 
					COALESCE(left(case when status_masuk='1' then jam else '00:00' end,5),'00:00') as jamm,
					COALESCE(left((select jam from tbl_absen_ski c where c.nik=b.nik and c.tgl_absen=b.tgl_absen and status_masuk='2'),5),'00:00')jamk,
					(select keterangan from tbl_setinghms s where s.kodeset=b.status)nm_stat,b.* 
					from tbl_absen_ski b 
					where tgl_absen = '$workDateC_'  AND NIK='$nik' and status_masuk='1'
					")->row();
				}else{ 
					$query = $this->db->query("SELECT (select keterangan from tbl_setinghms s where s.kodeset=b.status)nm_stat,b.* from tbl_absen_ski b where NIK='123' ")->row();
				}


				$hariindo=$this->M_cetak->hari_indo($workDateC->format('D'));
				if($hariindo=='Minggu'){
					$bgg='#12d1ed';
				}else if($hariindo<>'Minggu' && $query->status<>'AB1'){
					$bgg='#f31a46';

				}else{
					$bgg='';
				}

				if($query->status<>'AB1'){
					$chari .="<td bgcolor=\"$bgg\" align=\"center\">".$query->nm_stat."</td>";

				}else{
					$chari .="<td bgcolor=\"$bgg\" align=\"left\">".$query->jamm ." - ". $query->jamk."</td>";
				}
			
				$workDateC->modify('+1 day');
			}
			$workDateC    = new DateTime( $tgl1 );

			$chari .="
			<td rowspan=\"2\" align=\"center\">$rekap</td>
			</tr>
			
			<tr>";
			
			for ($i = 0; $i <= $harijum; $i++) {
				$workDateD_    = $workDateD->format('Y-m-d');
				$cek           = $this->db->query("SELECT * from tbl_absen_ski b where  tgl_absen = '$workDateD_' AND NIK='$nik'")->num_rows();

				if($cek>0){
					$query = $this->db->query("SELECT * from tbl_absen_ski b where  tgl_absen = '$workDateD_' AND NIK='$nik' and status_masuk='1' ")->row();
				}else{ 
					$query = $this->db->query("SELECT * from tbl_absen_ski b where NIK='123' ")->row();
				}

				$hariindo=$this->M_cetak->hari_indo($workDateD->format('D'));
				if($hariindo=='Minggu'){
					$bgg='#12d1ed';
				}else{
					$bgg='';

				}

				if($query->status=='AB1'){
					$stat='1';
				}else{
					$stat='0';
				}

				$chari .="<td bgcolor=\"$bgg\" align=\"center\">$stat</td>";
			
				$workDateD->modify('+1 day');
			}
			
			$workDateD    = new DateTime( $tgl1 );
			$chari .="
			</tr>
			";

			// $jum_in   += $row->masuk;
			// $jum_out  += $row->keluar;
			// $jum_sisa += $row->masuk - $row->keluar;
	}

		$jumrek=$harijum1+6;
		$chari .= "<tr>
			<td bgcolor=\"#cccccc\" colspan=\"$jumrek\" align=\"center\"><b></b></td>
			</tr>";



		$chari .= "</table>";

		$data['prev'] = $chari;
		$judul        = 'ABSENSI';


		switch ($cek_pdf) {
			case 0;
				echo ("<title>ABSENSI</title>");
				echo ($chari);
				break;

			case 1;
				$this->M_cetak->mpdf('L', 'A3', $judul, $chari, 'LAPORAN-ABSEN_SKI.PDF', 10, 10, 10, 2);
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