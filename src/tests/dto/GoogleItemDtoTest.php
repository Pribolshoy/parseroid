<?php

namespace pribolshoy\parseroid\dto;

use PHPUnit\Framework\TestCase;

class GoogleItemDtoTest extends TestCase
{
    public function test_get()
    {
        $initParams = [
            'title'        => 'title',
            'description'  => 'description',
            'url'          => 'url',
            'icon'         => 'icon',
        ];

        $obj = new GoogleItemDto($initParams['title'], $initParams['description'], $initParams['url'], $initParams['icon']);
        $this->assertIsArray($result = $obj->get());

        $this->assertEquals($initParams, $result);
    }
}