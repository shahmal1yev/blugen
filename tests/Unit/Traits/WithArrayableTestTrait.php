<?php

namespace Blugen\Tests\Unit\Traits;

trait WithArrayableTestTrait
{
    public function test_toArray_works_expected(): void
    {
        $expected = [[
            'type' => 'schema type',
            'description' => 'schema description',
            'foo' => 'bar',
            'bar' => 'qux'
        ]];

        $schema = $this->schema($expected);

        $this->assertSame($expected, $schema->toArray());
    }
}
