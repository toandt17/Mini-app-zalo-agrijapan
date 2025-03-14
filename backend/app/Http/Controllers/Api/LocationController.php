<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Location\LocationInterface;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    protected $locationRepository;

    public function __construct(LocationInterface $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    /**
     * Lấy danh sách tất cả tỉnh/thành phố
     */
    public function getProvinces()
    {
        $provinces = $this->locationRepository->getProvinces();
        return response()->json(['provinces' => $provinces]);
    }

    /**
     * Lấy danh sách quận/huyện theo tỉnh/thành phố
     */
    public function getDistricts($provinceId)
    {
        $districts = $this->locationRepository->getDistricts($provinceId);
        // Debug log
        \Illuminate\Support\Facades\Log::info('Districts for province ' . $provinceId . ': ' . $districts->count());
        return response()->json($districts);
    }

    /**
     * Lấy danh sách phường/xã theo quận/huyện
     */
    public function getWards($districtId)
    {
        $wards = $this->locationRepository->getWards($districtId);
        // Debug log
        \Illuminate\Support\Facades\Log::info('Wards for district ' . $districtId . ': ' . $wards->count());
        return response()->json($wards);
    }

    /**
     * Lấy toàn bộ dữ liệu địa lý (tỉnh, quận/huyện, phường/xã)
     */
    public function getLocationData()
    {
        $locationData = $this->locationRepository->getLocationData();
        return response()->json(['provinces' => $locationData]);
    }

    /**
     * Tìm địa điểm gần nhất dựa trên tọa độ
     */
    public function findNearestLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $location = $this->locationRepository->findNearestLocation(
            $request->latitude,
            $request->longitude
        );

        return response()->json($location);
    }
}
