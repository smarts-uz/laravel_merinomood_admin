<?php

namespace Arniro\Admin\Support;

use Illuminate\Support\Collection as BaseCollection;

class Collection extends BaseCollection
{
    public function toArray()
    {
        return json_decode(json_encode($this->items), true);
    }
}
