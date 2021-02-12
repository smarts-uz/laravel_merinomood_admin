<?php

namespace Arniro\Admin\Fields\Tiptap\Extensions;

class Headings extends Extension
{
    public $component = 'headings';

    public function __construct($headings = [1, 2, 3])
    {
        $this->value['headings'] = is_array($headings) ? $headings : func_get_args();
    }
}
