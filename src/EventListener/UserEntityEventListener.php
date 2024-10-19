<?php

namespace App\EventListener;

use App\Entity\AbstractBase;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: User::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: User::class)]
final readonly class UserEntityEventListener
{
    public function __construct(private PasswordHasherFactoryInterface $hasherFactory)
    {
    }

    public function prePersist(User $user): void
    {
        if (is_null($user->getPlainPassword()) && $user->getPassword()) {
            // force to rehash on API request to create a new user
            $user->setPlainPassword($user->getPassword());
        }
        $user->addRole(AbstractBase::DEFAULT_ROLE_USER);
        $user->setPassword($this->getHashedPassword($user));
    }

    public function preUpdate(User $user, PreUpdateEventArgs $args): void
    {
        if ($user->getPlainPassword()) {
            $user->setPassword($this->getHashedPassword($user));
        }
    }

    private function getHashedPassword(User $user): string
    {
        return $this->hasherFactory->getPasswordHasher($user)->hash($user->getPlainPassword());
    }
}
