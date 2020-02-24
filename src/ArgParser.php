<?php
namespace exussum12\CoverageChecker;

use exussum12\CoverageChecker\Exceptions\ArgumentNotFound;

class ArgParser
{
    protected $args;

    public function __construct(array $args)
    {
        $this->args = $args;
    }

    /**
     * @throws ArgumentNotFound
     */
    public function getArg(string $name): string
    {
        if (is_numeric($name)) {
            $name = (int) $name;
            return $this->numericArg($name);
        }

        return $this->letterArg($name);
    }

    protected function numericArg(int $position): string
    {
        foreach ($this->args as $arg) {
            if ($arg[0] != '-' && $position-- == 0) {
                return $arg;
            }
        }

        throw new ArgumentNotFound();
    }

    protected function letterArg($name): string
    {
        $name = $this->getAdjustedArg($name);
        foreach ($this->args as $arg) {
            list($value, $arg) = $this->splitArg($arg);

            if ($arg[0] == '-' && $name == $arg) {
                return $value;
            }
        }

        throw new ArgumentNotFound();
    }

    protected function splitArg(string $arg): array
    {
        $value = '1';
        if (strpos($arg, '=') > 0) {
            list($arg, $value) = explode('=', $arg, 2);
        }

        return array($value, $arg);
    }

    protected function getAdjustedArg(string $name): string
    {
        $name = strlen($name) == 1 ?
            '-' . $name :
            '--' . $name;
        return $name;
    }
}
