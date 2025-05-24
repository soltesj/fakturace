<?php

namespace App\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:db-log-slow',
    description: 'Spravuje slow query log v databázi (zapnout, vypnout, status) Zapnutí performance_schema = ON je nutne udelat my.cnf (typicky v /etc/mysql/my.cnf nebo /etc/mysql/mariadb.conf.d/50-server.cnf)',
)]
class DbLogSlowCommand extends Command
{
    public function __construct(private readonly Connection $connection)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('action', InputArgument::REQUIRED, 'on|off|status');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $action = strtolower($input->getArgument('action'));
        match ($action) {
            'on' => $this->enableLogging($output),
            'off' => $this->disableLogging($output),
            'status' => $this->showStatus($output),
            default => $output->writeln('<error>Neznámá akce. Použij on|off|status.</error>'),
        };

        return Command::SUCCESS;
    }

    private function enableLogging(OutputInterface $output): void
    {
        $this->connection->executeStatement("SET GLOBAL slow_query_log = 'ON'");
        $this->connection->executeStatement("SET GLOBAL long_query_time = 0.002");
        $this->connection->executeStatement("SET GLOBAL log_queries_not_using_indexes = 'ON'");
        $output->writeln('<info>Slow query log zapnut.</info>');
    }

    private function disableLogging(OutputInterface $output): void
    {
        $this->connection->executeStatement("SET GLOBAL slow_query_log = 'OFF'");
        $this->connection->executeStatement("SET GLOBAL log_queries_not_using_indexes = 'OFF'");
        $output->writeln('<info>Slow query log vypnut.</info>');
    }

    private function showStatus(OutputInterface $output): void
    {
        $vars = [
            'performance_schema',
            'slow_query_log',
            'datadir',
            'slow_query_log_file',
            'long_query_time',
            'log_queries_not_using_indexes',
        ];
        foreach ($vars as $var) {
            $value = $this->connection->fetchAssociative("SHOW VARIABLES LIKE '$var'");
            $output->writeln("$var: <comment>{$value['Value']}</comment>");
        }
    }
}
