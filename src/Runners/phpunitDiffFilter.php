<?php
namespace exussum12\CoverageChecker\Runners;

global $argv;

require_once __DIR__ . "/../functions.php";
addExceptionHandler();
findAutoLoader();
$args = new ArgParser($argv);
checkCallIsCorrect($args);
$minimumPercentCovered = getMinPercent($args->getArg(3));

$matcher = new FileMatchers\EndsWith();
$diff = new  DiffFileLoader($args->getArg(1));
$phpunit = new XMLReport($args->getArg(2));
$coverageCheck = new CoverageCheck($diff, $phpunit, $matcher);

$lines = $coverageCheck->getCoveredLines();

handleOutput($lines, $minimumPercentCovered);
