<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_KartuStock extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	public function tgl($cabang, $barang, $gudang, $tgl1, $tgl2)
	{
		$mutasi = 
		"SELECT * FROM(
			SELECT tbl_baranghterima.terima_date AS tanggal, 
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
			FROM tbl_baranghterima 
			INNER JOIN tbl_barangdterima ON tbl_baranghterima.terima_no=tbl_barangdterima.terima_no 
			INNER JOIN tbl_vendor ON tbl_baranghterima.vendor_id=tbl_vendor.vendor_id 
			
			UNION ALL 
			
			SELECT tbl_baranghreturbeli.retur_date AS tanggal, 
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
			FROM tbl_baranghreturbeli 
			INNER JOIN tbl_barangdreturbeli ON tbl_baranghreturbeli.retur_no=tbl_barangdreturbeli.retur_no 
			
			UNION ALL 
			
			SELECT tbl_apohresep.tglresep AS tanggal, 
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
				'PENJUALAN RESEP' AS keterangan, 
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodresep.kodebarang) AS hpp, 
				(tbl_apodresep.qty*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodresep.kodebarang)) AS totalhpp 
			FROM tbl_apohresep 
			INNER JOIN tbl_apodresep ON tbl_apohresep.resepno=tbl_apodresep.resepno 
			
			UNION ALL 
			
			SELECT tbl_apohresep.tglresep AS tanggal, 
				tbl_apohresep.koders, 
				tbl_apohresep.resepno AS nomor, 
				tbl_apohresep.gudang AS gudang, 
				tbl_apodetresep.kodebarang, 
				0 AS terima, 
				tbl_apodetresep.qtyr AS keluar, 
				tbl_apodetresep.qtyr AS qty, 
				tbl_apodetresep.hpp AS harga, 
				tbl_apohresep.jam AS jam, 
				(SELECT namapas FROM tbl_pasien WHERE rekmed = tbl_apohresep.rekmed) AS rekanan, 
				'PENJUALAN RACIK' AS keterangan, 
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodetresep.kodebarang) AS hpp, 
				(tbl_apodetresep.qtyr*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodetresep.kodebarang)) AS totalhpp 
			FROM tbl_apohresep 
			INNER JOIN tbl_apodetresep ON tbl_apohresep.resepno=tbl_apodetresep.resepno
			
			UNION ALL 
			
			SELECT tbl_apohreturjual.tglretur AS tanggal, 
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
			FROM tbl_apohreturjual 
			INNER JOIN tbl_apodreturjual ON tbl_apohreturjual.returno=tbl_apodreturjual.returno 

			UNION ALL

			SELECT LEFT(tgltransaksi,10) AS tanggal,
				koders, 
				notr AS nomor, 
				gudang AS gudang, 
				kodeobat AS kodebarang, 
				0 AS terima, 
				qty AS keluar, 
				qty AS qty, 
				harga AS harga, 
				RIGHT(tgltransaksi,8) AS jam, 
				'ALKES' AS rekanan, 
				'PENJUALAN ALKES' AS keterangan, 
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_alkestransaksi.kodeobat) AS hpp, 
				(tbl_alkestransaksi.qty*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_alkestransaksi.kodeobat)) AS totalhpp 
			FROM tbl_alkestransaksi 
			
			UNION ALL 
			
			SELECT tbl_apohmove.movedate AS tanggal, 
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
				'MUTASI KELUAR' AS keterangan, 
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodmove.kodebarang) AS hpp, 
				(tbl_apodmove.qtymove*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodmove.kodebarang)) AS totalhpp 
			FROM tbl_apohmove 
			INNER JOIN tbl_apodmove ON tbl_apohmove.moveno=tbl_apodmove.moveno 
			WHERE tbl_apohmove.dari = '$gudang' 
			
			UNION ALL 
			
			SELECT tbl_apohmove.movedate AS tanggal, 
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
				'MUTASI MASUK' AS keterangan, 
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodmove.kodebarang) AS hpp, 
				(tbl_apodmove.qtymove*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodmove.kodebarang)) AS totalhpp 
			FROM tbl_apohmove 
			INNER JOIN tbl_apodmove ON tbl_apohmove.moveno=tbl_apodmove.moveno 
			WHERE tbl_apohmove.ke = '$gudang' 
			
			UNION ALL 
			
			SELECT tbl_apohproduksi.tglproduksi AS tanggal, 
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
			FROM tbl_apohproduksi 
			INNER JOIN tbl_apodproduksi ON tbl_apohproduksi.prdno=tbl_apodproduksi.prdno 
			
			UNION ALL 
			
			SELECT tbl_apohproduksi.tglproduksi AS tanggal, 
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
			
			SELECT tbl_aposesuai.tglso AS tanggal, 
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
					' ', tbl_aposesuai.type ,' 
					[ ', tbl_aposesuai.hasilso ,' - ', tbl_aposesuai.saldo ,' ] '
					) AS keterangan, 
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_aposesuai.kodebarang) AS hpp, 
				(tbl_aposesuai.sesuai*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_aposesuai.kodebarang)) AS totalhpp 
			FROM tbl_aposesuai 
			WHERE approve = 1 
			
			UNION ALL 
			
			SELECT tbl_apohex.tgl_ed AS tanggal, 
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
			FROM tbl_apohex INNER JOIN tbl_apodex ON tbl_apohex.ed_no=tbl_apodex.ed_no WHERE tbl_apohex.approved = 3
			
			UNION ALL 
			
			SELECT tbl_apohpakai.tglbhp AS tanggal, 
				tbl_apohpakai.koders, 
				tbl_apohpakai.nobhp AS nomor, 
				tbl_apohpakai.gudang AS gudang, 
				tbl_apodpakai.kodeobat AS kodebarang, 
				0 AS terima, 
				tbl_apodpakai.qty AS keluar, 
				tbl_apodpakai.qty AS qty, 
				tbl_apodpakai.hpp AS harga, 
				tbl_apohpakai.jampakai AS jam, 
				tbl_apohpakai.pro AS rekanan, 
				'BARANG HABIS PAKAI' AS keterangan, 
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodpakai.kodeobat) AS hpp, 
				(tbl_apodpakai.qty*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodpakai.kodeobat)) AS totalhpp 
			FROM tbl_apohpakai 
			INNER JOIN tbl_apodpakai ON tbl_apohpakai.nobhp=tbl_apodpakai.nobhp 
		) mutasi 
		WHERE kodebarang ='$barang' AND koders ='$cabang' AND
		gudang = '$gudang' AND tanggal BETWEEN '$tgl1' AND '$tgl2' ORDER BY tanggal, jam ASC";
		return $this->db->query($mutasi)->result();
	}

	public function cekstok($cabang, $barang, $gudang, $tgl1)
	{
		$mutasi = 
		"SELECT * FROM(
			SELECT tbl_baranghterima.terima_date AS tanggal, 
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
			FROM tbl_baranghterima 
			INNER JOIN tbl_barangdterima ON tbl_baranghterima.terima_no=tbl_barangdterima.terima_no 
			INNER JOIN tbl_vendor ON tbl_baranghterima.vendor_id=tbl_vendor.vendor_id 
			
			UNION ALL 
			
			SELECT tbl_baranghreturbeli.retur_date AS tanggal, 
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
			FROM tbl_baranghreturbeli 
			INNER JOIN tbl_barangdreturbeli ON tbl_baranghreturbeli.retur_no=tbl_barangdreturbeli.retur_no 
			
			UNION ALL 
			
			SELECT tbl_apohresep.tglresep AS tanggal, 
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
				'PENJUALAN RESEP' AS keterangan, 
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodresep.kodebarang) AS hpp, 
				(tbl_apodresep.qty*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodresep.kodebarang)) AS totalhpp 
			FROM tbl_apohresep 
			INNER JOIN tbl_apodresep ON tbl_apohresep.resepno=tbl_apodresep.resepno 
			
			UNION ALL 
			
			SELECT tbl_apohresep.tglresep AS tanggal, 
				tbl_apohresep.koders, 
				tbl_apohresep.resepno AS nomor, 
				tbl_apohresep.gudang AS gudang, 
				tbl_apodetresep.kodebarang, 
				0 AS terima, 
				tbl_apodetresep.qtyr AS keluar, 
				tbl_apodetresep.qtyr AS qty, 
				tbl_apodetresep.hpp AS harga, 
				tbl_apohresep.jam AS jam, 
				(SELECT namapas FROM tbl_pasien WHERE rekmed = tbl_apohresep.rekmed) AS rekanan, 
				'PENJUALAN RACIK' AS keterangan, 
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodetresep.kodebarang) AS hpp, 
				(tbl_apodetresep.qtyr*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodetresep.kodebarang)) AS totalhpp 
			FROM tbl_apohresep 
			INNER JOIN tbl_apodetresep ON tbl_apohresep.resepno=tbl_apodetresep.resepno
			
			UNION ALL 
			
			SELECT tbl_apohreturjual.tglretur AS tanggal, 
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
			FROM tbl_apohreturjual 
			INNER JOIN tbl_apodreturjual ON tbl_apohreturjual.returno=tbl_apodreturjual.returno 

			UNION ALL

			SELECT LEFT(tgltransaksi,10) AS tanggal,
				koders, 
				notr AS nomor, 
				gudang AS gudang, 
				kodeobat AS kodebarang, 
				0 AS terima, 
				qty AS keluar, 
				qty AS qty, 
				harga AS harga, 
				RIGHT(tgltransaksi,8) AS jam, 
				'ALKES' AS rekanan, 
				'PENJUALAN ALKES' AS keterangan, 
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_alkestransaksi.kodeobat) AS hpp, 
				(tbl_alkestransaksi.qty*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_alkestransaksi.kodeobat)) AS totalhpp 
			FROM tbl_alkestransaksi 
			
			UNION ALL 
			
			SELECT tbl_apohmove.movedate AS tanggal, 
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
				'MUTASI KELUAR' AS keterangan, 
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodmove.kodebarang) AS hpp, 
				(tbl_apodmove.qtymove*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodmove.kodebarang)) AS totalhpp 
			FROM tbl_apohmove 
			INNER JOIN tbl_apodmove ON tbl_apohmove.moveno=tbl_apodmove.moveno 
			WHERE tbl_apohmove.dari = '$gudang' 
			
			UNION ALL 
			
			SELECT tbl_apohmove.movedate AS tanggal, 
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
				'MUTASI MASUK' AS keterangan, 
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodmove.kodebarang) AS hpp, 
				(tbl_apodmove.qtymove*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodmove.kodebarang)) AS totalhpp 
			FROM tbl_apohmove 
			INNER JOIN tbl_apodmove ON tbl_apohmove.moveno=tbl_apodmove.moveno 
			WHERE tbl_apohmove.ke = '$gudang' 
			
			UNION ALL 
			
			SELECT tbl_apohproduksi.tglproduksi AS tanggal, 
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
			FROM tbl_apohproduksi 
			INNER JOIN tbl_apodproduksi ON tbl_apohproduksi.prdno=tbl_apodproduksi.prdno 
			
			UNION ALL 
			
			SELECT tbl_apohproduksi.tglproduksi AS tanggal, 
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
			
			SELECT tbl_aposesuai.tglso AS tanggal, 
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
					' ', tbl_aposesuai.type ,' 
					[ ', tbl_aposesuai.hasilso ,' - ', tbl_aposesuai.saldo ,' ] '
					) AS keterangan, 
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_aposesuai.kodebarang) AS hpp, 
				(tbl_aposesuai.sesuai*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_aposesuai.kodebarang)) AS totalhpp 
			FROM tbl_aposesuai 
			WHERE approve = 1 
			
			UNION ALL 
			
			SELECT tbl_apohex.tgl_ed AS tanggal, 
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
			FROM tbl_apohex INNER JOIN tbl_apodex ON tbl_apohex.ed_no=tbl_apodex.ed_no WHERE tbl_apohex.approved = 3
			
			UNION ALL 
			
			SELECT tbl_apohpakai.tglbhp AS tanggal, 
				tbl_apohpakai.koders, 
				tbl_apohpakai.nobhp AS nomor, 
				tbl_apohpakai.gudang AS gudang, 
				tbl_apodpakai.kodeobat AS kodebarang, 
				0 AS terima, 
				tbl_apodpakai.qty AS keluar, 
				tbl_apodpakai.qty AS qty, 
				tbl_apodpakai.hpp AS harga, 
				tbl_apohpakai.jampakai AS jam, 
				tbl_apohpakai.pro AS rekanan, 
				'BARANG HABIS PAKAI' AS keterangan, 
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodpakai.kodeobat) AS hpp, 
				(tbl_apodpakai.qty*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodpakai.kodeobat)) AS totalhpp 
			FROM tbl_apohpakai 
			INNER JOIN tbl_apodpakai ON tbl_apohpakai.nobhp=tbl_apodpakai.nobhp 
		) mutasi 
		WHERE kodebarang ='$barang' AND koders ='$cabang' AND
		gudang = '$gudang' AND tanggal < '$tgl1' ORDER BY tanggal, jam ASC";
		return $this->db->query($mutasi)->result();
	}

	public function farmasistok($id)
	{
		$brg = $this->db->get_where('tbl_barangstock', ['id' => $id])->row();
		$mutasi =
		"SELECT * FROM(
			SELECT tbl_baranghterima.terima_date AS tanggal, 
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
			FROM tbl_baranghterima 
			INNER JOIN tbl_barangdterima ON tbl_baranghterima.terima_no=tbl_barangdterima.terima_no 
			INNER JOIN tbl_vendor ON tbl_baranghterima.vendor_id=tbl_vendor.vendor_id 
			
			UNION ALL 
			
			SELECT tbl_baranghreturbeli.retur_date AS tanggal, 
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
			FROM tbl_baranghreturbeli 
			INNER JOIN tbl_barangdreturbeli ON tbl_baranghreturbeli.retur_no=tbl_barangdreturbeli.retur_no 
			
			UNION ALL 
			
			SELECT tbl_apohresep.tglresep AS tanggal, 
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
				'PENJUALAN RESEP' AS keterangan, 
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodresep.kodebarang) AS hpp, 
				(tbl_apodresep.qty*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodresep.kodebarang)) AS totalhpp 
			FROM tbl_apohresep 
			INNER JOIN tbl_apodresep ON tbl_apohresep.resepno=tbl_apodresep.resepno 
			
			UNION ALL 
			
			SELECT tbl_apohresep.tglresep AS tanggal, 
				tbl_apohresep.koders, 
				tbl_apohresep.resepno AS nomor, 
				tbl_apohresep.gudang AS gudang, 
				tbl_apodetresep.kodebarang, 
				0 AS terima, 
				tbl_apodetresep.qtyr AS keluar, 
				tbl_apodetresep.qtyr AS qty, 
				tbl_apodetresep.hpp AS harga, 
				tbl_apohresep.jam AS jam, 
				(SELECT namapas FROM tbl_pasien WHERE rekmed = tbl_apohresep.rekmed) AS rekanan, 
				'PENJUALAN RACIK' AS keterangan, 
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodetresep.kodebarang) AS hpp, 
				(tbl_apodetresep.qtyr*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodetresep.kodebarang)) AS totalhpp 
			FROM tbl_apohresep 
			INNER JOIN tbl_apodetresep ON tbl_apohresep.resepno=tbl_apodetresep.resepno
			
			UNION ALL 
			
			SELECT tbl_apohreturjual.tglretur AS tanggal, 
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
			FROM tbl_apohreturjual 
			INNER JOIN tbl_apodreturjual ON tbl_apohreturjual.returno=tbl_apodreturjual.returno 

			UNION ALL

			SELECT LEFT(tgltransaksi,10) AS tanggal,
				koders, 
				notr AS nomor, 
				gudang AS gudang, 
				kodeobat AS kodebarang, 
				0 AS terima, 
				qty AS keluar, 
				qty AS qty, 
				harga AS harga, 
				RIGHT(tgltransaksi,8) AS jam, 
				'ALKES' AS rekanan, 
				'PENJUALAN ALKES' AS keterangan, 
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_alkestransaksi.kodeobat) AS hpp, 
				(tbl_alkestransaksi.qty*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_alkestransaksi.kodeobat)) AS totalhpp 
			FROM tbl_alkestransaksi 
			
			UNION ALL 
			
			SELECT tbl_apohmove.movedate AS tanggal, 
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
				'MUTASI KELUAR' AS keterangan, 
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodmove.kodebarang) AS hpp, 
				(tbl_apodmove.qtymove*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodmove.kodebarang)) AS totalhpp 
			FROM tbl_apohmove 
			INNER JOIN tbl_apodmove ON tbl_apohmove.moveno=tbl_apodmove.moveno 
			WHERE tbl_apohmove.dari = '$brg->gudang' 
			
			UNION ALL 
			
			SELECT tbl_apohmove.movedate AS tanggal, 
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
				'MUTASI MASUK' AS keterangan, 
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodmove.kodebarang) AS hpp, 
				(tbl_apodmove.qtymove*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodmove.kodebarang)) AS totalhpp 
			FROM tbl_apohmove 
			INNER JOIN tbl_apodmove ON tbl_apohmove.moveno=tbl_apodmove.moveno 
			WHERE tbl_apohmove.ke = '$brg->gudang' 
			
			UNION ALL 
			
			SELECT tbl_apohproduksi.tglproduksi AS tanggal, 
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
			FROM tbl_apohproduksi 
			INNER JOIN tbl_apodproduksi ON tbl_apohproduksi.prdno=tbl_apodproduksi.prdno 
			
			UNION ALL 
			
			SELECT tbl_apohproduksi.tglproduksi AS tanggal, 
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
			
			SELECT tbl_aposesuai.tglso AS tanggal, 
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
					' ', tbl_aposesuai.type ,' 
					[ ', tbl_aposesuai.hasilso ,' - ', tbl_aposesuai.saldo ,' ] '
					) AS keterangan, 
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_aposesuai.kodebarang) AS hpp, 
				(tbl_aposesuai.sesuai*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_aposesuai.kodebarang)) AS totalhpp 
			FROM tbl_aposesuai 
			WHERE approve = 1 
			
			UNION ALL 
			
			SELECT tbl_apohex.tgl_ed AS tanggal, 
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
			FROM tbl_apohex INNER JOIN tbl_apodex ON tbl_apohex.ed_no=tbl_apodex.ed_no WHERE tbl_apohex.approved = 3
			
			UNION ALL 
			
			SELECT tbl_apohpakai.tglbhp AS tanggal, 
				tbl_apohpakai.koders, 
				tbl_apohpakai.nobhp AS nomor, 
				tbl_apohpakai.gudang AS gudang, 
				tbl_apodpakai.kodeobat AS kodebarang, 
				0 AS terima, 
				tbl_apodpakai.qty AS keluar, 
				tbl_apodpakai.qty AS qty, 
				tbl_apodpakai.hpp AS harga, 
				tbl_apohpakai.jampakai AS jam, 
				tbl_apohpakai.pro AS rekanan, 
				'BARANG HABIS PAKAI' AS keterangan, 
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodpakai.kodeobat) AS hpp, 
				(tbl_apodpakai.qty*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodpakai.kodeobat)) AS totalhpp 
			FROM tbl_apohpakai 
			INNER JOIN tbl_apodpakai ON tbl_apohpakai.nobhp=tbl_apodpakai.nobhp 
		) mutasi 
			where 
			kodebarang ='$brg->kodebarang' and koders ='$brg->koders' and
			gudang = '$brg->gudang' order by tanggal, jam ASC";
		return $this->db->query($mutasi)->result();
	}

	public function update_stok($kodebarang, $gudang, $cabang)
	{
		$mutasi =
		"SELECT * FROM(
			SELECT tbl_baranghterima.terima_date AS tanggal, 
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
			FROM tbl_baranghterima 
			INNER JOIN tbl_barangdterima ON tbl_baranghterima.terima_no=tbl_barangdterima.terima_no 
			INNER JOIN tbl_vendor ON tbl_baranghterima.vendor_id=tbl_vendor.vendor_id 
			
			UNION ALL 
			
			SELECT tbl_baranghreturbeli.retur_date AS tanggal, 
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
			FROM tbl_baranghreturbeli 
			INNER JOIN tbl_barangdreturbeli ON tbl_baranghreturbeli.retur_no=tbl_barangdreturbeli.retur_no 
			
			UNION ALL 
			
			SELECT tbl_apohresep.tglresep AS tanggal, 
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
				'PENJUALAN RESEP' AS keterangan, 
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodresep.kodebarang) AS hpp, 
				(tbl_apodresep.qty*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodresep.kodebarang)) AS totalhpp 
			FROM tbl_apohresep 
			INNER JOIN tbl_apodresep ON tbl_apohresep.resepno=tbl_apodresep.resepno 
			
			UNION ALL 
			
			SELECT tbl_apohresep.tglresep AS tanggal, 
				tbl_apohresep.koders, 
				tbl_apohresep.resepno AS nomor, 
				tbl_apohresep.gudang AS gudang, 
				tbl_apodetresep.kodebarang, 
				0 AS terima, 
				tbl_apodetresep.qtyr AS keluar, 
				tbl_apodetresep.qtyr AS qty, 
				tbl_apodetresep.hpp AS harga, 
				tbl_apohresep.jam AS jam, 
				(SELECT namapas FROM tbl_pasien WHERE rekmed = tbl_apohresep.rekmed) AS rekanan, 
				'PENJUALAN RACIK' AS keterangan, 
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodetresep.kodebarang) AS hpp, 
				(tbl_apodetresep.qtyr*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodetresep.kodebarang)) AS totalhpp 
			FROM tbl_apohresep 
			INNER JOIN tbl_apodetresep ON tbl_apohresep.resepno=tbl_apodetresep.resepno
			
			UNION ALL 
			
			SELECT tbl_apohreturjual.tglretur AS tanggal, 
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
			FROM tbl_apohreturjual 
			INNER JOIN tbl_apodreturjual ON tbl_apohreturjual.returno=tbl_apodreturjual.returno 

			UNION ALL

			SELECT LEFT(tgltransaksi,10) AS tanggal,
				koders, 
				notr AS nomor, 
				gudang AS gudang, 
				kodeobat AS kodebarang, 
				0 AS terima, 
				qty AS keluar, 
				qty AS qty, 
				harga AS harga, 
				RIGHT(tgltransaksi,8) AS jam, 
				'ALKES' AS rekanan, 
				'PENJUALAN ALKES' AS keterangan, 
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_alkestransaksi.kodeobat) AS hpp, 
				(tbl_alkestransaksi.qty*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_alkestransaksi.kodeobat)) AS totalhpp 
			FROM tbl_alkestransaksi 
			
			UNION ALL 
			
			SELECT tbl_apohmove.movedate AS tanggal, 
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
				'MUTASI KELUAR' AS keterangan, 
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodmove.kodebarang) AS hpp, 
				(tbl_apodmove.qtymove*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodmove.kodebarang)) AS totalhpp 
			FROM tbl_apohmove 
			INNER JOIN tbl_apodmove ON tbl_apohmove.moveno=tbl_apodmove.moveno 
			WHERE tbl_apohmove.dari = '$gudang' 
			
			UNION ALL 
			
			SELECT tbl_apohmove.movedate AS tanggal, 
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
				'MUTASI MASUK' AS keterangan, 
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodmove.kodebarang) AS hpp, 
				(tbl_apodmove.qtymove*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodmove.kodebarang)) AS totalhpp 
			FROM tbl_apohmove 
			INNER JOIN tbl_apodmove ON tbl_apohmove.moveno=tbl_apodmove.moveno 
			WHERE tbl_apohmove.ke = '$gudang' 
			
			UNION ALL 
			
			SELECT tbl_apohproduksi.tglproduksi AS tanggal, 
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
			FROM tbl_apohproduksi 
			INNER JOIN tbl_apodproduksi ON tbl_apohproduksi.prdno=tbl_apodproduksi.prdno 
			
			UNION ALL 
			
			SELECT tbl_apohproduksi.tglproduksi AS tanggal, 
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
			
			SELECT tbl_aposesuai.tglso AS tanggal, 
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
					' ', tbl_aposesuai.type ,' 
					[ ', tbl_aposesuai.hasilso ,' - ', tbl_aposesuai.saldo ,' ] '
					) AS keterangan, 
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_aposesuai.kodebarang) AS hpp, 
				(tbl_aposesuai.sesuai*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_aposesuai.kodebarang)) AS totalhpp 
			FROM tbl_aposesuai 
			WHERE approve = 1 
			
			UNION ALL 
			
			SELECT tbl_apohex.tgl_ed AS tanggal, 
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
			FROM tbl_apohex INNER JOIN tbl_apodex ON tbl_apohex.ed_no=tbl_apodex.ed_no WHERE tbl_apohex.approved = 3
			
			UNION ALL 
			
			SELECT tbl_apohpakai.tglbhp AS tanggal, 
				tbl_apohpakai.koders, 
				tbl_apohpakai.nobhp AS nomor, 
				tbl_apohpakai.gudang AS gudang, 
				tbl_apodpakai.kodeobat AS kodebarang, 
				0 AS terima, 
				tbl_apodpakai.qty AS keluar, 
				tbl_apodpakai.qty AS qty, 
				tbl_apodpakai.hpp AS harga, 
				tbl_apohpakai.jampakai AS jam, 
				tbl_apohpakai.pro AS rekanan, 
				'BARANG HABIS PAKAI' AS keterangan, 
				(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodpakai.kodeobat) AS hpp, 
				(tbl_apodpakai.qty*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodpakai.kodeobat)) AS totalhpp 
			FROM tbl_apohpakai 
			INNER JOIN tbl_apodpakai ON tbl_apohpakai.nobhp=tbl_apodpakai.nobhp 
		) mutasi 
			where 
			kodebarang ='$kodebarang' and koders ='$cabang' and
			gudang = '$gudang' order by tanggal, jam ASC";
		return $this->db->query($mutasi)->result();
	}

	public function cekstoklog($cabang, $barang, $gudang, $tgl1) {
		$mutasi =
		"SELECT * FROM(
			SELECT tbl_apohterimalog.terima_date AS tanggal, 
				tbl_apohterimalog.koders, 
				tbl_apohterimalog.terima_no AS nomor, 
				tbl_apohterimalog.gudang, 
				tbl_apodterimalog.kodebarang, 
				tbl_apodterimalog.qty_terima AS terima, 
				0 keluar, 
				tbl_apodterimalog.qty_terima AS qty, 
				tbl_apodterimalog.price AS harga, 
				tbl_apohterimalog.jamterima AS jam, 
				tbl_vendor.vendor_name AS rekanan, 
				'PEMBELIAN' AS keterangan, 
				tbl_apodterimalog.totalrp as hpp,
				(tbl_apodterimalog.qty_terima*(SELECT hpp FROM tbl_logbarang WHERE kodebarang=tbl_apodterimalog.kodebarang)) AS totalhpp 
			FROM tbl_apohterimalog 
			INNER JOIN tbl_apodterimalog ON tbl_apohterimalog.terima_no=tbl_apodterimalog.terima_no 
			INNER JOIN tbl_vendor ON tbl_apohterimalog.vendor_id=tbl_vendor.vendor_id 
			
			UNION ALL 
			
			SELECT tbl_apohreturbelilog.retur_date AS tanggal, 
				tbl_apohreturbelilog.koders, 
				tbl_apohreturbelilog.retur_no AS nomor, 
				tbl_apohreturbelilog.gudang AS gudang, 
				tbl_apodreturbelilog.kodebarang, 
				0 AS terima, 
				tbl_apodreturbelilog.qty_retur AS keluar, 
				tbl_apodreturbelilog.qty_retur AS qty, 
				tbl_apodreturbelilog.price AS harga, 
				tbl_apohreturbelilog.jamretur AS jam, 
				tbl_apohreturbelilog.vendor_id AS rekanan, 
				'RETUR PEMBELIAN' AS keterangan, 
				tbl_apodreturbelilog.totalrp as hpp,
				(tbl_apodreturbelilog.qty_retur*(SELECT hpp FROM tbl_logbarang WHERE kodebarang=tbl_apodreturbelilog.kodebarang)) AS totalhpp 
			FROM tbl_apohreturbelilog 
			INNER JOIN tbl_apodreturbelilog ON tbl_apohreturbelilog.retur_no=tbl_apodreturbelilog.retur_no 
			
			UNION ALL 
			
			SELECT tbl_apohresep.tglresep AS tanggal, 
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
				'PENJUALAN RESEP' AS keterangan, 
				if(tbl_apodresep.hna = 0 AND tbl_apodresep.hpp = 0, 0, if(tbl_apodresep.hpp = 0, tbl_apodresep.hna, tbl_apodresep.hpp)) as hpp,
				(tbl_apodresep.qty*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodresep.kodebarang)) AS totalhpp 
			FROM tbl_apohresep 
			INNER JOIN tbl_apodresep ON tbl_apohresep.resepno=tbl_apodresep.resepno 
			
			UNION ALL 
			
			SELECT tbl_apohresep.tglresep AS tanggal, 
				tbl_apohresep.koders, 
				tbl_apohresep.resepno AS nomor, 
				tbl_apohresep.gudang AS gudang, 
				tbl_apodetresep.kodebarang, 
				0 AS terima, 
				tbl_apodetresep.qtyr AS keluar, 
				tbl_apodetresep.qtyr AS qty, 
				tbl_apodetresep.hpp AS harga, 
				tbl_apohresep.jam AS jam, 
				(SELECT namapas FROM tbl_pasien WHERE rekmed = tbl_apohresep.rekmed) AS rekanan, 
				'PENJUALAN RACIK' AS keterangan, 
				tbl_apodetresep.hpp as hpp,
				(tbl_apodetresep.qtyr*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodetresep.kodebarang)) AS totalhpp 
			FROM tbl_apohresep 
			INNER JOIN tbl_apodetresep ON tbl_apohresep.resepno=tbl_apodetresep.resepno 
			
			UNION ALL 
			
			SELECT tbl_apohreturjual.tglretur AS tanggal, 
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
				tbl_apodreturjual.totalrp as hpp,
				(tbl_apodreturjual.qtyretur*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodreturjual.kodebarang)) AS totalhpp 
			FROM tbl_apohreturjual 
			INNER JOIN tbl_apodreturjual ON tbl_apohreturjual.returno=tbl_apodreturjual.returno 
			
			UNION ALL 
			
			SELECT tbl_pakaihlog.pakaidate AS tanggal, 
				tbl_pakaihlog.koders, 
				tbl_pakaihlog.pakaino AS nomor, 
				tbl_pakaihlog.gudang AS gudang, 
				tbl_pakaidlog.kodebarang, 
				0 AS terima, 
				tbl_pakaidlog.qty AS keluar, 
				tbl_pakaidlog.qty AS qty, 
				tbl_pakaidlog.harga AS harga, 
				tbl_pakaihlog.jampakai AS jam, 
				'PAKAI' AS rekanan, 
				'PAKAI LOG' AS keterangan, 
				(SELECT hpp FROM tbl_logbarang WHERE kodebarang = tbl_pakaidlog.kodebarang) AS hpp,
				(tbl_pakaidlog.qty*(SELECT hpp FROM tbl_logbarang WHERE kodebarang=tbl_pakaidlog.kodebarang)) AS totalhpp 
			FROM tbl_pakaihlog 
			INNER JOIN tbl_pakaidlog ON tbl_pakaihlog.pakaino=tbl_pakaidlog.pakaino 
			
			UNION ALL 
			
			SELECT tbl_apohmovelog.movedate AS tanggal, 
				tbl_apohmovelog.koders, 
				tbl_apohmovelog.moveno AS nomor, 
				tbl_apohmovelog.dari AS gudang, 
				tbl_apodmovelog.kodebarang, 
				0 AS terima, 
				tbl_apodmovelog.qtymove AS keluar, 
				tbl_apodmovelog.qtymove AS qty, 
				tbl_apodmovelog.harga AS harga, 
				tbl_apohmovelog.jammutasi AS jam, 
				tbl_apohmovelog.mohonno AS rekanan, 
				'MUTASI KELUAR' AS keterangan, 
				tbl_apodmovelog.totalharga as hpp,
				(tbl_apodmovelog.qtymove*(SELECT hpp FROM tbl_logbarang WHERE kodebarang=tbl_apodmovelog.kodebarang)) AS totalhpp 
			FROM tbl_apohmovelog 
			INNER JOIN tbl_apodmovelog ON tbl_apohmovelog.moveno=tbl_apodmovelog.moveno 
			WHERE tbl_apohmovelog.dari = '$gudang' 
			
			UNION ALL 
			
			SELECT tbl_apohmovelog.movedate AS tanggal, 
				tbl_apohmovelog.koders, 
				tbl_apohmovelog.moveno AS nomor, 
				tbl_apohmovelog.ke AS gudang, 
				tbl_apodmovelog.kodebarang, 
				tbl_apodmovelog.qtymove AS terima, 
				0 AS keluar, 
				tbl_apodmovelog.qtymove AS qty, 
				tbl_apodmovelog.harga AS harga, 
				tbl_apohmovelog.jammutasi AS jam, 
				tbl_apohmovelog.mohonno AS rekanan, 
				'MUTASI MASUK' AS keterangan, 
				tbl_apodmovelog.totalharga as hpp,
				(tbl_apodmovelog.qtymove*(SELECT hpp FROM tbl_logbarang WHERE kodebarang=tbl_apodmovelog.kodebarang)) AS totalhpp 
			FROM tbl_apohmovelog 
			INNER JOIN tbl_apodmovelog ON tbl_apohmovelog.moveno=tbl_apodmovelog.moveno 
			WHERE tbl_apohmovelog.ke = '$gudang' 
			
			UNION ALL 
			
			SELECT tbl_aposesuailog.tglso AS tanggal, 
				tbl_aposesuailog.koders, 
				'-' AS nomor, 
				tbl_aposesuailog.gudang AS gudang, 
				tbl_aposesuailog.kodeobat as kodebarang, 
				tbl_aposesuailog.sesuai AS terima, 
				0 AS keluar, 
				tbl_aposesuailog.sesuai AS qty, 
				tbl_aposesuailog.hpp AS harga, 
				tbl_aposesuailog.jamentry AS jam, 
				tbl_aposesuailog.type AS rekanan, 
				CONCAT( 
					' ', tbl_aposesuailog.type ,' 
					[ ', tbl_aposesuailog.hasilso ,' - ', tbl_aposesuailog.saldo ,' ] '
					) AS keterangan, 
				tbl_aposesuailog.hpp,
				(tbl_aposesuailog.sesuai*(SELECT hpp FROM tbl_logbarang WHERE kodebarang=tbl_aposesuailog.kodeobat)) AS totalhpp 
			FROM tbl_aposesuailog 
			WHERE approve = 1 
		) mutasi 
		WHERE kodebarang ='$barang' AND koders ='$cabang' AND
		gudang = '$gudang' AND tanggal < '$tgl1' ORDER BY tanggal, jam ASC";
		return $this->db->query($mutasi)->result();
	}

	public function tgllog($cabang, $barang, $gudang, $tgl1, $tgl2){
		$mutasi =
		"SELECT * FROM(
			SELECT tbl_apohterimalog.terima_date AS tanggal, 
				tbl_apohterimalog.koders, 
				tbl_apohterimalog.terima_no AS nomor, 
				tbl_apohterimalog.gudang, 
				tbl_apodterimalog.kodebarang, 
				tbl_apodterimalog.qty_terima AS terima, 
				0 keluar, 
				tbl_apodterimalog.qty_terima AS qty, 
				tbl_apodterimalog.price AS harga, 
				tbl_apohterimalog.jamterima AS jam, 
				tbl_vendor.vendor_name AS rekanan, 
				'PEMBELIAN' AS keterangan, 
				tbl_apodterimalog.totalrp as hpp,
				(tbl_apodterimalog.qty_terima*(SELECT hpp FROM tbl_logbarang WHERE kodebarang=tbl_apodterimalog.kodebarang)) AS totalhpp 
			FROM tbl_apohterimalog 
			INNER JOIN tbl_apodterimalog ON tbl_apohterimalog.terima_no=tbl_apodterimalog.terima_no 
			INNER JOIN tbl_vendor ON tbl_apohterimalog.vendor_id=tbl_vendor.vendor_id 
			
			UNION ALL 
			
			SELECT tbl_apohreturbelilog.retur_date AS tanggal, 
				tbl_apohreturbelilog.koders, 
				tbl_apohreturbelilog.retur_no AS nomor, 
				tbl_apohreturbelilog.gudang AS gudang, 
				tbl_apodreturbelilog.kodebarang, 
				0 AS terima, 
				tbl_apodreturbelilog.qty_retur AS keluar, 
				tbl_apodreturbelilog.qty_retur AS qty, 
				tbl_apodreturbelilog.price AS harga, 
				tbl_apohreturbelilog.jamretur AS jam, 
				tbl_apohreturbelilog.vendor_id AS rekanan, 
				'RETUR PEMBELIAN' AS keterangan, 
				tbl_apodreturbelilog.totalrp as hpp,
				(tbl_apodreturbelilog.qty_retur*(SELECT hpp FROM tbl_logbarang WHERE kodebarang=tbl_apodreturbelilog.kodebarang)) AS totalhpp 
			FROM tbl_apohreturbelilog 
			INNER JOIN tbl_apodreturbelilog ON tbl_apohreturbelilog.retur_no=tbl_apodreturbelilog.retur_no 
			
			UNION ALL 
			
			SELECT tbl_pakaihlog.pakaidate AS tanggal, 
				tbl_pakaihlog.koders, 
				tbl_pakaihlog.pakaino AS nomor, 
				tbl_pakaihlog.gudang AS gudang, 
				tbl_pakaidlog.kodebarang, 
				0 AS terima, 
				tbl_pakaidlog.qty AS keluar, 
				tbl_pakaidlog.qty AS qty, 
				tbl_pakaidlog.harga AS harga, 
				tbl_pakaihlog.jampakai AS jam, 
				'PAKAI' AS rekanan, 
				'PAKAI LOG' AS keterangan, 
				(SELECT hpp FROM tbl_logbarang WHERE kodebarang = tbl_pakaidlog.kodebarang) AS hpp,
				(tbl_pakaidlog.qty*(SELECT hpp FROM tbl_logbarang WHERE kodebarang=tbl_pakaidlog.kodebarang)) AS totalhpp 
			FROM tbl_pakaihlog 
			INNER JOIN tbl_pakaidlog ON tbl_pakaihlog.pakaino=tbl_pakaidlog.pakaino 
			
			UNION ALL 
			
			SELECT tbl_apohresep.tglresep AS tanggal, 
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
				'PENJUALAN RESEP' AS keterangan, 
				if(tbl_apodresep.hna = 0 AND tbl_apodresep.hpp = 0, 0, if(tbl_apodresep.hpp = 0, tbl_apodresep.hna, tbl_apodresep.hpp)) as hpp,
				(tbl_apodresep.qty*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodresep.kodebarang)) AS totalhpp 
			FROM tbl_apohresep 
			INNER JOIN tbl_apodresep ON tbl_apohresep.resepno=tbl_apodresep.resepno 
			
			UNION ALL 
			
			SELECT tbl_apohresep.tglresep AS tanggal, 
				tbl_apohresep.koders, 
				tbl_apohresep.resepno AS nomor, 
				tbl_apohresep.gudang AS gudang, 
				tbl_apodetresep.kodebarang, 
				0 AS terima, 
				tbl_apodetresep.qtyr AS keluar, 
				tbl_apodetresep.qtyr AS qty, 
				tbl_apodetresep.hpp AS harga, 
				tbl_apohresep.jam AS jam, 
				(SELECT namapas FROM tbl_pasien WHERE rekmed = tbl_apohresep.rekmed) AS rekanan, 
				'PENJUALAN RACIK' AS keterangan, 
				tbl_apodetresep.hpp as hpp,
				(tbl_apodetresep.qtyr*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodetresep.kodebarang)) AS totalhpp 
			FROM tbl_apohresep 
			INNER JOIN tbl_apodetresep ON tbl_apohresep.resepno=tbl_apodetresep.resepno 
			
			UNION ALL 
			
			SELECT tbl_apohreturjual.tglretur AS tanggal, 
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
				tbl_apodreturjual.totalrp as hpp,
				(tbl_apodreturjual.qtyretur*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodreturjual.kodebarang)) AS totalhpp 
			FROM tbl_apohreturjual 
			INNER JOIN tbl_apodreturjual ON tbl_apohreturjual.returno=tbl_apodreturjual.returno 
			
			UNION ALL 
			
			SELECT tbl_apohmovelog.movedate AS tanggal, 
				tbl_apohmovelog.koders, 
				tbl_apohmovelog.moveno AS nomor, 
				tbl_apohmovelog.dari AS gudang, 
				tbl_apodmovelog.kodebarang, 
				0 AS terima, 
				tbl_apodmovelog.qtymove AS keluar, 
				tbl_apodmovelog.qtymove AS qty, 
				tbl_apodmovelog.harga AS harga, 
				tbl_apohmovelog.jammutasi AS jam, 
				tbl_apohmovelog.mohonno AS rekanan, 
				'MUTASI KELUAR' AS keterangan, 
				tbl_apodmovelog.totalharga as hpp,
				(tbl_apodmovelog.qtymove*(SELECT hpp FROM tbl_logbarang WHERE kodebarang=tbl_apodmovelog.kodebarang)) AS totalhpp 
			FROM tbl_apohmovelog 
			INNER JOIN tbl_apodmovelog ON tbl_apohmovelog.moveno=tbl_apodmovelog.moveno 
			WHERE tbl_apohmovelog.dari = '$gudang' 
			
			UNION ALL 
			
			SELECT tbl_apohmovelog.movedate AS tanggal, 
				tbl_apohmovelog.koders, 
				tbl_apohmovelog.moveno AS nomor, 
				tbl_apohmovelog.ke AS gudang, 
				tbl_apodmovelog.kodebarang, 
				tbl_apodmovelog.qtymove AS terima, 
				0 AS keluar, 
				tbl_apodmovelog.qtymove AS qty, 
				tbl_apodmovelog.harga AS harga, 
				tbl_apohmovelog.jammutasi AS jam, 
				tbl_apohmovelog.mohonno AS rekanan, 
				'MUTASI MASUK' AS keterangan, 
				tbl_apodmovelog.totalharga as hpp,
				(tbl_apodmovelog.qtymove*(SELECT hpp FROM tbl_logbarang WHERE kodebarang=tbl_apodmovelog.kodebarang)) AS totalhpp 
			FROM tbl_apohmovelog 
			INNER JOIN tbl_apodmovelog ON tbl_apohmovelog.moveno=tbl_apodmovelog.moveno 
			WHERE tbl_apohmovelog.ke = '$gudang' 
			
			UNION ALL 
			
			SELECT tbl_aposesuailog.tglso AS tanggal, 
				tbl_aposesuailog.koders, 
				'-' AS nomor, 
				tbl_aposesuailog.gudang AS gudang, 
				tbl_aposesuailog.kodeobat as kodebarang, 
				tbl_aposesuailog.sesuai AS terima, 
				0 AS keluar, 
				tbl_aposesuailog.sesuai AS qty, 
				tbl_aposesuailog.hpp AS harga, 
				tbl_aposesuailog.jamentry AS jam, 
				tbl_aposesuailog.type AS rekanan, 
				CONCAT( 
					' ', tbl_aposesuailog.type ,' 
					[ ', tbl_aposesuailog.hasilso ,' - ', tbl_aposesuailog.saldo ,' ] '
					) AS keterangan, 
				tbl_aposesuailog.hpp,
				(tbl_aposesuailog.sesuai*(SELECT hpp FROM tbl_logbarang WHERE kodebarang=tbl_aposesuailog.kodeobat)) AS totalhpp 
			FROM tbl_aposesuailog 
			WHERE approve = 1 
		) mutasi 
		WHERE kodebarang ='$barang' AND koders ='$cabang' AND
		gudang = '$gudang' AND tanggal BETWEEN '$tgl1' AND '$tgl2' ORDER BY tanggal, jam ASC";
		return $this->db->query($mutasi)->result();
	}

	public function logistikstok($id)
	{
		$brg = $this->db->get_where('tbl_apostocklog', ['id' => $id])->row();
		$mutasi =
		"SELECT * FROM(
			SELECT tbl_apohterimalog.terima_date AS tanggal, 
				tbl_apohterimalog.koders, 
				tbl_apohterimalog.terima_no AS nomor, 
				tbl_apohterimalog.gudang, 
				tbl_apodterimalog.kodebarang, 
				tbl_apodterimalog.qty_terima AS terima, 
				0 keluar, 
				tbl_apodterimalog.qty_terima AS qty, 
				tbl_apodterimalog.price AS harga, 
				tbl_apohterimalog.jamterima AS jam, 
				tbl_vendor.vendor_name AS rekanan, 
				'PEMBELIAN' AS keterangan, 
				tbl_apodterimalog.totalrp as hpp,
				(tbl_apodterimalog.qty_terima*(SELECT hpp FROM tbl_logbarang WHERE kodebarang=tbl_apodterimalog.kodebarang)) AS totalhpp 
			FROM tbl_apohterimalog 
			INNER JOIN tbl_apodterimalog ON tbl_apohterimalog.terima_no=tbl_apodterimalog.terima_no 
			INNER JOIN tbl_vendor ON tbl_apohterimalog.vendor_id=tbl_vendor.vendor_id 
			
			UNION ALL 
			
			SELECT tbl_apohreturbelilog.retur_date AS tanggal, 
				tbl_apohreturbelilog.koders, 
				tbl_apohreturbelilog.retur_no AS nomor, 
				tbl_apohreturbelilog.gudang AS gudang, 
				tbl_apodreturbelilog.kodebarang, 
				0 AS terima, 
				tbl_apodreturbelilog.qty_retur AS keluar, 
				tbl_apodreturbelilog.qty_retur AS qty, 
				tbl_apodreturbelilog.price AS harga, 
				tbl_apohreturbelilog.jamretur AS jam, 
				tbl_apohreturbelilog.vendor_id AS rekanan, 
				'RETUR PEMBELIAN' AS keterangan, 
				tbl_apodreturbelilog.totalrp as hpp,
				(tbl_apodreturbelilog.qty_retur*(SELECT hpp FROM tbl_logbarang WHERE kodebarang=tbl_apodreturbelilog.kodebarang)) AS totalhpp 
			FROM tbl_apohreturbelilog 
			INNER JOIN tbl_apodreturbelilog ON tbl_apohreturbelilog.retur_no=tbl_apodreturbelilog.retur_no 
			
			UNION ALL 
			
			SELECT tbl_pakaihlog.pakaidate AS tanggal, 
				tbl_pakaihlog.koders, 
				tbl_pakaihlog.pakaino AS nomor, 
				tbl_pakaihlog.gudang AS gudang, 
				tbl_pakaidlog.kodebarang, 
				0 AS terima, 
				tbl_pakaidlog.qty AS keluar, 
				tbl_pakaidlog.qty AS qty, 
				tbl_pakaidlog.harga AS harga, 
				tbl_pakaihlog.jampakai AS jam, 
				'PAKAI' AS rekanan, 
				'PAKAI LOG' AS keterangan, 
				(SELECT hpp FROM tbl_logbarang WHERE kodebarang = tbl_pakaidlog.kodebarang) AS hpp,
				(tbl_pakaidlog.qty*(SELECT hpp FROM tbl_logbarang WHERE kodebarang=tbl_pakaidlog.kodebarang)) AS totalhpp 
			FROM tbl_pakaihlog 
			INNER JOIN tbl_pakaidlog ON tbl_pakaihlog.pakaino=tbl_pakaidlog.pakaino 
			
			UNION ALL 
			
			SELECT tbl_apohresep.tglresep AS tanggal, 
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
				'PENJUALAN RESEP' AS keterangan, 
				if(tbl_apodresep.hna = 0 AND tbl_apodresep.hpp = 0, 0, if(tbl_apodresep.hpp = 0, tbl_apodresep.hna, tbl_apodresep.hpp)) as hpp,
				(tbl_apodresep.qty*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodresep.kodebarang)) AS totalhpp 
			FROM tbl_apohresep 
			INNER JOIN tbl_apodresep ON tbl_apohresep.resepno=tbl_apodresep.resepno 
			
			UNION ALL 
			
			SELECT tbl_apohresep.tglresep AS tanggal, 
				tbl_apohresep.koders, 
				tbl_apohresep.resepno AS nomor, 
				tbl_apohresep.gudang AS gudang, 
				tbl_apodetresep.kodebarang, 
				0 AS terima, 
				tbl_apodetresep.qtyr AS keluar, 
				tbl_apodetresep.qtyr AS qty, 
				tbl_apodetresep.hpp AS harga, 
				tbl_apohresep.jam AS jam, 
				(SELECT namapas FROM tbl_pasien WHERE rekmed = tbl_apohresep.rekmed) AS rekanan, 
				'PENJUALAN RACIK' AS keterangan, 
				tbl_apodetresep.hpp as hpp,
				(tbl_apodetresep.qtyr*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodetresep.kodebarang)) AS totalhpp 
			FROM tbl_apohresep 
			INNER JOIN tbl_apodetresep ON tbl_apohresep.resepno=tbl_apodetresep.resepno 
			
			UNION ALL 
			
			SELECT tbl_apohreturjual.tglretur AS tanggal, 
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
				tbl_apodreturjual.totalrp as hpp,
				(tbl_apodreturjual.qtyretur*(SELECT hpp FROM tbl_barang WHERE kodebarang=tbl_apodreturjual.kodebarang)) AS totalhpp 
			FROM tbl_apohreturjual 
			INNER JOIN tbl_apodreturjual ON tbl_apohreturjual.returno=tbl_apodreturjual.returno 
			
			UNION ALL 
			
			SELECT tbl_apohmovelog.movedate AS tanggal, 
				tbl_apohmovelog.koders, 
				tbl_apohmovelog.moveno AS nomor, 
				tbl_apohmovelog.dari AS gudang, 
				tbl_apodmovelog.kodebarang, 
				0 AS terima, 
				tbl_apodmovelog.qtymove AS keluar, 
				tbl_apodmovelog.qtymove AS qty, 
				tbl_apodmovelog.harga AS harga, 
				tbl_apohmovelog.jammutasi AS jam, 
				tbl_apohmovelog.mohonno AS rekanan, 
				'MUTASI KELUAR' AS keterangan, 
				tbl_apodmovelog.totalharga as hpp,
				(tbl_apodmovelog.qtymove*(SELECT hpp FROM tbl_logbarang WHERE kodebarang=tbl_apodmovelog.kodebarang)) AS totalhpp 
			FROM tbl_apohmovelog 
			INNER JOIN tbl_apodmovelog ON tbl_apohmovelog.moveno=tbl_apodmovelog.moveno 
			WHERE tbl_apohmovelog.dari = '$brg->gudang' 
			
			UNION ALL 
			
			SELECT tbl_apohmovelog.movedate AS tanggal, 
				tbl_apohmovelog.koders, 
				tbl_apohmovelog.moveno AS nomor, 
				tbl_apohmovelog.ke AS gudang, 
				tbl_apodmovelog.kodebarang, 
				tbl_apodmovelog.qtymove AS terima, 
				0 AS keluar, 
				tbl_apodmovelog.qtymove AS qty, 
				tbl_apodmovelog.harga AS harga, 
				tbl_apohmovelog.jammutasi AS jam, 
				tbl_apohmovelog.mohonno AS rekanan, 
				'MUTASI MASUK' AS keterangan, 
				tbl_apodmovelog.totalharga as hpp,
				(tbl_apodmovelog.qtymove*(SELECT hpp FROM tbl_logbarang WHERE kodebarang=tbl_apodmovelog.kodebarang)) AS totalhpp 
			FROM tbl_apohmovelog 
			INNER JOIN tbl_apodmovelog ON tbl_apohmovelog.moveno=tbl_apodmovelog.moveno 
			WHERE tbl_apohmovelog.ke = '$brg->gudang' 
			
			UNION ALL 
			
			SELECT tbl_aposesuailog.tglso AS tanggal, 
				tbl_aposesuailog.koders, 
				'-' AS nomor, 
				tbl_aposesuailog.gudang AS gudang, 
				tbl_aposesuailog.kodeobat as kodebarang, 
				tbl_aposesuailog.sesuai AS terima, 
				0 AS keluar, 
				tbl_aposesuailog.sesuai AS qty, 
				tbl_aposesuailog.hpp AS harga, 
				tbl_aposesuailog.jamentry AS jam, 
				tbl_aposesuailog.type AS rekanan, 
				CONCAT( 
					' ', tbl_aposesuailog.type ,' 
					[ ', tbl_aposesuailog.hasilso ,' - ', tbl_aposesuailog.saldo ,' ] '
					) AS keterangan, 
				tbl_aposesuailog.hpp,
				(tbl_aposesuailog.sesuai*(SELECT hpp FROM tbl_logbarang WHERE kodebarang=tbl_aposesuailog.kodeobat)) AS totalhpp 
			FROM tbl_aposesuailog 
			WHERE approve = 1 
		) mutasi 
			where 
			kodebarang ='$brg->kodebarang' and koders ='$brg->koders' and
			gudang = '$brg->gudang' order by tanggal, jam asc";
		return $this->db->query($mutasi)->result();
	}

	public function cek_jeda($cabang, $dari, $depo, $kodebarang){
		if ($depo != "") {
				$kondisi = "gudang = '$depo'";
		} else {
				$kondisi = "gudang = '$depo'";
		}
		$jam = date("H:i:s");
		$awalx = $this->db->get_where("tbl_periode", ["koders" => $cabang])->row();
		$awal = $awalx->apoperiode;
		// $dari = date('Y-m-d', strtotime("-1 day", strtotime($darix)));
		// $y = "CALL sal_awal_persediaan('$cabang', '$dari', '$depo', '$kodebarang')";
		$y = "SELECT *, (total_masuk - total_keluar) AS saldo, hpp, ((total_masuk - total_keluar) * hpp) AS total FROM 
		(      
			SELECT p.*, (pembelian + move_in + produksi_jadi + so + retur_beli) AS total_masuk, 
			(jual + mutasi_out + retur_jual + produksi_bahan + bhp + expire) AS total_keluar 
			FROM ( 
						SELECT a.kodebarang, 
						(SELECT namabarang FROM tbl_barang WHERE kodebarang = a.kodebarang) AS namabarang, 
						(SELECT satuan1 FROM tbl_barang WHERE kodebarang = a.kodebarang) AS satuan, 
						(SELECT hpp FROM tbl_barang WHERE kodebarang = a.kodebarang) AS hpp, 
						IFNULL( 
								( SELECT qty FROM ( SELECT dt.kodebarang, SUM(dt.qty_terima) AS qty, ht.gudang, ht.koders 
								FROM tbl_barangdterima dt JOIN tbl_baranghterima ht ON dt.terima_no = ht.terima_no 
								WHERE ht.gudang = '$depo' AND ht.koders = '$cabang' AND ht.terima_date BETWEEN '2022-01-01' AND '$dari' GROUP BY dt.kodebarang 
								) AS beli 
						WHERE beli.kodebarang=a.kodebarang ) ,0) AS pembelian, 
						IFNULL( 
								( SELECT qty FROM ( SELECT dm.kodebarang, SUM(dm.qtymove) AS qty, hm.dari, hm.koders 
								FROM tbl_apodmove dm JOIN tbl_apohmove hm ON dm.moveno = hm.moveno 
								WHERE hm.dari = '$depo' AND hm.koders = '$cabang' AND hm.movedate BETWEEN '2022-01-01' AND '$dari' GROUP BY dm.kodebarang 
								) AS move_i 
						WHERE move_i.kodebarang=a.kodebarang ) ,0) AS move_in, 
						IFNULL( 
								( SELECT qty FROM ( SELECT kodebarang, SUM(qtyjadi) AS qty, gudang, koders 
								FROM tbl_apohproduksi 
								WHERE gudang = '$depo' AND koders = '$cabang' AND tglproduksi BETWEEN '2022-01-01' AND '$dari' GROUP BY kodebarang 
								) AS prod_jadi 
						WHERE prod_jadi.kodebarang=a.kodebarang ) ,0) AS produksi_jadi, 
						IFNULL( 
								( SELECT qty FROM ( SELECT kodebarang, SUM(sesuai) AS qty, gudang, koders 
								FROM tbl_aposesuai 
								WHERE gudang = '$depo' AND koders = '$cabang' AND tglso BETWEEN '2022-01-01' AND '$dari' GROUP BY kodebarang 
								) AS so_ 
						WHERE so_.kodebarang=a.kodebarang ) ,0) AS so, 
						IFNULL( 
								( SELECT qty FROM ( SELECT dr.kodebarang, SUM(dr.qty_retur) AS qty, hr.gudang, hr.koders 
								FROM tbl_barangdreturbeli dr JOIN tbl_baranghreturbeli hr ON dr.retur_no = hr.retur_no 
								WHERE hr.gudang = '$depo' AND hr.koders = '$cabang' AND hr.retur_date BETWEEN '2022-01-01' AND '$dari' GROUP BY dr.kodebarang 
								) AS ret 
						WHERE ret.kodebarang=a.kodebarang ) ,0) AS retur_beli, 
						IFNULL( 
								( SELECT SUM(qty) AS qty FROM ( SELECT d.kodebarang, SUM(d.qty) AS qty, h.gudang, h.koders 
								FROM tbl_apodresep d JOIN tbl_apohresep h ON d.resepno = h.resepno 
								WHERE h.gudang = '$depo' AND h.koders = '$cabang' AND h.tglresep BETWEEN '2022-01-01' AND '$dari' GROUP BY d.kodebarang 
								UNION ALL 
								SELECT d.kodebarang, SUM(d.qtyr) AS qty, h.gudang, h.koders 
								FROM tbl_apodetresep d JOIN tbl_apohresep h ON d.resepno = h.resepno 
								WHERE h.gudang = '$depo' AND h.koders = '$cabang' AND h.tglresep BETWEEN '2022-01-01' AND '$dari' GROUP BY d.kodebarang 
								) xx 
						WHERE xx.kodebarang=a.kodebarang GROUP BY xx.kodebarang ) ,0) AS jual, 
						IFNULL( 
								( SELECT qty FROM ( SELECT dm.kodebarang, SUM(dm.qtymove) AS qty, hm.ke, hm.koders 
								FROM tbl_apodmove dm JOIN tbl_apohmove hm ON dm.moveno = hm.moveno 
								WHERE hm.ke = '$depo' AND hm.koders = '$cabang' AND hm.movedate BETWEEN '2022-01-01' AND '$dari' GROUP BY dm.kodebarang 
								) AS move_o 
						WHERE move_o.kodebarang=a.kodebarang ) ,0) AS mutasi_out, 
						IFNULL( 
								( SELECT qty FROM ( SELECT dm.kodebarang, SUM(dm.qtyretur) AS qty, hm.gudang, hm.koders 
								FROM tbl_apodreturjual dm JOIN tbl_apohreturjual hm ON dm.returno = hm.returno 
								WHERE hm.gudang = '$depo' AND hm.koders = '$cabang' AND hm.tglretur BETWEEN '2022-01-01' AND '$dari' GROUP BY dm.kodebarang 
								) AS retur_j 
						WHERE retur_j.kodebarang=a.kodebarang ) ,0) AS retur_jual, 
						IFNULL( 
								( SELECT qty FROM ( SELECT tbl_apodproduksi.kodebarang, SUM(tbl_apodproduksi.qty) AS qty, 
								tbl_apohproduksi.gudang, tbl_apodproduksi.koders 
								FROM tbl_apodproduksi JOIN tbl_apohproduksi ON tbl_apohproduksi.prdno = tbl_apodproduksi.prdno 
								WHERE tbl_apohproduksi.gudang = '$depo' AND tbl_apodproduksi.koders = '$cabang' 
								AND tbl_apohproduksi.tglproduksi BETWEEN '2022-01-01' AND '$dari' GROUP BY kodebarang 
								) AS prod_jadi 
						WHERE prod_jadi.kodebarang=a.kodebarang ) ,0) AS produksi_bahan, 
						IFNULL( 
								( SELECT qty FROM ( SELECT tbl_apodpakai.kodeobat AS kodebarang, SUM(tbl_apodpakai.qty) AS qty, 
								tbl_apohpakai.gudang, tbl_apohpakai.koders 
								FROM tbl_apodpakai JOIN tbl_apohpakai ON tbl_apohpakai.nobhp = tbl_apodpakai.nobhp 
								WHERE tbl_apohpakai.gudang = '$depo' AND tbl_apohpakai.koders = '$cabang' 
								AND tbl_apohpakai.tglbhp BETWEEN '2022-01-01' AND '$dari' GROUP BY kodebarang 
								) AS prod_jadi 
						WHERE prod_jadi.kodebarang=a.kodebarang ) ,0) AS bhp, 
						IFNULL( 
								( SELECT qty FROM ( SELECT dm.kodebarang, SUM(dm.qty) AS qty, hm.gudang, hm.koders 
								FROM tbl_apodex dm JOIN tbl_apohex hm ON dm.ed_no = hm.ed_no 
								WHERE hm.gudang = '$depo' AND hm.koders = '$cabang' AND hm.tgl_ed BETWEEN '2022-01-01' AND '$dari' GROUP BY dm.kodebarang 
								) AS expi 
						WHERE expi.kodebarang=a.kodebarang ) ,0) AS expire 
					FROM tbl_barangstock a WHERE a.gudang = '$depo' AND a.koders = '$cabang'  AND a.kodebarang = '$kodebarang'
			) p
		) z";
		return $this->db->query($y)->row();
	}
}
