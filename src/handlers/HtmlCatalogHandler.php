<?php

namespace pribolshoy\parseroid\handlers;

use pribolshoy\parseroid\factories\BaseFactory;
use pribolshoy\parseroid\parsers\html\HtmlCatalogParser;

class HtmlCatalogHandler extends \pribolshoy\parseroid\handlers\BaseResourceHandler
{
    protected function initParser()
    {
        if (!isset($this->config['parser_name'])) {
            throw new \RuntimeException('Отсутствует ID поставщика.', 202);
        }

        $namespace = "pribolshoy\\parseroid\\parsers\\html\\page\\";
        if ($this->config['namespace']) {
            $namespace = $this->config['namespace'];
        }

        /** @var $parser HtmlCatalogParser */
        // Подключаем парсер через парсер менеджер
        $this->parser = $parser = ($objManager = new BaseFactory())
            ->setInstancesNamespace($namespace)
            ->create($this->config['parser_name']);

        // если переданна лимитация парсинга страниц
        if ($this->config['page_limit']) $parser->setPageLimit($this->config['page_limit']);
    }

    public function isParsingFinished() :bool
    {
        $parser = $this->parser;

        /** @var $parser HtmlCatalogParser */
        // если общее количество страниц каталога больше актуальной страницы пагинации
        if ($parser->getMaxPageNum() > $parser->getActualPageNum()) {
            return false;
        }

        return true;
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