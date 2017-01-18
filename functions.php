<?php
namespace exussum12\CoverageChecker;

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

function checkCallIsCorrect(ArgParser $args)
{
    if (false === $args->getArg(1) || false === $args->getArg(2)) {
        error_log(
            "Missing arguments, please call with diff and check file"
        );
        exit(1);
    }
}

function adjustForStdIn($argument)
{
    if ($argument == "-") {
        return "php://stdin";
    }

    return $argument;
}

function getMinPercent($percent)
{
    $minimumPercentCovered = 100;

    if (is_numeric($percent)) {
        $minimumPercentCovered = min(
            $minimumPercentCovered,
            max(0, $percent)
        );
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
