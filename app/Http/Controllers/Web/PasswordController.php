<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\PasswordController as PasswordControllerBase;
use App\Http\Requests\ForgotPasswordRequest;
use App\Services\UserServiceInterface;

class PasswordController extends PasswordControllerBase
{
    /** @var string $emailSetPageView */
    protected $emailSetPageView = 'pages.web.auth.forgot-password';

    /** @var string $passwordResetPageView */
    protected $passwordResetPageView = 'pages.web.auth.reset-password';

    /** @var string $returnAction */
    protected $returnAction = 'Web\IndexController@index';

    public function __construct(UserServiceInterface $userService)
    {
        $this->authenticatableService = $userService;
    }
    
    public function postForgotPassword(ForgotPasswordRequest $request)
    {
        parent::postForgotPassword($request);
        
        return redirect()->back()->with('status', 'success');
    }
}
