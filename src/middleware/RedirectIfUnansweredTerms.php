<?php

namespace Dialect\Gdpr\middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfUnansweredTerms
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! Auth::user()->accepted_gdpr) {
            return redirect('/show_terms');
        }

        return $next($request);
    }
}
