<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QrCodeScan extends Model
{
    use HasFactory;

    protected $fillable = [
        'qr_code_id',
        'agent_id',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'device_fingerprint',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    /**
     * Lấy mã QR liên quan
     */
    public function qrCode(): BelongsTo
    {
        return $this->belongsTo(AgentQrCode::class, 'qr_code_id');
    }

    /**
     * Lấy đại lý liên quan
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    /**
     * Phát hiện loại thiết bị từ user agent
     */
    public static function detectDeviceType($userAgent)
    {
        if (preg_match('/(android|iphone|ipad|ipod|blackberry|windows phone)/i', $userAgent)) {
            return 'mobile';
        } elseif (preg_match('/(tablet|ipad)/i', $userAgent)) {
            return 'tablet';
        }
        return 'desktop';
    }

    /**
     * Phát hiện trình duyệt từ user agent
     */
    public static function detectBrowser($userAgent)
    {
        // Phát hiện theo thứ tự từ cụ thể đến chung
        if (preg_match('/Edg(e|)/i', $userAgent)) {
            return 'Microsoft Edge';
        } elseif (preg_match('/OPR|Opera/i', $userAgent)) {
            return 'Opera';
        } elseif (preg_match('/Firefox/i', $userAgent)) {
            return 'Firefox';
        } elseif (preg_match('/MSIE|Trident/i', $userAgent)) {
            return 'Internet Explorer';
        } elseif (preg_match('/Chrome/i', $userAgent)) {
            return 'Chrome';
        } elseif (preg_match('/Safari/i', $userAgent)) {
            return 'Safari';
        } elseif (preg_match('/Netscape/i', $userAgent)) {
            return 'Netscape';
        } else {
            return 'Không xác định';
        }
    }

    /**
     * Tạo dấu vân tay thiết bị từ thông tin User-Agent
     *
     * @param string $userAgent
     * @param string $ipAddress
     * @return string
     */
    public static function generateDeviceFingerprint($userAgent, $ipAddress)
    {
        // Trích xuất thông tin thiết bị từ User-Agent
        $deviceInfo = [];

        // Lấy hệ điều hành
        if (preg_match('/(Windows|Mac OS X|Linux|Android|iOS|iPhone|iPad)/i', $userAgent, $matches)) {
            $deviceInfo['os'] = $matches[1];
        } else {
            $deviceInfo['os'] = 'Unknown';
        }

        // Lấy phiên bản trình duyệt
        if (preg_match('/Chrome\/([0-9\.]+)/i', $userAgent, $matches)) {
            $deviceInfo['browser_version'] = 'Chrome/' . $matches[1];
        } elseif (preg_match('/Firefox\/([0-9\.]+)/i', $userAgent, $matches)) {
            $deviceInfo['browser_version'] = 'Firefox/' . $matches[1];
        } elseif (preg_match('/Safari\/([0-9\.]+)/i', $userAgent, $matches)) {
            $deviceInfo['browser_version'] = 'Safari/' . $matches[1];
        } elseif (preg_match('/Edg(e|)\/([0-9\.]+)/i', $userAgent, $matches)) {
            $deviceInfo['browser_version'] = 'Edge/' . $matches[2];
        }

        // Lấy thông tin thiết bị di động
        if (preg_match('/(iPhone|iPad|Android)[\s\/]([0-9\.]+)/i', $userAgent, $matches)) {
            $deviceInfo['mobile_device'] = $matches[1] . '/' . $matches[2];
        }

        // Lấy thông tin độ phân giải màn hình nếu có
        if (preg_match('/([0-9]+)x([0-9]+)/i', $userAgent, $matches)) {
            $deviceInfo['screen'] = $matches[1] . 'x' . $matches[2];
        }

        // Kết hợp thông tin và tạo dấu vân tay (fingerprint)
        $fingerprintData = json_encode($deviceInfo) . $userAgent;

        // Tạo mã băm từ dữ liệu
        return md5($fingerprintData);
    }
}

   