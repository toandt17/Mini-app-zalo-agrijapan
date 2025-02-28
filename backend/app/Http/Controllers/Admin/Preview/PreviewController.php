<?php

namespace App\Http\Controllers\Admin\Preview;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PreviewController extends Controller
{
    public function index(){
        return view('admin.previews.index');
    }

    public function add(){
        return view('admin.previews.add');
    }

    public function edit(){
        return view('admin.previews.edit');
    }
}
