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

  function set_dokter($id){
    $nama_display = $this->input->get("nama");
    $kodokter1 = $this->input->get('kodokter1');
    $kodokter2 = $this->input->get('kodokter2');
    $kodokter3 = $this->input->get('kodokter3');
    $kodokter4 = $this->input->get('kodokter4');
    $where = [
      'id' => $id,
    ];
    $cek_id = $this->db->get_where("dokter_display", $where);
    if($cek_id->num_rows() > 0){
      $dd = $cek_id->row();
      $id_baru = (int)$dd->id + 1;
    } else {
      $id_baru = $id;
    }
    $data_dokterx = [
      'nama_display' => $nama_display,
      'kodokter1' => $kodokter1,
      'kodokter2' => $kodokter2,
      'kodokter3' => $kodokter3,
      'kodokter4' => $kodokter4,
    ];
    $data_dokter = [
      'id' => $id_baru,
      'nama_display' => $nama_display,
      'kodokter1' => $kodokter1,
      'kodokter2' => $kodokter2,
      'kodokter3' => $kodokter3,
      'kodokter4' => $kodokter4,
    ];
    $cek = $this->db->get_where("dokter_display", ["id" => $id]);
    if($cek->num_rows() > 0){
      $this->db->update("dokter_display", $data_dokterx, ["id" => $id]);
    } else {
      $this->db->insert("dokter_display", $data_dokter);
    }
    $data_cek = $this->db->get_where("dokter_display", $where)->row();
    $dokter1 = $this->db->query("SELECT kodokter, nadokter FROM tbl_dokter WHERE kodokter = '$data_cek->kodokter1'")->row();
    $dokter2 = $this->db->query("SELECT kodokter, nadokter FROM tbl_dokter WHERE kodokter = '$data_cek->kodokter2'")->row();
    $dokter3 = $this->db->query("SELECT kodokter, nadokter FROM tbl_dokter WHERE kodokter = '$data_cek->kodokter3'")->row();
    $dokter4 = $this->db->query("SELECT kodokter, nadokter FROM tbl_dokter WHERE kodokter = '$data_cek->kodokter4'")->row();
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

  function hapusbaris($id){
    $this->db->delete("dokter_display", ["id" => $id]);
  }

  function noantrian1($kodokter){
    $cabang = $this->session->userdata("unit");
    $dokter = $this->db->query("SELECT kodokter, nadokter FROM tbl_dokter WHERE kodokter = '$kodokter'")->row();
    $antrix = $this->db->query("SELECT tbl_regist.rekmed, concat(antrino1, '.', antrino, ' | ', namapas) as noantri FROM tbl_regist JOIN tbl_pasien ON tbl_pasien.rekmed = tbl_regist.rekmed WHERE tglmasuk = '2022-11-01' AND tbl_regist.koders = '$cabang' AND kodokter = '$kodokter' AND tbl_regist.rekmed != '' AND cekpanggil < 1 LIMIT 5");
    $antri = $antrix->result();
    if($antrix->num_rows() > 0) :
    ?>
    <!-- <marquee behavior="" direction="up" style="color: black;"> -->
    <?php foreach($antri as $a) : ?>
      <?php $pasien = $this->db->query("SELECT * FROM tbl_pasien WHERE rekmed = '$a->rekmed'")->row(); ?>
      <div style="font-size: 16px; color: white; font-weight: bold;"><?= mb_strimwidth($a->noantri, 0, 18, '...'); ?></div>
    <?php endforeach; ?>
    <!-- </marquee> -->
    <?php
    else : ?>
      <div style="font-size: 16px; color: white; font-weight: bold;"> - </div>
    <?php endif;
  }

  function noantrian2($kodokter){
    $cabang = $this->session->userdata("unit");
    $dokter = $this->db->query("SELECT kodokter, nadokter FROM tbl_dokter WHERE kodokter = '$kodokter'")->row();
    $antrix = $this->db->query("SELECT tbl_regist.rekmed, concat(antrino1, '.', antrino, ' | ', namapas) as noantri FROM tbl_regist JOIN tbl_pasien ON tbl_pasien.rekmed = tbl_regist.rekmed WHERE tglmasuk = '2022-11-01' AND tbl_regist.koders = '$cabang' AND kodokter = '$kodokter' AND tbl_regist.rekmed != '' AND cekpanggil < 1 LIMIT 5");
    $antri = $antrix->result();
    if($antrix->num_rows() > 0) :
    ?>
    <!-- <marquee behavior="" direction="up" style="color: black;"> -->
      <?php foreach($antri as $a) : ?>
        <?php $pasien = $this->db->query("SELECT * FROM tbl_pasien WHERE rekmed = '$a->rekmed'")->row(); ?>
        <div style="font-size: 16px; color: white; font-weight: bold;"><?= mb_strimwidth($a->noantri, 0, 18, '...'); ?></div>
      <?php endforeach; ?>
    <!-- </marquee> -->
    <?php else : ?>
      <div style="font-size: 16px; color: white; font-weight: bold;"> - </div>
    <?php endif;
  }

  function noantrian3($kodokter){
    $cabang = $this->session->userdata("unit");
    $dokter = $this->db->query("SELECT kodokter, nadokter FROM tbl_dokter WHERE kodokter = '$kodokter'")->row();
    $antrix = $this->db->query("SELECT tbl_regist.rekmed, concat(antrino1, '.', antrino, ' | ', namapas) as noantri FROM tbl_regist JOIN tbl_pasien ON tbl_pasien.rekmed = tbl_regist.rekmed WHERE tglmasuk = '2022-11-01' AND tbl_regist.koders = '$cabang' AND kodokter = '$kodokter' AND tbl_regist.rekmed != '' AND cekpanggil < 1 LIMIT 5");
    $antri = $antrix->result();
    if($antrix->num_rows() > 0) :
    ?>
    <!-- <marquee behavior="" direction="up" style="color: black;"> -->
      <?php foreach($antri as $a) : ?>
        <?php $pasien = $this->db->query("SELECT * FROM tbl_pasien WHERE rekmed = '$a->rekmed'")->row(); ?>
        <div style="font-size: 16px; color: white; font-weight: bold;"><?= mb_strimwidth($a->noantri, 0, 18, '...'); ?></div>
      <?php endforeach; ?>
    <!-- </marquee> -->
    <?php else : ?>
      <div style="font-size: 16px; color: white; font-weight: bold;"> - </div>
    <?php endif;
  }

  function noantrian4($kodokter){
    $cabang = $this->session->userdata("unit");
    $dokter = $this->db->query("SELECT kodokter, nadokter FROM tbl_dokter WHERE kodokter = '$kodokter'")->row();
    $antrix = $this->db->query("SELECT tbl_regist.rekmed, concat(antrino1, '.', antrino, ' | ', namapas) as noantri FROM tbl_regist JOIN tbl_pasien ON tbl_pasien.rekmed = tbl_regist.rekmed WHERE tglmasuk = '2022-11-01' AND tbl_regist.koders = '$cabang' AND kodokter = '$kodokter' AND tbl_regist.rekmed != '' AND cekpanggil < 1 LIMIT 5");
    $antri = $antrix->result();
    if($antrix->num_rows() > 0) :
    ?>
    <!-- <marquee behavior="" direction="up" style="color: black;"> -->
      <?php foreach($antri as $a) : ?>
        <?php $pasien = $this->db->query("SELECT * FROM tbl_pasien WHERE rekmed = '$a->rekmed'")->row(); ?>
        <div style="font-size: 16px; color: white; font-weight: bold;"><?= mb_strimwidth($a->noantri, 0, 18, '...'); ?></div>
      <?php endforeach; ?>
    <!-- </marquee> -->
    <?php else : ?>
      <div style="font-size: 16px; color: white; font-weight: bold;"> - </div>
    <?php endif;
  }

  function index(){
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
    $master_dokter = $this->db->query("SELECT (SELECT nadokter FROM tbl_dokter WHERE kodokter = r.kodokter) nadokter, r.* FROM tbl_regist r WHERE r.tglmasuk = '2022-11-01' AND r.koders = '$cabang' AND r.keluar = 0 GROUP BY r.kodokter ORDER BY r.kodokter ASC")->result();
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