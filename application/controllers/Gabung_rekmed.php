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

	

	function cetak_data()
	{

		$cekpdf   = 1;
		$position = 'L';
		$date     = '-';
		$body     = '';

		$body.= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"3\">
			<tr>
				<td style=\"border:0\" colspan=\"13\" align=\"center\"><br></td>                
			</tr>
			<tr>
				<td bgcolor=\"#cccccc\" width=\"2%\" align=\"center\"><b>koders</b></td>
				<td bgcolor=\"#cccccc\" width=\"2%\" align=\"center\"><b>rekmed</b></td>
				<td bgcolor=\"#cccccc\" width=\"2%\" align=\"center\"><b>namapas</b></td>
				<td bgcolor=\"#cccccc\" width=\"2%\" align=\"center\"><b>preposisi</b></td>
				<td bgcolor=\"#cccccc\" width=\"2%\" align=\"center\"><b>tempatlahir</b></td>
				<td bgcolor=\"#cccccc\" width=\"2%\" align=\"center\"><b>tgllahir</b></td>
				<td bgcolor=\"#cccccc\" width=\"2%\" align=\"center\"><b>jkel</b></td>
				<td bgcolor=\"#cccccc\" width=\"2%\" align=\"center\"><b>status</b></td>
				<td bgcolor=\"#cccccc\" width=\"2%\" align=\"center\"><b>noidentitas</b></td>
				<td bgcolor=\"#cccccc\" width=\"2%\" align=\"center\"><b>nocard</b></td>
				<td bgcolor=\"#cccccc\" width=\"2%\" align=\"center\"><b>pendidikan</b></td>
				<td bgcolor=\"#cccccc\" width=\"2%\" align=\"center\"><b>agama</b></td>
				<td bgcolor=\"#cccccc\" width=\"2%\" align=\"center\"><b>pekerjaan</b></td>
				<td bgcolor=\"#cccccc\" width=\"2%\" align=\"center\"><b>goldarah</b></td>
				<td bgcolor=\"#cccccc\" width=\"2%\" align=\"center\"><b>alamat</b></td>
				<td bgcolor=\"#cccccc\" width=\"2%\" align=\"center\"><b>alamat2</b></td>
				<td bgcolor=\"#cccccc\" width=\"2%\" align=\"center\"><b>handphone</b></td>
				<td bgcolor=\"#cccccc\" width=\"2%\" align=\"center\"><b>useredit</b></td>
			</tr>";

			$sql =
				"SELECT*FROM tbl_pasien where namapas in (
				select p.namapas from(
				SELECT koders,namapas,tgllahir,noidentitas,count(*) from tbl_pasien
				group by koders,namapas,tgllahir,noidentitas
				having count(*)>1
				)p
				) order by namapas";

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

					// $chari .= "<tr>
					// <td width=\" 1%\" align=\"left\">$lcno.</td>
					// <td width=\" 12%\" align=\"left\">$namakary</td>
					// <td width=\" 15%\" align=\"left\">: $jam_m - $jam_k</td>
					// <td width=\" 72%\" align=\"left\"></td>
					// </tr>"; 
				
				
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
