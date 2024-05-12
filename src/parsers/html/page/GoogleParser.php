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
        $phpQueryDoc = $this->getConvertedDocument($this->getDocument());
        $result[] = $this->parseItem($phpQueryDoc) ?? [];

        return $result;
    }

    /**
     * @param  DOMElement $item
     * @return array|BaseDto
     */
    public function parseItem($item) :array
    {
        if ($value = pq($item->find('input[name="q"]'))->val()) {
            $input_text = $value;
        } else if ($value = pq($item->find('form[action="/search"] textarea'))->val()) {
            $input_text = $value;
        }

        $item = [
            'input_text' => $input_text ?? null,
        ];

        return $item;
    }

}