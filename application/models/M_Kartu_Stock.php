<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Kartu_Stock extends CI_Model {

    public function __construct(){
        parent::__construct();
		$this->load->database();
    }

    public function query(){
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
				'PEMBELIAN' as keterangan
				from tbl_apohterimalog AS a
				inner join tbl_apodterimalog AS b on a.terima_no=b.terima_no
				inner join tbl_vendor on a.vendor_id=tbl_vendor.vendor_id
				
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
				'MUTASI' as keterangan
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
				'MUTASI' as keterangan
				from tbl_apohmovelog inner join
				tbl_apodmovelog on tbl_apohmovelog.moveno=tbl_apodmovelog.moveno
				
				union all
				
				
				SELECT
				a.pakaidate AS tanggal,
				a.koders,
				a.pakaino AS nomor,
				a.username AS gudang,
				b.kodebarang,
				0 AS terima,
				b.qty AS keluar,
				b.qty AS qty,
				b.harga,
				'-' AS rekanan,
				'PEMAKAIAN' AS keterangan
				FROM tbl_pakaihlog AS a
				INNER JOIN tbl_pakaidlog AS b
				ON a.pakaino = b.pakaino
				) mutasi
			
				where kodebarang = '$barang' and
				koders = '$cabang' and
				gudang = '$gudang' and
				tanggal between '$_tgl1' and '$_tgl2'
				ORDER BY tanggal ASC, keterangan, nomor
				
				";
			$nourut = 1;
            $lap = $this->db->query($mutasi)->result();
    }
   
}