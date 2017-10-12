<?php
namespace exussum12\CoverageChecker;

class ArgParser
{
    protected $args;

    public function __construct(array $args)
    {
        $this->args = $args;
    }

    public function getArg($name)
    {
        if (is_numeric($name)) {
            return $this->numericArg($name);
        }

        return $this->letterArg($name);
    }

    protected function numericArg($position)
    {
        foreach ($this->args as $arg) {
            if ($arg{0} != '-' && $position-- == 0) {
                return $arg;
            }
        }

        return null;
    }

    protected function letterArg($name)
    {
        $name = $this->getAdjustedArg($name);
        foreach ($this->args as $arg) {
            list($value, $arg) = $this->splitArg($arg);

            if ($arg{0} == '-' && $name == $arg) {
                return $value;
            }
        }

        return false;
    }

    protected function splitArg($arg)
    {
        $value = true;
        if (strpos($arg, '=')) {
            list($arg, $value) = explode('=', $arg, 2);
        }

        return array($value, $arg);
    }

    /**
     * @param $name
     * @return string
     */
    protected function getAdjustedArg($name)
    {
        $name = strlen($name) == 1 ?
            '-' . $name :
            '--' . $name;
        return $name;
    }
}
