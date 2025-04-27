<?php

namespace Blugen\Service\Lexicon\V1\ComponentGenerator\Field;

use Blugen\Service\Lexicon\GeneratorInterface;
use Blugen\Service\Lexicon\V1\Property;
use Blugen\Service\Lexicon\V1\Resolver\NsidResolver;
use Blugen\Service\Lexicon\V1\TypeSpecificSchema\Field\RefSchema;
use Nette\PhpGenerator\ClassType;

class RefComponentGenerator implements GeneratorInterface
{
    private readonly RefSchema $schema;

    public function __construct(
        private readonly ClassType $class,
        private readonly Property $property,
    ) {
        $this->schema = new RefSchema($this->property->schema());
    }

    public function generate(): void
    {
        $this->generateProperty();
        $this->generateGetter();
        $this->generateSetter();
    }

    private function generateProperty(): void
    {
        $type = $this->phpType();
        $doc = $this->docType();

        $this->class->getNamespace()
            ->addUse(trim($type, '?'));

        $this->class->addProperty($this->property->name())
            ->setPrivate()
            ->setType($type)
            ->setComment("@var {$doc}" . $this->description());
    }

    private function generateGetter(): void
    {
        $name = $this->property->name();

        $this->class->addMethod('get' . ucfirst($name))
            ->setPublic()
            ->setReturnType($this->phpType())
            ->setBody("return \$this->{$name};")
            ->setComment("Get the value of \${$name}.\n\n@return {$this->docType()}");
    }

    private function generateSetter(): void
    {
        $name = $this->property->name();

        $this->class->addMethod('set' . ucfirst($name))
            ->setComment("Set the value of \${$name}.\n\n@param {$this->docType()} \${$name}\n@return self")
            ->setPublic()
            ->setReturnType('self')
            ->setBody("\$this->{$name} = \${$name};\nreturn \$this;")
            ->addParameter($name)
            ->setType($this->phpType());
    }

    private function phpType(): string
    {
        $resolved = NsidResolver::namespace($this->schema->ref());
        return $this->property->isRequired() ? $resolved : '?' . $resolved;
    }

    private function docType(): string
    {
        $resolved = NsidResolver::namespace($this->schema->ref());
        return $this->property->isRequired() ? $resolved : "{$resolved}|null";
    }

    private function description(): string
    {
        return $this->property->description()
            ? "\n\n" . $this->property->description()
            : '';
    }
}
