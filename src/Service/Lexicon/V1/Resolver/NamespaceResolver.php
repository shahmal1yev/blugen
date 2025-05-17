<?php

namespace Blugen\Service\Lexicon\V1\Resolver;

use Blugen\Enum\PrimaryTypeEnum;
use Blugen\Service\Lexicon\DefinitionInterface;
use Blugen\Service\Lexicon\LexiconInterface;
use Blugen\Service\Lexicon\NamespaceResolverInterface;

use function \toPascalCase;

class NamespaceResolver
{
    /**
     * Return the full namespace and class name derived from NSID and definition.
     *
     * @return array{0: string, 1: string} [$namespace, $className]
     */
    public static function namespace(LexiconInterface $lexicon, DefinitionInterface $definition): array
    {
        self::assertDefinitionExists($lexicon, $definition);

        $namespaceParts = array_map('ucwords', explode('.', $lexicon->nsid()));
        $definitionName = toPascalCase($definition->name());

        // If definition type is primary, override class name with last part of NSID
        if (in_array($definition->type(), PrimaryTypeEnum::values(), true)) {
            $definitionName = array_pop($namespaceParts);
        }

        $namespace = implode('\\', $namespaceParts);

        return [$namespace, $definitionName];
    }

    /**
     * Return the filesystem path where this definition class should be written.
     */
    public static function path(LexiconInterface $lexicon, DefinitionInterface $definition): string
    {
        self::assertDefinitionExists($lexicon, $definition);

        [$namespace, $className] = self::namespace($lexicon, $definition);

        return str_replace('\\', DIRECTORY_SEPARATOR, $namespace)
            . DIRECTORY_SEPARATOR
            . $className;
    }

    /**
     * Ensure the definition actually exists inside the lexicon.
     */
    private static function assertDefinitionExists(LexiconInterface $lexicon, DefinitionInterface $definition): void
    {
        $name = $definition->name();

        if (!isset($lexicon->defs()[$name])) {
            throw new \InvalidArgumentException("Definition \"$name\" does not exist in the lexicon.");
        }
    }
}
