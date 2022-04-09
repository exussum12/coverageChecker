<?php
namespace exussum12\CoverageChecker;

use Exception;
use exussum12\CoverageChecker\DiffFileLoaderOldVersion;
use exussum12\CoverageChecker\FileMatchers;
use exussum12\CoverageChecker\PhpunitFilter;
use PHPUnit\Framework\AssertionFailedError as AssertionFailedError;
use PHPUnit\Framework\Test as Test;
use PHPUnit\Framework\TestCase as TestCase;
use PHPUnit\Framework\TestListener as TestListener;
use PHPUnit\Framework\TestSuite as TestSuite;
use PHPUnit\Framework\Warning;
use Throwable;

/**
 * Coverage ignored due to not being able to run this
 * @codeCoverageIgnore
 * PHPMD suppressed due to having to implement TestListener
 * @SuppressWarnings(PHPMD)
 */
class DiffFilter implements TestListener
{
    protected $modifiedTests = null;
    protected $modifiedSuites = null;
    public function __construct(string $coverageFile, string $diff, int $fuzziness = 0)
    {
        if (env('DIFF_FILTER_TEST') != 1) {
            return;
        }

        try {
            $diff = new DiffFileLoaderOldVersion($diff);
            $matcher = new FileMatchers\EndsWith();
            $coverage = new PhpunitFilter($diff, $matcher, $coverageFile);
            $this->modifiedTests = $coverage->getTestsForRunning($fuzziness);
            $this->modifiedSuites = array_keys($this->modifiedTests);
            unset($coverage);
        } catch (Exception $exception) {
            //Something has gone wrong, Don't filter
            echo "Missing required diff / php coverage, Running all tests\n";
        }
    }
    public function startTestSuite(TestSuite $suite): void
    {
        if (!is_array($this->modifiedTests)) {
            return;
        }

        $suiteName = $suite->getName();
        $runTests = [];
        if (empty($suiteName)) {
            return;
        }

        $tests = $suite->tests();
                         
        foreach ($tests as $test) {
            $skipTest =
                $test instanceof TestCase &&
                !$this->hasTestChanged(
                    $test
                );

            if ($skipTest) {
                continue;
            }
            $runTests[] = $test;
        }

        $suite->setTests($runTests);
    }
    public function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    protected function shouldRunTest($modifiedTest, $currentTest, $class)
    {
        foreach ($modifiedTest as $test) {
            $testName = $currentTest->getName();
            $testMatches =
                strpos($class, get_class($currentTest)) !== false &&
                (
                    empty($test) ||
                    strpos($test, $testName) !== false
                )
            ;
            if ($testMatches) {
                return true;
            }
        }
        return false;
    }

    private function hasTestChanged(TestCase $test)
    {
        foreach ($this->modifiedTests as $class => $modifiedTest) {
            if ($this->shouldRunTest($modifiedTest, $test, $class)) {
                return true;
            }
        }

        return false;
    }

    public function addError(Test $test, Throwable $t, float $time): void
    {
        // TODO: Implement addError() method.
    }

    public function addWarning(Test $test, Warning $e, float $time): void
    {
        // TODO: Implement addWarning() method.
    }

    public function addFailure(Test $test, AssertionFailedError $e, float $time): void
    {
        // TODO: Implement addFailure() method.
    }

    public function addIncompleteTest(Test $test, Throwable $t, float $time): void
    {
        // TODO: Implement addIncompleteTest() method.
    }

    public function addRiskyTest(Test $test, Throwable $t, float $time): void
    {
        // TODO: Implement addRiskyTest() method.
    }

    public function addSkippedTest(Test $test, Throwable $t, float $time): void
    {
        // TODO: Implement addSkippedTest() method.
    }

    public function endTestSuite(TestSuite $suite): void
    {
        // TODO: Implement endTestSuite() method.
    }

    public function startTest(Test $test): void
    {
        // TODO: Implement startTest() method.
    }

    public function endTest(Test $test, float $time): void
    {
        // TODO: Implement endTest() method.
    }
}
