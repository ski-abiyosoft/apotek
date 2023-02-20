<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_ap extends CI_Controller
{

  public function __construct() 
  {
		parent::__construct();
		$this->load->model('M_kode');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '1000');
		$this->session->set_userdata('submenuapp', '1103');
	}

  public function index()
  {
    $cek      = $this->session->userdata('level');
		$unit     = $this->session->userdata('unit');
		if(!empty($cek)) {
      $data["cabang"] = $unit;
			$data["kode"] = $this->M_kode->kode_ap();
      $data['atp']  = $this->db->query("SELECT * FROM tbl_barangsetup WHERE apogroup='ATURANPAKAI'")->result();
			$this->load->view('master/v_masterap', $data);
		} else {
			header('location:'.base_url());
		}
  }

	public function cek_nama() {
		$nama = $this->input->get("aponame");
		$data = $this->db->query("SELECT * FROM tbl_barangsetup WHERE apogroup='ATURANPAKAI' AND aponame = '$nama'")->num_rows();
		if($data > 0) {
			echo json_encode(["status" => 1]);
		} else {
			echo json_encode(["status" => 0]);
		}
	}

	public function tambah_ap() {
		$kode = $this->M_kode->kode_ap();
		$nama = $this->input->get("aponame");
		$data = [
			'apocode' => $kode,
			'aponame' => $nama,
			'apogroup' => 'ATURANPAKAI'
		];
		$cek = $this->db->insert("tbl_barangsetup", $data);
		if($cek) {
			echo json_encode(["status" => 1]);
		} else {
			echo json_encode(["status" => 0]);
		}
	}

	public function delete($kode) {
		$data = $this->db->query("DELETE FROM tbl_barangsetup WHERE apocode = '$kode' AND apogroup = 'ATURANPAKAI'");
		if($data) {
			echo json_encode(["status" => 1]);
		} else {
			echo json_encode(["status" => 0]);
		}
	}

	public function update() {
		$kode = $this->input->get("apocode");
		$nama = $this->input->get("aponame");
		$data = $this->db->query("UPDATE tbl_barangsetup SET aponame = '$nama' WHERE apocode = '$kode' AND apogroup = 'ATURANPAKAI'");
		if($data) {
			echo json_encode(["status" => 1]);
		} else {
			echo json_encode(["status" => 0]);
		}
	}
}
?>