<?php

namespace Blugen\Service\Lexicon;

interface LexiconInterface
{
    public function nsid(): string;
    public function version(): int;
    public function description(): ?string;
    public function defs(): array;
}
