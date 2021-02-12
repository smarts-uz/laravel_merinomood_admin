<?php

namespace Arniro\Admin\Http\Middleware;

use Arniro\Admin\Admin;
use Closure;

class Authorize
{
    public function handle($request, Closure $next, ...$guards)
    {
        return Admin::authorize($request) ? $next($request) : abort(403);
    }
}
