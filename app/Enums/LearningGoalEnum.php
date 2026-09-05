<?php

namespace App\Enums;

/**
 * This enum represents the learning goals for language learning.
 * CASUAL: Represents a casual learning goal.
 * BEGINNER: Represents a beginner learning goal.
 * INTERMEDIATE: Represents an intermediate learning goal.
 * ADVANCED: Represents an advanced learning goal.
 * FLUENT: Represents a fluent learning goal.
 */
enum LearningGoalEnum: string
{
    case CASUAL = 'casual';
    case BEGINNER = 'beginner';
    case INTERMEDIATE = 'intermediate';
    case ADVANCED = 'advanced';
    case FLUENT = 'fluent';

    public static function getAllValues(): array
    {
        return array_map(fn (self $goal) => $goal->value, self::cases());
    }

    public static function getAllKeys(): array
    {
        return array_map(fn (self $goal) => $goal->name, self::cases());
    }
    //
}
