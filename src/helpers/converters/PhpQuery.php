<?php

namespace pribolshoy\parseroid\helpers\converters;

class PhpQuery implements ConverterInterface
{
    protected string $library_path = '/php_query.php';

    public function __construct()
    {
        require_once __DIR__ . $this->library_path;
    }

    /**
     * @param string $document
     * @return \phpQueryObject|\QueryTemplatesParse|\QueryTemplatesSource|\QueryTemplatesSourceQuery
     */
    public static function convert(string $document)
    {
        return \phpQuery::newDocument($document);
    }
}