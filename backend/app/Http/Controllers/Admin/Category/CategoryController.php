<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        return view('admin.categorys.index');
    }
    public function add(){
        return view('admin.categorys.add');
    }
    public function edit(){
        return view('admin.categorys.edit');
    }

}
