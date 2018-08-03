<?php
namespace exussum12\CoverageChecker\tests\Loaders;

use PHPUnit\Framework\TestCase;

use exussum12\CoverageChecker\Loaders\Jacoco;

class JacocoLoaderTest extends TestCase
{
    public function testLoadXML()
    {
        $xmlReport = new Jacoco(__DIR__ . '/../fixtures/jacoco.xml');
        $coveredLines = $xmlReport->parseLines();
        $expected = [
            'org/jacoco/examples/maven/java/HelloWorld.java',
            'org/jacoco/examples/maven/java/New/HelloWorld.java',

        ];

        $this->assertEquals($expected, $coveredLines);

        $this->assertEquals(
            [],
            $xmlReport->getErrorsOnLine('org/jacoco/examples/maven/java/HelloWorld.java', 3)
        );
        $this->assertEquals(
            ['No unit test covering this line'],
            $xmlReport->getErrorsOnLine('org/jacoco/examples/maven/java/HelloWorld.java', 7)
        );

        $this->assertNull($xmlReport->getErrorsOnLine('doesntExist.java', 10));
    }
}
