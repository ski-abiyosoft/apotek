<?php

if (!class_exists("Pcare_service")) {
    require "Pcare_service.php";
}

require APPPATH . "libraries/dpsPcare/Repositories/ClubProlanisRepository.php";
require APPPATH . "libraries/dpsPcare/Repositories/KegiatanKelompokRepository.php";
require APPPATH . "libraries/dpsPcare/Repositories/PesertaKegiatanRepository.php";

class Pcare_kelompok extends Pcare_service
{
    private $club_prolanis;
    private $url;
    private $kegiatan_kelompok;
    private $peserta_kegiatan;

    public function __construct(array $arg)
    {
        parent::__construct($arg["kdppk"]);

        $this->url                  = $this->base_url . "kelompok";
        $this->club_prolanis        = new ClubProlanisRepository();
        $this->kegiatan_kelompok    = new KegiatanKelompokRepository();
        $this->peserta_kegiatan     = new PesertaKegiatanRepository();
    }

    /**
     * Method for getting medicine data from BPJS database
     * 
     * @param string $kdProgram
     */
    public function get_club_prolanis (string $kdProgram)
    {
        $timestamp  = $this->get_timestamp();
        $result     = $this->make_request($timestamp, "{$this->url}/club/{$kdProgram}");

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

    /**
     * Method for creating kegiatan kelompok
     * 
     * @param stdClass $dataset
     */
    public function add_kegiatan_kelompok (stdClass $dataset)
    {
        $timestamp = $this->get_timestamp();
        $payload = [
            "eduId" => NULL,
            "clubId" => intval($dataset->clubId),
            "tglPelayanan" => parse_local_date($dataset->tglPelayanan),
            "kdKegiatan" => $dataset->kdKegiatan,
            "kdKelompok" => $dataset->kdKelompok,
            "materi" => $dataset->materi,
            "pembicara" => $dataset->pembicara,
            "lokasi" => $dataset->lokasi,
            "keterangan" => $dataset->keterangan,
            "biaya" => intval($dataset->biaya)
        ];

        $result = $this->make_request($timestamp, "$this->url/kegiatan", "POST", $payload);

        return (object) [
            "status"    => $result->status,
            "message"   => $result
        ];

        if ($result->status >= 200 AND $result->status < 300) {
            $decrypted = $this->decrypt_result(json_decode($result->data), $timestamp);
            $payload->eduId = json_decode($decrypted->response)->message;
            $this->kegiatan_kelompok->save_or_update($payload);

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
            "message"   => json_decode($result->data)->response
        ];
    }

    /**
     * Method for getting kegiatan kelompok from BPJS WS
     * 
     * @param string $date
     */
    public function get_kegiatan_kelompok ($date)
    {
        $timestamp  = $this->get_timestamp();
        $result     = $this->make_request($timestamp, "{$this->url}/kegiatan/{$date}");

        if ($result->status >= 200 AND $result->status < 300) {
            $decrypted = $this->decrypt_result(json_decode($result->data), $timestamp);

            // If response is not null
            if ($decrypted->response) {
                $response_data = json_decode($decrypted->response);

                // if ($response_data->count > 0) {
                //     foreach ($response_data->list as $data) {
                //         $data->kodeRs       = $this->kdppk;
                //         $data->kdProgram    = $kdProgram;
                //         $data->tglMulai     = parse_local_date($data->tglMulai);
                //         $data->tglAkhir     = parse_local_date($data->tglAkhir);
                        
                //         $this->club_prolanis->save_or_update($data, ["clubId", "kodeRs"]);
                //     }
                // }

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
     * Method handling dataTable request
     * 
     * @param stdClass $request
     * @param array $where clause
     * @return stdClass
     */
    public function create_data_table_response (stdClass $request, array $where_clause): stdClass
    {
        return $this->kegiatan_kelompok->create_data_table($request, $where_clause);
    }
}