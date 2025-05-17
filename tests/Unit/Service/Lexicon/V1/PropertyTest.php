<?php

namespace Blugen\Tests\Unit\Service\Lexicon\V1;

use Blugen\Service\Lexicon\V1\Property;
use Blugen\Service\Lexicon\V1\Schema;
use PHPUnit\Framework\TestCase;

class PropertyTest extends TestCase
{
    private function sampleSchema(): Schema
    {
        return new Schema([
            'type' => 'string',
            'description' => 'Username of the user',
            'minLength' => 3,
            'maxLength' => 30,
        ]);
    }

    public function test_property_returns_correct_basic_information(): void
    {
        $schema = $this->sampleSchema();
        $property = new Property('username', $schema, false, true);

        $this->assertSame('username', $property->name());
        $this->assertSame($schema, $property->schema());
        $this->assertFalse($property->isNullable());
        $this->assertTrue($property->isRequired());
    }

    public function test_property_returns_description_from_schema(): void
    {
        $schema = $this->sampleSchema();
        $property = new Property('username', $schema);

        $this->assertSame('Username of the user', $property->description());
    }

    public function test_property_handles_nullable_true(): void
    {
        $schema = $this->sampleSchema();
        $property = new Property('email', $schema, true, false);

        $this->assertTrue($property->isNullable());
        $this->assertFalse($property->isRequired());
    }

    public function test_property_description_returns_null_when_schema_has_no_description(): void
    {
        $schema = new Schema([
            'type' => 'integer',
            // no description field
        ]);

        $property = new Property('age', $schema);

        $this->assertNull($property->description());
    }
}
