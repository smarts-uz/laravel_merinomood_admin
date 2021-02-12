<?php

namespace Arniro\Admin\Fields;

class Trix extends Text
{
    public $component = 'trix-field';
    public $html = true;
    public $show = [
        'index' => false,
        'detail' => true,
        'edit' => true,
        'create' => true
    ];
}
