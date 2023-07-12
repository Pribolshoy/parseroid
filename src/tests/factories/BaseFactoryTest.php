<?php

namespace pribolshoy\parseroid\test\factories;

use PHPUnit\Framework\TestCase;
use pribolshoy\parseroid\factories\BaseFactory;

class BaseFactoryTest extends TestCase
{
    public function test_create()
    {
        $obj = new BaseFactory();

        try {
            $obj->create('not_existing_class');
            $this->fail('It must be error here on line: ' . __LINE__);
        } catch (\Exception $e) {
            $this->assertEquals('There is not set property INSTANCES_NAMESPACE in ' . get_class($obj) . ' factory', $e->getMessage());
        }

        $this->assertIsObject($obj->setInstancesNamespace('some\\namespace\\'));

        try {
            $obj->create('not_existing_class');
            $this->fail('It must be error here on line: ' . __LINE__);
        } catch (\Exception $e) {
            $this->assertEquals('Class some\namespace\NotExistingClass not existing', $e->getMessage());
        }

        $this->assertIsObject($obj->setInstancesNamespace('pribolshoy\\parseroid\\factories\\'));
        $createdObj = $obj->create('base_factory');
        $this->assertInstanceOf('pribolshoy\\parseroid\\factories\\BaseFactory', $createdObj);
    }

    public function test_getClassName()
    {
        $obj = new BaseFactory();

        try {
            $obj->getClassName('not_existing_class');
            $this->fail('It must be error here on line: ' . __LINE__);
        } catch (\Exception $e) {
            $this->assertEquals('There is not set property INSTANCES_NAMESPACE in ' . get_class($obj) . ' factory', $e->getMessage());
        }

        $this->assertIsObject($obj->setInstancesNamespace('some\\namespace\\'));

        $this->assertEquals(
            'some\\namespace\\NotExistingClass',
            $obj->getClassName('not_existing_class')
        );

    }

    public function test_getInstancesNamespace()
    {
        $obj = new BaseFactory();

        $this->assertEmpty($obj->getInstancesNamespace());
    }

    public function test_setInstancesNamespace()
    {
        $obj = new BaseFactory();

        $this->assertEmpty($obj->getInstancesNamespace());
        $this->assertIsObject($obj->setInstancesNamespace('some\\namespace\\'));
        $this->assertEquals('some\\namespace\\', $obj->getInstancesNamespace());

        $this->assertIsObject($obj->setInstancesNamespace('some\\namespace'));
        $this->assertEquals('some\\namespace\\', $obj->getInstancesNamespace());
    }
}