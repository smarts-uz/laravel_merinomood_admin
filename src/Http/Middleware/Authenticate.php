<?php

namespace Arniro\Admin\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        if (auth()->check()) {
            return $next($request);
        }

        throw new AuthenticationException(
            'Unauthenticated.', $guards, route('admin.login')
        );
    }
}