<?php

return [
    'route_prefix' => 'laravelunused',

    'middleware' => [
        TypeHints\Unused\Middleware\LaravelUnusedMiddleware::class,
    ],

    'ignored_views' => [
    ],
];
