<?php

namespace Arniro\Admin\Tests\Fixtures;

use Arniro\Admin\Fields\MorphTo;
use Arniro\Admin\Fields\Textarea;
use Arniro\Admin\Http\Resources\Resource;

class CommentResource extends Resource
{
    public static $model = 'Arniro\\Admin\\Tests\\Fixtures\\Comment';

    public static $search = ['id'];

    public function fields()
    {
        return [
            Textarea::make('Body', 'body'),

            MorphTo::make('At', 'commentable')->types([
                PostResource::class
            ])->rules('required')
        ];
    }
}
