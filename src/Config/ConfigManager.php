<?php

namespace Blugen\Config;

/**
 */
class ConfigManager
{
    private array $config = [];

    /**
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     */
    public function all(): array
    {
        return $this->config;
    }

    /**
     *
     * @param string $key
     * @param mixed $default
     */
    public function get(string $key, $default = null)
    {
        $parts = explode('.', $key);
        $value = $this->config;

        foreach ($parts as $part) {
            if (!is_array($value) || !array_key_exists($part, $value)) {
                return $default;
            }
            $value = $value[$part];
        }

        return $value;
    }

    /**
     */
    public function has(string $key): bool
    {
        $parts = explode('.', $key);
        $value = $this->config;

        foreach ($parts as $part) {
            if (!is_array($value) || !array_key_exists($part, $value)) {
                return false;
            }
            $value = $value[$part];
        }

        return true;
    }

    /**
     */
    public function set(string $key, $value): void
    {
        $parts = explode('.', $key);
        $config = &$this->config;

        foreach ($parts as $i => $part) {
            if ($i === count($parts) - 1) {
                $config[$part] = $value;
                break;
            }

            if (!isset($config[$part]) || !is_array($config[$part])) {
                $config[$part] = [];
            }

            $config = &$config[$part];
        }
    }

    /**
     */
    public static function loadFile(string $filePath): array
    {
        if (!file_exists($filePath)) {
            return [];
        }

        return require $filePath;
    }

    /**
     */
    public static function load(): self
    {
        $defaultConfig = self::loadDefaultConfig();
        $userConfig = self::loadUserConfig();

        $mergedConfig = array_replace_recursive($defaultConfig, $userConfig);

        return new self($mergedConfig);
    }

    /**
     */
    private static function loadDefaultConfig(): array
    {
        $defaultConfigFile = dirname(__DIR__, 2) . '/config/codegen.php';
        return file_exists($defaultConfigFile) ? require $defaultConfigFile : [];
    }

    /**
     */
    private static function loadUserConfig(): array
    {
        $possiblePaths = [
            getcwd() . '/config/codegen.php',
            getcwd() . '/config/blugen/codegen.php',

            getcwd() . '/config/packages/codegen.php',
            getcwd() . '/config/packages/blugen/codegen.php',
        ];

        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                return require $path;
            }
        }

        return [];
    }
}
