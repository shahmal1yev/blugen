<?php

namespace Blugen\Service\Lexicon\V1\Schema\Primary;

use Blugen\Service\Lexicon\SchemaInterface;
use Blugen\Service\Lexicon\V1\Schema;
use Blugen\Service\Lexicon\V1\Schema\Container\ParamsSchema;
use Blugen\Service\Lexicon\V1\Schema\Support\ErrorsSchema;
use Blugen\Service\Lexicon\V1\Traits\ArrayableTrait;
use Blugen\Service\Lexicon\V1\Traits\RawSchemaAccessorTrait;
use Blugen\Service\Lexicon\V1\Traits\SchemaTrait;

abstract class HTTPAbstract
{
    use RawSchemaAccessorTrait;
    use ArrayableTrait;
    use SchemaTrait;

    public function __construct(
        private readonly SchemaInterface $schema,
    )
    {
    }

    public function parameters(): ?ParamsSchema
    {
        if (is_null($schemaContent = $this->__get('parameters'))) {
            return null;
        }

        return new ParamsSchema(new Schema($schemaContent));
    }

    public function errors(): ?ErrorsSchema
    {
        if (is_null($schemaContent = $this->__get('errors'))) {
            return null;
        }

        return new ErrorsSchema(new Schema($schemaContent));
    }
}
