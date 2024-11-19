<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Published
{
    public static function published()
    {
        static::addGlobalScope('published', function (Builder $builder) {
            $builder->where('published', 1);
        });
    }
}