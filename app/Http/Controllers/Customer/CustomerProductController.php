<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HelperTrait;
use Inertia\Inertia;

class CustomerProductController extends Controller
{
    use HelperTrait;
    
    public function details($slug)
    {
        // dd($this->getProductBySlug($slug));
        return Inertia::render('Customer/Product/Details', [
            'product' => $this->getProductBySlug($slug),
        ]);
        
    }
}
