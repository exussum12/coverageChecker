<?php
namespace exussum12\CoverageChecker\tests;

use exussum12\CoverageChecker\DiffFileLoaderOldVersion;
use PHPUnit\Framework\TestCase;

class DiffFileLoadOldVersionTest extends TestCase
{
    /**
     * @dataProvider getResults
     */
    public function testDiffResultsMatch($file, $expected)
    {
        $changed = $this->getChangedLines($file);

        $this->assertEquals($expected, $changed);
    }

    public function getResults()
    {
        return [
            'newFile' => [
                __DIR__ . '/fixtures/newFile.txt',
                [
                    'dev/null' => [-1]
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
                     'dev/null' => [-1],
                     'deletedFile.php' => [1,2,3]
                 ]
             ],
             'removeFile' => [
                 __DIR__ . '/fixtures/removeFile.txt',
                 [
                     'deletedFile.php' => [1,2,3]
                 ]
             ],
        ];
    }

    private function getChangedLines($file)
    {
        $fileLoader = new DiffFileLoaderOldVersion($file);
        return $fileLoader->getChangedLines();
    }
}
