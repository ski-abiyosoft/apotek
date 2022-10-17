<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_retur_bapb_farmasi extends CI_Model {

  public function __construct() {
    parent::__construct();
    date_default_timezone_set("Asia/Jakarta");
    $this->load->database();
  }
  
  var $table          = 'tbl_baranghreturbeli as h';
  var $column_order   = array('h.id', 'h.retur_no', 'h.retur_date','h.vendor_id','h.terima_no', 'h.koders', null);
  var $column_search  = array('h.id', 'h.retur_no', 'h.retur_date', 'h.vendor_id','h.terima_no', 'h.koders');
  var $order          = array('h.retur_no' => 'asc');

  private function _get_datatables_query($jns, $bulan, $tahun)
  {
    $cabang = $this->session->userdata('unit');
    $this->db->select($this->column_order);
    $this->db->from($this->table);
    $this->db->join('tbl_vendor', 'tbl_vendor.vendor_id=h.vendor_id');
    $this->db->where('h.koders', $cabang);
    if ($jns == 1) {
      $tanggal = date('Y-m-d');
      $this->db->where(['h.retur_date' => $tanggal]);
    } else {
      $this->db->where(['h.retur_date >=' => $bulan, 'retur_date<= ' => $tahun]);
    }
    $i = 0;
    foreach ($this->column_search as $item) {
      if ($_POST['search']['value']) {
        if ($i === 0) {
          $this->db->group_start();
          $this->db->like($item, $_POST['search']['value']);
        } else {
          $this->db->or_like($item, $_POST['search']['value']);
        }
        if (count($this->column_search) - 1 == $i)
          $this->db->group_end();
      }
      $i++;
    }
    if (isset($_POST['order'])) {
      $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->order)) {
      $order = $this->order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  public function get_datatables($jns, $bulan, $tahun) {
    $this->_get_datatables_query($jns, $bulan, $tahun);
    if ($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $this->input->post('start'));
    $query = $this->db->get();
    return $query->result();
  }

  public function count_filtered($jns, $bulan, $tahun) {
    $this->_get_datatables_query($jns, $bulan, $tahun);
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all($jns, $bulan, $tahun) {
    $cabang = $this->session->userdata('unit');
    $this->db->from($this->table);
    $this->db->where('koders', $cabang);
    if ($jns == 1) {
      $this->db->where(['year(retur_date)' => $tahun, 'month(retur_date)' => $bulan]);
    } else {
      $this->db->where(['retur_date >=' => $bulan, 'retur_date<= ' => $tahun]);
    }
    return $this->db->count_all_results();
  }
}
