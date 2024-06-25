<?php
namespace exussum12\CoverageChecker\tests;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use exussum12\CoverageChecker\DiffFileLoader;
use exussum12\CoverageChecker\DiffFileState;
use exussum12\CoverageChecker\DiffLineHandle\ContextLine;

class DiffFileLoadTest extends TestCase
{
    #[DataProvider('getResults')]
    public function testDiffResultsMatch($file, $expected)
    {
        $changed = $this->getChangedLines($file);

        $this->assertEquals($changed, $expected);
    }

    public function testNonExistantFile()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(1);
        $this->getChangedLines('ufhbubfusdf');
    }

    public static function getResults()
    {
        return [
            'newFile' => [
                __DIR__ . '/fixtures/newFile.txt',
                [
                    'changedFile.php' => [1, 2, 3]
                ]
            ],
            'lineChange' => [
                __DIR__ . '/fixtures/change.txt',
                [
                    'changedFile.php' => [3]
                ]
            ],
            'multipleFiles' => [
                __DIR__ . '/fixtures/multiple.txt',
                [
                    'changedFile.php' => [3],
                    'newFile.php' => [1, 2, 3]
                ]
            ],
            'removeFile' => [
                __DIR__ . '/fixtures/removeFile.txt',
                []
            ],
        ];
    }

    public function testEnsureContextLineIsValid()
    {
        $diff = new DiffFileState();
        $contextLine = new ContextLine($diff);
        $this->assertTrue($contextLine->isValid("anything"));
    }

    private function getChangedLines($file)
    {
        $fileLoader = new DiffFileLoader($file);
        return $fileLoader->getChangedLines();
    }
}
