<?php

namespace App\Enums;

enum LessonProgressStatusEnum: string
{
    case NotStarted = 'not_started';
    case InProgress = 'in_progress';
    case Completed = 'completed';

    /**
     * @return array<int, string>
     */
    public static function getAllValues(): array
    {
        return array_map(static fn (self $status): string => $status->value, self::cases());
    }
}
