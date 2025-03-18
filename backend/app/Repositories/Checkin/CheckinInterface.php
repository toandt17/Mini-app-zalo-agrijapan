<?php

namespace App\Repositories\Checkin;

interface CheckinInterface
{
    /**
     * Lấy danh sách lịch sử điểm danh của tất cả người dùng
     *
     * @param array $params Các tham số lọc
     * @return mixed
     */
    public function getAllCheckins(array $params = []);

    /**
     * Lấy lịch sử điểm danh của một người dùng cụ thể
     *
     * @param int $userId ID của người dùng
     * @param array $params Các tham số lọc
     * @return mixed
     */
    public function getUserCheckins(int $userId, array $params = []);

    /**
     * Lấy thống kê điểm danh
     *
     * @param array $params Các tham số thống kê
     * @return mixed
     */
    public function getCheckinStats(array $params = []);

    /**
     * Lấy cài đặt điểm danh hiện tại
     *
     * @return mixed
     */
    public function getCheckinSettings();

    /**
     * Cập nhật cài đặt điểm danh
     *
     * @param array $settings Các cài đặt mới
     * @return mixed
     */
    public function updateCheckinSettings(array $settings);
}
