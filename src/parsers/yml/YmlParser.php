<?php

namespace pribolshoy\parseroid\parsers\yml;

use pribolshoy\parseroid\helpers\converters\YmlConverter;
use pribolshoy\parseroid\parsers\UrlParser;

/**
 * Class YmlParser
 *
 * Base abstract class with interface for parsing yml resources.
 *
 * @package pribolshoy\parseroid
 */
abstract class YmlParser extends UrlParser
{
    protected ?string $documentConverterClass = YmlConverter::class;
}