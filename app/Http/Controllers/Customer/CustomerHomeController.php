<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Services\Admin\AdminProductService;

class CustomerHomeController extends Controller
{
    protected $productService;

    public function __construct(AdminProductService $productService)
    {
        $this->productService = $productService;
    }

    public function home()
    {
        // $products = $this->productService->getProducts()->limit(8)->get();
        $products = $this->productService->getProducts()->paginate(4);
        return Inertia::render('Customer/Home', [
            'products' => $products,
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }
}
