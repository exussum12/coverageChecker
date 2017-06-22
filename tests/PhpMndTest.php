<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;
use exussum12\CoverageChecker\PhpMndLoader;

class PhpMndTest extends TestCase
{
    public function testLoadClass()
    {
        $file = __DIR__ . "/fixtures/phpmnd.txt";
        $mnd = new PhpMndLoader($file);

        $this->assertInstanceOf(PhpMndLoader::class, $mnd);
    }

    public function testGetOutput()
    {
        $file = __DIR__ . "/fixtures/phpmnd.txt";
        $mnd = new PhpMndLoader($file);
        $expected = [
            'test.php' => [
                3 => 'Magic number: 7',
                4 => 'Magic number: 12',
            ],
            'test2.php' => [
                3 => 'Magic number: 7',
                4 => 'Magic number: 12',
            ],
        ];

        $this->assertSame($expected, $mnd->getLines());
    }
}
