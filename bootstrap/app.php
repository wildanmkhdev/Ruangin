<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
        // cek api disini harus sama dengan yg ad pad aroute group kita
        $middleware->alias([
            'cek_api' => \App\Http\Middleware\CheckApiKey::class,
        ]);
        // ini kode untuk daftarin middlewar ekita dnegan nama api key yg cek lgsg ke miidleware checkapikey kita
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
