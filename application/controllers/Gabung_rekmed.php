<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Gabung_rekmed extends CI_Controller
{




	public function __construct()
	{
		parent::__construct();

		$this->load->model('M_global', 'M_global');
		$this->load->model('M_cetak');
		$this->session->set_userdata('menuapp', '6000');
		$this->session->set_userdata('submenuapp', '6102');
		$this->load->model('M_template_cetak');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');
		if (!empty($cek)) {
			$unit = $this->session->userdata('unit');
			$this->load->helper('url');
			$d['cabang'] = $this->db->get('tbl_namers')->result();
			$d['bulan']  = $this->db->query("SELECT*FROM ms_bln order by id")->result();

			$this->load->view('master/v_master_gab_rekmed', $d);
		} else {
			header('location:' . base_url());
		}
	}

	

	function cetak_data($cekpdf=0)
	{

		// $cekpdf   = 0;
		$position = 'L';
		$date     = '-';
		$body     = '';

		$body.= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
			<tr>
				<td style=\"border:0\" colspan=\"18\" align=\"center\"><br></td>                
			</tr>
			<tr>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>No</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>Koders</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>Rekmed</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>Namapas</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>Noidentitas</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>Preposisi</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>Tempatlahir</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>Tgllahir</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>Jkel</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>Status</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>idpas</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>Nocard</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>Pendidikan</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>suku</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>Agama</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>Alamat</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>Alamat2</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>Handphone</b></td>
				<td bgcolor=\"#cccccc\" align=\"center\"><b>Lastnoreg</b></td>
			</tr>";

			$sql =$this->db->query(
				"SELECT*FROM tbl_pasien where noidentitas in (
					select p.noidentitas from(
					SELECT noidentitas,count(*) from tbl_pasien -- where koders<>'abi'
					group by noidentitas
					having count(*)>1
					)p
					) order by noidentitas,namapas ")->result();


			$lcno        = 0;
			$koders      = '';
			$rekmed      = '';
			$namapas     = '';
			$preposisi   = '';
			$tempatlahir = '';
			$tgllahir    = '';
			$jkel        = '';
			$status      = '';
			$noidentitas = '';
			$idpas       = '';
			$nocard      = '';
			$pendidikan  = '';
			$suku        = '';
			$agama       = '';
			$alamat      = '';
			$alamat2     = '';
			$handphone   = '';
			$phone       = '';
			$lastnoreg   = '';



			foreach ($sql as $row) {
				$lcno           = $lcno + 1;
				$koders         = $row->koders;
				$rekmed         = $row->rekmed;
				$namapas        = $row->namapas;
				$preposisi      = $row->preposisi;
				$tempatlahir    = $row->tempatlahir;
				$tgllahir       = $row->tgllahir;
				$jkel           = $row->jkel;
				$status         = $row->status;
				$noidentitas    = $row->noidentitas;
				$idpas          = $row->idpas;
				$nocard         = $row->nocard;
				$pendidikan     = $row->pendidikan;
				$suku           = $row->suku;
				$agama          = $row->agama;
				$alamat         = $row->alamat;
				$alamat2        = $row->alamat2;
				$handphone      = $row->handphone;
				$phone          = $row->phone;
				$lastnoreg      = $row->lastnoreg;


				if($lcno % 2 == 0){
					$bgg="#d2dfee";

				}else{
					$bgg="";
					
				}

				if($cekpdf==2){
					$noidentitas="'".$noidentitas;
				}else{
					$noidentitas=$noidentitas;
				}

				$body .= "<tr>

						<td bgcolor=\"$bgg\" align=\"center\">$lcno</td>
						<td bgcolor=\"$bgg\" align=\"center\">$koders</td>
						<td bgcolor=\"$bgg\" align=\"center\">$rekmed</td>
						<td bgcolor=\"$bgg\" align=\"center\">$namapas</td>
						<td bgcolor=\"$bgg\" align=\"center\">$noidentitas</td>
						<td bgcolor=\"$bgg\" align=\"center\">$preposisi</td>
						<td bgcolor=\"$bgg\" align=\"center\">$tempatlahir</td>
						<td bgcolor=\"$bgg\" align=\"center\">$tgllahir</td>
						<td bgcolor=\"$bgg\" align=\"center\">$jkel</td>
						<td bgcolor=\"$bgg\" align=\"center\">$status</td>
						<td bgcolor=\"$bgg\" align=\"center\">$idpas</td>
						<td bgcolor=\"$bgg\" align=\"center\">$nocard</td>
						<td bgcolor=\"$bgg\" align=\"center\">$pendidikan</td>
						<td bgcolor=\"$bgg\" align=\"center\">$suku</td>
						<td bgcolor=\"$bgg\" align=\"center\">$agama</td>
						<td bgcolor=\"$bgg\" align=\"center\">$alamat</td>
						<td bgcolor=\"$bgg\" align=\"center\">$alamat2</td>
						<td bgcolor=\"$bgg\" align=\"center\">$handphone</td>
						<td bgcolor=\"$bgg\" align=\"center\">$lastnoreg</td>
						</tr>"; 
			
			}




			$body .= "</table>";

			$judul        = 'DATA PASIEN GANDA';

			$this->M_template_cetak->template($judul, $body, $position, $date, $cekpdf);
		
	}


	public function gabdat($status_masuk='')
	{
		
		$rm1    = $this->input->post('rm1');
		$rm2    = $this->input->post('rm2');
		$jam    = date("H:i:s");		
		
		$sql = $this->db->query("CALL gabung_rekmed('$rm1','$rm2')");

		if($sql){

			$sql1   = $this->db->query("SELECT * FROM tbl_regist WHERE rekmed in ('$rm2') order by tglmasuk desc,jam desc,noreg desc limit 1")->row();

			$sql2   = $this->db->query("UPDATE tbl_pasien set lastnoreg='$sql1->noreg' WHERE rekmed in ('$rm2') ");

			if($sql2){
				$sql3   = $this->db->query("DELETE from tbl_pasien WHERE rekmed in ('$rm1')");
				history_log(0 ,'Gabung_Rekmed' ,'OK' ,$rm1.'-'.$rm2 ,'-');
				if($sql3){
					echo json_encode(array("status" => '1'));
				}else{
					echo json_encode(array("status" => '2'));
				}
			}else{
				echo json_encode(array("status" => '2'));
			}

		}else{
			echo json_encode(array("status" => '2'));
		}
	}

}
