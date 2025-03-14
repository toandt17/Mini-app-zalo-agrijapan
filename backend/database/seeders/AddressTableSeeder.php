<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Ward;
use Illuminate\Database\Seeder;

class AddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy 10 phường/xã ngẫu nhiên
        $wards = Ward::inRandomOrder()->take(10)->get();

        foreach ($wards as $ward) {
            // Tạo 1-3 địa chỉ cho mỗi phường/xã
            $addressCount = rand(1, 3);

            for ($i = 1; $i <= $addressCount; $i++) {
                // Tạo một unique identifier cho địa chỉ
                $uniqueId = $ward->id . '-' . $i;

                Address::updateOrCreate(
                    ['id' => $uniqueId],
                    [
                        'street' => 'Đường số ' . rand(1, 100),
                        'ward_id' => $ward->id,
                        'district_id' => $ward->district_id,
                        'province_id' => $ward->district->province_id,
                        'country' => 'Việt Nam',
                        'latitude' => 10 + (rand(0, 1000) / 1000),
                        'longitude' => 106 + (rand(0, 1000) / 1000),
                    ]
                );
            }
        }
    }
}
