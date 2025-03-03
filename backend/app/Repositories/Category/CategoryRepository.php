<?php

namespace App\Repositories\Category;

use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryRepository implements CategoryInterface
{
    protected Category $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function create(array $data)
    {
        return $this->category->create($data);
    }

    public function update(int $id, array $data)
    {
        $category = $this->category->find($id);
        if ($category) {
            $category->update($data);
            return $category;
        }
        return null;
    }

    public function delete(int $id)
    {
        $category = $this->category->find($id);
        if ($category) {
            return $category->delete();
        }
        return false;
    }

    public function getAll()
    {
        return $this->category->all();
    }

    public function getById(int $id)
    {
        return $this->category->find($id);
    }

    public function validate(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    }

    public function getTopCategories($limit = 8)
    {
        return $this->category
            ->withCount('products')  // Đếm số lượng sản phẩm
            ->orderBy('products_count', 'desc')  // Sắp xếp theo số lượng giảm dần
            ->take($limit)  // Lấy $limit bản ghi (mặc định là 8)
            ->get();
    }
}
