<?php
namespace exussum12\CoverageChecker;

use Exception;
use exussum12\CoverageChecker\Exceptions\ArgumentNotFound;
use exussum12\CoverageChecker\Outputs\Text;

function findAutoLoader()
{
    $locations = [
        // Vendor directory locally
        __DIR__ . '/../vendor/autoload.php',
        // Vendor directory when installed with composer
        __DIR__ . '/../../../vendor/autoload.php',
        __DIR__ . '/../../../../vendor/autoload.php',
        // Local install (without composer)
        __DIR__ . '/../autoload.php'
    ];

    $found = false;

    foreach ($locations as $file) {
        if (file_exists($file)) {
            require_once($file);
            $found = true;
            break;
        }
    }
    // @codeCoverageIgnoreStart
    if (!$found) {
        error_log(
            "Can't find the autoload file," .
            "please make sure 'composer install' has been run"
        );

        exit(1);
    // @codeCoverageIgnoreEnd
    }
}

function checkCallIsCorrect(ArgParser $args)
{
    try {
        $args->getArg('1');
        $args->getArg('2');
    } catch (ArgumentNotFound $exception) {
        throw new Exception(
            "Missing arguments, please call with diff and check file\n" .
            "e.g. vendor/bin/diffFilter --phpcs diff.txt phpcs.json",
            1
        );
    }
}

/**
 * @codeCoverageIgnore
 */
function adjustForStdIn(string $argument)
{
    if ($argument == "-") {
        return "php://stdin";
    }

    // @codeCoverageIgnoreStart
    if (strpos($argument, '/dev/fd') === 0) {
        return str_replace('/dev/fd', 'php://fd', $argument);
    }
    // @codeCoverageIgnoreEnd

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

function handleOutput(array $lines, float $minimumPercentCovered, Output $output)
{
    $coveredLines = calculateLines($lines['coveredLines']);
    $uncoveredLines = calculateLines($lines['uncoveredLines']);


    if ($coveredLines + $uncoveredLines == 0) {
        error_log('No lines found!');
        return;
    }

    $percentCovered = 100 * ($coveredLines / ($coveredLines + $uncoveredLines));

    $output->output(
        $lines['uncoveredLines'],
        $percentCovered,
        $minimumPercentCovered
    );

    if ($percentCovered >= $minimumPercentCovered) {
        return;
    }

    throw new Exception(
        'Failing due to coverage being lower than threshold',
        2
    );
}

function calculateLines(array $lines)
{
    return array_sum(array_map('count', $lines));
}

function addExceptionHandler()
{
    set_exception_handler(
        function ($exception) {
            // @codeCoverageIgnoreStart
            error_log($exception->getMessage());
            exit($exception->getCode());
            // @codeCoverageIgnoreEnd
        }
    );
}

function getFileChecker(
    ArgParser $args,
    array $argMapper,
    string $filename
): FileChecker {
    foreach ($argMapper as $arg => $class) {
        try {
            $args->getArg($arg);
            $class = __NAMESPACE__ . '\\Loaders\\' . $class;
            return new $class($filename);
        } catch (ArgumentNotFound $exception) {
            continue;
        }
    }
    printOptions($argMapper);
    throw new Exception("Can not find file handler");
}

function printOptions(array $arguments)
{
    $tabWidth = 8;
    $defaultWidth = 80;

    $width = (int) (`tput cols` ?: $defaultWidth);
    $width -= 2 * $tabWidth;
    foreach ($arguments as $argument => $class) {
        $class = __NAMESPACE__ . '\\Loaders\\' . $class;

        $argument = adjustArgument($argument, $tabWidth);

        error_log(sprintf(
            "%s\t%s",
            $argument,
            wordwrap(
                $class::getDescription(),
                $width,
                "\n\t\t",
                true
            )
        ));
    }
}

function adjustArgument($argument, $tabWidth)
{
    $argument = '--' . $argument;
    if (strlen($argument) < $tabWidth) {
        $argument .= "\t";
    }
    return $argument;
}

function checkForVersion(ArgParser $args)
{
    try {
        $args->getArg("v");
    } catch (ArgumentNotFound $e) {
        return;
    }

    throw new Exception('Version: 0.10.3-dev', 0);
}
