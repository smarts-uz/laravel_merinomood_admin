<?php

namespace Arniro\Admin\Fields;

use Arniro\Admin\Http\Resources\Resource;

class HasMany extends Relationship
{
    public $component = 'has-many-field';

    public function fill(?Resource $resource = null)
    {
        $this->value = $this->resource::fetch($resource->resource()->{$this->attribute}(), $resource);
    }
}
