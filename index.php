<?php


if(version_compare(phpversion(), '5.3.0', '<') == true) {
    die('PHP >= 5.3.0 Only (Версия пхп должна быть >= 5.3.0)');
}

define('BASE_PATH', dirname(realpath(__FILE__)) . '/');


spl_autoload_register(function($class) {

    $classParts = explode('\\', $class);

    $classParts[0] = BASE_PATH;

    $classParts = array_map('strtolower', $classParts);

    $path = implode(DIRECTORY_SEPARATOR, $classParts) . '.php';

    if (is_readable($path))
        require_once $path;

});


try
{

    \App\Core\Http\Route::route(new \App\Core\Http\Request($_SERVER['REQUEST_URI']));


} catch (Exception $e) {
    echo 'Error :' . $e->getMessage() . '<br />';
    echo 'Code :' . $e->getCode() . '<br />';
    echo 'File :' . $e->getFile() . '<br />';
    echo 'Line :' . $e->getLine() . '<br />';
}

