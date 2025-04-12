<?php

namespace Blugen\Service\Lexicon\V1\ComponentGenerator;

use Blugen\Service\Lexicon\GeneratorInterface;

class BaseDecorator implements GeneratorInterface
{
    public function __construct(
        private readonly GeneratorInterface $generator
    )
    {}

    public function generate()
    {
        return $this->generator->generate();
    }
}
