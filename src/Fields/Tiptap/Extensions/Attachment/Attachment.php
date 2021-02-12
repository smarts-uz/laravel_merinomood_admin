<?php

namespace Arniro\Admin\Fields\Tiptap\Extensions\Attachment;

use Arniro\Admin\Fields\Tiptap\Extensions\Extension;

class Attachment extends Extension
{
    protected $component = 'attachment';

    public function __construct($dir = 'tiptap-uploads')
    {
        $this->dir($dir);
    }

    public function dir($dir)
    {
        $this->value['dir'] = $dir;

        return $this;
    }
}
