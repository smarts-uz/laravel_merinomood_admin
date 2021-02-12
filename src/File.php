<?php

namespace Arniro\Admin;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class File
{
    private $customName;
    private $deleting;

    public function store($dir, $image)
    {
        $fileName = $this->name($dir, $image->getClientOriginalName());
        //Check uploaded image exist on storage

        Storage::putFileAs($dir, $image, $fileName);

        return "$dir/$fileName";
    }

    public function replace($image)
    {
        $this->deleting = $image;

        return $this;
    }

    public function with($dir, $image)
    {
        Storage::delete($this->deleting);

        return $this->store($dir, $image);
    }

    public function as($name)
    {
        $this->customName = $name;

        return $this;
    }

    private function name($dir, $name)
    {
        $extension = pathinfo($name, PATHINFO_EXTENSION);
        $originalName = Str::slug(pathinfo($name, PATHINFO_FILENAME));
        $fileName = $originalName . '.' . $extension;

        $i = 1;
        while (Storage::exists("$dir/$fileName")) {
            $fileName = $originalName . '_' . $i . ($extension ? ('.' . $extension) : ('')); //example: file_1.jpg, file_2.jpg etc.
            $i++;
        }

        return $fileName;
    }
}
