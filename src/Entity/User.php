<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Entity\Traits\EmailTrait;
use App\Entity\Traits\NameTrait;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'User',
    description: 'A user.',
    operations: [
        new Post(),
    ],
    normalizationContext: [
        'groups' => ['user:read'],
    ],
    denormalizationContext: [
        'groups' => ['user:write'],
    ],
    paginationItemsPerPage: 100,
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User extends AbstractBase implements UserInterface, PasswordAuthenticatedUserInterface
{
    use EmailTrait;
    use NameTrait;

    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'user')]
    private Collection $tasks;

    #[Assert\Email]
    #[Assert\NotNull]
    #[Groups(['user:write'])]
    #[ORM\Column(type: Types::STRING, length: 255, unique: true, nullable: false)]
    private ?string $email = null;

    #[Assert\NotNull]
    #[Groups(['user:write'])]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    private ?string $name = null;

    #[ORM\Column]
    private array $roles = [];

    #[Assert\NotNull]
    #[Groups(['user:write'])]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    private ?string $password = null;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function getTasksAmount(): int
    {
        return $this->getTasks()->count();
    }

    public function setTasks(Collection $tasks): self
    {
        $this->tasks = $tasks;

        return $this;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
        }

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = self::DEFAULT_ROLE_USER;

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
