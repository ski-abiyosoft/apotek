<?php

if (!class_exists("Repository")) {
    require APPPATH . "dpsAccounting/Repositories/Repository.php";
}

class RujukanRepository extends Repository
{
    protected $table = "bpjs_pcare_rujukan";

    /**
     * Method for save_or update rujukan
     * 
     * @param stdClass $data_set
     * @param array $unique_column
     */
    public function save_or_update_rujukan (stdClass $data_set, array $unique_column)
    {
        $insert_data    = (object) [
            "kodeRs" => $data_set->kodeRs,
            "noRujukan" => $data_set->noRujukan,
            "kdPpk" => $data_set->ppkRujuk->kdPPK,
            "nmPpk" => $data_set->ppkRujuk->nmPPK,
            "tglKunjungan" => parse_local_date($data_set->tglKunjungan),
            "kdPoli" => $data_set->poli->kdPoli,
            "nmPoli" => $data_set->poli->nmPoli,
            "nokaPst" => $data_set->nokaPst,
            "nmPst" => $data_set->nmPst,
            "tglLahir" => parse_local_date($data_set->tglLahir),
            "pisa" => $data_set->pisa,
            "ketPisa" => $data_set->ketPisa,
            "sex" => $data_set->sex,
            "kdDiag1" => $data_set->diag1->kdDiag,
            "nmDiag1" => $data_set->diag1->nmDiag,
            "kdDiag2" => isset($data_set->diag2->kdDiag) ? $data_set->diag2->kdDiag : NULL,
            "nmDiag2" => isset($data_set->diag2->nmDiag) ? $data_set->diag2->nmDiag : NULL,
            "kdDiag3" => isset($data_set->diag3->kdDiag) ? $data_set->diag3->kdDiag : NULL,
            "nmDiag3" => isset($data_set->diag3->nmDiag) ? $data_set->diag3->nmDiag : NULL,
            "catatan" => $data_set->catatan,
            "kdDokter" => $data_set->dokter->kdDokter,
            "nmTacc" => isset($data_set->tacc->nmTacc) ? $data_set->tacc->nmTacc : NULL,
            "alasanTacc" => isset($data_set->tacc->alasanTacc) ? $data_set->tacc->alasanTacc : NULL,
            "infoDenda" => isset($data_set->infoDenda) ? $data_set->infoDenda : NULL,
            "tglEstRujuk" => parse_local_date($data_set->tglEstRujuk),
            "tglAkhirRujuk" => parse_local_date($data_set->tglAkhirRujuk),
            "jadwal" => $data_set->jadwal,
            "kdDati" => $data_set->ppk->kc->dati->kdDati,
            "nmDati" => $data_set->ppk->kc->dati->nmDati,
            "nmKR" => $data_set->ppk->kc->kdKR->nmKR,
            "kdKR" => $data_set->ppk->kc->kdKR->kdKR,
            "nmDokter" => $data_set->dokter->nmDokter
        ];
        $where_clause = [];

        foreach($unique_column as $key => $value) {
            $where_clause[$value] = $insert_data->$value;
        }

        $is_exists      = $this->select("id")->where($where_clause)->get()->row();

        if (isset($is_exists)) {
            return $this->update($insert_data, $where_clause);
        }else {
            return $this->save($insert_data);
        }
    }
}