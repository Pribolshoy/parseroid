<?php

namespace pribolshoy\parseroid\parsers\html;

use pribolshoy\parseroid\parsers\html\traits\HtmlPaginationTrait;

/**
 * Class HtmlCatalogParser
 * Базовый абстрактный класс с интерфейсом парсера страниц сайтов
 * каталогового вида.
 * Все конкретные парсеры сайтов должны наследоваться от него.
 *
 * @package pribolshoy\parseroid\parsers\html
 */
abstract class HtmlCatalogParser extends HtmlParser
{
    use HtmlPaginationTrait;

    protected string $paginationsSelector = '';

    protected string $catalogFlagSelector = '';

    /**
     * Получить спарсенные элементы по переданной ссылке.
     *
     * @param string $url адрес для парсинга
     * @param int $page_offset с какой страницы начинать парсинг
     * @param bool $refresh обнулить счетчик номера страницы
     *                      в цикле парсинга
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
        return array_merge($this->getOrigSummary(), [
            'max_attempts'      => $this->getMaxAttempts(),
            'parse_attempts'    => $this->getParseAttempts(),
            'parsed_page_count' => $this->parsed_page_count,
        ]);
    }
}