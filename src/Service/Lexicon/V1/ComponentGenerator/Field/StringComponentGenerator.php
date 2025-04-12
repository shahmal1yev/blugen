<?php

namespace Blugen\Service\Lexicon\V1\ComponentGenerator\Field;

use Blugen\Service\Lexicon\GeneratorInterface;
use Blugen\Service\Lexicon\V1\Property;
use Nette\PhpGenerator\ClassType;

class StringComponentGenerator implements GeneratorInterface
{
    public function __construct(
        private readonly ClassType $class,
        private readonly Property $property
    ) {}

    public function generate(): void
    {
        $phpType = $this->type();
        $docType = $this->docType();
        $name = $this->property->name();

        $this->class->addProperty($name)
            ->setPrivate()
            ->setType($phpType)
            ->setComment("@var {$docType}" . $this->description());

        $this->class->addMethod('get' . ucfirst($name))
            ->setPublic()
            ->setReturnType($phpType)
            ->setBody("return \$this->{$name};")
            ->setComment("Get the value of \${$name}.\n\n@return {$docType}");

        $this->class->addMethod('set' . ucfirst($name))
            ->setPublic()
            ->setComment("Set the value of \${$name}.\n\n@param {$docType} \${$name}\n@return self")
            ->setReturnType('self')
            ->setBody("\$this->{$name} = \${$name};\nreturn \$this;")
            ->addParameter($name)
            ->setType($phpType);
    }

    private function type(): string
    {
        return $this->property->isNullable() ? '?string' : 'string';
    }

    private function docType(): string
    {
        return $this->property->isNullable() ? 'string|null' : 'string';
    }

    private function description(): string
    {
        return $this->property->description() ? "\n\n" . $this->property->description() : '';
    }
}
