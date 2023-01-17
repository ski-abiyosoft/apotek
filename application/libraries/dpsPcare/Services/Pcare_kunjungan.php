<?php 

require_once("Pcare_service.php");
require_once(APPPATH . "libraries/dpsPcare/Repositories/KunjunganRepository.php");

class Pcare_kunjungan extends Pcare_service
{
    private $uri;
    private $kunjungan;
    
    public function __construct(array $arg)
    {
        parent::__construct($arg["kdppk"]);
        $this->url              = $this->base_url . "kunjungan";
        $this->kunjungan        = new KunjunganRepository();
    }

    /**
     * Method for handling add kunjungan request. You must provide valid id from bpjs_pcare_kunjungan
     * table, otherwise this service will fail.
     * 
     * @param int $id
     * @return stdClass
     */
    public function add_kunjungan (int $id)
    {
        $bridgingData       = $this->kunjungan->find($id);

        $bridgingPayload = [
            "noKartu"               => $bridgingData->noKartu,
            "tglDaftar"             => date("d-m-Y", strtotime($bridgingData->tglDaftar)),
            "kdPoli"                => $bridgingData->kdPoli,
            "keluhan"               => $bridgingData->keluhan,
            "kdSadar"               => $bridgingData->kdSadar,
            "sistole"               => floatval($bridgingData->sistole),
            "diastole"              => floatval($bridgingData->diastole),
            "beratBadan"            => floatval($bridgingData->beratBadan),
            "tinggiBadan"           => floatval($bridgingData->tinggiBadan),
            "respRate"              => floatval($bridgingData->respRate),
            "heartRate"             => floatval($bridgingData->heartRate),
            "lingkarPerut"          => floatval($bridgingData->lingkarPerut),
            "terapi"                => $bridgingData->terapi,
            "kdStatusPulang"        => $bridgingData->kdStatusPulang,
            "tglPulang"             => date("d-m-Y", strtotime($bridgingData->tglPulang)),
            "kdDokter"              => $bridgingData->kdDokter,
            "kdDiag1"               => $bridgingData->kdDiag1,
            "kdDiag2"               => $bridgingData->kdDiag2,
            "kdDiag3"               => $bridgingData->kdDiag3,
            "kdPoliRujukInternal"   => $bridgingData->kdPoliRujukInternal,
            "kdTacc"                => $bridgingData->kdTacc,
            "alasanTacc"            => $bridgingData->alasanTacc
        ];

        // Rujuk lanjut? true
        if ($bridgingData->rujukLanjutKdPpk) {
            $bridgingPayload["rujukLanjut"] = [
                "tglEstRujuk"   => date("d-m-Y", strtotime($bridgingData->rujukLanjutTglEstimasiRujuk)),
                "kdppk"         => $bridgingData->rujukLanjutKdPpk
            ];
        }

        // Rujuk lanjut spesialis? true
        if ($bridgingData->rujukLanjutSubSpesialisKdSubSpesialis1) {
            $bridgingPayload["rujukLanjut"]["subSpesialis"] = [
                "kdSubSpesialis1"   => $bridgingData->rujukLanjutSubSpesialisKdSubSpesialis1,
                "kdSarana"          => $bridgingData->rujukLanjutSubSpesialisKdSarana
            ];
            $bridgingPayload["rujukLanjut"]["khusus"] = null;
        }

        // Rujuk lanjut sarana khusus
        if ($bridgingData->rujukLanjutKhususKdKhusus) {
            $bridgingPayload["rujukLanjut"]["khusus"] = [
                "kdKhusus"          => $bridgingData->rujukLanjutKhususKdKhusus,
                "kdSubSpesialis"    => $bridgingData->rujukLanjutKhususKdSubSpesialis,
                "catatan"           => $bridgingData->rujukLanjutKhususCatatan
            ];
            $bridgingPayload["rujukLanjut"]["subSpesialis"] = null;
        }

        $timestamp  = $this->get_timestamp();
        $result     = $this->make_request($timestamp, $this->url, "POST", $bridgingPayload);
        $decrypted  = $this->decrypt_result(json_decode($result->data), $timestamp);

        if ($result->status >= 200 AND $result < 300) {
            $response = json_decode($decrypted->response);
            $bridgingData->noKunjungan = $response->message;

            $this->kunjungan->save($bridgingData);

            return (object) [
                'status'    => $result->status,
                "response"  => $response
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
     * Method for deleting visitation record from the BPJS Database. You must provide a valid
     * id of bpjs_pcare_kunjungan table.
     * 
     * @param int $id
     * @return stdClass
     */
    public function delete_kunjungan (int $id)
    {
        $raw_data = $this->kunjungan->find($id);
        
        $timestamp = $this->get_timestamp();

        $this->header_init($timestamp);

        $request = curl_init("{$this->uri}/{$raw_data->noKunjungan}");
        curl_setopt($request, CURLOPT_TIMEOUT, 5);
        curl_setopt($request, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_HTTPHEADER, $this->get_headers());
        curl_setopt($request, CURLOPT_CUSTOMREQUEST, "DELETE");
        $data = curl_exec($request);
        $httpStatus = curl_getinfo($request, CURLINFO_HTTP_CODE);
        curl_close($request);

        if ($httpStatus >= 400) {
            return (object) [
                "status"    => $httpStatus,
                "response"  => json_decode($data)->response
            ];    
        }

        $raw_data->deleted = 1;

        $this->kunjungan->save($raw_data);

        return (object) [
            "status"    => $httpStatus,
            "response"  => json_decode($data)->metaData
        ];
    }
    
    public function test(string $noKartu)
    {
        $timestamp = $this->get_timestamp();

        $this->header_init($timestamp);

        $request = curl_init("{$this->uri}/peserta/{$noKartu}");
        curl_setopt($request, CURLOPT_TIMEOUT, 5);
        curl_setopt($request, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_HTTPHEADER, $this->get_headers());
        $data = curl_exec($request);
        curl_close($request);

        $decrypted = $this->decrypt_result(json_decode($data), $timestamp);

        return $decrypted;
    }

    /**
     * Method for save or update given data.
     * 
     * @param stdClass $data_set
     * @return int
     */
    public function save_or_update (stdClass $data_set): int
    {
        $this->kunjungan->save_or_update ($data_set, ["no_reg"]);

        $kunjungan = $this->kunjungan->select("id")->where("noReg", $data_set->noReg)->get()->row();

        return $kunjungan->id;
    }

    /**
     * Method for getting TACC
     * 
     * @param string $kdTacc
     * @return mixed
     */
    public function get_tacc (string $kdTacc)
    {
        $tacc_enum = [
            [ 
                "kdTacc"        => "-1", 
                "nmTacc"        => "Tanpa TACC", 
                "alasanTacc"    => null
            ],
            [ 
                "kdTacc"        => "1", 
                "nmTacc"        => "Time", 
                "alasanTacc"    => ["< 3 Hari", ">= 3 - 7 Hari", ">= 7 Hari"] 
            ],
            [ 
                "kdTacc"        => "2", 
                "nmTacc"        => "Age", 
                "alasanTacc"    => [ "< 1 Bulan", ">= 1 Bulan s/d < 12 Bulan", ">= 1 Tahun s/d < 5 Tahun",">= 5 Tahun s/d < 12 Tahun", ">= 12 Tahun s/d < 55 Tahun", ">= 55 Tahun"]   ],
            [ 
                "kdTacc"        => "3", 
                "nmTacc"        => "Complication", 
                "alasanTacc"    => "Provide valid diagnosis code and name."
            ],
            [ 
                "kdTacc"        => "4", 
                "nmTacc"        => "Comorbidity", 
                "alasanTacc"    => ["< 3 Hari", ">= 3 - 7 Hari", ">= 7 Hari"]
            ]
        ];

        $tacc = (object) array_values(array_filter($tacc_enum, function ($item) use ($kdTacc) {
            return $item['kdTacc'] == $kdTacc;
        }))[0];

        return $tacc->alasanTacc;
    }
}