<?php

namespace App\Repositories\Checkin;

use App\Models\DailyCheckin;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Pagination\LengthAwarePaginator;

class CheckinRepository implements CheckinInterface
{
    // Đường dẫn file lưu cài đặt
    protected $configPath = 'config/checkin_settings.json';

    // Cài đặt mặc định
    protected $defaultSettings = [
        'points_reward' => 10,
        'spin_tickets_reward' => 1,
        'consecutive_bonus' => true
    ];

    /**
     * Lấy danh sách lịch sử điểm danh của tất cả người dùng
     *
     * @param array $params Các tham số lọc
     * @return LengthAwarePaginator
     */
    public function getAllCheckins(array $params = [])
    {
        $query = DailyCheckin::with('user')
            ->select('daily_checkin.*');

        // Lọc theo ngày
        if (isset($params['date_from'])) {
            $query->where('checkin_date', '>=', $params['date_from']);
        }

        if (isset($params['date_to'])) {
            $query->where('checkin_date', '<=', $params['date_to']);
        }

        // Sắp xếp
        $sortField = $params['sort_field'] ?? 'checkin_date';
        $sortDirection = $params['sort_direction'] ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        // Phân trang
        $perPage = $params['per_page'] ?? 15;

        return $query->paginate($perPage);
    }

    /**
     * Lấy lịch sử điểm danh của một người dùng cụ thể
     *
     * @param int $userId ID của người dùng
     * @param array $params Các tham số lọc
     * @return LengthAwarePaginator
     */
    public function getUserCheckins(int $userId, array $params = [])
    {
        $query = DailyCheckin::where('user_id', $userId);

        // Lọc theo ngày
        if (isset($params['date_from'])) {
            $query->where('checkin_date', '>=', $params['date_from']);
        }

        if (isset($params['date_to'])) {
            $query->where('checkin_date', '<=', $params['date_to']);
        }

        // Sắp xếp
        $sortField = $params['sort_field'] ?? 'checkin_date';
        $sortDirection = $params['sort_direction'] ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        // Phân trang
        $perPage = $params['per_page'] ?? 15;

        return $query->paginate($perPage);
    }

    /**
     * Lấy thống kê điểm danh
     *
     * @param array $params Các tham số thống kê
     * @return array
     */
    public function getCheckinStats(array $params = [])
    {
        $startDate = $params['start_date'] ?? now()->subDays(30)->format('Y-m-d');
        $endDate = $params['end_date'] ?? now()->format('Y-m-d');

        // Tổng số điểm danh trong khoảng thời gian
        $totalCheckins = DailyCheckin::whereBetween('checkin_date', [$startDate, $endDate])->count();

        // Số người dùng duy nhất đã điểm danh
        $uniqueUsers = DailyCheckin::whereBetween('checkin_date', [$startDate, $endDate])
            ->distinct('user_id')
            ->count('user_id');

        // Thống kê điểm danh theo ngày
        $dailyStats = DailyCheckin::whereBetween('checkin_date', [$startDate, $endDate])
            ->select(DB::raw('DATE(checkin_date) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Tổng số điểm đã phát trong khoảng thời gian
        $totalPoints = DailyCheckin::whereBetween('checkin_date', [$startDate, $endDate])
            ->sum('points_earned');

        // Tổng số vé quay đã phát
        $totalSpinTickets = DailyCheckin::whereBetween('checkin_date', [$startDate, $endDate])
            ->sum('spin_tickets');

        return [
            'total_checkins' => $totalCheckins,
            'unique_users' => $uniqueUsers,
            'daily_stats' => $dailyStats,
            'total_points' => $totalPoints,
            'total_spin_tickets' => $totalSpinTickets
        ];
    }

    /**
     * Lấy cài đặt điểm danh hiện tại
     *
     * @return array
     */
    public function getCheckinSettings()
    {
        // Sử dụng cache để tránh đọc file liên tục
        return Cache::remember('checkin_settings', 60, function () {
            // Nếu file tồn tại, đọc từ file
            if (File::exists(base_path($this->configPath))) {
                $settings = json_decode(File::get(base_path($this->configPath)), true);
                return $settings ?: $this->defaultSettings;
            }

            // Nếu không có file, sử dụng giá trị mặc định
            return $this->defaultSettings;
        });
    }

    /**
     * Cập nhật cài đặt điểm danh
     *
     * @param array $settings Các cài đặt mới
     * @return bool
     */
    public function updateCheckinSettings(array $settings)
    {
        // Tạo thư mục config nếu chưa tồn tại
        $configDir = dirname(base_path($this->configPath));
        if (!File::exists($configDir)) {
            File::makeDirectory($configDir, 0755, true);
        }

        // Lưu cài đặt mới vào file
        File::put(
            base_path($this->configPath),
            json_encode($settings, JSON_PRETTY_PRINT)
        );

        // Xóa cache cũ
        Cache::forget('checkin_settings');

        return true;
    }
}
