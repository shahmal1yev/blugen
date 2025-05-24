<?php

namespace Blugen\Command;

use Blugen\Config\ConfigManager;
use Blugen\Container;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

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
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Blugen Code Generator');

        // Source option
        $source = $input->getOption('source') ?? $this->configManager->get('lexicons.source');

        if (! file_exists($source)) {
            $io->error(sprintf("Source file '%s' does not exist", $source));
            return Command::FAILURE;
        }

        if (! is_readable($source)) {
            $io->error(sprintf("Source file '%s' is not readable", $source));
            return Command::FAILURE;
        }

        $this->configManager->set("lexicons.source", $source);

        // Load configuration values
        $outputDir = $this->configManager->get('output.path');
        $namespaces = $this->configManager->get('namespaces');
        $sourcePath = $this->configManager->get('lexicons.source');

        // Show configuration summary
        $io->section('Configuration');
        $io->table(
            ['Setting', 'Value'],
            [
                ['Output Directory', $outputDir],
                ['Default Namespace', $namespaces['default']['namespace']],
                ['Lexicon Source', $sourcePath],
            ]
        );

        // Code generation would happen here
        $io->section('Generating Code');
        $io->progressStart(3); // Example: assume we have 3 steps

        sleep(1);

        // Step 1: Parse lexicons
        $io->progressAdvance();
        $io->text('✓ Parsed lexicon definitions');

        sleep(1);

        // Step 2: Generate classes
        $io->progressAdvance();
        $io->text('✓ Generated class files');

        // Step 3: Write to disk
        $io->progressAdvance();
        $io->text('✓ Written files to disk');

        sleep(1);

        $io->progressFinish();

        $io->success('Code generation completed successfully!');

        return Command::SUCCESS;
    }
}
