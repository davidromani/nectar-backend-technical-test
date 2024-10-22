<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableCellStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:query:get-users-without-completed-tasks-list',
    description: 'Get users without completed tasks list command',
)]
final class QueryGetUsersWithoutCompletedTasksListCommand extends AbstractBaseCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->info('Get users without completed tasks list command command');
        $usersTasksDto = $this->usersTasksDtoSerializerManager->getUserWithoutCompletedTasksListDeserializedResults($this->taskRepository->getUsersWithoutCompletedTasks());
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
        ]);
        foreach ($usersTasksDto as $index => $userTaskDto) {
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
            ]);
        }
        if ($input->getOption('show-table')) {
            $this->table->render();
        }
        $this->io->info('Executed MySQL query 1 to obtain users ID array with completed tasks');
        $this->io->block($this->taskRepository->getUsersIdsArrayWithCompletedTasksQ()->getSQL());
        $this->io->info('Executed dependent MySQL query 2 to obtain users without completed tasks applying NOT IN parameter from query 1 result');
        $this->io->block($this->taskRepository->getUsersWithoutCompletedTasksQ()->getSQL());

        return Command::SUCCESS;
    }
}
