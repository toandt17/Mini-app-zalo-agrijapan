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

        return view('admin.game.checkin.settings', compact('settings'));
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
            'points_reward' => 'required|integer|min:0',
            'spin_tickets_reward' => 'required|integer|min:0'
        ]);

        $settings = $request->only([
            'points_reward',
            'spin_tickets_reward',
            'consecutive_bonus'
        ]);

        // Chuyển đổi checkbox consecutive_bonus
        $settings['consecutive_bonus'] = isset($settings['consecutive_bonus']) ? 1 : 0;

        $this->checkinRepository->updateCheckinSettings($settings);

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
