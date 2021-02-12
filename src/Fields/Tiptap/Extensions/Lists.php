<?php

namespace Arniro\Admin\Fields\Tiptap\Extensions;

use ReflectionClass;

class Lists extends Extension
{
    protected $component = 'lists';

    const UNORDERED = 'unordered';
    const ORDERED = 'ordered';
    const TODO = 'todo';

    protected $lists = [];

    public function __construct($lists = null)
    {
        $this->types(
            func_get_args() ?: array_values((new ReflectionClass($this))->getConstants())
        );
    }

    public function types($lists)
    {
        $this->value['lists'] = is_array($lists) ? $lists : func_get_args();

        return $this;
    }
}
