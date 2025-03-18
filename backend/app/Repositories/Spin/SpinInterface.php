<?php

namespace App\Repositories\Spin;

interface SpinInterface
{
    /**
     * Lấy tất cả các giải thưởng vòng quay
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllPrizes();

    /**
     * Lấy thông tin 1 giải thưởng theo ID
     *
     * @param int $id
     * @return \App\Models\SpinWheel|null
     */
    public function getPrizeById($id);

    /**
     * Tạo mới giải thưởng
     *
     * @param array $data
     * @return \App\Models\SpinWheel
     */
    public function createPrize(array $data);

    /**
     * Cập nhật thông tin giải thưởng
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updatePrize($id, array $data);

    /**
     * Xóa giải thưởng
     *
     * @param int $id
     * @return bool
     */
    public function deletePrize($id);

    /**
     * Lấy thống kê vòng quay
     *
     * @return array
     */
    public function getSpinStatistics();
}
