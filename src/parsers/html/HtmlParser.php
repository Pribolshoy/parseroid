<?php

namespace pribolshoy\parseroid\parsers\html;

use pribolshoy\parseroid\exceptions\ParserException;

/**
 * Class HtmlParser
 *
 * Base abstract class with interface for parsing one page html resources.
 * Includes all necessary traits for working.
 *
 * @package pribolshoy\parseroid
 */
abstract class HtmlParser extends BaseHtmlParser
{

    /**
     * Html selector for recognizing that page is catalog
     * @var string
     */
    protected string $catalogFlagSelector = '';

    /**
     * Parse resource page and return collected data
     *
     * @param string $resource
     *
     * @return array
     * @throws ParserException
     */
    public function getItem(string $resource)
    {
        if ($this->initDocument($resource)) {
            $items = $this->run();
        }

        return $items[0] ?? [];
    }

    /**
     * Returns is parsed page is catalog page or not
     *
     * @return bool
     * @throws \Exception
     */
    public function isPageCatalog() :bool
    {
        if ($this->catalogFlagSelector
            && $document = $this->getDocument()
        ) {
            $phpQueryObject = $this->getPhpQueryObject($document);

            if (count($phpQueryObject->find($this->catalogFlagSelector)) ) {
                if ($this->isResourceTransferActive()) {
                    $this->setStatusCode(404);
                }

                return true;
            }
        } else {
            throw new ParserException('Not set resource or not filled catalogFlagSelector prop');
        }

        return false;
    }
}