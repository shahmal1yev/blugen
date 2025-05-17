<?php

namespace Blugen\Tests\Unit\Service\Lexicon\V1;

use Blugen\Service\Lexicon\V1\Schema;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class SchemaTest extends TestCase
{
    private function schemaArray(): array
    {
        return [
            'type' => 'object',
            'description' => 'Example schema description',
            'properties' => [
                'username' => [
                    'type' => 'string',
                    'minLength' => 3,
                    'maxLength' => 30,
                ],
                'meta' => [
                    'type' => 'object',
                    'properties' => [
                        'createdAt' => ['type' => 'string', 'format' => 'datetime'],
                        'updatedAt' => ['type' => 'string', 'format' => 'datetime'],
                    ],
                ],
            ],
        ];
    }

    public function test_schema_returns_correct_type_and_description(): void
    {
        $schema = new Schema($this->schemaArray());

        $this->assertSame('object', $schema->type());
        $this->assertSame('Example schema description', $schema->description());

        $this->assertIsString($schema->type());
        $this->assertIsString($schema->description());
    }

    public function test_schema_returns_null_when_description_is_missing(): void
    {
        $schemaArray = [
            'type' => 'array',
            // no description here
        ];

        $schema = new Schema($schemaArray);

        $this->assertNull($schema->description());
    }

    public static function magicSuccessPathsProvider(): array
    {
        return [
            ['properties.username.type', 'string'],
            ['properties.username.minLength', 3],
            ['properties.meta.properties.createdAt.format', 'datetime'],
        ];
    }

    #[DataProvider('magicSuccessPathsProvider')]
    public function test_magic_get_returns_expected_values(string $path, mixed $expected): void
    {
        $schema = new Schema($this->schemaArray());

        $this->assertSame($expected, $schema->__get($path));
    }

    public static function magicFailurePathsProvider(): array
    {
        return [
            ['nonExistent.path'],
            ['properties.username.nonExistingProperty'],
            ['properties.meta.properties.invalidField'],
        ];
    }

    #[DataProvider('magicFailurePathsProvider')]
    public function test_magic_get_returns_null_when_path_does_not_exist(string $path): void
    {
        $schema = new Schema($this->schemaArray());

        $this->assertNull($schema->__get($path));
    }

    public function test_magic_get_returns_null_when_traversing_non_array(): void
    {
        $schema = new Schema($this->schemaArray());

        // username.type is a string; trying to go deeper should fail
        $this->assertNull($schema->__get('properties.username.type.deep'));
    }
}
