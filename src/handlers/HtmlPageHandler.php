<?php

namespace pribolshoy\parseroid\handlers;

use pribolshoy\parseroid\factories\BaseFactory;
use pribolshoy\parseroid\parsers\html\HtmlParser;

class HtmlPageHandler extends BaseResourceHandler
{
    protected function initParser()
    {
        if (!isset($this->config['parser_name'])) {
            throw new \RuntimeException('Отсутствует ID поставщика.', 202);
        }

        $namespace = "pribolshoy\\parseroid\\parsers\\html\\page\\";
        if (isset($this->config['namespace'])
            && $this->config['namespace']
        ) {
            $namespace = $this->config['namespace'];
        }

        /** @var $parser HtmlParser */
        $this->parser = $parser = ($objManager = new BaseFactory())
            ->setInstancesNamespace($namespace)
            ->create($this->config['parser_name']);
    }
}