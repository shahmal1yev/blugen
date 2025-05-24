<?php

namespace Blugen\Service\Lexicon;

interface ReaderInterface
{
    public function read(string $path): static;
    public function get(): array;
}
