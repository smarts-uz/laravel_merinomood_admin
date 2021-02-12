<?php

namespace Arniro\Admin\Http\Requests;

use Arniro\Admin\Http\Resources\Resource;
use Arniro\Admin\Parsers\ResourceParser;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest {
    /** @var ResourceParser */
    protected $parser;

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);

        $this->parser = resolve(ResourceParser::class);
    }

    public function rules()
    {
        return [];
    }

    public function resourceClass($resource = null)
    {
        return $this->parser->class(
            $resource ?: $this->get('resource')
        );
    }

    /**
     * @param null $resource
     * @param null $resourceId
     * @return Resource
     */
    public function resource($resource = null, $resourceId = null)
    {
        return $this->parser->instance(
            $resource ?: $this->get('resource'),
            $resourceId ?: $this->get('resourceId')
        );
    }

    public function newModel($resource = null)
    {
        return $this->resource($resource)->newModel();
    }

    public function model($resource = null, $resourceId = null)
    {
        return $this->resource($resource, $resourceId)->resource();
    }

    public function isViaResource()
    {
        return $this->has('viaResource') && $this->input('viaResource') !== 'undefined';
    }

    /**
     * @param null $viaResource
     * @param null $viaResourceId
     * @return Resource
     */
    public function viaResource($viaResource = null, $viaResourceId = null)
    {
        return $this->parser->instance(
            $viaResource ?: $this->input('viaResource'),
            $viaResourceId ?: $this->input('viaResourceId')
        );
    }

    public function viaModel($viaResource = null, $viaResourceId = null)
    {
        return $this->viaResource($viaResource, $viaResourceId)->resource();
    }

    public function viaModelClass($viaResource = null, $viaResourceId = null)
    {
        return $this->viaResource($viaResource, $viaResourceId)->model();
    }

    /**
     * @param string $viaResource
     * @param string $viaResourceId
     * @return Relation
     */
    public function viaRelationship($viaResource = null, $viaResourceId = null)
    {
        $relationshipMethod = $this->input('viaRelationship');

        if (! $relationshipMethod) return null;

        return $this->viaModel($viaResource, $viaResourceId)->$relationshipMethod();
    }

    public function getJson($key)
    {
       return json_decode($this->input($key), true);
    }
}
