<?php

namespace App\Repositories\Agent;

use App\Models\Agent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AgentRepository implements AgentInterface
{
    protected $agent;

    // URL gốc của ứng dụng frontend
    protected $frontendUrl = 'https://thiepcuoitoandao.id.vn'; // Thay đổi URL thành domain thực tế của bạn

    public function __construct(Agent $agent)
    {
        $this->agent = $agent;
    }

    /**
     * Lấy tất cả đại lý
     */
    public function getAllAgents()
    {
        return $this->agent->with(['province', 'district', 'ward'])->orderBy('created_at', 'desc')->get();
    }

    /**
     * Tìm đại lý theo ID
     */
    public function findById($id)
    {
        return $this->agent->with(['province', 'district', 'ward'])->find($id);
    }

    /**
     * Tạo mới đại lý
     */
    public function create(array $data)
    {
        $agent = $this->agent->create($data);
        $this->generateQrCode($agent->id);
        return $agent;
    }

    /**
     * Cập nhật thông tin đại lý
     */
    public function update($id, array $data)
    {
        $agent = $this->agent->find($id);
        if ($agent) {
            $agent->update($data);
            return $agent;
        }
        return false;
    }

    /**
     * Xóa đại lý
     */
    public function delete($id)
    {
        $agent = $this->agent->find($id);
        if ($agent) {
            // Xóa file QR code nếu có
            if ($agent->qr_code) {
                $qrPath = str_replace('/storage/', 'public/', $agent->qr_code);
                Storage::delete($qrPath);
            }
            return $agent->delete();
        }
        return false;
    }

    /**
     * Lấy các đại lý theo vị trí
     */
    public function getAgentsByLocation($provinceId, $districtId = null, $wardId = null)
    {
        $query = $this->agent->where('province_id', $provinceId);

        if ($districtId) {
            $query->where('district_id', $districtId);
        }

        if ($wardId) {
            $query->where('ward_id', $wardId);
        }

        return $query->with(['province', 'district', 'ward'])->get();
    }

    /**
     * Tạo mã QR cho đại lý
     */
    public function generateQrCode($id)
    {
        try {
            $agent = $this->findById($id);

            if (!$agent) {
                \Illuminate\Support\Facades\Log::error('Agent not found for QR generation: ' . $id);
                return false;
            }

            // Tạo thư mục lưu trữ mã QR nếu chưa có
            $storagePath = storage_path('app/public/qrcodes');
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }

            // Lấy thời gian hiện tại để làm timestamp
            $now = now();
            $timestamp = $now->timestamp;

            // Mã hóa timestamp thành base64 để rút ngắn độ dài
            $encodedTimestamp = base64_encode($timestamp);

            // ID ứng dụng Zalo Mini App (thay thế bằng ID thực tế của bạn)
            $zaloAppId = env('ZALO_MINI_APP_ID', '123456789'); // Lấy từ biến môi trường hoặc sử dụng giá trị mặc định

            // Tạo URL web trực tiếp đến backend để hiển thị giao diện detail.blade.php
            $directViewUrl = url("/agent/qr/{$agent->id}?created={$encodedTimestamp}");

            // URL cũ - để tương thích với các mã QR cũ
            $webUrl = 'https://thiepcuoitoandao.id.vn/agents/' . $agent->id . '?created=' . $encodedTimestamp;

            // Tạo URL cho Zalo Mini App (obsolete - chúng ta sẽ dùng direct view URL)
            $zaloMiniAppUrl = 'https://zalo.me/s/45775019718875745/?page=/agents/' . $agent->id . '?created=' . $encodedTimestamp;

            // URL cuối cùng sẽ là direct view URL thay vì Zalo URL
            $qrUrl = $directViewUrl;

            // Log URL để debug
            \Illuminate\Support\Facades\Log::info('Generated QR URLs: Direct=' . $directViewUrl . ', Web=' . $webUrl);

            // Tạo mã QR với SVG format
            $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                ->size(300)
                ->errorCorrection('H')
                ->generate($qrUrl);

            // Tạo tên file duy nhất với timestamp để đảm bảo mỗi mã QR là duy nhất
            $filename = 'agent_' . $agent->id . '_' . $timestamp . '.svg';
            $qrCodePath = 'qrcodes/' . $filename;

            // Xóa file QR cũ nếu có
            if ($agent->qr_code) {
                $oldPath = str_replace('storage/', 'public/', $agent->qr_code);
                if (Storage::exists($oldPath)) {
                    Storage::delete($oldPath);
                }
            }

            // Lưu mã QR vào storage
            Storage::disk('public')->put($qrCodePath, $qrCode);

            // Đường dẫn hiển thị (URL công khai)
            $publicPath = 'storage/' . $qrCodePath;

            // Lưu thông tin mã QR vào bảng agent_qr_codes
            $agentQrCode = new \App\Models\AgentQrCode([
                'agent_id' => $agent->id,
                'qr_code_path' => $publicPath,
                'qr_token' => $encodedTimestamp,
                'generated_at' => $now,
                'url' => $directViewUrl,
                'metadata' => [
                    'agent_name' => $agent->name,
                    'regeneration_count' => ($agent->qr_regeneration_count ?? 0) + 1,
                    'web_url' => $webUrl,
                    'zalo_mini_app_url' => $zaloMiniAppUrl,
                    'direct_view_url' => $directViewUrl,
                ],
                'is_active' => true,
            ]);
            $agentQrCode->save();

            // Cập nhật đường dẫn mã QR và thông tin trong bảng agents
            $agent->qr_code = $publicPath;
            $agent->qr_regeneration_count = ($agent->qr_regeneration_count ?? 0) + 1;
            $agent->qr_code_generated_at = $now;
            $agent->save();

            // Tạo barcode cho đại lý khi tạo mã QR mới
            $this->generateBarcode($agent->id);

            \Illuminate\Support\Facades\Log::info('QR Code generated successfully for agent: ' . $agent->id . ' at ' . $now->format('Y-m-d H:i:s'));
            return true;

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error generating QR code: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Tạo mã EAN-13 theo quy tắc tiêu chuẩn
     *
     * @param string $digits Chuỗi 12 số ban đầu (không bao gồm số kiểm tra)
     * @return string Mã EAN-13 đầy đủ (12 số + 1 số kiểm tra)
     */
    protected function generateEAN13Code($digits)
    {
        // Đảm bảo chuỗi đầu vào chỉ có 12 ký tự số
        $digits = preg_replace('/[^0-9]/', '', $digits);
        $digits = str_pad($digits, 12, '0', STR_PAD_LEFT);
        $digits = substr($digits, 0, 12);

        // Tính toán số kiểm tra theo thuật toán EAN-13
        $sum_odd = 0;
        $sum_even = 0;

        // 1. Lấy tổng các số ở vị trí lẻ (1,3,5,7,9,11)
        for ($i = 0; $i < 12; $i += 2) {
            $sum_odd += (int)$digits[$i];
        }

        // 2. Lấy tổng các số ở vị trí chẵn (2,4,6,8,10,12) và nhân với 3
        for ($i = 1; $i < 12; $i += 2) {
            $sum_even += (int)$digits[$i];
        }
        $sum_even *= 3;

        // 3. Lấy tổng của A và B
        $total = $sum_odd + $sum_even;

        // 4. Tính số dư khi chia cho 10
        $remainder = $total % 10;

        // 5. Tính số kiểm tra (nếu dư = 0 thì check = 0, ngược lại check = 10 - dư)
        $check_digit = ($remainder == 0) ? 0 : (10 - $remainder);

        // 6. Trả về mã EAN-13 đầy đủ
        return $digits . $check_digit;
    }

    /**
     * Tạo mã barcode cho đại lý
     *
     * @param int $agentId ID của đại lý
     * @param string|null $orderCode Mã đơn hàng (nếu có)
     * @return bool
     */
    public function generateBarcode($agentId, $orderCode = null)
    {
        try {
            $agent = $this->findById($agentId);

            if (!$agent) {
                \Illuminate\Support\Facades\Log::error('Agent not found for barcode generation: ' . $agentId);
                return false;
            }

            // Tạo thư mục lưu trữ mã barcode nếu chưa có
            $storagePath = storage_path('app/public/barcodes');
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }

            // Lấy thời gian hiện tại
            $now = now();

            // Đảm bảo có mã quy ước đại lý
            $agentCode = $agent->code_agent;
            if (empty($agentCode)) {
                // Tạo mã quy ước đại lý nếu chưa có và lưu vào DB
                $agentCode = $agent->generateCodeAgentIfNotExists();
            }

            // Loại bỏ 'AG' từ mã đại lý và lấy số ID
            $agentId = intval(preg_replace('/[^0-9]/', '', $agentCode));

            // Tạo chuỗi 12 số cố định dựa trên mã đại lý và mã đơn hàng (nếu có)
            // Format: NNNNNNDDDDDD
            // N: Mã cố định cho hệ thống (2 số đầu tiên)
            // A: Mã đại lý (5 số - từ agent ID, thêm số 0 vào đầu nếu cần)
            // O: Mã đơn hàng hoặc số thứ tự (5 số - từ order code hoặc timestamp)

            // Bắt đầu với 2 số cố định của hệ thống (01 = AgriJapan)
            $ean12 = '01';

            // Thêm mã đại lý (5 số)
            $ean12 .= str_pad($agentId, 5, '0', STR_PAD_LEFT);

            // Thêm mã đơn hàng hoặc thời gian
            if ($orderCode) {
                // Nếu có mã đơn hàng, lấy 5 số cuối
                $orderDigits = preg_replace('/[^0-9]/', '', $orderCode);
                $ean12 .= str_pad(substr($orderDigits, -5), 5, '0', STR_PAD_LEFT);
            } else {
                // Nếu không có mã đơn hàng, lấy 5 số từ timestamp
                $ean12 .= substr(str_pad($now->timestamp % 100000, 5, '0', STR_PAD_LEFT), 0, 5);
            }

            // Thêm số kiểm tra để tạo mã EAN-13 đầy đủ
            $ean13 = $this->generateEAN13Code($ean12);

            // Lưu lại giá trị barcode đầy đủ để tham chiếu
            $fullBarcodeValue = $agentCode . ($orderCode ? '-' . $orderCode : '') . '-' . $now->format('YmdHis');

            // Tạo barcode image với PHP Barcode Generator
            $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
            $barcodeImage = $generator->getBarcode($ean13, $generator::TYPE_EAN_13);

            // Tạo tên file duy nhất với timestamp
            $filename = 'barcode_' . $agent->id . '_' . $now->timestamp . '.png';
            $barcodePath = 'barcodes/' . $filename;

            // Lưu barcode vào storage
            Storage::disk('public')->put($barcodePath, $barcodeImage);

            // Đường dẫn hiển thị (URL công khai)
            $publicPath = 'storage/' . $barcodePath;

            // Lưu thông tin barcode vào bảng agent_barcodes
            $agentBarcode = new \App\Models\AgentBarcode([
                'agent_id' => $agent->id,
                'barcode_value' => $ean13,
                'barcode_image' => $publicPath,
                'agent_code' => $agentCode,
                'order_code' => $orderCode,
                'status' => 'active',
                'generated_at' => $now,
                'metadata' => [
                    'agent_name' => $agent->name,
                    'generated_time' => $now->format('Y-m-d H:i:s'),
                    'full_barcode_value' => $fullBarcodeValue,
                    'ean12' => $ean12,
                    'ean13' => $ean13,
                    'has_order' => !empty($orderCode),
                    'code_agent' => $agentCode,
                    'timestamp' => $now->timestamp,
                ],
            ]);
            $agentBarcode->save();

            \Illuminate\Support\Facades\Log::info('EAN-13 Barcode generated successfully for agent: ' . $agent->id . ' with value: ' . $ean13);
            return true;

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error generating barcode: ' . $e->getMessage());
            return false;
        }
    }
}
