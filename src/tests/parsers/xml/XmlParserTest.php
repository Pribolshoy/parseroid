<?php

namespace pribolshoy\parseroid\parsers\html\page;

use PHPUnit\Framework\TestCase;
use pribolshoy\parseroid\parsers\xml\XmlParser;

class XmlParserClass extends XmlParser
{
    /**
     * @return array|mixed
     * @throws \pribolshoy\parseroid\exceptions\ParserException
     */
    public function run()
    {
        $result = [];

        $doc = $this->getConvertedDocument($this->getDocument());

        foreach ($doc->item as $item) {
            $result[] = (array)$item;
        }

        return $result;
    }
}

class XmlParserTest extends TestCase
{

    protected function getExampleDoc()
    {
        RETURN $_SERVER['TEST_DIR'] . "/files/example.xml";
    }

    /**
     * @throws \pribolshoy\parseroid\exceptions\ParserException
     */
    public function test_getConvertedDocument()
    {
        $parser = new XmlParserClass();

        $result = $parser->getConvertedDocument(file_get_contents($this->getExampleDoc()));

        $this->assertInstanceOf(\SimpleXMLElement::class, $result);
    }

    /**
     * @throws \Exception
     */
    public function test_getItems()
    {
        $parser = new XmlParserClass();

        // test of empty string
        $items = $parser->getItems('');
        $this->assertEmpty($items);
        $this->assertIsArray($items);


        // test real xml doc
        $testedExample = [];

        $doc = new \SimpleXMLElement(file_get_contents($this->getExampleDoc()));

        foreach ($doc->item as $item) {
            $testedExample[] = (array)$item;
        }


        $items = $parser->getItems($this->getExampleDoc());
        $this->assertNotEmpty($items);
        $this->assertIsArray($items);

        $this->assertEquals($testedExample, $items);
    }

    /**
     * @throws \Exception
     */
    public function test_getItem()
    {
        $parser = new XmlParserClass();

        // test of empty string
        $item = $parser->getItem('');
        $this->assertEmpty($item);
        $this->assertIsArray($item);

        // test real xml doc
        $testedExample = [];

        $doc = new \SimpleXMLElement(file_get_contents($this->getExampleDoc()));

        foreach ($doc->item as $item) {
            $testedExample[] = (array)$item;
        }


        $item = $parser->getItem($this->getExampleDoc());
        $this->assertNotEmpty($item);
        $this->assertIsArray($item);

        $this->assertEquals($testedExample[0], $item);
    }
}