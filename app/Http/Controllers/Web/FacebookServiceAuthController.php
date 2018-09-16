<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\ServiceAuthController;
use App\Services\UserServiceInterface;
use App\Services\UserServiceAuthenticationServiceInterface;
use Laravel\Socialite\Contracts\Factory as Socialite;

class FacebookServiceAuthController extends ServiceAuthController
{
    protected $driver = 'facebook';

    protected $redirectAction = 'Web\IndexController@index';

    protected $errorRedirectAction = 'Web\AuthController@getSignUp';

    public function __construct(
        UserServiceInterface $authenticatableService,
        UserServiceAuthenticationServiceInterface $serviceAuthenticationService,
        Socialite $socialite
    ) {
        $this->authenticatableService = $authenticatableService;
        $this->serviceAuthenticationService = $serviceAuthenticationService;
        $this->socialite = $socialite;
    }
}
