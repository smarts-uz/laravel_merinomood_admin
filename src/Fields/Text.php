<?php

namespace Arniro\Admin\Fields;

class Text extends Field
{
    public $component = 'text-field';
    public $html = false;
    public $limit = false;

    public function asHtml()
    {
        $this->html = true;

        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;

        return $this;
    }
}
