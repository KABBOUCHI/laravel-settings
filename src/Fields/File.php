<?php

namespace KABBOUCHI\Settings\Fields;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class File extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'file-field';

    public $disk;

    public $storagePath = '/';

    protected $translatable = false;

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
        return array_merge([], $this->meta);
    }

    public function store(UploadedFile $file)
    {
        return $file->store($this->storagePath, $this->disk);
    }
}
