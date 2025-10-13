<?php

namespace Blugen\Service\Syntax\Factory;

use Blugen\Service\Lexicon\SchemaInterface;
use Blugen\Service\Lexicon\V1\Schema;
use Blugen\Service\Lexicon\V1\Schema\Concrete\BlobSchema;
use Blugen\Service\Lexicon\V1\Schema\Concrete\BooleanSchema;
use Blugen\Service\Lexicon\V1\Schema\Concrete\BytesSchema;
use Blugen\Service\Lexicon\V1\Schema\Concrete\CidLinkSchema;
use Blugen\Service\Lexicon\V1\Schema\Concrete\IntegerSchema;
use Blugen\Service\Lexicon\V1\Schema\Concrete\NullSchema;
use Blugen\Service\Lexicon\V1\Schema\Concrete\StringSchema;
use Blugen\Service\Lexicon\V1\Schema\Container\ArraySchema;
use Blugen\Service\Lexicon\V1\Schema\Container\ObjectSchema;
use Blugen\Service\Lexicon\V1\Schema\Meta\RefSchema;
use Blugen\Service\Lexicon\V1\Schema\Meta\TokenSchema;
use Blugen\Service\Lexicon\V1\Schema\Meta\UnionSchema;
use Blugen\Service\Lexicon\V1\Schema\Meta\UnknownSchema;

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
