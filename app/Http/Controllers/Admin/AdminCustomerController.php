<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminCustomerController extends Controller
{
    public function list()
    {
        return Inertia::render('Admin/Customer/List', [
            'customers' => Customer::paginate(10)
        ]);
    }
}
