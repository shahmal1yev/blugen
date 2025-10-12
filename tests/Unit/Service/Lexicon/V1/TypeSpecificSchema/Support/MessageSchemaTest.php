<?php

namespace Blugen\Tests\Unit\Service\Lexicon\V1\TypeSpecificSchema\Support;

use Blugen\Service\Lexicon\V1\Schema;
use Blugen\Service\Lexicon\V1\TypeSpecificSchema\Support\MessageSchema;
use Blugen\Tests\TestCase;
use TypeError;

class MessageSchemaTest extends TestCase
{
    public function test_type_returns_expected_value(): void
    {
        $schema = new MessageSchema(new Schema([

        ]));

        $this->assertSame('message', $schema->type());
    }

    public function test_type_is_static(): void
    {
        $schema = new MessageSchema(new Schema([
            'type' => 'blah blah'
        ]));

        $this->assertSame('message', $schema->type());
    }

    public function test_description_returns_expected_value(): void
    {
        $schema = new MessageSchema(new Schema([
            'description' => 'foo bar baz'
        ]));

        $this->assertSame('foo bar baz', $schema->description());
    }

    public function test_description_is_optional(): void
    {
        $schema = new MessageSchema(new Schema([
            // missing desc
        ]));

        $this->assertNull($schema->description());
    }

    public function test_schema_returns_expected_value(): void
    {
        $schema = ['foo' => 'bar', 'baz' => 'qux'];
        $messageSchema = new MessageSchema(new Schema(['schema' => $schema]));

        $this->assertSame($schema, $messageSchema->schema());
    }

    public function test_schema_is_required(): void
    {
        $messageSchema = new MessageSchema(new Schema([
            // missing schema
        ]));

        $this->expectException(TypeError::class);

        $messageSchema->schema();
    }

    public function test_toArray_works_expected(): void
    {
        $schema = [
            'description' => 'blah blah blah',
            'schema' => [
                'type' => 'union',
                'refs' => ['app.com.example', 'example.reverse.dns']
            ]
        ];

        $messageSchema = new MessageSchema(new Schema($schema));

        $this->assertSame($schema, $messageSchema->toArray());
    }
}
