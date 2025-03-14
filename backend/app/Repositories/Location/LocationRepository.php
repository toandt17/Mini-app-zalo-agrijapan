<?php

namespace App\Repositories\Location;

use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use App\Models\Agent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LocationRepository implements LocationInterface
{
    /**
     * Lấy danh sách tất cả tỉnh/thành phố
     */
    public function getProvinces()
    {
        try {
            $provinces = Province::orderBy('name')->get();

            // Log số lượng tỉnh/thành phố
            Log::info('Số lượng tỉnh/thành phố: ' . $provinces->count());

            // Đếm số lượng đại lý trong mỗi tỉnh/thành phố
            return $provinces->map(function($province) {
                $agentCount = Agent::where('province_id', $province->id)->count();
                return [
                    'id' => $province->id,
                    'name' => $province->name,
                    'agent_count' => $agentCount
                ];
            });
        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy danh sách tỉnh/thành phố: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Lấy danh sách quận/huyện theo tỉnh/thành phố
     */
    public function getDistricts($provinceId)
    {
        try {
            $districts = District::where('province_id', $provinceId)
                ->orderBy('name')
                ->get();

            // Đếm số lượng đại lý trong mỗi quận/huyện
            return $districts->map(function($district) {
                $agentCount = Agent::where('district_id', $district->id)->count();
                return [
                    'id' => $district->id,
                    'name' => $district->name,
                    'agent_count' => $agentCount
                ];
            });
        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy danh sách quận/huyện: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Lấy danh sách phường/xã theo quận/huyện
     */
    public function getWards($districtId)
    {
        try {
            $wards = Ward::where('district_id', $districtId)
                ->orderBy('name')
                ->get();

            // Đếm số lượng đại lý trong mỗi phường/xã
            return $wards->map(function($ward) {
                $agentCount = Agent::where('ward_id', $ward->id)->count();
                return [
                    'id' => $ward->id,
                    'name' => $ward->name,
                    'agent_count' => $agentCount
                ];
            });
        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy danh sách phường/xã: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Lấy toàn bộ dữ liệu địa lý (tỉnh, quận/huyện, phường/xã)
     */
    public function getLocationData()
    {
        try {
            $provinces = Province::orderBy('name')->get();

            return $provinces->map(function($province) {
                $provinceAgentCount = Agent::where('province_id', $province->id)->count();

                $districts = District::where('province_id', $province->id)
                    ->orderBy('name')
                    ->get()
                    ->map(function($district) {
                        $districtAgentCount = Agent::where('district_id', $district->id)->count();

                        $wards = Ward::where('district_id', $district->id)
                            ->orderBy('name')
                            ->get()
                            ->map(function($ward) {
                                $wardAgentCount = Agent::where('ward_id', $ward->id)->count();

                                return [
                                    'id' => $ward->id,
                                    'name' => $ward->name,
                                    'agent_count' => $wardAgentCount
                                ];
                            });

                        return [
                            'id' => $district->id,
                            'name' => $district->name,
                            'agent_count' => $districtAgentCount,
                            'wards' => $wards
                        ];
                    });

                return [
                    'id' => $province->id,
                    'name' => $province->name,
                    'agent_count' => $provinceAgentCount,
                    'districts' => $districts
                ];
            });
        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy dữ liệu địa lý: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Tìm địa điểm gần nhất dựa trên tọa độ
     */
    public function findNearestLocation($lat, $lng)
    {
        try {
            // Tìm đại lý gần nhất
            $agent = Agent::select('*', DB::raw("
                (6371 * acos(cos(radians($lat)) * cos(radians(latitude)) * cos(radians(longitude) - radians($lng)) + sin(radians($lat)) * sin(radians(latitude)))) AS distance
            "))
            ->orderBy('distance')
            ->first();

            if (!$agent) {
                // Nếu không tìm thấy đại lý, trả về tỉnh/thành phố đầu tiên
                $province = Province::first();
                $district = District::where('province_id', $province->id)->first();
                $ward = Ward::where('district_id', $district->id)->first();
            } else {
                // Nếu tìm thấy đại lý, trả về địa điểm của đại lý đó
                $province = Province::find($agent->province_id);
                $district = District::find($agent->district_id);
                $ward = Ward::find($agent->ward_id);
            }

            return [
                'province' => [
                    'id' => $province->id,
                    'name' => $province->name,
                ],
                'district' => [
                    'id' => $district->id,
                    'name' => $district->name,
                ],
                'ward' => [
                    'id' => $ward->id,
                    'name' => $ward->name,
                ],
            ];
        } catch (\Exception $e) {
            Log::error('Lỗi khi tìm địa điểm gần nhất: ' . $e->getMessage());
            return [
                'province' => null,
                'district' => null,
                'ward' => null
            ];
        }
    }
}

