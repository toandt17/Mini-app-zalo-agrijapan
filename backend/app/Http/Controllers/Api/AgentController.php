<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AgentController extends Controller
{
    /**
     * Lấy danh sách đại lý
     */
    public function index(Request $request)
    {
        try {
            $query = Agent::with(['province', 'district', 'ward']);

            // Lọc theo tỉnh/thành phố
            if ($request->has('province_id')) {
                $query->where('province_id', $request->province_id);
            }

            // Lọc theo quận/huyện
            if ($request->has('district_id')) {
                $query->where('district_id', $request->district_id);
            }

            // Lọc theo phường/xã
            if ($request->has('ward_id')) {
                $query->where('ward_id', $request->ward_id);
            }

            // Tìm kiếm theo tên
            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            // Nếu có vị trí người dùng, tính khoảng cách và sắp xếp theo khoảng cách
            if ($request->has('lat') && $request->has('lng')) {
                $lat = $request->lat;
                $lng = $request->lng;

                $query->select('*', DB::raw("
                    (6371 * acos(cos(radians($lat)) * cos(radians(latitude)) * cos(radians(longitude) - radians($lng)) + sin(radians($lat)) * sin(radians(latitude)))) AS distance
                "))
                ->orderBy('distance');
            } else {
                $query->orderBy('name');
            }

            $agents = $query->paginate(10);

            // Thêm URL đầy đủ cho QR code (nếu có)
            $agentItems = collect($agents->items());
            $agentItems = $agentItems->map(function ($agent) {
                if ($agent->qr_code) {
                    $agent->qr_code = url($agent->qr_code);
                }
                return $agent;
            });

            // Log để debug
            Log::info('Agents count: ' . $agents->total());
            if ($agents->total() > 0 && isset($agents->items()[0])) {
                $firstAgent = $agents->items()[0];
                Log::info('First agent: ' . json_encode([
                    'id' => $firstAgent->id,
                    'name' => $firstAgent->name,
                    'province_id' => $firstAgent->province_id,
                    'province' => $firstAgent->province ? $firstAgent->province->name : null,
                    'district_id' => $firstAgent->district_id,
                    'district' => $firstAgent->district ? $firstAgent->district->name : null,
                    'ward_id' => $firstAgent->ward_id,
                    'ward' => $firstAgent->ward ? $firstAgent->ward->name : null,
                ]));
            }

            return response()->json([
                'data' => $agentItems->all(),
                'meta' => [
                    'current_page' => $agents->currentPage(),
                    'last_page' => $agents->lastPage(),
                    'per_page' => $agents->perPage(),
                    'total' => $agents->total()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in AgentController@index: ' . $e->getMessage());
            return response()->json([
                'error' => 'Đã xảy ra lỗi khi lấy danh sách đại lý',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy thông tin chi tiết đại lý
     */
    public function show(Request $request, $id)
    {
        try {
            $query = Agent::with(['province', 'district', 'ward']);

            // Nếu có vị trí người dùng, tính khoảng cách
            if ($request->has('lat') && $request->has('lng')) {
                $lat = $request->lat;
                $lng = $request->lng;

                $query->select('*', DB::raw("
                    (6371 * acos(cos(radians($lat)) * cos(radians(latitude)) * cos(radians(longitude) - radians($lng)) + sin(radians($lat)) * sin(radians(latitude)))) AS distance
                "));
            }

            $agent = $query->findOrFail($id);

            // Thêm URL đầy đủ cho QR code (nếu có)
            if ($agent->qr_code) {
                $agent->qr_code = url($agent->qr_code);
            }

            // Xử lý thông tin thời gian tạo mã QR từ tham số 'created'
            $qrCreatedTimestamp = null;
            $qrCreatedFormatted = null;
            $qrInfo = null;

            if ($request->has('created')) {
                // Sử dụng QrCodeService để lấy thông tin từ token
                $qrCodeService = app(\App\Services\QrCodeService::class);
                $result = $qrCodeService->findByToken($request->created);

                if ($result['success']) {
                    $qrCreatedTimestamp = $result['created_at_timestamp'] ?? null;
                    $qrCreatedFormatted = $result['created_at'] ?? null;
                    $qrInfo = $result;

                    // Log thông tin để debug
                    Log::info('QR Info from service:', $result);
                }
            } else if ($agent->qr_code_generated_at) {
                // Nếu không có tham số created nhưng có thông tin về thời gian tạo mã QR trong cơ sở dữ liệu
                $qrCreatedTimestamp = $agent->qr_code_generated_at;
                $qrCreatedFormatted = $agent->qr_code_generated_at->format('d/m/Y H:i:s');
            }

            // Thêm thông tin về thời gian tạo mã QR vào dữ liệu trả về
            $agent->qr_created_at_timestamp = $qrCreatedTimestamp ? $qrCreatedTimestamp : null;
            $agent->qr_created_at_formatted = $qrCreatedFormatted;
            $agent->qr_from_request = $request->has('created');

            // Thêm thông tin chi tiết về mã QR
            $agent->qr_info = [
                'created_time' => $qrCreatedFormatted,
                'is_valid' => true,
                'scan_time' => now()->format('d/m/Y H:i:s'),
                'qr_source' => $request->has('created') ?
                    ($qrInfo ? $qrInfo['source'] : 'Từ mã QR đã quét') :
                    'Từ hệ thống',
                'details' => $qrInfo ?: null,
            ];

            // Log để debug
            Log::info('Agent detail: ' . json_encode([
                'id' => $agent->id,
                'name' => $agent->name,
                'qr_created_at' => $qrCreatedFormatted,
                'has_created_param' => $request->has('created'),
                'created_param' => $request->get('created')
            ]));

            // Lấy thêm lịch sử mã QR nếu cần
            if ($request->has('include_qr_history') && $request->include_qr_history) {
                $agent->qr_codes = \App\Models\AgentQrCode::where('agent_id', $agent->id)
                    ->orderBy('generated_at', 'desc')
                    ->get();
            }

            return response()->json([
                'data' => $agent
            ]);
        } catch (\Exception $e) {
            Log::error('Error in AgentController@show: ' . $e->getMessage());
            return response()->json([
                'error' => 'Đã xảy ra lỗi khi lấy thông tin đại lý',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tìm đại lý gần nhất dựa trên tọa độ
     */
    public function findNearest(Request $request)
    {
        try {
            $request->validate([
                'lat' => 'required|numeric',
                'lng' => 'required|numeric',
            ]);

            $lat = $request->lat;
            $lng = $request->lng;

            // Sử dụng công thức Haversine để tính khoảng cách
            $agent = Agent::with(['province', 'district', 'ward'])
                ->select('*', DB::raw("
                    (6371 * acos(cos(radians($lat)) * cos(radians(latitude)) * cos(radians(longitude) - radians($lng)) + sin(radians($lat)) * sin(radians(latitude)))) AS distance
                "))
                ->orderBy('distance')
                ->first();

            if (!$agent) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy đại lý nào'
                ]);
            }

            return response()->json([
                'success' => true,
                'agent' => [
                    'id' => $agent->id,
                    'name' => $agent->name,
                    'address' => $agent->address,
                    'phone' => $agent->phone,
                    'email' => $agent->email,
                    'province' => $agent->province ? $agent->province->name : '',
                    'district' => $agent->district ? $agent->district->name : '',
                    'ward' => $agent->ward ? $agent->ward->name : '',
                    'full_address' => $agent->full_address,
                    'latitude' => $agent->latitude,
                    'longitude' => $agent->longitude,
                    'distance' => round($agent->distance, 2), // Khoảng cách tính bằng km
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in AgentController@findNearest: ' . $e->getMessage());
            return response()->json([
                'error' => 'Đã xảy ra lỗi khi tìm đại lý gần nhất',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
