<?php

namespace Arniro\Admin\Fields\Tiptap;

use Arniro\Admin\Fields\Field;
use Arniro\Admin\Fields\Tiptap\Extensions\Extension;
use Arniro\Admin\Http\Requests\AdminRequest;
use Arniro\Admin\Http\Resources\Resource;
use Arniro\Admin\Models\Attachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Tiptap extends Field
{
    public $component = 'tiptap-field';

    public $show = [
        'index' => false,
        'detail' => true,
        'edit' => true,
        'create' => true
    ];

    public $draftId;
    public $extensions;

    public function extensions($extensions)
    {
        $this->extensions = is_array($extensions) ? $extensions : func_get_args();

        return $this;
    }

    public function withTrailingLine()
    {
        $this->extensions[] = (new Extension)->component('trailing-node');

        return $this;
    }

    public function toResponse(Resource $resource)
    {
        $this->extensions = collect($this->extensions)
            ->map->toResponse()->toArray();

        $this->draftId = Str::uuid();

        return parent::toResponse($resource);
    }

    public function storeSavedModel(AdminRequest $request, Model $model)
    {
        $this->updateAttachments($request, $model);
    }

    public function updateModel(AdminRequest $request, Model $model)
    {
        $model->{$this->attribute} = $request->input($this->attribute);

        $this->updateAttachments($request, $model);
    }

    public function destroyModel(AdminRequest $request, Model $model)
    {
        Attachment::where([
            'attachable_type' => get_class($model),
            'attachable_id' => $model->getKey()
        ])->get()->each(function ($attachment) {
            Storage::delete($attachment->url);

            $attachment->delete();
        });
    }

    /**
     * @param AdminRequest $request
     * @param Model $model
     */
    protected function updateAttachments(AdminRequest $request, Model $model): void
    {
        Attachment::where('draft_id', $request->input($this->attribute . '_draft'))
            ->update([
                'draft_id' => null,
                'attachable_type' => get_class($model),
                'attachable_id' => $model->getKey()
            ]);
    }
}
