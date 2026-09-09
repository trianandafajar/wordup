<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Users', User::query()->whereDoesntHave('roles', fn ($q) => $q->where('name', 'admin'))->count())
                ->description('Total pengguna terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
            Stat::make('Courses', Course::count())
                ->description('Kursus pembelajaran')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('info'),
            Stat::make('Lessons', Lesson::count())
                ->description('Total lesson tersedia')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('warning'),
            Stat::make('Questions', Question::count())
                ->description('Total soal dibuat')
                ->descriptionIcon('heroicon-m-question-mark-circle')
                ->color('danger'),
        ];
    }
}
