<?php

namespace Arniro\Admin\Tests\Fixtures;

use Arniro\Admin\Fields\BelongsTo;
use Arniro\Admin\Fields\BelongsToMany;
use Arniro\Admin\Fields\Text;
use Arniro\Admin\Http\Resources\Resource;

class ProductResource extends Resource
{
    public static $model = 'Arniro\\Admin\\Tests\\Fixtures\\Product';

    public static $search = ['name', 'price'];

    public static $paginate = 10;

    public function fields()
    {
        return [
            Text::make('Название', 'name'),
            Text::make('Цена', 'price'),
            BelongsTo::make('Пользователь', 'user', UserResource::class),

            BelongsToMany::make('tags', TagResource::class)->fields(function () {
                return [
                    Text::make('Note', 'note')->rules('required')
                ];
            })->label('Custom tags label')
        ];
    }

    public function tagResourceAttached()
    {
        $this->resource->name = 'changed';
        $this->resource->save();
    }
}
