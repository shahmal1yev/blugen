<?php

namespace Blugen\Service\Lexicon\V1\TypeSpecificSchema\Support;

use Blugen\Enum\SupportTypeEnum;
use Blugen\Service\Lexicon\SchemaInterface;

class OutputSchema extends IOAbstract implements SchemaInterface
{
    public function type(): string
    {
        return SupportTypeEnum::OUTPUT->value;
    }
}
