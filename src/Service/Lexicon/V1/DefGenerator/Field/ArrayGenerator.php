<?php

namespace Blugen\Service\Lexicon\V1\DefGenerator\Field;

use Blugen\Service\Lexicon\GeneratorInterface;
use Blugen\Service\Lexicon\V1\Resolver\NamespaceResolver;
use Blugen\Service\Lexicon\V1\Schema;
use Blugen\Service\Lexicon\V1\Support\ArrayHandler;
use Blugen\Service\Lexicon\V1\TypeSpecificDefinition\Field\ArrayTypeDefinition;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;

class ArrayGenerator implements GeneratorInterface
{
    private readonly PhpFile $file;
    private readonly PhpNamespace $namespace;
    private readonly ClassType $class;

    public function __construct(
        private readonly ArrayTypeDefinition $definition
    ) {
        $fqcnParts = NamespaceResolver::namespace($this->definition->lexicon(), $this->definition);

        $this->file = new PhpFile();
        $this->file->setStrictTypes();

        $this->namespace = $this->file->addNamespace(current($fqcnParts));
        $this->namespace->addUse(\InvalidArgumentException::class);
        $this->namespace->addUse(ArrayHandler::class);

        $this->class = $this->namespace->addClass(next($fqcnParts));
        $this->class->addImplement('ArrayAccess');
        $this->class->addImplement('IteratorAggregate');
        $this->class->addImplement('Countable');
    }

    public function generate(): string
    {
        $this->class->addProperty('handler')
            ->setPrivate()
            ->setType(ArrayHandler::class);

        $this->generateConstructor();
        $this->generateItemValidator();
        $this->generateArrayDelegates();

        return $this->file->__toString();
    }

    private function generateConstructor(): void
    {
        $method = $this->class->addMethod('__construct')
            ->setPublic();

        $method->addParameter('items')
            ->setType('array')
            ->setDefaultValue([]);

        $method->setBody(<<<'BODY'
$this->handler = container()->get(\Blugen\Service\Lexicon\V1\Support\ArrayHandler::class)->init(
    $items, 
    fn(mixed $item) => $this->validateItem($item)
);
BODY
        );
    }

    private function generateItemValidator(): void
    {
        $itemsType = (new Schema($this->definition->items()))->type();

        $body = match ($itemsType) {
            'string' => <<<PHP
if (!is_string(\$item)) {
    throw new InvalidArgumentException("Each item must be a string.");
}
PHP,
            'integer' => <<<PHP
if (!is_int(\$item)) {
    throw new InvalidArgumentException("Each item must be an integer.");
}
PHP,
            'boolean' => <<<PHP
if (!is_bool(\$item)) {
    throw new InvalidArgumentException("Each item must be a boolean.");
}
PHP,
            default => <<<PHP
if (!is_object(\$item)) {
    throw new InvalidArgumentException("Each item must be an object.");
}
PHP
        };

        $this->class->addMethod('validateItem')
            ->setPrivate()
            ->setReturnType('void')
            ->setBody($body)
            ->addParameter('item')
            ->setType('mixed');
    }

    private function generateArrayDelegates(): void
    {
        $delegates = [
            'getItems' => ['array', 'return $this->handler->getItems();', []],
            'setItems' => ['void', '$this->handler->setItems($items);', [['items', 'array']]],
            'offsetExists' => ['bool', 'return $this->handler->offsetExists($offset);', [['offset', 'mixed']]],
            'offsetGet' => ['mixed', 'return $this->handler->offsetGet($offset);', [['offset', 'mixed']]],
            'offsetSet' => ['void', '$this->handler->offsetSet($offset, $value);', [['offset', 'mixed'], ['value', 'mixed']]],
            'offsetUnset' => ['void', '$this->handler->offsetUnset($offset);', [['offset', 'mixed']]],
            'getIterator' => ['Traversable', 'return $this->handler->getIterator();', []],
            'count' => ['int', 'return $this->handler->count();', []],
        ];

        foreach ($delegates as $name => [$returnType, $body, $params]) {
            $method = $this->class->addMethod($name)
                ->setPublic()
                ->setReturnType($returnType)
                ->setBody($body);

            foreach ($params as [$paramName, $paramType]) {
                $method->addParameter($paramName)->setType($paramType);
            }
        }
    }
}
