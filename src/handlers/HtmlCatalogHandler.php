<?php

namespace pribolshoy\parseroid\handlers;

use pribolshoy\parseroid\parsers\html\HtmlCatalogParser;

/**
 * Class HtmlCatalogHandler
 *
 * Class for catalog html parser, because it has some specific behaivour
 *
 * @package pribolshoy\parseroid\handlers
 */
class HtmlCatalogHandler extends \pribolshoy\parseroid\handlers\BaseResourceHandler
{
    protected function afterParserInit()
    {
        /** @var HtmlCatalogParser $parser */
        if ($parser = $this->parser) {
            if ($pageLimit = $this->getConfig('page_limit')) {
                $parser->setPageLimit($pageLimit);
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isParsingFinished() :bool
    {
        /** @var $parser HtmlCatalogParser */
        // если общее количество страниц каталога больше актуальной страницы пагинации
        if (($parser = $this->parser)
            && $parser->getMaxPageNum() > $parser->getActualPageNum()
        ) {
            return false;
        }

        return true;
    }

    /**
     * @return int
     */
    public function getPageOffset()
    {
        /** @var $parser HtmlCatalogParser */
        $parser = $this->parser;
        return ($parser->getActualPageNum() + 1);
    }
}