<?php

namespace Blugen\Service\Lexicon\V1;

use Blugen\Service\Lexicon\ReaderInterface;

class Reader implements ReaderInterface
{
    private array $paths;

    public function __construct(private readonly ?string $path = null)
    {
    }

    public function read(?string $path = null): static
    {
        $path ??= $this->path;
        $this->paths = $this->flatPath($path);

        return $this;
    }

    public function get(): array
    {
        return $this->paths;
    }

    private function flatPath(?string $path): array
    {
        $resolved = [];

        if ($path && ! is_readable($path)) {
            throw new \RuntimeException("Path '$path' is not readable.");
        }

        if (is_file($path)) {
            $resolved[] = $path;
        }

        if (is_dir($path)) {
            foreach (scandir($path) as $file) {
                $resolved = array_merge($resolved, $this->flatPath(
                    rtrim($path, DIRECTORY_SEPARATOR)
                    . DIRECTORY_SEPARATOR
                    . $file
                ));
            }
        }

        return array_filter($resolved, fn (string $path) => ! in_array($path, ['.', '..'], true));
    }
}
