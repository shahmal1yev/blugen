<?php

namespace Blugen\Tests\Unit\Traits;

trait WithSupportSchemaTestTrait
{
    public function test_type_throws_exception(): void
    {
        $schema = $this->schema([]);

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage($schema::class . "::type() is not supported for support schemas");

        $schema->type();
    }

    public function test_description_throws_exception(): void
    {
        $schema = $this->schema([]);

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage($schema::class . "::description() is not supported for support schemas");

        $schema->description();
    }
}
