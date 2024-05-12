<?php

namespace pribolshoy\parseroid\parsers\html\page;

use PHPUnit\Framework\TestCase;
use pribolshoy\parseroid\parsers\BaseParser;

class BaseParserClass extends BaseParser
{
    public function run() {
        // TODO: Implement run() method.
    }
}

class BaseParserTest extends TestCase
{

    /**
     * @throws \pribolshoy\parseroid\exceptions\ParserException
     */
    public function test_initDocument()
    {
        $parser = new BaseParserClass();

        $this->assertFalse($parser->initDocument(''));

        $this->assertTrue($parser->initDocument('some_value'));
        $this->assertEquals('some_value', $parser->getDocument());
    }

    public function test_getDocument()
    {
        $parser = new BaseParserClass();

        $this->assertNull($parser->getDocument());

        $parser->setDocument('some_value');
        $this->assertEquals('some_value', $parser->getDocument());

        $parser->setDocument('');
        $this->assertEmpty($parser->getDocument());
    }

    public function test_setDocument()
    {
        $parser = new BaseParserClass();

        // test of empty string
        $this->assertIsObject($parser->setDocument(''));
        $this->assertInstanceOf(BaseParser::class, $parser->setDocument(''));

        $this->assertEmpty($parser->getDocument());

        $parser->setDocument('some_value');
        $this->assertEquals('some_value', $parser->getDocument());
    }

    public function test_getItems()
    {
        $parser = new BaseParserClass();

        // test of empty string
        $items = $parser->getItems('');
        $this->assertEmpty($items);
        $this->assertIsArray($items);
    }

    public function test_getItem()
    {
        $parser = new BaseParserClass();

        // test of empty string
        $item = $parser->getItem('');
        $this->assertEmpty($item);
        $this->assertIsArray($item);
    }
}