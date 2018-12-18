<?php
namespace exussum12\CoverageChecker\Outputs;

use exussum12\CoverageChecker\Output;

class Phpcs implements Output
{

    private $violations;

    public function output(array $coverage, float $percent, float $minimumPercent)
    {
        $this->violations = ['files' => []];
        $total = 0;
        foreach ($coverage as $file => $lines) {
            foreach ($lines as $line => $errors) {
                $this->displayErrors($errors, $file, $line);
            }
            $total++;
        }

        $this->addTotal($total);

        echo json_encode($this->violations) . "\n";
    }

    protected function displayErrors(array $errors, string $file, int $line)
    {
        foreach ($errors as $error) {
            $current = &$this->violations['files'][$file];
            $current['messages'][] = [
                'message' => $error,
                'source' => 'diffFilter',
                'severity' => 1,
                'type' => 'ERROR',
                'line' => $line,
                'column' => 1,
                'fixable' => 'false',
            ];

            $current['errors'] = count(
                $current['messages']
            );
            $current['warnings'] = 0;
        }
    }

    protected function addTotal(int $total)
    {
        $this->violations['totals'] = [
            'errors' => $total,
            'fixable' => 0,
            'warnings' => 0,
        ];
    }
}
