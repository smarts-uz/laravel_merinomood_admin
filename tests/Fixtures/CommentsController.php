<?php

namespace Arniro\Admin\Tests\Fixtures;

use Arniro\Admin\Http\Controllers\Controller;
use Arniro\Admin\Http\Requests\AdminRequest;

class CommentsController extends Controller
{
    public function index()
    {
        return CommentResource::fetch();
    }

    public function show($post)
    {
        $post = Comment::find($post);

        return new CommentResource($post);
    }

    public function create()
    {
        return new CommentResource;
    }

    public function store(CommentResource $resource, AdminRequest $request)
    {
        return $resource->store($request);
    }

    public function update($comment, AdminRequest $request)
    {
        $comment = Comment::find($comment);

        return (new CommentResource($comment))->update($request);
    }
}
