<?php

namespace App\Repository;

use App\Entity\User;
use App\Enum\TaskStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function getTotalUsersAmount(): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id) AS total')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getTasksListGroupedByUserQB(): QueryBuilder
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.tasks', 't')
            ->select('u.name, SUM(CASE WHEN t.status = :pending THEN 1 ELSE 0 END) AS pending, SUM(CASE WHEN t.status = :completed THEN 1 ELSE 0 END) AS completed')
            ->setParameter('pending', TaskStatusEnum::PENDING->value)
            ->setParameter('completed', TaskStatusEnum::COMPLETED->value)
        ;
    }

    public function getTasksListGroupedByUserQ(): Query
    {
        return $this->getTasksListGroupedByUserQB()->getQuery();
    }

    public function getTasksListGroupedByUser(): array
    {
        return $this->getTasksListGroupedByUserQ()->getArrayResult();
    }
}
