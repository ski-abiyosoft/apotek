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

  function index(){
    setlocale(LC_TIME, 'id_ID.utf8');
    $hariIni = new DateTime();
    $now = date("Y-m-d");
    $cabang = $this->session->userdata('unit');
    $dokter = $this->db->query("SELECT (SELECT nadokter FROM tbl_dokter WHERE kodokter = r.kodokter) nadokter, r.* FROM tbl_regist r WHERE r.tglmasuk = '2022-11-01' AND r.koders = '$cabang' GROUP BY r.kodokter")->result();
    $limit = 4;
    $dokter_show = $this->db->query("SELECT (SELECT nadokter FROM tbl_dokter WHERE kodokter = r.kodokter) nadokter, r.* FROM tbl_regist r WHERE r.tglmasuk = '2022-11-01' AND r.koders = '$cabang' GROUP BY r.kodokter ORDER BY id ASC LIMIT $limit")->result();
    foreach($dokter as $d) {
      $sql = $this->db->query("SELECT concat(antrino1, '.',antrino) as noantri FROM tbl_regist WHERE kodokter = '$d->kodokter' AND tglmasuk = '2022-11-01' AND koders = '$cabang' ORDER BY id ASC LIMIT 3")->result();
    }
    $data = [
      'cabang' => $cabang,
      'tgl' => strftime('%d %B %Y', $hariIni->getTimestamp()),
      'dokter' => $dokter,
      'dokter_show' => $dokter_show,
      'sql' => $sql,
    ];
    $this->load->view("Display", $data);
  }
}
?>