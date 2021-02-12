<?php

namespace Arniro\Admin\Fields;

use Arniro\Admin\Http\Resources\Resource;

class BelongsToMany extends Relationship
{
    public $component = 'belongs-to-many-field';
    protected $innerFields = [];

    public function toResponse(Resource $resource)
    {
        $response = parent::toResponse($resource);

        $resourceResponse = $this->resource::fetch(
            $resource->resource->{$this->attribute}(),
            $resource,
            $this
        );

        $response['resources'] = $resourceResponse;

        return $response;
    }

    public function fields($callback)
    {
        $this->innerFields = $callback();

        return $this;
    }

    public function getExtraFields()
    {
        return $this->innerFields;
    }

    /**
     * @param Resource $resource
     * @return array
     */
    public function getFields(?Resource $resource = null)
    {
        if ($resource) {
            foreach ($this->innerFields as $field) {
                $field->fillPivot($resource);
            }
        }

        return array_merge([
            $this->viaField($resource)
        ], $this->innerFields);
    }

    /**
     * @param Resource $resource
     * @return BelongsTo
     */
    protected function viaField(?Resource $resource = null)
    {
        $field = BelongsTo::make($this->resource::label(), $this->attribute, $this->resource);

        if ($resource) {
            $field->fillWith($resource->resource()->id)->disabled();
        }

        return $field;
    }
}
