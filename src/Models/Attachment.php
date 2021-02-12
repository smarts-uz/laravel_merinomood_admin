<?php

namespace Arniro\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $table = 'tiptap_attachments';

    protected $fillable = [
        'attachable_type',
        'attachable_id',
        'attachment',
        'url',
        'type',
        'draft_id'
    ];
}
