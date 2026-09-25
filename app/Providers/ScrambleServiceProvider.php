<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Dedoc\Scramble\Scramble;
use Illuminate\Routing\Router;

class ScrambleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Scramble::routes(function (\Illuminate\Routing\Route $route) {
            return $route->domain() === null;
        });
    }
}
