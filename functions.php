<?php
namespace exussum12\CoverageChecker;

function setUp()
{
    findAutoLoader();
    checkCallIsCorrect();
    adjustForStdIn();
}

function findAutoLoader()
{
    $locations = [
        __DIR__ . '/vendor/autoload.php',
        __DIR__ . '/autoload.php'
    ];

    $found = false;

    foreach ($locations as $file) {
        if (file_exists($file)) {
            require_once($file);
            $found = true;
            break;
        }
    }

    if (!$found) {
        error_log(
            "Can't find the autoload file," .
            "please make sure 'composer install' has been run"
        );

        exit(1);
    }
}

function checkCallIsCorrect()
{
    global $argv;
    if (!isset($argv[1], $argv[2])) {
        error_log(
            "Missing arguments, please call with diff and check file"
        );
        exit(1);
    }
}

function adjustForStdIn()
{
    global $argv;
    foreach ([1, 2] as $arg) {
        if ($argv[$arg] == "-") {
            $argv[$arg] = "php://stdin";
        }
    }
}

function getMinPercent()
{
    global $argv;
    $minimumPercentCovered = 100;
    if (isset($argv[3])) {
        $minimumPercentCovered = min($minimumPercentCovered, max(0, $argv[3]));
        return $minimumPercentCovered;
    }
    return $minimumPercentCovered;
}

function handleOutput($lines, $minimumPercentCovered)
{
    $coveredLines = count($lines['coveredLines'], COUNT_RECURSIVE);
    $uncoveredLines = count($lines['uncoveredLines'], COUNT_RECURSIVE);

    if ($coveredLines + $uncoveredLines == 0) {
        exit(0);
    }

    $percentCovered = 100 * ($coveredLines / ($coveredLines + $uncoveredLines));
    
    echo "$percentCovered% Covered, Missed lines " . PHP_EOL;
    print_r($lines['uncoveredLines']);
    
    if ($percentCovered >= $minimumPercentCovered) {
        exit(0);
    }

    exit(2);
}
