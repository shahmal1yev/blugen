<?php

namespace Blugen\Service\Lexicon\V1;

use Blugen\Service\Lexicon\V1\TypeSpecificDefinition\Field\StringTypeDefinition;

class Nsid
{
    private readonly string $id;
    private readonly ?string $fragment;
    public function __construct(string $nsid)
    {
        $parts = explode('#', $nsid, 2);

        $this->id = $parts[0];
        $this->fragment = $parts[1] ?? null;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function fragment(): ?string
    {
        return $this->fragment;
    }

    public function full(): string
    {
        $full = $this->id;

        if ($this->fragment !== null) {
            $full .= "#$this->fragment";
        }

        return $full;
    }

    public function __toString(): string
    {
        return $this->full();
    }
}
