<?php

/**
 * This is the base class of Pcare service. This library are created to extend the tbsJKN_api.
 * 
 * Author: tripletTrouble (DPS) https://github.com/triplettrouble
 */

require_once(APPPATH."libraries/dpsPcare/Repositories/SettingRepository.php");
require_once("Decrypt_service.php");

class Pcare_service
{
    private $decryptor;
    protected $kdppk;
    protected $setting;
    protected $header;
    protected $base_url;

    /**
     * Contructor method, here we prepared header for every request to BPJS Web Service
     * 
     */
    public function __construct(string $kdppk)
    {
        $this->setting   = new SettingRepository();
        $this->decryptor = new Decypt_service();
        
        $settings        = $this->setting->select()->where("koders", $kdppk)->get()->row();
        
        $this->kdppk     = $kdppk;
        $this->base_url  = $settings->base_url . "pcare-rest-dev/";
    }

    protected function header_init (int $timestamp): void
    {
        $settings = $this->setting->select()->where("koders", $this->kdppk)->get()->row();

        $this->header = (object) [];
        $this->header->timestamp    = $timestamp;
        $this->header->cons_id      = $settings->consid;
        $this->header->user_key     = $settings->user_key;
        $this->header->auth         = base64_encode("{$settings->username_pcare}:{$settings->password_pcare}:{$settings->kode_app_pcare}");
        $this->header->signature    = $this->create_signature("{$settings->consid}&{$timestamp}", $settings->conspas);
    }

    /**
     * Method for getting current timestamp
     * 
     * @return int
     */
    protected function get_timestamp (): int
    {
        date_default_timezone_set('UTC');

        return strval(time()-strtotime('1970-01-01 00:00:00'));
    }

    /**
     * Method for generating signature
     * 
     * @param string $data
     * @param string $secretKey
     * @return string
     */
    protected function create_signature (string $data, string $secretKey): string
    {
        $signature = hash_hmac('sha256', $data, $secretKey, true);

        return base64_encode($signature);
    }

    /**
     * Method for transforming header into header cURL
     * 
     * @return array
     */
    protected function get_headers (): array
    {
        return [
            "Accept: application/json", 
            "X-cons-id:".$this->header->cons_id, 
            "X-timestamp: ".$this->header->timestamp, 
            "X-signature: ".$this->header->signature, 
            "X-authorization: Basic " .$this->header->auth,
            "user_key: {$this->header->user_key}" 
        ];
    }

    /**
     * Method for making a request into specified end point. A timestamp must be provided in order to decrypt the response.
     * 
     * @param int $timestamp
     * @param string $endpoint
     * @param string $method = "GET"
     * @param array $data
     * @return stdClass {status, data} || {status, message}
     */
    public function make_request (int $timestamp, string $endpoint, string $method = "GET", array $data = []): stdClass
    {
        $this->header_init($timestamp);

        $request = curl_init($endpoint);

        curl_setopt($request, CURLOPT_TIMEOUT, 5);
        curl_setopt($request, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_HTTPHEADER, $this->get_headers());

        // If use POST Method
        if ($method == "POST" OR $method == "post") {
            curl_setopt($request, CURLOPT_POST, TRUE);
            curl_setopt($request, CURLOPT_POSTFIELDS, json_encode($data));
        }

        // If use PUT method
        if ($method == "PUT" OR $method == "put") {
            curl_setopt($request, CURLOPT_PUT, TRUE);
            curl_setopt($request, CURLOPT_POSTFIELDS, json_encode($data));
        }

        // If use DELETE method
        if ($method == "DELETE" OR $method == "delete") {
            curl_setopt($request, CURLOPT_CUSTOMREQUEST, "DELETE");
        }

        $data       = curl_exec($request);
        $statusCode = curl_getinfo($request, CURLINFO_HTTP_CODE);
        
        curl_close($request);

        // If request failed
        if (!$data) {
            return (object) [
                "status"    => 500,
                "message"   => [ 
                        [
                            "field" => NULL,
                            "message" => "Internal server error. The server may be busy or your endpoint was not found"
                        ] 
                    ]
            ];
        }

        // If request success
        return (object) [
            "status"    => $statusCode,
            "data"      => $data
        ];
    }

    /**
     * Method for decrypt response
     * 
     * @return stdClass
     */
    protected function decrypt_result (stdClass $result, int $timestamp)
    {
        $settings = $this->setting->select()->where("koders", $this->kdppk)->get()->row();
        
        $raw_string = $result->response;

        if (is_string($raw_string)) {
            $key = "{$settings->consid}{$settings->conspas}".$timestamp;

            $clearResponse = $this->decryptor::decrypt_response($key, $raw_string);
            
            $result->response = $clearResponse;
    
            return $result;
        }

        return $result;
    }
}