<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('qr_code_scans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qr_code_id')->constrained('agent_qr_codes')->onDelete('cascade');
            $table->foreignId('agent_id')->constrained('agents')->onDelete('cascade');
            $table->string('ip_address', 45)->nullable()->comment('Địa chỉ IP của người quét');
            $table->string('user_agent')->nullable()->comment('User-Agent của trình duyệt');
            $table->string('device_type')->nullable()->comment('Loại thiết bị: mobile, desktop, tablet');
            $table->string('browser')->nullable()->comment('Loại trình duyệt');
            $table->text('metadata')->nullable()->comment('Thông tin bổ sung dưới dạng JSON');
            $table->timestamps();

            // Index để tìm kiếm nhanh
            $table->index(['qr_code_id', 'created_at']);
            $table->index(['agent_id', 'created_at']);
            $table->index('ip_address');
        });

        // Kiểm tra xem các cột đã tồn tại chưa trước khi thêm
        if (!Schema::hasColumn('agent_qr_codes', 'scan_count')) {
            Schema::table('agent_qr_codes', function (Blueprint $table) {
                $table->integer('scan_count')->default(0)->comment('Tổng số lần mã QR được quét');
            });
        }

        if (!Schema::hasColumn('agent_qr_codes', 'unique_scan_count')) {
            Schema::table('agent_qr_codes', function (Blueprint $table) {
                $table->integer('unique_scan_count')->default(0)->comment('Số lượng người quét khác nhau');
            });
        }

        if (!Schema::hasColumn('agent_qr_codes', 'last_scanned_at')) {
            Schema::table('agent_qr_codes', function (Blueprint $table) {
                $table->timestamp('last_scanned_at')->nullable()->comment('Thời gian quét gần nhất');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Không xóa các cột trong agent_qr_codes vì chúng có thể đã tồn tại trước đó

        Schema::dropIfExists('qr_code_scans');
    }
};