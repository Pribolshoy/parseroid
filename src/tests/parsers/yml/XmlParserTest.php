<?php

namespace pribolshoy\parseroid\parsers\html\page;

use PHPUnit\Framework\TestCase;
use pribolshoy\parseroid\parsers\yml\YmlParser;

class YmlParserClass extends YmlParser
{
    /**
     * @return array|mixed
     * @throws \pribolshoy\parseroid\exceptions\ParserException
     */
    public function run()
    {
        $result = [];

        $doc = $this->getConvertedDocument($this->getDocument());

        foreach ($doc['product'] as $item) {
            $result[] = $item;
        }

        return $result;
    }
}

class YmlParserTest extends TestCase
{

    protected function getExampleDoc()
    {
        RETURN $_SERVER['TEST_DIR'] . "/files/example.yml";
    }

    /**
     * @throws \pribolshoy\parseroid\exceptions\ParserException
     */
//    public function test_getConvertedDocument()
//    {
//        $parser = new YmlParserClass();
//
//        $result = $parser->getConvertedDocument(file_get_contents($this->getExampleDoc()));
//
//        $this->assertInstanceOf(\SimpleXMLElement::class, $result);
//    }

    /**
     * @throws \Exception
     */
    public function test_getItems()
    {
        $parser = new YmlParserClass();

        // test of empty string
        $items = $parser->getItems('');
        $this->assertEmpty($items);
        $this->assertIsArray($items);


        // test real yml doc
        $testedExample = [];

        $doc = yaml_parse(file_get_contents($this->getExampleDoc()));

        foreach ($doc['product'] as $item) {
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
        $parser = new YmlParserClass();

        // test of empty string
        $item = $parser->getItem('');
        $this->assertEmpty($item);
        $this->assertIsArray($item);

        // test real yml doc
        $testedExample = [];

        $doc = yaml_parse(file_get_contents($this->getExampleDoc()));

        foreach ($doc['product'] as $item) {
            $testedExample[] = (array)$item;
        }


        $item = $parser->getItem($this->getExampleDoc());
        $this->assertNotEmpty($item);
        $this->assertIsArray($item);

        $this->assertEquals($testedExample[0], $item);
    }
}