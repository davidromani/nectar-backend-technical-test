<?php

namespace App\Command;

use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleSectionOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class AbstractBaseCommand extends Command
{
    protected TaskRepository $taskRepository;
    protected UserRepository $userRepository;
    protected SymfonyStyle $io;
    protected ConsoleSectionOutput $section;
    protected Table $table;

    public function __construct(
        TaskRepository $taskRepository,
        UserRepository $userRepository,
    ) {
        parent::__construct();
        $this->taskRepository = $taskRepository;
        $this->userRepository = $userRepository;
    }

    protected function configure(): void
    {
        $this
            ->addOption('show-table', null, InputOption::VALUE_NONE, 'Show table results')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->buildInputOutputSymfonyStyleAndGet($input, $output);
        $this->buildSectionAndGet($output);
        $this->buildTableAndGet();
    }

    protected function buildInputOutputSymfonyStyleAndGet(InputInterface $input, OutputInterface $output): SymfonyStyle
    {
        if (!isset($this->io)) {
            $this->io = new SymfonyStyle($input, $output);
        }

        return $this->io;
    }

    protected function buildSectionAndGet(OutputInterface $output): ConsoleSectionOutput
    {
        if (!isset($this->section)) {
            $this->section = $output->section();
        }

        return $this->section;
    }

    protected function buildTableAndGet(): Table
    {
        if (!isset($this->table)) {
            $this->table = new Table($this->section);
            $this->table->setStyle('box-double');
        }

        return $this->table;
    }

    protected function setTableHeaders(array $headers): void
    {
        $this->table->setHeaders($headers);
    }

    protected function addTableRow(array $row): void
    {
        $this->table->addRow($row);
    }
}
