<?php

use Illuminate\Routing\Route;

return [
    'api_path' => 'api',
    'api_domain' => null,
    'export_path' => 'api.json',
    'info' => [
        'title' => 'Wordup API Documentation',
        'version' => '1.0.0',
        'description' => 'API documentation for Wordup application.',
    ],
    'ui' => [
        'title' => 'Wordup API Docs',
        'path' => 'docs/api',
    ],
    'middleware' => [
        'web',
    ],
    'routes' => function (Route $route) {
        return str_starts_with($route->uri(), 'api/');
    },
];
