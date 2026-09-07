<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getColumns(): int|string|array
    {
        return 12;
    }

    public function getHeaderWidgetsColumns(): int|string|array
    {
        return 12;
    }

    public function getFooterWidgetsColumns(): int|string|array
    {
        return 12;
    }
}
