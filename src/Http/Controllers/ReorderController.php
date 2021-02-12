<?php

namespace Arniro\Admin\Http\Controllers;

use Arniro\Admin\Http\Requests\AdminRequest;

class ReorderController extends Controller
{
    public function __invoke($resource, AdminRequest $request)
    {
        $model = $request->newModel($resource);

        foreach ($request->input('resources') as $orderedResource) {
            $model = $model->find($orderedResource['id'])
                ->setAttribute('index', $orderedResource['index']);

            $model->save();
        }

        return $request->resource($resource)->fetch();
    }
}
