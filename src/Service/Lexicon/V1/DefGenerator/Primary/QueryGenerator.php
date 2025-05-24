<?php

namespace Blugen\Service\Lexicon\V1\DefGenerator\Primary;

use Blugen\Service\Lexicon\GeneratorInterface;
use Blugen\Service\Lexicon\QueryInterface;
use Blugen\Service\Lexicon\V1\Factory\ComponentGeneratorFactory;
use Blugen\Service\Lexicon\V1\Resolver\NamespaceResolver;
use Blugen\Service\Lexicon\V1\TypeSpecificDefinition\Field\ObjectTypeDefinition;
use Blugen\Service\Lexicon\V1\TypeSpecificDefinition\Primary\QueryTypeDefinition;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;

class QueryGenerator implements GeneratorInterface
{
    private readonly PhpFile $file;
    private readonly PhpNamespace $namespace;
    private readonly ClassType $class;

    public function __construct(
        private readonly QueryTypeDefinition $definition
    )
    {
        [$namespaceString, $className] = NamespaceResolver::namespace($this->definition->lexicon(), $this->definition);

        $this->file = new PhpFile();
        $this->namespace = $this->file->addNamespace($namespaceString);
        $this->class = $this->namespace->addClass($className);

        $this->file->setStrictTypes();
    }

    public function generate(): string
    {
        $this->class->addImplement(QueryInterface::class);

        foreach ($this->definition->parameters()?->properties() ?? [] as $property) {
            ComponentGeneratorFactory::create($this->class, $property)->generate();
        }

        return $this->file->__toString();
    }
}
