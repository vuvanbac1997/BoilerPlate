<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryBindServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     */
    public function boot() {
        //
    }

    /**
     * Register any application services.
     */
    public function register() {
        $this->app->singleton(
            \App\Repositories\AdminUserRepositoryInterface::class,
            \App\Repositories\Eloquent\AdminUserRepository::class
        );
        $this->app->singleton(
            \App\Repositories\AdminUserRoleRepositoryInterface::class,
            \App\Repositories\Eloquent\AdminUserRoleRepository::class
        );
        $this->app->singleton(
            \App\Repositories\UserRepositoryInterface::class,
            \App\Repositories\Eloquent\UserRepository::class
        );
        $this->app->singleton(
            \App\Repositories\FileRepositoryInterface::class,
            \App\Repositories\Eloquent\FileRepository::class
        );
        $this->app->singleton(
            \App\Repositories\ImageRepositoryInterface::class,
            \App\Repositories\Eloquent\ImageRepository::class
        );
        $this->app->singleton(
            \App\Repositories\UserServiceAuthenticationRepositoryInterface::class,
            \App\Repositories\Eloquent\UserServiceAuthenticationRepository::class
        );
        $this->app->singleton(
            \App\Repositories\PasswordResettableRepositoryInterface::class,
            \App\Repositories\Eloquent\PasswordResettableRepository::class
        );
        $this->app->singleton(
            \App\Repositories\UserPasswordResetRepositoryInterface::class,
            \App\Repositories\Eloquent\UserPasswordResetRepository::class
        );
        $this->app->singleton(
            \App\Repositories\AdminPasswordResetRepositoryInterface::class,
            \App\Repositories\Eloquent\AdminPasswordResetRepository::class
        );
        $this->app->singleton(
            \App\Repositories\ArticleRepositoryInterface::class,
            \App\Repositories\Eloquent\ArticleRepository::class
        );
        $this->app->singleton(
            \App\Repositories\NotificationRepositoryInterface::class,
            \App\Repositories\Eloquent\NotificationRepository::class
        );
        $this->app->singleton(
            \App\Repositories\UserNotificationRepositoryInterface::class,
            \App\Repositories\Eloquent\UserNotificationRepository::class
        );
        $this->app->singleton(
            \App\Repositories\AdminUserNotificationRepositoryInterface::class,
            \App\Repositories\Eloquent\AdminUserNotificationRepository::class
        );

        $this->app->singleton(
            \App\Repositories\LogRepositoryInterface::class,
            \App\Repositories\Eloquent\LogRepository::class
        );

        $this->app->singleton(
            \App\Repositories\OauthClientRepositoryInterface::class,
            \App\Repositories\Eloquent\OauthClientRepository::class
        );

        $this->app->singleton(
            \App\Repositories\OauthAccessTokenRepositoryInterface::class,
            \App\Repositories\Eloquent\OauthAccessTokenRepository::class
        );

        $this->app->singleton(
            \App\Repositories\OauthRefreshTokenRepositoryInterface::class,
            \App\Repositories\Eloquent\OauthRefreshTokenRepository::class
        );
        $this->app->singleton(
            \App\Repositories\TestRepositoryInterface::class,
            \App\Repositories\Eloquent\TestRepository::class
        );

        $this->app->singleton(
            \App\Repositories\TestRepositoryInterface::class,
            \App\Repositories\Eloquent\TestRepository::class
        );

        $this->app->singleton(
            \App\Repositories\TestRepositoryInterface::class,
            \App\Repositories\Eloquent\TestRepository::class
        );

        /* NEW BINDING */
    }
}
