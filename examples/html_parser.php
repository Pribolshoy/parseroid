<?php

require_once "vendor/autoload.php";

// here is creating of concrete parser through BaseFactory

// 1 variant
try {
    $factory = new \pribolshoy\parseroid\factories\BaseFactory();
    $parser = $factory->setInstancesNamespace("\\pribolshoy\\parseroid\\parsers\\html\\page\\")
        ->create('google_parser');

    $result = $parser->getItem('https://www.google.com/search?q=php+it%27s+amazing');

    print_r($result);
} catch (\Exception $e) {
    print "Something went wrong: " . $e->getMessage();
}


// 2 variant
try {
    $factory = new \pribolshoy\parseroid\factories\BaseFactory();
    $parser = $factory
        ->create(\pribolshoy\parseroid\parsers\html\page\GoogleParser::class);

    $result = $parser->getItem('https://www.google.com/search?q=php+it%27s+amazing');

    print_r($result);
} catch (\Exception $e) {
    print "Something went wrong: " . $e->getMessage();
}

// here is creating of concrete parser without factory

try {
    $parser = new \pribolshoy\parseroid\parsers\html\page\GoogleParser;

    $result = $parser->getItem('https://www.google.com/search?q=php+it%27s+amazing');

    print_r($result);
} catch (\Exception $e) {
    print "Something went wrong: " . $e->getMessage();
}