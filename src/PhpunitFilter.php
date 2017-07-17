<?php
namespace exussum12\coverageChecker;

use Exception;

class PhpunitFilter
{
    protected $diff;
    protected $matcher;
    protected $coverage;
    public function __construct(DiffFileLoader $diff, FileMatcher $matcher, $coveragePhp)
    {
        if (!is_readable(($coveragePhp))) {
            throw new Exception("Coverage File not found");
        }
        $this->coverage = include($coveragePhp);
        $this->diff = $diff;
        $this->matcher = $matcher;
    }

    public function getTestsForRunning()
    {
        $changes = $this->diff->getChangedLines();
        $testData = $this->coverage->getData();
        $fileNames = array_keys($testData);
        $runTests = [];
        foreach ($changes as $file => $lines) {
            try {
                if ($found = $this->matcher->match($file, $fileNames)) {
                    foreach ($lines as $line) {
                        if (isset($testData[$found][$line])) {
                            $runTests = array_unique(
                                array_merge(
                                    $runTests,
                                    $testData[$found][$line]
                                )
                            );
                        }
                    }
                }
            } catch (Exception $e) {
                if ($this->endsWith($file, ".php")) {
                    $runTests[] = $this->stripFileExtension($file);
                }
            }
        }
        return $this->groupTestsBySuite($runTests);
    }

    protected function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, -$length) === $needle);
    }

    protected function stripFileExtension($file)
    {
        $ext = ".php";
        return str_replace('/', '\\', substr($file, 0, -strlen($ext)));
    }

    protected function groupTestsBySuite($tests)
    {
        $groupedTests = [];
        foreach ($tests as $test) {
            $suite = $test;
            $testName = '';

            if (strpos($test, '::') > 0) {
                list ($suite, $testName) = explode('::', $test);
            }
            $groupedTests[$suite][] = $testName;
        }
        return $groupedTests;
    }
}
