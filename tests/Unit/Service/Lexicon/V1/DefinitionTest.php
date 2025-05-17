<?php

namespace Blugen\Tests\Unit\Service\Lexicon\V1;

use Blugen\Service\Lexicon\LexiconInterface;
use Blugen\Service\Lexicon\V1\Definition;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class DefinitionTest extends TestCase
{
    private static array $defs = [
        'main' => [
            'type' => 'object',
            'description' => 'Main description',
            'properties' => [
                'meta' => [
                    'type' => 'object',
                    'properties' => [
                        'deleted' => ['type' => 'boolean'],
                        'createdAt' => ['format' => 'datetime'],
                    ],
                ],
                'username' => [
                    'type' => 'string',
                ],
            ],
        ],
        'usersList' => [
            'type' => 'array',
            'description' => 'Users list description',
        ],
        'userPreferences' => [
            'type' => 'object',
            'properties' => [
                'language' => [
                    'enum' => ["en", "az", "tr", "de"],
                ],
            ],
        ],
    ];

    private function lexicon(array $defs = null): LexiconInterface
    {
        $defs ??= self::$defs;

        $mock = $this->createMock(LexiconInterface::class);
        $mock->method('defs')->willReturn($defs);
        $mock->method('nsid')->willReturn('app.user.profile');
        $mock->method('version')->willReturn(1);
        $mock->method('description')->willReturn('Test lexicon');

        return $mock;
    }

    #[DataProvider('fixedPropertiesProvider')]
    public function test_definition_returns_correct_fixed_properties(string $defName, string $type, string $description): void
    {
        $definition = new Definition($this->lexicon(), $defName);

        $this->assertSame($type, $definition->type());
        $this->assertSame($defName, $definition->name());
        $this->assertSame($description, $definition->description());
        $this->assertInstanceOf(LexiconInterface::class, $definition->lexicon());
    }

    public static function fixedPropertiesProvider(): array
    {
        return [
            ['main', 'object', 'Main description'],
            ['usersList', 'array', 'Users list description'],
        ];
    }

    #[DataProvider('magicSuccessPathsProvider')]
    public function test_magic_get_returns_expected_values(string $path, mixed $expected): void
    {
        [$defName, $subPath] = explode('.', $path, 2);

        $definition = new Definition($this->lexicon(), $defName);

        $this->assertSame($expected, $definition->__get($subPath));
    }

    public static function magicSuccessPathsProvider(): array
    {
        return [
            ['userPreferences.properties.language.enum', ["en", "az", "tr", "de"]],
            ['main.properties.meta.properties.deleted.type', 'boolean'],
            ['main.properties.meta.properties.createdAt.format', 'datetime'],
        ];
    }

    #[DataProvider('magicFailurePathsProvider')]
    public function test_magic_get_returns_null_for_nonexistent_paths(string $path): void
    {
        [$defName, $subPath] = explode('.', $path, 2);

        $definition = new Definition($this->lexicon(), $defName);

        $this->assertNull($definition->__get($subPath));
    }

    public static function magicFailurePathsProvider(): array
    {
        return [
            ['usersList.bar.test'],
            ['main.properties.meta.nonExistentProperty'],
            ['main.nonExistent.level'],
            ['userPreferences.nonExisting.deep.path'],
        ];
    }

    public function test_magic_get_returns_null_when_accessing_non_array(): void
    {
        $definition = new Definition($this->lexicon(), 'main');

        $this->assertNull($definition->__get('properties.username.nonExistingDeepPath'));
    }

    public function test_definition_constructor_throws_exception_for_non_existent_name(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Definition 'missingDef' does not exist in the lexicon.");

        new Definition($this->lexicon(), 'missingDef');
    }
}
