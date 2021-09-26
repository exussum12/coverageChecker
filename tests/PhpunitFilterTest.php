<?php
namespace exussum12\CoverageChecker\tests;

use exussum12\CoverageChecker\PhpunitFilter;
use PHPUnit\Framework\TestCase;
use exussum12\CoverageChecker\DiffFileLoader;
use exussum12\CoverageChecker\FileMatchers;
use Exception;

/**
 * @requires extension xdebug
 */
class PhpunitFilterTest extends TestCase
{
    protected $coverage;
    protected $diff;
    protected $matcher;

    /**
     * @before
     */
    public function setUpTest()
    {
        if(PHP_VERSION > 7.2) {
            $this->markTestSkipped("Not currently supported");
        }
        $this->coverage = __DIR__ . '/fixtures/php-coverage.php';
        $this->diff = new DiffFileLoader(__DIR__ . '/fixtures/change.txt');
        $this->matcher = new FileMatchers\EndsWith();
    }
    public function testBadFilesPassedIn()
    {
        $this->expectException(Exception::class);
        $badFile = 'doesNotExist.blah';
        new PhpunitFilter(
            $this->diff,
            $this->matcher,
            $badFile
        );
    }

    public function testOutputOfGetTests()
    {
        $this->diff = new DiffFileLoader(__DIR__ . '/fixtures/coverageMatchFiles.txt');
        $coverage = new PhpunitFilter(
            $this->diff,
            $this->matcher,
            $this->coverage
        );

        $expected = [
            'exussum12\CoverageChecker\tests\ArgParserTest' => [
                'testNumericArgs',
            ],
            'exussum12\CoverageChecker\tests\GenericDiffFilterTest' => [
                'testValid',
                'testMissingHandler',
            ],
            'exussum12\CoverageChecker\tests\PhpcsDiffFilterTest' => [
                'testValid',
            ],
        ];
        $this->assertEquals($expected, $coverage->getTestsForRunning());
    }

    public function testUnknownDiffFiles()
    {
        $this->diff = new DiffFileLoader(__DIR__ . '/fixtures/coverageMatchUnknownFiles.txt');
        $coverage = new PhpunitFilter(
            $this->diff,
            $this->matcher,
            $this->coverage
        );
        $expected = [
            'tests\Unknown' => [''],
        ];
        $this->assertEquals($expected, $coverage->getTestsForRunning());
    }
}
