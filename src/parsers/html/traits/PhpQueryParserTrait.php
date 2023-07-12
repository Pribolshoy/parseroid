<?php

namespace pribolshoy\parseroid\parsers\html\traits;

/**
 * Трейт предоставляющий функционал для работы с phpQueryObject
 *
 * @package pribolshoy\parseroid\parsers\html\traits
 */
trait PhpQueryParserTrait
{
    /**
     * Путь к библиотеке для парсинга
     * @var string
     */
    protected string $library_path = '/php_query.php';

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
}
