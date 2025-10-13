<?php

namespace Blugen\Service\Lexicon\V1\Schema\Primary;

use Blugen\Service\Lexicon\SchemaInterface;
use Blugen\Service\Lexicon\V1\Schema;
use Blugen\Service\Lexicon\V1\Schema\Support\MessageSchema;

class SubscriptionSchema extends HTTPAbstract implements SchemaInterface
{
    public function message(): ?MessageSchema
    {
        if (is_null($schemaContent = $this->__get('message'))) {
            return null;
        }

        return new MessageSchema(new Schema($schemaContent));
    }
}
