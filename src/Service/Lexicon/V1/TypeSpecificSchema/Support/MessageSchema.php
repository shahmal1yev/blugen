<?php

namespace Blugen\Service\Lexicon\V1\TypeSpecificSchema\Support;

use Blugen\Enum\SupportTypeEnum;
use Blugen\Service\Lexicon\SchemaInterface;
use Blugen\Service\Lexicon\V1\Schema;
use Blugen\Service\Lexicon\V1\TypeSpecificSchema\Field\UnionSchema;

class MessageSchema implements SchemaInterface
{
    public function __construct(private readonly SchemaInterface $schema)
    {
    }

    public function type(): string
    {
        return SupportTypeEnum::MESSAGE->value;
    }

    public function description(): ?string
    {
        return $this->schema->description() ?? null;
    }

    public function __get(string $name): mixed
    {
        return $this->schema->__get($name);
    }

    public function schema(): UnionSchema
    {
        /** @var array $schema */
        $schema = $this->__get('schema');
        return new UnionSchema(new Schema($schema));
    }
}
