<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\AgentQrCode;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QrCodeController extends Controller
{
    protected $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    /**
     * Lấy thông tin mã QR
     */
    public function getQrInfo(Request $request)
    {
        $token = $request->get('token');
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token không hợp lệ'
            ]);
        }

        // Tìm mã QR theo token
        $qrCode = AgentQrCode::where('qr_token', $token)->first();
        if (!$qrCode) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy mã QR'
            ]);
        }

        // Cập nhật số lần quét
        $qrCode->scan_count = ($qrCode->scan_count ?? 0) + 1;
        $qrCode->last_scanned_at = now();
        $qrCode->save();

        // Lấy thông tin đại lý
        $agent = Agent::find($qrCode->agent_id);
        if (!$agent) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đại lý'
            ]);
        }

        return response()->json([
            'success' => true,
            'agent' => [
                'id' => $agent->id,
                'name' => $agent->name,
                'phone' => $agent->phone,
                'address' => $agent->full_address,
            ],
            'qr_code' => [
                'generated_at' => $qrCode->generated_at,
                'scan_count' => $qrCode->scan_count,
                'last_scanned_at' => $qrCode->last_scanned_at,
            ]
        ]);
    }

    /**
     * Lấy lịch sử quét mã QR
     */
    public function getQrHistory($agentId)
    {
        $qrCodes = AgentQrCode::where('agent_id', $agentId)
            ->orderBy('generated_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'qr_codes' => $qrCodes
        ]);
    }

    /**
     * Xử lý khi quét mã QR cho API backend
     * Điều hướng người dùng đến Zalo Mini App nếu có thể
     */
    public function showAgentDetail($id, Request $request)
    {
        try {
            // Lấy thông tin đại lý
            $agent = Agent::with(['province', 'district', 'ward'])->findOrFail($id);

            // Lấy token từ request nếu có
            $token = $request->get('created');

            // Thông tin thêm để hiển thị
            $data = [
                'agent' => $agent,
                'timestamp' => now()->format('d/m/Y H:i:s'),
            ];

            // Nếu có token, lấy thông tin mã QR
            if ($token) {
                $qrCode = AgentQrCode::where('qr_token', $token)->first();
                if ($qrCode) {
                    // Cập nhật số lần quét
                    $qrCode->scan_count = ($qrCode->scan_count ?? 0) + 1;
                    $qrCode->last_scanned_at = now();
                    $qrCode->save();

                    $data['qr_code'] = $qrCode;
                    $data['created_time'] = $qrCode->generated_at ? $qrCode->generated_at->format('d/m/Y H:i:s') : null;

                    // Lấy URL Zalo Mini App từ metadata nếu có
                    $zaloMiniAppUrl = $qrCode->metadata['zalo_mini_app_url'] ?? null;
                    $data['zalo_url'] = $zaloMiniAppUrl;
                }
            }

            // Kiểm tra xem đang chạy trên Zalo hay không
            $userAgent = $request->header('User-Agent');
            $isZaloApp = stripos($userAgent, 'Zalo') !== false;
            $data['is_zalo_app'] = $isZaloApp;

            // Log để debug
            Log::info('QR Code scan request:', [
                'id' => $id,
                'token' => $token,
                'user_agent' => $userAgent,
                'is_zalo_app' => $isZaloApp
            ]);

            // Nếu là request API, trả về JSON
            if ($request->wantsJson() || $request->ajax() || $request->has('api')) {
                return response()->json([
                    'success' => true,
                    'data' => $agent,
                    'qr_info' => [
                        'created_time' => $data['created_time'] ?? null,
                        'scan_time' => $data['timestamp'],
                        'is_zalo_app' => $isZaloApp
                    ]
                ]);
            }

            // Nếu không, hiển thị trang web với script chuyển hướng
            return view('qr_redirect', $data);

        } catch (\Exception $e) {
            Log::error('Error in QrCodeController@showAgentDetail: ' . $e->getMessage());

            if ($request->wantsJson() || $request->ajax() || $request->has('api')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
                ], 500);
            }

            return view('errors.agent_not_found', [
                'error' => $e->getMessage(),
                'agent_id' => $id
            ]);
        }
    }

    /**
     * Tìm thông tin từ token QR code
     */
    public function findByToken($token)
    {
        try {
            // Tìm mã QR theo token
            $qrCode = AgentQrCode::where('qr_token', $token)->first();
            if (!$qrCode) {
                return [
                    'success' => false,
                    'message' => 'Không tìm thấy mã QR'
                ];
            }

            return [
                'success' => true,
                'source' => 'database',
                'qr_code' => $qrCode,
                'agent_id' => $qrCode->agent_id,
                'created_at' => $qrCode->generated_at ? $qrCode->generated_at->format('Y-m-d H:i:s') : null,
                'created_at_timestamp' => $qrCode->generated_at ? $qrCode->generated_at->timestamp : null,
                'is_active' => (bool) $qrCode->is_active
            ];
        } catch (\Exception $e) {
            Log::error('Error in QrCodeService@findByToken: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Hiển thị thông tin đại lý trực tiếp từ quét mã QR
     * Hiển thị giao diện tương tự detail.blade.php
     */
    public function viewAgentFromQr($id, Request $request)
    {
        try {
            // Lấy thông tin đại lý với các quan hệ
            $agent = Agent::with(['province', 'district', 'ward'])->findOrFail($id);

            // Xử lý thông tin thời gian tạo mã QR từ tham số 'created'
            $qrCreatedFormatted = null;
            $qrSource = null;
            $qrIsActive = true;

            if ($request->has('created')) {
                // Sử dụng QrCodeService để lấy thông tin từ token
                $qrCodeService = app(\App\Services\QrCodeService::class);
                $result = $qrCodeService->findByToken($request->created);

                if ($result['success']) {
                    $qrCreatedFormatted = $result['created_at'] ?? null;
                    $qrSource = $result['source'] ?? 'unknown';
                    $qrIsActive = $result['is_active'] ?? true;

                    // Log thông tin để debug
                    Log::info('QR Info from service for view:', $result);
                }
            } else if ($agent->qr_code_generated_at) {
                // Nếu không có tham số created nhưng có thông tin về thời gian tạo mã QR
                $qrCreatedFormatted = $agent->qr_code_generated_at->format('d/m/Y H:i:s');
            }

            // Trả về view với dữ liệu
            return view('agents.qr.detail', [
                'agent' => $agent,
                'qrCreatedFormatted' => $qrCreatedFormatted,
                'qrSource' => $qrSource,
                'qrIsActive' => $qrIsActive
            ]);

        } catch (\Exception $e) {
            Log::error('Error in QrCodeController@viewAgentFromQr: ' . $e->getMessage());

            return view('errors.agent_not_found', [
                'error' => $e->getMessage(),
                'agent_id' => $id
            ]);
        }
    }
}
