<?php 

require_once "Pcare_service.php";
require_once APPPATH . "libraries/dpsPcare/Repositories/PesertaRepository.php";

class Pcare_peserta extends Pcare_service
{
    private $url;
    private $peserta;
    
    public function __construct(array $arg)
    {
        parent::__construct($arg["kdppk"]);

        $this->url      = $this->base_url . "peserta";
        $this->peserta  = new PesertaRepository();
    }

    /**
     * Method for getting participant data form BPJS database
     * 
     * @param string $noKatu
     * @param string $jenisKartu
     * @return stdClass
     */
    public function get_peserta (string $noKartu, string $jenisKartu = "noka"): stdClass
    {
        $endpoint   = $this->url . "/{$jenisKartu}/{$noKartu}";
        $timestamp  = $this->get_timestamp();
        $result     = $this->make_request($timestamp, $endpoint);

        // If request completed with 200 status
        if ($result->status >= 200 AND $result->status < 300) {
            $decrypted = $this->decrypt_result(json_decode($result->data), $timestamp);

            // If response is not null
            if ($decrypted->response) {
                $response_data = json_decode($decrypted->response);
                $data_set = [
                    "noKartu"           => $response_data->noKartu,
                    "nama"              => $response_data->nama,
                    "kodeRs"            => "",
                    "hubunganKeluarga"  => $response_data->hubunganKeluarga,
                    "sex"               => $response_data->sex,
                    "tglLahir"          => parse_local_date($response_data->tglLahir),
                    "tglMulaiAktif"     => parse_local_date($response_data->tglMulaiAktif),
                    "tglAkhirBerlaku"   => parse_local_date($response_data->tglAkhirBerlaku),
                    "kdProviderPst"     => $response_data->kdProviderPst->kdProvider,
                    "nmProviderPst"     => $response_data->kdProviderPst->nmProvider,
                    "kdProviderGigi"    => $response_data->kdProviderGigi->kdProvider,
                    "nmProviderGigi"    => $response_data->kdProviderGigi->nmProvider,
                    "kdKelas"           => $response_data->jnsKelas->kode,
                    "nmKelas"           => $response_data->jnsKelas->nama,
                    "kdJnsPeserta"      => $response_data->jnsPeserta->kode,
                    "nmJnsPeserta"      => $response_data->jnsPeserta->nama,
                    "golDarah"          => $response_data->golDarah,
                    "noHP"              => $response_data->noHP,
                    "noKTP"             => $response_data->noKTP,
                    "pstProl"           => $response_data->pstProl,
                    "pstPrb"            => $response_data->pstPrb,
                    "aktif"             => $response_data->aktif,
                    "ketAktif"          => $response_data->ketAktif,
                    "kdAsuransi"        => $response_data->asuransi->kdAsuransi,
                    "nmAsuransi"        => $response_data->asuransi->nmAsuransi,
                    "noAsuransi"        => $response_data->asuransi->noAsuransi,
                    "cob"               => $response_data->asuransi->cob,
                    "tunggakan"         => $response_data->tunggakan
                ];

                $this->peserta->save_or_update((object) $data_set, ["noKartu"]);

                return (object) [
                    "status"    => $result->status,
                    "data"      => $data_set
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