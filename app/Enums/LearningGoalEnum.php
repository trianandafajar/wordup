<?php

namespace App\Enums;

enum LearningGoalEnum: string
{
    case CASUAL = 'casual';
    case BEGINNER = 'beginner';
    case INTERMEDIATE = 'intermediate';
    case ADVANCED = 'advanced';
    case FLUENT = 'fluent';

    /**
     * @return array<int, string>
     */
    public static function getAllValues(): array
    {
        return array_map(fn (self $goal) => $goal->value, self::cases());
    }

    /**
     * @return array<int, string>
     */
    public static function getAllKeys(): array
    {
        return array_map(fn (self $goal) => $goal->name, self::cases());
    }
    //
}
