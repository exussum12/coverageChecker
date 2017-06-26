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

$diff = new  DiffFileLoader(adjustForStdIn($args->getArg(1)));
$phpmd = new PhpMdLoader(adjustForStdIn($args->getArg(2)));
if ($args->getArg("strict")) {
    $phpmd = new PhpMdLoaderStrict(adjustForStdIn($args->getArg(2)));
}

$coverageCheck = new CoverageCheck($diff, $phpmd, $matcher);

$lines = $coverageCheck->getCoveredLines();

handleOutput($lines, $minimumPercentCovered);
