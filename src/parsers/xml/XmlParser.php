<?php

namespace pribolshoy\parseroid\parsers\xml;

use pribolshoy\parseroid\parsers\BaseParser;

abstract class XmlParser extends BaseParser
{
    /**
     * Получить товары из ресурса
     * Здесь происходит общая обработка для всех
     * наследников
     *
     * @param string $resource
     * @param int $page_offset
     * @param bool $refresh
     *
     * @return mixed
     */
    public function getItems(string $resource, int $page_offset = 1, bool $refresh = true)
    {
        $file_data = file_get_contents($resource);
        $this->document = new \SimpleXMLElement($file_data);
        return $this->run();
    }

}