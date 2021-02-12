<?php

namespace Arniro\Admin\Http\Middleware;

use Arniro\Admin\Events\ServingAdmin;
use Closure;

class ServeAdmin
{
    public function handle($request, Closure $next)
    {
        ServingAdmin::dispatch();

        return $next($request);
    }
}
