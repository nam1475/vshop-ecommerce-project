<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Auth\RegisterRequest;
use App\Models\Customer;
use App\Notifications\Welcome;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Validation\ValidationException;  

class RegisteredController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Customer/Auth/Register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        try{
            DB::beginTransaction();
    
            $customer = Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            event(new Registered($customer));   
            
            Auth::guard('customer')->login($customer);
    
            $customer->notify(new Welcome());
    
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }

        // return redirect(RouteServiceProvider::HOME);
        return redirect()->route('verification.notice');
    }
}
