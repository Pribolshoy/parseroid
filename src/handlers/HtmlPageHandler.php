<?php

namespace pribolshoy\parseroid\handlers;

use pribolshoy\parseroid\factories\BaseFactory;

class HtmlPageHandler extends BaseResourceHandler
{
    protected function initParser()
    {
        if (!isset($this->config['parser_name'])) {
            throw new \RuntimeException('Отсутствует ID поставщика.', 202);
        }

        /** @var $parser HtmlParser */
        // Подключаем парсер через парсер менеджер
        $this->parser = $parser = ($objManager = new BaseFactory())
            ->setInstancesNamespace("pribolshoy\\parseroid\\parsers\\html\\page\\")
            ->create($this->config['parser_name']);
    }
}