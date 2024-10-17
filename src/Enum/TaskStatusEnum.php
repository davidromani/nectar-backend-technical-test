<?php

namespace App\Enum;

enum TaskStatusEnum: int
{
    case PENDING = 0;
    case COMPLETED = 1;

    public static function getChoices(): array
    {
        return [
            'pending' => self::PENDING->value,
            'completed' => self::COMPLETED->value,
        ];
    }
}
