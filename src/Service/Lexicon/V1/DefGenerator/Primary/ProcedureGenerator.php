<?php

namespace Blugen\Service\Lexicon\V1\DefGenerator\Primary;

use Blugen\Service\Lexicon\GeneratorInterface;
use Blugen\Service\Lexicon\ProcedureInterface;
use Blugen\Service\Lexicon\V1\ComponentGenerator\Field\RefComponentGenerator;
use Blugen\Service\Lexicon\V1\Factory\ComponentGeneratorFactory;
use Blugen\Service\Lexicon\V1\Resolver\NamespaceResolver;
use Blugen\Service\Lexicon\V1\Resolver\NsidResolver;
use Blugen\Service\Lexicon\V1\TypeSpecificDefinition\Primary\ProcedureTypeDefinition;
use Blugen\Service\Lexicon\V1\TypeSpecificSchema\Field\ObjectSchema;
use Blugen\Service\Lexicon\V1\TypeSpecificSchema\Field\RefSchema;
use Blugen\Service\Lexicon\V1\TypeSpecificSchema\Field\UnionSchema;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Literal;
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

        $schemaProperty = $this->class->addProperty("schema")
            ->setType('object')
            ->setPrivate();

        $setSchemaMethod = $this->class->addMethod("setSchema")
            ->setPublic()
            ->setReturnType('object');

        $getSchemaMethod = $this->class->addMethod("getSchema")
            ->setPublic()
            ->setReturnType('object');

        $schema = $this->definition->input()?->schema();

        if ($schema instanceof ObjectSchema) {
            $anonClass = new ClassType();

            foreach($schema?->properties() as $property) {
                $generator = ComponentGeneratorFactory::create($anonClass, $property);

                if ($generator instanceof RefComponentGenerator) {
                    $a = 12;
                }

                $generator->generate();
            }

            $setSchemaMethod->addBody(new Literal("return \$this->schema = new class $anonClass;"));
            $getSchemaMethod->addBody(new Literal("return \$this->schema;"));

            return $this->file->__toString();
        }

        $refs = [];

        if ($schema instanceof RefSchema) {
            $refs[] = $schema->ref();
        }

        if ($schema instanceof UnionSchema) {
            $refs = array_merge($refs, $schema->refs());
        }

        $refs = array_map(function ($ref) {
            $ref = NsidResolver::namespace($ref);

            $this->file->addUse($ref);

            return $ref;
        }, $refs);

        $setSchemaMethod->setReturnType("self")
            ->addBody(new Literal("\$this->schame = \$schema;\nreturn \$this;"))
            ->addParameter("schema")
            ->setType(implode("|", $refs));

        $getSchemaMethod->addBody(new Literal("return \$this->schame;"))
            ->setReturnType(implode("|", $refs));

        $schemaProperty->setType(implode("|", $refs));

        return $this->file->__toString();
    }
}
