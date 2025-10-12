<?php

namespace Blugen\Tests\Unit\Service\Lexicon\V1\TypeSpecificSchema\Support;

use ArrayIterator;
use Blugen\Service\Lexicon\V1\Schema;
use Blugen\Service\Lexicon\V1\TypeSpecificSchema\Support\ErrorSchema;
use Blugen\Service\Lexicon\V1\TypeSpecificSchema\Support\ErrorsSchema;
use Blugen\Tests\TestCase;
use Blugen\Tests\Unit\Traits\WithArrayableTest;
use Blugen\Tests\Unit\Traits\WithGetTest;
use Blugen\Tests\Unit\Traits\WithSchema;
use Blugen\Tests\Unit\Traits\WithSupportSchemaTest;
use PHPUnit\Framework\Attributes\DataProvider;
use TypeError;

class ErrorsSchemaTest extends TestCase
{
    use WithSchema;
    use WithGetTest;
    use WithArrayableTest;
    use WithSupportSchemaTest;

    private function schema(array $content): ErrorsSchema
    {
        return new ErrorsSchema(new Schema($content));
    }

    public function test_offsetSet_throws_badMethodCall_exception(): void
    {
        $schema = $this->schema([]);

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage("Schema is read-only");

        $schema->offsetSet('foo', 'bar');
    }

    public function test_offsetUnset_throws_badMethodCall_exception(): void
    {
        $schema = $this->schema([]);

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage("Schema is read-only");

        $schema->offsetUnset('foo');
    }

    #[DataProvider('offsetExistsCaseProvider')]
    public function test_offsetExists_returns_expected_boolean(array $content, int $index, bool $expected): void
    {
        $schema = $this->schema($content);

        $this->assertSame($expected, $schema->offsetExists($index));
    }

    public static function offsetExistsCaseProvider(): array
    {
        $schema = [
            [],
        ];

        return [
            [$schema, 0, true],
            [$schema, 1, false],
        ];
    }

    public function test_offsetGet_returns_ErrorSchema_instance(): void
    {
        $schema = $this->schema([[]]);

        $this->assertInstanceOf(ErrorSchema::class, $schema->offsetGet(0));
    }

    public function test_offsetGet_throws_TypeError_when_index_does_not_exist(): void
    {
        $schema = $this->schema([]);

        $this->expectException(TypeError::class);

        $schema->offsetGet('foo');
    }

    public static function countCaseProvider(): \Generator
    {
        $schema = [];

        foreach (range(1, 5) as $item) {
            $schema[] = ['name' => 'error name', 'description' => 'error description'];

            yield ['schema' => $schema, 'expected' => $item];
        }
    }

    #[DataProvider('countCaseProvider')]
    public function test_count_returns_actual_count(array $schema, int $expected)
    {
        $schema = $this->schema($schema);

        $this->assertSame($expected, $schema->count());
    }

    public function test_getIterator_returns_ArrayIterator_instance(): void
    {
        $schema = $this->schema([]);
        $this->assertInstanceOf(ArrayIterator::class, $schema->getIterator());
    }

    public function test_getIterator_yields_ErrorSchema_instances(): void
    {
        $schema = $this->schema([['name' => 'n']]);

        foreach ($schema as $item) {
            $this->assertInstanceOf(ErrorSchema::class, $item);
        }
    }
}
