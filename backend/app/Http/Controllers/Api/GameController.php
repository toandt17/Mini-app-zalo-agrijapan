<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Game\GameInterface;
use App\Models\UserSpin;
use App\Models\User;
use App\Models\SpinHistory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\SpinWheel;
use App\Models\Mission;
use App\Models\UserMission;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
    protected $gameRepository;

    public function __construct(gameInterface $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }
    public function reward(Request $request)
    {
        $data = $request->all();
        $phone = $data['phone'];
        $reward = $data['reward'];
        $message = "Chúc mừng bạn đã nhận được phần thưởng $reward từ chúng tôi!";
        return response()->json([
            'message' => $message
        ]);
    }

    public function index()
    {
        $gameLk = $this->gameRepository->getAll();

        // Chỉ lấy các trường cần thiết và bọc trong key "lucky_wheel"
        $formattedData = $gameLk->map(function ($item) {
            return [
                'id' => $item->id,
                'prize_name' => $item->prize_name
            ];
        });

        return response()->json(['lucky_wheel' => $formattedData], 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function index_lucky()
    {
        $lucky = $this->gameRepository->getAll();
        return view('admin.game.index', compact('lucky'));
    }

    public function add_lucky()
    {
        return view('admin.game.add');
    }




    public function edit_lucky($id)
    {
        $lucky = $this->gameRepository->getById($id);
        if (!$lucky) {
            return redirect()->route('admin.game.index_lucky')->with('error', 'Loại sản phẩm không tồn tại.');
        }
        return view('admin.game.edit_lucky', compact('new_lucky'));
    }

    public function update_lucky(Request $request, $id)
    {
        $validator = $this->gameRepository->validate($request->all());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $lucky = $this->gameRepository->getById($id);

        if ($lucky) {
            // Xử lý tải lên ảnh mới nếu có
            if ($request->hasFile('img')) {
                // Xóa ảnh cũ nếu có
                if ($lucky->img) {
                    Storage::disk('public')->delete($lucky->img);
                }
                $img = $request->file('img');
                $imagePath = $img->store('lucky', 'public');
                $data['img'] = $imagePath;
            }

            $this->gameRepository->update($id, $data);
            return redirect()->route('admin.game.index_lucky')->with('success', 'Loại sản phẩm đã được cập nhật thành công.');
        }

        return redirect()->route('admin.game.index_lucky')->with('error', 'Loại sản phẩm không tồn tại.');
    }

    /**
     * Lấy số lượt quay của người dùng
     *
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserSpinTickets($userId)
    {
        try {
            // Kiểm tra user tồn tại không
            $user = User::find($userId);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng'
                ], 404);
            }

            // Lấy thông tin lượt quay từ bảng UserSpin
            $userSpin = UserSpin::where('user_id', $userId)->first();

            if (!$userSpin) {
                // Nếu chưa có bản ghi nào, trả về 0 lượt quay
                return response()->json([
                    'success' => true,
                    'data' => [
                        'user_id' => $userId,
                        'spin_count' => 0,
                        'last_spin' => null,
                        'last_prize' => null
                    ]
                ]);
            }

            // Trả về số lượt quay và thông tin quay gần nhất
            return response()->json([
                'success' => true,
                'data' => [
                    'user_id' => $userId,
                    'spin_count' => $userSpin->spin_count,
                    'last_spin' => $userSpin->spin_time,
                    'last_prize' => $userSpin->prize_id
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy số lượt quay: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy thông tin lượt quay: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sử dụng một lượt quay và lưu kết quả
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function useSpinTicket(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);

            $userId = $request->input('user_id');

            // Tìm bản ghi UserSpin của người dùng
            $userSpin = UserSpin::where('user_id', $userId)->first();

            // Nếu chưa có, tạo mới với số lượt quay mặc định là 0
            if (!$userSpin) {
                $userSpin = new UserSpin([
                    'user_id' => $userId,
                    'spin_count' => 0,
                    'spin_time' => now()
                ]);
                $userSpin->save();
            }

            // Kiểm tra xem người dùng còn lượt quay không
            if ($userSpin->spin_count <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đã hết lượt quay'
                ], 400);
            }

            // Chọn ngẫu nhiên phần thưởng dựa trên xác suất
            $prize = $this->pickRandomPrize();

            if (!$prize) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không có phần thưởng nào khả dụng'
                ], 500);
            }

            // Bắt đầu transaction để đảm bảo tính nhất quán
            DB::beginTransaction();

            try {
                // Giảm số lượng phần thưởng còn lại (nếu có)
                if ($prize->has_reward && $prize->remaining_quantity > 0) {
                    $prize->remaining_quantity -= 1;
                    $prize->save();
                }

                // Tìm user để cộng điểm nếu phần thưởng là điểm (category = 0)
                $pointsEarned = 0;
                if ($prize->category === 0) {
                    $user = User::find($userId);

                    // Lấy số điểm từ description hoặc tên giải thưởng
                    if (preg_match('/(\d+)/', $prize->prize_name, $matches)) {
                        $pointsEarned = (int) $matches[0];
                    } elseif (preg_match('/(\d+)/', $prize->description ?? '', $matches)) {
                        $pointsEarned = (int) $matches[0];
                    }

                    if ($pointsEarned > 0 && $user) {
                        $user->points = ($user->points ?? 0) + $pointsEarned;
                        $user->save();

                        // Ghi log cộng điểm
                        Log::info("User {$userId} được cộng {$pointsEarned} điểm từ vòng quay may mắn");
                    }
                }

                // Giảm số lượt quay
                $userSpin->spin_count -= 1;
                $userSpin->spin_time = now();
                $userSpin->prize_id = $prize->id;
                $userSpin->save();

                // Lưu lịch sử quay thưởng
                SpinHistory::create([
                    'user_id' => $userId,
                    'prize_id' => $prize->id,
                    'spin_time' => now()
                ]);

                DB::commit();

                // Log để debug
                Log::info("User {$userId} đã quay được phần thưởng {$prize->prize_name}, prize_id: {$prize->id}, has_reward: {$prize->has_reward}, category: {$prize->category}, remaining_quantity: {$prize->remaining_quantity}");

                $responseData = [
                    'user_id' => $userId,
                    'remaining_spins' => $userSpin->spin_count,
                    'prize_id' => $prize->id,
                    'prize_name' => $prize->prize_name,
                    'prize_description' => $prize->description,
                    'prize_image' => $prize->image,
                    'has_reward' => $prize->has_reward,
                    'category' => $prize->category,
                    'remaining_quantity' => $prize->remaining_quantity,
                    'spin_time' => $userSpin->spin_time
                ];

                // Thêm thông tin điểm nếu phần thưởng là điểm
                if ($prize->category === 0 && $pointsEarned > 0) {
                    $responseData['points_earned'] = $pointsEarned;
                    $responseData['message'] = "Chúc mừng! Bạn đã nhận được {$pointsEarned} điểm.";
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Đã sử dụng 1 lượt quay thành công',
                    'data' => $responseData
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Lỗi khi sử dụng lượt quay: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi sử dụng lượt quay: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Chọn phần thưởng ngẫu nhiên dựa trên xác suất và số lượng còn lại
     *
     * @return \App\Models\SpinWheel|null
     */
    private function pickRandomPrize()
    {
        // Lấy tất cả phần thưởng còn khả dụng
        $prizes = DB::table('spin_wheel')
            ->where(function($query) {
                $query->where('has_reward', false)
                      ->orWhere(function($q) {
                          $q->where('has_reward', true)
                            ->where('remaining_quantity', '>', 0);
                      });
            })
            ->get();

        if ($prizes->isEmpty()) {
            return null;
        }

        // Tính tổng xác suất
        $totalProbability = $prizes->sum('probability');

        // Nếu tổng xác suất không bằng 100, điều chỉnh
        if ($totalProbability > 0 && $totalProbability != 100) {
            $adjustmentFactor = 100 / $totalProbability;
            $prizes = $prizes->map(function($prize) use ($adjustmentFactor) {
                $prize->probability *= $adjustmentFactor;
                return $prize;
            });
        }

        // Chọn phần thưởng ngẫu nhiên dựa trên xác suất
        $randomValue = mt_rand(1, 100);
        $cumulativeProbability = 0;

        foreach ($prizes as $prize) {
            $cumulativeProbability += $prize->probability;
            if ($randomValue <= $cumulativeProbability) {
                return SpinWheel::find($prize->id);
            }
        }

        // Nếu không có phần thưởng nào được chọn (hiếm khi xảy ra), chọn phần thưởng đầu tiên
        return SpinWheel::find($prizes->first()->id);
    }

    /**
     * Lấy lịch sử quay số của người dùng
     *
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSpinHistory($userId)
    {
        try {
            // Kiểm tra user tồn tại không
            $user = User::find($userId);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng'
                ], 404);
            }

            // Lấy lịch sử quay số, mới nhất lên đầu
            $history = SpinHistory::where('user_id', $userId)
                ->orderBy('spin_time', 'desc')
                ->get();

            // Lấy thông tin chi tiết phần thưởng
            $historyWithDetails = $history->map(function($item) {
                $prize = DB::table('spin_wheel')->where('id', $item->prize_id)->first();
                return [
                    'id' => $item->id,
                    'spin_time' => $item->spin_time,
                    'prize_id' => $item->prize_id,
                    'prize_name' => $prize ? $prize->prize_name : 'Phần thưởng không xác định'
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'user_id' => $userId,
                    'history' => $historyWithDetails,
                    'total_spins' => count($history)
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy lịch sử quay số: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy lịch sử quay số: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Thêm lượt quay cho người dùng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addSpinTickets(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'tickets' => 'required|integer|min:1',
                'source' => 'nullable|string',
            ]);

            $userId = $request->input('user_id');
            $ticketsToAdd = $request->input('tickets');
            $source = $request->input('source', 'admin');

            // Tìm bản ghi UserSpin của người dùng
            $userSpin = UserSpin::where('user_id', $userId)->first();

            // Nếu chưa có, tạo mới với số lượt quay là ticketsToAdd
            if (!$userSpin) {
                $userSpin = new UserSpin([
                    'user_id' => $userId,
                    'spin_count' => $ticketsToAdd,
                    'spin_time' => now()
                ]);
            } else {
                // Nếu đã có, cộng thêm số lượt quay
                $userSpin->spin_count += $ticketsToAdd;
            }

            $userSpin->save();

            // Ghi log nếu cần
            Log::info("Đã thêm $ticketsToAdd lượt quay cho user ID: $userId, nguồn: $source");

            return response()->json([
                'success' => true,
                'message' => "Đã thêm $ticketsToAdd lượt quay thành công",
                'data' => [
                    'user_id' => $userId,
                    'new_spin_count' => $userSpin->spin_count,
                    'added' => $ticketsToAdd,
                    'source' => $source
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Lỗi khi thêm lượt quay: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm lượt quay: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy danh sách quà tặng của người dùng
     *
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserGifts($userId)
    {
        try {
            // Kiểm tra user tồn tại không
            $user = User::find($userId);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng'
                ], 404);
            }

            // Lấy lịch sử quà tặng từ SpinHistory, chỉ lọc các phần thưởng có has_reward = true
            $gifts = DB::table('spin_histories')
                ->join('spin_wheel', 'spin_histories.prize_id', '=', 'spin_wheel.id')
                ->select(
                    'spin_histories.id',
                    'spin_histories.spin_time as received_time',
                    'spin_wheel.id as gift_id',
                    'spin_wheel.prize_name as gift_name',
                    'spin_wheel.description',
                    'spin_wheel.image',
                    'spin_wheel.has_reward',
                    'spin_wheel.category'
                )
                ->where('spin_histories.user_id', $userId)
                ->where('spin_wheel.has_reward', true)
                ->orderBy('spin_histories.spin_time', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'user_id' => $userId,
                    'gifts' => $gifts,
                    'total_gifts' => count($gifts)
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy danh sách quà tặng: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy danh sách quà tặng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Đổi quà cho người dùng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function claimUserGift(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'gift_id' => 'required|exists:spin_wheel,id',
                'shipping_info' => 'nullable|string'
            ]);

            $userId = $request->input('user_id');
            $giftId = $request->input('gift_id');
            $shippingInfo = $request->input('shipping_info');

            // Kiểm tra quà tặng có tồn tại và còn số lượng
            $gift = SpinWheel::find($giftId);
            if (!$gift) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy quà tặng'
                ], 404);
            }

            if (!$gift->has_reward) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không phải là quà tặng có thể đổi'
                ], 400);
            }

            if ($gift->remaining_quantity <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quà tặng đã hết số lượng'
                ], 400);
            }

            // Lưu thông tin đổi quà
            DB::beginTransaction();

            try {
                // Giảm số lượng quà tặng
                $gift->remaining_quantity -= 1;
                $gift->save();

                // Lưu lịch sử đổi quà (sử dụng bảng SpinHistory)
                $giftHistory = SpinHistory::create([
                    'user_id' => $userId,
                    'prize_id' => $giftId,
                    'spin_time' => now(),
                    // Không cần thêm trường mới, bạn có thể sử dụng các trường hiện có
                ]);

                // Lưu thông tin vận chuyển nếu có (có thể lưu vào table khác nếu cần)
                if ($shippingInfo) {
                    // Có thể lưu thông tin vận chuyển vào bảng mới hoặc ghi log
                    Log::info("Thông tin vận chuyển quà ID {$giftId} cho user {$userId}: {$shippingInfo}");
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Đổi quà thành công',
                    'data' => [
                        'user_id' => $userId,
                        'gift_id' => $giftId,
                        'gift_name' => $gift->prize_name,
                        'claimed_at' => now(),
                        'remaining_quantity' => $gift->remaining_quantity
                    ]
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Lỗi khi đổi quà: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đổi quà: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy lịch sử đổi quà của người dùng
     *
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGiftHistory($userId)
    {
        try {
            // Kiểm tra user tồn tại không
            $user = User::find($userId);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng'
                ], 404);
            }

            // Lấy lịch sử đổi quà, dùng bảng SpinHistory và thêm điều kiện has_reward = true
            $history = DB::table('spin_histories')
                ->join('spin_wheel', 'spin_histories.prize_id', '=', 'spin_wheel.id')
                ->select(
                    'spin_histories.id',
                    'spin_histories.spin_time as claimed_at',
                    'spin_wheel.id as gift_id',
                    'spin_wheel.prize_name as gift_name',
                    'spin_wheel.description',
                    'spin_wheel.image',
                    'spin_wheel.category'
                )
                ->where('spin_histories.user_id', $userId)
                ->where('spin_wheel.has_reward', true)
                ->orderBy('spin_histories.spin_time', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'user_id' => $userId,
                    'history' => $history,
                    'total_gifts_claimed' => count($history)
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy lịch sử đổi quà: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy lịch sử đổi quà: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy lịch sử giao dịch điểm của người dùng
     *
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserPointTransactions($userId)
    {
        try {
            // Kiểm tra user tồn tại không
            $user = User::find($userId);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng'
                ], 404);
            }

            // Lấy lịch sử giao dịch điểm, mới nhất lên đầu
            $transactions = DB::table('point_transactions')
                ->where('user_id', $userId)
                ->orderBy('transaction_date', 'desc')
                ->limit(20) // Giới hạn 20 giao dịch gần nhất
                ->get();

            // Format lại dữ liệu để phục vụ frontend
            $formattedTransactions = $transactions->map(function ($item) {
                return [
                    'id' => $item->id,
                    'points' => $item->points,
                    'activity_type' => $item->activity_type,
                    'date' => date('d/m/Y', strtotime($item->transaction_date)),
                    'type' => $item->points > 0 ? 'earn' : 'spend'
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'user_id' => $userId,
                    'transactions' => $formattedTransactions,
                    'total_transactions' => count($transactions)
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy lịch sử giao dịch điểm: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy lịch sử giao dịch điểm: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy thống kê điểm của người dùng theo ngày trong tuần
     *
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserPointsStatistics($userId)
    {
        try {
            // Kiểm tra user tồn tại không
            $user = User::find($userId);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng'
                ], 404);
            }

            // Lấy ngày đầu tiên của tuần hiện tại (Thứ 2)
            $startOfWeek = now()->startOfWeek();

            // Mảng chứa dữ liệu điểm theo ngày
            $statistics = [];

            // Khởi tạo mảng với giá trị mặc định là 0 cho mỗi ngày trong tuần
            for ($i = 0; $i < 7; $i++) {
                $day = $startOfWeek->copy()->addDays($i);
                $statistics[$i] = [
                    'date' => $day->format('Y-m-d'),
                    'points' => 0,
                    'day_of_week' => $i + 2 > 7 ? 1 : $i + 2 // Thứ 2 = 2, Thứ 3 = 3, ..., CN = 1
                ];
            }

            // Lấy tổng điểm tích lũy theo ngày trong tuần hiện tại
            $pointsData = DB::table('point_transactions')
                ->select(DB::raw('DATE(transaction_date) as date'), DB::raw('SUM(points) as total_points'))
                ->where('user_id', $userId)
                ->whereBetween('transaction_date', [$startOfWeek->format('Y-m-d'), $startOfWeek->copy()->endOfWeek()->format('Y-m-d')])
                ->groupBy(DB::raw('DATE(transaction_date)'))
                ->get();

            // Cập nhật mảng statistics với dữ liệu thực tế
            foreach ($pointsData as $point) {
                $date = new \DateTime($point->date);
                $dayOfWeek = $date->format('N'); // 1 (Thứ 2) đến 7 (Chủ nhật)
                $index = $dayOfWeek == 7 ? 6 : $dayOfWeek - 1; // Chuyển sang index từ 0-6

                $statistics[$index]['points'] = (int) $point->total_points;
            }

            // Format lại dữ liệu để phù hợp với biểu đồ
            $labels = ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'];
            $data = array_column($statistics, 'points');

            return response()->json([
                'success' => true,
                'data' => [
                    'user_id' => $userId,
                    'labels' => $labels,
                    'data' => $data,
                    'statistics' => $statistics
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy thống kê điểm: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy thống kê điểm: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy danh sách câu hỏi trắc nghiệm
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQuizQuestions(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'limit' => 'nullable|integer|min:1|max:20',
                'difficulty' => 'nullable|string|in:easy,medium,hard',
                'user_id' => 'required|integer' // Make user_id required
            ]);

            $limit = $request->input('limit', 5); // Mặc định lấy 5 câu hỏi
            $difficulty = $request->input('difficulty');
            $userId = $request->input('user_id');

            // Query câu hỏi
            $query = DB::table('quiz_questions');

            // Lọc theo độ khó nếu có
            if ($difficulty) {
                $query->where('difficulty_level', $difficulty);
            }

            // Lấy tất cả câu hỏi để xử lý logic trước khi trả về
            $allQuestions = $query->orderBy('id')->get();

            // Kiểm tra tiến trình hiện tại của người dùng
            $lastAttempts = DB::table('user_quiz_attempts')
                ->where('user_id', $userId)
                ->select('question_id', 'is_correct')
                ->orderBy('question_id', 'asc')
                ->get();

            // Chuyển đổi thành mảng id để dễ kiểm tra
            $answeredQuestions = [];
            $lastCorrectQuestionId = 0;

            foreach ($lastAttempts as $attempt) {
                $answeredQuestions[$attempt->question_id] = $attempt->is_correct;
                if ($attempt->is_correct) {
                    $lastCorrectQuestionId = $attempt->question_id;
                }
            }

            // Tìm câu hỏi tiếp theo chưa trả lời đúng
            $nextQuestionId = 0;
            $foundActiveQuestion = false;

            foreach ($allQuestions as $question) {
                // Nếu câu hỏi chưa được trả lời hoặc đã trả lời sai
                if (!isset($answeredQuestions[$question->id]) || $answeredQuestions[$question->id] === false) {
                    // Nếu chưa có câu trả lời đúng nào hoặc câu hỏi này lớn hơn câu cuối đã trả lời đúng
                    if ($lastCorrectQuestionId == 0 || $question->id > $lastCorrectQuestionId) {
                        $nextQuestionId = $question->id;
                        $foundActiveQuestion = true;
                        break;
                    }
                }
            }

            // Nếu không tìm thấy câu hỏi tiếp theo, lấy $limit câu hỏi theo thứ tự bình thường
            $questions = $allQuestions->take($limit);

            // Bổ sung thông tin về câu hỏi hiện tại cho front-end
            $currentProgress = [
                'next_question_id' => $nextQuestionId,
                'has_active_question' => $foundActiveQuestion
            ];

            // Format lại dữ liệu câu hỏi
            $formattedQuestions = $questions->map(function ($question) use ($answeredQuestions) {
                $reward = null;

                // Lấy thông tin phần thưởng nếu có
                if ($question->reward_id) {
                    $rewardInfo = DB::table('rewards')->find($question->reward_id);
                    if ($rewardInfo) {
                        $reward = [
                            'id' => $rewardInfo->id,
                            'name' => $rewardInfo->name,
                            'description' => $rewardInfo->description,
                            'image' => $rewardInfo->image
                        ];
                    }
                }

                // Chuyển đổi correct_answer từ chữ cái sang index
                $correctAnswerIndex = $this->convertCorrectAnswerToIndex($question->correct_answer);

                $questionData = [
                    'id' => $question->id,
                    'question' => $question->question,
                    'options' => [
                        $question->option_a,
                        $question->option_b,
                        $question->option_c,
                        $question->option_d
                    ],
                    'correct_answer' => $correctAnswerIndex, // Đã chuyển đổi sang index 0-3
                    'difficulty' => $question->difficulty_level,
                    'points_reward' => $question->points_reward,
                    'spin_tickets' => $question->spin_tickets,
                    'reward' => $reward
                ];

                // Thêm thông tin về trạng thái câu hỏi (đã trả lời hay chưa)
                if (isset($answeredQuestions[$question->id])) {
                    $questionData['answered'] = true;
                    $questionData['answered_correctly'] = $answeredQuestions[$question->id];
                } else {
                    $questionData['answered'] = false;
                }

                return $questionData;
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'questions' => $formattedQuestions,
                    'total' => count($formattedQuestions),
                    'current_progress' => $currentProgress
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy câu hỏi trắc nghiệm: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy câu hỏi trắc nghiệm: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xử lý việc trả lời câu hỏi trắc nghiệm
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function answerQuizQuestion(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'question_id' => 'required|exists:quiz_questions,id',
                'selected_option' => 'required|integer|min:0|max:3'
            ]);

            $userId = $request->input('user_id');
            $questionId = $request->input('question_id');
            $selectedOption = $request->input('selected_option'); // Index từ 0-3

            // Tìm thông tin câu hỏi
            $question = DB::table('quiz_questions')->find($questionId);
            if (!$question) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy câu hỏi'
                ], 404);
            }

            // Chuyển đổi correct_answer từ a,b,c,d sang index 0,1,2,3
            $correctAnswerIndex = $this->convertCorrectAnswerToIndex($question->correct_answer);

            // Kiểm tra xem người dùng đã trả lời câu hỏi này chưa
            $existingAttempt = DB::table('user_quiz_attempts')
                ->where('user_id', $userId)
                ->where('question_id', $questionId)
                ->first();

            // Nếu đã trả lời rồi thì trả về kết quả cũ
            if ($existingAttempt) {
                // Tìm câu hỏi tiếp theo (chỉ khi đã trả lời đúng câu hiện tại)
                $nextQuestionId = null;
                if ($existingAttempt->is_correct) {
                    $nextQuestion = DB::table('quiz_questions')
                        ->where('id', '>', $questionId)
                        ->orderBy('id')
                        ->first();

                    if ($nextQuestion) {
                        $nextQuestionId = $nextQuestion->id;
                    }
                }

                // Lấy thông tin phần thưởng nếu có
                $reward = null;
                if ($existingAttempt->reward_id) {
                    $rewardInfo = DB::table('rewards')->find($existingAttempt->reward_id);
                    if ($rewardInfo) {
                        $reward = [
                            'id' => $rewardInfo->id,
                            'name' => $rewardInfo->name,
                            'description' => $rewardInfo->description,
                            'image' => $rewardInfo->image
                        ];
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => $existingAttempt->is_correct
                        ? 'Bạn đã trả lời đúng câu hỏi này trước đó'
                        : 'Bạn đã trả lời sai câu hỏi này trước đó',
                    'data' => [
                        'is_correct' => $existingAttempt->is_correct,
                        'selected_option' => $existingAttempt->selected_option,
                        'correct_answer' => $correctAnswerIndex,
                        'points_earned' => $existingAttempt->points_earned,
                        'spin_tickets' => $existingAttempt->spin_tickets,
                        'next_question_id' => $nextQuestionId,
                        'reward' => $reward,
                        'already_answered' => true
                    ]
                ]);
            }

            // Kiểm tra xem đây có phải là câu hỏi tiếp theo cần trả lời không
            // Lấy câu hỏi gần nhất đã trả lời đúng
            $lastCorrectAttempt = DB::table('user_quiz_attempts')
                ->where('user_id', $userId)
                ->where('is_correct', true)
                ->orderBy('id', 'desc')
                ->first();

            // Nếu chưa có câu trả lời đúng nào và đây không phải câu hỏi đầu tiên
            // hoặc đã có câu trả lời đúng và câu hỏi này không tiếp nối theo thứ tự
            $firstQuestion = DB::table('quiz_questions')
                ->orderBy('id')
                ->first();

            if (($lastCorrectAttempt === null && $questionId != $firstQuestion->id) ||
                ($lastCorrectAttempt !== null && $question->id <= $lastCorrectAttempt->question_id)) {
                // Kiểm tra nếu câu hỏi này không phải là câu tiếp theo
                $nextQuestion = DB::table('quiz_questions')
                    ->where('id', '>', $lastCorrectAttempt ? $lastCorrectAttempt->question_id : 0)
                    ->orderBy('id')
                    ->first();

                if ($nextQuestion && $questionId != $nextQuestion->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Bạn cần trả lời các câu hỏi theo đúng thứ tự',
                        'data' => [
                            'next_question_id' => $nextQuestion->id
                        ]
                    ], 400);
                }
            }

            // Kiểm tra câu trả lời
            $isCorrect = $selectedOption == $correctAnswerIndex;

            // Bắt đầu transaction để đảm bảo tính nhất quán
            DB::beginTransaction();

            try {
                // Phần thưởng điểm nếu trả lời đúng
                $pointsEarned = $isCorrect ? $question->points_reward : 0;

                // Phần thưởng lượt quay nếu trả lời đúng
                $spinTickets = $isCorrect ? $question->spin_tickets : 0;

                // Cập nhật điểm người dùng nếu trả lời đúng
                if ($isCorrect && $pointsEarned > 0) {
                    $user = User::find($userId);
                    $user->points = ($user->points ?? 0) + $pointsEarned;
                    $user->save();

                    // Lưu lịch sử giao dịch điểm
                    DB::table('point_transactions')->insert([
                        'user_id' => $userId,
                        'points' => $pointsEarned,
                        'activity_type' => 'quiz_reward',
                        'transaction_date' => now()
                    ]);
                }

                // Thêm lượt quay nếu trả lời đúng và có lượt quay
                if ($isCorrect && $spinTickets > 0) {
                    // Tìm bản ghi UserSpin của người dùng
                    $userSpin = UserSpin::where('user_id', $userId)->first();

                    // Nếu chưa có, tạo mới với số lượt quay là spinTickets
                    if (!$userSpin) {
                        $userSpin = new UserSpin([
                            'user_id' => $userId,
                            'spin_count' => $spinTickets,
                            'spin_time' => now()
                        ]);
                    } else {
                        // Nếu đã có, cộng thêm số lượt quay
                        $userSpin->spin_count += $spinTickets;
                    }

                    $userSpin->save();
                }

                // Lưu thông tin phần thưởng nếu có và trả lời đúng
                $rewardId = null;
                if ($isCorrect && $question->reward_id) {
                    $rewardId = $question->reward_id;
                }

                // Lưu lịch sử trả lời câu hỏi
                DB::table('user_quiz_attempts')->insert([
                    'user_id' => $userId,
                    'question_id' => $questionId,
                    'selected_option' => $selectedOption,
                    'is_correct' => $isCorrect,
                    'points_earned' => $pointsEarned,
                    'reward_id' => $rewardId,
                    'spin_tickets' => $spinTickets,
                    'attempt_time' => now()
                ]);

                DB::commit();

                // Lấy thông tin phần thưởng nếu có
                $reward = null;
                if ($isCorrect && $question->reward_id) {
                    $rewardInfo = DB::table('rewards')->find($question->reward_id);
                    if ($rewardInfo) {
                        $reward = [
                            'id' => $rewardInfo->id,
                            'name' => $rewardInfo->name,
                            'description' => $rewardInfo->description,
                            'image' => $rewardInfo->image
                        ];
                    }
                }

                // Nếu trả lời đúng, tìm câu hỏi tiếp theo để thông báo
                $nextQuestionId = null;
                if ($isCorrect) {
                    $nextQuestion = DB::table('quiz_questions')
                        ->where('id', '>', $questionId)
                        ->orderBy('id')
                        ->first();

                    if ($nextQuestion) {
                        $nextQuestionId = $nextQuestion->id;
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => $isCorrect ? 'Trả lời chính xác!' : 'Trả lời không chính xác!',
                    'data' => [
                        'is_correct' => $isCorrect,
                        'selected_option' => $selectedOption,
                        'correct_answer' => $correctAnswerIndex,
                        'points_earned' => $pointsEarned,
                        'spin_tickets' => $spinTickets,
                        'reward' => $reward,
                        'next_question_id' => $nextQuestionId,
                        'already_answered' => false
                    ]
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Lỗi khi trả lời câu hỏi trắc nghiệm: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi trả lời câu hỏi trắc nghiệm: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Chuyển đổi correct_answer từ chữ cái (a, b, c, d) sang index số (0, 1, 2, 3)
     *
     * @param string|int $answer
     * @return int
     */
    private function convertCorrectAnswerToIndex($answer)
    {
        Log::info("Chuyển đổi correct_answer: " . var_export($answer, true) . " - Kiểu: " . gettype($answer));

        if (is_numeric($answer)) {
            Log::info("Kết quả chuyển đổi (numeric): " . (int)$answer);
            return (int)$answer;
        }

        $result = 0;
        switch(strtolower($answer)) {
            case 'a': $result = 0; break;
            case 'b': $result = 1; break;
            case 'c': $result = 2; break;
            case 'd': $result = 3; break;
            default:
                // Mặc định trả về 0 nếu không nhận diện được
                Log::warning('Giá trị correct_answer không hợp lệ: ' . $answer);
                $result = 0;
        }

        Log::info("Kết quả chuyển đổi (chữ cái): " . $result);
        return $result;
    }

    /**
     * Lấy lịch sử trả lời câu hỏi của người dùng
     *
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQuizHistory($userId)
    {
        try {
            // Kiểm tra user tồn tại không
            $user = User::find($userId);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng'
                ], 404);
            }

            // Lấy lịch sử trả lời câu hỏi, mới nhất lên đầu
            $history = DB::table('user_quiz_attempts as uqa')
                ->join('quiz_questions as qq', 'uqa.question_id', '=', 'qq.id')
                ->leftJoin('rewards as r', 'uqa.reward_id', '=', 'r.id')
                ->select(
                    'uqa.id',
                    'uqa.question_id',
                    'qq.question',
                    'uqa.selected_option',
                    'qq.correct_answer',
                    'uqa.is_correct',
                    'uqa.points_earned',
                    'uqa.spin_tickets',
                    'uqa.attempt_time',
                    'r.id as reward_id',
                    'r.name as reward_name',
                    'r.description as reward_description',
                    'r.image as reward_image'
                )
                ->where('uqa.user_id', $userId)
                ->orderBy('uqa.attempt_time', 'desc')
                ->limit(20)
                ->get();

            // Format lại dữ liệu
            $formattedHistory = $history->map(function ($attempt) {
                $reward = null;
                if ($attempt->reward_id) {
                    $reward = [
                        'id' => $attempt->reward_id,
                        'name' => $attempt->reward_name,
                        'description' => $attempt->reward_description,
                        'image' => $attempt->reward_image
                    ];
                }

                return [
                    'id' => $attempt->id,
                    'question_id' => $attempt->question_id,
                    'question' => $attempt->question,
                    'selected_option' => $attempt->selected_option,
                    'correct_answer' => $attempt->correct_answer,
                    'is_correct' => $attempt->is_correct,
                    'points_earned' => $attempt->points_earned,
                    'spin_tickets' => $attempt->spin_tickets,
                    'attempt_time' => $attempt->attempt_time,
                    'reward' => $reward
                ];
            });

            // Lấy thống kê tổng quát
            $stats = DB::table('user_quiz_attempts')
                ->where('user_id', $userId)
                ->selectRaw('COUNT(*) as total_attempts,
                            SUM(CASE WHEN is_correct = 1 THEN 1 ELSE 0 END) as correct_answers,
                            SUM(points_earned) as total_points,
                            SUM(spin_tickets) as total_tickets')
                ->first();

            return response()->json([
                'success' => true,
                'data' => [
                    'user_id' => $userId,
                    'history' => $formattedHistory,
                    'stats' => [
                        'total_attempts' => $stats->total_attempts,
                        'correct_answers' => $stats->correct_answers,
                        'accuracy' => $stats->total_attempts > 0
                            ? round(($stats->correct_answers / $stats->total_attempts) * 100, 2)
                            : 0,
                        'total_points' => $stats->total_points,
                        'total_tickets' => $stats->total_tickets
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy lịch sử trả lời câu hỏi: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy lịch sử trả lời câu hỏi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy danh sách nhiệm vụ của người dùng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMissions(Request $request)
    {
        try {
            $userId = $request->input('user_id');

            // Nếu không có user_id, lấy tất cả nhiệm vụ chung
            if (!$userId) {
                $missions = $this->gameRepository->getAllMissions();

                // Map để thêm trạng thái mặc định
                $missions = $missions->map(function($mission) {
                    $mission->status = 'available';
                    $mission->completed_at = null;
                    return $mission;
                });

                return response()->json([
                    'success' => true,
                    'data' => $missions
                ]);
            }

            // Nếu có user_id, lấy nhiệm vụ với thông tin hoàn thành
            $missions = $this->gameRepository->getUserMissions($userId);

            if ($missions === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $missions
            ]);

        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy danh sách nhiệm vụ: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy danh sách nhiệm vụ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy chi tiết một nhiệm vụ
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMissionDetail($id, Request $request)
    {
        try {
            $userId = $request->input('user_id');

            // Nếu không có user_id, lấy thông tin nhiệm vụ không kèm trạng thái
            if (!$userId) {
                $mission = $this->gameRepository->getMissionById($id);

                if (!$mission) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Không tìm thấy nhiệm vụ'
                    ], 404);
                }

                // Thêm trạng thái mặc định
                $mission->status = 'available';
                $mission->completed_at = null;

                return response()->json([
                    'success' => true,
                    'data' => $mission
                ]);
            }

            // Nếu có user_id, lấy chi tiết nhiệm vụ với thông tin hoàn thành
            $mission = $this->gameRepository->getMissionDetail($id, $userId);

            if ($mission === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng hoặc nhiệm vụ'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $mission
            ]);

        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy chi tiết nhiệm vụ: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy chi tiết nhiệm vụ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hoàn thành một nhiệm vụ
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function completeMission(Request $request)
    {
        try {
            // Validate dữ liệu đầu vào
            $request->validate([
                'mission_id' => 'required|exists:missions,id',
                'user_id' => 'required|exists:users,id',
            ]);

            $missionId = $request->input('mission_id');
            $userId = $request->input('user_id');
            $actionData = $request->input('action_data');

            // Sử dụng repository để hoàn thành nhiệm vụ
            $result = $this->gameRepository->completeMission($userId, $missionId, $actionData);

            if ($result === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể hoàn thành nhiệm vụ. Nhiệm vụ có thể đã hoàn thành hoặc không tồn tại.'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Hoàn thành nhiệm vụ thành công',
                'data' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('Lỗi khi hoàn thành nhiệm vụ: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi hoàn thành nhiệm vụ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy lịch sử hoàn thành nhiệm vụ của người dùng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMissionHistory(Request $request)
    {
        try {
            $userId = $request->input('user_id');

            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Thiếu thông tin người dùng'
                ], 400);
            }

            // Sử dụng repository để lấy lịch sử nhiệm vụ
            $missionHistory = $this->gameRepository->getMissionHistory($userId);

            return response()->json([
                'success' => true,
                'data' => $missionHistory
            ]);

        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy lịch sử nhiệm vụ: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy lịch sử nhiệm vụ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Đánh dấu video đã được xem
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markVideoWatched(Request $request)
    {
        try {
            // Validate dữ liệu đầu vào
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'video_id' => 'required|string',
                'watch_duration' => 'nullable|integer',
                'mission_id' => 'nullable|exists:missions,id'
            ]);

            $userId = $validated['user_id'];
            $videoId = $validated['video_id'];
            $watchDuration = $validated['watch_duration'] ?? 0;
            $missionId = $validated['mission_id'] ?? null;

            // Lưu thông tin xem video
            DB::table('video_watches')->insert([
                'user_id' => $userId,
                'video_id' => $videoId,
                'watch_duration' => $watchDuration,
                'watched_at' => now()
            ]);

            // Cập nhật tiến trình nhiệm vụ nếu có mission_id
            if ($missionId) {
                $mission = Mission::find($missionId);

                if (!$mission) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Không tìm thấy nhiệm vụ'
                    ], 404);
                }

                // Kiểm tra và cập nhật UserMission
                $userMission = UserMission::where('user_id', $userId)
                    ->where('mission_id', $missionId)
                    ->first();

                if (!$userMission) {
                    // Tạo mới bản ghi UserMission nếu chưa có
                    $userMission = new UserMission([
                        'user_id' => $userId,
                        'mission_id' => $missionId,
                        'status' => 'in_progress',
                        'progress' => 0,
                        'progress_data' => json_encode(['videos_watched' => []]),
                        'last_action_at' => now()
                    ]);
                }

                // Cập nhật tiến trình
                $progressData = json_decode($userMission->progress_data, true) ?: ['videos_watched' => []];

                // Thêm video đã xem nếu chưa có trong danh sách
                if (!in_array($videoId, $progressData['videos_watched'])) {
                    $progressData['videos_watched'][] = $videoId;
                    $userMission->progress_data = json_encode($progressData);

                    // Tính toán tiến trình hoàn thành
                    // Nếu action_required của mission là 'watch_youtube', có thể xác định 1 video = 100%
                    if ($mission->action_required == 'watch_youtube') {
                        $userMission->progress = 100; // Đánh dấu hoàn thành 100%
                        $userMission->status = 'completed';
                        $userMission->completed_at = now();
                    } else {
                        // Nếu nhiệm vụ yêu cầu xem nhiều video
                        $videosRequired = 3; // Mặc định là 3 video, có thể thay đổi tùy theo yêu cầu
                        $progress = min(100, (count($progressData['videos_watched']) / $videosRequired) * 100);
                        $userMission->progress = $progress;

                        // Nếu đã hoàn thành 100%
                        if ($progress >= 100) {
                            $userMission->status = 'completed';
                            $userMission->completed_at = now();
                        }
                    }

                    $userMission->last_action_at = now();
                    $userMission->save();
                }

                // Nếu nhiệm vụ đã hoàn thành, cập nhật phần thưởng cho người dùng
                if ($userMission->status == 'completed') {
                    // Cộng điểm
                    if ($mission->points_reward > 0) {
                        $user = User::find($userId);
                        $user->points += $mission->points_reward;
                        $user->save();

                        // Lưu lịch sử giao dịch điểm
                        DB::table('point_transactions')->insert([
                            'user_id' => $userId,
                            'points' => $mission->points_reward,
                            'description' => 'Hoàn thành nhiệm vụ: ' . $mission->name,
                            'source_type' => 'mission',
                            'source_id' => $mission->id,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }

                    // Cộng lượt quay
                    if ($mission->spin_tickets > 0) {
                        $user = User::find($userId);
                        $user->spin_tickets += $mission->spin_tickets;
                        $user->save();
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Đã ghi nhận xem video thành công',
                'data' => [
                    'video_id' => $videoId,
                    'watch_duration' => $watchDuration,
                    'mission_progress' => $userMission->progress ?? 0,
                    'mission_status' => $userMission->status ?? 'not_started'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Lỗi khi đánh dấu video đã xem: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Theo dõi tiến trình hoàn thành nhiệm vụ
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function trackMissionProgress(Request $request)
    {
        try {
            // Validate dữ liệu đầu vào
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'mission_id' => 'required|exists:missions,id',
                'action_type' => 'required|string',
                'action_data' => 'nullable|array',
                'increment_by' => 'nullable|numeric'
            ]);

            $userId = $validated['user_id'];
            $missionId = $validated['mission_id'];
            $actionType = $validated['action_type'];
            $actionData = $validated['action_data'] ?? [];
            $incrementBy = $validated['increment_by'] ?? 0;

            // Lấy thông tin nhiệm vụ
            $mission = Mission::find($missionId);
            if (!$mission) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy nhiệm vụ'
                ], 404);
            }

            // Kiểm tra và cập nhật UserMission
            $userMission = UserMission::where('user_id', $userId)
                ->where('mission_id', $missionId)
                ->first();

            if (!$userMission) {
                // Tạo mới bản ghi UserMission nếu chưa có
                $userMission = new UserMission([
                    'user_id' => $userId,
                    'mission_id' => $missionId,
                    'status' => 'in_progress',
                    'progress' => 0,
                    'progress_data' => json_encode([
                        'actions' => [],
                        'last_action_type' => null
                    ]),
                    'last_action_at' => now()
                ]);
            }

            // Cập nhật progress_data
            $progressData = json_decode($userMission->progress_data, true) ?: [
                'actions' => [],
                'last_action_type' => null
            ];

            // Thêm hành động mới vào danh sách
            $actionItem = [
                'type' => $actionType,
                'data' => $actionData,
                'timestamp' => now()->toIso8601String()
            ];
            $progressData['actions'][] = $actionItem;
            $progressData['last_action_type'] = $actionType;

            $userMission->progress_data = json_encode($progressData);
            $userMission->last_action_at = now();

            // Cập nhật tiến trình dựa trên loại hành động
            $completed = false;

            switch ($mission->action_required) {
                case 'read_articles':
                    // Đếm số bài viết đã đọc
                    $articles = collect($progressData['actions'])
                        ->where('type', 'read_article')
                        ->pluck('data.article_id')
                        ->unique()
                        ->count();

                    $articlesRequired = 3; // Mặc định yêu cầu đọc 3 bài
                    $progress = min(100, ($articles / $articlesRequired) * 100);
                    $userMission->progress = $progress;
                    $completed = $progress >= 100;
                    break;

                case 'watch_youtube':
                    // Đếm số video đã xem
                    $videos = collect($progressData['actions'])
                        ->where('type', 'watch_video')
                        ->pluck('data.video_id')
                        ->unique()
                        ->count();

                    $videosRequired = 1; // Mặc định yêu cầu xem 1 video
                    $progress = min(100, ($videos / $videosRequired) * 100);
                    $userMission->progress = $progress;
                    $completed = $progress >= 100;
                    break;

                case 'comment_posts':
                    // Đếm số bài viết đã bình luận
                    $comments = collect($progressData['actions'])
                        ->where('type', 'add_comment')
                        ->pluck('data.article_id')
                        ->unique()
                        ->count();

                    $commentsRequired = 3; // Mặc định yêu cầu bình luận 3 bài
                    $progress = min(100, ($comments / $commentsRequired) * 100);
                    $userMission->progress = $progress;
                    $completed = $progress >= 100;
                    break;

                case 'follow_zalo_page':
                case 'follow_tiktok':
                case 'complete_profile':
                case 'share_app':
                case 'share_facebook':
                case 'tiktok_review':
                case 'join_livestream':
                case 'submit_article':
                    // Các nhiệm vụ chỉ cần thực hiện một lần
                    if ($actionType === $mission->action_required ||
                        ($actionType === 'manually_confirmed' && isset($actionData['action_type']) && $actionData['action_type'] === $mission->action_required)) {
                        $userMission->progress = 100;
                        $completed = true;
                    }
                    break;

                case 'login_streak':
                    // Đếm số ngày đăng nhập liên tiếp
                    $loginDays = collect($progressData['actions'])
                        ->where('type', 'login')
                        ->pluck('timestamp')
                        ->map(function ($timestamp) {
                            return \Carbon\Carbon::parse($timestamp)->format('Y-m-d');
                        })
                        ->unique()
                        ->values()
                        ->toArray();

                    // Sắp xếp theo thứ tự ngày
                    sort($loginDays);

                    // Kiểm tra tính liên tục
                    $streakCount = 1;
                    $maxStreak = 1;

                    for ($i = 1; $i < count($loginDays); $i++) {
                        $prev = \Carbon\Carbon::parse($loginDays[$i-1]);
                        $curr = \Carbon\Carbon::parse($loginDays[$i]);

                        if ($curr->diffInDays($prev) === 1) {
                            $streakCount++;
                        } else {
                            $streakCount = 1;
                        }

                        $maxStreak = max($maxStreak, $streakCount);
                    }

                    $daysRequired = 7; // Yêu cầu 7 ngày liên tiếp
                    $progress = min(100, ($maxStreak / $daysRequired) * 100);
                    $userMission->progress = $progress;
                    $completed = $progress >= 100;
                    break;

                case 'quiz_completion':
                    // Đếm số câu hỏi trắc nghiệm đã hoàn thành với độ chính xác 80%
                    $quizAttempts = collect($progressData['actions'])
                        ->where('type', 'quiz_completion')
                        ->last();

                    if ($quizAttempts && isset($quizAttempts['data']['accuracy']) && $quizAttempts['data']['accuracy'] >= 80) {
                        $userMission->progress = 100;
                        $completed = true;
                    } else {
                        $userMission->progress = 0;
                    }
                    break;

                case 'refer_users':
                    // Đếm số người dùng đã giới thiệu
                    $referrals = collect($progressData['actions'])
                        ->where('type', 'referral')
                        ->pluck('data.referred_user_id')
                        ->unique()
                        ->count();

                    $referralsRequired = 5; // Yêu cầu giới thiệu 5 người
                    $progress = min(100, ($referrals / $referralsRequired) * 100);
                    $userMission->progress = $progress;
                    $completed = $progress >= 100;
                    break;

                case 'reach_points':
                    // Kiểm tra số điểm tích lũy
                    $user = User::find($userId);
                    $pointsRequired = 500; // Yêu cầu đạt 500 điểm
                    $progress = min(100, ($user->points / $pointsRequired) * 100);
                    $userMission->progress = $progress;
                    $completed = $progress >= 100;
                    break;

                default:
                    // Nếu không có xử lý đặc biệt, sử dụng giá trị incrementBy
                    if ($incrementBy > 0) {
                        $userMission->progress = min(100, $userMission->progress + $incrementBy);
                        $completed = $userMission->progress >= 100;
                    } elseif (isset($actionData['manually_completed']) && $actionData['manually_completed']) {
                        $userMission->progress = 100;
                        $completed = true;
                    }
                    break;
            }

            // Nếu nhiệm vụ đã hoàn thành
            if ($completed && $userMission->status !== 'completed') {
                $userMission->status = 'completed';
                $userMission->completed_at = now();

                // Cập nhật phần thưởng
                $user = User::find($userId);

                // Cộng điểm
                if ($mission->points_reward > 0) {
                    $user->points += $mission->points_reward;

                    // Lưu lịch sử giao dịch điểm
                    DB::table('point_transactions')->insert([
                        'user_id' => $userId,
                        'points' => $mission->points_reward,
                        'description' => 'Hoàn thành nhiệm vụ: ' . $mission->name,
                        'source_type' => 'mission',
                        'source_id' => $mission->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                // Cộng lượt quay
                if ($mission->spin_tickets > 0) {
                    $user->spin_tickets += $mission->spin_tickets;
                }

                $user->save();
            } elseif (!$completed && $userMission->status !== 'in_progress') {
                $userMission->status = 'in_progress';
            }

            $userMission->save();

            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật tiến trình nhiệm vụ',
                'data' => [
                    'mission_id' => $missionId,
                    'progress' => $userMission->progress,
                    'status' => $userMission->status,
                    'completed' => $completed
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Lỗi khi theo dõi tiến trình nhiệm vụ: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy tiến trình nhiệm vụ của người dùng
     *
     * @param int $userId
     * @param int $missionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMissionProgress($userId, $missionId)
    {
        try {
            // Kiểm tra người dùng tồn tại
            $user = User::find($userId);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng'
                ], 404);
            }

            // Kiểm tra nhiệm vụ tồn tại
            $mission = Mission::find($missionId);
            if (!$mission) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy nhiệm vụ'
                ], 404);
            }

            // Lấy thông tin UserMission
            $userMission = UserMission::where('user_id', $userId)
                ->where('mission_id', $missionId)
                ->first();

            if (!$userMission) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'mission_id' => $missionId,
                        'user_id' => $userId,
                        'status' => 'not_started',
                        'progress' => 0,
                        'progress_data' => null,
                        'completed_at' => null
                    ]
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'mission_id' => $missionId,
                    'user_id' => $userId,
                    'status' => $userMission->status,
                    'progress' => $userMission->progress,
                    'progress_data' => json_decode($userMission->progress_data),
                    'last_action_at' => $userMission->last_action_at,
                    'completed_at' => $userMission->completed_at
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy tiến trình nhiệm vụ: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Đánh dấu bài viết đã được đọc
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markArticleAsRead(Request $request)
    {
        try {
            // Validate dữ liệu đầu vào
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'article_id' => 'required|integer',
                'mission_id' => 'nullable|exists:missions,id',
                'read_time' => 'nullable|integer'
            ]);

            $userId = $validated['user_id'];
            $articleId = $validated['article_id'];
            $missionId = $validated['mission_id'] ?? null;
            $readTime = $validated['read_time'] ?? 0;

            // Lưu thông tin đọc bài viết
            DB::table('article_reads')->insert([
                'user_id' => $userId,
                'article_id' => $articleId,
                'read_time' => $readTime,
                'read_at' => now()
            ]);

            // Kiểm tra xem có liên quan đến nhiệm vụ không
            if ($missionId) {
                // Gọi phương thức trackMissionProgress để cập nhật tiến trình
                return $this->trackMissionProgress(new Request([
                    'user_id' => $userId,
                    'mission_id' => $missionId,
                    'action_type' => 'read_article',
                    'action_data' => [
                        'article_id' => $articleId,
                        'read_time' => $readTime
                    ]
                ]));
            }

            return response()->json([
                'success' => true,
                'message' => 'Đã ghi nhận đọc bài viết thành công'
            ]);

        } catch (\Exception $e) {
            Log::error('Lỗi khi đánh dấu bài viết đã đọc: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Đánh dấu đã thêm bình luận vào bài viết
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markCommentAdded(Request $request)
    {
        try {
            // Validate dữ liệu đầu vào
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'article_id' => 'required|integer',
                'comment_text' => 'required|string',
                'mission_id' => 'nullable|exists:missions,id'
            ]);

            $userId = $validated['user_id'];
            $articleId = $validated['article_id'];
            $commentText = $validated['comment_text'];
            $missionId = $validated['mission_id'] ?? null;

            // Lưu thông tin bình luận
            DB::table('article_comments')->insert([
                'user_id' => $userId,
                'article_id' => $articleId,
                'comment_text' => $commentText,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Kiểm tra xem có liên quan đến nhiệm vụ không
            if ($missionId) {
                // Gọi phương thức trackMissionProgress để cập nhật tiến trình
                return $this->trackMissionProgress(new Request([
                    'user_id' => $userId,
                    'mission_id' => $missionId,
                    'action_type' => 'add_comment',
                    'action_data' => [
                        'article_id' => $articleId,
                        'comment_length' => strlen($commentText)
                    ]
                ]));
            }

            return response()->json([
                'success' => true,
                'message' => 'Đã ghi nhận bình luận thành công'
            ]);

        } catch (\Exception $e) {
            Log::error('Lỗi khi đánh dấu đã thêm bình luận: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xác minh hoàn thành hồ sơ cá nhân
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyProfileCompletion(Request $request)
    {
        try {
            // Validate dữ liệu đầu vào
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'mission_id' => 'required|exists:missions,id'
            ]);

            $userId = $validated['user_id'];
            $missionId = $validated['mission_id'];

            // Kiểm tra thông tin hồ sơ người dùng
            $user = User::find($userId);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng'
                ], 404);
            }

            // Kiểm tra xem hồ sơ đã hoàn thành chưa (tùy vào tiêu chí của ứng dụng)
            $isProfileComplete = !empty($user->name) && !empty($user->avatar) && !empty($user->phone);

            if ($isProfileComplete) {
                // Gọi phương thức trackMissionProgress để cập nhật tiến trình
                return $this->trackMissionProgress(new Request([
                    'user_id' => $userId,
                    'mission_id' => $missionId,
                    'action_type' => 'complete_profile',
                    'action_data' => [
                        'profile_completion_date' => now()->toDateTimeString()
                    ]
                ]));
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Hồ sơ cá nhân chưa hoàn thành. Vui lòng cập nhật đầy đủ thông tin.',
                    'data' => [
                        'missing_fields' => [
                            'name' => empty($user->name),
                            'avatar' => empty($user->avatar),
                            'phone' => empty($user->phone)
                        ]
                    ]
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Lỗi khi xác minh hoàn thành hồ sơ: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xác minh theo dõi Zalo OA
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyZaloFollow(Request $request)
    {
        try {
            // Validate dữ liệu đầu vào
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'mission_id' => 'required|exists:missions,id',
                'followed' => 'required|boolean'
            ]);

            $userId = $validated['user_id'];
            $missionId = $validated['mission_id'];
            $followed = $validated['followed'];

            if ($followed) {
                // Gọi phương thức trackMissionProgress để cập nhật tiến trình
                return $this->trackMissionProgress(new Request([
                    'user_id' => $userId,
                    'mission_id' => $missionId,
                    'action_type' => 'follow_zalo_page',
                    'action_data' => [
                        'followed_at' => now()->toDateTimeString()
                    ]
                ]));
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn chưa theo dõi trang Zalo OA'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Lỗi khi xác minh theo dõi Zalo: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
