<?php

namespace App\Http\Controllers;

use App\Models\AgentBarcode;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BarcodeViewController extends Controller
{
    /**
     * Hiển thị trang tìm kiếm mã barcode
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function showSearchForm(Request $request)
    {
        // Nếu có barcode trong request, thực hiện tìm kiếm
        if ($request->has('barcode')) {
            return $this->searchBarcode($request);
        }

        // Nếu không có, chỉ hiển thị form tìm kiếm
        return view('agents.barcode.search');
    }

    /**
     * Tìm kiếm mã barcode và hiển thị kết quả
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function searchBarcode(Request $request)
    {
        $barcodeValue = $request->input('barcode');

        // Kiểm tra barcode có đúng định dạng EAN-13 không
        if (!preg_match('/^\d{13}$/', $barcodeValue)) {
            return view('agents.barcode.search', [
                'barcode_query' => $barcodeValue,
                'error_message' => 'Mã barcode không đúng định dạng. Vui lòng nhập đúng 13 chữ số.'
            ]);
        }

        try {
            // Tìm mã barcode trong cơ sở dữ liệu
            $barcode = AgentBarcode::where('barcode_value', $barcodeValue)
                ->with('agent')
                ->first();

            if (!$barcode) {
                return view('agents.barcode.search', [
                    'barcode_query' => $barcodeValue,
                    'error_message' => 'Không tìm thấy thông tin mã barcode trong hệ thống.'
                ]);
            }

            // Lấy thông tin đại lý
            $agent = $barcode->agent;
            if (!$agent) {
                return view('agents.barcode.search', [
                    'barcode_query' => $barcodeValue,
                    'error_message' => 'Không tìm thấy thông tin đại lý liên kết với mã barcode này.'
                ]);
            }

            $data = [
                'barcode_query' => $barcodeValue,
                'barcode' => $barcode,
                'agent' => $agent,
                'metadata' => $barcode->metadata ?? [],
                'code_agent' => $barcode->metadata['code_agent'] ?? $barcode->agent_code,
                'generated_time' => $barcode->metadata['generated_time'] ?? ($barcode->generated_at ? $barcode->generated_at->format('d/m/Y H:i:s') : 'Không xác định'),
            ];

            return view('agents.barcode.search', $data);

        } catch (\Exception $e) {
            Log::error('Error searching barcode: ' . $e->getMessage());

            return view('agents.barcode.search', [
                'barcode_query' => $barcodeValue,
                'error_message' => 'Có lỗi xảy ra khi tìm kiếm mã barcode.'
            ]);
        }
    }

    /**
     * Hiển thị thông tin chi tiết của mã barcode khi quét
     *
     * @param string $barcodeValue Giá trị mã barcode
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function showBarcodeDetail($barcodeValue, Request $request)
    {
        try {
            // Tìm mã barcode trong cơ sở dữ liệu
            $barcode = AgentBarcode::where('barcode_value', $barcodeValue)
                ->with('agent')
                ->first();

            if (!$barcode) {
                return view('agents.barcode.not-found', [
                    'message' => 'Không tìm thấy thông tin mã barcode.',
                    'barcode_value' => $barcodeValue
                ]);
            }

            // Kiểm tra trạng thái của mã barcode
            if ($barcode->status !== 'active') {
                return view('agents.barcode.not-found', [
                    'message' => 'Mã barcode này đã bị vô hiệu hóa.',
                    'barcode' => $barcode
                ]);
            }

            // Lấy thông tin đại lý
            $agent = $barcode->agent;
            if (!$agent || $agent->status !== 'active') {
                return view('agents.barcode.not-found', [
                    'message' => 'Đại lý liên kết với mã barcode này hiện không hoạt động.',
                    'barcode' => $barcode
                ]);
            }

            $data = [
                'barcode' => $barcode,
                'agent' => $agent,
                'metadata' => $barcode->metadata ?? [],
                'full_barcode_value' => $barcode->metadata['full_barcode_value'] ?? $barcode->barcode_value,
                'generated_time' => $barcode->metadata['generated_time'] ?? ($barcode->generated_at ? $barcode->generated_at->format('d/m/Y H:i:s') : 'Không xác định'),
                'code_agent' => $barcode->metadata['code_agent'] ?? $barcode->agent_code,
            ];

            return view('agents.barcode.detail', $data);

        } catch (\Exception $e) {
            Log::error('Error showing barcode detail: ' . $e->getMessage());

            return view('agents.barcode.not-found', [
                'message' => 'Có lỗi xảy ra khi hiển thị thông tin mã barcode.',
                'error' => $e->getMessage()
            ]);
        }
    }
}
