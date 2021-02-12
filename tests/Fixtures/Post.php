<?php

namespace Arniro\Admin\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Post extends Model
{
    use HasTranslations;

    protected $fillable = ['name'];

    public $translatable = ['name', 'surname'];

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
