<?php

namespace Arniro\Admin\Fields\Tiptap\Extensions;

class Extension
{
    protected $component;
    protected $value;

    public function component($component)
    {
        $this->component = $component;

        return $this;
    }

    public function toResponse()
    {
        return [
            'component' => $this->component,
            'value' => $this->value
        ];
    }
}
