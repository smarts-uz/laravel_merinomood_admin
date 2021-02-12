<?php

namespace Arniro\Admin;

use Arniro\Admin\Events\ServingAdmin;
use Arniro\Admin\Http\Requests\AdminRequest;
use Illuminate\Support\Facades\Event;

class Admin
{
    use AuthorizeRequests;

    public static function routes()
    {
        return new RouteRegistration;
    }

    public static function serving($callback)
    {
       Event::listen(ServingAdmin::class, $callback);
    }

    public static function ui()
    {
        return new UI;
    }

    /**
     * @return AdminRequest
     */
    public function request()
    {
        return resolve(AdminRequest::class);
    }
}
