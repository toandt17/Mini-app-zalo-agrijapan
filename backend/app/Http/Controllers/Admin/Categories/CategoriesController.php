<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\categories;

class CategoriesController extends Controller
{
    public function index(){
        return view('admin.categories.index');
    }

    public function add(){
        return view('admin.categories.add');
    }
    public function edit(){
        return view('admin.categories.edit');
    }

}
