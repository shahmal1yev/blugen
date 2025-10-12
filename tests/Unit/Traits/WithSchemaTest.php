<?php

namespace Blugen\Tests\Unit\Traits;

use Blugen\Service\Lexicon\V1\Traits\SchemaTrait;
use TypeError;

trait WithSchemaTest
{
    public function test_type_is_required(): void
    {
        $schema = $this->schema([
            // missing type
        ]);

        $this->expectException(TypeError::class);
        $schema->type();
    }

    public function test_type_returns_expected_type(): void
    {
        $schema = $this->schema([
            'type' => 'schema type'
        ]);

        $this->assertSame('schema type', $schema->type());
    }

    public function test_description_is_optional(): void
    {
        $schema = $this->schema([
            // missing description
        ]);

        $this->assertNull($schema->description());
    }

    public function test_description_returns_expected_value(): void
    {
        $schema = $this->schema([
            'description' => 'schema description'
        ]);

        $this->assertSame('schema description', $schema->description());
    }
}
