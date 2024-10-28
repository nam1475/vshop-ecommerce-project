<?php

namespace Tests\Feature\Customer\Auth;

use App\Models\Customer;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }
    
    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $this->withExceptionHandling();
        $token = csrf_token();
        $customer = Customer::factory()->create();   

        $response = $this->post('/login', [
            'email' => $customer->email,
            'password' => '1',
            // '_token' => $token
        ]);

        $this->assertAuthenticated('customer');
        $response->assertRedirect('/');
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $customer = Customer::factory()->create();

        $this->post('/login', [
            'email' => $customer->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest('customer');
    }

    public function test_users_can_logout(): void
    {
        $this->withExceptionHandling();
        $customer = Customer::factory()->create();   

        $response = $this->actingAs($customer)->post('/logout');

        $this->assertGuest('customer');
        $response->assertRedirect('/login');
    }
}
