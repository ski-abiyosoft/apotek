<?php

require APPPATH . "libraries/dpsPcare/Repositories/DiagnosaRepository.php";

if (!class_exists("Pcare_service")) {
    require "Pcare_service.php";
}

class Pcare_diagnosa extends Pcare_service
{
    private $diagnosa;
    private $url;
    
    public function __construct(array $arg)
    {
        parent::__construct($arg["kdppk"]);
        $this->diagnosa  = new DiagnosaRepository();
        $this->url       = $this->base_url . "diagnosa";
    }

    public function get_diagnosa (string $search_term, int $offset = 0, int $limit = 100)
    {
        $timestamp  = $this->get_timestamp();
        $result     = $this->make_request($timestamp, "{$this->url}/{$search_term}/{$offset}/{$limit}");

        if ($result->status >= 200 AND $result->status < 300) {
            $decrypted = $this->decrypt_result(json_decode($result->data), $timestamp);

            // If response is not null
            if ($decrypted->response) {
                $response_data = json_decode($decrypted->response);

                if ($response_data->count > 0) {
                    foreach ($response_data->list as $data) {
                        $this->diagnosa->save_or_update($data, ["kdDiag"]);
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