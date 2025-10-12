<?php

namespace Blugen\Service\Lexicon\V1\TypeSpecificSchema\Support;

use Blugen\Service\Lexicon\SchemaInterface;
use Blugen\Service\Lexicon\V1\Traits\ArrayableTrait;
use Blugen\Service\Lexicon\V1\Traits\SchemaTrait;
use Blugen\Service\Lexicon\V1\Traits\SupportSchemaTrait;

class ErrorSchema implements SchemaInterface
{
    use ArrayableTrait;
    use SchemaTrait;
    use SupportSchemaTrait;

    public function __construct(private readonly SchemaInterface $schema)
    {

    }

    public function name(): string
    {
        return $this->__get('name');
    }

    public function description(): ?string
    {
        return $this->__get('description');
    }

    public function __get(string $name): mixed
    {
        return $this->schema->__get($name);
    }
}
