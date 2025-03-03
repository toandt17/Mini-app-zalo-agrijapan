<?php

namespace App\Repositories\Category;

interface CategoryInterface
{
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getAll();
    public function getById(int $id);
    public function validate(array $data);
    public function getTopCategories($limit = 8);
}
