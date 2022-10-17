<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_laporan extends CI_Model {

	// var $tabel_user="userlogin";
    
	function __construct()
	{
	    parent::__construct();
	}

	function get_kunjungan_pasien($startdate, $enddate, $jenis_kunjungan){
        $qry = "      
                SELECT p.rekmed, p.koders, CONCAT(p.namapas,' ',p.preposisi) AS namapas, p.jkel,  
                    p.tgllahir, TIMESTAMPDIFF(YEAR, p.tgllahir, CURDATE()) AS umur, p.alamat,
                    CONCAT(COALESCE(p.handphone,'0'), ' / ', COALESCE(p.phone,'0')) AS telp,
                    GROUP_CONCAT(dta.konsultasi_tindakan SEPARATOR ', ') AS konsultasi_tindakan, GROUP_CONCAT(dta.nadokter SEPARATOR ', ') AS nadokter,
                    MIN(dta.tglbayar) AS tgl_kunjungan_pertama, MAX(dta.tglbayar) AS tgl_kunjungan_terakhir,
                    SUM(dta.frekuensi_kunjungan) AS frekuensi, SUM(dta.nilai_total_pembelanjaan) AS nilai_pembelanjaan
                FROM (
                    SELECT
                        dt.tglbayar, 
                        dt.rekmed, dt.noreg, 
                        COUNT(dt.tglbayar) AS frekuensi_kunjungan, SUM(dt.nilai_total_pembelanjaan) AS nilai_total_pembelanjaan
                        , h.konsultasi_tindakan, d.nadokter
                    FROM (
                        SELECT DATE_FORMAT(tglbayar,'%Y-%m-%d') AS tglbayar, 
                            rekmed, noreg, (totalbayar - kembali + uangmuka) AS nilai_total_pembelanjaan
                        FROM tbl_kasir
                        WHERE tglbayar >= '$startdate' AND tglbayar <= '$enddate' -- AND noreg = ''
                    ) dt 
                    LEFT JOIN (
                        SELECT h.noreg, GROUP_CONCAT(d.tindakan SEPARATOR ', ') AS konsultasi_tindakan
                        FROM tbl_hpoli h 
                            LEFT JOIN (

                                SELECT dp.noreg, t.`tindakan`
                                FROM (
                                    SELECT d.noreg, d.`kodetarif`
                                    FROM tbl_dpoli d 
                                    GROUP BY noreg, kodetarif
                                ) AS dp LEFT JOIN tbl_tarifh t ON dp.kodetarif = t.`kodetarif`
                                
                            ) d ON h.`noreg` = d.`noreg`
                        WHERE h.tglperiksa >= '$startdate' AND h.tglperiksa <= '$enddate'
                        GROUP BY h.noreg
                    ) AS h ON dt.noreg = h.noreg
                    LEFT JOIN (
                        SELECT h.noreg, GROUP_CONCAT(d.nadokter SEPARATOR ', ') AS nadokter
                        FROM tbl_hpoli h 
                            LEFT JOIN (

                                SELECT dp.noreg, d.nadokter
                                FROM (
                                    SELECT d.noreg, d.`kodokter`
                                    FROM tbl_dpoli d 
                                    GROUP BY noreg, kodokter
                                ) AS dp LEFT JOIN  (
                                    SELECT kodokter, nadokter
                                    FROM tbl_dokter
                                    GROUP BY kodokter
                                )d ON dp.kodokter = d.kodokter
                                
                            ) d ON h.`noreg` = d.`noreg`
                        WHERE h.tglperiksa >= '$startdate' AND h.tglperiksa <= '$enddate'
                        GROUP BY h.noreg
                    ) AS d ON dt.noreg = d.noreg
                    GROUP BY dt.rekmed, dt.noreg
                ) AS dta LEFT JOIN tbl_pasien p ON dta.rekmed = p.`rekmed`
                GROUP BY rekmed
                ORDER BY $jenis_kunjungan DESC
                ;
        ";
		$cek_cabang = $this->db->query($qry);

		if($cek_cabang->num_rows() > 0){
			return $cek_cabang->result();
		} else {
			return false;
		}
	}


}
?>