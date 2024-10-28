<?php

namespace Tests\Feature\Admin;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminBrandTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Brand $brand;

    public function setUp(): void
    {
        parent::setUp();
        $this->withExceptionHandling();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->brand = Brand::factory()->create();
    }

    public function test_auth_users_can_access_brand_index_page()
    {
        $response = $this->get(route('admin.brand.index'));
        $response->assertStatus(200);
    }

    public function test_auth_users_can_create_brand()
    {
        $response = $this->post(route('admin.brand.store'), [
            'name' => 'Test Brand',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.brand.index'));

        /* Kiểm tra xem dữ liệu đã được thêm vào database chưa */
        $this->assertDatabaseHas('brands', [
            'name' => 'Test Brand',
        ]);
    }

    public function test_auth_users_can_edit_brand()
    {
        $response = $this->get(route('admin.brand.edit', $this->brand->id));
        
        $response->assertStatus(200);
        /* Kiểm tra tên brand có tồn tại trên giao diện không */
        $response->assertSee($this->brand->name); 
    }

    public function test_auth_users_can_update_brand()
    {
        $response = $this->put(route('admin.brand.update', $this->brand->id), [
            'name' => 'Adidas',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.brand.index'));
    }

    public function test_auth_users_can_destroy_brand()
    {
        $brand = Brand::factory()->create();
        $response = $this->delete(route('admin.brand.destroy', $this->brand->id));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.brand.index'));
        
        $this->assertDatabaseMissing('brands', $this->brand->toArray());
    }
}
