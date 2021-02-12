<?php

namespace Arniro\Admin\Tests\Fixtures;

use Arniro\Admin\Fields\HasMany;
use Arniro\Admin\Fields\Password;
use Arniro\Admin\Fields\Text;
use Arniro\Admin\Http\Resources\Resource;

class UserResource extends Resource
{
    public static $model = 'Arniro\\Admin\\Tests\\Fixtures\\User';
    public static $search = ['name'];

    public static $title = 'name';

    public function fields()
    {
        return [
            Text::make('Name', 'name')->creationRules([
                'required' => 'You need to provide the name while creating.'
            ])->updateRules([
                'required' => 'You need to provide the name while updating.'
            ]),
            Text::make('Email', 'email')->rules('required', 'email'),
            Password::make('Password', 'password')->rules('required'),
            HasMany::make('products', ProductResource::class)
        ];
    }
}
