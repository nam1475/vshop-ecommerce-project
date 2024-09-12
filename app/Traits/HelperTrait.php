<?php

namespace App\Traits;

use App\Http\Resources\OrderResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Str;

trait HelperTrait
{
    public function getCategories()
    {
        return Category::with('childrenRecursive')->whereNull('parent_id');
    }

    public function getBrands()
    {
        return Brand::all();
    }

    public function getProductBySlug($slug)
    {
        return Product::with(['images', 'category'])->where('slug', $slug)->first();
    }

    public function getCateogryBySlug($slug)
    {
        return Category::where('slug', $slug)->first();
    }

    public function getChildrenByParent($categoryParent)
    {
        return $categoryParent->children()->get();
    }

    public function getAllProductIds($category)
    {
        // Tạo một mảng để chứa tất cả các ID sản phẩm
        $productIds = collect($category->products->pluck('id'));
        // $productIds = collect($category->products);
        // dd($productIds);

        // Lặp qua tất cả các danh mục con và gọi đệ quy
        foreach ($category->childrenRecursive as $childCategory) {
            $productIds = $productIds->merge($this->getAllProductIds($childCategory));
        }

        return $productIds;
    }

    public function getProductsByCategory($request, $category)
    {
        $query = $category->products()->with('images');
        // $query = $category->query();

        if($category->children()->exists()) {

            /* Lấy tất cả danh mục con, cháu của danh mục cha hiện tại */
            $childrenRecursiveCategory = Category::with('childrenRecursive')->find($category->id);
            // dd($childrenRecursiveCategory);

            // $products = $this->getAllProductIds($childrenRecursiveCategory);
            // $query = $childrenRecursiveCategory->products()->with('images');
            // $query = $products;
            // dd($query);

            /* Hàm đệ quy để lấy ID sản phẩm từ tất cả các danh mục con */
            $productIds = $this->getAllProductIds($childrenRecursiveCategory);

            /* Query để lấy các sản phẩm dựa trên các ID sản phẩm */
            $query = Product::join('categories as c', 'products.category_id', '=', 'c.id')
                        ->select('products.*')
                        ->whereIn('products.id', $productIds)
                        ->with('images');
            // dd($query);
        }
        
        /* Nếu tồn tại query string */
        if(!empty($request->query())) {
            $priceFrom = $request->input('price.from');
            $priceTo = $request->input('price.to');
            $brands = $request->input('brands');
            $categories = $request->input('categories');
            $sort = $request->input('sort');
            $query
                ->when($request->price, function ($q) use ($priceFrom, $priceTo) {
                    $q->whereBetween('products.price', [$priceFrom, $priceTo]);
                })
                ->when($brands, function ($q) use ($brands) {
                    $q->whereIn('products.brand_id', $brands);
                })
                ->when($categories, function ($q) use ($categories) {
                    $q->whereIn('products.category_id', $categories);
                })
                ->when($sort, function ($q) use ($sort) {
                    $q->orderBy('products.price', $sort);
                });
        }

        return $query->paginate(8)->withQueryString();
    }

    public function getBrandsByProducts($products)
    {
        $collect = collect();
        foreach ($products as $product) {
            if(!$collect->contains($product->brand)){
                $collect->push($product->brand);
            }
        }
        return $collect;
    }

    public function getCategoriesByProducts($products, $categoryParent)
    {
        $collect = collect();
        // $categoryIds = $this->getChildrenByParent($categoryParent)->pluck('id')->toArray();
        $categories = $this->getChildrenByParent($categoryParent);
        // dd($products);
        foreach ($products as $product) {
            foreach($categories as $category){
                // dd($product->category);
                // if(in_array($product->category->parent_id, $categories)){
                //     $collect->push($product->category);
                // }
                if($product->category->parent_id == $category->id){
                    $collect->push($category);
                }
            }
        }
        dd($collect);
        $collect = $collect->unique();
        return $collect;
    }

    public function getCustomerMainAddress()
    {
        return CustomerAddress::where([
            'customer_id' => auth('customer')->user()->id, 
            'is_main' => 1
        ])->first();
    }

    public function getOrder($orderId)
    {
        $order = Order::with(['orderItems.product.images', 'customerAddress.customer'])->where('id', $orderId)->first();
        return new OrderResource($order);
    }

    public function uniqueImageName($image, $path)
    {
        $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
        $publicPath = public_path($path);
        $image->move($publicPath, $uniqueName);
        return $uniqueName;
    }
}

