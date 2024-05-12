<?php

require_once "vendor/autoload.php";

// here is creating of parser handler through BaseFactory

try {
    /** @var \pribolshoy\parseroid\handlers\ResourceHandler $handler */
    $handlerFactory = new \pribolshoy\parseroid\factories\BaseFactory();
    $handlerFactory->setInstancesNamespace("\\pribolshoy\\parseroid\\handlers\\");

    $handler = $handlerFactory->create('resource_handler', [
        'resource'      => 'https://www.google.com/search?q=php+it%27s+amazing',
        'parser_class'      => \pribolshoy\parseroid\parsers\html\page\GoogleParser::class,
    ]);

    $result = $handler->getItem();

    print_r($result);
} catch (\Exception $e) {
    print "Something went wrong: " . $e->getMessage();
}


// here is creating of parser handler through HandlerFactory

try {
    $handlerFactory = new \pribolshoy\parseroid\factories\HandlerFactory();

    /** @var \pribolshoy\parseroid\handlers\ResourceHandler $handler */
    $handler = $handlerFactory->create('resource_handler', [
        'resource'      => 'https://www.google.com/search?q=php+it%27s+amazing',
        'parser_class'      => \pribolshoy\parseroid\parsers\html\page\GoogleParser::class,
    ]);

    $result = $handler->getItem();

    print_r($result);
} catch (\Exception $e) {
    print "Something went wrong: " . $e->getMessage();
}


// here is creating of parser handler without any factory

try {
    $handler = new \pribolshoy\parseroid\handlers\ResourceHandler([
        'resource'      => 'https://www.google.com/search?q=php+it%27s+amazing',
        'parser_class'      => \pribolshoy\parseroid\parsers\html\page\GoogleParser::class,
    ]);

    $result = $handler->getItem();

    print_r($result);
} catch (\Exception $e) {
    print "Something went wrong: " . $e->getMessage();
}