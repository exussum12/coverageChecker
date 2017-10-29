<?php
namespace exussum12\CoverageChecker\Outputs;

use exussum12\CoverageChecker\Output;

class Text implements Output
{
    public function output($coverage, $percent, $minimumPercent)
    {

        printf("%.2f%% Covered\n", $percent);

        $output = '';
        foreach ($coverage as $filename => $lines) {
            $output .= "\n\n'$filename' has no coverage for the following lines:\n";
            foreach ($lines as $line => $message) {
                $output .= $this->generateOutputLine($line, $message);
            }
        }

        echo trim($output);
    }

    private function generateOutputLine($line, $message)
    {
        $output = "Line $line:\n";
        if (!empty($message)) {
            foreach ($message as $part) {
                $output .= "\t$part\n";
            }
        }

        return $output . "\n";
    }
}
