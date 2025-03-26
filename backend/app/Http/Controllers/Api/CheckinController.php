<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DailyCheckin;
use App\Models\PointTransaction;
use App\Models\UserSpin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Repositories\Checkin\CheckinInterface;
use Illuminate\Support\Facades\Log;

class CheckinController extends Controller
{
    protected $checkinRepository;

    public function __construct(CheckinInterface $checkinRepository)
    {
        $this->checkinRepository = $checkinRepository;
    }

    /**
     * Người dùng thực hiện điểm danh hàng ngày
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkIn(Request $request)
    {
        try {
            // Lấy thông tin người dùng
            $userId = $request->input('user_id');
            $user = User::findOrFail($userId);

            // Kiểm tra xem người dùng đã điểm danh hôm nay chưa
            $today = Carbon::today()->format('Y-m-d');
            $existingCheckin = DailyCheckin::where('user_id', $userId)
                ->whereDate('checkin_date', $today)
                ->first();

            if ($existingCheckin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã điểm danh hôm nay rồi!'
                ]);
            }

            // Tính ngày trong chu kỳ (từ 1-7) dựa vào ngày trong tuần
            // Trong PHP: 1 = Thứ 2, 7 = Chủ Nhật
            $dayOfWeek = Carbon::today()->dayOfWeek;

            // Chuyển đổi để 1 = Thứ 2, 7 = Chủ Nhật
            $dayInCycle = $dayOfWeek == 0 ? 7 : $dayOfWeek;

            // Log thông tin
            Log::info('Checkin day of week', [
                'user_id' => $userId,
                'date' => Carbon::today()->format('Y-m-d'),
                'day_of_week' => $dayOfWeek,
                'day_in_cycle' => $dayInCycle
            ]);

            // Kiểm tra điểm danh liên tiếp (chỉ để hiển thị số ngày liên tiếp)
            $latestCheckin = DailyCheckin::where('user_id', $userId)
                ->orderBy('checkin_date', 'desc')
                ->first();

            $isConsecutive = false;
            $consecutiveDays = 1;

            if ($latestCheckin) {
                $lastCheckinDate = Carbon::parse($latestCheckin->checkin_date);
                $daysDifference = $lastCheckinDate->diffInDays(Carbon::today());

                // Nếu điểm danh ngày hôm qua thì là liên tiếp
                if ($daysDifference === 1) {
                    $isConsecutive = true;
                    $consecutiveDays = $latestCheckin->consecutive_days + 1;
                }
            }

            // Lấy phần thưởng từ cài đặt
            $rewards = $this->checkinRepository->getCheckinRewards();
            $reward = null;

            // Tìm phần thưởng cho ngày hiện tại trong chu kỳ
            foreach ($rewards as $r) {
                if ($r['day'] == $dayInCycle) {
                    $reward = $r;
                    break;
                }
            }

            // Nếu không tìm thấy phần thưởng, sử dụng giá trị mặc định
            if (!$reward) {
                // Áp dụng phần thưởng theo ngày trong chu kỳ 7 ngày
                switch ($dayInCycle) {
                    case 1:
                        $pointsEarned = 5;
                        $spinTickets = 0;
                        break;
                    case 2:
                        $pointsEarned = 10;
                        $spinTickets = 0;
                        break;
                    case 3:
                        $pointsEarned = 15;
                        $spinTickets = 0;
                        break;
                    case 4:
                        $pointsEarned = 20;
                        $spinTickets = 0;
                        break;
                    case 5:
                        $pointsEarned = 0;
                        $spinTickets = 1;
                        break;
                    case 6:
                        $pointsEarned = 30;
                        $spinTickets = 0;
                        break;
                    case 7:
                        $pointsEarned = 50;
                        $spinTickets = 2;
                        break;
                }
            } else {
                $pointsEarned = $reward['points'] ?? 0;
                $spinTickets = $reward['spin_tickets'] ?? 0;
            }

            // Bắt đầu transaction để đảm bảo tính nhất quán dữ liệu
            DB::beginTransaction();

            // Tạo bản ghi điểm danh mới
            $checkin = new DailyCheckin();
            $checkin->user_id = $userId;
            $checkin->checkin_date = Carbon::now();
            $checkin->consecutive_days = $consecutiveDays;
            $checkin->points_earned = $pointsEarned;
            $checkin->spin_tickets = $spinTickets;
            $checkin->save();

            // Cập nhật điểm cho người dùng
            if ($pointsEarned > 0) {
                $user->points += $pointsEarned;
                $user->save();

                // Lưu lịch sử giao dịch điểm
                $pointTransaction = new PointTransaction();
                $pointTransaction->user_id = $userId;
                $pointTransaction->points = $pointsEarned;
                $pointTransaction->activity_type = 'daily_checkin';
                $pointTransaction->transaction_date = Carbon::now();
                $pointTransaction->save();
            }

            // Cập nhật lượt quay cho người dùng nếu có
            if ($spinTickets > 0) {
                // Log trước khi thực hiện
                Log::info('Bắt đầu cập nhật lượt quay', [
                    'user_id' => $userId,
                    'spin_tickets' => $spinTickets,
                    'ngày trong chu kỳ' => $dayInCycle
                ]);

                try {
                    // Tìm bản ghi UserSpin hiện tại của người dùng hoặc tạo mới
                    $userSpin = UserSpin::where('user_id', $userId)->first();

                    if ($userSpin) {
                        // Cập nhật số lượt quay hiện có
                        $userSpin->spin_count += $spinTickets;
                        $savedResult = $userSpin->save();

                        Log::info('Cập nhật lượt quay', [
                            'user_id' => $userId,
                            'spin_tickets_added' => $spinTickets,
                            'total_spin_count' => $userSpin->spin_count,
                            'saved_result' => $savedResult
                        ]);
                    } else {
                        // Tạo bản ghi mới nếu chưa có
                        $userSpin = new UserSpin();
                        $userSpin->user_id = $userId;
                        $userSpin->spin_count = $spinTickets;
                        $userSpin->spin_time = null; // Chưa quay lần nào
                        $userSpin->prize_id = null; // Chưa trúng giải nào
                        $savedResult = $userSpin->save();

                        Log::info('Tạo mới lượt quay', [
                            'user_id' => $userId,
                            'spin_tickets' => $spinTickets,
                            'saved_result' => $savedResult
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Lỗi khi lưu lượt quay', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            } else {
                Log::info('Không có lượt quay để cập nhật', [
                    'user_id' => $userId,
                    'ngày trong chu kỳ' => $dayInCycle,
                    'spin_tickets' => $spinTickets
                ]);
            }

            DB::commit();

            // Trả về thông tin kết quả
            return response()->json([
                'success' => true,
                'message' => 'Điểm danh thành công!',
                'data' => [
                    'checkin_date' => $checkin->checkin_date,
                    'consecutive_days' => $consecutiveDays,
                    'day_in_cycle' => $dayInCycle,
                    'points_earned' => $pointsEarned,
                    'spin_tickets' => $spinTickets,
                    'user_points' => $user->points,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy lịch sử điểm danh của người dùng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHistory(Request $request)
    {
        try {
            $userId = $request->input('user_id');
            $limit = $request->input('limit', 30);

            $history = DailyCheckin::where('user_id', $userId)
                ->orderBy('checkin_date', 'desc')
                ->limit($limit)
                ->get();

            // Lấy thông tin điểm danh liên tiếp hiện tại
            $latestCheckin = DailyCheckin::where('user_id', $userId)
                ->orderBy('checkin_date', 'desc')
                ->first();

            $currentStreak = 0;
            $lastCheckinDate = null;

            if ($latestCheckin) {
                $lastCheckinDate = $latestCheckin->checkin_date;

                // Nếu lần cuối điểm danh là hôm nay hoặc hôm qua, thì lấy số ngày liên tiếp
                $daysDifference = Carbon::parse($lastCheckinDate)->diffInDays(Carbon::today());
                if ($daysDifference <= 1) {
                    $currentStreak = $latestCheckin->consecutive_days;
                }
            }

            $checkedInToday = false;
            if ($latestCheckin && Carbon::parse($latestCheckin->checkin_date)->isToday()) {
                $checkedInToday = true;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'history' => $history,
                    'current_streak' => $currentStreak,
                    'last_checkin' => $lastCheckinDate,
                    'checked_in_today' => $checkedInToday
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy thông tin phần thưởng của các ngày trong chu kỳ điểm danh
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRewards()
    {
        try {
            // Lấy cài đặt phần thưởng điểm danh từ repository
            $rewards = $this->checkinRepository->getCheckinRewards();

            // Nếu không có dữ liệu từ repository, sử dụng giá trị mặc định
            if (empty($rewards)) {
                $rewards = [
                    ['day' => 1, 'name' => '5 điểm', 'points' => 5, 'spin_tickets' => 0],
                    ['day' => 2, 'name' => '10 điểm', 'points' => 10, 'spin_tickets' => 0],
                    ['day' => 3, 'name' => '15 điểm', 'points' => 15, 'spin_tickets' => 0],
                    ['day' => 4, 'name' => '20 điểm', 'points' => 20, 'spin_tickets' => 0],
                    ['day' => 5, 'name' => '1 lượt quay', 'points' => 0, 'spin_tickets' => 5],
                    ['day' => 6, 'name' => '30 điểm', 'points' => 30, 'spin_tickets' => 0],
                    ['day' => 7, 'name' => '50 điểm + 2 lượt quay', 'points' => 50, 'spin_tickets' => 2],
                ];
            }

            // Đảm bảo dữ liệu trả về có kiểu số nguyên
            foreach ($rewards as &$reward) {
                $reward['day'] = (int)$reward['day'];
                $reward['points'] = (int)$reward['points'];
                $reward['spin_tickets'] = (int)$reward['spin_tickets'];
            }

            return response()->json([
                'success' => true,
                'data' => $rewards
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy dữ liệu phần thưởng: ' . $e->getMessage()
            ], 500);
        }
    }
}
