<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounting 
{
    private $_CI;

    public function __construct()
    {
        $this->_CI =& get_instance();

        $this->_CI->load->model('M_penyusutan', 'penyusutan', TRUE);
    }
    
    /**
     * Method untuk menghitung depresiasi
     * 
     * @return object
     */
    public function calculate_depreciation (array $data)
    {
        $month_total = $data['depreciation_year'] * 12;
        $depreciation_rate = 100 / $month_total;
        $depreciation_per_month = $data['accuisition_value'] * ($depreciation_rate / 100);

        return (object) [
            'kode_rs' => $data['kode_rs'],
            'kode_fix' => $data['kode_fix'],
            'bulan_pakai' => $data['bulan_pakai'],
            'month_total' => $month_total,
            'depreciation_rate' => $depreciation_rate,
            'depreciation_per_month' => $depreciation_per_month
        ];
    }

    /**
     * Method untuk menyimpan depresiasi ke database
     * 
     * @return bool
     */
    public function create_depreciation (array $data): bool
    {
        // Calculate depreciation per month
        $depreciation_per_month = $this->calculate_depreciation($data);
        $unix_tanggal_pakai = strtotime($depreciation_per_month->bulan_pakai);
        
        // Prepare the data for insertion
        $depreciation_total = [];
        $unix_penyusutan = 0;

        for ($i = 1; $i <= $depreciation_per_month->month_total; $i++){
            
            // Logika tanggal penyusutan
            $total_days = 0;
            if ($i == 1){
                $month = date('m', $unix_tanggal_pakai);
                $year = date('Y', $unix_tanggal_pakai);
                $total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year); // Mencari jumlah hari dalam sebulan
                $unix_penyusutan = $unix_tanggal_pakai + ($total_days * 24 * 60 * 60); // Tanggal penyusutan adalah tanggal pemakaian ditambah jumlah hari
            }else{
                $month = $month = date('m', $unix_penyusutan);
                $year = date('Y', $unix_penyusutan);
                $total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year); // Mencari jumlah hari dalam sebulan
                $unix_penyusutan = $unix_penyusutan + ($total_days * 24 * 60 * 60); // Tanggal penyusutan adalah tanggal pemakaian ditambah jumlah hari
            }

            array_push($depreciation_total, (object) [
                'koders' => $depreciation_per_month->kode_rs,
                'kodefix' => $depreciation_per_month->kode_fix,
                'kodesusut' => $depreciation_per_month->kode_fix . date('mY', $unix_penyusutan),
                'tglsusut' => date('Y-m-d', $unix_penyusutan),
                'blsusut' => date('mY', $unix_penyusutan),
                'prosensusut' => round($depreciation_per_month->depreciation_rate, 2),
                'susutrp' => round($depreciation_per_month->depreciation_per_month, 2),
                'susut' => 0
            ]);
        }

        // Masukkan ke database dengan loop
        for ($i = 0; $i < count($depreciation_total); $i++){
            if ($this->_CI->penyusutan->save($depreciation_total[$i]) == false){
                return false;
            }
        }

        return true;
    }
}