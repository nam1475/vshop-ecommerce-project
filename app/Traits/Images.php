<?php

namespace App\Traits;

use App\Models\Category;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait Images
{

    public function getImagesByModel($model, $collectionName = 'default')
    {
        return $model->getMedia($collectionName)->map(function ($mediaItem) {
            return [
                'id' => $mediaItem->id,
                'url' => $mediaItem->getUrl(),
            ];
        });
    }

    public function getAllImagesByCollection($collection, $collectionName = 'default')
    {
        foreach($collection as $item) {
            $item['images'] = $item->getMedia($collectionName)->map(function ($mediaItem) {
                return $mediaItem->getUrl();
            });
            
            if($item instanceof Category) {
                $this->getAllImagesByCollection($item->childrenRecursive, $collectionName);
            }
        }
    }

    

}