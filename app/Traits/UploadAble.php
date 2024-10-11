<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait UploadAble
{
    public function uploadOne($file, $object, $collection = 'default')
    {
        $object->addMedia($file)->toMediaCollection($collection);
    }

    public function uploadMultiple($files, $object, $collection = 'default')
    {
        foreach ($files as $file) {
            $this->uploadOne($file, $object, $collection);
        }
    }

}