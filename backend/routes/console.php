<?php

use App\Models\Agent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Thêm lệnh tạo QR cho đại lý
Artisan::command('agents:generate-qr {agentId?}', function ($agentId = null) {
    // URL frontend
    $frontendUrl = 'https://agrijapanvn.com.vn';

    // Tạo thư mục lưu mã QR nếu chưa tồn tại
    if (!Storage::exists('public/qrcodes')) {
        Storage::makeDirectory('public/qrcodes');
        $this->info("Đã tạo thư mục public/qrcodes");
    }

    // Xác định đại lý cần tạo QR
    if ($agentId) {
        $agents = Agent::where('id', $agentId)->get();
        $this->info("Tạo QR cho đại lý ID: {$agentId}");
    } else {
        $agents = Agent::all();
        $this->info("Tạo QR cho tất cả " . $agents->count() . " đại lý");
    }

    // Tạo QR cho từng đại lý
    foreach ($agents as $agent) {
        // Tạo URL
        $agentUrl = "{$frontendUrl}/agents/{$agent->id}";

        // Tên file - đổi từ png sang svg
        $fileName = "agent_{$agent->id}.svg";
        $qrPath = "public/qrcodes/{$fileName}";

        try {
            // Tạo QR - đổi format('png') thành format('svg')
            QrCode::format('svg')
                ->size(300)
                ->errorCorrection('H')
                ->generate($agentUrl, storage_path("app/{$qrPath}"));

            $this->info("Đã tạo QR cho {$agent->name} tại " . storage_path("app/{$qrPath}"));

            // Kiểm tra file
            if (file_exists(storage_path("app/{$qrPath}"))) {
                // Cập nhật đường dẫn trong DB
                $agent->qr_code = '/storage/qrcodes/' . $fileName;
                $agent->save();

                $this->info("Đã cập nhật QR path cho đại lý #{$agent->id}: {$agent->name}");
            } else {
                $this->error("File QR không tồn tại sau khi tạo: " . storage_path("app/{$qrPath}"));
            }
        } catch (\Exception $e) {
            $this->error("Lỗi khi tạo QR cho đại lý #{$agent->id}: " . $e->getMessage());
            Log::error("Lỗi QR cho đại lý #{$agent->id}: " . $e->getMessage());
        }
    }

    $this->info("Hoàn tất tạo QR!");
})->purpose('Tạo mã QR cho đại lý');
