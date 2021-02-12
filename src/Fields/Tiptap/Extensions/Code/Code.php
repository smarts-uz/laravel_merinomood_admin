<?php

namespace Arniro\Admin\Fields\Tiptap\Extensions\Code;

use Arniro\Admin\Fields\Tiptap\Extensions\Extension;

class Code extends Extension
{
    public $component = 'code';

    public function __construct($languages = [])
    {
        if (!is_array($languages)) {
            $languages = func_get_args();
        }

        $this->value['languages'] = Languages::get($languages);
        $this->value['theme'] = Themes::DEFAULT;
    }

    public function theme($theme)
    {
        $this->value['theme'] = $theme;

        return $this;
    }
}
