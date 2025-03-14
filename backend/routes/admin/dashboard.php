    <?php

use App\Http\Controllers\Admin\Dashboards\DashboardsController;
use Illuminate\Support\Facades\Route;

Route::prefix('/admin')->name('dashboards.')->group(function () {
    Route::get('/', [DashboardsController::class, 'index'])->name('index');
});
