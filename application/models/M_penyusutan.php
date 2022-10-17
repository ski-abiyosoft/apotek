<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_penyusutan extends CI_Model 
{
    public $table = 'tbl_fixsusut';

    /**
     * Load library dan database yang dibutuhkan
     * 
     */
    public function __construct()
    {
        parent::__construct();
		$this->load->database();
    }

    /**
     * Method untuk mendapatkan data penyusutan
     * berdasarkan id
     * 
     * @return object
     */
    public function get_susut(string $kodefix, string $periode = NULL)
    {
        if ($periode){
            $tglsusut = date('Y-m-d H:i:s', strtotime($periode));
            $data = $this->db->from($this->table)->where(['kodefix' => $kodefix, 'tglsusut <' => $tglsusut])
                    ->get()->result();
        }else{
            $data = $this->db->from($this->table)->where('kodefix', $kodefix)->get()->result();
        }

        if (count($data) > 0){
            $result = (object) [];
            $result->depreciation_list = $data;
            $result->depreciation_total = array_reduce($data, function ($carry, $item) {
                                                $carry += floatval($item->susutrp);
                                                return $carry;
                                            });
        }

        return $result ?? null;
    }

    /**
     * Method untuk menyimpan data penyusutan ke dalam tbl_fixsusut
     * 
     * @param object
     * @return bool
     */
    public function save($data): bool
    {
        $value = [
            'koders' => $data->koders,
            'kodefix' => $data->kodefix,
            'kodesusut' => $data->kodesusut,
            'tglsusut' => $data->tglsusut,
            'blsusut' => $data->blsusut,
            'prosensusut' => $data->prosensusut,
            'susutrp' => $data->susutrp,
            'susut' => $data->susut
        ];
        if ($this->db->insert($this->table, $value)){
            return true;
        }else{
            return false;
        }
    }

    
}