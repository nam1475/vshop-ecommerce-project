<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Traits\InStock;
use App\Traits\Published;

class Product extends Model implements HasMedia
{
    use HasFactory, HasSlug, InteractsWithMedia, InStock, Published;

    protected $table = 'products';
    protected $fillable = [
        'name',
        'slug',
        'quantity',
        'category_id',  
        'brand_id',     
        'description',
        'published',
        'in_stock',
        'price',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public static function boot()
    {
        parent::boot();

        static::creatingAndUpdatingInStock();
        static::published();
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
    
    // public function images()
    // {
    //     return $this->hasMany(ProductImage::class);
    // }
    
    // public function images()
    // {
    //     return $this->getMedia('product_images')->map(function ($mediaItem) {
    //         return $mediaItem->getUrl();
    //     });
    // }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function cartItems(){
        return $this->hasMany(CartItem::class);
    }

    // public function scopeFiltered(Builder $query)  {
    //     return $query
    //     ->when(request('brands'), function (Builder $q)  {
    //         $q->whereIn('brand_id',request('brands'));
    //     })
    //     ->when(request('categories'), function (Builder $q)  {
    //         $q->whereIn('category_id',request('categories'));
    //     })
    //     ->when(request('prices'), function(Builder $q)  {
    //         $q->whereBetween('price',[
    //             request('prices.from',0),
    //             request('prices.to', 100000),
    //         ]);
    //     });
        
    // }
}
