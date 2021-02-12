<?php

namespace Arniro\Admin\Fields;

use Arniro\Admin\Http\Resources\ResourceView;

class Relationship extends Field
{
    public $label;
    public $resource;
    protected $query;

    protected $show = [
        ResourceView::INDEX => false,
        ResourceView::DETAIL => true,
        ResourceView::CREATE => false,
        ResourceView::EDIT => false
    ];

    protected function __construct($relationship, $resource)
    {
        $this->attribute = $relationship;
        $this->resource = $resource;
    }

    public static function make($relationship, $resource)
    {
        return new static(...func_get_args());
    }

    public function label($label)
    {
        $this->label = $label;

        return $this;
    }

    public function query($query)
    {
        $this->query = $query;

        return $this;
    }
}
