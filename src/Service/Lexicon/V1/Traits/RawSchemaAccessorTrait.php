<?php

namespace Blugen\Service\Lexicon\V1\Traits;

use Blugen\Service\Lexicon\SchemaInterface;

trait RawSchemaAccessorTrait
{
    public function schema(): array
    {
        $propertyExists = property_exists($this, 'schema');

        if ($propertyExists && $this->schema instanceof SchemaInterface) {
            return $this->schema->schema();
        }

        if ($propertyExists && is_array($this->schema)) {
            return $this->schema;
        }

        throw new \LogicException(sprintf(
            "%s::\$schema must be an array or implement %s",
            self::class,
            SchemaInterface::class
        ));
    }
}
