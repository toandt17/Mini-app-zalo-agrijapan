<?php

namespace App\Repositories\Product;

use App\Models\Product;

class ProductRepository implements ProductInterface
{
    protected Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function create(array $data)
    {
        return $this->product->create($data);
    }

    public function update(int $id, array $data)
    {
        $product = $this->product->find($id);
        if ($product) {
            $product->update($data);
            return $product;
        }
        return null;
    }

    public function delete(int $id)
    {
        $product = $this->product->find($id);
        if ($product) {
            return $product->delete();
        }
        return false;
    }

    public function getAll()
    {
        return $this->product->get();
    }

    public function getById(int $id)
    {
        return $this->product->find($id);
    }

    public function getByCategory(int $category_id)
    {
        return $this->product->where('category_id', $category_id)->get();
    }

    public function searchByKeyword($keyword)
    {
        if (empty($keyword)) {
            return collect(); // Trả về collection rỗng nếu không có keyword
        }

        // Sử dụng join để kết nối với bảng categories
        return $this->product
            ->select('products.*')
            ->where(function($query) use ($keyword) {
                $query->where('products.name', 'like', '%' . $keyword . '%')
                      ->orWhere('products.detail', 'like', '%' . $keyword . '%');
            })
            ->get();
    }
}
