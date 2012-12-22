<?php
// autoloader for php unit tests
spl_autoload_register(function($class) {
    $filename = __DIR__ . '/' . str_replace('\\', '/', str_replace('hergot\\databroker', 'src', $class)) . '.php'; 
    if (file_exists($filename)) {
        require_once $filename;
    }
});