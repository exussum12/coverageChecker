<?php
namespace exussum12\CoverageChecker\Runners;

use exussum12\CoverageChecker;
use exussum12\CoverageChecker\Exceptions\ArgumentNotFound;
use exussum12\CoverageChecker\Outputs\Json;
use exussum12\CoverageChecker\Outputs\Phpcs;
use exussum12\CoverageChecker\Outputs\Text;

require_once __DIR__ . "/../functions.php";
global $argv;

CoverageChecker\addExceptionHandler();
CoverageChecker\findAutoLoader();
$args = new CoverageChecker\ArgParser($argv);
CoverageChecker\checkForVersion($args);
CoverageChecker\checkCallIsCorrect($args);

try {
    $minimumPercentCovered = CoverageChecker\getMinPercent($args->getArg('3'));
} catch (ArgumentNotFound $exception) {
    $minimumPercentCovered = 100;
}

$matcher = new CoverageChecker\FileMatchers\EndsWith();

$diff = new CoverageChecker\DiffFileLoader(
    CoverageChecker\adjustForStdIn($args->getArg('1'))
);

try {
    $autoload = $args->getArg('autoload');
    if (file_exists(($autoload))) {
        require_once $autoload;
    }
} catch (ArgumentNotFound $exception) {
    // do nothing, its not a required argument
}

$checkerArray = [
    'buddy' => 'Buddy',
    'checkstyle' => 'Checkstyle',
    'clover' => 'Clover',
    'codeclimate' => 'CodeClimate',
    'humbug' => 'Humbug',
    'infecton' => 'Infection',
    'jacoco' => 'Jacoco',
    'phan' => 'PhanText',
    'phanJson' => 'PhanJson',
    'phpcpd' => 'Phpcpd',
    'phpcs' => 'PhpCs',
    'phpcsStrict' => 'PhpCsStrict',
    'phpmd' => 'PhpMd',
    'phpmdStrict' => 'PhpMdStrict',
    'phpmnd' => 'PhpMnd',
    'phpmndXml' => 'PhpMndXml',
    'phpstan' => 'PhpStan',
    'phpunit' => 'PhpUnit',
    'pylint' => 'Pylint',
    'psalm' => 'Psalm',
];

$fileCheck = CoverageChecker\getFileChecker(
    $args,
    $checkerArray,
    CoverageChecker\adjustForStdIn($args->getArg('2'))
);

$outputArray = [
    'text' => Text::class,
    'json' => Json::class,
    'phpcs' => Phpcs::class,
];
try {
    $report = $args->getArg('report');
} catch (ArgumentNotFound $exception) {
    $report = 'text';
}

$report = new $outputArray[$report];

$coverageCheck = new CoverageChecker\CoverageCheck($diff, $fileCheck, $matcher);

$lines = $coverageCheck->getCoveredLines();

CoverageChecker\handleOutput($lines, $minimumPercentCovered, $report);
