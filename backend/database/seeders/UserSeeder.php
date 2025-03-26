<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo tài khoản admin
        User::create([
            'name' => 'Toàn Đào',
            'email' => 'toandt17.dev@gmail.com',
            'password' => Hash::make('password'),
            'avatar' => 'https://ui-avatars.com/api/?name=Admin&color=7F9CF5&background=EBF4FF',
            'phone' => '0987654321',
            'followed_oa' => true,
            'points' => 100,
            'last_login' => now(),
        ]);

        // Tạo tài khoản quản lý
        User::create([
            'name' => 'Ngọc Trâm',
            'email' => 'ngoctram123@gmail.com',
            'password' => Hash::make('password'),
            'avatar' => 'https://ui-avatars.com/api/?name=Ngọc Trâm&color=7F9CF5&background=EBF4FF',
            'phone' => '0987654321',
            'followed_oa' => true,
            'points' => 50,
            'last_login' => now(),
        ]);
    }
}
