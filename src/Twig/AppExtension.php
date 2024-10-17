<?php

namespace App\Twig;

use App\Enum\TaskStatusEnum;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('task_status_label', [$this, 'taskStatusLabel']),
        ];
    }

    public function taskStatusLabel(int $value): string
    {
        $html = '<span class="label label-%s">%s</span>';
        $labelCssClass = $value === TaskStatusEnum::PENDING->value ? 'warning' : 'info';
        $text = array_flip(TaskStatusEnum::getChoices())[$value];

        return sprintf($html, $labelCssClass, $text);
    }
}
