<?php

namespace Arniro\Admin\Http\Controllers;

use Arniro\Admin\Http\Requests\AdminRequest;
use Illuminate\Support\Arr;

class ResourcesController extends Controller
{
    public function index($resource, AdminRequest $request)
    {
        return $request->resource($resource)->authorize()->fetch();
    }

    public function create($resource, AdminRequest $request)
    {
        return $request->resource($resource)->authorize();
    }

    public function store($resource, AdminRequest $request)
    {
        return $request->resource($resource)->authorize()->store($request);
    }

    public function show($resource, $id, AdminRequest $request)
    {
        return $request->resource($resource, $id)->authorize();
    }

    public function edit($resource, $id, AdminRequest $request)
    {
        return $request->resource($resource, $id)->authorize();
    }

    public function update($resource, $id, AdminRequest $request)
    {
        return $request->resource($resource, $id)->authorize()->update($request);
    }

    public function destroy($resource, $ids, AdminRequest $request)
    {
        $ids = Arr::wrap(explode(',', $ids));

        $request->newModel($resource)->whereIn('id', $ids)
            ->get()->map(function ($model) use ($request) {
                $resourceClass = $request->resourceClass();

                return new $resourceClass($model);
            })->each->destroy();

        return $request->resource($resource)->fetch(
            $request->isViaResource() ? $request->viaRelationship() : null
        );
    }
}
