<?php

namespace App\Http\Middleware;

use App\Http\Resources\CartResource;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Services\Admin\AdminCategoryService;
use App\Traits\CartTrait;

class HandleInertiaRequests extends Middleware
{

    use CartTrait;

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

            // 'customerAddress' => new CustomerAddressResource(CustomerAddress::where('customer_id', $request->user('customer')->id)->first()),
            
            'categories' => $this->categoryService->getCategories()->get(),

            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ];
    }
}
