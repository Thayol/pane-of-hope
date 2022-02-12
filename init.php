<?php
session_start();

require_once __DIR__ . "/settings-loader.php";

spl_autoload_register(function ($class_name) {
    foreach ([
        _WEBROOT_ . "/app/lib/{$class_name}.php",
        _WEBROOT_ . "/app/models/{$class_name}.php",
    ] as $file)
    {
        if (file_exists($file))
        {
            require $file;
            return null;
        }
    }
    
    throw new Exception("Couldn't load class: {$class_name}");
});

require_once _WEBROOT_ . "/app/lib/Session.php"; // TODO: refactor session to a class
