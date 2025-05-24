<?php

namespace Blugen\Service\Lexicon\V1\DefGenerator\Primary;

use Blugen\Service\Lexicon\GeneratorInterface;
use Blugen\Service\Lexicon\QueryInterface;
use Blugen\Service\Lexicon\V1\Factory\ComponentGeneratorFactory;
use Blugen\Service\Lexicon\V1\Resolver\NamespaceResolver;
use Blugen\Service\Lexicon\V1\TypeSpecificDefinition\Field\ObjectTypeDefinition;
use Blugen\Service\Lexicon\V1\TypeSpecificDefinition\Primary\QueryTypeDefinition;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Literal;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;

class QueryGenerator implements GeneratorInterface
{
    private readonly PhpFile $file;
    private readonly PhpNamespace $namespace;
    private readonly ClassType $class;
    private readonly string $namespaceString;
    private readonly string $className;

    public function __construct(
        private readonly QueryTypeDefinition $definition
    )
    {
        [$this->namespaceString, $this->className] = NamespaceResolver::namespace($this->definition->lexicon(), $this->definition);

        $this->file = new PhpFile();
        $this->namespace = $this->file->addNamespace($this->namespaceString);
        $this->class = $this->namespace->addClass($this->className);

        $this->file->setStrictTypes();
    }

    public function generate(): array
    {
        $result = [];

        $this->class->addImplement(QueryInterface::class);

        $paramsFile = new PhpFile();
        $paramsFile->setStrictTypes();
        $paramsPhpNamespace = $paramsFile->addNamespace($this->namespaceString);
        $paramsClassName = "{$this->className}Params";
        $paramsNamespace = sprintf("%s\\%s", $paramsPhpNamespace->getName(), $paramsClassName);
        $paramsClass = $paramsPhpNamespace->addClass($paramsClassName);


        foreach ($this->definition->parameters()?->properties() ?? [] as $property) {
            ComponentGeneratorFactory::create($paramsClass, $property)->generate();
        }

        $this->class->addProperty(new Literal("params"))
            ->setPrivate()
            ->setType($paramsNamespace);

        $this->class->addMethod(new Literal("setParams"))
            ->setPublic()
            ->setReturnType(new Literal("self"))
            ->addBody(new Literal("\$this->params = \$params;\nreturn \$this;"))
            ->addParameter(new Literal("params"))
            ->setType($paramsNamespace);

        $this->class->addMethod(new Literal("getParams"))
            ->setPublic()
            ->setReturnType($paramsNamespace)
            ->addBody(new Literal("return \$this->params;"));

        $this->namespace->addUse($paramsNamespace);

        return [
            "$this->className.php" => $this->file->__toString(),
            "$paramsClassName.php" => $paramsFile->__toString(),
        ];
    }
}
