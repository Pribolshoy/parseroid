<?php

namespace pribolshoy\parseroid\parsers\html;

use pribolshoy\parseroid\parsers\UrlParser;

/**
 * Class BaseHtmlParser
 *
 * Includes all necessary base properties and methods.
 *
 * @package pribolshoy\parseroid
 */
abstract class BaseHtmlParser extends UrlParser
{
    /**
     * Path to library for parsing html document
     * @var string
     */
    protected string $library_path = '/php_query.php';

    public function init()
    {
        parent::init();
        $this->initPhpQuery();
    }

    /**
     * Подключение phpQueryObject
     *
     * @return $this
     */
    protected function initPhpQuery()
    {
        require_once __DIR__ . $this->library_path;
        return $this;
    }

    /**
     * Получить phpQueryObject на основе переданного
     * html кода  страницы
     *
     * @param string $document
     *
     * @return \phpQueryObject
     */
    public function getPhpQueryObject(string $document) :\phpQueryObject
    {
        return \phpQuery::newDocument($document);
    }

    /**
     * Является ли данная страница страницей каталога.
     * Реализуется в каждом конкретном парсере сайта
     * самостоятельно.
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function isPageCatalog() :bool
    {
        return false;
    }

    /**
     * Return summary of parsing
     *
     * @return array
     */
    public function getSummary(): array
    {
        return [];
    }
}