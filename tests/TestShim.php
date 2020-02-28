<?php
namespace exussum12\CoverageChecker\tests;

trait TestShim
{
    public function assertContainsString($test, $compare)
    {
        if (method_exists(parent::class, 'assertStringContainsString')) {
            return $this->assertStringContainsString($test, $compare);
        }
        return $this->assertContains($test, $compare);
    }
}
