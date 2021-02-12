<?php

namespace Arniro\Admin\Facades;

use Illuminate\Support\Facades\Facade;

class Resource extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'resource';
    }
}
