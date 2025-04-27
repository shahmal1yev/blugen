<?php

namespace Blugen\Service\Lexicon\V1\ComponentGenerator\Field;

use Blugen\Service\Lexicon\GeneratorInterface;
use Blugen\Service\Lexicon\V1\Property;
use Blugen\Service\Lexicon\V1\TypeSpecificSchema\Field\BytesSchema;
use Nette\PhpGenerator\ClassType;

class BytesComponentGenerator implements GeneratorInterface
{
    public function __construct(
        private readonly ClassType $class,
        private readonly Property $property
    ) {}

    public function generate(): void
    {
        $this->addProperty();
        $this->addGetter();
        $this->addSetter();
    }

    private function addProperty(): void
    {
        $this->class->addProperty($this->property->name())
            ->setPrivate()
            ->setType('string')
            ->setNullable($this->property->isNullable())
            ->setComment('@var string|null');
    }

    private function addGetter(): void
    {
        $name = $this->property->name();
        $methodName = 'get' . ucfirst($name);

        $this->class->addMethod($methodName)
            ->setPublic()
            ->setReturnType('string')
            ->setReturnNullable(true)
            ->setBody("return \$this->{$name};")
            ->setComment("@return string|null");
    }

    private function addSetter(): void
    {
        $name = $this->property->name();
        $methodName = 'set' . ucfirst($name);

        $schema = new BytesSchema($this->property->schema());

        $body = array_merge(
            $this->generateMinLengthCheck($name, $schema),
            $this->generateMaxLengthCheck($name, $schema),
            [
                "\$this->{$name} = \${$name};",
                "return \$this;"
            ]
        );

        $this->class->addMethod($methodName)
            ->setPublic()
            ->setReturnType('self')
            ->setBody(implode("\n", $body))
            ->setComment(implode("\n", [
                "Set the value of `{$name}`.",
                "",
                "@param string \${$name}",
                "@return self"
            ]))
            ->addParameter($name)->setType('string');
    }

    private function generateMinLengthCheck(string $name, BytesSchema $schema): array
    {
        $min = $schema->minLength();
        if ($min === null) {
            return [];
        }

        return [
            "if (strlen(\${$name}) < {$min}) {",
            "    throw new \\InvalidArgumentException('The value of `{$name}` must be at least {$min} bytes.');",
            "}"
        ];
    }

    private function generateMaxLengthCheck(string $name, BytesSchema $schema): array
    {
        $max = $schema->maxLength();
        if ($max === null) {
            return [];
        }

        return [
            "if (strlen(\${$name}) > {$max}) {",
            "    throw new \\InvalidArgumentException('The value of `{$name}` must not exceed {$max} bytes.');",
            "}"
        ];
    }
}
