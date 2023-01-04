<?php 

require_once "Pcare_service.php";
require_once APPPATH . "libraries/dpsPcare/Repositories/ObatRepository.php";
require_once APPPATH . "libraries/dpsPcare/Repositories/MasterBarangRepository.php";

class Pcare_obat extends Pcare_service
{
    private $obat;
    private $master_barang;
    private $url;

    public function __construct(array $arg)
    {
        parent::__construct($arg["kdppk"]);

        $this->url           = $this->base_url . "obat";
        $this->obat          = new ObatRepository();
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
        return $this->master_barang->update((object) ["pcare_kdObat" => $kdObat], ["kodebarang" => $kodebarang]);
    }
}