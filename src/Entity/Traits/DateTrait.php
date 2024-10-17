<?php

namespace App\Entity\Traits;

use App\Entity\AbstractBase;

trait DateTrait
{
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function getDateString(): string
    {
        return AbstractBase::convertDateAsString($this->getDate());
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getYear(): string
    {
        return $this->getDate()?->format('Y');
    }

    public function getMonth(): string
    {
        return $this->getDate()?->format('m');
    }

    public function getDay(): string
    {
        return $this->getDate()?->format('d');
    }
}
