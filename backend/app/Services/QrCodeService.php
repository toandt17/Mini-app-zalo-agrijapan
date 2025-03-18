<?php

namespace App\Services;

use App\Models\Agent;
use App\Models\AgentQrCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class QrCodeService
{
    /**
     * Tìm thông tin mã QR dựa vào token
     */
    public function findByToken($token)
    {
        try {
            // Tìm mã QR trong bảng agent_qr_codes
            $qrCode = AgentQrCode::findByToken($token);

            if (!$qrCode) {
                // Nếu không tìm thấy, có thể đây là mã QR cũ chưa được lưu trong bảng agent_qr_codes
                // Thử giải mã token để lấy timestamp
                try {
                    $timestamp = base64_decode($token);
                    $createdAt = Carbon::createFromTimestamp($timestamp);

                    // Log để debug
                    Log::info('Decoded timestamp from token: ' . $createdAt->format('Y-m-d H:i:s'));

                    return [
                        'success' => true,
                        'source' => 'legacy',
                        'timestamp' => $timestamp,
                        'created_at' => $createdAt->format('Y-m-d H:i:s'),
                        'created_at_timestamp' => $createdAt->timestamp,
                    ];
                } catch (\Exception $e) {
                    Log::error('Error decoding QR token: ' . $e->getMessage());
                    return [
                        'success' => false,
                        'message' => 'Không thể giải mã token QR'
                    ];
                }
            }

            return [
                'success' => true,
                'source' => 'database',
                'qr_code' => $qrCode,
                'agent_id' => $qrCode->agent_id,
                'created_at' => $qrCode->generated_at->format('Y-m-d H:i:s'),
                'created_at_timestamp' => $qrCode->generated_at->timestamp,
                'is_active' => $qrCode->is_active,
            ];
        } catch (\Exception $e) {
            Log::error('Error in QrCodeService@findByToken: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi tìm thông tin mã QR: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lấy lịch sử mã QR của một đại lý
     */
    public function getQrCodeHistory($agentId)
    {
        try {
            $qrCodes = AgentQrCode::where('agent_id', $agentId)
                ->orderBy('generated_at', 'desc')
                ->get();

            return [
                'success' => true,
                'count' => $qrCodes->count(),
                'qr_codes' => $qrCodes
            ];
        } catch (\Exception $e) {
            Log::error('Error in QrCodeService@getQrCodeHistory: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi lấy lịch sử mã QR: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Vô hiệu hóa tất cả mã QR cũ của một đại lý
     */
    public function deactivateOldQrCodes($agentId)
    {
        try {
            AgentQrCode::where('agent_id', $agentId)
                ->update(['is_active' => false]);

            return [
                'success' => true,
                'message' => 'Đã vô hiệu hóa tất cả mã QR cũ của đại lý'
            ];
        } catch (\Exception $e) {
            Log::error('Error in QrCodeService@deactivateOldQrCodes: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi vô hiệu hóa mã QR cũ: ' . $e->getMessage()
            ];
        }
    }
}
