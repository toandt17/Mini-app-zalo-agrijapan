<?php

namespace App\Repositories\Reward;

interface RewardInterface
{
    /**
     * Lấy tất cả quà tặng
     *
     * @return mixed
     */
    public function getAll();

    /**
     * Lấy quà tặng theo ID
     *
     * @param int $id
     * @return mixed
     */
    public function findById($id);

    /**
     * Tạo quà tặng mới
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Cập nhật thông tin quà tặng
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data);

    /**
     * Xóa quà tặng
     *
     * @param int $id
     * @return mixed
     */
    public function delete($id);

    /**
     * Tìm kiếm quà tặng theo tên
     *
     * @param string $keyword
     * @return mixed
     */
    public function search($keyword);

    /**
     * Kiểm tra số lượng quà tặng có sẵn
     *
     * @param int $id
     * @return mixed
     */
    public function checkAvailability($id);

    /**
     * Giảm số lượng quà tặng sau khi được phát
     *
     * @param int $id
     * @param int $quantity
     * @return mixed
     */
    public function decreaseQuantity($id, $quantity = 1);
}
