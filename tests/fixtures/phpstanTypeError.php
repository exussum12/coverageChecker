<?php
// @codingStandardsIgnoreStart
class A {
    /**
     * @param int $c
     */
    function B($c)
    {

    }
}

$a = new A;
$a->B("C");

function testing (int $a) {
}

if (false) {
    testing("hello");
}

// @codingStandardsIgnoreEnd
