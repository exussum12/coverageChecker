<?php
namespace exussum12\CoverageChecker\tests\Loaders;

use PHPUnit\Framework\TestCase;
use exussum12\CoverageChecker\Loaders\Phpcpd;

class PhpcpdTest extends TestCase
{
    /** @var  Phpcpd */
    protected $cpd;
    protected function setUp()
    {
        parent::setUp();
        $this->cpd = new Phpcpd(__DIR__ . '/../fixtures/phpcpd.txt');

        $this->cpd->parseLines();
    }

    public function testOutput()
    {

        $file1 = '/home/user/code/coverageChecker/vendor/symfony/console/Tests/Helper/SymfonyQuestionHelperTest.php';
        $file2 = '/home/user/code/coverageChecker/vendor/symfony/console/Tests/Helper/QuestionHelperTest.php';

        $this->assertEquals(
            ["Duplicate of $file2:58-60"],
            $this->cpd->getErrorsOnLine($file1, 45)
        );
        $this->assertEquals(
            [],
            $this->cpd->getErrorsOnLine($file1, 49)
        );
    }

    public function testNotFoundFile()
    {
        $this->assertTrue($this->cpd->handleNotFoundFile());
    }
}
