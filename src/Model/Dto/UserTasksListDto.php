<?php

namespace App\Model\Dto;

class UserTasksListDto extends UserWithoutCompletedTasksListDto
{
    private int $pending;
    private int $completed;

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
