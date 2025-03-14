<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Lấy danh sách tất cả tỉnh/thành phố
     */
    public function getProvinces()
    {
        $provinces = Province::orderBy('name')->get();
        return response()->json(['provinces' => $provinces]);
    }

    /**
     * Lấy danh sách quận/huyện theo tỉnh/thành phố
     */
    public function getDistricts($provinceId)
    {
        $districts = District::where('province_id', $provinceId)
            ->orderBy('name')
            ->get();
        return response()->json(['districts' => $districts]);
    }

    /**
     * Lấy danh sách phường/xã theo quận/huyện
     */
    public function getWards($districtId)
    {
        $wards = Ward::where('district_id', $districtId)
            ->orderBy('name')
            ->get();
        return response()->json(['wards' => $wards]);
    }

    /**
     * Lấy thông tin địa chỉ đầy đủ
     */
    public function getFullAddress(Request $request)
    {
        $request->validate([
            'province_id' => 'required|exists:provinces,id',
            'district_id' => 'required|exists:districts,id',
            'ward_id' => 'required|exists:wards,id',
        ]);

        $province = Province::find($request->province_id);
        $district = District::find($request->district_id);
        $ward = Ward::find($request->ward_id);

        return response()->json([
            'success' => true,
            'address' => [
                'province' => $province->name,
                'district' => $district->name,
                'ward' => $ward->name,
                'full_address' => $ward->name . ', ' . $district->name . ', ' . $province->name
            ]
        ]);
    }
}
