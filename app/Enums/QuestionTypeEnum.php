<?php

namespace App\Enums;

enum QuestionTypeEnum: string
{
    case MultipleChoice = 'multiple_choice';
    case FillInTheBlank = 'fill_in_the_blank';
    case Listening = 'listening';

    /**
     * @return array<int, string>
     */
    public static function getAllValues(): array
    {
        return array_map(static fn (self $type): string => $type->value, self::cases());
    }
}
