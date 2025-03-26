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

    // Đường dẫn đến file cấu hình
    protected $settingsPath;
    protected $rewardsPath;

    public function __construct()
    {
        $this->settingsPath = 'config/checkin_settings.json';
        $this->rewardsPath = 'config/checkin_rewards.json';
    }

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
        if (!File::exists($this->settingsPath)) {
            // Cài đặt mặc định nếu file không tồn tại
            $settings = [
                'points_reward' => 10,
                'spin_tickets_reward' => 1,
                'consecutive_bonus' => true,
            ];
            // Lưu cài đặt mặc định
            File::put($this->settingsPath, json_encode($settings, JSON_PRETTY_PRINT));
            return $settings;
        }

        // Đọc file cài đặt
        $settingsContent = File::get($this->settingsPath);
        return json_decode($settingsContent, true);
    }

    /**
     * Lưu cài đặt điểm danh vào file JSON
     *
     * @param array $settings
     * @return bool
     */
    public function saveCheckinSettings($settings)
    {
        // Đảm bảo thư mục tồn tại
        File::ensureDirectoryExists(dirname($this->settingsPath));
        // Lưu cài đặt
        return File::put($this->settingsPath, json_encode($settings, JSON_PRETTY_PRINT));
    }

    /**
     * Lấy phần thưởng điểm danh từ file JSON
     *
     * @return array
     */
    public function getCheckinRewards()
    {
        if (!File::exists($this->rewardsPath)) {
            // Phần thưởng mặc định nếu file không tồn tại
            $rewards = [
                ['day' => 1, 'name' => '5 điểm', 'points' => 5, 'spin_tickets' => 0],
                ['day' => 2, 'name' => '10 điểm', 'points' => 10, 'spin_tickets' => 0],
                ['day' => 3, 'name' => '15 điểm', 'points' => 15, 'spin_tickets' => 0],
                ['day' => 4, 'name' => '20 điểm', 'points' => 20, 'spin_tickets' => 0],
                ['day' => 5, 'name' => '1 lượt quay', 'points' => 0, 'spin_tickets' => 1],
                ['day' => 6, 'name' => '30 điểm', 'points' => 30, 'spin_tickets' => 0],
                ['day' => 7, 'name' => '50 điểm + 2 lượt quay', 'points' => 50, 'spin_tickets' => 2],
            ];
            // Lưu phần thưởng mặc định
            File::put($this->rewardsPath, json_encode($rewards, JSON_PRETTY_PRINT));
            return $rewards;
        }

        // Đọc file phần thưởng
        $rewardsContent = File::get($this->rewardsPath);
        return json_decode($rewardsContent, true);
    }

    /**
     * Lưu phần thưởng điểm danh vào file JSON
     *
     * @param array $rewards
     * @return bool
     */
    public function saveCheckinRewards($rewards)
    {
        // Đảm bảo thư mục tồn tại
        File::ensureDirectoryExists(dirname($this->rewardsPath));
        // Lưu phần thưởng
        return File::put($this->rewardsPath, json_encode($rewards, JSON_PRETTY_PRINT));
    }

    /**
     * Alias của saveCheckinSettings để tương thích ngược
     *
     * @param array $settings
     * @return bool
     */
    public function updateCheckinSettings(array $settings)
    {
        return $this->saveCheckinSettings($settings);
    }
}
