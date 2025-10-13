<?php

namespace Blugen\Service\Syntax\Factory;

use Blugen\Service\Lexicon\SchemaInterface;
use Blugen\Service\Lexicon\V1\Schema;
use Blugen\Service\Lexicon\V1\Schema\Field\ArraySchema;
use Blugen\Service\Lexicon\V1\Schema\Field\BlobSchema;
use Blugen\Service\Lexicon\V1\Schema\Field\BooleanSchema;
use Blugen\Service\Lexicon\V1\Schema\Field\BytesSchema;
use Blugen\Service\Lexicon\V1\Schema\Field\CidLinkSchema;
use Blugen\Service\Lexicon\V1\Schema\Field\IntegerSchema;
use Blugen\Service\Lexicon\V1\Schema\Field\NullSchema;
use Blugen\Service\Lexicon\V1\Schema\Field\ObjectSchema;
use Blugen\Service\Lexicon\V1\Schema\Field\RefSchema;
use Blugen\Service\Lexicon\V1\Schema\Field\StringSchema;
use Blugen\Service\Lexicon\V1\Schema\Field\TokenSchema;
use Blugen\Service\Lexicon\V1\Schema\Field\UnionSchema;
use Blugen\Service\Lexicon\V1\Schema\Field\UnknownSchema;

class SchemaFactory
{
    public static function create(string $type, array $schema): SchemaInterface
    {
        return new (match ($type) {
            'string' => StringSchema::class,
            'integer' => IntegerSchema::class,
            'boolean' => BooleanSchema::class,
            'array' => ArraySchema::class,
            'null' => NullSchema::class,
            'ref' => RefSchema::class,
            'blob' => BlobSchema::class,
            'bytes' => BytesSchema::class,
            'cid-link' => CidLinkSchema::class,
            'object' => ObjectSchema::class,
            'token' => TokenSchema::class,
            'union' => UnionSchema::class,
            'unknown' => UnknownSchema::class,

            default => throw new \InvalidArgumentException("Not found a type-specific schema for type '$type'"),
        })(new Schema($schema));
    }
}
