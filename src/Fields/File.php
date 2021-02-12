<?php

namespace Arniro\Admin\Fields;

use Arniro\Admin\File as Files;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class File extends Field
{
    public $component = 'file-field';
    protected $dir = '';

    /**
     * @var Files
     */
    protected $files;

    protected function __construct($label, $attribute)
    {
        parent::__construct($label, $attribute);

        $this->files = new Files;
    }

    public function dir($dir)
    {
        $this->dir = $dir;

        return $this;
    }

    /**
     * @param Request $request
     * @param Model $model
     * @return void
     */
    public function updateModel(Request $request, Model $model)
    {
        $currentFile = $model->getAttribute($this->attribute);

        if (! $value = $request->input($this->attribute)) {
            Storage::delete($currentFile);
        }

        if ($file = $request->file($this->attribute)) {
            $value = $this->files->replace($currentFile)
                ->with($this->dir, $file);
        }

        $model->setAttribute($this->attribute, $value);
    }

    /**
     * @param Request $request
     * @param Model $model
     * @return void
     */
    public function storeModel(Request $request, Model $model)
    {
        if (! $request->file($this->attribute)) return;

        $model->setAttribute(
            $this->attribute,
            $this->files->store(
                $this->dir,
                $request->file($this->attribute)
            )
        );
    }
}
