<?php 
class M_kode extends CI_Model
{
  function kode_ap() {
    $get = $this->db->query("SELECT apocode AS kode FROM tbl_barangsetup WHERE apogroup='ATURANPAKAI' ORDER BY apocode DESC LIMIT 1");
    if ($get->num_rows() > 0) {
      $row = $get->row();
      $n = (substr($row->kode, 2)) + 1;
      $no = sprintf("%'.01d", $n);
    } else {
      $no = "1";
    }
    $kode = "AP" . $no;
    return $kode;
  }
}

?>