<?php

namespace Blugen\Tests\Unit\Traits;

use Blugen\Service\Lexicon\SchemaInterface;
use Blugen\Service\Lexicon\V1\TypeSpecificSchema\Support\ErrorSchema;

trait WithSchema
{
    abstract private function schema(array $content): SchemaInterface;
}
