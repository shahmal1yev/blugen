<?php

namespace Blugen\Tests\Unit\Support;

use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    /**
     * @dataProvider providePascalCaseConversions
     */
    public function test_to_pascal_case_converts_properly(string $input, string $expected): void
    {
        $this->assertSame($expected, toPascalCase($input));
    }

    public static function providePascalCaseConversions(): array
    {
        return [
            ['user_profile', 'UserProfile'],
            ['user-profile', 'UserProfile'],
            ['userProfile', 'UserProfile'],
            ['UserProfile', 'UserProfile'],
            ['some-random_case', 'SomeRandomCase'],
            ['anotherExample_here-test', 'AnotherExampleHereTest'],
            ['alreadyPascalCase', 'AlreadyPascalCase'],
            ['simple', 'Simple'],
            ['snake_case_example', 'SnakeCaseExample'],
            ['kebab-case-example', 'KebabCaseExample'],
            ['withMixed-Case_inputString', 'WithMixedCaseInputString'],
        ];
    }
}
