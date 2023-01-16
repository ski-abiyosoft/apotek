<?php

if (!class_exists("Pcare_service")) {
    require "Pcare_service.php";
}

require APPPATH . "libraries/dpsPcare/Repositories/ClubProlanisRepository.php";

class Pcare_kelompok extends Pcare_service
{
    private $club_prolanis;
    private $url;

    public function __construct(array $arg)
    {
        parent::__construct($arg["kdppk"]);

        $this->url           = $this->base_url . "kelompok/club";
        $this->club_prolanis = new ClubProlanisRepository();
    }

    /**
     * Method for getting medicine data from BPJS database
     * 
     * @param string $kdProgram
     */
    public function get_club_prolanis (string $kdProgram)
    {
        $timestamp  = $this->get_timestamp();
        $result     = $this->make_request($timestamp, "{$this->url}/{$kdProgram}");

        if ($result->status >= 200 AND $result->status < 300) {
            $decrypted = $this->decrypt_result(json_decode($result->data), $timestamp);

            // If response is not null
            if ($decrypted->response) {
                $response_data = json_decode($decrypted->response);

                if ($response_data->count > 0) {
                    foreach ($response_data->list as $data) {
                        $data->kodeRs       = $this->kdppk;
                        $data->kdProgram    = $kdProgram;
                        $data->tglMulai     = parse_local_date($data->tglMulai);
                        $data->tglAkhir     = parse_local_date($data->tglAkhir);
                        
                        $this->club_prolanis->save_or_update($data, ["clubId", "kodeRs"]);
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