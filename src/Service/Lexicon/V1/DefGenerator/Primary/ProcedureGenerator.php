<?php

namespace Blugen\Service\Lexicon\V1\DefGenerator\Primary;

use Blugen\Service\Lexicon\GeneratorInterface;
use Blugen\Service\Lexicon\ProcedureInterface;
use Blugen\Service\Lexicon\V1\Factory\ComponentGeneratorFactory;
use Blugen\Service\Lexicon\V1\Resolver\NamespaceResolver;
use Blugen\Service\Lexicon\V1\TypeSpecificDefinition\Primary\ProcedureTypeDefinition;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;

class ProcedureGenerator implements GeneratorInterface
{
    private readonly PhpFile $file;
    private readonly PhpNamespace $namespace;
    private readonly ClassType $class;

    public function __construct(private readonly ProcedureTypeDefinition $definition)
    {
        [$namespaceString, $className] = NamespaceResolver::namespace($this->definition->lexicon(), $this->definition);

        $this->file = new PhpFile();
        $this->namespace = $this->file->addNamespace($namespaceString);
        $this->class = $this->namespace->addClass($className);

        $this->file->setStrictTypes();
    }

    public function generate(): string
    {
        $this->class->addImplement(ProcedureInterface::class);

        foreach($this->definition->input()?->schema()?->properties() as $property) {
            ComponentGeneratorFactory::create($this->class, $property)->generate();
        }

        return $this->file->__toString();
    }
}
