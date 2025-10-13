<?php

namespace Blugen\Service\Lexicon\V1\Schema\Primary;

use Blugen\Service\Lexicon\SchemaInterface;
use Blugen\Service\Lexicon\V1\Schema;
use Blugen\Service\Lexicon\V1\Schema\Support\InputSchema;
use Blugen\Service\Lexicon\V1\Schema\Support\OutputSchema;

class ProcedureSchema extends HTTPAbstract implements SchemaInterface
{
    public function input(): ?InputSchema
    {
        $schemaContent = $this->__get('input');

        if (is_null($schemaContent)) {
            return null;
        }

        return new InputSchema(new Schema($schemaContent));
    }

    public function output(): ?OutputSchema
    {
        if (is_null($schemaContent = $this->__get('output'))) {
            return null;
        }

        return new OutputSchema(new Schema($schemaContent));
    }
}
