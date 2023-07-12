<?php

namespace pribolshoy\parseroid\helpers;

class CurlHelper
{
    /**
     * useragent
     * @var string
     */
    const USERAGENT = 'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36';

    /**
     * Get html resource by CURL
     *
     * @param $url
     *
     * @return bool|string
     */
    public static function curl($url)
    {

        $ch = curl_init();

        $cookie_path = __DIR__ . '/cookies.txt';

        // TODO: add proxy

        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, static::USERAGENT);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);

        $page = curl_exec($ch);
        curl_close($ch);

        return $page;
    }

    /**
     * Split html data to headers array
     *
     * @param string $data
     *
     * @return array
     */
    public static function getHeaders(string $data)
    {
        $page_parts = explode('<!DOCTYPE html>', $data);

        if (count($page_parts)) {
            $headers = trim($page_parts[0]);
            return explode("\n", $headers);
        }
        return [];
    }
}