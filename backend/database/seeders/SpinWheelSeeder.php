<?php

namespace Database\Seeders;

use App\Models\SpinWheel;
use Illuminate\Database\Seeder;

class SpinWheelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prizes = [
            [
                'prize_name' => 'Phiếu giảm giá 10%',
                'description' => 'Phiếu giảm giá 10% cho đơn hàng kế tiếp',
                'probability' => 15.0,
                'remaining_quantity' => 500,
                'has_reward' => 1,
                'image' => 'prizes/voucher-10.png',
            ],
            [
                'prize_name' => 'Phiếu giảm giá 20%',
                'description' => 'Phiếu giảm giá 20% cho đơn hàng kế tiếp',
                'probability' => 10.0,
                'remaining_quantity' => 200,
                'has_reward' => 1,
                'image' => 'prizes/voucher-20.png',
            ],
            [
                'prize_name' => 'Phiếu giảm giá 50%',
                'description' => 'Phiếu giảm giá 50% cho đơn hàng kế tiếp',
                'probability' => 5.0,
                'remaining_quantity' => 50,
                'has_reward' => 1,
                'image' => 'prizes/voucher-50.png',
            ],
            [
                'prize_name' => '10 điểm',
                'description' => 'Thêm 10 điểm vào tài khoản của bạn',
                'probability' => 20.0,
                'remaining_quantity' => 1000,
                'has_reward' => 1,
                'image' => 'prizes/points-10.png',
            ],
            [
                'prize_name' => '50 điểm',
                'description' => 'Thêm 50 điểm vào tài khoản của bạn',
                'probability' => 10.0,
                'remaining_quantity' => 300,
                'has_reward' => 1,
                'image' => 'prizes/points-50.png',
            ],
            [
                'prize_name' => '100 điểm',
                'description' => 'Thêm 100 điểm vào tài khoản của bạn',
                'probability' => 5.0,
                'remaining_quantity' => 100,
                'has_reward' => 1,
                'image' => 'prizes/points-100.png',
            ],
            [
                'prize_name' => 'Sản phẩm đặc biệt',
                'description' => 'Một sản phẩm bất ngờ từ AgriJapan',
                'probability' => 3.0,
                'remaining_quantity' => 20,
                'has_reward' => 1,
                'image' => 'prizes/special-product.png',
            ],
            [
                'prize_name' => 'Chúc may mắn lần sau',
                'description' => 'Rất tiếc, bạn không trúng thưởng lần này',
                'probability' => 32.0,
                'remaining_quantity' => 9999,
                'has_reward' => 0,
                'image' => 'prizes/try-again.png',
            ],
        ];

        foreach ($prizes as $prize) {
            SpinWheel::create($prize);
        }
    }
}
