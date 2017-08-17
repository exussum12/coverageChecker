<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;

use exussum12\CoverageChecker\JacocoReport;

class JacocoLoaderTest extends TestCase
{
    public function testLoadXML()
    {
        $xmlReport = new JacocoReport(__DIR__ . '/fixtures/jacoco.xml');
        $coveredLines = $xmlReport->getLines();
        $expected = [
            'org/jacoco/examples/maven/java/HelloWorld.java' => [
                3 => true,
                6 => true,
                7 => false,
                9 => true,
            ],
            'org/jacoco/examples/maven/java/New/HelloWorld.java' => [
                3 => false,
                6 => false,
                7 => false,
                9 => false,
            ]

        ];

        $this->assertEquals($expected, $coveredLines);
    }
}
