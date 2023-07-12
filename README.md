# Parseroid

Package for parsing different types of resources (html pages, xml etc.).

This package is not ready solution for all resource types. 

It just giving you necessary abstract functional for constructing you own needs. But in a way that makes it easy for you to do.

__Simple example:__
```
<?php
require_once "vendor/autoload.php";

$handler = (new \pribolshoy\parseroid\factories\HandlerFactory())
    ->create('html_page_handler', [
        'parser_name' => 'google_parser',
        'resource' => 'https://www.google.com/search?q=php+it%27s+amazing',
    ]);
$result = $handler->getItem();
print_r($result);

//<pre>Array
//(
//    [0] => Array
//    (
//        [input_text] => php it's amazing
//    )
//
//)
//</pre>
?>
```
