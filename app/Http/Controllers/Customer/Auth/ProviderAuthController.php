<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class ProviderAuthController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try{
            // DB::beginTransaction();
            $socialCustomer = Socialite::driver($provider)->user();

            $isExistEmail = Customer::where('email', $socialCustomer->email)->exists();
            $customer = Customer::where([
                'provider_id' => $socialCustomer->id,
                'provider' => $provider
            ])->first();

            // /* Khi email đã tồn tại */
            if($isExistEmail){
                /* Nhưng thuộc của người dùng */
                if($customer){
                    Auth::guard('customer')->login($customer);
                    return redirect()->route('home');
                }
                /* Nhưng ko phải của người dùng */
                else{
                    return redirect()->route('login')->with('error', 'Your email has already been registered');
                }
            }

            /* Khi email chưa tồn tại */
            else{
                /* Tạo người dùng mới */
                $customer = Customer::create([
                    'name' => $socialCustomer->name,
                    'email' => $socialCustomer->email,
                    'provider' => $provider,
                    'provider_id' => $socialCustomer->id,
                    'provider_token' => $socialCustomer->token,
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(60),
                ]);
                
                Auth::guard('customer')->login($customer);
                return redirect()->route('home');
            }
            
            // DB::commit();
            // return redirect()->route('home');
        } catch (\Exception $e) {
            // DB::rollBack();  
            throw new \Exception($e->getMessage());
        }
        
    }
}
