<?php

namespace pribolshoy\parseroid\parsers;

use pribolshoy\parseroid\exceptions\ParserException;

/**
 * Class BaseParser
 * A basic abstract class with a parser interface.
 * All specific parsers (site/xml/xls) must inherit from it.
 *
 * @package pribolshoy\parseroid
 */
abstract class BaseParser implements ParserInterface
{
    protected ?string $document = null;

    protected array $items = [];

    public function __construct()
    {
        $this->init();
    }

    /**
     * Adding configuring
     */
    public function init()
    {
    }

    /**
     * @param string $resource
     *
     * @return bool
     *
     * @throws ParserException
     */
    public function initDocument(string $resource) :bool
    {
        if (is_file($resource)) {
            $resource = file_get_contents($resource);
        }

        if (!is_string($resource)) {
            throw new ParserException("Wrong type of resource");
        }

        // only if not empty
        if ($resource) {
            $this->setDocument($resource);
            return true;
        }

        return false;
    }

    /**
     * @return string|null
     */
    public function getDocument() :?string
    {
        return $this->document;
    }

    /**
     * @param string $document
     *
     * @return object
     */
    public function setDocument(string $document) :object
    {
        $this->document = $document;
        return $this;
    }

    /**
     * @param string $resource
     * @param int $page_offset
     * @param bool $refresh
     *
     * @return array
     */
    public function getItems(string $resource, int $page_offset = 1, bool $refresh = true)
    {
        return [];
    }

    /**
     * @param string $resource
     *
     * @return array
     */
    public function getItem(string $resource)
    {
        return [];
    }

}