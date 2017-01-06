<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;
use exussum12\CoverageChecker\DiffFileLoader;

class DiffFileLoaderTest extends TestCase
{
    /**
     * @dataProvider getResults
     */
    public function testDiffResultsMatch($file, $expected)
    {
        $changed = $this->getChangedLines($file);

        $this->assertEquals($changed, $expected);
    }
    /**
     * @expectedException InvalidArgumentException
     */
    public function testNonExistantFile()
    {
        $changed = $this->getChangedLines('ufhbubfusdf');
    }

    public function getResults()
    {
        return [
            'newFile' => [
                __DIR__ . '/fixtures/newFile.txt',
                [
                    'changedFile.php' => [1,2,3]
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
                    'newFile.php' => [1,2,3]
                ]
            ],
            'removeFile' => [
                __DIR__ . '/fixtures/removeFile.txt',
                []
            ],
        ];

    }


    private function getChangedLines($file)
    {
        $fileLoader = new DiffFileLoader($file);
        return $fileLoader->getChangedLines();
    }
}
