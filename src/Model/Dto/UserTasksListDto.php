<?php

namespace App\Model\Dto;

class UserTasksListDto
{
    private string $name;
    private int $pending;
    private int $completed;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPending(): int
    {
        return $this->pending;
    }

    public function setPending(int $pending): self
    {
        $this->pending = $pending;

        return $this;
    }

    public function getCompleted(): int
    {
        return $this->completed;
    }

    public function setCompleted(int $completed): self
    {
        $this->completed = $completed;

        return $this;
    }
}
