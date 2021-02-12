<?php

namespace Arniro\Admin\Fields;

class CropImage extends File
{
    public $component = 'crop-image-field';
    public $ratio;

    public function ratio($ratio) {
        $this->ratio = $ratio;

        return $this;
    }
}
