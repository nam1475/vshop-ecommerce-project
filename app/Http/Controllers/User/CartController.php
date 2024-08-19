<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\CartItem;
use App\Services\User\CartService;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function list()
    {
        return Inertia::render('User/Cart/List');
    }

    public function store(Request $request, $productId)
    {
        $result = $this->cartService->store($request, $productId);
        if($result) {
            return redirect()->route('home')->with('success', 'Product added to cart successfully.');
        }
        return redirect()->route('home')->with('error', 'Something went wrong. Please try again.');
    }
    
    public function update(Request $request, $cartId)
    {
        $result = $this->cartService->update($request, $cartId);
        if($result) {
            return redirect()->back()->with('success', 'Product quantity updated successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }

    public function delete(Request $request, $cartId)
    {
        $result = $this->cartService->delete($cartId);
        if($result) {
            return redirect()->back()->with('success', 'Product deleted from cart successfully.');
        }
        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }
    
}
