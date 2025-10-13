<?php

namespace Blugen\Service\Lexicon\V1\DefGenerator\Primary;

use Blugen\Enum\ClassNameSuffix;
use Blugen\Service\Lexicon\ArraySerialization\ArraySerializable;
use Blugen\Service\Lexicon\ArraySerialization\ArraySerializationContext;
use Blugen\Service\Lexicon\ArraySerialization\BuildsArraySerialization;
use Blugen\Service\Lexicon\GeneratorInterface;
use Blugen\Service\Lexicon\V1\Exceptions\MissingRequiredFieldException;
use Blugen\Service\Lexicon\V1\Factory\ComponentGeneratorFactory;
use Blugen\Service\Lexicon\V1\Property;
use Blugen\Service\Lexicon\V1\Resolver\NamespaceResolver;
use Blugen\Service\Lexicon\V1\Schema;
use Blugen\Service\Lexicon\V1\TypeSpecificDefinition\Primary\RecordTypeDefinition;
use JsonSerializable;
use Nette\InvalidArgumentException;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;

class RecordGenerator implements GeneratorInterface, ArraySerializationContext
{
    use BuildsArraySerialization;

    private readonly PhpFile $file;
    private readonly PhpNamespace $namespace;
    private readonly ClassType $class;

    public function __construct(
        private readonly RecordTypeDefinition $definition,
    )
    {
        [$namespaceString, $className] = NamespaceResolver::namespace($this->definition->lexicon(), $this->definition);

        $this->file = new PhpFile();
        $this->namespace = $this->file->addNamespace($namespaceString);
        try {
            $this->class = $this->namespace->addClass($className);
        } catch (InvalidArgumentException $e) {
            if (! str_contains($e->getMessage(), "is not valid class name.")) {
                throw $e;
            }

            $this->class = $this->namespace->addClass("{$className}" . ClassNameSuffix::DEFINITION->value);
        }
        $this->file->setStrictTypes();
    }

    public function generate(): string
    {
        $this->class->addImplement(ArraySerializable::class)
            ->addImplement(JsonSerializable::class);

        try {
            $properties = $this->definition->record()->properties();
        } catch (MissingRequiredFieldException) {
            $properties = [];
        }

        $nullable = $this->definition->record()->nullable() ?? [];
        $required = $this->definition->record()->required() ?? [];

        foreach ($properties as $propertyName => $property) {
            $property = new Property(
                $propertyName,
                new Schema($property->toArray()),
                in_array($propertyName, $nullable, true),
                in_array($propertyName, $required, true)
            );
            ComponentGeneratorFactory::create($this->class, $property, $this->definition->lexicon(), $this)->generate();
        }

        $this->class->addMethod('toArray')
            ->setReturnType('array')
            ->setBody($this->generateBody());

        $this->class->addMethod('jsonSerialize')
            ->setReturnType('array')
            ->setBody('return $this->toArray();');

        return $this->file->__toString();
    }
}
