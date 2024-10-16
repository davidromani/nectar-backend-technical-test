<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Traits\DateTrait;
use App\Entity\Traits\DescriptionTrait;
use App\Entity\Traits\TitleTrait;
use App\Enum\TaskStatusEnum;
use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    description: 'A task.',
    operations: [
        new Post(),
        new Patch(),
        new Delete(),
    ],
)]
#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\Table(name: 'task')]
class Task extends AbstractBase
{
    use DateTrait;
    use DescriptionTrait;
    use TitleTrait;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'tasks')]
    private User $user;

    #[Assert\NotNull]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, length: 4000, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::INTEGER, nullable: false, options: ['default' => TaskStatusEnum::PENDING->value])]
    private int $status = TaskStatusEnum::PENDING->value;

    #[Assert\Date]
    #[Assert\NotNull]
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: false)]
    private ?\DateTimeInterface $date;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
