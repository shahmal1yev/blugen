<?php

namespace Blugen\Service\Lexicon\V1;

use Blugen\Config\ConfigManager;
use Blugen\Service\Lexicon\ReaderInterface;
use RuntimeException;

class Reader implements ReaderInterface
{
    private array $paths = [];
    private readonly ConfigManager $config;
    private readonly ?string $path;

    public function __construct(
        ?string $path = null,
        ?ConfigManager $config = null,
    ) {
        $this->config = $config ?? container()->get(ConfigManager::class);
        $this->path = (string) ($path ?? $this->config->get('lexicons.source'));
    }

    public function read(?string $path = null): static
    {
        $targetPath = $path ?? $this->path;

        if ($targetPath === null) {
            throw new RuntimeException("No path provided to read.");
        }

        if (!is_readable($targetPath)) {
            throw new RuntimeException("Path '$targetPath' is not readable.");
        }

        $this->paths = $this->resolvePaths($targetPath);

        return $this;
    }

    public function get(): array
    {
        return $this->paths;
    }

    private function resolvePaths(string $path): array
    {
        if (is_file($path)) {
            return [$path];
        }

        if (is_dir($path)) {
            $entries = array_diff(scandir($path) ?: [], ['.', '..']);
            $files = [];

            foreach ($entries as $entry) {
                $fullPath = $path . DIRECTORY_SEPARATOR . $entry;
                $files = [...$files, ...$this->resolvePaths($fullPath)];
            }

            return $files;
        }

        // Unexpected case: neither file nor directory
        return [];
    }
}
