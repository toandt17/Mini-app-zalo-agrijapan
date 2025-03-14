<?php

namespace App\Repositories\Location;

interface LocationInterface
{
    /**
     * Lấy danh sách tất cả tỉnh/thành phố
     */
    public function getProvinces();

    /**
     * Lấy danh sách quận/huyện theo tỉnh/thành phố
     */
    public function getDistricts($provinceId);

    /**
     * Lấy danh sách phường/xã theo quận/huyện
     */
    public function getWards($districtId);

    /**
     * Lấy toàn bộ dữ liệu địa lý (tỉnh, quận/huyện, phường/xã)
     */
    public function getLocationData();

    /**
     * Tìm địa điểm gần nhất dựa trên tọa độ
     */
    public function findNearestLocation($lat, $lng);
}
