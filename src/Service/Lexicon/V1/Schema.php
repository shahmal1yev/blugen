<?php

namespace Blugen\Service\Lexicon\V1;

use Blugen\Service\Lexicon\SchemaInterface;
use Blugen\Service\Lexicon\V1\Traits\ArrayableTrait;
use Blugen\Service\Lexicon\V1\Traits\RawSchemaAccessorTrait;

class Schema implements SchemaInterface
{
    use ArrayableTrait;
    use RawSchemaAccessorTrait;

    public function __construct(private readonly array $schema)
    {
    }

    public function type(): string
    {
        return $this->schema['type'];
    }

    public function description(): ?string
    {
        return $this->schema['description'] ?? null;
    }

    public function __get(string $name): mixed
    {
        $schema = $this->schema;

        $pathParts = explode('.', $name);

        foreach ($pathParts as $part) {
            if (is_array($schema) && array_key_exists($part, $schema)) {
                $schema = $schema[$part];
            } else {
                return null;
            }
        }

        return $schema;
    }
}
