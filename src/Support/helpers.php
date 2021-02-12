<?php

use Arniro\Admin\Admin;
use Arniro\Admin\Http\Requests\AdminRequest;

if (! function_exists('admin')) {
    function admin() {
        return new Admin;
    }
}

if (! function_exists('resource')) {
    function resource($resource = null, $resourceId = null) {
        return resolve(AdminRequest::class)->resource($resource, $resourceId);
    }
}
