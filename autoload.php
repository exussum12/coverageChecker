<?php

spl_autoload_register(function($className) {
    $classPrefix = 'exussum12\CoverageChecker';
    if (strpos($className, $classPrefix) === 0) {
        $classPrefix = str_replace("\\", "\\\\", $classPrefix);
        $unprefixed = preg_replace("/^$classPrefix\\\/", '', $className, 1);
        $filename =
            __DIR__ . DIRECTORY_SEPARATOR .
            'src' . DIRECTORY_SEPARATOR .
            str_replace('\\', DIRECTORY_SEPARATOR, $unprefixed) .
            '.php';

        if (file_exists($filename)) {
            require_once $filename;
        }
    }
});
