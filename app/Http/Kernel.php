<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\SecurePath::class,
        \App\Http\Middleware\TrustProxies::class,
    ];

    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\Locale::class,
        ],

        'admin' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\Locale::class,
        ],

        'api' => [
            \App\Http\Middleware\API\V1\SetDefaultValues::class,
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth.basic'                => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'admin.auth'                => \App\Http\Middleware\Admin\Authenticate::class,
        'admin.guest'               => \App\Http\Middleware\Admin\RedirectIfAuthenticated::class,
        'admin.has_role.super_user' => \App\Http\Middleware\Admin\HasRoleSuperUser::class,
        'admin.has_role.admin'      => \App\Http\Middleware\Admin\HasRoleAdmin::class,
        'admin.values'              => \App\Http\Middleware\Admin\SetDefaultValues::class,
        'web.auth'                  => \App\Http\Middleware\Web\Authenticate::class,
        'web.guest'                 => \App\Http\Middleware\Web\RedirectIfAuthenticated::class,
        'web.values'                => \App\Http\Middleware\Web\SetDefaultValues::class,
        'throttle'                  => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'bindings'                  => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'auth'                      => \Illuminate\Auth\Middleware\Authenticate::class,
        'api.client'                => \Laravel\Passport\Http\Middleware\CheckClientCredentials::class,
        'api.user'                  => \App\Http\Middleware\API\V1\DetectUser::class
    ];
}
