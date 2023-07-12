<?php

namespace pribolshoy\parseroid\parsers\html\page;

use app\modules\parser\components\dto\ProductDetailDto;
use DOMElement;
use pribolshoy\parseroid\dto\BaseDto;
use pribolshoy\parseroid\parsers\html\HtmlParser;

class GoogleParser extends HtmlParser
{
    public function run()
    {
        /** @var \phpQueryObject */
        $phpQueryDoc = $this->getPhpQueryObject($this->document);
        $result[] = $this->parseItem($phpQueryDoc) ?? [];

        return $result;
    }

    /**
     * @param DOMElement $item
     * @return array|BaseDto
     */
    public function parseItem($pqItem) :array
    {
        $result = [];

        $input_text = pq($pqItem->find('form[action="/search"] textarea'))->val();

        $items[] = [
            'input_text' => $input_text,
        ];

        return $items;
    }

}