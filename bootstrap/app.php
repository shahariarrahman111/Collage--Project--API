<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
       
        
        $middleware->validateCsrfTokens(
            except: ['stripe/*', 'user-registration', 'user-login', 'user-logout','profile/create','profile','profile/update','otp/create',
            'otp/verify','reset-password','categories/create', 'categories/update/{id}' ,'categories/delete/{id}','product-create','product-update/{id}',
            'product-delete/{id}','dashboard','admin/profile/update','cart-add','orders','admin/orders/{userId}','add/wishlist',
            'delete/wishlist/{id}']
        );

       

       $middleware->appendToGroup('web', [
                // App\Http\Middleware\VerifyCsrfToken::class,
        ]);


       $middleware->alias([
        'role'=> \App\Http\Middleware\RoleMiddleware::class,
      
       ]);


    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
