<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_KartuStock extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	public function tampildata($cabang, $barang, $gudang, $tgl1, $tgl2, $tglx)
	{

		// $barang  = $this->input->get('kodebarang');
		// $gudang  = $this->input->get('gudang');
		// $cabang  = $this->input->get('cabang');
		// $tgl1    = $this->input->get('tanggal1');
		// $tgl2    = $this->input->get('tanggal2');
		$_tgl1 = date('Y-m-d', strtotime($tgl1));
		$_tgl2 = date('Y-m-d', strtotime($tgl2));
		$_peri = 'Dari ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2));
		$_peri1 = 'Per Tgl. ' . date('d', strtotime($tgl2)) . ' ' . $this->M_global->_namabulan(date('n', strtotime($tgl2))) . ' ' . date('Y', strtotime($tgl2));
		$mutasi =
			// "SELECT * from 
			//  (select  
			//  tbl_apohresep.tglresep as tanggal,
			//  tbl_apohresep.koders,
			//  tbl_apohresep.resepno as nomor,
			//  tbl_apohresep.gudang as gudang,
			//  tbl_apodresep.kodebarang,
			//  0 as terima,
			//  tbl_apodresep.qty as keluar,
			//  tbl_apodresep.qty as qty,
			//  tbl_apodresep.hpp as harga,
			//  tbl_apohresep.rekmed as rekanan,
			//  'PENJUALAN' as keterangan
			//  from tbl_apohresep inner join
			//  tbl_apodresep on tbl_apohresep.resepno=tbl_apodresep.resepno

			//  union all

			//  select 
			//  tbl_baranghreturbeli.retur_date as tanggal,
			//  tbl_baranghreturbeli.koders,
			//  tbl_baranghreturbeli.retur_no as nomor,
			//  tbl_baranghreturbeli.gudang as gudang,
			//  tbl_barangdreturbeli.kodebarang,
			//  0 as terima,
			//  tbl_barangdreturbeli.qty_retur as keluar,
			//  tbl_barangdreturbeli.qty_retur as qty,
			//  tbl_barangdreturbeli.price as harga,
			//  tbl_baranghreturbeli.vendor_id as rekanan,
			//  'RETUR PEMBELIAN' as keterangan
			//  from tbl_baranghreturbeli inner join
			//  tbl_barangdreturbeli on tbl_baranghreturbeli.retur_no=tbl_barangdreturbeli.retur_no

			//  union all

			//  select 
			//  tbl_apohmove.movedate as tanggal,
			//  tbl_apohmove.koders,
			//  tbl_apohmove.moveno as nomor,
			//  tbl_apohmove.dari as gudang,
			//  tbl_apodmove.kodebarang,
			//  0 as terima,
			//  tbl_apodmove.qtymove as keluar,				
			//  tbl_apodmove.qtymove as qty,
			//  tbl_apodmove.harga as harga,
			//  tbl_apohmove.mohonno as rekanan,
			//  'MUTASI' as keterangan
			//  from tbl_apohmove inner join
			//  tbl_apodmove on tbl_apohmove.moveno=tbl_apodmove.moveno

			//  union all

			//  select 
			//  tbl_apohproduksi.tglproduksi as tanggal,
			//  tbl_apohproduksi.koders,
			//  tbl_apohproduksi.prdno as nomor,
			//  tbl_apohproduksi.gudang as gudang,
			//  tbl_apodproduksi.kodebarang,
			//  0 as terima,
			//  tbl_apodproduksi.qty as keluar,
			//  tbl_apodproduksi.qty as qty,
			//  tbl_apodproduksi.harga as harga,
			//  tbl_apohproduksi.gudang as rekanan,
			//  'PRODUKSI' as keterangan
			//  from tbl_apohproduksi inner join tbl_apodproduksi
			//  on tbl_apohproduksi.prdno=tbl_apodproduksi.prdno

			//  union all

			//  select 
			//  tbl_apohex.tgl_ed as tanggal,
			//  tbl_apohex.koders,
			//  tbl_apohex.ed_no as nomor,
			//  tbl_apohex.gudang as gudang,
			//  tbl_apodex.kodebarang,
			//  0 as terima,
			//  tbl_apodex.qty as keluar,
			//  tbl_apodex.qty as qty,
			//  tbl_apodex.hpp as harga,
			//  tbl_apohex.keterangan as rekanan,
			//  'BARANG EXPIRE' as keterangan
			//  from tbl_apohex inner join
			//  tbl_apodex on tbl_apohex.ed_no=tbl_apodex.ed_no

			//  union all

			//  select
			//  tbl_baranghterima.terima_date as tanggal,
			//  tbl_baranghterima.koders,
			//  tbl_baranghterima.terima_no as nomor,
			//  tbl_baranghterima.gudang,
			//  tbl_barangdterima.kodebarang,
			//  tbl_barangdterima.qty_terima as terima,
			//  0 keluar,
			//  tbl_barangdterima.qty_terima as qty,
			//  tbl_barangdterima.price as harga,
			//  tbl_vendor.vendor_name as rekanan,
			//  'PEMBELIAN' as keterangan
			//  from tbl_baranghterima inner join
			//  tbl_barangdterima on tbl_baranghterima.terima_no=tbl_barangdterima.terima_no
			//  inner join tbl_vendor on tbl_baranghterima.vendor_id=tbl_vendor.vendor_id

			//  union all

			//  select 
			//  tbl_apohmove.movedate as tanggal,
			//  tbl_apohmove.koders,
			//  tbl_apohmove.moveno as nomor,
			//  tbl_apohmove.ke as gudang,
			//  tbl_apodmove.kodebarang,
			//  tbl_apodmove.qtymove as terima,
			//  0 as keluar,
			//  tbl_apodmove.qtymove as qty,
			//  tbl_apodmove.harga as harga,
			//  tbl_apohmove.mohonno as rekanan,
			//  'MUTASI' as keterangan
			//  from tbl_apohmove inner join
			//  tbl_apodmove on tbl_apohmove.moveno=tbl_apodmove.moveno

			//  union all

			//  select 
			//  tbl_apohreturjual.tglretur as tanggal,
			//  tbl_apohreturjual.koders,
			//  tbl_apohreturjual.returno as nomor,
			//  tbl_apohreturjual.gudang as gudang,
			//  tbl_apodreturjual.kodebarang,
			//  tbl_apodreturjual.qtyretur as terima,
			//  0 as keluar,
			//  tbl_apodreturjual.qtyretur as qty,
			//  tbl_apodreturjual.price as harga,
			//  tbl_apohreturjual.rekmed as rekanan,
			//  'RETUR JUAL' as keterangan
			//  from tbl_apohreturjual inner join
			//  tbl_apodreturjual on tbl_apohreturjual.returno=tbl_apodreturjual.returno

			//  union all

			//  select 
			//  tbl_apohproduksi.tglproduksi as tanggal,
			//  tbl_apohproduksi.koders,
			//  tbl_apohproduksi.prdno as nomor,
			//  tbl_apohproduksi.gudang as gudang,
			//  tbl_apohproduksi.kodebarang,
			//  tbl_apohproduksi.qtyjadi as terima,
			//  0 as keluar,				
			//  tbl_apohproduksi.qtyjadi as qty,
			//  tbl_apohproduksi.hpp as harga,
			//  tbl_apohproduksi.gudang as rekanan,
			//  'PRODUKSI' as keterangan
			//  from tbl_apohproduksi

			//  ) mutasi
			//  where kodebarang = '$barang' and
			//  koders = '$cabang' and
			// (gudang = '$gudang' or gudang = 'LOKAL') and 
			// tanggal BETWEEN '$_tgl1' AND '$_tgl2'
			// ORDER BY tanggal, keterangan ASC";

			"SELECT * from 
			    (
				
				select
				tbl_baranghterima.terima_date as tanggal,
				tbl_baranghterima.koders,
				tbl_baranghterima.terima_no as nomor,
				tbl_baranghterima.gudang,
				tbl_barangdterima.kodebarang,
				tbl_barangdterima.qty_terima as terima,
				0 keluar,
				tbl_barangdterima.qty_terima as qty,
				tbl_barangdterima.price as harga,
				tbl_vendor.vendor_name as rekanan,
				'PEMBELIAN' as keterangan
				from tbl_baranghterima inner join
				tbl_barangdterima on tbl_baranghterima.terima_no=tbl_barangdterima.terima_no
				inner join tbl_vendor on tbl_baranghterima.vendor_id=tbl_vendor.vendor_id
				-- WHERE kodebarang = '$barang' AND tbl_baranghterima.koders = '$cabang'

				union all
				
				select 
				tbl_baranghreturbeli.retur_date as tanggal,
				tbl_baranghreturbeli.koders,
				tbl_baranghreturbeli.retur_no as nomor,
				tbl_baranghreturbeli.gudang as gudang,
				tbl_barangdreturbeli.kodebarang,
				0 as terima,
				tbl_barangdreturbeli.qty_retur as keluar,
				tbl_barangdreturbeli.qty_retur as qty,
				tbl_barangdreturbeli.price as harga,
				tbl_baranghreturbeli.vendor_id as rekanan,
				'RETUR PEMBELIAN' as keterangan
				from tbl_baranghreturbeli inner join
				tbl_barangdreturbeli on tbl_baranghreturbeli.retur_no=tbl_barangdreturbeli.retur_no
				-- WHERE kodebarang = '$barang' AND tbl_baranghreturbeli.koders = '$cabang'

				union all
				 
				select 
				tbl_apohresep.tglresep as tanggal,
				tbl_apohresep.koders,
				tbl_apohresep.resepno as nomor,
				tbl_apohresep.gudang as gudang,
				tbl_apodresep.kodebarang,
				0 as terima,
				tbl_apodresep.qty as keluar,
				tbl_apodresep.qty as qty,
				tbl_apodresep.hpp as harga,
				(select namapas from tbl_pasien where rekmed = tbl_apohresep.rekmed) as rekanan,
				'PENJUALAN' as keterangan
				from tbl_apohresep join
				tbl_apodresep on tbl_apohresep.resepno=tbl_apodresep.resepno
				-- WHERE kodebarang = '$barang' AND tbl_apohresep.koders = '$cabang'

				-- new union racikan
				union all

				select
				tbl_apodetresep.exp_date as tanggal,
				tbl_apodetresep.koders,
				tbl_apodetresep.resepno as nomor,
				'' as gudang,
				tbl_apodetresep.kodebarang,
				0 as terima,
				tbl_apodetresep.qtyr as keluar,
				tbl_apodetresep.qty as qty,
				tbl_apodetresep.price as harga,
				'' as rekanan,
				'RACIKAN' as keterangan
				from tbl_apodetresep
				-- WHERE kodebarang = '$barang' AND koders = '$cabang'

				
				union all
				
				select 
				tbl_apohreturjual.tglretur as tanggal,
				tbl_apohreturjual.koders,
				tbl_apohreturjual.returno as nomor,
				tbl_apohreturjual.gudang as gudang,
				tbl_apodreturjual.kodebarang,
				tbl_apodreturjual.qtyretur as terima,
				0 as keluar,
				tbl_apodreturjual.qtyretur as qty,
				tbl_apodreturjual.price as harga,
				tbl_apohreturjual.rekmed as rekanan,
				'RETUR JUAL' as keterangan
				from tbl_apohreturjual inner join
				tbl_apodreturjual on tbl_apohreturjual.returno=tbl_apodreturjual.returno
				-- WHERE kodebarang = '$barang' AND tbl_apohreturjual.koders = '$cabang'

				union all
				
				select 
				tbl_apohmove.movedate as tanggal,
				tbl_apohmove.koders,
				tbl_apohmove.moveno as nomor,
				tbl_apohmove.dari as gudang,
				tbl_apodmove.kodebarang,
				0 as terima,
				tbl_apodmove.qtymove as keluar,				
				tbl_apodmove.qtymove as qty,
				tbl_apodmove.harga as harga,
				tbl_apohmove.mohonno as rekanan,
				'MUTASI' as keterangan
				from tbl_apohmove inner join
				tbl_apodmove on tbl_apohmove.moveno=tbl_apodmove.moveno
				-- WHERE kodebarang = '$barang' AND tbl_apohmove.koders = '$cabang'

				union all
				
				select 
				tbl_apohmove.movedate as tanggal,
				tbl_apohmove.koders,
				tbl_apohmove.moveno as nomor,
				tbl_apohmove.ke as gudang,
				tbl_apodmove.kodebarang,
				tbl_apodmove.qtymove as terima,
				0 as keluar,
				tbl_apodmove.qtymove as qty,
				tbl_apodmove.harga as harga,
				tbl_apohmove.mohonno as rekanan,
				'MUTASI' as keterangan
				from tbl_apohmove inner join
				tbl_apodmove on tbl_apohmove.moveno=tbl_apodmove.moveno
				
				union all
				
				select 
				tbl_apohproduksi.tglproduksi as tanggal,
				tbl_apohproduksi.koders,
				tbl_apohproduksi.prdno as nomor,
				tbl_apohproduksi.gudang as gudang,
				tbl_apodproduksi.kodebarang,
				0 as terima,
				tbl_apodproduksi.qty as keluar,
				tbl_apodproduksi.qty as qty,
				tbl_apodproduksi.harga as harga,
				tbl_apohproduksi.gudang as rekanan,
				'PRODUKSI' as keterangan
				from tbl_apohproduksi inner join tbl_apodproduksi
				on tbl_apohproduksi.prdno=tbl_apodproduksi.prdno
				-- WHERE tbl_apodproduksi.kodebarang = '$barang' AND tbl_apodproduksi.koders = '$cabang'

				union all
				
				select 
				tbl_apohproduksi.tglproduksi as tanggal,
				tbl_apohproduksi.koders,
				tbl_apohproduksi.prdno as nomor,
				tbl_apohproduksi.gudang as gudang,
				tbl_apohproduksi.kodebarang,
				tbl_apohproduksi.qtyjadi as terima,
				0 as keluar,				
				tbl_apohproduksi.qtyjadi as qty,
				tbl_apohproduksi.hpp as harga,
				tbl_apohproduksi.gudang as rekanan,
				'PRODUKSI' as keterangan
				from tbl_apohproduksi
				-- WHERE kodebarang = '$barang' AND koders = '$cabang'
				
				union all

				select 
				tbl_apohex.tgl_ed as tanggal,
				tbl_apohex.koders,
				tbl_apohex.ed_no as nomor,
				tbl_apohex.gudang as gudang,
				tbl_apodex.kodebarang,
				0 as terima,
				tbl_apodex.qty as keluar,
				tbl_apodex.qty as qty,
				tbl_apodex.hpp as harga,
				tbl_apohex.keterangan as rekanan,
				'BARANG EXPIRE' as keterangan
				from tbl_apohex inner join
				tbl_apodex on tbl_apohex.ed_no=tbl_apodex.ed_no
				-- WHERE kodebarang = '$barang' AND tbl_apohex.koders = '$cabang'
				
	
				) mutasi
			
				where 
				kodebarang ='$barang' and koders ='$cabang' and
				gudang = '$gudang' and  
				tanggal = '$tglx' ORDER BY tanggal asc";


		return $this->db->query($mutasi)->result();
		// var_dump($lap);


	}
	public function tgl($cabang, $barang, $gudang, $tgl1, $tgl2, $jam, $_tanggalawal)
	{
		$_tgl1 = date('Y-m-d', strtotime($tgl1));
		$_tgl2 = date('Y-m-d', strtotime($tgl2));
		$_peri = 'Dari ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2));
		$_peri1 = 'Per Tgl. ' . date('d', strtotime($tgl2)) . ' ' . $this->M_global->_namabulan(date('n', strtotime($tgl2))) . ' ' . date('Y', strtotime($tgl2));
		$mutasi =
			"SELECT * FROM(
				select
				tbl_baranghterima.terima_date as tanggal,
				tbl_baranghterima.koders,
				tbl_baranghterima.terima_no as nomor,
				tbl_baranghterima.gudang,
				tbl_barangdterima.kodebarang,
				tbl_barangdterima.qty_terima as terima,
				0 keluar,
				tbl_barangdterima.qty_terima as qty,
				tbl_barangdterima.price as harga,
				tbl_baranghterima.jamterima as jam,
				tbl_vendor.vendor_name as rekanan,
				'PEMBELIAN' as keterangan,
				(Select hpp from tbl_barang where kodebarang=tbl_barangdterima.kodebarang) as hpp,
				(tbl_barangdterima.qty_terima*(Select hpp from tbl_barang where kodebarang=tbl_barangdterima.kodebarang)) as totalhpp
				from tbl_baranghterima inner join
				tbl_barangdterima on tbl_baranghterima.terima_no=tbl_barangdterima.terima_no
				inner join tbl_vendor on tbl_baranghterima.vendor_id=tbl_vendor.vendor_id

				union all
				
				select 
				tbl_baranghreturbeli.retur_date as tanggal,
				tbl_baranghreturbeli.koders,
				tbl_baranghreturbeli.retur_no as nomor,
				tbl_baranghreturbeli.gudang as gudang,
				tbl_barangdreturbeli.kodebarang,
				0 as terima,
				tbl_barangdreturbeli.qty_retur as keluar,
				tbl_barangdreturbeli.qty_retur as qty,
				tbl_barangdreturbeli.price as harga,
				tbl_baranghreturbeli.jamretur as jam,
				tbl_baranghreturbeli.vendor_id as rekanan,
				'RETUR PEMBELIAN' as keterangan,
				(Select hpp from tbl_barang where kodebarang=tbl_barangdreturbeli.kodebarang) as hpp,
				(tbl_barangdreturbeli.qty_retur*(Select hpp from tbl_barang where kodebarang=tbl_barangdreturbeli.kodebarang)) as totalhpp
				from tbl_baranghreturbeli inner join
				tbl_barangdreturbeli on tbl_baranghreturbeli.retur_no=tbl_barangdreturbeli.retur_no

				union all
				 
				select 
				tbl_apohresep.tglresep as tanggal,
				tbl_apohresep.koders,
				tbl_apohresep.resepno as nomor,
				tbl_apohresep.gudang as gudang,
				tbl_apodresep.kodebarang,
				0 as terima,
				tbl_apodresep.qty as keluar,
				tbl_apodresep.qty as qty,
				tbl_apodresep.hpp as harga,
				tbl_apohresep.jam as jam,
				(select namapas from tbl_pasien where rekmed = tbl_apohresep.rekmed) as rekanan,
				'PENJUALAN' as keterangan,
				(Select hpp from tbl_barang where kodebarang=tbl_apodresep.kodebarang) as hpp,
				(tbl_apodresep.qty*(Select hpp from tbl_barang where kodebarang=tbl_apodresep.kodebarang)) as totalhpp
				from tbl_apohresep inner join
				tbl_apodresep on tbl_apohresep.resepno=tbl_apodresep.resepno

				union all
				 
				select 
				tbl_apohresep.tglresep as tanggal,
				tbl_apohresep.koders,
				tbl_apohresep.resepno as nomor,
				tbl_apohresep.gudang as gudang,
				tbl_apodetresep.kodebarang,
				0 as terima,
				tbl_apodetresep.qtyr as keluar,
				tbl_apodetresep.qtyr as qty,
				tbl_apodetresep.hpp as harga,
				tbl_apohresep.jam as jam,
				(select namapas from tbl_pasien where rekmed = tbl_apohresep.rekmed) as rekanan,
				'PENJUALAN RACIK' as keterangan,
				(Select hpp from tbl_barang where kodebarang=tbl_apodetresep.kodebarang) as hpp,
				(tbl_apodetresep.qtyr*(Select hpp from tbl_barang where kodebarang=tbl_apodetresep.kodebarang)) as totalhpp
				from tbl_apohresep inner join
				tbl_apodetresep on tbl_apohresep.resepno=tbl_apodetresep.resepno

				union all

				select
				tbl_apodetresep.exp_date as tanggal,
				tbl_apodetresep.koders,
				tbl_apodetresep.resepno as nomor,
				'' as gudang,
				tbl_apodetresep.kodebarang,
				0 as terima,
				tbl_apodetresep.qtyr as keluar,
				tbl_apodetresep.qty as qty,
				tbl_apodetresep.price as harga,
				tbl_apodetresep.jamdresep as jam,
				'' as rekanan,
				'RACIKAN' as keterangan,
				(Select hpp from tbl_barang where kodebarang=tbl_apodetresep.kodebarang) as hpp,
				(tbl_apodetresep.qtyr*(Select hpp from tbl_barang where kodebarang=tbl_apodetresep.kodebarang)) as totalhpp
				from tbl_apodetresep
				
				union all
				
				select 
				tbl_apohreturjual.tglretur as tanggal,
				tbl_apohreturjual.koders,
				tbl_apohreturjual.returno as nomor,
				tbl_apohreturjual.gudang as gudang,
				tbl_apodreturjual.kodebarang,
				tbl_apodreturjual.qtyretur as terima,
				0 as keluar,
				tbl_apodreturjual.qtyretur as qty,
				tbl_apodreturjual.price as harga,
				tbl_apohreturjual.jamreturjual as jam,
				tbl_apohreturjual.rekmed as rekanan,
				'RETUR JUAL' as keterangan,
				(Select hpp from tbl_barang where kodebarang=tbl_apodreturjual.kodebarang) as hpp,
				(tbl_apodreturjual.qtyretur*(Select hpp from tbl_barang where kodebarang=tbl_apodreturjual.kodebarang)) as totalhpp
				from tbl_apohreturjual inner join
				tbl_apodreturjual on tbl_apohreturjual.returno=tbl_apodreturjual.returno

				union all
				
				select 
				tbl_apohmove.movedate as tanggal,
				tbl_apohmove.koders,
				tbl_apohmove.moveno as nomor,
				tbl_apohmove.dari as gudang,
				tbl_apodmove.kodebarang,
				0 as terima,
				tbl_apodmove.qtymove as keluar,				
				tbl_apodmove.qtymove as qty,
				tbl_apodmove.harga as harga,
				tbl_apohmove.jammove as jam,
				tbl_apohmove.mohonno as rekanan,
				'MUTASI KELUAR' as keterangan,
				(Select hpp from tbl_barang where kodebarang=tbl_apodmove.kodebarang) as hpp,
				(tbl_apodmove.qtymove*(Select hpp from tbl_barang where kodebarang=tbl_apodmove.kodebarang)) as totalhpp
				from tbl_apohmove inner join
				tbl_apodmove on tbl_apohmove.moveno=tbl_apodmove.moveno
				where tbl_apohmove.dari = '$gudang'

				union all
				
				select 
				tbl_apohmove.movedate as tanggal,
				tbl_apohmove.koders,
				tbl_apohmove.moveno as nomor,
				tbl_apohmove.ke as gudang,
				tbl_apodmove.kodebarang,
				tbl_apodmove.qtymove as terima,
				0 as keluar,
				tbl_apodmove.qtymove as qty,
				tbl_apodmove.harga as harga,
				tbl_apohmove.jammove as jam,
				tbl_apohmove.mohonno as rekanan,
				'MUTASI MASUK' as keterangan,
				(Select hpp from tbl_barang where kodebarang=tbl_apodmove.kodebarang) as hpp,
				(tbl_apodmove.qtymove*(Select hpp from tbl_barang where kodebarang=tbl_apodmove.kodebarang)) as totalhpp
				from tbl_apohmove inner join
				tbl_apodmove on tbl_apohmove.moveno=tbl_apodmove.moveno
				where tbl_apohmove.ke = '$gudang'
				
				union all
				
				select 
				tbl_apohproduksi.tglproduksi as tanggal,
				tbl_apohproduksi.koders,
				tbl_apohproduksi.prdno as nomor,
				tbl_apohproduksi.gudang as gudang,
				tbl_apodproduksi.kodebarang,
				0 as terima,
				tbl_apodproduksi.qty as keluar,
				tbl_apodproduksi.qty as qty,
				tbl_apodproduksi.harga as harga,
				tbl_apohproduksi.jamproduksi as jam,
				tbl_apohproduksi.gudang as rekanan,
				'PRODUKSI' as keterangan,
				(Select hpp from tbl_barang where kodebarang=tbl_apodproduksi.kodebarang) as hpp,
				(tbl_apodproduksi.qty*(Select hpp from tbl_barang where kodebarang=tbl_apodproduksi.kodebarang)) as totalhpp
				from tbl_apohproduksi inner join tbl_apodproduksi
				on tbl_apohproduksi.prdno=tbl_apodproduksi.prdno

				union all
				
				select 
				tbl_apohproduksi.tglproduksi as tanggal,
				tbl_apohproduksi.koders,
				tbl_apohproduksi.prdno as nomor,
				tbl_apohproduksi.gudang as gudang,
				tbl_apohproduksi.kodebarang,
				tbl_apohproduksi.qtyjadi as terima,
				0 as keluar,				
				tbl_apohproduksi.qtyjadi as qty,
				tbl_apohproduksi.hpp as harga,
				tbl_apohproduksi.jamproduksi as jam,
				tbl_apohproduksi.gudang as rekanan,
				'PRODUKSI' as keterangan,
				(Select hpp from tbl_barang where kodebarang=tbl_apohproduksi.kodebarang) as hpp,
				(tbl_apohproduksi.qtyjadi*(Select hpp from tbl_barang where kodebarang=tbl_apohproduksi.kodebarang)) as totalhpp
				from tbl_apohproduksi

				union all

				SELECT 
				tbl_aposesuai.tglso AS tanggal,
				tbl_aposesuai.koders,
				'-' AS nomor,
				tbl_aposesuai.gudang AS gudang,
				tbl_aposesuai.kodebarang,
				tbl_aposesuai.sesuai AS terima,
				0 AS keluar,
				tbl_aposesuai.sesuai AS qty,
				tbl_aposesuai.hpp AS harga,
				tbl_aposesuai.jamentry AS jam,
				tbl_aposesuai.type AS rekanan,
				CONCAT(
				' ', tbl_aposesuai.type ,' [ ', tbl_aposesuai.hasilso ,' - ', tbl_aposesuai.saldo ,' ] 
				') AS keterangan,
				(Select hpp from tbl_barang where kodebarang=tbl_aposesuai.kodebarang) as hpp,
				(tbl_aposesuai.sesuai*(Select hpp from tbl_barang where kodebarang=tbl_aposesuai.kodebarang)) as totalhpp
				FROM tbl_aposesuai
				where approve = 1
				
				union all

				select 
				tbl_apohex.tgl_ed as tanggal,
				tbl_apohex.koders,
				tbl_apohex.ed_no as nomor,
				tbl_apohex.gudang as gudang,
				tbl_apodex.kodebarang,
				0 as terima,
				tbl_apodex.qty as keluar,
				tbl_apodex.qty as qty,
				tbl_apodex.hpp as harga,
				tbl_apohex.jam as jam,
				tbl_apohex.keterangan as rekanan,
				'BARANG EXPIRE' as keterangan,
				(Select hpp from tbl_barang where kodebarang=tbl_apodex.kodebarang) as hpp,
				(tbl_apodex.qty*(Select hpp from tbl_barang where kodebarang=tbl_apodex.kodebarang)) as totalhpp
				from tbl_apohex inner join
				tbl_apodex on tbl_apohex.ed_no=tbl_apodex.ed_no
			) mutasi
			where 
			kodebarang ='$barang' and koders ='$cabang' and
			gudang = '$gudang' and tanggal BETWEEN '$_tanggalawal' AND '$_tgl2' and jam > '$jam' order by tanggal, jam asc";
		return $this->db->query($mutasi)->result();
	}
	public function cekstok($cabang, $barang, $gudang, $tgl1, $tgl2)
	{
		$_tgl1x = date('Y-m-d', strtotime($tgl1));
		$ck = $this->db->get_where("tbl_periode", ["koders" => $cabang])->row();
		if($_tgl1x < $ck->apoperiode){
			$_tgl1 = $ck->apoperiode;
		} else {
			$_tgl1 = $_tgl1x;
		}
		$_tgl2 = date('Y-m-d', strtotime($tgl2));
		$_peri = 'Dari ' . date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2));
		$_peri1 = 'Per Tgl. ' . date('d', strtotime($tgl2)) . ' ' . $this->M_global->_namabulan(date('n', strtotime($tgl2))) . ' ' . date('Y', strtotime($tgl2));
		$mutasi =
			"SELECT SUM(mutasi.terima) AS t_terima, SUM(mutasi.keluar) AS t_keluar, (SUM(mutasi.terima) - SUM(mutasi.keluar)) AS total FROM(
				SELECT
				tbl_baranghterima.terima_date AS tanggal,
				tbl_baranghterima.koders,
				tbl_baranghterima.terima_no AS nomor,
				tbl_baranghterima.gudang,
				tbl_barangdterima.kodebarang,
				tbl_barangdterima.qty_terima AS terima,
				0 keluar,
				tbl_barangdterima.qty_terima AS qty,
				tbl_barangdterima.price AS harga,
				tbl_baranghterima.jamterima AS jam,
				tbl_vendor.vendor_name AS rekanan,
				'PEMBELIAN' AS keterangan,
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_barangdterima.kodebarang) AS hpp,
				(tbl_barangdterima.qty_terima*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_barangdterima.kodebarang)) AS totalhpp
				FROM tbl_baranghterima INNER JOIN
				tbl_barangdterima ON tbl_baranghterima.terima_no=tbl_barangdterima.terima_no
				INNER JOIN tbl_vendor ON tbl_baranghterima.vendor_id=tbl_vendor.vendor_id

				UNION ALL
				
				SELECT 
				tbl_baranghreturbeli.retur_date AS tanggal,
				tbl_baranghreturbeli.koders,
				tbl_baranghreturbeli.retur_no AS nomor,
				tbl_baranghreturbeli.gudang AS gudang,
				tbl_barangdreturbeli.kodebarang,
				0 AS terima,
				tbl_barangdreturbeli.qty_retur AS keluar,
				tbl_barangdreturbeli.qty_retur AS qty,
				tbl_barangdreturbeli.price AS harga,
				tbl_baranghreturbeli.jamretur AS jam,
				tbl_baranghreturbeli.vendor_id AS rekanan,
				'RETUR PEMBELIAN' AS keterangan,
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_barangdreturbeli.kodebarang) AS hpp,
				(tbl_barangdreturbeli.qty_retur*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_barangdreturbeli.kodebarang)) AS totalhpp
				FROM tbl_baranghreturbeli INNER JOIN
				tbl_barangdreturbeli ON tbl_baranghreturbeli.retur_no=tbl_barangdreturbeli.retur_no

				UNION ALL
				 
				SELECT 
				tbl_apohresep.tglresep AS tanggal,
				tbl_apohresep.koders,
				tbl_apohresep.resepno AS nomor,
				tbl_apohresep.gudang AS gudang,
				tbl_apodresep.kodebarang,
				0 AS terima,
				tbl_apodresep.qty AS keluar,
				tbl_apodresep.qty AS qty,
				tbl_apodresep.hpp AS harga,
				tbl_apohresep.jam AS jam,
				(SELECT namapas FROM tbl_pasien WHERE rekmed = tbl_apohresep.rekmed) AS rekanan,
				'PENJUALAN' AS keterangan,
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodresep.kodebarang) AS hpp,
				(tbl_apodresep.qty*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodresep.kodebarang)) AS totalhpp
				FROM tbl_apohresep INNER JOIN
				tbl_apodresep ON tbl_apohresep.resepno=tbl_apodresep.resepno

				UNION ALL

				SELECT
				tbl_apodetresep.exp_date AS tanggal,
				tbl_apodetresep.koders,
				tbl_apodetresep.resepno AS nomor,
				'' AS gudang,
				tbl_apodetresep.kodebarang,
				0 AS terima,
				tbl_apodetresep.qtyr AS keluar,
				tbl_apodetresep.qty AS qty,
				tbl_apodetresep.price AS harga,
				tbl_apodetresep.jamdresep AS jam,
				'' AS rekanan,
				'RACIKAN' AS keterangan,
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodetresep.kodebarang) AS hpp,
				(tbl_apodetresep.qtyr*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodetresep.kodebarang)) AS totalhpp
				FROM tbl_apodetresep
				
				UNION ALL
				
				SELECT 
				tbl_apohreturjual.tglretur AS tanggal,
				tbl_apohreturjual.koders,
				tbl_apohreturjual.returno AS nomor,
				tbl_apohreturjual.gudang AS gudang,
				tbl_apodreturjual.kodebarang,
				tbl_apodreturjual.qtyretur AS terima,
				0 AS keluar,
				tbl_apodreturjual.qtyretur AS qty,
				tbl_apodreturjual.price AS harga,
				tbl_apohreturjual.jamreturjual AS jam,
				tbl_apohreturjual.rekmed AS rekanan,
				'RETUR JUAL' AS keterangan,
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodreturjual.kodebarang) AS hpp,
				(tbl_apodreturjual.qtyretur*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodreturjual.kodebarang)) AS totalhpp
				FROM tbl_apohreturjual INNER JOIN
				tbl_apodreturjual ON tbl_apohreturjual.returno=tbl_apodreturjual.returno

				UNION ALL
				
				SELECT 
				tbl_apohmove.movedate AS tanggal,
				tbl_apohmove.koders,
				tbl_apohmove.moveno AS nomor,
				tbl_apohmove.dari AS gudang,
				tbl_apodmove.kodebarang,
				0 AS terima,
				tbl_apodmove.qtymove AS keluar,				
				tbl_apodmove.qtymove AS qty,
				tbl_apodmove.harga AS harga,
				tbl_apohmove.jammove AS jam,
				tbl_apohmove.mohonno AS rekanan,
				'MUTASI' AS keterangan,
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodmove.kodebarang) AS hpp,
				(tbl_apodmove.qtymove*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodmove.kodebarang)) AS totalhpp
				FROM tbl_apohmove INNER JOIN
				tbl_apodmove ON tbl_apohmove.moveno=tbl_apodmove.moveno

				UNION ALL
				
				SELECT 
				tbl_apohmove.movedate AS tanggal,
				tbl_apohmove.koders,
				tbl_apohmove.moveno AS nomor,
				tbl_apohmove.ke AS gudang,
				tbl_apodmove.kodebarang,
				tbl_apodmove.qtymove AS terima,
				0 AS keluar,
				tbl_apodmove.qtymove AS qty,
				tbl_apodmove.harga AS harga,
				tbl_apohmove.jammove AS jam,
				tbl_apohmove.mohonno AS rekanan,
				'MUTASI' AS keterangan,
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodmove.kodebarang) AS hpp,
				(tbl_apodmove.qtymove*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodmove.kodebarang)) AS totalhpp
				FROM tbl_apohmove INNER JOIN
				tbl_apodmove ON tbl_apohmove.moveno=tbl_apodmove.moveno
				
				UNION ALL
				
				SELECT 
				tbl_apohproduksi.tglproduksi AS tanggal,
				tbl_apohproduksi.koders,
				tbl_apohproduksi.prdno AS nomor,
				tbl_apohproduksi.gudang AS gudang,
				tbl_apodproduksi.kodebarang,
				0 AS terima,
				tbl_apodproduksi.qty AS keluar,
				tbl_apodproduksi.qty AS qty,
				tbl_apodproduksi.harga AS harga,
				tbl_apohproduksi.jamproduksi AS jam,
				tbl_apohproduksi.gudang AS rekanan,
				'PRODUKSI' AS keterangan,
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodproduksi.kodebarang) AS hpp,
				(tbl_apodproduksi.qty*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodproduksi.kodebarang)) AS totalhpp
				FROM tbl_apohproduksi INNER JOIN tbl_apodproduksi
				ON tbl_apohproduksi.prdno=tbl_apodproduksi.prdno

				UNION ALL
				
				SELECT 
				tbl_apohproduksi.tglproduksi AS tanggal,
				tbl_apohproduksi.koders,
				tbl_apohproduksi.prdno AS nomor,
				tbl_apohproduksi.gudang AS gudang,
				tbl_apohproduksi.kodebarang,
				tbl_apohproduksi.qtyjadi AS terima,
				0 AS keluar,				
				tbl_apohproduksi.qtyjadi AS qty,
				tbl_apohproduksi.hpp AS harga,
				tbl_apohproduksi.jamproduksi AS jam,
				tbl_apohproduksi.gudang AS rekanan,
				'PRODUKSI' AS keterangan,
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apohproduksi.kodebarang) AS hpp,
				(tbl_apohproduksi.qtyjadi*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apohproduksi.kodebarang)) AS totalhpp
				FROM tbl_apohproduksi

				UNION ALL

				SELECT 
				tbl_aposesuai.tglso AS tanggal,
				tbl_aposesuai.koders,
				'-' AS nomor,
				tbl_aposesuai.gudang AS gudang,
				tbl_aposesuai.kodebarang,
				tbl_aposesuai.sesuai AS terima,
				0 AS keluar,
				tbl_aposesuai.sesuai AS qty,
				tbl_aposesuai.hpp AS harga,
				tbl_aposesuai.jamentry AS jam,
				tbl_aposesuai.type AS rekanan,
				CONCAT(
				' ', tbl_aposesuai.type ,' [ ', tbl_aposesuai.hasilso ,' - ', tbl_aposesuai.saldo ,' ] 
				') AS keterangan,
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_aposesuai.kodebarang) AS hpp,
				(tbl_aposesuai.sesuai*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_aposesuai.kodebarang)) AS totalhpp
				FROM tbl_aposesuai
				WHERE approve = 1
				
				UNION ALL

				SELECT 
				tbl_apohex.tgl_ed AS tanggal,
				tbl_apohex.koders,
				tbl_apohex.ed_no AS nomor,
				tbl_apohex.gudang AS gudang,
				tbl_apodex.kodebarang,
				0 AS terima,
				tbl_apodex.qty AS keluar,
				tbl_apodex.qty AS qty,
				tbl_apodex.hpp AS harga,
				tbl_apohex.jam AS jam,
				tbl_apohex.keterangan AS rekanan,
				'BARANG EXPIRE' AS keterangan,
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodex.kodebarang) AS hpp,
				(tbl_apodex.qty*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodex.kodebarang)) AS totalhpp
				FROM tbl_apohex INNER JOIN
				tbl_apodex ON tbl_apohex.ed_no=tbl_apodex.ed_no
			) mutasi
			WHERE 
			kodebarang ='$barang' AND koders ='$cabang' AND
			gudang = '$gudang' AND tanggal < '$_tgl2' ORDER BY tanggal, jam ASC";
			$mutasi2 = $this->db->get_where("tbl_barangstock", ["gudang" => $gudang, "koders" => $cabang, "kodebarang" => $barang, "lasttr" => "'< ".$_tgl1."'"])->row();
		// return $this->db->query($mutasi)->result();
		return $mutasi2;
	}
	public function farmasistok($id)
	{
		$brg = $this->db->get_where('tbl_barangstock', ['id' => $id])->row_array();
		$_tgl1 = date('d-m-Y', strtotime($brg['tglso']));
		$mutasi =
			"SELECT * FROM(
				select
				tbl_baranghterima.terima_date as tanggal,
				tbl_baranghterima.koders,
				tbl_baranghterima.terima_no as nomor,
				tbl_baranghterima.gudang,
				tbl_barangdterima.kodebarang,
				tbl_barangdterima.qty_terima as terima,
				0 keluar,
				tbl_barangdterima.qty_terima as qty,
				tbl_barangdterima.price as harga,
				tbl_vendor.vendor_name as rekanan,
				'PEMBELIAN' as keterangan,
				(Select hpp from tbl_barang where kodebarang=tbl_barangdterima.kodebarang) as hpp,
				(tbl_barangdterima.qty_terima*(Select hpp from tbl_barang where kodebarang=tbl_barangdterima.kodebarang)) as totalhpp
				from tbl_baranghterima join
				tbl_barangdterima on tbl_baranghterima.terima_no=tbl_barangdterima.terima_no
				inner join tbl_vendor on tbl_baranghterima.vendor_id=tbl_vendor.vendor_id

				union all
				
				select 
				tbl_baranghreturbeli.retur_date as tanggal,
				tbl_baranghreturbeli.koders,
				tbl_baranghreturbeli.retur_no as nomor,
				tbl_baranghreturbeli.gudang as gudang,
				tbl_barangdreturbeli.kodebarang,
				0 as terima,
				tbl_barangdreturbeli.qty_retur as keluar,
				tbl_barangdreturbeli.qty_retur as qty,
				tbl_barangdreturbeli.price as harga,
				tbl_baranghreturbeli.vendor_id as rekanan,
				'RETUR PEMBELIAN' as keterangan,
				(Select hpp from tbl_barang where kodebarang=tbl_barangdreturbeli.kodebarang) as hpp,
				(tbl_barangdreturbeli.qty_retur*(Select hpp from tbl_barang where kodebarang=tbl_barangdreturbeli.kodebarang)) as totalhpp
				from tbl_baranghreturbeli inner join
				tbl_barangdreturbeli on tbl_baranghreturbeli.retur_no=tbl_barangdreturbeli.retur_no

				union all
				 
				select 
				tbl_apohresep.tglresep as tanggal,
				tbl_apohresep.koders,
				tbl_apohresep.resepno as nomor,
				tbl_apohresep.gudang as gudang,
				tbl_apodresep.kodebarang,
				0 as terima,
				tbl_apodresep.qty as keluar,
				tbl_apodresep.qty as qty,
				tbl_apodresep.hpp as harga,
				(select namapas from tbl_pasien where rekmed = tbl_apohresep.rekmed) as rekanan,
				'PENJUALAN' as keterangan,
				(Select hpp from tbl_barang where kodebarang=tbl_apodresep.kodebarang) as hpp,
				(tbl_apodresep.qty*(Select hpp from tbl_barang where kodebarang=tbl_apodresep.kodebarang)) as totalhpp
				from tbl_apohresep inner join
				tbl_apodresep on tbl_apohresep.resepno=tbl_apodresep.resepno

				union all

				select
				tbl_apodetresep.exp_date as tanggal,
				tbl_apodetresep.koders,
				tbl_apodetresep.resepno as nomor,
				'' as gudang,
				tbl_apodetresep.kodebarang,
				0 as terima,
				tbl_apodetresep.qtyr as keluar,
				tbl_apodetresep.qty as qty,
				tbl_apodetresep.price as harga,
				'' as rekanan,
				'RACIKAN' as keterangan,
				(Select hpp from tbl_barang where kodebarang=tbl_apodetresep.kodebarang) as hpp,
				(tbl_apodetresep.qtyr*(Select hpp from tbl_barang where kodebarang=tbl_apodetresep.kodebarang)) as totalhpp
				from tbl_apodetresep
				
				union all
				
				select 
				tbl_apohreturjual.tglretur as tanggal,
				tbl_apohreturjual.koders,
				tbl_apohreturjual.returno as nomor,
				tbl_apohreturjual.gudang as gudang,
				tbl_apodreturjual.kodebarang,
				tbl_apodreturjual.qtyretur as terima,
				0 as keluar,
				tbl_apodreturjual.qtyretur as qty,
				tbl_apodreturjual.price as harga,
				tbl_apohreturjual.rekmed as rekanan,
				'RETUR JUAL' as keterangan,
				(Select hpp from tbl_barang where kodebarang=tbl_apodreturjual.kodebarang) as hpp,
				(tbl_apodreturjual.qtyretur*(Select hpp from tbl_barang where kodebarang=tbl_apodreturjual.kodebarang)) as totalhpp
				from tbl_apohreturjual inner join
				tbl_apodreturjual on tbl_apohreturjual.returno=tbl_apodreturjual.returno

				union all
				
				select 
				tbl_apohmove.movedate as tanggal,
				tbl_apohmove.koders,
				tbl_apohmove.moveno as nomor,
				tbl_apohmove.dari as gudang,
				tbl_apodmove.kodebarang,
				0 as terima,
				tbl_apodmove.qtymove as keluar,				
				tbl_apodmove.qtymove as qty,
				tbl_apodmove.harga as harga,
				tbl_apohmove.mohonno as rekanan,
				'MUTASI' as keterangan,
				(Select hpp from tbl_barang where kodebarang=tbl_apodmove.kodebarang) as hpp,
				(tbl_apodmove.qtymove*(Select hpp from tbl_barang where kodebarang=tbl_apodmove.kodebarang)) as totalhpp
				from tbl_apohmove inner join
				tbl_apodmove on tbl_apohmove.moveno=tbl_apodmove.moveno

				union all
				
				select 
				tbl_apohmove.movedate as tanggal,
				tbl_apohmove.koders,
				tbl_apohmove.moveno as nomor,
				tbl_apohmove.ke as gudang,
				tbl_apodmove.kodebarang,
				tbl_apodmove.qtymove as terima,
				0 as keluar,
				tbl_apodmove.qtymove as qty,
				tbl_apodmove.harga as harga,
				tbl_apohmove.mohonno as rekanan,
				'MUTASI' as keterangan,
				(Select hpp from tbl_barang where kodebarang=tbl_apodmove.kodebarang) as hpp,
				(tbl_apodmove.qtymove*(Select hpp from tbl_barang where kodebarang=tbl_apodmove.kodebarang)) as totalhpp
				from tbl_apohmove inner join
				tbl_apodmove on tbl_apohmove.moveno=tbl_apodmove.moveno
				
				union all
				
				select 
				tbl_apohproduksi.tglproduksi as tanggal,
				tbl_apohproduksi.koders,
				tbl_apohproduksi.prdno as nomor,
				tbl_apohproduksi.gudang as gudang,
				tbl_apodproduksi.kodebarang,
				0 as terima,
				tbl_apodproduksi.qty as keluar,
				tbl_apodproduksi.qty as qty,
				tbl_apodproduksi.harga as harga,
				tbl_apohproduksi.gudang as rekanan,
				'PRODUKSI' as keterangan,
				(Select hpp from tbl_barang where kodebarang=tbl_apodproduksi.kodebarang) as hpp,
				(tbl_apodproduksi.qty*(Select hpp from tbl_barang where kodebarang=tbl_apodproduksi.kodebarang)) as totalhpp
				from tbl_apohproduksi inner join tbl_apodproduksi
				on tbl_apohproduksi.prdno=tbl_apodproduksi.prdno

				union all
				
				select 
				tbl_apohproduksi.tglproduksi as tanggal,
				tbl_apohproduksi.koders,
				tbl_apohproduksi.prdno as nomor,
				tbl_apohproduksi.gudang as gudang,
				tbl_apohproduksi.kodebarang,
				tbl_apohproduksi.qtyjadi as terima,
				0 as keluar,				
				tbl_apohproduksi.qtyjadi as qty,
				tbl_apohproduksi.hpp as harga,
				tbl_apohproduksi.gudang as rekanan,
				'PRODUKSI' as keterangan,
				(Select hpp from tbl_barang where kodebarang=tbl_apohproduksi.kodebarang) as hpp,
				(tbl_apohproduksi.qtyjadi*(Select hpp from tbl_barang where kodebarang=tbl_apohproduksi.kodebarang)) as totalhpp
				from tbl_apohproduksi

				union all

				SELECT 
				tbl_aposesuai.tglso AS tanggal,
				tbl_aposesuai.koders,
				'-' AS nomor,
				tbl_aposesuai.gudang AS gudang,
				tbl_aposesuai.kodebarang,
				tbl_aposesuai.sesuai AS terima,
				0 AS keluar,
				tbl_aposesuai.sesuai AS qty,
				tbl_aposesuai.hpp AS harga,
				tbl_aposesuai.type AS rekanan,
				CONCAT(
				' ', tbl_aposesuai.type ,' [ ', tbl_aposesuai.saldo ,' - ', tbl_aposesuai.hasilso ,' ] 
				') AS keterangan,
				(Select hpp from tbl_barang where kodebarang=tbl_aposesuai.kodebarang) as hpp,
				(tbl_aposesuai.sesuai*(Select hpp from tbl_barang where kodebarang=tbl_aposesuai.kodebarang)) as totalhpp
				FROM tbl_aposesuai
				where approve = 1
				
				union all

				select 
				tbl_apohex.tgl_ed as tanggal,
				tbl_apohex.koders,
				tbl_apohex.ed_no as nomor,
				tbl_apohex.gudang as gudang,
				tbl_apodex.kodebarang,
				0 as terima,
				tbl_apodex.qty as keluar,
				tbl_apodex.qty as qty,
				tbl_apodex.hpp as harga,
				tbl_apohex.keterangan as rekanan,
				'BARANG EXPIRE' as keterangan,
				(Select hpp from tbl_barang where kodebarang=tbl_apodex.kodebarang) as hpp,
				(tbl_apodex.qty*(Select hpp from tbl_barang where kodebarang=tbl_apodex.kodebarang)) as totalhpp
				from tbl_apohex inner join
				tbl_apodex on tbl_apohex.ed_no=tbl_apodex.ed_no
			) mutasi
			where 
			kodebarang ='" . $brg['kodebarang'] . "' and koders ='" . $brg["koders"] . "' and
			gudang = '" . $brg["gudang"] . "' order by tanggal";
		return $this->db->query($mutasi)->result();
	}
	public function logistikstok($id)
	{
		$brg = $this->db->get_where('tbl_apostocklog', ['id' => $id])->row_array();
		$_tgl1 = date('d-m-Y', strtotime($brg['tglso']));
		$mutasi =
			"SELECT * from 
		 (select 
		 a.terima_date as tanggal,
		 a.koders,
		 a.terima_no as nomor,
		 a.gudang,
		 b.kodebarang,
		 b.qty_terima as terima,
		 0 keluar,
		 b.qty_terima as qty,
		 b.price as harga,
		 tbl_vendor.vendor_name as rekanan,
		 'PEMBELIAN' as keterangan,
		 (select hpp from tbl_logbarang where kodebarang=b.kodebarang) as hpp,
		 (b.qty_terima*(select hpp from tbl_logbarang where kodebarang=b.kodebarang)) as totalhpp
		 from tbl_apohterimalog AS a
		 inner join tbl_apodterimalog AS b on a.terima_no=b.terima_no
		 inner join tbl_vendor on a.vendor_id=tbl_vendor.vendor_id
		 
		 union all

		 SELECT 
		tbl_apohreturbelilog.retur_date AS tanggal,
		tbl_apohreturbelilog.koders,
		tbl_apohreturbelilog.terima_no AS nomor,
		tbl_apohreturbelilog.gudang AS gudang,
		tbl_apodreturbelilog.kodebarang,
		0 AS terima,
		tbl_apodreturbelilog.qty_retur AS keluar,
		tbl_apodreturbelilog.qty_retur AS qty,
		tbl_apodreturbelilog.price AS harga,
		tbl_apohreturbelilog.vendor_id AS rekanan,
		'RETUR PEMBELIAN' AS keterangan,
		 (select hpp from tbl_logbarang where kodebarang=tbl_apodreturbelilog.kodebarang) as hpp,
		 (tbl_apodreturbelilog.qty_retur*(select hpp from tbl_logbarang where kodebarang=tbl_apodreturbelilog.kodebarang)) as totalhpp
		FROM tbl_apohreturbelilog INNER JOIN tbl_apodreturbelilog
		ON tbl_apohreturbelilog.retur_no = tbl_apodreturbelilog.retur_no 

		union all
			
		select 
		tbl_apohresep.tglresep as tanggal,
		tbl_apohresep.koders,
		tbl_apohresep.resepno as nomor,
		tbl_apohresep.gudang as gudang,
		tbl_apodresep.kodebarang,
		0 as terima,
		tbl_apodresep.qty as keluar,
		tbl_apodresep.qty as qty,
		tbl_apodresep.hpp as harga,
		(select namapas from tbl_pasien where rekmed = tbl_apohresep.rekmed) as rekanan,
		'PENJUALAN' as keterangan,
		(Select hpp from tbl_logbarang where kodebarang=tbl_apodresep.kodebarang) as hpp,
		(tbl_apodresep.qty*(Select hpp from tbl_logbarang where kodebarang=tbl_apodresep.kodebarang)) as totalhpp
		from tbl_apohresep inner join
		tbl_apodresep on tbl_apohresep.resepno=tbl_apodresep.resepno

		union all

		SELECT
		tbl_apohresep.tglresep as tanggal,
		tbl_apohresep.koders,
		tbl_apohresep.resepno as nomor,
		tbl_apohresep.gudang as gudang,
		tbl_apodresep.kodebarang,
		tbl_apodresep.qty as terima,
		0 as keluar,
		tbl_apodresep.qty as qty,
		tbl_apodresep.price as harga,
		tbl_apohresep.pro as rekanan,
		'RETUR PENJUALAN' as keterangan,
		(select hpp from tbl_logbarang where kodebarang=tbl_apodresep.kodebarang) as hpp,
		 (tbl_apodresep.qty*(select hpp from tbl_logbarang where kodebarang=tbl_apodresep.kodebarang)) as totalhpp
		 FROM tbl_apohresep INNER JOIN tbl_apodresep
		 ON tbl_apohresep.resepno = tbl_apodresep.resepno
		
		union all

		SELECT 
		tbl_pakaihlog.pakaidate AS tanggal,
		tbl_pakaihlog.koders,
		tbl_pakaihlog.pakaino AS nomor,
		tbl_pakaihlog.gudang AS gudang,
		tbl_pakaidlog.kodebarang,
		0 AS terima,
		tbl_pakaidlog.qty AS keluar,
		0 AS qty,
		tbl_pakaidlog.harga AS harga,
		tbl_pakaihlog.keterangan AS rekanan,
		'PEMAKAIAN LOG' AS keterangan,
		 (select hpp from tbl_logbarang where kodebarang=tbl_pakaidlog.kodebarang) as hpp,
		 (tbl_pakaidlog.qty*(select hpp from tbl_logbarang where kodebarang=tbl_pakaidlog.kodebarang)) as totalhpp
		FROM tbl_pakaihlog INNER JOIN tbl_pakaidlog
		ON tbl_pakaihlog.pakaino = tbl_pakaidlog.pakaino
		
		union all
		 
		 select 
		 a.movedate as tanggal,
		 a.koders,
		 a.moveno as nomor,
		 a.ke as gudang,
		 b.kodebarang,
		 b.qtymove as terima,
		 0 as keluar,
		 b.qtymove as qty,
		 b.harga as harga,
		 a.mohonno as rekanan,
		 'MUTASI' as keterangan,
		 (select hpp from tbl_logbarang where kodebarang=b.kodebarang) as hpp,
		 (b.qtymove*(select hpp from tbl_logbarang where kodebarang=b.kodebarang)) as totalhpp
		 from tbl_apohmovelog AS a 
		 inner join tbl_apodmovelog AS b on a.moveno=b.moveno

		 union all
		 
		 select 
		 tbl_apohmovelog.movedate as tanggal,
		 tbl_apohmovelog.koders,
		 tbl_apohmovelog.moveno as nomor,
		 tbl_apohmovelog.dari as gudang,
		 tbl_apodmovelog.kodebarang,
		 0 as terima,
		 tbl_apodmovelog.qtymove as keluar,				
		 tbl_apodmovelog.qtymove as qty,
		 tbl_apodmovelog.harga as harga,
		 tbl_apohmovelog.mohonno as rekanan,
		 'MUTASI' as keterangan,
		 (select hpp from tbl_logbarang where kodebarang=tbl_apodmovelog.kodebarang) as hpp,
		 (tbl_apodmovelog.qtymove*(select hpp from tbl_logbarang where kodebarang=tbl_apodmovelog.kodebarang)) as totalhpp
		 from tbl_apohmovelog inner join
		 tbl_apodmovelog on tbl_apohmovelog.moveno=tbl_apodmovelog.moveno

		union all

		SELECT 
		tbl_aposesuailog.tglso AS tanggal,
		tbl_aposesuailog.koders,
		'-' AS nomor,
		tbl_aposesuailog.gudang AS gudang,
		tbl_aposesuailog.kodeobat as kodebarang,
		tbl_aposesuailog.sesuai AS terima,
		0 AS keluar,
		tbl_aposesuailog.sesuai AS qty,
		tbl_aposesuailog.hpp AS harga,
		tbl_aposesuailog.type AS rekanan,
		CONCAT(
		' ', tbl_aposesuailog.type ,' [ ', tbl_aposesuailog.saldo ,' - ', tbl_aposesuailog.hasilso ,' ] 
		') AS keterangan,
		(Select hpp from tbl_logbarang where kodebarang=tbl_aposesuailog.kodeobat) as hpp,
		(tbl_aposesuailog.sesuai*(Select hpp from tbl_logbarang where kodebarang=tbl_aposesuailog.kodeobat)) as totalhpp
		FROM tbl_aposesuailog
		where approve = 1
		 ) mutasi
		 where 
			kodebarang ='" . $brg['kodebarang'] . "' and koders ='" . $brg["koders"] . "' and
			gudang = '" . $brg["gudang"] . "'
		";
		return $this->db->query($mutasi)->result();
	}
	public function cekstok_log($cabang, $barang, $gudang, $tgl1, $tgl2)
	{
		$mutasi =
			"SELECT SUM(mutasi.terima) AS t_terima, SUM(mutasi.keluar) AS t_keluar, (SUM(mutasi.terima) - SUM(mutasi.keluar)) AS total from 
		 (select 
		 a.terima_date as tanggal,
		 a.koders,
		 a.terima_no as nomor,
		 a.gudang,
		 b.kodebarang,
		 b.qty_terima as terima,
		 0 keluar,
		 b.qty_terima as qty,
		 b.price as harga,
		 tbl_vendor.vendor_name as rekanan,
		 'PEMBELIAN' as keterangan,
		 (select hpp from tbl_logbarang where kodebarang=b.kodebarang) as hpp,
		 (b.qty_terima*(select hpp from tbl_logbarang where kodebarang=b.kodebarang)) as totalhpp
		 from tbl_apohterimalog AS a
		 inner join tbl_apodterimalog AS b on a.terima_no=b.terima_no
		 inner join tbl_vendor on a.vendor_id=tbl_vendor.vendor_id
		 
		 union all

		 SELECT 
		tbl_apohreturbelilog.retur_date AS tanggal,
		tbl_apohreturbelilog.koders,
		tbl_apohreturbelilog.terima_no AS nomor,
		tbl_apohreturbelilog.gudang AS gudang,
		tbl_apodreturbelilog.kodebarang,
		0 AS terima,
		tbl_apodreturbelilog.qty_retur AS keluar,
		tbl_apodreturbelilog.qty_retur AS qty,
		tbl_apodreturbelilog.price AS harga,
		tbl_apohreturbelilog.vendor_id AS rekanan,
		'RETUR PEMBELIAN' AS keterangan,
		 (select hpp from tbl_logbarang where kodebarang=tbl_apodreturbelilog.kodebarang) as hpp,
		 (tbl_apodreturbelilog.qty_retur*(select hpp from tbl_logbarang where kodebarang=tbl_apodreturbelilog.kodebarang)) as totalhpp
		FROM tbl_apohreturbelilog INNER JOIN tbl_apodreturbelilog
		ON tbl_apohreturbelilog.retur_no = tbl_apodreturbelilog.retur_no 

		union all
			
		select 
		tbl_apohresep.tglresep as tanggal,
		tbl_apohresep.koders,
		tbl_apohresep.resepno as nomor,
		tbl_apohresep.gudang as gudang,
		tbl_apodresep.kodebarang,
		0 as terima,
		tbl_apodresep.qty as keluar,
		tbl_apodresep.qty as qty,
		tbl_apodresep.hpp as harga,
		(select namapas from tbl_pasien where rekmed = tbl_apohresep.rekmed) as rekanan,
		'PENJUALAN' as keterangan,
		(Select hpp from tbl_logbarang where kodebarang=tbl_apodresep.kodebarang) as hpp,
		(tbl_apodresep.qty*(Select hpp from tbl_logbarang where kodebarang=tbl_apodresep.kodebarang)) as totalhpp
		from tbl_apohresep inner join
		tbl_apodresep on tbl_apohresep.resepno=tbl_apodresep.resepno

		union all

		SELECT
		tbl_apohresep.tglresep as tanggal,
		tbl_apohresep.koders,
		tbl_apohresep.resepno as nomor,
		tbl_apohresep.gudang as gudang,
		tbl_apodresep.kodebarang,
		tbl_apodresep.qty as terima,
		0 as keluar,
		tbl_apodresep.qty as qty,
		tbl_apodresep.price as harga,
		tbl_apohresep.pro as rekanan,
		'RETUR PENJUALAN' as keterangan,
		(select hpp from tbl_logbarang where kodebarang=tbl_apodresep.kodebarang) as hpp,
		 (tbl_apodresep.qty*(select hpp from tbl_logbarang where kodebarang=tbl_apodresep.kodebarang)) as totalhpp
		 FROM tbl_apohresep INNER JOIN tbl_apodresep
		 ON tbl_apohresep.resepno = tbl_apodresep.resepno
		
		union all

		SELECT 
		tbl_pakaihlog.pakaidate AS tanggal,
		tbl_pakaihlog.koders,
		tbl_pakaihlog.pakaino AS nomor,
		tbl_pakaihlog.gudang AS gudang,
		tbl_pakaidlog.kodebarang,
		0 AS terima,
		tbl_pakaidlog.qty AS keluar,
		0 AS qty,
		tbl_pakaidlog.harga AS harga,
		tbl_pakaihlog.keterangan AS rekanan,
		'PEMAKAIAN LOG' AS keterangan,
		 (select hpp from tbl_logbarang where kodebarang=tbl_pakaidlog.kodebarang) as hpp,
		 (tbl_pakaidlog.qty*(select hpp from tbl_logbarang where kodebarang=tbl_pakaidlog.kodebarang)) as totalhpp
		FROM tbl_pakaihlog INNER JOIN tbl_pakaidlog
		ON tbl_pakaihlog.pakaino = tbl_pakaidlog.pakaino
		
		union all
		 
		 select 
		 a.movedate as tanggal,
		 a.koders,
		 a.moveno as nomor,
		 a.ke as gudang,
		 b.kodebarang,
		 b.qtymove as terima,
		 0 as keluar,
		 b.qtymove as qty,
		 b.harga as harga,
		 a.mohonno as rekanan,
		 'MUTASI' as keterangan,
		 (select hpp from tbl_logbarang where kodebarang=b.kodebarang) as hpp,
		 (b.qtymove*(select hpp from tbl_logbarang where kodebarang=b.kodebarang)) as totalhpp
		 from tbl_apohmovelog AS a 
		 inner join tbl_apodmovelog AS b on a.moveno=b.moveno

		 union all
		 
		 select 
		 tbl_apohmovelog.movedate as tanggal,
		 tbl_apohmovelog.koders,
		 tbl_apohmovelog.moveno as nomor,
		 tbl_apohmovelog.dari as gudang,
		 tbl_apodmovelog.kodebarang,
		 0 as terima,
		 tbl_apodmovelog.qtymove as keluar,				
		 tbl_apodmovelog.qtymove as qty,
		 tbl_apodmovelog.harga as harga,
		 tbl_apohmovelog.mohonno as rekanan,
		 'MUTASI' as keterangan,
		 (select hpp from tbl_logbarang where kodebarang=tbl_apodmovelog.kodebarang) as hpp,
		 (tbl_apodmovelog.qtymove*(select hpp from tbl_logbarang where kodebarang=tbl_apodmovelog.kodebarang)) as totalhpp
		 from tbl_apohmovelog inner join
		 tbl_apodmovelog on tbl_apohmovelog.moveno=tbl_apodmovelog.moveno

		union all

		SELECT 
		tbl_aposesuailog.tglso AS tanggal,
		tbl_aposesuailog.koders,
		'-' AS nomor,
		tbl_aposesuailog.gudang AS gudang,
		tbl_aposesuailog.kodeobat as kodebarang,
		tbl_aposesuailog.sesuai AS terima,
		0 AS keluar,
		tbl_aposesuailog.sesuai AS qty,
		tbl_aposesuailog.hpp AS harga,
		tbl_aposesuailog.type AS rekanan,
		CONCAT(
		' ', tbl_aposesuailog.type ,' [ ', tbl_aposesuailog.saldo ,' - ', tbl_aposesuailog.hasilso ,' ] 
		') AS keterangan,
		(Select hpp from tbl_logbarang where kodebarang=tbl_aposesuailog.kodeobat) as hpp,
		(tbl_aposesuailog.sesuai*(Select hpp from tbl_logbarang where kodebarang=tbl_aposesuailog.kodeobat)) as totalhpp
		FROM tbl_aposesuailog
		where approve = 1
		 ) mutasi
		 where 
			kodebarang ='$barang' and koders ='$cabang' and
			gudang = '$gudang' AND tanggal < '$tgl2' ORDER BY tanggal ASC
		";
		return $this->db->query($mutasi)->result();
	}

	public function cekstok_farmasi($cabang, $barang, $gudang, $tgl1, $tgl2)
	{
		$cek1 = $this->db->query("SELECT saldoawal as saldoawal, date('H:i:s') as jam FROM tbl_barangstock WHERE koders = '$cabang' AND kodebarang = '$barang' AND gudang = '$gudang'")->row();
		if ((int)$cek1->saldoawal > 0) {
			$saldo = $cek1;
		} else {
			$periode = $this->db->get_where("tbl_periode", ['koders' => $cabang])->row();
			$tgl = date("Y-m-d", strtotime($periode->apoperiode));
			$cek2 = $this->db->query("SELECT hasilso as saldoawal, jamentry as jam FROM tbl_aposesuai WHERE koders = '$cabang' AND kodebarang = '$barang' AND gudang = '$gudang' AND tglso = '$tgl'")->row();
			$saldo = $cek2;
		}
		return $saldo;
	}
}

// -- WHERE kodebarang = '$barang' AND tbl_apohmove.koders = '$cabang'

// -- 	where kodebarang = '01PONT' and
// 			--      koders = '$cabang' and
// 		     --    gudang = 'FARMASI' or gudang = 'LOKAL' and
// 			--     tanggal between '2022-05-25' and '2022-05-25'
// 			--     ORDER BY tanggal ASC;