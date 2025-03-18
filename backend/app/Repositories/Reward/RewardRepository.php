<?php

namespace App\Repositories\Reward;

use App\Models\Reward;
use Illuminate\Support\Facades\Storage;

class RewardRepository implements RewardInterface
{
    protected $model;

    /**
     * Khởi tạo repository với model Reward
     */
    public function __construct(Reward $reward)
    {
        $this->model = $reward;
    }

    /**
     * Lấy tất cả quà tặng
     *
     * @return mixed
     */
    public function getAll()
    {
        return $this->model->orderBy('created_at', 'desc')->get();
    }

    /**
     * Lấy quà tặng theo ID
     *
     * @param int $id
     * @return mixed
     */
    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Tạo quà tặng mới
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        // Xử lý upload hình ảnh nếu có
        if (isset($data['image']) && $data['image']) {
            $data['image'] = $this->uploadImage($data['image']);
        }

        return $this->model->create($data);
    }

    /**
     * Cập nhật thông tin quà tặng
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data)
    {
        $reward = $this->findById($id);

        // Xử lý upload hình ảnh mới nếu có
        if (isset($data['image']) && $data['image']) {
            // Xóa hình ảnh cũ nếu có
            if ($reward->image) {
                $this->deleteImage($reward->image);
            }

            $data['image'] = $this->uploadImage($data['image']);
        }

        $reward->update($data);
        return $reward;
    }

    /**
     * Xóa quà tặng
     *
     * @param int $id
     * @return mixed
     */
    public function delete($id)
    {
        $reward = $this->findById($id);

        // Xóa hình ảnh nếu có
        if ($reward->image) {
            $this->deleteImage($reward->image);
        }

        return $reward->delete();
    }

    /**
     * Tìm kiếm quà tặng theo tên
     *
     * @param string $keyword
     * @return mixed
     */
    public function search($keyword)
    {
        return $this->model->where('name', 'LIKE', "%{$keyword}%")
                           ->orWhere('description', 'LIKE', "%{$keyword}%")
                           ->get();
    }

    /**
     * Kiểm tra số lượng quà tặng có sẵn
     *
     * @param int $id
     * @return mixed
     */
    public function checkAvailability($id)
    {
        $reward = $this->findById($id);
        return $reward->quantity > 0;
    }

    /**
     * Giảm số lượng quà tặng sau khi được phát
     *
     * @param int $id
     * @param int $quantity
     * @return mixed
     */
    public function decreaseQuantity($id, $quantity = 1)
    {
        $reward = $this->findById($id);

        if ($reward->quantity >= $quantity) {
            $reward->quantity -= $quantity;
            $reward->save();
            return $reward;
        }

        return false;
    }

    /**
     * Upload hình ảnh lên storage
     *
     * @param $image
     * @return string
     */
    protected function uploadImage($image)
    {
        $path = $image->store('rewards', 'public');
        return $path;
    }

    /**
     * Xóa hình ảnh khỏi storage
     *
     * @param string $path
     * @return bool
     */
    protected function deleteImage($path)
    {
        return Storage::disk('public')->delete($path);
    }
}
