<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     *
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // User seeder
            UserSeeder::class,

            // Chạy các seeder địa chỉ trước
            ProvinceTableSeeder::class,
            DistrictTableSeeder::class,
            WardTableSeeder::class,
            // AddressTableSeeder::class,

            // Sau đó chạy các seeder khác
            CategorySeeder::class,
            ProductSeeder::class,
            AgentSeeder::class,

            // Game system seeders
            RewardSeeder::class,           // Seeder cho phần thưởng
            SpinWheelSeeder::class,        // Seeder cho vòng quay may mắn
            MissionSeeder::class,          // Seeder cho nhiệm vụ
            QuizQuestionSeeder::class,     // Seeder cho câu hỏi trắc nghiệm
        ]);
    }
}
