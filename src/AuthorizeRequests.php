<?php

namespace Arniro\Admin;

use Arniro\Admin\Http\Resources\Resource;

trait AuthorizeRequests
{
    public static $isAuthorized;

    public static function authorize($request)
    {
        return static::$isAuthorized;
    }

    public static function auth($callback)
    {
        static::$isAuthorized = $callback();
    }

    public function authorizeResource(Resource $resource)
    {
        if (! $resource::authorizable()) {
            return $this;
        }

        $resource->authorize();
    }
}
