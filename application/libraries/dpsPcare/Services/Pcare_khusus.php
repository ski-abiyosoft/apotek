<?php

if (!class_exists("PcareService")) {
    require_once(APPPATH . "libraries/dpsPcare/Services/Pcare_service.php");
}

require_once(APPPATH . "libraries/dpsPcare/Repositories/KhususRepository.php");

class Pcare_khusus extends Pcare_service
{
    protected $url;
    protected $khusus;

    public function __construct(array $arg)
    {
        parent::__construct($arg["kdppk"]);

        $this->url              = $this->base_url . "spesialis/khusus";
        $this->khusus         = new KhususRepository();
    }

    /**
     * Method for getting provider from BPJS Web Service
     * 
     * @param int $offset
     * @param int $limit
     * @return stdClass
     */
    public function get_provider (int $offset = 0, int $limit = 100)
    {
        $timestamp  = $this->get_timestamp();
        $result     = $this->make_request($timestamp, "{$this->url}");

        if ($result->status >= 200 AND $result->status < 300) {
            $decrypted = $this->decrypt_result(json_decode($result->data), $timestamp);

            // If response is not null
            if ($decrypted->response) {
                $response_data = json_decode($decrypted->response);

                if ($response_data->count > 0) {
                    foreach ($response_data->list as $data) {
                        $data->kodeRs = $this->kdppk;
                        $this->khusus->save_or_update($data, ["kdKhusus", "kodeRs"]);
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