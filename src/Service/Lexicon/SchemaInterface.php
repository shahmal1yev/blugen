<?php

namespace Blugen\Service\Lexicon;

interface SchemaInterface
{
    public function type(): string;
    public function description(): ?string;
    public function __get(string $name): mixed;
}
