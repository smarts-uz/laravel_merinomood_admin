<?php

namespace Arniro\Admin\Fields;

use Illuminate\Support\Arr;

class Select extends Field
{
    public $component = 'select-field';
    public $options = [];
    public $disabled = false;

    /**
     * Make taken options normalized and give the
     * default values to empty or absent elements.
     *
     * @param array $options
     * @return $this
     */
    public function options($options = [])
    {
        foreach ($this->normalizeOptions($options) as $key => $value) {
            $this->options[$key] = [
                'label' => Arr::get($value, 'label', $value),
                'color' => Arr::get($value, 'color'),
            ];
        }

        return $this;
    }

    public function disabled()
    {
        $this->disabled = true;

        return $this;
    }

    /**
     * Convert a simple array to an associative array if it's not.
     *
     * @param $array
     * @return array
     */
    protected function normalizeOptions($array)
    {
        if (!Arr::isAssoc($array)) {
            return array_combine($array, $array);
        }

        return $array;
    }
}
