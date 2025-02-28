<?php

namespace App\Http\Controllers\Admin\Chart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function index()
    {
        return view('admin.charts.index');
    }

    public function add()
    {
        return view('admin.charts.add');
    }

    public function edit()
    {
        return view('admin.charts.edit');
    }
}
