<?php

namespace Arniro\Admin\Parsers;

use Illuminate\Support\Str;

class ResourceParser
{
    protected static $namespace;

    public function class($resource)
    {
        $resource = Str::studly(Str::singular($resource));

        return $this->namespace() . $resource;
    }

    public function instance($resource, $resourceId = null)
    {
        $class = $this->class($resource);

        return new $class($resourceId ? $class::newModel()->find($resourceId) : null);
    }

    public function model($resource, $resourceId)
    {
        return $this->instance($resource, $resourceId)->resource();
    }

    public static function setNamespace($namespace)
    {
        static::$namespace = $namespace;
    }

    public function namespace()
    {
        return static::$namespace ?: app()->getNamespace() . 'Admin\\';
    }
}
