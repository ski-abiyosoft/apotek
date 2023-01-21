<?php

if (!class_exists("Pcare_service")) {
    require "Pcare_service.php";
}

class Pcare_rujukan extends Pcare_service
{
    protected $url;
    protected $spesialis;

    public function __construct(array $arg)
    {
        parent::__construct($arg["kdppk"]);

        $this->url              = $this->base_url . "spesialis/rujuk";
    }

    /**
     * Method for getting spesialis reference from BPJS web service
     * 
     * @return stdClass
     */
    public function get_subspesialis (string $subspesialis, string $sarana, string $tglEstRujuk): stdClass
    {
        $tglEstRujuk  = parse_local_date($tglEstRujuk);
        
        $timestamp  = $this->get_timestamp();
        $result     = $this->make_request($timestamp, "{$this->url}/subspesialis/$subspesialis/sarana/$sarana/tglEstRujuk/$tglEstRujuk");

        if ($result->status >= 200 AND $result->status < 300) {
            $decrypted = $this->decrypt_result(json_decode($result->data), $timestamp);

            // If response is not null
            if ($decrypted->response) {
                $response_data = json_decode($decrypted->response);

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
            "message"   => $result
        ];
    }

    /**
     * Method for getting sarana reference from BPJS web service
     * 
     */
    public function get_khusus (string $noKartu, string $kdKhusus, string $subspesialis, string $tglEstRujuk): stdClass
    {
        $tglEstRujuk    = parse_local_date($tglEstRujuk);
        $url            = "{$this->url}/khusus/$kdKhusus/noKartu/$noKartu/tglEstRujuk/$tglEstRujuk";

        if ($kdKhusus == "THA" OR $kdKhusus == "HEM") $url = "{$this->url}/khusus/$kdKhusus/subspesialis/$subspesialis/noKartu/$noKartu/tglEstRujuk/$tglEstRujuk";
        
        $timestamp  = $this->get_timestamp();
        $result     = $this->make_request($timestamp, $url);

        if ($result->status >= 200 AND $result->status < 300) {
            $decrypted = $this->decrypt_result(json_decode($result->data), $timestamp);

            // If response is not null
            if ($decrypted->response) {
                $response_data = json_decode($decrypted->response);

                if ($response_data->count > 0) {
                    foreach ($response_data->list as $data) {
                        $data->kodeRs   = $this->kdppk;
                        $this->spesialis->save_or_update($data, ["kdSpesialis", "kodeRs"]);
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