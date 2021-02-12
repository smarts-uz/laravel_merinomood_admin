<?php

namespace Arniro\Admin\Parsers;

use Arniro\Admin\Fields\Field;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ValidationRulesParser
{
    /** @var Field */
    protected $field;
    protected $rules;

    public function __construct($field, $rules = [])
    {
        $this->field = $field;
        $this->rules = $this->normalize($rules);
    }

    public function normalize($rules)
    {
        if (func_num_args() === 1 && is_array($rules[0])) {
            $rules = $rules[0];
        }

        return Arr::wrap($rules);
    }

    public function gather()
    {
        return array_merge(
            $this->getRaw(),
            array_keys($this->getMessageables())
        );
    }

    /**
     * Get validation rules which doesn't have custom messages
     *
     * @return array
     */
    public function getRaw()
    {
        return array_filter($this->rules, function ($key) {
            return is_numeric($key);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Get validation rules which have custom messages
     *
     * @return array
     */
    public function getMessageables()
    {
        return array_filter($this->rules, function ($key) {
            return !is_numeric($key);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Get messages attributes
     *
     * @return array
     */
    public function getMessageKeys()
    {
        return array_map(function ($key) {
            return $this->field->attribute . '.ru.' . $key;
        }, array_keys($this->getMessageables()));
    }

    public static function format(Collection $rules, $model = null)
    {
        if (!$model)
            return $rules;

        return $rules->map(function ($rule) use ($model) {
            $rule = str_replace('{{resourceId}}', $model->id, $rule);

            return $rule;
        });
    }

    public function getRules()
    {
        return $this->rules;
    }
}
