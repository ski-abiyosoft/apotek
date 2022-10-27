<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_stockopname extends CI_Model
{

	var $table = 'tbl_aposesuai';
	var $column_order = array('koders', 'kodebarang', 'gudang', 'tglso', 'saldo', 'hasilso', 'sesuai', 'yangubah', 'approve', 'username', 'tglentry', 'jamentry', null);
	var $column_search = array('nm','koders', 'kodebarang', 'gudang', 'tglso', 'saldo', 'hasilso', 'sesuai', 'yangubah', 'approve', 'username', 'tglentry', 'jamentry');
	var $order = array('kodebarang' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('M_barang');
		$this->load->model('M_param');
	}

	public function validate($uid,  $password, $cabang = null)
	{
		$this->db->from('userlogin');
		$this->db->where('uidlogin', $uid);
		$this->db->where('pwdlogin', md5($password));
		$this->db->where('locked', 0);
		$login = $this->db->get()->result();
		// var_dump($login);die;
		if (is_array($login) && count($login) == 1) {
			// $this->details = $login[ 0 ];
			// $this->set_session($cabang);
			return true;
		}
		return false;
	}

	public function validatePassword($password)
	{
		$this->db->from('userlogin');
		// $this->db->where( 'uidlogin', $uid );
		$this->db->where('pwdlogin', md5($password));
		$login = $this->db->get();
		return $login;
	}
	private function _get_datatables_query($cabang, $gudang)
	{
		$cabang = $this->session->userdata("unit");
		$dt = $this->db->from('(SELECT (SELECT tbl_barang.namabarang from tbl_barang where tbl_barang.kodebarang=tbl_aposesuai.kodebarang)nm, tbl_aposesuai.* 
		FROM tbl_aposesuai)tbl_aposesuai');
		// $saldo = $this->db->query("SELECT saldo FROM tbl_aposesuai WHERE koders='$cabang' AND gudang ='$gudang'");
		// echo json_encode($saldo);
		if ($gudang != "") {
			$this->db->where('gudang', $gudang);
		}

		$this->db->order_by('id', 'desc');

		$i = 0;


		foreach ($this->column_search as $item) {
			if ($_POST['search']['value']) {

				if ($i === 0) {

					$this->db->like($item, $_POST['search']['value']);
					$this->db->where('koders', $cabang);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
					$this->db->where('koders', $cabang);
				}
			}
			$i++;
		}
		$this->db->where('koders', $cabang);

		if (isset($_POST['order'])) {
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order)) {
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables($cabang, $gudang)
	{
		$this->_get_datatables_query($cabang, $gudang);
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($cabang, $gudang)
	{
		$this->_get_datatables_query($cabang, $gudang);
		$query = $this->db->get();
		return $query->nuM_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('id', $id);
		$query = $this->db->get();

		return $query->row();
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

	public function get_by_tglberlaku($tanggal)
	{
		$this->db->from($this->table);
		$this->db->where('tglberlaku', $tanggal);
		$query = $this->db->get();
		return $query->result();
	}
}
