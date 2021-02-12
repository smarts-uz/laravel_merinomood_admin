<?php

namespace Arniro\Admin\Fields;

class Number extends Field
{
    public $component = 'number-field';
    public $step = 1;
    public $min;
    public $max;

    public function step($step)
    {
        $this->step = $step;

        return $this;
    }

    public function min($min)
    {
        $this->min = $min;

        return $this;
    }

    public function max($max)
    {
        $this->max = $max;

        return $this;
    }
}
