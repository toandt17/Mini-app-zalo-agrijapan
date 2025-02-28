<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(){
        return view('admin.orders.index');
    }
    public function edit(){
        return view('admin.orders.edit');
    }
}
