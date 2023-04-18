<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_pareto extends CI_Model {
  public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Jakarta");
		$this->load->database();
	}

  var $table          = 'tbl_apodresep';
	var $column_order   = ['resepid', 'koders', 'kodebarang', 'namabarang', 'satuan', 'SUM(qty) AS qty', 'resepno'];
	var $column_search  = ['resepid', 'koders', 'kodebarang', 'namabarang', 'satuan', 'SUM(qty) AS qty', 'resepno'];
	var $order          = ['tbl_apodresep.resepno' => 'desc'];

  private function _get_datatables_query($jns, $bulan, $tahun, $koders)
	{
		$this->db->select($this->column_order);
		$this->db->from($this->table);
    if($koders == null || $koders == '') {} else {
      $this->db->where("koders", $koders);
    }
    $this->db->group_by("kodebarang");
		$i = 0;
		foreach ($this->column_search as $item) {
			if ($_POST['search']['value']) {
				if ($i === 0) {
					$this->db->group_start();
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}
				if (count($this->column_search) - 1 == $i){
					$this->db->group_end();
        }
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

	function get_datatables($jns, $bulan, $tahun, $koders)
	{
		$this->_get_datatables_query($jns, $bulan, $tahun, $koders);
		if ($_POST['length'] != -1) {
			$this->db->limit($_POST['length'], $this->input->post('start'));
    }
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($jns, $bulan, $tahun, $koders)
	{
		$this->_get_datatables_query($jns, $bulan, $tahun, $koders);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($jns, $bulan, $tahun, $koders)
	{
		$this->db->from($this->table);
		if ($jns != 1) {
      $this->db->where('koders', $koders);
		}
		return $this->db->count_all_results();
	}

  function kode()
  {
    $get = $this->db->query("SELECT kode_pareto AS kode FROM tbl_pareto ORDER BY kode_pareto DESC LIMIT 1");
    if ($get->num_rows() > 0) {
      $row = $get->row();
      $n = (substr($row->kode, 7)) + 1;
      $no = sprintf("%'.04d", $n);
    } else {
      $no = "0001";
    }
    $kode = "PAR" . $this->session->userdata("unit") . "-" . $no;
    return $kode;
  }
}