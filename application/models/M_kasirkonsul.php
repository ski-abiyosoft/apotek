<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_kasirkonsul extends CI_Model
{

	var $table = 'tbl_kasir';
	var $column_order = array(
		'id', 'nokwitansi', 'koders', 'noreg', 'rekmed', 'tglbayar', 'jambayar', 'jenisbayar',
		'uangmuka', 'dibayaroleh', 'namapasien', 'username', 'totalsemua', 'totalbayar', null
	);
	var $column_search = array(
		'id', 'nokwitansi', 'koders', 'noreg', 'rekmed', 'tglbayar', 'jambayar', 'jenisbayar',
		'uangmuka', 'dibayaroleh', 'namapasien', 'username', 'totalsemua', 'totalbayar'
	);
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($jns, $bulan, $tahun)
	{
		$cabang = $this->session->userdata('unit');
		$this->db->from($this->table);
		$this->db->where('koders', $cabang);
		$this->db->where('posbayar', 'RAWAT_JALAN');

		if ($jns == 1) {
			$tanggal = date('Y-m-d');
			$this->db->where(array('tglbayar' => $tanggal));
		} else {
			$this->db->where(array('tglbayar >=' => $bulan, 'tglbayar<= ' => $tahun));
		}


		$i = 0;


		foreach ($this->column_search as $item) {
			if ($_POST['search']['value']) {

				if ($i === 0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket


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

	function get_datatables($jns,  $bulan, $tahun)
	{
		$this->_get_datatables_query($jns,  $bulan, $tahun);
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($jns,  $bulan, $tahun)
	{
		$this->_get_datatables_query($jns,  $bulan, $tahun);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($jns, $bulan, $tahun)
	{
		$cabang = $this->session->userdata('unit');
		$this->db->from($this->table);
		$this->db->where('koders', $cabang);
		$this->db->where('posbayar', 'RAWAT_JALAN');

		if ($jns == 1) {
			$this->db->where(array('year(tglbayar)' => $tahun, 'month(tglbayar)' => $bulan));
		} else {
			$this->db->where(array('tglbayar >=' => $bulan, 'tglbayar<= ' => $tahun));
		}

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

	public function _dataresep($noreg)
	{
		// $q1=$this->db->query("select resepno as nomor, poscredit as jumlah from tbl_apoposting where noreg = '$noreg'")->result();
		// saya ganti karena ditambah join
		// script original
		// $q1 = $this->db->query("SELECT tbl_apoposting.resepno as nomor, sum(totalrp) as jumlah, sum(discrp) as discrp, poscredit FROM tbl_apoposting JOIN tbl_apodresep ON tbl_apodresep.resepno = tbl_apoposting.resepno WHERE noreg = '$noreg'");
		
		// husain change
		$q1x = $this->db->query("SELECT tbl_apoposting.resepno as nomor, sum(totalrp) as jumlah, sum(discrp) as discrp, poscredit FROM tbl_apoposting JOIN tbl_apodresep ON tbl_apodresep.resepno = tbl_apoposting.resepno WHERE noreg = '$noreg' AND tbl_apoposting.keluar = 0");
		if($q1x->num_rows() < 1){
			$q1 = $q1x->result();
		} else {
			$q1 = $this->db->query("SELECT tbl_apoposting.resepno as nomor, sum(totalrp) as jumlah, sum(discrp) as discrp, poscredit FROM tbl_apoposting JOIN tbl_apodresep ON tbl_apodresep.resepno = tbl_apoposting.resepno WHERE noreg = '$noreg'")->result();
		}
		// end husain
		if ($q1) {
			$rowcount = 1;
			foreach ($q1 as $res1) {
				$info['item_nomor']   = $res1->nomor;
				// $info['item_jumlah']  = $res1->jumlah + $res1->discrp;
				$info['item_jumlah']  = $res1->poscredit;
				$info['item_disc']	  = $res1->discrp;
				// $info['item_disc']	  = $res1->poscredit;
				$result = $this->return_row_with_data($rowcount++, $info);
			}
			return $result;
		} else  return "";
	}

	// public function _datadp($rekmed){
	// 	$q1=$this->db->query("
	// 	select * from tbl_kasir where posbayar='UANG_MUKA' and uangmuka-umuka>0 and
	//     rekmed='$rekmed'")->result();
	// 	if($q1){
	// 	$rowcount =1;
	// 	foreach ($q1 as $res1) {			
	// 		$info['item_nomor']   = $res1->nokwitansi;
	// 		$info['item_tanggal'] = tanggal($res1->tglbayar);
	// 		$info['item_jumlah']  = $res1->uangmuka;
	// 		$info['item_pakai']   = $res1->umuka;
	// 		$info['item_sisa']    = $res1->uangmuka-$res1->umuka;
	// 		$result = $this->return_row_with_data_dp($rowcount++,$info);
	// 	}
	// 	return $result;
	// 	} else  return "";
	// }

	public function _datadp($rekmed)
	{
		$cabang = $this->session->userdata('unit');
		$q1 = $this->db->query("
		select * from tbl_uangmuka where posbayar='UANG_MUKA' and closed = 0 and
        rekmed='$rekmed' and koders = '$cabang'")->result();
		if ($q1) {
			$rowcount = 1;
			foreach ($q1 as $res1) {
				$info['item_nomor']   = $res1->nokwitansi;
				$info['item_tanggal'] = tanggal($res1->tglbayar);
				$info['item_jumlah']  = $res1->jmbayar;
				$info['item_pakai']   = 0;
				$info['item_sisa']    = $res1->jmbayar;
				$result = $this->return_row_with_data_dp($rowcount++, $info);
			}
			return $result;
		} else  return "";
	}

	public function return_row_with_data_dp($rowcount, $info)
	{
		extract($info);

?>
		<tr id="row_<?= $rowcount; ?>" data-row='<?= $rowcount; ?>'>
			<td id="td_<?= $rowcount; ?>_0">
				<!-- item name  -->
				<input type="checkbox" checked style="font-weight: bold-;" name="td_data_<?= $rowcount; ?>_0" id="td_data_<?= $rowcount; ?>_0" class="form-control no-padding" onclick="total_bayar();" value=''>
			</td>
			<td id="td_<?= $rowcount; ?>_1">
				<!-- item name  -->
				<input type="text" style="font-weight: bold-;" name="dp_td_data1[]" id="td_data_<?= $rowcount; ?>_1" class="form-control no-padding" value='<?= $item_nomor; ?>' readonly>
			</td>
			<td id="td_<?= $rowcount; ?>_2">
				<!-- item name  -->
				<input type="text" style="font-weight: bold-;" name="dp_td_data2[]" id="td_data_<?= $rowcount; ?>_2" class="form-control no-padding" value='<?= $item_tanggal; ?>' readonly>
			</td>

			<td id="td_<?= $rowcount; ?>_3">
				<!-- item name  -->
				<input type="text" style="font-weight: bold-;text-align:right" name="dp_td_data3[]" id="td_data_<?= $rowcount; ?>_3" class="form-control no-padding" value='<?= number_format($item_jumlah, 0, '.', ','); ?>' readonly>
			</td>

			<td id="td_<?= $rowcount; ?>_4">
				<!-- item name  -->
				<input type="text" style="font-weight: bold-;text-align:right" name="dp_td_data4[]" id="td_data_<?= $rowcount; ?>_4" class="form-control no-padding" value='<?= number_format($item_pakai, 0, '.', ','); ?>' readonly>
			</td>

			<td id="td_<?= $rowcount; ?>_5">
				<!-- item name  -->
				<input type="text" style="font-weight: bold-;text-align:right" name="dp_td_data5[]" id="td_data_<?= $rowcount; ?>_5" class="form-control no-padding" value='<?= number_format($item_sisa, 0, '.', ','); ?>'>
			</td>

		</tr>
	<?php

	}


	public function return_row_with_data($rowcount, $info)
	{
		extract($info);

	?>
		<tr id="row_<?= $rowcount; ?>" data-row='<?= $rowcount; ?>'>
			<td id="td_<?= $rowcount; ?>_1">
				<!-- item name  -->
				<input type="text" style="font-weight: bold-;" name="td_data_<?= $rowcount; ?>_1" id="td_data_<?= $rowcount; ?>_1" class="form-control no-padding" value='<?= $item_nomor; ?>' readonly>
			</td>
			<td id="td_<?= $rowcount; ?>_2">
				<!-- item name  -->
				<input type="text" style="font-weight: bold-;text-align:right" name="td_data_<?= $rowcount; ?>_2" id="td_data_<?= $rowcount; ?>_2" class="form-control no-padding" value='<?= number_format($item_jumlah, 0, '.', ','); ?>' readonly>
				<input type="hidden" style="font-weight: bold-;text-align:right" name="td_data_<?= $rowcount; ?>_3" id="td_data_<?= $rowcount; ?>_3" class="form-control no-padding" value='<?= number_format($item_disc, 0, '.', ','); ?>' readonly>

			</td>

		</tr>
<?php

	}
}
