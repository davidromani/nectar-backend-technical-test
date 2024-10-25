<?php

namespace App\Repository;

use App\Entity\Task;
use App\Entity\User;
use App\Enum\TaskStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function getTotalTasksAmount(): int
    {
        return $this->createQueryBuilder('t')
            ->select('COUNT(t.id) AS total')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getUsersIdsArrayWithCompletedTasksQB(): QueryBuilder
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.user', 'u')
            ->select('u.id')
            ->where('t.status = :completed')
            ->setParameter('completed', TaskStatusEnum::COMPLETED->value)
            ->groupBy('u.id')
        ;
    }

    public function getUsersIdsArrayWithCompletedTasksQ(): Query
    {
        return $this->getUsersIdsArrayWithCompletedTasksQB()->getQuery();
    }

    public function getUsersIdsArrayWithCompletedTasks(): array
    {
        return $this->getUsersIdsArrayWithCompletedTasksQ()->getSingleColumnResult();
    }

    public function getUsersWithoutCompletedTasksQB(): QueryBuilder
    {
        return $this->createQueryBuilder('u')
            ->select('us.id, us.name')
            ->from(User::class, 'us')
            ->where('us.id NOT IN (:ids)')
            ->setParameter('ids', $this->getUsersIdsArrayWithCompletedTasks())
            ->groupBy('us.id')
        ;
    }

    public function getUsersWithoutCompletedTasksQ(): Query
    {
        return $this->getUsersWithoutCompletedTasksQB()->getQuery();
    }

    public function getUsersWithoutCompletedTasks(): array
    {
        return $this->getUsersWithoutCompletedTasksQ()->getArrayResult();
    }
}
