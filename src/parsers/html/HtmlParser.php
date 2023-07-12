<?php

namespace pribolshoy\parseroid\parsers\html;

use pribolshoy\parseroid\parsers\BaseParser;
use pribolshoy\parseroid\parsers\html\traits\HtmlDocumentParserTrait;
use pribolshoy\parseroid\parsers\html\traits\HtmlParserTrait;
use pribolshoy\parseroid\parsers\html\traits\PhpQueryParserTrait;

/**
 * Class HtmlParser
 * Базовый абстрактный класс с интерфейсом парсера страниц сайтов.
 * Все конкретные парсеры сайтов должны наследоваться от него.
 * Включает в себя необходимые трейты для реализации
 * парсинга.
 *
 * @package pribolshoy\parseroid\parsers\html
 */
abstract class HtmlParser extends BaseParser
{
    use HtmlDocumentParserTrait,
        PhpQueryParserTrait;

    protected string $catalogFlagSelector = '';

    public function init()
    {
        $this->initPhpQuery();
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
        $this->setUrl($resource)
            ->resetParseAttempts();

        // парсим сайт по адресу, получаем html страницу
        if ($this->parse() && $this->document) $items = $this->run();

        return $items[0] ?? [];
    }

    public function isPageCatalog() :bool
    {
        if ($this->catalogFlagSelector && $this->document) {
            $document = $this->getPhpQueryObject($this->document);
            // Если находит данный элемент, значит это каталог, а не карта товара, тогда 404
            if (count($document->find($this->catalogFlagSelector)) ) {
                $this->setStatusCode(404);
                return true;
            }
        } else {
            throw new \Exception('Перед определением каталога необходимо спарсить страницу! URL: ' . $this->getUrl(), 80);
        }

        return false;
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