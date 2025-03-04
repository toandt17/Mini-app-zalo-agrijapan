<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ZaloUserController extends Controller
{
    /**
     * Lưu thông tin cơ bản của người dùng Zalo
     */
    public function saveUser(Request $request)
    {
        $request->validate([
            'zaloId' => 'required|string',
            'name' => 'nullable|string',
            'avatar' => 'nullable|string',
            'idByOA' => 'nullable|string',
            'followedOA' => 'nullable|boolean',
            'isSensitive' => 'nullable|boolean',
        ]);

        try {
            $user = User::updateOrCreate(
                ['zalo_id' => $request->zaloId],
                [
                    'name' => $request->name,
                    'avatar' => $request->avatar,
                    'id_by_oa' => $request->idByOA,
                    'followed_oa' => $request->followedOA ?? false,
                    'is_sensitive' => $request->isSensitive ?? false,
                    'last_login' => now(),
                ]
            );

            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving Zalo user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save user information'
            ], 500);
        }
    }

    /**
     * Xử lý token số điện thoại từ Zalo
     */
    public function processPhoneToken(Request $request)
    {
        // Tăng cường logging để debug vấn đề
        Log::info('Phone token request received', [
            'request_data' => $request->all(),
        ]);

        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'zaloId' => 'required|string',
                'token' => 'required|string',
                'userData' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed:', [
                    'errors' => $validator->errors()->toArray(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Lấy thông tin cấu hình từ .env
            $zaloAppId = config('services.zalo.app_id'); // Thay đổi cách lấy config
            $zaloSecretKey = config('services.zalo.secret_key'); // Thay đổi cách lấy config

            // Kiểm tra đã có đủ thông tin chưa
            if (empty($zaloAppId) || empty($zaloSecretKey)) {
                Log::error('Missing Zalo configuration', [
                    'app_id_exists' => !empty($zaloAppId),
                    'secret_key_exists' => !empty($zaloSecretKey)
                ]);

                // Fallback to env if config is not available
                $zaloAppId = env('ZALO_APP_ID');
                $zaloSecretKey = env('ZALO_SECRET_KEY');

                if (empty($zaloAppId) || empty($zaloSecretKey)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Missing Zalo configuration on server'
                    ], 500);
                }
            }

            // Log cấu hình (chỉ log 5 ký tự đầu để bảo mật)
            Log::info('Zalo configuration', [
                'app_id' => $zaloAppId,
                'secret_key_prefix' => substr($zaloSecretKey, 0, 5) . '...',
                'token_prefix' => substr($request->token, 0, 5) . '...',
            ]);

            // Gọi API Zalo
            try {
                // Điều chỉnh payload theo tài liệu mới nhất
                $payload = [
                    'app_id' => $zaloAppId,
                    'code' => $request->token
                ];

                // Sử dụng header Authorization thay vì gửi secret_key trong body
                $headers = [
                    'Authorization' => 'Bearer ' . $zaloSecretKey,
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ];

                Log::info('Sending request to Zalo API', [
                    'endpoint' => 'https://graph.zalo.me/v2.0/me/info',
                    'payload' => $payload,
                    'headers' => ['Authorization' => 'Bearer ***HIDDEN***']
                ]);

                // Thực hiện request đến Zalo API với header Authorization
                $response = Http::withHeaders($headers)
                                ->asForm()
                                ->post('https://graph.zalo.me/v2.0/me/info', $payload);

                // Log response để debug
                Log::info('Zalo API response', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                // Kiểm tra response status code
                if (!$response->successful()) {
                    Log::error('Zalo API returned non-200 status code', [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Zalo API returned status: ' . $response->status(),
                        'response_body' => $response->body()
                    ], 500);
                }

                // Parse response JSON
                $responseData = $response->json();
                Log::info('Parsed response data', ['data' => $responseData]);

                // Kiểm tra cấu trúc response theo tài liệu mới nhất
                if (isset($responseData['data']) && isset($responseData['data']['phone'])) {
                    // Lấy số điện thoại từ response
                    $phoneNumber = $responseData['data']['phone'];

                    // Định dạng số điện thoại (nếu cần)
                    if (substr($phoneNumber, 0, 2) === '84') {
                        $phoneNumber = '0' . substr($phoneNumber, 2);
                    }

                    Log::info('Successfully decoded phone number', [
                        'phone' => $phoneNumber
                    ]);
                } else {
                    // Thử lại với endpoint cũ
                    Log::info('Retrying with legacy endpoint');

                    $payload = [
                        'app_id' => $zaloAppId,
                        'code' => $request->token,
                        'secret_key' => $zaloSecretKey
                    ];

                    $response = Http::post('https://graph.zalo.me/v2.0/api/open/getphone', $payload);

                    $responseData = $response->json();
                    Log::info('Legacy API response', ['data' => $responseData]);

                    if (isset($responseData['data']) && isset($responseData['data']['number'])) {
                        $phoneNumber = $responseData['data']['number'];

                        // Định dạng số điện thoại (nếu cần)
                        if (substr($phoneNumber, 0, 2) === '84') {
                            $phoneNumber = '0' . substr($phoneNumber, 2);
                        }

                        Log::info('Successfully decoded phone number from legacy API', [
                            'phone' => $phoneNumber
                        ]);
                    } else {
                        // Xử lý lỗi từ Zalo API
                        $errorCode = $responseData['error'] ?? 'unknown';
                        $errorMessage = $responseData['message'] ?? 'Unknown error';

                        Log::error('Zalo API error or unexpected response structure', [
                            'code' => $errorCode,
                            'message' => $errorMessage,
                            'response' => $responseData
                        ]);

                        return response()->json([
                            'success' => false,
                            'message' => "Lỗi từ Zalo API: $errorMessage (Mã lỗi: $errorCode)",
                            'error_details' => $responseData
                        ], 500);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Exception calling Zalo API', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi kết nối đến Zalo API: ' . $e->getMessage()
                ], 500);
            }

            // Cập nhật thông tin người dùng vào database
            try {
                $userData = $request->userData ?? [];
                $user = User::updateOrCreate(
                    ['zalo_id' => $request->zaloId],
                    [
                        'phone' => $phoneNumber,
                        'name' => $userData['name'] ?? null,
                        'avatar' => $userData['avatar'] ?? null,
                        'id_by_oa' => $userData['idByOA'] ?? null,
                        'followed_oa' => $userData['followedOA'] ?? false,
                        'last_login' => now(),
                    ]
                );

                Log::info('User updated with phone number', [
                    'user_id' => $user->id,
                    'zalo_id' => $request->zaloId,
                    'phone' => $phoneNumber
                ]);

                // Trả về response thành công
                return response()->json([
                    'success' => true,
                    'phone' => $phoneNumber,
                    'user' => $user
                ]);
            } catch (\Exception $e) {
                Log::error('Database error when updating user', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi cập nhật cơ sở dữ liệu: ' . $e->getMessage()
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('General error processing phone token: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Lỗi xử lý token: ' . $e->getMessage()
            ], 500);
        }
    }
}
