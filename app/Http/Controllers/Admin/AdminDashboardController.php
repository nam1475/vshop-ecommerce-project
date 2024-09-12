<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Admin Dashboard',
            'totalOrders' => Order::count(),
            'totalProducts' => Product::count(),
            'totalCustomers' => Customer::count(),
            'totalIncomes' => Order::where('status', 'delivered')->sum('total_price'),
        ];

        return Inertia::render('Admin/Dashboard', [
            'title' => 'Admin Dashboard',
            'data' => $data
        ]);
    }   
}
