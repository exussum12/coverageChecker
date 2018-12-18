<?php
namespace exussum12\CoverageChecker\Outputs;

use exussum12\CoverageChecker\Output;

class Json implements Output
{

    public function output(array $coverage, float $percent, float $minimumPercent)
    {
        $violations = [];
        foreach ($coverage as $file => $lines) {
            foreach ($lines as $line => $error) {
                $violations[$file][] = (object) [
                    'lineNumber' => $line,
                    'message' => $error
                ];
            }
        }
        $output = (object) [
            'coverage' => number_format($percent, 2),
            'status' => $percent >= $minimumPercent ?
                'Passed':
                'Failed',
            'violations' => $violations
        ];
        echo json_encode($output) . "\n";
    }
}
