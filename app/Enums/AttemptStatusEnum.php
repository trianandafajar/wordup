<?php

namespace App\Enums;

enum AttemptStatusEnum: string
{
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Abandoned = 'abandoned';

    /**
     * @return array<int, string>
     */
    public static function getAllValues(): array
    {
        return array_map(static fn (self $status): string => $status->value, self::cases());
    }
}
