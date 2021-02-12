<?php

namespace Arniro\Admin\Fields;

use Arniro\Admin\Http\Resources\Resource;
use Closure;
use Illuminate\Support\Arr;
use ReflectionObject;
use ReflectionProperty;

class Field
{
    use HandlesVisibility, ValidationRules, FieldPersisting;

    public $label;
    public $attribute;
    public $component;
    public $translatable = false;
    public $sortable = false;
    public $align = 'left';
    public $when = [];
    public $classes = [
        'head' => '',
        'body' => ''
    ];
    public $value;
    public $showLocales = true;

    protected $pivot = false;

    protected $customValue;
    protected $needsModel = false;

    protected function __construct($label, $attribute)
    {
        $this->label = $label;
        $this->attribute = $attribute;
    }

    public static function make($label, $attribute)
    {
        return new static(...func_get_args());
    }

    public function sortable()
    {
        $this->sortable = true;

        return $this;
    }

    public function align($align)
    {
        $this->align = $align;

        return $this;
    }

    /**
     * @param string $classes
     * @return $this
     */
    public function headClasses($classes)
    {
        $this->classes['head'] = $classes;

        return $this;
    }

    /**
     * @param string $classes
     * @return $this
     */
    public function bodyClasses($classes)
    {
        $this->classes['body'] = $classes;

        return $this;
    }

    public function getValue(Resource $resource)
    {
        if ($this->isTranslatable($resource)) {
            return $this->getValueTranslations($resource);
        }

        return $resource->resource()->getAttribute($this->attribute);
    }

    public function isTranslatable(Resource $resource)
    {
        return $resource->resource()->translatable &&
            $resource->resource()->isTranslatableAttribute($this->attribute);
    }

    protected function getValueTranslations(Resource $resource)
    {
        $translations = [];

        foreach (config('app.locales', []) as $locale => $label) {
            $translations[$locale] = $resource->resource()->getTranslationWithoutFallback($this->attribute, $locale);
        }

        return $translations;
    }

    /**
     * Prepares the field for response.
     *
     * @param Resource $resource
     * @return array
     */
    public function toResponse(Resource $resource)
    {
        $this->fill($resource);

        $this->translatable = $this->isTranslatable($resource);

        $field = [];

        foreach ($this->getPublicProperties() as $property) {
            $field[$property->name] = $property->getValue($this);
        }

        return $field;
    }

    protected function getPublicProperties()
    {
        return (new ReflectionObject($this))->getProperties(ReflectionProperty::IS_PUBLIC);
    }

    public function resolveUsing(Closure $callback)
    {
        $this->customValue = $callback;

        return $this;
    }

    public function fill(?Resource $resource = null)
    {
        $customValueCallback = $this->customValue ?: function () {};

        $this->value = app()->call($customValueCallback) ?: $this->getValue($resource);

        return $this;
    }

    public function fillPivot(Resource $resource)
    {
        $this->value = $resource->resource()->pivot->{$this->attribute};

        return $this;
    }

    public function fillWith($value)
    {
        $this->value = $value;

        return $this;
    }

    public function when($attribute, $value = null)
    {
        $this->when[$attribute] = $value ? Arr::wrap($value) : $value;

        return $this;
    }

    public function pivot()
    {
        $this->pivot = true;

        return $this;
    }

    /**
     * Format the value before persisting to the database.
     *
     * @param $value
     * @return mixed
     */
    protected function normalize($value)
    {
        return $value;
    }

    /**
     * @param $callback
     * @return $this
     */
    public function canSeeLocales($callback)
    {
        $this->showLocales = !! ($callback instanceof Closure ? app()->call($callback) : $callback);

        return $this;
    }
}
