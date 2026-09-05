<?php

namespace App\Enums;

enum QuestionDifficultyEnum: string
{
    case Beginner = 'beginner';
    case Intermediate = 'intermediate';
    case Advanced = 'advanced';

    /**
     * @return array<int, string>
     */
    public static function getAllValues(): array
    {
        return array_map(static fn (self $difficulty): string => $difficulty->value, self::cases());
    }
}
