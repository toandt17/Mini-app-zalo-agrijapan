<?php

namespace App\Http\Controllers;

use App\Models\AgentQrCode;
use App\Models\Agent;
use App\Models\QrCodeScan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class QrCodeScanController extends Controller
{
    /**
     * Xử lý khi mã QR được quét - API endpoint
     */
    public function scan(Request $request, $token)
    {
        try {
            // Tìm mã QR theo token
            $qrCode = AgentQrCode::where('qr_token', $token)->first();

            if (!$qrCode) {
                return response()->json(['error' => 'Mã QR không hợp lệ'], 404);
            }

            if (!$qrCode->is_active) {
                return response()->json(['error' => 'Mã QR này đã bị vô hiệu hóa'], 403);
            }

            // Lấy thông tin IP và User Agent
            $ipAddress = $request->ip();
            $userAgent = $request->header('User-Agent');

            // Ghi nhận lượt quét mới
            $scan = $qrCode->incrementScanCount($ipAddress, $userAgent);

            // Lấy thông tin đại lý
            $agent = Agent::with(['province', 'district', 'ward'])->find($qrCode->agent_id);

            if (!$agent) {
                return response()->json(['error' => 'Không tìm thấy thông tin đại lý'], 404);
            }

            // Trả về thông tin đại lý và số lượt quét
            return response()->json([
                'success' => true,
                'message' => 'Quét mã QR thành công',
                'agent' => $agent,
                'qr_info' => [
                    'total_scans' => $qrCode->scan_count,
                    'unique_scans' => $qrCode->unique_scan_count,
                    'generated_at' => $qrCode->created_at,
                    'last_scanned_at' => $qrCode->last_scanned_at
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing QR scan: ' . $e->getMessage());
            return response()->json(['error' => 'Đã xảy ra lỗi khi xử lý mã QR'], 500);
        }
    }

    /**
     * Hiển thị trang chi tiết đại lý khi quét mã QR
     */
    public function viewAgent($id, Request $request)
    {
        try {
            // Thêm full user agent vào log để debug
            $fullUserAgent = $request->header('User-Agent');
            Log::info('Full User Agent: ' . $fullUserAgent);

            Log::info('QR scan request received', [
                'agent_id' => $id,
                'query' => $request->all(),
                'ip' => $request->ip(),
                'user_agent_summary' => substr($fullUserAgent, 0, 100) . '...'
            ]);

            $encodedTimestamp = $request->query('created');

            // Tìm đại lý
            $agent = Agent::with(['province', 'district', 'ward'])->find($id);

            if (!$agent) {
                Log::error('Agent not found', ['id' => $id]);
                return view('errors.agent-not-found', ['message' => 'Không tìm thấy đại lý với ID: ' . $id]);
            }

            // Biến lưu trữ thông tin về QR
            $qrCode = null;
            $qrCreatedFormatted = null;
            $qrSource = null;
            $qrIsActive = true;
            $scan_count = 0;
            $unique_scan_count = 0;

            // Tìm mã QR tương ứng với timestamp
            if ($encodedTimestamp) {
                $qrCode = AgentQrCode::where('agent_id', $id)
                    ->where('qr_token', $encodedTimestamp)
                    ->first();

                if ($qrCode) {
                    $scan_count = $qrCode->scan_count ?? 0;
                    $unique_scan_count = $qrCode->unique_scan_count ?? 0;

                    $qrSource = 'database';
                    $qrIsActive = $qrCode->is_active;
                    $qrCreatedFormatted = Carbon::parse($qrCode->created_at)->format('d/m/Y H:i:s');

                    if ($qrCode->is_active) {
                        // Ghi nhận lượt quét mới
                        $ipAddress = $request->ip();
                        $userAgent = $request->header('User-Agent');

                        // Tạo device fingerprint
                        $deviceFingerprint = QrCodeScan::generateDeviceFingerprint($userAgent, $ipAddress);

                        Log::info('Recording QR scan', [
                            'qr_id' => $qrCode->id,
                            'ip' => $ipAddress,
                            'fingerprint' => $deviceFingerprint
                        ]);

                        // Cập nhật thời gian quét gần nhất
                        $qrCode->last_scanned_at = now();

                        // Tăng số lượt quét
                        $qrCode->scan_count = ($qrCode->scan_count ?? 0) + 1;
                        $scan_count = $qrCode->scan_count; // Cập nhật biến để hiển thị

                        // Kiểm tra xem thiết bị này đã quét trước đó chưa - sử dụng fingerprint
                        $existingScan = QrCodeScan::where('qr_code_id', $qrCode->id)
                            ->where('device_fingerprint', $deviceFingerprint)
                            ->exists();

                        // Nếu là thiết bị mới, tăng số lượng người quét khác nhau
                        if (!$existingScan) {
                            $qrCode->unique_scan_count = ($qrCode->unique_scan_count ?? 0) + 1;
                            $unique_scan_count = $qrCode->unique_scan_count; // Cập nhật biến để hiển thị
                        }

                        $qrCode->save();

                        // Tạo bản ghi lần quét mới
                        QrCodeScan::create([
                            'qr_code_id' => $qrCode->id,
                            'agent_id' => $qrCode->agent_id,
                            'ip_address' => $ipAddress,
                            'user_agent' => $userAgent,
                            'device_type' => QrCodeScan::detectDeviceType($userAgent),
                            'browser' => QrCodeScan::detectBrowser($userAgent),
                            'device_fingerprint' => $deviceFingerprint
                        ]);
                    }
                } else {
                    // Nếu không tìm thấy trong DB, có thể là mã QR cũ
                    $qrSource = 'legacy';
                    try {
                        // Giải mã timestamp để hiển thị thời gian tạo
                        $timestamp = base64_decode($encodedTimestamp);
                        if (is_numeric($timestamp)) {
                            $qrCreatedFormatted = Carbon::createFromTimestamp($timestamp)->format('d/m/Y H:i:s');
                        }
                    } catch (\Exception $e) {
                        Log::error('Error decoding QR timestamp: ' . $e->getMessage());
                    }
                }
            }

            // Tiếp tục với code trả về view - giữ nguyên
            Log::info('Rendering agent view', [
                'agent_id' => $agent->id,
                'qr_found' => $qrCode ? 'yes' : 'no',
                'scan_count' => $scan_count,
                'unique_count' => $unique_scan_count
            ]);

            // Trả về view hiển thị thông tin đại lý
            return view('agents.qr.detail', [
                'agent' => $agent,
                'qrCode' => $qrCode,
                'qrCreatedFormatted' => $qrCreatedFormatted,
                'qrSource' => $qrSource,
                'qrIsActive' => $qrIsActive,
                'scan_count' => $scan_count,
                'unique_scan_count' => $unique_scan_count
            ]);

        } catch (\Exception $e) {
            Log::error('Error viewing agent: ' . $e->getMessage(), [
                'agent_id' => $id,
                'exception' => $e
            ]);

            return view('errors.general-error', [
                'message' => 'Đã xảy ra lỗi khi xử lý yêu cầu: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Phát hiện loại thiết bị từ user agent
     */
    private function detectDeviceType($userAgent)
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
    private function detectBrowser($userAgent)
    {
        if (preg_match('/MSIE/i', $userAgent) || preg_match('/Trident/i', $userAgent)) {
            return 'Internet Explorer';
        } elseif (preg_match('/Firefox/i', $userAgent)) {
            return 'Firefox';
        } elseif (preg_match('/Chrome/i', $userAgent)) {
            return 'Chrome';
        } elseif (preg_match('/Safari/i', $userAgent)) {
            return 'Safari';
        } elseif (preg_match('/Opera/i', $userAgent)) {
            return 'Opera';
        } elseif (preg_match('/Netscape/i', $userAgent)) {
            return 'Netscape';
        } else {
            return 'Không xác định';
        }
    }
}
