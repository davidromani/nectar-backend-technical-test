<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableCellStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:query:get-tasks-list-by-user',
    description: 'Get total pending & completed tasks grouped by user list command',
)]
final class QueryGetTasksListByUserCommand extends AbstractBaseCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->info('Get total pending & completed tasks grouped by user list command');
        $userTasksDto = $this->usersTasksDtoSerializerManager->getDeserializedResults($this->userRepository->getTasksListGroupedByUser());
        $this->setTableHeaders([
            new TableCell(
                '#',
                [
                    'style' => new TableCellStyle([
                        'align' => 'right',
                    ]),
                ]
            ),
            new TableCell(
                'ID',
                [
                    'style' => new TableCellStyle([
                        'align' => 'right',
                    ]),
                ]
            ),
            new TableCell(
                'User',
                [
                    'style' => new TableCellStyle([
                        'align' => 'left',
                    ]),
                ]
            ),
            new TableCell(
                'Pending tasks',
                [
                    'style' => new TableCellStyle([
                        'align' => 'right',
                    ]),
                ]
            ),
            new TableCell(
                'Completed tasks',
                [
                    'style' => new TableCellStyle([
                        'align' => 'right',
                    ]),
                ]
            ),
        ]);
        foreach ($userTasksDto as $index => $userTaskDto) {
            $this->addTableRow([
                new TableCell(
                    $index + 1,
                    [
                        'style' => new TableCellStyle([
                            'align' => 'right',
                        ]),
                    ]
                ),
                new TableCell(
                    $userTaskDto->getId(),
                    [
                        'style' => new TableCellStyle([
                            'align' => 'right',
                        ]),
                    ]
                ),
                new TableCell(
                    $userTaskDto->getName(),
                    [
                        'style' => new TableCellStyle([
                            'align' => 'left',
                        ]),
                    ]
                ),
                new TableCell(
                    $userTaskDto->getPending(),
                    [
                        'style' => new TableCellStyle([
                            'align' => 'right',
                            'fg' => 'yellow',
                        ]),
                    ]
                ),
                new TableCell(
                    $userTaskDto->getCompleted(),
                    [
                        'style' => new TableCellStyle([
                            'align' => 'right',
                            'fg' => 'green',
                        ]),
                    ]
                ),
            ]);
        }
        if ($input->getOption('show-table')) {
            $this->table->render();
        }
        $this->io->info('Executed MySQL query');
        $this->io->block($this->userRepository->getTasksListGroupedByUserQ()->getSQL());

        return Command::SUCCESS;
    }
}
