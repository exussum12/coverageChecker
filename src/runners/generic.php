<?php
namespace exussum12\CoverageChecker;

require_once __DIR__ . "/../functions.php";
global $argv;

addExceptionHandler();
findAutoLoader();
$args = new ArgParser($argv);
checkCallIsCorrect($args);
$minimumPercentCovered = getMinPercent($args->getArg(3));

$matcher = new FileMatchers\EndsWith();

$diff = new DiffFileLoader(adjustForStdIn($args->getArg(1)));

$checkerArray = [
    'phpcs' => 'PhpCsLoader',
    'phpmd' => 'PhpMdLoader',
    'phpmdStrict' => 'PhpMdLoaderStrict',
    'phpmnd' => 'PhpMndLoader',
    'phpunit' => 'XMLReport',
];

$fileCheck = getFileChecker(
    $args,
    $checkerArray,
    adjustForStdIn($args->getArg(2))
);

$coverageCheck = new CoverageCheck($diff, $fileCheck, $matcher);

$lines = $coverageCheck->getCoveredLines();

handleOutput($lines, $minimumPercentCovered);
