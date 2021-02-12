<?php

namespace Arniro\Admin\Tests\Fixtures;

use Arniro\Admin\Http\Controllers\Controller;
use Arniro\Admin\Http\Requests\AdminRequest;

class PostsController extends Controller
{
    public function index()
    {
        return PostResource::fetch();
    }

    public function show($post)
    {
        $post = Post::find($post);

        return new PostResource($post);
    }

    public function create()
    {
        return new PostResource;
    }

    public function store(PostResource $resource, AdminRequest $request)
    {
        return $resource->store($request);
    }

    public function update($post, AdminRequest $request)
    {
        $post = Post::find($post);

        return (new PostResource($post))->update($request);
    }
}
