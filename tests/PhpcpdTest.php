<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;
use exussum12\CoverageChecker\Phpcpd;

class PhpcpdTest extends TestCase
{
    /** @var  Phpcpd */
    protected $cpd;
    protected function setUp()
    {
        parent::setUp();
        $this->cpd = new Phpcpd(__DIR__ . '/fixtures/phpcpd.txt');
    }

    public function testOutput()
    {

        $file1 = '/home/user/code/coverageChecker/vendor/symfony/console/Tests/Helper/SymfonyQuestionHelperTest.php';
        $file2 = '/home/user/code/coverageChecker/vendor/symfony/console/Tests/Helper/QuestionHelperTest.php';
        $expected = [
            $file1 => [
                45 => ["Duplicate of $file2:58-60"],
                46 => ["Duplicate of $file2:58-60"],
                47 => ["Duplicate of $file2:58-60"],
            ],
            $file2 => [
                58 => ["Duplicate of $file1:45-47"],
                59 => ["Duplicate of $file1:45-47"],
                60 => ["Duplicate of $file1:45-47"],
            ],
        ];

        $this->assertEquals($expected, $this->cpd->getLines());
        $this->assertFalse($this->cpd->isValidLine($file1, 45));
        $this->assertTrue($this->cpd->isValidLine($file1, 49));
    }

    public function testNotFoundFile()
    {
        $this->assertTrue($this->cpd->handleNotFoundFile());
    }
}
