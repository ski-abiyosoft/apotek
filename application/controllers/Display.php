<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Display extends CI_Controller{
  function __construct()
  {
    parent::__construct();
    $this->is_logged_in();
    $this->load->model('M_dashboard');
    $this->load->model('M_template_cetak');
  }

  public function is_logged_in()
  {
    $is_logged_in = $this->session->userdata('is_logged_in');
    if (!isset($is_logged_in) || $is_logged_in != true) {
      redirect(base_url());
    }
  }

  function set_dokter(){
    $dokter1 = $this->input->get('dokter1');
    $dokter2 = $this->input->get('dokter2');
    $dokter3 = $this->input->get('dokter3');
    $dokter4 = $this->input->get('dokter4');
    $data_dokter = [
      'kodokter1' => $dokter1,
      'kodokter2' => $dokter2,
      'kodokter3' => $dokter3,
      'kodokter4' => $dokter4,
    ];
    $this->db->update("dokter_display", $data_dokter, ["id" => 1]);
  }

  function index(){
    setlocale(LC_TIME, 'id_ID.utf8');
    $hariIni = new DateTime();
    $now = date("Y-m-d");
    $cabang = $this->session->userdata('unit');
    $dokter = $this->db->query("SELECT dc.kodokter, a.nadokter FROM dokter AS a JOIN tbl_doktercabang dc ON dc.kodokter=a.kodokter WHERE dc.koders = '$cabang' AND a.koders = '$cabang' AND a.kodokter IN (SELECT kodokter FROM tbl_regist WHERE tujuan = 1) AND LEFT(dc.kodokter, 3) = '$cabang' GROUP BY dc.kodokter ORDER BY a.nadokter")->result();
    
    $dokter = $this->db->query("SELECT (SELECT nadokter FROM tbl_dokter WHERE kodokter = r.kodokter) nadokter, r.* FROM dokter_display dk JOIN tbl_regist r ON dk.kodokter = r.kodokter WHERE r.tglmasuk = '2022-11-01' AND r.koders = '$cabang' GROUP BY r.kodokter ORDER BY r.kodokter ASC")->result();
    $limit = 4;
    $dokter_show = $this->db->query("SELECT (SELECT nadokter FROM tbl_dokter WHERE kodokter = r.kodokter) nadokter, r.* FROM tbl_regist r WHERE r.tglmasuk = '2022-11-01' AND r.koders = '$cabang' GROUP BY r.kodokter ORDER BY id ASC LIMIT $limit")->result();
    if($dokter){
      foreach($dokter as $d) {
        $sqlx = $this->db->query("SELECT concat(antrino1, '.',antrino) as noantri, rekmed FROM tbl_regist WHERE kodokter = '$d->kodokter' AND tglmasuk = '2022-11-01' AND koders = '$cabang' AND keluar = 0 AND rekmed IN (SELECT rekmed FROM tbl_pasien) ORDER BY id ASC LIMIT 3");
        if($sqlx->num_rows() > 0){
          $sql = $sqlx->result();
        } else {
          $sql = $this->db->query("SELECT concat('-', '.', '0') as noantri, '-' as rekmed FROM tbl_regist LIMIT 1")->result();
        }
      }
    } else {
      $dokter = $this->db->query("SELECT '-' nadokter, '-' as kodokter FROM tbl_regist r LIMIT 1")->result();
      $sql = $this->db->query("SELECT concat('-', '.', '0') as noantri, '-' as rekmed FROM tbl_regist LIMIT 1")->result();
    }
    $data = [
      'cabang' => $cabang,
      'tgl' => strftime('%d %B %Y', $hariIni->getTimestamp()),
      'dokter' => $dokter,
      'dokter_show' => $dokter_show,
      'sql' => $sql,
      'dkr' => $dkr,
    ];
    $this->load->view("Display", $data);
  }
}
?>