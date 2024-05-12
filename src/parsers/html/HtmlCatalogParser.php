<?php

namespace pribolshoy\parseroid\parsers\html;

/**
 * Class HtmlCatalogParser
 *
 * Base abstract class with interface for parsing html resources of catalogs.
 * Includes all necessary traits for working.
 *
 * @package pribolshoy\parseroid
 */
abstract class HtmlCatalogParser extends HtmlParser
{
    /**
     * Общее количество страниц каталога для парсинга
     *
     * @var int
     */
    protected int $max_page_num = 1;

    /**
     * Актуальная страница пагинации каталога
     *
     * @var int
     */
    protected int $actual_page_num = 1;

    /**
     * Количество спарсеных страниц каталога.
     * Не зависит от того сколько было запусков
     * парсинга, считает именно спарсенные страницы
     * каталога.
     *
     * @var int
     */
    protected int $downloaded_resources_count = 0;

    /**
     * Максимальное количество спарсеных страниц
     * каталога за раз
     *
     * @var int
     */
    protected int $page_limit = 10;

    /**
     * Увеличить количество спарсеных страниц каталога.
     */
    public function incrDownloadedResourcesCount()
    {
        return ++$this->downloaded_resources_count;
    }

    /**
     * Получить счетчик спарсеных страниц каталога.
     */
    public function getDownloadedResourcesCount()
    {
        return $this->downloaded_resources_count;
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
     */
    function getActualPageNum() :int
    {
        return $this->actual_page_num;
    }

    /**
     * Parse and returns elements by url resource
     *
     * @param string $resource    url resource for parsing
     * @param int    $page_offset num of catalog page where start to parse
     * @param bool   $refresh     flag to set parsed_page_count property to 0
     *
     * @return mixed
     * @throws \Exception
     */
    public function getItems(string $resource, int $page_offset = 1, bool $refresh = true)
    {
        if ($this->initDocument($resource)) {
            $this->setActualPageNum($page_offset)
                ->prepareCatalog($this->document);

            $items = $this->run();
        }

        return $items ?? [];
    }

    /**
     * Парсинг страницы и извлечение из нее
     * необходимых данных - пагинация и тд
     *
     * @param string $document
     *
     * @return $this
     * @throws \Exception
     */
    public function prepareCatalog(string $document)
    {
        $this->setMaxPageNum($this->getConvertedDocument($document));
        return $this;
    }

    /**
     * Получение количество последней страницы
     * в пагинации каталога
     */
    public function getMaxPageNum() :int
    {
        return $this->max_page_num;
    }

    function setMaxPageNum($phpQueryDoc)
    {
        if ($this->paginationsSelector
            && count($elements = $phpQueryDoc->find($this->paginationsSelector))
        ) {
            foreach($elements as $pagination) {
                if ($pageNUm = (int)pq($pagination)->text()) {
                    $this->max_page_num = $pageNUm;
                }
            }
        }
        return $this;
    }

    public function getSummary(): array
    {
        return array_merge(
            parent::getSummary(), [
            'max_attempts'      => $this->getMaxAttempts(),
            'parse_attempts'    => $this->getDownloadAttempts(),
            'attempts_to_download_resource' => $this->attempts_to_download_resource,
            ]
        );
    }
}