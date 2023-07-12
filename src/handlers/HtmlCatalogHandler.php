<?php

namespace pribolshoy\parseroid\handlers;

use pribolshoy\parseroid\factories\BaseFactory;
use pribolshoy\parseroid\factories\HtmlCatalogParserFactory;
use pribolshoy\parseroid\parsers\html\HtmlCatalogParser;
use pribolshoy\parseroid\parsers\html\HtmlParser;
use pribolshoy\parseroid\parsers\xml\ProductParser;

class HtmlCatalogHandler extends \pribolshoy\parseroid\handlers\BaseResourceHandler
{
    protected function initParser()
    {
        if (!isset($this->config['parser_name'])) {
            throw new \RuntimeException('Отсутствует ID поставщика.', 202);
        }

        /** @var $parser HtmlCatalogParser */
        // Подключаем парсер через парсер менеджер
        $this->parser = $parser = ($objManager = new BaseFactory())
            ->setInstancesNamespace("pribolshoy\\parseroid\\parsers\\html\\catalog\\")
            ->create($this->config['parser_name']);

        // если переданна лимитация парсинга страниц
        if ($this->config['page_limit']) $parser->setPageLimit($this->config['page_limit']);
    }

    /**
     * Индивидуальный метод адаптер только для
     * парсинга каталогов сайта
     *
     * @return int
     */
    public function getPageOffset()
    {
        /** @var $parser HtmlCatalogParser */
        $parser = $this->parser;
        return ($parser->getActualPageNum() + 1);
    }
}