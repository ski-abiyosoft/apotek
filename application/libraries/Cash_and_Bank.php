<?php 

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Author: tripletTrouble (DPS)
 * Github: https://github.com/triplettrouble
 * 
 * Modul kas dan bank untuk menghandel saldo kas dan bank. Dibuat untuk PT. Sistem Kesehatan Indonesia.
 * All right reserved.
 */

class Cash_and_Bank
{
    protected $_CI;
    protected $ar_payment_table = "tbl_harpas";
    protected $ap_payment_table = "tbl_hap";
    protected $cash_in_table = "tbl_kasmasuk";
    protected $cash_out_table = "tbl_hbayar";

    /**
     * Here is contruct method. This method will load Codeigniter instance and all library or model that
     * needed by this module.
     * 
     */
    public function __construct()
    {
        // Load the Codeigniter instance;
        $this->_CI = &get_instance();

        // Load the needed library
        $this->_CI->load->library('collection');
        $this->_CI->load->library('timefication');

        // Bind the Codeigniter property
        $this->db = $this->_CI->db;
        $this->collection = $this->_CI->collection;
        $this->timefication = $this->_CI->timefication;
    }

    /**
     * Method untuk mendapatkan data penerimaan kas
     * 
     * @param string $start_date, $end_date, $cabang
     * @return array
     */
    public function get_cash_in_detail (string $start_date, string $end_date, string $cabang): array
    {
        $start_date = substr($start_date, 0, 10);
        $end_date = substr($end_date, 0, 10);

        $query = "
            SELECT 
                *
            FROM 
                (
                    (SELECT
                        tglkas as tanggal,
                        ('Penerimaan Kas') as sumber,
                        notr as bukti,
                        nilairp as jumlah
                    FROM 
                        {$this->cash_in_table}
                    WHERE
                        koders = '$cabang'
                            AND
                        tglkas >= '$start_date'
                            AND
                        tglkas <= '$end_date')
                    UNION ALL
                    (SELECT
                        ardate as tanggal,
                        ('Penerimaan Piutang') as sumber,
                        arno as bukti,
                        totalterima as jumlah
                    FROM
                        {$this->ar_payment_table}
                    WHERE
                        koders = '$cabang'
                            AND
                        ardate >= '$start_date'
                            AND
                        ardate <= '$end_date')
                ) as kas_masuk
            ORDER BY
                kas_masuk.tanggal
        ";
        
        return $this->db->query($query)->result();
    }

    /**
     * Method untuk mendapatkan data pengeluaran kas
     * 
     * @param string $start_date, $end_date, $cabang
     * @return array
     */
    public function get_cash_out_detail (string $start_date, string $end_date, string $cabang): array
    {
        $start_date = substr($start_date, 0, 10);
        $end_date = substr($end_date, 0, 10);

        $query = "
            SELECT 
                *
            FROM 
                (
                    (SELECT
                        tglbayar as tanggal,
                        COALESCE(keterangan, 'Pengeluaran Kas') as keperluan,
                        bayarno as bukti,
                        jmbayar as jumlah
                    FROM 
                        {$this->cash_out_table}
                    WHERE
                        koders = '$cabang'
                            AND
                        tglbayar >= '$start_date'
                            AND
                        tglbayar <= '$end_date')
                    UNION ALL
                    (SELECT
                        pay_date as tanggal,
                        COALESCE(ket, 'Pembayaran Utang') as keperluan,
                        ap_id as bukti,
                        totalbayar as jumlah
                    FROM
                        {$this->ap_payment_table}
                    WHERE
                        koders = '$cabang'
                            AND
                        pay_date >= '$start_date'
                            AND
                        pay_date <= '$end_date')
                ) as kas_keluar
            ORDER BY
                kas_keluar.tanggal
        ";
        
        return $this->db->query($query)->result();
    }

    /**
     * Method untuk mendapatkan data perubahan kas selama periode
     * 
     * @param string $start_date $end_date $cabang
     * @return array
     */
    public function get_cash_flow_detail (string $start_date, string $end_date, string $cabang): array
    {
        $start_date = substr($start_date, 0, 10);
        $end_date = substr($end_date, 0, 10);

        $query = "
            SELECT 
                *
            FROM 
                (
                    (SELECT
                        tglbayar as tanggal,
                        COALESCE(keterangan, 'Pengeluaran Kas') as keterangan,
                        bayarno as bukti,
                        (jmbayar * -1) as jumlah
                    FROM 
                        {$this->cash_out_table}
                    WHERE
                        koders = '$cabang'
                            AND
                        tglbayar >= '$start_date'
                            AND
                        tglbayar <= '$end_date')
                    UNION ALL
                    (SELECT
                        pay_date as tanggal,
                        COALESCE(ket, 'Pembayaran Utang') as keterangan,
                        ap_id as bukti,
                        (totalbayar * -1) as jumlah
                    FROM
                        {$this->ap_payment_table}
                    WHERE
                        koders = '$cabang'
                            AND
                        pay_date >= '$start_date'
                            AND
                        pay_date <= '$end_date')
                    UNION ALL
                    (SELECT
                        tglkas as tanggal,
                        ('Penerimaan Kas') as keterangan,
                        notr as bukti,
                        nilairp as jumlah
                    FROM 
                        {$this->cash_in_table}
                    WHERE
                        koders = '$cabang'
                            AND
                        tglkas >= '$start_date'
                            AND
                        tglkas <= '$end_date')
                    UNION ALL
                    (SELECT
                        ardate as tanggal,
                        ('Penerimaan Piutang') as keterangan,
                        arno as bukti,
                        totalterima as jumlah
                    FROM
                        {$this->ar_payment_table}
                    WHERE
                        koders = '$cabang'
                            AND
                        ardate >= '$start_date'
                            AND
                        ardate <= '$end_date')
                ) as perubahan_kas
            ORDER BY
                perubahan_kas.tanggal
        ";
        
        return $this->db->query($query)->result();
    }
}