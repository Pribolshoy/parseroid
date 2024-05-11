<?php

namespace pribolshoy\parseroid\parsers\html;

use pribolshoy\parseroid\helpers\Curl;
use pribolshoy\parseroid\parsers\html\traits\HtmlPaginationTrait;

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
    use HtmlPaginationTrait;

    /**
     * Parse and returns elements by url resource
     *
     * @param string $url url resource for parsing
     * @param int $page_offset num of catalog page where start to parse
     * @param bool $refresh flag to set parsed_page_count property to 0
     *
     * @return mixed
     * @throws \Exception
     */
    public function getItems(string $url, int $page_offset = 1, bool $refresh = true)
    {
        $this->setUrl($url);

        if ($refresh) $this->resetParseAttempts();

        // парсим сайт по адресу, получаем html страницу
        $this->document = $this->parse();

        if ($this->document) {
            $this->setActualPageNum($page_offset);
            $this->prepareCatalog($this->document);
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
     * @return HtmlPaginationTrait
     * @throws \Exception
     */
    public function prepareCatalog(string $document)
    {
        $phpQueryDoc = $this->getPhpQueryObject($document);
        $this->setMaxPageNum($phpQueryDoc);
        return $this;
    }

    function setMaxPageNum($phpQueryDoc)
    {
        if ($this->paginationsSelector && count($elements = $phpQueryDoc->find($this->paginationsSelector)) ) {
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
        return array_merge($this->getSummary(), [
            'max_attempts'      => $this->getMaxAttempts(),
            'parse_attempts'    => $this->getParseAttempts(),
            'parsed_page_count' => $this->parsed_page_count,
        ]);
    }
}