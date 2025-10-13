<?php

namespace Blugen\Service\Lexicon\V1\Traits;

use Blugen\Service\Lexicon\DefinitionInterface;

trait DefinitionTrait
{
    public function schema(): array
    {
        if (property_exists($this, 'definition') && $this->definition instanceof DefinitionInterface) {
            return $this->definition->schema();
        }

        throw new \LogicException(sprintf(
            "%s::\$definition must implement %s",
            self::class,
            DefinitionInterface::class
        ));
    }
}
