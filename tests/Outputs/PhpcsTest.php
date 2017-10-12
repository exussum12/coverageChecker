<?php
namespace exussum12\CoverageChecker\tests\Outputs;

use exussum12\CoverageChecker\Outputs\Phpcs;
use exussum12\CoverageChecker\tests\Output;

class PhpcsTest extends Output
{
    public function testSuccessfulOutput()
    {
        $output = new Phpcs();
        ob_start();
        $output->output($this->getAllValid(), 100, 100);
        $report = ob_get_clean();

        $expected = '{"files":[],"totals":{"errors":0,"fixable":0,"warnings":0}}';
        $this->assertJsonStringEqualsJsonString($expected, $report);
    }

    public function testFailedOutput()
    {
        $output = new Phpcs();
        ob_start();
        $output->output($this->getFailing(), 80, 100);
        $report = ob_get_clean();
        $expected = '{
            "files":
                {"file1.php":
                    {"messages":[
                        {
                            "message":"Line 10 has an error",
                            "line": 10,
                            "type":"ERROR",
                            "column":1,
                            "fixable":"false",
                            "severity":1,
                            "source":"diffFilter"
                        }
                      ],
                      "errors":1,
                      "warnings":0
                    }
                ,
                "file2.php":
                    {"messages":[
                        {
                            "message":"Line 15 has another error",
                            "line": 15,
                            "type":"ERROR",
                            "column":1,
                            "fixable":"false",
                            "severity":1,
                            "source":"diffFilter"
                        }
                      ],
                      "errors":1,
                      "warnings":0
                    }
                },
            "totals":{"errors":2,"fixable":0,"warnings":0}}';
        $this->assertJsonStringEqualsJsonString($expected, $report);
    }
}
