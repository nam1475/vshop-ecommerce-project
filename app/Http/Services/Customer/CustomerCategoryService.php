<?php

namespace App\Http\Services\Customer;

use App\Traits\HelperTrait;
use App\Models\Category;
use App\Models\Product;
use App\Http\Services\Customer\CustomerProductService;

class CustomerCategoryService
{
    protected $productService;
    
    public function __construct(CustomerProductService $productService)
    {
        $this->productService = $productService;
    }
    
    public function getProductsByCategory($request, $category)
    {
        /* 
        - Lazy Loading (Ko sử dụng with()):
        +, Khi bạn không sử dụng with(), Laravel sẽ tự động tải mối quan hệ (relationship) khi bạn truy cập vào nó.
        +, Nếu bạn truy cập nhiều lần vào quan hệ đó trong vòng lặp hoặc nhiều nơi khác, mỗi lần sẽ thực hiện một truy vấn 
        riêng.

        - Eager Loading (Sử dụng with()):
        +, Sử dụng with() để eager loading giúp giảm số lượng truy vấn đến cơ sở dữ liệu bằng cách tải tất cả các quan hệ 
        cần thiết trong một truy vấn duy nhất.
        +, Điều này hữu ích khi bạn biết trước rằng sẽ truy cập các quan hệ và muốn tối ưu hóa hiệu suất.
        */

        $query = $category->products();
        
        if($category->children()->exists()) {

            /* Lấy tất cả danh mục con, cháu của danh mục cha hiện tại */
            $childrenRecursiveCategory = $category->load('childrenRecursive');
            // $childrenRecursiveCategory = Category::with('childrenRecursive')->find($category->id); 

            /* Hàm đệ quy để lấy ID sản phẩm từ tất cả các danh mục con */
            $productIds = $this->productService->getProductIdsByCategory($childrenRecursiveCategory);

            /* Query để lấy các sản phẩm dựa trên các ID sản phẩm */
            $query = Product::whereIn('id', $productIds);
        }
        
        /* Nếu tồn tại query string */
        if(!empty($request->query())) {
            $priceMin = $request->input('price_min');
            $priceMax = $request->input('price_max');
            $brandSlugs = $request->input('brands');
            $categorySlugs = $request->input('categories');
            $sort = $request->input('sort');
            $query
                ->when($priceMax, function ($q) use ($priceMin, $priceMax) {
                    $q->whereBetween('products.price', [$priceMin, $priceMax]);
                })
                ->when($brandSlugs, function ($q) use ($brandSlugs) {
                    $q->whereHas('brand', function ($q) use ($brandSlugs) {
                        $q->whereIn('slug', $brandSlugs);
                    });
                })
                ->when($categorySlugs, function ($q) use ($categorySlugs) {
                    $q->whereHas('category', function ($q) use ($categorySlugs) {                        
                        $categories = Category::whereIn('slug', $categorySlugs)->get();
                        $ids = $this->getChildrenCategoryIds($categories);
                        $result = $q->whereIn('id', $ids);
                        return $result;
                    });
                })
                ->when($sort, function ($q) use ($sort) {
                    $q->orderBy('products.price', $sort);
                });
        }

        return $query->paginate(4)->withQueryString();
    }

    public function getProductPriceMinMaxByCategory($category)
    {
        $productIds = $this->productService->getProductIdsByCategory($category);
        $priceMin = Product::whereIn('id', $productIds)->min('price');
        $priceMax = Product::whereIn('id', $productIds)->max('price');
        return [
            'price_min' => $priceMin,
            'price_max' => $priceMax
        ];
    }

    public function getCategoryBySlug($slug)
    {
        return Category::where('slug', $slug)->first();
    }

    public function getChildrenCategoryIds($categories)
    {
        $ids = collect($categories->pluck('id'));
        foreach ($categories as $category) {
            $ids = $ids->merge($this->getChildrenCategoryIds($category->childrenRecursive));
        }
        return $ids;
    }

    // public function getCategoriesByProducts($products)
    public function getCategoriesByIds($categoryIds)
    {
        // return Category::whereIn('id', $products->pluck('category_id')->unique())->get();
        return Category::whereIn('id', $categoryIds)->get();
    }

}