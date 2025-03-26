<?php

namespace App\Http\Controllers\Admin\Game;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Checkin\CheckinInterface;
use App\Models\User;

class CheckinController extends Controller
{
    protected $checkinRepository;

    /**
     * Khởi tạo controller với dependency injection
     *
     * @param CheckinInterface $checkinRepository
     */
    public function __construct(CheckinInterface $checkinRepository)
    {
        $this->checkinRepository = $checkinRepository;
    }

    /**
     * Hiển thị trang tổng quan điểm danh
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Lấy thống kê về điểm danh
        $stats = $this->checkinRepository->getCheckinStats();

        // Lấy lịch sử điểm danh gần đây
        $recentCheckins = $this->checkinRepository->getAllCheckins([
            'per_page' => 10
        ]);

        return view('admin.game.checkin.index', compact('stats', 'recentCheckins'));
    }

    /**
     * Hiển thị báo cáo thống kê điểm danh
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function reports(Request $request)
    {
        $params = $request->only(['start_date', 'end_date']);
        $stats = $this->checkinRepository->getCheckinStats($params);

        return view('admin.game.checkin.reports', compact('stats'));
    }

    /**
     * Hiển thị trang cài đặt điểm danh
     *
     * @return \Illuminate\View\View
     */
    public function settings()
    {
        $settings = $this->checkinRepository->getCheckinSettings();
        $rewards = $this->checkinRepository->getCheckinRewards();

        return view('admin.game.checkin.settings', compact('settings', 'rewards'));
    }

    /**
     * Lưu cài đặt điểm danh
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveSettings(Request $request)
    {
        $request->validate([
            'points_reward' => 'sometimes|integer|min:0',
            'spin_tickets_reward' => 'sometimes|integer|min:0',
            'rewards' => 'sometimes|array',
            'rewards.*.day' => 'required|integer|min:1|max:7',
            'rewards.*.points' => 'required|integer|min:0',
            'rewards.*.spin_tickets' => 'required|integer|min:0',
            'rewards.*.name' => 'sometimes|nullable|string|max:100',
        ]);

        // Lưu cài đặt cơ bản (nếu có)
        if ($request->has('points_reward') && $request->has('spin_tickets_reward')) {
            $settings = [
                'points_reward' => $request->input('points_reward'),
                'spin_tickets_reward' => $request->input('spin_tickets_reward'),
                'consecutive_bonus' => 0 // Mặc định tắt tính năng này vì đã bỏ khỏi form
            ];

            $this->checkinRepository->updateCheckinSettings($settings);
        }

        // Xử lý và lưu phần thưởng theo ngày
        if ($request->has('rewards')) {
            $rewards = [];

            foreach ($request->rewards as $dayData) {
                $day = $dayData['day'];
                $points = (int)$dayData['points'];
                $spinTickets = (int)$dayData['spin_tickets'];
                $providedName = isset($dayData['name']) ? trim($dayData['name']) : '';

                // Sử dụng tên được cung cấp nếu có, ngược lại tạo tên tự động
                if (!empty($providedName)) {
                    $name = $providedName;
                } else {
                    // Tạo tên hiển thị tự động dựa trên điểm và lượt quay
                    $name = '';
                    if ($points > 0) {
                        $name .= $points . ' điểm';
                    }

                    if ($points > 0 && $spinTickets > 0) {
                        $name .= ' + ';
                    }

                    if ($spinTickets > 0) {
                        $name .= $spinTickets . ' lượt quay';
                    }

                    if (empty($name)) {
                        $name = 'Không có phần thưởng';
                    }
                }

                $rewards[] = [
                    'day' => (int)$day,
                    'name' => $name,
                    'points' => $points,
                    'spin_tickets' => $spinTickets
                ];
            }

            // Sắp xếp rewards theo thứ tự ngày
            usort($rewards, function($a, $b) {
                return $a['day'] - $b['day'];
            });

            try {
                $saveResult = $this->checkinRepository->saveCheckinRewards($rewards);

                if (!$saveResult) {
                    return redirect()->route('admin.checkin.settings')
                        ->with('error', 'Lỗi khi lưu cài đặt phần thưởng. Kiểm tra quyền ghi file hoặc đường dẫn.');
                }
            } catch (\Exception $e) {
                return redirect()->route('admin.checkin.settings')
                    ->with('error', 'Lỗi khi lưu cài đặt: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.checkin.settings')
            ->with('success', 'Cài đặt điểm danh đã được cập nhật thành công.');
    }

    /**
     * Hiển thị lịch sử điểm danh của một hoặc tất cả người dùng
     *
     * @param Request $request
     * @param int|null $userId
     * @return \Illuminate\View\View
     */
    public function history(Request $request, $userId = null)
    {
        $params = $request->only([
            'date_from',
            'date_to',
            'sort_field',
            'sort_direction',
            'per_page'
        ]);

        if ($userId) {
            $user = User::findOrFail($userId);
            $checkins = $this->checkinRepository->getUserCheckins($userId, $params);
            return view('admin.game.checkin.user_history', compact('checkins', 'user'));
        }

        $checkins = $this->checkinRepository->getAllCheckins($params);
        return view('admin.game.checkin.history', compact('checkins'));
    }
}
