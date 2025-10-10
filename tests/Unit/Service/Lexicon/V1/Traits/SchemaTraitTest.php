<?php

namespace Blugen\Tests\Unit\Service\Lexicon\V1\Traits;

use Blugen\Service\Lexicon\SchemaInterface;
use Blugen\Service\Lexicon\V1\Schema;
use Blugen\Service\Lexicon\V1\Traits\SchemaTrait;
use PHPUnit\Framework\TestCase;

class SchemaTraitTest extends TestCase
{
    public function test_schema_returns_array_when_backed_by_array_property(): void
    {
        $data = ['type' => 'object', 'description' => 'demo'];

        $obj = new class($data) {
            use SchemaTrait;

            public function __construct(private array $schema)
            {
            }
        };

        $this->assertSame($data, $obj->schema());
    }

    public function test_schema_returns_array_when_backed_by_schema_interface(): void
    {
        $data = ['type' => 'object', 'description' => 'from-interface'];

        $obj = new class(new Schema($data)) {
            use SchemaTrait;

            public function __construct(private SchemaInterface $schema)
            {
            }
        };

        $this->assertSame($data, $obj->schema());
    }

    public function test_schema_throws_when_property_missing(): void
    {
        $obj = new class {
            use SchemaTrait;
        };

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('::$schema must be an array or implement');

        $obj->schema();
    }

    public function test_schema_throws_when_property_invalid_type(): void
    {
        $obj = new class('not-an-array-or-interface') {
            use SchemaTrait;

            public function __construct(private string $schema)
            {
            }
        };

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('::$schema must be an array or implement');

        $obj->schema();
    }
}

