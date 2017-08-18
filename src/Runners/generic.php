<?php
namespace exussum12\CoverageChecker\Runners;

use exussum12\CoverageChecker;

require_once __DIR__ . "/../functions.php";
global $argv;

CoverageChecker\addExceptionHandler();
CoverageChecker\findAutoLoader();
$args = new CoverageChecker\ArgParser($argv);
CoverageChecker\checkCallIsCorrect($args);
$minimumPercentCovered = CoverageChecker\getMinPercent($args->getArg(3));

$matcher = new CoverageChecker\FileMatchers\EndsWith();

$diff = new CoverageChecker\DiffFileLoader(
    CoverageChecker\adjustForStdIn($args->getArg(1))
);

$checkerArray = [
    'jacoco' => 'JacocoReport',
    'phpcpd' => 'Phpcpd',
    'phpcs' => 'PhpCsLoader',
    'phpcsStrict' => 'PhpCsLoaderStrict',
    'phpmd' => 'PhpMdLoader',
    'phpmdStrict' => 'PhpMdLoaderStrict',
    'phpmnd' => 'PhpMndLoader',
    'phpstan' => 'PhpStanLoader',
    'phpunit' => 'XMLReport',
];

$fileCheck = CoverageChecker\getFileChecker(
    $args,
    $checkerArray,
    CoverageChecker\adjustForStdIn($args->getArg(2))
);

$coverageCheck = new CoverageChecker\CoverageCheck($diff, $fileCheck, $matcher);

$lines = $coverageCheck->getCoveredLines();

CoverageChecker\handleOutput($lines, $minimumPercentCovered);
