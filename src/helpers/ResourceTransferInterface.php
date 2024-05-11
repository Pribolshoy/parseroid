<?php

namespace pribolshoy\parseroid\helpers;

interface ResourceTransferInterface
{
    /**
     * Get html resource by CURL
     *
     * @param string $url
     *
     * @return string
     */
    public static function get(string $url):string;

    /**
     * Split html data to headers array
     *
     * @param string $data
     *
     * @return array
     */
    public static function getHeaders(string $data) :array;
}