<?php

namespace pribolshoy\parseroid\handlers;

use pribolshoy\parseroid\parsers\xml\ProductParser;

class XmlHandler extends BaseResourceHandler
{
    protected string $parser_class = ProductParser::class;
}