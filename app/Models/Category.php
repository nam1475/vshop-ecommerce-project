<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\Cast\Object_;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    use HasFactory, HasSlug, InteractsWithMedia;

    protected $table = 'categories';
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
                if($model->parent_id == null) {
                    return $model->name;
                }
                $categoryParent = Category::find($model->parent_id);
                // $parentSlug = $this->getSlugParents($categoryParent);
                return $categoryParent->slug . '-' . $model->name;
            })
            ->saveSlugsTo('slug');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
        // return $this->hasMany(Product::class)->with('images');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function childrenRecursive()
    {
        // return $this->children()->with('childrenRecursive');
        return $this->children()->with(['childrenRecursive', 'parent', 'media']);
        // return $this->children()->with(['childrenRecursive', 'products']);
    }

    /* Lấy id tất cả các danh mục con của danh mục cha hiện tại */
    public function getAllChildrenIds()
    {
        $ids = $this->childrenRecursive()->pluck('id');
        // return $ids->merge([$this->getAllChildrenIds()]); // Bao gồm cả chính category hiện tại
        return $ids; 
    }
    
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }


}
