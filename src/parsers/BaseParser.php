<?php

namespace pribolshoy\parseroid\parsers;

use pribolshoy\parseroid\exceptions\ParserException;
use pribolshoy\parseroid\helpers\converters\ConverterInterface;

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

    protected ?ConverterInterface $documentConverter = null;

    protected ?string $documentConverterClass = null;

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
     * Default do nothing
     *
     * @param string $document
     *
     * @return mixed
     * @throws ParserException
     */
    public function getConvertedDocument(string $document)
    {
        if ($document
            && !is_null($converter = $this->getDocumentConverter())
        ) {
            $document = $converter->convert($document);
        }
        return $document;
    }

    /**
     * @return ConverterInterface|null
     * @throws ParserException
     */
    protected function getDocumentConverter() :?ConverterInterface
    {
        if (is_null($this->documentConverter)
            && $documentConverterClass = $this->documentConverterClass
        ) {
            if (!class_exists($documentConverterClass)) {
                throw new ParserException(
                    "Класс конвертера "
                    . " $documentConverterClass не существует"
                );
            }

            $this->documentConverter = new $documentConverterClass();
        }

        return $this->documentConverter;
    }

    /**
     * Parse resource page and return collected data
     *
     * @param string $resource
     *
     * @return array
     * @throws \Exception
     */
    public function getItem(string $resource)
    {
        return $this->getItems($resource)[0] ?? [];
    }

    /**
     * Parse and returns elements by url resource
     *
     * @param string $resource    url resource for parsing
     * @param int    $page_offset num of catalog page where start to parse
     * @param bool   $refresh     flag to set parsed_page_count property to 0
     *
     * @return mixed
     * @throws \Exception
     */
    public function getItems(string $resource, int $page_offset = 1, bool $refresh = true)
    {
        if ($this->initDocument($resource)) {
            $items = $this->run();
        }

        return $items ?? [];
    }

}