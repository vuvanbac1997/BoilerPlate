<?php
namespace App\Http\Middleware;

use Closure;

class SecurePath
{
    /**
     * ELB-HealthChecker
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $ua = $request->header('User-Agent');
        if (app()->environment('production') && !\Request::secure() && strpos($ua, 'ELB-HealthChecker') === false) {
            // The environment is production
            return \Redirect::secure(\Request::path());
        }

        return $next($request);
    }
}
