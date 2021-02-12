<?php

namespace Arniro\Admin\Tests\Fixtures;

use Arniro\Admin\Fields\Text;
use Arniro\Admin\Http\Requests\AdminRequest;
use Arniro\Admin\Http\Resources\Resource;
use Illuminate\Support\Facades\Storage;

class TagResource extends Resource
{
    public static $model = 'Arniro\\Admin\\Tests\\Fixtures\\Tag';

    public static $search = ['id'];
    public static $title = 'name';

    public function fields()
    {
        return [
            Text::make('Название', 'name')
                ->storeVia(function (AdminRequest $request, Tag $model) {
                    $model->name = 'bar';
                })->updateVia(function (AdminRequest $request, Tag $model) {
                    $model->name = 'bar';
                }),

            Text::make('Note', 'note')
                ->storeSavedVia(function (AdminRequest $request, Tag $model) {
                    $model->note = $request->note . $model->id;
                    $model->save();
                })->destroyVia(function (Tag $model) {
                    Storage::delete($model->note);
                })
        ];
    }
}
