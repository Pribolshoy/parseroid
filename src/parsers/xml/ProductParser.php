<?php

namespace pribolshoy\parseroid\parsers\xml;

class ProductParser extends XmlParser
{
    /**
     * @return array
     */
    public function run() :array
    {
        if ($this->document->shop->offers && $this->document->shop->offers->offer) {
            foreach ($this->document->shop->offers->offer as $offer) {
                $items[] = [
                    'title'     => (string)$offer->name,
                ];
            }
        }

        return $items ?? [];
    }
}