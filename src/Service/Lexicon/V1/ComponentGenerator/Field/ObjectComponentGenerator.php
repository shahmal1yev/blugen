<?php

namespace Blugen\Service\Lexicon\V1\ComponentGenerator\Field;

use Blugen\Service\Lexicon\GeneratorInterface;
use Blugen\Service\Lexicon\V1\Exceptions\MissingRequiredFieldException;
use Blugen\Service\Lexicon\V1\Factory\ComponentGeneratorFactory;
use Blugen\Service\Lexicon\V1\Property;
use Blugen\Service\Lexicon\V1\Schema;
use Blugen\Service\Lexicon\V1\Schema\Container\ObjectSchema;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Literal;

class ObjectComponentGenerator implements GeneratorInterface
{
    public function __construct(
        private readonly ClassType $class,
        private readonly Property $property,
        private readonly ?GeneratorInterface $context = null,
    ) {}

    public function generate(): void
    {
        $anonClass = new ClassType(null);
        $objectSchema = new ObjectSchema($this->property->schema());

        try {
            $properties = $objectSchema->properties();
        } catch (MissingRequiredFieldException) {
            $properties = [];
        }

        $nullable = $objectSchema->nullable() ?? [];
        $required = $objectSchema->required() ?? [];

        foreach ($properties as $childPropertyName => $childProperty) {
            $childProperty = new Property(
                $childPropertyName,
                new Schema($childProperty),
                in_array($childPropertyName, $nullable, true),
                in_array($childPropertyName, $required, true),
            );

            ComponentGeneratorFactory::create($anonClass, $childProperty)->generate();
        }

        try {
            $constructor = $this->class->getMethod('__construct');
        } catch (\Nette\InvalidArgumentException) {
            $constructor = $this->class->addMethod('__construct')->setPublic();
        }

        $constructor->setBody(
            $constructor->getBody() .
            new Literal("\n\$this->{$this->property->name()} = new class $anonClass;")
        );

        $this->class->addProperty($this->property->name())
            ->setPrivate()
            ->setType('object');

        $this->class->addMethod(sprintf("get%s", ucfirst($this->property->name())))
            ->setReturnType('object')
            ->setPublic()
            ->setBody("return \$this->{$this->property->name()};");
    }
}
