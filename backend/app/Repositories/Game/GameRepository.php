<?php

namespace App\Repositories\Game;

use App\Models\SpinWheel;
use App\Models\Mission;
use App\Models\User;
use App\Models\UserMission;
use App\Models\UserSpin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GameRepository implements GameInterface
{
    protected ?SpinWheel $game;
    protected ?Mission $mission;

    public function __construct(SpinWheel $game, Mission $mission)
    {
        $this->game = $game;
        $this->mission = $mission;
    }

    public function getAll()
    {
        return $this->game->all();
    }

    public function getById($id)
    {
        return $this->game->find($id);
    }

    public function update($id, array $data)
    {
        $game = $this->getById($id);
        if ($game) {
            $game->update($data);
            return $game;
        }
        return false;
    }

    public function validate(array $data)
    {
        $rules = [
            'prize_name' => 'required|string|max:255',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'probability' => 'nullable|numeric|min:0|max:100',
        ];

        return Validator::make($data, $rules);
    }

    /**
     * Lấy tất cả nhiệm vụ
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllMissions()
    {
        return $this->mission->all();
    }

    /**
     * Lấy một nhiệm vụ theo ID
     *
     * @param int $id
     * @return \App\Models\Mission|null
     */
    public function getMissionById($id)
    {
        return $this->mission->find($id);
    }

    /**
     * Lấy danh sách nhiệm vụ của một người dùng
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserMissions($userId)
    {
        try {
            // Kiểm tra user tồn tại không
            $user = User::find($userId);
            if (!$user) {
                return false;
            }

            // Lấy tất cả nhiệm vụ
            $allMissions = $this->mission->all();

            // Lấy các nhiệm vụ đã hoàn thành của user
            $userMissions = UserMission::where('user_id', $userId)->get()->keyBy('mission_id');

            // Map các thông tin từ UserMission vào Mission
            return $allMissions->map(function($mission) use ($userMissions) {
                // Kiểm tra nhiệm vụ có trong danh sách đã hoàn thành không
                if (isset($userMissions[$mission->id])) {
                    $userMission = $userMissions[$mission->id];
                    $mission->status = $userMission->status ?? 'available';
                    $mission->completed_at = $userMission->completed_at;
                } else {
                    $mission->status = 'available';
                    $mission->completed_at = null;
                }

                return $mission;
            });
        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy danh sách nhiệm vụ: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Lấy chi tiết một nhiệm vụ cho một người dùng cụ thể
     *
     * @param int $missionId
     * @param int $userId
     * @return \App\Models\Mission|null
     */
    public function getMissionDetail($missionId, $userId)
    {
        try {
            // Kiểm tra user tồn tại không
            $user = User::find($userId);
            if (!$user) {
                return false;
            }

            // Lấy thông tin nhiệm vụ
            $mission = $this->getMissionById($missionId);
            if (!$mission) {
                return false;
            }

            // Lấy thông tin UserMission nếu có
            $userMission = UserMission::where('user_id', $userId)
                ->where('mission_id', $missionId)
                ->first();

            if ($userMission) {
                $mission->status = $userMission->status;
                $mission->completed_at = $userMission->completed_at;
                $mission->progress = $userMission->progress ?? 0;
            } else {
                $mission->status = 'available';
                $mission->completed_at = null;
                $mission->progress = 0;
            }

            return $mission;
        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy chi tiết nhiệm vụ: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Hoàn thành một nhiệm vụ
     *
     * @param int $userId
     * @param int $missionId
     * @param mixed $actionData
     * @return array|false
     */
    public function completeMission($userId, $missionId, $actionData = null)
    {
        try {
            // Kiểm tra user tồn tại không
            $user = User::find($userId);
            if (!$user) {
                return false;
            }

            // Kiểm tra nhiệm vụ tồn tại không
            $mission = $this->getMissionById($missionId);
            if (!$mission) {
                return false;
            }

            // Kiểm tra nhiệm vụ đã hoàn thành chưa
            $userMission = UserMission::where('user_id', $userId)
                ->where('mission_id', $missionId)
                ->first();

            if ($userMission && $userMission->status === 'completed') {
                return false;
            }

            // Bắt đầu transaction để đảm bảo tính nhất quán dữ liệu
            DB::beginTransaction();

            try {
                // Cập nhật hoặc tạo mới bản ghi user_missions
                if ($userMission) {
                    $userMission->status = 'completed';
                    $userMission->completed_at = now();
                    $userMission->save();
                } else {
                    UserMission::create([
                        'user_id' => $userId,
                        'mission_id' => $missionId,
                        'status' => 'completed',
                        'completed_at' => now(),
                    ]);
                }

                // Tạo phần thưởng điểm nếu có
                if ($mission->points_reward > 0) {
                    DB::table('point_transactions')->insert([
                        'user_id' => $userId,
                        'points' => $mission->points_reward,
                        'description' => 'Hoàn thành nhiệm vụ: ' . $mission->name,
                        'source_type' => 'mission',
                        'source_id' => $missionId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                // Tạo phần thưởng lượt quay nếu có
                if ($mission->spin_tickets > 0) {
                    $userSpin = UserSpin::where('user_id', $userId)->first();

                    if ($userSpin) {
                        $userSpin->spin_count += $mission->spin_tickets;
                        $userSpin->save();
                    } else {
                        UserSpin::create([
                            'user_id' => $userId,
                            'spin_count' => $mission->spin_tickets,
                            'spin_time' => now()
                        ]);
                    }
                }

                // Cập nhật tổng điểm của user
                $totalPoints = DB::table('point_transactions')
                    ->where('user_id', $userId)
                    ->sum('points');

                $user->total_points = $totalPoints;
                $user->save();

                DB::commit();

                return [
                    'reward' => [
                        'points' => $mission->points_reward,
                        'spin_tickets' => $mission->spin_tickets,
                        'name' => $mission->points_reward . ' điểm và ' . $mission->spin_tickets . ' lượt quay'
                    ],
                    'user' => [
                        'id' => $user->id,
                        'total_points' => $user->total_points
                    ]
                ];

            } catch (\Exception $innerException) {
                DB::rollBack();
                throw $innerException;
            }
        } catch (\Exception $e) {
            Log::error('Lỗi khi hoàn thành nhiệm vụ: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy lịch sử hoàn thành nhiệm vụ của người dùng
     *
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function getMissionHistory($userId)
    {
        try {
            // Kiểm tra user tồn tại không
            $user = User::find($userId);
            if (!$user) {
                return collect([]);
            }

            // Lấy lịch sử hoàn thành nhiệm vụ
            return UserMission::with('mission')
                ->where('user_id', $userId)
                ->where('status', 'completed')
                ->orderBy('completed_at', 'desc')
                ->get()
                ->map(function($userMission) {
                    return [
                        'id' => $userMission->id,
                        'name' => $userMission->mission->name,
                        'description' => $userMission->mission->description,
                        'points_reward' => $userMission->mission->points_reward,
                        'spin_tickets' => $userMission->mission->spin_tickets,
                        'status' => $userMission->status,
                        'completed_at' => $userMission->completed_at,
                        'created_at' => $userMission->created_at
                    ];
                });
        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy lịch sử nhiệm vụ: ' . $e->getMessage());
            return collect([]);
        }
    }
}

