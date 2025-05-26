<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:analyze-log-slow',
    description: 'Show slow query log via pt-query-digest)',
)]
class DbAnalyzeSlowLogCommand extends Command
{
    const string LOG_FILE = '/var/log/mysql/slow.log';

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('limit', 'l', InputOption::VALUE_OPTIONAL, 'Limiting output', 10);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $limit = (int)$input->getOption('limit');
        $exists = shell_exec('which pt-query-digest');
        if (!$exists) {
            $io->error('pt-query-digest not found');

            return Command::FAILURE;
        }
        $logFile = self::LOG_FILE;
        if (!file_exists($logFile)) {
            $io->error("File $logFile doesnt ist");

            return Command::FAILURE;
        }
        $command = "pt-query-digest --limit={$limit} {$logFile}";
        $io->info("Running: {$command}");
        $result = shell_exec($command);
        if (!$result) {
            $io->error("pt-query-digest retuned empty output.");

            return Command::FAILURE;
        }
        $output->writeln($result);

        return Command::SUCCESS;
    }
}
