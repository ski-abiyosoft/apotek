<?php 

require_once APPPATH . "libraries/dpsPcare/Repositories/TindakanRepository.php";
require_once APPPATH . "libraries/dpsPcare/Repositories/TindakanKunjunganRepository.php";
require_once APPPATH . "libraries/dpsPcare/Repositories/MasterTarifHeaderRepository.php";
require_once "Pcare_service.php";

class Pcare_tindakan extends Pcare_service
{
    protected $url;
    protected $tindakan;
    protected $tindakan_kunjungan;
    protected $master_tindakan;

    public function __construct(array $arg)
    {
        parent::__construct($arg["kdppk"]);

        $this->url                  = $this->base_url . "tindakan";
        $this->tindakan             = new TindakanRepository();
        $this->tindakan_kunjungan   = new TindakanKunjunganRepository();
        $this->master_tindakan      = new MasterTarifHeaderRepository();
    }
    
    /**
     * Method for getting tindakan from BPJS database
     * 
     * @param string $kdTkp
     * @param int $offset
     * @param int $limit
     * @return stdClass
     */
    public function get_tindakan (string $kdTkp, int $offset = 0, int $limit = 100): stdClass
    {
        $timestamp  = $this->get_timestamp();
        $result     = $this->make_request($timestamp, "{$this->url}/kdTkp/{$kdTkp}/{$offset}/{$limit}");

        if ($result->status >= 200 AND $result->status < 300) {
            $decrypted = $this->decrypt_result(json_decode($result->data), $timestamp);

            // If response is not null
            if ($decrypted->response) {
                $response_data = json_decode($decrypted->response);

                if ($response_data->count > 0) {
                    foreach ($response_data->list as $data) {
                        $data->kodeRs   = $this->kdppk;

                        if ($kdTkp == 10) {
                            $data->rjtp = 1;
                        }

                        if ($kdTkp == 20) {
                            $data->ritp = 1;
                        }

                        if ($kdTkp == 50) {
                            $data->promotif  = 1;
                        }

                        $this->tindakan->save_or_update($data, ["kdTindakan", "kodeRs"]);
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
     * Method for updating master tindakan
     * 
     * @param string $kdTindakan
     * @param string $kodetarif
     */
    public function update_master_tindakan (string $kdTindakan, string $kodetarif)
    {
        $this->master_tindakan->update((object) ["pcare_kdTindakan" => NULL], ["pcare_kdTindakan" => $kdTindakan]);

        return $this->master_tindakan->update((object) ["pcare_kdTindakan" => $kdTindakan], ["kodetarif" => $kodetarif]);
    }

    /**
     * Method for adding action into visitation record
     * 
     * @param string $noKunjungan
     * @param stdClass $data_set
     * @return stdClass
     */
    public function add_tindakan_kunjungan (string $noKunjungan, stdClass $data_set): stdClass
    {
        $payload = [
            "kdTindakanSK" => 0,
            "noKunjungan" => $noKunjungan,
            "kdTindakan" => $data_set->kdTindakan,
            "biaya" => floatval($data_set->biaya),
            "keterangan" => $data_set->keterangan,
            "hasil" => 1
        ];

        $timestamp  = $this->get_timestamp();
        $result     = $this->make_request($timestamp, $this->url, "POST", $payload);

        if ($result->status >= 200 AND $result->status < 300) {
            $decrypted = $this->decrypt_result(json_decode($result->data), $timestamp);

            if ($decrypted->response) {
                $response_data = json_decode($decrypted->response);
                $data_set = (object) $payload;
                $data_set->kodeRs = $this->kdppk;
                $data_set->kdTindakanSK = $response_data->message;

                $this->tindakan_kunjungan->save_or_update($data_set, ["kdTindakanSK", "kodeRs"]);

                return (object) [
                    "status"    => $result->status,
                    "data"      => $response_data
                ];
            }

            return (object) [
                'status'    => $result->status,
                "data"      => $result
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
    public function get_tindakan_kunjungan (string $noKunjungan): stdClass
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
                            "kdTindakanSK" => 0,
                            "noKunjungan" => $noKunjungan,
                            "kdTindakan" => $data->kdTindakan,
                            "biaya" => floatval($data->biaya),
                            "keterangan" => $data->keterangan,
                            "kodeRs" => $this->kdppk,
                            "hasil" => $data->hasil
                        ];

                        $this->tindakan_kunjungan->save_or_update($data_set, ["kdTindakanSK", "kodeRs"]);
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