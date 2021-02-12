<?php

namespace Arniro\Admin\Tests\Fixtures;

use Arniro\Admin\Fields\Text;
use Arniro\Admin\Http\Resources\Resource;

class PostResource extends Resource
{
    public static $model = 'Arniro\\Admin\\Tests\\Fixtures\\Post';

    public static $search = ['id'];
    public static $title = 'name';

    public function fields()
    {
        return [
            Text::make('Surname', 'surname')->localesRules([
                'ru' => [
                    'required' => 'This is required.'
                ]
            ]),

            Text::make('Название', 'name')->rules(['required' => 'This is required.']),
        ];
    }
}
