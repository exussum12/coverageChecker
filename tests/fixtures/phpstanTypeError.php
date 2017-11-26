<?php
/**
 * Ignored due to being intentionally bad
 * @codingStandardsIgnoreStart
 *
 * @SuppressWarnings(PHPMD)
 */
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
/**
 * @SuppressWarnings(PHPMD)
 */
function testing (int $a) {
}

if (false) {
    testing("hello");
}

// @codingStandardsIgnoreEnd
