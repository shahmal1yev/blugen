<?php

namespace Blugen\Tests\Unit\Service\Lexicon\V1\TypeSpecificSchema\Field;

use Blugen\Service\Lexicon\V1\Property;
use Blugen\Service\Lexicon\V1\Schema;
use Blugen\Service\Lexicon\V1\Schema\Field\ObjectSchema;
use PHPUnit\Framework\TestCase;

class ObjectSchemaTest extends TestCase
{
    private function make(array $schema): ObjectSchema
    {
        return new ObjectSchema(new Schema($schema));
    }

    public function test_type_description_and_to_array_filters_only_nulls(): void
    {
        $schema = [
            'type' => 'object',
            'description' => 'User profile',
            'properties' => [
                'name' => ['type' => 'string'],
            ],
            'required' => ['name'],
            'nullable' => null,
            'extra' => null,
        ];

        $object = $this->make($schema);

        $this->assertSame('object', $object->type());
        $this->assertSame('User profile', $object->description());

        $this->assertSame([
            'type' => 'object',
            'description' => 'User profile',
            'properties' => [
                'name' => ['type' => 'string'],
            ],
            'required' => ['name'],
        ], $object->toArray());
    }

    public function test_properties_maps_to_property_objects_with_flags(): void
    {
        $schema = [
            'type' => 'object',
            'properties' => [
                'name' => ['type' => 'string', 'description' => 'The name'],
                'age' => ['type' => 'integer'],
            ],
            'required' => ['name'],
            'nullable' => ['age'],
        ];

        $object = $this->make($schema);
        $props = $object->properties();

        $this->assertCount(2, $props);
        $this->assertContainsOnlyInstancesOf(Property::class, $props);

        // Preserve order from input (name, age)
        $nameProp = $props[0];
        $ageProp = $props[1];

        $this->assertSame('name', $nameProp->name());
        $this->assertSame(['type' => 'string', 'description' => 'The name'], $nameProp->schema()->toArray());
        $this->assertFalse($nameProp->isNullable());
        $this->assertTrue($nameProp->isRequired());

        $this->assertSame('age', $ageProp->name());
        $this->assertSame(['type' => 'integer'], $ageProp->schema()->toArray());
        $this->assertTrue($ageProp->isNullable());
        $this->assertFalse($ageProp->isRequired());
    }

    public function test_required_nullable_accessors_and_schema_return(): void
    {
        $schema = [
            'type' => 'object',
            'properties' => [],
            'required' => ['x'],
            'nullable' => ['y'],
        ];

        $object = $this->make($schema);
        $this->assertSame(['x'], $object->required());
        $this->assertSame(['y'], $object->nullable());
        $this->assertSame($schema, $object->schema());
    }

    public function test_required_nullable_null_when_absent(): void
    {
        $schema = [
            'type' => 'object',
            'properties' => [],
        ];

        $object = $this->make($schema);
        $this->assertNull($object->required());
        $this->assertNull($object->nullable());
    }

    public function test_magic_get_dotted_paths(): void
    {
        $schema = [
            'type' => 'object',
            'meta' => ['info' => ['title' => 'Example']],
        ];

        $object = $this->make($schema);
        $this->assertSame('Example', $object->__get('meta.info.title'));
        $this->assertNull($object->__get('meta.info.missing'));
    }
}
