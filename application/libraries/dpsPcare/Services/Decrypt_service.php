<?php

require_once "vendor/autoload.php";

class Decypt_service
{
    // function decrypt
    private function stringDecrypt(string $key, string $string){
        $encrypt_method = 'AES-256-CBC';

        // hash
        $key_hash = hex2bin(hash('sha256', $key));
    
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);

        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
    
        return $output;
    }

    // function lzstring decompress 
    // download libraries lzstring : https://github.com/nullpunkt/lz-string-php
    private function decompress(string $string){
        return \LZCompressor\LZString::decompressFromEncodedURIComponent($string);
    }

    /**
     * Method for parsing response into human readable JSON string
     * 
     * @param string $key
     * @param string $response
     * @return string
     */
    public function decrypt (string $key, string $response): string
    {
        $encrypted = $this->stringDecrypt($key, $response);
        return $this->decompress($encrypted);
    }

    public static function decrypt_response (string $key, string $response): string
    {
        $decyptor = new self();

        return $decyptor->decrypt($key, $response);
    }
}