<?php
// autoloader for php unit tests
spl_autoload_register(function($class) {
    $path = str_replace(
            array(
                'hergot\\databroker\\test',
                'hergot\\databroker'
            ), 
            array(
                'test', 
                'src'
            ), $class);
    $filename = __DIR__ . '/' 
            . str_replace('\\', '/', $path) 
            . '.php';
    if (file_exists($filename)) {
        require_once $filename;
    }
});