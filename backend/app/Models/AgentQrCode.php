<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentQrCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'qr_code_path',
        'qr_token',
        'generated_at',
        'url',
        'metadata',
        'is_active',
    ];

    /**
     * Các trường được tự động chuyển thành đối tượng Carbon
     */
    protected $dates = [
        'generated_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Các thuộc tính cần được cast
     */
    protected $casts = [
        'is_active' => 'boolean',
        'metadata' => 'array',
        'generated_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Lấy đại lý sở hữu mã QR này
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    /**
     * Lấy mã QR theo token
     */
    public static function findByToken($token)
    {
        return self::where('qr_token', $token)->first();
    }

    /**
     * Lấy mã QR mới nhất của một đại lý
     */
    public static function getLatestForAgent($agentId)
    {
        return self::where('agent_id', $agentId)
            ->orderBy('generated_at', 'desc')
            ->first();
    }

    /**
     * Lấy tất cả mã QR của một đại lý
     */
    public static function getAllForAgent($agentId)
    {
        return self::where('agent_id', $agentId)
            ->orderBy('generated_at', 'desc')
            ->get();
    }

    /**
     * Lấy tất cả lượt quét của mã QR này
     */
    public function scans()
    {
        return $this->hasMany(QrCodeScan::class, 'qr_code_id');
    }

    /**
     * Cập nhật số lượt quét
     */
    public function incrementScanCount($ipAddress, $userAgent)
    {
        // Tạo device fingerprint
        $deviceFingerprint = QrCodeScan::generateDeviceFingerprint($userAgent, $ipAddress);

        // Cập nhật thời gian quét gần nhất
        $this->last_scanned_at = now();

        // Tăng số lượt quét
        $this->scan_count = ($this->scan_count ?? 0) + 1;

        // Kiểm tra xem thiết bị này đã quét trước đó chưa - sử dụng fingerprint thay vì chỉ IP
        $existingScan = $this->scans()
            ->where('device_fingerprint', $deviceFingerprint)
            ->exists();

        // Nếu là thiết bị mới, tăng số lượng người quét khác nhau
        if (!$existingScan) {
            $this->unique_scan_count = ($this->unique_scan_count ?? 0) + 1;
        }

        $this->save();

        // Tạo bản ghi lần quét mới
        $deviceType = QrCodeScan::detectDeviceType($userAgent);
        $browser = QrCodeScan::detectBrowser($userAgent);

        return $this->scans()->create([
            'agent_id' => $this->agent_id,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'device_type' => $deviceType,
            'browser' => $browser,
            'device_fingerprint' => $deviceFingerprint
        ]);
    }
}
