<?php

chdir(__DIR__);

$pharName = 'diffFilter.phar';

cleanUp($pharName);

$pharFile = getcwd() . '/diffFilter.phar';

if (file_exists($pharFile)) {
    unlink($pharFile);
}

$phar = new Phar($pharFile, 0, $pharName);

$phar->addFile('autoload.php');
$phar->addFile('bin/diffFilter');


$dirs = [
    'src',
    'vendor',
];

foreach($dirs as $dir) {
    addDir($dir, $phar);
}

$phar->setStub(
    "#!/usr/bin/env php
    <?php
    Phar::mapPhar('$pharName');
    require 'phar://$pharName/src/Runners/generic.php';
    __HALT_COMPILER();"
);

function addDir($dir, $phar)
{
    $code = realpath(__DIR__ . "/$dir/");
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
        $path = $dir . substr($fullPath, $codeLength);

        if (strpos($path, '/test/') !== false) {
            continue;
        }

        if (is_file($path)) {
            $phar->addFromString($path, php_strip_whitespace($path));
        }
    }
}

function cleanUp($pharName)
{
    shell_exec("rm -rf vendor");
    shell_exec("rm $pharName");
    shell_exec("composer install --no-dev -o");
}
