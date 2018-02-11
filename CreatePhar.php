<?php

$pharName = 'diffFilter.phar';
$pharFile = getcwd() . '/diffFilter.phar';

if (file_exists($pharFile)) {
    unlink($pharFile);
}

$phar = new Phar($pharFile, 0, $pharName);

$phar->addFile('autoload.php');
$phar->addFile('bin/diffFilter');

$code = realpath(__DIR__ . '/src');
$codeLength = strlen($code);
$directory = new RecursiveDirectoryIterator(
    $code,
    RecursiveDirectoryIterator::FOLLOW_SYMLINKS
);
$iterator = new RecursiveIteratorIterator(
    $directory,
    0,
    RecursiveIteratorIterator::CATCH_GET_CHILD
);

foreach ($iterator as $file) {
    $fullPath = $file->getPathname();
    $path = 'src' . substr($fullPath, $codeLength);

    $phar->addFile($path);
}

$phar->setStub(
    "#!/usr/bin/env php
    <?php
    require 'src/Runners/generic.php';
    __HALT_COMPILER();"
);
