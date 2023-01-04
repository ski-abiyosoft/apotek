<?php 

require_once APPPATH . "libraries/dpsPcare/Repositories/TindakanRepository.php";
require_once "Pcare_service.php";

class Pcare_tindakan extends Pcare_service
{
    protected $url;
    protected $tindakan;

    public function __construct(array $arg)
    {
        parent::__construct($arg["kdppk"]);

        $this->url        = $this->base_url . "tindakan";
        $this->tindakan   = new TindakanRepository();
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
                        $data->rjtp     = $kdTkp == 10 ? 1 : 0;
                        $data->ritp     = $kdTkp == 20 ? 1 : 0;
                        $data->promotif = $kdTkp == 50 ? 1 : 0;
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
}