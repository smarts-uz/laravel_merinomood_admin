<?php

namespace Arniro\Admin\Fields;

class Boolean extends Field
{
    public $component = 'boolean-field';
    public $align = 'center';

    protected function normalize($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
