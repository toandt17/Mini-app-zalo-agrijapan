<?php

namespace Database\Seeders;

use App\Models\Reward;
use Illuminate\Database\Seeder;

class RewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rewards = [
            [
                'name' => 'Phiếu mua hàng 50.000đ',
                'description' => 'Phiếu mua hàng trị giá 50.000đ tại cửa hàng AgriJapan',
                'image' => 'rewards/voucher-50k.png',
                'quantity' => 100,
            ],
            [
                'name' => 'Phiếu mua hàng 100.000đ',
                'description' => 'Phiếu mua hàng trị giá 100.000đ tại cửa hàng AgriJapan',
                'image' => 'rewards/voucher-100k.png',
                'quantity' => 50,
            ],
            [
                'name' => 'Combo sản phẩm nông nghiệp',
                'description' => 'Bộ sản phẩm nông nghiệp nhập khẩu từ Nhật Bản',
                'image' => 'rewards/farming-combo.png',
                'quantity' => 30,
            ],
            [
                'name' => 'Nón bảo hiểm AgriJapan',
                'description' => 'Nón bảo hiểm chất lượng cao với logo AgriJapan',
                'image' => 'rewards/helmet.png',
                'quantity' => 20,
            ],
            [
                'name' => 'Áo thun AgriJapan',
                'description' => 'Áo thun cotton cao cấp với logo AgriJapan',
                'image' => 'rewards/tshirt.png',
                'quantity' => 40,
            ],
            [
                'name' => 'Túi đựng đồ cá nhân',
                'description' => 'Túi đựng đồ cá nhân với logo AgriJapan',
                'image' => 'rewards/bag.png',
                'quantity' => 60,
            ],
            [
                'name' => 'Bộ dụng cụ làm vườn mini',
                'description' => 'Bộ dụng cụ làm vườn mini dành cho người mới bắt đầu',
                'image' => 'rewards/garden-tools.png',
                'quantity' => 25,
            ],
        ];

        foreach ($rewards as $reward) {
            Reward::create($reward);
        }
    }
}
