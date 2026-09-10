<?php

namespace App\Enums;

enum ProficiencyLevelEnum: string
{
    case BEGINNER = 'beginner';
    case INTERMEDIATE = 'intermediate';
    case ADVANCED = 'advanced';
    case FLUENT = 'fluent';

     /**
     * @return array<int, string>
     */
    public static function getAllValues(): array
    {
        return array_map(fn (self $level) => $level->value, self::cases());
    }

    /**
     * @return array<int, string>
     */
    public static function getAllKeys(): array
    {
        return array_map(fn (self $level) => $level->name, self::cases());
    }
}
