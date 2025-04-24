<?php

namespace Blugen\Service\Lexicon\V1\Resolver;

use Blugen\Enum\PrimaryTypeEnum;
use Blugen\Service\Lexicon\DefinitionInterface;
use Blugen\Service\Lexicon\LexiconInterface;

class NamespaceResolver
{
    public static function namespace(LexiconInterface $lexicon, DefinitionInterface $definition): array
    {
        self::validateDefExisting($lexicon, $definition);

        $namespaceParts = array_map('ucfirst', explode('.', $lexicon->nsid()));

        $className = ucfirst($definition->name());

        if (! in_array($definition->type(), PrimaryTypeEnum::values())) {
            $className = array_pop($namespaceParts);
        }

        $namespace = implode("\\", $namespaceParts);

        return [$namespace, $className];
    }

    public static function path(LexiconInterface $lexicon, DefinitionInterface $definition): string
    {
        self::validateDefExisting($lexicon, $definition);

        return array_reduce(
            explode('.', $lexicon->nsid()),
            static fn (?string $path, string $nsidPart) => $path .= $nsidPart . DIRECTORY_SEPARATOR,
        );
    }

    private static function validateDefExisting(LexiconInterface $lexicon, DefinitionInterface $definition): void
    {
        if (! isset($lexicon->defs()[$name = $definition->name()])) {
            throw new \InvalidArgumentException("Definition $name does not exist");
        }
    }
}
