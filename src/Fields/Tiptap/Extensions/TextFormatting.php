<?php

namespace Arniro\Admin\Fields\Tiptap\Extensions;

use ReflectionClass;

class TextFormatting extends Extension
{
    public $component = 'text-formatting';

    const BOLD = 'bold';
    const ITALIC = 'italic';
    const STRIKE = 'strike';
    const UNDERLINE = 'underline';
    const LINK = 'link';

    public function __construct($emphases = null)
    {
        $this->emphases(
            func_get_args() ?: array_values((new ReflectionClass($this))->getConstants())
        );
    }

    public function emphases($emphases)
    {
        $this->value['emphases'] = is_array($emphases) ? $emphases : func_get_args();

        return $this;
    }
}
