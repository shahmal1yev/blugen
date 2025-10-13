<?php

namespace Blugen\Service\Lexicon\V1\Schema\Container;

use Blugen\Service\Lexicon\SchemaInterface;
use Blugen\Service\Lexicon\V1\Exceptions\MissingRequiredFieldException;
use Blugen\Service\Lexicon\V1\Property;
use Blugen\Service\Lexicon\V1\Schema;
use Blugen\Service\Lexicon\V1\Traits\ArrayableTrait;
use Blugen\Service\Lexicon\V1\Traits\RawSchemaAccessorTrait;
use Blugen\Service\Lexicon\V1\Traits\SchemaTrait;
use Blugen\Service\Syntax\Factory\SchemaFactory;

class ObjectSchema implements SchemaInterface
{
    use SchemaTrait;
    use ArrayableTrait;
    use RawSchemaAccessorTrait;

    public function __construct(private readonly SchemaInterface $schema, private ?SchemaFactory $factory = null)
    {
        $this->factory ??= container()->get(SchemaFactory::class);
    }

    /**
     * @throws MissingRequiredFieldException
     */
    public function properties(): array
    {
        if (! is_array($properties = $this->__get('properties'))) {
            throw new MissingRequiredFieldException("Missing the required array property 'properties'");
        }

        return array_map(
            fn (array $schemaContent) => $this->factory::create($schemaContent['type'], $schemaContent),
            $properties
        );
    }

    public function required(): ?array
    {
        return $this->__get('required');
    }

    public function nullable(): ?array
    {
        return $this->__get('nullable');
    }
}
