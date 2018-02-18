<?php
namespace exussum12\CoverageChecker\Runners;

use exussum12\CoverageChecker;
use exussum12\CoverageChecker\Outputs\Json;
use exussum12\CoverageChecker\Outputs\Phpcs;
use exussum12\CoverageChecker\Outputs\Text;

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

if ($autoload = $args->getArg('autoload')) {
    if (file_exists(($autoload))) {
        require_once $autoload;
    }
}

$checkerArray = [
    'checkstyle' => 'CheckstyleLoader',
    'clover' => 'CloverLoader',
    'codeclimate' => 'CodeClimateLoader',
    'humbug' => 'HumbugLoader',
    'infecton' => 'InfectionLoader',
    'jacoco' => 'JacocoReport',
    'phan' => 'PhanTextLoader',
    'phanJson' => 'PhanJsonLoader',
    'phpcpd' => 'Phpcpd',
    'phpcs' => 'PhpCsLoader',
    'phpcsStrict' => 'PhpCsLoaderStrict',
    'phpmd' => 'PhpMdLoader',
    'phpmdStrict' => 'PhpMdLoaderStrict',
    'phpmnd' => 'PhpMndLoader',
    'phpstan' => 'PhpStanLoader',
    'phpunit' => 'PhpUnitLoader',
    'pylint' => 'PylintLoader',
];

$fileCheck = CoverageChecker\getFileChecker(
    $args,
    $checkerArray,
    CoverageChecker\adjustForStdIn($args->getArg(2))
);

$outputArray = [
    'text' => Text::class,
    'json' => Json::class,
    'phpcs' => Phpcs::class,
];
$report = 'text';
$requestedReport = $args->getArg('report');

if (isset($outputArray[$requestedReport])) {
    $report = $requestedReport;
}

$report = new $outputArray[$report];

$coverageCheck = new CoverageChecker\CoverageCheck($diff, $fileCheck, $matcher);

$lines = $coverageCheck->getCoveredLines();

CoverageChecker\handleOutput($lines, $minimumPercentCovered, $report);
