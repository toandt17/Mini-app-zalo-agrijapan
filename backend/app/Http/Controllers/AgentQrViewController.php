<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\AgentQrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AgentQrViewController extends Controller
{
    /**
     * Hiển thị thông tin chi tiết đại lý khi quét QR code
     *
     * @param int $id ID của đại lý
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function showQrDetail($id, Request $request)
    {
        try {
            // Lấy thông tin đại lý
            $agent = Agent::with(['province', 'district', 'ward'])->find($id);

            if (!$agent) {
                return view('agents.qr.not-found', [
                    'message' => 'Không tìm thấy thông tin đại lý.'
                ]);
            }

            // Kiểm tra trạng thái của đại lý
            if ($agent->status !== 'active') {
                return view('agents.qr.not-found', [
                    'message' => 'Đại lý này hiện không hoạt động.',
                    'agent' => $agent
                ]);
            }

            // Đảm bảo đại lý có mã quy ước
            if (empty($agent->code_agent)) {
                $agent->generateCodeAgentIfNotExists();
            }

            $data = [
                'agent' => $agent,
                'agent_code' => $agent->code_agent,
            ];

            // Xử lý thông tin timestamp từ QR code (nếu có)
            $encodedTimestamp = $request->query('created');
            if ($encodedTimestamp) {
                try {
                    // Giải mã Base64 để lấy timestamp
                    $timestamp = base64_decode($encodedTimestamp);

                    // Kiểm tra xem timestamp có hợp lệ không
                    if (is_numeric($timestamp)) {
                        $qrCreated = \Carbon\Carbon::createFromTimestamp($timestamp);
                        $data['qrCreatedFormatted'] = $qrCreated->format('d/m/Y H:i:s');

                        // Kiểm tra xem mã QR có trong cơ sở dữ liệu không
                        $qrCode = AgentQrCode::where('agent_id', $id)
                            ->where('qr_token', $encodedTimestamp)
                            ->first();

                        if ($qrCode) {
                            $data['qrSource'] = 'database';
                            $data['qrIsActive'] = $qrCode->is_active;
                        } else {
                            $data['qrSource'] = 'legacy';
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Error processing QR timestamp: ' . $e->getMessage());
                }
            }

            return view('agents.qr.detail', $data);

        } catch (\Exception $e) {
            Log::error('Error showing agent QR detail: ' . $e->getMessage());

            return view('agents.qr.not-found', [
                'message' => 'Có lỗi xảy ra khi hiển thị thông tin đại lý.',
                'error' => $e->getMessage()
            ]);
        }
    }
}
