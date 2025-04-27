<?php

namespace Blugen\Service\Lexicon\V1\Factory;

use Blugen\Service\Lexicon\DefinitionInterface;
use Blugen\Service\Lexicon\GeneratorInterface;
use Blugen\Service\Lexicon\V1\DefGenerator\Field\ArrayGenerator;
use Blugen\Service\Lexicon\V1\DefGenerator\Field\ObjectGenerator;
use Blugen\Service\Lexicon\V1\TypeSpecificDefinition\Field\ArrayTypeDefinition;
use Blugen\Service\Lexicon\V1\TypeSpecificDefinition\Field\ObjectTypeDefinition;

class DefGeneratorFactory
{
    public static function create(DefinitionInterface $definition): GeneratorInterface
    {
        return match($definition->type()) {
            'object' => new ObjectGenerator(new ObjectTypeDefinition($definition)),
            'array' => new ArrayGenerator(new ArrayTypeDefinition($definition)),
            default => throw new \RuntimeException("Unexpected definition type '{$definition->type()}'")
        };
    }
}
