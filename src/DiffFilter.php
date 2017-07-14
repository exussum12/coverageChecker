<?php
namespace exussum12\CoverageChecker;

use Exception;
use PHPUnit_Framework_AssertionFailedError as AssertionFailedError;
use PHPUnit_Framework_Test as Test;
use PHPUnit_Framework_TestCase as TestCase;
use PHPUnit_Framework_TestListener as TestListener;
use PHPUnit_Framework_TestSuite as TestSuite;

class DiffFilter implements TestListener
{
    protected $modifiedTests = null;
    public function __construct($old, $diff)
    {
        try {
            $diff = new DiffFileLoader($diff);
            $matcher = new FileMatchers\EndsWith();
            $coverage = new PhpunitFilter($diff, $matcher, $old);
            $this->modifiedTests = $coverage->getTestsForRunning();
            unset($coverage);
        } catch (Exception $e) {
            //Something has gone wrong, Don't filter
            echo "Missing required diff / php coverage, Running all tests\n";
        }
    }

    public function addError(Test $test, Exception $e, $time)
    {
    }
    public function addFailure(Test $test, AssertionFailedError $e, $time)
    {
    }
    public function addRiskyTest(Test $test, Exception $e, $time)
    {
    }
    public function startTestSuite(TestSuite $suite)
    {
        if (!is_array($this->modifiedTests)) {
            return;
        }
        $tests = $suite->tests();
        $runTests = [];
        foreach ($tests as $test) {
            if ($test instanceof TestCase && !$this->hasTestChanged($test)) {
                continue;
            }
            $runTests[]= $test;
        }

        $suite->setTests($runTests);
    }
    public function startTest(Test $test)
    {
    }
    public function endTest(Test $test, $time)
    {
    }
    public function addIncompleteTest(Test $test, Exception $e, $time)
    {
    }
    public function addSkippedTest(Test $test, Exception $e, $time)
    {
    }
    public function endTestSuite(TestSuite $suite)
    {
    }
    public function onFatalError()
    {
    }
    public function onCancel()
    {
    }

    public function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    protected function shouldRunTest($modifiedTest, $currentTest)
    {
        $testName = explode("::", $currentTest)[0];

        return
            $this->startsWith($modifiedTest, $currentTest) ||
            strpos($currentTest, $modifiedTest . '::') > 0 ||
            strpos($modifiedTest, $testName)> 0
        ;
    }

    private function hasTestChanged(TestCase $test)
    {
        foreach ($this->modifiedTests as $modifiedTest) {
            $currentTest = get_class($test) . '::' . $test->getName();
            if (!$this->shouldRunTest($modifiedTest, $currentTest)) {
                return false;
            }
        }

        return true;
    }
}
