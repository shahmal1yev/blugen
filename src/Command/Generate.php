<?php

namespace Blugen\Command;

use Blugen\Enum\PrimaryTypeEnum;
use Composer\Autoload\ClassLoader;
use Nette\InvalidArgumentException;
use Nette\PhpGenerator\PhpFile;
use Blugen\Lexicon;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    'lexicons:generate',
    "Generate based on ATProto lexicons."
)]
class Generate extends Command
{
    private readonly ClassLoader $classLoader;
    private readonly Filesystem $filesystem;
    private const ERROR_PATH_NOT_EXIST     = "The path '%s' does not exist.";
    private const ERROR_PATH_NOT_READABLE  = "The path '%s' is not readable.";
    private const ERROR_INVALID_PREFIX     = "Prefix '%s' is not registered in PSR-4 autoload.";
    private const ERROR_INVALID_CLASSNAME  = "is not valid class name.";

    public function __construct()
    {
        parent::__construct();

        $this->classLoader = container()->get(ClassLoader::class);
        $this->filesystem = new Filesystem();
    }

    protected function configure(): void
    {
        $this->addArgument('source', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $paths = $this->validated(
            $this->flat($this->source($input))
        );

        foreach ($paths as $filePath) {
            $lexicon = new Lexicon(json_decode(file_get_contents($filePath), true));

            $prefix = "Blugen\\Lexicons\\";
            foreach ($this->classNamespaces($lexicon, $prefix) as $classFqcn) {
                $file = new PhpFile();
                $file->setStrictTypes();

                $namespaceParts = explode("\\", $classFqcn);
                $className = array_pop($namespaceParts);
                $namespace = implode("\\", $namespaceParts);
                $phpNamespace = $file->addNamespace($namespace);

                $path = $this->classPath($classFqcn, $prefix);

                try {
                    $phpNamespace->addClass($className);
                } catch (InvalidArgumentException $e) {
                    if (str_contains($e->getMessage(), self::ERROR_INVALID_CLASSNAME)) {
                        $className .= 'Definition';
                        $phpNamespace->addClass($className);
                        $path = preg_replace('/\.php$/', 'Definition.php', $path);
                    } else {
                        throw $e;
                    }
                }

                file_put_contents($path, $file);
            }
        }

        return Command::SUCCESS;
    }

    private function source(InputInterface $input): array
    {
        $path = $input->getArgument('source');

        if (!file_exists($path)) {
            throw new \InvalidArgumentException(sprintf(self::ERROR_PATH_NOT_EXIST, $path));
        }

        if (!is_readable($path)) {
            throw new \InvalidArgumentException(sprintf(self::ERROR_PATH_NOT_READABLE, $path));
        }

        return [$path];
    }

    private function flat(array $paths): array
    {
        $resolved = [];

        foreach ($paths as $path) {
            if (is_file($path)) {
                $resolved[] = $path;
                continue;
            }

            if (is_dir($path)) {
                foreach (scandir($path) as $item) {
                    if (in_array($item, ['.', '..'])) {
                        continue;
                    }
                    $resolved = array_merge($resolved, $this->flat([
                        rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $item
                    ]));
                }
            }
        }

        return $resolved;
    }

    private function validated(array $paths): array
    {
        return array_filter($paths, fn (string $file) =>
            str_ends_with($file, '.json') &&
            json_validate(file_get_contents($file))
        );
    }

    private function classNamespaces(Lexicon $lexicon, ?string $prefix = null): array
    {
        $nsidParts = explode('.', $lexicon->nsid());
        $baseNamespace = $prefix . implode("\\", array_map('ucfirst', $nsidParts));

        $definitions = $lexicon->definitions();
        $primaryTypes = array_map('strtolower', PrimaryTypeEnum::names());

        $namespaces = [];

        foreach ($definitions as $key => $definition) {
            $type = $definition['type'] ?? '';
            $namespace = $baseNamespace;

            if (!in_array(strtolower($type), $primaryTypes, true)) {
                $namespace .= "\\" . ucfirst($key);
            }

            $namespaces[] = $namespace;
        }

        return $namespaces;
    }

    public function classPath(string $namespace, ?string $prefix = null, bool $create = true): string
    {
        $prefixPath = $prefix ? $this->prefixPath($prefix) : null;

        $normalize = fn(string $ns) => implode(DIRECTORY_SEPARATOR, explode("\\", $ns));
        $prefixNs = $prefix ? $normalize($prefix) : '';
        $normalizedNamespace = $normalize($namespace);

        if ($prefixPath) {
            $prefixPath = rtrim($prefixPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            $relativePath = str_replace($prefixNs, '', $normalizedNamespace);
        } else {
            $relativePath = ltrim($normalizedNamespace, DIRECTORY_SEPARATOR);
        }

        $path = $prefixPath
            . ltrim($relativePath, DIRECTORY_SEPARATOR)
            . '.php';

        if ($create && !$this->filesystem->exists($path)) {
            $this->filesystem->mkdir(dirname($path), 0775);
        }

        return $path;
    }

    private function prefixPath(string $prefix): string
    {
        $this->validatePrefix($prefix);
        return current($this->classLoader->getPrefixesPsr4()[$prefix]);
    }

    private function validatePrefix(string $prefix): void
    {
        if (!isset($this->classLoader->getPrefixesPsr4()[$prefix])) {
            throw new \RuntimeException(sprintf(self::ERROR_INVALID_PREFIX, $prefix));
        }
    }
}
