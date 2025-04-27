<?php

namespace Blugen\Tests\Unit\Service\Lexicon\V1;

use Blugen\Service\Lexicon\V1\Lexicon;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use JsonException;

class LexiconTest extends TestCase
{
    private function sampleLexiconArray(): array
    {
        return [
            'version' => 1,
            'id' => 'app.user.profile',
            'description' => 'Sample user profile lexicon.',
            'defs' => [
                'main' => [
                    'type' => 'object',
                ],
                'usersList' => [
                    'type' => 'array',
                ],
            ],
        ];
    }

    private function sampleLexiconJson(): string
    {
        return json_encode($this->sampleLexiconArray(), JSON_THROW_ON_ERROR);
    }

    #[DataProvider('lexiconProvider')]
    public function test_it_accepts_json_and_array_on_construction(array $expectedArray, Lexicon $lexicon): void
    {
        $this->assertSame($expectedArray['id'], $lexicon->nsid());
        $this->assertSame($expectedArray['version'], $lexicon->version());
        $this->assertSame($expectedArray['description'], $lexicon->description());
        $this->assertSame($expectedArray['defs'], $lexicon->defs());

        $this->assertIsString($lexicon->nsid());
        $this->assertIsInt($lexicon->version());
        $this->assertIsArray($lexicon->defs());
        $this->assertTrue(
            is_string($lexicon->description()) || is_null($lexicon->description())
        );
    }

    public static function lexiconProvider(): array
    {
        $array = [
            'version' => 1,
            'id' => 'app.user.profile',
            'description' => 'Sample user profile lexicon.',
            'defs' => [
                'main' => ['type' => 'object'],
                'usersList' => ['type' => 'array'],
            ],
        ];

        return [
            'from array' => [$array, new Lexicon($array)],
            'from json'  => [$array, new Lexicon(json_encode($array, JSON_THROW_ON_ERROR))],
        ];
    }

    public function test_it_throws_json_exception_when_invalid_json_provided(): void
    {
        $this->expectException(JsonException::class);

        new Lexicon('invalid_json');
    }

    public function test_description_can_be_null_if_not_set(): void
    {
        $array = [
            'version' => 1,
            'id' => 'app.minimal',
            'defs' => [],
        ];

        $lexicon = new Lexicon($array);

        $this->assertNull($lexicon->description());
    }
}
