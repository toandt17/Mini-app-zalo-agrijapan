<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'category_id' => 1, // ID của danh mục Quy Trình Cây Lúa
            'name' => 'Bộ Giải Pháp Chồi To Cây Khỏe',
            'price' => 150.00,
            'original_price' => 200.00,
            'status' => 'active',
            'quantity' => 100,
            'image' => 'bo_giai_phap_choi_to_cay_khoe.jpg',
            'detail' => 'Giải pháp giúp cây lúa phát triển mạnh mẽ và khỏe mạnh.',
        ]);

        Product::create([
            'category_id' => 2, // ID của danh mục Điều hòa sinh trưởng
            'name' => 'Điều Hòa Sinh Trưởng BRASS 481',
            'price' => 120.00,
            'original_price' => 150.00,
            'status' => 'active',
            'quantity' => 100,
            'image' => 'dieu_hoa_sinh_truong_brass_481.jpg',
            'detail' => 'Sản phẩm giúp điều hòa sinh trưởng cho cây trồng.',
        ]);

        Product::create([
            'category_id' => 3, // ID của danh mục Phân bón siêu vi lượng
            'name' => 'KẼM ARMOR, KẼM BÁC SĨ (LK-ZN ARMOR)',
            'price' => 80.00,
            'original_price' => 100.00,
            'status' => 'active',
            'quantity' => 100,
            'image' => 'kem_armor_kem_bac_si.jpg',
            'detail' => 'Phân bón siêu vi lượng giúp cây trồng hấp thụ dinh dưỡng tốt hơn.',
        ]);

        Product::create([
            'category_id' => 4, // ID của danh mục Thuốc trừ bệnh
            'name' => 'STARSUPER 21SL Arigod',
            'price' => 90.00,
            'original_price' => 110.00,
            'status' => 'active',
            'quantity' => 100,
            'image' => 'starsuper_21sl_arigod.jpg',
            'detail' => 'Thuốc trừ bệnh hiệu quả cho cây trồng.',
        ]);

        Product::create([
            'category_id' => 5, // ID của danh mục Thuốc trừ sâu rầy
            'name' => 'RONADO 500EC',
            'price' => 95.00,
            'original_price' => 120.00,
            'status' => 'active',
            'quantity' => 100,
            'image' => 'ronado_500ec.jpg',
            'detail' => 'Thuốc trừ sâu rầy bảo vệ cây trồng khỏi sâu bệnh.',
        ]);
    }
}
