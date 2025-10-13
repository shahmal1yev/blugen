<?php

namespace Blugen\Tests\Unit\Service\Lexicon\V1\Traits;

use Blugen\Service\Lexicon\V1\Traits\ArrayableTrait;
use PHPUnit\Framework\TestCase;

class ArrayableTraitTest extends TestCase
{
    private function makeArrayable(array $schema): object
    {
        return new class($schema) {
            use ArrayableTrait;

            public function __construct(private array $schema)
            {
            }

            public function schema(): array
            {
                return $this->schema;
            }
        };
    }

    public function test_to_array_filters_only_null_values(): void
    {
        $schema = [
            'a' => 1,
            'b' => null,
            'c' => 0,
            'd' => '',
            'e' => false,
            'f' => [],
            'g' => null,
        ];

        $obj = $this->makeArrayable($schema);

        $this->assertSame([
            'a' => 1,
            'c' => 0,
            'd' => '',
            'e' => false,
            'f' => [],
        ], $obj->toArray());
    }

    public function test_to_array_preserves_nested_nulls(): void
    {
        $schema = [
            'nested' => [
                'x' => null,
                'y' => 1,
            ],
            'top' => null,
        ];

        $obj = $this->makeArrayable($schema);

        $this->assertSame([
            'nested' => [
                'x' => null,
                'y' => 1,
            ],
        ], $obj->toArray());
    }

    public function test_to_array_preserves_numeric_keys(): void
    {
        $schema = [1, null, 2, null, 3];

        $obj = $this->makeArrayable($schema);

        // array_filter with a callback preserves keys
        $this->assertSame([
            0 => 1,
            2 => 2,
            4 => 3,
        ], $obj->toArray());
    }

    public function test_to_array_returns_empty_array_when_all_null(): void
    {
        $schema = ['a' => null, 'b' => null];
        $obj = $this->makeArrayable($schema);

        $this->assertSame([], $obj->toArray());
    }
}

