<?php

namespace pribolshoy\parseroid\parsers;

/**
 * Class BaseParser
 * A basic abstract class with a parser interface.
 * All specific parsers (site/xml/xls) must inherit from it.
 *
 * @package pribolshoy\parseroid
 */
abstract class BaseParser
{
    protected $document;

    protected array $items = [];

    public function __construct()
    {
        $this->init();
    }

    /**
     * Дополнительная конфигурация при создании
     */
    public function init()
    {
    }

    /**
     * Парсинг элементов сайта через переданный
     * ресурс (ссылка на страницу каталога, файл)
     *
     * @param string $resource
     * @param int $page_offset
     * @param bool $refresh обнулить счетчик номера страницы
     *                      в цикле парсинга
     *
     * @return array
     */
    public function getItems(string $resource, int $page_offset = 1, bool $refresh = true)
    {
        return [];
    }

    /**
     * Парсинг элементов сайта через переданный
     * ресурс (ссылка на страницу каталога, файл)
     *
     * @param string $resource
     *
     * @return array
     */
    public function getItem(string $resource)
    {
        return [];
    }

    /**
     * Здесь происходит реализация обработки ресурса
     * конкретных парсеров-наследников
     *
     * @return mixed
     */
    abstract public function run();
}