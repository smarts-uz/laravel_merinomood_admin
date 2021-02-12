<?php

namespace Arniro\Admin\Fields;

use Arniro\Admin\Http\Resources\Resource;
use Arniro\Admin\Media\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Gallery extends File
{
    public $component = 'gallery-field';
    protected $storing = false;

    protected function __construct($label)
    {
        $attribute = 'media';

        parent::__construct($label, $attribute);
    }

    public static function make($label, $attribute = null)
    {
        return new static(...func_get_args());
    }

    /**
     * @param Request $request
     * @param Model $model
     * @return mixed
     */
    public function updateModel(Request $request, Model $model)
    {
        $images = $request->{$this->attribute} ?? [];

        foreach ($model->media as $image) {
            Storage::delete($image->src);
            Media::where('mediable_id', '=', $model->id)->delete();
        }
        foreach ($images as $image) {

            $model->media()->create([
                'src' => $this->files->store($this->dir, $image)
            ]);
        }
    }

    /**
     * @param Request $request
     * @param Model $model
     * @return mixed
     */
    public function storeSavedModel(Request $request, Model $model)
    {
        $images = $request->{$this->attribute} ?? [];

        foreach ($images as $image) {
            $model->media()->create([
                'src' => $this->files->store($this->dir, $image)
            ]);
        }
    }

    public function getValue(Resource $resource)
    {
        return $resource->media;
    }
}
