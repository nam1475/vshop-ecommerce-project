<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;

trait Filterable
{
    public function scopeFilters($query, $filters, $model)
    {
        $object = new $model;
        return $query
            ->when($filters, function($q) use ($filters, $model, $object) {
                $q
                ->when(Schema::hasColumn($object->getTable(),'status'), function($q) use ($filters) {
                    $q->whereIn('status', $filters);
                });

                // Kiểm tra xem mối quan hệ 'brand' có tồn tại trong mô hình hay không
                if (method_exists($model, 'brand')) {
                    $q->whereHas('brand', function($q) use ($filters) {
                        $q->whereIn('name', $filters);
                    });
                }

                // Kiểm tra tương tự cho 'category'
                if (method_exists($model, 'category')) {
                    $q->orWhereHas('category', function($q) use ($filters) {
                        $q->whereIn('name', $filters);
                    });
                }
            });
    }
}