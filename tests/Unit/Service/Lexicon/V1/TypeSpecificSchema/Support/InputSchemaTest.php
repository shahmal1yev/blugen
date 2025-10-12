<?php

namespace Blugen\Tests\Unit\Service\Lexicon\V1\TypeSpecificSchema\Support;

use Blugen\Service\Lexicon\V1\Schema;
use Blugen\Service\Lexicon\V1\TypeSpecificSchema\Field\ParamsSchema;
use Blugen\Service\Lexicon\V1\TypeSpecificSchema\Support\InputSchema;
use Blugen\Tests\TestCase;
use TypeError;

class InputSchemaTest extends TestCase
{
    public function test_type_returns_expected_value(): void
    {
        $schema = new InputSchema(new Schema([]));

        $expected = 'input';
        $actual = $schema->type();

        $this->assertSame($expected, $actual);
    }

    public function test_description_returns_expected_value(): void
    {
        $schema = new InputSchema(new Schema([
            'description' => 'An example description.',
        ]));

        $expected = 'An example description.';
        $actual = $schema->description();

        $this->assertSame($expected, $actual);
    }

    public function test_description_is_optional(): void
    {
        $schema = new InputSchema(new Schema([
            // missing description
        ]));

        $this->assertNull($schema->description());
    }

    public function test_encoding_returns_expected_value(): void
    {
        $schema = new InputSchema(new Schema([
            'encoding' => 'application/json',
        ]));

        $this->assertSame('application/json', $schema->encoding());
    }

    public function test_encoding_is_required(): void
    {
        $schema = new InputSchema(new Schema([
            // missing encoding
        ]));

        $this->expectException(TypeError::class);

        $schema->encoding();
    }

    public function test_schema_returns_expected_value(): void
    {
        $schema = ['type' => 'ref', 'ref' => 'com.example.schema'];
        $inputSchema = new InputSchema(new Schema(['schema' => $schema]));

        $this->assertSame($schema, $inputSchema->schema());
    }

    public function test_schema_is_required(): void
    {
        $schema = new InputSchema(new Schema([
            // missing schema
        ]));

        $this->expectException(TypeError::class);

        $schema->schema();
    }

    public function test_toArray_works_properly(): void
    {
        $expected = [
            'description' => 'An example description.',
            'encoding' => 'application/json',
            'schema' => [
                'type' => 'union',
                'refs' => [
                    'com.example.schema',
                    'com.test.another.schema',
                    'tools.example.schema',
                ]
            ]
        ];

        $schema = new InputSchema(new Schema($expected));

        $this->assertSame($expected, $schema->toArray());
    }

    public function test_parameters_returns_expected_value(): void
    {
        $params = [
            'required' => ['field1', 'field2'],
            'properties' => [
                [
                    'type' => 'string',
                    'format' => 'did',
                ],
                [
                    'type' => 'array',
                    'items' => [
                        'type' => 'integer',
                        'minimum' => 30,
                        'maximum' => 100,
                    ]
                ]
            ],
        ];

        $schema = new InputSchema(new Schema([
            'parameters' => $params,
        ]));

        $this->assertSame($params, $schema->parameters());
    }

    public function test_parameters_is_optional(): void
    {
        $schema = new InputSchema(new Schema([
            // missing parameters
        ]));

        $this->assertNull($schema->parameters());
    }
}
