<?php

namespace Arniro\Admin;

use Illuminate\Support\Facades\File;

class UI
{
    static protected $variables = [];

    public function initialize()
    {
        static::$variables = [
            'user' => auth()->user(),
            'locales' => config('app.locales'),
            'locale' => config('app.locale'),
            'environment' => config('app.env'),
            'appUrl' => config('app.url'),
            'translations' => $this->getTranslations(),
            'optimizeImages' => null
        ];
    }

    public function addVariable($key, $value)
    {
        static::$variables[$key] = $value;

        return $this;
    }

    public static function getVariables()
    {
        return static::$variables;
    }

    protected function getTranslations()
    {
        $translationFile = base_path('admin/resources/lang/' . app()->getLocale() . '.json');

        if (! File::exists($translationFile)) {
            return [];
        }

        return json_decode(file_get_contents($translationFile), true);
    }
}

