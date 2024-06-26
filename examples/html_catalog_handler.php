<?php
if (!function_exists('EA()')) {
    function EA($arr, $die = true) {
        if ($arr) {
            print '<pre>';
            print_r($arr);
            print '</pre>';
        }

        if ($die) {
            die();
        }
    }
}
require_once "vendor/autoload.php";

// here is creating of parser handler through BaseFactory

try {
    /** @var \pribolshoy\parseroid\handlers\ResourceHandler $handler */
    $handlerFactory = new \pribolshoy\parseroid\factories\BaseFactory();
    $handlerFactory->setInstancesNamespace("\\pribolshoy\\parseroid\\handlers\\");

    $handler = $handlerFactory->create('html_catalog_handler', [
        'resource'      => 'https://www.google.com/search?q=php+it%27s+amazing',
        'parser_class'      => \pribolshoy\parseroid\parsers\html\catalog\GoogleParser::class,
    ]);

    $result = $handler->getItems();

    print_r($result);
} catch (\Exception $e) {
    print "Something went wrong: " . $e->getMessage();
}


//// here is creating of parser handler through HandlerFactory
//
//try {
//    $handlerFactory = new \pribolshoy\parseroid\factories\HandlerFactory();
//
//    /** @var \pribolshoy\parseroid\handlers\ResourceHandler $handler */
//    $handler = $handlerFactory->create('html_catalog_handler', [
//        'resource'      => 'https://www.google.com/search?q=php+it%27s+amazing',
//        'parser_class'      => \pribolshoy\parseroid\parsers\html\catalog\GoogleParser::class,
//    ]);
//
//    $result = $handler->getItems();
//
//    print_r($result);
//} catch (\Exception $e) {
//    print "Something went wrong: " . $e->getMessage();
//}
//
//
//// here is creating of parser handler without any factory
//
//try {
//    $handler = new \pribolshoy\parseroid\handlers\ResourceHandler([
//        'resource'      => 'https://www.google.com/search?q=php+it%27s+amazing',
//        'parser_class'      => \pribolshoy\parseroid\parsers\html\catalog\GoogleParser::class,
//    ]);
//
//    $result = $handler->getItems();
//
//    print_r($result);
//} catch (\Exception $e) {
//    print "Something went wrong: " . $e->getMessage();
//}