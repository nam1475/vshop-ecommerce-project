<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = ['name', 'parent_id', 'slug'];

    // public function getSlugParents($categoryParent)
    // {
    //     $slug = $categoryParent->slug;
    //     if($categoryParent->parentRecursive == null) {
    //         return $slug;
    //     }
    //     return $this->getParents($categoryParent->parentRecursive) . '-' . $slug;
    // }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function ($model) {
                $categoryParent = Category::find($model->parent_id);
                // $parentSlug = $this->getSlugParents($categoryParent);
                return $categoryParent->slug . '-' . strtolower($model->name);
            })
            ->saveSlugsTo('slug');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
        // return $this->hasMany(Product::class)->with('images');
    }

    public function productRecursives()
    {
        return $this->products()->with('productRecursives');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
        // return $this->children()->with(['childrenRecursive', 'products']);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function parentRecursive()
    {
        return $this->parent()->with('parentRecursive');
    }


}
