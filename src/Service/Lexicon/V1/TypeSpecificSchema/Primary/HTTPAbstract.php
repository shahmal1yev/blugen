<?php

namespace Blugen\Service\Lexicon\V1\TypeSpecificSchema\Primary;

use Blugen\Service\Lexicon\SchemaInterface;
use Blugen\Service\Lexicon\V1\Schema;
use Blugen\Service\Lexicon\V1\Traits\ArrayableTrait;
use Blugen\Service\Lexicon\V1\Traits\DefaultSchemaTrait;
use Blugen\Service\Lexicon\V1\Traits\SchemaTrait;
use Blugen\Service\Lexicon\V1\TypeSpecificSchema\Field\ParamsSchema;
use Blugen\Service\Lexicon\V1\TypeSpecificSchema\Support\ErrorsSchema;

abstract class HTTPAbstract
{
    use SchemaTrait;
    use ArrayableTrait;
    use DefaultSchemaTrait;

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
