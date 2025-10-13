<?php

namespace Blugen\Tests\Unit\Traits;

use Blugen\Service\Lexicon\SchemaInterface;

trait WithSchema
{
    abstract private function schema(array $content): SchemaInterface;
}
