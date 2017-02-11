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
$diff = new  DiffFileLoader($args->getArg(1));
$phpcs = new PhpCsLoader($args->getArg(2));
$coverageCheck = new CoverageCheck($diff, $phpcs, $matcher);

$lines = $coverageCheck->getCoveredLines();

handleOutput($lines, $minimumPercentCovered);
