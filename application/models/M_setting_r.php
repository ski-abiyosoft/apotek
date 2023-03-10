<?php

use LDAP\Result;

defined('BASEPATH') or exit('No direct script access allowed');

class M_setting_r extends CI_Model
{

	var $table = 'tbl_tarifh';
	var $column_order = array('id', 'kodetarif', 'tindakan', 'kodepos', null);
	var $column_search = array('id', 'kodetarif', 'tindakan', 'kodepos');
	var $order = array('id' => 'asc');

	var $tablebhp = 'tbl_masterbhp';
	var $tabletarif = 'tbl_tarif';

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$this->db->from($this->table);

		$i = 0;

		foreach ($this->column_search as $item) {
			if ($_POST['search']['value']) {

				if ($i === 0) {

					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}
			}
			$i++;
		}

		if (isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order)) {
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->select('tbl_tarifh.*, tbl_accounting.acname')->from($this->table)
		->join('tbl_accounting', 'tbl_accounting.accountno = tbl_tarifh.accountno')->where('tbl_tarifh.id', $id);
		$result = $this->db->get()->row();

		$result->detail_tarif = $this->db->select('id, kodetarif, koders, cust_id, tarifrspoli, tarifdrpoli, feemedispoli, bhppoli, cost')->from($this->tabletarif)->where('kodetarif', $result->kodetarif)->get()->result();

		$result->bhp = $this->db->select('tbl_masterbhp.id, kodetarif, kodeobat, qty, satuan, harga, totalharga, tbl_barang.namabarang')->from($this->tablebhp)->join('tbl_barang', 'tbl_barang.kodebarang=tbl_masterbhp.kodeobat')->where('kodetarif', $result->kodetarif)->get()->result();

		return $result;
	}

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where('id', $id);
		$this->db->delete($this->table);
	}
}
