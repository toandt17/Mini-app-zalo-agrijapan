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
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'zaloId' => 'required|string',
                'name' => 'nullable|string',
                'avatar' => 'nullable|string',
                'idByOA' => 'nullable|string',
                'followedOA' => 'nullable|boolean',
                'isSensitive' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed for saveUser', [
                    'errors' => $validator->errors()->toArray(),
                    'input' => $request->all()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            Log::info('Saving basic Zalo user info', [
                'zaloId' => $request->zaloId,
                'request_data' => $request->except('avatar') // Log tất cả trừ avatar để tránh log quá dài
            ]);

            // Tìm người dùng hiện tại nếu có
            $existingUser = User::where('zalo_id', $request->zaloId)->first();

            Log::info('User existence check result', [
                'zaloId' => $request->zaloId,
                'exists' => $existingUser ? true : false,
                'user_id' => $existingUser ? $existingUser->id : null
            ]);

            // Chuẩn bị dữ liệu cập nhật
            $userData = [
                'name' => $request->name,
                'avatar' => $request->avatar,
                'id_by_oa' => $request->idByOA,
                'followed_oa' => $request->followedOA ?? false,
                'is_sensitive' => $request->isSensitive ?? false,
                'last_login' => now(),
            ];

            // Nếu người dùng đã tồn tại, không ghi đè số điện thoại và email
            if ($existingUser) {
                try {
                    // Loại bỏ bất kỳ giá trị null từ dữ liệu cập nhật
                    $userData = array_filter($userData, function ($value) {
                        return $value !== null;
                    });

                    // Cập nhật chỉ các trường cần thiết
                    $existingUser->fill($userData);
                    $existingUser->save();

                    $user = $existingUser;
                    $isNew = false;

                    Log::info('Updated existing user basic info', [
                        'zalo_id' => $request->zaloId,
                        'user_id' => $user->id,
                        'fields_updated' => array_keys($userData)
                    ]);
                } catch (\Exception $updateError) {
                    Log::error('Error updating existing user', [
                        'zalo_id' => $request->zaloId,
                        'error' => $updateError->getMessage(),
                        'exception' => get_class($updateError),
                        'file' => $updateError->getFile(),
                        'line' => $updateError->getLine()
                    ]);

                    throw $updateError;
                }
            } else {
                try {
                    // Tạo người dùng mới
                    $user = new User();
                    $user->zalo_id = $request->zaloId;
                    $user->fill($userData);
                    $user->save();

                    $isNew = true;

                    Log::info('Created new user with basic info', [
                        'zalo_id' => $request->zaloId,
                        'user_id' => $user->id
                    ]);
                } catch (\Exception $createError) {
                    Log::error('Error creating new user', [
                        'zalo_id' => $request->zaloId,
                        'error' => $createError->getMessage(),
                        'exception' => get_class($createError),
                        'file' => $createError->getFile(),
                        'line' => $createError->getLine(),
                        'trace' => $createError->getTraceAsString()
                    ]);

                    throw $createError;
                }
            }

            return response()->json([
                'success' => true,
                'message' => $isNew ? 'Đã tạo người dùng mới' : 'Đã cập nhật thông tin người dùng',
                'user' => $user,
                'is_new' => $isNew
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving Zalo user: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['avatar'])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to save user information: ' . $e->getMessage(),
                'error_type' => get_class($e)
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

            if (empty($request->zaloId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing zaloId field'
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
                'accessToken' => $request->accessToken ? 'received' : 'missing',
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
                        'body' => substr($response->body(), 0, 200) . '...' // Log một phần để tránh quá dài
                    ]);

                    $responseData = $response->json();

                    // Kiểm tra kết quả
                    if (isset($responseData['data']) && isset($responseData['data']['number'])) {
                        $phoneNumber = $responseData['data']['number'];

                        // Định dạng số điện thoại nếu cần
                        if (substr($phoneNumber, 0, 2) === '84') {
                            $phoneNumber = '0' . substr($phoneNumber, 2);
                        }

                        // Chuẩn bị dữ liệu để lưu/cập nhật
                        $userData = [
                            'phone' => $phoneNumber,
                            'last_login' => now()
                        ];

                        // Thêm thông tin từ request->userData nếu có
                        if (!empty($request->userData)) {
                            if (!empty($request->userData['name'])) {
                                $userData['name'] = $request->userData['name'];
                            }
                            if (!empty($request->userData['avatar'])) {
                                $userData['avatar'] = $request->userData['avatar'];
                            }
                            if (isset($request->userData['idByOA'])) {
                                $userData['id_by_oa'] = $request->userData['idByOA'];
                            }
                            if (isset($request->userData['followedOA'])) {
                                $userData['followed_oa'] = $request->userData['followedOA'];
                            }
                        }

                        // Lưu tất cả thông tin vào database trong một lần
                        try {
                            $user = User::updateOrCreate(
                                ['zalo_id' => $request->zaloId],
                                $userData
                            );

                            Log::info('Saved/updated user with all information in one go', [
                                'zalo_id' => $request->zaloId,
                                'phone' => $phoneNumber,
                                'is_new' => $user->wasRecentlyCreated
                            ]);

                            // Trả về thông tin người dùng
                            return response()->json([
                                'success' => true,
                                'message' => $user->wasRecentlyCreated
                                    ? 'Đã tạo người dùng mới với số điện thoại'
                                    : 'Đã cập nhật thông tin người dùng với số điện thoại',
                                'phone' => $phoneNumber,
                                'user' => [
                                    'id' => $user->id,
                                    'name' => $user->name,
                                    'email' => $user->email,
                                    'phone' => $user->phone,
                                    'is_new' => $user->wasRecentlyCreated
                                ]
                            ]);
                        } catch (\Exception $dbErr) {
                            Log::error('Error saving user data to DB: ' . $dbErr->getMessage(), [
                                'exception' => get_class($dbErr),
                                'file' => $dbErr->getFile(),
                                'line' => $dbErr->getLine()
                            ]);

                            return response()->json([
                                'success' => false,
                                'message' => 'Lỗi khi lưu thông tin người dùng: ' . $dbErr->getMessage(),
                                'phone' => $phoneNumber // Vẫn trả về số điện thoại dù có lỗi
                            ], 500);
                        }
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
            Log::error('General error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

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

    /**
     * Cập nhật thông tin profile của người dùng
     */
    public function updateProfile(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'zaloId' => 'required|string',
                'email' => 'nullable|email',
                'phone' => 'nullable|string',
                'name' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Tìm user bằng zaloId
            $user = User::where('zalo_id', $request->zaloId)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng'
                ], 404);
            }

            // Cập nhật thông tin nếu có
            if ($request->has('email')) {
                $user->email = $request->email;
            }

            if ($request->has('name')) {
                $user->name = $request->name;
            }

            // Cập nhật số điện thoại nếu được gửi lên
            if ($request->has('phone') && !empty($request->phone)) {
                // Chuẩn hóa số điện thoại (nếu cần)
                $phone = $request->phone;
                if (str_starts_with($phone, '+84')) {
                    $phone = '0' . substr($phone, 3);
                }
                $user->phone = $phone;
            }

            // Lưu thông tin vào database
            $user->save();

            // Log thông tin cập nhật
            Log::info('Updated user profile', [
                'zalo_id' => $request->zaloId,
                'email' => $request->email,
                'phone' => $request->phone ?? 'not updated'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thông tin thành công',
                'user' => [
                    'id' => $user->id,
                    'zalo_id' => $user->zalo_id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                ]
            ]);
        } catch (\Throwable $e) {
            Log::error('Error updating user profile: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy thông tin người dùng dựa vào số điện thoại
     */
    public function getUserByPhone(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'phone' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Chuẩn hóa số điện thoại (loại bỏ +84 nếu có)
            $phone = $request->phone;
            if (str_starts_with($phone, '+84')) {
                $phone = '0' . substr($phone, 3);
            }

            // Tìm user bằng số điện thoại
            $user = User::where('phone', $phone)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng với số điện thoại này',
                    'phone' => $phone
                ], 404);
            }

            // Trả về thông tin người dùng
            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'zalo_id' => $user->zalo_id,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'email' => $user->email,
                ]
            ]);
        } catch (\Throwable $e) {
            Log::error('Error getting user by phone: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy thông tin người dùng dựa vào zaloId
     */
    public function getUserByZaloId(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'zaloId' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Log thông tin request
            Log::info('getUserByZaloId request', [
                'zaloId' => $request->zaloId
            ]);

            // Tìm user bằng zaloId
            $user = User::where('zalo_id', $request->zaloId)->first();

            if (!$user) {
                Log::warning('User not found with zaloId', ['zaloId' => $request->zaloId]);
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng với Zalo ID này',
                    'zaloId' => $request->zaloId
                ], 404);
            }

            // Log thông tin người dùng tìm thấy
            Log::info('User found with zaloId', [
                'zaloId' => $request->zaloId,
                'user_id' => $user->id
            ]);

            // Trả về thông tin người dùng
            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'zalo_id' => $user->zalo_id,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'email' => $user->email,
                    'avatar' => $user->avatar,
                    'followed_oa' => $user->followed_oa,
                    'id_by_oa' => $user->id_by_oa,
                    'is_sensitive' => $user->is_sensitive,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at
                ]
            ]);
        } catch (\Throwable $e) {
            Log::error('Error getting user by zaloId: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()
            ], 500);
        }
    }
}
