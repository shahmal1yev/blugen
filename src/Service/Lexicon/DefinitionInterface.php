<?php

namespace Blugen\Service\Lexicon;

interface DefinitionInterface extends SchemaInterface
{
    public function name(): string;
    public function type(): string;
    public function  description(): ?string;
    public function lexicon(): LexiconInterface;
    public function __get(string $name): mixed;
//    public function typeSpecificDef(): TypeSpecificDefInterface;
}
