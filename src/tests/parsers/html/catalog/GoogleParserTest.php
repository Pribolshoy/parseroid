<?php

namespace pribolshoy\parseroid\parsers\html\catalog;

use PHPUnit\Framework\TestCase;

class GoogleParserTest extends TestCase
{
    protected function getExampleDoc()
    {
        RETURN $_SERVER['TEST_DIR'] . "/files/google_page.html";
    }

    /**
     * @throws \pribolshoy\parseroid\exceptions\ParserException
     * @throws \Exception
     */
    public function test_getItem()
    {
        $parser = new GoogleParser();

        // test of empty string
        $items = $parser->getItem('');
        $this->assertEmpty($items);
        $this->assertIsArray($items);


        // test of html in string
//        $item = $parser->getItem($this->getExampleDoc());
//EA($item);
//        $this->assertNotEmpty($item);
//        $this->assertArrayHasKey('input_text', $item);
//        $this->assertEquals('Hello world', $item['input_text']);

//        // test of html in file
//        $item = $parser->getItem($_SERVER['SCRIPT_NAME'] . "/files/google_page.html");
//
//        $this->assertNotEmpty($item);
//        $this->assertArrayHasKey('input_text', $item);
//        $this->assertEquals('Hello world', $item['input_text']);
    }

}