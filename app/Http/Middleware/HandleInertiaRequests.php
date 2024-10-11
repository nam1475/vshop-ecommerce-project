<?php

namespace App\Http\Middleware;

use App\Http\Resources\CartResource;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Services\Admin\AdminCategoryService;
use App\Models\Category;
use App\Traits\CartTrait;
use App\Traits\Images;

class HandleInertiaRequests extends Middleware
{

    use CartTrait, Images;

    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';
    protected $categoryService;

    public function __construct(AdminCategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $categories = $this->categoryService->getCategories()->get();
        $this->getAllImagesByCollection($categories, 'category_images');

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'customer' => $request->user('customer'),
            ],

            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'warning' => fn () => $request->session()->get('warning'),
                'info' => fn () => $request->session()->get('info'),
            ],

            'cart' => new CartResource($this->getProductsAndCartItems()),

            
            // 'categories' => $this->categoryService->getCategories()->get(),
            'categories' => $categories,

            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ];
    }
}
