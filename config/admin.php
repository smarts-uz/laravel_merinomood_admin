<?php

use Arniro\Admin\Http\Middleware\Authenticate;
use Arniro\Admin\Http\Middleware\Authorize;
use Arniro\Admin\Http\Middleware\ServeAdmin;

return [
    'middleware' => [
        'web',
        Authenticate::class,
        ServeAdmin::class,
        Authorize::class,
    ]
];
