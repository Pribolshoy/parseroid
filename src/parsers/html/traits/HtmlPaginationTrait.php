<?php

namespace pribolshoy\parseroid\parsers\html\traits;

/**
 * Class HtmlPaginationTrait
 * Трейт парсера, включающий в себя вспомогательные
 * методы и свойства для пагинации.
 *
 * @package pribolshoy\parseroid\parsers
 */
trait HtmlPaginationTrait
{
    use PhpQueryParserTrait;

    /**
     * Общее количество страниц каталога для парсинга
     * @var int
     */
    protected int $max_page_num = 0;

    /**
     * Актуальная страница пагинации каталога
     * @var int
     */
    protected int $actual_page_num = 1;

    /**
     * Количество спарсеных страниц каталога.
     * Не зависит от того сколько было запусков
     * парсинга, считает именно спарсенные страницы
     * каталога.
     * @var int
     */
    protected int $catalog_page_count = 0;

    /**
     * Максимальное количество спарсеных страниц
     * каталога за раз
     * @var int
     */
    protected int $page_limit = 10;

    /**
     * Увеличить количество спарсеных страниц каталога.
     */
    public function incrCatalogPageCount()
    {
        return ++$this->catalog_page_count;
    }

    /**
     * Получить счетчик спарсеных страниц каталога.
     */
    public function getCatalogPageCount()
    {
        return $this->catalog_page_count;
    }

    /**
     * Установка максимального количества спарсеных
     * страниц каталога за раз
     *
     * @param int $num
     *
     * @return static
     */
    function setPageLimit(int $num)
    {
        $this->page_limit = $num;
        return $this;
    }

    /**
     * Получение максимального количества спарсеных
     * страниц каталога за раз
     */
    function getPageLimit() :int
    {
        return $this->page_limit;
    }

    /**
     * Установка актуальной страницы пагинации каталога
     *
     * @param int $num
     *
     * @return static
     */
    function setActualPageNum(int $num)
    {
        $this->actual_page_num = $num;
        return $this;
    }

    /**
     * Получение актуальной страницы пагинации каталога
     *
     */
    function getActualPageNum() :int
    {
        return $this->actual_page_num;
    }

    /**
     * Установка количества последней страницы
     * в пагинации каталога.
     * Реализуется в каждом конкретном парсере сайта
     * самостоятельно.
     *
     * @param \phpQueryObject $phpQueryDoc
     *
     * @return static
     * @throws \Exception
     */
    public function setMaxPageNum(\phpQueryObject $phpQueryDoc)
    {
        throw new \Exception('Функционал определения пагинации не реализован!', 81);
    }

    /**
     * Получение количество последней страницы
     * в пагинации каталога
     *
     */
    public function getMaxPageNum() :int
    {
        return $this->max_page_num;
    }

    /**
     * Подготовительные действия перед парсингом
     * страинцы каталога.
     * При необходимеости переопределяется в наследнике.
     *
     * @param string $document
     *
     * @return $this
     * @throws \Exception
     */
    public function prepareCatalog(string $document)
    {
        return $this;
    }

    /**
     * Парсинг, обработка и сбор результатов
     * для каждого элемента каталога.
     * Реализуется в каждом конкретном парсере сайта
     * самостоятельно.
     *
     * @param DOMElement $item
     *
     * @return array
     */
    public function parseItem(DOMElement $item) :array
    {
        return [];
    }
}