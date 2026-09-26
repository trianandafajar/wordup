<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use Dedoc\Scramble\ScrambleServiceProvider;

return [
    AppServiceProvider::class,
    AdminPanelProvider::class,
    ScrambleServiceProvider::class,
    App\Providers\ScrambleServiceProvider::class,
];
