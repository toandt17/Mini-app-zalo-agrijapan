<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('admin.products.index');
    }

    public function add()
    {
        return view('admin.products.add');
    }

    public function edit()
    {
        return view('admin.products.edit');
    }

    public function getProducts()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function exportToJson()
    {
        $products = Product::all();
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
}
