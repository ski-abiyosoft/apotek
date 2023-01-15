<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Display extends CI_Controller{
  function __construct(){
    parent::__construct();
    $this->is_logged_in();
    $this->load->model('M_dashboard');
    $this->load->model('M_template_cetak');
    $this->load->model('M_global');
    $this->load->model('M_Poliklinik');
  }

  public function is_logged_in(){
    $is_logged_in = $this->session->userdata('is_logged_in');
    if (!isset($is_logged_in) || $is_logged_in != true) {
      redirect(base_url());
    }
  }

  public function set_id($id){
    $noreg1 = $this->input->get("noreg1");
    $noreg2 = $this->input->get("noreg2");
    $noreg3 = $this->input->get("noreg3");
    $noreg4 = $this->input->get("noreg4");
    $this->db->query("UPDATE tbl_regist SET id_display = '$id' WHERE (noreg = '$noreg1' OR noreg = '$noreg2' OR noreg = '$noreg3' OR noreg = '$noreg4')");
  }

  public function set_dokter($id){
    $nama_display = $this->input->get("nama");
    $kodokter1 = $this->input->get('kodokter1');
    $kodokter2 = $this->input->get('kodokter2');
    $kodokter3 = $this->input->get('kodokter3');
    $kodokter4 = $this->input->get('kodokter4');
    $cek_id = $this->db->get_where("dokter_display", ["id" => $id]);
    if($cek_id->num_rows() > 0){
      $id_new = (int)$id + 1;
    } else {
      $id_new = (int)$id;
    }
    $where = [
      'id' => $id,
    ];
    $data_dokterx = [
      'nama_display' => $nama_display,
      'kodokter1' => $kodokter1,
      'kodokter2' => $kodokter2,
      'kodokter3' => $kodokter3,
      'kodokter4' => $kodokter4,
    ];
    $data_dokter = [
      'id' => $id,
      'nama_display' => $nama_display,
      'kodokter1' => $kodokter1,
      'kodokter2' => $kodokter2,
      'kodokter3' => $kodokter3,
      'kodokter4' => $kodokter4,
    ];
    $cek = $this->db->get_where("dokter_display", $where);
    if($cek->num_rows() > 0){
      $this->db->update("dokter_display", $data_dokterx, ["id" => $id]);
    } else {
      $this->db->insert("dokter_display", $data_dokter);
    }
    $data_cek = $this->db->get_where("dokter_display", $where)->row();
    $dokter1 = $this->db->query("SELECT kodokter, UPPER(nadokter) as nadokter FROM tbl_dokter WHERE kodokter = '$data_cek->kodokter1'")->row();
    $dokter2 = $this->db->query("SELECT kodokter, UPPER(nadokter) as nadokter FROM tbl_dokter WHERE kodokter = '$data_cek->kodokter2'")->row();
    $dokter3 = $this->db->query("SELECT kodokter, UPPER(nadokter) as nadokter FROM tbl_dokter WHERE kodokter = '$data_cek->kodokter3'")->row();
    $dokter4 = $this->db->query("SELECT kodokter, UPPER(nadokter) as nadokter FROM tbl_dokter WHERE kodokter = '$data_cek->kodokter4'")->row();
    $data = [
      'kodokter1' => $dokter1->kodokter,
      'nadokter1' => mb_strimwidth($dokter1->nadokter, 0, 25, "..."),
      'kodokter2' => $dokter2->kodokter,
      'nadokter2' => mb_strimwidth($dokter2->nadokter, 0, 25, "..."),
      'kodokter3' => $dokter3->kodokter,
      'nadokter3' => mb_strimwidth($dokter3->nadokter, 0, 25, "..."),
      'kodokter4' => $dokter4->kodokter,
      'nadokter4' => mb_strimwidth($dokter4->nadokter, 0, 25, "..."),
      'nama_display' => $data_cek->nama_display,
    ];
    echo json_encode($data);
  }

  public function hapusbaris($id){
    $this->db->delete("dokter_display", ["id" => $id]);
    $cek1 = $this->db->query("SELECT * FROM dokter_display WHERE id > $id")->result();
    foreach($cek1 as $c1){
      $this->db->query("UPDATE dokter_display set id = $c1->id - 1 WHERE id = '$c1->id'");
    }
    echo json_encode(["status" => 1]);
  }

  public function noantrian1($kodokter){
    $cabang = $this->session->userdata("unit");
    $now = date("Y-m-d");
    $dokter = $this->db->query("SELECT kodokter, nadokter FROM tbl_dokter WHERE kodokter = '$kodokter'")->row();
    $antrix = $this->db->query("SELECT tbl_regist.rekmed, concat(antrino1, '.', LPAD(antrino, 3, '0'), ' | ', UPPER(namapas)) as noantri FROM tbl_regist JOIN tbl_pasien ON tbl_pasien.rekmed = tbl_regist.rekmed WHERE tglmasuk = '$now' AND tbl_regist.koders = '$cabang' AND kodokter = '$kodokter' AND tbl_regist.rekmed != '' AND cekpanggil < 1 AND noreg NOT IN (SELECT noreg FROM tbl_kasir) LIMIT 5");
    $antri = $antrix->result();
    if($antrix->num_rows() > 0) :
    ?>
      <?php foreach($antri as $a) : ?>
        <?php $pasien = $this->db->query("SELECT * FROM tbl_pasien WHERE rekmed = '$a->rekmed'")->row(); ?>
        <div class="text-primary" style="font-size: 16px; font-weight: bold;"><?= mb_strimwidth($a->noantri, 0, 17, '...'); ?></div>
      <?php endforeach; ?>
    <?php
    else : ?>
      <div class="text-primary" style="font-size: 16px; font-weight: bold;"> - </div>
    <?php endif;
  }

  public function noantrian2($kodokter){
    $cabang = $this->session->userdata("unit");
    $now = date("Y-m-d");
    $dokter = $this->db->query("SELECT kodokter, nadokter FROM tbl_dokter WHERE kodokter = '$kodokter'")->row();
    $antrix = $this->db->query("SELECT tbl_regist.rekmed, concat(antrino1, '.', LPAD(antrino, 3, '0'), ' | ', UPPER(namapas)) as noantri FROM tbl_regist JOIN tbl_pasien ON tbl_pasien.rekmed = tbl_regist.rekmed WHERE tglmasuk = '$now' AND tbl_regist.koders = '$cabang' AND kodokter = '$kodokter' AND tbl_regist.rekmed != '' AND cekpanggil < 1 AND noreg NOT IN (SELECT noreg FROM tbl_kasir) LIMIT 5");
    $antri = $antrix->result();
    if($antrix->num_rows() > 0) :
    ?>
      <?php foreach($antri as $a) : ?>
        <?php $pasien = $this->db->query("SELECT * FROM tbl_pasien WHERE rekmed = '$a->rekmed'")->row(); ?>
        <div style="font-size: 16px; color: #f9cb9c; font-weight: bold;"><?= mb_strimwidth($a->noantri, 0, 17, '...'); ?></div>
      <?php endforeach; ?>
    <?php else : ?>
      <div style="font-size: 16px; color: #f9cb9c; font-weight: bold;"> - </div>
    <?php endif;
  }

  public function noantrian3($kodokter){
    $cabang = $this->session->userdata("unit");
    $now = date("Y-m-d");
    $dokter = $this->db->query("SELECT kodokter, nadokter FROM tbl_dokter WHERE kodokter = '$kodokter'")->row();
    $antrix = $this->db->query("SELECT tbl_regist.rekmed, concat(antrino1, '.', LPAD(antrino, 3, '0'), ' | ', UPPER(namapas)) as noantri FROM tbl_regist JOIN tbl_pasien ON tbl_pasien.rekmed = tbl_regist.rekmed WHERE tglmasuk = '$now' AND tbl_regist.koders = '$cabang' AND kodokter = '$kodokter' AND tbl_regist.rekmed != '' AND cekpanggil < 1 AND noreg NOT IN (SELECT noreg FROM tbl_kasir) LIMIT 5");
    $antri = $antrix->result();
    if($antrix->num_rows() > 0) :
    ?>
      <?php foreach($antri as $a) : ?>
        <?php $pasien = $this->db->query("SELECT * FROM tbl_pasien WHERE rekmed = '$a->rekmed'")->row(); ?>
        <div style="font-size: 16px; color: #198754; font-weight: bold;"><?= mb_strimwidth($a->noantri, 0, 17, '...'); ?></div>
      <?php endforeach; ?>
    <?php else : ?>
      <div style="font-size: 16px; color: #198754; font-weight: bold;"> - </div>
    <?php endif;
  }

  public function noantrian4($kodokter){
    $cabang = $this->session->userdata("unit");
    $now = date("Y-m-d");
    $dokter = $this->db->query("SELECT kodokter, nadokter FROM tbl_dokter WHERE kodokter = '$kodokter'")->row();
    $antrix = $this->db->query("SELECT tbl_regist.rekmed, concat(antrino1, '.', LPAD(antrino, 3, '0'), ' | ', UPPER(namapas)) as noantri FROM tbl_regist JOIN tbl_pasien ON tbl_pasien.rekmed = tbl_regist.rekmed WHERE tglmasuk = '$now' AND tbl_regist.koders = '$cabang' AND kodokter = '$kodokter' AND tbl_regist.rekmed != '' AND cekpanggil < 1 AND noreg NOT IN (SELECT noreg FROM tbl_kasir) LIMIT 5");
    $antri = $antrix->result();
    if($antrix->num_rows() > 0) :
    ?>
      <?php foreach($antri as $a) : ?>
        <?php $pasien = $this->db->query("SELECT * FROM tbl_pasien WHERE rekmed = '$a->rekmed'")->row(); ?>
        <div style="font-size: 16px; color: #6c757d; font-weight: bold;"><?= mb_strimwidth($a->noantri, 0, 17, '...'); ?></div>
      <?php endforeach; ?>
    <?php else : ?>
      <div style="font-size: 16px; color: #6c757d; font-weight: bold;"> - </div>
    <?php endif;
  }

  public function panggil(){
    $id = $this->input->get("id");
    $noreg1 = $this->input->get("noreg1");
    $noreg2 = $this->input->get("noreg2");
    $noreg3 = $this->input->get("noreg3");
    $noreg4 = $this->input->get("noreg4");
    $sqlx = $this->db->query("SELECT * FROM tbl_regist WHERE (noreg = '$noreg1' OR noreg = '$noreg2' OR noreg = '$noreg3' OR noreg = '$noreg4') AND panggil = 1 AND cekpanggil = 1 AND id_display = '$id' ORDER BY lastno DESC LIMIT 1");
    if($sqlx->num_rows() > 0){
      $sql = $sqlx->row();
      $sebut = trim($this->M_global->penyebut($sql->antrino));
      $data = [
        'id_display' => $sql->id_display,
        'panggil' => $sql->panggil,
        'kodepos' => $sql->kodepos,
        'antrino1' => strtolower($sql->antrino1),
        'antrino' => $sql->antrino,
        'sebut' => $sebut,
        'status' => 1,
        'noreg' => $sql->noreg,
      ];
      echo json_encode($data);
    } else {
      echo json_encode(["status" => 0]);
    }
  }

  public function matikan($noreg){
    $this->db->query("UPDATE tbl_regist SET panggil = 0 WHERE noreg = '$noreg'");
    $sql = $this->db->query("SELECT * FROM tbl_regist WHERE noreg = '$noreg'")->row();
    $data = [
      'panggil' => $sql->panggil,
      'kodepos' => $sql->kodepos,
      'antrino1' => $sql->antrino1,
      'antrino' => $sql->antrino,
      'status' => 0,
      'noreg' => $sql->noreg,
    ];
    echo json_encode($data);
  }

  public function index(){
    setlocale(LC_TIME, 'id_ID.utf8');
    $hariIni = new DateTime();
    $now = date("Y-m-d");
    $cabang = $this->session->userdata('unit');
    $display = $this->input->get("id");
    if($display != '') {
      $dokter = $this->db->query("SELECT * FROM dokter_display WHERE id = '$display' LIMIT 1")->result();
    } else {
      $dokter = $this->db->query("SELECT * FROM dokter_display ORDER BY id ASC LIMIT 1")->result();
    }
    $master_display = $this->db->query("SELECT * FROM dokter_display")->result();
    $jum_display = $this->db->query("SELECT * FROM dokter_display")->num_rows();
    $master_dokter = $this->db->query("SELECT (SELECT nadokter FROM tbl_dokter WHERE kodokter = r.kodokter) nadokter, r.* FROM tbl_regist r WHERE r.tglmasuk = '$now' AND r.koders = '$cabang' AND r.keluar = 0 GROUP BY r.kodokter ORDER BY r.kodokter ASC")->result();
    $data = [
      'cabang' => $cabang,
      'tgl' => strftime('%d %B %Y', $hariIni->getTimestamp()),
      'dokter' => $dokter,
      'dkr' => $master_dokter,
      'master_display' => $master_display,
      'jum_display' => $jum_display,
    ];
    $this->load->view("Display", $data);
  }
}
?>