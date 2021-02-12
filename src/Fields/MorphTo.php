<?php

namespace Arniro\Admin\Fields;

use Arniro\Admin\Http\Requests\AdminRequest;
use Arniro\Admin\Http\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class MorphTo extends Select
{
    public $component = 'morph-to-field';
    public $disabled = false;
    public $attributeType;
    public $attributeId;
    public $types = [];
    public $options = [];

    public $classes = [
        'head' => 'pl-10'
    ];

    /**
     * @var AdminRequest
     */
    protected $request;

    protected function __construct($label, $attribute)
    {
        parent::__construct($label, $attribute);

        $this->attributeId = $attribute . '_id';
        $this->attributeType = $attribute . '_type';

        $this->request = resolve(AdminRequest::class);

        if ($this->request->isViaResource()) {
            $this->disabled = true;
        }
    }

    public function types($types)
    {
        foreach ($types as $type) {
            $this->types[$type::model()] = $type::label();
            $this->options[$type::model()] = $type::newModel()
                ->pluck($type::title(), 'id')->toArray();
        }

        return $this;
    }

    public function fill(?Resource $resource = null)
    {
        $this->value = [
            'attributeType' => $this->request->isViaResource()
                ? $this->request->viaModelClass()
                : $resource->resource()->{$this->attributeType},
            'attributeId' => $this->request->isViaResource()
                ? $this->request->viaResource()->resource()->id
                : $resource->resource()->{$this->attributeId}
        ];

        return $this;
    }

    /**
     * @param Request $request
     * @param Model $model
     * @return mixed
     */
    public function updateModel(Request $request, Model $model)
    {
        $model->{$this->attributeType} = $this->normalize($request[$this->attributeType]);
        $model->{$this->attributeId} = $this->normalize($request[$this->attributeId]);
    }

    /**
     * @param Request $request
     * @param Model $model
     * @return mixed
     */
    public function storeModel(Request $request, Model $model)
    {
        $model->{$this->attributeType} = $this->normalize($request[$this->attributeType]);
        $model->{$this->attributeId} = $this->normalize($request[$this->attributeId]);
    }

    protected function customRuleAttribute()
    {
        return $this->attributeId;
    }
}
