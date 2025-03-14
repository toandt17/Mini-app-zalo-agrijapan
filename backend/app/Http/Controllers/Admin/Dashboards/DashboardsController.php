<?php

namespace App\Http\Controllers\Admin\Dashboards;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardsController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $usersByMonth = DB::table('users')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y')) // Lọc theo năm hiện tại
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = collect(range(1, 12))->map(function ($month) use ($usersByMonth) {
            return [
                'month' => $month,
                'count' => $usersByMonth->firstWhere('month', $month)->count ?? 0
            ];
        });
        $usersByMonth = collect($months);
        // dd($usersByMonth);
        return view('admin.dashboards.index', compact('totalUsers','totalProducts','totalCategories','usersByMonth','months'));
    }
}
