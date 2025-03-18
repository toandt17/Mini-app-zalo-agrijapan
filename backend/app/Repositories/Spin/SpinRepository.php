<?php

namespace App\Repositories\Spin;

use App\Models\SpinWheel;
use App\Models\UserSpin;
use Illuminate\Support\Facades\DB;

class SpinRepository implements SpinInterface
{
    /**
     * @var SpinWheel
     */
    protected $model;

    /**
     * SpinRepository constructor.
     *
     * @param SpinWheel $model
     */
    public function __construct(SpinWheel $model)
    {
        $this->model = $model;
    }

    /**
     * Lấy tất cả các giải thưởng vòng quay
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllPrizes()
    {
        return $this->model->orderBy('id', 'desc')->get();
    }

    /**
     * Lấy thông tin 1 giải thưởng theo ID
     *
     * @param int $id
     * @return \App\Models\SpinWheel|null
     */
    public function getPrizeById($id)
    {
        return $this->model->find($id);
    }

    /**
     * Tạo mới giải thưởng
     *
     * @param array $data
     * @return \App\Models\SpinWheel
     */
    public function createPrize(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Cập nhật thông tin giải thưởng
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updatePrize($id, array $data)
    {
        $prize = $this->getPrizeById($id);
        if ($prize) {
            return $prize->update($data);
        }
        return false;
    }

    /**
     * Xóa giải thưởng
     *
     * @param int $id
     * @return bool
     */
    public function deletePrize($id)
    {
        $prize = $this->getPrizeById($id);
        if ($prize) {
            return $prize->delete();
        }
        return false;
    }

    /**
     * Lấy thống kê vòng quay
     *
     * @return array
     */
    public function getSpinStatistics()
    {
        $totalSpins = UserSpin::count();
        $totalWinners = UserSpin::whereNotNull('prize_id')->count();

        $prizeStatistics = DB::table('user_spins')
            ->select('spin_wheel.prize_name', DB::raw('count(user_spins.id) as win_count'))
            ->join('spin_wheel', 'user_spins.prize_id', '=', 'spin_wheel.id')
            ->groupBy('spin_wheel.prize_name')
            ->orderBy('win_count', 'desc')
            ->get();

        return [
            'total_spins' => $totalSpins,
            'total_winners' => $totalWinners,
            'prize_statistics' => $prizeStatistics
        ];
    }
}
