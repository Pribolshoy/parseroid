<?php
// ensure we get report on all possible php errors
error_reporting(E_ERROR & E_WARNING);

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

$_SERVER['TEST_DIR']     = __DIR__;
$_SERVER['SCRIPT_NAME']     = __DIR__;
$_SERVER['SCRIPT_FILENAME'] = __FILE__;
$composerAutoload           = __DIR__ . '/../../vendor/autoload.php';
if (!is_file($composerAutoload))
{
    die("Composer autoloader not found!");
}
require_once($composerAutoload);
