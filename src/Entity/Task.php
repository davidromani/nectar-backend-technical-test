<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Doctrine\Attributes\UserAware;
use App\Entity\Traits\DateTrait;
use App\Entity\Traits\DescriptionTrait;
use App\Entity\Traits\TitleTrait;
use App\Enum\SortOrderEnum;
use App\Enum\TaskStatusEnum;
use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'Task',
    description: 'A task.',
    operations: [
        new GetCollection(),
        new Post(),
        new Patch(security: 'object.getUser() == user'),
        new Delete(security: 'object.getUser() == user'),
    ],
    normalizationContext: [
        'groups' => ['task:read'],
    ],
    denormalizationContext: [
        'groups' => ['task:write'],
    ],
    order: ['date' => SortOrderEnum::DESCENDING->value],
    paginationItemsPerPage: 100,
)]
#[ApiFilter(SearchFilter::class, properties: ['status' => 'exact', 'user' => 'exact'])]
#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\Table(name: 'task')]
#[UserAware(userFieldName: 'user_id')]
class Task extends AbstractBase
{
    use DateTrait;
    use DescriptionTrait;
    use TitleTrait;

    #[Groups(['task:read', 'task:write'])]
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'tasks')]
    private User $user;

    #[Assert\NotNull]
    #[Groups(['task:read', 'task:write'])]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    private ?string $title = null;

    #[Groups(['task:read', 'task:write'])]
    #[ORM\Column(type: Types::TEXT, length: 4000, nullable: true)]
    private ?string $description = null;

    #[Groups(['task:read', 'task:write'])]
    #[ORM\Column(type: Types::INTEGER, nullable: false, options: ['default' => TaskStatusEnum::PENDING->value])]
    private int $status = TaskStatusEnum::PENDING->value;

    #[Assert\NotNull]
    #[Groups(['task:read', 'task:write'])]
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
