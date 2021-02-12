<?php

namespace Arniro\Admin\Tests\Fixtures;

use Arniro\Admin\Http\Controllers\Controller;
use Arniro\Admin\Http\Requests\AdminRequest;

class UsersController extends Controller
{
    public function index()
    {
        return UserResource::fetch();
    }

    public function show($user)
    {
        $user = User::find($user);

        return new UserResource($user);
    }

    public function create()
    {
        return new UserResource;
    }

    public function store(UserResource $resource, AdminRequest $request)
    {
        return $resource->store($request);
    }

    public function update($user, AdminRequest $request)
    {
        $user = User::find($user);

        return (new UserResource($user))->update($request);
    }
}
