<?php

namespace Arniro\Admin\Http\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait PerformsValidation
{
    /**
     * @var bool
     */
    protected $shouldValidate = true;

    /**
     * Disable validation for before persisting to the database
     *
     * @return $this
     */
    public function withoutValidation()
    {
        $this->shouldValidate = false;

        return $this;
    }

    /**
     * Validate the request for resource
     *
     * @param Request $request
     * @param Model|null $model
     * @return $this
     */
    public function validate(Request $request, ?Model $model = null)
    {
        if ($this->isControllerMethod('store')) {
            return $this->validateForCreate($request);
        }

        if ($this->isControllerMethod('update')) {
            return $this->validateForUpdate($request, $model);
        }

        return $this;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function validateForCreate(Request $request)
    {
        $request->validate(
            $this->getCreationRules(),
            $this->getCreationMessages()
        );

        return $this;
    }

    /**
     * @param Request $request
     * @param Model $model
     * @return $this
     */
    public function validateForUpdate(Request $request, Model $model)
    {
        $request->validate(
            $this->getUpdateRules($model),
            $this->getUpdateMessages()
        );

        return $this;
    }

    /**
     * Get validation rules for resource creation
     *
     * @return array
     */
    protected function getCreationRules()
    {
        return $this->getRules('creation');
    }

    /**
     * Get validation rules for resource update
     *
     * @param $model Model
     * @return array
     */
    protected function getUpdateRules(Model $model)
    {
        return $this->getRules('update', $model);
    }

    protected function getRules($type, $model = null)
    {
        $rules = [];
        $method = $this->getValidationMethod($type, 'Rules');

        foreach ($this->fields() as $field) {
            $rules[$field->ruleAttribute($this)] = $field->$method($model);

            foreach ($field->getLocalesRules() as $locale => $localeRules) {
                $rules["$field->attribute.$locale"] = $localeRules;
            }
        }

        return $rules;
    }

    /**
     * Get validation rules messages for resource creation
     *
     * @return array
     */
    protected function getCreationMessages()
    {
        return $this->getMessages('creation');
    }

    /**
     * Get validation rules messages for resource update
     *
     * @return array
     */
    protected function getUpdateMessages()
    {
        return $this->getMessages('update');
    }

    protected function getMessages($type)
    {
        $messages = [];
        $method = $this->getValidationMethod($type, 'Messages');

        foreach ($this->fields() as $field) {
            $messages = array_merge($messages, $field->$method($this), $field->getLocalesMessages($this));
        }

        return $messages;
    }

    /**
     * Determine the validation method
     *
     * @param $method string
     * @param $type string
     * @return string
     */
    protected function getValidationMethod($method, $type)
    {
        return 'get' . Str::camel($method) . $type;
    }

    /**
     * Determine the controller method the validation has been triggered from
     *
     * @param $method
     * @return bool
     */
    protected function isControllerMethod($method)
    {
        return debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3)[2]['function'] === $method;
    }
}
