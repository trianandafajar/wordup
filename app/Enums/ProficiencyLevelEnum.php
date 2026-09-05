<?php

namespace App\Enums;

/**
 * This enum represents the proficiency levels for language learning.
 * BEGINNER: Represents a beginner level of proficiency.
 * INTERMEDIATE: Represents an intermediate level of proficiency.
 * ADVANCED: Represents an advanced level of proficiency.
 * FLUENT: Represents a fluent level of proficiency.
 */
enum ProficiencyLevelEnum: string
{
    case BEGINNER = 'beginner';
    case INTERMEDIATE = 'intermediate';
    case ADVANCED = 'advanced';
    case FLUENT = 'fluent';

    public static function getAllValues(): array
    {
        return array_map(fn (self $level) => $level->value, self::cases());
    }

    public static function getAllKeys(): array
    {
        return array_map(fn (self $level) => $level->name, self::cases());
    }
}
