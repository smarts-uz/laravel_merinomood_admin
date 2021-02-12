<?php

namespace Arniro\Admin\Fields;

use Arniro\Admin\Http\Requests\AdminRequest;
use Arniro\Admin\Http\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

class BelongsTo extends Select
{
    public $resource;

    /** @var AdminRequest */
    protected $request;

    protected static $cachedOptions = [];

    protected function __construct($label, $attribute, $resource = null)
    {
        parent::__construct($label, $attribute);

        $this->resource = $resource;

        /** @var $resource Resource */
        $resource = new $this->resource;
        $model = $resource->newModel();

        $this->request = resolve(AdminRequest::class);

        $this->options = $this->getOptions($model, $resource);

        if ($this->shouldBeAutofilled($resource)) {
            $this->disabled = true;
        }
    }

    public static function make($label, $attribute, $resource = null)
    {
        return new static(...func_get_args());
    }

    public function fill(?Resource $resource = null)
    {
        if ($this->customValue) {
            $this->value = $this->customValue;

            return $this;
        }

        $this->value = $this->value ?? $resource->resource()->{$this->getForeignKey($resource->resource())};

        if ($this->shouldBeAutofilled(new $this->resource)) {
            $this->value = $this->request->viaResource()->resource()->id;
        }

        return $this;
    }

    /**
     * @param Request $request
     * @param Model $model
     * @return mixed
     */
    public function storeModel(Request $request, Model $model)
    {
        $model->{$this->getForeignKey($model)} = $request[$this->attribute];
    }

    /**
     * @param Request $request
     * @param Model $model
     * @return mixed
     */
    public function updateModel(Request $request, Model $model)
    {
        $model->{$this->getForeignKey($model)} = $request[$this->attribute];
    }

    /**
     * @param $model Model
     * @return Relation
     */
    protected function getForeignKey($model)
    {
        $method = $this->attribute;

        if (method_exists($model, $method)) {
            return $model->$method()->getForeignKeyName();
        }
    }

    /**
     * Determine whether the field should be disabled or not.
     *
     * @param Resource $resource
     * @return bool
     */
    protected function shouldBeAutofilled(Resource $resource)
    {
        return $this->request->isViaResource() &&
            $this->request->viaResource()->is($resource);
    }

    /**
     * @param $model
     * @param Resource $resource
     * @return array
     */
    protected function getOptions($model, Resource $resource)
    {
        if (! array_key_exists($modelName = class_basename($model), static::$cachedOptions)) {
            $options = $model->pluck($resource->title(), 'id')->toArray();

            static::$cachedOptions[$modelName] = [];

            foreach ($options as $id => $label) {
                static::$cachedOptions[$modelName][$id] = [
                    'label' => $label,
                    'link' => "/{$resource->resourceName()}/{$id}",
                ];
            }
        }

        return static::$cachedOptions[$modelName];
    }

    public static function clearCachedOptions()
    {
        static::$cachedOptions = [];
    }
}
