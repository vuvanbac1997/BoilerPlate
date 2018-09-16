<?php
namespace App\Services\Production;

use App\Repositories\AuthenticatableRepositoryInterface;
use App\Repositories\PasswordResettableRepositoryInterface;
use App\Repositories\OauthClientRepositoryInterface;
use App\Services\AuthenticatableServiceInterface;

class AuthenticatableService implements AuthenticatableServiceInterface
{
    /** @var \App\Repositories\AuthenticatableRepositoryInterface */
    protected $authenticatableRepository;

    /** @var \App\Repositories\OauthClientRepositoryInterface */
    protected $oauthClientRepository;

    /** @var  \App\Repositories\PasswordResettableRepositoryInterface */
    protected $passwordResettableRepository;

    /** @var string $resetEmailTitle */
    protected $resetEmailTitle = 'Reset Password';

    /** @var string $resetEmailTemplate */
    protected $resetEmailTemplate = '';

    public function __construct(
        AuthenticatableRepositoryInterface      $authenticatableRepository,
        PasswordResettableRepositoryInterface   $passwordResettableRepository,
        OauthClientRepositoryInterface          $oauthClientRepository
    )
    {
        $this->authenticatableRepository    = $authenticatableRepository;
        $this->passwordResettableRepository = $passwordResettableRepository;
        $this->oauthClientRepository        = $oauthClientRepository;
    }

    public function signInById($id)
    {
        /** @var \App\Models\AuthenticatableBase $user */
        $user = $this->authenticatableRepository->find($id);
        if (empty($user)) {
            return false;
        }
        $guard = $this->getGuard();
        $guard->login($user);

        return $guard->user();
    }

    public function signIn($input)
    {
        $rememberMe = (bool) array_get($input, 'remember_me', 0);
        $guard = $this->getGuard();
        if (!$guard->attempt(['email' => $input['email'], 'password' => $input['password']], $rememberMe, true)) {
            return false;
        }

        return $guard->user();
    }

    public function signUp($input)
    {
        $existingUser = $this->authenticatableRepository->findByEmail(array_get($input, 'email'));
        if ( !empty($existingUser) ) {
            return null;
        }

        /** @var \App\Models\AuthenticatableBase $user */
        $user = $this->authenticatableRepository->create($input);
        if (empty($user)) {
            return false;
        }
        $guard = $this->getGuard();
        $guard->login($user);

        return $guard->user();
    }

    public function sendPasswordReset($email)
    {
        return false;
    }

    public function signOut()
    {
        $user = $this->getUser();
        if (empty($user)) {
            return false;
        }
        $guard = $this->getGuard();
        $guard->logout();
        \Session::flush();

        return true;
    }

    public function resignation()
    {
        $user = $this->getUser();
        if (empty($user)) {
            return false;
        }
        $guard = $this->getGuard();
        $guard->logout();
        \Session::flush();
        $this->authenticatableRepository->delete($user);

        return true;
    }

    public function setUser($user)
    {
        $guard = $this->getGuard();
        $guard->login($user);
    }

    public function getUser()
    {
        $guard = $this->getGuard();

        return $guard->user();
    }

    public function sendPasswordResetEmail($email)
    {
        $user = $this->authenticatableRepository->findByEmail($email);
        if (empty($user)) {
            return false;
        }

        $token = $this->passwordResettableRepository->create($user);

        /** @var \App\Services\MailServiceInterface $mailService */
        $mailService = \App::make('App\Services\MailServiceInterface');

        $mailService->sendMail(
            $this->resetEmailTitle,
            config('mail.from'),
            [
                'name' => '',
                'address' => $user->email
            ],
            $this->resetEmailTemplate,
            [
                'token' => $token
            ]
        );

        return true;
    }

    public function resetPassword($email, $password, $token)
    {
        $user = $this->authenticatableRepository->findByEmail($email);
        if (empty($user)) {
            return false;
        }

        $tokenModel = $this->passwordResettableRepository->exists($user, $token);

        if (empty($tokenModel)) {
            return false;
        }
        $this->authenticatableRepository->update($user, ['password' => $password]);

        $this->passwordResettableRepository->delete($token);
        $this->setUser($user);

        return true;
    }

    public function isSignedIn()
    {
        $guard = $this->getGuard();

        return $guard->check();
    }

    public function signInByAPI($input)
    {
        /** @var \App\Models\AuthenticatableBase $user */
        $user = $this->signIn($input);
        if (empty($user)) {
            return null;
        }

        return $this->setAPIAccessToken($user);
    }

    public function signUpByAPI($input)
    {
        /** @var \App\Models\AuthenticatableBase $user */
        $user = $this->signUp($input);
        if (empty($user)) {
            return null;
        }

        return $this->setAPIAccessToken($user);
    }

    public function setAPIAccessToken($user)
    {
        $user->setAPIAccessToken();
        $this->authenticatableRepository->save($user);

        return $user;
    }

    /**
     * @return string
     */
    public function getGuardName()
    {
        return '';
    }

    /**
     * @return \Illuminate\Contracts\Auth\Guard
     */
    protected function getGuard()
    {
        return \Auth::guard($this->getGuardName());
    }

    public function checkClient($request)
    {
        $oauthClient = $this->oauthClientRepository->findByIdAndSecret(
            $request->get('client_id'),
            $request->get('client_secret')
        );
        return !empty($oauthClient);
    }

    public function sendUserPasswordResetEmail($email)
    {
        $user     = $this->authenticatableRepository->findByEmail($email);
        $template = 'emails.user.reset_password';

        if (empty($user)) {
            return;
        }

        $token = $this->passwordResettableRepository->create($user);

        /** @var \App\Services\MailServiceInterface $mailService */
        $mailService = \App::make('App\Services\MailServiceInterface');
        $mailService->sendEmailForgotPassWord($user, $token);
    }

    public function sendContactUsEmail($user)
    {
        $template    = 'emails.user.contact_us';
        $mailService = \App::make('App\Services\MailServiceInterface');
        //        $mailService->sendEmailContactUs($user);
        $mailService->sendMail(
            trans('user.pages.emails.title.contact_us'),
            config('mail.from'),
            config('mail.contact'),
            $template,
            ['user' => $user]
        );
    }
}