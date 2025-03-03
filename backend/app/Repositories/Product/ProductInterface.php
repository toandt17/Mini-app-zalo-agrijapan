<?php

namespace App\Repositories\Product;

interface ProductInterface
{
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getAll();
    public function getById(int $id);
    public function getByCategory(int $category_id);
    public function searchByKeyword($keyword);
}
