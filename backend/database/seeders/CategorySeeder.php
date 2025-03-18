<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Quy Trình Cây Lúa',
            'image' => 'quy_trinh_cay_lua.jpg',
        ]);

        Category::create([
            'name' => 'Điều hòa sinh trưởng',
            'image' => 'dieu_hoa_sinh_truong.jpg',
        ]);

        Category::create([
            'name' => 'Phân bón siêu vi lượng',
            'image' => 'phan_bon_sieu_vi_luong.jpg',
        ]);

        Category::create([
            'name' => 'Thuốc trừ bệnh',
            'image' => 'thuoc_tru_benh.jpg',
        ]);

        Category::create([
            'name' => 'Thuốc trừ sâu rầy',
            'image' => 'thuoc_tru_sau_ray.jpg',
        ]);
    }
}
