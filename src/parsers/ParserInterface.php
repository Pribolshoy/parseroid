<?php

namespace pribolshoy\parseroid\parsers;

/**
 * Interface ParserInterface
 *
 * @package pribolshoy\parseroid
 */
interface ParserInterface
{
    /**
     * Get items by parsing of resource.
     *
     * @param string $resource
     * @param int $page_offset
     * @param bool $refresh обнулить счетчик номера страницы
     *                      в цикле парсинга
     *
     * @return array
     */
    public function getItems(string $resource, int $page_offset = 1, bool $refresh = true);

    /**
     * Get item by parsing of resource.
     *
     * @param string $resource
     *
     * @return array
     */
    public function getItem(string $resource);

    /**
     * Здесь происходит реализация обработки ресурса
     * конкретных парсеров-наследников
     *
     * @return mixed
     */
    public function run();
}