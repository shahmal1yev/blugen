<?php

namespace Blugen\Service\Lexicon\V1\ComponentGenerator\Field;

use Blugen\Service\Lexicon\GeneratorInterface;
use Blugen\Service\Lexicon\V1\Property;
use Blugen\Service\Lexicon\V1\TypeSpecificSchema\Field\TokenSchema;
use Nette\PhpGenerator\ClassType;

class TokenComponentGenerator implements GeneratorInterface
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
            ->setType($this->phpType())
            ->setComment("@var {$this->docType()}" . $this->description() . "\n\n@token");

        $this->class->addMethod('get' . ucfirst($name))
            ->setPublic()
            ->setReturnType($this->phpType())
            ->setBody("return \$this->{$name};")
            ->setComment("Get token value.\n\n@return {$this->docType()}");

        $this->class->addMethod('set' . ucfirst($name))
            ->setPublic()
            ->setReturnType('self')
            ->setBody("\$this->{$name} = \${$name};\nreturn \$this;")
            ->setComment("@param {$this->docType()} \${$name}\n@return self")
            ->addParameter($name)
            ->setType($this->phpType());
    }

    private function phpType(): string
    {
        return $this->property->isRequired() ? 'string' : '?string';
    }

    private function docType(): string
    {
        return $this->property->isRequired() ? 'string' : 'string|null';
    }

    private function description(): string
    {
        return $this->property->description() ? "\n\n" . $this->property->description() : '';
    }
}
