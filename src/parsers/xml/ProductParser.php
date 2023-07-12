<?php

namespace pribolshoy\parseroid\parsers\xml;

use pribolshoy\parseroid\parsers\xml\XmlParser;

class ProductParser extends XmlParser
{
    /**
     * Получить товары из документа
     * @return mixed
     */
    public function run()
    {
        $products = [];

        if ($this->document->shop->offers && $this->document->shop->offers->offer) {
            foreach ($this->document->shop->offers->offer as $offer) {
                $products[] = [
                    'title'     => (string)$offer->name,
                    'name'      => (string)$offer->name,
                    'hash'      => md5((string)$offer->name),
                    'hashname'  => (string)$offer->hashname,
                    'available' => (int)$offer->attributes()->available,
                    'url'       => (string)$offer->url,
                    'link'      => (string)$offer->link,
                    'price'     => (int)$offer->price,
                    'oldprice'  => (int)$offer->oldprice,
                    'picture'   => (string)$offer->picture,
                    'image'     => (string)$offer->picture,
                ];
            }
        }

        return $products;
    }
}