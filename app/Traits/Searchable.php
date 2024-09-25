<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;

trait Searchable
{
    public function scopeSearch($query, $search, $model)
    {
        $object = new $model;
        return $query
            ->when($search, function($q) use ($search, $object) {
                $q
                    ->where('id', $search)
                    ->when(Schema::hasColumn($object->getTable(), 'name'), function($q) use ($search) {
                        $q->orWhere('name', 'like', "%{$search}%");             
                    });
            });
            // ->whereAny([
            //     'id', 
            //     'name'
            // ], 'like', "%{$search}%");
    }
}