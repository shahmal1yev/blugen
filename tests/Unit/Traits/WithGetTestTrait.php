<?php

namespace Blugen\Tests\Unit\Traits;

use PHPUnit\Framework\Attributes\DataProvider;

trait WithGetTestTrait
{
    #[DataProvider('magicGetCaseProvider')]
    public function test_magic_get_works_as_expected(array $arr, string $param, mixed $expected): void
    {
        $schema = $this->schema($arr);

        $this->assertSame($expected, $schema->__get($param));
    }

    public static function magicGetCaseProvider(): array
    {
        $arr = [
            'app' => ['bsky' => ['feed' => ['post' => 'app.bsky.feed.post']]],
            'com' => ['atproto' => ['repo' => []]],
            'nine' => ['eight' => ['seven' => ['six' => null]]]
        ];

        return [
            [$arr, 'app.bsky.feed.post', 'app.bsky.feed.post'],
            [$arr, 'com.atproto.repo', []],
            [$arr, 'com.atproto.repo.createRecord', null],
            [$arr, 'nine.eight.seven.six', null],
        ];
    }
}
