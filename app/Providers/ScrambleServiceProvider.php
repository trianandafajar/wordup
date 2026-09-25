<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Dedoc\Scramble\Scramble;
use Illuminate\Routing\Route;

class ScrambleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Scramble::routes(function (Route $route) {
            return $route->domain() === null
                && str_starts_with($route->uri(), 'api/')
                && ! str_starts_with($route->getName() ?? '', 'filament.');
        });
    }
}
