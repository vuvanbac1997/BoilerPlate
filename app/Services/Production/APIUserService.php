<?php
namespace App\Services\Production;

use App\Services\APIUserServiceInterface;
use App\Repositories\OauthClientRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\UserPasswordResetRepositoryInterface;

class APIUserService extends AuthenticatableService implements APIUserServiceInterface
{
    /** @var string $resetEmailTitle */
    protected $resetEmailTitle = 'Reset Password';

    /** @var string $resetEmailTemplate */
    protected $resetEmailTemplate = '';

    public function __construct(
        UserRepositoryInterface                 $userRepository,
        UserPasswordResetRepositoryInterface    $userPasswordResetRepository,
        OauthClientRepositoryInterface          $oauthClientRepository
    )
    {
        $this->authenticatableRepository    = $userRepository;
        $this->passwordResettableRepository = $userPasswordResetRepository;
        $this->oauthClientRepository        = $oauthClientRepository;
    }
    
    public function getGuardName()
    {
        return 'api';
    }
}
