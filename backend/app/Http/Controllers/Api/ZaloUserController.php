<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
        try {
            // Log request
            Log::info('Phone/profile request received', [
                'request_data' => $request->all()
            ]);

            // Validate input
            if (empty($request->token)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing token field'
                ], 400);
            }

            // Lấy secret key từ .env
            $zaloSecretKey = env('ZALO_SECRET_KEY');

            if (empty($zaloSecretKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing Zalo secret key',
                    'error_code' => 'missing_secret_key'
                ], 500);
            }

            // Kiểm tra và log access token
            Log::info('Access token received', [
                'accessToken' => $request->accessToken,
                'accessToken_length' => $request->accessToken ? strlen($request->accessToken) : 0
            ]);

            // Nếu có access token từ client
            if (!empty($request->accessToken)) {
                try {
                    // Gọi API để lấy số điện thoại từ token
                    $response = Http::withHeaders([
                        'access_token' => $request->accessToken,
                        'code' => $request->token,
                        'secret_key' => $zaloSecretKey
                    ])->get('https://graph.zalo.me/v2.0/me/info');

                    Log::info('Zalo phone info response', [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);

                    $responseData = $response->json();

                    // Kiểm tra kết quả
                    if (isset($responseData['data']) && isset($responseData['data']['number'])) {
                        $phoneNumber = $responseData['data']['number'];

                        // Định dạng số điện thoại nếu cần
                        if (substr($phoneNumber, 0, 2) === '84') {
                            $phoneNumber = '0' . substr($phoneNumber, 2);
                        }

                        // Lưu vào database
                        try {
                            $user = User::where('zalo_id', $request->zaloId)->first();
                            if ($user) {
                                $user->phone = $phoneNumber;
                                $user->save();
                                Log::info('Updated phone number for user', [
                                    'zalo_id' => $request->zaloId,
                                    'phone' => $phoneNumber
                                ]);
                            }
                        } catch (\Exception $dbErr) {
                            Log::error('Error saving phone to DB: ' . $dbErr->getMessage());
                        }

                        return response()->json([
                            'success' => true,
                            'message' => 'Lấy số điện thoại thành công',
                            'phone' => $phoneNumber
                        ]);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Không thể lấy số điện thoại từ token',
                            'response' => $responseData
                        ]);
                    }
                } catch (\Exception $apiErr) {
                    Log::error('Error calling Zalo Graph API: ' . $apiErr->getMessage());

                    return response()->json([
                        'success' => false,
                        'message' => 'Lỗi khi gọi Zalo Graph API: ' . $apiErr->getMessage()
                    ]);
                }
            } else {
                // Thử phương pháp thay thế nếu không có access token
                return response()->json([
                    'success' => false,
                    'message' => 'Thiếu access token từ client',
                    'debug_info' => 'Cần cung cấp access token để lấy số điện thoại từ token',
                    'received_data' => $request->all() // Thêm dữ liệu nhận được để debug
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('General error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Lỗi xử lý yêu cầu: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Process location token from Zalo API
     */
    public function processLocationToken(Request $request)
    {
        try {
            // Debug: Log the incoming request
            Log::info('processLocationToken called with data:', [
                'request' => $request->all(),
                'headers' => $request->header(),
                'method' => $request->method(),
                'url' => $request->url(),
                'path' => $request->path()
            ]);

            // Validate input
            if (empty($request->token)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing token field'
                ], 400);
            }

            // Lấy secret key từ .env
            $zaloSecretKey = env('ZALO_SECRET_KEY');

            if (empty($zaloSecretKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing Zalo secret key',
                    'error_code' => 'missing_secret_key'
                ], 500);
            }

            // Kiểm tra và log access token
            Log::info('Access token received for location', [
                'accessToken' => $request->accessToken,
                'accessToken_length' => $request->accessToken ? strlen($request->accessToken) : 0
            ]);

            // Nếu có access token từ client
            if (!empty($request->accessToken)) {
                try {
                    // Gọi API để lấy vị trí từ token
                    $response = Http::withHeaders([
                        'access_token' => $request->accessToken,
                        'code' => $request->token,
                        'secret_key' => $zaloSecretKey
                    ])->get('https://graph.zalo.me/v2.0/me/info');

                    // Log đầy đủ thông tin request và response
                    Log::info('Zalo location API request details', [
                        'headers' => [
                            'access_token' => substr($request->accessToken, 0, 10) . '...',
                            'code' => substr($request->token, 0, 10) . '...',
                            'secret_key' => substr($zaloSecretKey, 0, 5) . '...'
                        ],
                        'url' => 'https://graph.zalo.me/v2.0/me/info'
                    ]);

                    Log::info('Zalo location API response details', [
                        'status' => $response->status(),
                        'headers' => $response->headers(),
                        'body' => $response->body()
                    ]);

                    $responseData = $response->json();

                    // Kiểm tra kết quả và trích xuất dữ liệu vị trí
                    // Có thể có nhiều định dạng khác nhau, nên kiểm tra kỹ

                    // Tìm dữ liệu vị trí
                    $locationData = null;

                    // Kiểm tra định dạng chuẩn như tài liệu
                    if (isset($responseData['data']) &&
                        isset($responseData['data']['latitude']) &&
                        isset($responseData['data']['longitude'])) {
                        $locationData = $responseData['data'];
                        Log::info('Location data found in standard format', ['data' => $locationData]);
                    }
                    // Nếu dữ liệu ở mức root
                    else if (isset($responseData['latitude']) && isset($responseData['longitude'])) {
                        $locationData = $responseData;
                        Log::info('Location data found at root level', ['data' => $locationData]);
                    }

                    if ($locationData) {
                        $latitude = $locationData['latitude'];
                        $longitude = $locationData['longitude'];
                        $provider = $locationData['provider'] ?? 'unknown';
                        $timestamp = $locationData['timestamp'] ?? now()->timestamp;

                        // Lưu thông tin người dùng nếu có zaloId
                        if (!empty($request->zaloId)) {
                            try {
                                $user = User::where('zalo_id', $request->zaloId)->first();
                                if ($user) {
                                    $user->latitude = $latitude;
                                    $user->longitude = $longitude;
                                    $user->location_provider = $provider;
                                    $user->location_timestamp = $timestamp;
                                    $user->location_updated_at = now();
                                    $user->save();

                                    Log::info('Updated location for user', [
                                        'zalo_id' => $request->zaloId,
                                        'latitude' => $latitude,
                                        'longitude' => $longitude
                                    ]);
                                }
                            } catch (\Exception $dbErr) {
                                Log::error('Error saving location to DB: ' . $dbErr->getMessage());
                            }
                        }

                        return response()->json([
                            'success' => true,
                            'message' => 'Lấy vị trí thành công',
                            'location' => [
                                'latitude' => (float)$latitude,
                                'longitude' => (float)$longitude,
                                'provider' => $provider,
                                'timestamp' => $timestamp
                            ]
                        ]);
                    } else {
                        Log::error('Location data not found in response', ['response' => $responseData]);
                        return response()->json([
                            'success' => false,
                            'message' => 'Không thể tìm thấy dữ liệu vị trí trong phản hồi',
                            'response' => $responseData
                        ]);
                    }
                } catch (\Exception $apiErr) {
                    Log::error('Error calling Zalo API for location: ' . $apiErr->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => 'Lỗi khi gọi Zalo API: ' . $apiErr->getMessage()
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Thiếu access token từ client',
                    'received_data' => $request->all()
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Unexpected error in processLocationToken: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi không mong muốn: ' . $e->getMessage()
            ]);
        }
    }
}
