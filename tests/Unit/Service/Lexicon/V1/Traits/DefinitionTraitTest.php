<?php

namespace Blugen\Tests\Unit\Service\Lexicon\V1\Traits;

use Blugen\Service\Lexicon\DefinitionInterface;
use Blugen\Service\Lexicon\LexiconInterface;
use Blugen\Service\Lexicon\V1\Traits\DefinitionTrait;
use PHPUnit\Framework\TestCase;

class DefinitionTraitTest extends TestCase
{
    public function test_schema_delegates_to_underlying_definition(): void
    {
        $schema = ['type' => 'object', 'props' => ['a' => ['type' => 'string']]];

        $lexicon = new class implements LexiconInterface {
            public function nsid(): string { return 'com.example.test'; }
            public function version(): int { return 1; }
            public function description(): ?string { return null; }
            public function defs(): array { return []; }
        };

        $definition = new class($schema, $lexicon) implements DefinitionInterface {
            public function __construct(private array $schema, private LexiconInterface $lexicon) {}
            public function name(): string { return 'com.example.test#def'; }
            public function type(): string { return $this->schema['type']; }
            public function description(): ?string { return $this->schema['description'] ?? null; }
            public function lexicon(): LexiconInterface { return $this->lexicon; }
            public function __get(string $name): mixed { return $this->schema[$name] ?? null; }
            public function toArray(): array { return $this->schema; }
            // Not required by interface, but used by DefinitionTrait
            public function schema(): array { return $this->schema; }
        };

        $wrapper = new class($definition) {
            use DefinitionTrait;
            public function __construct(private DefinitionInterface $definition) {}
        };

        $this->assertSame($schema, $wrapper->schema());
    }

    public function test_schema_throws_when_definition_property_missing(): void
    {
        $wrapper = new class {
            use DefinitionTrait;
        };

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('::$definition must implement');
        $wrapper->schema();
    }

    public function test_schema_throws_when_definition_wrong_type(): void
    {
        $wrapper = new class('not-a-definition') {
            use DefinitionTrait;
            public function __construct(private string $definition) {}
        };

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('::$definition must implement');
        $wrapper->schema();
    }
}

