<?php

namespace Blugen;

use Composer\Autoload\ClassLoader;
use Symfony\Component\Filesystem\Filesystem;

class Lexicon
{
    public function __construct(private readonly array $lexicon = [])
    {
    }

    public function version(): int
    {
        return $this->lexicon['lexicon'];
    }

    public function nsid(): string
    {
        return $this->lexicon['id'];
    }

    public function description(): ?string
    {
        return $this->lexicon['description'] ?? null;
    }

    public function definitions(): array
    {
        return $this->lexicon['defs'] ?? [];
    }
}
