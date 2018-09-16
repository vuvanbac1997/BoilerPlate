<?php

namespace App\Http\Middleware;

use Closure;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next)
    {
        // locale by sub-domain
        $locale = \LocaleHelper::getLocaleSubDomain();
        // if not, redirect to sub-domain

        if (config('locale.isSubdomainEnabled')) {
            if (is_null($locale)) {
                return $this->redirect();
            } else {
                // if have, set locale
                \App::setLocale($locale);
            }
        } else {
            $locale = \LocaleHelper::getLocale();
            \Session::put('locale', $locale);
            \App::setLocale($locale);
        }

        return $next($request);
    }

    /**
     * redirect to sub-domain.
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    private function redirect()
    {
        $code = \LocaleHelper::getLocale();

        $domain = config('locale.domains.'.$code, false);

        if (!$domain) {
            throw new \Exception("Need config domain for code $code");
        }
        $domain = rtrim($domain, '/');
        $path = (\Request::getPathInfo().(\Request::getQueryString() ? ('?'.\Request::getQueryString()) : ''));
        $redirectUrl = \Request::getScheme().'://'.$domain.'/'.ltrim($path, '/');

        return \Redirect::to($redirectUrl);
    }
}
