<?php

namespace App\Doctrine\EventListener;

use App\Entity\User;
use App\Enum\UserRoleEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class DoctrineFilterEventListener
{
    public function __construct(private EntityManagerInterface $em, private Security $security) {}

    public function onKernelRequest(): void
    {
        if ($this->security->getUser() instanceof User && !$this->security->isGranted(UserRoleEnum::ADMIN->value)) {
            $userFilter = $this->em->getFilters()->getFilter('user_filter');
            $userFilter->setParameter('id', $this->security->getUser()->getId());
        }
    }
}
