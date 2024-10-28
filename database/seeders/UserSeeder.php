<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa tất cả dữ liệu trong bảng users
        // User::truncate();

        // Tạo dữ liệu mới
        $user = User::factory(5)->create();

        $user[0]->assignRole('admin');
        $user[1]->assignRole('writer', 'admin');
        $user[2]->assignRole('user');
        $user[3]->assignRole('admin');
        $user[4]->assignRole('writer');


    }
}
