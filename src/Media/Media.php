<?php

namespace Arniro\Admin\Media;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'media';
    protected $fillable = [
        'src','mediable_id','mediable_type',
    ];

    public function mediable()
    {
        return $this->morphTo();
    }
}
