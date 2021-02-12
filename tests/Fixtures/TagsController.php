<?php

namespace Arniro\Admin\Tests\Fixtures;

use Arniro\Admin\Http\Controllers\Controller;
use Arniro\Admin\Http\Requests\AdminRequest;

class TagsController extends Controller
{
    public function index()
    {
        return TagResource::fetch();
    }

    public function show($tag)
    {
        $tag = Tag::find($tag);

        return new TagResource($tag);
    }

    public function create()
    {
        return new TagResource;
    }

    public function store(TagResource $resource, AdminRequest $request)
    {
        return $resource->store($request);
    }

    public function update($tag, AdminRequest $request)
    {
        $tag = Tag::find($tag);

        return (new TagResource($tag))->update($request);
    }
}
