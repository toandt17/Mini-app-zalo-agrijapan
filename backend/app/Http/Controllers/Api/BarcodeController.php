<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AgentBarcode;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    /**
     * Xác thực barcode từ giá trị barcode
     *
     * @param string $barcodeValue Giá trị barcode cần xác thực
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify($barcodeValue)
    {
        try {
            // Tìm barcode trong cơ sở dữ liệu
            $barcode = AgentBarcode::where('barcode_value', $barcodeValue)
                ->with('agent')
                ->first();

            if (!$barcode) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã barcode không hợp lệ hoặc không tồn tại',
                    'data' => null
                ], 404);
            }

            // Kiểm tra trạng thái barcode
            if ($barcode->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã barcode đã bị vô hiệu hóa',
                    'data' => [
                        'barcode' => $barcode,
                        'is_active' => false,
                        'agent' => $barcode->agent,
                    ]
                ], 403);
            }

            // Tạo thông tin trả về
            $responseData = [
                'success' => true,
                'message' => 'Xác thực barcode thành công',
                'data' => [
                    'barcode' => [
                        'id' => $barcode->id,
                        'barcode_value' => $barcode->barcode_value,
                        'agent_code' => $barcode->agent_code,
                        'order_code' => $barcode->order_code,
                        'status' => $barcode->status,
                        'generated_at' => $barcode->generated_at->format('Y-m-d H:i:s'),
                        'created_at' => $barcode->created_at->format('Y-m-d H:i:s'),
                    ],
                    'agent' => [
                        'id' => $barcode->agent->id,
                        'name' => $barcode->agent->name,
                        'address' => $barcode->agent->full_address,
                        'phone' => $barcode->agent->phone,
                        'status' => $barcode->agent->status,
                    ],
                    'is_active' => true,
                ]
            ];

            // Log the verification
            \Illuminate\Support\Facades\Log::info('Barcode verified successfully', [
                'barcode_value' => $barcodeValue,
                'agent_name' => $barcode->agent->name,
                'timestamp' => now()->format('Y-m-d H:i:s'),
            ]);

            return response()->json($responseData, 200);

        } catch (\Exception $e) {
            // Log lỗi
            \Illuminate\Support\Facades\Log::error('Error verifying barcode: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xác thực mã barcode',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
