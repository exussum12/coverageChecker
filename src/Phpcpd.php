<?php
namespace exussum12\CoverageChecker;

class Phpcpd implements FileChecker
{
    protected $file;

    protected $duplicateCode = [];

    public function __construct($file)
    {
        $this->file = fopen($file, 'r');
    }

    public function parseLines()
    {
        $block = [];
        $this->duplicateCode = [];
        while (($line = fgets($this->file)) !== false) {
            if (!$this->hasFileName($line)) {
                continue;
            }

            if ($this->startOfBlock($line)) {
                $this->handleEndOfBlock($block);
                $block = [];
            }

            $block += $this->addFoundBlock($line);
        }

        return array_keys($this->duplicateCode);
    }


    public function getErrorsOnLine($file, $lineNumber)
    {
        $errors = [];
        if (isset($this->duplicateCode[$file][$lineNumber])) {
            $errors = $this->duplicateCode[$file][$lineNumber];
        }

        return $errors;
    }

    public function handleNotFoundFile()
    {
        return true;
    }

    public static function getDescription()
    {
        return "Parses the text output from phpcpd (Copy Paste Detect)";
    }

    private function startOfBlock($line)
    {
        return preg_match('/^\s+-/', $line);
    }

    private function hasFileName($line)
    {
        return preg_match('/:\d+-\d+/', $line);
    }

    private function addFoundBlock($line)
    {
        $matches = [];
        preg_match('/\s+(?:- )?(?<fileName>.*?):(?<startLine>\d+)-(?<endLine>\d+)$/', $line, $matches);
        return [$matches['fileName'] => range($matches['startLine'], $matches['endLine'])];
    }

    private function handleEndOfBlock($block)
    {
        foreach ($block as $filename => $lines) {
            foreach ($lines as $lineNumber) {
                foreach ($block as $duplicate => $dupeLines) {
                    if ($filename == $duplicate) {
                        continue;
                    }
                    $start = reset($dupeLines);
                    $end = end($dupeLines);
                    $message = "Duplicate of " . $duplicate . ':' . $start . '-' . $end;
                    $this->duplicateCode[$filename][$lineNumber][] = $message;
                }
            }
        }
    }
}
