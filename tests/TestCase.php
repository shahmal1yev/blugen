<?php

namespace Blugen\Tests;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        require_once __DIR__ . "/../bootstrap/container.php";
    }
}
