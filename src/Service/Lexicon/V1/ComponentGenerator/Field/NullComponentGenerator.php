<?php

namespace Blugen\Service\Lexicon\V1\ComponentGenerator\Field;

use Blugen\Service\Lexicon\GeneratorInterface;
use Blugen\Service\Lexicon\V1\Property;
use Blugen\Service\Lexicon\V1\TypeSpecificSchema\FieldType\NullSchema;
use Nette\PhpGenerator\ClassType;

class NullComponentGenerator implements GeneratorInterface
{
    public function __construct(
        private readonly ClassType $class,
        private readonly Property $property
    ) {
    }

    public function generate(): void
    {
        $name = $this->property->name();

        $this->class->addProperty($name)
            ->setPrivate()
            ->setType('null')
            ->setComment("@var null" . $this->description());

        $this->class->addMethod('get' . ucfirst($name))
            ->setPublic()
            ->setReturnType('null')
            ->setBody("return null;")
            ->setComment("Always returns null.");

        $this->class->addMethod('set' . ucfirst($name))
            ->setPublic()
            ->setReturnType('self')
            ->setBody("return \$this;")
            ->addParameter($name)
            ->setType('null')
            ->setDefaultValue(null)
            ->setComment("@param null \${$name}\n@return self");
    }

    private function description(): string
    {
        return $this->property->description() ? "\n\n" . $this->property->description() : '';
    }
}
