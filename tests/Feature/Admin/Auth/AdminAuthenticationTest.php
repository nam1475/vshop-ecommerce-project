<?php

namespace Tests\Feature\Admin\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->withExceptionHandling();
        
        $this->user = User::factory()->create();
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
    }
    
    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $response = $this->post('/admin/login', [
            'email' => $this->user->email,
            'password' => '1',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/admin/dashboard');
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $this->post('/admin/login', [
            'email' => $this->user->email,
            'password' => '123',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $response = $this->actingAs($this->user)->post('/admin/logout');

        $this->assertGuest();
        $response->assertRedirect('/admin/login');
    }
}
