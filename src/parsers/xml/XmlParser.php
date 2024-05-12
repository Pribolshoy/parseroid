<?php

namespace pribolshoy\parseroid\parsers\xml;

use pribolshoy\parseroid\helpers\converters\SimpleXml;
use pribolshoy\parseroid\parsers\UrlParser;

/**
 * Class XmlParser
 *
 * Base abstract class with interface for parsing xml resources.
 *
 * @package pribolshoy\parseroid
 */
abstract class XmlParser extends UrlParser
{
    protected ?string $documentConverterClass = SimpleXml::class;
}