<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class RegistrationChartWidget extends ChartWidget
{
    protected static ?string $heading = 'User Registrations';

    protected static string $color = 'success';

    protected static ?string $maxHeight = '300px';

    protected int|string|array $columnSpan = 7;

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
        ];
    }

    protected function getData(): array
    {
        $users = User::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Users',
                    'data' => $users->pluck('total')->values()->toArray(),
                ],
            ],
            'labels' => $users->pluck('date')->map(fn ($d) => Carbon::parse($d)->format('d M'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
