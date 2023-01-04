<?php

require_once(APPPATH . "libraries/dpsPcare/Services/Pcare_service.php");
require_once(APPPATH . "libraries/dpsPcare/Repositories/PendaftaranRepository.php");

class Pcare_pendaftaran extends Pcare_service
{
    private $pendaftaran;
    private $url;
    
    public function __construct(array $arg)
    {
        parent::__construct($arg["kdppk"]);
        $this->pendaftaran   = new PendaftaranRepository();
        $this->url           = $this->base_url . "pendaftaran";
    }

    /**
     * Method for creating registration record in the BPJS database. You must provide valid id of bpjs_pcare_pendaftaran_table
     * or, this method will fail.
     * 
     * @param int $id
     * @return stdClass
     */
    public function add_pendaftaran (int $id)
    {
        $timestamp = $this->get_timestamp();
        $data_set  = $this->pendaftaran->find($id);
        $payload   = [
            "kdProviderPeserta" => $data_set->kdProviderPeserta,
            "tglDaftar"         => date("d-m-Y", strtotime($data_set->tglDaftar)),
            "noKartu"           => $data_set->noKartuPeserta,
            "kdPoli"            => $data_set->kdPoli,
            "keluhan"           => $data_set->keluhan,
            "kunjSakit"         => boolval($data_set->kunjSakit),
            "sistole"           => floatVal($data_set->sistole),
            "diastole"          => floatVal($data_set->diastole),
            "beratBadan"        => floatVal($data_set->beratBadan),
            "tinggiBadan"       => floatVal($data_set->tinggiBadan),
            "respRate"          => floatVal($data_set->respRate),
            "lingkarPerut"      => floatVal($data_set->lingkarPerut),
            "heartRate"         => floatVal($data_set->heartRate),
            "rujukBalik"        => floatVal($data_set->rujukBalik),
            "kdTkp"             => $data_set->kdTkp
        ];

        $result = $this->make_request($timestamp, $this->url, "POST", $payload);

        $decrypted = $this->decrypt_result(json_decode($result->data), $timestamp);

        if ($result->status >= 200 AND $result->status < 300) {
            return (object) [
                "status" => $result->status,
                "data"   => json_decode($decrypted->response)
            ];
        }

        if ($result->status >= 500) {
            return $result;
        }

        return (object) [
            "status"    => $result->status,
            "message"   => json_decode($decrypted->response)
        ];
    }

    /**
     * Method for saving or update from given data
     * 
     * @param stdClass $data_set
     * @return int
     */
    public function save_or_update (stdClass $data_set): int
    {   
        $this->pendaftaran->save_or_update($data_set, ["noReg"]);

        $row = $this->pendaftaran->select("id")->where("noReg", $data_set->noReg)->get()->row();

        return $row->id;
    }

    /**
     * Method for deleting registration record from BPJS database
     * 
     * @param int $id
     * @return stdClass
     */
    public function delete_pendaftaran (int $id): stdClass
    {
        $data_set   = $this->pendaftaran->find($id);
        
        if ($data_set) {
            $timestamp  = $this->get_timestamp();
            $tglDaftar  = date("d-m-Y", strtotime($data_set->tglDaftar));
            $result     = $this->make_request(
                                                $timestamp, 
                                                $this->url . "/peserta/{$data_set->noKartuPeserta}/tglDaftar/{$tglDaftar}/noUrut/{$data_set->noUrut}/kdPoli/{$data_set->kdPoli}",
                                                "DELETE"
                                            );

            if ($result->status >= 200 AND $result->status < 300) {
                $data_set->deleted = 1;
                unset($data_set->id);

                $this->save_or_update($data_set);

                return (object) [
                    "status" => $result->status,
                    "data"   => $data_set
                ];
            }

            // If internal server error
            if ($result->status >= 500) {
                return $result;
            }

            // If request completed with error
            return (object) [
                "status"    => $result->status,
                "message"   => json_decode($result->data)->response
            ];
        }

        return (object) [
            "status" => 200,
            "data"   => $data_set
        ];
    }

    /**
     * Method for getting registration record from BPJS database
     * 
     * @param string $tglDaftar
     * @param int $offset
     * @param int $limit 
     * @return stdClass
     */
    public function get_pendaftaran_provider (string $date, int $offset = 0, int $limit = 100): stdClass
    {
        $timestamp  = $this->get_timestamp();
        $result     = $this->make_request($timestamp, $this->url . "/tglDaftar/{$date}/{$offset}/${limit}");

        if ($result->status >= 200 AND $result->status < 300) {
            $decrypted = $this->decrypt_result(json_decode($result->data), $timestamp);

            // If response null
            return (object) [
                "status"    => $result->status,
                "data"      => $decrypted->response
            ];
        }

        // return $result;

        // If internal server error
        if ($result->status >= 500) {
            return $result;
        }

        // If request completed with error
        return (object) [
            "status"    => $result->status,
            "message"   => json_decode($result->data)->response
        ];
    }

    /**
     * Method for synchronizing registration data from BPJS database
     * 
     * @param string $tglDaftar
     * @return bool
     */
    public function sync_data (string $tglDaftar): bool
    {
        $totalData      = $this->pendaftaran->select("COUNT(*) as jumlah")->where("tglDaftar", parse_local_date($tglDaftar))->get()->row()->jumlah;
        $limit          = 100;
        
        if ($totalData > 0) {
            $totalBatch = ceil($totalData/$limit);

            for ($i = 0; $i < $totalBatch; $i++) {
                $offset    = $i * $limit;
                $result    = $this->get_pendaftaran_provider($tglDaftar, $offset, $limit);
                
                if (isset($result->data)) {
                    $result_data = json_decode($result->data);

                    foreach ($result_data->list as $data) {
                        $data_set = (object) [
                            "kodeRs"                => $data->providerPelayanan->kdProvider,
                            "noUrut"                => $data->noUrut,
                            "tglDaftar"             => parse_local_date($data->tglDaftar),
                            "kdProviderPelayanan"   => $data->providerPelayanan->kdProvider,
                            "kdProviderPeserta"     => $data->peserta->kdProviderPst->kdProvider,
                            "noKartuPeserta"        => $data->peserta->noKartu,
                            "namaPeserta"           => $data->peserta->nama,
                            "kdPoli"                => $data->poli->kdPoli,
                            "keluhan"               => $data->keluhan,
                            "kunjSakit"             => (int) $data->kunjSakit,
                            "status"                => $data->status,
                            "sistole"               => floatval($data->sistole),
                            "diastole"              => floatval($data->diastole),
                            "beratBadan"            => floatval($data->beratBadan),
                            "tinggiBadan"           => floatval($data->tinggiBadan),
                            "respRate"              => floatval($data->respRate),
                            "lingkarPerut"          => floatval($data->lingkarPerut),
                            "heartRate"             => floatval($data->heartRate),
                            "IMT"                   => floatval($data->IMT),
                            "kdTkp"                 => $data->tkp->kdTkp
                        ];

                        $this->pendaftaran->save_or_update($data_set, ["noUrut", "tglDaftar"]);
                    }
                }
            }

            return true;
        }

        return false;
    }

}