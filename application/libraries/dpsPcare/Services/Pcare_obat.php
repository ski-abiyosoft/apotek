<?php 

require_once "Pcare_service.php";
require_once APPPATH . "libraries/dpsPcare/Repositories/ObatRepository.php";
require_once APPPATH . "libraries/dpsPcare/Repositories/ObatKunjunganRepository.php";
require_once APPPATH . "libraries/dpsPcare/Repositories/MasterBarangRepository.php";

class Pcare_obat extends Pcare_service
{
    private $obat;
    private $obat_kunjungan;
    private $master_barang;
    private $url;

    public function __construct(array $arg)
    {
        parent::__construct($arg["kdppk"]);

        $this->url           = $this->base_url . "obat";
        $this->obat          = new ObatRepository();
        $this->obat          = new ObatKunjunganRepository();
        $this->master_barang = new MasterBarangRepository();
    }

    /**
     * Method for getting medicine data from BPJS database
     * 
     * @param string $search_term
     * @param int $offset
     * @param int $limit
     */
    public function get_obat_dpho (string $search_term, int $offset = 0, int $limit = 100)
    {
        $timestamp  = $this->get_timestamp();
        $result     = $this->make_request($timestamp, "{$this->url}/dpho/{$search_term}/{$offset}/{$limit}");

        if ($result->status >= 200 AND $result->status < 300) {
            $decrypted = $this->decrypt_result(json_decode($result->data), $timestamp);

            // If response is not null
            if ($decrypted->response) {
                $response_data = json_decode($decrypted->response);

                if ($response_data->count > 0) {
                    foreach ($response_data->list as $data) {
                        $data->kodeRs = $this->kdppk;
                        $this->obat->save_or_update($data, ["kdObat", "kodeRs"]);
                    }
                }

                return (object) [
                    "status"    => $result->status,
                    "data"      => $response_data
                ];
            }

            // If response null
            return (object) [
                "status"    => $result->status,
                "data"      => $decrypted->response
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

    /**
     * Method for saving sync data into master table
     * 
     * @param string $kdObat
     * @param string $kodebarang
     */
    public function update_master_barang (string $kdObat, string $kodebarang): bool
    {
        $this->master_barang->update((object) ["pcare_kdObat" => NULL], ["pcare_kdObat" => $kdObat]);

        return $this->master_barang->update((object) ["pcare_kdObat" => $kdObat], ["kodebarang" => $kodebarang]);
    }

    /**
     * Method for adding medicine into visitation record
     * 
     * @param string $noKunjungan
     * @param stdClass $data_set
     * @return stdClass
     */
    public function add_obat_kunjungan (string $noKunjungan, stdClass $data_set): stdClass
    {
        $payload = [
            "kdObatSK" => 0,
            "noKunjungan" => $noKunjungan,
            "racikan" => boolval($data_set->racikan),
            "obatDPHO" => boolval($data_set->obatDPHO),
            "kdRacikan" => $data_set->kdRacikan ,
            "kdObat" => $data_set->kdObat,
            "signa1" => floatval($data_set->signa1),
            "signa2" => floatval($data_set->signa2),
            "jmlObat" => floatval($data_set->jmlObat),
            "jmlHari" => floatval($data_set->jmlHari),
            "kekuatan" => floatval($data_set->kekuatan),
            "jmlPermintaan" => floatval($data_set->jmlPermintaan),
            "nmObatNonDPHO" => $data_set->nmObatNonDPHO
        ];

        $timestamp  = $this->get_timestamp();
        $result     = $this->make_request($timestamp, $this->url . "/kunjungan", "POST", $payload);

        if ($result->status >= 200 AND $result->status < 300) {
            $response = json_decode($result)->response;
            $data_set = (object) $payload;
            $data_set->kodeRs = $this->kdppk;
            $data_set->kdObatSK = $response[0]->message;

            $this->obat_kunjungan->save_or_update($data_set, ["kdObatSK", "kodeRs"]);

            return (object) [
                'status'    => $result->status,
                "data"      => $response
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

    /**
     * Method for getting obat kunjungan records
     * 
     * @param string $noKunjungan
     * @return stdClass
     */
    public function get_obat_kunjungan (string $noKunjungan): stdClass
    {
        $timestamp  = $this->get_timestamp();
        $result     = $this->make_request($timestamp, "{$this->url}/kunjungan/{$noKunjungan}");

        if ($result->status >= 200 AND $result->status < 300) {
            $decrypted = $this->decrypt_result(json_decode($result->data), $timestamp);

            // If response is not null
            if ($decrypted->response) {
                $response_data = json_decode($decrypted->response);

                if ($response_data->count > 0) {
                    foreach ($response_data->list as $data) {
                        $data_set = (object) [
                            "kodeRs" => $this->kdppk,
                            "kdObatSK" => $data->kdObatSK,
                            "noKunjungan" => $noKunjungan,
                            "kdRacikan" => $data->kdRacikan,
                            "kdObat" => $data->obat->kdObat,
                            "nmObat" => $data->obat->nmObat,
                            "sedia" => $data->obat->sedia,
                            "signa1" => $data->signa1,
                            "signa2" => $data->signa2,
                            "jmlObat" => $data->jmlObat,
                            "jmlHari" => $data->jmlHari,
                            "kekuatan" => $data->kekuatan,
                            "jmlPermintaan" => $data->jmlPermintaan,
                            "jmlObatRacikan" => $data->jmlObatRacikan,
                        ];

                        if (isset($data->nmObatNonDPHO)) {
                            $data_set->nmObatNonDPHO = $data->nmObatNonDPHO;
                        }

                        $this->obat->save_or_update($data_set, ["kdObatSK", "kodeRs"]);
                    }
                }

                return (object) [
                    "status"    => $result->status,
                    "data"      => $response_data
                ];
            }

            // If response null
            return (object) [
                "status"    => $result->status,
                "data"      => $decrypted->response
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
}