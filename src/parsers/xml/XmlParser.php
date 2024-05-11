<?php

namespace pribolshoy\parseroid\parsers\xml;

use pribolshoy\parseroid\exceptions\ResourceNotExistsException;
use pribolshoy\parseroid\parsers\BaseParser;

/**
 * Class XmlParser
 *
 * Base abstract class with interface for parsing xml resources.
 *
 * @package pribolshoy\parseroid
 */
abstract class XmlParser extends BaseParser
{
    /**
     * Parse file resource and return results.
     *
     * @param string $resource
     * @param int $page_offset
     * @param bool $refresh
     *
     * @return mixed
     * @throws ResourceNotExistsException
     */
    public function getItems(string $resource, int $page_offset = 1, bool $refresh = true)
    {
        if (!file_exists($resource)) {
            throw new ResourceNotExistsException();
        }

        if (!$file_data = file_get_contents($resource)) return [];

        $this->document = new \SimpleXMLElement($file_data);

        return $this->run();
    }

}