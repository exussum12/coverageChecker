<?php
namespace exussum12\CoverageChecker\Loaders;

/**
 * Class PhpMdLoaderStrict
 * Used for parsing phpmd xml output
 * Strict mode reports errors multiple times
 * @package exussum12\CoverageChecker
 */
class PhpMdStrict extends PhpMd
{
    public function getErrorsOnLine(string $file, int $lineNumber)
    {
        $errors = [];
        foreach ($this->errorRanges[$file] as $error) {
            if ((
                $error['start'] <= $lineNumber &&
                $error['end'] >= $lineNumber
            )) {
                $errors[] = $error['error'];
            }
        }

        return $errors;
    }

    /**
     * {@inheritdoc}
     */
    public static function getDescription(): string
    {
        return 'Parses the xml report format of phpmd, this mode ' .
            'reports multi line violations once per line they occur ';
    }
}
