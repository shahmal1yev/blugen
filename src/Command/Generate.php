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

#[AsCommand('lexicons:generate')]
class Generate extends Command
{
    private readonly ClassLoader $classLoader;
    private readonly Filesystem $filesystem;

    public function __construct()
    {
        parent::__construct();

        $this->classLoader = container()->get(ClassLoader::class);
        $this->filesystem = new Filesystem();
    }

    protected function configure(): void
    {
        $this->addArgument(
            'source',
            InputArgument::REQUIRED
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $source = $this->source($input);
        $paths = $this->flat($source);
        $validated = $this->validated($paths);

        foreach ($validated as $file) {
            $lexicon = new Lexicon(json_decode(file_get_contents($file), true));

            $prefix = "Blugen\\Lexicons\\";
            $classes = $this->classNamespaces($lexicon, $prefix);

            foreach($classes as $class) {
                $path = $this->classPath($class, $prefix);
                $namespaceParts = explode("\\", $class);

                $className = array_pop($namespaceParts);
                $namespaceString = implode("\\", $namespaceParts);

                $file = new PhpFile();

                $namespace = $file->addNamespace($namespaceString);

                try {
                    $namespace->addClass($className);
                } catch (InvalidArgumentException $e) {
                    if ($e->getMessage() === "Value '$className' is not valid class name.") {
                        $newClassName = "{$className}Definition";
                        $namespace->addClass($newClassName);
                        $path = str_replace("$className.php", "$newClassName.php", $path);
                    }
                }

                $file->setStrictTypes();

                file_put_contents($path, $file);
            }
        }

        return Command::SUCCESS;
    }

    private function source(InputInterface $input): array
    {
        $lexiconsPath = $input->getArgument('source');

        if (! file_exists($lexiconsPath)) {
            throw new \InvalidArgumentException("The path '$lexiconsPath' does not exist.");
        }

        if (! is_readable($lexiconsPath)) {
            throw new \InvalidArgumentException("The path '$lexiconsPath' is not readable.");
        }

        return [$lexiconsPath];
    }

    private function flat(array $paths): array
    {
        $resolved = [];

        foreach ($paths as $path) {
            if (is_file($path)) {
                $resolved[] = $path;
                continue;
            }

            if (is_dir($path) && is_readable($path)) {
                $items = scandir($path);

                foreach ($items as $item) {
                    if ($item === '.' || $item === '..') {
                        continue;
                    }

                    $fullPath = rtrim($path, DIRECTORY_SEPARATOR). DIRECTORY_SEPARATOR. $item;
                    $resolved = array_merge($resolved, $this->flat([$fullPath]));
                }
            }
        }

        return $resolved;
    }

    private function validated(array $lexicons): array
    {
        return array_filter($lexicons, fn (string $lexicon) =>
            str_ends_with($lexicon, '.json')
            && json_validate(file_get_contents($lexicon))
        );
    }



    private function classNamespaces(Lexicon $lexicon, ?string $prefix = null): array
    {
        $namespaces = [];

        $nsidParts = explode(".", $lexicon->nsid());

        $baseNamespace = $prefix . implode("\\", array_map(fn (string $part) => ucfirst($part), $nsidParts));

        $types = array_merge(...array_map(
            fn (array $definition, string $definitionName) => [$definitionName => $definition['type']],
            $definitions = $lexicon->definitions(),
            $definitionKeys = array_keys($definitions)
        ));

        $primaryTypes = array_map(fn (string $type) => strtolower($type), PrimaryTypeEnum::names());

        foreach($definitionKeys as $definitionKey) {
            $namespace = $baseNamespace;
            if (! in_array($types[$definitionKey], $primaryTypes, true)) {
                $namespace .= "\\" . ucfirst($definitionKey);
            }

            $namespaces[] = $namespace;
        }

        return $namespaces;
    }

    public function classPath(string $namespace, ?string $prefix = null, bool $create = true): string
    {
        $prefixPath = null;

        if (! is_null($prefix)) {
            $this->validatePrefix($prefix);
            $prefixPath = $this->prefixPath($prefix);
        }

        $normalize = fn(?string $namespace) => implode(
            DIRECTORY_SEPARATOR,
            explode("\\",
                $namespace ?? [])
        );

        $prefix = $normalize($prefix);
        $namespace = $normalize($namespace);

        if (! is_null($prefixPath)) {
            $namespace = str_replace($prefix, "", $namespace);
        }

        $path = $prefixPath . DIRECTORY_SEPARATOR . $namespace . ".php";

        if ($create) {
            if (! $this->filesystem->exists($path)) {
                $pathParts = explode(DIRECTORY_SEPARATOR, $path);
                array_pop($pathParts);
                $directoryPath = implode(DIRECTORY_SEPARATOR, $pathParts);
                $this->filesystem->mkdir($directoryPath, 0775);
            }
        }

        return $path;
    }

    private function prefixPath(string $prefix): string
    {
        return current($this->classLoader->getPrefixesPsr4()[$prefix]);
    }

    private function validatePrefix(string $prefix): void
    {
        if (! isset($this->classLoader->getPrefixesPsr4()[$prefix])) {
            throw new \RuntimeException("Prefix '$prefix' does not registered.");
        }
    }
}
