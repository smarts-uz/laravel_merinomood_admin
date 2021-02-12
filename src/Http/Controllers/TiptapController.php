<?php

namespace Arniro\Admin\Http\Controllers;

use Arniro\Admin\Fields\Tiptap\Extensions\Attachment\AttachmentTypes;
use Arniro\Admin\Models\Attachment;
use Arniro\Admin\File;
use Arniro\Admin\Http\Requests\AdminRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TiptapController
{
    public function storeFile(AdminRequest $request, File $files)
    {
        $file = $request->file('file');

        $filename = $files->store($request->dir, $file);

        $validator = Validator::make($request->all(), [
            'file' => 'image'
        ]);

        $attachment = Attachment::create([
            'attachment' => $file->getClientOriginalName(),
            'url' => Storage::url($filename),
            'type' => $validator->passes() ? AttachmentTypes::IMAGE : AttachmentTypes::FILE,
            'draft_id' => $request->draftId
        ]);

        return compact('attachment');
    }
}
