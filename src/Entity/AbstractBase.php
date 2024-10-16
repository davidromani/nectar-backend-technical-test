<?php

namespace App\Entity;

use App\Enum\BooleanEnum;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Attribute\Groups;

abstract class AbstractBase
{
    public const string DEFAULT_ROLE_USER = 'ROLE_USER';
    public const string DEFAULT_NULL_STRING = '---';
    public const string DEFAULT_NULL_DATE_STRING = '--/--/----';
    public const string DEFAULT_NULL_DATETIME_STRING = '--/--/---- --:--';
    public const string DATE_PICKER_TYPE_FORMAT = 'dd/MM/yyyy';
    public const string DATE_FORM_TYPE_FORMAT = 'd/M/y';
    public const string DATETIME_FORM_TYPE_FORMAT = 'd/M/y hh:ss';
    public const string DATE_STRING_FORMAT = 'd/m/Y';
    public const string DATETIME_STRING_FORMAT = 'd/m/Y H:i';
    public const string DATABASE_DATE_STRING_FORMAT = 'Y-m-d';
    public const string DATABASE_DATETIME_STRING_FORMAT = 'Y-m-d H:i:s';

    #[Groups(['task:read', 'user:read'])]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    #[ORM\Id]
    protected ?int $id = null;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    protected \DateTimeInterface $createdAt;

    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    protected \DateTimeInterface $updatedAt;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => true])]
    protected bool $active = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getCreatedAtString(): string
    {
        return self::convertDateTimeAsString($this->getCreatedAt());
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getUpdatedAtString(): string
    {
        return self::convertDateTimeAsString($this->getUpdatedAt());
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function getActive(): ?bool
    {
        return $this->isActive();
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public static function convertBooleanValueAsString(?bool $value): string
    {
        return $value ? BooleanEnum::YES->value : BooleanEnum::NO->value;
    }

    public static function convertDateAsString(?\DateTimeInterface $date): string
    {
        return $date ? $date->format(self::DATE_STRING_FORMAT) : self::DEFAULT_NULL_DATE_STRING;
    }

    public static function convertDateTimeAsString(?\DateTimeInterface $date): string
    {
        return $date ? $date->format(self::DATETIME_STRING_FORMAT) : self::DEFAULT_NULL_DATETIME_STRING;
    }

    public function __toString(): string
    {
        return $this->id ? $this->getId().' · '.$this->getCreatedAtString() : self::DEFAULT_NULL_STRING;
    }
}
