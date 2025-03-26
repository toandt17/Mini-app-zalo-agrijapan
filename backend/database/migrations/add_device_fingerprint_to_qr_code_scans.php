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
        Schema::table('qr_code_scans', function (Blueprint $table) {
            if (!Schema::hasColumn('qr_code_scans', 'device_fingerprint')) {
                $table->string('device_fingerprint')->nullable()->after('browser');
                $table->index('device_fingerprint');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qr_code_scans', function (Blueprint $table) {
            $table->dropColumn('device_fingerprint');
        });
    }
};
