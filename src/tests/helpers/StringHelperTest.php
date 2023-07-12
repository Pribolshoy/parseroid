<?php

namespace pribolshoy\parseroid\helpers;

use PHPUnit\Framework\TestCase;

class StringHelperTest extends TestCase
{
    public function test_camelize()
    {
        $this->assertEquals('SomeText', StringHelper::camelize('some text'));
        $this->assertEquals('SomeText', StringHelper::camelize('some_text'));
        $this->assertEquals('SomeText', StringHelper::camelize('some_tEXT'));
    }
}