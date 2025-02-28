<?php

namespace App\Http\Controllers\Admin\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(){
        return view('admin.payments.index');
    }

    public function add(){
        return view('admin.payments.add');
    }

    public function edit(){
        return view('admin.payments.edit');
    }
}
