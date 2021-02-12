<?php

namespace Arniro\Admin\Fields;

use Arniro\Admin\Http\Resources\Resource;
use Arniro\Admin\Parsers\ValidationRulesParser;

trait ValidationRules
{
    protected $rules = [];
    protected $creationRules = [];
    protected $updateRules = [];

    protected $messages = [];
    protected $creationMessages = [];
    protected $updateMessages = [];

    protected $localesRules = [];
    protected $localesMessages = [];

    public function rules(...$rules)
    {
        $this->rules = $this->rulesParser($rules)->gather();
        $this->messages = $this->rulesParser($rules)->getMessageables();

        return $this;
    }

    public function updateRules(...$rules)
    {
        $this->updateRules = $this->rulesParser($rules)->gather();
        $this->updateMessages = $this->rulesParser($rules)->getMessageables();

        return $this;
    }

    public function creationRules(...$rules)
    {
        $this->creationRules = $this->rulesParser($rules)->gather();
        $this->creationMessages = $this->rulesParser($rules)->getMessageables();

        return $this;
    }

    /**
     * Specify rules for specific locales
     *
     * @param array $localesRules
     * @return $this
     */
    public function localesRules($localesRules)
    {
        foreach ($localesRules as $locale => $rules) {
            $this->localesRules[$locale] = $this->rulesParser([$rules])->gather();
            $this->localesMessages[$locale] = $this->rulesParser([$rules])->getMessageables();
        }

        return $this;
    }

    public function getCreationRules()
    {
        return collect($this->rules)->merge($this->creationRules)->toArray();
    }

    public function getUpdateRules($model = null)
    {
        return ValidationRulesParser::format(
            collect($this->rules)->merge($this->updateRules),
            $model
        )->toArray();
    }

    public function getLocalesRules()
    {
        return $this->localesRules;
    }

    public function getCreationMessages(?Resource $resource = null)
    {
        return $this->messages(
            array_merge($this->messages, $this->creationMessages),
            $resource
        );
    }

    public function getUpdateMessages(?Resource $resource = null)
    {
        return $this->messages(
            array_merge($this->messages, $this->updateMessages),
            $resource
        );
    }

    public function getLocalesMessages(?Resource $resource = null)
    {
        $messages = [];

        foreach ($this->localesMessages as $locale => $localeMessages) {
            foreach ($localeMessages as $rule => $message) {
                $messages["$this->attribute.$locale.$rule"] = $message;
            }
        }

        return $messages;
    }

    protected function messages($rawMessages, ?Resource $resource = null)
    {
        $messages = [];

        if ($resource && $this->isTranslatable($resource)) {
            return $this->getTranslationsMessages($rawMessages);
        }

        foreach ($rawMessages as $rule => $message) {
            $messages[$this->messageAttribute($rule)] = $message;
        }

        return $messages;
    }

    protected function getTranslationsMessages($rawMessages)
    {
        $messages = [];

        foreach ($rawMessages as $rule => $message) {
            $messages[$this->messageAttribute($rule, true)] = $message;
        }

        return $messages;
    }

    protected function messageAttribute($rule, $locale = false)
    {
        if ($locale) {
            $locale = '*.';
        }

        return $this->attribute . '.' . $locale . $rule;
    }

    /**
     * @return string
     */
    protected function customRuleAttribute()
    {
        return $this->attribute;
    }

    public function ruleAttribute(Resource $resource)
    {
        return $this->isTranslatable($resource)
            ? $this->customRuleAttribute(). '.*'
            : $this->customRuleAttribute();
    }

    public function rulesParser($rules)
    {
        return new ValidationRulesParser($this, $rules);
    }
}
