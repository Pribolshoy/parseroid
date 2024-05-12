<?php

namespace pribolshoy\parseroid\helpers\converters;

class YmlConverter implements ConverterInterface
{
    /**
     * @param string $document
     *
     * @return mixed
     */
    public static function convert(string $document)
    {
        return yaml_parse($document);
    }
}