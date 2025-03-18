<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Repositories\Product\ProductRepository;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        $products = $this->productRepository->getAll();
        return view('admin.products.index', compact('products'));
    }

    public function add()
    {
        $categories = Category::all();
        return view('admin.products.add', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'detail' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.products.add')
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('products', 'public');
            $data['image'] = $imagePath;
        }

        $this->productRepository->create($data);

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được thêm thành công.');
    }

    public function edit($id)
    {
        $product = $this->productRepository->getById($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'detail' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.products.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('products', 'public');
            $data['image'] = $imagePath;
        }

        $this->productRepository->update($id, $data);

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được cập nhật thành công.');
    }

    public function delete($id)
    {
        $this->productRepository->delete($id);
        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được xóa thành công.');
    }

    public function getProducts()
    {
        $products = $this->productRepository->getAll();
        return response()->json($products);
    }

    public function getProductsByCategory($category_id)
    {
        $products = $this->productRepository->getByCategory($category_id);
        return response()->json($products);
    }

    public function exportToJson()
    {
        $products = $this->productRepository->getAll();
        $jsonData = json_encode($products, JSON_PRETTY_PRINT);

        // Adjust the path to point to the correct location
        $filePath = base_path('../frontend/src/mock/products.json');

        // Ensure the directory exists
        $directory = dirname($filePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        file_put_contents($filePath, $jsonData);

        return response()->json(['message' => 'Products exported successfully to products.json']);
    }

    public function searchProducts(Request $request)
    {
        $keyword = $request->input('keyword', '');

        // Log để debug
        Log::info('Search keyword: ' . $keyword);

        $products = $this->productRepository->searchByKeyword($keyword);

        // Log kết quả
        Log::info('Search results count: ' . count($products));

        return response()->json($products);
    }
}
