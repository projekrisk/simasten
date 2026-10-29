<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();

$publicPath = env('PUBLIC_PATH');

if (! $publicPath) {
    if (basename($app->basePath()) === 'public_html') {
        $publicPath = $app->basePath();
    } elseif (is_dir($app->basePath('../public_html'))) {
        $publicPath = realpath($app->basePath('../public_html'));
    } elseif (is_dir($app->basePath('public_html'))) {
        $publicPath = $app->basePath('public_html');
    }
}

if ($publicPath) {
    $app->usePublicPath($publicPath);
}

return $app;
