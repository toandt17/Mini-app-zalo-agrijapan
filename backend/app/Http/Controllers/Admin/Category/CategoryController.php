<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Category\CategoryInterface;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    protected $categoryRepository;

    public function __construct(CategoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $categories = $this->categoryRepository->getAll();
        return view('admin.categories.index', compact('categories'));
    }

    public function add()
    {
        return view('admin.categories.add');
    }

    public function store(Request $request)
    {
        $validator = $this->categoryRepository->validate($request->all());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        // Xử lý tải lên ảnh
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('categories', 'public');
            $data['image'] = $imagePath;
        }

        $this->categoryRepository->create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Loại sản phẩm đã được thêm thành công.');
    }

    public function edit($id)
    {
        $category = $this->categoryRepository->getById($id);
        if (!$category) {
            return redirect()->route('admin.categories.index')->with('error', 'Loại sản phẩm không tồn tại.');
        }
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $validator = $this->categoryRepository->validate($request->all());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $category = $this->categoryRepository->getById($id);

        if ($category) {
            // Xử lý tải lên ảnh mới nếu có
            if ($request->hasFile('image')) {
                // Xóa ảnh cũ nếu có
                if ($category->image) {
                    Storage::disk('public')->delete($category->image);
                }
                $image = $request->file('image');
                $imagePath = $image->store('categories', 'public');
                $data['image'] = $imagePath;
            }

            $this->categoryRepository->update($id, $data);
            return redirect()->route('admin.categories.index')->with('success', 'Loại sản phẩm đã được cập nhật thành công.');
        }

        return redirect()->route('admin.categories.index')->with('error', 'Loại sản phẩm không tồn tại.');
    }

    public function delete($id)
    {
        $category = $this->categoryRepository->getById($id);

        if ($category) {
            // Xóa ảnh nếu có
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $this->categoryRepository->delete($id);
            return redirect()->route('admin.categories.index')->with('success', 'Loại sản phẩm đã được xóa thành công.');
        }

        return redirect()->route('admin.categories.index')->with('error', 'Loại sản phẩm không tồn tại.');
    }
}
