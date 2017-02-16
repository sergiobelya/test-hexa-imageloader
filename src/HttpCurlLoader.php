<?php

namespace sergiobelya\TestHexaImageloader;

use sergiobelya\TestHexaImageloader\Exceptions\CurlException;

/**
 * @author Serg
 */
class HttpCurlLoader implements HttpLoaderInterface
{
    protected $ch = null;
    
    protected $curl_options = [
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_POST => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT => 'Hexa curl agent',
        CURLOPT_TIMEOUT => 10,
    ];

    function load($url) {
        $ch = curl_init();
        if ($ch == false) {
            throw new CurlException('curl_init');
        }
        if (!curl_setopt_array($ch, $this->curl_options)) {
            throw new CurlException('curl_setopt_array');
        }
        if (!curl_setopt($ch, CURLOPT_URL, $url)) {
            throw new CurlException('curl_setopt');
        }
        $exec = curl_exec($ch);
        if (!$exec) {
            return false;
        }
        curl_close($ch);
        return $exec;
    }

}
