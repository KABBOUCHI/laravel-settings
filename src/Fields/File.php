<?php

namespace KABBOUCHI\Settings\Fields;

use Illuminate\Http\UploadedFile;

class File extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'file-field';

    public $disk = null;

    public $storagePath = '/';

    public function disk($disk)
    {
        $this->disk = $disk;

        return $this;
    }

    public function path($path)
    {
        $this->storagePath = $path;

        return $this;
    }

    public function meta()
    {
        return array_merge(['disk' => $this->disk], $this->meta);
    }

    public function store(UploadedFile $file)
    {
        return $file->store($this->storagePath, $this->disk);
    }
}
