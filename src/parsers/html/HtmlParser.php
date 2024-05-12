<?php

namespace pribolshoy\parseroid\parsers\html;

use pribolshoy\parseroid\exceptions\ParserException;
use pribolshoy\parseroid\helpers\converters\PhpQuery;
use pribolshoy\parseroid\parsers\UrlParser;

/**
 * Class HtmlParser
 *
 * Base abstract class with interface for parsing one page html resources.
 * Includes all necessary traits for working.
 *
 * @package pribolshoy\parseroid
 */
abstract class HtmlParser extends UrlParser
{
    protected ?string $documentConverterClass = PhpQuery::class;

    /**
     * Html selector for recognizing that page is catalog
     *
     * @var string
     */
    protected string $catalogFlagSelector = '';

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
            $phpQueryObject = $this->getConvertedDocument($document);

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