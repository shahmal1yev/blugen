<?php

namespace Blugen\Command;

use Blugen\Config\ConfigManager;
use Blugen\Container;
use Blugen\Service\Lexicon\GeneratorInterface;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

class Generate extends Command
{
    private ConfigManager $configManager;

    public function __construct(ConfigManager $configManager = null)
    {
        parent::__construct();
        $this->configManager = $configManager ?? container()->get(ConfigManager::class);
    }

    protected function configure(): void
    {
        $this->setName('generate')
            ->setDescription('Generate based on lexicons')
            ->addOption(
                "source",
                "s",
                InputOption::VALUE_OPTIONAL,
                "Lexicon(s) source: directory or direct path of a lexicon",
            )
            ->addOption(
                "base-namespace",
                "bn",
                InputOption::VALUE_OPTIONAL,
                "Base namespace for the lexicons to be generated",
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $options = $this->options($input);
            $this->configManager->set('lexicons.source', $options['source']);
            $this->configManager->set('output.base_namespace', $options['base-namespace']);
        } catch (InvalidArgumentException $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        /** @var GeneratorInterface $generator */
        $generator = \container()->get(GeneratorInterface::class);

        foreach($generator->generate() as $path => $content) {
            $dirname = dirname($path);

            if (! file_exists($dirname)) {
                mkdir($dirname, 0755, true);
            }

            file_put_contents($path, $content);
        }

        return Command::SUCCESS;
    }

    private function options(InputInterface $input): array
    {
        $options = [
            'source' => (string) ($input->getOption('source') ?? $this->configManager->get('lexicons.source')),
            'base-namespace' => (string) ($input->getOption('base-namespace') ?? $this->configManager->get('output.base_namespace')),
        ];

        $violations = $this->validate($options);

        if (count($violations) > 0) {
            throw new InvalidArgumentException("Validation failed:\n\n" . $violations->__toString());
        }

        return $options;
    }

    private function validate(array $options): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();

        $loader = container()->get('loader');

        return $validator->validate($options, new Collection([
            'source' => [
                new NotBlank(),
                new Callback(function ($value, $context) {
                    if (!file_exists($value)) {
                        $context->buildViolation("Path '{{ value }}' does not exist.")
                            ->setParameter('{{ value }}', $value)
                            ->addViolation();
                    } elseif (!is_readable($value)) {
                        $context->buildViolation("Path '{{ value }}' is not readable.")
                            ->setParameter('{{ value }}', $value)
                            ->addViolation();
                    }
                }),
            ],
            'base-namespace' => [
                new NotBlank(),
                new Callback(function ($value, $context) use ($loader) {
                    if (!isset($loader->getPrefixesPsr4()[$value])) {
                        $context->buildViolation("Namespace '{{ value }}' is not registered in PSR-4 loader.")
                            ->setParameter('{{ value }}', $value)
                            ->addViolation();
                    }
                }),
            ]
        ]));
    }
}
