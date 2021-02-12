<?php

namespace Arniro\Admin\Http\Resources;

use Illuminate\Support\Str;

class ResourceSuggestions
{
    /**
     * @var Resource
     */
    protected $resource;

    /**
     * @param Resource $resource
     */
    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    /**
     * @param Resource $viaResource
     * @return string
     */
    public function postAttachedMethod($viaResource)
    {
        return Str::camel(class_basename($viaResource)) . 'Attached';
    }

    /**
     * @param Resource $viaResource
     * @return bool
     */
    public function hasPostAttachedMethod($viaResource)
    {
        if (!method_exists($this->resource, $method = $this->postAttachedMethod($viaResource))) {
            return false;
        }

        return $method;
    }
}
