<?php

namespace Blugen\Service\Lexicon\V1\Factory;

use Blugen\Service\Lexicon\DefinitionInterface;
use Blugen\Service\Lexicon\GeneratorInterface;
use Blugen\Service\Lexicon\V1\DefGenerator\Field\ArrayGenerator;
use Blugen\Service\Lexicon\V1\DefGenerator\Field\ObjectGenerator;
use Blugen\Service\Lexicon\V1\DefGenerator\Field\StringGenerator;
use Blugen\Service\Lexicon\V1\DefGenerator\Primary\ProcedureGenerator;
use Blugen\Service\Lexicon\V1\DefGenerator\Primary\QueryGenerator;
use Blugen\Service\Lexicon\V1\DefGenerator\Primary\RecordGenerator;
use Blugen\Service\Lexicon\V1\DefGenerator\Primary\SubscriptionGenerator;
use Blugen\Service\Lexicon\V1\TypeSpecificDefinition\Field\ArrayTypeDefinition;
use Blugen\Service\Lexicon\V1\TypeSpecificDefinition\Field\ObjectTypeDefinition;
use Blugen\Service\Lexicon\V1\TypeSpecificDefinition\Field\StringTypeDefinition;
use Blugen\Service\Lexicon\V1\TypeSpecificDefinition\Primary\ProcedureTypeDefinition;
use Blugen\Service\Lexicon\V1\TypeSpecificDefinition\Primary\QueryTypeDefinition;
use Blugen\Service\Lexicon\V1\TypeSpecificDefinition\Primary\RecordTypeDefinition;
use Blugen\Service\Lexicon\V1\TypeSpecificDefinition\Primary\SubscriptionTypeDefinition;

class DefGeneratorFactory
{
    public static function create(DefinitionInterface $definition): ?GeneratorInterface
    {
        return match($definition->type()) {
            'object' => new ObjectGenerator(new ObjectTypeDefinition($definition)),
            'array' => new ArrayGenerator(new ArrayTypeDefinition($definition)),
            'record' => new RecordGenerator(new RecordTypeDefinition($definition)),
            'query' => new QueryGenerator(new QueryTypeDefinition($definition)),
            'procedure' => new ProcedureGenerator(new ProcedureTypeDefinition($definition)),
            'subscription' => new SubscriptionGenerator(new SubscriptionTypeDefinition($definition)),
            'string' => new StringGenerator(new StringTypeDefinition($definition)),
            'token' => null,
            default => throw new \RuntimeException("Unexpected definition type '{$definition->type()}'")
        };
    }
}
