<?php

namespace pribolshoy\parseroid\helpers\converters;

class SimpleXml implements ConverterInterface
{
    /**
     * @param string $document
     *
     * @return \SimpleXMLElement
     */
    public static function convert(string $document)
    {
        return new \SimpleXMLElement($document);
    }
}