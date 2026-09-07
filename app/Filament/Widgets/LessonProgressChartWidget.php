<?php

namespace App\Filament\Widgets;

use App\Enums\LessonProgressStatusEnum;
use App\Models\UserLessonProgress;
use Filament\Widgets\ChartWidget;

class LessonProgressChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Lesson Progress';

    protected static string $color = 'primary';

    protected static ?string $maxHeight = '300px';

    protected int|string|array $columnSpan = 5;

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
        ];
    }

    protected function getData(): array
    {
        $completed = UserLessonProgress::query()->where('status', LessonProgressStatusEnum::Completed->value)->count();
        $inProgress = UserLessonProgress::query()->where('status', LessonProgressStatusEnum::InProgress->value)->count();
        $notStarted = UserLessonProgress::query()->where('status', LessonProgressStatusEnum::NotStarted->value)->count();

        return [
            'datasets' => [
                [
                    'label' => 'Progress',
                    'data' => [$completed, $inProgress, $notStarted],
                    'backgroundColor' => ['#4caf50', '#ff9800', '#e0e0e0'],
                ],
            ],
            'labels' => ['Completed', 'In Progress', 'Not Started'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
