<?php

namespace pribolshoy\parseroid\helpers;

class StringHelper
{

    /**
     * Converting string to camel format
     *
     * @param  string $string
     * @param  string $encoding
     * @return string|string[]|null
     */
    public static function camelize(string $string, string $encoding = 'UTF-8')
    {
        if (empty($string)) {
            return $string;
        }

        $string = preg_replace('/[^\pL\pN]+/u', ' ', $string);

        $parts = preg_split('/(\s+[^\w]+\s+|^[^\w]+\s+|\s+)/u', $string, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $newParts = [];
        $ucfirstEven = trim(mb_substr($parts[0], -1, 1, $encoding)) === '';
        foreach ($parts as $key => $value) {
            $value = mb_strtolower($value);
            $isEven = (bool)($key % 2);
            if ($ucfirstEven === $isEven) {
                $newParts[$key] = preg_replace('#\s#', '', ucfirst($value));
            }
        }

        return implode('', $newParts);
    }
}