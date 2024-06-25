<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use exussum12\CoverageChecker\DiffFileLoaderOldVersion;


class DiffFileLoadOldVersionTest extends TestCase
{
    #[DataProvider('getResults')]
    public function testDiffResultsMatch($file, $expected)
    {
        $changed = $this->getChangedLines($file);

        $this->assertEquals($expected, $changed);
    }

    public static function getResults()
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
