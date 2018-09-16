<?php

namespace App\Http\Middleware\Web;

use App\Services\UserServiceInterface;

class RedirectIfAuthenticated
{
    /** @var UserServiceInterface */
    protected $userService;

    /**
     * Create a new filter instance.
     *
     * @param UserServiceInterface $userService
     */
    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if ($this->userService->isSignedIn()) {
            return redirect()->action('Web\IndexController@index');
        }

        return $next($request);
    }
}
