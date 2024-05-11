<?php

namespace pribolshoy\parseroid\parsers\html\page;

use DOMElement;
use pribolshoy\parseroid\dto\BaseDto;
use pribolshoy\parseroid\parsers\html\HtmlParser;

class GoogleParser extends HtmlParser
{
    public function run()
    {
        /** @var \phpQueryObject */
        $phpQueryDoc = $this->getPhpQueryObject($this->getDocument());
        $result[] = $this->parseItem($phpQueryDoc) ?? [];

        return $result;
    }

    /**
     * @param DOMElement $item
     * @return array|BaseDto
     */
    public function parseItem($item) :array
    {
        $input_text = pq($item->find('input[name="q"]'))->val();

        $item = [
            'input_text' => $input_text,
        ];

        return $item;
    }

}